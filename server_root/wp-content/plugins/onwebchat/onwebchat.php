<?php

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

/*
Plugin Name: onWebChat - Live Chat & AI Chatbot
Plugin URI: https://www.onwebchat.com/wordpress-live-chat-plugin.php
Description: Empower visitors with real-time human chat and integrated AI chatbots. onWebChat Live Chat delivers instant responses, smoother interactions, and 24/7 availability - improving engagement, satisfaction, and conversions.
Author: onWebChat
Version: 3.4.1
Author URI: https://www.onwebchat.com
*/


/* ---------------------------------------------------- *
* Create Settings Page
* ---------------------------------------------------- */


define('ONWEBCHAT_SMALL_LOGO', plugins_url( 'images/onwebchat-logo.png' , __FILE__ ));
define('ONWEBCHAT_SERVER_URL','https://www.onwebchat.com/get-chatid.php');


// add styles
add_action( 'admin_enqueue_scripts', 'register_plugin_styles' );

// call function on hook admin_menu
add_action('admin_menu','onwebchat_setup_menu');

// create login error notice
add_action( 'admin_notices', 'onwebchat_login_error' );

// define the settings
add_action('admin_init','onwebchat_register_setttings');


/* ---------------------------------------------------- *
* Display onWebChat on footer
* ---------------------------------------------------- */

// add widget plugin to the footer
add_action('wp_footer', 'onwebchat_add_script_at_footer');


/* ----------------------------------------------------- *
* Admin Page registration
* ----------------------------------------------------- */

function onwebchat_setup_menu() {
    add_menu_page( 'onWebChat Settings', 'onWebChat', 'administrator', 'onwebchat_settings', 'onwebchat_init_menu_page', ONWEBCHAT_SMALL_LOGO);
}


/* ------------------------------------------------------ *
* Admin Page Display Function
* ------------------------------------------------------ */

