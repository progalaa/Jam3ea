<?php

/**
 * Static Pages
 */
class checkoutController extends Controller {

    public function __construct() {
        parent::__construct();
        $this->Data['dFullWidth'] = true;
    }

    public function index() {
        $d = &$this->Data;
        $d['dTitle'] = $this->_['_Checkout'];
        $this->API->method = ActionMethod::POST;
        $Data = $this->GetData();
        if ($this->Authentication(false)) {
            $this->API->SendAuth();
        } else {
            Session::Init();
            $cartItems = Session::Get('cart')? : $Data['CartItems'];
            $this->API->data = array('CartItems' => $cartItems);
        }
        if ($this->getTotalCart() < 10) {
//            Redirect(BASE_URL . 'cart');
        }
        $this->View->Render();
    }

    public function buy() {
        if ($this->getTotalCart() < 10) {
            Redirect(BASE_URL . 'cart');
        }
        Session::Init();
        if (Request::IsPost() || isset($_GET['PaymentID'])) {
            $d = &$this->Data;
            $pD = $this->GetData();
            if (GetValue($pD['Payment_Method']) == Payments_Methods::Knet && !isset($_GET['PaymentID'])) {
                if (!$this->Authentication(false)) {
                    Session::Set('VisitorData', $pD);
                }
                $this->PayWithKnet();
            }

            $this->API->url = GetAPIUrl('checkout/buy');
            $this->API->method = ActionMethod::POST;
            if ($this->Authentication(false)) {
                $this->API->data = array(
                    'Payment_Method' => isset($_GET['PaymentID']) ? Payments_Methods::Knet : Payments_Methods::Cash_On_Delivery,
                    'PaymentData' => $_GET
                );
                $this->API->SendAuth();
            } else {
                Session::Init();
                $cartItems = Session::Get('cart');
                $visitorData = isset($_GET['PaymentID']) ? Session::Get('VisitorData') : $this->GetData();
                $this->API->data = array(
                    'CartItems' => $cartItems,
                    'VisitorData' => $visitorData,
                    'Payment_Method' => isset($_GET['PaymentID']) ? Payments_Methods::Knet : Payments_Methods::Cash_On_Delivery,
                    'PaymentData' => $_GET
                );
            }
            $Data = $this->API->Process();
            $out = $Data['data'];
//echo '<pre>';var_dump($out);exit;
            $d['dTitle'] = $this->_['_Checkout'];
            $d['dMsg'] = $out['Message'];
            $d['dResults'] = $out['Products'];
            $d['dOrderID'] = $out['OrderID'];
            $d['dOrderDate'] = $out['OrderDate'];
            $d['dFullWidth'] = true;

            if (!$Data['success']) {
                $this->EchoErrors($Data['error']);
            } else {
                Session::DeSet('cart');
            }

            $this->View->RenderOnly();
        } else {
            RedirectNotFound();
        }
    }

    public function checkvalidation() {
        $this->API->url = GetAPIUrl('checkout/checkvalidation');
        $this->API->method = ActionMethod::POST;
        $this->API->data = array(
            'VisitorData' => $this->GetData()
        );
        $Data = $this->API->Process();
        if (!$Data['success']) {
            $this->EchoErrors($Data['error']);
        }
    }

    private function PayWithKnet() {
        Session::Init();
        $cartItems = Session::Get('cart');
        $this->API->url = GetAPIUrl('checkout/totalcart');
        $this->API->method = ActionMethod::POST;
        if ($this->Authentication(false)) {
            $this->API->SendAuth();
        } else {
            $this->API->data = array(
                'CartItems' => $cartItems
            );
        }
        $TotalData = $this->API->Process();

        $cls = $this->Data['pID'];
        $d = &$this->Data;
        $track = time() . rand(12354, 99999);
        $amount = $TotalData['data'];
        require_once BASE_DIR . "System/Plugins/knet/com/aciworldwide/commerce/gateway/plugins/e24PaymentPipe.inc.php";
        $Pipe = new e24PaymentPipe;
        $Pipe->setAction(1);
        $Pipe->setCurrency(414);
        $Pipe->setLanguage("ARA"); //change it to "ARA" for arabic language
        $Pipe->setResponseURL(BASE_URL . "{$cls}/response"); // set your respone page URL
        $Pipe->setErrorURL(BASE_URL . "{$cls}/error"); //set your error page URL
        $Pipe->setAmt($amount); //set the amount for the transaction
//$Pipe->setResourcePath("/Applications/MAMP/htdocs/php-toolkit/resource/");
        $Pipe->setResourcePath(BASE_DIR . "System/Plugins/knet/resource/"); //change the path where your resource file is
        $Pipe->setAlias("jme"); //set your alias name here
        $Pipe->setTrackId($track); //generate the random number here

        $Pipe->setUdf1($amount); //set User defined value
        $Pipe->setUdf2(""); //set User defined value
        $Pipe->setUdf3(""); //set User defined value
        $Pipe->setUdf4(""); //set User defined value
        $Pipe->setUdf5(""); //set User defined value
//get results
        if ($Pipe->performPaymentInitialization() != $Pipe->SUCCESS) {
            $d['dResult'] = $Pipe->SUCCESS;
            $d['dErrorMsg'] = $Pipe->getErrorMsg();
            $d['dDebugMsg'] = $Pipe->getDebugMsg();
            header("location: " . BASE_URL . "{$cls}/error");
        } else {
            $payID = $Pipe->getPaymentId();
            $payURL = $Pipe->getPaymentPage();
            $d['dDebugMsg'] = $Pipe->getDebugMsg();
            header("location:" . $payURL . "?PaymentID=" . $payID);
        }
        exit;
    }

