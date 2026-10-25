<?php
/**
 * Template Name: Colecciones
 * Template Post Type: page
 *
 * Every collection, grouped by its subcategory (inc/collections.php): the band with the
 * page's title and introduction, one group per subcategory with a link to it, any content
 * written in the editor and a closing call to action. New collections appear here once they
 * have a subcategory.
 */

get_header();
?>
<main id="primary" class="site-main collections-page">
	<?php
	while ( have_posts() ) :
		the_post();
		$frave_lead = has_excerpt() ? get_the_excerpt() : __( 'Grupos de aromas pensados para cada tipo de producto: elige la colección de tu aplicación y conoce sus fragancias.', 'frave' );
		get_template_part(
			'template-parts/components/page-hero',
			null,
			array(
				'eyebrow' => __( 'Catálogo', 'frave' ),
				'title'   => get_the_title(),
				'lead'    => $frave_lead,
			)
		);

		// One group per subcategory, in the order of the collections.
		$frave_groups = array();
		foreach ( frave_collections() as $frave_collection ) {
			$frave_category = frave_collection_category( $frave_collection );
			$frave_key      = $frave_category ? $frave_category->term_id : 0;
			if ( ! isset( $frave_groups[ $frave_key ] ) ) {
				$frave_groups[ $frave_key ] = array(
					'category' => $frave_category,
					'items'    => array(),
				);
			}
			$frave_groups[ $frave_key ]['items'][] = $frave_collection;
		}
		?>
		<div class="section collections-hub">
			<div class="container">
				<?php if ( ! $frave_groups ) : ?>
					<p class="lead"><?php esc_html_e( 'Muy pronto presentaremos aquí nuestras colecciones.', 'frave' ); ?></p>
				<?php endif; ?>
				<?php foreach ( $frave_groups as $frave_group ) : ?>
					<?php
					$frave_category = $frave_group['category'];
					$frave_line     = $frave_category ? frave_term_line_name( $frave_category ) : '';
					get_template_part(
						'template-parts/components/collections',
						null,
						array(
							'collections'   => $frave_group['items'],
							'eyebrow'       => '' !== $frave_line && $frave_category && $frave_line !== $frave_category->name ? $frave_line : __( 'Colecciones', 'frave' ),
							'title'         => $frave_category ? $frave_category->name : __( 'Otras colecciones', 'frave' ),
							'id'            => 'colecciones-' . ( $frave_category ? $frave_category->slug : 'otras' ),
							'compact'       => true,
							'show_category' => false,
							/* translators: %s: subcategory name. */
							'more_label'    => $frave_category ? sprintf( __( 'Ver %s', 'frave' ), mb_strtolower( $frave_category->name ) ) : '',
							'more'          => $frave_category ? frave_term_detail_url( $frave_category ) : '',
						)
					);
					?>
				<?php endforeach; ?>
			</div>
		</div>
		<?php
		if ( '' !== trim( get_the_content() ) ) :
			?>
			<section class="section company-content">
				<div class="container content-container">
					<div class="entry-content"><?php the_content(); ?></div>
				</div>
			</section>
			<?php
		endif;

		$frave_line_term = frave_line_term( 'fragancias' );
		get_template_part(
			'template-parts/components/cta-panel',
			null,
			array(
				'title'    => __( '¿Buscas un aroma para *tu producto*?', 'frave' ),
				'text'     => __( 'Cuéntanos qué fabricas y te proponemos fragancias de nuestras colecciones o a tu medida.', 'frave' ),
				'action'   => $frave_line_term ? frave_line_quote_link( $frave_line_term ) : frave_company_action(),
				'whatsapp' => frave_whatsapp_url( __( 'Hola, quisiera información sobre sus colecciones de fragancias.', 'frave' ) ),
			)
		);
	endwhile;
	?>
</main>
<?php
get_footer();
