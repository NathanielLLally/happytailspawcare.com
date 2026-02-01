<?php

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Enqueue WordPress media uploader for 'sltk' post type
 */
function sltk_media_files() {
    global $post;

    $type = isset($_GET['post_type'])
        ? sanitize_text_field( wp_unslash($_GET['post_type']) )
        : (isset($post) ? $post->post_type : '');

    if ($type === 'sltk') {
        wp_enqueue_media();
    }
}
add_action('admin_enqueue_scripts', 'sltk_media_files');


/**
 * Generic meta saving function
 */
function sltk_save_meta($post_id, $nonce_name, $nonce_action, $field_name, $meta_key, $sanitize = 'text') {

    if (!isset($_POST[$nonce_name])) return $post_id;

    $nonce = sanitize_text_field( wp_unslash($_POST[$nonce_name]) );
    if (!wp_verify_nonce($nonce, $nonce_action)) return $post_id;

    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) return $post_id;
    if (!current_user_can('edit_post', $post_id)) return $post_id;

    if (isset($_POST[$field_name])) {
        $value = wp_unslash($_POST[$field_name]);

        switch ($sanitize) {
            case 'int':
                $value = intval($value);
                break;
            case 'hex_color':
                $value = sanitize_hex_color($value);
                break;
            case 'yes_no':
                $value = ($value === 'yes') ? 'yes' : 'no';
                break;
            default:
                $value = sanitize_text_field($value);
        }
        update_post_meta($post_id, $meta_key, $value);
    }

    return $post_id;
}


/**
 * Generic input renderer
 */
function sltk_render_input($post, $args) {

    wp_nonce_field($args['nonce_action'], $args['nonce_name']);

    $value = get_post_meta($post->ID, $args['meta_key'], true);

    $path     = plugin_dir_url(dirname(__FILE__)) . $args['icon'];
    $infoPath = plugin_dir_url(dirname(__FILE__)) . '/assets/img/info.png';

    echo '<div style="display:flex;flex-direction:row;align-items:center;margin-bottom:10px;">';
    echo '<img style="width:30px;height:auto;object-fit:contain;" src="' . esc_url($path) . '" alt="">';
    echo '<label style="margin-left:10px;">' . esc_html($args['label']) . '</label>';

    switch ($args['type']) {

        case 'color':
            echo '<input type="color" name="' . esc_attr($args['field_name']) . '" value="' . esc_attr($value) . '">';
            break;

            case 'number':
                $min_attr  = isset($args['min'])  ? $args['min']  : '';
                $max_attr  = isset($args['max'])  ? $args['max']  : '';
                $step_attr = isset($args['step']) ? $args['step'] : '';
            
                echo '<input type="number" name="' . esc_attr($args['field_name']) . '" value="' . esc_attr($value) . '"';
            
                if ($min_attr !== '') echo ' min="' . esc_attr($min_attr) . '"';
                if ($max_attr !== '') echo ' max="' . esc_attr($max_attr) . '"';
                if ($step_attr !== '') echo ' step="' . esc_attr($step_attr) . '"';
            
                echo '>px';
                break;
            
            case 'range':
                $min_attr  = isset($args['min'])  ? $args['min']  : '';
                $max_attr  = isset($args['max'])  ? $args['max']  : '';
                $step_attr = isset($args['step']) ? $args['step'] : '';
            
                echo '<input type="range" name="' . esc_attr($args['field_name']) . '"';
            
                if ($min_attr !== '') echo ' min="' . esc_attr($min_attr) . '"';
                if ($max_attr !== '') echo ' max="' . esc_attr($max_attr) . '"';
                if ($step_attr !== '') echo ' step="' . esc_attr($step_attr) . '"';
            
                echo ' value="' . esc_attr($value) . '"';
                echo ' oninput="document.getElementById(\'' . esc_attr($args['field_name']) . '_value\').textContent = this.value + \' ms\';">';
            
                echo '<span id="' . esc_attr($args['field_name']) . '_value">' . esc_html($value) . ' ms</span>';
                break;
            

        case 'radio':
            foreach ($args['options'] as $opt_value => $opt_label) {
                echo '<input type="radio" 
                    name="' . esc_attr($args['field_name']) . '" 
                    value="' . esc_attr($opt_value) . '" ' . checked($value, $opt_value, false) . '>' 
                    . esc_html($opt_label) . ' ';
            }
            break;

        case 'select':
            echo '<select name="' . esc_attr($args['field_name']) . '">';
            foreach ($args['options'] as $opt_value => $opt_label) {
                echo '<option value="' . esc_attr($opt_value) . '" ' . selected($value, $opt_value, false) . '>' . esc_html($opt_label) . '</option>';
            }
            echo '</select>';
            break;
    }

    if (!empty($args['tooltip'])) {
        echo '<div class="tooltip" style="margin-left:20px;">
                <img style="width:30px;height:auto;cursor:help;" src="'.esc_url($infoPath).'" alt="info">
                <span class="tooltiptext">'.esc_html($args['tooltip']).'</span>
              </div>';
    }

    echo '</div>';
}



