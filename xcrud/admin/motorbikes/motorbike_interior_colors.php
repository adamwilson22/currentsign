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
    $xcrud->table('motorbikes_interior_colors');
    
    /*
    
SELECT `motorbike_interior_color_id`, `motorbike_interior_color_name`, `motorbike_interior_color_created_at`, `motorbike_interior_color_updated_at`,
`motorbike_interior_color_deleted_at`, `motorbike_interior_color_admin_status` FROM `motorbikes_body_colors` WHERE 1    */
    
    
    $xcrud->columns('motorbike_interior_color_id,motorbike_interior_color_name,motorbike_interior_color_admin_status');
    $xcrud->fields('motorbike_interior_color_id,motorbike_interior_color_name,motorbike_interior_color_admin_status');
    
    $search = @$_REQUEST['search'];

    $xcrud->where("motorbike_interior_color_id like '$search%' or motorbike_interior_color_name like '$search%'");
    
    $xcrud->order_by('motorbike_interior_color_id','desc');

    $xcrud->label('motorbike_interior_color_id','InteriorColorId');
    


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