<h3><?php esc_html_e( 'Maximum number of variations to test', 'optimizely' ); ?></h3>
<p><?php esc_html_e( 'Place a number in the text box below. This will be the maximum additional number of variations a user can test per post.', 'optimizely' ); ?></p>

<input id="optimizely_num_variations" name="optimizely_num_variations" type="number" maxlength="1" value="<?php echo absint( get_option( 'optimizely_num_variations', OPTIMIZELY_NUM_VARIATIONS ) ) ?>" class="" />
