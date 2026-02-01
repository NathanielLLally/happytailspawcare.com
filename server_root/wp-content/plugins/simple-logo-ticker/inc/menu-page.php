<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

function sltk_admin_enqueue() {
    $screen = get_current_screen();
    if ($screen && $screen->post_type === 'sltk') {

        // CSS
        wp_enqueue_style(
            'sltk-adminmenu',
            plugin_dir_url( dirname(__FILE__) ) . 'assets/css/sltk_adminmenu.css',
            [],
            '1.0.0'
        );

        // Media scripts de WP
        wp_enqueue_media();

        // JS personalizado
        wp_enqueue_script(
            'sltk-admin-media',
            plugin_dir_url( dirname(__FILE__) ) . 'assets/js/sltk_media.js',
            ['jquery', 'jquery-ui-sortable'],
            '1.0.0',
            true
        );
    }
}
add_action( 'admin_enqueue_scripts', 'sltk_admin_enqueue', 99 );


function sltk_admin_body_class( $classes ) {
    $screen = get_current_screen();
    if ( $screen && $screen->post_type === 'sltk' ) {
        // agregamos sltk al body
        $classes .= ' sltk';
    }
    return $classes;
}
add_filter( 'admin_body_class', 'sltk_admin_body_class' );

function sltk_adminOptions($hook) {
    global $post;

    if (($hook === 'post.php' || $hook === 'post-new.php') &&
        isset($post->post_type) && $post->post_type === 'sltk'
    ) {
        // CSS
        wp_enqueue_style(
            'sltk-admin-css',
            plugin_dir_url( dirname(__FILE__) ) . 'assets/css/sltk_adminmenu.css',
            array(),
            '1.0'
        );

        // JS
        wp_enqueue_script(
            'sltk-admin-js',
            plugin_dir_url( dirname(__FILE__) ) . 'assets/js/sltk_adminOptions.js',
            array(),
            '1.0',
            true
        );
    }
}
add_action('admin_enqueue_scripts', 'sltk_adminOptions');




function sltk_admin_head_custom_styles() {
    if (
        is_admin() &&
        isset($_GET['from_shortcode']) &&
        $_GET['from_shortcode'] === '1' &&
        get_current_screen()->base === 'post'
    ) {
        $custom_css = '
        .editor-post-trash, 
        .submitdelete {
            display: none !important;
        }
    ';
    wp_add_inline_style('sltk_adminmenu', $custom_css);
    }
}
add_action( 'admin_head', 'sltk_admin_head_custom_styles' );
?>