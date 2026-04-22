<?php
include('config.php');
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
             
             
    $path = dirname(__DIR__) . "/" . '/xcrud/xcrud/xcrud.php';
    
    require($path);
    
    $xcrud = Xcrud::get_instance();
    $xcrud->table('sub_categories');
    
    
    /*
    SELECT `sub_cat_id`, `sub_cat_category_id`, `sub_cat_name`, `sub_cat_ar_name`, 
    `sub_cat_admin_status`, `sub_cat_create_at`, `sub_cat_updated_at`, `sub_cat_deleted_at` FROM `sub_categories` WHERE 1
    */
    
    
    $xcrud->columns('sub_cat_category_id,sub_cat_name,sub_cat_ar_name,sub_cat_admin_status');
    
    $xcrud->fields('sub_cat_category_id,sub_cat_name,sub_cat_ar_name,sub_cat_admin_status');
    
    $search = @$_REQUEST['search'];
    
    $xcrud->where("sub_cat_name like '$search%'");

    $xcrud->order_by('sub_cat_id','desc');

     $xcrud->label('sub_cat_category_id','MainCategory');
     $xcrud->label('sub_cat_name','EN Name');
     $xcrud->label('sub_cat_ar_name','AR Name');
     $xcrud->label('sub_cat_admin_status','AdminStatus');

     
     
      /*******************************************/
         $db_country = Xcrud_db::get_instance();
         $query_country = "SELECT * FROM `categories` WHERE 1";  
         
         $db_country->query($query_country);
        
         $result_country = $db_country->result();

         $select = array();



         foreach ($result_country as $key => $item)
         {

                  $select[$item['cat_id']] = $item['cat_name'];

         }
         /*******************************************/

        //print_r($select);
   
      $xcrud->change_type('sub_cat_category_id','select','',$select);
     
     

     //$xcrud->change_type('category_id','MainCategory');


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