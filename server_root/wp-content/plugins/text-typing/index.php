<?php

/**
 * Plugin Name: Text Typing - Block
 * Description: Make your text in amazing typing effect.
 * Version: 2.0.3
 * Author: bPlugins
 * Author URI: https://bplugins.com
 * License: GPLv3
 * License URI: https://www.gnu.org/licenses/gpl-3.0.txt
 * Text Domain: text-typing
 * @fs_free_only, /bplugins_sdk
 */
// ABS PATH
if ( !defined( 'ABSPATH' ) ) {
    exit;
}
if ( function_exists( 'ttb_fs' ) ) {
    ttb_fs()->set_basename( false, __FILE__ );
} else {
    // Constant
    define( 'TTB_PLUGIN_VERSION', ( isset( $_SERVER['HTTP_HOST'] ) && 'localhost' === $_SERVER['HTTP_HOST'] ? time() : '2.0.3' ) );
    define( 'TTB_DIR_URL', plugin_dir_url( __FILE__ ) );
    define( 'TTB_DIR_PATH', plugin_dir_path( __FILE__ ) );
    define( 'TTB_HAS_FREE', 'text-typing/index.php' === plugin_basename( __FILE__ ) );
    define( 'TTB_HAS_PRO', 'text-typing-pro/index.php' === plugin_basename( __FILE__ ) );
    if ( !function_exists( 'ttb_fs' ) ) {
        // Create a helper function for easy SDK access.
        function ttb_fs() {
            global $ttb_fs;
            if ( !isset( $ttb_fs ) ) {
                // Include Freemius SDK.
                $fsStartPath = dirname( __FILE__ ) . '/freemius/start.php';
                $bSDKInitPath = dirname( __FILE__ ) . '/freemius-lite/start.php';
                if ( TTB_HAS_PRO && file_exists( $fsStartPath ) ) {
                    require_once $fsStartPath;
                } else {
                    if ( TTB_HAS_FREE && file_exists( $bSDKInitPath ) ) {
                        require_once $bSDKInitPath;
                    }
                }
                $ttbConfig = array(
                    'id'                  => '20170',
                    'slug'                => 'text-typing',
                    'premium_slug'        => 'text-typing-pro',
                    'type'                => 'plugin',
                    'public_key'          => 'pk_b0a805a4574f7a1db93e8859282de',
                    'is_premium'          => true,
                    'premium_suffix'      => 'Pro',
                    'has_premium_version' => true,
                    'has_addons'          => false,
                    'has_paid_plans'      => true,
                    'trial'               => array(
                        'days'               => 7,
                        'is_require_payment' => false,
                    ),
                    'menu'                => array(
                        'slug'       => 'edit.php?post_type=text-typing',
                        'first-path' => 'edit.php?post_type=text-typing&page=ttb_demo_page',
                        'support'    => false,
                    ),
                );
                $ttb_fs = ( TTB_HAS_PRO && file_exists( $fsStartPath ) ? fs_dynamic_init( $ttbConfig ) : fs_lite_dynamic_init( $ttbConfig ) );
            }
            return $ttb_fs;
        }

        // Init Freemius.
        ttb_fs();
        // Signal that SDK was initiated.
        do_action( 'ttb_fs_loaded' );
    }
    function ttbIsPremium() {
        return ( TTB_HAS_PRO ? ttb_fs()->can_use_premium_code() : false );
    }

    if ( !class_exists( 'TTBPlugin' ) ) {
        class TTBPlugin {
            function __construct() {
                add_action( 'enqueue_block_assets', [$this, 'enqueueBlockAssets'] );
                add_action( 'init', [$this, 'onInit'] );
                // sub menu function hooks
                add_action( 'admin_menu', [$this, 'addSubmenu'] );
                add_action( 'admin_enqueue_scripts', [$this, 'adminEnqueueScripts'] );
                // premium checker
                add_action( 'wp_ajax_ttbPipeChecker', [$this, 'ttbPipeChecker'] );
                add_action( 'wp_ajax_nopriv_ttbPipeChecker', [$this, 'ttbPipeChecker'] );
                add_action( 'admin_init', [$this, 'registerSettings'] );
                add_action( 'rest_api_init', [$this, 'registerSettings'] );
                // Post Type function hooks
                add_action( 'init', array($this, 'ttb_text_typing_post_type') );
                // shortcode type function hooks
                add_shortcode( 'text-typing', [$this, 'ttb_shortcode_handler'] );
                //manage column
                add_filter( 'manage_text-typing_posts_columns', [$this, 'textTypingManageColumns'], 10 );
                // Custom manage column
                add_action(
                    'manage_text-typing_posts_custom_column',
                    [$this, 'textTypingManageCustomColumns'],
                    10,
                    2
                );
            }

            //manage column
            function textTypingManageColumns( $defaults ) {
                unset($defaults['date']);
                $defaults['shortcode'] = 'ShortCode';
                $defaults['date'] = 'Date';
                return $defaults;
            }

            // custom manage column
            function textTypingManageCustomColumns( $column_name, $post_ID ) {
                if ( $column_name == 'shortcode' ) {
                    echo '<div class="bPlAdminShortcode" id="bPlAdminShortcode-' . esc_attr( $post_ID ) . '">
			 <input value="[text-typing id=' . esc_attr( $post_ID ) . ']" onclick="copyBPlAdminShortcode(\'' . esc_attr( $post_ID ) . '\')" readonly>
			 <span class="tooltip">Copy To Clipboard</span>
		 </div>';
                }
            }

            public function ttb_shortcode_handler( $atts ) {
                $post_id = $atts['id'];
                $post = get_post( $post_id );
                if ( !$post ) {
                    return '';
                }
                if ( post_password_required( $post ) ) {
                    return get_the_password_form( $post );
                }
                switch ( $post->post_status ) {
                    case 'publish':
                        return $this->displayContent( $post );
                    case 'private':
                        if ( current_user_can( 'read_private_posts' ) ) {
                            return $this->displayContent( $post );
                        }
                        return '';
                    case 'draft':
                    case 'pending':
                    case 'future':
                        if ( current_user_can( 'edit_post', $post_id ) ) {
                            return $this->displayContent( $post );
                        }
                        return '';
                    default:
                        return '';
                }
            }

            public function displayContent( $post ) {
                $blocks = parse_blocks( $post->post_content );
                return render_block( $blocks[0] );
            }

            // Custom Post Type function calls
            function ttb_text_typing_post_type() {
                $menuIcon = '<svg stroke="currentColor" fill="currentColor" stroke-width="0" viewBox="0 0 24 24" height="3em" width="3em" xmlns="http://www.w3.org/2000/svg"><path d="M2.5 4v3h5v12h3V7h5V4h-13zm19 5h-9v3h3v7h3v-7h3V9z"></path></svg>';
                register_post_type( 'text-typing', array(
                    'label'         => 'Text Typing',
                    'labels'        => [
                        'add_new'        => 'Add New',
                        'add_new_item'   => 'Add New',
                        'edit_item'      => 'Edit Animated',
                        'not_found'      => 'There is no please add one',
                        'item_published' => 'Published',
                        'item_updated'   => 'Updated',
                    ],
                    'public'        => false,
                    'show_ui'       => true,
                    'show_in_rest'  => true,
                    'menu_icon'     => 'data:image/svg+xml;base64,' . base64_encode( $menuIcon ),
                    'template'      => [['ttb/text-typing']],
                    'template_lock' => 'all',
                ) );
            }

            function ttbPipeChecker() {
                $nonce = $_POST['_wpnonce'] ?? null;
                if ( !wp_verify_nonce( $nonce, 'wp_ajax' ) ) {
                    wp_send_json_error( 'Invalid Request' );
                }
                wp_send_json_success( [
                    'isPipe' => ttbIsPremium(),
                ] );
            }

            function registerSettings() {
                register_setting( 'ttbUtils', 'ttbUtils', [
                    'show_in_rest'      => [
                        'name'   => 'ttbUtils',
                        'schema' => [
                            'type' => 'string',
                        ],
                    ],
                    'type'              => 'string',
                    'default'           => wp_json_encode( [
                        'nonce' => wp_create_nonce( 'wp_ajax' ),
                    ] ),
                    'sanitize_callback' => 'sanitize_text_field',
                ] );
            }

            function enqueueBlockAssets() {
                wp_register_script(
                    'typedJS',
                    TTB_DIR_URL . 'public/js/typed.min.js',
                    [],
                    '2.0.12',
                    true
                );
            }

            function onInit() {
                register_block_type( __DIR__ . '/build' );
            }

            function addSubmenu() {
                add_submenu_page(
                    'edit.php?post_type=text-typing',
                    'Help & Demo',
                    'Help & Demo',
                    'manage_options',
                    'ttb_demo_page',
                    [$this, 'ttb_render_demo_page']
                );
            }

            function renderTemplate( $content ) {
                $parseBlocks = parse_blocks( $content );
                return render_block( $parseBlocks[0] );
            }

            function ttb_render_demo_page() {
                ?>
				<div id="bplAdminHelpPage"
						data-info="<?php 
                echo esc_attr( wp_json_encode( [
                    'version'   => TTB_PLUGIN_VERSION,
                    'isPremium' => ttbIsPremium(),
                ] ) );
                ?>"
						>
		
				</div>
				<?php 
            }

            function adminEnqueueScripts() {
                $screen = get_current_screen();
                if ( isset( $screen->post_type ) && $screen->post_type === 'text-typing' ) {
                    // dashboard shortcode copy function
                    wp_enqueue_style(
                        'dashboard-post-css',
                        TTB_DIR_URL . 'build/dashboard-post-css.css',
                        [],
                        TTB_PLUGIN_VERSION
                    );
                    wp_enqueue_script(
                        'dashboard-post-js',
                        TTB_DIR_URL . 'build/dashboard-post-js.js',
                        [],
                        TTB_PLUGIN_VERSION,
                        true
                    );
                }
                if ( isset( $screen->base ) && $screen->base === 'text-typing_page_ttb_demo_page' ) {
                    wp_enqueue_script(
                        'ttb-dashboard-help',
                        TTB_DIR_URL . 'build/dashboard.js',
                        ['react', 'react-dom', 'wp-components'],
                        TTB_PLUGIN_VERSION,
                        true
                    );
                    wp_enqueue_style(
                        'ttb-dashboard-style',
                        TTB_DIR_URL . 'build/dashboard.css',
                        ['wp-components', 'wp-edit-blocks', 'wp-block-editor'],
                        TTB_PLUGIN_VERSION
                    );
                    wp_set_script_translations( 'ttb-admin-help', 'text-typing', TTB_DIR_URL . 'languages' );
                }
            }

        }

        new TTBPlugin();
    }
}