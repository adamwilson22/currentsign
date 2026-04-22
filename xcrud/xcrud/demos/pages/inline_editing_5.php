<?php
	$xcrud1 = Xcrud::get_instance();
    $xcrud1->table('orders');
	$xcrud1->fields_inline('orderNumber,orderDate,requiredDate,shippedDate,comments');//set the fields to allow inline editing
    echo $xcrud1->render();
    
    $xcrud2 = Xcrud::get_instance();
    $xcrud2->table('payments');
	$xcrud2->fields_inline('customerNumber,checkNumber,paymentDate,amount');//set the fields to allow inline editing
    echo $xcrud2->render();
?>