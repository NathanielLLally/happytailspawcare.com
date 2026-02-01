<?php

namespace Wpxero\Marqueex\Utils;

use Wpxero\Marqueex\Traits\Singleton;

/**
 * Meta Descriptions for MarqueeX
 *
 * This file adds meta descriptions to pages that use MarqueeX blocks
 * to improve SEO visibility.
 *
 * @package marqueex
 */

// Exit if accessed directly.
if (!defined('ABSPATH')) {
    exit;
}
/**
 * Class MetaDescription
 */
class MetaDescription {
    use Singleton;
    /**
     * Constructor
     */
    public function __construct() {
        add_filter('wpseo_metadesc', array($this, 'modify_yoast_meta_description'), 10, 1);
        add_filter('rank_math/frontend/description', array($this, 'modify_rankmath_description'), 10, 1);
        add_action('wp_head', array($this, 'add_default_meta_description'), 1);
    }

    /**
     * Modify Yoast SEO meta description
     *
     * @param string $description The current meta description
     * @return string Modified description
     */
    public function modify_yoast_meta_description($description) {
        // Only modify if Yoast is active and we have an empty description
        if (empty($description) && $this->should_modify_description()) {
            return $this->get_marqueex_meta_description();
        }
        return $description;
    }

    /**
     * Modify Rank Math meta description
     *
     * @param string $description The current meta description
     * @return string Modified description
     */
    public function modify_rankmath_description($description) {
        // Only modify if Rank Math is active and we have an empty description
        if (empty($description) && $this->should_modify_description()) {
            return $this->get_marqueex_meta_description();
        }
        return $description;
    }

    /**
     * Add default meta description if no SEO plugin is active
     */
    public function add_default_meta_description() {
        // Only add if no SEO plugin is active
        if (!$this->is_seo_plugin_active() && $this->should_modify_description()) {
            echo '<meta name="description" content="' . esc_attr($this->get_marqueex_meta_description()) . '" />';
        }
    }

    /**
     * Check if we should modify the description
     *
     * @return bool Whether description should be modified
     */
    private function should_modify_description() {
        global $post;

        // Only on singular pages with blocks
        if (!is_singular() || !is_object($post) || !has_blocks($post->post_content)) {
            return false;
        }

        // Only if page contains MarqueeX blocks
        if (
            has_block('marqueex/infinite-slider', $post->post_content) ||
            has_block('marqueex/infinite-slider-item', $post->post_content)
        ) {
            return true;
        }

        return false;
    }

    /**
     * Get a meta description based on MarqueeX content
     *
     * @return string Generated meta description
     */
    private function get_marqueex_meta_description() {
        global $post;

        if (!is_object($post)) {
            return '';
        }

        $blocks = parse_blocks($post->post_content);
        $content = $this->extract_marqueex_content($blocks);

        if (empty($content)) {
            // Default meta description if no specific content is found
            $title = get_the_title($post->ID);
            return sprintf(
                // translators: %s: Post title
                __('View our scrolling content for %s featuring MarqueeX smooth animations.', 'marqueex'),
                $title
            );
        }

        // Create a description from the content (limited to 160 characters)
        $description = implode(' ', $content);
        $description = wp_trim_words($description, 20, '...');

        return $description;
    }

    /**
     * Extract content from MarqueeX blocks
     *
     * @param array $blocks Array of blocks
     * @return array Content from MarqueeX blocks
     */
    private function extract_marqueex_content($blocks) {
        $content = array();

        foreach ($blocks as $block) {
            if (isset($block['blockName']) && strpos($block['blockName'], 'marqueex/') === 0) {
                // Extract text content from the block
                if (!empty($block['innerContent'])) {
                    foreach ($block['innerContent'] as $inner_content) {
                        if (!empty($inner_content)) {
                            $content[] = wp_strip_all_tags($inner_content);
                        }
                    }
                }
            }

            // Check inner blocks recursively
            if (!empty($block['innerBlocks'])) {
                $inner_content = $this->extract_marqueex_content($block['innerBlocks']);
                if (!empty($inner_content)) {
                    $content = array_merge($content, $inner_content);
                }
            }
        }

        return $content;
    }

    /**
     * Check if a SEO plugin is active
     *
     * @return bool Whether a SEO plugin is active
     */
    private function is_seo_plugin_active() {
        return defined('WPSEO_VERSION') || class_exists('RankMath');
    }
}
