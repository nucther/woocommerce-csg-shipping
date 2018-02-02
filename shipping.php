<?php
/**
 * Plugin Name: Consap - Expandore WooCommerce Shipping
 * Description: Private plugin for Expandore
 * Author: Consap
 * Version: 1.0
 */
 
if( !defined('ABSPATH')){ die('Access Denied'); }

define('EXPANDORE_VERSION','1.0');
define('EXPANDORE_PATH', plugin_dir_path( __FILE__ ));
define('EXPANDORE_URL', plugin_dir_url( __FILE__ ));

/**
 * Load activation 
 */
require_once EXPANDORE_PATH .'/install.php';
register_activation_hook( __FILE__, 'consap_expandore_shipping_register_db' );

/**
 * Check if WooCommerce is active
 */
if( in_array('woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ))){
    function consap_expandore_shipping_init(){
        require_once EXPANDORE_PATH . '/includes/class_shipping_calc.php';
        require_once EXPANDORE_PATH . '/includes/class_shipping.php';        
    }

    add_action( 'woocommerce_shipping_init', 'consap_expandore_shipping_init');

    function add_consap_expandore_shipping_method( $methods ){
        $methods[] = 'WC_Consap_Expandore_Shipping_Method';
        return $methods;
    }

    add_action( 'woocommerce_shipping_methods', 'add_consap_expandore_shipping_method' );
}