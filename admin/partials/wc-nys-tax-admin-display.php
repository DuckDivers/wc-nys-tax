<?php

/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @link       https://www.howardehrenberg.com
 * @since      1.0.0
 *
 * @package    Wc_Nys_Tax
 * @subpackage Wc_Nys_Tax/admin/partials
 */

$wc_report = new WC_Admin_Report();

if (!isset($_GET['range'])){$current_range = 'month';} else {$current_range=$_GET['range'];}

$nonce = $wc_report->check_current_range_nonce($current_range);

$reporObject = $wc_report->calculate_current_range($current_range);
$startdate = ($wc_report->start_date);
$enddate = ($wc_report->end_date);

$startdate = gmdate("Y-m-d" , $startdate);
$enddate = gmdate("Y-m-d", $enddate);

global $wpdb;
$prefix = $wpdb->prefix;

$sql = "SELECT P.`post_date`, WOI.`order_item_id`, sum( WIM.`meta_value` ) AS taxamount, sum( SHIP.`meta_value`) AS shippingtax, PM.`meta_value` as jurisdiction
        FROM `{$prefix}woocommerce_order_items` WOI 
        RIGHT JOIN `{$prefix}woocommerce_order_itemmeta` WIM on WOI.`order_item_id` = WIM.`order_item_id` AND WIM.`meta_key` = 'tax_amount' 
        RIGHT JOIN `{$prefix}woocommerce_order_itemmeta` SHIP on WOI.`order_item_id` = SHIP.`order_item_id` AND SHIP.`meta_key` = 'shipping_tax_amount'
        RIGHT JOIN `{$prefix}postmeta` PM on PM.`post_id` = WOI.`order_id` AND PM.`meta_key` = '_nys_jurisdiction'
        LEFT JOIN `{$prefix}posts` P on P.`ID` = WOI.`order_id` WHERE WOI.`order_item_name` = 'US-NY-NY TAX-1'
        AND P.`post_date` BETWEEN '{$startdate} 00:00:00' AND '{$enddate} 23:59:59'
        GROUP BY jurisdiction;";

$query = $wpdb->get_results( $sql, ARRAY_A );

$tamount = '';
$tshipping = '';
$ttax = '';

?>

<div class="wrap">
    <h2>New York State Sales Tax Reporting</h2>
    <div class="inside">
        <div class="stats_range">
            <h4 style="margin-bottom: 1rem;">Choose your date range:</h4>
             <ul>
                  <li class="time"><a href="<?php echo admin_url('admin.php?page=wc-nys-tax&amp;range=year');?>">Year</a>
                       </li>
                  <li class="time"><a href="<?php echo admin_url('admin.php?page=wc-nys-tax&amp;range=last_month');?>">Last month</a>
                       </li>
                  <li class="time"><a href="<?php echo admin_url('admin.php?page=wc-nys-tax&amp;range=month');?>">This month</a>
                       </li>
                  <li class="time"><a href="<?php echo admin_url('admin.php?page=wc-nys-tax&amp;range=7day');?>">Last 7 days</a>
                       </li>
                  <li class="custom active">
                       Custom:
                       <form method="GET" class="inline">
                            <div class="inline">
                                 <?php
                                // Maintain query string.
                                    foreach ( $_GET as $key => $value ) {
                                        if ( is_array( $value ) ) {
                                            foreach ( $value as $v ) {
                                                echo '<input type="hidden" name="' . esc_attr( sanitize_text_field( $key ) ) . '[]" value="' . esc_attr( sanitize_text_field( $v ) ) . '" />';
                                            }
                                        } else {
                                            echo '<input type="hidden" name="' . esc_attr( sanitize_text_field( $key ) ) . '" value="' . esc_attr( sanitize_text_field( $value ) ) . '" />';
                                        }
                                    }
                                    ?>
                                    <input type="hidden" name="range" value="custom" />
                                    <input type="text" size="11" placeholder="yyyy-mm-dd" value="<?php echo ( ! empty( $_GET['start_date'] ) ) ? esc_attr( wp_unslash( $_GET['start_date'] ) ) : ''; ?>" name="start_date" class="range_datepicker from" /><?php //@codingStandardsIgnoreLine ?>
                                    <span>&ndash;</span>
                                    <input type="text" size="11" placeholder="yyyy-mm-dd" value="<?php echo ( ! empty( $_GET['end_date'] ) ) ? esc_attr( wp_unslash( $_GET['end_date'] ) ) : ''; ?>" name="end_date" class="range_datepicker to" /><?php //@codingStandardsIgnoreLine ?>
                                    <button type="submit" class="button" value="<?php esc_attr_e( 'Go', 'wc-nys-tax' ); ?>"><?php esc_html_e( 'Go', 'wc-nys-tax' ); ?></button>
                                    <?php wp_nonce_field( 'custom_range', 'wc_reports_nonce', false ); ?>
                               </div>
                            </form>
                       </li>
             </ul>
        </div>
        <table class="widefat" id="report">
            <thead>
                <tr>
                    <th>Jurisdiction</th>
                    <th class="total_row">Tax amount</th>
                    <th class="total_row">Shipping tax amount</th>
                    <th class="total_row">Total tax</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($query as $region) : ?>
                <?php 
                $tax = round($region['taxamount'], 2);
                $shipping = round($region['shippingtax'],2);
                $rowtotal = floatval($tax) + floatval($shipping); 
                
                ?>
                <tr>
                    <td>
                        <?php echo $region['jurisdiction'];?>
                    </td>
                    <td class="total_row"><span class="woocommerce-Price-amount amount"><span class="woocommerce-Price-currencySymbol">$</span>
                        <?php printf("%.2f", $tax);?>
                        </span>
                    </td>
                    <td class="total_row"><span class="woocommerce-Price-amount amount"><span class="woocommerce-Price-currencySymbol">$</span>
                        <?php printf("%.2f", $shipping);?>
                        </span>
                    </td>
                    <td class="total_row"><span class="woocommerce-Price-amount amount"><span class="woocommerce-Price-currencySymbol">$</span>
                        <?php echo $rowtotal;?>
                        </span>
                    </td>
                </tr>
                <?php 
                $tamount = $tamount + $tax;
                $tshipping = $tshipping + $shipping;
                $ttax = $ttax + $rowtotal;
                
                
                endforeach;?>
            </tbody>
            <tfoot>
                <tr>
                    <th></th>
                    <th class="total_row"><strong><span class="woocommerce-Price-amount amount"><span class="woocommerce-Price-currencySymbol">$</span><?php printf("%.2f", $tamount);?></span></strong>
                    </th>
                    <th class="total_row"><strong><span class="woocommerce-Price-amount amount"><span class="woocommerce-Price-currencySymbol">$</span><?php printf("%.2f", $tshipping);?></span></strong>
                    </th>
                    <th class="total_row"><strong><span class="woocommerce-Price-amount amount"><span class="woocommerce-Price-currencySymbol">$</span><?php printf("%.2f", $ttax);?></span></strong>
                    </th>
                </tr>
            </tfoot>
        </table>
    </div>
</div>