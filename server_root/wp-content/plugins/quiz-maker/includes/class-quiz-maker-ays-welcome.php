<?php

class Quiz_Maker_Ays_Welcome {

    /**
     * Hidden welcome page slug.
     *
     * @since 4.6.4
     */
    const SLUG = 'quiz-maker-getting-started';

    /**
     * Primary class constructor.
     *
     * @since 4.6.4
     */
    public function __construct() {
        add_action( 'plugins_loaded', [ $this, 'hooks' ] );
    }

    public function hooks() {
		add_action( 'admin_menu', [ $this, 'register' ] );
		add_action( 'admin_head', [ $this, 'hide_menu' ] );
		add_action( 'admin_init', [ $this, 'redirect' ], 9999 );
        add_action( 'admin_enqueue_scripts', [ $this, 'enqueue_styles' ] );
        // add_action( 'admin_enqueue_scripts', [ $this, 'enqueue_scripts' ] );s
    }

	/**
	 * Register the pages to be used for the Welcome screen (and tabs).
	 *
	 * These pages will be removed from the Dashboard menu, so they will
	 * not actually show. Sneaky, sneaky.
	 *
	 * @since 1.0.0
	 */
	public function register() {
        add_dashboard_page(
			esc_html__( 'Welcome to Quiz Maker', "quiz-maker" ),
			esc_html__( 'Welcome to Quiz Maker', "quiz-maker" ),
			'manage_options',
			self::SLUG,
			[ $this, 'output' ]
		);
	}

    /**
     * Removed the dashboard pages from the admin menu.
     *
     * This means the pages are still available to us, but hidden.
     *
     * @since 4.6.4
     */
    public function hide_menu() {

        remove_submenu_page( 'index.php', self::SLUG );
    }

    /**
     * Welcome screen redirect.
     *
     * This function checks if a new install or update has just occurred. If so,
     * then we redirect the user to the appropriate page.
     *
     * @since 4.6.4
     */
    public function redirect() {

        $current_page = isset( $_GET['page'] ) ? $_GET['page'] : '';

        // Check if we are already on the welcome page.
        // if ( $current_page === self::SLUG ) {
        //     return;
        // }

        $terms_activation = get_option('ays_quiz_show_agree_terms');
        $first_activation = get_option('ays_quiz_first_time_activation_page', false);

        if($current_page === self::SLUG && $terms_activation && $terms_activation == 'hide'){
            wp_safe_redirect( admin_url( 'admin.php?page=quiz-maker' ) );
        }

        if(isset($_POST['ays_quiz_agree_terms']) && $_POST['ays_quiz_agree_terms'] === 'agree'){
            $this->ays_quiz_request( 'agree' );
            update_option('ays_quiz_agree_terms', 'true');
            update_option('ays_quiz_show_agree_terms', 'hide');
            wp_safe_redirect( admin_url( 'admin.php?page=quiz-maker-admin-dashboard' ) );
        }

        if(isset($_POST['ays_quiz_cancel_terms']) && $_POST['ays_quiz_cancel_terms'] === 'cancel'){
            $this->ays_quiz_request( 'cancel' );
            update_option('ays_quiz_agree_terms', 'false');
            update_option('ays_quiz_show_agree_terms', 'hide');
            wp_safe_redirect( admin_url( 'admin.php?page=quiz-maker-admin-dashboard' ) );
        }

        if($current_page === self::SLUG){
            return;
        }
        if ( isset($_GET['page']) && strpos($_GET['page'], AYS_QUIZ_NAME) !== false && !$terms_activation && $first_activation) {
            wp_safe_redirect( admin_url( 'index.php?page=' . self::SLUG ) );
            exit;
        }
        
    }

    /**
     * Enqueue custom CSS styles for the welcome page.
     *
     * @since 4.6.4
     */
    public function enqueue_styles() {
        wp_enqueue_style(
            'quiz-maker-welcome-css', 
            esc_url(AYS_QUIZ_ADMIN_URL) . '/css/quiz-maker-welcome.css',
            array(), AYS_QUIZ_VERSION, 'all');
    }

    /**
     * Getting Started screen. Shows after first install.
     *
     * @since 1.0.0
     */
    public function output() {
        ?>
            <style>
                #wpcontent  {
                    padding-left: 0 !important;
                    position: relative;
                }

                .ays-notice-banner,
                #wpfooter,
                #wpbody-content .ays_ask_question_content{
                    display: none;
                }
            </style>
            <form method="POST">
                <div id="ays-quiz-welcome">        
                    <div class="ays-quiz-welcome-container">        
                        <div class="ays-quiz-welcome-intro">        
                            <div class="ays-quiz-welcome-logo">
                                <img src="<?php echo esc_url(AYS_QUIZ_ADMIN_URL); ?>/images/icons/quiz-maker-logo.png" alt="<?php echo esc_html__( 'Quiz Maker Logo', "quiz-maker" ); ?>">
                            </div>
                            <div class="ays-quiz-welcome-block">
                                <h1><?php echo esc_html__( 'Thank you for using Quiz Maker plugin !', "quiz-maker" ); ?></h1>
                                <h6><?php echo esc_html__( 'To enhance the user experience of our product, we would like to request permission to track certain user interactions. Please rest assured that this tracking will be minimal and will only involve non-sensitive actions, such as specific clicks within the interface. No personal or sensitive data will be collected.', "quiz-maker" ); ?></h6>
                                <h6 style="margin-top: 20px;"><?php echo esc_html__( 'Our goal is solely to improve the functionality and usability of the product based on user behavior.', "quiz-maker" ); ?></h6>
                            </div>        
                            <div class="ays-quiz-welcome-block-buttons">        
                                <div class="ays-quiz-welcome-button-wrap ays-quiz-clear">
                                    <div class="ays-quiz-welcome-left">
                                        <button 
                                        class="ays-quiz-tu-cancel" 
                                        type="submit"
                                         name="ays_quiz_cancel_terms"
                                          value="cancel">
                                            <?php echo esc_html__( 'Cancel', "quiz-maker" ); ?>
                                        </button>
                                    </div>
                                    <div class="ays-quiz-welcome-right">
                                        <button class="ays-quiz-tu-agree" target="_blank" type="submit" name="ays_quiz_agree_terms" value="agree">
                                            <?php echo esc_html__( 'Agree & continue', "quiz-maker" ); ?>
                                        </button>
                                    </div>
                                </div>        
                            </div>        
                        </div>
                    </div>
                </div>
            </form>
        <?php
    }

    public function ays_quiz_request($cta){
        $curl = curl_init();

        $api_url = "https://poll-plugin.com/quiz-maker/";

        $data = array(
            'type'  => 'quiz-maker',
            'cta'   => $cta,
        );

        wp_remote_post( $api_url, array(
            'timeout' => 30,
            'body' => wp_json_encode(array(
                'type'  => 'quiz-maker',
                'cta'   => $cta,
            )),
        ) );
    }
}
new Quiz_Maker_Ays_Welcome();