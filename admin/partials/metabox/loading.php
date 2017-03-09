<?php
/**
 * Optimizely X admin metabox partials: Loading template
 *
 * @package Optimizely_X
 * @since 1.0.0
 */

?>

<div class="optimizely-loading">
	<p><?php esc_html_e( 'Loading your experiment ...', 'optimizely-x' ); ?></p>
	<img src="<?php echo esc_url( OPTIMIZELY_X_BASE_URL . '/admin/images/ajax-loader.gif' ); ?>" />
</div>
