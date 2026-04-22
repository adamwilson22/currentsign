<?php
	include('../../../admin/xcrud/xcrudA.php');
	$xcrud = Xcrud::get_instance();	

    //We need to figure out the command for cron job run this php page one time every morning 
    //When run, send email shown below to everyone selected for the xcrud table using information in the xcrud table (but do not email people the xcrud table)
    //Subject line for email should read $Session_day $Session_time | Willow Way Tutoring Appointment Reminder

    $currenttime = time();//Current unix time
    $max_time = ($currenttime + 86399);//

    $xcrud->table('Student_log');
    $xcrud->where('Send_time >=', $currenttime);
    $xcrud->where('Send_time <=', $max_time);

    $xcrud->join('People_id','People_list','People_id');
    $xcrud->join('WWT_id','Scheduled_list','WWT_id');
    
    $xcrud->columns('Student_log.Send_time,People_list.People_id,People_list.Email, Scheduled_list.Session_day,Scheduled_list.Session_time,Scheduled_list.Zoom_id');

    $xcrud->relation('People_list.People_id','People_list','People_id','First_name');
    $xcrud->relation('Scheduled_list.Session_day','Session_list_day','Session_id_day','Session_name_day');
    $xcrud->relation('Scheduled_list.Session_time','Session_list_time_display','Session_id_time','Session_name_time');
    $xcrud->relation('Scheduled_list.Zoom_id','Zoom_list','Zoom_id','Zoom_name');

    $xcrud->unset_remove();
    $xcrud->unset_print();
    $xcrud->unset_add();
    $xcrud->unset_edit();
    $xcrud->unset_view(); 

    echo $xcrud->render();
 
?>

<!DOCTYPE html>
<html>
<head>
<title></title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta http-equiv="X-UA-Compatible" content="IE=edge" />
<style type="text/css">
/* CLIENT-SPECIFIC STYLES */
body, table, td, a { -webkit-text-size-adjust: 100%; -ms-text-size-adjust: 100%; }
table, td { mso-table-lspace: 0pt; mso-table-rspace: 0pt; }
img { -ms-interpolation-mode: bicubic; }

/* RESET STYLES */
img { border: 0; height: auto; line-height: 100%; outline: none; text-decoration: none; }
table { border-collapse: collapse !important; }
body { height: 100% !important; margin: 0 !important; padding: 0 !important; width: 100% !important; }

/* iOS BLUE LINKS */
a[x-apple-data-detectors] {
    color: inherit !important;
    text-decoration: none !important;
    font-size: inherit !important;
    font-family: inherit !important;
    font-weight: inherit !important;
    line-height: inherit !important;
}

/* MEDIA QUERIES */
@media screen and (max-width: 480px) {
    .mobile-hide {
        display: none !important;
    }
    .mobile-center {
        text-align: center !important;
    }
}

/* ANDROID CENTER FIX */
div[style*="margin: 16px 0;"] { margin: 0 !important; }
</style>
</head>
<body style="margin: 0 !important; padding: 0 !important; background-color: #eeeeee;" bgcolor="#eeeeee">

<!-- HIDDEN PREHEADER TEXT -->
<div style="display: none; font-size: 1px; color: #fefefe; line-height: 1px; font-family: Open Sans, Helvetica, Arial, sans-serif; max-height: 0px; max-width: 0px; opacity: 0; overflow: hidden;">
We're excited to see you!
</div>

<table border="0" cellpadding="0" cellspacing="0" width="100%">
    <tr>
        <td align="center" style="background-color: #eeeeee;" bgcolor="#eeeeee">
        
        <table align="center" border="0" cellpadding="0" cellspacing="0" width="100%" style="max-width:600px;">
            <tr>
                <td align="center" valign="top" style="font-size:0; padding: 35px;" bgcolor="#044767">
            
                <div style="display:inline-block; max-width:100%; vertical-align:top; width:100%;">
                    <table align="left" border="0" cellpadding="0" cellspacing="0" width="100%" style="max-width:600px;">
                        <tr>
                            <td align="center" valign="top" style="font-family: Open Sans, Helvetica, Arial, sans-serif; font-size: 36px; font-weight: 800; line-height: 48px;" class="mobile-center">
                                <h1 style="font-size: 36px; font-weight: 800; margin: 0; color: #ffffff;">Willow Way Tutoring</h1>
                            </td>
                        </tr>
                    </table>
                </div>
                
             
                </td>
            </tr>
            <tr>
                <td align="center" style="padding: 35px; background-color: #ffffff;" bgcolor="#ffffff">
                <table align="center" border="0" cellpadding="0" cellspacing="0" width="100%" style="max-width:600px;">
                    <tr>
                        <td align="center" style="font-family: Open Sans, Helvetica, Arial, sans-serif; font-size: 16px; font-weight: 400; line-height: 24px; padding-bottom: 15px; border-bottom: 3px solid #eeeeee;">
                            <img src="http://wwtutoring.club/assets/img/logo.png" width="190" height="187" style="display: block; border: 0px;"/><br>
                            <h2 style="font-size: 30px; font-weight: 800; line-height: 36px; color: #333333; margin: 0;">
                                Appointment Reminder<br>
                          
                             <p style="font-size: 14px; font-weight: 400; line-height: 22px; color: #333333; margin: 0;">
                                From SJUSD Newcomers / Refugee Support Team and Willow Way Tutoring<br><br>

                            </p>
                          
                              </h2>
                            <br>
                            <p align="left" style="font-size: 16px; font-weight: 400; line-height: 24px; color: #777777;">
                               What: Newcomers Support
                            </p>
                             <p align="left" style="font-size: 16px; font-weight: 400; line-height: 24px; color: #777777;">
                               When: $Session_name_day at $Session_name_time_display PDT
                            </p>
                            
            
                        </td>
                    </tr>
                    <tr>
                        <td align="center" style="padding: 25px 0;">
                            <table border="0" cellspacing="0" cellpadding="0">
                                <tr>
                                    <td align="center" style="border-radius: 5px;" bgcolor="#9BA17B">
                                 
                                      <a href="$Zoom_name" target="_blank" style="font-size: 18px; font-family: Open Sans, Helvetica, Arial, sans-serif; color: #ffffff; text-decoration: none; border-radius: 5px; background-color: #9BA17B; padding: 15px 30px; border: 1px solid #9BA17B; display: block;">$Zoom_name</a>
                                    </td>
                                </tr>
                                <br>
                       
                            </table>
                            <tr>
                        <td align="center" style="font-family: Open Sans, Helvetica, Arial, sans-serif; font-size: 20px; font-weight: 400; line-height: 24px; padding-bottom: 25px;">
                            <h3 style="font-size: 20px; font-weight: 800; line-height: 24px; color: #333333; margin: 0;">
                                $First_name, we're looking forward to seeing you soon!
                            </h3>
                            <h3 style="font-size: 18px; font-weight: 400; line-height: 24px; color: #333333; margin: 0;">
                                -Your friends at Willow Way Tutoring
                            </h3>
                        </td>
                    </tr>
                        </td>
                    </tr>
                </table>
                </td>
            </tr>
                </table>
                </td>
            </tr>
          
        </table>
        </td>
    </tr>
</table>

 </body>
</html>
