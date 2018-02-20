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
            'name' => strtoupper($country_code) .'_'. esc_attr( $country_name ),
            'type' => 'zone',
            'provider' => sanitize_title( esc_attr( $provider ) ),
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
        return $wpdb->query("DELETE FROM ". $wpdb->prefix ."expandore_shipping WHERE provider='". sanitize_title( esc_attr( $provider ) ) ."' AND type='zone'");
    }
    

    public function add_rate($provider, $package, $condition, $zone, $value){
        global $wpdb;

        $wpdb->insert( $wpdb->prefix .'expandore_shipping', array(
            'name' =>  $condition,
            'type' => 'rate',
            'provider' => sanitize_title( esc_attr( $provider ) ),
            'package' => sanitize_title( esc_attr($package) ),
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

        return $wpdb->query("DELETE FROM ". $wpdb->prefix ."expandore_shipping WHERE provider='". sanitize_title( esc_attr( $provider )) ."' AND package='". sanitize_title( esc_attr( $package )) ."' AND type='rate'");
    }

    /**
     * Add new package
     * 
     * @since 1.0
     * @param string $provider
     * @param string $package
     * @return void;
     */
    public function add_package($provider, $package){
        global $wpdb;

        $wpdb->delete( $wpdb->prefix .'expandore_shipping', array('type' => 'package', 'name' => esc_attr( $provider ) .' '. esc_attr( $package )));

        $wpdb->insert( $wpdb->prefix. 'expandore_shipping', array(
            'name' => esc_attr( $provider ) .' '. esc_attr( $package ),
            'type' => 'package',
            'provider' => esc_attr( $provider ),
            'package' => esc_attr( $package ),
            'condition_value' => true,
            'value' => sanitize_title( esc_attr( $provider )).'_'. sanitize_title( esc_attr( $package ) )
        ));
    }

    /**
     * Disable all package
     * 
     * @return void
     */
    public function disable_package(){
        global $wpdb;

        $wpdb->update($wpdb->prefix .'expandore_shipping', array('condition_value' => false), array('type' => 'package'));
    }

    /**
     * Enable specific package
     * 
     * @param string $package
     * @return void
     */
    public function enable_package($package){
        global $wpdb;

        $wpdb->update( $wpdb->prefix .'expandore_shipping', array('condition_value' => true), array('type' => 'package', 'value' => $package));
    }

    /**
     * Delete Package
     * 
     * @param string $package
     * @return void
     */
    public function delete_package($package){
        global $wpdb;

        $wpdb->delete( $wpdb->prefix .'expandore_shipping', array('value' => $package));
    }

    /**
     * Get all package
     * 
     * @since 1.0
     * @param bolean $enableOnly
     * @return array
     */
    public function get_packages($enableOnly=false){
        global $wpdb;
        $sql = '';        
        if($enableOnly){
            $sql = " AND condition_value='enable'";
        }
        return $wpdb->get_results("SELECT name, condition_value, value FROM ". $wpdb->prefix ."expandore_shipping WHERE type='package'". $sql);
    }

    /**
     * Get calculated shipping cost
     * 
     * @since 1.0
     * @param int $weight
     * @param string $country
     * @param string $city
     * @param string $postcode
     * @return array
     */
    public function get_shipping($weight, $country, $city, $postcode){
        global $wpdb;        

        $zones = $wpdb->get_results("SELECT name, provider, value FROM ". $wpdb->prefix ."expandore_shipping WHERE type='zone' AND name like '". $country ."%'");

        $_shipping_zone = array();
        foreach($zones as $zone){
            $_shipping_zone[] = array(
                'provider' => $zone->provider,
                'zone' => $zone->value
            );
        }

        $_shipping_cost = array();
        $weight = $this->round($weight);

        $shipping = array();
        foreach($_shipping_zone as $sz){
            $cost = $wpdb->get_results("SELECT provider,package, value from ". $wpdb->prefix ."expandore_shipping WHERE type='rate' AND provider='". $sz['provider'] ."' AND condition_value='". $sz['zone'] ."' AND name='". $weight ."'");
            
            if( $cost[0]->value > 0){
                $shipping[] = array(
                    'id' => $cost[0]->provider.'_'. $cost[0]->package,
                    'cost' => $cost[0]->value
                );
            }            
        }
        
        usort($shipping, array('Consap_Shipping_Class', 'usort'));
        return $shipping;
    }

    /**
     * Round UP and set 2 digit
     * 
     * @since 1.0
     * @param int $num
     * @return int
     */
    private function round($num){
        $round = round($num * 2) / 2;
        if( $num > $round){
            return sprintf('%0.2f', round($num) );
        }
        else {
            return sprintf('%0.2f', $round );
        }
    }

    public function usort($a, $b){
        if ( $a['cost'] < $b['cost'] ) 
            return -1;
        else 
            return 1;

        if ( $b['cost'] < $a['cost'] ) 
            return -1;        
        else 
            return 1;
    }
    
}