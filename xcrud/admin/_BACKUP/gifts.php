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
    $xcrud->table('gifts');
    
     $xcrud->columns('gifts_id,gifts_sender_id,gifts_receiver_id,gifts_description,gifts_created_at,gifts_updated_at,gifts_deleted_at,gifts_admin_status,gifts_claim_status,gifts_amount');
     $xcrud->fields('gifts_id,gifts_sender_id,gifts_receiver_id,gifts_description,gifts_created_at,gifts_updated_at,gifts_deleted_at,gifts_admin_status,gifts_claim_status,gifts_amount');


//SELECT `gifts_id`, `gifts_sender_id`, `gifts_receiver_id`, `gifts_description`, `gifts_created_at`, `gifts_updated_at`, `gifts_deleted_at`, `gifts_admin_status`,`gifts_claim_status`, `gifts_amount` FROM `gifts` WHERE 1

     $xcrud->order_by('gifts_id','desc');
     
     
         $search = @$_REQUEST['search'];
    
    $xcrud->where("gifts_claim_status like '$search%'
    
    or gifts_sender_id like '$search%'
    or gifts_receiver_id like '$search%'
    or gifts_claim_status like '$search%'


    ");     

    // $xcrud->label('faqs_id','UserId');


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