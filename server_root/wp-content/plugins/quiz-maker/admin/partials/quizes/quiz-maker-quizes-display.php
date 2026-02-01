<?php

$action = ( isset($_GET['action']) ) ? sanitize_text_field( $_GET['action'] ) : '';
$id     = ( isset($_GET['quiz']) ) ? sanitize_text_field( $_GET['quiz'] ) : null;

if($action == 'duplicate'){
    $this->quizes_obj->duplicate_quizzes($id);
}
$max_id = $this->get_max_id('questions');
$quiz_max_id = $this->get_max_id('quizes');
$user_id = get_current_user_id();


$gen_options = ($this->settings_obj->ays_get_setting('options') === false) ? array() : json_decode(stripcslashes($this->settings_obj->ays_get_setting('options')), true);

$question_default_type = isset($gen_options['question_default_type']) && $gen_options['question_default_type'] != '' ? $gen_options['question_default_type'] : null;

$options = array(
    'bg_image'                      => "",
    'use_html'                      => 'off',
);
$question = array(
    'category_id'                   => '1',
    'author_id'                     => $user_id,
    'question'                      => '',
    'question_image'                => '',
    'type'                          => $question_default_type,
    'published'                     => '',
    'user_explanation'              => 'off',
    'wrong_answer_text'             => '',
    'right_answer_text'             => '',
    'explanation'                   => '',
    'create_date'                   => current_time( 'mysql' ),
    'not_influence_to_score'        => 'off',
    'weight'                        => floatval(1),
    'options'                       => json_encode($options),
);

$quiz_accordion_svg_html = '
<div class="ays-quiz-accordion-arrow-box">
    <svg class="ays-quiz-accordion-arrow ays-quiz-accordion-arrow-down" version="1.2" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" overflow="visible" preserveAspectRatio="none" viewBox="0 0 24 24" width="32" height="32">
        <g>
            <path xmlns:default="http://www.w3.org/2000/svg" d="M8.59 16.34l4.58-4.59-4.58-4.59L10 5.75l6 6-6 6z" fill="#008cff" vector-effect="non-scaling-stroke" />
        </g>
    </svg>
</div>';

$svg_sanitize_properties = Quiz_Maker_Admin::ays_quiz_svg_sanitize_allowed_properties();

if(empty($svg_sanitize_properties)){
    $svg_sanitize_properties = 'post';
}

$question_categories = $this->quizes_obj->get_question_categories();
$quiz_categories = $this->quizes_obj->get_quiz_categories();

$plus_icon_svg = "<span class=''><img src='". AYS_QUIZ_ADMIN_URL ."/images/icons/plus-icon.svg'></span>";
$youtube_icon_svg = "<span class=''><img src='". AYS_QUIZ_ADMIN_URL ."/images/icons/youtube-video-icon.svg'></span>";

$quick_quiz_plugin_nonce = wp_create_nonce( 'quiz-maker-ajax-quick-quiz-nonce' );

// Buttons Text
$buttons_texts_res      = ($this->settings_obj->ays_get_setting('buttons_texts') === false) ? json_encode(array()) : $this->settings_obj->ays_get_setting('buttons_texts');
$buttons_texts          = json_decode( stripcslashes( $buttons_texts_res ) , true);


$start_button           = (isset($buttons_texts['start_button']) && $buttons_texts['start_button'] != '') ? stripslashes( esc_attr( $buttons_texts['start_button'] ) ) : 'Start';
$next_button            = (isset($buttons_texts['next_button']) && $buttons_texts['next_button'] != '') ? stripslashes( esc_attr( $buttons_texts['next_button'] ) ) : 'Next';
$previous_button        = (isset($buttons_texts['previous_button']) && $buttons_texts['previous_button'] != '') ? stripslashes( esc_attr( $buttons_texts['previous_button'] ) ) : 'Prev';
$clear_button           = (isset($buttons_texts['clear_button']) && $buttons_texts['clear_button'] != '') ? stripslashes( esc_attr( $buttons_texts['clear_button'] ) ) : 'Clear';
$finish_button          = (isset($buttons_texts['finish_button']) && $buttons_texts['finish_button'] != '') ? stripslashes( esc_attr( $buttons_texts['finish_button'] ) ) : 'Finish';
$see_result_button      = (isset($buttons_texts['see_result_button']) && $buttons_texts['see_result_button'] != '') ? stripslashes( esc_attr( $buttons_texts['see_result_button'] ) ) : 'See Result';
$restart_quiz_button    = (isset($buttons_texts['restart_quiz_button']) && $buttons_texts['restart_quiz_button'] != '') ? stripslashes( esc_attr( $buttons_texts['restart_quiz_button'] ) ) : 'Restart quiz';
$send_feedback_button   = (isset($buttons_texts['send_feedback_button']) && $buttons_texts['send_feedback_button'] != '') ? esc_attr(stripslashes($buttons_texts['send_feedback_button'])) : 'Send feedback';
$load_more_button       = (isset($buttons_texts['load_more_button']) && $buttons_texts['load_more_button'] != '') ? esc_attr(stripslashes($buttons_texts['load_more_button'])) : 'Load more';
$gen_exit_button        = (isset($buttons_texts['exit_button']) && $buttons_texts['exit_button'] != '') ? esc_attr(stripslashes($buttons_texts['exit_button'])) : 'Exit';
$gen_check_button       = (isset($buttons_texts['check_button']) && $buttons_texts['check_button'] != '') ? esc_attr(stripslashes($buttons_texts['check_button'])) : 'Check';
$gen_login_button       = (isset($buttons_texts['login_button']) && $buttons_texts['login_button'] != '') ? esc_attr(stripslashes($buttons_texts['login_button'])) : 'Log In';

// Enable custom texts for buttons
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

?>

