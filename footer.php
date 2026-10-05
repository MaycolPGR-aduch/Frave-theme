<?php
/** Global site footer. */
?>
	</div><!-- .site-content -->
	<footer class="site-footer">
		<div class="container site-footer__main">
			<div class="site-footer__brand">
				<a href="<?php echo esc_url( home_url( '/' ) ); ?>" aria-label="<?php echo esc_attr( get_bloginfo( 'name' ) ); ?>">
					<img src="<?php echo esc_url( frave_asset_url( 'assets/images/frave-logo-footer.png' ) ); ?>" alt="" width="330" height="198" loading="lazy">
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
				<h2><?php esc_html_e( 'Tu cuenta', 'frave' ); ?></h2>
				<p><?php esc_html_e( 'Consulta tu cuenta y el estado de tus pedidos.', 'frave' ); ?></p>
				<?php if ( class_exists( 'WooCommerce' ) ) : ?>
					<a href="<?php echo esc_url( wc_get_page_permalink( 'myaccount' ) ); ?>"><?php esc_html_e( 'Mi cuenta y pedidos', 'frave' ); ?></a>
				<?php endif; ?>
				<?php $whatsapp_url = frave_whatsapp_url( frave_whatsapp_message() ); ?>
				<?php if ( $whatsapp_url ) : ?>
					<a class="footer-whatsapp" href="<?php echo esc_url( $whatsapp_url ); ?>" target="_blank" rel="noopener" data-frave-whatsapp="footer">
						<?php esc_html_e( 'Escríbenos por WhatsApp', 'frave' ); ?>
						<span class="screen-reader-text"><?php esc_html_e( '(se abre en una nueva pestaña)', 'frave' ); ?></span>
					</a>
				<?php endif; ?>
				<?php $claims_url = function_exists( 'frave_peru_claims_page_url' ) ? frave_peru_claims_page_url() : ''; ?>
				<?php if ( $claims_url ) : ?>
					<a class="claims-book-link" href="<?php echo esc_url( $claims_url ); ?>">
						<svg viewBox="0 0 24 24" aria-hidden="true" focusable="false"><path d="M4 5.5C4 4.7 4.7 4 5.5 4H11v16H5.5c-.8 0-1.5-.7-1.5-1.5v-13Z"></path><path d="M20 5.5c0-.8-.7-1.5-1.5-1.5H13v16h5.5c.8 0 1.5-.7 1.5-1.5v-13Z"></path><path d="M6.5 8h2.5M6.5 11h2.5M15 8h2.5M15 11h2.5"></path></svg>
						<span><?php esc_html_e( 'Libro de Reclamaciones', 'frave' ); ?></span>
					</a>
				<?php endif; ?>
			</div>
		</div>
		<div class="container site-footer__bottom">
			<p>&copy; <?php echo esc_html( gmdate( 'Y' ) ); ?> <?php echo esc_html( get_bloginfo( 'name' ) ); ?>. <?php esc_html_e( 'Todos los derechos reservados.', 'frave' ); ?></p>
			<?php frave_legal_menu(); ?>
			<p><?php esc_html_e( 'Diseñado para acompañar tu creatividad.', 'frave' ); ?></p>
		</div>
	</footer>
</div><!-- .site-shell -->
<?php get_template_part( 'template-parts/components/mini-cart' ); ?>
<?php get_template_part( 'template-parts/components/whatsapp-button' ); ?>
<?php wp_footer(); ?>
</body>
</html>
