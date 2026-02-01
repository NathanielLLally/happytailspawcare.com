<?php

namespace Wpxero\Marqueex\Integrations\Shortcode;

use Wpxero\Marqueex\Traits\Singleton;

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Shortcode Integration Class
 */
class ShortcodeIntegration {
    use Singleton;

    /**
     * Constructor
     */
    private function __construct() {
        add_action('init', [$this, 'init']);
    }

    /**
     * Initialize Shortcode integration
     */
    public function init() {
        // Check if integration is enabled
        $settings = get_option('marqueex_settings', []);
        $shortcode_enabled = $settings['builder_support']['shortcode']['enabled'] ?? true;

        if (!$shortcode_enabled) {
            return;
        }

        // Register shortcodes
        add_shortcode('marqueex_slider', [$this, 'render_slider_shortcode']);
        add_shortcode('marqueex_ticker', [$this, 'render_ticker_shortcode']);
        add_shortcode('marqueex_infinite', [$this, 'render_infinite_shortcode']);

        // Add shortcode documentation
        add_action('admin_footer', [$this, 'add_shortcode_documentation']);
    }

    /**
     * Render Marquee Slider Shortcode
     */
    public function render_slider_shortcode($atts, $content = null) {
        $atts = shortcode_atts([
            'speed' => '30',
            'direction' => 'left',
            'pause_on_hover' => 'true',
            'class' => '',
            'id' => '',
        ], $atts, 'marqueex_slider');

        $unique_id = 'marqueex-slider-' . uniqid();
        $class = !empty($atts['class']) ? ' ' . esc_attr($atts['class']) : '';
        $id = !empty($atts['id']) ? ' id="' . esc_attr($atts['id']) . '"' : '';

        $output = sprintf(
            '<div%s class="marqueex-slider%s" data-speed="%s" data-direction="%s" data-hover-paused-enabled="%s">%s</div>',
            $id,
            $class,
            esc_attr($atts['speed']),
            esc_attr($atts['direction']),
            esc_attr($atts['pause_on_hover']),
            do_shortcode($content)
        );

        return $output;
    }

    /**
     * Render News Ticker Shortcode
     */
    public function render_ticker_shortcode($atts, $content = null) {
        $atts = shortcode_atts([
            'speed' => '30',
            'direction' => 'left',
            'pause_on_hover' => 'true',
            'label' => 'BREAKING',
            'show_label' => 'true',
            'class' => '',
            'id' => '',
        ], $atts, 'marqueex_ticker');

        $unique_id = 'marqueex-ticker-' . uniqid();
        $class = !empty($atts['class']) ? ' ' . esc_attr($atts['class']) : '';
        $id = !empty($atts['id']) ? ' id="' . esc_attr($atts['id']) . '"' : '';

        $output = '<div class="marqueex-news-ticker-wrapper">';

        if ($atts['show_label'] === 'true') {
            $output .= sprintf(
                '<div class="marqueex-ticker-label">%s</div>',
                esc_html($atts['label'])
            );
        }

        $output .= sprintf(
            '<div%s class="marqueex-news-ticker%s" data-speed="%s" data-direction="%s" data-hover-paused-enabled="%s">%s</div>',
            $id,
            $class,
            esc_attr($atts['speed']),
            esc_attr($atts['direction']),
            esc_attr($atts['pause_on_hover']),
            do_shortcode($content)
        );

        $output .= '</div>';

        return $output;
    }

    /**
     * Render Infinite Slider Shortcode
     */
    public function render_infinite_shortcode($atts, $content = null) {
        $atts = shortcode_atts([
            'speed' => '30',
            'direction' => 'left',
            'pause_on_hover' => 'true',
            'gap' => '20',
            'class' => '',
            'id' => '',
        ], $atts, 'marqueex_infinite');

        $unique_id = 'marqueex-infinite-' . uniqid();
        $class = !empty($atts['class']) ? ' ' . esc_attr($atts['class']) : '';
        $id = !empty($atts['id']) ? ' id="' . esc_attr($atts['id']) . '"' : '';

        $output = sprintf(
            '<div%s class="marqueex-infinite-slider%s" data-speed="%s" data-direction="%s" data-hover-paused-enabled="%s" data-gap="%s">%s</div>',
            $id,
            $class,
            esc_attr($atts['speed']),
            esc_attr($atts['direction']),
            esc_attr($atts['pause_on_hover']),
            esc_attr($atts['gap']),
            do_shortcode($content)
        );

        return $output;
    }

