<?php
/**
 * Optimizely X admin metabox partials: Unauthenticated template
 *
 * @package Optimizely_X
 * @since 1.0.0
 */

?>

<p>
	<?php
		printf(
			esc_html__( 'Please configure your API credentials and Project in the %1$sOptimizely settings page%2$s.', 'optimizely-x' ),
			'<a href="' . esc_url( menu_page_url( 'optimizely-config', false ) ) . '">',
			'</a>'
		);
	?>
</p>