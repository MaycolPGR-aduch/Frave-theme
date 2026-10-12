<?php
/**
 * Line hero: the line image fills the screen (on phones it sits under the copy), with the
 * title, the introduction and the two actions: a quote request about the line and its
 * products in the catalog (or the portfolio while there are none).
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$frave_slug   = $args['slug'];
$frave_term   = $args['term'];
$frave_copy   = frave_line_copy( $frave_slug );
$frave_images = frave_line_images( $frave_slug );
$frave_quote  = $frave_term ? frave_line_quote_link( $frave_term ) : null;
$frave_shop   = $frave_term ? frave_term_products_url( $frave_term ) : '';
$frave_groups = $frave_term ? frave_line_groups( $frave_term ) : array();
?>
<section class="line-hero line-hero--<?php echo esc_attr( $frave_slug ); ?>" style="--line-tone: <?php echo esc_attr( $frave_copy['tone'] ); ?>" aria-labelledby="line-hero-title">
	<?php if ( '' !== $frave_images['mobile'] || '' !== $frave_images['desktop'] ) : ?>
		<picture class="line-hero__media">
			<?php if ( '' !== $frave_images['desktop'] ) : ?>
				<source media="(min-width: 64rem)" srcset="<?php echo esc_url( $frave_images['desktop'] ); ?>" width="<?php echo (int) $frave_images['desktop_size'][0]; ?>" height="<?php echo (int) $frave_images['desktop_size'][1]; ?>">
			<?php endif; ?>
			<?php
			$frave_fallback = '' !== $frave_images['mobile'] ? 'mobile' : 'desktop';
			$frave_size     = $frave_images[ $frave_fallback . '_size' ];
			?>
			<img src="<?php echo esc_url( $frave_images[ $frave_fallback ] ); ?>" width="<?php echo (int) $frave_size[0]; ?>" height="<?php echo (int) $frave_size[1]; ?>" alt="" fetchpriority="high" decoding="async">
		</picture>
	<?php endif; ?>
	<div class="container line-hero__inner">
		<div class="line-hero__copy">
			<p class="eyebrow"><?php echo esc_html( frave_line_text( 'eyebrow', $frave_slug ) ); ?></p>
			<h1 class="display line-hero__title" id="line-hero-title"><?php echo frave_emphasis( frave_line_text( 'title', $frave_slug ), 'pill' ); // phpcs:ignore WordPress.Security.EscapeOutput -- escaped in the helper. ?></h1>
			<?php $frave_lead = frave_line_text( 'lead', $frave_slug ); ?>
			<?php if ( '' !== $frave_lead ) : ?>
				<p class="lead line-hero__lead"><?php echo esc_html( $frave_lead ); ?></p>
			<?php endif; ?>
			<div class="btn-row">
				<?php if ( $frave_quote ) : ?>
					<a class="btn btn--primary" href="<?php echo esc_url( $frave_quote['url'] ); ?>"<?php echo $frave_quote['external'] ? ' target="_blank" rel="noopener"' : ''; ?>>
						<span><?php esc_html_e( 'Solicitar cotización', 'frave' ); ?></span>
						<?php if ( $frave_quote['external'] ) : ?>
							<span class="screen-reader-text"><?php esc_html_e( '(se abre en una nueva pestaña)', 'frave' ); ?></span>
						<?php endif; ?>
						<span class="btn__icon"><?php echo frave_icon( 'arrow-right' ); // phpcs:ignore WordPress.Security.EscapeOutput -- static SVG. ?></span>
					</a>
				<?php endif; ?>
				<?php if ( '' !== $frave_shop ) : ?>
					<?php frave_button( __( 'Ver productos', 'frave' ), $frave_shop, 'btn btn--dark' ); ?>
				<?php elseif ( $frave_groups ) : ?>
					<?php frave_button( __( 'Ver el portafolio', 'frave' ), '#portafolio', 'btn btn--dark', 'chevron-down' ); ?>
				<?php endif; ?>
			</div>
		</div>
	</div>
</section>
