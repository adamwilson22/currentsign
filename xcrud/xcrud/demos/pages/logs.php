<?php	
	$xcrud2 = Xcrud::get_instance();	
	$xcrud2->table("logs");
	$xcrud2->limit(5);
	$xcrud2->unset_remove();
	$xcrud2->unset_add();
	$xcrud2->unset_edit();
	$xcrud2->order_by('updated','desc');
	echo $xcrud2->render();		
?>
