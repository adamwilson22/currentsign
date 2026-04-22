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
    $xcrud->table('cars_models');
    
    /*
    
SELECT `car_model_id`, `car_model_name`, `car_model_ar_name`, `car_model_car_model_id`, `car_model_created_at`, `car_model_updated_at`, 
`car_model_deleted_at`, `car_model_admin_status` FROM `cars_models` WHERE 1
    
    */
    
    
    $xcrud->columns('car_model_id,car_model_name,car_model_car_maker_id,car_model_admin_status');
    $xcrud->fields('car_model_id,car_model_name,car_model_car_maker_id,car_model_admin_status');
    
    $search = @$_REQUEST['search'];

    $xcrud->where("car_model_id like '$search%' or car_model_admin_status like '$search%'");
    
    //$xcrud->where("car_maker_sub_category_id='52'");
    
    
    $xcrud->order_by('car_model_id','desc');

    $xcrud->label('car_model_id','Car Model Id');
    $xcrud->label('car_model_admin_status','Admin Status');

    $xcrud->label('car_model_car_maker_id','Car Maker');




   /*******************************************/
   /*******************************************/
   
  //SELECT `car_maker_id`, `car_maker_sub_category_id`, `car_maker_name`, `car_maker_created_at`, `car_maker_updated_at`, `car_maker_deleted_at`, `car_maker_admin_status` FROM `cars_makers` WHERE 1 
   
         $db_country = Xcrud_db::get_instance();
         $query_country = "SELECT * FROM `cars_makers` WHERE 1";  
         $db_country->query($query_country);
         $result_country = $db_country->result();
         $select = array();

         foreach ($result_country as $key => $item)
         {
                  $select[$item['car_maker_id']] = $item['car_maker_name'];
         }
        $xcrud->change_type('car_model_car_maker_id','select','',$select);

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