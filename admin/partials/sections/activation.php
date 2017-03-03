<h3><?php esc_html_e( 'Activation Mode', 'optimizely-x' ); ?></h3>
<p>
	<?php printf(
		esc_html__( 'You can choose between Immediate Activation Mode or Conditional Activation Mode. If you choose immediate, the experiment will run on every page of your site regardless of whether the headline is on the page or not. Conditional Activation will only run the experiment if the headline is on the page. However, this does require additional coding. For more information about activation modes, please visit %1$sthis article on our knowledge base%2$s.', 'optimizely-x' ),
		'<a href="https://help.optimizely.com/hc/en-us/articles/200040225" target="_blank">',
		'</a>'
	); ?>
</p>
<?php if( !get_option( 'optimizely_activation_mode' ) || 'conditional' == get_option( 'optimizely_activation_mode' ) ): ?>
  <input type="radio" name="optimizely_activation_mode" value="immediate"> <?php esc_html_e( 'Immediate', 'optimizely-x' ); ?>
  <input type="radio" name="optimizely_activation_mode" value="conditional" checked> <?php esc_html_e( 'Conditional', 'optimizely-x' ); ?>
  <div id="optimizely_conditional_activation_code_block">
<?php else:	?>
  <input type="radio" name="optimizely_activation_mode" value="immediate" checked> <?php esc_html_e( 'Immediate', 'optimizely-x' ); ?>
  <input type="radio" name="optimizely_activation_mode" value="conditional"> <?php esc_html_e( 'Conditional', 'optimizely-x' ); ?>
  <div id="optimizely_conditional_activation_code_block" style="display:none;">
<?php endif;	?>

  <p><?php esc_html_e( 'You can use the variables $POST_ID and $OLD_TITLE in your code. The code below will activate the experiment if the original title is on the page and its not the first page the user has visited.', 'optimizely-x' ); ?></p>
  <textarea class="code" rows="5" name="conditional_activation_code" id="conditional_activation_code"><?php echo esc_html( get_option( 'optimizely_conditional_activation_code', OPTIMIZELY_DEFAULT_CONDITIONAL_TEMPLATE ) ) ?></textarea>
</div>
