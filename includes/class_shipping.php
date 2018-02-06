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

        $this->init();
        $this->enable               = true;
    }

    function init(){
        $this->init_form_fields();
        $this->init_settings();

        add_action( 'woocommerce_update_options_shipping_'. $this->id , array($this, 'process_admin_options'));
    }

    function init_form_fields(){
        $this->form_fields = array(
                'etabBox' => array(
                    'type' => 'expandore_tab_box'
                )
            );
    }

    public function generate_expandore_tab_box_html(){
        $subsection = isset($_GET['subsection'])? esc_attr( $_GET['subsection'] ) : 'general';

        echo '<div class="wrap">
            <style>
                .woocommerce-save-button{ display:none !important; }
            </style>
        ';
        $this->menu($subsection);

        switch($subsection){
            case 'import': 
                require_once EXPANDORE_PATH .'/includes/tab_import.php';
                break;
            default: 
                require_once EXPANDORE_PATH .'/includes/tab_general.php';
                break;
        }
        echo '</div>';
    }

    private function menu($active = 'general'){
        $tabs = array(
            'general' => __('General', 'consap'),
            'import' => __('Import CSV', 'consap'),
        );

        $html = '<h2 class="nav-tab-wrapper">';
        foreach($tabs as $tab => $name){
            $class = ($tab === $active)? 'nav-tab-active' : '';

            $html .='<a class="nav-tab '. $class .'" href="?page=wc-settings&tab=shipping&section=expandore&subsection='. $tab .'">'. $name .'</a>';
        }
        $html .='</h2>';

        echo $html;
    }

    public function calculate_shipping($package) {
        
    }
}