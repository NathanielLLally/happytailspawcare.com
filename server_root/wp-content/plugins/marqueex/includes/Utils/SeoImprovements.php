<?php
namespace Wpxero\Marqueex\Utils;

use Wpxero\Marqueex\Traits\Singleton;

/**
 * SEO Improvements for MarqueeX
 *
 * This file adds structured data and other SEO improvements to the MarqueeX plugin.
 *
 * @package marqueex
 */

// Exit if accessed directly.
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Class SeoImprovements
 */
class SeoImprovements {
    use Singleton;
    /**
     * Constructor
     */
    public function __construct() {
        // Add hooks for SEO improvements
        add_action('wp_head', array($this, 'add_structured_data'));
        add_filter('plugin_row_meta', array($this, 'add_plugin_meta_links'), 10, 2);
        add_action('wp_footer', array($this, 'add_seo_attribution'), 999);
    }

    /**
     * Add structured data to pages with MarqueeX blocks
     */
    public function add_structured_data() {
        global $post;

        if (!is_singular() || !has_blocks($post->post_content)) {
            return;
        }

        // Check if the post contains MarqueeX blocks
        if (!$this->post_has_marqueex_blocks($post->post_content)) {
            return;
        }

        // Generate structured data for the marquee content
        $blocks = parse_blocks($post->post_content);
        $marquee_content = $this->extract_marqueex_content($blocks);

        if (!empty($marquee_content)) {
            $this->output_structured_data($marquee_content);
        }
    }

    /**
     * Check if post content contains MarqueeX blocks
     *
     * @param string $content Post content
     * @return bool Whether post has MarqueeX blocks
     */
    private function post_has_marqueex_blocks($content) {
        return has_block('marqueex/infinite-slider', $content) ||
            has_block('marqueex/infinite-slider-item', $content);
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
            if (isset($block['blockName']) && (
                strpos($block['blockName'], 'marqueex/') === 0
            )) {
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
     * Output structured data for MarqueeX content
     *
     * @param array $content_items Content items to include in structured data
     */
    private function output_structured_data($content_items) {
        $schema = array(
            '@context' => 'https://schema.org',
            '@type' => 'WebPage',
            'speakable' => array(
                '@type' => 'SpeakableSpecification',
                'cssSelector' => ['.wp-block-marqueex-infinite-slider']
            ),
            'mainContentOfPage' => array(
                '@type' => 'WebPageElement',
                'cssSelector' => '.wp-block-marqueex-infinite-slider'
            )
        );

        // Add scrolling text as potential "offers" if it looks like promotional content
        $promo_keywords = array('sale', 'discount', 'offer', 'promotion', 'special', 'limited', 'deal');
        foreach ($content_items as $content) {
            $content_lower = strtolower($content);
            $is_promo = false;

            foreach ($promo_keywords as $keyword) {
                if (strpos($content_lower, $keyword) !== false) {
                    $is_promo = true;
                    break;
                }
            }

            if ($is_promo) {
                if (!isset($schema['offers'])) {
                    $schema['offers'] = array(
                        '@type' => 'Offer',
                        'description' => $content
                    );
                }
                break;
            }
        }

        echo '<script type="application/ld+json">' . wp_json_encode($schema) . '</script>';
    }

    /**
     * Add meta links to the plugin row in the plugins page
     *
     * @param array $links Plugin row meta links
     * @param string $file Plugin base file
     * @return array Modified meta links
     */
    public function add_plugin_meta_links($links, $file) {
        if (plugin_basename(MARQUEEX_FILE) === $file) {
            $new_links = array(
                'docs' => '<a href="https://wpxero.com/marqueex/docs" aria-label="' . esc_attr__('Documentation for MarqueeX', 'marqueex') . '">' . esc_html__('Documentation', 'marqueex') . '</a>',
                'support' => '<a href="https://wpxero.com/support" aria-label="' . esc_attr__('Support for MarqueeX', 'marqueex') . '">' . esc_html__('Support', 'marqueex') . '</a>'
            );

            $links = array_merge($links, $new_links);
        }

        return $links;
    }

    /**
     * Add SEO attribution to footer
     * This adds an invisible attribution that helps search engines understand the page uses MarqueeX
     */
    public function add_seo_attribution() {
        global $post;

        if (!is_singular() || !has_blocks($post->post_content)) {
            return;
        }

        // Check if the post contains MarqueeX blocks
        if (!$this->post_has_marqueex_blocks($post->post_content)) {
            return;
        }

        echo '<!-- This page uses MarqueeX - Smooth Marquee & Infinite Scroll for WordPress by WPXERO (https://wpxero.com/marqueex) -->';
    }
}
