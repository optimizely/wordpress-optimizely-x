<h3><?php esc_html_e( 'Default URL Targeting', 'optimizely-x' ); ?></h3>
<p>
	<?php printf(
		esc_html__( 'This is the default location on your site you would like to run experiments on. By default we use your domain and a substring match so that the experiment will run anywhere on your site. Used with conditional activation this will assure you change the headline no matter where it is. For more info on URL targeting, please visit our %1$sknowledge base article%2$s.', 'optimizely-x' ),
		'<a href="https://help.optimizely.com/hc/en-us/articles/200040835" target="_blank">',
		'</a>'
	); ?>
</p>
<input id="optimizely_url_targeting" name="optimizely_url_targeting" type="text" value="<?php echo esc_attr( $optimizely_url_targeting )  ?>" />
<select id="optimizely_url_targeting_type" name="optimizely_url_targeting_type">
	<?php
		$url_type_array = array(
			'simple' => __( 'simple', 'optimizely-x' ),
			'exact' => __( 'exact', 'optimizely-x' ),
			'substring' => __( 'substring', 'optimizely-x' ),
			'regex' => __( 'regex', 'optimizely-x' ),
		);
	?>
	<?php foreach ( $url_type_array as $type => $label ) : ?>
		<option value="<?php echo esc_attr( $type ); ?>" <?php selected( $type, $optimizely_url_targeting_type ); ?>>
			<?php echo esc_html( $label ); ?>
		</option>
	<?php endforeach; ?>
</select>
