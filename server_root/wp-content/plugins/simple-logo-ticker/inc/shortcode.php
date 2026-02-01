<?php

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

function sltk_create_custom_post_type_shortcode($atts){
    $post = get_post($atts['id']);
    if (!$post) return; 

    $images = get_post_meta($post->ID, '_custom_images', true);
    if (!$images) return '<p>No images found.</p>';  

    // Fondo
    $color = get_post_meta($post->ID, '_favorite_color', true);
    $background_style = $color ? 'background-color:' . esc_attr($color) . ';' : '';

    // Dimensiones del contenedor
    $height = get_post_meta($post->ID, '_custom_height',true);
    $height_style = $height ? 'height:' . esc_attr($height) . 'px;' : '';
    $width = get_post_meta($post->ID, '_custom_width',true);
    $width_style = $width ? 'width:' . esc_attr($width) . 'px;' : '';
    $padding = get_post_meta($post->ID, '_custom_padding',true);
    $padding_style = $padding ? 'padding:' . esc_attr($padding) . 'px;' : '';

    // Borde
    $border = get_post_meta($post->ID, '_custom_border',true);
    $border_style = $border ? 'border:' . esc_attr($border) . 'px ' : '';
    $borderType = get_post_meta($post->ID, '_custom_border_type',true);
    $borderType_style = $borderType ? esc_attr($borderType) : '';
    $borderColor = get_post_meta($post->ID, '_custom_border-color',true);
    $borderColor_style = $borderColor ? esc_attr($borderColor) . ';' : '';

    // Centrado
    $centerSlider = get_post_meta($post->ID, '_custom_centerSlider',true);
    $centerSlider_style = ($centerSlider === 'yes') ? 'margin:0 auto;' : '';

    // Estilos para las imágenes
    $height_Pictures = get_post_meta($post->ID, '_custom_height_Pictures',true);
    $height_style_pictures = $height_Pictures ? 'height:' . esc_attr($height_Pictures) . 'px;' : '';
    $width_Pictures = get_post_meta($post->ID, '_custom_width_Pictures',true);
    $width_style_pictures = $width_Pictures ? 'width:' . esc_attr($width_Pictures) . 'px;' : '';

    $gap = get_post_meta($post->ID, '_custom_gap',true);
    $gap_style = $gap ? 'gap:' . esc_attr($gap) . 'px;' : '';

    // Blanco y negro (solo fondo)
    $blackAndWhite = get_post_meta($post->ID, '_custom_blackAndWhite', true);
    $bw_class = ($blackAndWhite === 'yes') ? ' bw' : '';

    // JS data
    $speed = get_post_meta($post->ID, '_custom_speed', true);
    $speed = ($speed !== '' && is_numeric($speed)) ? intval($speed) : 1000;
    $stop = get_post_meta($post->ID, '_custom_stopSlider', true);
    $stop = ($stop === 'yes') ? 'yes' : 'no';
    $slidesPerView = get_post_meta($post->ID, '_custom_slidesPerView', true);
    $slidesPerView = ($slidesPerView !== '' && is_numeric($slidesPerView)) ? intval($slidesPerView) : 3;

    $images = explode(',', $images);

    // Contenedor principal
    $output = '<div class="custom-slider-container sltk_custom-slider-container' . $bw_class . '" style="' .
        $background_style . $height_style . $width_style . $padding_style .
        $border_style . $borderType_style . $borderColor_style . $centerSlider_style . '">';

    // Slider Swiper
    $output .= '<div class="swiper sltk_swiper" 
        data-speed="' . esc_attr($speed) . '" 
        data-slides-per-view="' . esc_attr($slidesPerView) . '" 
        data-no-stop="' . esc_attr($stop) . '">';

    $output .= '<div class="swiper-wrapper sltk_swiper-wrapper" style="' . $gap_style .'">';
    
    foreach ($images as $image) {
        $output .= '<div class="swiper-slide"><img style="' .
            $height_style_pictures . $width_style_pictures . '" src="' . esc_url($image) . '" alt="Slider image"></div>';
    }

    $output .= '</div></div></div>';

    return $output;
}

add_shortcode('sltk', 'sltk_create_custom_post_type_shortcode');
