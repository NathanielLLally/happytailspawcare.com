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
use Elementor\Utils;

if (!defined('ABSPATH')) {
    exit;
}

/**
 * MarqueeX Image Marquee Widget for Elementor
 */
class ImageMarquee extends Widget_Base {

    /**
     * Get widget name
     */
    public function get_name() {
        return 'marqueex-image-marquee';
    }

    /**
     * Get widget title
     */
    public function get_title() {
        return __('Image Marquee', 'marqueex');
    }

    /**
     * Get widget icon
     */
    public function get_icon() {
        return 'eicon-featured-image eicon-marqueex';
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
        return ['marqueex-image-marquee'];
    }

    public function get_style_depends() {
        return ['marqueex-image-marquee'];
    }
    /**
     * Get widget keywords
     */
    public function get_keywords() {
        return ['marquee', 'ticker', 'marquee', 'posts', 'slider', 'scroll'];
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
                'label' => __('Images', 'marqueex'),
                'tab' => Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'content_type',
            [
                'label' => __('Content Type', 'marqueex'),
                'type' => Controls_Manager::SELECT,
                'default' => 'custom',
                'options' => [
                    'posts' => __('Dynamic Posts', 'marqueex'),
                    'custom' => __('Custom Images', 'marqueex'),
                ],
                'condition' => [
                    'content_type' => 'never_display',
                ],
            ]
        );

