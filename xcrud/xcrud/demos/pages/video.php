<?php
$xcrud = Xcrud::get_instance();
$xcrud->table('uploads');

$xcrud->columns('simple_video');
$xcrud->fields('simple_video');
// image upload with resizing
$xcrud->change_type('simple_video', 'video');
$xcrud->unset_remove(true,'id','=','14');
echo $xcrud->render();
//echo $xcrud->render('edit', 14);
?>