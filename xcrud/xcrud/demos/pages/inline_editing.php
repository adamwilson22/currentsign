<?php
    $xcrud = Xcrud::get_instance();	
	$xcrud->table_name('Payments - Single click cell to edit!');
	$xcrud->table('payments');
	$xcrud->unset_remove();
	$xcrud->fields_inline('customerNumber,checkNumber,paymentDate,amount');//set the fields to allow inline editing
	$xcrud->unset_edit();
	$xcrud->set_logging(true);
    echo $xcrud->render();
?>
