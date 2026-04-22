<?php echo $this->render_table_name($mode); ?>
<div class="xcrud-top-actions">
	<?php 
 if($this->is_next_previous){
	 echo $this->render_previous('Previous','edit','','xcrud-button xcrud-green','','edit');
	 echo $this->details_counter();    
	 echo $this->render_next('Next','edit','','xcrud-button xcrud-green','','edit');
     echo "<br><br>";  
 }
 ?>
	
    <?php echo $this->render_button('save_new','save','create','xcrud-button xcrud-blue','','create,edit') ?>
    <?php echo $this->render_button('save_edit','save','edit','xcrud-button xcrud-green','','create,edit') ?>
    <?php echo $this->render_button('save_return','save','list','xcrud-button xcrud-purple','','create,edit') ?>
    <?php echo $this->render_button('return','list','','xcrud-button xcrud-orange') ?>
</div>
<form data-parsley-validate='' class="parsley-form">
<div class="xcrud-view">
<?php echo $this->render_fields_list($mode); ?>
</div>
</form>
<div class="xcrud-nav">
    <?php echo $this->render_benchmark(); ?>
</div>
<?php
if($this->parsley_active){
	include("xcrud_parsley.php");
}
?>