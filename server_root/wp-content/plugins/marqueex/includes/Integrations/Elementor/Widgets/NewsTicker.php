<?php

namespace Wpxero\Marqueex\Integrations\Elementor\Widgets;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Text_Shadow;
use Elementor\Icons_Manager;

if (!defined('ABSPATH')) {
    exit;
}

/**
 * MarqueeX News Ticker Widget for Elementor
 */
class NewsTicker extends Widget_Base {

    /**
     * Get widget name
     */
    public function get_name() {
        return 'marqueex-news-ticker';
    }

    /**
     * Get widget title
     */
    public function get_title() {
        return __('News Ticker', 'marqueex');
    }

    /**
     * Get widget icon
     */
    public function get_icon() {
        return 'eicon-posts-ticker eicon-marqueex';
    }

    /**
     * Get widget categories
     */
    public function get_categories() {
        return ['marqueex'];
    }

    /**
     * Enqueue widget scripts and styles
     */
    public function get_script_depends() {
        return ['marqueex-news-ticker'];
    }

    public function get_style_depends() {
        return ['marqueex-news-ticker'];
    }
    /**
     * Get widget keywords
     */
    public function get_keywords() {
        return ['news', 'ticker', 'marquee', 'posts', 'slider', 'scroll'];
    }

    /**
     * Get available post types
     */
    private function get_post_types() {
        $post_types = get_post_types(['public' => true, 'show_in_nav_menus' => true], 'objects');
        $post_types = wp_list_pluck($post_types, 'label', 'name');

        // Security: Filter out sensitive post types
        $excluded_types = ['elementor_library', 'attachment', 'revision', 'nav_menu_item'];
        return array_diff_key($post_types, array_flip($excluded_types));
    }

    /**
     * Get available post categories
     */
    private function get_post_categories() {
        $categories = get_categories(['hide_empty' => false]);
        $options = [];

        foreach ($categories as $category) {
            if ($category instanceof \WP_Term) {
                $options[intval($category->term_id)] = sanitize_text_field($category->name);
            }
        }

        return $options;
    }

    /**
     * Get query arguments for posts
     */
    private function get_query_args($settings = []) {
        $settings = wp_parse_args($settings, [
            'post_type' => 'post',
            'posts_per_page' => 6,
            'category_ids' => [],
            'orderby' => 'date',
            'order' => 'desc',
        ]);

        // Security: Sanitize and validate inputs
        $post_type = sanitize_text_field($settings['post_type']);
        $posts_per_page = min(50, max(1, intval($settings['posts_per_page']))); // Limit to prevent performance issues
        $orderby = in_array($settings['orderby'], ['date', 'title', 'rand', 'menu_order']) ? $settings['orderby'] : 'date';
        $order = strtoupper($settings['order']) === 'ASC' ? 'ASC' : 'DESC';

        $args = [
            'post_type' => $post_type,
            'post_status' => 'publish',
            'ignore_sticky_posts' => true,
            'posts_per_page' => $posts_per_page,
            'no_found_rows' => true, // Performance: Skip pagination queries
            'update_post_meta_cache' => false, // Performance: Skip meta cache if not needed
            'update_post_term_cache' => false, // Performance: Skip term cache if not needed
        ];

        // Order by & order
        if ('rand' === $orderby) {
            $args['orderby'] = 'rand';
        } else {
            $args['orderby'] = $orderby;
            $args['order'] = $order;
        }

        // Category filter with security validation
        if (!empty($settings['category_ids']) && $post_type === 'post') {
            $category_ids = array_map('intval', (array) $settings['category_ids']);
            $category_ids = array_filter($category_ids, function ($id) {
                return $id > 0 && term_exists($id, 'category');
            });

            if (!empty($category_ids)) {
                $args['category__in'] = $category_ids;
            }
        }

        return $args;
    }