/* Background Color */
function sltk_change_color($post) {
    sltk_render_input($post, [
        'type' => 'color',
        'meta_key' => '_favorite_color',
        'field_name' => 'favcolor',
        'nonce_name' => 'sltk_nonce',
        'nonce_action' => 'sltk_save',
        'label' => 'Select background color:',
        'icon' => '/assets/img/background-color.png',
        'tooltip' => 'Change the slider’s background color.'
    ]);
}
add_action('save_post', function($post_id) {

    if (!isset($_POST['sltk_nonce'])) return $post_id;
    $nonce = sanitize_text_field( wp_unslash($_POST['sltk_nonce']) );
    if (!wp_verify_nonce($nonce, 'sltk_save')) return $post_id;

    if (isset($_POST['favcolor'])) {
        update_post_meta($post_id, '_favorite_color', sanitize_hex_color( wp_unslash($_POST['favcolor']) ));
    }

    return $post_id;
});


/* Height Slider */
function sltk_change_height($post) {
    sltk_render_input($post, [
        'type' => 'number',
        'meta_key' => '_custom_height',
        'field_name' => 'height',
        'nonce_name' => 'sltk_nonce_height',
        'nonce_action' => 'sltk_save_height',
        'label' => 'Slider height:',
        'icon' => '/assets/img/heightSlider.png',
        'tooltip' => 'Change the height of the slider.'
    ]);
}
add_action('save_post', function($post_id){
    return sltk_save_meta($post_id,'sltk_nonce_height','sltk_save_height','height','_custom_height','int');
});


/* Width Slider */
function sltk_change_width($post) {
    sltk_render_input($post, [
        'type' => 'number',
        'meta_key' => '_custom_width',
        'field_name' => 'width',
        'nonce_name' => 'sltk_nonce_width',
        'nonce_action' => 'sltk_save_width',
        'label' => 'Slider width:',
        'icon' => '/assets/img/widthSlider.png',
        'tooltip' => 'Change the width of the slider.'
    ]);
}
add_action('save_post', function($post_id){
    return sltk_save_meta($post_id,'sltk_nonce_width','sltk_save_width','width','_custom_width','int');
});


/* Height Pictures */
function sltk_change_height_pictures($post) {
    sltk_render_input($post, [
        'type' => 'number',
        'meta_key' => '_custom_height_Pictures',
        'field_name' => 'heightPictures',
        'nonce_name' => 'sltk_nonce_height_pictures',
        'nonce_action' => 'sltk_save_height_pictures',
        'label' => 'Select height Pictures:',
        'icon' => '/assets/img/height.png',
        'tooltip' => 'Change the height of the slider images.'
    ]);
}
add_action('save_post', function($post_id){
    return sltk_save_meta($post_id,'sltk_nonce_height_pictures','sltk_save_height_pictures','heightPictures','_custom_height_Pictures','int');
});


/* Width Pictures */
function sltk_change_width_pictures($post) {
    sltk_render_input($post, [
        'type' => 'number',
        'meta_key' => '_custom_width_Pictures',
        'field_name' => 'widthPictures',
        'nonce_name' => 'sltk_nonce_width_pictures',
        'nonce_action' => 'sltk_save_width_pictures',
        'label' => 'Select width Pictures:',
        'icon' => '/assets/img/width.png',
        'tooltip' => 'Change the width of the slider images.'
    ]);
}
add_action('save_post', function($post_id){
    return sltk_save_meta($post_id,'sltk_nonce_width_pictures','sltk_save_width_pictures','widthPictures','_custom_width_Pictures','int');
});


/* Padding */
function sltk_change_padding($post) {
    sltk_render_input($post, [
        'type' => 'number',
        'meta_key' => '_custom_padding',
        'field_name' => 'padding',
        'nonce_name' => 'sltk_nonce_padding',
        'nonce_action' => 'sltk_save_padding',
        'label' => 'Select Padding Slider:',
        'icon' => '/assets/img/padding.png',
        'tooltip' => 'Increase the size of the slider.'
    ]);
}
add_action('save_post', function($post_id){
    return sltk_save_meta($post_id,'sltk_nonce_padding','sltk_save_padding','padding','_custom_padding','int');
});


