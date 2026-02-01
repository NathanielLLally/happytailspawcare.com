<?php
/**
 * Created by PhpStorm.
 * User: biggie18
 * Date: 6/15/18
 * Time: 3:34 PM
 */

$action = ( isset($_GET['action']) ) ? sanitize_text_field( $_GET['action'] ) : '';
$id     = ( isset($_GET['question_category']) ) ? absint( sanitize_text_field( $_GET['question_category'] ) ) : null;

if( $action == 'duplicate' && !is_null($id) ){
    $this->question_categories_obj->duplicate_question_categories($id);
}

$plus_icon_svg = "<span class=''><img src='". AYS_QUIZ_ADMIN_URL ."/images/icons/plus-icon.svg'></span>";

?>

<div class="wrap ays-quiz-list-table ays_quiz_question_categories_list_table">
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
        echo esc_html(get_admin_page_title());
        ?>
    </h1>
    <?php do_action('ays_quiz_sale_banner'); ?>
    <div class="nav-tab-wrapper">
        <a href="#poststuff" class="nav-tab nav-tab-active">
            <?php echo esc_html__("Categories", 'quiz-maker');?>
        </a>
        <a href="#question_tags" class="nav-tab">
            <?php echo esc_html__("Tags", 'quiz-maker');?>
        </a>
    </div>
    <div id="poststuff" class="ays-quiz-tab-content ays-quiz-tab-content-active">
        <div class="ays-quiz-add-new-button-box" style="margin-top: 10px;">
            <a href="<?php echo esc_url( admin_url('admin.php') . "?page=". esc_attr( $_REQUEST['page'] ) . "&action=add" ); ?>" class="page-title-action button-primary ays-quiz-add-new-button ays-quiz-add-new-button-new-design">
                <span class=''><img src='<?php echo esc_url(AYS_QUIZ_ADMIN_URL); ?>/images/icons/plus-icon.svg'></span>
                <?php echo esc_html__('Add New', 'quiz-maker'); ?>
            </a>
        </div>
        <div id="post-body" class="metabox-holder">
            <div id="post-body-content">
                <div class="meta-box-sortables ui-sortable">
                    <?php
                        $this->question_categories_obj->views();
                    ?>
                    <form method="post">
                        <?php
                            $this->question_categories_obj->prepare_items();
                            $search = esc_html__( "Search", 'quiz-maker' );
                            $this->question_categories_obj->search_box($search, 'quiz-maker');
                            $this->question_categories_obj->display();
                        ?>
                    </form>
                </div>
            </div>
        </div>
        <br class="clear">
        <div class="ays-quiz-add-new-button-box">
            <a href="<?php echo esc_url( admin_url('admin.php') . "?page=". esc_attr( $_REQUEST['page'] ) . "&action=add" ); ?>" class="page-title-action button-primary ays-quiz-add-new-button ays-quiz-add-new-button-new-design">
                <span class=''><img src='<?php echo esc_url(AYS_QUIZ_ADMIN_URL); ?>/images/icons/plus-icon.svg'></span>
                <?php echo esc_html__('Add New', 'quiz-maker'); ?>
            </a>
        </div>
    </div>
    <div id="question_tags" class="ays-quiz-tab-content">
        <div class="row" style="margin: 0; margin-top:20px;">
            <div class="col-sm-12 only_pro">
                <div class="pro_features pro_features_popup_only_hover">

                </div>
                <img src="<?php echo esc_url( AYS_QUIZ_ADMIN_URL.'/images/question_tags_screen.png' ); ?>" alt="<?php echo esc_attr( __( "Question Tags", 'quiz-maker' ) ); ?>" style="width:100%;" >
                <a href="https://ays-pro.com/wordpress/quiz-maker?utm_source=dashboard&utm_medium=quiz-free&utm_campaign=question-tags-menu-<?php echo esc_attr( AYS_QUIZ_UTM_VERSION ); ?>" target="_blank" class="ays-quiz-new-upgrade-button-link">
                    <div class="ays-quiz-new-upgrade-button-box">
                        <div>
                            <img src="<?php echo esc_url( AYS_QUIZ_ADMIN_URL.'/images/icons/locked_24x24.svg' ); ?>">
                            <img src="<?php echo esc_url( AYS_QUIZ_ADMIN_URL.'/images/icons/unlocked_24x24.svg' ); ?>" class="ays-quiz-new-upgrade-button-hover">
                        </div>
                        <div class="ays-quiz-new-upgrade-button"><?php echo esc_html__("Upgrade", "quiz-maker"); ?></div>
                    </div>
                </a>
                <div class="ays-quiz-center-big-main-button-box ays-quiz-new-big-button-flex">
                    <div class="ays-quiz-center-big-upgrade-button-box">
                        <a href="https://ays-pro.com/wordpress/quiz-maker?utm_source=dashboard&utm_medium=quiz-free&utm_campaign=question-tags-menu-<?php echo esc_attr( AYS_QUIZ_UTM_VERSION ); ?>" target="_blank" class="ays-quiz-new-upgrade-button-link">
                            <div class="ays-quiz-center-new-big-upgrade-button">
                                <img src="<?php echo esc_url( AYS_QUIZ_ADMIN_URL.'/images/icons/locked_24x24.svg' ); ?>" class="ays-quiz-new-button-img-hide">
                                <img src="<?php echo esc_url( AYS_QUIZ_ADMIN_URL.'/images/icons/unlocked_24x24.svg' ); ?>" class="ays-quiz-new-upgrade-button-hover">  
                                <?php echo esc_html__("Upgrade", "quiz-maker"); ?>
                            </div>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
</div>
