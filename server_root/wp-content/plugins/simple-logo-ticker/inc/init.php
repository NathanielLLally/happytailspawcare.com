<?php

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

include plugin_dir_path( __FILE__ ) . '/post-type.php';
include plugin_dir_path( __FILE__ ) . '/helpers.php';
include plugin_dir_path( __FILE__ ) . '/meta-boxes.php';
include plugin_dir_path( __FILE__ ) . '/shortcode.php';


function sltk_custom_post_type() {
    register_post_type('sltk',
        array(
            'labels' => array(
                'name' => __('Simple logo ticker', 'simple-logo-ticker'),
                'singular_name' => _x( 'Simple_logo_ticker', 'Post type singular name', 'simple-logo-ticker' ),
                'add_new'  => __('Add new slider', 'simple-logo-ticker'), 
                'add_new_item' => __('Add new slider', 'simple-logo-ticker'),
                'edit_item' => __( 'Edit Slider', 'simple-logo-ticker' ),
            ),
            'public' => true,
            'has_archive' => true,
            'menu_icon' => 'dashicons-images-alt2',
            'supports' => array('title'),
            'show_in_rest' => true,
        )
    );
}
add_action('init', 'sltk_custom_post_type');


function sltk_disable_drag_metabox() {
    $screen = get_current_screen();
    if ( $screen && $screen->post_type === 'sltk' ) {
        wp_deregister_script('postbox');
    }
}
add_action( 'admin_enqueue_scripts', 'sltk_disable_drag_metabox' );


function sltk_enqueue_admin_scripts( $hook ) {
    $screen = get_current_screen();
    if ( $hook !== 'post.php' && $hook !== 'post-new.php' ) return;
    if ( $screen->post_type !== 'sltk' ) return;

    wp_enqueue_media();

    wp_register_script(
        'sltk_admin_media',
        plugin_dir_url( __FILE__ ) . 'assets/js/admin-media.js',
        ['jquery', 'jquery-ui-sortable'],
        '1.0.0',
        true
    );

    wp_enqueue_script('sltk_admin_media');
}
