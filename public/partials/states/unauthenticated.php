<p>
<?php
$message = sprintf(
  '%s <a href="%s">%s</a>',
  esc_html__( 'Please configure your API credentials and Project in the', 'optimizely' ),
  esc_url( menu_page_url( 'optimizely-config', false ) ),
  esc_html__( 'Optimizely settings page', 'optimizely' )
);
echo($message);
?>.
</p>
