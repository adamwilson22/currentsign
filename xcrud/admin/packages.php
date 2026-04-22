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
    $xcrud->table('packages');
    
    /*
    
SELECT `package_id`, `package_amount`, `package_duration_time`, `package_plan_name`, 
`package_quantity`, `package_created_at`, `package_updated_at`, `package_deleted_at`, 
`package_admin_status` FROM `packages` WHERE 1
    
    */
    
    
    $xcrud->columns('package_plan_name,package_amount,package_duration_time,package_quantity,package_admin_status');
    $xcrud->fields('package_plan_name,package_amount,package_duration_time,package_quantity,package_admin_status');
    
    $search = @$_REQUEST['search'];

    $xcrud->where("package_plan_name like '$search%' ");
    
    $xcrud->order_by('package_id','desc');

    $xcrud->label('package_plan_name','PlanName');
    $xcrud->label('package_amount','Amount');
    $xcrud->label('package_duration_time','Duration');
    $xcrud->label('package_quantity','Quantity');
    $xcrud->label('package_admin_status','AdminStatus');


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