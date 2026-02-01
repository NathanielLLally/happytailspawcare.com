<?php

namespace Wpxero\Marqueex\Traits\AnimatedWordRoller;

use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Text_Stroke;

if (!defined('ABSPATH')) {
    exit;
}

trait StyleControls {
    
    private function register_style_section_controls() {
        $this->start_controls_section(
            'marqueex_word_roller_style_section',
            [
                'label' => __('Content', 'marqueex'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $text_blocks = [
            [
                'label' => __('Title', 'marqueex'),
                'heading' => 'marqueex_word_roller_title_heading',
                'selector' => '{{WRAPPER}} .marqueex-fixed-text',
                'typography' => 'marqueex_word_roller_title_typography',
                'color' => 'marqueex_word_roller_title_color',
                'text_stroke' => 'marqueex_word_roller_title_text_stroke',
            ],
            [
                'label' => __('Animated Words', 'marqueex'),
                'heading' => 'marqueex_word_roller_animated_words_heading',
                'selector' => '{{WRAPPER}} .marqueex-rotating-word',
                'typography' => 'marqueex_word_roller_animated_words_typography',
                'color' => 'marqueex_word_roller_animated_words_color',
                'text_stroke' => 'marqueex_word_roller_animated_words_text_stroke',
            ],
        ];

        foreach ($text_blocks as $block) {
            $this->add_control(
                $block['heading'],
                [
                    'label' => $block['label'],
                    'type' => Controls_Manager::HEADING,
                    'separator' => 'before',
                ]
            );

            $this->add_group_control(
                Group_Control_Typography::get_type(),
                [
                    'name' => $block['typography'],
                    'selector' => $block['selector'],
                ]
            );

            $this->add_control(
                $block['color'],
                [
                    'label' => __('Color', 'marqueex'),
                    'type' => Controls_Manager::COLOR,
                    'selectors' => [
                        $block['selector'] => 'color: {{VALUE}}',
                    ],
                ]
            );

            $this->add_group_control(
                Group_Control_Text_Stroke::get_type(),
                [
                    'name' => $block['text_stroke'],
                    'selector' => $block['selector'],
                ]
            );
        }

        // Icon Styles
        $this->add_control(
            'marqueex_word_roller_icons_heading',
            [
                'label' => __('Icons', 'marqueex'),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'marqueex_word_roller_icons_size',
            [
                'label' => __('Size', 'marqueex'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'range' => [
                    'px' => ['min' => 3, 'max' => 100, 'step' => 1],
                ],
                'default' => ['unit' => 'px', 'size' => 16],
                'selectors' => [
                    '{{WRAPPER}} .marqueex-rotate-text svg' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .marqueex-rotate-text i' => 'font-size: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'marqueex_word_roller_gap',
            [
                'label' => __('Gap', 'marqueex'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'range' => [
                    'px' => ['min' => 3, 'max' => 100, 'step' => 1],
                ],
                'default' => ['unit' => 'px', 'size' => 16],
                'selectors' => [
                    '{{WRAPPER}} .marqueex-rotate-text' => 'gap: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'marqueex_word_roller_icons_color',
            [
                'label' => __('Color', 'marqueex'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .marqueex-rotate-text i' => 'color: {{VALUE}}',
                    '{{WRAPPER}} .marqueex-rotate-text path' => 'fill: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_section();
    }
}
