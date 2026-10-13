<?php
/**
 * Share links of the current post: WhatsApp, LinkedIn, Facebook and email, plus «Copiar
 * enlace» and the phone's own share sheet, which need JavaScript (share.js shows them).
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$frave_new_tab = '<span class="screen-reader-text"> ' . esc_html__( '(se abre en una nueva pestaña)', 'frave' ) . '</span>';
?>
<div class="share" data-share data-share-url="<?php echo esc_url( get_permalink() ); ?>" data-share-title="<?php echo esc_attr( wp_strip_all_tags( get_the_title() ) ); ?>" data-share-copied="<?php esc_attr_e( 'Enlace copiado', 'frave' ); ?>">
	<p class="share__title" id="share-title-<?php the_ID(); ?>"><?php esc_html_e( 'Compartir', 'frave' ); ?></p>
	<ul class="share__list" aria-labelledby="share-title-<?php the_ID(); ?>">
		<li hidden data-share-native>
			<button class="share__button" type="button">
				<?php echo frave_icon( 'arrow-up-right' ); // phpcs:ignore WordPress.Security.EscapeOutput -- static SVG. ?>
				<span class="share__label"><?php esc_html_e( 'Compartir…', 'frave' ); ?></span>
			</button>
		</li>
		<?php foreach ( frave_share_links() as $frave_network => $frave_link ) : ?>
			<?php $frave_external = 'email' !== $frave_network; ?>
			<li>
				<a class="share__button share__button--<?php echo esc_attr( $frave_network ); ?>" href="<?php echo esc_url( $frave_link['url'] ); ?>"<?php echo $frave_external ? ' target="_blank" rel="noopener"' : ''; ?>>
					<?php echo frave_share_icon( $frave_network ); // phpcs:ignore WordPress.Security.EscapeOutput -- static SVG. ?>
					<span class="share__label"><?php echo esc_html( $frave_link['label'] ); ?></span>
					<?php echo $frave_external ? $frave_new_tab : ''; // phpcs:ignore WordPress.Security.EscapeOutput -- escaped above. ?>
				</a>
			</li>
		<?php endforeach; ?>
		<li hidden data-share-copy>
			<button class="share__button" type="button">
				<?php echo frave_icon( 'link' ); // phpcs:ignore WordPress.Security.EscapeOutput -- static SVG. ?>
				<span class="share__label" data-share-copy-label><?php esc_html_e( 'Copiar enlace', 'frave' ); ?></span>
			</button>
		</li>
	</ul>
	<p class="screen-reader-text" role="status" data-share-status></p>
</div>
