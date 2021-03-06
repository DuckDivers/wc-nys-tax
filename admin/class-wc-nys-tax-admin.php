<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://www.howardehrenberg.com
 * @since      1.0.0
 *
 * @package    Wc_Nys_Tax
 * @subpackage Wc_Nys_Tax/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Wc_Nys_Tax
 * @subpackage Wc_Nys_Tax/admin
 * @author     Howard Ehrenberg <howard@howardehrenberg.com>
 */
class Wc_Nys_Tax_Admin {

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
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/wc-nys-tax-admin.css', array(), $this->version, 'all' );
        wp_enqueue_style( $this->plugin_name . 'jquery-ui-style', '//ajax.googleapis.com/ajax/libs/jqueryui/1.9.0/themes/base/jquery-ui.css',false,"1.9.0",false);

	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

        wp_enqueue_script( $this->plugin_name . 'data-tables', plugin_dir_url( __FILE__ ) . 'js/jquery.dataTables.min.js', array('jquery'), '1.10.15', true);
        wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/wc-nys-tax-admin.js', array( 'jquery' ), '1.0.0', true );
        wp_enqueue_script( 'jquery-ui-datepicker' );
    }
    
    /**
	 * Add an Submenu page under the WooCommerce submenu
	 *
	 * @since  1.0.0
	 */
	public function add_submenu_page() {
		$this->plugin_screen_hook_suffix = add_submenu_page(
            'woocommerce',
            'NY Sales Tax Reports',
            'NY Sales Tax',
            'manage_woocommerce',
			$this->plugin_name,
			array( $this, 'display_main_page' )
		);
	}
    
    public function display_main_page() {
        include_once(WC()->plugin_path().'/includes/admin/reports/class-wc-admin-report.php');
        include_once 'partials/wc-nys-tax-admin-display.php';
    }

}