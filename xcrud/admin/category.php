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
    $xcrud->table('categories');
    
/*
SELECT `cat_id`, `cat_name`, `cat_image`, `cat_background_image`, `cat_description`, `cat_ar_description`, `cat_ar_name`, `cat_date_time`,
`cat_admin_status`, `cat_created_at`, `cat_updated_at`, `cat_deleted_at` FROM `categories` WHERE 1

*/

    
    $xcrud->columns('cat_name,cat_description,cat_ar_description,cat_ar_name,cat_ar_description,cat_admin_status');
    
    $xcrud->fields('cat_name,cat_description,cat_ar_description,cat_ar_name,cat_ar_description,cat_admin_status');
    
    $search = @$_REQUEST['search'];

    $xcrud->where("cat_name like '$search%'");
    
    $xcrud->order_by('cat_id','desc');
    
    $xcrud->label('cat_name','EN Name');
    $xcrud->label('cat_description','EN Description');
    $xcrud->label('cat_ar_name','ARName');
    $xcrud->label('cat_ar_description','AR Description');
    $xcrud->label('cat_admin_status','AdminStatus');


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