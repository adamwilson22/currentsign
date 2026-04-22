<?php
ob_start();
include 'htmlemail.php';
$body = ob_get_clean();
?>