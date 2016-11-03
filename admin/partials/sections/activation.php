<h3><?php esc_html_e( 'Activation Mode', 'optimizely' ); ?></h3>
<p><?php esc_html_e( 'You can choose between Immediate Activation Mode or Conditional Activation Mode. If you choose immediate, the experiment will run on every page of your site reguardless if the headline is on the page or not. Conditional Activation will only run the experiment if the headline is on the page. However this does require additional coding. For more information about activation modes please visit ', 'optimizely' ); ?><a href="https://help.optimizely.com/hc/en-us/articles/200040225" target="_blank"><?php esc_html_e( 'this article on our knowledge base','optimizely' ); ?></a></p>
<?php if( !get_option( 'optimizely_activation_mode' ) || 'conditional' == get_option( 'optimizely_activation_mode' ) ): ?>
  <input type="radio" name="optimizely_activation_mode" value="immediate"> <?php esc_html_e( 'Immediate', 'optimizely' ); ?>
  <input type="radio" name="optimizely_activation_mode" value="conditional" checked> <?php esc_html_e( 'Conditional', 'optimizely' ); ?>
  <div id="optimizely_conditional_activation_code_block">
<?php else:	?>
  <input type="radio" name="optimizely_activation_mode" value="immediate" checked> <?php esc_html_e( 'Immediate', 'optimizely' ); ?>
  <input type="radio" name="optimizely_activation_mode" value="conditional"> <?php esc_html_e( 'Conditional', 'optimizely' ); ?>
  <div id="optimizely_conditional_activation_code_block" style="display:none;">
<?php endif;	?>

  <p><?php esc_html_e( 'You can use the variables $POST_ID and $OLD_TITLE in your code. The code below will activate the experiment if the original title is on the page and its not the first page the user has visited.', 'optimizely' ); ?></p>
  <textarea class="code" rows="5" name="conditional_activation_code" id="conditional_activation_code"><?php echo esc_html( get_option( 'optimizely_conditional_activation_code', OPTIMIZELY_DEFAULT_CONDITIONAL_TEMPLATE ) ) ?></textarea>
</div>
