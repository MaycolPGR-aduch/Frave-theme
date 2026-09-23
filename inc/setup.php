<?php
/** Theme supports, image sizes, and navigation. */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function frave_setup(): void {
	load_theme_textdomain( 'frave', FRAVE_THEME_DIR . '/languages' );

	add_theme_support( 'title-tag' );
	add_theme_support( 'post-thumbnails' );
	add_theme_support( 'responsive-embeds' );
	add_theme_support( 'align-wide' );
	add_theme_support( 'custom-logo', array(
		'height'      => 96,
		'width'       => 320,
		'flex-height' => true,
		'flex-width'  => true,
	) );
	add_theme_support( 'html5', array( 'search-form', 'comment-form', 'comment-list', 'gallery', 'caption', 'style', 'script' ) );
	add_theme_support( 'woocommerce', array(
		'thumbnail_image_width' => 640,
		'single_image_width'    => 900,
		'product_grid'          => array(
			'default_rows'    => 3,
			'min_rows'        => 1,
			'max_rows'        => 8,
			'default_columns' => 3,
			'min_columns'     => 2,
			'max_columns'     => 4,
		),
	) );

	register_nav_menus( array(
		'primary' => __( 'Navegación principal', 'frave' ),
		'footer'  => __( 'Navegación del pie', 'frave' ),
	) );

	add_image_size( 'frave-card', 720, 900, true );
	add_image_size( 'frave-editorial', 1200, 850, true );
}
add_action( 'after_setup_theme', 'frave_setup' );

/** Make a predictable content width available to embeds and legacy plugins. */
function frave_content_width(): void {
	$GLOBALS['content_width'] = 760;
}
add_action( 'after_setup_theme', 'frave_content_width', 0 );

/** Provide a small, useful menu until the owner assigns a custom menu. */
function frave_primary_menu_fallback(): void {
	$shop_url = class_exists( 'WooCommerce' ) ? wc_get_page_permalink( 'shop' ) : home_url( '/' );
	?>
	<ul class="menu-list">
		<li><a href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php esc_html_e( 'Inicio', 'frave' ); ?></a></li>
		<?php if ( class_exists( 'WooCommerce' ) ) : ?>
			<li><a href="<?php echo esc_url( $shop_url ); ?>"><?php esc_html_e( 'Tienda', 'frave' ); ?></a></li>
			<li><a href="<?php echo esc_url( wc_get_page_permalink( 'myaccount' ) ); ?>"><?php esc_html_e( 'Mi cuenta', 'frave' ); ?></a></li>
		<?php endif; ?>
	</ul>
	<?php
}
