<?php

$loader_iamge = "<span class='display_none ays_quiz_loader_box'><img src='". esc_url(AYS_QUIZ_ADMIN_URL) ."/images/loaders/loading.gif'></span>";
$youtube_icon_svg = "<span class=''><img src='". AYS_QUIZ_ADMIN_URL ."/images/icons/youtube-video-icon.svg'></span>";

if(isset($_GET['ays_quiz_tab'])){
    $ays_quiz_tab = sanitize_key( $_GET['ays_quiz_tab'] );
}else{
    $ays_quiz_tab = 'tab1';
}

?>
<div class="wrap" style="position:relative;">
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
            echo esc_html__('Integrations','quiz-maker');
        ?>
        </h1>
        <?php do_action('ays_quiz_sale_banner'); ?>
        <form method="post" class="ays-quiz-general-settings-form ays-quiz-general-settings-integration-page" id="ays-quiz-general-settings-form">
            <input type="hidden" name="ays_quiz_tab" value="<?php echo esc_attr($ays_quiz_tab); ?>">
            <hr/>
            <div class="form-group ays-settings-wrapper">
                <div class="ays-quiz-tabs-wrapper" style="border: unset;">
                    <div id="tab2" class="ays-quiz-tab-content ays-quiz-tab-content-active">
                        <fieldset>
                            <legend>
                                <img class="ays_integration_logo" src="<?php echo esc_url( AYS_QUIZ_ADMIN_URL ); ?>/images/integrations/mailchimp_logo.png" alt="">
                                <h5><?php echo esc_html__('MailChimp','quiz-maker'); ?></h5>
                            </legend>
                            <div class="form-group row" style="padding:0;margin:0;">
                                <div class="col-sm-12">
                                    <div class="ays-quiz-heading-box ays-quiz-unset-float ays-quiz-unset-margin">
                                        <div class="ays-quiz-wordpress-user-manual-box ays-quiz-wordpress-text-align">
                                            <a href="https://www.youtube.com/watch?v=joPQrsF0a60" target="_blank" style="text-decoration: none;">
                                                <?php echo wp_kses_post($youtube_icon_svg); ?>
                                                <span style="margin-left: 3px; text-decoration: underline;"><?php echo esc_html__("How to integrate MailChimp - video", 'quiz-maker'); ?></span>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-12 only_pro ays-quiz-margin-top-20" style="padding:20px;">
                                    <div class="pro_features" style="justify-content:flex-end;">

                                    </div>
                                    <div class="form-group row" aria-describedby="aaa">
                                        <div class="col-sm-3">
                                            <label for="ays_mailchimp_username">
                                                <?php echo esc_html__('MailChimp Username','quiz-maker')?>
                                            </label>
                                        </div>
                                        <div class="col-sm-9">
                                            <input type="text" 
                                                class="ays-text-input" 
                                                id="ays_mailchimp_username" 
                                                name="ays_mailchimp_username"
                                            />
                                        </div>
                                    </div>
                                    <hr/>
                                    <div class="form-group row" aria-describedby="aaa">
                                        <div class="col-sm-3">
                                            <label for="ays_mailchimp_api_key">
                                                <?php echo esc_html__('MailChimp API Key','quiz-maker')?>
                                            </label>
                                        </div>
                                        <div class="col-sm-9">
                                            <input type="text" 
                                                class="ays-text-input" 
                                                id="ays_mailchimp_api_key" 
                                                name="ays_mailchimp_api_key"
                                            />
                                        </div>
                                    </div>
                                    <blockquote>
                                        <?php echo sprintf( esc_attr( __( "You can get your API key from your %s Account Extras menu. %s", 'quiz-maker' ) ), "<a href='https://us20.admin.mailchimp.com/account/api/' target='_blank'>", '</a>' ); ?>
                                    </blockquote>
                                    <a href="https://ays-pro.com/wordpress/quiz-maker?utm_source=dashboard&utm_medium=quiz-free&utm_campaign=mailchimp-integration-menu-<?php echo esc_attr( AYS_QUIZ_UTM_VERSION ); ?>" target="_blank" class="ays-quiz-new-upgrade-button-link">
                                        <div class="ays-quiz-new-upgrade-button-box">
                                            <div>
                                                <img src="<?php echo esc_url(AYS_QUIZ_ADMIN_URL.'/images/icons/locked_24x24.svg'); ?>">
                                                <img src="<?php echo esc_url(AYS_QUIZ_ADMIN_URL.'/images/icons/unlocked_24x24.svg'); ?>" class="ays-quiz-new-upgrade-button-hover">
                                            </div>
                                            <div class="ays-quiz-new-upgrade-button"><?php echo esc_html__("Upgrade", "quiz-maker"); ?></div>
                                        </div>
                                    </a>
                                </div>
                            </div>
                        </fieldset> <!-- MailChimp -->
                        <hr/>                    
                        <fieldset>
                            <legend>
                                <img class="ays_integration_logo" src="<?php echo esc_url(AYS_QUIZ_ADMIN_URL); ?>/images/integrations/paypal_logo.png" alt="">
                                <h5><?php echo esc_html__('PayPal','quiz-maker')?></h5>
                            </legend>
                            <div class="form-group row" style="padding:0;margin:0;">
                                <div class="col-sm-12">
                                    <div class="ays-quiz-heading-box ays-quiz-unset-float ays-quiz-unset-margin">
                                        <div class="ays-quiz-wordpress-user-manual-box ays-quiz-wordpress-text-align">
                                            <a href="https://www.youtube.com/watch?v=IwT-2d9OE1g" target="_blank" style="text-decoration: none;">
                                                <?php echo wp_kses_post($youtube_icon_svg); ?>
                                                <span style="margin-left: 3px; text-decoration: underline;"><?php echo esc_html__("How to integrate PayPal - video", 'quiz-maker'); ?></span>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-12 only_pro ays-quiz-margin-top-20" style="padding:20px;">
                                    <div class="pro_features" style="justify-content:flex-end;">

                                    </div>
                                    <div class="form-group row" aria-describedby="aaa">
                                        <div class="col-sm-3">
                                            <label for="ays_paypal_client_id">
                                                <?php echo esc_html__('PayPal Client ID','quiz-maker')?>
                                            </label>
                                        </div>
                                        <div class="col-sm-9">
                                            <input type="text" 
                                                class="ays-text-input" 
                                                id="ays_paypal_client_id" 
                                                name="ays_paypal_client_id"
                                            />
                                        </div>
                                    </div>
                                    <hr>
                                    <div class="form-group row" aria-describedby="aaa">
                                        <div class="col-sm-3">
                                            <label>
                                                <?php echo esc_html__('Payment terms','quiz-maker'); ?>
                                                <a class="ays_help" style="font-size:15px;" data-toggle="tooltip" data-html="true"
                                                title="<?php
                                                    echo __('Choose your preferred method.',$this->plugin_name) .
                                                    "<ul style='list-style-type: circle;padding-left: 20px;'>".
                                                        "<li>". __('Lifetime  - By enabling this option, the user pays once at the beginning and gets access to the given quiz each time starting from that moment. It detects the user after the first attempt based on their WP user ID (designed for logged-in users).','quiz-maker') ."</li>".
                                                        "<li>". __('Onetime - By enabling this option, the user needs to pay each time separately for taking the quiz.','quiz-maker') ."</li>".
                                                        "<li>". __('Active Period - By enabling this option, the quiz will be available during your chosen period of time. You can set the active period duration by Day, Month or Year. This option is (designed for logged-in users).','quiz-maker') ."</li>".
                                                        "<li>". __('Limit By Count - By enabling this option, users can take the quiz up to the set limit. Once the limit is reached, they will need to pay again to retake the quiz. For example, if the limit count is set as 5, the users can pay for the quiz and pass it 5 times. Once the limit is reached, they will need to make another payment to continue taking the quiz.','quiz-maker') ."</li>".
                                                    "</ul>";
                                                ?>">
                                                <i class="ays_fa ays_fa_info_circle"></i>
                                            </a>
                                            </label>
                                        </div>
                                        <div class="col-sm-9">
                                            <label class="ays_quiz_loader" style="display:inline-block;">
                                                <input type="radio" />
                                                <span><?php echo esc_html__('Lifetime payment','quiz-maker'); ?></span>
                                            </label>
                                            <label class="ays_quiz_loader" style="display:inline-block;">
                                                <input type="radio" />
                                                <span><?php echo esc_html__('Onetime payment','quiz-maker'); ?></span>
                                            </label>
                                            <label class="ays_quiz_loader" style="display:inline-block;">
                                                <input type="radio" />
                                                <span><?php echo esc_html__('Active Period','quiz-maker'); ?></span>
                                            </label>
                                            <label class="ays_quiz_loader" style="display:inline-block;">
                                                <input type="radio" />
                                                <span><?php echo esc_html__('Limit by Count','quiz-maker'); ?></span>
                                            </label>
                                            <!-- <label class="ays_toggle_target" style="display:inline-block;">
                                                <input type="checkbox" value="on"/>
                                                <span><?php echo esc_html__('Turn on extra security check', 'quiz-maker'); ?></span>
                                                <a class="ays_help" data-toggle="tooltip" title="<?php echo esc_attr( __('When the user pays for the quiz and starts passing it but leaves without finishing, he/she has to pay again every time he wants to pass it.','quiz-maker') );?>">
                                                    <i class="ays_fa ays_fa_info_circle"></i>
                                                </a>
                                            </label> -->
                                            <hr>
                                            <div class="ays_toggle_target_2" style="display:block;">
                                                <div class="form-group row">
                                                    <div class="col-sm-4">
                                                        <label class="form-check-label" for="ays-subscribtion-duration"> <?php echo esc_html__('Active Period duration', 'quiz-maker'); ?> </label>
                                                    </div>
                                                    <div class="col-sm-8 d-flex">
                                                        <input type="text" class="ays-text-input ays-text-input-short" value="30">
                                                        <select name="ays-subscribtion-duration-by" class="ays-text-input-short ml-3">
                                                            <option><?php echo esc_html__( "Day", 'quiz-maker' ); ?></option>
                                                            <option><?php echo esc_html__( "Month", 'quiz-maker' ); ?></option>
                                                            <option><?php echo esc_html__( "Year", 'quiz-maker' ); ?></option>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <blockquote>
                                        <?php echo sprintf( wp_kses_post( __( "You can get your Client ID from", 'quiz-maker' ) ) . "<a href='%s' target='_blank'> %s.</a>", "https://developer.paypal.com/developer/applications", esc_html__( "Developer PayPal", 'quiz-maker' ) ); ?>
                                    </blockquote>
                                    <a href="https://ays-pro.com/wordpress/quiz-maker?utm_source=dashboard&utm_medium=quiz-free&utm_campaign=paypal-integration-menu-<?php echo esc_attr( AYS_QUIZ_UTM_VERSION ); ?>" target="_blank" class="ays-quiz-new-upgrade-button-link">
                                        <div class="ays-quiz-new-upgrade-button-box">
                                            <div>
                                                <img src="<?php echo esc_url( AYS_QUIZ_ADMIN_URL.'/images/icons/locked_24x24.svg' ); ?>">
                                                <img src="<?php echo esc_url( AYS_QUIZ_ADMIN_URL.'/images/icons/unlocked_24x24.svg'); ?>" class="ays-quiz-new-upgrade-button-hover">
                                            </div>
                                            <div class="ays-quiz-new-upgrade-button"><?php echo esc_html__("Upgrade to Developer/Agency", "quiz-maker"); ?></div>
                                        </div>
                                    </a>
                                </div>
                            </div>
                        </fieldset> <!-- PayPal -->
                        <hr/>
                        <fieldset>
                            <legend>
                                <img class="ays_integration_logo" src="<?php echo esc_url(AYS_QUIZ_ADMIN_URL); ?>/images/integrations/stripe_logo.png" alt="">
                                <h5><?php echo esc_html__('Stripe','quiz-maker')?></h5>
                            </legend>
                            <div class="form-group row" style="padding:0;margin:0;">
                                <div class="col-sm-12 only_pro" style="padding:20px;">
                                    <div class="pro_features" style="justify-content:flex-end;">

                                    </div>
                                    <div class="form-group row">
                                        <div class="col-sm-12">
                                            <div class="form-group row">
                                                <div class="col-sm-3">
                                                    <label for="ays_stripe_api_key">
                                                        Stripe <?php echo esc_html__('Publishable Key', 'quiz-maker'); ?>
                                                    </label>
                                                </div>
                                                <div class="col-sm-9">
                                                    <input type="text" class="ays-text-input" value="" >
                                                </div>
                                            </div>
                                            <hr/>
                                            <div class="form-group row">
                                                <div class="col-sm-3">
                                                    <label for="ays_stripe_secret_key">
                                                        Stripe <?php echo esc_html__('Secret Key', 'quiz-maker'); ?>
                                                    </label>
                                                </div>
                                                <div class="col-sm-9">
                                                    <input type="text" class="ays-text-input" value="" >
                                                </div>
                                            </div>
                                            <hr/>
                                            <div class="form-group row">
                                                <div class="col-sm-3">
                                                    <label>
                                                        <?php echo esc_html__('Payment terms','quiz-maker'); ?>
                                                        <a class="ays_help" style="font-size:15px;" data-toggle="tooltip" data-html="true"
                                                            title="<?php
                                                                echo __('Choose your preferred method.','quiz-maker') .
                                                                "<ul style='list-style-type: circle;padding-left: 20px;'>".
                                                                    "<li>". __('Lifetime  - By enabling this option, the user pays once at the beginning and gets access to the given quiz each time starting from that moment. It detects the user after the first attempt based on their WP user ID (designed for logged-in users).','quiz-maker') ."</li>".
                                                                    "<li>". __('Onetime - By enabling this option, the user needs to pay each time separately for taking the quiz.','quiz-maker') ."</li>".
                                                                    "<li>". __('Active Period - By enabling this option, the quiz will be available during your chosen period of time. You can set the active period duration by Day, Month or Year. This option is (designed for logged-in users).','quiz-maker') ."</li>".
                                                                    "<li>". __('Limit By Count - By enabling this option, users can take the quiz up to the set limit. Once the limit is reached, they will need to pay again to retake the quiz. For example, if the limit count is set as 5, the users can pay for the quiz and pass it 5 times. Once the limit is reached, they will need to make another payment to continue taking the quiz.','quiz-maker') ."</li>".
                                                                "</ul>";
                                                            ?>">
                                                            <i class="ays_fa ays_fa_info_circle"></i>
                                                        </a>
                                                    </label>
                                                </div>
                                                <div class="col-sm-9">
                                                    <label class="ays_quiz_loader" style="display:inline-block;">
                                                        <input type="radio" />
                                                        <span><?php echo esc_html__('Lifetime payment','quiz-maker');?></span>
                                                    </label>
                                                    <label class="ays_quiz_loader" style="display:inline-block;">
                                                        <input type="radio" />
                                                        <span><?php echo esc_html__('Onetime payment','quiz-maker');?></span>
                                                    </label>
                                                    <label class="ays_quiz_loader" style="display:inline-block;">
                                                        <input type="radio" />
                                                        <span><?php echo __('Active Period','quiz-maker'); ?></span>
                                                    </label>
                                                    <label class="ays_quiz_loader" style="display:inline-block;">
                                                        <input type="radio" />
                                                        <span><?php echo esc_html__('Limit by Count','quiz-maker'); ?></span>
                                                    </label>
                                                </div>
                                            </div>
                                            <blockquote>
                                                <?php echo esc_html__("You can get your Publishable and Secret keys on API Keys page on your Stripe dashboard.", 'quiz-maker'); ?>
                                            </blockquote>
                                        </div>
                                    </div>
                                    <a href="https://ays-pro.com/wordpress/quiz-maker?utm_source=dashboard&utm_medium=quiz-free&utm_campaign=stripe-integration-menu-<?php echo esc_attr( AYS_QUIZ_UTM_VERSION ); ?>" target="_blank" class="ays-quiz-new-upgrade-button-link">
                                        <div class="ays-quiz-new-upgrade-button-box">
                                            <div>
                                                <img src="<?php echo esc_url(AYS_QUIZ_ADMIN_URL.'/images/icons/locked_24x24.svg'); ?>">
                                                <img src="<?php echo esc_url(AYS_QUIZ_ADMIN_URL.'/images/icons/unlocked_24x24.svg'); ?>" class="ays-quiz-new-upgrade-button-hover">
                                            </div>
                                            <div class="ays-quiz-new-upgrade-button"><?php echo esc_html__("Upgrade to Developer/Agency", "quiz-maker"); ?></div>
                                        </div>
                                    </a>
                                </div>
                            </div>
                        </fieldset> <!-- Stripe -->
                        <hr/>
                        <fieldset>
                            <legend>
                                <img class="ays_integration_logo" src="<?php echo esc_url(AYS_QUIZ_ADMIN_URL); ?>/images/integrations/recaptcha_logo.png" alt="">
                                <h5><?php echo esc_html__('reCAPTCHA','quiz-maker'); ?></h5>
                            </legend>
                            <div class="form-group row" style="padding:0;margin:0;">
                                <div class="col-sm-12 only_pro" style="padding:20px;">
                                    <div class="pro_features" style="justify-content:flex-end;">
                                    </div>
                                    <div class="form-group row">
                                        <div class="col-sm-12">
                                            <div class="form-group row">
                                                <div class="col-sm-3">
                                                    <label for="ays_quiz_recaptcha_site_key"><?php echo esc_html__('reCAPTCHA v2 Site Key', 'quiz-maker'); ?></label>
                                                </div>
                                                <div class="col-sm-9">
                                                    <input type="text" class="ays-text-input" id="ays_quiz_recaptcha_site_key" value="" >
                                                </div>
                                            </div>
                                            <hr/>
                                            <div class="form-group row">
                                                <div class="col-sm-3">
                                                    <label for="ays_quiz_recaptcha_secret_key"><?php echo esc_html__('reCAPTCHA v2 Secret Key', 'quiz-maker'); ?></label>
                                                </div>
                                                <div class="col-sm-9">
                                                    <input type="text" class="ays-text-input" id="ays_quiz_recaptcha_secret_key" value="" >
                                                </div>
                                            </div>
                                            <hr/>
                                            <div class="form-group row">
                                                <div class="col-sm-3">
                                                    <label for="ays_quiz_recaptcha_language"><?php echo esc_html__('reCAPTCHA Language', 'quiz-maker'); ?></label>
                                                </div>
                                                <div class="col-sm-9">
                                                    <input type="text" class="ays-text-input" id="ays_quiz_recaptcha_language" value="" >
                                                    <span class="ays_quiz_small_hint_text">
                                                        <span><?php echo sprintf(
                                                            wp_kses_post( __( "e.g. en, de - Language used by reCAPTCHA. To get the code for your language click %s here %s", 'quiz-maker' ) ),
                                                            '<a href="https://developers.google.com/recaptcha/docs/language" target="_blank">',
                                                            "</a>"
                                                        ) ?></span>
                                                    </span>
                                                </div>
                                            </div>
                                            <hr/>
                                            <div class="form-group row">
                                                <div class="col-sm-3">
                                                    <label for="ays_quiz_recaptcha_theme"><?php echo esc_html__('reCAPTCHA Theme', 'quiz-maker'); ?></label>
                                                </div>
                                                <div class="col-sm-9">
                                                    <select class="ays-text-input" id="ays_quiz_recaptcha_theme">
                                                        <option value="light"><?php echo esc_html__('Light', 'quiz-maker'); ?></option>
                                                        <option value="dark"><?php echo esc_html__('Dark', 'quiz-maker'); ?></option>
                                                    </select>
                                                </div>
                                            </div>
                                            <blockquote>
                                                <?php echo sprintf( wp_kses_post( __( "You need to set up reCAPTCHA in your Google account to generate the required keys and get them by %s Google's reCAPTCHA admin console %s.", 'quiz-maker' ) ), "<a href='https://www.google.com/recaptcha/admin/create' target='_blank'>", "</a>"); ?>
                                            </blockquote>
                                        </div>
                                    </div>
                                    <a href="https://ays-pro.com/wordpress/quiz-maker?utm_source=dashboard&utm_medium=quiz-free&utm_campaign=recaptcha-integration-menu-<?php echo esc_attr( AYS_QUIZ_UTM_VERSION ); ?>" target="_blank" class="ays-quiz-new-upgrade-button-link">
                                        <div class="ays-quiz-new-upgrade-button-box">
                                            <div>
                                                <img src="<?php echo esc_url(AYS_QUIZ_ADMIN_URL.'/images/icons/locked_24x24.svg'); ?>">
                                                <img src="<?php echo esc_url(AYS_QUIZ_ADMIN_URL.'/images/icons/unlocked_24x24.svg'); ?>" class="ays-quiz-new-upgrade-button-hover">
                                            </div>
                                            <div class="ays-quiz-new-upgrade-button"><?php echo esc_html__("Upgrade to Developer/Agency", "quiz-maker"); ?></div>
                                        </div>
                                    </a>
                                </div>
                            </div>
                        </fieldset> <!-- reCAPTCHA -->
                        <hr/>
                        <fieldset>
                            <legend>
                                <img class="ays_integration_logo" src="<?php echo esc_url(AYS_QUIZ_ADMIN_URL); ?>/images/integrations/campaignmonitor_logo.png" alt="">
                                <h5><?php echo esc_html__('Campaign Monitor','quiz-maker')?></h5>
                            </legend>
                            <div class="form-group row" style="padding:0;margin:0;">
                                <div class="col-sm-12 only_pro" style="padding:20px;">
                                    <div class="pro_features" style="justify-content:flex-end;">

                                    </div>
                                    <div class="form-group row" aria-describedby="aaa">
                                        <div class="col-sm-3">
                                            <label for="ays_monitor_client">
                                                <?php echo esc_html__('Campaign Monitor Client ID', 'quiz-maker'); ?>
                                            </label>
                                        </div>
                                        <div class="col-sm-9">
                                            <input type="text" class="ays-text-input" id="ays_monitor_client" >
                                        </div>
                                    </div>
                                    <hr/>
                                    <div class="form-group row" aria-describedby="aaa">
                                        <div class="col-sm-3">
                                            <label for="ays_monitor_api_key">
                                                <?php echo esc_html__('Campaign Monitor API Key', 'quiz-maker'); ?>
                                            </label>
                                        </div>
                                        <div class="col-sm-9">
                                            <input type="text" class="ays-text-input" id="ays_monitor_api_key">
                                        </div>
                                    </div>
                                    <blockquote>
                                        <?php echo esc_html__("You can get your API key and Client ID from your Account Settings page", 'quiz-maker'); ?>
                                    </blockquote>
                                    <a href="https://ays-pro.com/wordpress/quiz-maker?utm_source=dashboard&utm_medium=quiz-free&utm_campaign=campaign-monitor-integration-menu-<?php echo esc_attr( AYS_QUIZ_UTM_VERSION ); ?>" target="_blank" class="ays-quiz-new-upgrade-button-link">
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
                        </fieldset> <!-- Campaign Monitor -->
                        <hr/>
                        <fieldset>
                            <legend>
                                <img class="ays_integration_logo" src="<?php echo esc_url( AYS_QUIZ_ADMIN_URL ); ?>/images/integrations/zapier_logo.png" alt="">
                                <h5><?php echo esc_html__('Zapier','quiz-maker')?></h5>
                            </legend>
                            <div class="form-group row" style="padding:0;margin:0;">
                                <div class="col-sm-12 only_pro" style="padding:20px;">
                                    <div class="pro_features" style="justify-content:flex-end;">

                                    </div>
                                    <div class="form-group row" aria-describedby="aaa">
                                        <div class="col-sm-3">
                                            <label for="ays_zapier_hook">
                                                <?php esc_html__('Zapier Webhook URL', 'quiz-maker'); ?>
                                            </label>
                                        </div>
                                        <div class="col-sm-9">
                                            <input type="text" class="ays-text-input" id="ays_zapier_hook">
                                        </div>
                                    </div>
                                    <blockquote>
                                        <?php echo sprintf( wp_kses_post( __("If you do not have any ZAP created, go " . "<a href='%s' target='_blank'>%s</a>" . ". Remember to choose Webhooks by Zapier as Trigger App.", 'quiz-maker') ) , "https://zapier.com/app/editor/", "here"); ?>
                                    </blockquote>
                                    <blockquote>
                                        <?php echo esc_html__("We will send you all data from quiz information form with \"AysQuiz\" key by POST method", 'quiz-maker'); ?>
                                    </blockquote>
                                    <a href="https://ays-pro.com/wordpress/quiz-maker?utm_source=dashboard&utm_medium=quiz-free&utm_campaign=zapier-integration-menu-<?php echo esc_attr( AYS_QUIZ_UTM_VERSION ); ?>" target="_blank" class="ays-quiz-new-upgrade-button-link">
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
                        </fieldset> <!-- Zapier -->
                        <hr/>
                        <fieldset>
                            <legend>
                                <img class="ays_integration_logo" src="<?php echo esc_url( AYS_QUIZ_ADMIN_URL ); ?>/images/integrations/activecampaign_logo.png" alt="">
                                <h5><?php echo esc_html__('ActiveCampaign','quiz-maker')?></h5>
                            </legend>
                            <div class="form-group row" style="padding:0;margin:0;">
                                <div class="col-sm-12 only_pro" style="padding:20px;">
                                    <div class="pro_features" style="justify-content:flex-end;">

                                    </div>
                                    <div class="form-group row">
                                        <div class="col-sm-3">
                                            <label for="ays_active_camp_url">
                                                <?php echo esc_html__('API Access URL', 'quiz-maker'); ?>
                                            </label>
                                        </div>
                                        <div class="col-sm-9">
                                            <input type="text" class="ays-text-input" id="ays_active_camp_url" >
                                        </div>
                                    </div>
                                    <hr/>
                                    <div class="form-group row" aria-describedby="aaa">
                                        <div class="col-sm-3">
                                            <label for="ays_active_camp_api_key">
                                                <?php esc_html__('API Access Key', 'quiz-maker'); ?>
                                            </label>
                                        </div>
                                        <div class="col-sm-9">
                                            <input type="text" class="ays-text-input" id="ays_active_camp_api_key">
                                        </div>
                                    </div>
                                    <blockquote>
                                        <?php echo esc_html__("Your API URL and Key can be found in your account on the My Settings page under the \"Developer\" tab", 'quiz-maker'); ?>
                                    </blockquote>
                                    <a href="https://ays-pro.com/wordpress/quiz-maker?utm_source=dashboard&utm_medium=quiz-free&utm_campaign=activecampaign-integration-menu-<?php echo esc_attr( AYS_QUIZ_UTM_VERSION ); ?>" target="_blank" class="ays-quiz-new-upgrade-button-link">
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
                        </fieldset> <!-- ActiveCampaign -->
                        <hr/>
                        <fieldset>
                            <legend>
                                <img class="ays_integration_logo" src="<?php echo esc_url(AYS_QUIZ_ADMIN_URL); ?>/images/integrations/slack_logo.png" alt="">
                                <h5><?php echo esc_html__('Slack','quiz-maker')?></h5>
                            </legend>
                            <div class="form-group row" style="padding:0;margin:0;">
                                <div class="col-sm-12 only_pro" style="padding:20px;">
                                    <div class="pro_features" style="justify-content:flex-end;">

                                    </div>
                                    <div class="form-group row">
                                        <div class="col-sm-3">
                                            <label for="ays_slack_client">
                                                <?php echo esc_html__('App Client ID', 'quiz-maker') ?>
                                            </label>
                                        </div>
                                        <div class="col-sm-9">
                                            <input type="text" class="ays-text-input" id="ays_slack_client">
                                        </div>
                                    </div>
                                    <hr/>
                                    <div class="form-group row">
                                        <div class="col-sm-3">
                                            <label for="ays_slack_oauth">
                                                <?php echo esc_html__('Slack Authorization', 'quiz-maker') ?>
                                            </label>
                                        </div>
                                        <div class="col-sm-9">
                                            <button type="button" id="slackOAuth2" class="btn btn-outline-secondary disabled">
                                                <?php echo esc_html__("Authorize", 'quiz-maker') ?>
                                            </button>
                                        </div>
                                    </div>
                                    <hr/>
                                    <div class="form-group row">
                                        <div class="col-sm-3">
                                            <label for="ays_slack_secret">
                                                <?php echo esc_html__('App Client Secret', 'quiz-maker') ?>
                                            </label>
                                        </div>
                                        <div class="col-sm-9">
                                            <input type="text" class="ays-text-input" id="ays_slack_secret" >
                                        </div>
                                    </div>
                                    <hr/>
                                    <div class="form-group row">
                                        <div class="col-sm-3">
                                            <label for="ays_slack_oauth">
                                                <?php echo esc_html__('App Access Token', 'quiz-maker') ?>
                                            </label>
                                        </div>
                                        <div class="col-sm-9">
                                            <button type="button" class="btn btn-outline-secondary disabled">
                                                <?php echo esc_html__("Need Authorization", 'quiz-maker') ?>
                                            </button>
                                            <input type="hidden" id="ays_slack_token">
                                        </div>
                                    </div>
                                    <blockquote>
                                        <?php echo esc_html__("You can get your App Client ID and Client Secret from your App's the Basic Information page", 'quiz-maker'); ?>
                                    </blockquote>
                                    <a href="https://ays-pro.com/wordpress/quiz-maker?utm_source=dashboard&utm_medium=quiz-free&utm_campaign=slack-integration-menu-<?php echo esc_attr( AYS_QUIZ_UTM_VERSION ); ?>" target="_blank" class="ays-quiz-new-upgrade-button-link">
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
                        </fieldset> <!-- Slack -->
                        <hr/>
                        <!-- _________________________GOOGLE SHEETS START____________________ -->
                        <fieldset>
                            <legend>
                                <img class="ays_integration_logo" src="<?php echo esc_url( AYS_QUIZ_ADMIN_URL ); ?>/images/integrations/sheets_logo.png" alt="">
                                <h5><?php echo esc_html__('Google Sheets','quiz-maker')?></h5>
                            </legend>
                            <div class="form-group row" style="padding:0;margin:0;">
                                <div class="col-sm-12 only_pro" style="padding:20px;">
                                    <div class="pro_features" style="justify-content:flex-end;">

                                    </div>
                                    <div class="form-group row">
                                        <div class="col-sm-12">
                                            <div class="form-group row" aria-describedby="aaa">
                                                <div class="col-sm-3">
                                                    <button type="button" class="btn btn-info disabled" data-original-title="Google Integration Setup Instructions" disabled style="color: #fff;"><?php echo esc_html__('Instructions', 'quiz-maker'); ?></button>
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <div class="col-sm-3">
                                                    <label for="ays_google_client">
                                                        <?php echo esc_html__('Google Client ID', 'quiz-maker') ?>
                                                    </label>
                                                </div>
                                                <div class="col-sm-9">
                                                    <input type="text" class="ays-text-input" id="ays_google_client" value="" >
                                                </div>
                                            </div>
                                            <hr/>
                                            <div class="form-group row">
                                                <div class="col-sm-3">
                                                    <label for="ays_google_secret">
                                                        <?php echo esc_html__('Google Client Secret', 'quiz-maker') ?>
                                                    </label>
                                                </div>
                                                <div class="col-sm-9">
                                                    <input type="text" class="ays-text-input" id="ays_google_secret" value="">
                                                    <input type="hidden" id="ays_google_redirect" value="">
                                                </div>
                                            </div>
                                            <hr/>
                                            <div class="form-group row">
                                                <div class="col-sm-3"></div>
                                                <div class="col-sm-9">
                                                    <button type="button" class="btn btn-outline-info disabled" disabled>
                                                        <?php echo esc_html__("Connect", 'quiz-maker') ?>
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <a href="https://ays-pro.com/wordpress/quiz-maker?utm_source=dashboard&utm_medium=quiz-free&utm_campaign=google-sheets-integration-menu-<?php echo esc_attr( AYS_QUIZ_UTM_VERSION ); ?>" target="_blank" class="ays-quiz-new-upgrade-button-link">
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
                        </fieldset> <!-- Google Sheets -->
                        <!-- __________________________GOOGLE SHEETS END_____________________ -->
                        <hr/>
                        <fieldset>
                            <legend>
                                <img class="ays_integration_logo" src="<?php echo esc_url(AYS_QUIZ_ADMIN_URL); ?>/images/integrations/mad-mimi-logo.png" alt="">
                                <h5><?php echo esc_html__('Mad Mimi','quiz-maker')?></h5>
                            </legend>
                            <div class="form-group row" style="padding:0;margin:0;">
                                <div class="col-sm-12 only_pro" style="padding:20px;">
                                    <div class="pro_features" style="justify-content:flex-end;">

                                    </div>
                                    <div class="form-group row">
                                        <div class="col-sm-12">
                                            <div class="form-group row">
                                                <div class="col-sm-3">
                                                    <label><?php echo esc_html__('Username', 'quiz-maker'); ?></label>
                                                </div>
                                                <div class="col-sm-9">
                                                    <input type="text" class="ays-text-input" value="" >
                                                </div>
                                            </div>
                                            <hr/>
                                            <div class="form-group row">
                                                <div class="col-sm-3">
                                                    <label><?php echo esc_html__('API Key', 'quiz-maker'); ?></label>
                                                </div>
                                                <div class="col-sm-9">
                                                    <input type="text" class="ays-text-input" value="" >
                                                </div>
                                            </div>
                                            <blockquote>
                                                <?php echo sprintf( wp_kses_post( __( "You can get your API key from your %s Account. %s", 'quiz-maker' ) ), "<a href='https://madmimi.com/user/edit?account_info_tabs=account_info_personal' target='_blank'>", '</a>' ); ?>
                                            </blockquote>
                                        </div>
                                    </div>
                                    <a href="https://ays-pro.com/wordpress/quiz-maker?utm_source=dashboard&utm_medium=quiz-free&utm_campaign=mad-mimi-integration-menu-<?php echo esc_attr( AYS_QUIZ_UTM_VERSION ); ?>" target="_blank" class="ays-quiz-new-upgrade-button-link">
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
                        </fieldset> <!-- Mad Mimi -->
                        <hr/>
                        <fieldset>
                            <legend>
                                <img class="ays_integration_logo" src="<?php echo esc_url( AYS_QUIZ_ADMIN_URL ); ?>/images/integrations/convertkit_logo.png" alt="">
                                <h5><?php echo esc_html__('ConvertKit','quiz-maker')?></h5>
                            </legend>
                            <div class="form-group row" style="padding:0;margin:0;">
                                <div class="col-sm-12 only_pro" style="padding:20px;">
                                    <div class="pro_features" style="justify-content:flex-end;">

                                    </div>
                                    <div class="form-group row">
                                        <div class="col-sm-12">
                                            <div class="form-group row">
                                                <div class="col-sm-3">
                                                    <label><?php echo esc_html__('API Key', 'quiz-maker'); ?></label>
                                                </div>
                                                <div class="col-sm-9">
                                                    <input type="text" class="ays-text-input" value="">
                                                </div>
                                            </div>
                                            <blockquote>
                                                <?php echo sprintf( wp_kses_post( __( "You can get your API key from your %s Account. %s", 'quiz-maker' ) ), "<a href='https://app.convertkit.com/account/edit' target='_blank'>", '</a>' ); ?>
                                            </blockquote>
                                        </div>
                                    </div>
                                    <a href="https://ays-pro.com/wordpress/quiz-maker?utm_source=dashboard&utm_medium=quiz-free&utm_campaign=convertkit-integration-menu-<?php echo esc_attr( AYS_QUIZ_UTM_VERSION ); ?>" target="_blank" class="ays-quiz-new-upgrade-button-link">
                                        <div class="ays-quiz-new-upgrade-button-box">
                                            <div>
                                                <img src="<?php echo esc_url( AYS_QUIZ_ADMIN_URL .'/images/icons/locked_24x24.svg' ); ?>">
                                                <img src="<?php echo esc_url( AYS_QUIZ_ADMIN_URL.'/images/icons/unlocked_24x24.svg' ); ?>" class="ays-quiz-new-upgrade-button-hover">
                                            </div>
                                            <div class="ays-quiz-new-upgrade-button"><?php echo esc_html__("Upgrade", "quiz-maker"); ?></div>
                                        </div>
                                    </a>
                                </div>
                            </div>
                        </fieldset> <!-- ConvertKit -->
                        <hr/>
                        <fieldset>
                            <legend>
                                <img class="ays_integration_logo" src="<?php echo esc_url( AYS_QUIZ_ADMIN_URL ); ?>/images/integrations/get_response.png" alt="">
                                <h5><?php echo esc_html__('GetResponse','quiz-maker')?></h5>
                            </legend>
                            <div class="form-group row" style="padding:0;margin:0;">
                                <div class="col-sm-12 only_pro" style="padding:20px;">
                                    <div class="pro_features" style="justify-content:flex-end;">

                                    </div>
                                    <div class="form-group row">
                                        <div class="col-sm-12">
                                            <div class="form-group row">
                                                <div class="col-sm-3">
                                                    <label for="ays_quiz_getresponse_api_key"><?php echo esc_html__('GetResponse API Key', 'quiz-maker'); ?></label>
                                                </div>
                                                <div class="col-sm-9">
                                                    <input type="text" class="ays-text-input" id="ays_quiz_getresponse_api_key" name="ays_quiz_getresponse_api_key" value="" >
                                                </div>
                                            </div>
                                            <blockquote>
                                                <?php echo sprintf( wp_kses_post( __( "You can get your API key from your %s Account. %s", 'quiz-maker' ) ), "<a href='https://app.getresponse.com/api' target='_blank'>", '</a>' ); ?>
                                            </blockquote>
                                            <blockquote>
                                                <?php echo esc_html__( "For security reasons, unused API keys expire after 90 days. When that happens, you’ll need to generate a new key.", 'quiz-maker' ); ?>
                                            </blockquote>
                                        </div>
                                    </div>
                                    <a href="https://ays-pro.com/wordpress/quiz-maker?utm_source=dashboard&utm_medium=quiz-free&utm_campaign=getresponse-integration-menu-<?php echo esc_attr( AYS_QUIZ_UTM_VERSION ); ?>" target="_blank" class="ays-quiz-new-upgrade-button-link">
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
                        </fieldset> <!-- GetResponse -->
                    </div>
                </div>
            </div>
            <hr/>
            <div style="padding:15px 0px;">
            <?php
                // wp_nonce_field('settings_action', 'settings_action');
                // $other_attributes = array();
                // submit_button( esc_html__('Save changes', 'quiz-maker'), 'primary ays-quiz-loader-banner', 'ays_submit', true, $other_attributes);
                // echo wp_kses_post($loader_iamge);
            ?>
            </div>
        </form>
    </div>
</div>
