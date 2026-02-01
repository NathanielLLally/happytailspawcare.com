<?php

namespace Wpxero\Marqueex\Traits\AnimatedHeading;

use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Text_Stroke;
use Elementor\Group_Control_Text_Shadow;

if (!defined('ABSPATH')) {
    exit;
}

trait TextStylesControls {

    private function add_text_style_controls($section_id, $label, $selector_prefix, $text_field_name) {
        $this->start_controls_section(
            $section_id,
            [
                'label' => esc_html($label, 'marqueex'),
                'tab' => Controls_Manager::TAB_STYLE,
                'condition' => [
                    $text_field_name . '!' => '',
                ],
            ]
        );

        // Font color for before/after text
        if (in_array($section_id, ['marqueex_before_text_style', 'marqueex_after_text_style'])) {
            $this->add_control(
                "{$section_id}_color",
                [
                    'label' => __('Font Color', 'marqueex'),
                    'type' => Controls_Manager::COLOR,
                    'selectors' => [
                        "{{WRAPPER}} {$selector_prefix}" => 'color: {{VALUE}};',
                    ],
                ]
            );
        }

        // Typography
        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => "{$section_id}_typography",
                'label' => __('Typography', 'marqueex'),
                'selector' => "{{WRAPPER}} {$selector_prefix}",
                'condition' => [
                    $text_field_name . '!' => '',
                ],
            ]
        );

        // Text Stroke (popover for before/after text)
        if (in_array($section_id, ['marqueex_before_text_style', 'marqueex_after_text_style'])) {
            $this->add_group_control(
                Group_Control_Text_Stroke::get_type(),
                [
                    'name' => "{$section_id}_stroke",
                    'selector' => "{{WRAPPER}} {$selector_prefix}"
                ]
            );
        }

        // Text shadow
        $this->add_group_control(
            Group_Control_Text_Shadow::get_type(),
            [
                'name' => "{$section_id}_shadow",
                'label' => __('Text Shadow', 'marqueex'),
                'selector' => "{{WRAPPER}} {$selector_prefix}",
                'condition' => [
                    $text_field_name . '!' => '',
                ],
            ]
        );

        $this->end_controls_section();
    }

    private function register_text_styles_section_controls() {
        $this->add_text_style_controls(
            'marqueex_before_text_style',
            'Before Text',
            '.marqueex-heading .marqueex-before-text',
            'marqueex_before_text'
        );

        $this->add_text_style_controls(
            'animated_text_style',
            'Animated Text',
            '.marqueex-heading .marqueex-texts-wrapper span',
            'marqueex_animated_texts'
        );

        $this->add_text_style_controls(
            'marqueex_after_text_style',
            'After Text',
            '.marqueex-heading .marqueex-after-text',
            'marqueex_after_text'
        );
    }
}
