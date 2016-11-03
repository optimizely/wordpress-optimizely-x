<?php

/**
 * @link       https://www.optimizely.com
 * @since      1.0.0
 *
 * @package    Optimizely_X
 * @subpackage Optimizely_X/public/partials
 */

if ( ! $this->optimizely_can_create_experiments() ) {
  require_once plugin_dir_path( __FILE__ ) . 'states/unauthenticated.php';
} else {
  if(get_post_status($post->ID) == 'publish'){
    require_once plugin_dir_path( __FILE__ ) . 'states/unauthenticated.php';
  } else {
    require_once plugin_dir_path( __FILE__ ) . 'states/unpublished.php';
  }
}
?>
