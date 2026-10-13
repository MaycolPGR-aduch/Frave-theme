<?php
/**
 * Blog: the posts page (Ajustes → Lectura), its categories and the articles, with
 * breadcrumbs, reading time, share links and related posts.
 *
 * The blog appears in the menus once it has published posts, so a fresh site never links
 * to an empty page. WordPress's sample post («Hello world!») is left out everywhere.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/** Published posts page chosen in Ajustes → Lectura, or 0. */
function frave_blog_page_id(): int {
	$page_id = (int) get_option( 'page_for_posts' );
	return $page_id && 'publish' === get_post_status( $page_id ) ? $page_id : 0;
}

/** Address of the blog, or '' without a posts page. */
function frave_blog_url(): string {
	$page_id = frave_blog_page_id();
	return $page_id ? (string) get_permalink( $page_id ) : '';
}

/** Title of the blog: the posts page title or «Blog». */
function frave_blog_title(): string {
	$page_id = frave_blog_page_id();
	return $page_id ? get_the_title( $page_id ) : __( 'Blog', 'frave' );
}

/** IDs of WordPress's sample post, if it is still there. */
function frave_blog_sample_post_ids(): array {
	static $ids = null;
	if ( null === $ids ) {
		$ids = get_posts(
			array(
				'post_type'      => 'post',
				'post_status'    => 'any',
				'post_name__in'  => array( 'hello-world', 'hola-mundo' ),
				'fields'         => 'ids',
				'posts_per_page' => 2,
			)
		);
	}
	return $ids;
}

/** Whether the blog has published posts besides the sample post: only then the menus link to it. */
function frave_blog_has_posts(): bool {
	static $has_posts = null;
	if ( null === $has_posts ) {
		$has_posts = frave_blog_url() !== '' && (bool) get_posts(
			array(
				'post_type'      => 'post',
				'post_status'    => 'publish',
				'post__not_in'   => frave_blog_sample_post_ids(),
				'fields'         => 'ids',
				'posts_per_page' => 1,
			)
		);
	}
	return $has_posts;
}

/** The sample post stays out of the blog, its categories and the search. */
function frave_blog_query( WP_Query $query ): void {
	if ( is_admin() || ! $query->is_main_query() || ! ( $query->is_home() || $query->is_category() || $query->is_tag() || $query->is_search() ) ) {
		return;
	}
	$sample = frave_blog_sample_post_ids();
	if ( $sample ) {
		$query->set( 'post__not_in', array_merge( (array) $query->get( 'post__not_in' ), $sample ) );
	}
}
add_action( 'pre_get_posts', 'frave_blog_query' );

/** Main category of a post: the first one other than the default category, or the default. */
function frave_post_category( $post = null ): ?WP_Term {
	$categories = get_the_category( $post ? get_post( $post )->ID : 0 );
	if ( ! $categories ) {
		return null;
	}
	$default = (int) get_option( 'default_category' );
	foreach ( $categories as $category ) {
		if ( $category->term_id !== $default ) {
			return $category;
		}
	}
	return $categories[0];
}

/** Minutes to read a post, at about 200 words per minute. */
function frave_reading_minutes( $post = null ): int {
	$content = wp_strip_all_tags( strip_shortcodes( (string) get_post_field( 'post_content', $post ) ) );
	$words   = count( preg_split( '/\s+/u', trim( $content ), -1, PREG_SPLIT_NO_EMPTY ) );
	return max( 1, (int) ceil( $words / 200 ) );
}

/**
 * Date of a post as «13 de octubre de 2026», whatever the date format in Ajustes →
 * Generales (WordPress installs default to the English «October 13, 2026»).
 */
function frave_post_date( $post = null ): string {
	return (string) get_the_date( _x( 'j \d\e F \d\e Y', 'post date format', 'frave' ), $post );
}

/** «13 de octubre de 2026 · 4 min de lectura», with the date as <time>. */
function frave_post_meta_html( $post = null ): string {
	$minutes = frave_reading_minutes( $post );
	return sprintf(
		'<time datetime="%1$s">%2$s</time><span aria-hidden="true"> · </span><span>%3$s</span>',
		esc_attr( get_the_date( 'Y-m-d', $post ) ),
		esc_html( frave_post_date( $post ) ),
		/* translators: %d: minutes. */
		esc_html( sprintf( _n( '%d min de lectura', '%d min de lectura', $minutes, 'frave' ), $minutes ) )
	);
}

/** Categories with published posts (the sample post does not count), for the topic links of the blog. */
function frave_blog_categories(): array {
	$terms = get_terms(
		array(
			'taxonomy'   => 'category',
			'hide_empty' => true,
			'orderby'    => 'count',
			'order'      => 'DESC',
		)
	);
	if ( is_wp_error( $terms ) ) {
		return array();
	}
	$sample = frave_blog_sample_post_ids();
	return array_values(
		array_filter(
			$terms,
			static fn( WP_Term $term ): bool => ! $sample || (bool) get_posts(
				array(
					'cat'            => $term->term_id,
					'post__not_in'   => $sample,
					'fields'         => 'ids',
					'posts_per_page' => 1,
				)
			)
		)
	);
}

/**
 * Trail of the current blog page: Inicio › Blog › Categoría › Artículo. The last item is
 * the current page (no URL).
 *
 * @return array<int, array{label: string, url: string}>
 */
