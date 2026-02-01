<?php
    $actions = $this->settings_obj;
    $questions_obj = new Questions_List_Table($this->plugin_name);
    $loader_iamge = "<span class='display_none ays_quiz_loader_box'><img src='". AYS_QUIZ_ADMIN_URL ."/images/loaders/loading.gif'></span>";

    if( isset( $_REQUEST['ays_submit'] ) ){
        $actions->store_data();
    }
    if(isset($_GET['ays_quiz_tab'])){
        $ays_quiz_tab = sanitize_key( $_GET['ays_quiz_tab'] );
    }else{
        $ays_quiz_tab = 'tab1';
    }
    $data = $actions->get_data();
    global $wp_roles;
    $ays_users_roles = $wp_roles->role_names;

    $question_types = array(
        "radio"             => __("Radio", 'quiz-maker'),
        "checkbox"          => __("Checkbox( Multiple )", 'quiz-maker'),
        "select"            => __("Dropdown", 'quiz-maker'),
        "text"              => __("Text", 'quiz-maker'),
        "short_text"        => __("Short Text", 'quiz-maker'),
        "number"            => __("Number", 'quiz-maker'),
        "date"              => __("Date", 'quiz-maker'),
        "true_or_false"     => __("True/False", 'quiz-maker'),
        "custom"            => __("Info Banner (PRO)", 'quiz-maker'),
        "fill_in_blank"     => __("Fill in the blanks (PRO)", 'quiz-maker'),
        "matching"          => __("Matching (PRO)", 'quiz-maker'),
        "upload_file"       => __("Upload File (PRO)", 'quiz-maker'),
    );

    $question_types_icon_url = array(
        "radio"             => AYS_QUIZ_ADMIN_URL ."/images/QuestionTypes/quiz-maker-radio-type.svg",
        "checkbox"          => AYS_QUIZ_ADMIN_URL ."/images/QuestionTypes/quiz-maker-checkbox-type.svg",
        "select"            => AYS_QUIZ_ADMIN_URL ."/images/QuestionTypes/quiz-maker-dropdown-type.svg",
        "text"              => AYS_QUIZ_ADMIN_URL ."/images/QuestionTypes/quiz-maker-text-type.svg",
        "short_text"        => AYS_QUIZ_ADMIN_URL ."/images/QuestionTypes/quiz-maker-short-text-type.svg",
        "number"            => AYS_QUIZ_ADMIN_URL ."/images/QuestionTypes/quiz-maker-number-type.svg",
        "date"              => AYS_QUIZ_ADMIN_URL ."/images/QuestionTypes/quiz-maker-date-type.svg",
        "true_or_false"     => AYS_QUIZ_ADMIN_URL ."/images/QuestionTypes/quiz-maker-true-or-false-type.svg",
        "custom"            => AYS_QUIZ_ADMIN_URL ."/images/QuestionTypes/quiz-maker-custom-type.svg",
        "fill_in_blank"     => AYS_QUIZ_ADMIN_URL ."/images/QuestionTypes/quiz-maker-fill-in-blank-type.svg",
        "matching"          => AYS_QUIZ_ADMIN_URL ."/images/QuestionTypes/quiz-maker-matching-type.svg",
        "upload_file"       => AYS_QUIZ_ADMIN_URL ."/images/QuestionTypes/quiz-maker-upload-file-type.svg",
    );

    $options = ($actions->ays_get_setting('options') === false) ? array() : json_decode( stripcslashes( $actions->ays_get_setting('options') ), true);
    $options['question_default_type'] = !isset($options['question_default_type']) ? 'radio' : esc_attr( stripslashes($options['question_default_type']) );
    $question_default_type = isset($options['question_default_type']) ? esc_attr( stripslashes($options['question_default_type']) ) : '';
    $ays_answer_default_count = isset($options['ays_answer_default_count']) ? intval($options['ays_answer_default_count']) : 3;

    if ( $question_default_type == 'true_or_false' ) {
        $ays_answer_default_count = 2;
    }

    $right_answer_sound = isset($options['right_answer_sound']) ? esc_url($options['right_answer_sound']) : '';
    $wrong_answer_sound = isset($options['wrong_answer_sound']) ? esc_url($options['wrong_answer_sound']) : '';

    //Questions title length
    $question_title_length = (isset($options['question_title_length']) && intval($options['question_title_length']) != 0) ? absint(intval($options['question_title_length'])) : 5;

    //Quizzes title length
    $quizzes_title_length = (isset($options['quizzes_title_length']) && intval($options['quizzes_title_length']) != 0) ? absint(intval($options['quizzes_title_length'])) : 5;

    //Results title length
    $results_title_length = (isset($options['results_title_length']) && intval($options['results_title_length']) != 0) ? absint(intval($options['results_title_length'])) : 5;

    // Question title length
    $question_categories_title_length = (isset($options['question_categories_title_length']) && intval($options['question_categories_title_length']) != 0) ? absint(intval($options['question_categories_title_length'])) : 5;

    // Quiz title length
    $quiz_categories_title_length = (isset($options['quiz_categories_title_length']) && intval($options['quiz_categories_title_length']) != 0) ? absint(intval($options['quiz_categories_title_length'])) : 5;

    // Reviews title length
    $quiz_reviews_title_length = (isset($options['quiz_reviews_title_length']) && intval($options['quiz_reviews_title_length']) != 0) ? absint(intval($options['quiz_reviews_title_length'])) : 5;
            
    $default_leadboard_column_names = array(
        "pos"           => __( 'Pos.', 'quiz-maker' ),
        "name"          => __( 'Name', 'quiz-maker' ),
        "score"         => __( 'Score', 'quiz-maker' ),
        "duration"      => __( 'Duration', 'quiz-maker' ),
        "points"        => __( 'Points', 'quiz-maker' ),
    );

    $default_user_page_column_names = array(
        "quiz_name"             => __( 'Quiz name', 'quiz-maker' ),
        "start_date"            => __( 'Start date', 'quiz-maker' ),
        "end_date"              => __( 'End date', 'quiz-maker' ),
        "duration"              => __( 'Duration', 'quiz-maker' ),
        "score"                 => __( 'Score', 'quiz-maker' ),
        "details"               => __( 'Details', 'quiz-maker' ),
        "download_certificate"  => __( 'Certificate', 'quiz-maker' ),
        "points"                => __( 'Points', 'quiz-maker' ),
        "status"                => __( 'Status', 'quiz-maker' ),
    );

     // Aro Buttons Text

    $buttons_texts_res      = ($actions->ays_get_setting('buttons_texts') === false) ? json_encode(array()) : $actions->ays_get_setting('buttons_texts');
    $buttons_texts          = json_decode( stripcslashes( $buttons_texts_res ) , true);

    $start_button           = (isset($buttons_texts['start_button']) && $buttons_texts['start_button'] != '') ? stripslashes( esc_attr( $buttons_texts['start_button'] ) ) : 'Start' ;
    $next_button            = (isset($buttons_texts['next_button']) && $buttons_texts['next_button'] != '') ? stripslashes( esc_attr( $buttons_texts['next_button'] ) ) : 'Next' ;
    $previous_button        = (isset($buttons_texts['previous_button']) && $buttons_texts['previous_button'] != '') ? stripslashes( esc_attr( $buttons_texts['previous_button'] ) ) : 'Prev' ;
    $clear_button           = (isset($buttons_texts['clear_button']) && $buttons_texts['clear_button'] != '') ? stripslashes( esc_attr( $buttons_texts['clear_button'] ) ) : 'Clear' ;
    $finish_button          = (isset($buttons_texts['finish_button']) && $buttons_texts['finish_button'] != '') ? stripslashes( esc_attr( $buttons_texts['finish_button'] ) ) : 'Finish' ;
    $see_result_button      = (isset($buttons_texts['see_result_button']) && $buttons_texts['see_result_button'] != '') ? stripslashes( esc_attr( $buttons_texts['see_result_button'] ) ) : 'See Result' ;
    $restart_quiz_button    = (isset($buttons_texts['restart_quiz_button']) && $buttons_texts['restart_quiz_button'] != '') ? stripslashes( esc_attr( $buttons_texts['restart_quiz_button'] ) ) : 'Restart quiz' ;
    $send_feedback_button   = (isset($buttons_texts['send_feedback_button']) && $buttons_texts['send_feedback_button'] != '') ? stripslashes( esc_attr( $buttons_texts['send_feedback_button'] ) ) : 'Send feedback' ;
    $load_more_button       = (isset($buttons_texts['load_more_button']) && $buttons_texts['load_more_button'] != '') ? stripslashes( esc_attr( $buttons_texts['load_more_button'] ) ) : 'Load more' ;
    $exit_button            = (isset($buttons_texts['exit_button']) && $buttons_texts['exit_button'] != '') ? stripslashes( esc_attr( $buttons_texts['exit_button'] ) ) : 'Exit' ;
    $check_button           = (isset($buttons_texts['check_button']) && $buttons_texts['check_button'] != '') ? stripslashes( esc_attr( $buttons_texts['check_button'] ) ) : 'Check' ;
    $login_button           = (isset($buttons_texts['login_button']) && $buttons_texts['login_button'] != '') ? stripslashes( esc_attr( $buttons_texts['login_button'] ) ) : 'Log In' ;
    
    //Aro end

    // Default texts | Start
    $default_texts_res = ($actions->ays_get_setting('default_texts') === false) ? json_encode(array()) : $actions->ays_get_setting('default_texts');
    $default_texts = json_decode( stripcslashes($default_texts_res), true);

    $wrong_shortcode_text              = (isset($default_texts['wrong_shortcode_text']) && $default_texts['wrong_shortcode_text'] != '') ? stripslashes( esc_attr( $default_texts['wrong_shortcode_text'] ) ) : 'Wrong shortcode initialized';
    $enter_password_text               = (isset($default_texts['enter_password_text']) && $default_texts['enter_password_text'] != '') ? stripslashes( esc_attr( $default_texts['enter_password_text'] ) ) : 'Please enter password';
    $wrong_password_text               = (isset($default_texts['wrong_password_text']) && $default_texts['wrong_password_text'] != '') ? stripslashes( esc_attr( $default_texts['wrong_password_text'] ) ) : 'Password is wrong!';
    $empty_results_text                = (isset($default_texts['empty_results_text']) && $default_texts['empty_results_text'] != '') ? stripslashes( esc_attr( $default_texts['empty_results_text'] ) ) : 'There are no results yet.';
    $not_answered_question_text        = (isset($default_texts['not_answered_question_text']) && $default_texts['not_answered_question_text'] != '') ? stripslashes( esc_attr( $default_texts['not_answered_question_text'] ) ) : 'You have not answered this question';
    $finish_quiz_text                  = (isset($default_texts['finish_quiz_text']) && $default_texts['finish_quiz_text'] != '') ? stripslashes( esc_attr( $default_texts['finish_quiz_text'] ) ) : 'Do you want to finish the quiz? Are you sure?';
    $select_question_placeholder_text  = (isset($default_texts['select_question_placeholder_text']) && $default_texts['select_question_placeholder_text'] != '') ? stripslashes( esc_attr( $default_texts['select_question_placeholder_text'] ) ) : 'Select an answer';
    $no_more_reviews_text              = (isset($default_texts['no_more_reviews_text']) && $default_texts['no_more_reviews_text'] != '') ? stripslashes( esc_attr( $default_texts['no_more_reviews_text'] ) ) : 'No more reviews';
    // Default texts | End


    // Do not store IP addresses
    $options['disable_user_ip'] = isset($options['disable_user_ip']) ? $options['disable_user_ip'] : 'off';
    $disable_user_ip = (isset($options['disable_user_ip']) && $options['disable_user_ip'] == "on") ? true : false;

    //default all results column
    $default_all_results_columns = array(
        'user_name'     => 'user_name',
        'quiz_name'     => 'quiz_name',
        'start_date'    => 'start_date',
        'end_date'      => 'end_date',
        'duration'      => 'duration',
        'score'         => 'score',
        'status'        => '',
    );

    $default_all_results_column_names = array(
        "user_name"     => __( 'User name', 'quiz-maker'),
        "quiz_name"     => __( 'Quiz name', 'quiz-maker' ),
        "start_date"    => __( 'Start date','quiz-maker' ),
        "end_date"      => __( 'End date',  'quiz-maker' ),
        "duration"      => __( 'Duration',  'quiz-maker' ),
        "score"         => __( 'Score',     'quiz-maker' ),
        "status"        => __( 'Status',    'quiz-maker' ),
    );

    $options['all_results_columns'] = ! isset( $options['all_results_columns'] ) ? $default_all_results_columns : $options['all_results_columns'];
    $all_results_columns = (isset( $options['all_results_columns'] ) && !empty($options['all_results_columns']) ) ? $options['all_results_columns'] : array();
    $all_results_columns_order = (isset( $options['all_results_columns_order'] ) && !empty($options['all_results_columns_order']) ) ? $options['all_results_columns_order'] : $default_all_results_columns;

    $all_results_columns_order_arr = $all_results_columns_order;

    foreach( $default_all_results_columns as $key => $value ){
        if( !isset( $all_results_columns[$key] ) ){
            $all_results_columns[$key] = '';
        }

        if( !isset( $all_results_columns_order[$key] ) ){
            $all_results_columns_order[$key] = $key;
        }

        if ( ! in_array( $key , $all_results_columns_order_arr) ) {
            $all_results_columns_order_arr[] = $key;
        }
    }

    foreach( $all_results_columns_order as $key => $value ){
        if( !isset( $all_results_columns[$key] ) ){
            if( isset( $all_results_columns[$value] ) ){
                $all_results_columns_order[$value] = $value;
            }
            unset( $all_results_columns_order[$key] );
        }
    }

    foreach ($all_results_columns_order_arr  as $key => $value) {
        if( isset( $all_results_columns_order[$value] ) ){
            $all_results_columns_order_arr[$value] = $value;
        }
        
        if ( is_int( $key ) ) {
            unset( $all_results_columns_order_arr[$key] );
        }
    }

    $all_results_columns_order = $all_results_columns_order_arr;

    // Animation Top 
    $quiz_animation_top = (isset($options['quiz_animation_top']) && $options['quiz_animation_top'] != '') ? absint(intval($options['quiz_animation_top'])) : 100 ;
    $options['quiz_enable_animation_top'] = isset($options['quiz_enable_animation_top']) ? $options['quiz_enable_animation_top'] : 'on';
    $quiz_enable_animation_top = (isset($options['quiz_enable_animation_top']) && $options['quiz_enable_animation_top'] == "on") ? true : false;

    // Question Categories Array
    $question_categories = $questions_obj->get_question_categories();

    // Question Category
    $question_default_category = isset($options['question_default_category']) ? absint(intval($options['question_default_category'])) : 1; 

    // Show publicly ( All Results )
    $options['all_results_show_publicly'] = isset($options['all_results_show_publicly']) ? $options['all_results_show_publicly'] : 'off';
    $all_results_show_publicly = (isset($options['all_results_show_publicly']) && $options['all_results_show_publicly'] == "on") ? true : false;

    // Show publicly ( Single Quiz Results )
    $options['quiz_all_results_show_publicly'] = isset($options['quiz_all_results_show_publicly']) ? $options['quiz_all_results_show_publicly'] : 'off';
    $quiz_all_results_show_publicly = (isset($options['quiz_all_results_show_publicly']) && $options['quiz_all_results_show_publicly'] == "on") ? true : false;

    //default quiz all results column
    $default_quiz_all_results_columns = array(
        'user_name'     => 'user_name',
        'start_date'    => 'start_date',
        'end_date'      => 'end_date',
        'duration'      => 'duration',
        'score'         => 'score',
    );

    $default_quiz_all_results_column_names = array(
        "user_name"     => __( 'User name', 'quiz-maker' ),
        "start_date"    => __( 'Start date','quiz-maker' ),
        "end_date"      => __( 'End date',  'quiz-maker' ),
        "duration"      => __( 'Duration',  'quiz-maker' ),
        "score"         => __( 'Score',     'quiz-maker' ),
    );

    $options['quiz_all_results_columns'] = ! isset( $options['quiz_all_results_columns'] ) ? $default_quiz_all_results_columns : $options['quiz_all_results_columns'];
    $quiz_all_results_columns = (isset( $options['quiz_all_results_columns'] ) && !empty($options['quiz_all_results_columns']) ) ? $options['quiz_all_results_columns'] : array();
    $quiz_all_results_columns_order = (isset( $options['quiz_all_results_columns_order'] ) && !empty($options['quiz_all_results_columns_order']) ) ? $options['quiz_all_results_columns_order'] : $default_quiz_all_results_columns;

    // Enable question allow HTML
    $options['quiz_enable_question_allow_html'] = isset($options['quiz_enable_question_allow_html']) ? sanitize_text_field( $options['quiz_enable_question_allow_html'] ) : 'off';
    $quiz_enable_question_allow_html = (isset($options['quiz_enable_question_allow_html']) && sanitize_text_field( $options['quiz_enable_question_allow_html'] ) == "on") ? true : false;

    // Enable No influence to score for new question
    $options['quiz_enable_question_not_influence_to_score'] = isset($options['quiz_enable_question_not_influence_to_score']) ? sanitize_text_field( $options['quiz_enable_question_not_influence_to_score'] ) : 'off';
    $quiz_enable_question_not_influence_to_score = (isset($options['quiz_enable_question_not_influence_to_score']) && sanitize_text_field( $options['quiz_enable_question_not_influence_to_score'] ) == "on") ? true : false;

    // Start button activation
    $options['enable_start_button_loader'] = isset($options['enable_start_button_loader']) ? sanitize_text_field( $options['enable_start_button_loader'] ) : 'off';
    $enable_start_button_loader = (isset($options['enable_start_button_loader']) && sanitize_text_field( $options['enable_start_button_loader'] ) == "on") ? true : false;

    // Leaderboard By Quiz Category Settings
    $default_leadboard_column_names = array(
        "pos"        => __( 'Pos.', 'quiz-maker' ),
        "name"       => __( 'Name', 'quiz-maker' ),
        "score"      => __( 'Score', 'quiz-maker' ),
        "duration"   => __( 'Duration', 'quiz-maker' ),
        "points"     => __( 'Points', 'quiz-maker' ),
        "admin_note" => __( 'Admin Note', 'quiz-maker' ),
    );

    // WP Editor height
    $quiz_wp_editor_height = (isset($options['quiz_wp_editor_height']) && $options['quiz_wp_editor_height'] != '' && $options['quiz_wp_editor_height'] != 0) ? absint( sanitize_text_field($options['quiz_wp_editor_height']) ) : 100 ;

    // Textarea height (public)
    $quiz_textarea_height = (isset($options['quiz_textarea_height']) && $options['quiz_textarea_height'] != '' && $options['quiz_textarea_height'] != 0) ? absint( sanitize_text_field($options['quiz_textarea_height']) ) : 100 ;

    // Show quiz button to Admins only
    $options['quiz_show_quiz_button_to_admin_only'] = isset($options['quiz_show_quiz_button_to_admin_only']) ? sanitize_text_field( $options['quiz_show_quiz_button_to_admin_only'] ) : 'off';
    $quiz_show_quiz_button_to_admin_only = (isset($options['quiz_show_quiz_button_to_admin_only']) && sanitize_text_field( $options['quiz_show_quiz_button_to_admin_only'] ) == "on") ? true : false;

    // Question title view
    $quiz_question_title_view = (isset($options['quiz_question_title_view']) && sanitize_text_field( $options['quiz_question_title_view'] ) != "") ? stripslashes( esc_attr($options['quiz_question_title_view']) ) : 'question_title';


    // Fields placeholders | Start

    $fields_placeholders_res      = ($actions->ays_get_setting('fields_placeholders') === false) ? json_encode(array()) : $actions->ays_get_setting('fields_placeholders');
    $fields_placeholders          = json_decode( stripcslashes( $fields_placeholders_res ) , true);

    $quiz_fields_placeholder_name  = (isset($fields_placeholders['quiz_fields_placeholder_name']) && $fields_placeholders['quiz_fields_placeholder_name'] != '') ? stripslashes( esc_attr( $fields_placeholders['quiz_fields_placeholder_name'] ) ) : 'Name';

    $quiz_fields_placeholder_eamil = (isset($fields_placeholders['quiz_fields_placeholder_eamil']) && $fields_placeholders['quiz_fields_placeholder_eamil'] != '') ? stripslashes( esc_attr( $fields_placeholders['quiz_fields_placeholder_eamil'] ) ) : 'Email';

    $quiz_fields_placeholder_phone = (isset($fields_placeholders['quiz_fields_placeholder_phone']) && $fields_placeholders['quiz_fields_placeholder_phone'] != '') ? stripslashes( esc_attr( $fields_placeholders['quiz_fields_placeholder_phone'] ) ) : 'Phone Number';

    $quiz_fields_label_name  = (isset($fields_placeholders['quiz_fields_label_name']) && $fields_placeholders['quiz_fields_label_name'] != '') ? stripslashes( esc_attr( $fields_placeholders['quiz_fields_label_name'] ) ) : 'Name';

    $quiz_fields_label_eamil = (isset($fields_placeholders['quiz_fields_label_eamil']) && $fields_placeholders['quiz_fields_label_eamil'] != '') ? stripslashes( esc_attr( $fields_placeholders['quiz_fields_label_eamil'] ) ) : 'Email';

    $quiz_fields_label_phone = (isset($fields_placeholders['quiz_fields_label_phone']) && $fields_placeholders['quiz_fields_label_phone'] != '') ? stripslashes( esc_attr( $fields_placeholders['quiz_fields_label_phone'] ) ) : 'Phone Number';

    // Fields placeholders | End

    // General CSS File
    $options['quiz_exclude_general_css'] = isset($options['quiz_exclude_general_css']) ? esc_attr( $options['quiz_exclude_general_css'] ) : 'off';
    $quiz_exclude_general_css = (isset($options['quiz_exclude_general_css']) && esc_attr( $options['quiz_exclude_general_css'] ) == "on") ? true : false;

    // Enable question answers
    $options['quiz_enable_question_answers'] = isset($options['quiz_enable_question_answers']) ? esc_attr( $options['quiz_enable_question_answers'] ) : 'off';
    $quiz_enable_question_answers = (isset($options['quiz_enable_question_answers']) && esc_attr( $options['quiz_enable_question_answers'] ) == "on") ? true : false;

    // Show correct answers
    $options['quiz_show_correct_answers'] = isset($options['quiz_show_correct_answers']) ? esc_attr( $options['quiz_show_correct_answers'] ) : 'off';
    $quiz_show_correct_answers = (isset($options['quiz_show_correct_answers']) && esc_attr( $options['quiz_show_correct_answers'] ) == "on") ? true : false;

    // Default all orders column
    $default_all_orders_columns = array(
        'quiz_name'      => 'quiz_name',
        'payment_date'   => 'payment_date',
        'amount'         => 'amount',
        'type'           => 'type'
    );

    $default_all_orders_columns_names = array(
        "quiz_name"      => __( 'Quiz name', 'quiz-maker' ),
        "payment_date"   => __( 'Payment date','quiz-maker' ),
        "amount"         => __( 'Amount',  'quiz-maker' ),
        "type"           => __( 'Type',  'quiz-maker' )
    );

    $options['quiz_all_orders_columns'] = !isset( $options['quiz_all_orders_columns'] ) || empty($options['quiz_all_orders_columns']) ? $default_all_orders_columns : $options['quiz_all_orders_columns'];
    $quiz_all_orders_columns = (isset( $options['quiz_all_orders_columns'] ) && !empty($options['quiz_all_orders_columns']) ) ? $options['quiz_all_orders_columns'] : array();
    $quiz_all_orders_columns_order = (isset( $options['quiz_all_orders_columns_order'] ) && !empty($options['quiz_all_orders_columns_order']) ) ? $options['quiz_all_orders_columns_order'] : $default_all_orders_columns;

    // Enable lazy loading attribute for images
    $options['quiz_enable_lazy_loading'] = isset($options['quiz_enable_lazy_loading']) ? esc_attr( $options['quiz_enable_lazy_loading'] ) : 'off';
    $quiz_enable_lazy_loading = (isset($options['quiz_enable_lazy_loading']) && esc_attr( $options['quiz_enable_lazy_loading'] ) == "on") ? true : false;

    // Disable Quiz maker menu item notification
    $options['quiz_disable_quiz_menu_notification'] = isset($options['quiz_disable_quiz_menu_notification']) ? esc_attr( $options['quiz_disable_quiz_menu_notification'] ) : 'off';
    $quiz_disable_quiz_menu_notification = (isset($options['quiz_disable_quiz_menu_notification']) && esc_attr( $options['quiz_disable_quiz_menu_notification'] ) == "on") ? true : false;

    // Disable results menu item notification
    $options['quiz_disable_results_menu_notification'] = isset($options['quiz_disable_results_menu_notification']) ? esc_attr( $options['quiz_disable_results_menu_notification'] ) : 'off';
    $quiz_disable_results_menu_notification = (isset($options['quiz_disable_results_menu_notification']) && esc_attr( $options['quiz_disable_results_menu_notification'] ) == "on") ? true : false;

    $options['quiz_disable_question_report_menu_notification'] = isset($options['quiz_disable_question_report_menu_notification']) ? sanitize_text_field( $options['quiz_disable_question_report_menu_notification'] ) : 'off';
    $quiz_disable_question_report_menu_notification = (isset($options['quiz_disable_question_report_menu_notification']) && sanitize_text_field( $options['quiz_disable_question_report_menu_notification'] ) == "on") ? true : false;

    // Show Result Information | Start

    $options['ays_quiz_show_result_info_user_ip'] = isset($options['ays_quiz_show_result_info_user_ip']) ? sanitize_text_field( $options['ays_quiz_show_result_info_user_ip'] ) : 'on';
    $ays_quiz_show_result_info_user_ip = (isset($options['ays_quiz_show_result_info_user_ip']) && sanitize_text_field( $options['ays_quiz_show_result_info_user_ip'] ) == "on") ? true : false;

    $options['ays_quiz_show_result_info_user_id'] = isset($options['ays_quiz_show_result_info_user_id']) ? sanitize_text_field( $options['ays_quiz_show_result_info_user_id'] ) : 'on';
    $ays_quiz_show_result_info_user_id = (isset($options['ays_quiz_show_result_info_user_id']) && sanitize_text_field( $options['ays_quiz_show_result_info_user_id'] ) == "on") ? true : false;

    $options['ays_quiz_show_result_info_user'] = isset($options['ays_quiz_show_result_info_user']) ? sanitize_text_field( $options['ays_quiz_show_result_info_user'] ) : 'on';
    $ays_quiz_show_result_info_user = (isset($options['ays_quiz_show_result_info_user']) && sanitize_text_field( $options['ays_quiz_show_result_info_user'] ) == "on") ? true : false;

    $options['ays_quiz_show_result_info_user_email'] = isset($options['ays_quiz_show_result_info_user_email']) ? sanitize_text_field( $options['ays_quiz_show_result_info_user_email'] ) : 'on';
    $ays_quiz_show_result_info_user_email = (isset($options['ays_quiz_show_result_info_user_email']) && sanitize_text_field( $options['ays_quiz_show_result_info_user_email'] ) == "on") ? true : false;

    $options['ays_quiz_show_result_info_user_name'] = isset($options['ays_quiz_show_result_info_user_name']) ? sanitize_text_field( $options['ays_quiz_show_result_info_user_name'] ) : 'on';
    $ays_quiz_show_result_info_user_name = (isset($options['ays_quiz_show_result_info_user_name']) && sanitize_text_field( $options['ays_quiz_show_result_info_user_name'] ) == "on") ? true : false;

    $options['ays_quiz_show_result_info_user_phone'] = isset($options['ays_quiz_show_result_info_user_phone']) ? sanitize_text_field( $options['ays_quiz_show_result_info_user_phone'] ) : 'on';
    $ays_quiz_show_result_info_user_phone = (isset($options['ays_quiz_show_result_info_user_phone']) && sanitize_text_field( $options['ays_quiz_show_result_info_user_phone'] ) == "on") ? true : false;

    $options['ays_quiz_show_result_info_start_date'] = isset($options['ays_quiz_show_result_info_start_date']) ? sanitize_text_field( $options['ays_quiz_show_result_info_start_date'] ) : 'on';
    $ays_quiz_show_result_info_start_date = (isset($options['ays_quiz_show_result_info_start_date']) && sanitize_text_field( $options['ays_quiz_show_result_info_start_date'] ) == "on") ? true : false;

    $options['ays_quiz_show_result_info_duration'] = isset($options['ays_quiz_show_result_info_duration']) ? sanitize_text_field( $options['ays_quiz_show_result_info_duration'] ) : 'on';
    $ays_quiz_show_result_info_duration = (isset($options['ays_quiz_show_result_info_duration']) && sanitize_text_field( $options['ays_quiz_show_result_info_duration'] ) == "on") ? true : false;

    $options['ays_quiz_show_result_info_score'] = isset($options['ays_quiz_show_result_info_score']) ? sanitize_text_field( $options['ays_quiz_show_result_info_score'] ) : 'on';
    $ays_quiz_show_result_info_score = (isset($options['ays_quiz_show_result_info_score']) && sanitize_text_field( $options['ays_quiz_show_result_info_score'] ) == "on") ? true : false;

    $options['ays_quiz_show_result_info_rate'] = isset($options['ays_quiz_show_result_info_rate']) ? sanitize_text_field( $options['ays_quiz_show_result_info_rate'] ) : 'on';
    $ays_quiz_show_result_info_rate = (isset($options['ays_quiz_show_result_info_rate']) && sanitize_text_field( $options['ays_quiz_show_result_info_rate'] ) == "on") ? true : false;
    
    // Show Result Information | End

    // Enable Hide question text for new question
    $options['quiz_enable_question_hide_question_text'] = isset($options['quiz_enable_question_hide_question_text']) ? sanitize_text_field( $options['quiz_enable_question_hide_question_text'] ) : 'off';
    $quiz_enable_question_hide_question_text = (isset($options['quiz_enable_question_hide_question_text']) && sanitize_text_field( $options['quiz_enable_question_hide_question_text'] ) == "on") ? true : false;

    // Strip slashes for answers
    $options['quiz_stripslashes_for_answer'] = isset($options['quiz_stripslashes_for_answer']) ? sanitize_text_field( $options['quiz_stripslashes_for_answer'] ) : 'off';
    $quiz_stripslashes_for_answer = (isset($options['quiz_stripslashes_for_answer']) && sanitize_text_field( $options['quiz_stripslashes_for_answer'] ) == "on") ? true : false;

    // Enable case sensitive text for a new question
    $options['quiz_case_sensitive_text'] = isset($options['quiz_case_sensitive_text']) ? sanitize_text_field( $options['quiz_case_sensitive_text'] ) : 'off';
    $quiz_case_sensitive_text = (isset($options['quiz_case_sensitive_text']) && sanitize_text_field( $options['quiz_case_sensitive_text'] ) == "on") ? true : false;

    $settings_ordering = Quiz_Maker_Admin::ays_quiz_get_settings_order_defaults();
    
    $result_page_ordering_htmls = $settings_ordering['result_page'];

    $buttons_ordering = $settings_ordering['buttons'];
    $buttons_ordering_mobile = $settings_ordering['buttons'];

    $buttons_default_sort = array_keys($buttons_ordering);

    $quiz_result_order_res = json_encode(array());
    $quiz_result_order = json_decode($quiz_result_order_res, true);


    $quiz_buttons_order_res = json_encode(array());
    $quiz_buttons_order = json_decode($quiz_buttons_order_res, true);
    $buttons_sort_desktop = isset($quiz_buttons_order['desktop']) ? $quiz_buttons_order['desktop'] : $buttons_default_sort ;
    $buttons_sort_mobile = isset($quiz_buttons_order['mobile']) ? $quiz_buttons_order['mobile'] : $buttons_default_sort ;


    $result_default_sort = array_keys($result_page_ordering_htmls);

    $result_sort = !empty($quiz_result_order) ? $quiz_result_order : $result_default_sort;

    if( isset( $_REQUEST['ays_quiz_default_buttons_order'] ) ){
        $buttons_sort_desktop = $buttons_default_sort ;
        $buttons_sort_mobile = $buttons_default_sort ;
    }
  
    if( isset( $_REQUEST['ays_quiz_default_result_order'] ) ){
        $result_sort = $result_default_sort ;
    }

    $woo_bg = 'ays-quiz-bg-light-secondary';

    if (class_exists('WooCommerce')) {
        $woo_bg = '';
    }

    if (function_exists('is_plugin_active')) {
        $woo_bg =  is_plugin_active('woocommerce/woocommerce.php') ? '': 'ays-quiz-bg-light-secondary';
    }

    $conditions_bg = 'ays-quiz-bg-light-secondary';

    if(has_action('ays_qm_conditions_action')){
        $conditions_bg = '';
    }
    $save_button_bg = 'ays-quiz-bg-light-secondary';

    if(has_action('ays_qm_front_end_question_additional_buttons')){
        $save_button_bg = '';
    }

    // Enable case sensitive text for a new question
    $options['quiz_disable_live_chat_icon'] = isset($options['quiz_disable_live_chat_icon']) ? sanitize_text_field( $options['quiz_disable_live_chat_icon'] ) : 'off';
    $quiz_disable_live_chat_icon = (isset($options['quiz_disable_live_chat_icon']) && sanitize_text_field( $options['quiz_disable_live_chat_icon'] ) == "on") ? true : false;

