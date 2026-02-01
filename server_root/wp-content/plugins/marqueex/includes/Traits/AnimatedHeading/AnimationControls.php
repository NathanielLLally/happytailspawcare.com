<?php

namespace Wpxero\Marqueex\Traits\AnimatedHeading;

use Elementor\Controls_Manager;

if (!defined('ABSPATH')) {
    exit;
}

trait AnimationControls {

    private function register_animation_section_controls() {
        $this->start_controls_section(
            'marqueex_animation_section',
            [
                'label' => __('Animation Effect', 'marqueex'),
                'tab' => Controls_Manager::TAB_CONTENT,
            ]
        );


        // Check if MarqueeX Pro is active
        $is_pro_active = class_exists('Wpxero\MarqueexPro\Plugin');

        $animation_options = [
            'slide' => __('Slide', 'marqueex'),
            'slide-horizontal' => __('Slide Horizontal', 'marqueex'),
            'typing' => __('Typing', 'marqueex'),
        ];

        // Add Pro features if Pro plugin is active
        if ($is_pro_active) {
            $animation_options = array_merge($animation_options, [
                'rotation-3d' => __('3D Rotation', 'marqueex'),
                'construct' => __('Construct', 'marqueex'),
                'twist' => __('Twist', 'marqueex'),
                'line' => __('Line', 'marqueex'),
                'wave' => __('Wave', 'marqueex'),
                'swing' => __('Swing', 'marqueex'),
                'tilt' => __('Tilt', 'marqueex'),
                'lean' => __('Lean', 'marqueex'),
            ]);
        }

        $this->add_control('marqueex_animation_type', [
            'label' => __('Type', 'marqueex'),
            'type' => Controls_Manager::SELECT,
            'default' => 'slide',
            'options' => $animation_options,
        ]);

        // Add Pro upgrade notice if Pro is not active
        // if (!$is_pro_active) {
        //     $this->add_control(
        //         'marqueex_animation_pro_notice',
        //         [
        //             'type' => Controls_Manager::RAW_HTML,
        //             'raw' => '<div style="background: #f8f9fa; border: 1px solid #e9ecef; border-radius: 4px; padding: 15px; margin: 10px 0;">
        //                 <h4 style="margin: 0 0 10px 0; color: #495057;">🚀 Unlock More Animation Types</h4>
        //                 <p style="margin: 0 0 10px 0; color: #6c757d; font-size: 13px;">Get access to advanced animations like 3D Rotation, Construct, Twist, Line, Wave, Swing, Tilt, and Lean with MarqueeX Pro.</p>
        //                 <a href="https://wpxero.com/marqueex-pro/" target="_blank" style="display: inline-block; background: #007cba; color: white; padding: 8px 16px; text-decoration: none; border-radius: 3px; font-size: 12px;">Upgrade to Pro</a>
        //             </div>',
        //         ]
        //     );
        // }

        $this->add_control('marqueex_animation_speed', [
            'label' => __('Speed', 'marqueex'),
            'type' => Controls_Manager::NUMBER,
            'min' => 1,
            'max' => 1000,
            'step' => 1,
            'default' => 10,
        ]);

        $this->add_control('marqueex_pause_between_words', [
            'label' => __('Pause Between Words (sec)', 'marqueex'),
            'type' => Controls_Manager::NUMBER,
            'condition' => [
                'marqueex_animation_type' => 'construct'
            ],
            'min' => 0.1,
            'max' => 100,
            'step' => 0.1,
            'default' => 1,
        ]);

        $this->add_control('marqueex_pause_after_typed', [
            'label' => __('Pause After Typed (sec)', 'marqueex'),
            'type' => Controls_Manager::NUMBER,
            'condition' => [
                'marqueex_animation_type' => 'typing'
            ],
            'min' => 0.1,
            'max' => 100,
            'step' => 0.1,
            'default' => 1,
        ]);

        $this->add_control('marqueex_delay_per_word', [
            'label' => __('Delay Per Word (sec)', 'marqueex'),
            'type' => Controls_Manager::NUMBER,
            'condition' => [
                'marqueex_animation_type' => ['slide', 'slide-horizontal']
            ],
            'min' => 0.1,
            'max' => 100,
            'step' => 0.1,
            'default' => 1.5,
        ]);

        $this->add_control('marqueex_slide_vertical_direction', [
            'label' => __('Slide Direction', 'marqueex'),
            'type' => Controls_Manager::SELECT,
            'condition' => [
                'marqueex_animation_type' => 'slide',
            ],
            'default' => '1',
            'options' => [
                '1' => __('To Top', 'marqueex'),
                '-1' => __('To Bottom', 'marqueex'),
            ],
        ]);

        $this->add_control('marqueex_slide_horizontal_direction', [
            'label' => __('Slide Direction', 'marqueex'),
            'type' => Controls_Manager::SELECT,
            'condition' => [
                'marqueex_animation_type' => 'slide-horizontal',
            ],
            'default' => '1',
            'options' => [
                '1' => __('To Left', 'marqueex'),
                '-1' => __('To Right', 'marqueex'),
            ],
        ]);

        $this->add_control('marqueex_line_type', [
            'label' => __('Line Type', 'marqueex'),
            'type' => Controls_Manager::SELECT,
            'condition' => [
                'marqueex_animation_type' => 'line'
            ],
            'default' => 'singleUnderline',
            'options' => [
                'singleUnderline' => __('Single Underline', 'marqueex'),
                'doubleUnderline' => __('Double Underline', 'marqueex'),
                'wavyUnderline' => __('Wavy Underline', 'marqueex'),
                'underlineZigzag' => __('Zigzag Underline', 'marqueex'),
                'circle' => __('Circle', 'marqueex'),
            ],
        ]);

        $this->add_control('marqueex_delay_before_erase', [
            'label' => __('Delay Before Erase (sec)', 'marqueex'),
            'type' => Controls_Manager::NUMBER,
            'condition' => [
                'marqueex_animation_type' => 'line'
            ],
            'min' => 0.1,
            'max' => 100,
            'step' => 0.1,
            'default' => 1,
        ]);

        $this->add_control('marqueex_animation_pause_on_hover', [
            'label' => __('Pause on Hover', 'marqueex'),
            'type' => Controls_Manager::SWITCHER,
            'condition' => [
                'marqueex_animation_type!' => 'line'
            ],
            'label_on' => __('On', 'marqueex'),
            'label_off' => __('Off', 'marqueex'),
            'return_value' => 'yes',
            'default' => '',
        ]);

        $this->end_controls_section();
    }
}
