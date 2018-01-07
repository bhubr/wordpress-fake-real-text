<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://developpeur-web-toulouse.fr/
 * @since             0.2.0
 * @package           Fake_Real_Text
 *
 * @wordpress-plugin
 * Plugin Name:       Fake Real Text
 * Plugin URI:        https://developpeur-web-toulouse.fr/wordpress-fake-real-text-plugin/
 * Description:       This is a short description of what the plugin does. It's displayed in the WordPress admin area.
 * Version:           0.2.0
 * Author:            Benoît Hubert
 * Author URI:        https://developpeur-web-toulouse.fr/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       fake-real-text
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Currently plugin version.
 * Start at version 0.2.0 and use SemVer - https://semver.org
 * Rename this for your plugin and update it as you release new versions.
 */
define( 'PLUGIN_NAME_VERSION', '0.2.0' );

/**
 * Require Composer autoloader
 */
require 'vendor/autoload.php';

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-fake-real-text-activator.php
 */
function activate_fake_real_text() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-fake-real-text-activator.php';
	Fake_Real_Text_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-fake-real-text-deactivator.php
 */
function deactivate_fake_real_text() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-fake-real-text-deactivator.php';
	Fake_Real_Text_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_fake_real_text' );
register_deactivation_hook( __FILE__, 'deactivate_fake_real_text' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-fake-real-text.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    0.2.0
 */
function run_fake_real_text() {

	$plugin = new Fake_Real_Text();
	$plugin->run();

}
run_fake_real_text();
