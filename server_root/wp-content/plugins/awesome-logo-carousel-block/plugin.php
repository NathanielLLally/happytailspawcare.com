<?php
/**
 * Plugin Name:       Awesome Logo Carousel Blocks
 * Plugin URI:        https://logocarousel.gutenbergkits.com
 * Description:       Showcase brand logos in interactive grid, carousel, slider, ticker, and list view.
 * Requires at least: 6.0
 * Requires PHP:      7.0
 * Version:           2.2.0
 * Author:            Gutenbergkits Team
 * Author URI:        https://gutenbergkits.com
 * License:           GPL-2.0-or-later
 * Text Domain:       awesome-logo-carousel-blocks
 * Domain Path:       /languages
 */

if ( ! defined( 'ABSPATH' ) ) exit;

if( ! class_exists ( 'Alcb_Logo_Carousel' ) ) {

	final class Alcb_Logo_Carousel {

		const VERSION = '2.2.0';
		private static $instance = null;

		public function __construct() {

			// Media Custom Field Hooks
			add_filter('attachment_fields_to_edit', [ $this, 'add_custom_link_field_to_media'], 10, 2);
			add_filter('attachment_fields_to_save', [ $this, 'save_custom_link_field'], 10, 2);
			add_action('rest_api_init', [ $this, 'register_custom_link_rest_field']);

			$this->constants();
			$this->includes();

			register_activation_hook( __FILE__, [ $this, 'redirect_to_admin' ] );
			add_action( 'admin_init', [ $this, 'handle_redirection' ] );
		}

		public function constants() {
			$constants = [
				'ALCB_VERSION' => self::VERSION,
				'ALCB_FILE'    => __FILE__,
				'ALCB_URL'     => plugin_dir_url( __FILE__ ),
				'ALCB_PATH'    => plugin_dir_path( __FILE__ ),
				'ALCB_INC'     => plugin_dir_path( __FILE__ ) . 'inc/',
			];

			foreach ( $constants as $key => $value ) {
				if ( ! defined( $key ) ) define( $key, $value );
			}
		}

		public function includes() {
			require_once ALCB_INC . 'instance.php';
			require_once ALCB_INC . 'init.php';
			require_once ALCB_PATH . 'admin/admin.php';
		}

		// Add custom field
		public function add_custom_link_field_to_media($form_fields, $post) {
			$form_fields['gtvb_custom_link'] = [
				'label' => __('Link', 'awesome-logo-carousel-blocks'),
				'input' => 'text',
				'value' => get_post_meta($post->ID, '_gtvb_custom_link', true),
				'helps' => __('Custom link for this image', 'awesome-logo-carousel-blocks')
			];
			return $form_fields;
		}

		// Save custom field
		public function save_custom_link_field($post, $attachment) {
			if (isset($attachment['gtvb_custom_link'])) {
				update_post_meta($post['ID'], '_gtvb_custom_link', sanitize_text_field($attachment['gtvb_custom_link']));
			}
			return $post;
		}

		// Add to REST API
		public function register_custom_link_rest_field() {
			register_rest_field('attachment', 'gtvb_custom_link', [
				'get_callback' => function($object){
					return get_post_meta($object['id'], '_gtvb_custom_link', true);
				},
				'update_callback' => function($value, $object){
					return update_post_meta($object['id'], '_gtvb_custom_link', sanitize_text_field($value));
				},
				'schema' => [
					'description' => __('Custom link for the image', 'awesome-logo-carousel-blocks'),
					'type' => 'string',
					'context' => ['view', 'edit']
				]
			]);
		}

		public function redirect_to_admin() {
			set_transient( '_alcb_redirect', true, 30 );
		}

		public function handle_redirection() {
			if ( get_transient( '_alcb_redirect' ) ) {
				delete_transient( '_alcb_redirect' );
				wp_safe_redirect( admin_url( 'options-general.php?page=aclb-carousel' ) );
				exit;
			}
		}

		public static function instance() {
			if ( is_null( self::$instance ) ) self::$instance = new self();
			return self::$instance;
		}
	}

	function alcb_logo_carousel() {
		return Alcb_Logo_Carousel::instance();
	}

	alcb_logo_carousel();
}
