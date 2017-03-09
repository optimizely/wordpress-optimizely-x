<?php
/**
 * Optimizely X admin field partials: optimizely_token field
 *
 * @package Optimizely_X
 * @since 1.0.0
 */

?>

<div>
	<input id="optimizely-token"
		name="optimizely_token"
		type="password"
		maxlength="80"
		value="<?php echo esc_attr( hash('ripemd160', get_option( 'optimizely_token' ) ) ); ?>" class="code"
	/>
</div>
<p class="description">
	<?php printf(
		esc_html__( 'Once you create an account, you can create a Personal Token on the %1$sdeveloper portal%2$s.', 'optimizely-x' ),
		'<a href="https://app.optimizely.com/v2/accountsettings/apps/developers" target="_blank">',
		'</a>'
	); ?>
</p>
