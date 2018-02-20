<?php 
/**
 * Delete Database
 * 
 */

 $calc = new Consap_Shipping_class();

 if( isset($_POST['deleteselected'])){
    foreach(($_POST['delete']) as $db){        
        if(strpos($db,'_') > -1){
            $split = split('_', $db);
            $calc->clean_rate($db[0], $db[1]);
            $calc->delete_package($db);
        }
    }
 }

 ?>

<div class="table-box" style="border: 1px solid #ccc; border-top: none;padding: 20px;">    
    <table class="table">        
        <tbody>
            <?php                

                foreach(($packages = $calc->get_packages()) as $package){
                    ?>
                    <tr>
                        <td><input type="checkbox" name="delete[]" value="<?php echo $package->value; ?>"></td>
                        <td><?php echo $package->name; ?></td>                            
                    </tr>
                    <?php
                }
                ?>
        </tbody>
    </table>
    <br><br>
    <input type="submit" name="deleteselected" value="Delete selected">
</div>