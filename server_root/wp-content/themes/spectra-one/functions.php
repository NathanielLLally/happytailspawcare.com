<?php
/**
 * Functions and definitions
 *
 * @package Spectra One
 * @author Brainstorm Force
 * @since 0.0.1
 */

declare( strict_types=1 );

namespace Swt;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

function add_homepage_body_id( $atts ) {
            if ( is_front_page() || is_home() ) {
                            $atts['id'] = 'home';
                                }
                return $atts;
}
add_filter( 'body_attributes', 'add_homepage_body_id' );

const SWT_VER  = '1.1.7';
const SWT_SLUG = 'spectra-one';
const SWT_NAME = 'Spectra One';
const SWT_PFX  = 'swt';
const SWT_LOC  = 'spectraOne';
const SWT_NS   = __NAMESPACE__ . '\\';
const SWT_DS   = DIRECTORY_SEPARATOR;
const SWT_DIR  = __DIR__ . SWT_DS;

/**
 * Setup base spectra one functions
 */
require_once SWT_DIR . 'inc/utilities/all.php';
require_once SWT_DIR . 'inc/theme-options.php';
require_once SWT_DIR . 'inc/theme-updater.php';
require_once SWT_DIR . 'inc/scripts.php';
require_once SWT_DIR . 'inc/blocks/all.php';
require_once SWT_DIR . 'inc/compatibility/all.php';
require_once SWT_DIR . 'inc/extensions/all.php';
require_once SWT_DIR . 'inc/block-styles/all.php';

/**
 * Admin functions
 */

require_once SWT_DIR . 'inc/admin/welcome-notice.php';
require_once SWT_DIR . 'inc/admin/settings.php';
