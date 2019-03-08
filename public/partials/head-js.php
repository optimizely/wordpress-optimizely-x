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
?>
<link rel="prefetch" href="<?php echo esc_url( '//cdn.optimizely.com/js/' . $project_id . '.js' ); ?>">
<link rel="preconnect" href="//logx.optimizely.com">
<script src="<?php echo esc_url( 'https://cdn.optimizely.com/js/' . $project_id . '.js' ); ?>"></script>