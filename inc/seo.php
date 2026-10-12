<?php
/**
 * Basic search and sharing metadata while no SEO plugin is active: a meta description and
 * Open Graph tags (the preview WhatsApp, Facebook or LinkedIn show for a shared link),
 * built from each page's own copy. An SEO plugin (phase 6) takes over as soon as it is
 * active. Also a default favicon (the isotype) until a site icon is set in the Customizer.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/** Whether a known SEO plugin prints these tags itself. */
function frave_seo_plugin_active(): bool {
	return defined( 'WPSEO_VERSION' ) || defined( 'RANK_MATH_VERSION' ) || defined( 'AIOSEO_VERSION' ) || defined( 'SEOPRESS_VERSION' ) || defined( 'THE_SEO_FRAMEWORK_VERSION' );
}

/** Plain text cut at a word boundary to about $length characters. */
function frave_seo_trim( string $text, int $length = 160 ): string {
	$text = trim( preg_replace( '/\s+/u', ' ', wp_strip_all_tags( frave_plain( $text ) ) ) );
	if ( mb_strlen( $text ) <= $length ) {
		return $text;
	}
	$cut = mb_substr( $text, 0, $length - 1 );
	$cut = preg_replace( '/\s+\S*$/u', '', $cut );
	return rtrim( $cut, ' ,;:' ) . '…';
}

/**
 * Description and image of the current page.
 *
 * @return array{description: string, image: string}
 */
function frave_seo_data(): array {
	$description = '';
	$image       = '';
	// Share images are JPG (1200 × 630): link previews do not always show WebP.
	$share = static fn( string $name ): string => frave_asset_url( "assets/media/og/{$name}.jpg" );

	if ( is_front_page() ) {
		$description = frave_home_text( 'frave_home_hero_text' );
		$image       = frave_field( 'frave_home_hero_poster' ) ? frave_home_hero_media()['poster'] : $share( 'portada' );
	} elseif ( is_page_template( FRAVE_TEMPLATE_LINE ) ) {
		$slug        = frave_line_slug();
		$description = frave_line_text( 'lead', $slug );
		$image       = frave_field( 'frave_line_image_desktop' ) || ! frave_line_has_theme_image( $slug ) ? frave_line_images( $slug )['desktop'] : $share( $slug );
	} elseif ( is_page_template( FRAVE_TEMPLATE_ABOUT ) ) {
		$description = frave_company_text( 'frave_about_hero_text' );
		$image       = $share( 'empresa' );
	} elseif ( is_page_template( FRAVE_TEMPLATE_EXPERTISE ) ) {
		$description = frave_company_text( 'frave_expertise_hero_text' );
	} elseif ( is_page_template( FRAVE_TEMPLATE_SOLUTION ) ) {
		$slug        = frave_solution_slug();
		$description = frave_solution_text( 'lead', $slug );
		$images      = array(
			'perfumeria'                        => 'envases',
			'cosmetica-y-cuidado-personal'      => 'fragancias',
			'ambientacion-y-marketing-olfativo' => 'empresa',
			'alimentos-y-bebidas'               => 'sabores',
		);
		$image       = $share( $images[ $slug ] ?? 'portada' );
	} elseif ( is_page_template( FRAVE_TEMPLATE_SOLUTIONS ) ) {
		$description = __( 'Fragancias, sabores y envases según tu industria: perfumería, cosmética y cuidado personal, hogar, ambientación y alimentos y bebidas.', 'frave' );
	} elseif ( is_page_template( FRAVE_TEMPLATE_CONTACT ) ) {
		$description = __( 'Escríbenos o solicita una cotización de sabores, fragancias y envases para tu marca. Te respondemos por correo o por WhatsApp.', 'frave' );
	} elseif ( function_exists( 'is_product' ) && is_product() ) {
		$product     = wc_get_product( get_queried_object_id() );
		$description = $product ? $product->get_short_description() : '';
		$image       = $product && $product->get_image_id() ? (string) wp_get_attachment_image_url( $product->get_image_id(), 'large' ) : '';
	} elseif ( is_category() || is_tag() || is_tax() ) {
		$term        = get_queried_object();
		$description = $term instanceof WP_Term ? $term->description : '';
		$thumbnail   = $term instanceof WP_Term ? (int) get_term_meta( $term->term_id, 'thumbnail_id', true ) : 0;
		$image       = $thumbnail ? (string) wp_get_attachment_image_url( $thumbnail, 'large' ) : '';
	} elseif ( is_singular() ) {
		$post        = get_queried_object();
		$description = $post instanceof WP_Post ? ( has_excerpt( $post ) ? $post->post_excerpt : $post->post_content ) : '';
		$image       = has_post_thumbnail() ? (string) get_the_post_thumbnail_url( null, 'large' ) : '';
	}

	if ( '' === trim( wp_strip_all_tags( $description ) ) ) {
		$description = get_bloginfo( 'description' );
	}
	if ( '' === trim( $description ) ) {
		$description = frave_home_text( 'frave_home_hero_text' );
	}

	return array(
		'description' => frave_seo_trim( $description ),
		'image'       => '' !== $image ? $image : $share( 'portada' ),
	);
}

/** Address of the current page for og:url. */
function frave_seo_url(): string {
	if ( is_singular() ) {
		return (string) get_permalink();
	}
	if ( is_category() || is_tag() || is_tax() ) {
		$link = get_term_link( get_queried_object() );
		return is_wp_error( $link ) ? home_url( '/' ) : $link;
	}
	if ( function_exists( 'is_shop' ) && is_shop() ) {
		return frave_catalog_shop_url();
	}
	return home_url( '/' );
}

function frave_seo_head(): void {
	if ( frave_seo_plugin_active() || is_search() || is_404() ) {
		return;
	}
	$data = frave_seo_data();
	$tags = array(
		array( 'name', 'description', $data['description'] ),
		array( 'property', 'og:type', is_front_page() ? 'website' : 'article' ),
		array( 'property', 'og:locale', 'es_PE' ),
		array( 'property', 'og:site_name', get_bloginfo( 'name' ) ),
		array( 'property', 'og:title', wp_get_document_title() ),
		array( 'property', 'og:description', $data['description'] ),
		array( 'property', 'og:url', frave_seo_url() ),
		array( 'property', 'og:image', $data['image'] ),
		array( 'name', 'twitter:card', 'summary_large_image' ),
	);
	foreach ( $tags as list( $attribute, $key, $value ) ) {
		if ( '' === $value ) {
			continue;
		}
		printf( '<meta %1$s="%2$s" content="%3$s">' . "\n", esc_attr( $attribute ), esc_attr( $key ), esc_attr( $value ) );
	}
}
add_action( 'wp_head', 'frave_seo_head', 2 );

/** The isotype as favicon until a site icon is chosen in Apariencia → Personalizar. */
function frave_default_favicon(): void {
	if ( has_site_icon() ) {
		return;
	}
	printf( '<link rel="icon" href="%s" type="image/svg+xml">' . "\n", esc_url( frave_asset_url( 'assets/images/favicon.svg' ) ) );
	printf( '<link rel="icon" href="%s" sizes="512x512" type="image/png">' . "\n", esc_url( frave_asset_url( 'assets/images/favicon-512.png' ) ) );
	printf( '<link rel="apple-touch-icon" href="%s">' . "\n", esc_url( frave_asset_url( 'assets/images/favicon-180.png' ) ) );
}
add_action( 'wp_head', 'frave_default_favicon', 3 );