function frave_breadcrumb_items(): array {
	$items = array(
		array(
			'label' => __( 'Inicio', 'frave' ),
			'url'   => home_url( '/' ),
		),
	);
	$blog  = array(
		'label' => frave_blog_title(),
		'url'   => frave_blog_url(),
	);

	if ( is_home() ) {
		$items[] = array(
			'label' => $blog['label'],
			'url'   => '',
		);
	} elseif ( is_category() ) {
		$term = get_queried_object();
		if ( '' !== $blog['url'] ) {
			$items[] = $blog;
		}
		foreach ( array_reverse( get_ancestors( $term->term_id, 'category' ) ) as $ancestor ) {
			$items[] = array(
				'label' => get_cat_name( $ancestor ),
				'url'   => (string) get_category_link( $ancestor ),
			);
		}
		$items[] = array(
			'label' => single_cat_title( '', false ),
			'url'   => '',
		);
	} elseif ( is_singular( 'post' ) ) {
		if ( '' !== $blog['url'] ) {
			$items[] = $blog;
		}
		$category = frave_post_category();
		if ( $category ) {
			$items[] = array(
				'label' => $category->name,
				'url'   => (string) get_category_link( $category ),
			);
		}
		$items[] = array(
			'label' => get_the_title(),
			'url'   => '',
		);
	} else {
		$items[] = array(
			'label' => wp_strip_all_tags( get_the_archive_title() ),
			'url'   => '',
		);
	}
	return $items;
}

/** Breadcrumb of the blog pages, in the page hero. */
function frave_breadcrumb(): void {
	$items = frave_breadcrumb_items();
	echo '<nav class="breadcrumb" aria-label="' . esc_attr__( 'Migas de pan', 'frave' ) . '"><ol>';
	foreach ( $items as $item ) {
		if ( '' === $item['url'] ) {
			printf( '<li><span aria-current="page">%s</span></li>', esc_html( $item['label'] ) );
		} else {
			printf( '<li><a href="%1$s">%2$s</a></li>', esc_url( $item['url'] ), esc_html( $item['label'] ) );
		}
	}
	echo '</ol></nav>';
}

/**
 * Share links of a post. WhatsApp first: it is how most links travel in Peru.
 *
 * @return array<string, array{label: string, url: string}>
 */
function frave_share_links( $post = null ): array {
	$url   = (string) get_permalink( $post );
	$title = wp_strip_all_tags( get_the_title( $post ) );
	return array(
		'whatsapp' => array(
			'label' => __( 'WhatsApp', 'frave' ),
			'url'   => 'https://wa.me/?text=' . rawurlencode( $title . ' ' . $url ),
		),
		'linkedin' => array(
			'label' => __( 'LinkedIn', 'frave' ),
			'url'   => 'https://www.linkedin.com/sharing/share-offsite/?url=' . rawurlencode( $url ),
		),
		'facebook' => array(
			'label' => __( 'Facebook', 'frave' ),
			'url'   => 'https://www.facebook.com/sharer/sharer.php?u=' . rawurlencode( $url ),
		),
		'email'    => array(
			'label' => __( 'Correo', 'frave' ),
			'url'   => 'mailto:?subject=' . rawurlencode( $title ) . '&body=' . rawurlencode( $url ),
		),
	);
}

/** Logo of each network for the share buttons (decorative, the label names the network). */
function frave_share_icon( string $network ): string {
	if ( 'whatsapp' === $network ) {
		return frave_whatsapp_icon();
	}
	return 'email' === $network ? frave_icon( 'mail' ) : frave_brand_icon( $network );
}

/**
 * Posts related to $post: same main category first, then the latest ones.
 *
 * @return WP_Post[]
 */
function frave_related_posts( $post = null, int $count = 3 ): array {
	$post    = get_post( $post );
	$exclude = array_merge( array( $post->ID ), frave_blog_sample_post_ids() );
	$related = array();

	$category = frave_post_category( $post );
	if ( $category ) {
		$related = get_posts(
			array(
				'numberposts'  => $count,
				'cat'          => $category->term_id,
				'post__not_in' => $exclude,
			)
		);
	}
	if ( count( $related ) < $count ) {
		$related = array_merge(
			$related,
			get_posts(
				array(
					'numberposts'  => $count - count( $related ),
					'post__not_in' => array_merge( $exclude, wp_list_pluck( $related, 'ID' ) ),
				)
			)
		);
	}
	return $related;
}

/** Shorter excerpts with an ellipsis instead of «[…]». */
function frave_excerpt_length(): int {
	return 28;
}
add_filter( 'excerpt_length', 'frave_excerpt_length' );

function frave_excerpt_more(): string {
	return '…';
}
add_filter( 'excerpt_more', 'frave_excerpt_more' );

/** Lead of the blog: the posts page excerpt, or a default. */
function frave_blog_lead(): string {
	$page_id = frave_blog_page_id();
	if ( $page_id && has_excerpt( $page_id ) ) {
		return get_the_excerpt( $page_id );
	}
	return __( 'Tendencias, guías técnicas y novedades para crear mejores productos con sabores, fragancias y envases.', 'frave' );
}

/** Pages need an excerpt field for the blog lead and their own search description. */
function frave_blog_page_excerpts(): void {
	add_post_type_support( 'page', 'excerpt' );
}
add_action( 'init', 'frave_blog_page_excerpts' );
