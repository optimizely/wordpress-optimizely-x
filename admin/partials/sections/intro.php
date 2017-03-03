<?php
	wp_nonce_field( OPTIMIZELY_NONCE );
	$project_name = get_option( 'optimizely_project_name' );
?>
<h3><?php esc_html_e( 'Installation Instructions', 'optimizely-x' ); ?></h3>
<p>
	<?php printf(
		esc_html__( 'For full instructions on how to configure the settings and use the Optimizely plugin, please %1$svisit our knowledge base article%2$s.', 'optimizely-x' ),
		'<a href="https://help.optimizely.com/tbd" target="_blank">',
		'</a>'
	); ?>
</p>

<h3><?php esc_html_e( 'About Optimizely', 'optimizely-x' ); ?></h3>
<p>
	<?php printf(
		esc_html__( 'Simple, fast, and powerful. %1$sOptimizely%2$s is a dramatically easier way for you to improve your website through A/B testing. Create an experiment in minutes with absolutely no coding or engineering required. Convert your website visitors into customers and earn more revenue: create an account at %3$soptimizely.com%4$s and start A/B testing today!', 'optimizely-x' ),
		'<a href="https://www.optimizely.com" target="_blank">',
		'</a>',
		'<a href="https://www.optimizely.com" target="_blank">',
		'</a>'
	); ?>
</p>
