<?php
	$xcrud = Xcrud::get_instance();
    $xcrud->table('employees');
    $xcrud->join('officeCode','offices','officeCode'); // ... INNER JOIN offices ON employees.officeCode = offices.officeCode ...
    echo $xcrud->render();
	
	 
	
?>
<script>
			$(document).ready(function() {
				$(".xcrud-list").tableHeadFixer({"head" : false, "left" : 2}); 
			});
		</script>