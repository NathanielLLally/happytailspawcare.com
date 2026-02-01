<?php

namespace Wpxero\Marqueex\Integrations\Elementor;

use Wpxero\Marqueex\Traits\Singleton;

if (!defined('ABSPATH')) {
    exit;
}

// Import WordPress functions
use function add_action;
use function get_option;
use function wp_enqueue_script;
use function wp_enqueue_style;
use function wp_register_script;
use function wp_register_style;
use function plugins_url;
use function admin_url;
use function wp_create_nonce;
use function __;

/**
 * Elementor Integration Class
 */
class ElementorIntegration {
    use Singleton;

    /**
     * Constructor
     */
    private function __construct() {
        add_action('init', [$this, 'init']);
    }

    /**
     * Initialize Elementor integration
     */
    public function init() {
        // Check if Elementor is active
        if (!$this->is_elementor_active()) {
            return;
        }

        // Check if integration is enabled
        $settings = get_option('marqueex_settings', []);
        $elementor_enabled = $settings['builder_support']['elementor']['enabled'] ?? false;

        if (!$elementor_enabled) {
            return;
        }

        // Hook into Elementor
        add_action('elementor/widgets/register', [$this, 'register_widgets']);
        add_action('elementor/elements/categories_registered', [$this, 'add_widget_categories'], 1);
        add_action('elementor/editor/before_enqueue_scripts', [$this, 'enqueue_editor_scripts']);
        // add_action('elementor/frontend/after_enqueue_scripts', [$this, 'enqueue_elementor_assets']);
        add_action('wp_enqueue_scripts', [$this, 'enqueue_elementor_assets']);
    }

    /**
     * Check if Elementor is active
     */
    private function is_elementor_active() {
        return class_exists('\Elementor\Plugin');
    }

    /**
     * Register MarqueeX widgets
     */
    public function register_widgets($widgets_manager) {
        // Get widget settings
        $settings = get_option('marqueex_settings', []);
        $widget_settings = $settings['builder_support']['elementor']['elements'] ?? [];

        // Include and register ImageMarquee widget if enabled
        if ($widget_settings['image_marquee'] ?? true) {
            require_once __DIR__ . '/Widgets/ImageMarquee.php';
            $widgets_manager->register(new Widgets\ImageMarquee());
        }

        // Include and register TextMarquee widget if enabled
        if ($widget_settings['text_marquee'] ?? true) {
            require_once __DIR__ . '/Widgets/TextMarquee.php';
            $widgets_manager->register(new Widgets\TextMarquee());
        }
        // Include and register AnimatedHeading widget if enabled
        if ($widget_settings['animated_heading'] ?? true) {
            require_once __DIR__ . '/Widgets/AnimatedHeading.php';
            $widgets_manager->register(new Widgets\AnimatedHeading());
        }

        // Include and register AnimatedWordRoller widget if enabled
        if ($widget_settings['animated_word_roller'] ?? true) {
            require_once __DIR__ . '/Widgets/AnimatedWordRoller.php';
            $widgets_manager->register(new Widgets\AnimatedWordRoller());
        }

        // Include and register NewsTicker widget if enabled
        if ($widget_settings['news_ticker'] ?? true) {
            require_once __DIR__ . '/Widgets/NewsTicker.php';
            $widgets_manager->register(new Widgets\NewsTicker());
        }
    }

    /**
     * Add custom widget categories
     */
    public function add_widget_categories($elements_manager) {
        $elements_manager->add_category(
            'marqueex',
            [
                'title' => __('MarqueeX', 'marqueex'),
                'icon' => 'fa fa-sliders',
            ]
        );
    }

    /**
     * Enqueue editor scripts
     */
    public function enqueue_editor_scripts() {
        wp_enqueue_style(
            'marqueex-elementor-editor',
            plugins_url('/assets/css/elementor-editor.css', MARQUEEX_FILE),
            [],
            MARQUEEX_VERSION
        );
    }

