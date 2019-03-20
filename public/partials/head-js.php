<?php
/**
 * Optimizely X public partials: Head JS
 *
 * @package Optimizely_X
 * @since 1.0.0
 */

// If we don't have a valid project ID, bail.
$project_id = (int) get_option( 'optimizely_x_project_id' );
if ( empty( $project_id ) ) {
	return;
}
$url = 'https://cdn.optimizely.com/js/' . $project_id . '.js';
?>
<link rel="preload" href="<?php echo esc_url( $url ); ?>" as="script" type="text/javascript" crossorigin="anonymous">
<link rel="preconnect" href="//logx.optimizely.com">
<script src="<?php echo esc_url( $url ); ?>" crossorigin="anonymous"></script>
