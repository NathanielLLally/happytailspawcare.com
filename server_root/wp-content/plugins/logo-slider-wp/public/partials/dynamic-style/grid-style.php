<?php
if (!defined('WPINC')) {
    die;
}

// Value
$lgx_grid_column_gap     = $lgx_shortcodes_meta['lgx_grid_column_gap'];
$lgx_grid_row_gap       = $lgx_shortcodes_meta['lgx_grid_row_gap'];

$lgx_lsw_dynamic_style_grid = '';

$lgx_lsw_dynamic_style_grid .= '#lgx_logo_slider_app_'. $lgx_app_id.' .lgx_app_layout_grid .lgx_app_item_row{
        grid-column-gap: '. $lgx_grid_column_gap.';
        grid-row-gap: '. $lgx_grid_row_gap.';
        grid-template-columns: repeat('. $lgx_large_desktop_item.', 2fr);
    }';

$lgx_lsw_dynamic_style_grid .= '@media screen and (max-width: 767px) {
        #lgx_logo_slider_app_'. $lgx_app_id.' .lgx_app_layout_grid .lgx_app_item_row{
            grid-template-columns: repeat('. $lgx_mobile_item.', 1fr);
        }
    }';
$lgx_lsw_dynamic_style_grid .= '@media screen and (min-width: 768px) {
        #lgx_logo_slider_app_'. $lgx_app_id.' .lgx_app_layout_grid .lgx_app_item_row{
            grid-template-columns: repeat('. $lgx_tablet_item.', 1fr);
        }
    }';
$lgx_lsw_dynamic_style_grid .= '@media screen and (min-width: 992px) {
        #lgx_logo_slider_app_'. $lgx_app_id.' .lgx_app_layout_grid .lgx_app_item_row{
            grid-template-columns: repeat('. $lgx_desktop_item.', 1fr);
        }
    }';
    
$lgx_lsw_dynamic_style_grid .= '@media screen and (min-width: 1200px) {
        #lgx_logo_slider_app_'. $lgx_app_id.' .lgx_app_layout_grid .lgx_app_item_row{
            grid-template-columns: repeat('. $lgx_large_desktop_item.', 1fr);
        }
    }';


  
    
/**
 *  Inline Style
 */

wp_add_inline_style( 'lgx-logo-slider-style', $lgx_lsw_dynamic_style_grid );