    public function response() {
        $cls = $this->Data['pID'];
        $PaymentID = $_POST['paymentid'];
        $presult = $_POST['result'];
        $postdate = $_POST['postdate'];
        $tranid = $_POST['tranid'];
        $auth = $_POST['auth'];
        $ref = $_POST['ref'];
        $trackid = $_POST['trackid'];
        $udf1 = $_POST['udf1'];
        $udf2 = $_POST['udf2'];
        $udf3 = $_POST['udf3'];
        $udf4 = $_POST['udf4'];
        $udf5 = $_POST['udf5'];

        $Data = array(
            'PaymentID' => $_POST['paymentid'],
            'Result' => $_POST['result'],
            'PostDate' => $_POST['postdate'],
            'TranID' => $_POST['tranid'],
            'Auth' => $_POST['auth'],
            'Ref' => $_POST['ref'],
            'TrackID' => $_POST['trackid'],
            'UDF1' => $_POST['udf1'],
            'UDF2' => $_POST['udf2'],
            'UDF3' => $_POST['udf3'],
            'UDF4' => $_POST['udf4'],
            'UDF5' => $_POST['udf5']
        );

        if ($presult == "CAPTURED") {
            $result_url = BASE_URL . "{$cls}/buy";
            $result_params = "?PaymentID=" . $PaymentID . "&Result=" . $presult . "&PostDate=" . $postdate . "&TranID=" . $tranid . "&Auth=" . $auth . "&Ref=" . $ref . "&TrackID=" . $trackid . "&UDF1=" . $udf1 . "&UDF2=" . $udf2 . "&UDF3=" . $udf3 . "&UDF4=" . $udf4 . "&UDF5=" . $udf5;
            $allData = array();

            $this->API->data = $allData;
            $this->API->url = GetAPIUrl('checkout/buy');
            /*             * *****************************************************************
              /*******************************************************************
              Merchant must send the email to customer containing all the transaction details if the transactino was successfull
             */
        } else {
            $result_url = BASE_URL . "{$cls}/error";
            $result_params = "?PaymentID=" . $PaymentID . "&Result=" . $presult . "&PostDate=" . $postdate . "&TranID=" . $tranid . "&Auth=" . $auth . "&Ref=" . $ref . "&TrackID=" . $trackid . "&UDF1=" . $udf1 . "&UDF2=" . $udf2 . "&UDF3=" . $udf3 . "&UDF4=" . $udf4 . "&UDF5=" . $udf5;

            $this->API->url = GetAPIUrl('checkout/error');
            $this->API->data = $Data;
            $this->API->method = ActionMethod::POST;
            $this->API->Process();
        }
        echo "REDIRECT=" . $result_url . $result_params;
    }

    public function error() {
        $PaymentID = $_GET['PaymentID']; // Reads the value of the Payment ID passed by GET request by the user.
        $result = $_GET['Result']; // Reads the value of the Result passed by GET request by the user.
        $postdate = $_GET['PostDate']; // Reads the value of the PostDate passed by GET request by the user.
        $tranid = $_GET['TranID']; // Reads the value of the TranID passed by GET request by the user.
        $auth = $_GET['Auth']; // Reads the value of the Auth passed by GET request by the user.
        $ref = $_GET['Ref']; // Reads the value of the Ref passed by GET request by the user.
        $trackid = $_GET['TrackID'];  // Reads the value of the TrackID passed by GET request by the user.
        $udf1 = $_GET['UDF1'];  // Reads the value of the UDF1 passed by GET request by the user.
        $udf2 = $_GET['UDF2'];  // Reads the value of the UDF1 passed by GET request by the user.
        $udf3 = $_GET['UDF3'];  // Reads the value of the UDF1 passed by GET request by the user.
        $udf4 = $_GET['UDF4'];  // Reads the value of the UDF1 passed by GET request by the user.
        $udf5 = $_GET['UDF5'];  // Reads the value of the UDF1 passed by GET request by the user.

        $this->View->Render();
    }

    private function getTotalCart() {
        $this->API->url = GetAPIUrl('checkout/totalcart');
        $this->API->method = ActionMethod::POST;
        if ($this->Authentication(false)) {
            $this->API->SendAuth();
        } else {
            Session::Init();
            $cartItems = Session::Get('cart');
            $this->API->data = array('CartItems' => $cartItems);
        }
        $dCart = $this->API->Process();
        return $dCart['data'];
    }

}