?>
<div class="wrap" style="position:relative;">
    <div class="container-fluid">
        <h1 class="wp-heading-inline" style="display: block;">
            <?php
                echo __('General Settings','quiz-maker');
            ?>
            <div class="ays-quiz-heading-box ays-quiz-heading-box-margin-top" style="margin-top: 0;">
                <div class="ays-quiz-wordpress-user-manual-box">
                    <a href="https://quiz-plugin.com/docs/" target="_blank">
                        <i class="ays_fa ays_fa_file_text" ></i> 
                        <span style="margin-left: 3px;text-decoration: underline;"><?php echo __("View Documentation", "quiz-maker"); ?></span>
                    </a>
                </div>
            </div>
        </h1>
        <?php do_action('ays_quiz_sale_banner'); ?>
        <form method="post" class="ays-quiz-general-settings-form" id="ays-quiz-general-settings-form">
            <input type="hidden" name="ays_quiz_tab" value="<?php echo esc_attr($ays_quiz_tab); ?>">
            <hr/>
            <div class="form-group ays-settings-wrapper">
                <div>
                    <div class="nav-tab-wrapper" style="position:sticky; top:35px;">
                        <a href="#tab1" data-tab="tab1" class="nav-tab <?php echo ($ays_quiz_tab == 'tab1') ? 'nav-tab-active' : ''; ?>">
                            <?php echo __("General", 'quiz-maker');?>
                        </a>
                        <!-- <a href="#tab2" data-tab="tab2" class="nav-tab <?php echo ($ays_quiz_tab == 'tab2') ? 'nav-tab-active' : ''; ?>">
                            <?php echo __("Integrations", 'quiz-maker');?>
                        </a> -->
                        <a href="#tab3" data-tab="tab3" class="nav-tab <?php echo ($ays_quiz_tab == 'tab3') ? 'nav-tab-active' : ''; ?>">
                            <?php echo __("Shortcodes", 'quiz-maker');?>
                        </a>
                        <a href="#tab7" data-tab="tab7" class="nav-tab <?php echo ($ays_quiz_tab == 'tab7') ? 'nav-tab-active' : ''; ?>">
                            <?php echo __("Extra shortcodes", 'quiz-maker');?>
                        </a>
                        <a href="#tab4" data-tab="tab4" class="nav-tab <?php echo ($ays_quiz_tab == 'tab4') ? 'nav-tab-active' : ''; ?>">
                            <?php echo __("Message variables", 'quiz-maker');?>
                        </a>
                        <a href="#tab5" data-tab="tab5" class="nav-tab <?php echo ($ays_quiz_tab == 'tab5') ? 'nav-tab-active' : ''; ?>">
                            <?php echo __("Text Customizations", 'quiz-maker');?>
                        </a>
                        <a href="#tab6" data-tab="tab6" class="nav-tab <?php echo ($ays_quiz_tab == 'tab6') ? 'nav-tab-active' : ''; ?>">
                            <?php echo __("Fields texts", 'quiz-maker');?>
                        </a>
                        <a href="#tab8" data-tab="tab8" class="nav-tab <?php echo ($ays_quiz_tab == 'tab8') ? 'nav-tab-active' : ''; ?>">
                            <?php echo __("Detailed Report Options", 'quiz-maker');?>
                        </a>
                        <a href="#tab9" data-tab="tab9" class="nav-tab <?php echo ($ays_quiz_tab == 'tab9') ? 'nav-tab-active' : ''; ?>">
                            <?php echo __("Export Settings", 'quiz-maker');?>
                        </a>
                        <a href="#tab10" data-tab="tab10" class="nav-tab <?php echo ($ays_quiz_tab == 'tab10') ? 'nav-tab-active' : ''; ?>">
                            <?php echo esc_html__("Ordering", 'quiz-maker');?>
                        </a>
                    </div>
                </div>
                <div class="ays-quiz-tabs-wrapper">
                    <div id="tab1" class="ays-quiz-tab-content <?php echo ($ays_quiz_tab == 'tab1') ? 'ays-quiz-tab-content-active' : ''; ?>">
                        <p class="ays-subtitle"><?php echo __('General Settings','quiz-maker')?></p>
                        <hr class="ays-quiz-bolder-hr"/>
                        <fieldset>
                            <legend>
                                <strong style="font-size:30px;"><i class="ays_fa ays_fa_question_circle"></i></strong>
                                <h5><?php echo __('Default parameters for Quiz','quiz-maker')?></h5>
                            </legend>
                            <div class="form-group row">
                                <div class="col-sm-4">
                                    <label for="ays_questions_default_type">
                                        <?php echo __( "Questions default type", 'quiz-maker' ); ?>
                                        <a class="ays_help" data-toggle="tooltip" title="<?php echo __('You can choose default question type which will be selected in the Add new question page.','quiz-maker')?>">
                                            <i class="ays_fa ays_fa_info_circle"></i>
                                        </a>
                                    </label>
                                </div>
                                <div class="col-sm-8">
                                    <select id="ays-type" name="ays_question_default_type">
                                        <option></option>
                                        <?php
                                            foreach($question_types as $type => $label):
                                            $selected = $question_default_type == $type ? "selected" : "";
                                            $ays_question_disabled = "";
                                            if ( $type == "custom" || $type == "fill_in_blank" || $type == "matching" || $type == "upload_file" ) {
                                                $ays_question_disabled = "disabled title='". __( "This feature is available only in PRO version", 'quiz-maker' ) ."' ";
                                            }
                                        ?>
                                        <option value="<?php echo $type; ?>" data-nkar="<?php echo $question_types_icon_url[ $type ]; ?>" <?php echo $selected; ?> <?php echo $ays_question_disabled; ?> ><?php echo $label; ?></option>
                                        <?php
                                            endforeach;
                                        ?>
                                    </select>
                                </div>
                            </div>
                            <hr />
                            <div class="form-group row">
                                <div class="col-sm-4">
                                    <label for="ays_answer_default_count">
                                        <?php echo __( "Answer default count", 'quiz-maker' ); ?>
                                        <a class="ays_help" data-toggle="tooltip" title="<?php echo __('You can write the default answer count which will be showing in the Add new question page (this will work only with radio, checkbox, and dropdown types).','quiz-maker')?>">
                                            <i class="ays_fa ays_fa_info_circle"></i>
                                        </a>
                                    </label>
                                </div>
                                <div class="col-sm-8">
                                    <input type="number" name="ays_answer_default_count" id="ays_answer_default_count" min="2" class="ays-text-input" value="<?php echo $ays_answer_default_count; ?>">
                                </div>
                            </div>
                            <hr />
                            <div class="form-group row">
                                <div class="col-sm-4">
                                    <label for="ays_question_default_category">
                                        <?php echo __( "Questions default category", 'quiz-maker' ); ?>
                                        <a class="ays_help" data-toggle="tooltip" title="<?php echo __('Choose the category of the questions which will be selected by default each time you create a question by the Add New button.','quiz-maker')?>">
                                            <i class="ays_fa ays_fa_info_circle"></i>
                                        </a>
                                    </label>
                                </div>
                                <div class="col-sm-8">
                                    <select id="ays_question_default_category" name="ays_question_default_category">
                                        <option></option>
                                        <?php
                                            foreach($question_categories as $key => $question_category):
                                                $question_category_id = $question_category['id'];
                                                $question_category_title = $question_category['title'];
                                                $selected = ($question_default_category == $question_category_id) ? "selected" : "";
                                        ?>
                                        <option value="<?php echo $question_category_id; ?>" <?php echo $selected; ?> ><?php echo $question_category_title; ?></option>
                                        <?php
                                            endforeach;
                                        ?>
                                    </select>
                                </div>
                            </div>
                            <hr />
                            <div class="form-group row">
                                <div class="col-sm-4">
                                    <label for="ays_quiz_wp_editor_height">
                                        <?php echo __( "WP Editor height", 'quiz-maker' ); ?>
                                        <a class="ays_help" data-toggle="tooltip" title="<?php echo __('Give the default value to the height of the WP Editor. It will apply to all WP Editors within the plugin on the dashboard.','quiz-maker'); ?>">
                                            <i class="ays_fa ays_fa_info_circle"></i>
                                        </a>
                                    </label>
                                </div>
                                <div class="col-sm-8">
                                    <input type="number" name="ays_quiz_wp_editor_height" id="ays_quiz_wp_editor_height" class="ays-text-input" value="<?php echo $quiz_wp_editor_height; ?>">
                                </div>
                            </div>
                            <hr />
                            <div class="form-group row">
                                <div class="col-sm-4">
                                    <label for="ays_quiz_textarea_height">
                                        <?php echo __( "Textarea height (public)", 'quiz-maker' ); ?>
                                        <a class="ays_help" data-toggle="tooltip" title="<?php echo __('Set the height of the textarea by entering a numeric value. It applies to Text question type textarea, Feedback textarea and so on.','quiz-maker'); ?>">
                                            <i class="ays_fa ays_fa_info_circle"></i>
                                        </a>
                                    </label>
                                </div>
                                <div class="col-sm-8">
                                    <input type="number" name="ays_quiz_textarea_height" id="ays_quiz_textarea_height" class="ays-text-input" value="<?php echo $quiz_textarea_height; ?>">
                                </div>
                            </div>
                            <hr />
                            <div class="form-group row">
                                <div class="col-sm-4">
                                    <label for="ays_quiz_question_title_view">
                                        <?php echo __( "Question Title View", 'quiz-maker' ); ?>
                                        <a class="ays_help" data-toggle="tooltip" data-html="true" title="<?php
                                            echo 
                                                "<ul style='list-style-type: circle;padding-left: 20px;'>".
                                                    "<li>". __('By Question Title - Choose this method to display the text you write for the Question Title option.','quiz-maker') ."</li>".
                                                    "<li>". __('By Question Content - Choose this method to show the question content instead of the question title.','quiz-maker') ."</li>".
                                                "</ul>" .
                                                __('*Note: These options will work only in the table of the Quiz Edit page and when inserting questions to the quiz.','quiz-maker');
                                            ?>">
                                            <i class="ays_fa ays_fa_info_circle"></i>
                                        </a>
                                    </label>
                                </div>
                                <div class="col-sm-8">
                                    <label class="ays_quiz_loader">
                                        <input type="radio" id="ays_quiz_question_title_view_title" name="ays_quiz_question_title_view" value="question_title" <?php echo ($quiz_question_title_view == 'question_title') ? 'checked' : ''; ?>/>
                                        <span for="ays_quiz_question_title_view_title"><?php echo __('By Question Title','quiz-maker'); ?></span>
                                    </label>
                                    <label class="ays_quiz_loader">
                                        <input type="radio" id="ays_quiz_question_title_view_content" name="ays_quiz_question_title_view" value="question_content" <?php echo ($quiz_question_title_view == 'question_content') ? 'checked' : ''; ?>/>
                                        <span for="ays_quiz_question_title_view_content"><?php echo __('By Question Content','quiz-maker'); ?></span>
                                    </label>
                                </div>
                            </div>
                            <hr />
                            <div class="form-group row">
                                <div class="col-sm-4">
                                    <label for="ays_quiz_enable_question_allow_html">
                                        <?php echo __( "Enable answers allow HTML for new question", 'quiz-maker' ); ?>
                                        <a class="ays_help" data-toggle="tooltip" title="<?php echo __('Allow implementing HTML coding in answer boxes while adding new question. This works only for Radio and Checkbox (Multiple) questions.','quiz-maker'); ?>">
                                            <i class="ays_fa ays_fa_info_circle"></i>
                                        </a>
                                    </label>
                                </div>
                                <div class="col-sm-8">
                                    <input type="checkbox" class="ays-checkbox-input" id="ays_quiz_enable_question_allow_html" name="ays_quiz_enable_question_allow_html" value="on" <?php echo $quiz_enable_question_allow_html ? 'checked' : ''; ?> />
                                </div>
                            </div>
                            <hr />
                            <div class="form-group row">
                                <div class="col-sm-4">
                                    <label for="ays_quiz_enable_question_not_influence_to_score">
                                        <?php echo __( "Enable No influence to score for new question", 'quiz-maker' ); ?>
                                        <a class="ays_help" data-toggle="tooltip" title="<?php echo __('Enable this option and the No Influence to score option will be ticked for a new question by default.','quiz-maker'); ?>">
                                            <i class="ays_fa ays_fa_info_circle"></i>
                                        </a>
                                    </label>
                                </div>
                                <div class="col-sm-8">
                                    <input type="checkbox" class="ays-checkbox-input" id="ays_quiz_enable_question_not_influence_to_score" name="ays_quiz_enable_question_not_influence_to_score" value="on" <?php echo $quiz_enable_question_not_influence_to_score ? 'checked' : ''; ?> />
                                </div>
                            </div>
                            <hr />
                            <div class="form-group row">
                                <div class="col-sm-4">
                                    <label for="ays_quiz_enable_question_hide_question_text">
                                        <?php echo __( "Enable Hide question text for new question", 'quiz-maker' ); ?>
                                        <a class="ays_help" data-toggle="tooltip" title="<?php echo __('Enable this option and the Hide question text on the front-end option will be ticked for a new question by default.','quiz-maker'); ?>">
                                            <i class="ays_fa ays_fa_info_circle"></i>
                                        </a>
                                    </label>
                                </div>
                                <div class="col-sm-8">
                                    <input type="checkbox" class="ays-checkbox-input" id="ays_quiz_enable_question_hide_question_text" name="ays_quiz_enable_question_hide_question_text" value="on" <?php echo $quiz_enable_question_hide_question_text ? 'checked' : ''; ?> />
                                </div>
                            </div>
                            <hr />
                            <div class="form-group row">
                                <div class="col-sm-4">
                                    <label for="ays_quiz_stripslashes_for_answer">
                                        <?php echo __( "Strip slashes for answers for a new question", 'quiz-maker' ); ?>
                                        <a class="ays_help" data-toggle="tooltip" title="<?php echo __('Enable this option and the Strip slashes for answers option will be ticked for a new question by default.','quiz-maker'); ?>">
                                            <i class="ays_fa ays_fa_info_circle"></i>
                                        </a>
                                    </label>
                                </div>
                                <div class="col-sm-8">
                                    <input type="checkbox" class="ays-checkbox-input" id="ays_quiz_stripslashes_for_answer" name="ays_quiz_stripslashes_for_answer" value="on" <?php echo $quiz_stripslashes_for_answer ? 'checked' : ''; ?> />
                                </div>
                            </div>
                            <hr />
                            <div class="form-group row">
                                <div class="col-sm-4">
                                    <label for="ays_quiz_case_sensitive_text">
                                        <?php echo __( "Enable case sensitive text for a new question", 'quiz-maker' ); ?>
                                        <a class="ays_help" data-toggle="tooltip" title="<?php echo __('Enable this option and the case sensitive text option will be ticked for a new question by default.','quiz-maker'); ?>">
                                            <i class="ays_fa ays_fa_info_circle"></i>
                                        </a>
                                    </label>
                                </div>
                                <div class="col-sm-8">
                                    <input type="checkbox" class="ays-checkbox-input" id="ays_quiz_case_sensitive_text" name="ays_quiz_case_sensitive_text" value="on" <?php echo $quiz_case_sensitive_text ? 'checked' : ''; ?> />
                                </div>
                            </div>
                            <hr />
                            <div class="form-group row">
                                <div class="col-sm-4">
                                    <label for="ays_quiz_show_quiz_button_to_admin_only">
                                        <?php echo __( "Show quiz button to Admins only", 'quiz-maker' ); ?>
                                        <a class="ays_help" data-toggle="tooltip" title="<?php echo __('Allow only admins to see the Quiz Maker button within the WP Editor while adding/editing a new post/page.','quiz-maker'); ?>">
                                            <i class="ays_fa ays_fa_info_circle"></i>
                                        </a>
                                    </label>
                                </div>
                                <div class="col-sm-8">
                                    <input type="checkbox" class="ays-checkbox-input" id="ays_quiz_show_quiz_button_to_admin_only" name="ays_quiz_show_quiz_button_to_admin_only" value="on" <?php echo $quiz_show_quiz_button_to_admin_only ? 'checked' : ''; ?> />
                                </div>
                            </div>
                            <?php if( 1 == 0 ): ?>
                            <hr />
                            <div class="form-group row" style="padding:0px;margin:0;">
                                <div class="col-sm-12 only_pro" style="padding:20px;">
                                    <div class="pro_features" style="">

                                    </div>
                                    <div class="form-group row">
                                        <div class="col-sm-4">
                                            <label for="ays_questions_default_keyword">
                                                <?php echo __( "Keyword default count", 'quiz-maker' ); ?>
                                                <a class="ays_help" data-toggle="tooltip" title="<?php echo __('Specify the default keyword count which will be selected while adding answers to your new question. It will apply to the previous questions and intervals as well.','quiz-maker'); ?>">
                                                    <i class="ays_fa ays_fa_info_circle"></i>
                                                </a>
                                            </label>
                                        </div>
                                        <div class="col-sm-8">
                                            <input type="number" id="ays_keyword_default_max_value" class="ays-text-input">
                                        </div>
                                    </div>
                                    <a href="https://ays-pro.com/wordpress/quiz-maker?utm_source=dashboard&utm_medium=quiz-free&utm_campaign=keyword-default-count-<?php echo esc_attr( AYS_QUIZ_UTM_VERSION ); ?>" target="_blank" class="ays-quiz-new-upgrade-button-link">
                                        <div class="ays-quiz-new-upgrade-button-box">
                                            <div>
                                                <img src="<?php echo AYS_QUIZ_ADMIN_URL.'/images/icons/locked_24x24.svg'?>">
                                                <img src="<?php echo AYS_QUIZ_ADMIN_URL.'/images/icons/unlocked_24x24.svg'?>" class="ays-quiz-new-upgrade-button-hover">
                                            </div>
                                            <div class="ays-quiz-new-upgrade-button"><?php echo __("Upgrade", "quiz-maker"); ?></div>
                                        </div>
                                    </a>
                                </div>
                            </div>
                            <?php endif; ?>
                        </fieldset> <!-- Default parameters for Quiz -->
                        <hr>
                        <fieldset>
                            <legend>
                                <strong style="font-size:30px;"><i class="ays_fa ays_fa_user_ip"></i></strong>
                                <h5><?php echo __('Users IP addresses','quiz-maker')?></h5>
                            </legend>
                            <blockquote class="ays_warning">
                                <p style="margin:0;"><?php echo __( "If this option is enabled then the 'Limitation by IP' option will not work!", 'quiz-maker' ); ?></p>
                            </blockquote>
                            <hr/>
                            <div class="form-group row">
                                <div class="col-sm-4">
                                    <label for="ays_disable_user_ip">
                                        <?php echo __( "Do not store IP addresses", 'quiz-maker' ); ?>
                                        <a class="ays_help" data-toggle="tooltip" title="<?php echo __('After enabling this option, IP address of the users will not be stored in database. Note: If this option is enabled, then the `Limits user by IP` option will not work.','quiz-maker')?>">
                                            <i class="ays_fa ays_fa_info_circle"></i>
                                        </a>
                                    </label>
                                </div>
                                <div class="col-sm-8">
                                    <input type="checkbox" class="ays-checkbox-input" id="ays_disable_user_ip" name="ays_disable_user_ip" value="on" <?php echo $disable_user_ip ? 'checked' : ''; ?> />
                                </div>
                            </div>
                        </fieldset> <!-- Users IP addresses -->
                        <hr>
                        <fieldset>
                            <legend>
                                <strong style="font-size:30px;"><i class="ays_fa ays_fa_music"></i></strong>
                                <h5><?php echo __('Quiz Right/Wrong answers sounds','quiz-maker')?></h5>
                            </legend>
                            <div class="form-group row">
                                <div class="col-sm-4">
                                    <label for="ays_questions_default_type">
                                        <?php echo __( "Sounds for right/wrong answers", 'quiz-maker' ); ?>
                                        <a class="ays_help" data-toggle="tooltip" title="<?php echo esc_attr( __('This option will work with Enable correct answers option.','quiz-maker') ); ?>">
                                            <i class="ays_fa ays_fa_info_circle"></i>
                                        </a>
                                    </label>
                                </div>
                                <div class="col-sm-8">
                                    <div class="form-group row">
                                        <div class="col-sm-12">
                                            <label for="ays_questions_default_type">
                                                <?php echo __( "Sounds for right answers", 'quiz-maker' ); ?>
                                            </label>                                            
                                            <div class="ays-bg-music-container">
                                                <a class="add-quiz-bg-music" href="javascript:void(0);"><?php echo esc_html__("Select sound", 'quiz-maker'); ?></a>
                                                <audio controls src="<?php echo esc_url($right_answer_sound); ?>"></audio>
                                                <input type="hidden" name="ays_right_answer_sound" class="ays_quiz_bg_music" value="<?php echo esc_url($right_answer_sound); ?>">
                                            </div>
                                        </div>
                                    </div>
                                    <hr>
                                    <div class="form-group row">
                                        <div class="col-sm-12">                                            
                                            <label for="ays_questions_default_type">
                                                <?php echo esc_html__( "Sounds for wrong answers", 'quiz-maker' ); ?>
                                            </label>
                                            <div class="ays-bg-music-container">
                                                <a class="add-quiz-bg-music" href="javascript:void(0);"><?php echo esc_html__("Select sound", 'quiz-maker'); ?></a>
                                                <audio controls src="<?php echo esc_url($wrong_answer_sound); ?>"></audio>
                                                <input type="hidden" name="ays_wrong_answer_sound" class="ays_quiz_bg_music" value="<?php echo esc_url($wrong_answer_sound); ?>">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </fieldset> <!-- Quiz Right/Wrong answers sounds -->
                        <hr>
                        <fieldset>
                            <legend>
                                <strong style="font-size:30px;"><i class="ays_fa ays_fa_text"></i></strong>
                                <h5><?php echo __('Excerpt words count in list tables','quiz-maker')?></h5>
                            </legend>
                            <div class="form-group row">
                                <div class="col-sm-4">
                                    <label for="ays_question_title_length">
                                        <?php echo __( "Questions list table", 'quiz-maker' ); ?>
                                        <a class="ays_help" data-toggle="tooltip" title="<?php echo __('Determine the length of the questions to be shown in the Questions List Table by putting your preferred count of words in the following field. (For example: if you put 10, you will see the first 10 words of each question in the Questions page of your dashboard).', 'quiz-maker'); ?>">
                                            <i class="ays_fa ays_fa_info_circle"></i>
                                        </a>
                                    </label>
                                </div>
                                <div class="col-sm-8">
                                    <input type="number" name="ays_question_title_length" id="ays_question_title_length" class="ays-text-input" value="<?php echo $question_title_length; ?>">
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-sm-4">
                                    <label for="ays_quizzes_title_length">
                                        <?php echo __( "Quizzes list table", 'quiz-maker' ); ?>
                                        <a class="ays_help" data-toggle="tooltip" title="<?php echo __('Determine the length of the quizzes to be shown in the Quizzes List Table by putting your preferred count of words in the following field. (For example: if you put 10, you will see the first 10 words of each quiz in the Quizzes page of your dashboard).', 'quiz-maker'); ?>">
                                            <i class="ays_fa ays_fa_info_circle"></i>
                                        </a>
                                    </label>
                                </div>
                                <div class="col-sm-8">
                                    <input type="number" name="ays_quizzes_title_length" id="ays_quizzes_title_length" class="ays-text-input" value="<?php echo $quizzes_title_length; ?>">
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-sm-4">
                                    <label for="ays_results_title_length">
                                        <?php echo __( "Results list table", 'quiz-maker' ); ?>
                                        <a class="ays_help" data-toggle="tooltip" title="<?php echo __('Determine the length of the results to be shown in the Results List Table by putting your preferred count of words in the following field. (For example: if you put 10, you will see the first 10 words of each result in the Results page of your dashboard).', 'quiz-maker'); ?>">
                                            <i class="ays_fa ays_fa_info_circle"></i>
                                        </a>
                                    </label>
                                </div>
                                <div class="col-sm-8">
                                    <input type="number" name="ays_results_title_length" id="ays_results_title_length" class="ays-text-input" value="<?php echo $results_title_length; ?>">
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-sm-4">
                                    <label for="ays_question_categories_title_length">
                                        <?php echo __( "Question categories list table", 'quiz-maker' ); ?>
                                        <a class="ays_help" data-toggle="tooltip" title="<?php echo __('Determine the length of the results to be shown in the Question categories List Table by putting your preferred count of words in the following field. (For example: if you put 10, you will see the first 10 words of each result in the Question categories page of your dashboard).', 'quiz-maker'); ?>">
                                            <i class="ays_fa ays_fa_info_circle"></i>
                                        </a>
                                    </label>
                                </div>
                                <div class="col-sm-8">
                                    <input type="number" name="ays_question_categories_title_length" id="ays_question_categories_title_length" class="ays-text-input" value="<?php echo $question_categories_title_length; ?>">
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-sm-4">
                                    <label for="ays_quiz_categories_title_length">
                                        <?php echo __( "Quiz categories list table", 'quiz-maker' ); ?>
                                        <a class="ays_help" data-toggle="tooltip" title="<?php echo __('Determine the length of the results to be shown in the Quiz categories List Table by putting your preferred count of words in the following field. (For example: if you put 10, you will see the first 10 words of each result in the Question categories page of your dashboard).', 'quiz-maker'); ?>">
                                            <i class="ays_fa ays_fa_info_circle"></i>
                                        </a>
                                    </label>
                                </div>
                                <div class="col-sm-8">
                                    <input type="number" name="ays_quiz_categories_title_length" id="ays_quiz_categories_title_length" class="ays-text-input" value="<?php echo $quiz_categories_title_length; ?>">
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-sm-4">
                                    <label for="ays_quiz_reviews_title_length">
                                        <?php echo __( "Reviews list table", 'quiz-maker' ); ?>
                                        <a class="ays_help" data-toggle="tooltip" title="<?php echo __('Determine the length of the results to be shown in the Reviews List Table by putting your preferred count of words in the following field. (For example: if you put 10, you will see the first 10 words of each result in the Reviews page of your dashboard)  .', 'quiz-maker'); ?>">
                                            <i class="ays_fa ays_fa_info_circle"></i>
                                        </a>
                                    </label>
                                </div>
                                <div class="col-sm-8">
                                    <input type="number" name="ays_quiz_reviews_title_length" id="ays_quiz_reviews_title_length" class="ays-text-input" value="<?php echo $quiz_reviews_title_length; ?>">
                                </div>
                            </div>
                        </fieldset> <!-- Excerpt words count in list tables -->
                        <hr>
                        <fieldset>
                            <legend>
                                <strong style="font-size:30px;"><i class="ays_fa ays_fa_spinner"></i></strong>
                                <h5><?php echo __('Start button activation','quiz-maker'); ?></h5>
                            </legend>
                            <blockquote>
                                <?php echo __( 'Tick on the checkbox if you would like to show loader and "Loading ..." text over the start button while the JavaScript of the given webpage loads. As soon as the webpage completes its loading, the start button will become active.', 'quiz-maker' ); ?>
                            </blockquote>
                            <hr>
                            <div class="form-group row">
                                <div class="col-sm-4">
                                    <label for="ays_enable_start_button_loader">
                                        <?php echo __( "Enable Start button loader", 'quiz-maker' ); ?>
                                    </label>
                                </div>
                                <div class="col-sm-8">
                                    <input type="checkbox" class="ays-checkbox-input" id="ays_enable_start_button_loader" name="ays_enable_start_button_loader" value="on" <?php echo $enable_start_button_loader ? 'checked' : ''; ?> />
                                </div>
                            </div>                            
                        </fieldset> <!-- Start button activation -->
                        <hr>
                        <fieldset>
                            <legend>
                                <strong style="font-size:30px;"><i class="ays_fa ays_fa_code"></i></strong>
                                <h5><?php echo __('Animation Top','quiz-maker')?></h5>
                            </legend>
                            <div class="form-group row">
                                <div class="col-sm-4">
                                    <label for="ays_quiz_enable_animation_top">
                                        <?php echo __( "Enable animation", 'quiz-maker' ); ?>
                                        <a class="ays_help" data-toggle="tooltip" title="<?php echo __('Enable animation of the scroll offset of the quiz container. It works when the quiz container is visible on the screen partly and the user starts the quiz and moves from one question to another.','quiz-maker')?>">
                                            <i class="ays_fa ays_fa_info_circle"></i>
                                        </a>
                                    </label>
                                </div>
                                <div class="col-sm-8">
                                    <input type="checkbox" name="ays_quiz_enable_animation_top" id="ays_quiz_enable_animation_top" value="on" <?php echo $quiz_enable_animation_top ? 'checked' : ''; ?>>
                                </div>
                            </div>
                            <hr>
                            <div class="form-group row">
                                <div class="col-sm-4">
                                    <label for="ays_quiz_animation_top">
                                        <?php echo __( "Scroll offset(px)", 'quiz-maker' ); ?>
                                        <a class="ays_help" data-toggle="tooltip" title="<?php echo __('Define the scroll offset of the quiz container after the animation starts. It works when the quiz container is visible on the screen partly and the user starts the quiz and moves from one question to another.','quiz-maker')?>">
                                            <i class="ays_fa ays_fa_info_circle"></i>
                                        </a>
                                    </label>
                                </div>
                                <div class="col-sm-8">
                                    <input type="number" name="ays_quiz_animation_top" id="ays_quiz_animation_top" class="ays-text-input" value="<?php echo $quiz_animation_top; ?>">
                                </div>
                            </div>                            
                        </fieldset> <!-- Animation Top -->
                        <hr>
                        <fieldset>
                            <legend>
                                <strong style="font-size:30px;"><i class="ays_fa ays_fa_file_code"></i></strong>
                                <h5><?php echo __('General CSS File','quiz-maker'); ?></h5>
                            </legend>
                            <div class="form-group row">
                                <div class="col-sm-4">
                                    <label for="ays_quiz_exclude_general_css">
                                        <?php echo __( "Exclude general CSS file from home page", 'quiz-maker' ); ?>
                                        <a class="ays_help" data-toggle="tooltip" title="<?php echo __('If the option is enabled, then the quiz general CSS file will not be applied to the home page. Please note, that if you have inserted the quiz on the home page, then the option must be disabled so that the CSS File can normally work for that quiz..','quiz-maker'); ?>">
                                            <i class="ays_fa ays_fa_info_circle"></i>
                                        </a>
                                    </label>
                                </div>
                                <div class="col-sm-8">
                                    <input type="checkbox" name="ays_quiz_exclude_general_css" id="ays_quiz_exclude_general_css" value="on" <?php echo $quiz_exclude_general_css ? 'checked' : ''; ?>>
                                </div>
                            </div>
                        </fieldset> <!-- General CSS File -->
                        <hr>
                        <fieldset>
                            <legend>
                                <img class="ays_integration_logo" src="<?php echo AYS_QUIZ_ADMIN_URL; ?>/images/integrations/ays-quiz-loading-icon.svg" alt="" style="width: 30px;">
                                <h5><?php echo __('Lazy loading','quiz-maker'); ?></h5>
                            </legend>
                            <div class="form-group row">
                                <div class="col-sm-4">
                                    <label for="ays_quiz_enable_lazy_loading">
                                        <?php echo __( "Enable lazy loading attribute for images", 'quiz-maker' ); ?>
                                        <a class="ays_help" data-toggle="tooltip" title="<?php echo esc_attr( __('If you enable this option, the loading="lazy" attribute will be added to all the question and answer images, except of the first question and answer images. Note: The feature will not work for the Quiz image option. The default value for this option is set as "On".','quiz-maker') ); ?>">
                                            <i class="ays_fa ays_fa_info_circle"></i>
                                        </a>
                                    </label>
                                </div>
                                <div class="col-sm-8">
                                    <input type="checkbox" name="ays_quiz_enable_lazy_loading" id="ays_quiz_enable_lazy_loading" value="on" <?php echo $quiz_enable_lazy_loading ? 'checked' : ''; ?>>
                                </div>
                            </div>
                        </fieldset> <!-- Lazy loading -->
                        <hr>
                        <fieldset>
                            <legend>
                                <strong style="font-size:30px;"><i class="ays_fa ays_fa_bell"></i></strong>
                                <h5><?php echo __('Menu notifications','quiz-maker'); ?></h5>
                            </legend>
                            <div class="form-group row">
                                <div class="col-sm-4">
                                    <label for="ays_quiz_disable_quiz_menu_notification">
                                        <?php echo __( "Disable Quiz maker menu item notification", 'quiz-maker' ); ?>
                                        <a class="ays_help" data-toggle="tooltip" title="<?php echo esc_attr( __('Enable this option and the notifications will not be displayed in the Quiz Maker menu.','quiz-maker') ); ?>">
                                            <i class="ays_fa ays_fa_info_circle"></i>
                                        </a>
                                    </label>
                                </div>
                                <div class="col-sm-8">
                                    <input type="checkbox" name="ays_quiz_disable_quiz_menu_notification" id="ays_quiz_disable_quiz_menu_notification" value="on" <?php echo $quiz_disable_quiz_menu_notification ? 'checked' : ''; ?>>
                                </div>
                            </div>
                            <hr>
                            <div class="form-group row">
                                <div class="col-sm-4">
                                    <label for="ays_quiz_disable_results_menu_notification">
                                        <?php echo __( "Disable Results menu item notification", 'quiz-maker' ); ?>
                                        <a class="ays_help" data-toggle="tooltip" title="<?php echo esc_attr( __('Enable this option and the notifications will not be displayed in the Results menu.','quiz-maker') ); ?>">
                                            <i class="ays_fa ays_fa_info_circle"></i>
                                        </a>
                                    </label>
                                </div>
                                <div class="col-sm-8">
                                    <input type="checkbox" name="ays_quiz_disable_results_menu_notification" id="ays_quiz_disable_results_menu_notification" value="on" <?php echo $quiz_disable_results_menu_notification ? 'checked' : ''; ?>>
                                </div>
                            </div>
                            <hr>
                            <div class="form-group row">
                                <div class="col-sm-4">
                                    <label for="ays_quiz_disable_question_report_menu_notification">
                                        <?php echo esc_html__( "Disable Question report item notification", 'quiz-maker' ); ?>
                                        <a class="ays_help" data-toggle="tooltip" title="<?php echo esc_attr( __('Enable this option and the notifications will not be displayed in the Question report menu.','quiz-maker') ); ?>">
                                            <i class="ays_fa ays_fa_info_circle"></i>
                                        </a>
                                    </label>
                                </div>
                                <div class="col-sm-8">
                                    <input type="checkbox" name="ays_quiz_disable_question_report_menu_notification" id="ays_quiz_disable_question_report_menu_notification" value="on" <?php echo $quiz_disable_question_report_menu_notification ? 'checked' : ''; ?>>
                                </div>
                            </div>
                            <hr>
                            <div class="form-group row">
                                <div class="col-sm-4">
                                    <label for="ays_quiz_disable_live_chat_icon">
                                        <?php echo esc_html__( "Disable Live Chat icon", 'quiz-maker' ); ?>
                                        <a class="ays_help" data-toggle="tooltip" title="<?php echo esc_attr( __('Enable this option and the Live Chat Icon will not be displayed in the Quiz Admin Dashboard.','quiz-maker') ); ?>">
                                            <i class="ays_fa ays_fa_info_circle"></i>
                                        </a>
                                    </label>
                                </div>
                                <div class="col-sm-8">
                                    <input type="checkbox" name="ays_quiz_disable_live_chat_icon" id="ays_quiz_disable_live_chat_icon" value="on" <?php echo esc_attr($quiz_disable_live_chat_icon) ? 'checked' : ''; ?>>
                                </div>
                            </div>
                        </fieldset> <!-- Menu notifications -->
                        <hr>
                        <fieldset>
                            <legend>
                                <strong style="font-size:30px;"><i class="ays_fa ays_fa_trash"></i></strong>
                                <h5><?php echo __('Erase Quiz data','quiz-maker')?></h5>
                            </legend>
                            <?php if( isset( $_GET['del_stat'] ) ): ?>
                            <blockquote style="border-color:#46b450;background: rgba(70, 180, 80, 0.2);">
                                <?php echo "Results up to a ". sanitize_text_field( $_GET['mcount'] ) ." month ago deleted successfully."; ?>
                            </blockquote>
                            <hr>
                            <?php endif; ?>
                            <div class="form-group row">
                                <div class="col-sm-4">
                                    <label for="ays_delete_results_by">
                                        <?php echo __( "Delete results older than 'X' the month", 'quiz-maker' ); ?>
                                        <a class="ays_help" data-toggle="tooltip" title="<?php echo __('Specify count of months and save changes. Attention! it will remove submissions older than specified months permanently.','quiz-maker')?>">
                                            <i class="ays_fa ays_fa_info_circle"></i>
                                        </a>
                                    </label>
                                </div>
                                <div class="col-sm-8">
                                    <input type="number" name="ays_delete_results_by" id="ays_delete_results_by" class="ays-text-input">
                                </div>
                            </div>                            
                        </fieldset> <!-- Erase Quiz data -->
                        <hr>
                        <fieldset>
                            <legend>
                                <strong style="font-size:30px;"><i class="fa fa-clock-o" aria-hidden="true"></i></strong>
                                <h5><?php echo esc_html__("Quiz Start/End Date",'quiz-maker')?></h5>
                            </legend>
                            <div class="col-sm-12 only_pro" style="padding:20px;">
                                <div class="pro_features" style="justify-content:flex-end;">

                                </div>
                                <div class="form-group row">
                                    <div class="col-sm-4">
                                        <label for="ays_quiz_user_date_type">
                                            <?php echo esc_html__( "Date Type", 'quiz-maker' ); ?>
                                            
                                        </label>
                                    </div>
                                    <div class="col-sm-8">
                                        <label class="ays_quiz_loader">
                                            <input type="radio" />
                                            <span for="ays_quiz_user_date_type_local">
                                                <?php echo esc_html__('Client Local','quiz-maker'); ?>
                                                <a class="ays_help" data-toggle="tooltip" data-html="true" title="<?php echo  esc_html__("Tick the checkbox to use the user's local device time for the Quiz Start/End Date.",'quiz-maker'); ?>">
                                                    <i class="ays_fa ays_fa_info_circle"></i>
                                                </a>
                                            </span>
                                        </label>
                                        <label class="ays_quiz_loader">
                                            <input type="radio" />
                                            <span for="ays_quiz_user_date_type_server">
                                                <?php echo esc_html__('Server','quiz-maker'); ?>
                                                <a class="ays_help" data-toggle="tooltip" data-html="true" title="<?php echo  esc_html__("Tick the checkbox to use the server's time for the Quiz Start/End Date. Note, that in this case, not finished results will be displayed on the Results page as well.",'quiz-maker'); ?>">
                                                    <i class="ays_fa ays_fa_info_circle"></i>
                                                </a>
                                            </span>
                                        </label>
                                    </div>
                                </div>
                                <a href="https://ays-pro.com/wordpress/quiz-maker?utm_source=dashboard&utm_medium=quiz-free&utm_campaign=client-local-server-time-<?php echo esc_attr( AYS_QUIZ_UTM_VERSION ); ?>" target="_blank" class="ays-quiz-new-upgrade-button-link">
                                    <div class="ays-quiz-new-upgrade-button-box">
                                        <div>
                                            <img src="<?php echo AYS_QUIZ_ADMIN_URL.'/images/icons/locked_24x24.svg'?>">
                                            <img src="<?php echo AYS_QUIZ_ADMIN_URL.'/images/icons/unlocked_24x24.svg'?>" class="ays-quiz-new-upgrade-button-hover">
                                        </div>
                                        <div class="ays-quiz-new-upgrade-button"><?php echo __("Upgrade", "quiz-maker"); ?></div>
                                    </div>
                                </a>
                            </div>
                        </fieldset><!-- Quiz Start/End Date -->
                        <hr>
                        <fieldset>
                            <legend>
                                <strong style="font-size:30px;"><i class="ays_fa ays_fa_list_alt"></i></strong>
                                <h5><?php echo __('Results settings','quiz-maker'); ?></h5>
                            </legend>
                            <blockquote>
                                <?php echo __( 'All started, but not finished data of quizzes will be stored on the Not finished tab of the Results page.', 'quiz-maker' ); ?>
                            </blockquote>
                            <hr>
                            <div class="form-group row" style="padding:0;margin:0;">
                                <div class="col-sm-12 only_pro" style="padding:20px;">
                                    <div class="pro_features" style="justify-content:flex-end;">

                                    </div>
                                    <div class="form-group row">
                                        <div class="col-sm-4">
                                            <label for="ays_store_all_not_finished_results">
                                                <?php echo __( "Store all not finished results", 'quiz-maker' ); ?>
                                            </label>
                                        </div>
                                        <div class="col-sm-8">
                                            <input type="checkbox" class="ays-checkbox-input" value="on" />
                                        </div>
                                    </div>
                                    <a href="https://ays-pro.com/wordpress/quiz-maker?utm_source=dashboard&utm_medium=quiz-free&utm_campaign=general-settings-not-finished-results-<?php echo esc_attr( AYS_QUIZ_UTM_VERSION ); ?>" target="_blank" class="ays-quiz-new-upgrade-button-link">
                                        <div class="ays-quiz-new-upgrade-button-box">
                                            <div>
                                                <img src="<?php echo AYS_QUIZ_ADMIN_URL.'/images/icons/locked_24x24.svg'?>">
                                                <img src="<?php echo AYS_QUIZ_ADMIN_URL.'/images/icons/unlocked_24x24.svg'?>" class="ays-quiz-new-upgrade-button-hover">
                                            </div>
                                            <div class="ays-quiz-new-upgrade-button"><?php echo __("Upgrade", "quiz-maker"); ?></div>
                                        </div>
                                    </a>
                                </div>
                            </div>
                        </fieldset> <!-- Results settings -->
                        <hr>
                        <fieldset>
                            <legend>
                                <strong style="font-size:30px;"><i class="ays_fa ays_fa_sign_in"></i></strong>
                                <h5><?php echo __('Quiz Login Form Settings','quiz-maker'); ?></h5>
                            </legend>
                            <div class="form-group row" style="padding:0;margin:0;">
                                <div class="col-sm-12 only_pro" style="padding:20px;">
                                    <div class="pro_features" style="justify-content:flex-end;">

                                    </div>
                                    <div class="form-group row ays_toggle_parent">
                                        <div class="col-sm-4">
                                            <label>
                                                <?php echo __( "Enable Login Form Custom Redirection", 'quiz-maker' ); ?>
                                                <a class="ays_help" data-toggle="tooltip" title="<?php echo esc_attr( __('Enable this option to redirect users to your desired Login Form in case of filling an incorrect email address or password.','quiz-maker') ); ?>">
                                                    <i class="ays_fa ays_fa_info_circle"></i>
                                                </a>
                                            </label>
                                        </div>
                                        <div class="col-sm-1">
                                            <input type="checkbox" class="ays-checkbox-input ays-enable-timer1" />
                                        </div>
                                        <div class="col-sm-7 ays_toggle_target ays_divider_left">
                                            <div class="form-group row">
                                                <div class="col-sm-4">
                                                    <label class="form-check-label">
                                                        <?php echo __('Custom login form link', 'quiz-maker'); ?>
                                                        <a class="ays_help" data-toggle="tooltip"
                                                        title="<?php echo __('The URL for redirecting after writing an incorrect email address or password.', 'quiz-maker'); ?>">
                                                            <i class="ays_fa ays_fa_info_circle"></i>
                                                        </a>
                                                    </label>
                                                </div>
                                                <div class="col-sm-8">
                                                    <input type="text" class="ays-enable-timerl ays-text-input">
                                                </div>
                                            </div>
                                            <blockquote>
                                                <?php echo __( 'Note: If you leave the option empty,  the user will stay on the same page in case of a fail.', 'quiz-maker' ); ?>
                                            </blockquote>
                                        </div>
                                    </div>
                                    <a href="https://ays-pro.com/wordpress/quiz-maker?utm_source=dashboard&utm_medium=quiz-free&utm_campaign=custom-login-form-link-<?php echo esc_attr( AYS_QUIZ_UTM_VERSION ); ?>" target="_blank" class="ays-quiz-new-upgrade-button-link">
                                        <div class="ays-quiz-new-upgrade-button-box">
                                            <div>
                                                <img src="<?php echo AYS_QUIZ_ADMIN_URL.'/images/icons/locked_24x24.svg'?>">
                                                <img src="<?php echo AYS_QUIZ_ADMIN_URL.'/images/icons/unlocked_24x24.svg'?>" class="ays-quiz-new-upgrade-button-hover">
                                            </div>
                                            <div class="ays-quiz-new-upgrade-button"><?php echo __("Upgrade", "quiz-maker"); ?></div>
                                        </div>
                                    </a>
                                </div>
                            </div>
                        </fieldset> <!-- Results settings -->
                        <hr>
                        <fieldset>
                            <legend>
                                <strong style="font-size:30px;"><i class="ays_fa ays_fa_globe"></i></strong>
                                <h5><?php echo __('Who will have permission to Quiz menu','quiz-maker')?></h5>
                            </legend>
                            <div class="form-group row" style="padding:0px;margin:0;">
                                <div class="col-sm-12 only_pro" style="padding:20px;">
                                    <div class="pro_features" style="">

                                    </div>
                                    <div class="form-group row">
                                        <div class="col-sm-4">
                                            <label for="ays_user_roles">
                                                <?php echo __( "Select user role for giving access to Quiz menu", 'quiz-maker' ); ?>
                                                <a class="ays_help" data-toggle="tooltip" title="<?php echo __('Give permissions to see only their own quizzes to these user roles.','quiz-maker')?>">
                                                    <i class="ays_fa ays_fa_info_circle"></i>
                                                </a>
                                            </label>
                                        </div>
                                        <div class="col-sm-8 ays-quiz-user-roles">
                                            <select id="ays_user_roles" multiple>
                                                <option selected><?php echo __( "Administrator" , 'quiz-maker'); ?></option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <div class="col-sm-4">
                                            <label>
                                                <?php echo __( "Select user role for giving access to change all Quiz data", 'quiz-maker' ); ?>
                                                <a class="ays_help" data-toggle="tooltip" title="<?php echo __('Give permissions to manage all quizzes and results to these user roles. Please add the given user roles to the above field as well.','quiz-maker')?>">
                                                    <i class="ays_fa ays_fa_info_circle"></i>
                                                </a>
                                            </label>
                                        </div>
                                        <div class="col-sm-8 ays-quiz-user-roles">
                                            <select id="ays_user_roles_to_change_quiz" multiple>
                                                <option selected><?php echo __( "Administrator" , 'quiz-maker'); ?></option>
                                            </select>
                                        </div>
                                    </div>
                                    <blockquote>
                                        <?php echo __( "Control the access of the plugin from the dashboard and manage the capabilities of those user roles.", 'quiz-maker' ); ?>
                                        <br>
                                        <?php echo __( "If you want to give a full control to the given user role, please add the role in both fields.", 'quiz-maker' ); ?>
                                    </blockquote>
                                    <a href="https://ays-pro.com/wordpress/quiz-maker?utm_source=dashboard&utm_medium=quiz-free&utm_campaign=general-settings-user-role-permission-<?php echo esc_attr( AYS_QUIZ_UTM_VERSION ); ?>" target="_blank" class="ays-quiz-new-upgrade-button-link">
                                        <div class="ays-quiz-new-upgrade-button-box">
                                            <div>
                                                <img src="<?php echo AYS_QUIZ_ADMIN_URL.'/images/icons/locked_24x24.svg'?>">
                                                <img src="<?php echo AYS_QUIZ_ADMIN_URL.'/images/icons/unlocked_24x24.svg'?>" class="ays-quiz-new-upgrade-button-hover">
                                            </div>
                                            <div class="ays-quiz-new-upgrade-button"><?php echo __("Upgrade", "quiz-maker"); ?></div>
                                        </div>
                                    </a>
                                </div>
                            </div>
                        </fieldset> <!-- Who will have permission to Quiz menu -->
                        <hr>
                        <fieldset>
                            <legend>
                                <strong style="font-size:30px;"><i class="fa fa-envelope" aria-hidden="true"></i></strong>
                                <h5><?php echo esc_html__("Report Email",'quiz-maker')?></h5>
                            </legend>
                            <div class="col-sm-12 only_pro" style="padding:20px;">
                                <div class="pro_features" style="justify-content:flex-end;">

                                </div>
                                <div class="form-group row">
                                    <div class="col-sm-4">
                                        <label class="form-check-label">
                                            <?php echo esc_html__('Subject', 'quiz-maker'); ?>
                                            <a class="ays_help" data-toggle="tooltip" title="<?php echo esc_html__('Provide the subject of the email that will be sent when someone reports a question.', 'quiz-maker'); ?>">
                                                <i class="ays_fa ays_fa_info_circle"></i>
                                            </a>
                                        </label>
                                    </div>
                                    <div class="col-sm-8">
                                        <input type="text" class="ays-text-input" value="">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <div class="col-sm-4">
                                        <label class="form-check-label">
                                            <?php echo esc_html__('Message', 'quiz-maker'); ?>
                                            <a class="ays_help" data-toggle="tooltip" title="<?php echo esc_html__('Specify the content of the email that is sent when someone reports a question. The message can include the report message, the reported question ID, and any additional information you want to include.', 'quiz-maker'); ?>">
                                                <i class="ays_fa ays_fa_info_circle"></i>
                                            </a>
                                        </label>
                                    </div>
                                    <div class="col-sm-8">
                                        <textarea class="ays-textarea" cols="30" rows="10" style="height: 80px;"></textarea>
                                    </div>
                                </div>
                                
                                <blockquote style="margin-bottom: 20px;">
                                    <?php echo esc_html__('Please note, that these message variables work only for this option.', 'quiz-maker') ?>
                                </blockquote>
                                <p class="vmessage">
                                    <strong>
                                        <input type="text" onClick="this.setSelectionRange(0, this.value.length)" readonly value="%%question_id%%"/>
                                    </strong>
                                    <span> - </span>
                                    <span style="font-size:18px;">
                                        <?php echo esc_html__( "The ID of the reported question", 'quiz-maker'); ?>
                                    </span>
                                </p>
                                <p class="vmessage">
                                    <strong>
                                        <input type="text" onClick="this.setSelectionRange(0, this.value.length)" readonly value="%%question_title%%"/>
                                    </strong>
                                    <span> - </span>
                                    <span style="font-size:18px;">
                                        <?php echo esc_html__( "The reported question Title", 'quiz-maker'); ?>
                                    </span>
                                </p>
                                <p class="vmessage">
                                    <strong>
                                        <input type="text" onClick="this.setSelectionRange(0, this.value.length)" readonly value="%%question%%"/>
                                    </strong>
                                    <span> - </span>
                                    <span style="font-size:18px;">
                                        <?php echo esc_html__( "The reported question", 'quiz-maker'); ?>
                                    </span>
                                </p>
                                <p class="vmessage">
                                    <strong>
                                        <input type="text" onClick="this.setSelectionRange(0, this.value.length)" readonly value="%%quiz_link%%"/>
                                    </strong>
                                    <span> - </span>
                                    <span style="font-size:18px;">
                                        <?php echo esc_html__( "The link of the reported quiz", 'quiz-maker'); ?>
                                    </span>
                                </p>
                                <p class="vmessage">
                                    <strong>
                                        <input type="text" onClick="this.setSelectionRange(0, this.value.length)" readonly value="%%quiz_title_with_link%%"/>
                                    </strong>
                                    <span> - </span>
                                    <span style="font-size:18px;">
                                        <?php echo esc_html__( "The reported quiz title with the link", 'quiz-maker'); ?>
                                    </span>
                                </p>
                                <p class="vmessage">
                                    <strong>
                                        <input type="text" onClick="this.setSelectionRange(0, this.value.length)" readonly value="%%question_link%%"/>
                                    </strong>
                                    <span> - </span>
                                    <span style="font-size:18px;">
                                        <?php echo esc_html__( "The link of the reported question", 'quiz-maker'); ?>
                                    </span>
                                </p>
                                <p class="vmessage">
                                    <strong>
                                        <input type="text" onClick="this.setSelectionRange(0, this.value.length)" readonly value="%%question_title_with_link%%"/>
                                    </strong>
                                    <span> - </span>
                                    <span style="font-size:18px;">
                                        <?php echo esc_html__( "The reported question title with the link", 'quiz-maker'); ?>
                                    </span>
                                </p>
                                <p class="vmessage">
                                    <strong>
                                        <input type="text" onClick="this.setSelectionRange(0, this.value.length)" readonly value="%%report_text%%"/>
                                    </strong>
                                    <span> - </span>
                                    <span style="font-size:18px;">
                                        <?php echo esc_html__( "The report text", 'quiz-maker'); ?>
                                    </span>
                                </p>
                                <p class="vmessage">
                                    <strong>
                                        <input type="text" onClick="this.setSelectionRange(0, this.value.length)" readonly value="%%quiz_author_name%%"/>
                                    </strong>
                                    <span> - </span>
                                    <span style="font-size:18px;">
                                        <?php echo esc_html__( "The quiz author's name", 'quiz-maker'); ?>
                                    </span>
                                </p>
                                <a href="https://ays-pro.com/wordpress/quiz-maker?utm_source=dashboard&utm_medium=quiz-free&utm_campaign=report-email-section-<?php echo esc_attr( AYS_QUIZ_UTM_VERSION ); ?>" target="_blank" class="ays-quiz-new-upgrade-button-link">
                                    <div class="ays-quiz-new-upgrade-button-box">
                                        <div>
                                            <img src="<?php echo AYS_QUIZ_ADMIN_URL.'/images/icons/locked_24x24.svg'?>">
                                            <img src="<?php echo AYS_QUIZ_ADMIN_URL.'/images/icons/unlocked_24x24.svg'?>" class="ays-quiz-new-upgrade-button-hover">
                                        </div>
                                        <div class="ays-quiz-new-upgrade-button"><?php echo __("Upgrade", "quiz-maker"); ?></div>
                                    </div>
                                </a>
                            </div>
                        </fieldset><!-- Report Email -->
                    </div>
                    <div id="tab3" class="ays-quiz-tab-content <?php echo ($ays_quiz_tab == 'tab3') ? 'ays-quiz-tab-content-active' : ''; ?>">
                        <p class="ays-subtitle"><?php echo __('Shortcodes','quiz-maker')?></p>
                        <hr class="ays-quiz-bolder-hr"/>
                        <fieldset>
                            <legend>
                                <strong style="font-size:30px;">[ ]</strong>
                                <h5><?php echo __('All Results Settings','quiz-maker')?></h5>
                            </legend>
                            <div class="form-group row" style="padding:0px;margin:0;">
                                <div class="col-sm-12">
                                    <div class="ays-quiz-heading-box ays-quiz-unset-float ays-quiz-unset-margin">
                                        <div class="ays-quiz-wordpress-user-manual-box ays-quiz-wordpress-text-align">
                                            <a href="https://youtu.be/1G94I4mw4hY" target="_blank">
                                                <?php echo __("How to set All Results Settings Shortcode - video", 'quiz-maker'); ?>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-12" style="padding:20px;">
                                    <div class="form-group row">
                                        <div class="col-sm-4">
                                            <label for="ays_all_results">
                                                <?php echo __( "Shortcode", 'quiz-maker' ); ?>
                                                <a class="ays_help" data-toggle="tooltip" title="<?php echo __('You can copy the shortcode and insert it to any post to show all results.','quiz-maker')?>">
                                                    <i class="ays_fa ays_fa_info_circle"></i>
                                                </a>
                                            </label>
                                        </div>
                                        <div class="col-sm-8">
                                            <input type="text" id="ays_all_results" class="ays-text-input" onclick="this.setSelectionRange(0, this.value.length)" readonly="" value='[ays_all_results id="Your_Category_ID"]'>
                                        </div>
                                    </div>
                                    <hr>
                                    <div class="form-group row">
                                        <div class="col-sm-4">
                                            <label for="ays_all_results_show_publicly">
                                                <?php echo __( "Show to guests too", 'quiz-maker' ); ?>
                                                <a class="ays_help" data-toggle="tooltip" title="<?php echo __('Show the All results table to guests as well. By default, it is displayed only for logged-in users. If this option is disabled, then only the logged-in users will be able to see the table.','quiz-maker')?>">
                                                    <i class="ays_fa ays_fa_info_circle"></i>
                                                </a>
                                            </label>
                                        </div>
                                        <div class="col-sm-8">
                                            <input type="checkbox" class="ays-checkbox-input" id="ays_all_results_show_publicly" name="ays_all_results_show_publicly" value="on" <?php echo $all_results_show_publicly ? 'checked' : ''; ?> />
                                        </div>
                                    </div>
                                    <hr>
                                    <div class="form-group row">
                                        <div class="col-sm-12">
                                            <label>
                                                <?php echo __( "Table columns", 'quiz-maker' ); ?>
                                                <a class="ays_help" data-toggle="tooltip" title="<?php echo __('You can sort table columns and select which columns must display on the front-end.','quiz-maker')?>">
                                                    <i class="ays_fa ays_fa_info_circle"></i>
                                                </a>
                                            </label>
                                            <div class="ays-show-user-page-table-wrap">
                                                <ul class="ays-show-user-page-table">
                                                    <?php
                                                        foreach ($all_results_columns_order as $key => $val) {
                                                            $checked = '';
                                                            if(isset($all_results_columns[$val]) && $all_results_columns[$val] != ''){
                                                                $checked = 'checked';
                                                            }

                                                            if ($val == '') {
                                                               $checked = '';
                                                               $default_leadboard_column_names[$val] = $key;
                                                               $val = $key;
                                                            }

                                                            ?>
                                                            <li class="ays-user-page-option-row ui-state-default">
                                                                <input type="hidden" value="<?php echo $val; ?>" name="ays_all_results_columns_order[]"/>
                                                                <input type="checkbox" id="ays_show_result<?php echo $val; ?>" value="<?php echo $val; ?>" class="ays-checkbox-input" name="ays_all_results_columns[<?php echo $val; ?>]" <?php echo $checked; ?>/>
                                                                <label for="ays_show_result<?php echo $val; ?>">
                                                                    <?php echo $default_all_results_column_names[$val]; ?>
                                                                </label>
                                                            </li>
                                                            <?php
                                                        }
                                                     ?>
                                                </ul>
                                           </div>
                                        </div>
                                    </div>
                                    <hr>
                                    <blockquote>
                                        <ul class="ays-quiz-general-settings-blockquote-ul" style="margin: 0;">
                                            <li style="padding-bottom: 5px;">
                                                <?php
                                                    echo sprintf(
                                                        __( '%s ID %s', 'quiz-maker' ) . ' - ' . esc_attr( __( "Enter the ID of the quiz category. Example: id='23'. Note: In case you don't insert the ID of the Quiz Category, all results of all the quizzes will be displayed on the Front-end.", 'quiz-maker' ) ),
                                                        '<b>',
                                                        '</b>'
                                                    );
                                                ?>
                                            </li>
                                        </ul>
                                    </blockquote>
                                </div>
                            </div>
                        </fieldset> <!-- All Results Settings -->
                        <hr>
                        <fieldset>
                            <legend>
                                <strong style="font-size:30px;">[ ]</strong>
                                <h5><?php echo __('Single Quiz Results Settings','quiz-maker'); ?></h5>
                            </legend>
                            <div class="form-group row" style="padding:0px;margin:0;">
                                <div class="col-sm-12">
                                    <div class="ays-quiz-heading-box ays-quiz-unset-float ays-quiz-unset-margin">
                                        <div class="ays-quiz-wordpress-user-manual-box ays-quiz-wordpress-text-align">
                                            <a href="https://youtu.be/2qksH-vVc4w" target="_blank">
                                                <?php echo __("How to set Single Quiz Results Shortcode - video", 'quiz-maker'); ?>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-12" style="padding:20px;">
                                    <div class="form-group row">
                                        <div class="col-sm-4">
                                            <label for="ays_quiz_all_results">
                                                <?php echo __( "Shortcode", 'quiz-maker' ); ?>
                                                <a class="ays_help" data-toggle="tooltip" title="<?php echo __('You can copy the shortcode and insert it to any post to show quiz all results.','quiz-maker'); ?>">
                                                    <i class="ays_fa ays_fa_info_circle"></i>
                                                </a>
                                            </label>
                                        </div>
                                        <div class="col-sm-8">
                                            <input type="text" id="ays_quiz_all_results" class="ays-text-input" onclick="this.setSelectionRange(0, this.value.length)" readonly="" value='[ays_quiz_all_results id="Your_Quiz_ID"]'>
                                        </div>
                                    </div>
                                    <hr>
                                    <div class="form-group row">
                                        <div class="col-sm-4">
                                            <label for="ays_quiz_all_results_show_publicly">
                                                <?php echo __( "Show to guests too", 'quiz-maker' ); ?>
                                                <a class="ays_help" data-toggle="tooltip" title="<?php echo __('Show the Single quiz results table to guests as well. By default, it is displayed only for logged-in users. If this option is disabled, then only the logged-in users will be able to see the table.','quiz-maker')?>">
                                                    <i class="ays_fa ays_fa_info_circle"></i>
                                                </a>
                                            </label>
                                        </div>
                                        <div class="col-sm-8">
                                            <input type="checkbox" class="ays-checkbox-input" id="ays_quiz_all_results_show_publicly" name="ays_quiz_all_results_show_publicly" value="on" <?php echo $quiz_all_results_show_publicly ? 'checked' : ''; ?> />
                                        </div>
                                    </div>
                                    <hr>
                                    <div class="form-group row">
                                        <div class="col-sm-12">
                                            <label>
                                                <?php echo __( "Table columns", 'quiz-maker' ); ?>
                                                <a class="ays_help" data-toggle="tooltip" title="<?php echo __('You can sort table columns and select which columns must display on the front-end.','quiz-maker'); ?>">
                                                    <i class="ays_fa ays_fa_info_circle"></i>
                                                </a>
                                            </label>
                                            <div class="ays-show-user-page-table-wrap">
                                                <ul class="ays-show-user-page-table">
                                                    <?php
                                                        foreach ($quiz_all_results_columns_order as $key => $val) {
                                                            $checked = '';
                                                            if(isset($quiz_all_results_columns[$val])){
                                                                $checked = 'checked';
                                                            }
                                                            ?>
                                                            <li class="ays-user-page-option-row ui-state-default">
                                                                <input type="hidden" value="<?php echo $val; ?>" name="ays_quiz_all_results_columns_order[]"/>
                                                                <input type="checkbox" id="ays_show_quiz_result<?php echo $val; ?>" value="<?php echo $val; ?>" class="ays-checkbox-input" name="ays_quiz_all_results_columns[<?php echo $val; ?>]" <?php echo $checked; ?>/>
                                                                <label for="ays_show_quiz_result<?php echo $val; ?>">
                                                                    <?php echo $default_quiz_all_results_column_names[$val]; ?>
                                                                </label>
                                                            </li>
                                                            <?php
                                                        }
                                                     ?>
                                                </ul>
                                           </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </fieldset> <!-- Single Quiz Results Settings -->
                        <hr>
                        <fieldset>
                            <legend>
                                <strong style="font-size:30px;">[ ]</strong>
                                <h5><?php echo __('Display Quiz Bank(questions)','quiz-maker'); ?></h5>
                            </legend>
                            <div class="form-group row" style="padding:0px;margin:0;">
                                <div class="col-sm-12">
                                    <div class="ays-quiz-heading-box ays-quiz-unset-float ays-quiz-unset-margin">
                                        <div class="ays-quiz-wordpress-user-manual-box ays-quiz-wordpress-text-align">
                                            <a href="https://youtu.be/iciHKd_nyms" target="_blank">
                                                <?php echo __("How to set Display Quiz Bank Questions Shortcode - video", 'quiz-maker'); ?>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-12" style="padding:20px;">
                                    <div class="form-group row">
                                        <div class="col-sm-4">
                                            <label for="ays_quiz_display_questions">
                                                <?php echo __( "Shortcode", 'quiz-maker' ); ?>
                                                <a class="ays_help" data-toggle="tooltip" title="<?php echo __('Paste the shortcode into any of your posts to show questions of a given quiz. Designed to show questions to students, earlier on, for preparing for the test.','quiz-maker'); ?>">
                                                    <i class="ays_fa ays_fa_info_circle"></i>
                                                </a>
                                            </label>
                                        </div>
                                        <div class="col-sm-8">
                                            <input type="text" id="ays_quiz_display_questions" class="ays-text-input" onclick="this.setSelectionRange(0, this.value.length)" readonly="" value='[ays_display_questions by="quiz/category" id="N" orderby="ASC"]'>
                                        </div>
                                    </div>
                                </div>
                                <hr>
                                <div class="col-sm-12" style="padding:20px;">
                                    <div class="form-group row ays_toggle_parent">
                                        <div class="col-sm-4">
                                            <label for="ays_quiz_enable_question_answers">
                                                <?php echo __( "Enable question answers", 'quiz-maker' ); ?>
                                                <a class="ays_help" data-toggle="tooltip" title="<?php echo __('After enabling this option, the answers of the questions will be displayed in a list on the Front-end.','quiz-maker');?>">
                                                    <i class="ays_fa ays_fa_info_circle"></i>
                                                </a>
                                            </label>
                                        </div>
                                        <div class="col-sm-1">
                                            <input type="checkbox" class="ays-checkbox-input ays_toggle_checkbox" id="ays_quiz_enable_question_answers" name="ays_quiz_enable_question_answers" value="on" <?php echo $quiz_enable_question_answers ? 'checked' : ''; ?> />
                                        </div>
                                        <div class="col-sm-7 ays_toggle_target ays_divider_left <?php echo ($quiz_enable_question_answers) ? '' : 'display_none'; ?>">
                                            <div class="form-group row">
                                                <div class="col-sm-4">
                                                    <label class="form-check-label" for="ays_quiz_show_correct_answers">
                                                        <?php echo __('Show correct answers', 'quiz-maker'); ?>
                                                        <a class="ays_help" data-toggle="tooltip"
                                                        title="<?php echo __('If this option is activated, the correct answers will be in bold on the front-end.', 'quiz-maker'); ?>">
                                                            <i class="ays_fa ays_fa_info_circle"></i>
                                                        </a>
                                                    </label>
                                                </div>
                                                <div class="col-sm-8">
                                                    <input type="checkbox" class="" id="ays_quiz_show_correct_answers" name="ays_quiz_show_correct_answers" value="on" <?php echo $quiz_show_correct_answers ? 'checked' : ''; ?>>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <hr>
                            <blockquote>
                                <ul class="ays-quiz-general-settings-blockquote-ul">
                                    <li>
                                        <?php
                                            echo sprintf(
                                                __( '%s By %s', 'quiz-maker' ) . ' - ' . __( 'Choose the method of filtering. Example: by="category".', 'quiz-maker' ),
                                                '<b>',
                                                '</b>'
                                            );
                                        ?>
                                        <ul class='ays-quiz-general-settings-ul'>
                                            <li>
                                                <?php
                                                    echo sprintf(
                                                        __( '%s quiz %s', 'quiz-maker' ) . ' - ' . __( 'If you set the method as Quiz, it will show all questions added in the given quiz.', 'quiz-maker' ),
                                                        '<b>',
                                                        '</b>'
                                                    );
                                                ?>
                                            </li>
                                            <li>
                                                <?php
                                                    echo sprintf(
                                                        __( '%s category %s', 'quiz-maker' ) . ' - ' . __( 'If you set the method as Category, it will show all questions assigned to the given category.', 'quiz-maker' ),
                                                        '<b>',
                                                        '</b>'
                                                    );
                                                ?>
                                            </li>
                                        </ul>
                                    </li>
                                    <li>
                                        <?php
                                            echo sprintf(
                                                __( '%s ID %s', 'quiz-maker' ) . ' - ' . __( 'Select the ID. Example: id="23".', 'quiz-maker' ),
                                                '<b>',
                                                '</b>'
                                            );
                                        ?>
                                        <ul class='ays-quiz-general-settings-ul'>
                                            <li>
                                                <?php
                                                    echo sprintf(
                                                        __( '%s quiz %s', 'quiz-maker' ) . ' - ' . __( 'If you set the method as Quiz, please enter the ID of the given quiz.', 'quiz-maker' ),
                                                        '<b>',
                                                        '</b>'
                                                    );
                                                ?>
                                            </li>
                                            <li>
                                                <?php
                                                    echo sprintf(
                                                        __( '%s category %s', 'quiz-maker' ) . ' - ' . __( 'If you set the method as Category, please enter the ID of the given category.', 'quiz-maker' ),
                                                        '<b>',
                                                        '</b>'
                                                    );
                                                ?>
                                            </li>
                                        </ul>
                                    </li>
                                    <li>
                                        <?php
                                            echo sprintf(
                                                __( '%s Orderby %s', 'quiz-maker' ) . ' - ' . __( 'Choose the way of ordering the questions. Example: orderby="ASC".', 'quiz-maker' ),
                                                '<b>',
                                                '</b>'
                                            );
                                        ?>
                                        <ul class='ays-quiz-general-settings-ul'>
                                            <li>
                                                <?php
                                                    echo sprintf(
                                                        __( '%s ASC %s', 'quiz-maker' ) . ' - ' . __( 'The earliest created questions will appear at top of the list. The order will be classified based on question ID (oldest to newest).', 'quiz-maker' ),
                                                        '<b>',
                                                        '</b>'
                                                    );
                                                ?>
                                            </li>
                                            <li>
                                                <?php
                                                    echo sprintf(
                                                        __( '%s DESC %s', 'quiz-maker' ) . ' - ' . __( 'The latest created questions will appear at top of the list. The order will be classified based on question ID (newest to oldest).', 'quiz-maker' ),
                                                        '<b>',
                                                        '</b>'
                                                    );
                                                ?>
                                            </li>
                                            <li>
                                                <?php
                                                    echo sprintf(
                                                        __( '%s default %s', 'quiz-maker' ) . ' - ' . __( 'The order will be classified based on the reordering you have done while adding the questions to the quiz. It will work only with the by="quiz" method. The by="category" method will show the same order as orderby="ASC".', 'quiz-maker' ),
                                                        '<b>',
                                                        '</b>'
                                                    );
                                                ?>
                                            </li>
                                            <li>
                                                <?php
                                                    echo sprintf(
                                                        __( '%s random %s', 'quiz-maker' ) . ' - ' . __( 'The questions will be displayed in random order every time the users refresh the page.', 'quiz-maker' ),
                                                        '<b>',
                                                        '</b>'
                                                    );
                                                ?>
                                            </li>
                                        </ul>
                                    </li>
                                </ul>
                            </blockquote>
                        </fieldset> <!-- Display Quiz Bank(questions) -->
                        <hr>
                        <fieldset>
                            <legend>
                                <strong style="font-size:30px;">[ ]</strong>
                                <h5><?php echo __('Quiz categories','quiz-maker'); ?></h5>
                            </legend>
                            <div class="form-group row" style="padding:0px;margin:0;">
                                <div class="col-sm-12">
                                    <div class="ays-quiz-heading-box ays-quiz-unset-float ays-quiz-unset-margin">
                                        <div class="ays-quiz-wordpress-user-manual-box ays-quiz-wordpress-text-align">
                                            <a href="https://youtu.be/bRTNyC-8Byw" target="_blank">
                                                <?php echo __("How to set Quiz Categories shortcode - video", 'quiz-maker'); ?>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-12" style="padding:20px;">
                                    <div class="form-group row">
                                        <div class="col-sm-4">
                                            <label for="ays_quiz_categories">
                                                <?php echo __( "Shortcode", 'quiz-maker' ); ?>
                                                <a class="ays_help" data-toggle="tooltip" title="<?php echo __('Copy the following shortcode, configure it based on your preferences and paste it into the post/page. Put the ID of your preferred category,  choose the method of displaying (all/random) and specify the count of quizzes.','quiz-maker'); ?>">
                                                    <i class="ays_fa ays_fa_info_circle"></i>
                                                </a>
                                            </label>
                                        </div>
                                        <div class="col-sm-8">
                                            <input type="text" id="ays_quiz_categories" class="ays-text-input" onclick="this.setSelectionRange(0, this.value.length)" readonly="" value='[ays_quiz_cat id="Your_Quiz_Category_ID" display="random" count="5" layout="list"]'>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <hr>
                            <blockquote>
                                <ul class="ays-quiz-general-settings-blockquote-ul">
                                    <li style="padding-bottom: 5px;">
                                        <?php
                                            echo sprintf(
                                                __( '%s ID %s', 'quiz-maker' ) . ' - ' . __( 'Enter the ID of the category. Example: id="23".', 'quiz-maker' ),
                                                '<b>',
                                                '</b>'
                                            );
                                        ?>
                                    </li>
                                    <li>
                                        <?php
                                            echo sprintf(
                                                __( '%s Display %s', 'quiz-maker' ) . ' - ' . __( 'Choose the method of displaying. Example: display="random" count="5".', 'quiz-maker' ),
                                                '<b>',
                                                '</b>'
                                            );
                                        ?>
                                        <ul class='ays-quiz-general-settings-ul'>
                                            <li>
                                                <?php
                                                    echo sprintf(
                                                        __( '%s All %s', 'quiz-maker' ) . ' - ' . __( 'If you set the method as All, it will show all quizzes from the given category. In this case, it is not required to fill the %s Count %s attribute. You can either remove it or the system will ignore the value given to it.', 'quiz-maker' ),
                                                        '<b>',
                                                        '</b>',
                                                        '<b>',
                                                        '</b>'
                                                    );
                                                ?>
                                            </li>
                                            <li>
                                                <?php
                                                    echo sprintf(
                                                        __( '%s Random %s', 'quiz-maker' ) . ' - ' . __( 'If you set the method as Random, please give a value to %s Count %s option too, and it will randomly display that given amount of quizzes from the given category.', 'quiz-maker' ),
                                                        '<b>',
                                                        '</b>',
                                                        '<b>',
                                                        '</b>'
                                                    );
                                                ?>
                                            </li>
                                        </ul>
                                    </li>
                                    <li>
                                        <?php
                                            echo sprintf(
                                                __( '%s Layout %s', 'quiz-maker' ) . ' - ' . __( 'Choose the design of the layout. Example:layout=grid.', 'quiz-maker' ),
                                                '<b>',
                                                '</b>'
                                            );
                                        ?>
                                        <ul class='ays-quiz-general-settings-ul'>
                                            <li>
                                                <?php
                                                    echo sprintf(
                                                        __( '%s List %s', 'quiz-maker' ) . ' - ' . __( 'Choose the design of the layout as list․', 'quiz-maker' ),
                                                        '<b>',
                                                        '</b>'
                                                    );
                                                ?>
                                            </li>
                                            <li>
                                                <?php
                                                    echo sprintf(
                                                        __( '%s Grid %s', 'quiz-maker' ) . ' - ' . __( 'Choose the design of the layout as grid․', 'quiz-maker' ),
                                                        '<b>',
                                                        '</b>'
                                                    );
                                                ?>
                                            </li>
                                        </ul>
                                    </li>
                                </ul>
                            </blockquote>
                            <hr>
                            <div class="form-group row" style="padding:0px;margin:0;">
                                <div class="col-sm-12" style="padding:20px;">
                                    <div class="form-group row">
                                        <div class="col-sm-4">
                                            <label for="ays_quiz_cat_title">
                                                <?php echo __( "Shortcode", 'quiz-maker' ); ?>
                                                <a class="ays_help" data-toggle="tooltip" title="<?php echo __('You need to insert Your Quiz Category ID in the shortcode. It will show the category title. If there is no quiz category available/unavailable with that particular Quiz Category ID, the shortcode will stay empty.','quiz-maker'); ?>">
                                                    <i class="ays_fa ays_fa_info_circle"></i>
                                                </a>
                                            </label>
                                        </div>
                                        <div class="col-sm-8">
                                            <input type="text" id="ays_quiz_cat_title" class="ays-text-input" onclick="this.setSelectionRange(0, this.value.length)" readonly="" value='[ays_quiz_cat_title id="Your_Quiz_Category_ID"]'>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <hr>
                            <div class="form-group row" style="padding:0px;margin:0;">
                                <div class="col-sm-12" style="padding:20px;">
                                    <div class="form-group row">
                                        <div class="col-sm-4">
                                            <label for="ays_quiz_cat_description">
                                                <?php echo __( "Shortcode", 'quiz-maker' ); ?>
                                                <a class="ays_help" data-toggle="tooltip" title="<?php echo __('You need to insert Your Quiz Category ID in the shortcode. It will show the category description. If there is no quiz category available/unavailable with that particular Quiz Category ID, the shortcode will stay empty.','quiz-maker'); ?>">
                                                    <i class="ays_fa ays_fa_info_circle"></i>
                                                </a>
                                            </label>
                                        </div>
                                        <div class="col-sm-8">
                                            <input type="text" id="ays_quiz_cat_description" class="ays-text-input" onclick="this.setSelectionRange(0, this.value.length)" readonly="" value='[ays_quiz_cat_description id="Your_Quiz_Category_ID"]'>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </fieldset> <!-- Quiz categories -->
                        <hr/>
                        <fieldset>
                            <legend>
                                <strong style="font-size:30px;">[ ]</strong>
                                <h5><?php echo __('Question categories','quiz-maker'); ?></h5>
                            </legend>
                            <div class="form-group row" style="padding:0px;margin:0;">
                                <div class="col-sm-12">
                                    <div class="ays-quiz-heading-box ays-quiz-unset-float ays-quiz-unset-margin">
                                        <div class="ays-quiz-wordpress-user-manual-box ays-quiz-wordpress-text-align">
                                            <a href="https://youtu.be/bRTNyC-8Byw" target="_blank">
                                                <?php echo __("How to set Question Categories shortcode - video", 'quiz-maker'); ?>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-12" style="padding:20px;">
                                    <div class="form-group row">
                                        <div class="col-sm-4">
                                            <label for="ays_quiz_question_categories_title">
                                                <?php echo __( "Shortcode", 'quiz-maker' ); ?>
                                                <a class="ays_help" data-toggle="tooltip" title="<?php echo __('You need to insert Your Quiz Question Category ID in the shortcode. It will show the category title. If there is no quiz question category available/unavailable with that particular Quiz Question Category ID, the shortcode will stay empty.','quiz-maker'); ?>">
                                                    <i class="ays_fa ays_fa_info_circle"></i>
                                                </a>
                                            </label>
                                        </div>
                                        <div class="col-sm-8">
                                            <input type="text" id="ays_quiz_question_categories_title" class="ays-text-input" onclick="this.setSelectionRange(0, this.value.length)" readonly="" value='[ays_quiz_question_categories_title id="Your_Quiz_Question_Category_ID"]'>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <hr>
                            <div class="form-group row" style="padding:0px;margin:0;">
                                <div class="col-sm-12" style="padding:20px;">
                                    <div class="form-group row">
                                        <div class="col-sm-4">
                                            <label for="ays_quiz_question_categories_description">
                                                <?php echo __( "Shortcode", 'quiz-maker' ); ?>
                                                <a class="ays_help" data-toggle="tooltip" title="<?php echo __('You need to insert Your Quiz Question Category ID in the shortcode. It will show the category description. If there is no quiz question category available/unavailable with that particular Quiz Question Category ID, the shortcode will stay empty.','quiz-maker'); ?>">
                                                    <i class="ays_fa ays_fa_info_circle"></i>
                                                </a>
                                            </label>
                                        </div>
                                        <div class="col-sm-8">
                                            <input type="text" id="ays_quiz_question_categories_description" class="ays-text-input" onclick="this.setSelectionRange(0, this.value.length)" readonly="" value='[ays_quiz_question_categories_description id="Your_Quiz_Question_Category_ID"]'>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </fieldset> <!-- Question categories -->
                        <hr/>
                        <fieldset>
                            <legend>
                                <strong style="font-size:30px;">[ ]</strong>
                                <h5><?php echo __('Most popular quiz','quiz-maker'); ?></h5>
                            </legend>
                            <div class="form-group row" style="padding:0px;margin:0;">
                                <div class="col-sm-12">
                                    <div class="ays-quiz-heading-box ays-quiz-unset-float ays-quiz-unset-margin">
                                        <div class="ays-quiz-wordpress-user-manual-box ays-quiz-wordpress-text-align">
                                            <a href="https://youtu.be/QSJnZSvOqa0" target="_blank">
                                                <?php echo __("How to set the Most Popular Quiz shortcode - video", 'quiz-maker'); ?>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-12" style="padding:20px;">
                                    <div class="form-group row">
                                        <div class="col-sm-4">
                                            <label for="ays_quiz_most_popular">
                                                <?php echo __( "Shortcode", 'quiz-maker' ); ?>
                                                <a class="ays_help" data-toggle="tooltip" title="<?php echo __('Designed to show the most popular quiz that is passed most commonly by users.','quiz-maker'); ?>">
                                                    <i class="ays_fa ays_fa_info_circle"></i>
                                                </a>
                                            </label>
                                        </div>
                                        <div class="col-sm-8">
                                            <input type="text" id="ays_quiz_most_popular" class="ays-text-input" onclick="this.setSelectionRange(0, this.value.length)" readonly="" value='[ays_quiz_most_popular count="1"]'>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </fieldset> <!-- Most popular quiz -->
                        <hr>
                        <fieldset>
                            <legend>
                                <strong style="font-size:30px;">[ ]</strong>
                                <h5><?php echo __('Recent Quizzes Settings', 'quiz-maker'); ?></h5>
                            </legend>
                            <div class="form-group row" style="padding:0px;margin:0;">
                                <div class="col-sm-12">
                                    <div class="ays-quiz-heading-box ays-quiz-unset-float ays-quiz-unset-margin">
                                        <div class="ays-quiz-wordpress-user-manual-box ays-quiz-wordpress-text-align">
                                            <a href="https://youtu.be/Eg7wzKTSsEA" target="_blank">
                                                <?php echo __("How to set Recent Quizzes Settings shortcode - video", 'quiz-maker'); ?>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-12" style="padding:20px;">
                                    <div class="form-group row">
                                        <div class="col-sm-4">
                                            <label for="ays_quiz_recent_quizes">
                                                <?php echo __( "Shortcode", 'quiz-maker' ); ?>
                                                <a class="ays_help" data-toggle="tooltip" data-html="true"
                                                    title="<?php
                                                        echo __('Copy the following shortcode, configure it based on your preferences and paste it into the post.','quiz-maker') .
                                                        "<ul style='list-style-type: circle;padding-left: 20px;'>".
                                                            "<li>". __('Random - If you set the ordering method as random and gave a value to count option, then it will randomly display that given amount of quizzes from your created quizzes.','quiz-maker') ."</li>".
                                                            "<li>". __('Recent - If you set the ordering method as recent and gave a value to count option, then it will display that given amount of quizzes from your recently created quizzes.','quiz-maker') ."</li>".
                                                        "</ul>";
                                                    ?>">
                                                    <i class="ays_fa ays_fa_info_circle"></i>
                                                </a>
                                            </label>
                                        </div>
                                        <div class="col-sm-8">
                                            <input type="text" id="ays_quiz_recent_quizes" class="ays-text-input" onclick="this.setSelectionRange(0, this.value.length)" readonly="" value='[ays_display_quizzes orderby="random/recent" count="5"]'>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </fieldset> <!-- Recent Quizzes Settings -->
                        <hr>
                        <fieldset>
                            <legend style="margin-bottom: 0;">
                                <strong style="font-size:30px;">[ ]</strong>
                                <h5><?php echo __('Individual Leaderboard Settings','quiz-maker')?></h5>
                            </legend>
                            <div class="form-group row" style="padding:0px;margin:0;">
                                <div class="col-sm-12">
                                    <div class="ays-quiz-heading-box ays-quiz-unset-float ays-quiz-unset-margin">
                                        <div class="ays-quiz-wordpress-user-manual-box ays-quiz-wordpress-text-align">
                                            <a href="https://www.youtube.com/watch?v=trZEpGWm9GE" target="_blank">
                                                <?php echo __("How to add leaderboard - video", 'quiz-maker'); ?>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-12 only_pro ays-quiz-margin-top-20" style="padding:20px;">
                                    <div class="pro_features pro_features_popup pro_features_background_bolder">
                                        <div class="pro-features-popup-conteiner">
                                            <div class="pro-features-popup-title">
                                                <?php echo __("How to set Leaderboards with WordPress Quiz Plugin", 'quiz-maker'); ?>
                                            </div>
                                            <div class="pro-features-popup-content" data-link="https://youtu.be/I4rfyzf5D3E">
                                                <p>
                                                    <?php echo sprintf( __("The Quiz Maker plugin gives you the opportunity to add %s advanced Leaderboards %s to your WordPress website. Having Leaderboards on the website helps you %s stimulate competition %s and %s motivate quiz takers %s to improve their skills.", 'quiz-maker'),
                                                        "<strong>",
                                                        "</strong>",
                                                        "<strong>",
                                                        "</strong>",
                                                        "<strong>",
                                                        "</strong>"
                                                    ); ?>
                                                </p>
                                                <p>
                                                    <?php echo __("Follow the steps mentioned in this step-by-step video tutorial to create your own Leaderboard. ", 'quiz-maker'); ?>
                                                </p>
                                                <div>
                                                    <a href="https://quiz-plugin.com/individual-leaderboard" target="_blank"><?php echo __("See Demo", 'quiz-maker'); ?></a>
                                                </div>
                                            </div>
                                            <div class="pro-features-popup-button" data-link="https://ays-pro.com/wordpress/quiz-maker?utm_source=dashboard&utm_medium=quiz-free&utm_campaign=pro-popup-individual-leaderboard-shortcode-<?php echo esc_attr( AYS_QUIZ_UTM_VERSION ); ?>">
                                                <?php echo __("Pricing", 'quiz-maker'); ?>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <div class="col-sm-4">
                                            <label for="ays_invidLead">
                                                <?php echo __( "Shortcode", 'quiz-maker' ); ?>
                                            </label>
                                        </div>
                                        <div class="col-sm-8">
                                            <input type="text" id="ays_invidLead" class="ays-text-input" onclick="this.setSelectionRange(0, this.value.length)" readonly="" value='[ays_quiz_leaderboard id="Your_Quiz_ID" from="Y-m-d H:i:s" to="Y-m-d H:i:s"]'>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <div class="col-sm-4">
                                            <label for="ays_leadboard_count">
                                                <?php echo __('Users count','quiz-maker')?>
                                            </label>
                                        </div>
                                        <div class="col-sm-8">
                                            <input type="text"
                                                class="ays-text-input"                 
                                                id="ays_leadboard_count"
                                                value="5"
                                            />
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <div class="col-sm-4">
                                            <label for="ays_leadboard_width">
                                                <?php echo __('Width','quiz-maker')?>
                                            </label>
                                        </div>
                                        <div class="col-sm-8">
                                            <input type="text"
                                                class="ays-text-input"                 
                                                id="ays_leadboard_width"
                                                value="500"
                                            />
                                            <span style="display:block;" class="ays_quiz_small_hint_text"><?php echo __("For 100% leave blank", 'quiz-maker');?></span>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <div class="col-sm-4">
                                            <label>
                                                <?php echo __('Group users by','quiz-maker')?>
                                            </label>
                                        </div>
                                        <div class="col-sm-8">
                                            <label class="ays_quiz_loader">
                                                <input type="radio" value="id" checked/>
                                                <span><?php echo esc_html__( "ID", 'quiz-maker'); ?></span>
                                            </label>
                                            <label class="ays_quiz_loader">
                                                <input type="radio" value="email"/>
                                                <span><?php echo esc_html__( "Email", 'quiz-maker'); ?></span>
                                            </label>
                                            <label class="ays_quiz_loader">
                                                <input type="radio" value="no_grouping"/>
                                                <span><?php echo esc_html__( "No grouping", 'quiz-maker'); ?></span>
                                            </label>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <div class="col-sm-4">
                                            <label>
                                                <?php echo __('Show user’s result','quiz-maker')?>
                                            </label>
                                        </div>
                                        <div class="col-sm-8">
                                            <label class="ays_quiz_loader">
                                                <input type="radio" value="avg" checked/>
                                                <span><?php echo esc_html__( "AVG", 'quiz-maker'); ?></span>
                                            </label>
                                            <label class="ays_quiz_loader">
                                                <input type="radio" value="max"/>
                                                <span><?php echo esc_html__( "MAX", 'quiz-maker'); ?></span>
                                            </label>
                                            <label class="ays_quiz_loader">
                                                <input type="radio" value="first" />
                                                <span><?php echo esc_html__( "First", 'quiz-maker'); ?></span>
                                            </label>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <div class="col-sm-4">
                                            <label>
                                                <?php echo __('Show points','quiz-maker')?>
                                                <a class="ays_help" data-toggle="tooltip" title="<?php echo __('Decide how to display the score. For instance, if you choose the correct answer count, the score will be shown in this format: 8/10.','quiz-maker'); ?>">
                                                    <!-- <i class="ays_fa ays_fa_info_circle"></i> -->
                                                </a>
                                            </label>
                                        </div>
                                        <div class="col-sm-8">
                                            <label class="ays_quiz_loader">
                                                <input type="radio" checked />
                                                <span><?php echo __( "Without maximum point", 'quiz-maker'); ?></span>
                                            </label>
                                            <label class="ays_quiz_loader">
                                                <input type="radio" />
                                                <span><?php echo __( "With maximum point", 'quiz-maker'); ?></span>
                                            </label>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <div class="col-sm-4">
                                            <label>
                                                <?php echo __( "Enable pagination", 'quiz-maker' ); ?>
                                                <a class="ays_help" data-toggle="tooltip" title="<?php echo __('When this option is enabled, the data on the leaderboard will be displayed with pages. You can sort the data by leaderboard columns.','quiz-maker'); ?>">
                                                    <!-- <i class="ays_fa ays_fa_info_circle"></i> -->
                                                </a>
                                            </label>
                                        </div>
                                        <div class="col-sm-8">
                                            <input type="checkbox" class="ays-checkbox-input" value="on" >
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <div class="col-sm-4">
                                            <label>
                                                <?php echo __( "Enable User Avatar", 'quiz-maker' ); ?>
                                                <a class="ays_help" data-toggle="tooltip" title="<?php echo __('By enabling this option, you can display the user avatar on the Front-end. Note: The Name field (Information Form option) must be enabled so that this option can work for you. If the Name table column is disabled, but the User Avatar option is enabled, the avatar will not be displayed on the front end. The user avatar will be displayed next to the name of the user.','quiz-maker'); ?>">
                                                    <i class="ays_fa ays_fa_info_circle"></i>
                                                </a>
                                            </label>
                                        </div>
                                        <div class="col-sm-8">
                                            <input type="checkbox" class="ays-checkbox-input">
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <div class="col-sm-4">
                                            <label for="ays_leadboard_color">
                                                <?php echo __('Color','quiz-maker')?>
                                            </label>
                                        </div>
                                        <div class="col-sm-8">
                                            <input type="text" id="ays_leadboard_color" data-alpha="true" value="#99BB5A" />
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <div class="col-sm-4">
                                            <label for="ays_leadboard_custom_css">
                                                <?php echo __('Custom CSS','quiz-maker')?>
                                                <a class="ays_help" data-toggle="tooltip" title="<?php echo __('Field for entering your own CSS code','quiz-maker')?>">
                                                    <i class="ays_fa ays_fa_info_circle_test"></i>
                                                </a>
                                            </label>
                                        </div>
                                        <div class="col-sm-8">
                                            <textarea class="ays-textarea" id="ays_leadboard_custom_css" cols="30" rows="10" style="height: 80px;"></textarea>
                                        </div>
                                    </div> <!-- Custom leadboard CSS -->
                                    <hr>
                                    <div class="form-group row">
                                        <div class="col-sm-12">
                                            <label>
                                                <?php echo __( "Leaderboard Columns", 'quiz-maker' ); ?>
                                                <a class="ays_help" data-toggle="tooltip" title="<?php echo __('You can sort table columns and select which columns must display on the front-end.','quiz-maker')?>">
                                                    <i class="ays_fa ays_fa_info_circle"></i>
                                                </a>
                                            </label>
                                            <div class="ays-show-user-page-table-wrap">
                                                <ul class="ays-show-user-page-table">
                                                    <?php    
                                                        foreach ($default_leadboard_column_names as $key => $val) {
                                                            ?>
                                                            <li class="ays-user-page-option-row ui-state-default">
                                                                <input type="hidden" value="<?php echo $val; ?>" />
                                                                <input type="checkbox" id="ays_show_ind<?php echo $val; ?>" value="<?php echo $val; ?>" class="ays-checkbox-input" checked/>
                                                                <label for="ays_show_ind<?php echo $val; ?>">
                                                                    <?php echo $val; ?>
                                                                </label>
                                                            </li>
                                                            <?php
                                                        }
                                                     ?>
                                                </ul>
                                           </div>
                                        </div>
                                    </div>
                                    <a href="https://ays-pro.com/wordpress/quiz-maker?utm_source=dashboard&utm_medium=quiz-free&utm_campaign=pro-popup-individual-leaderboard-shortcode-<?php echo esc_attr( AYS_QUIZ_UTM_VERSION ); ?>" target="_blank" class="ays-quiz-new-upgrade-button-link">
                                        <div class="ays-quiz-new-upgrade-button-box">
                                            <div>
                                                <img src="<?php echo AYS_QUIZ_ADMIN_URL.'/images/icons/locked_24x24.svg'?>">
                                                <img src="<?php echo AYS_QUIZ_ADMIN_URL.'/images/icons/unlocked_24x24.svg'?>" class="ays-quiz-new-upgrade-button-hover">
                                            </div>
                                            <div class="ays-quiz-new-upgrade-button"><?php echo __("Upgrade", "quiz-maker"); ?></div>
                                        </div>
                                    </a>
                                    <div class="ays-quiz-new-watch-video-button-box">
                                        <div>
                                            <img src="<?php echo AYS_QUIZ_ADMIN_URL.'/images/icons/video_24x24.svg'?>">
                                            <img src="<?php echo AYS_QUIZ_ADMIN_URL.'/images/icons/video_24x24_hover.svg'?>" class="ays-quiz-new-watch-video-button-hover">
                                        </div>
                                        <div class="ays-quiz-new-watch-video-button"><?php echo __("Watch Video", "quiz-maker"); ?></div>
                                    </div>
                                    <div class="ays-quiz-center-big-main-button-box ays-quiz-new-big-button-flex">
                                        <div class="ays-quiz-center-big-watch-video-button-box ays-quiz-big-upgrade-margin-right-10">
                                            <div class="ays-quiz-center-new-watch-video-demo-button">
                                                <img src="<?php echo AYS_QUIZ_ADMIN_URL.'/images/icons/video_24x24.svg'?>" class="ays-quiz-new-button-img-hide">
                                                <img src="<?php echo AYS_QUIZ_ADMIN_URL.'/images/icons/video_24x24_hover.svg'?>" class="ays-quiz-new-watch-video-button-hover">
                                                <?php echo __("Watch Video", "quiz-maker"); ?>
                                            </div>
                                        </div>
                                        <div class="ays-quiz-center-big-upgrade-button-box">
                                            <a href="https://ays-pro.com/wordpress/quiz-maker?utm_source=dashboard&utm_medium=quiz-free&utm_campaign=pro-popup-individual-leaderboard-shortcode-<?php echo esc_attr( AYS_QUIZ_UTM_VERSION ); ?>" target="_blank" class="ays-quiz-new-upgrade-button-link">
                                                <div class="ays-quiz-center-new-big-upgrade-button">
                                                    <img src="<?php echo AYS_QUIZ_ADMIN_URL.'/images/icons/locked_24x24.svg'?>" class="ays-quiz-new-button-img-hide">
                                                    <img src="<?php echo AYS_QUIZ_ADMIN_URL.'/images/icons/unlocked_24x24.svg'?>" class="ays-quiz-new-upgrade-button-hover">  
                                                    <?php echo __("Upgrade", "quiz-maker"); ?>
                                                </div>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </fieldset> <!-- Individual Leaderboard Settings -->
                        <hr>
                        <fieldset>
                            <legend style="margin-bottom: 0;">
                                <strong style="font-size:30px;">[ ]</strong>
                                <h5 class="ays-subtitle"><?php echo __('Global Leaderboard Settings','quiz-maker')?></h5>
                            </legend>
                            <div class="form-group row" style="padding:0px;margin:0;">
                                <div class="col-sm-12">
                                    <div class="ays-quiz-heading-box ays-quiz-unset-float ays-quiz-unset-margin">
                                        <div class="ays-quiz-wordpress-user-manual-box ays-quiz-wordpress-text-align">
                                            <a href="https://www.youtube.com/watch?v=trZEpGWm9GE" target="_blank">
                                                <?php echo __("How to add leaderboard - video", 'quiz-maker'); ?>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-12 only_pro ays-quiz-margin-top-20" style="padding:20px;">
                                    <div class="pro_features pro_features_popup pro_features_background_bolder">
                                        <div class="pro-features-popup-conteiner">
                                            <div class="pro-features-popup-title">
                                                <?php echo __("How to set Leaderboards with WordPress Quiz Plugin", 'quiz-maker'); ?>
                                            </div>
                                            <div class="pro-features-popup-content" data-link="https://youtu.be/I4rfyzf5D3E">
                                                <p>
                                                    <?php echo sprintf( __("The Quiz Maker plugin gives you the opportunity to add %s advanced Leaderboards %s to your WordPress website. Having Leaderboards on the website helps you %s stimulate competition %s and %s motivate quiz takers %s to improve their skills.", 'quiz-maker'),
                                                        "<strong>",
                                                        "</strong>",
                                                        "<strong>",
                                                        "</strong>",
                                                        "<strong>",
                                                        "</strong>"
                                                    ); ?>
                                                </p>
                                                <p>
                                                    <?php echo __("Follow the steps mentioned in this step-by-step video tutorial to create your own Leaderboard. ", 'quiz-maker'); ?>
                                                </p>
                                                <div>
                                                    <a href="https://quiz-plugin.com/global-leaderboard-2/" target="_blank"><?php echo __("See Demo", 'quiz-maker'); ?></a>
                                                </div>
                                            </div>
                                            <div class="pro-features-popup-button" data-link="https://ays-pro.com/wordpress/quiz-maker?utm_source=dashboard&utm_medium=quiz-free&utm_campaign=pro-popup-global-leaderboard-shortcode-<?php echo esc_attr( AYS_QUIZ_UTM_VERSION ); ?>">
                                                <?php echo __("Pricing", 'quiz-maker'); ?>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <div class="col-sm-4">
                                            <label for="ays_globLead">
                                                <?php echo __( "Shortcode", 'quiz-maker' ); ?>
                                            </label>
                                        </div>
                                        <div class="col-sm-8">
                                            <input type="text" id="ays_globLead" class="ays-text-input" onclick="this.setSelectionRange(0, this.value.length)" readonly="" value='[ays_quiz_gleaderboard from="Y-m-d H:i:s" to="Y-m-d H:i:s"]'>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <div class="col-sm-4">
                                            <label for="ays_gleadboard_count">
                                                <?php echo __('Users count','quiz-maker')?>
                                            </label>
                                        </div>
                                        <div class="col-sm-8">
                                            <input type="text"
                                                class="ays-text-input"                 
                                                id="ays_gleadboard_count"
                                                value="10"
                                            />
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <div class="col-sm-4">
                                            <label for="ays_gleadboard_width">
                                                <?php echo __('Width','quiz-maker')?>
                                            </label>
                                        </div>
                                        <div class="col-sm-8">
                                            <input type="text"
                                                class="ays-text-input"                 
                                                id="ays_gleadboard_width"
                                                value="600"
                                            />
                                            <span style="display:block;" class="ays_quiz_small_hint_text"><?php echo __("For 100% leave blank", 'quiz-maker');?></span>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <div class="col-sm-4">
                                            <label>
                                                <?php echo __('Users order by','quiz-maker')?>
                                            </label>
                                        </div>
                                        <div class="col-sm-8">
                                            <label class="ays_quiz_loader">
                                                <input type="radio" value="id"/>
                                                <span><?php echo __( "ID", 'quiz-maker'); ?></span>
                                            </label>
                                            <label class="ays_quiz_loader">
                                                <input type="radio" value="email" checked/>
                                                <span><?php echo __( "Email", 'quiz-maker'); ?></span>
                                            </label>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <div class="col-sm-4">
                                            <label>
                                                <?php echo __('Show user’s result','quiz-maker')?>
                                            </label>
                                        </div>
                                        <div class="col-sm-8">
                                            <label class="ays_quiz_loader">
                                                <input type="radio" value="avg"/>
                                                <span><?php echo __( "AVG", 'quiz-maker'); ?></span>
                                            </label>
                                            <label class="ays_quiz_loader">
                                                <input type="radio" value="max" checked/>
                                                <span><?php echo __( "MAX", 'quiz-maker'); ?></span>
                                            </label>
                                            <label class="ays_quiz_loader">
                                                <input type="radio" value="sum"/>
                                                <span><?php echo __( "SUM", 'quiz-maker'); ?></span>
                                            </label>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <div class="col-sm-4">
                                            <label>
                                                <?php echo __( "Enable pagination", 'quiz-maker' ); ?>
                                                <a class="ays_help" data-toggle="tooltip" title="<?php echo __('When this option is enabled, the data on the leaderboard will be displayed with pages. You can sort the data by leaderboard columns.','quiz-maker'); ?>">
                                                    <!-- <i class="ays_fa ays_fa_info_circle"></i> -->
                                                </a>
                                            </label>
                                        </div>
                                        <div class="col-sm-8">
                                            <input type="checkbox" class="ays-checkbox-input" value="on" >
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <div class="col-sm-4">
                                            <label>
                                                <?php echo __( "Enable User Avatar", 'quiz-maker' ); ?>
                                                <a class="ays_help" data-toggle="tooltip" title="<?php echo __('By enabling this option, you can display the user avatar on the Front-end. Note: The Name field (Information Form option) must be enabled so that this option can work for you. If the Name table column is disabled, but the User Avatar option is enabled, the avatar will not be displayed on the front end. The user avatar will be displayed next to the name of the user.','quiz-maker'); ?>">
                                                    <i class="ays_fa ays_fa_info_circle"></i>
                                                </a>
                                            </label>
                                        </div>
                                        <div class="col-sm-8">
                                            <input type="checkbox" class="ays-checkbox-input">
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <div class="col-sm-4">
                                            <label for="ays_gleadboard_color">
                                                <?php echo __('Color','quiz-maker')?>
                                            </label>
                                        </div>
                                        <div class="col-sm-8">
                                            <input type="text" id="ays_gleadboard_color" data-alpha="true" value="#99BB5A" />
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <div class="col-sm-4">
                                            <label for="ays_gleadboard_custom_css">
                                                <?php echo __('Custom CSS','quiz-maker')?>
                                                <a class="ays_help" data-toggle="tooltip" title="<?php echo __('Field for entering your own CSS code','quiz-maker')?>">
                                                    <i class="ays_fa ays_fa_info_circle_aa"></i>
                                                </a>
                                            </label>
                                        </div>
                                        <div class="col-sm-8">
                                            <textarea class="ays-textarea" id="ays_gleadboard_custom_css" cols="30"
                                                  rows="10" style="height: 80px;"></textarea>
                                        </div>
                                    </div> <!-- Custom global leadboard CSS -->
                                    <hr>
                                    <div class="form-group row">
                                        <div class="col-sm-12">
                                            <label>
                                                <?php echo __( "Leaderboard Columns", 'quiz-maker' ); ?>
                                                <a class="ays_help" data-toggle="tooltip" title="<?php echo __('You can sort table columns and select which columns must display on the front-end.','quiz-maker')?>">
                                                    <i class="ays_fa ays_fa_info_circle"></i>
                                                </a>
                                            </label>
                                            <div class="ays-show-user-page-table-wrap">
                                                <ul class="ays-show-user-page-table">
                                                    <?php    
                                                        foreach ($default_leadboard_column_names as $key => $val) {
                                                            if($key == 'admin_note'){
                                                                continue;
                                                            }
                                                            ?>
                                                            <li class="ays-user-page-option-row ui-state-default">
                                                                <input type="hidden" value="<?php echo $val; ?>" />
                                                                <input type="checkbox" id="ays_show_gl<?php echo $val; ?>" value="<?php echo $val; ?>" class="ays-checkbox-input" checked/>
                                                                <label for="ays_show_gl<?php echo $val; ?>">
                                                                    <?php echo $val; ?>
                                                                </label>
                                                            </li>
                                                            <?php
                                                        }
                                                     ?>
                                                </ul>
                                           </div>
                                        </div>
                                    </div>
                                    <a href="https://ays-pro.com/wordpress/quiz-maker?utm_source=dashboard&utm_medium=quiz-free&utm_campaign=pro-popup-global-leaderboard-shortcode-<?php echo esc_attr( AYS_QUIZ_UTM_VERSION ); ?>" target="_blank" class="ays-quiz-new-upgrade-button-link">
                                        <div class="ays-quiz-new-upgrade-button-box">
                                            <div>
                                                <img src="<?php echo AYS_QUIZ_ADMIN_URL.'/images/icons/locked_24x24.svg'?>">
                                                <img src="<?php echo AYS_QUIZ_ADMIN_URL.'/images/icons/unlocked_24x24.svg'?>" class="ays-quiz-new-upgrade-button-hover">
                                            </div>
                                            <div class="ays-quiz-new-upgrade-button"><?php echo __("Upgrade", "quiz-maker"); ?></div>
                                        </div>
                                    </a>
                                    <div class="ays-quiz-new-watch-video-button-box">
                                        <div>
                                            <img src="<?php echo AYS_QUIZ_ADMIN_URL.'/images/icons/video_24x24.svg'?>">
                                            <img src="<?php echo AYS_QUIZ_ADMIN_URL.'/images/icons/video_24x24_hover.svg'?>" class="ays-quiz-new-watch-video-button-hover">
                                        </div>
                                        <div class="ays-quiz-new-watch-video-button"><?php echo __("Watch Video", "quiz-maker"); ?></div>
                                    </div>
                                    <div class="ays-quiz-center-big-main-button-box ays-quiz-new-big-button-flex">
                                        <div class="ays-quiz-center-big-watch-video-button-box ays-quiz-big-upgrade-margin-right-10">
                                            <div class="ays-quiz-center-new-watch-video-demo-button">
                                                <img src="<?php echo AYS_QUIZ_ADMIN_URL.'/images/icons/video_24x24.svg'?>" class="ays-quiz-new-button-img-hide">
                                                <img src="<?php echo AYS_QUIZ_ADMIN_URL.'/images/icons/video_24x24_hover.svg'?>" class="ays-quiz-new-watch-video-button-hover">
                                                <?php echo __("Watch Video", "quiz-maker"); ?>
                                            </div>
                                        </div>
                                        <div class="ays-quiz-center-big-upgrade-button-box">
                                            <a href="https://ays-pro.com/wordpress/quiz-maker?utm_source=dashboard&utm_medium=quiz-free&utm_campaign=pro-popup-global-leaderboard-shortcode-<?php echo esc_attr( AYS_QUIZ_UTM_VERSION ); ?>" target="_blank" class="ays-quiz-new-upgrade-button-link">
                                                <div class="ays-quiz-center-new-big-upgrade-button">
                                                    <img src="<?php echo AYS_QUIZ_ADMIN_URL.'/images/icons/locked_24x24.svg'?>" class="ays-quiz-new-button-img-hide">
                                                    <img src="<?php echo AYS_QUIZ_ADMIN_URL.'/images/icons/unlocked_24x24.svg'?>" class="ays-quiz-new-upgrade-button-hover">  
                                                    <?php echo __("Upgrade", "quiz-maker"); ?>
                                                </div>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </fieldset> <!-- Global Leaderboard Settings -->
                        <hr>
                        <fieldset>
                            <legend>
                                <strong style="font-size:30px;">[ ]</strong>
                                <h5 class="ays-subtitle"><?php echo __('Leaderboard By Quiz Category Settings','quiz-maker')?></h5>
                            </legend>
                            <blockquote>
                                <?php echo __( "It is designed for a particular quiz category results.", 'quiz-maker' ); ?>
                            </blockquote>
                            <hr>
                            <div class="col-sm-12 only_pro ays-quiz-margin-top-20" style="padding:20px;">
                                <div class="pro_features pro_features_popup pro_features_background_bolder">
                                    <div class="pro-features-popup-conteiner">
                                        <div class="pro-features-popup-title">
                                            <?php echo __("How to set Leaderboards with WordPress Quiz Plugin", 'quiz-maker'); ?>
                                        </div>
                                        <div class="pro-features-popup-content" data-link="https://youtu.be/I4rfyzf5D3E">
                                            <p>
                                                <?php echo sprintf( __("The Quiz Maker plugin gives you the opportunity to add %s advanced Leaderboards %s to your WordPress website. Having Leaderboards on the website helps you %s stimulate competition %s and %s motivate quiz takers %s to improve their skills.", 'quiz-maker'),
                                                    "<strong>",
                                                    "</strong>",
                                                    "<strong>",
                                                    "</strong>",
                                                    "<strong>",
                                                    "</strong>"
                                                ); ?>
                                            </p>
                                            <p>
                                                <?php echo __("Follow the steps mentioned in this step-by-step video tutorial to create your own Leaderboard. ", 'quiz-maker'); ?>
                                            </p>
                                            <div>
                                                <a href="https://quiz-plugin.com/leaderboard-by-quiz-category" target="_blank"><?php echo __("See Demo", 'quiz-maker'); ?></a>
                                            </div>
                                        </div>
                                        <div class="pro-features-popup-button" data-link="https://ays-pro.com/wordpress/quiz-maker?utm_source=dashboard&utm_medium=quiz-free&utm_campaign=pro-popup-quiz-category-leaderboard-shortcode-<?php echo esc_attr( AYS_QUIZ_UTM_VERSION ); ?>">
                                            <?php echo __("Pricing", 'quiz-maker'); ?>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <div class="col-sm-4">
                                        <label for="ays_globLead_cat">
                                            <?php echo __( "Shortcode", 'quiz-maker' ); ?>
                                            <a class="ays_help" data-toggle="tooltip" title="<?php echo __('You can copy the shortcode and paste it to any post/page to see the list of the top user’s who passed any quiz.','quiz-maker')?>">
                                                <i class="ays_fa ays_fa_info_circle"></i>
                                            </a>
                                        </label>
                                    </div>
                                    <div class="col-sm-8">
                                        <input type="text" id="ays_globLead_cat" class="ays-text-input" onclick="this.setSelectionRange(0, this.value.length)" readonly="" value='[ays_quiz_cat_gleaderboard id="Your_Quiz_Category_ID" from="Y-m-d H:i:s" to="Y-m-d H:i:s"]'>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <div class="col-sm-4">
                                        <label for="ays_gleadboard_quiz_cat_count">
                                            <?php echo __('Users count','quiz-maker')?>
                                            <a class="ays_help" data-toggle="tooltip" title="<?php echo __('How many users’ results will be shown in the leaderboard.','quiz-maker')?>">
                                                <i class="ays_fa ays_fa_info_circle"></i>
                                            </a>
                                        </label>
                                    </div>
                                    <div class="col-sm-8">
                                        <input type="number" class="ays-text-input" id="ays_gleadboard_quiz_cat_count" value="5"
                                        />
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <div class="col-sm-4">
                                        <label for="ays_gleadboard_quiz_cat_width">
                                            <?php echo __('Width','quiz-maker')?>
                                            <a class="ays_help" data-toggle="tooltip" title="<?php echo __('The width of the Leaderboard box. It accepts only numeric values. For 100% leave it blank.','quiz-maker')?>">
                                                <i class="ays_fa ays_fa_info_circle"></i>
                                            </a>
                                        </label>
                                    </div>
                                    <div class="col-sm-8">
                                        <input type="number" class="ays-text-input" id="ays_gleadboard_quiz_cat_width" value="500"
                                        />
                                        <span style="display:block;" class="ays_quiz_small_hint_text"><?php echo __("For 100% leave blank", 'quiz-maker');?></span>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <div class="col-sm-4">
                                        <label>
                                            <?php echo __('Group users by','quiz-maker');?>
                                            <a class="ays_help" data-toggle="tooltip" title="<?php echo __('Select the way for grouping the results. If you want to make Leaderboard for logged in users, then choose ID. It will collect results by WP user ID. If you want to make Leaderboard for guests, then you need to choose Email and enable Information Form and Email, Name options from quiz settings. It will group results by emails and display guests Names.','quiz-maker'); ?>">
                                                <i class="ays_fa ays_fa_info_circle"></i>
                                            </a>
                                        </label>
                                    </div>
                                    <div class="col-sm-8">
                                        <label class="ays_quiz_loader">
                                            <input type="radio" checked />
                                            <span><?php echo __( "ID", 'quiz-maker'); ?></span>
                                        </label>
                                        <label class="ays_quiz_loader">
                                            <input type="radio" />
                                            <span><?php echo __( "Email", 'quiz-maker'); ?></span>
                                        </label>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <div class="col-sm-4">
                                        <label>
                                            <?php echo __('Show user’s result','quiz-maker');?>
                                            <a class="ays_help" data-toggle="tooltip" title="<?php echo __('Show the users’ Average, Maximum or Sum results in the leaderboard. SUM does not work with Score(table column)','quiz-maker');?>">
                                                <i class="ays_fa ays_fa_info_circle"></i>
                                            </a>
                                        </label>
                                    </div>
                                    <div class="col-sm-8">
                                        <label class="ays_quiz_loader">
                                            <input type="radio" checked />
                                            <span><?php echo __( "AVG", 'quiz-maker'); ?></span>
                                        </label>
                                        <label class="ays_quiz_loader">
                                            <input type="radio" />
                                            <span><?php echo __( "MAX", 'quiz-maker'); ?></span>
                                        </label>
                                        <label class="ays_quiz_loader">
                                            <input type="radio" />
                                            <span><?php echo __( "SUM", 'quiz-maker'); ?></span>
                                        </label>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <div class="col-sm-4">
                                        <label>
                                            <?php echo __( "Enable pagination", 'quiz-maker' ); ?>
                                            <a class="ays_help" data-toggle="tooltip" title="<?php echo __('When this option is enabled, the data on the leaderboard will be displayed with pages. You can sort the data by leaderboard columns.','quiz-maker'); ?>">
                                                <i class="ays_fa ays_fa_info_circle"></i>
                                            </a>
                                        </label>
                                    </div>
                                    <div class="col-sm-8">
                                        <input type="checkbox" class="ays-checkbox-input" value="on" >
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <div class="col-sm-4">
                                        <label>
                                            <?php echo __( "Enable User Avatar", 'quiz-maker' ); ?>
                                            <a class="ays_help" data-toggle="tooltip" title="<?php echo __('By enabling this option, you can display the user avatar on the Front-end. Note: The Name field (Information Form option) must be enabled so that this option can work for you. If the Name table column is disabled, but the User Avatar option is enabled, the avatar will not be displayed on the front end. The user avatar will be displayed next to the name of the user.','quiz-maker'); ?>">
                                                <i class="ays_fa ays_fa_info_circle"></i>
                                            </a>
                                        </label>
                                    </div>
                                    <div class="col-sm-8">
                                        <input type="checkbox" class="ays-checkbox-input">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <div class="col-sm-4">
                                        <label for="ays_gleadboard_quiz_cat_color">
                                            <?php echo __('Color','quiz-maker');?>
                                            <a class="ays_help" data-toggle="tooltip" title="<?php echo __('Top color of the leaderboard','quiz-maker');?>">
                                                <i class="ays_fa ays_fa_info_circle"></i>
                                            </a>
                                        </label>
                                    </div>
                                    <div class="col-sm-8">
                                        <input type="text" id="ays_gleadboard_quiz_cat_color" data-alpha="true" value="#99BB5A" />
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <div class="col-sm-4">
                                        <label for="ays_gleadboard_quiz_cat_custom_css">
                                            <?php echo __('Custom CSS','quiz-maker');?>
                                            <a class="ays_help" data-toggle="tooltip" title="<?php echo __('Field for entering your own CSS code','quiz-maker');?>">
                                                <i class="ays_fa ays_fa_info_circle"></i>
                                            </a>
                                        </label>
                                    </div>
                                    <div class="col-sm-8">
                                        <textarea class="ays-textarea" id="ays_gleadboard_quiz_cat_custom_css" cols="30"
                                              rows="10" style="height: 80px;"></textarea>
                                    </div>
                                </div> <!-- Custom global leadboard CSS -->
                                <hr>
                                <div class="form-group row">
                                    <div class="col-sm-12">
                                        <label>
                                            <?php echo __( "Leaderboard Columns", 'quiz-maker' ); ?>
                                            <a class="ays_help" data-toggle="tooltip" title="<?php echo __('You can sort table columns and select which columns must display on the front-end.','quiz-maker');?>">
                                                <i class="ays_fa ays_fa_info_circle"></i>
                                            </a>
                                        </label>
                                        <div class="ays-show-user-page-table-wrap">
                                            <ul class="ays-show-user-page-table">
                                                <?php
                                                    foreach ($default_leadboard_column_names as $key => $val) {
                                                        if($key == 'admin_note'){
                                                            continue;
                                                        }
                                                        ?>
                                                        <li class="ays-user-page-option-row ui-state-default">
                                                            <input type="checkbox" class="ays-checkbox-input" checked/>
                                                            <label>
                                                                <?php echo $val; ?>
                                                            </label>
                                                        </li>
                                                        <?php
                                                    }
                                                 ?>
                                            </ul>
                                       </div>
                                    </div>
                                </div>
                                <a href="https://ays-pro.com/wordpress/quiz-maker?utm_source=dashboard&utm_medium=quiz-free&utm_campaign=pro-popup-quiz-category-leaderboard-shortcode-<?php echo esc_attr( AYS_QUIZ_UTM_VERSION ); ?>" target="_blank" class="ays-quiz-new-upgrade-button-link">
                                    <div class="ays-quiz-new-upgrade-button-box">
                                        <div>
                                            <img src="<?php echo AYS_QUIZ_ADMIN_URL.'/images/icons/locked_24x24.svg'?>">
                                            <img src="<?php echo AYS_QUIZ_ADMIN_URL.'/images/icons/unlocked_24x24.svg'?>" class="ays-quiz-new-upgrade-button-hover">
                                        </div>
                                        <div class="ays-quiz-new-upgrade-button"><?php echo __("Upgrade", "quiz-maker"); ?></div>
                                    </div>
                                </a>
                                <div class="ays-quiz-new-watch-video-button-box">
                                    <div>
                                        <img src="<?php echo AYS_QUIZ_ADMIN_URL.'/images/icons/video_24x24.svg'?>">
                                        <img src="<?php echo AYS_QUIZ_ADMIN_URL.'/images/icons/video_24x24_hover.svg'?>" class="ays-quiz-new-watch-video-button-hover">
                                    </div>
                                    <div class="ays-quiz-new-watch-video-button"><?php echo __("Watch Video", "quiz-maker"); ?></div>
                                </div>
                                <div class="ays-quiz-center-big-main-button-box ays-quiz-new-big-button-flex">
                                    <div class="ays-quiz-center-big-watch-video-button-box ays-quiz-big-upgrade-margin-right-10">
                                        <div class="ays-quiz-center-new-watch-video-demo-button">
                                            <img src="<?php echo AYS_QUIZ_ADMIN_URL.'/images/icons/video_24x24.svg'?>" class="ays-quiz-new-button-img-hide">
                                            <img src="<?php echo AYS_QUIZ_ADMIN_URL.'/images/icons/video_24x24_hover.svg'?>" class="ays-quiz-new-watch-video-button-hover">
                                            <?php echo __("Watch Video", "quiz-maker"); ?>
                                        </div>
                                    </div>
                                    <div class="ays-quiz-center-big-upgrade-button-box">
                                        <a href="https://ays-pro.com/wordpress/quiz-maker?utm_source=dashboard&utm_medium=quiz-free&utm_campaign=pro-popup-quiz-category-leaderboard-shortcode-<?php echo esc_attr( AYS_QUIZ_UTM_VERSION ); ?>" target="_blank" class="ays-quiz-new-upgrade-button-link">
                                            <div class="ays-quiz-center-new-big-upgrade-button">
                                                <img src="<?php echo AYS_QUIZ_ADMIN_URL.'/images/icons/locked_24x24.svg'?>" class="ays-quiz-new-button-img-hide">
                                                <img src="<?php echo AYS_QUIZ_ADMIN_URL.'/images/icons/unlocked_24x24.svg'?>" class="ays-quiz-new-upgrade-button-hover">  
                                                <?php echo __("Upgrade", "quiz-maker"); ?>
                                            </div>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </fieldset> <!-- Leaderboard By Quiz Category Settings -->
                        <hr>
                        <fieldset>
                            <legend>
                                <strong style="font-size:30px;">[ ]</strong>
                                <h5 class="ays-subtitle"><?php echo __('User Leaderboard Position Settings', 'quiz-maker'); ?></h5>
                            </legend>
                            <div class="col-sm-12 only_pro" style="padding:20px;">
                                <div class="pro_features" style="justify-content:flex-end;">

                                </div>
                                <div class="form-group row">
                                    <div class="col-sm-4">
                                        <label for="ays_user_leaderboard_position">
                                            <?php echo __( "Shortcode", 'quiz-maker' ); ?>
                                            <a class="ays_help" data-toggle="tooltip" title="<?php echo __('Copy the shortcode and paste it to any post/page to see the leaderboard position of the current user. It works with Individual Leaderboard shortcode options.','quiz-maker'); ?>">
                                                <i class="ays_fa ays_fa_info_circle"></i>
                                            </a>
                                        </label>
                                    </div>
                                    <div class="col-sm-8">
                                        <input type="text" id="ays_user_leaderboard_position" class="ays-text-input" onclick="this.setSelectionRange(0, this.value.length)" readonly="" value='[ays_user_leaderboard_position id="YOUR_QUIZ_ID"]'>
                                    </div>
                                </div>
                                <hr>
                                <div class="form-group row">
                                    <div class="col-sm-4">
                                        <label for="ays_user_gleaderboard_position">
                                            <?php echo __( "Shortcode", 'quiz-maker' ); ?>
                                            <a class="ays_help" data-toggle="tooltip" title="<?php echo __('Copy the shortcode and paste it to any post/page to see the global leaderboard position of the current user. It works with Global Leaderboard shortcode options.','quiz-maker'); ?>">
                                                <i class="ays_fa ays_fa_info_circle"></i>
                                            </a>
                                        </label>
                                    </div>
                                    <div class="col-sm-8">
                                        <input type="text" id="ays_user_gleaderboard_position" class="ays-text-input" onclick="this.setSelectionRange(0, this.value.length)" readonly="" value='[ays_user_gleaderboard_position]'>
                                    </div>
                                </div>
                                <hr>
                                <div class="form-group row">
                                    <div class="col-sm-4">
                                        <label for="ays_user_category_leaderboard_position">
                                            <?php echo __( "Shortcode", 'quiz-maker' ); ?>
                                            <a class="ays_help" data-toggle="tooltip" title="<?php echo __('Copy the shortcode and paste it to any post/page to see the quiz category leaderboard position of the current user. It works with Leaderboard By Quiz Category shortcode options.', 'quiz-maker'); ?>">
                                                <i class="ays_fa ays_fa_info_circle"></i>
                                            </a>
                                        </label>
                                    </div>
                                    <div class="col-sm-8">
                                        <input type="text" id="ays_user_category_leaderboard_position" class="ays-text-input" onclick="this.setSelectionRange(0, this.value.length)" readonly="" value='[ays_user_category_leaderboard_position id="Your_Quiz_Category_ID"]'>
                                    </div>
                                </div>
                                <a href="https://ays-pro.com/wordpress/quiz-maker?utm_source=dashboard&utm_medium=quiz-free&utm_campaign=leaderboard-position-shortcode-<?php echo esc_attr( AYS_QUIZ_UTM_VERSION ); ?>" target="_blank" class="ays-quiz-new-upgrade-button-link">
                                    <div class="ays-quiz-new-upgrade-button-box">
                                        <div>
                                            <img src="<?php echo AYS_QUIZ_ADMIN_URL.'/images/icons/locked_24x24.svg'?>">
                                            <img src="<?php echo AYS_QUIZ_ADMIN_URL.'/images/icons/unlocked_24x24.svg'?>" class="ays-quiz-new-upgrade-button-hover">
                                        </div>
                                        <div class="ays-quiz-new-upgrade-button"><?php echo __("Upgrade", "quiz-maker"); ?></div>
                                    </div>
                                </a>
                            </div>
                        </fieldset> <!-- User Leaderboard Position Settings -->
                        <hr>
                        <fieldset>
                            <legend>
                                <strong style="font-size:30px;">[ ]</strong>
                                <h5><?php echo __('User Page Settings','quiz-maker')?></h5>
                            </legend>
                            <div class="form-group row" style="padding:0px;margin:0;">
                                <div class="col-sm-12 only_pro" style="padding:20px;">
                                    <div class="pro_features pro_features_popup pro_features_background_bolder">
                                        <div class="pro-features-popup-conteiner">
                                            <div class="pro-features-popup-title">
                                                <?php echo __("How to set User Page Settings Shortcode with WordPress Quiz Plugin", 'quiz-maker'); ?>
                                            </div>
                                            <div class="pro-features-popup-content" data-link="https://youtu.be/YVp2KpsOAOc">
                                                <p>
                                                    <?php echo __("With the help of the WordPress Quiz plugin, you can display the current quiz taker's results history on the Front-end. By this, the users can understand what quizzes they passed and what score they got for each of those quizzes.", 'quiz-maker'); ?>
                                                </p>
                                                <p>
                                                    <?php echo sprintf( __("Moreover, the users will get the chance to %s download/export their results %s (in PDF file format) and %s Certificates %s right from the Front-end.", 'quiz-maker'),
                                                        "<strong>",
                                                        "</strong>",
                                                        "<strong>",
                                                        "</strong>"
                                                    ); ?>
                                                </p>
                                                <p>
                                                    <?php echo sprintf( __("Besides all these advanced features, you can %s collect data %s from quiz takers with the %s Custom Fields %s feature the plugin offers and increase your website traffic.", 'quiz-maker'),
                                                        "<strong>",
                                                        "</strong>",
                                                        "<strong>",
                                                        "</strong>"
                                                    ); ?>
                                                </p>
                                                <div>
                                                    <a href="https://quiz-plugin.com/user-page-2" target="_blank"><?php echo __("See Demo", 'quiz-maker'); ?></a>
                                                </div>
                                            </div>
                                            <div class="pro-features-popup-button" data-link="https://ays-pro.com/wordpress/quiz-maker?utm_source=dashboard&utm_medium=quiz-free&utm_campaign=pro-popup-user-page-shortcode-<?php echo esc_attr( AYS_QUIZ_UTM_VERSION ); ?>">
                                                <?php echo __("Pricing", 'quiz-maker'); ?>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <div class="col-sm-4">
                                            <label for="ays_user_page">
                                                <?php echo __( "Shortcode", 'quiz-maker' ); ?>
                                            </label>
                                        </div>
                                        <div class="col-sm-8">
                                            <input type="text" id="ays_user_page" class="ays-text-input" onclick="this.setSelectionRange(0, this.value.length)" readonly="" value='[ays_user_page id="Your_Category_ID"]'>
                                        </div>
                                    </div>
                                    <hr>
                                    <div class="form-group row">
                                        <div class="col-sm-4">
                                            <label for="ays_quiz_hide_correct_answer_user_page">
                                                <?php echo __( "Hide correct answer", 'quiz-maker' ); ?>
                                                <a class="ays_help" data-toggle="tooltip" title="<?php echo __('Tick the checkbox if you want to hide the correct answers presented in the detailed report.','quiz-maker')?>">
                                                    <i class="ays_fa ays_fa_info_circle"></i>
                                                </a>
                                            </label>
                                        </div>
                                        <div class="col-sm-8">
                                            <input type="checkbox" class="ays-checkbox-input" value="on" >
                                        </div>
                                    </div>
                                    <hr>
                                    <div class="form-group row">
                                        <div class="col-sm-4">
                                            <label>
                                                <?php echo __( "Hide Export PDF button", $this->plugin_name ); ?>
                                                <a class="ays_help" data-toggle="tooltip" title="<?php echo __('Tick the checkbox if you want to hide the Export PDF button in the detailed report.', 'quiz-maker'); ?>">
                                                    <i class="ays_fa ays_fa_info_circle"></i>
                                                </a>
                                            </label>
                                        </div>
                                        <div class="col-sm-8">
                                            <input type="checkbox" class="ays-checkbox-input" value="on">
                                        </div>
                                    </div>
                                    <hr>
                                    <div class="form-group row">
                                        <div class="col-sm-4">
                                            <label>
                                                <?php echo esc_html__( "Include Question Explanation", 'quiz-maker' ); ?>
                                                <a class="ays_help" data-toggle="tooltip" title="<?php echo esc_html__('Tick the checkbox to include the Question explanation in the detailed report.', 'quiz-maker'); ?>">
                                                    <i class="ays_fa ays_fa_info_circle"></i>
                                                </a>
                                            </label>
                                        </div>
                                        <div class="col-sm-8">
                                            <input type="checkbox" class="ays-checkbox-input" value="on">
                                        </div>
                                    </div>
                                    <hr>
                                    <div class="form-group row">
                                        <div class="col-sm-12">
                                            <label>
                                                <?php echo __( "User Page results table columns", 'quiz-maker' ); ?>
                                                <a class="ays_help" data-toggle="tooltip" title="<?php echo __('You can sort table columns and select which columns must display on the front-end.','quiz-maker')?>">
                                                    <i class="ays_fa ays_fa_info_circle"></i>
                                                </a>
                                            </label>
                                            <div class="ays-show-user-page-table-wrap">
                                                <ul class="ays-show-user-page-table">
                                                    <?php
                                                        foreach ($default_user_page_column_names as $key => $val) {
                                                            ?>
                                                            <li class="ays-user-page-option-row ui-state-default">
                                                                <input type="hidden" value="<?php echo $val; ?>"/>
                                                                <input type="checkbox" id="ays_show_user_page_<?php echo $val; ?>" value="<?php echo $val; ?>" class="ays-checkbox-input" checked/>
                                                                <label for="ays_show_user_page_<?php echo $val; ?>">
                                                                    <?php echo $val; ?>
                                                                </label>
                                                            </li>
                                                            <?php
                                                        }
                                                     ?>
                                                </ul>
                                           </div>
                                        </div>
                                    </div>
                                    <a href="https://ays-pro.com/wordpress/quiz-maker?utm_source=dashboard&utm_medium=quiz-free&utm_campaign=pro-popup-user-page-shortcode-<?php echo esc_attr( AYS_QUIZ_UTM_VERSION ); ?>" target="_blank" class="ays-quiz-new-upgrade-button-link">
                                        <div class="ays-quiz-new-upgrade-button-box">
                                            <div>
                                                <img src="<?php echo AYS_QUIZ_ADMIN_URL.'/images/icons/locked_24x24.svg'?>">
                                                <img src="<?php echo AYS_QUIZ_ADMIN_URL.'/images/icons/unlocked_24x24.svg'?>" class="ays-quiz-new-upgrade-button-hover">
                                            </div>
                                            <div class="ays-quiz-new-upgrade-button"><?php echo __("Upgrade", "quiz-maker"); ?></div>
                                        </div>
                                    </a>
                                    <div class="ays-quiz-new-watch-video-button-box">
                                        <div>
                                            <img src="<?php echo AYS_QUIZ_ADMIN_URL.'/images/icons/video_24x24.svg'?>">
                                            <img src="<?php echo AYS_QUIZ_ADMIN_URL.'/images/icons/video_24x24_hover.svg'?>" class="ays-quiz-new-watch-video-button-hover">
                                        </div>
                                        <div class="ays-quiz-new-watch-video-button"><?php echo __("Watch Video", "quiz-maker"); ?></div>
                                    </div>
                                </div>
                            </div>
                        </fieldset> <!-- User Page Settings -->
                        <hr>
                        <fieldset>
                            <legend>
                                <strong style="font-size:30px;">[ ]</strong>
                                <h5><?php echo __('Flash Cards Settings','quiz-maker'); ?></h5>
                            </legend>
                            <div class="form-group row" style="padding:0px;margin:0;">
                                <div class="col-sm-12">
                                    <div class="ays-quiz-heading-box ays-quiz-unset-float ays-quiz-unset-margin">
                                        <div class="ays-quiz-wordpress-user-manual-box ays-quiz-wordpress-text-align">
                                            <a href="https://www.youtube.com/watch?v=uBpzFjXyKC8" target="_blank">
                                                <?php echo __("How to create flashcards - video", 'quiz-maker'); ?>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-12 only_pro ays-quiz-margin-top-20" style="padding:20px;">
                                    <div class="pro_features pro_features_popup_only_hover" style="">

                                    </div>
                                    <div class="form-group row" style="padding:0px;margin:0;">
                                        <div class="col-sm-12" style="padding:20px;">
                                            <div class="form-group row">
                                                <div class="col-sm-4">
                                                    <label>
                                                        <?php echo __( "Shortcode", 'quiz-maker' ); ?>
                                                        <a class="ays_help" data-toggle="tooltip" title="<?php echo __('Paste the shortcode into any of your posts/pages to create flashcards in a question-and-answer format. Each flashcard shows a question on one side and a correct answer on the other.','quiz-maker'); ?>">
                                                            <i class="ays_fa ays_fa_info_circle"></i>
                                                        </a>
                                                    </label>
                                                </div>
                                                <div class="col-sm-8">
                                                    <input type="text" class="ays-text-input" onclick="this.setSelectionRange(0, this.value.length)" readonly="" value='[ays_quiz_flash_card by="quiz/category" id="ID(s)"]'>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <hr>
                                    <div class="form-group row" style="padding:0px;margin:0;">
                                        <div class="col-sm-12">
                                            <div class="form-group row">
                                                <div class="col-sm-4">
                                                    <label for="ays_quiz_flash_card_width">
                                                        <?php echo __( "Width", 'quiz-maker' ); ?>
                                                        <a class="ays_help" data-toggle="tooltip" title="<?php echo __('The width of the Flash Card. It accepts only numeric values. For 100% leave it blank.','quiz-maker'); ?>">
                                                            <i class="ays_fa ays_fa_info_circle"></i>
                                                        </a>
                                                    </label>
                                                </div>
                                                <div class="col-sm-8">
                                                    <input type="text" class="ays-text-input ays-quiz-flash-card-width" value=''>
                                                    <span style="display:block;" class="ays_quiz_small_hint_text"><?php echo __("For 100% leave blank", 'quiz-maker');?></span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <hr>
                                    <div class="form-group row" style="padding:0px;margin:0;">
                                        <div class="col-sm-12">
                                            <div class="form-group row">
                                                <div class="col-sm-4">
                                                    <label for="ays_quiz_flash_card_color">
                                                        <?php echo __( "Background color", 'quiz-maker' ); ?>
                                                        <a class="ays_help" data-toggle="tooltip" title="<?php echo __('The background color of the Flash Card.','quiz-maker'); ?>">
                                                            <i class="ays_fa ays_fa_info_circle"></i>
                                                        </a>
                                                    </label>
                                                </div>
                                                <div class="col-sm-8">
                                                    <input type="text" id="ays_quiz_flash_card_color" data-alpha="true" value="#ffffff">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <hr>
                                    <div class="form-group row" style="padding:0px;margin:0;">
                                        <div class="col-sm-12">
                                            <div class="form-group row ays_toggle_parent">
                                                <div class="col-sm-4">
                                                    <label for="ays_enable_fc_introduction">
                                                        <?php echo __( "Introduction page", 'quiz-maker' ); ?>
                                                        <a class="ays_help" data-toggle="tooltip" title="<?php echo __('Tick the checkbox to add a Start page to your Flashcards. You can customize the Start page and write your preferred texts in WP Editor.','quiz-maker'); ?>">
                                                            <i class="ays_fa ays_fa_info_circle"></i>
                                                        </a>
                                                    </label>
                                                </div>
                                                <div class="col-sm-1">
                                                    <input type="checkbox" class="ays_toggle_checkbox">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <hr>
                                    <div class="form-group row" style="padding:0px;margin:0;">
                                        <div class="col-sm-12">
                                            <div class="form-group row">
                                                <div class="col-sm-4">
                                                    <label for="ays_quiz_flash_card_randomize">
                                                        <?php echo __( "Randomize Flash Cards", 'quiz-maker' ); ?>
                                                        <a class="ays_help" data-toggle="tooltip" title="<?php echo __('Display the flashcard questions in random order.','quiz-maker'); ?>">
                                                            <i class="ays_fa ays_fa_info_circle"></i>
                                                        </a>
                                                    </label>
                                                </div>
                                                <div class="col-sm-8">
                                                    <input type="checkbox" id="ays_quiz_flash_card_randomize" class="ays-quiz-flash-card-randomize" value='on'>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <hr>
                                    <blockquote>
                                        <ul class="ays-quiz-general-settings-blockquote-ul">
                                            <li>
                                                <?php
                                                    echo sprintf(
                                                        __( '%s By %s', 'quiz-maker' ) . ' - ' . __( 'Choose the method of filtering. Example: by="quiz"', 'quiz-maker' ),
                                                        '<b>',
                                                        '</b>'
                                                    );
                                                ?>
                                                <ul class='ays-quiz-general-settings-ul'>
                                                    <li>
                                                        <?php
                                                            echo sprintf(
                                                                __( '%s quiz %s', 'quiz-maker' ) . ' - ' . __( 'If you set the method as Quiz, it will show all questions added in the given quiz.', 'quiz-maker' ),
                                                                '<b>',
                                                                '</b>'
                                                            );
                                                        ?>
                                                    </li>
                                                    <li>
                                                        <?php
                                                            echo sprintf(
                                                                __( '%s category %s', 'quiz-maker' ) . ' - ' . __( 'If you set the method as Category, it will show all questions assigned to the given category.', 'quiz-maker' ),
                                                                '<b>',
                                                                '</b>'
                                                            );
                                                        ?>
                                                    </li>
                                                </ul>
                                            </li>
                                            <li>
                                                <?php
                                                    echo sprintf(
                                                        __( '%s ID %s', 'quiz-maker' ) . ' - ' . __( 'Select a single ID or multiple IDs. List multiple IDs by separating them with commas. Example id="13,23,33"', 'quiz-maker' ),
                                                        '<b>',
                                                        '</b>'
                                                    );
                                                ?>
                                            </li>
                                        </ul>
                                    </blockquote>
                                    <a href="https://ays-pro.com/wordpress/quiz-maker?utm_source=dashboard&utm_medium=quiz-free&utm_campaign=general-settings-flash-cards-<?php echo esc_attr( AYS_QUIZ_UTM_VERSION ); ?>" target="_blank" class="ays-quiz-new-upgrade-button-link">
                                        <div class="ays-quiz-new-upgrade-button-box">
                                            <div>
                                                <img src="<?php echo AYS_QUIZ_ADMIN_URL.'/images/icons/locked_24x24.svg'?>">
                                                <img src="<?php echo AYS_QUIZ_ADMIN_URL.'/images/icons/unlocked_24x24.svg'?>" class="ays-quiz-new-upgrade-button-hover">
                                            </div>
                                            <div class="ays-quiz-new-upgrade-button"><?php echo __("Upgrade", "quiz-maker"); ?></div>
                                        </div>
                                    </a>
                                    <a href="https://quiz-plugin.com/flash-cards" target="_blank" class="ays-quiz-new-upgrade-button-link">
                                        <div class="ays-quiz-new-view-demo-button-box">
                                            <div>
                                                <img src="<?php echo AYS_QUIZ_ADMIN_URL.'/images/icons/view-demo.svg'?>">
                                            </div>
                                            <div class="ays-quiz-new-view-demo-button"><?php echo __("View Demo", "quiz-maker"); ?></div>
                                        </div>
                                    </a>
                                    
                                    <div class="ays-quiz-center-big-main-button-box ays-quiz-new-big-button-flex">
                                        <div class="ays-quiz-center-big-view-demo-button-box ays-quiz-big-upgrade-margin-right-10">
                                            <a href="https://quiz-plugin.com/flash-cards" target="_blank" class="ays-quiz-new-upgrade-button-link">
                                                <div class="ays-quiz-center-new-big-view-demo-button">
                                                    <img src="<?php echo AYS_QUIZ_ADMIN_URL.'/images/icons/view-demo.svg'?>">
                                                    <?php echo __("View Demo", "quiz-maker"); ?>
                                                </div>
                                            </a>
                                        </div>
                                        <div class="ays-quiz-center-big-upgrade-button-box">
                                            <a href="https://ays-pro.com/wordpress/quiz-maker?utm_source=dashboard&utm_medium=quiz-free&utm_campaign=general-settings-flash-cards-<?php echo esc_attr( AYS_QUIZ_UTM_VERSION ); ?>" target="_blank" class="ays-quiz-new-upgrade-button-link">
                                                <div class="ays-quiz-center-new-big-upgrade-button">
                                                    <img src="<?php echo AYS_QUIZ_ADMIN_URL.'/images/icons/locked_24x24.svg'?>" class="ays-quiz-new-button-img-hide">
                                                    <img src="<?php echo AYS_QUIZ_ADMIN_URL.'/images/icons/unlocked_24x24.svg'?>" class="ays-quiz-new-upgrade-button-hover">  
                                                    <?php echo __("Upgrade", "quiz-maker"); ?>
                                                </div>
                                            </a>
                                        </div>
                                    </div>
                                    
                                </div>
                            </div>
                        </fieldset> <!-- Flash Cards Settings -->
                        <hr>
                        <fieldset>
                            <legend>
                                <strong style="font-size:30px;">[ ]</strong>
                                <h5><?php echo __('Display the sum of the quiz points','quiz-maker'); ?></h5>
                            </legend>
                            <div class="form-group row" style="padding:0px;margin:0;">
                                <div class="col-sm-12 only_pro" style="padding:20px;">
                                    <div class="pro_features pro_features_popup pro_features_background_bolder">
                                        <div class="pro-features-popup-conteiner">
                                            <div class="pro-features-popup-title">
                                                <?php echo __("How to set the Display the sum of the quiz points shortcode with WordPress Quiz Plugin", 'quiz-maker'); ?>
                                            </div>
                                            <div class="pro-features-popup-content" data-link="https://youtu.be/ZbP1V2UXD-o">
                                                <p>
                                                    <?php echo sprintf( __("With this shortcode, you can display the %s sum of the points %s for both one quiz and several quizzes. There are two possible ways to sum the points: %s By All and By Best %s", 'quiz-maker'),
                                                        "<strong>",
                                                        "</strong>",
                                                        "<strong>",
                                                        "</strong>"
                                                    ); ?>
                                                </p>
                                                <p>
                                                    <?php echo sprintf( __("By choosing the All mode, the shortcode will display the %s sum of all the user's points. %s By choosing the Best mode, the shortcode will display the %s sum of all the maximum points of the user. %s", 'quiz-maker'),
                                                        "<strong>",
                                                        "</strong>",
                                                        "<strong>",
                                                        "</strong>"
                                                    ); ?>
                                                </p>
                                                <p>
                                                    <?php echo __("Use this shortcode to assess the overall performance of the quiz.", 'quiz-maker'); ?>
                                                </p>
                                                <div>
                                                    <a href="https://quiz-plugin.com/docs/" target="_blank"><?php echo __("See Documentation", 'quiz-maker'); ?></a>
                                                </div>
                                            </div>
                                            <div class="pro-features-popup-button" data-link="https://ays-pro.com/wordpress/quiz-maker?utm_source=dashboard&utm_medium=quiz-free&utm_campaign=pro-popup-sum-quiz-points-shortcode-<?php echo esc_attr( AYS_QUIZ_UTM_VERSION ); ?>">
                                                <?php echo __("Pricing", 'quiz-maker'); ?>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group row" style="padding:0px;margin:0;">
                                        <div class="col-sm-12" style="padding:20px;">
                                            <div class="form-group row">
                                                <div class="col-sm-4">
                                                    <label for="ays_quiz_display_questions">
                                                        <?php echo __( "Shortcode", 'quiz-maker' ); ?>
                                                        <a class="ays_help" data-toggle="tooltip" title="<?php echo __('Copy the following shortcode and paste into any post.  Insert the IDs of the Quizzes to receive the sum of the quiz points.','quiz-maker'); ?>">
                                                            <i class="ays_fa ays_fa_info_circle"></i>
                                                        </a>
                                                    </label>
                                                </div>
                                                <div class="col-sm-8">
                                                    <input type="text" id="ays_quiz_points_count" class="ays-text-input" onclick="this.setSelectionRange(0, this.value.length)" readonly="" value='[ays_quiz_points_count id="Your_Quiz_ID(s)" mode="all/best"]'>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <hr>
                                    <blockquote>
                                        <ul class="ays-quiz-general-settings-blockquote-ul">
                                            <li>
                                                <?php
                                                    echo sprintf(
                                                        __( '%s ID %s', 'quiz-maker' ) . ' - ' . __( 'Select the ID of the quiz. You can write more than one ID.', 'quiz-maker' ),
                                                        '<b>',
                                                        '</b>'
                                                    );
                                                ?>
                                            </li>
                                            <li>
                                                <?php
                                                    echo sprintf(
                                                        __( '%s Mode %s', 'quiz-maker' ) . ' - ' . __( 'Choose the way to sum the points. Example: mode="all".', 'quiz-maker' ),
                                                        '<b>',
                                                        '</b>'
                                                    );
                                                ?>
                                                <ul class='ays-quiz-general-settings-ul'>
                                                    <li>
                                                        <?php
                                                            echo sprintf(
                                                                __( '%s All %s', 'quiz-maker' ) . ' - ' . __( "It will display the sum of all the user's points.", 'quiz-maker' ),
                                                                '<b>',
                                                                '</b>'
                                                            );
                                                        ?>
                                                    </li>
                                                    <li>
                                                        <?php
                                                            echo sprintf(
                                                                __( '%s Best %s', 'quiz-maker' ) . ' - ' . __( ' It will display the sum of all the maximum points of the user.', 'quiz-maker' ),
                                                                '<b>',
                                                                '</b>'
                                                            );
                                                        ?>
                                                    </li>
                                                </ul>
                                            </li>
                                        </ul>
                                    </blockquote>
                                    <a href="https://ays-pro.com/wordpress/quiz-maker?utm_source=dashboard&utm_medium=quiz-free&utm_campaign=pro-popup-sum-quiz-points-shortcode-<?php echo esc_attr( AYS_QUIZ_UTM_VERSION ); ?>" target="_blank" class="ays-quiz-new-upgrade-button-link">
                                        <div class="ays-quiz-new-upgrade-button-box">
                                            <div>
                                                <img src="<?php echo AYS_QUIZ_ADMIN_URL.'/images/icons/locked_24x24.svg'?>">
                                                <img src="<?php echo AYS_QUIZ_ADMIN_URL.'/images/icons/unlocked_24x24.svg'?>" class="ays-quiz-new-upgrade-button-hover">
                                            </div>
                                            <div class="ays-quiz-new-upgrade-button"><?php echo __("Upgrade", "quiz-maker"); ?></div>
                                        </div>
                                    </a>
                                    <div class="ays-quiz-new-watch-video-button-box">
                                        <div>
                                            <img src="<?php echo AYS_QUIZ_ADMIN_URL.'/images/icons/video_24x24.svg'?>">
                                            <img src="<?php echo AYS_QUIZ_ADMIN_URL.'/images/icons/video_24x24_hover.svg'?>" class="ays-quiz-new-watch-video-button-hover">
                                        </div>
                                        <div class="ays-quiz-new-watch-video-button"><?php echo __("Watch Video", "quiz-maker"); ?></div>
                                    </div>
                                </div>
                            </div>
                        </fieldset> <!-- Display the sum of the quiz points -->
                        <hr>
                        <fieldset>
                            <legend>
                                <strong style="font-size:30px;">[ ]</strong>
                                <h5><?php echo __('Show Quiz Orders','quiz-maker'); ?></h5>
                            </legend>
                            <div class="form-group row" style="padding:0px;margin:0;">
                                <div class="col-sm-12 only_pro" style="padding:20px;">
                                    <div class="pro_features pro_features_popup pro_features_background_bolder">
                                        <div class="pro-features-popup-conteiner">
                                            <div class="pro-features-popup-title">
                                                <?php echo __("How to set Show Quiz Orders shortcode with WordPress Quiz Plugin", 'quiz-maker'); ?>
                                            </div>
                                            <div class="pro-features-popup-content" data-link="https://youtu.be/kbgfDnJ5t_o">
                                                <p>
                                                    <?php echo sprintf( __("Use this shortcode to display the %s payment history of the current user %s on the Front-end. The shortcode displays the Quiz Name, Payment Date, the Amount for the Quiz, and the Payment Type (PayPal or Stripe) Table Columns in one place.", 'quiz-maker'),
                                                        "<strong>",
                                                        "</strong>"
                                                    ); ?>
                                                </p>
                                                <p>
                                                    <?php echo __("So, the quiz takers can see what quizzes they passed and made a purchase for and the amount they paid at once. ", 'quiz-maker'); ?>
                                                </p>
                                                <div>
                                                    <a href="https://quiz-plugin.com/docs/" target="_blank"><?php echo __("See Documentation", 'quiz-maker'); ?></a>
                                                </div>
                                            </div>
                                            <div class="pro-features-popup-button" data-link="https://ays-pro.com/wordpress/quiz-maker?utm_source=dashboard&utm_medium=quiz-free&utm_campaign=pro-popup-quiz-orders-shortcode-<?php echo esc_attr( AYS_QUIZ_UTM_VERSION ); ?>">
                                                <?php echo __("Pricing", 'quiz-maker'); ?>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-group row" style="padding:0px;margin:0;">
                                        <div class="col-sm-12" style="padding:20px;">
                                            <div class="form-group row">
                                                <div class="col-sm-4">
                                                    <label for="ays_quiz_all_results">
                                                        <?php echo __( "Shortcode", 'quiz-maker' ); ?>
                                                        <a class="ays_help" data-toggle="tooltip" title="<?php echo __('You can copy the shortcode and insert it into any post or page and display the Quiz Name, Payment Date, Amount and Type','quiz-maker'); ?>">
                                                            <i class="ays_fa ays_fa_info_circle"></i>
                                                        </a>
                                                    </label>
                                                </div>
                                                <div class="col-sm-8">
                                                    <input type="text" id="ays_quiz_all_results" class="ays-text-input" onclick="this.setSelectionRange(0, this.value.length)" readonly="" value='[ays_quiz_paid_quizzes]'>
                                                </div>
                                            </div>
                                            <hr>
                                            <div class="form-group row">
                                                <div class="col-sm-12">
                                                    <label>
                                                        <?php echo __( "Table columns", 'quiz-maker' ); ?>
                                                        <a class="ays_help" data-toggle="tooltip" title="<?php echo __('You can sort table columns and select which columns must display on the front-end.','quiz-maker'); ?>">
                                                            <i class="ays_fa ays_fa_info_circle"></i>
                                                        </a>
                                                    </label>
                                                    <div class="ays-show-user-page-table-wrap">
                                                        <ul class="ays-show-user-page-table">
                                                            <?php
                                                                foreach ($quiz_all_orders_columns_order as $key => $val) {
                                                                    $checked = 'checked';

                                                                    $default_all_orders_column_names_label = '';
                                                                    if( isset( $default_all_orders_columns_names[$val] ) && $default_all_orders_columns_names[$val] != '' ){
                                                                        $default_all_orders_column_names_label = $default_all_orders_columns_names[$val];
                                                                    }

                                                                    if( $default_all_orders_column_names_label == '' ){
                                                                        continue;
                                                                    }

                                                                    ?>
                                                                    <li class="ays-user-page-option-row ui-state-default">
                                                                        <input type="hidden" value="<?php echo $val; ?>"/>
                                                                        <input type="checkbox" id="ays_show_order<?php echo $val; ?>" value="<?php echo $val; ?>" class="ays-checkbox-input" <?php echo $checked; ?>/>
                                                                        <label for="ays_show_order<?php echo $val; ?>">
                                                                            <?php echo $default_all_orders_column_names_label; ?>
                                                                        </label>
                                                                    </li>
                                                                    <?php
                                                                }
                                                             ?>
                                                        </ul>
                                                   </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <a href="https://ays-pro.com/wordpress/quiz-maker?utm_source=dashboard&utm_medium=quiz-free&utm_campaign=pro-popup-quiz-orders-shortcode-<?php echo esc_attr( AYS_QUIZ_UTM_VERSION ); ?>" target="_blank" class="ays-quiz-new-upgrade-button-link">
                                        <div class="ays-quiz-new-upgrade-button-box ays-quiz-new-upgrade-button-box-mobile-style">
                                            <div>
                                                <img src="<?php echo AYS_QUIZ_ADMIN_URL.'/images/icons/locked_24x24.svg'?>">
                                                <img src="<?php echo AYS_QUIZ_ADMIN_URL.'/images/icons/unlocked_24x24.svg'?>" class="ays-quiz-new-upgrade-button-hover">
                                            </div>
                                            <div class="ays-quiz-new-upgrade-button"><?php echo __("Upgrade to Developer/Agency", "quiz-maker"); ?></div>
                                        </div>
                                    </a>
                                    <div class="ays-quiz-new-watch-video-button-box ays-quiz-new-watch-video-button-box-mobile-style">
                                        <div>
                                            <img src="<?php echo AYS_QUIZ_ADMIN_URL.'/images/icons/video_24x24.svg'?>">
                                            <img src="<?php echo AYS_QUIZ_ADMIN_URL.'/images/icons/video_24x24_hover.svg'?>" class="ays-quiz-new-watch-video-button-hover">
                                        </div>
                                        <div class="ays-quiz-new-watch-video-button"><?php echo __("Watch Video", "quiz-maker"); ?></div>
                                    </div>
                                </div>
                            </div>
                        </fieldset> <!-- Show Quiz Orders -->
                        <hr>
                        <fieldset>
                            <legend>
                                <strong style="font-size:30px;">[ ]</strong>
                                <h5><?php echo __('Quiz multilanguage','quiz-maker'); ?></h5>
                            </legend>
                            <div class="form-group row" style="padding:0px;margin:0;">
                                <div class="col-sm-12 only_pro" style="padding:20px;">
                                    <div class="pro_features" style="">

                                    </div>
                                    <div class="form-group row" style="padding:0px;margin:0;">
                                        <div class="col-sm-12" style="padding:20px;">
                                            <div class="form-group row">
                                                <div class="col-sm-4">
                                                    <label for="ays_quiz_all_results">
                                                        <?php echo __( "Shortcode", 'quiz-maker' ); ?>
                                                        <a class="ays_help" data-toggle="tooltip" title="<?php echo __('Write your desired text in any WordPress language. It will be translated in the front-end. The languages must be included in the ISO 639-1 Code column.','quiz-maker'); ?>">
                                                            <i class="ays_fa ays_fa_info_circle"></i>
                                                        </a>
                                                    </label>
                                                </div>
                                                <div class="col-sm-8">
                                                    <input type="text" id="ays_quiz_multilanugage_shortcode" class="ays-text-input" onclick="this.setSelectionRange(0, this.value.length)" readonly="" value='[:en]Hello[:es]Hola[:]'>
                                                </div>
                                            </div>
                                            <hr>
                                        </div>
                                    </div>
                                    <blockquote>
                                        <ul class="ays-quiz-general-settings-blockquote-ul">
                                            <li>
                                                <?php
                                                    echo sprintf(
                                                        __( "In this shortcode you can add your desired text and its translation. The translated version of the text will be displayed in the front-end. The languages must be written in the %sLanguage Code%s", 'quiz-maker' ),
                                                        '<a href="https://www.loc.gov/standards/iso639-2/php/code_list.php" target="_blank">',
                                                        '</a>'
                                                    );
                                                ?>
                                            </li>
                                        </ul>
                                    </blockquote>
                                    <a href="https://ays-pro.com/wordpress/quiz-maker?utm_source=dashboard&utm_medium=quiz-free&utm_campaign=quiz-multilanguage-<?php echo esc_attr( AYS_QUIZ_UTM_VERSION ); ?>" target="_blank" class="ays-quiz-new-upgrade-button-link">
                                        <div class="ays-quiz-new-upgrade-button-box">
                                            <div>
                                                <img src="<?php echo AYS_QUIZ_ADMIN_URL.'/images/icons/locked_24x24.svg'?>">
                                                <img src="<?php echo AYS_QUIZ_ADMIN_URL.'/images/icons/unlocked_24x24.svg'?>" class="ays-quiz-new-upgrade-button-hover">
                                            </div>
                                            <div class="ays-quiz-new-upgrade-button"><?php echo __("Upgrade", "quiz-maker"); ?></div>
                                        </div>
                                    </a>
                                </div>
                            </div>
                        </fieldset> <!-- Quiz multilanguage -->
                        <hr>
                        <fieldset>
                            <legend>
                                <strong style="font-size:30px;">[ ]</strong>
                                <h5><?php echo __('Quiz intervals chart','quiz-maker'); ?></h5>
                            </legend>
                            <div class="form-group row" style="padding:0px;margin:0;">
                                <div class="col-sm-12 only_pro" style="padding:20px;">
                                    <div class="pro_features" style="">

                                    </div>
                                    <div class="form-group row" style="padding:0px;margin:0;">
                                        <div class="col-sm-12" style="padding:20px;">
                                            <div class="form-group row">
                                                <div class="col-sm-4">
                                                    <label for="ays_quiz_interval_chart">
                                                        <?php echo __( "Shortcode", 'quiz-maker' ); ?>
                                                        <a class="ays_help" data-toggle="tooltip" title="<?php echo esc_attr( __("You can copy the shortcode and paste it into your desired page/post to display a chart based on keywords on the Front-end. Don't forget to change YOUR_QUIZ_ID with the corresponding Quiz ID.",'quiz-maker') ); ?>">
                                                            <i class="ays_fa ays_fa_info_circle"></i>
                                                        </a>
                                                    </label>
                                                </div>
                                                <div class="col-sm-8">
                                                    <input type="text" id="ays_quiz_interval_chart" class="ays-text-input" onclick="this.setSelectionRange(0, this.value.length)" readonly="" value='[ays_quiz_interval_chart id="Your_Quiz_ID"]'>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <a href="https://ays-pro.com/wordpress/quiz-maker?utm_source=dashboard&utm_medium=quiz-free&utm_campaign=quiz-intervals-chart-<?php echo esc_attr( AYS_QUIZ_UTM_VERSION ); ?>" target="_blank" class="ays-quiz-new-upgrade-button-link">
                                        <div class="ays-quiz-new-upgrade-button-box">
                                            <div>
                                                <img src="<?php echo AYS_QUIZ_ADMIN_URL.'/images/icons/locked_24x24.svg'?>">
                                                <img src="<?php echo AYS_QUIZ_ADMIN_URL.'/images/icons/unlocked_24x24.svg'?>" class="ays-quiz-new-upgrade-button-hover">
                                            </div>
                                            <div class="ays-quiz-new-upgrade-button"><?php echo __("Upgrade", "quiz-maker"); ?></div>
                                        </div>
                                    </a>
                                </div>
                            </div>
                        </fieldset> <!-- Quiz multilanguage -->
                        <?php
                            if(has_action('ays_qm_settings_page_integrations')){
                                echo "<hr/>";
                                do_action( 'ays_qm_settings_page_integrations' );
                            }

                            if(has_action('ays_qm_settings_page_extra_shortcodes')){
                                echo "<hr/>";
                                do_action( 'ays_qm_settings_page_extra_shortcodes' );
                            }

                            if(has_action('ays_qm_advanced_user_dashboard')){
                                echo "<hr/>";
                                do_action( 'ays_qm_advanced_user_dashboard' );
                            }
                        ?>
                    </div>
                    <div id="tab4" class="ays-quiz-tab-content <?php echo ($ays_quiz_tab == 'tab4') ? 'ays-quiz-tab-content-active' : ''; ?>">
                        <p class="ays-subtitle">
                            <?php echo __('Message variables','quiz-maker')?>
                            <a class="ays_help" data-toggle="tooltip" data-html="true" title="<p style='margin-bottom:3px;'><?php echo __( 'You can copy these variables and paste them in the following options from the quiz settings', 'quiz-maker' ); ?>:</p>
                                <p style='padding-left:10px;margin:0;'>- <?php echo __( 'Result message', 'quiz-maker' ); ?></p>
                                <p style='padding-left:10px;margin:0;'>- <?php echo __( 'Quiz pass message', 'quiz-maker' ); ?></p>
                                <p style='padding-left:10px;margin:0;'>- <?php echo __( 'Quiz fail message', 'quiz-maker' ); ?></p>">
                                <i class="ays_fa ays_fa_info_circle"></i>
                            </a>
                        </p>
                        <blockquote>
                            <p><?php echo __( "You can copy these variables and paste them in the following options from the quiz settings", 'quiz-maker' ); ?>:</p>
                            <p style="text-indent:10px;margin:0;">- <?php echo __( "Result message", 'quiz-maker' ); ?></p>
                            <p style="text-indent:10px;margin:0;">- <?php echo __( "Quiz pass message", 'quiz-maker' ); ?></p>
                            <p style="text-indent:10px;margin:0;">- <?php echo __( "Quiz fail message", 'quiz-maker' ); ?></p>
                        </blockquote>
                        <hr class="ays-quiz-bolder-hr">
                        <div class="form-group row">
                            <div class="col-sm-12"> 
                                <div class="ays-quiz-heading-box ays-quiz-unset-float ays-quiz-unset-margin">
                                    <div class="ays-quiz-wordpress-user-manual-box ays-quiz-wordpress-text-align">
                                        <a href="https://www.youtube.com/watch?v=nzQEHzmUBc8" target="_blank">
                                            <?php echo __("How message variables works - video", 'quiz-maker'); ?>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <fieldset>
                            <legend>
                                <strong style="font-size:30px;">[ ]</strong>
                                <h5><?php echo __('General Message Variables','quiz-maker'); ?></h5>
                            </legend>
                            <p class="vmessage">
                                <strong>
                                    <input type="text" onClick="this.setSelectionRange(0, this.value.length)" readonly value="%%current_date%%" />
                                </strong>
                                <span> - </span>
                                <span style="font-size:18px;">
                                    <?php echo __( "The date of the passing quiz", 'quiz-maker'); ?>
                                </span>
                            </p>
                            <p class="vmessage">
                                <strong>
                                    <input type="text" onClick="this.setSelectionRange(0, this.value.length)" readonly value="%%home_page_url%%" />
                                </strong>
                                <span> - </span>
                                <span style="font-size:18px;">
                                    <?php echo esc_attr( __( "The URL of the home page.", 'quiz-maker') ); ?>
                                </span>
                            </p>
                            <p class="vmessage">
                                <strong>
                                    <input type="text" onClick="this.setSelectionRange(0, this.value.length)" readonly value="%%admin_email%%" />
                                </strong>
                                <span> - </span>
                                <span style="font-size:18px;">
                                    <?php echo esc_attr( __( "Shows the admin's email that was filled in their WordPress profile.", 'quiz-maker') ); ?>
                                </span>
                            </p>
                            <p class="vmessage">
                                <strong>
                                    <input type="text" onClick="this.setSelectionRange(0, this.value.length)" readonly value="%%site_title%%" />
                                </strong>
                                <span> - </span>
                                <span style="font-size:18px;">
                                    <?php echo esc_attr( __( "The title of the website.", 'quiz-maker') ); ?>
                                </span>
                            </p>
                        </fieldset> <!-- General Message Variables -->
                        <hr />
                        <fieldset>
                            <legend>
                                <strong style="font-size:30px;">[ ]</strong>
                                <h5><?php echo __('User Message Variables','quiz-maker'); ?></h5>
                            </legend>
                            <p class="vmessage">
                                <strong>
                                    <input type="text" onClick="this.setSelectionRange(0, this.value.length)" readonly value="%%user_name%%"/>
                                </strong>
                                <span> - </span>
                                <span style="font-size:18px;">
                                    <?php echo __( "The name the user entered into information form", 'quiz-maker'); ?>
                                </span>
                            </p>
                            <p class="vmessage">
                                <strong>
                                    <input type="text" onClick="this.setSelectionRange(0, this.value.length)" readonly value="%%user_email%%" />
                                </strong>
                                <span> - </span>
                                <span style="font-size:18px;">
                                    <?php echo __( "The E-mail the user entered into information form", 'quiz-maker'); ?>
                                </span>
                            </p>
                            <p class="vmessage">
                                <strong>
                                    <input type="text" onClick="this.setSelectionRange(0, this.value.length)" readonly value="%%user_phone%%" />
                                </strong>
                                <span> - </span>
                                <span style="font-size:18px;">
                                    <?php echo __( "The phone the user entered into information form", 'quiz-maker'); ?>
                                </span>
                            </p>
                            <p class="vmessage">
                                <strong>
                                    <input type="text" onClick="this.setSelectionRange(0, this.value.length)" readonly value="%%user_first_name%%" />
                                </strong>
                                <span> - </span>
                                <span style="font-size:18px;">
                                    <?php echo __( "The user's first name that was filled in their WordPress site during registration.", 'quiz-maker'); ?>
                                </span>
                            </p>
                            <p class="vmessage">
                                <strong>
                                    <input type="text" onClick="this.setSelectionRange(0, this.value.length)" readonly value="%%user_last_name%%" />
                                </strong>
                                <span> - </span>
                                <span style="font-size:18px;">
                                    <?php echo __( "The user's last name that was filled in their WordPress site during registration.", 'quiz-maker'); ?>
                                </span>
                            </p>
                            <p class="vmessage">
                                <strong>
                                    <input type="text" onClick="this.setSelectionRange(0, this.value.length)" readonly value="%%user_nickname%%" />
                                </strong>
                                <span> - </span>
                                <span style="font-size:18px;">
                                    <?php echo __( "The user's nickname that was filled in their WordPress profile.", 'quiz-maker'); ?>
                                </span>
                            </p>
                            <p class="vmessage">
                                <strong>
                                    <input type="text" onClick="this.setSelectionRange(0, this.value.length)" readonly value="%%user_display_name%%" />
                                </strong>
                                <span> - </span>
                                <span style="font-size:18px;">
                                    <?php echo __( "The user's display name that was filled in their WordPress profile.", 'quiz-maker'); ?>
                                </span>
                            </p>
                            <p class="vmessage">
                                <strong>
                                    <input type="text" onClick="this.setSelectionRange(0, this.value.length)" readonly value="%%user_wordpress_email%%" />
                                </strong>
                                <span> - </span>
                                <span style="font-size:18px;">
                                    <?php echo __( "The user's email that was filled in their WordPress profile.", 'quiz-maker'); ?>
                                </span>
                            </p>
                            <p class="vmessage">
                                <strong>
                                    <input type="text" onClick="this.setSelectionRange(0, this.value.length)" readonly value="%%user_wordpress_roles%%" />
                                </strong>
                                <span> - </span>
                                <span style="font-size:18px;">
                                    <?php echo __( "The user's role(s) when logged-in. In case the user is not logged-in, the field will be empty.", 'quiz-maker'); ?>
                                </span>
                            </p>
                            <p class="vmessage">
                                <strong>
                                    <input type="text" onClick="this.setSelectionRange(0, this.value.length)" readonly value="%%user_wordpress_website%%" />
                                </strong>
                                <span> - </span>
                                <span style="font-size:18px;">
                                    <?php echo __( "The user's website that was filled in their WordPress profile.", 'quiz-maker'); ?>
                                </span>
                            </p>
                            <p class="vmessage">
                                <strong>
                                    <input type="text" onClick="this.setSelectionRange(0, this.value.length)" readonly value="%%user_pass_time%%" />
                                </strong>
                                <span> - </span>
                                <span style="font-size:18px;">
                                    <?php echo __( "The time which spent that the user passed the quiz", 'quiz-maker'); ?>
                                </span>
                            </p>
                            <p class="vmessage">
                                <strong>
                                    <input type="text" onClick="this.setSelectionRange(0, this.value.length)" readonly value="%%user_corrects_count%%" />
                                </strong>
                                <span> - </span>
                                <span style="font-size:18px;">
                                    <?php echo __( "The number of correct answers of the user", 'quiz-maker'); ?>
                                </span>
                            </p>
                            <p class="vmessage">
                                <strong>
                                    <input type="text" onClick="this.setSelectionRange(0, this.value.length)" readonly value="%%wrong_answers_count%%" />
                                </strong>
                                <span> - </span>
                                <span style="font-size:18px;">
                                    <?php echo __( "The number of wrong answers of the user.", 'quiz-maker') ." ". __( "(skipped questions are included)", 'quiz-maker'); ?>
                                </span>
                            </p>
                            <p class="vmessage">
                                <strong>
                                    <input type="text" onClick="this.setSelectionRange(0, this.value.length)" readonly value="%%only_wrong_answers_count%%" />
                                </strong>
                                <span> - </span>
                                <span style="font-size:18px;">
                                    <?php echo __( "The number of only wrong answers of the user.", 'quiz-maker'); ?>
                                </span>
                            </p>
                            <p class="vmessage">
                                <strong>
                                    <input type="text" onClick="this.setSelectionRange(0, this.value.length)" readonly value="%%skipped_questions_count%%" />
                                </strong>
                                <span> - </span>
                                <span style="font-size:18px;">
                                    <?php echo __( "The count of unanswered questions of the user.", 'quiz-maker'); ?>
                                </span>
                            </p>
                            <p class="vmessage">
                                <strong>
                                    <input type="text" onClick="this.setSelectionRange(0, this.value.length)" readonly value="%%answered_questions_count%%" />
                                </strong>
                                <span> - </span>
                                <span style="font-size:18px;">
                                    <?php echo __( "The count of answered questions of the user.", 'quiz-maker'); ?>
                                </span>
                            </p>
                            <p class="vmessage">
                                <strong>
                                    <input type="text" onClick="this.setSelectionRange(0, this.value.length)" readonly value="%%user_id%%" />
                                </strong>
                                <span> - </span>
                                <span style="font-size:18px;">
                                    <?php echo esc_attr( __( "The ID of the current user.", 'quiz-maker') ); ?>
                                </span>
                            </p>
                            <p class="vmessage">
                                <strong>
                                    <input type="text" onClick="this.setSelectionRange(0, this.value.length)" readonly value="%%current_user_ip%%" />
                                </strong>
                                <span> - </span>
                                <span style="font-size:18px;">
                                    <?php echo esc_attr( __( "Shows the current user's IP no matter whether they are a logged-in user or a guest. Please note, that this message variable will return empty, if 'Do not store IP addresses' is ticked from General Settings>General>Users IP addresses.", 'quiz-maker') ); ?>
                                </span>
                            </p>
                        </fieldset> <!-- User Message Variables -->
                        <hr />
                        <fieldset>
                            <legend>
                                <strong style="font-size:30px;">[ ]</strong>
                                <h5><?php echo __('Score Message Variables','quiz-maker'); ?></h5>
                            </legend>
                            <p class="vmessage">
                                <strong>
                                    <input type="text" onClick="this.setSelectionRange(0, this.value.length)" readonly value="%%score%%" />
                                </strong>
                                <span> - </span>
                                <span style="font-size:18px;">
                                    <?php echo __( "The score of quiz which got the user", 'quiz-maker'); ?>
                                </span>
                            </p>
                            <p class="vmessage">
                                <strong>
                                    <input type="text" onClick="this.setSelectionRange(0, this.value.length)" readonly value="%%avg_score%%" />
                                </strong>
                                <span> - </span>
                                <span style="font-size:18px;">
                                    <?php echo __( "The average score of the quiz of all time", 'quiz-maker'); ?>
                                </span>
                            </p>
                            <p class="vmessage">
                                <strong>
                                    <input type="text" onClick="this.setSelectionRange(0, this.value.length)" readonly value="%%score_by_answered_questions%%" />
                                </strong>
                                <span> - </span>
                                <span style="font-size:18px;">
                                    <?php echo __( "The score of those questions which the given user answered(%). Skipped or unanswered questions will not be included in the calculation.", 'quiz-maker'); ?>
                                </span>
                            </p>
                            <p class="vmessage">
                                <strong>
                                    <input type="text" onClick="this.setSelectionRange(0, this.value.length)" readonly value="%%results_by_cats%%" />
                                </strong>
                                <span> - </span>
                                <span style="font-size:18px;">
                                    <?php echo __( "The score of the quiz by a question categories which got the user", 'quiz-maker'); ?>
                                </span>
                            </p>
                            <p class="vmessage">
                                <strong>
                                    <input type="text" onClick="this.setSelectionRange(0, this.value.length)" readonly value="%%avg_score_by_category%%" />
                                </strong>
                                <span> - </span>
                                <span style="font-size:18px;">
                                    <?php echo __( "The average score by the question category of the given quiz of the given user.", 'quiz-maker'); ?>
                                </span>
                            </p>
                        </fieldset> <!-- Score Message Variables -->
                        <hr />
                        <fieldset>
                            <legend>
                                <strong style="font-size:30px;">[ ]</strong>
                                <h5><?php echo __('Quiz Message Variables','quiz-maker'); ?></h5>
                            </legend>
                            <p class="vmessage">
                                <strong>
                                    <input type="text" onClick="this.setSelectionRange(0, this.value.length)" readonly value="%%quiz_name%%" />
                                </strong>
                                <span> - </span>
                                <span style="font-size:18px;">
                                    <?php echo __( "The title of the quiz", 'quiz-maker'); ?>
                                </span>
                            </p>
                            <p class="vmessage">
                                <strong>
                                    <input type="text" onClick="this.setSelectionRange(0, this.value.length)" readonly value="%%quiz_id%%" />
                                </strong>
                                <span> - </span>
                                <span style="font-size:18px;">
                                    <?php echo esc_attr( __( "The ID of the quiz.", 'quiz-maker') ); ?>
                                </span>
                            </p>
                            <p class="vmessage">
                                <strong>
                                    <input type="text" onClick="this.setSelectionRange(0, this.value.length)" readonly value="%%avg_rate%%" />
                                </strong>
                                <span> - </span>
                                <span style="font-size:18px;">
                                    <?php echo __( "The average rate of the quiz of all time", 'quiz-maker'); ?>
                                </span>
                            </p>
                            <p class="vmessage">
                                <strong>
                                    <input type="text" onClick="this.setSelectionRange(0, this.value.length)" readonly value="%%quiz_time%%" />
                                </strong>
                                <span> - </span>
                                <span style="font-size:18px;">
                                    <?php echo __( "The time which must spend the user to the quiz", 'quiz-maker'); ?>
                                </span>
                            </p>
                            <p class="vmessage">
                                <strong>
                                    <input type="text" onClick="this.setSelectionRange(0, this.value.length)" readonly value="%%questions_count%%" />
                                </strong>
                                <span> - </span>
                                <span style="font-size:18px;">
                                    <?php echo __( "The number of questions that the user must pass.", 'quiz-maker'); ?>
                                </span>
                            </p>
                            
                            <p class="vmessage">
                                <strong>
                                    <input type="text" onClick="this.setSelectionRange(0, this.value.length)" readonly value="%%quiz_creation_date%%" />
                                </strong>
                                <span> - </span>
                                <span style="font-size:18px;">
                                    <?php echo __( "The exact date/time of the quiz creation.", 'quiz-maker'); ?>
                                </span>
                            </p>
                            <p class="vmessage">
                                <strong>
                                    <input type="text" onClick="this.setSelectionRange(0, this.value.length)" readonly value="%%current_quiz_author%%" />
                                </strong>
                                <span> - </span>
                                <span style="font-size:18px;">
                                    <?php echo __( "It will show the author of the current quiz.", 'quiz-maker'); ?>
                                </span>
                            </p>
                            <p class="vmessage">
                                <strong>
                                    <input type="text" onClick="this.setSelectionRange(0, this.value.length)" readonly value="%%current_quiz_page_link%%" />
                                </strong>
                                <span> - </span>
                                <span style="font-size:18px;">
                                    <?php echo __( "Prints the webpage link where the current quiz is posted.", 'quiz-maker'); ?>
                                </span>
                            </p>
                            <p class="vmessage">
                                <strong>
                                    <input type="text" onClick="this.setSelectionRange(0, this.value.length)" readonly value="%%current_quiz_author_email%%" />
                                </strong>
                                <span> - </span>
                                <span style="font-size:18px;">
                                    <?php echo esc_attr( __( "Shows the current quiz author's email that was filled in their WordPress profile.", 'quiz-maker') ); ?>
                                </span>
                            </p>
                            <p class="vmessage">
                                <strong>
                                    <input type="text" onClick="this.setSelectionRange(0, this.value.length)" readonly value="%%current_quiz_author_nickname%%" />
                                </strong>
                                <span> - </span>
                                <span style="font-size:18px;">
                                    <?php echo __( "It will show the author nickname of the current quiz.", 'quiz-maker'); ?>
                                </span>
                            </p>
                            <p class="vmessage">
                                <strong>
                                    <input type="text" onClick="this.setSelectionRange(0, this.value.length)" readonly value="%%current_quiz_author_website_url%%" />
                                </strong>
                                <span> - </span>
                                <span style="font-size:18px;">
                                    <?php echo __( "It will show the author website of the current quiz.", 'quiz-maker'); ?>
                                </span>
                            </p>
                            <p class="vmessage">
                                <strong>
                                    <input type="text" onClick="this.setSelectionRange(0, this.value.length)" readonly value="%%result_id%%" />
                                </strong>
                                <span> - </span>
                                <span style="font-size:18px;">
                                    <?php echo esc_attr( __( "The current user result ID.", 'quiz-maker') ); ?>
                                </span>
                            </p>
                            <p class="vmessage">
                                <strong>
                                    <input type="text" onClick="this.setSelectionRange(0, this.value.length)" readonly value="%%current_quiz_question_categories_count%%" />
                                </strong>
                                <span> - </span>
                                <span style="font-size:18px;">
                                    <?php echo __( "It will display the number of question categories displayed in the front end.", 'quiz-maker'); ?>
                                </span>
                            </p>
                        </fieldset> <!-- Quiz Message Variables -->
                        <hr />
                        <div class="form-group row">
                            <div class="col-sm-12 only_pro" style="padding-top: 10px;;">
                                <div class="pro_features pro_features_popup_only_hover" style="">

                                </div>
                            
                                <p class="vmessage">
                                    <strong>
                                        <input type="text" onClick="this.setSelectionRange(0, this.value.length)" readonly value="%%user_points%%" />
                                    </strong>
                                    <span> - </span>
                                    <span style="font-size:18px;">
                                        <?php echo __( "The points of quiz which got the user", 'quiz-maker'); ?>
                                    </span>
                                </p>
                                <p class="vmessage">
                                    <strong>
                                        <input type="text" onClick="this.setSelectionRange(0, this.value.length)" readonly value="%%max_points%%" />
                                    </strong>
                                    <span> - </span>
                                    <span style="font-size:18px;">
                                        <?php echo __( "Maximum points which can get the user", 'quiz-maker'); ?>
                                    </span>
                                </p>
                                <p class="vmessage">
                                    <strong>
                                        <input type="text" onClick="this.setSelectionRange(0, this.value.length)" readonly value="%%quiz_logo%%" />
                                    </strong>
                                    <span> - </span>
                                    <span style="font-size:18px;">
                                        <?php echo __( "The quiz image which used for quiz start page", 'quiz-maker'); ?>
                                    </span>
                                </p>
                                <p class="vmessage">
                                    <strong>
                                        <input type="text" onClick="this.setSelectionRange(0, this.value.length)" readonly value="%%interval_message%%" />
                                    </strong>
                                    <span> - </span>
                                    <span style="font-size:18px;">
                                        <?php echo __( "The message which must display on the result page depending from score", 'quiz-maker'); ?>
                                    </span>
                                </p>
                                <!-- ///// -->
                                <p class="vmessage">
                                    <strong>
                                        <input type="text" onClick="this.setSelectionRange(0, this.value.length)" readonly value="%%unique_code%%" />
                                    </strong>
                                    <span> - </span>
                                    <span style="font-size:18px;">
                                        <?php echo __( "You can use this unique code as an identifier. It is unique for every attempt.", 'quiz-maker'); ?>
                                    </span>
                                </p>
                                <p class="vmessage">
                                    <strong>
                                        <input type="text" onClick="this.setSelectionRange(0, this.value.length)" readonly value="%%download_certificate%%" />
                                    </strong>
                                    <span> - </span>
                                    <span style="font-size:18px;">
                                        <?php echo __( "You can use this variable to allow users to download their certificate after quiz completion.", 'quiz-maker'); ?>
                                    </span>
                                </p>
                                <p class="vmessage">
                                    <strong>
                                        <input type="text" onClick="this.setSelectionRange(0, this.value.length)" readonly value="%%keyword_count_{keyword}%%" />
                                    </strong>
                                    <span> - </span>
                                    <span style="font-size:18px;">
                                        <?php echo __( "The count of the selected keyword that the user answers during the quiz. For instance, %%keyword_count_A%%.", 'quiz-maker'); ?>
                                    </span>
                                </p>
                                <p class="vmessage">
                                    <strong>
                                        <input type="text" onClick="this.setSelectionRange(0, this.value.length)" readonly value="%%keyword_percentage_{keyword}%%" />
                                    </strong>
                                    <span> - </span>
                                    <span style="font-size:18px;">
                                        <?php echo __( "The percentage of the selected keyword that the user answers during the quiz. For instance, %%keyword_percentage_A%%.", 'quiz-maker'); ?>
                                    </span>
                                </p>
                                <p class="vmessage">
                                    <strong>
                                        <input type="text" onClick="this.setSelectionRange(0, this.value.length)" readonly value="%%top_keywords_count_{count}%%" />
                                    </strong>
                                    <span> - </span>
                                    <span style="font-size:18px;">
                                        <?php echo __( "Top keywords of answers selected by the user during the quiz. Each keyword will be displayed with the count of selected keywords. For instance, %%top_keywords_count_3%%.", 'quiz-maker'); ?>
                                    </span>
                                </p>
                                <p class="vmessage">
                                    <strong>
                                        <input type="text" onClick="this.setSelectionRange(0, this.value.length)" readonly value="%%top_keywords_percentage_{count}%%" />
                                    </strong>
                                    <span> - </span>
                                    <span style="font-size:18px;">
                                        <?php echo __( "Top keywords of answers selected by the user during the quiz. Each keyword will be displayed with the percentage of selected keywords. For instance, %%top_keywords_percentage_3%%.", 'quiz-maker'); ?>
                                    </span>
                                </p>
                                <p class="vmessage">
                                    <strong>
                                        <input type="text" onClick="this.setSelectionRange(0, this.value.length)" readonly value="%%quiz_coupon%%" />
                                    </strong>
                                    <span> - </span>
                                    <span style="font-size:18px;">
                                        <?php echo __( "You can use this message variable for showing coupons to your users. This message variable won't work unless you enable the Enable quiz coupons option.", 'quiz-maker'); ?>
                                    </span>
                                </p>
                                <p class="vmessage">
                                    <strong>
                                        <input type="text" onClick="this.setSelectionRange(0, this.value.length)" readonly value="%%user_keyword_point_{keyword}%%" />
                                    </strong>
                                    <span> - </span>
                                    <span style="font-size:18px;">
                                        <?php echo __( "It will display the total count of the keyword. For instance, %%user_keyword_point_A%%.", 'quiz-maker'); ?>
                                    </span>
                                </p>
                                <p class="vmessage">
                                    <strong>
                                        <input type="text" onClick="this.setSelectionRange(0, this.value.length)" readonly value="%%max_point_keyword_{keyword}%%" />
                                    </strong>
                                    <span> - </span>
                                    <span style="font-size:18px;">
                                        <?php echo __( "It displays the maximum point of the keywords. For instance, %%max_point_keyword_A%%.", 'quiz-maker'); ?>
                                    </span>
                                </p>
                                <p class="vmessage">
                                    <strong>
                                        <input type="text" onClick="this.setSelectionRange(0, this.value.length)" readonly value="%%user_keyword_percentage_{keyword}%%" />
                                    </strong>
                                    <span> - </span>
                                    <span style="font-size:18px;">
                                        <?php echo __( "It will display the percentage of the chosen keyword from the maximum.For instance, %%user_keyword_percentage_A%%.", 'quiz-maker'); ?>
                                    </span>
                                </p>
                                <p class="vmessage">
                                    <strong>
                                        <input type="text" onClick="this.setSelectionRange(0, this.value.length)" readonly value="%%avg_user_points%%" />
                                    </strong>
                                    <span> - </span>
                                    <span style="font-size:18px;">
                                        <?php echo __( "It will display the average score of the user.", 'quiz-maker'); ?>
                                    </span>
                                </p>
                                <p class="vmessage">
                                    <strong>
                                        <input type="text" onClick="this.setSelectionRange(0, this.value.length)" readonly value="%%avg_res_by_cats%%" />
                                    </strong>
                                    <span> - </span>
                                    <span style="font-size:18px;">
                                        <?php echo __( "It will display the average rate of the Question Category (for example: Copywriting: 2.7/5 ...).", 'quiz-maker'); ?>
                                    </span>
                                </p>
                                <p class="vmessage">
                                    <strong>
                                        <input type="text" onClick="this.setSelectionRange(0, this.value.length)" readonly value="%%results_by_cats_ordered%%" />
                                    </strong>
                                    <span> - </span>
                                    <span style="font-size:18px;">
                                        <?php echo __( "It will display the user’s scores by question categories, ordered from the highest to the lowest score.", 'quiz-maker'); ?>
                                    </span>
                                </p>
                                <p class="vmessage">
                                    <strong>
                                        <input type="text" onClick="this.setSelectionRange(0, this.value.length)" readonly value="%%results_by_cats_bar_chart_ordered%%" />
                                    </strong>
                                    <span> - </span>
                                    <span style="font-size:18px;">
                                        <?php echo __( "It will display the user's score by question categories as a bar chart, ordered from the highest to the lowest score.", 'quiz-maker'); ?>
                                    </span>
                                </p>
                                <p class="vmessage">
                                    <strong>
                                        <input type="text" onClick="this.setSelectionRange(0, this.value.length)" readonly value="%%score_bar_chart%%" />
                                    </strong>
                                    <span> - </span>
                                    <span style="font-size:18px;">
                                        <?php echo __( "The score of quiz which got the user displayed as a bar chart. Note: This message variable will not work in the email fields.", 'quiz-maker'); ?>
                                    </span>
                                </p>
                                <p class="vmessage">
                                    <strong>
                                        <input type="text" onClick="this.setSelectionRange(0, this.value.length)" readonly value="%%user_points_bar_chart%%" />
                                    </strong>
                                    <span> - </span>
                                    <span style="font-size:18px;">
                                        <?php echo __( "The points of quiz which got the user displayed as a bar chart. Note: This message variable will not work in the email fields.", 'quiz-maker'); ?>
                                    </span>
                                </p>
                                <p class="vmessage">
                                    <strong>
                                        <input type="text" onClick="this.setSelectionRange(0, this.value.length)" readonly value="%%user_pass_time_bar_chart%%" />
                                    </strong>
                                    <span> - </span>
                                    <span style="font-size:18px;">
                                        <?php echo __( "The time which must spend the user to the quiz displayed as a bar chart. Note: This message variable will only work if Quiz Timer option is set, and will not work in the email fields.", 'quiz-maker'); ?>
                                    </span>
                                </p>
                                <p class="vmessage">
                                    <strong>
                                        <input type="text" onClick="this.setSelectionRange(0, this.value.length)" readonly value="%%results_by_cats_bar_chart%%" />
                                    </strong>
                                    <span> - </span>
                                    <span style="font-size:18px;">
                                        <?php echo __( "The score of the quiz by a question categories which got the user displayed as a bar chart. Note: This message variable will not work in the email fields.", 'quiz-maker'); ?>
                                    </span>
                                </p>
                                <p class="vmessage">
                                    <strong>
                                        <input type="text" onClick="this.setSelectionRange(0, this.value.length)" readonly value="%%results_by_tags_bar_chart%%" />
                                    </strong>
                                    <span> - </span>
                                    <span style="font-size:18px;">
                                        <?php echo __( "The score the user got for the quiz by question tags displayed as a bar chart. Note: This message variable will not work in the email fields.", 'quiz-maker'); ?>
                                    </span>
                                </p>
                                <p class="vmessage">
                                    <strong>
                                        <input type="text" onClick="this.setSelectionRange(0, this.value.length)" readonly value="%%detailed_result_column_chart%%" />
                                    </strong>
                                    <span> - </span>
                                    <span style="font-size:18px;">
                                        <?php echo __( "Detailed result of answered questions of the user displayed as a column chart. Note: This message variable will not work in the email fields.", 'quiz-maker'); ?>
                                    </span>
                                </p>
                                <p class="vmessage">
                                    <strong>
                                        <input type="text" onClick="this.setSelectionRange(0, this.value.length)" readonly value="%%user_corrects_count_pie_chart%%" />
                                    </strong>
                                    <span> - </span>
                                    <span style="font-size:18px;">
                                        <?php echo __( "The count of answered questions of the user displayed as a pie chart. Note: This message variable will not work in the email fields.", 'quiz-maker'); ?>
                                    </span>
                                </p>
                                <p class="vmessage">
                                    <strong>
                                        <input type="text" onClick="this.setSelectionRange(0, this.value.length)" readonly value="%%avg_res_by_cats_bar_chart%%" />
                                    </strong>
                                    <span> - </span>
                                    <span style="font-size:18px;">
                                        <?php echo __( "It will display the average rate of the Question Category as a bar chart. Note: This message variable will not work in the email fields.", 'quiz-maker'); ?>
                                    </span>
                                </p>
                                <p class="vmessage">
                                    <strong>
                                        <input type="text" onClick="this.setSelectionRange(0, this.value.length)" readonly value="%%personality_result_by_question_ids_{CatID_1,CatID_2,CatID_3,CatID_4}%%" />
                                    </strong>
                                    <span> - </span>
                                    <span style="font-size:18px;">
                                        <?php echo __( "It will display the Question Category title and the description. Moreover, it displays in which percentage you match the particular Question Category Keyword. The message variable is designed to create Myers Personality Test. For instance, %%personality_result_by_question_ids_3,5,16,2%%.", 'quiz-maker'); ?>
                                    </span>
                                </p>
                                <a href="https://ays-pro.com/wordpress/quiz-maker?utm_source=dashboard&utm_medium=quiz-free&utm_campaign=message-variable-tab-<?php echo esc_attr( AYS_QUIZ_UTM_VERSION ); ?>" target="_blank" class="ays-quiz-new-upgrade-button-link">
                                    <div class="ays-quiz-new-upgrade-button-box">
                                        <div>
                                            <img src="<?php echo AYS_QUIZ_ADMIN_URL.'/images/icons/locked_24x24.svg'?>">
                                            <img src="<?php echo AYS_QUIZ_ADMIN_URL.'/images/icons/unlocked_24x24.svg'?>" class="ays-quiz-new-upgrade-button-hover">
                                        </div>
                                        <div class="ays-quiz-new-upgrade-button"><?php echo __("Upgrade", "quiz-maker"); ?></div>
                                    </div>
                                </a>
                                <div class="ays-quiz-center-big-main-button-box ays-quiz-new-big-button-flex">
                                    <div class="ays-quiz-center-big-upgrade-button-box">
                                        <a href="https://ays-pro.com/wordpress/quiz-maker?utm_source=dashboard&utm_medium=quiz-free&utm_campaign=message-variable-tab-<?php echo esc_attr( AYS_QUIZ_UTM_VERSION ); ?>" target="_blank" class="ays-quiz-new-upgrade-button-link">
                                            <div class="ays-quiz-center-new-big-upgrade-button">
                                                <img src="<?php echo AYS_QUIZ_ADMIN_URL.'/images/icons/locked_24x24.svg'?>" class="ays-quiz-new-button-img-hide">
                                                <img src="<?php echo AYS_QUIZ_ADMIN_URL.'/images/icons/unlocked_24x24.svg'?>" class="ays-quiz-new-upgrade-button-hover">  
                                                <?php echo __("Upgrade", "quiz-maker"); ?>
                                            </div>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div id="tab5" class="ays-quiz-tab-content <?php echo ($ays_quiz_tab == 'tab5') ? 'ays-quiz-tab-content-active' : ''; ?>">
                        <p class="ays-subtitle">
                            <?php echo __('Default Texts', "quiz-maker"); ?>
                            <a class="ays_help" data-toggle="tooltip" data-html="true" title="<?php echo __( 'If you make a change here, these words will not be translatable via translation tools!', "quiz-maker" ); ?>">
                                <i class="ays_fa ays_fa_info_circle"></i>
                            </a>
                        </p>
                        <blockquote class="ays_warning">
                            <p style="margin:0;"><?php echo __( "If you make a change here, these words will not be translatable via translation tools!", "quiz-maker" ); ?></p>
                        </blockquote>
                        <hr>
                        <div class="form-group row">
                            <div class="col-sm-3">
                                <label for="ays_quiz_wrong_shortcode_text">
                                    <?php echo __( "Wrong shortcode text", "quiz-maker" ); ?>
                                    <a class="ays_help" data-toggle="tooltip" data-html="true" title="<?php echo __( 'The text will be displayed if the post/page contains an incorrect shortcode', "quiz-maker" ); ?>">
                                        <i class="ays_fa ays_fa_info_circle"></i>
                                    </a>
                                </label>
                            </div>
                            <div class="col-sm-9">
                                <input type="text" id="ays_quiz_wrong_shortcode_text" name="ays_quiz_wrong_shortcode_text" class="ays-text-input"  value='<?php echo esc_attr($wrong_shortcode_text); ?>'>
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-sm-3">
                                <label for="ays_quiz_enter_password_text">
                                    <?php echo __( "Enter password text", 'quiz-maker' ); ?>
                                    <a class="ays_help" data-toggle="tooltip" data-html="true" title="<?php echo __( 'The text will be displayed when the quiz taker is prompted to enter a password.', 'quiz-maker' ); ?>">
                                        <i class="ays_fa ays_fa_info_circle"></i>
                                    </a>
                                </label>
                            </div>
                            <div class="col-sm-9">
                                <input type="text" id="ays_quiz_enter_password_text" name="ays_quiz_enter_password_text" class="ays-text-input"  value='<?php echo $enter_password_text; ?>'>
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-sm-3">
                                <label for="ays_quiz_wrong_password_text">
                                    <?php echo __( "Wrong password text", 'quiz-maker' ); ?>
                                    <a class="ays_help" data-toggle="tooltip" data-html="true" title="<?php echo __( 'The text will be displayed in case the quiz taker fills in the incorrect password.', 'quiz-maker' ); ?>">
                                        <i class="ays_fa ays_fa_info_circle"></i>
                                    </a>
                                </label>
                            </div>
                            <div class="col-sm-9">
                                <input type="text" id="ays_quiz_wrong_password_text" name="ays_quiz_wrong_password_text" class="ays-text-input"  value='<?php echo $wrong_password_text; ?>'>
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-sm-3">
                                <label for="ays_quiz_empty_results_text">
                                    <?php echo __( "Empty results text", 'quiz-maker' ); ?>
                                    <a class="ays_help" data-toggle="tooltip" data-html="true" title="<?php echo __( 'The text will be displayed if no matching results are found by the system for the current shortcode.', 'quiz-maker' ); ?>">
                                        <i class="ays_fa ays_fa_info_circle"></i>
                                    </a>
                                </label>
                            </div>
                            <div class="col-sm-9">
                                <input type="text" id="ays_quiz_empty_results_text" name="ays_quiz_empty_results_text" class="ays-text-input"  value='<?php echo $empty_results_text; ?>'>
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-sm-3">
                                <label for="ays_quiz_not_answered_question_text">
                                    <?php echo __( "Not answered questions text", 'quiz-maker' ); ?>
                                    <a class="ays_help" data-toggle="tooltip" data-html="true" title="<?php echo esc_attr( __( "Specify the text displayed in case the user doesn't answer the question while passing the quiz. Note: The text will be displayed on the Result page of the quiz.", 'quiz-maker' ) ); ?>">
                                        <i class="ays_fa ays_fa_info_circle"></i>
                                    </a>
                                </label>
                            </div>
                            <div class="col-sm-9">
                                <input type="text" id="ays_quiz_not_answered_question_text" name="ays_quiz_not_answered_question_text" class="ays-text-input"  value='<?php echo $not_answered_question_text; ?>'>
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-sm-3">
                                <label for="ays_quiz_finish_quiz_text">
                                    <?php echo __( "Quiz Finish Confirmation text", 'quiz-maker' ); ?>
                                    <a class="ays_help" data-toggle="tooltip" data-html="true" title="<?php echo esc_attr( __( "Change the text of the confirmation message displayed when the user clicks on the Finish button before the full completion of the quiz. Note: The text is displayed if the Enable Finish button and the Enable confirm box for the Finish button options are enabled.", 'quiz-maker' ) ); ?>">
                                        <i class="ays_fa ays_fa_info_circle"></i>
                                    </a>
                                </label>
                            </div>
                            <div class="col-sm-9">
                                <input type="text" id="ays_quiz_finish_quiz_text" name="ays_quiz_finish_quiz_text" class="ays-text-input"  value='<?php echo $finish_quiz_text; ?>'>
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-sm-3">
                                <label for="ays_quiz_select_question_placeholder_text">
                                    <?php echo __( "Dropdown Placeholder Text", 'quiz-maker' ); ?>
                                    <a class="ays_help" data-toggle="tooltip" data-html="true" title="<?php echo esc_attr( __( "Change the placeholder text written in the dropdown select field for your questions.", 'quiz-maker' ) ); ?>">
                                        <i class="ays_fa ays_fa_info_circle"></i>
                                    </a>
                                </label>
                            </div>
                            <div class="col-sm-9">
                                <input type="text" id="ays_quiz_select_question_placeholder_text" name="ays_quiz_select_question_placeholder_text" class="ays-text-input"  value='<?php echo $select_question_placeholder_text; ?>'>
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-sm-3">
                                <label for="ays_quiz_no_more_reviews_text">
                                    <?php echo __( "No more reviews Text", 'quiz-maker' ); ?>
                                    <a class="ays_help" data-toggle="tooltip" data-html="true" title="<?php echo esc_attr( __( "Customize the text, displayed when there are no more reviews to show for the quiz assessment.", 'quiz-maker' ) ); ?>">
                                        <i class="ays_fa ays_fa_info_circle"></i>
                                    </a>
                                </label>
                            </div>
                            <div class="col-sm-9">
                                <input type="text" id="ays_quiz_no_more_reviews_text" name="ays_quiz_no_more_reviews_text" class="ays-text-input"  value='<?php echo $no_more_reviews_text; ?>'>
                            </div>
                        </div>
                        <hr />
                        <p class="ays-subtitle">
                            <?php echo __('Buttons texts','quiz-maker')?>
                            <a class="ays_help" data-toggle="tooltip" data-html="true" title="<?php echo __( 'If you make a change here, these words will not be translated either․', 'quiz-maker' ); ?>">
                                <i class="ays_fa ays_fa_info_circle"></i>
                            </a>
                        </p>
                        <blockquote>
                            <p>
                                <?php echo __( "You can change the buttons' texts and write the words you prefer for them.", 'quiz-maker' ); ?>
                                <span class="ays-quiz-blockquote-span"><?php echo __( "Please note, that if you change the default texts, these words will not be translated with Translation plugins or the Poedit app.", 'quiz-maker' ); ?></span>
                            </p>
                        </blockquote>
                        <hr class="ays-quiz-bolder-hr">
                        <div class="form-group row">
                            <div class="col-sm-3">
                                <label for="ays_start_button">
                                    <?php echo __( "Start button", 'quiz-maker' ); ?>
                                </label>
                            </div>
                            <div class="col-sm-9">
                                <input type="text" id="ays_start_button" name="ays_start_button" class="ays-text-input ays-text-input-short"  value='<?php echo $start_button ?>'>
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-sm-3">
                                <label for="ays_next_button">
                                    <?php echo __( "Next button", 'quiz-maker' ); ?>
                                </label>
                            </div>
                            <div class="col-sm-9">
                                <input type="text" id="ays_next_button" name="ays_next_button" class="ays-text-input ays-text-input-short"  value='<?php echo $next_button ?>'>
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-sm-3">
                                <label for="ays_previous_button">
                                    <?php echo __( "Previous button", 'quiz-maker' ); ?>
                                </label>
                            </div>
                            <div class="col-sm-9">
                                <input type="text" id="ays_previous_button" name="ays_previous_button" class="ays-text-input ays-text-input-short"  value='<?php echo $previous_button ?>'>
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-sm-3">
                                <label for="ays_clear_button">
                                    <?php echo __( "Clear button", 'quiz-maker' ); ?>
                                </label>
                            </div>
                            <div class="col-sm-9">
                                <input type="text" id="ays_clear_button" name="ays_clear_button" class="ays-text-input ays-text-input-short"  value='<?php echo $clear_button ?>'>
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-sm-3">
                                <label for="ays_finish_button">
                                    <?php echo __( "Finish button", 'quiz-maker' ); ?>
                                </label>
                            </div>
                            <div class="col-sm-9">
                                <input type="text" id="ays_finish_button" name="ays_finish_button" class="ays-text-input ays-text-input-short"  value='<?php echo $finish_button ?>'>
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-sm-3">
                                <label for="ays_see_result_button">
                                    <?php echo __( "See Result button", 'quiz-maker' ); ?>
                                </label>
                            </div>
                            <div class="col-sm-9">
                                <input type="text" id="ays_see_result_button" name="ays_see_result_button" class="ays-text-input ays-text-input-short"  value='<?php echo $see_result_button ?>'>
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-sm-3">
                                <label for="ays_restart_quiz_button">
                                    <?php echo __( "Restart quiz button", 'quiz-maker' ); ?>
                                </label>
                            </div>
                            <div class="col-sm-9">
                                <input type="text" id="ays_restart_quiz_button" name="ays_restart_quiz_button" class="ays-text-input ays-text-input-short"  value='<?php echo $restart_quiz_button ?>'>
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-sm-3">
                                <label for="ays_send_feedback_button">
                                    <?php echo __( "Send feedback button", 'quiz-maker' ); ?>
                                </label>
                            </div>
                            <div class="col-sm-9">
                                <input type="text" id="ays_send_feedback_button" name="ays_send_feedback_button" class="ays-text-input ays-text-input-short"  value='<?php echo $send_feedback_button ?>'>
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-sm-3">
                                <label for="ays_load_more_button">
                                    <?php echo __( "Load more button", 'quiz-maker' ); ?>
                                </label>
                            </div>
                            <div class="col-sm-9">
                                <input type="text" id="ays_load_more_button" name="ays_load_more_button" class="ays-text-input ays-text-input-short"  value='<?php echo $load_more_button ?>'>
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-sm-3">
                                <label for="ays_exit_button">
                                    <?php echo __( "Exit button", 'quiz-maker' ); ?>
                                </label>
                            </div>
                            <div class="col-sm-9">
                                <input type="text" id="ays_exit_button" name="ays_exit_button" class="ays-text-input ays-text-input-short"  value='<?php echo $exit_button ?>'>
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-sm-3">
                                <label for="ays_check_button">
                                    <?php echo __( "Check button", 'quiz-maker' ); ?>
                                </label>
                            </div>
                            <div class="col-sm-9">
                                <input type="text" id="ays_check_button" name="ays_check_button" class="ays-text-input ays-text-input-short"  value='<?php echo $check_button; ?>'>
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-sm-3">
                                <label for="ays_login_button">
                                    <?php echo __( "Log In button", 'quiz-maker' ); ?>
                                </label>
                            </div>
                            <div class="col-sm-9">
                                <input type="text" id="ays_login_button" name="ays_login_button" class="ays-text-input ays-text-input-short"  value='<?php echo $login_button; ?>'>
                            </div>
                        </div>
                        <hr>
                        <p class="ays-subtitle">
                            <?php echo esc_html__('Email Terms Texts', 'quiz-maker'); ?>
                            <a class="ays_help" data-toggle="tooltip" data-html="true" title="<?php echo esc_html__( 'If you make a change here, these words will not be translatable via translation tools!', 'quiz-maker' ); ?>">
                                <i class="ays_fa ays_fa_info_circle"></i>
                            </a>
                        </p>
                        <div class="col-sm-12 only_pro" style="padding:20px;">
                            <div class="pro_features" style="justify-content:flex-end;">

                            </div>
                            <blockquote class="ays_warning">
                                <p style="margin:0;"><?php echo esc_html__( "If you make a change here, these words will not be translatable via translation tools!", 'quiz-maker' ); ?></p>
                            </blockquote>
                            <hr>
                            <div class="form-group row">
                                <div class="col-sm-3">
                                    <label>
                                        <?php echo esc_html__( "Correct answer", 'quiz-maker' ); ?>
                                    </label>
                                </div>
                                <div class="col-sm-9">
                                    <input type="text" class="ays-text-input ays-text-input-short">
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-sm-3">
                                    <label>
                                        <?php echo esc_html__( "User answered", 'quiz-maker' ); ?>
                                    </label>
                                </div>
                                <div class="col-sm-9">
                                    <input type="text" class="ays-text-input ays-text-input-short">
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-sm-3">
                                    <label>
                                        <?php echo esc_html__( "Answer Message", 'quiz-maker' ); ?>
                                    </label>
                                </div>
                                <div class="col-sm-9">
                                    <input type="text" class="ays-text-input ays-text-input-short">
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-sm-3">
                                    <label>
                                        <?php echo esc_html__( "Success", 'quiz-maker' ); ?>
                                    </label>
                                </div>
                                <div class="col-sm-9">
                                    <input type="text" class="ays-text-input ays-text-input-short">
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-sm-3">
                                    <label>
                                        <?php echo esc_html__( "Fail", 'quiz-maker' ); ?>
                                    </label>
                                </div>
                                <div class="col-sm-9">
                                    <input type="text" class="ays-text-input ays-text-input-short">
                                </div>
                            </div>
                            <a href="https://ays-pro.com/wordpress/quiz-maker?utm_source=dashboard&utm_medium=quiz-free&utm_campaign=email-terms-texts-<?php echo esc_attr( AYS_QUIZ_UTM_VERSION ); ?>" target="_blank" class="ays-quiz-new-upgrade-button-link">
                                <div class="ays-quiz-new-upgrade-button-box">
                                    <div>
                                        <img src="<?php echo AYS_QUIZ_ADMIN_URL.'/images/icons/locked_24x24.svg'?>">
                                        <img src="<?php echo AYS_QUIZ_ADMIN_URL.'/images/icons/unlocked_24x24.svg'?>" class="ays-quiz-new-upgrade-button-hover">
                                    </div>
                                    <div class="ays-quiz-new-upgrade-button"><?php echo __("Upgrade", "quiz-maker"); ?></div>
                                </div>
                            </a>
                        </div>
                    </div>
                    <div id="tab6" class="ays-quiz-tab-content <?php echo ($ays_quiz_tab == 'tab6') ? 'ays-quiz-tab-content-active' : ''; ?>">
                        <p class="ays-subtitle">
                            <?php echo __('Fields texts','quiz-maker')?>
                            <a class="ays_help" data-toggle="tooltip" data-html="true" title="<?php echo __( 'If you make a change here, these words will not be translated either.', 'quiz-maker' ); ?>">
                                <i class="ays_fa ays_fa_info_circle"></i>
                            </a>
                        </p>
                        <blockquote>
                            <p>
                                <?php echo __( "With the help of this section, you can change the fields' placeholders and labels of the Information form. Find the available fields in the User data tab of your quizzes.", 'quiz-maker' ); ?>
                                <span class="ays-quiz-blockquote-span"><?php echo __( "Please note, that if you change the default texts, these words will not be translated with Translation plugins or the Poedit app.", 'quiz-maker' ); ?></span>
                            </p>
                        </blockquote>
                        <hr class="ays-quiz-bolder-hr">
                        <div class="form-group row">
                            <div class="col-sm-2"></div>
                            <div class="col-sm-4">
                                <span><?php echo __( "Placeholders", 'quiz-maker' ); ?></span>
                            </div>
                            <div class="col-sm-4">
                                <span><?php echo __( "Labels", 'quiz-maker' ); ?></span>
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-sm-2">
                                <label for="ays_quiz_fields_placeholder_name">
                                    <?php echo __( "Name", 'quiz-maker' ); ?>
                                </label>
                            </div>
                            <div class="col-sm-4">
                                <input type="text" id="ays_quiz_fields_placeholder_name" name="ays_quiz_fields_placeholder_name" class="ays-text-input ays-text-input-short"  value='<?php echo $quiz_fields_placeholder_name; ?>'>
                            </div>
                            <div class="col-sm-4">
                                <input type="text" id="ays_quiz_fields_label_name" name="ays_quiz_fields_label_name" class="ays-text-input ays-text-input-short"  value='<?php echo $quiz_fields_label_name; ?>'>
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-sm-2">
                                <label for="ays_quiz_fields_placeholder_eamil">
                                    <?php echo __( "Email", 'quiz-maker' ); ?>
                                </label>
                            </div>
                            <div class="col-sm-4">
                                <input type="text" id="ays_quiz_fields_placeholder_eamil" name="ays_quiz_fields_placeholder_eamil" class="ays-text-input ays-text-input-short"  value='<?php echo $quiz_fields_placeholder_eamil; ?>'>
                            </div>
                            <div class="col-sm-4">
                                <input type="text" id="ays_quiz_fields_label_eamil" name="ays_quiz_fields_label_eamil" class="ays-text-input ays-text-input-short"  value='<?php echo $quiz_fields_label_eamil; ?>'>
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-sm-2">
                                <label for="ays_quiz_fields_placeholder_phone">
                                    <?php echo __( "Phone", 'quiz-maker' ); ?>
                                </label>
                            </div>
                            <div class="col-sm-4">
                                <input type="text" id="ays_quiz_fields_placeholder_phone" name="ays_quiz_fields_placeholder_phone" class="ays-text-input ays-text-input-short"  value='<?php echo $quiz_fields_placeholder_phone; ?>'>
                            </div>
                            <div class="col-sm-4">
                                <input type="text" id="ays_quiz_fields_label_phone" name="ays_quiz_fields_label_phone" class="ays-text-input ays-text-input-short"  value='<?php echo $quiz_fields_label_phone; ?>'>
                            </div>
                        </div>
                    </div>
                    <div id="tab7" class="ays-quiz-tab-content <?php echo ($ays_quiz_tab == 'tab7') ? 'ays-quiz-tab-content-active' : ''; ?>">
                        <p class="ays-subtitle"><?php echo __('Shortcodes','quiz-maker')?></p>
                        <hr class="ays-quiz-bolder-hr">
                        <fieldset>
                            <legend>
                                <strong style="font-size:30px;">[ ]</strong>
                                <h5><?php echo __('Extra shortcodes','quiz-maker'); ?></h5>
                            </legend>
                            <div class="form-group row" style="padding:0px;margin:0;">
                                <div class="col-sm-12" style="padding:20px;">
                                    <div class="form-group row">
                                        <div class="col-sm-4">
                                            <label for="ays_quiz_avg_score">
                                                <?php echo __( "Average score", 'quiz-maker' ); ?>
                                                <a class="ays_help" data-toggle="tooltip" title="<?php echo __('Copy the given shortcode and paste it in posts. Insert the Quiz ID  to see the average score of participants of that quiz.','quiz-maker'); ?>">
                                                    <i class="ays_fa ays_fa_info_circle"></i>
                                                </a>
                                            </label>
                                        </div>
                                        <div class="col-sm-8">
                                            <input type="text" id="ays_quiz_avg_score" class="ays-text-input" onclick="this.setSelectionRange(0, this.value.length)" readonly="" value='[ays_quiz_avg_score id="Your_Quiz_ID"]'>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group row" style="padding:0px;margin:0;">
                                <div class="col-sm-12" style="padding:20px;">
                                    <div class="form-group row">
                                        <div class="col-sm-4">
                                            <label for="ays_quiz_passed_users_count">
                                                <?php echo __( "Passed users count", 'quiz-maker' ); ?>
                                                <a class="ays_help" data-toggle="tooltip" title="<?php echo __('Copy the following shortcode and paste it in posts. Insert the Quiz ID to receive the number of participants of the quiz.','quiz-maker'); ?>">
                                                    <i class="ays_fa ays_fa_info_circle"></i>
                                                </a>
                                            </label>
                                        </div>
                                        <div class="col-sm-8">
                                            <input type="text" id="ays_quiz_passed_users_count" class="ays-text-input" onclick="this.setSelectionRange(0, this.value.length)" readonly="" value='[ays_quiz_passed_users_count id="Your_Quiz_ID"]'>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group row" style="padding:0px;margin:0;">
                                <div class="col-sm-12" style="padding:20px;">
                                    <div class="form-group row">
                                        <div class="col-sm-4">
                                            <label for="ays_quiz_passed_users_count_by_score">
                                                <?php echo __( "Passed users count by score", 'quiz-maker' ); ?>
                                                <a class="ays_help" data-toggle="tooltip" title="<?php echo __('Copy the following shortcode and paste it into posts. Insert the Quiz ID to receive the number of passed users of the quiz. The pass score has to be determined in the Quiz Settings.','quiz-maker'); ?>">
                                                    <i class="ays_fa ays_fa_info_circle"></i>
                                                </a>
                                            </label>
                                        </div>
                                        <div class="col-sm-8">
                                            <input type="text" id="ays_quiz_passed_users_count_by_score" class="ays-text-input" onclick="this.setSelectionRange(0, this.value.length)" readonly="" value='[ays_quiz_passed_users_count_by_score id="Your_Quiz_ID"]'>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group row" style="padding:0px;margin:0;">
                                <div class="col-sm-12" style="padding:20px;">
                                    <div class="form-group row">
                                        <div class="col-sm-4">
                                            <label for="ays_quiz_failed_users_count_by_score">
                                                <?php echo __( "Failed users count by score", 'quiz-maker' ); ?>
                                                <a class="ays_help" data-toggle="tooltip" title="<?php echo __('Copy the following shortcode and paste it into posts. Insert the Quiz ID to receive the number of failed users of the quiz. The pass score has to be determined in the Quiz Settings.','quiz-maker'); ?>">
                                                    <i class="ays_fa ays_fa_info_circle"></i>
                                                </a>
                                            </label>
                                        </div>
                                        <div class="col-sm-8">
                                            <input type="text" id="ays_quiz_failed_users_count_by_score" class="ays-text-input" onclick="this.setSelectionRange(0, this.value.length)" readonly="" value='[ays_quiz_failed_users_count_by_score id="Your_Quiz_ID"]'>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group row" style="padding:0px;margin:0;">
                                <div class="col-sm-12" style="padding:20px;">
                                    <div class="form-group row">
                                        <div class="col-sm-4">
                                            <label for="ays_quiz_unread_results_count">
                                                <?php echo __( "Show quiz unread results count", 'quiz-maker' ); ?>
                                                <a class="ays_help" data-toggle="tooltip" title="<?php echo esc_attr( __("You need to insert Your Quiz ID in the shortcode. It will show the unread results count of the particular quiz. If there is no quiz available/found with that particular Quiz ID, the shortcode will be empty.",'quiz-maker') ); ?>">
                                                    <i class="ays_fa ays_fa_info_circle"></i>
                                                </a>
                                            </label>
                                        </div>
                                        <div class="col-sm-8">
                                            <input type="text" id="ays_quiz_unread_results_count" class="ays-text-input" onclick="this.setSelectionRange(0, this.value.length)" readonly="" value='[ays_quiz_unread_results_count id="Your_Quiz_ID"]'>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group row" style="padding:0px;margin:0;">
                                <div class="col-sm-12" style="padding:20px;">
                                    <div class="form-group row">
                                        <div class="col-sm-4">
                                            <label for="ays_quiz_read_results_count">
                                                <?php echo __( "Show quiz read results count", 'quiz-maker' ); ?>
                                                <a class="ays_help" data-toggle="tooltip" title="<?php echo esc_attr( __("You need to insert Your Quiz ID in the shortcode. It will show the read results count of the particular quiz. If there is no quiz available/found with that particular Quiz ID, the shortcode will be empty.",'quiz-maker') ); ?>">
                                                    <i class="ays_fa ays_fa_info_circle"></i>
                                                </a>
                                            </label>
                                        </div>
                                        <div class="col-sm-8">
                                            <input type="text" id="ays_quiz_read_results_count" class="ays-text-input" onclick="this.setSelectionRange(0, this.value.length)" readonly="" value='[ays_quiz_read_results_count id="Your_Quiz_ID"]'>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group row" style="padding:0px;margin:0;">
                                <div class="col-sm-12" style="padding:20px;">
                                    <div class="form-group row">
                                        <div class="col-sm-4">
                                            <label for="ays_quiz_user_passed_quizzes_count">
                                                <?php echo __( "Passed quizzes count per user", 'quiz-maker' ); ?>
                                                <a class="ays_help" data-toggle="tooltip" title="<?php echo esc_attr( __("Shows the number of passed quizzes of the current user. For instance, the current user has passed 20 quizzes. If the user is not logged in shortcode will be empty.",'quiz-maker') ); ?>">
                                                    <i class="ays_fa ays_fa_info_circle"></i>
                                                </a>
                                            </label>
                                        </div>
                                        <div class="col-sm-8">
                                            <input type="text" id="ays_quiz_user_passed_quizzes_count" class="ays-text-input" onclick="this.setSelectionRange(0, this.value.length)" readonly="" value='[ays_quiz_user_passed_quizzes_count]'>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group row" style="padding:0px;margin:0;">
                                <div class="col-sm-12" style="padding:20px;">
                                    <div class="form-group row">
                                        <div class="col-sm-4">
                                            <label for="ays_quiz_user_all_passed_quizzes_count">
                                                <?php echo __( "All passed quizzes count per user", 'quiz-maker' ); ?>
                                                <a class="ays_help" data-toggle="tooltip" title="<?php echo esc_attr( __("Shows the total sum of how many times the particular user has passed all the quizzes. For instance, the current user has passed 20 quizzes 500 times in total. If the user is not logged in shortcode will be empty.",'quiz-maker') ); ?>">
                                                    <i class="ays_fa ays_fa_info_circle"></i>
                                                </a>
                                            </label>
                                        </div>
                                        <div class="col-sm-8">
                                            <input type="text" id="ays_quiz_user_all_passed_quizzes_count" class="ays-text-input" onclick="this.setSelectionRange(0, this.value.length)" readonly="" value='[ays_quiz_user_all_passed_quizzes_count]'>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group row" style="padding:0px;margin:0;">
                                <div class="col-sm-12" style="padding:20px;">
                                    <div class="form-group row">
                                        <div class="col-sm-4">
                                            <label for="ays_quiz_user_first_name">
                                                <?php echo __( "Show User First Name", 'quiz-maker' ); ?>
                                                <a class="ays_help" data-toggle="tooltip" title="<?php echo esc_attr( __("Shows the logged-in user's First Name. If the user is not logged-in, the shortcode will be empty.",'quiz-maker') ); ?>">
                                                    <i class="ays_fa ays_fa_info_circle"></i>
                                                </a>
                                            </label>
                                        </div>
                                        <div class="col-sm-8">
                                            <input type="text" id="ays_quiz_user_first_name" class="ays-text-input" onclick="this.setSelectionRange(0, this.value.length)" readonly="" value='[ays_quiz_user_first_name]'>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group row" style="padding:0px;margin:0;">
                                <div class="col-sm-12" style="padding:20px;">
                                    <div class="form-group row">
                                        <div class="col-sm-4">
                                            <label for="ays_quiz_user_last_name">
                                                <?php echo __( "Show User Last Name", 'quiz-maker' ); ?>
                                                <a class="ays_help" data-toggle="tooltip" title="<?php echo esc_attr( __("Shows the logged-in user's Last Name. If the user is not logged-in, the shortcode will be empty.",'quiz-maker') ); ?>">
                                                    <i class="ays_fa ays_fa_info_circle"></i>
                                                </a>
                                            </label>
                                        </div>
                                        <div class="col-sm-8">
                                            <input type="text" id="ays_quiz_user_last_name" class="ays-text-input" onclick="this.setSelectionRange(0, this.value.length)" readonly="" value='[ays_quiz_user_last_name]'>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group row" style="padding:0px;margin:0;">
                                <div class="col-sm-12" style="padding:20px;">
                                    <div class="form-group row">
                                        <div class="col-sm-4">
                                            <label for="ays_quiz_user_nickname">
                                                <?php echo __( "Show User Nickname", 'quiz-maker' ); ?>
                                                <a class="ays_help" data-toggle="tooltip" title="<?php echo esc_attr( __("Shows the logged-in user's Nickname. If the user is not logged-in, the shortcode will be empty.",'quiz-maker') ); ?>">
                                                    <i class="ays_fa ays_fa_info_circle"></i>
                                                </a>
                                            </label>
                                        </div>
                                        <div class="col-sm-8">
                                            <input type="text" id="ays_quiz_user_nickname" class="ays-text-input" onclick="this.setSelectionRange(0, this.value.length)" readonly="" value='[ays_quiz_user_nickname]'>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group row" style="padding:0px;margin:0;">
                                <div class="col-sm-12" style="padding:20px;">
                                    <div class="form-group row">
                                        <div class="col-sm-4">
                                            <label for="ays_quiz_user_display_name">
                                                <?php echo __( "Show User Display name", 'quiz-maker' ); ?>
                                                <a class="ays_help" data-toggle="tooltip" title="<?php echo esc_attr( __("Shows the logged-in user's Display name. If the user is not logged-in, the shortcode will be empty.",'quiz-maker') ); ?>">
                                                    <i class="ays_fa ays_fa_info_circle"></i>
                                                </a>
                                            </label>
                                        </div>
                                        <div class="col-sm-8">
                                            <input type="text" id="ays_quiz_user_display_name" class="ays-text-input" onclick="this.setSelectionRange(0, this.value.length)" readonly="" value='[ays_quiz_user_display_name]'>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group row" style="padding:0px;margin:0;">
                                <div class="col-sm-12" style="padding:20px;">
                                    <div class="form-group row">
                                        <div class="col-sm-4">
                                            <label for="ays_quiz_user_email">
                                                <?php echo __( "Show User Email", 'quiz-maker' ); ?>
                                                <a class="ays_help" data-toggle="tooltip" title="<?php echo esc_attr( __("Shows the logged-in user's Email. If the user is not logged-in, the shortcode will be empty.",'quiz-maker') ); ?>">
                                                    <i class="ays_fa ays_fa_info_circle"></i>
                                                </a>
                                            </label>
                                        </div>
                                        <div class="col-sm-8">
                                            <input type="text" id="ays_quiz_user_email" class="ays-text-input" onclick="this.setSelectionRange(0, this.value.length)" readonly="" value='[ays_quiz_user_email]'>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group row" style="padding:0px;margin:0;">
                                <div class="col-sm-12" style="padding:20px;">
                                    <div class="form-group row">
                                        <div class="col-sm-4">
                                            <label for="ays_quiz_user_roles">
                                                <?php echo __( "Show user roles", 'quiz-maker' ); ?>
                                                <a class="ays_help" data-toggle="tooltip" title="<?php echo esc_attr( __("Shows the logged-in user's role(s). If the user is not logged-in, the shortcode will be empty.",'quiz-maker') ); ?>">
                                                    <i class="ays_fa ays_fa_info_circle"></i>
                                                </a>
                                            </label>
                                        </div>
                                        <div class="col-sm-8">
                                            <input type="text" id="ays_quiz_user_roles" class="ays-text-input" onclick="this.setSelectionRange(0, this.value.length)" readonly="" value='[ays_quiz_user_roles]'>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group row" style="padding:0px;margin:0;">
                                <div class="col-sm-12" style="padding:20px;">
                                    <div class="form-group row">
                                        <div class="col-sm-4">
                                            <label for="ays_quiz_user_website">
                                                <?php echo __( "Show user website", 'quiz-maker' ); ?>
                                                <a class="ays_help" data-toggle="tooltip" title="<?php echo esc_attr( __("Shows the logged-in user's website. If the user is not logged-in, the shortcode will be empty.",'quiz-maker') ); ?>">
                                                    <i class="ays_fa ays_fa_info_circle"></i>
                                                </a>
                                            </label>
                                        </div>
                                        <div class="col-sm-8">
                                            <input type="text" id="ays_quiz_user_website" class="ays-text-input" onclick="this.setSelectionRange(0, this.value.length)" readonly="" value='[ays_quiz_user_website]'>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group row" style="padding:0px;margin:0;">
                                <div class="col-sm-12" style="padding:20px;">
                                    <div class="form-group row">
                                        <div class="col-sm-4">
                                            <label for="ays_quiz_user_duration">
                                                <?php echo __( "Show user quiz duration", 'quiz-maker' ); ?>
                                                <a class="ays_help" data-toggle="tooltip" title="<?php echo esc_attr( __("Put this shortcode on a page to show the total time the user spent to pass quizzes. It includes all the quizzes in the user history.",'quiz-maker') ); ?>">
                                                    <i class="ays_fa ays_fa_info_circle"></i>
                                                </a>
                                            </label>
                                        </div>
                                        <div class="col-sm-8">
                                            <input type="text" id="ays_quiz_user_duration" class="ays-text-input" onclick="this.setSelectionRange(0, this.value.length)" readonly="" value='[ays_quiz_user_duration]'>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group row" style="padding:0px;margin:0;">
                                <div class="col-sm-12" style="padding:20px;">
                                    <div class="form-group row">
                                        <div class="col-sm-4">
                                            <label for="ays_quiz_creation_date">
                                                <?php echo __( "Show quiz creation date", 'quiz-maker' ); ?>
                                                <a class="ays_help" data-toggle="tooltip" title="<?php echo esc_attr( __("You need to insert Your Quiz ID in the shortcode. It will show the creation date of the particular quiz. If there is no quiz available/found with that particular Quiz ID, the shortcode will be empty.",'quiz-maker') ); ?>">
                                                    <i class="ays_fa ays_fa_info_circle"></i>
                                                </a>
                                            </label>
                                        </div>
                                        <div class="col-sm-8">
                                            <input type="text" id="ays_quiz_creation_date" class="ays-text-input" onclick="this.setSelectionRange(0, this.value.length)" readonly="" value='[ays_quiz_creation_date id="Your_Quiz_ID"]'>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group row" style="padding:0px;margin:0;">
                                <div class="col-sm-12" style="padding:20px;">
                                    <div class="form-group row">
                                        <div class="col-sm-4">
                                            <label for="ays_quiz_current_author">
                                                <?php echo __( "Show current quiz author", 'quiz-maker' ); ?>
                                                <a class="ays_help" data-toggle="tooltip" title="<?php echo esc_attr( __("You need to insert Your Quiz ID in the shortcode. It will show the current author of the particular quiz. If there is no quiz or questions available/found with that particular Quiz ID, the shortcode will be empty.",'quiz-maker') ); ?>">
                                                    <i class="ays_fa ays_fa_info_circle"></i>
                                                </a>
                                            </label>
                                        </div>
                                        <div class="col-sm-8">
                                            <input type="text" id="ays_quiz_current_author" class="ays-text-input" onclick="this.setSelectionRange(0, this.value.length)" readonly="" value='[ays_quiz_current_author id="Your_Quiz_ID"]'>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group row" style="padding:0px;margin:0;">
                                <div class="col-sm-12" style="padding:20px;">
                                    <div class="form-group row">
                                        <div class="col-sm-4">
                                            <label for="ays_quiz_questions_count">
                                                <?php echo __( "Show quiz questions count", 'quiz-maker' ); ?>
                                                <a class="ays_help" data-toggle="tooltip" title="<?php echo esc_attr( __("You need to insert Your Quiz ID in the shortcode. It will show the questions count of the particular quiz. If there is no quiz available/found with that particular Quiz ID, the shortcode will be empty.",'quiz-maker') ); ?>">
                                                    <i class="ays_fa ays_fa_info_circle"></i>
                                                </a>
                                            </label>
                                        </div>
                                        <div class="col-sm-8">
                                            <input type="text" id="ays_quiz_questions_count" class="ays-text-input" onclick="this.setSelectionRange(0, this.value.length)" readonly="" value='[ays_quiz_questions_count id="Your_Quiz_ID"]'>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group row" style="padding:0px;margin:0;">
                                <div class="col-sm-12" style="padding:20px;">
                                    <div class="form-group row">
                                        <div class="col-sm-4">
                                            <label for="ays_quiz_category_title">
                                                <?php echo __( "Show quiz category title", 'quiz-maker' ); ?>
                                                <a class="ays_help" data-toggle="tooltip" title="<?php echo esc_attr( __("You need to insert Your Quiz ID in the shortcode. It will show the cateogry title of the particular quiz. If there is no quiz available/found with that particular Quiz ID, the shortcode will be empty.",'quiz-maker') ); ?>">
                                                    <i class="ays_fa ays_fa_info_circle"></i>
                                                </a>
                                            </label>
                                        </div>
                                        <div class="col-sm-8">
                                            <input type="text" id="ays_quiz_category_title" class="ays-text-input" onclick="this.setSelectionRange(0, this.value.length)" readonly="" value='[ays_quiz_category_title id="Your_Quiz_ID"]'>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group row" style="padding:0px;margin:0;">
                                <div class="col-sm-12" style="padding:20px;">
                                    <div class="form-group row">
                                        <div class="col-sm-4">
                                            <label for="ays_quiz_category_description">
                                                <?php echo __( "Show quiz category description", 'quiz-maker' ); ?>
                                                <a class="ays_help" data-toggle="tooltip" title="<?php echo esc_attr( __("You need to insert Your Quiz ID in the shortcode. It will show the cateogry description of the particular quiz. If there is no quiz available/found with that particular Quiz ID, the shortcode will be empty.",'quiz-maker') ); ?>">
                                                    <i class="ays_fa ays_fa_info_circle"></i>
                                                </a>
                                            </label>
                                        </div>
                                        <div class="col-sm-8">
                                            <input type="text" id="ays_quiz_category_description" class="ays-text-input" onclick="this.setSelectionRange(0, this.value.length)" readonly="" value='[ays_quiz_category_description id="Your_Quiz_ID"]'>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group row" style="padding:0px;margin:0;">
                                <div class="col-sm-12" style="padding:20px;">
                                    <div class="form-group row">
                                        <div class="col-sm-4">
                                            <label for="ays_quiz_quizzes_count">
                                                <?php echo __( "Show quizzes count", 'quiz-maker' ); ?>
                                                <a class="ays_help" data-toggle="tooltip" title="<?php echo esc_attr( __("Put this shortcode on a page to show the total count of quizzes.",'quiz-maker') ); ?>">
                                                    <i class="ays_fa ays_fa_info_circle"></i>
                                                </a>
                                            </label>
                                        </div>
                                        <div class="col-sm-8">
                                            <input type="text" id="ays_quiz_quizzes_count" class="ays-text-input" onclick="this.setSelectionRange(0, this.value.length)" readonly="" value='[ays_quiz_quizzes_count]'>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group row" style="padding:0px;margin:0;">
                                <div class="col-sm-12" style="padding:20px;">
                                    <div class="form-group row">
                                        <div class="col-sm-4">
                                            <label for="ays_quiz_categories_count">
                                                <?php echo __( "Show quiz categories count", 'quiz-maker' ); ?>
                                                <a class="ays_help" data-toggle="tooltip" title="<?php echo esc_attr( __("Put this shortcode on a page to show the total count of quiz categories.",'quiz-maker') ); ?>">
                                                    <i class="ays_fa ays_fa_info_circle"></i>
                                                </a>
                                            </label>
                                        </div>
                                        <div class="col-sm-8">
                                            <input type="text" id="ays_quiz_categories_count" class="ays-text-input" onclick="this.setSelectionRange(0, this.value.length)" readonly="" value='[ays_quiz_categories_count]'>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group row" style="padding:0px;margin:0;">
                                <div class="col-sm-12" style="padding:20px;">
                                    <div class="form-group row">
                                        <div class="col-sm-4">
                                            <label for="ays_quiz_all_results_count">
                                                <?php echo __( "Show quizzes total results count", 'quiz-maker' ); ?>
                                                <a class="ays_help" data-toggle="tooltip" title="<?php echo esc_attr( __("Put this shortcode on a page to show the total results count of all quizzes of all users.",'quiz-maker') ); ?>">
                                                    <i class="ays_fa ays_fa_info_circle"></i>
                                                </a>
                                            </label>
                                        </div>
                                        <div class="col-sm-8">
                                            <input type="text" id="ays_quiz_all_results_count" class="ays-text-input" onclick="this.setSelectionRange(0, this.value.length)" readonly="" value='[ays_quiz_all_results_count]'>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group row" style="padding:0px;margin:0;">
                                <div class="col-sm-12" style="padding:20px;">
                                    <div class="form-group row">
                                        <div class="col-sm-4">
                                            <label for="ays_quiz_question_categories_count">
                                                <?php echo __( "Show question categories count", 'quiz-maker' ); ?>
                                                <a class="ays_help" data-toggle="tooltip" title="<?php echo esc_attr( __("Put this shortcode on a page to show the total count of question categories.",'quiz-maker') ); ?>">
                                                    <i class="ays_fa ays_fa_info_circle"></i>
                                                </a>
                                            </label>
                                        </div>
                                        <div class="col-sm-8">
                                            <input type="text" id="ays_quiz_question_categories_count" class="ays-text-input" onclick="this.setSelectionRange(0, this.value.length)" readonly="" value='[ays_quiz_question_categories_count]'>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group row" style="padding:0px;margin:0;">
                                <div class="col-sm-12" style="padding:20px;">
                                    <div class="form-group row">
                                        <div class="col-sm-4">
                                            <label for="ays_quiz_all_questions_count">
                                                <?php echo __( "Show questions total count", 'quiz-maker' ); ?>
                                                <a class="ays_help" data-toggle="tooltip" title="<?php echo esc_attr( __("Put this shortcode on a page to show the total count of questions.",'quiz-maker') ); ?>">
                                                    <i class="ays_fa ays_fa_info_circle"></i>
                                                </a>
                                            </label>
                                        </div>
                                        <div class="col-sm-8">
                                            <input type="text" id="ays_quiz_all_questions_count" class="ays-text-input" onclick="this.setSelectionRange(0, this.value.length)" readonly="" value='[ays_quiz_all_questions_count]'>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group row" style="padding:0px;margin:0;">
                                <div class="col-sm-12" style="padding:20px;">
                                    <div class="form-group row">
                                        <div class="col-sm-4">
                                            <label for="ays_quiz_avg_rate">
                                                <?php echo __( "Show quiz average rate", 'quiz-maker' ); ?>
                                                <a class="ays_help" data-toggle="tooltip" title="<?php echo esc_attr( __("Replace Your_Quiz_ID with the corresponding Quiz ID to show the Average Rate for the quiz.",'quiz-maker') ); ?>">
                                                    <i class="ays_fa ays_fa_info_circle"></i>
                                                </a>
                                            </label>
                                        </div>
                                        <div class="col-sm-8">
                                            <input type="text" id="ays_quiz_avg_rate" class="ays-text-input" onclick="this.setSelectionRange(0, this.value.length)" readonly="" value='[ays_quiz_avg_rate id="Your_Quiz_ID"]'>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </fieldset> <!-- Extra shortcodes -->
                    </div>
                    <div id="tab8" class="ays-quiz-tab-content <?php echo ($ays_quiz_tab == 'tab8') ? 'ays-quiz-tab-content-active' : ''; ?>">
                        <p class="ays-subtitle">
                            <?php echo __('User Information','quiz-maker')?>
                            <a class="ays_help" data-toggle="tooltip" title="<?php echo __('Choose what user information you want to be displayed. Tick on the needed options for making them visible in the detailed result. Note that the information will be available in the exported version. So, even if the option is hided, it will be displayed in the exported PDF and XLSX files.', 'quiz-maker'); ?>">
                                <i class="ays_fa ays_fa_info_circle"></i>
                            </a>
                        </p>
                        <hr class="ays-quiz-bolder-hr">
                        <div class="form-group row">
                            <div class="col-sm-4">
                                <label for="ays_quiz_show_result_info_user_ip">
                                    <?php echo __( "Show User IP", 'quiz-maker' ); ?>
                                </label>
                            </div>
                            <div class="col-sm-8">
                                <input type="checkbox" class="ays-checkbox-input" id="ays_quiz_show_result_info_user_ip" name="ays_quiz_show_result_info_user_ip" value="on" <?php echo $ays_quiz_show_result_info_user_ip ? 'checked' : ''; ?> />
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-sm-4">
                                <label for="ays_quiz_show_result_info_user_id">
                                    <?php echo __( "Show User ID", 'quiz-maker' ); ?>
                                </label>
                            </div>
                            <div class="col-sm-8">
                                <input type="checkbox" class="ays-checkbox-input" id="ays_quiz_show_result_info_user_id" name="ays_quiz_show_result_info_user_id" value="on" <?php echo $ays_quiz_show_result_info_user_id ? 'checked' : ''; ?> />
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-sm-4">
                                <label for="ays_quiz_show_result_info_user">
                                    <?php echo __( "Show User", 'quiz-maker' ); ?>
                                </label>
                            </div>
                            <div class="col-sm-8">
                                <input type="checkbox" class="ays-checkbox-input" id="ays_quiz_show_result_info_user" name="ays_quiz_show_result_info_user" value="on" <?php echo $ays_quiz_show_result_info_user ? 'checked' : ''; ?> />
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-sm-4">
                                <label for="ays_quiz_show_result_info_user_email">
                                    <?php echo __( "Show User Email", 'quiz-maker' ); ?>
                                </label>
                            </div>
                            <div class="col-sm-8">
                                <input type="checkbox" class="ays-checkbox-input" id="ays_quiz_show_result_info_user_email" name="ays_quiz_show_result_info_user_email" value="on" <?php echo $ays_quiz_show_result_info_user_email ? 'checked' : ''; ?> />
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-sm-4">
                                <label for="ays_quiz_show_result_info_user_name">
                                    <?php echo __( "Show User Name", 'quiz-maker' ); ?>
                                </label>
                            </div>
                            <div class="col-sm-8">
                                <input type="checkbox" class="ays-checkbox-input" id="ays_quiz_show_result_info_user_name" name="ays_quiz_show_result_info_user_name" value="on" <?php echo $ays_quiz_show_result_info_user_name ? 'checked' : ''; ?> />
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-sm-4">
                                <label for="ays_quiz_show_result_info_user_phone">
                                    <?php echo __( "Show User Phone", 'quiz-maker' ); ?>
                                </label>
                            </div>
                            <div class="col-sm-8">
                                <input type="checkbox" class="ays-checkbox-input" id="ays_quiz_show_result_info_user_phone" name="ays_quiz_show_result_info_user_phone" value="on" <?php echo $ays_quiz_show_result_info_user_phone ? 'checked' : ''; ?> />
                            </div>
                        </div>
                        <hr>
                        <p class="ays-subtitle">
                            <?php echo __('Quiz Information','quiz-maker')?>
                            <a class="ays_help" data-toggle="tooltip" title="<?php echo __('Choose what Quiz information you want to be displayed. Tick on the needed options for making them visible in the detailed result. Note that the information will be available in the exported version. So, even if the option is hided, it will be displayed in the exported PDF and XLSX files.', 'quiz-maker'); ?>">
                                <i class="ays_fa ays_fa_info_circle"></i>
                            </a>
                        </p>
                        <hr class="ays-quiz-bolder-hr">
                        <div class="form-group row">
                            <div class="col-sm-4">
                                <label for="ays_quiz_show_result_info_start_date">
                                    <?php echo __( "Show Start date", 'quiz-maker' ); ?>
                                </label>
                            </div>
                            <div class="col-sm-8">
                                <input type="checkbox" class="ays-checkbox-input" id="ays_quiz_show_result_info_start_date" name="ays_quiz_show_result_info_start_date" value="on" <?php echo $ays_quiz_show_result_info_start_date ? 'checked' : ''; ?> />
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-sm-4">
                                <label for="ays_quiz_show_result_info_duration">
                                    <?php echo __( "Show Duration", 'quiz-maker' ); ?>
                                </label>
                            </div>
                            <div class="col-sm-8">
                                <input type="checkbox" class="ays-checkbox-input" id="ays_quiz_show_result_info_duration" name="ays_quiz_show_result_info_duration" value="on" <?php echo $ays_quiz_show_result_info_duration ? 'checked' : ''; ?> />
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-sm-4">
                                <label for="ays_quiz_show_result_info_score">
                                    <?php echo __( "Show Score", 'quiz-maker' ); ?>
                                </label>
                            </div>
                            <div class="col-sm-8">
                                <input type="checkbox" class="ays-checkbox-input" id="ays_quiz_show_result_info_score" name="ays_quiz_show_result_info_score" value="on" <?php echo $ays_quiz_show_result_info_score ? 'checked' : ''; ?> />
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-sm-4">
                                <label for="ays_quiz_show_result_info_rate">
                                    <?php echo __( "Show Rate", 'quiz-maker' ); ?>
                                </label>
                            </div>
                            <div class="col-sm-8">
                                <input type="checkbox" class="ays-checkbox-input" id="ays_quiz_show_result_info_rate" name="ays_quiz_show_result_info_rate" value="on" <?php echo $ays_quiz_show_result_info_rate ? 'checked' : ''; ?> />
                            </div>
                        </div>
                    </div>
                    <div id="tab9" class="ays-quiz-tab-content <?php echo ($ays_quiz_tab == 'tab9') ? 'ays-quiz-tab-content-active' : ''; ?>">
                        <p class="ays-subtitle"><?php echo __('Export Settings','quiz-maker'); ?></p>
                        <hr class="ays-quiz-bolder-hr"/>
                        <fieldset>
                            <legend>
                                <strong style="font-size:30px;"><i class="ays_fa ays_fa_export"></i></strong>
                                <h5><?php echo __('Individual Quiz Result Export Settings', 'quiz-maker'); ?></h5>
                            </legend>
                            <div class="col-sm-12 only_pro" style="padding:20px;">
                                <div class="pro_features" style="justify-content:flex-end;">

                                </div>
                                <blockquote>
                                    <?php echo __('Select the columns you want to include in the XLSX export of each quiz results. Tick the checkboxes for the columns you wish to include for each quiz result.', 'quiz-maker'); ?>
                                </blockquote>
                                <hr>
                                <div class="form-group row">
                                    <div class="col-sm-12">
                                        <div class="ays-export-quiz-result-table-container">
                                            <ul class="ays-export-quiz-result-table">
                                                <li class="ays-export-quiz-result-option-row">
                                                    <input type="checkbox">
                                                    <label>
                                                        <?php echo __('Score', 'quiz-maker'); ?>
                                                    </label>
                                                </li>
                                                <li class="ays-export-quiz-result-option-row">
                                                    <input type="checkbox">
                                                    <label for="ays_quiz_export_result_columns_points">
                                                        <?php echo __('Points', 'quiz-maker'); ?>
                                                    </label>
                                                </li>
                                                <li class="ays-export-quiz-result-option-row">
                                                    <input type="checkbox">
                                                    <label for="ays_quiz_export_result_columns_status">
                                                        <?php echo __('Status', 'quiz-maker'); ?>
                                                    </label>
                                                </li>
                                                <li class="ays-export-quiz-result-option-row">
                                                    <input type="checkbox">
                                                    <label for="ays_quiz_export_result_columns_report_id">
                                                        <?php echo __('Report ID', 'quiz-maker'); ?>
                                                    </label>
                                                </li>
                                                <li class="ays-export-quiz-result-option-row">
                                                    <input type="checkbox">
                                                    <label for="ays_quiz_export_result_columns_unique_code">
                                                        <?php echo __('Unique Code', 'quiz-maker'); ?>
                                                    </label>
                                                </li>
                                                <li class="ays-export-quiz-result-option-row">
                                                    <input type="checkbox">
                                                    <label for="ays_quiz_export_result_columns_quiz_password">
                                                        <?php echo __('Quiz Password', 'quiz-maker'); ?>
                                                    </label>
                                                </li>
                                                <li class="ays-export-quiz-result-option-row">
                                                    <input type="checkbox">
                                                    <label for="ays_quiz_export_result_columns_quiz_coupon">
                                                        <?php echo __('Quiz Coupon', 'quiz-maker'); ?>
                                                    </label>
                                                </li>
                                                <li class="ays-export-quiz-result-option-row">
                                                    <input type="checkbox">
                                                    <label for="ays_quiz_export_result_columns_keywords">
                                                        <?php echo __('Keywords', 'quiz-maker'); ?>
                                                    </label>
                                                </li>
                                                <li class="ays-export-quiz-result-option-row">
                                                    <input type="checkbox">
                                                    <label for="ays_quiz_export_result_columns_start_date">
                                                        <?php echo __('Start Date', 'quiz-maker'); ?>
                                                    </label>
                                                </li>
                                                <li class="ays-export-quiz-result-option-row">
                                                    <input type="checkbox">
                                                    <label for="ays_quiz_export_result_columns_end_date">
                                                        <?php echo __('End Date', 'quiz-maker'); ?>
                                                    </label>
                                                </li>
                                                <li class="ays-export-quiz-result-option-row">
                                                    <input type="checkbox">
                                                    <label for="ays_quiz_export_result_columns_post">
                                                        <?php echo __('Post Link', 'quiz-maker'); ?>
                                                    </label>
                                                </li>
                                                <li class="ays-export-quiz-result-option-row">
                                                    <input type="checkbox">
                                                    <label for="ays_quiz_export_result_columns_duration">
                                                        <?php echo __('Duration', 'quiz-maker'); ?>
                                                    </label>
                                                </li>
                                                <li class="ays-export-quiz-result-option-row">
                                                    <input type="checkbox">
                                                    <label for="ays_quiz_export_result_columns_custom_fields">
                                                        <?php echo __('Custom Fields', 'quiz-maker'); ?>
                                                    </label>
                                                </li>
                                                <li class="ays-export-quiz-result-option-row">
                                                    <input type="checkbox">
                                                    <label for="ays_quiz_export_result_columns_questions">
                                                        <?php echo __('Questions', 'quiz-maker'); ?>
                                                    </label>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                                <a href="https://ays-pro.com/wordpress/quiz-maker?utm_source=dashboard&utm_medium=quiz-free&utm_campaign=individual-quiz-result-export-settings-<?php echo esc_attr( AYS_QUIZ_UTM_VERSION ); ?>" target="_blank" class="ays-quiz-new-upgrade-button-link">
                                    <div class="ays-quiz-new-upgrade-button-box">
                                        <div>
                                            <img src="<?php echo AYS_QUIZ_ADMIN_URL.'/images/icons/locked_24x24.svg'?>">
                                            <img src="<?php echo AYS_QUIZ_ADMIN_URL.'/images/icons/unlocked_24x24.svg'?>" class="ays-quiz-new-upgrade-button-hover">
                                        </div>
                                        <div class="ays-quiz-new-upgrade-button"><?php echo __("Upgrade", "quiz-maker"); ?></div>
                                    </div>
                                </a>
                            </div>
                        </fieldset>
                        <hr>
                    </div>
                    <div id="tab10" class="ays-quiz-tab-content  <?php echo ($ays_quiz_tab == 'tab10') ? 'ays-quiz-tab-content-active' : ''; ?>">
                        <p class="ays-subtitle"><?php echo esc_html__('Ordering','quiz-maker')?></p>
                        <hr class="ays-quiz-bolder-hr"/>
                        <div class="ays-quiz-tab-ordering ays-quiz-dashboard-main-wrap">
                            <h4><?php echo esc_html__('Result Page Layout','quiz-maker')?></h4>
                            <div class="col-sm-12 only_pro" style="padding:20px;">
                                <div class="pro_features" style="justify-content:flex-end;">

                                </div>
                                <p><?php echo esc_html__('Customize the order of component on the quiz result page.Drag to reorderitems.These settings  will apply to all quizzes.','quiz-maker')?></p>
                                <div class="ays-quiz-item-result-component">
                                    <h6><?php echo esc_html__('Order Component','quiz-maker')?></h6>
                                    <div  class="form-group row ">
                                        <div class="col-sm-12 ">
                                            <div class="ays-quiz-settings-ui-sortable-results">
                                                <?php foreach($result_sort as $key) { ?>
                                                    <?php if(isset($result_page_ordering_htmls[$key])) { ?>
                                                        <div class="ays-quiz-order-item-result ays-quiz-order-item ays-quiz-dlg-dragHandle <?php if($key == 'conditions_message') { echo $conditions_bg; }else if($key == 'woo_block'){ echo $woo_bg;}?>">
                                                            <div class="">
                                                                <svg width="18" height="18" viewBox="0 0 18 18" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                                    <g clip-path="url(#clip0_141_42)">
                                                                        <path
                                                                            d="M9.79463 0.330566C9.35518 -0.108887 8.6415 -0.108887 8.20205 0.330566L5.95205 2.58057C5.5126 3.02002 5.5126 3.73369 5.95205 4.17314C6.3915 4.6126 7.10518 4.6126 7.54463 4.17314L7.8751 3.84268V7.8751H3.84268L4.17314 7.54463C4.6126 7.10518 4.6126 6.3915 4.17314 5.95205C3.73369 5.5126 3.02002 5.5126 2.58057 5.95205L0.330566 8.20205C-0.108887 8.6415 -0.108887 9.35518 0.330566 9.79463L2.58057 12.0446C3.02002 12.4841 3.73369 12.4841 4.17314 12.0446C4.6126 11.6052 4.6126 10.8915 4.17314 10.452L3.84268 10.1216L7.8751 10.1251V14.1575L7.54463 13.827C7.10518 13.3876 6.3915 13.3876 5.95205 13.827C5.5126 14.2665 5.5126 14.9802 5.95205 15.4196L8.20205 17.6696C8.6415 18.1091 9.35518 18.1091 9.79463 17.6696L12.0446 15.4196C12.4841 14.9802 12.4841 14.2665 12.0446 13.827C11.6052 13.3876 10.8915 13.3876 10.452 13.827L10.1216 14.1575L10.1251 10.1251H14.1575L13.827 10.4556C13.3876 10.895 13.3876 11.6087 13.827 12.0481C14.2665 12.4876 14.9802 12.4876 15.4196 12.0481L17.6696 9.79814C18.1091 9.35869 18.1091 8.64502 17.6696 8.20557L15.4196 5.95557C14.9802 5.51611 14.2665 5.51611 13.827 5.95557C13.3876 6.39502 13.3876 7.10869 13.827 7.54814L14.1575 7.87861L10.1251 7.8751V3.84268L10.4556 4.17314C10.895 4.6126 11.6087 4.6126 12.0481 4.17314C12.4876 3.73369 12.4876 3.02002 12.0481 2.58057L9.79814 0.330566H9.79463Z"
                                                                            fill="black"
                                                                        />
                                                                    </g>
                                                                    <defs>
                                                                        <clipPath id="clip0_141_42">
                                                                            <rect width="18" height="18" fill="white" />
                                                                        </clipPath>
                                                                    </defs>
                                                                </svg>
                                                            </div>
                                                            <?php echo esc_html($result_page_ordering_htmls[$key]['name']);?>
                                                            <a class="ays_help" data-toggle="tooltip" title="<?php echo esc_html($result_page_ordering_htmls[$key]['message'])?>">
                                                                <i class="ays_fa ays_fa_info_circle"></i>
                                                            </a>
                                                        </div>
                                                        <?php unset($result_page_ordering_htmls[$key]);?>
                                                    <?php } ?>
                                                <?php } ?>
                                                <?php if(!empty($result_page_ordering_htmls)){ ?>
                                                    <?php foreach($result_page_ordering_htmls as $bin=>$key) { ?>
                                                        <div class="ays-quiz-order-item-result ays-quiz-order-item ays-quiz-dlg-dragHandle <?php if($bin=='conditions_message') { echo $conditions_bg; } else if($bin=='woo_block'){ echo $woo_bg;}?>">
                                                            <div class="">
                                                                <svg width="18" height="18" viewBox="0 0 18 18" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                                    <g clip-path="url(#clip0_141_42)">
                                                                        <path
                                                                            d="M9.79463 0.330566C9.35518 -0.108887 8.6415 -0.108887 8.20205 0.330566L5.95205 2.58057C5.5126 3.02002 5.5126 3.73369 5.95205 4.17314C6.3915 4.6126 7.10518 4.6126 7.54463 4.17314L7.8751 3.84268V7.8751H3.84268L4.17314 7.54463C4.6126 7.10518 4.6126 6.3915 4.17314 5.95205C3.73369 5.5126 3.02002 5.5126 2.58057 5.95205L0.330566 8.20205C-0.108887 8.6415 -0.108887 9.35518 0.330566 9.79463L2.58057 12.0446C3.02002 12.4841 3.73369 12.4841 4.17314 12.0446C4.6126 11.6052 4.6126 10.8915 4.17314 10.452L3.84268 10.1216L7.8751 10.1251V14.1575L7.54463 13.827C7.10518 13.3876 6.3915 13.3876 5.95205 13.827C5.5126 14.2665 5.5126 14.9802 5.95205 15.4196L8.20205 17.6696C8.6415 18.1091 9.35518 18.1091 9.79463 17.6696L12.0446 15.4196C12.4841 14.9802 12.4841 14.2665 12.0446 13.827C11.6052 13.3876 10.8915 13.3876 10.452 13.827L10.1216 14.1575L10.1251 10.1251H14.1575L13.827 10.4556C13.3876 10.895 13.3876 11.6087 13.827 12.0481C14.2665 12.4876 14.9802 12.4876 15.4196 12.0481L17.6696 9.79814C18.1091 9.35869 18.1091 8.64502 17.6696 8.20557L15.4196 5.95557C14.9802 5.51611 14.2665 5.51611 13.827 5.95557C13.3876 6.39502 13.3876 7.10869 13.827 7.54814L14.1575 7.87861L10.1251 7.8751V3.84268L10.4556 4.17314C10.895 4.6126 11.6087 4.6126 12.0481 4.17314C12.4876 3.73369 12.4876 3.02002 12.0481 2.58057L9.79814 0.330566H9.79463Z"
                                                                            fill="black"
                                                                        />
                                                                    </g>
                                                                    <defs>
                                                                        <clipPath id="clip0_141_42">
                                                                            <rect width="18" height="18" fill="white" />
                                                                        </clipPath>
                                                                    </defs>
                                                                </svg>
                                                            </div>
                                                            <?php echo esc_html($key['name']);?>
                                                            <a class="ays_help" data-toggle="tooltip" title="<?php echo esc_html($key['message'])?>">
                                                                <i class="ays_fa ays_fa_info_circle"></i>
                                                            </a>
                                                        </div>
                                                    <?php } ?>
                                                <?php }?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="d-flex ays-quiz-settings-order-buttons">
                                    <button type="button" name="ays_quiz_default_result_order" id="ays_quiz_default_result_order" class="button button-primary ays_default_sec_btn ays_default_button_class" data-message="<?php echo  __( "Are you sure you want to reset the ordering to the default configuration? Note: The ordering you have applied will be reset to the default one. To finalize and apply changes, click on the Save button.", 'quiz-maker' );?>">
                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><path d="M105.1 202.6c7.7-21.8 20.2-42.3 37.8-59.8c62.5-62.5 163.8-62.5 226.3 0L386.3 160 352 160c-17.7 0-32 14.3-32 32s14.3 32 32 32l111.5 0c0 0 0 0 0 0l.4 0c17.7 0 32-14.3 32-32l0-112c0-17.7-14.3-32-32-32s-32 14.3-32 32l0 35.2L414.4 97.6c-87.5-87.5-229.3-87.5-316.8 0C73.2 122 55.6 150.7 44.8 181.4c-5.9 16.7 2.9 34.9 19.5 40.8s34.9-2.9 40.8-19.5zM39 289.3c-5 1.5-9.8 4.2-13.7 8.2c-4 4-6.7 8.8-8.1 14c-.3 1.2-.6 2.5-.8 3.8c-.3 1.7-.4 3.4-.4 5.1L16 432c0 17.7 14.3 32 32 32s32-14.3 32-32l0-35.1 17.6 17.5c0 0 0 0 0 0c87.5 87.4 229.3 87.4 316.7 0c24.4-24.4 42.1-53.1 52.9-83.8c5.9-16.7-2.9-34.9-19.5-40.8s-34.9 2.9-40.8 19.5c-7.7 21.8-20.2 42.3-37.8 59.8c-62.5 62.5-163.8 62.5-226.3 0l-.1-.1L125.6 352l34.4 0c17.7 0 32-14.3 32-32s-14.3-32-32-32L48.4 288c-1.6 0-3.2 .1-4.8 .3s-3.1 .5-4.6 1z"/></svg>
                                        <?php echo esc_html__('Reset to Default', 'quiz-maker');?>
                                    </button>
                                </div>
                                <a href="https://ays-pro.com/wordpress/quiz-maker?utm_source=dashboard&utm_medium=quiz-free&utm_campaign=result-page-layout-ordering-<?php echo esc_attr( AYS_QUIZ_UTM_VERSION ); ?>" target="_blank" class="ays-quiz-new-upgrade-button-link">
                                    <div class="ays-quiz-new-upgrade-button-box">
                                        <div>
                                            <img src="<?php echo AYS_QUIZ_ADMIN_URL.'/images/icons/locked_24x24.svg'?>">
                                            <img src="<?php echo AYS_QUIZ_ADMIN_URL.'/images/icons/unlocked_24x24.svg'?>" class="ays-quiz-new-upgrade-button-hover">
                                        </div>
                                        <div class="ays-quiz-new-upgrade-button"><?php echo __("Upgrade", "quiz-maker"); ?></div>
                                    </div>
                                </a>
                            </div> 
                        </div>
                        <div class="ays-quiz-dashboard-main-wrap">
                            <div class="ays-quiz-tab-ordering nav-sub-tab-wrapper-container">
                                <h4><?php echo esc_html__('Button Order','quiz-maker')?></h4>
                                <div class="col-sm-12 only_pro" style="padding:20px;">
                                    <div class="pro_features" style="justify-content:flex-end;">

                                    </div>
                                    <p><?php echo esc_html__('Arrange the order of navigation buttons that appear on the quiz result page.','quiz-maker')?></p>
                                    <div class="form-group row nav-sub-tab-wrapper " style="margin-bottom: 20px;padding-left:20px">
                                        <a href="#ays_quiz_buttons_order_view_desktop" data-tab="ays_quiz_buttons_order_view_desktop" class="nav-tab nav-tab-active">
                                            <?php echo __('On desktop', 'quiz-maker'); ?>
                                        </a>
                                        <a href="#ays_quiz_buttons_order_view_mobile" data-tab="ays_quiz_buttons_order_view_mobile" class="nav-tab">
                                            <?php echo __('On mobile', 'quiz-maker'); ?>
                                        </a>
                                    </div>
                                    <div id="ays_quiz_buttons_order_view_desktop" class="form-group row ays-quiz-sub-tab-content ays-quiz-sub-tab-content-active">
                                        <div class="col-sm-12">
                                            <div class=" ays-quiz-buttons-order ays-quiz-settings-ui-sortable">
                                                <?php foreach($buttons_sort_desktop as $key) { ?>
                                                    <?php if(isset($buttons_ordering[$key])) { ?>
                                                        <div class="ays-quiz-order-item <?php if($key == 'save') { echo $save_button_bg;}?>" data-value="<?php echo $key;?>">
                                                            <div class="">
                                                                <svg width="18" height="18" viewBox="0 0 18 18" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                                    <g clip-path="url(#clip0_141_42)">
                                                                        <path
                                                                            d="M9.79463 0.330566C9.35518 -0.108887 8.6415 -0.108887 8.20205 0.330566L5.95205 2.58057C5.5126 3.02002 5.5126 3.73369 5.95205 4.17314C6.3915 4.6126 7.10518 4.6126 7.54463 4.17314L7.8751 3.84268V7.8751H3.84268L4.17314 7.54463C4.6126 7.10518 4.6126 6.3915 4.17314 5.95205C3.73369 5.5126 3.02002 5.5126 2.58057 5.95205L0.330566 8.20205C-0.108887 8.6415 -0.108887 9.35518 0.330566 9.79463L2.58057 12.0446C3.02002 12.4841 3.73369 12.4841 4.17314 12.0446C4.6126 11.6052 4.6126 10.8915 4.17314 10.452L3.84268 10.1216L7.8751 10.1251V14.1575L7.54463 13.827C7.10518 13.3876 6.3915 13.3876 5.95205 13.827C5.5126 14.2665 5.5126 14.9802 5.95205 15.4196L8.20205 17.6696C8.6415 18.1091 9.35518 18.1091 9.79463 17.6696L12.0446 15.4196C12.4841 14.9802 12.4841 14.2665 12.0446 13.827C11.6052 13.3876 10.8915 13.3876 10.452 13.827L10.1216 14.1575L10.1251 10.1251H14.1575L13.827 10.4556C13.3876 10.895 13.3876 11.6087 13.827 12.0481C14.2665 12.4876 14.9802 12.4876 15.4196 12.0481L17.6696 9.79814C18.1091 9.35869 18.1091 8.64502 17.6696 8.20557L15.4196 5.95557C14.9802 5.51611 14.2665 5.51611 13.827 5.95557C13.3876 6.39502 13.3876 7.10869 13.827 7.54814L14.1575 7.87861L10.1251 7.8751V3.84268L10.4556 4.17314C10.895 4.6126 11.6087 4.6126 12.0481 4.17314C12.4876 3.73369 12.4876 3.02002 12.0481 2.58057L9.79814 0.330566H9.79463Z"
                                                                            fill="black"
                                                                        />
                                                                    </g>
                                                                    <defs>
                                                                        <clipPath id="clip0_141_42">
                                                                            <rect width="18" height="18" fill="white" />
                                                                        </clipPath>
                                                                    </defs>
                                                                </svg>
                                                            </div>
                                                            <?php echo esc_html($buttons_ordering[$key]);?>
                                                            <?php if($key == 'save') { ?>
                                                                <a class="ays_help" data-toggle="tooltip" title="<?php echo esc_html__('Please note, that this option will be activated only if the Save and Resume addon is installed and activated and the Enable save and resume option is turned on in the Settings Tab of the given Quiz Edit page','quiz-maker')?>">
                                                                    <i class="ays_fa ays_fa_info_circle"></i>
                                                                </a>
                                                            <?php } else if($key == 'finish') { ?>
                                                                <a class="ays_help" data-toggle="tooltip" title="<?php echo esc_html__('Note: The Finish and the See Result button (displayed at the end of the quiz on the Result page) are the same.','quiz-maker')?>">
                                                                    <i class="ays_fa ays_fa_info_circle"></i>
                                                                </a>
                                                            <?php } ?>
                                                            <?php unset($buttons_ordering[$key]);?>
                                                        </div>
                                                    <?php } ?>
                                                <?php } ?>
                                                <?php if(!empty($buttons_ordering)) { ?>
                                                    <?php foreach ($buttons_ordering as $bin => $key){ ?>
                                                        <div class="ays-quiz-order-item <?php if($bin == 'save') { echo $save_button_bg;}?>">
                                                            <div class="">
                                                                <svg width="18" height="18" viewBox="0 0 18 18" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                                    <g clip-path="url(#clip0_141_42)">
                                                                        <path
                                                                            d="M9.79463 0.330566C9.35518 -0.108887 8.6415 -0.108887 8.20205 0.330566L5.95205 2.58057C5.5126 3.02002 5.5126 3.73369 5.95205 4.17314C6.3915 4.6126 7.10518 4.6126 7.54463 4.17314L7.8751 3.84268V7.8751H3.84268L4.17314 7.54463C4.6126 7.10518 4.6126 6.3915 4.17314 5.95205C3.73369 5.5126 3.02002 5.5126 2.58057 5.95205L0.330566 8.20205C-0.108887 8.6415 -0.108887 9.35518 0.330566 9.79463L2.58057 12.0446C3.02002 12.4841 3.73369 12.4841 4.17314 12.0446C4.6126 11.6052 4.6126 10.8915 4.17314 10.452L3.84268 10.1216L7.8751 10.1251V14.1575L7.54463 13.827C7.10518 13.3876 6.3915 13.3876 5.95205 13.827C5.5126 14.2665 5.5126 14.9802 5.95205 15.4196L8.20205 17.6696C8.6415 18.1091 9.35518 18.1091 9.79463 17.6696L12.0446 15.4196C12.4841 14.9802 12.4841 14.2665 12.0446 13.827C11.6052 13.3876 10.8915 13.3876 10.452 13.827L10.1216 14.1575L10.1251 10.1251H14.1575L13.827 10.4556C13.3876 10.895 13.3876 11.6087 13.827 12.0481C14.2665 12.4876 14.9802 12.4876 15.4196 12.0481L17.6696 9.79814C18.1091 9.35869 18.1091 8.64502 17.6696 8.20557L15.4196 5.95557C14.9802 5.51611 14.2665 5.51611 13.827 5.95557C13.3876 6.39502 13.3876 7.10869 13.827 7.54814L14.1575 7.87861L10.1251 7.8751V3.84268L10.4556 4.17314C10.895 4.6126 11.6087 4.6126 12.0481 4.17314C12.4876 3.73369 12.4876 3.02002 12.0481 2.58057L9.79814 0.330566H9.79463Z"
                                                                            fill="black"
                                                                        />
                                                                    </g>
                                                                    <defs>
                                                                        <clipPath id="clip0_141_42">
                                                                            <rect width="18" height="18" fill="white" />
                                                                        </clipPath>
                                                                    </defs>
                                                                </svg>
                                                            </div>
                                                            <?php echo esc_html($key);?>
                                                            <?php if($bin == 'save') { ?>
                                                                <a class="ays_help" data-toggle="tooltip" title="<?php echo esc_html__('Please note, that this option will be activated only if the Save and Resume addon is installed and activated and the Enable save and resume option is turned on in the Settings Tab of the given Quiz Edit page','quiz-maker')?>">
                                                                    <i class="ays_fa ays_fa_info_circle"></i>
                                                                </a>
                                                            <?php } else if($bin == 'finish') { ?>
                                                                <a class="ays_help" data-toggle="tooltip" title="<?php echo esc_html__('Note: The Finish and the See Result button (displayed at the end of the quiz on the Result page) are the same.','quiz-maker')?>">
                                                                    <i class="ays_fa ays_fa_info_circle"></i>
                                                                </a>
                                                            <?php } ?>
                                                        </div>
                                                    <?php } ?>
                                                <?php } ?>

                                            </div>
                                        </div>
                                    </div>
                                    <hr>
                                    <div class="d-flex ays-quiz-settings-order-buttons">
                                        <button type="button" name="ays_quiz_default_buttons_order" id="ays_quiz_default_buttons_order" class="button button-primary ays_default_sec_btn ays_default_button_class" data-message="<?php echo  __( "Are you sure you want to reset the ordering to the default configuration? Note: The ordering you have applied will be reset to the default one. To finalize and apply changes, click on the Save button.", 'quiz-maker' );?>">
                                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><path d="M105.1 202.6c7.7-21.8 20.2-42.3 37.8-59.8c62.5-62.5 163.8-62.5 226.3 0L386.3 160 352 160c-17.7 0-32 14.3-32 32s14.3 32 32 32l111.5 0c0 0 0 0 0 0l.4 0c17.7 0 32-14.3 32-32l0-112c0-17.7-14.3-32-32-32s-32 14.3-32 32l0 35.2L414.4 97.6c-87.5-87.5-229.3-87.5-316.8 0C73.2 122 55.6 150.7 44.8 181.4c-5.9 16.7 2.9 34.9 19.5 40.8s34.9-2.9 40.8-19.5zM39 289.3c-5 1.5-9.8 4.2-13.7 8.2c-4 4-6.7 8.8-8.1 14c-.3 1.2-.6 2.5-.8 3.8c-.3 1.7-.4 3.4-.4 5.1L16 432c0 17.7 14.3 32 32 32s32-14.3 32-32l0-35.1 17.6 17.5c0 0 0 0 0 0c87.5 87.4 229.3 87.4 316.7 0c24.4-24.4 42.1-53.1 52.9-83.8c5.9-16.7-2.9-34.9-19.5-40.8s-34.9 2.9-40.8 19.5c-7.7 21.8-20.2 42.3-37.8 59.8c-62.5 62.5-163.8 62.5-226.3 0l-.1-.1L125.6 352l34.4 0c17.7 0 32-14.3 32-32s-14.3-32-32-32L48.4 288c-1.6 0-3.2 .1-4.8 .3s-3.1 .5-4.6 1z"/></svg>
                                            <?php echo esc_html__('Reset to Default', 'quiz-maker');?>
                                        </button>
                                    </div> 
                                    <a href="https://ays-pro.com/wordpress/quiz-maker?utm_source=dashboard&utm_medium=quiz-free&utm_campaign=button-ordering-<?php echo esc_attr( AYS_QUIZ_UTM_VERSION ); ?>" target="_blank" class="ays-quiz-new-upgrade-button-link">
                                        <div class="ays-quiz-new-upgrade-button-box">
                                            <div>
                                                <img src="<?php echo AYS_QUIZ_ADMIN_URL.'/images/icons/locked_24x24.svg'?>">
                                                <img src="<?php echo AYS_QUIZ_ADMIN_URL.'/images/icons/unlocked_24x24.svg'?>" class="ays-quiz-new-upgrade-button-hover">
                                            </div>
                                            <div class="ays-quiz-new-upgrade-button"><?php echo __("Upgrade", "quiz-maker"); ?></div>
                                        </div>
                                    </a>
                                </div>         
                            </div>
                        </div>
                        <hr>
                    </div>
                </div>
            </div>
            <hr/>
            <div style="position:sticky;padding:15px 0px;bottom:0;">
            <?php
                wp_nonce_field('settings_action', 'settings_action');

                $other_attributes = array(
                    'title' => 'Ctrl + s',
                    'data-toggle' => 'tooltip',
                    'data-delay'=> '{"show":"1000"}'
                );
                
                submit_button(__('Save changes', 'quiz-maker'), 'primary ays-quiz-loader-banner', 'ays_submit', true, $other_attributes);
                echo $loader_iamge;
            ?>
            </div>
        </form>

        <div class="ays-modal" id="pro-features-popup-modal">
            <div class="ays-modal-content">
                <!-- Modal Header -->
                <div class="ays-modal-header">
                    <span class="ays-close-pro-popup">&times;</span>
                    <!-- <h2></h2> -->
                </div>

                <!-- Modal body -->
                <div class="ays-modal-body">
                   <div class="row">
                        <div class="col-sm-6 pro-features-popup-modal-left-section">
                        </div>
                        <div class="col-sm-6 pro-features-popup-modal-right-section">
                           <div class="pro-features-popup-modal-right-box">
                                <div class="pro-features-popup-modal-right-box-icon"><i class="ays_fa ays_fa_lock"></i></div>

                                <div class="pro-features-popup-modal-right-box-title"></div>

                                <div class="pro-features-popup-modal-right-box-content"></div>

                                <div class="pro-features-popup-modal-right-box-button">
                                    <a href="#" class="pro-features-popup-modal-right-box-link" target="_blank"></a>
                                </div>

                                <div class="pro-features-popup-modal-right-box-footer-text">
                                    <span class="ays_quiz_small_hint_text_for_message_variables"><?php echo esc_html__( "One-time payment", 'quiz-maker' ); ?></span>
                                </div>
                           </div>
                        </div>
                    </div>
                </div>

                <!-- Modal footer -->
                <div class="ays-modal-footer" style="display:none">
                </div>
            </div>
        </div>

    </div>
</div>
