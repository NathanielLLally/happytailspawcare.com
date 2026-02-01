    <?php
if(isset($_GET['tab'])){
    $ays_question_tab = sanitize_key( $_GET['tab'] );
}else{
    $ays_question_tab = 'tab1';
}
$action = (isset($_GET['action'])) ? sanitize_text_field( $_GET['action'] ) : '';
$heading = '';
$loader_iamge = '';
$image_text = esc_html__('Add Image', 'quiz-maker');

$id = (isset($_GET['question'])) ? absint( intval( $_GET['question'] ) ) : null;
$user_id = get_current_user_id();
$user = get_userdata($user_id);
$author = array(
    'id' => $user->ID,
    'name' => $user->data->display_name
);
$options = array(
    'author'                                => $author,
    'use_html'                              => 'off',
    'enable_question_text_max_length'       => 'off',
    'question_text_max_length'              => '',
    'question_limit_text_type'              => 'characters',
    'question_enable_text_message'          => 'off',
    'enable_question_number_max_length'     => 'off',
    'question_number_max_length'            => '',
    'quiz_hide_question_text'               => 'off',
    'enable_max_selection_number'           => 'off',
    'max_selection_number'                  => '',
    'quiz_question_note_message'            => '',
    'enable_case_sensitive_text'            => 'off',
    'enable_question_number_min_length'     => 'off',
    'question_number_min_length'            => '',
    'enable_question_number_error_message'  => 'off',
    'question_number_error_message'         => '',
    'enable_min_selection_number'           => 'off',
    'min_selection_number'                  => '',
);

$question_category_page_url  = admin_url( 'admin.php?page=quiz-maker-question-categories' );
$youtube_icon_svg = "<span class=''><img src='". AYS_QUIZ_ADMIN_URL ."/images/icons/youtube-video-icon.svg'></span>";


$gen_options = ($this->settings_obj->ays_get_setting('options') === false) ? array() : json_decode( stripcslashes($this->settings_obj->ays_get_setting('options') ), true);

$question_default_type = isset($gen_options['question_default_type']) ? sanitize_text_field( stripslashes($gen_options['question_default_type']) ) : null;
$ays_answer_default_count = isset($gen_options['ays_answer_default_count']) ? intval($gen_options['ays_answer_default_count']) : null;
// Question Default Category
$question_default_category = isset($gen_options['question_default_category']) ? absint(intval($gen_options['question_default_category'])) : null;

// Enable question allow HTML
$gen_options['quiz_enable_question_allow_html'] = isset($gen_options['quiz_enable_question_allow_html']) ? sanitize_text_field( $gen_options['quiz_enable_question_allow_html'] ) : 'off';
$quiz_enable_question_allow_html = (isset($gen_options['quiz_enable_question_allow_html']) && sanitize_text_field( $gen_options['quiz_enable_question_allow_html'] ) == "on") ? true : false;

// Enable No influence to score for new question
$gen_options['quiz_enable_question_not_influence_to_score'] = isset($gen_options['quiz_enable_question_not_influence_to_score']) ? sanitize_text_field( $gen_options['quiz_enable_question_not_influence_to_score'] ) : 'off';
$quiz_enable_question_not_influence_to_score = (isset($gen_options['quiz_enable_question_not_influence_to_score']) && sanitize_text_field( $gen_options['quiz_enable_question_not_influence_to_score'] ) == "on") ? true : false;

// Enable Hide question text for new question
$gen_options['quiz_enable_question_hide_question_text'] = isset($gen_options['quiz_enable_question_hide_question_text']) ? sanitize_text_field( $gen_options['quiz_enable_question_hide_question_text'] ) : 'off';
$quiz_enable_question_hide_question_text = (isset($gen_options['quiz_enable_question_hide_question_text']) && sanitize_text_field( $gen_options['quiz_enable_question_hide_question_text'] ) == "on") ? true : false;

// Strip slashes for answers for a new question
$gen_options['quiz_stripslashes_for_answer'] = isset($gen_options['quiz_stripslashes_for_answer']) ? sanitize_text_field( $gen_options['quiz_stripslashes_for_answer'] ) : 'off';
$quiz_stripslashes_for_answer = (isset($gen_options['quiz_stripslashes_for_answer']) && sanitize_text_field( $gen_options['quiz_stripslashes_for_answer'] ) == "on") ? true : false;

// Enable case sensitive text for a new question
$gen_options['quiz_case_sensitive_text'] = isset($gen_options['quiz_case_sensitive_text']) ? sanitize_text_field( $gen_options['quiz_case_sensitive_text'] ) : 'off';
$quiz_case_sensitive_text = (isset($gen_options['quiz_case_sensitive_text']) && sanitize_text_field( $gen_options['quiz_case_sensitive_text'] ) == "on") ? true : false;

// WP Editor height
$quiz_wp_editor_height = (isset($gen_options['quiz_wp_editor_height']) && $gen_options['quiz_wp_editor_height'] != '') ? absint( sanitize_text_field($gen_options['quiz_wp_editor_height']) ) : 100 ;

if($question_default_type === null){
    $question_default_type = 'radio';
}
if($ays_answer_default_count === null){
    $ays_answer_default_count = '3';
}
if($question_default_category === null){
    $question_default_category = 1;
}
$ays_answer_default_count = intval($ays_answer_default_count);

$question = array(
    'category_id'               => $question_default_category,
    'question'                  => '',
    'question_image'            => '',
    'type'                      => $question_default_type,
    'published'                 => '',
    'wrong_answer_text'         => '',
    'right_answer_text'         => '',
    'explanation'               => '',
    'create_date'               => current_time( 'mysql' ),
    'not_influence_to_score'    => 'off',
    'options'                   => json_encode($options),
);

$answers = array();
switch ($action) {
    case 'add':
        $heading = esc_html__('Add new question', 'quiz-maker');
        break;
    case 'edit':
        $heading    = esc_html__('Edit question', 'quiz-maker');
        $question   = $this->questions_obj->get_question($id);
        $answers    = $this->questions_obj->get_question_answers($id);
        break;
}

$loader_iamge = "<span class='display_none ays_quiz_loader_box'><img src='". AYS_QUIZ_ADMIN_URL ."/images/loaders/loading.gif'></span>";

$question['type'] = (isset($question['type']) && $question['type'] != '') ? sanitize_text_field( stripslashes($question['type']) ) : sanitize_text_field( stripslashes($question_default_type) );
$question['category_id'] = (isset($question['category_id']) && $question['category_id'] != '') ? intval($question['category_id']) : intval($question_default_category);
$question_categories = $this->questions_obj->get_question_categories();
if (isset($_POST['ays_submit']) || isset($_POST['ays_submit_top'])) {
    $_POST["id"] = $id;
    $this->questions_obj->add_edit_questions();
}
if(isset($_POST['ays_apply_top']) || isset($_POST['ays_apply'])){
    $_POST["id"] = $id;
    $_POST['ays_change_type'] = 'apply';
    $this->questions_obj->add_edit_questions();
}
if(isset($_POST['ays_save_new_top']) || isset($_POST['ays_save_new'])){
    $_POST["id"] = $id;
    $_POST['ays_change_type'] = 'save_new';
    $this->questions_obj->add_edit_questions();
}

$nex_question_id = "";
$prev_question_id = "";
if ( isset( $id ) && !is_null( $id ) ) {
    $nex_question = $this->get_next_or_prev_row_by_id( $id, "next", "aysquiz_questions" );
    $nex_question_id = (isset( $nex_question['id'] ) && $nex_question['id'] != "") ? absint( $nex_question['id'] ) : null;

    $prev_question = $this->get_next_or_prev_row_by_id( $id, "prev", "aysquiz_questions" );
    $prev_question_id = (isset( $prev_question['id'] ) && $prev_question['id'] != "") ? absint( $prev_question['id'] ) : null;
}

$q_question_title = (isset( $question["question"] ) && $question["question"] != "") ? stripslashes($question["question"]) : "";

$question_published = (isset( $question["published"] ) && $question["published"] != "") ? absint($question["published"]) : 1;

$question_image = (isset( $question['question_image'] ) && $question['question_image']) ? esc_url($question['question_image']) : "";

$style = null;
if ($question_image != '') {
    $style = "display: block;";
    $image_text = esc_html__('Edit Image', 'quiz-maker');
}
$question_create_date = (isset($question['create_date']) && $question['create_date'] != '') ? sanitize_text_field( stripslashes($question['create_date']) ) : "0000-00-00 00:00:00";

if ( isset( $question['options'] ) && $question['options'] != "" ) {
    $options = json_decode($question['options'], true);
}

