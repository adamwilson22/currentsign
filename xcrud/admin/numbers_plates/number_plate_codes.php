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
    $xcrud->table('numbers_plates_codes');
    
    /*
SELECT `number_plate_code_id`, `number_plate_code_car_source_id`, `number_plate_code_name`, 
`number_plate_code_created_at`, `number_plate_code_updated_at`, `number_plate_code_deleted_at`,
`number_plate_code_admin_code` FROM `numbers_plates_codes` WHERE 1
    */
    
    $xcrud->columns('number_plate_code_id,number_plate_code_car_source_id,number_plate_code_name,number_plate_code_admin_code');
    $xcrud->fields('number_plate_code_id,number_plate_code_car_source_id,number_plate_code_name,number_plate_code_admin_code');
    $search = @$_REQUEST['search'];
    
    $xcrud->where("number_plate_code_id like '$search%' or number_plate_code_name like '$search%'");
    
    $xcrud->order_by('number_plate_code_id','desc');
    
    $xcrud->label('number_plate_code_id',' Id');
    $xcrud->label('number_plate_code_name',' Name');
    $xcrud->label('number_plate_code_car_source_id',' SourceId');


// SELECT `number_plate_source_id`, `number_plate_source_name`, `number_plate_source_created_at`, `number_plate_source_updated_at`,
// `number_plate_source_deleted_at`, `number_plate_source_admin_status` FROM `numbers_plates_sources` WHERE 1

 $xcrud->relation('number_plate_code_car_source_id','numbers_plates_sources','number_plate_source_id','number_plate_source_name' );

    
    $xcrud->label('number_plate_code_admin_code','Admin Status');

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