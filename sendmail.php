<?php
//get data from form  

$name = $_POST['name'];
$email= $_POST['email'];
$message= $_POST['message'];
$to = "info@consultree.co.uk";
$subject = "Online Enquiry";

$body ="Name: " . $name
 . "\r\n Email: " . $email
 . "\r\n Phone: " . $Phone
 . "\r\n Journey: " . $startroute
 . "\r\n Date: " . $onDate
 . "\r\n Passengers: " . $pax
 . "\r\n Special Requests: " . $other;

$headers = "From: " . $email . "\r\n" .
"BCC: diptanshu@gmail.com";
if($email!=NULL){
    mail($to,$subject,$body,$headers);
}
//redirect
header("Location:success.html");
?>