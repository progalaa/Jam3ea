<?php

/**
 *
 * @author MrAbdelrahman
 */
class cartController extends ControllerAPI {

    public function index() {
        $Data = array();
        if ($this->Authentication(false)) {
            $Data = $this->Model->GetAll();
        } else {
            $Items = $this->GetJsonData();
            if (isset($Items['CartItems'])) {
                $Data = $this->Model->GetAllByIDs($Items['CartItems']);
            }
        }
        $d = array();
        foreach ($Data as $i) {
            if ($i['State']) {
                $i['SliderPictures'] = (unserialize($i['SliderPictures']));
                if (!isset($i['cQuantity'])) {
                    foreach ($Items['CartItems'] as $td) {
                        if ($td['ID'] == $i['ID']) {
                            $i['cQuantity'] = $td['Quantity'];
                            break;
                        }
                    }
                }
                $d[] = $i;
            }
        }
        $this->setJsonParser($d, false);
    }

    public function items_count() {
        if ($this->Authentication()) {
            $this->setJsonParser($this->Model->GetItemsCount(), false);
        }
    }

    public function history() {
        if ($this->Authentication()) {
            $d = array();
            $Data = $this->Model->GetHistory();
            foreach ($Data as $i) {
                $i['State'] = $this->_['_Order_State_List'][$i['State']];
                $d[] = $i;
            }
            $this->setJsonParser($d, false);
        }
    }

    public function add() {
        if ($this->Authentication()) {
            if (Request::IsPost()) {
                Session::Init();
                $Data = $this->GetJsonData();
                $Data['Quantity'] = intval(GetValue($Data['Quantity'], 1));
                $Data['Product'] = intval(GetValue($Data['Product'], 1));
                $_ = &$this->_;
                if ($Data['Quantity'] > 0 && $Data['Product'] > 0) {
                    $CartItems = $this->Model->GetAllIDs();
                    if ($CartItems) {
                        foreach ($CartItems as $i) {
                            if ($Data['Product'] == $i) {
                                $this->JsonParser->error = $_['_Cart_Is_Found'];
                                $this->JsonParser->success = false;
                                $this->JsonParser->Response();
                            }
                        }
                    }
                    $ID = $this->Model->Add($Data);
                    if (!$ID) {
                        $this->JsonParser->error = $_['_ErrorUnexpected'];
                        $this->JsonParser->success = false;
                    } else {
                        $this->JsonParser->data = $_['_Cart_Added'];
                    }
                } else {
                    $this->JsonParser->error = $_['_ErrorUnexpected'];
                    $this->JsonParser->success = false;
                }

                $this->JsonParser->Response();
            }
        }
    }

    public function update_quantity() {
        if ($this->Authentication()) {
            if (Request::IsPost()) {
                Session::Init();
                $CartItems = $this->Model->GetAll();
                $Data = $this->GetJsonData();
                $Data['Quantity'] = intval(GetValue($Data['Quantity'], 1));
                $Data['Product'] = intval(GetValue($Data['Product'], 1));
                $_ = &$this->_;
                if ($CartItems && $Data['Quantity'] > 0 && $Data['Product'] > 0) {
                    foreach ($CartItems as $i) {
                        if ($Data['Product'] == $i['ID']) {
                            $Prod_Quantity = $this->Model->GetQuantity($i['ID']);
                            if ($Data['Quantity'] > $i['Quantity']) {
                                $this->JsonParser->error = $_['_Cart_Quantity_Out_Stock'];
                                $this->JsonParser->success = false;
                                $this->JsonParser->Response();
                            }
                            $this->Model->EditQuantity($Data);
                            $this->JsonParser->data = $_['_Cart_Quantity_Updated'];
                            $this->JsonParser->Response();
                        }
                    }
                }
                $this->JsonParser->error = $_['_ErrorUnexpected'];
                $this->JsonParser->success = false;
                $this->JsonParser->Response();
            }
        }
    }

    public function delete() {
        if ($this->Authentication()) {
            if (Request::IsPost()) {
                $Data = $this->GetJsonData();
                $_ = &$this->_;
                $this->Model->Delete($Data['Product']);
                $this->JsonParser->data = $_['_Cart_Deleted'];
                $this->JsonParser->Response();
            }
        }
    }

}