    /**
     * Add shortcode documentation to admin
     */
    public function add_shortcode_documentation() {
        $screen = get_current_screen();

        // Only show on post edit screens
        if (!$screen || !in_array($screen->base, ['post', 'post-new'])) {
            return;
        }

?>
        <div id="marqueex-shortcode-help" style="display: none;">
            <div class="marqueex-shortcode-docs">
                <h3><?php _e('MarqueeX Shortcodes', 'marqueex'); ?></h3>

                <h4><?php _e('Marquee Slider', 'marqueex'); ?></h4>
                <code>[marqueex_slider speed="30" direction="left" pause_on_hover="true"]Your content here[/marqueex_slider]</code>

                <h4><?php _e('News Ticker', 'marqueex'); ?></h4>
                <code>[marqueex_ticker speed="30" direction="left" label="BREAKING" show_label="true"]Your news content here[/marqueex_ticker]</code>

                <h4><?php _e('Infinite Slider', 'marqueex'); ?></h4>
                <code>[marqueex_infinite speed="30" direction="left" gap="20"]Your slider content here[/marqueex_infinite]</code>

                <p><strong><?php _e('Parameters:', 'marqueex'); ?></strong></p>
                <ul>
                    <li><strong>speed:</strong> <?php _e('Animation speed (10-100)', 'marqueex'); ?></li>
                    <li><strong>direction:</strong> <?php _e('left, right, up, down', 'marqueex'); ?></li>
                    <li><strong>pause_on_hover:</strong> <?php _e('true or false', 'marqueex'); ?></li>
                    <li><strong>label:</strong> <?php _e('Label text for ticker (default: BREAKING)', 'marqueex'); ?></li>
                    <li><strong>show_label:</strong> <?php _e('Show/hide label (true or false)', 'marqueex'); ?></li>
                    <li><strong>gap:</strong> <?php _e('Gap between items in pixels', 'marqueex'); ?></li>
                    <li><strong>class:</strong> <?php _e('Additional CSS classes', 'marqueex'); ?></li>
                    <li><strong>id:</strong> <?php _e('Custom ID attribute', 'marqueex'); ?></li>
                </ul>
            </div>
        </div>

        <script>
            jQuery(document).ready(function($) {
                // Add shortcode button to toolbar
                if ($('#ed_toolbar').length) {
                    $('#ed_toolbar').append('<input type="button" id="marqueex_shortcodes" class="ed_button" value="MarqueeX" title="Insert MarqueeX Shortcodes" />');

                    $('#marqueex_shortcodes').click(function() {
                        $('#marqueex-shortcode-help').dialog({
                            title: 'MarqueeX Shortcodes',
                            width: 600,
                            height: 500,
                            modal: true
                        });
                    });
                }
            });
        </script>
<?php
    }

    /**
     * Get shortcode examples
     */
    public function get_shortcode_examples() {
        return [
            'slider' => [
                'shortcode' => '[marqueex_slider speed="30" direction="left"]Your marquee content here[/marqueex_slider]',
                'description' => __('Basic marquee slider with customizable speed and direction.', 'marqueex'),
            ],
            'ticker' => [
                'shortcode' => '[marqueex_ticker label="BREAKING" show_label="true"]Breaking news content here[/marqueex_ticker]',
                'description' => __('News ticker with optional label and customizable settings.', 'marqueex'),
            ],
            'infinite' => [
                'shortcode' => '[marqueex_infinite gap="20" pause_on_hover="true"]Slider item 1 | Slider item 2 | Slider item 3[/marqueex_infinite]',
                'description' => __('Infinite slider with configurable gap and hover pause.', 'marqueex'),
            ],
        ];
    }

    /**
     * Get integration status
     */
    public function get_status() {
        return [
            'active' => true,
            'shortcodes_registered' => true,
            'shortcodes' => [
                'marqueex_slider' => shortcode_exists('marqueex_slider'),
                'marqueex_ticker' => shortcode_exists('marqueex_ticker'),
                'marqueex_infinite' => shortcode_exists('marqueex_infinite'),
            ],
        ];
    }
}
