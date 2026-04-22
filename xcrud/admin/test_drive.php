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
    $xcrud->table('orders_test_drive');
    
/*

SELECT `order_test_drive_id`, `order_test_drive_user_id`, `order_test_drive_created_at`, `order_test_drive_updated_at`, 
`order_test_drive_deleted_at`, `order_test_drive_admin_status`, `order_test_drive_user_status`, 
`order_test_drive_order_status` FROM `orders_test_drive` WHERE 1


*/

    $xcrud->columns('order_test_drive_id,order_test_drive_user_id,order_test_drive_admin_status,order_test_drive_user_status,order_test_drive_order_status');
    $xcrud->fields('order_test_drive_id,order_test_drive_user_id,order_test_drive_admin_status,order_test_drive_user_status,order_test_drive_order_status');
    
    $search = @$_REQUEST['search'];

    //$xcrud->where("cat_name like '$search%'");
    
    $xcrud->order_by('order_test_drive_id','desc');
    
    $xcrud->label('order_test_drive_id','OrderId');
    
    
    $xcrud->label('order_test_drive_user_id','User');

    $xcrud->label('order_test_drive_admin_status','Admin Status');
    $xcrud->label('order_test_drive_user_status','User Status');
    $xcrud->label('order_test_drive_order_status','Order Status');
    // $xcrud->label('order_price_when_order','Price');
    // $xcrud->label('order_price_at_order_time','Price On Order');


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