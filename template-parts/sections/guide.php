<?php
/** Three editorial steps, independently editable with ACF Free. */
$steps = array(
	array( 'title' => __( 'Explora aromas', 'frave' ), 'text' => __( 'Descubre fragancias y esencias para inspirar tu próxima idea.', 'frave' ) ),
	array( 'title' => __( 'Elige insumos', 'frave' ), 'text' => __( 'Encuentra materias primas y complementos para tu proceso creativo.', 'frave' ) ),
	array( 'title' => __( 'Crea a tu manera', 'frave' ), 'text' => __( 'Combina posibilidades y da forma a un proyecto propio.', 'frave' ) ),
);
?>
<section class="section guide-section" aria-labelledby="guide-heading">
	<div class="container">
		<div class="section-heading">
			<div>
				<p class="eyebrow"><?php echo esc_html( frave_field( 'frave_guide_eyebrow', __( 'TU PROCESO CREATIVO', 'frave' ) ) ); ?></p>
				<h2 id="guide-heading"><?php echo esc_html( frave_field( 'frave_guide_title', __( 'Cada creación empieza con una idea', 'frave' ) ) ); ?></h2>
			</div>
		</div>
		<ol class="guide-grid">
			<?php foreach ( $steps as $index => $step ) : ?>
				<li class="guide-card">
					<span class="guide-card__number" aria-hidden="true"><?php echo esc_html( sprintf( '%02d', $index + 1 ) ); ?></span>
					<h3><?php echo esc_html( frave_field( 'frave_guide_' . ( $index + 1 ) . '_title', $step['title'] ) ); ?></h3>
					<p><?php echo esc_html( frave_field( 'frave_guide_' . ( $index + 1 ) . '_text', $step['text'] ) ); ?></p>
				</li>
			<?php endforeach; ?>
		</ol>
	</div>
</section>
