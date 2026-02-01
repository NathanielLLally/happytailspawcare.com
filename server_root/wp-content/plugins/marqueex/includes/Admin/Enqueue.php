<?php

namespace Wpxero\Marqueex\Admin;

use Wpxero\Marqueex\Traits\Singleton;

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

/**
 * Class Enqueue
 */
class Enqueue {
    use Singleton;

    /**
     * Initialize the class
     */
    private function __construct() {
        add_action('admin_enqueue_scripts', [$this, 'enqueue_scripts']);
        add_action('admin_body_class', [$this, 'add_custom_body_class']);
        add_action('admin_init', [$this, 'redirect_to_welcome_screen']);
        add_filter('plugin_action_links_marqueex/marqueeX.php', [$this, 'marqueex_settings_link']);
    }

    public function marqueex_settings_link($links) {
        $settings_link = '<a href="' . admin_url('admin.php?page=marqueex&sub_page=settings') . '">Settings</a>';
        array_unshift($links, $settings_link);
        return $links;
    }

    /**
     *
     * Add custom body class to admin pages for Marqueex plugin.
     *
     * @param array $classes Existing body classes.
     * @return array Modified body classes.
     */
    // add body class for extenderx
    public function add_custom_body_class($classes) {
        // Check if we are on editing screen in WordPress admin
        if (is_admin() && isset($_GET['action']) && $_GET['action'] === 'edit') { // phpcs:ignore
            $classes .= ' marqueex-admin-page';
        }
        return $classes;
    }

    /**
     * Enqueue admin scripts and styles
     *
     * @param string $hook_suffix The current admin page.
     */
    public function enqueue_scripts($hook_suffix) {
        // Only load on MarqueeX admin pages
        if (strpos($hook_suffix, 'marqueex') === false) {
            return;
        }

        $admin_assets = MARQUEEX_DIR_PATH . 'admin/dependencies.php';
        if (file_exists($admin_assets)) {
            $admin_assets = require $admin_assets;

            wp_enqueue_script(
                'marqueex-admin',
                MARQUEEX_ADMIN_URL . 'admin/marqueex-admin.js',
                $admin_assets['dependencies'],
                $admin_assets['version'],
                true
            );

            // Get current settings
            $settings = get_option('marqueex_settings', array());

            // Ensure we have the complete settings structure
            $default_settings = [
                'builder_support' => [
                    'gutenberg' => [
                        'enabled' => true,
                        'auto_detect' => true,
                        'custom_blocks' => true,
                    ],
                    'elementor' => [
                        'enabled' => false,
                        'auto_detect' => true,
                        'widgets' => [
                            'text_marquee' => true,
                            'image_marquee' => true,
                            'animated_heading' => true,
                            'animated_word_roller' => true,
                            'news_ticker' => true,
                        ],
                    ],
                    'bricks' => [
                        'enabled' => false,
                        'auto_detect' => true,
                        'elements' => [
                            'text_marquee' => true,
                            'news_ticker' => true,
                            'image_marquee' => true,
                        ],
                    ],
                    'shortcode' => [
                        'enabled' => true,
                        'auto_detect' => true,
                        'shortcodes' => [
                            'text_marquee' => true,
                            'news_ticker' => true,
                            'image_marquee' => true,
                        ],
                    ],
                ],
                'performance' => [
                    'lazy_loading' => true,
                    'minify_css' => true,
                    'cache_styles' => true,
                ],
                'compatibility' => [
                    'theme_compatibility' => true,
                    'plugin_compatibility' => true,
                    'responsive_breakpoints' => [
                        'mobile' => 480,
                        'tablet' => 1024,
                        'desktop' => 1200,
                    ],
                ],
            ];

            $complete_settings = array_merge($default_settings, $settings);

            wp_localize_script(
                'marqueex-admin',
                'marqueexAdminData',
                [
                    'settings' => $complete_settings,
                    'version' => MARQUEEX_VERSION,
                    'ajax_url' => admin_url('admin-ajax.php'),
                    'nonce' => wp_create_nonce('marqueex-admin-nonce'),
                    'rest_url' => rest_url('wpxero/marqueex/v1'),
                ]
            );

            wp_enqueue_style(
                'marqueex-admin',
                MARQUEEX_ADMIN_URL . 'admin/style-marqueex.css',
                $admin_assets['version']
            );
        }
    }
    /**
     * Redirect to Welcome page after activation.
     */
    public function redirect_to_welcome_screen() {
        // Bail if no activation redirect.
        if (! get_transient('_marqueex_welcome_screen_activation_redirect')) {
            return;
        }

        // Delete the redirect transient.
        delete_transient('_marqueex_welcome_screen_activation_redirect');

        // Bail if activating from network, or bulk.
        // phpcs:ignore WordPress.Security.NonceVerification.Recommended
        if (is_network_admin() || isset($_GET['activate-multi'])) {
            return;
        }

        // Redirect to welcome page.
        wp_safe_redirect(admin_url('admin.php?page=marqueex&sub_page=settings'));
    }
}
