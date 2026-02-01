<?php
if (!defined('WPINC')) {
    die;
}



$this->meta_form->buy_pro(
    array(
        'status'  => LGX_LS_PLUGIN_META_FIELD_PRO,
        'link' => 'https://logichunt.com/product/wordpress-logo-slider/',
    )
);


$this->meta_form->switch(
    array(
        'label' => __( 'Enable Preloader', $this->plugin_name ),
        'yes_label' => __( 'Enabled', $this->plugin_name ),
        'no_label' => __( 'Disabled', $this->plugin_name ),
        'desc' => __( 'The showcase will be invisible until the page load complete.', $this->plugin_name ),
        'name' => 'meta_lgx_lsp_shortcodes[lgx_preloader_en]',
        'id' => 'lgx_preloader_en',
        'default' => 'yes'
    )
);

$this->meta_form->upload(
    array(
        'label'   => __( 'Preloader Icon', $this->plugin_name ),
        'desc'    => __( 'Upload Background Icon for Preloader.', $this->plugin_name ),
        'name'    => 'meta_lgx_lsp_shortcodes[lgx_preloader_icon]',
        'status'  => LGX_LS_PLUGIN_META_FIELD_PRO,
        'id'      => 'lgx_preloader_icon',
    )
);

$this->meta_form->color(
    array(
        'label'     => __( 'Preloader Background', $this->plugin_name ),
        'desc'      => __( 'Please select background color for Preloader.', $this->plugin_name ),
        'name'      => 'meta_lgx_lsp_shortcodes[lgx_preloader_bg_color]',
        'id'        => 'lgx_preloader_bg_color',
        'status'  => LGX_LS_PLUGIN_META_FIELD_PRO,
        'default'   => '#ffffff',
    )
);

