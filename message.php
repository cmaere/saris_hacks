<?php
function sendSmsMessage($in_phoneNumber, $in_msg)
 {
   $url = '/cgi-bin/sendsms?username=smsuser'
          . '&password=kcnroot'
          . '&charset=UCS-2&coding=2'
          . "&to={$in_phoneNumber}"
          . '&text=' . urlencode(iconv('utf-8', 'ucs-2', $in_msg));

   $results = file('http://10.60.61.35:13013'.$url);
 }


$in_phoneNumber = 0993684680;
$in_msg = "Hello world";

sendSmsMessage($in_phoneNumber, $in_msg);

?>