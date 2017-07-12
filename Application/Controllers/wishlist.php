<?php

/**
 * Static Pages
 */
class wishlistController extends Controller {

    public function index() {
        if ($this->Authentication()) {
            $d = &$this->Data;

            $this->API->url = GetAPIUrl('wishlist');
            $this->API->method = ActionMethod::POST;
            $this->API->SendAuth();
            $Data = $this->API->Process();

            $d['dTitle'] = $this->_['_Wishlist'];
            $d['dResults'] = $Data['data'];
            $d['dFullWidth'] = true;

            $this->View->Render();
        }
    }

    public function items_count() {
        if ($this->Authentication()) {
            $this->API->url = GetAPIUrl('wishlist/items_count');
            $this->API->method = ActionMethod::POST;
            $this->API->SendAuth();
            $Data = $this->API->Process();
            EchoExit(intval($Data));
        }
    }

    public function add() {
        if ($this->Authentication() && Request::IsPost()) {
            $this->API->url = GetAPIUrl('wishlist/add');
            $this->API->method = ActionMethod::POST;
            $this->API->data = $this->GetData();
            $this->API->SendAuth();
            $Data = $this->API->Process();
            if ($Data['success']) {
                EchoExit($Data['data']);
            } else {
                EchoError($Data['error']);
            }
        }
    }

    public function delete() {
        if ($this->Authentication() && Request::IsPost()) {
            $this->API->url = GetAPIUrl('wishlist/delete');
            $this->API->method = ActionMethod::POST;
            $this->API->data = $this->GetData();
            $this->API->SendAuth();
            $Data = $this->API->Process();
            if ($Data['success']) {
                EchoExit($Data['data']);
            } else {
                EchoError($Data['error']);
            }
        }
    }

}
