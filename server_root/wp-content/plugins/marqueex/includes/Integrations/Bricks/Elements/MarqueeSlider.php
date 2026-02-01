<?php

namespace Wpxero\Marqueex\Integrations\Bricks\Elements;

use Bricks\Element;

if (!defined('ABSPATH')) {
    exit;
}

/**
 * MarqueeX Slider Element for Bricks
 */
class MarqueeSlider extends Element {

    /**
     * Get element name
     */
    public function get_name() {
        return 'marqueex-slider';
    }

    /**
     * Get element label
     */
    public function get_label() {
        return __('MarqueeX Slider', 'marqueex');
    }

    /**
     * Get element category
     */
    public function get_category() {
        return 'marqueex';
    }

    /**
     * Get element icon
     */
    public function get_icon() {
        return 'fas fa-scroll';
    }

    /**
     * Get element keywords
     */
    public function get_keywords() {
        return ['marquee', 'slider', 'scroll', 'ticker', 'news'];
    }

    /**
     * Set element control groups
     */
    public function set_control_groups() {
        $this->control_groups['content'] = [
            'title' => __('Content', 'marqueex'),
            'tab' => 'content',
        ];

        $this->control_groups['settings'] = [
            'title' => __('Settings', 'marqueex'),
            'tab' => 'content',
        ];

        $this->control_groups['style'] = [
            'title' => __('Style', 'marqueex'),
            'tab' => 'style',
        ];
    }

    /**
     * Set element controls
     */
    public function set_controls() {
        // Content Controls
        $this->controls['slider_content'] = [
            'group' => 'content',
            'label' => __('Slider Content', 'marqueex'),
            'type' => 'textarea',
            'default' => __('Your marquee content here...', 'marqueex'),
            'description' => __('Enter the content that will scroll in the marquee.', 'marqueex'),
        ];

        // Settings Controls
        $this->controls['speed'] = [
            'group' => 'settings',
            'label' => __('Speed', 'marqueex'),
            'type' => 'number',
            'min' => 10,
            'max' => 100,
            'default' => 30,
            'unit' => 'px/s',
        ];

        $this->controls['direction'] = [
            'group' => 'settings',
            'label' => __('Direction', 'marqueex'),
            'type' => 'select',
            'default' => 'left',
            'options' => [
                'left' => __('Left', 'marqueex'),
                'right' => __('Right', 'marqueex'),
                'up' => __('Up', 'marqueex'),
                'down' => __('Down', 'marqueex'),
            ],
        ];

        $this->controls['pause_on_hover'] = [
            'group' => 'settings',
            'label' => __('Pause on Hover', 'marqueex'),
            'type' => 'checkbox',
            'default' => true,
        ];

        // Style Controls
        $this->controls['background_color'] = [
            'group' => 'style',
            'label' => __('Background Color', 'marqueex'),
            'type' => 'color',
            'css' => [
                [
                    'property' => 'background-color',
                    'selector' => '.marqueex-slider',
                ],
            ],
        ];

        $this->controls['text_color'] = [
            'group' => 'style',
            'label' => __('Text Color', 'marqueex'),
            'type' => 'color',
            'css' => [
                [
                    'property' => 'color',
                    'selector' => '.marqueex-slider',
                ],
            ],
        ];

        $this->controls['typography'] = [
            'group' => 'style',
            'label' => __('Typography', 'marqueex'),
            'type' => 'typography',
            'css' => [
                [
                    'property' => 'font-family',
                    'selector' => '.marqueex-slider',
                ],
                [
                    'property' => 'font-size',
                    'selector' => '.marqueex-slider',
                ],
                [
                    'property' => 'font-weight',
                    'selector' => '.marqueex-slider',
                ],
            ],
        ];

        $this->controls['padding'] = [
            'group' => 'style',
            'label' => __('Padding', 'marqueex'),
            'type' => 'spacing',
            'css' => [
                [
                    'property' => 'padding',
                    'selector' => '.marqueex-slider',
                ],
            ],
        ];
    }

    /**
     * Render element output
     */
    public function render() {
        $settings = $this->settings;

        $unique_id = 'marqueex-' . $this->id;
        $speed = $settings['speed'] ?? 30;
        $direction = $settings['direction'] ?? 'left';
        $pause_on_hover = $settings['pause_on_hover'] ?? true;
        $content = $settings['slider_content'] ?? __('Your marquee content here...', 'marqueex');

        $this->set_attribute('_root', 'id', $unique_id);
        $this->set_attribute('_root', 'class', 'marqueex-slider');
        $this->set_attribute('_root', 'data-speed', $speed);
        $this->set_attribute('_root', 'data-direction', $direction);
        $this->set_attribute('_root', 'data-hover-paused-enabled', $pause_on_hover ? 'true' : 'false');

        echo "<div {$this->render_attributes('_root')}>";
        echo wp_kses_post($content);
        echo "</div>";
    }
}
