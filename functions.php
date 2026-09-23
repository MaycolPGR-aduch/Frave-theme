<?php
/**
 * Frave theme bootstrap.
 *
 * @package Frave
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'FRAVE_VERSION', '1.0.0' );
define( 'FRAVE_THEME_DIR', get_template_directory() );
define( 'FRAVE_THEME_URI', get_template_directory_uri() );

require_once FRAVE_THEME_DIR . '/inc/setup.php';
require_once FRAVE_THEME_DIR . '/inc/helpers.php';
require_once FRAVE_THEME_DIR . '/inc/enqueue.php';
require_once FRAVE_THEME_DIR . '/inc/woocommerce.php';
require_once FRAVE_THEME_DIR . '/inc/acf.php';
