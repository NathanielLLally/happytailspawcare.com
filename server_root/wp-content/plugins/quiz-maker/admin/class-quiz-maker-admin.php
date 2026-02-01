<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       http://ays-pro.com/
 * @since      1.0.0
 *
 * @package    Quiz_Maker
 * @subpackage Quiz_Maker/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Quiz_Maker
 * @subpackage Quiz_Maker/admin
 * @author     AYS Pro LLC <info@ays-pro.com>
 */

class Quiz_Maker_Admin
{

    /**
     * The ID of this plugin.
     *
     * @since    1.0.0
     * @access   private
     * @var      string $plugin_name The ID of this plugin.
     */
    private $plugin_name;

    /**
     * The version of this plugin.
     *
     * @since    1.0.0
     * @access   private
     * @var      string $version The current version of this plugin.
     */
    private $version;


    private $quizes_obj;
    private $quiz_categories_obj;
    private $questions_obj;
    private $question_categories_obj;
    private $results_obj;
    private $settings_obj;
    private $all_reviews_obj;
    private $question_reports_obj;

    private $capability;
    
    /**
     * Initialize the class and set its properties.
     *
     * @since    1.0.0
     * @param      string $plugin_name The name of this plugin.
     * @param      string $version The version of this plugin.
     */
    public function __construct($plugin_name, $version){

        $this->plugin_name = $plugin_name;
        $this->version = $version;
        add_filter('set-screen-option', array(__CLASS__, 'set_screen'), 10, 3);
        $per_page_array = array(
            'quizes_per_page',
            'questions_per_page',
            'quiz_categories_per_page',
            'question_categories_per_page',
            'quiz_results_per_page',
            'quiz_all_reviews_per_page',
            'quiz_question_reports_per_page',
        );
        foreach($per_page_array as $option_name){
            add_filter('set_screen_option_'.$option_name, array(__CLASS__, 'set_screen'), 10, 3);
        }
    }

    /**
     * Register the styles for the admin menu area.
     *
     * @since    1.0.0
     */
    public function admin_menu_styles(){
        
        echo "<style>
            .ays_menu_badge{
                color: #fff;
                display: inline-block;
                font-size: 10px;
                line-height: 14px;
                text-align: center;
                background: #ca4a1f;
                margin-left: 5px;
                border-radius: 20px;
                padding: 2px 5px;
            }

            #adminmenu a.toplevel_page_quiz-maker div.wp-menu-image img {
                width: 32px;
                padding: 1px 0 0;
                transition: .3s ease-in-out;
            }
        </style>";

    }

    /**
     * Register the stylesheets for the admin area.
     *
     * @since    1.0.0
     */
    public function enqueue_styles($hook_suffix){
        wp_enqueue_style($this->plugin_name . '-admin', plugin_dir_url(__FILE__) . 'css/admin.css', array(), $this->version, 'all');
        wp_enqueue_style($this->plugin_name . '-sweetalert-css', AYS_QUIZ_PUBLIC_URL . '/css/quiz-maker-sweetalert2.min.css', array(), $this->version, 'all');

        if (false === strpos($hook_suffix, $this->plugin_name))
            return;

        /**
         * This function is provided for demonstration purposes only.
         *
         * An instance of this class should be passed to the run() function
         * defined in Quiz_Maker_Loader as all of the hooks are defined
         * in that particular class.
         *
         * The Quiz_Maker_Loader will then create the relationship
         * between the defined hooks and the functions defined in this
         * class.
         */
        wp_enqueue_style('wp-color-picker');
        // You need styling for the datepicker. For simplicity I've linked to the jQuery UI CSS on a CDN.
        // wp_register_style( 'jquery-ui', 'https://code.jquery.com/ui/1.12.1/themes/smoothness/jquery-ui.css' );
        // wp_enqueue_style( 'jquery-ui' );

        wp_enqueue_style($this->plugin_name . '-animate.css', plugin_dir_url(__FILE__) . 'css/animate.css', array(), $this->version, 'all');
        wp_enqueue_style($this->plugin_name . '-sweetalert-css', AYS_QUIZ_PUBLIC_URL . '/css/quiz-maker-sweetalert2.min.css', array(), $this->version, 'all');
        wp_enqueue_style($this->plugin_name . '-font-awesome', AYS_QUIZ_PUBLIC_URL . '/css/quiz-maker-font-awesome.min.css', array(), $this->version, 'all');
        wp_enqueue_style($this->plugin_name . '-select2', AYS_QUIZ_PUBLIC_URL .  '/css/quiz-maker-select2.min.css', array(), $this->version, 'all');
        wp_enqueue_style($this->plugin_name . '-bootstrap', plugin_dir_url(__FILE__) . 'css/bootstrap.min.css', array(), $this->version, 'all');
        wp_enqueue_style($this->plugin_name . '-data-bootstrap', plugin_dir_url(__FILE__) . 'css/dataTables.bootstrap4.min.css', array(), $this->version, 'all');
        wp_enqueue_style($this->plugin_name . '-jquery-datetimepicker', plugin_dir_url(__FILE__) . 'css/jquery-ui-timepicker-addon.css', array(), $this->version, 'all');
        wp_enqueue_style($this->plugin_name, plugin_dir_url(__FILE__) . 'css/quiz-maker-admin.css', array(), $this->version, 'all');
        // wp_enqueue_style($this->plugin_name, plugin_dir_url(__FILE__) . 'css/unminified/quiz-maker-admin.css', array(), $this->version, 'all');
        wp_enqueue_style($this->plugin_name . '-banner', plugin_dir_url(__FILE__) . 'css/quiz-maker-banner.css', array(), $this->version, 'all');
        wp_enqueue_style($this->plugin_name . "-loaders", plugin_dir_url(__FILE__) . 'css/loaders.css', array(), $this->version, 'all');

        if( isset( $_GET['page'] ) && sanitize_key($_GET['page']) == $this->plugin_name . '-admin-dashboard' ){
            wp_enqueue_style( $this->plugin_name . "-dashboard", plugin_dir_url( __FILE__ ) . 'css/quiz-maker-dashboard.css', array(), $this->version, 'all' );
        }

    }

    /**
     * Register the JavaScript for the admin area.
     *
     * @since    1.0.0
     */
    public function enqueue_scripts($hook_suffix){
        global $wp_version;

        $version1 = $wp_version;
        $operator = '>=';
        $version2 = '5.5';
        $versionCompare = $this->versionCompare($version1, $operator, $version2);

        $check_terms_agreement = get_option('ays_quiz_agree_terms');
        if($check_terms_agreement === 'true' && strpos($hook_suffix, $this->plugin_name) !== false){
            wp_enqueue_script( $this->plugin_name . '-hotjar', plugin_dir_url(__FILE__) . 'js/extras/quiz-maker-hotjar.js', array(), $this->version, false);
            wp_localize_script($this->plugin_name . '-hotjar',  'quiz_maker_hotjar', array(
                'id' => '2c39f44b-7257-418e-8bba-9c78a81e8ee9',
            ));
        }

        if ($versionCompare) {
            wp_enqueue_script( $this->plugin_name.'-wp-load-scripts', plugin_dir_url(__FILE__) . 'js/ays-wp-load-scripts.js', array(), $this->version, true);
        }

        if (false !== strpos($hook_suffix, "plugins.php")){
            wp_enqueue_script($this->plugin_name . '-sweetalert-js', AYS_QUIZ_PUBLIC_URL . '/js/quiz-maker-sweetalert2.all.min.js', array('jquery'), $this->version, true );
            wp_enqueue_script($this->plugin_name . '-admin', plugin_dir_url(__FILE__) . 'js/admin.js', array('jquery'), $this->version, true);
            wp_localize_script($this->plugin_name . '-admin',  'quiz_maker_admin_ajax', array(
                'ajax_url'              => admin_url('admin-ajax.php'),
                'errorMsg'              => __( "Error", 'quiz-maker' ),
                'loadResource'          => __( "Can't load resource.", 'quiz-maker' ),
                'somethingWentWrong'    => __( "Maybe something went wrong.", 'quiz-maker' ),
            ));
        }

        if (false === strpos($hook_suffix, $this->plugin_name))
            return;

        /**
         * This function is provided for demonstration purposes only.
         *
         * An instance of this class should be passed to the run() function
         * defined in Quiz_Maker_Loader as all of the hooks are defined
         * in that particular class.
         *
         * The Quiz_Maker_Loader will then create the relationship
         * between the defined hooks and the functions defined in this
         * class.
         */

        $quiz_banner_date = $this->ays_quiz_update_banner_time();

        wp_enqueue_script('jquery');
        wp_enqueue_script('jquery-effects-core');
        wp_enqueue_script('jquery-ui-sortable');
        wp_enqueue_script('jquery-ui-datepicker');

        wp_enqueue_script( $this->plugin_name . '-color-picker-alpha', plugin_dir_url(__FILE__) . 'js/wp-color-picker-alpha.min.js', array( 'wp-color-picker' ), $this->version, true );
        $color_picker_strings = array(
            'clear'            => __( 'Clear', 'quiz-maker' ),
            'clearAriaLabel'   => __( 'Clear color', 'quiz-maker' ),
            'defaultString'    => __( 'Default', 'quiz-maker' ),
            'defaultAriaLabel' => __( 'Select default color', 'quiz-maker' ),
            'pick'             => __( 'Select Color', 'quiz-maker' ),
            'defaultLabel'     => __( 'Color value', 'quiz-maker' ),
        );
        wp_localize_script( $this->plugin_name . '-color-picker-alpha', 'wpColorPickerL10n', $color_picker_strings );

        wp_enqueue_media();
        wp_enqueue_script('ays_quiz_popper', plugin_dir_url(__FILE__) . 'js/popper.min.js', array('jquery'), $this->version, false);
        wp_enqueue_script('ays_quiz_bootstrap', plugin_dir_url(__FILE__) . 'js/bootstrap.min.js', array('jquery'), $this->version, false);
        wp_enqueue_script($this->plugin_name . '-select2js', AYS_QUIZ_PUBLIC_URL . '/js/quiz-maker-select2.min.js', array('jquery'), $this->version, true);
        wp_enqueue_script($this->plugin_name . '-sweetalert-js', AYS_QUIZ_PUBLIC_URL . '/js/quiz-maker-sweetalert2.all.min.js', array('jquery'), $this->version, true );
        wp_enqueue_script($this->plugin_name . '-datatable-min', AYS_QUIZ_PUBLIC_URL . '/js/quiz-maker-datatable.min.js', array('jquery'), $this->version, true);
		wp_enqueue_script($this->plugin_name . "-db4.min.js", plugin_dir_url( __FILE__ ) . 'js/dataTables.bootstrap4.min.js', array( 'jquery' ), $this->version, true );
		wp_enqueue_script($this->plugin_name . "-jquery.datetimepicker.js", plugin_dir_url( __FILE__ ) . 'js/jquery-ui-timepicker-addon.js', array( 'jquery' ), $this->version, true );
        wp_enqueue_script($this->plugin_name . '-ajax', plugin_dir_url(__FILE__) . 'js/quiz-maker-admin-ajax.js', array('jquery'), $this->version, true);
        // wp_enqueue_script($this->plugin_name . '-ajax', plugin_dir_url(__FILE__) . 'js/unminified/quiz-maker-admin-ajax.js', array('jquery'), $this->version, true);
        wp_enqueue_script($this->plugin_name, plugin_dir_url(__FILE__) . 'js/quiz-maker-admin.js', array('jquery', 'wp-color-picker'), $this->version, true);
        // wp_enqueue_script($this->plugin_name, plugin_dir_url(__FILE__) . 'js/unminified/quiz-maker-admin.js', array('jquery', 'wp-color-picker'), $this->version, true);
        
        wp_localize_script($this->plugin_name . '-ajax', 'quiz_maker_ajax', array(
            'ajax_url'                          => admin_url('admin-ajax.php'),
            'nonce'                             => wp_create_nonce('ays_quiz_nonce'),
            "emptyEmailError"                   => __( 'Email field is empty', 'quiz-maker'),
            "invalidEmailError"                 => __( 'Invalid Email address', 'quiz-maker'),
            'selectUser'                        => __( 'Select user', 'quiz-maker'),
            'pleaseEnterMore'                   => __( "Please enter 1 or more characters", 'quiz-maker' ),
            'searching'                         => __( "Searching...", 'quiz-maker' ),
            'activated'                         => __( "Activated", 'quiz-maker' ),
            'errorMsg'                          => __( "Error", 'quiz-maker' ),
            'loadResource'                      => __( "Can't load resource.", 'quiz-maker' ),
            'somethingWentWrong'                => __( "Maybe something went wrong.", 'quiz-maker' ),
            'youCanUuseThisShortcode'           => __( 'Copy the generated shortcode and paste it into any post or page to display Quiz.', 'quiz-maker'),
            'youQuizIsCreated'                  => __( 'Your Quiz is Created!', 'quiz-maker'),
            'greateJob'                         => __( 'Great job', 'quiz-maker'),
            'formMoreDetailed'                  => __( 'For more detailed configuration visit', 'quiz-maker'),
            'editQuizPage'                      => __( 'edit quiz page', 'quiz-maker'),
            'greate'                            => __( 'Great!', 'quiz-maker'),
            'thumbsUpGreat'                     => __( 'Thumbs up, great!', 'quiz-maker'),
            "preivewQuiz"                       => __( "Preview Quiz", 'quiz-maker' ),
            "mustSelectNewQuestion"             => __( "You must select new questions to add to the quiz.", 'quiz-maker' ),

            "checklistMarkAsDone"               => __( "Mark as done", 'quiz-maker' ),
            "checklistUnmarkAsDone"             => __( "Unmark as done", 'quiz-maker' ),
        ));
        wp_localize_script( $this->plugin_name, 'quizLangObj', array(
            'quizBannerDate'                    => $quiz_banner_date,
            'AYS_QUIZ_ADMIN_URL'                => AYS_QUIZ_ADMIN_URL,
            'AYS_QUIZ_UTM_VERSION'              => AYS_QUIZ_UTM_VERSION,
            'questionPageUrl'                   => admin_url( 'admin.php?page=quiz-maker-questions' ),

            'questionTitle'                     => __( 'Question Default Title', 'quiz-maker'),
            'radio'                             => __( 'Radio', 'quiz-maker'),
            'checkbox'                          => __( 'Checkbox', 'quiz-maker'),
            'dropdawn'                          => __( 'Dropdown', 'quiz-maker'),
            'emptyAnswer'                       => __( 'Empty Answer', 'quiz-maker'),
            'addGif'                            => __( 'Add Gif', 'quiz-maker'),
            'textType'                          => __( 'Text', 'quiz-maker'),
            'answerText'                        => __( 'Answer text', 'quiz-maker'),
            'copied'                            => __( 'Copied!', 'quiz-maker'),
            'clickForCopy'                      => __( 'Click for copy.', 'quiz-maker'),
            'shortTextType'                     => __( 'Short Text', 'quiz-maker'),
            'true'                              => __( 'True', 'quiz-maker'),
            'false'                             => __( 'False', 'quiz-maker'),
            'number'                            => __( 'Number', 'quiz-maker'),
            'trueOrFalse'                       => __( 'True/False', 'quiz-maker'),
            'date'                              => __( 'Date', 'quiz-maker'),
            'currentTime'                       => current_time( 'Y-m-d' ),
            'nextQustionPage'                   => __( 'Are you sure you want to go to the next question page?', 'quiz-maker'),
            'areYouSureButton'                  => __( 'Are you sure you want to redirect to another quiz? Note that the changes made in this quiz will not be saved.', 'quiz-maker'),
            'deleteQuestion'                    => __( 'Are you sure you want to delete question ?', 'quiz-maker'),
            'deleteElementFromListTable'        => __( 'Are you sure you want to delete?', 'quiz-maker'),
            'youCanUuseThisShortcode'           => __( 'Copy the generated shortcode and paste it into any post or page to display Quiz.', 'quiz-maker'),
            'youQuizIsCreated'                  => __( 'Your Quiz is Created!', 'quiz-maker'),
            'greateJob'                         => __( 'Great job', 'quiz-maker'),
            'formMoreDetailed'                  => __( 'For more detailed configuration visit', 'quiz-maker'),
            'editQuizPage'                      => __( 'edit quiz page', 'quiz-maker'),
            'greate'                            => __( 'Done', 'quiz-maker'),
            'thumbsUpGreat'                     => __( 'Thumbs up, great!', 'quiz-maker'),

            "all"                               => __( "All", 'quiz-maker' ),
            "selectCategory"                    => __( "Select Category", 'quiz-maker' ),
            "selectTags"                        => __( "Select Tags", 'quiz-maker' ),
            "preivewQuiz"                       => __( "Preview Quiz", 'quiz-maker' ),
            "createQuestion"                    => __( "Create question", 'quiz-maker' ),
            "insertQuestion"                    => __( "Insert questions", 'quiz-maker' ),

            "successCopyCoupon"                 => __( "Coupon code copied!", 'quiz-maker' ),
            "failedCopyCoupon"                  => __( "Failed to copy coupon code", 'quiz-maker' ),

            'generalTabDoc'                     => esc_html__( "How to Configure General Settings?", 'quiz-maker' ),
            'stylesTabDoc'                      => esc_html__( "How to Configure Styles Tab?", 'quiz-maker' ),
            'settingsTabDoc'                    => esc_html__( "How to Configure Settings Tab?", 'quiz-maker' ),
            'resultsSettingsTabDoc'             => esc_html__( "How to Configure Results Settings Tab?", 'quiz-maker' ),
            'limitationUsersTabDoc'             => esc_html__( "How to Configure Limitation Users Tab?", 'quiz-maker' ),
            'userDataTabDoc'                    => esc_html__( "How to Configure User Data Tab?", 'quiz-maker' ),
            'emailTabDoc'                       => esc_html__( "How to Configure E-Mail, Certificate Tab?", 'quiz-maker' ),
            'integrationTabDoc'                 => esc_html__( "How to Configure Integration Tab?", 'quiz-maker' ),

            'addAnswer'                         => esc_html__( "Add Answer", 'quiz-maker' ),
            'add'                               => esc_html__( 'Add', 'quiz-maker'),
            'editImage'                         => esc_html__( 'Edit Image', 'quiz-maker'),
            'deleteImage'                       => esc_html__( 'Delete Image', 'quiz-maker'),
        ) );

        wp_localize_script( $this->plugin_name, 'quizLangDataTableObj', array(
            "sEmptyTable"                       => __( "No data available in table", 'quiz-maker' ),
            'sInfo' => sprintf(
                /* translators: 1: start record number, 2: end record number, 3: total records */
                __( 'Showing %1$s to %2$s of %3$s entries', 'quiz-maker' ),
                '_START_',
                '_END_',
                '_TOTAL_'
            ),
            "sInfoEmpty"                        => __( "Showing 0 to 0 of 0 entries", 'quiz-maker' ),
            'sInfoFiltered' => sprintf(
                /* translators: %s: total number of entries */
                __( '(filtered from %s total entries)', 'quiz-maker' ),
                '_MAX_'
            ),
            'sLengthMenu'                       => sprintf(
                /* translators: %s: number of entries to show */
                __( 'Show %s entries', 'quiz-maker' ),
                '_MENU_'
            ),
            "sLoadingRecords"                   => __( "Loading...",'quiz-maker' ),
            "sProcessing"                       => __( "Processing...", 'quiz-maker' ),
            "sSearch"                           => __( "Search:", 'quiz-maker' ),
            // "sUrl":                          => __( "", $this->plugin_name ),
            "sZeroRecords"                      => __( "No matching records found", 'quiz-maker' ),
            "sFirst"                            => __( "First", 'quiz-maker' ),
            "sLast"                             => __( "Last", 'quiz-maker' ),
            "sNext"                             => __( "Next", 'quiz-maker' ),
            "sPrevious"                         => __( "Previous", 'quiz-maker' ),
            "sSortAscending"                    => __( ": activate to sort column ascending", 'quiz-maker' ),
            "sSortDescending"                   => __( ": activate to sort column descending",'quiz-maker' ),

            "all"                               => __( "All", 'quiz-maker' ),
            "selectCategory"                    => __( "Select Category", 'quiz-maker' ),
            "selectTags"                        => __( "Select Tags", 'quiz-maker' ),
        ) );

        $question_categories = $this->get_question_categories();
        wp_localize_script( $this->plugin_name, 'aysQuizCatObj', array(
            'category' => $question_categories,
        ) );

    }

    /**
     * De-register JavaScript files for the admin area.
     *
     * @since    1.0.0
     */
    public function disable_scripts($hook_suffix) {
        if (false !== strpos($hook_suffix, $this->plugin_name)) {
            if (is_plugin_active('ai-engine/ai-engine.php')) {
                wp_deregister_script('mwai');
                wp_deregister_script('mwai-vendor');
                wp_dequeue_script('mwai');
                wp_dequeue_script('mwai-vendor');
            }

            if (is_plugin_active('html5-video-player/html5-video-player.php')) {
                wp_dequeue_style('h5vp-admin');
                wp_dequeue_style('fs_common');
            }

            if (is_plugin_active('panorama/panorama.php')) {
                wp_dequeue_style('bppiv_admin_custom_css');
                wp_dequeue_style('bppiv-custom-style');
            }

            if (is_plugin_active('wp-social/wp-social.php')) {
                wp_dequeue_style('wp_social_select2_css');
                wp_deregister_script('wp_social_select2_js');
                wp_dequeue_script('wp_social_select2_js');
            }

            if (is_plugin_active('happyforms/happyforms.php')) {
                wp_dequeue_style('happyforms-admin');
            }

            if (is_plugin_active('ultimate-viral-quiz/index.php')) {
                wp_dequeue_style('select2');
                wp_dequeue_style('dataTables');
                
                wp_dequeue_script('sweetalert');
                wp_dequeue_script('select2');
                wp_dequeue_script('dataTables');
            }

            if (is_plugin_active('forms-by-made-it/madeit-form.php')) {
                wp_dequeue_style('madeit-form-admin-style');
            }

            if (is_plugin_active('real-media-library-lite/index.php')) {
                wp_dequeue_style('real-media-library-lite-rml');
            }

            if (is_plugin_active('wpschoolpress/wpschoolpress.php')) {
                wp_dequeue_style('wpsp_wp_admin_wpsp-style');
                wp_dequeue_script('wpsp_wp_admin_jquery8');
            }

            // Theme | Pixel Ebook Store
            wp_dequeue_style('pixel-ebook-store-free-demo-content-style');
            // Theme | Interactive Education
            wp_dequeue_style('interactive-education-free-demo-content-style');
            // Theme | Phlox 2.17.6
            wp_dequeue_style('auxin-admin-style');
            // Theme | Mavix Education 1.0
            wp_dequeue_style('mavix-education-admin-style');
            // Theme | RT Education School 1.1.9
            wp_dequeue_style('rt-education-school-custom-admin-style');
            wp_dequeue_style('rt-education-school-custom-admin-notice-style');
        }

        if (is_plugin_active('search-replace-for-block-editor/search-replace-for-block-editor.php')) {
            wp_deregister_script('search-replace-for-block-editor');
            wp_dequeue_script('search-replace-for-block-editor');
        }
    }

    public function ays_quiz_disable_all_notice_from_plugin() {
        if (!function_exists('get_current_screen')) {
            return;
        }

        $screen = get_current_screen();

        if (empty($screen) || strpos($screen->id, $this->plugin_name) === false) {
            return;
        }

        global $wp_filter;

        // Keep plugin-specific notices
        $our_plugin_notices = array();

        $exclude_functions = [
            'quiz_maker_general_admin_notice',
        ];

        if (!empty($wp_filter['admin_notices'])) {
            foreach ($wp_filter['admin_notices']->callbacks as $priority => $callbacks) {
                foreach ($callbacks as $key => $callback) {
                    // For class-based methods
                    if (
                        is_array($callback['function']) &&
                        is_object($callback['function'][0]) &&
                        get_class($callback['function'][0]) === __CLASS__
                    ) {
                        $our_plugin_notices[$priority][$key] = $callback;
                    }
                    // For standalone functions
                    elseif (
                        is_string($callback['function']) &&
                        in_array($callback['function'], $exclude_functions)
                    ) {
                        $our_plugin_notices[$priority][$key] = $callback;
                    }
                }
            }
        }

        // Remove all notices
        remove_all_actions('admin_notices');
        remove_all_actions('all_admin_notices');

        // Re-add only your plugin's notices
        foreach ($our_plugin_notices as $priority => $callbacks) {
            foreach ($callbacks as $callback) {
                add_action('admin_notices', $callback['function'], $priority);
            }
        }
    }

    public function ays_quiz_get_all_plugins_scripts_styles() {

        $result = array();
        $result['scripts'] = array();
        $result['styles'] = array();

        // Print all loaded Scripts
        global $wp_scripts;
        foreach( $wp_scripts->queue as $script ){
           $result['scripts'][$script] =  $wp_scripts->registered[$script]->src;
        }

        // Print all loaded Styles (CSS)
        global $wp_styles;
        foreach( $wp_styles->queue as $style ){ 
           $result['styles'][$style] =  $wp_styles->registered[$style]->src;
        }

        return $result;
    }

    /**
     * Remove HookFilter for the quiz admin area.
     *
     * @since    1.0.0
     */
    public function remove_hook_and_filter_from_dashboard() {
        if (!empty($_GET['page']) && false !== strpos(sanitize_text_field($_GET['page']), $this->plugin_name) ) {
            if (is_plugin_active('stepform/stepFORM.php') || is_plugin_active('stepform/stepform.php')) {
                if (class_exists('stepFORM_MCE')) {
                    remove_filter('mce_buttons_2', array('stepFORM_MCE', 'mce_buttons_2'));
                }
            }
        }
    }


    function codemirror_enqueue_scripts($hook) {
        if (false === strpos($hook, $this->plugin_name)){
            return;
        }
        if(function_exists('wp_enqueue_code_editor')){
            $cm_settings['codeEditor'] = wp_enqueue_code_editor(array(
                'type' => 'text/css',
                'codemirror' => array(
                    'inputStyle' => 'contenteditable',
                    'theme' => 'cobalt',
                )
            ));

            wp_enqueue_script('wp-theme-plugin-editor');
            wp_localize_script('wp-theme-plugin-editor', 'cm_settings', $cm_settings);
        
            wp_enqueue_style('wp-codemirror');
        }
    }

    function versionCompare($version1, $operator, $version2) {
   
        $_fv = intval ( trim ( str_replace ( '.', '', $version1 ) ) );
        $_sv = intval ( trim ( str_replace ( '.', '', $version2 ) ) );
       
        if (strlen ( $_fv ) > strlen ( $_sv )) {
            $_sv = str_pad ( $_sv, strlen ( $_fv ), 0 );
        }
       
        if (strlen ( $_fv ) < strlen ( $_sv )) {
            $_fv = str_pad ( $_fv, strlen ( $_sv ), 0 );
        }
       
        return version_compare ( ( string ) $_fv, ( string ) $_sv, $operator );
    }


    /**
     * Register the administration menu for this plugin into the WordPress Dashboard menu.
     *
     * @since    1.0.0
     */
    public function add_plugin_admin_menu(){

        /*
         * Add a settings page for this plugin to the Settings menu.
         *
         * NOTE:  Alternative menu locations are available via WordPress administration menu functions.
         *
         *        Administration Menus: http://codex.wordpress.org/Administration_Menus
         *
         */
        $setting_actions = new Quiz_Maker_Settings_Actions($this->plugin_name);
        $options = ($setting_actions->ays_get_setting('options') === false) ? array() : json_decode( stripcslashes( $setting_actions->ays_get_setting('options') ), true);


        // Disable Quiz maker menu item notification
        $options['quiz_disable_quiz_menu_notification'] = isset($options['quiz_disable_quiz_menu_notification']) ? esc_attr( $options['quiz_disable_quiz_menu_notification'] ) : 'off';
        $quiz_disable_quiz_menu_notification = (isset($options['quiz_disable_quiz_menu_notification']) && esc_attr( $options['quiz_disable_quiz_menu_notification'] ) == "on") ? true : false;

        if( $quiz_disable_quiz_menu_notification ){
            $menu_item = 'Quiz Maker';
        } else {
            global $wpdb;
            $sql = "SELECT COUNT(*) FROM {$wpdb->prefix}aysquiz_reports WHERE `read` = 0";
            $unread_results_count = $wpdb->get_var($sql);        
            $menu_item = ($unread_results_count == 0) ? 'Quiz Maker' : 'Quiz Maker' . '<span class="ays_menu_badge ays_results_bage">' . $unread_results_count . '</span>';
        }

        $this->capability = self::quiz_maker_capabilities();
        
        add_menu_page(
            'Quiz Maker', 
            $menu_item,
            $this->capability,
            $this->plugin_name, 
            array($this, 'display_plugin_quiz_page'), 
            AYS_QUIZ_ADMIN_URL . '/images/icons/icon-quiz-maker-128x128.svg', 
            '6.20'
        );
    }
    
    public function add_plugin_quizzes_submenu(){
        $hook_quiz_maker = add_submenu_page(
            $this->plugin_name,
            __('Quizzes', 'quiz-maker'),
            __('Quizzes', 'quiz-maker'),
            $this->capability,
            $this->plugin_name,
            array($this, 'display_plugin_quiz_page')
        );

        add_action("load-$hook_quiz_maker", array($this, 'screen_option_quizes'));
        add_action("load-$hook_quiz_maker", array( $this, 'add_tabs' ));
    }

    public function add_plugin_questions_submenu(){

        $actual_reports_count = self::get_actual_reports_count();

        $setting_actions = new Quiz_Maker_Settings_Actions($this->plugin_name);
        $options = ($setting_actions->ays_get_setting('options') === false) ? array() : json_decode( stripcslashes( $setting_actions->ays_get_setting('options') ), true);

        // Disable Results menu item notification
        $options['quiz_disable_question_report_menu_notification'] = isset($options['quiz_disable_question_report_menu_notification']) ? esc_attr( $options['quiz_disable_question_report_menu_notification'] ) : 'off';
        $quiz_disable_question_report_menu_notification = (isset($options['quiz_disable_question_report_menu_notification']) && esc_attr( $options['quiz_disable_question_report_menu_notification'] ) == "on") ? true : false;

        $menu_item = __('Questions', 'quiz-maker');
        if ($actual_reports_count > 0 && !$quiz_disable_question_report_menu_notification) {
            $menu_item .= '<span class="ays_menu_badge ays_results_bage">' . $actual_reports_count . '</span>';
        }

        $hook_questions = add_submenu_page(
            $this->plugin_name,
            __('Questions', 'quiz-maker'),
            $menu_item,
            $this->capability,
            $this->plugin_name . '-questions',
            array($this, 'display_plugin_questions_page')
        );

        add_action("load-$hook_questions", array($this, 'screen_option_questions'));
        add_action("load-$hook_questions", array( $this, 'add_tabs' ));

        $hook_all_results = add_submenu_page(
            'question_reports_slug',
            __('Reports', 'quiz-maker'),
            null,
            $this->capability,
            $this->plugin_name . '-question-reports',
            array($this, 'display_plugin_question_reports_page')
        );

        add_action("load-$hook_all_results", array($this, 'screen_option_question_reports'));
        add_action("load-$hook_all_results", array( $this, 'add_tabs' ));
    }

    public function add_plugin_quiz_categories_submenu(){
        $hook_quiz_categories = add_submenu_page(
            $this->plugin_name,
            __('Quiz Categories', 'quiz-maker'),
            __('Quiz Categories', 'quiz-maker'),
            $this->capability,
            $this->plugin_name . '-quiz-categories',
            array($this, 'display_plugin_quiz_categories_page')
        );

        add_action("load-$hook_quiz_categories", array($this, 'screen_option_quiz_categories'));
        add_action("load-$hook_quiz_categories", array( $this, 'add_tabs' ));
    }

    public function add_plugin_questions_categories_submenu(){
        $hook_questions_categories = add_submenu_page(
            $this->plugin_name,
            __('Question Categories', 'quiz-maker'),
            __('Question Categories', 'quiz-maker'),
            $this->capability,
            $this->plugin_name . '-question-categories',
            array($this, 'display_plugin_question_categories_page')
        );

        add_action("load-$hook_questions_categories", array($this, 'screen_option_questions_categories'));
        add_action("load-$hook_questions_categories", array( $this, 'add_tabs' ));
    }

    public function add_plugin_custom_fields_submenu(){
        $hook_quiz_categories = add_submenu_page(
            $this->plugin_name,
            __('Custom Fields', 'quiz-maker'),
            __('Custom Fields', 'quiz-maker'),
            $this->capability,
            $this->plugin_name . '-quiz-attributes',
            array($this, 'display_plugin_quiz_attributes_page')
        );
        add_action("load-$hook_quiz_categories", array( $this, 'add_tabs' ));
    }

    public function add_plugin_orders_submenu(){
        $hook_quiz_orders = add_submenu_page(
            $this->plugin_name,
            __('Orders', 'quiz-maker'),
            __('Orders', 'quiz-maker'),
            'manage_options',
            $this->plugin_name . '-quiz-orders',
            array($this, 'display_plugin_orders_page')
        );
        add_action("load-$hook_quiz_orders", array( $this, 'add_tabs' ));
    }

    public function add_plugin_results_submenu(){
        global $wpdb;

        $setting_actions = new Quiz_Maker_Settings_Actions($this->plugin_name);
        $options = ($setting_actions->ays_get_setting('options') === false) ? array() : json_decode( stripcslashes( $setting_actions->ays_get_setting('options') ), true);


        // Disable Results menu item notification
        $options['quiz_disable_results_menu_notification'] = isset($options['quiz_disable_results_menu_notification']) ? esc_attr( $options['quiz_disable_results_menu_notification'] ) : 'off';
        $quiz_disable_results_menu_notification = (isset($options['quiz_disable_results_menu_notification']) && esc_attr( $options['quiz_disable_results_menu_notification'] ) == "on") ? true : false;

        if( $quiz_disable_results_menu_notification ){
            $results_text = __('Results', 'quiz-maker');
            $menu_item = __('Results', 'quiz-maker');
        } else {
            $sql = "SELECT COUNT(*) FROM {$wpdb->prefix}aysquiz_reports WHERE `read` = 0";
            $unread_results_count = $wpdb->get_var($sql);
            $results_text = __('Results', 'quiz-maker');
            $menu_item = ($unread_results_count == 0) ? $results_text : $results_text . '<span class="ays_menu_badge ays_results_bage">' . $unread_results_count . '</span>';
        }
        
        $hook_results = add_submenu_page(
            'quiz-maker',
            $results_text,
            $menu_item,
            $this->capability,
            'quiz-maker' . '-results',
            array($this, 'display_plugin_results_page')
        );

        add_action("load-$hook_results", array($this, 'screen_option_results'));
        add_action("load-$hook_results", array( $this, 'add_tabs' ));

        $hook_all_reviews = add_submenu_page(
            'all_reviews_slug',
            __('Reviews', 'quiz-maker'),
            null,
            $this->capability,
            $this->plugin_name . '-all-reviews',
            array($this, 'display_plugin_all_reviews_page')
        );

        add_action("load-$hook_all_reviews", array($this, 'screen_option_all_quiz_reviews'));
        add_action("load-$hook_all_reviews", array( $this, 'add_tabs' ));

        add_filter('parent_file', array($this,'quiz_maker_select_submenu'));
    }

    public function add_plugin_dashboard_submenu(){
        $hook_quizes = add_submenu_page(
            $this->plugin_name,
            __('How to use', 'quiz-maker'),
            __('How to use', 'quiz-maker'),
            $this->capability,
            $this->plugin_name . '-dashboard',
            array($this, 'display_plugin_setup_page')
        );
        add_action("load-$hook_quizes", array( $this, 'add_tabs' ));
    }

    public function add_plugin_general_settings_submenu(){
        $hook_settings = add_submenu_page( $this->plugin_name,
            __('General Settings', 'quiz-maker'),
            __('General Settings', 'quiz-maker'),
            'manage_options',
            $this->plugin_name . '-settings',
            array($this, 'display_plugin_settings_page') 
        );
        add_action("load-$hook_settings", array($this, 'screen_option_settings'));
        add_action("load-$hook_settings", array( $this, 'add_tabs' ));
    }

    public function add_plugin_featured_plugins_submenu(){
        $hook_our_products = add_submenu_page( $this->plugin_name,
            __('Our products', 'quiz-maker'),
            __('Our products', 'quiz-maker'),
            'manage_options',
            $this->plugin_name . '-featured-plugins',
            array($this, 'display_plugin_featured_plugins_page') 
        );

        add_action("load-$hook_our_products", array( $this, 'add_tabs' ));
    }

    public function add_plugin_addons_submenu(){

        $menu_item =  __('Addons', 'quiz-maker') . '<span class="ays_menu_badge ays_results_bage">' .  __('New', 'quiz-maker') . '</span>';

        $hook_addons = add_submenu_page( 
            $this->plugin_name,
            __('Addons', 'quiz-maker'),
            $menu_item,
            'manage_options',
            $this->plugin_name . '-addons',
            array($this, 'display_plugin_addons_page')
        );
        add_action("load-$hook_addons", array( $this, 'add_tabs' ));
    }

    public function add_plugin_quiz_features_submenu(){
        $hook_pro_features = add_submenu_page(
            $this->plugin_name,
            __('PRO Features', 'quiz-maker'),
            __('PRO Features', 'quiz-maker'),
            'manage_options',
            $this->plugin_name . '-quiz-features',
            array($this, 'display_plugin_quiz_features_page')
        );

        add_action("load-$hook_pro_features", array( $this, 'add_tabs' ));
    }

    public function add_plugin_subscribe_email(){
        $hook_grab_your_gift = add_submenu_page(
            $this->plugin_name,
            __('Grab your GIFT', 'quiz-maker'),
            __('Grab your GIFT', 'quiz-maker'),
            'manage_options',
            $this->plugin_name . '-quiz-subscribe-email',
            array($this, 'display_plugin_subscribe_email')
        );

        add_action("load-$hook_grab_your_gift", array( $this, 'add_tabs' ));
    }

    public function add_plugin_integrations_submenu(){
        $hook_integrations = add_submenu_page( $this->plugin_name,
            __('Integrations', 'quiz-maker'),
            __('Integrations', 'quiz-maker'),
            'manage_options',
            $this->plugin_name . '-integrations',
            array($this, 'display_plugin_integrations_page') 
        );
        
        // add_action("load-$hook_integrations", array($this, 'screen_option_settings'));
        add_action("load-$hook_integrations", array( $this, 'add_tabs' ));
    }

    public function add_plugin_admin_dashboard_menu(){

        if (!doing_action('admin_menu')) {
            return;
        }

        $menuHook = add_submenu_page(
            $this->plugin_name,
            __('Dashboard', 'quiz-maker'),
            __('Dashboard', 'quiz-maker'),
            'manage_options',
            $this->plugin_name . '-admin-dashboard',
            array($this, 'display_plugin_admin_dashboard_page'),
            40
        );

        if (!$menuHook) {
            return;
        }

        add_action("load-$menuHook", array($this, 'add_tabs'));
    }

    public function quiz_maker_select_submenu($file) {
        global $plugin_page;
        if ( $this->plugin_name . "-all-reviews" == $plugin_page ) {
            $plugin_page = $this->plugin_name."-results";
        }else if($this->plugin_name . "-question-reports" == $plugin_page) {
            $plugin_page = $this->plugin_name."-questions";
        }

        return $file;
    }

    /**
     * Add settings action link to the plugins page.
     *
     * @since    1.0.0
     */
    public function add_action_links($links){
        /*
        *  Documentation : https://codex.wordpress.org/Plugin_API/Filter_Reference/plugin_action_links_(plugin_file_name)
        */

        $quiz_ajax_deactivate_plugin_nonce = wp_create_nonce( 'quiz-maker-ajax-deactivate-plugin-nonce' );

        $settings_link = array(
            '<a href="' . admin_url('admin.php?page=' . $this->plugin_name) . '">' . __('Settings', 'quiz-maker') . '</a>',
            '<a href="https://quiz-plugin.com/wordpress-quiz-plugin-free-demo/" target="_blank">' . __('Demo', 'quiz-maker') . '</a>',
            '<a href="https://ays-pro.com/wordpress/quiz-maker?utm_source=dashboard&utm_medium=quiz-free&utm_campaign=plugins-buy-now-button-' . esc_attr( AYS_QUIZ_UTM_VERSION ) . '" id="ays-quiz-plugins-buy-now-button" target="_blank">' . __('Upgrade', 'quiz-maker') . '</a>
            <input type="hidden" id="ays_quiz_ajax_deactivate_plugin_nonce" name="ays_quiz_ajax_deactivate_plugin_nonce" value="' . $quiz_ajax_deactivate_plugin_nonce .'">',
            );
        return array_merge($settings_link, $links);

    }

    public function add_quiz_row_meta( $links, $file ) {
        if ( AYS_QUIZ_BASENAME == $file ) {
            $row_meta = array(
                'ays-quiz-support'    => '<a href="' . esc_url( 'https://wordpress.org/support/plugin/quiz-maker/' ) . '" target="_blank">' . esc_html__( 'Free Support', 'quiz-maker' ) . '</a>'
                );

            return array_merge( $links, $row_meta );
        }
        return $links;
    }

    /**
     * Render the settings page for this plugin.
     *
     * @since    1.0.0
     */
    public function display_plugin_setup_page(){
        include_once('partials/quiz-maker-admin-display.php');
    }

    public function display_plugin_quiz_categories_page(){
        $action = (isset($_GET['action'])) ? sanitize_text_field($_GET['action']) : '';

        switch ($action) {
            case 'add':
                include_once('partials/quizes/actions/quiz-maker-quiz-categories-actions.php');
                break;
            case 'edit':
                include_once('partials/quizes/actions/quiz-maker-quiz-categories-actions.php');
                break;
            default:
                include_once('partials/quizes/quiz-maker-quiz-categories-display.php');
        }
    }
    
    public function display_plugin_quiz_attributes_page(){
        include_once('partials/attributes/quiz-maker-attributes-display.php');
    }
    
    public function display_plugin_quiz_page(){
        $action = (isset($_GET['action'])) ? sanitize_text_field($_GET['action']) : '';

        switch ($action) {
            case 'add':
                include_once('partials/quizes/actions/quiz-maker-quizes-actions.php');
                break;
            case 'edit':
                include_once('partials/quizes/actions/quiz-maker-quizes-actions.php');
                break;
            default:
                include_once('partials/quizes/quiz-maker-quizes-display.php');
        }
    }

    public function display_plugin_question_categories_page(){
        $action = (isset($_GET['action'])) ? sanitize_text_field($_GET['action']) : '';

        switch ($action) {
            case 'add':
                include_once('partials/questions/actions/quiz-maker-questions-categories-actions.php');
                break;
            case 'edit':
                include_once('partials/questions/actions/quiz-maker-questions-categories-actions.php');
                break;
            default:
                include_once('partials/questions/quiz-maker-question-categories-display.php');
        }
    }

    public function display_plugin_questions_page(){
        $action = (isset($_GET['action'])) ? sanitize_text_field($_GET['action']) : '';

        switch ($action) {
            case 'add':
                include_once('partials/questions/actions/quiz-maker-questions-actions.php');
                break;
            case 'edit':
                include_once('partials/questions/actions/quiz-maker-questions-actions.php');
                break;
            default:
                include_once('partials/questions/quiz-maker-questions-display.php');
        }
    }
    
    public function display_plugin_orders_page(){

        include_once('partials/orders/quiz-maker-orders-display.php');
    }
    
    public function display_plugin_settings_page(){        
        include_once('partials/settings/quiz-maker-settings.php');
    }

    public function display_plugin_quiz_features_page(){
        include_once('partials/features/quiz-maker-features-display.php');
    }

    public function display_plugin_subscribe_email(){
        include_once('partials/subscribe/quiz-maker-subscribe-email-display.php');
    }

    public function display_plugin_featured_plugins_page(){
        include_once('partials/features/quiz-maker-plugin-featured-display.php');
    }

    public function display_plugin_addons_page(){
        include_once('partials/features/quiz-maker-addons-display.php');
    }

    public function display_plugin_results_page(){
        include_once('partials/results/quiz-maker-results-display.php');
    }

    public function display_plugin_all_reviews_page(){
        include_once('partials/results/quiz-maker-all-reviews-display.php');
    }

    public function display_plugin_integrations_page(){        
        include_once('partials/integrations/quiz-maker-integrations.php');
    }

    public function display_plugin_admin_dashboard_page(){
        include_once('partials/dashboard/quiz-maker-dashboard-display.php');
    }

    public function display_plugin_question_reports_page(){
        include_once('partials/questions/quiz-maker-question-reports-display.php');
    }

    public static function set_screen($status, $option, $value){
        return $value;
    }

    public function screen_option_quizes(){
        $option = 'per_page';
        $args = array(
            'label' => __('Quizzes', 'quiz-maker'),
            'default' => 20,
            'option' => 'quizes_per_page'
        );

        if( ! ( isset( $_GET['action'] ) && ( $_GET['action'] == 'add' || $_GET['action'] == 'edit' ) ) ){
            add_screen_option($option, $args);
        }

        $this->quizes_obj = new Quizes_List_Table($this->plugin_name);
        $this->settings_obj = new Quiz_Maker_Settings_Actions($this->plugin_name);
    }

    public function screen_option_quiz_categories(){
        $option = 'per_page';
        $args = array(
            'label' => __('Quiz Categories', 'quiz-maker'),
            'default' => 20,
            'option' => 'quiz_categories_per_page'
        );

        if( ! ( isset( $_GET['action'] ) && ( $_GET['action'] == 'add' || $_GET['action'] == 'edit' ) ) ){
            add_screen_option($option, $args);
        }

        $this->quiz_categories_obj = new Quiz_Categories_List_Table($this->plugin_name);
        $this->settings_obj = new Quiz_Maker_Settings_Actions($this->plugin_name);
    }

    public function screen_option_questions(){
        $option = 'per_page';
        $args = array(
            'label' => __('Questions', 'quiz-maker'),
            'default' => 20,
            'option' => 'questions_per_page'
        );

        if( ! ( isset( $_GET['action'] ) && ( $_GET['action'] == 'add' || $_GET['action'] == 'edit' ) ) ){
            add_screen_option($option, $args);
        }

        $this->questions_obj = new Questions_List_Table($this->plugin_name);
        $this->settings_obj = new Quiz_Maker_Settings_Actions($this->plugin_name);
    }

    public function screen_option_questions_categories(){
        $option = 'per_page';
        $args = array(
            'label' => __('Question Categories', 'quiz-maker'),
            'default' => 20,
            'option' => 'question_categories_per_page'
        );

        if( ! ( isset( $_GET['action'] ) && ( $_GET['action'] == 'add' || $_GET['action'] == 'edit' ) ) ){
            add_screen_option($option, $args);
        }
        
        $this->question_categories_obj = new Question_Categories_List_Table($this->plugin_name);
        $this->settings_obj = new Quiz_Maker_Settings_Actions($this->plugin_name);
    }

    public function screen_option_results(){
        $option = 'per_page';
        $args = array(
            'label' => __('Results', 'quiz-maker'),
            'default' => 50,
            'option' => 'quiz_results_per_page'
        );

        add_screen_option($option, $args);
        $this->results_obj = new Results_List_Table($this->plugin_name);
    }

     public function screen_option_all_quiz_reviews(){
        $option = 'per_page';
        $args = array(
            'label' => __('All Reviews', 'quiz-maker'),
            'default' => 50,
            'option' => 'quiz_all_reviews_per_page'
        );

        add_screen_option($option, $args);
        $this->all_reviews_obj = new All_Reviews_List_Table($this->plugin_name);
    }

    public function screen_option_settings(){
        $this->settings_obj = new Quiz_Maker_Settings_Actions($this->plugin_name);
    }

    public function screen_option_question_reports(){
        $option = 'per_page';

        $args = array(
            'label' => __('Question Reports', 'quiz-maker'),
            'default' => 20,
            'option' => 'quiz_question_reports_per_page'
        );

        add_screen_option($option, $args);

        $this->question_reports_obj = new Question_Reports_List_Table($this->plugin_name);
        $this->settings_obj = new Quiz_Maker_Settings_Actions($this->plugin_name);
    }

    /**
     * Adding questions from modal to table
     */
    public function add_question_rows(){

        // Run a security check.
        check_ajax_referer( 'quiz-maker-ajax-add-question-nonce', sanitize_key( $_REQUEST['_ajax_nonce'] ) );

        $user_can_capability = self::quiz_maker_capabilities();

        // Check for permissions.
        if ( ! current_user_can( $user_can_capability ) ) {
            ob_end_clean();
            $ob_get_clean = ob_get_clean();
            echo json_encode(array(
                'status' => false,
                'rows' => '',
                'ids' => array()
            ));
            wp_die();
        }

        if( !is_user_logged_in() ) {
            ob_end_clean();
            $ob_get_clean = ob_get_clean();
            echo json_encode(array(
                'status' => false,
                'rows' => '',
                'ids' => array()
            ));
            wp_die();
        }

        if ( (isset($_REQUEST["action"]) && $_REQUEST["action"] == "add_question_rows") || (isset($_REQUEST["action"]) && $_REQUEST["action"] == "add_question_rows_top") ) {

            $question_ids = (isset($_REQUEST["ays_questions_ids"]) && !empty($_REQUEST["ays_questions_ids"])) ? array_map( 'sanitize_text_field', $_REQUEST['ays_questions_ids'] ) : array();

            // Question title view
            $question_title_view = (isset($_REQUEST['question_title_view']) && sanitize_text_field( $_REQUEST['question_title_view'] ) != "") ? sanitize_text_field( $_REQUEST['question_title_view'] ) : 'question_title';

            $rows = array();
            $ids = array();
            if (!empty($question_ids)) {
                $question_categories = $this->get_questions_categories();
                $question_categories_array = array();
                foreach($question_categories as $cat){
                    $question_categories_array[$cat['id']] = $cat['title'];
                }
                foreach ($question_ids as $question_id) {
                    $data = $this->get_published_questions_by('id', absint(intval($question_id)));
                    $table_question = (strip_tags(stripslashes($data['question'])));
                    if(isset($data['question_title']) && $data['question_title'] != '' && $question_title_view == 'question_title'){
                        $table_question = htmlspecialchars_decode( $data['question_title'], ENT_COMPAT);
                        $table_question = stripslashes( $table_question );
                    }elseif(isset($data['question']) && strlen($data['question']) != 0){

                        $is_exists_ruby = Quiz_Maker_Admin::ays_quiz_is_exists_needle_tag( $data['question'] , '<ruby>' );

                        if ( $is_exists_ruby ) {
                            $table_question = strip_tags( stripslashes($data['question']), '<ruby><rbc><rtc><rb><rt>' );
                        } else {
                            $table_question = strip_tags(stripslashes($data['question']));
                        }

                    }elseif ((isset($data['question_image']) && $data['question_image'] !='')){
                        $table_question = 'Image question';
                    }

                    switch ( $data['type'] ) {
                        case 'short_text':
                            $ays_question_type = 'short text';
                            break;
                        case 'true_or_false':
                            $ays_question_type = 'true/false';
                            break;
                        default:
                            $ays_question_type = $data['type'];
                            break;
                    }

                    $table_question = $this->ays_restriction_string("word", $table_question, 8);
                    $edit_question_url = "?page=".$this->plugin_name."-questions&action=edit&question=".$data['id'];
                    $rows[] = '<tr class="ays-question-row ui-state-default" data-id="' . $data['id'] . '">
                        <td class="ays-sort"><i class="ays_fa ays_fa_arrows" aria-hidden="true"></i></td>
                        <td>                        
                            <a href="'. $edit_question_url .'" target="_blank" class="ays-edit-question" title="'. __('Edit question', 'quiz-maker') .'">
                                ' . esc_html($table_question) . '
                            </a> 
                        </td>
                        <td>' . $ays_question_type . '</td>
                        <td>' . $question_categories_array[$data['category_id']] . '</td>
                        <td>' . stripslashes($data['id']) . '</td>
                        <td>
                            <input type="checkbox" class="ays_del_tr">
                            <a href="'. esc_url($edit_question_url) .'" target="_blank" class="ays-edit-question" title="'. __('Edit question', 'quiz-maker') .'">
                                <i class="ays_fa ays_fa_pencil_square" aria-hidden="true"></i>
                            </a>
                            <a href="javascript:void(0)" class="ays-delete-question" data-id="' . esc_attr($data['id']) . '">
                                <i class="ays_fa ays_fa_minus_square" aria-hidden="true"></i>
                            </a>
                        </td>
                   </tr>';
                    $ids[] = $data['id'];
                }
                ob_end_clean();
                $ob_get_clean = ob_get_clean();
                echo json_encode(array(
                    'status' => true,
                    'rows' => $rows,
                    'ids' => $ids
                ));
                wp_die();
            } else {
                ob_end_clean();
                $ob_get_clean = ob_get_clean();
                echo json_encode(array(
                    'status' => true,
                    'rows' => '',
                    'ids' => array()
                ));
                wp_die();
            }
        } else {
            ob_end_clean();
            $ob_get_clean = ob_get_clean();
            echo json_encode(array(
                'status' => true,
                'rows' => '',
                'ids' => array()
            ));
            wp_die();
        }
    }

    /**
     * @return string
     */
    public function ays_quick_start(){
        global $wpdb;

        // Run a security check.
        check_ajax_referer( 'quiz-maker-ajax-quick-quiz-nonce', sanitize_key( $_REQUEST['_ajax_nonce'] ) );

        $user_can_capability = self::quiz_maker_capabilities();

        // Check for permissions.
        if ( ! current_user_can( $user_can_capability ) ) {
            ob_end_clean();
            $ob_get_clean = ob_get_clean();
            echo json_encode(array(
                'status' => false,
                'quiz_id' => 0
            ));
            wp_die();
        }

        if( !is_user_logged_in() ) {
            ob_end_clean();
            $ob_get_clean = ob_get_clean();
            echo json_encode(array(
                'status' => false,
                'quiz_id' => 0
            ));
            wp_die();
        }

        $quiz_title = stripslashes( sanitize_text_field( $_REQUEST['ays_quiz_title'] ) );
        $quiz_description = (isset( $_REQUEST['ays_quick_quiz_description'] ) && $_REQUEST['ays_quick_quiz_description'] != "") ? stripslashes( wp_kses_post( $_REQUEST['ays_quick_quiz_description'] ) ) : "";
        $quiz_cat_id = sanitize_text_field( $_REQUEST['ays_quiz_category'] );
        $questions = array_map( 'sanitize_text_field', $_REQUEST['ays_quick_question'] );
        $questions_type = array_map( 'sanitize_text_field', $_REQUEST['ays_quick_question_type'] );
        $questions_cat = array_map( 'sanitize_text_field', $_REQUEST['ays_quick_question_cat'] );
        $answers_correct = self::recursive_sanitize_text_field( $_REQUEST['ays_quick_answer_correct'] );
        $answers = self::recursive_sanitize_text_field( $_REQUEST['ays_quick_answer'] );
        $quick_quiz_publish = isset($_REQUEST['ays_quick_quiz_publish']) && sanitize_text_field( $_REQUEST['ays_quick_quiz_publish'] ) != "" ? sanitize_text_field( $_REQUEST['ays_quick_quiz_publish'] ) : 1;

        $answers_table = esc_sql( $wpdb->prefix . 'aysquiz_answers' );
        $questions_table = esc_sql( $wpdb->prefix . 'aysquiz_questions' );
        $quizes_table = esc_sql( $wpdb->prefix . 'aysquiz_quizes' );

        $max_id = $this->get_max_id('quizes');
        $ordering = ( $max_id != NULL ) ? ( $max_id + 1 ) : 1;
        
        $questions_ids = '';
        $create_date = current_time( 'mysql' );
        $user_id = get_current_user_id();
        $user = get_userdata($user_id);
        $author = array(
            'id' => $user->ID,
            'name' => $user->data->display_name
        );
        $options = array(
            'author' => $author,
        );

        $setting_actions = new Quiz_Maker_Settings_Actions(AYS_QUIZ_NAME);

        // Buttons Text
        $buttons_texts_res          = ($setting_actions->ays_get_setting('buttons_texts') === false) ? json_encode(array()) : $setting_actions->ays_get_setting('buttons_texts');
        $buttons_texts              = json_decode( stripcslashes( $buttons_texts_res ) , true);
        

        $gen_start_button           = (isset($buttons_texts['start_button']) && $buttons_texts['start_button'] != '') ? stripslashes( esc_attr( $buttons_texts['start_button'] ) ) : 'Start';
        $gen_next_button            = (isset($buttons_texts['next_button']) && $buttons_texts['next_button'] != '') ? stripslashes( esc_attr( $buttons_texts['next_button'] ) ) : 'Next';
        $gen_previous_button        = (isset($buttons_texts['previous_button']) && $buttons_texts['previous_button'] != '') ? stripslashes( esc_attr( $buttons_texts['previous_button'] ) ) : 'Prev';
        $gen_clear_button           = (isset($buttons_texts['clear_button']) && $buttons_texts['clear_button'] != '') ? stripslashes( esc_attr( $buttons_texts['clear_button'] ) ) : 'Clear';
        $gen_finish_button          = (isset($buttons_texts['finish_button']) && $buttons_texts['finish_button'] != '') ? stripslashes( esc_attr( $buttons_texts['finish_button'] ) ) : 'Finish';
        $gen_see_result_button      = (isset($buttons_texts['see_result_button']) && $buttons_texts['see_result_button'] != '') ? stripslashes( esc_attr( $buttons_texts['see_result_button'] ) ) : 'See Result';
        $gen_restart_quiz_button    = (isset($buttons_texts['restart_quiz_button']) && $buttons_texts['restart_quiz_button'] != '') ? stripslashes( esc_attr( $buttons_texts['restart_quiz_button'] ) ) : 'Restart quiz';
        $gen_send_feedback_button   = (isset($buttons_texts['send_feedback_button']) && $buttons_texts['send_feedback_button'] != '') ? stripslashes(esc_attr($buttons_texts['send_feedback_button'])) : 'Send feedback';
        $gen_load_more_button       = (isset($buttons_texts['load_more_button']) && $buttons_texts['load_more_button'] != '') ? esc_attr(stripslashes($buttons_texts['load_more_button'])) : 'Load more';
        $gen_exit_button            = (isset($buttons_texts['exit_button']) && $buttons_texts['exit_button'] != '') ? esc_attr(stripslashes($buttons_texts['exit_button'])) : 'Exit';
        $gen_check_button           = (isset($buttons_texts['check_button']) && $buttons_texts['check_button'] != '') ? esc_attr(stripslashes($buttons_texts['check_button'])) : 'Check';
        $gen_login_button           = (isset($buttons_texts['login_button']) && $buttons_texts['login_button'] != '') ? esc_attr(stripslashes($buttons_texts['login_button'])) : 'Log In' ;

        // Enable Quiz Options
        $quiz_enable_options = (isset( $_REQUEST['ays_quick_quiz_enable_options'] ) && $_REQUEST['ays_quick_quiz_enable_options'] == "on") ? stripslashes( sanitize_text_field( $_REQUEST['ays_quick_quiz_enable_options'] ) ) : "off";
        
        // Settings
        $quick_quiz_enable_randomize_questions                  = 'off';
        $quick_quiz_enable_randomize_answers                    = 'off';
        $quick_quiz_display_all_questions                       = 'off';
        $quick_quiz_enable_correction                           = 'on';
        $quick_quiz_show_question_category                      = 'off';
        $quick_quiz_enable_pass_count                           = 'off';
        $quick_quiz_show_category                               = 'off';
        $quick_quiz_enable_rate_avg                             = 'off';
        $quick_quiz_show_author                                 = 'off';
        $quick_quiz_show_create_date                            = 'off';
        $quick_quiz_enable_next_button                          = 'on';
        $quick_quiz_enable_previous_button                      = 'on';
        $quick_quiz_enable_early_finish                         = 'off';
        $quick_quiz_enable_clear_answer                         = 'off';
        $quick_quiz_enable_enter_key                            = 'on';
        $quick_quiz_display_messages_before_buttons             = 'off';
        $quick_quiz_enable_audio_autoplay                       = 'off';
        $quick_quiz_enable_rtl_direction                        = 'off';
        $quick_quiz_enable_questions_counter                    = 'on';
        $quick_quiz_enable_question_image_zoom                  = 'off';
        $quick_quiz_enable_leave_page                           = 'on';
        $quick_quiz_enable_see_result_confirm_box               = 'off';
        $quick_quiz_enable_rw_asnwers_sounds                    = 'off';
        $quick_quiz_enable_custom_texts_for_buttons             = 'off';
        $quick_quiz_show_quiz_title                             = 'on';
        $quick_quiz_show_quiz_desc                              = 'on';
        $quick_quiz_show_questions_explanation                  = 'on_results_page';
        $quick_quiz_show_questions_numbering                    = 'none';
        $quick_quiz_answers_view                                = 'list';
        $quick_quiz_answers_rw_texts                            = 'on_passing';
        $quick_quiz_enable_questions_ordering_by_cat            = 'off';
        $quick_quiz_questions_numbering_by_category             = 'off';
        $quick_quiz_enable_question_category_description        = 'off';
        $quick_quiz_enable_quiz_category_description            = 'off';
        $quick_quiz_enable_live_progress_bar                    = 'off';
        $quick_quiz_enable_percent_view_option                  = 'off';

        $quick_quiz_custom_texts_start_button                   = $gen_start_button;
        $quick_quiz_custom_texts_next_button                    = $gen_next_button;
        $quick_quiz_custom_texts_prev_button                    = $gen_previous_button;
        $quick_quiz_custom_texts_clear_button                   = $gen_clear_button;
        $quick_quiz_custom_texts_finish_button                  = $gen_finish_button;
        $quick_quiz_custom_texts_see_results_button             = $gen_see_result_button;
        $quick_quiz_custom_texts_restart_button                 = $gen_restart_quiz_button;
        $quick_quiz_custom_texts_send_feedback_button           = $gen_send_feedback_button;
        $quick_quiz_custom_texts_load_more_button               = $gen_load_more_button;
        $quick_quiz_custom_texts_exit_button                    = $gen_exit_button;
        $quick_quiz_custom_texts_check_button                   = $gen_check_button;
        $quick_quiz_custom_texts_login_button                   = $gen_login_button;

        // Results Settings
        $quick_quiz_hide_score                                  = 'off';
        $quick_quiz_enable_restart_button                       = 'on';
        $quick_quiz_enable_progress_bar                         = 'on';
        $quick_quiz_enable_average_statistical                  = 'on';
        $quick_quiz_disable_store_data                          = 'off';
        $quick_quiz_display_score                               = 'by_percantage';
        $quick_quiz_enable_questions_result                     = 'on';
        $quick_quiz_hide_correct_answers                        = 'off';
        $quick_quiz_show_wrong_answers_first                    = 'off';
        $quick_quiz_show_only_wrong_answers                     = 'off';
        $quick_quiz_enable_results_toggle                       = 'off';
        $quick_quiz_enable_default_hide_results_toggle          = 'off';
        $quick_quiz_enable_early_finsh_comfirm_box              = 'on';
        $quick_quiz_show_restart_button_on_quiz_fail            = 'off';
        $quick_quiz_enable_quiz_rate                            = 'off';
        $quick_quiz_enable_rate_comments                        = 'off';
        $quick_quiz_make_responses_anonymous                    = 'off';
        $quick_quiz_enable_user_cհoosing_anonymous_assessment   = 'off';
        $quick_quiz_make_all_review_link                        = 'off';
        $quick_quiz_review_enable_comment_field                 = 'on';
        $quick_quiz_make_review_required                        = 'off';
        $quick_quiz_review_placeholder_text                     = '';

        // User Data
        $quick_quiz_show_information_form                   = 'on';
        $quick_quiz_autofill_user_data                      = 'off';
        $quick_quiz_display_fields_labels                   = 'off';

        // Styles Settings
        $quick_quiz_width                                   = 800;
        $quick_quiz_height                                  = 450;
        $quick_quiz_border_radius                           = 8;
        $quick_quiz_image_height                            = "";
        $quick_quiz_progress_bar_style                      = "third";
        $quick_quiz_progress_live_bar_style                 = "default";
        $quick_quiz_buttons_position                        = "center";
        $quick_quiz_title_transformation                    = "uppercase";
        $quick_quiz_title_font_size                         = 28;
        $quick_quiz_title_mobile_font_size                  = 20;
        $quick_quiz_custom_class                            = "";

        $quick_quiz_quest_animation                         = "none";
        $quick_quiz_question_font_size                      = 16;
        $quick_quiz_question_mobile_font_size               = 16;
        $quick_quiz_question_text_alignment                 = "center";
        $quick_quiz_image_width                             = "";
        $quick_quiz_image_width_by_percentage_px            = "pixels";
        $quick_quiz_image_height                            = "";
        $quick_quiz_image_sizing                            = "cover";
        $quick_quiz_answers_font_size                       = 15;
        $quick_quiz_answers_mobile_font_size                = 15;
        $quick_quiz_answers_margin                          = 12;
        $quick_quiz_disable_hover_effect                    = "off";
        $quick_quiz_buttons_size                            = "large";
        $quick_quiz_buttons_font_size                       = 18;
        $quick_quiz_buttons_mobile_font_size                = 18;
        $quick_quiz_buttons_width                           = "";
        $quick_quiz_buttons_left_right_padding              = 36;
        $quick_quiz_buttons_top_bottom_padding              = 14;
        $quick_quiz_buttons_border_radius                   = 8;
        $quick_quiz_note_text_font_size                     = 14;
        $quick_quiz_note_text_mobile_font_size              = 14;
        $quick_quiz_admin_note_text_transform               = "none";
        $quick_quiz_admin_note_text_decoration              = "none";
        $quick_quiz_admin_note_letter_spacing               = 0;
        $quick_quiz_admin_note_font_weight                  = "normal";
        $quick_quiz_quest_explanation_font_size             = 16;
        $quick_quiz_quest_explanation_mobile_font_size      = 16;
        $quick_quiz_quest_explanation_text_transform        = "none";
        $quick_quiz_quest_explanation_text_decoration       = "none";
        $quick_quiz_quest_explanation_letter_spacing        = 0;
        $quick_quiz_quest_explanation_font_weight           = "normal";
        $quick_quiz_right_answers_font_size                 = 16;
        $quick_quiz_right_answers_mobile_font_size          = 16;
        $quick_quiz_right_answer_text_transform             = "none";
        $quick_quiz_right_answers_text_decoration           = "none";
        $quick_quiz_right_answers_letter_spacing            = 0;
        $quick_quiz_right_answers_font_weight               = "normal";
        $quick_quiz_wrong_answers_font_size                 = 16;
        $quick_quiz_wrong_answers_mobile_font_size          = 16;
        $quick_quiz_wrong_answer_text_transform             = "none";
        $quick_quiz_wrong_answers_text_decoration           = "none";
        $quick_quiz_wrong_answers_letter_spacing            = 0;
        $quick_quiz_wrong_answers_font_weight               = "normal";
        $quick_quiz_admin_note_mobile_text_transform        = "none";

        if($quiz_enable_options == 'on'){
            $quick_quiz_enable_randomize_questions = (isset( $_REQUEST['ays_quick_quiz_enable_randomize_questions'] ) && $_REQUEST['ays_quick_quiz_enable_randomize_questions'] == "on") ? stripslashes( sanitize_text_field( $_REQUEST['ays_quick_quiz_enable_randomize_questions'] ) ) : "off";
            $quick_quiz_enable_randomize_answers = (isset( $_REQUEST['ays_quick_quiz_enable_randomize_answers'] ) && $_REQUEST['ays_quick_quiz_enable_randomize_answers'] == "on") ? stripslashes( sanitize_text_field( $_REQUEST['ays_quick_quiz_enable_randomize_answers'] ) ) : "off";
            $quick_quiz_display_all_questions = (isset( $_REQUEST['ays_quick_quiz_display_all_questions'] ) && $_REQUEST['ays_quick_quiz_display_all_questions'] == "on") ? stripslashes( sanitize_text_field( $_REQUEST['ays_quick_quiz_display_all_questions'] ) ) : "off";
            $quick_quiz_enable_correction = (isset( $_REQUEST['ays_quick_quiz_enable_correction'] ) && $_REQUEST['ays_quick_quiz_enable_correction'] == "on") ? stripslashes( sanitize_text_field( $_REQUEST['ays_quick_quiz_enable_correction'] ) ) : "off";
            $quick_quiz_show_question_category = (isset( $_REQUEST['ays_quick_quiz_show_question_category'] ) && $_REQUEST['ays_quick_quiz_show_question_category'] == "on") ? stripslashes( sanitize_text_field( $_REQUEST['ays_quick_quiz_show_question_category'] ) ) : "off";
            $quick_quiz_enable_pass_count = (isset( $_REQUEST['ays_quick_quiz_enable_pass_count'] ) && $_REQUEST['ays_quick_quiz_enable_pass_count'] == "on") ? stripslashes( sanitize_text_field( $_REQUEST['ays_quick_quiz_enable_pass_count'] ) ) : "off";
            $quick_quiz_show_category = (isset( $_REQUEST['ays_quick_quiz_show_category'] ) && $_REQUEST['ays_quick_quiz_show_category'] == "on") ? stripslashes( sanitize_text_field( $_REQUEST['ays_quick_quiz_show_category'] ) ) : "off";
            $quick_quiz_enable_rate_avg = (isset( $_REQUEST['ays_quick_quiz_enable_rate_avg'] ) && $_REQUEST['ays_quick_quiz_enable_rate_avg'] == "on") ? stripslashes( sanitize_text_field( $_REQUEST['ays_quick_quiz_enable_rate_avg'] ) ) : "off";
            $quick_quiz_show_author = (isset( $_REQUEST['ays_quick_quiz_show_author'] ) && $_REQUEST['ays_quick_quiz_show_author'] == "on") ? stripslashes( sanitize_text_field( $_REQUEST['ays_quick_quiz_show_author'] ) ) : "off";
            $quick_quiz_show_create_date = (isset( $_REQUEST['ays_quick_quiz_show_create_date'] ) && $_REQUEST['ays_quick_quiz_show_create_date'] == "on") ? stripslashes( sanitize_text_field( $_REQUEST['ays_quick_quiz_show_create_date'] ) ) : "off";
            $quick_quiz_enable_next_button = (isset( $_REQUEST['ays_quick_quiz_enable_next_button'] ) && $_REQUEST['ays_quick_quiz_enable_next_button'] == "on") ? stripslashes( sanitize_text_field( $_REQUEST['ays_quick_quiz_enable_next_button'] ) ) : "off";
            $quick_quiz_enable_previous_button = (isset( $_REQUEST['ays_quick_quiz_enable_previous_button'] ) && $_REQUEST['ays_quick_quiz_enable_previous_button'] == "on") ? stripslashes( sanitize_text_field( $_REQUEST['ays_quick_quiz_enable_previous_button'] ) ) : "off";
            $quick_quiz_enable_early_finish = (isset( $_REQUEST['ays_quick_quiz_enable_early_finish'] ) && $_REQUEST['ays_quick_quiz_enable_early_finish'] == "on") ? stripslashes( sanitize_text_field( $_REQUEST['ays_quick_quiz_enable_early_finish'] ) ) : "off";
            $quick_quiz_enable_clear_answer = (isset( $_REQUEST['ays_quick_quiz_enable_clear_answer'] ) && $_REQUEST['ays_quick_quiz_enable_clear_answer'] == "on") ? stripslashes( sanitize_text_field( $_REQUEST['ays_quick_quiz_enable_clear_answer'] ) ) : "off";
            $quick_quiz_enable_enter_key = (isset( $_REQUEST['ays_quick_quiz_enable_enter_key'] ) && $_REQUEST['ays_quick_quiz_enable_enter_key'] == "on") ? stripslashes( sanitize_text_field( $_REQUEST['ays_quick_quiz_enable_enter_key'] ) ) : "off";
            $quick_quiz_display_messages_before_buttons = (isset( $_REQUEST['ays_quick_quiz_display_messages_before_buttons'] ) && $_REQUEST['ays_quick_quiz_display_messages_before_buttons'] == "on") ? stripslashes( sanitize_text_field( $_REQUEST['ays_quick_quiz_display_messages_before_buttons'] ) ) : "off";
            $quick_quiz_enable_audio_autoplay = (isset( $_REQUEST['ays_quick_quiz_enable_audio_autoplay'] ) && $_REQUEST['ays_quick_quiz_enable_audio_autoplay'] == "on") ? stripslashes( sanitize_text_field( $_REQUEST['ays_quick_quiz_enable_audio_autoplay'] ) ) : "off";
            $quick_quiz_enable_rtl_direction = (isset( $_REQUEST['ays_quick_quiz_enable_rtl_direction'] ) && $_REQUEST['ays_quick_quiz_enable_rtl_direction'] == "on") ? stripslashes( sanitize_text_field( $_REQUEST['ays_quick_quiz_enable_rtl_direction'] ) ) : "off";
            $quick_quiz_enable_questions_counter = (isset( $_REQUEST['ays_quick_quiz_enable_questions_counter'] ) && $_REQUEST['ays_quick_quiz_enable_questions_counter'] == "on") ? stripslashes( sanitize_text_field( $_REQUEST['ays_quick_quiz_enable_questions_counter'] ) ) : "off";
            $quick_quiz_enable_question_image_zoom = (isset( $_REQUEST['ays_quick_quiz_enable_question_image_zoom'] ) && $_REQUEST['ays_quick_quiz_enable_question_image_zoom'] == "on") ? stripslashes( sanitize_text_field( $_REQUEST['ays_quick_quiz_enable_question_image_zoom'] ) ) : "off";
            $quick_quiz_enable_leave_page = (isset( $_REQUEST['ays_quick_quiz_enable_leave_page'] ) && $_REQUEST['ays_quick_quiz_enable_leave_page'] == "on") ? stripslashes( sanitize_text_field( $_REQUEST['ays_quick_quiz_enable_leave_page'] ) ) : "off";
            $quick_quiz_enable_see_result_confirm_box = (isset( $_REQUEST['ays_quick_quiz_enable_see_result_confirm_box'] ) && $_REQUEST['ays_quick_quiz_enable_see_result_confirm_box'] == "on") ? stripslashes( sanitize_text_field( $_REQUEST['ays_quick_quiz_enable_see_result_confirm_box'] ) ) : "off";
            $quick_quiz_enable_rw_asnwers_sounds = (isset( $_REQUEST['ays_quick_quiz_enable_rw_asnwers_sounds'] ) && $_REQUEST['ays_quick_quiz_enable_rw_asnwers_sounds'] == "on") ? stripslashes( sanitize_text_field( $_REQUEST['ays_quick_quiz_enable_rw_asnwers_sounds'] ) ) : "off";
            $quick_quiz_hide_score = (isset( $_REQUEST['ays_quick_quiz_hide_score'] ) && $_REQUEST['ays_quick_quiz_hide_score'] == "on") ? stripslashes( sanitize_text_field( $_REQUEST['ays_quick_quiz_hide_score'] ) ) : "off";
            $quick_quiz_enable_restart_button = (isset( $_REQUEST['ays_quick_quiz_enable_restart_button'] ) && $_REQUEST['ays_quick_quiz_enable_restart_button'] == "on") ? stripslashes( sanitize_text_field( $_REQUEST['ays_quick_quiz_enable_restart_button'] ) ) : "off";
            $quick_quiz_enable_progress_bar = (isset( $_REQUEST['ays_quick_quiz_enable_progress_bar'] ) && $_REQUEST['ays_quick_quiz_enable_progress_bar'] == "on") ? stripslashes( sanitize_text_field( $_REQUEST['ays_quick_quiz_enable_progress_bar'] ) ) : "off";
            $quick_quiz_enable_average_statistical = (isset( $_REQUEST['ays_quick_quiz_enable_average_statistical'] ) && $_REQUEST['ays_quick_quiz_enable_average_statistical'] == "on") ? stripslashes( sanitize_text_field( $_REQUEST['ays_quick_quiz_enable_average_statistical'] ) ) : "off";
            $quick_quiz_disable_store_data = (isset( $_REQUEST['ays_quick_quiz_disable_store_data'] ) && $_REQUEST['ays_quick_quiz_disable_store_data'] == "on") ? stripslashes( sanitize_text_field( $_REQUEST['ays_quick_quiz_disable_store_data'] ) ) : "off";

            $quick_quiz_show_information_form = (isset( $_REQUEST['ays_quick_quiz_show_information_form'] ) && $_REQUEST['ays_quick_quiz_show_information_form'] == "on") ? stripslashes( sanitize_text_field( $_REQUEST['ays_quick_quiz_show_information_form'] ) ) : "off";
            $quick_quiz_autofill_user_data    = (isset( $_REQUEST['ays_quick_quiz_autofill_user_data'] ) && $_REQUEST['ays_quick_quiz_autofill_user_data'] == "on") ? stripslashes( sanitize_text_field( $_REQUEST['ays_quick_quiz_autofill_user_data'] ) ) : "off";
            $quick_quiz_display_fields_labels = (isset( $_REQUEST['ays_quick_quiz_display_fields_labels'] ) && $_REQUEST['ays_quick_quiz_display_fields_labels'] == "on") ? stripslashes( sanitize_text_field( $_REQUEST['ays_quick_quiz_display_fields_labels'] ) ) : "off";
            
            $quick_quiz_enable_custom_texts_for_buttons = (isset( $_REQUEST['ays_quick_quiz_enable_custom_texts_for_buttons'] ) && $_REQUEST['ays_quick_quiz_enable_custom_texts_for_buttons'] == "on") ? stripslashes( sanitize_text_field( $_REQUEST['ays_quick_quiz_enable_custom_texts_for_buttons'] ) ) : "off";

            // Start button
            $quick_quiz_custom_texts_start_button  = (isset($_REQUEST['ays_quick_quiz_custom_texts_start_button']) && $_REQUEST['ays_quick_quiz_custom_texts_start_button'] != '') ? stripslashes( sanitize_text_field( $_REQUEST['ays_quick_quiz_custom_texts_start_button'] ) ) : $gen_start_button;

            // Next button
            $quick_quiz_custom_texts_next_button  = (isset($_REQUEST['ays_quick_quiz_custom_texts_next_button']) && $_REQUEST['ays_quick_quiz_custom_texts_next_button'] != '') ? stripslashes( sanitize_text_field( $_REQUEST['ays_quick_quiz_custom_texts_next_button'] ) ) : $gen_next_button;

            // Prev button
            $quick_quiz_custom_texts_prev_button  = (isset($_REQUEST['ays_quick_quiz_custom_texts_prev_button']) && $_REQUEST['ays_quick_quiz_custom_texts_prev_button'] != '') ? stripslashes( sanitize_text_field( $_REQUEST['ays_quick_quiz_custom_texts_prev_button'] ) ) : $gen_previous_button;

            // Clear button
            $quick_quiz_custom_texts_clear_button  = (isset($_REQUEST['ays_quick_quiz_custom_texts_clear_button']) && $_REQUEST['ays_quick_quiz_custom_texts_clear_button'] != '') ? stripslashes( sanitize_text_field( $_REQUEST['ays_quick_quiz_custom_texts_clear_button'] ) ) : $gen_clear_button;

            // Finish button
            $quick_quiz_custom_texts_finish_button  = (isset($_REQUEST['ays_quick_quiz_custom_texts_finish_button']) && $_REQUEST['ays_quick_quiz_custom_texts_finish_button'] != '') ? stripslashes( sanitize_text_field( $_REQUEST['ays_quick_quiz_custom_texts_finish_button'] ) ) : $gen_finish_button;

            // See results button
            $quick_quiz_custom_texts_see_results_button  = (isset($_REQUEST['ays_quick_quiz_custom_texts_see_results_button']) && $_REQUEST['ays_quick_quiz_custom_texts_see_results_button'] != '') ? stripslashes( sanitize_text_field( $_REQUEST['ays_quick_quiz_custom_texts_see_results_button'] ) ) : $gen_see_result_button;

            // Restart button
            $quick_quiz_custom_texts_restart_button  = (isset($_REQUEST['ays_quick_quiz_custom_texts_restart_quiz_button']) && $_REQUEST['ays_quick_quiz_custom_texts_restart_quiz_button'] != '') ? stripslashes( sanitize_text_field( $_REQUEST['ays_quick_quiz_custom_texts_restart_quiz_button'] ) ) : $gen_see_result_button;

            // Send feedback button
            $quick_quiz_custom_texts_send_feedback_button  = (isset($_REQUEST['ays_quick_quiz_custom_texts_send_feedback_button']) && $_REQUEST['ays_quick_quiz_custom_texts_send_feedback_button'] != '') ? stripslashes( sanitize_text_field( $_REQUEST['ays_quick_quiz_custom_texts_send_feedback_button'] ) ) : $gen_send_feedback_button;

            // Load more button
            $quick_quiz_custom_texts_load_more_button  = (isset($_REQUEST['ays_quick_quiz_custom_texts_load_more_button']) && $_REQUEST['ays_quick_quiz_custom_texts_load_more_button'] != '') ? stripslashes( sanitize_text_field( $_REQUEST['ays_quick_quiz_custom_texts_load_more_button'] ) ) : $gen_load_more_button;

            // Exit button
            $quick_quiz_custom_texts_exit_button = (isset($_REQUEST['ays_quick_quiz_custom_texts_exit_button']) && $_REQUEST['ays_quick_quiz_custom_texts_exit_button'] != '') ? stripslashes( esc_attr( $_REQUEST['ays_quick_quiz_custom_texts_exit_button'] ) ) : $gen_exit_button;

            // Check button
            $quick_quiz_custom_texts_check_button = (isset($_REQUEST['ays_quick_quiz_custom_texts_check_button']) && $_REQUEST['ays_quick_quiz_custom_texts_check_button'] != '') ? stripslashes( esc_attr( $_REQUEST['ays_quick_quiz_custom_texts_check_button'] ) ) : $gen_check_button;

            // Login button
            $quick_quiz_custom_texts_login_button = (isset($_REQUEST['ays_quick_quiz_custom_texts_login_button']) && $_REQUEST['ays_quick_quiz_custom_texts_login_button'] != '') ? stripslashes( esc_attr( $_REQUEST['ays_quick_quiz_custom_texts_login_button'] ) ) : $gen_login_button;

            /**
             * Style Settings
             */

            // Quiz Width
            $quick_quiz_width = (isset($_REQUEST['ays_quick_quiz_width']) && $_REQUEST['ays_quick_quiz_width'] != '') ? stripslashes( absint( $_REQUEST['ays_quick_quiz_width'] ) ) : "";

            // Quiz min-height
            $quick_quiz_height = (isset($_REQUEST['ays_quick_quiz_height']) && $_REQUEST['ays_quick_quiz_height'] != '') ? stripslashes( absint( $_REQUEST['ays_quick_quiz_height'] ) ) : 450;

            // Quiz min-height
            $quick_quiz_border_radius = (isset($_REQUEST['ays_quick_quiz_border_radius']) && $_REQUEST['ays_quick_quiz_border_radius'] != '') ? stripslashes( absint( $_REQUEST['ays_quick_quiz_border_radius'] ) ) : 8;

            // Quiz image height
            $quick_quiz_image_height = (isset($_REQUEST['ays_quick_quiz_image_height']) && $_REQUEST['ays_quick_quiz_image_height'] != '') ? stripslashes( absint( $_REQUEST['ays_quick_quiz_image_height'] ) ) : '';

            // Progress bar style
            $quick_quiz_progress_bar_style = (isset($_REQUEST['ays_quick_quiz_progress_bar_style']) && $_REQUEST['ays_quick_quiz_progress_bar_style'] != '') ? stripslashes( sanitize_text_field( $_REQUEST['ays_quick_quiz_progress_bar_style'] ) ) : 'third';

            // Progress bar style
            $quick_quiz_progress_live_bar_style = (isset($_REQUEST['ays_quick_quiz_progress_live_bar_style']) && $_REQUEST['ays_quick_quiz_progress_live_bar_style'] != '') ? stripslashes( sanitize_text_field( $_REQUEST['ays_quick_quiz_progress_live_bar_style'] ) ) : 'default';

            // Buttons position
            $quick_quiz_buttons_position = (isset($_REQUEST['ays_quick_quiz_buttons_position']) && $_REQUEST['ays_quick_quiz_buttons_position'] != '') ? stripslashes( sanitize_text_field( $_REQUEST['ays_quick_quiz_buttons_position'] ) ) : 'center';

            // Quiz title transformation
            $quick_quiz_title_transformation = (isset($_REQUEST['ays_quick_quiz_title_transformation']) && $_REQUEST['ays_quick_quiz_title_transformation'] != '') ? stripslashes( sanitize_text_field( $_REQUEST['ays_quick_quiz_title_transformation'] ) ) : 'uppercase';

            // Quiz title font size | desktop
            $quick_quiz_title_font_size = (isset($_REQUEST['ays_quick_quiz_title_font_size']) && $_REQUEST['ays_quick_quiz_title_font_size'] != '') ? stripslashes( absint( $_REQUEST['ays_quick_quiz_title_font_size'] ) ) : 28;

            // Quiz title font size | mobile
            $quick_quiz_title_mobile_font_size = (isset($_REQUEST['ays_quick_quiz_title_mobile_font_size']) && $_REQUEST['ays_quick_quiz_title_mobile_font_size'] != '') ? stripslashes( absint( $_REQUEST['ays_quick_quiz_title_mobile_font_size'] ) ) : 20;

            // Custom class for quiz container
            $quick_quiz_custom_class = (isset($_REQUEST['ays_quick_quiz_custom_class']) && $_REQUEST['ays_quick_quiz_custom_class'] != '') ? stripslashes( sanitize_text_field( $_REQUEST['ays_quick_quiz_custom_class'] ) ) : '';

            // Animation effect
            $quick_quiz_quest_animation = (isset($_REQUEST['ays_quick_quiz_quest_animation']) && $_REQUEST['ays_quick_quiz_quest_animation'] != '') ? stripslashes( sanitize_text_field( $_REQUEST['ays_quick_quiz_quest_animation'] ) ) : 'none';

            // Question Font Size | desktop
            $quick_quiz_question_font_size = (isset($_REQUEST['ays_quick_quiz_question_font_size']) && $_REQUEST['ays_quick_quiz_question_font_size'] != '') ? stripslashes( absint( $_REQUEST['ays_quick_quiz_question_font_size'] ) ) : 16;

            // Question Font Size | mobile
            $quick_quiz_question_mobile_font_size = (isset($_REQUEST['ays_quick_quiz_question_mobile_font_size']) && $_REQUEST['ays_quick_quiz_question_mobile_font_size'] != '') ? stripslashes( absint( $_REQUEST['ays_quick_quiz_question_mobile_font_size'] ) ) : 16;

            // Question text alignment
            $quick_quiz_question_text_alignment = (isset($_REQUEST['ays_quick_quiz_question_text_alignment']) && $_REQUEST['ays_quick_quiz_question_text_alignment'] != '') ? stripslashes( sanitize_text_field( $_REQUEST['ays_quick_quiz_question_text_alignment'] ) ) : 'center';

            // Question image Width
            $quick_quiz_image_width = (isset($_REQUEST['ays_quick_quiz_image_width']) && $_REQUEST['ays_quick_quiz_image_width'] != '') ? stripslashes( absint( $_REQUEST['ays_quick_quiz_image_width'] ) ) : "";

            // Quiz image width percentage/px
            $quick_quiz_image_width_by_percentage_px = (isset($_REQUEST['ays_quick_quiz_image_width_by_percentage_px']) && $_REQUEST['ays_quick_quiz_image_width_by_percentage_px'] != '') ? stripslashes( sanitize_text_field( $_REQUEST['ays_quick_quiz_image_width_by_percentage_px'] ) ) : 'pixels';

            // Question image Height
            $quick_quiz_image_height = (isset($_REQUEST['ays_quick_quiz_image_height']) && $_REQUEST['ays_quick_quiz_image_height'] != '') ? stripslashes( absint( $_REQUEST['ays_quick_quiz_image_height'] ) ) : "";

            // Question image sizing
            $quick_quiz_image_sizing = (isset($_REQUEST['ays_quick_quiz_image_sizing']) && $_REQUEST['ays_quick_quiz_image_sizing'] != '') ? stripslashes( sanitize_text_field( $_REQUEST['ays_quick_quiz_image_sizing'] ) ) : 'cover';

            // Answer Font Size | On desktop
            $quick_quiz_answers_font_size = (isset($_REQUEST['ays_quick_quiz_answers_font_size']) && $_REQUEST['ays_quick_quiz_answers_font_size'] != '') ? stripslashes( absint( $_REQUEST['ays_quick_quiz_answers_font_size'] ) ) : 15;

            // Answer Font Size | On mobile
            $quick_quiz_answers_mobile_font_size = (isset($_REQUEST['ays_quick_quiz_answers_mobile_font_size']) && $_REQUEST['ays_quick_quiz_answers_mobile_font_size'] != '') ? stripslashes( absint( $_REQUEST['ays_quick_quiz_answers_mobile_font_size'] ) ) : 15;

            // Answer gap
            $quick_quiz_answers_margin = (isset($_REQUEST['ays_quick_quiz_answers_margin']) && $_REQUEST['ays_quick_quiz_answers_margin'] != '') ? stripslashes( absint( $_REQUEST['ays_quick_quiz_answers_margin'] ) ) : 12;

            // Disable answer hover
            $quick_quiz_disable_hover_effect = (isset( $_REQUEST['ays_quick_quiz_disable_hover_effect'] ) && $_REQUEST['ays_quick_quiz_disable_hover_effect'] == "on") ? stripslashes( sanitize_text_field( $_REQUEST['ays_quick_quiz_disable_hover_effect'] ) ) : "off";

            /**
             * Buttons Styles Settings
             */

            // Buttons size
            $quick_quiz_buttons_size = (isset($_REQUEST['ays_quick_quiz_buttons_size']) && $_REQUEST['ays_quick_quiz_buttons_size'] != '') ? stripslashes( sanitize_text_field( $_REQUEST['ays_quick_quiz_buttons_size'] ) ) : 'large';

            // Button font-size | On desktop
            $quick_quiz_buttons_font_size = (isset($_REQUEST['ays_quick_quiz_buttons_font_size']) && $_REQUEST['ays_quick_quiz_buttons_font_size'] != '') ? absint( stripslashes( $_REQUEST['ays_quick_quiz_buttons_font_size'] ) ) : 18;

            // Button font-size | On mobile
            $quick_quiz_buttons_mobile_font_size = (isset($_REQUEST['ays_quick_quiz_buttons_mobile_font_size']) && $_REQUEST['ays_quick_quiz_buttons_mobile_font_size'] != '') ? absint( stripslashes( $_REQUEST['ays_quick_quiz_buttons_mobile_font_size'] ) ) : 18;

            // Buttons width
            $quick_quiz_buttons_width = (isset($_REQUEST['ays_quick_quiz_buttons_width']) && $_REQUEST['ays_quick_quiz_buttons_width'] != '') ? absint( stripslashes( $_REQUEST['ays_quick_quiz_buttons_width'] ) ) : "";

            // Buttons Left / Right padding
            $quick_quiz_buttons_left_right_padding = (isset($_REQUEST['ays_quick_quiz_buttons_left_right_padding']) && $_REQUEST['ays_quick_quiz_buttons_left_right_padding'] != '') ? absint( stripslashes( $_REQUEST['ays_quick_quiz_buttons_left_right_padding'] ) ) : 36;

            // Buttons Left / Right padding
            $quick_quiz_buttons_top_bottom_padding = (isset($_REQUEST['ays_quick_quiz_buttons_top_bottom_padding']) && $_REQUEST['ays_quick_quiz_buttons_top_bottom_padding'] != '') ? absint( stripslashes( $_REQUEST['ays_quick_quiz_buttons_top_bottom_padding'] ) ) : 14;

            // Buttons border radius
            $quick_quiz_buttons_border_radius = (isset($_REQUEST['ays_quick_quiz_buttons_border_radius']) && $_REQUEST['ays_quick_quiz_buttons_border_radius'] != '') ? absint( stripslashes( $_REQUEST['ays_quick_quiz_buttons_border_radius'] ) ) : 8;

            /**
             * Admin Note Styles Settings
             */

            // Font size for the note text | On desktop
            $quick_quiz_note_text_font_size = (isset($_REQUEST['ays_quick_quiz_note_text_font_size']) && $_REQUEST['ays_quick_quiz_note_text_font_size'] != '') ? absint( stripslashes( $_REQUEST['ays_quick_quiz_note_text_font_size'] ) ) : 14;

            // Font size for the note text | On mobile
            $quick_quiz_note_text_mobile_font_size = (isset($_REQUEST['ays_quick_quiz_note_text_mobile_font_size']) && $_REQUEST['ays_quick_quiz_note_text_mobile_font_size'] != '') ? absint( stripslashes( $_REQUEST['ays_quick_quiz_note_text_mobile_font_size'] ) ) : 14;

            // Admin note text transform
            $quick_quiz_admin_note_text_transform = (isset( $_REQUEST['ays_quick_quiz_admin_note_text_transform'] ) && $_REQUEST['ays_quick_quiz_admin_note_text_transform'] != "") ? stripslashes( sanitize_text_field( $_REQUEST['ays_quick_quiz_admin_note_text_transform'] ) ) : "none";

            // Note text decoration
            $quick_quiz_admin_note_text_decoration = (isset( $_REQUEST['ays_quick_quiz_admin_note_text_decoration'] ) && $_REQUEST['ays_quick_quiz_admin_note_text_decoration'] != "") ? stripslashes( sanitize_text_field( $_REQUEST['ays_quick_quiz_admin_note_text_decoration'] ) ) : "none";

            // Note letter spacing | Admin note
            $quick_quiz_admin_note_letter_spacing = (isset( $_REQUEST['ays_quick_quiz_admin_note_letter_spacing'] ) && $_REQUEST['ays_quick_quiz_admin_note_letter_spacing'] != "") ? absint( stripslashes( $_REQUEST['ays_quick_quiz_admin_note_letter_spacing'] ) ) : 0;

            // Admin Note font weight
            $quick_quiz_admin_note_font_weight = (isset( $_REQUEST['ays_quick_quiz_admin_note_font_weight'] ) && $_REQUEST['ays_quick_quiz_admin_note_font_weight'] != "") ? stripslashes( sanitize_text_field( $_REQUEST['ays_quick_quiz_admin_note_font_weight'] ) ) : "normal";

            // Font size for the question explanation | On desktop
            $quick_quiz_quest_explanation_font_size = (isset( $_REQUEST['ays_quick_quiz_quest_explanation_font_size'] ) && $_REQUEST['ays_quick_quiz_quest_explanation_font_size'] != "") ? absint( stripslashes( $_REQUEST['ays_quick_quiz_quest_explanation_font_size'] ) ) : 16;

            // Font size for the question explanation | On mobile
            $quick_quiz_quest_explanation_mobile_font_size = (isset( $_REQUEST['ays_quick_quiz_quest_explanation_mobile_font_size'] ) && $_REQUEST['ays_quick_quiz_quest_explanation_mobile_font_size'] != "") ? absint( stripslashes( $_REQUEST['ays_quick_quiz_quest_explanation_mobile_font_size'] ) ) : 16;

            // Text transformation for the question explanation
            $quick_quiz_quest_explanation_text_transform = (isset( $_REQUEST['ays_quick_quiz_quest_explanation_text_transform'] ) && $_REQUEST['ays_quick_quiz_quest_explanation_text_transform'] != "") ? stripslashes( sanitize_text_field( $_REQUEST['ays_quick_quiz_quest_explanation_text_transform'] ) ) : "none";

            // Text decoration for the question explanation
            $quick_quiz_quest_explanation_text_decoration = (isset( $_REQUEST['ays_quick_quiz_quest_explanation_text_decoration'] ) && $_REQUEST['ays_quick_quiz_quest_explanation_text_decoration'] != "") ? stripslashes( sanitize_text_field( $_REQUEST['ays_quick_quiz_quest_explanation_text_decoration'] ) ) : "none";

            // Letter spacing for the question explanation
            $quick_quiz_quest_explanation_letter_spacing = (isset( $_REQUEST['ays_quick_quiz_quest_explanation_letter_spacing'] ) && $_REQUEST['ays_quick_quiz_quest_explanation_letter_spacing'] != "") ? stripslashes( sanitize_text_field( $_REQUEST['ays_quick_quiz_quest_explanation_letter_spacing'] ) ) : 0;

            // Font weight for the question explanation
            $quick_quiz_quest_explanation_font_weight = (isset( $_REQUEST['ays_quick_quiz_quest_explanation_font_weight'] ) && $_REQUEST['ays_quick_quiz_quest_explanation_font_weight'] != "") ? stripslashes( sanitize_text_field( $_REQUEST['ays_quick_quiz_quest_explanation_font_weight'] ) ) : "normal";

            // Font size for the right answer | PC
            $quick_quiz_right_answers_font_size = (isset( $_REQUEST['ays_quick_quiz_right_answers_font_size'] ) && $_REQUEST['ays_quick_quiz_right_answers_font_size'] != "") ? stripslashes( sanitize_text_field( $_REQUEST['ays_quick_quiz_right_answers_font_size'] ) ) : 16;

            // Font size for the right answer | Mobile
            $quick_quiz_right_answers_mobile_font_size = (isset( $_REQUEST['ays_quick_quiz_right_answers_mobile_font_size'] ) && $_REQUEST['ays_quick_quiz_right_answers_mobile_font_size'] != "") ? stripslashes( sanitize_text_field( $_REQUEST['ays_quick_quiz_right_answers_mobile_font_size'] ) ) : 16;

            // Text transformation for the question explanation
            $quick_quiz_right_answer_text_transform = (isset( $_REQUEST['ays_quick_quiz_right_answer_text_transform'] ) && $_REQUEST['ays_quick_quiz_right_answer_text_transform'] != "") ? stripslashes( sanitize_text_field( $_REQUEST['ays_quick_quiz_right_answer_text_transform'] ) ) : "none";

            // Text transformation for the question explanation
            $quick_quiz_right_answers_text_decoration = (isset( $_REQUEST['ays_quick_quiz_right_answers_text_decoration'] ) && $_REQUEST['ays_quick_quiz_right_answers_text_decoration'] != "") ? stripslashes( sanitize_text_field( $_REQUEST['ays_quick_quiz_right_answers_text_decoration'] ) ) : "none";

            // Letter spacing for the right answers
            $quick_quiz_right_answers_letter_spacing = (isset( $_REQUEST['ays_quick_quiz_right_answers_letter_spacing'] ) && $_REQUEST['ays_quick_quiz_right_answers_letter_spacing'] != "") ? absint( stripslashes( $_REQUEST['ays_quick_quiz_right_answers_letter_spacing'] ) ) : 0;

            // Admin Note font weight
            $quick_quiz_right_answers_font_weight = (isset( $_REQUEST['ays_quick_quiz_right_answers_font_weight'] ) && $_REQUEST['ays_quick_quiz_right_answers_font_weight'] != "") ? stripslashes( sanitize_text_field( $_REQUEST['ays_quick_quiz_right_answers_font_weight'] ) ) : "normal";

            // Font size for the right answer | PC
            $quick_quiz_wrong_answers_font_size = (isset( $_REQUEST['ays_quick_quiz_wrong_answers_font_size'] ) && $_REQUEST['ays_quick_quiz_wrong_answers_font_size'] != "") ? stripslashes( sanitize_text_field( $_REQUEST['ays_quick_quiz_wrong_answers_font_size'] ) ) : 16;

            // Font size for the right answer | Mobile
            $quick_quiz_wrong_answers_mobile_font_size = (isset( $_REQUEST['ays_quick_quiz_wrong_answers_mobile_font_size'] ) && $_REQUEST['ays_quick_quiz_wrong_answers_mobile_font_size'] != "") ? stripslashes( sanitize_text_field( $_REQUEST['ays_quick_quiz_wrong_answers_mobile_font_size'] ) ) : 16;

            // Wrong answer text transform
            $quick_quiz_wrong_answer_text_transform = (isset( $_REQUEST['ays_quick_quiz_wrong_answer_text_transform'] ) && $_REQUEST['ays_quick_quiz_wrong_answer_text_transform'] != "") ? stripslashes( sanitize_text_field( $_REQUEST['ays_quick_quiz_wrong_answer_text_transform'] ) ) : "none";

            // Text transformation for the question explanation
            $quick_quiz_wrong_answers_text_decoration = (isset( $_REQUEST['ays_quick_quiz_wrong_answers_text_decoration'] ) && $_REQUEST['ays_quick_quiz_wrong_answers_text_decoration'] != "") ? stripslashes( sanitize_text_field( $_REQUEST['ays_quick_quiz_wrong_answers_text_decoration'] ) ) : "none";

            // Letter spacing | Wrong answer
            $quick_quiz_wrong_answers_letter_spacing = (isset($_REQUEST['ays_quick_quiz_wrong_answers_letter_spacing']) && $_REQUEST['ays_quick_quiz_wrong_answers_letter_spacing'] != '') ? absint ( stripslashes( $_REQUEST['ays_quick_quiz_wrong_answers_letter_spacing'] ) ) : 0;

            // Letter spacing | Wrong weight
            $quick_quiz_wrong_answers_font_weight = (isset( $_REQUEST['ays_quick_quiz_wrong_answers_font_weight'] ) && $_REQUEST['ays_quick_quiz_wrong_answers_font_weight'] != "") ? stripslashes( sanitize_text_field( $_REQUEST['ays_quick_quiz_wrong_answers_font_weight'] ) ) : "normal";

            // Show questions explanation
            $quick_quiz_show_questions_explanation = (isset( $_REQUEST['ays_quick_quiz_show_questions_explanation'] ) && $_REQUEST['ays_quick_quiz_show_questions_explanation'] != "") ? stripslashes( sanitize_text_field( $_REQUEST['ays_quick_quiz_show_questions_explanation'] ) ) : "on_results_page";

            // Questions numbering
            $quick_quiz_show_questions_numbering = (isset( $_REQUEST['ays_quick_quiz_show_questions_numbering'] ) && $_REQUEST['ays_quick_quiz_show_questions_numbering'] != "") ? stripslashes( sanitize_text_field( $_REQUEST['ays_quick_quiz_show_questions_numbering'] ) ) : "none";

            // Answers view
            $quick_quiz_answers_view = (isset( $_REQUEST['ays_quick_quiz_answers_view'] ) && $_REQUEST['ays_quick_quiz_answers_view'] != "") ? stripslashes( sanitize_text_field( $_REQUEST['ays_quick_quiz_answers_view'] ) ) : "list";

            // Show messages for right/wrong answers
            $quick_quiz_answers_rw_texts = (isset( $_REQUEST['ays_quick_quiz_answers_rw_texts'] ) && $_REQUEST['ays_quick_quiz_answers_rw_texts'] != "") ? stripslashes( sanitize_text_field( $_REQUEST['ays_quick_quiz_answers_rw_texts'] ) ) : "on_passing";

            // Show quiz head information / Show title
            $quick_quiz_show_quiz_title = (isset( $_REQUEST['ays_quick_quiz_show_quiz_title'] ) && $_REQUEST['ays_quick_quiz_show_quiz_title'] == "on") ? stripslashes( sanitize_text_field( $_REQUEST['ays_quick_quiz_show_quiz_title'] ) ) : "off";

            // Show quiz head information / Show Description
            $quick_quiz_show_quiz_desc = (isset( $_REQUEST['ays_quick_quiz_show_quiz_desc'] ) && $_REQUEST['ays_quick_quiz_show_quiz_desc'] == "on") ? stripslashes( sanitize_text_field( $_REQUEST['ays_quick_quiz_show_quiz_desc'] ) ) : "off";

            // Display score
            $quick_quiz_display_score = (isset( $_REQUEST['ays_quick_quiz_display_score'] ) && $_REQUEST['ays_quick_quiz_display_score'] != "") ? stripslashes( sanitize_text_field( $_REQUEST['ays_quick_quiz_display_score'] ) ) : "by_percantage";

            // Show question results on the results page
            $quick_quiz_enable_questions_result = (isset( $_REQUEST['ays_quick_quiz_enable_questions_result'] ) && $_REQUEST['ays_quick_quiz_enable_questions_result'] == "on") ? stripslashes( sanitize_text_field( $_REQUEST['ays_quick_quiz_enable_questions_result'] ) ) : "off";

            // Hide correct answers
            $quick_quiz_hide_correct_answers = (isset( $_REQUEST['ays_quick_quiz_hide_correct_answers'] ) && $_REQUEST['ays_quick_quiz_hide_correct_answers'] == "on") ? stripslashes( sanitize_text_field( $_REQUEST['ays_quick_quiz_hide_correct_answers'] ) ) : "off";

            // Show wrong answers first
            $quick_quiz_show_wrong_answers_first = (isset( $_REQUEST['ays_quick_quiz_show_wrong_answers_first'] ) && $_REQUEST['ays_quick_quiz_show_wrong_answers_first'] == "on") ? stripslashes( sanitize_text_field( $_REQUEST['ays_quick_quiz_show_wrong_answers_first'] ) ) : "off";

            // Show wrong answers first
            $quick_quiz_show_only_wrong_answers = (isset( $_REQUEST['ays_quick_quiz_show_only_wrong_answers'] ) && $_REQUEST['ays_quick_quiz_show_only_wrong_answers'] == "on") ? stripslashes( sanitize_text_field( $_REQUEST['ays_quick_quiz_show_only_wrong_answers'] ) ) : "off";

            // Show wrong answers first
            $quick_quiz_enable_results_toggle = (isset( $_REQUEST['ays_quick_quiz_enable_results_toggle'] ) && $_REQUEST['ays_quick_quiz_enable_results_toggle'] == "on") ? stripslashes( sanitize_text_field( $_REQUEST['ays_quick_quiz_enable_results_toggle'] ) ) : "off";

            // Show wrong answers first
            $quick_quiz_enable_default_hide_results_toggle = (isset( $_REQUEST['ays_quick_quiz_enable_default_hide_results_toggle'] ) && $_REQUEST['ays_quick_quiz_enable_default_hide_results_toggle'] == "on") ? stripslashes( sanitize_text_field( $_REQUEST['ays_quick_quiz_enable_default_hide_results_toggle'] ) ) : "off";

            // Group questions by category
            $quick_quiz_enable_questions_ordering_by_cat = (isset( $_REQUEST['ays_quick_quiz_enable_questions_ordering_by_cat'] ) && $_REQUEST['ays_quick_quiz_enable_questions_ordering_by_cat'] == "on") ? stripslashes( sanitize_text_field( $_REQUEST['ays_quick_quiz_enable_questions_ordering_by_cat'] ) ) : "off";

            // Enable questions numbering by category
            $quick_quiz_questions_numbering_by_category = (isset( $_REQUEST['ays_quick_quiz_questions_numbering_by_category'] ) && $_REQUEST['ays_quick_quiz_questions_numbering_by_category'] == "on") ? stripslashes( sanitize_text_field( $_REQUEST['ays_quick_quiz_questions_numbering_by_category'] ) ) : "off";

            // Show question category description
            $quick_quiz_enable_question_category_description = (isset( $_REQUEST['ays_quick_quiz_enable_question_category_description'] ) && $_REQUEST['ays_quick_quiz_enable_question_category_description'] == "on") ? stripslashes( sanitize_text_field( $_REQUEST['ays_quick_quiz_enable_question_category_description'] ) ) : "off";

            // Show quiz category description
            $quick_quiz_enable_quiz_category_description = (isset( $_REQUEST['ays_quick_quiz_enable_quiz_category_description'] ) && $_REQUEST['ays_quick_quiz_enable_quiz_category_description'] == "on") ? stripslashes( sanitize_text_field( $_REQUEST['ays_quick_quiz_enable_quiz_category_description'] ) ) : "off";

            // Show wrong answers first
            $quick_quiz_enable_early_finsh_comfirm_box = (isset( $_REQUEST['ays_quick_quiz_enable_early_finsh_comfirm_box'] ) && $_REQUEST['ays_quick_quiz_enable_early_finsh_comfirm_box'] == "on") ? stripslashes( sanitize_text_field( $_REQUEST['ays_quick_quiz_enable_early_finsh_comfirm_box'] ) ) : "off";

            // Enable live progress bar
            $quick_quiz_enable_live_progress_bar = (isset( $_REQUEST['ays_quick_quiz_enable_live_progress_bar'] ) && $_REQUEST['ays_quick_quiz_enable_live_progress_bar'] == "on") ? stripslashes( sanitize_text_field( $_REQUEST['ays_quick_quiz_enable_live_progress_bar'] ) ) : "off";

            // Enable percent view
            $quick_quiz_enable_percent_view_option = (isset( $_REQUEST['ays_quick_quiz_enable_percent_view_option'] ) && $_REQUEST['ays_quick_quiz_enable_percent_view_option'] == "on") ? stripslashes( sanitize_text_field( $_REQUEST['ays_quick_quiz_enable_percent_view_option'] ) ) : "off";

            // Show button on quiz fail
            $quick_quiz_show_restart_button_on_quiz_fail = (isset( $_REQUEST['ays_quick_quiz_show_restart_button_on_quiz_fail'] ) && $_REQUEST['ays_quick_quiz_show_restart_button_on_quiz_fail'] == "on") ? stripslashes( sanitize_text_field( $_REQUEST['ays_quick_quiz_show_restart_button_on_quiz_fail'] ) ) : "off";

            // Enable quiz assessment
            $quick_quiz_enable_quiz_rate = (isset( $_REQUEST['ays_quick_quiz_enable_quiz_rate'] ) && $_REQUEST['ays_quick_quiz_enable_quiz_rate'] == "on") ? stripslashes( sanitize_text_field( $_REQUEST['ays_quick_quiz_enable_quiz_rate'] ) ) : "off";

            // Show the last 5 reviews
            $quick_quiz_enable_rate_comments = (isset( $_REQUEST['ays_quick_quiz_enable_rate_comments'] ) && $_REQUEST['ays_quick_quiz_enable_rate_comments'] == "on") ? stripslashes( sanitize_text_field( $_REQUEST['ays_quick_quiz_enable_rate_comments'] ) ) : "off";

            // Make responses anonymous
            $quick_quiz_make_responses_anonymous = (isset( $_REQUEST['ays_quick_quiz_make_responses_anonymous'] ) && $_REQUEST['ays_quick_quiz_make_responses_anonymous'] == "on") ? stripslashes( sanitize_text_field( $_REQUEST['ays_quick_quiz_make_responses_anonymous'] ) ) : "off";

            // Enable users' anonymous assessment
            $quick_quiz_enable_user_cհoosing_anonymous_assessment = (isset( $_REQUEST['ays_quick_quiz_enable_user_cհoosing_anonymous_assessment'] ) && $_REQUEST['ays_quick_quiz_enable_user_cհoosing_anonymous_assessment'] == "on") ? stripslashes( sanitize_text_field( $_REQUEST['ays_quick_quiz_enable_user_cհoosing_anonymous_assessment'] ) ) : "off";

            // Display all reviews button
            $quick_quiz_make_all_review_link = (isset( $_REQUEST['ays_quick_quiz_make_all_review_link'] ) && $_REQUEST['ays_quick_quiz_make_all_review_link'] == "on") ? stripslashes( sanitize_text_field( $_REQUEST['ays_quick_quiz_make_all_review_link'] ) ) : "off";

            // Enable Comment Field
            $quick_quiz_review_enable_comment_field = (isset( $_REQUEST['ays_quick_quiz_review_enable_comment_field'] ) && $_REQUEST['ays_quick_quiz_review_enable_comment_field'] == "on") ? stripslashes( sanitize_text_field( $_REQUEST['ays_quick_quiz_review_enable_comment_field'] ) ) : "off";

            // Make the review field required
            $quick_quiz_make_review_required = (isset( $_REQUEST['ays_quick_quiz_make_review_required'] ) && $_REQUEST['ays_quick_quiz_make_review_required'] == "on") ? stripslashes( sanitize_text_field( $_REQUEST['ays_quick_quiz_make_review_required'] ) ) : "off";

            // Make the review field required
            $quick_quiz_review_placeholder_text = (isset( $_REQUEST['ays_quick_quiz_review_placeholder_text'] ) && !empty($_REQUEST['ays_quick_quiz_review_placeholder_text'])) ? stripslashes( sanitize_text_field( $_REQUEST['ays_quick_quiz_review_placeholder_text'] ) ) : "";

            // Admin note text transform | Mobile
            $quick_quiz_admin_note_mobile_text_transform = (isset( $_REQUEST['ays_quick_quiz_admin_note_mobile_text_transform'] ) && $_REQUEST['ays_quick_quiz_admin_note_mobile_text_transform'] != "") ? stripslashes( sanitize_text_field( $_REQUEST['ays_quick_quiz_admin_note_mobile_text_transform'] ) ) : "none";            
            
            
        }
        
        foreach ($questions as $question_key => $question) {

            $cat_key = array_search( $question_key, $questions_cat );
            $q_category_id = (isset( $questions_cat[$cat_key] ) && $questions_cat[$cat_key] != "") ? esc_sql( $questions_cat[$cat_key] ) : 1;

            if ( !isset( $questions_type[$question_key] ) || is_null( $questions_type[$question_key] ) ) {
                continue;
            }

            $wpdb->insert($questions_table, array(
                'category_id'   => $q_category_id,
                'question'      => esc_sql( stripslashes($question) ),
                'published'     => 1,
                'type'          => $questions_type[$question_key],
                'create_date'   => esc_sql( $create_date ),
                'options'       => json_encode($options)
            ));
            $question_id = $wpdb->insert_id;
            $questions_ids .= $question_id . ',';
            if ( isset( $answers[$question_key] ) && ! empty( $answers[$question_key] ) ) {

                foreach ($answers[$question_key] as $key => $answer) {
                    $type = $questions_type[$question_key];

                    if($type == "text" || $type == "short_text"){
                        $correct = 1;
                    }else{
                        $correct = ($answers_correct[$question_key][$key] == "true") ? 1 : 0;
                    }
                    $placeholder = '';

                    $wpdb->insert($answers_table, array(
                        'question_id'   => esc_sql( $question_id ),
                        'answer'        => esc_sql( trim( stripslashes($answer) ) ),
                        'correct'       => $correct,
                        'ordering'      => $key,
                        'placeholder'   => $placeholder
                    ));
                }
            }
        }


        $default_options = array(
            'quiz_theme'                                        => 'classic_light',
            'color'                                             => '#5d6cf9',
            'bg_color'                                          => '#fff',
            'text_color'                                        => '#000000',
            'height'                                            => $quick_quiz_height,
            'width'                                             => $quick_quiz_width,
            'timer'                                             => 100,
            'information_form'                                  => 'disable',
            'form_name'                                         => '',
            'form_email'                                        => '',
            'form_phone'                                        => '',
            'enable_logged_users'                               => 'off',
            'answers_view'                                      => $quick_quiz_answers_view,
            'image_width'                                       => $quick_quiz_image_width,
            'image_height'                                      => $quick_quiz_image_height,
            'quiz_image_width_by_percentage_px'                 => $quick_quiz_image_width_by_percentage_px,
            'quiz_image_height'                                 => $quick_quiz_image_height,
            'enable_correction'                                 => $quick_quiz_enable_correction,
            'enable_questions_counter'                          => $quick_quiz_enable_questions_counter,
            'limit_users'                                       => 'off',
            'limitation_message'                                => '',
            'redirect_url'                                      => '',
            'redirection_delay'                                 => '',
            'enable_progress_bar'                               => $quick_quiz_enable_progress_bar,
            'randomize_questions'                               => $quick_quiz_enable_randomize_questions,
            'randomize_answers'                                 => $quick_quiz_enable_randomize_answers,
            'enable_questions_result'                           => $quick_quiz_enable_questions_result,
            'enable_average_statistical'                        => $quick_quiz_enable_average_statistical,
            'enable_next_button'                                => $quick_quiz_enable_next_button,
            'enable_previous_button'                            => $quick_quiz_enable_previous_button,
            'custom_css'                                        => '',
            'enable_restriction_pass'                           => 'off',
            'restriction_pass_message'                          => '',
            'user_role'                                         => '',
            'result_text'                                       => '',
            'enable_result'                                     => 'off',
            'enable_timer'                                      => 'off',
            'enable_pass_count'                                 => $quick_quiz_enable_pass_count,
            'enable_quiz_rate'                                  => $quick_quiz_enable_quiz_rate,
            'enable_rate_avg'                                   => $quick_quiz_enable_rate_avg,
            'enable_rate_comments'                              => $quick_quiz_enable_rate_comments,
            'hide_score'                                        => $quick_quiz_hide_score,
            'rate_form_title'                                   => '',
            'enable_box_shadow'                                 => 'on',
            'box_shadow_color'                                  => '#c9c9c9',
            'quiz_border_radius'                                => $quick_quiz_border_radius,
            'quiz_bg_image'                                     => '',
            'enable_border'                                     => 'off',
            'quiz_border_width'                                 => '1',
            'quiz_border_style'                                 => 'solid',
            'quiz_border_color'                                 => '#000',
            'quiz_timer_in_title'                               => 'off',
            'enable_restart_button'                             => $quick_quiz_enable_restart_button,
            'quiz_loader'                                       => 'default',
            'create_date'                                       => $create_date,
            'author'                                            => $author,
            'autofill_user_data'                                => $quick_quiz_autofill_user_data,
            'display_fields_labels'                             => $quick_quiz_display_fields_labels,
            'quest_animation'                                   => $quick_quiz_quest_animation,
            'form_title'                                        => '',
            'enable_bg_music'                                   => 'off',
            'quiz_bg_music'                                     => '',
            'answers_font_size'                                 => $quick_quiz_answers_font_size,
            'show_create_date'                                  => $quick_quiz_show_create_date,
            'show_author'                                       => $quick_quiz_show_author,
            'enable_early_finish'                               => $quick_quiz_enable_early_finish,
            'answers_rw_texts'                                  => $quick_quiz_answers_rw_texts,
            'disable_store_data'                                => $quick_quiz_disable_store_data,
            'enable_background_gradient'                        => 'off',
            'background_gradient_color_1'                       => '#000',
            'background_gradient_color_2'                       => '#fff',
            'quiz_gradient_direction'                           => 'vertical',
            'redirect_after_submit'                             => 'off',
            'submit_redirect_url'                               => '',
            'submit_redirect_delay'                             => '',
            'progress_bar_style'                                => $quick_quiz_progress_bar_style,
            'progress_live_bar_style'                           => $quick_quiz_progress_live_bar_style,
            'enable_exit_button'                                => 'off',
            'exit_redirect_url'                                 => '',
            'image_sizing'                                      => $quick_quiz_image_sizing,
            'quiz_bg_image_position'                            => 'center center',
            'custom_class'                                      => $quick_quiz_custom_class,
            'enable_social_buttons'                             => 'off',
            'enable_social_links'                               => 'off',
            'social_links' => array(
                'linkedin_link'         => '',
                'facebook_link'         => '',
                'twitter_link'          => '',
                'vkontakte_link'        => '',
                'instagram_link'        => '',
                'youtube_link'          => '',
                'behance_link'          => '',
            ),
            'show_quiz_title'                                   => $quick_quiz_show_quiz_title,
            'show_quiz_desc'                                    => $quick_quiz_show_quiz_desc,
            'show_login_form'                                   => 'off',
            'mobile_max_width'                                  => '',
            'limit_users_by'                                    => 'ip',
            'activeInterval'                                    => '',
            'deactiveInterval'                                  => '',
            'active_date_check'                                 => 'off',
            'active_date_pre_start_message'                     => __("The quiz will be available soon!", 'quiz-maker'),
            'active_date_message'                               => __("The quiz has expired!", 'quiz-maker'),
            'explanation_time'                                  => '4',
            'enable_clear_answer'                               => $quick_quiz_enable_clear_answer,
            'show_category'                                     => $quick_quiz_show_category,
            'show_question_category'                            => $quick_quiz_show_question_category,
            'display_score'                                     => $quick_quiz_display_score,
            'enable_rw_asnwers_sounds'                          => $quick_quiz_enable_rw_asnwers_sounds,
            'ans_right_wrong_icon'                              => 'none',
            'quiz_bg_img_in_finish_page'                        => 'off',
            'finish_after_wrong_answer'                         => 'off',
            'enable_enter_key'                                  => $quick_quiz_enable_enter_key,
            'buttons_text_color'                                => '#ffffff',
            'buttons_position'                                  => $quick_quiz_buttons_position,
            'show_questions_explanation'                        => $quick_quiz_show_questions_explanation,
            'enable_audio_autoplay'                             => $quick_quiz_enable_audio_autoplay,
            'buttons_size'                                      => $quick_quiz_buttons_size,
            'buttons_font_size'                                 => $quick_quiz_buttons_font_size,
            'buttons_width'                                     => $quick_quiz_buttons_width,
            'buttons_left_right_padding'                        => $quick_quiz_buttons_left_right_padding,
            'buttons_top_bottom_padding'                        => $quick_quiz_buttons_top_bottom_padding,
            'buttons_border_radius'                             => $quick_quiz_buttons_border_radius,
            'enable_leave_page'                                 => $quick_quiz_enable_leave_page,
            'enable_see_result_confirm_box'                     => $quick_quiz_enable_see_result_confirm_box,
            'enable_tackers_count'                              => 'off',
            'pass_score'                                        => '0',
            'question_font_size'                                => $quick_quiz_question_font_size,
            'quiz_width_by_percentage_px'                       => 'pixels',
            'questions_hint_icon_or_text'                       => 'default',
            'enable_early_finsh_comfirm_box'                    => $quick_quiz_enable_early_finsh_comfirm_box,
            'enable_questions_ordering_by_cat'                  => $quick_quiz_enable_questions_ordering_by_cat,
            'show_schedule_timer'                               => 'off',
            'show_timer_type'                                   => 'countdown',
            'quiz_loader_text_value'                            => '',
            'hide_correct_answers'                              => $quick_quiz_hide_correct_answers,
            'show_information_form'                             => $quick_quiz_show_information_form,
            'quiz_loader_custom_gif'                            => '',
            'disable_hover_effect'                              => $quick_quiz_disable_hover_effect,
            'quiz_loader_custom_gif_width'                      => 100,
            'show_answers_numbering'                            => 'none',
            'quiz_title_transformation'                         => $quick_quiz_title_transformation,
            'quiz_box_shadow_x_offset'                          => 0,
            'quiz_box_shadow_y_offset'                          => 0,
            'quiz_box_shadow_z_offset'                          => 15,
            'quiz_question_text_alignment'                      => $quick_quiz_question_text_alignment,
            'quiz_arrow_type'                                   => 'default',
            'quiz_show_wrong_answers_first'                     => $quick_quiz_show_wrong_answers_first,
            'quiz_display_all_questions'                        => $quick_quiz_display_all_questions,
            'enable_rtl_direction'                              => $quick_quiz_enable_rtl_direction,
            'quiz_enable_question_image_zoom'                   => $quick_quiz_enable_question_image_zoom,
            'quiz_timer_red_warning'                            => 'off',
            'quiz_schedule_timezone'                            => get_option( 'timezone_string' ),
            'questions_hint_button_value'                       => '',
            'quiz_tackers_message'                              => __( "This quiz is expired!", 'quiz-maker' ),
            'quiz_enable_linkedin_share_button'                 => 'on',
            'quiz_enable_facebook_share_button'                 => 'on',
            'quiz_enable_twitter_share_button'                  => 'on',
            'quiz_make_responses_anonymous'                     => $quick_quiz_make_responses_anonymous,
            'quiz_enable_user_cհoosing_anonymous_assessment'    => $quick_quiz_enable_user_cհoosing_anonymous_assessment,
            'quiz_make_all_review_link'                         => $quick_quiz_make_all_review_link,
            'show_questions_numbering'                          => $quick_quiz_show_questions_numbering,
            'quiz_message_before_timer'                         => '',
            'enable_full_screen_mode'                           => 'off',
            'quiz_enable_password_visibility'                   => 'off',
            'question_mobile_font_size'                         => $quick_quiz_question_mobile_font_size,
            'answers_mobile_font_size'                          => $quick_quiz_answers_mobile_font_size,
            'social_buttons_heading'                            => '',
            'quiz_enable_vkontakte_share_button'                => 'on',
            'answers_border'                                    => 'on',
            'answers_border_width'                              => '1',
            'answers_border_style'                              => 'solid',
            'answers_border_color'                              => '#dddddd',
            'social_links_heading'                              => '',
            'quiz_enable_question_category_description'         => $quick_quiz_enable_question_category_description,
            'answers_margin'                                    => $quick_quiz_answers_margin,
            'quiz_message_before_redirect_timer'                => '',
            'buttons_mobile_font_size'                          => $quick_quiz_buttons_mobile_font_size,
            'answers_box_shadow'                                => 'off',
            'answers_box_shadow_color'                          => '#000',
            'quiz_answer_box_shadow_x_offset'                   => 0,
            'quiz_answer_box_shadow_y_offset'                   => 0,
            'quiz_answer_box_shadow_z_offset'                   => 10,
            'quiz_create_author'                                => $user_id,
            'quiz_enable_title_text_shadow'                     => "off",
            'quiz_title_text_shadow_color'                      => "#333",
            'quiz_title_text_shadow_x_offset'                   => 2,
            'quiz_title_text_shadow_y_offset'                   => 2,
            'quiz_title_text_shadow_z_offset'                   => 2,
            'quiz_title_font_size'                              => $quick_quiz_title_font_size,
            'quiz_title_mobile_font_size'                       => $quick_quiz_title_mobile_font_size,
            'quiz_password_width'                               => "",
            'quiz_review_placeholder_text'                      => $quick_quiz_review_placeholder_text,
            'quiz_enable_results_toggle'                        => $quick_quiz_enable_results_toggle,
            'quiz_review_thank_you_message'                     => "",
            'quiz_review_enable_comment_field'                  => $quick_quiz_review_enable_comment_field,
            'quiz_make_review_required'                         => $quick_quiz_make_review_required,
            'quest_explanation_font_size'                       => $quick_quiz_quest_explanation_font_size,
            'quest_explanation_mobile_font_size'                => $quick_quiz_quest_explanation_mobile_font_size,
            'wrong_answers_font_size'                           => $quick_quiz_wrong_answers_font_size,
            'wrong_answers_mobile_font_size'                    => $quick_quiz_wrong_answers_mobile_font_size,
            'right_answers_font_size'                           => $quick_quiz_right_answers_font_size,
            'right_answers_mobile_font_size'                    => $quick_quiz_right_answers_mobile_font_size,
            'note_text_font_size'                               => $quick_quiz_note_text_font_size,
            'note_text_mobile_font_size'                        => $quick_quiz_note_text_mobile_font_size,
            'quiz_questions_numbering_by_category'              => $quick_quiz_questions_numbering_by_category,
            'quiz_enable_custom_texts_for_buttons'              => $quick_quiz_enable_custom_texts_for_buttons,
            'quiz_custom_texts_start_button'                    => $quick_quiz_custom_texts_start_button,
            'quiz_custom_texts_next_button'                     => $quick_quiz_custom_texts_next_button,
            'quiz_custom_texts_prev_button'                     => $quick_quiz_custom_texts_prev_button,
            'quiz_custom_texts_clear_button'                    => $quick_quiz_custom_texts_clear_button,
            'quiz_custom_texts_finish_button'                   => $quick_quiz_custom_texts_finish_button,
            'quiz_custom_texts_see_results_button'              => $quick_quiz_custom_texts_see_results_button,
            'quiz_custom_texts_restart_quiz_button'             => $quick_quiz_custom_texts_restart_button,
            'quiz_custom_texts_send_feedback_button'            => $quick_quiz_custom_texts_send_feedback_button,
            'quiz_custom_texts_load_more_button'                => $quick_quiz_custom_texts_load_more_button,
            'quiz_custom_texts_exit_button'                     => $quick_quiz_custom_texts_exit_button,
            'quiz_custom_texts_check_button'                    => $quick_quiz_custom_texts_check_button,
            'quiz_custom_texts_login_button'                    => $quick_quiz_custom_texts_login_button,
            'quiz_display_messages_before_buttons'              => $quick_quiz_display_messages_before_buttons,
            'quiz_enable_quiz_category_description'             => $quick_quiz_enable_quiz_category_description,
            'quiz_admin_note_text_transform'                    => $quick_quiz_admin_note_text_transform,
            'quiz_quest_explanation_text_transform'             => $quick_quiz_quest_explanation_text_transform,
            'quiz_right_answer_text_transform'                  => $quick_quiz_right_answer_text_transform,
            'quiz_wrong_answer_text_transform'                  => $quick_quiz_wrong_answer_text_transform,
            'quiz_admin_note_text_decoration'                   => $quick_quiz_admin_note_text_decoration,
            'quiz_quest_explanation_text_decoration'            => $quick_quiz_quest_explanation_text_decoration,
            'quiz_right_answers_text_decoration'                => $quick_quiz_right_answers_text_decoration,
            'quiz_wrong_answers_text_decoration'                => $quick_quiz_wrong_answers_text_decoration,
            'quiz_admin_note_letter_spacing'                    => $quick_quiz_admin_note_letter_spacing,
            'quiz_bg_img_during_the_quiz'                       => "off",
            'quiz_quest_explanation_letter_spacing'             => $quick_quiz_quest_explanation_letter_spacing,
            'quiz_right_answers_letter_spacing'                 => $quick_quiz_right_answers_letter_spacing,
            'quiz_wrong_answers_letter_spacing'                 => $quick_quiz_wrong_answers_letter_spacing,
            'quiz_admin_note_font_weight'                       => $quick_quiz_admin_note_font_weight,
            'quiz_quest_explanation_font_weight'                => $quick_quiz_quest_explanation_font_weight,
            'quiz_right_answers_font_weight'                    => $quick_quiz_right_answers_font_weight,
            'quiz_wrong_answers_font_weight'                    => $quick_quiz_wrong_answers_font_weight,
            'quiz_show_only_wrong_answers'                      => $quick_quiz_show_only_wrong_answers,
            'quiz_content_max_width'                            => 90,
            'quiz_content_mobile_max_width'                     => 90,
            'quiz_timer_warning_text_color'                     => "#ff0000",
            'quiz_enable_default_hide_results_toggle'           => $quick_quiz_enable_default_hide_results_toggle,
            'quiz_show_restart_button_on_quiz_fail'             => $quick_quiz_show_restart_button_on_quiz_fail,
            'enable_live_progress_bar'                          => $quick_quiz_enable_live_progress_bar,
            'enable_percent_view'                               => $quick_quiz_enable_percent_view_option,
            'quiz_enable_keyboard_navigation'                   => 'on',
            'quiz_admin_note_mobile_text_transform'             => $quick_quiz_admin_note_mobile_text_transform,
        );


        $questions_ids = rtrim($questions_ids, ",");
        $wpdb->insert($quizes_table, array(
            'title'                 => $quiz_title,//esc_sql( $quiz_title ),
            'description'           => $quiz_description,//esc_sql( $quiz_description ),
            'question_ids'          => $questions_ids,
            'published'             => $quick_quiz_publish,
            'options'               => json_encode($default_options),
            'quiz_category_id'      => $quiz_cat_id,
            'ordering'              => $ordering,
        ));
        $quiz_id = $wpdb->insert_id;

        $post_type_args = array(
            'quiz_id'       => $quiz_id,
            'author_id'     => !empty($user->ID) ? $user->ID : get_current_user_id(),
            'quiz_title'    => $quiz_title,
        );
        
        $custom_post_id = Quiz_Maker_Custom_Post_Type::ays_quiz_add_custom_post($post_type_args);

        $preview_url = "#";
        if(!empty($custom_post_id)){
            $custom_post_url = array(
                'post_type' => 'ays-quiz-maker',
                'p'         => $custom_post_id,
                'preview'   => 'true',
            );
            $custom_post_url_ready = http_build_query($custom_post_url);
            $preview_url = get_home_url();
            $preview_url .= '/?' . $custom_post_url_ready;
        }

        ob_end_clean();
        $ob_get_clean = ob_get_clean();
        echo json_encode(array(
            'status'        => true,
            'quiz_id'       => $quiz_id,
            'preview_url'   => $preview_url,
        ));
        wp_die();
    }
    
    /**
     * Recursive sanitation for an array
     * 
     * @param $array
     *
     * @return mixed
     */
    public static function recursive_sanitize_text_field($array) {
        foreach ( $array as $key => &$value ) {
            if ( is_array( $value ) ) {
                $value = self::recursive_sanitize_text_field($value);
            } else {
                $value = sanitize_text_field( $value );
            }
        }

        return $array;
    }

    /**
     * Public Recursive sanitation for an array
     * 
     * @param $array
     *
     * @return mixed
     */
    public static function recursive_sanitize_text_field_public($array) {
        foreach ( $array as $key => &$value ) {
            if ( is_array( $value ) ) {
                $value = self::recursive_sanitize_text_field_public($value);
            } else {
                if( strpos($key, "ays-question") !== false  ){
                    $value = sanitize_text_field( $value );
                }elseif (is_numeric($key)) {
                    $value = sanitize_text_field( $value );
                } else {
                    if( isset($array[$key]) ){
                        unset($array[$key]);
                    }
                }
            }
        }

        return $array;
    }
    
    public static function get_max_id($table) {
        global $wpdb;
        $quiz_table = $wpdb->prefix . 'aysquiz_'.$table;

        $sql = "SELECT max(id) FROM {$quiz_table}";

        $result = intval($wpdb->get_var($sql));

        return $result;
    }
    
    public function ays_show_results(){
        global $wpdb;
        $results_table = $wpdb->prefix . "aysquiz_reports";
        $questions_table = $wpdb->prefix . "aysquiz_questions";

        // Run a security check.
        check_ajax_referer( 'quiz-maker-ajax-results-nonce', sanitize_key( $_REQUEST['_ajax_nonce'] ) );

        $user_can_capability = self::quiz_maker_capabilities();

        // Check for permissions.
        if ( ! current_user_can( $user_can_capability ) ) {
            ob_end_clean();
            $ob_get_clean = ob_get_clean();
            echo json_encode(array(
                'status' => false,
                "rows" => ""
            ));
            wp_die();
        }

        if (isset($_REQUEST['action']) && sanitize_text_field( $_REQUEST['action'] ) == 'ays_show_results') {
            $id = absint(intval($_REQUEST['result']));
            $results = $wpdb->get_row("SELECT * FROM {$results_table} WHERE id={$id}", "ARRAY_A");            
            $score = intval($results['score']);
            // $user_id = intval($results['user_id']);
            $user_id = isset($results['user_id']) ? intval($results['user_id']) : null;
            $quiz_id = isset($results['quiz_id']) ? intval($results['quiz_id']) : null;
            
            $user = get_user_by('id', $user_id);

            if( !$user ){
                $user_display_name = __( "Deleted user", 'quiz-maker' );
            } else {
                $user_display_name = isset( $user->data->display_name ) && $user->data->display_name != "" ? $user->data->display_name : __( "Deleted user", 'quiz-maker' );
            }
            
            $user_ip = $results['user_ip'];
            $options = json_decode($results['options']);
            $user_attributes = isset( $options->attributes_information ) ? $options->attributes_information : null;
            $start_date = $results['start_date'];
            $duration = isset( $options->passed_time ) ? intval($options->passed_time) : '';
            $rate_id = isset($options->rate_id) ? intval($options->rate_id) : null;
            $rate = $this->ays_quiz_rate($rate_id);
            $calc_method = isset($options->calc_method) ? sanitize_text_field($options->calc_method) : 'by_correctness';
            
            $from = self::get_user_country_by_ip( $user_ip );

            $note_text = ( isset($options->note_text) && $options->note_text != '' ) ? sanitize_text_field( stripslashes( $options->note_text ) ) : '';

            // Settings Options
            $settings_obj = new Quiz_Maker_Settings_Actions($this->plugin_name);
            $settings_options = ($settings_obj->ays_get_setting('options') === false) ? array() : json_decode( stripcslashes($settings_obj->ays_get_setting('options') ), true);

            
            $ays_quiz_show_result_info_user_ip = isset($settings_options['ays_quiz_show_result_info_user_ip']) ? sanitize_text_field( $settings_options['ays_quiz_show_result_info_user_ip'] ) : 'on';
            $ays_quiz_show_result_info_user_id = isset($settings_options['ays_quiz_show_result_info_user_id']) ? sanitize_text_field( $settings_options['ays_quiz_show_result_info_user_id'] ) : 'on';
            $ays_quiz_show_result_info_user = isset($settings_options['ays_quiz_show_result_info_user']) ? sanitize_text_field( $settings_options['ays_quiz_show_result_info_user'] ) : 'on';
            $ays_quiz_show_result_info_user_email = isset($settings_options['ays_quiz_show_result_info_user_email']) ? sanitize_text_field( $settings_options['ays_quiz_show_result_info_user_email'] ) : 'on';
            $ays_quiz_show_result_info_user_name = isset($settings_options['ays_quiz_show_result_info_user_name']) ? sanitize_text_field( $settings_options['ays_quiz_show_result_info_user_name'] ) : 'on';
            $ays_quiz_show_result_info_user_phone = isset($settings_options['ays_quiz_show_result_info_user_phone']) ? sanitize_text_field( $settings_options['ays_quiz_show_result_info_user_phone'] ) : 'on';

            $ays_quiz_show_result_info_start_date = isset($settings_options['ays_quiz_show_result_info_start_date']) ? sanitize_text_field( $settings_options['ays_quiz_show_result_info_start_date'] ) : 'on';
            $ays_quiz_show_result_info_duration = isset($settings_options['ays_quiz_show_result_info_duration']) ? sanitize_text_field( $settings_options['ays_quiz_show_result_info_duration'] ) : 'on';
            $ays_quiz_show_result_info_score = isset($settings_options['ays_quiz_show_result_info_score']) ? sanitize_text_field( $settings_options['ays_quiz_show_result_info_score'] ) : 'on';
            $ays_quiz_show_result_info_rate = isset($settings_options['ays_quiz_show_result_info_rate']) ? sanitize_text_field( $settings_options['ays_quiz_show_result_info_rate'] ) : 'on';


            $pro_content = array();

            $pro_content[] = '<div class="only_pro only_pro_save_as_default" style="margin: 0 10px;" title="'. __("This feature available only in pro version", 'quiz-maker') .'">';
                $pro_content[] = '<div class="pro_features pro_features_popup">';
                    $pro_content[] = '<div class="pro-features-popup-conteiner">';
                        $pro_content[] = '<div class="pro-features-popup-title">';
                        $pro_content[] = __("Export Individual Result", 'quiz-maker');
                        $pro_content[] = '</div>';

                        $pro_content[] = '<div class="pro-features-popup-content" data-link="https://www.youtube.com/watch?v=vrKgo74ZMzI">';
                            $pro_content[] = '<p>';
                                $pro_content[] = sprintf(
                                    /* translators: 1: opening <strong> tag, 2: closing </strong> tag */
                                    __( 'With the excellent plugin Quiz Maker, you can %1$s export a Detailed report of the Individual Result %2$s with just a few clicks.', 'quiz-maker' ),
                                    '<strong>',
                                    '</strong>'
                                );
                            $pro_content[] = '</p>';

                            $pro_content[] = '<p>';
                                $pro_content[] = sprintf(
                                    /* translators: 1: opening <strong> tag before "PDF file", 2: closing </strong> tag after "PDF file", 3: opening <strong> tag before "XLSX file", 4: closing </strong> tag after "XLSX file" */
                                    __( 'You can export detailed reports whether as a %1$s PDF file %2$s or as an %3$s XLSX file. %4$s', 'quiz-maker' ),
                                    '<strong>',
                                    '</strong>',
                                    '<strong>',
                                    '</strong>'
                                );
                            $pro_content[] = '</p>';

                            $pro_content[] = '<p>';
                                $pro_content[] = __("So choose the variant that suits you and easily export the detailed report.", 'quiz-maker');
                            $pro_content[] = '</p>';

                            $pro_content[] = '<div>';
                                $pro_content[] = '<a href="https://quiz-plugin.com/docs/" target="_blank"> '. __("See Documentation", 'quiz-maker'). '</a>';
                            $pro_content[] = '</div>';
                        $pro_content[] = '</div>';
                        $pro_content[] = '<div class="pro-features-popup-button" data-link="https://ays-pro.com/wordpress/quiz-maker?utm_source=dashboard&utm_medium=quiz-free&utm_campaign=pro-popup-xlsx-export-'. esc_attr( AYS_QUIZ_UTM_VERSION ) .'">';
                            $pro_content[] = __("Pricing", 'quiz-maker');
                        $pro_content[] = '</div>';
                    $pro_content[] = '</div>';
                $pro_content[] = '</div>';
                $pro_content[] = '<div>';

                    $pro_content[] = '<a href="https://ays-pro.com/wordpress/quiz-maker?utm_source=dashboard&utm_medium=quiz-free&utm_campaign=pro-popup-pdf-export-'. esc_attr( AYS_QUIZ_UTM_VERSION ) .'" target="_blank" class="ays-pro-a" style="margin: 0 10px;">';
                        $pro_content[] = '<span type="button" class="disabled-button" title="'. __("This feature available only in pro version", 'quiz-maker') .'">'.__("PDF", 'quiz-maker').'</span>';
                    $pro_content[] = '</a>';

                    $pro_content[] = '<a href="https://ays-pro.com/wordpress/quiz-maker?utm_source=dashboard&utm_medium=quiz-free&utm_campaign=pro-popup-xlsx-export-'. esc_attr( AYS_QUIZ_UTM_VERSION ) .'" target="_blank" class="ays-pro-a">';
                        $pro_content[] = '<span type="button" class="disabled-button" title="'. __("This feature available only in pro version", 'quiz-maker') .'">'.__("XLSX", 'quiz-maker').'</span>';
                    $pro_content[] = '</a>';
                $pro_content[] = '</div>';
            $pro_content[] = '</div>';

            $pro_content = implode('', $pro_content);


            $admin_note_content = array();

            $admin_note_content[] = '<div class="only_pro only_pro_save_as_default" style="margin: 0 10px;" title="'. __("This feature available only in pro version", 'quiz-maker') .'">';
                $admin_note_content[] = '<div class="pro_features pro_features_popup">';
                    $admin_note_content[] = '<div class="pro-features-popup-conteiner">';
                        $admin_note_content[] = '<div class="pro-features-popup-title">';
                        $admin_note_content[] = __("Admin Note", 'quiz-maker');
                        $admin_note_content[] = '</div>';

                        $admin_note_content[] = '<div class="pro-features-popup-content" data-link="https://youtu.be/MdbEsayrPUc">';
                            $admin_note_content[] = '<p>';
                                $admin_note_content[] = sprintf(
                                    /* translators: 1: opening <strong> tag, 2: closing </strong> tag */
                                    __( 'The Admin Note feature of the WordPress quiz plugin is a great tool for %1$s customizing each detailed report. %2$s', 'quiz-maker' ),
                                    '<strong>',
                                    '</strong>'
                                );
                            $admin_note_content[] = '</p>';

                            $admin_note_content[] = '<p>';
                                $admin_note_content[] = sprintf(
                                    /* translators: 1: opening <strong> tag, 2: closing </strong> tag */
                                    __( 'This feature allows you %1$s to add additional information %2$s about each user on the detailed report page.', 'quiz-maker' ),
                                    '<strong>',
                                    '</strong>'
                                );
                            $admin_note_content[] = '</p>';

                            $admin_note_content[] = '<p>';
                                $admin_note_content[] = __("Just type your text in the Admin Note field and save it on detailed reports easily.", 'quiz-maker');
                            $admin_note_content[] = '</p>';

                            $admin_note_content[] = '<div>';
                                $admin_note_content[] = '<a href="https://quiz-plugin.com/docs/" target="_blank"> '. __("See Documentation", 'quiz-maker'). '</a>';
                            $admin_note_content[] = '</div>';
                        $admin_note_content[] = '</div>';

                        $admin_note_content[] = '<div class="pro-features-popup-button" data-link="https://ays-pro.com/wordpress/quiz-maker?utm_source=dashboard&utm_medium=quiz-free&utm_campaign=pro-popup-admin-note-'. esc_attr( AYS_QUIZ_UTM_VERSION ) .'">';
                            $admin_note_content[] = __("Pricing", 'quiz-maker');
                        $admin_note_content[] = '</div>';
                    $admin_note_content[] = '</div>';
                $admin_note_content[] = '</div>';
                $admin_note_content[] = '<div>';

                    $admin_note_content[] = '<div class="ays-quiz-click-for-admin-note">';
                        $admin_note_content[] = '<a href="https://ays-pro.com/wordpress/quiz-maker?utm_source=dashboard&utm_medium=quiz-free&utm_campaign=pro-popup-admin-note-'. esc_attr( AYS_QUIZ_UTM_VERSION ) .'" target="_blank" class="ays-pro-a">';
                            $admin_note_content[] = '<button class="button button-primary disabled-button" style="color:#ffffff !important; font-weight:normal;" title="'. __("This feature available only in pro version", 'quiz-maker') .'">';
                                $admin_note_content[] = __( 'Click For Admin Note', 'quiz-maker' );
                            $admin_note_content[] = '</button>';
                        $admin_note_content[] = '</a>';
                    $admin_note_content[] = '</div>';

                $admin_note_content[] = '</div>';
            $admin_note_content[] = '</div>';

            //$admin_note_content = implode('', $admin_note_content);
            $admin_note_content = '';

            
            $row = "<table id='ays-results-table'>";

            // $row .= '<tr class="ays_result_element">
            //             <td colspan="3">
            //                 <div class="ays-quiz-admin-note">
            //                     '. $admin_note_content .'
            //                 </div>
            //             </td>
            //         </tr>';
            
            $row .= '<tr class="ays_result_element">
                        <td colspan="3"><h1>' . __('User Information','quiz-maker') . '</h1></td>
                        <td style="text-align: right; display:flex; justify-content: flex-end;">
                            <span style="min-width: 70px;">'.__("Export to", 'quiz-maker').'</span>
                            '. $pro_content .'   
                        </td>
                    </tr>';       
            
            if ($ays_quiz_show_result_info_user_ip == 'on') {
                if ($user_ip != '') {
                    $row .= '<tr class="ays_result_element">
                                <td>' . __("User IP", 'quiz-maker') . '</td>
                                <td colspan="3">' . esc_html($from) . '</td>
                            </tr>';
                }
            }
            
            $user_name = $user_id === 0 ? __( "Guest", 'quiz-maker' ) : $user_display_name;

            if( $ays_quiz_show_result_info_user_id == 'on' ){
                if($user_id !== 0){
                    $row .= '<tr class="ays_result_element">
                            <td>' . __("User ID", 'quiz-maker') . '</td>
                            <td colspan="3">' . esc_html($user_id) . '</td>                    
                        </tr>';
                }
            }
            if ($ays_quiz_show_result_info_user == 'on') {
                $row .= '<tr class="ays_result_element">
                        <td>' . __("User", 'quiz-maker') . '</td>
                        <td colspan="3">' . esc_html($user_name) . '</td>
                    </tr>';
            }
            
            if(isset($results['user_email']) && $results['user_email'] !== '' && $ays_quiz_show_result_info_user_email == 'on'){
                $row .= "<tr class=\"ays_result_element\">
                        <td>".__('Email','quiz-maker')."</td>
                        <td colspan='3'>".stripslashes($results['user_email'])."</td>
                     </tr>";
            }
            if(isset($results['user_name']) && $results['user_name'] !== '' && $ays_quiz_show_result_info_user_name == 'on'){
                $row .= "<tr class=\"ays_result_element\">
                        <td>".__('Name','quiz-maker')."</td>
                        <td colspan='3'>".esc_html(stripslashes($results['user_name']))."</td>
                     </tr>";
            }
            if(isset($results['user_phone']) && $results['user_phone'] !== '' && $ays_quiz_show_result_info_user_phone == 'on'){
                $row .= "<tr class=\"ays_result_element\">
                        <td>".__('Phone','quiz-maker')."</td>
                        <td colspan='3'>".esc_html(stripslashes($results['user_phone']))."</td>
                     </tr>";
            }
            if ($user_attributes !== null) {

                foreach ($user_attributes as $name => $value) {
                    $attr_value = stripslashes($value) == '' ? '-' : esc_html(stripslashes($value));
                    $row .= '<tr class="ays_result_element">
                            <td>' . esc_html(stripslashes($name)) . '</td>
                            <td colspan="3">' . esc_html($attr_value) . '</td>
                        </tr>';
                }
            }

            $row .= apply_filters( 'ays_qm_track_users_contents', '', $id );
            
            $row .= '<tr class="ays_result_element">
                        <td colspan="4"><h1>' . __('Quiz Information','quiz-maker') . '</h1></td>
                    </tr>';
            if(isset($rate['score'])){
                $rate_html = '<tr style="vertical-align: top;" class="ays_result_element">
                    <td>'.__('Rate','quiz-maker').'</td>
                    <td>'. __("Rate Score", 'quiz-maker').":<br>" . absint($rate['score']) . '</td>
                    <td colspan="2" style="max-width: 200px;">'. __("Review", 'quiz-maker').":<br>" . sanitize_text_field( nl2br($rate['review']) ) . '</td>
                </tr>';
            }else{
                $rate_html = '<tr class="ays_result_element">
                    <td>'.__('Rate','quiz-maker').'</td>
                    <td colspan="3">' . sanitize_text_field( nl2br($rate['review']) ) . '</td>
                </tr>';
            }

            if ($ays_quiz_show_result_info_start_date == 'on') {
                $row .= '<tr class="ays_result_element">
                            <td>'.__('Start date','quiz-maker').'</td>
                            <td colspan="3">' . esc_html($start_date) . '</td>
                        </tr>';                        
            }

            if( $ays_quiz_show_result_info_duration == 'on' ) {
                $row .= '                      
                    <tr class="ays_result_element">
                        <td>'.__('Duration','quiz-maker').'</td>
                        <td colspan="3">' . esc_html($duration) . '</td>
                    </tr>';
            }

            if( $ays_quiz_show_result_info_score == 'on' ){
                $row .= '
                    <tr class="ays_result_element">
                        <td>'.__('Score','quiz-maker').'</td>
                        <td colspan="3">' . esc_html($score) . '%</td>
                    </tr>';
            }

            if( $ays_quiz_show_result_info_rate == 'on' ){
                $row .= $rate_html;
            }



                    
            if(! empty($options->correctness)){
                $row .= '<tr class="ays_result_element">
                            <td colspan="3"><h1>' . __('Questions','quiz-maker') . '</h1></td>
                            <td>
                                <div class="ays_result_toogle_block">
                                    <span class="ays-show-quest-toggle quest-toggle-all">'. __("All", 'quiz-maker') .'</span>
                                    <input type="checkbox" class="ays_toggle ays_toggle_slide" id="ays_show_questions_toggle" checked>
                                    <label for="ays_show_questions_toggle" class="ays_switch_toggle">Toggle</label>
                                    <span class="ays-show-quest-toggle quest-toggle-failed">'. __("Failed", 'quiz-maker') .'</span>
                                </div>
                            </td>
                        </tr>';                
                $index = 1;
                //$user_exp = array();
                //if( isset( $results['user_explanation'] ) && $results['user_explanation'] != '' || $results['user_explanation'] !== null){
                //    $user_exp = json_decode($results['user_explanation'], true);
                //}
                
                foreach ($options->correctness as $key => $option) {
                    if (strpos($key, 'question_id_') !== false) {
                        $question_id = absint(intval(explode('_', $key)[2]));
                        $question = $wpdb->get_row("SELECT * FROM {$questions_table} WHERE id={$question_id}", "ARRAY_A");
                        
                        if ( is_null( $question ) || empty( $question )  ) {
                            continue;
                        }

                        $question_type = isset($question['type']) && $question['type'] != '' ? sanitize_text_field( $question['type'] ) : 'radio';

                        $question_type_with_images_arr = array("radio", "checkbox", 'select', 'true_or_false');

                        $qoptions = isset($question['options']) && $question['options'] != '' ? json_decode($question['options'], true) : array();
                        $use_html = isset($qoptions['use_html']) && $qoptions['use_html'] == 'on' ? true : false;
                        $correct_answers = $this->get_correct_answers($question_id);

                        $correct_answer_images = "";
                        if( !empty($question_type) && in_array($question_type, $question_type_with_images_arr) ){
                            $correct_answer_images = $this->get_correct_answer_images($question_id);
                        }
                        $is_text_type = $this->question_is_text_type($question_id);
                        $text_type = $this->text_answer_is($question_id);
                        $not_multiple_text_types = array("number", "date");
                        if($text_type){
                            $user_answered = $this->get_user_text_answered($options->user_answered, $key);  
                            $user_answered_images = '';
                        }else{
                            $user_answered = $this->get_user_answered($options->user_answered, $key);
                            $user_answered_images = $this->get_user_answered_images($options->user_answered, $key);
                        }
                        $ans_point = $option;
                        $ans_point_class = 'success';
                        if(is_array($user_answered)){
                            $user_answered = $user_answered['message'];
                            $ans_point = '-';
                            $ans_point_class = 'error';
                        }
                        $tr_class = "ays_result_element";
                        //if(isset($user_exp[$question_id])){
                        //    $tr_class = "";
                        //}

                        $not_influance_check = isset($question['not_influence_to_score']) && $question['not_influence_to_score'] == 'on' ? false : true;
                        if (!$not_influance_check) {
                            $not_influance_check_td = ' colspan="2" ';
                        }else{
                            $not_influance_check_td = '';                            
                        }

                        $correct_row = $option == true ? 'tr_success' : '';

                        $question_image = isset( $question["question_image"] ) && $question["question_image"] != '' ? esc_url($question["question_image"]) : '';
                        $question_title = isset( $question["question"] ) && $question["question"] != '' ? $question["question"] : '';
                        if($calc_method == 'by_correctness'){
                            if ($option == true) {
                                $row .= '<tr class="'.$tr_class.' '.$correct_row.'">
                                            <td>'.__('Question','quiz-maker').' ' . $index . ' :<br/>';
                                if( $question_image != '' ){
                                    $row .= '<img class="ays-quiz-question-image-in-report" src="' . $question_image . '"><br/>';
                                }
                                $row .= (stripslashes($question["question"])) . 
                                    '</td>';
                                if($is_text_type && ! in_array($text_type, $not_multiple_text_types)){
                                    $c_answers = explode('%%%', $correct_answers);
                                    $c_answer = $c_answers[0];
                                    foreach($c_answers as $c_ans){
                                        if(strtolower(trim($user_answered)) == strtolower(trim($c_ans))){
                                            $c_answer = $c_ans;
                                            break;
                                        }
                                    }
                                    $row .='<td>'.__('Correct answer','quiz-maker').':<br/>';
                                    $row .= '<p class="success">' . esc_attr(stripslashes($c_answer)) . '</p>';
                                    $row .='</td>';
                                }else{
                                    if($text_type == 'date'){
                                        $correct_answers = date( 'm/d/Y', strtotime( $correct_answers ) );
                                    }
                                    $correct_answer_content = esc_attr( stripslashes( $correct_answers ) );
                                    if($use_html){
                                        $correct_answer_content = stripslashes( $correct_answers );
                                    }
                                    $row .='<td>'.__('Correct answer','quiz-maker').':<br/><p class="success">' . $correct_answer_content . '<br>' . $correct_answer_images . '</p></td>';
                                }
                                
                                if($text_type == 'date'){
                                    if(self::validateDate($user_answered, 'Y-m-d')){
                                        $user_answered = date( 'm/d/Y', strtotime( $user_answered ) );
                                    }
                                }
                                $user_answer_content = esc_attr( stripslashes( $user_answered ) );
                                if($use_html){
                                    $user_answer_content = stripslashes( $user_answered );
                                }
                                $row .='<td '.$not_influance_check_td.'>'.__('User answered','quiz-maker').':<br/><p class="success">' . $user_answer_content . $user_answered_images . '</p></td>';
                                if ($not_influance_check) {
                                    $row .='<td>
                                                <svg version="1.1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 130.2 130.2">
                                                    <circle class="path circle" fill="none" stroke="#73AF55" stroke-width="6" stroke-miterlimit="10" cx="65.1" cy="65.1" r="62.1"/>
                                                    <polyline class="path check" fill="none" stroke="#73AF55" stroke-width="6" stroke-linecap="round" stroke-miterlimit="10" points="100.2,40.2 51.5,88.8 29.8,67.5 "/>
                                                </svg>
                                                <p class="success">'.__('Succeed','quiz-maker').'!</p>
                                            </td>';
                                }
                                $row .= '</tr>';
                            } else {
                                $row .= '<tr class="'.$tr_class.'">
                                            <td>'.__('Question','quiz-maker').' ' . $index . ' :<br/>';
                                if( $question_image != '' ){
                                    $row .= '<img class="ays-quiz-question-image-in-report" src="' . $question_image . '"><br/>';
                                }
                                $row .= (stripslashes($question_title)) . 
                                    '</td>';
                                if($is_text_type && ! in_array($text_type, $not_multiple_text_types)){
                                    $c_answers = explode('%%%', $correct_answers);
                                    $row .= '<td>'.__('Correct answer','quiz-maker').':<br/>';
                                    $row .= '<p class="success">' . esc_attr(stripslashes($c_answers[0])) . '</p>';
                                    $row .= '</td>';
                                }else{
                                    if($text_type == 'date'){
                                        $correct_answers = date( 'm/d/Y', strtotime( $correct_answers ) );
                                    }
                                    $correct_answer_content = esc_attr( stripslashes( $correct_answers ) );
                                    if($use_html){
                                        $correct_answer_content = stripslashes( $correct_answers );
                                    }
                                    $row .= '<td>'.__('Correct answer','quiz-maker').':<br/><p class="success">' . $correct_answer_content . '<br>' . $correct_answer_images . '</p></td>';
                                }
                                                
                                if($text_type == 'date'){
                                    if(self::validateDate($user_answered, 'Y-m-d')){
                                        $user_answered = date( 'm/d/Y', strtotime( $user_answered ) );
                                    }
                                }
                                $user_answer_content = esc_attr( stripslashes( $user_answered ) );
                                if($use_html){
                                    $user_answer_content = stripslashes( $user_answered );
                                }
                                $row .= '<td '.$not_influance_check_td.'>'.__('User answered','quiz-maker').':<br/><p class="error">' . $user_answer_content . $user_answered_images . '</p></td>';
                                if ($not_influance_check) {
                                    $row .='<td>
                                        <svg version="1.1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 130.2 130.2">
                                            <circle class="path circle" fill="none" stroke="#D06079" stroke-width="6" stroke-miterlimit="10" cx="65.1" cy="65.1" r="62.1"/>
                                            <line class="path line" fill="none" stroke="#D06079" stroke-width="6" stroke-linecap="round" stroke-miterlimit="10" x1="34.4" y1="37.9" x2="95.8" y2="92.3"/>
                                            <line class="path line" fill="none" stroke="#D06079" stroke-width="6" stroke-linecap="round" stroke-miterlimit="10" x1="95.8" y1="38" x2="34.4" y2="92.2"/>
                                        </svg>
                                        <p class="error">'.__('Failed','quiz-maker').'!</p>
                                    </td>';
                                }
                                $row .= '</tr>';
                            }
                        }elseif($calc_method == 'by_points'){
                            $row .= '<tr class="'.$tr_class.'">
                                        <td>'.__('Question','quiz-maker').' ' . $index . ' :<br/>';
                            if( $question_image != '' ){
                                $row .= '<img class="ays-quiz-question-image-in-report" src="' . $question_image . '"><br/>';
                            }
                            $row .= (stripslashes($question["question"])) . 
                                    '</td>';
                            $row .= '<td>'.__('User answered','quiz-maker').':<br/><p class="'.$ans_point_class.'">' . esc_attr(stripslashes($user_answered)) . '</p></td>
                                    <td>'.__('Answer point','quiz-maker').':<br/><p class="'.$ans_point_class.'">' . esc_attr($ans_point) . '</p></td>
                                </tr>';
                            
                        }
                        $index++;
                        //if(isset($user_exp[$question_id])){
                        //    $row .= '<tr class="ays_result_element">
                        //        <td>'.__('User explanation for this question','quiz-maker').'</td>
                        //        <td colspan="3">'.$user_exp[$question_id].'</td>
                        //    </tr>';
                        //}
                    }
                }
            }
            $row .= "</table>";
            
            $sql = "UPDATE $results_table SET `read`=1 WHERE `id`=$id";
            $wpdb->get_var($sql);

            ob_end_clean();
            $ob_get_clean = ob_get_clean();
            echo json_encode(array(
                "status" => true,
                "rows" => $row
            ));
            wp_die();
        }
    }
    
    protected function ays_quiz_rate( $id ) {
        global $wpdb;
        if($id === '' || $id === null){
            $reason = __("No rate provided", 'quiz-maker');
            $output = array(
                "review" => $reason,
            );
        }else{
            $id = intval($id);
            $rate = $wpdb->get_row("SELECT * FROM {$wpdb->prefix}aysquiz_rates WHERE id={$id}", "ARRAY_A");
            $output = array();
            if($rate !== null){
                $review = $rate['review'];
                $reason = stripslashes($review);
                if($reason == ''){
                    $reason = __("No review provided", 'quiz-maker');
                }
                $score = $rate['score'];
                $output = array(
                    "score" => $score,
                    "review" => $reason,
                );
            }else{
                $reason = __("No rate provided", 'quiz-maker');
                $output = array(
                    "review" => $reason,
                );
            }
        }
        return $output;
    }

    public function get_correct_answers($id){
        global $wpdb;
        $answers_table = $wpdb->prefix . "aysquiz_answers";
        $correct_answers = $wpdb->get_results("SELECT answer FROM {$answers_table} WHERE correct=1 AND question_id={$id}");
        $text = "";
        foreach ($correct_answers as $key => $correct_answer) {
            if ($key == (count($correct_answers) - 1))
                $text .= $correct_answer->answer;
            else
                $text .= $correct_answer->answer . ',';
        }
        return $text;
    }

    public function get_correct_answer_images($id){
        global $wpdb;

        if(empty($id)){
            return '';
        }

        $id = intval($id);

        if (empty($id) || is_null($id)) {
            return '';
        }

        $answers_table = $wpdb->prefix . "aysquiz_answers";
        $correct_answers = $wpdb->get_results("SELECT image FROM {$answers_table} WHERE correct=1 AND question_id={$id}");
        $text = "";
        $text .= '<div class="question-answer-image-box">';
        foreach ($correct_answers as $key => $correct_answer) {
            if ($correct_answer->image){
                $text .= "<img src='". $correct_answer->image ."' alt='Answer image'>";
            }
        }
        $text .= "</div>";

        return $text;
    }

    public function get_user_answered($user_choice, $key){
        global $wpdb;
        $answers_table = $wpdb->prefix . "aysquiz_answers";

        $choices = '';
        if( !empty($user_choice->$key) ){
            if (is_array($user_choice->$key)) {
                $choices = array_map('intval', $user_choice->$key);
            } else {
                $choices = intval($user_choice->$key);
            }
        }
        
        if($choices == ''){
            return array(
                'message' => __( "The user has not answered this question.", 'quiz-maker' ),
                'status' => false
            );
        }
        $text = array();
        if (is_array($choices)) {
            foreach ($choices as $choice) {
                $choice = (isset($choice) && intval($choice) > 0) ? intval($choice) : null;
                if(empty($choice)){
                    continue;
                }
                $result = $wpdb->get_row($wpdb->prepare("SELECT answer FROM {$answers_table} WHERE id=%d ;", $choice ), 'ARRAY_A');
                $text[] = (isset($result['answer']) && $result['answer'] != "") ? $result['answer'] : '';
            }
            $text = implode(', ', $text);
        } else {
            $choice = (isset($choices) && intval($choices) > 0) ? intval($choices) : null;
            if(!empty($choice) && $choices > 0){
                $result = $wpdb->get_row($wpdb->prepare("SELECT answer FROM {$answers_table} WHERE id=%d ;", $choice ), 'ARRAY_A');
                $text = (isset($result['answer']) && $result['answer'] != "") ? $result['answer'] : '';
            }
        }
        return $text;
    }

    public function get_user_answered_images($user_choice, $key){
        global $wpdb;
        $answers_table = $wpdb->prefix . "aysquiz_answers";
        $choices = '';
        if( !empty($user_choice->$key) ){
            if (is_array($user_choice->$key)) {
                $choices = array_map('intval', $user_choice->$key);
            } else {
                $choices = intval($user_choice->$key);
            }
        }

        if($choices == ''){
            return '';
        }

        $text = array();
        if (is_array($choices)) {
            foreach ($choices as $choice) {
                $choice = (isset($choice) && intval($choice) > 0) ? intval($choice) : null;
                if(empty($choice)){
                    continue;
                }
                $result = $wpdb->get_row($wpdb->prepare("SELECT image FROM {$answers_table} WHERE id=%d ;", $choice ), 'ARRAY_A');
                if(isset($result['image']) && $result['image'] != ''){
                    $text[] = "<img src='". esc_url($result['image']) ."' alt='Answer image'>";
                }
            }
            $text = '<br><div class="question-answer-image-box">' . implode('<br>', $text) . '</div>';
        } else {
            $choice = (isset($choices) && intval($choices) > 0) ? intval($choices) : null;

            $text = "";
            if(!empty($choice) && $choice > 0){
                $result = $wpdb->get_row($wpdb->prepare("SELECT image FROM {$answers_table} WHERE id=%d ;", $choice ), 'ARRAY_A');
                if(isset($result['image']) && $result['image'] != ''){
                    $text = "<br><img src='". esc_url($result['image']) ."' alt='Answer image'>";
                }else{
                    $text = '';
                }
            }
        }
        return $text;
    }

    public function get_user_text_answered($user_choice, $key){
        
        if($user_choice->$key == ""){
            $choices = array(
                'message' => __( "The user has not answered this question.", 'quiz-maker' ),
                'status' => false
            );
        }else{
            $choices = trim($user_choice->$key);
        }
        
        return $choices;
    }
    
    public function question_is_text_type($question_id){
        global $wpdb;
        $questions_table = $wpdb->prefix . "aysquiz_questions";
        $question_id = absint(intval($question_id));
        $text_types = array('text', 'short_text', 'number', 'date');
        $get_answers = $wpdb->get_var("SELECT type FROM {$questions_table} WHERE id={$question_id}");
        if (in_array($get_answers, $text_types)) {
            return $get_answers;
        }
        return false;
    }

    public function text_answer_is($question_id){
        global $wpdb;
        $questions_table = $wpdb->prefix . "aysquiz_questions";
        $question_id = absint(intval($question_id));

        $text_types = array('text', 'short_text', 'number', 'date');
        $get_answers = $wpdb->get_var("SELECT type FROM {$questions_table} WHERE id={$question_id}");

        if (in_array($get_answers, $text_types)) {
            return $get_answers;
        }
        return false;
    }

    /**
     * Changes previos version db structure
     */
    public function ays_change_db_questions(){
        global $wpdb;
        $quiz_table = $wpdb->prefix . 'aysquiz_quizes';

        $sql = "SELECT id, question_ids FROM {$quiz_table}";

        $rows = $wpdb->get_results($sql, 'ARRAY_A');


        foreach ($rows as $key => $row) {
            if (strpos($row['question_ids'], '***') !== false) {
                $question_ids = implode(',', explode('***', $row['question_ids']));
                $wpdb->update(
                    $quiz_table,
                    array('question_ids' => $question_ids),
                    array('id' => $row['id']),
                    array('%s'),
                    array('%d')
                );
            }
        }
    }
    
    public function get_questions_categories(){
        global $wpdb;
        $categories_table = $wpdb->prefix . "aysquiz_categories";
        $get_cats = $wpdb->get_results("SELECT * FROM {$categories_table}", ARRAY_A);
        return $get_cats;
    }
    
    public function get_published_questions_by($key, $value) {
        global $wpdb;

        $sql = "SELECT * FROM {$wpdb->prefix}aysquiz_questions WHERE {$key} = {$value};";

        $results = $wpdb->get_row( $sql, 'ARRAY_A' );

        return $results;

    }

    public static function ays_get_quiz_options(){
        global $wpdb;
        $table_name = $wpdb->prefix . 'aysquiz_quizes';
        $res = $wpdb->get_results("SELECT id, title FROM $table_name");
        $aysGlobal_array = array();

        foreach ($res as $ays_res_options) {
            $aysStatic_array = array();
            $aysStatic_array[] = $ays_res_options->id;
            $aysStatic_array[] = $ays_res_options->title;
            $aysGlobal_array[] = $aysStatic_array;
        }
        return $aysGlobal_array;
    }

    function ays_quiz_register_tinymce_plugin($plugin_array){
        if( isset( $_GET['page'] ) && 0 !== strpos(sanitize_key($_GET['page']), $this->plugin_name)){
            $this->settings_obj = new Quiz_Maker_Settings_Actions($this->plugin_name);

            // General Settings | options
            $gen_options = ($this->settings_obj->ays_get_setting('options') === false) ? array() : json_decode( stripcslashes($this->settings_obj->ays_get_setting('options') ), true);

            // Show quiz button to Admins only
            $gen_options['quiz_show_quiz_button_to_admin_only'] = isset($gen_options['quiz_show_quiz_button_to_admin_only']) ? sanitize_text_field( $gen_options['quiz_show_quiz_button_to_admin_only'] ) : 'off';
            $quiz_show_quiz_button_to_admin_only = (isset($gen_options['quiz_show_quiz_button_to_admin_only']) && sanitize_text_field( $gen_options['quiz_show_quiz_button_to_admin_only'] ) == "on") ? true : false;

            if ( $quiz_show_quiz_button_to_admin_only ) {

                if( current_user_can( 'manage_options' ) ){
                    $plugin_array['ays_quiz_button_mce'] = AYS_QUIZ_BASE_URL . 'ays_quiz_shortcode.js';
                }

            } else {
                $plugin_array['ays_quiz_button_mce'] = AYS_QUIZ_BASE_URL . 'ays_quiz_shortcode.js';
            }
        }

        return $plugin_array;
    }

    function ays_quiz_add_tinymce_button($buttons){
        if( isset( $_GET['page'] ) && 0 !== strpos(sanitize_key($_GET['page']), $this->plugin_name)){
            $buttons[] = "ays_quiz_button_mce";
        }
        return $buttons;
    }

    function gen_ays_quiz_shortcode_callback(){

        if(!is_user_logged_in()){
            die();
        }

        if( isset( $_GET['page'] ) && 0 !== strpos(sanitize_key($_GET['page']), $this->plugin_name)){
            die();
        }

        $shortcode_data = $this->ays_get_quiz_options();
        ob_end_clean();
        $ob_get_clean = ob_get_clean();
        ?>
        <html xmlns="http://www.w3.org/1999/xhtml">
        <head>
            <title><?php esc_html_e('Quiz Maker', 'quiz-maker'); ?></title>
            <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
            <script language="javascript" type="text/javascript"
                    src="<?php echo esc_url( site_url() ); ?>/wp-includes/js/tinymce/tiny_mce_popup.js"></script>
            <script language="javascript" type="text/javascript"
                    src="<?php echo esc_url( site_url() ); ?>/wp-includes/js/tinymce/utils/mctabs.js"></script>
            <script language="javascript" type="text/javascript"
                    src="<?php echo esc_url( site_url() ); ?>/wp-includes/js/tinymce/utils/form_utils.js"></script>

            <?php
            wp_print_scripts('jquery');
            ?>
            <base target="_self">
        </head>
        <body id="link" onLoad="tinyMCEPopup.executeOnLoad('init();');document.body.style.display='';" dir="ltr"
              class="forceColors">
        <div class="select-sb">

            <table align="center">
                <tr>
                    <td><label for="ays_quiz">Quiz Maker</label></td>
                    <td>
                     <span>
                               <select id="ays_quiz" style="padding: 2px; height: 25px; font-size: 16px;width:100%;">
                           <option>--Select Quiz--</option>
                                   <?php
                                   // echo "<pre>";
                                   // print_r($shortcode_data);
                                   // echo "</pre>";
                                   ?>
                                   <?php foreach ($shortcode_data as $index => $data)
                                       echo '<option id="' . esc_attr($data[0]) . '" value="' . esc_attr($data[0]) . '"  class="ays_quiz_options">' . esc_attr($data[1]) . '</option>';
                                   ?>
                               </select>
                           </span>
                    </td>
                </tr>
            </table>
        </div>
        <div class="mceActionPanel">
            <input type="submit" id="insert" name="insert" value="Insert" onClick="quiz_insert_shortcode();"/>
        </div>
        <script>

        </script>
        <script type="text/javascript">
            function quiz_insert_shortcode() {
                var tagtext = '[ays_quiz id="' + document.getElementById('ays_quiz')[document.getElementById('ays_quiz').selectedIndex].id + '"]';
                window.tinyMCE.execCommand('mceInsertContent', false, tagtext);
                tinyMCEPopup.close();
            }
        </script>
        </body>
        </html>
        <?php
        die();
    }
    
    public function vc_before_init_actions() {
        require_once( AYS_QUIZ_DIR.'pb_templates/quiz_maker_wpbvc.php' );
    }

    public function quiz_maker_el_widgets_registered() {
        wp_enqueue_style($this->plugin_name . '-admin', plugin_dir_url(__FILE__) . 'css/admin.css', array(), $this->version, 'all');
        // We check if the Elementor plugin has been installed / activated.
        if ( defined( 'ELEMENTOR_PATH' ) && class_exists( 'Elementor\Widget_Base' ) ) {
            // get our own widgets up and running:
            // copied from widgets-manager.php
            if ( class_exists( 'Elementor\Plugin' ) ) {
                if ( is_callable( 'Elementor\Plugin', 'instance' ) ) {
                    $elementor = Elementor\Plugin::instance();
                    if ( isset( $elementor->widgets_manager ) ) {
                        if ( defined( 'ELEMENTOR_VERSION' ) && version_compare( ELEMENTOR_VERSION, '3.5.0', '>=' ) ) {
                            if ( method_exists( $elementor->widgets_manager, 'register' ) ) {
                                $widget_file   = 'plugins/elementor/quiz_maker_elementor.php';
                                $template_file = locate_template( $widget_file );
                                if ( !$template_file || !is_readable( $template_file ) ) {
                                    $template_file = AYS_QUIZ_DIR.'pb_templates/quiz_maker_elementor.php';
                                }
                                if ( $template_file && is_readable( $template_file ) ) {
                                    require_once $template_file;
                                    Elementor\Plugin::instance()->widgets_manager->register( new Elementor\Widget_Quiz_Maker_Elementor() );
                                }
                            }
                        } else { 
                            if ( method_exists( $elementor->widgets_manager, 'register_widget_type' ) ) {
                                $widget_file   = 'plugins/elementor/quiz_maker_elementor.php';
                                $template_file = locate_template( $widget_file );
                                if ( !$template_file || !is_readable( $template_file ) ) {
                                    $template_file = AYS_QUIZ_DIR.'pb_templates/quiz_maker_elementor.php';
                                }
                                if ( $template_file && is_readable( $template_file ) ) {
                                    require_once $template_file;
                                    Elementor\Plugin::instance()->widgets_manager->register_widget_type( new Elementor\Widget_Quiz_Maker_Elementor() );
                                }
                            }
                        }
                    }
                }
            }
        }
    }
    
    public function deactivate_plugin_option(){

        // Run a security check.
        check_ajax_referer( 'quiz-maker-ajax-deactivate-plugin-nonce', sanitize_key( $_REQUEST['_ajax_nonce'] ) );

        // Check for permissions.
        if ( ! current_user_can( 'manage_options' ) ) {
            ob_end_clean();
            $ob_get_clean = ob_get_clean();
            echo json_encode(array(
                'option' => ''
            ));
            wp_die();
        }

        if( is_user_logged_in() ) {
            $request_value = esc_sql( sanitize_text_field( $_REQUEST['upgrade_plugin'] ) );
            $upgrade_option = get_option('ays_quiz_maker_upgrade_plugin','');
            if($upgrade_option === ''){
                add_option('ays_quiz_maker_upgrade_plugin',$request_value);
            }else{
                update_option('ays_quiz_maker_upgrade_plugin',$request_value);
            }
            ob_end_clean();
            $ob_get_clean = ob_get_clean();
            echo json_encode(array(
                'option' => get_option('ays_quiz_maker_upgrade_plugin', '')
            ));
            wp_die();
        } else {
            ob_end_clean();
            $ob_get_clean = ob_get_clean();
            echo json_encode(array(
                'option' => ''
            ));
            wp_die();
        }

    }
    
    public static function ays_restriction_string($type, $x, $length){
        $output = "";
        switch($type){
            case "char":                
                if(strlen($x)<=$length){
                    $output = $x;
                } else {
                    $output = substr($x,0,$length) . '...';
                }
                break;
            case "word":
                $res = explode(" ", $x);
                if(count($res)<=$length){
                    $output = implode(" ",$res);
                } else {
                    $res = array_slice($res,0,$length);
                    $output = implode(" ",$res) . '...';
                }
            break;
        }
        return $output;
    }
    
    public static function validateDate($date, $format = 'Y-m-d H:i:s'){
        $d = DateTime::createFromFormat($format, $date);
        return $d && $d->format($format) == $date;
    }
    
    // Title change function in dashboard
    public function change_dashboard_title( $admin_title ) {
        
        global $current_screen;
        global $wpdb;
        
        if(strpos($current_screen->id, $this->plugin_name) === false){
            return $admin_title;
        }
        $action = (isset($_GET['action'])) ? sanitize_text_field($_GET['action']) : '';        
        $quiz_id = (isset($_GET['quiz'])) ? absint(intval($_GET['quiz'])) : null;
        $question_id = (isset($_GET['question'])) ? absint(intval($_GET['question'])) : null;
        $question_cat_id = (isset($_GET['question_category'])) ? absint(intval($_GET['question_category'])) : null;
        $quiz_cat_id = (isset($_GET['quiz_category'])) ? absint(intval($_GET['quiz_category'])) : null;
        
        if($quiz_id !== null){
            $id = $quiz_id;
        }elseif($question_id !== null){
            $id = $question_id;
        }elseif($question_cat_id !== null){
            $id = $question_cat_id;
        }elseif($quiz_cat_id !== null){
            $id = $quiz_cat_id;
        }else{
            $id = null;
        }
        
        $current = explode($this->plugin_name, $current_screen->id);
        $current = trim($current[count($current)-1], "-");
        $sql = '';
        switch($current){
            case "":
                $page = __("Quiz", 'quiz-maker');
                if($id !== null){
                    $sql = "SELECT * FROM ".$wpdb->prefix."aysquiz_quizes WHERE id=".$id;
                }
                break;
            case "questions":
                $page = __("Question", 'quiz-maker');;
                if($id !== null){
                    $sql = "SELECT * FROM ".$wpdb->prefix."aysquiz_questions WHERE id=".$id;
                }
                break;
            case "quiz-categories":
                $page = __("Category", 'quiz-maker');;
                if($id !== null){
                    $sql = "SELECT * FROM ".$wpdb->prefix."aysquiz_quizcategories WHERE id=".$id;
                }
                break;
            case "question-categories":
                $page = __("Category", 'quiz-maker');;
                if($id !== null){
                    $sql = "SELECT * FROM ".$wpdb->prefix."aysquiz_categories WHERE id=".$id;
                }
                break;
            default:
                $page = '';
                $sql = '';
                break;
        }
        $results = null;
        if($sql != ""){
            $results = $wpdb->get_row($sql, "ARRAY_A");
        }
        $change_title = null;
        switch($action){
            case "add":
                $change_title = __("Add New", 'quiz-maker') ." ‹ ".$page;
                break;
            case "edit":
                if($results !== null){
                    $title = "";
                    if($current == "questions"){
                        if(isset($results['question']) && strlen($results['question']) != 0){
                            $title = strip_tags(stripslashes($results['question']));
                        }elseif ((isset($results['question_image']) && $results['question_image'] !='')){
                            $title = 'Image question';
                        }
                    }else{                        
                        $title = stripslashes($results['title']);
                    }
                    $title = strip_tags($title);
                    $change_title = $this->ays_restriction_string("word", $title, 5) ." ‹ ". __("Edit", 'quiz-maker') . " ".$page;
                }
                break;
            default:
                $change_title = $admin_title;
                break;
        }
        if($change_title === null){
            $change_title = $admin_title;
        }
        
        return $change_title;

    }    

    public static function get_listtables_title_length( $listtable_name ) {
        global $wpdb;

        // General Settings | options
        $settings_table = $wpdb->prefix . "aysquiz_settings";
        $sql = "SELECT meta_value FROM ".$settings_table." WHERE meta_key = 'options'";
        $result = $wpdb->get_var($sql);
        $options = ($result == "") ? array() : json_decode(stripcslashes($result), true);

        $listtable_title_length = 5;
        if(! empty($options) ){
            switch ( $listtable_name ) {
                case 'questions':
                    $listtable_title_length = (isset($options['question_title_length']) && intval($options['question_title_length']) != 0) ? absint(intval($options['question_title_length'])) : 5;
                    break;
                case 'quizzes':
                    $listtable_title_length = (isset($options['quizzes_title_length']) && intval($options['quizzes_title_length']) != 0) ? absint(intval($options['quizzes_title_length'])) : 5;
                    break;
                case 'results':
                    $listtable_title_length = (isset($options['results_title_length']) && intval($options['results_title_length']) != 0) ? absint(intval($options['results_title_length'])) : 5;
                    break;   
                case 'question_categories':
                    $listtable_title_length = (isset($options['question_categories_title_length']) && intval($options['question_categories_title_length']) != 0) ? absint(sanitize_text_field($options['question_categories_title_length'])) : 5;
                    break;
                case 'quiz_categories':
                    $listtable_title_length = (isset($options['quiz_categories_title_length']) && intval($options['quiz_categories_title_length']) != 0) ? absint(sanitize_text_field($options['quiz_categories_title_length'])) : 5;
                    break;
                case 'quiz_reviews':
                    $listtable_title_length = (isset($options['quiz_reviews_title_length']) && intval($options['quiz_reviews_title_length']) != 0) ? absint(sanitize_text_field($options['quiz_reviews_title_length'])) : 5;
                    break;        
                default:
                    $listtable_title_length = 5;
                    break;
            }
            return $listtable_title_length;
        }
        return $listtable_title_length;
    }
    
    public function quiz_maker_add_dashboard_widgets() {
        if(current_user_can('manage_options')){ // Administrator
            wp_add_dashboard_widget( 
                'quiz-maker', 
                'Quiz Maker Status', 
                array( $this, 'quiz_maker_dashboard_widget' )
            );

            // Globalize the metaboxes array, this holds all the widgets for wp-admin
            global $wp_meta_boxes;

            // Get the regular dashboard widgets array 
            // (which has our new widget already but at the end)
            $normal_dashboard = $wp_meta_boxes['dashboard']['normal']['core'];

            // Backup and delete our new dashboard widget from the end of the array
            $example_widget_backup = array( 
                'quiz-maker' => $normal_dashboard['quiz-maker'] 
            );
            unset( $normal_dashboard['example_dashboard_widget'] );

            // Merge the two arrays together so our widget is at the beginning
            $sorted_dashboard = array_merge( $example_widget_backup, $normal_dashboard );

            // Save the sorted array back into the original metaboxes 
            $wp_meta_boxes['dashboard']['normal']['core'] = $sorted_dashboard;
        }
    } 

    /**
     * Create the function to output the contents of our Dashboard Widget.
     */
    public function quiz_maker_dashboard_widget() {

        if (empty($this->questions_obj)) {
            $this->questions_obj = new Questions_List_Table($this->plugin_name);
        }

        if (empty($this->results_obj)) {
            $this->results_obj = new Results_List_Table($this->plugin_name);
        }

        global $wpdb;
        $questions_count = $this->questions_obj->record_count();
        $quizzes_count = $this->questions_obj->record_count();
        $results_count = $this->results_obj->unread_records_count();
        
        $questions_label = intval($questions_count) == 1 ? __("question", 'quiz-maker') : __("questions", 'quiz-maker');
        $quizzes_label = intval($quizzes_count) == 1 ? __("quiz", 'quiz-maker') : __("quizzes", 'quiz-maker');
        $results_label = intval($results_count) == 1 ? __("new result", 'quiz-maker') : __("new results", 'quiz-maker');

        $actual_reports_count = self::get_actual_reports_count();
        $reports_label = intval($actual_reports_count) == 1 ? __( "question reports", 'quiz-maker' ) : __( "question reports", 'quiz-maker' );
        
        // Display whatever it is you want to show.
        ?>
        <ul class="ays_quiz_maker_dashboard_widget">
            <li class="ays_dashboard_widget_item">
                <a href="<?php echo esc_url("admin.php?page=".$this->plugin_name); ?>">
                    <img src="<?php echo esc_url(AYS_QUIZ_ADMIN_URL . "/images/icons/icon-128x128.png"); ?>" alt="Quizzes">
                    <span><?php echo esc_html($quizzes_count); ?></span>
                    <span><?php echo esc_html($quizzes_label); ?></span>
                </a>
            </li>
            <li class="ays_dashboard_widget_item">
                <a href="<?php echo esc_url("admin.php?page=".$this->plugin_name."-questions"); ?>">
                    <img src="<?php echo AYS_QUIZ_ADMIN_URL."/images/icons/message-circle-question.svg"; ?>" alt="Questions">
                    <span><?php echo esc_html($questions_count); ?></span>
                    <span><?php echo esc_html($questions_label); ?></span>
                </a>
            </li>
            <li class="ays_dashboard_widget_item">
                <a href="<?php echo esc_url("admin.php?page=".$this->plugin_name."-results"); ?>">
                    <img src="<?php echo AYS_QUIZ_ADMIN_URL."/images/icons/users-icon.svg"; ?>" alt="Results">
                    <span><?php echo esc_html($results_count); ?></span>
                    <span><?php echo esc_html($results_label); ?></span>
                </a>
            </li>
            <li class="ays_dashboard_widget_item">
                <a href="<?php echo "admin.php?page=".$this->plugin_name."-question-reports" ?>">
                    <img src="<?php echo AYS_QUIZ_ADMIN_URL."/images/icons/triangle-alert.svg"; ?>" alt="Reports">
                    <span><?php echo esc_html($actual_reports_count); ?></span>
                    <span><?php echo esc_html($reports_label); ?></span>
                </a>
            </li>
        </ul>
        <div style="padding:10px;font-size:14px;border-top:1px solid #ccc;">
            <?php
                echo sprintf(
                    /* translators: %s: plugin version number */
                    esc_html__( 'Works version %s of ', 'quiz-maker' ),
                    esc_html( AYS_QUIZ_VERSION )
                );
            ?>
            <a href="<?php echo esc_url("admin.php?page=".$this->plugin_name); ?>">Quiz Maker</a>
        </div>
    <?php
    }
    
    public static function ays_query_string($remove_items){
        $query_string = isset($_SERVER['QUERY_STRING']) && $_SERVER['QUERY_STRING'] != "" ? sanitize_text_field($_SERVER['QUERY_STRING']) : "";
        $query_items = explode( "&", $query_string );
        foreach($query_items as $key => $value){
            $item = explode("=", $value);
            foreach($remove_items as $k => $i){
                if(in_array($i, $item)){
                    unset($query_items[$key]);
                }
            }
        }
        return implode( "&", $query_items );
    }

    public function get_question_categories(){
        global $wpdb;

        $sql = "SELECT * FROM {$wpdb->prefix}aysquiz_categories";

        $result = $wpdb->get_results($sql, 'ARRAY_A');

        return $result;
    }
    
    public function quiz_maker_admin_footer($a){
        if(isset($_REQUEST['page'])){
            if(false !== strpos( sanitize_text_field( $_REQUEST['page'] ), $this->plugin_name)){
                ?>
                <p class="ays-quiz-footer-review-box" style="font-size:13px;text-align:center;font-style:italic;">
                    <span style="margin-left:0px;margin-right:10px;" class="ays_heart_beat"><i class="ays_fa ays_fa_heart_o animated"></i></span>
                    <span><?php echo esc_html__( "If you love our plugin, please do big favor and rate us on", 'quiz-maker'); ?></span> 
                    <a target="_blank" href='https://wordpress.org/support/plugin/quiz-maker/reviews/?rate=5#new-post'>WordPress.org</a>
                    <span class="ays_heart_beat"><i class="ays_fa ays_fa_heart_o animated"></i></span>
                </p>
            <?php
            }
        }
    }
    
    public static function get_user_country_by_ip( $ip ){
        
        $url = AYS_QUIZ_ADMIN_URL . "/quiz-maker-countries-names.json";
        $response = wp_remote_get( $url );        
        $body = wp_remote_retrieve_body( $response );
        $countries = json_decode( $body );
        
        $headers = array(
            "headers" => array(            
                "Content-Type"  => "application/json",
                "cache-control" => "no-cache",
            )
        );

        $url = "https://ipinfo.io/". $ip ."/json";
        $response = wp_remote_get($url, $headers);
        $body     = wp_remote_retrieve_body( $response );
        
        if( empty( $body ) ){
            $from = $ip;
        }else{
            $body = json_decode( $body );
            
            $from = array();
            
            if( !empty( $body->city ) ){
                $from[] = $body->city;
            }
            if( !empty( $body->region ) ){
                $from[] = $body->region;
            }
            if( !empty( $body->country ) ){
                $country = $body->country;
                $from[] = $countries->$country;
            }
            
            $from[] = $ip;
            
            $from = implode( ', ', $from );
        }
        
        return $from;
    }

    public static function ays_quiz_is_exists_needle_tag( $str, $needle ) {

        $exists_flag = false;

        if ( isset( $str ) && ! is_null( $str ) && $str != '' ) {

            if ( isset( $needle ) && ! is_null( $needle ) && $needle != '' ) {

                $is_exists_needle = strpos( $str, $needle );

                if ( $is_exists_needle !== false ) {
                    $exists_flag = true;
                }
            }
        }

        return $exists_flag;
    }

    /*
    ==========================================
        Sale Banner | Start
    ==========================================
    */

    public function ays_quiz_sale_baner(){

        // if(isset($_POST['ays_quiz_sale_btn_for_two_months'])){
            // update_option('ays_quiz_sale_btn_for_two_months', 1);
            // update_option('ays_quiz_sale_date', current_time( 'mysql' ));
        // }

        $ays_quiz_sale_date = get_option('ays_quiz_sale_date');
        // $ays_quiz_sale_two_months = get_option('ays_quiz_sale_btn_for_two_months');

        $val = 60*60*24*5;
        // if($ays_quiz_sale_two_months == 1){
        //     $val = 60*60*24*61;
        // }

        $current_date = current_time( 'mysql' );
        $date_diff = strtotime($current_date) -  intval(strtotime($ays_quiz_sale_date));

        $days_diff = $date_diff / $val;

        if(intval($days_diff) > 0 ){
            update_option('ays_quiz_sale_btn', 0); 
            // update_option('ays_quiz_sale_btn_for_two_months', 0);
        }

        $ays_quiz_ishmar = intval(get_option('ays_quiz_sale_btn'));
        // $ays_quiz_ishmar += intval(get_option('ays_quiz_sale_btn_for_two_months'));
        if($ays_quiz_ishmar == 0 ){
            if (isset($_GET['page']) && strpos($_GET['page'], AYS_QUIZ_NAME) !== false) {
                if ( sanitize_text_field($_GET['page']) == 'quiz-maker-settings' ) {
                    // $this->ays_quiz_chart_bulider_message($ays_quiz_ishmar);
                    $this->ays_quiz_fox_lms_integration_message($ays_quiz_ishmar);
                } else {
                    if( $this->get_max_id('quizes') > 1 ){
                        // $this->ays_quiz_new_mega_bundle_message($ays_quiz_ishmar);
                        // $this->ays_quiz_black_friday_message($ays_quiz_ishmar);
                        // $this->ays_quiz_new_halloween_bundle_message_2025($ays_quiz_ishmar);
                        $this->ays_quiz_christmas_banner_message_2025($ays_quiz_ishmar);
                    }
                }
            }
        }
    }

    public function ays_quiz_dismiss_button(){

        $data = array(
            'status' => false,
        );

        if (isset($_REQUEST['action']) && $_REQUEST['action'] == 'ays_quiz_dismiss_button') { 
            if( (isset( $_REQUEST['_ajax_nonce'] ) && wp_verify_nonce( $_REQUEST['_ajax_nonce'], AYS_QUIZ_NAME . '-sale-banner' )) && current_user_can( 'manage_options' )){
                update_option('ays_quiz_sale_btn', 1); 
                update_option('ays_quiz_sale_date', current_time( 'mysql' ));
                $data['status'] = true;
            }
        }

        ob_end_clean();
        $ob_get_clean = ob_get_clean();
        echo json_encode($data);
        wp_die();

    }

    // Mega Bundle
    public function ays_quiz_sale_message($ishmar){
        if($ishmar == 0 ){
            $content = array();

            $content[] = '<div id="ays-quiz-dicount-month-main" class="notice notice-success is-dismissible ays_quiz_dicount_info">';
                $content[] = '<div id="ays-quiz-dicount-month" class="ays_quiz_dicount_month">';
                    $content[] = '<a href="https://ays-pro.com/mega-bundle" target="_blank" class="ays-quiz-sale-banner-link"><img src="' . AYS_QUIZ_ADMIN_URL . '/images/mega_bundle_logo_box.png"></a>';

                    $content[] = '<div class="ays-quiz-dicount-wrap-box">';

                        $content[] = '<strong style="font-weight: bold;">';
                            $content[] = __( "Limited Time <span style='color:#E85011;'>50%</span> SALE on <br><span><a href='https://ays-pro.com/mega-bundle' target='_blank' style='color:#E85011; text-decoration: underline;'>Mega Bundle</a></span> (Quiz + Survey + Poll)!", 'quiz-maker' );
                        $content[] = '</strong>';

                        $content[] = '<br>';

                        $content[] = '<strong>';
                                $content[] = "Hurry up! <a href='https://ays-pro.com/mega-bundle' target='_blank'>Check it out!</a>";
                        $content[] = '</strong>';

                        $content[] = '<div style="position: absolute;right: 10px;bottom: 1px;" class="ays-quiz-dismiss-buttons-container-for-form">';

                            $content[] = '<form action="" method="POST">';
                                $content[] = '<div id="ays-quiz-dismiss-buttons-content">';
                                    $content[] = '<button class="btn btn-link ays-button" name="ays_quiz_sale_btn" style="height: 32px; margin-left: 0;padding-left: 0">Dismiss ad</button>';
                                $content[] = '</div>';
                            $content[] = '</form>';
                            
                        $content[] = '</div>';

                    $content[] = '</div>';

                    $content[] = '<div class="ays-quiz-dicount-wrap-box">';

                        $content[] = '<div id="ays-quiz-maker-countdown-main-container">';
                            $content[] = '<div class="ays-quiz-maker-countdown-container">';

                                $content[] = '<div id="ays-quiz-countdown">';
                                    $content[] = '<ul>';
                                        $content[] = '<li><span id="ays-quiz-countdown-days"></span>days</li>';
                                        $content[] = '<li><span id="ays-quiz-countdown-hours"></span>Hours</li>';
                                        $content[] = '<li><span id="ays-quiz-countdown-minutes"></span>Minutes</li>';
                                        $content[] = '<li><span id="ays-quiz-countdown-seconds"></span>Seconds</li>';
                                    $content[] = '</ul>';
                                $content[] = '</div>';

                                $content[] = '<div id="ays-quiz-countdown-content" class="emoji">';
                                    $content[] = '<span>🚀</span>';
                                    $content[] = '<span>⌛</span>';
                                    $content[] = '<span>🔥</span>';
                                    $content[] = '<span>💣</span>';
                                $content[] = '</div>';

                            $content[] = '</div>';
                        $content[] = '</div>';
                            
                    $content[] = '</div>';

                    $content[] = '<a href="https://ays-pro.com/mega-bundle" class="button button-primary ays-button" id="ays-button-top-buy-now" target="_blank" style="height: 32px; display: flex; align-items: center; font-weight: 500; " >' . __( 'Buy Now !', 'quiz-maker' ) . '</a>';
                $content[] = '</div>';
            $content[] = '</div>';

            $content = implode( '', $content );
            echo $content;
        }
    }

    public function ays_quiz_sale_message2($ishmar){
        if($ishmar == 0 ){
            $content = array();

            $content[] = '<div id="ays-quiz-dicount-month-main" class="notice notice-success is-dismissible ays_quiz_dicount_info">';
                $content[] = '<div id="ays-quiz-dicount-month" class="ays_quiz_dicount_month">';
                    $content[] = '<a href="https://ays-pro.com/wordpress/quiz-maker" target="_blank" class="ays-quiz-sale-banner-link"><img src="' . AYS_QUIZ_ADMIN_URL . '/images/icons/quiz-maker-logo.png"></a>';

                    $content[] = '<div class="ays-quiz-dicount-wrap-box">';

                        $content[] = '<strong style="font-weight: bold;">';
                            $content[] = __( "Limited Time <span class='ays-quiz-dicount-wrap-color'>20%</span> SALE on <br><span><a href='https://ays-pro.com/wordpress/quiz-maker' target='_blank' class='ays-quiz-dicount-wrap-color ays-quiz-dicount-wrap-text-decoration' style='display:block;'>Quiz Maker Premium Versions</a></span>", 'quiz-maker' );
                        $content[] = '</strong>';

                        // $content[] = '<br>';

                        $content[] = '<strong>';
                                $content[] = "Hurry up! <a href='https://ays-pro.com/wordpress/quiz-maker' target='_blank'>Check it out!</a>";
                        $content[] = '</strong>';
                            
                    $content[] = '</div>';

                    $content[] = '<div class="ays-quiz-dicount-wrap-box">';

                        $content[] = '<div id="ays-quiz-maker-countdown-main-container">';
                            $content[] = '<div class="ays-quiz-maker-countdown-container">';

                                $content[] = '<div id="ays-quiz-countdown">';
                                    $content[] = '<ul>';
                                        $content[] = '<li><span id="ays-quiz-countdown-days"></span>days</li>';
                                        $content[] = '<li><span id="ays-quiz-countdown-hours"></span>Hours</li>';
                                        $content[] = '<li><span id="ays-quiz-countdown-minutes"></span>Minutes</li>';
                                        $content[] = '<li><span id="ays-quiz-countdown-seconds"></span>Seconds</li>';
                                    $content[] = '</ul>';
                                $content[] = '</div>';

                                $content[] = '<div id="ays-quiz-countdown-content" class="emoji">';
                                    $content[] = '<span>🚀</span>';
                                    $content[] = '<span>⌛</span>';
                                    $content[] = '<span>🔥</span>';
                                    $content[] = '<span>💣</span>';
                                $content[] = '</div>';

                            $content[] = '</div>';

                            // $content[] = '<form action="" method="POST">';
                            //     $content[] = '<div id="ays-quiz-dismiss-buttons-content">';
                            //         $content[] = '<button class="btn btn-link ays-button" name="ays_quiz_sale_btn" style="height: 32px; margin-left: 0;padding-left: 0">Dismiss ad</button>';
                            //         $content[] = '<button class="btn btn-link ays-button" name="ays_quiz_sale_btn_for_two_months" style="height: 32px; padding-left: 0">Dismiss ad for 2 months</button>';
                            //     $content[] = '</div>';
                            // $content[] = '</form>';

                        $content[] = '</div>';
                            
                    $content[] = '</div>';

                    $content[] = '<div class="ays-quiz-dicount-wrap-box ays-buy-now-button-box">';
                        $content[] = '<a href="https://ays-pro.com/wordpress/quiz-maker" class="button button-primary ays-buy-now-button" id="ays-button-top-buy-now" target="_blank" style="" >' . __( 'Buy Now !', 'quiz-maker' ) . '</a>';
                    $content[] = '</div>';

                    // $content[] = '<div class="ays-quiz-dicount-wrap-box ays-quiz-dicount-wrap-opacity-box">';
                    //     $content[] = '<a href="https://ays-pro.com/great-bundle" class="ays-buy-now-opacity-button" target="_blank">' . __( 'link', 'quiz-maker' ) . '</a>';
                    // $content[] = '</div>';

                $content[] = '</div>';

                $content[] = '<div style="position: absolute;right: 10px;bottom: 1px;" class="ays-quiz-dismiss-buttons-container-for-form">';
                    $content[] = '<form action="" method="POST">';
                        $content[] = '<div id="ays-quiz-dismiss-buttons-content">';
                            $content[] = '<button class="btn btn-link ays-button" name="ays_quiz_sale_btn" style="height: 32px; margin-left: 0;padding-left: 0">Dismiss ad</button>';
                            // $content[] = '<button class="btn btn-link ays-button" name="ays_quiz_sale_btn_for_two_months" style="height: 32px; padding-left: 0">Dismiss ad for 2 months</button>';
                        $content[] = '</div>';
                    $content[] = '</form>';
                $content[] = '</div>';

            $content[] = '</div>';

            $content = implode( '', $content );
            echo $content;
        }
    }

    // Christmas bundle
    public function ays_quiz_sale_message_christmas_bundle(){
        $content = array();

        $content = array();
        $content[] = '<div id="ays-quiz-winter-dicount-main">';
            $content[] = '<div id="ays-quiz-dicount-month-main" class="notice notice-success is-dismissible ays_quiz_dicount_info">';
                $content[] = '<div id="ays-quiz-dicount-month" class="ays_quiz_dicount_month">';
                    $content[] = '<a href="https://ays-pro.com/mega-bundle" target="_blank" class="ays-quiz-sale-banner-link"><img src="' . AYS_QUIZ_ADMIN_URL . '/images/mega_bundle_christmas_box.png"></a>';

                    $content[] = '<div class="ays-quiz-dicount-wrap-box">';

                        $content[] = '<strong>';
                            $content[] = __( "The BIGGEST <span class='ays-quiz-dicount-wrap-color' style='color:#000;'>50%</span> SALE on <br><span><a href='https://ays-pro.com/mega-bundle' target='_blank' class='ays-quiz-dicount-wrap-color ays-quiz-dicount-wrap-text-decoration' style='display:block;color:#000'>Christmas Bundle</a></span> (Quiz+Survey+Poll)!", 'quiz-maker' );
                        $content[] = '</strong>';

                        $content[] = '<br>';

                        $content[] = '<strong>';
                                $content[] = "Hurry up! Ending on. <a href='https://ays-pro.com/mega-bundle' target='_blank' style='color:#000;'>Check it out!</a>";
                        $content[] = '</strong>';
                            
                    $content[] = '</div>';

                    $content[] = '<div class="ays-quiz-dicount-wrap-box">';

                        $content[] = '<div id="ays-quiz-maker-countdown-main-container">';
                            $content[] = '<div class="ays-quiz-maker-countdown-container">';

                                $content[] = '<div id="ays-quiz-countdown">';
                                    $content[] = '<ul>';
                                        $content[] = '<li><span id="ays-quiz-countdown-days"></span>days</li>';
                                        $content[] = '<li><span id="ays-quiz-countdown-hours"></span>Hours</li>';
                                        $content[] = '<li><span id="ays-quiz-countdown-minutes"></span>Minutes</li>';
                                        $content[] = '<li><span id="ays-quiz-countdown-seconds"></span>Seconds</li>';
                                    $content[] = '</ul>';
                                $content[] = '</div>';

                                $content[] = '<div id="ays-quiz-countdown-content" class="emoji">';
                                    $content[] = '<span>🚀</span>';
                                    $content[] = '<span>⌛</span>';
                                    $content[] = '<span>🔥</span>';
                                    $content[] = '<span>💣</span>';
                                $content[] = '</div>';

                            $content[] = '</div>';

                            $content[] = '<form action="" method="POST">';
                                $content[] = '<button class="btn btn-link ays-button" name="ays_quiz_sale_btn_winter" style="height: 32px;color:#000" value="winter_bundle">Dismiss ad</button>';
                                $content[] = '<button class="btn btn-link ays-button" name="ays_quiz_sale_btn_winter_for_two_months" style="height: 32px; padding-left: 0;color:#000" value="winter_bundle">Dismiss ad for 2 months</button>';
                            $content[] = '</form>';

                        $content[] = '</div>';
                            
                    $content[] = '</div>';

                    
                    $content[] = '<a href="https://ays-pro.com/mega-bundle" class="button button-primary ays-button" id="ays-quiz-button-top-buy-now" target="_blank">' . __( 'Buy Now !', 'quiz-maker' ) . '</a>';
                $content[] = '</div>';
            $content[] = '</div>';
        // $content[] = '</div>';

        $content = implode( '', $content );
        echo $content;
    }

    public static function ays_quiz_spring_bundle_message($ishmar){
        if($ishmar == 0 ){
            $content = array();

            $content[] = '<div id="ays-quiz-dicount-month-main" class="notice notice-success is-dismissible ays_quiz_dicount_info">';
                $content[] = '<div id="ays-quiz-dicount-month" class="ays_quiz_dicount_month">';
                    $content[] = '<a href="https://ays-pro.com/spring-bundle" target="_blank" class="ays-quiz-sale-banner-link"><img src="' . AYS_QUIZ_ADMIN_URL . '/images/spring_bundle_logo.png"></a>';

                    $content[] = '<div class="ays-quiz-dicount-wrap-box">';
                        $content[] = '<p style="margin-right:25%;">';
                            $content[] = '<strong>';
                                $content[] = __( "Spring is here! <span class='ays-quiz-dicount-wrap-color'>40%</span> SALE on <br><span><a href='https://ays-pro.com/spring-bundle' target='_blank' class='ays-quiz-dicount-wrap-color ays-quiz-dicount-wrap-text-decoration' style='display:block;'>Spring Bundle</a></span> (Quiz+Copy+Popup) !", 'quiz-maker' );
                            $content[] = '</strong>';
                            $content[] = '<br>';
                            $content[] = '<strong>';
                                    $content[] = "Hurry up! Ending on. <a href='https://ays-pro.com/spring-bundle' target='_blank'>Check it out!</a>";
                            $content[] = '</strong>';
                        $content[] = '</p>';
                    $content[] = '</div>';

                    $content[] = '<div class="ays-quiz-dicount-wrap-box">';

                        $content[] = '<div id="ays-quiz-countdown-main-container">';
                            $content[] = '<div class="ays-quiz-countdown-container">';

                                $content[] = '<div id="ays-quiz-countdown">';
                                    $content[] = '<ul>';
                                        $content[] = '<li><span id="ays-quiz-countdown-days"></span>days</li>';
                                        $content[] = '<li><span id="ays-quiz-countdown-hours"></span>Hours</li>';
                                        $content[] = '<li><span id="ays-quiz-countdown-minutes"></span>Minutes</li>';
                                        $content[] = '<li><span id="ays-quiz-countdown-seconds"></span>Seconds</li>';
                                    $content[] = '</ul>';
                                $content[] = '</div>';

                                $content[] = '<div id="ays-quiz-countdown-content" class="emoji">';
                                    $content[] = '<span>🚀</span>';
                                    $content[] = '<span>⌛</span>';
                                    $content[] = '<span>🔥</span>';
                                    $content[] = '<span>💣</span>';
                                $content[] = '</div>';

                            $content[] = '</div>';

                            $content[] = '<form action="" method="POST" class="ays-quiz-btn-form">';
                                $content[] = '<button class="btn btn-link ays-button" name="ays_quiz_sale_btn_spring" style="height: 32px; margin-left: 0;padding-left: 0">Dismiss ad</button>';
                                $content[] = '<button class="btn btn-link ays-button" name="ays_quiz_sale_btn_spring_for_two_months" style="height: 32px; padding-left: 0">Dismiss ad for 2 months</button>';
                            $content[] = '</form>';

                        $content[] = '</div>';
                            
                    $content[] = '</div>';

                    $content[] = '<a href="https://ays-pro.com/spring-bundle" class="button button-primary ays-button" id="ays-button-top-buy-now" target="_blank">' . __( 'Buy Now !', 'quiz-maker' ) . '</a>';
                $content[] = '</div>';
            $content[] = '</div>';

            $content = implode( '', $content );
            echo $content;
        }
    }

    public static function ays_quiz_spring_bundle_small_message($ishmar){
        if($ishmar == 0 ){
            $content = array();

            $content[] = '<div id="ays-quiz-dicount-month-main" class="notice notice-success is-dismissible ays_quiz_dicount_info">';
                $content[] = '<div id="ays-quiz-dicount-month" class="ays_quiz_dicount_month">';
                    $content[] = '<a href="https://ays-pro.com/spring-bundle" target="_blank" class="ays-quiz-sale-banner-link"><img src="' . AYS_QUIZ_ADMIN_URL . '/images/spring_bundle_logo_box.png"></a>';

                    $content[] = '<div class="ays-quiz-dicount-wrap-box">';
                        $content[] = '<p>';
                            $content[] = '<strong>';
                                $content[] = __( "Spring is here! <span class='ays-quiz-dicount-wrap-color'>50%</span> SALE on <span><a href='https://ays-pro.com/spring-bundle' target='_blank' class='ays-quiz-dicount-wrap-color ays-quiz-dicount-wrap-text-decoration'>Spring Bundle</a></span><span style='display: block;'>Quiz + Popup + Copy</span>", 'quiz-maker' );
                            $content[] = '</strong>';
                        $content[] = '</p>';
                    $content[] = '</div>';

                    $content[] = '<div class="ays-quiz-dicount-wrap-box">';

                        $content[] = '<div id="ays-quiz-countdown-main-container">';

                            $content[] = '<form action="" method="POST" class="ays-quiz-btn-form">';
                                $content[] = '<button class="btn btn-link ays-button" name="ays_quiz_sale_spring_btn" style="height: 32px; margin-left: 0;padding-left: 0">Dismiss ad</button>';
                                $content[] = '<button class="btn btn-link ays-button" name="ays_quiz_sale_spring_btn_for_two_months" style="height: 32px; padding-left: 0">Dismiss ad for 2 months</button>';
                            $content[] = '</form>';

                        $content[] = '</div>';
                            
                    $content[] = '</div>';

                    $content[] = '<a href="https://ays-pro.com/spring-bundle" class="button button-primary ays-button" id="ays-button-top-buy-now" target="_blank">' . __( 'Buy Now !', 'quiz-maker' ) . '</a>';
                $content[] = '</div>';
            $content[] = '</div>';

            $content = implode( '', $content );
            echo $content;
        }
    }

    // Helloween bundle
    public static function ays_quiz_helloween_message($ishmar){
        if($ishmar == 0 ){
            $content = array();

            $content[] = '<div id="ays-quiz-dicount-month-main-helloween" class="notice notice-success is-dismissible ays_quiz_dicount_info">';
                $content[] = '<div id="ays-quiz-dicount-month-helloween" class="ays_quiz_dicount_month_helloween">';
                    $content[] = '<div class="ays-quiz-dicount-wrap-box-helloween-limited">';

                        $content[] = '<p>';
                            $content[] = __( "Limited Time <span class='ays-quiz-dicount-wrap-color-helloween' style='color:#b2ff00;'>30%</span> <span> SALE on</span><br><span style='' class='ays-quiz-helloween-bundle'><a href='https://ays-pro.com/wordpress/quiz-maker?utm_source=dashboard&utm_medium=quiz-free&utm_campaign=helloween-sale-banner' target='_blank' class='ays-quiz-dicount-wrap-color-helloween ays-quiz-dicount-wrap-text-decoration-helloween' style='display:block; color:#b2ff00;margin-right:6px;'>Quiz Maker</a>
                            </span>", 'quiz-maker' );
                        $content[] = '</p>';
                        $content[] = '<p>';
                                $content[] = "Hurry up! 
                                                <a href='https://ays-pro.com/wordpress/quiz-maker?utm_source=dashboard&utm_medium=quiz-free&utm_campaign=helloween-sale-banner' target='_blank' style='color:#ffc700;'>
                                                    Check it out!
                                                </a>";
                        $content[] = '</p>';
                            
                    $content[] = '</div>';

                    
                    $content[] = '<div class="ays-quiz-helloween-bundle-buy-now-timer">';
                        $content[] = '<div class="ays-quiz-dicount-wrap-box-helloween-timer">';
                            $content[] = '<div id="ays-quiz-countdown-main-container" class="ays-quiz-countdown-main-container-helloween">';
                                $content[] = '<div class="ays-quiz-countdown-container-helloween">';
                                    $content[] = '<div id="ays-quiz-countdown">';
                                        $content[] = '<ul>';
                                            $content[] = '<li><p><span id="ays-quiz-countdown-days"></span><span>days</span></p></li>';
                                            $content[] = '<li><p><span id="ays-quiz-countdown-hours"></span><span>Hours</span></p></li>';
                                            $content[] = '<li><p><span id="ays-quiz-countdown-minutes"></span><span>Mins</span></p></li>';
                                            $content[] = '<li><p><span id="ays-quiz-countdown-seconds"></span><span>Secs</span></p></li>';
                                        $content[] = '</ul>';
                                    $content[] = '</div>';

                                    $content[] = '<div id="ays-quiz-countdown-content" class="emoji">';
                                        $content[] = '<span>🚀</span>';
                                        $content[] = '<span>⌛</span>';
                                        $content[] = '<span>🔥</span>';
                                        $content[] = '<span>💣</span>';
                                    $content[] = '</div>';

                                $content[] = '</div>';

                            $content[] = '</div>';
                                
                        $content[] = '</div>';
                        $content[] = '<div class="ays-quiz-dicount-wrap-box ays-buy-now-button-box-helloween">';
                            $content[] = '<a href="https://ays-pro.com/wordpress/quiz-maker?utm_source=dashboard&utm_medium=quiz-free&utm_campaign=helloween-sale-banner" class="button button-primary ays-buy-now-button-helloween" id="ays-button-top-buy-now-helloween" target="_blank" style="" >' . __( 'Buy Now !', 'quiz-maker' ) . '</a>';
                        $content[] = '</div>';
                    $content[] = '</div>';

                $content[] = '</div>';

                $content[] = '<div style="position: absolute;right: 0;bottom: 1px;"  class="ays-quiz-dismiss-buttons-container-for-form-helloween">';
                    $content[] = '<form action="" method="POST">';
                        $content[] = '<div id="ays-quiz-dismiss-buttons-content-helloween">';
                        if( current_user_can( 'manage_options' ) ){
                            $content[] = '<button class="btn btn-link ays-button-helloween" name="ays_quiz_sale_btn" style="height: 32px; margin-left: 0;padding-left: 0">Dismiss ad</button>';
                            $content[] = wp_nonce_field( AYS_QUIZ_NAME . '-sale-banner' ,  AYS_QUIZ_NAME . '-sale-banner' );
                        }
                        $content[] = '</div>';
                    $content[] = '</form>';

                $content[] = '</div>';
                // $content[] = '<button type="button" class="notice-dismiss">';
                // $content[] = '</button>';
            $content[] = '</div>';

            $content = implode( '', $content );

            echo $content;
        }
    }

    // Christmas banner
    public static function ays_quiz_christmas_message($ishmar){
        if($ishmar == 0 ){
            $content = array();

            $content[] = '<div id="ays-quiz-dicount-christmas-month-main" class="notice notice-success is-dismissible ays_quiz_dicount_info">';
                $content[] = '<div id="ays-quiz-dicount-christmas-month" class="ays_quiz_dicount_month">';
                    $content[] = '<div class="ays-quiz-dicount-christmas-box">';
                        $content[] = '<div class="ays-quiz-dicount-christmas-wrap-box ays-quiz-dicount-christmas-wrap-box-80">';
                            $content[] = '<div class="ays-quiz-dicount-christmas-title-row">' . __( 'Limited Time', 'quiz-maker' ) .' '. '<a href="https://ays-pro.com/mega-bundle" class="ays-quiz-dicount-christmas-button-sale" target="_blank">' . __( '50%', 'quiz-maker' ) . '</a>' . ' SALE on</div>';
                            $content[] = '<div class="ays-quiz-dicount-christmas-title-row">'. '<a href="https://ays-pro.com/mega-bundle" class="ays-quiz-dicount-christmas-button-sale ays-quiz-dicount-christmas-button-block" target="_blank">' . __( 'Mega Bundle', 'quiz-maker' ) . '</a>' . __( '(Quiz + Survey + Poll)!', 'quiz-maker' ) . '</div>';
                        $content[] = '</div>';

                        $content[] = '<div class="ays-quiz-dicount-christmas-wrap-box" style="width: 25%;">';
                            $content[] = '<div id="ays-quiz-maker-countdown-main-container">';
                                $content[] = '<div class="ays-quiz-countdown-container">';
                                    $content[] = '<div id="ays-quiz-countdown" style="display: block;">';
                                        $content[] = '<ul>';
                                            $content[] = '<li><span id="ays-quiz-countdown-days"></span>' . __( 'Days', 'quiz-maker' ) . '</li>';
                                            $content[] = '<li><span id="ays-quiz-countdown-hours"></span>' . __( 'Hours', 'quiz-maker' ) . '</li>';
                                            $content[] = '<li><span id="ays-quiz-countdown-minutes"></span>' . __( 'Minutes', 'quiz-maker' ) . '</li>';
                                            $content[] = '<li><span id="ays-quiz-countdown-seconds"></span>' . __( 'Seconds', 'quiz-maker' ) . '</li>';
                                        $content[] = '</ul>';
                                    $content[] = '</div>';
                                    $content[] = '<div id="ays-quiz-countdown-content" class="emoji" style="display: none;">';
                                        $content[] = '<span>🚀</span>';
                                        $content[] = '<span>⌛</span>';
                                        $content[] = '<span>🔥</span>';
                                        $content[] = '<span>💣</span>';
                                    $content[] = '</div>';
                                $content[] = '</div>';
                            $content[] = '</div>';
                        $content[] = '</div>';

                        $content[] = '<div class="ays-quiz-dicount-christmas-wrap-box" style="width: 25%;">';
                            $content[] = '<a href="https://ays-pro.com/mega-bundle" class="ays-quiz-dicount-christmas-button-buy-now" target="_blank">' . __( 'BUY NOW!', 'quiz-maker' ) . '</a>';
                        $content[] = '</div>';
                    $content[] = '</div>';
                $content[] = '</div>';

                $content[] = '<div style="position: absolute;right: 0;bottom: 1px;"  class="ays-quiz-dismiss-buttons-container-for-form-christmas">';
                    $content[] = '<form action="" method="POST">';
                        $content[] = '<div id="ays-quiz-dismiss-buttons-content-christmas">';
                            $content[] = '<button class="btn btn-link ays-button-christmas" name="ays_quiz_sale_btn" style="">' . __( 'Dismiss ad', 'quiz-maker' ) . '</button>';
                        $content[] = '</div>';
                    $content[] = '</form>';
                $content[] = '</div>';
            $content[] = '</div>';

            $content = implode( '', $content );

            echo $content;
        }
    }

    // Chart Builder
    public function ays_quiz_chart_bulider_message($ishmar){
        if($ishmar == 0 ){
            $content = array();

            $content[] = '<div id="ays-quiz-dicount-month-main" class="notice notice-success is-dismissible ays_quiz_dicount_info ays_quiz_chart_bulider_container">';
                $content[] = '<div id="ays-quiz-dicount-month" class="ays_quiz_dicount_month ays_quiz_chart_bulider_box">';
                    $content[] = '<a href="https://wordpress.org/plugins/chart-builder/" target="_blank" class="ays-quiz-sale-banner-link"></a>';

                    $content[] = '<div class="ays-quiz-dicount-wrap-box">';

                        $content[] = '<strong style="font-weight: bold;">';
                            $content[] = __( "New Integration with <span><a href='https://wordpress.org/plugins/chart-builder/' target='_blank' style='color:#98FBA6; text-decoration: underline;'>Chart Builder</a></span> plugin", 'quiz-maker' );
                        $content[] = '</strong>';
                        $content[] = '<br>';
                        $content[] = __( "The integration will allow you to create beautiful charts based on your quiz data and share them with your users.", 'quiz-maker' );
                        $content[] = '<a href="https://www.youtube.com/watch?v=vqx76dw6NC8" target="_blank" style="margin-left: 5px; color:#98FBA6; text-decoration: underline;">' . __( 'Watch video', 'quiz-maker' ) . '</a>';

                        $content[] = '<br>';

                        $content[] = '<div style="position: absolute;right: 10px;bottom: 1px;" class="ays-quiz-dismiss-buttons-container-for-form">';

                            $content[] = '<form action="" method="POST">';
                                $content[] = '<div id="ays-quiz-dismiss-buttons-content">';
                                if( current_user_can( 'manage_options' ) ){
                                    $content[] = '<button class="btn btn-link ays-button" name="ays_quiz_sale_btn" style="height: 32px; margin-left: 0;padding-left: 0;">Dismiss ad</button>';
                                    $content[] = wp_nonce_field( $this->plugin_name . '-sale-banner' ,  $this->plugin_name . '-sale-banner' );
                                }
                                $content[] = '</div>';
                            $content[] = '</form>';
                            
                        $content[] = '</div>';

                    $content[] = '</div>';

                    $content[] = '<a href="https://wordpress.org/plugins/chart-builder/" class="button button-primary ays-button" id="ays-button-top-buy-now" target="_blank" >' . __( 'Install Now !', 'quiz-maker' ) . '</a>';
                $content[] = '</div>';
            $content[] = '</div>';

            $content = implode( '', $content );
            echo $content;
        }
    }

    // Fox LMS integration
    public function ays_quiz_fox_lms_integration_message($ishmar){
        if($ishmar == 0 ){
            $content = array();

            $content[] = '<div id="ays-quiz-dicount-month-main" class="notice notice-success is-dismissible ays_quiz_dicount_info ays_quiz_fox_lms_container">';
                $content[] = '<div id="ays-quiz-dicount-month" class="ays_quiz_dicount_month ays_quiz_fox_lms_box">';
                    $content[] = '<a href="https://wordpress.org/plugins/fox-lms/" target="_blank" class="ays-quiz-sale-banner-link"></a>';

                    $content[] = '<div class="ays-quiz-dicount-wrap-box">';

                        $content[] = '<strong style="font-weight: bold;">';
                            $content[] = __( "New Integration with <span><a href='https://wordpress.org/plugins/fox-lms/' target='_blank' style='color:#FF5104; text-decoration: underline;'>FoxLMS</a></span> plugin", 'quiz-maker' );
                        $content[] = '</strong>';
                        $content[] = '<br>';
                        $content[] = __( "The integration will allow you to add your quizzes into your courses.", 'quiz-maker' );
                        $content[] = '<a href="https://www.youtube.com/watch?v=a1MTsZEHY84" target="_blank" style="margin-left: 5px; color:#ffffff; text-decoration: underline;">' . __( 'Watch video', 'quiz-maker' ) . '</a>';

                        $content[] = '<br>';

                        $content[] = '<div style="position: absolute;right: 10px;bottom: 1px;" class="ays-quiz-dismiss-buttons-container-for-form">';

                            $content[] = '<form action="" method="POST">';
                                $content[] = '<div id="ays-quiz-dismiss-buttons-content">';
                                if( current_user_can( 'manage_options' ) ){
                                    $content[] = '<button class="btn btn-link ays-button" name="ays_quiz_sale_btn" style="height: 32px; margin-left: 0;padding-left: 0;">Dismiss ad</button>';
                                    $content[] = wp_nonce_field( $this->plugin_name . '-sale-banner' ,  $this->plugin_name . '-sale-banner' );
                                }
                                $content[] = '</div>';
                            $content[] = '</form>';
                            
                        $content[] = '</div>';

                    $content[] = '</div>';

                    $content[] = '<a href="https://wordpress.org/plugins/fox-lms/" class="button button-primary ays-button" id="ays-button-top-buy-now" target="_blank" >' . __( 'Install Now !', 'quiz-maker' ) . '</a>';
                $content[] = '</div>';
            $content[] = '</div>';

            $content = implode( '', $content );
            echo $content;
        }
    }

    // New Mega Bundle
    public function ays_quiz_new_mega_bundle_message($ishmar){
        if($ishmar == 0 ){
            $content = array();

            $date = time() + (int) ( get_option( 'gmt_offset' ) * HOUR_IN_SECONDS);
            $now_date = date('M d, Y H:i:s', $date);

            $quiz_banner_date = strtotime($this->ays_quiz_update_banner_time());

            $diff = $quiz_banner_date - $date;

            $style_attr = '';
            if( $diff < 0 ){
                $style_attr = 'style="display:none;"';
            }

            $quiz_cta_button_link = esc_url('https://ays-pro.com/mega-bundle?utm_source=dashboard&utm_medium=quiz-free&utm_campaign=mega-bundle-sale-banner-' . AYS_QUIZ_VERSION);

            $content[] = '<div id="ays-quiz-new-mega-bundle-dicount-month-main" class="ays-quiz-admin-notice notice notice-success is-dismissible ays_quiz_dicount_info">';
                $content[] = '<div id="ays-quiz-dicount-month" class="ays_quiz_dicount_month">';

                    $content[] = '<div class="ays-quiz-dicount-wrap-box ays-quiz-dicount-wrap-text-box">';
                        $content[] = '<div>';
                            $content[] = '<div class="ays-quiz-dicount-logo-box">';
                                $content[] = '<a href="' . $quiz_cta_button_link . '" target="_blank" class="ays-quiz-sale-banner-link"><img src="' . AYS_QUIZ_ADMIN_URL . '/images/mega_bundle_logo_box.png"></a>';

                                $content[] = '<div>';
                                    $content[] = '<span class="ays-quiz-new-mega-bundle-title">';
                                        $content[] = sprintf(
                                        /* translators: 1: opening link wrapper with <a> tag, 2: closing </a> tag */
                                        __( '%1$s Mega Bundle %2$s (Quiz + Survey + Poll)', 'quiz-maker' ),
                                        '<span style="display:inline-block; margin-right:5px;"><a href="' . esc_url( $quiz_cta_button_link ) . '" target="_blank" rel="noopener noreferrer" style="color:#ffffff !important; text-decoration: underline;">',
                                        '</a></span>'
                                    );
                                    $content[] = '</span>';
                                    $content[] = '</br>';
                                    $content[] = '<span class="ays-quiz-new-mega-bundle-desc">';
                                        $content[] = __( "30 Day Money Back Guarantee", 'quiz-maker' );
                                    $content[] = '</span>';
                                $content[] = '</div>';

                                $content[] = '<div class="ays-quiz-new-mega-bundle-title-icon-row" style="display: inline-block;">';
                                    $content[] = '<img src="' . AYS_QUIZ_ADMIN_URL . '/images/ays-quiz-banner-50.svg" class="ays-quiz-new-mega-bundle-mobile-image-display-none" style="width: 70px;">';
                                $content[] = '</div>';

                            $content[] = '</div>';

                            $content[] = '<div class="ays-quiz-new-mega-bundle-mobile-image-display-block display_none">';
                                $content[] = '<img src="' . AYS_QUIZ_ADMIN_URL . '/images/ays-quiz-banner-50.svg" style="width: 70px;">';
                            $content[] = '</div>';
                        $content[] = '</div>';

                        $content[] = '<div style="position: absolute;right: 10px;bottom: 1px;" class="ays-quiz-dismiss-buttons-container-for-form">';

                            $content[] = '<form action="" method="POST">';
                                $content[] = '<div id="ays-quiz-dismiss-buttons-content">';
                                if( current_user_can( 'manage_options' ) ){
                                    $content[] = '<button class="btn btn-link ays-button" name="ays_quiz_sale_btn" style="height: 32px; margin-left: 0;padding-left: 0">'. __( "Dismiss ad", 'quiz-maker' ) .'</button>';
                                    $content[] = wp_nonce_field( AYS_QUIZ_NAME . '-sale-banner' ,  AYS_QUIZ_NAME . '-sale-banner' );
                                }
                                $content[] = '</div>';
                            $content[] = '</form>';
                            
                        $content[] = '</div>';

                    $content[] = '</div>';

                    $content[] = '<div class="ays-quiz-dicount-wrap-box ays-quiz-dicount-wrap-countdown-box">';

                        $content[] = '<div id="ays-quiz-maker-countdown-main-container">';
                            $content[] = '<div class="ays-quiz-maker-countdown-container">';

                                $content[] = '<div ' . $style_attr . ' id="ays-quiz-countdown">';

                                    // $content[] = '<div>';
                                    //     $content[] = __( "Offer ends in:", 'quiz-maker' );
                                    // $content[] = '</div>';

                                    $content[] = '<ul>';
                                        $content[] = '<li><span id="ays-quiz-countdown-days"></span>'. __( "Days", 'quiz-maker' ) .'</li>';
                                        $content[] = '<li><span id="ays-quiz-countdown-hours"></span>'. __( "Hours", 'quiz-maker' ) .'</li>';
                                        $content[] = '<li><span id="ays-quiz-countdown-minutes"></span>'. __( "Minutes", 'quiz-maker' ) .'</li>';
                                        $content[] = '<li><span id="ays-quiz-countdown-seconds"></span>'. __( "Seconds", 'quiz-maker' ) .'</li>';
                                    $content[] = '</ul>';
                                $content[] = '</div>';

                                $content[] = '<div id="ays-quiz-countdown-content" class="emoji">';
                                //     $content[] = '<span></span>';
                                //     $content[] = '<span></span>';
                                //     $content[] = '<span></span>';
                                //     $content[] = '<span></span>';
                                $content[] = '</div>';

                            $content[] = '</div>';
                        $content[] = '</div>';
                            
                    $content[] = '</div>';

                    $content[] = '<div class="ays-quiz-dicount-wrap-box ays-quiz-dicount-wrap-button-box">';
                        $content[] = '<a href="'. $quiz_cta_button_link .'" class="button button-primary ays-button" id="ays-button-top-buy-now" target="_blank">' . __( 'Buy Now', 'quiz-maker' ) . '</a>';
                        $content[] = '<span class="ays-quiz-dicount-one-time-text">';
                            $content[] = __( "One-time payment", 'quiz-maker' );
                        $content[] = '</span>';
                    $content[] = '</div>';
                $content[] = '</div>';
            $content[] = '</div>';

            // /* New Mega Bundle Banner Quiz | Start */
            $content[] = '<style id="ays-quiz-mega-bundle-styles-inline-css">';
            $content[] = 'div#ays-quiz-new-mega-bundle-dicount-month-main{border:0;background:#fff;border-radius:20px;box-shadow:unset;position:relative;z-index:1;min-height:80px}div#ays-quiz-new-mega-bundle-dicount-month-main.ays_quiz_dicount_info button{display:flex;align-items:center}div#ays-quiz-new-mega-bundle-dicount-month-main div#ays-quiz-dicount-month a.ays-quiz-sale-banner-link:focus{outline:0;box-shadow:0}div#ays-quiz-new-mega-bundle-dicount-month-main .btn-link{color:#007bff;background-color:transparent;display:inline-block;font-weight:400;text-align:center;white-space:nowrap;vertical-align:middle;-webkit-user-select:none;-moz-user-select:none;-ms-user-select:none;user-select:none;border:1px solid transparent;padding:.375rem .75rem;font-size:1rem;line-height:1.5;border-radius:.25rem}div#ays-quiz-new-mega-bundle-dicount-month-main.ays_quiz_dicount_info{background-image:url("' . esc_attr(AYS_QUIZ_ADMIN_URL) . '/images/new-mega-bundle-logo-background.svg");background-position:center right;background-repeat:no-repeat;background-size:cover;background-color:#5551ff;padding:1px 38px 1px 12px}#ays-quiz-new-mega-bundle-dicount-month-main .ays_quiz_dicount_month{display:flex;align-items:center;justify-content:space-between;color:#fff}#ays-quiz-new-mega-bundle-dicount-month-main .ays_quiz_dicount_month img{width:60px}#ays-quiz-new-mega-bundle-dicount-month-main .ays-quiz-sale-banner-link{display:flex;justify-content:center;align-items:center;width:60px}#ays-quiz-new-mega-bundle-dicount-month-main .ays-quiz-dicount-wrap-box{font-size:14px;padding:12px;text-align:center}#ays-quiz-new-mega-bundle-dicount-month-main .ays-quiz-dicount-wrap-box.ays-quiz-dicount-wrap-text-box{text-align:left;width:auto;display:flex;justify-content:space-around;align-items:flex-start}#ays-quiz-new-mega-bundle-dicount-month-main .ays-quiz-dicount-wrap-box.ays-quiz-dicount-wrap-countdown-box{width:30%;display:flex;justify-content:center;align-items:center}#ays-quiz-new-mega-bundle-dicount-month-main .ays-quiz-dicount-wrap-box.ays-quiz-dicount-wrap-button-box{width:20%;display:flex;justify-content:center;align-items:center;flex-direction:column}#ays-quiz-new-mega-bundle-dicount-month-main .ays-quiz-dicount-wrap-box .ays-quiz-dicount-logo-box{display:flex;justify-content:flex-start;align-items:center;gap:20px}#ays-quiz-new-mega-bundle-dicount-month-main .ays-quiz-dicount-wrap-box.ays-quiz-dicount-wrap-text-box .ays-quiz-new-mega-bundle-title{color:#fdfdfd;font-size:22px;font-style:normal;font-weight:600;line-height:normal}#ays-quiz-new-mega-bundle-dicount-month-main .ays-quiz-dicount-wrap-box.ays-quiz-dicount-wrap-text-box .ays-quiz-new-mega-bundle-title-icon-row{display:inline-block}#ays-quiz-new-mega-bundle-dicount-month-main .ays-quiz-dicount-wrap-box.ays-quiz-dicount-wrap-text-box .ays-quiz-new-mega-bundle-desc{display:inline-block;color:#fff;font-size:15px;font-style:normal;font-weight:400;line-height:normal;margin-top:10px}#ays-quiz-new-mega-bundle-dicount-month-main .ays-quiz-dicount-wrap-box strong{font-size:17px;font-weight:700;letter-spacing:.8px}#ays-quiz-new-mega-bundle-dicount-month-main .ays-quiz-dicount-wrap-color{color:#971821}#ays-quiz-new-mega-bundle-dicount-month-main .ays-quiz-dicount-wrap-text-decoration{text-decoration:underline}#ays-quiz-new-mega-bundle-dicount-month-main .ays-quiz-dicount-wrap-box.ays-buy-now-button-box{display:flex;justify-content:flex-end;align-items:center;width:30%}#ays-quiz-new-mega-bundle-dicount-month-main .ays-quiz-dicount-wrap-box .ays-button,#ays-quiz-new-mega-bundle-dicount-month-main .ays-quiz-dicount-wrap-box .ays-buy-now-button{align-items:center;font-weight:500}#ays-quiz-new-mega-bundle-dicount-month-main .ays-quiz-dicount-wrap-box .ays-buy-now-button{background:#971821;border-color:#fff;display:flex;justify-content:center;align-items:center;padding:5px 15px;font-size:16px;border-radius:5px}#ays-quiz-new-mega-bundle-dicount-month-main .ays-quiz-dicount-wrap-box .ays-buy-now-button:hover{background:#7d161d;border-color:#971821}#ays-quiz-new-mega-bundle-dicount-month-main #ays-quiz-dismiss-buttons-content{display:flex;justify-content:center}#ays-quiz-new-mega-bundle-dicount-month-main #ays-quiz-dismiss-buttons-content .ays-button{margin:0!important;font-size:13px;color:#fff}#ays-quiz-new-mega-bundle-dicount-month-main .ays-quiz-dicount-wrap-opacity-box{width:19%}#ays-quiz-new-mega-bundle-dicount-month-main .ays-buy-now-opacity-button{padding:40px 15px;display:flex;justify-content:center;align-items:center;opacity:0}#ays-quiz-maker-countdown-main-container .ays-quiz-maker-countdown-container{margin:0 auto;text-align:center}#ays-quiz-maker-countdown-main-container #ays-quiz-countdown-headline{letter-spacing:.125rem;text-transform:uppercase;font-size:18px;font-weight:400;margin:0;padding:9px 0 4px;line-height:1.3}#ays-quiz-maker-countdown-main-container li,#ays-quiz-maker-countdown-main-container ul{margin:0}#ays-quiz-maker-countdown-main-container li{display:inline-block;font-size:14px;list-style-type:none;padding:14px;text-transform:lowercase}#ays-quiz-maker-countdown-main-container li span{display:flex;justify-content:center;align-items:center;font-size:40px;min-height:62px;min-width:62px;border-radius:4.273px;border:.534px solid #f4f4f4;background:#9896ed;color:#fff}#ays-quiz-maker-countdown-main-container .emoji{display:none;padding:1rem}#ays-quiz-maker-countdown-main-container .emoji span{font-size:30px;padding:0 .5rem}#ays-quiz-new-mega-bundle-dicount-month-main .ays-quiz-dicount-wrap-box li{position:relative}#ays-quiz-new-mega-bundle-dicount-month-main .ays-quiz-dicount-wrap-box li span:after{content:":";color:#fff;position:absolute;top:10px;right:-5px;font-size:40px}#ays-quiz-new-mega-bundle-dicount-month-main .ays-quiz-dicount-wrap-box li span#ays-quiz-countdown-seconds:after{content:unset}#ays-quiz-new-mega-bundle-dicount-month-main #ays-button-top-buy-now{display:flex;align-items:center;border-radius:6.409px;background:#f66123;padding:12px 32px;color:#fff;font-size:15px;font-style:normal;line-height:normal;margin:0!important}div#ays-quiz-new-mega-bundle-dicount-month-main button.notice-dismiss:before{color:#fff;content:"\f00d";font-family:fontawesome;font-size:22px}#ays-quiz-new-mega-bundle-dicount-month-main .ays-quiz-new-mega-bundle-guaranteeicon{width:30px;margin-right:5px}#ays-quiz-new-mega-bundle-dicount-month-main .ays-quiz-dicount-one-time-text{color:#fff;font-size:12px;font-style:normal;font-weight:600;line-height:normal}@media all and (max-width:768px){div#ays-quiz-new-mega-bundle-dicount-month-main.ays_quiz_dicount_info.notice{display:none!important;background-position:bottom right;background-repeat:no-repeat;background-size:cover;border-radius:32px}div#ays-quiz-new-mega-bundle-dicount-month-main{padding-right:0}div#ays-quiz-new-mega-bundle-dicount-month-main .ays_quiz_dicount_month{display:flex;align-items:center;justify-content:space-between;align-content:center;flex-wrap:wrap;flex-direction:column;padding:10px 0}div#ays-quiz-new-mega-bundle-dicount-month-main .ays-quiz-dicount-wrap-box{width:100%!important;text-align:center}#ays-quiz-maker-countdown-main-container #ays-quiz-countdown-headline{font-size:15px;font-weight:600}#ays-quiz-maker-countdown-main-container ul{font-weight:500}#ays-quiz-maker-countdown-main-container li span{font-size:35px;min-height:57px;min-width:55px}div#ays-quiz-maker-countdown-main-container li{padding:10px}div#ays-quiz-new-mega-bundle-dicount-month-main .ays-quiz-new-mega-bundle-mobile-image-display-none{display:none!important}div#ays-quiz-new-mega-bundle-dicount-month-main .ays-quiz-new-mega-bundle-mobile-image-display-block{display:block!important;margin-top:5px}div#ays-quiz-new-mega-bundle-dicount-month-main .ays-quiz-dicount-wrap-box.ays-quiz-dicount-wrap-text-box{width:100%!important;text-align:center;flex-direction:column;margin-top:20px;justify-content:center;align-items:center}div#ays-quiz-new-mega-bundle-dicount-month-main .ays-quiz-dicount-wrap-box li span:after{top:unset}div#ays-quiz-new-mega-bundle-dicount-month-main .ays-quiz-dicount-wrap-box.ays-quiz-dicount-wrap-countdown-box{width:100%;display:flex;justify-content:center;align-items:center}#ays-quiz-new-mega-bundle-dicount-month-main .ays-button{margin:0 auto!important}#ays-quiz-new-mega-bundle-dicount-month-main #ays-quiz-dismiss-buttons-content .ays-button{padding-left:unset!important}div#ays-quiz-new-mega-bundle-dicount-month-main .ays-quiz-dicount-wrap-box.ays-buy-now-button-box{justify-content:center}div#ays-quiz-new-mega-bundle-dicount-month-main .ays-quiz-dicount-wrap-box .ays-buy-now-button{font-size:14px;padding:5px 10px}div#ays-quiz-new-mega-bundle-dicount-month-main .ays-buy-now-opacity-button{display:none}#ays-quiz-new-mega-bundle-dicount-month-main .ays-quiz-dismiss-buttons-container-for-form{position:static!important}.comparison .product img{width:70px}.ays-quiz-features-wrap .comparison a.price-buy{padding:8px 5px;font-size:11px}}@media screen and (max-width:1350px) and (min-width:768px){div#ays-quiz-new-mega-bundle-dicount-month-main.ays_quiz_dicount_info.notice{background-position:bottom right;background-repeat:no-repeat;background-size:cover}div#ays-quiz-new-mega-bundle-dicount-month-main .ays-quiz-dicount-wrap-box strong{font-size:15px}#ays-quiz-maker-countdown-main-container li{font-size:11px}div#ays-quiz-new-mega-bundle-dicount-month-main .ays-quiz-dicount-wrap-opacity-box{display:none}}@media screen and (max-width:1680px){#ays-quiz-maker-countdown-main-container li span{font-size:30px;min-height:50px;min-width:50px}}@media screen and (max-width:1680px) and (min-width:1551px){div#ays-quiz-new-mega-bundle-dicount-month-main .ays-quiz-dicount-wrap-box.ays-quiz-dicount-wrap-text-box{width:29%}div#ays-quiz-new-mega-bundle-dicount-month-main .ays-quiz-dicount-wrap-box.ays-quiz-dicount-wrap-countdown-box{width:30%}}@media screen and (max-width:1410px){#ays-quiz-new-mega-bundle-dicount-month-main .ays-quiz-coupon-row{width:150px}}@media screen and (max-width:1550px) and (min-width:1400px){div#ays-quiz-new-mega-bundle-dicount-month-main .ays-quiz-dicount-wrap-box.ays-quiz-dicount-wrap-countdown-box{width:35%}}@media screen and (max-width:1400px) and (min-width:1250px){div#ays-quiz-new-mega-bundle-dicount-month-main .ays-quiz-dicount-wrap-box.ays-quiz-dicount-wrap-countdown-box{width:35%}div#ays-quiz-new-mega-bundle-dicount-month-main .ays-quiz-dicount-wrap-box.ays-quiz-dicount-wrap-text-box{width:40%}div#ays-quiz-maker-countdown-main-container li span{font-size:30px;min-height:50px;min-width:50px}}@media screen and (max-width:1274px){div#ays-quiz-maker-countdown-main-container li span{font-size:25px;min-height:40px;min-width:40px}#ays-quiz-new-mega-bundle-dicount-month-main .ays-quiz-dicount-wrap-box.ays-quiz-dicount-wrap-text-box .ays-quiz-new-mega-bundle-title{font-size:15px}}@media screen and (max-width:1200px){#ays-quiz-new-mega-bundle-dicount-month-main .ays-quiz-dicount-wrap-box.ays-quiz-dicount-wrap-button-box{margin-bottom:16px}#ays-quiz-maker-countdown-main-container ul{padding-left:0}#ays-quiz-new-mega-bundle-dicount-month-main .ays-quiz-coupon-row{width:120px;font-size:18px}#ays-quiz-new-mega-bundle-dicount-month-main #ays-button-top-buy-now{padding:12px 20px}#ays-quiz-new-mega-bundle-dicount-month-main .ays-quiz-dicount-wrap-box{font-size:12px}#ays-quiz-new-mega-bundle-dicount-month-main .ays-quiz-dicount-wrap-box.ays-quiz-dicount-wrap-text-box .ays-quiz-new-mega-bundle-desc{font-size:13px}}@media screen and (max-width:1076px) and (min-width:769px){#ays-quiz-maker-countdown-main-container li{padding:10px}#ays-quiz-new-mega-bundle-dicount-month-main .ays-quiz-coupon-row{width:100px;font-size:16px}#ays-quiz-new-mega-bundle-dicount-month-main .ays-quiz-dicount-wrap-box.ays-quiz-dicount-wrap-button-box{margin-bottom:16px}#ays-quiz-new-mega-bundle-dicount-month-main #ays-button-top-buy-now{padding:12px 15px}#ays-quiz-new-mega-bundle-dicount-month-main .ays-quiz-dicount-wrap-box{font-size:11px;padding:12px 0}}@media screen and (max-width:1250px) and (min-width:769px){div#ays-quiz-new-mega-bundle-dicount-month-main .ays-quiz-dicount-wrap-box.ays-quiz-dicount-wrap-countdown-box{width:45%}div#ays-quiz-new-mega-bundle-dicount-month-main .ays-quiz-dicount-wrap-box.ays-quiz-dicount-wrap-text-box{width:35%}div#ays-quiz-maker-countdown-main-container li span{font-size:30px;min-height:50px;min-width:50px}}';
            $content[] = '</style>';
            // /* New Mega Bundle Banner Quiz | End */

            $content = implode( '', $content );
            echo ($content);
            // echo wp_kses_post($content); // Div style attribute not working in this case
        }
    }

    // Black Friday
    public function ays_quiz_black_friday_message($ishmar){
        if($ishmar == 0 ){
            $content = array();

            $ays_quiz_cta_button_link = esc_url('https://ays-pro.com/wordpress/quiz-maker?utm_source=dashboard&utm_medium=quiz-free&utm_campaign=black-friday-sale-banner-' . AYS_QUIZ_UTM_VERSION);

            $content[] = '<div id="ays-quiz-dicount-black-friday-month-main" class="notice notice-success is-dismissible ays_quiz_dicount_info">';
                $content[] = '<div id="ays-quiz-dicount-black-friday-month" class="ays_quiz_dicount_month">';
                    $content[] = '<div class="ays-quiz-dicount-black-friday-box">';
                        $content[] = '<div class="ays-quiz-dicount-black-friday-wrap-box ays-quiz-dicount-black-friday-wrap-box-80" style="width: 70%;">';
                            $content[] = '<div class="">';
                                $content[] = '<div class="ays-quiz-dicount-black-friday-title-row" >' . __( 'Coupon Code', "quiz-maker" ) .' ' . '</div>';
                                $content[] = '<div class="ays-quiz-dicount-black-friday-title-row">';

                                $content[] = '
                                    <span class="ays-quiz-dicount-black-friday-banner-2025-coupon-wrapper">
                                        <span class="ays-quiz-dicount-black-friday-banner-2025-coupon-box" onclick="aysQuizHalloweenCopyToClipboard(\'FREE2PROBF\')" title="Click to copy">
                                            <span class="ays-quiz-dicount-black-friday-banner-2025-coupon-text">FREE2PROBF</span>
                                            <svg width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg" class="ays-quiz-dicount-black-friday-banner-2025-copy-icon">
                                                <path d="M13.5 2.5H6.5C5.67 2.5 5 3.17 5 4V10C5 10.83 5.67 11.5 6.5 11.5H13.5C14.33 11.5 15 10.83 15 10V4C15 3.17 14.33 2.5 13.5 2.5ZM13.5 10H6.5V4H13.5V10ZM2.5 6.5V12.5C2.5 13.33 3.17 14 4 14H10V12.5H4V6.5H2.5Z" fill="white"/>
                                            </svg>
                                        </span>
                                    </span>';
                                $content[] = '</div> ';
                            $content[] = '</div>';

                        $content[] = '</div>';

                        $content[] = '<div class="ays-quiz-dicount-black-friday-wrap-box ays-quiz-dicount-black-friday-wrap-text-box">';
                            $content[] = '<div class="ays-quiz-dicount-black-friday-text-row">' . '30% off' . '</div>';
                        $content[] = '</div>';

                        $content[] = '<div class="ays-quiz-dicount-black-friday-wrap-box" style="width: 25%;">';
                            $content[] = '<div id="ays-quiz-countdown-main-container">';
                                $content[] = '<div class="ays-quiz-countdown-container">';
                                    $content[] = '<div id="ays-quiz-countdown" style="display: block;">';
                                        $content[] = '<ul>';
                                            $content[] = '<li><span id="ays-quiz-countdown-days"></span>' . __( 'Days', "quiz-maker" ) . '</li>';
                                            $content[] = '<li><span id="ays-quiz-countdown-hours"></span>' . __( 'Hours', "quiz-maker" ) . '</li>';
                                            $content[] = '<li><span id="ays-quiz-countdown-minutes"></span>' . __( 'Minutes', "quiz-maker" ) . '</li>';
                                            $content[] = '<li><span id="ays-quiz-countdown-seconds"></span>' . __( 'Seconds', "quiz-maker" ) . '</li>';
                                        $content[] = '</ul>';
                                    $content[] = '</div>';
                                    $content[] = '<div id="ays-quiz-countdown-content" class="emoji" style="display: none;">';
                                        $content[] = '<span></span>';
                                        $content[] = '<span></span>';
                                        $content[] = '<span></span>';
                                        $content[] = '<span></span>';
                                    $content[] = '</div>';
                                $content[] = '</div>';
                            $content[] = '</div>';
                        $content[] = '</div>';

                        $content[] = '<div class="ays-quiz-dicount-black-friday-wrap-box" style="width: 25%;">';
                            $content[] = '<a href="'. $ays_quiz_cta_button_link .'" class="ays-quiz-dicount-black-friday-button-buy-now" target="_blank">' . __( 'Get Your Deal', "quiz-maker" ) . '</a>';
                        $content[] = '</div>';
                    $content[] = '</div>';
                $content[] = '</div>';

                $content[] = '<div style="position: absolute;right: 0;bottom: 1px;"  class="ays-quiz-dismiss-buttons-container-for-form-black-friday">';
                    $content[] = '<form action="" method="POST">';
                        $content[] = '<div id="ays-quiz-dismiss-buttons-content">';
                            if( current_user_can( 'manage_options' ) ){
                                $content[] = '<button class="btn btn-link ays-button" name="ays_quiz_sale_btn" style="height: 32px; margin-left: 0;padding-left: 0">Dismiss ad</button>';
                                $content[] = wp_nonce_field( AYS_QUIZ_NAME . '-sale-banner' ,  AYS_QUIZ_NAME . '-sale-banner' );
                            }
                        $content[] = '</div>';
                    $content[] = '</form>';
                $content[] = '</div>';
            $content[] = '</div>';

            $content[] = '<script>';
            $content[] = "
                    function aysQuizHalloweenCopyToClipboard(text) {
                        // Create a temporary textarea element
                        var textarea = document.createElement('textarea');
                        textarea.value = text;
                        textarea.style.position = 'fixed';
                        textarea.style.opacity = '0';
                        document.body.appendChild(textarea);
                        
                        // Select and copy the text
                        textarea.select();
                        textarea.setSelectionRange(0, 99999); // For mobile devices
                        
                        try {
                            document.execCommand('copy');
                            aysQuizHalloweenShowCopyNotification('Coupon code copied!');
                        } catch (err) {
                            console.error('Failed to copy text: ', err);
                        }
                        
                        // Remove the temporary textarea
                        document.body.removeChild(textarea);
                    }

                    function aysQuizHalloweenShowCopyNotification(message) {
                        // Check if notification already exists
                        var existingNotification = document.querySelector('.ays-quiz-discount-black-friday-banner-2025-copy-notification');
                        if (existingNotification) {
                            document.body.removeChild(existingNotification);
                        }
                        
                        // Create notification element
                        var notification = document.createElement('div');
                        notification.className = 'ays-quiz-discount-black-friday-banner-2025-copy-notification';
                        notification.textContent = message;
                        document.body.appendChild(notification);
                        
                        // Show notification with animation
                        setTimeout(function() {
                            notification.classList.add('show');
                        }, 10);
                        
                        // Hide and remove notification after 2 seconds
                        setTimeout(function() {
                            notification.classList.remove('show');
                            setTimeout(function() {
                                if (notification.parentNode) {
                                    document.body.removeChild(notification);
                                }
                            }, 300);
                        }, 2000);
                    }";
            $content[] = '</script>';

            $content[] = '<style>';
            $content[] = '
                /* Black friday banner start */
                div#ays-quiz-dicount-black-friday-month-main *{color:#fff}div#ays-quiz-dicount-black-friday-month-main div#ays-quiz-dicount-black-friday-month a.ays-quiz-sale-banner-link:focus{outline:0;box-shadow:0}div#ays-quiz-dicount-black-friday-month-main .btn-link{background-color:transparent;display:inline-block;font-weight:400;text-align:center;white-space:nowrap;vertical-align:middle;-webkit-user-select:none;-moz-user-select:none;-ms-user-select:none;user-select:none;border:1px solid transparent;padding:.375rem .75rem;font-size:12px;line-height:1.5;border-radius:.25rem;color:rgba(255,255,255,.6)}div#ays-quiz-dicount-black-friday-month-main.ays_quiz_dicount_info{background-image:linear-gradient(45deg,#1e101d,#c60af4);padding:unset;border-left:0}#ays-quiz-dicount-black-friday-month-main .ays_quiz_dicount_month{position:relative;background-image:url("'. esc_attr(AYS_QUIZ_ADMIN_URL) .'/images/black-friday-plugins-background-image.webp");background-position:center right;background-repeat:no-repeat;background-size:100% 100%}#ays-quiz-dicount-black-friday-month-main .ays_quiz_dicount_month img{width:80px}#ays-quiz-dicount-black-friday-month-main .ays-quiz-sale-banner-link{display:flex;justify-content:center;align-items:center;width:200px}#ays-quiz-dicount-black-friday-month-main .ays-quiz-dicount-black-friday-button-sale{font-style:normal;font-weight:600;font-size:24px;text-align:center;color:#b2ff00;text-transform:uppercase}#ays-quiz-dicount-black-friday-month-main .ays-quiz-dicount-black-friday-wrap-box{font-size:14px;padding:12px;text-align:center;width:50%;white-space:nowrap}#ays-quiz-dicount-black-friday-month-main .ays-quiz-dicount-black-friday-wrap-box strong{font-size:17px;font-weight:700;letter-spacing:.8px}#ays-quiz-dicount-black-friday-month-main .ays-quiz-dicount-black-friday-wrap-color{color:#971821}#ays-quiz-dicount-black-friday-month-main .ays-quiz-dicount-black-friday-wrap-text-decoration{text-decoration:underline}#ays-quiz-dicount-black-friday-month-main .ays-quiz-dicount-black-friday-wrap-box.ays-buy-now-button-box{display:flex;justify-content:flex-end;align-items:center;width:30%}#ays-quiz-dicount-black-friday-month-main .ays-quiz-dicount-black-friday-wrap-box .ays-button,#ays-quiz-dicount-black-friday-month-main .ays-quiz-dicount-black-friday-wrap-box .ays-buy-now-button{align-items:center;font-weight:500}#ays-quiz-dicount-black-friday-month-main .ays-quiz-dicount-black-friday-wrap-box .ays-buy-now-button{background:#971821;border-color:#fff;display:flex;justify-content:center;align-items:center;padding:5px 15px;font-size:16px;border-radius:5px}#ays-quiz-dicount-black-friday-month-main .ays-quiz-dicount-black-friday-wrap-box .ays-buy-now-button:hover{background:#7d161d;border-color:#971821}#ays-quiz-dicount-black-friday-month-main #ays-quiz-dismiss-buttons-content{display:flex;justify-content:center}#ays-quiz-dicount-black-friday-month-main #ays-quiz-dismiss-buttons-content .ays-button{margin:0!important;font-size:13px;color:#969393b0}#ays-quiz-dicount-black-friday-month-main .ays-quiz-dicount-black-friday-wrap-opacity-box{width:19%}#ays-quiz-dicount-black-friday-month-main .ays-buy-now-opacity-button{padding:40px 15px;display:flex;justify-content:center;align-items:center;opacity:0}#ays-quiz-countdown-main-container .ays-quiz-countdown-container{margin:0 auto;text-align:center}#ays-quiz-countdown-main-container #ays-quiz-countdown-headline{letter-spacing:.125rem;text-transform:uppercase;font-size:18px;font-weight:400;margin:0;padding:9px 0 4px;line-height:1.3}#ays-quiz-countdown-main-container li,#ays-quiz-countdown-main-container ul{margin:0;font-weight:600}#ays-quiz-countdown-main-container li{display:inline-block;font-size:10px;list-style-type:none;padding:10px;text-transform:uppercase}#ays-quiz-countdown-main-container li span{display:block;font-size:22px;min-height:33px}#ays-quiz-countdown-main-container .emoji{display:none;padding:1rem}#ays-quiz-countdown-main-container .emoji span{font-size:25px;padding:0 .5rem}#ays-quiz-dicount-black-friday-month-main .ays-quiz-dicount-black-friday-box{display:flex;justify-content:space-between;align-items:center;width:95%;margin:auto}#ays-quiz-dicount-black-friday-month-main .ays-quiz-dicount-black-friday-title-row{text-align:center;padding-right:50px;font-style:normal;font-weight:900;font-size:19px;color:#fff;}#ays-quiz-dicount-black-friday-month-main .ays-quiz-dicount-black-friday-button-buy-now{border:none;outline:0;padding:10px 20px;font-size:22px;text-transform:uppercase;font-weight:700;text-decoration:none;background:linear-gradient(180deg,#dd0bef 0,#82008d 100%);border-radius:16px}#ays-quiz-dicount-black-friday-month-main .ays-quiz-dicount-black-friday-text-row{text-transform:uppercase;text-shadow:-1.5px 0 #dd0bef,0 1.5px #dd0bef,1.5px 0 #dd0bef,0 -1.5px #dd0bef;font-weight:900;font-style:normal;font-size:40px;line-height:40px;color:#fff}#ays-quiz-dicount-black-friday-month-main .ays-quiz-dicount-black-friday-wrap-text-box{position:absolute;width:25%;top:10px;bottom:0;right:0;left:0;margin:0 auto}#ays-quiz-countdown ul{padding:0}#ays-quiz-dicount-black-friday-month-main .ays-quiz-dicount-black-friday-banner-2025-coupon-box{border:2px dashed rgba(255,255,255,.4);padding:0 12px;border-radius:6px;background:rgba(255,255,255,.1);cursor:pointer;transition:.3s;display:flex;align-items:center;justify-content:center;gap:6px;backdrop-filter:blur(10px);width:fit-content;margin:0 auto}#ays-quiz-dicount-black-friday-month-main .ays-quiz-dicount-black-friday-banner-2025-coupon-box:hover{background:rgba(255,255,255,.2);border-color:rgba(255,255,255,.6);transform:translateY(-1px)}#ays-quiz-dicount-black-friday-month-main .ays-quiz-discount-black-friday-banner-2025-coupon-text{font-size:14px;font-weight:700;letter-spacing:1px;color:#fff;font-family:monospace}#ays-quiz-dicount-black-friday-month-main .ays-quiz-discount-black-friday-banner-2025-copy-icon{opacity:.8;transition:opacity .3s}#ays-quiz-dicount-black-friday-month-main .ays-quiz-discount-black-friday-banner-banner-2025-coupon-box:hover .ays-quiz-discount-black-friday-banner-2025-copy-icon,.ays-quiz-discount-black-friday-banner-2025-copy-notification.show{opacity:1}.ays-quiz-discount-black-friday-banner-2025-copy-notification{position:fixed;top:50%;left:50%;transform:translate(-50%,-50%);background:rgba(0,0,0,.8);color:#fff;padding:12px 24px;border-radius:8px;font-size:14px;z-index:10000;opacity:0;transition:opacity .3s}@media screen and (max-width:1400px) and (min-width:1200px){div#ays-quiz-dicount-black-friday-month-main .ays-quiz-dicount-black-friday-title-row{font-size:15px}div#ays-quiz-dicount-black-friday-month-main .ays-quiz-dicount-black-friday-text-row{font-size:27px}div#ays-quiz-dicount-black-friday-month-main .ays-quiz-dicount-black-friday-box{width:100%}div#ays-quiz-dicount-black-friday-month-main .ays-quiz-dicount-black-friday-button-buy-now{font-size:13px}div#ays-quiz-dicount-black-friday-month-main .ays-quiz-dicount-black-friday-wrap-box-80{width:80%!important}}@media all and (max-width:1200px){div#ays-quiz-dicount-black-friday-month-main .ays_quiz_dicount_month{background:unset}div#ays-quiz-dicount-black-friday-month-main .ays-quiz-dicount-black-friday-text-row{font-size:30px}div#ays-quiz-dicount-black-friday-month-main .ays-quiz-dicount-black-friday-box{width:100%}div#ays-quiz-dicount-black-friday-month-main .ays-quiz-dicount-black-friday-button-buy-now{font-size:15px}div#ays-quiz-dicount-black-friday-month-main .ays-quiz-dicount-black-friday-wrap-box-80{width:80%!important}}@media all and (max-width:1200px) and (min-width:1150px){div#ays-quiz-dicount-black-friday-month-main .ays-quiz-dicount-black-friday-title-row{font-size:15px}div#ays-quiz-dicount-black-friday-month-main .ays-quiz-dicount-black-friday-button-buy-now{font-size:10px}}@media all and (max-width:1150px){div#ays-quiz-dicount-black-friday-month-main .ays-quiz-dicount-black-friday-wrap-box-80{width:80%!important}div#ays-quiz-dicount-black-friday-month-main .ays-quiz-dicount-black-friday-box{flex-direction:column}div#ays-quiz-dicount-black-friday-month-main{padding-right:0}div#ays-quiz-dicount-black-friday-month-main .ays_quiz_dicount_month{display:flex;align-items:center;justify-content:space-between;align-content:center;flex-wrap:wrap;flex-direction:column;padding:10px 0}div#ays-quiz-dicount-black-friday-month-main div.ays-quiz-dicount-black-friday-wrap-box{width:100%!important;text-align:center}#ays-quiz-dicount-black-friday-month-main .ays-quiz-dicount-black-friday-title-row,div#ays-quiz-dicount-black-friday-month-main #ays-quiz-countdown-main-container ul{padding:0;font-size:13px}#ays-quiz-dicount-black-friday-month-main .ays-quiz-dicount-black-friday-button-sale,div#ays-quiz-countdown-main-container li{font-size:15px}div#ays-quiz-countdown-main-container li span{font-size:25px}div#ays-quiz-dicount-black-friday-month-main div.ays-quiz-dicount-black-friday-wrap-text-box{position:unset}#ays-quiz-countdown-main-container #ays-quiz-countdown-headline{font-size:15px;font-weight:600}#ays-quiz-countdown-main-container ul{font-weight:500}#ays-quiz-countdown-main-container li span{font-size:20px}#ays-quiz-dicount-black-friday-month-main .ays-button{margin:0 auto!important}div#ays-quiz-dicount-black-friday-month-main.ays_quiz_dicount_info.notice{background-position:bottom right;background-repeat:no-repeat;background-size:contain;display:flex;justify-content:center;background-image:linear-gradient(45deg,#1e101d,#c60af4)}div#ays-quiz-dicount-black-friday-month-main .ays-quiz-dicount-black-friday-wrap-box.ays-buy-now-button-box{justify-content:center}div#ays-quiz-dicount-black-friday-month-main .ays-quiz-dicount-black-friday-wrap-box .ays-buy-now-button{font-size:14px;padding:5px 10px}div#ays-quiz-dicount-black-friday-month-main .ays-quiz-dicount-black-friday-button-buy-now{padding:10px 18px;font-size:15px}}@media all and (max-width:768px){#ays-quiz-dicount-black-friday-month-main{display:none!important}}
                ';
            $content[] = '</style>';

            $content = implode( '', $content );

            echo $content;
        }
    }

    // New Mega Bundle 2024
    public function ays_quiz_new_mega_bundle_message_2024($ishmar){
        if($ishmar == 0 ){
            $content = array();
            $content[] = '<div id="ays-quiz-new-mega-bundle-dicount-month-main-2024" class="notice notice-success is-dismissible ays_quiz_dicount_info">';
                $content[] = '<div id="ays-quiz-dicount-month" class="ays_quiz_dicount_month">';

                    $content[] = '<div class="ays-quiz-discount-box-sale-image"></div>';
                    $content[] = '<div class="ays-quiz-dicount-wrap-box ays-quiz-dicount-wrap-text-box">';

                        $content[] = '<div class="ays-quiz-dicount-wrap-text-box-texts">';
                            $content[] = '<div>
                                            <a href="https://ays-pro.com/mega-bundle?utm_source=dashboard&utm_medium=quiz-free&utm_campaign=mega-bundle-sale-banner" target="_blank" style="color:#30499B;">
                                            <span class="ays-quiz-new-mega-bundle-limited-text">Limited</span> Offer for Mega bundle </a> <br> 
                                            
                                            <span style="font-size: 19px ;">(Quiz + Survey + Poll)</span>
                                          </div>';
                        $content[] = '</div>';

                        $content[] = '<div style="font-size: 17px;">';
                            $content[] = '<img style="width: 24px;height: 24px;" src="' . esc_attr(AYS_QUIZ_ADMIN_URL) . '/images/icons/guarantee-new.png">';
                            $content[] = '<span style="padding-left: 4px; font-size: 14px; font-weight: 600;"> 30 Day Money Back Guarantee</span>';
                            
                        $content[] = '</div>';

                       

                        $content[] = '<div style="position: absolute;right: 10px;bottom: 1px;" class="ays-quiz-dismiss-buttons-container-for-form">';

                            $content[] = '<form action="" method="POST">';
                                $content[] = '<div id="ays-quiz-dismiss-buttons-content">';
                                    if( current_user_can( 'manage_options' ) ){
                                        $content[] = '<button class="btn btn-link ays-button" name="ays_quiz_sale_btn" style="height: 32px; margin-left: 0;padding-left: 0; color: #30499B;
                                        ">Dismiss ad</button>';
                                        $content[] = wp_nonce_field( $this->plugin_name . '-sale-banner' ,  $this->plugin_name . '-sale-banner' );
                                    }
                                $content[] = '</div>';
                            $content[] = '</form>';
                            
                        $content[] = '</div>';

                    $content[] = '</div>';

                    $content[] = '<div class="ays-quiz-dicount-wrap-box ays-quiz-dicount-wrap-countdown-box">';

                        $content[] = '<div id="ays-quiz-maker-countdown-main-container">';
                            $content[] = '<div class="ays-quiz-maker-countdown-container">';

                                $content[] = '<div id="ays-quiz-countdown">';

                                    $content[] = '<div style="font-weight: 500;">';
                                        $content[] = __( "Offer ends in:", "quiz-maker" );
                                    $content[] = '</div>';

                                    $content[] = '<ul>';
                                        $content[] = '<li><span id="ays-quiz-countdown-days"></span>days</li>';
                                        $content[] = '<li><span id="ays-quiz-countdown-hours"></span>Hours</li>';
                                        $content[] = '<li><span id="ays-quiz-countdown-minutes"></span>Minutes</li>';
                                        $content[] = '<li><span id="ays-quiz-countdown-seconds"></span>Seconds</li>';
                                    $content[] = '</ul>';
                                $content[] = '</div>';

                                $content[] = '<div id="ays-quiz-countdown-content" class="emoji">';
                                    $content[] = '<span>🚀</span>';
                                    $content[] = '<span>⌛</span>';
                                    $content[] = '<span>🔥</span>';
                                    $content[] = '<span>💣</span>';
                                $content[] = '</div>';

                            $content[] = '</div>';
                        $content[] = '</div>';
                            
                    $content[] = '</div>';

                    $content[] = '<div class="ays-quiz-dicount-wrap-box ays-quiz-dicount-wrap-button-box">';
                        $content[] = '<a href="https://ays-pro.com/mega-bundle?utm_source=dashboard&utm_medium=quiz-free&utm_campaign=mega-bundle-sale-banner" class="button button-primary ays-button" id="ays-button-top-buy-now" target="_blank">' . __( 'Buy Now !', "quiz-maker" ) . '</a>';
                        $content[] = '<span >' . __( 'One-time payment', "quiz-maker" ) . '</span>';
                    $content[] = '</div>';
                $content[] = '</div>';
            $content[] = '</div>';

            $content = implode( '', $content );
            echo html_entity_decode(esc_html( $content ));
        }        
    }

    // Black Friday 2024
    public function ays_quiz_black_friday_message_2024($ishmar){
        if($ishmar == 0 ){
            $content = array();

            $content[] = '<div id="ays-quiz-black-friday-bundle-dicount-month-main" class="notice notice-success is-dismissible ays_quiz_dicount_info">';
                $content[] = '<div id="ays-quiz-dicount-month" class="ays_quiz_dicount_month">';

                    $content[] = '<div class="ays-quiz-dicount-wrap-box ays-quiz-dicount-wrap-countdown-box">';

                        $content[] = '<div id="ays-quiz-maker-countdown-main-container">';
                            $content[] = '<div class="ays-quiz-maker-countdown-container">';

                                $content[] = '<div id="ays-quiz-countdown">';

                                    $content[] = '<div>';
                                        $content[] = __( "Offer ends in:", 'quiz-maker' );
                                    $content[] = '</div>';

                                    $content[] = '<ul>';
                                        $content[] = '<li><span id="ays-quiz-countdown-days"></span>'. __( "Days", 'quiz-maker' ) .'</li>';
                                        $content[] = '<li><span id="ays-quiz-countdown-hours"></span>'. __( "Hours", 'quiz-maker' ) .'</li>';
                                        $content[] = '<li><span id="ays-quiz-countdown-minutes"></span>'. __( "Minutes", 'quiz-maker' ) .'</li>';
                                        $content[] = '<li><span id="ays-quiz-countdown-seconds"></span>'. __( "Seconds", 'quiz-maker' ) .'</li>';
                                    $content[] = '</ul>';
                                $content[] = '</div>';

                                $content[] = '<div id="ays-quiz-countdown-content" class="emoji">';
                                    $content[] = '<span>🚀</span>';
                                    $content[] = '<span>⌛</span>';
                                    $content[] = '<span>🔥</span>';
                                    $content[] = '<span>💣</span>';
                                $content[] = '</div>';

                            $content[] = '</div>';
                        $content[] = '</div>';
                            
                    $content[] = '</div>';

                    $content[] = '<div class="ays-quiz-dicount-wrap-box ays-quiz-dicount-wrap-text-box">';
                        $content[] = '<div>';

                            $content[] = '<span class="ays-quiz-black-friday-bundle-title">';
                                $content[] = __( "<span><a href='https://ays-pro.com/mega-bundle?utm_source=dashboard&utm_medium=quiz-free&utm_campaign=black-friday-mega-bundle-sale-banner' class='ays-quiz-black-friday-bundle-title-link' target='_blank'>Black Friday Sale</a></span>", 'quiz-maker' );
                            $content[] = '</span>';

                            $content[] = '</br>';

                            $content[] = '<span class="ays-quiz-black-friday-bundle-desc">';
                                $content[] = '<a class="ays-quiz-black-friday-bundle-desc" href="https://ays-pro.com/mega-bundle?utm_source=dashboard&utm_medium=quiz-free&utm_campaign=black-friday-mega-bundle-sale-banner" class="ays-quiz-black-friday-bundle-title-link" target="_blank">';
                                    $content[] = __( "50% OFF", 'quiz-maker' );
                                $content[] = '</a>';
                            $content[] = '</span>';
                        $content[] = '</div>';

                        $content[] = '<div style="position: absolute;right: 10px;bottom: 1px;" class="ays-quiz-dismiss-buttons-container-for-form">';

                            $content[] = '<form action="" method="POST">';
                                $content[] = '<div id="ays-quiz-dismiss-buttons-content">';
                                if( current_user_can( 'manage_options' ) ){
                                    $content[] = '<button class="btn btn-link ays-button" name="ays_quiz_sale_btn" style="height: 32px; margin-left: 0;padding-left: 0">'. __( "Dismiss ad", 'quiz-maker' ) .'</button>';
                                    $content[] = wp_nonce_field( AYS_QUIZ_NAME . '-sale-banner' ,  AYS_QUIZ_NAME . '-sale-banner' );
                                }
                                $content[] = '</div>';
                            $content[] = '</form>';
                            
                        $content[] = '</div>';

                    $content[] = '</div>';

                    $content[] = '<div class="ays-quiz-dicount-wrap-box ays-quiz-dicount-wrap-text-box">';
                        $content[] = '<span class="ays-quiz-black-friday-bundle-title">';
                            $content[] = '<a class="ays-quiz-black-friday-bundle-title-link" href="https://ays-pro.com/mega-bundle?utm_source=dashboard&utm_medium=quiz-free&utm_campaign=black-friday-mega-bundle-sale-banner" target="_blank">';
                                $content[] = __( 'Mega Bundle', 'quiz-maker' );
                            $content[] = '</a>';
                        $content[] = '</span>';
                    $content[] = '</div>';

                    $content[] = '<div class="ays-quiz-dicount-wrap-box ays-quiz-dicount-wrap-button-box">';
                        $content[] = '<a href="https://ays-pro.com/mega-bundle?utm_source=dashboard&utm_medium=quiz-free&utm_campaign=black-friday-mega-bundle-sale-banner" class="button button-primary ays-button" id="ays-button-top-buy-now" target="_blank">' . __( 'Get Your Deal', 'quiz-maker' ) . '</a>';
                        $content[] = '<span class="ays-quiz-dicount-one-time-text">';
                            $content[] = __( "One-time payment", 'quiz-maker' );
                        $content[] = '</span>';
                    $content[] = '</div>';
                $content[] = '</div>';
            $content[] = '</div>';

            $content = implode( '', $content );
            echo $content;
        }
    }

    // Christmas Top Banner 2024
    public function ays_quiz_christmas_top_message_2024($ishmar){
        if($ishmar == 0 ){
            $content = array();

            $quiz_cta_button_link = esc_url('https://ays-pro.com/wordpress/quiz-maker?utm_source=dashboard&utm_medium=quiz-free&utm_campaign=christmas-sale-banner' . AYS_QUIZ_UTM_VERSION);

            $content[] = '<div id="ays-quiz-christmas-top-bundle-dicount-month-main" class="notice notice-success is-dismissible ays_quiz_dicount_info">';
                $content[] = '<div id="ays-quiz-dicount-month" class="ays_quiz_dicount_month">';

                    $content[] = '<div class="ays-quiz-dicount-wrap-box ays-quiz-dicount-wrap-countdown-box">';

                        $content[] = '<div id="ays-quiz-maker-countdown-main-container">';
                            $content[] = '<div class="ays-quiz-maker-countdown-container">';

                                $content[] = '<div id="ays-quiz-countdown">';

                                    $content[] = '<div>';
                                        $content[] = __( "Offer ends in:", 'quiz-maker' );
                                    $content[] = '</div>';

                                    $content[] = '<ul>';
                                        $content[] = '<li><span id="ays-quiz-countdown-days"></span>'. __( "Days", 'quiz-maker' ) .'</li>';
                                        $content[] = '<li><span id="ays-quiz-countdown-hours"></span>'. __( "Hours", 'quiz-maker' ) .'</li>';
                                        $content[] = '<li><span id="ays-quiz-countdown-minutes"></span>'. __( "Minutes", 'quiz-maker' ) .'</li>';
                                        $content[] = '<li><span id="ays-quiz-countdown-seconds"></span>'. __( "Seconds", 'quiz-maker' ) .'</li>';
                                    $content[] = '</ul>';
                                $content[] = '</div>';

                                $content[] = '<div id="ays-quiz-countdown-content" class="emoji">';
                                    $content[] = '<span>🚀</span>';
                                    $content[] = '<span>⌛</span>';
                                    $content[] = '<span>🔥</span>';
                                    $content[] = '<span>💣</span>';
                                $content[] = '</div>';

                            $content[] = '</div>';
                        $content[] = '</div>';
                            
                    $content[] = '</div>';

                    $content[] = '<div class="ays-quiz-dicount-wrap-box ays-quiz-dicount-wrap-text-box">';
                        $content[] = '<div>';

                            $content[] = '<span class="ays-quiz-christmas-top-bundle-title">';
                                $content[] = sprintf(
                                    /* translators: 1: opening <span><a> tag, 2: closing </a></span> tag */
                                    wp_kses_post(
                                        __( '%1$s Christmas Sale %2$s', 'quiz-maker' )
                                    ),
                                    '<span><a href="' . esc_url( $quiz_cta_button_link ) . '" class="ays-quiz-christmas-top-bundle-title-link" target="_blank">',
                                    '</a></span>'
                                );
                            $content[] = '</span>';

                            $content[] = '</br>';

                            $content[] = '<span class="ays-quiz-christmas-top-bundle-desc">';
                                $content[] = '<a class="ays-quiz-christmas-top-bundle-desc" href="'. $quiz_cta_button_link .'" class="ays-quiz-christmas-top-bundle-title-link" target="_blank">';
                                    $content[] = __( "20% Extra OFF", 'quiz-maker' );
                                $content[] = '</a>';
                            $content[] = '</span>';
                        $content[] = '</div>';

                        $content[] = '<div style="position: absolute;right: 10px;bottom: 1px;" class="ays-quiz-dismiss-buttons-container-for-form">';

                            $content[] = '<form action="" method="POST">';
                                $content[] = '<div id="ays-quiz-dismiss-buttons-content">';
                                if( current_user_can( 'manage_options' ) ){
                                    $content[] = '<button class="btn btn-link ays-button" name="ays_quiz_sale_btn" style="height: 32px; margin-left: 0;padding-left: 0">'. __( "Dismiss ad", 'quiz-maker' ) .'</button>';
                                    $content[] = wp_nonce_field( AYS_QUIZ_NAME . '-sale-banner' ,  AYS_QUIZ_NAME . '-sale-banner' );
                                }
                                $content[] = '</div>';
                            $content[] = '</form>';
                            
                        $content[] = '</div>';

                    $content[] = '</div>';

                    $content[] = '<div class="ays-quiz-dicount-wrap-box ays-quiz-christmas-top-bundle-coupon-text-box">';
                        $content[] = '<div class="ays-quiz-christmas-top-bundle-coupon-row">';
                            $content[] = 'xmas20off';
                        $content[] = '</div>';

                        $content[] = '<div class="ays-quiz-christmas-top-bundle-text-row">';
                            $content[] = __( '20% Extra Discount Coupon', 'quiz-maker' );
                        $content[] = '</div>'; 
                    $content[] = '</div>';

                    $content[] = '<div class="ays-quiz-dicount-wrap-box ays-quiz-dicount-wrap-button-box">';
                        $content[] = '<a href="'. $quiz_cta_button_link .'" class="button button-primary ays-button" id="ays-button-top-buy-now" target="_blank">' . __( 'Get Your Deal', 'quiz-maker' ) . '</a>';
                        $content[] = '<span class="ays-quiz-dicount-one-time-text">';
                            $content[] = __( "One-time payment", 'quiz-maker' );
                        $content[] = '</span>';
                    $content[] = '</div>';
                $content[] = '</div>';
            $content[] = '</div>';

            $content = implode( '', $content );
            echo $content;
        }
    }

    // New Mega Bundle
    public function ays_quiz_new_mega_bundle_message_2025($ishmar){
        if($ishmar == 0 ){
            $content = array();

            $quiz_cta_button_link = esc_url('https://ays-pro.com/mega-bundle?utm_source=dashboard&utm_medium=quiz-free&utm_campaign=mega-bundle-2025-sale-banner-' . AYS_QUIZ_VERSION);

            $content[] = '<div id="ays-quiz-new-mega-bundle-2025-dicount-month-main" class="notice notice-success is-dismissible ays_quiz_dicount_info">';
                $content[] = '<div id="ays-quiz-dicount-month" class="ays_quiz_dicount_month">';

                    $content[] = '<div class="ays-quiz-dicount-wrap-box ays-quiz-dicount-wrap-text-box">';
                        $content[] = '<div>';

                            $content[] = '<span class="ays-quiz-new-mega-bundle-2025-title">';
                                $content[] = sprintf(
                                    /* translators: 1: opening <span><a> tag, 2: closing </a></span> tag */
                                    wp_kses_post(
                                        __( '%1$s Mega Bundle %2$s (Quiz + Survey + Poll)', 'quiz-maker' )
                                    ),
                                    '<span><a href="' . esc_url( $quiz_cta_button_link ) . '" target="_blank" style="color:#ffffff; text-decoration: underline;">',
                                    '</a></span>'
                                );
                            $content[] = '</span>';

                            $content[] = '</br>';

                            $content[] = '<span class="ays-quiz-new-mega-bundle-2025-desc">';
                                $content[] = __( "30 Day Money Back Guarantee", 'quiz-maker' );
                            $content[] = '</span>';
                        $content[] = '</div>';

                        $content[] = '<div>';
                                $content[] = '<img class="ays-quiz-new-mega-bundle-guaranteeicon" src="' . AYS_QUIZ_ADMIN_URL . '/images/ays-quiz-mega-bundle-2025-discount.svg" style="width: 80px;">';
                        $content[] = '</div>';

                        $content[] = '<div style="position: absolute;right: 10px;bottom: 1px;" class="ays-quiz-dismiss-buttons-container-for-form">';

                            $content[] = '<form action="" method="POST">';
                                $content[] = '<div id="ays-quiz-dismiss-buttons-content">';
                                if( current_user_can( 'manage_options' ) ){
                                    $content[] = '<button class="btn btn-link ays-button" name="ays_quiz_sale_btn" style="height: 32px; margin-left: 0;padding-left: 0">'. __( "Dismiss ad", 'quiz-maker' ) .'</button>';
                                    $content[] = wp_nonce_field( AYS_QUIZ_NAME . '-sale-banner' ,  AYS_QUIZ_NAME . '-sale-banner' );
                                }
                                $content[] = '</div>';
                            $content[] = '</form>';
                            
                        $content[] = '</div>';

                    $content[] = '</div>';

                    $content[] = '<div class="ays-quiz-dicount-wrap-box ays-quiz-dicount-wrap-countdown-box">';

                        $content[] = '<div id="ays-quiz-maker-countdown-main-container">';
                            $content[] = '<div class="ays-quiz-maker-countdown-container">';

                                $content[] = '<div id="ays-quiz-countdown">';

                                    $content[] = '<ul>';
                                        $content[] = '<li><span id="ays-quiz-countdown-days"></span>'. __( "Days", 'quiz-maker' ) .'</li>';
                                        $content[] = '<li><span id="ays-quiz-countdown-hours"></span>'. __( "Hours", 'quiz-maker' ) .'</li>';
                                        $content[] = '<li><span id="ays-quiz-countdown-minutes"></span>'. __( "Minutes", 'quiz-maker' ) .'</li>';
                                        $content[] = '<li><span id="ays-quiz-countdown-seconds"></span>'. __( "Seconds", 'quiz-maker' ) .'</li>';
                                    $content[] = '</ul>';
                                $content[] = '</div>';

                                $content[] = '<div id="ays-quiz-countdown-content" class="emoji">';
                                    $content[] = '<span></span>';
                                    $content[] = '<span></span>';
                                    $content[] = '<span></span>';
                                    $content[] = '<span></span>';
                                $content[] = '</div>';

                            $content[] = '</div>';
                        $content[] = '</div>';
                            
                    $content[] = '</div>';

                    $content[] = '<div class="ays-quiz-dicount-wrap-box ays-quiz-dicount-wrap-button-box">';
                        $content[] = '<a href="'. $quiz_cta_button_link .'" class="button button-primary ays-button" id="ays-button-top-buy-now" target="_blank">' . __( 'Buy Now', 'quiz-maker' ) . '</a>';
                        $content[] = '<span class="ays-quiz-dicount-one-time-text">';
                            $content[] = __( "One-time payment", 'quiz-maker' );
                        $content[] = '</span>';
                    $content[] = '</div>';
                $content[] = '</div>';
            $content[] = '</div>';

            $content = implode( '', $content );
            echo wp_kses_post($content);
        }
    }

    // Fox LMS Pro Banner
    public function ays_quiz_discounted_licenses_banner_message($ishmar){
        if($ishmar == 0 ){
            $content = array();

            $date = time() + (int) ( get_option( 'gmt_offset' ) * HOUR_IN_SECONDS);
            $now_date = date('M d, Y H:i:s', $date);

            $start_date = strtotime('2025-09-08');
            $end_date = strtotime('2025-09-30');
            $diff_end = $end_date - $date;

            $style_attr = '';
            if( $diff_end < 0 ){
                $style_attr = 'style="display:none;"';
            }

            $total_licenses = 50;
            $progression_pattern = array(3, 2, 1, 4, 2, 3, 1, 2, 4, 3, 2, 1, 3, 2, 4, 1, 3, 2, 2, 3, 1, 2);
            $days_passed = floor(($date - $start_date) / (24 * 60 * 60));
            $used_licenses = 0;

            for ($i = 0; $i < min($days_passed, count($progression_pattern)); $i++) {
                $used_licenses += $progression_pattern[$i];
            }
            $used_licenses = min($used_licenses, $total_licenses);
            $remaining_licenses = $total_licenses - $used_licenses;
            $progress_percentage = ($used_licenses / $total_licenses) * 100;

            $ays_quiz_cta_button_link = esc_url('https://ays-pro.com/wordpress/quiz-maker?utm_source=dashboard&utm_medium=quiz-free&utm_campaign=quiz-maker-license-banner-' . AYS_QUIZ_UTM_VERSION);

            $content[] = '<div id="ays-quiz-progress-banner-main" class="ays-quiz-progress-banner-main ays_quiz_dicount_info ays-quiz-admin-notice notice notice-success is-dismissible" ' . $style_attr . '>';
                $content[] = '<div class="ays-quiz-progress-banner-content">';
                    $content[] = '<div class="ays-quiz-progress-banner-left">';
                        $content[] = '<div class="ays-quiz-progress-banner-icon">';
                            $content[] = '<svg width="48" height="48" viewBox="0 0 48 48" fill="none" xmlns="http://www.w3.org/2000/svg">
                                            <path d="M1.33325 22.6668L11.9999 13.3335L33.3333 14.6668L34.6666 36.0002L25.3333 46.6668C25.3333 46.6668 25.3346 38.6682 17.3333 30.6668C9.33192 22.6655 1.33325 22.6668 1.33325 22.6668Z" fill="#A0041E"/>
                                            <path d="M1.29739 46.6665C1.29739 46.6665 1.24939 36.0278 5.27739 31.9998C9.30539 27.9718 20.0001 28.2492 20.0001 28.2492C20.0001 28.2492 19.9987 38.6665 15.9987 42.6665C11.9987 46.6665 1.29739 46.6665 1.29739 46.6665Z" fill="#FFAC33"/>
                                            <path d="M11.9986 41.3332C14.9441 41.3332 17.3319 38.9454 17.3319 35.9998C17.3319 33.0543 14.9441 30.6665 11.9986 30.6665C9.0531 30.6665 6.66528 33.0543 6.66528 35.9998C6.66528 38.9454 9.0531 41.3332 11.9986 41.3332Z" fill="#FFCC4D"/>
                                            <path d="M47.9986 0C47.9986 0 34.6653 0 18.6653 13.3333C10.6653 20 10.6653 32 13.3319 34.6667C15.9986 37.3333 27.9986 37.3333 34.6653 29.3333C47.9986 13.3333 47.9986 0 47.9986 0Z" fill="#55ACEE"/>
                                            <path d="M35.9987 6.6665C33.8347 6.6665 31.9814 7.96117 31.144 9.81317C31.8134 9.5105 32.5507 9.33317 33.332 9.33317C36.2774 9.33317 38.6654 11.7212 38.6654 14.6665C38.6654 15.4478 38.488 16.1852 38.1867 16.8532C40.0387 16.0172 41.332 14.1638 41.332 11.9998C41.332 9.0545 38.944 6.6665 35.9987 6.6665Z" fill="black"/>
                                            <path d="M10.6667 37.3332C10.6667 37.3332 10.6667 31.9998 12.0001 30.6665C13.3334 29.3332 29.3347 16.0012 30.6667 17.3332C31.9987 18.6652 18.6654 34.6665 17.3321 35.9998C15.9987 37.3332 10.6667 37.3332 10.6667 37.3332Z" fill="#A0041E"/>
                                            </svg>';
                        $content[] = '</div>';
                        $content[] = '<div class="ays-quiz-progress-banner-text">';
                            $content[] = '<h2 class="ays-quiz-progress-banner-title">' . sprintf( __('Get the Pro Version of %s Quiz Maker%s – 20%% OFF', 'quiz-maker'), '<a href="'. $ays_quiz_cta_button_link .'" target="_blank">', '</a>' ) . '</h2>';
                            $content[] = '<p class="ays-quiz-progress-banner-subtitle">' . __('Unlock advanced features + 30 day Money Back Guarantee', 'quiz-maker') . '</p>';
                        $content[] = '</div>';
                    $content[] = '</div>';
                    
                    $content[] = '<div class="ays-quiz-progress-banner-center">';
                        $content[] = '<div class="ays-quiz-progress-banner-coupon">';
                            $content[] = '<div class="ays-quiz-progress-banner-coupon-box" onclick="copyToClipboard(\'FREE2PRO20\')" title="' . __('Click to copy', 'quiz-maker') . '">';
                                $content[] = '<span class="ays-quiz-progress-banner-coupon-text">FREE2PRO20</span>';
                                $content[] = '<svg width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg" class="ays-quiz-progress-banner-copy-icon">';
                                    $content[] = '<path d="M13.5 2.5H6.5C5.67 2.5 5 3.17 5 4V10C5 10.83 5.67 11.5 6.5 11.5H13.5C14.33 11.5 15 10.83 15 10V4C15 3.17 14.33 2.5 13.5 2.5ZM13.5 10H6.5V4H13.5V10ZM2.5 6.5V12.5C2.5 13.33 3.17 14 4 14H10V12.5H4V6.5H2.5Z" fill="white"/>';
                                $content[] = '</svg>';
                            $content[] = '</div>';
                        $content[] = '</div>';
                        
                        $content[] = '<div class="ays-quiz-progress-banner-progress">';
                            $content[] = '<p class="ays-quiz-progress-banner-progress-text">' . __('Only', 'quiz-maker') . ' <span id="remaining-licenses">' . $remaining_licenses . '</span> ' . __('of 50 discounted licenses left', 'quiz-maker') . '</p>';
                            $content[] = '<div class="ays-quiz-progress-banner-progress-bar">';
                                $content[] = '<div class="ays-quiz-progress-banner-progress-fill" id="progress-fill" style="width: ' . $progress_percentage . '%;"></div>';
                            $content[] = '</div>';
                        $content[] = '</div>';
                    $content[] = '</div>';
                    
                    $content[] = '<div class="ays-quiz-progress-banner-right">';
                        $content[] = '<a href="'. $ays_quiz_cta_button_link .'" class="ays-quiz-progress-banner-upgrade" target="_blank">';
                        $content[] = '<svg width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">';
                            $content[] = '<path d="M14.6392 6.956C14.5743 6.78222 14.4081 6.66667 14.2223 6.66667H8.85565L11.9512 0.648C12.0485 0.458667 11.9983 0.227111 11.8308 0.0955556C11.7499 0.0315556 11.6525 0 11.5556 0C11.4521 0 11.3485 0.0364444 11.2654 0.108L8.00009 2.928L1.48765 8.55244C1.3472 8.67378 1.29653 8.86978 1.36142 9.04356C1.42631 9.21733 1.59209 9.33333 1.77787 9.33333H7.14454L4.04898 15.352C3.95165 15.5413 4.00187 15.7729 4.16942 15.9044C4.25031 15.9684 4.34765 16 4.44453 16C4.54809 16 4.65165 15.9636 4.73476 15.892L8.00009 13.072L14.5125 7.44756C14.6534 7.32622 14.7036 7.13022 14.6392 6.956Z" fill="white"/>';
                        $content[] = '</svg>';
                         $content[] = ' ' . __('Upgrade Now', 'quiz-maker');
                        $content[] = '</a>';
                    $content[] = '</div>';
                $content[] = '</div>';
                
                if( current_user_can( 'manage_options' ) ){
                $content[] = '<div id="ays-quiz-dismiss-buttons-content">';
                    $content[] = '<form action="" method="POST" style="position: absolute; bottom: 0; right: 0; color: #fff;">';
                            $content[] = '<button class="btn btn-link ays-button" name="ays_quiz_sale_btn" style="color: darkgrey; font-size: 11px;">'. __( "Dismiss ad", 'quiz-maker' ) .'</button>';
                            $content[] = wp_nonce_field( AYS_QUIZ_NAME . '-sale-banner' ,  AYS_QUIZ_NAME . '-sale-banner' );
                    $content[] = '</form>';
                $content[] = '</div>';
                }
            $content[] = '</div>';

            // Fox LMS Pro Banner Styles
            $content[] = '<style id="ays-quiz-progress-banner-styles-inline-css">';
            $content[] = '
                .ays-quiz-progress-banner-main {
                    background: linear-gradient(135deg, #6344ED 0%, #8C2ABE 100%);
                    padding: 20px 30px;
                    border-radius: 16px;
                    color: white;
                    position: relative;
                    margin: 20px 0;
                    box-shadow: 0 8px 32px rgba(99, 68, 237, 0.3);
                    border: 0;
                }

                .ays-quiz-progress-banner-main .ays-quiz-progress-banner-content {
                    display: flex;
                    align-items: center;
                    justify-content: space-between;
                    gap: 30px;
                }

                .ays-quiz-progress-banner-main .ays-quiz-progress-banner-left {
                    display: flex;
                    align-items: center;
                    gap: 20px;
                    flex: 1;
                }

                .ays-quiz-progress-banner-main .ays-quiz-progress-banner-center {
                    display: flex;
                    align-items: center;
                    gap: 15px;
                    flex: 1;
                }

                .ays-quiz-progress-banner-main .ays-quiz-progress-banner-right {
                    display: flex;
                    align-items: center;
                    gap: 20px;
                    flex-shrink: 0;
                }

                .ays-quiz-progress-banner-main .ays-quiz-progress-banner-icon {
                    font-size: 32px;
                    filter: drop-shadow(0 2px 8px rgba(0, 0, 0, 0.2));
                }

                .ays-quiz-progress-banner-main .ays-quiz-progress-banner-title {
                    font-size: 21px;
                    font-weight: 700;
                    margin: 0 0 8px 0;
                    line-height: 1.2;
                    color: #fff;
                }

                .ays-quiz-progress-banner-main .ays-quiz-progress-banner-title a {
                    text-decoration: underline;
                    color: #fff;
                }

                .ays-quiz-progress-banner-main .ays-quiz-progress-banner-subtitle {
                    font-size: 16px;
                    margin: 0;
                    opacity: 0.9;
                    font-weight: 400;
                }

                .ays-quiz-progress-banner-main .ays-quiz-progress-banner-coupon {
                    margin-bottom: 5px;
                }

                .ays-quiz-progress-banner-main .ays-quiz-progress-banner-coupon-box {
                    border: 2px dotted rgba(255, 255, 255, 0.6);
                    padding: 8px 16px;
                    border-radius: 8px;
                    background: rgba(255, 255, 255, 0.1);
                    cursor: pointer;
                    transition: all 0.3s ease;
                    display: flex;
                    align-items: center;
                    gap: 8px;
                    backdrop-filter: blur(10px);
                }

                .ays-quiz-progress-banner-main .ays-quiz-progress-banner-coupon-box:hover {
                    background: rgba(255, 255, 255, 0.2);
                    border-color: rgba(255, 255, 255, 0.8);
                    transform: translateY(-1px);
                }

                .ays-quiz-progress-banner-main .ays-quiz-progress-banner-coupon-text {
                    font-size: 16px;
                    font-weight: 700;
                    letter-spacing: 1px;
                    color: #fff;
                    font-family: monospace;
                }

                .ays-quiz-progress-banner-main .ays-quiz-progress-banner-copy-icon {
                    opacity: 0.8;
                    transition: opacity 0.3s ease;
                }

                .ays-quiz-progress-banner-main .ays-quiz-progress-banner-coupon-box:hover .ays-quiz-progress-banner-copy-icon {
                    opacity: 1;
                }

                .ays-quiz-progress-banner-main .ays-quiz-progress-banner-progress {
                    text-align: center;
                    width: 100%;
                }

                .ays-quiz-progress-banner-main .ays-quiz-progress-banner-progress-text {
                    font-size: 14px;
                    margin: 0 0 10px 0;
                    opacity: 0.9;
                }

                .ays-quiz-progress-banner-main .ays-quiz-progress-banner-progress-bar {
                    width: 300px;
                    height: 10px;
                    background: rgba(255, 255, 255, 0.2);
                    border-radius: 4px;
                    overflow: hidden;
                    margin: 0 auto;
                }

                .ays-quiz-progress-banner-main .ays-quiz-progress-banner-progress-fill {
                    height: 100%;
                    background: linear-gradient(90deg, #4ADE80 0%, #22C55E 100%);
                    border-radius: 4px;
                    transition: width 0.8s ease;
                    width: 70%;
                }

                .ays-quiz-progress-banner-main .ays-quiz-progress-banner-upgrade {
                    background: linear-gradient(135deg, #F59E0B 0%, #F97316 100%);
                    color: white;
                    border: none;
                    padding: 12px 24px;
                    border-radius: 8px;
                    font-size: 16px;
                    font-weight: 600;
                    cursor: pointer;
                    transition: all 0.3s ease;
                    box-shadow: 0 4px 16px rgba(245, 158, 11, 0.4);
                    text-decoration: none;
                    display: inline-flex;
                    align-items: center;
                    gap: 8px;
                }

                .ays-quiz-progress-banner-main .ays-quiz-progress-banner-upgrade:hover {
                    transform: translateY(-2px);
                    box-shadow: 0 6px 20px rgba(245, 158, 11, 0.6);
                    text-decoration: none;
                    color: white;
                }

                .ays-quiz-progress-banner-main .notice-dismiss:before {
                    color: #fff;
                }

                /* Copy notification */
                .ays-quiz-copy-notification {
                    position: fixed;
                    top: 50%;
                    left: 50%;
                    transform: translate(-50%, -50%);
                    background: rgba(0, 0, 0, 0.8);
                    color: white;
                    padding: 12px 24px;
                    border-radius: 8px;
                    font-size: 14px;
                    z-index: 10000;
                    opacity: 0;
                    transition: opacity 0.3s ease;
                }

                .ays-quiz-copy-notification.show {
                    opacity: 1;
                }

                @media (max-width: 1400px) {
                    .ays-quiz-progress-banner-main .ays-quiz-progress-banner-center {
                        flex-direction: column;
                    }
                }

                @media (max-width: 1200px) {
                    .ays-quiz-progress-banner-main .ays-quiz-progress-banner-content {
                        flex-direction: column;
                        gap: 20px;
                    }

                    .ays-quiz-progress-banner-main .ays-quiz-progress-banner-left {
                        width: 100%;
                        justify-content: center;
                        text-align: center;
                        flex-direction: column;
                    }

                    .ays-quiz-progress-banner-main .ays-quiz-progress-banner-center {
                        width: 100%;
                    }

                    .ays-quiz-progress-banner-main .ays-quiz-progress-banner-right {
                        width: 100%;
                        justify-content: center;
                    }
                }

                @media (max-width: 768px) {
                    #ays-quiz-progress-banner-main {
                        display: none !important;
                    }

                    .ays-quiz-progress-banner-main {
                        padding: 15px 20px;
                        margin: 15px 0;
                    }
                    
                    .ays-quiz-progress-banner-main .ays-quiz-progress-banner-title {
                        font-size: 18px;
                    }
                    
                    .ays-quiz-progress-banner-main .ays-quiz-progress-banner-subtitle {
                        font-size: 14px;
                    }
                    
                    .ays-quiz-progress-banner-main .ays-quiz-progress-banner-progress-bar {
                        width: 100%;
                        max-width: 280px;
                    }
                    
                    .ays-quiz-progress-banner-main .ays-quiz-progress-banner-upgrade {
                        padding: 10px 20px;
                        font-size: 14px;
                    }
                }

                @media (max-width: 480px) {
                    .ays-quiz-progress-banner-main {
                        padding: 12px 15px;
                    }
                    
                    .ays-quiz-progress-banner-main .ays-quiz-progress-banner-coupon-text {
                        font-size: 14px;
                    }
                    
                    .ays-quiz-progress-banner-main .ays-quiz-progress-banner-progress-bar {
                        max-width: 250px;
                    }
                }
            ';

            $content[] = '</style>';

            $content = implode( '', $content );
            echo ($content);
        }
    }

    // Halloween Bundle 2025
    public function ays_quiz_new_halloween_bundle_message_2025($ishmar){
        if($ishmar == 0 ){
            $content = array();

            $date = time() + (int) ( get_option( 'gmt_offset' ) * HOUR_IN_SECONDS);
            $now_date = date('M d, Y H:i:s', $date);

            $start_date = strtotime('2025-09-08 00:00:01');
            $end_date = strtotime('2025-11-07 23:59:59');
            $diff_end = $end_date - $date;

            $style_attr = '';
            if( $diff_end < 0 ){
                $style_attr = 'style="display:none;"';
            }

            $ays_quiz_cta_button_link = esc_url('https://ays-pro.com/wordpress/quiz-maker?utm_source=dashboard&utm_medium=quiz-free&utm_campaign=halloween-deal-banner-' . AYS_QUIZ_UTM_VERSION);

            $content[] = '
                <div id="ays-quiz-halloween-banner-2025-main" class="ays-quiz-halloween-banner-2025-main ays_quiz_dicount_info notice notice-success is-dismissible">
                    <div class="ays-quiz-halloween-banner-2025-content">
                        <div class="ays-quiz-halloween-banner-2025-left">
                            <div class="ays-quiz-halloween-banner-2025-text">
                                <h2 class="ays-quiz-halloween-banner-2025-title">Boo! Grab Your <a href="'. $ays_quiz_cta_button_link .'" class="" target="_blank">Halloween Deal</a> <br/> Before It Vanishes!</h2>
                                <p class="ays-quiz-halloween-banner-2025-subtitle">Don’t get spooked by missing out!<br/> Get 25% discount using the coupon code while the magic lasts!</p>
                            </div>
                        </div>

                        <div class="ays-quiz-halloween-banner-2025-center">';

                        $content[] = '<div id="ays-quiz-halloween-banner-2025-countdown" class="ays-quiz-halloween-banner-2025-countdown" ' . $style_attr . '>';
                            $content[] = '<div class="ays-quiz-halloween-banner-2025-countdown-timer">';
                                $content[] = '<div class="ays-quiz-halloween-banner-2025-countdown-item">';
                                    $content[] = '<div class="ays-quiz-halloween-banner-2025-countdown-value" id="ays-quiz-halloween-banner-2025-days">00</div>';
                                    $content[] = '<div class="ays-quiz-halloween-banner-2025-countdown-label">' . __('days', 'quiz-maker') . '</div>';
                                $content[] = '</div>';
                                $content[] = '<div class="ays-quiz-halloween-banner-2025-countdown-separator">:</div>';
                                $content[] = '<div class="ays-quiz-halloween-banner-2025-countdown-item">';
                                    $content[] = '<div class="ays-quiz-halloween-banner-2025-countdown-value" id="ays-quiz-halloween-banner-2025-hours">00</div>';
                                    $content[] = '<div class="ays-quiz-halloween-banner-2025-countdown-label">' . __('hours', 'quiz-maker') . '</div>';
                                $content[] = '</div>';
                                $content[] = '<div class="ays-quiz-halloween-banner-2025-countdown-separator">:</div>';
                                $content[] = '<div class="ays-quiz-halloween-banner-2025-countdown-item">';
                                    $content[] = '<div class="ays-quiz-halloween-banner-2025-countdown-value" id="ays-quiz-halloween-banner-2025-minutes">00</div>';
                                    $content[] = '<div class="ays-quiz-halloween-banner-2025-countdown-label">' . __('minutes', 'quiz-maker') . '</div>';
                                $content[] = '</div>';
                                $content[] = '<div class="ays-quiz-halloween-banner-2025-countdown-separator">:</div>';
                                $content[] = '<div class="ays-quiz-halloween-banner-2025-countdown-item">';
                                    $content[] = '<div class="ays-quiz-halloween-banner-2025-countdown-value" id="ays-quiz-halloween-banner-2025-seconds">00</div>';
                                    $content[] = '<div class="ays-quiz-halloween-banner-2025-countdown-label">' . __('seconds', 'quiz-maker') . '</div>';
                                $content[] = '</div>';
                            $content[] = '</div>';
                        $content[] = '</div>';

                        $content[] = '</div>
                                                
                        <div class="ays-quiz-halloween-banner-2025-right">
                            <div class="ays-quiz-halloween-banner-2025-pumpkin">
                                <svg width="36" height="36" viewBox="0 0 36 36" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <g clip-path="url(#clip0_177_40)">
                                    <path d="M32.664 8.519C29.364 5.134 23.42 4.75 18 4.75C12.58 4.75 6.636 5.134 3.336 8.519C0.582 11.344 0 15.751 0 19.791C0 25.054 1.982 31.102 6.357 34.035C9.364 36.051 13.95 35.871 18 35.871C22.05 35.871 26.636 36.051 29.643 34.035C34.018 31.101 36 25.054 36 19.791C36 15.751 35.418 11.344 32.664 8.519Z" fill="#F4900C"/>
                                    <path d="M20.783 5.44401C20.852 5.86401 20.561 6.20801 20.136 6.20801H15.685C15.259 6.20801 14.968 5.86401 15.038 5.44401L15.783 0.972008C15.853 0.551008 16.259 0.208008 16.685 0.208008H19.136C19.562 0.208008 19.968 0.552008 20.037 0.972008L20.783 5.44401Z" fill="#3F7123"/>
                                    <path d="M20.6541 21.159L19.0561 18.563C18.7651 18.021 18.3831 17.75 17.9991 17.746C17.6161 17.75 17.2331 18.021 16.9421 18.563L15.3441 21.159C14.7571 22.252 16.2171 22.875 17.9981 22.875C19.7791 22.875 21.2411 22.251 20.6541 21.159ZM30.1621 24.351C30.1171 24.276 30.0361 24.23 29.9481 24.23H29.1071C29.0391 24.23 28.9731 24.258 28.9261 24.307L26.6951 26.641L23.9971 24.472C23.9461 24.431 23.8801 24.414 23.8121 24.419C23.7461 24.426 23.6851 24.46 23.6441 24.513L21.2361 27.575L18.1821 24.309C18.1691 24.295 18.1491 24.292 18.1341 24.281C18.1191 24.271 18.1091 24.254 18.0911 24.247C18.0851 24.245 18.0781 24.247 18.0721 24.245C18.0481 24.238 18.0251 24.24 18.0001 24.24C17.9751 24.24 17.9521 24.238 17.9281 24.246C17.9221 24.248 17.9151 24.245 17.9081 24.248C17.8901 24.255 17.8811 24.272 17.8651 24.282C17.8491 24.292 17.8301 24.295 17.8171 24.309L14.7641 27.575L12.3551 24.513C12.3141 24.46 12.2531 24.426 12.1871 24.419C12.1211 24.413 12.0541 24.431 12.0021 24.472L9.30411 26.641L7.07411 24.307C7.02711 24.258 6.96211 24.23 6.89311 24.23H6.05211C5.96511 24.23 5.88311 24.276 5.83811 24.351C5.79311 24.426 5.79011 24.519 5.83111 24.596L8.58511 29.815C8.61911 29.879 8.6781 29.925 8.7491 29.942C8.8201 29.959 8.8941 29.944 8.9521 29.902L10.9861 28.444L13.9901 32.077C14.0331 32.13 14.0961 32.162 14.1641 32.167L14.1831 32.168C14.2451 32.168 14.3041 32.146 14.3501 32.105L18.0001 28.836L21.6501 32.104C21.6961 32.145 21.7551 32.167 21.8171 32.167L21.8361 32.166C21.9041 32.161 21.9671 32.129 22.0101 32.076L25.0151 28.443L27.0491 29.901C27.1091 29.944 27.1821 29.961 27.2521 29.941C27.3221 29.924 27.3821 29.879 27.4151 29.815L30.1701 24.596C30.2101 24.519 30.2081 24.426 30.1621 24.351ZM27.9761 15.421C28.1051 17.548 27.1921 19.227 24.7711 19.374C22.3511 19.52 21.2421 17.963 21.1131 15.837C20.9841 13.711 22.3451 10.717 24.2401 10.603C26.1361 10.487 27.8481 13.294 27.9761 15.421ZM8.02411 15.421C7.89511 17.548 8.80811 19.227 11.2291 19.374C13.6491 19.52 14.7581 17.963 14.8871 15.837C15.0161 13.711 13.6551 10.717 11.7601 10.603C9.86511 10.489 8.15211 13.294 8.02411 15.421Z" fill="#642116"/>
                                    </g>
                                    <defs>
                                    <clipPath id="clip0_177_40">
                                    <rect width="36" height="36" fill="white"/>
                                    </clipPath>
                                    </defs>
                                </svg>
                            </div>



                            <div class="ays-quiz-halloween-banner-2025-discount-section">
                                <div class="ays-quiz-halloween-banner-2025-discount">25% OFF</div>
                                <div class="ays-quiz-halloween-banner-2025-coupon-wrapper">
                                    <div class="ays-quiz-halloween-banner-2025-coupon-box" onclick="aysQuizHalloweenCopyToClipboard(\'HALLOWEEN25\')" title="Click to copy">
                                        <span class="ays-quiz-halloween-banner-2025-coupon-text">HALLOWEEN25</span>
                                        <svg width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg" class="ays-quiz-halloween-banner-2025-copy-icon">
                                            <path d="M13.5 2.5H6.5C5.67 2.5 5 3.17 5 4V10C5 10.83 5.67 11.5 6.5 11.5H13.5C14.33 11.5 15 10.83 15 10V4C15 3.17 14.33 2.5 13.5 2.5ZM13.5 10H6.5V4H13.5V10ZM2.5 6.5V12.5C2.5 13.33 3.17 14 4 14H10V12.5H4V6.5H2.5Z" fill="white"/>
                                        </svg>
                                    </div>
                                </div>
                                <a href="'. $ays_quiz_cta_button_link .'" class="ays-quiz-halloween-banner-2025-upgrade" target="_blank">Upgrade Now</a>
                            </div>';

                            if( current_user_can( 'manage_options' ) ){
                                $content[] = '<div id="ays-quiz-dismiss-buttons-content">';
                                    $content[] = '<form action="" method="POST" style="position: absolute; bottom: -5px; right: 0; color: #fff;">';
                                            $content[] = '<button class="btn btn-link ays-button" name="ays_quiz_sale_btn" style="color: darkgrey; font-size: 11px;">'. __( "Dismiss ad", 'quiz-maker' ) .'</button>';
                                            $content[] = wp_nonce_field( AYS_QUIZ_NAME . '-sale-banner' ,  AYS_QUIZ_NAME . '-sale-banner' );
                                    $content[] = '</form>';
                                $content[] = '</div>';
                            }

                            $content[] = '
                        </div>
                    </div>
                </div>';

            $content[] = '<style id="ays-quiz-progress-banner-styles-inline-css">';
            $content[] = '
                .ays-quiz-halloween-banner-2025-main {
                    background: linear-gradient(135deg, #1A0F2E 100%, #2D1B4E 0%);
                    background-image: url("' . esc_attr(AYS_QUIZ_ADMIN_URL) . '/images/halloween-banner-background-image-remove.png"), linear-gradient(135deg, #2D1B4E 0%, #1A0F2E 100%);
                    background-position: left center, center;
                    background-repeat: no-repeat, no-repeat;
                    background-size: auto 100%, cover;
                    padding: 20px 30px 20px 130px;
                    border-radius: 12px;
                    color: white;
                    margin: 20px 0;
                    border: 0;
                    box-shadow: 0 8px 32px rgba(0, 0, 0, 0.4);
                    overflow: hidden;
                }

                .ays-quiz-halloween-banner-2025-main .ays-quiz-halloween-banner-2025-content {
                    display: flex;
                    align-items: center;
                    justify-content: space-between;
                    gap: 30px;
                }

                .ays-quiz-halloween-banner-2025-main .ays-quiz-halloween-banner-2025-left {
                    display: flex;
                    align-items: center;
                    gap: 20px;
                }

                .ays-quiz-halloween-banner-2025-main .ays-quiz-halloween-banner-2025-center {
                    display: flex;
                    align-items: center;
                    justify-content: center;
                    flex: 1;
                    max-width: 350px;
                }

                .ays-quiz-halloween-banner-2025-main .ays-quiz-halloween-banner-2025-right {
                    display: flex;
                    align-items: center;
                    gap: 15px;
                    flex-shrink: 0;
                }

                .ays-quiz-halloween-banner-2025-main .ays-quiz-halloween-banner-2025-icon {
                    flex-shrink: 0;
                }

                .ays-quiz-halloween-banner-2025-main .ays-quiz-halloween-banner-2025-pumpkin svg {
                    display: inline !important;
                    border: none !important;
                    box-shadow: none !important;
                    height: 1em !important;
                    width: 1em !important;
                    margin: 0 0.07em !important;
                    vertical-align: -0.1em !important;
                    background: none !important;
                    padding: 0 !important;
                }

                .ays-quiz-halloween-banner-2025-main .ays-quiz-halloween-banner-2025-orb {
                    width: 80px;
                    height: 80px;
                    background: linear-gradient(135deg, #8B5CF6 0%, #6D28D9 100%);
                    border-radius: 50%;
                    display: flex;
                    align-items: center;
                    justify-content: center;
                    box-shadow: 0 0 30px rgba(139, 92, 246, 0.6);
                    border: 3px solid rgba(168, 85, 247, 0.4);
                }

                .ays-quiz-halloween-banner-2025-main .ays-quiz-halloween-banner-2025-question {
                    font-size: 48px;
                    font-weight: 700;
                    color: #E9D5FF;
                }

                .ays-quiz-halloween-banner-2025-main .ays-quiz-halloween-banner-2025-bat {
                    font-size: 20px;
                    opacity: 0.8;
                }

                .ays-quiz-halloween-banner-2025-main .ays-quiz-halloween-banner-2025-bat-1 {
                    margin-left: -100px;
                    margin-top: -40px;
                }

                .ays-quiz-halloween-banner-2025-main .ays-quiz-halloween-banner-2025-bat-2 {
                    margin-left: -110px;
                    margin-top: 35px;
                }

                .ays-quiz-halloween-banner-2025-main .ays-quiz-halloween-banner-2025-sparkle {
                    font-size: 12px;
                    opacity: 0.7;
                }

                .ays-quiz-halloween-banner-2025-main .ays-quiz-halloween-banner-2025-sparkle-1 {
                    margin-left: -95px;
                    margin-top: -60px;
                }

                .ays-quiz-halloween-banner-2025-main .ays-quiz-halloween-banner-2025-sparkle-2 {
                    margin-left: -70px;
                    margin-top: -15px;
                }

                .ays-quiz-halloween-banner-2025-main .ays-quiz-halloween-banner-2025-sparkle-3 {
                    margin-left: -120px;
                    margin-top: 10px;
                }

                .ays-quiz-halloween-banner-2025-main .ays-quiz-halloween-banner-2025-text {
                    flex: 1;
                }

                .ays-quiz-halloween-banner-2025-main .ays-quiz-halloween-banner-2025-title {
                    font-size: 24px;
                    font-weight: 700;
                    margin: 0 0 8px 0;
                    line-height: 1.2;
                    color: #fff;
                    text-transform: uppercase;
                    letter-spacing: 1px;
                }

                .ays-quiz-halloween-banner-2025-main .ays-quiz-halloween-banner-2025-title a {
                    color: #FB923C;
                    text-decoration: underline;
                }

                .ays-quiz-halloween-banner-2025-main .ays-quiz-halloween-banner-2025-subtitle {
                    font-size: 16px;
                    margin: 0;
                    opacity: 0.9;
                    font-weight: 400;
                    color: #E9D5FF;
                }

                .ays-quiz-halloween-banner-2025-main .ays-quiz-halloween-banner-2025-description {
                    font-size: 14px;
                    margin: 0;
                    opacity: 0.85;
                    line-height: 1.5;
                    color: #D8B4FE;
                }

                .ays-quiz-halloween-banner-2025-main .ays-quiz-halloween-banner-2025-pumpkin {
                    font-size: 64px;
                    filter: drop-shadow(0 0 20px rgba(251, 146, 60, 0.8));
                    flex-shrink: 0;
                }

                .ays-quiz-halloween-banner-2025-main .ays-quiz-halloween-banner-2025-discount-section {
                    display: flex;
                    flex-direction: column;
                    align-items: center;
                    gap: 10px;
                }

                .ays-quiz-halloween-banner-2025-main .ays-quiz-halloween-banner-2025-discount {
                    font-size: 36px;
                    font-weight: 700;
                    color: #FB923C;
                    text-shadow: 0 0 20px rgba(251, 146, 60, 0.6);
                    margin: 0;
                    line-height: 1;
                }

                .ays-quiz-halloween-banner-2025-main .ays-quiz-halloween-banner-2025-coupon-wrapper {
                    margin-bottom: 5px;
                }

                .ays-quiz-halloween-banner-2025-main .ays-quiz-halloween-banner-2025-coupon-box {
                    border: 2px dashed rgba(255, 255, 255, 0.4);
                    padding: 6px 12px;
                    border-radius: 6px;
                    background: rgba(255, 255, 255, 0.1);
                    cursor: pointer;
                    transition: all 0.3s ease;
                    display: flex;
                    align-items: center;
                    gap: 6px;
                    backdrop-filter: blur(10px);
                }

                .ays-quiz-halloween-banner-2025-main .ays-quiz-halloween-banner-2025-coupon-box:hover {
                    background: rgba(255, 255, 255, 0.2);
                    border-color: rgba(255, 255, 255, 0.6);
                    transform: translateY(-1px);
                }

                .ays-quiz-halloween-banner-2025-main .ays-quiz-halloween-banner-2025-coupon-text {
                    font-size: 14px;
                    font-weight: 700;
                    letter-spacing: 1px;
                    color: #fff;
                    font-family: monospace;
                }

                .ays-quiz-halloween-banner-2025-main .ays-quiz-halloween-banner-2025-copy-icon {
                    opacity: 0.8;
                    transition: opacity 0.3s ease;
                }

                .ays-quiz-halloween-banner-2025-main .ays-quiz-halloween-banner-2025-coupon-box:hover .ays-quiz-halloween-banner-2025-copy-icon {
                    opacity: 1;
                }

                .ays-quiz-halloween-banner-2025-main .ays-quiz-halloween-banner-2025-upgrade {
                    background: linear-gradient(135deg, #FB923C 0%, #F97316 100%);
                    color: white;
                    border: none;
                    padding: 12px 28px;
                    border-radius: 8px;
                    font-size: 16px;
                    font-weight: 600;
                    cursor: pointer;
                    transition: all 0.3s ease;
                    box-shadow: 0 4px 16px rgba(251, 146, 60, 0.5);
                    text-decoration: none;
                    display: inline-flex;
                    align-items: center;
                    text-align: center;
                }

                .ays-quiz-halloween-banner-2025-main .ays-quiz-halloween-banner-2025-upgrade:hover {
                    transform: translateY(-2px);
                    box-shadow: 0 6px 20px rgba(251, 146, 60, 0.7);
                    text-decoration: none;
                    color: white;
                }

                .ays-quiz-halloween-banner-2025-main .notice-dismiss:before {
                    color: #fff;
                }

                .ays-quiz-halloween-banner-2025-copy-notification {
                    position: fixed;
                    top: 50%;
                    left: 50%;
                    transform: translate(-50%, -50%);
                    background: rgba(0, 0, 0, 0.8);
                    color: white;
                    padding: 12px 24px;
                    border-radius: 8px;
                    font-size: 14px;
                    z-index: 10000;
                    opacity: 0;
                    transition: opacity 0.3s ease;
                }

                .ays-quiz-halloween-banner-2025-copy-notification.show {
                    opacity: 1;
                }

                .ays-quiz-halloween-banner-2025-main .ays-quiz-halloween-banner-2025-countdown-timer {
                    display: flex;
                    align-items: center;
                    justify-content: center;
                    gap: 8px;
                }

                .ays-quiz-halloween-banner-2025-main .ays-quiz-halloween-banner-2025-countdown-item {
                    background: rgba(255, 255, 255, 0.15);
                    border-radius: 8px;
                    padding: 8px 12px;
                    min-width: 60px;
                    backdrop-filter: blur(10px);
                }

                .ays-quiz-halloween-banner-2025-main .ays-quiz-halloween-banner-2025-countdown-value {
                    font-size: 24px;
                    font-weight: 700;
                    line-height: 1;
                    margin-bottom: 4px;
                    color: #fff;
                    text-align: center;
                }

                .ays-quiz-halloween-banner-2025-main .ays-quiz-halloween-banner-2025-countdown-label {
                    font-size: 11px;
                    opacity: 0.8;
                    text-transform: lowercase;
                    text-align: center;
                }

                .ays-quiz-halloween-banner-2025-main .ays-quiz-halloween-banner-2025-countdown-separator {
                    font-size: 24px;
                    font-weight: 700;
                    opacity: 0.6;
                    margin: 0 4px;
                }

                @media (min-width: 1200px) {
                    .ays-quiz-halloween-banner-2025-main .wp-core-ui .notice.is-dismissible {
                        padding-right: 60px;
                    }
                }

                @media (max-width: 1200px) {

                    div.ays-quiz-halloween-banner-2025-main {
                        padding: 20px 30px;
                    }

                    div.ays-quiz-halloween-banner-2025-main .ays-quiz-halloween-banner-2025-pumpkin {
                        display: none;
                    }

                    .ays-quiz-halloween-banner-2025-main .ays-quiz-halloween-banner-2025-subtitle,
                    .ays-quiz-halloween-banner-2025-main .ays-quiz-halloween-banner-2025-title {
                        text-align: center;
                    }

                    .ays-quiz-halloween-banner-2025-main .ays-quiz-halloween-banner-2025-content {
                        flex-wrap: wrap;
                        gap: 20px;
                    }

                    .ays-quiz-halloween-banner-2025-main .ays-quiz-halloween-banner-2025-left {
                        width: 100%;
                    }

                    .ays-quiz-halloween-banner-2025-main .ays-quiz-halloween-banner-2025-center {
                        width: 100%;
                        max-width: 100%;
                        text-align: center;
                    }

                    .ays-quiz-halloween-banner-2025-main .ays-quiz-halloween-banner-2025-right {
                        width: 100%;
                        justify-content: center;
                    }
                }

                @media (max-width: 786px) {
                    #ays-quiz-halloween-banner-2025-main {
                        display: none !important;
                    }
                }

                @media (max-width: 768px) {
                    .ays-quiz-halloween-banner-2025-main {
                        padding: 15px 20px;
                        margin: 15px 0;
                    }

                    .ays-quiz-halloween-banner-2025-main .ays-quiz-halloween-banner-2025-title {
                        font-size: 20px;
                    }

                    .ays-quiz-halloween-banner-2025-main .ays-quiz-halloween-banner-2025-subtitle {
                        font-size: 14px;
                    }

                    .ays-quiz-halloween-banner-2025-main .ays-quiz-halloween-banner-2025-description {
                        font-size: 13px;
                    }

                    .ays-quiz-halloween-banner-2025-main .ays-quiz-halloween-banner-2025-pumpkin {
                        font-size: 48px;
                    }

                    .ays-quiz-halloween-banner-2025-main .ays-quiz-halloween-banner-2025-discount {
                        font-size: 28px;
                    }

                    .ays-quiz-halloween-banner-2025-main .ays-quiz-halloween-banner-2025-upgrade {
                        padding: 10px 20px;
                        font-size: 14px;
                    }
                }

                @media (max-width: 480px) {
                    .ays-quiz-halloween-banner-2025-main {
                        padding: 12px 15px;
                    }

                    .ays-quiz-halloween-banner-2025-main .ays-quiz-halloween-banner-2025-orb {
                        width: 60px;
                        height: 60px;
                    }

                    .ays-quiz-halloween-banner-2025-main .ays-quiz-halloween-banner-2025-question {
                        font-size: 36px;
                    }

                    .ays-quiz-halloween-banner-2025-main .ays-quiz-halloween-banner-2025-title {
                        font-size: 18px;
                    }

                    .ays-quiz-halloween-banner-2025-main .ays-quiz-halloween-banner-2025-coupon-text {
                        font-size: 12px;
                    }
                }
            ';

            $content[] = '</style>';

            $content[] = '<script>';
            $content[] = "
                    function aysQuizHalloweenCopyToClipboard(text) {
                        // Create a temporary textarea element
                        var textarea = document.createElement('textarea');
                        textarea.value = text;
                        textarea.style.position = 'fixed';
                        textarea.style.opacity = '0';
                        document.body.appendChild(textarea);
                        
                        // Select and copy the text
                        textarea.select();
                        textarea.setSelectionRange(0, 99999); // For mobile devices
                        
                        try {
                            document.execCommand('copy');
                            aysQuizHalloweenShowCopyNotification('Coupon code copied!');
                        } catch (err) {
                            console.error('Failed to copy text: ', err);
                        }
                        
                        // Remove the temporary textarea
                        document.body.removeChild(textarea);
                    }

                    function aysQuizHalloweenShowCopyNotification(message) {
                        // Check if notification already exists
                        var existingNotification = document.querySelector('.ays-quiz-halloween-banner-2025-copy-notification');
                        if (existingNotification) {
                            document.body.removeChild(existingNotification);
                        }
                        
                        // Create notification element
                        var notification = document.createElement('div');
                        notification.className = 'ays-quiz-halloween-banner-2025-copy-notification';
                        notification.textContent = message;
                        document.body.appendChild(notification);
                        
                        // Show notification with animation
                        setTimeout(function() {
                            notification.classList.add('show');
                        }, 10);
                        
                        // Hide and remove notification after 2 seconds
                        setTimeout(function() {
                            notification.classList.remove('show');
                            setTimeout(function() {
                                if (notification.parentNode) {
                                    document.body.removeChild(notification);
                                }
                            }, 300);
                        }, 2000);
                    }

                    (function() {
                        var endDate = new Date('". date('Y-m-d H:i:s', $end_date) ."').getTime();
                    
                        function updateCountdown() {
                            var now = new Date().getTime();
                            var distance = endDate - now;
                            
                            if (distance < 0) {
                                clearInterval(updateCountdown);
                                document.getElementById('ays-quiz-halloween-banner-2025-progress-banner-countdown').style.display = 'none';
                                return;
                            }
                            
                            var days = Math.floor(distance / (1000 * 60 * 60 * 24));
                            var hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                            var minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
                            var seconds = Math.floor((distance % (1000 * 60)) / 1000);
                            
                            function padZero(num) {
                                return num < 10 ? '0' + num : num;
                            }
                            
                            document.getElementById('ays-quiz-halloween-banner-2025-days').textContent = padZero(days);
                            document.getElementById('ays-quiz-halloween-banner-2025-hours').textContent = padZero(hours);
                            document.getElementById('ays-quiz-halloween-banner-2025-minutes').textContent = padZero(minutes);
                            document.getElementById('ays-quiz-halloween-banner-2025-seconds').textContent = padZero(seconds);
                        }
                        
                        updateCountdown();
                        setInterval(updateCountdown, 1000);
                    })()";
            $content[] = '</script>';

            $content = implode( '', $content );
            echo ($content);
        }
    }
 
    // Christmas Banner
    public function ays_quiz_christmas_banner_message_2025($ishmar){
        if($ishmar == 0 ){
            $content = array();

           $svg_icon = '<svg width="21" height="22" viewBox="0 0 21 22" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path fill-rule="evenodd" clip-rule="evenodd" d="M10.0445 0C10.4587 0 10.7945 0.33579 10.7945 0.75V2.93934L12.5141 1.21967C12.807 0.92678 13.2819 0.92678 13.5748 1.21967C13.8677 1.51256 13.8677 1.98744 13.5748 2.28033L10.7945 5.06066V9.451L14.5965 7.25588L15.6142 3.45788C15.7214 3.05778 16.1326 2.82034 16.5327 2.92755C16.9328 3.03475 17.1703 3.44601 17.0631 3.84611L16.4336 6.19522L18.3296 5.10055C18.6884 4.89344 19.147 5.01635 19.3542 5.37507C19.5613 5.73379 19.4384 6.19248 19.0796 6.39959L17.1836 7.49426L19.5327 8.1237C19.9328 8.23091 20.1703 8.64216 20.0631 9.0423C19.9558 9.4424 19.5446 9.6798 19.1445 9.5726L15.3465 8.55492L11.5445 10.75L15.3467 12.9452L19.1447 11.9275C19.5448 11.8203 19.956 12.0578 20.0633 12.4579C20.1705 12.858 19.933 13.2692 19.5329 13.3764L17.1838 14.0059L19.0798 15.1005C19.4386 15.3077 19.5615 15.7663 19.3544 16.1251C19.1472 16.4838 18.6886 16.6067 18.3298 16.3996L16.4338 15.3049L17.0633 17.654C17.1705 18.0541 16.933 18.4654 16.5329 18.5726C16.1328 18.6798 15.7216 18.4424 15.6144 18.0423L14.5967 14.2443L10.7945 12.049V16.4393L13.5748 19.2197C13.8677 19.5126 13.8677 19.9874 13.5748 20.2803C13.2819 20.5732 12.807 20.5732 12.5141 20.2803L10.7945 18.5607V20.75C10.7945 21.1642 10.4587 21.5 10.0445 21.5C9.63033 21.5 9.29453 21.1642 9.29453 20.75V18.5607L7.57484 20.2803C7.28195 20.5732 6.80707 20.5732 6.51418 20.2803C6.22129 19.9874 6.22129 19.5126 6.51418 19.2197L9.29453 16.4393V12.049L5.4923 14.2443L4.47463 18.0423C4.36742 18.4424 3.95617 18.6798 3.55607 18.5726C3.15597 18.4654 2.91853 18.0541 3.02574 17.654L3.65518 15.3049L1.75916 16.3996C1.40044 16.6067 0.941743 16.4838 0.734643 16.1251C0.527533 15.7663 0.650443 15.3077 1.00916 15.1005L2.90518 14.0059L0.556073 13.3764C0.155973 13.2692 -0.081467 12.858 0.0257431 12.4579C0.132943 12.0578 0.544203 11.8203 0.944303 11.9275L4.7423 12.9452L8.54453 10.75L4.74249 8.55492L0.944493 9.5726C0.544393 9.6798 0.133143 9.4424 0.0259331 9.0423C-0.0812669 8.64216 0.156163 8.23091 0.556263 8.1237L2.90538 7.49426L1.00935 6.39959C0.650633 6.19248 0.527733 5.73379 0.734833 5.37507C0.941943 5.01635 1.40063 4.89344 1.75935 5.10055L3.65538 6.19522L3.02593 3.84611C2.91873 3.44601 3.15616 3.03475 3.55626 2.92755C3.95636 2.82034 4.36762 3.05778 4.47482 3.45788L5.49249 7.25588L9.29453 9.451V5.06066L6.51418 2.28033C6.22129 1.98744 6.22129 1.51256 6.51418 1.21967C6.80707 0.92678 7.28195 0.92678 7.57484 1.21967L9.29453 2.93934V0.75C9.29453 0.33579 9.63033 0 10.0445 0Z" fill="white" fill-opacity="0.2"/>
            </svg>
            ';

            $ays_quiz_cta_button_link = esc_url('https://ays-pro.com/wordpress/quiz-maker?utm_source=dashboard&utm_medium=quiz-free&utm_campaign=christmas-sale-banner-' . AYS_QUIZ_UTM_VERSION);

            $content[] = '<div id="ays-quiz-christmas-banner-main" class="notice notice-success is-dismissible ays-quiz-christmas-banner-info ays_quiz_dicount_info">';
                $content[] = '<div id="ays-quiz-christmas-banner-month" class="ays-quiz-christmas-banner-month">';
                    
                    // Background effects
                    $content[] = '<div class="ays-quiz-christmas-banner-bg-effects">';
                        $content[] = '<div class="ays-quiz-christmas-banner-bg-gradient-1"></div>';
                        $content[] = '<div class="ays-quiz-christmas-banner-bg-gradient-2"></div>';
                        
                        // Snowflakes
                        $content[] = '<div class="ays-quiz-christmas-banner-snowflake" style="left: 5%; animation-delay: 0s; animation-duration: 8s;">'. $svg_icon .'</div>';
                        $content[] = '<div class="ays-quiz-christmas-banner-snowflake" style="left: 15%; animation-delay: 2s; animation-duration: 10s;">'. $svg_icon .'</div>';
                        $content[] = '<div class="ays-quiz-christmas-banner-snowflake" style="left: 25%; animation-delay: 4s; animation-duration: 9s;">'. $svg_icon .'</div>';
                        $content[] = '<div class="ays-quiz-christmas-banner-snowflake" style="left: 75%; animation-delay: 1s; animation-duration: 11s;">'. $svg_icon .'</div>';
                        $content[] = '<div class="ays-quiz-christmas-banner-snowflake" style="left: 85%; animation-delay: 3s; animation-duration: 8s;">'. $svg_icon .'</div>';
                        $content[] = '<div class="ays-quiz-christmas-banner-snowflake" style="left: 92%; animation-delay: 5s; animation-duration: 10s;">'. $svg_icon .'</div>';
                        
                        // Sparkles
                        $content[] = '<svg class="ays-quiz-christmas-banner-sparkle" style="top: 20%; left: 8%; animation-delay: 0s; width: 14px; height: 14px;" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">';
                            $content[] = '<path d="M9.937 15.5A2 2 0 0 0 8.5 14.063l-6.135-1.582a.5.5 0 0 1 0-.962L8.5 9.936A2 2 0 0 0 9.937 8.5l1.582-6.135a.5.5 0 0 1 .963 0L14.063 8.5A2 2 0 0 0 15.5 9.937l6.135 1.581a.5.5 0 0 1 0 .964L15.5 14.063a2 2 0 0 0-1.437 1.437l-1.582 6.135a.5.5 0 0 1-.963 0z"/>';
                        $content[] = '</svg>';
                        $content[] = '<svg class="ays-quiz-christmas-banner-sparkle" style="top: 60%; left: 3%; animation-delay: 0.5s; width: 10px; height: 10px;" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">';
                            $content[] = '<path d="M9.937 15.5A2 2 0 0 0 8.5 14.063l-6.135-1.582a.5.5 0 0 1 0-.962L8.5 9.936A2 2 0 0 0 9.937 8.5l1.582-6.135a.5.5 0 0 1 .963 0L14.063 8.5A2 2 0 0 0 15.5 9.937l6.135 1.581a.5.5 0 0 1 0 .964L15.5 14.063a2 2 0 0 0-1.437 1.437l-1.582 6.135a.5.5 0 0 1-.963 0z"/>';
                        $content[] = '</svg>';
                        $content[] = '<svg class="ays-quiz-christmas-banner-sparkle" style="top: 30%; right: 12%; animation-delay: 1s; width: 12px; height: 12px;" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">';
                            $content[] = '<path d="M9.937 15.5A2 2 0 0 0 8.5 14.063l-6.135-1.582a.5.5 0 0 1 0-.962L8.5 9.936A2 2 0 0 0 9.937 8.5l1.582-6.135a.5.5 0 0 1 .963 0L14.063 8.5A2 2 0 0 0 15.5 9.937l6.135 1.581a.5.5 0 0 1 0 .964L15.5 14.063a2 2 0 0 0-1.437 1.437l-1.582 6.135a.5.5 0 0 1-.963 0z"/>';
                        $content[] = '</svg>';
                    $content[] = '</div>';

                    // Main content
                    $content[] = '<div class="ays-quiz-christmas-banner-content">';
                        $content[] = '<div class="ays-quiz-christmas-banner-left">';
                            // Gift icon with hat
                            $content[] = '<div class="ays-quiz-christmas-banner-gift-wrapper">';
                                $content[] = '<svg class="ays-quiz-christmas-banner-gift-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">';
                                    $content[] = '<rect x="3" y="8" width="18" height="4" rx="1"></rect>';
                                    $content[] = '<path d="M12 8v13"></path>';
                                    $content[] = '<path d="M19 12v7a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2v-7"></path>';
                                    $content[] = '<path d="M7.5 8a2.5 2.5 0 0 1 0-5A4.8 8 0 0 1 12 8a4.8 8 0 0 1 4.5-5 2.5 2.5 0 0 1 0 5"></path>';
                                $content[] = '</svg>';
                                $content[] = '<div class="ays-quiz-christmas-banner-hat">';
                                    $content[] = '<svg viewBox="0 0 24 24" fill="none" class="ays-quiz-christmas-banner-hat-svg">';
                                        $content[] = '<path d="M12 2L4 14h16L12 2z" fill="hsl(0 80% 45%)"></path>';
                                        $content[] = '<path d="M4 14c0 2 3.5 3 8 3s8-1 8-3" fill="hsl(0 0% 100%)"></path>';
                                        $content[] = '<circle cx="12" cy="3" r="2" fill="hsl(0 0% 100%)"></circle>';
                                    $content[] = '</svg>';
                                $content[] = '</div>';
                            $content[] = '</div>';

                            $content[] = '<div class="ays-quiz-christmas-banner-special-label">';
                                $content[] = '<div class="ays-quiz-christmas-banner-special-label-name">';
                                    $content[] = '<a href="'. $ays_quiz_cta_button_link .'" class="ays-quiz-christmas-banner-special-label-name-link" target="_blank">';
                                        $content[] = __( 'Quiz Maker', "quiz-maker" );
                                    $content[] = '</a>';
                                $content[] = '</div>';

                                $content[] = '<div>✦ ' . __( 'CHRISTMAS SPECIAL', "quiz-maker" ) . ' ✦</div>';
                            $content[] = '</div>';
                        $content[] = '</div>';

                        $content[] = '<div class="ays-quiz-christmas-banner-center">';
                            $content[] = '<div class="ays-quiz-christmas-banner-discount-text">25% OFF</div>';
                            $content[] = '<div class="ays-quiz-christmas-banner-limited-offer">' . __( 'Limited time offer', "quiz-maker" ) . '</div>';
                        $content[] = '</div>';

                        $content[] = '<div class="ays-quiz-christmas-banner-right">';
                            $content[] = '<div class="ays-quiz-christmas-banner-coupon-box" onclick="aysQuizChristmasCopyToClipboard(\'XMAS25\')" title="' . __( 'Click to copy', "quiz-maker" ) . '">';
                                $content[] = '<span class="ays-quiz-christmas-banner-coupon-text">XMAS25</span>';
                                $content[] = '<svg class="ays-quiz-christmas-banner-copy-icon" width="16" height="16" viewBox="0 0 16 16" fill="none">';
                                    $content[] = '<path d="M13.5 2.5H6.5C5.67 2.5 5 3.17 5 4V10C5 10.83 5.67 11.5 6.5 11.5H13.5C14.33 11.5 15 10.83 15 10V4C15 3.17 14.33 2.5 13.5 2.5ZM13.5 10H6.5V4H13.5V10ZM2.5 6.5V12.5C2.5 13.33 3.17 14 4 14H10V12.5H4V6.5H2.5Z" fill="white"/>';
                                $content[] = '</svg>';
                            $content[] = '</div>';

                            $content[] = '<a href="'. $ays_quiz_cta_button_link .'" class="ays-quiz-christmas-banner-buy-now-btn" target="_blank">';
                                $content[] = __( 'Buy Now', "quiz-maker" );
                                // $content[] = '<span class="ays-quiz-christmas-banner-btn-arrow">→</span>';
                            $content[] = '</a>';
                        $content[] = '</div>';
                    $content[] = '</div>';

                $content[] = '</div>';

                if( current_user_can( 'manage_options' ) ){
                $content[] = '<div id="ays-quiz-dismiss-buttons-content">';
                    $content[] = '<form action="" method="POST" style="position: absolute; bottom: 0; right: 0; color: #fff;">';
                            $content[] = '<button class="btn btn-link ays-button" name="ays_quiz_sale_btn" style="color: darkgrey; font-size: 11px; padding: 0 .75rem;">'. __( "Dismiss ad", 'quiz-maker' ) .'</button>';
                            $content[] = wp_nonce_field( AYS_QUIZ_NAME . '-sale-banner' ,  AYS_QUIZ_NAME . '-sale-banner' );
                    $content[] = '</form>';
                $content[] = '</div>';
                }

            $content[] = '</div>';

            $content[] = '<script>';
            $content[] = "
                function aysQuizChristmasCopyToClipboard(text) {
                    var textarea = document.createElement('textarea');
                    textarea.value = text;
                    textarea.style.position = 'fixed';
                    textarea.style.opacity = '0';
                    document.body.appendChild(textarea);
                    
                    textarea.select();
                    textarea.setSelectionRange(0, 99999);
                    
                    try {
                        document.execCommand('copy');
                        aysQuizChristmasShowCopyNotification('" . __( 'Coupon code copied!', "quiz-maker" ) . "');
                    } catch (err) {
                        console.error('Failed to copy text: ', err);
                    }
                    
                    document.body.removeChild(textarea);
                }

                function aysQuizChristmasShowCopyNotification(message) {
                    var existingNotification = document.querySelector('.ays-quiz-christmas-banner-copy-notification');
                    if (existingNotification) {
                        document.body.removeChild(existingNotification);
                    }
                    
                    var notification = document.createElement('div');
                    notification.className = 'ays-quiz-christmas-banner-copy-notification';
                    notification.textContent = message;
                    document.body.appendChild(notification);
                    
                    setTimeout(function() {
                        notification.classList.add('show');
                    }, 10);
                    
                    setTimeout(function() {
                        notification.classList.remove('show');
                        setTimeout(function() {
                            if (notification.parentNode) {
                                document.body.removeChild(notification);
                            }
                        }, 300);
                    }, 2000);
                }";
            $content[] = '</script>';                

            $content[] = '<style>';
            $content[] = '
                /* Christmas banner start */

                div#ays-quiz-christmas-banner-main .btn-link {
                    background-color: transparent;
                    display: inline-block;
                    font-weight: 400;
                    text-align: center;
                    white-space: nowrap;
                    vertical-align: middle;
                    -webkit-user-select: none;
                    -moz-user-select: none;
                    -ms-user-select: none;
                    user-select: none;
                    border: 1px solid transparent;
                    padding: .375rem .75rem;
                    font-size: 12px;
                    line-height: 1.5;
                    border-radius: .25rem;
                    color: rgba(255, 255, 255, .6);
                }
                
                div#ays-quiz-christmas-banner-main.ays-quiz-christmas-banner-info {
                    background: linear-gradient(to right, hsl(0, 70%, 28%), hsl(0, 65%, 38%), hsl(0, 70%, 28%));
                    padding: unset;
                    border-left: 0;
                    position: relative;
                }
                
                #ays-quiz-christmas-banner-main .ays-quiz-christmas-banner-month {
                    position: relative;
                    padding: 15px 40px;
                    overflow: hidden;
                }
                
                /* Background effects */
                .ays-quiz-christmas-banner-bg-effects {
                    position: absolute;
                    top: 0;
                    left: 0;
                    right: 0;
                    bottom: 0;
                    pointer-events: none;
                    z-index: 1;
                }
                
                .ays-quiz-christmas-banner-bg-gradient-1 {
                    position: absolute;
                    top: 0;
                    left: 30%;
                    width: 40%;
                    height: 100%;
                    background: radial-gradient(circle, rgba(202, 43, 43, 0.5) 0%, transparent 60%);
                    opacity: 0.4;
                }
                
                .ays-quiz-christmas-banner-bg-gradient-2 {
                    position: absolute;
                    top: 0;
                    right: 15%;
                    width: 35%;
                    height: 100%;
                    background: radial-gradient(circle, rgba(246, 201, 85, 0.15) 0%, transparent 50%);
                    opacity: 0.3;
                }
                
                .ays-quiz-christmas-banner-snowflake {
                    position: absolute;
                    color: rgba(255, 255, 255, 0.2);
                    font-size: 20px;
                    animation: ays-quiz-christmas-snowfall linear infinite;
                    top: -10px;
                }
                
                @keyframes ays-quiz-christmas-snowfall {
                    0% {
                        transform: translateY(-10px) rotate(0deg);
                        opacity: 0;
                    }
                    10% {
                        opacity: 0.8;
                    }
                    90% {
                        opacity: 0.8;
                    }
                    100% {
                        transform: translateY(100%) rotate(360deg);
                        opacity: 0;
                    }
                }
                
                .ays-quiz-christmas-banner-sparkle {
                    position: absolute;
                    color: hsl(43, 90%, 65%);
                    animation: ays-quiz-christmas-twinkle 2s ease-in-out infinite;
                }
                
                @keyframes ays-quiz-christmas-twinkle {
                    0%, 100% {
                        opacity: 0.3;
                        transform: scale(0.8);
                    }
                    50% {
                        opacity: 1;
                        transform: scale(1.2);
                    }
                }
                
                /* Main content */
                .ays-quiz-christmas-banner-content {
                    position: relative;
                    display: flex;
                    align-items: center;
                    justify-content: space-between;
                    z-index: 2;
                }
                
                /* Left section */
                .ays-quiz-christmas-banner-left {
                    display: flex;
                    align-items: center;
                    gap: 50px;
                }
                
                .ays-quiz-christmas-banner-gift-wrapper {
                    position: relative;
                    animation: ays-quiz-christmas-float 3s ease-in-out infinite;
                }
                
                .ays-quiz-christmas-banner-gift-icon {
                    width: 48px;
                    height: 48px;
                    color: rgba(255, 247, 237, 0.9);
                }
                
                @keyframes ays-quiz-christmas-float {
                    0%, 100% {
                        transform: translateY(0);
                    }
                    50% {
                        transform: translateY(-5px);
                    }
                }
                
                .ays-quiz-christmas-banner-hat {
                    position: absolute;
                    top: -12px;
                    right: -4px;
                    width: 24px;
                    height: 24px;
                }
                
                .ays-quiz-christmas-banner-hat-svg {
                    width: 100%;
                    height: 100%;
                }
                
                .ays-quiz-christmas-banner-special-label {
                    color: hsl(43, 90%, 65%);
                    font-size: 14px;
                    font-weight: 500;
                    text-transform: uppercase;
                    letter-spacing: 1px;
                    /* font-family: "Outfit", sans-serif; */
                }

                .ays-quiz-christmas-banner-special-label-name {
                    color: #fffaf0;
                    text-align: center;
                }

                div#ays-quiz-christmas-banner-main .ays-quiz-christmas-banner-special-label-name-link {
                    color: #fffaf0;
                    box-shadow: unset;
                }
                
                /* Center section */
                .ays-quiz-christmas-banner-center {
                    display: flex;
                    flex-direction: row;
                    text-align: center;
                    justify-content: center;
                    align-items: center;
                    gap: 30px;
                }
                
                .ays-quiz-christmas-banner-discount-text {
                    font-family: "Outfit", sans-serif;
                    font-weight: 800;
                    font-size: 30px;
                    color: hsl(40, 100%, 97%);
                    letter-spacing: -1px;
                    line-height: 1;
                }
                
                .ays-quiz-christmas-banner-limited-offer {
                    color: rgba(255, 247, 237, 0.7);
                    font-size: 13px;
                    font-weight: 500;
                }
                
                /* Right section */
                .ays-quiz-christmas-banner-right {
                    display: flex;
                    align-items: center;
                    gap: 20px;
                }
                
                .ays-quiz-christmas-banner-coupon-box {
                    border: 2px dashed rgba(255, 255, 255, 0.4);
                    padding: 8px 16px;
                    border-radius: 6px;
                    background: rgba(255, 255, 255, 0.1);
                    cursor: pointer;
                    transition: all 0.3s;
                    display: flex;
                    align-items: center;
                    gap: 8px;
                    backdrop-filter: blur(10px);
                }
                
                .ays-quiz-christmas-banner-coupon-box:hover {
                    background: rgba(255, 255, 255, 0.2);
                    border-color: rgba(255, 255, 255, 0.6);
                    transform: translateY(-1px);
                }
                
                .ays-quiz-christmas-banner-coupon-text {
                    font-size: 16px;
                    font-weight: 700;
                    letter-spacing: 1px;
                    color: #fff;
                    font-family: monospace;
                }
                
                .ays-quiz-christmas-banner-copy-icon {
                    opacity: 0.8;
                    transition: opacity 0.3s;
                }
                
                .ays-quiz-christmas-banner-coupon-box:hover .ays-quiz-christmas-banner-copy-icon {
                    opacity: 1;
                }
                
                #ays-quiz-christmas-banner-main .ays-quiz-christmas-banner-buy-now-btn {
                    background-color: #fbe19f;
                    color: hsl(0, 72%, 35%);
                    padding: 10px 30px;
                    border-radius: 9999px;
                    font-size: 16px;
                    font-weight: 600;
                    font-family: "Outfit", sans-serif;
                    border: none;
                    cursor: pointer;
                    transition: all 0.3s;
                    text-decoration: none;
                    display: flex;
                    align-items: center;
                    gap: 6px;
                    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.15), 0 0 30px rgba(246, 201, 85, 0.2);
                }
                
                #ays-quiz-christmas-banner-main .ays-quiz-christmas-banner-buy-now-btn:hover {
                    background-color: #f2d58c;
                    transform: scale(1.05);
                    box-shadow: 0 8px 30px rgba(0, 0, 0, 0.2), 0 0 40px rgba(246, 201, 85, 0.3);
                }
                
                .ays-quiz-christmas-banner-btn-arrow {
                    display: inline-block;
                    transition: transform 0.3s;
                }
                
                .ays-quiz-christmas-banner-buy-now-btn:hover .ays-quiz-christmas-banner-btn-arrow {
                    transform: translateX(4px);
                }
                
                /* Notification */
                .ays-quiz-christmas-banner-copy-notification {
                    position: fixed;
                    top: 50%;
                    left: 50%;
                    transform: translate(-50%, -50%);
                    background: rgba(0, 0, 0, 0.8);
                    color: #fff;
                    padding: 12px 24px;
                    border-radius: 8px;
                    font-size: 14px;
                    z-index: 10000;
                    opacity: 0;
                    transition: opacity 0.3s;
                }
                
                .ays-quiz-christmas-banner-copy-notification.show {
                    opacity: 1;
                }
                
                /* Dismiss button */
                #ays-quiz-christmas-banner-main #ays-quiz-christmas-banner-dismiss-content {
                    display: flex;
                    justify-content: center;
                }
                
                #ays-quiz-christmas-banner-main #ays-quiz-christmas-banner-dismiss-content .ays-button {
                    margin: 0 !important;
                    font-size: 13px;
                    color: rgba(150, 147, 147, 0.69);
                }
                
                /* Responsive */
                @media (max-width: 1024px) {
                    .ays-quiz-christmas-banner-discount-text {
                        font-size: 40px;
                    }
                    .ays-quiz-christmas-banner-content {
                        flex-wrap: wrap;
                    }
                }
                
                @media (max-width: 768px) {
                    #ays-quiz-christmas-banner-main {
                        display: none !important;
                    }
                }
                /* Christmas banner end */
            ';
            $content[] = '</style>';

            $content = implode( '', $content );

            echo $content;
        }
    }


    /*
    ==========================================
        Sale Banner | End
    ==========================================
    */

    public function ays_quiz_subscribe_email(){

        if( !current_user_can( 'manage_options' ) ){
            echo json_encode(array(
                "status" => false,
                "message" => "Something went wrong. Please try again"
            ));
            wp_die();
        }

        $subscribe_email = isset($_REQUEST['email']) && $_REQUEST['email'] != "" ? sanitize_email($_REQUEST['email']) : "";
        // $url = "http://localhost/add-on-email/quiz_test_email";
        $url = "https://ays-pro.com/add-on-email/";
        if($subscribe_email != ""){
            $current_date = date("Y-m-d");
            $current_user_ip = $this->get_user_ip();
            $send_request = wp_remote_post($url, array(
                'headers'     => array("Content-Type: application/json; charset=UTF-8"),
                'body'        => json_encode( array(
                    "email"   => $subscribe_email,
                    "user_ip" => $current_user_ip,
                    "subscirbe_date" => $current_date
                ) ),
            ) );
            $response = wp_remote_retrieve_body($send_request);
            $response = json_decode($response, true);
            if(isset($response) && is_array($response)){
                $response_code = isset($response['code']) && $response['code'] != "" ? $response['code'] : "";
                $response_message = isset($response['msg']) && $response['msg'] != "" ? $response['msg'] : "";
                $response_status = $response_code > 0 ? true : false;
                echo json_encode(array(
                    "status" => $response_status,
                    "message" => $response_message
                ));
                wp_die();
            }       
            else{
                echo json_encode(array(
                    "status" => false,
                    "message" => "Something went wrong. Please try again"
                ));
                wp_die();
            }
        }
    }

    public function get_user_ip(){
        $ipaddress = '';
        if (getenv('REMOTE_ADDR'))
            $ipaddress = getenv('REMOTE_ADDR');
        elseif (getenv('HTTP_CLIENT_IP'))
            $ipaddress = getenv('HTTP_CLIENT_IP');
        else if (getenv('HTTP_X_FORWARDED_FOR'))
            $ipaddress = getenv('HTTP_X_FORWARDED_FOR');
        else if (getenv('HTTP_X_FORWARDED'))
            $ipaddress = getenv('HTTP_X_FORWARDED');
        else if (getenv('HTTP_FORWARDED_FOR'))
            $ipaddress = getenv('HTTP_FORWARDED_FOR');
        else if (getenv('HTTP_FORWARDED'))
            $ipaddress = getenv('HTTP_FORWARDED');
        else
            $ipaddress = 'UNKNOWN';
        return $ipaddress;
    }

    public function add_tabs() {
        $screen = get_current_screen();
    
        if ( ! $screen) {
            return;
        }

        /*
        ==========================================
            General information Tab | Start
        ==========================================
        */
        
        $general_tab_title   = __( 'General Information', 'quiz-maker');

        $content = array();

        $content[] = '<div class="ays-quiz-help-tab-conatiner">';

            $content[] = '<div class="ays-quiz-help-tab-title">';
                $content[] = __( "Quiz Maker Information", 'quiz-maker' );
            $content[] = '</div>';


            $content[] = '<div class="ays-quiz-help-tab-row">';

                $content[] = '<div class="ays-quiz-help-tab-box">';
                    $content[] = '<span>';

                        $content[] = sprintf(
                            __( 'Create engaging quizzes, tests, and exams within a few minutes with the help of the WordPress Quiz Maker plugin. The Quiz Maker has a user-friendly interface and responsive design.%s With this plugin, you are free to add as many questions as needed with the following question types: %sRadio, Checkbox, Dropdown, Text, Short Text, Number, Date.%s In order, to activate Integrations, send Certificates via Email, or create Intervals for your quiz results you will need to download and install the Pro Versions of the WordPress Quiz plugin.', 'quiz-maker' ),
                            '<br>',
                            '<em>',
                            '</em><br><br>'
                        );

                    $content[] = '</span>';
                $content[] = '</div>';

            $content[] = '</div>';
        $content[] = '</div>';

        $content_genereal_info = implode( '', $content );

        /*
        ==========================================
            General information Tab | End
        ==========================================
        */

        /*
        ==========================================
            Premium information Tab | Start
        ==========================================
        */

        $premium_tab_title   = __( 'Premium version', 'quiz-maker');

        $content = array();

        $content[] = '<div class="ays-quiz-help-tab-conatiner">';

            $content[] = '<div class="ays-quiz-help-tab-title">';
                $content[] = __( "Premium versions' overview", 'quiz-maker' );
            $content[] = '</div>';

                $content[] = '<div class="ays-quiz-dicount-wrap-box">';
                    $content[] = '<span>';

                        $content[] = sprintf(
                            __( 'By activating the pro version, you will get all the features to strive your WordPress website’s quizzes to an advanced level.%sWith the WordPress Quiz plugin, it is easy to generate the quiz types like %sTrivia quiz, Assessment quiz, Personality test,  Multiple-choice quiz, Knowledge quiz, IQ test, Yes-or-no quiz, True-or-false quiz, This-or-that quiz(with images), Diagnostic quiz, Scored quiz, Buzzfeed quiz, Viral Quiz%s and etc.%sMotivate your visitors with Certificates and Advanced Leaderboards, prevent cheating during online exams with Timer-Based quizzes, earn money with Paid Quizzes.', 'quiz-maker' ),
                            '<br>',
                            '<em>',
                            '</em>',
                            '</br></br>'
                        );

                    $content[] = '</span>';
            $content[] = '</div>';

        $content[] = '</div>';

        $content_premium_info = implode( '', $content );

        /*
        ==========================================
            Premium information Tab | End
        ==========================================
        */

        /*
        ==========================================
            Sidebar information | Start
        ==========================================
        */

        $sidebar_content = '
        <p><strong>' . __( 'For more information:', 'quiz-maker' ) . '</strong></p>' .
        '<p><a href="https://www.youtube.com/@AysProPlugins" target="_blank">' . __( 'YouTube video tutorials' , 'quiz-maker' ) . '</a></p>' .
        '<p><a href="https://quiz-plugin.com/docs/" target="_blank">' . __( 'Documentation', 'quiz-maker' ) . '</a></p>' .
        '<p><a href="https://ays-pro.com/wordpress/quiz-maker?utm_source=dashboard&utm_medium=quiz-free&utm_campaign=help-tab-'. esc_attr( AYS_QUIZ_UTM_VERSION ) .'" target="_blank">' . __( 'Quiz Maker plugin premium version', 'quiz-maker' ) . '</a></p>' .
        '<p><a href="https://quiz-plugin.com/wordpress-quiz-plugin-free-demo" target="_blank">' . __( 'Quiz Maker plugin free demo', 'quiz-maker' ) . '</a></p>';

        /*
        ==========================================
            Sidebar information | End
        ==========================================
        */


        $general_tab_content = array(
            'id'      => 'quiz-maker-general-tab',
            'title'   => $general_tab_title,
            'content' => $content_genereal_info
        );

        $premium_tab_content = array(
            'id'      => 'quiz-maker-premium-tab',
            'title'   => $premium_tab_title,
            'content' => $content_premium_info
        );
        
        $screen->add_help_tab($general_tab_content);
        $screen->add_help_tab($premium_tab_content);

        $screen->set_help_sidebar($sidebar_content);
    }

    public function get_next_or_prev_row_by_id( $id, $type = "next", $table = "aysquiz_questions" ) {
        global $wpdb;

        if ( is_null( $table ) || empty( $table ) ) {
            return null;
        }

        $ays_table = esc_sql( $wpdb->prefix . $table );

        $where = array();
        $where_condition = "";

        $id     = (isset( $id ) && $id != "" && absint($id) != 0) ? absint( sanitize_text_field( $id ) ) : null;
        $type   = (isset( $type ) && $type != "") ? sanitize_text_field( $type ) : "next";

        if ( is_null( $id ) || $id == 0 ) {
            return null;
        }

        switch ( $type ) {
            case 'prev':
                $where[] = ' `id` < ' . $id . ' ORDER BY `id` DESC ';
                break;
            case 'next':
            default:
                $where[] = ' `id` > ' . $id;
                break;
        }

        if( ! empty($where) ){
            $where_condition = " WHERE " . implode( " AND ", $where );
        }

        $sql = "SELECT `id` FROM {$ays_table} ". $where_condition ." LIMIT 1;";

        $results = $wpdb->get_row( $sql, 'ARRAY_A' );

        return $results;

    }

    public static function ays_quiz_ays_quiz_get_quizzes(){
        global $wpdb;
        $quiz_table = esc_sql( $wpdb->prefix . "aysquiz_quizes" );

        $sql = "SELECT id,title
                FROM {$quiz_table} WHERE `published` = 1";

        $quizzes = $wpdb->get_results( $sql , "ARRAY_A" );

        return $quizzes;
    }

    public function ays_quiz_update_banner_time(){

        $date = time() + ( 3 * 24 * 60 * 60 ) + (int) ( get_option( 'gmt_offset' ) * HOUR_IN_SECONDS);
        // $date = time() + ( 60 ) + (int) ( get_option( 'gmt_offset' ) * HOUR_IN_SECONDS); // for testing | 1 min
        $next_3_days = date('M d, Y H:i:s', $date);

        $ays_quiz_banner_time = get_option('ays_quiz_mega_bundle_banner_time');

        if ( !$ays_quiz_banner_time || is_null( $ays_quiz_banner_time ) ) {
            update_option('ays_quiz_mega_bundle_banner_time', $next_3_days ); 
        }

        $get_ays_quiz_banner_time = get_option('ays_quiz_mega_bundle_banner_time');

        $val = 60*60*24*0.5; // half day
        // $val = 60; // for testing | 1 min

        $current_date = current_time( 'mysql' );
        $date_diff = strtotime($current_date) - intval(strtotime($get_ays_quiz_banner_time));

        $days_diff = $date_diff / $val;
        if(intval($days_diff) > 0 ){
            update_option('ays_quiz_mega_bundle_banner_time', $next_3_days);
        }

        return $get_ays_quiz_banner_time;
    }

    public function ays_quiz_author_user_search() {

        $content_text = array(
            'results' => array()
        );

        if( current_user_can( 'manage_options' ) ){
            $search = isset($_REQUEST['search']) && $_REQUEST['search'] != '' ? sanitize_text_field( $_REQUEST['search'] ) : null;
            $checked = isset($_REQUEST['val']) && $_REQUEST['val'] !='' ? sanitize_text_field( $_REQUEST['val'] ) : null;

            $args = 'search=';
            if($search !== null){
                $args .= '*';
                $args .= $search;
                $args .= '*';
            }

            $users = get_users($args);

            foreach ($users as $key => $value) {
                if ($checked !== null) {
                    if ( !is_array( $checked ) ) {
                        $checked2 = $checked;
                        $checked = array();
                        $checked[] = absint($checked2);
                    }
                    if (in_array($value->ID, $checked)) {
                        continue;
                    }else{
                        $content_text['results'][] = array(
                            'id' => $value->ID,
                            'text' => $value->data->display_name,
                        );
                    }
                }else{
                    $content_text['results'][] = array(
                        'id' => $value->ID,
                        'text' => $value->data->display_name,
                    );
                }
            }
        }

        ob_end_clean();
        echo json_encode($content_text);
        wp_die();
    }

    public function ays_quiz_generate_message_vars_html( $quiz_message_vars ) {
        $content = array();
        $var_counter = 0; 

        $content[] = '<div class="ays-quiz-message-vars-box">';
            $content[] = '<div class="ays-quiz-message-vars-icon">';
                $content[] = '<div>';
                    $content[] = '<i class="ays_fa ays_fa_link"></i>';
                $content[] = '</div>';
                $content[] = '<div>';
                    $content[] = '<span>'. __("Message Variables" , 'quiz-maker') .'</span>';
                    $content[] = '<a class="ays_help" data-toggle="tooltip" data-html="true" title="'. __("Insert your preferred message variable into the editor by clicking." , 'quiz-maker') .'">';
                        $content[] = '<i class="ays_fa ays_fa_info_circle"></i>';
                    $content[] = '</a>';
                $content[] = '</div>';
            $content[] = '</div>';
            $content[] = '<div class="ays-quiz-message-vars-data">';
                foreach($quiz_message_vars as $var => $var_name){
                    $var_counter++;
                    $content[] = '<label class="ays-quiz-message-vars-each-data-label">';
                        // $content[] = '<input type="radio" class="ays-quiz-message-vars-each-data-checker" hidden id="ays_quiz_message_var_count_'. $var_counter .'" name="ays_quiz_message_var_count">';
                        $content[] = '<div class="ays-quiz-message-vars-each-data">';
                            $content[] = '<input type="hidden" class="ays-quiz-message-vars-each-var" value="'. $var .'">';
                            $content[] = '<span>'. $var_name .'</span>';
                        $content[] = '</div>';
                    $content[] = '</label>';
                }
            $content[] = '</div>';
        $content[] = '</div>';

        $content = implode( '', $content );

        return $content;
    }

    /**
     * Determine if the plugin/addon installations are allowed.
     *
     * @since 6.4.0.4
     *
     * @param string $type Should be `plugin` or `addon`.
     *
     * @return bool
     */
    public static function ays_quiz_can_install( $type ) {

        return self::ays_quiz_can_do( 'install', $type );
    }

    /**
     * Determine if the plugin/addon activations are allowed.
     *
     * @since 6.4.0.4
     *
     * @param string $type Should be `plugin` or `addon`.
     *
     * @return bool
     */
    public static function ays_quiz_can_activate( $type ) {

        return self::ays_quiz_can_do( 'activate', $type );
    }

    /**
     * Determine if the plugin/addon installations/activations are allowed.
     *
     * @since 6.4.0.4
     *
     * @param string $what Should be 'activate' or 'install'.
     * @param string $type Should be `plugin` or `addon`.
     *
     * @return bool
     */
    public static function ays_quiz_can_do( $what, $type ) {

        if ( ! in_array( $what, array( 'install', 'activate' ), true ) ) {
            return false;
        }

        if ( ! in_array( $type, array( 'plugin', 'addon' ), true ) ) {
            return false;
        }

        $capability = $what . '_plugins';

        if ( ! current_user_can( $capability ) ) {
            return false;
        }

        // Determine whether file modifications are allowed and it is activation permissions checking.
        if ( $what === 'install' && ! wp_is_file_mod_allowed( 'ays_quiz_can_install' ) ) {
            return false;
        }

        // All plugin checks are done.
        if ( $type === 'plugin' ) {
            return true;
        }
        return false;
    }

    /**
     * Activate plugin.
     *
     * @since 1.0.0
     * @since 6.4.0.4 Updated the permissions checking.
     */
    public function ays_quiz_activate_plugin() {

        // Run a security check.
        check_ajax_referer( $this->plugin_name . '-install-plugin-nonce', sanitize_key( $_REQUEST['_ajax_nonce'] ) );

        // Check for permissions.
        if ( ! current_user_can( 'activate_plugins' ) ) {
            wp_send_json_error( esc_html__( 'Plugin activation is disabled for you on this site.', 'quiz-maker' ) );
        }

        $type = 'addon';

        if ( isset( $_POST['plugin'] ) ) {

            if ( ! empty( $_POST['type'] ) ) {
                $type = sanitize_key( $_POST['type'] );
            }

            $plugin   = sanitize_text_field( wp_unslash( $_POST['plugin'] ) );
            $activate = activate_plugins( $plugin );

            if ( ! is_wp_error( $activate ) ) {
                if ( $type === 'plugin' ) {
                    wp_send_json_success( esc_html__( 'Plugin activated.', 'quiz-maker' ) );
                } else {
                        ( esc_html__( 'Addon activated.', 'quiz-maker' ) );
                }
            }
        }

        if ( $type === 'plugin' ) {
            wp_send_json_error( esc_html__( 'Could not activate the plugin. Please activate it on the Plugins page.', 'quiz-maker' ) );
        }

        wp_send_json_error( esc_html__( 'Could not activate the addon. Please activate it on the Plugins page.', 'quiz-maker' ) );
    }

    /**
     * Install addon.
     *
     * @since 1.0.0
     * @since 6.4.0.4 Updated the permissions checking.
     */
    public function ays_quiz_install_plugin() {

        // Run a security check.
        check_ajax_referer( $this->plugin_name . '-install-plugin-nonce', sanitize_key( $_REQUEST['_ajax_nonce'] ) );

        $generic_error = esc_html__( 'There was an error while performing your request.', 'quiz-maker' );
        $type          = ! empty( $_POST['type'] ) ? sanitize_key( $_POST['type'] ) : '';

        // Check if new installations are allowed.
        if ( ! self::ays_quiz_can_install( $type ) ) {
            wp_send_json_error( $generic_error );
        }

        $error = $type === 'plugin'
            ? esc_html__( 'Could not install the plugin. Please download and install it manually.', 'quiz-maker' )
            : "";

        $plugin_url = ! empty( $_POST['plugin'] ) ? esc_url_raw( wp_unslash( $_POST['plugin'] ) ) : '';

        if ( empty( $plugin_url ) ) {
            wp_send_json_error( $error );
        }

        // Prepare variables.
        $url = esc_url_raw(
            add_query_arg(
                [
                    'page' => 'quiz-maker-featured-plugins',
                ],
                admin_url( 'admin.php' )
            )
        );

        ob_start();
        $creds = request_filesystem_credentials( $url, '', false, false, null );

        // Hide the filesystem credentials form.
        ob_end_clean();

        // Check for file system permissions.
        if ( $creds === false ) {
            wp_send_json_error( $error );
        }
        
        if ( ! WP_Filesystem( $creds ) ) {
            wp_send_json_error( $error );
        }

        /*
         * We do not need any extra credentials if we have gotten this far, so let's install the plugin.
         */
        require_once AYS_QUIZ_DIR . 'includes/admin/class-quiz-maker-upgrader.php';
        require_once AYS_QUIZ_DIR . 'includes/admin/class-quiz-maker-install-skin.php';
        require_once AYS_QUIZ_DIR . 'includes/admin/class-quiz-maker-skin.php';


        // Do not allow WordPress to search/download translations, as this will break JS output.
        remove_action( 'upgrader_process_complete', array( 'Language_Pack_Upgrader', 'async_upgrade' ), 20 );

        // Create the plugin upgrader with our custom skin.
        $installer = new QuizMaker\Helpers\QuizMakerPluginSilentUpgrader( new Quiz_Maker_Install_Skin() );

        // Error check.
        if ( ! method_exists( $installer, 'install' ) ) {
            wp_send_json_error( $error );
        }

        $installer->install( $plugin_url );

        // Flush the cache and return the newly installed plugin basename.
        wp_cache_flush();

        $plugin_basename = $installer->plugin_info();

        if ( empty( $plugin_basename ) ) {
            wp_send_json_error( $error );
        }

        $result = array(
            'msg'          => $generic_error,
            'is_activated' => false,
            'basename'     => $plugin_basename,
        );

        // Check for permissions.
        if ( ! current_user_can( 'activate_plugins' ) ) {
            $result['msg'] = $type === 'plugin' ? esc_html__( 'Plugin installed.', 'quiz-maker' ) : "";

            wp_send_json_success( $result );
        }

        // Activate the plugin silently.
        $activated = activate_plugin( $plugin_basename );
        remove_action( 'activated_plugin', array( 'ays_sccp_activation_redirect_method', 'gallery_p_gallery_activation_redirect_method', 'poll_maker_activation_redirect_method' ), 100 );

        if ( ! is_wp_error( $activated ) ) {

            $result['is_activated'] = true;
            $result['msg']          = $type === 'plugin' ? esc_html__( 'Plugin installed and activated.', 'quiz-maker' ) : esc_html__( 'Addon installed and activated.', 'quiz-maker' );

            wp_send_json_success( $result );
        }

        // Fallback error just in case.
        wp_send_json_error( $result );
    }

    /**
     * List of AM plugins that we propose to install.
     *
     * @since 6.4.0.4
     *
     * @return array
     */
    protected function get_am_plugins() {
        if ( !isset( $_SESSION ) ) {
            session_start();
        }

        $images_url = AYS_QUIZ_ADMIN_URL . '/images/icons/';

        $plugin_slug = array(
            'fox-lms',
            'poll-maker',
            'survey-maker',
            'ays-popup-box',
            // 'gallery-photo-gallery',
            'secure-copy-content-protection',
            'personal-dictionary',
        );

        $plugin_url_arr = array();
        foreach ($plugin_slug as $key => $slug) {
            if ( isset( $_SESSION['ays_quiz_our_product_links'] ) && !empty( $_SESSION['ays_quiz_our_product_links'] ) 
                && isset( $_SESSION['ays_quiz_our_product_links'][$slug] ) && !empty( $_SESSION['ays_quiz_our_product_links'][$slug] ) ) {
                $plugin_url = (isset( $_SESSION['ays_quiz_our_product_links'][$slug] ) && $_SESSION['ays_quiz_our_product_links'][$slug] != "") ? esc_url( $_SESSION['ays_quiz_our_product_links'][$slug] ) : "";
            } else {
                $latest_version = $this->ays_quiz_get_latest_plugin_version($slug);
                $plugin_url = 'https://downloads.wordpress.org/plugin/'. $slug .'.zip';
                if ( $latest_version != '' ) {
                    $plugin_url = 'https://downloads.wordpress.org/plugin/'. $slug .'.'. $latest_version .'.zip';
                    $_SESSION['ays_quiz_our_product_links'][$slug] = $plugin_url;
                }
            }

            $plugin_url_arr[$slug] = $plugin_url;
        }

        $plugins_array = array(
            'fox-lms/fox-lms.php'        => array(
                'icon'        => $images_url . 'icon-fox-lms-128x128.png',
                'name'        => __( 'Fox LMS', 'quiz-maker' ),
                'desc'        => __( 'Build and manage online courses directly on your WordPress site.', 'quiz-maker' ),
                'desc_hidden' => __( 'With the FoxLMS plugin, you can create, sell, and organize courses, lessons, and quizzes, transforming your website into a dynamic e-learning platform.', 'quiz-maker' ),
                'wporg'       => 'https://wordpress.org/plugins/fox-lms/',
                'buy_now'     => 'https://foxlms.com/pricing/?utm_source=dashboard&utm_medium=quiz-free&utm_campaign=fox-lms-our-products-page',
                'url'         => $plugin_url_arr['fox-lms'],
            ),
           'poll-maker/poll-maker-ays.php'        => array(
                'icon'        => $images_url . 'icon-poll-128x128.png',
                'name'        => __( 'Poll Maker', 'quiz-maker' ),
                'desc'        => __( 'Create amazing online polls for your WordPress website super easily.', 'quiz-maker' ),
                'desc_hidden' => __( 'Build up various types of polls in a minute and get instant feedback on any topic or product.', 'quiz-maker' ),
                'wporg'       => 'https://wordpress.org/plugins/poll-maker/',
                'buy_now'     => 'https://ays-pro.com/wordpress/poll-maker/',
                'url'         => $plugin_url_arr['poll-maker'],
            ),
            'survey-maker/survey-maker.php'        => array(
                'icon'        => $images_url . 'icon-survey-128x128.png',
                'name'        => __( 'Survey Maker', 'quiz-maker' ),
                'desc'        => __( 'Make amazing online surveys and get real-time feedback quickly and easily.', 'quiz-maker' ),
                'desc_hidden' => __( 'Learn what your website visitors want, need, and expect with the help of Survey Maker. Build surveys without limiting your needs.', 'quiz-maker' ),
                'wporg'       => 'https://wordpress.org/plugins/survey-maker/',
                'buy_now'     => 'https://ays-pro.com/wordpress/survey-maker',
                'url'         => $plugin_url_arr['survey-maker'],
            ),
            'ays-popup-box/ays-pb.php'        => array(
                'icon'        => $images_url . 'icon-popup-128x128.png',
                'name'        => __( 'Popup Box', 'quiz-maker' ),
                'desc'        => __( 'Popup everything you want! Create informative and promotional popups all in one plugin.', 'quiz-maker' ),
                'desc_hidden' => __( 'Attract your visitors and convert them into email subscribers and paying customers.', 'quiz-maker' ),
                'wporg'       => 'https://wordpress.org/plugins/ays-popup-box/',
                'buy_now'     => 'https://ays-pro.com/wordpress/popup-box/',
                'url'         => $plugin_url_arr['ays-popup-box'],
            ),
            // 'gallery-photo-gallery/gallery-photo-gallery.php'        => array(
            //     'icon'        => $images_url . 'icon-gallery-128x128.jpg',
            //     'name'        => __( 'Gallery Photo Gallery', 'quiz-maker' ),
            //     'desc'        => __( 'Create unlimited galleries and include unlimited images in those galleries.', 'quiz-maker' ),
            //     'desc_hidden' => __( 'Represent images in an attractive way. Attract people with your own single and multiple free galleries from your photo library.', 'quiz-maker' ),
            //     'wporg'       => 'https://wordpress.org/plugins/gallery-photo-gallery/',
            //     'buy_now'     => 'https://ays-pro.com/wordpress/photo-gallery/',
            //     'url'         => $plugin_url_arr['gallery-photo-gallery'],
            // ),
            'secure-copy-content-protection/secure-copy-content-protection.php'        => array(
                'icon'        => $images_url . 'icon-sccp-128x128.png',
                'name'        => __( 'Secure Copy Content Protection', 'quiz-maker' ),
                'desc'        => __( 'Disable the right click, copy paste, content selection and copy shortcut keys on your website.', 'quiz-maker' ),
                'desc_hidden' => __( 'Protect web content from being plagiarized. Prevent plagiarism from your website with this easy to use plugin.', 'quiz-maker' ),
                'wporg'       => 'https://wordpress.org/plugins/secure-copy-content-protection/',
                'buy_now'     => 'https://ays-pro.com/wordpress/secure-copy-content-protection/',
                'url'         => $plugin_url_arr['secure-copy-content-protection'],
            ),
            'personal-dictionary/personal-dictionary.php'        => array(
                'icon'        => $images_url . 'pd-logo-128x128.png',
                'name'        => __( 'Personal Dictionary', 'quiz-maker' ),
                'desc'        => __( 'Allow your students to create personal dictionary, study and memorize the words.', 'quiz-maker' ),
                'desc_hidden' => __( 'Allow your users to create their own digital dictionaries and learn new words and terms as fastest as possible.', 'quiz-maker' ),
                'wporg'       => 'https://wordpress.org/plugins/personal-dictionary/',
                'buy_now'     => 'https://ays-pro.com/wordpress/personal-dictionary/',
                'url'         => $plugin_url_arr['personal-dictionary'],
                // 'pro'   => array(
                //     'plug' => '',
                //     'icon' => '',
                //     'name' => '',
                //     'desc' => '',
                //     'url'  => '',
                //     'act'  => 'go-to-url',
                // ),
            ),
        );

        return $plugins_array;
    }

    protected function ays_quiz_get_latest_plugin_version( $slug ){

        if ( is_null( $slug ) || empty($slug) ) {
            return "";
        }

        $version_latest = "";

        if ( ! function_exists( 'plugins_api' ) ) {
              require_once( ABSPATH . 'wp-admin/includes/plugin-install.php' );
        }

        // set the arguments to get latest info from repository via API ##
        $args = array(
            'slug' => $slug,
            'fields' => array(
                'version' => true,
            )
        );

        /** Prepare our query */
        $call_api = plugins_api( 'plugin_information', $args );

        /** Check for Errors & Display the results */
        if ( is_wp_error( $call_api ) ) {
            $api_error = $call_api->get_error_message();
        } else {

            //echo $call_api; // everything ##
            if ( ! empty( $call_api->version ) ) {
                $version_latest = $call_api->version;
            }
        }

        return $version_latest;
    }

    /**
     * Get AM plugin data to display in the Addons section of About tab.
     *
     * @since 6.4.0.4
     *
     * @param string $plugin      Plugin slug.
     * @param array  $details     Plugin details.
     * @param array  $all_plugins List of all plugins.
     *
     * @return array
     */
    protected function get_plugin_data( $plugin, $details, $all_plugins ) {

        $have_pro = ( ! empty( $details['pro'] ) && ! empty( $details['pro']['plug'] ) );
        $show_pro = false;

        $plugin_data = array();

        if ( $have_pro ) {
            if ( array_key_exists( $plugin, $all_plugins ) ) {
                if ( is_plugin_active( $plugin ) ) {
                    $show_pro = true;
                }
            }
            if ( array_key_exists( $details['pro']['plug'], $all_plugins ) ) {
                $show_pro = true;
            }
            if ( $show_pro ) {
                $plugin  = $details['pro']['plug'];
                $details = $details['pro'];
            }
        }

        if ( array_key_exists( $plugin, $all_plugins ) ) {
            if ( is_plugin_active( $plugin ) ) {
                // Status text/status.
                $plugin_data['status_class'] = 'status-active';
                $plugin_data['status_text']  = esc_html__( 'Active', 'quiz-maker' );
                // Button text/status.
                $plugin_data['action_class'] = $plugin_data['status_class'] . ' ays-quiz-card__btn-info disabled';
                $plugin_data['action_text']  = esc_html__( 'Activated', 'quiz-maker' );
                $plugin_data['plugin_src']   = esc_attr( $plugin );
            } else {
                // Status text/status.
                $plugin_data['status_class'] = 'status-installed';
                $plugin_data['status_text']  = esc_html__( 'Inactive', 'quiz-maker' );
                // Button text/status.
                $plugin_data['action_class'] = $plugin_data['status_class'] . ' ays-quiz-card__btn-info';
                $plugin_data['action_text']  = esc_html__( 'Activate', 'quiz-maker' );
                $plugin_data['plugin_src']   = esc_attr( $plugin );
            }
        } else {
            // Doesn't exist, install.
            // Status text/status.
            $plugin_data['status_class'] = 'status-missing';

            if ( isset( $details['act'] ) && 'go-to-url' === $details['act'] ) {
                $plugin_data['status_class'] = 'status-go-to-url';
            }
            $plugin_data['status_text'] = esc_html__( 'Not Installed', 'quiz-maker' );
            // Button text/status.
            $plugin_data['action_class'] = $plugin_data['status_class'] . ' ays-quiz-card__btn-info';
            $plugin_data['action_text']  = esc_html__( 'Install Plugin', 'quiz-maker' );
            $plugin_data['plugin_src']   = esc_url( $details['url'] );
        }

        $plugin_data['details'] = $details;

        return $plugin_data;
    }

    /**
     * Display the Addons section of About tab.
     *
     * @since 6.4.0.4
     */
    public function output_about_addons() {

        if ( ! function_exists( 'get_plugins' ) ) {
            require_once ABSPATH . 'wp-admin/includes/plugin.php';
        }

        $all_plugins          = get_plugins();
        $am_plugins           = $this->get_am_plugins();
        $can_install_plugins  = self::ays_quiz_can_install( 'plugin' );
        $can_activate_plugins = self::ays_quiz_can_activate( 'plugin' );

        $content = '';
        $content.= '<div class="ays-quiz-cards-block">';
        foreach ( $am_plugins as $plugin => $details ){

            $plugin_data              = $this->get_plugin_data( $plugin, $details, $all_plugins );
            $plugin_ready_to_activate = $can_activate_plugins
                && isset( $plugin_data['status_class'] )
                && $plugin_data['status_class'] === 'status-installed';
            $plugin_not_activated     = ! isset( $plugin_data['status_class'] )
                || $plugin_data['status_class'] !== 'status-active';

            $plugin_action_class = ( isset( $plugin_data['action_class'] ) && esc_attr( $plugin_data['action_class'] ) != "" ) ? esc_attr( $plugin_data['action_class'] ) : "";

            $plugin_action_class_disbaled = "";
            if ( strpos($plugin_action_class, 'status-active') !== false ) {
                $plugin_action_class_disbaled = "disbaled='true'";
            }

            $content .= '
                <div class="ays-quiz-card">
                    <div class="ays-quiz-card__content flexible">
                        <div class="ays-quiz-card__content-img-box">
                            <img class="ays-quiz-card__img" src="'. esc_url( $plugin_data['details']['icon'] ) .'" alt="'. esc_attr( $plugin_data['details']['name'] ) .'">
                        </div>
                        <div class="ays-quiz-card__text-block">
                            <h5 class="ays-quiz-card__title">'. esc_html( $plugin_data['details']['name'] ) .'</h5>
                            <p class="ays-quiz-card__text">'. wp_kses_post( $plugin_data['details']['desc'] ) .'
                                <span class="ays-quiz-card__text-hidden">
                                    '. wp_kses_post( $plugin_data['details']['desc_hidden'] ) .'
                                </span>
                            </p>
                        </div>
                    </div>
                    <div class="ays-quiz-card__footer">';
                        if ( $can_install_plugins || $plugin_ready_to_activate || ! $details['wporg'] ) {
                            $content .= '<button class="'. esc_attr( $plugin_data['action_class'] ) .'" data-plugin="'. esc_attr( $plugin_data['plugin_src'] ) .'" data-type="plugin" '. $plugin_action_class_disbaled .'>
                                '. wp_kses_post( $plugin_data['action_text'] ) .'
                            </button>';
                        }
                        elseif ( $plugin_not_activated ) {
                            $content .= '<a href="'. esc_url( $details['wporg'] ) .'" target="_blank" rel="noopener noreferrer">
                                '. esc_html_e( 'WordPress.org', 'quiz-maker' ) .'
                                <span aria-hidden="true" class="dashicons dashicons-external"></span>
                            </a>';
                        }
            $content .='
                        <a target="_blank" href="'. esc_url( $plugin_data['details']['buy_now'] ) .'" class="ays-quiz-card__btn-primary">'. __('Buy Now', 'quiz-maker') .'</a>
                    </div>
                </div>';
        }
        $install_plugin_nonce = wp_create_nonce( $this->plugin_name . '-install-plugin-nonce' );
        $content.= '<input type="hidden" id="ays_quiz_ajax_install_plugin_nonce" name="ays_quiz_ajax_install_plugin_nonce" value="'. $install_plugin_nonce .'">';
        $content.= '</div>';

        echo $content;
    }


    public function ays_quiz_footer_popup_box_banner() {
        global $wpdb;
        $sql = "SELECT COUNT(*) FROM {$wpdb->prefix}aysquiz_reports";
        $results_count = $wpdb->get_var($sql);

        if( current_user_can( 'manage_options' ) && $results_count >= 1000 ){

            $content = array();

            $content[] = '<div class="ays-quiz-popup-box-main-conatiner">';
                $content[] = '<div class="ays-quiz-popup-box-conatiner">';

                    $content[] = '<div class="ays-quiz-popup-box-header">';
                        $content[] = '<div class="ays-quiz-popup-box-header-title-row">';
                            $content[] = '<div class="ays-quiz-popup-box-header-title">';
                                $content[] = sprintf( __( "You've got %s quiz results!", 'quiz-maker' ),
                                            $results_count
                                            );
                            $content[] = '</div>';
                        $content[] = '</div>';

                        $content[] = '<div class="ays-quiz-popup-box-header-desc-row">';
                            $content[] = '<div class="ays-quiz-popup-box-header-desc">';
                                $content[] ='Upgrade now and enjoy a ' . '<a href="https://ays-pro.com/wordpress/quiz-maker?utm_source=dashboard&utm_medium=quiz-free&utm_campaign=result-popup-box-banner'. esc_attr( AYS_QUIZ_UTM_VERSION ) .'" class="ays-quiz-popup-box-header-desc-a" target="blank"><span class="ays-quiz-popup-box-header-desc-span">' . '20%'. '</a></span>' .' discount.';
                            $content[] = '</div>';
                        $content[] = '</div>';

                        $content[] = '<div class="ays-quiz-popup-box-header-img-row">';
                            $content[] = '<div class="ays-quiz-popup-box-header-img">';
                                $content[] = '<img src="'.  AYS_QUIZ_ADMIN_URL.'/images/banner/ays-quiz-popup-banner-party-popper-icon.webp">';
                            $content[] = '</div>';
                        $content[] = '</div>';

                        $content[] = '<div class="ays-quiz-popup-box-header-close-button">';
                                $content[] = '<img src="'.  AYS_QUIZ_ADMIN_URL.'/images/banner/ays-quiz-popup-banner-close-icon.svg">';
                        $content[] = '</div>';

                    $content[] = '</div>';


                    $content[] = '<div class="ays-quiz-popup-box-content">';

                        $content[] = '<div class="ays-quiz-popup-box-content-option-list-row">';
                            $content[] = '<div class="ays-quiz-popup-box-content-option-list">';
                                $content[] = '<ul class="ays-quiz-popup-box-content-option-list-ul">';
                                    $content[] = '<li class="ays-quiz-popup-box-content-option-list-ul-li">'. __("Export Results", 'quiz-maker') .'</li>';
                                    $content[] = '<li class="ays-quiz-popup-box-content-option-list-ul-li">'. __("Custom Fields", 'quiz-maker') .'</li>';
                                    $content[] = '<li class="ays-quiz-popup-box-content-option-list-ul-li">'. __("Leaderboards", 'quiz-maker') .'</li>';
                                    $content[] = '<li class="ays-quiz-popup-box-content-option-list-ul-li">'. __("Send Email & Certificate", 'quiz-maker') .'</li>';
                                    $content[] = '<li class="ays-quiz-popup-box-content-option-list-ul-li">'. __("10+ Integrations", 'quiz-maker') .'</li>';
                                    $content[] = '<li class="ays-quiz-popup-box-content-option-list-ul-li">'. __("Premium Support", 'quiz-maker') .'</li>';
                                    $content[] = '<li class="ays-quiz-popup-box-content-option-list-ul-li">'. __("Personalize Result", 'quiz-maker') .'</li>';
                                    $content[] = '<li class="ays-quiz-popup-box-content-option-list-ul-li">'. __("Lifetime Usage", 'quiz-maker') .'</li>';
                                $content[] = '</ul>';
                            $content[] = '</div>';
                        $content[] = '</div>';

                    $content[] = '</div>';


                    $content[] = '<div class="ays-quiz-popup-box-action">';
                        $content[] = '<div class="ays-quiz-popup-box-action-button-row">';
                            $content[] = '<a href="https://ays-pro.com/wordpress/quiz-maker?utm_source=dashboard&utm_medium=quiz-free&utm_campaign=result-popup-box-banner-'.esc_attr( AYS_QUIZ_UTM_VERSION ) .'" target="blank" class="ays-quiz-popup-box-action-button">';
                                $content[] = __( "Upgrade", 'quiz-maker' );
                            $content[] = '</a>';
                        $content[] = '</div>';
                    $content[] = '</div>';

                    $content[] = '<div class="ays-quiz-popup-box-footer">';

                    $content[] = '</div>';

                $content[] = '</div>';
            $content[] = '</div>';

            $content = implode( '', $content );

            echo $content;
        }

    }

    public static function ays_quiz_check_if_current_image_exists( $image_url ) {
        global $wpdb;

        $res = true;
        if( !isset($image_url) ){
            $res = false;
        }

        if ( isset($image_url) && !empty( $image_url ) ) {

            $re = '/-\d+[Xx]\d+\./';
            $subst = '.';

            $image_url = preg_replace($re, $subst, $image_url, 1);

            $attachment = $wpdb->get_col($wpdb->prepare("SELECT ID FROM $wpdb->posts WHERE guid='%s';", $image_url ));
            if ( is_null( $attachment ) || empty( $attachment ) ) {
                $res = false;
            }
        }

        return $res; 
    }

    // Integrations settings page action hook
    public function ays_quiz_settings_page_integrations_content( $args ){

        $integrations_contents = apply_filters( 'ays_qm_settings_page_integrations_contents', array(), $args );

        $integrations = array();

        foreach ($integrations_contents as $key => $integrations_content) {
            $content = '<fieldset>';
            if(isset($integrations_content['title'])){
                $content .= '<legend>';
                if(isset($integrations_content['icon'])){
                    $content .= '<img class="ays_integration_logo" src="'. esc_url($integrations_content['icon']) .'" alt="" style="margin-right: 10px;">';
                }
                $content .= '<h5>'. esc_html($integrations_content['title']) .'</h5></legend>';
            }
            if(isset($integrations_content['content'])){
                $content .= $integrations_content['content'];
            }

            $content .= '</fieldset>';

            $integrations[] = $content;
        }

        echo implode('<hr/>', $integrations);
    }

    public static function ays_quiz_svg_sanitize_allowed_properties(){

        $allowed_properties = array(
            'div' => array(
                'id'                    => true,
                'class'                 => true,
                'style'                 => true,
            ),
            'svg' => array(
                'class'                 => true,
                'version'               => true,
                'overflow'              => true,
                'preserveAspectRatio'   => true,
                'preserveaspectratio'   => true,
                'fill'                  => true,
                'viewBox'               => true,
                'viewbox'               => true,
                'height'                => true,
                'width'                 => true,
                'xmlns'                 => true,
                'xmlns:xlink'           => true,
                'id'                    => true,
                'title'                 => true,
            ),
            'g' => array(
                'id'                    => true,
                'class'                 => true,
                'stroke-width'          => true,
                'stroke-linecap'        => true,
                'stroke-linejoin'       => true,
            ),
            'path' => array(
                'id'                    => true,
                'class'                 => true,
                'd'                     => true,
                'fill'                  => true,
                'vector-effect'         => true,
                'xmlns:default'         => true,
            ),
        );

        return $allowed_properties;
    }

    public static function quiz_maker_capabilities(){

        $ays_user_roles = array('fox_instructor');
        
        $capability = 'manage_options';
        if(is_user_logged_in()){
            if ( is_plugin_active( 'fox-lms/fox-lms.php' )) {
                if( current_user_can( 'foxlms_edit_courses' ) && !current_user_can( 'manage_options' ) ){
                    $current_user = wp_get_current_user();
                    $current_user_roles = $current_user->roles;
                    $ishmar = 0;
                    foreach($current_user_roles as $r){
                        if(in_array($r, $ays_user_roles)){
                            $ishmar++;
                        }
                    }

                    if($ishmar > 0){
                        $capability = 'foxlms_edit_courses';
                    }
                }
            }
        }

        return $capability;
    }

    public function ays_quiz_exclude_custom_post_type_from_sitemap($exclude, $type){
        if($type == 'ays-quiz-maker'){
            return $type;
        }
    }

    public function ays_quiz_add_checklist_guide(){

        if(!empty($_REQUEST['page']) && sanitize_text_field( $_REQUEST['page'] ) != $this->plugin_name . "-getting-started"){
            if(false !== strpos( sanitize_text_field( $_REQUEST['page'] ), $this->plugin_name)){
                $current_user_id = get_current_user_id();
                $completed_steps = get_user_meta($current_user_id, 'ays_quiz_checklist_completed_steps', true);

                // Make sure we always get an array
                if (!is_array($completed_steps)) {
                    $completed_steps = array();
                }

                $popup_closed = get_user_meta($current_user_id, 'ays_quiz_checklist_popup_closed', true);

                $checklist_steps = array(
                    array(
                        'title' => esc_html__('Create questions', 'quiz-maker'),
                        'image' => esc_url( AYS_QUIZ_ADMIN_URL . '/images/checklist/checklist-guide-step-1.png' ),
                        'description' => esc_html__("Go to the Questions page and create your questions.", 'quiz-maker'),
                        'button_text' => esc_html__('Add Question', 'quiz-maker'),
                        'button_link' => esc_url( admin_url('admin.php') . "?page=".$this->plugin_name . "-questions" ),
                        'learn_more_link' => 'https://quiz-plugin.com/docs/creating-questions/',
                    ),
                    array(
                        'title' => esc_html__('Create quiz', 'quiz-maker'),
                        'image' => esc_url( AYS_QUIZ_ADMIN_URL . '/images/checklist/checklist-guide-step-2.png' ),
                        'description' => esc_html__("Go to the Quizzes page and click on Add New to create your first quiz.", 'quiz-maker'),
                        'button_text' => esc_html__('Add Quiz', 'quiz-maker'),
                        'button_link' => esc_url( admin_url('admin.php') . "?page=".$this->plugin_name ),
                        'learn_more_link' => 'https://quiz-plugin.com/docs/how-to-create-a-quiz/',
                    ),
                    array(
                        'title' => esc_html__('Insert questions', 'quiz-maker'),
                        'image' => esc_url( AYS_QUIZ_ADMIN_URL . '/images/checklist/checklist-guide-step-3.jpg' ),
                        'description' => esc_html__('After creating your questions insert them into a quiz by clicking on the "Insert Questions" button.', 'quiz-maker'),
                        'button_text' => esc_html__('Quizzes', 'quiz-maker'),
                        'button_link' => esc_url( admin_url('admin.php') . "?page=".$this->plugin_name ),
                        'learn_more_link' => 'https://quiz-plugin.com/docs/how-to-create-a-quiz/#1-toc-title',
                    ),
                    array(
                        'title' => esc_html__('Copy/paste shortcode', 'quiz-maker'),
                        'image' => esc_url( AYS_QUIZ_ADMIN_URL . '/images/checklist/checklist-guide-step-4.gif' ),
                        'description' => esc_html__("Copy the quiz shortcode and insert it into any post.", 'quiz-maker'),
                        'button_text' => esc_html__('Add new post', 'quiz-maker'),
                        'button_link' => esc_url( admin_url('post-new.php') ),
                        'learn_more_link' => 'https://quiz-plugin.com/docs/publish-quiz/',
                        'img_style' => 'object-fit:contain; height:auto;',
                    ),
                );
                ?>
                <div class="ays-quiz-checklist-panel" id="ays-quiz-checklist-container" style="<?php echo ($popup_closed) ? 'display: none;' : ''; ?>">
                    <header class="ays-quiz-checklist-header">
                        <h3><?php echo esc_html__('Create a Quiz with 4 Easy Steps', 'quiz-maker'); ?></h3>

                        <!-- Minimize Button -->
                        <button class="ays-quiz-checklist-minimize-btn" aria-label="Minimize">
                            <svg class="ays-quiz-checklist-minimize-icon-expanded" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                <path d="M6 12h12" stroke="#000" stroke-width="2" stroke-linecap="round"/>
                            </svg>
                            <svg class="ays-quiz-checklist-minimize-icon-collapsed" style="display:none;" width="24" height="24" viewBox="0 0 24 24">
                                <path fill-rule="evenodd" clip-rule="evenodd" d="M4 3.25H8C8.41421 3.25 8.75 3.58579 8.75 4C8.75 4.41421 8.41421 4.75 8 4.75H5.81066L10.5303 9.46967C10.8232 9.76256 10.8232 10.2374 10.5303 10.5303C10.2374 10.8232 9.76256 10.8232 9.46967 10.5303L4.75 5.81066V8C4.75 8.41421 4.41421 8.75 4 8.75C3.58579 8.75 3.25 8.41421 3.25 8V4C3.25 3.58579 3.58579 3.25 4 3.25ZM13.4697 13.4697C13.7626 13.1768 14.2374 13.1768 14.5303 13.4697L19.25 18.1893V16C19.25 15.5858 19.5858 15.25 20 15.25C20.4142 15.25 20.75 15.5858 20.75 16V20C20.75 20.4142 20.4142 20.75 20 20.75H16C15.5858 20.75 15.25 20.4142 15.25 20C15.25 19.5858 15.5858 19.25 16 19.25H18.1893L13.4697 14.5303C13.1768 14.2374 13.1768 13.7626 13.4697 13.4697Z"></path>
                            </svg>
                        </button>

                        <!-- Close Button -->
                        <button class="ays-quiz-checklist-close-btn" aria-label="Close"><svg width="24" height="24" class="" focusable="false" aria-hidden="true" viewBox="0 0 24 24"><path fill-rule="evenodd" clip-rule="evenodd" d="M18.5303 5.46967C18.8232 5.76256 18.8232 6.23744 18.5303 6.53033L6.53033 18.5303C6.23744 18.8232 5.76256 18.8232 5.46967 18.5303C5.17678 18.2374 5.17678 17.7626 5.46967 17.4697L17.4697 5.46967C17.7626 5.17678 18.2374 5.17678 18.5303 5.46967Z"></path><path fill-rule="evenodd" clip-rule="evenodd" d="M5.46967 5.46967C5.76256 5.17678 6.23744 5.17678 6.53033 5.46967L18.5303 17.4697C18.8232 17.7626 18.8232 18.2374 18.5303 18.5303C18.2374 18.8232 17.7626 18.8232 17.4697 18.5303L5.46967 6.53033C5.17678 6.23744 5.17678 5.76256 5.46967 5.46967Z"></path></svg></button>
                    </header>

                    <div class="ays-quiz-checklist-progress">
                        <div class="ays-quiz-checklist-progress-bar"><span style="width: 0%"></span></div>
                        <span class="ays-quiz-checklist-progress-text">0%</span>
                    </div>

                    <ul class="ays-quiz-checklist-items">
                    <?php foreach ($checklist_steps as $index => $step): 
                        $is_checked = in_array($index, $completed_steps);
                    ?>
                        <li class="ays-quiz-checklist-step<?php echo $is_checked ? ' completed' : ''; ?>" data-step="<?php echo esc_attr($index); ?>">
                            <div class="ays-quiz-checklist-header-row">
                                <label>
                                    <input type="checkbox" <?php checked($is_checked); ?> />
                                    <span class="ays-quiz-checklist-step-title"><?php echo esc_html($step['title']); ?></span>
                                </label>
                                <button class="ays-quiz-checklist-toggle-step" aria-label="Toggle Step">
                                    <svg width="16" height="16" class="MuiSvgIcon-root MuiSvgIcon-fontSizeMedium eui-1nf42ca" focusable="false" aria-hidden="true" viewBox="0 0 24 24"><path fill-rule="evenodd" clip-rule="evenodd" d="M5.46967 9.21967C5.76256 8.92678 6.23744 8.92678 6.53033 9.21967L12 14.6893L17.4697 9.21967C17.7626 8.92678 18.2374 8.92678 18.5303 9.21967C18.8232 9.51256 18.8232 9.98744 18.5303 10.2803L12.5303 16.2803C12.2374 16.5732 11.7626 16.5732 11.4697 16.2803L5.46967 10.2803C5.17678 9.98744 5.17678 9.51256 5.46967 9.21967Z"></path></svg>
                                </button>
                            </div>

                            <div class="ays-quiz-checklist-step-content" style="<?php echo $is_checked ? '' : 'display: none;'; ?>">
                                <img src="<?php echo esc_url($step['image']); ?>" alt="<?php echo esc_attr($step['title']); ?>" loading="lazy" width="328" height="180" style="<?php echo !empty($step['img_style']) ? esc_attr($step['img_style']) : ''; ?>">
                                <p class="ays-quiz-checklist-step-paragraph">
                                    <?php echo esc_html($step['description']); ?>
                                    <?php if(!empty($step['button_link'])): ?>
                                        <a href="<?php echo esc_url($step['learn_more_link']); ?>" target="_blank"><?php echo esc_html__('Learn more', 'quiz-maker'); ?></a>
                                    <?php endif; ?>
                                </p>
                                <div class="ays-quiz-checklist-actions">
                                    <?php if($is_checked): ?>
                                        <button class="ays-quiz-checklist-mark-done"><?php echo esc_html__('Unmark as done', 'quiz-maker'); ?></button>
                                    <?php else: ?>
                                        <button class="ays-quiz-checklist-mark-done"><?php echo esc_html__('Mark as done', 'quiz-maker'); ?></button>
                                    <?php endif; ?>
                                    <?php if(!empty($step['button_text'])): ?>
                                        <a href="<?php echo esc_url($step['button_link']); ?>" target="_blank" class="ays-quiz-checklist-primary-action"><?php echo esc_html($step['button_text']); ?></a>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </li>
                    <?php endforeach; ?>
                    </ul>
                    <style>
                       #ays-quiz-checklist-container.ays-quiz-checklist-panel{max-width:100%;background:#fff;font-family:Arial,sans-serif;overflow:hidden;margin:0 auto;color:#0c0d0e;box-shadow:rgba(0,0,0,.2) 0 3px 5px -1px,rgba(0,0,0,.14) 0 5px 8px 0,rgba(0,0,0,.12) 0 1px 14px 0;position:fixed;width:360px;bottom:40px;inset-inline-end:40px;z-index:99999;max-height:730px;overflow-y:auto;transition:box-shadow .3s cubic-bezier(.4, 0, .2, 1);border-radius:4px}#ays-quiz-checklist-container .ays-quiz-checklist-header{box-shadow:none;display:flex;flex-direction:row;align-items:center;width:100%;box-sizing:border-box;flex-shrink:0;position:sticky;z-index:1100;top:0;left:auto;right:0;color:rgba(0,0,0,.87);background-color:#fff;transition:box-shadow .3s cubic-bezier(.4, 0, .2, 1);padding:16px}#ays-quiz-checklist-container .ays-quiz-checklist-header-row{-webkit-tap-highlight-color:transparent;background-color:transparent;user-select:none;vertical-align:middle;appearance:none;display:flex;-webkit-box-flex:1;flex-grow:1;-webkit-box-pack:start;justify-content:space-between;-webkit-box-align:center;align-items:center;position:relative;min-width:0;box-sizing:border-box;text-align:left;padding:0;color:#0c0d0e;outline:0;border:0;margin:0;border-radius:0;text-decoration:none;transition:background-color 150ms cubic-bezier(.4, 0, .2, 1)}#ays-quiz-checklist-container .ays-quiz-checklist-header h3{box-sizing:content-box;display:inline;height:auto;padding:0;width:auto;text-transform:none;margin:0;font-weight:600;line-height:1.3;font-family:Arial,sans-serif;font-size:1rem;letter-spacing:.00938em;-webkit-box-flex:1;flex-grow:1}#ays-quiz-checklist-container .ays-quiz-checklist-close-btn,#ays-quiz-checklist-container .ays-quiz-checklist-minimize-btn{border:none;background:0 0;font-size:20px;cursor:pointer;outline:unset;transition:background-color .3s ease-in-out;border-radius:4px}#ays-quiz-checklist-container .ays-quiz-checklist-close-btn:hover,#ays-quiz-checklist-container .ays-quiz-checklist-minimize-btn:hover{background-color:rgba(0,0,0,.04)}#ays-quiz-checklist-container .ays-quiz-checklist-progress{display:flex;align-items:center;padding:12px 16px;border-bottom:1px solid #eee}#ays-quiz-checklist-container .ays-quiz-checklist-progress-bar{flex:1;background:#eee;height:6px;border-radius:4px;margin-right:10px;overflow:hidden}#ays-quiz-checklist-container .ays-quiz-checklist-progress-bar span{display:block;height:100%;background:#00a0d2;transition:width .3s ease-in-out}#ays-quiz-checklist-container .ays-quiz-checklist-progress-text{font-size:13px;min-width:30px}#ays-quiz-checklist-container .ays-quiz-checklist-items{list-style:none;padding:0;margin:0}#ays-quiz-checklist-container .ays-quiz-checklist-step{padding:12px 16px;border-bottom:1px solid #f0f0f0;transition:background .2s}#ays-quiz-checklist-container .ays-quiz-checklist-step:hover{background-color:#f9f9f9}#ays-quiz-checklist-container .ays-quiz-checklist-step label{display:flex;align-items:center;cursor:pointer}#ays-quiz-checklist-container .ays-quiz-checklist-step input[type=checkbox]{margin-right:10px}#ays-quiz-checklist-container .ays-quiz-checklist-step.completed label span{text-decoration:line-through;opacity:.6}#ays-quiz-checklist-container .ays-quiz-checklist-step-title{margin:0;font-family:Arial,sans-serif;font-weight:400;font-size:.875rem;letter-spacing:.01071em;display:block}#ays-quiz-checklist-container .ays-quiz-checklist-step-content{display:none;margin-top:12px}#ays-quiz-checklist-container .ays-quiz-checklist-step-content img{width:100%;max-width:100%;border-radius:4px;margin-bottom:0;height:180px;object-fit:contain}#wpwrap #wpfooter #ays-quiz-checklist-container .ays-quiz-checklist-step-content p{margin:0;font-size:13px;padding:16px 0}#ays-quiz-checklist-container .ays-quiz-checklist-step-content a:not(.ays-quiz-checklist-primary-action){color:#0073aa;text-decoration:underline}#ays-quiz-checklist-container .ays-quiz-checklist-actions{display:flex;-webkit-box-align:center;align-items:center;-webkit-box-pack:end;justify-content:flex-end;padding-bottom:12px}#ays-quiz-checklist-container .ays-quiz-checklist-mark-done,#ays-quiz-checklist-container .ays-quiz-checklist-primary-action{display:inline-flex;position:relative;box-sizing:border-box;-webkit-tap-highlight-color:transparent;user-select:none;vertical-align:middle;appearance:none;text-transform:none;font-family:Arial,sans-serif;font-weight:500;font-size:.8125rem;line-height:1.75;letter-spacing:.02857em;min-width:64px;box-shadow:none;outline:0;text-decoration:none;padding:4px 10px;transition:background-color 250ms cubic-bezier(.4, 0, .2, 1),box-shadow 250ms cubic-bezier(.4, 0, .2, 1),border-color 250ms cubic-bezier(.4, 0, .2, 1),color 250ms cubic-bezier(.4, 0, .2, 1);white-space:nowrap;cursor:pointer}#ays-quiz-checklist-container .ays-quiz-checklist-mark-done{-webkit-box-align:center;align-items:center;-webkit-box-pack:center;justify-content:center;background-color:transparent;color:#515962;border:0;margin:0;border-radius:4px}#ays-quiz-checklist-container .ays-quiz-checklist-mark-done:hover{color:#515962;text-decoration:none;background-color:rgba(81,89,98,.04)}#ays-quiz-checklist-container .ays-quiz-checklist-primary-action{-webkit-box-align:center;align-items:center;-webkit-box-pack:center;justify-content:center;color:#fff;background-color:#00a0d2;border:0 initial initial;border-image:initial;margin:0 0 0 8px;border-radius:4px}#ays-quiz-checklist-container .ays-quiz-checklist-toggle-step{background:0 0;border:none;font-size:16px;cursor:pointer;transform:rotate(0);transition:transform .2s}#ays-quiz-checklist-container .ays-quiz-checklist-toggle-step.open{transform:rotate(180deg)}#ays-quiz-checklist-container.ays-quiz-checklist-panel.ays-quiz-checklist-minimized .ays-quiz-checklist-items{display:none}.ays-quiz-launch-icon::after{content:"";width:8px;height:8px;background-color:#d179f2;border-radius:50%;position:absolute;top:0;right:0}.ays-quiz-checklist-open-icon{position:absolute;bottom:36px;right:20px;cursor:pointer}@media (max-width:1280px){div#ays-quiz-checklist-container.ays-quiz-checklist-panel{max-height:580px;width:300px;bottom:20px;inset-inline-end:20px}div#ays-quiz-checklist-container .ays-quiz-checklist-step-content img{height:150px}}@media (max-width:782px){#ays-quiz-checklist-container.ays-quiz-checklist-panel,.ays-quiz-checklist-open-icon{display:none}}@media (max-width:480px){#ays-quiz-checklist-container.ays-quiz-checklist-panel{border-radius:0;max-width:100%}#ays-quiz-checklist-container .ays-quiz-checklist-header h3{font-size:16px}#ays-quiz-checklist-container .ays-quiz-checklist-step-content p{font-size:13px}#ays-quiz-checklist-container .ays-quiz-checklist-actions{flex-direction:column}}
                    </style>
                </div>
                <?php
            }
        }

    }

    public function ays_quiz_save_checklist_progress() {
        check_ajax_referer('ays_quiz_nonce', 'nonce');

        $user_can_capability = self::quiz_maker_capabilities();

        // Check for permissions.
        if ( ! current_user_can( $user_can_capability ) ) {
            wp_send_json_error( esc_html__( 'Something went wrong', 'quiz-maker' ) );
        }

        if( !is_user_logged_in() ) {
            wp_send_json_error( esc_html__( 'Something went wrong', 'quiz-maker' ) );
        }

        $user_id = get_current_user_id();
        $steps = isset($_POST['steps']) ? array_map('sanitize_text_field', $_POST['steps']) : array();

        update_user_meta($user_id, 'ays_quiz_checklist_completed_steps', $steps);

        wp_send_json_success(array('saved' => true));
    }

    public function ays_quiz_checklist_close_popup() {
        check_ajax_referer('ays_quiz_nonce', 'nonce');

        $user_can_capability = self::quiz_maker_capabilities();

        // Check for permissions.
        if ( ! current_user_can( $user_can_capability ) ) {
            wp_send_json_error( esc_html__( 'Something went wrong', 'quiz-maker' ) );
        }

        if (is_user_logged_in()) {
            $user_id = get_current_user_id();
            update_user_meta($user_id, 'ays_quiz_checklist_popup_closed', 1);
            wp_send_json_success( esc_html__( 'Popup marked as closed.', 'quiz-maker' ) );
        }

        wp_send_json_error( esc_html__( 'User not logged in.', 'quiz-maker' ) );
    }

    public function ays_quiz_checklist_reopen_callback() {
        check_ajax_referer('ays_quiz_nonce', 'nonce');

        $user_can_capability = self::quiz_maker_capabilities();

        // Check for permissions.
        if ( ! current_user_can( $user_can_capability ) ) {
            wp_send_json_error( esc_html__( 'Something went wrong', 'quiz-maker' ) );
        }

        if (is_user_logged_in()) {
            $user_id = get_current_user_id();
            delete_user_meta($user_id, 'ays_quiz_checklist_popup_closed');
            wp_send_json_success('Popup reopened.');
        }

        wp_send_json_error( esc_html__( 'User not logged in.', 'quiz-maker' ) );
    }

    public static function ays_quiz_get_settings_order_defaults(){
        $buttons_ordering = array(
            'clear' =>  __('Clear','quiz-maker'),
            'prev' =>  __('Prev','quiz-maker'),
            'finish' =>  __('Finish','quiz-maker'),
            'next' =>  __('Next','quiz-maker'),
            'save' =>  __('Save','quiz-maker'),
        );

        $result_page_ordering = array(
            'pass_score' => array(
                                'name' => __('Pass Score','quiz-maker'),
                                'message' => __('If you want to display the pass score on the Quiz Result page, then go to the Quizzes > given quiz > Results Settings Tab and set your desired value for the Pass Score option.','quiz-maker'),
                            ),
            'conditions_message' => array(
                                'name' => __('Conditions Message','quiz-maker'),
                                'message' => __('If you want to display the conditional message on the Quiz Result page, then install the Conditional Results addon first․ Then, head to the Conditional Results addon and specify the conditions.','quiz-maker'),
                            ),
            'interval_message' => array(
                                'name' => __('Interval Message','quiz-maker'),
                                'message' => __('If you want to display the interval message (the most relevant text based on the user’s answers) on the Quiz Result page, then go to the Quizzes > given quiz > Results Settings Tab, tick the Show interval message option and specify your desired texts for each Interval.','quiz-maker'),
                            ),
            'ays_message' => array(
                                'name' => __('Result Message','quiz-maker'),
                                'message' => __('If you want to display the content you write for the Result Message option on the Quiz Result page, then go to the Quizzes > given quiz > Result Message option and specify the content there.','quiz-maker'),
                            ),
            'woo_block' => array(
                                'name' => __('WooCommerce Products','quiz-maker'),
                                'message' => __('If you want to display the WooCommerce products on the Quiz Result page, first, install the WooCommerce plugin. Then, go to the Quizzes > given quiz > Results Settings Tab > Intervals option, and specify the WooCommerce products for intervals.','quiz-maker'),
                            ),
            'score_html' => array(
                                'name' => __('Your Score','quiz-maker'),
                                'message' => __('If you want to display the score on the Quiz Result page, then head to the Quizzes > given quiz > Results Settings Tab and disable the Hide Score option.','quiz-maker'),
                            ),
            'average_score' => array(
                                'name' => __('Average Score','quiz-maker'),
                                'message' => __('If you want to display the Average Score on the Quiz Result page, then go to the Quizzes > given quiz > Results Settings Tab and enable the Show the statistical average option.','quiz-maker'),
                            ),
            'social_buttons' => array(
                                'name' => __('Social Buttons','quiz-maker'),
                                'message' => __('If you want to display the social buttons on the Quiz Result page, then go to the Quizzes > given quiz > Results Settings Tab and enable the Show the Social buttons option.','quiz-maker'),
                            ),
            'social_links' => array(
                                'name' => __('Social Links','quiz-maker'),
                                'message' => __('If you want to display the social links on the Quiz Result page, then go to the Quizzes > given quiz > Results Settings Tab and tick the Enable Social Media links option.','quiz-maker'),
                            ),
            'progress_bar' => array(
                                'name' => __('Progress Bar','quiz-maker'),
                                'message' => __('If you want to display the progress bar on the Quiz Result page, then go to the Quizzes > given quiz > Results Settings Tab and tick the  Enable progress bar option.','quiz-maker'),
                            ),
            'buttons' => array(
                                'name' => __('Buttons','quiz-maker'),
                                'message' => __('Please note, that this refers to the Restart Quiz and Exit buttons. When the Chained Quiz addon is installed and active, the Next Quiz button will appear during the quiz, and the See Result button will be displayed at the end.','quiz-maker'),
                            ),
            'rate' => array(
                                'name' => __('Quiz Rate','quiz-maker'),
                                'message' => __('If you want to display the quiz rate with stars on the Quiz Result page, then go to the Quizzes > given quiz > Results Settings Tab and tick the Enable quiz assessment option.','quiz-maker'),
                            ),
            'download_pdf' => array(
                                'name' => __('Download Quiz Result in PDF','quiz-maker'),
                                'message' => __(' If you want the users to download the results from the Quiz Result page in PDF after completing the quiz, then go to the Quizzes > given quiz > Results Settings Tab > Show question results on the results page option and tick the Download Result Page in PDF suboption','quiz-maker'),
                            ),
        );

        return array('result_page' => $result_page_ordering,'buttons' => $buttons_ordering);
    }

    public static function get_quiz_question_by_id($id){

        global $wpdb;

        $sql = "SELECT * FROM {$wpdb->prefix}aysquiz_questions WHERE id = " . intval($id);

        $results = $wpdb->get_row($sql, "ARRAY_A");

        return $results;

    }

    public static function get_actual_reports_count(){
        global $wpdb;

        $sql = "SELECT COUNT(*) FROM {$wpdb->prefix}aysquiz_question_reports WHERE resolved = 0";
        $result = $wpdb->get_var($sql);

        return $result;
    }

    public function ays_quiz_black_friady_popup_box(){
        if(!empty($_REQUEST['page']) && sanitize_text_field( $_REQUEST['page'] ) != $this->plugin_name . "-admin-dashboard"){
            if(false !== strpos( sanitize_text_field( $_REQUEST['page'] ), $this->plugin_name)){

                $flag = true;

                if( isset($_COOKIE['aysQuizBlackFridayPopupCount']) && intval($_COOKIE['aysQuizBlackFridayPopupCount']) >= 2 ){
                    $flag = false;
                }

                $ays_quiz_cta_button_link = esc_url('https://ays-pro.com/mega-bundle?utm_source=dashboard&utm_medium=quiz-free&utm_campaign=mega-bundle-popup-black-friday-sale-' . AYS_QUIZ_UTM_VERSION);

                if( $flag ){
                ?>
                <div class="ays-quiz-black-friday-popup-overlay" style="opacity: 0; visibility: hidden; display: none;">
                  <div class="ays-quiz-black-friday-popup-dialog">
                    <div class="ays-quiz-black-friday-popup-content">
                      <div class="ays-quiz-black-friday-popup-background-pattern">
                        <div class="ays-quiz-black-friday-popup-pattern-row">
                          <div class="ays-quiz-black-friday-popup-pattern-text">SALE SALE SALE</div>
                          <div class="ays-quiz-black-friday-popup-pattern-text">SALE SALE SALE</div>
                        </div>
                        <div class="ays-quiz-black-friday-popup-pattern-row">
                          <div class="ays-quiz-black-friday-popup-pattern-text">SALE SALE SALE</div>
                          <div class="ays-quiz-black-friday-popup-pattern-text">SALE SALE SALE</div>
                        </div>
                        <div class="ays-quiz-black-friday-popup-pattern-row">
                          <div class="ays-quiz-black-friday-popup-pattern-text">SALE SALE SALE</div>
                          <div class="ays-quiz-black-friday-popup-pattern-text">SALE SALE SALE</div>
                        </div>
                        <div class="ays-quiz-black-friday-popup-pattern-row">
                          <div class="ays-quiz-black-friday-popup-pattern-text">SALE SALE SALE</div>
                          <div class="ays-quiz-black-friday-popup-pattern-text">SALE SALE SALE</div>
                        </div>
                      </div>
                      
                      <button class="ays-quiz-black-friday-popup-close" aria-label="Close">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                          <path d="M18 6 6 18"></path>
                          <path d="m6 6 12 12"></path>
                        </svg>
                      </button>
                      
                      <div class="ays-quiz-black-friday-popup-badge">
                        <div class="ays-quiz-black-friday-popup-badge-content">
                          <div class="ays-quiz-black-friday-popup-badge-text-sm"><?php echo esc_html__( 'Up to', 'quiz-maker' ); ?></div>
                          <div class="ays-quiz-black-friday-popup-badge-text-lg">50%</div>
                          <div class="ays-quiz-black-friday-popup-badge-text-md"><?php echo esc_html__( 'OFF', 'quiz-maker' ); ?></div>
                        </div>
                      </div>
                      
                      <div class="ays-quiz-black-friday-popup-main-content">
                        <div class="ays-quiz-black-friday-popup-hashtag"><?php echo esc_html__( '#BLACKFRIDAY', 'quiz-maker' ); ?></div>
                        <h1 class="ays-quiz-black-friday-popup-title-mega"><?php echo esc_html__( 'MEGA', 'quiz-maker' ); ?></h1>
                        <h1 class="ays-quiz-black-friday-popup-title-bundle"><?php echo esc_html__( 'BUNDLE', 'quiz-maker' ); ?></h1>
                        <div class="ays-quiz-black-friday-popup-offer-label">
                          <h2 class="ays-quiz-black-friday-popup-offer-text"><?php echo esc_html__( 'BLACK FRIDAY OFFER', 'quiz-maker' ); ?></h2>
                        </div>
                        <p class="ays-quiz-black-friday-popup-description"><?php echo esc_html__( 'Get our exclusive plugins in one bundle', 'quiz-maker' ); ?></p>
                        <a href="<?php echo esc_url($ays_quiz_cta_button_link); ?>" target="_blank" class="ays-quiz-black-friday-popup-cta-btn"><?php echo esc_html__( 'Get Mega Bundle', 'quiz-maker' ); ?></a>
                      </div>
                    </div>
                  </div>
                </div>
                <script type="text/javascript">
                    (function() {
                      var overlay = document.querySelector('.ays-quiz-black-friday-popup-overlay');
                      var closeBtn = document.querySelector('.ays-quiz-black-friday-popup-close');
                      var learnMoreBtn = document.querySelector('.ays-quiz-black-friday-popup-learn-more');
                      var ctaBtn = document.querySelector('.ays-quiz-black-friday-popup-cta-btn');

                      // Cookie helper functions
                      function setCookie(name, value, days) {
                        var expires = "";
                        if (days) {
                          var date = new Date();
                          date.setTime(date.getTime() + (days * 24 * 60 * 60 * 1000));
                          expires = "; expires=" + date.toUTCString();
                        }
                        document.cookie = name + "=" + (value || "") + expires + "; path=/";
                      }

                      function getCookie(name) {
                        var nameEQ = name + "=";
                        var ca = document.cookie.split(';');
                        for (var i = 0; i < ca.length; i++) {
                          var c = ca[i];
                          while (c.charAt(0) == ' ') c = c.substring(1, c.length);
                          if (c.indexOf(nameEQ) == 0) return c.substring(nameEQ.length, c.length);
                        }
                        return null;
                      }

                      // Get current show count from cookie
                      var showCount = parseInt(getCookie('aysQuizBlackFridayPopupCount') || '0', 10);
                      var maxShows = 2;

                      // Show popup function
                      function showPopup() {
                        if (overlay && showCount < maxShows) {
                          overlay.classList.add('ays-quiz-black-friday-popup-active');
                          showCount++;
                          // Update cookie with new count (expires in 30 days)
                          setCookie('aysQuizBlackFridayPopupCount', showCount.toString(), 30);
                        }
                      }

                      // Close popup function
                      function closePopup(e) {
                        if (e) {
                          e.preventDefault();
                          e.stopPropagation();
                        }
                        if (overlay) {
                          overlay.classList.remove('ays-quiz-black-friday-popup-active');
                        }
                      }

                      // Determine timing based on show count
                      if (showCount === 0) {
                        // First time - show after 30 seconds
                        setTimeout(function() {
                          showPopup();
                        }, 30000);
                      } else if (showCount === 1) {
                        // Second time - show after 200 seconds
                        setTimeout(function() {
                          showPopup();
                        }, 200000);
                      }
                      // If showCount >= 2, don't show popup at all

                      // Close button
                      if (closeBtn) {
                        closeBtn.addEventListener('click', function(e) {
                          closePopup(e);
                        });
                      }

                      // Learn more button
                      if (learnMoreBtn) {
                        learnMoreBtn.addEventListener('click', function(e) {
                          closePopup(e);
                        });
                      }

                      // CTA button (optional - if you want it to close popup too)
                      if (ctaBtn) {
                        ctaBtn.addEventListener('click', function(e) {
                          // You can add redirect logic here if needed
                          // window.location.href = 'your-url';
                        });
                      }

                      // Close on overlay click
                      if (overlay) {
                        overlay.addEventListener('click', function(e) {
                          if (e.target === overlay) {
                            closePopup(e);
                          }
                        });
                      }

                      // Close on Escape key
                      document.addEventListener('keydown', function(e) {
                        if (e.key === 'Escape' && overlay && overlay.classList.contains('ays-quiz-black-friday-popup-active')) {
                          closePopup();
                        }
                      });
                    })();
                </script>
                <style>
                    .ays-quiz-black-friday-popup-overlay{position:fixed;top:0;left:0;right:0;bottom:0;z-index:9999;background-color:rgba(0,0,0,.8);display:flex;align-items:center;justify-content:center;opacity:0;visibility:hidden;transition:opacity .2s,visibility .2s}.ays-quiz-black-friday-popup-overlay.ays-quiz-black-friday-popup-active{display:flex!important;opacity:1!important;visibility:visible!important}.ays-quiz-black-friday-popup-overlay .ays-quiz-black-friday-popup-dialog{position:relative;max-width:470px;width:100%;border-radius:8px;overflow:hidden;background:0 0;box-shadow:0 25px 50px -12px rgba(0,0,0,.25);transform:scale(.95);transition:transform .2s}.ays-quiz-black-friday-popup-overlay.ays-quiz-black-friday-popup-active .ays-quiz-black-friday-popup-dialog{transform:scale(1)}.ays-quiz-black-friday-popup-overlay .ays-quiz-black-friday-popup-content{position:relative;width:470px;height:410px;background:linear-gradient(to right bottom,#c056f5,#f042f0,#7d7de8);overflow:hidden}.ays-quiz-black-friday-popup-overlay .ays-quiz-black-friday-popup-background-pattern{position:absolute;top:0;left:0;right:0;bottom:0;opacity:.07;pointer-events:none;transform:rotate(-12deg) translateY(32px);overflow:hidden}.ays-quiz-black-friday-popup-overlay .ays-quiz-black-friday-popup-pattern-row{display:flex;gap:16px;margin-bottom:16px}.ays-quiz-black-friday-popup-overlay .ays-quiz-black-friday-popup-pattern-text{color:#fff;font-weight:900;font-size:96px;white-space:nowrap;line-height:1}.ays-quiz-black-friday-popup-overlay .ays-quiz-black-friday-popup-close{position:absolute;top:16px;right:16px;z-index:9999;background:0 0;border:none;color:rgba(255,255,255,.8);cursor:pointer;padding:4px;transition:color .2s;line-height:0}.ays-quiz-black-friday-popup-overlay .ays-quiz-black-friday-popup-close:hover,.ays-quiz-black-friday-popup-overlay .ays-quiz-black-friday-popup-learn-more:hover{color:#fff}.ays-quiz-black-friday-popup-overlay .ays-quiz-black-friday-popup-badge{position:absolute;top:32px;right:32px;width:96px;height:96px;background-color:#d4fc79;border-radius:50%;display:flex;align-items:center;justify-content:center;box-shadow:0 25px 50px -12px rgba(0,0,0,.25);animation:3s ease-in-out infinite ays-quiz-black-friday-popup-float}@keyframes ays-quiz-black-friday-popup-float{0%,100%{transform:translateY(0)}50%{transform:translateY(-10px)}}.ays-quiz-black-friday-popup-overlay .ays-quiz-black-friday-popup-badge-content{text-align:center}.ays-quiz-black-friday-popup-overlay .ays-quiz-black-friday-popup-badge-text-sm{color:#1a1a1a;font-weight:900;font-size:24px;line-height:1}.ays-quiz-black-friday-popup-overlay .ays-quiz-black-friday-popup-badge-text-lg{color:#1a1a1a;font-weight:900;font-size:30px;line-height:1;margin-top:4px}.ays-quiz-black-friday-popup-overlay .ays-quiz-black-friday-popup-badge-text-md{color:#1a1a1a;font-weight:900;font-size:20px;line-height:1}.ays-quiz-black-friday-popup-overlay .ays-quiz-black-friday-popup-main-content{position:relative;z-index:10;height:100%;display:flex;flex-direction:column;align-items:center;justify-content:center;padding:0 48px;text-align:center}.ays-quiz-black-friday-popup-overlay .ays-quiz-black-friday-popup-hashtag{color:rgba(255,255,255,.9);font-weight:700;font-size:14px;margin-bottom:16px;letter-spacing:.1em}.ays-quiz-black-friday-popup-overlay .ays-quiz-black-friday-popup-title-mega{color:#fff;font-weight:900;font-size:60px;line-height:1;margin:0 0 12px;text-shadow:0 4px 6px rgba(0,0,0,.1)}.ays-quiz-black-friday-popup-overlay .ays-quiz-black-friday-popup-title-bundle{color:#fff;font-weight:900;font-size:60px;line-height:1;margin:0 0 24px;text-shadow:0 4px 6px rgba(0,0,0,.1)}.ays-quiz-black-friday-popup-overlay .ays-quiz-black-friday-popup-offer-label{background-color:#000;padding:12px 32px;margin-bottom:24px;display:inline-block}.ays-quiz-black-friday-popup-overlay .ays-quiz-black-friday-popup-offer-text{color:#fff;font-weight:700;font-size:20px;letter-spacing:.05em;margin:0}.ays-quiz-black-friday-popup-overlay .ays-quiz-black-friday-popup-description{color:rgba(255,255,255,.95);font-size:18px;font-weight:500;margin:0 0 32px!important}.ays-quiz-black-friday-popup-overlay .ays-quiz-black-friday-popup-cta-btn{display:inline-flex;align-items:center;justify-content:center;height:48px;background-color:#fff;color:#a855f7;font-size:18px;font-weight:700;border:none;border-radius:24px;padding:0 40px;cursor:pointer;box-shadow:0 20px 25px -5px rgba(0,0,0,.1);transition:.2s;text-decoration:none}.ays-quiz-black-friday-popup-overlay .ays-quiz-black-friday-popup-cta-btn:hover{background-color:rgba(255,255,255,.9);box-shadow:0 25px 50px -12px rgba(0,0,0,.25);transform:scale(1.05)}.ays-quiz-black-friday-popup-overlay .ays-quiz-black-friday-popup-learn-more{background:0 0;border:none;color:rgba(255,255,255,.9);font-size:14px;text-decoration:underline;text-underline-offset:4px;cursor:pointer;padding:8px;margin-top:16px;transition:color .2s}@media (max-width:768px){.ays-quiz-black-friday-popup-overlay{display:none!important}.ays-quiz-black-friday-popup-overlay .ays-quiz-black-friday-popup-content{width:90vw;max-width:400px;height:380px}.ays-quiz-black-friday-popup-overlay .ays-quiz-black-friday-popup-main-content{padding:0 32px}.ays-quiz-black-friday-popup-overlay .ays-quiz-black-friday-popup-badge{width:80px;height:80px;top:24px;right:24px}.ays-quiz-black-friday-popup-overlay .ays-quiz-black-friday-popup-badge-text-sm{font-size:20px}.ays-quiz-black-friday-popup-overlay .ays-quiz-black-friday-popup-badge-text-lg{font-size:26px}.ays-quiz-black-friday-popup-overlay .ays-quiz-black-friday-popup-badge-text-md,.ays-quiz-black-friday-popup-overlay .ays-quiz-black-friday-popup-offer-text{font-size:18px}.ays-quiz-black-friday-popup-overlay .ays-quiz-black-friday-popup-title-bundle,.ays-quiz-black-friday-popup-overlay .ays-quiz-black-friday-popup-title-mega{font-size:48px}.ays-quiz-black-friday-popup-overlay .ays-quiz-black-friday-popup-description{font-size:16px}.ays-quiz-black-friday-popup-overlay .ays-quiz-black-friday-popup-pattern-text{font-size:72px}}@media (max-width:480px){.ays-quiz-black-friday-popup-overlay .ays-quiz-black-friday-popup-content{width:95vw;max-width:340px;height:360px}.ays-quiz-black-friday-popup-overlay .ays-quiz-black-friday-popup-main-content{padding:0 24px}.ays-quiz-black-friday-popup-overlay .ays-quiz-black-friday-popup-badge{width:70px;height:70px;top:20px;right:20px}.ays-quiz-black-friday-popup-overlay .ays-quiz-black-friday-popup-badge-text-sm,.ays-quiz-black-friday-popup-overlay .ays-quiz-black-friday-popup-offer-text{font-size:16px}.ays-quiz-black-friday-popup-overlay .ays-quiz-black-friday-popup-badge-text-lg{font-size:22px}.ays-quiz-black-friday-popup-overlay .ays-quiz-black-friday-popup-badge-text-md{font-size:14px}.ays-quiz-black-friday-popup-overlay .ays-quiz-black-friday-popup-hashtag{font-size:12px;margin-bottom:12px}.ays-quiz-black-friday-popup-overlay .ays-quiz-black-friday-popup-title-bundle,.ays-quiz-black-friday-popup-overlay .ays-quiz-black-friday-popup-title-mega{font-size:40px;margin-bottom:8px}.ays-quiz-black-friday-popup-overlay .ays-quiz-black-friday-popup-offer-label{padding:10px 24px;margin-bottom:20px}.ays-quiz-black-friday-popup-overlay .ays-quiz-black-friday-popup-description{font-size:15px;margin-bottom:24px}.ays-quiz-black-friday-popup-overlay .ays-quiz-black-friday-popup-cta-btn{font-size:16px;height:44px;padding:0 32px}.ays-quiz-black-friday-popup-overlay .ays-quiz-black-friday-popup-pattern-text{font-size:60px}}
                </style>
                <?php
                }
            }
        }

    }
    
}
