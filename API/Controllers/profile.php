<?php

/**
 *
 * @author abduo
 */
class profileController extends ControllerAPI {

    public function index() {
        if ($this->Authentication()) {

        }
    }

    public function userdata() {
        if ($this->Authentication()) {
            $this->setJsonParser($this->UpdateUserData(), false);
        }
    }

    public function signin() {
        Session::Init();
        if (Request::IsPost()) {
            $_ = &$this->_;
            $Data = $this->GetJsonData();
            $Pass = $Data['Password'];
            $Data['Password'] = $this->Filter(Encrypt($Pass));
            $i = $this->Model->SignIn($Data);
            $err = null;
            if (!$i) {
                $err = $_['_User_Login_Error'];
            } elseif ($i['State'] == 0) {
                $err = $_['_User_Admin_DeActive'];
            } elseif ($i['IsActive'] == 0) {
                $err = $_['_User_DeActive'];
            }
            if ($err) {
                $this->JsonParser->error = $err;
                $this->JsonParser->success = false;
            } else {
                $i['AuthUserName'] = $Data['UserName'];
                $i['AuthPassword'] = $Pass;
                $this->Data['pUser'] = $i;
                Session::Set(_UD, $i);
                $this->add_products_to_cart();
                unset($i['Password']);
                $i['CartHasItems'] = GetValue($Data['CartItems']) ? true : false;
                $this->JsonParser->data = $i;
            }
            $this->JsonParser->Response();
        }
    }

    public function register() {
        if (Request::IsPost()) {
            $d = $this->RegisterValidation();
            if ($d) {
                $this->JsonParser->error = $d;
                $this->JsonParser->success = false;
            } else {
                $Data = $this->GetJsonData();
                $Pass = $Data['Password'];
                $Data['Password'] = $this->Filter($this->Encrypt($Pass));
                $Data['ID'] = $this->Model->Add($Data);
                $this->Model->Add_Address($Data);
                if ($Data['Email']) {
                    $this->MailNewUser();
                }
                $User = $this->Model->GetByID($Data['ID']);
                $User['AuthUserName'] = $Data['UserName'];
                $User['AuthPassword'] = $Pass;
                $this->Data['pUser'] = $User;
                Session::Set(_UD, $User);
                $this->add_products_to_cart();
                unset($User['Password']);
                $User['CartHasItems'] = GetValue($Data['CartItems']) ? true : false;
                $this->JsonParser->data = $User;
            }
            $this->JsonParser->Response();
        }
    }

    private function MailNewUser() {
        $Data = $this->GetJsonData();
        $Mail = new Mail();
        $Mail->From = $this->Settings['sEmail'];
        $Mail->FromName = $this->Settings['sSiteName'];
        $Mail->To = $Data['Email'];
        $Mail->Subject = $this->Settings['sSiteName'];
        $Mail->Body = str_format($this->_['_RegMsg'], $Data['FullName'], $Data['UserName']);
        $Mail->Send();
    }

    protected function RegisterValidation() {
        $Data = $this->GetJsonData();
        $Valid = array(
            new ErrorField('FullName', 'FullName', $Data['FullName'], true, NULL, 50, 3),
            new ErrorField('UserName', 'UserName', $Data['UserName'], true, NULL, 20, 3, $this->Model->CheckUserName($Data['UserName']), false),
            new ErrorField('Email', 'Email', $Data['Email'], false, FieldType::Email, 50, 0, $this->Model->CheckEmail($Data['Email']), false),
            new ErrorField('Password', 'Password', $Data['Password'], true, NULL, 50, 3),
            new ErrorField('Mobile', 'Mobile', $Data['Mobile'], true),
        );

        return $this->DoValidation($Valid);
    }

    private function add_products_to_cart() {
        $Data = $this->GetJsonData();
        if (isset($Data['CartItems'])) {
            $CartItems = $Data['CartItems'];
            $CartModel = $this->LoadModel('cart');
            $CartModel->db = $this->Model->db;
            $this->Model->pUser = $CartModel->pUser = $this->Data['pUser'];
            $IDs = $CartModel->GetAllIDs();
            if ($CartItems) {
                foreach ($CartItems as $i) {
                    if (!in_array($i['ID'], $IDs)) {
                        $i['Product'] = $i['ID'];
                        $CartModel->Add($i);
                    }
                }
            }
        }
    }

    public function changepassword() {
        if ($this->Authentication()) {
            if (Request::IsPost()) {
                $json = $this->ChangePasswordValidation();
                if ($json) {
                    $this->JsonParser->error = $json;
                    $this->JsonParser->success = false;
                } else {
                    $Data = $this->GetPasswordData();
                    $this->Model->ChangePassword($Data);
                    $this->pUser['AuthPassword'] = $Data['NewPassword'];
                    $this->UpdateUserData();
                    $this->JsonParser->data = $this->_['_Updated_Password'];
                }
                $this->JsonParser->Response();
            }
        }
    }

