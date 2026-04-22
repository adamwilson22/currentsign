<?php
    $xcrud = Xcrud::get_instance();	
	$xcrud->table("payments");
	$xcrud->columns(array('customerNumber','checkNumber','paymentDate'));
    $xcrud->buttons_arrange('dropdown-inline');//default , dropdown-inline, dropdown-list
	echo $xcrud->render();		
?>
