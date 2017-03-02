<?php
/**
 * Optimizely X
 *
 * @link https://www.optimizely.com
 *
 * @author Optimizely
 * @copyright 2017 Optimizely
 * @license GPL-2.0+
 * @package Optimizely_X
 * @since 1.0.0
 *
 * @wordpress-plugin
 * Plugin Name: Optimizely X
 * Plugin URI: https://wordpress.org/plugins/optimizely-x/
 * Description: Simple, fast, and powerful. <a href="https://www.optimizely.com">Optimizely</a> is a dramatically easier way for you to improve your website through A/B testing. Create an experiment in minutes with our easy-to-use visual interface with absolutely no coding or engineering required. Convert your website visitors into customers and earn more revenue today! To get started: 1) Click the "Activate" link to the left of this description, 2) Sign up for an <a href="https://www.optimizely.com">Optimizely account</a>, and 3) Create an API Token here: <a href="https://www.optimizely.com/tokens">API Tokens</a>, and enter your API token in the Configuration Tab of the Plugin, then select a project to start testing!
 * Version: 1.0.0
 * Author: Optimizely
 * Author URI: https://www.optimizely.com
 * License: GPL-2.0+
 * License URI: http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain: optimizely_x
 * Domain Path: /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * The base directory for all Optimizely X plugin files.
 *
 * @since 1.0.0
 * @var string
 */
define( 'OPTIMIZELY_X_BASE_DIR', __DIR__ );

/**
 * An autoloader callback for loading classes in the Optimizely_X namespace.
 *
 * @param string $class The class name that was referenced.
 *
 * @private
 */
function optimizely_x_load_class( $class ) {

	// Set project-specific namespace prefix.
	$prefix = 'Optimizely_X\\';

	// Set base directory for the namespace prefix.
	$base_dir = OPTIMIZELY_X_BASE_DIR . '/includes/';

	// Determine if the class uses the namespace prefix.
	$len = strlen( $prefix );
	if ( strncmp( $prefix, $class, $len ) !== 0 ) {
		return;
	}

	// Convert to WordPress-standard class naming convention.
	$relative_class = strtolower( substr( $class, $len ) );
	$relative_class = str_replace(
		array( '_', '\\' ),
		array( '-', '/' ),
		$relative_class
	);
	$pos = strrpos( $relative_class, '/' );
	$pos = ( ! empty( $pos ) ) ? $pos + 1 : 0;
	$relative_class = substr_replace( $relative_class, 'class-', $pos, 0 );

	// Construct the filename using the transformed class name.
	$file = $base_dir . $relative_class . '.php';

	// If the file exists, require it.
	if ( file_exists( $file ) ) {
		require_once $file;
	}
}

// Initialize the autoloader.
spl_autoload_register( 'optimizely_x_load_class' );

// Bootstrap the plugin.
Optimizely_X\Core::instance();
