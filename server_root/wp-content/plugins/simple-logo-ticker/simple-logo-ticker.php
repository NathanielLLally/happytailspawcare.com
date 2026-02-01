<?php
/**
 * Simple Logo Ticker
 *
 * Plugin Name: Simple Logo Ticker
 * Plugin URI:  https://github.com/akeley10/slider-plugin
 * Description: Slider personalizable en formato ticker
 * Version:     1.0.0
 * Author:      Alejandro
 * Author URI:  https://github.com/akeley10
 * License:     GPL2
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain: simple-logo-ticker
 * Requires at least: 5.5
 * Requires PHP: 7.2
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

 include plugin_dir_path( __FILE__ ) . '/inc/init.php';
 include plugin_dir_path( __FILE__ ) . '/inc/menu-page.php';



 function sltk_register_plugin_styles() {
    // CSS
    wp_enqueue_style( 'simple-logo-ticker-style', plugin_dir_url( __FILE__ ) . 'assets/css/sltk_swiper.css' );
    wp_enqueue_style( 'simple-logo-ticker-swiper', plugin_dir_url( __FILE__ ) . 'lib/swiper/swiper-bundle.min.css' );

    // JS
    wp_enqueue_script( 'simple-logo-ticker-swiper', plugin_dir_url( __FILE__ ) . 'lib/swiper/swiper-bundle.min.js', array(), '1.0', true );
    wp_enqueue_script( 'simple-logo-ticker-init', plugin_dir_url( __FILE__ ) . 'assets/js/swiper-init.js', array( 'simple-logo-ticker-swiper' ), '1.0', true );

    $speed = isset( $GLOBALS['sltk_speed'] ) ? $GLOBALS['sltk_speed'] : 1000;
    $slidesPerView = isset( $GLOBALS['sltk_slidesPerView'] ) ? $GLOBALS['sltk_slidesPerView'] : 3;
    $noStop = isset( $GLOBALS['sltk_stop'] ) ? $GLOBALS['sltk_stop'] : 'no';

    wp_localize_script( 'simple-logo-ticker-init', 'sltk', array(
        'noStop'        => $noStop,
        'speed'         => $speed,
        'slidesPerView' => $slidesPerView
    ) );
}
add_action( 'wp_enqueue_scripts', 'sltk_register_plugin_styles' );

function sltk_register_admin_plugin_styles( $hook ) {
    global $post;

    if ( isset( $post ) && $post->post_type === 'sltk' ) {
        wp_enqueue_style( 'simple-logo-ticker-admin-style', plugin_dir_url( __FILE__ ) . 'assets/css/sltk_swiper.css' );
        wp_enqueue_style( 'simple-logo-ticker-admin-swiper', plugin_dir_url( __FILE__ ) . 'lib/swiper/swiper-bundle.min.css' );
    }
}
add_action( 'admin_enqueue_scripts', 'sltk_register_admin_plugin_styles' );


function sltk_register_block_gutenberg(){

    $block_dir = __DIR__ . '/blocks/gutenberg-block/build/gutenberg-block';

    if(file_exists($block_dir . '/block.json')){
        register_block_type($block_dir);
    }

}
add_action('init','sltk_register_block_gutenberg');

function sltk_register_elementor_widgets() {
	if ( did_action( 'elementor/loaded' ) ) {
		require_once plugin_dir_path( __FILE__ ) . 'blocks/elementor-block/elementor-oembed-widget.php';
	}
}
add_action( 'plugins_loaded', 'sltk_register_elementor_widgets' );
 ?>
