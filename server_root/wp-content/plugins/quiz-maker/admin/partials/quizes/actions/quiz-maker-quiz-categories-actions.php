<?php
$action = (isset($_GET['action'])) ? sanitize_text_field( $_GET['action'] ) : '';
$heading = '';
$loader_iamge = '';
$id = ( isset( $_GET['quiz_category'] ) ) ? absint( intval( $_GET['quiz_category'] ) ) : null;
$quiz_category = array(
    'id'            => '',
    'title'         => '',
    'description'   => '',
    'published'     => '1'
);
switch( $action ) {
    case 'add':
        $heading = __('Add new category', 'quiz-maker');
        break;
    case 'edit':
        $heading = __('Edit category', 'quiz-maker');
        $quiz_category = $this->quiz_categories_obj->get_quiz_category( $id );
        break;
}

$nex_quiz_cat_id = "";
$prev_quiz_cat_id = "";
if ( isset( $id ) && !is_null( $id ) ) {
    $nex_quiz_cat = $this->get_next_or_prev_row_by_id( $id, "next", "aysquiz_quizcategories" );
    $nex_quiz_cat_id = (isset( $nex_quiz_cat['id'] ) && $nex_quiz_cat['id'] != "") ? absint( $nex_quiz_cat['id'] ) : null;

    $prev_quiz_cat = $this->get_next_or_prev_row_by_id( $id, "prev", "aysquiz_quizcategories" );
    $prev_quiz_cat_id = (isset( $prev_quiz_cat['id'] ) && $prev_quiz_cat['id'] != "") ? absint( $prev_quiz_cat['id'] ) : null;
}

$quiz_category_title = (isset($quiz_category['title']) && $quiz_category['title'] != '') ? stripslashes( esc_attr($quiz_category['title']) ) : "";
$quiz_category_description = (isset($quiz_category['description']) && $quiz_category['description'] != '') ? stripslashes($quiz_category['description']) : "";
$quiz_category_published = (isset($quiz_category["published"]) && $quiz_category["published"] != '') ? stripslashes(esc_attr($quiz_category["published"])) : 1;

// General Settings | options
$gen_options = ($this->settings_obj->ays_get_setting('options') === false) ? array() : json_decode( stripcslashes($this->settings_obj->ays_get_setting('options') ), true);

$loader_iamge = "<span class='display_none ays_quiz_loader_box'><img src='". AYS_QUIZ_ADMIN_URL ."/images/loaders/loading.gif'></span>";

if( isset( $_POST['ays_submit'] ) ) {
    $_POST['id'] = $id;
    $result = $this->quiz_categories_obj->add_edit_quiz_category();
}
if(isset($_POST['ays_apply'])){
    $_POST["id"] = $id;
    $_POST['ays_change_type'] = 'apply';
    $this->quiz_categories_obj->add_edit_quiz_category();
}

// WP Editor height
$quiz_wp_editor_height = (isset($gen_options['quiz_wp_editor_height']) && $gen_options['quiz_wp_editor_height'] != '') ? absint( sanitize_text_field($gen_options['quiz_wp_editor_height']) ) : 100 ;

