<?php

namespace Wpxero\Marqueex\Traits\AnimatedHeading;

use Elementor\Controls_Manager;
use Elementor\Group_Control_Background;

if (!defined('ABSPATH')) {
    exit;
}

trait AnimatedTextEffectControls {

    private function register_animated_text_effect_section_controls() {
        $this->start_controls_section(
            'marqueex_animated_text_effect_section',
            [
                'label' => __('Animated Text Effect', 'marqueex'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        // Check if MarqueeX Pro is active
        $is_pro_active = class_exists('Wpxero\MarqueexPro\Plugin');

        $effect_options = [
            'fill' => __('Fill', 'marqueex'),
            'outline' => __('Outline', 'marqueex'),
        ];

        // Add Pro features if Pro plugin is active
        if ($is_pro_active) {
            $effect_options = array_merge($effect_options, [
                'marqueex-gradient-text' => __('Gradient', 'marqueex'),
                'marqueex-masked-text' => __('Image Masking', 'marqueex'),
            ]);
        }

        // Select control: effect type
        $this->add_control(
            'marqueex_animated_text_effect_type',
            [
                'label' => __('Effect Type', 'marqueex'),
                'type' => Controls_Manager::SELECT,
                'default' => 'fill',
                'options' => $effect_options,
            ]
        );

        // Add Pro upgrade notice for text effects if Pro is not active
        // if (!$is_pro_active) {
        //     $this->add_control(
        //         'marqueex_text_effect_pro_notice',
        //         [
        //             'type' => Controls_Manager::RAW_HTML,
        //             'raw' => '<div style="background: #f8f9fa; border: 1px solid #e9ecef; border-radius: 4px; padding: 15px; margin: 10px 0;">
        //                 <h4 style="margin: 0 0 10px 0; color: #495057;">✨ Unlock Advanced Text Effects</h4>
        //                 <p style="margin: 0 0 10px 0; color: #6c757d; font-size: 13px;">Get access to Gradient Text and Image Masking effects with MarqueeX Pro.</p>
        //                 <a href="https://wpxero.com/marqueex-pro/" target="_blank" style="display: inline-block; background: #007cba; color: white; padding: 8px 16px; text-decoration: none; border-radius: 3px; font-size: 12px;">Upgrade to Pro</a>
        //             </div>',
        //         ]
        //     );
        // }

        // Fill: Text Color
        $this->add_control(
            'marqueex_animated_text_fill_color',
            [
                'label' => __('Text Color', 'marqueex'),
                'type' => Controls_Manager::COLOR,
                'condition' => [
                    'marqueex_animated_text_effect_type' => 'fill',
                ],
                'selectors' => [
                    '{{WRAPPER}} .marqueex-heading .marqueex-texts-wrapper span' => 'color: {{VALUE}};',
                ],
            ]
        );

        // Outline: Stroke color and width
        $this->add_control(
            'marqueex_animated_text_outline_color',
            [
                'label' => __('Outline Color', 'marqueex'),
                'type' => Controls_Manager::COLOR,
                'condition' => [
                    'marqueex_animated_text_effect_type' => 'outline',
                ],
                'selectors' => [
                    '{{WRAPPER}} .marqueex-heading .marqueex-texts-wrapper span' => '-webkit-text-stroke-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'marqueex_animated_text_outline_width',
            [
                'label' => __('Outline Width (px)', 'marqueex'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'default' => [
                    'size' => 2,
                    'unit' => 'px',
                ],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 10,
                    ],
                ],
                'condition' => [
                    'marqueex_animated_text_effect_type' => 'outline',
                ],
                'selectors' => [
                    '{{WRAPPER}} .marqueex-heading .marqueex-texts-wrapper span' => '-webkit-text-stroke-width: {{SIZE}}{{UNIT}}; color: transparent;',
                ],
            ]
        );

        // Gradient text effect
        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name' => 'marqueex_animated_text_gradient',
                'label' => __('Gradient Text', 'marqueex'),
                'types' => ['gradient'],
                'condition' => [
                    'marqueex_animated_text_effect_type' => 'marqueex-gradient-text',
                ],
                'selector' => '{{WRAPPER}} .marqueex-heading .marqueex-texts-wrapper span',
            ]
        );

        // Image masking effect
        $this->add_control(
            'marqueex_animated_text_image_mask',
            [
                'label' => __('Mask Image', 'marqueex'),
                'type' => Controls_Manager::MEDIA,
                'default' => [
                    'url' => \Elementor\Utils::get_placeholder_image_src(),
                ],
                'condition' => [
                    'marqueex_animated_text_effect_type' => 'marqueex-masked-text',
                ],
                'selectors' => [
                    '{{WRAPPER}} .marqueex-heading .marqueex-texts-wrapper span' => 'background-image: url({{URL}});',
                ],
            ]
        );

        $this->add_control(
            'marqueex_animated_text_image_mask_size',
            [
                'label' => __('Mask Size', 'marqueex'),
                'type' => Controls_Manager::SELECT,
                'default' => 'cover',
                'options' => [
                    'auto' => __('Auto', 'marqueex'),
                    'cover' => __('Cover', 'marqueex'),
                    'contain' => __('Contain', 'marqueex'),
                ],
                'condition' => [
                    'marqueex_animated_text_effect_type' => 'marqueex-masked-text',
                ],
                'selectors' => [
                    '{{WRAPPER}} .marqueex-heading .marqueex-texts-wrapper span' => 'background-size: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'marqueex_animated_text_image_mask_position',
            [
                'label' => __('Mask Position', 'marqueex'),
                'type' => Controls_Manager::SELECT,
                'default' => 'center center',
                'options' => [
                    'left top' => __('Top Left', 'marqueex'),
                    'center top' => __('Top Center', 'marqueex'),
                    'right top' => __('Top Right', 'marqueex'),
                    'left center' => __('Center Left', 'marqueex'),
                    'center center' => __('Center Center', 'marqueex'),
                    'right center' => __('Center Right', 'marqueex'),
                    'left bottom' => __('Bottom Left', 'marqueex'),
                    'center bottom' => __('Bottom Center', 'marqueex'),
                    'right bottom' => __('Bottom Right', 'marqueex'),
                ],
                'condition' => [
                    'marqueex_animated_text_effect_type' => 'marqueex-masked-text',
                ],
                'selectors' => [
                    '{{WRAPPER}} .marqueex-heading .marqueex-texts-wrapper span' => 'background-position: {{VALUE}};',
                ],
            ]
        );

        $this->add_control('marqueex_cursor_color', [
            'label' => __('Cursor Color', 'marqueex'),
            'type' => Controls_Manager::COLOR,
            'condition' => [
                'marqueex_animation_type' => 'typing',
            ],
            'default' => '#000000',
            'selectors' => [
                '{{WRAPPER}} .typing .marqueex-texts-wrapper' => 'border-color: {{VALUE}};',
            ],
        ]);

        $this->add_control('marqueex_cursor_width', [
            'label' => __('Cursor Width', 'marqueex'),
            'type' => Controls_Manager::SLIDER,
            'condition' => [
                'marqueex_animation_type' => 'typing',
            ],
            'size_units' => ['px'],
            'range' => [
                'px' => [
                    'min' => 1,
                    'max' => 10,
                    'step' => 0.1,
                ],
            ],
            'default' => [
                'unit' => 'px',
                'size' => 2,
            ],
            'selectors' => [
                '{{WRAPPER}} .typing .marqueex-texts-wrapper' => 'border-width: {{SIZE}}{{UNIT}};',
            ],
        ]);

        $this->add_control('marqueex_line_color', [
            'label' => __('Line Color', 'marqueex'),
            'type' => Controls_Manager::COLOR,
            'condition' => [
                'marqueex_animation_type' => 'line',
            ],
            'default' => '#000000',
            'selectors' => [
                '{{WRAPPER}} .line .marqueex-animated-lines path' => 'stroke: {{VALUE}};',
            ],
        ]);

        $this->add_control('marqueex_line_width', [
            'label' => __('Line Width', 'marqueex'),
            'type' => Controls_Manager::SLIDER,
            'condition' => [
                'marqueex_animation_type' => 'line',
            ],
            'range' => [
                'px' => [
                    'min' => 1,
                    'max' => 100,
                    'step' => 1,
                ],
            ],
            'default' => [
                'size' => 10,
            ],
            'selectors' => [
                '{{WRAPPER}} .line .marqueex-animated-lines path' => 'stroke-width: {{SIZE}};',
            ],
        ]);

        $this->end_controls_section();
    }
}
