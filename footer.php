<?php
/** Global site footer. */
?>
	</div><!-- .site-content -->
	<footer class="site-footer">
		<div class="container site-footer__main">
			<div class="site-footer__brand">
				<a href="<?php echo esc_url( home_url( '/' ) ); ?>" aria-label="<?php echo esc_attr( get_bloginfo( 'name' ) ); ?>">
					<img src="<?php echo esc_url( frave_asset_url( 'assets/images/frave-logo.png' ) ); ?>" alt="" width="330" height="198" loading="lazy">
				</a>
				<p><?php esc_html_e( 'Fragancias, esencias e insumos para crear con intención.', 'frave' ); ?></p>
			</div>
			<div class="site-footer__links">
				<h2><?php esc_html_e( 'Descubre Frave', 'frave' ); ?></h2>
				<?php
				wp_nav_menu( array(
					'theme_location' => 'footer',
					'container'      => false,
					'menu_class'     => 'footer-menu',
					'fallback_cb'    => false,
				) );
				?>
			</div>
			<div class="site-footer__contact">
				<h2><?php esc_html_e( 'Atención', 'frave' ); ?></h2>
				<p><?php esc_html_e( 'Escríbenos para recibir orientación sobre nuestros productos.', 'frave' ); ?></p>
				<?php if ( class_exists( 'WooCommerce' ) ) : ?>
					<a href="<?php echo esc_url( wc_get_page_permalink( 'myaccount' ) ); ?>"><?php esc_html_e( 'Mi cuenta y pedidos', 'frave' ); ?></a>
				<?php endif; ?>
			</div>
		</div>
		<div class="container site-footer__bottom">
			<p>&copy; <?php echo esc_html( gmdate( 'Y' ) ); ?> <?php echo esc_html( get_bloginfo( 'name' ) ); ?>. <?php esc_html_e( 'Todos los derechos reservados.', 'frave' ); ?></p>
			<p><?php esc_html_e( 'Diseñado para acompañar tu creatividad.', 'frave' ); ?></p>
		</div>
	</footer>
</div><!-- .site-shell -->
<?php wp_footer(); ?>
</body>
</html>
