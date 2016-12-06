<?php

/**
 * @link              https://www.optimizely.com
 * @since             1.0.0
 * @package           Optimizely_X
 *
 * @wordpress-plugin
 * Plugin Name:       Optimizely X
 * Plugin URI:        ttp://wordpress.org/extend/plugins/optimizely-x/
 * Description:       Simple, fast, and powerful.  <a href="http://www.optimizely.com">Optimizely</a> is a dramatically easier way for you to improve your website through A/B testing. Create an experiment in minutes with our easy-to-use visual interface with absolutely no coding or engineering required. Convert your website visitors into customers and earn more revenue today! To get started: 1) Click the "Activate" link to the left of this description, 2) Sign up for an <a href="http://www.optimizely.com">Optimizely account</a>, and 3) Create an API Token here: <a href="https://www.optimizely.com/tokens">API Tokens</a>, and enter your API token in the Configuration Tab of the Plugin, then select a project to start testing!
 * Version:           1.0.0
 * Author:            Optimizely
 * Author URI:        https://www.optimizely.com
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       optimizely_x
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-optimizely-x-activator.php
 */
function activate_plugin_name() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-optimizely-x-activator.php';
	Optimizely_X_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-optimizely-x-deactivator.php
 */
function deactivate_plugin_name() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-optimizely-x-deactivator.php';
	Optimizely_X_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_plugin_name' );
register_deactivation_hook( __FILE__, 'deactivate_plugin_name' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-optimizely-x.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_plugin_name() {

	$plugin = new Optimizely_X();
	$plugin->run();

}
run_plugin_name();
