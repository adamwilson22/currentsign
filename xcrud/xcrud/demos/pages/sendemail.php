<?php

echo "Click <a href='https://www.mailinator.com/v4/public/inboxes.jsp?to=xcrud'>here </a>to access the email that has just been sent! <br>";
$xcrud = Xcrud::get_instance();

$emailBody = "Hey there. This is from xcrud.net!!";
$subject = "Hey there from xCrud";

$to = "xcrud@mailinator.com";
$xcrud->send_email_public($to, $subject, $emailBody, $cc = array(), true);

  /*
    SMPT Mail configuration in file xcrud_config.php
    
    public static $mail_host = mail.gmail.com; 
    public static $mail_port = 587; 
    public static $smtp_auth = true; 
    public static $username = xcrud17@gmail.com; 
    public static $pass = abc123; 
    public static $emailfrom = xCRUD Company; 
    public static $smtpsecure = 'tls';
    */

?>