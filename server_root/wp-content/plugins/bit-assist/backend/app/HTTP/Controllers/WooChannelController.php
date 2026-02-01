<?php

namespace BitApps\Assist\HTTP\Controllers;

use AllowDynamicProperties;
use BitApps\Assist\Deps\BitApps\WPKit\Http\Request\Request;
use WP_Query;

#[AllowDynamicProperties]
final class WooChannelController
{
    public function orderDetails(Request $request)
    {
        if (!class_exists('WooCommerce')) {
            return ['message' => 'WooCommerce not installed or active.', 'status_code' => 404];
        }

        $order_id = sanitize_text_field($request['number']);
        $billing_email = sanitize_email($request['email']);
        $allOrders = [];

        global $wpdb;

        if ($order_id && $billing_email) {
            if (!empty(wc_get_order($order_id)) && $billing_email === wc_get_order($order_id)->get_billing_email()) {
                $item = $this->getOrderWithIdAndMail($order_id);

                return ['items' => $item, 'status_code' => 200];
            }

            return ['message' => 'No order found', 'status_code' => 404];
        } elseif ($order_id && !empty(wc_get_order($order_id))) {
            $item = $this->getOrderWithIdAndMail($order_id);

            return ['items' => $item, 'status_code' => 200];
        } elseif ($billing_email) {
            $query = $wpdb->prepare(
                "SELECT * FROM {$wpdb->postmeta} WHERE meta_key = %s AND meta_value = %s",
                '_billing_email',
                $billing_email
            );

            $orders = $wpdb->get_results($query);

            if ($orders) {
                foreach ($orders as $order) {
                    $order_details = wc_get_order($order->post_id);
                    $allOrders[] = $order_details;
                }
                $data = $this->allOrderWithPagination($request, $allOrders);

                return $data;
            }

            return ['message' => 'No order found', 'status_code' => 404];
        }

        return ['message' => 'No order found', 'status_code' => 404];
    }

    private function getOrderWithIdAndMail($order_id)
    {
        $order_details = wc_get_order($order_id);
        $shipping_status = $order_details->get_status();
        $total_items = $order_details->get_item_count();
        $total_amount = $order_details->get_total();
        $billing_name = $order_details->get_billing_first_name() . ' ' . $order_details->get_billing_last_name();
        $shipping_name = $order_details->get_shipping_first_name() . ' ' . $order_details->get_shipping_last_name();

        $item[] = ['order_id' => $order_id, 'shipping_status' => $shipping_status, 'total_items' => $total_items, 'total_amount' => $total_amount, 'billing_name' => $billing_name, 'shipping_name' => $shipping_name];

        return $item;
    }

    private function allOrderWithPagination($request, $allOrders)
    {
        $paged = !empty($request['page']) ? $request['page'] : 1;
        $per_page = 10;

        $args = [
            'post_type'      => 'shop_order',
            'post_status'    => 'any',
            'orderby'        => 'ID',
            'order'          => 'ASC',
            'paged'          => $paged,
            'posts_per_page' => $per_page,
            'post__in'       => wp_list_pluck($allOrders, 'ID')

        ];

        $orders_query = new WP_Query($args);

        $allItems = $this->allItemsForEmail($orders_query);

        $orders_query->pagination = [
            'total'        => $orders_query->max_num_pages,
            'current'      => $paged,
            'next'         => $paged + 1,
            'previous'     => $paged - 1,
            'has_next'     => $paged < $orders_query->max_num_pages,
            'has_previous' => $paged > 1,
        ];

        return ['items' => $allItems, 'pagination' => $orders_query->pagination, 'status_code' => 200];
    }

    private function allItemsForEmail($orders_query)
    {
        $items = [];

        foreach ($orders_query->posts as $order) {
            $order_id = $order->ID;
            $order_details = wc_get_order($order_id);

            $item_count = $order_details->get_item_count();
            $total = $order_details->get_total();
            $shipping_status = $order_details->get_status('shipping');
            $shipping_name = $order_details->get_shipping_first_name() . ' ' . $order_details->get_shipping_last_name();
            $billing_name = $order_details->get_billing_first_name() . ' ' . $order_details->get_billing_last_name();

            $items[] = ['order_id' => $order_id, 'shipping_status' => $shipping_status, 'total_items' => $item_count, 'total_amount' => $total, 'billing_name' => $billing_name, 'shipping_name' => $shipping_name];
        }

        return $items;
    }
}
