<?php
	wp_nonce_field( OPTIMIZELY_NONCE );
?>

<h3><?php esc_html_e( 'Connect with Optimizely', 'optimizely-x' ); ?></h3>
<p>
	<?php printf(
		esc_html__( 'Once you create an account, you can create a Personal Token on the %1$sdeveloper portal%2$s.', 'optimizely-x' ),
		'<a href="https://app.optimizely.com/v2/accountsettings/apps/developers" target="_blank">',
		'</a>'
	); ?>
</p>
<p>
	<label for="token"><strong><?php esc_html_e( 'Personal Token', 'optimizely-x' ); ?></strong></label>
	<br />
	<input id="token" name="token" type="password" maxlength="80" value="<?php echo esc_attr( hash('ripemd160', get_option( 'optimizely_token' ) ) ); ?>" class="code" />
</p>
<p class="submit">
	<input type="submit" name="submit" value="<?php esc_html_e( 'Submit', 'optimizely-x' ); ?> &raquo;" class="button-primary" />
	<span class="hidden retry-button">
		<input type="submit" value="<?php esc_html_e( 'Retry', 'optimizely-x' ); ?> &raquo;" class="button-secondary" />
	</span>
</p>
