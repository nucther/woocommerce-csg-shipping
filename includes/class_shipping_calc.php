<?php

/**
 * Class Consap shipping 
 * 
 */
class Consap_Shipping_Class{

    public function Consap_Shipping_Class(){
    }   

    /**
     * Get options value
     * 
     * @since 1.0
     * @param string $ame
     * @return string $value
     */
    public function get_option($name){
        global $wpdb;

        $options = $wpdb->get_results("SELECT * FROM ". $wpdb->prefix ."expandore_shipping WHERE type='option' AND name='". $name ."' LIMIT 0,1");

        return (isset($options[0]->value))? $options[0]->value : '';
    }

    /**
     * Add new option
     * 
     * @since 1.0
     * @param string $name
     * @param string $name
     * @return string
     */
    public function add_option($name, $value){
        global $wpdb;

        $wpdb->insert( $wpdb->prefix .'expandore_shipping', array(
            'name' => esc_attr( $name ),
            'type' => 'option',
            'value' => esc_attr( $value )
        ));

        return $wpdb->inser_id;
    }

    /**
     * Update option 
     * 
     * @since 1.0
     * @param string $name 
     * @param string $value 
     * @return string
     */
    public function update_option($name, $value){
        global $wpdb;

        if( empty($this->get_option($name) ) ){
            return $this->add_option($name, $value);
        }else {
            return $wpdb->update($wpdb->prefix .'expandore_shipping', array('value' => $value), array('name' => $name));
        }
    }

    /**
     * Add new country zone
     * 
     * @since 1.0
     * @param string $provider - ex. FedEx, DHL, TNT etc
     * @param string $country_code 
     * @param string $country_name
     * @param string $condition - postcode, province/district/city
     * @param string $condition_value - ex. post code : 3000-5000 ex. city : jakarta,tangerang,bandung ( use separator - for postcode and comma for city )
     * @param string $zone
     * @return string
     */
    public function add_country($provider, $country_code, $country_name, $condition, $condition_value, $zone){
        global $wpdb;

        $wpdb->insert($wpdb->prefix .'expandore_shipping', array(
            'name' => strtoupper($country_code) .'_'. sanitize_title( esc_attr( $provider ) ).'_'. esc_attr( $country_name ),
            'type' => 'zone',
            'condition_type' => $condition,
            'condition_value' => $condition_value,
            'value' => esc_attr( $zone )
        ));

        return $wpdb->insert_id;
    }

    /**
     * Clean all country zone from selected provider
     * 
     * @since 1.0
     * @param string $provider
     * @return void
     */
    public function clean_country($provider){
        global $wpdb;

        return $wpdb->query("DELETE FROM ". $wpdb->prefix ."expandore_shipping WHERE name like '%_". sanitize_title( esc_attr( $provider ) ) ."_%' AND type='zone'");
    }
    

    public function add_rate($provider, $package, $condition, $zone, $value){
        global $wpdb;

        $wpdb->insert( $wpdb->prefix .'expandore_shipping', array(
            'name' => sanitize_title( esc_attr( $provider ) ) .'_'. sanitize_title( esc_attr($package) ) .'_'. $condition,
            'type' => 'rate',
            'condition_type' => 'zone',
            'condition_value' => esc_attr( $zone ),
            'value' => esc_attr( $value )
        ));

        return $wpdb->insert_id;
    }

    /**
     * Clean rate on selected provider
     * 
     * @since 1.0
     * @param $provider
     * @return void
     */
    public function clean_rate($provider, $package){
        global $wpdb;

        return $wpdb->query("DELETE FROM ". $wpdb->prefix ."expandore_shipping WHERE name like '". sanitize_title( esc_attr( $provider )) ."_". sanitize_title( esc_attr( $package )) ."%' AND type='rate'");
    }


    public function add_package($provider, $package){
        global $wpdb;

        $wpdb->delete( $wpdb->prefix .'expandore_shipping', array('type' => 'package', 'name' => esc_attr( $provider ) .' '. esc_attr( $package )));

        $wpdb->insert( $wpdb->prefix. 'expandore_shipping', array(
            'name' => esc_attr( $provider ) .' '. esc_attr( $package ),
            'type' => 'package',
            'condition_value' => true,
            'value' => sanitize_title( esc_attr( $provider )).'_'. sanitize_title( esc_attr( $package ) )
        ));
    }

    public function get_packages($enableOnly=false){
        global $wpdb;
        $sql = '';        
        if($enableOnly){
            $sql = " AND condition_value='enable'";
        }
        return $wpdb->get_results("SELECT name, condition_value, value FROM ". $wpdb->prefix ."expandore_shipping WHERE type='package'". $sql);
    }

    public function get_shipping($weight, $country, $city, $postcode){
        
    }
}