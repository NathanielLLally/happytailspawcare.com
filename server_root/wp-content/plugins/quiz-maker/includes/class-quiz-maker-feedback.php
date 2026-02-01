<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

class Quiz_Maker_Feedback {

	/**
	 * API feedback URL.
	 *
	 * Holds the URL of the feedback API.
	 *
	 * @access private
	 * @static
	 *
	 * @var string API feedback URL.
	 */
	private static $api_feedback_url = 'https://poll-plugin.com/quiz-maker/feedback/';

	/**
	 * @since 1.0.0
	 * @access public
	 */
	public function __construct() {
		add_action( 'current_screen', function () {
			if ( ! $this->is_plugins_screen() ) {
				return;
			}

			add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_feedback_dialog_scripts' ) );
		} );

		// Ajax.
		add_action( 'wp_ajax_ays_quiz_deactivate_feedback', array( $this, 'ays_quiz_deactivate_feedback' ) );
	}

	/**
	 * Get module name.
	 *
	 * Retrieve the module name.
	 *
	 * @since  1.0.0
	 * @access public
	 *
	 * @return string Module name.
	 */
	public function get_name() {
		return 'feedback';
	}

	/**
	 * Enqueue feedback dialog scripts.
	 *
	 * Registers the feedback dialog scripts and enqueues them.
	 *
	 * @since 1.0.0
	 * @access public
	 */
	public function enqueue_feedback_dialog_scripts() {
		add_action( 'admin_footer', array( $this, 'print_deactivate_feedback_dialog' ) );
	}

