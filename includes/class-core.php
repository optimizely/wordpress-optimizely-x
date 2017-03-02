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
	 * Register all of the hooks related to the admin area functionality of the
	 * plugin.
	 *
	 * @since 1.0.0
	 * @access private
	 */
	private function define_admin_hooks() {
		$this->loader->add_action(
			'admin_menu',
			'Optimizely_X\\Admin',
			'optimizely_admin_menu'
		);
		$this->loader->add_action(
			'admin_notices',
			'Optimizely_X\\Admin',
			'optimizely_admin_notices'
		);
		$this->loader->add_action(
			'admin_enqueue_scripts',
			'Optimizely_X\\Admin',
			'enqueue_styles'
		);
		$this->loader->add_action(
			'admin_enqueue_scripts',
			'Optimizely_X\\Admin',
			'enqueue_scripts'
		);
	}

	/**
	 * Register the ajax functions for the admin area.
	 *
	 * @since 1.0.0
	 * @access private
	 */
	private function define_ajax_actions() {
		$this->loader->add_action(
			'wp_ajax_get_projects',
			'Optimizely_X\\AJAX',
			'get_projects'
		);
		$this->loader->add_action(
			'wp_ajax_create_experiment',
			'Optimizely_X\\AJAX',
			'create_experiment'
		);
		$this->loader->add_action(
			'wp_ajax_change_status',
			'Optimizely_X\\AJAX',
			'change_status'
		);
	}

	/**
	 * Register all of the hooks related to the public-facing functionality of the
	 * plugin.
	 *
	 * @since 1.0.0
	 * @access private
	 */
	private function define_public_hooks() {
		$this->loader->add_action(
			'wp_head',
			'Optimizely_X\\Frontend',
			'optimizely_add_script',
			- 1000
		);
		$this->loader->add_action(
			'add_meta_boxes',
			'Optimizely_X\\Frontend',
			'optimizely_title_variations_add'
		);
		$this->loader->add_action(
			'wp_enqueue_scripts',
			'Optimizely_X\\Frontend',
			'enqueue_styles'
		);
		$this->loader->add_action(
			'wp_enqueue_scripts',
			'Optimizely_X\\Frontend',
			'enqueue_scripts'
		);
	}

	/**
	 * Define the locale for this plugin for internationalization.
	 *
	 * Uses the I18N class in order to set the domain and to register the hook with
	 * WordPress.
	 *
	 * @since 1.0.0
	 * @access private
	 */
	private function set_locale() {
		$this->loader->add_action(
			'plugins_loaded',
			'Optimizely_X\\I18N',
			'load_plugin_textdomain'
		);
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
		$this->loader = new Loader;
		$this->set_locale();
		$this->define_admin_hooks();
		$this->define_public_hooks();
		$this->define_ajax_actions();
		$this->loader->run();
	}
}
