<?php

namespace Wpxero\Marqueex\Traits\AnimatedHeading;

use Elementor\Controls_Manager;
use Elementor\Repeater;

if (!defined('ABSPATH')) {
    exit;
}

trait TitleControls {
    
    private function register_title_section_controls() {
        $this->start_controls_section(
            'marqueex_title_section',
            [
                'label' => __('Title', 'marqueex'),
                'tab' => Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control('marqueex_before_text', [
            'label' => __('Before Text', 'marqueex'),
            'type' => Controls_Manager::TEXT,
            'label_block' => true,
            'default' => __('Before text', 'marqueex'),
        ]);

        $repeater = new Repeater();
        $repeater->add_control('marqueex_animated_text', [
            'label' => __('Text', 'marqueex'),
            'type' => Controls_Manager::TEXT,
            'default' => __('Animated Text', 'marqueex'),
            'label_block' => true,
        ]);

        $this->add_control('marqueex_animated_texts', [
            'label' => __('Animated Texts', 'marqueex'),
            'type' => Controls_Manager::REPEATER,
            'fields' => $repeater->get_controls(),
            'default' => [
                ['marqueex_animated_text' => 'Animated Text'],
            ],
            'title_field' => '{{{ marqueex_animated_text }}}',
        ]);

        $this->add_control('marqueex_after_text', [
            'label' => __('After Text', 'marqueex'),
            'type' => Controls_Manager::TEXT,
            'label_block' => true,
            'default' => __('After text', 'marqueex'),
        ]);

        $this->add_responsive_control('marqueex_text_alignment', [
            'label' => __('Alignment', 'marqueex'),
            'type' => Controls_Manager::CHOOSE,
            'options' => [
                'start' => [
                    'title' => __('Left', 'marqueex'),
                    'icon' => 'eicon-text-align-left',
                ],
                'center' => [
                    'title' => __('Center', 'marqueex'),
                    'icon' => 'eicon-text-align-center',
                ],
                'end' => [
                    'title' => __('Right', 'marqueex'),
                    'icon' => 'eicon-text-align-right',
                ],
            ],
            'default' => 'start',
            'toggle' => true,
            'selectors' => [
                '{{WRAPPER}} .marqueex-heading' => 'justify-content: {{VALUE}};',
                '{{WRAPPER}} .marqueex-texts-wrapper' => 'justify-content: {{VALUE}};',
                '{{WRAPPER}} .marqueex-before-text' => 'text-align: {{VALUE}};',
                '{{WRAPPER}} .marqueex-after-text' => 'text-align: {{VALUE}};',
            ],
        ]);

        $this->add_control('marqueex_heading_tag', [
            'label' => __('HTML Tag', 'marqueex'),
            'type' => Controls_Manager::SELECT,
            'default' => 'h2',
            'options' => [
                'h1' => 'H1',
                'h2' => 'H2',
                'h3' => 'H3',
                'h4' => 'H4',
                'h5' => 'H5',
                'h6' => 'H6',
                'div' => 'div',
                'span' => 'span',
                'p' => 'p',
            ],
        ]);

        $this->end_controls_section();
    }
}
