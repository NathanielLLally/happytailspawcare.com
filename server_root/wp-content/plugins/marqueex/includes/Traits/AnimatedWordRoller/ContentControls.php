<?php

namespace Wpxero\Marqueex\Traits\AnimatedWordRoller;

use Elementor\Controls_Manager;
use Elementor\Repeater;

if (!defined('ABSPATH')) {
    exit;
}

trait ContentControls {

    private function register_content_section_controls() {
        $this->start_controls_section(
            'marqueex_word_roller_content_section',
            [
                'label' => __('Content', 'marqueex'),
                'tab' => Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'marqueex_word_roller_title',
            [
                'label' => __('Title', 'marqueex'),
                'type' => Controls_Manager::TEXT,
                'default' => __('The Ultimate Solution for', 'marqueex'),
                'placeholder' => __('Type your title here', 'marqueex'),
                'label_block' => true,
            ]
        );

        $word_repeater = new Repeater();

        $word_repeater->add_control(
            'marqueex_word_roller_text',
            [
                'label' => __('Word', 'marqueex'),
                'type' => Controls_Manager::TEXT,
                'default' => __('Default title', 'marqueex'),
                'placeholder' => __('Type your word here', 'marqueex'),
                'label_block' => true,
            ]
        );

        $word_repeater->add_control(
            'marqueex_word_roller_icon',
            [
                'label' => __('Text Icon', 'marqueex'),
                'type' => Controls_Manager::ICONS,
                'label_block' => true,
                'skin' => 'inline',
                'default' => [
                    'value' => 'fas fa-heart',
                    'library' => 'fa-solid',
                ],
            ]
        );

        $this->add_control(
            'marqueex_word_roller_words',
            [
                'label' => __('Word Lists', 'marqueex'),
                'type' => Controls_Manager::REPEATER,
                'fields' => $word_repeater->get_controls(),
                'default' => [
                    [
                        'marqueex_word_roller_text' => __('Developers', 'marqueex'),
                    ],
                    [
                        'marqueex_word_roller_text' => __('Designers', 'marqueex'),
                    ],
                    [
                        'marqueex_word_roller_text' => __('Legal Experts', 'marqueex'),
                    ],
                    [
                        'marqueex_word_roller_text' => __('Doctors', 'marqueex'),
                    ],
                    [
                        'marqueex_word_roller_text' => __('Teachers', 'marqueex'),
                    ],
                    [
                        'marqueex_word_roller_text' => __('Attorneys', 'marqueex'),
                    ],
                ],
                'title_field' => '{{{ marqueex_word_roller_text }}}',
            ]
        );

        $this->add_control(
            'marqueex_word_roller_html_tag',
            [
                'label' => __('Title HTML Tag', 'marqueex'),
                'type' => Controls_Manager::SELECT,
                'default' => 'h2',
                'options' => [
                    'h1' => __('H1', 'marqueex'),
                    'h2' => __('H2', 'marqueex'),
                    'h3' => __('H3', 'marqueex'),
                    'h4' => __('H4', 'marqueex'),
                    'h5' => __('H5', 'marqueex'),
                    'h6' => __('H6', 'marqueex'),
                    'div' => __('div', 'marqueex'),
                ]
            ]
        );

        $this->add_control(
            'marqueex_word_roller_alignment',
            [
                'label' => __('Alignment', 'marqueex'),
                'type' => Controls_Manager::CHOOSE,
                'options' => [
                    'left' => [
                        'title' => __('Left', 'marqueex'),
                        'icon' => 'eicon-text-align-left',
                    ],
                    'center' => [
                        'title' => __('Center', 'marqueex'),
                        'icon' => 'eicon-text-align-center',
                    ],
                    'right' => [
                        'title' => __('Right', 'marqueex'),
                        'icon' => 'eicon-text-align-right',
                    ],
                ],
                'default' => 'center',
                'toggle' => true,
                'selectors' => [
                    '{{WRAPPER}} .marqueex-word-roller-container' => 'justify-content: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_section();
    }
}
