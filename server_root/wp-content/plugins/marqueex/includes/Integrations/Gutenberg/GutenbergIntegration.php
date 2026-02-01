<?php

namespace Wpxero\Marqueex\Integrations\Gutenberg;

use Wpxero\Marqueex\Traits\Singleton;
use WP_Block_Type_Registry;
use Wpxero\Marqueex\Core\CSSBuilder;

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Gutenberg Integration Class
 */
class GutenbergIntegration {
    use Singleton;

    /**
     * Constructor
     */
    private function __construct() {
        add_action('init', [$this, 'init']);
    }

    /**
     * Initialize Shortcode integration
     */
    public function init() {
        // Check if integration is enabled
        $settings = get_option('marqueex_settings', []);
        $gutenberg_enabled = $settings['builder_support']['gutenberg']['enabled'] ?? true;

        if (!$gutenberg_enabled) {
            return;
        }

        // Register shortcodes
        $this->register_blocks();

        if (!is_admin()) {
            add_action('enqueue_block_assets', array($this, 'enqueue_front_scripts'));
            add_action('wp_enqueue_scripts', array($this, 'enqueue_front_scripts'));
        }
        add_action('enqueue_block_assets', array($this, 'enqueue_editor_assets'));
        add_filter('block_categories_all', array($this, 'add_custom_block_category'));
    }

    public function enqueue_editor_assets() {
        if (!is_admin()) {
            return;
        }

        $asset_path = MARQUEEX_DIR_PATH . '/dist/marqueex.asset.php';
        if (!file_exists($asset_path)) {
            return;
        }


        $args = require $asset_path;
        wp_enqueue_style('@marqueex/library', MARQUEEX_ADMIN_URL . 'dist/marqueex.css', array(), $args['version']);
        wp_register_script('@marqueex/library', MARQUEEX_ADMIN_URL . 'dist/marqueex.js', $args['dependencies'], $args['version'], true);
        wp_localize_script('@marqueex/library', 'MARQUEEX', array(
            'ajax_url' => admin_url('admin-ajax.php'),
            'home_url' => home_url('/'),
            'nonce' => wp_create_nonce('marqueex-nonce'),
            'rest_url' => rest_url('marqueex/v1'),
            'assetsPath' =>  MARQUEEX_ADMIN_URL . 'assets/'
        ));
    }

    public function add_custom_block_category($categories) {
        $category = array(
            'slug' => 'marqueex',
            'title' => 'MarqueeX',
        );
        return array_merge(array($category), $categories);
    }

    public function register_blocks() {

        $dist_dir = MARQUEEX_DIR_PATH . 'dist/';
        if (!is_dir($dist_dir)) {
            return;
        }
        $settings = get_option('marqueex_settings', []);
        $active_blocks = $settings['builder_support']['gutenberg']['elements'] ?? [];
        // print_r($active_blocks);
        foreach (scandir($dist_dir) as $name) {
            if (!str_contains($name, '.')) {
                $option_name = str_replace('-', '_', $name);
                $should_register = false;

                // Check if block is directly enabled
                if (isset($active_blocks[$option_name]) && $active_blocks[$option_name]) {
                    $should_register = true;
                }

                // Check if this is an item block and its parent is enabled
                if (str_ends_with($name, '-item')) {
                    $parent_name = str_replace('-item', '', $name);
                    $parent_option = str_replace('-', '_', $parent_name);
                    if (isset($active_blocks[$parent_option]) && $active_blocks[$parent_option]) {
                        $should_register = true;
                    }
                }

                if ($should_register) {
                    register_block_type($dist_dir . $name);
                }
            }
        }
    }


    public function enqueue_front_scripts() {
        wp_enqueue_style('marqueex-frontend', MARQUEEX_ADMIN_URL . 'dist/style-marqueex.css', [], MARQUEEX_VERSION);
        $post = get_post();
        if ($post && $post->post_content) {
            $blocks = parse_blocks($post->post_content);
            foreach ($blocks as $block) {
                $this->style_block($block);
            }
        }
    }

    public function style_block($block) {
        $attrs = $block['attrs'] ?? array();
        $block_id = $attrs['blockId'] ?? '';
        if ($block_id) {
            $css = $this->block_css($block);
            if ($css) {
                $escaped_css = wp_strip_all_tags($css); // Escaping as late as possible
                wp_add_inline_style('marqueex-frontend', $escaped_css);
            }
        }

        foreach ($block['innerBlocks'] ?? array() as $inner_block) {
            $this->style_block($inner_block);
        }
    }

    public function block_css($block) {

        $css = '';
        $registry = WP_Block_Type_Registry::get_instance(); // Get the block type registry instance
        $attrs = $block['attrs'] ?? array();
        $block_id = $attrs['blockId'] ?? '';
        if ($block_id) {
            foreach ($attrs as $attr => $value) {
                if (is_array($value) && !empty($value)) {
                    $selector = $value['__selector__'] ?? null;
                    if (!$selector) {
                        $block_type = $registry->get_registered($block['blockName']);
                        if ($block_type) {
                            $selector = $block_type->attributes[$attr]['selector'] ?? null;
                        }
                    }
                    $css .= $this->build_css("marqueex-$block_id", $value, array('selector' => $selector));
                }
            }
        }
        return $css;
    }

    public function build_css($id, $attributes, $options = array()) {
        $builder = CSSBuilder::get_instance();
        $builder->add_attributes($attributes);
        return $builder->to_css($id, $options);
    }

    /**
     * Get integration status
     */
    public function get_status() {
        return [
            'active' => true,
            'blocks_registered' => true,
            'blocks' => [
                'marqueex_slider' => true,
                'marqueex_ticker' => true,
                'marqueex_infinite' => true,
            ],
        ];
    }
}
