<?php
    $xcrud = Xcrud::get_instance();	
	$xcrud->table("payments");
	$xcrud->set_logging(true);
	$xcrud->limit(5);	
	echo $xcrud->render();			
?>
