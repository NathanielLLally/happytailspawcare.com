<?php
ob_start();
/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://ays-pro.com/
 * @since             3.0.0
 * @package           Quiz_Maker
 *
 * @wordpress-plugin
 * Plugin Name:       Quiz Maker
 * Plugin URI:        https://ays-pro.com/wordpress/quiz-maker
 * Description:       Create powerful and engaging quizzes, tests, and exams in minutes. Build an unlimited number of quizzes and questions.
 * Version:           6.7.0.91
 * Author:            Quiz Maker team
 * Author URI:        https://ays-pro.com/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       quiz-maker
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}


/**
 * Currently plugin version.
 * Start at version 1.0.0 and use SemVer - https://semver.org
 * Rename this for your plugin and update it as you release new versions.
 */
define( 'AYS_QUIZ_NAME_VERSION', '6.7.0.91' );
define( 'AYS_QUIZ_VERSION', '6.7.0.91' );
define( 'AYS_QUIZ_NAME', 'quiz-maker' );

if( ! defined( 'AYS_QUIZ_BASENAME' ) )
    define( 'AYS_QUIZ_BASENAME', plugin_basename( __FILE__ ) );

if( ! defined( 'AYS_QUIZ_DIR' ) )
    define( 'AYS_QUIZ_DIR', plugin_dir_path( __FILE__ ) );

if( ! defined( 'AYS_QUIZ_BASE_URL' ) ) {
    define( 'AYS_QUIZ_BASE_URL', plugin_dir_url(__FILE__ ) );
}
if( ! defined( 'AYS_QUIZ_ADMIN_URL' ) )
    define( 'AYS_QUIZ_ADMIN_URL', plugin_dir_url( __FILE__ ) . 'admin' );

if( ! defined( 'AYS_QUIZ_PUBLIC_URL' ) )
    define( 'AYS_QUIZ_PUBLIC_URL', plugin_dir_url( __FILE__ ) . 'public' );

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-quiz-maker-activator.php
 */
function activate_quiz_maker() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-quiz-maker-activator.php';
	Quiz_Maker_Activator::ays_quiz_update_db_check();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-quiz-maker-deactivator.php
 */
function deactivate_quiz_maker() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-quiz-maker-deactivator.php';
	Quiz_Maker_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_quiz_maker' );
register_deactivation_hook( __FILE__, 'deactivate_quiz_maker' );

add_action( 'plugins_loaded', 'activate_quiz_maker' );

if(get_option('ays_quiz_rate_state') === false){
    add_option( 'ays_quiz_rate_state', 0 );
}

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-quiz-maker.php';

require plugin_dir_path( __FILE__ ) . 'quiz/quiz-maker-block.php';
/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_quiz_maker() {
    // add_action( 'activated_plugin', 'quiz_maker_activation_redirect_method' );
    add_action('admin_notices', 'quiz_maker_general_admin_notice');
	$plugin = new Quiz_Maker();
	$plugin->run();

}

function qm_get_client_ip() {
    $ipaddress = '';
    if (getenv('HTTP_CLIENT_IP'))
        $ipaddress = getenv('HTTP_CLIENT_IP');
    else if(getenv('HTTP_X_FORWARDED_FOR'))
        $ipaddress = getenv('HTTP_X_FORWARDED_FOR');
    else if(getenv('HTTP_X_FORWARDED'))
        $ipaddress = getenv('HTTP_X_FORWARDED');
    else if(getenv('HTTP_FORWARDED_FOR'))
        $ipaddress = getenv('HTTP_FORWARDED_FOR');
    else if(getenv('HTTP_FORWARDED'))
        $ipaddress = getenv('HTTP_FORWARDED');
    else if(getenv('REMOTE_ADDR'))
        $ipaddress = getenv('REMOTE_ADDR');
    else
        $ipaddress = 'UNKNOWN';
    return $ipaddress;
}

function quiz_maker_activation_redirect_method( $plugin ) {
    if( $plugin == plugin_basename( __FILE__ ) ) {
        exit( wp_safe_redirect( esc_url( admin_url( 'admin.php?page=' . AYS_QUIZ_NAME ) ) ) );
    }
}

