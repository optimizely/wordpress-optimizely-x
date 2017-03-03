<?php
/**
 * Optimizely X: Frontend class
 *
 * @package Optimizely_X
 * @since 1.0.0
 */

namespace Optimizely_X;

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to enqueue the
 * admin-specific stylesheet and JavaScript.
 *
 * @since 1.0.0
 */
class Frontend {

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
	 * @param      string    $plugin_name       The name of the plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}

	/**
	 * Generates the Optimizely script tag.
	 * @param int $project_code
	 * @return string
	 */
	function optimizely_generate_script( $project_id ) {
		return '<script src="//cdn.optimizely.com/js/' . abs( floatval( $project_id ) ) . '.js"></script>';
	}

	/**
	 * Force Optimizely to load first in the head tag.
	 */
	function optimizely_add_script() {
		$project_code = get_option( 'optimizely_project_code' );
		$project_id = get_option( 'optimizely_project_id' );
		if ( ! empty( $project_id ) ) {
			// This cannot be escaped since optimizely_generate_script returns a script tag.
			// The output of this script is fully escaped within the function below
			echo $this->optimizely_generate_script( $project_id );
		} else if ( ! empty( $project_code ) && false !== strpos( $project_code, 'js' ) && true !== WPCOM_IS_VIP_ENV ) {
			// Older non-VIP sites used an old filled project_code.
			// If this field is filled out we will strip the ID out of the field and use that id.
			// This will execute ONLY on non-VIP sites and is necessary for backwards compatibility.
			$project_id = substr( $project_code, strpos( $project_code,'js' ) + 3 );
			$project_id = substr( $project_id, 0, strpos( $project_id, 'js' ) -1 );
			update_option( 'optimizely_project_id', absint( $project_id ) );
			delete_option( 'optimizely_project_code' );
			echo $this->optimizely_generate_script( $project_id );
		}
	}

	/**
	 * When users go to write their posts, they'll see a new section for A/B testing headlines.
	 * This section will include inputs for users to write alternate headlines and a button to create the experiment.
	 * We also use several hidden input fields to store data about the project and experiment.
	 * These are used in edit.js to send AJAX requests to the Optimizely API.
	 */
	/**
	 * Add the meta box for title variations.
	 */
	function optimizely_title_variations_add() {
		// Only add the module if the current post type is one the user selected in the admin tab.
		if ( $this->optimizely_is_post_type_enabled( get_post_type() ) ) {
			add_meta_box(
				'optimizely-headlines',
				esc_attr__( 'A/B Test Headlines', 'optimizely-x' ),
				array( &$this, 'optimizely_title_variations_render' ),
				get_post_type(),
				'side',
				'high'
			);
		}
	}

	function optimizely_title_variations_render( $post ) {
		$this->enqueue_post_styles();
		$this->enqueue_post_scripts();
		$loading_image = esc_url( plugin_dir_url( __FILE__ ) ).'images/ajax-loader.gif';
		require_once OPTIMIZELY_X_BASE_DIR . '/public/partials/optimizely-x-public-display.php';
	}

	/**
	 * Check if this is a post type that uses Optimizely.
	 * @param string $post_type
	 * @return boolean
	 */
	function optimizely_is_post_type_enabled( $post_type ) {
		$selected_post_types = explode( ',', get_option( 'optimizely_post_types' ) );
		if ( ! empty( $selected_post_types ) && in_array( $post_type, $selected_post_types ) ) {
			return true;
		} else {
			return false;
		}
	}


	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_post_styles() {

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/optimizely-x-public.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_post_scripts() {

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/optimizely-x-public.js', array( 'jquery' ), $this->version, false );

	}

	/**
	 * Check capabilites for creating experiments.
	 */
	function optimizely_can_create_experiments() {
		return get_option( 'optimizely_token', false ) && get_option( 'optimizely_project_id', false ) && intval(get_option( 'optimizely_project_id', false )) != 0;
	}

}