?>
<div class="wrap ays-quiz-dashboard-main-wrap">
    <div class="container-fluid">
        <div class="ays-quiz-heading-box ays-quiz-heading-box-margin-top">
            <div class="ays-quiz-wordpress-user-manual-box">
                <a href="https://quiz-plugin.com/docs/" target="_blank">
                    <i class="ays_fa ays_fa_file_text" ></i> 
                    <span style="margin-left: 3px;text-decoration: underline;"><?php echo esc_html__("View Documentation", "quiz-maker"); ?></span>
                </a>
            </div>
        </div>
        <h1 class="wp-heading-inline"><?php echo wp_kses_post($heading); ?></h1>
        <?php do_action('ays_quiz_sale_banner'); ?>
        <div>
            <?php if($id !== null): ?>
            <div class="row">
                <div class="col-sm-3">
                    <label> <?php echo esc_html__( "Shortcode text for editor", 'quiz-maker' ); ?> </label>
                </div>
                <div class="col-sm-9">
                    <p style="font-size:14px; font-style:italic;">
                        <?php echo esc_html__("To insert the Quiz category into a page, post or text widget, copy shortcode", 'quiz-maker'); ?>
                        <strong class="ays-quiz-shortcode-box" onClick="selectElementContents(this)" class="ays_help" data-toggle="tooltip" title="<?php echo esc_html__('Click for copy.','quiz-maker');?>" style="font-size:16px; font-style:normal;"><?php echo "[ays_quiz_cat id='".esc_attr($id)."' display='all/random' count='5' layout='list/grid']"; ?></strong>
                        <?php echo " " . esc_html__( "and paste it at the desired place in the editor.", 'quiz-maker'); ?><br>
                        <?php echo esc_html__( "The count attribute is required for random view type.", 'quiz-maker'); ?>
                    </p>
                </div>
            </div>
            <?php endif;?>
        </div>
        <hr/>
        <form class="ays-quiz-category-form ays-quiz-real-category-form" id="ays-quiz-category-form" method="post">
            <div id="tab1" class="ays-quiz-tab-content ays-quiz-tab-content-active">
                <p class="ays-subtitle"><?php echo esc_html__('Settings','quiz-maker'); ?></p>
                <hr class="ays-quiz-bolder-hr"/>
                <input type="hidden" class="quiz_wp_editor_height" value="<?php echo esc_attr($quiz_wp_editor_height); ?>">
                <div class="form-group row">
                    <div class="col-sm-2">
                        <label for='ays-title'>
                            <?php echo esc_html__('Title', 'quiz-maker'); ?>
                            <a class="ays_help" data-toggle="tooltip" title="<?php echo esc_attr( __('Title of the category','quiz-maker') ); ?>">
                                <i class="ays_fa ays_fa_info_circle"></i>
                            </a>
                        </label>
                    </div>
                    <div class="col-sm-10">
                        <input class='ays-text-input' id='ays-title' name='ays_title' required type='text' value='<?php echo esc_attr($quiz_category_title); ?>'>
                    </div>
                </div>

                <hr/>
                <div class='ays-field form-group row'>
                    <div class="col-sm-2">
                        <label for='ays-description'>
                            <?php echo esc_html__('Description', 'quiz-maker'); ?>
                            <a class="ays_help" data-toggle="tooltip" title="<?php echo esc_attr( __('Provide more information about the quiz category','quiz-maker') ); ?>">
                                <i class="ays_fa ays_fa_info_circle"></i>
                            </a>
                        </label>
                    </div>
                    <div class="col-sm-10">
                    <?php
                        $content = $quiz_category_description;
                        $editor_id = 'ays-quiz-description';
                        $settings = array('editor_height'=> $quiz_wp_editor_height,'textarea_name'=>'ays_description','editor_class'=>'ays-textarea');
                        wp_editor($content,$editor_id,$settings);
                    ?>
                    </div>
                </div>

                <hr/>
                <div class="form-group row">
                    <div class="col-sm-2">
                        <label>
                            <?php echo esc_html__('Category status', 'quiz-maker'); ?>
                            <a class="ays_help" data-toggle="tooltip" title="<?php echo esc_attr( __('Choose whether the quiz category is active or not. If you choose Unpublished option, the quiz category won’t be shown anywhere on your website.','quiz-maker') ); ?>">
                                <i class="ays_fa ays_fa_info_circle"></i>
                            </a>
                        </label>
                    </div>

                    <div class="col-sm-3">
                        <div class="form-check form-check-inline">
                            <input type="radio" id="ays-publish" name="ays_publish" value="1" <?php echo ( $quiz_category_published == '' ) ? "checked" : ""; ?> <?php echo ( $quiz_category_published == '1') ? 'checked' : ''; ?> />
                            <label class="form-check-label" for="ays-publish"> <?php echo esc_html__('Published', 'quiz-maker'); ?> </label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input type="radio" id="ays-unpublish" name="ays_publish" value="0" <?php echo ( $quiz_category_published  == '0' ) ? 'checked' : ''; ?> />
                            <label class="form-check-label" for="ays-unpublish"> <?php echo esc_html__('Unpublished', 'quiz-maker'); ?> </label>
                        </div>
                    </div>
                </div>
            </div>
            <hr/>
            <div class="ays-question-button-box ays_save_buttons_content ays_save_buttons_bottom_content">
                <div class="ays-question-button-first-row" style="padding: 0;">
                <?php
                    wp_nonce_field('quiz_category_action', 'quiz_category_action');
                    echo wp_kses_post($loader_iamge);

                    $other_attributes = array( 
                        'id' => 'ays_apply',
                        'title' => 'Ctrl + s',
                        'data-toggle' => 'tooltip',
                        'data-delay'=> '{"show":"1000"}'
                    );
                    
                    submit_button( esc_html__( 'Save', 'quiz-maker'), 'primary ays-quiz-loader-banner', 'ays_apply', true, $other_attributes);

                    $other_attributes = array( 'id' => 'ays-button' );
                    submit_button( esc_html__( 'Save and close', 'quiz-maker' ), 'ays-quiz-loader-banner', 'ays_submit', true, $other_attributes );
                ?>
                </div>
                <div class="ays-question-button-second-row">
                <?php
                    if ( $prev_quiz_cat_id != "" && !is_null( $prev_quiz_cat_id ) ) {

                        $other_attributes = array(
                            'id' => 'ays-question-prev-button',
                            'data-message' => esc_html__( 'Are you sure you want to go to the previous quiz category page?', 'quiz-maker'),
                            'href' => sprintf( '?page=%s&action=%s&quiz_category=%d', esc_attr( $_REQUEST['page'] ), 'edit', absint( $prev_quiz_cat_id ) )
                        );
                        submit_button(esc_html__('Prev Category', 'quiz-maker'), 'button ays-quiz-category-next-button-class', 'ays_quiz_cat_prev_button', false, $other_attributes);
                    }

                    if ( $nex_quiz_cat_id != "" && !is_null( $nex_quiz_cat_id ) ) {

                        $other_attributes = array(
                            'id' => 'ays-quiz-category-next-button',
                            'data-message' => esc_html__( 'Are you sure you want to go to the next quiz category page?', 'quiz-maker'),
                            'href' => sprintf( '?page=%s&action=%s&quiz_category=%d', esc_attr( $_REQUEST['page'] ), 'edit', absint( $nex_quiz_cat_id ) )
                        );
                        submit_button(esc_html__('Next Category', 'quiz-maker'), 'button ays-quiz-category-next-button-class', 'ays_quiz_cat_next_button', false, $other_attributes);
                    }
                ?>
                </div>
            </div>
        </form>
    </div>
</div>