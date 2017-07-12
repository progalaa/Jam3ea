<?php

/**
 *
 * @author abduo
 */
class profileController extends Controller {

    public function __construct() {
        parent::__construct();
        $this->Data['dFullWidth'] = true;
    }

    public function index() {
        if ($this->Authentication()) {
            $this->Data['dTitle'] = $this->_['_Profile'];
            $this->View->Render();
        }
    }

    public function signin() {
        Session::Init();
        if (Session::Get(_UD)) {
            Redirect(BASE_URL);
        } else {
            $_ = &$this->_;
            $this->Data['dTitle'] = $_['_SignIn'];
            if (Request::IsPost()) {
                $this->setAuth();
                if ($this->Data['pUser']) {
                    Redirect(BASE_URL);
                }
            }
            $this->View->Render();
        }
    }

    public function register() {
        if ($this->Authentication(false)) {
            Redirect(BASE_URL);
        } else {
            if (Request::IsPost()) {
                $json = array();
                $this->API->url = GetAPIUrl('profile/register');
                $this->API->method = ActionMethod::POST;
                Session::Init();
                $cartItems = Session::Get('cart');
                $pData = array('CartItems' => $cartItems);
                $this->API->data = array_merge($pData, $this->GetData());
                $Data = $this->API->Process();
                if ($Data['success']) {
                    $Data['data']['AuthUserName'] = $this->API->data['UserName'];
                    $Data['data']['AuthPassword'] = $this->API->data['Password'];
                    Session::Init();
                    Session::Set(_UD, $Data['data']);
                    $json['Redirect'] = GetRewriteUrl('profile/registersuccessfully');
                    $json['IsResult'] = true;
                    EchoJson($json);
                } else {
                    $this->EchoErrors($Data['error']);
                }
            }
            $this->Data['dTitle'] = $this->_['_Register'];
            $this->View->Render();
        }
    }

    public function registersuccessfully() {
        $this->Data['dTitle'] = $this->_['_Register'];
        $this->View->Render();
    }

    public function changeuserdata() {
        if ($this->Authentication()) {
            if (Request::IsPost()) {
                $json = array();
                $this->API->url = GetAPIUrl('profile/changeuserdata');
                $this->API->method = ActionMethod::POST;
                $this->API->data = $this->GetData();
                $this->API->SendAuth();
                $Data = $this->API->Process();
                if ($Data['success']) {
                    $json['Msg'] = $Data['data'];
                    $json['Redirect'] = GetRewriteUrl('profile');

                    $UD = $this->Data['pUser'];
                    $UD['UserName'] = $this->API->data['UserName'];
                    $UD['AuthUserName'] = $this->API->data['UserName'];
                    Session::Init();
                    Session::Set(_UD, $UD);

                    EchoJson($json);
                } else {
                    $this->EchoErrors($Data['error']);
                }
            }
            $this->Data['dTitle'] = $this->_['_ChangeData'];
            $this->View->Render();
        }
    }

    public function changepassword() {
        if ($this->Authentication()) {
            if (Request::IsPost()) {
                $json = array();
                $this->API->url = GetAPIUrl('profile/changepassword');
                $this->API->method = ActionMethod::POST;
                $this->API->data = $this->GetData();
                $this->API->SendAuth();
                $Data = $this->API->Process();
                if ($Data['success']) {
                    $json['Msg'] = $Data['data'];
                    $json['Redirect'] = GetRewriteUrl('profile');

                    $UD = $this->Data['pUser'];
                    $UD['AuthPassword'] = $this->API->data['NewPassword'];
                    Session::Init();
                    Session::Set(_UD, $UD);

                    EchoJson($json);
                } else {
                    $this->EchoErrors($Data['error']);
                }
            }
            $this->Data['dTitle'] = $this->_['_ChangePassword'];
            $this->View->Render();
        }
    }

    public function forgotpassword() {
        if (Request::IsPost()) {
            $json = array();
            $this->API->url = GetAPIUrl('profile/forgotpassword');
            $this->API->method = ActionMethod::POST;
            $this->API->data = $this->GetData();
            $this->API->SendAuth();
            $Data = $this->API->Process();
            if ($Data['success']) {
                $json['Msg'] = $Data['data'];
                $json['Redirect'] = GetRewriteUrl('profile');
                EchoJson($json);
            } else {
                $this->EchoErrors($Data['error']);
            }
        }
        $this->Data['dTitle'] = $this->_['_Forgot_Password'];
        $this->View->Render();
    }

    private function UpdateUserData() {
        Session::Init();


        $this->API->url = GetAPIUrl('profile/userdata');
        $this->API->method = ActionMethod::POST;
        $this->API->SendAuth();
        $Data = $this->API->Process();

        Session::Set(_UD, $Data['data']);
        $this->Data['pUser'] = $Data['data'];
        return $this->Data['pUser'];
    }

    public function signout() {
        Session::Init();
        Session::Destroy();
        $this->API->url = GetAPIUrl('profile/signout');
        $this->API->Process();
        Redirect(BASE_URL);
    }

}
