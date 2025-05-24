<?php

$where_form_is="http://".$_SERVER['SERVER_NAME'].strrev(strstr(strrev($_SERVER['PHP_SELF']),"/"));

session_start();
//if( ($_SESSION['security_code']==$_POST['security_code']) && (!empty($_POST['security_code'])) ) { 
mail("diptanshu@gmail.com","Online Enquiry","Form data:

Name: " . $_POST['uName'] . " 
Phone: " . $_POST['Phone'] . " 
Email: " . $_POST['umail'] . " 
From Location: " . $_POST['startroute'] . " 
To Location: " . $_POST['endroute'] . " 
Date: " . $_POST['onDate'] . " 
Passengers: " . $_POST['pax'] . " 

___________________________
 Sent from consultree.co.uk
");

include("Thanks.html");
//}
// else {
// echo "Invalid Captcha String.";
// }

?>