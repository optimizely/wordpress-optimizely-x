<h3><?php esc_html_e( 'Variation Code', 'optimizely-x' ); ?></h3>
<p>
	<?php printf(
		esc_html__( 'Optimizely will use this variation code to change headlines on your site. We\'ve provided code that works if you have changed your headlines to have a class with the format optimizely-$POST_ID, but you might want to add or change it to work with your themes and plugins. For more information on how to update your HTML or write custom variation code please visit %1$sthis article on our knowledge base%2$s.', 'optimizely-x' ),
		'<a href="https://help.optimizely.com/tbd" target="_blank">',
		'</a>'
	); ?>
</p>
<p><?php esc_html_e( 'You can use the variables $POST_ID, $OLD_TITLE, and $NEW_TITLE in your code.', 'optimizely-x' ); ?></p>
<textarea class="code" rows="5" name="variation_template" id="variation_template"><?php echo esc_html( get_option( 'optimizely_variation_template', OPTIMIZELY_DEFAULT_VARIATION_TEMPLATE ) ) ?></textarea>
