<?php
/**
 * Optimizely X: Core class
 *
 * @package Optimizely_X
 * @since 1.0.0
 */

namespace Optimizely_X;

/**
 * The core plugin class. Keeps track of the plugin version and slug, as well as
 * registering action and filter hooks used by the plugin.
 *
 * @since 1.0.0
 */
class Core {

	/**
	 * The unique identifier of this plugin.
	 *
	 * @since 1.0.0
	 * @var string
	 */
	const PLUGIN_SLUG = 'optimizely_x';

	/**
	 * The current version of the plugin.
	 *
	 * @since 1.0.0
	 * @var string
	 */
	const VERSION = '1.0.0';

	/**
	 * The loader that's responsible for maintaining and registering all hooks that
	 * power the plugin.
	 *
	 * @since 1.0.0
	 * @access protected
	 * @var Loader
	 */
	protected $loader;

	/**
	 * Singleton instance.
	 *
	 * @since 1.0.0
	 * @access private
	 * @var Core
	 */
	private static $instance;

	/**
	 * Gets the singleton instance.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @return Core
	 */
	public static function instance() {

		// Initialize the instance, if necessary.
		if ( ! isset( self::$instance ) ) {
			self::$instance = new Core;
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
	 * Define the core functionality of the plugin.
	 *
	 * Set the plugin name and the plugin version that can be used throughout the
	 * plugin. Load the dependencies, define the locale, and set the hooks for the
	 * admin area and the public-facing side of the site.
	 *
	 * @since 1.0.0
	 * @access private
	 */
	private function setup() {
		Admin::instance();
		AJAX::instance();
		Frontend::instance();
		I18N::instance();

		// TODO: Refactor to use singleton instance pattern.
		$this->loader = new Loader;
		$this->loader->run();
	}
}