	/**
	 * Print deactivate feedback dialog.
	 *
	 * Display a dialog box to ask the user why he deactivated Quiz Maker.
	 *
	 * Fired by `admin_footer` filter.
	 *
	 * @since 1.0.0
	 * @access public
	 */
	public function print_deactivate_feedback_dialog() {
		$deactivate_reasons = array(
			'no_longer_needed' => array(
				'title' => esc_html__( 'I no longer need the plugin', 'quiz-maker' ),
				'input_placeholder' => '',
			),
			'found_a_better_plugin' => array(
				'title' => esc_html__( 'I found a better alternative', 'quiz-maker' ),
				'input_placeholder' => esc_html__( 'Other', 'quiz-maker' ),
				'sub_reason' => array(
					'qsm' 				=> esc_html__( 'QSM', 'quiz-maker' ),
					'formidable_forms' 	=> esc_html__( 'Formidable Forms', 'quiz-maker' ),
					'hd_quiz' 			=> esc_html__( 'HD Quiz', 'quiz-maker' ),
					'watu_quiz' 		=> esc_html__( 'Watu Quiz', 'quiz-maker' ),
					'quiz_cat' 			=> esc_html__( 'Quiz Cat', 'quiz-maker' ),
				),
			),
			'couldnt_get_the_plugin_to_work' => array(
				'title' => esc_html__( "The plugin didn’t work as expected", 'quiz-maker' ),
				'input_placeholder' => '',
			),
			'missing_features' => array(
				'title' => esc_html__( 'Missing essential features', 'quiz-maker' ),
				'input_placeholder' => esc_html__( 'Please share which features', 'quiz-maker' ),
			),
			'temporary_deactivation' => array(
				'title' => esc_html__( "I only needed it temporarily", 'quiz-maker' ),
				'input_placeholder' => '',
			),
			'plugin_or_theme_conflict' => array(
				'title' => esc_html__( "Conflicts with other plugins or themes", 'quiz-maker' ),
				// 'input_placeholder' => esc_html__( 'Please share which plugin or theme', 'quiz-maker' ),
				'input_placeholder' => '',
				'alert' => sprintf( __("Contact our %s support team %s to find and fix the issue.", 'quiz-maker'),
                                    "<a href='https://ays-pro.com/contact' target='_blank'>",
                                    "</a>"
                                ),
			),
			'quiz_pro' => array(
				'title' => esc_html__( 'I’m using the premium version now', 'quiz-maker' ),
				'input_placeholder' => '',
				// 'alert' => esc_html__( "Wait! Don't deactivate Quiz Maker. You have to activate both Quiz Maker and Quiz Maker Pro in order for the plugin to work.", 'quiz-maker' ),
			),
			'other' => array(
				'title' => esc_html__( 'Other', 'quiz-maker' ),
				'input_placeholder' => esc_html__( 'Please share the reason', 'quiz-maker' ),
			),
		);

		$quiz_deactivate_feedback_nonce = wp_create_nonce( 'ays_quiz_deactivate_feedback_nonce' );

		?>
		<div class="ays-quiz-dialog-widget ays-quiz-dialog-lightbox-widget ays-quiz-dialog-type-buttons ays-quiz-dialog-type-lightbox" id="ays-quiz-deactivate-feedback-modal" aria-modal="true" role="document" tabindex="0" style="display: none;">
		    <div class="ays-quiz-dialog-widget-content ays-quiz-dialog-lightbox-widget-content">
		        <div class="ays-quiz-dialog-header ays-quiz-dialog-lightbox-header">
		            <div id="ays-quiz-deactivate-feedback-dialog-header">
						<img class="ays-quiz-dialog-logo" src="<?php echo esc_url( AYS_QUIZ_ADMIN_URL . '/images/icons/quiz-maker-logo.png' ); ?>" alt="<?php echo esc_attr( __( "Quiz Maker", 'quiz-maker' ) ); ?>" title="<?php echo esc_attr( __( "Quiz Maker", 'quiz-maker' ) ); ?>" width="20" height="20"/>
						<span id="ays-quiz-deactivate-feedback-dialog-header-title"><?php echo esc_html__( 'Quick Feedback', 'quiz-maker' ); ?></span>
					</div>
		        </div>
		        <div class="ays-quiz-dialog-message ays-quiz-dialog-lightbox-message">
					<form id="ays-quiz-deactivate-feedback-dialog-form" method="post">
						<input type="hidden" id="ays_quiz_deactivate_feedback_nonce" name="ays_quiz_deactivate_feedback_nonce" value="<?php echo esc_attr($quiz_deactivate_feedback_nonce) ; ?>">
						<input type="hidden" name="action" value="ays_quiz_deactivate_feedback" />

						<div id="ays-quiz-deactivate-feedback-dialog-form-caption"><?php echo esc_html__( 'If you have a moment, please share why you are deactivating Quiz Maker:', 'quiz-maker' ); ?></div>
						<div id="ays-quiz-deactivate-feedback-dialog-form-body">
							<?php foreach ( $deactivate_reasons as $reason_key => $reason ) : ?>
								<div class="ays-quiz-deactivate-feedback-dialog-input-wrapper">
									<input id="ays-quiz-deactivate-feedback-<?php echo esc_attr( $reason_key ); ?>" class="ays-quiz-deactivate-feedback-dialog-input" type="radio" name="ays_quiz_reason_key" value="<?php echo esc_attr( $reason_key ); ?>" />
									<label for="ays-quiz-deactivate-feedback-<?php echo esc_attr( $reason_key ); ?>" class="ays-quiz-deactivate-feedback-dialog-label"><?php echo esc_html( $reason['title'] ); ?>
									<?php if ( ! empty( $reason['input_placeholder'] ) && empty( $reason['sub_reason'] ) ) : ?>
										<input class="ays-quiz-feedback-text" type="text" name="ays_quiz_reason_<?php echo esc_attr( $reason_key ); ?>" placeholder="<?php echo esc_attr( $reason['input_placeholder'] ); ?>" />
									<?php endif; ?>
									<?php if ( ! empty( $reason['alert'] ) ) : ?>
										<div class="ays-quiz-feedback-text ays-quiz-feedback-text-color"><?php echo wp_kses_post( $reason['alert'] ); ?></div>
									<?php endif; ?>
									<?php if ( ! empty( $reason['sub_reason'] ) && is_array($reason['sub_reason']) ) : ?>
										<div class="ays-quiz-deactivate-feedback-sub-dialog-input-wrapper">
										<?php foreach ( $reason['sub_reason'] as $sub_reason_key => $sub_reason ) : ?>
											<div class="ays-quiz-deactivate-feedback-dialog-input-wrapper">
												<input id="ays-quiz-deactivate-feedback-sub-<?php echo esc_attr( $sub_reason_key ); ?>" class="ays-quiz-deactivate-feedback-dialog-input" type="radio" name="ays_quiz_sub_reason_key" value="<?php echo esc_attr( $sub_reason_key ); ?>" />
												<label for="ays-quiz-deactivate-feedback-sub-<?php echo esc_attr( $sub_reason_key ); ?>" class="ays-quiz-deactivate-feedback-dialog-label"><?php echo esc_html( $sub_reason ); ?>
												</label>
											</div>
										<?php endforeach; ?>
										</div>
										<?php if ( ! empty( $reason['input_placeholder'] ) ) : ?>
											<input class="ays-quiz-feedback-text" type="text" name="ays_quiz_reason_<?php echo esc_attr( $reason_key ); ?>" placeholder="<?php echo esc_attr( $reason['input_placeholder'] ); ?>" />
										<?php endif; ?>
									<?php endif; ?>
									</label>
								</div>
							<?php endforeach; ?>
						</div>
					</form>
		        </div>
		        <div class="ays-quiz-dialog-buttons-wrapper ays-quiz-dialog-lightbox-buttons-wrapper">
		            <button class="ays-quiz-dialog-button ays-quiz-dialog-skip ays-quiz-dialog-lightbox-skip" data-type="skip"><?php echo esc_html__( 'Skip &amp; Deactivate', 'quiz-maker' ); ?></button>
		            <button class="ays-quiz-dialog-button ays-quiz-dialog-submit ays-quiz-dialog-lightbox-submit" data-type="submit"><?php echo esc_html__( 'Submit &amp; Deactivate', 'quiz-maker' ); ?></button>
		        </div>
    		</div>
		</div>
		<?php
	}

