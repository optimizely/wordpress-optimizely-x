<?php
/**
 * Optimizely X admin metabox partials: Running template
 *
 * @package Optimizely_X
 * @since 1.0.0
 */

global $post;
$experiment_id = get_post_meta( $post->ID, 'optimizely_experiment_id', true );
$status = get_post_meta( $post->ID, 'optimizely_experiment_status', true );

// Negotiate the number of variations.
$num_variations = absint( get_post_meta( $post->ID, 'optimizely_variations_num', true ) );
if ( empty( $num_variations ) ) {
	$num_variations = absint( get_option( 'optimizely_num_variations' ) );
}

?>

<div class="optimizely-running-experiment <?php if ( empty( $experiment_id ) ) : ?>hidden<?php endif; ?>">
	<div class="optimizely-experiment">
		<?php for ( $i = 0; $i < $num_variations; $i++ ) : ?>
			<p>
				<?php esc_html_e( 'Variation', 'optimizely-x' ); ?>
				#<?php echo absint( $i + 1 ); ?>
				<br />
				<strong><?php esc_html( get_post_meta( $post->ID, 'optimizely_variations_' . $i, true ) ); ?></strong>
			</p>
		<?php endfor; ?>
	</div>
	<script>
		window.optimizelyIntegration = window.optimizelyIntegration || {};
		window.optimizelyIntegration.entity_id = <?php the_ID(); ?>;
		window.optimizelyIntegration.status = '<?php echo esc_js( $status ); ?>';
	</script>
	<div id="optimizely-created">
		<?php if ( 'paused' === $status ) : ?>
			<a id="optimizely-toggle-running" class="button button-primary">
				<?php esc_html_e( 'Start Experiment', 'optimizely-x' ); ?>
			</a>
		<?php elseif ( 'running' === $status ) : ?>
			<a id="optimizely-toggle-running" class="button button-primary">
				<?php esc_html_e( 'Pause Experiment', 'optimizely-x' ); ?>
			</a>
		<?php else : ?>
			<a id="optimizely-toggle-running" class="button button-primary" hidden>
				<?php esc_html_e( 'Start Experiment', 'optimizely-x' ); ?>
			</a>
		<?php endif; ?>
		<a class="button button-secondary"
			href="<?php echo esc_url( get_post_meta( $post->ID, 'optimizely_editor_link', true ) ); ?>"
			id="optimizely-view"
			target="_blank"
		><?php esc_html_e( 'View on Optimizely', 'optimizely-x' ); ?></a>
		<p>
			<?php esc_html_e( 'Status', 'optimizely-x' ); ?>:
			<strong id="optimizely-experiment-status-text"><?php echo esc_html( $status ); ?></strong>
		</p>
		<p>
			<?php esc_html_e( 'Experiment ID', 'optimizely-x' ); ?>:
			<strong id="optimizely-experiment-id"><?php echo esc_html( $experiment_id ); ?></strong>
		</p>
	</div>
</div>
