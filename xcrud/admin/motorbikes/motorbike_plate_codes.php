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
    $xcrud->table('motorbikes');
    
    /*
    
        SELECT `motorbike_id`, `motorbike_name`, `motorbike_class_id`, `motorbike_user_id`, `motorbike_admin_id`, `motorbike_review_count`, `motorbike_price`, `motorbike_maker_id`, `motorbike_model_id`, 
        `motorbike_model_year_id`, `motorbike_reginal_specification`, `motorbike_kilometers`, `motorbike_engine_capacity_id`, `motorbike_cylinder_id`, `motorbike_transmission_id`, 
        `motorbike_body_color_id`, `motorbike_fuel_type_id`, `motorbike_door_id`, `motorbike_city_id`, `motorbike_created_at`, `motorbike_updated_at`, `motorbike_deleted_at`, `motorbike_admin_status`,
        `motorbike_plates_codes_id`, `motorbike_plate_design_id`, `motorbike_plate_source_id`, `motorbike_rim_sizes_id`, `motorbike_type_id`, `motorbike_condition_id`, `motorbike_under_warranty_id` 
        FROM `motorbikes` WHERE 1
    
    */
    
    
    $xcrud->columns('motorbike_name,motorbike_class_id');
    $xcrud->fields('motorbike_name,motorbike_class_id');
    
    $search = @$_REQUEST['search'];

    $xcrud->where("motorbike_id like '$search%' or motorbike_name like '$search%'");
    
    $xcrud->order_by('motorbike_id','desc');

    $xcrud->label('motorbike_id','CarId');
    

   /*******************************************/
   /*******************************************/
   
         $db_country = Xcrud_db::get_instance();
         $query_country = "SELECT * FROM `motorbikes_classes` WHERE 1";  
         $db_country->query($query_country);
         $result_country = $db_country->result();
         $select = array();

         foreach ($result_country as $key => $item)
         {
                  $select[$item['motorbike_classes_id']] = $item['motorbike_classes_name'];
         }
        $xcrud->change_type('motorbike_class_id','select','',$select);

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