/* Speed */
function sltk_change_speed($post) {
    sltk_render_input($post, [
        'type' => 'range',
        'meta_key' => '_custom_speed',
        'field_name' => 'speed',
        'nonce_name' => 'sltk_nonce_speed',
        'nonce_action' => 'sltk_save_speed',
        'label' => 'Select Speed Slider:',
        'icon' => '/assets/img/speed.png',
        'min' => 0,
        'max' => 5000,
        'step' => 100,
        'tooltip' => 'Adjust the speed of the slider.'
    ]);
}
add_action('save_post', function($post_id){
    return sltk_save_meta($post_id,'sltk_nonce_speed','sltk_save_speed','speed','_custom_speed','int');
});


/* Gap */
function sltk_change_gap($post) {
    sltk_render_input($post, [
        'type' => 'number',
        'meta_key' => '_custom_gap',
        'field_name' => 'gap',
        'nonce_name' => 'sltk_nonce_gap',
        'nonce_action' => 'sltk_save_gap',
        'label' => 'Select Gap Pictures:',
        'icon' => '/assets/img/gap.png',
        'tooltip' => 'Adjust the spacing between the slider images.'
    ]);
}
add_action('save_post', function($post_id){
    return sltk_save_meta($post_id,'sltk_nonce_gap','sltk_save_gap','gap','_custom_gap','int');
});


/* Black and White */
function sltk_change_blackAndWhite($post) {
    sltk_render_input($post, [
        'type' => 'radio',
        'meta_key' => '_custom_blackAndWhite',
        'field_name' => 'blackAndWhite',
        'nonce_name' => 'sltk_nonce_blackAndWhite',
        'nonce_action' => 'sltk_save_blackAndWhite',
        'label' => 'Slider black and white (Yes/No):',
        'icon' => '/assets/img/black-white.png',
        'options' => ['yes'=>'✔','no'=>'✖'],
        'tooltip' => 'Change the slider color to black or white.'
    ]);
}
add_action('save_post', function($post_id){
    return sltk_save_meta($post_id,'sltk_nonce_blackAndWhite','sltk_save_blackAndWhite','blackAndWhite','_custom_blackAndWhite','yes_no');
});


/* Stop Slider */
function sltk_change_stopSlider($post) {
    sltk_render_input($post, [
        'type' => 'radio',
        'meta_key' => '_custom_stopSlider',
        'field_name' => 'stopSlider',
        'nonce_name' => 'sltk_nonce_stopSlider',
        'nonce_action' => 'sltk_save_stopSlider',
        'label' => 'Slider stop (Yes/No):',
        'icon' => '/assets/img/stop.png',
        'options' => ['yes'=>'✔','no'=>'✖'],
        'tooltip' => 'Pause the slider on hover.'
    ]);
}
add_action('save_post', function($post_id){
    return sltk_save_meta($post_id,'sltk_nonce_stopSlider','sltk_save_stopSlider','stopSlider','_custom_stopSlider','yes_no');
});


/* Slides Per View */
function sltk_change_slidesPerView($post) {
    sltk_render_input($post, [
        'type' => 'number',
        'meta_key' => '_custom_slidesPerView',
        'field_name' => 'slidesPerView',
        'nonce_name' => 'sltk_nonce_slidesPerView',
        'nonce_action' => 'sltk_save_slidesPerView',
        'label' => 'Select Number of pictures:',
        'icon' => '/assets/img/pictures.png',
        'min' => 1,
        'max' => 6,
        'tooltip' => 'Set the number of images visible while the slider rotates.'
    ]);
}
add_action('save_post', function($post_id){
    return sltk_save_meta($post_id,'sltk_nonce_slidesPerView','sltk_save_slidesPerView','slidesPerView','_custom_slidesPerView','int');
});


/* Center Slider */
function sltk_change_centerSlider($post) {
    sltk_render_input($post, [
        'type' => 'radio',
        'meta_key' => '_custom_centerSlider',
        'field_name' => 'centerSlider',
        'nonce_name' => 'sltk_nonce_centerSlider',
        'nonce_action' => 'sltk_save_centerSlider',
        'label' => 'Center Slider (Yes/No):',
        'icon' => '/assets/img/centrar.png',
        'options' => ['yes'=>'✔','no'=>'✖'],
        'tooltip' => 'If you change the slider’s width, you can center it on the page.'
    ]);
}
add_action('save_post', function($post_id){
    return sltk_save_meta($post_id,'sltk_nonce_centerSlider','sltk_save_centerSlider','centerSlider','_custom_centerSlider','yes_no');
});


