
<?php
    $xcrud = Xcrud::get_instance();	
	$xcrud->table('payments');
	$xcrud->unset_remove();
	$xcrud->group_by_columns('customerNumber');//Allows only one field
	$xcrud->group_sum_columns('amount');//Allows only one field
	$xcrud->columns('customerNumber,checkNumber,amount');
	$xcrud->fields_inline('customerNumber,checkNumber,paymentDate,amount');
    echo $xcrud->render();
?>
