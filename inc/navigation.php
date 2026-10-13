<?php
/**
 * Site navigation: fallback menus with the corporate structure, accessible submenus and
 * the header call to action.
 *
 * Menus assigned in Apariencia → Menús take precedence. Until then the fallbacks link
 * only to pages and categories that exist, so a fresh install never shows dead links.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/** Product lines of Frave, as product category slugs and labels. */
function frave_product_lines(): array {
	return array(
		'sabores'    => __( 'Sabores', 'frave' ),
		'fragancias' => __( 'Fragancias', 'frave' ),
		'envases'    => __( 'Envases', 'frave' ),
	);
}

/** URL of the published page with this slug, or ''. */
function frave_published_page_url( string $slug ): string {
	$page = get_page_by_path( $slug );
	return $page instanceof WP_Post && 'publish' === $page->post_status ? (string) get_permalink( $page ) : '';
}

/** Contact page (template Contacto), or '' while it is not published. */
function frave_contact_page_url(): string {
	$page = frave_page_with_template( FRAVE_TEMPLATE_CONTACT );
	return $page ? (string) get_permalink( $page ) : '';
}

/**
 * Items of the fallback main menu: Inicio, Nosotros, Líneas (one entry per product line
 * with a line page or a category with products, so no entry leads to an empty page),
 * Soluciones (the hub and one entry per industry), Experticia, Catálogo and Contacto.
 * Pages appear once they are published. With seven entries Inicio gives way, as the logo
 * already leads home and the floating header has room for six.
 *
 * @return array<int, array{label: string, url: string, children?: array}>
 */
function frave_primary_menu_fallback_items(): array {
	$items = array(
		array(
			'label' => __( 'Inicio', 'frave' ),
			'url'   => home_url( '/' ),
		),
	);

	$about = frave_company_page_url( FRAVE_TEMPLATE_ABOUT );
	$about = '' !== $about ? $about : frave_published_page_url( 'nosotros' );
	if ( $about ) {
		$items[] = array(
			'label' => __( 'Nosotros', 'frave' ),
			'url'   => $about,
		);
	}

	if ( class_exists( 'WooCommerce' ) ) {
		$lines = array();
		foreach ( frave_product_lines() as $slug => $label ) {
			$link = frave_line_url( $slug );
			if ( '' !== $link ) {
				$lines[] = array(
					'label' => $label,
					'url'   => $link,
					'icon'  => frave_product_line_icon( $slug ),
				);
			}
		}
		if ( $lines ) {
			$items[] = array(
				'label'    => __( 'Líneas', 'frave' ),
				'url'      => '',
				'children' => $lines,
			);
		}

		$solutions = array();
		foreach ( frave_solution_pages() as $solution_slug => $solution_page ) {
			$solutions[] = array(
				'label' => get_the_title( $solution_page ),
				'url'   => (string) get_permalink( $solution_page ),
				'icon'  => frave_solutions()[ $solution_slug ]['icon'],
			);
		}
		if ( $solutions ) {
			$items[] = array(
				'label'    => __( 'Soluciones', 'frave' ),
				'url'      => frave_solutions_url(),
				'children' => $solutions,
			);
		}

		$expertise = frave_company_page_url( FRAVE_TEMPLATE_EXPERTISE );
		if ( $expertise ) {
			$items[] = array(
				'label' => __( 'Experticia', 'frave' ),
				'url'   => $expertise,
			);
		}

		$items[] = array(
			'label' => frave_catalog_mode() ? __( 'Catálogo', 'frave' ) : __( 'Tienda', 'frave' ),
			'url'   => frave_catalog_shop_url(),
		);
	}

	if ( frave_blog_has_posts() ) {
		$items[] = array(
			'label' => frave_blog_title(),
			'url'   => frave_blog_url(),
		);
	}

	$contact = frave_contact_page_url();
	if ( $contact ) {
		$children = array();
		$whatsapp = frave_whatsapp_url( frave_whatsapp_message() );
		if ( $whatsapp ) {
			$children[] = array(
				'label'    => __( 'WhatsApp', 'frave' ),
				'url'      => $whatsapp,
				'external' => true,
				'icon'     => 'whatsapp',
			);
		}
		$faq = frave_page_with_template( FRAVE_TEMPLATE_FAQ );
		if ( $faq ) {
			$children[] = array(
				'label' => get_the_title( $faq ),
				'url'   => (string) get_permalink( $faq ),
				'icon'  => 'chat',
			);
		}
		$claims = function_exists( 'frave_peru_claims_page_url' ) ? frave_peru_claims_page_url() : '';
		if ( $claims ) {
			$children[] = array(
				'label' => __( 'Libro de Reclamaciones', 'frave' ),
				'url'   => $claims,
				'icon'  => 'book',
			);
		}
		if ( $children ) {
			array_unshift(
				$children,
				array(
					'label' => __( 'Formulario de contacto', 'frave' ),
					'url'   => $contact,
					'icon'  => 'mail',
				)
			);
		}
		$items[] = array(
			'label'    => __( 'Contacto', 'frave' ),
			'url'      => $children ? '' : $contact,
			'children' => $children,
			// The footer has its own WhatsApp and Libro de Reclamaciones links.
			'footer'   => array(
				'label' => __( 'Contacto', 'frave' ),
				'url'   => $contact,
			),
			'extra'    => $faq ? array(
				'label' => get_the_title( $faq ),
				'url'   => (string) get_permalink( $faq ),
			) : null,
		);
	}

	if ( frave_shop_enabled() ) {
		$items[] = array(
			'label' => __( 'Mi cuenta', 'frave' ),
			'url'   => wc_get_page_permalink( 'myaccount' ),
		);
	}

	return count( $items ) > 6 ? array_slice( $items, 1 ) : $items;
}

