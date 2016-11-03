<h3><?php esc_html_e( 'Default URL Targeting', 'optimizely' ); ?></h3>
<p><?php esc_html_e( 'This is the default location on your site you would like to run experiments on. By default we use your domain and a substring match so that the experiment will run anywhere on your site. Used with conditional activation this will assure you change the headline no matter where it is. For more info on URL targeting ', 'optimizely' ); ?><a href="https://help.optimizely.com/hc/en-us/articles/200040835" target="_blank"><?php esc_html_e( 'please visit our knowledge base article located here','optimizely' ); ?></a></p>
<input id="optimizely_url_targeting" name="optimizely_url_targeting" type="text" value="<?php echo esc_attr( $optimizely_url_targeting )  ?>" />
<select id="optimizely_url_targeting_type" name="optimizely_url_targeting_type">
	<?php
		$url_type_array = array(
			"simple",
			"exact",
			"substring",
			"regex"
		);
		foreach ( $url_type_array as $type ) {
			if ( 0 !== strcmp( $type, $optimizely_url_targeting_type ) ) {
				echo ( '<option value="'. esc_attr( $type ) .'">'. esc_html( $type, 'optimizely' ) .'</option>' );
			} else {
				echo ( '<option value="'. esc_attr( $type ) .'" selected>'. esc_html( $type, 'optimizely' ) .'</option>' );
			}
		}
	?>
</select>
