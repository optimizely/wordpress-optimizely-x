<?php

/**
 * @link       https://www.optimizely.com
 * @since      1.0.0
 *
 * @package    Optimizely_X
 * @subpackage Optimizely_X/public/partials
 */

 /**
  * Return the meta key format used for all post title variations.
  * @param int $i
  * @return string
  */
function optimizely_meta_key( $i ) {
  return 'post_title_' . absint( $i );
}

echo '<div class="optimizely_loading hidden">';
require_once plugin_dir_path( __FILE__ ) . 'states/loading.php';
echo '</div>';

if ( ! $this->optimizely_can_create_experiments() ) {
  require_once plugin_dir_path( __FILE__ ) . 'states/unauthenticated.php';
} else {
  if(get_post_status($post->ID) == 'publish'){
    if(get_post_meta( $post->ID, 'optimizely_experiment_id', true )){
      echo '<div class="optimizely_running_experiment">';
      require_once plugin_dir_path( __FILE__ ) . 'states/running.php';
      echo '</div>';
    } else {
      echo '<div class="optimizely_new_experiment">';
      require_once plugin_dir_path( __FILE__ ) . 'states/new.php';
      echo '</div>';
      echo '<div class="hidden optimizely_running_experiment">';
        require_once plugin_dir_path( __FILE__ ) . 'states/running.php';
      echo '</div>';
    }
  } else {
    require_once plugin_dir_path( __FILE__ ) . 'states/unpublished.php';
  }
}
?>
