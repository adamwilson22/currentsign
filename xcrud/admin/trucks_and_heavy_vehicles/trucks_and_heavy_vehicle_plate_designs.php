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
    $xcrud->table('trucks_and_heavy_vehicles');
    
    /*
    
        SELECT `trucks_and_heavy_vehicle_id`, `trucks_and_heavy_vehicle_name`, `trucks_and_heavy_vehicle_class_id`, `trucks_and_heavy_vehicle_user_id`, `trucks_and_heavy_vehicle_admin_id`, `trucks_and_heavy_vehicle_review_count`, `trucks_and_heavy_vehicle_price`, `trucks_and_heavy_vehicle_maker_id`, `trucks_and_heavy_vehicle_model_id`, 
        `trucks_and_heavy_vehicle_model_year_id`, `trucks_and_heavy_vehicle_reginal_specification`, `trucks_and_heavy_vehicle_kilometers`, `trucks_and_heavy_vehicle_engine_capacity_id`, `trucks_and_heavy_vehicle_cylinder_id`, `trucks_and_heavy_vehicle_transmission_id`, 
        `trucks_and_heavy_vehicle_body_color_id`, `trucks_and_heavy_vehicle_fuel_type_id`, `trucks_and_heavy_vehicle_door_id`, `trucks_and_heavy_vehicle_city_id`, `trucks_and_heavy_vehicle_created_at`, `trucks_and_heavy_vehicle_updated_at`, `trucks_and_heavy_vehicle_deleted_at`, `trucks_and_heavy_vehicle_admin_status`,
        `trucks_and_heavy_vehicle_plates_codes_id`, `trucks_and_heavy_vehicle_plate_design_id`, `trucks_and_heavy_vehicle_plate_source_id`, `trucks_and_heavy_vehicle_rim_sizes_id`, `trucks_and_heavy_vehicle_type_id`, `trucks_and_heavy_vehicle_condition_id`, `trucks_and_heavy_vehicle_under_warranty_id` 
        FROM `trucks_and_heavy_vehicles` WHERE 1
    
    */
    
    
    $xcrud->columns('trucks_and_heavy_vehicle_name,trucks_and_heavy_vehicle_class_id');
    $xcrud->fields('trucks_and_heavy_vehicle_name,trucks_and_heavy_vehicle_class_id');
    
    $search = @$_REQUEST['search'];

    $xcrud->where("trucks_and_heavy_vehicle_id like '$search%' or trucks_and_heavy_vehicle_name like '$search%'");
    
    $xcrud->order_by('trucks_and_heavy_vehicle_id','desc');

    $xcrud->label('trucks_and_heavy_vehicle_id','CarId');
    

   /*******************************************/
   /*******************************************/
   
         $db_country = Xcrud_db::get_instance();
         $query_country = "SELECT * FROM `trucks_and_heavy_vehicles_classes` WHERE 1";  
         $db_country->query($query_country);
         $result_country = $db_country->result();
         $select = array();

         foreach ($result_country as $key => $item)
         {
                  $select[$item['trucks_and_heavy_vehicle_classes_id']] = $item['trucks_and_heavy_vehicle_classes_name'];
         }
        $xcrud->change_type('trucks_and_heavy_vehicle_class_id','select','',$select);

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