function onwebchat_init_menu_page() {


    $options = get_option('onwebchat_plugin_option');
    $chatId = $options['text_string'];

    $isConnected = false;
    if($chatId !="") {
        $isConnected = true;
    }

    $isSecondPage = false;


    /*****************************************************************
     * Ask server for chatId
     *****************************************************************/
    if ( isset( $_POST["action"] ) && $_POST["action"] == "login" ) {


        // the following lines are added to verify a correct security nonce(token) by using "wp_verify_nonce()"
        if (! isset($_POST['_wpnonce'])
         || ! wp_verify_nonce( $_POST['_wpnonce'], 'on_web_chat_nonce')){
            print 'Sorry, your nonce did not verify.';
            exit;
         }

        $options = get_option('onwebchat_plugin_option');
        $chatId = $options['text_string'];
        //****************************

        // get username, password and chatId

        
        $userName = isset($_POST["onWebChatUser"]) ? sanitize_text_field($_POST["onWebChatUser"]) : '';
        $userPass = isset($_POST["onWebChatPass"]) ? sanitize_text_field($_POST["onWebChatPass"]) : '';

        $chatId = isset($_POST["chatId"]) ? sanitize_text_field($_POST["chatId"]) : '';

        $pagesSelect = isset($_POST["pages-select"]) ? absint($_POST["pages-select"]) : 1;
        $showOnPages = isset($_POST["showonpages"]) ? sanitize_text_field($_POST["showonpages"]) : '';
        $hideOnPages = isset($_POST["hideonpages"]) ? sanitize_text_field($_POST["hideonpages"]) : '';
        $onwebchatApi = isset($_POST["onwebchat-api"]) ? wp_kses_post($_POST["onwebchat-api"]) : '';



        if(isChatIdValid($chatId)) {

            if ( isset($_POST["isSecondPage"])){
                $isSecondPage = $_POST["isSecondPage"];
            }

            //----------- update chatId option ------------
            $my_options = get_option('onwebchat_plugin_option');

            $my_options['text_string'] = $chatId;

            //Update entire array
            update_option('onwebchat_plugin_option', $my_options);
            //-----------------------------------------------

            // remove user email from db
            if(!$isSecondPage) {
                update_option( 'onwebchat_plugin_option_user', '');
                update_option( 'onwebchat_plugin_option_pages_select', 1);

            } else {

                update_option( 'onwebchat_plugin_option_pages_select', $pagesSelect);

                if ($pagesSelect==2){
                    update_option( 'onwebchat_plugin_option_show_pages', $showOnPages);
                } else if ($pagesSelect==3){
                    update_option( 'onwebchat_plugin_option_hide_pages', $hideOnPages);
                }

                //TODO update textarea
                update_option( 'onwebchat_plugin_option_api_code', $onwebchatApi);
            }

            // move to next page
            print('<script>window.location.href="admin.php?page=onwebchat_settings"</script>');

        } else {

            // save username at options
            update_option( 'onwebchat_plugin_option_user', $userName );

            // ask server for chatId
            $response = wp_remote_post(ONWEBCHAT_SERVER_URL, array(
                'method' => 'POST',
                'timeout' => 45,
                'redirection' => 5,
                'httpversion' => '1.0',
                'blocking' => true,
                'headers' => array(),
                'body' => array( 'email' => $userName, 'pass' => $userPass ),
                'cookies' => array()
            ));


       

            if ( is_wp_error( $response ) ) {
                $error_message = $response->get_error_message();
              

            } else {

            
                // If no chatId returned
                if($response['body'] == '') {
                    // display an error for wrong credentials
                     onwebchat_login_error(true);
                     
                } else {

                    //----------- update chatId
                    $my_options = get_option('onwebchat_plugin_option');

                    $my_options['text_string'] = $response['body'];

                    update_option('onwebchat_plugin_option', $my_options);
                    update_option( 'onwebchat_plugin_option_pages_select', 1);
                    //--------------------------------------------

                    // move to next page
                    print('<script>window.location.href="admin.php?page=onwebchat_settings"</script>');
                }
            }
        }

        $m_option_hide = false;
        if(isset($_POST['onwebchat_plugin_option_hide'])) {
            $m_option_hide = $_POST['onwebchat_plugin_option_hide'];
        }

        update_option('onwebchat_plugin_option_hide', $m_option_hide); //an einai stin proti selida tote to kanei keno (opote by default den einai epilegmeno)

    }

    //********************************************************************


    /*********************************************************************
     * Disconnect account
     *********************************************************************/
    if( isset($_GET["action"]) && $_GET["action"] == "deactivate" && $isConnected) {
        $isConnected = false;

        $my_options = get_option('onwebchat_plugin_option');

        $my_options['text_string'] = '';

        update_option('onwebchat_plugin_option', $my_options);
        update_option( 'onwebchat_plugin_option_pages_select', 1);

        $chatIdOption = get_option('onwebchat_plugin_option');
        $chatId = $chatIdOption['text_string'];

        print('<script>window.location.href="admin.php?page=onwebchat_settings"</script>');

    }
    //***********************************************************************



    ?>
    <div>



        
        <h1 style="font-weight: 400;margin-top:34px">onWebChat <?php ($isConnected == true) ? print 'Settings' :  print 'Activation'; ?> </h1>

        <form action="admin.php?page=onwebchat_settings" method="post">
            <input type="hidden" name="action" value="login">
            <?php

            //create nonce(token)
            wp_nonce_field('on_web_chat_nonce');

            // Login Page
            if($isConnected != true) {

                $chatId = get_option( 'onwebchat_plugin_option' );
                $chatId = $chatId['text_string'];
                ?>

                <hr class="small-hr2">

                <h3 class="header-1">Connect with your onWebChat account</h3>



                <div class="username-div">
                    <!-- sanitize user-provided parameter -->
                    <strong>Email: </strong><input class="username-text-field" type="text" name="onWebChatUser" value="<?php echo esc_attr(get_option( 'onwebchat_plugin_option_user' )); ?>"/>
                </div>

                <div class="password-div">
                    <strong>Password: </strong><input class="password-text-field" type="password" name="onWebChatPass" value="<?php echo get_option( 'onWebChatPass' ); ?>"/>
                </div>


                <div style="width: 560px; height: 10px; border-bottom: 1px solid #bfbfbf; text-align: center; margin-top:30px">
					<span class="header-or" style="color:#656565;font-size: 21px; background-color: #f1f1f1; padding: 0 10px;">
                    
						OR
                    
					</span>
                </div>


                <h3 class="header-2">Paste your onWebChat Chat Id</h3>
                <div class="password-div">
                    <strong>Chat Id:</strong> <input class="chatid-text-field" type="text" name="chatId" value="<?php echo $chatId; ?>"/>
                </div>

                <hr class="small-hr2">

                <div class="new-account-link">
                    Don’t have an onWebChat account yet? <a href="https://www.onwebchat.com/signup.php" target="_blank">Create one here</a> to get started.
                </div>

                <?php

                    $html = '<input class="button action" style="margin-left: 230px;" type="submit" value="Activate"/>';
                    echo $html;
            }

            // 2nd Page
            else {
                $options = get_option('onwebchat_plugin_option_user');

                // display user email
                if($options!=''){
                    //sanitize user-provided parameter
                    $email = esc_html($options);
                    $html = '<br><h3 class="header-1-p2">Activated for onWebChat account: </h3>';
                    $html .= "<strong class='account-id'>$email</strong> ";
                }

                // display chatId
                else {
                    $chatId = get_option( 'onwebchat_plugin_option' );
                    $chatId = $chatId['text_string'];
                    $html = '<br><h3 class="header-1-p2">Activated for onWebChat Chat Id: </h3>';
                    $html .= "<strong class='account-id'>$chatId</strong> ";
                }
                $html .= ' <a href="admin.php?page=onwebchat_settings&amp;action=deactivate">Unlink account</a>';
                //$html .= ' <div class="admin-login-info"> <span>*</span> To connect to onWebChat dashboard click <a target="_blank" href="https://www.onwebchat.com/login.php">here</a> </div>';
                echo $html;

                $options = get_option('onwebchat_plugin_option_hide');

                $chatId = get_option( 'onwebchat_plugin_option' );
                $chatId = $chatId['text_string'];

                // get correct value
                $onwebchatApi = get_option('onwebchat_plugin_option_api_code');
                $onwebchatApi = str_replace('\\','',$onwebchatApi);
                ?>
                

                <br><br><br>

                <div class="hide-div">
                    <select id="pages-select" name="pages-select" onchange="onwc_select_change()">
                        <option value="1" <?php selected( get_option('onwebchat_plugin_option_pages_select'), 1 ); ?>>Show the chat widget on all pages</option>
                        <option value="2" <?php selected( get_option('onwebchat_plugin_option_pages_select'), 2 ); ?>>Show the chat widget only on selected pages:</option>
                        <option value="3" <?php selected( get_option('onwebchat_plugin_option_pages_select'), 3 ); ?>>Hide the chat widget on selected pages:</option>
                        <option value="4" <?php selected( get_option('onwebchat_plugin_option_pages_select'), 4 ); ?>>Hide the chat widget on all pages</option>
                    </select>
                </div>

                <div id="onwc_show_on_pages_div" style="display:none">
                    <input id="showonpages" name="showonpages" class="showhidepages" type="text" value="<?php echo esc_attr(get_option( 'onwebchat_plugin_option_show_pages' )); ?>" /><a href="#" style="text-decoration: none;" onmouseover="document.getElementById('help').style.visibility = 'visible'"; ONMOUSEOUT="document.getElementById('help').style.visibility = 'hidden'"><strong><font size="4" face="Arial"> ? </font></strong></a>
                </div>
                <div id="onwc_hide_on_pages_div" style="display:none">
                    <input id="hideonpages" name="hideonpages" class="showhidepages" type="text" value="<?php echo esc_attr(get_option( 'onwebchat_plugin_option_hide_pages' )); ?>" /><a href="#" style="text-decoration: none;" onmouseover="document.getElementById('help').style.visibility = 'visible'"; ONMOUSEOUT="document.getElementById('help').style.visibility = 'hidden'"><strong><font size="4" face="Arial"> ? </font></strong></a>
                </div>

                <div id="help">
                    <span>Add multiple pages by separating them with a space. You can enter a full URL or just a part of it. Example:  &nbsp; index price contact.php blog/</span>
                </div>

                <br>
                <h3 class="header-1-p2">Enter your custom onWebChat JavaScript API code below</h3>
                <div class="chatid-div">
                    <!-- <strong>onWebChat API:</strong>
                    <br> -->
                    <!-- sanitize user-provided parameter  -->
                    <textarea class="chatid-text-field" style="margin-left: 0px;" rows="10" name="onwebchat-api"><?php echo esc_html($onwebchatApi); ?></textarea>
                    <br>
                    <br>
                </div>

                <div>
                    <br /><br />
                </div>

                <!-- hiden fields -->
                <input class="chatid-text-field-hide" type="text" name="chatId" value="<?php echo esc_attr($chatId); ?>"/>
                <input class="chatid-text-field-hide" type="text" name="onWebChatUser" value="<?php echo esc_attr(get_option( 'onwebchat_plugin_option_user' )); ?>"/>
                <input class="chatid-text-field-hide" type="text" name="isSecondPage" value="1"/>

                <div class="new-account-link">
                    <a target="_blank" href="https://www.onwebchat.com/login.php">Access your onWebChat Dashboard</a>
                </div>
                
                <?php
                    // Display the Save Button
                    $html = '<input class="button action" style="margin-left: 230px;" type="submit" value="Save Changes"/>';
                    echo $html;

            }

            
            ?>
        </form>
    </div>

    <script type="text/javascript">


        function onwc_select_change() {
            var e = document.getElementById("pages-select");
            if (!e) {
               // console.warn("Select element not found");
                return;
            }
            var selected = e.options[e.selectedIndex].value;
            if (selected == 1){
                document.getElementById("onwc_show_on_pages_div").style.display = "none";
                document.getElementById("onwc_hide_on_pages_div").style.display = "none";
            } else if (selected == 2){
                document.getElementById("onwc_show_on_pages_div").style.display = "block";
                document.getElementById("onwc_hide_on_pages_div").style.display = "none";
            } else if (selected == 3){
                document.getElementById("onwc_show_on_pages_div").style.display = "none";
                document.getElementById("onwc_hide_on_pages_div").style.display = "block";
            } else if (selected == 4){
                document.getElementById("onwc_show_on_pages_div").style.display = "none";
                document.getElementById("onwc_hide_on_pages_div").style.display = "none";
            }

        }
        document.addEventListener('DOMContentLoaded', function() {
            onwc_select_change();
        }, false);
    </script>
    <?php
}


