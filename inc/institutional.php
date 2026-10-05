<?php
/**
 * Institutional pages: Contacto, Preguntas frecuentes and legal texts (privacy, terms,
 * shipping, returns). Content is written in the block editor; the page templates in
 * /page-templates add the layout, an index of sections, FAQ structured data and links.
 * Starting structures live in /patterns (Frave: páginas institucionales).
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

const FRAVE_TEMPLATE_CONTACT = 'page-templates/contacto.php';
const FRAVE_TEMPLATE_FAQ     = 'page-templates/preguntas-frecuentes.php';
const FRAVE_TEMPLATE_LEGAL   = 'page-templates/politica.php';

function frave_register_institutional(): void {
	register_nav_menus( array( 'legal' => __( 'Enlaces legales del pie', 'frave' ) ) );
	register_block_pattern_category( 'frave-paginas', array( 'label' => __( 'Frave: páginas institucionales', 'frave' ) ) );
}
// Before the theme's /patterns are registered, so they land in this category.
add_action( 'init', 'frave_register_institutional', 9 );

/* ---------- Section anchors and index ---------- */

/** Anchor for a heading text, unique within the page. */
function frave_heading_anchor( string $text, array &$used ): string {
	$base   = sanitize_title( wp_strip_all_tags( $text ) );
	$base   = '' !== $base ? $base : 'seccion';
	$anchor = $base;
	for ( $i = 2; isset( $used[ $anchor ] ); $i++ ) {
		$anchor = $base . '-' . $i;
	}
	$used[ $anchor ] = true;
	return $anchor;
}

function frave_page_has_index(): bool {
	return is_page_template( FRAVE_TEMPLATE_FAQ ) || is_page_template( FRAVE_TEMPLATE_LEGAL );
}

/**
 * Second-level headings of a post's blocks, with the anchors the page will use.
 *
 * @return array<int, array{text: string, anchor: string}>
 */
function frave_page_sections( WP_Post $post ): array {
	$sections = array();
	$used     = array();
	$walk     = static function ( array $blocks ) use ( &$walk, &$sections, &$used ): void {
		foreach ( $blocks as $block ) {
			if ( 'core/heading' === $block['blockName'] && 2 === (int) ( $block['attrs']['level'] ?? 2 ) ) {
				$text = trim( wp_strip_all_tags( $block['innerHTML'] ) );
				if ( '' !== $text ) {
					$anchor = $block['attrs']['anchor'] ?? '';
					if ( '' === $anchor ) {
						$anchor = frave_heading_anchor( $text, $used );
					}
					$sections[] = array( 'text' => $text, 'anchor' => $anchor );
				}
			}
			if ( ! empty( $block['innerBlocks'] ) ) {
				$walk( $block['innerBlocks'] );
			}
		}
	};
	$walk( parse_blocks( $post->post_content ) );
	return $sections;
}

/** Give second-level headings an id on indexed pages, matching frave_page_sections(). */
function frave_heading_ids( string $content, array $block ): string {
	static $used = array();
	if ( ! frave_page_has_index() || ! in_the_loop() || 2 !== (int) ( $block['attrs']['level'] ?? 2 ) || ! empty( $block['attrs']['anchor'] ) ) {
		return $content;
	}
	$text = trim( wp_strip_all_tags( $block['innerHTML'] ) );
	if ( '' === $text ) {
		return $content;
	}
	$processor = new WP_HTML_Tag_Processor( $content );
	if ( $processor->next_tag( 'h2' ) && null === $processor->get_attribute( 'id' ) ) {
		$processor->set_attribute( 'id', frave_heading_anchor( $text, $used ) );
		return $processor->get_updated_html();
	}
	return $content;
}
add_filter( 'render_block_core/heading', 'frave_heading_ids', 10, 2 );

/** "En esta página" index; printed only when there are at least three sections. */
function frave_page_index( WP_Post $post ): void {
	$sections = frave_page_sections( $post );
	if ( count( $sections ) < 3 ) {
		return;
	}
	?>
	<nav class="page-index" aria-labelledby="page-index-title">
		<h2 class="page-index__title" id="page-index-title"><?php esc_html_e( 'En esta página', 'frave' ); ?></h2>
		<ol>
			<?php foreach ( $sections as $section ) : ?>
				<li><a href="#<?php echo esc_attr( $section['anchor'] ); ?>"><?php echo esc_html( $section['text'] ); ?></a></li>
			<?php endforeach; ?>
		</ol>
	</nav>
	<?php
}

/* ---------- FAQ structured data ---------- */

/**
 * Questions and answers from the core Details blocks of a post.
 *
 * @return array<int, array{question: string, answer: string}>
 */
function frave_faq_items( WP_Post $post ): array {
	$items = array();
	$walk  = static function ( array $blocks ) use ( &$walk, &$items ): void {
		foreach ( $blocks as $block ) {
			if ( 'core/details' === $block['blockName'] ) {
				// The summary is sourced from the <summary> element, not stored in the block attributes.
				$question = preg_match( '#<summary[^>]*>(.*?)</summary>#s', $block['innerHTML'], $match ) ? trim( wp_strip_all_tags( $match[1] ) ) : '';
				$answer   = trim( preg_replace( '/\s+/', ' ', wp_strip_all_tags( implode( ' ', array_map( 'render_block', $block['innerBlocks'] ) ) ) ) );
				if ( '' !== $question && '' !== $answer ) {
					$items[] = array( 'question' => $question, 'answer' => $answer );
				}
			} elseif ( ! empty( $block['innerBlocks'] ) ) {
				$walk( $block['innerBlocks'] );
			}
		}
	};
	$walk( parse_blocks( $post->post_content ) );
	return $items;
}

