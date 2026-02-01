<?php

 if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

function sltk_add_media() {
    add_meta_box(
        'sltk_add_media', 
        'Media Pictures Slider', 
        'sltk_render_images_field', 
        'sltk', 
        'normal', 
        'high'
    );
}
add_action('add_meta_boxes', 'sltk_add_media');

function sltk_add_color_picker_meta_box() {
    add_meta_box(
        'sltk_change_color', 
        'Change Color Slider', 
        'sltk_change_color', 
        'sltk', 
        'normal', 
        'default' 
    );
}
add_action('add_meta_boxes', 'sltk_add_color_picker_meta_box');

function sltk_add_height() {
    add_meta_box(
        'sltk_change_height', 
        'Change Height Slider', 
        'sltk_change_height', 
        'sltk', 
        'normal', 
        'default' 
    );
}
add_action('add_meta_boxes', 'sltk_add_height');

function sltk_add_width() {
    add_meta_box(
        'sltk_change_width', 
        'Change Width Slider', 
        'sltk_change_width', 
        'sltk', 
        'normal', 
        'default' 
    );
}
add_action('add_meta_boxes', 'sltk_add_width');

function sltk_add_height_pictures() {
    add_meta_box(
        'sltk_change_height_pictures', 
        'Change Height Pictures', 
        'sltk_change_height_pictures', 
        'sltk', 
        'normal', 
        'default' 
    );
}
add_action('add_meta_boxes', 'sltk_add_height_pictures');

function sltk_add_width_pictures() {
    add_meta_box(
        'sltk_change_width_pictures', 
        'Change Width Pictures', 
        'sltk_change_width_pictures', 
        'sltk', 
        'normal', 
        'default' 
    );
}
add_action('add_meta_boxes', 'sltk_add_width_pictures');

function sltk_add_padding() {
    add_meta_box(
        'sltk_change_padding', 
        'Change Padding Slider', 
        'sltk_change_padding', 
        'sltk', 
        'normal', 
        'default' 
    );
}
add_action('add_meta_boxes', 'sltk_add_padding');

function sltk_add_speed() {
    add_meta_box(
        'sltk_change_speed', 
        'Change Speed Slider', 
        'sltk_change_speed', 
        'sltk', 
        'normal', 
        'default' 
    );
}
add_action('add_meta_boxes', 'sltk_add_speed');

function sltk_add_gap() {
    add_meta_box(
        'sltk_change_gap', 
        'Change Gap Slider', 
        'sltk_change_gap', 
        'sltk', 
        'normal', 
        'default' 
    );
}
add_action('add_meta_boxes', 'sltk_add_gap');

function sltk_add_border() {
    add_meta_box(
        'sltk_change_border', 
        'Change Border Slider', 
        'sltk_change_border', 
        'sltk', 
        'normal', 
        'default' 
    );
}
add_action('add_meta_boxes', 'sltk_add_border');

function sltk_add_slidesPerView() {
    add_meta_box(
        'sltk_change_slidesPerView', 
        'Change Number of pictures of Slider', 
        'sltk_change_slidesPerView', 
        'sltk', 
        'normal', 
        'default' 
    );
}
add_action('add_meta_boxes', 'sltk_add_slidesPerView');

function sltk_add_centerSlider() {
    add_meta_box(
        'sltk_change_centerSlider', 
        'Center Slider', 
        'sltk_change_centerSlider', 
        'sltk', 
        'normal', 
        'default' 
    );
}
add_action('add_meta_boxes', 'sltk_add_centerSlider');


function sltk_add_blackAndWhite() {
    add_meta_box(
        'sltk_change_blackAndWhite', 
        'Black and White Slider', 
        'sltk_change_blackAndWhite', 
        'sltk', 
        'normal', 
        'default' 
    );
}
add_action('add_meta_boxes', 'sltk_add_blackAndWhite');

function sltk_add_stopSlider() {
    add_meta_box(
        'sltk_change_stopSlider', 
        'Stop Slider', 
        'sltk_change_stopSlider', 
        'sltk', 
        'normal', 
        'default' 
    );
}
add_action('add_meta_boxes', 'sltk_add_stopSlider');


function sltk_add_column_id_logo_ticker($columns) {
    $columns['post_id'] = 'Shortcode ID';
    return $columns;
}
add_filter('manage_edit-sltk_columns', 'sltk_add_column_id_logo_ticker');




function sltk_button_option_ticker($post){
    if($post->post_type === 'sltk'){
        echo '<div id="meta-box-list"<br>';
        echo '<h2>Configure Settings</h2>';
        echo '<button id="mostrar-media">Media</button>';
        echo '<button id="mostrar-style">Styles</button>';
        echo '<button id="mostrar-height">Height</button>';
        echo '<button id="mostrar-width">Width</button>';
        echo '</div>';
    }
}

add_action('edit_form_after_title', 'sltk_button_option_ticker');
