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
    
    
    $xcrud->columns('trucks_and_heavy_vehicle_id,trucks_and_heavy_vehicle_name,trucks_and_heavy_vehicle_class_id,trucks_and_heavy_vehicle_admin_id,trucks_and_heavy_vehicle_review_count,trucks_and_heavy_vehicle_price,trucks_and_heavy_vehicle_maker_id,trucks_and_heavy_vehicle_model_id,trucks_and_heavy_vehicle_model_year_id,trucks_and_heavy_vehicle_reginal_specification_id,trucks_and_heavy_vehicle_kilometers,trucks_and_heavy_vehicle_engine_capacity_id,trucks_and_heavy_vehicle_cylinder_id,trucks_and_heavy_vehicle_transmission_id,trucks_and_heavy_vehicle_body_color_id,trucks_and_heavy_vehicle_fuel_type_id,trucks_and_heavy_vehicle_door_id,trucks_and_heavy_vehicle_city_id,trucks_and_heavy_vehicle_created_at,trucks_and_heavy_vehicle_updated_at,trucks_and_heavy_vehicle_deleted_at,trucks_and_heavy_vehicle_admin_status,trucks_and_heavy_vehicle_rim_sizes_id,trucks_and_heavy_vehicle_type_id,trucks_and_heavy_vehicle_condition_id,trucks_and_heavy_vehicle_under_warranty_id,trucks_and_heavy_vehicle_extra_features,trucks_and_heavy_vehicle_rent_price,trucks_and_heavy_vehicle_advertisement_id');
    $xcrud->fields('trucks_and_heavy_vehicle_id,trucks_and_heavy_vehicle_name,trucks_and_heavy_vehicle_class_id,trucks_and_heavy_vehicle_admin_id,trucks_and_heavy_vehicle_review_count,trucks_and_heavy_vehicle_price,trucks_and_heavy_vehicle_maker_id,trucks_and_heavy_vehicle_model_id,trucks_and_heavy_vehicle_model_year_id,trucks_and_heavy_vehicle_reginal_specification_id,trucks_and_heavy_vehicle_kilometers,trucks_and_heavy_vehicle_engine_capacity_id,trucks_and_heavy_vehicle_cylinder_id,trucks_and_heavy_vehicle_transmission_id,trucks_and_heavy_vehicle_body_color_id,trucks_and_heavy_vehicle_fuel_type_id,trucks_and_heavy_vehicle_door_id,trucks_and_heavy_vehicle_city_id,trucks_and_heavy_vehicle_created_at,trucks_and_heavy_vehicle_updated_at,trucks_and_heavy_vehicle_deleted_at,trucks_and_heavy_vehicle_admin_status,trucks_and_heavy_vehicle_rim_sizes_id,trucks_and_heavy_vehicle_type_id,trucks_and_heavy_vehicle_condition_id,trucks_and_heavy_vehicle_under_warranty_id,trucks_and_heavy_vehicle_extra_features,trucks_and_heavy_vehicle_rent_price,trucks_and_heavy_vehicle_advertisement_id');
    
    $search = @$_REQUEST['search'];

    $xcrud->where("trucks_and_heavy_vehicle_id like '$search%' or trucks_and_heavy_vehicle_name like '$search%'");
    
    $xcrud->order_by('trucks_and_heavy_vehicle_id','desc');


    $xcrud->label('trucks_and_heavy_vehicle_id','trucks_and_heavy_vehicleId');
    $xcrud->label('trucks_and_heavy_vehicle_class_id','ClassName');
    $xcrud->label('trucks_and_heavy_vehicle_user_id','User');
    $xcrud->label('trucks_and_heavy_vehicle_admin_id','Admin');
    $xcrud->label('trucks_and_heavy_vehicle_maker_id','MakerName');
    $xcrud->label('trucks_and_heavy_vehicle_model_id','ModelName');
    $xcrud->label('trucks_and_heavy_vehicle_model_year_id','ModelYear');
    $xcrud->label('trucks_and_heavy_vehicle_reginal_specification_id','ModelSpecification');
    $xcrud->label('trucks_and_heavy_vehicle_engine_capacity_id','Capacity');
    $xcrud->label('trucks_and_heavy_vehicle_cylinder_id','Cylinder');
    $xcrud->label('trucks_and_heavy_vehicle_transmission_id','Transmission');
    $xcrud->label('trucks_and_heavy_vehicle_body_color_id','BodyColor');
    $xcrud->label('trucks_and_heavy_vehicle_fuel_type_id','FuelType');
    $xcrud->label('trucks_and_heavy_vehicle_door_id','Doors');
    $xcrud->label('trucks_and_heavy_vehicle_city_id','City');
    // $xcrud->label('trucks_and_heavy_vehicle_plates_codes_id','trucks_and_heavy_vehicle_id');
    // $xcrud->label('trucks_and_heavy_vehicle_plate_design_id','trucks_and_heavy_vehicle_id');
    // $xcrud->label('trucks_and_heavy_vehicle_plate_source_id','trucks_and_heavy_vehicle_id');
    $xcrud->label('trucks_and_heavy_vehicle_rim_sizes_id','RimSize');
    $xcrud->label('trucks_and_heavy_vehicle_type_id','Type');
    $xcrud->label('trucks_and_heavy_vehicle_condition_id','Condition');
    $xcrud->label('trucks_and_heavy_vehicle_under_warranty_id','Warranty');
    $xcrud->label('trucks_and_heavy_vehicle_advertisement_id','AdsId');


   /*******************************************/
   /*******************************************/
   
         $db_country = Xcrud_db::get_instance();
         $query_country = "SELECT * FROM `trucks_and_heavy_vehicles_classes`";  
         $db_country->query($query_country);
         $result_country = $db_country->result();
         $select = array();

         foreach ($result_country as $key => $item)
         {
                  $select[$item['trucks_and_heavy_vehicle_class_id']] = $item['trucks_and_heavy_vehicle_class_name'];
         }
         
        $xcrud->change_type('trucks_and_heavy_vehicle_class_id','select','',$select);

   /*******************************************/
   /*******************************************/

   /*******************************************/
   /*******************************************/
   
         $db_country = Xcrud_db::get_instance();
         $query_country = "SELECT * FROM `admins`";  
         $db_country->query($query_country);
         $result_country = $db_country->result();
         $select = array();

         foreach ($result_country as $key => $item)
         {
                  $select[$item['id']] = $item['name'];
         }
         
        $xcrud->change_type('trucks_and_heavy_vehicle_admin_id','select','',$select);

   /*******************************************/
   /*******************************************/



   /*******************************************/
   /*******************************************/
   
        //  $db_country = Xcrud_db::get_instance();
        //  $query_country = "SELECT * FROM `trucks_and_heavy_vehicles_makers` where `trucks_and_heavy_vehicle_maker_sub_category_id` = '52'";  
        //  $db_country->query($query_country);
        //  $result_country = $db_country->result();
        //  $select = array();

        //  foreach ($result_country as $key => $item)
        //  {
        //           $select[$item['trucks_and_heavy_vehicle_maker_id']] = $item['trucks_and_heavy_vehicle_maker_name'];
        //  }
         
        // $xcrud->change_type('trucks_and_heavy_vehicle_maker_id','select','',$select);

   /*******************************************/
   /*******************************************/
   



   /*******************************************/
   /*******************************************/
   
   
 $xcrud->relation('trucks_and_heavy_vehicle_maker_id','trucks_and_heavy_vehicles_makers','trucks_and_heavy_vehicle_maker_id','trucks_and_heavy_vehicle_maker_name' , "`trucks_and_heavy_vehicle_maker_sub_category_id` = '52'");
 
 $xcrud->relation('trucks_and_heavy_vehicle_model_id','trucks_and_heavy_vehicles_models','trucks_and_heavy_vehicle_model_id', 'trucks_and_heavy_vehicle_model_name' ,'','','','','','trucks_and_heavy_vehicle_maker_id','trucks_and_heavy_vehicle_maker_id');
     
 $xcrud->relation('trucks_and_heavy_vehicle_model_year_id','trucks_and_heavy_vehicles_years','trucks_and_heavy_vehicle_year_id','trucks_and_heavy_vehicle_year_name' );

 $xcrud->relation('trucks_and_heavy_vehicle_reginal_specification_id','trucks_and_heavy_vehicles_regional_specs','trucks_and_heavy_vehicle_regional_specs_id','trucks_and_heavy_vehicle_regional_specs_name' );

