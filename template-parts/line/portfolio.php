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
				get_template_part(
					'template-parts/components/term-card',
					null,
					array(
						'term'   => $frave_group['term'],
						'number' => $frave_index + 1,
					)
				);
				?>
			<?php endforeach; ?>
		</ol>
	</div>
</section>
