<?php 
/**
 * Import CSV Tab
 * 
 * @since 1.0
 */

if( isset($_POST['upload_csv'])){
    $file = $_FILES['csv'];

    $movefile = wp_handle_upload( $file, array('test_form' => false));

    if( $movefile && ! isset($movefile['error'])){         
        $csv = $movefile['file'];
    } else{
        print_r($movefile);
    }
}

 ?>
 <div class="table-box" style="border: 1px solid #ccc; border-top: none;padding: 20px;">    
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
                        <button class="button button-primary" type="submit" name="upload_csv" value="upload">Upload CSV</button>
                    </td>
                </tr>
                <tr>
                    <td>
                    <?php 
                        if(! is_null($csv)){
                            if( ($handle = fopen($csv,'r') ) !== FALSE){
                                $type = '';
                                $provider = '';
                                $package = '';                                
                                $clean = false;
                                $exception = array('type','name provider','package','weight','country name');
                                $num = 0;
                                while( ($data = fgetcsv($handle, 1000, ',')) !== FALSE){                                    
                                    switch(strtolower($data[0])){
                                        case 'type': 
                                            $type = $data[1];
                                            echo 'Detected : '. $type .'<br>';
                                            $clean = true;
                                            $num = 0;
                                            break;
                                        case 'name provider':
                                            $provider= $data[1];
                                            echo 'Provider: '. $provider .'<br>';
                                            break;
                                        case 'package': 
                                            $package = $data[1];
                                            $calc->add_package($provider, $package);
                                            echo 'Package: '. $package .'<br>';
                                            break;
                                        case 'weight':                                             
                                            $zone = [];
                                            foreach($data as $z){
                                                $zone[] = preg_replace('/zone\s?/i','', $z);
                                            }                                            
                                            break;
                                    }

                                    if($clean){
                                        if( strtolower($type) == 'country zone'){
                                            $calc->clean_country($provider);
                                        }
                                        
                                        if( strtolower($type)=='shipping rate'){
                                            $calc->clean_rate($provider, $package);
                                        }

                                        $clean = false;
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