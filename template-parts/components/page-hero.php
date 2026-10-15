<?php
/**
 * Sand band at the top of interior pages. It starts behind the floating header.
 *
 * Arguments:
 * - eyebrow:    small label above the title.
 * - title:      page title (basic HTML allowed, e.g. <strong> for emphasis).
 * - lead:       optional introduction (HTML allowed).
 * - meta:       optional line under the title, already escaped HTML (e.g. a <time>).
 * - breadcrumb: print WooCommerce's breadcrumb above the eyebrow.
 * - trail:      print the blog breadcrumb (frave_breadcrumb) above the eyebrow.
 * - search:     add the product search form.
 * - facts:      optional list of [ icon, text ] shown as small highlights under the lead.
 * - image:      optional <img> HTML, already escaped, shown beside the text on wide screens.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$frave_hero       = wp_parse_args(
	$args ?? array(),
	array(
		'eyebrow'    => '',
		'title'      => '',
		'lead'       => '',
		'meta'       => '',
		'breadcrumb' => false,
		'trail'      => false,
		'search'     => false,
		'facts'      => array(),
		'image'      => '',
	)
);
$frave_hero_media = '' !== $frave_hero['image'];
?>
<header class="page-hero<?php echo $frave_hero_media ? ' page-hero--media' : ''; ?>">
	<span class="orbit" aria-hidden="true"></span>
	<span class="orbit orbit--inner orbit--soft" aria-hidden="true"></span>
	<div class="page-hero__decor"><?php echo frave_isotype(); // phpcs:ignore WordPress.Security.EscapeOutput -- static SVG. ?></div>
	<?php if ( $frave_hero_media ) : ?>
		<div class="container page-hero__layout">
	<?php endif; ?>
	<div class="<?php echo $frave_hero_media ? '' : 'container '; ?>page-hero__inner">
		<?php
		if ( $frave_hero['breadcrumb'] && function_exists( 'woocommerce_breadcrumb' ) ) {
			woocommerce_breadcrumb();
		} elseif ( $frave_hero['trail'] ) {
			frave_breadcrumb();
		}
		?>
		<?php if ( '' !== $frave_hero['eyebrow'] ) : ?>
			<p class="eyebrow"><?php echo esc_html( $frave_hero['eyebrow'] ); ?></p>
		<?php endif; ?>
		<h1><?php echo wp_kses_post( $frave_hero['title'] ); ?></h1>
		<?php if ( '' !== trim( wp_strip_all_tags( (string) $frave_hero['lead'] ) ) ) : ?>
			<div class="page-hero__lead"><?php echo wp_kses_post( wpautop( $frave_hero['lead'] ) ); ?></div>
		<?php endif; ?>
		<?php if ( '' !== $frave_hero['meta'] ) : ?>
			<p class="page-hero__meta"><?php echo wp_kses_post( $frave_hero['meta'] ); ?></p>
		<?php endif; ?>
		<?php if ( $frave_hero['facts'] ) : ?>
			<ul class="page-hero__facts">
				<?php foreach ( $frave_hero['facts'] as list( $frave_fact_icon, $frave_fact_text ) ) : ?>
					<li><?php echo frave_icon( $frave_fact_icon ); // phpcs:ignore WordPress.Security.EscapeOutput -- static SVG. ?><span><?php echo esc_html( $frave_fact_text ); ?></span></li>
				<?php endforeach; ?>
			</ul>
		<?php endif; ?>
		<?php if ( $frave_hero['search'] ) : ?>
			<div class="page-hero__search">
				<?php get_template_part( 'template-parts/components/product-search-form' ); ?>
			</div>
		<?php endif; ?>
	</div>
	<?php if ( $frave_hero_media ) : ?>
			<div class="page-hero__media media-frame">
				<?php echo $frave_hero['image']; // phpcs:ignore WordPress.Security.EscapeOutput -- escaped by the caller. ?>
			</div>
		</div>
	<?php endif; ?>
</header>
