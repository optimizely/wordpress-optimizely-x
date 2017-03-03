

<?php

$post = get_post(get_the_id());
$experiment_id = esc_html( get_post_meta( $post->ID, 'optimizely_experiment_id', true ) );
$status = esc_html( get_post_meta( $post->ID, 'optimizely_experiment_status', true ) );
$link = esc_html( get_post_meta( $post->ID, 'optimizely_editor_link', true ) );

echo '<div class="experiment_running">';
$num_variations = get_post_meta( $post->ID, 'optimizely_variations_num', true );
if(!$num_variations){
	$num_variations = get_option( 'optimizely_num_variations', OPTIMIZELY_NUM_VARIATIONS );
}
for ( $i = 0; $i < $num_variations; $i++ ) {
	echo '<p>';
	echo sprintf(
		'<label for="%s">%s #%u</label><br>',
		esc_attr( $meta_key ),
		esc_html__( 'Variation', 'optimizely-x' ),
		absint( $i + 1 )
	);
	$variation = get_post_meta( $post->ID, 'optimizely_variations_'.$i, true );
	echo sprintf(
		'<b><span class="optimizely_variation_title">%s</span></b>',
		esc_attr( $variation )
	);
	echo '</p>';

}

echo '</div>';

?>
<script>
  window.optimizelyIntegration = window.optimizelyIntegration || {};
	window.optimizelyIntegration.entitiy_id = <?php the_ID(); ?>;
	window.optimizelyIntegration.status = '<?php echo $status ?>';
</script>
<div id="optimizely_created">

	<?php if($status == 'paused'){ ?>
		<a id="optimizely_toggle_running" class="button-primary"><?php esc_html_e( 'Start Experiment', 'optimizely-x' ); ?></a>
	<?php } else if ($status == 'running') { ?>
		<a id="optimizely_toggle_running" class="button-primary"><?php esc_html_e( 'Pause Experiment', 'optimizely-x' ); ?></a>
	<?php } else { ?>
		<a id="optimizely_toggle_running" class="button-primary" hidden><?php esc_html_e( 'Start Experiment', 'optimizely-x' ); ?></a>
	<?php } ?>

	<p></p>
	<a id="optimizely_view" href="<?php echo esc_url( $link ); ?>" class="button" target="_blank"><?php esc_html_e( 'View on Optimizely', 'optimizely-x' ); ?></a>
	<p><?php esc_html_e( 'Status', 'optimizely-x' ); ?>: <b id="optimizely_experiment_status_text"><?php echo esc_html( $status ); ?></b></p>
	<p><?php esc_html_e( 'Experiment ID', 'optimizely-x' ); ?>: <b id="optimizely_experiment_id"><?php echo esc_html( $experiment_id ); ?></b></p>
</div>