    private function GetPasswordData() {
        $Data = $this->GetJsonData();
        $Data["ID"] = $this->pUser['ID'];
        $Data["OldPassword"] = $this->Filter($this->Encrypt($Data["OldPassword"]));
        $Data["Password"] = $this->Filter($this->Encrypt($Data["NewPassword"]));
        return $Data;
    }

    private function ChangePasswordValidation() {
        $Data = $this->GetJsonData();
        $Valid = array(
            new ErrorField('OldPassword', 'OldPassword', $Data['OldPassword'], true, FieldType::String, 0, 0, null, null, $this->pUser['AuthPassword']),
            new ErrorField('NewPassword', 'NewPassword', $Data['NewPassword'], true, FieldType::String, 12, 4),
            new ErrorField('rNewPassword', 'rNewPassword', $Data['rNewPassword'], true, FieldType::String, 0, 0, null, null, $Data['NewPassword'])
        );
        return $this->DoValidation($Valid);
    }

    public function forgotpassword() {
        if (Request::IsPost()) {
            $this->_ = $this->LoadLangFile();
            $json = $this->ForgotPasswordValidation();
            if ($json) {
                $this->JsonParser->error = $json;
                $this->JsonParser->success = false;
            } else {
                $np = GenerateWord(4);
                $Data = $this->GetJsonData();
                $Data['Password'] = Encrypt($np);
                $this->Model->ForgotPassword($Data);

                $Mail = new Mail();
                $Mail->To = $Data['Email'];
                $Mail->Subject = $this->_['_Restore_Password'];
                $Mail->Body = $this->_['_New_Password'] . $np;
                $Mail->Send();
                $this->JsonParser->data = $this->_['_Restored_Password'];
            }
            $this->JsonParser->Response();
        }
    }

    private function ForgotPasswordValidation() {
        $Data = $this->GetJsonData();
        $Valid = array(
            new ErrorField('Email', 'Email', $Data['Email'], true, FieldType::Email, 50, 3, $this->Model->CheckEmail($Data['Email']), true)
        );
        $Error = $this->DoValidation($Valid);
        return $Error ? $Error['Email'] : null;
    }

    private function UpdateUserData() {
        $UD = $this->pUser;
        $UD['UserName'] = $UD['AuthUserName'];
        $UD['Password'] = Encrypt($UD['AuthPassword']);
        $Data = $this->Model->SignIn($UD);
        Session::Init();
        Session::Set(_UD, $Data);
        unset($Data['Password']);
        return $Data;
    }

    public function changeuserdata() {
        if ($this->Authentication()) {
            if (Request::IsPost()) {
                $json = $this->ChangeUserDataValidation();
                if ($json) {
                    $this->JsonParser->error = $json;
                    $this->JsonParser->success = false;
                } else {
                    $Data = $this->GetJsonData();
                    $this->Model->ChangeData($Data);

                    $UD = $this->pUser;
                    $UD['AuthUserName'] = $Data['UserName'];
                    $UD['AuthPassword'] = $UD['AuthPassword'];

                    $this->UpdateUserData();
                    $this->JsonParser->data = $this->_['_Updated_User_Data'];
                }
                $this->JsonParser->Response();
            }
        }
    }

    private function ChangeUserDataValidation() {
        $Data = $this->GetJsonData();
        $Valid = array(
            new ErrorField('FullName', 'FullName', $Data['FullName'], true, NULL, 50, 3),
            new ErrorField('UserName', 'UserName', $Data['UserName'], true, NULL, 20, 3, $this->Model->CheckUserName($Data['UserName']), false),
            new ErrorField('Email', 'Email', $Data['Email'], true, FieldType::Email, 50, 3, $this->Model->CheckEmail($Data['Email']), false),
            new ErrorField('Mobile', 'Mobile', $Data['Mobile'], true),
        );
        return $this->DoValidation($Valid);
    }

    public function signout() {
        Session::Init();
        Session::Destroy();
        $_ = $this->LoadLangFile();
        EchoExit($_['_SignOut']);
    }

    public function notifications() {
        if ($this->Authentication()) {
            EchoJson($this->Model->GetNotifications());
        }
    }

    public function read_notification($id) {
        if ($this->Authentication()) {
            $d = array();
            $ID = intval($id);
            if ($ID > 0) {
                $r = $this->Model->ReadNotification(intval($ID));
                if ($r > 0) {
                    $d['Result'] = true;
                } else {
                    $d['Result'] = false;
                }
            }
            EchoJson($d);
        }
    }

    public function delete_notification() {
        if ($this->Authentication()) {
            $d = array();
            $Data = $this->GetJsonData();
            $ID = intval($Data['ID']);
            if ($ID > 0) {
                $r = $this->Model->DeleteNotification(intval($ID));
                if ($r > 0) {
                    $d['Result'] = true;
                } else {
                    $d['Result'] = false;
                }
            }
            EchoJson($d);
        }
    }

    public function delete_notifications() {
        if ($this->Authentication()) {
            $d = array();
            $r = $this->Model->DeleteNotifications();
            if ($r > 0) {
                $d['Result'] = true;
            } else {
                $d['Result'] = false;
            }
            EchoJson($d);
        }
    }

}