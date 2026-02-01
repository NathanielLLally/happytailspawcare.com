<?php
/**
 * @see https://github.com/WordPress/gutenberg/blob/trunk/docs/reference-guides/block-api/block-metadata.md#render
 */


?>

<div <?php echo wp_kses_data( get_block_wrapper_attributes( $attributes ) ); ?>>
    <?php
    $output = ''; 

    $id = isset( $attributes['shortcodeId'] ) ? intval( $attributes['shortcodeId'] ) : 0;

    if ( $id ) {
        $output = do_shortcode( '[sltk id="' . $id . '"]' );
    }

    echo wp_kses_post( $output );
    ?>
</div>



