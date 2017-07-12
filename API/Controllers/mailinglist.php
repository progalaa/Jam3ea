<?php

/**
 *
 * @author MrAbdelrahman
 */
class mailinglistController extends ControllerAPI {

    public function index() {

    }

    public function add() {
        if (Request::IsPost()) {
            Session::Init();
            $Data = $this->GetJsonData();
            $_ = &$this->_;
            if (!filter_var($Data['Email'], FILTER_VALIDATE_EMAIL)) {
                $this->JsonParser->error = $_['_InCurrect_Email'];
                $this->JsonParser->success = false;
                $this->JsonParser->Response();
            }
            if ($this->Model->CheckEmail($Data['Email'])) {
                $this->JsonParser->error = $_['_MailingList_Email_Founded'];
                $this->JsonParser->success = false;
                $this->JsonParser->Response();
            }
            $ID = $this->Model->Add($Data['Email']);
            if ($ID > 0) {
                $this->JsonParser->data = $_['_MailingList_Email_Added'];
            } else {
                $this->JsonParser->error = $_['_ErrorUnexpected'];
                $this->JsonParser->success = false;
            }

            $this->JsonParser->Response();
        }
    }

    public function delete() {
        if (Request::IsPost()) {
            $Data = $this->GetJsonData();
            $_ = $this->_;
            $this->Model->Delete($Data['Email']);
            $this->JsonParser->data = $_['_MailingList_Email_Deleted'];
            $this->JsonParser->Response();
        }
    }

}
