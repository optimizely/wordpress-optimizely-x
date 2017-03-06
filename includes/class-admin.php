<?php
/**
 * Optimizely X: Admin class
 *
 * @package Optimizely_X
 * @since 1.0.0
 */

namespace Optimizely_X;

// TODO: Convert these to class constants.
define( 'OPTIMIZELY_DEFAULT_VARIATION_TEMPLATE', 'var utils = window[\'optimizely\'].get(\'utils\');
utils.waitForElement(\'.post-$POST_ID h1\').then(function() {
    var element = document.querySelector(\'.post-$POST_ID h1\');
    element.innerHTML = \'$NEW_TITLE\';
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
 * @since 1.0.0
 */
class Admin {

	/**
	 * Singleton instance.
	 *
	 * @since 1.0.0
	 * @access private
	 * @var Admin
	 */
	private static $instance;

	/**
	 * Gets the singleton instance.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @return Admin
	 */
	public static function instance() {

		// Initialize the instance, if necessary.
		if ( ! isset( self::$instance ) ) {
			self::$instance = new Admin;
			self::$instance->setup();
		}

		return self::$instance;
	}

	/**
	 * Add Optimizely to the admin menu.
	 *
	 * @since 1.0.0
	 * @access public
	 */
	public function add_menu_page() {
		add_menu_page(
			__( 'Optimizely', 'optimizely-x' ),
			__( 'Optimizely', 'optimizely-x' ),
			// TODO: Move to central filter class and add appropriate docblock.
			apply_filters( 'optimizely_admin_capability', 'manage_options' ),
			'optimizely-config',
			array( $this, 'optimizely_configuration' ),
			OPTIMIZELY_X_BASE_URL . '/admin/images/optimizely-icon.png'
		);
	}

	/**
	 * Add the meta box for title variations.
	 *
	 * @since 1.0.0
	 * @access public
	 */
	public function add_meta_boxes() {

		// Ensure Optimizely is enabled for this post type.
		if ( ! $this->is_post_type_enabled( get_post_type() ) ) {
			return;
		}

		// Add the meta box.
		add_meta_box(
			'optimizely-headlines',
			esc_attr__( 'A/B Test Headlines', 'optimizely-x' ),
			array( $this, 'metabox_headlines_render' ),
			get_post_type(),
			'side',
			'high'
		);
	}

	/**
	 * Display admin notices for the plugin.
	 *
	 * @todo Move this to a partial.
	 *
	 * @access public
	 */
	public function admin_notices() {
		if ( ! get_option( 'optimizely_token' ) && ! isset( $_POST['submit'] ) ) :
			?>
			<div id="optimizely-warning" class="updated fade">
				<p><strong><?php printf(
					esc_html__( 'Optimizely is almost ready. You must first add your %1$sAPI Token%2$s in the %3$sconfiguration tab%4$s.', 'optimizely-x' ),
					'<a href="https://app.optimizely.com/tokens" target="_blank">',
					'</a>',
					'<a href="' . esc_url( menu_page_url( 'optimizely-config', false ) . '#tabs-2' ) . '">',
					'</a>'
				); ?></strong></p>
			</div>
			<?php
		endif;
		if ( get_option( 'optimizely_token' ) && ! get_option( 'optimizely_project_id' ) && ! isset( $_POST['submit'] ) ) :
			?>
			<div id="optimizely-warning" class="updated fade">
				<p>
					<strong><?php esc_html_e( 'Optimizely is almost ready. You must choose a project.', 'optimizely-x' ); ?></strong>
				</p>
			</div>
			<?php
		endif;
	}

	/**
	 * Enqueues scripts and styles on Optimizely admin pages.
	 *
	 * @param string $hook The admin page the hook was called from.
	 *
	 * @access public
	 */
	public function enqueue_scripts( $hook ) {

		// Enqueue scripts and styles for the configuration page.
		if ( 'toplevel_page_optimizely-config' === $hook ) {

			// Enqueue main admin stylesheet.
			wp_enqueue_style(
				Core::PLUGIN_SLUG . '_admin_style',
				OPTIMIZELY_X_BASE_URL . '/admin/css/optimizely-x-admin.css',
				array(),
				Core::VERSION
			);

			// Enqueue Optimizely jQuery UI stylesheet.
			wp_enqueue_style(
				Core::PLUGIN_SLUG . '_admin_jquery_ui_style',
				OPTIMIZELY_X_BASE_URL . '/admin/css/jquery-ui.css',
				array(),
				Core::VERSION
			);

			// Enqueue beautify.js.
			wp_enqueue_script(
				Core::PLUGIN_SLUG . '_beautify_js',
				OPTIMIZELY_X_BASE_URL . '/admin/js/beautify.min.js',
				array(),
				Core::VERSION
			);

			// Enqueue main admin script.
			wp_enqueue_script(
				Core::PLUGIN_SLUG . '_admin_script',
				OPTIMIZELY_X_BASE_URL . '/admin/js/optimizely-x-admin.js',
				array(
					'jquery',
					'jquery-ui-core',
					'jquery-ui-tabs',
					'jquery-ui-progressbar',
					'jquery-ui-tooltip',
				),
				Core::VERSION
			);
		}

		// Enqueue scripts and styles for the post edit screen.
		if ( 'post.php' === $hook ) {

			// Enqueue the meta box style.
			wp_enqueue_style(
				Core::PLUGIN_SLUG . '_meta_box_style',
				OPTIMIZELY_X_BASE_URL . '/public/css/optimizely-x-public.css',
				array(),
				Core::VERSION
			);

			// Enqueue the meta box script.
			wp_enqueue_script(
				Core::PLUGIN_SLUG . '_meta_box_script',
				OPTIMIZELY_X_BASE_URL . '/public/js/optimizely-x-public.js',
				array( 'jquery' ),
				Core::VERSION
			);
		}
	}

	/**
	 * Display the contents of the meta box.
	 *
	 * @param \WP_Post $post The post object for which to render the metabox.
	 *
	 * @access public
	 */
	public function metabox_headlines_render( $post ) {
		require_once OPTIMIZELY_X_BASE_DIR . '/public/partials/optimizely-x-public-display.php';
	}

	/**
	 * Empty clone method, forcing the use of the instance() method.
	 *
	 * @see self::instance()
	 *
	 * @access private
	 */
	private function __clone() {
	}

	/**
	 * Empty constructor, forcing the use of the instance() method.
	 *
	 * @see self::instance()
	 *
	 * @access private
	 */
	private function __construct() {
	}

	/**
	 * Empty wakeup method, forcing the use of the instance() method.
	 *
	 * @see self::instance()
	 *
	 * @access private
	 */
	private function __wakeup() {
	}

	/**
	 * Check if this is a post type that uses Optimizely.
	 *
	 * @param string $post_type The post type to check.
	 *
	 * @since 1.0.0
	 * @access private
	 * @return bool
	 */
	private function is_post_type_enabled( $post_type ) {

		// Convert selected post types to an array.
		$selected_post_types = explode( ',', get_option( 'optimizely_post_types' ) );

		return ( is_array( $selected_post_types )
			&& in_array( $post_type, $selected_post_types, true )
		);
	}

	/**
	 * Registers action and filter hooks.
	 *
	 * @since 1.0.0
	 * @access private
	 */
	private function setup() {

		// Register action hooks.
		add_action( 'add_meta_boxes', array( $this, 'add_meta_boxes' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_scripts' ) );
		add_action( 'admin_menu', array( $this, 'add_menu_page' ) );
		add_action( 'admin_notices', array( $this, 'admin_notices' ) );
	}

	// TODO: Refactor from here down.

	public function optimizely_store_post_data() {

		if ( ! current_user_can( apply_filters( 'optimizely_admin_capability', 'manage_options' ) ) ) {
			die( esc_html__( 'Cheatin&#8217; uh?', 'optimizely-x' ) );
		}

		// Check the nonce
		check_admin_referer( OPTIMIZELY_NONCE );

		// Sanitize values
		$token = sanitize_text_field( $_POST['token'] );
		$project_id = sanitize_text_field( $_POST['project_id'] );
		$num_variations = sanitize_text_field( $_POST['optimizely_num_variations'] );
		$optimizely_post_types = $_POST['optimizely_post_types'];
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
    if(empty( $token )){
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
    }
		?>
		<div id="message" class="updated fade"><p><strong><?php esc_html_e( 'Settings saved.', 'optimizely-x' ); ?></strong></p></div>
		<?php

	}

	public function optimizely_configuration() {

		echo('<div class="optimizely_admin">');

		if ( isset( $_POST['submit'] ) ) {
			self::optimizely_store_post_data();
		}

		$loading_image = esc_url( plugin_dir_url( __FILE__ ) ).'images/ajax-loader.gif';

		// Display the config form.
		if(get_option('optimizely_token')){
			$token_set = true;
		}
		include( dirname( __FILE__ ) . '/partials/optimizely-x-admin-display.php' );
		echo('</div>');
	}
}
