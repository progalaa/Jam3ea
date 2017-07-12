<?php
ob_start();
ini_set("display_errors", "1");
error_reporting(E_ALL);
require_once '../../Libraries/Config.php';

require_once "com/aciworldwide/commerce/gateway/plugins/e24PaymentPipe.inc.php";
$Pipe = new e24PaymentPipe;

$Pipe->setAction(1);
$Pipe->setCurrency(414);
$Pipe->setLanguage("ARA"); //change it to "ARA" for arabic language
$Pipe->setResponseURL("https://www.jm3eia.com/System/Plugins/knet/response.php"); // set your respone page URL
$Pipe->setErrorURL("https://www.jm3eia.com/System/Plugins/knet/error.php"); //set your error page URL
$Pipe->setAmt("10"); //set the amount for the transaction
//$Pipe->setResourcePath("/Applications/MAMP/htdocs/php-toolkit/resource/");
$Pipe->setResourcePath(BASE_DIR . "System/Plugins/knet/resource/"); //change the path where your resource file is
$Pipe->setAlias("jme"); //set your alias name here
$Pipe->setTrackId(time()); //generate the random number here

$Pipe->setUdf1("UDF 1"); //set User defined value
$Pipe->setUdf2("UDF 2"); //set User defined value
$Pipe->setUdf3("UDF 3"); //set User defined value
$Pipe->setUdf4("UDF 4"); //set User defined value
$Pipe->setUdf5("UDF 5"); //set User defined value
//get results
if ($Pipe->performPaymentInitialization() != $Pipe->SUCCESS) {
//    echo "Result=" . $Pipe->SUCCESS;
//    echo "<br>" . $Pipe->getErrorMsg();
//    echo "<br>" . $Pipe->getDebugMsg();
    header("location: https://www.jm3eia.com/System/Plugins/knet/error.php");
} else {
    $payID = $Pipe->getPaymentId();
    $payURL = $Pipe->getPaymentPage();
//    echo $Pipe->getDebugMsg();
    header("location:" . $payURL . "?PaymentID=" . $payID);
}
?>