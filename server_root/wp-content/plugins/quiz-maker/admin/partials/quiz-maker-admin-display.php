<?php

/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @link       http://ays-pro.com/
 * @since      1.0.0
 *
 * @package    Quiz_Maker
 * @subpackage Quiz_Maker/admin/partials
 */

$quiz_page_url = sprintf('?page=%s', 'quiz-maker');
$add_new_url = sprintf('?page=%s&action=%s', 'quiz-maker', 'add');
$questions_page_url = sprintf('?page=%s', 'quiz-maker-questions');
$new_questions_page_url = sprintf('?page=%s&action=%s', 'quiz-maker-questions', 'add');

?>
<div class="wrap">
    <div class="ays-quiz-maker-wrapper" style="position:relative;">
        <h1 id="ays-quiz-maker-header-title"><?php echo esc_html(get_admin_page_title()); ?> <i class="ays_fa ays_fa_heart_o animated"></i></h1>
    </div>
    <?php do_action('ays_quiz_sale_banner'); ?>
    <div class="ays-quiz-faq-main">
        <h2>
            <?php 
                echo sprintf( esc_attr( __( "How to create a simple quiz in 4 steps with the help of the %s Quiz Maker %s", 'quiz-maker' ) ),
                    '<strong>',
                    '</strong>'
                ); 
            ?> 
            
        </h2>
        <fieldset>
            <div class="ays-quiz-ol-container">
                <ol>
                    <li>
                        <?php 
                            echo sprintf( esc_attr( __( "Create %s questions %s", 'quiz-maker' ) ),
                                '<a href="'. esc_url($questions_page_url) .'" target="_blank">',
                                '</a>'
                            ); 
                        ?> 
                    </li>
                    <li>
                        <?php 
                            echo sprintf( esc_attr( __( "Create %s quiz %s", 'quiz-maker' ) ),
                                '<a href="'. esc_url($quiz_page_url) .'" target="_blank">',
                                '</a>'
                            ); 
                        ?>
                    </li>
                    <li>
                        <?php echo esc_html__( "Insert questions to the quiz", 'quiz-maker' ); ?>
                    </li>
                    <li>
                        <?php 
                            echo sprintf( esc_attr( __( "Copy and paste %s shortcode %s", 'quiz-maker' ) ),
                                '<strong>',
                                '</strong>'
                            ); 
                        ?>
                    </li>
                </ol>
            </div>
            <div class="ays-quiz-p-container">
                <p><?php echo esc_html__("Congrats! You have already created your first quiz." , 'quiz-maker'); ?></p>
            </div>
        </fieldset>
    </div>
    <br>

    <div class="ays-quiz-community-wrap">
        <div class="ays-quiz-community-title">
            <h4><?php echo esc_html__( "Community", 'quiz-maker' ); ?></h4>
        </div>
        <div class="ays-quiz-community-youtube-video">
            <div class="ays-quiz-create-survey-youtube-video">
                <div class="ays-quiz-youtube-placeholder" data-video-id="E2pprALgwhs">
                    <img src="<?php echo esc_url(AYS_QUIZ_ADMIN_URL .'/images/youtube/wordpress-quiz-plugin.webp'); ?>" loading="lazy" width="560" height="315">
                </div>
            </div>
        </div>
        <div class="ays-quiz-community-container">
            <div class="ays-quiz-community-item">
                <div>
                    <a href="https://www.youtube.com/channel/UC-1vioc90xaKjE7stq30wmA" target="_blank" class="ays-quiz-community-item-cover">
                        <i class="ays-quiz-community-item-img ays_fa ays_fa_youtube_play"></i>
                    </a>
                </div>
                <h3 class="ays-quiz-community-item-title"><?php echo esc_html__( "YouTube community", 'quiz-maker' ); ?></h3>
                <p class="ays-quiz-community-item-desc"><?php echo esc_html__("Our YouTube community  guides you to step by step tutorials about our products and not only...", 'quiz-maker'); ?></p>
                <div class="ays-quiz-community-item-footer">
                    <a href="https://www.youtube.com/channel/UC-1vioc90xaKjE7stq30wmA" target="_blank" class="button"><?php echo esc_html__( "Subscribe", 'quiz-maker' ); ?></a>
                </div>
            </div>
            <div class="ays-quiz-community-item">
                <a href="https://wordpress.org/support/plugin/quiz-maker/" target="_blank" class="ays-quiz-community-item-cover" style="color: #0073aa;">
                    <i class="ays-quiz-community-item-img ays_fa ays_fa_wordpress"></i>
                </a>
                <h3 class="ays-quiz-community-item-title"><?php echo esc_html__( "Best Free Support", 'quiz-maker' ); ?></h3>
                <p class="ays-quiz-community-item-desc"><?php echo esc_html__( "With the Free version, you get a lifetime usage for the plugin, however, you will get new updates and support for only 1 month.", 'quiz-maker' ); ?></p>
                <div class="ays-quiz-community-item-footer">
                    <a href="https://wordpress.org/support/plugin/quiz-maker/" target="_blank" class="button"><?php echo esc_html__( "Join", 'quiz-maker' ); ?></a>
                </div>
            </div>
            <div class="ays-quiz-community-item">
                <a href="https://ays-pro.com/contact" target="_blank" class="ays-quiz-community-item-cover" style="color: #ff0000;">
                    <!-- <img class="ays-quiz-community-item-img" src="<?php #echo esc_url(AYS_QUIZ_ADMIN_URL); ?>/images/logo_final.png"> -->
                    <i class="ays-quiz-community-item-img ays_fa ays_fa_users" aria-hidden="true"></i>
                </a>
                <h3 class="ays-quiz-community-item-title"><?php echo esc_html__( "Premium support", 'quiz-maker' ); ?></h3>
                <p class="ays-quiz-community-item-desc"><?php echo esc_html__( "Get 12 months updates and support for the Business package and lifetime updates and support for the Developer package.", 'quiz-maker' ); ?></p>
                <div class="ays-quiz-community-item-footer">
                    <a href="https://ays-pro.com/contact" target="_blank" class="button"><?php echo esc_html__( "Contact", 'quiz-maker' ); ?></a>
                </div>
            </div>
        </div>
    </div>

    <div class="ays-quiz-articles-and-video-wrap">
        <div class="ays-quiz-articles-and-video-box">
            <div class="ays-quiz-articles-and-video-row ays-quiz-articles-box">
                <div class="ays-quiz-articles-and-video-header ays-quiz-articles-and-video-header-padding">
                    <div class="ays-quiz-articles-and-video-text-row"><?php echo esc_html__( "Articles", 'quiz-maker' ); ?></div>
                    <div class="ays-quiz-articles-and-video-icon-row"><i class="ays_fa ays_fa_caret_down" aria-hidden="true"></i></div>
                </div>
                <div class="ays-quiz-articles-and-video-content">
                    <div class="ays-quiz-articles-and-video-content-row">
                        <div class="ays-quiz-articles-and-video-icon-row"><i class="ays_fa ays_fa_external_link"></i></div>
                        <div class="ays-quiz-articles-and-video-text-row">
                            <a href="https://ays-pro.com/blog/creating-a-scored-quiz-via-wordpress-plugin" class="ays-quiz-articles-and-video-links" target="_blank">
                                <?php echo esc_html__( "Create Scored Quiz via WordPress Plugin", 'quiz-maker' ); ?>
                            </a>
                        </div>
                    </div>

                    <div class="ays-quiz-articles-and-video-content-row">
                        <div class="ays-quiz-articles-and-video-icon-row"><i class="ays_fa ays_fa_external_link"></i></div>
                        <div class="ays-quiz-articles-and-video-text-row">
                            <a href="https://ays-pro.com/blog/how-to-create-a-viral-quiz-in-wordpress" class="ays-quiz-articles-and-video-links" target="_blank">
                                <?php echo esc_html__( "How To Create a Viral Quiz In WordPress", 'quiz-maker' ); ?>
                            </a>
                        </div>
                    </div>

                    <div class="ays-quiz-articles-and-video-content-row">
                        <div class="ays-quiz-articles-and-video-icon-row"><i class="ays_fa ays_fa_external_link"></i></div>
                        <div class="ays-quiz-articles-and-video-text-row">
                            <a href="https://ays-pro.com/blog/how-to-create-video-quiz-in-wordpress" class="ays-quiz-articles-and-video-links" target="_blank">
                                <?php echo esc_html__( "How to Create Video Quiz in WordPress", 'quiz-maker' ); ?>
                            </a>
                        </div>
                    </div>

                    <div class="ays-quiz-articles-and-video-content-row">
                        <div class="ays-quiz-articles-and-video-icon-row"><i class="ays_fa ays_fa_external_link"></i></div>
                        <div class="ays-quiz-articles-and-video-text-row">
                            <a href="https://ays-pro.com/blog/how-to-create-diet-quiz-with-wordpress" class="ays-quiz-articles-and-video-links" target="_blank">
                                <?php echo esc_html__( "How to create Diet quiz with WordPress", 'quiz-maker' ); ?>
                            </a>
                        </div>
                    </div>

                    <div class="ays-quiz-articles-and-video-content-row">
                        <div class="ays-quiz-articles-and-video-icon-row"><i class="ays_fa ays_fa_external_link"></i></div>
                        <div class="ays-quiz-articles-and-video-text-row">
                            <a href="https://ays-pro.com/blog/how-to-create-an-assessment-quiz-in-wordpress" class="ays-quiz-articles-and-video-links" target="_blank">
                                <?php echo esc_html__( "How to create an Assessment Quiz in WordPress", 'quiz-maker' ); ?>
                            </a>
                        </div>
                    </div>

                    <div class="ays-quiz-articles-and-video-content-row">
                        <div class="ays-quiz-articles-and-video-icon-row"><i class="ays_fa ays_fa_external_link"></i></div>
                        <div class="ays-quiz-articles-and-video-text-row">
                            <a href="https://ays-pro.com/blog/how-to-make-buzzfeed-quizzes-using-wordpress-plugin-2022" class="ays-quiz-articles-and-video-links" target="_blank">
                                <?php echo esc_html__( "How To Make BuzzFeed Quizzes Using WordPress Plugin 2022", 'quiz-maker' ); ?>
                            </a>
                        </div>
                    </div>

                    <div class="ays-quiz-articles-and-video-content-row">
                        <div class="ays-quiz-articles-and-video-icon-row"><i class="ays_fa ays_fa_external_link"></i></div>
                        <div class="ays-quiz-articles-and-video-text-row">
                            <a href="https://ays-pro.com/blog/how-to-add-leaderboard-for-your-quiz-on-wordpress" class="ays-quiz-articles-and-video-links" target="_blank">
                                <?php echo esc_html__( "How to add Leaderboard for your quiz on WordPress", 'quiz-maker' ); ?>
                            </a>
                        </div>
                    </div>

                    <div class="ays-quiz-articles-and-video-content-row">
                        <div class="ays-quiz-articles-and-video-icon-row"><i class="ays_fa ays_fa_external_link"></i></div>
                        <div class="ays-quiz-articles-and-video-text-row">
                            <a href="https://ays-pro.com/blog/how-to-create-flashcards-in-wordpress" class="ays-quiz-articles-and-video-links" target="_blank">
                                <?php echo esc_html__( "How to create Online Flashcards in WordPress", 'quiz-maker' ); ?>
                            </a>
                        </div>
                    </div>

                    <div class="ays-quiz-articles-and-video-content-row">
                        <div class="ays-quiz-articles-and-video-icon-row"><i class="ays_fa ays_fa_external_link"></i></div>
                        <div class="ays-quiz-articles-and-video-text-row">
                            <a href="https://ays-pro.com/blog/how-to-create-product-recommendation-quiz" class="ays-quiz-articles-and-video-links" target="_blank">
                                <?php echo esc_html__( "How To Create Product Recommendation Quiz", 'quiz-maker' ); ?>
                            </a>
                        </div>
                    </div>

                    <div class="ays-quiz-articles-and-video-content-row">
                        <div class="ays-quiz-articles-and-video-icon-row"><i class="ays_fa ays_fa_external_link"></i></div>
                        <div class="ays-quiz-articles-and-video-text-row">
                            <a href="https://ays-pro.com/blog/how-to-create-a-relationship-quiz-in-wp" class="ays-quiz-articles-and-video-links" target="_blank">
                                <?php echo esc_html__( "How to create a relationship quiz in WP", 'quiz-maker' ); ?>
                            </a>
                        </div>
                    </div>
                </div>
                <div class="ays-quiz-articles-and-video-footer">
                    <div class="ays-quiz-articles-and-video-footer-button-row">
                        <a href="https://ays-pro.com/blog" target="_blank" class="button ays-quiz-articles-and-video-footer-button">
                            <span><?php echo esc_html__( "Go To Blog", 'quiz-maker' ); ?></span>
                            <span><i class="ays_fa ays_fa_long_arrow_right"></i></span>
                        </a>
                    </div>
                </div>
            </div>
            <div class="ays-quiz-articles-and-video-row ays-quiz-videos-box">
                <div class="ays-quiz-articles-and-video-header ays-quiz-articles-and-video-header-padding">
                    <div class="ays-quiz-articles-and-video-text-row"><?php echo esc_html__( "Videos", 'quiz-maker' ); ?></div>
                    <div class="ays-quiz-articles-and-video-icon-row"><i class="ays_fa ays_fa_caret_down" aria-hidden="true"></i></div>
                </div>
                <div class="ays-quiz-articles-and-video-content">
                    <div class="ays-quiz-articles-and-video-content-row">
                        <div class="ays-quiz-articles-and-video-icon-row ays-quiz-video-icon-row"><i class="ays_fa ays_fa_video_camera"></i></div>
                        <div class="ays-quiz-articles-and-video-text-row">
                            <a href="https://www.youtube.com/watch?v=DHolVT3O0Zk" class="ays-quiz-articles-and-video-links" target="_blank">
                                <?php echo esc_html__( "Creating a Scored Quiz in WordPress | Quiz Plugin 2022", 'quiz-maker' ); ?>
                            </a>
                        </div>
                    </div>

                    <div class="ays-quiz-articles-and-video-content-row">
                        <div class="ays-quiz-articles-and-video-icon-row ays-quiz-video-icon-row"><i class="ays_fa ays_fa_video_camera"></i></div>
                        <div class="ays-quiz-articles-and-video-text-row">
                            <a href="https://www.youtube.com/watch?v=KQ2WARfs2Vc" class="ays-quiz-articles-and-video-links" target="_blank">
                                <?php echo esc_html__( "How to create Viral Quiz in WordPress | Detailed Guide 2022", 'quiz-maker' ); ?>
                            </a>
                        </div>
                    </div>

                    <div class="ays-quiz-articles-and-video-content-row">
                        <div class="ays-quiz-articles-and-video-icon-row ays-quiz-video-icon-row"><i class="ays_fa ays_fa_video_camera"></i></div>
                        <div class="ays-quiz-articles-and-video-text-row">
                            <a href="https://youtu.be/0KbyUvFAdcI" class="ays-quiz-articles-and-video-links" target="_blank">
                                <?php echo esc_html__( "How to create a Video Quiz in WordPress", 'quiz-maker' ); ?>
                            </a>
                        </div>
                    </div>

                    <div class="ays-quiz-articles-and-video-content-row">
                        <div class="ays-quiz-articles-and-video-icon-row ays-quiz-video-icon-row"><i class="ays_fa ays_fa_video_camera"></i></div>
                        <div class="ays-quiz-articles-and-video-text-row">
                            <a href="https://youtu.be/eKZjUMKqjGY" class="ays-quiz-articles-and-video-links" target="_blank">
                                <?php echo esc_html__( "How to create Diet Quiz in WordPress", 'quiz-maker' ); ?>
                            </a>
                        </div>
                    </div>

                    <div class="ays-quiz-articles-and-video-content-row">
                        <div class="ays-quiz-articles-and-video-icon-row ays-quiz-video-icon-row"><i class="ays_fa ays_fa_video_camera"></i></div>
                        <div class="ays-quiz-articles-and-video-text-row">
                            <a href="https://youtu.be/NSIFY-WDoDw" class="ays-quiz-articles-and-video-links" target="_blank">
                                <?php echo esc_html__( "How to create an Assessment Quiz for WordPress", 'quiz-maker' ); ?>
                            </a>
                        </div>
                    </div>

                    <div class="ays-quiz-articles-and-video-content-row">
                        <div class="ays-quiz-articles-and-video-icon-row ays-quiz-video-icon-row"><i class="ays_fa ays_fa_video_camera"></i></div>
                        <div class="ays-quiz-articles-and-video-text-row">
                            <a href="https://youtu.be/PQSOjFUG1Fg" class="ays-quiz-articles-and-video-links" target="_blank">
                                <?php echo esc_html__( "How to create an Outcome Quiz in WordPress", 'quiz-maker' ); ?>
                            </a>
                        </div>
                    </div>

                    <div class="ays-quiz-articles-and-video-content-row">
                        <div class="ays-quiz-articles-and-video-icon-row ays-quiz-video-icon-row"><i class="ays_fa ays_fa_video_camera"></i></div>
                        <div class="ays-quiz-articles-and-video-text-row">
                            <a href="https://youtu.be/trZEpGWm9GE" class="ays-quiz-articles-and-video-links" target="_blank">
                                <?php echo esc_html__( "How to add Quiz Leaderboard in WordPress", 'quiz-maker' ); ?>
                            </a>
                        </div>
                    </div>

                    <div class="ays-quiz-articles-and-video-content-row">
                        <div class="ays-quiz-articles-and-video-icon-row ays-quiz-video-icon-row"><i class="ays_fa ays_fa_video_camera"></i></div>
                        <div class="ays-quiz-articles-and-video-text-row">
                            <a href="https://youtu.be/uBpzFjXyKC8" class="ays-quiz-articles-and-video-links" target="_blank">
                                <?php echo esc_html__( "How to create Flashcards Quiz in WordPress", 'quiz-maker' ); ?>
                            </a>
                        </div>
                    </div>

                    <div class="ays-quiz-articles-and-video-content-row">
                        <div class="ays-quiz-articles-and-video-icon-row ays-quiz-video-icon-row"><i class="ays_fa ays_fa_video_camera"></i></div>
                        <div class="ays-quiz-articles-and-video-text-row">
                            <a href="https://youtu.be/BeYNME9TZsQ" class="ays-quiz-articles-and-video-links" target="_blank" style="font-size: 13px;">
                                <?php echo esc_html__( "How to Create Product Recommendation Quiz for WooCommerce", 'quiz-maker' ); ?>
                            </a>
                        </div>
                    </div>

                    <div class="ays-quiz-articles-and-video-content-row">
                        <div class="ays-quiz-articles-and-video-icon-row ays-quiz-video-icon-row"><i class="ays_fa ays_fa_video_camera"></i></div>
                        <div class="ays-quiz-articles-and-video-text-row">
                            <a href="https://www.youtube.com/watch?v=5rTDQPbwRvw" class="ays-quiz-articles-and-video-links" target="_blank">
                                <?php echo esc_html__( "How to create a Relationship Quiz on WordPress", 'quiz-maker' ); ?>
                            </a>
                        </div>
                    </div>
                </div>
                <div class="ays-quiz-articles-and-video-footer">
                    <div class="ays-quiz-articles-and-video-footer-button-row">
                        <a href="https://www.youtube.com/channel/UC-1vioc90xaKjE7stq30wmA" target="_blank" class="button ays-quiz-articles-and-video-footer-button">
                            <span><?php echo esc_html__( "See All Videos", 'quiz-maker' ); ?></span> <span><i class="ays_fa ays_fa_long_arrow_right"></i></span>
                        </a>

                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- ays_fa_video_camera -->

    <div class="ays-quiz-faq-main">
        <div class="ays-quiz-asked-questions">
            <h4><?php echo esc_html__("FAQs" , 'quiz-maker'); ?></h4>
            <div class="ays-quiz-asked-question">
                <div class="ays-quiz-asked-question__header">
                    <div class="ays-quiz-asked-question__title">
                        <h4><strong><?php echo esc_html__( "Will I lose the data after the upgrade?", 'quiz-maker' ); ?></strong></h4>
                    </div>
                    <div class="ays-quiz-asked-question__arrow"><i class="fa fa-chevron-down"></i></div>
                </div>
                <div class="ays-quiz-asked-question__body">                      
                    <p>
                        <?php 
                            echo sprintf( esc_attr( __( "%s Nope! %s All your content and assigned settings of the plugin will remain unchanged even after switching to the Pro version. You don’t need to redo what you have already built with the free version. For the detailed instruction, please take a look at our %s upgrade guide. %s", 'quiz-maker' ) ),
                                '<strong>',
                                '</strong>',
                                '<a href="https://quiz-plugin.com/docs/plugin-setup-and-installation/" target="_blank">',
                                '</a>'
                            ); 
                        ?>
                    </p>
                </div>
            </div>
            <div class="ays-quiz-asked-question">
                <div class="ays-quiz-asked-question__header">
                    <div class="ays-quiz-asked-question__title">
                        <h4><strong><?php echo esc_html__("How do I change the design of the quiz?" , 'quiz-maker'); ?></strong></h4>
                    </div>
                    <div class="ays-quiz-asked-question__arrow"><i class="fa fa-chevron-down"></i></div>
                </div>
                <div class="ays-quiz-asked-question__body">                      
                    <p>
                        <?php 
                            echo sprintf( esc_attr( __( "To do that, please go to the %s Styles %s tab of the given quiz, which allows you to take full advantage of the various options it offers. The plugin provides 8 awesome ready-to-use themes. After choosing your preferred theme, you can customize it with 35+ style options to create appealing quizzes that people love to take, including %s main color, background image, right/wrong answer icons, progress bar, answer styles %s and etc. Moreover, you can use the %s Custom CSS %s written field to fully match the design of your website and brand.", 'quiz-maker' ) ),
                                '<strong>',
                                '</strong>',
                                '<strong>',
                                '</strong>',
                                '<strong>',
                                '</strong>'
                            ); 
                        ?>
                    </p>
                </div>
            </div>
            <div class="ays-quiz-asked-question">
                <div class="ays-quiz-asked-question__header">
                    <div class="ays-quiz-asked-question__title">
                        <h4><strong><?php echo esc_html__( "How do I limit access to the quiz?", 'quiz-maker' ); ?></strong></h4>
                    </div>
                    <div class="ays-quiz-asked-question__arrow"><i class="fa fa-chevron-down"></i></div>
                </div>
                <div class="ays-quiz-asked-question__body">                      
                    <p>
                        <?php 
                            echo sprintf( esc_attr( __( "To do that, please go to the %s Limitation %s tab of the given quiz. The plugin suggests two methods to manage and detect the number of attempts from the same person. Those are %s by IP %s or %s by User ID.%s One of the awesome functionalities that the plugin suggests is the %s Only for logged-in users %s option, which gives access to the quiz to those, who are logged-in users on your website. This option will allow you to precisely target your quiz takers, and not receive unnecessary data from the guests. Moreover, with the help of the %s Generated passwords (PRO) %s option, you can give unique one-time access codes to each participant individually for accessing the quiz. You can use those access codes as promo codes, discounted codes, coupon codes, and so on.", 'quiz-maker' ) ),
                                '<strong>',
                                '</strong>',
                                '<strong>',
                                '</strong>',
                                '<strong>',
                                '</strong>',
                                '<strong>',
                                '</strong>',
                                '<strong>',
                                '</strong>'
                            ); 
                        ?>
                    </p>
                </div>
            </div>
            <div class="ays-quiz-asked-question">
                <div class="ays-quiz-asked-question__header">
                    <div class="ays-quiz-asked-question__title">
                        <h4><strong><?php echo esc_html__( "Can I know more about my respondents?", 'quiz-maker' ); ?></strong></h4>
                    </div>
                    <div class="ays-quiz-asked-question__arrow"><i class="fa fa-chevron-down"></i></div>
                </div>
                <div class="ays-quiz-asked-question__body">                      
                    <p>
                        <?php 
                            echo sprintf( esc_attr( __( "%s You are in a right place! %s You just need to enable the %s Information Form %s from the %s User Data %s tab of the given quiz, create your preferred %s custom fields %s in the %s Custom Fields (PRO) %s page from the plugin left navbar, and come up with a clear picture of who your quiz participants are, where they live, what their lifestyle and personality are like, etc.", 'quiz-maker' ) ),
                                '<strong>',
                                '</strong>',
                                '<strong>',
                                '</strong>',
                                '<strong>',
                                '</strong>',
                                '<strong>',
                                '</strong>',
                                '<strong>',
                                '</strong>'
                            ); 
                        ?>
                    </p>
                </div>
            </div>
            <div class="ays-quiz-asked-question">
                <div class="ays-quiz-asked-question__header">
                    <div class="ays-quiz-asked-question__title">
                        <h4><strong><?php echo esc_html__( "Will I get notified every time a quiz is submitted? (PRO)", 'quiz-maker' ); ?></strong></h4>
                    </div>
                    <div class="ays-quiz-asked-question__arrow"><i class="fa fa-chevron-down"></i></div>
                </div>
                <div class="ays-quiz-asked-question__body">                      
                    <p>
                        <?php 
                            echo sprintf( esc_attr( __( "%s You will! %s To enable it, please go to the %s Email/Certificate %s tab of the given quiz. There you will find the %s Send mail to admin %s option. After enabling the option, the admin (and/or your provided additional email(s)) will receive an email notification about results at each time.", 'quiz-maker' ) ),
                                '<strong>',
                                '</strong>',
                                '<strong>',
                                '</strong>',
                                '<strong>',
                                '</strong>'
                            ); 
                        ?>
                    </p>
                </div>
            </div>

            <div class="ays-quiz-asked-question">
                <div class="ays-quiz-asked-question__header">
                    <div class="ays-quiz-asked-question__title">
                        <h4><strong><?php echo esc_html__( "Can I send certificates to those users who have passed the quiz? (PRO)" , 'quiz-maker' ); ?></strong></h4>
                    </div>
                    <div class="ays-quiz-asked-question__arrow"><i class="fa fa-chevron-down"></i></div>
                </div>
                <div class="ays-quiz-asked-question__body">                      
                    <p>
                        <?php 
                            echo sprintf( esc_attr( __( "%s Yes! %s To enable it, please go to the %s Email/Certificate %s tab of the given quiz. There you will find the %s Send certificate to user %s option. After enabling the option, you need to configure the settings of it such as %s Certificate pass score, Title %s and %s Body. %s Moreover, you can choose the orientation of the certificate, add a %s Background image %s (for instance the logo of your company) and select your preferred frame.", 'quiz-maker' ) ),
                                '<strong>',
                                '</strong>',
                                '<strong>',
                                '</strong>',
                                '<strong>',
                                '</strong>',
                                '<strong>',
                                '</strong>',
                                '<strong>',
                                '</strong>',
                                '<strong>',
                                '</strong>'
                            ); 
                        ?>
                    </p>
                </div>
            </div>
            <div class="ays-quiz-asked-question">
                <div class="ays-quiz-asked-question__header">
                    <div class="ays-quiz-asked-question__title">
                        <h4><strong><?php echo esc_html__( "How to add Mathematical equations into questions?", 'quiz-maker' ); ?></strong></h4>
                    </div>
                    <div class="ays-quiz-asked-question__arrow"><i class="fa fa-chevron-down"></i></div>
                </div>
                <div class="ays-quiz-asked-question__body">                      
                    <p>
                        <?php 
                            echo sprintf( esc_attr( esc_html__( "Do you wonder how to create a Math Quiz with hard mathematical equations in WordPress? It is easier than you think. The Quiz Maker plugin itself does not have the functionality for adding math equations into questions. For adding %s math equations %s into the %s Quiz Maker plugin %s you can make use of several math equation plugins such as %s MathJax - LaTex plugin. %s For adding the math equations just install and activate the mentioned plugin. It is one of the easiest math plugins. With the help of it, you can add as many equations as you need, even if they include several symbols. In this %s demo, %s you can see a step-by-step tutorial on how to use MathJax. You have to be careful to use less-than signs, ampersands, and other HTML special characters within your math equations. %s For example, to add a squared symbol, you need to write down the following sign ^2. (do not forget to insert $ character to mark the beginning and ending).", 'quiz-maker' ) ),
                                '<strong>',
                                '</strong>',
                                '<strong>',
                                '</strong>',
                                '<a href="https://wordpress.org/plugins/mathjax-latex/" target="_blank">',
                                '</a><br>',
                                '<a href="https://www.mathjax.org/#demo" target="_blank">',
                                '</a>',
                                '<br><br>'
                            ); 
                        ?>
                    </p>
                </div>
            </div>
        </div>
        <p class="ays-quiz-faq-footer">
            <?php 
                echo sprintf( esc_attr( __( "For more advanced needs, please take a look at our %s Quiz Maker plugin User Manual. %s", 'quiz-maker' ) ),
                    '<a href="https://quiz-plugin.com/docs/" target="_blank">',
                    '</a>'
                ); 
            ?>
            <br>
            <?php 
                echo sprintf( esc_attr( __( "If none of these guides help you, ask your question by contacting our %s support specialists %s and get a reply within a day.", 'quiz-maker' ) ),
                    '<a href="https://wordpress.org/support/plugin/quiz-maker/" target="_blank">',
                    '</a>'
                ); 
            ?>
        </p>
    </div>
</div>
<script>
    var acc = document.getElementsByClassName("ays-quiz-asked-question__header");
    var i;

    for (i = 0; i < acc.length; i++) {
      acc[i].addEventListener("click", function() {
        
        var panel = this.nextElementSibling;
        
        
        if (panel.style.maxHeight) {
          panel.style.maxHeight = null;
          this.children[1].children[0].style.transform="rotate(0deg)";
        } else {
          panel.style.maxHeight = panel.scrollHeight + "px";
          this.children[1].children[0].style.transform="rotate(180deg)";
        } 
      });
    }
</script>
