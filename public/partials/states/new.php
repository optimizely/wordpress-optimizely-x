

<?php

$post = get_post(get_the_id());

$titles = array();
$num_variations = get_option( 'optimizely_num_variations', OPTIMIZELY_NUM_VARIATIONS );
echo '<div class="new_experiment">';
for ( $i = 1; $i <= $num_variations; $i++ ) {
	$meta_key = optimizely_meta_key( $i );
	echo '<p>';
	echo sprintf(
		'<label for="%s">%s #%u</label><br>',
		esc_attr( $meta_key ),
		esc_html__( 'Variation', 'optimizely' ),
		absint( $i )
	);
	echo sprintf(
		'<input type="text" name="%s" id="%s" class="optimizely_variation" placeholder="%s %u" required>',
		esc_attr( $meta_key ),
		esc_attr( $meta_key ),
		esc_html__( 'Title', 'optimizely' ),
		absint( $i )
	);
	echo '</p>';
}
echo '</div>';

?>
<div id="optimizely_error_box" style="display:none">
  <p><span id='optimizely_error_message'></span></p>
</div>
<div id="optimizely_not_created">
	<a id="optimizely_create" class="button-primary"><?php esc_html_e( 'Create Experiment', 'optimizely' ) ?></a>
</div>
