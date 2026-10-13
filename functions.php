<?php
/**
 * Frave theme bootstrap.
 *
 * @package Frave
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'FRAVE_VERSION', '1.11.0' );
define( 'FRAVE_THEME_DIR', get_template_directory() );
define( 'FRAVE_THEME_URI', get_template_directory_uri() );

require_once FRAVE_THEME_DIR . '/inc/setup.php';
require_once FRAVE_THEME_DIR . '/inc/helpers.php';
require_once FRAVE_THEME_DIR . '/inc/icons.php';
require_once FRAVE_THEME_DIR . '/inc/enqueue.php';
require_once FRAVE_THEME_DIR . '/inc/woocommerce.php';
require_once FRAVE_THEME_DIR . '/inc/catalog-filters.php';
require_once FRAVE_THEME_DIR . '/inc/acf.php';
require_once FRAVE_THEME_DIR . '/inc/contact.php';
require_once FRAVE_THEME_DIR . '/inc/institutional.php';
require_once FRAVE_THEME_DIR . '/inc/navigation.php';
require_once FRAVE_THEME_DIR . '/inc/style-guide.php';
require_once FRAVE_THEME_DIR . '/inc/home.php';
require_once FRAVE_THEME_DIR . '/inc/lines.php';
require_once FRAVE_THEME_DIR . '/inc/company.php';
require_once FRAVE_THEME_DIR . '/inc/solutions.php';
require_once FRAVE_THEME_DIR . '/inc/blog.php';
require_once FRAVE_THEME_DIR . '/inc/seo.php';
require_once FRAVE_THEME_DIR . '/inc/product-page.php';
