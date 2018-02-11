<?php 
/**
 * Import CSV Tab
 * 
 * @since 1.0
 */

$calc = new Consap_Shipping_class();

$csv = null;

if( isset($_POST['upload_csv'])){
    $file = $_FILES['csv'];

    $movefile = wp_handle_upload( $file, array('test_form' => false));

    if( $movefile && ! isset($movefile['error'])){         
        $csv = $movefile['file'];
    } else{
        print_r($movefile);
    }

    $type = $_POST['data_type'];
    switch($type){
        case 'cz_fedex':
            $type = 'country zone';
            $provider = 'FedEx';
            $package = 'default';
            break;
        case 'cz_dhl': 
            $type = 'country zone';
            $provider = 'DHL';
            $package = 'default';
            break;
        case 'cz_tnt':
            $type = 'country zone';
            $provider = 'TNT';
            $package = 'default';
            break;
        case 'cz_other': 
            $type = 'country zone';
            break;
        case 'rate_fedex': 
            $type = 'shipping rate';
            $provider = 'FedEx';
            $package = 'default';
            break;
        case 'rate_dhl':
            $type = 'shipping rate'; 
            $provider = 'DHL';
            $package = 'default';
            break;
        case 'rate_tnt':
            $type = 'shipping rate';
            $provider = 'TNT';
            $package = 'default';
            break;
        case 'rate_other': 
            $type = 'shipping rate';
            break;
    }
}

 ?>
 <div class="table-box" style="border: 1px solid #ccc; border-top: none;padding: 20px;">    
 <table class="form-table wc-shipping-zone-settings">
            <tbody>
                <tr>
                    <th class="titledesc">
                        Select database to updated
                    </th>
                    <td class="forminp">
                        <select name="data_type">
                            <option value="cz_fedex">Country Zone FedEx</option>
                            <option value="cz_dhl">Country Zone DHL</option>
                            <option value="cz_tnt">Country Zone TNT</option>
                            <option value="cz_other">Country Zone Other</option>
                            <option value="rate_fedex">Shipping Rate FedEx</option>
                            <option value="rate_dhl">Shipping Rate DHL</option>
                            <option value="rate_tnt">Shipping Rate TNT</option>
                            <option value="rate_other">Shipping Rate Other</option>
                        </select>
                    </td>
                </tr>
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
                        <button class="button button-primary" type="submit" name="upload_csv" value="upload">Upload CSV</button>
                    </td>
                </tr>
                <tr>
                    <td>
                        <?php 
                            if(! is_null($csv)){
                                if( ($handle = fopen($csv,'r')) !== FALSE ){
                                    $exception = array('type','name provider','package','weight','country name');
                                    $num = 0;
                                    $clean = true;

                                    while( ($data = fgetcsv($handle, 1000, ',') ) !== FALSE ){
                                        $head =  strtolower( $data[0] );
                                        switch( $head ){
                                            case 'package':
                                                $package = $data[1];
                                                $calc->add_package($provider, $package);
                                                break;

                                            case 'weight':                                             
                                                $zone = [];
                                                foreach($data as $z){
                                                    $zone[] = preg_replace('/zone\s?/i','', $z);
                                                }                                            
                                                break;
                                        }

                                        if($clean){
                                            if( strtolower($type) == 'country zone' && !empty($provider)){
                                                $calc->clean_country($provider);
                                                $clean = false;
                                            }
                                            
                                            if( strtolower($type)=='shipping rate' && !empty($provider) && !empty($package) ){
                                                $calc->clean_rate($provider, $package);
                                                $clean = false;
                                            }
                                        }

                                        if( strtolower($type) == 'country zone'){
                                            $cv=''; $ct='';
                                            if(isset($data[2]) && !empty($data[2])){
                                                $ct = 'postcode';
                                                $cv = $data[2];
                                            }
                                            
                                            if( isset($data[3]) && !empty($data[3])){
                                                $ct = 'city';
                                                $cv = $data[3];
                                            }
                                            
                                            if( !empty($data[0]) && !in_array(strtolower($data[0]), $exception ) ){
                                                $calc->add_country($provider,$data[1], $data[0],$ct, $cv, $data[4] );
                                                $num++;$ct='';
                                            }
                                        }

                                        if( strtolower($type) == 'shipping rate'){
                                            $condition = '';
                                            foreach($data as $n => $value){
                                                if(strtolower($zone[$n]) == 'weight'){
                                                    $condition = $data[0];
                                                }else{
                                                    if( !empty($data[0]) && !in_array(strtolower($data[0]), $exception ) ){
                                                        $calc->add_rate($provider, $package, $condition, $zone[$n], $value);
                                                    }
                                                }
                                            }
                                        }

                                    }
                                }
                                fclose($handle);                            
                                unlink($csv);
                            }
                        ?>
                    </td>
                </tr>
            </tbody>
        </table>
 </div>