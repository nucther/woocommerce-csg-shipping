<?php
/**
 * Plugin Name: Consap - Expandore WooCommerce Shipping
 * Description: Private plugin for Expandore
 * Author: Consap
 * Version: 1.0
 */
 
if( !defined('WPINC')){ die('Access Denied'); }

include dirname(__FILE__).'/install.php';
include dirname(__FILE__).'/class_shipping.php';

/**
 * Install
 */

register_activation_hook( __FILE__, 'consap_expandore_shipping_register_db' );

/**
 * Check if WooCommerce is active
 */
if( in_array('woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ))){
    function consap_expandore_shipping_init(){
        if( ! class_exists('WC_Consap_Expandore_Shipping_Method') ){
            class WC_Consap_Expandore_Shipping_method extends WC_Shipping_Method{

                /**
                 * Construct shipping method
                 * 
                 * @access public
                 * @return void
                 */
                public function __construct(){
                    $this->id                   = 'expandore';
                    $this->method_title         = __('Expandore Shipping', 'consap');
                    $this->method_description   = __('Custom Shipping Method for Expandore by Consap', 'consap');

                    $this->enable               = 'yes';


                }

                function init(){
                    $this->init_form_fields();
                    $this->init_settings();

                    add_action( 'woocommerce_update_options_shipping_'. $this->id , array($this, 'process_admin_options'));
                }

                function init_form_fields(){

                }

                public function calculate_shipping($package) {

                }
            }
        }
    }

    add_action( 'woocommerce_shipping_init', 'consap_expandore_shipping_init');

    function add_consap_expandore_shipping_method( $methods ){
        $methods[] = 'WC_Consap_Expandore_Shipping_Method';
        return $methods;
    }

    add_action( 'woocommerce_shipping_methods', 'add_consap_expandore_shipping_method' );
}