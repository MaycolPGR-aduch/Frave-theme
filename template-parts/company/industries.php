<?php
/** "Industrias que atendemos": the industries each product line serves, with a link to the line. */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$frave_lines = frave_product_lines();
?>
<section class="section company-industries has-section-bg" aria-labelledby="company-industries-title">
	<?php
	get_template_part(
		'template-parts/components/section-bg',
		null,
		array(
			'image'  => 'textura-luz-sombra',
			'width'  => 2400,
			'height' => 1350,
			'small'  => 1200,
			'veil'   => 'white',
		)
	);
	?>
	<div class="container">
		<div class="section-head" data-reveal="up">
			<p class="eyebrow"><?php esc_html_e( 'A quién servimos', 'frave' ); ?></p>
			<h2 id="company-industries-title"><?php echo frave_emphasis( frave_company_text( 'frave_expertise_industries_title' ) ); // phpcs:ignore WordPress.Security.EscapeOutput -- escaped in the helper. ?></h2>
			<p class="lead"><?php echo esc_html( frave_company_text( 'frave_expertise_industries_text' ) ); ?></p>
		</div>
		<ul class="company-industries__grid" data-reveal-group>
			<?php foreach ( frave_company_industries() as $frave_slug => $frave_industries ) : ?>
				<?php
				$frave_term = frave_line_term( $frave_slug );
				$frave_name = $frave_term ? $frave_term->name : ( $frave_lines[ $frave_slug ] ?? $frave_slug );
				$frave_url  = frave_line_url( $frave_slug );
				?>
				<li class="card company-industry company-industry--<?php echo esc_attr( $frave_slug ); ?>">
					<span class="icon-tile icon-tile--lg"><?php echo frave_icon( frave_product_line_icon( $frave_slug ) ); // phpcs:ignore WordPress.Security.EscapeOutput -- static SVG. ?></span>
					<h3><?php echo esc_html( $frave_name ); ?></h3>
					<ul class="company-industry__list">
						<?php foreach ( $frave_industries as $frave_industry ) : ?>
							<li><?php echo esc_html( $frave_industry ); ?></li>
						<?php endforeach; ?>
					</ul>
					<?php if ( '' !== $frave_url ) : ?>
						<a class="link-arrow" href="<?php echo esc_url( $frave_url ); ?>"><?php echo esc_html( sprintf( /* translators: %s: product line name. */ __( 'Conocer %s', 'frave' ), $frave_name ) ); ?><?php echo frave_icon( 'arrow-right' ); // phpcs:ignore WordPress.Security.EscapeOutput -- static SVG. ?></a>
					<?php endif; ?>
				</li>
			<?php endforeach; ?>
		</ul>
	</div>
</section>
