<?php
/**
 * Optimizely X admin partials: Config page template
 *
 * @package Optimizely_X
 * @since 1.0.0
 */

?>

<div class="wrap optimizely-admin">
	<h1><?php esc_html_e( 'Optimizely', 'optimizely-x' ); ?></h1>
	<?php settings_errors(); ?>
	<div class="card">
		<h3><?php esc_html_e( 'Installation Instructions', 'optimizely-x' ); ?></h3>
		<p>
			<?php printf(
				esc_html__( 'For full instructions on how to configure the settings and use the Optimizely plugin, please read this %1$sknowledge base article%2$s.', 'optimizely-x' ),
				'<a href="https://help.optimizely.com/Integrate_Other_Platforms/Integrate_Optimizely_with_WordPress" target="_blank">',
				'</a>'
			); ?>
		</p>
	</div>
	<div class="card">
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
	</div>
	<form method="post" action="options.php">
		<?php settings_fields( 'optimizely_config_section' ); ?>
		<?php do_settings_sections( 'optimizely_config_options' ); ?>
		<?php submit_button(); ?>
	</form>
</div>
