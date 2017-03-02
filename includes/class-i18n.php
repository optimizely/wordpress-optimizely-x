<?php
/**
 * Optimizely X: I18N class
 *
 * @package Optimizely_X
 * @since 1.0.0
 */

namespace Optimizely_X;

/**
 * Define the internationalization functionality.
 *
 * Loads and defines the internationalization files for this plugin so that it is
 * ready for translation.
 *
 * @since 1.0.0
 */
class I18N {

	/**
	 * Load the plugin text domain for translation.
	 *
	 * @since 1.0.0
	 * @access public
	 */
	public function load_plugin_textdomain() {
		load_plugin_textdomain(
			'optimizely-x',
			false,
			OPTIMIZELY_X_BASE_DIR . '/languages/'
		);
	}
}
