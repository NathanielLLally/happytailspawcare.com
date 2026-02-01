<?php

namespace BitApps\Assist\HTTP\Controllers;

use AllowDynamicProperties;
use BitApps\Assist\Config;
use BitApps\Assist\Deps\BitApps\WPKit\Http\Request\Request;
use BitApps\Assist\Model\Widget;
use BitApps\Assist\Model\WidgetChannel;
use stdClass;

#[AllowDynamicProperties]
final class ApiWidgetController
{
    private $isPro = false;

    public function __construct()
    {
        $this->isPro = Config::isProActivated();
    }

    public function bitAssistWidget(Request $request)
    {
        $validated = $request->validate([
            'domain' => ['required', 'string', 'sanitize:url'],
        ]);

        $widget = $this->getWidget($validated['domain']);

        if (!isset($widget->id)) {
            return 'Widget not found';
        }

        $widgetChannels = $this->getChannelsByWidget($widget->id);

        if (\is_null($widgetChannels)) {
            return 'Widget channels not found';
        }

        $widget->widget_channels = $widgetChannels;

        $widget->isAnalyticsActivate = (int) Config::getOption('analytics_activate');

        return $widget;
    }

    private function getWidget($domain)
    {
        $widget = new Widget();
        $widget->where('status', 1);

        if (Config::get('SITE_URL') === $domain) {
            $widget->where('active', 1);
        } elseif ($this->isPro) {
            $domainExceptWWW = $domain;
            if (stristr($domainExceptWWW, 'www.')) {
                $domainExceptWWW = str_replace('www.', '', $domainExceptWWW);
            } else {
                $domainExceptWWW = $domain;
            }
            $widget->where('domains', 'LIKE', '%' . parse_url($domainExceptWWW)['host'] . '%');
        } else {
            return;
        }

        $columns = ['id', 'name', 'styles', 'initial_delay', 'page_scroll', 'widget_behavior', 'call_to_action', 'store_responses', 'status', 'hide_credit'];

        if ($this->isPro) {
            $columns = array_merge($columns, ['custom_css', 'timezone', 'business_hours', 'exclude_pages']);
        }

        $widget->take(1)->get($columns);

        return $widget;
    }

    private function getChannelsByWidget($widgetId)
    {
        $widgetChannels = WidgetChannel::where('status', 1)->where('widget_id', $widgetId)->orderBy('sequence')->get(['id', 'channel_name', 'config']);
        if (!is_array($widgetChannels) || \count($widgetChannels) < 1) {
            return null;
        }

        $rootURL = Config::get('ROOT_URI');
        foreach ($widgetChannels as $key => $value) {
            if (!empty($widgetChannels[$key]->config->channel_icon)) {
                $widgetChannels[$key]->channel_icon = $widgetChannels[$key]->config->channel_icon;

                continue;
            }
            $widgetChannels[$key]->channel_icon = $rootURL . '/img/channel/' . strtolower($value->channel_name) . '.svg';
        }

        foreach ($widgetChannels as $channel) {
            $channel->config->title = esc_html($channel->config->title);
            $channel = $this->escapeAll($channel);
        }

        return $widgetChannels;
    }

    private function escapeAll($channel)
    {
        if ($channel->channel_name === 'Custom-Channel') {
            $channel->config->unique_id = esc_url_raw($channel->config->unique_id);
            $channel->config->url = esc_url_raw($channel->config->url);
        }

        if ($channel->channel_name === 'Google-Map') {
            $channel = $this->sanitizeIframe($channel);
        }

        if ($channel->channel_name === 'Custom-Iframe') {
            $channel->config->unique_id = esc_url_raw($channel->config->unique_id);
            $channel->config->url = esc_url_raw($channel->config->url);
        }

        if ($channel->channel_name === 'FAQ' || $channel->channel_name === 'Knowledge-Base') {
            $channel = $this->escapeTitle($channel);
        }

        return $channel;
    }

    private function sanitizeIframe($channel)
    {
        $allowedAttributes = [
            'iframe' => [
                'src'             => [],
                'width'           => [],
                'height'          => [],
                'style'           => [],
                'allowfullscreen' => [],
                'loading'         => [],
                'referrerpolicy'  => [],
            ],
        ];

        if (\is_object($channel)) {
            $channel->config->unique_id = wp_kses($channel->config->unique_id, $allowedAttributes);
        } else {
            $channel['config']['unique_id'] = wp_kses($channel['config']['unique_id'], $allowedAttributes);
        }

        return $channel;
    }

    private function escapeTitle($channel)
    {
        $faqs = new stdClass();
        $kbs = new stdClass();

        if ($channel->channel_name === 'FAQ') {
            $faqs = &$channel->config->card_config->faqs;

            foreach ($faqs as &$faq) {
                if (isset($faq->title)) {
                    $faq->title = esc_html($faq->title);
                }
            }
        } else {
            $kbs = &$channel->config->card_config->knowledge_bases;

            foreach ($kbs as &$kb) {
                if (isset($kb->title)) {
                    $kb->title = esc_html($kb->title);
                }
            }
        }

        return $channel;
    }
}
