<?php
/**
 * Optimizely X admin metabox partials: New template
 *
 * @package Optimizely_X
 * @since 1.0.0
 */

global $post;
$num_variations = absint( get_option( 'optimizely_num_variations' ) );

?>

<div class="optimizely-new-experiment">
	<div class="optimizely-experiment">
		<?php for ( $i = 1; $i <= $num_variations; $i++ ) : ?>
			<?php $meta_key = 'post_title_' . $i; ?>
			<div>
				<label for="<?php echo esc_attr( $meta_key ); ?>">
					<?php esc_html_e( 'Variation', 'optimizely-x' ); ?> #<?php echo absint( $i ); ?>
				</label>
				<br />
				<input class="optimizely-variation"
					id="<?php echo esc_attr( $meta_key ); ?>"
					name="<?php echo esc_attr( $meta_key ); ?>"
					placeholder="<?php esc_attr_e( 'Title', 'optimizely-x' ); ?> <?php echo absint( $i ); ?>"
					type="text"
					required
				/>
			</div>
		<?php endfor; ?>
	</div>
	<div id="optimizely-create">
		<a class="button button-primary"><?php esc_html_e( 'Create Experiment', 'optimizely-x' ); ?></a>
	</div>
</div>
