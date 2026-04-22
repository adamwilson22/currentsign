<br>
<?php
    $xcrud = Xcrud::get_instance();
	   $languages = array(
	              array('en','English'),
	              array('fr','French'),
				  array('de','Germany'),
				  array('es','Spanish'));
	if(isset($_GET['language'])){
		$language = $_GET['language'];   
	}else{
		$language = 'en';//default to english   
	}
	
	$xcrud->language($language);
	//gets translation from one of the files based on selection
	//xcrud/languages/de.ini;
	//xcrud/languages/fr.ini;
	//xcrud/languages/es.ini;
	
	echo "<label>" . $xcrud->translate_external_text("Select Language") . "</label>";
?>

<select class="select-language selectpicker form-control" title="" data-width="110px">                              
     <?php						  
			foreach ( $languages as $val ) {	
					if($language == $val[0]){
						echo "<option selected value=" . $val[0]  . ">" . $val[1] . "</option>";	
					}else{
						echo "<option value=" . $val[0]  . ">" . $val[1] . "</option>";	
					}								
			}			
		?>
</select>

<?php
   	
	 
	$xcrud->table("payments");
	$xcrud->table_name($xcrud->translate_external_text("Payments"));
	$xcrud->label('checkNumber',$xcrud->translate_external_text("check_number"));
	$xcrud->label('paymentDate',$xcrud->translate_external_text("payment_date"));
	$xcrud->label('customerNumber',$xcrud->translate_external_text("check_number"));
	$xcrud->label('amount',$xcrud->translate_external_text("amount"));	   
	
	echo $xcrud->render();			
?>

<script>
	
	$(".select-language").change(function(){
		var hrefString = window.location.href;
		hrefString = hrefString.replace('&language=en',''); 
		hrefString = hrefString.replace('&language=fr',''); 
		hrefString = hrefString.replace('&language=de',''); 
		hrefString = hrefString.replace('&language=es','');  
		window.location = hrefString + "&language=" + $(".select-language").val();
	});
	
</script>