if(isset($options['author']) && $options['author'] != 'null'){
    if($action == 'edit'){
        if(is_array($options['author'])){
            $question_author = $options['author'];
        }else{
            $question_author = json_decode($options['author'], true);
        }        
    }else{
        $question_author = $options['author'];
    }
} else {
    $question_author = array('name' => 'Unknown');
}
$question_types = array(
    "radio"             => esc_html__("Radio", 'quiz-maker'),
    "checkbox"          => esc_html__("Checkbox( Multiple )", 'quiz-maker'),
    "select"            => esc_html__("Dropdown", 'quiz-maker'),
    "text"              => esc_html__("Text", 'quiz-maker'),
    "short_text"        => esc_html__("Short Text", 'quiz-maker'),
    "number"            => esc_html__("Number", 'quiz-maker'),
    "date"              => esc_html__("Date", 'quiz-maker'),
    "true_or_false"     => esc_html__("True/False", 'quiz-maker'),
    "custom"            => esc_html__("Info Banner (PRO)", 'quiz-maker'),
    "fill_in_blank"     => esc_html__("Fill in the blanks (PRO)", 'quiz-maker'),
    "matching"          => esc_html__("Matching (PRO)", 'quiz-maker'),
    "upload_file"       => esc_html__("Upload File (PRO)", 'quiz-maker'),
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
$true_or_false_arr = array(
    esc_html__("True", 'quiz-maker'),
    esc_html__("False", 'quiz-maker'),
);
$text_types = array( "text", "number", "short_text", "date" );
$not_multiple_text_types = array( "number", "date" );
switch ($question["type"]) {
    case "number":
        $question_type = 'number';
        break;
    case "text":
        $question_type = 'radio';
        break;
    case "checkbox":
        $question_type = 'checkbox';
        break;
    case "true_or_false":
        $question_type = 'radio';
        $ays_answer_default_count = 2;
        break;
    default:
        $question_type = 'radio';
        break;
}
$is_text_type = in_array($question["type"], $text_types);
$text_type = ($is_text_type && !in_array($question["type"], $not_multiple_text_types) ) ? true : false;

$only_text_types = array( "text","short_text" );
$is_only_text_type = in_array($question["type"], $only_text_types);

$only_number_types = array( "number" );
$is_only_number_type = in_array($question["type"], $only_number_types);

$only_checkbox_type = array( "checkbox" );
$is_only_checkbox_type = in_array($question["type"], $only_checkbox_type);

$only_radio_type = array( "radio", "checkbox", "true_or_false" );
$is_only_radio_type = in_array($question["type"], $only_radio_type);

// Not influence to score
$question['not_influence_to_score'] = ! isset($question['not_influence_to_score']) ? 'off' : $question['not_influence_to_score'];
$not_influence_to_score = (isset($question['not_influence_to_score']) && $question['not_influence_to_score'] == 'on') ? true : false;

if ($quiz_enable_question_not_influence_to_score) {
    if ($action == 'add') {
        $not_influence_to_score = true;
    }
}

// Use HTML for answers
$options['use_html'] = ! isset($options['use_html']) ? 'off' : $options['use_html'];
$use_html = (isset($options['use_html']) && $options['use_html'] == 'on') ? true : false;

if ($quiz_enable_question_allow_html) {
    if ($action == 'add') {
        $use_html = true;
    }
}

$question_title = (isset($question['question_title']) && $question['question_title']) ? esc_attr( $question['question_title'] ) : '';
$question_title = stripslashes( $question_title );

// Maximum length of a text field
$options['enable_question_text_max_length'] = isset($options['enable_question_text_max_length']) ? sanitize_text_field( $options['enable_question_text_max_length'] ) : 'off';
$enable_question_text_max_length = (isset($options['enable_question_text_max_length']) && $options['enable_question_text_max_length'] == 'on') ? true : false;

// Length
$question_text_max_length = ( isset($options['question_text_max_length']) && sanitize_text_field( $options['question_text_max_length'] ) != '' ) ? absint( intval( $options['question_text_max_length'] ) ) : '';

// Limit by
$question_limit_text_type = ( isset($options['question_limit_text_type']) && sanitize_text_field( $options['question_limit_text_type'] ) != '' ) ? sanitize_text_field( $options['question_limit_text_type'] ) : 'characters';

// Show the counter-message
$options['question_enable_text_message'] = isset($options['question_enable_text_message']) ? sanitize_text_field( $options['question_enable_text_message'] ) : 'off';
$question_enable_text_message = (isset($options['question_enable_text_message']) && $options['question_enable_text_message'] == 'on') ? true : false;

// Maximum length of a number field
$options['enable_question_number_max_length'] = isset($options['enable_question_number_max_length']) ? sanitize_text_field( $options['enable_question_number_max_length'] ) : 'off';
$enable_question_number_max_length = (isset($options['enable_question_number_max_length']) && sanitize_text_field( $options['enable_question_number_max_length'] ) == 'on') ? true : false;

// Length
$question_number_max_length = ( isset($options['question_number_max_length']) && sanitize_text_field( $options['question_number_max_length'] ) != '' ) ? intval( sanitize_text_field( $options['question_number_max_length'] ) ) : '';

// Hide question text on the front-end
$options['quiz_hide_question_text'] = isset($options['quiz_hide_question_text']) ? sanitize_text_field( $options['quiz_hide_question_text'] ) : 'off';
$quiz_hide_question_text = (isset($options['quiz_hide_question_text']) && $options['quiz_hide_question_text'] == 'on') ? true : false;

if ($quiz_enable_question_hide_question_text) {
    if ($action == 'add') {
        $quiz_hide_question_text = true;
    }
}

// Enable maximum selection number
$options['enable_max_selection_number'] = isset($options['enable_max_selection_number']) ? sanitize_text_field( $options['enable_max_selection_number'] ) : 'off';
$enable_max_selection_number = (isset($options['enable_max_selection_number']) && sanitize_text_field( $options['enable_max_selection_number'] ) == 'on') ? true : false;

// Max value
$max_selection_number = ( isset($options['max_selection_number']) && $options['max_selection_number'] != '' ) ? intval( sanitize_text_field( $options['max_selection_number'] ) ) : '';

// Note text
$quiz_question_note_message = ( isset( $options['quiz_question_note_message']) && $options['quiz_question_note_message'] != '' ) ? stripslashes( $options['quiz_question_note_message'] ) : '';

// Enable case sensitive text
$options['enable_case_sensitive_text'] = isset($options['enable_case_sensitive_text']) ? sanitize_text_field( $options['enable_case_sensitive_text'] ) : 'off';
$enable_case_sensitive_text = (isset($options['enable_case_sensitive_text']) && sanitize_text_field( $options['enable_case_sensitive_text'] ) == 'on') ? true : false;

if ($quiz_case_sensitive_text) {
    if ($action == 'add') {
        $enable_case_sensitive_text = true;
    }
}

// Minimum length of a number field
$options['enable_question_number_min_length'] = isset($options['enable_question_number_min_length']) ? sanitize_text_field( $options['enable_question_number_min_length'] ) : 'off';
$enable_question_number_min_length = (isset($options['enable_question_number_min_length']) && sanitize_text_field( $options['enable_question_number_min_length'] ) == 'on') ? true : false;

// Length
$question_number_min_length = ( isset($options['question_number_min_length']) && sanitize_text_field( $options['question_number_min_length'] ) != '' ) ? intval( sanitize_text_field( $options['question_number_min_length'] ) ) : '';

// Show error message
$options['enable_question_number_error_message'] = isset($options['enable_question_number_error_message']) ? sanitize_text_field( $options['enable_question_number_error_message'] ) : 'off';
$enable_question_number_error_message = (isset($options['enable_question_number_error_message']) && sanitize_text_field( $options['enable_question_number_error_message'] ) == 'on') ? true : false;

// Message
$question_number_error_message = ( isset($options['question_number_error_message']) && sanitize_text_field( $options['question_number_error_message'] ) != '' ) ? stripslashes( esc_attr( sanitize_text_field( $options['question_number_error_message'] ) ) ) : '';

// Enable minimum selection number
$options['enable_min_selection_number'] = isset($options['enable_min_selection_number']) ? sanitize_text_field( $options['enable_min_selection_number'] ) : 'off';
$enable_min_selection_number = (isset($options['enable_min_selection_number']) && sanitize_text_field( $options['enable_min_selection_number'] ) == 'on') ? true : false;

// Min value
$min_selection_number = ( isset($options['min_selection_number']) && $options['min_selection_number'] != '' ) ? intval( sanitize_text_field( $options['min_selection_number'] ) ) : '';

// Disable strip slashes for answers
$options['quiz_disable_answer_stripslashes'] = isset($options['quiz_disable_answer_stripslashes']) ? sanitize_text_field( $options['quiz_disable_answer_stripslashes'] ) : 'off';
$quiz_disable_answer_stripslashes = (isset($options['quiz_disable_answer_stripslashes']) && $options['quiz_disable_answer_stripslashes'] == 'on') ? true : false;

if ($quiz_stripslashes_for_answer) {
    if ($action == 'add') {
        $quiz_disable_answer_stripslashes = true;
    }
}

// Enable strip slashes for questions
$options['quiz_enable_question_stripslashes'] = isset($options['quiz_enable_question_stripslashes']) ? sanitize_text_field( $options['quiz_enable_question_stripslashes'] ) : 'off';
$quiz_enable_question_stripslashes = (isset($options['quiz_enable_question_stripslashes']) && $options['quiz_enable_question_stripslashes'] == 'on') ? true : false;

?>

<div class="wrap ays-quiz-dashboard-main-wrap ays-quiz-dashboard-main-nowrap">
    <div class="container-fluid">
        <div class="ays-quiz-heading-box ays-quiz-heading-box-margin-top">
            <div class="ays-quiz-wordpress-user-manual-box">
                <a href="https://quiz-plugin.com/docs/" target="_blank">
                    <i class="ays_fa ays_fa_file_text" ></i> 
                    <span style="margin-left: 3px;text-decoration: underline;"><?php echo esc_html__("View Documentation", "quiz-maker"); ?></span>
                </a>
            </div>
        </div>
        <h1 class="wp-heading-inline">
        <?php
            echo wp_kses_post($heading);
        ?>
        </h1>
        <?php do_action('ays_quiz_sale_banner'); ?>
        <form method="post" class="ays-quiz-main-form" id="ays-question-form">
            <input type="hidden" name="ays_question_tab" value="<?php echo esc_attr($ays_question_tab); ?>">
            <input type="hidden" name="ays_question_ctrate_date" value="<?php echo esc_attr( $question_create_date ); ?>">
            <input type="hidden" name="ays_question_author" value="<?php echo esc_attr(json_encode($question_author)); ?>">
            <input type="hidden" class="quiz_wp_editor_height" value="<?php echo esc_attr( $quiz_wp_editor_height ); ?>">
            <div class="ays-top-menu-container-wrapper">
                <div class="ays-top-menu-wrapper">
                    <div class="ays_menu_left" data-scroll="0"><i class="ays_fa ays_fa_angle_left"></i></div>
                    <div class="ays-top-menu">
                        <div class="nav-tab-wrapper ays-top-tab-wrapper">
                            <a href="#tab1" data-tab="tab1" class="nav-tab <?php echo ($ays_question_tab == 'tab1') ? 'nav-tab-active' : ''; ?>">
                                <?php echo esc_html__("General", 'quiz-maker');?>
                            </a>
                            <a href="#tab2" data-tab="tab2" class="nav-tab <?php echo ($ays_question_tab == 'tab2') ? 'nav-tab-active' : ''; ?>">
                                <?php echo esc_html__("Settings", 'quiz-maker');?>
                            </a>
                        </div>
                    </div>
                    <div class="ays_menu_right" data-scroll="-1"><i class="ays_fa ays_fa_angle_right"></i></div>
                </div>
                <div class="ays-quiz-add-new-button-box" style="">
                <?php
                    echo wp_kses_post($loader_iamge);
                    $other_attributes = array(
                        'id' => 'ays-button-apply-top',
                        'title' => 'Ctrl + s',
                        'data-toggle' => 'tooltip',
                        'data-delay'=> '{"show":"1000"}'
                    );
                    
                    submit_button(esc_html__('Save', 'quiz-maker'), 'primary ays-button ays-quiz-loader-banner', 'ays_apply_top', false, $other_attributes);

                    $other_attributes = array('id' => 'ays-button-save-top');
                    submit_button(esc_html__('Save and close', 'quiz-maker'), 'ays-button ays-quiz-loader-banner', 'ays_submit_top', false, $other_attributes);
                    $other_attributes = array('id' => 'ays-button-save-new-top');
                    submit_button(esc_html__('Save and new', 'quiz-maker'), 'ays-button ays-quiz-loader-banner', 'ays_save_new_top', false, $other_attributes);
                ?>
                </div>
            </div>
                
            <div id="tab1" class="ays-quiz-tab-content <?php echo ($ays_question_tab == 'tab1') ? 'ays-quiz-tab-content-active' : ''; ?>">
                <p class="ays-subtitle"><?php echo esc_html__('General Settings','quiz-maker')?></p>
                <hr class="ays-quiz-bolder-hr"/>
                <div class="form-group row ays-field">
                    <div class="col-sm-3">
                        <label for='ays-question'>
                            <?php echo esc_html__('Question image', 'quiz-maker'); ?>
                        </label>
                    </div>
                    <div class="col-sm-9">
                        <div>
                            <a href="javascript:void(0)" class="add-question-image" style="margin: 0;"><?php echo wp_kses_post($image_text); ?></a>
                        </div>
                        <p class="ays_quiz_small_hint_text_for_message_variables" style="margin-top: 5px;">
                            <span><?php echo esc_html__( "Please Note" , 'quiz-maker' ); ?></span>
                            <a class="ays_help" data-toggle="tooltip" title="<?php echo esc_attr( __('* Note: The plugin doesn’t make any changes concerning the images. It takes the images in a size, in which you have uploaded them. We are using the default WP Media. If the uploaded image is blurred and has a low quality, make sure to choose the right parameters (Full size) while uploading the images. You can find the Full Size option in the opened Add Media popup (Attachment Display Settings).','quiz-maker') ); ?>">
                                <i class="ays_fa ays_fa_info_circle"></i>
                            </a>
                        </p>
                        <div class="ays-question-image-container" style="<?php echo esc_attr($style); ?>">
                            <span class="ays-remove-question-img"></span>
                            <img src="<?php echo esc_url($question_image); ?>" id="ays-question-img"/>
                            <input type="hidden" name="ays_question_image" id="ays-question-image" value="<?php echo esc_url($question_image); ?>"/>
                        </div>
                    </div>
                </div> <!-- Question image -->
                <hr/>
                <div class="form-group row ays-field">
                    <div class="col-sm-3">
                        <label for='ays-question'><?php echo esc_html__('Question', 'quiz-maker'); ?>
                        </label>
                    </div>
                    <div class="col-sm-9">
                        <?php
                            $content = $q_question_title;
                            $editor_id = 'ays-question';
                            $settings = array('editor_height' => $quiz_wp_editor_height, 'textarea_name' => 'ays_question', 'editor_class' => 'ays-textarea', 'media_buttons' => true);
                            wp_editor($content, $editor_id, $settings);
                        ?>
                    </div>
                </div> <!-- Question -->
                <hr/>
                <div class="form-group row">
                    <div class="col-sm-3">
                        <label for="ays-type">
                            <?php echo esc_html__('Question type', 'quiz-maker'); ?>
                            <a class="ays_help" data-toggle="tooltip" data-html="true" 
                                title="<?php 
                                    echo esc_attr( __('Choose the type of question.','quiz-maker') .
                                    "<ul style='list-style-type: circle;padding-left: 20px;'>".
                                        "<li>Radio - ". __('Multiple choice question with one Correct answer.','quiz-maker') ."</li>".
                                        "<li>Checkbox - ". __('Multiple choice question with multiple Correct answers.','quiz-maker') ."</li>".
                                        "<li>Dropdown - ". __('Multiple choice questions with one Correct answer, which will be displayed as Dropdown.','quiz-maker') ."</li>".
                                        "<li>Text</li>".
                                        "<li>Number</li>".
                                    "</ul>");
                                ?>">
                                <i class="ays_fa ays_fa_info_circle"></i>
                            </a>
                        </label>
                        <div class="ays-quiz-heading-box ays-quiz-unset-float">
                            <div class="ays-quiz-wordpress-user-manual-box">
                                <a href="https://www.youtube.com/watch?v=ok6f59iV_R0" target="_blank" style="text-decoration: none;">
                                    <?php echo wp_kses_post($youtube_icon_svg); ?>
                                    <span style="margin-left: 3px; text-decoration: underline;"><?php echo esc_html__("View All Question Types - video", 'quiz-maker'); ?></span>
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-9">
                        <select id="ays-type" name="ays_question_type">
                            <option></option>
                            <?php
                                foreach($question_types as $type => $label):
                                $selected = $question["type"] == $type ? "selected" : "";
                                $ays_question_disabled = "";
                                if ( $type == "custom" || $type == "fill_in_blank" || $type == "matching" || $type == "upload_file" ) {
                                    $ays_question_disabled = "disabled title='". esc_attr(__( "This feature is available only in PRO version", 'quiz-maker' ) ) ."' ";
                                }
                            ?>
                            <option value="<?php echo esc_attr($type); ?>" data-nkar="<?php echo esc_attr($question_types_icon_url[ $type ]); ?>" <?php echo esc_attr($selected); ?> <?php echo esc_attr($ays_question_disabled); ?> ><?php echo esc_attr($label); ?></option>
                            <?php
                                endforeach;
                            ?>
                        </select>
                    </div>
                </div>
                <hr/>
                <div>
                    <label class='ays-label' for="ays-answers-table">
                       <?php
                            if($is_text_type):
                        ?>
                       <?php echo esc_html__('Answer', 'quiz-maker'); ?>
                        <?php
                            else:
                        ?>
                       <?php echo esc_html__('Answers', 'quiz-maker'); ?>
                       <?php
                            endif;
                        ?>
                    </label>
                </div>
                <div class="ays-field ays-table-wrap">
                    <table class="ays-answers-table " id="ays-answers-table" ays_default_count="<?php echo esc_attr($ays_answer_default_count); ?>">
                        <thead>
                            <tr class="ui-state-default">
                            <?php if(! $is_text_type): ?>
                                <th class="th-150 removable ays-quiz-question-answer-ordering-row"><?php echo esc_html__('Ordering', 'quiz-maker'); ?></th>
                                <th class="th-150 removable ays-quiz-question-answer-correct-row"><?php echo esc_html__('Correct', 'quiz-maker'); ?></th>
                            <?php endif; ?>
                                <th <?php echo ($is_text_type) ? 'class="th-650"' : 'class="ays-quiz-question-answer-answer-row" style="width:500px;"'; ?>><?php echo esc_html__('Answer', 'quiz-maker'); ?></th>
                                <th class="only_pro ays-weight-row ays-quiz-question-answer-weight-point-row" style="width:120px;padding:0;"><?php echo esc_html__('Point', 'quiz-maker'); ?><br>
                                    <a href="https://ays-pro.com/wordpress/quiz-maker?utm_source=dashboard&utm_medium=quiz-free&utm_campaign=answer-points-<?php echo esc_attr( AYS_QUIZ_UTM_VERSION ); ?>" tabindex="-1" target="_blank" class="ays-quiz-new-upgrade-button-link ays-quiz-new-upgrade-button-without-text-link">
                                        <div class="ays-quiz-new-upgrade-button-box">
                                            <div>
                                                <img src="<?php echo esc_url(AYS_QUIZ_ADMIN_URL.'/images/icons/locked_24x24.svg'); ?>">
                                                <img src="<?php echo esc_url(AYS_QUIZ_ADMIN_URL.'/images/icons/unlocked_24x24.svg'); ?>" class="ays-quiz-new-upgrade-button-hover">
                                            </div>
                                        </div>
                                    </a>
                                </th>
                            <?php if( $question["type"] == 'checkbox' || $question["type"] == 'radio' || $question["type"] == 'select' || $question["type"] == 'true_or_false' ): ?>
                                <th class="only_pro th-150 removable ays-quiz-question-answer-keyword-row" style="width:120px;padding:0;">
                                    <?php echo esc_html__('Keyword', 'quiz-maker'); ?><br>
                                    <a href="https://ays-pro.com/wordpress/quiz-maker?utm_source=dashboard&utm_medium=quiz-free&utm_campaign=answer-keywords-<?php echo esc_attr( AYS_QUIZ_UTM_VERSION ); ?>" tabindex="-1" target="_blank" class="ays-quiz-new-upgrade-button-link ays-quiz-new-upgrade-button-without-text-link">
                                        <div class="ays-quiz-new-upgrade-button-box">
                                            <div>
                                                <img src="<?php echo esc_url(AYS_QUIZ_ADMIN_URL.'/images/icons/locked_24x24.svg'); ?>">
                                                <img src="<?php echo esc_url(AYS_QUIZ_ADMIN_URL.'/images/icons/unlocked_24x24.svg'); ?>" class="ays-quiz-new-upgrade-button-hover">
                                            </div>
                                        </div>
                                    </a>
                                </th>
                            <?php endif; ?>
                            <?php if(! $is_text_type): ?>
                                <th class="th-150 removable ays-quiz-question-answer-image-row"><?php echo esc_html__('Image', 'quiz-maker'); ?></th>
                                <th class="th-150 removable ays-quiz-question-answer-delete-row"><?php echo esc_html__('Delete', 'quiz-maker'); ?></th>
                            <?php endif; ?>
                            <?php if($is_text_type && $question["type"] != 'date'): ?>
                                <th class="th-350 reremoveable ays-quiz-question-answer-placeholder-row"><?php echo esc_html__('Placeholder', 'quiz-maker'); ?></th>
                            <?php endif; ?>
                            </tr>
                        </thead>
                        <tbody class="<?php echo ($is_text_type) ? 'text_answer' : '';?>">
                        <?php if (empty($answers)) : ?>
                        <?php
                            if($question["type"] == 'number'):
                            ?>
                            <tr class="ays-answer-row ui-state-default">
                                <td>
                                    <input style="display:none;" class="ays-correct-answer" type="checkbox" name="ays-correct-answer[]" value="1" checked/>
                                    <input type="number" step="any" name="ays-correct-answer-value[]" class="ays-correct-answer-value" value=""/>
                                </td>
                                <td title="This property available only in pro version" class="only_pro">
                                    <div class="pro_features"></div>
                                    <input class="w-100" type="number" value="1" tabindex="-1"/>
                                </td>
                                <td>
                                    <input type="text" name="ays-answer-placeholder[]" class="ays-correct-answer-value" value=""/>
                                </td>
                            </tr>
                            <?php
                            elseif($question["type"] == 'short_text'):
                            ?>
                            <tr class="ays-answer-row ui-state-default">
                                <td>
                                    <input style="display:none;" class="ays-correct-answer" type="checkbox" name="ays-correct-answer[]" value="1" checked/>
                                    <input type="text" name="ays-correct-answer-value[]" class="ays-correct-answer-value" value=""/>
                                </td>
                                <td title="This property available only in pro version" class="only_pro">
                                    <div class="pro_features"></div>
                                    <input class="w-100" type="number" value="1" tabindex="-1"/>
                                </td>
                                <td>
                                    <input type="text" name="ays-answer-placeholder[]" class="ays-correct-answer-value" value=""/>
                                </td>
                            </tr>
                            <?php
                            elseif($question["type"] == 'text'):
                            ?>
                            <tr class="ays-answer-row ui-state-default">
                                <td>
                                    <input style="display:none;" class="ays-correct-answer" type="checkbox" name="ays-correct-answer[]" value="1" checked/>
                                    <textarea type="text" name="ays-correct-answer-value[]" class="ays-correct-answer-value"></textarea>
                                </td>
                                <td title="This property available only in pro version" class="only_pro">
                                    <div class="pro_features"></div>
                                    <input class="w-100" type="number" value="1" tabindex="-1"/>
                                </td>
                                <td>
                                    <input type="text" name="ays-answer-placeholder[]" class="ays-correct-answer-value" value=""/>
                                </td>
                            </tr>
                            <?php
                            elseif($question["type"] == 'date'):
                            ?>
                            <tr class="ays-answer-row ui-state-default">
                                <td>
                                    <input style="display:none;" class="ays-correct-answer" type="checkbox" name="ays-correct-answer[]" value="1" checked/>
                                    <input type="date" name="ays-correct-answer-value[]" class="ays-date-input ays-correct-answer-value" placeholder="<?php echo esc_attr("e. g. " . current_time( 'Y-m-d' )); ?>">
                                </td>
                                <td title="This property available only in pro version" class="only_pro">
                                    <div class="pro_features"></div>
                                    <input class="w-100" type="number" value="1" tabindex="-1"/>
                                </td>
                            </tr>
                            <?php
                            else:

                                for ($ays_i=0; $ays_i < $ays_answer_default_count; $ays_i++) :
                                    $ays_even_or_not =  ($ays_i%2 !=0) ? 'even' : '';

                                    $true_or_false_val = '';
                                    if ( $question["type"] == 'true_or_false' ) {
                                        if ( isset( $true_or_false_arr[ $ays_i ] ) ) {
                                            $true_or_false_val = $true_or_false_arr[ $ays_i ];
                                        }
                                    }
                            ?>

                            <tr class="ays-answer-row ui-state-default <?php echo esc_attr($ays_even_or_not); ?>">
                                <td class="ays-quiz-question-answer-ordering-row"><i class="ays_fa ays_fa_arrows" aria-hidden="true"></i></td>
                                <td class="ays-quiz-question-answer-correct-row">
                                    <span>
                                        <input type="<?php echo esc_attr($question_type); ?>" id="ays-correct-answer-<?php echo esc_attr($ays_i+1); ?>" class="ays-correct-answer" name="ays-correct-answer[]" value="<?php echo esc_attr($ays_i+1); ?>"/>
                                        <label for="ays-correct-answer-<?php echo esc_attr($ays_i+1); ?>"></label>
                                    </span>
                                </td>
                                <td class="ays-quiz-question-answer-answer-row"><input type="text" name="ays-correct-answer-value[]" class="ays-correct-answer-value"/ value="<?php echo esc_attr($true_or_false_val); ?>"></td>
                                
                                <td title="This property available only in pro version" class="only_pro ays-quiz-question-answer-weight-point-row">
                                    <div class="pro_features"></div>
                                    <input class="w-100" type="number" value="1" tabindex="-1"/>
                                </td>
                                <td title="This property available only in pro version" class="only_pro ays-quiz-question-answer-keyword-row">
                                    <div class="pro_features"></div>
                                    <select class="ays_quiz_keywords" tabindex="-1">
                                        <option value="A">A</option>
                                    </select>
                                </td>
                                <td class="ays-quiz-question-answer-image-row">
                                    <label class='ays-label' for='ays-answer'><a href="javascript:void(0)" class="add-answer-image" style="display:block;"><?php echo esc_html__('Add', 'quiz-maker'); ?></a></label>
                                    <div class="ays-answer-image-container ays-answer-image-container-div" style="display:none;">
                                        <span class="ays-edit-answer-img" title="<?php echo esc_html__('Edit Image', 'quiz-maker'); ?>"></span>
                                        <span class="ays-remove-answer-img" title="<?php echo esc_html__('Delete Image', 'quiz-maker'); ?>"></span>
                                        <img src="" class="ays-answer-img"/>
                                        <input type="hidden" name="ays_answer_image[]" class="ays-answer-image-path" value=""/>
                                    </div>
                                </td>

                                <td class="ays-quiz-question-answer-delete-row">
                                    <a href="javascript:void(0)" class="ays-delete-answer">
                                        <i class="ays_fa ays_fa_minus_square" aria-hidden="true"></i>
                                    </a>
                                </td>
                            </tr>
                            
                            <?php endfor; ?>
                            <?php
                            endif;
                        ?>
                        <?php
                        else:
                            foreach ($answers as $index => $answer) {
                                $class = (($index + 1) % 2 == 0) ? "even" : "";
                                $answer_text =  __('Add', 'quiz-maker');
                                
                                switch ($question["type"]) {
                                    case "number":
                                        $question_type = 'number';
                                        break;
                                    case "text":
                                        $question_type = 'radio';
                                        break;
                                    case "short_text":
                                        $question_type = 'text';
                                        break;
                                    case "checkbox":
                                        $question_type = 'checkbox';
                                        break;
                                    case "checkbox":
                                    default:
                                        $question_type = 'radio';
                                        break;
                                }
                                ?>
                                <tr class="ays-answer-row ui-state-default <?php echo esc_attr($class); ?>">
                                    <?php
                                        if($question["type"] == 'number'):
                                    ?>
                                    <td>
                                        <input style="display:none;" class="ays-correct-answer" type="checkbox" name="ays-correct-answer[]" value="1" checked/>
                                        <input type="<?php echo esc_attr($question_type); ?>" step="any" name="ays-correct-answer-value[]" class="ays-correct-answer-value" value="<?php echo esc_attr(stripslashes($answer["answer"])); ?>"/>
                                    </td>
                                    <td title="This property available only in pro version" class="only_pro">
                                        <div class="pro_features"></div>
                                        <input class="w-100" type="number" value="1" tabindex="-1"/>
                                    </td>
                                    <td>
                                        <input type="text" name="ays-answer-placeholder[]" class="ays-correct-answer-value" value="<?php echo esc_attr(stripslashes($answer["placeholder"])); ?>"/>
                                    </td>
                                    <?php
                                        elseif($question["type"] == 'short_text'):
                                    ?>
                                    <td>
                                        <input style="display:none;" class="ays-correct-answer" type="checkbox" name="ays-correct-answer[]" value="1" checked/>
                                        <input type="<?php echo esc_attr($question_type); ?>" name="ays-correct-answer-value[]" class="ays-correct-answer-value" value="<?php echo esc_attr(stripslashes($answer["answer"])); ?>"/>
                                    </td>
                                    <td title="This property available only in pro version" class="only_pro">
                                        <div class="pro_features"></div>
                                        <input class="w-100" type="number" value="1" tabindex="-1"/>
                                    </td>
                                    <td>
                                        <input type="text" name="ays-answer-placeholder[]" class="ays-correct-answer-value" value="<?php echo esc_attr(stripslashes($answer["placeholder"])); ?>"/>
                                    </td>
                                    <?php
                                        elseif($question["type"] == 'text'):
                                    ?>
                                    <td>
                                        <input style="display:none;" class="ays-correct-answer" type="checkbox" name="ays-correct-answer[]" value="1" checked/>
                                        <textarea type="text" name="ays-correct-answer-value[]" class="ays-correct-answer-value"><?php echo esc_attr(stripslashes($answer["answer"])); ?></textarea>
                                    </td>
                                    <td title="This property available only in pro version" class="only_pro">
                                        <div class="pro_features"></div>
                                        <input class="w-100" type="number" value="1" tabindex="-1"/>
                                    </td>
                                    <td>
                                        <input type="text" name="ays-answer-placeholder[]" class="ays-correct-answer-value" value="<?php echo esc_attr(stripslashes($answer["placeholder"])); ?>"/>
                                    </td>
                                    <?php
                                    elseif($question["type"] == 'date'):
                                    ?>
                                    <td>
                                        <input style="display:none;" class="ays-correct-answer" type="checkbox" name="ays-correct-answer[]" value="1" checked/>
                                        <input type="date" name="ays-correct-answer-value[]" class="ays-date-input ays-correct-answer-value" placeholder="<?php echo esc_attr("e. g. " . current_time( 'Y-m-d' )); ?>" value="<?php echo esc_attr(stripslashes($answer["answer"])); ?>">
                                    </td>
                                    <td title="This property available only in pro version" class="only_pro">
                                        <div class="pro_features"></div>
                                        <input class="w-100" type="number" value="1" tabindex="-1"/>
                                    </td>
                                    <?php
                                        else:
                                    ?>
                                    <td class="ays-quiz-question-answer-ordering-row"><i class="ays_fa ays_fa_arrows" aria-hidden="true"></i></td>
                                    <td class="ays-quiz-question-answer-correct-row">
                                        <span>
                                            <input type="<?php echo esc_attr($question_type); ?>" id="ays-correct-answer-<?php echo esc_attr($index + 1); ?>" class="ays-correct-answer" name="ays-correct-answer[]" value="<?php echo esc_attr($index + 1); ?>" <?php echo ($answer["correct"] == 1) ? "checked" : ""; ?>/>
                                            <label for="ays-correct-answer-<?php echo esc_attr($index + 1); ?>"></label>
                                        </span>
                                    </td>
                                    <td class="ays-quiz-question-answer-answer-row"><input type="text" name="ays-correct-answer-value[]" class="ays-correct-answer-value" value="<?php echo esc_attr(stripslashes($answer["answer"])); ?>"/></td>

                                    <td title="This property available only in pro version" class="only_pro ays-quiz-question-answer-weight-point-row">
                                        <div class="pro_features"></div>
                                        <input class="w-100" type="number" value="1" tabindex="-1"/>
                                    </td>

                                    <td title="This property available only in pro version" class="only_pro ays-quiz-question-answer-keyword-row">
                                        <div class="pro_features"></div>
                                        <select class="ays_quiz_keywords" tabindex="-1">
                                            <option value="A">A</option>
                                        </select>
                                    </td>
                                    <td class="ays-quiz-question-answer-image-row">
                                        <label class='ays-label' for='ays-answer'><a href="javascript:void(0)" class="add-answer-image" <?php echo (is_null($answer['image'])||$answer['image']=='') ? "style=display:block;":"style=display:none"?>><?php echo $answer_text; ?></a></label>
                                        <div class="ays-answer-image-container ays-answer-image-container-div" <?php echo (is_null($answer['image'])||$answer['image']=='') ? "style=display:none; ":"style=display:block"?>>
                                            <span class="ays-edit-answer-img" title="<?php echo esc_html__('Edit Image', 'quiz-maker'); ?>"></span>
                                            <span class="ays-remove-answer-img" title="<?php echo esc_html__('Delete Image', 'quiz-maker'); ?>"></span>
                                            <img src="<?php echo $answer['image']; ?>" class="ays-answer-img"/>
                                            <input type="hidden" name="ays_answer_image[]" class="ays-answer-image-path" value="<?php echo !empty( $answer['image'] ) ? esc_url($answer['image']) : ''; ?>"/>
                                        </div>
                                    </td>
                                    <td>
                                        <a href="javascript:void(0)" class="ays-delete-answer">
                                            <i class="ays_fa ays_fa_minus_square" aria-hidden="true"></i>
                                        </a>
                                    </td>
                                    <?php
                                        endif;
                                    ?>
                                </tr>
                                <?php
                            }
                        endif;
                        ?>
                        </tbody>
                    </table>
                    <div class="ays-text-answers-desc <?php echo ($text_type) ? '' : 'display_none'; ?>" style="padding:5px;padding-top:15px;">
                        <blockquote>
                            <p style="margin:0px;"><?php echo esc_html__( "For inserting multiple possible correct answers, please use delimeter %%%.", 'quiz-maker' ); ?></p>
                            <p style="margin:0px;"><?php echo esc_html__( "Example:", 'quiz-maker' ); ?> <strong>US%%%USA%%%United States</strong></p>
                        </blockquote>
                    </div>
                </div>
                <div class="ays-answers-toolbar-bottom <?php echo ($is_text_type) ? 'display_none' : 'ays-answers-toolbar-bottom-center'; ?>" style="padding:5px;padding-top:10px;">
                    <label class='ays-label ays-add-answer-first-label' for="ays-answers-table">
                    <?php if($is_text_type): ?>
                    <?php echo esc_html__('Answer', 'quiz-maker'); ?>
                    <?php else: ?>
                    <div class="ays-answers-footer-toolbar <?php echo ($is_text_type) ? 'display_none' : ''; ?>" style="padding:5px;padding-top:10px;">
                        <a href="javascript:void(0)" class="ays-add-answer ays-add-question-answer">
                            <i class="ays_fa ays_fa_plus_square" aria-hidden="true"></i><?php echo esc_html__('Add Answer', 'quiz-maker'); ?>
                        </a>
                    </div>
                    <?php endif; ?>
                    </label>
                </div>
                <hr class="show_for_text_type <?php echo ($is_only_radio_type) ? '' : 'display_none'; ?>"/>
                <div class="form-group row ays_toggle_parent show_for_text_type <?php echo ($is_only_radio_type) ? '' : 'display_none'; ?>">
                    <div class="col-sm-3">
                        <label for="ays_enable_question_text_max_length">
                            <?php echo esc_html__( "Use HTML for answers", 'quiz-maker' ); ?>
                            <a class="ays_help" style="margin-right:15px;" data-toggle="tooltip" title="<?php echo esc_attr( __('Allowed tags list','quiz-maker') . ": <br>, <b>, <em>, <span>, <mark>, <del>, <ins>, <sup>, <sub>, <strong>, <code>, <samp>, <kbd>, <var>, <q>" ); ?>">
                                <i class="ays_fa ays_fa_info_circle"></i>
                            </a>
                        </label>
                    </div>
                    <div class="col-sm-9">
                        <input type="checkbox" name="ays-use-html" value="on" <?php echo $use_html ? "checked" : ""; ?>>
                    </div>
                </div>
                <hr class="show_for_text_type <?php echo ($is_only_text_type) ? '' : 'display_none'; ?>"/>
                <div class="form-group row ays_toggle_parent show_for_text_type <?php echo ($is_only_text_type) ? '' : 'display_none'; ?>">
                    <div class="col-sm-3">
                        <label for="ays_enable_question_text_max_length">
                            <?php echo esc_html__('Maximum length of a text field', 'quiz-maker'); ?>
                            <a class="ays_help" data-toggle="tooltip" title="<?php echo esc_attr( __( 'Restrict the number of characters to be inserted in the text field by the user.' , 'quiz-maker' ) ); ?>">
                                <i class="ays_fa ays_fa_info_circle"></i>
                            </a>
                        </label>
                    </div>
                    <div class="col-sm-1">
                        <input type="checkbox" class="ays-enable-timer1 ays_toggle_checkbox" id="ays_enable_question_text_max_length" name="ays_enable_question_text_max_length" value="on" <?php echo ($enable_question_text_max_length) ? 'checked' : ''; ?>>
                    </div>
                    <div class="col-sm-8 ays_toggle_target ays_divider_left <?php echo ($enable_question_text_max_length) ? '' : 'display_none'; ?>">
                        <div class="form-group row">
                            <div class="col-sm-3">
                                <label for="ays_question_limit_text_type">
                                    <?php echo esc_html__('Limit by', 'quiz-maker'); ?>
                                    <a class="ays_help" data-toggle="tooltip" title="<?php echo esc_attr( __('Choose your preferred type of limitation.','quiz-maker') ); ?>">
                                        <i class="ays_fa ays_fa_info_circle"></i>
                                    </a>
                                </label>
                            </div>
                            <div class="col-sm-9">
                                <select class="ays-text-input ays-text-input-select" id="ays_question_limit_text_type" name="ays_question_limit_text_type">
                                    <option value='characters' <?php echo ($question_limit_text_type == 'characters') ? 'selected' : '' ?> ><?php echo esc_html__( 'Characters' , 'quiz-maker' ); ?></option>
                                    <option value='words' <?php echo ($question_limit_text_type == 'words') ? 'selected' : '' ?> ><?php echo esc_html__( 'Words' , 'quiz-maker' ); ?></option>
                                </select>
                            </div>
                        </div>
                        <hr>
                        <div class="form-group row">
                            <div class="col-sm-3">
                                <label for="ays_question_text_max_length">
                                    <?php echo esc_html__('Length', 'quiz-maker'); ?>
                                    <a class="ays_help" data-toggle="tooltip" title="<?php echo esc_attr( __('Indicate the length of the characters/words.','quiz-maker') ); ?>">
                                        <i class="ays_fa ays_fa_info_circle"></i>
                                    </a>
                                </label>
                            </div>
                            <div class="col-sm-9">
                                <input type="number" id="ays_question_text_max_length" class="ays-text-input" name="ays_question_text_max_length" value="<?php echo esc_attr($question_text_max_length); ?>">
                            </div>
                        </div>
                        <hr>
                        <div class="form-group row">
                            <div class="col-sm-3">
                                <label for="ays_question_enable_text_message">
                                    <?php echo esc_html__('Show word/character counter', 'quiz-maker'); ?>
                                    <a class="ays_help" data-toggle="tooltip" title="<?php echo esc_attr( __('Tick the checkbox and the live box will appear under the text field. It will indicate the current state of word/character usage.','quiz-maker') ); ?>">
                                        <i class="ays_fa ays_fa_info_circle"></i>
                                    </a>
                                </label>
                            </div>
                            <div class="col-sm-9">
                                <div class="form-check form-check-inline">
                                    <input type="checkbox" id="ays_question_enable_text_message" name="ays_question_enable_text_message" value="on" <?php echo ($question_enable_text_message) ? "checked" : ""; ?> />
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <hr class="show_for_text_type <?php echo ($is_only_text_type) ? '' : 'display_none'; ?>"/>
                <div class="form-group row show_for_text_type <?php echo ($is_only_text_type) ? '' : 'display_none'; ?>">
                    <div class="col-sm-3">
                        <label for="ays_enable_case_sensitive_text">
                            <?php echo esc_html__('Enable case sensitive text', 'quiz-maker'); ?>
                            <a class="ays_help" data-toggle="tooltip" title="<?php echo esc_attr( __( 'When this option is enabled, the user’s answer should be written in the particular form. For example, if the right answer is “true”, the “TRUE” will be counted as a wrong one.', 'quiz-maker' ) ); ?>">
                                <i class="ays_fa ays_fa_info_circle"></i>
                            </a>
                        </label>
                    </div>
                    <div class="col-sm-9">
                        <input type="checkbox" class="ays-enable-timer1" id="ays_enable_case_sensitive_text" name="ays_enable_case_sensitive_text" value="on" <?php echo ($enable_case_sensitive_text) ? 'checked' : ''; ?>>
                    </div>
                </div>
                <hr class="show_for_number_type <?php echo ($is_only_number_type) ? '' : 'display_none'; ?>"/>
                <div class="form-group row ays_toggle_parent show_for_number_type <?php echo ($is_only_number_type) ? '' : 'display_none'; ?>">
                    <div class="col-sm-3">
                        <label for="ays_enable_question_number_max_length">
                            <?php echo esc_html__('Maximum value of a number field', 'quiz-maker'); ?>
                            <a class="ays_help" data-toggle="tooltip" title="<?php echo esc_attr( __( 'Give a maximum legal value to your number field. For example, if you give a 20 value to the field, then the user will be able to answer the question by writing a value less than or equal to 20.' , 'quiz-maker' ) ); ?>">
                                <i class="ays_fa ays_fa_info_circle"></i>
                            </a>
                        </label>
                    </div>
                    <div class="col-sm-1">
                        <input type="checkbox" class="ays-enable-timer1 ays_toggle_checkbox" id="ays_enable_question_number_max_length" name="ays_enable_question_number_max_length" value="on" <?php echo ($enable_question_number_max_length) ? 'checked' : ''; ?>>
                    </div>
                    <div class="col-sm-8 ays_toggle_target ays_divider_left <?php echo ($enable_question_number_max_length) ? '' : 'display_none'; ?>">
                        <div class="form-group row">
                            <div class="col-sm-3">
                                <label for="ays_question_number_max_length">
                                    <?php echo esc_html__('Max value', 'quiz-maker'); ?>
                                    <a class="ays_help" data-toggle="tooltip" title="<?php echo esc_attr( __('Specify the maximum value allowed.','quiz-maker') ); ?>">
                                        <i class="ays_fa ays_fa_info_circle"></i>
                                    </a>
                                </label>
                            </div>
                            <div class="col-sm-9">
                                <input type="number" id="ays_question_number_max_length" class="ays-text-input" name="ays_question_number_max_length" value="<?php echo esc_attr($question_number_max_length); ?>">
                            </div>
                        </div>
                    </div>
                </div>
                <hr class="show_for_number_type <?php echo ($is_only_number_type) ? '' : 'display_none'; ?>"/>
                <div class="form-group row ays_toggle_parent show_for_number_type <?php echo ($is_only_number_type) ? '' : 'display_none'; ?>">
                    <div class="col-sm-3">
                        <label for="ays_enable_question_number_min_length">
                            <?php echo esc_html__('Minimum value of a number field', 'quiz-maker'); ?>
                            <a class="ays_help" data-toggle="tooltip" title="<?php echo esc_attr( __( 'Give a minimum legal value to your number field. For example, if you give a 20 value to the field, then the user will be able to answer the question by writing a value more than or equal to 20.' , 'quiz-maker' ) ); ?>">
                                <i class="ays_fa ays_fa_info_circle"></i>
                            </a>
                        </label>
                    </div>
                    <div class="col-sm-1">
                        <input type="checkbox" class="ays-enable-timer1 ays_toggle_checkbox" id="ays_enable_question_number_min_length" name="ays_enable_question_number_min_length" value="on" <?php echo ($enable_question_number_min_length) ? 'checked' : ''; ?>>
                    </div>
                    <div class="col-sm-8 ays_toggle_target ays_divider_left <?php echo ($enable_question_number_min_length) ? '' : 'display_none'; ?>">
                        <div class="form-group row">
                            <div class="col-sm-3">
                                <label for="ays_question_number_min_length">
                                    <?php echo esc_html__('Min value', 'quiz-maker'); ?>
                                    <a class="ays_help" data-toggle="tooltip" title="<?php echo esc_attr( __('Specify the minimum value allowed. ','quiz-maker') ); ?>">
                                        <i class="ays_fa ays_fa_info_circle"></i>
                                    </a>
                                </label>
                            </div>
                            <div class="col-sm-9">
                                <input type="number" id="ays_question_number_min_length" class="ays-text-input" name="ays_question_number_min_length" value="<?php echo esc_attr($question_number_min_length); ?>">
                            </div>
                        </div>
                    </div>
                </div>
                <hr class="show_for_number_type <?php echo ($is_only_number_type) ? '' : 'display_none'; ?>"/>
                <div class="form-group row ays_toggle_parent show_for_number_type <?php echo ($is_only_number_type) ? '' : 'display_none'; ?>">
                    <div class="col-sm-3">
                        <label for="ays_enable_question_number_error_message">
                            <?php echo esc_html__('Show error message', 'quiz-maker'); ?>
                            <a class="ays_help" data-toggle="tooltip" title="<?php echo esc_attr( __( 'When the option is enabled; on the Number Question Type, if in the answer box is typed something else besides the numbers. The "Error text" message will appear.' , 'quiz-maker' ) ); ?>">
                                <i class="ays_fa ays_fa_info_circle"></i>
                            </a>
                        </label>
                    </div>
                    <div class="col-sm-1">
                        <input type="checkbox" class="ays-enable-timer1 ays_toggle_checkbox" id="ays_enable_question_number_error_message" name="ays_enable_question_number_error_message" value="on" <?php echo ($enable_question_number_error_message) ? 'checked' : ''; ?>>
                    </div>
                    <div class="col-sm-8 ays_toggle_target ays_divider_left <?php echo ($enable_question_number_error_message) ? '' : 'display_none'; ?>">
                        <div class="form-group row">
                            <div class="col-sm-3">
                                <label for="ays_question_number_error_message">
                                    <?php echo esc_html__('Message', 'quiz-maker'); ?>
                                    <a class="ays_help" data-toggle="tooltip" title="<?php echo esc_attr( __('Write the message, which you want to be shown on the front.','quiz-maker') ); ?>">
                                        <i class="ays_fa ays_fa_info_circle"></i>
                                    </a>
                                </label>
                            </div>
                            <div class="col-sm-9">
                                <input type="text" id="ays_question_number_error_message" class="ays-text-input" name="ays_question_number_error_message" value="<?php echo esc_attr($question_number_error_message); ?>">
                            </div>
                        </div>
                    </div>
                </div>
                <hr class="show_for_checkbox_type <?php echo ($is_only_checkbox_type) ? '' : 'display_none'; ?>"/>
                <div class="form-group row ays_toggle_parent show_for_checkbox_type <?php echo ($is_only_checkbox_type) ? '' : 'display_none'; ?>">
                    <div class="col-sm-3">
                        <label for="ays_enable_max_selection_number">
                            <?php echo esc_html__('Enable maximum selection number', 'quiz-maker'); ?>
                            <a class="ays_help" data-toggle="tooltip" title="<?php echo esc_attr( __( 'Allow users to choose more than one answer but not over the max value you provided. It will work with the Checkbox type.' , 'quiz-maker' ) ); ?>">
                                <i class="ays_fa ays_fa_info_circle"></i>
                            </a>
                        </label>
                    </div>
                    <div class="col-sm-1">
                        <input type="checkbox" class="ays-enable-timer1 ays_toggle_checkbox" id="ays_enable_max_selection_number" name="ays_enable_max_selection_number" value="on" <?php echo ($enable_max_selection_number) ? 'checked' : ''; ?>>
                    </div>
                    <div class="col-sm-8 ays_toggle_target ays_divider_left <?php echo ($enable_max_selection_number) ? '' : 'display_none'; ?>">
                        <div class="form-group row">
                            <div class="col-sm-3">
                                <label for="ays_max_selection_number">
                                    <?php echo esc_html__('Max value', 'quiz-maker'); ?>
                                    <a class="ays_help" data-toggle="tooltip" title="<?php echo esc_attr( __('Specify the maximum value allowed.','quiz-maker') ); ?>">
                                        <i class="ays_fa ays_fa_info_circle"></i>
                                    </a>
                                </label>
                            </div>
                            <div class="col-sm-9">
                                <input type="number" id="ays_max_selection_number" class="ays-text-input" name="ays_max_selection_number" value="<?php echo esc_attr($max_selection_number); ?>">
                            </div>
                        </div>
                    </div>
                </div>
                <hr class="show_for_checkbox_type <?php echo ($is_only_checkbox_type) ? '' : 'display_none'; ?>"/>
                <div class="form-group row ays_toggle_parent show_for_checkbox_type <?php echo ($is_only_checkbox_type) ? '' : 'display_none'; ?>">
                    <div class="col-sm-3">
                        <label for="ays_enable_min_selection_number">
                            <?php echo esc_html__('Enable minimum selection number', 'quiz-maker'); ?>
                            <a class="ays_help" data-toggle="tooltip" title="<?php echo esc_attr( __( 'Require users to choose answers not under the min value you provided. It will work with the Checkbox type.' , 'quiz-maker' ) ); ?>">
                                <i class="ays_fa ays_fa_info_circle"></i>
                            </a>
                        </label>
                    </div>
                    <div class="col-sm-1">
                        <input type="checkbox" class="ays-enable-timer1 ays_toggle_checkbox" id="ays_enable_min_selection_number" name="ays_enable_min_selection_number" value="on" <?php echo ($enable_min_selection_number) ? 'checked' : ''; ?>>
                    </div>
                    <div class="col-sm-8 ays_toggle_target ays_divider_left <?php echo ($enable_min_selection_number) ? '' : 'display_none'; ?>">
                        <div class="form-group row">
                            <div class="col-sm-3">
                                <label for="ays_min_selection_number">
                                    <?php echo esc_html__('Min value', 'quiz-maker'); ?>
                                    <a class="ays_help" data-toggle="tooltip" title="<?php echo esc_attr( __('Specify the minimum value allowed.','quiz-maker') ); ?>">
                                        <i class="ays_fa ays_fa_info_circle"></i>
                                    </a>
                                </label>
                            </div>
                            <div class="col-sm-9">
                                <input type="number" id="ays_min_selection_number" class="ays-text-input" name="ays_min_selection_number" value="<?php echo esc_attr( $min_selection_number ); ?>">
                            </div>
                        </div>
                    </div>
                </div>
                <hr />
                <div class="form-group row">
                    <div class="col-sm-3">
                        <label for="ays-category">
                            <?php echo esc_html__('Question category', 'quiz-maker'); ?>
                            <a class="ays_help" data-toggle="tooltip" title="<?php echo esc_attr( __('You can choose your desired category prepared in advance.','quiz-maker') ); ?>">
                                <i class="ays_fa ays_fa_info_circle"></i>
                            </a>
                        </label>
                    </div>
                    <div class="col-sm-9">
                        <select id="ays-category" name="ays_question_category">
                            <option></option>
                            <?php
                            $cat = 0;
                            foreach ($question_categories as $question_category) {
                                $checked = (intval($question_category['id']) == intval($question['category_id'])) ? "selected" : "";
                                if ($cat == 0 && intval($question['category_id']) == 0) {
                                    $checked = 'selected';
                                }
                                echo "<option value='" . esc_attr($question_category['id']) . "' " . esc_attr($checked) . ">" . esc_attr(stripslashes( $question_category['title'] )) . "</option>";
                                $cat++;
                            }
                            ?>
                        </select>
                        <div class="ays_quiz_small_hint_text_for_message_variables" style="margin-top: 5px;">
                        <span><?php
                            echo sprintf(
                                /* translators: 1: opening <a> tag, 2: closing </a> tag */
                                wp_kses_post( __( 'Create a new category %1$s here %2$s', 'quiz-maker' ) ),
                                '<a href="' . esc_url( $question_category_page_url ) . '" target="_blank" rel="noopener noreferrer">',
                                '</a>'
                            );
                        ?></div>
                    </div>
                </div>
                <hr>
                <!-- Question Tags Start -->
                <div class="form-group row">
                    <div class="col-sm-12 only_pro" style="padding:15px;">
                        <div class="pro_features">                            

                        </div>
                        <div class="form-group row">
                            <div class="col-sm-3">
                                <label for="ays_quiz_question_tags">
                                    <?php echo esc_html__('Question tags', 'quiz-maker'); ?>
                                    <a class="ays_help" data-toggle="tooltip" title="<?php echo esc_attr( __('Add tags to your questions. Tags are used for filtering questions to add to your quiz. You can choose as many tags as you want. To create tags, please go to Question categories > Tags > Add New.', 'quiz-maker') ); ?>">
                                        <i class="ays_fa ays_fa_info_circle"></i>
                                    </a>
                                </label>
                            </div>
                            <div class="col-sm-9">
                                <select id="ays_quiz_question_tags" multiple tabindex="-1">
                                    <option value=""></option>
                                </select>
                            </div>
                        </div>
                        <a href="https://ays-pro.com/wordpress/quiz-maker?utm_source=dashboard&utm_medium=quiz-free&utm_campaign=question-tags-<?php echo esc_attr( AYS_QUIZ_UTM_VERSION ); ?>" target="_blank" class="ays-quiz-new-upgrade-button-link">
                            <div class="ays-quiz-new-upgrade-button-box">
                                <div>
                                    <img src="<?php echo esc_url( AYS_QUIZ_ADMIN_URL.'/images/icons/locked_24x24.svg' ); ?>">
                                    <img src="<?php echo esc_url( AYS_QUIZ_ADMIN_URL.'/images/icons/unlocked_24x24.svg' ); ?>" class="ays-quiz-new-upgrade-button-hover">
                                </div>
                                <div class="ays-quiz-new-upgrade-button"><?php echo esc_html__("Upgrade", "quiz-maker"); ?></div>
                            </div>
                        </a>
                    </div>
                </div>
                <hr>
                <!-- Question Tags End -->
                <div class="form-group row">
                    <div class="col-sm-3">
                        <label for="ays_question_title">
                            <?php echo esc_html__('Question title','quiz-maker'); ?>
                            <a class="ays_help" data-toggle="tooltip" title="<?php echo esc_attr( __('Add the title of the question to make it easy for you to find it among the other questions. It will be visible only in the questions list table.','quiz-maker') ); ?>">
                                <i class="ays_fa ays_fa_info_circle"></i>
                            </a>
                        </label>
                    </div>
                    <div class="col-sm-9">
                        <div>
                            <input type="text" class="ays-text-input" id="ays_question_title" name="ays_question_title" value="<?php echo esc_attr($question_title); ?>">
                        </div>
                    </div>
                </div>
                <hr>
                <div class="form-group row">
                    <div class="col-sm-12">
                        <a href="" id="ays_next_tab" class="ays_next_tab"><?php echo esc_html__( 'Advanced settings' , 'quiz-maker' ); ?></a>
                    </div>
                </div>
            </div>

            <div id="tab2" class="ays-quiz-tab-content <?php echo ($ays_question_tab == 'tab2') ? 'ays-quiz-tab-content-active' : ''; ?>">
                <p class="ays-subtitle"><?php echo esc_html__('Question Settings','quiz-maker')?></p>
                <hr class="ays-quiz-bolder-hr"/>
                <div class="form-group row">
                    <div class="col-sm-3">
                        <label>
                            <?php echo esc_html__('Question status', 'quiz-maker'); ?>
                            <a class="ays_help" data-toggle="tooltip" title="<?php echo esc_attr( __('Choose whether the question is active or not. If you choose Unpublished option, the question won’t be shown anywhere on your website.','quiz-maker') ); ?>">
                                <i class="ays_fa ays_fa_info_circle"></i>
                            </a>
                        </label>
                    </div>

                    <div class="col-sm-9">
                        <div class="form-check form-check-inline">
                            <input type="radio" id="ays-publish" name="ays_publish" value="1" <?php echo ($question_published == '') ? "checked" : ""; ?>  <?php echo ($question_published== '1') ? 'checked' : ''; ?>/>
                            <label class="form-check-label" for="ays-publish"> <?php echo esc_html__('Published', 'quiz-maker'); ?> </label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input type="radio" id="ays-unpublish" name="ays_publish" value="0" <?php echo ($question_published == '0') ? 'checked' : ''; ?>/>
                            <label class="form-check-label" for="ays-unpublish"> <?php echo esc_html__('Unpublished', 'quiz-maker'); ?> </label>
                        </div>
                    </div>
                </div>
                <hr>
                <div class="form-group row">
                    <div class="col-sm-3">
                        <label for="ays_question_hint">
                            <?php echo esc_html__('Question hint','quiz-maker'); ?>
                            <a class="ays_help" data-toggle="tooltip" title="<?php echo esc_attr( __('Add extra information that can help users about the question.','quiz-maker') ); ?>">
                                <i class="ays_fa ays_fa_info_circle"></i>
                            </a>
                        </label>
                    </div>
                    <div class="col-sm-9">
                        <?php
                        $content = stripslashes( isset( $question['question_hint'] ) ? $question['question_hint'] : '' );
                        $editor_id = 'ays_question_hint';
                        $settings = array('editor_height' => $quiz_wp_editor_height, 'textarea_name' => 'ays_question_hint', 'editor_class' => 'ays-textarea', 'media_buttons' => true );
                        wp_editor($content, $editor_id, $settings);
                        ?>
                    </div>
                </div>
                <hr>
                <div class="form-group row">
                    <div class="col-sm-3">
                        <label for="explanation">
                            <?php echo esc_html__('Question explanation','quiz-maker'); ?> <sup>(<?php echo esc_html__('except for checkbox type','quiz-maker');?>)</sup>
                            <a class="ays_help" data-toggle="tooltip" title="<?php echo esc_attr( __('Provide descriptive or informative text about the question.','quiz-maker') ); ?>">
                                <i class="ays_fa ays_fa_info_circle"></i>
                            </a>
                        </label>
                    </div>
                    <div class="col-sm-9">
                        <?php
                        $content = stripslashes( isset( $question['explanation'] ) ? $question['explanation'] : '' );
                        $editor_id = 'explanation';
                        $settings = array('editor_height' => $quiz_wp_editor_height, 'textarea_name' => 'explanation', 'editor_class' => 'ays-textarea', 'media_elements' => false);
                        wp_editor($content, $editor_id, $settings);
                        ?>
                    </div>
                </div>
                <hr>
                <div class="form-group row">
                    <div class="col-sm-3">
                        <label for="wrong_answer_text">
                            <?php echo esc_html__('Message for the wrong answer','quiz-maker'); ?> <sup>(<?php echo esc_html__('except for checkbox type','quiz-maker'); ?>)</sup>
                            <a class="ays_help" data-toggle="tooltip" title="<?php echo esc_attr( __('You can write text which will be shown in case of the wrong answer. It doesn’t work when you chose Quiz calculation option By Weight/points from Quiz Settings.','quiz-maker') ); ?>">
                                <i class="ays_fa ays_fa_info_circle"></i>
                            </a>
                        </label>
                    </div>
                    <div class="col-sm-9">
                        <?php
                        $content = stripslashes( isset( $question['wrong_answer_text'] ) ? $question['wrong_answer_text'] : '' );
                        $editor_id = 'wrong_answer_text';
                        $settings = array('editor_height' => $quiz_wp_editor_height, 'textarea_name' => 'wrong_answer_text', 'editor_class' => 'ays-textarea', 'media_elements' => false);
                        wp_editor($content, $editor_id, $settings);
                        ?>
                    </div>
                </div>
                <hr>
                <div class="form-group row">
                    <div class="col-sm-3">
                        <label for="right_answer_text">
                            <?php echo esc_html__('Message for the right answer','quiz-maker'); ?> <sup>(<?php echo esc_html__('except for checkbox type','quiz-maker'); ?>)</sup>
                            <a class="ays_help" data-toggle="tooltip" title="<?php echo esc_attr( __('You can write text which will be shown in case of right answer.  It doesn’t work when you chose Quiz calculation option By Weight/points from Quiz Settings.','quiz-maker') );?>">
                                <i class="ays_fa ays_fa_info_circle"></i>
                            </a>
                        </label>
                    </div>
                    <div class="col-sm-9">
                        <?php
                        $content = stripslashes( isset( $question['right_answer_text'] ) ? $question['right_answer_text'] : '' );
                        $editor_id = 'right_answer_text';
                        $settings = array('editor_height' => $quiz_wp_editor_height, 'textarea_name' => 'right_answer_text', 'editor_class' => 'ays-textarea', 'media_elements' => false);
                        wp_editor($content, $editor_id, $settings);
                        ?>
                    </div>
                </div>
                <hr>
                <div class="form-group row">
                    <div class="col-sm-3">
                        <label for="ays_quiz_question_note_message">
                            <?php echo esc_html__('Note text','quiz-maker'); ?>
                            <a class="ays_help" data-toggle="tooltip" title="<?php echo esc_attr( __('Write down additional information about the question that will appear under the answers.','quiz-maker') ); ?>">
                                <i class="ays_fa ays_fa_info_circle"></i>
                            </a>
                        </label>
                    </div>
                    <div class="col-sm-9">
                        <?php
                        $content = $quiz_question_note_message;
                        $editor_id = 'ays_quiz_question_note_message';
                        $settings = array('editor_height' => $quiz_wp_editor_height, 'textarea_name' => 'ays_quiz_question_note_message', 'editor_class' => 'ays-textarea', 'media_elements' => false);
                        wp_editor($content, $editor_id, $settings);
                        ?>
                    </div>
                </div>
                <hr>
                <div class="form-group row">
                    <div class="col-sm-3">
                        <label for="ays_quiz_hide_question_text">
                            <?php echo esc_html__('Hide question text on the front-end', 'quiz-maker'); ?>
                            <a class="ays_help" data-toggle="tooltip" title="<?php echo esc_attr( __('Make the question appear without text on the website (quiz page). The option is designed to use when the question includes an image as well.','quiz-maker') ); ?>">
                                <i class="ays_fa ays_fa_info_circle"></i>
                            </a>
                        </label>
                    </div>

                    <div class="col-sm-9">
                        <div class="form-check form-check-inline">
                            <input type="checkbox" id="ays_quiz_hide_question_text" name="ays_quiz_hide_question_text" value="on" <?php echo ($quiz_hide_question_text) ? "checked" : ""; ?> />
                        </div>
                    </div>
                </div>
                <hr>
                <div class="form-group row">
                    <div class="col-sm-3">
                        <label for="ays_quiz_disable_answer_stripslashes">
                            <?php echo esc_html__('Disable strip slashes for answers', 'quiz-maker'); ?>
                            <a class="ays_help" data-toggle="tooltip" title="<?php echo esc_attr( __("By enabling this option, the backslashes will not be removed from your answers' content. It is recommended to have this option enabled, if you are using the MathJax-Latex plugin.",'quiz-maker')  ); ?>">
                                <i class="ays_fa ays_fa_info_circle"></i>
                            </a>
                        </label>
                    </div>

                    <div class="col-sm-9">
                        <div class="form-check form-check-inline">
                            <input type="checkbox" id="ays_quiz_disable_answer_stripslashes" name="ays_quiz_disable_answer_stripslashes" value="on" <?php echo ($quiz_disable_answer_stripslashes) ? "checked" : ""; ?> />
                        </div>
                    </div>
                </div>
                <hr>
                <div class="form-group row">
                    <div class="col-sm-3">
                        <label for="ays_quiz_enable_question_stripslashes">
                            <?php echo esc_html__('Enable strip slashes for question', 'quiz-maker'); ?>
                            <a class="ays_help" data-toggle="tooltip" title="<?php echo esc_attr( __("If this option is enabled the backslashes will be removed from your questions' content. If you are using the MathJax-Latex plugin it is recommended to have this option disabled.",'quiz-maker')  ); ?>">
                                <i class="ays_fa ays_fa_info_circle"></i>
                            </a>
                        </label>
                    </div>

                    <div class="col-sm-9">
                        <div class="form-check form-check-inline">
                            <input type="checkbox" id="ays_quiz_enable_question_stripslashes" name="ays_quiz_enable_question_stripslashes" value="on" <?php echo ($quiz_enable_question_stripslashes) ? "checked" : ""; ?> />
                        </div>
                    </div>
                </div>
                <hr>
                <div class="form-group row">
                    <div class="col-sm-3">
                        <label for="ays_not_influence_to_score">
                            <?php echo esc_html__('No influence to score', 'quiz-maker'); ?>
                            <a class="ays_help" data-toggle="tooltip" title="<?php 
                               echo esc_html__( "If you enable this option, this question will not be counted in the final score.", 'quiz-maker' ) . " " .
                                    esc_html__( "So this question will be just a Survey question.", 'quiz-maker' ) . " " .
                                    esc_html__( "There will not be correct/incorrect answers.", 'quiz-maker' ) . " " .
                                    esc_html__( "This is for just collecting data from users.", 'quiz-maker' );
                            ?>">
                                <i class="ays_fa ays_fa_info_circle"></i>
                            </a>
                        </label>
                    </div>

                    <div class="col-sm-9">
                        <div class="form-check form-check-inline">
                            <input type="checkbox" id="ays_not_influence_to_score" name="ays_not_influence_to_score" value="on" <?php echo ($not_influence_to_score) ? "checked" : ""; ?> />
                        </div>
                    </div>
                </div>
                <hr/>
                <div class="form-group row">
                    <div class="col-sm-12 only_pro" style="padding:15px;">
                        <div class="pro_features">                            

                        </div>
                        <div class="form-group row">
                            <div class="col-sm-3">
                                <label for="ays_question_weight"><?php echo esc_html__('Question weight', 'quiz-maker'); ?></label>
                                <a class="ays_help" data-toggle="tooltip" title="<?php echo esc_attr( __('Specify the weight of the question. It\'s not connected with answers points. It will be multiplied with chosen answer weight (if you choose quiz calculation by points). The default value is 1.','quiz-maker') ); ?>">
                                    <i class="ays_fa ays_fa_info_circle"></i>
                                </a>
                            </div>
                            <div class="col-sm-9">
                                <input type="text" id="ays_question_weight" class="ays-text-input ays-text-input-short" value="1" tabindex="-1">
                            </div>
                        </div>
                        <a href="https://ays-pro.com/wordpress/quiz-maker?utm_source=dashboard&utm_medium=quiz-free&utm_campaign=question-weight-<?php echo esc_attr( AYS_QUIZ_UTM_VERSION ); ?>" target="_blank" class="ays-quiz-new-upgrade-button-link">
                            <div class="ays-quiz-new-upgrade-button-box">
                                <div>
                                    <img src="<?php echo esc_url( AYS_QUIZ_ADMIN_URL.'/images/icons/locked_24x24.svg' ); ?>">
                                    <img src="<?php echo esc_url( AYS_QUIZ_ADMIN_URL.'/images/icons/unlocked_24x24.svg'); ?>" class="ays-quiz-new-upgrade-button-hover">
                                </div>
                                <div class="ays-quiz-new-upgrade-button"><?php echo esc_html__("Upgrade", "quiz-maker"); ?></div>
                            </div>
                        </a>
                    </div>
                </div>
                <hr>
                <div class="form-group row">
                    <div class="col-sm-12 only_pro" style="padding:15px;">
                        <div class="pro_features">                            

                        </div>
                        <div class="form-group row">
                            <div class="col-sm-3">
                                <label><?php echo esc_html__('Question background image', 'quiz-maker'); ?></label>
                                <a class="ays_help" data-toggle="tooltip" title="<?php echo esc_attr( __('Background image of the container. You can choose different images for different questions.','quiz-maker') ); ?>">
                                    <i class="ays_fa ays_fa_info_circle"></i>
                                </a>
                            </div>
                            <div class="col-sm-3">
                                <a href="javascript:void(0)" class="add-question-image m-0" tabindex="-1" style="border: 1px solid #ededed;"><?php echo esc_html__('Add Image', 'quiz-maker'); ?></a>
                                <p class="ays_quiz_small_hint_text_for_message_variables" style="margin-top: 5px;">
                                    <span><?php echo esc_html__( "Please Note" , 'quiz-maker' ); ?></span>
                                    <a class="ays_help" data-toggle="tooltip" title="<?php echo esc_attr( __('* Note: The plugin doesn’t make any changes concerning the images. It takes the images in a size, in which you have uploaded them. We are using the default WP Media. If the uploaded image is blurred and has a low quality, make sure to choose the right parameters (Full size) while uploading the images. You can find the Full Size option in the opened Add Media popup (Attachment Display Settings).','quiz-maker') ); ?>">
                                        <i class="ays_fa ays_fa_info_circle"></i>
                                    </a>
                                </p>
                            </div>
                            <div class="col-sm-6">
                                <div class="ays-question-bg-image-container">
                                    <span class="ays-remove-question-bg-img"></span>
                                    <img src="" id="ays-question-bg-img"/>
                                </div>
                            </div>
                        </div>
                        <a href="https://ays-pro.com/wordpress/quiz-maker?utm_source=dashboard&utm_medium=quiz-free&utm_campaign=question-bg-img-<?php echo esc_attr( AYS_QUIZ_UTM_VERSION ); ?>" target="_blank" class="ays-quiz-new-upgrade-button-link">
                            <div class="ays-quiz-new-upgrade-button-box">
                                <div>
                                    <img src="<?php echo esc_url( AYS_QUIZ_ADMIN_URL.'/images/icons/locked_24x24.svg' ); ?>">
                                    <img src="<?php echo esc_url( AYS_QUIZ_ADMIN_URL.'/images/icons/unlocked_24x24.svg' ); ?>" class="ays-quiz-new-upgrade-button-hover">
                                </div>
                                <div class="ays-quiz-new-upgrade-button"><?php echo esc_html__("Upgrade", "quiz-maker"); ?></div>
                            </div>
                        </a>
                    </div>
                </div>
                <hr>
                <div class="form-group row">
                    <div class="col-sm-12 only_pro" style="padding:15px;">
                        <div class="pro_features">                            

                        </div>
                        <div class="form-group row">
                            <div class="col-sm-3">
                                <label><?php echo esc_html__('User answer explanation', 'quiz-maker'); ?></label>
                                <a class="ays_help" data-toggle="tooltip" title="<?php echo esc_attr( __('The users can write an explanation for their answers.','quiz-maker') ); ?>">
                                    <i class="ays_fa ays_fa_info_circle"></i>
                                </a>
                            </div>
                            <div class="col-sm-9">
                                <div class="form-check form-check-inline">
                                    <input type="radio" id="ays-user-ex-on" value="on" checked tabindex="-1"/>
                                    <label class="form-check-label" for="ays-user-ex-on"> <?php echo esc_html__('Enabled', 'quiz-maker'); ?> </label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input type="radio" id="ays-user-ex-off" value="off" tabindex="-1"/>
                                    <label class="form-check-label" for="ays-user-ex-off"> <?php echo esc_html__('Disabled', 'quiz-maker'); ?> </label>
                                </div>
                            </div>
                        </div>
                        <a href="https://ays-pro.com/wordpress/quiz-maker?utm_source=dashboard&utm_medium=quiz-free&utm_campaign=user-answer-explanation-<?php echo esc_attr( AYS_QUIZ_UTM_VERSION ); ?>" target="_blank" class="ays-quiz-new-upgrade-button-link">
                            <div class="ays-quiz-new-upgrade-button-box">
                                <div>
                                    <img src="<?php echo esc_url( AYS_QUIZ_ADMIN_URL.'/images/icons/locked_24x24.svg' ); ?>">
                                    <img src="<?php echo esc_url( AYS_QUIZ_ADMIN_URL.'/images/icons/unlocked_24x24.svg' ); ?>" class="ays-quiz-new-upgrade-button-hover">
                                </div>
                                <div class="ays-quiz-new-upgrade-button"><?php echo esc_html__("Upgrade", "quiz-maker"); ?></div>
                            </div>
                        </a>
                    </div>
                </div>
                <hr>
                <div class="form-group row">
                    <div class="col-sm-12">
                        <a href="" class="ays_prev_tab"><?php echo esc_html__( 'General settings' , 'quiz-maker' ); ?></a>
                    </div>
                </div>
            </div>
            <hr>
            <div class="ays_save_buttons_content ays_save_buttons_bottom_content ays-question-button-box">
                <div class="ays-question-button-first-row" style="padding: 0;">
                <?php
                    wp_nonce_field('question_action', 'question_action');
                    echo wp_kses_post($loader_iamge); 
                    $other_attributes = array(
                        'id'            => 'ays-button-apply',
                        'title'         => 'Ctrl + s',
                        'data-toggle'   => 'tooltip',
                        'data-delay'    => '{"show":"1000"}'
                    );
                    submit_button(esc_html__('Save', 'quiz-maker'), 'primary ays-button ays-quiz-loader-banner', 'ays_apply', false, $other_attributes);

                    $other_attributes = array('id' => 'ays-button-save');
                    submit_button(esc_html__('Save and close', 'quiz-maker'), 'ays-button ays-quiz-loader-banner', 'ays_submit', false, $other_attributes);
                    $other_attributes = array('id' => 'ays-button-save-new');
                    submit_button(esc_html__('Save and new', 'quiz-maker'), 'ays-button ays-quiz-loader-banner', 'ays_save_new', false, $other_attributes);                    
                ?>
                </div>
                <div class="ays-question-button-second-row ays-question-button-second-row-padding-right">
                <?php
                    if ( $prev_question_id != "" && !is_null( $prev_question_id ) ) {

                        $other_attributes = array(
                            'id' => 'ays-question-prev-button',
                            'data-message' => esc_html__( 'Are you sure you want to go to the previous question page?', 'quiz-maker'),
                            'href' => sprintf( '?page=%s&action=%s&question=%d', esc_attr( $_REQUEST['page'] ), 'edit', absint( $prev_question_id ) )
                        );
                        submit_button(esc_html__('Prev Question', 'quiz-maker'), 'button', 'ays_question_prev_button', false, $other_attributes);
                    }

                    if ( $nex_question_id != "" && !is_null( $nex_question_id ) ) {

                        $other_attributes = array(
                            'id' => 'ays-question-next-button',
                            'data-message' => esc_html__( 'Are you sure you want to go to the next question page?', 'quiz-maker'),
                            'href' => sprintf( '?page=%s&action=%s&question=%d', esc_attr( $_REQUEST['page'] ), 'edit', absint( $nex_question_id ) )
                        );
                        submit_button(esc_html__('Next Question', 'quiz-maker'), 'button', 'ays_question_next_button', false, $other_attributes);
                    }
                ?>
                </div>
            </div>
        </form>
    </div>
</div>
