<h3><?php esc_html_e( 'Post Types', 'optimizely' ); ?></h3>
<p><?php esc_html_e( 'Please choose the post types you would like to conduct A/B testing on', 'optimizely' ); ?></p>
<?php
	$args = array(
		'show_ui' => true
	);

	$selected_post_types_str = get_option( 'optimizely_post_types', 'post' );
	$selected_post_types = ( ! empty( $selected_post_types_str ) ) ? explode( ',', $selected_post_types_str ) : array();
	$post_types = get_post_types( $args, 'objects' );
	foreach( $post_types as $post_type ) {
		if ( 'page' != $post_type->name && 'attachment' != $post_type->name ) {
			if ( in_array( $post_type->name, $selected_post_types ) ) {
				echo '<input type="checkbox" name="optimizely_post_types[]" value="'. esc_attr( $post_type->name ) .'" checked/>&nbsp;' . esc_attr( $post_type->label ) . '</br>';
			} else {
				echo '<input type="checkbox" name="optimizely_post_types[]" value="'. esc_attr( $post_type->name ) .'"/>&nbsp;' . esc_attr( $post_type->label ) . '</br>';
			}
		}
	}
?>

<?php

	if ( get_option( 'optimizely_url_targeting' ) && get_option( 'optimizely_url_targeting_type' ) ){
		$optimizely_url_targeting = get_option( 'optimizely_url_targeting' );
		$optimizely_url_targeting_type = get_option( 'optimizely_url_targeting_type' );
	} else {
		// Set the default to the current host and substring
		$optimizely_url_targeting = get_site_url();
		$optimizely_url_targeting_type = 'substring';
	}
?>
