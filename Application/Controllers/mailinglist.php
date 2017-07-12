<?php

/**
 * MailingList
 */
class mailinglistController extends Controller {

    public function index() {
    }

    public function add() {
        if (Request::IsPost()) {
            $this->API->url = GetAPIUrl('mailinglist/add');
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
        if (Request::IsPost()) {
            $this->API->url = GetAPIUrl('mailinglist/delete');
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
