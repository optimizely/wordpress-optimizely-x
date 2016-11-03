<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://www.optimizely.com
 * @since      1.0.0
 *
 * @package    Optimizely_X
 * @subpackage Optimizely_X/admin
 */
define( 'OPTIMIZELY_DEFAULT_VARIATION_TEMPLATE', 'var utils = window[\'optimizely\'].get(\'utils\');
utils.waitForElement(\'.post-$POST_ID h2\').then(function(){
  var element = document.querySelector(\'.post-$POST_ID h2\');
  element.innerHTML = \'new title\';
});'
);
define( 'OPTIMIZELY_DEFAULT_CONDITIONAL_TEMPLATE', 'function pollingFn() {
  return document.querySelectorAll(\'.post-$POST_ID\').length > 0;
}' );
define( 'OPTIMIZELY_NUM_VARIATIONS', 2 );
define( 'OPTIMIZELY_NONCE', 'optimizely-update-code' );
/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Optimizely_X
 * @subpackage Optimizely_X/admin
 * @author     Your Name <email@example.com>
 */
class Optimizely_X_Admin {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}

  /**
   * Display admin notices for the plugin.
   */
  function optimizely_admin_notices() {
  	if ( ! get_option( 'optimizely_token' ) && ! isset( $_POST['submit'] ) ):
  		?>
  		<div id="optimizely-warning" class="updated fade">
  			<p><strong><?php echo sprintf(
  				'%s <a href="https://app.optimizely.com/tokens" target="_blank">%s</a> %s <a href="admin.php?page=optimizely-config#tabs-2">%s</a>.',
  				esc_html__( 'Optimizely is almost ready. You must first add your', 'optimizely' ),
  				esc_html__( 'API Token', 'optimizely' ),
  				esc_html__( 'in the', 'optimizely' ),
  				esc_html__( 'configuration tab', 'optimizely' )
  			);?></strong></p>
  		</div>
  		<?php
  	endif;
  	if ( get_option( 'optimizely_token' ) && ! get_option( 'optimizely_project_id' ) && ! isset( $_POST['submit'] ) ):
  		?>
  		<div id="optimizely-warning" class="updated fade">
  			<p><strong><?php esc_html_e( 'Optimizely is almost ready. You must choose a project', 'optimizely' ) ?>.</strong>
  			</p>
  		</div>
  		<?php
  	endif;
  }

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

	}

  /**
	 * Register the stylesheets for the admin area if the configuration page is
   * loaded.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_configuration_styles() {
		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/optimizely-x-admin.css', array( ), $this->version, 'all' );
		wp_enqueue_style( 'jquery_ui_styles', plugins_url( 'css/jquery-ui.css', __FILE__ ) );
	}


	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

	}

  /**
   * Register the JavaScript for the admin area if the configuration page is
   * loaded.
   *
   * @since    1.0.0
   */
  public function enqueue_configuration_scripts() {
    wp_enqueue_script( 'beautify-js', plugin_dir_url( __FILE__ ) . 'js/beautify.min.js', array( ), $this->version, false);
    wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/optimizely-x-admin.js', array( 'jquery','jquery-ui-core', 'jquery-ui-tabs','jquery-ui-progressbar','jquery-ui-tooltip'), $this->version, false );

  }

	public function optimizely_store_post_data() {

		if ( ! current_user_can( apply_filters( 'optimizely_admin_capability', 'manage_options' ) ) ) {
			die( __( 'Cheatin&#8217; uh?', 'optimizely' ) );
		}

		// Check the nonce
		check_admin_referer( OPTIMIZELY_NONCE );

		// Sanitize values
		$token = sanitize_text_field( $_POST['token'] );
		$project_id = sanitize_text_field( $_POST['project_id'] );
		$num_variations = sanitize_text_field( $_POST['optimizely_num_variations'] );
		$optimizely_post_types = array_map( 'sanitize_text_field', $_POST['optimizely_post_types'] );
		$optimizely_visitor_count = str_replace( ',', '', sanitize_text_field( $_POST['optimizely_visitor_count'] ) );
		$project_name = sanitize_text_field( stripcslashes( $_POST['project_name'] ) );
		$variation_template = sanitize_text_field( stripcslashes( $_POST['variation_template' ] ) );
		$activation_mode = sanitize_text_field( $_POST['optimizely_activation_mode' ] );
		$conditional_activation_code = sanitize_text_field( stripcslashes( $_POST['conditional_activation_code' ] ) );
		$optimizely_url_targeting_type = sanitize_text_field( $_POST['optimizely_url_targeting_type'] );
		$optimizely_url_targeting = sanitize_text_field( $_POST['optimizely_url_targeting'] );

    //2:aa523e0c-U2UK_-avHKWpbBb_-hjBujRAf82wsHYt7W4YqVaehg

		// Either save or delete/set a default if empty for each value
		if ( !empty( $token ) && $token != hash('ripemd160', get_option( 'optimizely_token' ) ) ) {
			update_option( 'optimizely_token', $token );
		}

		if ( empty( $project_id ) ) {
			delete_option( 'optimizely_project_id' );
		} else {
			update_option( 'optimizely_project_id', $project_id );
		}

		if ( empty( $num_variations ) ) {
			delete_option( 'optimizely_num_variations' );
		} else {
			update_option( 'optimizely_num_variations', $num_variations );
		}

		if ( empty( $optimizely_post_types ) ) {
			update_option( 'optimizely_post_types', '' );
		} else {
			$post_type_string = '';
			foreach ( $optimizely_post_types as $post_type ) {
				$post_type_string = $post_type_string . $post_type . ',';
			}
			update_option( 'optimizely_post_types', trim( $post_type_string, ',' ) );
		}

		if ( empty( $project_name ) ) {
			delete_option( 'optimizely_project_name' );
		} else {
			update_option( 'optimizely_project_name', $project_name );
		}

		if ( empty( $variation_template ) ) {
			update_option( 'optimizely_variation_template', OPTIMIZELY_DEFAULT_VARIATION_TEMPLATE );
		} else {
			update_option( 'optimizely_variation_template', $variation_template );
		}

		if ( empty( $conditional_activation_code ) ) {
			update_option( 'optimizely_conditional_activation_code', OPTIMIZELY_DEFAULT_CONDITIONAL_TEMPLATE );
		} else {
			update_option( 'optimizely_conditional_activation_code', $conditional_activation_code );
		}

		if ( empty( $activation_mode ) ) {
			delete_option( 'optimizely_activation_mode', 'immediate' );
		} else {
			update_option( 'optimizely_activation_mode', $activation_mode );
		}

		if ( empty( $optimizely_url_targeting ) ) {
			delete_option( 'optimizely_url_targeting', get_site_url() );
		} else {
			update_option( 'optimizely_url_targeting', $optimizely_url_targeting );
		}

		if ( empty( $optimizely_url_targeting_type ) ) {
			delete_option( 'optimizely_url_targeting_type', 'substring' );
		} else {
			update_option( 'optimizely_url_targeting_type', $optimizely_url_targeting_type );
		}

		?>
		<div id="message" class="updated fade"><p><strong><?php esc_html_e( 'Settings saved', 'optimizely' ) ?>.</strong></p></div>
		<?php

	}

	public function optimizely_configuration() {

    $this->enqueue_configuration_scripts();
    $this->enqueue_configuration_styles();

		echo('<div class="optimizely_admin">');

		if ( isset( $_POST['submit'] ) ) {
			Optimizely_X_Admin::optimizely_store_post_data();
		}

		$loading_image = esc_url( plugin_dir_url( __FILE__ ) ).'images/ajax-loader.gif';

		// Display the config form.
		if(get_option('optimizely_token')){
			$token_set = true;
		}
		include( dirname( __FILE__ ) . '/partials/optimizely-x-admin-display.php' );
		echo('</div>');
	}

	/**
	 * Add Optimizely to the admin menu.
	 */
	function optimizely_admin_menu() {

		add_menu_page(
		  __( 'Optimizely', 'optimizely' ),
			__( 'Optimizely', 'optimizely' ),
			apply_filters( 'optimizely_admin_capability', 'manage_options' ),
			'optimizely-config',
			array(&$this, 'optimizely_configuration'),
			plugin_dir_url( __FILE__ ) . 'images/optimizely-icon.png'
		);
	}


}
