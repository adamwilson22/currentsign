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
    $xcrud->table('wallet_transaction')->where('wal_tra_service_type','WALLET_RECHARGE');
    
     $xcrud->columns('wal_tra_id,wal_tra_users_id,wal_tra_transaction_reference,wal_tra_wallet_transaction_ufitpay_callback_id,wal_tra_transaction_date,wal_tra_customer_account_id,wal_tra_description,wal_tra_customer_name,wal_tra_transaction_value,wal_tra_transaction_fee,wal_tra_transaction_app_fee,wal_tra_service_code,wal_tra_session_id,wal_tra_credit_account_number,wal_tra_request_ref,wal_tra_created_at,wal_tra_updated_at');
    
     $xcrud->fields('wal_tra_id,wal_tra_users_id,wal_tra_transaction_reference,wal_tra_wallet_transaction_ufitpay_callback_id,wal_tra_transaction_date,wal_tra_customer_account_id,wal_tra_description,wal_tra_customer_name,wal_tra_transaction_value,wal_tra_transaction_fee,wal_tra_transaction_app_fee,wal_tra_service_code,wal_tra_session_id,wal_tra_credit_account_number,wal_tra_request_ref,wal_tra_created_at,wal_tra_updated_at');


    $search = @$_REQUEST['search'];
    $xcrud->where("wal_tra_transaction_reference like '$search%' 
    or wal_tra_transaction_date like '$search%'
    or wal_tra_customer_account_id like '$search%'


    ");

    $xcrud->order_by('wal_tra_id','desc');
    
    // $xcrud->order_by('faqs_id','desc');

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