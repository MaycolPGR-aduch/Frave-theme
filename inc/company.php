<?php
/**
 * Company pages: Nosotros (template "Nosotros") and Servicios (template "Servicios", called
 * Experticia until October 2026: its code still says "expertise").
 *
 * The defaults below are the copy agreed with Frave's management (October 2026) and the
 * single source of these pages' text: templates fall back to them and ACF shows them as
 * default values ("Nosotros Frave", "Servicios Frave"). Wrap a word in *asterisks* to
 * highlight it. bin/crear-paginas-empresa.php creates both pages.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

const FRAVE_TEMPLATE_ABOUT     = 'page-templates/nosotros.php';
const FRAVE_TEMPLATE_EXPERTISE = 'page-templates/experticia.php';

/** Default copy of every company page field. */
function frave_company_defaults(): array {
	return array(
		'frave_about_hero_eyebrow'          => __( 'Nosotros', 'frave' ),
		'frave_about_hero_title'            => __( 'El socio de las marcas que buscan *diferenciarse*', 'frave' ),
		'frave_about_hero_text'             => __( 'Somos una empresa peruana especializada en la provisión integral de fragancias e insumos para las industrias de perfumería, cosmética, cuidado personal y del hogar.', 'frave' ),
		'frave_about_intro_title'           => __( 'Insumos de alta gama y un profundo *conocimiento técnico* de la industria.', 'frave' ),
		'frave_about_intro_text'            => __( "Nos posicionamos como el socio comercial ideal para marcas que buscan diferenciarse en un mercado altamente competitivo.\n\nContamos con amplia experiencia en el mercado peruano y conectamos a nuestros clientes con fabricantes internacionales de fragancias y sabores de reconocido prestigio, con alianzas estratégicas, productos de calidad y asesoría técnica especializada.\n\nTrabajamos para construir relaciones a largo plazo basadas en el respaldo, la eficiencia operativa y el impulso conjunto hacia el crecimiento sostenible.", 'frave' ),
		'frave_about_essence_title'         => __( 'Conectamos conocimiento, confianza e innovación para *crear valor*.', 'frave' ),
		'frave_about_essence_text'          => __( 'En FRAVE creemos en hacer las cosas bien, construir relaciones genuinas y transformar nuestra experiencia en oportunidades de crecimiento para nuestros clientes y aliados.', 'frave' ),
		'frave_about_beliefs_title'         => __( 'Nuestras creencias y *comportamientos*', 'frave' ),
		'frave_about_beliefs_text'          => __( 'Son los principios que guían nuestra forma de trabajar y nos impulsan a construir relaciones de largo plazo con nuestros clientes y aliados.', 'frave' ),
		'frave_about_belief_1_title'        => __( 'Orientamos nuestro trabajo a generar valor', 'frave' ),
		'frave_about_belief_1_text'         => __( 'Nos enfocamos en comprender los desafíos de nuestros clientes y convertir sus necesidades en soluciones que contribuyan a mejorar sus productos, fortalecer su competitividad y alcanzar sus objetivos.', 'frave' ),
		'frave_about_belief_1_behavior'     => __( 'Actuamos con iniciativa y orientación a resultados.', 'frave' ),
		'frave_about_belief_2_title'        => __( 'Construimos relaciones que perduran', 'frave' ),
		'frave_about_belief_2_text'         => __( 'Creemos que las mejores oportunidades nacen de la confianza, la transparencia y el compromiso mutuo. Cultivamos vínculos comerciales que trascienden una transacción y promueven el crecimiento compartido.', 'frave' ),
		'frave_about_belief_2_behavior'     => __( 'Cumplimos nuestros compromisos y actuamos con integridad.', 'frave' ),
		'frave_about_belief_3_title'        => __( 'Evolucionamos con el mercado', 'frave' ),
		'frave_about_belief_3_text'         => __( 'Entendemos que las tendencias, la tecnología y las preferencias de los consumidores abren nuevas posibilidades. Por ello, impulsamos el aprendizaje continuo y exploramos alternativas que aporten diferenciación y nuevas oportunidades de negocio.', 'frave' ),
		'frave_about_belief_3_behavior'     => __( 'Aprendemos, proponemos y buscamos nuevas formas de crear valor.', 'frave' ),
		'frave_about_belief_4_title'        => __( 'Crecemos a través del conocimiento compartido', 'frave' ),
		'frave_about_belief_4_text'         => __( 'Valoramos la experiencia, la colaboración y el aporte de cada persona. Integramos capacidades comerciales y técnicas para ofrecer una atención especializada y construir soluciones que respondan a las exigencias de cada cliente.', 'frave' ),
		'frave_about_belief_4_behavior'     => __( 'Compartimos conocimientos, escuchamos y trabajamos en equipo.', 'frave' ),
		'frave_about_cta_title'             => __( '¿Construimos juntos tu *próximo producto*?', 'frave' ),
		'frave_about_cta_text'              => __( 'Cuéntanos sobre tu marca y te acompañamos desde la primera consulta.', 'frave' ),

		'frave_expertise_hero_eyebrow'      => __( 'Servicios', 'frave' ),
		'frave_expertise_hero_title'        => __( 'Conocimiento técnico al servicio de tu *marca*', 'frave' ),
		'frave_expertise_hero_text'         => __( 'Comprendemos las necesidades de cada negocio para ofrecer soluciones que aportan valor, promueven la innovación y contribuyen al crecimiento sostenible de nuestros clientes.', 'frave' ),
		'frave_expertise_cloud_caption'     => __( 'Aplicaciones, atributos y servicios que forman parte de nuestro trabajo.', 'frave' ),
		'frave_expertise_intro_title'       => __( 'Te conectamos con *fabricantes internacionales* de reconocido prestigio.', 'frave' ),
		'frave_expertise_intro_text'        => __( "Brindamos soluciones aromáticas e insumos especializados para impulsar el desarrollo y la competitividad de las empresas del sector.\n\nRespaldamos nuestra propuesta con alianzas estratégicas, productos de calidad y asesoría técnica especializada, siempre orientada a comprender las necesidades de cada negocio.", 'frave' ),
		'frave_expertise_highlight_1_title' => __( '+500 referencias', 'frave' ),
		'frave_expertise_highlight_1_text'  => __( 'De fragancias y esencias hidrosolubles para diversas industrias.', 'frave' ),
		'frave_expertise_highlight_2_title' => __( 'Asesoría técnica', 'frave' ),
		'frave_expertise_highlight_2_text'  => __( 'Orientación especializada para el desarrollo de tus productos.', 'frave' ),
		'frave_expertise_highlight_3_title' => __( 'Alianzas estratégicas', 'frave' ),
		'frave_expertise_highlight_3_text'  => __( 'Con fabricantes internacionales de fragancias y sabores.', 'frave' ),
		'frave_expertise_method_title'      => __( 'Cómo *trabajamos*', 'frave' ),
		'frave_expertise_method_text'       => __( 'Un acompañamiento cercano, desde la primera conversación hasta el crecimiento de tu marca.', 'frave' ),
		'frave_expertise_step_1_title'      => __( 'Escuchamos', 'frave' ),
		'frave_expertise_step_1_text'       => __( 'Comprendemos los desafíos de tu negocio, tu producto y tu público para convertir tus necesidades en soluciones.', 'frave' ),
		'frave_expertise_step_2_title'      => __( 'Proponemos', 'frave' ),
		'frave_expertise_step_2_text'       => __( 'Seleccionamos de nuestro portafolio y de nuestros fabricantes aliados las fragancias, sabores y envases que mejor se adaptan a tu formulación.', 'frave' ),
		'frave_expertise_step_3_title'      => __( 'Asesoramos', 'frave' ),
		'frave_expertise_step_3_text'       => __( 'Te acompañamos con orientación técnica en el desarrollo de tu producto: compatibilidad, aplicación y tendencias del mercado.', 'frave' ),
		'frave_expertise_step_4_title'      => __( 'Crecemos juntos', 'frave' ),
		'frave_expertise_step_4_text'       => __( 'Construimos una relación de largo plazo basada en el respaldo, la eficiencia operativa y el crecimiento compartido.', 'frave' ),
		'frave_expertise_industries_title'  => __( 'Industrias que *atendemos*', 'frave' ),
		'frave_expertise_industries_text'   => __( 'Fragancias, sabores y envases para cada etapa de tu producto.', 'frave' ),
		'frave_expertise_cta_title'         => __( '¿Necesitas asesoría para *tu proyecto*?', 'frave' ),
		'frave_expertise_cta_text'          => __( 'Conversemos sobre tu producto y encontremos juntos la mejor alternativa.', 'frave' ),
	);
}