//trucks_and_heavy_vehicle_engine_capacity_id

//SELECT `trucks_and_heavy_vehicle_engine_capacity_id`, `trucks_and_heavy_vehicle_engine_capacity_value`, `trucks_and_heavy_vehicle_engine_capacity_created_at`, `trucks_and_heavy_vehicle_engine_capacity_updated_at`, `trucks_and_heavy_vehicle_engine_capacity_deleted_at`, `trucks_and_heavy_vehicle_engine_capacity_admin_status` FROM `trucks_and_heavy_vehicles_engine_capacities` WHERE 1
   
 $xcrud->relation('trucks_and_heavy_vehicle_engine_capacity_id','trucks_and_heavy_vehicles_engine_capacities','trucks_and_heavy_vehicle_engine_capacity_id','trucks_and_heavy_vehicle_engine_capacity_value' );
   
//trucks_and_heavy_vehicle_cylinder_id

//SELECT `trucks_and_heavy_vehicle_cylinder_id`, `trucks_and_heavy_vehicle_cylinder_count`, `trucks_and_heavy_vehicle_cylinder_created_at`, `trucks_and_heavy_vehicle_cylinder_updated_at`, `trucks_and_heavy_vehicle_cylinder_deleted_at`, `trucks_and_heavy_vehicle_cylinder_admin_status` FROM `trucks_and_heavy_vehicles_cylinders` WHERE 1   
   
 $xcrud->relation('trucks_and_heavy_vehicle_cylinder_id','trucks_and_heavy_vehicles_cylinders','trucks_and_heavy_vehicle_cylinder_id','trucks_and_heavy_vehicle_cylinder_count' );

