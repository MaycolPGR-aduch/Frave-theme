<?php
/** Editorial introduction. Product data remains in WooCommerce. */
$eyebrow = frave_field( 'frave_story_eyebrow', __( 'ACERCA DE FRAVE', 'frave' ) );
$title   = frave_field( 'frave_story_title', __( 'Un espacio para imaginar nuevas esencias', 'frave' ) );
$copy    = frave_field( 'frave_story_text', __( 'Explora fragancias, esencias e insumos para dar forma a tus ideas. Frave reúne opciones para quienes disfrutan crear y descubrir el mundo de la perfumería.', 'frave' ) );
$image   = frave_field( 'frave_story_image' );
$image_id = is_array( $image ) ? absint( $image['ID'] ?? 0 ) : ( is_numeric( $image ) ? absint( $image ) : 0 );
$about   = get_page_by_path( 'nosotros' );
?>
<section class="section story-section" aria-labelledby="story-heading">
	<div class="container story-section__inner">
		<div class="story-section__visual<?php echo $image_id ? ' story-section__visual--image' : ''; ?>">
			<?php if ( $image_id ) : ?>
				<?php echo wp_get_attachment_image( $image_id, 'frave-editorial', false, array( 'class' => 'story-section__image', 'sizes' => '(max-width: 48rem) calc(100vw - 2.5rem), 45vw', 'loading' => 'lazy', 'decoding' => 'async' ) ); ?>
			<?php else : ?>
				<div class="story-section__mark" aria-hidden="true">F<span>.</span></div>
			<?php endif; ?>
		</div>
		<div class="story-section__copy">
			<p class="eyebrow"><?php echo esc_html( $eyebrow ); ?></p>
			<h2 id="story-heading"><?php echo esc_html( $title ); ?></h2>
			<p><?php echo esc_html( $copy ); ?></p>
			<?php if ( $about instanceof WP_Post && 'publish' === $about->post_status ) : ?>
				<?php frave_button( __( 'Conoce Frave', 'frave' ), get_permalink( $about ), 'button button--secondary' ); ?>
			<?php endif; ?>
		</div>
	</div>
</section>