/* Border */
function sltk_change_border($post) {

    $meta = [
        'border'       => get_post_meta($post->ID, '_custom_border', true),
        'border_type'  => get_post_meta($post->ID, '_custom_border_type', true),
        'border_color' => get_post_meta($post->ID, '_custom_border-color', true),
    ];

    $icon     = plugin_dir_url(dirname(__FILE__)) . '/assets/img/border.png';
    $infoPath = plugin_dir_url(dirname(__FILE__)) . '/assets/img/info.png';
    ?>
    <div style="display:flex;flex-direction:row;align-items:center;gap:10px;margin-bottom:10px;">
        <img style="width:30px;height:auto;object-fit:contain;" src="<?php echo esc_url($icon); ?>" alt="">
        <label>Border width:</label>
        <input type="number" name="border" min="0" value="<?php echo esc_attr($meta['border']); ?>">px
        <label>Type:</label>
        <select name="border-options">
            <?php 
            $types = ['none','solid','dotted','dashed','double','groove','ridge','outset'];

            foreach ($types as $type) {
                echo '<option value="' . esc_attr($type) . '" ' . selected($meta['border_type'], $type, false) . '>'
                    . esc_html($type) . '</option>';
            }
            ?>
        </select>
        <label>Color:</label>
        <input type="color" name="border-color" value="<?php echo esc_attr($meta['border_color']); ?>">
        <div class="tooltip" style="margin-left:20px;">
            <img style="width:30px;height:auto;cursor:help;" src="<?php echo esc_url($infoPath); ?>" alt="info">
            <span class="tooltiptext">Change the slider’s border width, style, or color.</span>
        </div>
        <?php wp_nonce_field('sltk_save_border','sltk_nonce_border'); ?>
    </div>
    <?php
}


function sltk_save_border($post_id){

    if (!isset($_POST['sltk_nonce_border'])) return $post_id;

    $nonce = sanitize_text_field( wp_unslash($_POST['sltk_nonce_border']) );
    if (!wp_verify_nonce($nonce, 'sltk_save_border')) return $post_id;

    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) return $post_id;
    if (!current_user_can('edit_post', $post_id)) return $post_id;

    if (isset($_POST['border'])) {
        update_post_meta($post_id, '_custom_border', sanitize_text_field( wp_unslash($_POST['border']) ));
    }

    if (isset($_POST['border-options'])) {
        update_post_meta($post_id, '_custom_border_type', sanitize_text_field( wp_unslash($_POST['border-options']) ));
    }

    if (isset($_POST['border-color'])) {
        update_post_meta($post_id, '_custom_border-color', sanitize_hex_color( wp_unslash($_POST['border-color']) ));
    }

    return $post_id;
}
add_action('save_post', 'sltk_save_border');


/* Images Field */
function sltk_render_images_field($post) {

    wp_nonce_field('sltk_save_images_field','sltk_nonce_images_field');

    $images = get_post_meta($post->ID, '_custom_images', true);
    $images = !empty($images) ? explode(',', $images) : [];

    $infoPath = plugin_dir_url(dirname(__FILE__)) . '/assets/img/info.png';
    ?>

    <div id="sltk_change_media_wrapper">
        <div class="containerPictures">
            <button type="button" class="button" id="sltk_select_images_btn">Select Pictures</button>

            <div class="tooltip">
                <img style="margin-left:20px;width:30px;height:auto;" src="<?php echo esc_url($infoPath); ?>" alt="info">
                <span class="tooltiptext">Select the photos you want for the slider, then drag them to change their order.</span>
            </div>
        </div>

        <ul id="sltk_images_preview"></ul>

        <input type="hidden" 
               name="custom_images" 
               id="sltk_custom_images" 
               value="<?php echo esc_attr(implode(',', $images)); ?>" />
    </div>

    <?php
}

add_action('save_post', function($post_id){
    return sltk_save_meta($post_id,'sltk_nonce_images_field','sltk_save_images_field','custom_images','_custom_images','text');
});


/* Show shortcode in admin list */
function sltk_show_column_id_logo_ticker($column,$post_id){
    if ($column === 'post_id') {
        echo '<input type="text" value="[sltk id=' . esc_attr($post_id) . ']" readonly>';
    }
}
add_action('manage_sltk_posts_custom_column','sltk_show_column_id_logo_ticker',10,2);

?>