<div class="wrap ays-quiz-list-table ays_quizzes_list_table">
    <button style="width:50px;height:50px;" class="ays-pulse-button ays-quizzes-table-quick-start" id="ays_quick_start" title="Quick quiz" data-container="body" data-toggle="popover" data-trigger="hover" data-placement="left" data-content="<?php echo esc_attr( __('Build your quiz in a few minutes','quiz-maker') ); ?>"></button>
    <div class="ays-quiz-heading-box" style="margin-right: 20px; margin-top: 15px;">
        <div class="ays-quiz-wordpress-user-manual-box">
            <a href="https://www.youtube.com/watch?v=gKjzOsn_yDo" target="_blank" style="text-decoration: none;font-size: 13px;">
                <span><img src='<?php echo esc_url( AYS_QUIZ_ADMIN_URL ); ?>/images/icons/youtube-video-icon.svg' ></span>
                <span style="margin-left: 3px; text-decoration: underline;"><?php echo esc_html__('Getting started', "quiz-maker"); ?></span>
            </a>
            <a href="https://quiz-plugin.com/docs/" target="_blank">
                <i class="ays_fa ays_fa_file_text" ></i> 
                <span style="margin-left: 3px;text-decoration: underline;"><?php echo esc_html__("View Documentation", "quiz-maker"); ?></span>
            </a>
        </div>
    </div>
    <h1 class="wp-heading-inline">
        <?php
            echo esc_html__( "Quizzes", 'quiz-maker' );

            // echo sprintf( '<a href="?page=%s&action=%s" class="page-title-action button-primary ays-quiz-add-new-button ays-quiz-add-new-button-new-design"> %s '  . __('Add New', 'quiz-maker') . '</a>', esc_attr( $_REQUEST['page'] ), 'add', $plus_icon_svg);
        ?>
    </h1>
    <?php do_action('ays_quiz_sale_banner'); ?>

    <?php if($max_id <= 3): ?>
    <div class="ays-quiz-admin-notice notice notice-success is-dismissible">
        <p style="font-size:14px;">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M10.7541 0.0528105C8.02071 0.318022 5.13594 1.75774 3.25374 3.77525C1.10672 6.0769 0.00010276 8.88057 0.00010276 12.0252C-0.0093555 14.5353 0.633806 16.6285 2.05254 18.7597C3.81178 21.3929 6.64926 23.2494 9.88398 23.865C10.8298 24.045 12.8539 24.045 13.9794 23.865C18.5194 23.1262 22.3027 19.7353 23.5512 15.2551C24.7524 10.9644 23.5039 6.43683 20.2692 3.33954C18.3397 1.49253 15.7576 0.299078 13.2511 0.0906979C11.804 -0.0229642 11.577 -0.0229642 10.7541 0.0528105ZM13.743 3.83208C16.5615 4.45722 18.7369 6.3137 19.8057 8.99423C20.4016 10.4908 20.5434 11.9873 20.2408 13.5691C20.0611 14.4784 19.3801 14.9899 18.4815 14.8857C17.9897 14.8289 17.6303 14.6016 17.3749 14.1564C17.2236 13.9006 17.1952 13.7017 17.2236 13.124C17.2993 11.4853 17.1668 10.5287 16.7412 9.68568C16.4007 9.0037 15.5589 8.03757 14.9536 7.63976C14.0362 7.02409 13.4592 6.84412 12.2296 6.78729C11.246 6.7494 11.0946 6.76835 10.3569 7.00514C8.16258 7.725 6.72492 9.68568 6.72492 11.9779C6.72492 13.5407 7.19784 14.6584 8.32337 15.7666C9.55294 16.9695 10.4704 17.301 12.5418 17.2632C14.0362 17.2442 14.1497 17.2821 14.5753 17.8693C15.0671 18.5513 14.8969 19.5364 14.2159 19.991C13.5632 20.4267 11.4068 20.5215 9.98802 20.1805C7.68021 19.6122 5.5521 17.9262 4.51169 15.814C3.8307 14.4405 3.65099 13.6165 3.66045 11.9305C3.66045 10.6707 3.68882 10.415 3.91582 9.6762C5.02244 6.06743 8.20041 3.67105 11.927 3.65211C12.5796 3.65211 13.2038 3.70894 13.743 3.83208ZM15.1333 13.2566C15.3792 13.3134 15.7765 13.6354 16.7791 14.6205C18.6802 16.4865 19.2193 17.1116 19.3044 17.5852C19.4084 18.1061 19.276 18.485 18.8599 18.9113C18.2924 19.4985 17.5925 19.5648 16.9398 19.1102C16.8074 19.0154 15.9278 18.1535 14.9914 17.1874C13.62 15.7855 13.2511 15.3593 13.166 15.0562C13.0336 14.5826 13.166 14.0617 13.516 13.6733C13.7808 13.3797 14.4145 13.0955 14.6699 13.1618C14.7455 13.1808 14.9536 13.2281 15.1333 13.2566Z" fill="#E85011"/>
            </svg>
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
    <?php if($quiz_max_id <= 3 && 1 == 0): ?>
    <div class="ays-quiz-heading-box ays-quiz-unset-float">
        <div class="ays-quiz-wordpress-user-manual-box">
            <a href="https://www.youtube.com/watch?v=gKjzOsn_yDo" target="_blank">
                <?php echo esc_html__("Gettings started with Quiz Maker plugin - video", 'quiz-maker'); ?>
            </a>
        </div>
    </div>
    <?php endif; ?>
    <div class="ays-quiz-add-new-button-box">
        <?php
            echo sprintf( '<a href="?page=%s&action=%s" class="page-title-action button-primary ays-quiz-add-new-button ays-quiz-add-new-button-new-design"> %s '  . esc_html__('Add New', 'quiz-maker') . '</a>', esc_attr( $_REQUEST['page'] ), 'add', wp_kses_post($plus_icon_svg) );
        ?>
    </div>
    <div id="poststuff">
        <div id="post-body" class="metabox-holder">
            <div id="post-body-content">
                <div class="meta-box-sortables ui-sortable">
                    <?php
                        $this->quizes_obj->views();
                    ?>
                    <form method="post">
                        <?php
                        $this->quizes_obj->prepare_items();
                        $search = esc_html__( "Search", 'quiz-maker' );
                        $this->quizes_obj->search_box($search, 'quiz-maker');
                        $this->quizes_obj->display();
                        ?>
                    </form>
                </div>
            </div>
        </div>
        <br class="clear">
    </div>
    <div class="ays-quiz-add-new-button-box">
        <?php
            echo sprintf( '<a href="?page=%s&action=%s" class="page-title-action button-primary ays-quiz-add-new-button ays-quiz-add-new-button-new-design"> %s '  . esc_html__('Add New', 'quiz-maker') . '</a>', esc_attr( $_REQUEST['page'] ), 'add', wp_kses_post( $plus_icon_svg ) );
        ?>
    </div>
    <?php if($quiz_max_id <= 3): ?>
    <div class="ays-quiz-create-survey-video-box" style="margin: 0px auto 30px;">
        <div class="ays-quiz-create-survey-title">
            <h4><?php echo esc_html__( "Create quiz with Quiz Maker plugin in One Minute", 'quiz-maker' ); ?></h4>
        </div>
        <div class="ays-quiz-create-survey-youtube-video">
            <div class="ays-quiz-youtube-placeholder" data-video-id="AUHZrVcBrMU">
                <img src="<?php echo esc_url(AYS_QUIZ_ADMIN_URL .'/images/youtube/create-quiz-on-wordpress.webp'); ?>" loading="lazy" width="560" height="315">
            </div>
        </div>
        <div class="ays_quiz_small_hint_text_for_message_variables" style="text-align: center;">
            <?php echo esc_html__( 'Please note that this video will disappear once you created 4 quizzes.', 'quiz-maker' ); ?>
        </div>
        <div class="ays-quiz-create-survey-youtube-video-button-box">
            <?php echo sprintf( '<a href="?page=%s&action=%s" class="ays-quiz-add-new-button-video ays-quiz-add-new-button-new-design"> %s ' . esc_html__('Add New', 'quiz-maker') . '</a>', esc_attr( $_REQUEST['page'] ), 'add', wp_kses_post( $plus_icon_svg ));?>
        </div>
    </div>
    <?php else: ?>
    <div class="ays-quiz-create-survey-video-box ays-quiz-create-survey-video-box-only-link" style="margin: auto;">
        <div class="ays-quiz-create-survey-title">
            <?php echo wp_kses_post($youtube_icon_svg); ?>
            <a href="https://www.youtube.com/watch?v=AUHZrVcBrMU" target="_blank" title="YouTube video player"><?php echo esc_html__("How to create a quiz in one minute?", 'quiz-maker'); ?></a>
        </div>
    </div>
    <?php endif; ?>
    <div id="ays-quick-modal" tabindex="-1" class="ays-modal">
        <!-- Modal content -->
        <div class="ays-modal-content fadeInDown" id="ays-quick-modal-content">
            <div class="ays-quiz-preloader">
                <img src="<?php echo esc_url(AYS_QUIZ_ADMIN_URL); ?>/images/loaders/tail-spin.svg">
            </div>
            <div class="ays-modal-header">
                <span class="ays-close">&times;</span>
                <h4><?php echo esc_html__('Build your quiz in few seconds', 'quiz-maker'); ?></h4>
            </div>
            <div class="ays-modal-body">
                <form method="post" id="ays_quick_popup">
                    <div class="ays_modal_element">
                        <div class="form-group row">
                            <div class="col-sm-2">
                                <label class='ays-label ays_quiz_title' for='ays-quiz-title'><?php echo esc_html__('Quiz Title', 'quiz-maker'); ?></label>
                            </div>
                            <div class="col-sm-10">
                                <input type="text" class="ays-text-input" id='ays-quiz-title' name='ays_quiz_title' value=""/>
                            </div>
                        </div>
                        <hr>
                        <div class="form-group row">
                            <div class="col-sm-2">
                                <label class='ays-label ays_quick_quiz_description' for='ays-quick-quiz-description'><?php echo esc_html__('Quiz Description', 'quiz-maker'); ?></label>
                            </div>
                            <div class="col-sm-10">
                                <textarea class="ays-text-input ays-textarea-height-100" id='ays-quick-quiz-description' name='ays_quick_quiz_description'></textarea>
                            </div>
                        </div>
                        <hr>
                        <div class="form-group row">
                            <div class="col-sm-2">
                                <label class='ays-label ays_quiz_title' for='ays-quiz-category'><?php echo esc_html__('Quiz Category', 'quiz-maker'); ?></label>
                            </div>
                            <div class="col-sm-10">
                                <select id="ays-quiz-category" class="ays-text-input ays-text-input-short" name="ays_quiz_category">
                                    <?php
                                        foreach ($quiz_categories as $key => $quiz_category) {
                                            echo "<option value='" . esc_attr($quiz_category['id']) . "'>" . esc_attr( $quiz_category['title'] ) . "</option>";
                                        }
                                    ?>
                                </select>
                            </div>
                        </div>
                        <hr>
                        <div class="form-group row">
                            <div class="col-sm-2">
                                <label class='ays-label ays_quiz_title' for='ays-quiz-category'><?php echo esc_html__('Status', 'quiz-maker'); ?></label>
                            </div>
                            <div class="col-sm-10" style="display: flex;">
                                <div class="form-check form-check-inline">
                                    <input type="radio" id="ays-quick-quiz-publish" name="ays_quick_quiz_publish" value="1" checked />
                                    <label class="form-check-label" for="ays-quick-quiz-publish"> <?php echo esc_html__('Published', 'quiz-maker'); ?> </label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input type="radio" id="ays-quick-quiz-unpublish" name="ays_quick_quiz_publish" value="0"/>
                                    <label class="form-check-label" for="ays-quick-quiz-unpublish"> <?php echo esc_html__('Unpublished', 'quiz-maker'); ?> </label>
                                </div>
                            </div>
                        </div>
                        <hr>
                        <div class="form-group row ays_toggle_parent">
                            <div class="col-sm-2">
                                <label for="ays_quick_quiz_enable_options">
                                    <?php echo esc_html__('Quiz Options','quiz-maker'); ?>
                                </label>
                            </div>
                            <div class="col-sm-1">
                                <input type="checkbox" class="ays-enable-timerl ays_toggle_checkbox" id="ays_quick_quiz_enable_options" name="ays_quick_quiz_enable_options" value="on" />
                            </div>
                            <div class="col-sm-9 ays_toggle_target ays-quiz-tab-content ays_divider_left display_none">
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
                                        <?php echo wp_kses($quiz_accordion_svg_html, $svg_sanitize_properties); ?>
                                        <p class="ays-subtitle" style="margin-top: 0;"><?php echo esc_html__('Settings','quiz-maker'); ?></p>
                                    </div>
                                    <hr class="ays-quiz-bolder-hr"/>
                                    <div class="ays-quiz-accordion-options-box">
                                        <div class="form-group row">
                                            <div class="col-sm-4">
                                                <label for="ays_quick_quiz_enable_randomize_questions">
                                                   <?php echo esc_html__('Enable randomize questions','quiz-maker'); ?>
                                                </label>
                                            </div>
                                            <div class="col-sm-8">
                                                <input type="checkbox" class="ays-enable-timerl" id="ays_quick_quiz_enable_randomize_questions" name="ays_quick_quiz_enable_randomize_questions" value="on" />
                                            </div>
                                        </div> <!-- Enable randomize questions -->
                                        <hr>
                                        <div class="form-group row">
                                            <div class="col-sm-4">
                                                <label for="ays_quick_quiz_enable_randomize_answers">
                                                   <?php echo esc_html__('Enable randomize answers','quiz-maker'); ?>
                                                </label>
                                            </div>
                                            <div class="col-sm-8">
                                                <input type="checkbox" class="ays-enable-timerl" id="ays_quick_quiz_enable_randomize_answers" name="ays_quick_quiz_enable_randomize_answers" value="on" />
                                            </div>
                                        </div> <!-- Enable randomize answers -->
                                        <hr/>
                                        <div class="form-group row ays_toggle_parent">
                                            <div class="col-sm-4">
                                                <label for="ays_quick_quiz_enable_questions_ordering_by_cat">
                                                   <?php echo esc_html__('Group questions by category','quiz-maker'); ?>
                                                </label>
                                            </div>
                                            <div class="col-sm-1">
                                                <input type="checkbox" class="ays-enable-timerl ays_toggle_checkbox" id="ays_quick_quiz_enable_questions_ordering_by_cat" name="ays_quick_quiz_enable_questions_ordering_by_cat" value="on"/>
                                            </div>
                                            <div class="col-sm-7 ays_toggle_target ays_divider_left display_none">
                                                <div class="form-group row">
                                                    <div class="col-sm-4">
                                                        <label class="form-check-label" for="ays_quick_quiz_questions_numbering_by_category">
                                                            <?php echo esc_html__('Enable questions numbering by category', 'quiz-maker'); ?>
                                                        </label>
                                                    </div>
                                                    <div class="col-sm-8">
                                                        <input type="checkbox" class="" id="ays_quick_quiz_questions_numbering_by_category" name="ays_quick_quiz_questions_numbering_by_category" value="on">
                                                    </div>
                                                </div>
                                            </div>
                                        </div> <!-- Group questions by category -->
                                        <hr/>
                                        <div class="form-group row">
                                            <div class="col-sm-4">
                                                <label for="ays_quick_quiz_display_all_questions">
                                                    <?php echo esc_html__('Display all questions on one page','quiz-maker'); ?>
                                                </label>
                                            </div>
                                            <div class="col-sm-8">
                                                <input type="checkbox" class="ays-enable-timerl" id="ays_quick_quiz_display_all_questions" name="ays_quick_quiz_display_all_questions" value="on" />
                                            </div>
                                        </div> <!-- Display all questions on one page -->
                                        <hr/>
                                        <div class="form-group row">
                                            <div class="col-sm-4">
                                                <label for="ays_quick_quiz_enable_correction">
                                                    <?php echo esc_html__('Show correct answers','quiz-maker'); ?>
                                                </label>
                                            </div>
                                            <div class="col-sm-8">
                                                <input type="checkbox" class="ays-enable-timer1" id="ays_quick_quiz_enable_correction" name="ays_quick_quiz_enable_correction" value="on" checked />
                                            </div>
                                        </div> <!-- Show correct answers -->
                                        <hr/>
                                        <div class="form-group row ays_toggle_parent">
                                            <div class="col-sm-4">
                                                <label for="ays_quick_quiz_show_question_category">
                                                    <?php echo esc_html__('Show question category','quiz-maker'); ?>
                                                </label>
                                            </div>
                                            <div class="col-sm-1">
                                                <input type="checkbox" class="ays-enable-timer1 ays_toggle_checkbox" id="ays_quick_quiz_show_question_category" name="ays_quick_quiz_show_question_category" value="on" />
                                            </div>
                                            <div class="col-sm-7 ays_toggle_target ays_divider_left display_none">
                                                <div class="form-group row">
                                                    <div class="col-sm-4">
                                                        <label for="ays_quick_quiz_enable_question_category_description">
                                                            <?php echo esc_html__('Show question category description','quiz-maker'); ?>
                                                        </label>
                                                    </div>
                                                    <div class="col-sm-8">
                                                        <input type="checkbox" name="ays_quick_quiz_enable_question_category_description" id="ays_quick_quiz_enable_question_category_description" />
                                                    </div>
                                                </div>
                                            </div>
                                        </div> <!-- Show question category -->
                                        <hr/>
                                        <div class="form-group row">
                                            <div class="col-sm-4">
                                                <label for="ays_quick_quiz_enable_pass_count">
                                                    <?php echo esc_html__('Show passed users count','quiz-maker'); ?>
                                                </label>
                                            </div>
                                            <div class="col-sm-8">
                                                <input type="checkbox" id="ays_quick_quiz_enable_pass_count" name="ays_quick_quiz_enable_pass_count" value="on" />
                                            </div>
                                        </div> <!-- Show passed users count -->
                                        <hr/>
                                        <div class="form-group row ays_toggle_parent">
                                            <div class="col-sm-4">
                                                <label for="ays_quick_quiz_show_category">
                                                    <?php echo esc_html__('Show quiz category','quiz-maker'); ?>
                                                </label>
                                            </div>
                                            <div class="col-sm-1">
                                                <input type="checkbox" class="ays-enable-timer1 ays_toggle_checkbox" id="ays_quick_quiz_show_category" name="ays_quick_quiz_show_category" value="on" />
                                            </div>
                                            <div class="col-sm-7 ays_toggle_target ays_divider_left display_none">
                                                <div class="form-group row">
                                                    <div class="col-sm-4">
                                                        <label for="ays_quick_quiz_enable_quiz_category_description">
                                                            <?php echo esc_html__('Show quiz category description','quiz-maker'); ?>
                                                        </label>
                                                    </div>
                                                    <div class="col-sm-8">
                                                        <input type="checkbox" name="ays_quick_quiz_enable_quiz_category_description" id="ays_quick_quiz_enable_quiz_category_description" />
                                                    </div>
                                                </div>
                                            </div>
                                        </div> <!-- Show quiz category -->
                                        <hr/>
                                        <div class="form-group row">
                                            <div class="col-sm-4">
                                                <label for="ays_quick_quiz_enable_rate_avg">
                                                    <?php echo esc_html__('Show average rate','quiz-maker'); ?>
                                                </label>
                                            </div>
                                            <div class="col-sm-8">
                                                <input type="checkbox" id="ays_quick_quiz_enable_rate_avg" name="ays_quick_quiz_enable_rate_avg" value="on" />
                                            </div>
                                        </div> <!-- Show average rate -->
                                        <hr/>
                                        <div class="form-group row">
                                            <div class="col-sm-4">
                                                <label for="ays_quick_quiz_show_author">
                                                    <?php echo esc_html__('Show quiz author','quiz-maker'); ?>
                                                </label>
                                            </div>
                                            <div class="col-sm-8">
                                                <input type="checkbox" id="ays_quick_quiz_show_author" name="ays_quick_quiz_show_author" value="on" />
                                            </div>
                                        </div> <!-- Show quiz author -->
                                        <hr/>
                                        <div class="form-group row">
                                            <div class="col-sm-4">
                                                <label for="ays_quick_quiz_show_create_date">
                                                    <?php echo esc_html__('Show creation date','quiz-maker'); ?>
                                                </label>
                                            </div>
                                            <div class="col-sm-8">
                                                <input type="checkbox" id="ays_quick_quiz_show_create_date" name="ays_quick_quiz_show_create_date" value="on" />
                                            </div>
                                        </div> <!-- Show creation date -->
                                        <hr />
                                        <div class="form-group row">
                                            <div class="col-sm-4">
                                                <label for="ays_quick_quiz_enable_next_button">
                                                    <?php echo esc_html__('Enable next button','quiz-maker'); ?>
                                                </label>
                                            </div>
                                            <div class="col-sm-8">
                                                <input type="checkbox" id="ays_quick_quiz_enable_next_button" value="on" name="ays_quick_quiz_enable_next_button" checked>
                                            </div>
                                        </div> <!-- Enable next button -->
                                        <hr/>
                                        <div class="form-group row">
                                            <div class="col-sm-4">
                                                <label for="ays_quick_quiz_enable_previous_button">
                                                    <?php echo esc_html__('Enable previous button','quiz-maker'); ?>
                                                </label>
                                            </div>
                                            <div class="col-sm-8">
                                                <input type="checkbox" id="ays_quick_quiz_enable_previous_button" value="on" name="ays_quick_quiz_enable_previous_button" checked>
                                            </div>
                                        </div> <!-- Enable previous button -->
                                        <hr/>
                                        <div class="form-group row ays_toggle_parent">
                                            <div class="col-sm-4">
                                                <label for="ays_quick_quiz_enable_early_finish">
                                                    <?php echo esc_html__('Enable finish button','quiz-maker'); ?>
                                                </label>
                                            </div>
                                            <div class="col-sm-1">
                                                <input type="checkbox" class="ays-enable-timer1 ays_toggle_checkbox" id="ays_quick_quiz_enable_early_finish" name="ays_quick_quiz_enable_early_finish" value="on"/>
                                            </div>
                                            <div class="col-sm-7 ays_toggle_target ays_divider_left display_none">
                                                <div class="form-group row">
                                                    <div class="col-sm-4">
                                                        <label for="ays_quick_quiz_enable_early_finsh_comfirm_box">
                                                            <?php echo esc_html__('Enable confirm box for the Finish button','quiz-maker'); ?>
                                                        </label>
                                                    </div>
                                                    <div class="col-sm-8">
                                                        <input type="checkbox" class="ays-enable-timer1" id="ays_quick_quiz_enable_early_finsh_comfirm_box" name="ays_quick_quiz_enable_early_finsh_comfirm_box" value="on" checked />
                                                    </div>
                                                </div>
                                            </div>
                                        </div> <!-- Enable finish button -->
                                        <hr/>
                                        <div class="form-group row">
                                            <div class="col-sm-4">
                                                <label for="ays_quick_quiz_enable_clear_answer">
                                                    <?php echo esc_html__('Enable clear answer button','quiz-maker')?>
                                                </label>
                                            </div>
                                            <div class="col-sm-8">
                                                <input type="checkbox" class="ays-enable-timer1" id="ays_quick_quiz_enable_clear_answer" name="ays_quick_quiz_enable_clear_answer" value="on" />
                                            </div>
                                        </div> <!-- Enable clear answer button -->
                                        <hr/>
                                        <div class="form-group row">
                                            <div class="col-sm-4">
                                                <label for="ays_quick_quiz_enable_enter_key">
                                                    <?php echo esc_html__('Enable to go next by pressing Enter key','quiz-maker'); ?>
                                                </label>
                                            </div>
                                            <div class="col-sm-8">
                                                <input type="checkbox" class="ays-enable-timer1" id="ays_quick_quiz_enable_enter_key" name="ays_quick_quiz_enable_enter_key" value="on" checked/>
                                            </div>
                                        </div> <!-- Enable to go next by pressing Enter key -->
                                        <hr/>
                                        <div class="form-group row">
                                            <div class="col-sm-4">
                                                <label for="ays_quick_quiz_display_messages_before_buttons">
                                                    <?php echo esc_html__('Display messages before the buttons','quiz-maker'); ?>
                                                </label>
                                            </div>
                                            <div class="col-sm-8">
                                                <input type="checkbox" class="ays-enable-timer1" id="ays_quick_quiz_display_messages_before_buttons" name="ays_quick_quiz_display_messages_before_buttons" value="on" />
                                            </div>
                                        </div> <!-- Display messages before the buttons -->
                                        <hr>
                                        <div class="form-group row">
                                            <div class="col-sm-4">
                                                <label for="ays_quick_quiz_enable_audio_autoplay">
                                                    <?php echo esc_html__('Enable audio autoplay','quiz-maker')?>
                                                </label>
                                            </div>
                                            <div class="col-sm-8">
                                                <input type="checkbox" class="ays-enable-timer1" id="ays_quick_quiz_enable_audio_autoplay" name="ays_quick_quiz_enable_audio_autoplay" value="on" />
                                            </div>
                                        </div> <!-- Enable audio autoplay -->
                                        <hr/>
                                        <div class="form-group row ays_toggle_parent">
                                            <div class="col-sm-4">
                                                <label for="ays_quick_quiz_enable_live_progress_bar">
                                                    <?php echo esc_html__('Enable live progress bar','quiz-maker'); ?>
                                                </label>
                                            </div>
                                            <div class="col-sm-1">
                                                <input type="checkbox" class="ays-enable-timer1 ays_toggle_checkbox" id="ays_quick_quiz_enable_live_progress_bar" name="ays_quick_quiz_enable_live_progress_bar" value="on"/>
                                            </div>
                                            <div class="col-sm-7 ays_toggle_target ays_divider_left display_none">
                                                <div class="form-group row">
                                                    <div class="col-sm-4">
                                                        <label for="ays_quick_quiz_enable_percent_view_option">
                                                            <?php echo esc_html__('Enable percent view','quiz-maker'); ?>
                                                        </label>
                                                    </div>
                                                    <div class="col-sm-8">
                                                        <input type="checkbox" class="ays-enable-timer1" id="ays_quick_quiz_enable_percent_view_option" name="ays_quick_quiz_enable_percent_view_option"value="on"/>
                                                    </div>
                                                </div>
                                            </div>
                                        </div> <!-- Enable live progress bar -->
                                        <hr/>
                                        <div class="form-group row">
                                            <div class="col-sm-4">
                                                <label for="ays_quick_quiz_enable_rtl_direction">
                                                    <?php echo esc_html__('Use RTL Direction','quiz-maker'); ?>
                                                </label>
                                            </div>
                                            <div class="col-sm-8">
                                                <input type="checkbox" class="ays-enable-timerl" id="ays_quick_quiz_enable_rtl_direction" name="ays_quick_quiz_enable_rtl_direction" value="on" />
                                            </div>
                                        </div> <!-- Use RTL Direction -->
                                        <hr/>
                                        <div class="form-group row">
                                            <div class="col-sm-4">
                                                <label for="ays_quick_quiz_enable_questions_counter">
                                                    <?php echo esc_html__('Show questions counter','quiz-maker'); ?>
                                                </label>
                                            </div>
                                            <div class="col-sm-8">
                                                <input type="checkbox" class="ays-enable-timerl" id="ays_quick_quiz_enable_questions_counter" name="ays_quick_quiz_enable_questions_counter" value="on" checked/>
                                            </div>
                                        </div> <!-- Show questions counter -->
                                        <hr/>
                                        <div class="form-group row">
                                            <div class="col-sm-4">
                                                <label for="ays_quick_quiz_enable_question_image_zoom">
                                                    <?php echo esc_html__('Question Image Zoom','quiz-maker'); ?>
                                                </label>
                                            </div>
                                            <div class="col-sm-8">
                                                <input type="checkbox" class="ays-enable-timer1" id="ays_quick_quiz_enable_question_image_zoom" name="ays_quick_quiz_enable_question_image_zoom" value="on" />
                                            </div>
                                        </div> <!-- Question Image Zoom -->
                                        <hr>
                                        <div class="form-group row">
                                            <div class="col-sm-4">
                                                <label for="ays_quick_quiz_enable_leave_page">
                                                    <?php echo esc_html__('Enable confirmation box for leaving the page','quiz-maker'); ?>
                                                </label>
                                            </div>
                                            <div class="col-sm-8">
                                                <input type="checkbox" class="ays-enable-timer1" id="ays_quick_quiz_enable_leave_page" name="ays_quick_quiz_enable_leave_page" value="on" checked/>
                                            </div>
                                        </div> <!-- Enable confirmation box for leaving the page -->
                                        <hr/>
                                        <div class="form-group row">
                                            <div class="col-sm-4">
                                                <label for="ays_quick_quiz_enable_see_result_confirm_box">
                                                    <?php echo esc_html__('Enable confirmation box for the See Result button','quiz-maker'); ?>
                                                </label>
                                            </div>
                                            <div class="col-sm-8">
                                                <input type="checkbox" class="ays-enable-timer1" id="ays_quick_quiz_enable_see_result_confirm_box" name="ays_quick_quiz_enable_see_result_confirm_box" value="on" />
                                            </div>
                                        </div> <!-- Enable confirmation box for the See Result button  -->
                                        <hr/>
                                        <div class="form-group row ays_toggle_parent">
                                            <div class="col-sm-4">
                                                <label for="ays_quick_quiz_enable_rw_asnwers_sounds">
                                                    <?php echo esc_html__('Enable sounds for right/wrong answers','quiz-maker'); ?>
                                                </label>
                                            </div>
                                            <div class="col-sm-8">
                                                <input type="checkbox" id="ays_quick_quiz_enable_rw_asnwers_sounds" name="ays_quick_quiz_enable_rw_asnwers_sounds" class="ays_toggle_checkbox" value="on"/>
                                            </div>
                                        </div> <!-- Enable sounds for right/wrong answers -->
                                        <hr>
                                        <div class="form-group row ays_toggle_parent">
                                            <div class="col-sm-4">
                                                <label for="ays_quick_quiz_enable_custom_texts_for_buttons">
                                                    <?php echo esc_html__('Enable custom texts for buttons','quiz-maker'); ?>
                                                </label>
                                            </div>
                                            <div class="col-sm-1">
                                                <input type="checkbox" class="ays-enable-timer1 ays_toggle_checkbox" id="ays_quick_quiz_enable_custom_texts_for_buttons" name="ays_quick_quiz_enable_custom_texts_for_buttons" value="on" />
                                            </div>
                                            <div class="col-sm-7 ays_toggle_target ays_divider_left display_none">
                                                <div class="form-group row">
                                                    <div class="col-sm-4">
                                                        <label for="ays_quick_quiz_custom_texts_start_button">
                                                            <?php echo esc_html__('Start button','quiz-maker'); ?>
                                                        </label> 
                                                    </div>
                                                    <div class="col-sm-8">
                                                        <input type="text" class="ays-text-input" name="ays_quick_quiz_custom_texts_start_button" id="ays_quick_quiz_custom_texts_start_button" value="<?php echo esc_attr($quiz_custom_texts_start_button); ?>"/>
                                                    </div>
                                                </div>
                                                <br>
                                                <div class="form-group row">
                                                    <div class="col-sm-4">
                                                        <label for="ays_quick_quiz_custom_texts_next_button">
                                                            <?php echo esc_html__('Next button','quiz-maker'); ?>
                                                        </label> 
                                                    </div>
                                                    <div class="col-sm-8">
                                                        <input type="text" class="ays-text-input" name="ays_quick_quiz_custom_texts_next_button" id="ays_quick_quiz_custom_texts_next_button" value="<?php echo esc_attr($quiz_custom_texts_next_button); ?>"/>
                                                    </div>
                                                </div>
                                                <br>
                                                <div class="form-group row">
                                                    <div class="col-sm-4">
                                                        <label for="ays_quick_quiz_custom_texts_prev_button">
                                                            <?php echo esc_html__('Previous button','quiz-maker'); ?>
                                                        </label> 
                                                    </div>
                                                    <div class="col-sm-8">
                                                        <input type="text" class="ays-text-input" name="ays_quick_quiz_custom_texts_prev_button" id="ays_quick_quiz_custom_texts_prev_button" value="<?php echo esc_attr($quiz_custom_texts_prev_button); ?>"/>
                                                    </div>
                                                </div>
                                                <br>
                                                <div class="form-group row">
                                                    <div class="col-sm-4">
                                                        <label for="ays_quick_quiz_custom_texts_clear_button">
                                                            <?php echo esc_html__('Clear button','quiz-maker'); ?>
                                                        </label> 
                                                    </div>
                                                    <div class="col-sm-8">
                                                        <input type="text" class="ays-text-input" name="ays_quick_quiz_custom_texts_clear_button" id="ays_quick_quiz_custom_texts_clear_button" value="<?php echo esc_attr($quiz_custom_texts_clear_button); ?>"/>
                                                    </div>
                                                </div>
                                                <br>
                                                <div class="form-group row">
                                                    <div class="col-sm-4">
                                                        <label for="ays_quick_quiz_custom_texts_finish_button">
                                                            <?php echo esc_html__('Finish button','quiz-maker'); ?>
                                                        </label> 
                                                    </div>
                                                    <div class="col-sm-8">
                                                        <input type="text" class="ays-text-input" name="ays_quick_quiz_custom_texts_finish_button" id="ays_quick_quiz_custom_texts_finish_button" value="<?php echo esc_attr($quiz_custom_texts_finish_button); ?>"/>
                                                    </div>
                                                </div>
                                                <br>
                                                <div class="form-group row">
                                                    <div class="col-sm-4">
                                                        <label for="ays_quick_quiz_custom_texts_see_results_button">
                                                            <?php echo esc_html__('See Result button','quiz-maker'); ?>
                                                        </label> 
                                                    </div>
                                                    <div class="col-sm-8">
                                                        <input type="text" class="ays-text-input" name="ays_quick_quiz_custom_texts_see_results_button" id="ays_quick_quiz_custom_texts_see_results_button" value="<?php echo esc_attr($quiz_custom_texts_see_results_button); ?>"/>
                                                    </div>
                                                </div>
                                                <br>
                                                <div class="form-group row">
                                                    <div class="col-sm-4">
                                                        <label for="ays_quick_quiz_custom_texts_restart_quiz_button">
                                                            <?php echo esc_html__('Restart quiz button','quiz-maker'); ?>
                                                        </label> 
                                                    </div>
                                                    <div class="col-sm-8">
                                                        <input type="text" class="ays-text-input" name="ays_quick_quiz_custom_texts_restart_quiz_button" id="ays_quick_quiz_custom_texts_restart_quiz_button" value="<?php echo esc_attr($quiz_custom_texts_restart_quiz_button); ?>"/>
                                                    </div>
                                                </div>
                                                <br>
                                                <div class="form-group row">
                                                    <div class="col-sm-4">
                                                        <label for="ays_quick_quiz_custom_texts_send_feedback_button">
                                                            <?php echo esc_html__('Send feedback button','quiz-maker'); ?>
                                                        </label> 
                                                    </div>
                                                    <div class="col-sm-8">
                                                        <input type="text" class="ays-text-input" name="ays_quick_quiz_custom_texts_send_feedback_button" id="ays_quick_quiz_custom_texts_send_feedback_button" value="<?php echo esc_attr($quiz_custom_texts_send_feedback_button); ?>"/>
                                                    </div>
                                                </div>
                                                <br>
                                                <div class="form-group row">
                                                    <div class="col-sm-4">
                                                        <label for="ays_quick_quiz_custom_texts_load_more_button">
                                                            <?php echo esc_html__('Load more button','quiz-maker'); ?>
                                                        </label> 
                                                    </div>
                                                    <div class="col-sm-8">
                                                        <input type="text" class="ays-text-input" name="ays_quick_quiz_custom_texts_load_more_button" id="ays_quick_quiz_custom_texts_load_more_button" value="<?php echo esc_attr($quiz_custom_texts_load_more_button); ?>"/>
                                                    </div>
                                                </div>
                                                <br>
                                                <div class="form-group row">
                                                    <div class="col-sm-4">
                                                        <label for="ays_quick_quiz_custom_texts_exit_button">
                                                            <?php echo esc_html__('Exit button','quiz-maker'); ?>
                                                        </label> 
                                                    </div>
                                                    <div class="col-sm-8">
                                                        <input type="text" class="ays-text-input" name="ays_quick_quiz_custom_texts_exit_button" id="ays_quick_quiz_custom_texts_exit_button" value="<?php echo esc_attr($quiz_custom_texts_exit_button); ?>"/>
                                                    </div>
                                                </div>
                                                <br>
                                                <div class="form-group row">
                                                    <div class="col-sm-4">
                                                        <label for="ays_quick_quiz_custom_texts_check_button">
                                                            <?php echo esc_html__('Check button','quiz-maker'); ?>
                                                        </label> 
                                                    </div>
                                                    <div class="col-sm-8">
                                                        <input type="text" class="ays-text-input" name="ays_quick_quiz_custom_texts_check_button" id="ays_quick_quiz_custom_texts_check_button" value="<?php echo esc_attr($quiz_custom_texts_check_button); ?>"/>
                                                    </div>
                                                </div>
                                                <br>
                                                <div class="form-group row">
                                                    <div class="col-sm-4">
                                                        <label for="ays_quick_quiz_custom_texts_login_button">
                                                            <?php echo esc_html__('Log In button','quiz-maker'); ?>
                                                        </label> 
                                                    </div>
                                                    <div class="col-sm-8">
                                                        <input type="text" class="ays-text-input" name="ays_quick_quiz_custom_texts_login_button" id="ays_quick_quiz_custom_texts_login_button" value="<?php echo esc_attr($quiz_custom_texts_login_button); ?>"/>
                                                    </div>
                                                </div>
                                            </div>
                                        </div> <!-- Enable custom texts for buttons -->
                                        <hr/>
                                        <div class="form-group row">
                                            <div class="col-sm-4">
                                                <label for="ays_quick_quiz_show_quiz_title">
                                                    <?php echo esc_html__('Show quiz title','quiz-maker'); ?>
                                                </label>
                                            </div>
                                            <div class="col-sm-8">
                                                <input type="checkbox" id="ays_quick_quiz_show_quiz_title" name="ays_quick_quiz_show_quiz_title" class="" value="on" checked />
                                            </div>
                                        </div> <!-- Show quiz head information / Show quiz title -->
                                        <hr/>
                                        <div class="form-group row">
                                            <div class="col-sm-4">
                                                <label for="ays_quick_quiz_show_quiz_desc">
                                                    <?php echo esc_html__('Show quiz description','quiz-maker'); ?>
                                                </label>
                                            </div>
                                            <div class="col-sm-8">
                                                <input type="checkbox" id="ays_quick_quiz_show_quiz_desc" name="ays_quick_quiz_show_quiz_desc" class="" value="on" checked />
                                            </div>
                                        </div> <!-- Show quiz head information / Show quiz description -->
                                        <hr/>
                                        <div class="form-group row">
                                            <div class="col-sm-4">
                                                <label>
                                                    <?php echo esc_html__('Show question explanation','quiz-maker'); ?>
                                                </label>
                                            </div>
                                            <div class="col-sm-8">
                                                <select id="ays_quick_quiz_show_questions_explanation" name="ays_quick_quiz_show_questions_explanation" class="ays-text-input ays-text-input-short">
                                                    <option value="on_passing"><?php echo esc_html__( "During the quiz", 'quiz-maker' ); ?></option>
                                                    <option value="on_results_page" selected><?php echo esc_html__( 'On results page', 'quiz-maker'); ?></option>
                                                    <option value="on_both"><?php echo esc_html__( 'On Both', 'quiz-maker'); ?></option>
                                                    <option value="disable"><?php echo esc_html__( 'Disable', 'quiz-maker'); ?></option>
                                                </select>
                                            </div>
                                        </div> <!-- Show question explanation -->
                                        <hr/>
                                        <div class="form-group row">
                                            <div class="col-sm-4">
                                                <label for="ays_quick_quiz_show_questions_numbering">
                                                    <?php echo esc_html__('Questions numbering','quiz-maker'); ?>
                                                </label>
                                            </div>
                                            <div class="col-sm-8">
                                                <select name="ays_quick_quiz_show_questions_numbering" class="ays-text-input ays-text-input-short" id="ays_quick_quiz_show_questions_numbering">
                                                    <option value="none" selected><?php echo esc_html__( "None", 'quiz-maker' ); ?></option>
                                                    <option value="1."><?php echo esc_html__( "1.", 'quiz-maker' ); ?></option>
                                                    <option value="1)"><?php echo esc_html__( "1)", 'quiz-maker' ); ?></option>
                                                    <option value="A."><?php echo esc_html__( "A.", 'quiz-maker' ); ?></option>
                                                    <option value="A)"><?php echo esc_html__( "A)", 'quiz-maker' ); ?></option>
                                                    <option value="a."><?php echo esc_html__( "a.", 'quiz-maker' ); ?></option>
                                                    <option value="a)"><?php echo esc_html__( "a)", 'quiz-maker' ); ?></option>
                                                </select>
                                            </div>
                                        </div> <!-- Show questions numbering -->
                                        <hr/>
                                        <div class="form-group row">
                                            <div class="col-sm-4">
                                                <label for="ays_quick_quiz_answers_view">
                                                    <?php echo esc_html__('Answers view','quiz-maker'); ?>
                                                </label>
                                            </div>
                                            <div class="col-sm-8">
                                                <select class="ays-text-input ays-text-input-short ays-enable-timerl" id="ays_quick_quiz_answers_view" name="ays_quick_quiz_answers_view">
                                                    <option value="list" selected>
                                                        <?php echo esc_html__('List','quiz-maker'); ?>
                                                    </option>
                                                    <option value="grid">
                                                        <?php echo esc_html__('Grid','quiz-maker'); ?>
                                                    </option>
                                                </select>
                                            </div>
                                        </div> <!-- Answers view  -->
                                        <hr/>
                                        <div class="form-group row">
                                            <div class="col-sm-4">
                                                <label>
                                                    <?php echo esc_html__('Show messages for right/wrong answers','quiz-maker'); ?>
                                                </label>
                                            </div>
                                            <div class="col-sm-8">
                                                <select id="ays_quick_quiz_answers_rw_texts" name="ays_quick_quiz_answers_rw_texts" class="ays-text-input ays-text-input-short">
                                                    <option value="on_passing" selected><?php echo esc_html__( "During the quiz", 'quiz-maker' ); ?></option>
                                                    <option value="on_results_page"><?php echo esc_html__( 'On results page', 'quiz-maker'); ?></option>
                                                    <option value="on_both"><?php echo esc_html__( 'On Both', 'quiz-maker'); ?></option>
                                                    <option value="disable"><?php echo esc_html__( 'Disable', 'quiz-maker'); ?></option>
                                                </select>
                                            </div>
                                        </div> <!-- Show messages for right/wrong answers  -->
                                    </div>
                                </div><!-- Settings Tab -->
                                <div class="ays-quiz-accordion-options-main-container" data-collapsed="false">
                                    <div class="ays-quiz-accordion-container">
                                        <?php echo wp_kses($quiz_accordion_svg_html, $svg_sanitize_properties); ?>
                                        <p class="ays-subtitle"><?php echo esc_html__('Results Settings','quiz-maker'); ?></p>
                                    </div>
                                    <hr class="ays-quiz-bolder-hr"/>
                                    <div class="ays-quiz-accordion-options-box">
                                        <div class="form-group row">
                                            <div class="col-sm-4">
                                                <label for="ays_quick_quiz_hide_score">
                                                    <?php echo esc_html__('Hide score','quiz-maker'); ?>
                                                </label>
                                            </div>
                                            <div class="col-sm-8">
                                                <input type="checkbox" class="ays-enable-timer1" id="ays_quick_quiz_hide_score" name="ays_quick_quiz_hide_score" value="on" />
                                            </div>
                                        </div><!-- Hide score -->
                                        <hr/>
                                        <div class="form-group row ays_toggle_parent">
                                            <div class="col-sm-4">
                                                <label for="ays_quick_quiz_enable_restart_button">
                                                    <?php echo esc_html__('Enable restart button','quiz-maker'); ?>
                                                </label>
                                            </div>
                                            <div class="col-sm-1">
                                                <input type="checkbox" class="ays-enable-timer1 ays_toggle_checkbox" id="ays_quick_quiz_enable_restart_button" name="ays_quick_quiz_enable_restart_button" value="on" checked />
                                            </div>
                                            <div class="col-sm-7 ays_toggle_target ays_divider_left">
                                                <div class="form-group row">
                                                    <div class="col-sm-4">
                                                        <label for="ays_quick_quiz_show_restart_button_on_quiz_fail">
                                                            <?php echo esc_html__('Show button on quiz fail', 'quiz-maker'); ?>
                                                        </label>
                                                    </div>
                                                    <div class="col-sm-8">
                                                        <input type="checkbox" class="ays-enable-timer1" id="ays_quick_quiz_show_restart_button_on_quiz_fail" name="ays_quick_quiz_show_restart_button_on_quiz_fail" value="on"/>
                                                    </div>
                                                </div>
                                            </div><!-- Show button on quiz fail -->
                                        </div><!-- Enable restart button -->
                                        <hr/>
                                        <div class="form-group row">
                                            <div class="col-sm-4">
                                                <label for="ays_quick_quiz_enable_progress_bar">
                                                    <?php echo esc_html__('Enable progress bar','quiz-maker'); ?>
                                                </label>
                                            </div>
                                            <div class="col-sm-8">
                                                <input type="checkbox" class="ays-enable-timer1" id="ays_quick_quiz_enable_progress_bar" name="ays_quick_quiz_enable_progress_bar" value="on" checked />
                                            </div>
                                        </div><!-- Enable progress bar -->
                                        <hr/>
                                        <div class="form-group row ays_toggle_parent">
                                            <div class="col-sm-4">
                                                <label for="ays_quick_quiz_enable_quiz_rate">
                                                    <?php echo esc_html__('Enable quiz assessment','quiz-maker'); ?>
                                                </label>
                                            </div>
                                            <div class="col-sm-1">
                                                <input type="checkbox" class="ays-enable-timer1 ays_toggle_checkbox" id="ays_quick_quiz_enable_quiz_rate" name="ays_quick_quiz_enable_quiz_rate" value="on" />
                                            </div>
                                            <div class="col-sm-7 ays_toggle_target ays_divider_left display_none">
                                                <div class="form-group row">
                                                    <div class="col-sm-4">
                                                        <label for="ays_quick_quiz_enable_rate_comments">
                                                            <?php echo esc_html__('Show the last 5 reviews', 'quiz-maker'); ?>
                                                        </label>
                                                    </div>
                                                    <div class="col-sm-8">
                                                        <input type="checkbox" id="ays_quick_quiz_enable_rate_comments" name="ays_quick_quiz_enable_rate_comments" value="on" />
                                                    </div>
                                                </div>
                                                <hr>
                                                <div class="form-group row">
                                                    <div class="col-sm-4">
                                                        <label for="ays_quick_quiz_make_responses_anonymous">
                                                            <?php echo esc_html__('Make responses anonymous','quiz-maker'); ?>
                                                        </label>
                                                    </div>
                                                    <div class="col-sm-8">
                                                        <input type="checkbox" name="ays_quick_quiz_make_responses_anonymous" id="ays_quick_quiz_make_responses_anonymous" value="on"/>
                                                    </div>
                                                </div>
                                                <hr>
                                                <div class="form-group row">
                                                    <div class="col-sm-4">
                                                        <label for="ays_quick_quiz_enable_user_cհoosing_anonymous_assessment">
                                                            <?php echo esc_html__("Enable users' anonymous assessment",'quiz-maker'); ?>
                                                        </label>
                                                    </div>
                                                    <div class="col-sm-8">
                                                        <input type="checkbox" name="ays_quick_quiz_enable_user_cհoosing_anonymous_assessment" id="ays_quick_quiz_enable_user_cհoosing_anonymous_assessment"/>
                                                    </div>
                                                </div>
                                                <hr>
                                                <div class="form-group row">
                                                    <div class="col-sm-4">
                                                        <label for="ays_quick_quiz_make_all_review_link">
                                                            <?php echo esc_html__('Display all reviews button','quiz-maker'); ?>
                                                        </label>
                                                    </div>
                                                    <div class="col-sm-8">
                                                        <input type="checkbox" name="ays_quick_quiz_make_all_review_link" id="ays_quick_quiz_make_all_review_link"/>
                                                    </div>
                                                </div>
                                                <hr>
                                                <div class="form-group row">
                                                    <div class="col-sm-4">
                                                        <label for="ays_quick_quiz_review_enable_comment_field">
                                                            <?php echo esc_html__('Enable Comment Field','quiz-maker'); ?>
                                                        </label>
                                                    </div>
                                                    <div class="col-sm-8">
                                                        <input type="checkbox" name="ays_quick_quiz_review_enable_comment_field" id="ays_quick_quiz_review_enable_comment_field" checked />
                                                    </div>
                                                </div>
                                                <hr>
                                                <div class="form-group row">
                                                    <div class="col-sm-4">
                                                        <label for="ays_quick_quiz_make_review_required">
                                                            <?php echo esc_html__('Make the review field required','quiz-maker'); ?>
                                                        </label>
                                                    </div>
                                                    <div class="col-sm-8">
                                                        <input type="checkbox" name="ays_quick_quiz_make_review_required" id="ays_quick_quiz_make_review_required" />
                                                    </div>
                                                </div>
                                                <hr/>
                                                <div class="form-group row">
                                                    <div class="col-sm-4">
                                                        <label for="ays_quick_quiz_review_placeholder_text">
                                                            <?php echo esc_html__('Placeholder text','quiz-maker'); ?>
                                                        </label>
                                                    </div>
                                                    <div class="col-sm-8">
                                                        <input type="text" class="ays-text-input" id="ays_quick_quiz_review_placeholder_text" name="ays_quick_quiz_review_placeholder_text" value=""/>
                                                    </div>
                                                </div>
                                            </div>
                                        </div><!-- Enable quiz assessment -->
                                        <hr/>
                                        <div class="form-group row">
                                            <div class="col-sm-4">
                                                <label for="ays_quick_quiz_enable_average_statistical">
                                                    <?php echo esc_html__('Show the statistical average','quiz-maker'); ?>
                                                </label>
                                            </div>
                                            <div class="col-sm-8">
                                                <input type="checkbox" class="ays-enable-timer1" id="ays_quick_quiz_enable_average_statistical" name="ays_quick_quiz_enable_average_statistical" value="on" checked />
                                            </div>
                                        </div><!-- Show the statistical average -->
                                        <hr/>
                                        <div class="form-group row">
                                            <div class="col-sm-4">
                                                <label for="ays_quick_quiz_disable_store_data">
                                                    <?php echo esc_html__('Disable data storing in database','quiz-maker'); ?>
                                                </label>
                                                <p class="ays_quiz_small_hint_text_for_not_recommended" style="margin: 0;">
                                                    <span><?php echo esc_html__( "Not recommended" , 'quiz-maker' ); ?></span>
                                                </p>
                                            </div>
                                            <div class="col-sm-8">
                                                <input type="checkbox" class="ays-enable-timer1" id="ays_quick_quiz_disable_store_data" name="ays_quick_quiz_disable_store_data" value="on" />
                                            </div>
                                        </div><!-- Disable data storing in database -->
                                        <hr/>
                                        <div class="form-group row ays_toggle_parent">
                                            <div class="col-sm-4">
                                                <label for="ays_quick_quiz_enable_questions_result">
                                                    <?php echo esc_html__('Show question results on the results page','quiz-maker'); ?>
                                                </label>
                                            </div>
                                            <div class="col-sm-1">
                                                <input type="checkbox" class="ays-enable-timer1 ays_toggle_checkbox" id="ays_quick_quiz_enable_questions_result" name="ays_quick_quiz_enable_questions_result" value="on" checked />
                                            </div>
                                            <div class="col-sm-7 ays_toggle_target ays_divider_left">
                                                <div class="form-group row">
                                                    <div class="col-sm-4">
                                                        <label for="ays_quick_quiz_hide_correct_answers">
                                                            <?php echo esc_html__('Hide correct answers','quiz-maker'); ?>
                                                        </label>
                                                    </div>
                                                    <div class="col-sm-8">
                                                        <input type="checkbox" class="ays-enable-timer1" id="ays_quick_quiz_hide_correct_answers" name="ays_quick_quiz_hide_correct_answers" value="on"/>
                                                    </div>
                                                </div>
                                                <hr>
                                                <div class="form-group row">
                                                    <div class="col-sm-4">
                                                        <label for="ays_quick_quiz_show_wrong_answers_first">
                                                            <?php echo esc_html__('Show wrong answers first','quiz-maker'); ?>
                                                        </label>
                                                    </div>
                                                    <div class="col-sm-8">
                                                        <input type="checkbox" class="ays-enable-timer1" id="ays_quick_quiz_show_wrong_answers_first" name="ays_quick_quiz_show_wrong_answers_first" value="on" />
                                                    </div>
                                                </div>
                                                <hr>
                                                <div class="form-group row">
                                                    <div class="col-sm-4">
                                                        <label for="ays_quick_quiz_show_only_wrong_answers">
                                                            <?php echo esc_html__('Show only wrong answers','quiz-maker'); ?>
                                                        </label>
                                                    </div>
                                                    <div class="col-sm-8">
                                                        <input type="checkbox" class="ays-enable-timer1" id="ays_quick_quiz_show_only_wrong_answers" name="ays_quick_quiz_show_only_wrong_answers" value="on" />
                                                    </div>
                                                </div>
                                                <hr>
                                                <div class="form-group row ays_toggle_parent">
                                                    <div class="col-sm-4">
                                                        <label for="ays_quick_quiz_enable_results_toggle">
                                                            <?php echo esc_html__('Enable the Show/Hide toggle','quiz-maker'); ?>
                                                        </label>
                                                    </div>
                                                    <div class="col-sm-1">
                                                        <input type="checkbox" class="ays-enable-timer1 ays_toggle_checkbox" id="ays_quick_quiz_enable_results_toggle" name="ays_quick_quiz_enable_results_toggle" value="on" />
                                                    </div>
                                                    <div class="col-sm-7 ays_toggle_target ays_divider_left display_none">
                                                        <div class="form-group row">
                                                            <div class="col-sm-4">
                                                                <label for="ays_quick_quiz_enable_default_hide_results_toggle">
                                                                    <?php echo esc_html__('Enable Default Hide', 'quiz-maker'); ?>
                                                                </label>
                                                            </div>
                                                            <div class="col-sm-8">
                                                                <input type="checkbox" class="ays-enable-timer1" id="ays_quick_quiz_enable_default_hide_results_toggle" name="ays_quick_quiz_enable_default_hide_results_toggle" value="on" />
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div><!-- Show question results on the results page -->
                                        <hr/>
                                        <div class="form-group row">
                                            <div class="col-sm-4">
                                                <label for="ays_quick_quiz_display_score">
                                                    <?php echo esc_html__('Display score','quiz-maker'); ?>
                                                </label>
                                            </div>
                                            <div class="col-sm-8">
                                                <select name="ays_quick_quiz_display_score" class="ays-text-input ays-text-input-short" id="ays_quick_quiz_display_score">
                                                    <option value="by_percantage" selected><?php echo esc_html__( "By percentage", 'quiz-maker' ); ?></option>
                                                    <option value="by_correctness"><?php echo esc_html__( "By correct answers count", 'quiz-maker' ); ?></option>
                                                </select>
                                            </div>
                                        </div> <!-- Display score -->
                                    </div>
                                </div><!-- Results Settings Tab -->
                                <div class="ays-quiz-accordion-options-main-container" data-collapsed="false">
                                    <div class="ays-quiz-accordion-container">
                                        <?php echo wp_kses($quiz_accordion_svg_html, $svg_sanitize_properties); ?>
                                        <p class="ays-subtitle"><?php echo esc_html__('User Data','quiz-maker'); ?></p>
                                    </div>
                                    <hr class="ays-quiz-bolder-hr"/>
                                    <div class="ays-quiz-accordion-options-box">
                                        <div class="form-group row">
                                            <div class="col-sm-4">
                                                <label for="ays_quick_quiz_show_information_form">
                                                    <?php echo esc_html__('Show Information Form to logged-in users', 'quiz-maker'); ?>
                                                </label>
                                            </div>
                                            <div class="col-sm-8">
                                                <div class="information_form_settings">
                                                    <input type="checkbox" id="ays_quick_quiz_show_information_form" name="ays_quick_quiz_show_information_form" value="on" checked />
                                                </div>
                                            </div>
                                        </div><!-- Show Information Form to logged-in users -->
                                        <hr>
                                        <div class="form-group row">
                                            <div class="col-sm-4">
                                                <label for="ays_quick_quiz_autofill_user_data">
                                                    <?php echo esc_html__('Autofill logged-in user data','quiz-maker'); ?>
                                                </label>
                                            </div>
                                            <div class="col-sm-8">
                                                <div class="information_form_settings">
                                                    <input type="checkbox" id="ays_quick_quiz_autofill_user_data" name="ays_quick_quiz_autofill_user_data" value="on" />
                                                </div>
                                            </div>
                                        </div><!-- Autofill logged-in user data -->
                                        <hr>
                                        <div class="form-group row">
                                            <div class="col-sm-4">
                                                <label for="ays_quick_quiz_display_fields_labels">
                                                    <?php echo esc_html__('Display form fields with labels',"quiz-maker"); ?>
                                                </label>
                                            </div>
                                            <div class="col-sm-8">
                                                <div class="information_form_settings">
                                                    <input type="checkbox" id="ays_quick_quiz_display_fields_labels" name="ays_quick_quiz_display_fields_labels" value="on" />
                                                </div>
                                            </div>
                                        </div><!-- Display form fields with labels -->
                                    </div>
                                </div><!-- User Data Tab -->
                                <div class="ays-quiz-accordion-options-main-container" data-collapsed="false">
                                    <div class="ays-quiz-accordion-container">
                                        <?php echo wp_kses($quiz_accordion_svg_html, $svg_sanitize_properties); ?>
                                        <p class="ays-subtitle"><?php echo esc_html__('Styles','quiz-maker'); ?></p>
                                    </div>
                                    <hr class="ays-quiz-bolder-hr"/>
                                    <div class="ays-quiz-accordion-options-box">
                                        <div class="form-group row">
                                            <div class="col-sm-4">
                                                <label for="ays_quick_quiz_width">
                                                    <?php echo esc_html__('Quiz width',"quiz-maker"); ?>
                                                </label>
                                            </div>
                                            <div class="col-sm-8">
                                                <div class="ays_quiz_display_flex_width">
                                                    <div>
                                                        <input type="number" class="ays-text-input ays-text-input-short" id='ays_quick_quiz_width' name='ays_quick_quiz_width' value="800"/>
                                                        <span style="display:block;" class="ays_quiz_small_hint_text"><?php echo esc_html__("For 100% leave blank", "quiz-maker");?></span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div><!-- Quiz width -->
                                        <hr/>
                                        <div class="form-group row">
                                            <div class="col-sm-4">
                                                <label for='ays_quick_quiz_height'>
                                                    <?php echo esc_html__('Quiz min-height', "quiz-maker"); ?>
                                                </label>
                                            </div>
                                            <div class="col-sm-8 ays_quiz_display_flex_width">
                                                <div>
                                                    <input type="number" class="ays-text-input ays-text-input-short" id='ays_quick_quiz_height' name='ays_quick_quiz_height' value="450"/>
                                                </div>
                                                <div class="ays_quiz_dropdown_max_width">
                                                    <input type="text" value="px" class='ays-quiz-form-hint-for-size' disabled>
                                                </div>
                                            </div>
                                        </div><!-- Quiz min-height -->
                                        <hr/> 
                                        <div class="form-group row">
                                            <div class="col-sm-4">
                                                <label for="ays_quick_quiz_border_radius">
                                                    <?php echo esc_html__('Border radius','quiz-maker'); ?>
                                                </label>
                                            </div>
                                            <div class="col-sm-8 ays_quiz_display_flex_width">
                                                <div>
                                                    <input type="number" class="ays-text-input ays-text-input-short" id="ays_quick_quiz_border_radius" name="ays_quick_quiz_border_radius" value="8"/>
                                                </div>
                                                <div class="ays_quiz_dropdown_max_width ays-display-flex" style="align-items: end;">
                                                    <input type="text" value="px" class='ays-quiz-form-hint-for-size' disabled>
                                                </div>
                                            </div>
                                        </div><!-- Border radius -->
                                        <hr/>
                                        <div class="form-group row">
                                            <div class="col-sm-4">
                                                <label for='ays_quick_quiz_image_height'>
                                                    <?php echo esc_html__('Quiz image height', 'quiz-maker'); ?>
                                                </label>
                                            </div>
                                            <div class="col-sm-8 ays_quiz_display_flex_width">
                                                <div>
                                                    <input type="number" class="ays-text-input ays-text-input-short" id='ays_quick_quiz_image_height' name='ays_quick_quiz_image_height' value=""/>
                                                </div>
                                                <div class="ays_quiz_dropdown_max_width">
                                                    <input type="text" value="px" class='ays-quiz-form-hint-for-size' disabled>
                                                </div>
                                            </div>
                                        </div><!-- Quiz image height -->
                                        <hr/>
                                        <div class="form-group row">
                                            <div class="col-sm-4">
                                                <label for="ays_quick_quiz_progress_bar_style">
                                                    <?php echo esc_html__('Progress bar style','quiz-maker'); ?>
                                                </label>
                                            </div>
                                            <div class="col-sm-8">
                                                <select id="ays_quick_quiz_progress_bar_style" name="ays_quick_quiz_progress_bar_style" class="ays-text-input ays-text-input-short">
                                                    <option value="first"><?php echo esc_html__( 'Rounded', 'quiz-maker'); ?></option>
                                                    <option value="second"><?php echo esc_html__( 'Rectangle', 'quiz-maker'); ?></option>
                                                    <option selected value="third"><?php echo esc_html__( 'With stripes', 'quiz-maker'); ?></option>
                                                    <option value="fourth"><?php echo esc_html__( 'With stripes and animation', 'quiz-maker'); ?></option>
                                                </select>
                                            </div>
                                        </div><!-- Progress bar style -->
                                        <hr>
                                        <div class="form-group row">
                                            <div class="col-sm-4">
                                                <label for="ays_quick_quiz_progress_live_bar_style">
                                                    <?php echo esc_html__('Progress live bar style','quiz-maker'); ?>
                                                </label>
                                            </div>
                                            <div class="col-sm-8">
                                                <select id="ays_quick_quiz_progress_live_bar_style" name="ays_quick_quiz_progress_live_bar_style" class="ays-text-input ays-text-input-short">
                                                    <option selected value="default"><?php echo esc_html__( 'Default', 'quiz-maker'); ?></option>
                                                    <option value="second"><?php echo esc_html__( 'Rectangle', 'quiz-maker'); ?></option>
                                                    <option value="third"><?php echo esc_html__( 'With stripes', 'quiz-maker'); ?></option>
                                                    <option value="fourth"><?php echo esc_html__( 'With stripes and animation', 'quiz-maker'); ?></option>
                                                </select>
                                            </div>
                                        </div><!-- Progress live bar style -->
                                        <hr/>
                                        <div class="form-group row">
                                            <div class="col-sm-4">
                                                <label for="ays_quick_quiz_buttons_position">
                                                    <?php echo esc_html__('Buttons position','quiz-maker'); ?>
                                                </label>
                                            </div>
                                            <div class="col-sm-8">
                                                <select id="ays_quick_quiz_buttons_position" name="ays_quick_quiz_buttons_position" class="ays-text-input ays-text-input-short">
                                                    <option selected value="center"><?php echo esc_html__( 'Center', 'quiz-maker'); ?></option>
                                                    <option value="flex-start"><?php echo esc_html__( 'Left', 'quiz-maker'); ?></option>
                                                    <option value="flex-end"><?php echo esc_html__( 'Right', 'quiz-maker'); ?></option>
                                                    <option value="space-between"><?php echo esc_html__( 'Space Between', 'quiz-maker'); ?></option>
                                                </select>
                                            </div>
                                        </div><!-- Buttons position -->
                                        <hr/>
                                        <div class="form-group row">
                                            <div class="col-sm-4">
                                                <label for="ays_quick_quiz_title_transformation">
                                                    <?php echo esc_html__('Quiz title transformation', 'quiz-maker' ); ?>
                                                </label>
                                            </div>
                                            <div class="col-sm-8">
                                                <select name="ays_quick_quiz_title_transformation" id="ays_quick_quiz_title_transformation" class="ays-text-input ays-text-input-short" style="display:block;">
                                                    <option value="uppercase" selected><?php echo esc_html__( "Uppercase", 'quiz-maker' ); ?></option>
                                                    <option value="lowercase"><?php echo esc_html__( "Lowercase", 'quiz-maker' ); ?></option>
                                                    <option value="capitalize"><?php echo esc_html__( "Capitalize", 'quiz-maker' ); ?></option>
                                                    <option value="none"><?php echo esc_html__( "None", 'quiz-maker' ); ?></option>
                                                </select>
                                            </div>
                                        </div><!-- Quiz title transformation -->
                                        <hr/>
                                        <div class="form-group row">
                                            <div class="col-sm-4">
                                                <label for='ays_quick_quiz_title_font_size'>
                                                    <?php echo esc_html__('Quiz title font size', 'quiz-maker'); ?>
                                                </label>
                                            </div>
                                            <div class="col-sm-8">
                                                <div class="row">
                                                    <div class="col-sm-12">
                                                        <div style="margin-bottom: 10px;">
                                                            <label for='ays_quick_quiz_title_font_size'>
                                                                <?php echo esc_html__('On desktop', 'quiz-maker'); ?>
                                                            </label>
                                                        </div>
                                                        <div class="ays_quiz_display_flex_width">
                                                            <div>
                                                                <input type="number" class="ays-text-input" id='ays_quick_quiz_title_font_size' name='ays_quick_quiz_title_font_size' value="28"/>
                                                            </div>
                                                            <div class="ays_quiz_dropdown_max_width">
                                                                <input type="text" value="px" class='ays-quiz-form-hint-for-size' disabled>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <hr>
                                                <div class="row">
                                                    <div class="col-sm-12">
                                                        <div style="margin-bottom: 10px;">
                                                            <label for='ays_quick_quiz_title_mobile_font_size'>
                                                                <?php echo esc_html__('On mobile', 'quiz-maker'); ?>
                                                            </label>
                                                        </div>
                                                        <div class="ays_quiz_display_flex_width">
                                                            <div>
                                                                <input type="number" class="ays-text-input" id='ays_quick_quiz_title_mobile_font_size' name='ays_quick_quiz_title_mobile_font_size' value="20"/>
                                                            </div>
                                                            <div class="ays_quiz_dropdown_max_width">
                                                                <input type="text" value="px" class='ays-quiz-form-hint-for-size' disabled>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div><!-- Quiz title font size -->
                                        <hr/>
                                        <div class="form-group row">
                                            <div class="col-sm-4">
                                                <label for="ays_quick_quiz_custom_class">
                                                    <?php echo esc_html__('Custom class for quiz container','quiz-maker'); ?>
                                                </label>
                                            </div>
                                            <div class="col-sm-8">
                                                <input type="text" class="ays-text-input ays-text-input-short" name="ays_quick_quiz_custom_class" id="ays_quick_quiz_custom_class" placeholder="myClass myAnotherClass..." value="">
                                            </div>
                                        </div><!-- Custom class for quiz container -->
                                        <hr/>
                                        <div class="form-group row">
                                            <div class="col-sm-4">
                                                <label for='ays_quick_quiz_quest_animation'>
                                                    <?php echo esc_html__('Animation effect', 'quiz-maker'); ?>
                                                </label>
                                            </div>
                                            <div class="col-sm-8">
                                                <select class="ays-text-input ays-text-input-short" name="ays_quick_quiz_quest_animation" id="ays_quick_quiz_quest_animation">
                                                    <option value="none"><?php echo esc_html__('None', 'quiz-maker'); ?></option>
                                                    <option value="fade"><?php echo esc_html__('Fade', 'quiz-maker'); ?></option>
                                                    <option value="shake" selected><?php echo esc_html__('Shake', 'quiz-maker'); ?></option>
                                                </select>
                                            </div>
                                        </div><!-- Animation effect -->
                                        <hr/>
                                        <div class="form-group row">
                                            <div class="col-sm-4">
                                                <label for='ays_quick_quiz_question_font_size'>
                                                    <?php echo esc_html__('Question font size', 'quiz-maker'); ?>
                                                </label>
                                            </div>
                                            <div class="col-sm-8">
                                                <div class="row">
                                                    <div class="col-sm-12">
                                                        <div style="margin-bottom: 10px;">
                                                            <label for='ays_quick_quiz_question_font_size'>
                                                                <?php echo esc_html__('On desktop', 'quiz-maker'); ?>
                                                            </label>
                                                        </div>
                                                        <div class="ays_quiz_display_flex_width">
                                                            <div>
                                                                <input type="number" class="ays-text-input" id='ays_quick_quiz_question_font_size' name='ays_quick_quiz_question_font_size' value="16"/>
                                                            </div>
                                                            <div class="ays_quiz_dropdown_max_width">
                                                                <input type="text" value="px" class='ays-quiz-form-hint-for-size' disabled>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <hr>
                                                <div class="row">
                                                    <div class="col-sm-12">
                                                        <div style="margin-bottom: 10px;">
                                                            <label for='ays_quick_quiz_question_mobile_font_size'>
                                                                <?php echo esc_html__('On mobile', 'quiz-maker'); ?>
                                                            </label>
                                                        </div>
                                                        <div class="ays_quiz_display_flex_width">
                                                            <div>
                                                                <input type="number" class="ays-text-input" id='ays_quick_quiz_question_mobile_font_size' name='ays_quick_quiz_question_mobile_font_size' value="16"/>
                                                            </div>
                                                            <div class="ays_quiz_dropdown_max_width">
                                                                <input type="text" value="px" class='ays-quiz-form-hint-for-size' disabled>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div><!-- Question font size -->
                                        <hr/>
                                        <div class="form-group row">
                                            <div class="col-sm-4">
                                                <label for="ays_quick_quiz_question_text_alignment">
                                                    <?php echo esc_html__( 'Question text alignment', 'quiz-maker' ); ?>
                                                </label>
                                            </div>
                                            <div class="col-sm-8">
                                                <select name="ays_quick_quiz_question_text_alignment" id="ays_quick_quiz_question_text_alignment" class="ays-text-input ays-text-input-short" style="display:block;">
                                                    <option value="left"><?php echo esc_html__( "Left", 'quiz-maker' ); ?></option>
                                                    <option value="center" selected><?php echo esc_html__( "Center", 'quiz-maker' ); ?></option>
                                                    <option value="right"><?php echo esc_html__( "Right", 'quiz-maker' ); ?></option>
                                                </select>
                                            </div>
                                        </div><!-- Question text alignment -->
                                        <hr/>
                                        <div class="form-group row">
                                            <div class="col-sm-4">
                                                <label for='ays_quick_quiz_image_width'>
                                                    <?php echo esc_html__('Question image styles', 'quiz-maker'); ?>
                                                </label>
                                            </div>
                                            <div class="col-sm-8">
                                                <div class="row">
                                                    <div class="col-sm-12">
                                                        <div style="margin-bottom: 10px;">
                                                            <label for='ays_quick_quiz_image_width'>
                                                                <?php echo esc_html__('Image Width', 'quiz-maker'); ?>
                                                            </label>
                                                        </div>
                                                        <div class="ays_quiz_display_flex_width">
                                                            <div>
                                                                <input type="number" class="ays-text-input" id='ays_quick_quiz_image_width' name='ays_quick_quiz_image_width' value=""/>
                                                                <span class="ays_quiz_small_hint_text"><?php echo esc_html__("For 100% leave blank", 'quiz-maker');?></span>
                                                            </div>
                                                            <div class="ays_quiz_dropdown_max_width ays-display-flex" style="align-items: flex-start;">
                                                                <select id="ays_quick_quiz_image_width_by_percentage_px" name="ays_quick_quiz_image_width_by_percentage_px" class="ays-text-input ays-text-input-short" style="display:inline-block; width: 60px;">
                                                                    <option value="pixels" selected><?php echo esc_html__( "px", 'quiz-maker' ); ?></option>
                                                                    <option value="percentage"><?php echo esc_html__( "%", 'quiz-maker' ); ?></option>
                                                                </select>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <hr/>
                                                <div class="form-group row">
                                                    <div class="col-sm-12">
                                                        <div style="margin-bottom: 10px;">
                                                            <label for="ays_quick_quiz_image_height">
                                                                <?php echo esc_html__('Image Height','quiz-maker'); ?>
                                                            </label>
                                                        </div>
                                                        <div class="ays_quiz_display_flex_width">
                                                            <div>
                                                                <input type="number" class="ays-text-input ays-text-input-short" id="ays_quick_quiz_image_height" name="ays_quick_quiz_image_height" value=""/>
                                                            </div>
                                                            <div class="ays_quiz_dropdown_max_width ays-display-flex" style="align-items: end;">
                                                                <input type="text" value="px" class='ays-quiz-form-hint-for-size' disabled>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <hr />
                                                <div class="form-group row">
                                                    <div class="col-sm-12">
                                                        <div style="margin-bottom: 10px;">
                                                            <label for="ays_quick_quiz_image_sizing">
                                                                <?php echo esc_html__('Image sizing', 'quiz-maker' ); ?>
                                                            </label>
                                                        </div>
                                                        <select name="ays_quick_quiz_image_sizing" id="ays_quick_quiz_image_sizing" class="ays-text-input ays-text-input-short" style="display:block;">
                                                            <option value="cover" selected><?php echo esc_html__( "Cover", 'quiz-maker' ); ?></option>
                                                            <option value="contain"><?php echo esc_html__( "Contain", 'quiz-maker' ); ?></option>
                                                            <option value="none"><?php echo esc_html__( "None", 'quiz-maker' ); ?></option>
                                                            <option value="unset"><?php echo esc_html__( "Unset", 'quiz-maker' ); ?></option>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                        </div><!-- Question image styles -->
                                        <hr />
                                        <div class="form-group row">
                                            <div class="col-sm-4">
                                                <label for='ays_quick_quiz_answers_font_size'>
                                                    <?php echo esc_html__('Answer font size', 'quiz-maker'); ?>
                                                </label>
                                            </div>
                                            <div class="col-sm-8">
                                                <div class="row">
                                                    <div class="col-sm-12">
                                                        <div style="margin-bottom: 10px;">
                                                            <label for='ays_quick_quiz_answers_font_size'>
                                                                <?php echo esc_html__('On desktop', 'quiz-maker'); ?>
                                                            </label>
                                                        </div>
                                                        <div class="ays_quiz_display_flex_width">
                                                            <div>
                                                                <input type="number" class="ays-text-input" id='ays_quick_quiz_answers_font_size' name='ays_quick_quiz_answers_font_size' value="15"/>
                                                            </div>
                                                            <div class="ays_quiz_dropdown_max_width">
                                                                <input type="text" value="px" class='ays-quiz-form-hint-for-size' disabled>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <hr />
                                                <div class="row">
                                                    <div class="col-sm-12">
                                                        <div style="margin-bottom: 10px;">
                                                            <label for='ays_quick_quiz_answers_mobile_font_size'>
                                                                <?php echo esc_html__('On mobile', 'quiz-maker'); ?>
                                                            </label>
                                                        </div>
                                                        <div class="ays_quiz_display_flex_width">
                                                            <div>
                                                                <input type="number" class="ays-text-input" id='ays_quick_quiz_answers_mobile_font_size' name='ays_quick_quiz_answers_mobile_font_size' value="15"/>
                                                            </div>
                                                            <div class="ays_quiz_dropdown_max_width">
                                                                <input type="text" value="px" class='ays-quiz-form-hint-for-size' disabled>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div><!-- Answer font size -->
                                        <hr/>
                                        <div class="form-group row">
                                            <div class="col-sm-4">
                                                <label for="ays_quick_quiz_answers_margin">
                                                    <?php echo esc_html__('Answer gap','quiz-maker'); ?>
                                                </label>
                                            </div>
                                            <div class="col-sm-8 ays_quiz_display_flex_width">
                                                <div>
                                                    <input type="number" class="ays-text-input ays-text-input-short" id='ays_quick_quiz_answers_margin' name='ays_quick_quiz_answers_margin' value="12"/>
                                                </div>
                                                <div class="ays_quiz_dropdown_max_width ays-display-flex" style="align-items: end;">
                                                    <input type="text" value="px" class='ays-quiz-form-hint-for-size' disabled>
                                                </div>
                                            </div>
                                        </div><!-- Answers gap -->
                                        <hr/>
                                        <div class="form-group row">
                                            <div class="col-sm-4">
                                                <label for="ays_quick_quiz_disable_hover_effect">
                                                    <?php echo esc_html__('Disable answer hover','quiz-maker'); ?>
                                                </label>
                                            </div>
                                            <div class="col-sm-8">
                                                <input type="checkbox" id="ays_quick_quiz_disable_hover_effect" name="ays_quick_quiz_disable_hover_effect" value="on" />
                                            </div>
                                        </div><!-- Disable answer hover -->
                                    </div>
                                </div><!-- Styles Tab -->
                                <div class="ays-quiz-accordion-options-main-container" data-collapsed="false">
                                    <div class="ays-quiz-accordion-container">
                                        <?php echo wp_kses($quiz_accordion_svg_html, $svg_sanitize_properties); ?>
                                        <p class="ays-subtitle"><?php echo esc_html__('Buttons Styles','quiz-maker'); ?></p>
                                    </div>
                                    <hr class="ays-quiz-bolder-hr"/>
                                    <div class="ays-quiz-accordion-options-box">
                                        <div class="form-group row">
                                            <div class="col-sm-4">
                                                <label for="ays_quick_quiz_buttons_size">
                                                    <?php echo esc_html__('Button size','quiz-maker'); ?>
                                                </label>
                                            </div>
                                            <div class="col-sm-8">
                                                <select class="ays-text-input ays-text-input-short" id="ays_quick_quiz_buttons_size" name="ays_quick_quiz_buttons_size">
                                                    <option value="small">
                                                        <?php echo esc_html__('Small','quiz-maker'); ?>
                                                    </option>
                                                    <option value="medium">
                                                        <?php echo esc_html__('Medium','quiz-maker'); ?>
                                                    </option>
                                                    <option value="large" selected>
                                                        <?php echo esc_html__('Large','quiz-maker'); ?>
                                                    </option>
                                                </select>
                                            </div>
                                        </div><!-- Button size -->
                                        <hr>
                                        <div class="form-group row">
                                            <div class="col-sm-4">
                                                <label for='ays_quick_quiz_buttons_font_size'>
                                                    <?php echo esc_html__('Button font-size', 'quiz-maker'); ?>
                                                </label>
                                            </div>
                                            <div class="col-sm-8">
                                                <div class="row">
                                                    <div class="col-sm-12">
                                                        <div style="margin-bottom: 10px;">
                                                            <label for='ays_quick_quiz_buttons_font_size'>
                                                                <?php echo esc_html__('On desktop', 'quiz-maker'); ?>
                                                            </label>
                                                        </div>
                                                        <div class="ays_quiz_display_flex_width">
                                                            <div>
                                                                <input type="number" class="ays-text-input" id='ays_quick_quiz_buttons_font_size' name='ays_quick_quiz_buttons_font_size' value="18" />
                                                            </div>
                                                            <div class="ays_quiz_dropdown_max_width ays-display-flex" style="align-items: end;">
                                                                <input type="text" value="px" class='ays-quiz-form-hint-for-size' disabled>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <hr>
                                                <div class="row">
                                                    <div class="col-sm-12">
                                                        <div style="margin-bottom: 10px;">
                                                            <label for='ays_quick_quiz_buttons_mobile_font_size'>
                                                                <?php echo esc_html__('On mobile', 'quiz-maker'); ?>
                                                            </label>
                                                        </div>
                                                        <div class="ays_quiz_display_flex_width">
                                                            <div>
                                                                <input type="number" class="ays-text-input" id='ays_quick_quiz_buttons_mobile_font_size'name='ays_quick_quiz_buttons_mobile_font_size' value="18"/>
                                                            </div>
                                                            <div class="ays_quiz_dropdown_max_width ays-display-flex" style="align-items: end;">
                                                                <input type="text" value="px" class='ays-quiz-form-hint-for-size' disabled>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div><!-- Buttons font size -->
                                        <hr>
                                        <div class="form-group row">
                                            <div class="col-sm-4">
                                                <label for='ays_quick_quiz_buttons_width'>
                                                    <?php echo esc_html__('Button width', 'quiz-maker'); ?>
                                                </label>
                                            </div>
                                            <div class="col-sm-8 ays_quiz_display_flex_width">
                                                <div>
                                                    <input type="number" class="ays-text-input ays-text-input-short" id='ays_quick_quiz_buttons_width' name='ays_quick_quiz_buttons_width' value="" />
                                                    <span style="display:block;" class="ays_quiz_small_hint_text"><?php echo esc_html__('For an initial width, leave blank.', 'quiz-maker'); ?></span>
                                                </div>
                                                <div class="ays_quiz_dropdown_max_width ays-display-flex" style="align-items: flex-start;">
                                                    <input type="text" value="px" class='ays-quiz-form-hint-for-size' disabled>
                                                </div>
                                            </div>
                                        </div><!-- Button width -->
                                        <hr>
                                        <div class="form-group row">
                                            <div class="col-sm-4">
                                                <label for="ays_buttons_padding">
                                                    <?php echo esc_html__('Button padding','quiz-maker'); ?>
                                                </label>
                                            </div>
                                            <div class="col-sm-8">
                                                <div class="row">
                                                    <div class="col-sm-4">
                                                        <div style="margin-bottom: 10px;">
                                                            <label for='ays_quick_quiz_buttons_left_right_padding'>
                                                                <?php echo esc_html__('Left / Right', 'quiz-maker'); ?>
                                                            </label>
                                                        </div>
                                                        <div style="display: inline-block; padding-left: 0;">
                                                            <input type="number" class="ays-text-input" id='ays_quick_quiz_buttons_left_right_padding' name='ays_quick_quiz_buttons_left_right_padding' value="36" style="width: 100px; max-width: 100%;" />
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-4">
                                                        <div style="margin-bottom: 10px;">
                                                            <label for='ays_quick_quiz_buttons_top_bottom_padding'>
                                                                <?php echo esc_html__('Top / Bottom', 'quiz-maker'); ?>
                                                            </label>
                                                        </div>
                                                        <div style="display: inline-block; padding-left: 0;">
                                                            <input type="number" class="ays-text-input" id='ays_quick_quiz_buttons_top_bottom_padding' name='ays_quick_quiz_buttons_top_bottom_padding' value="14" style="width: 100px; max-width: 100%;" />
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div><!-- Buttons padding -->
                                        <hr/>
                                        <div class="form-group row">
                                            <div class="col-sm-4">
                                                <label for="ays_quick_quiz_buttons_border_radius">
                                                    <?php echo esc_html__('Button border-radius','quiz-maker'); ?>
                                                </label>
                                            </div>
                                            <div class="col-sm-8 ays_quiz_display_flex_width">
                                                <div>
                                                    <input type="number" class="ays-text-input ays-text-input-short" id="ays_quick_quiz_buttons_border_radius" name="ays_quick_quiz_buttons_border_radius" value="8"/>
                                                </div>
                                                <div class="ays_quiz_dropdown_max_width ays-display-flex" style="align-items: flex-start;">
                                                    <input type="text" value="px" class='ays-quiz-form-hint-for-size' disabled>
                                                </div>
                                            </div>
                                        </div><!-- Buttons border radius -->
                                    </div>
                                </div><!-- Buttons Styles -->
                                <div class="ays-quiz-accordion-options-main-container" data-collapsed="false">
                                    <div class="ays-quiz-accordion-container">
                                        <?php echo wp_kses($quiz_accordion_svg_html, $svg_sanitize_properties); ?>
                                        <p class="ays-subtitle"><?php echo esc_html__('Admin Note styles','quiz-maker'); ?></p>
                                    </div>
                                    <hr class="ays-quiz-bolder-hr"/>
                                    <div class="ays-quiz-accordion-options-box">
                                        <div class="form-group row">
                                            <div class="col-sm-4">
                                                <label for='ays_quick_quiz_note_text_font_size'>
                                                    <?php echo esc_html__('Font size for the note text', 'quiz-maker'); ?>
                                                </label>
                                            </div>
                                            <div class="col-sm-8">
                                                <div class="row">
                                                    <div class="col-sm-12">
                                                        <div style="margin-bottom: 10px;">
                                                            <label for='ays_quick_quiz_note_text_font_size'>
                                                                <?php echo esc_html__('On desktop', 'quiz-maker'); ?>
                                                            </label>
                                                        </div>
                                                        <div class="ays_quiz_display_flex_width">
                                                            <div>
                                                                <input type="number" class="ays-text-input" id='ays_quick_quiz_note_text_font_size' name='ays_quick_quiz_note_text_font_size' value="14" />
                                                            </div>
                                                            <div class="ays_quiz_dropdown_max_width ays-display-flex" style="align-items: end;">
                                                                <input type="text" value="px" class='ays-quiz-form-hint-for-size' disabled>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <hr>
                                                <div class="row">
                                                    <div class="col-sm-12">
                                                        <div style="margin-bottom: 10px;">
                                                            <label for='ays_quick_quiz_note_text_mobile_font_size'>
                                                                <?php echo esc_html__('On mobile', 'quiz-maker'); ?>
                                                            </label>
                                                        </div>
                                                        <div class="ays_quiz_display_flex_width">
                                                            <div>
                                                                <input type="number" class="ays-text-input" id='ays_quick_quiz_note_text_mobile_font_size' name='ays_quick_quiz_note_text_mobile_font_size' value="14"/>
                                                            </div>
                                                            <div class="ays_quiz_dropdown_max_width ays-display-flex" style="align-items: end;">
                                                                <input type="text" value="px" class='ays-quiz-form-hint-for-size' disabled>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div><!-- Font size for the note text -->
                                        <hr>
                                        <div class="form-group row">
                                            <div class="col-sm-4">
                                                <label for="ays_quick_quiz_admin_note_text_transform">
                                                    <?php echo esc_html__('Text transformation','quiz-maker'); ?>
                                                </label>
                                            </div>
                                            <div class="col-sm-8">
                                                <div class="row">
                                                    <div class="col-sm-12">
                                                        <div style="margin-bottom: 10px;">
                                                            <label for='ays_quick_quiz_admin_note_text_transform'>
                                                                <?php echo esc_html__('On desktop', 'quiz-maker'); ?>
                                                            </label>
                                                        </div>
                                                        <div class="ays_quiz_display_flex_width">
                                                            <select name="ays_quick_quiz_admin_note_text_transform" id="ays_quick_quiz_admin_note_text_transform" class="ays-text-input ays-text-input-short" style="display:block;">
                                                                <option value="uppercase"><?php echo esc_html__( "Uppercase", 'quiz-maker' ); ?></option>
                                                                <option value="lowercase"><?php echo esc_html__( "Lowercase", 'quiz-maker' ); ?></option>
                                                                <option value="capitalize"><?php echo esc_html__( "Capitalize", 'quiz-maker' ); ?></option>
                                                                <option value="none" selected><?php echo esc_html__( "None", 'quiz-maker' ); ?></option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>
                                                <hr>
                                                <div class="row">
                                                    <div class="col-sm-12">
                                                        <div style="margin-bottom: 10px;">
                                                            <label for='ays_quick_quiz_admin_note_mobile_text_transform'>
                                                                <?php echo esc_html__('On mobile', 'quiz-maker'); ?>
                                                            </label>
                                                        </div>
                                                        <div class="ays_quiz_display_flex_width">
                                                            <select name="ays_quick_quiz_admin_note_mobile_text_transform" id="ays_quick_quiz_admin_note_mobile_text_transform" class="ays-text-input ays-text-input-short" style="display:block;">
                                                                <option value="uppercase"><?php echo esc_html__( "Uppercase", 'quiz-maker' ); ?></option>
                                                                <option value="lowercase"><?php echo esc_html__( "Lowercase", 'quiz-maker' ); ?></option>
                                                                <option value="capitalize"><?php echo esc_html__( "Capitalize", 'quiz-maker' ); ?></option>
                                                                <option value="none" selected><?php echo esc_html__( "None", 'quiz-maker' ); ?></option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div><!-- Admin note text transform -->
                                        <hr/>
                                        <div class="form-group row">
                                            <div class="col-sm-4">
                                                <label for="ays_quick_quiz_admin_note_text_decoration">
                                                    <?php echo esc_html__('Text decoration','quiz-maker'); ?>
                                                </label>
                                            </div>
                                            <div class="col-sm-8">
                                                <select class="ays-text-input ays-text-input-short" id="ays_quick_quiz_admin_note_text_decoration" name="ays_quick_quiz_admin_note_text_decoration">
                                                    <option value="none" selected><?php echo esc_html__('None','quiz-maker'); ?></option>
                                                    <option value="overline"><?php echo esc_html__('Overline','quiz-maker'); ?></option>
                                                    <option value="line-through"><?php echo esc_html__('Line through','quiz-maker'); ?></option>
                                                    <option value="underline"><?php echo esc_html__('Underline','quiz-maker'); ?></option>
                                                </select>
                                            </div>
                                        </div><!-- Admin note text decoration -->
                                        <hr/>
                                        <div class="form-group row">
                                            <div class="col-sm-4">
                                                <label for="ays_quick_quiz_admin_note_letter_spacing">
                                                    <?php echo esc_html__('Letter spacing','quiz-maker'); ?>
                                                </label>
                                            </div>
                                            <div class="col-sm-8 ays_quiz_display_flex_width">
                                                <div>
                                                    <input type="number" class="ays-text-input ays-text-input-short" id="ays_quick_quiz_admin_note_letter_spacing" name="ays_quick_quiz_admin_note_letter_spacing" value="0"/>
                                                </div>
                                                <div class="ays_quiz_dropdown_max_width ays-display-flex" style="align-items: flex-start;">
                                                    <input type="text" value="px" class='ays-quiz-form-hint-for-size' disabled>
                                                </div>
                                            </div>
                                        </div><!-- Letter spacing -->
                                        <hr/>
                                        <div class="form-group row">
                                            <div class="col-sm-4">
                                                <label for="ays_quick_quiz_admin_note_font_weight">
                                                    <?php echo esc_html__('Font weight','quiz-maker'); ?>
                                                </label>
                                            </div>
                                            <div class="col-sm-8">
                                                <select class="ays-text-input ays-text-input-short" id="ays_quick_quiz_admin_note_font_weight" name="ays_quick_quiz_admin_note_font_weight">
                                                    <option value="normal" selected><?php echo esc_html__('Normal','quiz-maker'); ?></option>
                                                    <option value="lighter"><?php echo esc_html__('Lighter','quiz-maker'); ?></option>
                                                    <option value="bold"><?php echo esc_html__('Bold','quiz-maker'); ?></option>
                                                    <option value="bolder"><?php echo esc_html__('Bolder','quiz-maker'); ?></option>
                                                    <option value="100"><?php echo '100'; ?></option>
                                                    <option value="200"><?php echo '200'; ?></option>
                                                    <option value="300"><?php echo '300'; ?></option>
                                                    <option value="400"><?php echo '400'; ?></option>
                                                    <option value="500"><?php echo '500'; ?></option>
                                                    <option value="600"><?php echo '600'; ?></option>
                                                    <option value="700"><?php echo '700'; ?></option>
                                                    <option value="800"><?php echo '800'; ?></option>
                                                    <option value="900"><?php echo '900'; ?></option>
                                                </select>
                                            </div>
                                        </div><!-- Admin note font weight -->
                                    </div>
                                </div><!-- Admin note styles -->
                                <div class="ays-quiz-accordion-options-main-container" data-collapsed="false">
                                    <div class="ays-quiz-accordion-container">
                                        <?php echo wp_kses($quiz_accordion_svg_html, $svg_sanitize_properties); ?>
                                        <p class="ays-subtitle"><?php echo esc_html__('Question explanation styles','quiz-maker'); ?></p>
                                    </div>
                                    <hr class="ays-quiz-bolder-hr"/>
                                    <div class="ays-quiz-accordion-options-box">
                                        <div class="form-group row">
                                            <div class="col-sm-4">
                                                <label for='ays_quick_quiz_quest_explanation_font_size'>
                                                    <?php echo esc_html__('Font size for the question explanation', 'quiz-maker'); ?>
                                                </label>
                                            </div>
                                            <div class="col-sm-8">
                                                <div class="row">
                                                    <div class="col-sm-12">
                                                        <div style="margin-bottom: 10px;">
                                                            <label for='ays_quick_quiz_quest_explanation_font_size'>
                                                                <?php echo esc_html__('On desktop', 'quiz-maker'); ?>
                                                            </label>
                                                        </div>
                                                        <div class="ays_quiz_display_flex_width">
                                                            <div>
                                                                <input type="number" class="ays-text-input" id='ays_quick_quiz_quest_explanation_font_size' name='ays_quick_quiz_quest_explanation_font_size' value="16" />
                                                            </div>
                                                            <div class="ays_quiz_dropdown_max_width ays-display-flex" style="align-items: end;">
                                                                <input type="text" value="px" class='ays-quiz-form-hint-for-size' disabled>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <hr/>
                                                <div class="row">
                                                    <div class="col-sm-12">
                                                        <div style="margin-bottom: 10px;">
                                                            <label for='ays_quick_quiz_quest_explanation_mobile_font_size'>
                                                                <?php echo esc_html__('On mobile', 'quiz-maker'); ?>
                                                            </label>
                                                        </div>
                                                        <div class="ays_quiz_display_flex_width">
                                                            <div>
                                                                <input type="number" class="ays-text-input" id='ays_quick_quiz_quest_explanation_mobile_font_size' name='ays_quick_quiz_quest_explanation_mobile_font_size' value="16" />
                                                            </div>
                                                            <div class="ays_quiz_dropdown_max_width ays-display-flex" style="align-items: end;">
                                                                <input type="text" value="px" class='ays-quiz-form-hint-for-size' disabled>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div><!-- Font size for the note text -->
                                        <hr/>
                                        <div class="form-group row">
                                            <div class="col-sm-4">
                                                <label for="ays_quick_quiz_quest_explanation_text_transform">
                                                    <?php echo esc_html__('Text transformation','quiz-maker'); ?>
                                                </label>
                                            </div>
                                            <div class="col-sm-8">
                                                <select class="ays-text-input ays-text-input-short" id="ays_quick_quiz_quest_explanation_text_transform" name="ays_quick_quiz_quest_explanation_text_transform">
                                                    <option value="none" selected><?php echo esc_html__('None','quiz-maker'); ?></option>
                                                    <option value="capitalize"><?php echo esc_html__('Capitalize','quiz-maker'); ?></option>
                                                    <option value="uppercase"><?php echo esc_html__('Uppercase','quiz-maker'); ?></option>
                                                    <option value="lowercase"><?php echo esc_html__('Lowercase','quiz-maker'); ?></option>
                                                </select>
                                            </div>
                                        </div><!-- Question explanation text transform -->
                                        <hr/>
                                        <div class="form-group row">
                                            <div class="col-sm-4">
                                                <label for="ays_quick_quiz_quest_explanation_text_decoration">
                                                    <?php echo esc_html__('Text decoration','quiz-maker'); ?>
                                                </label>
                                            </div>
                                            <div class="col-sm-8">
                                                <select class="ays-text-input ays-text-input-short" id="ays_quick_quiz_quest_explanation_text_decoration" name="ays_quick_quiz_quest_explanation_text_decoration">
                                                    <option value="none" selected><?php echo esc_html__('None','quiz-maker'); ?></option>
                                                    <option value="overline"><?php echo esc_html__('Overline','quiz-maker'); ?></option>
                                                    <option value="line-through"><?php echo esc_html__('Line through','quiz-maker'); ?></option>
                                                    <option value="underline"><?php echo esc_html__('Underline','quiz-maker'); ?></option>
                                                </select>
                                            </div>
                                        </div><!-- Question explanation text decoration -->
                                        <hr/>
                                        <div class="form-group row">
                                            <div class="col-sm-4">
                                                <label for="ays_quick_quiz_quest_explanation_letter_spacing">
                                                    <?php echo esc_html__('Letter spacing','quiz-maker'); ?>
                                                </label>
                                            </div>
                                            <div class="col-sm-8 ays_quiz_display_flex_width">
                                                <div>
                                                    <input type="number" class="ays-text-input ays-text-input-short" id="ays_quick_quiz_quest_explanation_letter_spacing" name="ays_quick_quiz_quest_explanation_letter_spacing" value="0"/>
                                                </div>
                                                <div class="ays_quiz_dropdown_max_width ays-display-flex" style="align-items: flex-start;">
                                                    <input type="text" value="px" class='ays-quiz-form-hint-for-size' disabled>
                                                </div>
                                            </div>
                                        </div><!-- Question explanation Letter spacing -->
                                        <hr/>
                                        <div class="form-group row">
                                            <div class="col-sm-4">
                                                <label for="ays_quick_quiz_quest_explanation_font_weight">
                                                    <?php echo esc_html__('Font weight','quiz-maker'); ?>
                                                </label>
                                            </div>
                                            <div class="col-sm-8">
                                                <select class="ays-text-input ays-text-input-short" id="ays_quick_quiz_quest_explanation_font_weight" name="ays_quick_quiz_quest_explanation_font_weight">
                                                    <option value="normal" selected>
                                                        <?php echo esc_html__('Normal','quiz-maker'); ?>
                                                    </option>
                                                    <option value="lighter">
                                                        <?php echo esc_html__('Lighter','quiz-maker'); ?>
                                                    </option>
                                                    <option value="bold">
                                                        <?php echo esc_html__('Bold','quiz-maker'); ?>
                                                    </option>
                                                    <option value="bolder">
                                                        <?php echo esc_html__('Bolder','quiz-maker'); ?>
                                                    </option>
                                                    <option value="100">
                                                        <?php echo '100'; ?>
                                                    </option>
                                                    <option value="200">
                                                        <?php echo '200'; ?>
                                                    </option>
                                                    <option value="300">
                                                        <?php echo '300'; ?>
                                                    </option>
                                                    <option value="400">
                                                        <?php echo '400'; ?>
                                                    </option>
                                                    <option value="500">
                                                        <?php echo '500'; ?>
                                                    </option>
                                                    <option value="600">
                                                        <?php echo '600'; ?>
                                                    </option>
                                                    <option value="700">
                                                        <?php echo '700'; ?>
                                                    </option>
                                                    <option value="800">
                                                        <?php echo '800'; ?>
                                                    </option>
                                                    <option value="900">
                                                        <?php echo '900'; ?>
                                                    </option>
                                                </select>
                                            </div>
                                        </div><!-- Question explanation font weight -->
                                    </div>
                                </div><!-- Question explanation styles -->
                                <div class="ays-quiz-accordion-options-main-container" data-collapsed="false">
                                    <div class="ays-quiz-accordion-container">
                                        <?php echo wp_kses($quiz_accordion_svg_html, $svg_sanitize_properties); ?>
                                        <p class="ays-subtitle" style="margin-top:0;"><?php echo esc_html__('Right answer styles','quiz-maker'); ?></p>
                                    </div>
                                    <hr class="ays-quiz-bolder-hr"/>
                                    <div class="ays-quiz-accordion-options-box">
                                        <div class="form-group row">
                                            <div class="col-sm-4">
                                                <label for="ays_quick_quiz_right_answers_font_size">
                                                    <?php echo esc_html__('Font size for the right answer','quiz-maker'); ?>
                                                </label>
                                            </div>
                                            <div class="col-sm-8">
                                                <div class="row">
                                                    <div class="col-sm-12">
                                                        <div style="margin-bottom: 10px;">
                                                            <label for='ays_quick_quiz_right_answers_font_size'>
                                                                <?php echo esc_html__('On desktop', 'quiz-maker'); ?>
                                                            </label>
                                                        </div>
                                                        <div class="ays_quiz_display_flex_width">
                                                            <div>
                                                                <input type="number" class="ays-text-input" id='ays_quick_quiz_right_answers_font_size' name='ays_quick_quiz_right_answers_font_size' value="16"/>
                                                            </div>
                                                            <div class="ays_quiz_dropdown_max_width ays-display-flex" style="align-items: end;">
                                                                <input type="text" value="px" class='ays-quiz-form-hint-for-size' disabled>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <hr>
                                                <div class="row">
                                                    <div class="col-sm-12">
                                                        <div style="margin-bottom: 10px;">
                                                            <label for='ays_quick_quiz_right_answers_mobile_font_size'>
                                                                <?php echo esc_html__('On mobile', 'quiz-maker'); ?>
                                                            </label>
                                                        </div>
                                                        <div class="ays_quiz_display_flex_width">
                                                            <div>
                                                                <input type="number" class="ays-text-input" id='ays_quick_quiz_right_answers_mobile_font_size' name='ays_quick_quiz_right_answers_mobile_font_size' value="16"/>
                                                            </div>
                                                            <div class="ays_quiz_dropdown_max_width ays-display-flex" style="align-items: end;">
                                                                <input type="text" value="px" class='ays-quiz-form-hint-for-size' disabled>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <hr/>
                                        <div class="form-group row">
                                            <div class="col-sm-4">
                                                <label for="ays_quick_quiz_right_answer_text_transform">
                                                    <?php echo esc_html__('Text transformation','quiz-maker'); ?>
                                                </label>
                                            </div>
                                            <div class="col-sm-8">
                                                <select class="ays-text-input ays-text-input-short" id="ays_quick_quiz_right_answer_text_transform" name="ays_quick_quiz_right_answer_text_transform">
                                                    <option value="none" selected>
                                                        <?php echo esc_html__('None','quiz-maker'); ?>
                                                    </option>
                                                    <option value="capitalize">
                                                        <?php echo esc_html__('Capitalize','quiz-maker'); ?>
                                                    </option>
                                                    <option value="uppercase">
                                                        <?php echo esc_html__('Uppercase','quiz-maker'); ?>
                                                    </option>
                                                    <option value="lowercase">
                                                        <?php echo esc_html__('Lowercase','quiz-maker'); ?>
                                                    </option>
                                                </select>
                                            </div>
                                        </div><!-- Right answer text transform -->
                                        <hr/>
                                        <div class="form-group row">
                                            <div class="col-sm-4">
                                                <label for="ays_quick_quiz_right_answers_text_decoration">
                                                    <?php echo esc_html__('Text decoration','quiz-maker'); ?>
                                                </label>
                                            </div>
                                            <div class="col-sm-8">
                                                <select class="ays-text-input ays-text-input-short" id="ays_quick_quiz_right_answers_text_decoration" name="ays_quick_quiz_right_answers_text_decoration">
                                                    <option value="none" selected>
                                                        <?php echo esc_html__('None','quiz-maker'); ?>
                                                    </option>
                                                    <option value="overline">
                                                        <?php echo esc_html__('Overline','quiz-maker'); ?>
                                                    </option>
                                                    <option value="line-through">
                                                        <?php echo esc_html__('Line through','quiz-maker'); ?>
                                                    </option>
                                                    <option value="underline">
                                                        <?php echo esc_html__('Underline','quiz-maker'); ?>
                                                    </option>
                                                </select>
                                            </div>
                                        </div><!-- Right answer text decoration -->
                                        <hr/>
                                        <div class="form-group row">
                                            <div class="col-sm-4">
                                                <label for="ays_quick_quiz_right_answers_letter_spacing">
                                                    <?php echo esc_html__('Letter spacing','quiz-maker')?>
                                                </label>
                                            </div>
                                            <div class="col-sm-8 ays_quiz_display_flex_width">
                                                <div>
                                                    <input type="number" class="ays-text-input ays-text-input-short" id="ays_quick_quiz_right_answers_letter_spacing" name="ays_quick_quiz_right_answers_letter_spacing" value="0" />
                                                </div>
                                                <div class="ays_quiz_dropdown_max_width ays-display-flex" style="align-items: flex-start;">
                                                    <input type="text" value="px" class='ays-quiz-form-hint-for-size' disabled>
                                                </div>
                                            </div>
                                        </div><!-- Right answer Letter spacing -->
                                        <hr/>
                                        <div class="form-group row">
                                            <div class="col-sm-4">
                                                <label for="ays_quick_quiz_right_answers_font_weight">
                                                    <?php echo esc_html__('Font weight','quiz-maker'); ?>
                                                </label>
                                            </div>
                                            <div class="col-sm-8">
                                                <select class="ays-text-input ays-text-input-short" id="ays_quick_quiz_right_answers_font_weight" name="ays_quick_quiz_right_answers_font_weight">
                                                    <option value="normal" selected>
                                                        <?php echo esc_html__('Normal','quiz-maker'); ?>
                                                    </option>
                                                    <option value="lighter">
                                                        <?php echo esc_html__('Lighter','quiz-maker'); ?>
                                                    </option>
                                                    <option value="bold">
                                                        <?php echo esc_html__('Bold','quiz-maker'); ?>
                                                    </option>
                                                    <option value="bolder">
                                                        <?php echo esc_html__('Bolder','quiz-maker'); ?>
                                                    </option>
                                                    <option value="100">
                                                        <?php echo '100'; ?>
                                                    </option>
                                                    <option value="200">
                                                        <?php echo '200'; ?>
                                                    </option>
                                                    <option value="300">
                                                        <?php echo '300'; ?>
                                                    </option>
                                                    <option value="400">
                                                        <?php echo '400'; ?>
                                                    </option>
                                                    <option value="500">
                                                        <?php echo '500'; ?>
                                                    </option>
                                                    <option value="600">
                                                        <?php echo '600'; ?>
                                                    </option>
                                                    <option value="700">
                                                        <?php echo '700'; ?>
                                                    </option>
                                                    <option value="800">
                                                        <?php echo '800'; ?>
                                                    </option>
                                                    <option value="900">
                                                        <?php echo '900'; ?>
                                                    </option>
                                                </select>
                                            </div>
                                        </div><!-- Right answer font weight -->
                                    </div>
                                </div><!-- Right answer styles -->
                                <div class="ays-quiz-accordion-options-main-container" data-collapsed="false">
                                    <div class="ays-quiz-accordion-container">
                                        <?php echo wp_kses($quiz_accordion_svg_html, $svg_sanitize_properties); ?>
                                        <p class="ays-subtitle" style="margin-top:0;"><?php echo esc_html__('Wrong answer styles','quiz-maker'); ?></p>
                                    </div>
                                    <hr class="ays-quiz-bolder-hr"/>
                                    <div class="ays-quiz-accordion-options-box">
                                        <div class="form-group row">
                                            <div class="col-sm-4">
                                                <label for="ays_quick_quiz_wrong_answers_font_size">
                                                    <?php echo esc_html__('Font size for the wrong answer','quiz-maker'); ?>
                                                </label>
                                            </div>
                                            <div class="col-sm-8">
                                                <div class="row">
                                                    <div class="col-sm-12">
                                                        <div style="margin-bottom: 10px;">
                                                            <label for='ays_quick_quiz_wrong_answers_font_size'>
                                                                <?php echo esc_html__('On desktop', 'quiz-maker'); ?>
                                                            </label>
                                                        </div>
                                                        <div class="ays_quiz_display_flex_width">
                                                            <div>
                                                                <input type="number" class="ays-text-input" id='ays_quick_quiz_wrong_answers_font_size' name='ays_quick_quiz_wrong_answers_font_size' value="16"/>
                                                            </div>
                                                            <div class="ays_quiz_dropdown_max_width ays-display-flex" style="align-items: end;">
                                                                <input type="text" value="px" class='ays-quiz-form-hint-for-size' disabled>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <hr>
                                                <div class="row">
                                                    <div class="col-sm-12">
                                                        <div style="margin-bottom: 10px;">
                                                            <label for='ays_quick_quiz_wrong_answers_mobile_font_size'>
                                                                <?php echo esc_html__('On mobile', 'quiz-maker'); ?>
                                                            </label>
                                                        </div>
                                                        <div class="ays_quiz_display_flex_width">
                                                            <div>
                                                                <input type="number" class="ays-text-input" id='ays_quick_quiz_wrong_answers_mobile_font_size' name='ays_quick_quiz_wrong_answers_mobile_font_size' value="16"/>
                                                            </div>
                                                            <div class="ays_quiz_dropdown_max_width ays-display-flex" style="align-items: end;">
                                                                <input type="text" value="px" class='ays-quiz-form-hint-for-size' disabled>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div><!-- Font size for the wrong answer -->
                                        <hr/>
                                        <div class="form-group row">
                                            <div class="col-sm-4">
                                                <label for="ays_quick_quiz_wrong_answer_text_transform">
                                                    <?php echo esc_html__('Text transformation','quiz-maker'); ?>
                                                </label>
                                            </div>
                                            <div class="col-sm-8">
                                                <select class="ays-text-input ays-text-input-short" id="ays_quick_quiz_wrong_answer_text_transform" name="ays_quick_quiz_wrong_answer_text_transform">
                                                    <option value="none" selected>
                                                        <?php echo esc_html__('None','quiz-maker'); ?>
                                                    </option>
                                                    <option value="capitalize">
                                                        <?php echo esc_html__('Capitalize','quiz-maker'); ?>
                                                    </option>
                                                    <option value="uppercase">
                                                        <?php echo esc_html__('Uppercase','quiz-maker'); ?>
                                                    </option>
                                                    <option value="lowercase">
                                                        <?php echo esc_html__('Lowercase','quiz-maker'); ?>
                                                    </option>
                                                </select>
                                            </div>
                                        </div><!-- Wrong answer text transform -->
                                        <hr/>
                                        <div class="form-group row">
                                            <div class="col-sm-4">
                                                <label for="ays_quick_quiz_wrong_answers_text_decoration">
                                                    <?php echo esc_html__('Text decoration','quiz-maker'); ?>
                                                </label>
                                            </div>
                                            <div class="col-sm-8">
                                                <select class="ays-text-input ays-text-input-short" id="ays_quick_quiz_wrong_answers_text_decoration" name="ays_quick_quiz_wrong_answers_text_decoration">
                                                    <option value="none" selected>
                                                        <?php echo esc_html__('None','quiz-maker'); ?>
                                                    </option>
                                                    <option value="overline">
                                                        <?php echo esc_html__('Overline','quiz-maker'); ?>
                                                    </option>
                                                    <option value="line-through">
                                                        <?php echo esc_html__('Line through','quiz-maker'); ?>
                                                    </option>
                                                    <option value="underline">
                                                        <?php echo esc_html__('Underline','quiz-maker'); ?>
                                                    </option>
                                                </select>
                                            </div>
                                        </div><!-- Text decoration -->
                                        <hr/>
                                        <div class="form-group row">
                                            <div class="col-sm-4">
                                                <label for="ays_quick_quiz_wrong_answers_letter_spacing">
                                                    <?php echo esc_html__('Letter spacing','quiz-maker'); ?>
                                                </label>
                                            </div>
                                            <div class="col-sm-8 ays_quiz_display_flex_width">
                                                <div>
                                                    <input type="number" class="ays-text-input ays-text-input-short" id="ays_quick_quiz_wrong_answers_letter_spacing" name="ays_quick_quiz_wrong_answers_letter_spacing" value="0"/>
                                                </div>
                                                <div class="ays_quiz_dropdown_max_width ays-display-flex" style="align-items: flex-start;">
                                                    <input type="text" value="px" class='ays-quiz-form-hint-for-size' disabled>
                                                </div>
                                            </div>
                                        </div><!-- Letter spacing -->
                                        <hr/>
                                        <div class="form-group row">
                                            <div class="col-sm-4">
                                                <label for="ays_quick_quiz_wrong_answers_font_weight">
                                                    <?php echo esc_html__('Font weight','quiz-maker'); ?>
                                                </label>
                                            </div>
                                            <div class="col-sm-8">
                                                <select class="ays-text-input ays-text-input-short" id="ays_quick_quiz_wrong_answers_font_weight" name="ays_quick_quiz_wrong_answers_font_weight">
                                                    <option value="normal" selected>
                                                        <?php echo esc_html__('Normal','quiz-maker'); ?>
                                                    </option>
                                                    <option value="lighter">
                                                        <?php echo esc_html__('Lighter','quiz-maker'); ?>
                                                    </option>
                                                    <option value="bold">
                                                        <?php echo esc_html__('Bold','quiz-maker'); ?>
                                                    </option>
                                                    <option value="bolder">
                                                        <?php echo esc_html__('Bolder','quiz-maker'); ?>
                                                    </option>
                                                    <option value="100">
                                                        <?php echo '100'; ?>
                                                    </option>
                                                    <option value="200">
                                                        <?php echo '200'; ?>
                                                    </option>
                                                    <option value="300">
                                                        <?php echo '300'; ?>
                                                    </option>
                                                    <option value="400">
                                                        <?php echo '400'; ?>
                                                    </option>
                                                    <option value="500">
                                                        <?php echo '500'; ?>
                                                    </option>
                                                    <option value="600">
                                                        <?php echo '600'; ?>
                                                    </option>
                                                    <option value="700">
                                                        <?php echo '700'; ?>
                                                    </option>
                                                    <option value="800">
                                                        <?php echo '800'; ?>
                                                    </option>
                                                    <option value="900">
                                                        <?php echo '900'; ?>
                                                    </option>
                                                </select>
                                            </div>
                                        </div><!-- Wrong answer font weight -->
                                    </div>
                                </div><!-- Wrong answer styles -->
                            </div>
                        </div> <!-- Quiz Options -->
                    </div>
                    <hr>
                    <div class="ays-quick-questions-container">
                        <div>
                            <p class="ays_questions_title"><?php echo esc_html__('Questions','quiz-maker')?></p>
                            <!-- <a href="javascript:void(0)" class="ays_add_question">
                                <i class="ays_fa ays_fa_plus_square" aria-hidden="true"></i>
                            </a> -->
                            <div>
                                <a href="javascript:void(0)" class="ays_add_question ays-quick-quiz-add-question">
                                    <i class="ays_fa ays_fa_plus_square" aria-hidden="true"></i>
                                    <?php echo esc_html__('Add question', 'quiz-maker'); ?>
                                </a>
                            </div>
                        </div>
                        <hr/>
                        <div tabindex="0" class="ays_modal_element ays_modal_question active_question_border" id="ays_question_id_1">
                            <div class="form-group row">
                                <div class="col-sm-9">
                                    <input type="text" value="<?php echo esc_html__( 'Question Default Title' , 'quiz-maker'); ?>" class="ays_question_input">
                                </div>
                                <div class="col-sm-3" style="text-align: right;">
                                    <select class="ays_quick_question_type" name="ays_quick_question_type[]" style="width: 120px;">
                                        <option value="radio"><?php echo esc_html__("Radio", 'quiz-maker'); ?></option>
                                        <option value="checkbox"><?php echo esc_html__("Checkbox", 'quiz-maker'); ?></option>
                                        <option value="select"><?php echo esc_html__("Dropdown", 'quiz-maker'); ?></option>
                                        <option value="text"><?php echo esc_html__("Text", 'quiz-maker'); ?></option>
                                        <option value="short_text"><?php echo esc_html__("Short Text", 'quiz-maker'); ?></option>
                                        <option value="number"><?php echo esc_html__("Number", 'quiz-maker'); ?></option>
                                        <option value="true_or_false"><?php echo esc_html__("True/False", 'quiz-maker'); ?></option>
                                        <option value="date"><?php echo esc_html__("Date", 'quiz-maker'); ?></option>
                                    </select>
                                </div>
                            </div>
                            <!-- <div class="ays_question_overlay"></div> -->
                            <div class="form-group row">
                                <div class="col-sm-12" style="text-align: right;">
                                    <select class="ays_quick_question_cat" name="ays_quick_question_cat[]" style="width: 120px;">
                                        <?php
                                            $cat = 0;
                                            foreach ($question_categories as $k => $question_category) {
                                                $checked = (intval($question_category['id']) == intval($question['category_id'])) ? "selected" : "";
                                                if ($cat == 0 && intval($question['category_id']) == 0) {
                                                    $checked = 'selected';
                                                }
                                                echo "<option value='" . esc_attr($question_category['id']) . "' " . esc_attr($checked) . ">" . esc_attr( stripslashes($question_category['title']) ) . "</option>";
                                                $cat++;
                                            }
                                        ?>
                                    </select>
                                </div>
                            </div>
                            <div class="ays-modal-flexbox flex-end">
                                <table class="ays_answers_table">
                                    <tr>
                                        <td>
                                            <input class="ays_answer_unique_id" type="radio" name="ays_answer_radio[1]" checked>
                                        </td>
                                        <td class="ays_answer_td">
                                            <p class="ays_answer"><?php echo esc_html__('Answer','quiz-maker')?></p>
                                        </td>
                                        <td class="show_remove_answer">
                                            <i class="ays_fa ays_fa_times" aria-hidden="true"></i>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <input class="ays_answer_unique_id" type="radio" name="ays_answer_radio[1]">
                                        </td>
                                        <td class="ays_answer_td">
                                            <p class="ays_answer"><?php echo esc_html__('Answer','quiz-maker')?></p>
                                        </td>
                                        <td class="show_remove_answer">
                                            <i class="ays_fa ays_fa_times" aria-hidden="true"></i>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <input class="ays_answer_unique_id" type="radio" name="ays_answer_radio[1]">
                                        </td>
                                        <td class="ays_answer_td">
                                            <p class="ays_answer"><?php echo esc_html__('Answer','quiz-maker')?></p>
                                        </td>
                                        <td class="show_remove_answer">
                                            <i class="ays_fa ays_fa_times" aria-hidden="true"></i>
                                        </td>
                                    </tr>
                                    <tr class="ays_quiz_add_answer_box show_add_answer">
                                        <td colspan="3">
                                            <a href="javascript:void(0)" class="ays_add_answer">
                                                <i class="ays_fa ays_fa_plus_square" aria-hidden="true"></i>
                                            </a>
                                        </td>
                                    </tr>
                                </table>
                                <table class="ays_quick_quiz_text_type_table display_none">
                                    <tr>
                                        <td>
                                            <input style="display:none;" class="ays-correct-answer" type="checkbox" name="ays-correct-answer[]" value="1" checked/>
                                            <textarea type="text" name="ays-correct-answer-value[]" class="ays-correct-answer-value ays-text-question-type-value" placeholder="<?php echo esc_html__( 'Answer text', 'quiz-maker' ); ?>"></textarea>
                                        </td>
                                    </tr>
                                </table>
                                <div class="ays-quick-quiz-icons-box">
                                    <a href="javascript:void(0)" class="ays_question_clone_icon">
                                        <i class="ays_fa ays_fa_clone" aria-hidden="true"></i>
                                    </a>
                                    <a href="javascript:void(0)" class="ays_trash_icon">
                                        <i class="ays_fa ays_fa_trash_o" aria-hidden="true"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <hr/>
                    <div class="ays-modal-flexbox">
                        <!-- <a href="javascript:void(0)" class="ays_add_question">
                            <i class="ays_fa ays_fa_plus_square" aria-hidden="true"></i>
                        </a> -->
                        <a href="javascript:void(0)" class="ays_add_question ays-quick-quiz-add-question">
                            <i class="ays_fa ays_fa_plus_square" aria-hidden="true"></i>
                            <?php echo esc_html__('Add question', 'quiz-maker'); ?>
                        </a>
                    </div>
                    <input type="button" class="btn btn-primary ays_submit_button" id="ays_quick_submit_button" value="<?php echo esc_html__('Submit','quiz-maker')?>"/>
                    <input type="hidden" id="ays_quick_question_max_id" value="1"/>
                    <input type="hidden" id="ays_quiz_ajax_quick_quiz_nonce" name="ays_quiz_ajax_quick_quiz_nonce" value="<?php echo $quick_quiz_plugin_nonce; ?>">
                </form>
            </div>
        </div>
    </div>
</div>
