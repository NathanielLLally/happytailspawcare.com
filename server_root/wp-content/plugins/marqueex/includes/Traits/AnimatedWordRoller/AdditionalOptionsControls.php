<?php

namespace Wpxero\Marqueex\Traits\AnimatedWordRoller;

use Elementor\Controls_Manager;

if (!defined('ABSPATH')) {
    exit;
}

trait AdditionalOptionsControls {
    
    private function register_additional_options_section_controls() {
        $this->start_controls_section(
            'marqueex_word_roller_additional_section',
            [
                'label' => __('Additional Options', 'marqueex'),
                'tab' => Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'marqueex_word_roller_delay',
            [
                'label' => __('Active Word Duration', 'marqueex'),
                'description' => __('Time each word stays active (in seconds).', 'marqueex'),
                'type' => Controls_Manager::NUMBER,
                'min' => 1,
                'max' => 10,
                'step' => 1,
                'default' => 1,
            ]
        );

        $this->add_control(
            'marqueex_word_roller_visible_words',
            [
                'label' => __('Visible Words', 'marqueex'),
                'type' => Controls_Manager::NUMBER,
                'min' => 1,
                'max' => 50,
                'step' => 1,
                'default' => 5,
            ]
        );

        // Check if MarqueeX Pro is active
        $is_pro_active = class_exists('Wpxero\MarqueexPro\Plugin');

        $animation_options = [
            'vertical' => __('Vertical Scroll', 'marqueex'),
        ];

        // Add Pro features if Pro plugin is active
        if ($is_pro_active) {
            $animation_options = array_merge($animation_options, [
                'horizontal' => __('Horizontal Scroll', 'marqueex'),
                'fade' => __('Fade', 'marqueex'),
                'slide' => __('Slide', 'marqueex'),
                'zoom' => __('Zoom', 'marqueex'),
            ]);
        }

        $this->add_control(
            'marqueex_word_roller_animation_type',
            [
                'label' => __('Animation Type', 'marqueex'),
                'type' => Controls_Manager::SELECT,
                'default' => 'vertical',
                'options' => $animation_options,
            ]
        );

        // Add Pro upgrade notice if Pro is not active
        if (!$is_pro_active) {
            $this->add_control(
                'marqueex_word_roller_pro_notice',
                [
                    'type' => Controls_Manager::RAW_HTML,
                    'raw' => '<div style="background: #f8f9fa; border: 1px solid #e9ecef; border-radius: 4px; padding: 15px; margin: 10px 0;">
                        <h4 style="margin: 0 0 10px 0; color: #495057;">🚀 Unlock More Animation Types</h4>
                        <p style="margin: 0 0 10px 0; color: #6c757d; font-size: 13px;">Get access to Horizontal Scroll, Fade, Slide, and Zoom animations with MarqueeX Pro.</p>
                        <a href="https://wpxero.com/marqueex-pro/" target="_blank" style="display: inline-block; background: #007cba; color: white; padding: 8px 16px; text-decoration: none; border-radius: 3px; font-size: 12px;">Upgrade to Pro</a>
                    </div>',
                ]
            );
        }

        $this->add_control(
            'marqueex_word_roller_pause_on_hover',
            [
                'label' => __('Pause on Hover', 'marqueex'),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => __('Yes', 'marqueex'),
                'label_off' => __('No', 'marqueex'),
                'return_value' => 'yes',
                'default' => 'no',
            ]
        );

        $this->add_control(
            'marqueex_word_roller_notice',
            [
                'type' => Controls_Manager::ALERT,
                'alert_type' => 'warning',
                'heading' => __('Visible Words Notice', 'marqueex'),
                'content' => __('Please make sure to select an odd number that is less than or equal to the total number of words in your list.', 'marqueex'),
            ]
        );

        $this->end_controls_section();
    }
}