        $this->add_control(
            'image_content_custom',
            [
                'label' => __('Image Content', 'marqueex'),
                'type' => Controls_Manager::REPEATER,
                'condition' => [
                    'content_type' => 'custom',
                ],
                'fields' => [
                    [
                        'name' => 'image',
                        'label' => __('Image', 'marqueex'),
                        'type' => Controls_Manager::MEDIA,
                        'default' => [
                            'url' => Utils::get_placeholder_image_src(),
                        ],
                        'description' => __('Choose an image for the marquee.', 'marqueex'),
                    ],
                    [
                        'name' => 'alt_text',
                        'label' => __('Alt Text', 'marqueex'),
                        'type' => Controls_Manager::TEXT,
                        'default' => __('Image', 'marqueex'),
                        'description' => __('Enter alt text for accessibility.', 'marqueex'),
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
                        'description' => __('Enter the link for the image.', 'marqueex'),
                    ],
                ],
                'condition' => [
                    'content_type' => 'custom',
                ],
                'title_field' => '{{{ alt_text }}}',
                'default' => [
                    [
                        'image' => [
                            'url' => Utils::get_placeholder_image_src(),
                        ],
                        'alt_text' => __('Sample Image 1', 'marqueex'),
                        'link' => [
                            'url' => '',
                            'is_external' => '',
                            'nofollow' => '',
                        ],
                    ],
                    [
                        'image' => [
                            'url' => Utils::get_placeholder_image_src(),
                        ],
                        'alt_text' => __('Sample Image 2', 'marqueex'),
                        'link' => [
                            'url' => '',
                            'is_external' => '',
                            'nofollow' => '',
                        ],
                    ],
                    [
                        'image' => [
                            'url' => Utils::get_placeholder_image_src(),
                        ],
                        'alt_text' => __('Sample Image 3', 'marqueex'),
                        'link' => [
                            'url' => '',
                            'is_external' => '',
                            'nofollow' => '',
                        ],
                    ],
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
                'separator' => 'before',
                // 'condition' => [
                //     'content_type' => 'never_display',
                // ],
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
            'direction_vertical',
            [
                'label' => __('Direction Vertical', 'marqueex'),
                'type' => Controls_Manager::SWITCHER,
                'default' => 'no',
                'options' => [
                    'no' => __('Horizontal', 'marqueex'),
                    'yes' => __('Vertical', 'marqueex'),
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


        // Image Settings Section
        $this->start_controls_section(
            'image_settings_section',
            [
                'label' => __('Image', 'marqueex'),
                'tab' => Controls_Manager::TAB_STYLE,
                'condition' => [
                    'content_type' => 'custom',
                ],
            ]
        );

        $this->add_responsive_control(
            'image_width',
            [
                'label' => __('Image Width', 'marqueex'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px', '%', 'em', 'rem', 'vw'],
                'range' => [
                    'px' => [
                        'min' => 50,
                        'max' => 500,
                        'step' => 1,
                    ],
                    '%' => [
                        'min' => 10,
                        'max' => 100,
                        'step' => 1,
                    ],
                ],
                'default' => [
                    'unit' => 'px',
                    'size' => 150,
                ],
                'selectors' => [
                    '{{WRAPPER}} .marqueex-image-item' => 'width: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'image_height',
            [
                'label' => __('Image Height', 'marqueex'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px', '%', 'em', 'rem', 'vh'],
                'range' => [
                    'px' => [
                        'min' => 50,
                        'max' => 500,
                        'step' => 1,
                    ],
                    '%' => [
                        'min' => 10,
                        'max' => 100,
                        'step' => 1,
                    ],
                ],
                'default' => [
                    'unit' => 'px',
                    'size' => 150,
                ],
                'selectors' => [
                    '{{WRAPPER}} .marqueex-image-item' => 'height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );
        //border control
        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'image_border',
                'label' => __('Border', 'marqueex'),
                'selector' => '{{WRAPPER}} .marqueex-image-item',
            ]
        );

        $this->add_responsive_control(
            'image_border_radius',
            [
                'label' => __('Border Radius', 'marqueex'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'selectors' => [
                    '{{WRAPPER}} .marqueex-image-item' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'image_box_shadow',
                'label' => __('Box Shadow', 'marqueex'),
                'selector' => '{{WRAPPER}} .marqueex-image-item',
            ]
        );

        $this->add_responsive_control(
            'image_padding',
            [
                'label' => __('Padding', 'marqueex'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'selectors' => [
                    '{{WRAPPER}} .marqueex-image-item' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'image_margin',
            [
                'label' => __('Margin', 'marqueex'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'selectors' => [
                    '{{WRAPPER}} .marqueex-image-item' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );



        $this->add_control(
            'image_object_fit',
            [
                'label' => __('Object Fit', 'marqueex'),
                'type' => Controls_Manager::SELECT,
                'default' => 'cover',
                'options' => [
                    'fill' => __('Fill', 'marqueex'),
                    'contain' => __('Contain', 'marqueex'),
                    'cover' => __('Cover', 'marqueex'),
                    'none' => __('None', 'marqueex'),
                    'scale-down' => __('Scale Down', 'marqueex'),
                ],
                'selectors' => [
                    '{{WRAPPER}} .marqueex-image-item' => 'object-fit: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'image_object_position',
            [
                'label' => __('Object Position', 'marqueex'),
                'type' => Controls_Manager::SELECT,
                'default' => 'center center',
                'options' => [
                    'center center' => __('Center Center', 'marqueex'),
                    'center left' => __('Center Left', 'marqueex'),
                    'center right' => __('Center Right', 'marqueex'),
                    'top center' => __('Top Center', 'marqueex'),
                    'top left' => __('Top Left', 'marqueex'),
                    'top right' => __('Top Right', 'marqueex'),
                    'bottom center' => __('Bottom Center', 'marqueex'),
                    'bottom left' => __('Bottom Left', 'marqueex'),
                    'bottom right' => __('Bottom Right', 'marqueex'),
                ],
                'selectors' => [
                    '{{WRAPPER}} .marqueex-image-item' => 'object-position: {{VALUE}};',
                ],
                'condition' => [
                    'image_object_fit!' => 'fill',
                ],
            ]
        );

        $this->end_controls_section();
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
            'marquee_direction' => $settings['marquee_direction'] ?? 'horizontal',
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
            $this->render_custom_image_content($settings);
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
     * Render custom image content
     *
     * @param array $settings Widget settings
     */
    private function render_custom_image_content($settings) {
        $image_content = $settings['image_content_custom'] ?? [];

        if (empty($image_content)) {
            return;
        }

        echo '<div class="marqueex-news-wrapper">';
        $total_items = count($image_content);
        $image_content = $this->ensure_minimum_posts($image_content);

        foreach ($image_content as $index => $item) {
            $image = $item['image'] ?? [];
            $alt_text = $item['alt_text'] ?? '';
            $link = $item['link'] ?? [];

            if (empty($image['url'])) {
                continue;
            }

            echo '<div class="marqueex-image-item">';

            // Render image with optional link
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

                echo '<a href="' . esc_url($link['url']) . '"' . $target . $rel . ' class="marqueex-image-link" aria-label="' . esc_attr($alt_text) . '">';
                echo '<img src="' . esc_url($image['url']) . '" alt="' . esc_attr($alt_text) . '" class="marqueex-marquee-image" loading="lazy" />';
                echo '</a>';
            } else {
                echo '<img src="' . esc_url($image['url']) . '" alt="' . esc_attr($alt_text) . '" class="marqueex-marquee-image" loading="lazy" />';
            }

            echo '</div>';
        }
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
     * Render single image item from post
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
        $url = $this->get_post_url($post);

        // Get featured image
        $image_id = get_post_thumbnail_id($post);
        if (!$image_id) {
            return; // Skip posts without featured images
        }

        $image_url = wp_get_attachment_image_url($image_id, 'medium_large');
        if (!$image_url) {
            return;
        }

        // Security: Trim title if enabled for alt text
        $alt_text = $title;
        if ($settings['title_trim_enable'] === 'yes') {
            $alt_text = wp_trim_words($title, $settings['title_trim_length'], '...');
        }

        echo '<div class="marqueex-image-item">';

        if ($link_config['enable_links'] === 'yes' && !empty($url)) {
            $target = $link_config['open_in_new_tab'] === 'yes' ? ' target="_blank" rel="noopener noreferrer"' : '';
            echo '<a href="' . esc_url($url) . '"' . $target . ' class="marqueex-image-link" aria-label="' . esc_attr($alt_text) . '">';
            echo '<img src="' . esc_url($image_url) . '" alt="' . esc_attr($alt_text) . '" class="marqueex-marquee-image" loading="lazy" />';
            echo '</a>';
        } else {
            echo '<img src="' . esc_url($image_url) . '" alt="' . esc_attr($alt_text) . '" class="marqueex-marquee-image" loading="lazy" />';
        }

        echo '</div>';
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
            if ($settings['direction_vertical'] === 'yes') {
                $this->render_separator($settings, $post);
            }
            $this->render_single_news_item($post, $settings, $link_config);
            echo '</div>';

            // Only render separator if not the last item
            if ($settings['direction_vertical'] !== 'yes') {
                if ($index < $total_posts - 1) {
                    $this->render_separator($settings, $post);
                }
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
    private function render_icons($settings, $icon) {
        $separator_type = $settings['separator_type'] ?? 'icon';

        if ($separator_type === 'none') {
            return;
        }
        $separator_preset = $settings['separator_style_preset'] ?? 'modern';
        $separator_classes = 'marqueex-separator marqueex-separator-icon marqueex-separator-preset-' . esc_attr($separator_preset);

        if (!empty($icon['value'])) {
            echo '<span class="' . esc_attr($separator_classes) . '">';
            echo '<span class="marqueex-separator-icon">';
            \Elementor\Icons_Manager::render_icon($icon, ['aria-hidden' => 'true']);
            echo '</span>';
            echo '</span>';
        }
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
                        'marqueex-image-marquee',
                        $settings['pause_on_hover'] === 'yes' ? 'is-paused' : '',
                        $settings['reverse_direction'] === 'yes' ? 'is-reversed' : '',
                        $settings['direction_vertical'] === 'yes' ? 'marqueex-marquee-vertical' : 'marqueex-marquee-horizontal',
                    ],
                    'data-speed' => max(10, min(100, intval($settings['speed']['size'] ?? 30))),
                    // 'data-direction' => $settings['reverse_direction'] === 'yes' ? 'right' : 'left',
                    // 'data-pause-on-hover' => $settings['pause_on_hover'] === 'yes' ? 'true' : 'false',
                    'role' => 'region',
                    'aria-label' => __('Image Marquee', 'marqueex'),
                ]
            ]
        );

?>
        <div <?php echo $this->get_render_attribute_string('marqueex-marquee-block'); ?>>
            <div class="marqueex-marquee-track-wrapper">
                <div class="marqueex-marquee-track">
                    <?php $this->render_ticker_content_by_type($settings, $widget_config); ?>
                </div>
                <div aria-hidden="true" class="marqueex-marquee-track">
                    <?php $this->render_ticker_content_by_type($settings, $widget_config); ?>
                </div>
            </div>
        </div>

<?php
    }
}
