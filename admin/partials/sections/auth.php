<?php
	wp_nonce_field( OPTIMIZELY_NONCE );
?>

<h3><?php esc_html_e( 'Connect with Optimizely', 'optimizely' ); ?></h3>
<p><?php esc_html_e( 'Once you create an account, you can create an Personal Token on', 'optimizely' ); ?> <a href="https://app.optimizely.com/v2/accountsettings/apps/developers">https://app.optimizely.com/v2/accountsettings/apps/developers</a>.</p>
<p>
	<label for="token"><strong><?php esc_html_e( 'Personal Token', 'optimizely' ); ?></strong></label>
	<br />
	<input id="token" name="token" type="password" maxlength="80" value="<?php echo esc_attr( hash('ripemd160', get_option( 'optimizely_token' ) ) ); ?>" class="code" />
</p>
<p class="submit">
	<input type="submit" name="submit" value="<?php esc_html_e( 'Submit &raquo;', 'optimizely' ); ?>" class="button-primary" />
	<span class="hidden retry-botton">
		<input type="submit" value="<?php esc_html_e( 'Retry &raquo;', 'optimizely' ); ?>" class="button-secondary" />
	</span>
</p>
