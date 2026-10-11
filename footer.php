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
				<p><?php esc_html_e( 'Sabores, fragancias y envases para dar vida a tus productos.', 'frave' ); ?></p>
			</div>
			<div class="site-footer__links">
				<h2><?php esc_html_e( 'Descubre Frave', 'frave' ); ?></h2>
				<?php
				wp_nav_menu(
					array(
						'theme_location' => 'footer',
						'container'      => false,
						'menu_class'     => 'footer-menu',
						'depth'          => 1,
						'fallback_cb'    => 'frave_footer_menu_fallback',
					)
				);
				?>
			</div>
			<div class="site-footer__contact">
				<h2><?php esc_html_e( 'Contacto', 'frave' ); ?></h2>
				<?php $frave_channels = array_intersect_key( frave_contact_channels(), array_flip( array( 'email', 'phone', 'address' ) ) ); ?>
				<?php if ( $frave_channels ) : ?>
					<ul class="footer-channels">
						<?php foreach ( $frave_channels as $frave_channel ) : ?>
							<li>
								<span class="screen-reader-text"><?php echo esc_html( $frave_channel['label'] ); ?>:</span>
								<?php if ( $frave_channel['url'] ) : ?>
									<a href="<?php echo esc_url( $frave_channel['url'] ); ?>"><?php echo esc_html( $frave_channel['value'] ); ?></a>
								<?php else : ?>
									<?php echo nl2br( esc_html( $frave_channel['value'] ), false ); ?>
								<?php endif; ?>
							</li>
						<?php endforeach; ?>
					</ul>
				<?php endif; ?>
				<?php $frave_contact_url = frave_contact_page_url(); ?>
				<?php if ( $frave_contact_url ) : ?>
					<a class="footer-link" href="<?php echo esc_url( $frave_contact_url ); ?>"><?php esc_html_e( 'Escríbenos o solicita una cotización', 'frave' ); ?></a>
				<?php endif; ?>
				<?php if ( frave_shop_enabled() ) : ?>
					<a class="footer-link" href="<?php echo esc_url( wc_get_page_permalink( 'myaccount' ) ); ?>"><?php esc_html_e( 'Mi cuenta y pedidos', 'frave' ); ?></a>
				<?php endif; ?>
				<?php $frave_whatsapp_url = frave_whatsapp_url( frave_whatsapp_message() ); ?>
				<?php if ( $frave_whatsapp_url ) : ?>
					<a class="footer-whatsapp" href="<?php echo esc_url( $frave_whatsapp_url ); ?>" target="_blank" rel="noopener" data-frave-whatsapp="footer">
						<?php esc_html_e( 'Escríbenos por WhatsApp', 'frave' ); ?>
						<span class="screen-reader-text"><?php esc_html_e( '(se abre en una nueva pestaña)', 'frave' ); ?></span>
					</a>
				<?php endif; ?>
				<?php $frave_claims_url = function_exists( 'frave_peru_claims_page_url' ) ? frave_peru_claims_page_url() : ''; ?>
				<?php if ( $frave_claims_url ) : ?>
					<a class="claims-book-link" href="<?php echo esc_url( $frave_claims_url ); ?>">
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
