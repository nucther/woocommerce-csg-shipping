<?php

/**
 * Class Consap shipping 
 * 
 */
class Consap_Shipping_Class{

    public function Consap_Shipping_Class(){
    }

    public function get_shipping_types(){
    }


    public function get_option($name){
        global $wpdb;

        $options = $wpdb->get_results("SELECT * FROM ". $wpdb->prefix ."expandore_shipping WHERE type='option' AND name='". $name ."' LIMIT 0,1");

        return (isset($options[0]->value))? $options[0]->value : '';
    }

    public function add_option($name, $value){
        global $wpdb;

        $wpdb->insert( $wpdb->prefix .'expandore_shipping', array(
            'name' => esc_attr( $name ),
            'type' => 'option',
            'value' => esc_attr( $value )
        ));

        return $wpdb->inser_id;
    }

    public function update_option($name, $value){
        global $wpdb;

        if( empty($this->get_option($name) ) ){
            return $this->add_option($name, $value);
        }else {
            return $wpdb->update($wpdb->prefix .'expandore_shipping', array('value' => $value), array('name' => $name));
        }
    }
    
}