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
    
    
    $xcrud->columns('motorbike_id,motorbike_name,motorbike_class_id,motorbike_admin_id,motorbike_review_count,motorbike_price,motorbike_maker_id,motorbike_model_id,motorbike_model_year_id,motorbike_reginal_specification_id,motorbike_kilometers,motorbike_engine_capacity_id,motorbike_cylinder_id,motorbike_transmission_id,motorbike_body_color_id,motorbike_fuel_type_id,motorbike_door_id,motorbike_city_id,motorbike_created_at,motorbike_updated_at,motorbike_deleted_at,motorbike_admin_status,motorbike_rim_sizes_id,motorbike_type_id,motorbike_condition_id,motorbike_under_warranty_id,motorbike_extra_features,motorbike_rent_price,motorbike_advertisement_id');
    $xcrud->fields('motorbike_id,motorbike_name,motorbike_class_id,motorbike_admin_id,motorbike_review_count,motorbike_price,motorbike_maker_id,motorbike_model_id,motorbike_model_year_id,motorbike_reginal_specification_id,motorbike_kilometers,motorbike_engine_capacity_id,motorbike_cylinder_id,motorbike_transmission_id,motorbike_body_color_id,motorbike_fuel_type_id,motorbike_door_id,motorbike_city_id,motorbike_created_at,motorbike_updated_at,motorbike_deleted_at,motorbike_admin_status,motorbike_rim_sizes_id,motorbike_type_id,motorbike_condition_id,motorbike_under_warranty_id,motorbike_extra_features,motorbike_rent_price,motorbike_advertisement_id');
    
    $search = @$_REQUEST['search'];

    $xcrud->where("motorbike_id like '$search%' or motorbike_name like '$search%'");
    
    $xcrud->order_by('motorbike_id','desc');


    $xcrud->label('motorbike_id','MotorbikeId');
    $xcrud->label('motorbike_class_id','ClassName');
    $xcrud->label('motorbike_user_id','User');
    $xcrud->label('motorbike_admin_id','Admin');
    $xcrud->label('motorbike_maker_id','MakerName');
    $xcrud->label('motorbike_model_id','ModelName');
    $xcrud->label('motorbike_model_year_id','ModelYear');
    $xcrud->label('motorbike_reginal_specification_id','ModelSpecification');
    $xcrud->label('motorbike_engine_capacity_id','Capacity');
    $xcrud->label('motorbike_cylinder_id','Cylinder');
    $xcrud->label('motorbike_transmission_id','Transmission');
    $xcrud->label('motorbike_body_color_id','BodyColor');
    $xcrud->label('motorbike_fuel_type_id','FuelType');
    $xcrud->label('motorbike_door_id','Doors');
    $xcrud->label('motorbike_city_id','City');
    // $xcrud->label('motorbike_plates_codes_id','motorbike_id');
    // $xcrud->label('motorbike_plate_design_id','motorbike_id');
    // $xcrud->label('motorbike_plate_source_id','motorbike_id');
    $xcrud->label('motorbike_rim_sizes_id','RimSize');
    $xcrud->label('motorbike_type_id','Type');
    $xcrud->label('motorbike_condition_id','Condition');
    $xcrud->label('motorbike_under_warranty_id','Warranty');
    $xcrud->label('motorbike_advertisement_id','AdsId');


   /*******************************************/
   /*******************************************/
   
         $db_country = Xcrud_db::get_instance();
         $query_country = "SELECT * FROM `motorbikes_classes`";  
         $db_country->query($query_country);
         $result_country = $db_country->result();
         $select = array();

         foreach ($result_country as $key => $item)
         {
                  $select[$item['motorbike_class_id']] = $item['motorbike_class_name'];
         }
         
        $xcrud->change_type('motorbike_class_id','select','',$select);

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
         
        $xcrud->change_type('motorbike_admin_id','select','',$select);

   /*******************************************/
   /*******************************************/



   /*******************************************/
   /*******************************************/
   
        //  $db_country = Xcrud_db::get_instance();
        //  $query_country = "SELECT * FROM `motorbikes_makers` where `motorbike_maker_sub_category_id` = '52'";  
        //  $db_country->query($query_country);
        //  $result_country = $db_country->result();
        //  $select = array();

        //  foreach ($result_country as $key => $item)
        //  {
        //           $select[$item['motorbike_maker_id']] = $item['motorbike_maker_name'];
        //  }
         
        // $xcrud->change_type('motorbike_maker_id','select','',$select);

   /*******************************************/
   /*******************************************/
   



   /*******************************************/
   /*******************************************/
   
   
 $xcrud->relation('motorbike_maker_id','motorbikes_makers','motorbike_maker_id','motorbike_maker_name' , "`motorbike_maker_sub_category_id` = '52'");
 
 $xcrud->relation('motorbike_model_id','motorbikes_models','motorbike_model_id', 'motorbike_model_name' ,'','','','','','motorbike_maker_id','motorbike_maker_id');
     
 $xcrud->relation('motorbike_model_year_id','motorbikes_years','motorbike_year_id','motorbike_year_name' );

 $xcrud->relation('motorbike_reginal_specification_id','motorbikes_regional_specs','motorbike_regional_specs_id','motorbike_regional_specs_name' );

