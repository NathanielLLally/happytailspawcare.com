<br>
<div class="wrap ays-quiz-custom-fields-list-table">
    <div class="ays-quiz-heading-box ays-quiz-heading-box-margin-top">
        <div class="ays-quiz-wordpress-user-manual-box">
            <a href="https://quiz-plugin.com/docs/" target="_blank">
                <i class="ays_fa ays_fa_file_text" ></i> 
                <span style="margin-left: 3px;text-decoration: underline;"><?php echo esc_html__("View Documentation", "quiz-maker"); ?></span>
            </a>
        </div>
    </div>
    <h1 class="wp-heading-inline">
        <?php echo esc_html(get_admin_page_title()); ?>
    </h1>
    <?php do_action('ays_quiz_sale_banner'); ?>
    <div class="ays-quiz-how-to-user-custom-fields-box" style="margin: 30px auto;">
        <div class="ays-quiz-how-to-user-custom-fields-title">
            <h4><?php echo esc_html__( "Learn How to Use Custom Fields in Quiz Maker", 'quiz-maker' ); ?></h4>
        </div>
        <div class="ays-quiz-how-to-user-custom-fields-youtube-video">
            <div class="ays-quiz-create-survey-youtube-video">
                <div class="ays-quiz-youtube-placeholder" data-video-id="Guq_SncdCMo">
                    <img src="<?php echo esc_url(AYS_QUIZ_ADMIN_URL .'/images/youtube/custom-fields-in-quiz-maker.webp'); ?>" width="560" height="315">
                </div>
            </div>
        </div>
    </div>
    <div class="row" style="margin:0;">
        <div class="col-sm-12 only_pro">
            <div class="pro_features pro_features_popup">
                <div class="pro-features-popup-conteiner">
                    <div class="pro-features-popup-title">
                        <?php echo esc_html__("Custom Field", 'quiz-maker'); ?>
                    </div>
                    <div class="pro-features-popup-content" data-link="https://youtu.be/SEv7ZY7idtE">
                        <p>
                            <?php
                            echo sprintf(
                                /* translators: 1: opening <strong> tag before "8 available field types", 2: closing </strong> tag, 3: opening <strong> tag before "GDPR", 4: closing </strong> tag */
                                wp_kses_post(
                                    __(
                                        'Custom Fields will allow you to create various fields with %1$s 8 available field types, %2$s including text, number, telephone. With just two simple steps, you can get any information you wish from the Quiz takers and add %3$s GDPR %4$s checkbox as well. Get personal data, such as gender, country, age etc.',
                                        'quiz-maker'
                                    )
                                ),
                                '<strong>',
                                '</strong>',
                                '<strong>',
                                '</strong>'
                            );
                            ?>
                        </p>
                        <div>
                            <a href="https://quiz-plugin.com/docs/" target="_blank"><?php echo esc_html__("See Documentation", 'quiz-maker'); ?></a>
                        </div>
                    </div>
                    <div class="pro-features-popup-button" data-link="https://ays-pro.com/wordpress/quiz-maker?utm_source=dashboard&utm_medium=quiz-free&utm_campaign=custom-field-list-table-<?php echo esc_attr( AYS_QUIZ_UTM_VERSION ); ?>">
                        <?php echo esc_html__("Pricing", 'quiz-maker'); ?>
                    </div>
                </div>
            </div>
            <img src="<?php echo esc_url(AYS_QUIZ_ADMIN_URL.'/images/attributes_screen.png'); ?>" alt="Statistics" style="width:100%;" >
            <a href="https://ays-pro.com/wordpress/quiz-maker?utm_source=dashboard&utm_medium=quiz-free&utm_campaign=custom-field-list-table-<?php echo esc_attr( AYS_QUIZ_UTM_VERSION ); ?>" target="_blank">
                <div class="ays-quiz-new-upgrade-button-box">
                    <div>
                        <img src="<?php echo esc_url(AYS_QUIZ_ADMIN_URL.'/images/icons/locked_24x24.svg'); ?>">
                        <img src="<?php echo esc_url(AYS_QUIZ_ADMIN_URL.'/images/icons/unlocked_24x24.svg'); ?>" class="ays-quiz-new-upgrade-button-hover">
                    </div>
                    <div class="ays-quiz-new-upgrade-button"><?php echo esc_html__("Upgrade", "quiz-maker"); ?></div>
                </div>
            </a>
            <div class="ays-quiz-new-watch-video-button-box">
                <div>
                    <img src="<?php echo esc_url(AYS_QUIZ_ADMIN_URL.'/images/icons/video_24x24.svg'); ?>">
                    <img src="<?php echo esc_url(AYS_QUIZ_ADMIN_URL.'/images/icons/video_24x24_hover.svg'); ?>" class="ays-quiz-new-watch-video-button-hover">
                </div>
                <div class="ays-quiz-new-watch-video-button"><?php echo esc_html__("Watch Video", "quiz-maker"); ?></div>
            </div>
            <div class="ays-quiz-center-big-main-button-box ays-quiz-new-big-button-flex">
                <div class="ays-quiz-center-big-watch-video-button-box ays-quiz-big-upgrade-margin-right-10">
                    <div class="ays-quiz-center-new-watch-video-demo-button">
                        <img src="<?php echo esc_url(AYS_QUIZ_ADMIN_URL.'/images/icons/video_24x24.svg'); ?>" class="ays-quiz-new-button-img-hide">
                        <img src="<?php echo esc_url(AYS_QUIZ_ADMIN_URL.'/images/icons/video_24x24_hover.svg'); ?>" class="ays-quiz-new-watch-video-button-hover">
                        <?php echo esc_html__("Watch Video", "quiz-maker"); ?>
                    </div>
                </div>
                <div class="ays-quiz-center-big-upgrade-button-box">
                    <a href="https://ays-pro.com/wordpress/quiz-maker?utm_source=dashboard&utm_medium=quiz-free&utm_campaign=custom-field-list-table-<?php echo esc_attr( AYS_QUIZ_UTM_VERSION ); ?>" target="_blank" class="ays-quiz-new-upgrade-button-link">
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