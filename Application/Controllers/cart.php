<?php

/**
 * Static Pages
 */
class cartController extends Controller {

    public function index() {
        $d = &$this->Data;

        $this->API->url = GetAPIUrl('cart');
        $this->API->method = ActionMethod::POST;
        if ($this->Authentication(false)) {
            $this->API->SendAuth();
        } else {
            Session::Init();
            $cartItems = Session::Get('cart');
            $this->API->data = array('CartItems' => $cartItems);
        }
        $Data = $this->API->Process();

        $d['dTitle'] = $this->_['_Cart'];
        $d['dResults'] = $Data['data'];
        $d['dFullWidth'] = true;

        $this->View->Render();
    }

    public function history() {
        if ($this->Authentication()) {
            $d = &$this->Data;

            $this->API->url = GetAPIUrl('cart/history');
            $this->API->method = ActionMethod::POST;
            $this->API->SendAuth();
            $Data = $this->API->Process();

            $d['dTitle'] = $this->_['_Cart_History'];
            $d['dResults'] = $Data['data'];
            $d['dFullWidth'] = true;

            $this->View->Render();
        }
    }

    public function items_count() {
        if ($this->Authentication(false)) {
            $this->API->url = GetAPIUrl('cart/items_count');
            $this->API->method = ActionMethod::POST;
            $this->API->SendAuth();
            $Data = $this->API->Process();
            EchoExit(intval($Data['data']));
        } else {
            Session::Init();
            EchoExit(count(Session::Get('cart')));
        }
    }

    public function add() {
        if (Request::IsPost()) {
            if ($this->Authentication(false)) {
                $this->API->url = GetAPIUrl('cart/add');
                $this->API->method = ActionMethod::POST;
                $this->API->data = $this->GetData();
                $this->API->SendAuth();
                $Data = $this->API->Process();
                if ($Data['success']) {
                    $this->getCart(true);
                    EchoExit($Data['data']);
                } else {
                    EchoError($Data['error']);
                }
            } else {
                Session::Init();
                $CartItems = Session::Get('cart')? : array();
                $Data = $this->GetData();
                $Data['Quantity'] = intval(GetValue($Data['Quantity'], 1));
                $Data['Product'] = intval(GetValue($Data['Product'], 1));
                $Prods = array_column($CartItems, 'ID');
                if ($Data['Quantity'] > 0 && $Data['Product'] > 0) {
                    if (!$Prods || !in_array($Data['Product'], $Prods)) {
                        $i = array(
                            'ID' => $Data['Product'],
                            'Quantity' => $Data['Quantity'],
                            'CreatedDate' => GetDateValue()
                        );
                        $CartItems[] = $i;
                        Session::Set('cart', $CartItems);
                    }
                    EchoExit($this->_['_Cart_Added']);
                } else {
                    EchoError($this->_['_ErrorUnexpected']);
                }
            }
        }
    }

    public function delete() {
        if (Request::IsPost()) {
            if ($this->Authentication(false)) {
                $this->API->url = GetAPIUrl('cart/delete');
                $this->API->method = ActionMethod::POST;
                $this->API->data = $this->GetData();
                $this->API->SendAuth();
                $Data = $this->API->Process();
                if ($Data['success']) {
                    $this->getCart(true);
                    EchoExit($Data['data']);
                } else {
                    EchoError($Data['error']);
                }
            } else {
                Session::Init();
                $CartItems = Session::Get('cart');
                $NewCart = array();
                $Data = $this->GetData();
                $ID = $Data['Product'];
                if ($CartItems) {
                    foreach ($CartItems as $i) {
                        if ($i['ID'] != $ID) {
                            $NewCart[] = $i;
                        }
                    }
                    Session::Set('cart', $NewCart);
                    EchoExit($this->_['_Cart_Deleted']);
                }
            }
        }
    }

    public function update_quantity() {
        if (Request::IsPost()) {
            if ($this->Authentication(false)) {
                $this->API->url = GetAPIUrl('cart/update_quantity');
                $this->API->method = ActionMethod::POST;
                $this->API->data = $this->GetData();
                $this->API->SendAuth();
                $Data = $this->API->Process();
                if ($Data['success']) {
                    EchoExit($Data['data']);
                } else {
                    EchoError($Data['error']);
                }
            } else {
                Session::Init();
                $CartItems = Session::Get('cart');
                $NewCart = array();
                $Data = $this->GetData();
                $ID = $Data['Product'];
                $Quantity = $Data['Quantity'];
                if ($CartItems && $ID > 0 && $Quantity > 0) {
                    foreach ($CartItems as $i) {
                        if ($i['ID'] == $ID) {
                            $i['Quantity'] = $Quantity;
                        }
                        $NewCart[] = $i;
                    }
                    Session::Set('cart', $NewCart);
                    EchoExit($this->_['_Cart_Quantity_Updated']);
                } else {
                    EchoError($this->_['_ErrorUnexpected']);
                }
            }
        }
    }

}
