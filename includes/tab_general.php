<?php 
/**
 * General Tab
 */
 $calc = new Consap_Shipping_class();

 if( isset($_POST['save_general']) ){
     $calc->update_option('fuel_subcharge', esc_attr( $_POST['expandore_shipping_fuel_subcharge'] ));
     $calc->update_option('safety_factor', esc_attr( $_POST['expandore_shipping_safety_factor'] ));
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
                    </td>
                </tr>
                
                <tr>
                    <th class="titledesc">Safety Factor</th>
                    <td class="forminp">
                        <input type="text" name="expandore_shipping_safety_factor" value="<?php echo $calc->get_option('safety_factor'); ?>">
                    </td>
                </tr>

                <tr>
                    <th></th>
                    <td>
                        <button class="button button-primary" type="submit" name="save_general">Save settings</button>
                    </td>
                </tr>
            </tbody>
        </table>       
    
    <hr class="wp-header-end">

    
        <input type="hidden" name="ex-action" value="csv-upload">

        <table class="form-table wc-shipping-zone-settings" style="border-top:1px solid #ccc;margin-top:20px;">
            <tbody>
                <tr>
                    <th class="titledesc">
                        Update Database
                        <span class="woocommerce-help-tip" data-tip="Upload csv template with updated data"></span>
                    </th>
                    <td class="forminp">
                        <input type="file" name="csv">
                    </td>
                </tr>
                <tr>
                    <th></th>
                    <td>
                        <button class="button button-primary" type="submit">Upload CSV</button>
                    </td>
                </tr>
                <tr>
                    <td></td>
                </tr>
            </tbody>
        </table>        
    

</div>