<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       https://www.howardehrenberg.com
 * @since      1.0.0
 *
 * @package    Wc_Nys_Tax
 * @subpackage Wc_Nys_Tax/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    Wc_Nys_Tax
 * @subpackage Wc_Nys_Tax/public
 * @author     Howard Ehrenberg <howard@howardehrenberg.com>
 */
class Wc_Nys_Tax_Public {

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
	 * @param      string    $plugin_name       The name of the plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}

	/**
	 * Register the hooks for hooking into WooCommerce to add the order meta for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */

     public function add_wc_hooks($order_id){
        global $woocommerce, $wpdb;
         
         $order = wc_get_order($order_id);
         
         if ($order){
             if ($order->get_shipping_postcode()){
                $zip = $order->get_shipping_postcode();
                $state = $order->get_shipping_state(); 
             }
             else {
                 $zip = $order->get_billing_postcode();
                 $state = $order->get_billing_state();
             }
            $table = $wpdb->prefix . 'ny_tax';
            $nycode = $wpdb->get_row("SELECT * from `{$table}` WHERE `zipcode` = {$zip}", ARRAY_A);
            if (null !== $nycode) {
                
                $order->update_meta_data('_nys_jurisdiction', $nycode['region']);   
                $order->save();

            }
         
         }
    
     }

}
