<?php

/**
 * Fired during plugin activation
 *
 * @link       https://https://github.com/blitheforge
 * @since      1.0.0
 *
 * @package    Sohojpaybd
 * @subpackage Sohojpaybd/includes
 */

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      1.0.0
 * @package    Sohojpaybd
 * @subpackage Sohojpaybd/includes
 * @author     Blithe Forge <blitheforge@gmail.com>
 */
class Sohojpaybd_Activator
{
    public static function activate()
    {
        global $wpdb;
        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
        $charset_collate = $wpdb->get_charset_collate();
        $table1_name = $wpdb->prefix . 'sohojpay_transactions';

        // Create transactions table
        $sql1 = "CREATE TABLE $table1_name (
            ID bigint(20) NOT NULL AUTO_INCREMENT,
            order_id varchar(100) NOT NULL,
            trx_id varchar(50) DEFAULT NULL,
            invoice_id varchar(100) NOT NULL,
            payment_id varchar(50) DEFAULT NULL,
            amount decimal(15,2) NOT NULL,
            currency varchar(10) NOT NULL,
            status varchar(50) DEFAULT NULL,
            datetime timestamp NULL DEFAULT NULL,
            PRIMARY KEY (ID)
        ) $charset_collate;";
        dbDelta($sql1);
        $wpdb->query("ALTER TABLE $table1_name MODIFY ID bigint(20) NOT NULL AUTO_INCREMENT;");

        // Ensure WooCommerce is active
        if (!in_array('woocommerce/woocommerce.php', apply_filters('active_plugins', get_option('active_plugins')))) {
            return;
        }

        // Get enabled payment gateways
        $enabled_gateways = get_option('woocommerce_gateway_order', []);
        
        // Add SohojpayBD if not already enabled
        if (!in_array('sohojpaybd', $enabled_gateways)) {
            $enabled_gateways[] = 'sohojpaybd';
            update_option('woocommerce_gateway_order', $enabled_gateways);
        }

        // Set default settings for SohojpayBD
        update_option('woocommerce_sohojpaybd_settings', [
            'enabled'      => 'yes',
            'title'        => 'Sohojpay BD',
            'description'  => 'Pay securely using Bkash, Nagad, Rocket, and other BD gateways.',
            'instructions' => 'Follow the payment instructions provided at checkout.',
        ]);
    }
}

