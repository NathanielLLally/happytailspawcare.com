<br>
<div class="wrap">
    <h1 class="wp-heading-inline">
        <?php echo esc_html(get_admin_page_title()); ?>
    </h1>
    <?php do_action('ays_quiz_sale_banner'); ?>
    <div class="row" style="margin:0;">
        <div class="col-sm-12">
            <div class="pro_features">
                <div>
                    <p>
                        <?php echo esc_html__("This feature is available only in", 'quiz-maker'); ?>
                        <a href="https://ays-pro.com/wordpress/quiz-maker?utm_source=dashboard&utm_medium=quiz-free&utm_campaign=orders-menu-<?php echo esc_attr( AYS_QUIZ_UTM_VERSION ); ?>" target="_blank" title="<?php echo esc_attr(__("Developer version!!!", 'quiz-maker') ); ?>"><?php echo esc_html__("Developer version!!!", 'quiz-maker'); ?></a>
                    </p>
                    <p class="ays-quiz-pro-features-text">
                        <?php echo esc_html__("This feature is available only in", 'quiz-maker'); ?>
                        <a href="https://ays-pro.com/wordpress/quiz-maker?utm_source=dashboard&utm_medium=quiz-free&utm_campaign=orders-menu-<?php echo esc_attr( AYS_QUIZ_UTM_VERSION ); ?>" target="_blank" title="<?php echo esc_attr(__("Developer version!!!", 'quiz-maker') ); ?>"><?php echo esc_html__("Developer version!!!", 'quiz-maker'); ?></a>
                    </p>
                </div>
            </div>
            <img src="<?php echo esc_url( AYS_QUIZ_ADMIN_URL.'/images/orders_table.png' ); ?>" alt="<?php echo esc_attr(__("Statistics", 'quiz-maker')); ?>" style="width:100%;" >
        </div>
    </div>
</div>