function quiz_maker_general_admin_notice(){
    global $wpdb;
    if ( isset( $_GET['page'] ) && strpos( sanitize_text_field( $_GET['page'] ), AYS_QUIZ_NAME ) !== false ) {
        $is_chat_available = ays_quiz_maker_is_chat_available();
        $is_chat_icon_disabled = get_option('ays_quiz_disable_live_chat_icon');

        $chat_icon_flag = true;
        if( $is_chat_icon_disabled && $is_chat_icon_disabled == 'on' ){
            $chat_icon_flag = false;
        }
        ?>
         <div class="ays-notice-banner">
            <div class="navigation-bar">
                <div id="navigation-container">
                    <div class="ays-quiz-logo-container-upgrade">
                        <div class="logo-container">
                            <a href="https://ays-pro.com/wordpress/quiz-maker?utm_source=dashboard&utm_medium=quiz-free&utm_campaign=quiz-maker-top-banner-logo-link-<?php echo esc_attr( AYS_QUIZ_UTM_VERSION ); ?>" target="_blank" style="display: inline-block;box-shadow: none;">
                                <img  class="quiz-logo" src="<?php echo esc_url( plugin_dir_url( __FILE__ ) . 'admin/images/icons/quiz-maker-logo.png' ); ?>" alt="<?php echo esc_attr( __( "Quiz Maker", 'quiz-maker' ) ); ?>" title="<?php echo esc_attr( __( "Quiz Maker", 'quiz-maker' ) ); ?>"/>
                            </a>
                        </div>
                        <div class="ays-quiz-upgrade-container">
                            <a href="https://ays-pro.com/wordpress/quiz-maker?utm_source=dashboard&utm_medium=quiz-free&utm_campaign=quiz-top-banner-upgrade-button-<?php echo esc_attr( AYS_QUIZ_UTM_VERSION ); ?>" target="_blank" target="_blank">
                                <!-- <img src="<?php #echo esc_attr(AYS_QUIZ_ADMIN_URL) . '/images/icons/lightning.svg'; ?>"> -->
                                <img src="<?php echo esc_attr(AYS_QUIZ_ADMIN_URL . '/images/icons/lightning-white.svg'); ?>" class="ays-quiz-svg-light-hover">
                                <span><?php echo esc_html__( "Upgrade", 'quiz-maker' ); ?></span>
                            </a>
                            <span class="ays-quiz-logo-container-one-time-text"><?php echo esc_html__( "One-time payment", 'quiz-maker' ); ?></span>
                        </div>
                        <?php if(false): ?>
                        <!-- <div class="ays-quiz-coupon-container">
                            <div class="ays-quiz-coupon-box ays-quiz-copy-element-box-parent">
                                <img src="<?php echo esc_attr(AYS_QUIZ_ADMIN_URL . '/images/icons/receipt-solid.svg'); ?>" class="ays-quiz-svg-light-hover">
                                <span onClick="selectAndCopyElementContents(this)" class="ays-quiz-copy-element-box" data-toggle="tooltip" title="<?php echo esc_html__( "Click for copy", 'quiz-maker' ); ?>"><?php echo esc_html( "", 'quiz-maker' ); ?></span>
                            </div>
                            <span class="ays-quiz-logo-container-one-time-text"><?php echo esc_html__( "Extra 20% Coupon", 'quiz-maker' ); ?></span>
                        </div> -->
                        <?php endif; ?>
                    </div>
                    <ul id="menu">
                        <li class="modile-ddmenu-lg"><a class="ays-btn" href="https://ays-pro.com/wordpress/quiz-maker?utm_source=dashboard&utm_medium=quiz-free&utm_campaign=quiz-maker-top-banner-pricing-link-<?php echo esc_attr( AYS_QUIZ_UTM_VERSION ); ?>" target="_blank"><?php echo esc_html__( "Pricing", 'quiz-maker' ); ?></a></li>
                        <li class="modile-ddmenu-lg"><a class="ays-btn" href="https://quiz-plugin.com/wordpress-quiz-plugin-free-demo/" target="_blank"><?php echo esc_html__( "Demo", 'quiz-maker' ); ?></a></li>
                        <li class="modile-ddmenu-lg modile-ddmenu-lg-custom"><a class="ays-btn" href="https://wordpress.org/support/plugin/quiz-maker/" target="_blank"><?php echo esc_html__( "Free Support", 'quiz-maker' ); ?></a></li>
                        <!-- <li class="modile-ddmenu-lg take_survay modile-ddmenu-lg-custom"><a class="ays-btn" href="https://ays-demo.com/quiz-maker-plugin-feedback-survey/" target="_blank"><?php echo esc_html__( "Make a Suggestion", 'quiz-maker' ); ?></a></li> -->
                        <li class="modile-ddmenu-lg ays_quiz_take_gift modile-ddmenu-lg-custom"><a class="ays-btn" href="https://quiz-plugin.com/quiz-addon-as-a-gift/" target="_blank"><?php echo __( "Grab your GIFT", 'quiz-maker' ); ?></a></li>
                        <?php if($is_chat_available): ?>
                        <li class="modile-ddmenu-xs"><a class="ays-btn" href="https://ays-pro.com/onlinesupport/" target="_blank"><?php echo esc_html__( "Live Chat", 'quiz-maker' ); ?></a></li>
                        <?php endif; ?>
                        <li class="modile-ddmenu-lg"><a class="ays-btn" href="https://ays-pro.com/contact" target="_blank"><?php echo esc_html__( "Contact us", 'quiz-maker' ); ?></a></li>
                        <li class="modile-ddmenu-md">
                            <a class="toggle_ddmenu" href="javascript:void(0);"><i class="ays_fa ays_fa_ellipsis_h"></i></a>
                            <ul class="ddmenu" data-expanded="false">
                                <li><a class="ays-btn" href="https://ays-pro.com/wordpress/quiz-maker?utm_source=dashboard&utm_medium=quiz-free&utm_campaign=quiz-maker-top-banner-pricing-link-<?php echo esc_attr( AYS_QUIZ_UTM_VERSION ); ?>" target="_blank"><?php echo esc_html__( "Pricing", 'quiz-maker' ); ?></a></li>
                                <!-- <li class="take_survay"><a class="ays-btn" href="https://ays-demo.com/quiz-maker-plugin-feedback-survey/" target="_blank"><?php echo esc_html__( "Make a Suggestion", 'quiz-maker' ); ?></a></li> -->
                                <li class="ays_quiz_take_gift"><a class="ays-btn" href="https://quiz-plugin.com/quiz-addon-as-a-gift/" target="_blank"><?php echo __( "Grab your GIFT", 'quiz-maker' ); ?></a></li>
                                <li><a class="ays-btn" href="https://quiz-plugin.com/wordpress-quiz-plugin-free-demo/" target="_blank"><?php echo esc_html__( "Demo", 'quiz-maker' ); ?></a></li>
                                <li><a class="ays-btn" href="https://wordpress.org/support/plugin/quiz-maker/" target="_blank"><?php echo esc_html__( "Free Support", 'quiz-maker' ); ?></a></li>
                                <li><a class="ays-btn" href="https://ays-pro.com/contact" target="_blank"><?php echo esc_html__( "Contact us", 'quiz-maker' ); ?></a></li>
                            </ul>
                        </li>
                        <li class="modile-ddmenu-sm">
                            <a class="toggle_ddmenu" href="javascript:void(0);"><i class="ays_fa ays_fa_ellipsis_h"></i></a>
                            <ul class="ddmenu" data-expanded="false">
                                <li><a class="ays-btn" href="https://ays-pro.com/wordpress/quiz-maker?utm_source=dashboard&utm_medium=quiz-free&utm_campaign=quiz-maker-top-banner-pricing-link-<?php echo esc_attr( AYS_QUIZ_UTM_VERSION ); ?>" target="_blank"><?php echo esc_html__( "Pricing", 'quiz-maker' ); ?></a></li>
                                <li><a class="ays-btn" href="https://quiz-plugin.com/wordpress-quiz-plugin-free-demo/" target="_blank"><?php echo esc_html__( "Demo", 'quiz-maker' ); ?></a></li>
                                <li><a class="ays-btn" href="https://wordpress.org/support/plugin/quiz-maker/" target="_blank"><?php echo esc_html__( "Free Support", 'quiz-maker' ); ?></a></li>
                                <!-- <li class="take_survay"><a class="ays-btn" href="https://ays-demo.com/quiz-maker-plugin-feedback-survey/" target="_blank"><?php echo esc_html__( "Make a Suggestion", 'quiz-maker' ); ?></a></li> -->
                                <li class="ays_quiz_take_gift"><a class="ays-btn" href="https://quiz-plugin.com/quiz-addon-as-a-gift/" target="_blank"><?php echo __( "Grab your GIFT", 'quiz-maker' ); ?></a></li>
                                <?php if($is_chat_available): ?>
                                <li><a class="ays-btn" href="https://ays-pro.com/onlinesupport/" target="_blank"><?php echo esc_html__( "Live Chat", 'quiz-maker' ); ?></a></li>
                                <?php endif; ?>
                                <li><a class="ays-btn" href="https://ays-pro.com/contact" target="_blank"><?php echo esc_html__( "Contact us", 'quiz-maker' ); ?></a></li>
                            </ul>
                        </li>
                    </ul>
                    <div class="ays-quiz-checklist-open-icon" style="<?php echo (1 == 0 ) ? 'display: none;' : ''; ?>" title="<?php echo esc_html__( "Checklist", 'quiz-maker' ); ?>">
                        <svg width="18" height="24" viewBox="0 0 18 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <g clip-path="url(#clip0_130_42)">
                        <path d="M9 0C7.26094 0 5.83125 1.3125 5.64375 3H5.25C4.27031 3 3.43594 3.62812 3.12656 4.5H3C1.34531 4.5 0 5.84531 0 7.5V21C0 22.6547 1.34531 24 3 24H15C16.6547 24 18 22.6547 18 21V7.5C18 5.84531 16.6547 4.5 15 4.5H14.8734C14.5641 3.62812 13.7297 3 12.75 3H12.3563C12.1688 1.3125 10.7391 0 9 0ZM0.75 7.5C0.75 6.25781 1.75781 5.25 3 5.25V6C3 6.82969 3.67031 7.5 4.5 7.5H13.5C14.3297 7.5 15 6.82969 15 6V5.25C16.2422 5.25 17.25 6.25781 17.25 7.5V21C17.25 22.2422 16.2422 23.25 15 23.25H3C1.75781 23.25 0.75 22.2422 0.75 21V7.5ZM6.375 3.375C6.375 1.92656 7.55156 0.75 9 0.75C10.4484 0.75 11.625 1.92656 11.625 3.375C11.625 3.58125 11.7937 3.75 12 3.75H12.75C13.5797 3.75 14.25 4.42031 14.25 5.25V6C14.25 6.4125 13.9125 6.75 13.5 6.75H4.5C4.0875 6.75 3.75 6.4125 3.75 6V5.25C3.75 4.42031 4.42031 3.75 5.25 3.75H6C6.20625 3.75 6.375 3.58125 6.375 3.375ZM9 4.5C9.19891 4.5 9.38968 4.42098 9.53033 4.28033C9.67098 4.13968 9.75 3.94891 9.75 3.75C9.75 3.55109 9.67098 3.36032 9.53033 3.21967C9.38968 3.07902 9.19891 3 9 3C8.80109 3 8.61032 3.07902 8.46967 3.21967C8.32902 3.36032 8.25 3.55109 8.25 3.75C8.25 3.94891 8.32902 4.13968 8.46967 4.28033C8.61032 4.42098 8.80109 4.5 9 4.5ZM7.39219 11.1422C7.5375 10.9969 7.5375 10.7578 7.39219 10.6125C7.24687 10.4672 7.00781 10.4672 6.8625 10.6125L4.5 12.9703L3.64219 12.1078C3.49687 11.9625 3.25781 11.9625 3.1125 12.1078C2.96719 12.2531 2.96719 12.4922 3.1125 12.6375L4.2375 13.7625C4.38281 13.9078 4.62187 13.9078 4.76719 13.7625L7.39219 11.1375V11.1422ZM9 12.375C9 12.5813 9.16875 12.75 9.375 12.75H14.625C14.8313 12.75 15 12.5813 15 12.375C15 12.1687 14.8313 12 14.625 12H9.375C9.16875 12 9 12.1687 9 12.375ZM7.5 18C7.5 18.2063 7.66875 18.375 7.875 18.375H14.625C14.8313 18.375 15 18.2063 15 18C15 17.7937 14.8313 17.625 14.625 17.625H7.875C7.66875 17.625 7.5 17.7937 7.5 18ZM4.5 18.75C4.69891 18.75 4.88968 18.671 5.03033 18.5303C5.17098 18.3897 5.25 18.1989 5.25 18C5.25 17.8011 5.17098 17.6103 5.03033 17.4697C4.88968 17.329 4.69891 17.25 4.5 17.25C4.30109 17.25 4.11032 17.329 3.96967 17.4697C3.82902 17.6103 3.75 17.8011 3.75 18C3.75 18.1989 3.82902 18.3897 3.96967 18.5303C4.11032 18.671 4.30109 18.75 4.5 18.75Z" fill="black"/>
                        </g>
                        <defs>
                        <clipPath id="clip0_130_42">
                        <rect width="18" height="24" fill="white"/>
                        </clipPath>
                        </defs>
                        </svg>
                    </div>
                    <div class="ays-quiz-checklist-popup" style="display:none; position: absolute; top: 80px; right: 0; background: #111; color: #fff; padding: 16px; border-radius: 6px; z-index: 9999; max-width: 250px;">
                        <div style="position: absolute; top: -10px; right: 20px; width: 0; height: 0; border-left: 10px solid transparent; border-right: 10px solid transparent; border-bottom: 10px solid #111;"></div>
                        <strong><?php echo esc_html__( "Looking for your Launchpad Checklist?", 'quiz-maker' ); ?></strong>
                        <p><?php echo esc_html__( "Click the launch icon to continue setting up your quiz.", 'quiz-maker' ); ?></p>
                        <button class="ays-quiz-checklist-popup-close" style="background-color: #f66123; color: #fff; padding: 5px 10px; border: none; border-radius: 4px; cursor: pointer;"><?php echo esc_html__( "Got it", 'quiz-maker' ); ?></button>
                    </div>
                </div>
            </div>
         </div>

        <!-- Ask a question box start -->
        <?php if($chat_icon_flag): ?>
            <?php if($is_chat_available): ?>
                <?php
                if(get_option('ays_quiz_agree_terms') === 'true' && 1 == 0): ?>
                <div class="ays-quiz-crisp-chat-online-status">
                </div>
                <?php else: ?>
                <div class="ays_live_chat_ask_question_content ays_ask_question_content">
                    <div class="ays_ask_question_content_inner">
                        <a href="https://ays-pro.com/onlinesupport/" class="ays_quiz_question_link" target="_blank">
                            <span class="ays-ask-question-content-inner-question-mark-text"></span>
                            <span class="ays-ask-question-content-inner-hidden-text"><?php echo esc_html__( "Live Chat", 'quiz-maker' ); ?></span>
                        </a>
                    </div>
                </div>
                <?php endif; ?>
            <?php else: ?>
            <div class="ays_ask_question_content">
                <div class="ays_ask_question_content_inner">
                    <a href="https://wordpress.org/support/plugin/quiz-maker/" class="ays_quiz_question_link" target="_blank">
                        <span class="ays-ask-question-content-inner-question-mark-text">?</span>
                        <span class="ays-ask-question-content-inner-hidden-text"><?php echo esc_html__( "Ask a question", 'quiz-maker' ); ?></span>
                    </a>
                </div>
            </div>
            <?php endif; ?>
        <?php endif; ?>
        <!-- Ask a question box end -->
         <?php
            $ays_quiz_rate = intval(get_option('ays_quiz_rate_state'));
            $sql = "SELECT COUNT(*) AS res_count FROM {$wpdb->prefix}aysquiz_reports";
            $results = $wpdb->get_row($sql, 'ARRAY_A');
            if (!is_null($results) && !empty($results)) {
                if(($results['res_count'] >= 5000) && ($ays_quiz_rate < 4)){
                    update_option('ays_quiz_rate_state', 4);
                    ays_quiz_rate_message(5000);
                }elseif(($results['res_count'] >= 1000) && ($ays_quiz_rate < 3)){                
                    update_option('ays_quiz_rate_state', 3);
                    ays_quiz_rate_message(1000);
                }elseif(($results['res_count'] >= 500) && ($ays_quiz_rate < 2)){                
                    update_option('ays_quiz_rate_state', 2);
                    ays_quiz_rate_message(500);
                }elseif(($results['res_count'] >= 100) && ($ays_quiz_rate < 1)){                
                    update_option('ays_quiz_rate_state', 1);
                    ays_quiz_rate_message(100);
                }
            }
    }
}
    
function ays_quiz_rate_message($count){
     ?>
     <div class="quiz_toast__container">
        <div class="quiz_toast__cell">
            <div class="quiz_toast quiz_toast--red">
                <div class="quiz_toast__main">
                    <div class="quiz_toast__icon">
                        <svg version="1.1" class="quiz_toast__svg" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 301.691 301.691" style="enable-background:new 0 0 301.691 301.691;" xml:space="preserve">
                            <g>
                                <polygon points="119.151,0 129.6,218.406 172.06,218.406 182.54,0  "></polygon>
                                <rect x="130.563" y="261.168" width="40.525" height="40.523"></rect>
                            </g>
                        </svg>
                    </div>
                    <div class="quiz_toast__content">
                        <p class="quiz_toast__type">
                            <?php 
                                echo sprintf( esc_attr( __('Wow!!! Excellent job!! Your quizzes was passed by more than %s people!!', 'quiz-maker') ), intval($count));
                            ?>
                        </p>
                        <p class="quiz_toast__message">
                            <?php echo sprintf( '<span>%s</span> <a class="quiz_toast__rate_button" href="https://wordpress.org/support/plugin/quiz-maker/reviews/?rate=5#new-post" target="_blank">%s</a>', 'Satisfied with our Quiz Maker plugin? It brings a lot of user to your website? Then it\'s time to rate us!! ', esc_attr(__('Rate Us', 'quiz-maker'))); ?>
                        </p>
                    </div>
                </div>
                <div class="quiz_toast__close">
                    <svg version="1.1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 15.642 15.642" xmlns:xlink="http://www.w3.org/1999/xlink" enable-background="new 0 0 15.642 15.642">
                        <path fill-rule="evenodd" d="M8.882,7.821l6.541-6.541c0.293-0.293,0.293-0.768,0-1.061  c-0.293-0.293-0.768-0.293-1.061,0L7.821,6.76L1.28,0.22c-0.293-0.293-0.768-0.293-1.061,0c-0.293,0.293-0.293,0.768,0,1.061  l6.541,6.541L0.22,14.362c-0.293,0.293-0.293,0.768,0,1.061c0.147,0.146,0.338,0.22,0.53,0.22s0.384-0.073,0.53-0.22l6.541-6.541  l6.541,6.541c0.147,0.146,0.338,0.22,0.53,0.22c0.192,0,0.384-0.073,0.53-0.22c0.293-0.293,0.293-0.768,0-1.061L8.882,7.821z"></path>
                    </svg>
                </div>
            </div>
        </div>
    </div>
    <?php
}
run_quiz_maker();