/** FAQPage JSON-LD built from the page's Details blocks; skipped while answers are placeholders. */
function frave_faq_structured_data(): void {
	if ( ! is_page_template( FRAVE_TEMPLATE_FAQ ) ) {
		return;
	}
	$post  = get_queried_object();
	$items = $post instanceof WP_Post ? frave_faq_items( $post ) : array();
	$items = array_filter( $items, static fn( $item ) => false === stripos( $item['answer'], '[PENDIENTE' ) );
	if ( ! $items ) {
		return;
	}
	$data = array(
		'@context'   => 'https://schema.org',
		'@type'      => 'FAQPage',
		'mainEntity' => array_values(
			array_map(
				static fn( $item ) => array(
					'@type'          => 'Question',
					'name'           => $item['question'],
					'acceptedAnswer' => array( '@type' => 'Answer', 'text' => $item['answer'] ),
				),
				$items
			)
		),
	);
	echo '<script type="application/ld+json">' . wp_json_encode( $data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES ) . '</script>' . "\n";
}
add_action( 'wp_head', 'frave_faq_structured_data' );

/* ---------- Links between pages ---------- */

/** First published page that uses a template, e.g. to link to the contact page. */
function frave_page_with_template( string $template ): ?WP_Post {
	$pages = get_pages(
		array(
			'meta_key'    => '_wp_page_template', // phpcs:ignore WordPress.DB.SlowDBQuery -- small, cached query on pages.
			'meta_value'  => $template, // phpcs:ignore WordPress.DB.SlowDBQuery
			'post_status' => 'publish',
			'number'      => 1,
		)
	);
	return $pages ? $pages[0] : null;
}

/**
 * Published legal pages: privacy (WordPress setting), terms (WooCommerce setting) and every
 * page using the legal template, plus the Libro de Reclamaciones when available.
 *
 * @return array<int, array{title: string, url: string, id: int}>
 */
function frave_legal_links(): array {
	$ids = array_filter(
		array(
			(int) get_option( 'wp_page_for_privacy_policy' ),
			function_exists( 'wc_terms_and_conditions_page_id' ) ? (int) wc_terms_and_conditions_page_id() : 0,
		)
	);
	$templated = get_pages(
		array(
			'meta_key'    => '_wp_page_template', // phpcs:ignore WordPress.DB.SlowDBQuery -- small, cached query on pages.
			'meta_value'  => FRAVE_TEMPLATE_LEGAL, // phpcs:ignore WordPress.DB.SlowDBQuery
			'post_status' => 'publish',
			'sort_column' => 'menu_order,post_title',
		)
	);
	$pages = array_filter(
		array_map( 'get_post', array_unique( array_merge( $ids, wp_list_pluck( $templated, 'ID' ) ) ) ),
		static fn( $page ) => $page instanceof WP_Post && 'publish' === $page->post_status
	);
	// Page order (Páginas → Atributos de página → Orden), then title.
	usort( $pages, static fn( WP_Post $a, WP_Post $b ) => array( $a->menu_order, $a->post_title ) <=> array( $b->menu_order, $b->post_title ) );

	$links = array();
	foreach ( $pages as $page ) {
		$links[] = array( 'title' => get_the_title( $page ), 'url' => (string) get_permalink( $page ), 'id' => (int) $page->ID );
	}
	$claims = function_exists( 'frave_peru_claims_page_url' ) ? frave_peru_claims_page_url() : '';
	if ( $claims ) {
		$links[] = array( 'title' => __( 'Libro de Reclamaciones', 'frave' ), 'url' => $claims, 'id' => 0 );
	}
	return $links;
}

/** Footer legal links: the "legal" menu if assigned, otherwise the published legal pages. */
function frave_legal_menu(): void {
	if ( has_nav_menu( 'legal' ) ) {
		wp_nav_menu(
			array(
				'theme_location' => 'legal',
				'container'            => 'nav',
				'container_aria_label' => __( 'Enlaces legales', 'frave' ),
				'menu_class'     => 'legal-menu',
				'depth'          => 1,
			)
		);
		return;
	}
	$links = frave_legal_links();
	if ( ! $links ) {
		return;
	}
	echo '<nav aria-label="' . esc_attr__( 'Enlaces legales', 'frave' ) . '"><ul class="legal-menu">';
	foreach ( $links as $link ) {
		printf( '<li><a href="%1$s">%2$s</a></li>', esc_url( $link['url'] ), esc_html( $link['title'] ) );
	}
	echo '</ul></nav>';
}

/** The contact template adds the plugin's form, so the plugin must process it there too. */
function frave_contact_template_has_form( bool $is_contact_page ): bool {
	return $is_contact_page || is_page_template( FRAVE_TEMPLATE_CONTACT );
}
add_filter( 'frave_peru_is_contact_page', 'frave_contact_template_has_form' );
