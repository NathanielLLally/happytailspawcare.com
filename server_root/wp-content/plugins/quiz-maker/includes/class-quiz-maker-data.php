<?php
/**
 * The public-facing functionality of the plugin.
 *
 * @link       http://ays-pro.com/
 * @since      1.0.0
 *
 * @package    Quiz_Maker
 * @subpackage Quiz_Maker/includes
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    Quiz_Maker
 * @subpackage Quiz_Maker/includes
 * @author     AYS Pro LLC <info@ays-pro.com>
 */
class Quiz_Maker_Data {

	public static function ays_quiz_allowed_html() {
        $additionalAllowedTags = wp_kses_allowed_html('post');
        return array_merge(
            $additionalAllowedTags,
            array(
                'div' => array(
                    'class'                 => true,
                    'id'                    => true,
                    'style'                 => true,
                    'data-*'                => true,
                    'aria-selected'         => true,
                ),
                'svg' => array(
                    'class'                 => true,
                    'width'                 => true,
                    'height'                => true,
                    'viewBox'               => true,
                    'viewbox'               => true,
                    'xmlns'                 => true,
                    'fill'                  => true,
                ),
                'path' => array(
                    'class'                 => true,
                    'd'                     => true,
                    'fill'                  => true,
                    'fill-rule'             => true,
                    'clip-rule'             => true,
                    'stroke-width'          => true,
                    'stroke-linecap'        => true,
                ),
                'circle'                    => array(
                    'style'                 => true,
                    'id'                    => true,
                    'class'                 => true,
                    'r'                     => true,
                    'cx'                    => true,
                    'cy'                    => true,
                ),
                'rect' => array(
                    'class'                 => true,
                    'x'                     => true,
                    'y'                     => true,
                    'width'                 => true,
                    'height'                => true,
                    'rx'                    => true,
                    'fill'                  => true,
                    'stroke'                => true,
                    'style'                 => true,
                    'transform'             => true,
                ),
                'defs' => array(
                    'class'                 => true,
                    'width'                 => true,
                    'height'                => true,
                    'fill'                  => true,
                    'style'                 => true,
                ),
                'pattern' => array(
                    'class'                 => true,
                    'id'                    => true,
                    'width'                 => true,
                    'height'                => true,
                    'patternContentUnits'   => true,
                    'patterncontentunits'   => true,
                    'style'                 => true,
                ),
                'use' => array(
                    'class'                 => true,
                    'id'                    => true,
                    'xlink'                 => true,
                    'xlink:href'            => true,
                    'transform'             => true,
                    'style'                 => true,
                ),
                'image' => array(
                    'class'                 => true,
                    'id'                    => true,
                    'xlink'                 => true,
                    'xlink:href'            => true,
                    'width'                 => true,
                    'height'                => true,
                    'style'                 => true,
                ),
                'video' => array(
                    'class'                 => true,
                    'id'                    => true,
                    'controls'              => true,
                    'style'                 => true,
                ),
                'source' => array(
                    'class'                 => true,
                    'id'                    => true,
                    'src'                   => true,
                    'style'                 => true,
                ),
                'form' => array(
                    'name'                  => true,
                    'id'                    => true,
                    'action'                => true,
                    'method'                => true,
                    'data-*'                => true,
                    'style'                 => true,
                ),
                'input' => array(
                    'id'                    => true,
                    'name'                  => true,
                    'class'                 => true,
                    'type'                  => true,
                    'value'                 => true,
                    'size'                  => true,
                    'required'              => true,
                    'readonly'              => true,
                    'data-*'                => true,
                    'style'                 => true,
                    'onClick'               => true,
                    'onclick'               => true,
                ),
                'select' => array(
                    'id'                    => true,
                    'name'                  => true,
                    'class'                 => true,
                    'type'                  => true,
                    'value'                 => true,
                    'size'                  => true,
                    'required'              => true,
                    'readonly'              => true,
                    'data-*'                => true,
                    'style'                 => true,
                ),
                'option' => array(
                    'id'                    => true,
                    'class'                 => true,
                    'type'                  => true,
                    'value'                 => true,
                    'size'                  => true,
                    'required'              => true,
                    'readonly'              => true,
                    'data-*'                => true,
                    'style'                 => true,
                ),
                'progress' => array(
                    'id'                    => true,
                    'class'                 => true,
                    'max'                   => true,
                    'value'                 => true,
                    'data-*'                => true,
                    'style'                 => true,
                ),
                'img' => array(
                    'id'                    => true,
                    'class'                 => true,
                    'loading'               => true,
                    'decoding'              => true,
                    'src'                   => true,
                    'sizes'                 => true,
                    'srcset'                => true,
                    'width'                 => true,
                    'height'                => true,
                ),
                'a' => array(
                    'aria-selected'         => true,
                    'data-*'                => true,
                    'style'                 => true,
                    'target'                => true,
                ),
            ),
            $additionalAllowedTags
        );
    }
}