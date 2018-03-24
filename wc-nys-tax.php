<?php

/**
 * @link              https://www.howardehrenberg.com
 * @since             1.0.0
 * @package           Wc_Nys_Tax
 *
 * @wordpress-plugin
 * Plugin Name:       WC NYS Sales Tax Jurisdictions
 * Plugin URI:        https://www.duckdiverllc.com
 * Description:       Adds the New York State Tax Jurisdictions to the ordering process, and creates a simple sales tax report with breakdowns by taxing jurisdiction
 * Version:           1.0.0
 * Author:            Howard Ehrenberg
 * Author URI:        https://www.howardehrenberg.com
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       wc-nys-tax
 * Domain Path:       /languages
 * WC requires at least: 2.2
 * WC tested up to: 3.3
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Currently plugin version.
 * Start at version 1.0.0 and use SemVer - https://semver.org
 * Rename this for your plugin and update it as you release new versions.
 */
define( 'DD_WC_TAX_VERSION', '1.0.0' );

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-wc-nys-tax-activator.php
 */
function activate_wc_nys_tax() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-wc-nys-tax-activator.php';
	Wc_Nys_Tax_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-wc-nys-tax-deactivator.php
 */
function deactivate_wc_nys_tax() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-wc-nys-tax-deactivator.php';
	Wc_Nys_Tax_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_wc_nys_tax' );
register_deactivation_hook( __FILE__, 'deactivate_wc_nys_tax' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-wc-nys-tax.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_wc_nys_tax() {

	$plugin = new Wc_Nys_Tax();
	$plugin->run();

}
run_wc_nys_tax();
