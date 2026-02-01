<?php
/**
 * Provide a dashboard view for the plugin
 *
 * This file is used to markup the public-facing aspects of the plugin.
 *
 * @link       http://logichunt.com
 * @since      1.0.0
 *
 * @package    logosliderwpcarousel
 * @subpackage logosliderwpcarousel/admin/partials
 */
if (!defined('WPINC')) {
    die;
}
?>

<!-- This file should primarily consist of HTML with a little bit of PHP. -->

<div class="wrap">
    <div id="icon-options-general" class="icon32"></div>
    <h2><?php _e('Logo Slider WP: Usage & Help', 'logoslider-domain'); ?></h2>

    <div id="poststuff">

        <div id="post-body" class="metabox-holder columns-2">




            <!-- main content -->
            <div id="post-body-content">
                <div class="meta-box-sortables ui-sortable">
                    <?php

                    /*
                     * Add Header File
                     */
                    include_once plugin_dir_path( __FILE__ ) . '/shortcode_meta_display/__meta_fields_lsp_shortcodes_header.php';

                    ?>
                    <div class="postbox">
                        <div class="inside lgx-settings-inside">
                        <div style="margin-left: -5%;">
                                <?php

                                /*
                                 * Add Get Pro blocks
                                 */
                                include plugin_dir_path( __FILE__ ) . '/shortcode_meta_display/__meta_fields_lsp_shortcodes_get_pro.php';

                                ?>
                            </div>
                            <hr>
                            <hr>

                        
                            <h3 class="clear"><?php _e('Quick Usage Guidelines', 'logo-slider-wp'); ?></h3>
                            <h4 style="margin: 25px 0 15px 0;">Thank you for downloading and activating the plugin. It's incredibly easy to configure and use. Simply follow the steps below:</h4>
                            <ol>
                                <li><?php _e('First, go to "Add New Logo" to add brand logos.', 'logo-slider-wp'); ?></li>
                                <li><?php _e('Provide the company name, URL, brand image, description, and tooltip text.', 'logo-slider-wp'); ?></li>
                                <li><?php _e('If you need to filter logo items or want to display multiple showcases, add categories and assign logo items to the desired category based on your requirements.', 'logo-slider-wp'); ?></li>
                                <li><?php _e('Now, go to "Shortcode Generator" to create your desired logo showcase.', 'logo-slider-wp'); ?></li>
                                <li><?php _e('Select either carousel or grid layout and configure your shortcode according to your needs.', 'logo-slider-wp'); ?></li>
                                <li><?php _e('Carefully read the description/instruction for each option at the bottom of the page.', 'logo-slider-wp'); ?></li>
                                <li><?php _e('Finally, use the generated shortcode in any post, page, widget, or theme to display the Logo Slider WP.', 'logo-slider-wp'); ?></li>
                            </ol>
                            <ul>
                                <li> <strong>*Note: Easily sort the order of logo items using the premium drag and drop feature.</strong></li>
                                <li> <strong>*Note: It is recommended to use brand logos with the same dimensions (height and width) for a better visual appeal.</strong></li>
                            </ul>

                    
                            <p style="margin-top: 25px;">For detailed instructions, please refer to the user manual: <a class="button button-primary" href="https://docs.logichunt.com/logo-slider-wp" target="_blank">Documentation</a></p>

                            <br />
                            <br />
                            <hr>

                            <h3 class="clear"><?php _e('Upgrading to Premium Version without any data loss', 'logo-slider-wp'); ?></h3>
                            <h4 style="margin: 25px 0 15px 0;">Upgrading to Logo Slider Pro is a straightforward process. If you're currently using the Free version and wish to upgrade to the Premium version, please follow these steps:</h4>

                            <ol>
                                <li>Purchase the Pro version from the following link:  <a href="https://logichunt.com/product/wordpress-logo-slider/" target="_blank" ><span>Logo Slider Pro.</span></a></li>

                                <li>After completing the purchase, navigate to "My Account → Dashboard → Downloads" on the website. Also, you will receive a confirmation email with a download link.</li>
                                <li>Download the Pro version of the plugin.</li>
                                <li>Once the Pro version is downloaded, manually install it on your WordPress site. You can do this by going to "Plugins → Add New → Upload Plugin" and selecting the Pro version file you just downloaded.</li>
                                <li>After installing the Pro version, the Free version will be automatically deactivated.</li>
                                <li>You can now manage and create showcases using all the premium features included in Logo Slider Pro.</li>
                                <li>It's important to note that upgrading to the Pro version will not result in any data loss. All your previous showcase shortcodes will be carried over to the Pro version seamlessly.</li>

</ol>

                            <br />
                            <br />
                            <hr>
                            <hr>

                            <h3 class="clear"><?php _e('Get Support', 'logo-slider-wp'); ?></h3>
                            <p><strong>If you need further assistance, please visit our <a target="_blank" href="https://logichunt.com/support/">support page</a>. Our dedicated team is here to provide you with the best possible help and support.</strong></p>
                            
                            <br />
                            <br />
                            <hr>
                            <hr>
                            <div style="margin-left: -5%;">
                                <?php

                                /*
                                 * Add Get Pro blocks
                                 */
                                include plugin_dir_path( __FILE__ ) . '/shortcode_meta_display/__meta_fields_lsp_shortcodes_get_pro.php';

                                ?>
                            </div>
                            <hr>
                            <br />
                        </div> <!-- .inside -->
                    </div> <!-- .postbox -->
                </div> <!-- .meta-box-sortables .ui-sortable -->
            </div> <!-- post-body-content -->
            <?php
            include('sidebar.php');
            ?>

        </div> <!-- #post-body .metabox-holder .columns-2 -->

        <br class="clear">
    </div> <!-- #poststuff -->

</div> <!-- .wrap -->