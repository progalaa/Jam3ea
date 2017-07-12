<?php

class MailingListController extends ControllerAdmin implements IControllerAdmin {

    protected function GetData() {
        $remSlashes = array('Message');
        return $this->FilterPost($remSlashes);
    }

    protected function AfterAdd() {
        if (Request::IsPost()) {
            $Data = $_POST;
            $Subject = $Data['Subject'];
            $Message = $Data['Message'];
            $Mails = $this->Model->GetAllEmails();
            foreach ($Mails as $i) {
                $Mail = new Mail();
                $Mail->Subject = $Subject . ' From: ' . $this->Settings['sSiteName'];
                $Mail->Body = $Message;
                $Mail->From = $this->Settings['sEmail'];
                $Mail->FromName = $this->Settings['sSiteName'];
                $Mail->To = $i;
                $Mail->Send();
            }
        }
    }

    protected function Validation() {
        $Data = $this->GetData();
        $Valid = array(
            new ErrorField('Subject', 'Subject', $Data['Subject'], true),
            new ErrorField('Message', 'Message', $Data['Message'], true)
        );

        return $this->DoValidation($Valid);
    }

}
