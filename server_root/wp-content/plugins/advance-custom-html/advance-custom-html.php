<?php

/**
 * Plugin Name: Advance Custom HTML
 * Description: An advance html code editor which enable you to code professionally. It provides different skins, denting, correction and more. 
 * Version: 2.0.3
 * Author: bPlugins
 * Author URI: http://bplugins.com
 * License: GPLv3
 * License URI: https://www.gnu.org/licenses/gpl-3.0.txt
 * Text Domain: custom-html
 */
if ( !defined( 'ABSPATH' ) ) {
    exit;
}
if ( function_exists( 'achb_fs' ) ) {
    achb_fs()->set_basename( false, __FILE__ );
} else {
    define( 'ACHB_VERSION', ( isset( $_SERVER['HTTP_HOST'] ) && 'localhost' === $_SERVER['HTTP_HOST'] ? time() : '2.0.3' ) );
    define( 'ACHB_DIR_URL', plugin_dir_url( __FILE__ ) );
    define( 'ACHB_DIR_PATH', plugin_dir_path( __FILE__ ) );
    define( 'ACHB_HAS_FREE', 'advance-custom-html/advance-custom-html.php' === plugin_basename( __FILE__ ) );
    define( 'ACHB_HAS_PRO', 'advance-custom-html-pro/advance-custom-html.php' === plugin_basename( __FILE__ ) );
    if ( !function_exists( 'achb_fs' ) ) {
        function achb_fs() {
            global $achb_fs;
            if ( !isset( $achb_fs ) ) {
                $fsStartPath = dirname( __FILE__ ) . '/vendor/freemius/start.php';
                $bSDKInitPath = dirname( __FILE__ ) . '/vendor/freemius-lite/start.php';
                if ( ACHB_HAS_PRO && file_exists( $fsStartPath ) ) {
                    require_once $fsStartPath;
                } else {
                    if ( ACHB_HAS_FREE && file_exists( $bSDKInitPath ) ) {
                        require_once $bSDKInitPath;
                    }
                }
                $achbConfig = [
                    'id'                  => '16894',
                    'slug'                => 'advance-custom-html',
                    'premium_slug'        => 'advance-custom-html-pro',
                    'type'                => 'plugin',
                    'public_key'          => 'pk_e99f567863d84a62f963ac66aeb42',
                    'is_premium'          => true,
                    'premium_suffix'      => 'Pro',
                    'has_premium_version' => true,
                    'has_addons'          => false,
                    'has_paid_plans'      => true,
                    'trial'               => array(
                        'days'               => 7,
                        'is_require_payment' => false,
                    ),
                    'menu'                => array(
                        'slug'       => 'advanced-custom-html',
                        'first-path' => 'tools.php?page=advanced-custom-html#/dashboard',
                        'contact'    => false,
                        'support'    => false,
                        'parent'     => array(
                            'slug' => 'tools.php',
                        ),
                    ),
                ];
                $achb_fs = ( ACHB_HAS_PRO && file_exists( $fsStartPath ) ? fs_dynamic_init( $achbConfig ) : fs_lite_dynamic_init( $achbConfig ) );
            }
            return $achb_fs;
        }

        achb_fs();
        do_action( 'achb_fs_loaded' );
    }
    function achbIsPremium() {
        return ( ACHB_HAS_PRO ? achb_fs()->can_use_premium_code() : false );
    }

    // Main Plugin Logic
    require_once ACHB_DIR_PATH . 'includes/AdminMenu.php';
    class ACHB_Main {
        function __construct() {
            add_filter(
                'plugin_row_meta',
                [$this, 'pluginRowMeta'],
                10,
                2
            );
            add_action( 'init', [$this, 'init'] );
            add_action( 'wp_ajax_achbPipeChecker', [$this, 'achbPipeChecker'] );
            add_action( 'wp_ajax_nopriv_achbPipeChecker', [$this, 'achbPipeChecker'] );
            add_action( 'admin_init', [$this, 'registerSettings'] );
            add_action( 'rest_api_init', [$this, 'registerSettings'] );
        }

        function pluginRowMeta( $plugin_meta, $plugin_file ) {
            if ( strpos( $plugin_file, 'advance-custom-html' ) !== false && time() < strtotime( '2025-12-06' ) ) {
                $new_links = array(
                    'deal' => "<a href='https://bplugins.com/coupons/?from=plugins.php&plugin=advance-custom-html' target='_blank' style='font-weight: 600; color: #146ef5;'>🎉 Black Friday Sale - Get up to 80% OFF Now!</a>",
                );
                $plugin_meta = array_merge( $plugin_meta, $new_links );
            }
            return $plugin_meta;
        }

        function achbPipeChecker() {
            $nonce = $_POST['_wpnonce'] ?? null;
            if ( !wp_verify_nonce( $nonce, 'wp_ajax' ) ) {
                wp_send_json_error( 'Invalid Request' );
            }
            wp_send_json_success( [
                'isPipe' => [
                    'isPipe'   => achbIsPremium(),
                    'adminUrl' => admin_url(),
                ],
            ] );
        }

        function registerSettings() {
            register_setting( 'achbUtils', 'achbUtils', [
                'show_in_rest'      => [
                    'name'   => 'achbUtils',
                    'schema' => [
                        'type' => 'string',
                    ],
                ],
                'type'              => 'string',
                'default'           => wp_json_encode( [
                    'nonce' => wp_create_nonce( 'wp_ajax' ),
                ] ),
                'sanitize_callback' => 'sanitize_text_field',
            ] );
        }

        function init() {
            register_block_type( __DIR__ . '/build' );
            wp_set_script_translations( 'achb-editor', 'custom-html', plugin_dir_path( __FILE__ ) . 'languages' );
        }

        // function render($attributes) {
        // 	extract($attributes);
        // 	$isDisplayCodeToFrontend = $attributes["options"]["displayCodeToFrontend"];
        // 	$id = wp_unique_id('bPluginsCustomHtml-');
        // 	if (empty($isDisplayCodeToFrontend)) {
        // 		return $HTML;
        // 	} else {
        // 		return '<div ' . get_block_wrapper_attributes() . ' id="' . esc_attr($id) . '" data-attributes="' . esc_attr(wp_json_encode($attributes)) . '"></div>';
        // 	}
        // }
    }

    new ACHB_Main();
}