//motorbike_engine_capacity_id

//SELECT `motorbike_engine_capacity_id`, `motorbike_engine_capacity_value`, `motorbike_engine_capacity_created_at`, `motorbike_engine_capacity_updated_at`, `motorbike_engine_capacity_deleted_at`, `motorbike_engine_capacity_admin_status` FROM `motorbikes_engine_capacities` WHERE 1
   
 $xcrud->relation('motorbike_engine_capacity_id','motorbikes_engine_capacities','motorbike_engine_capacity_id','motorbike_engine_capacity_value' );
   
//motorbike_cylinder_id

//SELECT `motorbike_cylinder_id`, `motorbike_cylinder_count`, `motorbike_cylinder_created_at`, `motorbike_cylinder_updated_at`, `motorbike_cylinder_deleted_at`, `motorbike_cylinder_admin_status` FROM `motorbikes_cylinders` WHERE 1   
   
 $xcrud->relation('motorbike_cylinder_id','motorbikes_cylinders','motorbike_cylinder_id','motorbike_cylinder_count' );

//motorbike_transmission_id
      
//SELECT `motorbike_transmission_id`, `motorbike_transmission_name`, `motorbike_transmission_created_at`, `motorbike_transmission_updated_at`, `motorbike_transmission_deleted_at`, `motorbike_transmission_admin_status` FROM `motorbikes_transmissions` WHERE 1

 $xcrud->relation('motorbike_transmission_id','motorbikes_transmissions','motorbike_transmission_id','motorbike_transmission_name' );


//motorbike_body_color_id

//SELECT `motorbike_fuel_type_id`, `motorbike_fuel_type_name`, `motorbike_fuel_type_created_at`, `motorbike_fuel_type_updated_at`, `motorbike_fuel_type_deleted_at`, `motorbike_fuel_type_admin_status` FROM `motorbikes_fuel_types` WHERE 1

 $xcrud->relation('motorbike_fuel_type_id','motorbikes_fuel_types','motorbike_fuel_type_id','motorbike_fuel_type_name' );

//motorbike_fuel_type_id
   
//SELECT `motorbike_interior_color_id`, `motorbike_interior_color_name`, `motorbike_interior_color_created_at`, `motorbike_interior_color_updated_at`, `motorbike_interior_color_deleted_at`, `motorbike_interior_color_admin_status` FROM `motorbikes_body_colors` WHERE 1

//motorbike_door_id

//SELECT `motorbike_door_id`, `motorbike_door_count`, `motorbike_door_created_at`, `motorbike_door_updated_at`, `motorbike_door_deleted_at`, `motorbike_door_admin_status` FROM `motorbikes_doors` WHERE 1


 $xcrud->relation('motorbike_door_id','motorbikes_doors','motorbike_door_id','motorbike_door_count' );


//motorbike_city_id

//SELECT `motorbike_city_id`, `motorbike_city_name`, `motorbike_city_created_at`, `motorbike_city_updated_at`, `motorbike_city_deleted_at`, `motorbike_city_admin_status` FROM `motorbikes_cities` WHERE 1

//motorbike_rim_sizes_id

//SELECT `motorbike_rim_size_id`, `motorbike_rim_size_name`, `motorbike_rim_size_created_at`, `motorbike_rim_size_updated_at`, `motorbike_rim_size_deleted_at`, `motorbike_rim_size_admin_status` FROM `motorbikes_rim_sizes` WHERE 1

//motorbike_type_id

//SELECT `motorbike_type_id`, `motorbike_type_name`, `motorbike_type_created_at`, `motorbike_type_updated_at`, `motorbike_type_deleted_at`, `motorbike_type_admin_status` FROM `motorbikes_types` WHERE 1

//motorbike_condition_id
   
//SELECT `motorbike_condition_id`, `motorbike_condition_name`, `motorbike_condition_created_at`, `motorbike_condition_updated_at`, `motorbike_condition_deleted_at`, `motorbike_condition_admin_status` FROM `motorbikes_conditions` WHERE 1

//motorbike_under_warranty_id

//SELECT `motorbike_under_warranty_id`, `motorbike_under_warranty_name`, `motorbike_under_warranty_created_at`, `motorbike_under_warranty_updated_at`, `motorbike_under_warranty_deleted_at`, `motorbike_under_warranty_admin_status` FROM `motorbikes_under_warranties` WHERE 1

//motorbike_advertisement_id;
   

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