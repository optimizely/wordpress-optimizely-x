<?php

/**
 *
 * @link       https://www.optimizely.com
 * @since      1.0.0
 *
 * @package    Optimizely_X
 * @subpackage Optimizely_X/admin/partials
 */
?>

<div id="optimizely-tabs">

	<div id="optimizely_introduction" class='section'>
		<?php include( dirname( __FILE__ ) . '/sections/intro.php' ); ?>
		<?php echo($response); ?>
	</div>


  <div>
	  <div class="unauthenticated hidden ui-widget-content ui-corner-all">
      <div class="invalid_token hidden">
    		<div class="narrow">
    			<div class="ui-widget">
    		    <div class="ui-state-error ui-corner-all" style="padding: 0 .7em;">
    		        <p>
    							<span class="ui-icon ui-icon-alert" style="float: left; margin-right: .3em;"></span>
    		          <strong>Alert:</strong> A request with the Personal Token that you have previously saved failed. Either submit a new token or click the retry button below.
    		        </p>
    		    </div>
    			</div>
    		</div>
    	</div>
  		<div class="narrow">
  			<form action="" method="post" id="optimizely-conf">
  				<div class="optimizely_authentication" class='section ui-tabs-panel'>
  					<?php include( dirname( __FILE__ ) . '/sections/auth.php' ); ?>
  				</div>
  			</form>
  		</div>
  	</div>

  	<div class="loading">
  		<p>Loading your configuration ...</p>
  		<img src="<?php echo $loading_image ?>" />
    </div>
  </div>

  <div class="authenticated hidden">
    <ul class="tabs-header" id="tabs-header">
      <!--<li><a href="#tabs-1"><?php echo esc_html_e( 'Results', 'optimizely' ) ?></a></li>-->
      <li><a href="#tabs-2"><?php echo esc_html_e( 'Configuration', 'optimizely' ) ?></a></li>
      <li><a href="#tabs-3"><?php echo esc_html_e( 'Re-Authenticate', 'optimizely' ) ?></a></li>
    </ul>

    <!-- <div id="tabs-1" class="tabs">
      <div class="narrow">
        <form action="" method="post" id="optimizely-conf">
          <div id="optimizely_results" class='section'>
            <?php include( dirname( __FILE__ ) . '/sections/results.php' ); ?>
          </div>
        </form>
      </div>
    </div> -->

    <div id="tabs-2" class="tabs">
      <div class="narrow">
        <form action="" method="post" id="optimizely-conf">
          <?php
            wp_nonce_field( OPTIMIZELY_NONCE );
          ?>
          <div id="optimizely_project" class='section'>
            <?php include( dirname( __FILE__ ) . '/sections/project.php' ); ?>
          </div>
          <div id="optimizely_post_types" class='section'>
            <?php include( dirname( __FILE__ ) . '/sections/post_types.php' ); ?>
          </div>
          <div id="optimizely_urls" class='section'>
            <?php include( dirname( __FILE__ ) . '/sections/urls.php' ); ?>
          </div>
          <div id="optimizely_variation" class='section'>
            <?php include( dirname( __FILE__ ) . '/sections/variation.php' ); ?>
          </div>
          <div id="optimizely_activation" class='section'>
            <?php include( dirname( __FILE__ ) . '/sections/activation.php' ); ?>
          </div>
          <div id="optimizely_number" class='section'>
            <?php include( dirname( __FILE__ ) . '/sections/number.php' ); ?>
          </div>
          <p class="submit"><input type="submit" name="submit" value="<?php esc_html_e( 'Submit &raquo;', 'optimizely' ); ?>" class="button-primary" /></p>
        </form>
      </div>
    </div>

    <div id="tabs-3" class="tabs">
      <div class="narrow">
        <form action="" method="post" id="optimizely-conf">
          <div class="optimizely_authentication" class='section'>
            <?php include( dirname( __FILE__ ) . '/sections/auth.php' ); ?>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>
