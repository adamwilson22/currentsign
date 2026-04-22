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
    $xcrud->table('advertisements');
    

    
     $xcrud->columns('advertisement_id,advertisement_category_id,advertisement_sub_category_id,advertisement_created_at,advertisement_admin_status,advertisement_type');
    
     $xcrud->fields('advertisement_id,advertisement_category_id,advertisement_sub_category_id,advertisement_created_at,advertisement_admin_status,advertisement_type');
    
     $search = @$_REQUEST['search'];

     $xcrud->where("advertisement_id like '$search%' or advertisement_category_id like '$search%' or advertisement_sub_category_id like '$search%'");
    
     $xcrud->order_by('advertisement_id','desc');

    $xcrud->label('advertisement_id','ADSID');
    $xcrud->label('advertisement_category_id','Category');
    $xcrud->label('advertisement_sub_category_id','SubCategory');
    $xcrud->label('advertisement_created_at','Date');
    $xcrud->label('advertisement_admin_status','AdminStatus');



/*

SELECT `advertisement_id`, `advertisement_category_id`, `advertisement_sub_category_id`, `advertisement_created_at`, `advertisement_updated_at`, 
`advertisement_deleted_at`, `advertisement_admin_status`, `advertisement_type` FROM `advertisements` WHERE 1

*/
$xcrud->relation('advertisement_category_id','categories','cat_id','cat_name');


$xcrud->relation('advertisement_sub_category_id','sub_categories','sub_cat_id','sub_cat_name');

$xcrud->field_callback('advertisement_category_id','model_list_cars');



/*
SELECT `cat_id`, `cat_name`, `cat_image`, `cat_background_image`, `cat_description`, `cat_ar_description`, 
`cat_ar_name`, `cat_date_time`, `cat_admin_status`, `cat_created_at`, `cat_updated_at`, `cat_deleted_at` FROM `categories` WHERE 1

SELECT `sub_cat_id`, `sub_cat_category_id`, `sub_cat_name`, `sub_cat_ar_name`, `sub_cat_admin_status`, 
`sub_cat_created_at`, `sub_cat_updated_at`, `sub_cat_deleted_at` FROM `sub_categories` WHERE 1
*/

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