<?php

/**
 *
 * @author MrAbdelrahman10
 */
class checkoutController extends ControllerAPI {

    public function index() {

    }

    public function buy() {
        if (Request::IsPost()) {
            Session::Init();
            $_ = &$this->_;
            $JsonData = $this->GetJsonData();
//            EchoJson($JsonData);

            if (!$this->Authentication(false)) {
                $d = $this->Validation();
                if ($d) {
                    $this->JsonParser->error = $d;
                    $this->JsonParser->success = false;
                    $this->JsonParser->Response();
                }
                $this->Model->VisitorData = serialize($JsonData['VisitorData']);
            }
            $this->FinishCart();
            $this->JsonParser->Response();
        }
    }

    public function totalcart() {
        $Data = array();
        if ($this->Authentication(false)) {
            $Data = $this->Model->GetAll();
        } else {
            $Items = $this->GetJsonData();
            if (isset($Items['CartItems'])) {
                $Data = $this->Model->GetAllByIDs($Items['CartItems']);
            }
        }
        $total = 0;
        if ($Data) {
            foreach ($Data as $i) {
                if (!isset($i['cQuantity'])) {
                    foreach ($Items['CartItems'] as $td) {
                        if ($td['ID'] == $i['ID']) {
                            $i['cQuantity'] = $td['Quantity'];
                            break;
                        }
                    }
                }
                $total += ($i['Price'] * $i['cQuantity']);
            }
        }
        $total += $this->Settings['sShippingCost'];
        $this->setJsonParser($total, false);
    }

    private function FinishCart() {
        $_ = &$this->_;
        $___ = null;
        $JsonData = $this->GetJsonData();

        if (isset($JsonData['CartItems'])) {
            $tData = $this->Model->GetAllByIDs($JsonData['CartItems']);

            foreach ($tData as $i) {
                if ($i['State']) {
                    $i['SliderPictures'] = (unserialize($i['SliderPictures']));
                    if (!isset($i['cQuantity'])) {
                        foreach ($JsonData['CartItems'] as $td) {
                            if ($td['ID'] == $i['ID']) {
                                $i['cQuantity'] = $td['Quantity'];
                                $i_d = array(
                                    'Product' => $td['ID'],
                                    'Quantity' => $td['Quantity']
                                );
                                $this->Model->Add($i_d);
                                break;
                            }
                        }
                    }
                    $Data[] = $i;
                }
            }
        } else {
            $Data = $this->Model->GetAll();
        }
        if ($Data) {
            $Payment_Method = GetValue($JsonData['Payment_Method'], 1);
            $___ = $this->Model->FinishCart($Payment_Method);
        }

        if (!$___) {
            $this->JsonParser->error = $_['_ErrorUnexpected'];
            $this->JsonParser->success = false;
        } else {
            $this->JsonParser->data = array(
                'Message' => $_['_Order_Added'],
                'OrderID' => $this->Model->LastCartID,
                'OrderDate' => GetDateValue(),
                'Products' => $Data
            );

            $this->Model->UpdateProductData();

            if ($Payment_Method == Payments_Methods::Knet) {
                $Pay_Data = $JsonData['PaymentData'];
                $Pay_Data['CartID'] = $this->Model->LastCartID;
                $Pay_Data['Amount'] = $Pay_Data['UDF1'];

                $this->Model->AddPayment($Pay_Data);

                $this->JsonParser->data = array_merge($this->JsonParser->data, array(
                    'Payment' => $Pay_Data
                ));
            }
            $this->SendBuyMail($this->JsonParser->data);
        }
    }

    private function SendBuyMail($Data) {
        $_ = $this->LoadLangFile();

        $File = BASE_DIR . 'Mail.php';
        $Handle = fopen($File, 'w');
        fwrite($Handle, serialize($Data));
        fclose($Handle);

        ob_start();
        include API_PATH . 'Views/Checkout_Mail.php';
        $body = ob_get_contents();
        ob_end_clean();

        $Mail = new Mail();
        $Mail->From = $this->Settings['sEmail'];
        $Mail->FromName = $this->Settings['sSiteName'];
        $Mail->To = GetValue($pData['VisitorData']['Email'], $this->pUser ? $this->pUser['Email'] : null);
        $Mail->Subject = $this->Settings['sSiteName'] . ' :: ' . $_['_NewPayment'];
        $Mail->Body = $body;
        $Mail->Send();

        /*         * ********* Copy to Admin ***********       */
        $Mail2 = new Mail();
        $Mail2->From = GetValue($pData['VisitorData']['Email'], $this->pUser ? $this->pUser['Email'] : null);
        $Mail2->FromName = GetValue($pData['VisitorData']['FullName'], $this->pUser ? $this->pUser['FullName'] : null);
        $Mail2->To = $this->Settings['sEmail'];
        $Mail2->Subject = $this->Settings['sSiteName'] . ' :: ' . $_['_NewPayment'];
        $Mail2->Body = $body;
        $Mail2->Send();
    }

    protected function Validation() {
        $jData = $this->GetJsonData();
        $Data = $jData['VisitorData'];
        $Valid = array(
            new ErrorField('FullName', 'FullName', $Data['FullName'], true, NULL, 50, 3),
            new ErrorField('Mobile', 'Mobile', $Data['Mobile'], true),
        );

        return $this->DoValidation($Valid);
    }

    public function checkvalidation() {
        $d = $this->Validation();
        if ($d) {
            $this->JsonParser->error = $d;
            $this->JsonParser->success = false;
        } else {
            $this->JsonParser->data = true;
        }
        $this->JsonParser->Response();
    }

    public function error() {
        $Data = $this->GetJsonData();
        $Data['Amount'] = $Data['UDF1'];
        $this->Model->AddPayment($Data);
        $this->JsonParser->data = true;
        $this->JsonParser->Response();
    }

}
