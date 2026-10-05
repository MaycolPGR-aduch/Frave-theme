<?php
/**
 * WhatsApp contact: Customizer settings, link helpers and the product page button.
 * The number is site configuration; nothing renders until it is set.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Keep digits only and add Peru's country code to 9-digit mobile numbers,
 * so "999 888 777" and "+51 999-888-777" both become 51999888777.
 */
function frave_normalize_whatsapp_number( string $number ): string {
	$digits = (string) preg_replace( '/\D+/', '', $number );
	if ( 9 === strlen( $digits ) && str_starts_with( $digits, '9' ) ) {
		$digits = '51' . $digits;
	}
	return $digits;
}

/** @return true|WP_Error */
function frave_validate_whatsapp_number( $validity, $value ) {
	$digits = frave_normalize_whatsapp_number( (string) $value );
	if ( '' !== $digits && ( strlen( $digits ) < 10 || strlen( $digits ) > 15 ) ) {
		$validity->add( 'invalid_whatsapp', __( 'Escribe el número con código de país, por ejemplo 51 999 888 777.', 'frave' ) );
	}
	return $validity;
}

function frave_customize_contact( WP_Customize_Manager $wp_customize ): void {
	$wp_customize->add_section(
		'frave_contact',
		array(
			'title'       => __( 'Contacto', 'frave' ),
			'description' => __( 'Canales que se muestran en la página de contacto. Los botones de WhatsApp aparecen cuando hay un número configurado. Deja vacío lo que Frave aún no haya confirmado.', 'frave' ),
			'priority'    => 160,
		)
	);

	$wp_customize->add_setting(
		'frave_whatsapp_number',
		array(
			'default'           => '',
			'sanitize_callback' => 'frave_normalize_whatsapp_number',
			'validate_callback' => 'frave_validate_whatsapp_number',
		)
	);
	$wp_customize->add_control(
		'frave_whatsapp_number',
		array(
			'label'       => __( 'Número de WhatsApp', 'frave' ),
			'description' => __( 'Con código de país. Para un celular de Perú basta con los 9 dígitos.', 'frave' ),
			'section'     => 'frave_contact',
			'type'        => 'tel',
		)
	);

	$wp_customize->add_setting(
		'frave_whatsapp_message',
		array(
			'default'           => __( 'Hola, tengo una consulta sobre los productos de Frave.', 'frave' ),
			'sanitize_callback' => 'sanitize_text_field',
		)
	);
	$wp_customize->add_control(
		'frave_whatsapp_message',
		array(
			'label'       => __( 'Mensaje inicial', 'frave' ),
			'description' => __( 'En las fichas de producto se usa un mensaje con el nombre y el enlace del producto.', 'frave' ),
			'section'     => 'frave_contact',
			'type'        => 'text',
		)
	);

	$toggles = array(
		'frave_whatsapp_floating' => __( 'Mostrar el botón flotante (no aparece en el checkout)', 'frave' ),
		'frave_whatsapp_product'  => __( 'Mostrar «Consultar por WhatsApp» en las fichas de producto', 'frave' ),
	);
	foreach ( $toggles as $setting => $label ) {
		$wp_customize->add_setting(
			$setting,
			array(
				'default'           => true,
				'sanitize_callback' => 'wp_validate_boolean',
			)
		);
		$wp_customize->add_control(
			$setting,
			array(
				'label'   => $label,
				'section' => 'frave_contact',
				'type'    => 'checkbox',
			)
		);
	}

	$details = array(
		'frave_contact_email'   => array( __( 'Correo de atención', 'frave' ), 'email', 'sanitize_email' ),
		'frave_contact_phone'   => array( __( 'Teléfono de atención', 'frave' ), 'tel', 'sanitize_text_field' ),
		'frave_contact_hours'   => array( __( 'Horario de atención', 'frave' ), 'textarea', 'sanitize_textarea_field' ),
		'frave_contact_address' => array( __( 'Dirección o zona de cobertura', 'frave' ), 'textarea', 'sanitize_textarea_field' ),
	);
	foreach ( $details as $setting => list( $label, $type, $sanitize ) ) {
		$wp_customize->add_setting(
			$setting,
			array(
				'default'           => '',
				'sanitize_callback' => $sanitize,
			)
		);
		$wp_customize->add_control(
			$setting,
			array(
				'label'   => $label,
				'section' => 'frave_contact',
				'type'    => $type,
			)
		);
	}
}
add_action( 'customize_register', 'frave_customize_contact' );

/**
 * Contact channels configured in the Customizer, in display order. Empty ones are left out.
 *
 * @return array<string, array{label: string, value: string, url: string}>
 */