	/**
	 * Ajax Quiz Maker deactivate feedback.
	 *
	 * Send the user feedback when Quiz Maker is deactivated.
	 *
	 * Fired by `wp_ajax_ays_quiz_deactivate_feedback` action.
	 *
	 * @since 1.0.0
	 * @access public
	 */
	public function ays_quiz_deactivate_feedback() {

		if ( empty($_REQUEST['ays_quiz_deactivate_feedback_nonce']) ) {
			wp_send_json_error();
		}

		// Run a security check.
        check_ajax_referer( 'ays_quiz_deactivate_feedback_nonce', sanitize_key( $_REQUEST['_ajax_nonce'] ) );

		if ( ! current_user_can( 'activate_plugins' ) ) {
			wp_send_json_error( 'Permission denied' );
		}

		if (empty($_REQUEST['action']) || (isset($_REQUEST['action']) && $_REQUEST['action'] != 'ays_quiz_deactivate_feedback')) {
			wp_send_json_error( 'Action error' );
		}

		$reason_key = !empty($_REQUEST['ays_quiz_reason_key']) ? sanitize_text_field($_REQUEST['ays_quiz_reason_key']) : "";
		$sub_reason_key = !empty($_REQUEST['ays_quiz_sub_reason_key']) ? sanitize_text_field($_REQUEST['ays_quiz_sub_reason_key']) : "";
		$reason_text = !empty($_REQUEST["ays_quiz_reason_{$reason_key}"]) ? sanitize_text_field($_REQUEST["ays_quiz_reason_{$reason_key}"]) : "";
		$type = !empty($_REQUEST["type"]) ? sanitize_text_field($_REQUEST["type"]) : "";

		self::send_feedback( $reason_key, $sub_reason_key, $reason_text, $type );

		wp_send_json_success();
	}

	/**
	 * @since 1.0.0
	 * @access private
	 */
	private function is_plugins_screen() {
		return in_array( get_current_screen()->id, array( 'plugins', 'plugins-network' ) );
	}

	/**
	 * Send Feedback.
	 *
	 * Fires a request to Quiz Maker server with the feedback data.
	 *
	 * @since 1.0.0
	 * @access public
	 * @static
	 *
	 * @param string $feedback_key  Feedback key.
	 * @param string $feedback_text Feedback text.
	 *
	 * @return array The response of the request.
	 */
	public static function send_feedback( $feedback_key, $sub_feedback_key, $feedback_text, $type ) {
		return wp_remote_post( self::$api_feedback_url, array(
			'timeout' => 30,
			'body' => wp_json_encode(array(
				'type' 				=> 'quiz-maker',
				'version' 			=> AYS_QUIZ_VERSION,
				'first_version' 	=> get_option( 'ays_quiz_first_time_activation_verson' ),
				'site_lang' 		=> get_bloginfo( 'language' ),
				'button' 			=> $type,
				'feedback_key' 		=> $feedback_key,
				'sub_feedback_key' 	=> $sub_feedback_key,
				'feedback' 			=> $feedback_text,
			)),
		) );
	}
}
new Quiz_Maker_Feedback();
