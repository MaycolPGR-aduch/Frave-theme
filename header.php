<?php
/** The document head and global site header. */
?><!doctype html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>
<a class="skip-link" href="#primary"><?php esc_html_e( 'Saltar al contenido', 'frave' ); ?></a>
<div class="site-shell">
	<header class="site-header">
		<div class="container site-header__inner">
			<button class="menu-toggle" type="button" aria-expanded="false" aria-controls="primary-navigation" data-menu-toggle>
				<span class="menu-toggle__icon" aria-hidden="true"><span></span><span></span></span>
				<span class="screen-reader-text" data-menu-toggle-label><?php esc_html_e( 'Abrir menú', 'frave' ); ?></span>
			</button>

			<div class="site-branding">
				<?php if ( has_custom_logo() ) : ?>
					<?php the_custom_logo(); ?>
				<?php else : ?>
					<a class="site-branding__link" href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home">
						<img class="site-branding__logo" src="<?php echo esc_url( frave_asset_url( 'assets/images/frave-logo.png' ) ); ?>" alt="<?php echo esc_attr( get_bloginfo( 'name' ) ); ?>" width="440" height="264">
					</a>
				<?php endif; ?>
			</div>

			<nav class="primary-navigation" id="primary-navigation" aria-label="<?php esc_attr_e( 'Navegación principal', 'frave' ); ?>" data-primary-navigation>
				<?php
				wp_nav_menu( array(
					'theme_location' => 'primary',
					'container'      => false,
					'menu_class'     => 'menu-list',
					'fallback_cb'    => 'frave_primary_menu_fallback',
				) );
				?>
			</nav>

			<div class="header-actions">
				<?php if ( class_exists( 'WooCommerce' ) ) : ?>
					<a class="header-action header-action--search" href="<?php echo esc_url( wc_get_page_permalink( 'shop' ) ); ?>" aria-label="<?php esc_attr_e( 'Buscar productos', 'frave' ); ?>">
						<svg viewBox="0 0 24 24" aria-hidden="true" focusable="false"><circle cx="10.8" cy="10.8" r="6.4"></circle><path d="m16 16 4.2 4.2"></path></svg>
					</a>
					<a class="header-action header-action--account" href="<?php echo esc_url( wc_get_page_permalink( 'myaccount' ) ); ?>" aria-label="<?php esc_attr_e( 'Mi cuenta', 'frave' ); ?>">
						<svg viewBox="0 0 24 24" aria-hidden="true" focusable="false"><circle cx="12" cy="8" r="3.2"></circle><path d="M5.2 20c.5-3.3 2.8-5.2 6.8-5.2s6.3 1.9 6.8 5.2"></path></svg>
					</a>
					<a class="header-action header-action--cart" href="<?php echo esc_url( wc_get_cart_url() ); ?>" aria-label="<?php echo esc_attr( sprintf( __( 'Carrito, %d productos', 'frave' ), WC()->cart ? WC()->cart->get_cart_contents_count() : 0 ) ); ?>">
						<svg viewBox="0 0 24 24" aria-hidden="true" focusable="false"><path d="M3.5 4h2l2.1 11.2h10.8l2-8H6.2"></path><circle cx="9.5" cy="19" r="1"></circle><circle cx="17.2" cy="19" r="1"></circle></svg>
						<span class="cart-count"><?php echo esc_html( WC()->cart ? (string) WC()->cart->get_cart_contents_count() : '0' ); ?></span>
					</a>
				<?php endif; ?>
			</div>
		</div>
	</header>
	<div class="mobile-menu-backdrop" data-menu-backdrop hidden></div>
	<div class="site-content">
		<?php
		if ( class_exists( 'WooCommerce' ) ) {
			get_template_part( 'template-parts/components', 'search' );
		}
		?>