function frave_contact_channels(): array {
	$email    = sanitize_email( (string) get_theme_mod( 'frave_contact_email', '' ) );
	$phone    = trim( (string) get_theme_mod( 'frave_contact_phone', '' ) );
	$whatsapp = frave_whatsapp_number();

	$channels = array(
		'whatsapp' => array(
			'label' => __( 'WhatsApp', 'frave' ),
			'value' => $whatsapp ? '+' . $whatsapp : '',
			'url'   => frave_whatsapp_url( frave_whatsapp_message() ),
		),
		'email'    => array(
			'label' => __( 'Correo', 'frave' ),
			'value' => $email,
			'url'   => $email ? 'mailto:' . $email : '',
		),
		'phone'    => array(
			'label' => __( 'Teléfono', 'frave' ),
			'value' => $phone,
			'url'   => $phone ? 'tel:' . preg_replace( '/[^\d+]/', '', $phone ) : '',
		),
		'hours'    => array(
			'label' => __( 'Horario', 'frave' ),
			'value' => trim( (string) get_theme_mod( 'frave_contact_hours', '' ) ),
			'url'   => '',
		),
		'address'  => array(
			'label' => __( 'Dirección', 'frave' ),
			'value' => trim( (string) get_theme_mod( 'frave_contact_address', '' ) ),
			'url'   => '',
		),
	);
	return array_filter( $channels, static fn( $channel ) => '' !== $channel['value'] );
}

function frave_whatsapp_number(): string {
	return frave_normalize_whatsapp_number( (string) get_theme_mod( 'frave_whatsapp_number', '' ) );
}

/** wa.me link with a prefilled message, or '' when no number is configured. */
function frave_whatsapp_url( string $message = '' ): string {
	$number = frave_whatsapp_number();
	if ( '' === $number ) {
		return '';
	}
	$url = 'https://wa.me/' . $number;
	return '' === $message ? $url : $url . '?text=' . rawurlencode( $message );
}

/** Prefilled message for the current page: product pages mention the product. */
function frave_whatsapp_message(): string {
	if ( function_exists( 'is_product' ) && is_product() ) {
		$product = wc_get_product( get_queried_object_id() );
		if ( $product ) {
			/* translators: 1: product name, 2: product URL. */
			return sprintf( __( 'Hola, quiero consultar por este producto: %1$s %2$s', 'frave' ), $product->get_name(), $product->get_permalink() );
		}
	}
	return (string) get_theme_mod( 'frave_whatsapp_message', __( 'Hola, tengo una consulta sobre los productos de Frave.', 'frave' ) );
}

/** The floating button would cover the "place order" button on phones. */
function frave_show_whatsapp_floating(): bool {
	$show = '' !== frave_whatsapp_number() && (bool) get_theme_mod( 'frave_whatsapp_floating', true );
	if ( $show && function_exists( 'is_checkout' ) && is_checkout() && ! is_wc_endpoint_url( 'order-received' ) ) {
		$show = false;
	}
	return (bool) apply_filters( 'frave_show_whatsapp_floating', $show );
}

/** Inline WhatsApp glyph; decorative, the link carries the accessible name. */
function frave_whatsapp_icon(): string {
	return '<svg class="whatsapp-icon" viewBox="0 0 24 24" aria-hidden="true" focusable="false"><path d="M12.04 2a9.9 9.9 0 0 0-8.5 14.98L2 22l5.15-1.5A9.9 9.9 0 1 0 12.04 2Zm0 18.1a8.2 8.2 0 0 1-4.18-1.14l-.3-.18-3.06.9.91-2.98-.2-.31a8.2 8.2 0 1 1 6.83 3.71Zm4.5-6.14c-.25-.12-1.46-.72-1.69-.8-.23-.08-.39-.12-.55.12-.17.25-.64.8-.78.97-.14.16-.29.18-.53.06a6.7 6.7 0 0 1-3.34-2.92c-.25-.43.25-.4.72-1.34.08-.16.04-.3-.02-.43-.06-.12-.55-1.32-.75-1.8-.2-.48-.4-.4-.55-.41h-.47a.9.9 0 0 0-.65.3 2.74 2.74 0 0 0-.86 2.04 4.76 4.76 0 0 0 1 2.53 10.9 10.9 0 0 0 4.18 3.7c1.56.67 2.17.73 2.95.62.48-.07 1.46-.6 1.67-1.18.2-.58.2-1.07.14-1.18-.06-.1-.22-.16-.47-.28Z"/></svg>';
}

/** "Consultar por WhatsApp" under the add-to-cart form, for advice before buying. */
function frave_product_whatsapp_button(): void {
	if ( ! get_theme_mod( 'frave_whatsapp_product', true ) ) {
		return;
	}
	$url = frave_whatsapp_url( frave_whatsapp_message() );
	if ( '' === $url ) {
		return;
	}
	?>
	<p class="product-whatsapp">
		<a class="whatsapp-button" href="<?php echo esc_url( $url ); ?>" target="_blank" rel="noopener" data-frave-whatsapp="product">
			<?php echo frave_whatsapp_icon(); // phpcs:ignore WordPress.Security.EscapeOutput -- static SVG. ?>
			<span><?php esc_html_e( 'Consultar por WhatsApp', 'frave' ); ?></span>
			<span class="screen-reader-text"><?php esc_html_e( '(se abre en una nueva pestaña)', 'frave' ); ?></span>
		</a>
	</p>
	<?php
}
add_action( 'woocommerce_single_product_summary', 'frave_product_whatsapp_button', 35 );
