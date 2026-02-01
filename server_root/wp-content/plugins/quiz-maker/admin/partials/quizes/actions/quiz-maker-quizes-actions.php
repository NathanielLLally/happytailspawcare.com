<?php

if(isset($_GET['ays_quiz_tab'])){
    $ays_quiz_tab = sanitize_text_field( $_GET['ays_quiz_tab'] );
}else{
    $ays_quiz_tab = 'tab1';
}
$action = (isset($_GET['action'])) ? sanitize_key( $_GET['action'] ) : '';
$heading = '';
$loader_iamge = '';

$id = (isset($_GET['quiz'])) ? absint( intval( $_GET['quiz'] ) ) : null;

$user_id = get_current_user_id();
$user = get_userdata($user_id);
$author = array(
    'id'                                        => $user->ID,
    'name'                                      => $user->data->display_name
);
$quiz = array(
    'title'                                     => '',
    'description'                               => '',
    'quiz_image'                                => '',
    'quiz_category_id'                          => 1,
    'question_ids'                              => '',
    'published'                                 => 1,
    'quiz_url'                                  => '',
);
$options = array(
    'quiz_theme'                                => 'classic_light',
    'color'                                     => '#5d6cf9',
    'bg_color'                                  => '#fff',
    'text_color'                                => '#000000',
    'height'                                    => 450,
    'width'                                     => 800,
    'timer'                                     => 100,
    'information_form'                          => 'disable',
    'form_name'                                 => '',
    'form_email'                                => '',
    'form_phone'                                => '',
    'enable_logged_users'                       => 'off',
    'image_width'                               => '',
    'image_height'                              => '',
    'enable_correction'                         => 'off',
    'enable_questions_counter'                  => 'on',
    'limit_users'                               => 'off',
    'limitation_message'                        => '',
    'redirect_url'                              => '',
    'redirection_delay'                         => '',
    'enable_progress_bar'                       => 'on',
    'randomize_questions'                       => 'off',
    'randomize_answers'                         => 'off',
    'enable_questions_result'                   => 'on',
    'enable_average_statistical'                => 'on',
    'enable_next_button'                        => 'on',
    'enable_previous_button'                    => 'on',
    'custom_css'                                => '',
    'enable_restriction_pass'                   => 'off',
    'restriction_pass_message'                  => '',
    'user_role'                                 => '',
    'result_text'                               => '',
    'enable_result'                             => 'off',
    'enable_timer'                              => 'off',
    'enable_pass_count'                         => 'off',
    'enable_quiz_rate'                          => 'off',
    'enable_rate_avg'                           => 'off',
    'enable_rate_comments'                      => 'off',
    'hide_score'                                => 'off',
    'rate_form_title'                           => '',
    'enable_box_shadow'                         => 'on',
    'box_shadow_color'                          => '#c9c9c9',
    'quiz_border_radius'                        => '8',
    'quiz_bg_image'                             => '',
    'enable_border'                             => 'off',
    'quiz_border_width'                         => '1',
    'quiz_border_style'                         => 'solid',
    'quiz_border_color'                         => '#000',
    'quiz_timer_in_title'                       => 'off',
    'enable_restart_button'                     => 'on',
    'quiz_loader'                               => 'default',
    'create_date'                               => current_time( 'mysql' ),
    'author'                                    => $author,
    'autofill_user_data'                        => 'off',
    'quest_animation'                           => 'none',
    'form_title'                                => '',
    'enable_bg_music'                           => 'off',
    'quiz_bg_music'                             => '',
    'answers_font_size'                         => '15',
    'show_create_date'                          => 'off',
    'show_author'                               => 'off',
    'enable_early_finish'                       => 'off',
    'answers_rw_texts'                          => 'on_passing',
    'disable_store_data'                        => 'off',
    'enable_background_gradient'                => 'off',
    'background_gradient_color_1'               => '#000',
    'background_gradient_color_2'               => '#fff',
    'quiz_gradient_direction'                   => 'vertical',
    'redirect_after_submit'                     => 'off',
    'submit_redirect_url'                       => '',
    'submit_redirect_delay'                     => '',
    'progress_bar_style'                        => 'third',
    'enable_exit_button'                        => 'off',
    'exit_redirect_url'                         => '',
    'image_sizing'                              => 'cover',
    'quiz_bg_image_position'                    => 'center center',
    'custom_class'                              => '',
    'enable_social_buttons'                     => 'off',
    'enable_social_links'                       => 'off',
    'social_links' => array(
        'linkedin_link'     => '',
        'facebook_link'     => '',
        'twitter_link'      => '',
        'vkontakte_link'    => '',
        'instagram_link'    => '',
        'youtube_link'      => '',
        'behance_link'      => '',
    ),
    'show_quiz_title'                           => 'on',
    'show_quiz_desc'                            => 'on',
    'show_login_form'                           => 'off',
    'mobile_max_width'                          => '',
    'limit_users_by'                            => 'ip',
	'activeInterval'                            => '',
	'deactiveInterval'                          => '',
	'active_date_check'                         => 'off',
	'active_date_pre_start_message'             => __("The quiz will be available soon!", 'quiz-maker'),
    'active_date_message'                       => __("The quiz has expired!", 'quiz-maker'),
	'explanation_time'                          => '4',
	'enable_clear_answer'                       => 'off',
	'show_category'                             => 'off',
	'show_question_category'                    => 'off',
	'display_score'                             => 'by_percantage',
	'enable_rw_asnwers_sounds'                  => 'off',
    'ans_right_wrong_icon'                      => 'none',
    'quiz_bg_img_in_finish_page'                => 'off',
    'finish_after_wrong_answer'                 => 'off',
    'enable_enter_key'                          => 'on',
    'buttons_text_color'                        => '#ffffff',
    'buttons_position'                          => 'center',
    'show_questions_explanation'                => 'on_results_page',
    'enable_audio_autoplay'                     => 'off',
    'buttons_size'                              => 'large',
    'buttons_font_size'                         => '18',
    'buttons_width'                             => '',
    'buttons_left_right_padding'                => '36',
    'buttons_top_bottom_padding'                => '14',
    'buttons_border_radius'                     => '8',
    'enable_leave_page'                         => 'on',
    'enable_tackers_count'                      => 'off',
    'pass_score'                                => '0',
    'question_font_size'                        => '16',
    'quiz_width_by_percentage_px'               => 'pixels',
    'questions_hint_icon_or_text'               => 'default',
    'enable_early_finsh_comfirm_box'            => 'on',
    'enable_questions_ordering_by_cat'          => 'off',
    'show_schedule_timer'                       => 'off',
    'show_timer_type'                           => 'countdown',
    'quiz_loader_text_value'                    => '',
    'hide_correct_answers'                      => 'off',
    'show_information_form'                     => 'on',
    'quiz_loader_custom_gif'                    => '',
    'disable_hover_effect'                      => 'off',
    'quiz_loader_custom_gif_width'              => 100,
    'show_answers_numbering'                    => 'none',
    'quiz_title_transformation'                 => 'uppercase',
    'quiz_box_shadow_x_offset'                  => 0,
    'quiz_box_shadow_y_offset'                  => 0,
    'quiz_box_shadow_z_offset'                  => 15,
    'quiz_question_text_alignment'              => 'center',
    'quiz_arrow_type'                           => 'default',
    'quiz_show_wrong_answers_first'             => 'off',
    'quiz_display_all_questions'                => 'off',
    'quiz_timer_red_warning'                    => 'off',
    'quiz_schedule_timezone'                    => get_option( 'timezone_string' ),
    'questions_hint_button_value'               => '',
    'quiz_tackers_message'                      => __( "This quiz is expired!", 'quiz-maker' ),
    'quiz_enable_linkedin_share_button'         => 'on',
    'quiz_enable_facebook_share_button'         => 'on',
    'quiz_enable_twitter_share_button'          => 'on',
    'quiz_make_responses_anonymous'             => 'off',
    'quiz_make_all_review_link'                 => 'off',
    'show_questions_numbering'                  => 'none',
    'quiz_message_before_timer'                 => '',
    'display_fields_labels'                     => 'off',
    'enable_full_screen_mode'                   => 'off',
    'quiz_enable_password_visibility'           => 'off',
    'question_mobile_font_size'                 => 16,
    'answers_mobile_font_size'                  => 15,
    'social_buttons_heading'                    => '',
    'quiz_enable_vkontakte_share_button'        => 'on',
    'answers_border'                            => 'on',
    'answers_border_width'                      => '1',
    'answers_border_style'                      => 'solid',
    'answers_border_color'                      => '#dddddd',
    'ans_img_height'                            => 150,
    'show_answers_caption'                      => 'on',
    'ans_img_caption_position'                  => 'bottom',
    'social_links_heading'                      => '',
    'quiz_enable_question_category_description' => 'off',
    'answers_margin'                            => '12',
    'quiz_message_before_redirect_timer'        => '',
    'buttons_mobile_font_size'                  => 18,
    'answers_box_shadow'                        => 'off',
    'answers_box_shadow_color'                  => '#000',
    'quiz_answer_box_shadow_x_offset'           => 0,
    'quiz_answer_box_shadow_y_offset'           => 0,
    'quiz_answer_box_shadow_z_offset'           => 10,
    'quiz_create_author'                        => $user_id,
    'quiz_enable_title_text_shadow'             => "off",
    'quiz_title_text_shadow_color'              => "#333",
    'quiz_title_text_shadow_x_offset'           => 2,
    'quiz_title_text_shadow_y_offset'           => 2,
    'quiz_title_text_shadow_z_offset'           => 2,
    'quiz_title_font_size'                      => 28,
    'quiz_title_mobile_font_size'               => 20,
    'quiz_password_width'                       => "",
    'quiz_review_placeholder_text'              => "",
    'quiz_enable_results_toggle'                => "off",
    'quiz_review_thank_you_message'             => "",
    'quiz_review_enable_comment_field'          => "on",
    'quest_explanation_font_size'               => "16",
    'quest_explanation_mobile_font_size'        => "16",
    'wrong_answers_font_size'                   => "16",
    'wrong_answers_mobile_font_size'            => "16",
    'right_answers_font_size'                   => "16",
    'right_answers_mobile_font_size'            => "16",
    'note_text_font_size'                       => "14",
    'note_text_mobile_font_size'                => "14",
    'quiz_questions_numbering_by_category'      => "off",
    'quiz_enable_custom_texts_for_buttons'      => "off",
    'quiz_custom_texts_start_button'            => "Start",
    'quiz_custom_texts_next_button'             => "Next",
    'quiz_custom_texts_prev_button'             => "Prev",
    'quiz_custom_texts_clear_button'            => "Clear",
    'quiz_custom_texts_finish_button'           => "Finish",
    'quiz_custom_texts_see_results_button'      => "See Result",
    'quiz_custom_texts_restart_quiz_button'     => "Restart quiz",
    'quiz_custom_texts_send_feedback_button'    => "Send feedback",
    'quiz_custom_texts_load_more_button'        => "Load more",
    'quiz_custom_texts_exit_button'             => "Exit",
    'quiz_custom_texts_check_button'            => "Check",
    'quiz_custom_texts_login_button'            => "Log In",
    'quiz_enable_quiz_category_description'     => "off",
    'quiz_admin_note_text_transform'            => "none",
    'quiz_quest_explanation_text_transform'     => "none",
    'quiz_right_answer_text_transform'          => "none",
    'quiz_wrong_answer_text_transform'          => "none",
    'quiz_admin_note_text_decoration'           => "none",
    'quiz_quest_explanation_text_decoration'    => "none",
    'quiz_right_answers_text_decoration'        => "none",
    'quiz_wrong_answers_text_decoration'        => "none",
    'quiz_admin_note_letter_spacing'            => 0,
    'quiz_bg_img_during_the_quiz'               => "off",
    'quiz_quest_explanation_letter_spacing'     => 0,
    'quiz_right_answers_letter_spacing'         => 0,
    'quiz_wrong_answers_letter_spacing'         => 0,
    'quiz_admin_note_font_weight'               => "normal",
    'quiz_quest_explanation_font_weight'        => "normal",
    'quiz_right_answers_font_weight'            => "normal",
    'quiz_wrong_answers_font_weight'            => "normal",
    'quiz_section_collapse_flag'                => "off",
    'quiz_content_max_width'                    => 90,
    'quiz_content_mobile_max_width'             => 90,
    'quiz_timer_warning_text_color'             => "#ff0000",
    'quiz_enable_default_hide_results_toggle'   => "off",
    'quiz_show_restart_button_on_quiz_fail'     => "off",
    'quiz_enable_keyboard_navigation'           => 'on',
    'quiz_admin_note_mobile_text_transform'     => 'none',
    'quiz_quest_explanation_mobile_text_transform' => 'none',
    'quiz_right_answer_mobile_text_transform'   => "none",
    'quiz_wrong_answer_mobile_text_transform'   => "none",
    'quiz_admin_note_mobile_text_decoration'    => "none",
    'quiz_dont_show_quiz'                       => "off",
    'enable_questions_reporting'                => "off",
    'quiz_quest_explanation_mobile_text_decoration' => "none",
    'quiz_wrong_answers_mobile_text_decoration' => "none",
    'quiz_right_answers_mobile_letter_spacing'  => 0,
    'quiz_wrong_answers_mobile_letter_spacing'  => 0,

);

$quiz_intervals_default = array(
    array(
        'interval_min'      => '0',
        'interval_max'      => '25',
        'interval_text'     => '',
        'interval_image'    => '',
        'interval_keyword'  => 'A',
    ),
    array(
        'interval_min'      => '26',
        'interval_max'      => '50',
        'interval_text'     => '',
        'interval_image'    => '',
        'interval_keyword'  => 'B',
    ),
    array(
        'interval_min'      => '51',
        'interval_max'      => '75',
        'interval_text'     => '',
        'interval_image'    => '',
        'interval_keyword'  => 'C',
    ),
    array(
        'interval_min'      => '76',
        'interval_max'      => '100',
        'interval_text'     => '',
        'interval_image'    => '',
        'interval_keyword'  => 'D',
    ),
);

$question_ids = '';
$question_id_array = array();
$quiz_intervals = 3;
switch ($action) {
    case 'add':
        $heading = __('Add new quiz', 'quiz-maker');
        break;
    case 'edit':
        $heading = __('Edit quiz', 'quiz-maker');
        $quiz = $this->quizes_obj->get_quiz_by_id($id);
        if (isset( $quiz['options'] ) && $quiz['options'] != "") {
            $options = json_decode($quiz['options'], true);
        } 
        $question_ids = (isset( $quiz['question_ids'] ) && $quiz['question_ids'] != "") ? $quiz['question_ids'] : "";
        $question_id_array = explode(',', $question_ids);
        $question_id_array = ($question_id_array[0] == '' && count($question_id_array) == 1) ? array() : $question_id_array;
        break;
}

$quiz_title = (isset( $quiz['title'] ) && $quiz['title'] != "") ? stripslashes( esc_attr($quiz['title']) ) : ""; 
$quiz_description = (isset( $quiz['description'] ) && $quiz['description'] != "") ? stripslashes(wpautop($quiz['description'])) : "";
$quiz_published = (isset( $quiz['published'] ) && $quiz['published'] != "") ? esc_attr( absint( $quiz['published'] ) ) : 1;

$loader_iamge = "<span class='display_none ays_quiz_loader_box'><img src='". AYS_QUIZ_ADMIN_URL ."/images/loaders/loading.gif'></span>";

$questions = $this->quizes_obj->get_published_questions();
$question_max_id = $this->get_max_id('questions');
$total_questions_count = $this->quizes_obj->published_questions_record_count();
$quiz_categories = $this->quizes_obj->get_quiz_categories();
$question_categories = $this->get_questions_categories();
$question_categories_array = array();
foreach($question_categories as $cat){
    $question_categories_array[$cat['id']] = $cat['title'];
}


$settings_options = ($this->settings_obj->ays_get_setting('options'));
if($settings_options){
    $settings_options = json_decode(stripcslashes($settings_options), true);
}else{
    $settings_options = array();
}

// Buttons Text
$buttons_texts_res      = ($this->settings_obj->ays_get_setting('buttons_texts') === false) ? json_encode(array()) : $this->settings_obj->ays_get_setting('buttons_texts');
$buttons_texts          = json_decode( stripcslashes( $buttons_texts_res ) , true);


$start_button           = (isset($buttons_texts['start_button']) && $buttons_texts['start_button'] != '') ? stripslashes( esc_attr( $buttons_texts['start_button'] ) ) : 'Start';
$next_button            = (isset($buttons_texts['next_button']) && $buttons_texts['next_button'] != '') ? stripslashes( esc_attr( $buttons_texts['next_button'] ) ) : 'Next';
$previous_button        = (isset($buttons_texts['previous_button']) && $buttons_texts['previous_button'] != '') ? stripslashes( esc_attr( $buttons_texts['previous_button'] ) ) : 'Prev' ;
$clear_button           = (isset($buttons_texts['clear_button']) && $buttons_texts['clear_button'] != '') ? stripslashes( esc_attr( $buttons_texts['clear_button'] ) ) : 'Clear' ;
$finish_button          = (isset($buttons_texts['finish_button']) && $buttons_texts['finish_button'] != '') ? stripslashes( esc_attr( $buttons_texts['finish_button'] ) ) : 'Finish' ;
$see_result_button      = (isset($buttons_texts['see_result_button']) && $buttons_texts['see_result_button'] != '') ? stripslashes( esc_attr( $buttons_texts['see_result_button'] ) ) : 'See Result' ;
$restart_quiz_button    = (isset($buttons_texts['restart_quiz_button']) && $buttons_texts['restart_quiz_button'] != '') ? stripslashes( esc_attr( $buttons_texts['restart_quiz_button'] ) ) : 'Restart quiz' ;
$send_feedback_button   = (isset($buttons_texts['send_feedback_button']) && $buttons_texts['send_feedback_button'] != '') ? esc_attr(stripslashes($buttons_texts['send_feedback_button'])) : 'Send feedback' ;
$load_more_button       = (isset($buttons_texts['load_more_button']) && $buttons_texts['load_more_button'] != '') ? esc_attr(stripslashes($buttons_texts['load_more_button'])) : 'Load more' ;
$gen_exit_button        = (isset($buttons_texts['exit_button']) && $buttons_texts['exit_button'] != '') ? esc_attr(stripslashes($buttons_texts['exit_button'])) : 'Exit' ;
$gen_check_button       = (isset($buttons_texts['check_button']) && $buttons_texts['check_button'] != '') ? esc_attr(stripslashes($buttons_texts['check_button'])) : 'Check' ;
$gen_login_button       = (isset($buttons_texts['login_button']) && $buttons_texts['login_button'] != '') ? esc_attr(stripslashes($buttons_texts['login_button'])) : 'Log In' ;

$right_answer_sound = (isset($settings_options['right_answer_sound']) && $settings_options['right_answer_sound'] != '') ? true : false;
$wrong_answer_sound = (isset($settings_options['wrong_answer_sound']) && $settings_options['wrong_answer_sound'] != '') ? true : false;
$rw_answers_sounds_status = false;
if($right_answer_sound && $wrong_answer_sound){
    $rw_answers_sounds_status = true;
}

// WP Editor height
$quiz_wp_editor_height = (isset($settings_options['quiz_wp_editor_height']) && $settings_options['quiz_wp_editor_height'] != '') ? absint( sanitize_text_field($settings_options['quiz_wp_editor_height']) ) : 100;

// Question title view
$quiz_question_title_view = (isset($settings_options['quiz_question_title_view']) && sanitize_text_field( $settings_options['quiz_question_title_view'] ) != "") ? stripslashes( esc_attr($settings_options['quiz_question_title_view']) ) : 'question_title';

if (isset($_POST['ays_submit']) || isset($_POST['ays_submit_top'])) {
    $_POST['id'] = $id;
    $this->quizes_obj->add_or_edit_quizes();
}
if (isset($_POST['ays_apply_top']) || isset($_POST['ays_apply'])) {
    $_POST["id"] = $id;
    $_POST['ays_change_type'] = 'apply';
    $this->quizes_obj->add_or_edit_quizes();
}

if (isset($_POST['ays_quiz_cancel_top']) || isset($_POST['ays_quiz_cancel'])) {
    unset($_GET['page']);
    $url = remove_query_arg( array_keys($_GET) );
    wp_safe_redirect( $url );
    exit;
}

$next_quiz_id = "";
$prev_quiz_id = "";
if ( isset( $id ) && !is_null( $id ) ) {
    $next_quiz = $this->get_next_or_prev_row_by_id( $id, "next", "aysquiz_quizes" );
    $next_quiz_id = (isset( $next_quiz['id'] ) && $next_quiz['id'] != "") ? absint( $next_quiz['id'] ) : null;

    $prev_quiz = $this->get_next_or_prev_row_by_id( $id, "prev", "aysquiz_quizes" );
    $prev_quiz_id = (isset( $prev_quiz['id'] ) && $prev_quiz['id'] != "") ? absint( $prev_quiz['id'] ) : null;
}

$wp_general_settings_url = admin_url( 'options-general.php' );
$quiz_category_page_url  = admin_url( 'admin.php?page=quiz-maker-quiz-categories' );
$question_add_new_page_url  = admin_url( 'admin.php?page=quiz-maker-questions&action=add' );

$quiz_message_vars = array(
    "%%user_name%%"                                 => __("User Name", 'quiz-maker'),
    "%%user_email%%"                                => __("User Email", 'quiz-maker'),
    "%%user_phone%%"                                => __("User Phone", 'quiz-maker'),
    "%%quiz_name%%"                                 => __("Quiz Title", 'quiz-maker'),
    "%%score%%"                                     => __("Score", 'quiz-maker'),
    "%%current_date%%"                              => __("Current Date", 'quiz-maker'),
    "%%results_by_cats%%"                           => __("Results by question categories", 'quiz-maker'),
    "%%avg_score%%"                                 => __("Average score", 'quiz-maker'),
    "%%avg_rate%%"                                  => __("Average Rate", 'quiz-maker'),
    "%%user_pass_time%%"                            => __("User passed time", 'quiz-maker'),
    "%%quiz_time%%"                                 => __("Quiz time", 'quiz-maker'),
    "%%avg_score_by_category%%"                     => __("Average score by the question category", 'quiz-maker'),
    "%%user_corrects_count%%"                       => __("Correct answers count", 'quiz-maker'),
    "%%wrong_answers_count%%"                       => __("Wrong answers count (skipped questions are included)", 'quiz-maker'),
    "%%only_wrong_answers_count%%"                  => __("Only wrong answers count", 'quiz-maker'),
    "%%skipped_questions_count%%"                   => __("Unanswered questions count", 'quiz-maker'),
    "%%score_by_answered_questions%%"               => __("Score by answered questions", 'quiz-maker'),
    "%%user_first_name%%"                           => __("User's First Name", 'quiz-maker'),
    "%%user_last_name%%"                            => __("User's Last Name", 'quiz-maker'),
    "%%user_nickname%%"                             => __("User's Nick Name", 'quiz-maker'),
    "%%user_display_name%%"                         => __("User's Display Name", 'quiz-maker'),
    "%%user_wordpress_email%%"                      => __("User's WordPress profile email", 'quiz-maker'),
    "%%user_wordpress_roles%%"                      => __("User's WordPress Roles", 'quiz-maker'),
    "%%questions_count%%"                           => __("Questions count", 'quiz-maker'),
    "%%quiz_creation_date%%"                        => __("Quiz creation date", 'quiz-maker'),
    "%%current_quiz_author%%"                       => __("Quiz Author", 'quiz-maker'),
    "%%current_quiz_page_link%%"                    => __("Quiz page link", 'quiz-maker'),
    "%%current_user_ip%%"                           => __("User's IP Address", 'quiz-maker'),
    "%%current_quiz_author_email%%"                 => __("Quiz Author Email", 'quiz-maker'),
    "%%current_quiz_author_nickname%%"              => __("Quiz Author Nickname", 'quiz-maker'),
    "%%current_quiz_author_website_url%%"           => __("Quiz Author Website", 'quiz-maker'),
    "%%admin_email%%"                               => __("Admin Email", 'quiz-maker'),
    "%%home_page_url%%"                             => __("Home page URL", 'quiz-maker'),
    "%%quiz_id%%"                                   => __("Quiz ID", 'quiz-maker'),
    "%%user_id%%"                                   => __("User ID", 'quiz-maker'),
    "%%result_id%%"                                 => __("User result ID", 'quiz-maker'),
    "%%current_quiz_question_categories_count%%"    => __("Question cateogries count", 'quiz-maker'),
    "%%site_title%%"                                => __("Site title", 'quiz-maker'),
);

$quiz_message_vars_timer = array(
    "%%time%%"                                      => __("Time", 'quiz-maker'),
    "%%quiz_name%%"                                 => __("Quiz Title", 'quiz-maker'),
    "%%user_first_name%%"                           => __("User's First Name", 'quiz-maker'),
    "%%user_last_name%%"                            => __("User's Last Name", 'quiz-maker'),
    "%%questions_count%%"                           => __("Questions count", 'quiz-maker'),
    "%%user_nickname%%"                             => __("User's Nick Name", 'quiz-maker'),
    "%%user_display_name%%"                         => __("User's Display Name", 'quiz-maker'),
    "%%user_wordpress_email%%"                      => __("User's WordPress profile email", 'quiz-maker'),
    "%%user_wordpress_roles%%"                      => __("User's WordPress Roles", 'quiz-maker'),
    "%%quiz_creation_date%%"                        => __("Quiz creation date", 'quiz-maker'),
    "%%current_quiz_author%%"                       => __("Quiz Author", 'quiz-maker'),
    "%%current_user_ip%%"                           => __("User's IP Address", 'quiz-maker'),
    "%%current_quiz_author_email%%"                 => __("Quiz Author Email", 'quiz-maker'),
    "%%current_quiz_author_nickname%%"              => __("Quiz Author Nickname", 'quiz-maker'),
    "%%current_quiz_author_website_url%%"           => __("Quiz Author Website", 'quiz-maker'),
    "%%admin_email%%"                               => __("Admin Email", 'quiz-maker'),
    "%%home_page_url%%"                             => __("Home page URL", 'quiz-maker'),
    "%%quiz_id%%"                                   => __("Quiz ID", 'quiz-maker'),
    "%%user_id%%"                                   => __("User ID", 'quiz-maker'),
    "%%site_title%%"                                => __("Site title", 'quiz-maker'),
);

$quiz_message_vars_information_form = array(
    "%%quiz_name%%"                                 => __("Quiz Title", 'quiz-maker'),
    "%%user_first_name%%"                           => __("User's First Name", 'quiz-maker'),
    "%%user_last_name%%"                            => __("User's Last Name", 'quiz-maker'),
    "%%questions_count%%"                           => __("Questions count", 'quiz-maker'),
    "%%user_nickname%%"                             => __("User's Nick Name", 'quiz-maker'),
    "%%user_display_name%%"                         => __("User's Display Name", 'quiz-maker'),
    "%%user_wordpress_email%%"                      => __("User's WordPress profile email", 'quiz-maker'),
    "%%user_wordpress_roles%%"                      => __("User's WordPress Roles", 'quiz-maker'),
    "%%quiz_creation_date%%"                        => __("Quiz creation date", 'quiz-maker'),
    "%%current_quiz_author%%"                       => __("Quiz Author", 'quiz-maker'),
    "%%current_user_ip%%"                           => __("User's IP Address", 'quiz-maker'),
    "%%current_quiz_author_email%%"                 => __("Quiz Author Email", 'quiz-maker'),
    "%%current_quiz_author_nickname%%"              => __("Quiz Author Nickname", 'quiz-maker'),
    "%%current_quiz_author_website_url%%"           => __("Quiz Author Website", 'quiz-maker'),
    "%%admin_email%%"                               => __("Admin Email", 'quiz-maker'),
    "%%home_page_url%%"                             => __("Home page URL", 'quiz-maker'),
    "%%quiz_id%%"                                   => __("Quiz ID", 'quiz-maker'),
    "%%user_id%%"                                   => __("User ID", 'quiz-maker'),
    "%%site_title%%"                                => __("Site title", 'quiz-maker'),
);

$quiz_message_vars_description = array(
    "%%quiz_name%%"                                 => __("Quiz Title", 'quiz-maker'),
    "%%user_first_name%%"                           => __("User's First Name", 'quiz-maker'),
    "%%user_last_name%%"                            => __("User's Last Name", 'quiz-maker'),
    "%%questions_count%%"                           => __("Questions count", 'quiz-maker'),
    "%%user_nickname%%"                             => __("User's Nick Name", 'quiz-maker'),
    "%%user_display_name%%"                         => __("User's Display Name", 'quiz-maker'),
    "%%user_wordpress_email%%"                      => __("User's WordPress profile email", 'quiz-maker'),
    "%%user_wordpress_roles%%"                      => __("User's WordPress Roles", 'quiz-maker'),
    "%%quiz_creation_date%%"                        => __("Quiz creation date", 'quiz-maker'),
    "%%current_quiz_author%%"                       => __("Quiz Author", 'quiz-maker'),
    "%%current_user_ip%%"                           => __("User's IP Address", 'quiz-maker'),
    "%%current_quiz_author_email%%"                 => __("Quiz Author Email", 'quiz-maker'),
    "%%current_quiz_author_nickname%%"              => __("Quiz Author Nickname", 'quiz-maker'),
    "%%current_quiz_author_website_url%%"           => __("Quiz Author Website", 'quiz-maker'),
    "%%admin_email%%"                               => __("Admin Email", 'quiz-maker'),
    "%%home_page_url%%"                             => __("Home page URL", 'quiz-maker'),
    "%%quiz_id%%"                                   => __("Quiz ID", 'quiz-maker'),
    "%%user_id%%"                                   => __("User ID", 'quiz-maker'),
    "%%site_title%%"                                => __("Site title", 'quiz-maker'),
);

$quiz_message_vars_limitation_message = array(
    "%%quiz_name%%"                                 => __("Quiz Title", 'quiz-maker'),
    "%%user_first_name%%"                           => __("User's First Name", 'quiz-maker'),
    "%%user_last_name%%"                            => __("User's Last Name", 'quiz-maker'),
    "%%questions_count%%"                           => __("Questions count", 'quiz-maker'),
    "%%user_nickname%%"                             => __("User's Nick Name", 'quiz-maker'),
    "%%user_display_name%%"                         => __("User's Display Name", 'quiz-maker'),
    "%%user_wordpress_email%%"                      => __("User's WordPress profile email", 'quiz-maker'),
    "%%user_wordpress_roles%%"                      => __("User's WordPress Roles", 'quiz-maker'),
    "%%quiz_creation_date%%"                        => __("Quiz creation date", 'quiz-maker'),
    "%%current_quiz_author%%"                       => __("Quiz Author", 'quiz-maker'),
    "%%current_user_ip%%"                           => __("User's IP Address", 'quiz-maker'),
    "%%current_quiz_author_email%%"                 => __("Quiz Author Email", 'quiz-maker'),
    "%%current_quiz_author_nickname%%"              => __("Quiz Author Nickname", 'quiz-maker'),
    "%%current_quiz_author_website_url%%"           => __("Quiz Author Website", 'quiz-maker'),
    "%%admin_email%%"                               => __("Admin Email", 'quiz-maker'),
    "%%home_page_url%%"                             => __("Home page URL", 'quiz-maker'),
    "%%quiz_id%%"                                   => __("Quiz ID", 'quiz-maker'),
    "%%user_id%%"                                   => __("User ID", 'quiz-maker'),
    "%%site_title%%"                                => __("Site title", 'quiz-maker'),
);

$quiz_message_vars_logged_in_users = array(
    "%%quiz_name%%"                                 => __("Quiz Title", 'quiz-maker'),
    "%%questions_count%%"                           => __("Questions count", 'quiz-maker'),
    "%%quiz_creation_date%%"                        => __("Quiz creation date", 'quiz-maker'),
    "%%current_quiz_author%%"                       => __("Quiz Author", 'quiz-maker'),
    "%%current_user_ip%%"                           => __("User's IP Address", 'quiz-maker'),
    "%%current_quiz_author_email%%"                 => __("Quiz Author Email", 'quiz-maker'),
    "%%current_quiz_author_nickname%%"              => __("Quiz Author Nickname", 'quiz-maker'),
    "%%current_quiz_author_website_url%%"           => __("Quiz Author Website", 'quiz-maker'),
    "%%admin_email%%"                               => __("Admin Email", 'quiz-maker'),
    "%%home_page_url%%"                             => __("Home page URL", 'quiz-maker'),
    "%%quiz_id%%"                                   => __("Quiz ID", 'quiz-maker'),
    "%%user_id%%"                                   => __("User ID", 'quiz-maker'),
    "%%site_title%%"                                => __("Site title", 'quiz-maker'),
);

$quiz_message_vars_only_selected_user_role = array(
    "%%quiz_name%%"                                 => __("Quiz Title", 'quiz-maker'),
    "%%user_first_name%%"                           => __("User's First Name", 'quiz-maker'),
    "%%user_last_name%%"                            => __("User's Last Name", 'quiz-maker'),
    "%%questions_count%%"                           => __("Questions count", 'quiz-maker'),
    "%%user_nickname%%"                             => __("User's Nick Name", 'quiz-maker'),
    "%%user_display_name%%"                         => __("User's Display Name", 'quiz-maker'),
    "%%user_wordpress_email%%"                      => __("User's WordPress profile email", 'quiz-maker'),
    "%%user_wordpress_roles%%"                      => __("User's WordPress Roles", 'quiz-maker'),
    "%%quiz_creation_date%%"                        => __("Quiz creation date", 'quiz-maker'),
    "%%current_quiz_author%%"                       => __("Quiz Author", 'quiz-maker'),
    "%%current_user_ip%%"                           => __("User's IP Address", 'quiz-maker'),
    "%%current_quiz_author_email%%"                 => __("Quiz Author Email", 'quiz-maker'),
    "%%current_quiz_author_nickname%%"              => __("Quiz Author Nickname", 'quiz-maker'),
    "%%current_quiz_author_website_url%%"           => __("Quiz Author Website", 'quiz-maker'),
    "%%admin_email%%"                               => __("Admin Email", 'quiz-maker'),
    "%%home_page_url%%"                             => __("Home page URL", 'quiz-maker'),
    "%%quiz_id%%"                                   => __("Quiz ID", 'quiz-maker'),
    "%%user_id%%"                                   => __("User ID", 'quiz-maker'),
    "%%site_title%%"                                => __("Site title", 'quiz-maker'),
);

$quiz_message_vars_limitation_count_of_takers = array(
    "%%quiz_name%%"                                 => __("Quiz Title", 'quiz-maker'),
    "%%user_first_name%%"                           => __("User's First Name", 'quiz-maker'),
    "%%user_last_name%%"                            => __("User's Last Name", 'quiz-maker'),
    "%%questions_count%%"                           => __("Questions count", 'quiz-maker'),
    "%%user_nickname%%"                             => __("User's Nick Name", 'quiz-maker'),
    "%%user_display_name%%"                         => __("User's Display Name", 'quiz-maker'),
    "%%user_wordpress_email%%"                      => __("User's WordPress profile email", 'quiz-maker'),
    "%%user_wordpress_roles%%"                      => __("User's WordPress Roles", 'quiz-maker'),
    "%%quiz_creation_date%%"                        => __("Quiz creation date", 'quiz-maker'),
    "%%current_quiz_author%%"                       => __("Quiz Author", 'quiz-maker'),
    "%%current_user_ip%%"                           => __("User's IP Address", 'quiz-maker'),
    "%%current_quiz_author_email%%"                 => __("Quiz Author Email", 'quiz-maker'),
    "%%current_quiz_author_nickname%%"              => __("Quiz Author Nickname", 'quiz-maker'),
    "%%current_quiz_author_website_url%%"           => __("Quiz Author Website", 'quiz-maker'),
    "%%admin_email%%"                               => __("Admin Email", 'quiz-maker'),
    "%%home_page_url%%"                             => __("Home page URL", 'quiz-maker'),
    "%%quiz_id%%"                                   => __("Quiz ID", 'quiz-maker'),
    "%%user_id%%"                                   => __("User ID", 'quiz-maker'),
    "%%site_title%%"                                => __("Site title", 'quiz-maker'),
);

$quiz_message_vars_password_for_passing_quiz = array(
    "%%quiz_name%%"                                 => __("Quiz Title", 'quiz-maker'),
    "%%user_first_name%%"                           => __("User's First Name", 'quiz-maker'),
    "%%user_last_name%%"                            => __("User's Last Name", 'quiz-maker'),
    "%%questions_count%%"                           => __("Questions count", 'quiz-maker'),
    "%%user_nickname%%"                             => __("User's Nick Name", 'quiz-maker'),
    "%%user_display_name%%"                         => __("User's Display Name", 'quiz-maker'),
    "%%user_wordpress_email%%"                      => __("User's WordPress profile email", 'quiz-maker'),
    "%%user_wordpress_roles%%"                      => __("User's WordPress Roles", 'quiz-maker'),
    "%%quiz_creation_date%%"                        => __("Quiz creation date", 'quiz-maker'),
    "%%current_quiz_author%%"                       => __("Quiz Author", 'quiz-maker'),
    "%%current_user_ip%%"                           => __("User's IP Address", 'quiz-maker'),
    "%%current_quiz_author_email%%"                 => __("Quiz Author Email", 'quiz-maker'),
    "%%current_quiz_author_nickname%%"              => __("Quiz Author Nickname", 'quiz-maker'),
    "%%current_quiz_author_website_url%%"           => __("Quiz Author Website", 'quiz-maker'),
    "%%admin_email%%"                               => __("Admin Email", 'quiz-maker'),
    "%%home_page_url%%"                             => __("Home page URL", 'quiz-maker'),
    "%%quiz_id%%"                                   => __("Quiz ID", 'quiz-maker'),
    "%%user_id%%"                                   => __("User ID", 'quiz-maker'),
    "%%site_title%%"                                => __("Site title", 'quiz-maker'),
);

$quiz_message_vars_rating_form_title = array(
    "%%quiz_name%%"                                 => __("Quiz Title", 'quiz-maker'),
    "%%user_first_name%%"                           => __("User's First Name", 'quiz-maker'),
    "%%user_last_name%%"                            => __("User's Last Name", 'quiz-maker'),
    "%%questions_count%%"                           => __("Questions count", 'quiz-maker'),
    "%%user_nickname%%"                             => __("User's Nick Name", 'quiz-maker'),
    "%%user_display_name%%"                         => __("User's Display Name", 'quiz-maker'),
    "%%user_wordpress_email%%"                      => __("User's WordPress profile email", 'quiz-maker'),
    "%%user_wordpress_roles%%"                      => __("User's WordPress Roles", 'quiz-maker'),
    "%%quiz_creation_date%%"                        => __("Quiz creation date", 'quiz-maker'),
    "%%current_quiz_author%%"                       => __("Quiz Author", 'quiz-maker'),
    "%%current_user_ip%%"                           => __("User's IP Address", 'quiz-maker'),
    "%%current_quiz_author_email%%"                 => __("Quiz Author Email", 'quiz-maker'),
    "%%current_quiz_author_nickname%%"              => __("Quiz Author Nickname", 'quiz-maker'),
    "%%current_quiz_author_website_url%%"           => __("Quiz Author Website", 'quiz-maker'),
    "%%admin_email%%"                               => __("Admin Email", 'quiz-maker'),
    "%%home_page_url%%"                             => __("Home page URL", 'quiz-maker'),
    "%%quiz_id%%"                                   => __("Quiz ID", 'quiz-maker'),
    "%%user_id%%"                                   => __("User ID", 'quiz-maker'),
    "%%site_title%%"                                => __("Site title", 'quiz-maker'),
);

$quiz_message_vars_schedule_pre_start_message = array(
    "%%quiz_name%%"                                 => __("Quiz Title", 'quiz-maker'),
    "%%user_first_name%%"                           => __("User's First Name", 'quiz-maker'),
    "%%user_last_name%%"                            => __("User's Last Name", 'quiz-maker'),
    "%%questions_count%%"                           => __("Questions count", 'quiz-maker'),
    "%%user_nickname%%"                             => __("User's Nick Name", 'quiz-maker'),
    "%%user_display_name%%"                         => __("User's Display Name", 'quiz-maker'),
    "%%user_wordpress_email%%"                      => __("User's WordPress profile email", 'quiz-maker'),
    "%%user_wordpress_roles%%"                      => __("User's WordPress Roles", 'quiz-maker'),
    "%%quiz_creation_date%%"                        => __("Quiz creation date", 'quiz-maker'),
    "%%current_quiz_author%%"                       => __("Quiz Author", 'quiz-maker'),
    "%%current_user_ip%%"                           => __("User's IP Address", 'quiz-maker'),
    "%%current_quiz_author_email%%"                 => __("Quiz Author Email", 'quiz-maker'),
    "%%current_quiz_author_nickname%%"              => __("Quiz Author Nickname", 'quiz-maker'),
    "%%current_quiz_author_website_url%%"           => __("Quiz Author Website", 'quiz-maker'),
    "%%admin_email%%"                               => __("Admin Email", 'quiz-maker'),
    "%%home_page_url%%"                             => __("Home page URL", 'quiz-maker'),
    "%%quiz_id%%"                                   => __("Quiz ID", 'quiz-maker'),
    "%%user_id%%"                                   => __("User ID", 'quiz-maker'),
    "%%site_title%%"                                => __("Site title", 'quiz-maker'),
);

$quiz_message_vars_review_thank_you_message = array(
    "%%quiz_name%%"                                 => __("Quiz Title", 'quiz-maker'),
    "%%user_first_name%%"                           => __("User's First Name", 'quiz-maker'),
    "%%user_last_name%%"                            => __("User's Last Name", 'quiz-maker'),
    "%%questions_count%%"                           => __("Questions count", 'quiz-maker'),
    "%%user_nickname%%"                             => __("User's Nick Name", 'quiz-maker'),
    "%%user_display_name%%"                         => __("User's Display Name", 'quiz-maker'),
    "%%user_wordpress_email%%"                      => __("User's WordPress profile email", 'quiz-maker'),
    "%%user_wordpress_roles%%"                      => __("User's WordPress Roles", 'quiz-maker'),
    "%%quiz_creation_date%%"                        => __("Quiz creation date", 'quiz-maker'),
    "%%current_quiz_author%%"                       => __("Quiz Author", 'quiz-maker'),
    "%%current_user_ip%%"                           => __("User's IP Address", 'quiz-maker'),
    "%%current_quiz_author_email%%"                 => __("Quiz Author Email", 'quiz-maker'),
    "%%current_quiz_author_nickname%%"              => __("Quiz Author Nickname", 'quiz-maker'),
    "%%current_quiz_author_website_url%%"           => __("Quiz Author Website", 'quiz-maker'),
    "%%admin_email%%"                               => __("Admin Email", 'quiz-maker'),
    "%%home_page_url%%"                             => __("Home page URL", 'quiz-maker'),
    "%%quiz_id%%"                                   => __("Quiz ID", 'quiz-maker'),
    "%%user_id%%"                                   => __("User ID", 'quiz-maker'),
    "%%site_title%%"                                => __("Site title", 'quiz-maker'),
);

$quiz_message_vars_html                             = $this->ays_quiz_generate_message_vars_html( $quiz_message_vars );
$quiz_message_vars_timer_html                       = $this->ays_quiz_generate_message_vars_html( $quiz_message_vars_timer );
$quiz_message_vars_information_form_html            = $this->ays_quiz_generate_message_vars_html( $quiz_message_vars_information_form );
$quiz_message_vars_description_html                 = $this->ays_quiz_generate_message_vars_html( $quiz_message_vars_description );
$quiz_message_vars_limitation_message_html          = $this->ays_quiz_generate_message_vars_html( $quiz_message_vars_limitation_message );
$quiz_message_vars_logged_in_users_html             = $this->ays_quiz_generate_message_vars_html( $quiz_message_vars_logged_in_users );
$quiz_message_vars_only_selected_user_role_html     = $this->ays_quiz_generate_message_vars_html( $quiz_message_vars_only_selected_user_role );
$quiz_message_vars_limitation_count_of_takers_html  = $this->ays_quiz_generate_message_vars_html( $quiz_message_vars_limitation_count_of_takers );
$quiz_message_vars_password_for_passing_quiz_html   = $this->ays_quiz_generate_message_vars_html( $quiz_message_vars_password_for_passing_quiz );
$quiz_message_vars_rating_form_title_html           = $this->ays_quiz_generate_message_vars_html( $quiz_message_vars_rating_form_title );
$quiz_message_vars_schedule_pre_start_message_html  = $this->ays_quiz_generate_message_vars_html( $quiz_message_vars_schedule_pre_start_message );
$quiz_message_vars_review_thank_you_message_html    = $this->ays_quiz_generate_message_vars_html( $quiz_message_vars_review_thank_you_message );

$quiz_allowed_html = Quiz_Maker_Data::ays_quiz_allowed_html();

$certificate_body_html = '
This is to certify that
%%user_name%%

has completed the quiz

"%%quiz_name%%"

with score of %%score%%

dated
%%current_date%%';

$quiz_accordion_svg_html = '
<div class="ays-quiz-accordion-arrow-box">
    <svg class="ays-quiz-accordion-arrow ays-quiz-accordion-arrow-down" version="1.2" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" overflow="visible" preserveAspectRatio="none" viewBox="0 0 24 24" width="32" height="32">
        <g>
            <path xmlns:default="http://www.w3.org/2000/svg" d="M8.59 16.34l4.58-4.59-4.58-4.59L10 5.75l6 6-6 6z" fill="#008cff" vector-effect="non-scaling-stroke" />
        </g>
    </svg>
</div>';

$quiz_open_quizzes_list_icon_svg_html = '
<div class="ays-quiz-accordion-arrow-box">
    <svg width="22" height="23" viewBox="0 0 22 23" fill="none" xmlns="http://www.w3.org/2000/svg">
        <mask id="mask0_2411_1023" style="mask-type: alpha;" maskUnits="userSpaceOnUse" x="0" y="0" width="22" height="23">
            <rect y="0.5" width="22" height="22" fill="#D9D9D9" />
        </mask>
        <g mask="url(#mask0_2411_1023)">
            <path
                d="M8.25 17.9167V16.0834H19.25V17.9167H8.25ZM8.25 12.4167V10.5834H19.25V12.4167H8.25ZM8.25 6.91669V5.08335H19.25V6.91669H8.25ZM4.58333 18.8334C4.07917 18.8334 3.64757 18.6538 3.28854 18.2948C2.92951 17.9358 2.75 17.5042 2.75 17C2.75 16.4959 2.92951 16.0643 3.28854 15.7052C3.64757 15.3462 4.07917 15.1667 4.58333 15.1667C5.0875 15.1667 5.5191 15.3462 5.87813 15.7052C6.23715 16.0643 6.41667 16.4959 6.41667 17C6.41667 17.5042 6.23715 17.9358 5.87813 18.2948C5.5191 18.6538 5.0875 18.8334 4.58333 18.8334ZM4.58333 13.3334C4.07917 13.3334 3.64757 13.1538 3.28854 12.7948C2.92951 12.4358 2.75 12.0042 2.75 11.5C2.75 10.9959 2.92951 10.5643 3.28854 10.2052C3.64757 9.8462 4.07917 9.66669 4.58333 9.66669C5.0875 9.66669 5.5191 9.8462 5.87813 10.2052C6.23715 10.5643 6.41667 10.9959 6.41667 11.5C6.41667 12.0042 6.23715 12.4358 5.87813 12.7948C5.5191 13.1538 5.0875 13.3334 4.58333 13.3334ZM4.58333 7.83335C4.07917 7.83335 3.64757 7.65384 3.28854 7.29481C2.92951 6.93578 2.75 6.50419 2.75 6.00002C2.75 5.49585 2.92951 5.06426 3.28854 4.70523C3.64757 4.3462 4.07917 4.16669 4.58333 4.16669C5.0875 4.16669 5.5191 4.3462 5.87813 4.70523C6.23715 5.06426 6.41667 5.49585 6.41667 6.00002C6.41667 6.50419 6.23715 6.93578 5.87813 7.29481C5.5191 7.65384 5.0875 7.83335 4.58333 7.83335Z"
                fill="#1C1B1F"
            />
        </g>
    </svg>
</div>';

$tab_docs = array(
    'tab1' => array(
        'link' => 'https://quiz-plugin.com/docs/how-to-create-a-quiz/',
        'text' => __('How to Configure General Settings?', 'quiz-maker'),
    ),
    'tab2' => array(
        'link' => 'https://quiz-plugin.com/docs/styles-settings/',
        'text' => __('How to Configure Styles Tab?', 'quiz-maker'),
    ),
    'tab3' => array(
        'link' => 'https://quiz-plugin.com/docs/quiz-settings-tab/',
        'text' => __('How to Configure Settings Tab?', 'quiz-maker'),
    ),
    'tab4' => array(
        'link' => 'https://quiz-plugin.com/docs/quiz-results-settings/',
        'text' => __('How to Configure Results Settings Tab?', 'quiz-maker'),
    ),
    'tab5' => array(
        'link' => 'https://quiz-plugin.com/docs/quiz-limitation-settings/',
        'text' => __('How to Configure Limitation Users Tab?', 'quiz-maker'),
    ),
    'tab6' => array(
        'link' => 'https://quiz-plugin.com/docs/quiz-user-data-settings/',
        'text' => __('How to Configure User Data Tab?', 'quiz-maker'),
    ),
    'tab7' => array(
        'link' => 'https://quiz-plugin.com/docs/email-certificate-settings/',
        'text' => __('How to Configure E-Mail, Certificate Tab?', 'quiz-maker'),
    ),
    'tab8' => array(
        'link' => 'https://quiz-plugin.com/docs-category/integrations/',
        'text' => __('How to Configure Integration Tab?', 'quiz-maker'),
    ),
);
$style = null;
$image_text = __('Add Image', 'quiz-maker');
$bg_image_text = __('Add Image', 'quiz-maker');

$quiz_image = (isset( $quiz['quiz_image']  ) && $quiz['quiz_image'] != '') ? esc_url($quiz['quiz_image']) : "";
if ( $quiz_image != "" ) {
    $style = "display: block;";
    $image_text = __('Edit Image', 'quiz-maker');
}

$get_all_quizzes = $this->ays_quiz_ays_quiz_get_quizzes();

global $wp_roles;
$ays_users_roles = $wp_roles->roles;

$required_fields = (isset($options['required_fields']) ? $options['required_fields'] : array());
$enable_pass_count = (isset($options['enable_pass_count'])) ? $options['enable_pass_count'] : 'off';

// Enable Timer
$options['enable_timer'] = isset($options['enable_timer']) ? $options['enable_timer'] : 'off';
$enable_timer = (isset($options['enable_timer']) && $options['enable_timer'] == 'on') ? true : false;

$options['timer'] = !(isset($options['timer'])) ? 100 : intval($options['timer']);
$timer = (isset($options['timer']) && $options['timer'] != '') ? intval($options['timer']) : '100';
$enable_quiz_rate = (isset($options['enable_quiz_rate'])) ? esc_attr( stripslashes($options['enable_quiz_rate'])) : 'off';
$enable_rate_avg = (isset($options['enable_rate_avg'])) ? esc_attr( stripslashes($options['enable_rate_avg'])) : 'off';
$enable_rate_comments = (isset($options['enable_rate_comments'])) ? esc_attr( stripslashes($options['enable_rate_comments'])) : '';
$enable_box_shadow = (!isset($options['enable_box_shadow'])) ? 'on' : esc_attr( stripslashes($options['enable_box_shadow']));
$box_shadow_color = (!isset($options['box_shadow_color'])) ? '#c9c9c9' : esc_attr( stripslashes($options['box_shadow_color']) );
$quiz_border_radius = (isset($options['quiz_border_radius']) && $options['quiz_border_radius'] != '') ? intval( esc_attr($options['quiz_border_radius'])) : '8';
$quiz_bg_image = (isset($options['quiz_bg_image']) && $options['quiz_bg_image'] != '') ? esc_url($options['quiz_bg_image']) : '';
$enable_border = (isset($options['enable_border']) && $options['enable_border'] == 'on') ? true : false;
$quiz_border_width = (isset($options['quiz_border_width']) && $options['quiz_border_width'] != '') ? intval( esc_attr($options['quiz_border_width'])) : '1';
$quiz_border_style = (isset($options['quiz_border_style']) && $options['quiz_border_style'] != '') ? esc_attr( stripslashes($options['quiz_border_style'])) : 'solid';
$quiz_border_color = (isset($options['quiz_border_color']) && $options['quiz_border_color'] != '') ? esc_attr( stripslashes($options['quiz_border_color']) ) : '#000';
$quiz_timer_in_title = (isset($options['quiz_timer_in_title']) && $options['quiz_timer_in_title'] == 'on') ? true : false;
$enable_restart_button = (isset($options['enable_restart_button']) && $options['enable_restart_button'] == 'on') ? true : false;

$rate_form_title = (isset($options['rate_form_title'])) ? $options['rate_form_title'] : __('Please click the stars to rate the quiz', 'quiz-maker');
$quiz_loader = (isset($options['quiz_loader']) && $options['quiz_loader'] != '') ? esc_attr( stripslashes($options['quiz_loader'])) : 'default';

$main_quiz_url = (isset($quiz['quiz_url']) && esc_url($quiz['quiz_url']) != '') ? esc_url($quiz['quiz_url']) : '';

$quiz_create_date = (isset($options['create_date']) && $options['create_date'] != '') ? esc_attr( stripslashes($options['create_date'])) : "0000-00-00 00:00:00";
if(isset($options['author']) && $options['author'] != 'null'){
    if ( ! is_array( $options['author'] ) ) {
        $options['author'] = json_decode($options['author'], true);
        $quiz_author = $options['author'];
    } else {
        $quiz_author = array_map( 'stripslashes', $options['author'] );
    }
} else {
    $quiz_author = array('name' => 'Unknown');
}

$autofill_user_data = (isset($options['autofill_user_data']) && $options['autofill_user_data'] == 'on') ? true : false;

$quest_animation = (isset($options['quest_animation'])) ? esc_attr( stripslashes($options['quest_animation'])) : "none";
$enable_bg_music = (isset($options['enable_bg_music']) && $options['enable_bg_music'] == "on") ? true : false;
$quiz_bg_music = (isset($options['quiz_bg_music']) && $options['quiz_bg_music'] != "") ? $options['quiz_bg_music'] : "";
$answers_font_size = (isset($options['answers_font_size']) && $options['answers_font_size'] != "" && absint( esc_attr( $options['answers_font_size'] ) ) > 0) ? absint( esc_attr( $options['answers_font_size'] ) ) : '15';
$show_create_date = (isset($options['show_create_date']) && $options['show_create_date'] == "on") ? true : false;
$show_author = (isset($options['show_author']) && $options['show_author'] == "on") ? true : false;
$enable_early_finish = (isset($options['enable_early_finish']) && $options['enable_early_finish'] == "on") ? true : false;
$answers_rw_texts = (isset($options['answers_rw_texts']) && $options['answers_rw_texts'] != '') ? esc_attr( stripslashes($options['answers_rw_texts'])) : 'on_passing';
$disable_store_data = (isset($options['disable_store_data']) && $options['disable_store_data'] == 'on') ? true : false;

$options['enable_background_gradient'] = (!isset($options['enable_background_gradient'])) ? 'off' : $options['enable_background_gradient'];
$enable_background_gradient = (isset($options['enable_background_gradient']) && $options['enable_background_gradient'] == 'on') ? true : false;
$background_gradient_color_1 = (isset($options['background_gradient_color_1']) && $options['background_gradient_color_1'] != '') ? esc_attr( stripslashes($options['background_gradient_color_1']) ) : '#000';
$background_gradient_color_2 = (isset($options['background_gradient_color_2']) && $options['background_gradient_color_2'] != '') ? esc_attr( stripslashes($options['background_gradient_color_2']) ) : '#fff';
$quiz_gradient_direction = (isset($options['quiz_gradient_direction']) && $options['quiz_gradient_direction'] != '') ? $options['quiz_gradient_direction'] : 'vertical';

// Redirect after submit
$options['redirect_after_submit'] = (!isset($options['redirect_after_submit'])) ? 'off' : $options['redirect_after_submit'];
$redirect_after_submit = isset($options['redirect_after_submit']) && $options['redirect_after_submit'] == 'on' ? true : false;
$submit_redirect_url = isset($options['submit_redirect_url']) ? $options['submit_redirect_url'] : '';
$submit_redirect_delay = (isset($options['submit_redirect_delay']) && $options['submit_redirect_delay'] != "") ? esc_attr( absint($options['submit_redirect_delay']) ) : '';

// Progress bar style
$progress_bar_style = (isset($options['progress_bar_style']) && $options['progress_bar_style'] != "") ? $options['progress_bar_style'] : 'third';

// Exit button in finish page
$options['enable_exit_button'] = (!isset($options['enable_exit_button'])) ? 'off' : $options['enable_exit_button'];
$enable_exit_button = isset($options['enable_exit_button']) && $options['enable_exit_button'] == 'on' ? true : false;
$exit_redirect_url = isset($options['exit_redirect_url']) ? $options['exit_redirect_url'] : '';

// Question image sizing
$image_sizing = (isset($options['image_sizing']) && $options['image_sizing'] != "") ? $options['image_sizing'] : 'cover';

// Quiz background image position
$quiz_bg_image_position = (isset($options['quiz_bg_image_position']) && $options['quiz_bg_image_position'] != "") ? $options['quiz_bg_image_position'] : 'center center';

// Custom class for quiz container
$custom_class = (isset($options['custom_class']) && $options['custom_class'] != "") ? esc_attr( stripslashes($options['custom_class']) ) : '';

// Social Media links
$enable_social_links = (isset($options['enable_social_links']) && $options['enable_social_links'] == "on") ? true : false;
$social_links = (isset($options['social_links'])) ? $options['social_links'] : array(
    'linkedin_link'     => '',
    'facebook_link'     => '',
    'twitter_link'      => '',
    'vkontakte_link'    => '',
    'instagram_link'    => '',
    'youtube_link'      => '',
    'behance_link'      => '',
);
$linkedin_link = isset($social_links['linkedin_link']) && $social_links['linkedin_link'] != '' ? esc_url($social_links['linkedin_link']) : '';
$facebook_link = isset($social_links['facebook_link']) && $social_links['facebook_link'] != '' ? esc_url($social_links['facebook_link']) : '';
$twitter_link = isset($social_links['twitter_link']) && $social_links['twitter_link'] != '' ? esc_url($social_links['twitter_link']) : '';
$vkontakte_link = isset($social_links['vkontakte_link']) && $social_links['vkontakte_link'] != '' ? esc_url($social_links['vkontakte_link']) : '';
$instagram_link = isset($social_links['instagram_link']) && $social_links['instagram_link'] != '' ? esc_url($social_links['instagram_link']) : '';
$youtube_link = isset($social_links['youtube_link']) && $social_links['youtube_link'] != '' ? esc_url($social_links['youtube_link']) : '';
$behance_link = isset($social_links['behance_link']) && $social_links['behance_link'] != '' ? esc_url($social_links['behance_link']) : '';

// Show quiz head information
// Show quiz title and description
$options['show_quiz_title'] = isset($options['show_quiz_title']) ? $options['show_quiz_title'] : 'on';
$options['show_quiz_desc'] = isset($options['show_quiz_desc']) ? $options['show_quiz_desc'] : 'on';
$show_quiz_title = (isset($options['show_quiz_title']) && $options['show_quiz_title'] == "on") ? true : false;
$show_quiz_desc = (isset($options['show_quiz_desc']) && $options['show_quiz_desc'] == "on") ? true : false;


// Show login form for not logged in users
$options['show_login_form'] = isset($options['show_login_form']) ? $options['show_login_form'] : 'off';
$show_login_form = (isset($options['show_login_form']) && $options['show_login_form'] == "on") ? true : false;


// Quiz container max-width for mobile
$mobile_max_width = (isset($options['mobile_max_width']) && $options['mobile_max_width'] != "") ? intval(esc_attr($options['mobile_max_width'])) : '';


// Quiz theme
$quiz_theme = (isset($options['quiz_theme']) && $options['quiz_theme'] != '') ? esc_attr( stripslashes($options['quiz_theme'])) : 'classic_light';


// Limit users by option
$limit_users_by = (isset($options['limit_users_by']) && $options['limit_users_by'] != '') ? esc_attr( stripslashes($options['limit_users_by'])) : 'ip';

//Schedule of Quiz
$options['active_date_check'] = isset($options['active_date_check']) ? $options['active_date_check'] : 'off';
$active_date_check = (isset($options['active_date_check']) && $options['active_date_check'] == 'on') ? true : false;
if ($active_date_check) {
    $activateTime   = strtotime($options['activeInterval']);
	$activeQuiz     = date('Y-m-d H:i:s', $activateTime);
	$deactivateTime = strtotime($options['deactiveInterval']);
	$deactiveQuiz   = date('Y-m-d H:i:s', $deactivateTime);
} else {
    $activeQuiz   = current_time( 'mysql' );
	$deactiveQuiz = current_time( 'mysql' );
}

// Show all questions result in finish page
$options['enable_questions_result'] = isset($options['enable_questions_result']) ? $options['enable_questions_result'] : 'off';
$enable_questions_result = (isset($options['enable_questions_result']) && $options['enable_questions_result'] == 'on') ? true : false;

// Right/wrong answer text showing time option
$explanation_time = (isset($options['explanation_time']) && $options['explanation_time'] != '') ? intval( esc_attr($options['explanation_time']) ) : '4';

// Enable claer answer button
$options['enable_clear_answer'] = isset($options['enable_clear_answer']) ? $options['enable_clear_answer'] : 'off';
$enable_clear_answer = (isset($options['enable_clear_answer']) && $options['enable_clear_answer'] == "on") ? true : false;

// Show quiz category
$options['show_category'] = isset($options['show_category']) ? $options['show_category'] : 'off';
$show_category = (isset($options['show_category']) && $options['show_category'] == "on") ? true : false;

// Show question category
$options['show_question_category'] = isset($options['show_question_category']) ? $options['show_question_category'] : 'off';
$show_question_category = (isset($options['show_question_category']) && $options['show_question_category'] == "on") ? true : false;

// Display score option
$display_score = (isset($options['display_score']) && $options['display_score'] != "") ? esc_attr( stripslashes($options['display_score'])) : 'by_percantage';

// Right / Wrong answers sound option
$options['enable_rw_asnwers_sounds'] = isset($options['enable_rw_asnwers_sounds']) ? $options['enable_rw_asnwers_sounds'] : 'off';
$enable_rw_asnwers_sounds = (isset($options['enable_rw_asnwers_sounds']) && $options['enable_rw_asnwers_sounds'] == "on") ? true : false;

// Answers right/wrong answers icons
$ans_right_wrong_icon = (isset($options['ans_right_wrong_icon']) && $options['ans_right_wrong_icon'] != '') ? $options['ans_right_wrong_icon'] : 'none';

// Hide quiz background image on the result page
$options['quiz_bg_img_in_finish_page'] = isset($options['quiz_bg_img_in_finish_page']) ? $options['quiz_bg_img_in_finish_page'] : 'off';
$quiz_bg_img_in_finish_page = (isset($options['quiz_bg_img_in_finish_page']) && $options['quiz_bg_img_in_finish_page'] == "on") ? true : false;

// Finish the quiz after making one wrong answer
$options['finish_after_wrong_answer'] = isset($options['finish_after_wrong_answer']) ? $options['finish_after_wrong_answer'] : 'off';
$finish_after_wrong_answer = (isset($options['finish_after_wrong_answer']) && $options['finish_after_wrong_answer'] == "on") ? true : false;

// Text after timer ends
$after_timer_text = (isset($options['after_timer_text']) && $options['after_timer_text'] != '') ? wpautop(stripslashes($options['after_timer_text'])) : '';

// Enable to go next by pressing Enter key
$options['enable_enter_key'] = isset($options['enable_enter_key']) ? $options['enable_enter_key'] : 'on';
$enable_enter_key = (isset($options['enable_enter_key']) && $options['enable_enter_key'] == "on") ? true : false;

// Text color
$text_color = (isset($options['text_color']) && $options['text_color'] != '') ? esc_attr( stripslashes($options['text_color']) ) : '#000000';

// Buttons text color
$buttons_text_color = (isset($options['buttons_text_color']) && $options['buttons_text_color'] != '') ? esc_attr( stripslashes($options['buttons_text_color']) ) : $text_color;

// Buttons position
$buttons_position = (isset($options['buttons_position']) && $options['buttons_position'] != '') ? $options['buttons_position'] : 'center';

// Show questions explanation on
$show_questions_explanation = (isset($options['show_questions_explanation']) && $options['show_questions_explanation'] != '') ? $options['show_questions_explanation'] : 'on_results_page';

// Enable audio autoplay
$enable_audio_autoplay = (isset($options['enable_audio_autoplay']) && $options['enable_audio_autoplay'] == 'on') ? true : false;

// =========== Buttons Styles Start ===========

// Buttons size
$buttons_size = (isset($options['buttons_size']) && $options['buttons_size'] != "") ? $options['buttons_size'] : 'large';

// Buttons font size
$buttons_font_size = (isset($options['buttons_font_size']) && $options['buttons_font_size'] != "") ? $options['buttons_font_size'] : '18';

// Buttons font size
$buttons_width = (isset($options['buttons_width']) && $options['buttons_width'] != "") ? $options['buttons_width'] : '';

// Buttons Left / Right padding
$buttons_left_right_padding = (isset($options['buttons_left_right_padding']) && $options['buttons_left_right_padding'] != '') ? $options['buttons_left_right_padding'] : '36';

// Buttons Top / Bottom padding
$buttons_top_bottom_padding = (isset($options['buttons_top_bottom_padding']) && $options['buttons_top_bottom_padding'] != '') ? $options['buttons_top_bottom_padding'] : '14';

// Buttons border radius
$buttons_border_radius = (isset($options['buttons_border_radius']) && $options['buttons_border_radius'] != "") ? $options['buttons_border_radius'] : '8';

// =========== Buttons Styles End ===========

// Enable leave page
$options['enable_leave_page'] = isset($options['enable_leave_page']) ? $options['enable_leave_page'] : 'on';
$enable_leave_page = (isset($options['enable_leave_page']) && $options['enable_leave_page'] == "on") ? true : false;

// Limitation tackers of quiz
$options['enable_tackers_count'] = !isset($options['enable_tackers_count']) ? 'off' : $options['enable_tackers_count'];
$enable_tackers_count = (isset($options['enable_tackers_count']) && $options['enable_tackers_count'] == 'on') ? true : false;
$tackers_count = (isset($options['tackers_count']) && $options['tackers_count'] != '') ? $options['tackers_count'] : '';

// Pass Score
$pass_score = (isset($options['pass_score']) && $options['pass_score'] != '') ? absint(intval($options['pass_score'])) : '0';
$pass_score_message = isset($options['pass_score_message']) ? stripslashes($options['pass_score_message']) : '<h4 style="text-align: center;">'. __("Congratulations!", 'quiz-maker') .'</h4><p style="text-align: center;">'. __("You passed the quiz!", 'quiz-maker') .'</p>';
$fail_score_message = isset($options['fail_score_message']) ? stripslashes($options['fail_score_message']) : '<h4 style="text-align: center;">'. __("Oops!", 'quiz-maker') .'</h4><p style="text-align: center;">'. __("You have not passed the quiz! <br> Try again!", 'quiz-maker') .'</p>';

// Question Font Size
$question_font_size = (isset($options['question_font_size']) && $options['question_font_size'] != '' && absint(esc_attr($options['question_font_size'])) > 0) ? absint(esc_attr($options['question_font_size'])) : '16';

// Quiz Width by percentage or pixels
$quiz_width_by_percentage_px = (isset($options['quiz_width_by_percentage_px']) && $options['quiz_width_by_percentage_px'] != '') ? $options['quiz_width_by_percentage_px'] : 'pixels';

// Text instead of question hint
$questions_hint_icon_or_text = (isset($options['questions_hint_icon_or_text']) && $options['questions_hint_icon_or_text'] != '') ? $options['questions_hint_icon_or_text'] : 'default';
$questions_hint_value = (isset($options['questions_hint_value']) && $options['questions_hint_value'] != '') ? stripslashes(esc_attr($options['questions_hint_value'])) : '';

// Enable Finish Button Comfirm Box 
$options['enable_early_finsh_comfirm_box'] = isset($options['enable_early_finsh_comfirm_box']) ? $options['enable_early_finsh_comfirm_box'] : 'on';
$enable_early_finsh_comfirm_box = (isset($options['enable_early_finsh_comfirm_box']) && $options['enable_early_finsh_comfirm_box'] == "on") ? true : false;

// Enable questions ordering by category
$options['enable_questions_ordering_by_cat'] = isset($options['enable_questions_ordering_by_cat']) ? $options['enable_questions_ordering_by_cat'] : 'off';
$enable_questions_ordering_by_cat = (isset($options['enable_questions_ordering_by_cat']) && $options['enable_questions_ordering_by_cat'] == "on") ? true : false;

// Show schedule timer
$options['show_schedule_timer'] = isset($options['show_schedule_timer']) ? $options['show_schedule_timer'] : 'off';
$schedule_show_timer = (isset($options['show_schedule_timer']) && $options['show_schedule_timer'] == 'on') ? true : false;
$show_timer_type = isset($options['show_timer_type']) && $options['show_timer_type'] != '' ? $options['show_timer_type'] : 'countdown';

// Quiz loader text value
$quiz_loader_text_value = (isset($options['quiz_loader_text_value']) && $options['quiz_loader_text_value'] != '') ? stripslashes(esc_attr($options['quiz_loader_text_value'])) : '';

// Hide correct answers
$options['hide_correct_answers'] = isset($options['hide_correct_answers']) ? $options['hide_correct_answers'] : 'off';
$hide_correct_answers = (isset($options['hide_correct_answers']) && $options['hide_correct_answers'] == 'on') ? true : false;

// Show information form to logged in users
$options['show_information_form'] = isset($options['show_information_form']) ? $options['show_information_form'] : 'on';
$show_information_form = (isset($options['show_information_form']) && $options['show_information_form'] == 'on') ? true : false;

// Quiz loader custom gif value
$quiz_loader_custom_gif = (isset($options['quiz_loader_custom_gif']) && $options['quiz_loader_custom_gif'] != '') ? stripslashes(esc_url($options['quiz_loader_custom_gif'])) : '';

// Disable answer hover
$options['disable_hover_effect'] = isset($options['disable_hover_effect']) ? $options['disable_hover_effect'] : 'off';
$disable_hover_effect = (isset($options['disable_hover_effect']) && $options['disable_hover_effect'] == 'on') ? true : false;

//  Quiz loader custom gif width
$quiz_loader_custom_gif_width = (isset($options['quiz_loader_custom_gif_width']) && $options['quiz_loader_custom_gif_width'] != '') ? absint( intval( $options['quiz_loader_custom_gif_width'] ) ) : 100;

// Progress live bar style
$progress_live_bar_style = (isset($options['progress_live_bar_style']) && $options['progress_live_bar_style'] != "") ? $options['progress_live_bar_style'] : 'default';

// Quiz title transformation
$quiz_title_transformation = (isset($options['quiz_title_transformation']) && sanitize_text_field($options['quiz_title_transformation']) != "") ? sanitize_text_field($options['quiz_title_transformation']) : 'uppercase';

// Show answers numbering
$show_answers_numbering = (isset($options['show_answers_numbering']) && sanitize_text_field( $options['show_answers_numbering'] ) != '') ? sanitize_text_field( $options['show_answers_numbering'] ) : 'none';

// Image Width(px)
$image_width = (isset($options['image_width']) && sanitize_text_field($options['image_width']) != '') ? absint( sanitize_text_field($options['image_width']) ) : '';

// Quiz image width percentage/px
$quiz_image_width_by_percentage_px = (isset($options['quiz_image_width_by_percentage_px']) && esc_attr( $options['quiz_image_width_by_percentage_px'] ) != '') ? esc_attr( $options['quiz_image_width_by_percentage_px'] ) : 'pixels';

// Quiz image height
$quiz_image_height = (isset($options['quiz_image_height']) && esc_attr($options['quiz_image_height']) != '') ? absint( esc_attr($options['quiz_image_height']) ) : '';

// Hide background image on start page
$options['quiz_bg_img_on_start_page'] = isset($options['quiz_bg_img_on_start_page']) ? $options['quiz_bg_img_on_start_page'] : 'off';
$quiz_bg_img_on_start_page = (isset($options['quiz_bg_img_on_start_page']) && $options['quiz_bg_img_on_start_page'] == 'on') ? true : false;

//  Box Shadow X offset
$quiz_box_shadow_x_offset = (isset($options['quiz_box_shadow_x_offset']) && ( $options['quiz_box_shadow_x_offset'] ) != '' && ( $options['quiz_box_shadow_x_offset'] ) != 0) ? intval( ( $options['quiz_box_shadow_x_offset'] ) ) : 0;

//  Box Shadow Y offset
$quiz_box_shadow_y_offset = (isset($options['quiz_box_shadow_y_offset']) && ( $options['quiz_box_shadow_y_offset'] ) != '' && ( $options['quiz_box_shadow_y_offset'] ) != 0) ? intval( ( $options['quiz_box_shadow_y_offset'] ) ) : 0;

//  Box Shadow Z offset
$quiz_box_shadow_z_offset = (isset($options['quiz_box_shadow_z_offset']) && ( $options['quiz_box_shadow_z_offset'] ) != '' && ( $options['quiz_box_shadow_z_offset'] ) != 0) ? intval( ( $options['quiz_box_shadow_z_offset'] ) ) : 15;

// Question text alignment
$quiz_question_text_alignment = (isset($options['quiz_question_text_alignment']) && ( $options['quiz_question_text_alignment'] ) != '') ? ( $options['quiz_question_text_alignment'] ) : 'center';

// Quiz arrows option arrows
$quiz_arrow_type = (isset($options['quiz_arrow_type']) && ( $options['quiz_arrow_type'] ) != '') ? ( $options['quiz_arrow_type'] ) : 'default';

// Show wrong answers first
$options['quiz_show_wrong_answers_first'] = isset($options['quiz_show_wrong_answers_first']) ? sanitize_text_field($options['quiz_show_wrong_answers_first']) : 'off';
$quiz_show_wrong_answers_first = (isset($options['quiz_show_wrong_answers_first']) && $options['quiz_show_wrong_answers_first'] == 'on') ? true : false;

// Display all questions on one page
$options['quiz_display_all_questions'] = isset($options['quiz_display_all_questions']) ? sanitize_text_field($options['quiz_display_all_questions']) : 'off';
$quiz_display_all_questions = (isset($options['quiz_display_all_questions']) && $options['quiz_display_all_questions'] == 'on') ? true : false;

// Turn red warning
$options['quiz_timer_red_warning'] = isset($options['quiz_timer_red_warning']) ? sanitize_text_field($options['quiz_timer_red_warning']) : 'off';
$quiz_timer_red_warning = (isset($options['quiz_timer_red_warning']) && $options['quiz_timer_red_warning'] == 'on') ? true : false;

// Timezone | Schedule the quiz
$ays_quiz_schedule_timezone = (isset($options['quiz_schedule_timezone']) && $options['quiz_schedule_timezone'] != '') ? sanitize_text_field( $options['quiz_schedule_timezone'] ) : get_option( 'timezone_string' );

// Remove old Etc mappings. Fallback to gmt_offset.
if ( strpos( $ays_quiz_schedule_timezone, 'Etc/GMT' ) !== false ) {
    $ays_quiz_schedule_timezone = '';
}

$current_offset = get_option( 'gmt_offset' );
if ( empty( $ays_quiz_schedule_timezone ) ) { // Create a UTC+- zone if no timezone string exists.

    if ( 0 == $current_offset ) {
        $ays_quiz_schedule_timezone = 'UTC+0';
    } elseif ( $current_offset < 0 ) {
        $ays_quiz_schedule_timezone = 'UTC' . $current_offset;
    } else {
        $ays_quiz_schedule_timezone = 'UTC+' . $current_offset;
    }
}

// Hint icon | Button | Text Value
$questions_hint_button_value = (isset($options['questions_hint_button_value']) && sanitize_text_field( $options['questions_hint_button_value'] ) != '') ? sanitize_text_field( esc_attr( $options['questions_hint_button_value']) ) : '';

// Quiz takers message
$quiz_tackers_message = ( isset($options['quiz_tackers_message']) && $options['quiz_tackers_message'] != '' ) ? stripslashes( wpautop( $options['quiz_tackers_message'] ) ) : __( "This quiz is expired!", 'quiz-maker' );

// Show the Social buttons
$options['enable_social_buttons'] = isset($options['enable_social_buttons']) ? sanitize_text_field($options['enable_social_buttons']) : 'off';
$enable_social_buttons = (isset($options['enable_social_buttons']) && $options['enable_social_buttons'] == 'on') ? true : false;

// Enable Linkedin button
$options['quiz_enable_linkedin_share_button'] = isset($options['quiz_enable_linkedin_share_button']) ? sanitize_text_field($options['quiz_enable_linkedin_share_button']) : 'on';
$quiz_enable_linkedin_share_button = (isset($options['quiz_enable_linkedin_share_button']) && $options['quiz_enable_linkedin_share_button'] == 'on') ? true : false;

// Enable Facebook button
$options['quiz_enable_facebook_share_button'] = isset($options['quiz_enable_facebook_share_button']) ? sanitize_text_field($options['quiz_enable_facebook_share_button']) : 'on';
$quiz_enable_facebook_share_button = (isset($options['quiz_enable_facebook_share_button']) && $options['quiz_enable_facebook_share_button'] == 'on') ? true : false;

// Enable Twitter button
$options['quiz_enable_twitter_share_button'] = isset($options['quiz_enable_twitter_share_button']) ? sanitize_text_field($options['quiz_enable_twitter_share_button']) : 'on';
$quiz_enable_twitter_share_button = (isset($options['quiz_enable_twitter_share_button']) && $options['quiz_enable_twitter_share_button'] == 'on') ? true : false;

// Enable Vkontakte button
$options['quiz_enable_vkontakte_share_button'] = isset($options['quiz_enable_vkontakte_share_button']) ? sanitize_text_field($options['quiz_enable_vkontakte_share_button']) : 'on';
$quiz_enable_vkontakte_share_button = (isset($options['quiz_enable_vkontakte_share_button']) && $options['quiz_enable_vkontakte_share_button'] == 'on') ? true : false;

if ( ! $quiz_enable_linkedin_share_button && ! $quiz_enable_facebook_share_button && ! $quiz_enable_twitter_share_button && ! $quiz_enable_vkontakte_share_button ) {
    $quiz_enable_linkedin_share_button = true;
    $quiz_enable_facebook_share_button = true;
    $quiz_enable_twitter_share_button  = true;
    $quiz_enable_vkontakte_share_button  = true;
}

// Make responses anonymous
$options['quiz_make_responses_anonymous'] = isset($options['quiz_make_responses_anonymous']) ? sanitize_text_field($options['quiz_make_responses_anonymous']) : 'off';
$quiz_make_responses_anonymous = (isset($options['quiz_make_responses_anonymous']) && $options['quiz_make_responses_anonymous'] == 'on') ? true : false;

// Add all reviews link
$options['quiz_make_all_review_link'] = isset($options['quiz_make_all_review_link']) ? sanitize_text_field($options['quiz_make_all_review_link']) : 'off';
$quiz_make_all_review_link = (isset($options['quiz_make_all_review_link']) && $options['quiz_make_all_review_link'] == 'on') ? true : false;

// Custom CSS
$ays_quiz_custom_css = (isset($options['custom_css']) && $options['custom_css'] != '') ? stripslashes( esc_attr( $options['custom_css'] ) ) : '';

// Show questions numbering
$show_questions_numbering = (isset($options['show_questions_numbering']) && $options['show_questions_numbering'] != '') ? sanitize_text_field( $options['show_questions_numbering'] ) : 'none';

// Message before timer
$quiz_message_before_timer = (isset($options['quiz_message_before_timer']) && $options['quiz_message_before_timer'] != '') ? esc_attr( sanitize_text_field( $options['quiz_message_before_timer'] ) ) : '';


// Password quiz
$options['enable_password'] = isset($options['enable_password']) ? sanitize_text_field( $options['enable_password'] ) : 'off';
$enable_password = (isset($options['enable_password']) && $options['enable_password'] == 'on') ? true : false;

// Password for passing quiz | Password
$password_quiz = (isset($options['password_quiz']) && $options['password_quiz'] != '') ? esc_attr( sanitize_text_field( $options['password_quiz'] ) ) : '';

// Password for passing quiz | Message
$quiz_password_message = ( isset( $options['quiz_password_message']) && $options['quiz_password_message'] != '' ) ? stripslashes( $options['quiz_password_message'] ) : '';

// Enable confirmation box for the See Result button
$options['enable_see_result_confirm_box'] = isset($options['enable_see_result_confirm_box']) ? sanitize_text_field($options['enable_see_result_confirm_box']) : 'off';
$enable_see_result_confirm_box = (isset($options['enable_see_result_confirm_box']) && $options['enable_see_result_confirm_box'] == 'on') ? true : false;

// Display form fields labels
$options['display_fields_labels'] = isset($options['display_fields_labels']) ? sanitize_text_field($options['display_fields_labels']) : 'off';
$display_fields_labels = (isset($options['display_fields_labels']) && $options['display_fields_labels'] == 'on') ? true : false;

//Enable Full Screen Mode
$options['enable_full_screen_mode'] = isset($options['enable_full_screen_mode']) ? $options['enable_full_screen_mode'] : 'off';
$enable_full_screen_mode = (isset($options['enable_full_screen_mode']) && $options['enable_full_screen_mode'] == 'on') ? true : false;

// Enable toggle password visibility
$options['quiz_enable_password_visibility'] = isset($options['quiz_enable_password_visibility']) ? $options['quiz_enable_password_visibility'] : 'off';
$quiz_enable_password_visibility = (isset($options['quiz_enable_password_visibility']) && $options['quiz_enable_password_visibility'] == 'on') ? true : false;

// Question font size | On mobile
$question_mobile_font_size = (isset($options['question_mobile_font_size']) && sanitize_text_field($options['question_mobile_font_size']) != '' && absint( esc_attr($options['question_mobile_font_size']) ) > 0) ? absint( esc_attr($options['question_mobile_font_size']) ) : 16;

// Answer font size | On mobile
$answers_mobile_font_size = (isset($options['answers_mobile_font_size']) && sanitize_text_field($options['answers_mobile_font_size']) != '' && absint( sanitize_text_field($options['answers_mobile_font_size']) ) > 0) ? absint( sanitize_text_field($options['answers_mobile_font_size']) ) : 15;

// Heading for social buttons
$social_buttons_heading = (isset($options['social_buttons_heading']) && $options['social_buttons_heading'] != '') ? stripslashes( wpautop( $options['social_buttons_heading'] ) ) : "";

// Answers border options
$options['answers_border'] = (isset($options['answers_border'])) ? $options['answers_border'] : 'on';
$answers_border = (isset($options['answers_border']) && $options['answers_border'] == 'on') ? true : false;
$answers_border_width = (isset($options['answers_border_width']) && $options['answers_border_width'] != '') ? $options['answers_border_width'] : '1';
$answers_border_style = (isset($options['answers_border_style']) && $options['answers_border_style'] != '') ? $options['answers_border_style'] : 'solid';
$answers_border_color = (isset($options['answers_border_color']) && $options['answers_border_color'] != '') ? esc_attr( stripslashes($options['answers_border_color']) ) : '#dddddd';

// Heading for social media links
$social_links_heading = (isset($options['social_links_heading']) && $options['social_links_heading'] != '') ? stripslashes( wpautop( $options['social_links_heading'] ) ) : "";

// Show question category description
$options['quiz_enable_question_category_description'] = isset($options['quiz_enable_question_category_description']) ? $options['quiz_enable_question_category_description'] : 'off';
$quiz_enable_question_category_description = (isset($options['quiz_enable_question_category_description']) && $options['quiz_enable_question_category_description'] == 'on') ? true : false;

// Answers margin option
$answers_margin = (isset($options['answers_margin']) && $options['answers_margin'] != '') ? esc_attr( stripslashes( $options['answers_margin'] ) ) : '12';

// Message before redirect timer
$quiz_message_before_redirect_timer = (isset($options['quiz_message_before_redirect_timer']) && $options['quiz_message_before_redirect_timer'] != '') ? stripslashes( esc_attr( $options['quiz_message_before_redirect_timer'] ) ) : '';

// Button font-size (px) | Mobile
$buttons_mobile_font_size = (isset($options['buttons_mobile_font_size']) && $options['buttons_mobile_font_size'] != '') ? absint( esc_attr( $options['buttons_mobile_font_size'] ) ) : 18;

// Change current quiz creation date
$change_creation_date = ( isset($options['create_date']) && Quiz_Maker_Admin::validateDate($options['create_date']) ) ? esc_attr( stripslashes($options['create_date']) ) : current_time( 'mysql' );

// Answers box shadow
$options['answers_box_shadow'] = isset($options['answers_box_shadow']) ? esc_attr($options['answers_box_shadow']) : 'off';
$answers_box_shadow = (isset($options['answers_box_shadow']) && $options['answers_box_shadow'] == 'on') ? true : false;

// Answer box-shadow color
$answers_box_shadow_color = (isset($options['answers_box_shadow_color']) && $options['answers_box_shadow_color'] != '') ? esc_attr($options['answers_box_shadow_color']) : '#000';

// Answer box Shadow X offset
$quiz_answer_box_shadow_x_offset = (isset($options['quiz_answer_box_shadow_x_offset']) && ( $options['quiz_answer_box_shadow_x_offset'] ) != '' && ( $options['quiz_answer_box_shadow_x_offset'] ) != 0) ? esc_attr( intval( $options['quiz_answer_box_shadow_x_offset'] ) ) : 0;

// Answer box Shadow Y offset
$quiz_answer_box_shadow_y_offset = (isset($options['quiz_answer_box_shadow_y_offset']) && ( $options['quiz_answer_box_shadow_y_offset'] ) != '' && ( $options['quiz_answer_box_shadow_y_offset'] ) != 0) ? esc_attr( intval( $options['quiz_answer_box_shadow_y_offset'] ) ) : 0;

// Answer box Shadow Z offset
$quiz_answer_box_shadow_z_offset = (isset($options['quiz_answer_box_shadow_z_offset']) && ( $options['quiz_answer_box_shadow_z_offset'] ) != '' && ( $options['quiz_answer_box_shadow_z_offset'] ) != 0) ? esc_attr( intval( $options['quiz_answer_box_shadow_z_offset'] ) ) : 10;

// Change the author of the current quiz
$change_quiz_create_author = (isset($options['quiz_create_author']) && $options['quiz_create_author'] != '') ? absint( sanitize_text_field( $options['quiz_create_author'] ) ) : $user_id;

if( $change_quiz_create_author  && $change_quiz_create_author > 0 ){
    global $wpdb;
    $users_table = esc_sql( $wpdb->base_prefix . 'users' );

    $sql_users = "SELECT ID,display_name FROM {$users_table} WHERE ID = {$change_quiz_create_author}";

    $ays_quiz_create_author_data = $wpdb->get_row($sql_users, "ARRAY_A");

    if( is_null( $ays_quiz_create_author_data ) || empty($ays_quiz_create_author_data) ){
        $change_quiz_create_author = $user_id;
        $ays_quiz_create_author_data = array(
            "ID" => $user_id,
            "display_name" => $user->data->display_name,
        );
    }
} else {
    $change_quiz_create_author = $user_id;
    $ays_quiz_create_author_data = array(
        "ID" => $user_id,
        "display_name" => $user->data->display_name,
    );
}

// Quiz title text shadow
$options['quiz_enable_title_text_shadow'] = isset($options['quiz_enable_title_text_shadow']) ? esc_attr($options['quiz_enable_title_text_shadow']) : 'off';
$quiz_enable_title_text_shadow = (isset($options['quiz_enable_title_text_shadow']) && $options['quiz_enable_title_text_shadow'] == 'on') ? true : false;

// Quiz title text shadow color
$quiz_title_text_shadow_color = (isset($options['quiz_title_text_shadow_color']) && $options['quiz_title_text_shadow_color'] != '') ? esc_attr($options['quiz_title_text_shadow_color']) : '#333';

// Quiz Title Text Shadow X offset
$quiz_title_text_shadow_x_offset = (isset($options['quiz_title_text_shadow_x_offset']) && ( $options['quiz_title_text_shadow_x_offset'] ) != '' && ( $options['quiz_title_text_shadow_x_offset'] ) != 0) ? esc_attr( intval( $options['quiz_title_text_shadow_x_offset'] ) ) : 2;

// Quiz Title Text Shadow Y offset
$quiz_title_text_shadow_y_offset = (isset($options['quiz_title_text_shadow_y_offset']) && ( $options['quiz_title_text_shadow_y_offset'] ) != '' && ( $options['quiz_title_text_shadow_y_offset'] ) != 0) ? esc_attr( intval( $options['quiz_title_text_shadow_y_offset'] ) ) : 2;

// Quiz Title Text Shadow Z offset
$quiz_title_text_shadow_z_offset = (isset($options['quiz_title_text_shadow_z_offset']) && ( $options['quiz_title_text_shadow_z_offset'] ) != '' && ( $options['quiz_title_text_shadow_z_offset'] ) != 0) ? esc_attr( intval( $options['quiz_title_text_shadow_z_offset'] ) ) : 2;

// Show only wrong answers
$options['quiz_show_only_wrong_answers'] = isset($options['quiz_show_only_wrong_answers']) ? sanitize_text_field($options['quiz_show_only_wrong_answers']) : 'off';
$quiz_show_only_wrong_answers = (isset($options['quiz_show_only_wrong_answers']) && $options['quiz_show_only_wrong_answers'] == 'on') ? true : false;

// Quiz title font size
$quiz_title_font_size = (isset($options['quiz_title_font_size']) && ( $options['quiz_title_font_size'] ) != '' && ( $options['quiz_title_font_size'] ) != 0) ? esc_attr( absint( $options['quiz_title_font_size'] ) ) : 28;

// Quiz title font size | On mobile
$quiz_title_mobile_font_size = (isset($options['quiz_title_mobile_font_size']) && sanitize_text_field($options['quiz_title_mobile_font_size']) != '') ? esc_attr( absint($options['quiz_title_mobile_font_size']) ) : 20;

// Quiz password width
$quiz_password_width = (isset($options['quiz_password_width']) && ( $options['quiz_password_width'] ) != '' && ( $options['quiz_password_width'] ) != 0) ? esc_attr( absint( $options['quiz_password_width'] ) ) : "";

// Enable quiz assessment | Placeholder text
$quiz_review_placeholder_text = (isset($options['quiz_review_placeholder_text']) && $options['quiz_review_placeholder_text'] != '') ? stripslashes( esc_attr( $options['quiz_review_placeholder_text'] ) ) : "";

// Make review required
$options['quiz_make_review_required'] = isset($options['quiz_make_review_required']) ? sanitize_text_field($options['quiz_make_review_required']) : 'off';
$quiz_make_review_required = (isset($options['quiz_make_review_required']) && $options['quiz_make_review_required'] == 'on') ? true : false;

// Enable the Show/Hide toggle
$options['quiz_enable_results_toggle'] = isset($options['quiz_enable_results_toggle']) ? sanitize_text_field($options['quiz_enable_results_toggle']) : 'off';
$quiz_enable_results_toggle = (isset($options['quiz_enable_results_toggle']) && $options['quiz_enable_results_toggle'] == 'on') ? true : false;

// Thank you message | Review
$quiz_review_thank_you_message = (isset($options['quiz_review_thank_you_message']) && $options['quiz_review_thank_you_message'] != '') ? stripslashes( wpautop( $options['quiz_review_thank_you_message'] ) ) : "";

// Enable Comment Field
$options['quiz_review_enable_comment_field'] = isset($options['quiz_review_enable_comment_field']) ? sanitize_text_field($options['quiz_review_enable_comment_field']) : 'on';
$quiz_review_enable_comment_field = (isset($options['quiz_review_enable_comment_field']) && $options['quiz_review_enable_comment_field'] == 'on') ? true : false;

// Font size for the question explanation | PC
$quest_explanation_font_size = (isset($options['quest_explanation_font_size']) && $options['quest_explanation_font_size'] != '') ? absint(esc_attr($options['quest_explanation_font_size'])) : '16';

// Font size for the question explanation | Mobile
$quest_explanation_mobile_font_size = (isset($options['quest_explanation_mobile_font_size']) && $options['quest_explanation_mobile_font_size'] != '') ? absint(esc_attr($options['quest_explanation_mobile_font_size'])) : $quest_explanation_font_size;

// Waiting time
$options['quiz_waiting_time'] = isset($options['quiz_waiting_time']) ? esc_attr($options['quiz_waiting_time']) : 'off';
$quiz_waiting_time = (isset($options['quiz_waiting_time']) && $options['quiz_waiting_time'] == 'on') ? true : false;

// Font size for the wrong answer
$wrong_answers_font_size = (isset($options['wrong_answers_font_size']) && $options['wrong_answers_font_size'] != '') ? absint(esc_attr($options['wrong_answers_font_size'])) : '16';

// Font size for the wrong answer | Mobile
$wrong_answers_mobile_font_size = (isset($options['wrong_answers_mobile_font_size']) && $options['wrong_answers_mobile_font_size'] != '') ? absint(esc_attr($options['wrong_answers_mobile_font_size'])) : $wrong_answers_font_size;

// Question Image Zoom
$options['quiz_enable_question_image_zoom'] = isset($options['quiz_enable_question_image_zoom']) ? esc_attr($options['quiz_enable_question_image_zoom']) : 'off';
$quiz_enable_question_image_zoom = (isset($options['quiz_enable_question_image_zoom']) && $options['quiz_enable_question_image_zoom'] == 'on') ? true : false;

// Font size for the right answer | PC
$right_answers_font_size = (isset($options['right_answers_font_size']) && $options['right_answers_font_size'] != '') ? absint(esc_attr($options['right_answers_font_size'])) : '16';

// Font size for the right answer | Mobile
$right_answers_mobile_font_size = (isset($options['right_answers_mobile_font_size']) && $options['right_answers_mobile_font_size'] != '') ? absint(esc_attr($options['right_answers_mobile_font_size'])) : $right_answers_font_size;

// Display Messages before the buttons
$options['quiz_display_messages_before_buttons'] = isset($options['quiz_display_messages_before_buttons']) ? esc_attr($options['quiz_display_messages_before_buttons']) : 'off';
$quiz_display_messages_before_buttons = (isset($options['quiz_display_messages_before_buttons']) && $options['quiz_display_messages_before_buttons'] == 'on') ? true : false;

// Enable users' anonymous assessment
$options['quiz_enable_user_cհoosing_anonymous_assessment'] = isset($options['quiz_enable_user_cհoosing_anonymous_assessment']) ? sanitize_text_field($options['quiz_enable_user_cհoosing_anonymous_assessment']) : 'off';
$quiz_enable_user_cհoosing_anonymous_assessment = (isset($options['quiz_enable_user_cհoosing_anonymous_assessment']) && $options['quiz_enable_user_cհoosing_anonymous_assessment'] == 'on') ? true : false;

// Font size for the Note text | PC
$note_text_font_size = (isset($options['note_text_font_size']) && $options['note_text_font_size'] != '') ? absint(esc_attr($options['note_text_font_size'])) : '14';

// Font size for the Note text | Mobile
$note_text_mobile_font_size = (isset($options['note_text_mobile_font_size']) && $options['note_text_mobile_font_size'] != '') ? absint(esc_attr($options['note_text_mobile_font_size'])) : $note_text_font_size;

// Enable questions numbering by category
$options['quiz_questions_numbering_by_category'] = isset($options['quiz_questions_numbering_by_category']) ? sanitize_text_field($options['quiz_questions_numbering_by_category']) : 'off';
$quiz_questions_numbering_by_category = (isset($options['quiz_questions_numbering_by_category']) && $options['quiz_questions_numbering_by_category'] == 'on') ? true : false;

$embed_code_html = '<iframe width="400" height="500" frameborder="0" scrolling="yes" layout="responsive" sandbox="" resizable id="aysQuizIframe"></iframe>';

// Enable custom texts for buttons
$options['quiz_enable_custom_texts_for_buttons'] = isset($options['quiz_enable_custom_texts_for_buttons']) ? sanitize_text_field($options['quiz_enable_custom_texts_for_buttons']) : 'off';
$quiz_enable_custom_texts_for_buttons = (isset($options['quiz_enable_custom_texts_for_buttons']) && $options['quiz_enable_custom_texts_for_buttons'] == 'on') ? true : false;

$quiz_custom_texts_start_button = (isset($options['quiz_custom_texts_start_button']) && $options['quiz_custom_texts_start_button'] != '') ? stripslashes( esc_attr( $options['quiz_custom_texts_start_button'] ) ) : $start_button;

$quiz_custom_texts_next_button = (isset($options['quiz_custom_texts_next_button']) && $options['quiz_custom_texts_next_button'] != '') ? stripslashes( esc_attr( $options['quiz_custom_texts_next_button'] ) ) : $next_button;

$quiz_custom_texts_prev_button = (isset($options['quiz_custom_texts_prev_button']) && $options['quiz_custom_texts_prev_button'] != '') ? stripslashes( esc_attr( $options['quiz_custom_texts_prev_button'] ) ) : $previous_button;

$quiz_custom_texts_clear_button = (isset($options['quiz_custom_texts_clear_button']) && $options['quiz_custom_texts_clear_button'] != '') ? stripslashes( esc_attr( $options['quiz_custom_texts_clear_button'] ) ) : $clear_button;

$quiz_custom_texts_finish_button = (isset($options['quiz_custom_texts_finish_button']) && $options['quiz_custom_texts_finish_button'] != '') ? stripslashes( esc_attr( $options['quiz_custom_texts_finish_button'] ) ) : $finish_button;

$quiz_custom_texts_see_results_button = (isset($options['quiz_custom_texts_see_results_button']) && $options['quiz_custom_texts_see_results_button'] != '') ? stripslashes( esc_attr( $options['quiz_custom_texts_see_results_button'] ) ) : $see_result_button;

$quiz_custom_texts_restart_quiz_button = (isset($options['quiz_custom_texts_restart_quiz_button']) && $options['quiz_custom_texts_restart_quiz_button'] != '') ? stripslashes( esc_attr( $options['quiz_custom_texts_restart_quiz_button'] ) ) : $restart_quiz_button;

$quiz_custom_texts_send_feedback_button = (isset($options['quiz_custom_texts_send_feedback_button']) && $options['quiz_custom_texts_send_feedback_button'] != '') ? stripslashes( esc_attr( $options['quiz_custom_texts_send_feedback_button'] ) ) : $send_feedback_button;

$quiz_custom_texts_load_more_button = (isset($options['quiz_custom_texts_load_more_button']) && $options['quiz_custom_texts_load_more_button'] != '') ? stripslashes( esc_attr( $options['quiz_custom_texts_load_more_button'] ) ) : $load_more_button;

$quiz_custom_texts_exit_button = (isset($options['quiz_custom_texts_exit_button']) && $options['quiz_custom_texts_exit_button'] != '') ? stripslashes( esc_attr( $options['quiz_custom_texts_exit_button'] ) ) : $gen_exit_button;

$quiz_custom_texts_check_button = (isset($options['quiz_custom_texts_check_button']) && $options['quiz_custom_texts_check_button'] != '') ? stripslashes( esc_attr( $options['quiz_custom_texts_check_button'] ) ) : $gen_check_button;

$quiz_custom_texts_login_button = (isset($options['quiz_custom_texts_login_button']) && $options['quiz_custom_texts_login_button'] != '') ? stripslashes( esc_attr( $options['quiz_custom_texts_login_button'] ) ) : $gen_login_button;

// Show quiz category description
$options['quiz_enable_quiz_category_description'] = isset($options['quiz_enable_quiz_category_description']) ? $options['quiz_enable_quiz_category_description'] : 'off';
$quiz_enable_quiz_category_description = (isset($options['quiz_enable_quiz_category_description']) && $options['quiz_enable_quiz_category_description'] == 'on') ? true : false;

// Note text transform size
$quiz_admin_note_text_transform = (isset($options[ 'quiz_admin_note_text_transform' ]) && $options[ 'quiz_admin_note_text_transform' ] != '') ? stripslashes ( esc_attr( $options[ 'quiz_admin_note_text_transform' ] ) ) : 'none';

// Question explanation transform size
$quiz_quest_explanation_text_transform = (isset($options[ 'quiz_quest_explanation_text_transform' ]) && $options[ 'quiz_quest_explanation_text_transform' ] != '') ? stripslashes ( esc_attr( $options[ 'quiz_quest_explanation_text_transform' ] ) ) : 'none';

// Right answer transform size
$quiz_right_answer_text_transform = (isset($options[ 'quiz_right_answer_text_transform' ]) && $options[ 'quiz_right_answer_text_transform' ] != '') ? stripslashes ( esc_attr( $options[ 'quiz_right_answer_text_transform' ] ) ) : 'none';

// Wrong answer transform size
$quiz_wrong_answer_text_transform = (isset($options[ 'quiz_wrong_answer_text_transform' ]) && $options[ 'quiz_wrong_answer_text_transform' ] != '') ? stripslashes ( esc_attr( $options[ 'quiz_wrong_answer_text_transform' ] ) ) : 'none';

// Note text decoration
$quiz_admin_note_text_decoration = (isset($options[ 'quiz_admin_note_text_decoration' ]) && $options[ 'quiz_admin_note_text_decoration' ] != '') ? stripslashes ( esc_attr( $options[ 'quiz_admin_note_text_decoration' ] ) ) : 'none';

// Question Explanation text decoration
$quiz_quest_explanation_text_decoration = (isset($options[ 'quiz_quest_explanation_text_decoration' ]) && $options[ 'quiz_quest_explanation_text_decoration' ] != '') ? stripslashes ( esc_attr( $options[ 'quiz_quest_explanation_text_decoration' ] ) ) : 'none';

// Right answer text decoration
$quiz_right_answers_text_decoration = (isset($options[ 'quiz_right_answers_text_decoration' ]) && $options[ 'quiz_right_answers_text_decoration' ] != '') ? stripslashes ( esc_attr( $options[ 'quiz_right_answers_text_decoration' ] ) ) : 'none';

// Wrong answer text decoration
$quiz_wrong_answers_text_decoration = (isset($options[ 'quiz_wrong_answers_text_decoration' ]) && $options[ 'quiz_wrong_answers_text_decoration' ] != '') ? stripslashes ( esc_attr( $options[ 'quiz_wrong_answers_text_decoration' ] ) ) : 'none';

// Note letter spacing | Admin note
$quiz_admin_note_letter_spacing = (isset($options[ 'quiz_admin_note_letter_spacing' ]) && $options[ 'quiz_admin_note_letter_spacing' ] != '') ? stripslashes ( absint( $options[ 'quiz_admin_note_letter_spacing' ] ) ) : 0;

// Hide background image during the quiz
$options['quiz_bg_img_during_the_quiz'] = isset($options['quiz_bg_img_during_the_quiz']) ? $options['quiz_bg_img_during_the_quiz'] : 'off';
$quiz_bg_img_during_the_quiz = (isset($options['quiz_bg_img_during_the_quiz']) && $options['quiz_bg_img_during_the_quiz'] == 'on') ? true : false;

// Letter spacing | Question Explanation
$quiz_quest_explanation_letter_spacing = (isset($options[ 'quiz_quest_explanation_letter_spacing' ]) && $options[ 'quiz_quest_explanation_letter_spacing' ] != '') ? stripslashes ( absint( $options[ 'quiz_quest_explanation_letter_spacing' ] ) ) : 0;

// Letter spacing | Right answer
$quiz_right_answers_letter_spacing = (isset($options[ 'quiz_right_answers_letter_spacing' ]) && $options[ 'quiz_right_answers_letter_spacing' ] != '') ? stripslashes ( absint( $options[ 'quiz_right_answers_letter_spacing' ] ) ) : 0;

// Letter spacing | Wrong answer
$quiz_wrong_answers_letter_spacing = (isset($options[ 'quiz_wrong_answers_letter_spacing' ]) && $options[ 'quiz_wrong_answers_letter_spacing' ] != '') ? stripslashes ( absint( $options[ 'quiz_wrong_answers_letter_spacing' ] ) ) : 0;

// Admin Note font weight
$quiz_admin_note_font_weight = (isset($options[ 'quiz_admin_note_font_weight' ]) && $options[ 'quiz_admin_note_font_weight' ] != '') ? stripslashes ( esc_attr( $options[ 'quiz_admin_note_font_weight' ] ) ) : 'normal';

// Question Explanation font weight
$quiz_quest_explanation_font_weight = (isset($options[ 'quiz_quest_explanation_font_weight' ]) && $options[ 'quiz_quest_explanation_font_weight' ] != '') ? stripslashes ( esc_attr( $options[ 'quiz_quest_explanation_font_weight' ] ) ) : 'normal';

// Right answer font weight
$quiz_right_answers_font_weight = (isset($options[ 'quiz_right_answers_font_weight' ]) && $options[ 'quiz_right_answers_font_weight' ] != '') ? stripslashes ( esc_attr( $options[ 'quiz_right_answers_font_weight' ] ) ) : 'normal';

// Wrong answer font weight
$quiz_wrong_answers_font_weight = (isset($options[ 'quiz_wrong_answers_font_weight' ]) && $options[ 'quiz_wrong_answers_font_weight' ] != '') ? stripslashes ( esc_attr( $options[ 'quiz_wrong_answers_font_weight' ] ) ) : 'normal';

// Quiz Section collapse flag
// $options['quiz_section_collapse_flag'] = isset($options['quiz_section_collapse_flag']) ? $options['quiz_section_collapse_flag'] : 'off';
// $quiz_section_collapse_flag_input = (isset($options['quiz_section_collapse_flag']) && $options['quiz_section_collapse_flag'] == 'on') ? 'on' : 'off';
// $quiz_section_collapse_flag = (isset($options['quiz_section_collapse_flag']) && $options['quiz_section_collapse_flag'] == 'on') ? 'true' : 'false';

// Quiz content max-width
$quiz_content_max_width = ( isset($options['quiz_content_max_width']) && $options['quiz_content_max_width'] != "" && intval($options['quiz_content_max_width']) > 0 ) ? intval(esc_attr($options['quiz_content_max_width'])) : 90;

// Quiz content mobile max-width
$quiz_content_mobile_max_width = ( isset($options['quiz_content_mobile_max_width']) && $options['quiz_content_mobile_max_width'] != "" && intval($options['quiz_content_mobile_max_width']) > 0 ) ? intval(esc_attr($options['quiz_content_mobile_max_width'])) : 90;

// Timer Warning text color
$quiz_timer_warning_text_color = (isset($options['quiz_timer_warning_text_color']) && $options['quiz_timer_warning_text_color'] != '') ? stripslashes ( esc_attr( $options[ 'quiz_timer_warning_text_color' ] ) ) : '#ff0000';

// Enable default Hide toggle
$options['quiz_enable_default_hide_results_toggle'] = isset($options['quiz_enable_default_hide_results_toggle']) ? sanitize_text_field($options['quiz_enable_default_hide_results_toggle']) : 'off';
$quiz_enable_default_hide_results_toggle = (isset($options['quiz_enable_default_hide_results_toggle']) && $options['quiz_enable_default_hide_results_toggle'] == 'on') ? true : false;

// Show Restart button on quiz fail
$options['quiz_show_restart_button_on_quiz_fail'] = isset($options['quiz_show_restart_button_on_quiz_fail']) ? sanitize_text_field($options['quiz_show_restart_button_on_quiz_fail']) : 'off';
$quiz_show_restart_button_on_quiz_fail = (isset($options['quiz_show_restart_button_on_quiz_fail']) && $options['quiz_show_restart_button_on_quiz_fail'] == 'on') ? true : false;

// Disable input focusing
$options[ 'quiz_disable_input_focusing' ] = isset($options[ 'quiz_disable_input_focusing' ]) ? $options[ 'quiz_disable_input_focusing' ] : 'off';
$quiz_disable_input_focusing = (isset($options[ 'quiz_disable_input_focusing' ]) && $options[ 'quiz_disable_input_focusing' ] == 'on') ? true : false;

//Enable keyboard navigation
$options['quiz_enable_keyboard_navigation'] = isset($options['quiz_enable_keyboard_navigation']) ? $options['quiz_enable_keyboard_navigation'] : 'on';
$quiz_enable_keyboard_navigation = (isset($options['quiz_enable_keyboard_navigation']) && $options['quiz_enable_keyboard_navigation'] == 'on') ? true : false;
// if( $action == 'add' ){
//     $quiz_enable_keyboard_navigation = true;
// }

// Note text transform size | Mobile
$quiz_admin_note_mobile_text_transform = (isset($options[ 'quiz_admin_note_mobile_text_transform' ]) && $options[ 'quiz_admin_note_mobile_text_transform' ] != '') ? stripslashes ( esc_attr( $options[ 'quiz_admin_note_mobile_text_transform' ] ) ) : $quiz_admin_note_text_transform;

// Question explanation transform size | Mobile
$quiz_quest_explanation_mobile_text_transform = (isset($options[ 'quiz_quest_explanation_mobile_text_transform' ]) && $options[ 'quiz_quest_explanation_mobile_text_transform' ] != '') ? stripslashes ( esc_attr( $options[ 'quiz_quest_explanation_mobile_text_transform' ] ) ) : $quiz_quest_explanation_text_transform;

// Right answer text transform size | Mobile
$quiz_right_answer_mobile_text_transform = (isset($options[ 'quiz_right_answer_mobile_text_transform' ]) && $options[ 'quiz_right_answer_mobile_text_transform' ] != '') ? stripslashes ( esc_attr( $options[ 'quiz_right_answer_mobile_text_transform' ] ) ) : $quiz_right_answer_text_transform;

// Wrong answer text transform size | Mobile
$quiz_wrong_answer_mobile_text_transform = (isset($options[ 'quiz_wrong_answer_mobile_text_transform' ]) && $options[ 'quiz_wrong_answer_mobile_text_transform' ] != '') ? stripslashes ( esc_attr( $options[ 'quiz_wrong_answer_mobile_text_transform' ] ) ) : $quiz_wrong_answer_text_transform;

// Note text decoration | On mobile
$quiz_admin_note_mobile_text_decoration = (isset($options[ 'quiz_admin_note_mobile_text_decoration' ]) && $options[ 'quiz_admin_note_mobile_text_decoration' ] != '') ? stripslashes ( esc_attr( $options[ 'quiz_admin_note_mobile_text_decoration' ] ) ) : $quiz_admin_note_text_decoration;

// Dont show quiz
$options['quiz_dont_show_quiz'] = isset($options['quiz_dont_show_quiz']) ? stripslashes ( esc_attr($options['quiz_dont_show_quiz']) ) : 'off';
$quiz_dont_show_quiz = (isset($options['quiz_dont_show_quiz']) && $options['quiz_dont_show_quiz'] == 'on') ? true : false;

// enable question reporting
$options['enable_questions_reporting'] = isset($options['enable_questions_reporting']) ? $options['enable_questions_reporting'] : 'off';
$enable_question_reporting = (isset($options['enable_questions_reporting']) && $options['enable_questions_reporting'] == "on") ? true : false;

// Question Explanation text decoration | Mobile
$quiz_quest_explanation_mobile_text_decoration = (isset($options[ 'quiz_quest_explanation_mobile_text_decoration' ]) && $options[ 'quiz_quest_explanation_mobile_text_decoration' ] != '') ? stripslashes ( esc_attr( $options[ 'quiz_quest_explanation_mobile_text_decoration' ] ) ) : $quiz_quest_explanation_text_decoration;

// Right answer text decoration | Mobile
$quiz_right_answers_mobile_text_decoration = (isset($options[ 'quiz_right_answers_mobile_text_decoration' ]) && $options[ 'quiz_right_answers_mobile_text_decoration' ] != '') ? stripslashes ( esc_attr( $options[ 'quiz_right_answers_mobile_text_decoration' ] ) ) : $quiz_right_answers_text_decoration;

// Answers image height
$ans_img_height = (isset($options['ans_img_height']) && $options['ans_img_height'] != '') ? stripslashes (absint($options['ans_img_height']) ) : 150;

// Show answers caption
$options['show_answers_caption'] = isset($options['show_answers_caption']) ? stripslashes ( esc_attr($options['show_answers_caption']) ) : 'on';
$show_answers_caption = (isset($options['show_answers_caption']) && $options['show_answers_caption'] == 'on') ? true : false;

$ans_img_caption_position = (isset($options['ans_img_caption_position']) && $options['ans_img_caption_position'] != '') ? stripslashes ( esc_attr($options['ans_img_caption_position']) ) : 'bottom';

// Wrong answer text decoration | Mobile
$quiz_wrong_answers_mobile_text_decoration = (isset($options[ 'quiz_wrong_answers_mobile_text_decoration' ]) && $options[ 'quiz_wrong_answers_mobile_text_decoration' ] != '') ? stripslashes ( esc_attr( $options[ 'quiz_wrong_answers_mobile_text_decoration' ] ) ) : $quiz_wrong_answers_text_decoration;

// Note letter spacing | Admin note | Mobile
$quiz_admin_note_mobile_letter_spacing = (isset($options[ 'quiz_admin_note_mobile_letter_spacing' ]) && $options[ 'quiz_admin_note_mobile_letter_spacing' ] != '') ? absint ( stripslashes( $options[ 'quiz_admin_note_mobile_letter_spacing' ] ) ) : $quiz_admin_note_letter_spacing;

// Letter spacing | Question Explanation | Mobile
$quiz_quest_explanation_mobile_letter_spacing = (isset($options[ 'quiz_quest_explanation_mobile_letter_spacing' ]) && $options[ 'quiz_quest_explanation_mobile_letter_spacing' ] != '') ? absint ( stripslashes( $options[ 'quiz_quest_explanation_mobile_letter_spacing' ] ) ) : $quiz_quest_explanation_letter_spacing;

// Letter spacing | Right answer | Mobile
$quiz_right_answers_mobile_letter_spacing = (isset($options[ 'quiz_right_answers_mobile_letter_spacing' ]) && $options[ 'quiz_right_answers_mobile_letter_spacing' ] != '') ? absint ( stripslashes( $options[ 'quiz_right_answers_mobile_letter_spacing' ] ) ) : $quiz_right_answers_letter_spacing;

// Letter spacing | Wrong answer | Mobile
$quiz_wrong_answers_mobile_letter_spacing = (isset($options[ 'quiz_wrong_answers_mobile_letter_spacing' ]) && $options[ 'quiz_wrong_answers_mobile_letter_spacing' ] != '') ? absint ( stripslashes( $options[ 'quiz_wrong_answers_mobile_letter_spacing' ] ) ) : $quiz_wrong_answers_letter_spacing;


?>
<style id="ays_live_custom_css"></style>
<div class="wrap ays-quiz-dashboard-main-wrap">
    <div class="container-fluid">
        <?php if(1 == 0): ?>
        <div class="ays-quiz-heading-box ays-quiz-heading-box-margin-top">
            <div class="ays-quiz-wordpress-user-manual-box">
                <a href="https://www.youtube.com/watch?v=gKjzOsn_yDo" target="_blank" style="text-decoration: none;font-size: 13px;">
                    <span><img loading="lazy" src='<?php echo esc_url(AYS_QUIZ_ADMIN_URL); ?>/images/icons/youtube-video-icon.svg' ></span>
                    <span style="margin-left: 3px; text-decoration: underline;"><?php echo esc_html__('Getting started', "quiz-maker"); ?></span>
                </a>
                <a href="https://quiz-plugin.com/docs/" target="_blank">
                    <i class="ays_fa ays_fa_file_text" ></i> 
                    <span style="margin-left: 3px;text-decoration: underline;"><?php echo esc_html__("View Documentation", "quiz-maker"); ?></span>
                </a>
            </div>
        </div>
        <?php endif; ?>
        <h1 class="wp-heading-inline">
            <?php
                echo esc_html($heading);
            ?>
        </h1>
        <?php do_action('ays_quiz_sale_banner'); ?>

        <?php if($question_max_id <= 3): ?>
        <div class="ays-quiz-admin-notice notice notice-success is-dismissible">
            <p style="font-size:14px;">
                <strong>
                    <?php echo esc_html__( "If you haven't created questions yet, you need to do it first.", 'quiz-maker' ); ?> 
                </strong>
                <br>
                <strong>
                    <em>
                        <?php echo esc_html__( "For creating a question go", 'quiz-maker' ); ?> 
                        <a href="<?php echo esc_url( admin_url('admin.php') . "?page=".$this->plugin_name . "-questions" ); ?>" target="_blank">
                            <?php echo esc_html__( "here", 'quiz-maker' ); ?>.
                        </a>
                    </em>
                </strong>
            </p>
        </div>
        <?php endif; ?>

        <form class="ays-quiz-category-form ays-quiz-main-form" id="ays-quiz-category-form" method="post">
            <input type="hidden" name="ays_quiz_tab" value="<?php echo esc_attr($ays_quiz_tab); ?>">
            <input type="hidden" name="ays_quiz_ctrate_date" value="<?php echo esc_attr($quiz_create_date); ?>">
            <input type="hidden" name="ays_quiz_author" value="<?php echo esc_attr(json_encode($quiz_author, JSON_UNESCAPED_SLASHES)); ?>">
            <input type="hidden" class="quiz_wp_editor_height" value="<?php echo esc_attr($quiz_wp_editor_height); ?>">
            <input type="hidden" class="quiz_question_title_view" value="<?php echo esc_attr($quiz_question_title_view); ?>">
            <!-- <input type="hidden" id="ays_quiz_section_collapse_flag" name="ays_quiz_section_collapse_flag" value="<?php #echo esc_attr($quiz_section_collapse_flag_input); ?>"> -->
            <div>
                <div class="ays-quiz-subtitle-main-box ays-quiz-edit-page-subtitle-container">
                    <p class="ays-subtitle">
                        <strong class="ays_quiz_title_in_top"><?php echo esc_html($quiz_title); ?></strong>
                    </p>
                    <?php if(isset($id) && count($get_all_quizzes) > 1):?>
                    <div class="ays-quiz-open-quizzes-list">
                        <svg width="22" height="23" viewBox="0 0 22 23" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <mask id="mask0_2411_1023" style="mask-type: alpha;" maskUnits="userSpaceOnUse" x="0" y="0" width="22" height="23">
                                <rect y="0.5" width="22" height="22" fill="#D9D9D9" />
                            </mask>
                            <g mask="url(#mask0_2411_1023)">
                                <path
                                    d="M8.25 17.9167V16.0834H19.25V17.9167H8.25ZM8.25 12.4167V10.5834H19.25V12.4167H8.25ZM8.25 6.91669V5.08335H19.25V6.91669H8.25ZM4.58333 18.8334C4.07917 18.8334 3.64757 18.6538 3.28854 18.2948C2.92951 17.9358 2.75 17.5042 2.75 17C2.75 16.4959 2.92951 16.0643 3.28854 15.7052C3.64757 15.3462 4.07917 15.1667 4.58333 15.1667C5.0875 15.1667 5.5191 15.3462 5.87813 15.7052C6.23715 16.0643 6.41667 16.4959 6.41667 17C6.41667 17.5042 6.23715 17.9358 5.87813 18.2948C5.5191 18.6538 5.0875 18.8334 4.58333 18.8334ZM4.58333 13.3334C4.07917 13.3334 3.64757 13.1538 3.28854 12.7948C2.92951 12.4358 2.75 12.0042 2.75 11.5C2.75 10.9959 2.92951 10.5643 3.28854 10.2052C3.64757 9.8462 4.07917 9.66669 4.58333 9.66669C5.0875 9.66669 5.5191 9.8462 5.87813 10.2052C6.23715 10.5643 6.41667 10.9959 6.41667 11.5C6.41667 12.0042 6.23715 12.4358 5.87813 12.7948C5.5191 13.1538 5.0875 13.3334 4.58333 13.3334ZM4.58333 7.83335C4.07917 7.83335 3.64757 7.65384 3.28854 7.29481C2.92951 6.93578 2.75 6.50419 2.75 6.00002C2.75 5.49585 2.92951 5.06426 3.28854 4.70523C3.64757 4.3462 4.07917 4.16669 4.58333 4.16669C5.0875 4.16669 5.5191 4.3462 5.87813 4.70523C6.23715 5.06426 6.41667 5.49585 6.41667 6.00002C6.41667 6.50419 6.23715 6.93578 5.87813 7.29481C5.5191 7.65384 5.0875 7.83335 4.58333 7.83335Z"
                                    fill="#1C1B1F"
                                />
                            </g>
                        </svg>
                        <div class="ays-quiz-quizzes-data">
                            <?php $var_counter = 0; foreach($get_all_quizzes as $var => $var_name): if( intval($var_name['id']) == $id ){continue;} $var_counter++; ?>
                                <?php ?>
                                <label class="ays-quiz-message-vars-each-data-label">
                                    <input type="radio" class="ays-quiz-quizzes-each-data-checker" hidden id="ays_quiz_message_var_count_<?php echo esc_attr($var_counter); ?>" name="ays_quiz_message_var_count">
                                    <div class="ays-quiz-quizzes-each-data">
                                        <input type="hidden" class="ays-quiz-quizzes-each-var" value="<?php echo esc_attr($var); ?>">
                                        <a href="?page=quiz-maker&action=edit&quiz=<?php echo esc_attr($var_name['id']); ?>" target="_blank" class="ays-quiz-go-to-quizzes"><span><?php echo stripslashes(esc_attr($var_name['title'])); ?></span></a>
                                    </div>
                                </label>              
                            <?php endforeach ?>
                        </div>                        
                    </div>
                    <?php endif; ?>
                </div>

                <?php if($id !== null): ?>
                <div class="row">
                    <div class="col-sm-12">
                        <p style="font-size:14px; font-style:italic;">
                            <?php echo esc_html__("To make your quiz live, copy shortcode", 'quiz-maker'); ?>
                            <strong class="ays-quiz-shortcode-box" onClick="selectElementContents(this)" class="ays_help" data-toggle="tooltip" title="<?php echo esc_html__('Click for copy.','quiz-maker');?>" style="font-size:16px; font-style:normal;"><?php echo esc_html("[ays_quiz id='". intval($id) ."']"); ?></strong>
                            <?php echo esc_html(" ") . esc_html__( "and paste it into your desired Page or Post.", 'quiz-maker'); ?>
                        </p>
                    </div>
                </div>
                <?php endif;?>
                <div id="ays-quiz-tab-doc-link" class="ays-quiz-heading-box" style="margin-bottom: 40px;">
                    <div class="ays-quiz-wordpress-user-manual-box">
                        <a href="https://www.youtube.com/watch?v=gKjzOsn_yDo" target="_blank" style="text-decoration: none;font-size: 13px;">
                            <span><img loading="lazy" src='<?php echo esc_url(AYS_QUIZ_ADMIN_URL); ?>/images/icons/youtube-video-icon.svg' ></span>
                            <span style="margin-left: 3px; text-decoration: underline;"><?php echo esc_html__('Getting started', "quiz-maker"); ?></span>
                        </a>
                        <a class="ays-quiz-doc-link" href="<?php echo isset($tab_docs[$ays_quiz_tab]['link']) ? esc_url($tab_docs[$ays_quiz_tab]['link']) : 'https://quiz-plugin.com/docs/how-to-create-a-quiz/'; ?>" target="_blank">
                            <i class="ays_fa ays_fa_file_text" ></i> 
                            <span style="margin-left: 3px;text-decoration: underline;"><?php echo isset($tab_docs[$ays_quiz_tab]['text']) ? esc_html($tab_docs[$ays_quiz_tab]['text']) : __('How to Configure General Settings?', 'quiz-maker'); ?></span>
                        </a>
                    </div>
                </div>
            </div>
            <hr/>
            <div class="ays-top-menu-container-wrapper">
                <div class="ays-top-menu-wrapper">
                    <div class="ays_menu_left" data-scroll="0"><i class="ays_fa ays_fa_angle_left"></i></div>
                    <div class="ays-top-menu">
                        <div class="nav-tab-wrapper ays-top-tab-wrapper">
                            <a href="#tab1" data-tab="tab1" class="nav-tab <?php echo ($ays_quiz_tab == 'tab1') ? 'nav-tab-active' : ''; ?>">
                                <?php echo esc_html__("General", 'quiz-maker');?>
                            </a>
                            <a href="#tab2" data-tab="tab2" class="nav-tab <?php echo ($ays_quiz_tab == 'tab2') ? 'nav-tab-active' : ''; ?>">
                                <?php echo esc_html__("Styles", 'quiz-maker');?>
                            </a>
                            <a href="#tab3" data-tab="tab3" class="nav-tab <?php echo ($ays_quiz_tab == 'tab3') ? 'nav-tab-active' : ''; ?>">
                                <?php echo esc_html__("Settings", 'quiz-maker');?>
                            </a>
                            <a href="#tab4" data-tab="tab4" class="nav-tab <?php echo ($ays_quiz_tab == 'tab4') ? 'nav-tab-active' : ''; ?>">
                                <?php echo esc_html__("Results Settings", 'quiz-maker');?>
                            </a>
                            <a href="#tab5" data-tab="tab5" class="nav-tab <?php echo ($ays_quiz_tab == 'tab5') ? 'nav-tab-active' : ''; ?>">
                                <?php echo esc_html__("Limitation Users", 'quiz-maker');?>
                            </a>
                            <a href="#tab6" data-tab="tab6" class="nav-tab <?php echo ($ays_quiz_tab == 'tab6') ? 'nav-tab-active' : ''; ?>">
                                <?php echo esc_html__("User Data", 'quiz-maker');?>
                            </a>
                            <a href="#tab7" data-tab="tab7" class="nav-tab <?php echo ($ays_quiz_tab == 'tab7') ? 'nav-tab-active' : ''; ?>">
                                <?php echo esc_html__("E-Mail, Certificate", 'quiz-maker');?>
                            </a>
                            <a href="#tab8" data-tab="tab8" class="nav-tab <?php echo ($ays_quiz_tab == 'tab8') ? 'nav-tab-active' : ''; ?>">
                                <?php echo esc_html__("Integrations", 'quiz-maker');?>
                            </a>
                        </div>
                    </div>              
                    <div class="ays_menu_right" data-scroll="-1"><i class="ays_fa ays_fa_angle_right"></i></div>
                </div>
                <div class="ays-quiz-add-new-button-box ays-quiz-add-new-button-quiz-edit-box top-menu-buttons-container">
                <?php
                    $other_attributes = array();

                    $other_attributes_only_save = array(
                        'title' => 'Ctrl + s',
                        'data-toggle' => 'tooltip',
                        'data-delay'=> '{"show":"1000"}'
                    );
                    echo wp_kses($loader_iamge, $quiz_allowed_html);
                    submit_button(esc_html__('Save', 'quiz-maker'), 'primary ays-quiz-loader-banner', 'ays_apply_top', false, $other_attributes_only_save);
                    submit_button(esc_html__('Save and close', 'quiz-maker'), 'ays-quiz-loader-banner ays-quiz-submit-button-margin-unset', 'ays_submit_top', false, $other_attributes);
                    submit_button(esc_html__('Cancel', "quiz-maker"), 'ays-quiz-loader-banner', 'ays_quiz_cancel_top', false, array());
                ?>
                </div>
            </div>

            <div id="tab1" class="ays-quiz-tab-content <?php echo ($ays_quiz_tab == 'tab1') ? 'ays-quiz-tab-content-active' : ''; ?>">
                <p class="ays-subtitle"><?php echo esc_html__('General Settings','quiz-maker')?></p>
                <hr class="ays-quiz-bolder-hr"/>
                <div class="form-group row">
                    <div class="col-sm-2">
                        <label for='ays-quiz-title'>
                            <?php echo esc_html__('Title', 'quiz-maker'); ?>
                            <a class="ays_help" data-toggle="tooltip" title="<?php echo esc_html__('Title of the quiz','quiz-maker')?>">
                                <i class="ays_fa ays_fa_info_circle"></i>
                            </a>
                        </label>
                    </div>
                    <div class="col-sm-10">
                        <input type="text" class="ays-text-input" id='ays-quiz-title' name='ays_quiz_title'
                               value="<?php echo esc_attr($quiz_title); ?>"/>
                    </div>
                </div> <!-- Title of the quiz -->
                <hr/>
                <div class="form-group row ays-field">
                    <div class="col-sm-2">
                        <label>
                            <?php echo esc_html__('Quiz image', 'quiz-maker'); ?>
                            <a class="ays_help" data-toggle="tooltip" title="<?php echo esc_html__('Add image to the starting page of the quiz','quiz-maker')?>">
                                <i class="ays_fa ays_fa_info_circle"></i>
                            </a>
                        </label>
                    </div>
                    <div class="col-sm-10">
                        <a href="javascript:void(0)" class="add-quiz-image" style="margin: 0;"><?php echo esc_html($image_text); ?></a>
                        <p class="ays_quiz_small_hint_text_for_message_variables" style="margin-top: 5px;">
                            <span><?php echo esc_html__( "Please Note" , 'quiz-maker' ); ?></span>
                            <a class="ays_help" data-toggle="tooltip" title="<?php echo esc_attr( __('* Note: The plugin doesn’t make any changes concerning the images. It takes the images in a size, in which you have uploaded them. We are using the default WP Media. If the uploaded image is blurred and has a low quality, make sure to choose the right parameters (Full size) while uploading the images. You can find the Full Size option in the opened Add Media popup (Attachment Display Settings).','quiz-maker') ); ?>">
                                <i class="ays_fa ays_fa_info_circle"></i>
                            </a>
                        </p>
                        <div class="ays-quiz-image-container" style="<?php echo !empty($style) ? esc_attr($style) : ''; ?>">
                            <span class="ays-remove-quiz-img"></span>
                            <img loading="lazy" src="<?php echo esc_url($quiz_image); ?>" id="ays-quiz-img"/>
                            <input type="hidden" name="ays_quiz_image" id="ays-quiz-image" value="<?php echo esc_url($quiz_image); ?>"/>
                        </div>
                    </div>
                </div><!-- Quiz Image -->
                <hr/>
                <div class="form-group row ays-field ays-quiz-result-message-vars-parent">
                    <div class="col-sm-2">
                        <label for='ays-quiz-description'>
                            <?php echo esc_html__('Description', 'quiz-maker'); ?>
                            <a class="ays_help" data-toggle="tooltip" title="<?php echo esc_attr( __('Provide more information about the quiz. You can choose whether to show it or not in the front end in the “Settings” tab','quiz-maker') ); ?>">
                                <i class="ays_fa ays_fa_info_circle"></i>
                            </a>
                        </label>
                    </div>
                    <div class="col-sm-10">
                        <?php
                            echo wp_kses($quiz_message_vars_description_html, $quiz_allowed_html);
                            $content = $quiz_description;
                            $editor_id = 'ays-quiz-description';
                            $settings = array('editor_height' => $quiz_wp_editor_height, 'textarea_name' => 'ays_quiz_description', 'editor_class' => 'ays-textarea', 'media_elements' => false);
                            wp_editor($content, $editor_id, $settings);
                        ?>
                    </div>
                </div><!-- Quiz Description -->
                <hr/>
                <div class='form-group row ays-field ays_items_count_div'>
                    <div class="col-sm-3" style="display: flex; align-items: center;">
                        <div style='display: flex;align-items: center;margin-right: 15px;'>
                            <!-- <a href="javascript:void(0)" class="ays-add-question ays-add-question-top-button">
                                <i class="ays_fa ays_fa_plus_square" aria-hidden="true"></i>
                                <?php echo esc_html__('Insert questions', 'quiz-maker'); ?>
                            </a> -->
                            <span style="margin-right: 5px;"><?php echo esc_html__('Questions', 'quiz-maker'); ?></span>
                            <a class="ays_help" style="font-size:15px;" data-placement="bottom" data-toggle="tooltip" data-html="true" title="<?php echo esc_attr("<p style='margin:0;text-indent:7px;'>").esc_attr(__('For inserting questions to the quiz you need to make questions first from the “Questions page” in the left navbar. After popup’s opening, you can filter and select your prepared questions for this quiz.', 'quiz-maker')).esc_attr("</p><p style='margin:0;text-indent:7px;'>").esc_attr(__('The ordering of the questions will be the same as you chose. Also, you can reorder them after selection. There are no limitations for questions quantity.', 'quiz-maker')).esc_attr("</p>"); ?>">
                                <i class="ays_fa ays_fa_info_circle"></i>
                            </a>
                        </div>
                    </div>
                    <div class="col-sm-9">
                        <div class="form-group row" style="margin-bottom: 0;">
                            <div class="col-sm-12" style="display: flex; flex-wrap: wrap; justify-content: flex-end; align-items: center;">
                                <p class="ays_questions_action">
                                    <span class="ays_questions_count">
                                        <?php
                                        $ays_questions_count = '<span class="questions_count_number">' . count($question_id_array) . '</span> '. __('items','quiz-maker');

                                        echo wp_kses($ays_questions_count, $quiz_allowed_html);
                                        ?>
                                    </span>
                                </p>
                                <div class="ays-question-ordering" tabindex="0" data-ordered="false">
                                    <i class="ays_fa fas ays_fa_exchange"></i>
                                    <a class="ays_help" data-toggle="tooltip" title="<?php echo esc_attr( __('Reverse the ordering of the questions in the list.','quiz-maker') ); ?>">
                                        <i class="ays_fa ays_fa_info_circle"></i>
                                    </a>
                                </div>
                                <div style="display: flex;">
                                    <button class="ays_bulk_del_questions button" style="margin: 0 10px;" type="button" disabled>
                                        <?php echo esc_html__( 'Delete', 'quiz-maker'); ?>                            
                                    </button>
                                    <button class="ays_select_all button" type="button">
                                        <?php echo esc_html__( 'Select All', 'quiz-maker'); ?>                            
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="ays-field ays-table-wrap" style="padding-top: 15px;">
                    <table class="ays-questions-table" id="ays-questions-table">
                        <thead>
                            <tr class="ui-state-default">
                                <th class="ays-quiz-question-ordering-row th-150"><?php echo esc_html__('Ordering', 'quiz-maker'); ?></th>
                                <th class="ays-quiz-question-question-row" style="width:500px;"><?php echo esc_html__('Question', 'quiz-maker'); ?></th>
                                <th class="ays-quiz-question-type-row th-150"><?php echo esc_html__('Type', 'quiz-maker'); ?></th>
                                <th class="ays-quiz-question-category-row th-150"><?php echo esc_html__('Category', 'quiz-maker'); ?></th>
                                <th class="ays-quiz-question-id-row th-150"><?php echo esc_html__('ID', 'quiz-maker'); ?></th>
                                <th class="ays-quiz-question-action-row th-150" style="min-width:120px;"><?php echo esc_html__('Actions', 'quiz-maker'); ?></th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php
                        if( isset($question_id_array) && !(count($question_id_array) === 1 && $question_id_array[0] == '')) {
                            foreach ($question_id_array as $key => $question_id) {
                                $data = $this->quizes_obj->get_published_questions_by('id', absint(intval($question_id)));
                                $className = "";
                                if (($key + 1) % 2 == 0) {
                                    $className = "even";
                                }

                                $data_id = isset( $data['id'] ) && $data['id'] != "" ? esc_attr($data['id']) : null;

                                $edit_question_url = "?page=".$this->plugin_name."-questions&action=edit&question=".$data_id;

                                $table_question = "";
                                if(isset($data['question_title']) && $data['question_title'] != '' && $quiz_question_title_view == 'question_title'){
                                    $table_question = htmlspecialchars_decode( $data['question_title'], ENT_COMPAT);
                                    $table_question = stripslashes( $table_question );
                                } elseif(isset($data['question']) && strlen($data['question']) != 0){

                                    $is_exists_ruby = Quiz_Maker_Admin::ays_quiz_is_exists_needle_tag( $data['question'] , '<ruby>' );

                                    if ( $is_exists_ruby ) {
                                        $table_question = strip_tags( stripslashes($data['question']), '<ruby><rbc><rtc><rb><rt>' );
                                    } else {
                                        $table_question = strip_tags(stripslashes($data['question']));
                                    }

                                }elseif ((isset($data['question_image']) && $data['question_image'] !='')){
                                    $table_question = __( 'Image question', 'quiz-maker' );
                                }
                                $table_question = $this->ays_restriction_string("word",$table_question, 10);

                                $ays_question_type = "";
                                $data_question_type = (isset( $data['type'] ) && $data['type'] != "") ? esc_attr($data['type']) : "";

                                switch ( $data_question_type ) {
                                    case 'short_text':
                                        $ays_question_type = 'short text';
                                        break;
                                    case 'true_or_false':
                                        $ays_question_type = 'true/false';
                                        break;
                                    default:
                                        $ays_question_type = $data_question_type;
                                        break;
                                }

                                $question_cat_title = "";
                                if( isset($data['category_id']) && $data['category_id'] != "" ){
                                    $question_cat_title = isset( $question_categories_array[$data['category_id']] ) && $question_categories_array[$data['category_id']] != "" ? esc_attr( $question_categories_array[$data['category_id']] ) : "";
                                }

                                ?>
                                <tr class="ays-question-row ui-state-default <?php echo esc_attr($className); ?>"
                                    data-id="<?php echo esc_attr($data_id); ?>">
                                    <td class="ays-quiz-question-ordering-row ays-sort"><i class="ays_fa ays_fa_arrows" aria-hidden="true"></i></td>
                                    <td class="ays-quiz-question-question-row">
                                        <a href="<?php echo esc_url($edit_question_url); ?>" target="_blank" class="ays-edit-question" title="<?php echo esc_attr( __('Edit question', 'quiz-maker') ); ?>">
                                            <?php echo esc_html($table_question); ?>
                                        </a>
                                    </td>
                                    <td class="ays-quiz-question-type-row"><?php echo esc_html($ays_question_type); ?></td>
                                    <td class="ays-quiz-question-category-row"><?php echo esc_html($question_cat_title); ?></td>
                                    <td class="ays-quiz-question-id-row"><?php echo esc_html($data_id); ?></td>
                                    <td class="ays-quiz-question-action-row">
                                        <input type="checkbox" class="ays_del_tr">
                                        <a href="<?php echo esc_url($edit_question_url); ?>" target="_blank" class="ays-edit-question" title="<?php echo esc_attr(__('Edit question', 'quiz-maker')); ?>">
                                            <i class="ays_fa ays_fa_pencil_square" aria-hidden="true"></i>
                                        </a>
                                        <a href="javascript:void(0)" class="ays-delete-question" title="<?php echo esc_attr(__('Delete', 'quiz-maker')); ?>"
                                           data-id="<?php echo esc_attr($data_id); ?>">
                                            <i class="ays_fa ays_fa_minus_square" aria-hidden="true"></i>
                                        </a>
                                    </td>
                                </tr>
                                <?php
                            }
                        }
                        //if(empty($question_id_array)){                            
                            ?>
                            <tr class="ays-question-row ays-question-empty-row ui-state-default">
                                <td colspan="6" class="empty_quiz_td">
                                    <div class="ays-quiz-create-question-link-box" style="<?php echo !empty($question_id_array) ? 'display: none;' : ''; ?>">
                                        <i class="ays_fa ays_fa_info" aria-hidden="true" style="margin-right:10px"></i>
                                        <span style="font-size: 13px; font-style: italic;">
                                        <?php
                                            echo esc_html__( 'There are no questions yet.', 'quiz-maker' );
                                        ?>
                                        </span>
                                    </div>
                                    <div class='ays_add_question_from_table'>                                        
                                        <a href="javascript:void(0)" class="ays-add-question-primary">
                                            <i class="ays_fa ays_fa_plus_square" aria-hidden="true"></i>
                                            <?php echo esc_html__('Insert questions', 'quiz-maker'); ?>
                                        </a>
                                        <a href="<?php echo $question_add_new_page_url; ?>" class="ays-add-question" target="_blank">
                                            <?php echo esc_html__('Create question', 'quiz-maker'); ?>
                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-external-link-icon lucide-external-link"><path d="M15 3h6v6"/><path d="M10 14 21 3"/><path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"/></svg>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                            <?php
                        //}
                        ?>
                        </tbody>
                    </table>
                    <p class="ays_questions_action" style="width:100%;">                
                        <span class="ays_questions_count">
                            <?php
                            $ays_questions_count = '<span class="questions_count_number">' . ((isset($question_id_array) && !empty($question_id_array)) ? count($question_id_array) : 0) . '</span> '. esc_html__('items','quiz-maker');

                            echo wp_kses($ays_questions_count, $quiz_allowed_html);
                            ?>
                        </span>
                        <button class="ays_bulk_del_questions button" type="button" disabled>
                            <?php echo esc_html__( 'Delete', 'quiz-maker'); ?>                            
                        </button>
                        <button class="ays_select_all button" type="button">
                            <?php echo esc_html__( 'Select All', 'quiz-maker'); ?>                            
                        </button>
                    </p>
                </div>
                <input type="hidden" id="ays_already_added_questions" name="ays_added_questions" value="<?php echo esc_attr($question_ids); ?>"/>
            </div>

            <div id="tab2" class="ays-quiz-tab-content <?php echo ($ays_quiz_tab == 'tab2') ? 'ays-quiz-tab-content-active' : ''; ?>">
                <div class="ays-quiz-top-actions-container-wrapper form-group row">
                    <div class="col-sm-12">
                        <p class="m-0 text-right">
                            <a class="ays-quiz-collapse-all" href="javascript:void(0);"><?php echo esc_html__( "Collapse All", "quiz-maker" ); ?></a>
                            <span>|</span>
                            <a class="ays-quiz-expand-all" href="javascript:void(0);"><?php echo esc_html__( "Expand All", "quiz-maker" ); ?></a>
                        </p>
                    </div>
                </div>
                <div class="ays-quiz-accordion-options-main-container" data-collapsed="false">
                    <div class="ays-quiz-accordion-container">
                        <?php echo wp_kses($quiz_accordion_svg_html, $quiz_allowed_html); ?>
                        <p class="ays-subtitle"><?php echo esc_html__('Quiz Styles','quiz-maker')?></p>
                    </div>
                    <hr class="ays-quiz-bolder-hr"/>
                    <div class="ays-quiz-accordion-options-box">
                        <div class="form-group row">
                            <div class="col-sm-2">
                                <label>
                                    <?php echo esc_html__('Theme', 'quiz-maker'); ?>
                                    <a class="ays_help" data-toggle="tooltip" title="<?php echo esc_attr( __('You can choose your preferred template and customize it with options below Elegant Dark, Elegant Light, Classic Dark, Classic Light, Rect Dark, Rect Light.','quiz-maker') ); ?>">
                                        <i class="ays_fa ays_fa_info_circle"></i>
                                    </a>
                                </label>
                            </div>
                            <div class="col-sm-10">
                                <div class="form-group row ays_themes_images_main_div">
                                    <div class="ays_theme_image_div col-sm-2 <?php echo ($quiz_theme == 'elegant_dark') ? 'ays_active_theme_image' : '' ?>" style="padding:0;">
                                        <label for="theme_elegant_dark" class="ays-quiz-theme-item">
                                            <p><?php echo esc_html__('Elegant Dark','quiz-maker')?></p>
                                            <img loading="lazy" src="<?php echo esc_url( AYS_QUIZ_ADMIN_URL . '/images/themes/elegant_dark.webp' ); ?>" alt="Elegant Dark">
                                        </label>
                                    </div>
                                    <div class="ays_theme_image_div col-sm-2 <?php echo ($quiz_theme == 'elegant_light') ? 'ays_active_theme_image' : '' ?>" style="padding:0;">
                                        <label for="theme_elegant_light" class="ays-quiz-theme-item">
                                            <p><?php echo esc_html__('Elegant Light','quiz-maker')?></p>
                                            <img loading="lazy" src="<?php echo esc_url( AYS_QUIZ_ADMIN_URL . '/images/themes/elegant_light.webp' ); ?>" alt="Elegant Light">
                                        </label>
                                    </div>
                                    <div class="ays_theme_image_div col-sm-2 <?php echo ($quiz_theme == 'classic_dark') ? 'ays_active_theme_image' : '' ?>" style="padding:0;">
                                        <label for="theme_classic_dark" class="ays-quiz-theme-item">
                                            <p><?php echo esc_html__('Classic Dark','quiz-maker')?></p>
                                            <img loading="lazy" src="<?php echo esc_url( AYS_QUIZ_ADMIN_URL . '/images/themes/classic_dark.webp' ); ?>" alt="Classic Dark">
                                        </label>
                                    </div>
                                    <div class="ays_theme_image_div col-sm-2 <?php echo ($quiz_theme == 'classic_light') ? 'ays_active_theme_image' : '' ?>" style="padding:0;">
                                        <label for="theme_classic_light" class="ays-quiz-theme-item">
                                            <p><?php echo esc_html__('Classic Light','quiz-maker')?></p>
                                            <img loading="lazy" src="<?php echo esc_url( AYS_QUIZ_ADMIN_URL . '/images/themes/classic_light.webp' ); ?>" alt="Classic Light">
                                        </label>
                                    </div>
                                    <div class="ays_theme_image_div col-sm-2 <?php echo ($quiz_theme == 'rect_dark') ? 'ays_active_theme_image' : '' ?>" style="padding:0;">
                                        <label for="theme_rect_dark" class="ays-quiz-theme-item">
                                            <p><?php echo esc_html__('Rect Dark','quiz-maker')?></p>
                                            <img loading="lazy" src="<?php echo esc_url( AYS_QUIZ_ADMIN_URL . '/images/themes/rect_dark.webp' ); ?>" alt="Rect Dark" >
                                        </label>
                                    </div>
                                    <div class="ays_theme_image_div col-sm-2 <?php echo ($quiz_theme == 'rect_light') ? 'ays_active_theme_image' : '' ?>" style="padding:0;">
                                        <label for="theme_rect_light" class="ays-quiz-theme-item">
                                            <p><?php echo esc_html__('Rect Light','quiz-maker')?></p>
                                            <img loading="lazy" src="<?php echo esc_url( AYS_QUIZ_ADMIN_URL . '/images/themes/rect_light.webp' ); ?>" alt="Rect Light" >
                                        </label>
                                    </div>
                                </div>
                                <hr>
                                <div class="form-group row">
                                    <div class="col-sm-12 only_pro">
                                        <div class="pro_features">

                                        </div>
                                        <div class="form-group row">
                                            <div class="col-sm-2 ays_theme_image_div" style="padding:0;">
                                                <label class="ays-quiz-theme-item ays-disable-setting">
                                                    <p><?php echo esc_html__('Modern Light','quiz-maker')?></p>
                                                    <img loading="lazy" src="<?php echo esc_url(AYS_QUIZ_ADMIN_URL . '/images/themes/modern_light.webp' ); ?>" alt="Modern Light"/>
                                                </label>
                                            </div>
                                            <div class="col-sm-2 ays_theme_image_div" style="padding:0;">
                                                <label class="ays-quiz-theme-item ays-disable-setting">
                                                    <p><?php echo esc_html__('Modern Dark','quiz-maker')?></p>
                                                    <img loading="lazy" src="<?php echo esc_url(AYS_QUIZ_ADMIN_URL . '/images/themes/modern_dark.webp'); ?>" alt="Modern Dark"/>
                                                </label>
                                            </div>
                                        </div>
                                        <a href="https://ays-pro.com/wordpress/quiz-maker?utm_source=dashboard&utm_medium=quiz-free&utm_campaign=style-tab-theme-<?php echo esc_attr( AYS_QUIZ_UTM_VERSION ); ?>" target="_blank" class="ays-quiz-new-upgrade-button-link">
                                            <div class="ays-quiz-new-upgrade-button-box">
                                                <div>
                                                    <img loading="lazy" src="<?php echo esc_url( AYS_QUIZ_ADMIN_URL.'/images/icons/locked_24x24.svg' );?>">
                                                    <img loading="lazy" src="<?php echo esc_url( AYS_QUIZ_ADMIN_URL.'/images/icons/unlocked_24x24.svg' ); ?>" class="ays-quiz-new-upgrade-button-hover">
                                                </div>
                                                <div class="ays-quiz-new-upgrade-button"><?php echo esc_html__("Upgrade", "quiz-maker"); ?></div>
                                            </div>
                                        </a>
                                    </div>
                                </div>
                                <input type="radio" id="theme_elegant_dark" name="ays_quiz_theme" value="elegant_dark" <?php echo ($quiz_theme == 'elegant_dark') ? 'checked' : '' ?>>
                                <input type="radio" id="theme_elegant_light" name="ays_quiz_theme" value="elegant_light" <?php echo ($quiz_theme == 'elegant_light') ? 'checked' : '' ?>>
                                <input type="radio" id="theme_classic_dark" name="ays_quiz_theme" value="classic_dark" <?php echo ($quiz_theme == 'classic_dark') ? 'checked' : '' ?>>
                                <input type="radio" id="theme_classic_light" name="ays_quiz_theme" value="classic_light" <?php echo ($quiz_theme == 'classic_light') ? 'checked' : '' ?>>
                                <input type="radio" id="theme_rect_dark" name="ays_quiz_theme" value="rect_dark" <?php echo ($quiz_theme == 'rect_dark') ? 'checked' : '' ?>>
                                <input type="radio" id="theme_rect_light" name="ays_quiz_theme" value="rect_light" <?php echo ($quiz_theme == 'rect_light') ? 'checked' : '' ?>>
                            </div>
                        </div><!-- Theme -->
                        <hr/>
                    </div>
                    <div class="ays-quiz-accordion-options-box cow">
                        <div class="row">
                            <div class="col-lg-7 col-sm-12">
                                <div class="form-group row">
                                    <div class="col-sm-5">
                                        <label for='ays-quiz-width'>
                                            <?php echo esc_html__('Quiz width', 'quiz-maker'); ?>
                                            <a class="ays_help" data-toggle="tooltip" title="<?php echo esc_attr( __('Quiz container width in pixels. Set it 0 or leave it blank for making a quiz with 100%  width. It accepts only numeric values.','quiz-maker') ); ?>">
                                                <i class="ays_fa ays_fa_info_circle"></i>
                                            </a>
                                        </label>
                                    </div>
                                    <div class="col-sm-6 ays_divider_left">
                                        <div class="ays_quiz_display_flex_width">
                                            <div>
                                                <input type="number" class="ays-text-input ays-text-input-short" id='ays-quiz-width' name='ays_quiz_width' value="<?php echo (isset($options['width'])) ? intval($options['width']) : ''; ?>"/>
                                                <span style="display:block;" class="ays_quiz_small_hint_text"><?php echo esc_html__("For 100% leave blank", 'quiz-maker');?></span>
                                            </div>
                                            <div class="ays_quiz_dropdown_max_width">
                                                <select id="ays_quiz_width_by_percentage_px" name="ays_quiz_width_by_percentage_px" class="ays-text-input ays-text-input-short" style="display:inline-block; width: 60px;">
                                                    <option value="pixels" <?php echo $quiz_width_by_percentage_px == "pixels" ? "selected" : ""; ?>><?php echo esc_html__( "px", 'quiz-maker' ); ?></option>
                                                    <option value="percentage" <?php echo $quiz_width_by_percentage_px == "percentage" ? "selected" : ""; ?>><?php echo esc_html__( "%", 'quiz-maker' ); ?></option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div><!-- Quiz width -->
                                <hr/>
                                <div class="form-group row">
                                    <div class="col-sm-5">
                                        <label for='ays_mobile_max_width'>
                                            <?php echo esc_html__('Quiz max-width for mobile', 'quiz-maker'); ?>
                                            <a class="ays_help" data-toggle="tooltip" title="<?php echo esc_attr( __('Quiz container max-width for mobile in percentage. This option will work for the screens with less than 640 pixels width.','quiz-maker') ); ?>">
                                                <i class="ays_fa ays_fa_info_circle"></i>
                                            </a>
                                        </label>
                                    </div>
                                    <div class="col-sm-7 ays_divider_left ays_quiz_display_flex_width">
                                        <div>
                                            <input type="number" class="ays-text-input ays-text-input-short" id='ays_mobile_max_width'
                                                   name='ays_mobile_max_width' style="display:inline-block;"
                                                   value="<?php echo esc_attr($mobile_max_width); ?>"/>
                                            <span style="display:block;" class="ays_quiz_small_hint_text"><?php echo esc_html__("For 100% leave blank", 'quiz-maker');?></span>
                                        </div>
                                        <div class="ays_quiz_dropdown_max_width">
                                            <input type="text" value="%" class='ays-quiz-form-hint-for-size' disabled>
                                        </div>
                                    </div>
                                </div><!--Quiz max-width for mobile -->
                                <hr/>
                                <div class="form-group row">
                                    <div class="col-sm-5">
                                        <label for='ays_quiz_content_max_width'>
                                            <?php echo esc_html__('Quiz content max-width', 'quiz-maker'); ?>
                                            <a class="ays_help" data-toggle="tooltip" title="<?php echo esc_attr( __('Set your desired value for the Quiz content max-width in percentage. By default, it is set as 90%.','quiz-maker') ); ?>">
                                                <i class="ays_fa ays_fa_info_circle"></i>
                                            </a>
                                        </label>
                                    </div>
                                    <div class="col-sm-7 ays_divider_left">
                                        <div class="row">
                                            <div class="col-sm-5">
                                                <label for='ays_answers_font_size'>
                                                    <?php echo esc_html__('On desktop', 'quiz-maker'); ?>
                                                    <a class="ays_help" data-toggle="tooltip" title="<?php echo esc_attr( __('Define the Quiz content max-width for desktop devices.','quiz-maker') ); ?>">
                                                        <i class="ays_fa ays_fa_info_circle"></i>
                                                    </a>
                                                </label>
                                            </div>
                                            <div class="col-sm-7 ays_quiz_display_flex_width">
                                                <div>
                                                    <input type="number" class="ays-text-input" id='ays_quiz_content_max_width' name='ays_quiz_content_max_width' style="display:inline-block;" value="<?php echo esc_attr($quiz_content_max_width); ?>"/>
                                                </div>
                                                <div class="ays_quiz_dropdown_max_width">
                                                    <input type="text" value="%" class='ays-quiz-form-hint-for-size' disabled>
                                                </div>
                                            </div>
                                        </div>
                                        <hr>
                                        <div class="row">
                                            <div class="col-sm-5">
                                                <label for='ays_quiz_content_mobile_max_width'>
                                                    <?php echo esc_html__('On mobile', 'quiz-maker'); ?>
                                                    <a class="ays_help" data-toggle="tooltip" title="<?php echo esc_attr( __('Define the Quiz content max-width for mobile devices.','quiz-maker') ); ?>">
                                                        <i class="ays_fa ays_fa_info_circle"></i>
                                                    </a>
                                                </label>
                                            </div>
                                            <div class="col-sm-7 ays_quiz_display_flex_width">
                                                <div>
                                                    <input type="number" class="ays-text-input" id='ays_quiz_content_mobile_max_width' name='ays_quiz_content_mobile_max_width' style="display:inline-block;" value="<?php echo esc_attr($quiz_content_mobile_max_width); ?>"/>
                                                </div>
                                                <div class="ays_quiz_dropdown_max_width">
                                                    <input type="text" value="%" class='ays-quiz-form-hint-for-size' disabled>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div><!--Quiz content max-width -->
                                <hr/>
                                <div class="form-group row">
                                    <div class="col-sm-5">
                                        <label for='ays-quiz-height'>
                                            <?php echo esc_html__('Quiz min-height', 'quiz-maker'); ?>
                                            <a class="ays_help" data-toggle="tooltip" title="<?php echo esc_attr( __('Quiz minimal height in pixels','quiz-maker') ); ?>">
                                                <i class="ays_fa ays_fa_info_circle"></i>
                                            </a>
                                        </label>
                                    </div>
                                    <div class="col-sm-7 ays_divider_left ays_quiz_display_flex_width">
                                        <div>
                                            <input type="number" class="ays-text-input ays-text-input-short" id='ays-quiz-height' name='ays_quiz_height' value="<?php echo (isset($options['height'])) ? intval($options['height']) : ''; ?>"/>
                                        </div>
                                        <div class="ays_quiz_dropdown_max_width">
                                            <input type="text" value="px" class='ays-quiz-form-hint-for-size' disabled>
                                        </div>
                                    </div>
                                </div><!-- Quiz min-height -->
                                <hr/>
                                <div class="form-group row">
                                    <div class="col-sm-5">
                                        <label for='ays-quiz-color'>
                                            <?php echo esc_html__('Quiz Color', 'quiz-maker'); ?>
                                            <a class="ays_help" data-toggle="tooltip" title="<?php echo esc_html__('Colors of the quiz main attributes (buttons, hover effect, progress bar, etc.).','quiz-maker')?>">
                                                <i class="ays_fa ays_fa_info_circle"></i>
                                            </a>
                                        </label>
                                    </div>
                                    <div class="col-sm-7 ays_divider_left">
                                        <input type="text" class="ays-text-input" id='ays-quiz-color' data-alpha="true" name='ays_quiz_color'
                                               value="<?php echo (isset($options['color'])) ? esc_attr( stripslashes( $options['color'] ) ) : ''; ?>"/>
                                    </div>
                                </div><!-- Quiz Color -->
                                <hr/>
                                <div class="form-group row">
                                    <div class="col-sm-5">
                                        <label for='ays-quiz-bg-color'>
                                            <?php echo esc_html__('Background color', 'quiz-maker'); ?>
                                            <a class="ays_help" data-toggle="tooltip" title="<?php echo esc_attr( __('Background color of the quiz box. You can also choose the opacity(alfa) level on the right side.','quiz-maker') ); ?>">
                                                <i class="ays_fa ays_fa_info_circle"></i>
                                            </a>
                                        </label>
                                    </div>
                                    <div class="col-sm-7 ays_divider_left">
                                        <input type="text" class="ays-text-input" id='ays-quiz-bg-color' data-alpha="true"
                                               name='ays_quiz_bg_color'
                                               value="<?php echo (isset($options['bg_color'])) ? esc_attr( stripslashes($options['bg_color']) ) : ''; ?>"/>
                                    </div>
                                </div><!-- Background color -->
                                <hr/>
                                <div class="form-group row">
                                    <div class="col-sm-5">
                                        <label for='ays-quiz-text-color'>
                                            <?php echo esc_html__('Text Color', 'quiz-maker'); ?>
                                            <a class="ays_help" data-toggle="tooltip" title="<?php echo esc_attr__('Specify the text color inside the quiz and questions. It affects all kinds of texts and icons.','quiz-maker')?>">
                                                <i class="ays_fa ays_fa_info_circle"></i>
                                            </a>
                                        </label>
                                    </div>
                                    <div class="col-sm-7 ays_divider_left">
                                        <input type="text" class="ays-text-input" id='ays-quiz-text-color' data-alpha="true"
                                               name='ays_quiz_text_color'
                                               value="<?php echo (isset($options['text_color'])) ? esc_attr( stripslashes($options['text_color']) ) : ''; ?>"/>
                                    </div>
                                </div><!-- Text Color -->
                                <hr/>
                                <div class="form-group row">
                                    <div class="col-sm-5">
                                        <label for='ays-quiz-buttons-text-color'>
                                            <?php echo esc_html__('Buttons text color', 'quiz-maker'); ?>
                                            <a class="ays_help" data-toggle="tooltip" title="<?php echo esc_attr__('Specify the text color of buttons inside the quiz and questions. It affects only to buttons.','quiz-maker')?>">
                                                <i class="ays_fa ays_fa_info_circle"></i>
                                            </a>
                                        </label>
                                    </div>
                                    <div class="col-sm-7 ays_divider_left">
                                        <input type="text" class="ays-text-input" id='ays-quiz-buttons-text-color' data-alpha="true"
                                               name='ays_buttons_text_color'
                                               value="<?php echo esc_attr($buttons_text_color); ?>"/>
                                    </div>
                                </div><!-- Buttons text color -->
                                <hr/> 
                                <div class="form-group row">
                                    <div class="col-sm-5">
                                        <label for="ays_quiz_border_radius">
                                            <?php echo esc_html__('Border radius','quiz-maker'); ?>
                                            <a class="ays_help" data-toggle="tooltip" title="<?php echo esc_attr__('Quiz container border-radius in pixels. It accepts only numeric values.','quiz-maker')?>">
                                                <i class="ays_fa ays_fa_info_circle"></i>
                                            </a>
                                        </label>
                                    </div>
                                    <div class="col-sm-7 ays_divider_left ays_quiz_display_flex_width">
                                        <div>
                                            <input type="number" class="ays-text-input ays-text-input-short"
                                               id="ays_quiz_border_radius"
                                               name="ays_quiz_border_radius"
                                               value="<?php echo intval($quiz_border_radius); ?>"/>
                                        </div>
                                        <div class="ays_quiz_dropdown_max_width ays-display-flex" style="align-items: end;">
                                            <input type="text" value="px" class='ays-quiz-form-hint-for-size' disabled>
                                        </div>
                                    </div>
                                </div><!-- Border radius -->
                                <hr/>
                                <div class="form-group row">
                                    <div class="col-sm-5">
                                        <label for="ays_enable_box_shadow">
                                            <?php echo esc_html__('Box shadow','quiz-maker')?>
                                            <a class="ays_help" data-toggle="tooltip" title="<?php echo esc_attr__('Allow quiz container box shadow','quiz-maker')?>">
                                                <i class="ays_fa ays_fa_info_circle"></i>
                                            </a>
                                        </label>
                                    </div>
                                    <div class="col-sm-7 ays_divider_left">
                                        <input type="checkbox" class="ays_toggle ays_toggle_slide"
                                               id="ays_enable_box_shadow"
                                               name="ays_enable_box_shadow"
                                               <?php echo ($enable_box_shadow == 'on') ? 'checked' : ''; ?>/>
                                        <label for="ays_enable_box_shadow" class="ays_switch_toggle">Toggle</label>
                                        <div class="col-sm-12 ays_toggle_target ays_divider_top" style="margin-top: 10px; padding-top: 10px; <?php echo ($enable_box_shadow == 'on') ? '' : 'display:none;' ?>">
                                            <div class="form-group row">
                                                <div class="col-sm-12">
                                                    <label for="ays-quiz-box-shadow-color">
                                                        <?php echo esc_html__('Box shadow color','quiz-maker')?>
                                                        <a class="ays_help" data-toggle="tooltip" title="<?php echo esc_attr__('The color of the shadow of the quiz container','quiz-maker' ); ?>">
                                                            <i class="ays_fa ays_fa_info_circle"></i>
                                                        </a>
                                                     </label>
                                                    <input type="text" class="ays-text-input" id='ays-quiz-box-shadow-color' name='ays_quiz_box_shadow_color' data-alpha="true" data-default-color="#c9c9c9" value="<?php echo esc_attr($box_shadow_color); ?>"/>
                                               </div>
                                            </div>
                                            <hr>
                                            <div class="form-group row">
                                                <div class="col-sm-12">
                                                    <div class="col-sm-3" style="display: inline-block;">
                                                        <span class="ays_quiz_small_hint_text"><?php echo esc_html__('X', 'quiz-maker'); ?></span>
                                                        <input type="number" class="ays-text-input ays-text-input-90-width" id='ays_quiz_box_shadow_x_offset' name='ays_quiz_box_shadow_x_offset' value="<?php echo esc_attr($quiz_box_shadow_x_offset); ?>" />
                                                    </div>
                                                    <div class="col-sm-3 ays_divider_left" style="display: inline-block;">
                                                        <span class="ays_quiz_small_hint_text"><?php echo esc_html__('Y', 'quiz-maker'); ?></span>
                                                        <input type="number" class="ays-text-input ays-text-input-90-width" id='ays_quiz_box_shadow_y_offset' name='ays_quiz_box_shadow_y_offset' value="<?php echo esc_attr($quiz_box_shadow_y_offset); ?>" />
                                                    </div>
                                                    <div class="col-sm-3 ays_divider_left" style="display: inline-block;">
                                                        <span class="ays_quiz_small_hint_text"><?php echo esc_html__('Z', 'quiz-maker'); ?></span>
                                                        <input type="number" class="ays-text-input ays-text-input-90-width" id='ays_quiz_box_shadow_z_offset' name='ays_quiz_box_shadow_z_offset' value="<?php echo esc_attr($quiz_box_shadow_z_offset); ?>" />
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div><!-- Box shadow -->
                                <hr/>
                                <div class="form-group row ays-quiz-bg-image-main-container">
                                    <div class="col-sm-5">
                                        <label>
                                            <?php echo esc_html__('Background image','quiz-maker')?>
                                            <a class="ays_help" data-toggle="tooltip" title="<?php echo esc_attr__('Background image of the container. You can choose different images for each question from the Settings tab on the Edit question page. The background-size is set “Cover” by default for not scaling the image.','quiz-maker')?>">
                                                <i class="ays_fa ays_fa_info_circle"></i>
                                            </a>
                                        </label>
                                    </div>
                                    <div class="col-sm-7 ays_divider_left">                                
                                        <a href="javascript:void(0)" style="<?php echo $quiz_bg_image == '' ? 'display:inline-block' : 'display:none'; ?>" class="add-quiz-bg-image"><?php echo esc_html($bg_image_text); ?></a>
                                        <p class="ays_quiz_small_hint_text_for_message_variables" style="margin-top: 5px;">
                                            <span><?php echo esc_html__( "Please Note" , 'quiz-maker' ); ?></span>
                                            <a class="ays_help" data-toggle="tooltip" title="<?php echo esc_attr( __('* Note: The plugin doesn’t make any changes concerning the images. It takes the images in a size, in which you have uploaded them. We are using the default WP Media. If the uploaded image is blurred and has a low quality, make sure to choose the right parameters (Full size) while uploading the images. You can find the Full Size option in the opened Add Media popup (Attachment Display Settings).','quiz-maker') ); ?>">
                                                <i class="ays_fa ays_fa_info_circle"></i>
                                            </a>
                                        </p>
                                        <input type="hidden" id="ays_quiz_bg_image" name="ays_quiz_bg_image"
                                               value="<?php echo esc_url($quiz_bg_image); ?>"/>
                                        <div class="ays-quiz-bg-image-container" style="<?php echo $quiz_bg_image == '' ? 'display:none' : 'display:block'; ?>">
                                            <span class="ays-edit-quiz-bg-img">
                                                <i class="ays_fa ays_fa_pencil_square_o"></i>
                                            </span>
                                            <span class="ays-remove-quiz-bg-img"></span>
                                            <img loading="lazy" src="<?php echo esc_url($quiz_bg_image); ?>" id="ays-quiz-bg-img"/>
                                        </div>
                                        <hr/>
                                        <div class="form-group row">
                                            <div class="col-sm-12">
                                                <label for="ays_quiz_bg_image_position">
                                                    <?php echo esc_html__( "Background image position", 'quiz-maker' ); ?>
                                                    <a class="ays_help" data-toggle="tooltip" title="<?php echo esc_attr__('The position of background image of the quiz','quiz-maker')?>">
                                                        <i class="ays_fa ays_fa_info_circle"></i>
                                                    </a>
                                                </label>
                                                <select id="ays_quiz_bg_image_position" name="ays_quiz_bg_image_position" class="ays-text-input ays-text-input-short" style="display:inline-block;">
                                                    <option value="left top" <?php echo $quiz_bg_image_position == "left top" ? "selected" : ""; ?>><?php echo esc_html__( "Left Top", 'quiz-maker' ); ?></option>
                                                    <option value="left center" <?php echo $quiz_bg_image_position == "left center" ? "selected" : ""; ?>><?php echo esc_html__( "Left Center", 'quiz-maker' ); ?></option>
                                                    <option value="left bottom" <?php echo $quiz_bg_image_position == "left bottom" ? "selected" : ""; ?>><?php echo esc_html__( "Left Bottom", 'quiz-maker' ); ?></option>
                                                    <option value="center top" <?php echo $quiz_bg_image_position == "center top" ? "selected" : ""; ?>><?php echo esc_html__( "Center Top", 'quiz-maker' ); ?></option>
                                                    <option value="center center" <?php echo $quiz_bg_image_position == "center center" ? "selected" : ""; ?>><?php echo esc_html__( "Center Center", 'quiz-maker' ); ?></option>
                                                    <option value="center bottom" <?php echo $quiz_bg_image_position == "center bottom" ? "selected" : ""; ?>><?php echo esc_html__( "Center Bottom", 'quiz-maker' ); ?></option>
                                                    <option value="right top" <?php echo $quiz_bg_image_position == "right top" ? "selected" : ""; ?>><?php echo esc_html__( "Right Top", 'quiz-maker' ); ?></option>
                                                    <option value="right center" <?php echo $quiz_bg_image_position == "right center" ? "selected" : ""; ?>><?php echo esc_html__( "Right Center", 'quiz-maker' ); ?></option>
                                                    <option value="right bottom" <?php echo $quiz_bg_image_position == "right bottom" ? "selected" : ""; ?>><?php echo esc_html__( "Right Bottom", 'quiz-maker' ); ?></option>
                                                </select>
                                            </div>
                                        </div>
                                        <hr/>
                                        <div class="form-group row">
                                            <div class="col-sm-8">
                                                <label for="ays_quiz_bg_img_in_finish_page">
                                                    <?php echo esc_html__( "Hide background image on result page", 'quiz-maker' ); ?>
                                                    <a class="ays_help" data-toggle="tooltip" title="<?php echo esc_attr__('If this option is enabled background image of quiz will disappear on the result page.','quiz-maker')?>">
                                                        <i class="ays_fa ays_fa_info_circle"></i>
                                                    </a>
                                                </label>
                                            </div>
                                            <div class="col-sm-4">
                                                <input type="checkbox" class="ays_toggle ays_toggle_slide"
                                                       id="ays_quiz_bg_img_in_finish_page"
                                                       name="ays_quiz_bg_img_in_finish_page"
                                                        <?php echo ($quiz_bg_img_in_finish_page) ? 'checked' : ''; ?>/>
                                                <label for="ays_quiz_bg_img_in_finish_page" style="display:inline-block;margin-left:10px;" class="ays_switch_toggle">Toggle</label>
                                            </div>
                                        </div>
                                        <hr/>
                                        <div class="form-group row">
                                            <div class="col-sm-8">
                                                <label for="ays_quiz_bg_img_on_start_page">
                                                    <?php echo esc_html__( "Hide background image on start page", 'quiz-maker' ); ?>
                                                    <a class="ays_help" data-toggle="tooltip" title="<?php echo esc_attr__('If this option is enabled background image of quiz will disappear on the start page.','quiz-maker'); ?>">
                                                        <i class="ays_fa ays_fa_info_circle"></i>
                                                    </a>
                                                </label>
                                            </div>
                                            <div class="col-sm-4">
                                                <input type="checkbox" class="ays_toggle ays_toggle_slide" id="ays_quiz_bg_img_on_start_page" name="ays_quiz_bg_img_on_start_page" <?php echo ($quiz_bg_img_on_start_page) ? 'checked' : ''; ?>/>
                                                <label for="ays_quiz_bg_img_on_start_page" style="display:inline-block;margin-left:10px;" class="ays_switch_toggle">Toggle</label>
                                            </div>
                                        </div>
                                        <hr/>
                                        <div class="form-group row">
                                            <div class="col-sm-8">
                                                <label for="ays_quiz_bg_img_during_the_quiz">
                                                    <?php echo esc_html__( "Hide background image during the quiz", 'quiz-maker' ); ?>
                                                    <a class="ays_help" data-toggle="tooltip" title="<?php echo esc_attr__('If this option is enabled the quiz background image will not be displayed during the quiz.','quiz-maker'); ?>">
                                                        <i class="ays_fa ays_fa_info_circle"></i>
                                                    </a>
                                                </label>
                                            </div>
                                            <div class="col-sm-4">
                                                <input type="checkbox" class="ays_toggle ays_toggle_slide" id="ays_quiz_bg_img_during_the_quiz" name="ays_quiz_bg_img_during_the_quiz" <?php echo ($quiz_bg_img_during_the_quiz) ? 'checked' : ''; ?>/>
                                                <label for="ays_quiz_bg_img_during_the_quiz" style="display:inline-block;margin-left:10px;" class="ays_switch_toggle">Toggle</label>
                                            </div>
                                        </div>
                                    </div>
                                </div><!-- Background image -->
                                <hr/>
                                <div class="form-group row">
                                    <div class="col-sm-5">
                                        <label for="ays-enable-background-gradient">
                                            <?php echo esc_html__('Background gradient','quiz-maker')?>
                                            <a class="ays_help" data-toggle="tooltip" title="<?php echo esc_attr__('Color gradient of the quiz background','quiz-maker')?>">
                                                <i class="ays_fa ays_fa_info_circle"></i>
                                            </a>
                                        </label>
                                    </div>
                                    <div class="col-sm-7 ays_divider_left">
                                        <input type="checkbox" class="ays_toggle ays_toggle_slide"
                                               id="ays-enable-background-gradient"
                                               name="ays_enable_background_gradient"
                                                <?php echo ($enable_background_gradient) ? 'checked' : ''; ?>/>
                                        <label for="ays-enable-background-gradient" class="ays_switch_toggle">Toggle</label>
                                        <div class="row ays_toggle_target" style="margin: 10px 0 0 0; padding-top: 10px; <?php echo ($enable_background_gradient) ? '' : 'display:none;' ?>">
                                            <div class="col-sm-12 ays_divider_top" style="margin-top: 10px; padding-top: 10px;">
                                                <label for='ays-background-gradient-color-1'>
                                                    <?php echo esc_html__('Color 1', 'quiz-maker'); ?>
                                                    <a class="ays_help" data-toggle="tooltip" title="<?php echo esc_attr__('Color 1 of the quiz background gradient','quiz-maker'); ?>">
                                                        <i class="ays_fa ays_fa_info_circle"></i>
                                                    </a>
                                                </label>
                                                <input type="text" class="ays-text-input" id='ays-background-gradient-color-1' data-alpha="true" name='ays_background_gradient_color_1' value="<?php echo esc_attr($background_gradient_color_1); ?>"/>
                                            </div>
                                            <div class="col-sm-12 ays_divider_top" style="margin-top: 10px; padding-top: 10px;">
                                                <label for='ays-background-gradient-color-2'>
                                                    <?php echo esc_html__('Color 2', 'quiz-maker'); ?>
                                                    <a class="ays_help" data-toggle="tooltip" title="<?php echo esc_attr__('Color 2 of the quiz background gradient','quiz-maker')?>">
                                                        <i class="ays_fa ays_fa_info_circle"></i>
                                                    </a>
                                                </label>
                                                <input type="text" class="ays-text-input" id='ays-background-gradient-color-2' data-alpha="true" name='ays_background_gradient_color_2' value="<?php echo esc_attr($background_gradient_color_2); ?>"/>
                                            </div>
                                            <div class="col-sm-12 ays_divider_top" style="margin-top: 10px; padding-top: 10px;">
                                                <label for="ays_quiz_gradient_direction">
                                                    <?php echo esc_html__('Gradient direction','quiz-maker')?>
                                                    <a class="ays_help" data-toggle="tooltip" title="<?php echo esc_attr__('The direction of the color gradient','quiz-maker')?>">
                                                        <i class="ays_fa ays_fa_info_circle"></i>
                                                    </a>
                                                </label>
                                                <select id="ays_quiz_gradient_direction" name="ays_quiz_gradient_direction" class="ays-text-input ays-text-input-short">
                                                    <option <?php echo ($quiz_gradient_direction == 'vertical') ? 'selected' : ''; ?> value="vertical"><?php echo esc_html__( 'Vertical', 'quiz-maker'); ?></option>
                                                    <option <?php echo ($quiz_gradient_direction == 'horizontal') ? 'selected' : ''; ?> value="horizontal"><?php echo esc_html__( 'Horizontal', 'quiz-maker'); ?></option>
                                                    <option <?php echo ($quiz_gradient_direction == 'diagonal_left_to_right') ? 'selected' : ''; ?> value="diagonal_left_to_right"><?php echo esc_html__( 'Diagonal left to right', 'quiz-maker'); ?></option>
                                                    <option <?php echo ($quiz_gradient_direction == 'diagonal_right_to_left') ? 'selected' : ''; ?> value="diagonal_right_to_left"><?php echo esc_html__( 'Diagonal right to left', 'quiz-maker'); ?></option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div><!-- Background gradient -->
                                <hr/>
                                <div class="form-group row">
                                    <div class="col-sm-5">
                                        <label for="ays_enable_border">
                                            <?php echo esc_html__('Quiz container border','quiz-maker')?>
                                            <a class="ays_help" data-toggle="tooltip" title="<?php echo esc_attr__('Allow quiz container border','quiz-maker')?>">
                                                <i class="ays_fa ays_fa_info_circle"></i>
                                            </a>
                                        </label>
                                    </div>
                                    <div class="col-sm-7 ays_divider_left">
                                        <input type="checkbox" class="ays_toggle ays_toggle_slide"
                                               id="ays_enable_border"
                                               name="ays_enable_border"
                                               value="on"
                                               <?php echo ($enable_border) ? 'checked' : ''; ?>/>
                                        <label for="ays_enable_border" class="ays_switch_toggle">Toggle</label>
                                        <div class="col-sm-12 ays_toggle_target ays_divider_top" style="margin-top: 10px; padding-top: 10px; <?php echo ($enable_border) ? '' : 'display:none;' ?>">
                                            <div class="ays_quiz_display_flex_width">
                                                <div>
                                                    <label for="ays_quiz_border_width">
                                                        <?php echo esc_html__('Border width','quiz-maker'); ?>
                                                        <a class="ays_help" data-toggle="tooltip" title="<?php echo esc_attr__('The width of quiz container border','quiz-maker')?>">
                                                            <i class="ays_fa ays_fa_info_circle"></i>
                                                        </a>
                                                     </label>
                                                    <input type="number" class="ays-text-input ays-text-input-short" id='ays_quiz_border_width'
                                                       name='ays_quiz_border_width'
                                                       value="<?php echo intval($quiz_border_width); ?>"/>
                                                </div>
                                                <div class="ays_quiz_dropdown_max_width ays-display-flex" style="align-items: end;">
                                                    <input type="text" value="px" class='ays-quiz-form-hint-for-size' disabled>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-sm-12 ays_toggle_target ays_divider_top" style="margin-top: 10px; padding-top: 10px; <?php echo ($enable_border) ? '' : 'display:none;' ?>">
                                            <label for="ays_quiz_border_style">
                                                <?php echo esc_html__('Border style','quiz-maker'); ?>
                                                <a class="ays_help" data-toggle="tooltip" title="<?php echo esc_attr__('The style of quiz container border','quiz-maker'); ?>">
                                                    <i class="ays_fa ays_fa_info_circle"></i>
                                                </a>
                                            </label>
                                            <select id="ays_quiz_border_style" 
                                                    name="ays_quiz_border_style" 
                                                    class="ays-text-input">
                                                <option <?php echo ($quiz_border_style == 'solid') ? 'selected' : ''; ?> value="solid">Solid</option>
                                                <option <?php echo ($quiz_border_style == 'dashed') ? 'selected' : ''; ?> value="dashed">Dashed</option>
                                                <option <?php echo ($quiz_border_style == 'dotted') ? 'selected' : ''; ?> value="dotted">Dotted</option>
                                                <option <?php echo ($quiz_border_style == 'double') ? 'selected' : ''; ?> value="double">Double</option>
                                                <option <?php echo ($quiz_border_style == 'groove') ? 'selected' : ''; ?> value="groove">Groove</option>
                                                <option <?php echo ($quiz_border_style == 'ridge') ? 'selected' : ''; ?> value="ridge">Ridge</option>
                                                <option <?php echo ($quiz_border_style == 'inset') ? 'selected' : ''; ?> value="inset">Inset</option>
                                                <option <?php echo ($quiz_border_style == 'outset') ? 'selected' : ''; ?> value="outset">Outset</option>
                                                <option <?php echo ($quiz_border_style == 'none') ? 'selected' : ''; ?> value="none">None</option>
                                            </select>
                                        </div>
                                        <div class="col-sm-12 ays_toggle_target ays_divider_top" style="margin-top: 10px; padding-top: 10px; <?php echo ($enable_border) ? '' : 'display:none;' ?>">
                                            <label for="ays_quiz_border_color">
                                                <?php echo esc_html__('Border color','quiz-maker'); ?>
                                                <a class="ays_help" data-toggle="tooltip" title="<?php echo esc_attr__('The color of the quiz container border','quiz-maker')?>">
                                                    <i class="ays_fa ays_fa_info_circle"></i>
                                                </a>
                                            </label>
                                            <input id="ays_quiz_border_color" 
                                                   class="ays-text-input" 
                                                   type="text" 
                                                   data-alpha="true"
                                                   name='ays_quiz_border_color'
                                                   value="<?php echo esc_attr($quiz_border_color); ?>" 
                                                   data-default-color="#000000">
                                        </div>
                                    </div>
                                </div><!-- Quiz container border -->
                                <hr/>
                                <div class="form-group row">
                                    <div class="col-sm-5">
                                        <label for='ays_quiz_image_height'>
                                            <?php echo esc_html__('Quiz image height', 'quiz-maker'); ?>
                                            <a class="ays_help" data-toggle="tooltip" title="<?php echo esc_attr__('Set quiz image height in pixels. It accepts only number values.','quiz-maker'); ?>">
                                                <i class="ays_fa ays_fa_info_circle"></i>
                                            </a>
                                        </label>
                                    </div>
                                    <div class="col-sm-7 ays_divider_left ays_quiz_display_flex_width">
                                        <div>
                                            <input type="number" class="ays-text-input ays-text-input-short" id='ays_quiz_image_height' name='ays_quiz_image_height' value="<?php echo intval($quiz_image_height); ?>"/>
                                        </div>
                                        <div class="ays_quiz_dropdown_max_width">
                                            <input type="text" value="px" class='ays-quiz-form-hint-for-size' disabled>
                                        </div>
                                    </div>
                                </div><!-- Quiz image height -->
                                <hr/>
                                <div class="form-group row">
                                    <div class="col-sm-5">
                                        <label for="ays_progress_bar_style">
                                            <?php echo esc_html__('Progress bar style','quiz-maker')?>
                                            <a class="ays_help" data-toggle="tooltip" title="<?php echo esc_attr__('Design of the progress bar which will appear on the finish page only. It will show the user’s score.','quiz-maker')?>">
                                                <i class="ays_fa ays_fa_info_circle"></i>
                                            </a>
                                        </label>
                                    </div>
                                    <div class="col-sm-7 ays_divider_left">
                                        <select id="ays_progress_bar_style" name="ays_progress_bar_style" class="ays-text-input ays-text-input-short">
                                            <option <?php echo ($progress_bar_style == 'first') ? 'selected' : ''; ?> value="first"><?php echo esc_html__( 'Rounded', 'quiz-maker'); ?></option>
                                            <option <?php echo ($progress_bar_style == 'second') ? 'selected' : ''; ?> value="second"><?php echo esc_html__( 'Rectangle', 'quiz-maker'); ?></option>
                                            <option <?php echo ($progress_bar_style == 'third') ? 'selected' : ''; ?> value="third"><?php echo esc_html__( 'With stripes', 'quiz-maker'); ?></option>
                                            <option <?php echo ($progress_bar_style == 'fourth') ? 'selected' : ''; ?> value="fourth"><?php echo esc_html__( 'With stripes and animation', 'quiz-maker'); ?></option>
                                        </select>
                                        <div style="margin:20px 0;">
                                            <div class='ays-progress first <?php echo ($progress_bar_style == 'first') ? "display_block" : ""; ?>'>
                                                <span class='ays-progress-value first' style='width:67%;'>67%</span>
                                                <div class="ays-progress-bg first">
                                                    <div class="ays-progress-bar first" style='width:67%;'></div>
                                                </div>
                                            </div>

                                            <div class='ays-progress second <?php echo ($progress_bar_style == 'second') ? "display_block" : ""; ?>'>
                                                <span class='ays-progress-value second' style='width:88%;'>88%</span>
                                                <div class="ays-progress-bg second">
                                                    <div class="ays-progress-bar second" style='width:88%;'></div>
                                                </div>
                                            </div>

                                            <div class="ays-progress third <?php echo ($progress_bar_style == 'third') ? "display_block" : ""; ?>">
                                                <span class="ays-progress-value third">55%</span>
                                                <div class="ays-progress-bg third">
                                                    <div class="ays-progress-bar third" style='width:55%;'></div>
                                                </div>
                                            </div>

                                            <div class="ays-progress fourth <?php echo ($progress_bar_style == 'fourth') ? "display_block" : ""; ?>">
                                                <span class="ays-progress-value fourth">34%</span>
                                                <div class="ays-progress-bg fourth">
                                                    <div class="ays-progress-bar fourth" style="width:34%"></div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div><!-- Progress bar style -->
                                <hr>
                                <!-- Progress Live bar style start -->
                                <div class="form-group row">
                                    <div class="col-sm-5">
                                        <label for="ays_progress_bar_style">
                                            <?php echo esc_html__('Progress live bar style','quiz-maker'); ?>
                                            <a class="ays_help" data-toggle="tooltip" title="<?php echo esc_attr__('Choose your preferred design for the progress live bar which will appear while taking the quiz. It will show the current state of the user in the quiz.','quiz-maker');?>">
                                                <i class="ays_fa ays_fa_info_circle"></i>
                                            </a>
                                        </label>
                                    </div>
                                    <div class="col-sm-7 ays_divider_left">
                                        <select id="ays_progress_live_bar_style" name="ays_progress_live_bar_style" class="ays-text-input ays-text-input-short">
                                            <option <?php echo ($progress_live_bar_style == 'default') ? 'selected' : ''; ?> value="default"><?php echo esc_html__( 'Default', 'quiz-maker'); ?></option>
                                            <option <?php echo ($progress_live_bar_style == 'second') ? 'selected' : ''; ?> value="second"><?php echo esc_html__( 'Rectangle', 'quiz-maker'); ?></option>
                                            <option <?php echo ($progress_live_bar_style == 'third') ? 'selected' : ''; ?> value="third"><?php echo esc_html__( 'With stripes', 'quiz-maker'); ?></option>
                                            <option <?php echo ($progress_live_bar_style == 'fourth') ? 'selected' : ''; ?> value="fourth"><?php echo esc_html__( 'With stripes and animation', 'quiz-maker'); ?></option>
                                        </select>
                                        <div style="margin:20px 0;">
                                            <div class="ays-progress default <?php echo ($progress_live_bar_style == 'default') ? "display_block" : ""; ?>">
                                                <span class="ays-progress-value ays-live-default" aria-valuenow="100"><?php echo ($progress_live_bar_style == 'default') ? "100%" : ""; ?></span>
                                                <div class="ays-progress-bg ays-live-default-line"></div>
                                            </div>

                                            <div class='ays-progress second <?php echo ($progress_live_bar_style == 'second') ? "display_block" : ""; ?>'>
                                                <span class='ays-progress-value second' style='width:67%;'>67%</span>
                                                <div class="ays-progress-bg second">
                                                    <div class="ays-progress-bar second" style='width:67%;'></div>
                                                </div>
                                            </div>

                                            <div class='ays-progress third <?php echo ($progress_live_bar_style == 'third') ? "display_block" : ""; ?> ays-live-preview'>
                                                <span class='ays-progress-value third ays-live-third' style='width:88%;'>88%</span>
                                                <div class="ays-progress-bg third ays-live-preview">
                                                    <div class="ays-progress-bar third ays-live-preview" style='width:88%;'></div>
                                                </div>
                                            </div>

                                            <div class="ays-progress fourth <?php echo ($progress_live_bar_style == 'fourth') ? "display_block" : ""; ?> ays-live-preview">
                                                <span class="ays-progress-value fourth ays-live-preview">55%</span>
                                                <div class="ays-progress-bg fourth ays-live-preview">
                                                    <div class="ays-progress-bar fourth ays-live-preview" style='width:55%;'></div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div><!-- Progress live bar style -->
                                <!-- Progress Live bar style end -->
                                <hr/>
                                <div class="form-group row">
                                    <div class="col-sm-5">
                                        <label for="ays_buttons_position">
                                            <?php echo esc_html__('Buttons position','quiz-maker'); ?>
                                            <a class="ays_help" data-toggle="tooltip" title="<?php echo esc_attr__('Specify the position of buttons of the quiz.','quiz-maker'); ?>">
                                                <i class="ays_fa ays_fa_info_circle"></i>
                                            </a>
                                        </label>
                                    </div>
                                    <div class="col-sm-7 ays_divider_left">
                                        <select id="ays_buttons_position" name="ays_buttons_position" class="ays-text-input ays-text-input-short">
                                            <option <?php echo ($buttons_position == 'center') ? 'selected' : ''; ?> value="center"><?php echo esc_html__( 'Center', 'quiz-maker'); ?></option>
                                            <option <?php echo ($buttons_position == 'flex-start') ? 'selected' : ''; ?> value="flex-start"><?php echo esc_html__( 'Left', 'quiz-maker'); ?></option>
                                            <option <?php echo ($buttons_position == 'flex-end') ? 'selected' : ''; ?> value="flex-end"><?php echo esc_html__( 'Right', 'quiz-maker'); ?></option>
                                            <option <?php echo ($buttons_position == 'space-between') ? 'selected' : ''; ?> value="space-between"><?php echo esc_html__( 'Space Between', 'quiz-maker'); ?></option>
                                        </select>
                                    </div>
                                </div><!-- Buttons position -->
                                <hr/>
                                <div class="form-group row">
                                    <div class="col-sm-5">
                                        <label for="ays_quiz_title_transformation">
                                            <?php echo esc_html__('Quiz title transformation', 'quiz-maker' ); ?>
                                            <a class="ays_help" data-toggle="tooltip" data-html="true" data-placement="top" title="<?php
                                                echo esc_attr__("Specify how to capitalize a title text of your quiz.", 'quiz-maker') .
                                                    "<ul style='list-style-type: circle;padding-left: 20px;'>".
                                                        "<li>". __('Uppercase – Transforms all characters to uppercase','quiz-maker') ."</li>".
                                                        "<li>". __('Lowercase – Transforms all characters to lowercase','quiz-maker') ."</li>".
                                                        "<li>". __('Capitalize – Transforms the first character of each word to uppercase','quiz-maker') ."</li>".
                                                    "</ul>";
                                                ?>">
                                                <i class="ays_fa ays_fa_info_circle"></i>
                                            </a>
                                        </label>
                                    </div>
                                    <div class="col-sm-7 ays_divider_left">
                                        <select name="ays_quiz_title_transformation" id="ays_quiz_title_transformation" class="ays-text-input ays-text-input-short" style="display:block;">
                                            <option value="uppercase" <?php echo $quiz_title_transformation == 'uppercase' ? 'selected' : ''; ?>><?php echo esc_html__( "Uppercase", 'quiz-maker' ); ?></option>
                                            <option value="lowercase" <?php echo $quiz_title_transformation == 'lowercase' ? 'selected' : ''; ?>><?php echo esc_html__( "Lowercase", 'quiz-maker' ); ?></option>
                                            <option value="capitalize" <?php echo $quiz_title_transformation == 'capitalize' ? 'selected' : ''; ?>><?php echo esc_html__( "Capitalize", 'quiz-maker' ); ?></option>
                                            <option value="none" <?php echo $quiz_title_transformation == 'none' ? 'selected' : ''; ?>><?php echo esc_html__( "None", 'quiz-maker' ); ?></option>
                                        </select>
                                    </div>
                                </div><!-- Quiz title transformation -->
                                <hr/>
                                <div class="form-group row">
                                    <div class="col-sm-5">
                                        <label for='ays_quiz_title_font_size'>
                                            <?php echo esc_html__('Quiz title font size', 'quiz-maker'); ?>
                                            <a class="ays_help" data-toggle="tooltip" title="<?php echo esc_attr__('Set your preferred text size for the Quiz Title. The default size is 21px.','quiz-maker'); ?>">
                                                <i class="ays_fa ays_fa_info_circle"></i>
                                            </a>
                                        </label>
                                    </div>
                                    <div class="col-sm-7 ays_divider_left">
                                        <div class="row">
                                            <div class="col-sm-5">
                                                <label for='ays_answers_font_size'>
                                                    <?php echo esc_html__('On desktop', 'quiz-maker'); ?>
                                                    <a class="ays_help" data-toggle="tooltip" title="<?php echo esc_attr__('Define the font size for desktop devices.','quiz-maker'); ?>">
                                                        <i class="ays_fa ays_fa_info_circle"></i>
                                                    </a>
                                                </label>
                                            </div>
                                            <div class="col-sm-7 ays_quiz_display_flex_width">
                                                <div>
                                                    <input type="number" class="ays-text-input" id='ays_quiz_title_font_size' name='ays_quiz_title_font_size' value="<?php echo intval($quiz_title_font_size); ?>"/>
                                                </div>
                                                <div class="ays_quiz_dropdown_max_width">
                                                    <input type="text" value="px" class='ays-quiz-form-hint-for-size' disabled>
                                                </div>
                                            </div>
                                        </div>
                                        <hr>
                                        <div class="row">
                                            <div class="col-sm-5">
                                                <label for='ays_quiz_title_mobile_font_size'>
                                                    <?php echo esc_html__('On mobile', 'quiz-maker'); ?>
                                                    <a class="ays_help" data-toggle="tooltip" title="<?php echo esc_attr__('Define the font size for mobile devices.','quiz-maker'); ?>">
                                                        <i class="ays_fa ays_fa_info_circle"></i>
                                                    </a>
                                                </label>
                                            </div>
                                            <div class="col-sm-7 ays_quiz_display_flex_width">
                                                <div>
                                                    <input type="number" class="ays-text-input" id='ays_quiz_title_mobile_font_size' name='ays_quiz_title_mobile_font_size' value="<?php echo $quiz_title_mobile_font_size; ?>"/>
                                                </div>
                                                <div class="ays_quiz_dropdown_max_width">
                                                    <input type="text" value="px" class='ays-quiz-form-hint-for-size' disabled>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div><!-- Quiz title font size -->
                                <hr/>
                                <div class="form-group row">
                                    <div class="col-sm-5">
                                        <label for="ays_quiz_enable_title_text_shadow">
                                            <?php echo __('Quiz title text shadow','quiz-maker')?>
                                            <a class="ays_help" data-toggle="tooltip" title="<?php echo __('Specify the text shadow of the quiz title.','quiz-maker')?>">
                                                <i class="ays_fa ays_fa_info_circle"></i>
                                            </a>
                                        </label>
                                    </div>
                                    <div class="col-sm-7 ays_divider_left">
                                        <input type="checkbox" class="ays_toggle ays_toggle_slide" id="ays_quiz_enable_title_text_shadow" name="ays_quiz_enable_title_text_shadow" <?php echo ($quiz_enable_title_text_shadow == 'on') ? 'checked' : ''; ?>/>
                                        <label for="ays_quiz_enable_title_text_shadow" class="ays_switch_toggle">Toggle</label>
                                        <div class="col-sm-12 ays_toggle_target ays_divider_top <?php echo ($quiz_enable_title_text_shadow == 'on') ? '' : 'display_none'; ?>" style="margin-top: 10px; padding-top: 10px;">
                                            <div class="form-group row">
                                                <div class="col-sm-12">
                                                    <label for="ays_quiz_title_text_shadow_color">
                                                        <?php echo __('Text shadow color','quiz-maker')?>
                                                        <a class="ays_help" data-toggle="tooltip" title="<?php echo __('The color of the text shadow of the quiz title.','quiz-maker' ); ?>">
                                                            <i class="ays_fa ays_fa_info_circle"></i>
                                                        </a>
                                                     </label>
                                                    <input type="text" class="ays-text-input" id='ays_quiz_title_text_shadow_color' name='ays_quiz_title_text_shadow_color' data-alpha="true" data-default-color="#333" value="<?php echo $quiz_title_text_shadow_color; ?>"/>
                                               </div>
                                            </div>
                                            <hr>
                                            <div class="form-group row">
                                                <div class="col-sm-12">
                                                    <div class="col-sm-3" style="display: inline-block;">
                                                        <span class="ays_quiz_small_hint_text"><?php echo __('X', 'quiz-maker'); ?></span>
                                                        <input type="number" class="ays-text-input ays-text-input-90-width" id='ays_quiz_title_text_shadow_x_offset' name='ays_quiz_title_text_shadow_x_offset' value="<?php echo $quiz_title_text_shadow_x_offset; ?>" />
                                                    </div>
                                                    <div class="col-sm-3 ays_divider_left" style="display: inline-block;">
                                                        <span class="ays_quiz_small_hint_text"><?php echo __('Y', 'quiz-maker'); ?></span>
                                                        <input type="number" class="ays-text-input ays-text-input-90-width" id='ays_quiz_title_text_shadow_y_offset' name='ays_quiz_title_text_shadow_y_offset' value="<?php echo $quiz_title_text_shadow_y_offset; ?>" />
                                                    </div>
                                                    <div class="col-sm-3 ays_divider_left" style="display: inline-block;">
                                                        <span class="ays_quiz_small_hint_text"><?php echo __('Z', 'quiz-maker'); ?></span>
                                                        <input type="number" class="ays-text-input ays-text-input-90-width" id='ays_quiz_title_text_shadow_z_offset' name='ays_quiz_title_text_shadow_z_offset' value="<?php echo $quiz_title_text_shadow_z_offset; ?>" />
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div> <!-- Quiz title text shadow -->
                                <hr/>
                                <div class="form-group row">
                                    <div class="col-sm-5">
                                        <label for="ays_custom_class">
                                            <?php echo __('Custom class for quiz container','quiz-maker'); ?>
                                            <a class="ays_help" data-toggle="tooltip" title="<?php echo __('Custom HTML class for quiz container. You can use your class for adding your custom styles for quiz container.','quiz-maker'); ?>">
                                                <i class="ays_fa ays_fa_info_circle"></i>
                                            </a>
                                        </label>
                                    </div>
                                    <div class="col-sm-7 ays_divider_left">
                                        <input type="text" class="ays-text-input" name="ays_custom_class" id="ays_custom_class" placeholder="myClass myAnotherClass..." value="<?php echo esc_attr($custom_class); ?>">
                                    </div>
                                </div><!-- Custom class for quiz container -->
                                <hr/>
                                <div class="ays-quiz-accordion-options-main-container" data-collapsed="false">
                                    <div class="ays-quiz-accordion-container">
                                        <?php echo $quiz_accordion_svg_html; ?>
                                        <p class="ays-subtitle"><?php echo __('Question Styles','quiz-maker'); ?></p>
                                    </div>
                                    <hr class="ays-quiz-bolder-hr"/>
                                    <div class="ays-quiz-accordion-options-box">
                                        <div class="form-group row">
                                            <div class="col-sm-5">
                                                <label for='ays_quest_animation'>
                                                    <?php echo __('Animation effect', 'quiz-maker'); ?>
                                                    <a class="ays_help" data-toggle="tooltip" title="<?php echo __('Animation effect of transition between questions','quiz-maker')?>">
                                                        <i class="ays_fa ays_fa_info_circle"></i>
                                                    </a>
                                                </label>
                                            </div>
                                            <div class="col-sm-7 ays_divider_left">
                                                <select class="ays-text-input ays-text-input-short" name="ays_quest_animation" id="ays_quest_animation">
                                                    <option <?php echo $quest_animation == "none" ? "selected" : ""; ?> value="none">None</option>
                                                    <option <?php echo $quest_animation == "fade" ? "selected" : ""; ?> value="fade">Fade</option>
                                                    <option <?php echo $quest_animation == "shake" ? "selected" : ""; ?> value="shake">Shake</option>
                                                </select>
                                            </div>
                                        </div><!-- Animation effect -->
                                        <hr/>
                                        <div class="form-group row">
                                            <div class="col-sm-5">
                                                <label for='ays_answers_font_size'>
                                                    <?php echo __('Question font size', 'quiz-maker'); ?>
                                                    <a class="ays_help" data-toggle="tooltip" title="<?php echo __('The font size of the questions in pixels in the quiz (only for <p> tag). It accepts only numeric values.','quiz-maker')?>">
                                                        <i class="ays_fa ays_fa_info_circle"></i>
                                                    </a>
                                                </label>
                                            </div>
                                            <div class="col-sm-7 ays_divider_left">
                                                <div class="row">
                                                    <div class="col-sm-5">
                                                        <label for='ays_question_font_size'>
                                                            <?php echo __('On desktop', 'quiz-maker'); ?>
                                                            <a class="ays_help" data-toggle="tooltip" title="<?php echo __('Define the font size for desktop devices.','quiz-maker'); ?>">
                                                                <i class="ays_fa ays_fa_info_circle"></i>
                                                            </a>
                                                        </label>
                                                    </div>
                                                    <div class="col-sm-7 ays_quiz_display_flex_width">
                                                        <div>
                                                            <input type="number" class="ays-text-input" id='ays_question_font_size'name='ays_question_font_size' value="<?php echo $question_font_size; ?>"/>
                                                        </div>
                                                        <div class="ays_quiz_dropdown_max_width ays-display-flex" style="align-items: end;">
                                                            <input type="text" value="px" class='ays-quiz-form-hint-for-size' disabled>
                                                        </div>
                                                    </div>
                                                </div>
                                                <hr>
                                                <div class="row">
                                                    <div class="col-sm-5">
                                                        <label for='ays_question_mobile_font_size'>
                                                            <?php echo __('On mobile', 'quiz-maker'); ?>
                                                            <a class="ays_help" data-toggle="tooltip" title="<?php echo __('Define the font size for mobile devices.','quiz-maker'); ?>">
                                                                <i class="ays_fa ays_fa_info_circle"></i>
                                                            </a>
                                                        </label>
                                                    </div>
                                                    <div class="col-sm-7 ays_quiz_display_flex_width">
                                                        <div>
                                                            <input type="number" class="ays-text-input" id='ays_question_mobile_font_size'name='ays_question_mobile_font_size' value="<?php echo $question_mobile_font_size; ?>"/>
                                                        </div>
                                                        <div class="ays_quiz_dropdown_max_width ays-display-flex" style="align-items: end;">
                                                            <input type="text" value="px" class='ays-quiz-form-hint-for-size' disabled>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div><!-- Question font size -->
                                        <hr/>
                                        <div class="form-group row">
                                            <div class="col-sm-5">
                                                <label for="ays_limitation_message">
                                                    <?php echo __( 'Question text alignment', 'quiz-maker' ); ?>
                                                    <a class="ays_help" data-toggle="tooltip" data-html="true" title="<?php echo __( 'Align the text of your questions to the left, center, or right.', 'quiz-maker' ); ?>">
                                                        <i class="ays_fa ays_fa_info_circle"></i>
                                                    </a>
                                                </label>
                                            </div>
                                            <div class="col-sm-7 ays_divider_left">
                                                <label class="ays_quiz_loader">
                                                    <input type="radio" id="ays_quiz_question_text_alignment_left" name="ays_quiz_question_text_alignment" value="left" <?php echo ($quiz_question_text_alignment == 'left') ? 'checked' : ''; ?>/>
                                                    <span for="ays_quiz_question_text_alignment_left"><?php echo __( 'Left', 'quiz-maker' ); ?></span>
                                                </label>
                                                <label class="ays_quiz_loader">
                                                    <input type="radio" id="ays_quiz_question_text_alignment_center" name="ays_quiz_question_text_alignment" value="center" <?php echo ($quiz_question_text_alignment == 'center') ? 'checked' : ''; ?>/>
                                                    <span for="ays_quiz_question_text_alignment_center"><?php echo __( 'Center', 'quiz-maker' ); ?></span>
                                                </label>
                                                <label class="ays_quiz_loader">
                                                    <input type="radio" id="ays_quiz_question_text_alignment_right" name="ays_quiz_question_text_alignment" value="right" <?php echo ($quiz_question_text_alignment == 'right') ? 'checked' : ''; ?>/>
                                                    <span for="ays_quiz_question_text_alignment_right"><?php echo __( 'Right', 'quiz-maker' ); ?></span>
                                                </label>
                                            </div>
                                        </div><!-- Question text alignment -->
                                        <hr/>
                                        <div class="form-group row">
                                            <div class="col-sm-5">
                                                <label>
                                                    <?php echo __('Question image styles','quiz-maker')?>
                                                    <a class="ays_help" data-toggle="tooltip" title="<?php echo __('It affects the images chosen from “Add Image” not from “Add media”  on the Edit question page.','quiz-maker')?>">
                                                        <i class="ays_fa ays_fa_info_circle"></i>
                                                    </a>
                                                </label>
                                            </div>
                                            <div class="col-sm-7 ays_divider_left">
                                                <div class="form-group row">
                                                    <div class="col-sm-12 ays_quiz_display_flex_width">
                                                        <div>
                                                            <label for="ays_image_width">
                                                                <?php echo __('Image Width','quiz-maker')?>
                                                                <a class="ays_help" data-toggle="tooltip" title="<?php echo __('Question image width in pixels. Set it 0 or leave it blank for making it 100%. It accepts only numeric values.','quiz-maker')?>">
                                                                    <i class="ays_fa ays_fa_info_circle"></i>
                                                                </a>
                                                            </label>
                                                            <input type="number" class="ays-text-input ays-text-input-short" id="ays_image_width" name="ays_image_width" value="<?php echo $image_width; ?>"/>
                                                            <span class="ays_quiz_small_hint_text"><?php echo __("For 100% leave blank", 'quiz-maker');?></span>
                                                        </div>
                                                        <div class="ays_quiz_dropdown_max_width ays-display-flex" style="align-items: center;">
                                                            <select id="ays_quiz_image_width_by_percentage_px" name="ays_quiz_image_width_by_percentage_px" class="ays-text-input ays-text-input-short" style="display:inline-block; width: 60px; margin-top: .5rem;">
                                                                <option value="pixels" <?php echo $quiz_image_width_by_percentage_px == "pixels" ? "selected" : ""; ?>><?php echo __( "px", 'quiz-maker' ); ?></option>
                                                                <option value="percentage" <?php echo $quiz_image_width_by_percentage_px == "percentage" ? "selected" : ""; ?>><?php echo __( "%", 'quiz-maker' ); ?></option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>
                                                <hr/>
                                                <div class="form-group row">
                                                    <div class="col-sm-12 ays_quiz_display_flex_width">
                                                        <div>
                                                            <label for="ays_image_height">
                                                                <?php echo __('Image Height','quiz-maker')?>
                                                                <a class="ays_help" data-toggle="tooltip" title="<?php echo __('Question image height in pixels. It accepts only number values.','quiz-maker')?>">
                                                                    <i class="ays_fa ays_fa_info_circle"></i>
                                                                </a>
                                                            </label>
                                                            <input type="number" class="ays-text-input ays-text-input-short" id="ays_image_height" name="ays_image_height" value="<?php echo (isset($options['image_height']) && $options['image_height'] != '') ? intval($options['image_height']) : ''; ?>"/>
                                                        </div>
                                                        <div class="ays_quiz_dropdown_max_width ays-display-flex" style="align-items: end;">
                                                            <input type="text" value="px" class='ays-quiz-form-hint-for-size' disabled>
                                                        </div>
                                                    </div>
                                                </div>
                                                <hr/>
                                                <div class="form-group row">
                                                    <div class="col-sm-12">
                                                        <label for="ays_image_sizing">
                                                            <?php echo __('Image sizing', 'quiz-maker' ); ?>
                                                            <a class="ays_help" data-toggle="tooltip" title="<?php echo __('It helps to configure the scale of the images inside the quiz in case of differences between the sizes.','quiz-maker')?>">
                                                                <i class="ays_fa ays_fa_info_circle"></i>
                                                            </a>
                                                        </label>
                                                        <select name="ays_image_sizing" id="ays_image_sizing" class="ays-text-input ays-text-input-short" style="display:block;">
                                                            <option value="cover" <?php echo $image_sizing == 'cover' ? 'selected' : ''; ?>><?php echo __( "Cover", 'quiz-maker' ); ?></option>
                                                            <option value="contain" <?php echo $image_sizing == 'contain' ? 'selected' : ''; ?>><?php echo __( "Contain", 'quiz-maker' ); ?></option>
                                                            <option value="none" <?php echo $image_sizing == 'none' ? 'selected' : ''; ?>><?php echo __( "None", 'quiz-maker' ); ?></option>
                                                            <option value="unset" <?php echo $image_sizing == 'unset' ? 'selected' : ''; ?>><?php echo __( "Unset", 'quiz-maker' ); ?></option>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                        </div><!-- Question image styles -->
                                        <hr/>
                                    </div>
                                </div>
                                <div class="ays-quiz-accordion-options-main-container" data-collapsed="false">
                                    <div class="ays-quiz-accordion-container">
                                        <?php echo $quiz_accordion_svg_html; ?>
                                        <p class="ays-subtitle"><?php echo __('Answers Styles','quiz-maker'); ?></p>
                                    </div>
                                    <div class="ays-quiz-accordion-options-box">
                                        <hr class="ays-quiz-bolder-hr"/>
                                        <div class="form-group row">
                                            <div class="col-sm-5">
                                                <label for='ays_answers_font_size'>
                                                    <?php echo __('Answer font size', 'quiz-maker'); ?>
                                                    <a class="ays_help" data-toggle="tooltip" title="<?php echo __('The font size of the answers in pixels in the quiz. It accepts only numeric values.','quiz-maker')?>">
                                                        <i class="ays_fa ays_fa_info_circle"></i>
                                                    </a>
                                                </label>
                                            </div>
                                            <div class="col-sm-7 ays_divider_left">
                                                <div class="row">
                                                    <div class="col-sm-5">
                                                        <label for='ays_answers_font_size'>
                                                            <?php echo __('On desktop', 'quiz-maker'); ?>
                                                            <a class="ays_help" data-toggle="tooltip" title="<?php echo __('Define the font size for desktop devices.','quiz-maker'); ?>">
                                                                <i class="ays_fa ays_fa_info_circle"></i>
                                                            </a>
                                                        </label>
                                                    </div>
                                                    <div class="col-sm-7 ays_quiz_display_flex_width">
                                                        <div>
                                                            <input type="number" class="ays-text-input" id='ays_answers_font_size'name='ays_answers_font_size' value="<?php echo $answers_font_size; ?>"/>
                                                        </div>
                                                        <div class="ays_quiz_dropdown_max_width ays-display-flex" style="align-items: end;">
                                                            <input type="text" value="px" class='ays-quiz-form-hint-for-size' disabled>
                                                        </div>
                                                    </div>
                                                </div>
                                                <hr>
                                                <div class="row">
                                                    <div class="col-sm-5">
                                                        <label for='ays_answers_mobile_font_size'>
                                                            <?php echo __('On mobile', 'quiz-maker'); ?>
                                                            <a class="ays_help" data-toggle="tooltip" title="<?php echo __('Define the font size for mobile devices.','quiz-maker'); ?>">
                                                                <i class="ays_fa ays_fa_info_circle"></i>
                                                            </a>
                                                        </label>
                                                    </div>
                                                    <div class="col-sm-7 ays_quiz_display_flex_width">
                                                        <div>
                                                            <input type="number" class="ays-text-input" id='ays_answers_mobile_font_size'name='ays_answers_mobile_font_size' value="<?php echo $answers_mobile_font_size; ?>"/>
                                                        </div>
                                                        <div class="ays_quiz_dropdown_max_width ays-display-flex" style="align-items: end;">
                                                            <input type="text" value="px" class='ays-quiz-form-hint-for-size' disabled>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div><!-- Answer font size -->
                                        <hr/>
                                        <div class="form-group row">
                                            <div class="col-sm-5">
                                                <label for="ays_answers_view">
                                                    <?php echo esc_html__('Answers view','quiz-maker'); ?>
                                                    <a class="ays_help" data-toggle="tooltip" title="<?php echo esc_attr__('Specify the design of the answers of question.','quiz-maker'); ?>">
                                                        <i class="ays_fa ays_fa_info_circle"></i>
                                                    </a>
                                                </label>
                                            </div>
                                            <div class="col-sm-7 ays_divider_left">
                                                <select class="ays-enable-timerl" id="ays_answers_view" name="ays_answers_view">
                                                    <option value="list" <?php echo (isset($options['answers_view']) && $options['answers_view'] == 'list') ? 'selected' : ''; ?>>
                                                        List
                                                    </option>
                                                    <option value="grid" <?php echo (isset($options['answers_view']) && $options['answers_view'] == 'grid') ? 'selected' : ''; ?>>
                                                        Grid
                                                    </option>
                                                </select>
                                            </div>
                                        </div> <!-- Answers view  -->
                                        <hr/>
                                        <div class="form-group row">
                                            <div class="col-sm-5">
                                                <label for="ays_answers_border">
                                                    <?php echo __('Answer border','quiz-maker')?>
                                                    <a class="ays_help" data-toggle="tooltip" title="<?php echo __('Allow answer border','quiz-maker')?>">
                                                        <i class="ays_fa ays_fa_info_circle"></i>
                                                    </a>
                                                </label>
                                            </div>
                                            <div class="col-sm-7 ays_divider_left">
                                                <input type="checkbox" class="ays_toggle ays_toggle_slide" id="ays_answers_border" name="ays_answers_border" value="on"
                                                       <?php echo ($answers_border) ? 'checked' : ''; ?>/>
                                                <label for="ays_answers_border" class="ays_switch_toggle">Toggle</label>
                                                <div class="col-sm-12 ays_toggle_target ays_divider_top" style="margin-top: 10px; padding-top: 10px; <?php echo ($answers_border) ? '' : 'display:none;' ?>">
                                                    <div class="ays_quiz_display_flex_width">
                                                        <div>
                                                            <label for="ays_answers_border_width">
                                                                <?php echo __('Border width','quiz-maker')?>
                                                                <a class="ays_help" data-toggle="tooltip" title="<?php echo __('The width of answers border','quiz-maker')?>">
                                                                    <i class="ays_fa ays_fa_info_circle"></i>
                                                                </a>
                                                             </label>
                                                            <input type="number" class="ays-text-input" id='ays_answers_border_width' name='ays_answers_border_width'
                                                                   value="<?php echo $answers_border_width; ?>"/>
                                                        </div>
                                                        <div class="ays_quiz_dropdown_max_width ays-display-flex" style="align-items: end;">
                                                            <input type="text" value="px" class='ays-quiz-form-hint-for-size' disabled>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-sm-12 ays_toggle_target ays_divider_top" style="margin-top: 10px; padding-top: 10px; <?php echo ($answers_border) ? '' : 'display:none;' ?>">
                                                    <label for="ays_answers_border_style">
                                                        <?php echo __('Border style','quiz-maker')?>
                                                        <a class="ays_help" data-toggle="tooltip" title="<?php echo __('The style of answers border','quiz-maker')?>">
                                                            <i class="ays_fa ays_fa_info_circle"></i>
                                                        </a>
                                                    </label>
                                                    <select id="ays_answers_border_style" name="ays_answers_border_style" class="ays-text-input">
                                                        <option <?php echo ($answers_border_style == 'solid') ? 'selected' : ''; ?> value="solid">Solid</option>
                                                        <option <?php echo ($answers_border_style == 'dashed') ? 'selected' : ''; ?> value="dashed">Dashed</option>
                                                        <option <?php echo ($answers_border_style == 'dotted') ? 'selected' : ''; ?> value="dotted">Dotted</option>
                                                        <option <?php echo ($answers_border_style == 'double') ? 'selected' : ''; ?> value="double">Double</option>
                                                        <option <?php echo ($answers_border_style == 'groove') ? 'selected' : ''; ?> value="groove">Groove</option>
                                                        <option <?php echo ($answers_border_style == 'ridge') ? 'selected' : ''; ?> value="ridge">Ridge</option>
                                                        <option <?php echo ($answers_border_style == 'inset') ? 'selected' : ''; ?> value="inset">Inset</option>
                                                        <option <?php echo ($answers_border_style == 'outset') ? 'selected' : ''; ?> value="outset">Outset</option>
                                                        <option <?php echo ($answers_border_style == 'none') ? 'selected' : ''; ?> value="none">None</option>
                                                    </select>
                                                </div>
                                                <div class="col-sm-12 ays_toggle_target ays_divider_top" style="margin-top: 10px; padding-top: 10px; <?php echo ($answers_border) ? '' : 'display:none;' ?>">
                                                    <label for="ays_answers_border_color">
                                                        <?php echo __('Border color','quiz-maker')?>
                                                        <a class="ays_help" data-toggle="tooltip" title="<?php echo __('The color of the answers border','quiz-maker')?>">
                                                            <i class="ays_fa ays_fa_info_circle"></i>
                                                        </a>
                                                    </label>
                                                    <input id="ays_answers_border_color" class="ays-text-input" type="text" data-alpha="true" name='ays_answers_border_color'
                                                           value="<?php echo $answers_border_color; ?>" data-default-color="#dddddd">
                                                </div>
                                            </div>
                                        </div><!-- Answers border -->
                                        <hr/>
                                        <div class="form-group row">
                                            <div class="col-sm-5">
                                                <label for="ays_answers_margin">
                                                    <?php echo __('Answer gap','quiz-maker')?>
                                                    <a class="ays_help" data-toggle="tooltip" title="<?php echo __('Gap between answers.','quiz-maker')?>">
                                                        <i class="ays_fa ays_fa_info_circle"></i>
                                                    </a>
                                                </label>
                                            </div>
                                            <div class="col-sm-7 ays_divider_left ays_quiz_display_flex_width">
                                                <div>
                                                    <input type="number" class="ays-text-input ays-text-input-short" id='ays_answers_margin' name='ays_answers_margin' value="<?php echo $answers_margin; ?>"/>
                                                </div>
                                                <div class="ays_quiz_dropdown_max_width ays-display-flex" style="align-items: end;">
                                                    <input type="text" value="px" class='ays-quiz-form-hint-for-size' disabled>
                                                </div>
                                            </div>
                                        </div><!-- Answers gap -->
                                        <hr/>
                                        <div class="form-group row">
                                            <div class="col-sm-5">
                                                <label for="ays_answers_box_shadow">
                                                    <?php echo __('Answers box shadow','quiz-maker')?>
                                                    <a class="ays_help" data-toggle="tooltip" title="<?php echo __('Allow answer container box shadow','quiz-maker')?>">
                                                        <i class="ays_fa ays_fa_info_circle"></i>
                                                    </a>
                                                </label>
                                            </div>
                                            <div class="col-sm-7 ays_divider_left">
                                                <input type="checkbox" class="ays_toggle ays_toggle_slide"
                                                       id="ays_answers_box_shadow" name="ays_answers_box_shadow"
                                                       <?php echo ($answers_box_shadow) ? 'checked' : ''; ?>/>
                                                <label for="ays_answers_box_shadow" class="ays_switch_toggle">Toggle</label>
                                                <div class="col-sm-12 ays_toggle_target ays_divider_top" style="margin-top: 10px; padding-top: 10px; <?php echo ($answers_box_shadow) ? '' : 'display:none;' ?>">
                                                    <div class="form-group row">
                                                        <div class="col-sm-12">
                                                            <label for="ays_answers_box_shadow_color">
                                                                <?php echo __('Answer box-shadow color','quiz-maker')?>
                                                                <a class="ays_help" data-toggle="tooltip" title="<?php echo __('The shadow color of answers container','quiz-maker')?>">
                                                                    <i class="ays_fa ays_fa_info_circle"></i>
                                                                </a>
                                                             </label>
                                                            <input type="text" class="ays-text-input" id='ays_answers_box_shadow_color' name='ays_answers_box_shadow_color' data-alpha="true" data-default-color="#000000" value="<?php echo $answers_box_shadow_color; ?>"/>
                                                       </div>
                                                    </div>
                                                    <hr>
                                                    <div class="form-group row">
                                                        <div class="col-sm-12">
                                                            <div class="col-sm-3" style="display: inline-block;">
                                                                <span class="ays_quiz_small_hint_text"><?php echo __('X', 'quiz-maker'); ?></span>
                                                                <input type="number" class="ays-text-input ays-text-input-90-width" id='ays_quiz_answer_box_shadow_x_offset' name='ays_quiz_answer_box_shadow_x_offset' value="<?php echo $quiz_answer_box_shadow_x_offset; ?>" />
                                                            </div>
                                                            <div class="col-sm-3 ays_divider_left" style="display: inline-block;">
                                                                <span class="ays_quiz_small_hint_text"><?php echo __('Y', 'quiz-maker'); ?></span>
                                                                <input type="number" class="ays-text-input ays-text-input-90-width" id='ays_quiz_answer_box_shadow_y_offset' name='ays_quiz_answer_box_shadow_y_offset' value="<?php echo $quiz_answer_box_shadow_y_offset; ?>" />
                                                            </div>
                                                            <div class="col-sm-3 ays_divider_left" style="display: inline-block;">
                                                                <span class="ays_quiz_small_hint_text"><?php echo __('Z', 'quiz-maker'); ?></span>
                                                                <input type="number" class="ays-text-input ays-text-input-90-width" id='ays_quiz_answer_box_shadow_z_offset' name='ays_quiz_answer_box_shadow_z_offset' value="<?php echo $quiz_answer_box_shadow_z_offset; ?>" />
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div><!-- Answers box shadow -->
                                        <hr/>
                                        <div class="form-group row">
                                            <div class="col-sm-5">
                                                <label for="ays_ans_right_wrong_icon">
                                                    <?php echo __('Right/wrong answer icons','quiz-maker'); ?>
                                                </label>
                                            </div>
                                            <div class="col-sm-7 ays_divider_left">
                                                <label class="ays_quiz_rw_icon ays_quiz_loader">
                                                    <input name="ays_ans_right_wrong_icon" type="radio" value="default" <?php echo $ans_right_wrong_icon == 'default' ? 'checked' : ''; ?>>
                                                    <img loading="lazy" class="right_icon" src="<?php echo AYS_QUIZ_PUBLIC_URL; ?>/images/correct.png">
                                                    <img loading="lazy" class="wrong_icon" src="<?php echo AYS_QUIZ_PUBLIC_URL; ?>/images/wrong.png">
                                                </label>
                                                <?php
                                                    for($i = 1; $i <= 10; $i++):
                                                        $right_style_name = "correct-style-".$i;
                                                        $wrong_style_name = "wrong-style-".$i;

                                                        $quiz_rw_answers_img_class = "";
                                                        if( $i == 9 ){
                                                            $quiz_rw_answers_img_class = "quiz_rw_answers_img_class";
                                                        }
                                                ?>
                                                <label class="ays_quiz_rw_icon ays_quiz_loader">
                                                    <input name="ays_ans_right_wrong_icon" type="radio" value="style-<?php echo $i; ?>" <?php echo $ans_right_wrong_icon == 'style-'.$i ? 'checked' : ''; ?>>
                                                    <img loading="lazy" class="right_icon <?php echo $quiz_rw_answers_img_class; ?>" src="<?php echo AYS_QUIZ_PUBLIC_URL; ?>/images/<?php echo $right_style_name; ?>.png">
                                                    <img loading="lazy" class="wrong_icon <?php echo $quiz_rw_answers_img_class; ?>" src="<?php echo AYS_QUIZ_PUBLIC_URL; ?>/images/<?php echo $wrong_style_name; ?>.png">
                                                </label>
                                                <?php
                                                    endfor;
                                                ?>
                                                <label class="ays_quiz_rw_icon ays_quiz_loader">
                                                    <input name="ays_ans_right_wrong_icon" type="radio" value="none" <?php echo $ans_right_wrong_icon == 'none' ? 'checked' : ''; ?>>
                                                    <?php echo __("None", 'quiz-maker'); ?>
                                                    <div></div>
                                                </label>
                                            </div>
                                        </div><!-- Right/wrong answer icons -->
                                        <hr/>
                                        <div class="form-group row">
                                            <div class="col-sm-5">
                                                <label for="ays_disable_hover_effect">
                                                    <?php echo __('Disable answer hover','quiz-maker'); ?>
                                                    <a class="ays_help" data-toggle="tooltip" title="<?php echo __('Disable the hover effect for the answers.', 'quiz-maker'); ?>">
                                                        <i class="ays_fa ays_fa_info_circle"></i>
                                                    </a>
                                                </label>
                                            </div>
                                            <div class="col-sm-7 ays_divider_left">
                                                <input type="checkbox" id="ays_disable_hover_effect" name="ays_disable_hover_effect" class="ays_toggle ays_toggle_slide" <?php echo ($disable_hover_effect) ? 'checked' : ''; ?>/>
                                                <label for="ays_disable_hover_effect" class="ays_switch_toggle">Toggle</label>
                                            </div>
                                        </div><!-- Disable answer hover -->
                                        <hr/>
                                        <div class="form-group row">
                                            <div class="col-sm-5">
                                                <label for="ays_ans_img_height">
                                                    <?php echo esc_html__('Answer image height', 'quiz-maker'); ?>
                                                    <a class="ays_help" data-toggle="tooltip" title="<?php echo esc_html__('Height of answers images.','quiz-maker'); ?>">
                                                        <i class="ays_fa ays_fa_info_circle"></i>
                                                    </a>
                                                </label>
                                            </div>
                                            <div class="col-sm-7 ays_divider_left ays_quiz_display_flex_width">
                                                <div>
                                                    <input type="number" class="ays-text-input ays-text-input-short" id='ays_ans_img_height' name='ays_ans_img_height' value="<?php echo esc_attr($ans_img_height); ?>"/>
                                                </div>
                                                <div class="ays_quiz_dropdown_max_width ays-display-flex" style="align-items: end;">
                                                    <input type="text" value="px" class='ays-quiz-form-hint-for-size' disabled>
                                                </div>
                                            </div>
                                        </div> <!-- Answers image height -->
                                        <hr/>
                                        <div class="form-group row">
                                            <div class="col-sm-5">
                                                <label for="ays_show_answers_caption">
                                                    <?php echo esc_html__('Show answer caption', 'quiz-maker'); ?>
                                                    <a class="ays_help" data-toggle="tooltip" title="<?php echo esc_attr__('Show answers caption near the answer image. This option will be work only when answer has image.', 'quiz-maker'); ?>">
                                                        <i class="ays_fa ays_fa_info_circle"></i>
                                                    </a>
                                                </label>
                                            </div>
                                            <div class="col-sm-7 ays_divider_left">
                                                <input type="checkbox" class="ays_toggle ays_toggle_slide"
                                                    id="ays_show_answers_caption" name="ays_show_answers_caption"
                                                    <?php echo ($show_answers_caption) ? 'checked' : ''; ?>/>
                                                <label for="ays_show_answers_caption" class="ays_switch_toggle">Toggle</label>
                                            </div>
                                        </div> <!-- Show answers caption -->
                                        <hr/>
                                        <div class="form-group row">
                                            <div class="col-sm-5">
                                                <label for="ays_ans_img_caption_position">
                                                    <?php echo esc_html__('Caption position of the image answer', 'quiz-maker'); ?>
                                                    <a class="ays_help" data-toggle="tooltip" title="<?php echo esc_attr__('Choose the position of captions in the image answers.', 'quiz-maker')?>">
                                                        <i class="ays_fa ays_fa_info_circle"></i>
                                                    </a>
                                                </label>
                                            </div>
                                            <div class="col-sm-7 ays_divider_left">
                                                <select id="ays_ans_img_caption_position" name="ays_ans_img_caption_position" class="ays-text-input ays-text-input-short">
                                                    <option <?php echo ($ans_img_caption_position == 'top') ? 'selected' : ''; ?> value="top"><?php echo esc_html__('Top', 'quiz-maker'); ?></option>
                                                    <option <?php echo ($ans_img_caption_position == 'bottom') ? 'selected' : ''; ?> value="bottom"><?php echo esc_html__('Bottom', 'quiz-maker'); ?></option>
                                                </select>
                                            </div>
                                        </div> <!-- Answers image caption position -->
                                        <hr/>
                                        <div class="form-group row">
                                            <div class="col-sm-12 only_pro" style="padding:10px 0 0 10px;">
                                                <div class="pro_features" style="justify-content:flex-end;">

                                                </div>
                                                <div class="form-group row">
                                                    <div class="col-sm-5">
                                                        <label for="ays_ans_img_caption_style">
                                                            <?php echo __('Caption style of the image answer','quiz-maker'); ?>
                                                            <a class="ays_help" data-toggle="tooltip" title="<?php echo __('Choose the preferred view type of captions in the image answers.','quiz-maker'); ?>">
                                                                <i class="ays_fa ays_fa_info_circle"></i>
                                                            </a>
                                                        </label>
                                                    </div>
                                                    <div class="col-sm-7 ays_divider_left">
                                                        <select class="ays-text-input ays-text-input-short">
                                                            <option value="outside" selected><?php echo __('Outside', 'quiz-maker'); ?></option>
                                                            <option value="inside"><?php echo __('Inside', 'quiz-maker'); ?></option>
                                                        </select>
                                                    </div>
                                                </div> <!-- Answers image caption style -->
                                                <a href="https://ays-pro.com/wordpress/quiz-maker?utm_source=dashboard&utm_medium=quiz-free&utm_campaign=style-tab-answer-image-options-<?php echo esc_attr( AYS_QUIZ_UTM_VERSION ); ?>" target="_blank" class="ays-quiz-new-upgrade-button-link">
                                                    <div class="ays-quiz-new-upgrade-button-box">
                                                        <div>
                                                            <img loading="lazy" src="<?php echo AYS_QUIZ_ADMIN_URL.'/images/icons/locked_24x24.svg'?>">
                                                            <img loading="lazy" src="<?php echo AYS_QUIZ_ADMIN_URL.'/images/icons/unlocked_24x24.svg'?>" class="ays-quiz-new-upgrade-button-hover">
                                                        </div>
                                                        <div class="ays-quiz-new-upgrade-button"><?php echo __("Upgrade", "quiz-maker"); ?></div>
                                                    </div>
                                                </a>
                                            </div>
                                        </div><!-- pro_features -->
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-5 col-sm-12 ays_divider_left" style="position:relative;">
                                <div id="ays_styles_tab" style="position:sticky;top:50px; margin:auto;">
                                    <div class="ays-quiz-live-container ays-quiz-live-container-1">
                                        <div class="step active-step">
                                            <div class="ays-abs-fs">
                                                <img loading="lazy" src="" alt="Ays Question Image" class="ays-quiz-live-image">
                                                <p class="ays-fs-title ays-quiz-live-title"></p>
                                                <p class="ays-fs-subtitle ays-quiz-live-subtitle"></p>
                                                <input type="hidden" name="ays_quiz_id" value="2">
                                                <div class="ays_buttons_div">
                                                    <input type="button" name="next" class="ays_next action-button ays-quiz-live-button"
                                                        value="<?php echo __( "Start", 'quiz-maker' ); ?>">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="ays-quiz-live-container ays-quiz-live-container-2" style="display:none;">
                                        <div class="step active-step">
                                            <div class="ays-abs-fs">
                                                <img loading="lazy" src="" alt="Ays Question Image" class="ays-quiz-live-image">
                                                <p class="ays-fs-title ays-quiz-live-title"></p>
                                                <p class="ays-fs-subtitle ays-quiz-live-subtitle"></p>
                                                <input type="hidden" name="ays_quiz_id" value="2">
                                                <div class="ays_buttons_div">
                                                    <input type="button" name="next" class="ays_next action-button ays-quiz-live-button"
                                                       value="<?php echo __( "Start", 'quiz-maker' ); ?>">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <p style="text-align: center;">
                                        <span class="ays_quiz_small_hint_text" style="color: #ccc;"><?php echo __("Note: This is only a live preview and the quiz cannot be started here.", 'quiz-maker'); ?></span>
                                    </p>
                                </div>
                            </div>
                        </div>
                        <hr/>
                    </div><!-- .cow -->
                </div><!-- .ays-quiz-accordion-options-main-container -->
                <div class="ays-quiz-accordion-options-main-container" data-collapsed="false">
                    <div class="ays-quiz-accordion-container">
                        <?php echo $quiz_accordion_svg_html; ?>
                        <p class="ays-subtitle"><?php echo __('Buttons Styles','quiz-maker'); ?></p>
                    </div>
                    <hr class="ays-quiz-bolder-hr"/>
                    <div class="ays-quiz-accordion-options-box">
                        <div class="form-group row"><!-- Buttons Styles -->
                            <div class="col-lg-7 col-sm-12">
                                <div class="form-group row">
                                    <div class="col-sm-5">
                                        <label for="ays_buttons_size">
                                            <?php echo __('Button size','quiz-maker'); ?>
                                            <a class="ays_help" data-toggle="tooltip" title="<?php echo __('The default sizes of buttons.','quiz-maker')?>">
                                                <i class="ays_fa ays_fa_info_circle"></i>
                                            </a>
                                        </label>
                                    </div>
                                    <div class="col-sm-7 ays_divider_left">
                                        <select class="ays-text-input ays-text-input-short" id="ays_buttons_size" name="ays_buttons_size">
                                            <option value="small" <?php echo (isset($options['buttons_size']) && $options['buttons_size'] == 'small') ? 'selected' : ''; ?>>
                                                <?php echo __('Small','quiz-maker')?>
                                            </option>
                                            <option value="medium" <?php echo ( (isset($options['buttons_size']) && $options['buttons_size'] == 'medium') || !isset($options['buttons_size']) ) ? 'selected' : ''; ?>>
                                                <?php echo __('Medium','quiz-maker')?>
                                            </option>
                                            <option value="large" <?php echo (isset($options['buttons_size']) && $options['buttons_size'] == 'large') ? 'selected' : ''; ?>>
                                                <?php echo __('Large','quiz-maker')?>
                                            </option>
                                        </select>
                                    </div>
                                </div><!-- Button size -->
                                <hr>
                                <div class="form-group row">
                                    <div class="col-sm-5">
                                        <label for='ays_buttons_font_size'>
                                            <?php echo __('Button font-size', 'quiz-maker'); ?>
                                            <a class="ays_help" data-toggle="tooltip" title="<?php echo __('The font size of the buttons in pixels in the quiz. It accepts only numeric values.','quiz-maker')?>">
                                                <i class="ays_fa ays_fa_info_circle"></i>
                                            </a>
                                        </label>
                                    </div>
                                    <div class="col-sm-7 ays_divider_left">
                                        <div class="row">
                                            <div class="col-sm-5">
                                                <label for='ays_buttons_font_size'>
                                                    <?php echo __('On desktop', 'quiz-maker'); ?>
                                                    <a class="ays_help" data-toggle="tooltip" title="<?php echo __('Define the font size for desktop devices.','quiz-maker'); ?>">
                                                        <i class="ays_fa ays_fa_info_circle"></i>
                                                    </a>
                                                </label>
                                            </div>
                                            <div class="col-sm-7 ays_quiz_display_flex_width">
                                                <div>
                                                    <input type="number" class="ays-text-input" id='ays_buttons_font_size' name='ays_buttons_font_size' value="<?php echo $buttons_font_size; ?>"/>
                                                </div>
                                                <div class="ays_quiz_dropdown_max_width ays-display-flex" style="align-items: end;">
                                                    <input type="text" value="px" class='ays-quiz-form-hint-for-size' disabled>
                                                </div>
                                            </div>
                                        </div>
                                        <hr>
                                        <div class="row">
                                            <div class="col-sm-5">
                                                <label for='ays_buttons_mobile_font_size'>
                                                    <?php echo __('On mobile', 'quiz-maker'); ?>
                                                    <a class="ays_help" data-toggle="tooltip" title="<?php echo __('Define the font size for mobile devices.','quiz-maker'); ?>">
                                                        <i class="ays_fa ays_fa_info_circle"></i>
                                                    </a>
                                                </label>
                                            </div>
                                            <div class="col-sm-7 ays_quiz_display_flex_width">
                                                <div>
                                                    <input type="number" class="ays-text-input" id='ays_buttons_mobile_font_size'name='ays_buttons_mobile_font_size' value="<?php echo $buttons_mobile_font_size; ?>"/>
                                                </div>
                                                <div class="ays_quiz_dropdown_max_width ays-display-flex" style="align-items: end;">
                                                    <input type="text" value="px" class='ays-quiz-form-hint-for-size' disabled>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div><!-- Buttons font size -->
                                <hr>
                                <div class="form-group row">
                                    <div class="col-sm-5">
                                        <label for='ays_buttons_width'>
                                            <?php echo __('Button width', 'quiz-maker'); ?>
                                            <a class="ays_help" data-toggle="tooltip" title="<?php echo __('Set the button width in pixels. For an initial width, leave the field blank.', 'quiz-maker'); ?>">
                                                <i class="ays_fa ays_fa_info_circle"></i>
                                            </a>
                                        </label>
                                    </div>
                                    <div class="col-sm-7 ays_divider_left ays_quiz_display_flex_width">
                                        <div>
                                            <input type="number" class="ays-text-input ays-text-input-short" id='ays_buttons_width'name='ays_buttons_width' value="<?php echo $buttons_width; ?>"/>
                                            <span style="display:block;" class="ays_quiz_small_hint_text"><?php echo __('For an initial width, leave the field blank.', 'quiz-maker'); ?></span>
                                        </div>
                                        <div class="ays_quiz_dropdown_max_width ays-display-flex" style="align-items: flex-start;">
                                            <input type="text" value="px" class='ays-quiz-form-hint-for-size' disabled>
                                        </div>
                                    </div>
                                </div><!-- Button width -->
                                <hr>
                                <div class="form-group row">
                                    <div class="col-sm-5">
                                        <label for="ays_buttons_padding">
                                            <?php echo __('Button padding','quiz-maker')?>
                                            <a class="ays_help" data-toggle="tooltip" title="<?php echo __('Padding of buttons.','quiz-maker')?>">
                                                <i class="ays_fa ays_fa_info_circle"></i>
                                            </a>
                                        </label>
                                    </div>
                                    <div class="col-sm-7 ays_divider_left">
                                        <div class="col-sm-5" style="display: inline-block; padding-left: 0;">
                                            <span class="ays_quiz_small_hint_text"><?php echo __('Left / Right','quiz-maker')?></span>
                                            <input type="number" class="ays-text-input" id='ays_buttons_left_right_padding' name='ays_buttons_left_right_padding' value="<?php echo $buttons_left_right_padding; ?>" style="width: 100px;" />
                                        </div>
                                        <div class="col-sm-5 ays_divider_left ays-buttons-top-bottom-padding-box" style="display: inline-block;">
                                            <span class="ays_quiz_small_hint_text"><?php echo __('Top / Bottom','quiz-maker')?></span>
                                            <input type="number" class="ays-text-input" id='ays_buttons_top_bottom_padding' name='ays_buttons_top_bottom_padding' value="<?php echo $buttons_top_bottom_padding; ?>" style="width: 100px;" />
                                        </div>
                                    </div>
                                </div><!-- Buttons padding -->
                                <hr/>
                                <div class="form-group row">
                                    <div class="col-sm-5">
                                        <label for="ays_buttons_border_radius">
                                            <?php echo __('Button border-radius','quiz-maker')?>
                                            <a class="ays_help" data-toggle="tooltip" title="<?php echo __('Quiz buttons border-radius in pixels. It accepts only numeric values.','quiz-maker')?>">
                                                <i class="ays_fa ays_fa_info_circle"></i>
                                            </a>
                                        </label>
                                    </div>
                                    <div class="col-sm-7 ays_divider_left ays_quiz_display_flex_width">
                                        <div>
                                            <input type="number" class="ays-text-input ays-text-input-short" id="ays_buttons_border_radius" name="ays_buttons_border_radius" value="<?php echo $buttons_border_radius; ?>"/>
                                        </div>
                                        <div class="ays_quiz_dropdown_max_width ays-display-flex" style="align-items: flex-start;">
                                            <input type="text" value="px" class='ays-quiz-form-hint-for-size' disabled>
                                        </div>
                                    </div>
                                </div><!-- Buttons border radius -->
                                <hr/>
                            </div>
                            <!-- <hr/> -->
                            <div class="col-lg-5 col-sm-12 ays_divider_left" style="position:relative;">
                                <div id="ays_buttons_styles_tab" style="position:sticky;top:50px; margin:auto;">
                                    <div class="ays_buttons_div" style="justify-content: center; overflow: hidden;">
                                        <input type="button" name="next" class="action-button ays-quiz-live-button" style="padding:0;" value="<?php echo __( "Start", 'quiz-maker' ); ?>">
                                    </div>
                                </div>
                            </div><!-- Buttons Styles Live -->
                        </div><!-- Buttons Styles End -->
                    </div>
                </div>
                <div class="ays-quiz-accordion-options-main-container" data-collapsed="false">
                    <div class="ays-quiz-accordion-container">
                        <?php echo $quiz_accordion_svg_html; ?>
                        <p class="ays-subtitle" style="margin-top:0;"><?php echo __('Admin note styles','quiz-maker'); ?></p>
                    </div>
                    <hr class="ays-quiz-bolder-hr"/>
                    <div class="ays-quiz-accordion-options-box">
                        <div class="form-group row"> <!-- Admin note Styles -->
                            <div class="col-lg-7 col-sm-12">
                                <div class="form-group row">
                                    <div class="col-sm-5">
                                        <label for="ays_note_text_font_size">
                                            <?php echo __('Font size for the note text','quiz-maker')?>
                                            <a class="ays_help" data-toggle="tooltip" title="<?php echo __('Choose the Font Size for the Message displayed for the note text( only for <p> tag ).','quiz-maker')?>">
                                                <i class="ays_fa ays_fa_info_circle"></i>
                                            </a>
                                        </label>
                                    </div>
                                    <div class="col-sm-7 ays_divider_left">
                                        <div class="row">
                                            <div class="col-sm-5">
                                                <label for='ays_note_text_font_size'>
                                                    <?php echo __('On desktop', 'quiz-maker'); ?>
                                                    <a class="ays_help" data-toggle="tooltip" title="<?php echo __('Define the font size for desktop devices.','quiz-maker'); ?>">
                                                        <i class="ays_fa ays_fa_info_circle"></i>
                                                    </a>
                                                </label>
                                            </div>
                                            <div class="col-sm-7 ays_quiz_display_flex_width">
                                                <div>
                                                    <input type="number" class="ays-text-input" id='ays_note_text_font_size' name='ays_note_text_font_size' value="<?php echo $note_text_font_size; ?>"/>
                                                </div>
                                                <div class="ays_quiz_dropdown_max_width ays-display-flex" style="align-items: end;">
                                                    <input type="text" value="px" class='ays-quiz-form-hint-for-size' disabled>
                                                </div>
                                            </div>
                                        </div>
                                        <hr>
                                        <div class="row">
                                            <div class="col-sm-5">
                                                <label for='ays_note_text_mobile_font_size'>
                                                    <?php echo __('On mobile', 'quiz-maker'); ?>
                                                    <a class="ays_help" data-toggle="tooltip" title="<?php echo __('Define the font size for mobile devices.','quiz-maker'); ?>">
                                                        <i class="ays_fa ays_fa_info_circle"></i>
                                                    </a>
                                                </label>
                                            </div>
                                            <div class="col-sm-7 ays_quiz_display_flex_width">
                                                <div>
                                                    <input type="number" class="ays-text-input" id='ays_note_text_mobile_font_size' name='ays_note_text_mobile_font_size' value="<?php echo $note_text_mobile_font_size; ?>"/>
                                                </div>
                                                <div class="ays_quiz_dropdown_max_width ays-display-flex" style="align-items: end;">
                                                    <input type="text" value="px" class='ays-quiz-form-hint-for-size' disabled>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div><!-- Font size for the note text -->
                                <hr/>
                                <div class="form-group row">
                                    <div class="col-sm-5">
                                        <label for="ays_quiz_admin_note_text_transform">
                                            <?php echo esc_html__('Text transformation','quiz-maker'); ?>
                                            <a class="ays_help" data-toggle="tooltip" title="<?php echo esc_attr( __('Specify how the text appears in all-uppercase or all-lowercase, or with each word capitalized.','quiz-maker') ); ?>">
                                                <i class="ays_fa ays_fa_info_circle"></i>
                                            </a>
                                        </label>
                                    </div>
                                    <div class="col-sm-7 ays_divider_left">
                                        <div class="row">
                                            <div class="col-sm-5">
                                                <label for='ays_quiz_admin_note_text_transform'>
                                                    <?php echo esc_html__('On desktop', 'quiz-maker'); ?>
                                                    <a class="ays_help" data-toggle="tooltip" title="<?php echo esc_attr( __('Define the text transformation for desktop devices.','quiz-maker') ); ?>">
                                                        <i class="ays_fa ays_fa_info_circle"></i>
                                                    </a>
                                                </label>
                                            </div>
                                            <div class="col-sm-7 ays_quiz_display_flex_width">
                                                <select class="ays-text-input ays-text-input-short" id="ays_quiz_admin_note_text_transform" name="ays_quiz_admin_note_text_transform">
                                                    <option value="none" <?php echo ($quiz_admin_note_text_transform == 'none') ? 'selected' : ''; ?>>
                                                        <?php echo esc_html__('None','quiz-maker'); ?>
                                                    </option>
                                                    <option value="capitalize" <?php echo ($quiz_admin_note_text_transform == 'capitalize') ? 'selected' : ''; ?>>
                                                        <?php echo esc_html__('Capitalize','quiz-maker'); ?>
                                                    </option>
                                                    <option value="uppercase" <?php echo ($quiz_admin_note_text_transform == 'uppercase')  ? 'selected' : ''; ?>>
                                                        <?php echo esc_html__('Uppercase','quiz-maker'); ?>
                                                    </option>
                                                    <option value="lowercase" <?php echo ($quiz_admin_note_text_transform == 'lowercase') ? 'selected' : ''; ?>>
                                                        <?php echo esc_html__('Lowercase','quiz-maker'); ?>
                                                    </option>
                                                </select>
                                            </div>
                                        </div>
                                        <hr>
                                        <div class="row">
                                            <div class="col-sm-5">
                                                <label for='ays_quiz_admin_note_mobile_text_transform'>
                                                    <?php echo esc_html__('On mobile', 'quiz-maker'); ?>
                                                    <a class="ays_help" data-toggle="tooltip" title="<?php echo esc_attr(__('Define the text transformation for mobile devices.','quiz-maker')); ?>">
                                                        <i class="ays_fa ays_fa_info_circle"></i>
                                                    </a>
                                                </label>
                                            </div>
                                            <div class="col-sm-7 ays_quiz_display_flex_width">
                                                <select class="ays-text-input ays-text-input-short" id="ays_quiz_admin_note_mobile_text_transform" name="ays_quiz_admin_note_mobile_text_transform">
                                                    <option value="none" <?php echo ($quiz_admin_note_mobile_text_transform == 'none') ? 'selected' : ''; ?>>
                                                        <?php echo esc_html__('None','quiz-maker'); ?>
                                                    </option>
                                                    <option value="capitalize" <?php echo ($quiz_admin_note_mobile_text_transform == 'capitalize') ? 'selected' : ''; ?>>
                                                        <?php echo esc_html__('Capitalize','quiz-maker'); ?>
                                                    </option>
                                                    <option value="uppercase" <?php echo ($quiz_admin_note_mobile_text_transform == 'uppercase')  ? 'selected' : ''; ?>>
                                                        <?php echo esc_html__('Uppercase','quiz-maker'); ?>
                                                    </option>
                                                    <option value="lowercase" <?php echo ($quiz_admin_note_mobile_text_transform == 'lowercase') ? 'selected' : ''; ?>>
                                                        <?php echo esc_html__('Lowercase','quiz-maker'); ?>
                                                    </option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div><!-- Admin note text transform -->
                                <hr/>
                                <div class="form-group row">
                                    <div class="col-sm-5">
                                        <label for="ays_quiz_admin_note_text_decoration">
                                            <?php echo __('Text decoration','quiz-maker'); ?>
                                            <a class="ays_help" data-toggle="tooltip" title="<?php echo __('Choose the line position for the Note Text on the front end. Note: It is set as None by default.','quiz-maker'); ?>">
                                                <i class="ays_fa ays_fa_info_circle"></i>
                                            </a>
                                        </label>
                                    </div>
                                    <div class="col-sm-7 ays_divider_left">
                                        <div class="row">
                                            <div class="col-sm-5">
                                                <label for='ays_quiz_admin_note_text_transform'>
                                                    <?php echo esc_html__('On desktop', 'quiz-maker'); ?>
                                                    <a class="ays_help" data-toggle="tooltip" title="<?php echo esc_attr( __('Choose the line position for the Note Text on the front end for desktop devices. Note: It is set as None by default.','quiz-maker') ); ?>">
                                                        <i class="ays_fa ays_fa_info_circle"></i>
                                                    </a>
                                                </label>
                                            </div>
                                            <div class="col-sm-7 ays_quiz_display_flex_width">
                                                <select class="ays-text-input ays-text-input-short" id="ays_quiz_admin_note_text_decoration" name="ays_quiz_admin_note_text_decoration">
                                                    <option value="none" <?php echo ($quiz_admin_note_text_decoration == 'none') ? 'selected' : ''; ?>>
                                                        <?php echo __('None','quiz-maker'); ?>
                                                    </option>
                                                    <option value="overline" <?php echo ($quiz_admin_note_text_decoration == 'overline') ? 'selected' : ''; ?>>
                                                        <?php echo __('Overline','quiz-maker'); ?>
                                                    </option>
                                                    <option value="line-through" <?php echo ($quiz_admin_note_text_decoration == 'line-through')  ? 'selected' : ''; ?>>
                                                        <?php echo __('Line through','quiz-maker'); ?>
                                                    </option>
                                                    <option value="underline" <?php echo ($quiz_admin_note_text_decoration == 'underline') ? 'selected' : ''; ?>>
                                                        <?php echo __('Underline','quiz-maker'); ?>
                                                    </option>
                                                </select>
                                            </div>
                                        </div>
                                        <hr>
                                        <div class="row">
                                            <div class="col-sm-5">
                                                <label for='ays_quiz_admin_note_mobile_text_decoration'>
                                                    <?php echo esc_html__('On mobile', 'quiz-maker'); ?>
                                                    <a class="ays_help" data-toggle="tooltip" title="<?php echo esc_attr(__('Choose the line position for the Note Text on the front end  for mobile devices. Note: It is set as None by default.','quiz-maker')); ?>">
                                                        <i class="ays_fa ays_fa_info_circle"></i>
                                                    </a>
                                                </label>
                                            </div>
                                            <div class="col-sm-7 ays_quiz_display_flex_width">
                                                <select class="ays-text-input ays-text-input-short" id="ays_quiz_admin_note_mobile_text_decoration" name="ays_quiz_admin_note_mobile_text_decoration">
                                                    <option value="none" <?php echo ($quiz_admin_note_mobile_text_decoration == 'none') ? 'selected' : ''; ?>>
                                                        <?php echo __('None','quiz-maker'); ?>
                                                    </option>
                                                    <option value="overline" <?php echo ($quiz_admin_note_mobile_text_decoration == 'overline') ? 'selected' : ''; ?>>
                                                        <?php echo __('Overline','quiz-maker'); ?>
                                                    </option>
                                                    <option value="line-through" <?php echo ($quiz_admin_note_mobile_text_decoration == 'line-through')  ? 'selected' : ''; ?>>
                                                        <?php echo __('Line through','quiz-maker'); ?>
                                                    </option>
                                                    <option value="underline" <?php echo ($quiz_admin_note_mobile_text_decoration == 'underline') ? 'selected' : ''; ?>>
                                                        <?php echo __('Underline','quiz-maker'); ?>
                                                    </option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div><!-- Admin note text decoration -->
                                <hr/>
                                <div class="form-group row">
                                    <div class="col-sm-5">
                                        <label for="ays_quiz_admin_note_letter_spacing">
                                            <?php echo esc_html__('Letter spacing','quiz-maker'); ?>
                                            <a class="ays_help" data-toggle="tooltip" title="<?php echo esc_attr__('Define the space between the letters of the admin note text in pixels. Note: The default value for this option is 0.','quiz-maker'); ?>">
                                                <i class="ays_fa ays_fa_info_circle"></i>
                                            </a>
                                        </label>
                                    </div>
                                    <div class="col-sm-7 ays_divider_left">
                                        <div class="row">
                                            <div class="col-sm-5">
                                                <label for='ays_quiz_admin_note_text_transform'>
                                                    <?php echo esc_html__('On desktop', 'quiz-maker'); ?>
                                                    <a class="ays_help" data-toggle="tooltip" title="<?php echo esc_attr( __('Choose the letter spacing for the Note Text on the front end for desktop devices. Note: It is set as Empty (0px) by default.','quiz-maker') ); ?>">
                                                        <i class="ays_fa ays_fa_info_circle"></i>
                                                    </a>
                                                </label>
                                            </div>
                                            <div class="col-sm-7 ays_quiz_display_flex_width">
                                                <div>
                                                    <input type="number" class="ays-text-input" id="ays_quiz_admin_note_letter_spacing" name="ays_quiz_admin_note_letter_spacing" value="<?php echo intval($quiz_admin_note_letter_spacing); ?>"/>
                                                </div>
                                                <div class="ays_quiz_dropdown_max_width ays-display-flex" style="align-items: flex-start;">
                                                    <input type="text" value="px" class='ays-quiz-form-hint-for-size' disabled>
                                                </div>
                                            </div>
                                        </div>
                                        <hr>
                                        <div class="row">
                                            <div class="col-sm-5">
                                                <label for='ays_quiz_admin_note_mobile_text_decoration'>
                                                    <?php echo esc_html__('On mobile', 'quiz-maker'); ?>
                                                    <a class="ays_help" data-toggle="tooltip" title="<?php echo esc_attr(__('Choose the letter spacing for the Note Text on the front end for mobile devices. Note: It is set as Empty (0px) by default.','quiz-maker')); ?>">
                                                        <i class="ays_fa ays_fa_info_circle"></i>
                                                    </a>
                                                </label>
                                            </div>
                                            <div class="col-sm-7 ays_quiz_display_flex_width">
                                                <div>
                                                    <input type="number" class="ays-text-input" id="ays_quiz_admin_note_mobile_letter_spacing" name="ays_quiz_admin_note_mobile_letter_spacing" value="<?php echo intval($quiz_admin_note_mobile_letter_spacing); ?>"/>
                                                </div>
                                                <div class="ays_quiz_dropdown_max_width ays-display-flex" style="align-items: flex-start;">
                                                    <input type="text" value="px" class='ays-quiz-form-hint-for-size' disabled>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div><!-- Letter spacing -->
                                <hr/>
                                <div class="form-group row">
                                    <div class="col-sm-5">
                                        <label for="ays_quiz_admin_note_font_weight">
                                            <?php echo __('Font weight','quiz-maker'); ?>
                                            <a class="ays_help" data-toggle="tooltip" title="<?php echo __('Specify the font weight for the Admin Note. Note: By default, it is set as Normal.','quiz-maker'); ?>">
                                                <i class="ays_fa ays_fa_info_circle"></i>
                                            </a>
                                        </label>
                                    </div>
                                    <div class="col-sm-7 ays_divider_left">
                                        <select class="ays-text-input ays-text-input-short" id="ays_quiz_admin_note_font_weight" name="ays_quiz_admin_note_font_weight">
                                            <option value="normal" <?php echo ($quiz_admin_note_font_weight == 'normal') ? 'selected' : ''; ?>>
                                                <?php echo __('Normal','quiz-maker'); ?>
                                            </option>
                                            <option value="lighter" <?php echo ($quiz_admin_note_font_weight == 'lighter') ? 'selected' : ''; ?>>
                                                <?php echo __('Lighter','quiz-maker'); ?>
                                            </option>
                                            <option value="bold" <?php echo ($quiz_admin_note_font_weight == 'bold')  ? 'selected' : ''; ?>>
                                                <?php echo __('Bold','quiz-maker'); ?>
                                            </option>
                                            <option value="bolder" <?php echo ($quiz_admin_note_font_weight == 'bolder') ? 'selected' : ''; ?>>
                                                <?php echo __('Bolder','quiz-maker'); ?>
                                            </option>
                                            <option value="100" <?php echo ($quiz_admin_note_font_weight == '100') ? 'selected' : ''; ?>>
                                                <?php echo '100'; ?>
                                            </option>
                                            <option value="200" <?php echo ($quiz_admin_note_font_weight == '200') ? 'selected' : ''; ?>>
                                                <?php echo '200'; ?>
                                            </option>
                                            <option value="300" <?php echo ($quiz_admin_note_font_weight == '300') ? 'selected' : ''; ?>>
                                                <?php echo '300'; ?>
                                            </option>
                                            <option value="400" <?php echo ($quiz_admin_note_font_weight == '400') ? 'selected' : ''; ?>>
                                                <?php echo '400'; ?>
                                            </option>
                                            <option value="500" <?php echo ($quiz_admin_note_font_weight == '500') ? 'selected' : ''; ?>>
                                                <?php echo '500'; ?>
                                            </option>
                                            <option value="600" <?php echo ($quiz_admin_note_font_weight == '600') ? 'selected' : ''; ?>>
                                                <?php echo '600'; ?>
                                            </option>
                                            <option value="700" <?php echo ($quiz_admin_note_font_weight == '700') ? 'selected' : ''; ?>>
                                                <?php echo '700'; ?>
                                            </option>
                                            <option value="800" <?php echo ($quiz_admin_note_font_weight == '800') ? 'selected' : ''; ?>>
                                                <?php echo '800'; ?>
                                            </option>
                                            <option value="900" <?php echo ($quiz_admin_note_font_weight == '900') ? 'selected' : ''; ?>>
                                                <?php echo '900'; ?>
                                            </option>
                                        </select>
                                    </div>
                                </div><!-- Admin note font weight -->
                            </div>
                            <hr/>
                            <div class="col-lg-5 col-sm-12 ays_divider_left" style="position:relative;"></div>
                        </div> <!-- Admin note Styles End -->
                    </div>
                </div>
                <!-- <hr/> -->
                <div class="ays-quiz-accordion-options-main-container" data-collapsed="false">
                    <div class="ays-quiz-accordion-container">
                        <?php echo $quiz_accordion_svg_html; ?>
                        <p class="ays-subtitle" style="margin-top:0;"><?php echo __('Question explanation styles','quiz-maker'); ?></p>
                    </div>
                    <hr class="ays-quiz-bolder-hr"/>
                    <div class="ays-quiz-accordion-options-box">
                        <div class="form-group row"> <!-- Question explanation styles -->
                            <div class="col-lg-7 col-sm-12">
                                <div class="form-group row">
                                    <div class="col-sm-5">
                                        <label for="ays_quest_explanation_font_size">
                                            <?php echo __('Font size for the question explanation','quiz-maker')?>
                                            <a class="ays_help" data-toggle="tooltip" title="<?php echo __('Choose the Font Size for the question explanation text( only for <p> tag ).','quiz-maker')?>">
                                                <i class="ays_fa ays_fa_info_circle"></i>
                                            </a>
                                        </label>
                                    </div>
                                    <div class="col-sm-7 ays_divider_left">
                                        <div class="row">
                                            <div class="col-sm-5">
                                                <label for='ays_question_font_size'>
                                                    <?php echo __('On desktop', 'quiz-maker'); ?>
                                                    <a class="ays_help" data-toggle="tooltip" title="<?php echo __('Define the font size for desktop devices.','quiz-maker'); ?>">
                                                        <i class="ays_fa ays_fa_info_circle"></i>
                                                    </a>
                                                </label>
                                            </div>
                                            <div class="col-sm-7 ays_quiz_display_flex_width">
                                                <div>
                                                    <input type="number" class="ays-text-input" id='ays_quest_explanation_font_size' name='ays_quest_explanation_font_size' value="<?php echo $quest_explanation_font_size; ?>"/>
                                                </div>
                                                <div class="ays_quiz_dropdown_max_width ays-display-flex" style="align-items: end;">
                                                    <input type="text" value="px" class='ays-quiz-form-hint-for-size' disabled>
                                                </div>
                                            </div>
                                        </div>
                                        <hr>
                                        <div class="row">
                                            <div class="col-sm-5">
                                                <label for='ays_quest_explanation_mobile_font_size'>
                                                    <?php echo __('On mobile', 'quiz-maker'); ?>
                                                    <a class="ays_help" data-toggle="tooltip" title="<?php echo __('Define the font size for mobile devices.','quiz-maker'); ?>">
                                                        <i class="ays_fa ays_fa_info_circle"></i>
                                                    </a>
                                                </label>
                                            </div>
                                            <div class="col-sm-7 ays_quiz_display_flex_width">
                                                <div>
                                                    <input type="number" class="ays-text-input" id='ays_quest_explanation_mobile_font_size' name='ays_quest_explanation_mobile_font_size' value="<?php echo $quest_explanation_mobile_font_size; ?>"/>
                                                </div>
                                                <div class="ays_quiz_dropdown_max_width ays-display-flex" style="align-items: end;">
                                                    <input type="text" value="px" class='ays-quiz-form-hint-for-size' disabled>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div><!-- Font size for the question explanation -->
                                <hr/>
                                <div class="form-group row">
                                    <div class="col-sm-5">
                                        <label for="ays_quiz_quest_explanation_text_transform">
                                            <?php echo __('Text transformation','quiz-maker'); ?>
                                            <a class="ays_help" data-toggle="tooltip" title="<?php echo __('Specify how the text appears in all-uppercase or all-lowercase, or with each word capitalized.','quiz-maker'); ?>">
                                                <i class="ays_fa ays_fa_info_circle"></i>
                                            </a>
                                        </label>
                                    </div>
                                    <div class="col-sm-7 ays_divider_left">
                                        <div class="row">
                                            <div class="col-sm-5">
                                                <label for='ays_quiz_quest_explanation_text_transform'>
                                                    <?php echo esc_html__('On desktop', 'quiz-maker'); ?>
                                                    <a class="ays_help" data-toggle="tooltip" title="<?php echo esc_attr( __('Define the text transformation for desktop devices.','quiz-maker') ); ?>">
                                                        <i class="ays_fa ays_fa_info_circle"></i>
                                                    </a>
                                                </label>
                                            </div>
                                            <div class="col-sm-7 ays_quiz_display_flex_width">
                                                <select class="ays-text-input ays-text-input-short" id="ays_quiz_quest_explanation_text_transform" name="ays_quiz_quest_explanation_text_transform">
                                                    <option value="none" <?php echo ($quiz_quest_explanation_text_transform == 'none') ? 'selected' : ''; ?>>
                                                        <?php echo __('None','quiz-maker'); ?>
                                                    </option>
                                                    <option value="capitalize" <?php echo ($quiz_quest_explanation_text_transform == 'capitalize') ? 'selected' : ''; ?>>
                                                        <?php echo __('Capitalize','quiz-maker'); ?>
                                                    </option>
                                                    <option value="uppercase" <?php echo ($quiz_quest_explanation_text_transform == 'uppercase')  ? 'selected' : ''; ?>>
                                                        <?php echo __('Uppercase','quiz-maker'); ?>
                                                    </option>
                                                    <option value="lowercase" <?php echo ($quiz_quest_explanation_text_transform == 'lowercase') ? 'selected' : ''; ?>>
                                                        <?php echo __('Lowercase','quiz-maker'); ?>
                                                    </option>
                                                </select>
                                            </div>
                                        </div>
                                        <hr>
                                        <div class="row">
                                            <div class="col-sm-5">
                                                <label for='ays_quiz_quest_explanation_mobile_text_transform'>
                                                    <?php echo esc_html__('On mobile', 'quiz-maker'); ?>
                                                    <a class="ays_help" data-toggle="tooltip" title="<?php echo esc_attr(__('Define the text transformation for mobile devices.','quiz-maker')); ?>">
                                                        <i class="ays_fa ays_fa_info_circle"></i>
                                                    </a>
                                                </label>
                                            </div>
                                            <div class="col-sm-7 ays_quiz_display_flex_width">
                                                <select class="ays-text-input ays-text-input-short" id="ays_quiz_quest_explanation_mobile_text_transform" name="ays_quiz_quest_explanation_mobile_text_transform">
                                                    <option value="none" <?php echo ($quiz_quest_explanation_mobile_text_transform == 'none') ? 'selected' : ''; ?>>
                                                        <?php echo __('None','quiz-maker'); ?>
                                                    </option>
                                                    <option value="capitalize" <?php echo ($quiz_quest_explanation_mobile_text_transform == 'capitalize') ? 'selected' : ''; ?>>
                                                        <?php echo __('Capitalize','quiz-maker'); ?>
                                                    </option>
                                                    <option value="uppercase" <?php echo ($quiz_quest_explanation_mobile_text_transform == 'uppercase')  ? 'selected' : ''; ?>>
                                                        <?php echo __('Uppercase','quiz-maker'); ?>
                                                    </option>
                                                    <option value="lowercase" <?php echo ($quiz_quest_explanation_mobile_text_transform == 'lowercase') ? 'selected' : ''; ?>>
                                                        <?php echo __('Lowercase','quiz-maker'); ?>
                                                    </option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div><!-- Question explanation text transform -->
                                <hr/>
                                <div class="form-group row">
                                    <div class="col-sm-5">
                                        <label for="ays_quiz_quest_explanation_text_decoration">
                                            <?php echo __('Text decoration','quiz-maker'); ?>
                                            <a class="ays_help" data-toggle="tooltip" title="<?php echo __('Choose the line position for the Question Explanation on the front end. Note: It is set as None by default.','quiz-maker'); ?>">
                                                <i class="ays_fa ays_fa_info_circle"></i>
                                            </a>
                                        </label>
                                    </div>
                                    <div class="col-sm-7 ays_divider_left">
                                        <div class="row">
                                            <div class="col-sm-5">
                                                <label for='ays_quiz_admin_note_text_transform'>
                                                    <?php echo esc_html__('On desktop', 'quiz-maker'); ?>
                                                    <a class="ays_help" data-toggle="tooltip" title="<?php echo esc_attr( __('Choose the line position for the Note Text on the front end for desktop devices. Note: It is set as None by default.','quiz-maker') ); ?>">
                                                        <i class="ays_fa ays_fa_info_circle"></i>
                                                    </a>
                                                </label>
                                            </div>
                                            <div class="col-sm-7 ays_quiz_display_flex_width">
                                                <select class="ays-text-input ays-text-input-short" id="ays_quiz_quest_explanation_text_decoration" name="ays_quiz_quest_explanation_text_decoration">
                                                    <option value="none" <?php echo ($quiz_quest_explanation_text_decoration == 'none') ? 'selected' : ''; ?>>
                                                        <?php echo esc_html__('None','quiz-maker'); ?>
                                                    </option>
                                                    <option value="overline" <?php echo ($quiz_quest_explanation_text_decoration == 'overline') ? 'selected' : ''; ?>>
                                                        <?php echo esc_html__('Overline','quiz-maker'); ?>
                                                    </option>
                                                    <option value="line-through" <?php echo ($quiz_quest_explanation_text_decoration == 'line-through')  ? 'selected' : ''; ?>>
                                                        <?php echo esc_html__('Line through','quiz-maker'); ?>
                                                    </option>
                                                    <option value="underline" <?php echo ($quiz_quest_explanation_text_decoration == 'underline') ? 'selected' : ''; ?>>
                                                        <?php echo esc_html__('Underline','quiz-maker'); ?>
                                                    </option>
                                                </select>
                                            </div>
                                        </div>
                                        <hr>
                                        <div class="row">
                                            <div class="col-sm-5">
                                                <label for='ays_quiz_quest_explanation_mobile_text_decoration'>
                                                    <?php echo esc_html__('On mobile', 'quiz-maker'); ?>
                                                    <a class="ays_help" data-toggle="tooltip" title="<?php echo esc_attr(__('Choose the line position for the Note Text on the front end  for mobile devices. Note: It is set as None by default.','quiz-maker')); ?>">
                                                        <i class="ays_fa ays_fa_info_circle"></i>
                                                    </a>
                                                </label>
                                            </div>
                                            <div class="col-sm-7 ays_quiz_display_flex_width">
                                                <select class="ays-text-input ays-text-input-short" id="ays_quiz_quest_explanation_mobile_text_decoration" name="ays_quiz_quest_explanation_mobile_text_decoration">
                                                    <option value="none" <?php echo ($quiz_quest_explanation_mobile_text_decoration == 'none') ? 'selected' : ''; ?>>
                                                        <?php echo esc_html__('None','quiz-maker'); ?>
                                                    </option>
                                                    <option value="overline" <?php echo ($quiz_quest_explanation_mobile_text_decoration == 'overline') ? 'selected' : ''; ?>>
                                                        <?php echo esc_html__('Overline','quiz-maker'); ?>
                                                    </option>
                                                    <option value="line-through" <?php echo ($quiz_quest_explanation_mobile_text_decoration == 'line-through')  ? 'selected' : ''; ?>>
                                                        <?php echo esc_html__('Line through','quiz-maker'); ?>
                                                    </option>
                                                    <option value="underline" <?php echo ($quiz_quest_explanation_mobile_text_decoration == 'underline') ? 'selected' : ''; ?>>
                                                        <?php echo esc_html__('Underline','quiz-maker'); ?>
                                                    </option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div><!-- Admin note text transform -->
                                <hr/>
                                <div class="form-group row">
                                    <div class="col-sm-5">
                                        <label for="ays_quiz_quest_explanation_letter_spacing">
                                            <?php echo __('Letter spacing','quiz-maker')?>
                                            <a class="ays_help" data-toggle="tooltip" title="<?php echo __('Define the space between the letters of the question explanation text in pixels. Note: The default value for this option is 0.','quiz-maker'); ?>">
                                                <i class="ays_fa ays_fa_info_circle"></i>
                                            </a>
                                        </label>
                                    </div>
                                    <div class="col-sm-7 ays_divider_left">
                                        <div class="row">
                                            <div class="col-sm-5">
                                                <label for='ays_quiz_admin_note_text_transform'>
                                                    <?php echo esc_html__('On desktop', 'quiz-maker'); ?>
                                                    <a class="ays_help" data-toggle="tooltip" title="<?php echo esc_attr( __('Choose the letter spacing for the Question Explanation Text on the front end for desktop devices. Note: It is set as Empty (0px) by default.','quiz-maker') ); ?>">
                                                        <i class="ays_fa ays_fa_info_circle"></i>
                                                    </a>
                                                </label>
                                            </div>
                                            <div class="col-sm-7 ays_quiz_display_flex_width">
                                                <div>
                                                    <input type="number" class="ays-text-input" id="ays_quiz_quest_explanation_letter_spacing" name="ays_quiz_quest_explanation_letter_spacing" value="<?php echo intval($quiz_quest_explanation_letter_spacing); ?>"/>
                                                </div>
                                                <div class="ays_quiz_dropdown_max_width ays-display-flex" style="align-items: flex-start;">
                                                    <input type="text" value="px" class='ays-quiz-form-hint-for-size' disabled>
                                                </div>
                                            </div>
                                        </div>
                                        <hr>
                                        <div class="row">
                                            <div class="col-sm-5">
                                                <label for='ays_quiz_quest_explanation_mobile_letter_spacing'>
                                                    <?php echo esc_html__('On mobile', 'quiz-maker'); ?>
                                                    <a class="ays_help" data-toggle="tooltip" title="<?php echo esc_attr(__('Choose the letter spacing for the Question Explanation Text on the front end for mobile devices. Note: It is set as Empty (0px) by default.','quiz-maker')); ?>">
                                                        <i class="ays_fa ays_fa_info_circle"></i>
                                                    </a>
                                                </label>
                                            </div>
                                            <div class="col-sm-7 ays_quiz_display_flex_width">
                                                <div>
                                                    <input type="number" class="ays-text-input" id="ays_quiz_quest_explanation_mobile_letter_spacing" name="ays_quiz_quest_explanation_mobile_letter_spacing" value="<?php echo intval($quiz_quest_explanation_mobile_letter_spacing); ?>"/>
                                                </div>
                                                <div class="ays_quiz_dropdown_max_width ays-display-flex" style="align-items: flex-start;">
                                                    <input type="text" value="px" class='ays-quiz-form-hint-for-size' disabled>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div><!-- Letter spacing -->
                                <hr/>
                                <div class="form-group row">
                                    <div class="col-sm-5">
                                        <label for="ays_quiz_quest_explanation_font_weight">
                                            <?php echo __('Font weight','quiz-maker'); ?>
                                            <a class="ays_help" data-toggle="tooltip" title="<?php echo __('Specify the font weight for the Question Explanation. Note: By default, it is set as Normal.','quiz-maker'); ?>">
                                                <i class="ays_fa ays_fa_info_circle"></i>
                                            </a>
                                        </label>
                                    </div>
                                    <div class="col-sm-7 ays_divider_left">
                                        <select class="ays-text-input ays-text-input-short" id="ays_quiz_quest_explanation_font_weight" name="ays_quiz_quest_explanation_font_weight">
                                            <option value="normal" <?php echo ($quiz_quest_explanation_font_weight == 'normal') ? 'selected' : ''; ?>>
                                                <?php echo __('Normal','quiz-maker'); ?>
                                            </option>
                                            <option value="lighter" <?php echo ($quiz_quest_explanation_font_weight == 'lighter') ? 'selected' : ''; ?>>
                                                <?php echo __('Lighter','quiz-maker'); ?>
                                            </option>
                                            <option value="bold" <?php echo ($quiz_quest_explanation_font_weight == 'bold')  ? 'selected' : ''; ?>>
                                                <?php echo __('Bold','quiz-maker'); ?>
                                            </option>
                                            <option value="bolder" <?php echo ($quiz_quest_explanation_font_weight == 'bolder') ? 'selected' : ''; ?>>
                                                <?php echo __('Bolder','quiz-maker'); ?>
                                            </option>
                                            <option value="100" <?php echo ($quiz_quest_explanation_font_weight == '100') ? 'selected' : ''; ?>>
                                                <?php echo '100'; ?>
                                            </option>
                                            <option value="200" <?php echo ($quiz_quest_explanation_font_weight == '200') ? 'selected' : ''; ?>>
                                                <?php echo '200'; ?>
                                            </option>
                                            <option value="300" <?php echo ($quiz_quest_explanation_font_weight == '300') ? 'selected' : ''; ?>>
                                                <?php echo '300'; ?>
                                            </option>
                                            <option value="400" <?php echo ($quiz_quest_explanation_font_weight == '400') ? 'selected' : ''; ?>>
                                                <?php echo '400'; ?>
                                            </option>
                                            <option value="500" <?php echo ($quiz_quest_explanation_font_weight == '500') ? 'selected' : ''; ?>>
                                                <?php echo '500'; ?>
                                            </option>
                                            <option value="600" <?php echo ($quiz_quest_explanation_font_weight == '600') ? 'selected' : ''; ?>>
                                                <?php echo '600'; ?>
                                            </option>
                                            <option value="700" <?php echo ($quiz_quest_explanation_font_weight == '700') ? 'selected' : ''; ?>>
                                                <?php echo '700'; ?>
                                            </option>
                                            <option value="800" <?php echo ($quiz_quest_explanation_font_weight == '800') ? 'selected' : ''; ?>>
                                                <?php echo '800'; ?>
                                            </option>
                                            <option value="900" <?php echo ($quiz_quest_explanation_font_weight == '900') ? 'selected' : ''; ?>>
                                                <?php echo '900'; ?>
                                            </option>
                                        </select>
                                    </div>
                                </div><!-- Question explanation font weight -->
                            </div>
                            <hr/>
                            <div class="col-lg-5 col-sm-12 ays_divider_left" style="position:relative;"></div>
                        </div> <!-- Question explanation styles End -->
                    </div>
                </div>
                <div class="ays-quiz-accordion-options-main-container" data-collapsed="false">
                    <div class="ays-quiz-accordion-container">
                        <?php echo $quiz_accordion_svg_html; ?>
                        <p class="ays-subtitle" style="margin-top:0;"><?php echo __('Right answer styles','quiz-maker'); ?></p>
                    </div>
                    <hr class="ays-quiz-bolder-hr"/>
                    <div class="ays-quiz-accordion-options-box">
                        <div class="form-group row"> <!-- Right answer styles -->
                            <div class="col-lg-7 col-sm-12">
                                <div class="form-group row">
                                    <div class="col-sm-5">
                                        <label for="ays_right_answers_font_size">
                                            <?php echo __('Font size for the right answer','quiz-maker')?>
                                            <a class="ays_help" data-toggle="tooltip" title="<?php echo __('Choose the Font Size for the Message displayed for the right answer( only for <p> tag ).','quiz-maker')?>">
                                                <i class="ays_fa ays_fa_info_circle"></i>
                                            </a>
                                        </label>
                                    </div>
                                    <div class="col-sm-7 ays_divider_left">
                                        <div class="row">
                                            <div class="col-sm-5">
                                                <label for='ays_right_answers_font_size'>
                                                    <?php echo __('On desktop', 'quiz-maker'); ?>
                                                    <a class="ays_help" data-toggle="tooltip" title="<?php echo __('Define the font size for desktop devices.','quiz-maker'); ?>">
                                                        <i class="ays_fa ays_fa_info_circle"></i>
                                                    </a>
                                                </label>
                                            </div>
                                            <div class="col-sm-7 ays_quiz_display_flex_width">
                                                <div>
                                                    <input type="number" class="ays-text-input" id='ays_right_answers_font_size' name='ays_right_answers_font_size' value="<?php echo $right_answers_font_size; ?>"/>
                                                </div>
                                                <div class="ays_quiz_dropdown_max_width ays-display-flex" style="align-items: end;">
                                                    <input type="text" value="px" class='ays-quiz-form-hint-for-size' disabled>
                                                </div>
                                            </div>
                                        </div>
                                        <hr>
                                        <div class="row">
                                            <div class="col-sm-5">
                                                <label for='ays_right_answers_mobile_font_size'>
                                                    <?php echo __('On mobile', 'quiz-maker'); ?>
                                                    <a class="ays_help" data-toggle="tooltip" title="<?php echo __('Define the font size for mobile devices.','quiz-maker'); ?>">
                                                        <i class="ays_fa ays_fa_info_circle"></i>
                                                    </a>
                                                </label>
                                            </div>
                                            <div class="col-sm-7 ays_quiz_display_flex_width">
                                                <div>
                                                    <input type="number" class="ays-text-input" id='ays_right_answers_mobile_font_size' name='ays_right_answers_mobile_font_size' value="<?php echo $right_answers_mobile_font_size; ?>"/>
                                                </div>
                                                <div class="ays_quiz_dropdown_max_width ays-display-flex" style="align-items: end;">
                                                    <input type="text" value="px" class='ays-quiz-form-hint-for-size' disabled>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div><!-- Font size for the right answer -->
                                <hr/>
                                <div class="form-group row">
                                    <div class="col-sm-5">
                                        <label for="ays_quiz_right_answer_text_transform">
                                            <?php echo __('Text transformation','quiz-maker'); ?>
                                            <a class="ays_help" data-toggle="tooltip" title="<?php echo __('Specify how the text appears in all-uppercase or all-lowercase, or with each word capitalized.','quiz-maker'); ?>">
                                                <i class="ays_fa ays_fa_info_circle"></i>
                                            </a>
                                        </label>
                                    </div>
                                    <div class="col-sm-7 ays_divider_left">
                                        <div class="row">
                                            <div class="col-sm-5">
                                                <label for='ays_quiz_right_answer_text_transform'>
                                                    <?php echo esc_html__('On desktop', 'quiz-maker'); ?>
                                                    <a class="ays_help" data-toggle="tooltip" title="<?php echo esc_attr( __('Define the text transformation for desktop devices.','quiz-maker') ); ?>">
                                                        <i class="ays_fa ays_fa_info_circle"></i>
                                                    </a>
                                                </label>
                                            </div>
                                            <div class="col-sm-7 ays_quiz_display_flex_width">
                                                <select class="ays-text-input ays-text-input-short" id="ays_quiz_right_answer_text_transform" name="ays_quiz_right_answer_text_transform">
                                                    <option value="none" <?php echo ($quiz_right_answer_text_transform == 'none') ? 'selected' : ''; ?>>
                                                        <?php echo esc_html__('None','quiz-maker'); ?>
                                                    </option>
                                                    <option value="capitalize" <?php echo ($quiz_right_answer_text_transform == 'capitalize') ? 'selected' : ''; ?>>
                                                        <?php echo esc_html__('Capitalize','quiz-maker'); ?>
                                                    </option>
                                                    <option value="uppercase" <?php echo ($quiz_right_answer_text_transform == 'uppercase')  ? 'selected' : ''; ?>>
                                                        <?php echo esc_html__('Uppercase','quiz-maker'); ?>
                                                    </option>
                                                    <option value="lowercase" <?php echo ($quiz_right_answer_text_transform == 'lowercase') ? 'selected' : ''; ?>>
                                                        <?php echo esc_html__('Lowercase','quiz-maker'); ?>
                                                    </option>
                                                </select>
                                            </div>
                                        </div>
                                        <hr>
                                        <div class="row">
                                            <div class="col-sm-5">
                                                <label for='ays_quiz_right_answer_mobile_text_transform'>
                                                    <?php echo esc_html__('On mobile', 'quiz-maker'); ?>
                                                    <a class="ays_help" data-toggle="tooltip" title="<?php echo esc_attr(__('Define the text transformation for mobile devices.','quiz-maker')); ?>">
                                                        <i class="ays_fa ays_fa_info_circle"></i>
                                                    </a>
                                                </label>
                                            </div>
                                            <div class="col-sm-7 ays_quiz_display_flex_width">
                                                <select class="ays-text-input ays-text-input-short" id="ays_quiz_right_answer_mobile_text_transform" name="ays_quiz_right_answer_mobile_text_transform">
                                                    <option value="none" <?php echo ($quiz_right_answer_mobile_text_transform == 'none') ? 'selected' : ''; ?>>
                                                        <?php echo esc_html__('None','quiz-maker'); ?>
                                                    </option>
                                                    <option value="capitalize" <?php echo ($quiz_right_answer_mobile_text_transform == 'capitalize') ? 'selected' : ''; ?>>
                                                        <?php echo esc_html__('Capitalize','quiz-maker'); ?>
                                                    </option>
                                                    <option value="uppercase" <?php echo ($quiz_right_answer_mobile_text_transform == 'uppercase')  ? 'selected' : ''; ?>>
                                                        <?php echo esc_html__('Uppercase','quiz-maker'); ?>
                                                    </option>
                                                    <option value="lowercase" <?php echo ($quiz_right_answer_mobile_text_transform == 'lowercase') ? 'selected' : ''; ?>>
                                                        <?php echo esc_html__('Lowercase','quiz-maker'); ?>
                                                    </option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div><!-- Right answer text transform -->
                                <hr/>
                                <div class="form-group row">
                                    <div class="col-sm-5">
                                        <label for="ays_quiz_right_answers_text_decoration">
                                            <?php echo __('Text decoration','quiz-maker'); ?>
                                            <a class="ays_help" data-toggle="tooltip" title="<?php echo __('Choose the line position for the Right answer on the front end. Note: It is set as None by default.','quiz-maker'); ?>">
                                                <i class="ays_fa ays_fa_info_circle"></i>
                                            </a>
                                        </label>
                                    </div>
                                    <div class="col-sm-7 ays_divider_left">
                                        <div class="row">
                                            <div class="col-sm-5">
                                                <label for='ays_quiz_right_answers_text_decoration'>
                                                    <?php echo esc_html__('On desktop', 'quiz-maker'); ?>
                                                    <a class="ays_help" data-toggle="tooltip" title="<?php echo esc_attr( __('Choose the line position for the Right answer Text on the front end for desktop devices. Note: It is set as None by default.','quiz-maker') ); ?>">
                                                        <i class="ays_fa ays_fa_info_circle"></i>
                                                    </a>
                                                </label>
                                            </div>
                                            <div class="col-sm-7 ays_quiz_display_flex_width">
                                                <select class="ays-text-input ays-text-input-short" id="ays_quiz_right_answers_text_decoration" name="ays_quiz_right_answers_text_decoration">
                                                    <option value="none" <?php echo ($quiz_right_answers_text_decoration == 'none') ? 'selected' : ''; ?>>
                                                        <?php echo __('None','quiz-maker'); ?>
                                                    </option>
                                                    <option value="overline" <?php echo ($quiz_right_answers_text_decoration == 'overline') ? 'selected' : ''; ?>>
                                                        <?php echo __('Overline','quiz-maker'); ?>
                                                    </option>
                                                    <option value="line-through" <?php echo ($quiz_right_answers_text_decoration == 'line-through')  ? 'selected' : ''; ?>>
                                                        <?php echo __('Line through','quiz-maker'); ?>
                                                    </option>
                                                    <option value="underline" <?php echo ($quiz_right_answers_text_decoration == 'underline') ? 'selected' : ''; ?>>
                                                        <?php echo __('Underline','quiz-maker'); ?>
                                                    </option>
                                                </select>
                                            </div>
                                        </div>
                                        <hr>
                                        <div class="row">
                                            <div class="col-sm-5">
                                                <label for='ays_quiz_right_answers_mobile_text_decoration'>
                                                    <?php echo esc_html__('On mobile', 'quiz-maker'); ?>
                                                    <a class="ays_help" data-toggle="tooltip" title="<?php echo esc_attr(__('Choose the line position for the Right answer Text on the front end for mobile devices. Note: It is set as None by default.','quiz-maker')); ?>">
                                                        <i class="ays_fa ays_fa_info_circle"></i>
                                                    </a>
                                                </label>
                                            </div>
                                            <div class="col-sm-7 ays_quiz_display_flex_width">
                                                <select class="ays-text-input ays-text-input-short" id="ays_quiz_right_answers_mobile_text_decoration" name="ays_quiz_right_answers_mobile_text_decoration">
                                                    <option value="none" <?php echo ($quiz_right_answers_mobile_text_decoration == 'none') ? 'selected' : ''; ?>>
                                                        <?php echo esc_html__('None','quiz-maker'); ?>
                                                    </option>
                                                    <option value="overline" <?php echo ($quiz_right_answers_mobile_text_decoration == 'overline') ? 'selected' : ''; ?>>
                                                        <?php echo esc_html__('Overline','quiz-maker'); ?>
                                                    </option>
                                                    <option value="line-through" <?php echo ($quiz_right_answers_mobile_text_decoration == 'line-through')  ? 'selected' : ''; ?>>
                                                        <?php echo esc_html__('Line through','quiz-maker'); ?>
                                                    </option>
                                                    <option value="underline" <?php echo ($quiz_right_answers_mobile_text_decoration == 'underline') ? 'selected' : ''; ?>>
                                                        <?php echo esc_html__('Underline','quiz-maker'); ?>
                                                    </option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div><!-- Right answer text decoration -->
                                <hr/>
                                <div class="form-group row">
                                    <div class="col-sm-5">
                                        <label for="ays_quiz_right_answers_letter_spacing">
                                            <?php echo __('Letter spacing','quiz-maker')?>
                                            <a class="ays_help" data-toggle="tooltip" title="<?php echo __('Define the space between the letters of the right answer text in pixels. Note: The default value for this option is 0.','quiz-maker'); ?>">
                                                <i class="ays_fa ays_fa_info_circle"></i>
                                            </a>
                                        </label>
                                    </div>
                                    <div class="col-sm-7 ays_divider_left">
                                        <div class="row">
                                            <div class="col-sm-5">
                                                <label for='ays_quiz_right_answers_letter_spacing'>
                                                    <?php echo esc_html__('On desktop', 'quiz-maker'); ?>
                                                    <a class="ays_help" data-toggle="tooltip" title="<?php echo esc_attr( __('Choose the letter spacing for the Right answer Text on the front end for desktop devices. Note: It is set as Empty (0px) by default.','quiz-maker') ); ?>">
                                                        <i class="ays_fa ays_fa_info_circle"></i>
                                                    </a>
                                                </label>
                                            </div>
                                            <div class="col-sm-7 ays_quiz_display_flex_width">
                                                <div>
                                                    <input type="number" class="ays-text-input ays-text-input-short" id="ays_quiz_right_answers_letter_spacing" name="ays_quiz_right_answers_letter_spacing" value="<?php echo intval($quiz_right_answers_letter_spacing); ?>"/>
                                                </div>
                                                <div class="ays_quiz_dropdown_max_width ays-display-flex" style="align-items: flex-start;">
                                                    <input type="text" value="px" class='ays-quiz-form-hint-for-size' disabled>
                                                </div>
                                            </div>
                                        </div>
                                        <hr>
                                        <div class="row">
                                            <div class="col-sm-5">
                                                <label for='ays_quiz_right_answers_mobile_letter_spacing'>
                                                    <?php echo esc_html__('On mobile', 'quiz-maker'); ?>
                                                    <a class="ays_help" data-toggle="tooltip" title="<?php echo esc_attr(__('Choose the letter spacing for the Right answer Text on the front end for mobile devices. Note: It is set as Empty (0px) by default.','quiz-maker')); ?>">
                                                        <i class="ays_fa ays_fa_info_circle"></i>
                                                    </a>
                                                </label>
                                            </div>
                                            <div class="col-sm-7 ays_quiz_display_flex_width">
                                                <div>
                                                    <input type="number" class="ays-text-input" id="ays_quiz_right_answers_mobile_letter_spacing" name="ays_quiz_right_answers_mobile_letter_spacing" value="<?php echo intval($quiz_right_answers_mobile_letter_spacing); ?>"/>
                                                </div>
                                                <div class="ays_quiz_dropdown_max_width ays-display-flex" style="align-items: flex-start;">
                                                    <input type="text" value="px" class='ays-quiz-form-hint-for-size' disabled>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div><!-- Letter spacing -->
                                <hr/>
                                <div class="form-group row">
                                    <div class="col-sm-5">
                                        <label for="ays_quiz_right_answers_font_weight">
                                            <?php echo __('Font weight','quiz-maker'); ?>
                                            <a class="ays_help" data-toggle="tooltip" title="<?php echo __('Specify the font weight for the Right answer. Note: By default, it is set as Normal.','quiz-maker'); ?>">
                                                <i class="ays_fa ays_fa_info_circle"></i>
                                            </a>
                                        </label>
                                    </div>
                                    <div class="col-sm-7 ays_divider_left">
                                        <select class="ays-text-input ays-text-input-short" id="ays_quiz_right_answers_font_weight" name="ays_quiz_right_answers_font_weight">
                                            <option value="normal" <?php echo ($quiz_right_answers_font_weight == 'normal') ? 'selected' : ''; ?>>
                                                <?php echo __('Normal','quiz-maker'); ?>
                                            </option>
                                            <option value="lighter" <?php echo ($quiz_right_answers_font_weight == 'lighter') ? 'selected' : ''; ?>>
                                                <?php echo __('Lighter','quiz-maker'); ?>
                                            </option>
                                            <option value="bold" <?php echo ($quiz_right_answers_font_weight == 'bold')  ? 'selected' : ''; ?>>
                                                <?php echo __('Bold','quiz-maker'); ?>
                                            </option>
                                            <option value="bolder" <?php echo ($quiz_right_answers_font_weight == 'bolder') ? 'selected' : ''; ?>>
                                                <?php echo __('Bolder','quiz-maker'); ?>
                                            </option>
                                            <option value="100" <?php echo ($quiz_right_answers_font_weight == '100') ? 'selected' : ''; ?>>
                                                <?php echo '100'; ?>
                                            </option>
                                            <option value="200" <?php echo ($quiz_right_answers_font_weight == '200') ? 'selected' : ''; ?>>
                                                <?php echo '200'; ?>
                                            </option>
                                            <option value="300" <?php echo ($quiz_right_answers_font_weight == '300') ? 'selected' : ''; ?>>
                                                <?php echo '300'; ?>
                                            </option>
                                            <option value="400" <?php echo ($quiz_right_answers_font_weight == '400') ? 'selected' : ''; ?>>
                                                <?php echo '400'; ?>
                                            </option>
                                            <option value="500" <?php echo ($quiz_right_answers_font_weight == '500') ? 'selected' : ''; ?>>
                                                <?php echo '500'; ?>
                                            </option>
                                            <option value="600" <?php echo ($quiz_right_answers_font_weight == '600') ? 'selected' : ''; ?>>
                                                <?php echo '600'; ?>
                                            </option>
                                            <option value="700" <?php echo ($quiz_right_answers_font_weight == '700') ? 'selected' : ''; ?>>
                                                <?php echo '700'; ?>
                                            </option>
                                            <option value="800" <?php echo ($quiz_right_answers_font_weight == '800') ? 'selected' : ''; ?>>
                                                <?php echo '800'; ?>
                                            </option>
                                            <option value="900" <?php echo ($quiz_right_answers_font_weight == '900') ? 'selected' : ''; ?>>
                                                <?php echo '900'; ?>
                                            </option>
                                        </select>
                                    </div>
                                </div><!-- Right answer font weight -->
                            </div>
                            <hr/>
                            <div class="col-lg-5 col-sm-12 ays_divider_left" style="position:relative;"></div>
                        </div> <!-- Right answer styles End -->
                    </div>
                </div>
                <div class="ays-quiz-accordion-options-main-container" data-collapsed="false">
                    <div class="ays-quiz-accordion-container">
                        <?php echo $quiz_accordion_svg_html; ?>
                        <p class="ays-subtitle" style="margin-top:0;"><?php echo __('Wrong answer styles','quiz-maker'); ?></p>
                    </div>
                    <hr class="ays-quiz-bolder-hr"/>
                    <div class="ays-quiz-accordion-options-box">
                        <div class="form-group row"> <!-- Wrong answer styles -->
                            <div class="col-lg-7 col-sm-12">
                                <div class="form-group row">
                                    <div class="col-sm-5">
                                        <label for="ays_wrong_answers_font_size">
                                            <?php echo esc_html__('Font size for the wrong answer','quiz-maker'); ?>
                                            <a class="ays_help" data-toggle="tooltip" title="<?php echo esc_attr__('Choose the Font Size for the Message displayed for the wrong answer( only for <p> tag ).','quiz-maker')?>">
                                                <i class="ays_fa ays_fa_info_circle"></i>
                                            </a>
                                        </label>
                                    </div>
                                    <div class="col-sm-7 ays_divider_left">
                                        <div class="row">
                                            <div class="col-sm-5">
                                                <label for='ays_question_font_size'>
                                                    <?php echo esc_html__('On desktop', 'quiz-maker'); ?>
                                                    <a class="ays_help" data-toggle="tooltip" title="<?php echo esc_attr__('Define the font size for desktop devices.','quiz-maker'); ?>">
                                                        <i class="ays_fa ays_fa_info_circle"></i>
                                                    </a>
                                                </label>
                                            </div>
                                            <div class="col-sm-7 ays_quiz_display_flex_width">
                                                <div>
                                                    <input type="number" class="ays-text-input" id='ays_wrong_answers_font_size' name='ays_wrong_answers_font_size' value="<?php echo intval($wrong_answers_font_size); ?>"/>
                                                </div>
                                                <div class="ays_quiz_dropdown_max_width ays-display-flex" style="align-items: end;">
                                                    <input type="text" value="px" class='ays-quiz-form-hint-for-size' disabled>
                                                </div>
                                            </div>
                                        </div>
                                        <hr>
                                        <div class="row">
                                            <div class="col-sm-5">
                                                <label for='ays_wrong_answers_mobile_font_size'>
                                                    <?php echo esc_html__('On mobile', 'quiz-maker'); ?>
                                                    <a class="ays_help" data-toggle="tooltip" title="<?php echo esc_attr__('Define the font size for mobile devices.','quiz-maker'); ?>">
                                                        <i class="ays_fa ays_fa_info_circle"></i>
                                                    </a>
                                                </label>
                                            </div>
                                            <div class="col-sm-7 ays_quiz_display_flex_width">
                                                <div>
                                                    <input type="number" class="ays-text-input" id='ays_wrong_answers_mobile_font_size' name='ays_wrong_answers_mobile_font_size' value="<?php echo intval($wrong_answers_mobile_font_size); ?>"/>
                                                </div>
                                                <div class="ays_quiz_dropdown_max_width ays-display-flex" style="align-items: end;">
                                                    <input type="text" value="px" class='ays-quiz-form-hint-for-size' disabled>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div><!-- Font size for the wrong answer -->
                                <hr/>
                                <div class="form-group row">
                                    <div class="col-sm-5">
                                        <label for="ays_quiz_wrong_answer_text_transform">
                                            <?php echo __('Text transformation','quiz-maker'); ?>
                                            <a class="ays_help" data-toggle="tooltip" title="<?php echo esc_attr__('Specify how the text appears in all-uppercase or all-lowercase, or with each word capitalized.','quiz-maker'); ?>">
                                                <i class="ays_fa ays_fa_info_circle"></i>
                                            </a>
                                        </label>
                                    </div>
                                    <div class="col-sm-7 ays_divider_left">
                                        <div class="row">
                                            <div class="col-sm-5">
                                                <label for='ays_quiz_right_answer_text_transform'>
                                                    <?php echo esc_html__('On desktop', 'quiz-maker'); ?>
                                                    <a class="ays_help" data-toggle="tooltip" title="<?php echo esc_attr( __('Define the text transformation for desktop devices.','quiz-maker') ); ?>">
                                                        <i class="ays_fa ays_fa_info_circle"></i>
                                                    </a>
                                                </label>
                                            </div>
                                            <div class="col-sm-7 ays_quiz_display_flex_width">
                                                <select class="ays-text-input ays-text-input-short" id="ays_quiz_wrong_answer_text_transform" name="ays_quiz_wrong_answer_text_transform">
                                                    <option value="none" <?php echo ($quiz_wrong_answer_text_transform == 'none') ? 'selected' : ''; ?>>
                                                        <?php echo __('None','quiz-maker'); ?>
                                                    </option>
                                                    <option value="capitalize" <?php echo ($quiz_wrong_answer_text_transform == 'capitalize') ? 'selected' : ''; ?>>
                                                        <?php echo __('Capitalize','quiz-maker'); ?>
                                                    </option>
                                                    <option value="uppercase" <?php echo ($quiz_wrong_answer_text_transform == 'uppercase')  ? 'selected' : ''; ?>>
                                                        <?php echo __('Uppercase','quiz-maker'); ?>
                                                    </option>
                                                    <option value="lowercase" <?php echo ($quiz_wrong_answer_text_transform == 'lowercase') ? 'selected' : ''; ?>>
                                                        <?php echo __('Lowercase','quiz-maker'); ?>
                                                    </option>
                                                </select>
                                            </div>
                                        </div>
                                        <hr>
                                        <div class="row">
                                            <div class="col-sm-5">
                                                <label for='ays_quiz_wrong_answer_mobile_text_transform'>
                                                    <?php echo esc_html__('On mobile', 'quiz-maker'); ?>
                                                    <a class="ays_help" data-toggle="tooltip" title="<?php echo esc_attr(__('Define the text transformation for mobile devices.','quiz-maker')); ?>">
                                                        <i class="ays_fa ays_fa_info_circle"></i>
                                                    </a>
                                                </label>
                                            </div>
                                            <div class="col-sm-7 ays_quiz_display_flex_width">
                                                <select class="ays-text-input ays-text-input-short" id="ays_quiz_wrong_answer_mobile_text_transform" name="ays_quiz_wrong_answer_mobile_text_transform">
                                                    <option value="none" <?php echo ($quiz_wrong_answer_mobile_text_transform == 'none') ? 'selected' : ''; ?>>
                                                        <?php echo esc_html__('None','quiz-maker'); ?>
                                                    </option>
                                                    <option value="capitalize" <?php echo ($quiz_wrong_answer_mobile_text_transform == 'capitalize') ? 'selected' : ''; ?>>
                                                        <?php echo esc_html__('Capitalize','quiz-maker'); ?>
                                                    </option>
                                                    <option value="uppercase" <?php echo ($quiz_wrong_answer_mobile_text_transform == 'uppercase')  ? 'selected' : ''; ?>>
                                                        <?php echo esc_html__('Uppercase','quiz-maker'); ?>
                                                    </option>
                                                    <option value="lowercase" <?php echo ($quiz_wrong_answer_mobile_text_transform == 'lowercase') ? 'selected' : ''; ?>>
                                                        <?php echo esc_html__('Lowercase','quiz-maker'); ?>
                                                    </option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div><!-- Wrong answer text transform -->
                                <hr/>
                                <div class="form-group row">
                                    <div class="col-sm-5">
                                        <label for="ays_quiz_wrong_answers_text_decoration">
                                            <?php echo __('Text decoration','quiz-maker'); ?>
                                            <a class="ays_help" data-toggle="tooltip" title="<?php echo __('Choose the line position for the Wrong answer on the front end. Note: It is set as None by default.','quiz-maker'); ?>">
                                                <i class="ays_fa ays_fa_info_circle"></i>
                                            </a>
                                        </label>
                                    </div>
                                    <div class="col-sm-7 ays_divider_left">
                                        <div class="row">
                                            <div class="col-sm-5">
                                                <label for='ays_quiz_right_answers_text_decoration'>
                                                    <?php echo esc_html__('On desktop', 'quiz-maker'); ?>
                                                    <a class="ays_help" data-toggle="tooltip" title="<?php echo esc_attr( __('Choose the line position for the Wrong answer Text on the front end for desktop devices. Note: It is set as None by default.','quiz-maker') ); ?>">
                                                        <i class="ays_fa ays_fa_info_circle"></i>
                                                    </a>
                                                </label>
                                            </div>
                                            <div class="col-sm-7 ays_quiz_display_flex_width">
                                                <select class="ays-text-input ays-text-input-short" id="ays_quiz_wrong_answers_text_decoration" name="ays_quiz_wrong_answers_text_decoration">
                                                    <option value="none" <?php echo ($quiz_wrong_answers_text_decoration == 'none') ? 'selected' : ''; ?>>
                                                        <?php echo __('None','quiz-maker'); ?>
                                                    </option>
                                                    <option value="overline" <?php echo ($quiz_wrong_answers_text_decoration == 'overline') ? 'selected' : ''; ?>>
                                                        <?php echo __('Overline','quiz-maker'); ?>
                                                    </option>
                                                    <option value="line-through" <?php echo ($quiz_wrong_answers_text_decoration == 'line-through')  ? 'selected' : ''; ?>>
                                                        <?php echo __('Line through','quiz-maker'); ?>
                                                    </option>
                                                    <option value="underline" <?php echo ($quiz_wrong_answers_text_decoration == 'underline') ? 'selected' : ''; ?>>
                                                        <?php echo __('Underline','quiz-maker'); ?>
                                                    </option>
                                                </select>
                                            </div>
                                        </div>
                                        <hr>
                                        <div class="row">
                                            <div class="col-sm-5">
                                                <label for='ays_quiz_wrong_answers_mobile_text_decoration'>
                                                    <?php echo esc_html__('On mobile', 'quiz-maker'); ?>
                                                    <a class="ays_help" data-toggle="tooltip" title="<?php echo esc_attr(__('Choose the line position for the Wrong answer Text on the front end for mobile devices. Note: It is set as None by default.','quiz-maker')); ?>">
                                                        <i class="ays_fa ays_fa_info_circle"></i>
                                                    </a>
                                                </label>
                                            </div>
                                            <div class="col-sm-7 ays_quiz_display_flex_width">
                                                <select class="ays-text-input ays-text-input-short" id="ays_quiz_wrong_answers_mobile_text_decoration" name="ays_quiz_wrong_answers_mobile_text_decoration">
                                                    <option value="none" <?php echo ($quiz_wrong_answers_mobile_text_decoration == 'none') ? 'selected' : ''; ?>>
                                                        <?php echo esc_html__('None','quiz-maker'); ?>
                                                    </option>
                                                    <option value="overline" <?php echo ($quiz_wrong_answers_mobile_text_decoration == 'overline') ? 'selected' : ''; ?>>
                                                        <?php echo esc_html__('Overline','quiz-maker'); ?>
                                                    </option>
                                                    <option value="line-through" <?php echo ($quiz_wrong_answers_mobile_text_decoration == 'line-through')  ? 'selected' : ''; ?>>
                                                        <?php echo esc_html__('Line through','quiz-maker'); ?>
                                                    </option>
                                                    <option value="underline" <?php echo ($quiz_wrong_answers_mobile_text_decoration == 'underline') ? 'selected' : ''; ?>>
                                                        <?php echo esc_html__('Underline','quiz-maker'); ?>
                                                    </option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div><!-- Text decoration -->
                                <hr/>
                                <div class="form-group row">
                                    <div class="col-sm-5">
                                        <label for="ays_quiz_wrong_answers_letter_spacing">
                                            <?php echo __('Letter spacing','quiz-maker')?>
                                            <a class="ays_help" data-toggle="tooltip" title="<?php echo __('Define the space between the letters of the wrong answer text in pixels. Note: The default value for this option is 0.','quiz-maker'); ?>">
                                                <i class="ays_fa ays_fa_info_circle"></i>
                                            </a>
                                        </label>
                                    </div>
                                    <div class="col-sm-7 ays_divider_left">
                                        <div class="row">
                                            <div class="col-sm-5">
                                                <label for='ays_quiz_wrong_answers_letter_spacing'>
                                                    <?php echo esc_html__('On desktop', 'quiz-maker'); ?>
                                                    <a class="ays_help" data-toggle="tooltip" title="<?php echo esc_attr( __('Choose the letter spacing for the Right answer Text on the front end for desktop devices. Note: It is set as Empty (0px) by default.','quiz-maker') ); ?>">
                                                        <i class="ays_fa ays_fa_info_circle"></i>
                                                    </a>
                                                </label>
                                            </div>
                                            <div class="col-sm-7 ays_quiz_display_flex_width">
                                                <div>
                                                    <input type="number" class="ays-text-input ays-text-input-short" id="ays_quiz_wrong_answers_letter_spacing" name="ays_quiz_wrong_answers_letter_spacing" value="<?php echo intval($quiz_wrong_answers_letter_spacing); ?>"/>
                                                </div>
                                                <div class="ays_quiz_dropdown_max_width ays-display-flex" style="align-items: flex-start;">
                                                    <input type="text" value="px" class='ays-quiz-form-hint-for-size' disabled>
                                                </div>
                                            </div>
                                        </div>
                                        <hr>
                                        <div class="row">
                                            <div class="col-sm-5">
                                                <label for='ays_quiz_wrong_answers_mobile_letter_spacing'>
                                                    <?php echo esc_html__('On mobile', 'quiz-maker'); ?>
                                                    <a class="ays_help" data-toggle="tooltip" title="<?php echo esc_attr(__('Choose the letter spacing for the Wrong answer Text on the front end for mobile devices. Note: It is set as Empty (0px) by default.','quiz-maker')); ?>">
                                                        <i class="ays_fa ays_fa_info_circle"></i>
                                                    </a>
                                                </label>
                                            </div>
                                            <div class="col-sm-7 ays_quiz_display_flex_width">
                                                <div>
                                                    <input type="number" class="ays-text-input" id="ays_quiz_wrong_answers_mobile_letter_spacing" name="ays_quiz_wrong_answers_mobile_letter_spacing" value="<?php echo intval($quiz_wrong_answers_mobile_letter_spacing); ?>"/>
                                                </div>
                                                <div class="ays_quiz_dropdown_max_width ays-display-flex" style="align-items: flex-start;">
                                                    <input type="text" value="px" class='ays-quiz-form-hint-for-size' disabled>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div><!-- Letter spacing -->
                                <hr/>
                                <div class="form-group row">
                                    <div class="col-sm-5">
                                        <label for="ays_quiz_wrong_answers_font_weight">
                                            <?php echo __('Font weight','quiz-maker'); ?>
                                            <a class="ays_help" data-toggle="tooltip" title="<?php echo __('Specify the font weight for the Wrong answer. Note: By default, it is set as Normal.','quiz-maker'); ?>">
                                                <i class="ays_fa ays_fa_info_circle"></i>
                                            </a>
                                        </label>
                                    </div>
                                    <div class="col-sm-7 ays_divider_left">
                                        <select class="ays-text-input ays-text-input-short" id="ays_quiz_wrong_answers_font_weight" name="ays_quiz_wrong_answers_font_weight">
                                            <option value="normal" <?php echo ($quiz_wrong_answers_font_weight == 'normal') ? 'selected' : ''; ?>>
                                                <?php echo __('Normal','quiz-maker'); ?>
                                            </option>
                                            <option value="lighter" <?php echo ($quiz_wrong_answers_font_weight == 'lighter') ? 'selected' : ''; ?>>
                                                <?php echo __('Lighter','quiz-maker'); ?>
                                            </option>
                                            <option value="bold" <?php echo ($quiz_wrong_answers_font_weight == 'bold')  ? 'selected' : ''; ?>>
                                                <?php echo __('Bold','quiz-maker'); ?>
                                            </option>
                                            <option value="bolder" <?php echo ($quiz_wrong_answers_font_weight == 'bolder') ? 'selected' : ''; ?>>
                                                <?php echo __('Bolder','quiz-maker'); ?>
                                            </option>
                                            <option value="100" <?php echo ($quiz_wrong_answers_font_weight == '100') ? 'selected' : ''; ?>>
                                                <?php echo '100'; ?>
                                            </option>
                                            <option value="200" <?php echo ($quiz_wrong_answers_font_weight == '200') ? 'selected' : ''; ?>>
                                                <?php echo '200'; ?>
                                            </option>
                                            <option value="300" <?php echo ($quiz_wrong_answers_font_weight == '300') ? 'selected' : ''; ?>>
                                                <?php echo '300'; ?>
                                            </option>
                                            <option value="400" <?php echo ($quiz_wrong_answers_font_weight == '400') ? 'selected' : ''; ?>>
                                                <?php echo '400'; ?>
                                            </option>
                                            <option value="500" <?php echo ($quiz_wrong_answers_font_weight == '500') ? 'selected' : ''; ?>>
                                                <?php echo '500'; ?>
                                            </option>
                                            <option value="600" <?php echo ($quiz_wrong_answers_font_weight == '600') ? 'selected' : ''; ?>>
                                                <?php echo '600'; ?>
                                            </option>
                                            <option value="700" <?php echo ($quiz_wrong_answers_font_weight == '700') ? 'selected' : ''; ?>>
                                                <?php echo '700'; ?>
                                            </option>
                                            <option value="800" <?php echo ($quiz_wrong_answers_font_weight == '800') ? 'selected' : ''; ?>>
                                                <?php echo '800'; ?>
                                            </option>
                                            <option value="900" <?php echo ($quiz_wrong_answers_font_weight == '900') ? 'selected' : ''; ?>>
                                                <?php echo '900'; ?>
                                            </option>
                                        </select>
                                    </div>
                                </div><!-- Wrong answer font weight -->
                            </div>
                            <hr/>
                            <div class="col-lg-5 col-sm-12 ays_divider_left" style="position:relative;"></div>
                        </div> <!-- Wrong answer styles End -->
                        <hr/>
                    </div>
                </div>
                <div class="ays-quiz-accordion-options-main-container" data-collapsed="false">
                    <div class="ays-quiz-accordion-container">
                        <?php echo $quiz_accordion_svg_html; ?>
                        <p class="ays-subtitle" style="margin-top:0;"><?php echo __('Advanced settings','quiz-maker'); ?></p>
                    </div>
                    <hr class="ays-quiz-bolder-hr"/>
                    <div class="ays-quiz-accordion-options-box">
                        <div class="form-group row">
                            <div class="col-sm-3">
                                <label for="ays_custom_css">
                                    <?php echo __('Custom CSS','quiz-maker')?>
                                    <a class="ays_help" data-toggle="tooltip" title="<?php echo __('Field for entering your own CSS code. Example: p{color:red !important}','quiz-maker')?>">
                                        <i class="ays_fa ays_fa_info_circle"></i>
                                    </a>
                                </label>
                            </div>
                            <div class="col-sm-9">
                                <textarea class="ays-textarea" id="ays_custom_css" name="ays_custom_css" cols="30" rows="10"><?php echo $ays_quiz_custom_css; ?></textarea>
                            </div>
                        </div> <!-- Custom CSS -->
                    </div>
                </div>
            </div><!-- #tab2 --> 

            <div id="tab3" class="ays-quiz-tab-content <?php echo ($ays_quiz_tab == 'tab3') ? 'ays-quiz-tab-content-active' : ''; ?>">
                <div class="ays-quiz-top-actions-container-wrapper form-group row">
                    <div class="col-sm-12">
                        <p class="m-0 text-right">
                            <a class="ays-quiz-collapse-all" href="javascript:void(0);"><?php echo __( "Collapse All", "quiz-maker" ); ?></a>
                            <span>|</span>
                            <a class="ays-quiz-expand-all" href="javascript:void(0);"><?php echo __( "Expand All", "quiz-maker" ); ?></a>
                        </p>
                    </div>
                </div>
                <div class="ays-quiz-accordion-options-main-container" data-collapsed="false">
                    <div class="ays-quiz-accordion-container">
                        <?php echo $quiz_accordion_svg_html; ?>
                        <p class="ays-subtitle"><?php echo __('Primary','quiz-maker')?></p>
                    </div>
                    <hr class="ays-quiz-bolder-hr"/>
                    <div class="ays-quiz-accordion-options-box">
                        <div class="form-group row">
                            <div class="col-sm-4">
                                <label>
                                    <?php echo __('Status', 'quiz-maker'); ?>
                                    <a class="ays_help" data-toggle="tooltip" title="<?php echo __('Published OR Unpublished. Choose whether the quiz is active or not. If you choose Unpublished option, the quiz won’t be shown anywhere in your website (You don’t need to remove shortcodes).','quiz-maker'); ?>">
                                        <i class="ays_fa ays_fa_info_circle"></i>
                                    </a>
                                </label>
                            </div>
                            <div class="col-sm-8">
                                <div class="form-check form-check-inline">
                                    <input type="radio" id="ays-publish" name="ays_publish"
                                           value="1" <?php echo ($quiz_published == '') ? "checked" : ""; ?>  <?php echo ($quiz_published == '1') ? 'checked' : ''; ?>/>
                                    <label class="form-check-label"
                                           for="ays-publish"> <?php echo __('Published', 'quiz-maker'); ?> </label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input type="radio" id="ays-unpublish" name="ays_publish"
                                           value="0" <?php echo ($quiz_published == '0') ? 'checked' : ''; ?>/>
                                    <label class="form-check-label"
                                           for="ays-unpublish"> <?php echo __('Unpublished', 'quiz-maker'); ?> </label>
                                </div>
                            </div>
                        </div>
                        <hr/>
                        <div class="form-group row">
                            <div class="col-sm-4">
                                <label for="ays-category">
                                    <?php echo esc_html__('Category', 'quiz-maker'); ?>
                                    <a class="ays_help" data-toggle="tooltip" title="<?php echo esc_attr( __('Category of the quiz. For making a category please visit Quiz Categories page from the left navbar.','quiz-maker') ); ?>">
                                        <i class="ays_fa ays_fa_info_circle"></i>
                                    </a>
                                </label>
                            </div>
                            <div class="col-sm-8">
                                <select id="ays-category" name="ays_quiz_category">
                                    <option></option>
                                    <?php
                                    $cat = 0;
                                    foreach ($quiz_categories as $key => $quiz_category) {

                                        $quiz_category_id = (isset( $quiz['quiz_category_id'] ) && $quiz['quiz_category_id'] != "") ? $quiz['quiz_category_id'] : 1;
                                        $q_category_id = (isset( $quiz_category['id'] ) && $quiz_category['id'] != "") ? $quiz_category['id'] : 1;

                                        $quiz_category_title = (isset( $quiz_category['title'] ) && $quiz_category['title'] != "") ? esc_attr( stripslashes($quiz_category['title']) ) : "";

                                        $selected = (intval($q_category_id) == intval($quiz_category_id)) ? "selected" : "";
                                        if ($cat == 0 && intval($quiz_category_id) == 0) {
                                            $selected = 'selected';
                                        }
                                        echo '<option value="' . esc_attr($q_category_id) . '" ' . esc_attr($selected) . '>' . esc_html($quiz_category_title) . '</option>';
                                        $cat++;
                                    }
                                    ?>
                                </select>
                                <div class="ays_quiz_small_hint_text_for_message_variables" style="margin-top: 5px;">
                                <span><?php
                                    echo (sprintf(
                                        /* translators: %s: opening and closing <a> HTML code  */
                                        wp_kses_post(__('Create a new category %s here %s', 'quiz-maker')),
                                        '<a href="'. $quiz_category_page_url .'" target="_blank">',
                                        '</a>'
                                    )) ;
                                ?></div>
                            </p>
                            </div>
                        </div>
                        <hr/>
                        <div class="form-group row ays_toggle_parent">
                            <div class="col-sm-4">
                                <label for="ays_enable_timer">
                                    <?php echo __('Enable Timer','quiz-maker'); ?>
                                    <a class="ays_help" data-toggle="tooltip" title="<?php echo __('Show countdown time in the quiz. It will be automatically submitted if the time is over.','quiz-maker'); ?>">
                                        <i class="ays_fa ays_fa_info_circle"></i>
                                    </a>
                                </label>
                            </div>
                            <div class="col-sm-1">
                                <input type="checkbox" class="ays-enable-timerl ays_toggle_checkbox" id="ays_enable_timer" name="ays_enable_timer" value="on" <?php echo ( $enable_timer ) ? 'checked' : ''; ?>/>
                            </div>
                            <div class="col-sm-7 ays_toggle_target ays_divider_left  <?php echo ( $enable_timer ) ? '' : 'display_none'; ?>">
                                <div class="ays-quiz-timer-container" id="ays-quiz-timer-container">
                                    <div class="col-sm-12" style="padding:10px 0 0 10px;">
                                        <div class="form-group row">
                                            <div class="col-sm-12" style="display: flex;">
                                                <label class="ays_quiz_loader">
                                                    <input type="radio" class="ays-enable-timer1" name="ays_quiz_timer_type" value="quiz_timer" checked />
                                                    <span><?php echo esc_html__( "Quiz Timer", 'quiz-maker' ); ?></span>
                                                </label>
                                                <div class="only_pro custom_pro_features ays-pro-features-v2-main-box ays-pro-features-v2-main-box-themes ays-pro-features-v2-main-box-small" style="background: unset;">
                                                    <div class="ays-pro-features-v2-small-buttons-box" style="right: 65px; top: -15px;">
                                                        <a href="https://ays-pro.com/wordpress/quiz-maker?utm_source=dashboard&utm_medium=quiz-free&utm_campaign=quiz-timer-<?php echo esc_attr( AYS_QUIZ_UTM_VERSION ); ?>" target="_blank" class="ays-pro-features-v2-upgrade-button">
                                                            <div class="ays-pro-features-v2-upgrade-icon" style="background-image: url('<?php echo esc_attr(AYS_QUIZ_ADMIN_URL); ?>/images/icons/locked_24x24.svg');" data-img-src="<?php echo esc_attr(AYS_QUIZ_ADMIN_URL); ?>/images/icons/locked_24x24.svg"></div>
                                                        </a>
                                                    </div>
                                                    <label class="ays_quiz_loader" style="background: rgba(53 113 196 / 10%);">
                                                        <input type="radio" class="ays-enable-timer1" disabled />
                                                        <span><?php echo esc_html__( "Question Timer", 'quiz-maker' ); ?></span>
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <hr>
                                    <div class="form-group row">
                                        <div class="col-sm-12">
                                            <div class="ays-quiz-heading-box ays-quiz-unset-float ays-quiz-unset-margin">
                                                <div class="ays-quiz-wordpress-user-manual-box ays-quiz-wordpress-text-align">
                                                    <a href="https://www.youtube.com/watch?v=748BkDmA92U" target="_blank">
                                                        <?php echo __("How timer works - video", 'quiz-maker'); ?>
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <div class="col-sm-3">
                                            <label for="ays_quiz_timer"><?php echo __('Timer seconds','quiz-maker')?></label>
                                        </div>
                                        <div class="col-sm-9">
                                            <input type="number" name="ays_quiz_timer" id="ays_quiz_timer"
                                                   class="ays-text-input"
                                                   value="<?php echo $timer; ?>"/>
                                            <p class="ays-important-note"><span><?php echo __('Note','quiz-maker')?>!!</span> <?php echo __('After timer finished countdowning, quiz will be submitted automatically.','quiz-maker')?></p>
                                        </div>
                                    </div>
                                    <hr>
                                    <div class="form-group row">
                                        <div class="col-sm-3">
                                            <label for="ays_quiz_message_before_timer">
                                                <?php echo __('Message before timer','quiz-maker'); ?>
                                                <a class="ays_help" data-toggle="tooltip" title="<?php echo esc_attr( __('Write a message to display before the timer. For example, "Hurry up, the time is ticking! 00:30".','quiz-maker') ); ?>">
                                                    <i class="ays_fa ays_fa_info_circle"></i>
                                                </a>
                                            </label>
                                        </div>
                                        <div class="col-sm-9">
                                            <input type="text" class="ays-text-input" id="ays_quiz_message_before_timer" name="ays_quiz_message_before_timer" value="<?php echo $quiz_message_before_timer; ?>"/>
                                        </div>
                                    </div>
                                    <hr/>
                                    <div class="form-group row ays-quiz-result-message-vars-parent">
                                        <div class="col-sm-3">
                                            <label for="timer_text">
                                                <?php echo __("Message before starting the quiz", 'quiz-maker'); ?>
                                                <a class="ays_help" data-toggle="tooltip" data-html="true" title="<?php echo esc_attr( sprintf(
                                                    __( '%sThis message will appear in your quiz, before it starts. You can use:%s %%%%time%%%% %s %%%%quiz_name%%%% %s %%%%user_first_name%%%% %s %%%%user_last_name%%%% %s %%%%questions_count%%%% %s %%%%user_nickname%%%% %s %%%%user_display_name%%%% %s message variables to customize the text. %s', 'quiz-maker' ),
                                                        "<div class='ays-quiz-tooltip-box'>",
                                                        "<ul class='ays-quiz-tooltip-ul'><li>",
                                                        "</li><li>",
                                                        "</li><li>",
                                                        "</li><li>",
                                                        "</li><li>",
                                                        "</li><li>",
                                                        "</li><li>",
                                                        "</li></ul>",
                                                        "</div>"
                                                    ) ); ?>"
                                                >
                                                    <i class="ays_fa ays_fa_info_circle"></i>
                                                </a>
                                            </label>
                                        </div>
                                        <div class="col-sm-9">
                                            <?php
                                                echo $quiz_message_vars_timer_html;
                                                $content = wpautop(stripslashes((isset($options['timer_text'])) ? $options['timer_text'] : ''));
                                                $editor_id = 'timer_text';
                                                $settings = array('editor_height' => $quiz_wp_editor_height, 'textarea_name' => 'ays_timer_text', 'editor_class' => 'ays-textarea', 'media_elements' => false);
                                                wp_editor($content, $editor_id, $settings);
                                            ?>
                                        </div>
                                    </div>
                                    <hr>
                                    <div class="form-group row ays-quiz-result-message-vars-parent">
                                        <div class="col-sm-3">
                                            <label for="after_timer_text">
                                                <?php echo __("Message after the timer ends", 'quiz-maker'); ?>
                                                <a class="ays_help" data-toggle="tooltip" data-html="true" title="<?php echo esc_attr( sprintf(
                                                    __( '%sThis message will appear after the timer ends. You can use:%s %%%%time%%%% %s %%%%quiz_name%%%% %s %%%%user_first_name%%%% %s %%%%user_last_name%%%% %s %%%%questions_count%%%% %s %%%%user_nickname%%%% %s %%%%user_display_name%%%% %s message variables to customize the text. %s', 'quiz-maker' ),
                                                        "<div class='ays-quiz-tooltip-box'>",
                                                        "<ul class='ays-quiz-tooltip-ul'><li>",
                                                        "</li><li>",
                                                        "</li><li>",
                                                        "</li><li>",
                                                        "</li><li>",
                                                        "</li><li>",
                                                        "</li><li>",
                                                        "</li></ul>",
                                                        "</div>"
                                                    ) ); ?>"
                                                >
                                                    <i class="ays_fa ays_fa_info_circle"></i>
                                                </a>
                                            </label>
                                        </div>
                                        <div class="col-sm-9">
                                            <?php
                                                echo $quiz_message_vars_timer_html;
                                                $content = $after_timer_text;
                                                $editor_id = 'after_timer_text';
                                                $settings = array('editor_height' => $quiz_wp_editor_height, 'textarea_name' => 'ays_after_timer_text', 'editor_class' => 'ays-textarea', 'media_elements' => false);
                                                wp_editor($content, $editor_id, $settings);
                                            ?>
                                        </div>
                                    </div>
                                    <hr>
                                    <div class="form-group row">
                                        <div class="col-sm-3">
                                            <label for="ays_quiz_timer_in_title">
                                                <?php echo __('Show timer on page tab','quiz-maker'); ?>
                                                <a class="ays_help" data-toggle="tooltip" title="<?php echo __('Enable to show countdown timer in the browser tab.','quiz-maker'); ?>">
                                                    <i class="ays_fa ays_fa_info_circle"></i>
                                                </a>
                                            </label>
                                        </div>
                                        <div class="col-sm-9">
                                            <input type="checkbox" name="ays_quiz_timer_in_title" id="ays_quiz_timer_in_title"
                                                   <?php echo ($quiz_timer_in_title) ? 'checked' : ''; ?>/>
                                        </div>
                                    </div>
                                    <hr>
                                    <div class="form-group row ays_toggle_parent">
                                        <div class="col-sm-3">
                                            <label for="ays_quiz_timer_red_warning">
                                                <?php echo __('Turn on warning','quiz-maker'); ?>
                                                <a class="ays_help" data-toggle="tooltip" title="<?php echo __('Enable this option and the timer color will be changed after the user completes 90% of the quiz.','quiz-maker'); ?>">
                                                    <i class="ays_fa ays_fa_info_circle"></i>
                                                </a>
                                            </label>
                                        </div>
                                        <div class="col-sm-1">
                                            <input type="checkbox" class="ays_toggle_checkbox" name="ays_quiz_timer_red_warning" id="ays_quiz_timer_red_warning"
                                                   <?php echo ($quiz_timer_red_warning) ? 'checked' : ''; ?>/>
                                        </div>

                                        <div class="col-sm-8 ays_toggle_target ays_divider_left <?php echo ($quiz_timer_red_warning) ? '' : 'display_none'; ?>">
                                            <div class="form-group row">
                                                <div class="col-sm-4">
                                                    <label class="form-check-label" for="ays_quiz_timer_warning_text_color">
                                                        <?php echo __('Warning text color', 'quiz-maker'); ?>
                                                        <a class="ays_help" data-toggle="tooltip"
                                                        title="<?php echo esc_attr(__('Specify the text color of the Warning Message. *Note: The color is set Red by default.', 'quiz-maker')); ?>">
                                                            <i class="ays_fa ays_fa_info_circle"></i>
                                                        </a>
                                                    </label>
                                                </div>
                                                <div class="col-sm-8">
                                                    <input type="text" class="ays-text-input" id='ays_quiz_timer_warning_text_color' data-alpha="true" name='ays_quiz_timer_warning_text_color' value="<?php echo esc_attr($quiz_timer_warning_text_color); ?>"/>
                                                </div>
                                            </div> <!-- Warning text color -->
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-7 ays_toggle_target_inverse <?php echo ( $enable_timer ) ? 'display_none' : ''; ?>">
                                <div class="ays-quiz-heading-box ays-quiz-unset-float ays-quiz-unset-margin">
                                    <div class="ays-quiz-wordpress-user-manual-box ays-quiz-wordpress-text-align">
                                        <a href="https://www.youtube.com/watch?v=748BkDmA92U" target="_blank">
                                            <?php echo __("How timer works - video", 'quiz-maker'); ?>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div> <!--  Enable Timer -->
                        <hr/>
                        <div class="form-group row">
                            <div class="col-sm-4">
                                <label for="ays_enable_randomize_questions">
                                   <?php echo __('Enable randomize questions','quiz-maker')?>
                                   <a class="ays_help" data-toggle="tooltip" title="<?php echo __('The possibility of showing questions in an accidental sequence. It will show questions in random order. If you want to take a specific amount of questions from a pool of questions randomly you need to enable question bank option.','quiz-maker')?>">
                                        <i class="ays_fa ays_fa_info_circle"></i>
                                    </a>
                                </label>
                            </div>
                            <div class="col-sm-8">
                                <div class="form-group row">
                                    <div class="col-sm-12" style="margin-bottom: .5rem;">
                                        <input type="checkbox" class="ays-enable-timerl" id="ays_enable_randomize_questions" name="ays_enable_randomize_questions" value="on" <?php echo (isset($options['randomize_questions']) && $options['randomize_questions'] == 'on') ? 'checked' : ''; ?>/>
                                   </div>
                                   <div class="col-sm-12">
                                        <p class="ays_quiz_small_hint_text_for_message_variables">
                                            <span><?php echo __( "Note: If you are using a Cache plugin, make sure to exclude the URL, where the given quiz is located so that the feature can work correctly for you." , 'quiz-maker' ); ?></span>
                                        </p>
                                    </div>
                               </div>
                            </div>
                        </div> <!-- Enable randomize questions -->
                        <hr/>
                        <div class="form-group row">
                            <div class="col-sm-4">
                                <label for="ays_enable_randomize_answers">
                                    <?php echo __('Enable randomize answers','quiz-maker')?>
                                    <a class="ays_help" data-toggle="tooltip" title="<?php echo __('The possibility of showing the answers of the questions in an accidental sequence. Every time it will show answers in random order.','quiz-maker')?>">
                                        <i class="ays_fa ays_fa_info_circle"></i>
                                    </a>
                                </label>
                                <?php if( 1 == 0 ): ?>
                                <p class="ays_quiz_small_hint_text_for_message_variables">
                                    <span><?php echo __( "Please Note" , 'quiz-maker' ); ?></span>
                                    <a class="ays_help" data-toggle="tooltip" title="<?php echo __('Note: If you notice, that the ordering is not being changed on the Front-end, then, most presumably, there is a cache problem for you. Please exclude the link, where the given quiz is located from the Cache plugin settings. Also, clear all the cache types (DB, plugin, browser). After clearing all the caches, check the case with either the Incognito mode or another browser/device.','quiz-maker'); ?>">
                                        <i class="ays_fa ays_fa_info_circle"></i>
                                    </a>
                                </p>
                                <?php endif; ?>
                            </div>
                            <div class="col-sm-8">
                                <div class="form-group row">
                                    <div class="col-sm-12" style="margin-bottom: .5rem;">
                                        <input type="checkbox" class="ays-enable-timerl" id="ays_enable_randomize_answers" name="ays_enable_randomize_answers" value="on" <?php echo (isset($options['randomize_answers']) && $options['randomize_answers'] == 'on') ? 'checked' : ''; ?>/>
                                    </div>
                                    <div class="col-sm-12">
                                        <p class="ays_quiz_small_hint_text_for_message_variables">
                                            <span><?php echo __( "Note: If you are using a Cache plugin, make sure to exclude the URL, where the given quiz is located so that the feature can work correctly for you." , 'quiz-maker' ); ?></span>
                                        </p>
                                    </div>
                               </div>
                            </div>
                        </div> <!--  Enable randomize answers -->
                        <hr/>
                        <div class="form-group row ays_toggle_parent" style="margin-bottom: 0;">
                            <div class="col-sm-4">
                                <label for="ays_enable_question_bank">
                                    <?php echo __('Enable question bank','quiz-maker')?>
                                    <a class="ays_help" data-toggle="tooltip" title="<?php echo __('Enable to take a specific amount of questions from the quiz randomly. For example, you can choose 20 questions from 50 randomly. Every time it will take different questions from the pool.','quiz-maker')?>">
                                        <i class="ays_fa ays_fa_info_circle"></i>
                                    </a>
                                </label>
                            </div>
                            <div class="col-sm-1">
                                <input type="checkbox" class="ays-enable-timer1 ays_toggle_checkbox" id="ays_enable_question_bank"
                                       name="ays_enable_question_bank" value="on"
                                    <?php echo (isset($options['enable_question_bank']) && $options['enable_question_bank'] == 'on') ? 'checked' : ''; ?>>
                            </div>
                            <div class="col-sm-7 ays_toggle_target" style="border-left: 1px solid #ededed; <?php echo (isset($options['enable_question_bank']) && $options['enable_question_bank'] == 'on') ? '' : 'display:none;'; ?>"
                                 id="ays_question_bank_div" >
                                <div class="form-group row">
                                    <div class="col-sm-12"> 
                                        <div class="ays-quiz-heading-box ays-quiz-unset-float ays-quiz-unset-margin">
                                            <div class="ays-quiz-wordpress-user-manual-box ays-quiz-wordpress-text-align">
                                                <a href="https://www.youtube.com/watch?v=nzQEHzmUBc8" target="_blank">
                                                    <?php echo __("How question bank works - video", 'quiz-maker'); ?>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <div class="col-sm-12 only_pro" style="padding:10px 0 0 10px;">
                                        <div class="pro_features" style="justify-content:flex-end;">
           
                                        </div>
                                        <label class="ays_quiz_loader">
                                            <input type="radio" class="ays-enable-timer1" value="general" checked tabindex="-1" />
                                            <span><?php echo __( "General", 'quiz-maker' ); ?></span>
                                        </label>
                                        <label class="ays_quiz_loader">
                                            <input type="radio" class="ays-enable-timer1" value="by_category" tabindex="-1"/>
                                            <span><?php echo __( "By Category", 'quiz-maker' ); ?></span>
                                        </label>
                                        <a class="ays_help" data-toggle="tooltip" data-html="true" title="<?php echo "<p style='text-indent:10px;margin:0;'>" .
                                            __('There are two ways of making question bank system.', 'quiz-maker' ) . "</p><p style='text-indent:10px;margin:0;'><strong>" .
                                            __('General', 'quiz-maker' ) . ": </strong>" .
                                            __('It will take the specified amount of questions from all the questions you include in this quiz.', 'quiz-maker' ) . "</p><p style='text-indent:10px;margin:0;'><strong>" .
                                            __('By Category', 'quiz-maker' ) . ": </strong>" .
                                            __('Here you can see all the categories of questions you have included in the general tab. You can provide different numbers for different categories. Also, you can reorder them as you want by drag and dropping. The category order will be kept in the front end, but questions will be printed randomly.', 'quiz-maker' ) . "</p>"; ?>">
                                            <i class="ays_fa ays_fa_info_circle"></i>
                                        </a>
                                        <div class="ays_refresh_qbank_categories display_none float-right">
                                            <p>
                                                <button type="button" class="button ays_refresh_qbank_cats_button"><?php echo __( "Refresh Categories", 'quiz-maker' ); ?></button>
                                            </p>
                                        </div>
                                        <a href="https://ays-pro.com/wordpress/quiz-maker?utm_source=dashboard&utm_medium=quiz-free&utm_campaign=question-bank-by-category-<?php echo esc_attr( AYS_QUIZ_UTM_VERSION ); ?>" target="_blank" class="ays-quiz-new-upgrade-button-link">
                                            <div class="ays-quiz-new-upgrade-button-box">
                                                <div>
                                                    <img loading="lazy" src="<?php echo AYS_QUIZ_ADMIN_URL.'/images/icons/locked_24x24.svg'?>">
                                                    <img loading="lazy" src="<?php echo AYS_QUIZ_ADMIN_URL.'/images/icons/unlocked_24x24.svg'?>" class="ays-quiz-new-upgrade-button-hover">
                                                </div>
                                                <div class="ays-quiz-new-upgrade-button"><?php echo __("Upgrade", "quiz-maker"); ?></div>
                                            </div>
                                        </a>
                                    </div>
                                </div>
                                <hr/>
                                <div class="form-group row">
                                    <div class="col-sm-4">
                                        <label for="ays_questions_count">
                                            <?php echo __('Questions count','quiz-maker')?>
                                            <a class="ays_help" data-toggle="tooltip" title="<?php echo __('Number of randomly selected questions','quiz-maker')?>">
                                                <i class="ays_fa ays_fa_info_circle"></i>
                                            </a>
                                        </label>
                                    </div>
                                    <div class="col-sm-8">
                                        <input type="number" name="ays_questions_count" id="ays_questions_count"
                                               class="ays-enable-timerl"
                                               value="<?php echo (isset($options['questions_count'])) ? $options['questions_count'] : '' ?>">
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-7 ays_toggle_target_inverse <?php echo (isset($options['enable_question_bank']) && $options['enable_question_bank'] == 'on') ? 'display_none' : ''; ?>">
                                <div class="ays-quiz-heading-box ays-quiz-unset-float ays-quiz-unset-margin">
                                    <div class="ays-quiz-wordpress-user-manual-box ays-quiz-wordpress-text-align">
                                        <a href="https://www.youtube.com/watch?v=nzQEHzmUBc8" target="_blank">
                                            <?php echo __("How question bank works - video", 'quiz-maker'); ?>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div> <!-- Enable question bank -->
                        <div class="form-group row">
                            <div class="col-sm-4">
                            </div>
                            <div class="col-sm-8">
                                <p class="ays_quiz_small_hint_text_for_message_variables">
                                    <span><?php echo __( "Note: If you are using a Cache plugin, make sure to exclude the URL, where the given quiz is located so that the feature can work correctly for you." , 'quiz-maker' ); ?></span>
                                </p>
                            </div>
                        </div>
                        <hr/>
                        <div class="form-group row ays_toggle_parent">
                            <div class="col-sm-4">
                                <label for="ays_enable_questions_ordering_by_cat">
                                   <?php echo __('Group questions by category','quiz-maker')?>
                                   <a class="ays_help" data-toggle="tooltip" title="<?php echo __('If the option is enabled, then selected questions for the given quiz, will be grouped based on categories. When the Enable randomize questions option is enabled too, then it will randomize both questions among categories and categories among quiz.','quiz-maker')?>">
                                        <i class="ays_fa ays_fa_info_circle"></i>
                                    </a>
                                </label>
                            </div>
                            <div class="col-sm-1">
                                <input type="checkbox" class="ays-enable-timerl ays_toggle_checkbox" id="ays_enable_questions_ordering_by_cat"
                                       name="ays_enable_questions_ordering_by_cat"
                                       value="on" <?php echo $enable_questions_ordering_by_cat ? 'checked' : ''; ?>/>
                            </div>
                            <div class="col-sm-7 ays_toggle_target ays_divider_left <?php echo ($enable_questions_ordering_by_cat) ? '' : 'display_none'; ?>">
                                <div class="form-group row">
                                    <div class="col-sm-4">
                                        <label class="form-check-label" for="ays_quiz_questions_numbering_by_category">
                                            <?php echo __('Enable questions numbering by category', 'quiz-maker'); ?>
                                            <a class="ays_help" data-toggle="tooltip"
                                            title="<?php echo __('Enable this option and the ordering for the question numbering will be by Category. By this, the question numbering will start from 1 for each category. Note: If you choose None for the Questions Numbering option, this feature will not work for you.', 'quiz-maker'); ?>">
                                                <i class="ays_fa ays_fa_info_circle"></i>
                                            </a>
                                        </label>
                                    </div>
                                    <div class="col-sm-8">
                                        <input type="checkbox" class="" id="ays_quiz_questions_numbering_by_category" name="ays_quiz_questions_numbering_by_category" value="on" <?php echo $quiz_questions_numbering_by_category ? 'checked' : ''; ?>>
                                    </div>
                                </div>
                            </div>
                        </div> <!-- Group questions by category -->
                        <hr/>
                        <div class="form-group row ays_toggle_parent">
                            <div class="col-sm-4" style="padding-right: 0px;">
                                <label for="ays_enable_correction">
                                    <?php echo esc_html__('Show correct answers','quiz-maker'); ?>
                                    <a class="ays_help" data-toggle="tooltip" title="<?php echo esc_attr__('Show if the selected answer is right or wrong with green and red marks. To decide when the right/wrong answers will be shown go to “Show messages for right/wrong answers option”.','quiz-maker'); ?> <?php echo esc_attr__('Visibility of specific response data may depend on the user’s familiarity with source-level structures.','quiz-maker'); ?>">
                                        <i class="ays_fa ays_fa_info_circle"></i>
                                    </a>
                                </label>
                            </div>
                            <div class="col-sm-1">
                                <input type="checkbox" class="ays-enable-timer1 ays_toggle_checkbox" id="ays_enable_correction"
                                       name="ays_enable_correction"
                                       value="on" <?php echo (isset($options['enable_correction']) && $options['enable_correction'] == 'on') ? 'checked' : ''; ?>/>
                            </div>
                            <div class="col-sm-7 ays_toggle_target ays_divider_left <?php echo (isset($options['enable_correction']) && $options['enable_correction'] == 'on') ? '' : 'display_none'; ?>">
                                <div class="form-group row">
                                    <div class="col-sm-4">
                                        <label class="form-check-label" for="ays_explanation_time">
                                            <?php echo __('Display duration of right/wrong answers (in seconds)', 'quiz-maker'); ?>
                                            <a class="ays_help" data-toggle="tooltip"
                                            title="<?php echo __('Display duration of right/wrong answers (in seconds) after answering the question.', 'quiz-maker'); ?>">
                                                <i class="ays_fa ays_fa_info_circle"></i>
                                            </a>
                                        </label>
                                    </div>
                                    <div class="col-sm-8">
                                        <div class="input-group mb-3">
                                            <input type="number" class="ays-text-input" id="ays_explanation_time" name="ays_explanation_time" value="<?php echo $explanation_time; ?>" placeholder="4">
                                        </div>
                                    </div>
                                </div>
                                <hr/>
                                <div class="form-group row">
                                    <div class="col-sm-4">
                                        <label class="form-check-label" for="ays_finish_after_wrong_answer">
                                            <?php echo __('Finish the quiz after one wrong answer', 'quiz-maker'); ?>
                                            <a class="ays_help" data-toggle="tooltip"
                                            title="<?php echo __('Finish the quiz after one wrong answer.', 'quiz-maker'); ?>">
                                                <i class="ays_fa ays_fa_info_circle"></i>
                                            </a>
                                        </label>
                                    </div>
                                    <div class="col-sm-8">
                                        <div class="input-group mb-3">
                                            <input type="checkbox" class="" id="ays_finish_after_wrong_answer" name="ays_finish_after_wrong_answer" value="on" <?php echo $finish_after_wrong_answer ? 'checked' : ''; ?>>
                                        </div>
                                    </div>
                                </div>
                                <hr>
                                <div class="form-group row">
                                    <div class="col-sm-4">
                                        <label class="form-check-label" for="ays_quiz_waiting_time">
                                            <?php echo __('Waiting time', 'quiz-maker'); ?>
                                            <a class="ays_help" data-toggle="tooltip"
                                            title="<?php echo __('Enable this option to inform the users the next question will be displayed after some seconds, like this 00:05. This option works with the Radio, Select, True/False question types and when the Next button is disabled for the quiz.', 'quiz-maker') ?>">
                                                <i class="ays_fa ays_fa_info_circle"></i>
                                            </a>
                                        </label>
                                    </div>
                                    <div class="col-sm-8">
                                        <div class="input-group mb-3">
                                            <input type="checkbox" class="" id="ays_quiz_waiting_time" name="ays_quiz_waiting_time" value="on" <?php echo $quiz_waiting_time ? 'checked' : ''; ?>>
                                        </div>
                                    </div>
                                </div>
                                <hr>
                                <div class="form-group row">
                                    <div class="col-sm-12 only_pro" style="padding:15px;">
                                        <div class="pro_features" style="align-items: flex-end;justify-content: flex-end;">                            

                                        </div>
                                        <div class="form-group row">
                                            <div class="col-sm-4">
                                                <label class="form-check-label" for="ays_show_only_wrong_answer">
                                                    <?php echo __('Show only wrong answers', 'quiz-maker'); ?>
                                                    <a class="ays_help" data-toggle="tooltip"
                                                    title="<?php echo __('If the user\'s chosen answer is wrong he/she won\'t see the right answer.', 'quiz-maker'); ?>">
                                                        <i class="ays_fa ays_fa_info_circle"></i>
                                                    </a>
                                                </label>
                                            </div>
                                            <div class="col-sm-8">
                                                <div class="input-group mb-3">
                                                    <input type="checkbox" class="" id="ays_show_only_wrong_answer" value="on">
                                                </div>
                                            </div>
                                        </div>
                                        <a href="https://ays-pro.com/wordpress/quiz-maker?utm_source=dashboard&utm_medium=quiz-free&utm_campaign=show-only-wrong-answer-<?php echo esc_attr( AYS_QUIZ_UTM_VERSION ); ?>" target="_blank" class="ays-quiz-new-upgrade-button-link">
                                            <div class="ays-quiz-new-upgrade-button-box">
                                                <div>
                                                    <img loading="lazy" src="<?php echo AYS_QUIZ_ADMIN_URL.'/images/icons/locked_24x24.svg'?>">
                                                    <img loading="lazy" src="<?php echo AYS_QUIZ_ADMIN_URL.'/images/icons/unlocked_24x24.svg'?>" class="ays-quiz-new-upgrade-button-hover">
                                                </div>
                                                <div class="ays-quiz-new-upgrade-button"><?php echo __("Upgrade", "quiz-maker"); ?></div>
                                            </div>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div> <!-- Show correct answers -->
                        <hr/>
                        <div class="form-group row">
                            <div class="col-sm-4">
                                <label for="ays_quiz_display_all_questions">
                                    <?php echo __('Display all questions on one page','quiz-maker'); ?>
                                    <a class="ays_help" data-toggle="tooltip" title="<?php echo __('Tick the checkbox if you want to show all your questions on one page.','quiz-maker'); ?>">
                                        <i class="ays_fa ays_fa_info_circle"></i>
                                    </a>
                                </label>
                            </div>
                            <div class="col-sm-8">
                                <input type="checkbox" class="ays-enable-timerl" id="ays_quiz_display_all_questions" name="ays_quiz_display_all_questions" value="on" <?php echo ( $quiz_display_all_questions ) ? 'checked' : ''; ?>/>
                            </div>
                        </div> <!-- Display all questions on one page -->
                        <hr/>
                        <div class="form-group row ays_toggle_parent">
                            <div class="col-sm-4">
                                <label for="active_date_check">
                                    <?php echo __('Schedule the quiz', 'quiz-maker'); ?>
                                    <a class="ays_help" data-toggle="tooltip"
                                       title="<?php echo __('The period of time when quiz will be active. When the date is out the expiration message will be shown.', 'quiz-maker'); ?>">
                                        <i class="ays_fa ays_fa_info_circle"></i>
                                    </a>
                                </label>
                            </div>
                            <div class="col-sm-1">
                                <input id="active_date_check" type="checkbox" class="active_date_check ays_toggle_checkbox"
                                        name="active_date_check" <?php echo $active_date_check ? 'checked' : '' ?>>
                            </div>
                            <div class="col-sm-7 ays_toggle_target ays_divider_left active_date <?php echo $active_date_check ? '' : 'display_none' ?>">
                                <div class="form-group row">
                                    <div class="col-sm-4">
                                        <label class="form-check-label" for="ays-active"> <?php echo __('Start date:', 'quiz-maker'); ?> </label>
                                    </div>
                                    <div class="col-sm-8">
                                        <div class="input-group mb-3">
                                            <input type="text" class="ays-text-input ays-text-input-short" id="ays-active" name="ays-active"
                                               value="<?php echo $activeQuiz; ?>" placeholder="<?php echo current_time( 'mysql' ); ?>">
                                            <div class="input-group-append">
                                                <label for="ays-active" class="input-group-text">
                                                    <span><i class="ays_fa ays_fa_calendar"></i></span>
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <div class="col-sm-4">
                                        <label class="form-check-label" for="ays-deactive"> <?php echo __('End date:', 'quiz-maker'); ?> </label>
                                    </div>
                                    <div class="col-sm-8">
                                        <div class="input-group mb-3">
                                            <input type="text" class="ays-text-input ays-text-input-short" id="ays-deactive" name="ays-deactive"
                                               value="<?php echo $deactiveQuiz; ?>" placeholder="<?php echo current_time( 'mysql' ); ?>">
                                            <div class="input-group-append">
                                                <label for="ays-deactive" class="input-group-text">
                                                    <span><i class="ays_fa ays_fa_calendar"></i></span>
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <hr> <!--Show timer start -->
                                <div class="form-group row">
                                    <div class="col-sm-3">
                                        <label for='ays_quiz_show_timer'>
                                            <?php echo esc_html__('Show timer', 'quiz-maker'); ?>
                                            <a class="ays_help" data-toggle="tooltip"
                                               data-placement="top"
                                               title="<?php echo esc_attr( __("Show the countdown or end date time in the quiz.", 'quiz-maker') ); ?>">
                                                <i class="ays_fa ays_fa_info_circle"></i>
                                            </a>
                                        </label>
                                    </div>
                                    <div class="col-sm-1">
                                        <input type="checkbox" name="ays_quiz_show_timer" id="ays_quiz_show_timer"
                                               value="on" <?php echo $schedule_show_timer ? 'checked' : ''; ?> >
                                    </div>
                                    <div class="col-sm-8 ays-quiz-show-timer-mobile-style">
                                        <label class="ays_quiz_loader" for="show_time_countdown">
                                           <input type="radio" id="show_time_countdown" name="ays_show_timer_type" value="countdown" <?php echo $show_timer_type == 'countdown' ? 'checked' : ''; ?> />
                                           <span><?php echo esc_html__('Show countdown', 'quiz-maker'); ?></span>
                                        </label>
                                        <label class="ays_quiz_loader" for="show_time_enddate">
                                           <input type="radio" id="show_time_enddate" name="ays_show_timer_type"
                                           value="enddate" <?php echo $show_timer_type == 'enddate' ? 'checked' : ''; ?> />
                                           <span><?php echo esc_html__('Show start date', 'quiz-maker'); ?></span>
                                        </label>
                                    </div>
                                </div> <!--Show timer end-->
                                <hr>
                                <div class="form-group row">
                                    <div class="col-sm-4">
                                        <label for="ays_quiz_dont_show_quiz">
                                            <?php echo esc_html__("Don't show quiz", 'quiz-maker'); ?>
                                            <a class="ays_help" data-toggle="tooltip" title="<?php echo esc_html__('Do not show the quiz container on the front-end at all when it is expired or has not started yet.', 'quiz-maker'); ?>">
                                                <i class="ays_fa ays_fa_info_circle"></i>
                                            </a>
                                        </label>
                                    </div>
                                    <div class="col-sm-8">
                                        <input type="checkbox" name="ays_quiz_dont_show_quiz" id="ays_quiz_dont_show_quiz" value="on" <?php echo $quiz_dont_show_quiz ? 'checked' : ''; ?>>
                                    </div>
                                </div>
                                <hr>
                                <div class="form-group row">
                                    <div class="col-sm-4">
                                        <label for='ays_quiz_schedule_timezone'>
                                            <?php echo __('Timezone', 'quiz-maker'); ?>
                                            <a class="ays_help" data-toggle="tooltip" title="<?php echo __('Choose the right timezone based on the coordinates of your quiz takers.','quiz-maker');?>">
                                                <i class="ays_fa ays_fa_info_circle"></i>
                                            </a>
                                        </label>
                                    </div>
                                    <div class="col-sm-8">
                                        <select class="ays-text-input" name="ays_quiz_schedule_timezone" id="ays_quiz_schedule_timezone">
                                            <?php echo wp_timezone_choice( $ays_quiz_schedule_timezone, get_user_locale() ); ?>
                                        </select>
                                    </div>
                                </div>
                                <hr>
                                <div class="form-group row ays-quiz-result-message-vars-parent">
                                    <div class="col-sm-4">
                                        <label class="form-check-label" for="active_date_pre_start_message"><?php echo __("Pre-start message", 'quiz-maker'); ?></label>
                                    </div>
                                    <div class="col-sm-8">
                                        <div class="editor">
                                            <?php
                                            echo $quiz_message_vars_schedule_pre_start_message_html;
                                            $content   = isset($options['active_date_pre_start_message']) ? stripslashes($options['active_date_pre_start_message']) : __("The quiz will be available soon!", 'quiz-maker');
                                            $editor_id = 'active_date_pre_start_message';
                                            $settings  = array(
                                                'editor_height'  => $quiz_wp_editor_height,
                                                'textarea_name'  => 'active_date_pre_start_message',
                                                'editor_class'   => 'ays-textarea',
                                                'media_elements' => false
                                            );
                                            wp_editor($content, $editor_id, $settings);
                                            ?>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <div class="col-sm-4">
                                        <label class="form-check-label" for="active_date_message"><?php echo __("Expiration message:", 'quiz-maker') ?></label>
                                    </div>
                                    <div class="col-sm-8">
                                        <div class="editor">
                                            <?php
                                            $content   = isset($options['active_date_message']) ? stripslashes($options['active_date_message']) : __("This quiz has expired!", 'quiz-maker');
                                            $editor_id = 'active_date_message';
                                            $settings  = array(
                                                'editor_height'  => $quiz_wp_editor_height,
                                                'textarea_name'  => 'active_date_message',
                                                'editor_class'   => 'ays-textarea',
                                                'media_elements' => false
                                            );
                                            wp_editor($content, $editor_id, $settings);
                                            ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div> <!-- Schedule the quiz -->
                        <hr/>
                        <div class="form-group row">
                            <div class="col-sm-4">
                                <label>
                                    <?php echo __('Show question explanation','quiz-maker'); ?>
                                    <a class="ays_help" data-toggle="tooltip" title="<?php echo __('Specify where to display questions explanation. Note that the “Show correct answers” option should be enabled. In order to make this option work, you need to add the corresponding texts from the Edit page of the particular question.','quiz-maker'); ?>">
                                        <i class="ays_fa ays_fa_info_circle"></i>
                                    </a>
                                </label>
                            </div>
                            <div class="col-sm-8">
                                <label class="ays_quiz_loader">
                                    <input type="radio" class="ays-enable-timer1" name="ays_show_questions_explanation" value="on_passing" <?php echo ($show_questions_explanation == 'on_passing') ? 'checked' : '' ?>/>
                                    <span><?php echo __( "During the quiz", 'quiz-maker' ); ?></span>
                                </label>
                                <label class="ays_quiz_loader">
                                    <input type="radio" class="ays-enable-timer1" name="ays_show_questions_explanation" value="on_results_page" <?php echo ($show_questions_explanation == 'on_results_page') ? 'checked' : '' ?>/>
                                    <span><?php echo __( "On results page", 'quiz-maker' ); ?></span>
                                </label>
                                <label class="ays_quiz_loader">
                                    <input type="radio" class="ays-enable-timer1" name="ays_show_questions_explanation" value="on_both" <?php echo ($show_questions_explanation == 'on_both') ? 'checked' : '' ?>/>
                                    <span><?php echo __( "On Both", 'quiz-maker' ); ?></span>
                                </label>
                                <label class="ays_quiz_loader">
                                    <input type="radio" class="ays-enable-timer1" name="ays_show_questions_explanation" value="disable" <?php echo ($show_questions_explanation == 'disable') ? 'checked' : '' ?>/>
                                    <span><?php echo __( "Disable", 'quiz-maker' ); ?></span>
                                </label>
                            </div>
                        </div> <!-- Show question explanation -->
                        <hr/>
                        <div class="form-group row">
                            <div class="col-sm-4">
                                <label for="ays_show_questions_numbering">
                                    <?php echo __('Questions numbering','quiz-maker'); ?>
                                    <a class="ays_help" data-toggle="tooltip" title="<?php echo __('Assign numbering to each question in ascending sequential order. Choose your preferred type from the list.','quiz-maker'); ?>">
                                        <i class="ays_fa ays_fa_info_circle"></i>
                                    </a>
                                </label>
                            </div>
                            <div class="col-sm-8">
                                <select name="ays_show_questions_numbering" class="ays-text-input ays-text-input-short" id="ays_show_questions_numbering">
                                    <option value="none" <?php echo ($show_questions_numbering == 'none') ? 'selected' : ''; ?> ><?php echo __( "None", 'quiz-maker' ); ?></option>
                                    <option value="1." <?php echo ($show_questions_numbering == '1.') ? 'selected' : ''; ?> ><?php echo __( "1.", 'quiz-maker' ); ?></option>
                                    <option value="1)" <?php echo ($show_questions_numbering == '1)') ? 'selected' : ''; ?> ><?php echo __( "1)", 'quiz-maker' ); ?></option>
                                    <option value="A." <?php echo ($show_questions_numbering == 'A.') ? 'selected' : ''; ?> ><?php echo __( "A.", 'quiz-maker' ); ?></option>
                                    <option value="A)" <?php echo ($show_questions_numbering == 'A)') ? 'selected' : ''; ?> ><?php echo __( "A)", 'quiz-maker' ); ?></option>
                                    <option value="a." <?php echo ($show_questions_numbering == 'a.') ? 'selected' : ''; ?> ><?php echo __( "a.", 'quiz-maker' ); ?></option>
                                    <option value="a)" <?php echo ($show_questions_numbering == 'a)') ? 'selected' : ''; ?> ><?php echo __( "a)", 'quiz-maker' ); ?></option>
                                </select>
                            </div>
                        </div> <!-- Show questions numbering -->
                        <?php if( 1 == 0 ): ?>
                        <hr/>
                        <div class="form-group row" style="margin:0px;">
                            <div class="col-sm-12 only_pro" style="padding:10px 0 0 10px;">
                                <div class="pro_features" style="justify-content:flex-end;">

                                </div>
                                <div class="form-group row">
                                    <div class="col-sm-4">
                                        <label for="ays_enable_copy_protection">
                                            <?php echo __('Enable copy protection','quiz-maker')?>
                                            <a class="ays_help" data-toggle="tooltip" title="<?php echo __('Disable copy functionality in quiz page(CTRL+C) and Right-click','quiz-maker')?>">
                                                <i class="ays_fa ays_fa_info_circle"></i>
                                            </a>
                                        </label>
                                    </div>
                                    <div class="col-sm-8">
                                        <input type="checkbox" class="ays-enable-timer1" id="ays_enable_copy_protection" value="on" tabindex="-1"/>
                                    </div>
                                </div>
                                <a href="https://ays-pro.com/wordpress/quiz-maker?utm_source=dashboard&utm_medium=quiz-free&utm_campaign=enable-copy-protection-<?php echo esc_attr( AYS_QUIZ_UTM_VERSION ); ?>" target="_blank" class="ays-quiz-new-upgrade-button-link">
                                    <div class="ays-quiz-new-upgrade-button-box">
                                        <div>
                                            <img loading="lazy" src="<?php echo AYS_QUIZ_ADMIN_URL.'/images/icons/locked_24x24.svg'?>">
                                            <img loading="lazy" src="<?php echo AYS_QUIZ_ADMIN_URL.'/images/icons/unlocked_24x24.svg'?>" class="ays-quiz-new-upgrade-button-hover">
                                        </div>
                                        <div class="ays-quiz-new-upgrade-button"><?php echo __("Upgrade to Developer/Agency", "quiz-maker"); ?></div>
                                    </div>
                                </a>
                            </div>
                        </div> <!-- Enable copy protection -->
                        <?php endif; ?>
                        <hr/>
                        <div class="form-group row ays_toggle_parent">
                            <div class="col-sm-4" style="padding-right: 0px;">
                                <label for="ays_show_question_category">
                                    <?php echo __('Show question category','quiz-maker')?>
                                    <a class="ays_help" data-toggle="tooltip" title="<?php echo __('Show question category in each question.','quiz-maker')?>">
                                        <i class="ays_fa ays_fa_info_circle"></i>
                                    </a>
                                </label>
                            </div>
                            <div class="col-sm-1">
                                <input type="checkbox" class="ays-enable-timer1 ays_toggle_checkbox" id="ays_show_question_category" name="ays_show_question_category" value="on" <?php echo ($show_question_category) ? 'checked' : ''; ?>/>
                            </div>
                            <div class="col-sm-7 ays_toggle_target ays_divider_left <?php echo ( $show_question_category ) ? '' : 'display_none'; ?>">
                                <div class="form-group row">
                                    <div class="col-sm-4">
                                        <label for="ays_quiz_enable_question_category_description">
                                            <?php echo __('Show question category description','quiz-maker'); ?>
                                            <a class="ays_help" data-toggle="tooltip" title="<?php echo __('Show question category description for each question.','quiz-maker'); ?>">
                                                <i class="ays_fa ays_fa_info_circle"></i>
                                            </a>
                                        </label>
                                    </div>
                                    <div class="col-sm-8">
                                        <input type="checkbox" name="ays_quiz_enable_question_category_description" id="ays_quiz_enable_question_category_description" <?php echo ($quiz_enable_question_category_description) ? 'checked' : ''; ?>/>
                                    </div>
                                </div>
                            </div>
                        </div> <!-- Show question category -->
                        <hr>
                        <div class="form-group row">
                            <div class="col-sm-4">
                                <label for="ays_main_quiz_url">
                                    <?php echo __('Quiz Display Page URL','quiz-maker')?>
                                    <a class="ays_help" data-toggle="tooltip" title="<?php echo __('Copy and Paste the link of the page where your quiz is displayed. This option is for easily detecting where your quiz is displayed.','quiz-maker'); ?>">
                                        <i class="ays_fa ays_fa_info_circle"></i>
                                    </a>
                                </label>
                            </div>
                            <div class="col-sm-8">
                                <input type="text" class="ays-text-input" id="ays_main_quiz_url" name="ays_main_quiz_url" value="<?php echo $main_quiz_url; ?>"/>
                            </div>
                        </div> <!-- Quiz Display Page URL -->
                        <hr>
                        <div class="form-group row">
                            <div class="col-sm-4">
                                <label for="ays_enable_full_screen_mode">
                                    <?php echo __('Enable full-screen mode','quiz-maker')?>
                                    <a class="ays_help" data-toggle="tooltip" title="<?php echo __('Allow the quiz takers to enter full-screen mode by pressing the icon located in the top-right corner of the quiz container.','quiz-maker')?>">
                                        <i class="ays_fa ays_fa_info_circle"></i>
                                    </a>
                                </label>
                            </div>
                            <div class="col-sm-8">
                                <input type="checkbox" class="ays-enable-timer1" id="ays_enable_full_screen_mode"
                                       name="ays_enable_full_screen_mode" value="on" <?php echo $enable_full_screen_mode ? 'checked' : '' ?>/>
                            </div>
                        </div> <!-- Open Full Screen Mode -->
                        <hr/>
                        <div class="form-group row ays_toggle_parent">
                            <div class="col-sm-12">
                                <div class="form-group row" style="margin-bottom: 0;">
                                    <div class="col-sm-4">
                                        <label>
                                            <?php echo __('Hint icon','quiz-maker'); ?>
                                            <a class="ays_help" data-toggle="tooltip" title="<?php echo __('Choose either the default symbol or your preferred text for the hint button. In order to make this option work, you need to add the corresponding texts from the Edit page of the particular question.','quiz-maker'); ?>">
                                                <i class="ays_fa ays_fa_info_circle"></i>
                                            </a>
                                        </label>
                                    </div>
                                    <div class="col-sm-8">
                                        <label class="ays_quiz_loader">
                                            <input type="radio" class="ays-enable-timer1 ays_toggle_questions_hint_radio" data-flag="false" data-type="default" name="ays_questions_hint_icon_or_text" value="default" <?php echo ($questions_hint_icon_or_text == 'default') ? 'checked' : '' ?>/>
                                            <span>
                                                <?php echo __( "Default", 'quiz-maker' ); ?>
                                                <i class="ays_fa ays_fa_info_circle ays_question_hint" aria-hidden="true"> </i>
                                            </span>
                                        </label>
                                        <label class="ays_quiz_loader">
                                            <input type="radio" class="ays-enable-timer1 ays_toggle_questions_hint_radio" data-flag="true" data-type="text" name="ays_questions_hint_icon_or_text" value="text" <?php echo ($questions_hint_icon_or_text == 'text') ? 'checked' : '' ?>/>
                                            <span><?php echo __( "Custom text", 'quiz-maker' ); ?></span>
                                        </label>
                                        <label class="ays_quiz_loader">
                                            <input type="radio" class="ays-enable-timer1 ays_toggle_questions_hint_radio" data-flag="true" data-type="button" name="ays_questions_hint_icon_or_text" value="button" <?php echo ($questions_hint_icon_or_text == 'button') ? 'checked' : '' ?>/>
                                            <span><?php echo __( "Button", 'quiz-maker' ); ?></span>
                                        </label>
                                        <label class="ays_quiz_loader">
                                            <input type="radio" class="ays-enable-timer1 ays_toggle_questions_hint_radio" data-flag="false" data-type="hide" name="ays_questions_hint_icon_or_text" value="hide" <?php echo ($questions_hint_icon_or_text == 'hide') ? 'checked' : '' ?>/>
                                            <span>
                                                <?php echo __( "Hide", 'quiz-maker' ); ?>
                                            </span>
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-12">
                                <div class="form-group row" style="margin-bottom: 0;">
                                    <div class="col-sm-4">
                                    </div>
                                    <div class="col-sm-8" style="padding: unset;">
                                        <div data-type="button" class="col-sm-5 ays_padding_unset ays_toggle_target <?php echo ($questions_hint_icon_or_text == 'button') ? '' : 'display_none' ?>">
                                            <input type="text" class="ays-text-input" placeholder="<?php echo __( "Button text", 'quiz-maker' ); ?>" name="ays_questions_hint_button_value" value="<?php echo $questions_hint_button_value; ?>">
                                        </div>
                                        <div data-type="text" class="col-sm-5 ays_padding_unset ays_toggle_target <?php echo ($questions_hint_icon_or_text == 'text') ? '' : 'display_none' ?>">
                                            <input type="text" class="ays-text-input" placeholder="<?php echo __( "Custom text", 'quiz-maker' ); ?>" name="ays_questions_hint_value" value="<?php echo $questions_hint_value; ?>">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div> <!-- Hint icon -->
                        <hr>
                        <div class="form-group row" style="margin:0px;">
                            <div class="col-sm-12 only_pro" style="padding:10px 0 0 10px;">
                                <div class="pro_features" style="justify-content:flex-end;">

                                </div>
                                <div class="form-group row">
                                    <div class="col-sm-4">
                                        <label for="ays_payment_type">
                                            <?php echo __('Payment Type','quiz-maker'); ?>
                                            <a class="ays_help" data-toggle="tooltip" data-html="true"
                                                title="<?php
                                                    echo __('Select the time when the quiz taker will need to pay to pass the quiz:','quiz-maker') .
                                                    "<ul style='list-style-type: circle;padding-left: 20px;'>".
                                                        "<li>". __('Prepay: The quiz taker will be allowed to pass the quiz only after paying.','quiz-maker') ."</li>".
                                                        "<li>". __('Postpay: The quiz taker will be able to see the results of his/her quiz only after doing a payment. That means that they could pass the quiz but would not be allowed to get results, emails, or certificates until they pay. Besides, if you set the payment type as Postpay the only payment term that will be available is Onetime payment․','quiz-maker') ."</li>".
                                                    "</ul>";
                                                ?>">
                                                <i class="ays_fa ays_fa_info_circle"></i>
                                            </a>
                                        </label>
                                    </div>
                                    <div class="col-sm-8">
                                        <select name="ays_payment_type" class="ays-text-input ays-text-input-short" id="ays_payment_type" tabindex="-1">
                                            <option><?php echo __( "Prepay", 'quiz-maker' ); ?></option>
                                            <option><?php echo __( "Postpay", 'quiz-maker' ); ?></option>
                                        </select>
                                    </div>
                                </div>
                                <a href="https://ays-pro.com/wordpress/quiz-maker?utm_source=dashboard&utm_medium=quiz-free&utm_campaign=payment-type-<?php echo esc_attr( AYS_QUIZ_UTM_VERSION ); ?>" target="_blank" class="ays-quiz-new-upgrade-button-link">
                                    <div class="ays-quiz-new-upgrade-button-box">
                                        <div>
                                            <img loading="lazy" src="<?php echo AYS_QUIZ_ADMIN_URL.'/images/icons/locked_24x24.svg'?>">
                                            <img loading="lazy" src="<?php echo AYS_QUIZ_ADMIN_URL.'/images/icons/unlocked_24x24.svg'?>" class="ays-quiz-new-upgrade-button-hover">
                                        </div>
                                        <div class="ays-quiz-new-upgrade-button"><?php echo __("Upgrade", "quiz-maker"); ?></div>
                                    </div>
                                </a>
                            </div>
                        </div> <!-- Payment Type -->
                        <hr/>
                        <div class="form-group row" style="margin:0px;">
                            <div class="col-sm-12 only_pro" style="padding:10px 0 0 10px;">
                                <div class="pro_features" style="justify-content:flex-end;">

                                </div>
                                <div class="form-group row">
                                    <div class="col-sm-4">
                                        <label class="ays-disable-setting">
                                            <?php echo __('Question count per page','quiz-maker')?>
                                            <a class="ays_help" data-toggle="tooltip" title="<?php echo __('Allow more than one question per page','quiz-maker')?>">
                                                <i class="ays_fa ays_fa_info_circle"></i>
                                            </a>
                                        </label>
                                    </div>
                                    <div class="col-sm-1">
                                        <input type="checkbox" disabled>
                                    </div>
                                    <div class="col-sm-7">
                                        <div class="form-group row">
                                            <div class="col-sm-12">
                                                <label class="ays_quiz_loader">
                                                    <input type="radio" class="ays-enable-timer1" checked/>
                                                    <span><?php echo __( "General", 'quiz-maker' ); ?></span>
                                                </label>
                                                <label class="ays_quiz_loader">
                                                    <input type="radio" class="ays-enable-timer1" />
                                                    <span><?php echo __( "Custom", 'quiz-maker' ); ?></span>
                                                </label>
                                            </div>
                                        </div>
                                        <hr/>
                                        <div class="form-group row">
                                            <div class="col-sm-3">
                                                <label class="ays-disable-setting">
                                                    <?php echo __('Questions count','quiz-maker')?>
                                                </label>
                                            </div>
                                            <div class="col-sm-9">
                                                <input type="number" class="ays-text-input" tabindex="-1" disabled>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <a href="https://ays-pro.com/wordpress/quiz-maker?utm_source=dashboard&utm_medium=quiz-free&utm_campaign=question-count-per-page-<?php echo esc_attr( AYS_QUIZ_UTM_VERSION ); ?>" target="_blank" class="ays-quiz-new-upgrade-button-link">
                                    <div class="ays-quiz-new-upgrade-button-box">
                                        <div>
                                            <img loading="lazy" src="<?php echo AYS_QUIZ_ADMIN_URL.'/images/icons/locked_24x24.svg'?>">
                                            <img loading="lazy" src="<?php echo AYS_QUIZ_ADMIN_URL.'/images/icons/unlocked_24x24.svg'?>" class="ays-quiz-new-upgrade-button-hover">
                                        </div>
                                        <div class="ays-quiz-new-upgrade-button"><?php echo __("Upgrade", "quiz-maker"); ?></div>
                                    </div>
                                </a>
                            </div>
                        </div> <!-- Question count per page -->
                        <hr/>
                        <div class="form-group row" style="margin:0px;">
                            <div class="col-sm-12 only_pro" style="padding:10px 0 0 10px;">
                                <div class="pro_features pro_features_popup">
                                    <div class="pro-features-popup-conteiner">
                                        <div class="pro-features-popup-title">
                                            <?php echo __("Navigation Bar", 'quiz-maker'); ?>
                                        </div>
                                        <div class="pro-features-popup-content" data-link="https://youtu.be/pf-HSumxP3Y">
                                            <p>
                                                <?php echo sprintf( __("Add a navigation bar at the top of your Quiz and allow the users to %s move into the questions easily. %s Give them to pass the hard questions and get back to it whenever they will have the answer.", 'quiz-maker'),
                                                    "<strong>",
                                                    "</strong>"
                                                ); ?>
                                            </p>
                                            <p>
                                                <?php echo __("After answering the question, the box will change its color by that making it easier for the user to see his/her progress.", 'quiz-maker'); ?>
                                            </p>
                                            <div>
                                                <a href="https://quiz-plugin.com/english-exam-with-certificate/" target="_blank"><?php echo __("See Demo", 'quiz-maker'); ?></a>
                                            </div>
                                        </div>
                                        <div class="pro-features-popup-button" data-link="https://ays-pro.com/wordpress/quiz-maker?utm_source=dashboard&utm_medium=quiz-free&utm_campaign=pro-popup-navigation-bar-<?php echo esc_attr( AYS_QUIZ_UTM_VERSION ); ?>">
                                            <?php echo __("Pricing", 'quiz-maker'); ?>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <div class="col-sm-4">
                                        <label for="ays_enable_navigation_bar">
                                           <?php echo __('Enable navigation bar','quiz-maker'); ?>
                                           <a class="ays_help" data-toggle="tooltip" title="<?php echo __('Activate the quiz navigation box in the upper of the questions․ It helps to move back and forth between questions easily. After answering a question, its box becomes black and indicates that you have answered it already.Please note that it does not work with the Questions count per page and Display all questions on one page options.', 'quiz-maker'); ?>">
                                                <i class="ays_fa ays_fa_info_circle"></i>
                                            </a>
                                        </label>
                                    </div>
                                    <div class="col-sm-1">
                                        <input type="checkbox" class="ays-enable-timerl" value="on" tabindex="-1"/>
                                    </div>
                                    <div class="col-sm-7 ays_toggle_target ays_divider_left">
                                        <div class="form-group row">
                                            <div class="col-sm-4">
                                                <label for="ays_enable_navigation_bar_marked_questions">
                                                    <?php echo __('Question marking','quiz-maker')?>
                                                    <a class="ays_help" data-toggle="tooltip" title="<?php echo __('Enable question bookmarking for the navigation bar.','quiz-maker')?>">
                                                        <i class="ays_fa ays_fa_info_circle"></i>
                                                    </a>
                                                </label>
                                            </div>
                                            <div class="col-sm-8">
                                                <input type="checkbox" class="ays-enable-timerl" value="on" tabindex="-1" />
                                            </div>
                                        </div>
                                    </div>
                                </div> <!-- Enable navigation bar -->
                                <a href="https://ays-pro.com/wordpress/quiz-maker?utm_source=dashboard&utm_medium=quiz-free&utm_campaign=pro-popup-navigation-bar-<?php echo esc_attr( AYS_QUIZ_UTM_VERSION ); ?>" target="_blank" class="ays-quiz-new-upgrade-button-link">
                                    <div class="ays-quiz-new-upgrade-button-box">
                                        <div>
                                            <img loading="lazy" src="<?php echo AYS_QUIZ_ADMIN_URL.'/images/icons/locked_24x24.svg'?>">
                                            <img loading="lazy" src="<?php echo AYS_QUIZ_ADMIN_URL.'/images/icons/unlocked_24x24.svg'?>" class="ays-quiz-new-upgrade-button-hover">
                                        </div>
                                        <div class="ays-quiz-new-upgrade-button"><?php echo __("Upgrade", "quiz-maker"); ?></div>
                                    </div>
                                </a>
                                <div class="ays-quiz-new-watch-video-button-box">
                                    <div>
                                        <img loading="lazy" src="<?php echo AYS_QUIZ_ADMIN_URL.'/images/icons/video_24x24.svg'?>">
                                        <img loading="lazy" src="<?php echo AYS_QUIZ_ADMIN_URL.'/images/icons/video_24x24_hover.svg'?>" class="ays-quiz-new-watch-video-button-hover">
                                    </div>
                                    <div class="ays-quiz-new-watch-video-button"><?php echo __("Watch Video", "quiz-maker"); ?></div>
                                </div>
                            </div>
                        </div> <!-- Navigation Bar -->
                        <hr/>
                        <div class="form-group row" style="margin:0px;">
                            <div class="col-sm-12 only_pro" style="padding:10px 0 0 10px;">
                                <div class="pro_features" style="justify-content:flex-end;">

                                </div>
                                <div class="form-group row">
                                    <div class="col-sm-4">
                                        <label for="ays_make_questions_required">
                                            <?php echo __('Make the questions required','quiz-maker')?>
                                            <a class="ays_help" data-toggle="tooltip" title="<?php echo __('If the user doesn\'t answer the question he/she can\'t go to the next question.','quiz-maker')?>">
                                                <i class="ays_fa ays_fa_info_circle"></i>
                                            </a>
                                        </label>
                                    </div>
                                    <div class="col-sm-8">
                                        <input type="checkbox" class="ays-enable-timer1" tabindex="-1" id="ays_make_questions_required"
                                               value="on"/>
                                    </div>
                                </div>
                                <a href="https://ays-pro.com/wordpress/quiz-maker?utm_source=dashboard&utm_medium=quiz-free&utm_campaign=make-the-questions-required-<?php echo esc_attr( AYS_QUIZ_UTM_VERSION ); ?>" target="_blank" class="ays-quiz-new-upgrade-button-link">
                                    <div class="ays-quiz-new-upgrade-button-box">
                                        <div>
                                            <img loading="lazy" src="<?php echo AYS_QUIZ_ADMIN_URL.'/images/icons/locked_24x24.svg'?>">
                                            <img loading="lazy" src="<?php echo AYS_QUIZ_ADMIN_URL.'/images/icons/unlocked_24x24.svg'?>" class="ays-quiz-new-upgrade-button-hover">
                                        </div>
                                        <div class="ays-quiz-new-upgrade-button"><?php echo __("Upgrade", "quiz-maker"); ?></div>
                                    </div>
                                </a>
                            </div>
                        </div> <!-- Make the questions required -->
                        <hr/>
                        <div class="form-group row" style="margin:0px;">
                            <div class="col-sm-12 only_pro" style="padding:10px 0 0 10px;">
                                <div class="pro_features" style="justify-content:flex-end;">

                                </div>
                                <div class="form-group row">
                                    <div class="col-sm-4">
                                        <label>
                                            <?php echo __('Enable text to speech','quiz-maker')?>
                                            <a class="ays_help" data-toggle="tooltip" title="<?php echo __('Enable this option to allow listening to the questions being read aloud. Note this option can be used only for questions.','quiz-maker')?>">
                                                <i class="ays_fa ays_fa_info_circle"></i>
                                            </a>
                                        </label>
                                    </div>
                                    <div class="col-sm-1">
                                        <input type="checkbox" class="ays-enable-timer1" tabindex="-1" />
                                    </div>
                                    <div class="col-sm-7 ays_toggle_target ays_divider_left">
                                        <div class="form-group row">
                                            <div class="col-sm-4">
                                                <label>
                                                    <?php echo __('Select language (voice)','quiz-maker')?>
                                                    <a class="ays_help" data-toggle="tooltip" title="<?php echo __('Select the language and voice for the text to speech option. Note: The list may vary depending on your browser.','quiz-maker')?>">
                                                        <i class="ays_fa ays_fa_info_circle"></i>
                                                    </a>
                                                </label>
                                            </div>
                                            <div class="col-sm-8">
                                                <select class="ays-text-input ays-text-input-short">
                                                    <option><?php echo __('Select','quiz-maker'); ?></option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div> <!-- Questions text to speech -->
                                <a href="https://ays-pro.com/wordpress/quiz-maker?utm_source=dashboard&utm_medium=quiz-free&utm_campaign=text-to-speech-<?php echo esc_attr( AYS_QUIZ_UTM_VERSION ); ?>" target="_blank" class="ays-quiz-new-upgrade-button-link">
                                    <div class="ays-quiz-new-upgrade-button-box">
                                        <div>
                                            <img loading="lazy" src="<?php echo AYS_QUIZ_ADMIN_URL.'/images/icons/locked_24x24.svg'?>">
                                            <img loading="lazy" src="<?php echo AYS_QUIZ_ADMIN_URL.'/images/icons/unlocked_24x24.svg'?>" class="ays-quiz-new-upgrade-button-hover">
                                        </div>
                                        <div class="ays-quiz-new-upgrade-button"><?php echo __("Upgrade", "quiz-maker"); ?></div>
                                    </div>
                                </a>
                            </div>
                        </div> <!-- Enable text to speech -->
                        <hr/>
                        <div class="form-group row" style="margin:0px;">
                            <div class="col-sm-12 only_pro" style="padding:10px 0 0 10px;">
                                <div class="pro_features" style="justify-content:flex-end;">

                                </div>
                                <div class="form-group row">
                                    <div class="col-sm-4" style="padding-right: 0px;">
                                        <label>
                                            <?php echo __('Show Question Max Points', 'quiz-maker'); ?>
                                            <a class="ays_help" data-toggle="tooltip" title="<?php echo esc_attr( __('Enable this option to display the maximum points for each question during the quiz.', 'quiz-maker') ); ?>">
                                                <i class="ays_fa ays_fa_info_circle"></i>
                                            </a>
                                        </label>
                                    </div>
                                    <div class="col-sm-8">
                                        <input type="checkbox" value="on" />
                                    </div>
                                </div>
                                <a href="https://ays-pro.com/wordpress/quiz-maker?utm_source=dashboard&utm_medium=quiz-free&utm_campaign=show-question-max-points-<?php echo esc_attr( AYS_QUIZ_UTM_VERSION ); ?>" target="_blank" class="ays-quiz-new-upgrade-button-link">
                                    <div class="ays-quiz-new-upgrade-button-box">
                                        <div>
                                            <img loading="lazy" src="<?php echo AYS_QUIZ_ADMIN_URL.'/images/icons/locked_24x24.svg'?>">
                                            <img loading="lazy" src="<?php echo AYS_QUIZ_ADMIN_URL.'/images/icons/unlocked_24x24.svg'?>" class="ays-quiz-new-upgrade-button-hover">
                                        </div>
                                        <div class="ays-quiz-new-upgrade-button"><?php echo __("Upgrade", "quiz-maker"); ?></div>
                                    </div>
                                </a>
                            </div>
                        </div> <!-- Show question max point -->
                        <hr/>
                    </div>
                </div>
                <div class="ays-quiz-accordion-options-main-container" data-collapsed="false">
                    <div class="ays-quiz-accordion-container">
                        <?php echo $quiz_accordion_svg_html; ?>
                        <p class="ays-subtitle"><?php echo __('Answer Settings','quiz-maker')?></p>
                    </div>
                    <hr class="ays-quiz-bolder-hr"/>
                    <div class="ays-quiz-accordion-options-box">
                        <div class="form-group row">
                            <div class="col-sm-4">
                                <label for="ays_show_answers_numbering">
                                    <?php echo __('Answers numbering','quiz-maker'); ?>
                                    <a class="ays_help" data-toggle="tooltip" title="<?php echo __('Assign numbering to each answer in ascending sequential order. Choose your preferred type from the list.','quiz-maker'); ?>">
                                        <i class="ays_fa ays_fa_info_circle"></i>
                                    </a>
                                </label>
                            </div>
                            <div class="col-sm-8">
                                <select class="ays-text-input ays-text-input-short" name="ays_show_answers_numbering" id="ays_show_answers_numbering">
                                    <option <?php echo $show_answers_numbering == "none" ? "selected" : ""; ?> value="none"><?php echo __( "None", 'quiz-maker'); ?></option>
                                    <option <?php echo $show_answers_numbering == "1." ? "selected" : ""; ?> value="1."><?php echo __( "1.", 'quiz-maker'); ?></option>
                                    <option <?php echo $show_answers_numbering == "1)" ? "selected" : ""; ?> value="1)"><?php echo __( "1)", 'quiz-maker'); ?></option>
                                    <option <?php echo $show_answers_numbering == "A." ? "selected" : ""; ?> value="A."><?php echo __( "A.", 'quiz-maker'); ?></option>
                                    <option <?php echo $show_answers_numbering == "A)" ? "selected" : ""; ?> value="A)"><?php echo __( "A)", 'quiz-maker'); ?></option>
                                    <option <?php echo $show_answers_numbering == "a." ? "selected" : ""; ?> value="a."><?php echo __( "a.", 'quiz-maker'); ?></option>
                                    <option <?php echo $show_answers_numbering == "a)" ? "selected" : ""; ?> value="a)"><?php echo __( "a)", 'quiz-maker'); ?></option>
                                </select>
                            </div>
                        </div> <!-- Show answers numbering -->
                        <hr/>
                        <div class="form-group row">
                            <div class="col-sm-4">
                                <label>
                                    <?php echo __('Show messages for right/wrong answers','quiz-maker'); ?>
                                    <a class="ays_help" data-toggle="tooltip" title="<?php echo __('Specify where to display right/wrong answers. Note that the “Show correct answers” option should be enabled. In order to make this option work, you need to add the corresponding texts from the Edit page of the particular question.','quiz-maker')?>">
                                        <i class="ays_fa ays_fa_info_circle"></i>
                                    </a>
                                </label>
                            </div>
                            <div class="col-sm-8">
                                <label class="ays_quiz_loader">
                                    <input type="radio" class="ays-enable-timer1" name="ays_answers_rw_texts" value="on_passing" <?php echo ($answers_rw_texts == 'on_passing') ? 'checked' : '' ?>/>
                                    <span><?php echo __( "During the quiz", 'quiz-maker' ); ?></span>
                                </label>
                                <label class="ays_quiz_loader">
                                    <input type="radio" class="ays-enable-timer1" name="ays_answers_rw_texts" value="on_results_page" <?php echo ($answers_rw_texts == 'on_results_page') ? 'checked' : '' ?>/>
                                    <span><?php echo __( "On results page", 'quiz-maker' ); ?></span>
                                </label>
                                <label class="ays_quiz_loader">
                                    <input type="radio" class="ays-enable-timer1" name="ays_answers_rw_texts" value="on_both" <?php echo ($answers_rw_texts == 'on_both') ? 'checked' : '' ?>/>
                                    <span><?php echo __( "On Both", 'quiz-maker' ); ?></span>
                                </label>
                                <label class="ays_quiz_loader">
                                    <input type="radio" class="ays-enable-timer1" name="ays_answers_rw_texts" value="disable" <?php echo ($answers_rw_texts == 'disable') ? 'checked' : '' ?>/>
                                    <span><?php echo __( "Disable", 'quiz-maker' ); ?></span>
                                </label>
                            </div>
                        </div> <!-- Show messages for right/wrong answers  -->
                        <hr/>
                        <div class="form-group row">
                            <div class="col-sm-4">
                                <label for="ays_quiz_disable_input_focusing">
                                    <?php echo esc_html__('Disable input focusing', 'quiz-maker'); ?>
                                    <a class="ays_help" data-toggle="tooltip" title="<?php echo esc_attr( __('Enable this option, and the keyboard will not be focused when clicking on the Next button. The option refers to Text, Short_text, Date, Number, and Fill in the blank question types.', 'quiz-maker') ); ?>">
                                        <i class="ays_fa ays_fa_info_circle"></i>
                                    </a>
                                </label>
                            </div>
                            <div class="col-sm-8">
                                <input type="checkbox" class="ays-enable-timer1" id="ays_quiz_disable_input_focusing" name="ays_quiz_disable_input_focusing" value="on" <?php echo ($quiz_disable_input_focusing) ? 'checked' : ''; ?>/>
                            </div>
                        </div> <!-- Disable input focusing -->
                        <hr/>
                    </div>
                </div>
                <div class="ays-quiz-accordion-options-main-container" data-collapsed="false">
                    <div class="ays-quiz-accordion-container">
                        <?php echo $quiz_accordion_svg_html; ?>
                        <p class="ays-subtitle"><?php echo __('Start Page','quiz-maker')?></p>
                    </div>
                    <hr class="ays-quiz-bolder-hr"/>
                    <div class="ays-quiz-accordion-options-box">
                        <div class="form-group row">
                            <div class="col-sm-4">
                                <label>
                                    <?php echo __('Show quiz head information','quiz-maker')?>
                                    <a class="ays_help" data-toggle="tooltip" title="<?php echo __('Enable to show the quiz title and description in the start page of the quiz(in the front-end).','quiz-maker')?>">
                                        <i class="ays_fa ays_fa_info_circle"></i>
                                    </a>
                                </label>
                            </div>
                            <div class="col-sm-8">
                                <div class="form-check form-check-inline checkbox_ays">
                                    <input type="checkbox" id="ays_show_quiz_title" name="ays_show_quiz_title"
                                            value="on" <?php echo $show_quiz_title ? 'checked' : ''; ?>/>
                                    <label class="form-check-label" for="ays_show_quiz_title"><?php echo __('Show title','quiz-maker')?></label>
                                </div>
                                <div class="form-check form-check-inline checkbox_ays">
                                    <input type="checkbox" id="ays_show_quiz_desc" name="ays_show_quiz_desc"
                                            value="on" <?php echo $show_quiz_desc ? 'checked' : ''; ?>/>
                                    <label class="form-check-label" for="ays_show_quiz_desc"><?php echo __('Show description','quiz-maker')?></label>
                                </div>
                            </div>
                        </div> <!-- Show quiz head information -->
                        <hr/>
                        <div class="form-group row">
                            <div class="col-sm-4" style="padding-right: 0px;">
                                <label for="ays_enable_pass_count">
                                    <?php echo __('Show passed users count','quiz-maker')?>
                                    <a class="ays_help" data-toggle="tooltip" title="<?php echo __('Show how many users passed the quiz. It will be shown at the bottom of  the start page of the quiz','quiz-maker')?>">
                                        <i class="ays_fa ays_fa_info_circle"></i>
                                    </a>
                                </label>
                            </div>
                            <div class="col-sm-8">
                                <input type="checkbox" id="ays_enable_pass_count"
                                       name="ays_enable_pass_count"
                                       value="on" <?php echo ($enable_pass_count == 'on') ? 'checked' : ''; ?>/>
                            </div>
                        </div> <!-- Show passed users count -->
                        <hr/>
                        <div class="form-group row ays_toggle_parent">
                            <div class="col-sm-4" style="padding-right: 0px;">
                                <label for="ays_show_category">
                                    <?php echo __('Show quiz category','quiz-maker')?>
                                    <a class="ays_help" data-toggle="tooltip" title="<?php echo __('Show quiz category in quiz start page','quiz-maker')?>">
                                        <i class="ays_fa ays_fa_info_circle"></i>
                                    </a>
                                </label>
                            </div>
                            <div class="col-sm-1">
                                <input type="checkbox" id="ays_show_category" name="ays_show_category" class="ays-enable-timer1 ays_toggle_checkbox" value="on" <?php echo ($show_category) ? 'checked' : ''; ?>/>
                            </div>
                            <div class="col-sm-7 ays_toggle_target ays_divider_left <?php echo ( $show_category ) ? '' : 'display_none'; ?>">
                                <div class="form-group row">
                                    <div class="col-sm-4">
                                        <label for="ays_quiz_enable_quiz_category_description">
                                            <?php echo __('Show quiz category description','quiz-maker'); ?>
                                            <a class="ays_help" data-toggle="tooltip" title="<?php echo __('Show the quiz category description on the Quiz Start page.','quiz-maker'); ?>">
                                                <i class="ays_fa ays_fa_info_circle"></i>
                                            </a>
                                        </label>
                                    </div>
                                    <div class="col-sm-8">
                                        <input type="checkbox" name="ays_quiz_enable_quiz_category_description" id="ays_quiz_enable_quiz_category_description" <?php echo ($quiz_enable_quiz_category_description) ? 'checked' : ''; ?>/>
                                    </div>
                                </div>
                            </div>
                        </div> <!-- Show quiz category -->
                        <hr/>
                        <div class="form-group row">
                            <div class="col-sm-4" style="padding-right: 0px;">
                                <label for="ays_enable_rate_avg">
                                    <?php echo __('Show average rate','quiz-maker'); ?>
                                    <a class="ays_help" data-toggle="tooltip" title="<?php echo __('Show the average rate of the quiz. It will be shown at the bottom of the start page of the quiz.','quiz-maker'); ?>">
                                        <i class="ays_fa ays_fa_info_circle"></i>
                                    </a>
                                </label>
                            </div>
                            <div class="col-sm-8">
                                <input type="checkbox" id="ays_enable_rate_avg"
                                       name="ays_enable_rate_avg"
                                       value="on" <?php echo ($enable_rate_avg == 'on') ? 'checked' : ''; ?>/>
                            </div>
                        </div> <!-- Show average rate -->
                        <hr/>
                        <div class="form-group row">
                            <div class="col-sm-4" style="padding-right: 0px;">
                                <label for="ays_show_author">
                                    <?php echo __('Show quiz author','quiz-maker')?>
                                    <a class="ays_help" data-toggle="tooltip" title="<?php echo __('Show quiz author in quiz start page','quiz-maker')?>">
                                        <i class="ays_fa ays_fa_info_circle"></i>
                                    </a>
                                </label>
                            </div>
                            <div class="col-sm-8">
                                <input type="checkbox" id="ays_show_author"
                                       name="ays_show_author"
                                       value="on" <?php echo ($show_author == 'on') ? 'checked' : ''; ?>/>
                            </div>
                        </div> <!-- Show quiz author -->
                        <hr/>
                        <div class="form-group row">
                            <div class="col-sm-4" style="padding-right: 0px;">
                                <label for="ays_show_create_date">
                                    <?php echo __('Show creation date','quiz-maker')?>
                                    <a class="ays_help" data-toggle="tooltip" title="<?php echo __('Show creation date in quiz start page','quiz-maker'); ?>">
                                        <i class="ays_fa ays_fa_info_circle"></i>
                                    </a>
                                </label>
                            </div>
                            <div class="col-sm-8">
                                <input type="checkbox" id="ays_show_create_date"
                                       name="ays_show_create_date"
                                       value="on" <?php echo ($show_create_date == 'on') ? 'checked' : ''; ?>/>
                            </div>
                        </div> <!-- Show creation date -->
                        <hr/>
                        <div class="form-group row">
                            <div class="col-sm-4">
                                <label for="ays_quiz_change_creation_date">
                                    <?php echo __('Change current quiz creation date','quiz-maker'); ?>
                                    <a class="ays_help" data-toggle="tooltip" title="<?php echo __('Change the quiz creation date to your preferred date.','quiz-maker'); ?>">
                                        <i class="ays_fa ays_fa_info_circle"></i>
                                    </a>
                                </label>
                            </div>
                            <div class="col-sm-8">
                                <div class="input-group mb-3">
                                    <input type="text" class="ays-text-input ays-text-input-short ays-quiz-date-create" id="ays_quiz_change_creation_date" name="ays_quiz_change_creation_date" value="<?php echo $change_creation_date; ?>" placeholder="<?php echo current_time( 'mysql' ); ?>">
                                    <div class="input-group-append">
                                        <label for="ays_quiz_change_creation_date" class="input-group-text">
                                            <span><i class="ays_fa ays_fa_calendar"></i></span>
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div> <!-- Change current quiz creation date -->
                        <hr/>
                        <div class="form-group row" style="margin:0px;">
                            <div class="col-sm-12 only_pro" style="padding:10px 0 0 10px;">
                                <div class="pro_features" style="justify-content:flex-end;">

                                </div>
                                <div class="form-group row">
                                    <div class="col-sm-4">
                                        <label for="ays_enable_autostart">
                                            <?php echo __('Enable autostart','quiz-maker')?>
                                            <a class="ays_help" data-toggle="tooltip" title="<?php echo __('If you enable this option, your quiz will start automatically after the page is fully loaded. Note, that this option is designed for 1 quiz in a page. If you put multiple quizzes in a page, only the one located at the top will autostart.','quiz-maker')?>">
                                                <i class="ays_fa ays_fa_info_circle"></i>
                                            </a>
                                        </label>
                                    </div>
                                    <div class="col-sm-8">
                                        <input type="checkbox" value="on" tabindex="-1"/>
                                    </div>
                                </div> <!-- Enable autostart -->
                                <a href="https://ays-pro.com/wordpress/quiz-maker?utm_source=dashboard&utm_medium=quiz-free&utm_campaign=enable-autostart-<?php echo esc_attr( AYS_QUIZ_UTM_VERSION ); ?>" target="_blank" class="ays-quiz-new-upgrade-button-link">
                                    <div class="ays-quiz-new-upgrade-button-box">
                                        <div>
                                            <img loading="lazy" src="<?php echo AYS_QUIZ_ADMIN_URL.'/images/icons/locked_24x24.svg'?>">
                                            <img loading="lazy" src="<?php echo AYS_QUIZ_ADMIN_URL.'/images/icons/unlocked_24x24.svg'?>" class="ays-quiz-new-upgrade-button-hover">
                                        </div>
                                        <div class="ays-quiz-new-upgrade-button"><?php echo __("Upgrade", "quiz-maker"); ?></div>
                                    </div>
                                </a>
                            </div>
                        </div> <!-- Enable autostart -->
                        <hr/>
                    </div>
                </div>
                <div class="ays-quiz-accordion-options-main-container" data-collapsed="false">
                    <div class="ays-quiz-accordion-container">
                        <?php echo $quiz_accordion_svg_html; ?>
                        <p class="ays-subtitle"><?php echo __('Button Settings','quiz-maker')?></p>
                    </div>
                    <hr class="ays-quiz-bolder-hr" />
                    <div class="ays-quiz-accordion-options-box">
                        <div class="form-group row">
                            <div class="col-sm-4">
                                <label for="ays_enable_next_button">
                                    <?php echo __('Enable next button','quiz-maker'); ?>
                                    <a class="ays_help" data-toggle="tooltip" title="<?php echo __('User can change the question forward manually. If you want to make the questions required just disable this option.','quiz-maker'); ?>">
                                        <i class="ays_fa ays_fa_info_circle"></i>
                                    </a>
                                </label>
                            </div>
                            <div class="col-sm-8">
                                <input type="checkbox" id="ays_enable_next_button" value="on" name="ays_enable_next_button" <?php echo (isset($options['enable_next_button']) && $options['enable_next_button'] == 'on') ? 'checked' : ''; ?>>
                            </div>
                        </div> <!-- Enable next button -->
                        <hr/>
                        <div class="form-group row">
                            <div class="col-sm-4">
                                <label for="ays_enable_previous_button">
                                    <?php echo __('Enable previous button','quiz-maker')?>
                                    <a class="ays_help" data-toggle="tooltip" title="<?php echo __('User can change the question backward manually','quiz-maker'); ?>">
                                        <i class="ays_fa ays_fa_info_circle"></i>
                                    </a>
                                </label>
                            </div>
                            <div class="col-sm-8">
                                <input type="checkbox" id="ays_enable_previous_button" value="on" name="ays_enable_previous_button" <?php echo (isset($options['enable_previous_button']) && $options['enable_previous_button'] == 'on') ? 'checked' : '' ?>>
                            </div>
                        </div> <!-- Enable previous button -->
                        <hr/>
                        <div class="form-group row ays_toggle_parent">
                            <div class="col-sm-4">
                                <label for="ays_enable_early_finish">
                                    <?php echo __('Enable finish button','quiz-maker')?>
                                    <a class="ays_help" data-toggle="tooltip" title="<?php echo __('Allow user to finish the quiz early.','quiz-maker')?>">
                                        <i class="ays_fa ays_fa_info_circle"></i>
                                    </a>
                                </label>
                            </div>
                            <div class="col-sm-1">
                                <input type="checkbox" class="ays-enable-timer1 ays_toggle_checkbox" id="ays_enable_early_finish"
                                       name="ays_enable_early_finish"
                                       value="on" <?php echo ($enable_early_finish) ? 'checked' : '' ?>/>
                            </div>
                            <div class="col-sm-7 ays_toggle_target ays_divider_left <?php echo ($enable_early_finish) ? '' : 'display_none' ?>">
                                <div class="form-group row">
                                    <div class="col-sm-4">
                                        <label for="ays_enable_early_finsh_comfirm_box">
                                            <?php echo __('Enable confirm box for the Finish button','quiz-maker')?>
                                            <a class="ays_help" data-toggle="tooltip" title="<?php echo __('If the checkbox is ticked and the Finish button is enabled too, then when the user clicks on the Finish button, the confirmation box will be displayed. It will ask `Do you want to finish the quiz? Are you sure? `.','quiz-maker')?>">
                                                <i class="ays_fa ays_fa_info_circle"></i>
                                            </a>
                                        </label>
                                    </div>
                                    <div class="col-sm-8">
                                        <input type="checkbox" class="ays-enable-timer1" id="ays_enable_early_finsh_comfirm_box" name="ays_enable_early_finsh_comfirm_box" value="on" <?php echo ($enable_early_finsh_comfirm_box) ? 'checked' : '' ?>/>
                                    </div>
                                </div>
                            </div>
                        </div> <!-- Enable finish button -->
                        <hr/>
                        <div class="form-group row">
                            <div class="col-sm-4">
                                <label for="ays_enable_clear_answer">
                                    <?php echo __('Enable clear answer button','quiz-maker')?>
                                    <a class="ays_help" data-toggle="tooltip" title="<?php echo __('Allow user to clear the selected answer. Button will not be displayed if Show correct answers option is enabled.','quiz-maker')?>">
                                        <i class="ays_fa ays_fa_info_circle"></i>
                                    </a>
                                </label>
                            </div>
                            <div class="col-sm-8">
                                <input type="checkbox" class="ays-enable-timer1" id="ays_enable_clear_answer"
                                       name="ays_enable_clear_answer"
                                       value="on" <?php echo ($enable_clear_answer) ? 'checked' : '' ?>/>
                            </div>
                        </div> <!-- Enable clear answer button -->
                        <hr/>
                        <div class="form-group row">
                            <div class="col-sm-4">
                                <label for="ays_enable_enter_key">
                                    <?php echo __('Enable to go next by pressing Enter key','quiz-maker')?>
                                    <a class="ays_help" data-toggle="tooltip" title="<?php echo __('This option allows users to go to the next question by pressing Enter key. It is working with the following question types only: Text, Short Text, Number.','quiz-maker')?>">
                                        <i class="ays_fa ays_fa_info_circle"></i>
                                    </a>
                                </label>
                            </div>
                            <div class="col-sm-8">
                                <input type="checkbox" class="ays-enable-timer1" id="ays_enable_enter_key"
                                       name="ays_enable_enter_key"
                                       value="on" <?php echo ($enable_enter_key) ? 'checked' : '' ?>/>
                            </div>
                        </div> <!-- Enable to go next by pressing Enter key -->
                        <hr/>
                        <div class="form-group row ays_toggle_parent">
                            <div class="col-sm-4">
                                <label for="ays_enable_arrows">
                                    <?php echo __('Use arrows instead of buttons','quiz-maker'); ?>
                                    <a class="ays_help" data-toggle="tooltip" title="<?php echo __('Buttons will be replaced to icons.','quiz-maker'); ?>">
                                        <i class="ays_fa ays_fa_info_circle"></i>
                                    </a>
                                </label>
                                <p class="ays_quiz_small_hint_text_for_message_variables">
                                    <span><?php echo __( "Please Note" , 'quiz-maker' ); ?></span>
                                    <a class="ays_help" data-toggle="tooltip" title="<?php echo __('Please note that if the background color and the button text color are the same, the arrows will not be visible.','quiz-maker'); ?>">
                                        <i class="ays_fa ays_fa_info_circle"></i>
                                    </a>
                                </p>
                            </div>
                            <div class="col-sm-1">
                                <input type="checkbox" class="ays-enable-timerl ays_toggle_checkbox" id="ays_enable_arrows" name="ays_enable_arrows" value="on" <?php echo (isset($options['enable_arrows']) && $options['enable_arrows'] == 'on') ? 'checked' : ''; ?>/>
                            </div>
                            <div class="col-sm-7 ays_toggle_target <?php echo (isset($options['enable_arrows']) && $options['enable_arrows'] == 'on') ? '' : 'display_none' ?>">
                                <label class="ays_quiz_loader ays_quiz_arrows_option_arrows">
                                    <input name="ays_quiz_arrow_type" class="" type="radio" value="default" <?php echo ($quiz_arrow_type == 'default') ? 'checked' : ''; ?>>
                                    <i class="ays_fa ays_fa_arrow_left"></i>
                                    <i class="ays_fa ays_fa_arrow_right"></i>
                                </label>
                                <label class="ays_quiz_loader ays_quiz_arrows_option_arrows">
                                    <input name="ays_quiz_arrow_type" class="" type="radio" value="long_arrow" <?php echo ($quiz_arrow_type == 'long_arrow') ? 'checked' : ''; ?>>
                                    <i class="ays_fa ays_fa_long_arrow_left"></i>
                                    <i class="ays_fa ays_fa_long_arrow_right"></i>
                                </label>
                                <label class="ays_quiz_loader ays_quiz_arrows_option_arrows">
                                    <input name="ays_quiz_arrow_type" class="" type="radio" value="arrow_circle_o" <?php echo ($quiz_arrow_type == 'arrow_circle_o') ? 'checked' : ''; ?>>
                                    <i class="ays_fa ays_fa_arrow_circle_o_left"></i>
                                    <i class="ays_fa ays_fa_arrow_circle_o_right"></i>
                                </label>
                                <label class="ays_quiz_loader ays_quiz_arrows_option_arrows">
                                    <input name="ays_quiz_arrow_type" class="" type="radio" value="arrow_circle" <?php echo ($quiz_arrow_type == 'arrow_circle') ? 'checked' : ''; ?>>
                                    <i class="ays_fa ays_fa_arrow_circle_left"></i>
                                    <i class="ays_fa ays_fa_arrow_circle_right"></i>
                                </label>
                            </div>
                        </div> <!-- Use arrows instead of buttons -->
                        <hr/>
                        <div class="form-group row">
                            <div class="col-sm-4">
                                <label for="ays_quiz_display_messages_before_buttons">
                                    <?php echo __('Display messages before the buttons','quiz-maker'); ?>
                                    <a class="ays_help" data-toggle="tooltip" title="<?php echo __('If you enable this option, you can display the texts of the following options before the buttons: Message for the right/wrong answers, Question Explanation, and Show correct answers. Note: If the Show correct answers option is disabled, the messages will not be displayed.','quiz-maker'); ?>">
                                        <i class="ays_fa ays_fa_info_circle"></i>
                                    </a>
                                </label>
                            </div>
                            <div class="col-sm-8">
                                <input type="checkbox" class="ays-enable-timer1" id="ays_quiz_display_messages_before_buttons"
                                       name="ays_quiz_display_messages_before_buttons" value="on" <?php echo $quiz_display_messages_before_buttons ? 'checked' : '' ?>/>
                            </div>
                        </div> <!-- Display messages before the buttons -->
                        <hr>
                        <div class="form-group row ays_toggle_parent">
                            <div class="col-sm-4" style="padding-right: 0px;">
                                <label for="ays_quiz_enable_custom_texts_for_buttons">
                                    <?php echo __('Enable custom texts for buttons','quiz-maker'); ?>
                                    <a class="ays_help" data-toggle="tooltip" title="<?php echo __('Enable this option and write your desired custom texts for buttons, instead of the default ones. Note: If this option is disabled, the Buttons Texts will be taken from the General Settings page > Buttons Texts tab.','quiz-maker')?>">
                                        <i class="ays_fa ays_fa_info_circle"></i>
                                    </a>
                                </label>
                            </div>
                            <div class="col-sm-1">
                                <input type="checkbox" class="ays-enable-timer1 ays_toggle_checkbox" id="ays_quiz_enable_custom_texts_for_buttons" name="ays_quiz_enable_custom_texts_for_buttons" value="on" <?php echo ($quiz_enable_custom_texts_for_buttons) ? 'checked' : ''; ?>/>
                            </div>
                            <div class="col-sm-7 ays_toggle_target ays_divider_left <?php echo ( $quiz_enable_custom_texts_for_buttons ) ? '' : 'display_none'; ?>">
                                <div class="form-group row">
                                    <div class="col-sm-4">
                                        <label for="ays_quiz_custom_texts_start_button">
                                            <?php echo __('Start button','quiz-maker'); ?>
                                        </label> 
                                    </div>
                                    <div class="col-sm-8">
                                        <input type="text" class="ays-text-input" name="ays_quiz_custom_texts_start_button" id="ays_quiz_custom_texts_start_button" value="<?php echo esc_attr($quiz_custom_texts_start_button); ?>"/>
                                    </div>
                                </div>
                                <br>
                                <div class="form-group row">
                                    <div class="col-sm-4">
                                        <label for="ays_quiz_custom_texts_next_button">
                                            <?php echo __('Next button','quiz-maker'); ?>
                                        </label> 
                                    </div>
                                    <div class="col-sm-8">
                                        <input type="text" class="ays-text-input" name="ays_quiz_custom_texts_next_button" id="ays_quiz_custom_texts_next_button" value="<?php echo esc_attr($quiz_custom_texts_next_button); ?>"/>
                                    </div>
                                </div>
                                <br>
                                <div class="form-group row">
                                    <div class="col-sm-4">
                                        <label for="ays_quiz_custom_texts_prev_button">
                                            <?php echo __('Previous button','quiz-maker'); ?>
                                        </label> 
                                    </div>
                                    <div class="col-sm-8">
                                        <input type="text" class="ays-text-input" name="ays_quiz_custom_texts_prev_button" id="ays_quiz_custom_texts_prev_button" value="<?php echo esc_attr($quiz_custom_texts_prev_button); ?>"/>
                                    </div>
                                </div>
                                <br>
                                <div class="form-group row">
                                    <div class="col-sm-4">
                                        <label for="ays_quiz_custom_texts_clear_button">
                                            <?php echo __('Clear button','quiz-maker'); ?>
                                        </label> 
                                    </div>
                                    <div class="col-sm-8">
                                        <input type="text" class="ays-text-input" name="ays_quiz_custom_texts_clear_button" id="ays_quiz_custom_texts_clear_button" value="<?php echo esc_attr($quiz_custom_texts_clear_button); ?>"/>
                                    </div>
                                </div>
                                <br>
                                <div class="form-group row">
                                    <div class="col-sm-4">
                                        <label for="ays_quiz_custom_texts_finish_button">
                                            <?php echo __('Finish button','quiz-maker'); ?>
                                        </label> 
                                    </div>
                                    <div class="col-sm-8">
                                        <input type="text" class="ays-text-input" name="ays_quiz_custom_texts_finish_button" id="ays_quiz_custom_texts_finish_button" value="<?php echo esc_attr($quiz_custom_texts_finish_button); ?>"/>
                                    </div>
                                </div>
                                <br>
                                <div class="form-group row">
                                    <div class="col-sm-4">
                                        <label for="ays_quiz_custom_texts_see_results_button">
                                            <?php echo __('See Result button','quiz-maker'); ?>
                                        </label> 
                                    </div>
                                    <div class="col-sm-8">
                                        <input type="text" class="ays-text-input" name="ays_quiz_custom_texts_see_results_button" id="ays_quiz_custom_texts_see_results_button" value="<?php echo esc_attr($quiz_custom_texts_see_results_button); ?>"/>
                                    </div>
                                </div>
                                <br>
                                <div class="form-group row">
                                    <div class="col-sm-4">
                                        <label for="ays_quiz_custom_texts_restart_quiz_button">
                                            <?php echo __('Restart quiz button','quiz-maker'); ?>
                                        </label> 
                                    </div>
                                    <div class="col-sm-8">
                                        <input type="text" class="ays-text-input" name="ays_quiz_custom_texts_restart_quiz_button" id="ays_quiz_custom_texts_restart_quiz_button" value="<?php echo esc_attr($quiz_custom_texts_restart_quiz_button); ?>"/>
                                    </div>
                                </div>
                                <br>
                                <div class="form-group row">
                                    <div class="col-sm-4">
                                        <label for="ays_quiz_custom_texts_send_feedback_button">
                                            <?php echo __('Send feedback button','quiz-maker'); ?>
                                        </label> 
                                    </div>
                                    <div class="col-sm-8">
                                        <input type="text" class="ays-text-input" name="ays_quiz_custom_texts_send_feedback_button" id="ays_quiz_custom_texts_send_feedback_button" value="<?php echo esc_attr($quiz_custom_texts_send_feedback_button); ?>"/>
                                    </div>
                                </div>
                                <br>
                                <div class="form-group row">
                                    <div class="col-sm-4">
                                        <label for="ays_quiz_custom_texts_load_more_button">
                                            <?php echo __('Load more button','quiz-maker'); ?>
                                        </label> 
                                    </div>
                                    <div class="col-sm-8">
                                        <input type="text" class="ays-text-input" name="ays_quiz_custom_texts_load_more_button" id="ays_quiz_custom_texts_load_more_button" value="<?php echo esc_attr($quiz_custom_texts_load_more_button); ?>"/>
                                    </div>
                                </div>
                                <br>
                                <div class="form-group row">
                                    <div class="col-sm-4">
                                        <label for="ays_quiz_custom_texts_exit_button">
                                            <?php echo __('Exit button','quiz-maker'); ?>
                                        </label> 
                                    </div>
                                    <div class="col-sm-8">
                                        <input type="text" class="ays-text-input" name="ays_quiz_custom_texts_exit_button" id="ays_quiz_custom_texts_exit_button" value="<?php echo esc_attr($quiz_custom_texts_exit_button); ?>"/>
                                    </div>
                                </div>
                                <br>
                                <div class="form-group row">
                                    <div class="col-sm-4">
                                        <label for="ays_quiz_custom_texts_check_button">
                                            <?php echo __('Check button','quiz-maker'); ?>
                                        </label> 
                                    </div>
                                    <div class="col-sm-8">
                                        <input type="text" class="ays-text-input" name="ays_quiz_custom_texts_check_button" id="ays_quiz_custom_texts_check_button" value="<?php echo esc_attr($quiz_custom_texts_check_button); ?>"/>
                                    </div>
                                </div>
                                <br>
                                <div class="form-group row">
                                    <div class="col-sm-4">
                                        <label for="ays_quiz_custom_texts_login_button">
                                            <?php echo __('Log In button','quiz-maker'); ?>
                                        </label> 
                                    </div>
                                    <div class="col-sm-8">
                                        <input type="text" class="ays-text-input" name="ays_quiz_custom_texts_login_button" id="ays_quiz_custom_texts_login_button" value="<?php echo esc_attr($quiz_custom_texts_login_button); ?>"/>
                                    </div>
                                </div>
                            </div>
                        </div> <!-- Enable custom texts for buttons -->
                        <hr>
                        <div class="form-group row">
                            <div class="col-sm-4">
                                <label for="ays_quiz_enable_keyboard_navigation">
                                    <?php echo esc_html__('Enable Keyboard Navigation', 'quiz-maker'); ?>
                                    <a class="ays_help" data-toggle="tooltip" title="<?php echo esc_attr( __('After enabling this option, users can navigate through answers by pressing  the Tab key(forward) or the Shift+Tab shortcut(back), tick an answer by pressing the Space key, and move forward to the next question using the Enter key.', 'quiz-maker') ); ?>">
                                        <i class="ays_fa ays_fa_info_circle"></i>
                                    </a>
                                </label>
                            </div>
                            <div class="col-sm-8">
                                <input type="checkbox" class="ays-enable-timer1" id="ays_quiz_enable_keyboard_navigation"
                                    name="ays_quiz_enable_keyboard_navigation" value="on" <?php echo $quiz_enable_keyboard_navigation ? 'checked' : ''; ?>/>
                            </div>
                        </div> <!-- Enable Keyboard navigation -->
                        <hr>
                    </div>
                </div>
                <div class="ays-quiz-accordion-options-main-container" data-collapsed="false">
                    <div class="ays-quiz-accordion-container">
                        <?php echo $quiz_accordion_svg_html; ?>
                        <p class="ays-subtitle"><?php echo __('Advanced Settings','quiz-maker')?></p>
                    </div>
                    <hr class="ays-quiz-bolder-hr">
                    <div class="ays-quiz-accordion-options-box">
                        <div class="form-group row">
                            <div class="col-sm-4">
                                <label for="ays_enable_audio_autoplay">
                                    <?php echo __('Enable audio autoplay','quiz-maker')?>
                                    <a class="ays_help" data-toggle="tooltip" title="<?php echo __('If there is audio in the question, it will automatically turn on.','quiz-maker')?>">
                                        <i class="ays_fa ays_fa_info_circle"></i>
                                    </a>
                                </label>
                            </div>
                            <div class="col-sm-8">
                                <input type="checkbox" class="ays-enable-timer1" id="ays_enable_audio_autoplay"
                                       name="ays_enable_audio_autoplay"
                                       value="on" <?php echo ($enable_audio_autoplay) ? 'checked' : '' ?>/>
                            </div>
                        </div> <!-- Enable audio autoplay -->
                        <hr/>
                        <div class="form-group row">
                            <div class="col-sm-4">
                                <label for="ays_enable_live_bar_option">
                                    <?php echo __('Enable live progress bar','quiz-maker'); ?>
                                    <a class="ays_help" data-toggle="tooltip" title="<?php echo __('Show the current state of the user passing the quiz. It will be shown at the top of the quiz container. Note: If the Display all questions on one page option is enabled, then, the Progress Bar will display the 100% value. If the Question count per page option is enabled and you have a multipage quiz, then, the Progress Bar value will be displayed by stages.','quiz-maker'); ?>">
                                        <i class="ays_fa ays_fa_info_circle"></i>
                                    </a>
                                </label>
                            </div>
                            <div class="col-sm-1">
                                <input type="checkbox" class="ays-enable-timer1" id="ays_enable_live_bar_option"
                                       name="ays_enable_live_progress_bar"
                                       value="on" <?php echo (isset($options['enable_live_progress_bar']) && $options['enable_live_progress_bar'] == 'on') ? 'checked' : '' ?>/>
                            </div>
                            <div class="col-sm-7" style="border-left: 1px solid #ededed; <?php echo (isset($options['enable_live_progress_bar']) && $options['enable_live_progress_bar'] == 'on') ? '' : 'display:none;' ?>"
                                 id="ays_enable_percent_view_option_div" >
                                <div class="form-group row">
                                    <div class="col-sm-4">
                                        <label for="ays_enable_percent_view_option">
                                            <?php echo __('Enable percent view','quiz-maker')?>
                                            <a class="ays_help" data-toggle="tooltip" title="<?php echo __('Show the progress bar by percentage.','quiz-maker')?>">
                                                <i class="ays_fa ays_fa_info_circle"></i>
                                            </a>
                                        </label>
                                    </div>
                                    <div class="col-sm-8">
                                        <input type="checkbox" class="ays-enable-timer1" id="ays_enable_percent_view_option"
                                               name="ays_enable_percent_view"
                                               value="on" <?php echo (isset($options['enable_percent_view']) && $options['enable_percent_view'] == 'on') ? 'checked' : '' ?>/>
                                    </div>
                                </div>
                            </div>
                        </div> <!-- Enable live progress bar -->
                        <hr/>
                        <div class="form-group row">
                            <div class="col-sm-4">
                                <label for="ays_enable_rtl_direction">
                                    <?php echo __('Use RTL Direction','quiz-maker')?>
                                    <a class="ays_help" data-toggle="tooltip" title="<?php echo __('Enable Right to Left direction for the text. This option is intended for the Arabic language.','quiz-maker')?>">
                                        <i class="ays_fa ays_fa_info_circle"></i>
                                    </a>
                                </label>
                            </div>
                            <div class="col-sm-8">
                                <input type="checkbox" class="ays-enable-timerl" id="ays_enable_rtl_direction"
                                       name="ays_enable_rtl_direction"
                                       value="on" <?php echo (isset($options['enable_rtl_direction']) && $options['enable_rtl_direction'] == 'on') ? 'checked' : ''; ?>/>
                            </div>
                        </div> <!-- Use RTL Direction -->
                        <hr/>
                        <div class="form-group row">
                            <div class="col-sm-4">
                                <label for="ays_quiz_create_author">
                                    <?php echo __('Change the author of the current quiz','quiz-maker'); ?>
                                    <a class="ays_help" data-toggle="tooltip" title="<?php echo __('You can change the author who created the current quiz to your preferred one. You need to write the User ID here. Please note, that in case you write an ID, by which there are no users found, the changes will not be applied and the previous author will remain the same.','quiz-maker'); ?>">
                                        <i class="ays_fa ays_fa_info_circle"></i>
                                    </a>
                                </label>
                            </div>
                            <div class="col-sm-8">
                                <select class="ays-text-input ays-text-input-short select2-container-200-width" id='ays_quiz_create_author'name='ays_quiz_create_author'>
                                    <option value=""><?php echo __('Select User','quiz-maker')?></option>
                                    <?php
                                        echo "<option value='" . $ays_quiz_create_author_data['ID'] . "' selected>" . $ays_quiz_create_author_data['display_name'] . "</option>";
                                    ?>
                                </select>
                            </div>
                        </div> <!-- Change the author of the current quiz -->
                        <hr/>
                        <div class="form-group row">
                            <div class="col-sm-4">
                                <label for="ays_enable_questions_counter">
                                    <?php echo __('Show questions counter','quiz-maker')?>
                                    <a class="ays_help" data-toggle="tooltip" title="<?php echo __('Show the number of the current question and the total amount of the question in the quiz. It will be shown on the right top corner of the quiz container. Example: 3/7.','quiz-maker')?>">
                                        <i class="ays_fa ays_fa_info_circle"></i>
                                    </a>
                                </label>
                            </div>
                            <div class="col-sm-8">
                                <input type="checkbox" class="ays-enable-timerl" id="ays_enable_questions_counter"
                                       name="ays_enable_questions_counter"
                                       value="on" <?php echo (isset($options['enable_questions_counter']) && $options['enable_questions_counter'] == 'on') ? 'checked' : ''; ?>/>
                            </div>
                        </div> <!-- Show questions counter -->
                        <hr/>
                        <div class="form-group row">
                            <div class="col-sm-4">
                                <label for="ays_quiz_enable_question_image_zoom">
                                    <?php echo __('Question Image Zoom','quiz-maker')?>
                                    <a class="ays_help" data-toggle="tooltip" title="<?php echo __('By enabling this option, the users can zoom the question images and open them in the large size on the Front-end.','quiz-maker')?>">
                                        <i class="ays_fa ays_fa_info_circle"></i>
                                    </a>
                                </label>
                            </div>
                            <div class="col-sm-8">
                                <input type="checkbox" class="ays-enable-timer1" id="ays_quiz_enable_question_image_zoom"
                                       name="ays_quiz_enable_question_image_zoom" value="on" <?php echo $quiz_enable_question_image_zoom ? 'checked' : '' ?>/>
                            </div>
                        </div> <!-- Question Image Zoom -->
                        <hr>
                        <div class="form-group row">
                            <div class="col-sm-4">
                                <label for="ays_enable_leave_page">
                                    <?php echo __('Enable confirmation box for leaving the page','quiz-maker')?>
                                    <a class="ays_help" data-toggle="tooltip" title="<?php echo __('Show confirmation popup if user tries to refresh or leave the page during the quiz taking process.','quiz-maker')?>">
                                        <i class="ays_fa ays_fa_info_circle"></i>
                                    </a>
                                </label>
                            </div>
                            <div class="col-sm-8">
                                <input type="checkbox" class="ays-enable-timer1" id="ays_enable_leave_page"
                                       name="ays_enable_leave_page"
                                       value="on" <?php echo $enable_leave_page ? 'checked' : '' ?>/>
                            </div>
                        </div> <!-- Enable confirmation box for leaving the page -->
                        <hr/>
                        <div class="form-group row">
                            <div class="col-sm-4">
                                <label for="ays_enable_see_result_confirm_box">
                                    <?php echo __('Enable confirmation box for the See Result button','quiz-maker'); ?>
                                    <a class="ays_help" data-toggle="tooltip" title="<?php echo __('When this option is ticked, a confirmation box will appear after the user clicks the See Result button at the end of the quiz.','quiz-maker'); ?>">
                                        <i class="ays_fa ays_fa_info_circle"></i>
                                    </a>
                                </label>
                            </div>
                            <div class="col-sm-8">
                                <input type="checkbox" class="ays-enable-timer1" id="ays_enable_see_result_confirm_box" name="ays_enable_see_result_confirm_box" value="on" <?php echo $enable_see_result_confirm_box ? 'checked' : '' ?>/>
                            </div>
                        </div> <!-- Enable confirmation box for the See Result button  -->
                        <hr/>
                        <div class="form-group row ays_toggle_parent">
                            <div class="col-sm-4" style="padding-right: 0px;">
                                <label for="ays_enable_rw_asnwers_sounds">
                                    <?php echo __('Enable sounds for right/wrong answers','quiz-maker'); ?>
                                    <a class="ays_help" data-toggle="tooltip" title="<?php echo __('This option will work only when Enable Show correct answers option is enabled and sounds are selected from General options page.','quiz-maker')?>">
                                        <i class="ays_fa ays_fa_info_circle"></i>
                                    </a>
                                </label>
                            </div>
                            <div class="col-sm-1">
                                <input type="checkbox" id="ays_enable_rw_asnwers_sounds"
                                       name="ays_enable_rw_asnwers_sounds" class="ays_toggle_checkbox"
                                       value="on" <?php echo $enable_rw_asnwers_sounds ? 'checked' : ''; ?>/>
                            </div>
                            <div class="col-sm-7 ays_toggle_target ays_divider_left" style="<?php echo $enable_rw_asnwers_sounds ? '' : 'display:none;' ?>">
                                <?php if($rw_answers_sounds_status): ?>
                                <blockquote class=""><?php echo __('Sounds are selected. For change sounds go to', 'quiz-maker'); ?> <a href="?page=quiz-maker-settings"><?php echo __('General options', 'quiz-maker'); ?></a> <?php echo __('page', 'quiz-maker'); ?></blockquote>
                                <?php else: ?>
                                <blockquote class=""><?php echo __('Sounds are not selected. For selecting sounds go to', 'quiz-maker'); ?> <a href="?page=quiz-maker-settings"><?php echo __('General options', 'quiz-maker'); ?></a> <?php echo __('page', 'quiz-maker'); ?></blockquote>
                                <?php endif; ?>
                            </div>
                        </div> <!-- Enable sounds for right/wrong answers -->
                        <hr/>
                        <div class="form-group row ays_toggle_parent">
                            <div class="col-sm-4" style="padding-right: 0px;">
                                <label for="ays_enable_bg_music">
                                    <?php echo __('Enable Background music','quiz-maker')?>
                                    <a class="ays_help" data-toggle="tooltip" title="<?php echo __('Background music will play while passing the quiz. Upload your own audio file for the quiz.','quiz-maker')?>">
                                        <i class="ays_fa ays_fa_info_circle"></i>
                                    </a>
                                </label>
                            </div>
                            <div class="col-sm-1">
                                <input type="checkbox" id="ays_enable_bg_music"
                                       name="ays_enable_bg_music" class="ays_toggle_checkbox"
                                       value="on" <?php echo $enable_bg_music ? 'checked' : ''; ?>/>
                            </div>
                            <div class="col-sm-7 ays_toggle_target ays_divider_left" style="<?php echo $enable_bg_music ? '' : 'display:none;' ?>">
                                <div class="ays-bg-music-container">
                                    <a class="add-quiz-bg-music" href="javascript:void(0);"><?php echo __("Select music", 'quiz-maker'); ?></a>
                                    <audio controls src="<?php echo $quiz_bg_music; ?>"></audio>
                                    <input type="hidden" name="ays_quiz_bg_music" class="ays_quiz_bg_music" value="<?php echo $quiz_bg_music; ?>">
                                </div>
                            </div>
                        </div> <!-- Enable Background music -->
                        <hr/>
                        <div class="form-group row ays_toggle_parent">
                            <div class="col-sm-4" style="padding-right: 0px;">
                                <label for="ays_enable_questions_reporting">
                                    <?php echo esc_html__('Enable question reporting', 'quiz-maker'); ?>
                                    <a class="ays_help" data-toggle="tooltip" title="<?php echo esc_attr__('Enable this option and the users can report questions from the Front-end, once they encounter any issues, errors, or inaccuracies.', 'quiz-maker' );?>">
                                        <i class="ays_fa ays_fa_info_circle"></i>
                                    </a>
                                </label>
                            </div>
                            <div class="col-sm-8">
                                <input type="checkbox" class="ays-enable-timer1 ays_toggle_checkbox" id="ays_enable_questions_reporting" name="ays_enable_questions_reporting" value="on" <?php echo ($enable_question_reporting) ? 'checked' : ''; ?>/>
                            </div>
                        </div> <!-- Enable question reporting -->
                        <hr/>
                        <div class="form-group row" style="margin:0px;">
                            <div class="col-sm-12 only_pro" style="padding:10px 0 0 10px;">
                                <div class="pro_features" style="justify-content:flex-end;">

                                </div>
                                <div class="form-group row">
                                    <div class="col-sm-4">
                                        <label>
                                            <?php echo __('Embed code','quiz-maker')?>
                                            <a class="ays_help" data-toggle="tooltip" title="<?php echo __('Copy the iframe and embed it on another/your website. Paste the iframe like HTML on your desired WP Editor. By this, the quiz will be opened on that particular page.','quiz-maker'); ?>">
                                                <i class="ays_fa ays_fa_info_circle"></i>
                                            </a>
                                        </label>
                                    </div>
                                    <div class="col-sm-2">
                                        <input type="button" class="button button-primary" value="<?php echo __('Copy Code','quiz-maker');?>">
                                    </div>
                                    <div class="col-sm-6">
                                        <input type="text" class="ays-text-input" tabindex="-1" value="<?php echo esc_attr($embed_code_html); ?>"/>
                                    </div>
                                </div>
                                <a href="https://ays-pro.com/wordpress/quiz-maker?utm_source=dashboard&utm_medium=quiz-free&utm_campaign=embed-code-<?php echo esc_attr( AYS_QUIZ_UTM_VERSION ); ?>" target="_blank" class="ays-quiz-new-upgrade-button-link">
                                    <div class="ays-quiz-new-upgrade-button-box">
                                        <div>
                                            <img loading="lazy" src="<?php echo AYS_QUIZ_ADMIN_URL.'/images/icons/locked_24x24.svg'?>">
                                            <img loading="lazy" src="<?php echo AYS_QUIZ_ADMIN_URL.'/images/icons/unlocked_24x24.svg'?>" class="ays-quiz-new-upgrade-button-hover">
                                        </div>
                                        <div class="ays-quiz-new-upgrade-button"><?php echo __("Upgrade to Developer/Agency", "quiz-maker"); ?></div>
                                    </div>
                                </a>
                            </div>
                        </div> <!-- Embed code -->
                        <hr/>
                        <div class="form-group row" style="margin:0px;">
                            <div class="col-sm-12 only_pro" style="padding:10px 0 0 10px;">
                                <div class="pro_features" style="justify-content:flex-end;">

                                </div>
                                <div class="form-group row">
                                    <div class="col-sm-4">
                                        <label for="ays_allow_exporting_quizzes">
                                            <?php echo __('Export Quiz as PDF','quiz-maker')?>
                                            <a class="ays_help" data-toggle="tooltip" title="<?php echo __('After ticking this option the Users field will be activated. You will be able to choose whom you want to give the permission to export the Quiz in the PDF file format. The Export to PDF button will be displayed on the front-end.','quiz-maker'); ?>">
                                                <i class="ays_fa ays_fa_info_circle"></i>
                                            </a>
                                        </label>
                                    </div>
                                    <div class="col-sm-1">
                                        <input type="checkbox" class="ays-enable-timer1" />
                                    </div>
                                </div> <!-- Allow exporting quizzes -->
                                <a href="https://ays-pro.com/wordpress/quiz-maker?utm_source=dashboard&utm_medium=quiz-free&utm_campaign=export-quiz-pdf-<?php echo esc_attr( AYS_QUIZ_UTM_VERSION ); ?>" target="_blank" class="ays-quiz-new-upgrade-button-link">
                                    <div class="ays-quiz-new-upgrade-button-box">
                                        <div>
                                            <img loading="lazy" src="<?php echo AYS_QUIZ_ADMIN_URL.'/images/icons/locked_24x24.svg'?>">
                                            <img loading="lazy" src="<?php echo AYS_QUIZ_ADMIN_URL.'/images/icons/unlocked_24x24.svg'?>" class="ays-quiz-new-upgrade-button-hover">
                                        </div>
                                        <div class="ays-quiz-new-upgrade-button"><?php echo __("Upgrade", "quiz-maker"); ?></div>
                                    </div>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                <?php
                    if(has_action('ays_qm_addon_integration_accordion_settings_tab')){
                        $args = apply_filters( 'ays_qm_front_end_integrations_options', array(), $options );
                        do_action( 'ays_qm_addon_integration_accordion_settings_tab', $args);
                    }
                ?>
            </div>
            
            <div id="tab4" class="ays-quiz-tab-content <?php echo ($ays_quiz_tab == 'tab4') ? 'ays-quiz-tab-content-active' : ''; ?>">
                <div class="ays-quiz-top-actions-container-wrapper form-group row">
                    <div class="col-sm-12">
                        <p class="m-0 text-right">
                            <a class="ays-quiz-collapse-all" href="javascript:void(0);"><?php echo __( "Collapse All", "quiz-maker" ); ?></a>
                            <span>|</span>
                            <a class="ays-quiz-expand-all" href="javascript:void(0);"><?php echo __( "Expand All", "quiz-maker" ); ?></a>
                        </p>
                    </div>
                </div>
                <div class="ays-quiz-accordion-options-main-container" data-collapsed="false">
                    <div class="ays-quiz-accordion-container">
                        <?php echo $quiz_accordion_svg_html; ?>
                        <p class="ays-subtitle"><?php echo __('Primary','quiz-maker'); ?></p>
                    </div>
                    <hr class="ays-quiz-bolder-hr"/>
                    <div class="ays-quiz-accordion-options-box">
                        <div class="form-group row">
                            <div class="col-sm-12">
                                <div class="ays-quiz-heading-box ays-quiz-unset-float ays-quiz-unset-margin">
                                    <div class="ays-quiz-wordpress-user-manual-box ays-quiz-wordpress-text-align">
                                        <a href="https://www.youtube.com/watch?v=DHolVT3O0Zk" target="_blank">
                                            <?php echo __("How to create scored quiz - video", 'quiz-maker'); ?>
                                        </a>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-12 ays-quiz-margin-top-20" style="padding:15px;">
                                <div class="form-group row">
                                    <div class="col-sm-4">
                                        <label for="ays_calculate_score">
                                            <?php echo __('Calculate the score','quiz-maker')?>
                                            <a class="ays_help" data-toggle="tooltip" title="<?php echo __('Calculate the score of results by the selected method.','quiz-maker')?>">
                                                <i class="ays_fa ays_fa_info_circle"></i>
                                            </a>
                                        </label>
                                    </div>
                                    <div class="col-sm-8 pro_features_parent">
                                        <label class="ays_quiz_loader">
                                            <input type="radio" class="ays-enable-timer1" value="by_correctness" tabindex="-1" checked/>
                                            <span><?php echo __( "By correctness", 'quiz-maker' ); ?></span>
                                        </label>
                                        <label class="ays_quiz_loader only_pro ays-pro-features-v2-main-box">
                                            <div class="ays-pro-features-v2-small-buttons-box" style="right: 40px; top: -15px;">
                                                <div class="ays-pro-features-v2-video-button ays-quiz-small-new-watch-video-button-box">
                                                    <div>
                                                        <img loading="lazy" src="<?php echo AYS_QUIZ_ADMIN_URL.'/images/icons/video_24x24.svg'?>">
                                                        <img loading="lazy" src="<?php echo AYS_QUIZ_ADMIN_URL.'/images/icons/video_24x24_hover.svg'?>" class="ays-quiz-new-watch-video-button-hover">
                                                    </div>
                                                </div>
                                                <a href="https://ays-pro.com/wordpress/quiz-maker?utm_source=dashboard&utm_medium=quiz-free&utm_campaign=calculate-the-score-<?php echo esc_attr( AYS_QUIZ_UTM_VERSION ); ?>" target="_blank" class="ays-pro-features-v2-upgrade-button">
                                                    <div class="ays-pro-features-v2-upgrade-icon" style="background-image: url('<?php echo esc_attr(AYS_QUIZ_ADMIN_URL); ?>/images/icons/locked_24x24.svg');" data-img-src="<?php echo esc_attr(AYS_QUIZ_ADMIN_URL); ?>/images/icons/locked_24x24.svg"></div>
                                                </a>
                                            </div>
                                            <div class="pro_features pro_features_popup" style="align-items: flex-end;justify-content: flex-end;">
                                                <div class="pro-features-popup-conteiner">
                                                    <div class="pro-features-popup-title">
                                                        <?php echo __("Calculate the score option", 'quiz-maker'); ?>
                                                    </div>
                                                    <div class="pro-features-popup-content" data-link="https://youtu.be/2OYoqJtsjoc">
                                                        <p>
                                                            <?php echo sprintf( __("The option gives you the possibility to choose the most appropriate calculation system for your quiz. You can set the calculation method either %s by Correctness or by Weight/Points. %s", 'quiz-maker'),
                                                                '<strong>',
                                                                '</strong>'
                                                            ); ; ?>
                                                        </p>
                                                        <p>
                                                            <?php echo __("You just need to configure the calculation system depending on the type of quiz you want to create.", 'quiz-maker'); ?>
                                                        </p>
                                                        <div>
                                                            <a href="https://quiz-plugin.com/docs/" target="_blank"><?php echo __("See Documentation", 'quiz-maker'); ?></a>
                                                        </div>
                                                    </div>
                                                    <div class="pro-features-popup-button" data-link="https://ays-pro.com/wordpress/quiz-maker?utm_source=dashboard&utm_medium=quiz-free&utm_campaign=pro-popup-calculate-score-<?php echo esc_attr( AYS_QUIZ_UTM_VERSION ); ?>">
                                                        <?php echo __("Pricing", 'quiz-maker'); ?>
                                                    </div>
                                                </div>
                                            </div>
                                            <input type="radio" class="ays-enable-timer1" tabindex="-1" value="by_points" disabled />
                                            <span><?php echo __( "By weight / points", 'quiz-maker' ); ?></span>
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div><!-- Calculate the score option -->
                        <hr/>
                        <div class="form-group row ays-quiz-result-message-vars-parent">
                            <div class="col-sm-4">
                                <label for="ays_result_text">
                                    <?php echo __('Result message','quiz-maker')?>
                                    <a class="ays_help" data-toggle="tooltip" title="<?php echo __('The message will be displayed after submitting the quiz. You can use Variables (General Settings) to insert user data here. If you want to show results with points or with the number of correct answers, you need to use correspondent variables and enable the “Hide score” option.','quiz-maker')?>">
                                        <i class="ays_fa ays_fa_info_circle"></i>
                                    </a>
                                </label>
                                <p class="ays_quiz_small_hint_text_for_message_variables">
                                    <span><?php echo __( "To see all Message Variables " , 'quiz-maker' ); ?></span>
                                    <a href="?page=quiz-maker-settings&ays_quiz_tab=tab4" target="_blank"><?php echo __( "click here" , 'quiz-maker' ); ?></a>
                                </p>
                            </div>
                            <div class="col-sm-8">
                                <?php
                                echo $quiz_message_vars_html;
                                $content = wpautop(stripslashes((isset($options['result_text'])) ? $options['result_text'] : ''));
                                $editor_id = 'ays_result_text';
                                $settings = array('editor_height' => $quiz_wp_editor_height, 'textarea_name' => 'ays_result_text', 'editor_class' => 'ays-textarea', 'media_elements' => false);
                                wp_editor($content, $editor_id, $settings);
                                ?>
                            </div>
                        </div><!-- Result message -->
                        <hr/>
                        <div class="form-group row">
                            <div class="col-sm-4">
                                <label class="form-check-label" for="ays-pass-score">
                                    <?php echo __("Pass Score (%)", 'quiz-maker') ?>
                                    <a class="ays_help" data-toggle="tooltip" title="<?php echo __('Set the minimum score to pass the quiz in percentage. Please note to give a value to it above 0, otherwise, the Quiz pass message and Quiz fail message options will not work.','quiz-maker'); ?>">
                                        <i class="ays_fa ays_fa_info_circle"></i>
                                    </a>
                                </label>
                            </div>
                            <div class="col-sm-8">
                                <?php if(1 == 0): ?>
                                <div class="form-group row" style="margin:0px;">
                                    <div class="col-sm-12 only_pro" style="padding:10px 0 0 10px;">
                                        <div class="pro_features" style="justify-content:flex-end;">

                                        </div>
                                        <div class="form-group row">
                                            <div class="col-sm-12">
                                                <label class="ays_quiz_loader">
                                                    <input type="radio" class="ays-enable-timer1" checked/>
                                                    <span><?php echo __( "Percentage", 'quiz-maker' ); ?></span>
                                                </label>
                                                <label class="ays_quiz_loader">
                                                    <input type="radio" class="ays-enable-timer1" />
                                                    <span><?php echo __( "Points", 'quiz-maker' ); ?></span>
                                                </label>
                                            </div>
                                        </div>
                                        <a href="https://ays-pro.com/wordpress/quiz-maker?utm_source=dashboard&utm_medium=quiz-free&utm_campaign=pass-score-<?php echo esc_attr( AYS_QUIZ_UTM_VERSION ); ?>" target="_blank" class="ays-quiz-new-upgrade-button-link">
                                            <div class="ays-quiz-new-upgrade-button-box">
                                                <div>
                                                    <img loading="lazy" src="<?php echo AYS_QUIZ_ADMIN_URL.'/images/icons/locked_24x24.svg'?>">
                                                    <img loading="lazy" src="<?php echo AYS_QUIZ_ADMIN_URL.'/images/icons/unlocked_24x24.svg'?>" class="ays-quiz-new-upgrade-button-hover">
                                                </div>
                                                <div class="ays-quiz-new-upgrade-button"><?php echo __("Upgrade", "quiz-maker"); ?></div>
                                            </div>
                                        </a>
                                    </div>
                                </div>
                                <hr>
                                <?php endif; ?>
                                <div class="form-group row">
                                    <div class="col-sm-12">
                                         <input type="number" class="ays-text-input" id='ays-pass-score' name='ays_pass_score' value="<?php echo $pass_score; ?>"/>
                                    </div>
                                </div>
                                <hr/>
                                <div class="form-group row ays-quiz-result-message-vars-parent">
                                    <div class="col-sm-4">
                                        <label class="form-check-label" for="ays_pass_score_message">
                                            <?php echo __("Quiz pass message", 'quiz-maker') ?>
                                            <a class="ays_help" data-toggle="tooltip" title="<?php echo __('The message in the case of the user passes the quiz','quiz-maker')?>">
                                                <i class="ays_fa ays_fa_info_circle"></i>
                                            </a>
                                        </label>
                                        <p class="ays_quiz_small_hint_text_for_message_variables">
                                            <span><?php echo __( "To see all Message Variables " , 'quiz-maker' ); ?></span>
                                            <a href="?page=quiz-maker-settings&ays_quiz_tab=tab4" target="_blank"><?php echo __( "click here" , 'quiz-maker' ); ?></a>
                                        </p>
                                    </div>
                                    <div class="col-sm-8">
                                        <?php echo $quiz_message_vars_html; ?>
                                        <div class="editor">
                                            <?php
                                            $editor_id = 'ays_pass_score_message';
                                            $settings  = array(
                                                'editor_height'  => $quiz_wp_editor_height,
                                                'textarea_name'  => 'ays_pass_score_message',
                                                'editor_class'   => 'ays-textarea',
                                                'media_elements' => false
                                            );
                                            wp_editor($pass_score_message, $editor_id, $settings);
                                            ?>
                                        </div>
                                    </div>
                                </div>
                                <hr/>
                                <div class="form-group row ays-quiz-result-message-vars-parent">
                                    <div class="col-sm-4">
                                        <label class="form-check-label" for="ays_fail_score_message">
                                            <?php echo __("Quiz fail message", 'quiz-maker') ?>
                                            <a class="ays_help" data-toggle="tooltip" title="<?php echo __('The message in the case of the user fails the quiz','quiz-maker')?>">
                                                <i class="ays_fa ays_fa_info_circle"></i>
                                            </a>
                                        </label>
                                        <br>
                                        <p class="ays_quiz_small_hint_text_for_message_variables">
                                            <span><?php echo __( "To see all Message Variables " , 'quiz-maker' ); ?></span>
                                            <a href="?page=quiz-maker-settings&ays_quiz_tab=tab4" target="_blank"><?php echo __( "click here" , 'quiz-maker' ); ?></a>
                                        </p>
                                    </div>
                                    <div class="col-sm-8">
                                        <?php echo $quiz_message_vars_html; ?>
                                        <div class="editor">
                                            <?php
                                            $editor_id = 'ays_fail_score_message';
                                            $settings  = array(
                                                'editor_height'  => $quiz_wp_editor_height,
                                                'textarea_name'  => 'ays_fail_score_message',
                                                'editor_class'   => 'ays-textarea',
                                                'media_elements' => false
                                            );
                                            wp_editor($fail_score_message, $editor_id, $settings);
                                            ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div><!-- Pass Score (%) -->
                        <hr/>
                        <div class="form-group row">
                            <div class="col-sm-4">
                                <label>
                                    <?php echo __('Display score','quiz-maker')?>
                                    <a class="ays_help" data-toggle="tooltip" title="<?php echo __('How to display score of result','quiz-maker')?>">
                                        <i class="ays_fa ays_fa_info_circle"></i>
                                    </a>
                                </label>
                            </div>
                            <div class="col-sm-8">
                                <label class="ays_quiz_loader">
                                    <input type="radio" class="ays-enable-timer1" name="ays_display_score" value="by_percantage" <?php echo ($display_score == 'by_percantage') ? 'checked' : '' ?>/>
                                    <span><?php echo __( "By percentage", 'quiz-maker' ); ?></span>
                                </label>
                                <label class="ays_quiz_loader">
                                    <input type="radio" class="ays-enable-timer1" name="ays_display_score" value="by_correctness" <?php echo ($display_score == 'by_correctness') ? 'checked' : '' ?>/>
                                    <span><?php echo __( "By correct answers count", 'quiz-maker' ); ?></span>
                                </label>
                            </div>
                        </div><!-- Display score -->
                        <hr/>
                        <div class="form-group row">
                            <div class="col-sm-4">
                                <label for="ays_hide_score">
                                    <?php echo __('Hide score','quiz-maker')?>
                                    <a class="ays_help" data-toggle="tooltip" title="<?php echo __('Disable to show the user score with percentage on the finish page. If you want to show points or correct answers count, you need to tick this option and use Variables (General Settings) in the “Text for showing after quiz completion” option.','quiz-maker')?>">
                                        <i class="ays_fa ays_fa_info_circle"></i>
                                    </a>
                                </label>
                            </div>
                            <div class="col-sm-8">
                                <input type="checkbox" class="ays-enable-timer1" id="ays_hide_score"
                                       name="ays_hide_score"
                                       value="on" <?php echo (isset($options['hide_score']) && $options['hide_score'] == 'on') ? 'checked' : '' ?>/>
                            </div>
                        </div><!-- Hide score -->
                        <hr/>
                        <div class="form-group row ays_toggle_parent">
                            <div class="col-sm-4">
                                <label for="ays_redirect_after_submit">
                                    <?php echo __('Redirect after submission','quiz-maker'); ?>
                                    <a class="ays_help" data-toggle="tooltip" title="<?php echo __('Redirect to custom URL after user submit the form.','quiz-maker'); ?>">
                                        <i class="ays_fa ays_fa_info_circle"></i>
                                    </a>
                                </label>
                            </div>
                            <div class="col-sm-1">
                                <input type="checkbox" class="ays-enable-timer1 ays_toggle_checkbox" id="ays_redirect_after_submit"
                                       name="ays_redirect_after_submit"
                                       value="on" <?php echo $redirect_after_submit ? 'checked' : '' ?>/>
                            </div>
                            <div class="col-sm-7 ays_toggle_target ays_divider_left <?php echo $redirect_after_submit ? '' : 'display_none'; ?>">                                
                                <div class="form-group row">
                                    <div class="col-sm-4">
                                        <label for="ays_submit_redirect_url">
                                            <?php echo __('Redirect URL','quiz-maker')?>
                                            <a class="ays_help" data-toggle="tooltip" title="<?php echo __('The URL for redirecting after the user submits the form.','quiz-maker')?>">
                                                <i class="ays_fa ays_fa_info_circle"></i>
                                            </a>
                                        </label>
                                    </div>
                                    <div class="col-sm-8">
                                        <input type="text" class="ays-text-input" id="ays_submit_redirect_url"
                                            name="ays_submit_redirect_url"
                                            value="<?php echo $submit_redirect_url; ?>"/>
                                    </div>
                                </div>
                                <div class="form-group row" style="margin:0px;">
                                    <div class="col-sm-12 only_pro" style="padding:10px 0 0 10px;">
                                        <div class="pro_features" style="justify-content:flex-end;">

                                        </div>
                                        <span style="display:block; pointer-events: none;" class="ays_quiz_small_hint_text">
                                            <?php echo 
                                                sprintf( __("Add '%s' phrase at the end of the url.%s Use %s line in your code, by replacing the 'if_false' with a value that will be returned in case something goes wrong.", 'quiz-maker'),
                                                "<strong class='ays_help'>[uniquecode]</strong>",
                                                "<br>",
                                                "<strong class='ays_help'>apply_filters('ays_quiz_get_submission_results', 'if_false')</strong>"
                                                ); 
                                            ?>
                                        </span>
                                        <a href="https://ays-pro.com/wordpress/quiz-maker?utm_source=dashboard&utm_medium=quiz-free&utm_campaign=redirect-url-hook-<?php echo esc_attr( AYS_QUIZ_UTM_VERSION ); ?>" target="_blank" class="ays-quiz-new-upgrade-button-link">
                                            <div class="ays-quiz-new-upgrade-button-box">
                                                <div>
                                                    <img loading="lazy" src="<?php echo AYS_QUIZ_ADMIN_URL.'/images/icons/locked_24x24.svg'?>">
                                                    <img loading="lazy" src="<?php echo AYS_QUIZ_ADMIN_URL.'/images/icons/unlocked_24x24.svg'?>" class="ays-quiz-new-upgrade-button-hover">
                                                </div>
                                                <div class="ays-quiz-new-upgrade-button"><?php echo __("Upgrade", "quiz-maker"); ?></div>
                                            </div>
                                        </a>
                                    </div>
                                </div>
                                <hr/>
                                <div class="form-group row">
                                    <div class="col-sm-4">
                                        <label for="ays_submit_redirect_delay">
                                            <?php echo __('Redirect delay (sec)', 'quiz-maker')?>
                                            <a class="ays_help" data-toggle="tooltip" title="<?php echo __('The redirection delay in seconds after the user submits the form. Value should be greater than 0.','quiz-maker')?>">
                                                <i class="ays_fa ays_fa_info_circle"></i>
                                            </a>
                                        </label>
                                    </div>
                                    <div class="col-sm-8">
                                        <input type="number" class="ays-text-input" id="ays_submit_redirect_delay"
                                            name="ays_submit_redirect_delay"
                                            value="<?php echo $submit_redirect_delay; ?>"/>
                                    </div>
                                </div>
                                <hr/>
                                <div class="form-group row">
                                    <div class="col-sm-4">
                                        <label for="ays_quiz_message_before_redirect_timer">
                                            <?php echo __('Message before redirect timer','quiz-maker'); ?>
                                            <a class="ays_help" data-toggle="tooltip" title="<?php echo esc_attr( __('Write a message to display before the timer. For example, "You will be redirected in 00:30".','quiz-maker') ); ?>">
                                                <i class="ays_fa ays_fa_info_circle"></i>
                                            </a>
                                        </label>
                                    </div>
                                    <div class="col-sm-8">
                                        <input type="text" class="ays-text-input" id="ays_quiz_message_before_redirect_timer" name="ays_quiz_message_before_redirect_timer" value="<?php echo $quiz_message_before_redirect_timer; ?>"/>
                                    </div>
                                </div>
                            </div>
                        </div><!-- Redirect after submission -->
                        <hr/>
                    </div>
                </div>
                <div class="ays-quiz-accordion-options-main-container" data-collapsed="false">
                    <div class="ays-quiz-accordion-container">
                        <?php echo $quiz_accordion_svg_html; ?>
                        <p class="ays-subtitle"><?php echo __('Completion Actions','quiz-maker'); ?></p>
                    </div>
                    <hr class="ays-quiz-bolder-hr"/>
                    <div class="ays-quiz-accordion-options-box">
                        <div class="form-group row">
                            <div class="col-sm-4">
                                <label>
                                    <?php echo __('Quiz loader icon','quiz-maker')?>
                                    <a class="ays_help" data-toggle="tooltip" title="<?php echo __('Choose the design of the loader on the finish page after submitting. It will inherit the Quiz Text color from the Styles tab.','quiz-maker')?>">
                                        <i class="ays_fa ays_fa_info_circle"></i>
                                    </a>
                                </label>
                            </div>
                            <div class="col-sm-8 ays_toggle_loader_parent">
                                <label class="ays_quiz_loader">
                                    <input name="ays_quiz_loader" class="ays_toggle_loader_radio" data-flag="false" data-type="loader" type="radio" value="default" <?php echo ($quiz_loader == 'default') ? 'checked' : ''; ?>>
                                    <div class="lds-ellipsis"><div></div><div></div><div></div><div></div></div>
                                </label>
                                <label class="ays_quiz_loader">
                                    <input name="ays_quiz_loader" class="ays_toggle_loader_radio" data-flag="false" data-type="loader" type="radio" value="circle" <?php echo ($quiz_loader == 'circle') ? 'checked' : ''; ?>>
                                    <div class="lds-circle"></div>
                                </label>
                                <label class="ays_quiz_loader">
                                    <input name="ays_quiz_loader" class="ays_toggle_loader_radio" data-flag="false" data-type="loader" type="radio" value="dual_ring" <?php echo ($quiz_loader == 'dual_ring') ? 'checked' : ''; ?>>
                                    <div class="lds-dual-ring"></div>
                                </label>
                                <label class="ays_quiz_loader">
                                    <input name="ays_quiz_loader" class="ays_toggle_loader_radio" data-flag="false" data-type="loader" type="radio" value="facebook" <?php echo ($quiz_loader == 'facebook') ? 'checked' : ''; ?>>
                                    <div class="lds-facebook"><div></div><div></div><div></div></div>
                                </label>
                                <label class="ays_quiz_loader">
                                    <input name="ays_quiz_loader" class="ays_toggle_loader_radio" data-flag="false" data-type="loader" type="radio" value="hourglass" <?php echo ($quiz_loader == 'hourglass') ? 'checked' : ''; ?>>
                                    <div class="lds-hourglass"></div>
                                </label>
                                <label class="ays_quiz_loader">
                                    <input name="ays_quiz_loader" class="ays_toggle_loader_radio" data-flag="false" data-type="loader" type="radio" value="ripple" <?php echo ($quiz_loader == 'ripple') ? 'checked' : ''; ?>>
                                    <div class="lds-ripple"><div></div><div></div></div>
                                </label>
                                <hr/>
                                <label class="ays_quiz_loader">
                                    <input name="ays_quiz_loader" class="ays_toggle_loader_radio" data-flag="true" data-type="text" type="radio" value="text" <?php echo ($quiz_loader == 'text') ? 'checked' : ''; ?>>
                                    <div class="ays_quiz_loader_text">
                                        <?php echo __( "Text" , 'quiz-maker' ); ?>
                                    </div>
                                    <div class="ays_toggle_loader_target <?php echo ($quiz_loader == 'text') ? '' : 'display_none' ?>" data-type="text">
                                        <input type="text" class="ays-text-input" data-type="text" id="ays_quiz_loader_text_value" name="ays_quiz_loader_text_value" value="<?php echo $quiz_loader_text_value; ?>">
                                    </div>
                                </label>
                                <label class="ays_quiz_loader">
                                    <input name="ays_quiz_loader" class="ays_toggle_loader_radio" data-flag="true" data-type="gif" type="radio" value="custom_gif" <?php echo ($quiz_loader == 'custom_gif') ? 'checked' : ''; ?>>
                                    <div class="ays_quiz_loader_custom_gif">
                                        <?php echo __( "Gif" , 'quiz-maker' ); ?>
                                    </div>
                                    <div class="ays_toggle_loader_target ays-image-wrap <?php echo ($quiz_loader == 'custom_gif') ? '' : 'display_none' ?>" data-type="gif">
                                        <a href="javascript:void(0)" style="<?php echo ($quiz_loader_custom_gif == '') ? 'display:inline-block' : 'display:none'; ?>" class="ays-add-image add_quiz_loader_custom_gif"><?php echo __('Add Gif', 'quiz-maker'); ?></a>
                                        <input type="hidden" class="ays-image-path" id="ays_quiz_loader_custom_gif" name="ays_quiz_loader_custom_gif" value="<?php echo $quiz_loader_custom_gif; ?>"/>
                                        <div class="ays-image-container" style="<?php echo ($quiz_loader_custom_gif == '') ? 'display:none' : 'display:block'; ?>">
                                            <span class="ays-edit-img ays-edit-quiz-loader-custom-gif">
                                                <i class="ays_fa ays_fa_pencil_square_o"></i>
                                            </span>
                                            <span class="add_quiz_loader_custom_gif ays-remove-quiz-loader-custom-gif"></span>
                                            <img loading="lazy"  src="<?php echo $quiz_loader_custom_gif; ?>" class="img_quiz_loader_custom_gif"/>
                                        </div>
                                    </div>
                                    <div class="ays_toggle_loader_target ays_gif_loader_width_container <?php echo ($quiz_loader == 'custom_gif') ? 'display_flex' : 'display_none'; ?>" data-type="gif" style="margin: 10px;">
                                        <div>
                                            <label for='ays_quiz_loader_custom_gif_width'>
                                                <?php echo __('Width (px)', 'quiz-maker'); ?>
                                                <a class="ays_help" data-toggle="tooltip" title="<?php echo __('Custom Gif width in pixels. It accepts only numeric values.','quiz-maker'); ?>">
                                                    <i class="ays_fa ays_fa_info_circle"></i>
                                                </a>
                                            </label>
                                        </div>
                                        <div style="margin-left: 5px;">
                                            <input type="number" class="ays-text-input" id='ays_quiz_loader_custom_gif_width' name='ays_quiz_loader_custom_gif_width' value="<?php echo ( $quiz_loader_custom_gif_width ); ?>"/>
                                        </div>
                                    </div>
                                </label>
                            </div>
                        </div><!-- Quiz loader icon -->
                        <hr/>
                        <div class="form-group row ays_toggle_parent">
                            <div class="col-sm-4">
                                <label for="ays_enable_restart_button">
                                    <?php echo __('Enable restart button','quiz-maker')?>
                                    <a class="ays_help" data-toggle="tooltip" title="<?php echo __('Show the restart button at the end of the quiz for restarting the quiz and pass it again.','quiz-maker')?>">
                                        <i class="ays_fa ays_fa_info_circle"></i>
                                    </a>
                                </label>
                            </div>
                            <div class="col-sm-1">
                                <input type="checkbox" class="ays-enable-timer1 ays_toggle_checkbox" id="ays_enable_restart_button"
                                       name="ays_enable_restart_button"
                                       value="on" <?php echo ($enable_restart_button) ? 'checked' : '' ?>/>
                            </div>
                            <div class="col-sm-7 ays_toggle_target ays_divider_left <?php echo $enable_restart_button ? '' : 'display_none'; ?>">
                                <div class="form-group row">
                                    <div class="col-sm-4">
                                        <label for="ays_quiz_show_restart_button_on_quiz_fail">
                                            <?php echo __('Show button on quiz fail', 'quiz-maker'); ?>
                                            <a class="ays_help" data-toggle="tooltip" title="<?php echo esc_attr(__('If this option is activated the "Restart" button will be displayed only when the user failed the quiz.', 'quiz-maker')); ?>">
                                                <i class="ays_fa ays_fa_info_circle"></i>
                                            </a>
                                        </label>
                                    </div>
                                    <div class="col-sm-8">
                                        <input type="checkbox" class="ays-enable-timer1" id="ays_quiz_show_restart_button_on_quiz_fail" name="ays_quiz_show_restart_button_on_quiz_fail" value="on" <?php echo ($quiz_show_restart_button_on_quiz_fail) ? 'checked' : '' ?>/>
                                    </div>
                                </div>
                            </div><!-- Show button on quiz fail -->
                        </div><!-- Enable restart button -->
                        <hr/>
                        <div class="form-group row ays_toggle_parent">
                            <div class="col-sm-4">
                                <label for="ays_questions_result_option">
                                    <?php echo __('Show question results on the results page','quiz-maker'); ?>
                                    <a class="ays_help" data-toggle="tooltip" title="<?php echo __('Show all questions with right and wrong answers after quiz','quiz-maker'); ?>">
                                        <i class="ays_fa ays_fa_info_circle"></i>
                                    </a>
                                </label>
                            </div>
                            <div class="col-sm-1">
                                <input type="checkbox" class="ays-enable-timer1 ays_toggle_checkbox" id="ays_questions_result_option" name="ays_enable_questions_result" value="on" <?php echo ($enable_questions_result) ? 'checked' : '' ?>/>
                            </div>
                            <div class="col-sm-7 ays_toggle_target ays_divider_left <?php echo $enable_questions_result ? '' : 'display_none'; ?>">                                
                                <div class="form-group row">
                                    <div class="col-sm-4">
                                        <label for="ays_hide_correct_answers">
                                            <?php echo __('Hide correct answers','quiz-maker')?>
                                            <a class="ays_help" data-toggle="tooltip" title="<?php echo __('After enabling this option, the user whose chosen answer to the question is wrong will not see the right one.','quiz-maker')?>">
                                                <i class="ays_fa ays_fa_info_circle"></i>
                                            </a>
                                        </label>
                                    </div>
                                    <div class="col-sm-8">
                                        <input type="checkbox" class="ays-enable-timer1" id="ays_hide_correct_answers" name="ays_hide_correct_answers" value="on" <?php echo ($hide_correct_answers) ? 'checked' : '' ?>/>
                                    </div>
                                </div>
                                <hr>
                                <div class="form-group row">
                                    <div class="col-sm-4">
                                        <label for="ays_quiz_show_wrong_answers_first">
                                            <?php echo __('Show wrong answers first','quiz-maker'); ?>
                                            <a class="ays_help" data-toggle="tooltip" title="<?php echo __('Tick the checkbox if you want to show the wrongly answered questions by the particular user in the first place on the result page.','quiz-maker'); ?>">
                                                <i class="ays_fa ays_fa_info_circle"></i>
                                            </a>
                                        </label>
                                    </div>
                                    <div class="col-sm-8">
                                        <input type="checkbox" class="ays-enable-timer1" id="ays_quiz_show_wrong_answers_first" name="ays_quiz_show_wrong_answers_first" value="on" <?php echo ($quiz_show_wrong_answers_first) ? 'checked' : ''; ?> />
                                    </div>
                                </div>
                                <hr>
                                <div class="form-group row">
                                    <div class="col-sm-4">
                                        <label for="ays_quiz_show_only_wrong_answers">
                                            <?php echo __('Show only wrong answers','quiz-maker'); ?>
                                            <a class="ays_help" data-toggle="tooltip" title="<?php echo __('Tick this option if you want to see only the wrong answers on the quiz results page.','quiz-maker'); ?>">
                                                <i class="ays_fa ays_fa_info_circle"></i>
                                            </a>
                                        </label>
                                    </div>
                                    <div class="col-sm-8">
                                        <input type="checkbox" class="ays-enable-timer1" id="ays_quiz_show_only_wrong_answers" name="ays_quiz_show_only_wrong_answers" value="on" <?php echo ($quiz_show_only_wrong_answers) ? 'checked' : ''; ?> />
                                    </div>
                                </div>
                                <hr>
                                <div class="form-group row ays_toggle_parent">
                                    <div class="col-sm-4">
                                        <label for="ays_quiz_enable_results_toggle">
                                            <?php echo __('Enable the Show/Hide toggle','quiz-maker'); ?>
                                            <a class="ays_help" data-toggle="tooltip" title="<?php echo __('If you enable this option, a toggle will be displayed by which you can show/hide the results of the quiz questions on the Front-end.','quiz-maker'); ?>">
                                                <i class="ays_fa ays_fa_info_circle"></i>
                                            </a>
                                        </label>
                                    </div>
                                    <div class="col-sm-1">
                                        <input type="checkbox" class="ays-enable-timer1 ays_toggle_checkbox" id="ays_quiz_enable_results_toggle" name="ays_quiz_enable_results_toggle" value="on" <?php echo ($quiz_enable_results_toggle) ? 'checked' : ''; ?> />
                                    </div>
                                    <div class="col-sm-7 ays_toggle_target ays_divider_left <?php echo $quiz_enable_results_toggle ? '' : 'display_none'; ?>">
                                        <div class="form-group row">
                                            <div class="col-sm-4">
                                                <label for="ays_quiz_enable_default_hide_results_toggle">
                                                    <?php echo esc_html__('Enable Default Hide', 'quiz-maker'); ?>
                                                    <a class="ays_help" data-toggle="tooltip" title="<?php echo esc_attr( __('If this option is activated, in the front end the answers will be by default hidden. The users will need to choose the show toggle to display it.', 'quiz-maker') ); ?>">
                                                        <i class="ays_fa ays_fa_info_circle"></i>
                                                    </a>
                                                </label>
                                            </div>
                                            <div class="col-sm-8">
                                                <input type="checkbox" class="ays-enable-timer1" id="ays_quiz_enable_default_hide_results_toggle" name="ays_quiz_enable_default_hide_results_toggle" value="on" <?php echo ($quiz_enable_default_hide_results_toggle) ? 'checked' : ''; ?> />
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div><!-- Show question results on the results page -->
                        <hr/>
                        <div class="form-group row">
                            <div class="col-sm-4">
                                <label for="ays_enable_bar_option">
                                    <?php echo __('Enable progress bar','quiz-maker'); ?>
                                    <a class="ays_help" data-toggle="tooltip" title="<?php echo __('Show score via progressbar','quiz-maker'); ?>">
                                        <i class="ays_fa ays_fa_info_circle"></i>
                                    </a>
                                </label>
                            </div>
                            <div class="col-sm-8">
                                <input type="checkbox" class="ays-enable-timer1" id="ays_enable_bar_option"
                                       name="ays_enable_progress_bar"
                                       value="on" <?php echo (isset($options['enable_progress_bar']) && $options['enable_progress_bar'] == 'on') ? 'checked' : '' ?>/>
                            </div>
                        </div><!-- Enable progress bar -->
                        <hr/>
                        <div class="form-group row">
                            <div class="col-sm-4" style="padding-right: 0px;">
                                <label for="ays_enable_quiz_rate">
                                    <?php echo __('Enable quiz assessment','quiz-maker'); ?>
                                    <a class="ays_help" data-toggle="tooltip" title="<?php echo __('Comment and rate the quiz with up to 5 stars at the end of the quiz.','quiz-maker'); ?>">
                                        <i class="ays_fa ays_fa_info_circle"></i>
                                    </a>
                                </label>
                            </div>
                            <div class="col-sm-1">
                                <input type="checkbox" id="ays_enable_quiz_rate"
                                       name="ays_enable_quiz_rate"
                                       value="on" <?php echo ($enable_quiz_rate == 'on') ? 'checked' : ''; ?>/>
                            </div>
                            <div class="col-sm-7 ays_hidden" style="border-left: 1px solid #ededed; <?php echo ($enable_quiz_rate == 'on') ? '' : 'display:none;' ?>">
                                <div class="form-group row">
                                    <div class="col-sm-4" style="padding-right: 0px;">
                                        <label for="ays_enable_rate_comments">
                                            <?php echo __('Show the last 5 reviews','quiz-maker')?>
                                            <a class="ays_help" data-toggle="tooltip" title="<?php echo __('Show the last 5 reviews after rating the quiz.','quiz-maker'); ?>">
                                                <i class="ays_fa ays_fa_info_circle"></i>
                                            </a>
                                        </label>
                                    </div>
                                    <div class="col-sm-8">
                                        <input type="checkbox" id="ays_enable_rate_comments"
                                               name="ays_enable_rate_comments"
                                               value="on" <?php echo ($enable_rate_comments == 'on') ? 'checked' : ''; ?>/>
                                    </div>
                                </div>
                                <hr>
                                <div class="form-group row">
                                    <div class="col-sm-4">
                                        <label for="ays_quiz_make_responses_anonymous">
                                            <?php echo __('Make responses anonymous','quiz-maker'); ?>
                                            <a class="ays_help" data-toggle="tooltip" title="<?php echo __('Collect anonymous responses no matter the quiz taker is a logged-in user or guest.','quiz-maker'); ?>">
                                                <i class="ays_fa ays_fa_info_circle"></i>
                                            </a>
                                        </label>
                                    </div>
                                    <div class="col-sm-8">
                                        <input type="checkbox" name="ays_quiz_make_responses_anonymous" id="ays_quiz_make_responses_anonymous" value="on" <?php echo ($quiz_make_responses_anonymous) ? 'checked' : ''; ?>/>
                                    </div>
                                </div>
                                <hr>
                                <div class="form-group row">
                                    <div class="col-sm-4">
                                        <label for="ays_quiz_enable_user_cհoosing_anonymous_assessment">
                                            <?php echo __("Enable users' anonymous assessment",'quiz-maker'); ?>
                                            <a class="ays_help" data-toggle="tooltip" title="<?php echo __('By ticking this option, the users can choose to leave a rating anonymously.','quiz-maker'); ?>">
                                                <i class="ays_fa ays_fa_info_circle"></i>
                                            </a>
                                        </label>
                                    </div>
                                    <div class="col-sm-8">
                                        <input type="checkbox" name="ays_quiz_enable_user_cհoosing_anonymous_assessment" id="ays_quiz_enable_user_cհoosing_anonymous_assessment"
                                               <?php echo ($quiz_enable_user_cհoosing_anonymous_assessment) ? 'checked' : ''; ?>/>
                                    </div>
                                </div>
                                <hr>
                                <div class="form-group row">
                                    <div class="col-sm-4">
                                        <label for="ays_quiz_make_all_review_link">
                                            <?php echo __('Display all reviews button','quiz-maker'); ?>
                                            <a class="ays_help" data-toggle="tooltip" title="<?php echo __('Tick the option, and the quiz taker will have the opportunity to see all feedbacks written by others.','quiz-maker'); ?>">
                                                <i class="ays_fa ays_fa_info_circle"></i>
                                            </a>
                                        </label>
                                    </div>
                                    <div class="col-sm-8">
                                        <input type="checkbox" name="ays_quiz_make_all_review_link" id="ays_quiz_make_all_review_link" <?php echo ($quiz_make_all_review_link) ? 'checked' : ''; ?>/>
                                    </div>
                                </div>
                                <hr>
                                <div class="form-group row">
                                    <div class="col-sm-4">
                                        <label for="ays_quiz_review_enable_comment_field">
                                            <?php echo __('Enable Comment Field','quiz-maker'); ?>
                                            <a class="ays_help" data-toggle="tooltip" title="<?php echo __('By disabling this option, the comment field will be hidden and you can only rate with stars. This option is enabled by default.','quiz-maker'); ?>">
                                                <i class="ays_fa ays_fa_info_circle"></i>
                                            </a>
                                        </label>
                                    </div>
                                    <div class="col-sm-8">
                                        <input type="checkbox" name="ays_quiz_review_enable_comment_field" id="ays_quiz_review_enable_comment_field" <?php echo ($quiz_review_enable_comment_field) ? 'checked' : ''; ?>/>
                                    </div>
                                </div>
                                <hr>
                                <div class="form-group row">
                                    <div class="col-sm-4">
                                        <label for="ays_quiz_make_review_required">
                                            <?php echo __('Make the review field required','quiz-maker'); ?>
                                            <a class="ays_help" data-toggle="tooltip" title="<?php echo esc_attr( __("If this option is enabled, the users can't send a feedback without writing a review.",'quiz-maker') ); ?>">
                                                <i class="ays_fa ays_fa_info_circle"></i>
                                            </a>
                                        </label>
                                    </div>
                                    <div class="col-sm-8">
                                        <input type="checkbox" name="ays_quiz_make_review_required" id="ays_quiz_make_review_required" <?php echo ($quiz_make_review_required) ? 'checked' : ''; ?>/>
                                    </div>
                                </div>
                                <hr/>
                                <div class="form-group row">
                                    <div class="col-sm-4">
                                        <label for="ays_quiz_review_placeholder_text">
                                            <?php echo __('Placeholder text','quiz-maker'); ?>
                                            <a class="ays_help" data-toggle="tooltip" title="<?php echo esc_attr( __('Write your custom placeholder for the Rating form.','quiz-maker') ); ?>">
                                                <i class="ays_fa ays_fa_info_circle"></i>
                                            </a>
                                        </label>
                                    </div>
                                    <div class="col-sm-8">
                                        <input type="text" class="ays-text-input" id="ays_quiz_review_placeholder_text" name="ays_quiz_review_placeholder_text" value="<?php echo $quiz_review_placeholder_text; ?>"/>
                                    </div>
                                </div>
                                <hr>
                                <div class="form-group row ays-quiz-result-message-vars-parent">
                                    <div class="col-sm-4" style="padding-right: 0px;">
                                        <label for="ays_rate_form_title">
                                            <?php echo __('Rating form title','quiz-maker')?>
                                            <a class="ays_help" data-toggle="tooltip" title="<?php echo __('Text which will notify user that he can submit a feedback','quiz-maker')?>">
                                                <i class="ays_fa ays_fa_info_circle"></i>
                                            </a>
                                        </label>
                                    </div>
                                    <div class="col-sm-8">
                                        <?php
                                        echo $quiz_message_vars_rating_form_title_html;
                                        $content = stripslashes(wpautop($rate_form_title));
                                        $editor_id = 'ays_rate_form_title';
                                        $settings = array('editor_height' => $quiz_wp_editor_height, 'textarea_name' => 'ays_rate_form_title', 'editor_class' => 'ays-textarea', 'media_elements' => false);
                                        wp_editor($content, $editor_id, $settings);
                                        ?>
                                    </div>
                                </div>
                                <hr>
                                <div class="form-group row ays-quiz-result-message-vars-parent">
                                    <div class="col-sm-4" style="padding-right: 0px;">
                                        <label for="ays_quiz_review_thank_you_message">
                                            <?php echo __('Thank you message','quiz-maker')?>
                                            <a class="ays_help" data-toggle="tooltip" title="<?php echo __('By enabling this option, the text written in the editor is displayed when the user writes a review for the quiz.','quiz-maker'); ?>">
                                                <i class="ays_fa ays_fa_info_circle"></i>
                                            </a>
                                        </label>
                                    </div>
                                    <div class="col-sm-8">
                                        <?php
                                        echo $quiz_message_vars_review_thank_you_message_html;
                                        $content = $quiz_review_thank_you_message;
                                        $editor_id = 'ays_quiz_review_thank_you_message';
                                        $settings = array('editor_height' => $quiz_wp_editor_height, 'textarea_name' => 'ays_quiz_review_thank_you_message', 'editor_class' => 'ays-textarea', 'media_elements' => false);
                                        wp_editor($content, $editor_id, $settings);
                                        ?>
                                    </div>
                                </div>
                            </div>
                        </div><!-- Enable quiz assessment -->
                        <hr/>
                    </div>
                </div>
                <div class="ays-quiz-accordion-options-main-container" data-collapsed="false">
                    <div class="ays-quiz-accordion-container">
                        <?php echo $quiz_accordion_svg_html; ?>
                        <p class="ays-subtitle"><?php echo __('Advanced','quiz-maker'); ?></p>
                    </div>
                    <hr class="ays-quiz-bolder-hr"/>
                    <div class="ays-quiz-accordion-options-box">
                        <div class="form-group row ays_toggle_parent">
                            <div class="col-sm-4">
                                <label for="ays_enable_exit_button">
                                    <?php echo __('Enable Exit button','quiz-maker'); ?>
                                    <a class="ays_help" data-toggle="tooltip" title="<?php echo __('Exit button will be displayed in the finish page and must redirect the user to a custom URL.','quiz-maker'); ?>">
                                        <i class="ays_fa ays_fa_info_circle"></i>
                                    </a>
                                </label>
                            </div>
                            <div class="col-sm-1">
                                <input type="checkbox" class="ays-enable-timer1 ays_toggle_checkbox" id="ays_enable_exit_button"
                                       name="ays_enable_exit_button"
                                       value="on" <?php echo $enable_exit_button ? 'checked' : '' ?>/>
                            </div>
                            <div class="col-sm-7 ays_toggle_target ays_divider_left <?php echo $enable_exit_button ? '' : 'display_none'; ?>">                                
                                <div class="form-group row">
                                    <div class="col-sm-4">
                                        <label for="ays_exit_redirect_url">
                                            <?php echo __('Redirect URL','quiz-maker')?>
                                            <a class="ays_help" data-toggle="tooltip" title="<?php echo __('The custom URL address for EXIT button in finish page.','quiz-maker')?>">
                                                <i class="ays_fa ays_fa_info_circle"></i>
                                            </a>
                                        </label>
                                    </div>
                                    <div class="col-sm-8">
                                        <input type="text" class="ays-text-input" id="ays_exit_redirect_url"
                                            name="ays_exit_redirect_url"
                                            value="<?php echo $exit_redirect_url; ?>"/>
                                    </div>
                                </div>
                            </div>
                        </div><!-- Enable Exit button -->
                        <hr/>
                        <div class="form-group row">
                            <div class="col-sm-4">
                                <label for="ays_average_statistical_option">
                                    <?php echo __('Show the statistical average','quiz-maker'); ?>
                                    <a class="ays_help" data-toggle="tooltip" title="<?php echo __('Show average score according to all results of the quiz','quiz-maker'); ?>">
                                        <i class="ays_fa ays_fa_info_circle"></i>
                                    </a>
                                </label>
                            </div>
                            <div class="col-sm-8">
                                <input type="checkbox" class="ays-enable-timer1" id="ays_average_statistical_option"
                                       name="ays_enable_average_statistical"
                                       value="on" <?php echo (isset($options['enable_average_statistical']) && $options['enable_average_statistical'] == 'on') ? 'checked' : '' ?>/>
                            </div>
                        </div><!-- Show the statistical average -->
                        <hr/>
                        <div class="form-group row ays_toggle_parent ays-quiz-result-message-vars-parent">
                            <div class="col-sm-4">
                                <label for="ays_social_buttons">
                                    <?php echo __('Show the Social buttons','quiz-maker')?>
                                    <a class="ays_help" data-toggle="tooltip" title="<?php echo __('Display social buttons for sharing quiz page URL. LinkedIn, Facebook, X, VKontakte','quiz-maker')?>">
                                        <i class="ays_fa ays_fa_info_circle"></i>
                                    </a>
                                </label>
                            </div>
                            <div class="col-sm-1">
                                <input type="checkbox" class="ays-enable-timer1 ays_toggle_checkbox" id="ays_social_buttons" name="ays_social_buttons" value="on" <?php echo ( $enable_social_buttons ) ? 'checked' : '' ?>/>
                            </div>
                            <div class="col-sm-7 ays_toggle_target ays_divider_left <?php echo $enable_social_buttons ? '' : 'display_none'; ?>">
                                <div class="form-group row">
                                    <div class="col-sm-4">
                                        <label>
                                            <?php echo __('Heading for share buttons','quiz-maker')?>
                                            <a class="ays_help" data-toggle="tooltip" title="<?php echo __('Text that will be displayed over share buttons.','quiz-maker'); ?>">
                                                <i class="ays_fa ays_fa_info_circle"></i>
                                            </a>
                                        </label>
                                    </div>
                                    <div class="col-sm-8">
                                        <?php echo $quiz_message_vars_html; ?>
                                        <?php
                                            $content = $social_buttons_heading;
                                            $editor_id = 'ays_social_buttons_heading';
                                            $settings = array('editor_height' => $quiz_wp_editor_height, 'textarea_name' => 'ays_social_buttons_heading', 'editor_class' => 'ays-textarea', 'media_elements' => false);
                                            wp_editor($content, $editor_id, $settings);
                                        ?>
                                    </div>
                                </div>
                                <hr/>
                                <div class="form-group row">
                                    <div class="col-sm-4">
                                        <label for="ays_quiz_enable_linkedin_share_button">
                                            <i class="ays_fa ays_fa_linkedin_square"></i>
                                            <?php echo __('Enable LinkedIn button','quiz-maker')?>
                                            <a class="ays_help" data-toggle="tooltip" title="<?php echo __('Display LinkedIn social button so that the users can share the page on which your quiz is posted.','quiz-maker'); ?>">
                                                <i class="ays_fa ays_fa_info_circle"></i>
                                            </a>
                                        </label>
                                    </div>
                                    <div class="col-sm-8">
                                        <input type="checkbox" class="ays-enable-timer1" id="ays_quiz_enable_linkedin_share_button" name="ays_quiz_enable_linkedin_share_button" value="on" <?php echo ( $quiz_enable_linkedin_share_button ) ? 'checked' : ''; ?>/>
                                    </div>
                                </div>
                                <hr/>
                                <div class="form-group row">
                                    <div class="col-sm-4">
                                        <label for="ays_quiz_enable_facebook_share_button">
                                            <i class="ays_fa ays_fa_facebook_square"></i>
                                            <?php echo __('Enable Facebook button','quiz-maker')?>
                                            <a class="ays_help" data-toggle="tooltip" title="<?php echo __('Display Facebook social button so that the users can share the page on which your quiz is posted.','quiz-maker'); ?>">
                                                <i class="ays_fa ays_fa_info_circle"></i>
                                            </a>
                                        </label>
                                    </div>
                                    <div class="col-sm-8">
                                        <input type="checkbox" class="ays-enable-timer1" id="ays_quiz_enable_facebook_share_button" name="ays_quiz_enable_facebook_share_button" value="on" <?php echo ( $quiz_enable_facebook_share_button ) ? 'checked' : ''; ?>/>
                                    </div>
                                </div>
                                <hr/>
                                <div class="form-group row">
                                    <div class="col-sm-4">
                                        <label class="ays_quiz_enable_twitter_share_button_row" for="ays_quiz_enable_twitter_share_button">
                                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" width="14" height="16">
                                                <path d="M389.2 48h70.6L305.6 224.2 487 464H345L233.7 318.6 106.5 464H35.8L200.7 275.5 26.8 48H172.4L272.9 180.9 389.2 48zM364.4 421.8h39.1L151.1 88h-42L364.4 421.8z"/>
                                            </svg>
                                            <span><?php echo __('Enable X button','quiz-maker'); ?></span>
                                            <a class="ays_help" data-toggle="tooltip" title="<?php echo __('Display X social button so that the users can share the page on which your quiz is posted.','quiz-maker'); ?>">
                                                <i class="ays_fa ays_fa_info_circle"></i>
                                            </a>
                                        </label>
                                    </div>
                                    <div class="col-sm-8">
                                        <input type="checkbox" class="ays-enable-timer1" id="ays_quiz_enable_twitter_share_button" name="ays_quiz_enable_twitter_share_button" value="on" <?php echo ( $quiz_enable_twitter_share_button ) ? 'checked' : ''; ?>/>
                                    </div>
                                </div>
                                <hr/>
                                <div class="form-group row">
                                    <div class="col-sm-4">
                                        <label for="ays_quiz_enable_vkontakte_share_button">
                                            <i class="ays_fa ays_fa_vk"></i>
                                            <?php echo __('Enable VKontakte button','quiz-maker')?>
                                            <a class="ays_help" data-toggle="tooltip" title="<?php echo __('Display VKontakte social button so that the users can share the page on which your quiz is posted.','quiz-maker'); ?>">
                                                <i class="ays_fa ays_fa_info_circle"></i>
                                            </a>
                                        </label>
                                    </div>
                                    <div class="col-sm-8">
                                        <input type="checkbox" class="ays-enable-timer1" id="ays_quiz_enable_vkontakte_share_button" name="ays_quiz_enable_vkontakte_share_button" value="on" <?php echo ( $quiz_enable_vkontakte_share_button ) ? 'checked' : ''; ?>/>
                                    </div>
                                </div>
                            </div>
                        </div><!-- Show the Social buttons -->
                        <hr/>
                        <div class="form-group row ays_toggle_parent ays-quiz-result-message-vars-parent">
                            <div class="col-sm-4">
                                <label for="ays_enable_social_links">
                                    <?php echo __('Enable Social Media links','quiz-maker'); ?>
                                    <a class="ays_help" data-toggle="tooltip" title="<?php echo __('Display social media links at the end of the quiz to allow users to visit your pages in the Social media.','quiz-maker')?>">
                                        <i class="ays_fa ays_fa_info_circle"></i>
                                    </a>
                                </label>
                            </div>
                            <div class="col-sm-1">
                                <input type="checkbox" class="ays-enable-timer1 ays_toggle_checkbox" id="ays_enable_social_links"
                                       name="ays_enable_social_links"
                                       value="on" <?php echo $enable_social_links ? 'checked' : '' ?>/>
                            </div>
                            <div class="col-sm-7 ays_toggle_target ays_divider_left <?php echo $enable_social_links ? '' : 'display_none' ?>">
                                <div class="form-group row">
                                    <div class="col-sm-4">
                                        <label>
                                            <?php echo __('Heading for social media links','quiz-maker')?>
                                            <a class="ays_help" data-toggle="tooltip" title="<?php echo __('Text that will be displayed over social media links.','quiz-maker'); ?>">
                                                <i class="ays_fa ays_fa_info_circle"></i>
                                            </a>
                                        </label>
                                    </div>
                                    <div class="col-sm-8">
                                        <?php
                                            echo $quiz_message_vars_html;   
                                            $content = $social_links_heading;
                                            $editor_id = 'ays_social_links_heading';
                                            $settings = array('editor_height' => $quiz_wp_editor_height, 'textarea_name' => 'ays_social_links_heading', 'editor_class' => 'ays-textarea', 'media_elements' => false);
                                            wp_editor($content, $editor_id, $settings);
                                        ?>
                                    </div>
                                </div>
                                <hr/>
                                <div class="form-group row">
                                    <div class="col-sm-4">
                                        <label for="ays_linkedin_link">
                                            <i class="ays_fa ays_fa_linkedin_square"></i>
                                            <?php echo __('LinkedIn link','quiz-maker')?>
                                            <a class="ays_help" data-toggle="tooltip" title="<?php echo __('LinkedIn profile or page link for showing after quiz finish.','quiz-maker')?>">
                                                <i class="ays_fa ays_fa_info_circle"></i>
                                            </a>
                                        </label>
                                    </div>
                                    <div class="col-sm-8">
                                        <input type="text" class="ays-text-input" id="ays_linkedin_link" name="ays_social_links[ays_linkedin_link]"
                                            value="<?php echo $linkedin_link; ?>" />
                                    </div>
                                </div>
                                <hr/>
                                <div class="form-group row">
                                    <div class="col-sm-4">
                                        <label for="ays_facebook_link">
                                            <i class="ays_fa ays_fa_facebook_square"></i>
                                            <?php echo __('Facebook link','quiz-maker')?>
                                            <a class="ays_help" data-toggle="tooltip" title="<?php echo __('Facebook profile or page link for showing after quiz finish.','quiz-maker')?>">
                                                <i class="ays_fa ays_fa_info_circle"></i>
                                            </a>
                                        </label>
                                    </div>
                                    <div class="col-sm-8">
                                        <input type="text" class="ays-text-input" id="ays_facebook_link" name="ays_social_links[ays_facebook_link]"
                                            value="<?php echo $facebook_link; ?>" />
                                    </div>
                                </div>
                                <hr/>
                                <div class="form-group row">
                                    <div class="col-sm-4">
                                        <label for="ays_twitter_link">
                                            <!-- <i class="ays_fa ays_fa_twitter_square"></i> -->
                                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" width="14" height="16">
                                                <path d="M389.2 48h70.6L305.6 224.2 487 464H345L233.7 318.6 106.5 464H35.8L200.7 275.5 26.8 48H172.4L272.9 180.9 389.2 48zM364.4 421.8h39.1L151.1 88h-42L364.4 421.8z"/>
                                            </svg>
                                            <span><?php echo __('X link','quiz-maker'); ?></span>
                                            <a class="ays_help" data-toggle="tooltip" title="<?php echo __('X profile or page link for showing after quiz finish.','quiz-maker'); ?>">
                                                <i class="ays_fa ays_fa_info_circle"></i>
                                            </a>
                                        </label>
                                    </div>
                                    <div class="col-sm-8">
                                        <input type="text" class="ays-text-input" id="ays_twitter_link" name="ays_social_links[ays_twitter_link]"
                                            value="<?php echo $twitter_link; ?>" />
                                    </div>
                                </div>
                                <hr/>
                                <div class="form-group row">
                                    <div class="col-sm-4">
                                        <label for="ays_vkontakte_link">
                                            <i class="ays_fa ays_fa_vk"></i>
                                            <?php echo __('VKontakte link','quiz-maker')?>
                                            <a class="ays_help" data-toggle="tooltip" title="<?php echo __('VKontakte profile or page link for showing after quiz finish.','quiz-maker')?>">
                                                <i class="ays_fa ays_fa_info_circle"></i>
                                            </a>
                                        </label>
                                    </div>
                                    <div class="col-sm-8">
                                        <input type="text" class="ays-text-input" id="ays_vkontakte_link" name="ays_social_links[ays_vkontakte_link]"
                                            value="<?php echo $vkontakte_link; ?>" />
                                    </div>
                                </div>
                                <hr/>
                                <div class="form-group row">
                                    <div class="col-sm-4">
                                        <label for="instagram_link">
                                            <i class="ays_fa ays_fa_instagram_square"></i>
                                            <?php echo __('Instagram link','quiz-maker')?>
                                            <a class="ays_help" data-toggle="tooltip" title="<?php echo __('Instagram profile or page link for showing after quiz finish.','quiz-maker')?>">
                                                <i class="ays_fa ays_fa_info_circle"></i>
                                            </a>
                                        </label>
                                    </div>
                                    <div class="col-sm-8">
                                        <input type="text" class="ays-text-input" id="instagram_link" name="ays_social_links[ays_instagram_link]"
                                            value="<?php echo $instagram_link; ?>" />
                                    </div>
                                </div>
                                <hr/>
                                <div class="form-group row">
                                    <div class="col-sm-4">
                                        <label for="youtube_link">
                                            <i class="ays_fa ays_fa_youtube_play"></i>
                                            <?php echo __('YouTube link','quiz-maker')?>
                                            <a class="ays_help" data-toggle="tooltip" title="<?php echo __('YouTube profile or page link for showing after quiz finish.','quiz-maker');?>">
                                                <i class="ays_fa ays_fa_info_circle"></i>
                                            </a>
                                        </label>
                                    </div>
                                    <div class="col-sm-8">
                                        <input type="text" class="ays-text-input" id="youtube_link" name="ays_social_links[ays_youtube_link]"
                                            value="<?php echo $youtube_link; ?>" />
                                    </div>
                                </div>
                                <hr/>
                                <div class="form-group row">
                                    <div class="col-sm-4">
                                        <label for="behance_link">
                                            <i class="ays_fa ays_fa_behance"></i>
                                            <?php echo __('Behance link','quiz-maker')?>
                                            <a class="ays_help" data-toggle="tooltip" title="<?php echo __('Behance profile or page link for showing after quiz finish.','quiz-maker');?>">
                                                <i class="ays_fa ays_fa_info_circle"></i>
                                            </a>
                                        </label>
                                    </div>
                                    <div class="col-sm-8">
                                        <input type="text" class="ays-text-input" id="behance_link" name="ays_social_links[ays_behance_link]"
                                            value="<?php echo $behance_link; ?>" />
                                    </div>
                                </div>
                            </div>
                        </div><!-- Enable Social Media links -->
                        <hr/>
                        <div class="form-group row">
                            <div class="col-sm-4">
                                <label for="ays_disable_store_data">
                                    <?php echo __('Disable data storing in database','quiz-maker')?>
                                    <a class="ays_help" data-toggle="tooltip" title="<?php echo esc_attr( __("When this option is enabled, results will not be shown in the admin dashboard (not recommended), however, they will remain visible to users on the front end after submitting the quiz.",'quiz-maker') );?>">
                                        <i class="ays_fa ays_fa_info_circle"></i>
                                    </a>
                                </label>
                                <p class="ays_quiz_small_hint_text_for_not_recommended">
                                    <span><?php echo __( "Not recommended" , 'quiz-maker' ); ?></span>
                                </p>
                            </div>
                            <div class="col-sm-8">
                                <input type="checkbox" class="ays-enable-timer1" id="ays_disable_store_data" name="ays_disable_store_data" value="on" <?php echo $disable_store_data ? 'checked' : '' ?>/>
                            </div>
                        </div><!-- Disable data storing in database -->
                        <div class="ays-quiz-maker-interval-table-settings-container">
                        <?php
                            if(has_action('quiz_interval_table_settings')){
                                do_action( 'quiz_interval_table_settings', $options);
                            }
                        ?>
                        </div><!-- quiz_interval_table_settings -->
                        <hr/>
                        <div class="ays-quiz-heading-box ays-quiz-unset-float">
                            <div class="ays-quiz-wordpress-user-manual-box ays-quiz-wordpress-text-align">
                                <a href="https://www.youtube.com/watch?v=PQSOjFUG1Fg" target="_blank">
                                    <?php echo __("How intervals feature works - video", 'quiz-maker'); ?>
                                </a>
                            </div>
                        </div>
                        <div class="form-group row" style="margin-bottom: 0;">
                            <div class="col-sm-12 only_pro ays-quiz-margin-top-20" style="padding:15px;">
                                <div class="pro_features pro_features_popup">
                                    <div class="pro-features-popup-conteiner">
                                        <div class="pro-features-popup-title">
                                            <?php echo __("Intervals feature", 'quiz-maker'); ?>
                                        </div>
                                        <div class="pro-features-popup-content" data-link="https://youtu.be/W_H69qg3LFA">
                                            <p>
                                                <?php echo __("With the help of the Intervals feature you can display different specified results based on the Interval the user appeared in. Then, attach an image to each Interval.", 'quiz-maker'); ?>
                                            </p>
                                            <p>
                                                <?php echo sprintf( __("You can choose either %s By percentage, By Points, or By Keywords %s as a method of calculation for the Intervals.", 'quiz-maker'),
                                                    '<strong>',
                                                    '</strong>'
                                                ); ; ?>
                                            </p>
                                            <p>
                                                <?php echo __("One of the best examples of the use case of the Intervals feature is a personality quiz, where you can show different personality types to the users.", 'quiz-maker'); ?>
                                            </p>
                                            <div>
                                                <a href="https://quiz-plugin.com/personality-quiz-for-wp/" target="_blank"><?php echo __("See Demo", 'quiz-maker'); ?></a>
                                            </div>
                                        </div>
                                        <div class="pro-features-popup-button" data-link="https://ays-pro.com/wordpress/quiz-maker?utm_source=dashboard&utm_medium=quiz-free&utm_campaign=pro-popup-intervals-table-<?php echo esc_attr( AYS_QUIZ_UTM_VERSION ); ?>">
                                            <?php echo __("Pricing", 'quiz-maker'); ?>
                                        </div>
                                    </div>
                                </div>
                                <div class='form-group row ays-field-dashboard'>
                                    <div class="col-sm-4">
                                        <label for="ays-answers-table"><?php echo __('Intervals', 'quiz-maker'); ?>
                                            <a href="javascript:void(0)" class="ays-add-interval" tabindex="-1">
                                                <i class="ays_fa ays_fa_plus_square" aria-hidden="true"></i>
                                            </a>
                                            <a class="ays_help" style="font-size:15px;" data-toggle="tooltip" title="<?php echo __('Set different messages based on the user\'s score. The message will be displayed on the result page of the quiz.','quiz-maker'); ?>">
                                                <i class="ays_fa ays_fa_info_circle"></i>
                                            </a>
                                        </label>
                                        <div><?php echo __("Conditional result messages", 'quiz-maker'); ?></div>
                                    </div>
                                    <div class="col-sm-8">
                                        <label class="ays_quiz_loader">
                                            <input type="radio" class="ays-enable-timer1 ays_table_by ays_intervals_display_by" tabindex="-1" checked>
                                            <span><?php echo __( "By percentage", 'quiz-maker' ); ?></span>
                                        </label>
                                        <label class="ays_quiz_loader">
                                            <input type="radio" class="ays-enable-timer1 ays_table_by ays_intervals_display_by" tabindex="-1">
                                            <span><?php echo __( "By points", 'quiz-maker' ); ?></span>
                                        </label>
                                        <label class="ays_quiz_loader">
                                            <input type="radio" class="ays-enable-timer1 ays_table_by ays_intervals_display_by" tabindex="-1">
                                            <span><?php echo __( "By keywords", 'quiz-maker' ); ?></span>
                                        </label>
                                        <a class="ays_help" style="font-size:15px;" data-toggle="tooltip" data-html="true"
                                            title="<?php
                                                echo __('Choose your preferred method of calculation.','quiz-maker') .
                                                "<ul style='list-style-type: circle;padding-left: 20px;'>".
                                                    "<li>". __('By percentage - If this option is enabled, you need to assign values to Min and Max fields by percentage and write a correspondent message and attach an image for each interval separately. You need to cover the 0-100 range with as many intervals as you want.','quiz-maker') ."</li>".
                                                    "<li>". __('By points - If this option is enabled, you need to assign values to Min and Max fields by points and write a correspondent message and attach an image for each interval separately. There is no limitation to that.','quiz-maker') ."</li>".
                                                    "<li>". __('By keywords - If this option is enabled, you need to select the keywords, which you have already assigned to your answers and write a correspondent message and attach an image for each interval separately. It will be calculated based on the majority of the selected answers of the user.','quiz-maker') ."</li>".
                                                "</ul>";
                                            ?>">
                                            <i class="ays_fa ays_fa_info_circle"></i>
                                        </a>
                                    </div>
                                </div>

                                <div class='ays-field-dashboard ays-table-wrap'>
                                    <table class="ays-intervals-table">
                                        <thead>
                                        <tr class="ui-state-default">
                                            <th><?php echo __('Ordering', 'quiz-maker'); ?></th>
                                            <th class="ays_interval_min_row"><?php echo __('Min', 'quiz-maker'); ?></th>
                                            <th class="ays_interval_max_row"><?php echo __('Max', 'quiz-maker'); ?></th>
                                            <th><?php echo __('Text', 'quiz-maker'); ?></th>
                                            <th><?php echo __('Image', 'quiz-maker'); ?></th>
                                            <th><?php echo __('Delete', 'quiz-maker'); ?></th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <?php
                                            foreach ($quiz_intervals_default as $key => $quiz_interval) {
                                                $className = "";
                                                if (($key + 1) % 2 == 0) {
                                                    $className = "even";
                                                }
                                                $quiz_interval_text = 'Add';

                                                ?>
                                                <tr class="ays-interval-row ui-state-default <?php echo $className; ?>">
                                                    <td class="ays-sort">
                                                        <i class="ays_fa ays_fa_arrows" aria-hidden="true"></i>
                                                    </td>
                                                    <td class="ays_interval_min_row">
                                                        <input type="number" tabindex="-1" value="<?php echo $quiz_interval['interval_min'] ?>" class="interval_min">
                                                    </td>
                                                    <td class="ays_interval_max_row">
                                                        <input type="number" tabindex="-1" value="<?php echo $quiz_interval['interval_max'] ?>" class="interval_max">
                                                    </td>
                                                    <td>
                                                        <textarea type="text" class="interval__text" tabindex="-1"></textarea>
                                                    </td>
                                                    <td class="ays-interval-image-td">
                                                        <label class='ays-label' for='ays-answer'>
                                                            <a href="javascript:void(0)" class="add-answer-image add-interval-image" style="display:block;" tabindex="-1">
                                                                <?php echo $quiz_interval_text; ?>
                                                            </a>
                                                        </label>
                                                    </td>
                                                    <td>
                                                        <a href="javascript:void(0)" class="ays-delete-interval" tabindex="-1" data-id="<?php echo $key; ?>">
                                                            <i class="ays_fa ays_fa_minus_square" aria-hidden="true"></i>
                                                        </a>
                                                    </td>
                                                </tr>
                                                <?php
                                            }
                                        ?>
                                        </tbody>
                                    </table>
                                </div> <!-- Intervals -->

                                <a href="https://ays-pro.com/wordpress/quiz-maker?utm_source=dashboard&utm_medium=quiz-free&utm_campaign=pro-popup-intervals-table-<?php echo esc_attr( AYS_QUIZ_UTM_VERSION ); ?>" target="_blank" class="ays-quiz-new-upgrade-button-link">
                                    <div class="ays-quiz-new-upgrade-button-box">
                                        <div>
                                            <img loading="lazy" src="<?php echo AYS_QUIZ_ADMIN_URL.'/images/icons/locked_24x24.svg'?>">
                                            <img loading="lazy" src="<?php echo AYS_QUIZ_ADMIN_URL.'/images/icons/unlocked_24x24.svg'?>" class="ays-quiz-new-upgrade-button-hover">
                                        </div>
                                        <div class="ays-quiz-new-upgrade-button"><?php echo __("Upgrade", "quiz-maker"); ?></div>
                                    </div>
                                </a>
                                <div class="ays-quiz-new-watch-video-button-box">
                                    <div>
                                        <img loading="lazy" src="<?php echo AYS_QUIZ_ADMIN_URL.'/images/icons/video_24x24.svg'?>">
                                        <img loading="lazy" src="<?php echo AYS_QUIZ_ADMIN_URL.'/images/icons/video_24x24_hover.svg'?>" class="ays-quiz-new-watch-video-button-hover">
                                    </div>
                                    <div class="ays-quiz-new-watch-video-button"><?php echo __("Watch Video", "quiz-maker"); ?></div>
                                </div>

                                <div class="ays-quiz-center-big-main-button-box ays-quiz-new-big-button-flex">
                                    <div class="ays-quiz-center-big-watch-video-button-box ays-quiz-big-upgrade-margin-right-10">
                                        <div class="ays-quiz-center-new-watch-video-demo-button">
                                            <img loading="lazy" src="<?php echo AYS_QUIZ_ADMIN_URL.'/images/icons/video_24x24.svg'?>" class="ays-quiz-new-button-img-hide">
                                            <img loading="lazy" src="<?php echo AYS_QUIZ_ADMIN_URL.'/images/icons/video_24x24_hover.svg'?>" class="ays-quiz-new-watch-video-button-hover">
                                            <?php echo __("Watch Video", "quiz-maker"); ?>
                                        </div>
                                    </div>
                                    <div class="ays-quiz-center-big-upgrade-button-box">
                                        <a href="https://ays-pro.com/wordpress/quiz-maker?utm_source=dashboard&utm_medium=quiz-free&utm_campaign=pro-popup-intervals-table-<?php echo esc_attr( AYS_QUIZ_UTM_VERSION ); ?>" target="_blank" class="ays-quiz-new-upgrade-button-link">
                                            <div class="ays-quiz-center-new-big-upgrade-button">
                                                <img loading="lazy" src="<?php echo AYS_QUIZ_ADMIN_URL.'/images/icons/locked_24x24.svg'?>" class="ays-quiz-new-button-img-hide">
                                                <img loading="lazy" src="<?php echo AYS_QUIZ_ADMIN_URL.'/images/icons/unlocked_24x24.svg'?>" class="ays-quiz-new-upgrade-button-hover">  
                                                <?php echo __("Upgrade", "quiz-maker"); ?>
                                            </div>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="ays-quiz-heading-box ays-quiz-unset-float ays-quiz-unset-margin">
                            <div class="ays-quiz-wordpress-user-manual-box ays-quiz-wordpress-text-align">
                                <a href="https://www.youtube.com/watch?v=lUbLHe9mEZ0" target="_blank">
                                    <?php echo __("How to create personality quiz - video", 'quiz-maker'); ?>
                                </a>
                            </div>
                        </div>
                        <?php
                        if( in_array('woocommerce/woocommerce.php', apply_filters('active_plugins', get_option('active_plugins'))) ) {
                        ?>
                        <div class="ays-quiz-heading-box ays-quiz-unset-float ays-quiz-unset-margin">
                            <div class="ays-quiz-wordpress-user-manual-box ays-quiz-wordpress-text-align">
                                <a href="https://www.youtube.com/watch?v=BeYNME9TZsQ" target="_blank">
                                    <?php echo __("How to create WooCommerce quiz - video", 'quiz-maker'); ?>
                                </a>
                            </div>
                        </div>
                        <?php
                        }
                        ?>
                        <!-- Intervals -->
                        <hr/>
                        <!-- Top Keywords -->
                        <div class="form-group row">
                            <div class="col-sm-12 only_pro" style="padding:15px;">
                                <div class="pro_features pro_features_popup">
                                    <div class="pro-features-popup-conteiner">
                                        <div class="pro-features-popup-title">
                                            <?php echo __("Top Keywords", 'quiz-maker'); ?>
                                        </div>
                                        <div class="pro-features-popup-content" data-link="https://youtu.be/o-GB91dfsLo">
                                            <p>
                                                <?php echo __("Are you interested in showing the question results based on chosen keywords? If yes, then, you can use the Top Keywords functionality.", 'quiz-maker'); ?>
                                            </p>
                                            <p>
                                                <?php echo sprintf( __("One of the %s best use cases %s of the feature is the %s Personality Trait quiz. %s", 'quiz-maker'),
                                                    '<strong>',
                                                    '</strong>',
                                                    '<strong>',
                                                    '</strong>'
                                                ); ?>
                                            </p>
                                            <p>
                                                <?php echo sprintf( __("You need to configure the %s Keyword texts %s accordingly. Note: The %s Apply points to keywords %s option appears and works only in case you chose the %s By Keywords %s method of calculation.", 'quiz-maker'),
                                                    '<strong>',
                                                    '</strong>',
                                                    '<strong>',
                                                    '</strong>',
                                                    '<strong>',
                                                    '</strong>'
                                                ); ?>
                                            </p>
                                            <p>
                                                <?php echo __("By using this feature, you can display what percentage of which personality traits the particular user has.", 'quiz-maker'); ?>
                                            </p>
                                            <div>
                                                <a href="https://quiz-plugin.com/docs/" target="_blank"><?php echo __("See Documentation", 'quiz-maker'); ?></a>
                                            </div>
                                        </div>
                                        <div class="pro-features-popup-button" data-link="https://ays-pro.com/wordpress/quiz-maker?utm_source=dashboard&utm_medium=quiz-free&utm_campaign=pro-popup-top-keywords-<?php echo esc_attr( AYS_QUIZ_UTM_VERSION ); ?>">
                                            <?php echo __("Pricing", 'quiz-maker'); ?>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-sm-4">
                                            <label for="ays_enable_top_keywords">
                                                <?php echo __('Assign message to each keyword','quiz-maker'); ?>
                                                <?php echo __('(Personality Quiz)','quiz-maker'); ?>
                                                <a class="ays_help" data-toggle="tooltip" title="<?php echo __('Show the question results based on keywords on the resultes page with specified texts for each keyword.','quiz-maker'); ?>">
                                                    <i class="ays_fa ays_fa_info_circle"></i>
                                                </a>
                                            </label>
                                        </div>
                                        <div class="col-sm-8">
                                            <input type="checkbox" class="ays-enable-timer1" value="on" tabindex="-1"/>
                                        </div>
                                    </div>
                                </div> <!-- Show all questions result in finish page -->
                                <a href="https://ays-pro.com/wordpress/quiz-maker?utm_source=dashboard&utm_medium=quiz-free&utm_campaign=pro-popup-top-keywords-<?php echo esc_attr( AYS_QUIZ_UTM_VERSION ); ?>" target="_blank" class="ays-quiz-new-upgrade-button-link">
                                    <div class="ays-quiz-new-upgrade-button-box">
                                        <div>
                                            <img loading="lazy" src="<?php echo AYS_QUIZ_ADMIN_URL.'/images/icons/locked_24x24.svg'?>">
                                            <img loading="lazy" src="<?php echo AYS_QUIZ_ADMIN_URL.'/images/icons/unlocked_24x24.svg'?>" class="ays-quiz-new-upgrade-button-hover">
                                        </div>
                                        <div class="ays-quiz-new-upgrade-button"><?php echo __("Upgrade", "quiz-maker"); ?></div>
                                    </div>
                                </a>
                                <div class="ays-quiz-new-watch-video-button-box">
                                    <div>
                                        <img loading="lazy" src="<?php echo AYS_QUIZ_ADMIN_URL.'/images/icons/video_24x24.svg'?>">
                                        <img loading="lazy" src="<?php echo AYS_QUIZ_ADMIN_URL.'/images/icons/video_24x24_hover.svg'?>" class="ays-quiz-new-watch-video-button-hover">
                                    </div>
                                    <div class="ays-quiz-new-watch-video-button"><?php echo __("Watch Video", "quiz-maker"); ?></div>
                                </div>
                            </div>
                        </div><!-- Top Keywords -->
                        <hr>
                        <div class="ays-quiz-heading-box ays-quiz-unset-float" style="margin-bottom: 20px;">
                            <div class="ays-quiz-wordpress-user-manual-box ays-quiz-wordpress-text-align">
                                <a href="https://www.youtube.com/watch?v=LJ5X2-AMdm0" target="_blank">
                                    <?php echo __("How to Add Coupons - video", 'quiz-maker'); ?>
                                </a>
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-sm-12 only_pro" style="padding:15px;">
                                <div class="pro_features pro_features_popup">
                                    <div class="pro-features-popup-conteiner">
                                        <div class="pro-features-popup-title">
                                            <?php echo __("Quiz Coupon", 'quiz-maker'); ?>
                                        </div>
                                        <div class="pro-features-popup-content" data-link="https://youtu.be/EfdYE1o2ALk">
                                            <p>
                                                <?php echo sprintf( __("Quiz Coupons can become a great motivation for your new audience. Give them a reason to try your product. Provide Quiz Coupons on the Results Page and %s increase sells in your website. %s Every time the users will receive unique codes that you will import into the plugin.", 'quiz-maker'),
                                                    '<strong>',
                                                    '</strong>'
                                                ); ?>
                                            </p>
                                            <div>
                                                <a href="https://quiz-plugin.com/coupon-quiz/" target="_blank"><?php echo __("See Demo", 'quiz-maker'); ?></a>
                                            </div>
                                        </div>
                                        <div class="pro-features-popup-button" data-link="https://ays-pro.com/wordpress/quiz-maker?utm_source=dashboard&utm_medium=quiz-free&utm_campaign=pro-popup-quiz-coupon-<?php echo esc_attr( AYS_QUIZ_UTM_VERSION ); ?>">
                                            <?php echo __("Pricing", 'quiz-maker'); ?>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <div class="col-sm-4">
                                        <label for="ays_quiz_enable_coupon">
                                            <?php echo __('Enable quiz coupons','quiz-maker'); ?>
                                            <a class="ays_help" data-html="true" data-toggle="tooltip" title="<?php echo __("Enable coupon receiving after finishing the quiz. For showing the coupons, you have to use the %%quiz_coupon%% message variable from General Settings>Message variables.",'quiz-maker'); ?>">
                                                <i class="ays_fa ays_fa_info_circle"></i>
                                            </a>
                                        </label>
                                    </div>
                                    <div class="col-sm-8">
                                        <input type="checkbox" class="ays-enable-timer1" value="on" tabindex="-1" />
                                    </div>
                                </div> <!-- Show all questions result in finish page -->
                                <a href="https://ays-pro.com/wordpress/quiz-maker?utm_source=dashboard&utm_medium=quiz-free&utm_campaign=pro-popup-quiz-coupon-<?php echo esc_attr( AYS_QUIZ_UTM_VERSION ); ?>" target="_blank" class="ays-quiz-new-upgrade-button-link">
                                    <div class="ays-quiz-new-upgrade-button-box">
                                        <div>
                                            <img loading="lazy" src="<?php echo AYS_QUIZ_ADMIN_URL.'/images/icons/locked_24x24.svg'?>">
                                            <img loading="lazy" src="<?php echo AYS_QUIZ_ADMIN_URL.'/images/icons/unlocked_24x24.svg'?>" class="ays-quiz-new-upgrade-button-hover">
                                        </div>
                                        <div class="ays-quiz-new-upgrade-button"><?php echo __("Upgrade", "quiz-maker"); ?></div>
                                    </div>
                                </a>
                                <div class="ays-quiz-new-watch-video-button-box">
                                    <div>
                                        <img loading="lazy" src="<?php echo AYS_QUIZ_ADMIN_URL.'/images/icons/video_24x24.svg'?>">
                                        <img loading="lazy" src="<?php echo AYS_QUIZ_ADMIN_URL.'/images/icons/video_24x24_hover.svg'?>" class="ays-quiz-new-watch-video-button-hover">
                                    </div>
                                    <div class="ays-quiz-new-watch-video-button"><?php echo __("Watch Video", "quiz-maker"); ?></div>
                                </div>
                            </div>
                        </div><!-- Quiz Coupon -->
                    </div>
                </div>
            </div>
            
            <div id="tab5" class="ays-quiz-tab-content <?php echo ($ays_quiz_tab == 'tab5') ? 'ays-quiz-tab-content-active' : ''; ?>">
                <div class="ays-quiz-top-actions-container-wrapper form-group row">
                    <div class="col-sm-12">
                        <p class="m-0 text-right">
                            <a class="ays-quiz-collapse-all" href="javascript:void(0);"><?php echo __( "Collapse All", "quiz-maker" ); ?></a>
                            <span>|</span>
                            <a class="ays-quiz-expand-all" href="javascript:void(0);"><?php echo __( "Expand All", "quiz-maker" ); ?></a>
                        </p>
                    </div>
                </div>
                <div class="ays-quiz-accordion-options-main-container" data-collapsed="false">
                    <div class="ays-quiz-accordion-container">
                        <?php echo $quiz_accordion_svg_html; ?>
                        <p class="ays-subtitle"><?php echo __('Limitation of Users','quiz-maker')?></p>
                    </div>
                    <hr class="ays-quiz-bolder-hr"/>
                    <div class="ays-quiz-accordion-options-box">
                        <div class="form-group row">
                            <div class="col-sm-3">
                                <label for="ays_limit_users">
                                    <?php echo __('Limit Users','quiz-maker')?>
                                    <a class="ays_help" data-toggle="tooltip" title="<?php echo __('This option allows to block the users who have already passed the quiz.','quiz-maker')?>">
                                        <i class="ays_fa ays_fa_info_circle"></i>
                                    </a>
                                </label>
                            </div>
                            <div class="col-sm-1">
                                <input type="checkbox" class="ays-enable-timer1" id="ays_limit_users" name="ays_limit_users"
                                       value="on" <?php echo (isset($options['limit_users']) && $options['limit_users'] == 'on') ? 'checked' : ''; ?>/>
                            </div>
                            <div class="col-sm-8" id="limit-user-options" style="border-left: 1px solid #ccc">
                                <div class="ays-limitation-options">
                                    <!-- Limitation by -->
                                    <div class="form-group row">
                                        <div class="col-sm-3">
                                            <label for="ays_limitation_message">
                                                <?php echo __('Limit users by','quiz-maker')?>
                                                <a class="ays_help" data-toggle="tooltip" data-html="true" title="<?php echo __('Limit users pass the quiz by IP or by User ID.','quiz-maker')?><br><?php echo __('If you choose \'User ID\', the \'Limit users\' option will not work for the not logged in users. It works only with \'Only for logged in users\' option.','quiz-maker')?>">
                                                    <i class="ays_fa ays_fa_info_circle"></i>
                                                </a>
                                            </label>
                                        </div>
                                        <div class="col-sm-9">
                                            <label class="ays_quiz_loader">
                                                <input type="radio" id="ays_limit_users_by_ip" name="ays_limit_users_by" value="ip" <?php echo ($limit_users_by == 'ip') ? 'checked' : ''; ?>/>
                                                <span for="ays_limit_users_by_ip"><?php echo __('IP','quiz-maker')?></span>
                                            </label>
                                            <label class="ays_quiz_loader">
                                                <input type="radio" id="ays_limit_users_by_user_id" name="ays_limit_users_by" value="user_id" <?php echo ($limit_users_by == 'user_id') ? 'checked' : ''; ?>/>
                                                <span for="ays_limit_users_by_user_id"><?php echo __('User ID','quiz-maker')?></span>
                                            </label>
                                            <label class="ays_quiz_loader">
                                                <input type="radio" id="ays_limit_users_by_cookie" name="ays_limit_users_by" value="cookie" <?php echo ($limit_users_by == 'cookie') ? 'checked' : ''; ?>/>
                                                <span for="ays_limit_users_by_cookie"><?php echo __('Cookie','quiz-maker')?></span>
                                            </label>
                                            <label class="ays_quiz_loader">
                                                <input type="radio" id="ays_limit_users_by_ip_cookie" name="ays_limit_users_by" value="ip_cookie" <?php echo ($limit_users_by == 'ip_cookie') ? 'checked' : ''; ?>/>
                                                <span for="ays_limit_users_by_ip_cookie"><?php echo __('IP and Cookie','quiz-maker')?></span>
                                            </label>
                                        </div>
                                    </div>
                                </div>
                                <hr/>
                                <div class="form-group row" style="position:relative;">
                                    <div class="col-sm-12 only_pro" style="padding:15px;">
                                        <div class="pro_features pro_features_popup">
                                            <div class="pro-features-popup-conteiner">
                                                <div class="pro-features-popup-title">
                                                    <?php echo __("Maximum number of attempts", 'quiz-maker'); ?>
                                                </div>
                                                <div class="pro-features-popup-content" data-link="https://youtu.be/3MboTs_CO3k">
                                                    <p>
                                                        <?php echo sprintf( __("With the help of this option you can no longer struggle with getting multiple results from the same person, which will draw a false conclusion. Restrict the attempt of the same user by just activating the %s Maximum number of attempts option. %s", 'quiz-maker'),
                                                            "<strong>",
                                                            "</strong>"
                                                        ); ?>
                                                    </p>
                                                    <p>
                                                        <?php echo __("This can be a useful tool for running a fair and safe examination.", 'quiz-maker'); ?>
                                                    </p>
                                                    <p>
                                                        <?php echo __("You will be able to detect uses by IP addresses, WP User IDs, Browser Cookies and by both IP addresses and Cookies. Just write the attempts count and the users will not be able to pass the quiz for more than the count that you have set.", 'quiz-maker'); ?>
                                                    </p>
                                                    <p>
                                                        <?php echo __("Write warning messages, redirect them to your desired URL and all this just in one feature.", 'quiz-maker'); ?>
                                                    </p>
                                                    <div>
                                                        <a href="https://quiz-plugin.com/docs/" target="_blank"><?php echo __("Learn More", 'quiz-maker'); ?></a>
                                                    </div>
                                                </div>
                                                <div class="pro-features-popup-button" data-link="https://ays-pro.com/wordpress/quiz-maker?utm_source=dashboard&utm_medium=quiz-free&utm_campaign=pro-popup-attempts-count-<?php echo esc_attr( AYS_QUIZ_UTM_VERSION ); ?>">
                                                    <?php echo __("Pricing", 'quiz-maker'); ?>
                                                </div>
                                            </div>
                                        </div>
                                        <!-- Limitation count -->
                                        <div class="form-group row">
                                            <div class="col-sm-3">
                                                <label for="ays_quiz_max_pass_count">
                                                    <?php echo __('Attempts count:','quiz-maker')?>
                                                    <a class="ays_help" data-toggle="tooltip" title="<?php echo __('Specify the count of the attempts per user for passing the quiz.','quiz-maker')?>">
                                                        <i class="ays_fa ays_fa_info_circle"></i>
                                                    </a>
                                                </label>
                                            </div>
                                            <div class="col-sm-9">
                                                <input type="number" class="ays-text-input" id="ays_quiz_max_pass_count" tabindex="-1"/>
                                            </div>
                                        </div>
                                        <hr/>
                                        <!-- Limitation pass score -->
                                        <div class="form-group row">
                                            <div class="col-sm-3">
                                                <label for="ays_quiz_pass_score">
                                                    <?php echo __('Pass score for attempt restriction','quiz-maker')?> (%)
                                                    <a class="ays_help" data-toggle="tooltip" title="<?php echo __('Select the passing score(in percentage), and the attempt of the user will be detected only under that given condition. For example: If we give 40% value to it and assign 5 to the Attempts count option, the user can pass the quiz with getting more than 40% score in 5 times, but will have a chance to pass the quiz with getting under the 40% score as to how much as he/she wants.','quiz-maker')?>">
                                                        <i class="ays_fa ays_fa_info_circle"></i>
                                                    </a>
                                                </label>
                                            </div>
                                            <div class="col-sm-9">
                                                <input type="number" class="ays-text-input" id="ays_quiz_pass_score" tabindex="-1"/>
                                            </div>
                                        </div>
                                        <hr/>
                                        <!-- Limit count by user role -->
                                        <div class="form-group row">
                                            <div class="col-sm-3">
                                                <label for="ays_limit_count_by_user_role">
                                                    <?php echo __( 'Attempts count for each user role', 'quiz-maker' ); ?>
                                                    <a class="ays_help" data-toggle="tooltip" title="<?php echo __('Limit the count of the attempts for each user role for passing the quiz. To have this option work you need to enable Only for selected user role option.', 'quiz-maker')?>">
                                                        <i class="ays_fa ays_fa_info_circle"></i>
                                                    </a>
                                                </label>
                                            </div>
                                            <div class="col-sm-9">
                                                <input type="number" class="ays-text-input" value="" tabindex="-1"/>
                                            </div>
                                        </div>

                                        <a href="https://ays-pro.com/wordpress/quiz-maker?utm_source=dashboard&utm_medium=quiz-free&utm_campaign=pro-popup-attempts-count-<?php echo esc_attr( AYS_QUIZ_UTM_VERSION ); ?>" target="_blank" class="ays-quiz-new-upgrade-button-link">
                                            <div class="ays-quiz-new-upgrade-button-box">
                                                <div>
                                                    <img loading="lazy" src="<?php echo AYS_QUIZ_ADMIN_URL.'/images/icons/locked_24x24.svg'?>">
                                                    <img loading="lazy" src="<?php echo AYS_QUIZ_ADMIN_URL.'/images/icons/unlocked_24x24.svg'?>" class="ays-quiz-new-upgrade-button-hover">
                                                </div>
                                                <div class="ays-quiz-new-upgrade-button"><?php echo __("Upgrade", "quiz-maker"); ?></div>
                                            </div>
                                        </a>
                                        <div class="ays-quiz-new-watch-video-button-box">
                                            <div>
                                                <img loading="lazy" src="<?php echo AYS_QUIZ_ADMIN_URL.'/images/icons/video_24x24.svg'?>">
                                                <img loading="lazy" src="<?php echo AYS_QUIZ_ADMIN_URL.'/images/icons/video_24x24_hover.svg'?>" class="ays-quiz-new-watch-video-button-hover">
                                            </div>
                                            <div class="ays-quiz-new-watch-video-button"><?php echo __("Watch Video", "quiz-maker"); ?></div>
                                        </div>
                                    </div>
                                </div>
                                <hr/>
                                <div class="ays-limitation-options">
                                    <!-- Limitation message -->
                                    <div class="form-group row ays-quiz-result-message-vars-parent">
                                        <div class="col-sm-3">
                                            <label for="ays_limitation_message">
                                                <?php echo __('Message','quiz-maker')?>
                                                <a class="ays_help" data-toggle="tooltip" title="<?php echo __('Message for those who have passed the quiz','quiz-maker')?>">
                                                    <i class="ays_fa ays_fa_info_circle"></i>
                                                </a>
                                            </label>
                                        </div>
                                        <div class="col-sm-9">
                                            <?php
                                            echo $quiz_message_vars_limitation_message_html;
                                            $content = wpautop(stripslashes((isset($options['limitation_message'])) ? $options['limitation_message'] : ''));
                                            $editor_id = 'ays_limitation_message';
                                            $settings = array('editor_height' => $quiz_wp_editor_height, 'textarea_name' => 'ays_limitation_message', 'editor_class' => 'ays-textarea', 'media_elements' => false);
                                            wp_editor($content, $editor_id, $settings);
                                            ?>
                                        </div>
                                    </div>
                                    <hr/>
                                    <!-- Limitation redirect url -->
                                    <div class="form-group row">
                                        <div class="col-sm-3">
                                            <label for="ays_redirect_url">
                                                <?php echo __('Redirect URL','quiz-maker')?>
                                                <a class="ays_help" data-toggle="tooltip" title="<?php echo __('For leave current page and go to the link provided','quiz-maker')?>">
                                                    <i class="ays_fa ays_fa_info_circle"></i>
                                                </a>
                                            </label>
                                        </div>
                                        <div class="col-sm-9">
                                            <input type="text" name="ays_redirect_url" id="ays_redirect_url"
                                                   class="ays-text-input"
                                                   value="<?php echo (isset($options['redirect_url'])) ? $options['redirect_url'] : ''; ?>"/>
                                        </div>
                                    </div>
                                    <hr/>
                                    <!-- Limitation redirect delay -->
                                    <div class="form-group row">
                                        <div class="col-sm-3">
                                            <label for="ays_redirection_delay">
                                                <?php echo __('Redirect delay','quiz-maker')?>(s)
                                                <a class="ays_help" data-toggle="tooltip" title="<?php echo __('Leave current page and go to the link provided after X second','quiz-maker')?>">
                                                    <i class="ays_fa ays_fa_info_circle"></i>
                                                </a>
                                            </label>
                                        </div>
                                        <div class="col-sm-9">
                                            <input type="number" name="ays_redirection_delay" id="ays_redirection_delay"
                                                   class="ays-text-input"
                                                   value="<?php echo (isset($options['redirection_delay'])) ? $options['redirection_delay'] : 0; ?>"/>
                                        </div>
                                    </div>
                                    <hr/>
                                    <div class="form-group row" style="position:relative;">
                                        <div class="col-sm-12 only_pro" style="padding:15px;">
                                            <div class="pro_features pro_features_popup">
                                                <div class="pro-features-popup-conteiner">
                                                    <div class="pro-features-popup-title">
                                                        <?php echo __("Turn on extra security check", 'quiz-maker'); ?>
                                                    </div>
                                                    <div class="pro-features-popup-content" data-link="https://youtu.be/Ie0x_jP-ng8">
                                                        <p>
                                                            <?php echo sprintf( __("The %s Turn on extra security check %s option will help you to create a quiz that %s is not possible to cheat. %s", 'quiz-maker'),
                                                                "<strong>",
                                                                "</strong>",
                                                                "<strong>",
                                                                "</strong>"
                                                            ); ?>
                                                        </p>
                                                        <p>
                                                            <?php echo __("The option works when the admin has enabled a limitation for the quiz and set the attempts count for it.", 'quiz-maker'); ?>
                                                        </p>
                                                        <p>
                                                            <?php echo __("You will be able to detect uses by IP addresses, WP User IDs, Browser Cookies and by both IP addresses and Cookies. Just write the attempts count and the users will not be able to pass the quiz for more than the count that you have set.", 'quiz-maker'); ?>
                                                        </p>
                                                        <p>
                                                            <?php echo __("If this option is enabled, and the user tries to open the quiz in more than one tab simultaneously, the quiz will not be opened.", 'quiz-maker'); ?>
                                                        </p>
                                                        <div>
                                                            <a href="https://quiz-plugin.com/docs/" target="_blank"><?php echo __("See Documentation", 'quiz-maker'); ?></a>
                                                        </div>
                                                    </div>
                                                    <div class="pro-features-popup-button" data-link="https://ays-pro.com/wordpress/quiz-maker?utm_source=dashboard&utm_medium=quiz-free&utm_campaign=pro-popup-extra-security-<?php echo esc_attr( AYS_QUIZ_UTM_VERSION ); ?>">
                                                        <?php echo __("Pricing", 'quiz-maker'); ?>
                                                    </div>
                                                </div>
                                            </div>
                                            <!-- Turn on extra security check -->
                                            <div class="form-group row">
                                                <div class="col-sm-3">
                                                    <label for="ays_turn_on_extra_security_check">
                                                        <?php echo __( 'Turn on extra security check', 'quiz-maker' ); ?>
                                                        <a class="ays_help" data-toggle="tooltip" title="<?php echo __('When the attempt limit of the quiz has reached, and a user tries to open your quiz in more than one tab concurrently, the results of their additional attempt will not be stored.', 'quiz-maker')?>">
                                                            <i class="ays_fa ays_fa_info_circle"></i>
                                                        </a>
                                                    </label>
                                                </div>
                                                <div class="col-sm-9">
                                                    <input type="checkbox" value="on" tabindex="-1"/>
                                                </div>
                                            </div>
                                            <hr/>
                                            <!-- Hide attempts limitation notice -->
                                            <div class="form-group row">
                                                <div class="col-sm-3">
                                                    <label for="ays_hide_limit_attempts_notice">
                                                        <?php echo __( 'Hide attempts limitation notice', 'quiz-maker' ); ?>
                                                        <a class="ays_help" data-toggle="tooltip" title="<?php echo __('Hide the remaining attempts count warning when the limitation is activated.', 'quiz-maker')?>">
                                                            <i class="ays_fa ays_fa_info_circle"></i>
                                                        </a>
                                                    </label>
                                                </div>
                                                <div class="col-sm-9">
                                                    <input type="checkbox" value="on" tabindex="-1"/>
                                                </div>
                                            </div>

                                            <a href="https://ays-pro.com/wordpress/quiz-maker?utm_source=dashboard&utm_medium=quiz-free&utm_campaign=pro-popup-extra-security-<?php echo esc_attr( AYS_QUIZ_UTM_VERSION ); ?>" target="_blank" class="ays-quiz-new-upgrade-button-link">
                                                <div class="ays-quiz-new-upgrade-button-box">
                                                    <div>
                                                        <img loading="lazy" src="<?php echo AYS_QUIZ_ADMIN_URL.'/images/icons/locked_24x24.svg'?>">
                                                        <img loading="lazy" src="<?php echo AYS_QUIZ_ADMIN_URL.'/images/icons/unlocked_24x24.svg'?>" class="ays-quiz-new-upgrade-button-hover">
                                                    </div>
                                                    <div class="ays-quiz-new-upgrade-button"><?php echo __("Upgrade", "quiz-maker"); ?></div>
                                                </div>
                                            </a>
                                            <div class="ays-quiz-new-watch-video-button-box">
                                                <div>
                                                    <img loading="lazy" src="<?php echo AYS_QUIZ_ADMIN_URL.'/images/icons/video_24x24.svg'?>">
                                                    <img loading="lazy" src="<?php echo AYS_QUIZ_ADMIN_URL.'/images/icons/video_24x24_hover.svg'?>" class="ays-quiz-new-watch-video-button-hover">
                                                </div>
                                                <div class="ays-quiz-new-watch-video-button"><?php echo __("Watch Video", "quiz-maker"); ?></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <hr/>
                        <div class="form-group row ays-quiz-result-message-vars-parent">
                            <div class="col-sm-3">
                                <label for="ays_enable_logged_users">
                                    <?php echo __('Only for logged in users','quiz-maker')?>
                                    <a class="ays_help" data-toggle="tooltip" title="<?php echo __('After enabling this option, only logged in users will be able to pass the quiz.','quiz-maker')?>">
                                        <i class="ays_fa ays_fa_info_circle"></i>
                                    </a>
                                </label>
                            </div>
                            <div class="col-sm-1">
                                <input type="checkbox" class="ays-enable-timer1" id="ays_enable_logged_users"
                                       name="ays_enable_logged_users"
                                       value="on" <?php echo (((isset($options['enable_logged_users']) && $options['enable_logged_users'] == 'on')) || (isset($options['enable_restriction_pass']) && $options['enable_restriction_pass'] == 'on')) ? 'checked' : ''; ?>/>
                            </div>
                            <div class="col-sm-8" style="border-left: 1px solid #ededed; <?php echo ((isset($options['enable_logged_users']) && $options['enable_logged_users'] == 'on') || (isset($options['enable_restriction_pass']) && $options['enable_restriction_pass'] == 'on')) ? '' : 'display:none;' ?>"
                                 id="ays_logged_in_users_div" >
                                <div class="form-group row">
                                    <div class="col-sm-3">
                                        <label for="ays_logged_in_message">
                                            <?php echo __('Message','quiz-maker')?>
                                            <a class="ays_help" data-toggle="tooltip" title="<?php echo __('Message for those who haven’t logged in','quiz-maker')?>">
                                                <i class="ays_fa ays_fa_info_circle"></i>
                                            </a>
                                        </label>
                                    </div>
                                    <div class="col-sm-9">
                                        <?php
                                        echo $quiz_message_vars_logged_in_users_html;
                                        $content = wpautop(stripslashes((isset($options['enable_logged_users_message'])) ? $options['enable_logged_users_message'] : ''));
                                        $editor_id = 'ays_logged_in_message';
                                        $settings = array('editor_height' => $quiz_wp_editor_height, 'textarea_name' => 'ays_enable_logged_users_message', 'editor_class' => 'ays-textarea', 'media_elements' => false);
                                        wp_editor($content, $editor_id, $settings);
                                        ?>
                                    </div>
                                </div>
                                <hr/>
                                <div class="form-group row">
                                    <div class="col-sm-3">
                                        <label for="ays_show_login_form">
                                            <?php echo __('Show Login form','quiz-maker')?>
                                            <a class="ays_help" data-toggle="tooltip" title="<?php echo __('Show the Login form bottom of the message for not logged in users.','quiz-maker')?>">
                                                <i class="ays_fa ays_fa_info_circle"></i>
                                            </a>
                                        </label>
                                    </div>
                                    <div class="col-sm-9">
                                        <input type="checkbox" class="ays-enable-timer1" id="ays_show_login_form" name="ays_show_login_form" value="on" <?php echo $show_login_form ? 'checked' : ''; ?>/>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <hr/>
                        <div class="form-group row ays-quiz-result-message-vars-parent">
                            <div class="col-sm-3">
                                <label for="ays_enable_restriction_pass">
                                    <?php echo __('Only for selected user role','quiz-maker')?>
                                    <a class="ays_help" data-toggle="tooltip" title="<?php echo __('Quiz is available only for the users who have role mentioned in the list.','quiz-maker')?>">
                                        <i class="ays_fa ays_fa_info_circle"></i>
                                    </a>
                                </label>
                            </div>
                            <div class="col-sm-1">
                                <input type="checkbox" class="ays-enable-timer1" id="ays_enable_restriction_pass"
                                       name="ays_enable_restriction_pass"
                                       value="on" <?php echo (isset($options['enable_restriction_pass']) && $options['enable_restriction_pass'] == 'on') ? 'checked' : ''; ?>/>
                            </div>
                            <div class="col-sm-8" id="ays_users_roles_td"
                                 style="border-left: 1px solid #ededed; display: <?php echo (isset($options['enable_restriction_pass']) && $options['enable_restriction_pass'] == 'on') ? '' : 'none'; ?>">
                                <div class="form-group row">
                                    <div class="col-sm-3">
                                        <label for="ays_users_roles">
                                            <?php echo __('User role','quiz-maker')?>
                                            <a class="ays_help" data-toggle="tooltip" title="<?php echo __('Role of the user on the website. Option accepts multiple values.','quiz-maker')?>">
                                                <i class="ays_fa ays_fa_info_circle"></i>
                                            </a>
                                        </label>
                                    </div>
                                    <div class="col-sm-9">
                                        <select name="ays_users_roles[]" id="ays_users_roles" multiple>
                                            <?php
                                            foreach ($ays_users_roles as $key => $user_role) {
                                                $selected_role = "";
                                                if(isset($options['user_role'])){
                                                    if(is_array($options['user_role'])){
                                                        if(in_array($user_role['name'], $options['user_role'])){
                                                            $selected_role = 'selected';
                                                        }else{
                                                            $selected_role = '';
                                                        }
                                                    }else{
                                                        if($options['user_role'] == $user_role['name']){
                                                            $selected_role = 'selected';
                                                        }else{
                                                            $selected_role = '';
                                                        }
                                                    }
                                                }
                                                echo "<option value='" . $user_role['name'] . "' " . $selected_role . ">" . $user_role['name'] . "</option>";
                                            }
                                            ?>
                                        </select>
                                    </div>
                                </div>
                                <hr>
                                <div class="form-group row">
                                    <div class="col-sm-3">
                                        <label for="restriction_pass_message">
                                            <?php echo __('Message','quiz-maker')?>
                                            <a class="ays_help" data-toggle="tooltip" title="<?php echo __('Message for the users who aren’t included in the above-mentioned list.','quiz-maker')?>">
                                                <i class="ays_fa ays_fa_info_circle"></i>
                                            </a>
                                        </label>
                                    </div>
                                    <div class="col-sm-9">
                                        <?php
                                        echo $quiz_message_vars_only_selected_user_role_html;
                                        $content = wpautop(stripslashes((isset($options['restriction_pass_message'])) ? $options['restriction_pass_message'] : ''));
                                        $editor_id = 'restriction_pass_message';
                                        $settings = array('editor_height' => $quiz_wp_editor_height, 'textarea_name' => 'restriction_pass_message', 'editor_class' => 'ays-textarea', 'media_elements' => false);
                                        wp_editor($content, $editor_id, $settings);
                                        ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <hr/>
                        <div class="form-group row ays_toggle_parent ays-quiz-result-message-vars-parent">
                            <div class="col-sm-3">
                                <label for="ays_enable_tackers_count">
                                    <?php echo __('Limitation count of takers', 'quiz-maker')?>
                                    <a class="ays_help" data-toggle="tooltip" title="<?php echo __('You can choose how many users can pass the quiz.','quiz-maker')?>">
                                        <i class="ays_fa ays_fa_info_circle"></i>
                                    </a>
                                </label>
                            </div>
                            <div class="col-sm-1">
                                <input type="checkbox" class="ays-enable-timer1 ays_toggle_checkbox" id="ays_enable_tackers_count"
                                       name="ays_enable_tackers_count" value="on" <?php echo $enable_tackers_count ? 'checked' : ''; ?>/>
                            </div>
                            <div class="col-sm-8 ays_toggle_target ays_divider_left <?php echo $enable_tackers_count ? '' : 'display_none'; ?>">
                                <div class="form-group row">
                                    <div class="col-sm-2">
                                        <label for="ays_tackers_count">
                                            <?php echo __('Count','quiz-maker')?>
                                            <a class="ays_help" data-toggle="tooltip" title="<?php echo __('The number of users who can pass the quiz.','quiz-maker')?>">
                                                <i class="ays_fa ays_fa_info_circle"></i>
                                            </a>
                                        </label>
                                    </div>
                                    <div class="col-sm-10">
                                        <input type="number" name="ays_tackers_count" id="ays_tackers_count" class="ays-enable-timerl ays-text-input"
                                               value="<?php echo $tackers_count; ?>">
                                    </div>
                                </div>
                                <hr>
                                <div class="form-group row">
                                    <div class="col-sm-2">
                                        <label for="ays_quiz_tackers_message">
                                            <?php echo __('Message','quiz-maker'); ?>
                                            <a class="ays_help" data-toggle="tooltip" title="<?php echo __('Show the message when the quiz is already taken by the required count of takers.','quiz-maker'); ?>">
                                                <i class="ays_fa ays_fa_info_circle"></i>
                                            </a>
                                        </label>
                                    </div>
                                    <div class="col-sm-10">
                                        <?php
                                        echo $quiz_message_vars_limitation_count_of_takers_html;
                                        $editor_id = 'ays_quiz_tackers_message';
                                        $settings = array('editor_height' => $quiz_wp_editor_height, 'textarea_name' => 'ays_quiz_tackers_message', 'editor_class' => 'ays-textarea', 'media_elements' => false);
                                        wp_editor($quiz_tackers_message, $editor_id, $settings);
                                        ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <hr>
                        <div class="form-group row ays_toggle_parent">
                            <div class="col-sm-3">
                                <label for="ays_enable_password">
                                    <?php echo __('Password for passing quiz', 'quiz-maker')?>
                                    <a class="ays_help" data-toggle="tooltip" title="<?php echo __('You can choose a password for users to pass the quiz.','quiz-maker')?>">
                                        <i class="ays_fa ays_fa_info_circle"></i>
                                    </a>
                                </label>
                            </div>
                            <div class="col-sm-1">
                                <input type="checkbox" class="ays-enable-timer1 ays_toggle_checkbox" id="ays_enable_password" name="ays_enable_password" value="on" <?php echo $enable_password ? 'checked' : ''; ?>/>
                            </div>
                            <div class="col-sm-12 ays_toggle_target <?php echo $enable_password ? '' : 'display_none'; ?>">
                                <div class="form-group row" style="position:relative;">
                                    <div class="col-sm-12 only_pro" style="padding:15px;">
                                        <div class="pro_features" style="align-items: flex-end;justify-content: flex-end;">

                                        </div>
                                        <div class="form-group row">
                                            <div class="col-sm-6">
                                                <label class="ays_quiz_loader" for="ays_psw_quiz">
                                                    <input type="radio" class="ays_generate_password_quiz_class" id="ays_psw_quiz" name='ays_psw_quiz' value='general' checked>
                                                    <?php echo __('General', 'quiz-maker') ?>
                                                </label>
                                                <label class="ays_quiz_loader" for="ays_generate_password_quiz">
                                                    <input type="radio" class="ays_generate_password_quiz_class">
                                                    <?php echo __('Generated Passwords', 'quiz-maker') ?>
                                                </label>
                                            </div>

                                            <div class="col-sm-6 ays_psw_quiz_import_type_box">
                                                <label class="ays_quiz_loader">
                                                    <input type="radio" checked>
                                                    <?php echo __('Default', 'quiz-maker') ?>
                                                </label>
                                                <label class="ays_quiz_loader">
                                                    <input type="radio">
                                                    <?php echo __('File upload', 'quiz-maker') ?>
                                                </label>
                                                <label class="ays_quiz_loader">
                                                    <input type="radio">
                                                    <?php echo __('Clipboard', 'quiz-maker') ?>
                                                </label>
                                            </div>
                                        </div>
                                        <a href="https://ays-pro.com/wordpress/quiz-maker?utm_source=dashboard&utm_medium=quiz-free&utm_campaign=generated-passwords-<?php echo esc_attr( AYS_QUIZ_UTM_VERSION ); ?>" target="_blank" class="ays-quiz-new-upgrade-button-link">
                                            <div class="ays-quiz-new-upgrade-button-box">
                                                <div>
                                                    <img loading="lazy" src="<?php echo AYS_QUIZ_ADMIN_URL.'/images/icons/locked_24x24.svg'?>">
                                                    <img loading="lazy" src="<?php echo AYS_QUIZ_ADMIN_URL.'/images/icons/unlocked_24x24.svg'?>" class="ays-quiz-new-upgrade-button-hover">
                                                </div>
                                                <div class="ays-quiz-new-upgrade-button"><?php echo __("Upgrade", "quiz-maker"); ?></div>
                                            </div>
                                        </a>
                                    </div>
                                </div>
                                <hr>
                                <div class="ays-quiz-heading-box ays-quiz-unset-float" style="margin-bottom: 10px;">
                                    <div class="ays-quiz-wordpress-user-manual-box ays-quiz-wordpress-text-align">
                                        <a href="https://www.youtube.com/watch?v=BMmQbafdzR8" target="_blank">
                                            <?php echo __("How to create Password-Protected Quiz - video", 'quiz-maker'); ?>
                                        </a>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <div class="col-sm-2">
                                        <label for="ays_password_quiz">
                                            <?php echo __('Password','quiz-maker')?>
                                            <a class="ays_help" data-toggle="tooltip" title="<?php echo __('Password for users who can pass the quiz.','quiz-maker')?>">
                                                <i class="ays_fa ays_fa_info_circle"></i>
                                            </a>
                                        </label>
                                    </div>
                                    <div class="col-sm-10">
                                        <input type="text" name="ays_password_quiz" id="ays_password_quiz" class="ays-enable-timer ays-text-input" value="<?php echo $password_quiz; ?>">
                                    </div>
                                </div>
                                <hr>
                                <div class="form-group row">
                                    <div class="col-sm-2">
                                        <label for="ays_password_quiz">
                                            <?php echo __('Password input width','quiz-maker')?>
                                            <a class="ays_help" data-toggle="tooltip" title="<?php echo __('Define the password text box width in px. If you leave the box empty the width will automatically be 100%.','quiz-maker'); ?>">
                                                <i class="ays_fa ays_fa_info_circle"></i>
                                            </a>
                                        </label>
                                    </div>
                                    <div class="col-sm-10">
                                        <input type="text" name="ays_quiz_password_width" id="ays_quiz_password_width" class="ays-enable-timer ays-text-input" value="<?php echo $quiz_password_width; ?>">
                                        <span style="display:block;" class="ays_quiz_small_hint_text"><?php echo __("For 100% leave blank", 'quiz-maker');?></span>
                                    </div>
                                </div>
                                <hr>
                                <div class="form-group row">
                                    <div class="col-sm-2">
                                        <label for="ays_quiz_enable_password_visibility">
                                            <?php echo __('Enable toggle password visibility','quiz-maker'); ?>
                                            <a class="ays_help" data-toggle="tooltip" title="<?php echo __('Tick the option, and it will let you enable and disable password visibility in a password input field.','quiz-maker'); ?>">
                                                <i class="ays_fa ays_fa_info_circle"></i>
                                            </a>
                                        </label>
                                    </div>
                                    <div class="col-sm-10">
                                        <input type="checkbox" class="ays-enable-timer1" id="ays_quiz_enable_password_visibility" name="ays_quiz_enable_password_visibility" value="on" <?php echo $quiz_enable_password_visibility ? 'checked' : ''; ?>/>
                                    </div>
                                </div>
                                <hr>
                                <div class="form-group row ays-quiz-result-message-vars-parent">
                                    <div class="col-sm-2">
                                        <label for="ays_quiz_password_message">
                                            <?php echo __('Message','quiz-maker'); ?>
                                            <a class="ays_help" data-toggle="tooltip" title="<?php echo __('Users will see this message before entering the password for passing the quiz.','quiz-maker'); ?>">
                                                <i class="ays_fa ays_fa_info_circle"></i>
                                            </a>
                                        </label>
                                    </div>
                                    <div class="col-sm-10">
                                        <?php
                                        echo $quiz_message_vars_password_for_passing_quiz_html;
                                        $content = $quiz_password_message;
                                        $editor_id = 'ays_quiz_password_message';
                                        $settings = array('editor_height' => $quiz_wp_editor_height, 'textarea_name' => 'ays_quiz_password_message', 'editor_class' => 'ays-textarea', 'media_elements' => false);
                                        wp_editor($content, $editor_id, $settings);
                                        ?>
                                    </div>
                                </div>
                            </div>
                        </div><!-- Password for quiz -->
                        <hr/>  <!-- AV Access Only selected users -->
                        <div class="form-group row" style="position:relative;">
                            <div class="col-sm-12 only_pro" style="padding:15px;">
                                <div class="pro_features pro_features_popup">
                                    <div class="pro-features-popup-conteiner">
                                        <div class="pro-features-popup-title">
                                            <?php echo __("Access only selected users", 'quiz-maker'); ?>
                                        </div>
                                        <div class="pro-features-popup-content" data-link="https://youtu.be/mWAByB5La3Q">
                                            <p>
                                                <?php echo sprintf( __("Do you want to decide for yourself who can take your quiz? Access only selected users feature of the Quiz Maker plugin allows you to decide who can take your quiz swiftly. Just enable that feature and type in only the users' names %s who will have an access to your quiz. %s The ones whose names you don’t type, can’t take your quiz. So type in the message and inform them about it.", 'quiz-maker'),
                                                    "<strong>",
                                                    "</strong>"
                                                ); ?>
                                            </p>
                                            <div>
                                                <a href="https://quiz-plugin.com/docs/" target="_blank"><?php echo __("See Documentation", 'quiz-maker'); ?></a>
                                            </div>
                                        </div>
                                        <div class="pro-features-popup-button" data-link="https://ays-pro.com/wordpress/quiz-maker?utm_source=dashboard&utm_medium=quiz-free&utm_campaign=pro-popup-only-selected-users-<?php echo esc_attr( AYS_QUIZ_UTM_VERSION ); ?>">
                                            <?php echo __("Pricing", 'quiz-maker'); ?>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group row ays_toggle_parent">
                                    <div class="col-sm-3">
                                        <label for="ays_enable_restriction_pass_users">
                                            <?php echo __('Access only selected users','quiz-maker')?>
                                            <a class="ays_help" data-toggle="tooltip" title="<?php echo __('Quiz is available only for the users mentioned in the list.','quiz-maker')?>">
                                                <i class="ays_fa ays_fa_info_circle"></i>
                                            </a>
                                        </label>
                                    </div>
                                    <div class="col-sm-1">
                                        <input type="checkbox" class="ays-enable-timer1 ays_toggle_checkbox" id="ays_enable_restriction_pass_users" value="on" tabindex="-1" />
                                    </div>
                                    <div class="col-sm-8 ays_toggle_target ays_divider_left">
                                        <div class="form-group row">
                                            <div class="col-sm-2">
                                                <label for="ays_users_roles">
                                                    <?php echo __('Users','quiz-maker')?>
                                                    <a class="ays_help" data-toggle="tooltip" title="<?php echo __('Users on the website.','quiz-maker')?>">
                                                        <i class="ays_fa ays_fa_info_circle"></i>
                                                    </a>
                                                </label>
                                            </div>
                                            <div class="col-sm-10">
                                                <select id="ays_quiz_users_sel" name="ays_users_search[]" tabindex="-1" multiple style="width: 100%; max-width: 100%;">
                                                    <option></option>
                                                </select>
                                            </div>
                                        </div>
                                        <hr>
                                        <div class="form-group row">
                                            <div class="col-sm-2">
                                                <label for="restriction_pass_users_message">
                                                    <?php echo __('Message','quiz-maker')?>
                                                    <a class="ays_help" data-toggle="tooltip" title="<?php echo __('Message for the users who aren’t included in the above-mentioned list.','quiz-maker')?>">
                                                        <i class="ays_fa ays_fa_info_circle"></i>
                                                    </a>
                                                </label>
                                            </div>
                                            <div class="col-sm-10">
                                                <textarea type="text" tabindex="-1" id="restriction_pass_users_message" class="ays-textarea ays-disable-setting"
                                                      disabled></textarea>                                        
                                            </div>
                                        </div>
                                    </div>
                                </div> <!-- Access Only selected users -->

                                <a href="https://ays-pro.com/wordpress/quiz-maker?utm_source=dashboard&utm_medium=quiz-free&utm_campaign=pro-popup-only-selected-users-<?php echo esc_attr( AYS_QUIZ_UTM_VERSION ); ?>" target="_blank" class="ays-quiz-new-upgrade-button-link">
                                    <div class="ays-quiz-new-upgrade-button-box">
                                        <div>
                                            <img loading="lazy" src="<?php echo AYS_QUIZ_ADMIN_URL.'/images/icons/locked_24x24.svg'?>">
                                            <img loading="lazy" src="<?php echo AYS_QUIZ_ADMIN_URL.'/images/icons/unlocked_24x24.svg'?>" class="ays-quiz-new-upgrade-button-hover">
                                        </div>
                                        <div class="ays-quiz-new-upgrade-button"><?php echo __("Upgrade", "quiz-maker"); ?></div>
                                    </div>
                                </a>
                                <div class="ays-quiz-new-watch-video-button-box">
                                    <div>
                                        <img loading="lazy" src="<?php echo AYS_QUIZ_ADMIN_URL.'/images/icons/video_24x24.svg'?>">
                                        <img loading="lazy" src="<?php echo AYS_QUIZ_ADMIN_URL.'/images/icons/video_24x24_hover.svg'?>" class="ays-quiz-new-watch-video-button-hover">
                                    </div>
                                    <div class="ays-quiz-new-watch-video-button"><?php echo __("Watch Video", "quiz-maker"); ?></div>
                                </div>
                                <div class="ays-quiz-center-big-main-button-box ays-quiz-new-big-button-flex">
                                    <div class="ays-quiz-center-big-watch-video-button-box ays-quiz-big-upgrade-margin-right-10">
                                        <div class="ays-quiz-center-new-watch-video-demo-button">
                                            <img loading="lazy" src="<?php echo AYS_QUIZ_ADMIN_URL.'/images/icons/video_24x24.svg'?>" class="ays-quiz-new-button-img-hide">
                                            <img loading="lazy" src="<?php echo AYS_QUIZ_ADMIN_URL.'/images/icons/video_24x24_hover.svg'?>" class="ays-quiz-new-watch-video-button-hover">
                                            <?php echo __("Watch Video", "quiz-maker"); ?>
                                        </div>
                                    </div>
                                    <div class="ays-quiz-center-big-upgrade-button-box">
                                        <a href="https://ays-pro.com/wordpress/quiz-maker?utm_source=dashboard&utm_medium=quiz-free&utm_campaign=pro-popup-only-selected-users-<?php echo esc_attr( AYS_QUIZ_UTM_VERSION ); ?>" target="_blank" class="ays-quiz-new-upgrade-button-link">
                                            <div class="ays-quiz-center-new-big-upgrade-button">
                                                <img loading="lazy" src="<?php echo AYS_QUIZ_ADMIN_URL.'/images/icons/locked_24x24.svg'?>" class="ays-quiz-new-button-img-hide">
                                                <img loading="lazy" src="<?php echo AYS_QUIZ_ADMIN_URL.'/images/icons/unlocked_24x24.svg'?>" class="ays-quiz-new-upgrade-button-hover">  
                                                <?php echo __("Upgrade", "quiz-maker"); ?>
                                            </div>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div id="tab6" class="ays-quiz-tab-content <?php echo ($ays_quiz_tab == 'tab6') ? 'ays-quiz-tab-content-active' : ''; ?>">
                <div class="ays-quiz-top-actions-container-wrapper form-group row">
                    <div class="col-sm-12">
                        <p class="m-0 text-right">
                            <a class="ays-quiz-collapse-all" href="javascript:void(0);"><?php echo __( "Collapse All", "quiz-maker" ); ?></a>
                            <span>|</span>
                            <a class="ays-quiz-expand-all" href="javascript:void(0);"><?php echo __( "Expand All", "quiz-maker" ); ?></a>
                        </p>
                    </div>
                </div>
                <div class="ays-quiz-accordion-options-main-container" data-collapsed="false">
                    <div class="ays-quiz-accordion-container">
                        <?php echo $quiz_accordion_svg_html; ?>
                        <p class="ays-subtitle"><?php echo __('User Information','quiz-maker')?></p>
                    </div>
                    <hr class="ays-quiz-bolder-hr"/>
                    <div class="ays-quiz-accordion-options-box">
                        <div class="form-group row ays-quiz-result-message-vars-parent">
                            <div class="col-sm-4">
                                <label for="ays_form_title">
                                    <?php echo __('Information Form title','quiz-maker')?>
                                    <a class="ays_help" data-toggle="tooltip" title="<?php echo __('Description of the Information Form which will be shown at the top of the Form Fields.','quiz-maker')?>">
                                        <i class="ays_fa ays_fa_info_circle"></i>
                                    </a>
                                </label>
                            </div>
                            <div class="col-sm-8" style="border-left: 1px solid #ccc">
                                <?php
                                echo $quiz_message_vars_information_form_html;
                                $content = wpautop(stripslashes((isset($options['form_title'])) ? $options['form_title'] : ''));
                                $editor_id = 'ays_form_title';
                                $settings = array('editor_height' => $quiz_wp_editor_height, 'textarea_name' => 'ays_form_title', 'editor_class' => 'ays-textarea', 'media_elements' => false);
                                wp_editor($content, $editor_id, $settings);
                                ?>
                            </div>
                        </div>
                        <hr/>
                        <div class="form-group row">
                            <div class="col-sm-2">
                                <label for="ays_information_form">
                                    <?php echo __('Information form','quiz-maker')?>
                                    <a class="ays_help" data-toggle="tooltip" title="<?php echo __('Data form for the user personal information. You can choose when the Information Form will be shown for completion.','quiz-maker')?>">
                                        <i class="ays_fa ays_fa_info_circle"></i>
                                    </a>
                                </label>
                            </div>
                            <div class="col-sm-2">
                                <div class="information_form_settings">
                                    <select name="ays_information_form" id="ays_information_form">
                                        <option value="after" <?php echo (isset($options['information_form']) && $options['information_form'] == 'after') ? 'selected' : ''; ?>>
                                            <?php echo __('After Quiz','quiz-maker')?>
                                        </option>
                                        <option value="before" <?php echo (isset($options['information_form']) && $options['information_form'] == 'before') ? 'selected' : ''; ?>>
                                            <?php echo __('Before Quiz','quiz-maker')?>
                                        </option>
                                        <option value="disable" <?php echo (isset($options['information_form']) && $options['information_form'] == 'disable') ? 'selected' : ''; ?>>
                                            <?php echo __('Disable','quiz-maker')?>
                                        </option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-sm-8" style="border-left: 1px solid #ccc">
                                <div class="information_form_options" <?php echo (!isset($options['information_form']) || $options['information_form'] == "disable") ? 'style="display:none"' : ''; ?>>
                                    <p class="ays_required_field_title"><?php echo __('Form Fields','quiz-maker')?></p>
                                    <hr>
                                    <div class="form-check form-check-inline">
                                        <input type="checkbox" class="form-check-input" id="ays_form_name" name="ays_form_name"
                                               value="on" <?php echo (isset($options['form_name']) && $options['form_name'] !== '') ? 'checked' : ''; ?>/>
                                        <label class="form-check-label" for="ays_form_name"><?php echo __('Name','quiz-maker')?></label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input type="checkbox" class="form-check-input" id="ays_form_email"
                                               name="ays_form_email"
                                               value="on" <?php echo (isset($options['form_email']) && $options['form_email'] !== '') ? 'checked' : ''; ?>/>
                                        <label class="form-check-label" for="ays_form_email"><?php echo __('Email','quiz-maker')?></label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input type="checkbox" class="form-check-input" id="ays_form_phone"
                                               name="ays_form_phone"
                                               value="on" <?php echo (isset($options['form_phone']) && $options['form_phone'] !== '') ? 'checked' : ''; ?>/>
                                        <label class="form-check-label" for="ays_form_phone"><?php echo __('Phone','quiz-maker')?></label>
                                    </div>
                                    <hr>
                                    <p class="ays_required_field_title"><?php echo __('Required Fields','quiz-maker')?></p>
                                    <hr>
                                    <div class="form-check form-check-inline">
                                        <input type="checkbox" class="form-check-input" id="ays_form_name_required"
                                               name="ays_required_field[]"
                                               value="ays_user_name" <?php echo (in_array('ays_user_name', $required_fields)) ? 'checked' : ''; ?>/>
                                        <label class="form-check-label" for="ays_form_name_required"><?php echo __('Name','quiz-maker')?></label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input type="checkbox" class="form-check-input" id="ays_form_email_required"
                                               name="ays_required_field[]"
                                               value="ays_user_email" <?php echo (in_array('ays_user_email', $required_fields)) ? 'checked' : ''; ?>/>
                                        <label class="form-check-label" for="ays_form_email_required"><?php echo __('Email','quiz-maker')?></label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input type="checkbox" class="form-check-input" id="ays_form_phone_required"
                                               name="ays_required_field[]"
                                               value="ays_user_phone" <?php echo (in_array('ays_user_phone', $required_fields)) ? 'checked' : ''; ?>/>
                                        <label class="form-check-label" for="ays_form_phone_required"><?php echo __('Phone','quiz-maker')?></label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <hr/>
                        <div class="form-group row">
                            <div class="col-sm-4">
                                <label for="ays_show_information_form">
                                    <?php echo __('Show Information Form to logged-in users','quiz-maker'); ?>
                                    <a class="ays_help" data-toggle="tooltip" title="<?php echo __('Enable the option if you want to show the Information Form to logged-in users as well. If the option is disabled, then logged-in users will not see the Information Form before or after the quiz, but the system will collect the Name and Email info from their WP accounts and store in the Name and Email fields in the database.','quiz-maker')?>">
                                        <i class="ays_fa ays_fa_info_circle"></i>
                                    </a>
                                </label>
                            </div>
                            <div class="col-sm-8">
                                <div class="information_form_settings">
                                    <input type="checkbox" id="ays_show_information_form" name="ays_show_information_form" value="on" <?php echo $show_information_form ? "checked" : ""; ?>>
                                </div>
                            </div>
                        </div>
                        <hr>
                        <div class="form-group row">
                            <div class="col-sm-4">
                                <label for="ays_autofill_user_data">
                                    <?php echo __('Autofill logged-in user data','quiz-maker'); ?>
                                    <a class="ays_help" data-toggle="tooltip" title="<?php echo __('After enabling this option, logged in  user’s name and email will be autofilled in Information Form.','quiz-maker'); ?>">
                                        <i class="ays_fa ays_fa_info_circle"></i>
                                    </a>
                                </label>
                            </div>
                            <div class="col-sm-8">
                                <div class="information_form_settings">
                                    <input type="checkbox" id="ays_autofill_user_data" name="ays_autofill_user_data" value="on" <?php echo $autofill_user_data ? "checked" : ""; ?>>
                                </div>
                            </div>
                        </div>
                        <hr>
                        <div class="form-group row">
                            <div class="col-sm-4">
                                <label for="ays_display_fields_labels">
                                    <?php echo __('Display form fields with labels','quiz-maker'); ?>
                                    <a class="ays_help" data-toggle="tooltip" title="<?php echo esc_attr__('Show labels of form fields on the top of each field. Texts of labels will be taken from the "Fields placeholder" section on the General setting page.','quiz-maker'); ?>">
                                        <i class="ays_fa ays_fa_info_circle"></i>
                                    </a>
                                </label>
                            </div>
                            <div class="col-sm-8">
                                <div class="information_form_settings">
                                    <input type="checkbox" id="ays_display_fields_labels" name="ays_display_fields_labels" value="on" <?php echo $display_fields_labels ? "checked" : ""; ?>>
                                </div>
                            </div>
                        </div>
                        <hr>
                        <div class="form-group row only_pro" style="position:relative;padding:15px;">
                            <div class="pro_features pro_features_popup">
                                <div class="pro-features-popup-conteiner">
                                    <div class="pro-features-popup-title">
                                        <?php echo __("Custom Field", 'quiz-maker'); ?>
                                    </div>
                                    <div class="pro-features-popup-content" data-link="https://youtu.be/SEv7ZY7idtE">
                                        <p>
                                            <?php echo sprintf( __("Custom Fields will allow you to create various fields with %s 8 available field types, %s including text, number, telephone. With just two simple steps, you can get any information you wish from the Quiz takers and add  %s GDPR %s checkbox as well. Get personal data, such as gender, country, age etc.", 'quiz-maker'),
                                                "<strong>",
                                                "</strong>",
                                                "<strong>",
                                                "</strong>"
                                            ); ?>
                                        </p>
                                        <div>
                                            <a href="https://quiz-plugin.com/docs/" target="_blank"><?php echo __("See Documentation", 'quiz-maker'); ?></a>
                                        </div>
                                    </div>
                                    <div class="pro-features-popup-button" data-link="https://ays-pro.com/wordpress/quiz-maker?utm_source=dashboard&utm_medium=quiz-free&utm_campaign=pro-popup-custom-field-option-<?php echo esc_attr( AYS_QUIZ_UTM_VERSION ); ?>">
                                        <?php echo __("Pricing", 'quiz-maker'); ?>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <label>
                                    <?php echo __('Add custom fields','quiz-maker'); ?>
                                    <a class="ays_help" tabindex="-1" data-toggle="tooltip" title="<?php echo __('You can add form custom fields from “Custom fields” page in Quiz Maker menu.  (text, textarea, checkbox, select, URL etc.)','quiz-maker')?>">
                                        <i class="ays_fa ays_fa_info_circle"></i>
                                    </a>
                                </label>
                            </div>
                            <div class="col-sm-8 ays_divider_left">
                                <blockquote>
                                    <?php echo __("For creating custom fields click", 'quiz-maker'); ?>
                                    <a href="?page=<?php echo $this->plugin_name; ?>-quiz-attributes" target="_blank" ><?php echo __("here", 'quiz-maker'); ?></a>
                                </blockquote>
                            </div>
                            <a href="https://ays-pro.com/wordpress/quiz-maker?utm_source=dashboard&utm_medium=quiz-free&utm_campaign=pro-popup-custom-field-option-<?php echo esc_attr( AYS_QUIZ_UTM_VERSION ); ?>" target="_blank" class="ays-quiz-new-upgrade-button-link">
                                <div class="ays-quiz-new-upgrade-button-box">
                                    <div>
                                        <img loading="lazy" src="<?php echo AYS_QUIZ_ADMIN_URL.'/images/icons/locked_24x24.svg'?>">
                                        <img loading="lazy" src="<?php echo AYS_QUIZ_ADMIN_URL.'/images/icons/unlocked_24x24.svg'?>" class="ays-quiz-new-upgrade-button-hover">
                                    </div>
                                    <div class="ays-quiz-new-upgrade-button"><?php echo __("Upgrade", "quiz-maker"); ?></div>
                                </div>
                            </a>
                            <div class="ays-quiz-new-watch-video-button-box">
                                <div>
                                    <img loading="lazy" src="<?php echo AYS_QUIZ_ADMIN_URL.'/images/icons/video_24x24.svg'?>">
                                    <img loading="lazy" src="<?php echo AYS_QUIZ_ADMIN_URL.'/images/icons/video_24x24_hover.svg'?>" class="ays-quiz-new-watch-video-button-hover">
                                </div>
                                <div class="ays-quiz-new-watch-video-button"><?php echo __("Watch Video", "quiz-maker"); ?></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div id="tab7" class="ays-quiz-tab-content <?php echo ($ays_quiz_tab == 'tab7') ? 'ays-quiz-tab-content-active' : ''; ?>">
                <div class="ays-quiz-top-actions-container-wrapper form-group row">
                    <div class="col-sm-12">
                        <p class="m-0 text-right">
                            <a class="ays-quiz-collapse-all" href="javascript:void(0);"><?php echo esc_html__( "Collapse All", "quiz-maker" ); ?></a>
                            <span>|</span>
                            <a class="ays-quiz-expand-all" href="javascript:void(0);"><?php echo esc_html__( "Expand All", "quiz-maker" ); ?></a>
                        </p>
                    </div>
                </div>
                <div class="ays-quiz-accordion-options-main-container" data-collapsed="false">
                    <div class="ays-quiz-accordion-container">
                        <?php echo $quiz_accordion_svg_html; ?>
                        <p class="ays-subtitle"><?php echo esc_html__('E-mail and Certificate settings','quiz-maker')?></p>
                    </div>
                    <hr class="ays-quiz-bolder-hr"/>
                    <div class="ays-quiz-accordion-options-box">
                        <div class="form-group row">
                            <div class="col-sm-12">
                                <div class="ays-quiz-heading-box ays-quiz-unset-float ays-quiz-unset-margin">
                                    <div class="ays-quiz-wordpress-user-manual-box ays-quiz-wordpress-text-align">
                                        <a href="https://www.youtube.com/watch?v=LoQw1wxkj6k" target="_blank">
                                            <?php echo esc_html__("How to create certifiication test - video", 'quiz-maker'); ?>
                                        </a>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-12 only_pro ays-quiz-margin-top-20">
                                <div class="pro_features pro_features_popup">
                                    <div class="pro-features-popup-conteiner">
                                        <div class="pro-features-popup-title">
                                            <?php echo esc_html__("Send email to user", 'quiz-maker'); ?>
                                        </div>
                                        <div class="pro-features-popup-content" data-link="https://youtu.be/tHKiu-AOvYw">
                                            <p>
                                                <?php echo sprintf( esc_attr( __("Get in touch with the Quiz takers directly. %s Generate more leads %s and use their email address to send the quiz results and build relationships with your website visitors. Generate a Certificate for each user and send it to their email, so they can easily install and keep it.", 'quiz-maker') ),
                                                    "<strong>",
                                                    "</strong>"
                                                ); ?>
                                            </p>
                                            <p>
                                                <?php echo sprintf( esc_attr( __("To be sure that the users will share their email addresses with you, here we have a little hack for you! Activate the %s Hide Score option (Results Settings > Hide Score) %s and display the score only in the email message. Now, you are ready to generate leads, so start thinking about your email campaigns.", 'quiz-maker') ),
                                                    "<strong>",
                                                    "</strong>"
                                                ); ?>
                                            </p>
                                            <div>
                                                <a href="https://quiz-plugin.com/docs/" target="_blank"><?php echo esc_html__("See Documentation", 'quiz-maker'); ?></a>
                                            </div>
                                        </div>
                                        <div class="pro-features-popup-button" data-link="https://ays-pro.com/wordpress/quiz-maker?utm_source=dashboard&utm_medium=quiz-free&utm_campaign=pro-popup-send-mail-to-user-<?php echo esc_attr( AYS_QUIZ_UTM_VERSION ); ?>">
                                            <?php echo esc_html__("Pricing", 'quiz-maker'); ?>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <div class="col-sm-2">
                                        <label class="ays-disable-setting"><?php echo esc_html__('Send Mail To User','quiz-maker')?></label>
                                    </div>
                                    <div class="col-sm-2">
                                        <input type="checkbox" class="ays-enable-timerl ays-disable-setting"
                                               id="ays_enable_mail_user"
                                               disabled/>
                                    </div>

                                    <div class="col-sm-8 ays_divider_left" id="ays_mail_message_div">
                                        <div class="form-group row">
                                            <div class="col-sm-3">
                                                <label for="ays_enable_send_mail_to_user_by_pass_score">
                                                    <?php echo esc_html__('Pass score (%)', 'quiz-maker'); ?>
                                                    <a  class="ays_help" data-toggle="tooltip" title="<?php echo esc_attr( __('If the option is enabled, then the user will receive the email only if he/she has passed the minimum score required. It will take the value of the general pass score of the quiz. Please specify it in the Result Settings tab.','quiz-maker') ); ?>">
                                                        <i class=""></i>
                                                    </a>
                                                </label>
                                            </div>
                                            <div class="col-sm-9">
                                                <input type="checkbox" class="ays-enable-timerl" value="on" />
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <div class="col-sm-12">
                                                <blockquote><?php echo esc_html__( 'Tick the checkbox, and the user will receive the email only if he/she has passed the minimum score required.', 'quiz-maker' ); ?></blockquote>
                                            </div>
                                        </div>
                                        <hr/>
                                        <div class="form-group row">
                                            <div class="col-sm-3">
                                                <label class="ays-disable-setting"><?php echo esc_html__('Mail message','quiz-maker')?></label>
                                            </div>
                                            <div class="col-sm-9">
                                            <textarea type="text" id="ays_mail_message" class="ays-textarea ays-disable-setting"
                                                      disabled></textarea>
                                            </div>
                                        </div>
                                        <hr/>                              
                                        <div class="form-group row">
                                            <div class="col-sm-3">
                                                <label for="ays_send_results_user">
                                                    <?php echo esc_html__('Send Results to User','quiz-maker'); ?>
                                                </label>
                                            </div>
                                            <div class="col-sm-9">
                                                <input type="checkbox" class="ays-enable-timerl" id="ays_send_results_user" value="on"/>
                                            </div>
                                        </div>
                                        <hr/>
                                        <div class="form-group row">
                                            <div class="col-sm-3">
                                                <label for="ays_send_interval_msg">
                                                    <?php echo esc_html__('Send Interval message to User','quiz-maker'); ?>
                                                </label>
                                            </div>
                                            <div class="col-sm-9">
                                                <input type="checkbox" class="ays-enable-timerl" id="ays_send_interval_msg"/>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <a href="https://ays-pro.com/wordpress/quiz-maker?utm_source=dashboard&utm_medium=quiz-free&utm_campaign=pro-popup-send-mail-to-user-<?php echo esc_attr( AYS_QUIZ_UTM_VERSION ); ?>" target="_blank" class="ays-quiz-new-upgrade-button-link">
                                    <div class="ays-quiz-new-upgrade-button-box">
                                        <div>
                                            <img loading="lazy" src="<?php echo esc_url( AYS_QUIZ_ADMIN_URL.'/images/icons/locked_24x24.svg' ); ?>">
                                            <img loading="lazy" src="<?php echo esc_url( AYS_QUIZ_ADMIN_URL.'/images/icons/unlocked_24x24.svg' ); ?>" class="ays-quiz-new-upgrade-button-hover">
                                        </div>
                                        <div class="ays-quiz-new-upgrade-button"><?php echo esc_html__("Upgrade", "quiz-maker"); ?></div>
                                    </div>
                                </a>
                                <div class="ays-quiz-new-watch-video-button-box">
                                    <div>
                                        <img loading="lazy" src="<?php echo esc_url( AYS_QUIZ_ADMIN_URL.'/images/icons/video_24x24.svg' ); ?>">
                                        <img loading="lazy" src="<?php echo esc_url( AYS_QUIZ_ADMIN_URL.'/images/icons/video_24x24_hover.svg' ); ?>" class="ays-quiz-new-watch-video-button-hover">
                                    </div>
                                    <div class="ays-quiz-new-watch-video-button"><?php echo esc_html__("Watch Video", "quiz-maker"); ?></div>
                                </div>
                                <div class="ays-quiz-center-big-main-button-box ays-quiz-new-big-button-flex">
                                    <div class="ays-quiz-center-big-watch-video-button-box ays-quiz-big-upgrade-margin-right-10">
                                        <div class="ays-quiz-center-new-watch-video-demo-button">
                                            <img loading="lazy" src="<?php echo esc_url(AYS_QUIZ_ADMIN_URL.'/images/icons/video_24x24.svg'); ?>" class="ays-quiz-new-button-img-hide">
                                            <img loading="lazy" src="<?php echo esc_url(AYS_QUIZ_ADMIN_URL.'/images/icons/video_24x24_hover.svg'); ?>" class="ays-quiz-new-watch-video-button-hover">
                                            <?php echo esc_html__("Watch Video", "quiz-maker"); ?>
                                        </div>
                                    </div>
                                    <div class="ays-quiz-center-big-upgrade-button-box">
                                        <a href="https://ays-pro.com/wordpress/quiz-maker?utm_source=dashboard&utm_medium=quiz-free&utm_campaign=pro-popup-send-mail-to-user-<?php echo esc_attr( AYS_QUIZ_UTM_VERSION ); ?>" target="_blank" class="ays-quiz-new-upgrade-button-link">
                                            <div class="ays-quiz-center-new-big-upgrade-button">
                                                <img loading="lazy" src="<?php echo esc_url(AYS_QUIZ_ADMIN_URL.'/images/icons/locked_24x24.svg'); ?>" class="ays-quiz-new-button-img-hide">
                                                <img loading="lazy" src="<?php echo esc_url(AYS_QUIZ_ADMIN_URL.'/images/icons/unlocked_24x24.svg'); ?>" class="ays-quiz-new-upgrade-button-hover">  
                                                <?php echo esc_html__("Upgrade", "quiz-maker"); ?>
                                            </div>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <hr/>
                        <div class="form-group row">
                            <div class="col-sm-12 only_pro">
                                <div class="pro_features pro_features_popup">
                                    <div class="pro-features-popup-conteiner">
                                        <div class="pro-features-popup-title">
                                            <?php echo esc_html__("Send Certificate to user", 'quiz-maker'); ?>
                                        </div>
                                        <div class="pro-features-popup-content" data-link="https://youtu.be/Av9HVaP3CdY">
                                            <p>
                                                <?php echo esc_html__("If you want to create an online exam and send a certificate to the users after the quiz completion, then, you can make use of the Send Certificate to user option.", 'quiz-maker'); ?>
                                            </p>
                                            <p>
                                                <?php echo sprintf( esc_html__("By enabling this option, you can send fully customized Certificates to your users by inserting your preferred message variables into the %s Certificate title %s and the %s Certificate body %s options.", 'quiz-maker'),
                                                    "<strong>",
                                                    "</strong>",
                                                    "<strong>",
                                                    "</strong>"
                                                ); ?>
                                            </p>
                                            <p>
                                                <?php echo esc_html__("Also, set a passing score, so that the users who got the minimum required score can receive the Certificate.", 'quiz-maker'); ?>
                                            </p>
                                            <div>
                                                <a href="https://quiz-plugin.com/english-exam-with-certificate/" target="_blank"><?php echo esc_html__("PRO Demo", 'quiz-maker'); ?></a>
                                            </div>
                                        </div>
                                        <div class="pro-features-popup-button" data-link="https://ays-pro.com/wordpress/quiz-maker?utm_source=dashboard&utm_medium=quiz-free&utm_campaign=pro-popup-send-certificate-to-user-<?php echo esc_attr( AYS_QUIZ_UTM_VERSION ); ?>">
                                            <?php echo esc_html__("Pricing", 'quiz-maker'); ?>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <div class="col-sm-2">
                                        <label class="ays-disable-setting"><?php echo esc_html__('Send Certificate To User','quiz-maker')?></label>
                                        <hr>
                                        <label for="ays_enable_certificate_without_send"><?php echo esc_html__('Generate certificate without sending to user','quiz-maker')?></label>
                                        <hr>
                                        <div class="ays_generate_cert_preview_wrap">
                                        <div class="ays_generate_cert_preview_button_wrap">
                                            <button class="button-primary" type="button"><?php echo esc_html__( 'Generate Certificate preview', 'quiz-maker' ); ?></button>
                                            <a class="ays_help" data-html="true" data-toggle="tooltip" title="<?php echo esc_attr( __("This is a just preview of the certificate and some message variables will not work on preview mode. Please be understanding.", 'quiz-maker' ) ); ?>">
                                                <i class=""></i>
                                            </a>
                                        </div>
                                    </div>
                                    </div>
                                    <div class="col-sm-2">
                                       <div style="display:inline-block;margin-bottom:.5rem;">
                                             <input type="checkbox" class="ays-enable-timerl ays-disable-setting"
                                               id="ays_enable_certificate"
                                               value="on" disabled/>
                                        </div>
                                        <hr>
                                        <div style="display:inline-block;margin-bottom:.5rem;">
                                            <input type="checkbox" class="ays-enable-timerl ays-disable-setting"
                                               value="on" disabled/>
                                        </div>
                                    </div>
                                    <div class="col-sm-8 ays_divider_left" id="ays_certificate_pass_div">

                                        <div class="form-group row">
                                            <div class="col-sm-12">
                                                <label class="ays_quiz_loader">
                                                    <input type="radio" class="ays-enable-timer1"/>
                                                    <span><?php echo esc_html__( "Percentage", 'quiz-maker' ); ?></span>
                                                </label>
                                                <label class="ays_quiz_loader">
                                                    <input type="radio" class="ays-enable-timer1" />
                                                    <span><?php echo esc_html__( "Points", 'quiz-maker' ); ?></span>
                                                </label>
                                            </div>
                                        </div>
                                        <hr>
                                        <div class="form-group row">
                                            <div class="col-sm-3">
                                                <label class="ays-disable-setting"><?php echo esc_html__('Certificate pass score','quiz-maker'); ?></label>
                                            </div>
                                            <div class="col-sm-9">
                                                <input type="number" id="ays_certificate_pass" class="ays-text-input ays-disable-setting" disabled>
                                            </div>    
                                        </div>   
                                        <hr/>                         
                                        <div class="form-group row">
                                            <div class="col-sm-3">
                                                <label class="ays-disable-setting"><?php echo esc_html__('Certificate title','quiz-maker'); ?></label>
                                            </div>
                                            <div class="col-sm-9">
                                                <textarea disabled class="ays-textarea ays-disable-setting"><?php echo esc_html__('Certificate of Completion','quiz-maker'); ?></textarea>
                                            </div>
                                        </div>
                                        <hr/>
                                        <div class="form-group row">
                                            <div class="col-sm-3">
                                                <label class="ays-disable-setting"><?php echo esc_html__('Certificate body','quiz-maker')?></label>
                                            </div>
                                            <div class="col-sm-9">  
                                                <textarea disabled class="ays-textarea ays-disable-setting" style="height:320px;"><?php echo esc_textarea($certificate_body_html);?>
                                                </textarea>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <a href="https://ays-pro.com/wordpress/quiz-maker?utm_source=dashboard&utm_medium=quiz-free&utm_campaign=pro-popup-send-certificate-to-user-<?php echo esc_attr( AYS_QUIZ_UTM_VERSION ); ?>" target="_blank" class="ays-quiz-new-upgrade-button-link">
                                    <div class="ays-quiz-new-upgrade-button-box">
                                        <div>
                                            <img loading="lazy" src="<?php echo AYS_QUIZ_ADMIN_URL.'/images/icons/locked_24x24.svg'?>">
                                            <img loading="lazy" src="<?php echo AYS_QUIZ_ADMIN_URL.'/images/icons/unlocked_24x24.svg'?>" class="ays-quiz-new-upgrade-button-hover">
                                        </div>
                                        <div class="ays-quiz-new-upgrade-button"><?php echo __("Upgrade", "quiz-maker"); ?></div>
                                    </div>
                                </a>
                                <div class="ays-quiz-new-watch-video-button-box">
                                    <div>
                                        <img loading="lazy" src="<?php echo AYS_QUIZ_ADMIN_URL.'/images/icons/video_24x24.svg'?>">
                                        <img loading="lazy" src="<?php echo AYS_QUIZ_ADMIN_URL.'/images/icons/video_24x24_hover.svg'?>" class="ays-quiz-new-watch-video-button-hover">
                                    </div>
                                    <div class="ays-quiz-new-watch-video-button"><?php echo __("Watch Video", "quiz-maker"); ?></div>
                                </div>
                                <div class="ays-quiz-center-big-main-button-box ays-quiz-new-big-button-flex">
                                    <div class="ays-quiz-center-big-watch-video-button-box ays-quiz-big-upgrade-margin-right-10">
                                        <div class="ays-quiz-center-new-watch-video-demo-button">
                                            <img loading="lazy" src="<?php echo AYS_QUIZ_ADMIN_URL.'/images/icons/video_24x24.svg'?>" class="ays-quiz-new-button-img-hide">
                                            <img loading="lazy" src="<?php echo AYS_QUIZ_ADMIN_URL.'/images/icons/video_24x24_hover.svg'?>" class="ays-quiz-new-watch-video-button-hover">
                                            <?php echo __("Watch Video", "quiz-maker"); ?>
                                        </div>
                                    </div>
                                    <div class="ays-quiz-center-big-upgrade-button-box">
                                        <a href="https://ays-pro.com/wordpress/quiz-maker?utm_source=dashboard&utm_medium=quiz-free&utm_campaign=pro-popup-send-certificate-to-user-<?php echo esc_attr( AYS_QUIZ_UTM_VERSION ); ?>" target="_blank" class="ays-quiz-new-upgrade-button-link">
                                            <div class="ays-quiz-center-new-big-upgrade-button">
                                                <img loading="lazy" src="<?php echo AYS_QUIZ_ADMIN_URL.'/images/icons/locked_24x24.svg'?>" class="ays-quiz-new-button-img-hide">
                                                <img loading="lazy" src="<?php echo AYS_QUIZ_ADMIN_URL.'/images/icons/unlocked_24x24.svg'?>" class="ays-quiz-new-upgrade-button-hover">  
                                                <?php echo __("Upgrade", "quiz-maker"); ?>
                                            </div>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <hr>
                        <div class="form-group row">
                            <div class="col-sm-12 only_pro">
                                <div class="pro_features pro_features_popup">
                                    <div class="pro-features-popup-conteiner">
                                        <div class="pro-features-popup-title">
                                            <?php echo __("Send email to admin", 'quiz-maker'); ?>
                                        </div>
                                        <div class="pro-features-popup-content" data-link="https://youtu.be/YUGTj9zJVeE">
                                            <p>
                                                <?php echo sprintf( __("The Quiz Maker plugin gives the opportunity not only to send the email to the users after the quiz completion but also to %s the admin. %s", 'quiz-maker'),
                                                    "<strong>",
                                                    "</strong>"
                                                ); ?>
                                            </p>
                                            <p>
                                                <?php echo sprintf( __("By enabling this option, you can send a %s Certificate and results report %s to the admin. You can set a passing score so that the admin can receive the email only if the user has passed the minimum required score.", 'quiz-maker'),
                                                    "<strong>",
                                                    "</strong>"
                                                ); ?>
                                            </p>
                                            <p>
                                                <?php echo sprintf( __("Also, you can %s fully customize %s the Email Message option, by inserting your desired %s message variables %s in the WP Editor from the %s General Settings %s page.", 'quiz-maker'),
                                                    "<strong>",
                                                    "</strong>",
                                                    "<strong>",
                                                    "</strong>",
                                                    "<strong>",
                                                    "</strong>"
                                                ); ?>
                                            </p>
                                            <div>
                                                <a href="https://quiz-plugin.com/docs/" target="_blank"><?php echo __("See Documentation", 'quiz-maker'); ?></a>
                                            </div>
                                        </div>
                                        <div class="pro-features-popup-button" data-link="https://ays-pro.com/wordpress/quiz-maker?utm_source=dashboard&utm_medium=quiz-free&utm_campaign=pro-popup-send-mail-to-admin-<?php echo esc_attr( AYS_QUIZ_UTM_VERSION ); ?>">
                                            <?php echo __("Pricing", 'quiz-maker'); ?>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <div class="col-sm-3">
                                        <label class="ays-disable-setting"><?php echo __('Send Mail To Admin','quiz-maker')?></label>
                                    </div>
                                    <div class="col-sm-1">
                                        <input type="checkbox" class="ays-enable-timerl" id="ays_enable_mail_admin" value="on"/>
                                    </div>
                                    <div class="col-sm-8 ays_divider_left">
                                        <div class="form-group row">
                                            <div class="col-sm-3">
                                                <label for="ays_enable_send_mail_to_admin_by_pass_score">
                                                    <?php echo __('Pass score (%)', 'quiz-maker'); ?>
                                                    <a  class="ays_help" data-toggle="tooltip" title="<?php echo __('If the option is enabled, then the admin will receive the email only if the user has passed the minimum score required. It will take the value of the general pass score of the quiz. Please specify it in the Result Settings tab.','quiz-maker'); ?>">
                                                        <i class=""></i>
                                                    </a>
                                                </label>
                                            </div>
                                            <div class="col-sm-9">
                                                <input type="checkbox" class="ays-enable-timerl" />
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <div class="col-sm-12">
                                                <blockquote><?php echo __( 'Tick the checkbox, and admin will receive the email only if the user has passed the minimum score required.', 'quiz-maker' ); ?></blockquote>
                                            </div>
                                        </div>
                                        <hr>
                                        <!-- ................ -->
                                        <div class="form-group row">
                                            <div class="col-sm-3">
                                                <label for="ays_send_mail_to_site_admin">
                                                    <?php echo __('Admin', 'quiz-maker')?>
                                                    <a  class="ays_help" data-toggle="tooltip" title="<?php echo __('Disable this feature, if you want to make it possible not to send emails to the registered Mail of the site Admin, but only to additional emails.','quiz-maker')?>">
                                                    </a>
                                                </label>
                                            </div>
                                            <div class="col-sm-1">
                                                <input type="checkbox" class="ays-enable-timerl" id="ays_send_mail_to_site_admin" value="on" />
                                            </div>
                                            <div class="col-sm-8">
                                                <input type="text" class="ays-text-input ays-enable-timerl" value="example@gmail.com" disabled />
                                            </div>
                                        </div>
                                        <hr/>
                                        <!-- ................ -->
                                        <div class="form-group row">
                                            <div class="col-sm-3">
                                                <label for="ays_additional_emails">
                                                    <?php echo __('Additional Emails','quiz-maker')?>
                                                </label>
                                            </div>
                                            <div class="col-sm-9">
                                                <input type="text" class="ays-text-input" id="ays_additional_emails" value="example1@gmail.com, example2@gmail.com, ..."/>
                                            </div>
                                        </div>

                                        <hr>
                                        <div class="form-group row">
                                            <div class="col-sm-5">
                                                <label for="ays_send_results_admin">
                                                    <?php echo __('Send Report table to Admin','quiz-maker')?>
                                                    <a  class="ays_help" data-toggle="tooltip" title="<?php echo __('You can send results to the admin after the quiz is completed','quiz-maker')?>">
                                                    </a>
                                                </label>
                                            </div>
                                            <div class="col-sm-7">
                                                <input type="checkbox" class="ays-enable-timerl" id="ays_send_results_admin"
                                                       value="on" />
                                            </div>
                                        </div>
                                        <hr/>
                                        <div class="form-group row">
                                            <div class="col-sm-5">
                                                <label for="ays_send_interval_msg_to_admin">
                                                    <?php echo __('Send Interval message to Admin','quiz-maker')?>
                                                    <a  class="ays_help" data-toggle="tooltip" title="<?php echo __('If this option is enabled then the admin will get the Email with Interval message.','quiz-maker')?>">
                                                    </a>
                                                </label>
                                            </div>
                                            <div class="col-sm-7">
                                                <input type="checkbox" class="ays-enable-timerl" id="ays_send_interval_msg_to_admin"
                                                       value="on" />
                                            </div>
                                        </div>
                                        <hr/>
                                        <div class="form-group row">
                                            <div class="col-sm-5">
                                                <label for="ays_send_certificate_to_admin">
                                                    <?php echo __('Send Certificate to Admin too','quiz-maker')?>
                                                    <a  class="ays_help" data-toggle="tooltip" title="<?php echo htmlentities(__('If this option is enabled then the admin will get the Email with an attached PDF file that gets the user. If the "Send Certificate To User" option is disabled admin does not get a certificate too.','quiz-maker')); ?>">
                                                    </a>
                                                </label>
                                            </div>
                                            <div class="col-sm-7">
                                                <input type="checkbox" class="ays-enable-timerl" id="ays_send_certificate_to_admin"
                                                       value="on" />
                                            </div>
                                        </div>
                                        <hr>
                                        <div class="form-group row">
                                            <div class="col-sm-3">
                                                <label for="ays_mail_message_admin">
                                                    <?php echo __('Mail message','quiz-maker')?>
                                                    <a  class="ays_help" data-toggle="tooltip" title="<?php echo __('Provide the message text for sending to the Admin by email. You can use Variables from General Settings page to insert data. (name, score, date etc.)','quiz-maker')?>">
                                                    </a>
                                                </label>
                                            </div>
                                            <div class="col-sm-9">
                                                <textarea type="text" id="ays_mail_message_admin" class="ays-textarea ays-disable-setting" disabled=""></textarea>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <hr/>
                                <div class="form-group row">
                                    <div class="col-sm-4">
                                        <label>
                                            <?php echo __('Email Configuration','quiz-maker')?>
                                        </label>
                                    </div>
                                    <div class="col-sm-8 ays_divider_left">
                                        <div class="form-group row">
                                            <div class="col-sm-3">
                                                <label for="ays_email_configuration_from_email">
                                                    <?php echo __('From Email','quiz-maker')?>
                                                </label>
                                            </div>
                                            <div class="col-sm-9">
                                                <input type="text" class="ays-text-input" id="ays_email_configuration_from_email"/>
                                            </div>
                                        </div>
                                        <hr/>
                                        <div class="form-group row">
                                            <div class="col-sm-3">
                                                <label for="ays_email_configuration_from_name">
                                                    <?php echo __('From Name','quiz-maker')?>
                                                </label>
                                            </div>
                                            <div class="col-sm-9">
                                                <input type="text" class="ays-text-input" id="ays_email_configuration_from_name"/>
                                            </div>
                                        </div>
                                        <hr/>
                                        <div class="form-group row">
                                            <div class="col-sm-3">
                                                <label for="ays_email_configuration_from_subject">
                                                    <?php echo __('From Subject','quiz-maker')?>
                                                </label>
                                            </div>
                                            <div class="col-sm-9">
                                                <input type="text" class="ays-text-input" id="ays_email_configuration_from_subject"/>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <a href="https://ays-pro.com/wordpress/quiz-maker?utm_source=dashboard&utm_medium=quiz-free&utm_campaign=pro-popup-send-mail-to-admin-<?php echo esc_attr( AYS_QUIZ_UTM_VERSION ); ?>" target="_blank" class="ays-quiz-new-upgrade-button-link">
                                    <div class="ays-quiz-new-upgrade-button-box">
                                        <div>
                                            <img loading="lazy" src="<?php echo AYS_QUIZ_ADMIN_URL.'/images/icons/locked_24x24.svg'?>">
                                            <img loading="lazy" src="<?php echo AYS_QUIZ_ADMIN_URL.'/images/icons/unlocked_24x24.svg'?>" class="ays-quiz-new-upgrade-button-hover">
                                        </div>
                                        <div class="ays-quiz-new-upgrade-button"><?php echo __("Upgrade", "quiz-maker"); ?></div>
                                    </div>
                                </a>
                                <div class="ays-quiz-new-watch-video-button-box">
                                    <div>
                                        <img loading="lazy" src="<?php echo AYS_QUIZ_ADMIN_URL.'/images/icons/video_24x24.svg'?>">
                                        <img loading="lazy" src="<?php echo AYS_QUIZ_ADMIN_URL.'/images/icons/video_24x24_hover.svg'?>" class="ays-quiz-new-watch-video-button-hover">
                                    </div>
                                    <div class="ays-quiz-new-watch-video-button"><?php echo __("Watch Video", "quiz-maker"); ?></div>
                                </div>
                                <div class="ays-quiz-center-big-main-button-box ays-quiz-new-big-button-flex">
                                    <div class="ays-quiz-center-big-watch-video-button-box ays-quiz-big-upgrade-margin-right-10">
                                        <div class="ays-quiz-center-new-watch-video-demo-button">
                                            <img loading="lazy" src="<?php echo AYS_QUIZ_ADMIN_URL.'/images/icons/video_24x24.svg'?>" class="ays-quiz-new-button-img-hide">
                                            <img loading="lazy" src="<?php echo AYS_QUIZ_ADMIN_URL.'/images/icons/video_24x24_hover.svg'?>" class="ays-quiz-new-watch-video-button-hover">
                                            <?php echo __("Watch Video", "quiz-maker"); ?>
                                        </div>
                                    </div>
                                    <div class="ays-quiz-center-big-upgrade-button-box">
                                        <a href="https://ays-pro.com/wordpress/quiz-maker?utm_source=dashboard&utm_medium=quiz-free&utm_campaign=pro-popup-send-mail-to-admin-<?php echo esc_attr( AYS_QUIZ_UTM_VERSION ); ?>" target="_blank" class="ays-quiz-new-upgrade-button-link">
                                            <div class="ays-quiz-center-new-big-upgrade-button">
                                                <img loading="lazy" src="<?php echo AYS_QUIZ_ADMIN_URL.'/images/icons/locked_24x24.svg'?>" class="ays-quiz-new-button-img-hide">
                                                <img loading="lazy" src="<?php echo AYS_QUIZ_ADMIN_URL.'/images/icons/unlocked_24x24.svg'?>" class="ays-quiz-new-upgrade-button-hover">  
                                                <?php echo __("Upgrade", "quiz-maker"); ?>
                                            </div>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div id="tab8" class="ays-integrations-tab ays-quiz-tab-content <?php echo ($ays_quiz_tab == 'tab8') ? 'ays-quiz-tab-content-active' : ''; ?>">
                <div class="ays-quiz-top-actions-container-wrapper form-group row">
                    <div class="col-sm-12">
                        <p class="m-0 text-right">
                            <a class="ays-quiz-collapse-all" href="javascript:void(0);"><?php echo esc_html__( "Collapse All", "quiz-maker" ); ?></a>
                            <span>|</span>
                            <a class="ays-quiz-expand-all" href="javascript:void(0);"><?php echo esc_html__( "Expand All", "quiz-maker" ); ?></a>
                        </p>
                    </div>
                </div>
                <div class="ays-quiz-accordion-options-main-container" data-collapsed="false">
                    <div class="ays-quiz-accordion-container">
                        <?php echo $quiz_accordion_svg_html; ?>
                        <p class="ays-subtitle"><?php echo esc_html__('Integrations settings','quiz-maker'); ?></p>
                    </div>
                    <hr class="ays-quiz-bolder-hr"/>
                    <div class="ays-quiz-accordion-options-box">
                        <fieldset>
                            <legend>
                                <img loading="lazy" class="ays_integration_logo" src="<?php echo esc_url(AYS_QUIZ_ADMIN_URL); ?>/images/integrations/mailchimp_logo.png" alt="">
                                <h5><?php echo esc_html__('MailChimp Settings','quiz-maker')?></h5>
                            </legend>
                            <div class="form-group row">
                                <div class="col-sm-12">
                                    <div class="ays-quiz-heading-box ays-quiz-unset-float ays-quiz-unset-margin">
                                        <div class="ays-quiz-wordpress-user-manual-box ays-quiz-wordpress-text-align">
                                            <a href="https://www.youtube.com/watch?v=joPQrsF0a60" target="_blank">
                                                <?php echo esc_html__("How to integrate MailChimp - video", 'quiz-maker'); ?>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-12 only_pro ays-quiz-margin-top-20" style="padding:20px;">
                                    <div class="pro_features" style="justify-content:flex-end;">
                                        
                                    </div>
                                    <div class="form-group row">
                                        <div class="col-sm-4">
                                            <label for="ays_enable_mailchimp">
                                                <?php echo esc_html__('Enable MailChimp','quiz-maker')?>
                                            </label>
                                        </div>
                                        <div class="col-sm-1">
                                            <input type="checkbox" class="ays-enable-timer1" id="ays_enable_mailchimp"/>
                                        </div>
                                    </div>
                                    <hr>
                                    <div class="form-group row">
                                        <div class="col-sm-4">
                                            <label for="ays_mailchimp_list">
                                                <?php echo esc_html__('MailChimp list','quiz-maker')?>
                                            </label>
                                        </div>
                                        <div class="col-sm-8">
                                            <select id="ays_mailchimp_list" class="ays-text-input ays-text-input-short">
                                                <option value="" disabled selected>Select list</option>
                                            </select>
                                        </div>
                                    </div>
                                    <hr>
                                    <div class="form-group row">
                                        <div class="col-sm-4">
                                            <label for="ays_enable_double_opt_in">
                                                <?php echo esc_html__('Enable double opt-in','quiz-maker')?>
                                            </label>
                                        </div>
                                        <div class="col-sm-8">
                                            <input type="checkbox" class="ays-enable-timer1"/>
                                            <span class="ays_option_description"><?php echo esc_html__( 'Send contacts an opt-in confirmation email when their email address added to the list.', 'quiz-maker' ); ?></span>
                                        </div>
                                    </div>
                                    <a href="https://ays-pro.com/wordpress/quiz-maker?utm_source=dashboard&utm_medium=quiz-free&utm_campaign=quiz-edit-page-mailchimp-<?php echo esc_attr( AYS_QUIZ_UTM_VERSION ); ?>" target="_blank" class="ays-quiz-new-upgrade-button-link">
                                        <div class="ays-quiz-new-upgrade-button-box">
                                            <div>
                                                <img loading="lazy" src="<?php echo esc_url(AYS_QUIZ_ADMIN_URL.'/images/icons/locked_24x24.svg'); ?>">
                                                <img loading="lazy" src="<?php echo esc_url(AYS_QUIZ_ADMIN_URL.'/images/icons/unlocked_24x24.svg'); ?>" class="ays-quiz-new-upgrade-button-hover">
                                            </div>
                                            <div class="ays-quiz-new-upgrade-button"><?php echo esc_html__("Upgrade", "quiz-maker"); ?></div>
                                        </div>
                                    </a>
                                </div>
                            </div>
                        </fieldset> <!-- MailChimp Settings -->
                        <hr/>
                        <fieldset>
                            <legend>
                                <img loading="lazy" class="ays_integration_logo" src="<?php echo esc_url(AYS_QUIZ_ADMIN_URL); ?>/images/integrations/paypal_logo.png" alt="">
                                <h5><?php echo esc_html__('PayPal Settings','quiz-maker')?></h5>
                            </legend>                    
                            <div class="form-group row">
                                <div class="col-sm-12">
                                    <div class="ays-quiz-heading-box ays-quiz-unset-float ays-quiz-unset-margin">
                                        <div class="ays-quiz-wordpress-user-manual-box ays-quiz-wordpress-text-align">
                                            <a href="https://www.youtube.com/watch?v=IwT-2d9OE1g" target="_blank">
                                                <?php echo esc_html__("How to integrate PayPal - video", 'quiz-maker'); ?>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-12 only_pro ays-quiz-margin-top-20" style="padding:20px;">
                                    <div class="pro_features" style="justify-content:flex-end;">
                                        
                                    </div>
                                    <div class="form-group row">
                                        <div class="col-sm-4">
                                            <label for="ays_enable_paypal">
                                                <?php echo esc_html__('Enable PayPal','quiz-maker')?>
                                            </label>
                                        </div>
                                        <div class="col-sm-1">
                                            <input type="checkbox" class="ays-enable-timer1" id="ays_enable_paypal" value="on"/>
                                        </div>
                                    </div>
                                    <hr>
                                    <div class="form-group row">
                                        <div class="col-sm-4">
                                            <label for="ays_paypal_amount">
                                                <?php echo esc_html__('Amount','quiz-maker')?>
                                            </label>
                                        </div>
                                        <div class="col-sm-8">
                                            <input type="text" class="ays-text-input ays-text-input-short" id="ays_paypal_amount" value="20"/>
                                        </div>
                                    </div>
                                    <hr>
                                    <div class="form-group row">
                                        <div class="col-sm-4">
                                            <label for="ays_paypal_currency">
                                                <?php echo esc_html__('Currency','quiz-maker')?>
                                            </label>
                                        </div>
                                        <div class="col-sm-8">
                                            <select id="ays_paypal_currency" class="ays-text-input ays-text-input-short">
                                                <option>USD - <?php echo esc_html__( 'United States Dollar', 'quiz-maker' ); ?></option>
                                                <option>EUR - <?php echo esc_html__( 'Euro', 'quiz-maker' ); ?></option>
                                                <option>GBP - <?php echo esc_html__( 'British Pound Sterling', 'quiz-maker' ); ?></option>
                                                <option>AUD - <?php echo esc_html__( 'Australian dollar', 'quiz-maker' ); ?></option>
                                                <option>CHF - <?php echo esc_html__( 'Swiss Franc', 'quiz-maker' ); ?></option>
                                                <option>JPY - <?php echo esc_html__( 'Japanese Yen', 'quiz-maker' ); ?></option>
                                                <option>INR - <?php echo esc_html__( 'Indian Rupee', 'quiz-maker' ); ?></option>
                                                <option>CNY - <?php echo esc_html__( 'Chinese Yuan', 'quiz-maker' ); ?></option>
                                                <option>CAD - <?php echo esc_html__( 'Canadian Dollar', 'quiz-maker' ); ?></option>
                                                <option>AED - <?php echo esc_html__( 'United Arab Emirates Dirham', 'quiz-maker' ); ?></option>
                                                <option>RUB - <?php echo esc_html__( 'Russian Ruble', 'quiz-maker' ); ?></option>
                                            </select>
                                        </div>
                                    </div>
                                    <hr>
                                    <div class="form-group row">
                                        <div class="col-sm-4">
                                            <label for="ays_stripe_currency">
                                                <?php echo esc_html__('Payment details','quiz-maker'); ?>
                                            </label>
                                        </div>
                                        <div class="col-sm-8">
                                            <textarea type="text" class="ays-textarea ays-disable-setting" disabled></textarea>
                                        </div>
                                    </div>
                                    <a href="https://ays-pro.com/wordpress/quiz-maker?utm_source=dashboard&utm_medium=quiz-free&utm_campaign=quiz-edit-page-paypal-<?php echo esc_attr( AYS_QUIZ_UTM_VERSION ); ?>" target="_blank" class="ays-quiz-new-upgrade-button-link">
                                        <div class="ays-quiz-new-upgrade-button-box">
                                            <div>
                                                <img loading="lazy" src="<?php echo esc_url(AYS_QUIZ_ADMIN_URL.'/images/icons/locked_24x24.svg'); ?>">
                                                <img loading="lazy" src="<?php echo esc_url(AYS_QUIZ_ADMIN_URL.'/images/icons/unlocked_24x24.svg'); ?>" class="ays-quiz-new-upgrade-button-hover">
                                            </div>
                                            <div class="ays-quiz-new-upgrade-button"><?php echo esc_html__("Upgrade to Developer/Agency", "quiz-maker"); ?></div>
                                        </div>
                                    </a>
                                </div>
                            </div>
                        </fieldset> <!-- PayPal Settings -->
                        <hr/>
                        <fieldset>
                            <legend>
                                <img loading="lazy" class="ays_integration_logo" src="<?php echo esc_url(AYS_QUIZ_ADMIN_URL); ?>/images/integrations/stripe_logo.png" alt="">
                                <h5><?php echo esc_html__('Stripe Settings','quiz-maker')?></h5>
                            </legend>
                            <div class="col-sm-12 only_pro" style="padding:20px;">
                                <div class="pro_features" style="justify-content:flex-end;">

                                </div>
                                <div class="form-group row">
                                    <div class="col-sm-4">
                                        <label for="ays_enable_stripe">
                                            <?php echo esc_html__('Enable Stripe','quiz-maker'); ?>
                                        </label>
                                    </div>
                                    <div class="col-sm-1">
                                        <input type="checkbox" class="ays-enable-timer1" />
                                    </div>
                                </div>
                                <hr>
                                <div class="form-group row">
                                    <div class="col-sm-4">
                                        <label for="ays_stripe_amount">
                                            <?php echo esc_html__('Amount','quiz-maker')?>
                                        </label>
                                    </div>
                                    <div class="col-sm-8">
                                        <input type="text" class="ays-text-input ays-text-input-short" />
                                    </div>
                                </div>
                                <hr>
                                <div class="form-group row">
                                    <div class="col-sm-4">
                                        <label for="ays_stripe_currency">
                                            <?php echo esc_html__('Currency','quiz-maker')?>
                                        </label>
                                    </div>
                                    <div class="col-sm-8">
                                        <select class="ays-text-input ays-text-input-short">
                                            <option>USD - <?php echo esc_html__( 'United States Dollar', 'quiz-maker' ); ?></option>
                                            <option>EUR - <?php echo esc_html__( 'Euro', 'quiz-maker' ); ?></option>
                                            <option>GBP - <?php echo esc_html__( 'British Pound Sterling', 'quiz-maker' ); ?></option>
                                            <option>AUD - <?php echo esc_html__( 'Australian dollar', 'quiz-maker' ); ?></option>
                                            <option>CHF - <?php echo esc_html__( 'Swiss Franc', 'quiz-maker' ); ?></option>
                                            <option>JPY - <?php echo esc_html__( 'Japanese Yen', 'quiz-maker' ); ?></option>
                                            <option>INR - <?php echo esc_html__( 'Indian Rupee', 'quiz-maker' ); ?></option>
                                            <option>CNY - <?php echo esc_html__( 'Chinese Yuan', 'quiz-maker' ); ?></option>
                                            <option>CAD - <?php echo esc_html__( 'Canadian Dollar', 'quiz-maker' ); ?></option>
                                            <option>AED - <?php echo esc_html__( 'United Arab Emirates Dirham', 'quiz-maker' ); ?></option>
                                            <option>RUB - <?php echo esc_html__( 'Russian Ruble', 'quiz-maker' ); ?></option>
                                        </select>
                                    </div>
                                </div>
                                <hr>
                                <div class="form-group row">
                                    <div class="col-sm-4">
                                        <label for="ays_stripe_currency">
                                            <?php echo esc_html__('Payment details','quiz-maker'); ?>
                                        </label>
                                    </div>
                                    <div class="col-sm-8">
                                        <textarea type="text" class="ays-textarea ays-disable-setting" disabled></textarea>
                                    </div>
                                </div>
                                <a href="https://ays-pro.com/wordpress/quiz-maker?utm_source=dashboard&utm_medium=quiz-free&utm_campaign=quiz-edit-page-stripe-<?php echo esc_attr( AYS_QUIZ_UTM_VERSION ); ?>" target="_blank" class="ays-quiz-new-upgrade-button-link">
                                    <div class="ays-quiz-new-upgrade-button-box">
                                        <div>
                                            <img loading="lazy" src="<?php echo esc_url(AYS_QUIZ_ADMIN_URL.'/images/icons/locked_24x24.svg'); ?>">
                                            <img loading="lazy" src="<?php echo esc_url(AYS_QUIZ_ADMIN_URL.'/images/icons/unlocked_24x24.svg'); ?>" class="ays-quiz-new-upgrade-button-hover">
                                        </div>
                                        <div class="ays-quiz-new-upgrade-button"><?php echo esc_html__("Upgrade to Developer/Agency", "quiz-maker"); ?></div>
                                    </div>
                                </a>
                            </div>
                        </fieldset> <!-- Stripe Settings -->
                        <hr/>
                        <fieldset>
                            <legend>
                                <img loading="lazy" class="ays_integration_logo" src="<?php echo esc_url(AYS_QUIZ_ADMIN_URL); ?>/images/integrations/recaptcha_logo.png" alt="">
                                <h5><?php echo esc_html__('reCAPTCHA Settings','quiz-maker')?></h5>
                            </legend>
                            <div class="col-sm-12 only_pro" style="padding:20px;">
                                <div class="pro_features" style="justify-content:flex-end;">
                                    
                                </div>
                                <div class="form-group row">
                                    <div class="col-sm-4">
                                        <label for="ays_quiz_enable_recaptcha"><?php echo esc_html__('Enable reCAPTCHA', 'quiz-maker') ?></label>
                                    </div>
                                    <div class="col-sm-1">
                                        <input type="checkbox" class="ays-enable-timer1"/>
                                    </div>
                                </div>
                                <a href="https://ays-pro.com/wordpress/quiz-maker?utm_source=dashboard&utm_medium=quiz-free&utm_campaign=quiz-edit-page-recaptcha-<?php echo esc_attr( AYS_QUIZ_UTM_VERSION ); ?>" target="_blank" class="ays-quiz-new-upgrade-button-link">
                                    <div class="ays-quiz-new-upgrade-button-box">
                                        <div>
                                            <img loading="lazy" src="<?php echo esc_url(AYS_QUIZ_ADMIN_URL.'/images/icons/locked_24x24.svg'); ?>">
                                            <img loading="lazy" src="<?php echo esc_url(AYS_QUIZ_ADMIN_URL.'/images/icons/unlocked_24x24.svg'); ?>" class="ays-quiz-new-upgrade-button-hover">
                                        </div>
                                        <div class="ays-quiz-new-upgrade-button"><?php echo esc_html__("Upgrade to Developer/Agency", "quiz-maker"); ?></div>
                                    </div>
                                </a>
                            </div>
                        </fieldset> <!-- Stripe Settings -->
                        <hr/>
                        <fieldset>
                            <legend>                        
                                <img loading="lazy" class="ays_integration_logo" src="<?php echo esc_url(AYS_QUIZ_ADMIN_URL); ?>/images/integrations/campaignmonitor_logo.png" alt="">
                                <h5><?php echo esc_html__('Campaign Monitor Settings', 'quiz-maker') ?></h5>
                            </legend>
                            <div class="form-group row">
                                <div class="col-sm-12 only_pro" style="padding:20px;">
                                    <div class="pro_features" style="justify-content:flex-end;">
                                        
                                    </div>
                                    <div class="form-group row">
                                        <div class="col-sm-4">
                                            <label for="ays_enable_monitor">
                                                <?php echo esc_html__('Enable Campaign Monitor', 'quiz-maker') ?>
                                            </label>
                                        </div>
                                        <div class="col-sm-1">
                                            <input type="checkbox" class="ays-enable-timer1" id="ays_enable_monitor"/>
                                        </div>
                                    </div>
                                    <hr>
                                    <div class="form-group row">
                                        <div class="col-sm-4">
                                            <label for="ays_monitor_list">
                                                <?php echo esc_html__('Campaign Monitor list', 'quiz-maker') ?>
                                            </label>
                                        </div>
                                        <div class="col-sm-8">
                                            <select id="ays_monitor_list" class="ays-text-input ays-text-input-short">
                                                <option value="" disabled selected><?php echo esc_html(__("Select List", 'quiz-maker')); ?></option>
                                            </select>
                                        </div>
                                    </div>
                                    <a href="https://ays-pro.com/wordpress/quiz-maker?utm_source=dashboard&utm_medium=quiz-free&utm_campaign=quiz-edit-page-campaign-monitor-<?php echo esc_attr( AYS_QUIZ_UTM_VERSION ); ?>" target="_blank" class="ays-quiz-new-upgrade-button-link">
                                        <div class="ays-quiz-new-upgrade-button-box">
                                            <div>
                                                <img loading="lazy" src="<?php echo esc_url(AYS_QUIZ_ADMIN_URL.'/images/icons/locked_24x24.svg'); ?>">
                                                <img loading="lazy" src="<?php echo esc_url(AYS_QUIZ_ADMIN_URL.'/images/icons/unlocked_24x24.svg'); ?>" class="ays-quiz-new-upgrade-button-hover">
                                            </div>
                                            <div class="ays-quiz-new-upgrade-button"><?php echo esc_html__("Upgrade", "quiz-maker"); ?></div>
                                        </div>
                                    </a>
                                </div>
                            </div>
                        </fieldset> <!-- Campaign Monitor Settings -->
                        <hr/>                
                        <fieldset>
                            <legend>
                                <img loading="lazy" class="ays_integration_logo" src="<?php echo esc_url(AYS_QUIZ_ADMIN_URL); ?>/images/integrations/zapier_logo.png" alt="">
                                <h5><?php echo esc_html__('Zapier Integration Settings', 'quiz-maker') ?></h5>
                            </legend>
                            <div class="form-group row">
                                <div class="col-sm-12 only_pro" style="padding:20px;">
                                    <div class="pro_features" style="justify-content:flex-end;">
           
                                    </div>
                                    <div class="form-group row">
                                        <div class="col-sm-4">
                                            <label for="ays_enable_zapier">
                                                <?php echo esc_html__('Enable Zapier Integration', 'quiz-maker') ?>
                                            </label>
                                        </div>
                                        <div class="col-sm-1">
                                            <input type="checkbox" class="ays-enable-timer1" id="ays_enable_zapier"/>
                                        </div>
                                        <div class="col-sm-3">
                                            <button type="button" id="testZapier" class="btn btn-outline-secondary">
                                                <?php echo esc_html__("Send test data", 'quiz-maker') ?>
                                            </button>
                                            <a class="ays_help" data-toggle="tooltip" style="font-size: 16px;"
                                               title="<?php echo esc_attr( __('We will send you a test data, and you can catch it in your ZAP for configure it.', 'quiz-maker') ); ?>">
                                                <i class="ays_fa ays_fa_info_circle"></i>
                                            </a>
                                        </div>
                                    </div>
                                    <div id="testZapierFields" class="d-none">
                                        <input type="checkbox"/>
                                        <input type="checkbox"/>
                                        <input type="checkbox"/>
                                    </div>
                                    <a href="https://ays-pro.com/wordpress/quiz-maker?utm_source=dashboard&utm_medium=quiz-free&utm_campaign=quiz-edit-page-zapier-<?php echo esc_attr( AYS_QUIZ_UTM_VERSION ); ?>" target="_blank" class="ays-quiz-new-upgrade-button-link">
                                        <div class="ays-quiz-new-upgrade-button-box">
                                            <div>
                                                <img loading="lazy" src="<?php echo esc_url(AYS_QUIZ_ADMIN_URL.'/images/icons/locked_24x24.svg'); ?>">
                                                <img loading="lazy" src="<?php echo esc_url(AYS_QUIZ_ADMIN_URL.'/images/icons/unlocked_24x24.svg'); ?>" class="ays-quiz-new-upgrade-button-hover">
                                            </div>
                                            <div class="ays-quiz-new-upgrade-button"><?php echo esc_html__("Upgrade", "quiz-maker"); ?></div>
                                        </div>
                                    </a>
                                </div>
                            </div>
                        </fieldset> <!-- Zapier Integration Settings -->
                        <hr/>                
                        <fieldset>
                            <legend>
                                <img loading="lazy" class="ays_integration_logo" src="<?php echo esc_url(AYS_QUIZ_ADMIN_URL); ?>/images/integrations/activecampaign_logo.png" alt="">
                                <h5><?php echo esc_html__('ActiveCampaign Settings', 'quiz-maker') ?></h5>
                            </legend>
                            <div class="form-group row">
                                <div class="col-sm-12 only_pro" style="padding:20px;">
                                    <div class="pro_features" style="justify-content:flex-end;">

                                    </div>
                                    <div class="form-group row">
                                        <div class="col-sm-4">
                                            <label for="ays_enable_active_camp">
                                                <?php echo esc_html__('Enable ActiveCampaign', 'quiz-maker') ?>
                                            </label>
                                        </div>
                                        <div class="col-sm-1">
                                            <input type="checkbox" class="ays-enable-timer1" id="ays_enable_active_camp"/>
                                        </div>
                                    </div>
                                    <hr>
                                    <div class="form-group row">
                                        <div class="col-sm-4">
                                            <label for="ays_active_camp_list">
                                                <?php echo esc_html__('ActiveCampaign list', 'quiz-maker') ?>
                                            </label>
                                        </div>
                                        <div class="col-sm-8">
                                            <select id="ays_active_camp_list" class="ays-text-input ays-text-input-short">
                                                <option value="" disabled selected><?php echo esc_html__("Select List", 'quiz-maker'); ?></option>
                                                <option value=""><?php echo esc_html__("Just create contact", 'quiz-maker'); ?></option>
                                            </select>
                                        </div>
                                    </div>
                                    <hr>
                                    <div class="form-group row">
                                        <div class="col-sm-4">
                                            <label for="ays_active_camp_automation">
                                                <?php echo esc_html__('ActiveCampaign automation', 'quiz-maker'); ?>
                                            </label>
                                        </div>
                                        <div class="col-sm-8">
                                            <select id="ays_active_camp_automation" class="ays-text-input ays-text-input-short">
                                                <option value="" disabled selected><?php echo esc_html__("Select List", 'quiz-maker'); ?></option>
                                                <option value=""><?php echo esc_html__("Just create contact", 'quiz-maker'); ?></option>
                                            </select>
                                        </div>
                                    </div>
                                    <a href="https://ays-pro.com/wordpress/quiz-maker?utm_source=dashboard&utm_medium=quiz-free&utm_campaign=quiz-edit-page-activecampaign-<?php echo esc_attr( AYS_QUIZ_UTM_VERSION ); ?>" target="_blank" class="ays-quiz-new-upgrade-button-link">
                                        <div class="ays-quiz-new-upgrade-button-box">
                                            <div>
                                                <img loading="lazy" src="<?php echo esc_url(AYS_QUIZ_ADMIN_URL.'/images/icons/locked_24x24.svg'); ?>">
                                                <img loading="lazy" src="<?php echo esc_url(AYS_QUIZ_ADMIN_URL.'/images/icons/unlocked_24x24.svg'); ?>" class="ays-quiz-new-upgrade-button-hover">
                                            </div>
                                            <div class="ays-quiz-new-upgrade-button"><?php echo esc_html__("Upgrade", "quiz-maker"); ?></div>
                                        </div>
                                    </a>
                                </div>
                            </div>
                        </fieldset> <!-- ActiveCampaign Settings -->
                        <hr/>
                        <fieldset>
                            <legend>
                                <img loading="lazy" class="ays_integration_logo" src="<?php echo esc_url(AYS_QUIZ_ADMIN_URL); ?>/images/integrations/slack_logo.png" alt="">
                                <h5><?php echo esc_html__('Slack Settings', 'quiz-maker'); ?></h5>
                            </legend>
                            <div class="form-group row">
                                <div class="col-sm-12 only_pro" style="padding:20px;">
                                    <div class="pro_features" style="justify-content:flex-end;">
          
                                    </div>
                                    <div class="form-group row">
                                        <div class="col-sm-4">
                                            <label for="ays_enable_slack">
                                                <?php echo esc_html__('Enable Slack integration', 'quiz-maker'); ?>
                                            </label>
                                        </div>
                                        <div class="col-sm-1">
                                            <input type="checkbox" class="ays-enable-timer1" id="ays_enable_slack"/>
                                        </div>
                                    </div>
                                    <hr>
                                    <div class="form-group row">
                                        <div class="col-sm-4">
                                            <label for="ays_slack_conversation">
                                                <?php echo esc_html__('Slack conversation', 'quiz-maker'); ?>
                                            </label>
                                        </div>
                                        <div class="col-sm-8">
                                            <select id="ays_slack_conversation" class="ays-text-input ays-text-input-short">
                                                <option value="" disabled selected><?php echo esc_html__("Select Channel", 'quiz-maker'); ?></option>
                                            </select>
                                        </div>
                                    </div>
                                    <a href="https://ays-pro.com/wordpress/quiz-maker?utm_source=dashboard&utm_medium=quiz-free&utm_campaign=quiz-edit-page-slack-<?php echo esc_attr( AYS_QUIZ_UTM_VERSION ); ?>" target="_blank" class="ays-quiz-new-upgrade-button-link">
                                        <div class="ays-quiz-new-upgrade-button-box">
                                            <div>
                                                <img loading="lazy" src="<?php echo esc_url(AYS_QUIZ_ADMIN_URL.'/images/icons/locked_24x24.svg'); ?>">
                                                <img loading="lazy" src="<?php echo esc_url(AYS_QUIZ_ADMIN_URL.'/images/icons/unlocked_24x24.svg'); ?>" class="ays-quiz-new-upgrade-button-hover">
                                            </div>
                                            <div class="ays-quiz-new-upgrade-button"><?php echo esc_html__("Upgrade", "quiz-maker"); ?></div>
                                        </div>
                                    </a>
                                </div>
                            </div>
                        </fieldset> <!-- Slack Settings -->
                        <hr/>
                        <fieldset>
                            <legend>
                                <img loading="lazy" class="ays_integration_logo" src="<?php echo esc_url(AYS_QUIZ_ADMIN_URL); ?>/images/integrations/sheets_logo.png" alt="">
                                <h5><?php echo esc_html__('Google Sheet Settings', 'quiz-maker'); ?></h5>
                            </legend>
                            <hr/>
                            <div class="form-group row">
                                <div class="col-sm-12 only_pro" style="padding:20px;">
                                    <div class="pro_features" style="justify-content:flex-end;">

                                    </div>
                                    <div class="form-group row">
                                        <div class="col-sm-4">
                                            <label for="ays_enable_google">
                                                <?php echo esc_html__('Enable Google integration', 'quiz-maker'); ?>
                                            </label>
                                        </div>
                                        <div class="col-sm-1">
                                            <input type="checkbox" class="ays-enable-timer1" id="ays_enable_google" value="on" />
                                        </div>
                                    </div>
                                    <a href="https://ays-pro.com/wordpress/quiz-maker?utm_source=dashboard&utm_medium=quiz-free&utm_campaign=quiz-edit-page-google-sheets-<?php echo esc_attr( AYS_QUIZ_UTM_VERSION ); ?>" target="_blank" class="ays-quiz-new-upgrade-button-link">
                                        <div class="ays-quiz-new-upgrade-button-box">
                                            <div>
                                                <img loading="lazy" src="<?php echo esc_url( AYS_QUIZ_ADMIN_URL.'/images/icons/locked_24x24.svg'); ?>">
                                                <img loading="lazy" src="<?php echo esc_url( AYS_QUIZ_ADMIN_URL.'/images/icons/unlocked_24x24.svg'); ?>" class="ays-quiz-new-upgrade-button-hover">
                                            </div>
                                            <div class="ays-quiz-new-upgrade-button"><?php echo esc_html__("Upgrade", "quiz-maker"); ?></div>
                                        </div>
                                    </a>
                                </div>
                            </div>
                        </fieldset> <!-- Google Sheets -->
                        <hr/>
                        <fieldset>
                            <legend>                        
                                <img loading="lazy" class="ays_integration_logo" src="<?php echo esc_url(AYS_QUIZ_ADMIN_URL); ?>/images/integrations/mad-mimi-logo.png" alt="">
                                <h5><?php echo esc_html__('Mad Mimi Settings', 'quiz-maker'); ?></h5>
                            </legend>
                            <div class="form-group row">
                                <div class="col-sm-12 only_pro" style="padding:20px;">
                                    <div class="pro_features" style="justify-content:flex-end;">

                                    </div>
                                    <div class="form-group row">
                                        <div class="col-sm-4">
                                            <label><?php echo esc_html__('Enable Mad Mimi', 'quiz-maker'); ?></label>
                                        </div>
                                        <div class="col-sm-1">
                                            <input type="checkbox" class="ays-enable-timer1"/>
                                        </div>
                                    </div>
                                    <hr>
                                    <div class="form-group row">
                                        <div class="col-sm-4">
                                            <label><?php echo esc_html__('Select List', 'quiz-maker'); ?></label>
                                        </div>
                                        <div class="col-sm-8">
                                            <select class="ays-text-input ays-text-input-short">
                                                <option value="" disabled selected><?php echo esc_html__("Select List", 'quiz-maker'); ?></option>
                                            </select>
                                        </div>
                                    </div>
                                    <a href="https://ays-pro.com/wordpress/quiz-maker?utm_source=dashboard&utm_medium=quiz-free&utm_campaign=quiz-edit-page-mad-mimi-<?php echo esc_attr( AYS_QUIZ_UTM_VERSION ); ?>" target="_blank" class="ays-quiz-new-upgrade-button-link">
                                        <div class="ays-quiz-new-upgrade-button-box">
                                            <div>
                                                <img loading="lazy" src="<?php echo esc_url(AYS_QUIZ_ADMIN_URL.'/images/icons/locked_24x24.svg'); ?>">
                                                <img loading="lazy" src="<?php echo esc_url(AYS_QUIZ_ADMIN_URL.'/images/icons/unlocked_24x24.svg'); ?>" class="ays-quiz-new-upgrade-button-hover">
                                            </div>
                                            <div class="ays-quiz-new-upgrade-button"><?php echo esc_html__("Upgrade", "quiz-maker"); ?></div>
                                        </div>
                                    </a>
                                </div>
                            </div>
                        </fieldset> <!-- Mad Mimi Settings -->
                        <hr/>
                        <fieldset>
                            <legend>                        
                                <img loading="lazy" class="ays_integration_logo" src="<?php echo esc_url(AYS_QUIZ_ADMIN_URL); ?>/images/integrations/convertkit_logo.png" alt="">
                                <h5><?php echo esc_html__('ConvertKit Settings', 'quiz-maker'); ?></h5>
                            </legend>
                            <div class="form-group row">
                                <div class="col-sm-12 only_pro" style="padding:20px;">
                                    <div class="pro_features" style="justify-content:flex-end;">

                                    </div>
                                    <div class="form-group row">
                                        <div class="col-sm-4">
                                            <label ><?php echo esc_html__('Enable ConvertKit', 'quiz-maker'); ?></label>
                                        </div>
                                        <div class="col-sm-1">
                                            <input type="checkbox" class="ays-enable-timer1"/>
                                        </div>
                                    </div>
                                    <hr>
                                    <div class="form-group row">
                                        <div class="col-sm-4">
                                            <label><?php echo esc_html__('ConvertKit List', 'quiz-maker'); ?></label>
                                        </div>
                                        <div class="col-sm-8">
                                            <select class="ays-text-input ays-text-input-short">
                                                <option value="" disabled selected><?php echo esc_html__("Select List", 'quiz-maker'); ?></option>
                                            </select>
                                        </div>
                                    </div>
                                    <a href="https://ays-pro.com/wordpress/quiz-maker?utm_source=dashboard&utm_medium=quiz-free&utm_campaign=quiz-edit-page-convertkit-<?php echo esc_attr( AYS_QUIZ_UTM_VERSION ); ?>" target="_blank" class="ays-quiz-new-upgrade-button-link">
                                        <div class="ays-quiz-new-upgrade-button-box">
                                            <div>
                                                <img loading="lazy" src="<?php echo esc_url(AYS_QUIZ_ADMIN_URL.'/images/icons/locked_24x24.svg');?>">
                                                <img loading="lazy" src="<?php echo esc_url(AYS_QUIZ_ADMIN_URL.'/images/icons/unlocked_24x24.svg'); ?>" class="ays-quiz-new-upgrade-button-hover">
                                            </div>
                                            <div class="ays-quiz-new-upgrade-button"><?php echo esc_html__("Upgrade", "quiz-maker"); ?></div>
                                        </div>
                                    </a>
                                </div>
                            </div>
                        </fieldset> <!-- ConvertKit Settings -->
                        <hr/>
                        <fieldset>
                            <legend>                        
                                <img loading="lazy" class="ays_integration_logo" src="<?php echo esc_url(AYS_QUIZ_ADMIN_URL); ?>/images/integrations/get_response.png" alt="">
                                <h5><?php echo esc_html__('GetResponse Settings', 'quiz-maker'); ?></h5>
                            </legend>
                            <div class="form-group row">
                                <div class="col-sm-12 only_pro" style="padding:20px;">
                                    <div class="pro_features" style="justify-content:flex-end;">
                                    </div>
                                    <div class="form-group row">
                                        <div class="col-sm-4">
                                            <label><?php echo esc_html__('Enable GetResponse', 'quiz-maker'); ?></label>
                                        </div>
                                        <div class="col-sm-1">
                                            <input type="checkbox" class="ays-enable-timer1"/>
                                        </div>
                                    </div>
                                    <hr>
                                    <div class="form-group row">
                                        <div class="col-sm-4">
                                            <label><?php echo esc_html__('GetResponse List', 'quiz-maker'); ?></label>
                                        </div>
                                        <div class="col-sm-8">
                                            <select class="ays-text-input ays-text-input-short">
                                                <option value="" disabled selected><?php echo esc_html__("Select List", 'quiz-maker'); ?></option>
                                            </select>
                                        </div>
                                    </div>
                                    <a href="https://ays-pro.com/wordpress/quiz-maker?utm_source=dashboard&utm_medium=quiz-free&utm_campaign=quiz-edit-page-getresponse-<?php echo esc_attr( AYS_QUIZ_UTM_VERSION ); ?>" target="_blank" class="ays-quiz-new-upgrade-button-link">
                                        <div class="ays-quiz-new-upgrade-button-box">
                                            <div>
                                                <img loading="lazy" src="<?php echo esc_url( AYS_QUIZ_ADMIN_URL.'/images/icons/locked_24x24.svg' ); ?>">
                                                <img loading="lazy" src="<?php echo esc_url( AYS_QUIZ_ADMIN_URL.'/images/icons/unlocked_24x24.svg' ); ?>" class="ays-quiz-new-upgrade-button-hover">
                                            </div>
                                            <div class="ays-quiz-new-upgrade-button"><?php echo esc_html__("Upgrade", "quiz-maker"); ?></div>
                                        </div>
                                    </a>
                                </div>
                            </div>
                        </fieldset> <!-- GetResponse Settings -->
                        <?php
                            if(has_action('ays_qm_quiz_page_integrations')){
                                $args = apply_filters( 'ays_qm_quiz_page_integrations_options', array(), $options );
                                do_action( 'ays_qm_quiz_page_integrations', $args);
                            }
                        ?>
                    </div>
                </div>
            </div>
            <!-- <hr>
            <div class="form-group row ays-quiz-general-bundle-container">
                <div class="col-sm-12 ays-quiz-general-bundle-box">
                    <div class="ays-quiz-general-bundle-row">
                        <div class="ays-quiz-general-bundle-text">
                            <?php echo esc_html__( "Grab your", 'quiz-maker' ); ?>
                            <span><?php echo esc_html__( "20% Christmas GIFT", 'quiz-maker' ); ?></span>
                            <?php echo esc_html__( "discount for Quiz Maker plugin! ", 'quiz-maker' ); ?>
                        </div>
                        <p><?php echo esc_html__( "Warm up your website for the winter colds with the best quiz plugin on WP.", 'quiz-maker' ); ?></p>
                        <div class="ays-quiz-general-bundle-sale-text ays-quiz-general-bundle-color">
                            <div><a href="https://ays-pro.com/wordpress/quiz-maker" class="ays-quiz-general-bundle-link-color" target="_blank"><?php echo esc_html__( "Discount 20% OFF", 'quiz-maker' ); ?></a></div>
                        </div>
                    </div>
                    <div class="ays-quiz-general-bundle-row">
                        <a href="https://ays-pro.com/wordpress/quiz-maker" class="ays-quiz-general-bundle-button" target="_blank">Get Now!</a>
                    </div>
                </div>
            </div> --> <!-- Grab your GIFT banner -->
            
            <hr/>
            <!-- <div class="ays_divider_top ays_sticky_submit"> -->
            <?php

                $pro_content = array();

                $pro_content[] = '<div class="only_pro only_pro_save_as_default" title="'.__("This property available only in pro version",'quiz-maker') .'">';
                    $pro_content[] = '<div class="pro_features pro_features_popup">';
                        $pro_content[] = '<div class="pro-features-popup-conteiner">';
                            $pro_content[] = '<div class="pro-features-popup-title">';
                            $pro_content[] = __("Save as Default feature of Quiz", 'quiz-maker');
                            $pro_content[] = '</div>';

                            $pro_content[] = '<div class="pro-features-popup-content" data-link="https://youtu.be/yuYdJnooygU">';
                                $pro_content[] = '<p>';
                                    $pro_content[] = sprintf( __("Do you want to create more than one quiz with the same options or style? The save as Default feature comes to help you, as it %s allows you to change default values while creating a new quiz. %s Just give new values to the option of a single quiz and click on the %s Save as default %s button. Your changes will be saved and you will avoid repeating the same steps for the other quizzes. Please note, that the changes refer to only the newly created quizzes and %s don't refer to the already created ones. %s", 'quiz-maker'),
                                        "<strong>",
                                        "</strong>",
                                        "<strong>",
                                        "</strong>",
                                        "<strong>",
                                        "</strong>"
                                    );
                                $pro_content[] = '</p>';
                                $pro_content[] = '<div>';
                                    $pro_content[] = '<a href="https://quiz-plugin.com/docs/" target="_blank"> '. __("See Documentation", 'quiz-maker'). '</a>';
                                $pro_content[] = '</div>';
                            $pro_content[] = '</div>';

                            $pro_content[] = '<div class="pro-features-popup-button" data-link="https://ays-pro.com/wordpress/quiz-maker">';
                                $pro_content[] = __("Pricing", 'quiz-maker');
                            $pro_content[] = '</div>';
                        $pro_content[] = '</div>';
                    $pro_content[] = '</div>';
                    $pro_content[] = '<div>';
                        $pro_content[] = '<a href="https://ays-pro.com/wordpress/quiz-maker" class="" target="_blank" title="'.__("This property available only in pro version",'quiz-maker') .'">';
                            $pro_content[] = '<button type="button" class="button button-primary ays_default_btn disabled-button">'.__( "Save as default" , 'quiz-maker' );
                            $pro_content[] = '</button>';
                        $pro_content[] = '</a>';
                    $pro_content[] = '</div>';
                $pro_content[] = '</div>';

                $pro_content = implode('', $pro_content);

                wp_nonce_field('quiz_action', 'quiz_action');
                $other_attributes = array();
                $other_attributes_only_save = array(
                    'title' => 'Ctrl + s',
                    'data-toggle' => 'tooltip',
                    'data-delay'=> '{"show":"1000"}'
                );
                $buttons_html = '';
                $buttons_html .= '<div class="ays_save_buttons_content ays_save_buttons_bottom_content">';
                    $buttons_html .= '<div class="ays_save_buttons_box ays-quiz-add-new-button-quiz-edit-box">';
                    echo $buttons_html;
                        echo $loader_iamge;
                        submit_button(esc_html__('Save', 'quiz-maker'), 'primary ays-quiz-loader-banner', 'ays_apply', true, $other_attributes_only_save);
                        submit_button(esc_html__('Save and close', 'quiz-maker'), 'ays-quiz-loader-banner', 'ays_submit', true, $other_attributes);
                        submit_button(esc_html__('Cancel', "quiz-maker"), 'ays-quiz-loader-banner', 'ays_quiz_cancel', true, array());
                    $buttons_html = '</div>';
                    $buttons_html .= '<div class="ays_save_default_button_box">';
                    echo $buttons_html;
                        if ( $prev_quiz_id != "" && !is_null( $prev_quiz_id ) && 1 == 0 ) {

                            $other_attributes = array(
                                'id'            => 'ays-quiz-prev-button',
                                'data-message'  => esc_html__( 'Are you sure you want to go to the previous quiz page?', 'quiz-maker'),
                                'href'          => sprintf( '?page=%s&action=%s&quiz=%d', esc_attr( $_REQUEST['page'] ), 'edit', absint( $prev_quiz_id ) )
                            );
                            submit_button(esc_html__('Prev Quiz', 'quiz-maker'), 'ays-quiz-next-button-class', 'ays_quiz_prev_button', true, $other_attributes);
                        }

                        if ( $next_quiz_id != "" && !is_null( $next_quiz_id ) && 1 == 0 ) {

                            $other_attributes = array(
                                'id'            => 'ays-quiz-next-button',
                                'data-message'  => esc_html__( 'Are you sure you want to go to the next quiz page?', 'quiz-maker'),
                                'href'          => sprintf( '?page=%s&action=%s&quiz=%d', esc_attr( $_REQUEST['page'] ), 'edit', absint( $next_quiz_id ) )
                            );
                            submit_button(esc_html__('Next Quiz', 'quiz-maker'), 'ays-quiz-next-button-class', 'ays_quiz_next_button', true, $other_attributes);
                        }
                        // $buttons_html = '<a class="ays_help" data-toggle="tooltip" title="'.__( "Saves the assigned settings of the current quiz as default. After clicking on this button, each time creating a new quiz, the system will take the settings and styles of the current quiz. If you want to change and renew it, please click on this button on another quiz. This feature is available only in PRO version!!!" ,'quiz-maker' ).'">
                        //     <i class="ays_fa ays_fa_info_circle"></i>
                        // </a>';
                        // echo $buttons_html;
                        $buttons_html = '';
                        // $buttons_html .= '<div>';
                            // $buttons_html .= '<a href="https://ays-pro.com/wordpress/quiz-maker" class="" target="_blank" title="'.__("This property available only in pro version",'quiz-maker') .'">';
                            //     $buttons_html .= '<button type="button" class="button button-primary ays_default_btn disabled-button">'.__( "Save as default" , 'quiz-maker' );
                            //     $buttons_html .= '</button>';
                            // $buttons_html .= '</a>';
                            // $buttons_html .= $pro_content;
                        // $buttons_html .= '</div>';
                    $buttons_html .= '</div>';
                $buttons_html .= "</div>";
                echo $buttons_html;
            ?>
            <!-- </div> -->
        </form>
        <div id="ays-questions-modal" class="ays-modal">
            <!-- Modal content -->
            <div class="ays-modal-content">
                <form method="post" id="ays_add_question_rows">
                    <div class="ays-quiz-preloader">
                        <img loading="lazy" class="ays-quiz-preloader-image" src="<?php echo AYS_QUIZ_ADMIN_URL; ?>/images/loaders/cogs.svg">
                    </div>
                    <div class="ays-modal-header">
                        <span class="ays-close">&times;</span>
                        <h2><?php echo esc_html__('Select questions', 'quiz-maker'); ?></h2>
                    </div>
                    <div class="ays-modal-body">
                        <?php
                        // wp_nonce_field('add_question_rows_top', 'add_question_rows_top_second');
                        $other_attributes = array();
                        submit_button(esc_html__('Insert questions', 'quiz-maker'), 'primary', 'add_question_rows_top', true, $other_attributes);
                        ?>
                        <span style="font-size: 13px; font-style: italic;">
                            <?php echo esc_html__('For select questions click on question row and then click "Insert questions" button', 'quiz-maker'); ?>
                        </span>
                        <div class="ays-quiz-add-question-filter-box">
                            <div class="ays-quiz-add-question-filter" id="ays-quiz-add-question-filter">
                                <img loading="lazy" src="<?php echo AYS_QUIZ_ADMIN_URL . '/images/icons/filter.svg' ?>" style="width: 20px;">
                                <span><?php echo esc_html__('Filters', 'quiz-maker'); ?></span>
                            </div>
                            <div class="ays-quiz-insert-question-modal-create-link" style="font-size: 16px; padding-right:20px; margin:0; text-align:right;">
                                <a class="" href="admin.php?page=<?php echo $this->plugin_name; ?>-questions&action=add" target="_blank">
                                    <?php echo esc_html__('Create question', 'quiz-maker'); ?>
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-external-link-icon lucide-external-link"><path d="M15 3h6v6"/><path d="M10 14 21 3"/><path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"/></svg>
                                </a>
                            </div>
                        </div>
                        <div class="form-group row display_none ays-quiz-add-question-filter-option-box">
                            <div class="col-sm-12 only_pro ays-quiz-margin-top-20" style="padding:15px;">
                                <div class="pro_features" style="align-items: flex-end;justify-content: flex-end;">                            

                                </div>
                                <div class="row" style="margin:0;">
                                    <div class="col-sm-12" id="quest_cat_container">
                                        <label style="width:100%;" for="add_quest_category_filter">
                                            <p style="font-size: 13px; margin:0; font-style: italic;">
                                                <?php echo esc_html__( "Filter by category", 'quiz-maker'); ?>
                                                <button type="button" class="ays_filter_cat_clear button button-small wp-picker-default"><?php echo esc_html__( "Clear", 'quiz-maker' ); ?></button>
                                            </p>
                                        </label>
                                        <select id="add_quest_category_filter" multiple="multiple" class='cat_filter custom-select custom-select-sm form-control form-control-sm'>
                                            <?php
                                                $quiz_cats = array();
                                                foreach($quiz_cats as $cat){
                                                    echo "<option value='". esc_attr( $cat['id'] ) ."'>". esc_attr( $cat['title'] ) ."</option>";
                                                }
                                            ?>
                                        </select>
                                    </div>
                                </div>
                                <hr>
                                <!-- Add Question Tag Filter Start -->
                                <div class="row" style="margin:0;">
                                    <div class="col-sm-12" id="quest_tag_container">
                                        <label style="width:100%;" for="add_quest_tag_filter">
                                            <p style="font-size: 13px; margin:0; font-style: italic;">
                                                <?php echo __( "Filter by tag", 'quiz-maker'); ?>
                                                <button type="button" class="ays_filter_tag_clear button button-small wp-picker-default"><?php echo esc_html__( "Clear", 'quiz-maker' ); ?></button>
                                            </p>
                                        </label>
                                        <select id="add_quest_tag_filter" multiple="multiple" class='tag_filter custom-select custom-select-sm form-control form-control-sm'>
                                            <?php
                                                $quiz_tags = array();
                                                foreach($quiz_tags as $tag){
                                                    echo "<option value='". esc_attr( $tag['id'] ) ."'>". esc_attr( $tag['title'] ) ."</option>";
                                                }
                                            ?>
                                        </select>
                                    </div>
                                </div>
                                <!-- Add Question Tag Filter End  -->
                                <a href="https://ays-pro.com/wordpress/quiz-maker?utm_source=dashboard&utm_medium=quiz-free&utm_campaign=insert-question-popup-filters-<?php echo esc_attr( AYS_QUIZ_UTM_VERSION ); ?>" target="_blank" class="ays-quiz-new-upgrade-button-link">
                                    <div class="ays-quiz-new-upgrade-button-box">
                                        <div>
                                            <img loading="lazy" src="<?php echo AYS_QUIZ_ADMIN_URL.'/images/icons/locked_24x24.svg'?>">
                                            <img loading="lazy" src="<?php echo AYS_QUIZ_ADMIN_URL.'/images/icons/unlocked_24x24.svg'?>" class="ays-quiz-new-upgrade-button-hover">
                                        </div>
                                        <div class="ays-quiz-new-upgrade-button"><?php echo esc_html__("Upgrade", "quiz-maker"); ?></div>
                                    </div>
                                </a>
                            </div>
                        </div>
                        <hr>
                        <table class="ays-add-questions-table hover order-column" id="ays-question-table-add" data-page-length='5'>
                            <thead>
                            <tr>
                                <th class="th-150">#</th>
                                <th style="500px"><?php echo esc_html__('Question', 'quiz-maker'); ?></th>
                                <th class="th-150"><?php echo esc_html__('Type', 'quiz-maker'); ?></th>
                                <th class="th-150"><?php echo esc_html__('Created', 'quiz-maker'); ?></th>
                                <th style="150px"><?php echo esc_html__('Category', 'quiz-maker'); ?></th>
                                <th style="50px">ID</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php
                            foreach ($questions as $index => $question) {
                                $question_options = array();
                                if ( isset( $question['options']) && !is_null( $question['options'] ) ) {
                                    $question_options = json_decode($question['options'], true);
                                }
                                $date = isset($question['create_date']) && Quiz_Maker_Admin::validateDate($question['create_date']) ? esc_attr( stripslashes($question['create_date']) ) : "0000-00-00 00:00:00";
                                if(isset($question_options['author'])){
                                    if(is_array($question_options['author'])){
                                        $author = $question_options['author'];
                                    }else{
                                        $author = json_decode($question_options['author'], true);
                                    }
                                }else{
                                    $author = array("name"=>"Unknown");
                                }
                                $text = "";
                                if(Quiz_Maker_Admin::validateDate($date)){
                                    $text .= "<p style='margin:0;text-align:left;'><b>Date:</b> ".$date."</p>";
                                }
                                if($author['name'] !== "Unknown"){
                                    $text .= "<p style='margin:0;text-align:left;'><b>Author:</b> ".$author['name']."</p>";
                                }
                                $selected_question = (isset($question_id_array) && in_array($question["id"], $question_id_array)) ? "selected" : "";

                                if(isset($question['question_title']) && $question['question_title'] != '' && $quiz_question_title_view == 'question_title'){
                                    $table_question = htmlspecialchars_decode( $question['question_title'], ENT_COMPAT);
                                    $table_question = stripslashes( $table_question );
                                }elseif(isset($question['question']) && strlen($question['question']) != 0){

                                    $is_exists_ruby = Quiz_Maker_Admin::ays_quiz_is_exists_needle_tag( $question['question'] , '<ruby>' );

                                    if ( $is_exists_ruby ) {
                                        $table_question = strip_tags( stripslashes($question['question']), '<ruby><rbc><rtc><rb><rt>' );
                                    } else {
                                        $table_question = strip_tags(stripslashes($question['question']));
                                    }

                                }elseif ((isset($question['question_image']) && $question['question_image'] !='')){
                                    $table_question = __( 'Image question', 'quiz-maker' );
                                }

                                switch ( $question["type"] ) {
                                    case 'short_text':
                                        $question_type = 'short text';
                                        break;
                                    case 'true_or_false':
                                        $question_type = 'true/false';
                                        break;
                                    default:
                                        $question_type = $question["type"];
                                        break;
                                }

                                $table_question = $this->ays_restriction_string("word", $table_question, 8);
                                ?>
                                <tr class="ays_quest_row <?php echo esc_attr($selected_question); ?>" data-id='<?php echo esc_attr($question["id"]); ?>'>
                                    <td>
                                        <span>
                                        <?php if (isset($question_id_array) && in_array($question["id"], $question_id_array)) : ?>
                                           <i class="ays-select-single ays_fa ays_fa_check_square_o"></i>
                                        <?php else: ?>
                                           <i class="ays-select-single ays_fa ays_fa_square_o"></i>
                                        <?php endif; ?>
                                        </span>
                                    </td>
                                    <td class="ays-modal-td-question"><?php echo esc_html($table_question); ?></td>
                                    <td><?php echo esc_html( $question_type ); ?></td>
                                    <td><?php echo $text; ?></td>
                                    <td class="ays-modal-td-category"><?php echo stripslashes($question["title"]); ?></td>
                                    <td><?php echo esc_html($question["id"]); ?></td>
                                </tr>
                                <?php
                            }
                            ?>
                            </tbody>
                        </table>
                    </div>
                    <div class="ays-modal-footer">
                        <?php
                        // wp_nonce_field('add_question_rows', 'add_question_rows');
                        $other_attributes = array('id' => 'ays-button');
                        submit_button(__('Insert questions', 'quiz-maker'), 'primary', 'add_question_rows', true, $other_attributes);

                        $quiz_ajax_add_question_nonce = wp_create_nonce( 'quiz-maker-ajax-add-question-nonce' );

                        ?>
                        <input type="hidden" id="ays_quiz_ajax_add_question_nonce" name="ays_quiz_ajax_add_question_nonce" value="<?php echo $quiz_ajax_add_question_nonce; ?>">
                    </div>
                </form>
            </div>
        </div>

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

        <?php 

        // $if_dismiss_cookie_exists = (isset( $_COOKIE['ays_pages_popup_dismiss_for_three_click'] ) && $_COOKIE['ays_pages_popup_dismiss_for_three_click'] >= 3) ? true : false;
        $if_dismiss_cookie_exists = (isset( $_COOKIE['ays_fox_lms_pages_popup_dismiss_for_three_click'] ) && $_COOKIE['ays_fox_lms_pages_popup_dismiss_for_three_click'] >= 3) ? true : false;
        
        $if_chart_plugin_exists = ( in_array('chart-builder/chart-builder.php', apply_filters('active_plugins', get_option('active_plugins'))) ) ? true : false;
        $if_fox_lms_plugin_exists = ( in_array('fox-lms/fox-lms.php', apply_filters('active_plugins', get_option('active_plugins'))) ) ? true : false;

        $if_chart_plugin_installed_flag = get_option('ays_quiz_and_chart_plugin_flag');

        if ( !$if_chart_plugin_installed_flag ) {
            update_option('ays_quiz_and_chart_plugin_flag', 0);
        }

        if ( $if_chart_plugin_exists ) {
            update_option('ays_quiz_and_chart_plugin_flag', 1);
        }
        
        $if_chart_plugin_installed_flag = get_option('ays_quiz_and_chart_plugin_flag');


        $if_fox_lms_plugin_installed_flag = get_option('ays_quiz_and_fox_lms_plugin_flag');

        if ( !$if_fox_lms_plugin_installed_flag ) {
            update_option('ays_quiz_and_fox_lms_plugin_flag', 0);
        }

        if ( $if_fox_lms_plugin_exists ) {
            update_option('ays_quiz_and_fox_lms_plugin_flag', 1);
        }
        
        $if_fox_lms_plugin_installed_flag = get_option('ays_quiz_and_fox_lms_plugin_flag');


        $quiz_max_id = $this->get_max_id('quizes');

        if( !$if_dismiss_cookie_exists && !$if_chart_plugin_exists && !$if_chart_plugin_installed_flag && $quiz_max_id > 3 && 1 == 0 ):
        ?>
        <!-- Quiz and Chart integration main page 2023 | Start -->
        <div id="ays-quiz-all-pages-popup" class="bounceInRight_2022">
            <div id="ays-quiz-all-pages-popup-main">
                <div class="ays-quiz-all-pages-popup-layer">
                    <div id="ays-quiz-all-pages-popup-close-main">
                        <div id="ays-quiz-all-pages-popup-close"><div>&times;</div></div>
                    </div>
                    <div id="ays-quiz-all-pages-popup-heading">
                        <div class="ays-quiz-all-pages-popup-heading-center">
                            <a href="http://bit.ly/3HsHaML" target="_blank">
                                <img loading="lazy" src="<?php echo AYS_QUIZ_ADMIN_URL; ?>/images/quiz-maker-and-chart-logo.svg">
                            </a>
                        </div>
                    </div>
                    <div id="ays-quiz-all-pages-popup-content">
                        <div class="ays-quiz-all-pages-popup-content-title"><?php echo __("New Integration", 'quiz-maker'); ?></div>
                        <div class="ays-quiz-all-pages-popup-content-description"><?php echo __("Visualize your data with a Chart Builder", 'quiz-maker'); ?></div>
                    </div>
                    <div class="ays-quiz-all-pages-popup-footer">
                        <div id="ays-quiz-all-pages-popup-button" class="ays-quiz-all-pages-popup-st">
                            <div class="ays-quiz-all-pages-popup-btn">
                                <a href="http://bit.ly/3HsHaML" id="ays-pages-submit-popup" class="ays-quiz-all-pages-popup-fields ays-quiz-all-pages-popup-fields-submit" target="_blank"><?php echo __("Try For Free", 'quiz-maker'); ?></a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Quiz and Chart integration main page 2023 | End -->
        <?php endif; ?>
        <?php if( !$if_dismiss_cookie_exists && !$if_fox_lms_plugin_exists && !$if_fox_lms_plugin_installed_flag && 1 == 0 ): ?>
        <!-- Quiz and Fox LMS integration main page 2025 | Start -->
        <div id="ays-quiz-fox-lms-all-pages-popup" class="bounceInRight_2022">
            <div id="ays-quiz-fox-lms-all-pages-popup-main">
                <div class="ays-quiz-fox-lms-all-pages-popup-layer">
                    <div id="ays-quiz-fox-lms-all-pages-popup-close-main">
                        <div id="ays-quiz-fox-lms-all-pages-popup-close"><div>&times;</div></div>
                    </div>
                    <div id="ays-quiz-fox-lms-all-pages-popup-heading-title">
                        <div class="ays-quiz-fox-lms-all-pages-popup-heading-center">
                            <a href="https://bit.ly/43MyeyB" target="_blank">
                                <?php echo esc_html__("WordPress LMS Plugin", 'quiz-maker'); ?>
                            </a>
                        </div>
                    </div>
                    <div id="ays-quiz-fox-lms-all-pages-popup-heading">
                        <div class="ays-quiz-fox-lms-all-pages-popup-heading-center">
                            <a href="https://bit.ly/43MyeyB" target="_blank">
                                <img loading="lazy" src="<?php echo AYS_QUIZ_ADMIN_URL; ?>/images/banner/ays-quiz-and-lms-popup-logo.svg">
                                <img loading="lazy" class="ays-quiz-fox-lms-all-pages-icon" src="<?php echo AYS_QUIZ_ADMIN_URL; ?>/images/banner/ays-quiz-and-lms-popup-icon.svg">
                            </a>
                        </div>
                    </div>
                    <div class="ays-quiz-fox-lms-all-pages-popup-footer">
                        <div id="ays-quiz-fox-lms-all-pages-popup-button" class="ays-quiz-fox-lms-all-pages-popup-st">
                            <div class="ays-quiz-fox-lms-all-pages-popup-btn">
                                <a href="https://bit.ly/43MyeyB" id="ays-pages-submit-popup" class="ays-quiz-fox-lms-all-pages-popup-fields ays-quiz-fox-lms-all-pages-popup-fields-submit" target="_blank"><?php echo esc_html__("Download FREE version", 'quiz-maker'); ?></a>
                            </div>
                        </div>
                    </div>
                    <div id="ays-quiz-fox-lms-all-pages-popup-content">
                        <div class="ays-quiz-fox-lms-all-pages-popup-content-description"><?php echo esc_html__("Get Learning Management System and online course solution in WordPress now.", 'quiz-maker'); ?></div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Quiz and Fox LMS integration main page 2025 | End -->
        <?php endif; ?>
    </div>
</div>
