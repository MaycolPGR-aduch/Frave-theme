<?php
/**
 * Search and sharing metadata while no SEO plugin is active: a meta description and Open
 * Graph tags (the preview WhatsApp, Facebook or LinkedIn show for a shared link), built from
 * each page's own copy or its excerpt; canonical addresses for listings; and structured
 * data (Organization, WebSite, BlogPosting, BreadcrumbList) for search engines. A known SEO
 * plugin takes over all of it as soon as it is active. Also a default favicon (the isotype)
 * until a site icon is set in the Customizer.
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
	} elseif ( is_home() ) {
		$description = frave_blog_lead();
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

	// The excerpt of a page («Extracto» in the editor) replaces its description for search engines.
	$queried = get_queried_object();
	if ( is_page() && $queried instanceof WP_Post && has_excerpt( $queried ) ) {
		$description = $queried->post_excerpt;
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
	if ( is_home() && '' !== frave_blog_url() ) {
		return frave_blog_url();
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
		array( 'property', 'og:type', is_singular( 'post' ) ? 'article' : 'website' ),
		array( 'property', 'og:locale', 'es_PE' ),
		array( 'property', 'og:site_name', get_bloginfo( 'name' ) ),
		array( 'property', 'og:title', wp_get_document_title() ),
		array( 'property', 'og:description', $data['description'] ),
		array( 'property', 'og:url', frave_seo_url() ),
		array( 'property', 'og:image', $data['image'] ),
		array( 'name', 'twitter:card', 'summary_large_image' ),
	);
	if ( is_singular( 'post' ) ) {
		$tags[] = array( 'property', 'article:published_time', (string) get_the_date( 'c' ) );
		$tags[] = array( 'property', 'article:modified_time', (string) get_the_modified_date( 'c' ) );
	}
	foreach ( $tags as list( $attribute, $key, $value ) ) {
		if ( '' === $value ) {
			continue;
		}
		printf( '<meta %1$s="%2$s" content="%3$s">' . "\n", esc_attr( $attribute ), esc_attr( $key ), esc_attr( $value ) );
	}
}
add_action( 'wp_head', 'frave_seo_head', 2 );

/**
 * Canonical address of listings (blog, categories, the catalog), which WordPress only prints
 * for single pages. Only on their first page and without filters: filtered views are
 * noindex (frave_catalog_robots) and later pages are their own address.
 */
function frave_seo_canonical(): void {
	if ( frave_seo_plugin_active() || is_singular() || is_paged() || is_search() || is_404() ) {
		return;
	}
	$is_shop = function_exists( 'is_shop' ) && is_shop();
	if ( ! ( is_home() || is_category() || is_tag() || is_tax() || $is_shop ) ) {
		return;
	}
	if ( frave_catalog_active_filter_count() > 0 ) {
		return;
	}
	printf( '<link rel="canonical" href="%s">' . "\n", esc_url( frave_seo_url() ) );
}
add_action( 'wp_head', 'frave_seo_canonical', 2 );

/** Search results are not pages to rank: crawlers may follow their links, not index them. */
function frave_seo_search_robots( array $robots ): array {
	if ( is_search() ) {
		$robots['noindex'] = true;
		$robots['follow']  = true;
	}
	return $robots;
}
add_filter( 'wp_robots', 'frave_seo_search_robots' );

/** The company, for the structured data: name, logo, contact details and social profiles. */
function frave_schema_organization(): array {
	$channels     = frave_contact_channels();
	$organization = array(
		'@type'       => 'Organization',
		'@id'         => home_url( '/#organization' ),
		'name'        => get_bloginfo( 'name' ),
		'url'         => home_url( '/' ),
		'logo'        => array(
			'@type'  => 'ImageObject',
			'url'    => frave_asset_url( 'assets/images/frave-logo.png' ),
			'width'  => 440,
			'height' => 264,
		),
		'description' => get_bloginfo( 'description' ),
	);
	if ( isset( $channels['email'] ) ) {
		$organization['email'] = $channels['email']['value'];
	}
	$phone = $channels['phone']['value'] ?? ( $channels['whatsapp']['value'] ?? '' );
	if ( '' !== $phone ) {
		$organization['telephone'] = $phone;
	}
	if ( isset( $channels['address'] ) ) {
		$organization['address'] = array(
			'@type'          => 'PostalAddress',
			'streetAddress'  => preg_replace( '/\s+/', ' ', $channels['address']['value'] ),
			'addressCountry' => 'PE',
		);
	}
	$profiles = array_values( wp_list_pluck( frave_social_links(), 'url' ) );
	if ( $profiles ) {
		$organization['sameAs'] = $profiles;
	}
	return array_filter( $organization );
}

/** Structured data of the current page, as the items of a JSON-LD graph. */
function frave_schema_graph(): array {
	$graph = array(
		frave_schema_organization(),
		array(
			'@type'      => 'WebSite',
			'@id'        => home_url( '/#website' ),
			'url'        => home_url( '/' ),
			'name'       => get_bloginfo( 'name' ),
			'inLanguage' => 'es-PE',
			'publisher'  => array( '@id' => home_url( '/#organization' ) ),
		),
	);

	if ( is_singular( 'post' ) ) {
		$post    = get_queried_object();
		$article = array(
			'@type'            => 'BlogPosting',
			'@id'              => get_permalink( $post ) . '#article',
			'headline'         => wp_strip_all_tags( get_the_title( $post ) ),
			'description'      => frave_seo_data()['description'],
			'datePublished'    => get_the_date( 'c', $post ),
			'dateModified'     => get_the_modified_date( 'c', $post ),
			'mainEntityOfPage' => get_permalink( $post ),
			'inLanguage'       => 'es-PE',
			// Author archives are off (frave-peru): articles are signed by the company.
			'author'           => array( '@id' => home_url( '/#organization' ) ),
			'publisher'        => array( '@id' => home_url( '/#organization' ) ),
		);
		if ( has_post_thumbnail( $post ) ) {
			$article['image'] = get_the_post_thumbnail_url( $post, 'full' );
		}
		$category = frave_post_category( $post );
		if ( $category ) {
			$article['articleSection'] = $category->name;
		}
		$graph[] = $article;
	}

	// The same trail as the visible breadcrumb of the blog pages.
	if ( is_home() || is_category() || is_singular( 'post' ) ) {
		$elements = array();
		foreach ( frave_breadcrumb_items() as $position => $item ) {
			$elements[] = array(
				'@type'    => 'ListItem',
				'position' => $position + 1,
				'name'     => $item['label'],
				'item'     => '' !== $item['url'] ? $item['url'] : frave_seo_url(),
			);
		}
		$graph[] = array(
			'@type'           => 'BreadcrumbList',
			'itemListElement' => $elements,
		);
	}

	return $graph;
}

function frave_schema_head(): void {
	if ( frave_seo_plugin_active() || is_404() ) {
		return;
	}
	$data = array(
		'@context' => 'https://schema.org',
		'@graph'   => frave_schema_graph(),
	);
	echo '<script type="application/ld+json">' . wp_json_encode( $data, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE ) . '</script>' . "\n";
}
add_action( 'wp_head', 'frave_schema_head', 4 );

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
