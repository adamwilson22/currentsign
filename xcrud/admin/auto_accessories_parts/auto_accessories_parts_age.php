<?php
require('../config.php');
?>
<style>
html{
    overflow: hidden;
}

.xcrud_custom_css textarea{
    min-height:300px;
}

.xcrud-upload-container img {
    display: none !important;
}
</style>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/js/bootstrap.min.js"></script>
<?php 
             
    
    $path = dirname(dirname(__DIR__)) . "/" . '/xcrud/xcrud/xcrud.php';
    
    require($path);
    
    $xcrud = Xcrud::get_instance();
    $xcrud->table('auto_accessories_age');
    
    /*
    
SELECT `auto_accessory_age_id`, `auto_accessory_age_name`, `auto_accessory_age_created_at`, 
`auto_accessory_age_updated_at`, `auto_accessory_age_deleted_at`, `auto_accessory_age_admin_status`
FROM `auto_accessories_age` WHERE 1
    
    */
    
    
    $xcrud->columns('auto_accessory_age_id,auto_accessory_age_name,auto_accessory_age_admin_status');
    $xcrud->fields('auto_accessory_age_id,auto_accessory_age_name,auto_accessory_age_admin_status');
    
    $search = @$_REQUEST['search'];

    $xcrud->where("auto_accessory_age_id like '$search%' or auto_accessory_age_name like '$search%'");
    
    $xcrud->order_by('auto_accessory_age_id','desc');

    $xcrud->label('auto_accessory_age_id','Id');
    $xcrud->label('auto_accessory_age_name','Age');
    $xcrud->label('auto_accessory_age_admin_status','Status');



   /*******************************************/
   /*******************************************/
   

    // $xcrud->unset_add(true);
    // $xcrud->unset_edit(true);
    // $xcrud->unset_view(true);
    $xcrud->unset_remove(true);
    $xcrud->unset_csv(true);
    // $xcrud->unset_search(true);
    $xcrud->unset_print(true);
    $xcrud->unset_title(true);
    $xcrud->unset_numbers(true);
    // $xcrud->unset_pagination(true);
    // $xcrud->unset_limitlist(true);
    // $xcrud->unset_sortable(true);
    // $xcrud->unset_list(true);
    //$xcrud->unset_edit(true,'username','=','admin'); // 'admin' row can't be editable
    $xcrud->buttons_position('left'); // can be left, right or none

    
    echo $xcrud->render(); 

?>