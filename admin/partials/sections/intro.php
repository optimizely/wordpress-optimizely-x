<?php
	wp_nonce_field( OPTIMIZELY_NONCE );
	$project_name = get_option( 'optimizely_project_name' );
?>
<h3><?php esc_html_e( 'Installation Instructions', 'optimizely' ); ?></h3>
<p><?php esc_html_e( 'For full instructions on how to configure the settings and use the Optimizely plugin, please', 'optimizely' ) ?> <a href="#" target="_blank"><?php esc_html_e( 'visit our knowledge base article', 'optimizely' ) ?></a></p>

<h3><?php esc_html_e( 'About Optimizely', 'optimizely' ); ?></h3>
<p><?php esc_html_e( 'Simple, fast, and powerful.', 'optimizely' ); ?> <a href="http://www.optimizely.com" target="_blank">Optimizely</a> <?php esc_html_e( 'is a dramatically easier way for you to improve your website through A/B testing. Create an experiment in minutes with absolutely no coding or engineering required. Convert your website visitors into customers and earn more revenue: create an account at', 'optimizely' ) ?> <a href="http://www.optimizely.com" target="_blank">optimizely.com</a> <?php esc_html_e( 'and start A/B testing today!', 'optimizely' ) ?></p>
