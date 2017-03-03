

<?php

$post = get_post(get_the_id());

$titles = array();
$num_variations = get_option( 'optimizely_num_variations', OPTIMIZELY_NUM_VARIATIONS );
?>
<div class="new_experiment">
<?php for ( $i = 1; $i <= $num_variations; $i++ ) : ?>
	<?php $meta_key = optimizely_meta_key( $i ); ?>
	<p>
		<label for="<?php echo esc_attr( $meta_key ); ?>">
			<?php esc_html_e( 'Variation', 'optimizely-x' ); ?> #<?php echo absint( $i ); ?>
		</label>
		<br>
		<input type="text"
			name="<?php echo esc_attr( $meta_key ); ?>"
			id="<?php echo esc_attr( $meta_key ); ?>"
			class="optimizely_variation"
			placeholder="<?php esc_attr_e( 'Title', 'optimizely-x' ); ?> <?php echo absint( $i ); ?>"
			required>
	</p>
<?php endfor; ?>
</div>
<div id="optimizely_error_box" style="display:none">
	<p><span id="optimizely_error_message"></span></p>
</div>
<div id="optimizely_not_created">
	<a id="optimizely_create" class="button-primary"><?php esc_html_e( 'Create Experiment', 'optimizely-x' ); ?></a>
</div>
