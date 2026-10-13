<?php
/**
 * Global site footer: an invitation to get in touch, the main links, contact channels
 * and legal links. On large screens the page content lifts off it at the end (motion.css).
 */

$frave_cta          = frave_header_cta();
$frave_whatsapp_url = frave_whatsapp_url( frave_whatsapp_message() );
$frave_channel_icon = array(
	'email'   => 'mail',
	'phone'   => 'phone',
	'address' => 'pin',
	'hours'   => 'clock',
);
?>
	</div><!-- .site-content -->
	<footer class="site-footer on-dark">
		<div class="site-footer__decor" aria-hidden="true"><?php echo frave_isotype(); // phpcs:ignore WordPress.Security.EscapeOutput -- static SVG. ?></div>
		<div class="container">
			<?php // Pages with their own closing call to action (line pages) turn this one off. ?>
			<?php if ( $frave_cta && apply_filters( 'frave_show_footer_cta', true ) ) : ?>
				<div class="site-footer__cta">
					<div>
						<p class="eyebrow"><?php esc_html_e( 'Hablemos', 'frave' ); ?></p>
						<p class="site-footer__cta-title"><?php echo wp_kses( __( '¿Tienes un proyecto en mente? <strong>Te asesoramos.</strong>', 'frave' ), array( 'strong' => array() ) ); ?></p>
					</div>
					<div class="btn-row">
						<?php if ( $frave_cta['external'] ) : ?>
							<a class="btn btn--primary" href="<?php echo esc_url( $frave_cta['url'] ); ?>" target="_blank" rel="noopener" data-frave-whatsapp="footer">
								<span><?php echo esc_html( $frave_cta['label'] ); ?></span>
								<span class="screen-reader-text"><?php esc_html_e( '(se abre en una nueva pestaña)', 'frave' ); ?></span>
								<span class="btn__icon"><?php echo frave_icon( 'arrow-up-right' ); // phpcs:ignore WordPress.Security.EscapeOutput -- static SVG. ?></span>
							</a>
						<?php else : ?>
							<?php frave_button( $frave_cta['label'], $frave_cta['url'] ); ?>
							<?php if ( $frave_whatsapp_url ) : ?>
								<a class="btn btn--outline" href="<?php echo esc_url( $frave_whatsapp_url ); ?>" target="_blank" rel="noopener" data-frave-whatsapp="footer">
									<span><?php esc_html_e( 'Escríbenos por WhatsApp', 'frave' ); ?></span>
									<span class="screen-reader-text"><?php esc_html_e( '(se abre en una nueva pestaña)', 'frave' ); ?></span>
									<span class="btn__icon"><?php echo frave_whatsapp_icon(); // phpcs:ignore WordPress.Security.EscapeOutput -- static SVG. ?></span>
								</a>
							<?php endif; ?>
						<?php endif; ?>
					</div>
				</div>
			<?php endif; ?>

			<div class="site-footer__main">
				<div class="site-footer__brand">
					<a href="<?php echo esc_url( home_url( '/' ) ); ?>" aria-label="<?php echo esc_attr( get_bloginfo( 'name' ) ); ?>">
						<img src="<?php echo esc_url( frave_asset_url( 'assets/images/frave-logo-footer.webp' ) ); ?>" alt="" width="840" height="504" loading="lazy">
					</a>
					<p><?php esc_html_e( 'Sabores, fragancias y envases para dar vida a tus productos.', 'frave' ); ?></p>
					<?php get_template_part( 'template-parts/components/social-links', null, array( 'context' => 'footer' ) ); ?>
				</div>
				<nav class="site-footer__links" aria-labelledby="footer-links-title">
					<h2 id="footer-links-title"><?php esc_html_e( 'Explora', 'frave' ); ?></h2>
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
				</nav>
				<div class="site-footer__contact">
					<h2><?php esc_html_e( 'Contacto', 'frave' ); ?></h2>
					<?php $frave_channels = array_intersect_key( frave_contact_channels(), $frave_channel_icon ); ?>
					<?php if ( $frave_channels ) : ?>
						<ul class="footer-channels">
							<?php foreach ( $frave_channels as $frave_key => $frave_channel ) : ?>
								<li>
									<?php echo frave_icon( $frave_channel_icon[ $frave_key ] ); // phpcs:ignore WordPress.Security.EscapeOutput -- static SVG. ?>
									<span>
										<span class="screen-reader-text"><?php echo esc_html( $frave_channel['label'] ); ?>:</span>
										<?php if ( $frave_channel['url'] ) : ?>
											<a href="<?php echo esc_url( $frave_channel['url'] ); ?>"><?php echo esc_html( $frave_channel['value'] ); ?></a>
										<?php else : ?>
											<?php echo nl2br( esc_html( $frave_channel['value'] ), false ); ?>
										<?php endif; ?>
									</span>
								</li>
							<?php endforeach; ?>
						</ul>
					<?php endif; ?>
					<?php $frave_contact_url = frave_contact_page_url(); ?>
					<?php if ( $frave_contact_url ) : ?>
						<a class="footer-link link-arrow" href="<?php echo esc_url( $frave_contact_url ); ?>"><?php esc_html_e( 'Escríbenos o solicita una cotización', 'frave' ); ?><?php echo frave_icon( 'arrow-right' ); // phpcs:ignore WordPress.Security.EscapeOutput -- static SVG. ?></a>
					<?php endif; ?>
					<?php if ( frave_shop_enabled() ) : ?>
						<a class="footer-link" href="<?php echo esc_url( wc_get_page_permalink( 'myaccount' ) ); ?>"><?php esc_html_e( 'Mi cuenta y pedidos', 'frave' ); ?></a>
					<?php endif; ?>
					<?php $frave_claims_url = function_exists( 'frave_peru_claims_page_url' ) ? frave_peru_claims_page_url() : ''; ?>
					<?php if ( $frave_claims_url ) : ?>
						<a class="claims-book-link" href="<?php echo esc_url( $frave_claims_url ); ?>">
							<?php echo frave_icon( 'book' ); // phpcs:ignore WordPress.Security.EscapeOutput -- static SVG. ?>
							<span><?php esc_html_e( 'Libro de Reclamaciones', 'frave' ); ?></span>
						</a>
					<?php endif; ?>
				</div>
			</div>
			<div class="site-footer__bottom">
				<p>&copy; <?php echo esc_html( gmdate( 'Y' ) ); ?> <?php echo esc_html( get_bloginfo( 'name' ) ); ?>. <?php esc_html_e( 'Todos los derechos reservados.', 'frave' ); ?></p>
				<?php frave_legal_menu(); ?>
			</div>
		</div>
	</footer>
</div><!-- .site-shell -->
<?php get_template_part( 'template-parts/components/mini-cart' ); ?>
<?php get_template_part( 'template-parts/components/whatsapp-button' ); ?>
<?php wp_footer(); ?>
</body>
</html>
