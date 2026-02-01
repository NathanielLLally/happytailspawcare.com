<?php
/**
 * Created by PhpStorm.
 * User: biggie18
 * Date: 7/30/18
 * Time: 12:08 PM
 */
// $url = "https://ays-pro.com/wordpress/quiz-maker";
// wp_safe_redirect( $url );
// exit;
?>

<div class="wrap ays-quiz-features-wrap-box">
    <div class="ays-quiz-heading-box ays-quiz-heading-box-margin-top">
        <div class="ays-quiz-wordpress-user-manual-box">
            <a href="https://quiz-plugin.com/docs/" target="_blank">
                <i class="ays_fa ays_fa_file_text" ></i> 
                <span style="margin-left: 3px;text-decoration: underline;"><?php echo esc_html__("View Documentation", "quiz-maker"); ?></span>
            </a>
        </div>
    </div>
    <h1 class="wp-heading-inline">
        <?php echo esc_html__( 'PRO Features' , 'quiz-maker'); ?>
    </h1>
    <?php do_action('ays_quiz_sale_banner'); ?>
    <h3 class="wp-heading" style="text-align: center;">
        <?php echo esc_html__( 'Limited Offer' , 'quiz-maker'); ?>
    </h3>
    <div class="ays-quiz-features-wrap">
        <div class="comparison">
            <table>
                <thead>
                    <tr>
                        <th class="tl tl2"></th>
                        <th class="product" style="background:#69C7F1; border-top-left-radius: 5px; border-left:0px;">
                            <span style="display: block"><?php echo esc_html__('Personal','quiz-maker'); ?></span>
                            <img src="<?php echo esc_url(AYS_QUIZ_ADMIN_URL) . '/images/avatars/personal_avatar.png'; ?>" alt="Free" title="Free" width="100"/>
                        </th>
                        <th class="product" style="background:#69C7F1;">
                            <span style="display: block"><?php echo  esc_html__('Business','quiz-maker'); ?></span>
                            <img src="<?php echo esc_url(AYS_QUIZ_ADMIN_URL) . '/images/avatars/business_avatar.png'; ?>" alt="Business" title="Business" width="100"/>
                        </th>
                        <th class="product" style="border-top-right-radius: 5px; background:#69C7F1;">
                            <span style="display: block"><?php echo esc_html__('Developer','quiz-maker'); ?></span>
                            <img src="<?php echo esc_url(AYS_QUIZ_ADMIN_URL) . '/images/avatars/pro_avatar.png'; ?>" alt="Developer" title="Developer" width="100"/>
                        </th>
                        <th class="product" style="border-top-right-radius: 5px; border-right:0px; background:#69C7F1;">
                            <span style="display: block"><?php echo esc_html__('Agency','quiz-maker'); ?></span>
                            <img src="<?php echo esc_url(AYS_QUIZ_ADMIN_URL) . '/images/avatars/agency_avatar.png'; ?>" alt="Agency" title="Agency" width="100"/>
                        </th>
                    </tr>
                    <tr>
                        <th></th>
                        <th class="price-info">
                            <div class="price-now"><span><?php echo esc_html__('Free','quiz-maker')?></span></div>
                        </th>
                        <th class="price-info">
                            <div class="price-now"><span class="sale-price" style="text-decoration: line-through; color: red;">$75</span>
                            </div>
                            <div class="price-now"><span>$59</span>
                            </div>
                            <!-- <div class="price-now"><span style="color: red; font-size: 12px;">Black Friday</span> -->
                            </div>
                            <div class="ays-quiz-pracing-table-td-flex">
                                <a href="https://ays-pro.com/wordpress/quiz-maker?utm_source=dashboard&utm_medium=quiz-free&utm_campaign=pricing-table-business-<?php echo esc_attr( AYS_QUIZ_UTM_VERSION ); ?>" class="price-buy"><?php echo esc_html__('Buy now','quiz-maker')?><span class="hide-mobile"></span></a>
                                <span><?php echo esc_html__('(One-time payment)', 'quiz-maker'); ?><span>
                            </div>
                        </th>
                        <th class="price-info">
                            <div class="price-now"><span class="sale-price" style="text-decoration: line-through; color: red;">$250</span>
                            </div>
                            <div class="price-now"><span>$195</span>
                            </div>
                            <!-- <div class="price-now"><span style="color: red; font-size: 12px;">Black Friday</span>
                            </div> -->
                            <div class="ays-quiz-pracing-table-td-flex">
                                <a href="https://ays-pro.com/wordpress/quiz-maker?utm_source=dashboard&utm_medium=quiz-free&utm_campaign=pricing-table-developer-<?php echo esc_attr( AYS_QUIZ_UTM_VERSION ); ?>" class="price-buy"><?php echo esc_html__('Buy now','quiz-maker')?><span class="hide-mobile"></span></a>
                                <span><?php echo esc_html__('(One-time payment)', 'quiz-maker'); ?><span>
                            </div>
                        </th>
                        <th class="price-info">
                            <div class="price-now"><span class="sale-price" style="text-decoration: line-through; color: red;">$450</span>
                            </div>
                            <div class="price-now"><span>$355</span>
                            </div>
                            <!-- <div class="price-now"><span style="color: red; font-size: 12px;">Black Friday</span>
                            </div> -->
                            <div class="ays-quiz-pracing-table-td-flex">
                                <a href="https://ays-pro.com/wordpress/quiz-maker?utm_source=dashboard&utm_medium=quiz-free&utm_campaign=pricing-table-agency-<?php echo esc_attr( AYS_QUIZ_UTM_VERSION ); ?>" class="price-buy"><?php echo esc_html__('Buy now','quiz-maker')?><span class="hide-mobile"></span></a>
                                <span><?php echo esc_html__('(One-time payment)', 'quiz-maker'); ?><span>
                            </div>
                        </th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td></td>
                        <td colspan="4"><?php echo esc_html__('Support for','quiz-maker')?></td>
                    </tr>
                    <tr>
                        <td><?php echo esc_html__('Support for','quiz-maker')?></td>
                        <td><span>–</span></td>
                        <td><?php echo esc_html__('5 site','quiz-maker')?></td>
                        <td><?php echo esc_html__('Unlimited sites','quiz-maker')?></td>
                        <td><?php echo esc_html__('Unlimited sites','quiz-maker')?></td>
                    </tr>
                    <tr>
                        <td> </td>
                        <td colspan="4"><?php echo esc_html__('Upgrade for','quiz-maker')?></td>
                    </tr>
                    <tr class="compare-row">
                        <td><?php echo esc_html__('Upgrade for','quiz-maker')?></td>
                        <td><span>–</span></td>
                        <td><?php echo esc_html__('12 months','quiz-maker')?></td>
                        <td><?php echo esc_html__('Lifetime','quiz-maker')?></td>
                        <td><?php echo esc_html__('Lifetime','quiz-maker')?></td>
                    </tr>
                    <tr>
                        <td></td>
                        <td colspan="4"><?php echo esc_html__('Support for','quiz-maker')?></td>
                    </tr>
                    <tr>
                        <td><?php echo esc_html__('Support for','quiz-maker')?></td>
                        <td><span>–</span></td>
                        <td><?php echo esc_html__('12 months','quiz-maker')?></td>
                        <td><?php echo esc_html__('Lifetime','quiz-maker')?></td>
                        <td><?php echo esc_html__('Lifetime','quiz-maker')?></td>
                    </tr>
                    <tr>
                        <td></td>
                        <td colspan="4"><?php echo esc_html__('Usage for','quiz-maker')?></td>
                    </tr>
                    <tr>
                        <td><?php echo esc_html__('Usage for','quiz-maker')?></td>
                        <td><?php echo esc_html__('Lifetime','quiz-maker')?></td>
                        <td><?php echo esc_html__('Lifetime','quiz-maker')?></td>
                        <td><?php echo esc_html__('Lifetime','quiz-maker')?></td>
                        <td><?php echo esc_html__('Lifetime','quiz-maker')?></td>
                    </tr>
                <tr>
                    <td> </td>
                    <td colspan="4"><?php echo esc_html__('Unlimited quizzes & attempts','quiz-maker')?></td>
                </tr>
                <tr>
                    <td><?php echo esc_html__('Unlimited quizzes & attempts','quiz-maker')?></td>
                    <td><i class="ays_fa ays_fa_check"></i></td>
                    <td><i class="ays_fa ays_fa_check"></i></td>
                    <td><i class="ays_fa ays_fa_check"></i></td>
                    <td><i class="ays_fa ays_fa_check"></i></td>
                </tr>
                <tr>
                    <td> </td>
                    <td colspan="4"><?php echo esc_html__('Capture leads & contact info','quiz-maker')?></td>
                </tr>
                <tr>
                    <td><?php echo esc_html__('Capture leads & contact info','quiz-maker')?></td>
                    <td><i class="ays_fa ays_fa_check"></i></td>
                    <td><i class="ays_fa ays_fa_check"></i></td>
                    <td><i class="ays_fa ays_fa_check"></i></td>
                    <td><i class="ays_fa ays_fa_check"></i></td>
                </tr>
                <tr>
                    <td> </td>
                    <td colspan="4"><?php echo esc_html__('Randomize questions & answers','quiz-maker')?></td>
                </tr>
                <tr>
                    <td><?php echo esc_html__('Randomize questions & answers','quiz-maker')?></td>
                    <td><i class="ays_fa ays_fa_check"></i></td>
                    <td><i class="ays_fa ays_fa_check"></i></td>
                    <td><i class="ays_fa ays_fa_check"></i></td>
                    <td><i class="ays_fa ays_fa_check"></i></td>
                </tr>
                <tr>
                    <td> </td>
                    <td colspan="4"><?php echo esc_html__('Reports in dashboard','quiz-maker')?></td>
                </tr>
                <tr>
                    <td><?php echo esc_html__('Reports in dashboard','quiz-maker')?></td>
                    <td><i class="ays_fa ays_fa_check"></i></td>
                    <td><i class="ays_fa ays_fa_check"></i></td>
                    <td><i class="ays_fa ays_fa_check"></i></td>
                    <td><i class="ays_fa ays_fa_check"></i></td>
                </tr>
                <tr>
                    <td></td>
                    <td colspan="4"><?php echo esc_html__('Export and import questions','quiz-maker')?></td>
                </tr>
                <tr>
                    <td><?php echo esc_html__('Export and import questions','quiz-maker')?></td>
                    <td><span>–</span></td>
                    <td><i class="ays_fa ays_fa_check"></i></td>
                    <td><i class="ays_fa ays_fa_check"></i></td>
                    <td><i class="ays_fa ays_fa_check"></i></td>
                </tr>
                <tr>
                    <td></td>
                    <td colspan="4"><?php echo esc_html__('Export results','quiz-maker')?></td>
                </tr>
                <tr>
                    <td><?php echo esc_html__('Export results','quiz-maker')?></td>
                    <td><span>–</span></td>
                    <td><i class="ays_fa ays_fa_check"></i></td>
                    <td><i class="ays_fa ays_fa_check"></i></td>
                    <td><i class="ays_fa ays_fa_check"></i></td>
                </tr>
                <tr>
                    <td></td>
                    <td colspan="4"><?php echo esc_html__('Add images & videos','quiz-maker')?></td>
                </tr>
                <tr>
                    <td><?php echo esc_html__('Add images & videos','quiz-maker')?></td>
                    <td><span>–</span></td>
                    <td><i class="ays_fa ays_fa_check"></i></td>
                    <td><i class="ays_fa ays_fa_check"></i></td>
                    <td><i class="ays_fa ays_fa_check"></i></td>
                </tr>
                <tr>
                    <td></td>
                    <td colspan="4"><?php echo esc_html__('Flash cards','quiz-maker')?></td>
                </tr>
                <tr>
                    <td><?php echo esc_html__('Flash cards','quiz-maker')?></td>
                    <td><span>–</span></td>
                    <td><i class="ays_fa ays_fa_check"></i></td>
                    <td><i class="ays_fa ays_fa_check"></i></td>
                    <td><i class="ays_fa ays_fa_check"></i></td>
                </tr>
                <tr>
                    <td></td>
                    <td colspan="4"><?php echo esc_html__('Send mail to user','quiz-maker')?></td>
                </tr>
                <tr>
                    <td><?php echo esc_html__('Send mail to user','quiz-maker')?></td>
                    <td><span>–</span></td>
                    <td><i class="ays_fa ays_fa_check"></i></td>
                    <td><i class="ays_fa ays_fa_check"></i></td>
                    <td><i class="ays_fa ays_fa_check"></i></td>
                </tr>
                <tr>
                    <td></td>
                    <td colspan="4"><?php echo esc_html__('Send mail to admin','quiz-maker')?></td>
                </tr>
                <tr>
                    <td><?php echo esc_html__('Send mail to admin','quiz-maker')?></td>
                    <td><span>–</span></td>
                    <td><i class="ays_fa ays_fa_check"></i></td>
                    <td><i class="ays_fa ays_fa_check"></i></td>
                    <td><i class="ays_fa ays_fa_check"></i></td>
                </tr>
                <tr>
                    <td></td>
                    <td colspan="4"><?php echo esc_html__('Personalized results','quiz-maker')?></td>
                </tr>
                <tr>
                    <td><?php echo esc_html__('Personalized results','quiz-maker')?></td>
                    <td><span>–</span></td>
                    <td><i class="ays_fa ays_fa_check"></i></td>
                    <td><i class="ays_fa ays_fa_check"></i></td>
                    <td><i class="ays_fa ays_fa_check"></i></td>
                </tr>
                <tr>
                    <td></td>
                    <td colspan="4"><?php echo esc_html__('Results with charts','quiz-maker')?></td>
                </tr>
                <tr>
                    <td><?php echo esc_html__('Results with charts','quiz-maker')?></td>
                    <td><span>–</span></td>
                    <td><i class="ays_fa ays_fa_check"></i></td>
                    <td><i class="ays_fa ays_fa_check"></i></td>
                    <td><i class="ays_fa ays_fa_check"></i></td>
                </tr>
                <tr>
                    <td></td>
                    <td colspan="4"><?php echo esc_html__('Send reports & certificate','quiz-maker')?></td>
                </tr>
                <tr>
                    <td><?php echo esc_html__('Send reports & certificate','quiz-maker')?></td>
                    <td><span>–</span></td>
                    <td><i class="ays_fa ays_fa_check"></i></td>
                    <td><i class="ays_fa ays_fa_check"></i></td>
                    <td><i class="ays_fa ays_fa_check"></i></td>
                </tr>
                <tr>
                    <td></td>
                    <td colspan="4"><?php echo esc_html__('Custom Form Fields','quiz-maker')?></td>
                </tr>
                <tr>
                    <td><?php echo esc_html__('Custom Form Fields','quiz-maker')?></td>
                    <td><span>–</span></td>
                    <td><i class="ays_fa ays_fa_check"></i></td>
                    <td><i class="ays_fa ays_fa_check"></i></td>
                    <td><i class="ays_fa ays_fa_check"></i></td>
                </tr>
                <tr>
                    <td></td>
                    <td colspan="4"><?php echo esc_html__('Google sheets integration','quiz-maker')?></td>
                </tr>
                <tr>
                    <td><?php echo esc_html__('Google sheets integration','quiz-maker')?></td>
                    <td><span>–</span></td>
                    <td><i class="ays_fa ays_fa_check"></i></td>
                    <td><i class="ays_fa ays_fa_check"></i></td>
                    <td><i class="ays_fa ays_fa_check"></i></td>
                </tr>
                <tr>
                    <td></td>
                    <td colspan="4"><?php echo esc_html__('Mailchimp integration','quiz-maker')?></td>
                </tr>
                <tr>
                    <td><?php echo esc_html__('Mailchimp integration','quiz-maker')?></td>
                    <td><span>–</span></td>
                    <td><i class="ays_fa ays_fa_check"></i></td>
                    <td><i class="ays_fa ays_fa_check"></i></td>
                    <td><i class="ays_fa ays_fa_check"></i></td>
                </tr>
                <tr>
                    <td></td>
                    <td colspan="4"><?php echo esc_html__('Campaign Monitor integration','quiz-maker')?></td>
                </tr>
                <tr>
                    <td><?php echo esc_html__('Campaign Monitor integration','quiz-maker')?></td>
                    <td><span>–</span></td>
                    <td><i class="ays_fa ays_fa_check"></i></td>
                    <td><i class="ays_fa ays_fa_check"></i></td>
                    <td><i class="ays_fa ays_fa_check"></i></td>
                </tr>
                <tr>
                    <td></td>
                    <td colspan="4"><?php echo esc_html__('Zapier integration','quiz-maker')?></td>
                </tr>
                <tr>
                    <td><?php echo esc_html__('Zapier integration','quiz-maker')?></td>
                    <td><span>–</span></td>
                    <td><i class="ays_fa ays_fa_check"></i></td>
                    <td><i class="ays_fa ays_fa_check"></i></td>
                    <td><i class="ays_fa ays_fa_check"></i></td>
                </tr>
                <tr>
                    <td></td>
                    <td colspan="4"><?php echo esc_html__('Slack integration','quiz-maker')?></td>
                </tr>
                <tr>
                    <td><?php echo esc_html__('Slack integration','quiz-maker')?></td>
                    <td><span>–</span></td>
                    <td><i class="ays_fa ays_fa_check"></i></td>
                    <td><i class="ays_fa ays_fa_check"></i></td>
                    <td><i class="ays_fa ays_fa_check"></i></td>
                </tr>
                <tr>
                    <td></td>
                    <td colspan="4"><?php echo esc_html__('ActiveCampaign integration','quiz-maker')?></td>
                </tr>
                <tr>
                    <td><?php echo esc_html__('ActiveCampaign integration','quiz-maker')?></td>
                    <td><span>–</span></td>
                    <td><i class="ays_fa ays_fa_check"></i></td>
                    <td><i class="ays_fa ays_fa_check"></i></td>
                    <td><i class="ays_fa ays_fa_check"></i></td>
                </tr>
                <tr>
                    <td></td>
                    <td colspan="4"><?php echo esc_html__('User frontend dashboard','quiz-maker')?></td>
                </tr>
                <tr>
                    <td><?php echo esc_html__('User frontend dashboard','quiz-maker')?></td>
                    <td><span>–</span></td>
                    <td><i class="ays_fa ays_fa_check"></i></td>
                    <td><i class="ays_fa ays_fa_check"></i></td>
                    <td><i class="ays_fa ays_fa_check"></i></td>
                </tr>
                <tr>
                    <td></td>
                    <td colspan="4"><?php echo esc_html__('Email configuration','quiz-maker')?></td>
                </tr>
                <tr>
                    <td><?php echo esc_html__('Email configuration','quiz-maker')?></td>
                    <td><span>–</span></td>
                    <td><i class="ays_fa ays_fa_check"></i></td>
                    <td><i class="ays_fa ays_fa_check"></i></td>
                    <td><i class="ays_fa ays_fa_check"></i></td>
                </tr>
                <tr>
                    <td></td>
                    <td colspan="4"><?php echo esc_html__('Question weight','quiz-maker')?></td>
                </tr>
                <tr>
                    <td><?php echo esc_html__('Question weight','quiz-maker')?></td>
                    <td><span>–</span></td>
                    <td><i class="ays_fa ays_fa_check"></i></td>
                    <td><i class="ays_fa ays_fa_check"></i></td>
                    <td><i class="ays_fa ays_fa_check"></i></td>
                </tr>
                <tr>
                    <td></td>
                    <td colspan="4"><?php echo esc_html__('Points system','quiz-maker')?></td>
                </tr>
                <tr>
                    <td><?php echo esc_html__('Points system','quiz-maker')?></td>
                    <td><span>–</span></td>
                    <td><i class="ays_fa ays_fa_check"></i></td>
                    <td><i class="ays_fa ays_fa_check"></i></td>
                    <td><i class="ays_fa ays_fa_check"></i></td>
                </tr>
                <!-- //////////////// -->
                <tr>
                    <td></td>
                    <td colspan="4"><?php echo esc_html__('Leaderboards','quiz-maker')?></td>
                </tr>
                <tr>
                    <td><?php echo esc_html__('Leaderboards','quiz-maker')?></td>
                    <td><span>–</span></td>
                    <td><i class="ays_fa ays_fa_check"></i></td>
                    <td><i class="ays_fa ays_fa_check"></i></td>
                    <td><i class="ays_fa ays_fa_check"></i></td>
                </tr>
                <tr>
                    <td></td>
                    <td colspan="4"><?php echo esc_html__('Make questions required','quiz-maker')?></td>
                </tr>
                <tr>
                    <td><?php echo esc_html__('Make questions required','quiz-maker')?></td>
                    <td><span>–</span></td>
                    <td><i class="ays_fa ays_fa_check"></i></td>
                    <td><i class="ays_fa ays_fa_check"></i></td>
                    <td><i class="ays_fa ays_fa_check"></i></td>
                </tr>
                <tr>
                    <td></td>
                    <td colspan="4"><?php echo esc_html__('Password protected quiz','quiz-maker')?></td>
                </tr>
                <tr>
                    <td><?php echo esc_html__('Password protected quiz','quiz-maker')?></td>
                    <td><span>–</span></td>
                    <td><i class="ays_fa ays_fa_check"></i></td>
                    <td><i class="ays_fa ays_fa_check"></i></td>
                    <td><i class="ays_fa ays_fa_check"></i></td>
                </tr>
                <tr>
                    <td></td>
                    <td colspan="4"><?php echo esc_html__('Navigation bar','quiz-maker')?></td>
                </tr>
                <tr>
                    <td><?php echo esc_html__('Navigation bar','quiz-maker')?></td>
                    <td><span>–</span></td>
                    <td><i class="ays_fa ays_fa_check"></i></td>
                    <td><i class="ays_fa ays_fa_check"></i></td>
                    <td><i class="ays_fa ays_fa_check"></i></td>
                </tr>
                <tr>
                    <td></td>
                    <td colspan="4"><?php echo esc_html__('Personality quiz','quiz-maker')?></td>
                </tr>
                <tr>
                    <td><?php echo esc_html__('Personality quiz','quiz-maker')?></td>
                    <td><span>–</span></td>
                    <td><i class="ays_fa ays_fa_check"></i></td>
                    <td><i class="ays_fa ays_fa_check"></i></td>
                    <td><i class="ays_fa ays_fa_check"></i></td>
                </tr>
                <tr>
                    <td></td>
                    <td colspan="4"><?php echo esc_html__('Multilingual quiz','quiz-maker')?></td>
                </tr>
                <tr>
                    <td><?php echo esc_html__('Multilingual quiz','quiz-maker')?></td>
                    <td><span>–</span></td>
                    <td><i class="ays_fa ays_fa_check"></i></td>
                    <td><i class="ays_fa ays_fa_check"></i></td>
                    <td><i class="ays_fa ays_fa_check"></i></td>
                </tr>
                <tr>
                    <td></td>
                    <td colspan="4"><?php echo esc_html__('Product Recommendation (Woo)','quiz-maker')?></td>
                </tr>
                <tr>
                    <td><?php echo esc_html__('Product Recommendation (Woo)','quiz-maker')?></td>
                    <td><span>–</span></td>
                    <td><i class="ays_fa ays_fa_check"></i></td>
                    <td><i class="ays_fa ays_fa_check"></i></td>
                    <td><i class="ays_fa ays_fa_check"></i></td>
                </tr>
                <!-- //////////////// -->
                <tr>
                    <td></td>
                    <td colspan="4"><?php echo esc_html__('Timer per question','quiz-maker')?></td>
                </tr>
                <tr>
                    <td><?php echo esc_html__('Timer per question','quiz-maker')?></td>
                    <td><span>–</span></td>
                    <td><span>–</span></td>
                    <td><i class="ays_fa ays_fa_check"></i></td>
                    <td><i class="ays_fa ays_fa_check"></i></td>
                </tr>
                <tr>
                    <td></td>
                    <td colspan="4"><?php echo esc_html__('Copy content protection','quiz-maker')?></td>
                </tr>
                <tr>
                    <td><?php echo esc_html__('Copy content protection','quiz-maker')?></td>
                    <td><span>–</span></td>
                    <td><span>–</span></td>
                    <td><i class="ays_fa ays_fa_check"></i></td>
                    <td><i class="ays_fa ays_fa_check"></i></td>
                </tr>
                <tr>
                    <td></td>
                    <td colspan="4"><?php echo esc_html__('Spam protection','quiz-maker')?></td>
                </tr>
                <tr>
                    <td><?php echo esc_html__('Spam protection','quiz-maker')?></td>
                    <td><span>–</span></td>
                    <td><span>–</span></td>
                    <td><i class="ays_fa ays_fa_check"></i></td>
                    <td><i class="ays_fa ays_fa_check"></i></td>
                </tr>
                <tr>
                    <td></td>
                    <td colspan="4"><?php echo esc_html__('PayPal integration','quiz-maker')?></td>
                </tr>
                <tr>
                    <td><?php echo esc_html__('PayPal integration','quiz-maker')?></td>
                    <td><span>–</span></td>
                    <td><span>–</span></td>
                    <td><i class="ays_fa ays_fa_check"></i></td>
                    <td><i class="ays_fa ays_fa_check"></i></td>
                </tr>
                <tr>
                    <td></td>
                    <td colspan="4"><?php echo esc_html__('Stripe integration','quiz-maker')?></td>
                </tr>
                <tr>
                    <td><?php echo esc_html__('Stripe integration','quiz-maker')?></td>
                    <td><span>–</span></td>
                    <td><span>–</span></td>
                    <td><i class="ays_fa ays_fa_check"></i></td>
                    <td><i class="ays_fa ays_fa_check"></i></td>
                </tr>
                <tr>
                    <td></td>
                    <td colspan="4"><?php echo esc_html__('Embed quiz','quiz-maker')?></td>
                </tr>
                <tr>
                    <td><?php echo esc_html__('Embed quiz','quiz-maker')?></td>
                    <td><span>–</span></td>
                    <td><span>–</span></td>
                    <td><i class="ays_fa ays_fa_check"></i></td>
                    <td><i class="ays_fa ays_fa_check"></i></td>
                </tr>

                <tr>
                    <td></td>
                    <td colspan="4"><?php echo esc_html__('GamiPress integration','quiz-maker')?></td>
                </tr>
                <tr>
                    <td><?php echo esc_html__('GamiPress integration','quiz-maker')?></td>
                    <td><span>–</span></td>
                    <td><span>–</span></td>
                    <td><span>–</span></td>
                    <td><i class="ays_fa ays_fa_check"></i></td>
                </tr>

                <tr>
                    <td></td>
                    <td colspan="4"><?php echo esc_html__('MyCred integration','quiz-maker')?></td>
                </tr>
                <tr>
                    <td><?php echo esc_html__('MyCred integration','quiz-maker')?></td>
                    <td><span>–</span></td>
                    <td><span>–</span></td>
                    <td><span>–</span></td>
                    <td><i class="ays_fa ays_fa_check"></i></td>
                </tr>

                <tr>
                    <td></td>
                    <td colspan="4"><?php echo esc_html__('Frontend Statistics','quiz-maker')?></td>
                </tr>
                <tr>
                    <td><?php echo esc_html__('Frontend Statistics','quiz-maker')?></td>
                    <td><span>–</span></td>
                    <td><span>–</span></td>
                    <td><span>–</span></td>
                    <td><i class="ays_fa ays_fa_check"></i></td>
                </tr>

                <tr>
                    <td></td>
                    <td colspan="4"><?php echo esc_html__('Brevo integration','quiz-maker')?></td>
                </tr>
                <tr>
                    <td><?php echo esc_html__('Brevo integration','quiz-maker')?></td>
                    <td><span>–</span></td>
                    <td><span>–</span></td>
                    <td><span>–</span></td>
                    <td><i class="ays_fa ays_fa_check"></i></td>
                </tr>

                <tr>
                    <td></td>
                    <td colspan="4"><?php echo esc_html__('MailerLite integration','quiz-maker')?></td>
                </tr>
                <tr>
                    <td><?php echo esc_html__('MailerLite integration','quiz-maker')?></td>
                    <td><span>–</span></td>
                    <td><span>–</span></td>
                    <td><span>–</span></td>
                    <td><i class="ays_fa ays_fa_check"></i></td>
                </tr>

                <tr>
                    <td></td>
                    <td colspan="4"><?php echo esc_html__('Mailpoet integration','quiz-maker')?></td>
                </tr>
                <tr>
                    <td><?php echo esc_html__('Mailpoet integration','quiz-maker')?></td>
                    <td><span>–</span></td>
                    <td><span>–</span></td>
                    <td><span>–</span></td>
                    <td><i class="ays_fa ays_fa_check"></i></td>
                </tr>

                <tr>
                    <td></td>
                    <td colspan="4"><?php echo esc_html__('User Dashboard','quiz-maker')?></td>
                </tr>
                <tr>
                    <td><?php echo esc_html__('User Dashboard','quiz-maker')?></td>
                    <td><span>–</span></td>
                    <td><span>–</span></td>
                    <td><span>–</span></td>
                    <td><i class="ays_fa ays_fa_check"></i></td>
                </tr>

                <tr>
                    <td></td>
                    <td colspan="4"><?php echo esc_html__('Track Users','quiz-maker')?></td>
                </tr>
                <tr>
                    <td><?php echo esc_html__('Track Users','quiz-maker')?></td>
                    <td><span>–</span></td>
                    <td><span>–</span></td>
                    <td><span>–</span></td>
                    <td><i class="ays_fa ays_fa_check"></i></td>
                </tr>

                <tr>
                    <td></td>
                    <td colspan="4"><?php echo esc_html__('Frontend Request','quiz-maker')?></td>
                </tr>
                <tr>
                    <td><?php echo esc_html__('Frontend Request','quiz-maker')?></td>
                    <td><span>–</span></td>
                    <td><span>–</span></td>
                    <td><span>–</span></td>
                    <td><i class="ays_fa ays_fa_check"></i></td>
                </tr>

                <tr>
                    <td></td>
                    <td colspan="4"><?php echo esc_html__('Popup Quiz','quiz-maker')?></td>
                </tr>
                <tr>
                    <td><?php echo esc_html__('Popup Quiz','quiz-maker')?></td>
                    <td><span>–</span></td>
                    <td><span>–</span></td>
                    <td><span>–</span></td>
                    <td><i class="ays_fa ays_fa_check"></i></td>
                </tr>

                <tr>
                    <td></td>
                    <td colspan="4"><?php echo esc_html__('Chained Quiz','quiz-maker')?></td>
                </tr>
                <tr>
                    <td><?php echo esc_html__('Chained Quiz','quiz-maker')?></td>
                    <td><span>–</span></td>
                    <td><span>–</span></td>
                    <td><span>–</span></td>
                    <td><i class="ays_fa ays_fa_check"></i></td>
                </tr>

                <tr>
                    <td></td>
                    <td colspan="4"><?php echo esc_html__('Conditional Results','quiz-maker')?></td>
                </tr>
                <tr>
                    <td><?php echo esc_html__('Conditional Results','quiz-maker')?></td>
                    <td><span>–</span></td>
                    <td><span>–</span></td>
                    <td><span>–</span></td>
                    <td><i class="ays_fa ays_fa_check"></i></td>
                </tr>

                <tr>
                    <td></td>
                    <td colspan="4"><?php echo esc_html__('Easy Digital Downloads','quiz-maker')?></td>
                </tr>
                <tr>
                    <td><?php echo esc_html__('Easy Digital Downloads','quiz-maker')?></td>
                    <td><span>–</span></td>
                    <td><span>–</span></td>
                    <td><span>–</span></td>
                    <td><i class="ays_fa ays_fa_check"></i></td>
                </tr>

                <tr>
                    <td></td>
                    <td colspan="4"><?php echo esc_html__('Sell via Woocommerce','quiz-maker')?></td>
                </tr>
                <tr>
                    <td><?php echo esc_html__('Sell via Woocommerce','quiz-maker')?></td>
                    <td><span>–</span></td>
                    <td><span>–</span></td>
                    <td><span>–</span></td>
                    <td><i class="ays_fa ays_fa_check"></i></td>
                </tr>
                <tr>
                    <td></td>
                    <td colspan="4"><?php echo esc_html__('ChatGPT integration','quiz-maker')?></td>
                </tr>
                <tr>
                    <td><?php echo esc_html__('ChatGPT integration','quiz-maker')?></td>
                    <td><span>–</span></td>
                    <td><span>–</span></td>
                    <td><span>–</span></td>
                    <td><i class="ays_fa ays_fa_check"></i></td>
                </tr>
                <tr>
                    <td></td>
                    <td colspan="4"><?php echo esc_html__('Save and Resume','quiz-maker')?></td>
                </tr>
                <tr>
                    <td><?php echo esc_html__('Save and Resume','quiz-maker')?></td>
                    <td><span>–</span></td>
                    <td><span>–</span></td>
                    <td><span>–</span></td>
                    <td><i class="ays_fa ays_fa_check"></i></td>
                </tr>

                <tr>
                    <td> </td>
                </tr>
                <tr>
                    <td></td>
                    <td></td>
                    <td>
                        <div class="ays-quiz-pracing-table-td-flex">
                            <a href="https://ays-pro.com/wordpress/quiz-maker?utm_source=dashboard&utm_medium=quiz-free&utm_campaign=pricing-table-business-<?php echo esc_attr( AYS_QUIZ_UTM_VERSION ); ?>" class="price-buy"><?php echo esc_html__('Buy now','quiz-maker')?><span class="hide-mobile"></span></a>
                            <span><?php echo esc_html__('(One-time payment)', 'quiz-maker'); ?><span>
                        </div>
                    </td>
                    <td>
                        <div class="ays-quiz-pracing-table-td-flex">
                            <a href="https://ays-pro.com/wordpress/quiz-maker?utm_source=dashboard&utm_medium=quiz-free&utm_campaign=pricing-table-developer-<?php echo esc_attr( AYS_QUIZ_UTM_VERSION ); ?>" class="price-buy"><?php echo esc_html__('Buy now','quiz-maker')?><span class="hide-mobile"></span></a>
                            <span><?php echo esc_html__('(One-time payment)', 'quiz-maker'); ?><span>
                        </div>
                    </td>
                    <td>
                        <div class="ays-quiz-pracing-table-td-flex">
                            <a href="https://ays-pro.com/wordpress/quiz-maker?utm_source=dashboard&utm_medium=quiz-free&utm_campaign=pricing-table-agency-<?php echo esc_attr( AYS_QUIZ_UTM_VERSION ); ?>" class="price-buy"><?php echo esc_html__('Buy now','quiz-maker')?><span class="hide-mobile"></span></a>
                            <span><?php echo esc_html__('(One-time payment)', 'quiz-maker'); ?><span>
                        </div>
                    </td>
                </tr>
                </tbody>
            </table>
        </div>
    </div>
    <div class="ays-quiz-heading-box ays-quiz-unset-float ays-quiz-heading-box-center" style="gap: 20px;">
        <div class="ays-quiz-wordpress-user-manual-box">
            <a href="<?php echo esc_url('https://quiz-plugin.com/features/?utm_source=dashboard&utm_medium=quiz-free&utm_campaign=pricing-menu-all-features-' . AYS_QUIZ_UTM_VERSION); ?>" target="_blank" style="text-decoration: none;font-size: 20px;">
                <i class="ays_fa ays_fa_file_text" width="24"></i> 
                <span style="margin-left: 3px; text-decoration: underline; font-size: 18px;"><?php echo esc_html__('All Features', "quiz-maker"); ?></span>
            </a>
        </div>
        <div class="ays-quiz-wordpress-user-manual-box">
            <a href="https://www.youtube.com/watch?v=y-uCWOVmdEE" target="_blank" style="text-decoration: none;font-size: 20px;">
                <span><img src='<?php echo AYS_QUIZ_ADMIN_URL; ?>/images/icons/youtube-video-icon.svg' width="24"></span>
                <span style="margin-left: 3px; text-decoration: underline; font-size: 18px;"><?php echo esc_html__('Watch plans comparison video', "quiz-maker"); ?></span>
            </a>
        </div>
    </div>

    <div class="ays-quiz-heading-box ays-quiz-unset-float ays-quiz-heading-box-center" style="gap: 20px; margin-top: 30px;">
        <div class="ays-quiz-wordpress-user-manual-box">
            <a href="<?php echo esc_url('https://quiz.ays-plugins.com/?utm_source=dashboard&utm_medium=quiz-free&utm_campaign=pricing-menu-live-demo-' . AYS_QUIZ_UTM_VERSION); ?>" target="_blank" class="ays-quiz-comparison-secondary-button" style="text-decoration: none;font-size: 20px;">
                <span style="margin-left: 3px; font-size: 18px;"><?php echo esc_html__('Try Pro Version Live demo', "quiz-maker"); ?></span>
            </a>
        </div>
    </div>
    <div class="ays-quiz-sm-content-row-sg">
        <div class="ays-quiz-sm-guarantee-container-sg ays-quiz-sm-center-box-sg">
            <img src="<?php echo esc_url( AYS_QUIZ_ADMIN_URL ); ?>/images/money_back_logo.webp" alt="Best money-back guarantee logo">
            <div class="ays-quiz-sm-guarantee-text-container-sg">
                <h3><?php echo esc_html__("30 day money back guarantee !!!", 'quiz-maker'); ?></h3>
                <p>
                    <?php echo esc_html__("We're sure that you'll love our Quiz Maker plugin, but, if for some reason, you're not
                    satisfied in the first 30 days of using our product, there is a money-back guarantee and
                    we'll issue a refund.", 'quiz-maker'); ?>
                    
                </p>
            </div>
        </div>
    </div>
</div>

