<?php 
/**
 * Instalation and upgrade plugin table
 */
$current_version = get_option( 'consapES');

function consap_expandore_shipping_register_db(){
    global $wpdb;
    $table_name = $wpdb->prefix .'expandore_shipping';
    $charset = $wpdb->get_charset_collate();
    $sql_table = "CREATE TABLE ". $table_name ." (
        ID mediumint(12) NOT NULL AUTO_INCREMENT,
        name varchar(250) NOT NULL,
        type varchar(100) NOT NULL,
        provider varchar(100) NOT NULL,
        package varchar(100) NOT NULL,        
        condition_type varchar(100) NOT NULL,
        condition_value varchar(300) NOT NULL,
        value text NOT NULL,
        PRIMARY KEY (ID)
    ) ". $charset .";";

    require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
    dbDelta( $sql_table );
    add_option( 'consapES_version', $version_db );    
}

function consap_expandore_shipping_update_db(){    
    if( $current_version != EXPANDORE_VERSION ){
        consap_expandore_shipping_register_db();
        update_option( 'consapES_version', $version_db );
    }
}
add_action( 'plugin_loaded', 'consap_expandore_shipping_update_db');