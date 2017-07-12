<?php

class MailingList_UserController extends ControllerAdmin implements IControllerAdmin {

    protected function Validation() {
        $Data = $this->GetData();
        $Valid = array(
            new ErrorField('Email', 'Email', $Data['Email'], true, FieldType::Email, 50, 3, $this->Model->CheckEmail($Data['Email'], intval(GetValue($this->Data['pParameter'], 0))), false),
            new ErrorField('State', 'State', $Data['State'], true, FieldType::Bool)
        );

        return $this->DoValidation($Valid);
    }

}