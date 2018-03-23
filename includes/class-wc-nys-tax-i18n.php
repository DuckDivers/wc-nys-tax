<?php

/**
 * Define the internationalization functionality
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @link       https://www.howardehrenberg.com
 * @since      1.0.0
 *
 * @package    Wc_Nys_Tax
 * @subpackage Wc_Nys_Tax/includes
 */

/**
 * Define the internationalization functionality.
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @since      1.0.0
 * @package    Wc_Nys_Tax
 * @subpackage Wc_Nys_Tax/includes
 * @author     Howard Ehrenberg <howard@howardehrenberg.com>
 */
class Wc_Nys_Tax_i18n {


	/**
	 * Load the plugin text domain for translation.
	 *
	 * @since    1.0.0
	 */
	public function load_plugin_textdomain() {

		load_plugin_textdomain(
			'wc-nys-tax',
			false,
			dirname( dirname( plugin_basename( __FILE__ ) ) ) . '/languages/'
		);

	}



}
