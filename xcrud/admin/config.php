<?php
error_reporting(0);
unlink('error_log');
session_start();
if(!isset($_SERVER['HTTP_REFERER'])) {
    // Request is not coming from an iframe
    header("HTTP/1.0 403 Forbidden");
    exit;
}
?>