/** A company page field from ACF, or its default. */
function frave_company_text( string $name ): string {
	$defaults = frave_company_defaults();
	return (string) frave_field( $name, $defaults[ $name ] ?? '' );
}

/** Show the defaults in the ACF editor too, so the copy lives in one place. */
function frave_company_acf_defaults( array $field ): array {
	$defaults = frave_company_defaults();
	if ( isset( $defaults[ $field['name'] ] ) && '' === (string) ( $field['default_value'] ?? '' ) ) {
		$field['default_value'] = $defaults[ $field['name'] ];
	}
	return $field;
}
add_filter( 'acf/load_field', 'frave_company_acf_defaults' );

/** Icons of the four beliefs, as in management's sheet. */
function frave_company_belief_icons(): array {
	return array(
		1 => 'target',
		2 => 'link',
		3 => 'bulb',
		4 => 'users',
	);
}

/**
 * Industries each product line serves (management agreements), for Servicios.
 *
 * @return array<string, string[]>
 */
function frave_company_industries(): array {
	return array(
		'fragancias' => array(
			__( 'Perfumería fina', 'frave' ),
			__( 'Cosmética', 'frave' ),
			__( 'Cuidado personal', 'frave' ),
			__( 'Aseo e higiene', 'frave' ),
			__( 'Cuidado del hogar', 'frave' ),
			__( 'Ambientación', 'frave' ),
			__( 'Marketing olfativo', 'frave' ),
		),
		'sabores'    => array(
			__( 'Bebidas', 'frave' ),
			__( 'Alimentos', 'frave' ),
			__( 'Lácteos', 'frave' ),
			__( 'Confitería y panadería', 'frave' ),
			__( 'Salud bucal', 'frave' ),
		),
		'envases'    => array(
			__( 'Perfumería', 'frave' ),
			__( 'Cosmética', 'frave' ),
			__( 'Cuidado personal', 'frave' ),
			__( 'Difusores y ambientación', 'frave' ),
		),
	);
}