    /**
     * Register widget controls
     */
    protected function register_controls() {
        // Content Type Section
        $this->start_controls_section(
            'content_type_section',
            [
                'label' => __('Content Type', 'marqueex'),
                'tab' => Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'content_type',
            [
                'label' => __('Content Type', 'marqueex'),
                'type' => Controls_Manager::SELECT,
                'default' => 'posts',
                'options' => [
                    'posts' => __('Dynamic Posts', 'marqueex'),
                    'custom' => __('Custom Text', 'marqueex'),
                ],
            ]
        );

        $this->add_control(
            'ticker_content_custom',
            [
                'label' => __('Ticker Content', 'marqueex'),
                'type' => Controls_Manager::REPEATER,
                'condition' => [
                    'content_type' => 'custom',
                ],
                'fields' => [
                    [
                        'name' => 'text',
                        'label' => __('Text', 'marqueex'),
                        'type' => Controls_Manager::TEXT,
                        'default' => __('Breaking news: Your news content here...', 'marqueex'),
                        'description' => __('Enter the news content that will scroll in the ticker.', 'marqueex'),
                    ],
                    [
                        'name' => 'link',
                        'label' => __('Link', 'marqueex'),
                        'type' => Controls_Manager::URL,
                        'default' => [
                            'url' => '',
                            'is_external' => '',
                            'nofollow' => '',
                        ],
                        'description' => __('Enter the link for the news content.', 'marqueex'),
                    ],
                ],
                'condition' => [
                    'content_type' => 'custom',
                ],
                'title_field' => '{{{ text }}}',
                'default' => [
                    [
                        'text' => __('Breaking news: Your first news content here...', 'marqueex'),
                        'link' => [
                            'url' => '',
                            'is_external' => '',
                            'nofollow' => '',
                        ],
                    ],
                    [
                        'text' => __('Latest update: Your second news content here...', 'marqueex'),
                        'link' => [
                            'url' => '',
                            'is_external' => '',
                            'nofollow' => '',
                        ],
                    ],
                    [
                        'text' => __('Important announcement: Your third news content here...', 'marqueex'),
                        'link' => [
                            'url' => '',
                            'is_external' => '',
                            'nofollow' => '',
                        ],
                    ],
                ],
            ]
        );

        $this->end_controls_section();

        // Query Section
        $this->start_controls_section(
            'query_section',
            [
                'label' => __('Query', 'marqueex'),
                'tab' => Controls_Manager::TAB_CONTENT,
                'condition' => [
                    'content_type' => 'posts',
                ],
            ]
        );

        $this->add_control(
            'post_type',
            [
                'label' => __('Post Type', 'marqueex'),
                'type' => Controls_Manager::SELECT,
                'default' => 'post',
                'options' => $this->get_post_types(),
            ]
        );

        $this->add_control(
            'posts_per_page',
            [
                'label' => __('Number of Posts', 'marqueex'),
                'type' => Controls_Manager::NUMBER,
                'default' => 6,
                'min' => 1,
                'max' => 50,
            ]
        );

        $this->add_control(
            'orderby',
            [
                'label' => __('Order By', 'marqueex'),
                'type' => Controls_Manager::SELECT,
                'default' => 'date',
                'options' => [
                    'date' => __('Date', 'marqueex'),
                    'title' => __('Title', 'marqueex'),
                    'rand' => __('Random', 'marqueex'),
                    'menu_order' => __('Menu Order', 'marqueex'),
                ],
            ]
        );

        $this->add_control(
            'order',
            [
                'label' => __('Order', 'marqueex'),
                'type' => Controls_Manager::SELECT,
                'default' => 'desc',
                'options' => [
                    'asc' => __('ASC', 'marqueex'),
                    'desc' => __('DESC', 'marqueex'),
                ],
                'condition' => [
                    'orderby!' => 'rand',
                ],
            ]
        );

        $this->add_control(
            'category_ids',
            [
                'label' => __('Categories', 'marqueex'),
                'type' => Controls_Manager::SELECT2,
                'multiple' => true,
                'options' => $this->get_post_categories(),
                'condition' => [
                    'post_type' => 'post',
                ],
            ]
        );

        $this->end_controls_section();

        // Label Section
        $this->start_controls_section(
            'label_section',
            [
                'label' => __('Label', 'marqueex'),
                'tab' => Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'show_label',
            [
                'label' => __('Show Label', 'marqueex'),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => __('Yes', 'marqueex'),
                'label_off' => __('No', 'marqueex'),
                'return_value' => 'yes',
                'default' => 'yes',
            ]
        );

        $this->add_control(
            'label_text',
            [
                'label' => __('Label Text', 'marqueex'),
                'type' => Controls_Manager::TEXT,
                'default' => __('BREAKING', 'marqueex'),
                'condition' => [
                    'show_label' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'label_icon',
            [
                'label' => __('Label Icon', 'marqueex'),
                'type' => Controls_Manager::ICONS,
                'condition' => [
                    'show_label' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'label_heading_tag',
            [
                'label' => __('Label HTML Tag', 'marqueex'),
                'type' => Controls_Manager::SELECT,
                'default' => 'h4',
                'options' => [
                    'h1' => 'H1',
                    'h2' => 'H2',
                    'h3' => 'H3',
                    'h4' => 'H4',
                    'h5' => 'H5',
                    'h6' => 'H6',
                    'div' => 'div',
                    'span' => 'span',
                ],
                'condition' => [
                    'show_label' => 'yes',
                ],
            ]
        );

        $this->end_controls_section();

        // Content Options Section
        $this->start_controls_section(
            'content_options_section',
            [
                'label' => __('Content Options', 'marqueex'),
                'tab' => Controls_Manager::TAB_CONTENT,
                'condition' => [
                    'content_type' => 'posts',
                ],
            ]
        );

        $this->add_control(
            'title_trim_enable',
            [
                'label' => __('Trim Title', 'marqueex'),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => __('Yes', 'marqueex'),
                'label_off' => __('No', 'marqueex'),
                'return_value' => 'yes',
                'default' => 'no',
            ]
        );

        $this->add_control(
            'title_trim_length',
            [
                'label' => __('Title Length', 'marqueex'),
                'type' => Controls_Manager::NUMBER,
                'default' => 50,
                'min' => 1,
                'max' => 200,
                'condition' => [
                    'title_trim_enable' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'open_in_new_tab',
            [
                'label' => __('Open in New Tab', 'marqueex'),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => __('Yes', 'marqueex'),
                'label_off' => __('No', 'marqueex'),
                'return_value' => 'yes',
                'default' => 'no',
            ]
        );

        $this->end_controls_section();

        // Separator Section
        $this->start_controls_section(
            'separator_section',
            [
                'label' => __('Separator', 'marqueex'),
                'tab' => Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'separator_type',
            [
                'label' => __('Separator Type', 'marqueex'),
                'type' => Controls_Manager::SELECT,
                'default' => 'text',
                'options' => [
                    'none' => __('None', 'marqueex'),
                    'text' => __('Text', 'marqueex'),
                    'icon' => __('Icon', 'marqueex'),
                    // 'date' => __('Date', 'marqueex'),
                    // 'featured_image' => __('Featured Image', 'marqueex'),
                ],
            ]
        );

        // $this->add_control(
        //     'separator_style_preset',
        //     [
        //         'label' => __('Separator Style Preset', 'marqueex'),
        //         'type' => Controls_Manager::SELECT,
        //         'default' => 'modern',
        //         'options' => [
        //             'modern' => __('Modern Glass', 'marqueex'),
        //             'minimal' => __('Minimal Clean', 'marqueex'),
        //             'bold' => __('Bold Accent', 'marqueex'),
        //             'neon' => __('Neon Glow', 'marqueex'),
        //             'classic' => __('Classic Simple', 'marqueex'),
        //             'gradient' => __('Gradient Flow', 'marqueex'),
        //         ],
        //         'condition' => [
        //             'separator_type!' => 'none',
        //         ],
        //     ]
        // );

        $this->add_control(
            'separator_icon',
            [
                'label' => __('Separator Icon', 'marqueex'),
                'type' => Controls_Manager::ICONS,
                'default' => [
                    'value' => 'fas fa-circle',
                    'library' => 'fa-solid',
                ],
                'condition' => [
                    'separator_type' => 'icon',
                ],
            ]
        );

        $this->add_control(
            'separator_text',
            [
                'label' => __('Separator Text', 'marqueex'),
                'type' => Controls_Manager::TEXT,
                'default' => '•',
                'condition' => [
                    'separator_type' => 'text',
                ],
            ]
        );

        $this->add_responsive_control(
            'item_spacing',
            [
                'label' => __('Item Spacing', 'marqueex'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px', 'em', 'rem'],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 100,
                        'step' => 1,
                    ],
                    'em' => [
                        'min' => 0,
                        'max' => 10,
                        'step' => 0.1,
                    ],
                    'rem' => [
                        'min' => 0,
                        'max' => 10,
                        'step' => 0.1,
                    ],
                ],
                'default' => [
                    'unit' => 'rem',
                    'size' => 2,
                ],
                'selectors' => [
                    '{{WRAPPER}} .marqueex-marquee-track-wrapper' => 'gap: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .marqueex-marquee-track' => 'gap: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .marqueex-news-wrapper' => 'gap: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();

        // Marquee Settings Section
        $this->start_controls_section(
            'marquee_settings_section',
            [
                'label' => __('Marquee Settings', 'marqueex'),
                'tab' => Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'speed',
            [
                'label' => __('Speed', 'marqueex'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px/s'],
                'range' => [
                    'px' => [
                        'min' => 10,
                        'max' => 100,
                        'step' => 1,
                    ],
                ],
                'default' => [
                    'unit' => 'px',
                    'size' => 10,
                ],
            ]
        );

        $this->add_control(
            'pause_on_hover',
            [
                'label' => __('Pause on Hover', 'marqueex'),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => __('Yes', 'marqueex'),
                'label_off' => __('No', 'marqueex'),
                'return_value' => 'yes',
                'default' => 'yes',
            ]
        );

        $this->add_control(
            'reverse_direction',
            [
                'label' => __('Reverse Direction', 'marqueex'),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => __('Yes', 'marqueex'),
                'label_off' => __('No', 'marqueex'),
                'return_value' => 'yes',
                'default' => 'no',
            ]
        );

        $this->end_controls_section();

        // Container Style Section
        $this->start_controls_section(
            'container_style_section',
            [
                'label' => __('Container', 'marqueex'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name' => 'container_background',
                'label' => __('Background', 'marqueex'),
                'types' => ['classic', 'gradient'],
                'selector' => '{{WRAPPER}} .marqueex-marquee-block',
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'container_border',
                'label' => __('Border', 'marqueex'),
                'selector' => '{{WRAPPER}} .marqueex-marquee-block',
            ]
        );

        $this->add_responsive_control(
            'container_border_radius',
            [
                'label' => __('Border Radius', 'marqueex'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'selectors' => [
                    '{{WRAPPER}} .marqueex-marquee-block' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'container_box_shadow',
                'label' => __('Box Shadow', 'marqueex'),
                'selector' => '{{WRAPPER}} .marqueex-marquee-block',
            ]
        );

        $this->add_responsive_control(
            'container_padding',
            [
                'label' => __('Padding', 'marqueex'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'selectors' => [
                    '{{WRAPPER}} .marqueex-marquee-block' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'container_margin',
            [
                'label' => __('Margin', 'marqueex'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'selectors' => [
                    '{{WRAPPER}} .marqueex-marquee-block' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'container_height',
            [
                'label' => __('Height', 'marqueex'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px', 'vh', 'em'],
                'range' => [
                    'px' => [
                        'min' => 30,
                        'max' => 200,
                    ],
                    'vh' => [
                        'min' => 1,
                        'max' => 20,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .marqueex-marquee-block' => 'height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'container_overflow',
            [
                'label' => __('Overflow', 'marqueex'),
                'type' => Controls_Manager::SELECT,
                'default' => 'hidden',
                'options' => [
                    'visible' => __('Visible', 'marqueex'),
                    'hidden' => __('Hidden', 'marqueex'),
                    'scroll' => __('Scroll', 'marqueex'),
                    'auto' => __('Auto', 'marqueex'),
                ],
                'selectors' => [
                    '{{WRAPPER}} .marqueex-marquee-block' => 'overflow: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_section();

        // Text Style Section
        $this->start_controls_section(
            'text_style_section',
            [
                'label' => __('Text', 'marqueex'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );
        $this->start_controls_tabs(
            'style_tabs'
        );

        $this->start_controls_tab(
            'style_normal_tab',
            [
                'label' => esc_html__('Normal', 'textdomain'),
            ]
        );
        $this->add_control(
            'text_color',
            [
                'label' => __('Text Color', 'marqueex'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .marqueex-news-text' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .marqueex-title-link' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'text_typography',
                'selector' => '{{WRAPPER}} .marqueex-news-text, {{WRAPPER}} .marqueex-title-link',
            ]
        );

        $this->add_group_control(
            Group_Control_Text_Shadow::get_type(),
            [
                'name' => 'text_shadow',
                'selector' => '{{WRAPPER}} .marqueex-news-text, {{WRAPPER}} .marqueex-title-link',
            ]
        );

        $this->add_responsive_control(
            'text_spacing',
            [
                'label' => __('Letter Spacing', 'marqueex'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px', 'em'],
                'range' => [
                    'px' => [
                        'min' => -2,
                        'max' => 10,
                        'step' => 0.1,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .marqueex-news-text' => 'letter-spacing: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'word_spacing',
            [
                'label' => __('Word Spacing', 'marqueex'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px', 'em'],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 50,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .marqueex-news-text' => 'word-spacing: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_tab();
        $this->start_controls_tab(
            'link_normal_tab',
            [
                'label' => __('Link', 'marqueex'),
            ]
        );

        $this->add_control(
            'link_color',
            [
                'label' => __('Link Color', 'marqueex'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .marqueex-title-link' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'link_decoration',
            [
                'label' => __('Text Decoration', 'marqueex'),
                'type' => Controls_Manager::SELECT,
                'default' => 'none',
                'options' => [
                    'none' => __('None', 'marqueex'),
                    'underline' => __('Underline', 'marqueex'),
                    'overline' => __('Overline', 'marqueex'),
                    'line-through' => __('Line Through', 'marqueex'),
                ],
                'selectors' => [
                    '{{WRAPPER}} .marqueex-title-link' => 'text-decoration: {{VALUE}};',
                ],
            ]
        );
        $this->add_control(
            'link_hover_heading',
            [
                'label' => __('H O V E R', 'marqueex'),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );
        $this->add_control(
            'link_hover_color',
            [
                'label' => __('Color', 'marqueex'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .marqueex-title-link:hover' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'link_hover_decoration',
            [
                'label' => __('Text Decoration', 'marqueex'),
                'type' => Controls_Manager::SELECT,
                'default' => 'underline',
                'options' => [
                    'none' => __('None', 'marqueex'),
                    'underline' => __('Underline', 'marqueex'),
                    'overline' => __('Overline', 'marqueex'),
                    'line-through' => __('Line Through', 'marqueex'),
                ],
                'selectors' => [
                    '{{WRAPPER}} .marqueex-title-link:hover' => 'text-decoration: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'link_hover_transition',
            [
                'label' => __('Transition Duration', 'marqueex'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['ms', 's'],
                'range' => [
                    'ms' => [
                        'min' => 100,
                        'max' => 1000,
                        'step' => 50,
                    ],
                    's' => [
                        'min' => 0.1,
                        'max' => 2,
                        'step' => 0.1,
                    ],
                ],
                'default' => [
                    'unit' => 'ms',
                    'size' => 300,
                ],
                'selectors' => [
                    '{{WRAPPER}} .marqueex-title-link' => 'transition: all {{SIZE}}{{UNIT}} ease;',
                ],
            ]
        );
        $this->end_controls_tab();

        $this->end_controls_tabs();

        $this->end_controls_section();

        // // Link Style Section
        // $this->start_controls_section(
        //     'link_style_section',
        //     [
        //         'label' => __('Link', 'marqueex'),
        //         'tab' => Controls_Manager::TAB_STYLE,
        //     ]
        // );

        // $this->start_controls_tabs('link_style_tabs');



        // $this->end_controls_tab();

        // $this->start_controls_tab(
        //     'link_hover_tab',
        //     [
        //         'label' => __('Hover', 'marqueex'),
        //     ]
        // );



        // $this->end_controls_tab();

        // $this->end_controls_tabs();

        // $this->end_controls_section();

        // Separator Style Section
        $this->start_controls_section(
            'separator_style_section',
            [
                'label' => __('Separator', 'marqueex'),
                'tab' => Controls_Manager::TAB_STYLE,
                'condition' => [
                    'separator_type!' => 'none',
                ],
            ]
        );

        $this->add_control(
            'separator_color',
            [
                'label' => __('Separator Color', 'marqueex'),
                'type' => Controls_Manager::COLOR,
                'default' => '#333',
                'selectors' => [
                    '{{WRAPPER}} .marqueex-separator' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .marqueex-separator svg' => 'fill: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'separator_typography',
                'selector' => '{{WRAPPER}} .marqueex-separator',
                'condition' => [
                    'separator_type' => ['text', 'date'],
                ],
            ]
        );

        $this->add_responsive_control(
            'separator_size',
            [
                'label' => __('Icon Size', 'marqueex'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px', 'em'],
                'range' => [
                    'px' => [
                        'min' => 8,
                        'max' => 50,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .marqueex-separator-icon' => 'font-size: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .marqueex-separator-icon svg' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
                ],
                'condition' => [
                    'separator_type' => 'icon',
                ],
            ]
        );

        // $this->add_responsive_control(
        //     'separator_spacing',
        //     [
        //         'label' => __('Separator Spacing', 'marqueex'),
        //         'type' => Controls_Manager::SLIDER,
        //         'size_units' => ['px', 'em'],
        //         'range' => [
        //             'px' => [
        //                 'min' => 0,
        //                 'max' => 100,
        //             ],
        //         ],
        //         'selectors' => [
        //             '{{WRAPPER}} .marqueex-separator' => 'margin: 0 {{SIZE}}{{UNIT}};',
        //         ],
        //     ]
        // );

        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name' => 'separator_background',
                'label' => __('Background', 'marqueex'),
                'types' => ['classic', 'gradient'],
                'selector' => '{{WRAPPER}} .marqueex-separator',
                'fields_options' => [
                    'background' => [
                        'default' => 'classic',
                    ],
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'separator_border',
                'label' => __('Border', 'marqueex'),
                'selector' => '{{WRAPPER}} .marqueex-separator',
            ]
        );

        $this->add_responsive_control(
            'separator_border_radius',
            [
                'label' => __('Border Radius', 'marqueex'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'selectors' => [
                    '{{WRAPPER}} .marqueex-separator' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'separator_box_shadow',
                'label' => __('Box Shadow', 'marqueex'),
                'selector' => '{{WRAPPER}} .marqueex-separator',
            ]
        );

        $this->add_group_control(
            Group_Control_Text_Shadow::get_type(),
            [
                'name' => 'separator_text_shadow',
                'label' => __('Text Shadow', 'marqueex'),
                'selector' => '{{WRAPPER}} .marqueex-separator',
                'condition' => [
                    'separator_type' => ['text', 'date'],
                ],
            ]
        );

        $this->add_responsive_control(
            'separator_padding',
            [
                'label' => __('Padding', 'marqueex'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'selectors' => [
                    '{{WRAPPER}} .marqueex-separator' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'separator_opacity',
            [
                'label' => __('Opacity', 'marqueex'),
                'type' => Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'max' => 1,
                        'min' => 0.10,
                        'step' => 0.01,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .marqueex-separator' => 'opacity: {{SIZE}};',
                ],
            ]
        );

        $this->add_control(
            'separator_backdrop_filter',
            [
                'label' => __('Backdrop Filter Blur', 'marqueex'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 20,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .marqueex-separator' => 'backdrop-filter: blur({{SIZE}}{{UNIT}});',
                ],
            ]
        );

        $this->add_control(
            'separator_transform',
            [
                'label' => __('Transform', 'marqueex'),
                'type' => Controls_Manager::TEXT,
                'placeholder' => __('scale(1.1) rotate(5deg)', 'marqueex'),
                'selectors' => [
                    '{{WRAPPER}} .marqueex-separator' => 'transform: {{VALUE}};',
                ],
            ]
        );

        // Separator Hover Effects
        $this->add_control(
            'separator_hover_heading',
            [
                'label' => __('Hover Effects', 'marqueex'),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'separator_hover_color',
            [
                'label' => __('Hover Color', 'marqueex'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .marqueex-separator:hover' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .marqueex-separator:hover svg' => 'fill: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name' => 'separator_hover_background',
                'label' => __('Hover Background', 'marqueex'),
                'types' => ['classic', 'gradient'],
                'selector' => '{{WRAPPER}} .marqueex-separator:hover',
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'separator_hover_border',
                'label' => __('Hover Border', 'marqueex'),
                'selector' => '{{WRAPPER}} .marqueex-separator:hover',
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'separator_hover_box_shadow',
                'label' => __('Hover Box Shadow', 'marqueex'),
                'selector' => '{{WRAPPER}} .marqueex-separator:hover',
            ]
        );

        $this->add_control(
            'separator_hover_transform',
            [
                'label' => __('Hover Transform', 'marqueex'),
                'type' => Controls_Manager::TEXT,
                'placeholder' => __('translateY(-2px) scale(1.05)', 'marqueex'),
                'selectors' => [
                    '{{WRAPPER}} .marqueex-separator:hover' => 'transform: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'separator_hover_opacity',
            [
                'label' => __('Hover Opacity', 'marqueex'),
                'type' => Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'max' => 1,
                        'min' => 0.10,
                        'step' => 0.01,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .marqueex-separator:hover' => 'opacity: {{SIZE}};',
                ],
            ]
        );

        $this->add_control(
            'separator_transition_duration',
            [
                'label' => __('Transition Duration', 'marqueex'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['ms'],
                'range' => [
                    'ms' => [
                        'min' => 100,
                        'max' => 2000,
                        'step' => 50,
                    ],
                ],
                'default' => [
                    'size' => 300,
                    'unit' => 'ms',
                ],
                'selectors' => [
                    '{{WRAPPER}} .marqueex-separator' => 'transition: all {{SIZE}}{{UNIT}} ease;',
                ],
            ]
        );

        $this->add_responsive_control(
            'separator_image_size',
            [
                'label' => __('Image Size', 'marqueex'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'range' => [
                    'px' => [
                        'min' => 20,
                        'max' => 100,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .marqueex-separator-feature-image img' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}}; object-fit: cover;',
                ],
                'condition' => [
                    'separator_type' => 'featured_image',
                ],
            ]
        );

        $this->add_responsive_control(
            'separator_image_border_radius',
            [
                'label' => __('Image Border Radius', 'marqueex'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%'],
                'selectors' => [
                    '{{WRAPPER}} .marqueex-separator-feature-image img' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'condition' => [
                    'separator_type' => 'featured_image',
                ],
            ]
        );

        $this->end_controls_section();


        // Label Style Section
        $this->start_controls_section(
            'label_style_section',
            [
                'label' => __('Label', 'marqueex'),
                'tab' => Controls_Manager::TAB_STYLE,
                'condition' => [
                    'show_label' => 'yes',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name' => 'label_background',
                'label' => __('Background', 'marqueex'),
                'types' => ['classic', 'gradient'],
                'selector' => '{{WRAPPER}} .marqueex-ticker-label',
            ]
        );

        $this->add_control(
            'label_text_color',
            [
                'label' => __('Text Color', 'marqueex'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .marqueex-ticker-label' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'label_typography',
                'selector' => '{{WRAPPER}} .marqueex-ticker-label',
            ]
        );

        $this->add_group_control(
            Group_Control_Text_Shadow::get_type(),
            [
                'name' => 'label_text_shadow',
                'selector' => '{{WRAPPER}} .marqueex-ticker-label',
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'label_border',
                'label' => __('Border', 'marqueex'),
                'selector' => '{{WRAPPER}} .marqueex-ticker-label',
            ]
        );

        $this->add_responsive_control(
            'label_border_radius',
            [
                'label' => __('Border Radius', 'marqueex'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'selectors' => [
                    '{{WRAPPER}} .marqueex-ticker-label' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'label_box_shadow',
                'label' => __('Box Shadow', 'marqueex'),
                'selector' => '{{WRAPPER}} .marqueex-ticker-label',
            ]
        );

        $this->add_responsive_control(
            'label_padding',
            [
                'label' => __('Padding', 'marqueex'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'selectors' => [
                    '{{WRAPPER}} .marqueex-ticker-label' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'label_margin',
            [
                'label' => __('Margin', 'marqueex'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'selectors' => [
                    '{{WRAPPER}} .marqueex-ticker-label' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'label_width',
            [
                'label' => __('Width', 'marqueex'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px', '%', 'em'],
                'range' => [
                    'px' => [
                        'min' => 50,
                        'max' => 300,
                    ],
                    '%' => [
                        'min' => 10,
                        'max' => 50,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .marqueex-ticker-label' => 'width: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'label_position',
            [
                'label' => __('Position', 'marqueex'),
                'type' => Controls_Manager::SELECT,
                'default' => 'relative',
                'options' => [
                    'relative' => __('Relative', 'marqueex'),
                    'absolute' => __('Absolute', 'marqueex'),
                    'sticky' => __('Sticky', 'marqueex'),
                ],
                'selectors' => [
                    '{{WRAPPER}} .marqueex-ticker-label' => 'position: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'label_z_index',
            [
                'label' => __('Z-Index', 'marqueex'),
                'type' => Controls_Manager::NUMBER,
                'min' => 0,
                'max' => 999,
                'selectors' => [
                    '{{WRAPPER}} .marqueex-ticker-label' => 'z-index: {{VALUE}};',
                ],
                'condition' => [
                    'label_position!' => 'relative',
                ],
            ]
        );

        $this->end_controls_section();
        // Hover Effects Section
        // $this->start_controls_section(
        //     'hover_effects_section',
        //     [
        //         'label' => __('Hover Effects', 'marqueex'),
        //         'tab' => Controls_Manager::TAB_STYLE,
        //     ]
        // );

        // $this->add_control(
        //     'container_hover_animation',
        //     [
        //         'label' => __('Container Hover Animation', 'marqueex'),
        //         'type' => Controls_Manager::SELECT,
        //         'default' => 'none',
        //         'options' => [
        //             'none' => __('None', 'marqueex'),
        //             'scale' => __('Scale', 'marqueex'),
        //             'lift' => __('Lift', 'marqueex'),
        //             'glow' => __('Glow', 'marqueex'),
        //             'pulse' => __('Pulse', 'marqueex'),
        //         ],
        //         'prefix_class' => 'marqueex-hover-',
        //     ]
        // );

        // $this->add_control(
        //     'hover_scale_intensity',
        //     [
        //         'label' => __('Scale Intensity', 'marqueex'),
        //         'type' => Controls_Manager::SLIDER,
        //         'range' => [
        //             'px' => [
        //                 'min' => 1,
        //                 'max' => 1.2,
        //                 'step' => 0.01,
        //             ],
        //         ],
        //         'default' => [
        //             'size' => 1.05,
        //         ],
        //         'selectors' => [
        //             '{{WRAPPER}}.marqueex-hover-scale .marqueex-marquee-block:hover' => 'transform: scale({{SIZE}});',
        //         ],
        //         'condition' => [
        //             'container_hover_animation' => 'scale',
        //         ],
        //     ]
        // );

        // $this->add_control(
        //     'hover_lift_distance',
        //     [
        //         'label' => __('Lift Distance', 'marqueex'),
        //         'type' => Controls_Manager::SLIDER,
        //         'size_units' => ['px'],
        //         'range' => [
        //             'px' => [
        //                 'min' => 1,
        //                 'max' => 20,
        //             ],
        //         ],
        //         'default' => [
        //             'size' => 5,
        //         ],
        //         'selectors' => [
        //             '{{WRAPPER}}.marqueex-hover-lift .marqueex-marquee-block:hover' => 'transform: translateY(-{{SIZE}}{{UNIT}});',
        //         ],
        //         'condition' => [
        //             'container_hover_animation' => 'lift',
        //         ],
        //     ]
        // );

        // $this->add_control(
        //     'hover_glow_color',
        //     [
        //         'label' => __('Glow Color', 'marqueex'),
        //         'type' => Controls_Manager::COLOR,
        //         'default' => '#007cba',
        //         'selectors' => [
        //             '{{WRAPPER}}.marqueex-hover-glow .marqueex-marquee-block:hover' => 'box-shadow: 0 0 20px {{VALUE}};',
        //         ],
        //         'condition' => [
        //             'container_hover_animation' => 'glow',
        //         ],
        //     ]
        // );

        // $this->add_control(
        //     'hover_transition_duration',
        //     [
        //         'label' => __('Transition Duration', 'marqueex'),
        //         'type' => Controls_Manager::SLIDER,
        //         'size_units' => ['ms', 's'],
        //         'range' => [
        //             'ms' => [
        //                 'min' => 100,
        //                 'max' => 1000,
        //                 'step' => 50,
        //             ],
        //             's' => [
        //                 'min' => 0.1,
        //                 'max' => 2,
        //                 'step' => 0.1,
        //             ],
        //         ],
        //         'default' => [
        //             'unit' => 'ms',
        //             'size' => 300,
        //         ],
        //         'selectors' => [
        //             '{{WRAPPER}} .marqueex-marquee-block' => 'transition: all {{SIZE}}{{UNIT}} ease;',
        //         ],
        //         'condition' => [
        //             'container_hover_animation!' => 'none',
        //         ],
        //     ]
        // );

        // $this->end_controls_section();

        // Animation & Performance Section
        // $this->start_controls_section(
        //     'animation_performance_section',
        //     [
        //         'label' => __('Animation & Performance', 'marqueex'),
        //         'tab' => Controls_Manager::TAB_STYLE,
        //     ]
        // );

        // $this->add_control(
        //     'animation_easing',
        //     [
        //         'label' => __('Animation Easing', 'marqueex'),
        //         'type' => Controls_Manager::SELECT,
        //         'default' => 'linear',
        //         'options' => [
        //             'linear' => __('Linear', 'marqueex'),
        //             'ease' => __('Ease', 'marqueex'),
        //             'ease-in' => __('Ease In', 'marqueex'),
        //             'ease-out' => __('Ease Out', 'marqueex'),
        //             'ease-in-out' => __('Ease In Out', 'marqueex'),
        //         ],
        //         'selectors' => [
        //             '{{WRAPPER}} .marqueex-marquee-track' => 'animation-timing-function: {{VALUE}};',
        //         ],
        //     ]
        // );

        // $this->add_control(
        //     'gpu_acceleration',
        //     [
        //         'label' => __('GPU Acceleration', 'marqueex'),
        //         'type' => Controls_Manager::SWITCHER,
        //         'label_on' => __('On', 'marqueex'),
        //         'label_off' => __('Off', 'marqueex'),
        //         'return_value' => 'yes',
        //         'default' => 'yes',
        //         'selectors' => [
        //             '{{WRAPPER}} .marqueex-marquee-track' => 'transform: translateZ(0); will-change: transform;',
        //         ],
        //     ]
        // );

        // $this->add_control(
        //     'smooth_scrolling',
        //     [
        //         'label' => __('Smooth Scrolling', 'marqueex'),
        //         'type' => Controls_Manager::SWITCHER,
        //         'label_on' => __('On', 'marqueex'),
        //         'label_off' => __('Off', 'marqueex'),
        //         'return_value' => 'yes',
        //         'default' => 'yes',
        //         'selectors' => [
        //             '{{WRAPPER}} .marqueex-marquee-track-wrapper' => 'overflow: hidden; -webkit-overflow-scrolling: touch;',
        //         ],
        //     ]
        // );

        // $this->end_controls_section();
    }

    /**
     * Prepare widget configuration from settings
     *
     * Extracts and processes all widget settings into a clean configuration array.
     *
     * @param array $settings Widget settings from Elementor
     * @return array Processed configuration array
     */
    private function prepare_widget_config($settings) {
        return [
            'unique_id' => 'marqueex-ticker-' . $this->get_id(),
            'speed' => max(10, min(100, intval($settings['speed']['size'] ?? 10))), // Security: Clamp speed values
            'direction' => $settings['direction'] ?? 'left',
            'pause_on_hover' => $settings['pause_on_hover'] === 'yes' ? 'true' : 'false',
            'reverse_direction' => $settings['reverse_direction'] === 'yes',
            'content_type' => in_array($settings['content_type'] ?? 'posts', ['posts', 'custom']) ? $settings['content_type'] : 'posts',
        ];
    }

    /**
     * Render ticker content based on content type
     *
     * @param array $settings Widget settings
     * @param array $config Widget configuration
     */
    private function render_ticker_content_by_type($settings, $config) {
        if ($config['content_type'] === 'posts') {
            $this->render_dynamic_posts_content($settings);
        } else {
            $this->render_custom_text_content($settings);
        }
    }

    /**
     * Render dynamic posts content
     *
     * @param array $settings Widget settings
     */
    private function render_dynamic_posts_content($settings) {
        $query_args = $this->get_query_args($settings);
        $posts = get_posts($query_args);
        $this->render_ticker_content($settings, $posts);
    }

    /**
     * Render custom text content
     *
     * @param array $settings Widget settings
     */
    private function render_custom_text_content($settings) {
        $ticker_content = $settings['ticker_content_custom'] ?? [];

        if (empty($ticker_content)) {
            return;
        }

        echo '<div class="marqueex-news-wrapper">';
        $total_items = count($ticker_content);

        foreach ($ticker_content as $index => $item) {
            $text = $item['text'] ?? '';
            $link = $item['link'] ?? [];

            if (empty($text)) {
                continue;
            }

            echo '<div class="marqueex-news-item">';
            echo '<span class="marqueex-news-text">';

            if (!empty($link['url'])) {
                $target = $link['is_external'] ? ' target="_blank"' : '';
                $rel_attrs = [];

                if ($link['is_external']) {
                    $rel_attrs[] = 'noopener noreferrer';
                }
                if ($link['nofollow']) {
                    $rel_attrs[] = 'nofollow';
                }

                $rel = !empty($rel_attrs) ? ' rel="' . implode(' ', $rel_attrs) . '"' : '';

                echo '<a href="' . esc_url($link['url']) . '"' . $target . $rel . ' class="marqueex-title-link" aria-label="' . esc_attr($text) . '">' . esc_html($text) . '</a>';
            } else {
                echo esc_html($text);
            }

            echo '</span>';
            echo '</div>';

            // Only render separator if not the last item
            if ($index < $total_items - 1) {
                $this->render_separator_for_custom($settings);
            }
        }
        echo '</div>';
    }

    /**
     * Render label section with icon and text
     *
     * Displays the ticker label with optional icon and customizable HTML tag.
     *
     * @param array $settings Widget settings
     */
    private function render_label($settings) {
        // Only render if label is enabled
        if ($settings['show_label'] !== 'yes') {
            return;
        }

        $label_text = !empty($settings['label_text']) ? sanitize_text_field($settings['label_text']) : '';
        $label_icon = !empty($settings['label_icon']) ? $settings['label_icon'] : [];
        $label_tag = !empty($settings['label_heading_tag']) ? $settings['label_heading_tag'] : 'div';

        // Security: Validate HTML tag
        $allowed_tags = ['h1', 'h2', 'h3', 'h4', 'h5', 'h6', 'div', 'span'];
        if (!in_array($label_tag, $allowed_tags)) {
            $label_tag = 'div';
        }

        if (empty($label_text) && empty($label_icon['value'])) {
            return;
        }

        echo '<div class="marqueex-ticker-label">';
        echo '<' . esc_attr($label_tag) . ' class="marqueex-label-heading">';

        // Render icon if available
        if (!empty($label_icon['value']) && $settings['reverse_direction'] !== 'yes') {
            echo '<span class="marqueex-ticker-icon">';
            \Elementor\Icons_Manager::render_icon($label_icon, ['aria-hidden' => 'true']);
            echo '</span>';
        }

        // Render text if available
        if (!empty($label_text)) {
            echo esc_html($label_text);
        }

        if (!empty($label_icon['value']) && $settings['reverse_direction'] == 'yes') {
            echo '<span class="marqueex-ticker-icon">';
            \Elementor\Icons_Manager::render_icon($label_icon, ['aria-hidden' => 'true']);
            echo '</span>';
        }

        echo '</' . esc_attr($label_tag) . '>';
        echo '</div>';
    }


    /**
     * Ensure minimum posts for smooth marquee
     *
     * @param array $posts Array of post objects
     * @return array Array with minimum posts for smooth animation
     */
    private function ensure_minimum_posts($posts) {
        if (empty($posts)) {
            return [];
        }

        // Duplicate posts if we have less than 3 for smooth marquee
        while (count($posts) < 10) {
            $posts = array_merge($posts, $posts);
        }

        return $posts;
    }

    /**
     * Prepare link configuration
     *
     * @param array $settings Widget settings
     * @return array Link configuration
     */
    private function prepare_link_config($settings) {
        return [
            'enable_links' => $settings['enable_links'] ?? 'yes',
            'open_in_new_tab' => $settings['open_in_new_tab'] ?? 'no'
        ];
    }

    /**
     * Render single news item
     *
     * @param object $post Post object
     * @param array $settings Widget settings
     * @param array $link_config Link configuration
     */
    private function render_single_news_item($post, $settings, $link_config) {
        if (!$post instanceof \WP_Post) {
            return;
        }

        $title = get_the_title($post);

        // Security: Trim title if enabled
        if ($settings['title_trim_enable'] === 'yes') {
            // $trim_length = max(10, min(200, intval($settings['title_trim_length'] ?? 50)));
            // if (mb_strlen($title) > $trim_length) {
            //     $title = mb_substr($title, 0, $trim_length) . '...';
            // }
            $title = wp_trim_words($title, $settings['title_trim_length'], '...');
        }

        $url = $this->get_post_url($post);

        echo '<span class="marqueex-news-text">';

        if ($link_config['enable_links'] === 'yes' && !empty($url)) {
            $target = $link_config['open_in_new_tab'] === 'yes' ? ' target="_blank" rel="noopener noreferrer"' : '';
            echo '<a href="' . esc_url($url) . '"' . $target . ' class="marqueex-title-link" aria-label="' . esc_attr($title) . '">' . esc_html($title) . '</a>';
        } else {
            echo esc_html($title);
        }

        echo '</span>';
    }

    /**
     * Get post URL (custom or permalink)
     *
     * @param object $post Post object
     * @return string Post URL
     */
    private function get_post_url($post) {
        if (!$post instanceof \WP_Post) {
            return '';
        }

        if (isset($post->is_custom) && $post->is_custom && !empty($post->custom_url)) {
            return esc_url_raw($post->custom_url);
        }

        return get_permalink($post) ?: '';
    }

    /**
     * Render news ticker content with posts and separators
     *
     * Handles the rendering of news items with proper title trimming,
     * link generation, and separator insertion between items.
     *
     * @param array $settings Widget settings
     * @param array $posts Array of post objects
     */
    private function render_ticker_content($settings, $posts = []) {
        // Ensure we have posts to display
        $posts = $this->ensure_minimum_posts($posts);

        // Prepare link configuration
        $link_config = $this->prepare_link_config($settings);

        echo '<div class="marqueex-news-wrapper">';

        // Render each post with separators
        $total_posts = count($posts);
        foreach ($posts as $index => $post) {
            echo '<div class="marqueex-news-item">';
            $this->render_single_news_item($post, $settings, $link_config);
            echo '</div>';

            // Only render separator if not the last item
            if ($index < $total_posts - 1) {
                $this->render_separator($settings, $post);
            }
        }

        echo '</div>';
    }

    /**
     * Render separator based on type
     *
     * @param array $settings Widget settings
     * @param WP_Post $post Current post object
     */
    private function render_separator($settings, $post) {
        $separator_type = $settings['separator_type'] ?? 'none';

        if ($separator_type === 'none') {
            return;
        }

        $separator_preset = $settings['separator_style_preset'] ?? 'modern';
        $separator_classes = 'marqueex-separator marqueex-separator-' . esc_attr($separator_type) . ' marqueex-separator-preset-' . esc_attr($separator_preset);

        echo '<span class="' . esc_attr($separator_classes) . '">';

        switch ($separator_type) {
            case 'icon':
                $separator_icon = $settings['separator_icon'] ?? [];
                if (!empty($separator_icon['value'])) {
                    echo '<span class="marqueex-separator-icon">';
                    \Elementor\Icons_Manager::render_icon($separator_icon, ['aria-hidden' => 'true']);
                    echo '</span>';
                }
                break;

            case 'text':
                $separator_text = $settings['separator_text'] ?? '|';
                echo '<span class="marqueex-separator-text">' . esc_html($separator_text) . '</span>';
                break;

            case 'date':
                echo '<span class="marqueex-separator-date">' . esc_html(get_the_date('', $post)) . '</span>';
                break;

            case 'featured_image':
                if (has_post_thumbnail($post)) {
                    echo '<span class="marqueex-separator-feature-image">';
                    echo get_the_post_thumbnail($post, 'thumbnail');
                    echo '</span>';
                }
                break;
        }

        echo '</span>';
    }

    /**
     * Render separator for custom content
     *
     * @param array $settings Widget settings
     */
    private function render_separator_for_custom($settings) {
        $separator_type = $settings['separator_type'] ?? 'none';

        if ($separator_type === 'none') {
            return;
        }

        $separator_preset = $settings['separator_style_preset'] ?? 'modern';
        $separator_classes = 'marqueex-separator marqueex-separator-' . esc_attr($separator_type) . ' marqueex-separator-preset-' . esc_attr($separator_preset);

        echo '<span class="' . esc_attr($separator_classes) . '">';

        switch ($separator_type) {
            case 'icon':
                $separator_icon = $settings['separator_icon'] ?? [];
                if (!empty($separator_icon['value'])) {
                    echo '<span class="marqueex-separator-icon">';
                    \Elementor\Icons_Manager::render_icon($separator_icon, ['aria-hidden' => 'true']);
                    echo '</span>';
                }
                break;

            case 'text':
                $separator_text = $settings['separator_text'] ?? '|';
                echo '<span class="marqueex-separator-text">' . esc_html($separator_text) . '</span>';
                break;

            case 'date':
                echo '<span class="marqueex-separator-date">' . esc_html(current_time('F j, Y')) . '</span>';
                break;

            case 'featured_image':
                // For custom content, we can show a default image or skip
                // Since there's no post context, we'll show a placeholder or skip
                break;
        }

        echo '</span>';
    }

    /**
     * Render widget output on the frontend
     *
     * This method handles the complete rendering of the news ticker widget,
     * including label positioning, content generation, and marquee setup.
     */
    protected function render() {
        $settings = $this->get_settings_for_display();
        // Extract and prepare widget configuration
        $widget_config = $this->prepare_widget_config($settings);

        $this->add_render_attribute(
            [
                'marqueex-marquee-block' => [
                    'class' =>  [
                        'marqueex-marquee-block',
                        'marqueex-news-ticker',
                        $settings['pause_on_hover'] === 'yes' ? 'is-paused' : '',
                        $settings['reverse_direction'] === 'yes' ? 'is-reversed' : '',
                    ],
                    'data-settings' => [
                        wp_json_encode(array_filter([
                            "marquee_speed" => max(10, min(100, intval($settings['speed']['size'] ?? 30))),
                            "pause_on_hover" => $settings['pause_on_hover'] === 'yes',
                            "reverse_direction" => $settings['reverse_direction'] === 'yes',
                        ]))
                    ],
                    'role' => 'region',
                    'aria-label' => __('News Ticker', 'marqueex'),
                ]
            ]
        );

?>
        <div <?php echo $this->get_render_attribute_string('marqueex-marquee-block'); ?>>
            <?php

            if ($settings['reverse_direction'] !== 'yes') {
                $this->render_label($settings);
            }
            ?>
            <div class="marqueex-marquee-track-wrapper">
                <div class="marqueex-marquee-track">
                    <?php $this->render_ticker_content_by_type($settings, $widget_config); ?>
                </div>
                <div aria-hidden="true" class="marqueex-marquee-track">
                    <?php $this->render_ticker_content_by_type($settings, $widget_config); ?>
                </div>
            </div>
            <?php

            if ($settings['reverse_direction'] === 'yes') {
                $this->render_label($settings);
            }
            ?>
        </div>

<?php
    }
}
