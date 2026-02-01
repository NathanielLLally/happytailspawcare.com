<?php
    $tab_url = "?page=".$this->plugin_name."-questions";

    $actual_reports_count = Quiz_Maker_Admin::get_actual_reports_count();
?>
<div id="reports">
    <div class="wrap ays-quiz-list-table ays_quiz_question_reports_list_table">
        <h1 class="wp-heading-inline">
            <?php
                echo esc_html__('Question Reports', 'quiz-maker');
            ?>
        </h1>
        <div class="nav-tab-wrapper">
            <a href="<?php echo $tab_url; ?>" class="no-js nav-tab">
                <?php echo esc_html__("Questions", 'quiz-maker'); ?>
            </a>
            <a href="#reports" class="nav-tab nav-tab-active">
                <?php echo esc_html__("Reports", 'quiz-maker');
                if ($actual_reports_count > 0) {
                    echo '<span class="ays_menu_badge ays_results_bage">' . $actual_reports_count . '</span>';
                }
                ?>
            </a>
        </div>
        <div id="poststuff">
            <div id="post-body" class="metabox-holder">
                <div id="post-body-content">
                    <div class="meta-box-sortables ui-sortable">
                        <?php
                            $this->question_reports_obj->views();
                        ?>
                        <form method="post">
                            <?php
                            $this->question_reports_obj->prepare_items();
                            $this->question_reports_obj->display();
                            ?>
                        </form>
                    </div>
                </div>
            </div>
            <br class="clear">
        </div>
        <h1 class="wp-heading-inline">
            <?php
                echo esc_html__('Question Reports', 'quiz-maker');
            ?>
        </h1>
    </div>
</div>