/** Whether $url is the requested page: same path and query, in any order, with or without a trailing slash. */
function frave_is_current_url( string $url ): bool {
	if ( '' === $url ) {
		return false;
	}
	$normalize = static function ( string $address ): string {
		$query = array();
		wp_parse_str( (string) wp_parse_url( $address, PHP_URL_QUERY ), $query );
		ksort( $query );
		return trailingslashit( (string) wp_parse_url( $address, PHP_URL_PATH ) ) . '?' . http_build_query( $query );
	};
	$request   = isset( $_SERVER['REQUEST_URI'] ) ? esc_url_raw( wp_unslash( $_SERVER['REQUEST_URI'] ) ) : '/';
	return $normalize( $url ) === $normalize( $request );
}

/** Same markup as wp_nav_menu(), so styles and scripts treat both menus alike. */
function frave_primary_menu_fallback(): void {
	echo '<ul class="menu-list">';
	foreach ( frave_primary_menu_fallback_items() as $item ) {
		$children = $item['children'] ?? array();
		$classes  = $children ? 'menu-item menu-item-has-children' : 'menu-item';
		$current  = frave_is_current_url( $item['url'] );
		echo '<li class="' . esc_attr( $classes . ( $current ? ' current-menu-item' : '' ) ) . '">';
		if ( '' === $item['url'] ) {
			frave_submenu_toggle( $item['label'], true );
		} else {
			printf( '<a href="%1$s"%3$s>%2$s</a>', esc_url( $item['url'] ), esc_html( $item['label'] ), $current ? ' aria-current="page"' : '' );
			if ( $children ) {
				frave_submenu_toggle( $item['label'], false );
			}
		}
		if ( $children ) {
			echo '<ul class="sub-menu">';
			foreach ( $children as $child ) {
				$external = ! empty( $child['external'] );
				printf(
					'<li class="menu-item"><a href="%1$s"%3$s>%5$s<span class="menu-item__text">%2$s%4$s</span></a></li>',
					esc_url( $child['url'] ),
					esc_html( $child['label'] ),
					$external ? ' target="_blank" rel="noopener"' : '',
					$external ? '<span class="screen-reader-text"> ' . esc_html__( '(se abre en una nueva pestaña)', 'frave' ) . '</span>' : '',
					frave_menu_icon_html( $child['icon'] ?? '' ) // phpcs:ignore WordPress.Security.EscapeOutput -- static SVG.
				);
			}
			echo '</ul>';
		}
		echo '</li>';
	}
	echo '</ul>';
}

/** Footer fallback: the main pages in one list; a group without its own page lists its entries. */
function frave_footer_menu_fallback(): void {
	$links = array();
	foreach ( frave_primary_menu_fallback_items() as $item ) {
		if ( isset( $item['footer'] ) ) {
			$links[] = $item['footer'];
			if ( ! empty( $item['extra'] ) ) {
				$links[] = $item['extra'];
			}
		} elseif ( '' === $item['url'] ) {
			array_push( $links, ...( $item['children'] ?? array() ) );
		} else {
			$links[] = $item;
		}
	}
	echo '<ul class="footer-menu">';
	foreach ( $links as $link ) {
		printf( '<li><a href="%1$s">%2$s</a></li>', esc_url( $link['url'] ), esc_html( $link['label'] ) );
	}
	echo '</ul>';
}

/**
 * Disclosure button of a submenu. A parent without its own page (custom link "#") is the
 * button itself; otherwise the button follows the parent link.
 */
function frave_submenu_toggle( string $label, bool $is_label ): void {
	$chevron = '<svg class="submenu-toggle__icon" viewBox="0 0 24 24" aria-hidden="true" focusable="false"><path d="m6 9 6 6 6-6"></path></svg>';
	if ( $is_label ) {
		printf(
			'<button class="submenu-toggle submenu-toggle--label" type="button" aria-expanded="false" data-submenu-toggle>%1$s%2$s</button>',
			esc_html( $label ),
			$chevron // phpcs:ignore WordPress.Security.EscapeOutput -- static SVG.
		);
		return;
	}
	printf(
		'<button class="submenu-toggle" type="button" aria-expanded="false" data-submenu-toggle><span class="screen-reader-text">%1$s</span>%2$s</button>',
		/* translators: %s: menu item label. */
		esc_html( sprintf( __( 'Mostrar el submenú de %s', 'frave' ), $label ) ),
		$chevron // phpcs:ignore WordPress.Security.EscapeOutput -- static SVG.
	);
}