//trucks_and_heavy_vehicle_transmission_id
      
//SELECT `trucks_and_heavy_vehicle_transmission_id`, `trucks_and_heavy_vehicle_transmission_name`, `trucks_and_heavy_vehicle_transmission_created_at`, `trucks_and_heavy_vehicle_transmission_updated_at`, `trucks_and_heavy_vehicle_transmission_deleted_at`, `trucks_and_heavy_vehicle_transmission_admin_status` FROM `trucks_and_heavy_vehicles_transmissions` WHERE 1

 $xcrud->relation('trucks_and_heavy_vehicle_transmission_id','trucks_and_heavy_vehicles_transmissions','trucks_and_heavy_vehicle_transmission_id','trucks_and_heavy_vehicle_transmission_name' );


//trucks_and_heavy_vehicle_body_color_id

//SELECT `trucks_and_heavy_vehicle_fuel_type_id`, `trucks_and_heavy_vehicle_fuel_type_name`, `trucks_and_heavy_vehicle_fuel_type_created_at`, `trucks_and_heavy_vehicle_fuel_type_updated_at`, `trucks_and_heavy_vehicle_fuel_type_deleted_at`, `trucks_and_heavy_vehicle_fuel_type_admin_status` FROM `trucks_and_heavy_vehicles_fuel_types` WHERE 1

 $xcrud->relation('trucks_and_heavy_vehicle_fuel_type_id','trucks_and_heavy_vehicles_fuel_types','trucks_and_heavy_vehicle_fuel_type_id','trucks_and_heavy_vehicle_fuel_type_name' );

//trucks_and_heavy_vehicle_fuel_type_id
   
