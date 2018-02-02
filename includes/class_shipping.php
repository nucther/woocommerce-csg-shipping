<?php 
/**
 * 
 * 
 */

class WC_Consap_Expandore_Shipping_method extends WC_Shipping_Method{

    /**
     * Construct shipping method
     * 
     * @since 1.0
     * @access public
     * @return void
     */
    public function __construct(){
        $this->id                   = 'expandore';
        $this->method_title         = __('Expandore Shipping', 'consap');
        $this->method_description   = __('Custom Shipping Method for Expandore by Consap', 'consap');

        $this->init();
        $this->enable               = ( isset($this->settings['enable'] ) )? $this->settings['enable'] : 'no';
    }

    function init(){
        $this->init_form_fields();
        $this->init_settings();

        add_action( 'woocommerce_update_options_shipping_'. $this->id , array($this, 'process_admin_options'));
    }

    function init_form_fields(){
        $this->form_fields = array(
            'enable' => array(
                'title' => __('Enable','consap'),
                'type' => 'checkbox',
                'description' => __('Enable this shipping.', 'consap'),
                'default' => 'yes'
            ),

            'fuel-subcharge' => array(
                'title' => __('Fuel Subcharge', 'consap'),
                'type' => 'text',
                'description' => __('How much percentage (%) fuel subcharge', 'consap')                            
            ),

            'safety-factor' => array(
                'title' => __('Safety Factor', 'consap'),
                'type' => 'text',
                'description' => __('Shipping safety factor', 'consap')
            )
            
        );
    }

    public function calculate_shipping($package) {
        
    }
}