/** Published page using a company template, or ''. */
function frave_company_page_url( string $template ): string {
	$page = frave_page_with_template( $template );
	return $page ? (string) get_permalink( $page ) : '';
}

/** Main call to action of company pages: the contact form, or WhatsApp. */
function frave_company_action(): ?array {
	$cta = frave_header_cta();
	return $cta ? array(
		'url'      => $cta['url'],
		'external' => $cta['external'],
	) : null;
}

/** These pages close with their own call to action instead of the footer's. */
function frave_company_hide_footer_cta( bool $show ): bool {
	return is_page_template( array( FRAVE_TEMPLATE_ABOUT, FRAVE_TEMPLATE_EXPERTISE ) ) ? false : $show;
}
add_filter( 'frave_show_footer_cta', 'frave_company_hide_footer_cta' );

/**
 * /experticia/, the services page's address until October 2026, leads permanently to the
 * page with the Servicios template (WordPress keeps old addresses only for posts). Before
 * WordPress's own guess of a similar address.
 */
function frave_company_redirect_old_expertise(): void {
	global $wp;
	if ( ! is_404() || 'experticia' !== trim( (string) ( get_query_var( 'pagename' ) ? get_query_var( 'pagename' ) : $wp->request ), '/' ) ) {
		return;
	}
	$url = frave_company_page_url( FRAVE_TEMPLATE_EXPERTISE );
	if ( '' !== $url ) {
		wp_safe_redirect( $url, 301 );
		exit;
	}
}
add_action( 'template_redirect', 'frave_company_redirect_old_expertise', 9 );

/** The Nosotros image is the largest element at first paint: fetch it early. */
function frave_company_preload_image( array $resources ): array {
	if ( is_page_template( FRAVE_TEMPLATE_ABOUT ) ) {
		$resources[] = array(
			'href'          => frave_asset_url( 'assets/media/empresa/ingredientes.webp' ),
			'as'            => 'image',
			'fetchpriority' => 'high',
		);
	}
	return $resources;
}
add_filter( 'wp_preload_resources', 'frave_company_preload_image' );
