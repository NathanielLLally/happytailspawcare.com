<?php

/**
 * Plugin Name:       MarqueeX – Smooth Marquee Slider & News Ticker for Gutenberg & Elementor
 * Description:       Create smooth horizontal or vertical marquees, sliders, scrolling content, news tickers, infinite sliders and text animations in WordPress.
 * Requires at least: 6.0
 * Requires PHP:      7.4
 * Version:           0.2.3
 * Author:            WPXERO
 * Author URI:        https://wpxero.com
 * License:           GPL-2.0-or-later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain:       marqueex
 * Domain Path:       /languages
 */

namespace Wpxero\Marqueex;

use Wpxero\Marqueex\Traits\Singleton;
use Wpxero\Marqueex\Utils;
use Wpxero\Marqueex\Admin\Menu;
use Wpxero\Marqueex\Admin\Enqueue;
use Wpxero\Marqueex\Core\Settings;
use Wpxero\Marqueex\Integrations;

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

// Check if vendor directory and autoload.php exist
$autoload_file = __DIR__ . '/vendor/autoload.php';
if (file_exists($autoload_file)) {
    // Load autoloader (vendor/autoload.php).
    require_once $autoload_file;
}

/**
 * Main MarqueeX Class
 *
 * @class MarqueeX
 * @since 0.1.0
 */



class MarqueeX {
    use Singleton;



    /**
     * Path to the plugin directory
     *
     * @var $plugin_path
     */
    public $plugin_path;

    /**
     * URL to the plugin directory
     *
     * @var $plugin_url
     */
    public $plugin_url;

    /**
     * Plugin name
     *
     * @var $plugin_name
     */
    public $plugin_name;

    /**
     * Plugin version
     *
     * @var $plugin_version
     */
    public $plugin_version;

    /**
     * Plugin slug
     *
     * @var $plugin_slug
     */
    public $plugin_slug;

    /**
     * Plugin name sanitized
     *
     * @var $plugin_name_sanitized
     */
    public $plugin_name_sanitized;

    /**
     * MarqueeX constructor.
     */
    public function __construct() {
        /* We do nothing here! */
    }

    public function init_hooks() {
        $this->define_constants();
        $this->load_files();
    }
    /**
     * Define Constants
     */
    public static function define_constants() {
        define('MARQUEEX_FILE', __FILE__);
        define('MARQUEEX_NAMESPACE', 'MARQUEEX');
        define('MARQUEEX_SLUG', 'marqueex');
        define('MARQUEEX_VERSION', '0.2.3');
        define('MARQUEEX_DIR_PATH', plugin_dir_path(__FILE__));
        define('MARQUEEX_ADMIN_URL', plugin_dir_url(__FILE__));
        define('MARQUEEX_WP_VERSION', (float) get_bloginfo('version'));
        define('MARQUEEX_PHP_VERSION', (float) phpversion());
    }

    public function load_files() {
        Settings::get_instance();
        Menu::get_instance();
        Enqueue::get_instance();
        Utils\SeoImprovements::get_instance();
        Utils\MetaDescription::get_instance();

        // Load integrations
        $this->load_integrations();
        $this->init_freemius();
    }

    /**
     * Load builder integrations
     */
    private function load_integrations() {
        if (file_exists($this->plugin_path . 'vendor/freemius/wordpress-sdk/start.php')) {
            require_once $this->plugin_path . 'vendor/freemius/wordpress-sdk/start.php';
        }

        // Load Elementor integration
        if (class_exists('\Elementor\Plugin')) {
            Integrations\Elementor\ElementorIntegration::get_instance();
        }

        // Load Shortcode integration
        Integrations\Shortcode\ShortcodeIntegration::get_instance();

        // Load Gutenberg integration
        Integrations\Gutenberg\GutenbergIntegration::get_instance();
    }


    public function init_freemius() {
        if (! function_exists('marqueex_fs')) {
            // Create a helper function for easy SDK access.
            function marqueex_fs() {
                global $marqueex_fs;

                if (! isset($marqueex_fs)) {
                    // Activate multisite network integration.
                    if (! defined('WP_FS__PRODUCT_21024_MULTISITE')) {
                        define('WP_FS__PRODUCT_21024_MULTISITE', true);
                    }

                    // Include Freemius SDK.
                    // SDK is auto-loaded through composer
                    $marqueex_fs = fs_dynamic_init(array(
                        'id'                  => '21024',
                        'slug'                => 'marqueex',
                        'type'                => 'plugin',
                        'public_key'          => 'pk_b8a19bfe3fffa97b497382eddcc3b',
                        'is_premium'          => false,
                        'has_addons'          => false,
                        'has_paid_plans'      => false,
                        'menu'                => array(
                            'slug'           => 'marqueex',
                            'first-path'     => 'admin.php?page=marqueex',
                            'network'        => true,
                            'account'        => true,
                            'contact'        => false,
                            'support'        => false,
                        ),
                    ));
                }

                return $marqueex_fs;
            }

            // Init Freemius.
            marqueex_fs();
            // Signal that SDK was initiated.
            do_action('marqueex_fs_loaded');
        }
    }

    /**
     * Activation Hook
     */
    public function activation_hook() {
        // Welcome Page Flag.
        set_transient('_marqueex_welcome_screen_activation_redirect', true, 30);
    }

    /**
     * Deactivation Hook
     */
    public function deactivation_hook() {
    }
}


// Initialize the plugin
add_action('plugins_loaded', function () {
    $instance = \Wpxero\Marqueex\MarqueeX::get_instance();
    $instance->init_hooks();
});


register_activation_hook(__FILE__, [\Wpxero\Marqueex\MarqueeX::get_instance(), 'activation_hook']);
register_deactivation_hook(__FILE__, [\Wpxero\Marqueex\MarqueeX::get_instance(), 'deactivation_hook']);
