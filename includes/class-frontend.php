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
 * @since 1.0.0
 */
class Frontend {

	/**
	 * Singleton instance.
	 *
	 * @since 1.0.0
	 * @access private
	 * @var Frontend
	 */
	private static $instance;

	/**
	 * Gets the singleton instance.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @return Frontend
	 */
	public static function instance() {

		// Initialize the instance, if necessary.
		if ( ! isset( self::$instance ) ) {
			self::$instance = new Frontend;
			self::$instance->setup();
		}

		return self::$instance;
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
	 * Registers action and filter hooks.
	 *
	 * @since 1.0.0
	 * @access private
	 */
	private function setup() {

		// Register action hooks.
		add_action( 'wp_head', array( $this, 'inject_script' ), - 1000 );
	}

	// TODO: Refactor from here.

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
	function inject_script() {
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
	 * Check capabilites for creating experiments.
	 */
	function optimizely_can_create_experiments() {
		return get_option( 'optimizely_token', false ) && get_option( 'optimizely_project_id', false ) && intval(get_option( 'optimizely_project_id', false )) != 0;
	}

}
