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
    $xcrud->table('boats_yachts_types');
    
    /*
    
SELECT `boat_yach_types_id`, `boat_yach_types_name`, `boat_yach_types_created_at`, `boat_yach_types_updated_at`, 
`boat_yach_types_deleted_at`, `boat_yach_types_admin_status` FROM `boats_yachts_types` WHERE 1
    */
    
    
    $xcrud->columns('boat_yach_types_id,boat_yach_types_name,boat_yach_types_admin_status');
    $xcrud->fields('boat_yach_types_id,boat_yach_types_name,boat_yach_types_admin_status');
    
    $search = @$_REQUEST['search'];

    $xcrud->where("boat_yach_types_id like '$search%' or boat_yach_types_name like '$search%'");
    
    $xcrud->order_by('boat_yach_types_id','desc');

    $xcrud->label('boat_yach_types_id','Id');
    $xcrud->label('boat_yach_types_name','Types');
    $xcrud->label('boat_yach_types_admin_status','Status');



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