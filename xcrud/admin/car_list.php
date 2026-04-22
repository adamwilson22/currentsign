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
    $xcrud->table('cars');
    
    /*
    
        SELECT `car_id`, `car_name`, `car_class_id`, `car_user_id`, `car_admin_id`, `car_review_count`, `car_price`, `car_maker_id`, `car_model_id`, 
        `car_model_year_id`, `car_reginal_specification`, `car_kilometers`, `car_engine_capacity_id`, `car_cylinder_id`, `car_transmission_id`, 
        `car_body_color_id`, `car_fuel_type_id`, `car_door_id`, `car_city_id`, `car_created_at`, `car_updated_at`, `car_deleted_at`, `car_admin_status`,
        `car_plates_codes_id`, `car_plate_design_id`, `car_plate_source_id`, `car_rim_sizes_id`, `car_type_id`, `car_condition_id`, `car_under_warranty_id` 
        FROM `cars` WHERE 1
    
    */
    
    
    $xcrud->columns('car_id,car_name,car_class_id,car_admin_id,car_review_count,car_price,car_maker_id,car_model_id,car_model_year_id,car_reginal_specification_id,car_kilometers,car_engine_capacity_id,car_cylinder_id,car_transmission_id,car_body_color_id,car_fuel_type_id,car_door_id,car_city_id,car_created_at,car_updated_at,car_deleted_at,car_admin_status,car_rim_sizes_id,car_type_id,car_condition_id,car_under_warranty_id,car_extra_features,car_rent_price,car_advertisement_id');
    $xcrud->fields('car_id,car_name,car_class_id,car_admin_id,car_review_count,car_price,car_maker_id,car_model_id,car_model_year_id,car_reginal_specification_id,car_kilometers,car_engine_capacity_id,car_cylinder_id,car_transmission_id,car_body_color_id,car_fuel_type_id,car_door_id,car_city_id,car_created_at,car_updated_at,car_deleted_at,car_admin_status,car_rim_sizes_id,car_type_id,car_condition_id,car_under_warranty_id,car_extra_features,car_rent_price,car_advertisement_id');
    
    $search = @$_REQUEST['search'];

    $xcrud->where("car_id like '$search%' or car_name like '$search%'");
    
    $xcrud->order_by('car_id','desc');


    $xcrud->label('car_id','CarId');
    $xcrud->label('car_class_id','ClassName');
    $xcrud->label('car_user_id','User');
    $xcrud->label('car_admin_id','Admin');
    $xcrud->label('car_maker_id','MakerName');
    $xcrud->label('car_model_id','ModelName');
    $xcrud->label('car_model_year_id','ModelYear');
    $xcrud->label('car_reginal_specification_id','ModelSpecification');
    $xcrud->label('car_engine_capacity_id','Capacity');
    $xcrud->label('car_cylinder_id','Cylinder');
    $xcrud->label('car_transmission_id','Transmission');
    $xcrud->label('car_body_color_id','BodyColor');
    $xcrud->label('car_fuel_type_id','FuelType');
    $xcrud->label('car_door_id','Doors');
    $xcrud->label('car_city_id','City');
    // $xcrud->label('car_plates_codes_id','car_id');
    // $xcrud->label('car_plate_design_id','car_id');
    // $xcrud->label('car_plate_source_id','car_id');
    $xcrud->label('car_rim_sizes_id','RimSize');
    $xcrud->label('car_type_id','Type');
    $xcrud->label('car_condition_id','Condition');
    $xcrud->label('car_under_warranty_id','Warranty');
    $xcrud->label('car_advertisement_id','AdsId');


   /*******************************************/
   /*******************************************/
   
         $db_country = Xcrud_db::get_instance();
         $query_country = "SELECT * FROM `cars_classes`";  
         $db_country->query($query_country);
         $result_country = $db_country->result();
         $select = array();

         foreach ($result_country as $key => $item)
         {
                  $select[$item['car_class_id']] = $item['car_class_name'];
         }
         
        $xcrud->change_type('car_class_id','select','',$select);

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
         
        $xcrud->change_type('car_admin_id','select','',$select);

   /*******************************************/
   /*******************************************/



   /*******************************************/
   /*******************************************/
   
        //  $db_country = Xcrud_db::get_instance();
        //  $query_country = "SELECT * FROM `cars_makers` where `car_maker_sub_category_id` = '52'";  
        //  $db_country->query($query_country);
        //  $result_country = $db_country->result();
        //  $select = array();

        //  foreach ($result_country as $key => $item)
        //  {
        //           $select[$item['car_maker_id']] = $item['car_maker_name'];
        //  }
         
        // $xcrud->change_type('car_maker_id','select','',$select);

   /*******************************************/
   /*******************************************/
   



   /*******************************************/
   /*******************************************/
   
   
 $xcrud->relation('car_maker_id','cars_makers','car_maker_id','car_maker_name' , "`car_maker_sub_category_id` = '52'");
 
 $xcrud->relation('car_model_id','cars_models','car_model_id', 'car_model_name' ,'','','','','','car_maker_id','car_maker_id');
     
 $xcrud->relation('car_model_year_id','cars_years','car_year_id','car_year_name' );

 $xcrud->relation('car_reginal_specification_id','cars_regional_specs','car_regional_specs_id','car_regional_specs_name' );