/* ----------------------------------------------------- *
* Admin Page Styles Registration
* ----------------------------------------------------- */

function register_plugin_styles() {
    wp_register_style( 'onwebchat', plugins_url( 'onwebchat/css/onwebchat.css' ) );
    wp_enqueue_style( 'onwebchat' );
}


/* ----------------------------------------------------- *
* Tabs Settings Registration
* ----------------------------------------------------- */

function onwebchat_register_setttings() {

    /******************** Account Tab ************************/
    //* register the Chat Id
    register_setting( 'onwebchat_plugin_option', 'onwebchat_plugin_option');

    //* register the username
    register_setting( 'onwebchat_plugin_option', 'onwebchat_plugin_option_user');

    //* register the hide (checkbox)
    register_setting( 'onwebchat_plugin_option_checkbox', 'onwebchat_plugin_option_hide');

    //TODO api code
    //* register the api code
    register_setting( 'onwebchat_plugin_option', 'onwebchat_plugin_option_api_code');
}


/* ----------------------------------------------------- *
* onWebChat Display Function
* ----------------------------------------------------- */

// print chat widget code
function onwebchat_add_script_at_footer() {

    $options = get_option('onwebchat_plugin_option');
    $chatId = $options['text_string'];
    //$hideWidget = get_option('onwebchat_plugin_option_hide');
    $hideWidget = false;

    $widgetCode = "<script type='text/javascript'>var onWebChat={ar:[], set: function(a,b){if (typeof onWebChat_==='undefined'){this.ar.push([a,b]);}else{onWebChat_.set(a,b);}},get:function(a){return(onWebChat_.get(a));},w:(function(){ var ga=document.createElement('script'); ga.type = 'text/javascript';ga.async=1;ga.src='//www.onwebchat.com/clientchat/$chatId';var s=document.getElementsByTagName('script')[0];s.parentNode.insertBefore(ga,s);})()}</script>";

    $apiCode = get_option('onwebchat_plugin_option_api_code');

    $apiCode = str_replace('\\','',$apiCode);

    $apiCode =  "<script type='text/javascript'>$apiCode</script>";

    $widgetCode .= $apiCode;


    //check if user is logged if yes get username, email
    if ( is_user_logged_in() ) {

        global $current_user;
        get_currentuserinfo();

        // get user name
        $onwebchat_user = $current_user->user_login;

        // get user email
        $onwebchat_email = $current_user->user_email;

        //add api info
        if ($onwebchat_user != null && $onwebchat_user != "" && $onwebchat_user != "guest" && $onwebchat_user != "Guest") {
            if ($onwebchat_email != null && $onwebchat_email != ""){
                $widgetCode = $widgetCode."<script type='text/javascript'>onWebChat.set('name','$onwebchat_user');
                onWebChat.set('email','$onwebchat_email'); </script>";
            } else {
                $widgetCode = $widgetCode."<script type='text/javascript'>onWebChat.set('name','$onwebchat_user');</script>";
            }
        }
    }

    $currentPage = substr( strtolower($_SERVER['SERVER_PROTOCOL']), 0, strpos( strtolower($_SERVER['SERVER_PROTOCOL']), "/") );
    $currentPage .= ( empty($_SERVER['HTTPS']) ? NULL : ( ($_SERVER['HTTPS'] == "on") ? "s" : NULL) );
    $currentPage .= "://" . $_SERVER['SERVER_NAME'];
    $currentPage .= ( $_SERVER['SERVER_PORT'] == 80 ? "" : ":".$_SERVER['SERVER_PORT'] );
    $currentPage .= $_SERVER['REQUEST_URI'];

    $pagesSelect = get_option('onwebchat_plugin_option_pages_select');
    $showPages = get_option('onwebchat_plugin_option_show_pages');
    $showPages = explode(' ', $showPages);
    $hidePages = get_option('onwebchat_plugin_option_hide_pages');
    $hidePages = explode(' ', $hidePages);

    $showSelect=true;
    if ($pagesSelect==1){

    } else if ($pagesSelect==2){
        $showSelect=false;
        foreach ($showPages as $page){
            if ($page !== "") {
                if (strpos($currentPage, $page) !== false) {
                    $showSelect = true;
                }
            }
        }
    } else if ($pagesSelect==3){
        $showSelect=true;
        foreach ($hidePages as $page){
            if ($page !== "") {
                if (strpos($currentPage, $page) !== false) {
                    $showSelect = false;
                }
            }
        }
    } else if($pagesSelect == 4) {
        //hide chat widget from all pages
        $hideWidget = true;
    }

    if(!$hideWidget && $showSelect) {
        echo $widgetCode;
    }

}

/************************************************************************
 * display error function
 ***********************************************************************/
function onwebchat_login_error($contition = false) {
    
    

    if($contition) {
        ?>

        <div class="error">
            <p><strong>Invalid credentials!</strong> Please make sure you have entered the correct email and password, <strong>OR</strong> provide a valid Chat ID</p>
        </div>
        <?php
    }
    else {
        //display nothing
    }
}

/************************************************************************
 * Validate Chat Id function
 ***********************************************************************/
function isChatIdValid($input) {
    return preg_match('/^[a-f0-9]{32,40}+[\\/]?+[0-9]{0,2}+[\\/]?+[0-9]{0,2}$/i', $input);
}

// omit the closing php tag ? >  for avoid issues
