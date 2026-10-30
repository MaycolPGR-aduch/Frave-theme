<?php
/** Signature under an article: Frave's technical team, with a link to its services. */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$frave_services = function_exists( 'frave_company_page_url' ) ? frave_company_page_url( FRAVE_TEMPLATE_EXPERTISE ) : '';
?>
<div class="article-author">
	<span class="article-author__mark" aria-hidden="true"><?php echo frave_isotype(); // phpcs:ignore WordPress.Security.EscapeOutput -- static SVG. ?></span>
	<div>
		<p class="article-author__name"><?php esc_html_e( 'Equipo técnico Frave', 'frave' ); ?></p>
		<p class="article-author__text"><?php esc_html_e( 'Acompañamos a marcas y fabricantes en la elección de fragancias, sabores y envases, con fabricantes internacionales y pruebas en su propia base.', 'frave' ); ?></p>
		<?php if ( '' !== $frave_services ) : ?>
			<a class="link-arrow" href="<?php echo esc_url( $frave_services ); ?>"><?php esc_html_e( 'Conoce cómo trabajamos', 'frave' ); ?><?php echo frave_icon( 'arrow-right' ); // phpcs:ignore WordPress.Security.EscapeOutput -- static SVG. ?></a>
		<?php endif; ?>
	</div>
</div>