//SELECT `trucks_and_heavy_vehicle_interior_color_id`, `trucks_and_heavy_vehicle_interior_color_name`, `trucks_and_heavy_vehicle_interior_color_created_at`, `trucks_and_heavy_vehicle_interior_color_updated_at`, `trucks_and_heavy_vehicle_interior_color_deleted_at`, `trucks_and_heavy_vehicle_interior_color_admin_status` FROM `trucks_and_heavy_vehicles_body_colors` WHERE 1

//trucks_and_heavy_vehicle_door_id

//SELECT `trucks_and_heavy_vehicle_door_id`, `trucks_and_heavy_vehicle_door_count`, `trucks_and_heavy_vehicle_door_created_at`, `trucks_and_heavy_vehicle_door_updated_at`, `trucks_and_heavy_vehicle_door_deleted_at`, `trucks_and_heavy_vehicle_door_admin_status` FROM `trucks_and_heavy_vehicles_doors` WHERE 1


 $xcrud->relation('trucks_and_heavy_vehicle_door_id','trucks_and_heavy_vehicles_doors','trucks_and_heavy_vehicle_door_id','trucks_and_heavy_vehicle_door_count' );


//trucks_and_heavy_vehicle_city_id

//SELECT `trucks_and_heavy_vehicle_city_id`, `trucks_and_heavy_vehicle_city_name`, `trucks_and_heavy_vehicle_city_created_at`, `trucks_and_heavy_vehicle_city_updated_at`, `trucks_and_heavy_vehicle_city_deleted_at`, `trucks_and_heavy_vehicle_city_admin_status` FROM `trucks_and_heavy_vehicles_cities` WHERE 1

//trucks_and_heavy_vehicle_rim_sizes_id

//SELECT `trucks_and_heavy_vehicle_rim_size_id`, `trucks_and_heavy_vehicle_rim_size_name`, `trucks_and_heavy_vehicle_rim_size_created_at`, `trucks_and_heavy_vehicle_rim_size_updated_at`, `trucks_and_heavy_vehicle_rim_size_deleted_at`, `trucks_and_heavy_vehicle_rim_size_admin_status` FROM `trucks_and_heavy_vehicles_rim_sizes` WHERE 1

//trucks_and_heavy_vehicle_type_id

//SELECT `trucks_and_heavy_vehicle_type_id`, `trucks_and_heavy_vehicle_type_name`, `trucks_and_heavy_vehicle_type_created_at`, `trucks_and_heavy_vehicle_type_updated_at`, `trucks_and_heavy_vehicle_type_deleted_at`, `trucks_and_heavy_vehicle_type_admin_status` FROM `trucks_and_heavy_vehicles_types` WHERE 1

//trucks_and_heavy_vehicle_condition_id
   
//SELECT `trucks_and_heavy_vehicle_condition_id`, `trucks_and_heavy_vehicle_condition_name`, `trucks_and_heavy_vehicle_condition_created_at`, `trucks_and_heavy_vehicle_condition_updated_at`, `trucks_and_heavy_vehicle_condition_deleted_at`, `trucks_and_heavy_vehicle_condition_admin_status` FROM `trucks_and_heavy_vehicles_conditions` WHERE 1

//trucks_and_heavy_vehicle_under_warranty_id

//SELECT `trucks_and_heavy_vehicle_under_warranty_id`, `trucks_and_heavy_vehicle_under_warranty_name`, `trucks_and_heavy_vehicle_under_warranty_created_at`, `trucks_and_heavy_vehicle_under_warranty_updated_at`, `trucks_and_heavy_vehicle_under_warranty_deleted_at`, `trucks_and_heavy_vehicle_under_warranty_admin_status` FROM `trucks_and_heavy_vehicles_under_warranties` WHERE 1

//trucks_and_heavy_vehicle_advertisement_id;
   

//SELECT `advertisement_id`, `advertisement_category_id`, `advertisement_sub_category_id`, `advertisement_created_at`, `advertisement_updated_at`, `advertisement_deleted_at`, `advertisement_admin_status`, `advertisement_type` FROM `advertisements` WHERE 1























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