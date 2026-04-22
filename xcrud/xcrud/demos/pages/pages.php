<?php

    session_start();
    include('xcrud/xcrud.php');
	$db = Xcrud_db::get_instance();
	
?>

<button class="btn btn-primary" onclick="showPages();">Show Pages</button>
<script>
	function showPages(){		
		 Xcrud.request('.xcrud-ajax',Xcrud.list_data('.xcrud-ajax',{action: 'get_pages', task:'action',selected:items,table:'million',identifier:'id'}))	     
	}
</script>