    /**
     * Enqueue frontend scripts
     */
    public function enqueue_elementor_assets() {
        wp_enqueue_style(
            'marqueex-common',
            plugins_url('/assets/css/common.css', MARQUEEX_FILE),
            [],
            MARQUEEX_VERSION
        );
        wp_register_script(
            'marqueex-elementor-frontend',
            plugins_url('/assets/js/elementor-frontend.js', MARQUEEX_FILE),
            ['jquery'],
            MARQUEEX_VERSION,
            true
        );

        // register news ticker specific assets
        wp_register_style(
            'marqueex-news-ticker',
            plugins_url('/assets/css/news-ticker.css', MARQUEEX_FILE),
            [],
            MARQUEEX_VERSION
        );

        wp_register_script(
            'marqueex-news-ticker',
            plugins_url('/assets/js/news-ticker.js', MARQUEEX_FILE),
            ['jquery', 'marqueex-elementor-frontend'],
            MARQUEEX_VERSION,
            true
        );

        // register text marquee specific assets
        wp_register_style(
            'marqueex-text-marquee',
            plugins_url('/assets/css/text-marquee.css', MARQUEEX_FILE),
            [],
            MARQUEEX_VERSION
        );

        wp_register_script(
            'marqueex-text-marquee',
            plugins_url('/assets/js/text-marquee.js', MARQUEEX_FILE),
            ['jquery', 'marqueex-elementor-frontend'],
            MARQUEEX_VERSION,
            true
        );

        // register image marquee specific assets
        wp_register_style(
            'marqueex-image-marquee',
            plugins_url('/assets/css/image-marquee.css', MARQUEEX_FILE),
            [],
            MARQUEEX_VERSION
        );

        wp_register_script(
            'marqueex-image-marquee',
            plugins_url('/assets/js/image-marquee.js', MARQUEEX_FILE),
            ['jquery', 'marqueex-elementor-frontend'],
            MARQUEEX_VERSION,
            true
        );

        // register animated heading specific assets
        wp_register_style(
            'marqueex-animated-heading-style',
            plugins_url('/assets/css/animated-heading.css', MARQUEEX_FILE),
            [],
            MARQUEEX_VERSION
        );
        wp_register_script(
            'marqueex-animated-heading',
            plugins_url('/assets/js/animated-heading.js', MARQUEEX_FILE),
            ['jquery'],
            MARQUEEX_VERSION,
            true
        );

        // register animated word roller specific assets
        wp_register_style(
            'marqueex-animated-word-roller-style',
            plugins_url('/assets/css/animated-word-roller.css', MARQUEEX_FILE),
            [],
            MARQUEEX_VERSION
        );
        wp_register_script(
            'marqueex-animated-word-roller',
            plugins_url('/assets/js/animated-word-roller.js', MARQUEEX_FILE),
            ['jquery', 'marqueex-elementor-frontend'],
            MARQUEEX_VERSION,
            true
        );
    }

    /**
     * Get integration status
     */
    public function get_status() {
        $status = [
            'active' => false,
            'version' => null,
            'widgets_registered' => false,
            'enabled_widgets' => [],
        ];

        if ($this->is_elementor_active()) {
            $status['active'] = true;
            $status['version'] = \Elementor\Plugin::$instance->get_version();

            // Check which widgets are enabled
            $settings = get_option('marqueex_settings', []);
            $widget_settings = $settings['builder_support']['elementor']['elements'] ?? [];

            $enabled_widgets = [];
            if ($widget_settings['image_marquee'] ?? true) {
                $enabled_widgets[] = 'image_marquee';
            }
            if ($widget_settings['news_ticker'] ?? true) {
                $enabled_widgets[] = 'news_ticker';
            }
            if ($widget_settings['text_marquee'] ?? true) {
                $enabled_widgets[] = 'text_marquee';
            }
            if ($widget_settings['animated_heading'] ?? true) {
                $enabled_widgets[] = 'animated_heading';
            }
            if ($widget_settings['animated_word_roller'] ?? true) {
                $enabled_widgets[] = 'animated_word_roller';
            }

            $status['enabled_widgets'] = $enabled_widgets;
            $status['widgets_registered'] = !empty($enabled_widgets);
        }

        return $status;
    }
}
