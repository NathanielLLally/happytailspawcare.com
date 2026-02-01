<?php

$action = ( isset($_GET['action']) ) ? sanitize_text_field( $_GET['action'] ) : '';
$id     = ( isset($_GET['quiz_category']) ) ? absint( sanitize_text_field( $_GET['quiz_category'] ) ) : null;

if( $action == 'duplicate' && !is_null($id) ){
    $this->quiz_categories_obj->duplicate_quiz_categories($id);
}

$plus_icon_svg = "<span class=''><img src='". AYS_QUIZ_ADMIN_URL ."/images/icons/plus-icon.svg'></span>";

?>
<div class="wrap ays-quiz-list-table ays_quiz_categories_list_table">
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
    <div class="ays-quiz-add-new-button-box">
        <a href="<?php echo esc_url( admin_url('admin.php') . "?page=". esc_attr( $_REQUEST['page'] ) . "&action=add" ); ?>" class="page-title-action button-primary ays-quiz-add-new-button ays-quiz-add-new-button-new-design">
            <span class=''><img src='<?php echo esc_url(AYS_QUIZ_ADMIN_URL); ?>/images/icons/plus-icon.svg'></span>
            <?php echo esc_html__('Add New', 'quiz-maker'); ?>
        </a>
    </div>
    <div id="poststuff">
        <div id="post-body" class="metabox-holder">
            <div id="post-body-content">
                <div class="meta-box-sortables ui-sortable">
                    <?php
                        $this->quiz_categories_obj->views();
                    ?>
                    <form method="post">
                        <?php
                            $this->quiz_categories_obj->prepare_items();
                            $search = esc_html__( "Search", 'quiz-maker' );
                            $this->quiz_categories_obj->search_box($search, 'quiz-maker');
                            $this->quiz_categories_obj->display();
                        ?>
                    </form>
                </div>
            </div>
        </div>
        <br class="clear">
    </div>

    <div class="ays-quiz-add-new-button-box">
        <a href="<?php echo esc_url( admin_url('admin.php') . "?page=". esc_attr( $_REQUEST['page'] ) . "&action=add" ); ?>" class="page-title-action button-primary ays-quiz-add-new-button ays-quiz-add-new-button-new-design">
            <span class=''><img src='<?php echo esc_url(AYS_QUIZ_ADMIN_URL); ?>/images/icons/plus-icon.svg'></span>
            <?php echo esc_html__('Add New', 'quiz-maker'); ?>
        </a>
    </div>
</div>
