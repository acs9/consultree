<?PHP
#------------------------------------------------------
# Forms To Go Lite 4.5.4 by http://www.bebosoft.com/
#------------------------------------------------------

define('kOptional', true);
define('kMandatory', false);

define('kStringRangeFrom', 1);
define('kStringRangeTo', 2);
define('kStringRangeBetween', 3);
        
define('kYes', 'yes');
define('kNo', 'no');


error_reporting(E_ERROR | E_WARNING | E_PARSE);
ini_set('track_errors', true);

function DoStripSlashes($fieldValue)  { 
// temporary fix for PHP6 compatibility - magic quotes deprecated in PHP6
 if ( function_exists( 'get_magic_quotes_gpc' ) && get_magic_quotes_gpc() ) { 
  if (is_array($fieldValue) ) { 
   return array_map('DoStripSlashes', $fieldValue); 
  } else { 
   return trim(stripslashes($fieldValue)); 
  } 
 } else { 
  return $fieldValue; 
 } 
}

function FilterCChars($theString) {
 return preg_replace('/[\x00-\x1F]/', '', $theString);
}

function CheckString($value, $low, $high, $mode, $limitAlpha, $limitNumbers, $limitEmptySpaces, $limitExtraChars, $optional) {

 $regEx = '';

 if ($limitAlpha == kYes) {
  $regExp = 'A-Za-z';
 }
 
 if ($limitNumbers == kYes) {
  $regExp .= '0-9'; 
 }
 
 if ($limitEmptySpaces == kYes) {
  $regExp .= ' '; 
 }

 if (strlen($limitExtraChars) > 0) {
 
  $search = array('\\', '[', ']', '-', '$', '.', '*', '(', ')', '?', '+', '^', '{', '}', '|', '/');
  $replace = array('\\\\', '\[', '\]', '\-', '\$', '\.', '\*', '\(', '\)', '\?', '\+', '\^', '\{', '\}', '\|', '\/');

  $regExp .= str_replace($search, $replace, $limitExtraChars);

 }

 if ( (strlen($regExp) > 0) && (strlen($value) > 0) ){
  if (preg_match('/[^' . $regExp . ']/', $value)) {
   return false;
  }
 }

 if ( (strlen($value) == 0) && ($optional === kOptional) ) {
  return true;
 } elseif ( (strlen($value) >= $low) && ($mode == kStringRangeFrom) ) {
  return true;
 } elseif ( (strlen($value) <= $high) && ($mode == kStringRangeTo) ) {
  return true;
 } elseif ( (strlen($value) >= $low) && (strlen($value) <= $high) && ($mode == kStringRangeBetween) ) {
  return true;
 } else {
  return false;
 }

}


function CheckEmail($email, $optional) {
 if ( (strlen($email) == 0) && ($optional === kOptional) ) {
  return true;
  } elseif ( preg_match("/^([\w\!\#$\%\&\'\*\+\-\/\=\?\^\`{\|\}\~]+\.)*[\w\!\#$\%\&\'\*\+\-\/\=\?\^\`{\|\}\~]+@((((([a-z0-9]{1}[a-z0-9\-]{0,62}[a-z0-9]{1})|[a-z])\.)+[a-z]{2,6})|(\d{1,3}\.){3}\d{1,3}(\:\d{1,5})?)$/i", $email) == 1 ) {
  return true;
 } else {
  return false;
 }
}




if (isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {
 $clientIP = $_SERVER['HTTP_X_FORWARDED_FOR'];
} else {
 $clientIP = $_SERVER['REMOTE_ADDR'];
}

$FTGenv_report = DoStripSlashes( $_POST['env_report'] );
$FTGname = DoStripSlashes( $_POST['name'] );
$FTGemail = DoStripSlashes( $_POST['email'] );
$FTGPhone = DoStripSlashes( $_POST['Phone'] );
$FTGstartroute = DoStripSlashes( $_POST['startroute'] );
$FTGonDate = DoStripSlashes( $_POST['onDate'] );
$FTGpax = DoStripSlashes( $_POST['pax'] );
$FTGother = DoStripSlashes( $_POST['other'] );
$FTGsubmit = DoStripSlashes( $_POST['submit'] );



$validationFailed = false;

# Fields Validations


if (!CheckString($FTGname, 3, 0, kStringRangeFrom, kYes, kNo, kYes, '', kMandatory)) {
 $FTGErrorMessage['name'] = 'Name required';
 $validationFailed = true;
}

if (!CheckEmail($FTGemail, kMandatory)) {
 $FTGErrorMessage['email'] = 'Email required';
 $validationFailed = true;
}



# Include message in error page and dump it to the browser

if ($validationFailed === true) {

 $errorPage = '<html><head><meta http-equiv="content-type" content="text/html; charset=utf-8" /><title>Error</title></head><body>Errors found: <!--VALIDATIONERROR--></body></html>';

 $errorPage = str_replace('<!--FIELDVALUE:env_report-->', $FTGenv_report, $errorPage);
 $errorPage = str_replace('<!--FIELDVALUE:name-->', $FTGname, $errorPage);
 $errorPage = str_replace('<!--FIELDVALUE:email-->', $FTGemail, $errorPage);
 $errorPage = str_replace('<!--FIELDVALUE:Phone-->', $FTGPhone, $errorPage);
 $errorPage = str_replace('<!--FIELDVALUE:startroute-->', $FTGstartroute, $errorPage);
 $errorPage = str_replace('<!--FIELDVALUE:onDate-->', $FTGonDate, $errorPage);
 $errorPage = str_replace('<!--FIELDVALUE:pax-->', $FTGpax, $errorPage);
 $errorPage = str_replace('<!--FIELDVALUE:other-->', $FTGother, $errorPage);
 $errorPage = str_replace('<!--FIELDVALUE:submit-->', $FTGsubmit, $errorPage);
 $errorPage = str_replace('<!--ERRORMSG:name-->', $FTGErrorMessage['name'], $errorPage);
 $errorPage = str_replace('<!--ERRORMSG:email-->', $FTGErrorMessage['email'], $errorPage);


 $errorList = @implode("<br />\n", $FTGErrorMessage);
 $errorPage = str_replace('<!--VALIDATIONERROR-->', $errorList, $errorPage);



 echo $errorPage;

}

if ( $validationFailed === false ) {

 # Email to Form Owner
  
 $emailSubject = FilterCChars("New Enquiry");
  
 $emailBody = "Name : $FTGname\n"
  . "Email : $FTGemail\n"
  . "Phone : $FTGPhone\n"
  . "Journey : $FTGstartroute\n"
  . "Date : $FTGonDate\n"
  . "PAX : $FTGpax\n"
  . "Special Requests : $FTGother\n"
  . "";
  $emailTo = 'info@consultree.co.uk';
  // $emailTo = 'diptanshu@gmail.com';
   
  $emailFrom = FilterCChars("$FTGemail");
  // $emailFrom = "info@consultree.co.uk";
   
  $emailHeader = "From: $emailFrom\n"
   // . 'Bcc: diptanshu@msn.com' . "\n"
   . "MIME-Version: 1.0\n"
   . "Content-type: text/plain; charset=\"UTF-8\"\n"
   . "Content-transfer-encoding: 8bit\n";
   
  mail($emailTo, $emailSubject, $emailBody, $emailHeader);
  
  
# Redirect user to success page

header("Location: success.html");

}

?>