/** Icon of a submenu entry, in its tile; '' without an icon. */
function frave_menu_icon_html( string $name ): string {
	$svg = 'whatsapp' === $name ? frave_whatsapp_icon() : frave_icon( $name );
	return '' === $svg ? '' : '<span class="menu-item__icon">' . $svg . '</span>';
}

/**
 * Icon for an entry of a menu assigned in Apariencia → Menús: product lines by category,
 * the contact page, WhatsApp, the Libro de Reclamaciones, or a CSS class frave-icon-NAME.
 */
function frave_menu_item_icon_name( $item ): string {
	foreach ( (array) $item->classes as $class_name ) {
		if ( str_starts_with( (string) $class_name, 'frave-icon-' ) ) {
			return substr( $class_name, strlen( 'frave-icon-' ) );
		}
	}
	if ( 'product_cat' === $item->object ) {
		$term = get_term( (int) $item->object_id, 'product_cat' );
		return $term instanceof WP_Term ? frave_product_line_icon( $term->slug ) : '';
	}
	$url = (string) $item->url;
	if ( str_contains( $url, 'wa.me/' ) ) {
		return 'whatsapp';
	}
	if ( 'page' === $item->object ) {
		if ( FRAVE_TEMPLATE_CONTACT === get_page_template_slug( (int) $item->object_id ) ) {
			return 'mail';
		}
		$claims = function_exists( 'frave_peru_claims_page_url' ) ? frave_peru_claims_page_url() : '';
		if ( $claims && untrailingslashit( $claims ) === untrailingslashit( $url ) ) {
			return 'book';
		}
	}
	return '';
}

/** Submenu toggles, icons and descriptions for menus assigned in Apariencia → Menús. */
function frave_nav_menu_item_output( string $item_output, $item, int $depth, $args ): string {
	if ( ! isset( $args->theme_location ) || 'primary' !== $args->theme_location ) {
		return $item_output;
	}
	if ( 1 === $depth ) {
		$open  = strpos( $item_output, '>', (int) strpos( $item_output, '<a' ) );
		$close = strrpos( $item_output, '</a>' );
		if ( false !== $open && false !== $close ) {
			$description = '' !== trim( (string) $item->description ) ? '<span class="menu-item__description">' . esc_html( $item->description ) . '</span>' : '';
			$item_output = substr( $item_output, 0, $open + 1 )
				. frave_menu_icon_html( frave_menu_item_icon_name( $item ) )
				. '<span class="menu-item__text">' . substr( $item_output, $open + 1, $close - $open - 1 ) . $description . '</span>'
				. substr( $item_output, $close );
		}
	}
	if ( 0 !== $depth || ! in_array( 'menu-item-has-children', (array) $item->classes, true ) ) {
		return $item_output;
	}

	ob_start();
	$url = trim( (string) $item->url );
	if ( '' === $url || '#' === $url ) {
		frave_submenu_toggle( wp_strip_all_tags( $item->title ), true );
		return (string) ob_get_clean();
	}
	frave_submenu_toggle( wp_strip_all_tags( $item->title ), false );
	return $item_output . ob_get_clean();
}
add_filter( 'walker_nav_menu_start_el', 'frave_nav_menu_item_output', 10, 4 );

/**
 * Header call to action: the contact form, or WhatsApp while the contact page is not
 * published. Nothing when neither exists.
 *
 * @return array{label: string, url: string, external: bool}|null
 */
function frave_header_cta(): ?array {
	$contact = frave_contact_page_url();
	if ( $contact ) {
		return array(
			'label'    => __( 'Solicitar asesoría', 'frave' ),
			'url'      => $contact,
			'external' => false,
		);
	}
	$whatsapp = frave_whatsapp_url( frave_whatsapp_message() );
	if ( $whatsapp ) {
		return array(
			'label'    => __( 'Escríbenos', 'frave' ),
			'url'      => $whatsapp,
			'external' => true,
		);
	}
	return null;
}

function frave_render_header_cta( string $classes ): void {
	$cta = frave_header_cta();
	if ( ! $cta ) {
		return;
	}
	printf(
		'<a class="%1$s" href="%2$s"%4$s>%3$s%5$s</a>',
		esc_attr( $classes ),
		esc_url( $cta['url'] ),
		esc_html( $cta['label'] ),
		$cta['external'] ? ' target="_blank" rel="noopener"' : '',
		$cta['external'] ? '<span class="screen-reader-text"> ' . esc_html__( '(se abre en una nueva pestaña)', 'frave' ) . '</span>' : ''
	);
}
