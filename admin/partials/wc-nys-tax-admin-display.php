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

global $wpdb;
$prefix = $wpdb->prefix;

$sql = "SELECT WOI.`order_item_id`, sum( WIM.`meta_value` ) AS taxamount, sum( SHIP.`meta_value`) AS shippingtax, PM.`meta_value` as jurisdiction
FROM `{$prefix}woocommerce_order_items` WOI 
RIGHT JOIN `{$prefix}woocommerce_order_itemmeta` WIM on WOI.`order_item_id` = WIM.`order_item_id` AND WIM.`meta_key` = 'tax_amount' 
RIGHT JOIN `{$prefix}woocommerce_order_itemmeta` SHIP on WOI.`order_item_id` = SHIP.`order_item_id` AND SHIP.`meta_key` = 'shipping_tax_amount'
RIGHT JOIN `{$prefix}postmeta` PM on PM.`post_id` = WOI.`order_id` AND PM.`meta_key` = '_nys_jurisdiction'
WHERE WOI.`order_item_name` = 'US-NY-NY TAX-1'
GROUP BY jurisdiction;";

$query = $wpdb->get_results($sql, ARRAY_A);    

$tamount = '';
$tshipping = '';
$ttax = '';

?>

<div class="wrap">
    <h2>New York State Sales Tax Reporting</h2>
    <form action="options.php" method="post">
       <div class="inside">
            <table class="widefat">
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
                    <td><?php echo $region['jurisdiction'];?></td>
                    <td class="total_row"><span class="woocommerce-Price-amount amount"><span class="woocommerce-Price-currencySymbol">$</span><?php printf("%.2f", $tax);?></span></td>
                    <td class="total_row"><span class="woocommerce-Price-amount amount"><span class="woocommerce-Price-currencySymbol">$</span><?php printf("%.2f", $shipping);?></span></td>
                    <td class="total_row"><span class="woocommerce-Price-amount amount"><span class="woocommerce-Price-currencySymbol">$</span><?php echo $rowtotal;?></span></td>
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
                        <th class="total_row"><strong><span class="woocommerce-Price-amount amount"><span class="woocommerce-Price-currencySymbol">$</span><?php printf("%.2f", $tamount);?></span></strong></th>
						<th class="total_row"><strong><span class="woocommerce-Price-amount amount"><span class="woocommerce-Price-currencySymbol">$</span><?php printf("%.2f", $tshipping);?></span></strong></th>
                        <th class="total_row"><strong><span class="woocommerce-Price-amount amount"><span class="woocommerce-Price-currencySymbol">$</span><?php printf("%.2f", $ttax);?></span></strong></th>
					</tr>
				</tfoot>
                </table>
            </div>
    </form>
</div>

