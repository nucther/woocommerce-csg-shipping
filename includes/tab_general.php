<?php 
/**
 * General Tab
 */
 $calc = new Consap_Shipping_class();

 if( isset($_POST['save_general']) ){
     $calc->update_option('fuel_subcharge', esc_attr( $_POST['expandore_shipping_fuel_subcharge'] ));
     $calc->update_option('safety_factor', esc_attr( $_POST['expandore_shipping_safety_factor'] ));
     $calc->disable_package();
     if( isset($_POST['packages'])){
        foreach($_POST['packages'] as $package){            
            $calc->enable_package($package);
        }
     }
 }

?>
<div class="table-box" style="border: 1px solid #ccc; border-top: none;padding: 20px;">    
        <input type="hidden" name="action" value="general">
        <table class="form-table wc-shipping-zone-settings">
            <tbody>
                <tr>
                    <th class="titledesc">
                        Fuel Subcharge
                        <span class="woocommerce-help-tip" data-tip="Fuel subcharge in percentage (%)"></span>
                    </th>
                    <td class="forminp">
                        <input type="text" name="expandore_shipping_fuel_subcharge" value="<?php echo $calc->get_option('fuel_subcharge'); ?>">
                        <p class="description">left empty / blank, to disable this option.</p>
                    </td>
                </tr>
                
                <tr>
                    <th class="titledesc">Safety Factor</th>
                    <td class="forminp">
                        <input type="text" name="expandore_shipping_safety_factor" value="<?php echo $calc->get_option('safety_factor'); ?>">
                        <p class="description">left empty / blank, to disable this option.</p>
                    </td>
                </tr>

                <tr>
                    <th class="titledesc">Shipping Packages</th>
                    <td class="">
                        <?php 
                            $packages = $calc->get_packages();

                            foreach($packages as $package){
                                ?>
                                    <input type="checkbox" name="packages[]" value="<?php echo $package->value; ?>" id="<?php echo $package->value; ?>" <?php if( $package->condition_value ){ echo 'checked="checked"'; } ?>>
                                    <label for="<?php echo $package->value; ?>"><?php echo $package->name; ?></label><br>
                                <?php
                            }
                        ?>
                    </td>
                </tr>

                <tr>
                    <th></th>
                    <td>
                        <button class="button button-primary" type="submit" name="save_general" value="save">Save settings</button>
                    </td>
                </tr>
            </tbody>
        </table>
</div>