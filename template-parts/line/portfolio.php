<?php
/**
 * Portfolio of the line: one card per subcategory with its description and, when it has
 * them, its own subcategories as chips. Chips and "Ver productos" link to the catalog only
 * where there are products; every card offers a quote request about that subcategory.
 * Hidden while the line has no subcategories.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$frave_slug = $args['slug'];
$frave_term = $args['term'];
if ( ! $frave_term ) {
	return;
}
$frave_groups = frave_line_groups( $frave_term );
if ( ! $frave_groups ) {
	return;
}
$frave_text = frave_line_text( 'portfolio_text', $frave_slug );
$frave_deep = count( array_filter( $frave_groups, static fn( $group ) => ! empty( $group['children'] ) ) ) > 0;
?>
<section class="section line-portfolio<?php echo $frave_deep ? ' line-portfolio--groups' : ''; ?>" id="portafolio" aria-labelledby="line-portfolio-title">
	<div class="container">
		<div class="section-head section-head--split" data-reveal="up">
			<p class="eyebrow"><?php esc_html_e( 'Portafolio', 'frave' ); ?></p>
			<h2 id="line-portfolio-title"><?php echo frave_emphasis( frave_line_text( 'portfolio_title', $frave_slug ) ); // phpcs:ignore WordPress.Security.EscapeOutput -- escaped in the helper. ?></h2>
			<?php if ( '' !== $frave_text ) : ?>
				<p class="lead"><?php echo esc_html( $frave_text ); ?></p>
			<?php endif; ?>
		</div>

		<ol class="line-groups" data-reveal-group>
			<?php foreach ( $frave_groups as $frave_index => $frave_group ) : ?>
				<?php
				$frave_group_term = $frave_group['term'];
				$frave_products   = frave_term_products_url( $frave_group_term );
				$frave_quote      = frave_line_quote_link( $frave_group_term );
				$frave_group_text = wp_strip_all_tags( $frave_group_term->description );
				$frave_image_id   = (int) get_term_meta( $frave_group_term->term_id, 'thumbnail_id', true );
				?>
				<li class="card line-group<?php echo $frave_image_id ? ' line-group--image' : ''; ?>">
					<?php if ( $frave_image_id ) : ?>
						<div class="line-group__media media-frame">
							<?php
							echo wp_get_attachment_image(
								$frave_image_id,
								'frave-card',
								false,
								array(
									'alt'     => '',
									'sizes'   => '(max-width: 48rem) 90vw, 40vw',
									'loading' => 'lazy',
								)
							);
							?>
						</div>
					<?php endif; ?>
					<div class="line-group__body">
						<span class="line-group__number" aria-hidden="true"><?php echo esc_html( sprintf( '%02d', $frave_index + 1 ) ); ?></span>
						<h3 class="line-group__title"><?php echo esc_html( $frave_group_term->name ); ?></h3>
						<?php if ( '' !== $frave_group_text ) : ?>
							<p class="line-group__text"><?php echo esc_html( $frave_group_text ); ?></p>
						<?php endif; ?>
						<?php if ( $frave_group['children'] ) : ?>
							<ul class="line-group__types" aria-label="<?php echo esc_attr( sprintf( /* translators: %s: subcategory name. */ __( 'Tipos de %s', 'frave' ), $frave_group_term->name ) ); ?>">
								<?php foreach ( $frave_group['children'] as $frave_child ) : ?>
									<?php $frave_child_url = frave_term_products_url( $frave_child ); ?>
									<li>
										<?php if ( '' !== $frave_child_url ) : ?>
											<a class="line-chip line-chip--link" href="<?php echo esc_url( $frave_child_url ); ?>"><?php echo esc_html( $frave_child->name ); ?><?php echo frave_icon( 'arrow-up-right' ); // phpcs:ignore WordPress.Security.EscapeOutput -- static SVG. ?></a>
										<?php else : ?>
											<span class="line-chip"><?php echo esc_html( $frave_child->name ); ?></span>
										<?php endif; ?>
									</li>
								<?php endforeach; ?>
							</ul>
						<?php endif; ?>
						<div class="line-group__actions">
							<?php if ( $frave_quote ) : ?>
								<a class="link-arrow" href="<?php echo esc_url( $frave_quote['url'] ); ?>"<?php echo $frave_quote['external'] ? ' target="_blank" rel="noopener"' : ''; ?>>
									<?php esc_html_e( 'Cotizar', 'frave' ); ?>
									<span class="screen-reader-text"><?php echo esc_html( $frave_group_term->name ); ?></span>
									<?php if ( $frave_quote['external'] ) : ?>
										<span class="screen-reader-text"><?php esc_html_e( '(se abre en una nueva pestaña)', 'frave' ); ?></span>
									<?php endif; ?>
									<?php echo frave_icon( 'arrow-right' ); // phpcs:ignore WordPress.Security.EscapeOutput -- static SVG. ?>
								</a>
							<?php endif; ?>
							<?php if ( '' !== $frave_products ) : ?>
								<a class="link-arrow link-arrow--muted" href="<?php echo esc_url( $frave_products ); ?>">
									<?php esc_html_e( 'Ver productos', 'frave' ); ?>
									<span class="screen-reader-text"><?php echo esc_html( $frave_group_term->name ); ?></span>
									<?php echo frave_icon( 'arrow-right' ); // phpcs:ignore WordPress.Security.EscapeOutput -- static SVG. ?>
								</a>
							<?php endif; ?>
						</div>
					</div>
				</li>
			<?php endforeach; ?>
		</ol>
	</div>
</section>
