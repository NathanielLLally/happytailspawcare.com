<?php

namespace BitApps\Assist\HTTP\Controllers;

use BitApps\Assist\Config;
use AllowDynamicProperties;
use BitApps\Assist\Model\Analytics;
use BitApps\Assist\Deps\BitApps\WPKit\Http\Response;
use BitApps\Assist\Deps\BitApps\WPKit\Http\Request\Request;

#[AllowDynamicProperties]
final class AnalyticsController
{
    public function isAnalyticsActive()
    {
        $analyticsOption = Config::getOption('analytics_activate');

        return $analyticsOption ? (int)$analyticsOption : 0;
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'widget_id'  => ['required', 'integer'],
            'channel_id' => ['nullable', 'integer'],
            'is_clicked' => ['nullable', 'boolean'],
        ]);

        Analytics::insert($validated);

        return 'success';
    }

    public function toggleAnalytics(Request $request)
    {
        $validated = $request->validate([
            'widget_analytics' => ['required', 'boolean'],
        ]);

        Config::updateOption('analytics_activate', $validated['widget_analytics']);

        $isAnalyticsEnabled = (int) Config::getOption('analytics_activate');

        if ($isAnalyticsEnabled) {
            $this->addScheduleToCleanupAnalytics();
        } else {
            $this->removeAnalyticsCleanupSchedule();
        }

        return ['widget_analytics' => $isAnalyticsEnabled];
    }

    public function addScheduleToCleanupAnalytics()
    {
        if (!wp_next_scheduled(Config::VAR_PREFIX . 'analytics_cleanup')) {
            wp_schedule_event(time(), 'twicedaily', Config::VAR_PREFIX . 'analytics_cleanup');
        }
    }

    public function removeAnalyticsCleanupSchedule()
    {
        wp_clear_scheduled_hook(Config::VAR_PREFIX . 'analytics_cleanup');
    }

    /**
     * Cleans up old analytics data from the database.
     *
     * This function deletes records from the analytics table that are older than a specified number of days.
     * It performs the deletion in batches to avoid locking the table for too long.
     * The process will iterate up to a maximum number of times to ensure that all old records are deleted.
     *
     * @global wpdb $wpdb WordPress database abstraction object.
     *
     * @return void
     */
    public static function analyticsCleanup()
    {
        global $wpdb;

        $retentionDays = 30; // Number of days to keep analytics data.

        $batchSize = 1000;

        $maxIterations = 10;

        $iterations = 0;

        $cutoff = date('Y-m-d H:i:s', strtotime("-{$retentionDays} days"));

        $table = $wpdb->prefix . Config::VAR_PREFIX . 'analytics';

        $preparedQuery = $wpdb->prepare(
            "DELETE FROM {$table} WHERE created_at < %s LIMIT %d",
            $cutoff,
            $batchSize
        );

        /**
        * If the number of records deleted in a batch is equal to the batch size,
        * and the number of iterations is less than the maximum number of iterations,
        * continue deleting records in batches.
        */
        do {
            $deletedCount = $wpdb->query($preparedQuery);

            $iterations++;
        } while ($deletedCount === $batchSize && $iterations < $maxIterations);
    }

    public function getWidgetAnalytics($filterValue)
    {
        global $wpdb;

        $datePattern = '/\d{4}-\d{2}-\d{2}/';
        $isDate = preg_match($datePattern, $filterValue) === 1;

        $startDate = date('Y-m-d');
        $endDate = date('Y-m-d');
        $dateRange = [];

        if ($isDate) {
            $dateRange = explode(',', $filterValue);
        }

        $placeHolder = [0, 1, 0, 1, 1, 0, 0, 1];

        $dateCondition = '';
        if ($filterValue === '7days') {
            $dateCondition = 'DATE(analytics.created_at) >= DATE_SUB(CURDATE(), INTERVAL 7 DAY)';
        } elseif ($filterValue === '30days') {
            $dateCondition = 'DATE(analytics.created_at) >= DATE_SUB(CURDATE(), INTERVAL 30 DAY)';
        } elseif ($filterValue === 'today') {
            $dateCondition = 'DATE(analytics.created_at) = CURDATE()';
        } elseif ($isDate && isset($dateRange[0]) && isset($dateRange[1])) {
            $startDate = $dateRange[0];
            $endDate = $dateRange[1];
            $placeHolder[] = $startDate;
            $placeHolder[] = $endDate;
            $dateCondition = 'DATE(analytics.created_at) BETWEEN %s AND %s';
        } elseif ($isDate && count($dateRange) !== 2) {
            $startDate = $dateRange[0];
            $placeHolder[] = $startDate;
            $dateCondition = 'DATE(analytics.created_at) = %s';
        } else {
            $dateCondition = 'DATE(analytics.created_at) IS NOT NULL';
        }

        $sql = $wpdb->prepare(
            "SELECT
                    analytics.widget_id, widgets.name,
                    SUM(CASE WHEN analytics.is_clicked = %d THEN %d ELSE %d END) AS visitor_count,
                    SUM(CASE WHEN analytics.is_clicked = %d THEN %d ELSE %d END) AS click_count
                    FROM 
                        {$wpdb->prefix}bit_assist_analytics analytics
                    INNER JOIN 
                        {$wpdb->prefix}bit_assist_widgets widgets ON analytics.widget_id = widgets.id
                    WHERE 
                        (analytics.channel_id IS NULL AND (analytics.is_clicked = %d OR analytics.is_clicked = %d))
                    AND
                        $dateCondition
                    GROUP BY 
                        analytics.widget_id, widgets.name",
            $placeHolder
        );

        $widgetAnalyticsData = $wpdb->get_results($sql);

        return ['data' => $widgetAnalyticsData];
    }

    public function getChannelAnalytics(Request $request)
    {
        global $wpdb;

        $widget_id = $request->widget_id;
        $filterValue = $request->filter;
        $datePattern = '/\d{4}-\d{2}-\d{2}/';
        $isDate = is_array($filterValue) ? preg_match($datePattern, $filterValue[0]) === 1 : false;

        $startDate = date('Y-m-d');
        $endDate = date('Y-m-d');

        $placeHolder = [1, 0, 1, 0, $widget_id, 1];

        $dateCondition = '';
        if ($filterValue === '7days') {
            $dateCondition = 'DATE(analytics.created_at) >= DATE_SUB(CURDATE(), INTERVAL 7 DAY)';
        } elseif ($filterValue === '30days') {
            $dateCondition = 'DATE(analytics.created_at) >= DATE_SUB(CURDATE(), INTERVAL 30 DAY)';
        } elseif ($filterValue === 'today') {
            $dateCondition = 'DATE(analytics.created_at) = CURDATE()';
        } elseif ($isDate && isset($filterValue[0]) && isset($filterValue[1])) {
            $startDate = $filterValue[0];
            $endDate = $filterValue[1];
            $placeHolder[] = $startDate;
            $placeHolder[] = $endDate;
            $dateCondition = 'DATE(analytics.created_at) BETWEEN %s AND %s';
        } elseif ($isDate && count($filterValue) !== 2) {
            $startDate = $filterValue[0];
            $placeHolder[] = $startDate;
            $dateCondition = 'DATE(analytics.created_at) = %s';
        } else {
            $dateCondition = 'DATE(analytics.created_at) IS NOT NULL';
        }

        $sql = $wpdb->prepare(
            "SELECT
                c.id AS channel_id,
                JSON_UNQUOTE(JSON_EXTRACT(c.config, '$.title')) AS title,
                SUM(CASE WHEN analytics.channel_id IS NULL AND analytics.created_at >= c.created_at THEN %d ELSE %d END) AS visitor_count,
                SUM(CASE WHEN analytics.channel_id = c.id THEN %d ELSE %d END) AS click_count
                FROM
                    {$wpdb->prefix}bit_assist_widget_channels AS c
                LEFT JOIN
                    {$wpdb->prefix}bit_assist_analytics AS analytics
                ON
                    c.widget_id = analytics.widget_id
                WHERE
                    c.widget_id = %d
                AND
                    analytics.is_clicked = %d
                AND
                    $dateCondition
                GROUP BY
                    c.id",
            $placeHolder
        );

        $results = $wpdb->get_results($sql);

        return ['data' => $results];
    }

    public function destroy()
    {
        Analytics::delete();
        return Response::success('Analytics removed!');
    }
}