//car_engine_capacity_id

//SELECT `car_engine_capacity_id`, `car_engine_capacity_value`, `car_engine_capacity_created_at`, `car_engine_capacity_updated_at`, `car_engine_capacity_deleted_at`, `car_engine_capacity_admin_status` FROM `cars_engine_capacities` WHERE 1
   
 $xcrud->relation('car_engine_capacity_id','cars_engine_capacities','car_engine_capacity_id','car_engine_capacity_value' );
   
//car_cylinder_id

//SELECT `car_cylinder_id`, `car_cylinder_count`, `car_cylinder_created_at`, `car_cylinder_updated_at`, `car_cylinder_deleted_at`, `car_cylinder_admin_status` FROM `cars_cylinders` WHERE 1   
   
 $xcrud->relation('car_cylinder_id','cars_cylinders','car_cylinder_id','car_cylinder_count' );

//car_transmission_id
      
//SELECT `car_transmission_id`, `car_transmission_name`, `car_transmission_created_at`, `car_transmission_updated_at`, `car_transmission_deleted_at`, `car_transmission_admin_status` FROM `cars_transmissions` WHERE 1

 $xcrud->relation('car_transmission_id','cars_transmissions','car_transmission_id','car_transmission_name' );


//car_body_color_id

//SELECT `car_fuel_type_id`, `car_fuel_type_name`, `car_fuel_type_created_at`, `car_fuel_type_updated_at`, `car_fuel_type_deleted_at`, `car_fuel_type_admin_status` FROM `cars_fuel_types` WHERE 1

 $xcrud->relation('car_fuel_type_id','cars_fuel_types','car_fuel_type_id','car_fuel_type_name' );

//car_fuel_type_id
   
//SELECT `car_interior_color_id`, `car_interior_color_name`, `car_interior_color_created_at`, `car_interior_color_updated_at`, `car_interior_color_deleted_at`, `car_interior_color_admin_status` FROM `cars_body_colors` WHERE 1

//car_door_id

//SELECT `car_door_id`, `car_door_count`, `car_door_created_at`, `car_door_updated_at`, `car_door_deleted_at`, `car_door_admin_status` FROM `cars_doors` WHERE 1


 $xcrud->relation('car_door_id','cars_doors','car_door_id','car_door_count' );


//car_city_id

//SELECT `car_city_id`, `car_city_name`, `car_city_created_at`, `car_city_updated_at`, `car_city_deleted_at`, `car_city_admin_status` FROM `cars_cities` WHERE 1

//car_rim_sizes_id

//SELECT `car_rim_size_id`, `car_rim_size_name`, `car_rim_size_created_at`, `car_rim_size_updated_at`, `car_rim_size_deleted_at`, `car_rim_size_admin_status` FROM `cars_rim_sizes` WHERE 1

//car_type_id

//SELECT `car_type_id`, `car_type_name`, `car_type_created_at`, `car_type_updated_at`, `car_type_deleted_at`, `car_type_admin_status` FROM `cars_types` WHERE 1

//car_condition_id
   
//SELECT `car_condition_id`, `car_condition_name`, `car_condition_created_at`, `car_condition_updated_at`, `car_condition_deleted_at`, `car_condition_admin_status` FROM `cars_conditions` WHERE 1

//car_under_warranty_id

//SELECT `car_under_warranty_id`, `car_under_warranty_name`, `car_under_warranty_created_at`, `car_under_warranty_updated_at`, `car_under_warranty_deleted_at`, `car_under_warranty_admin_status` FROM `cars_under_warranties` WHERE 1

//car_advertisement_id;
   

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