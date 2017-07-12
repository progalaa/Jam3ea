<?php

interface IController {

    public function Index();
}

interface ITheme {

    public function GetSideCategories();

    public function GetPages();

    public function GetFooterLinks();

    public function GetMainMenu();

    public function GetMostViewed();

    public function GetBanners();
}

class Controller {

    public $View;
    public $Model;
    public $_ = array();
    public $API;
    public $Data = array();

    public function __construct() {
        $Registry = Registry::GetInstance();
        $this->_ = &$Registry->_;
        $this->API = &$Registry->API;
        $this->Data = &$Registry->Data;
        $this->Image = &$Registry->Image;
        $this->Lang = &$Registry->Lang;
        $this->Settings = &$Registry->Settings;
        $this->View = new View();
    }

    public function LoadModel($Name) {
        if (IncludeFileOnce(APP_MODELS . $Name . '.php', true)) {
            $ModelName = $Name . 'Model';
            return new $ModelName;
        }
    }

    protected function Encrypt($Key) {
        return sha1(md5($Key));
    }

    protected function Filter($_Value, $htmlDecode = false) {
        if (is_array($_Value)) {
            return $_Value;
        }
        if (is_object($_Value)) {
            return $_Value;
        }
        if ($htmlDecode == true) {
            return addslashes($_Value);
        }
        return addslashes(strip_tags($_Value));
    }

    protected function FilterPost(array &$remSlashes = null) {
        return $this->FilterData($_POST);
    }

    protected function FilterData($Data, array &$remSlashes = null) {
        $data = array();
        if (count($Data) > 0) {
            foreach ($Data as $key => $value) {
                if ($remSlashes) {
                    for ($i = 0; $i < count($remSlashes); $i++) {
                        if (in_array($key, $remSlashes)) {
                            $data[$key] = $this->Filter($value, true);
                        } else {
                            $data[$key] = $this->Filter($value);
                        }
                    }
                } else {
                    $data[$key] = $this->Filter($value);
                }
            }
        }
        return $data;
    }

    protected function LoadDropDown($Data, $ID = 'ID', $Value = 'Name', $Choose = '_Choose') {
        $DataList = $Data;
        $Output = '<option value="0">' . $this->_[$Choose] . '</option>';
        foreach ($DataList as $I) {
            $Output .= '<option value="' . $I[$ID] . '">' . $I[$Value] . '</option>';
        }
        return $Output;
    }

    protected function LoadDropDownAttr($Data, $Choose = '_Choose') {
        $DataList = $Data;
        $Output = '<option value="0">' . $this->_[$Choose] . '</option>';
        foreach ($DataList as $k => $v) {
            $Output .= '<option value="' . $k . '">' . $v . '</option>';
        }
        return $Output;
    }

    protected function Authentication($redirect = true) {
        Session::Init();
        $Data = Session::Get(_UD);

        if ($Data && GetValue($Data['AuthUserName']) && GetValue($Data['AuthPassword'])) {
            return true;
        }
        $pData = $this->GetData();

        if (GetValue($_POST['AuthUserName']) && GetValue($_POST['AuthPassword']) && !in_array($this->Data['pAction'], array('signin', 'register'))) {
            $_POST['UserName'] = $_POST['AuthUserName'];
            $_POST['Password'] = $_POST['AuthPassword'];
            $this->setAuth();
        }
        if ($redirect) {
            if (Request::IsAjax()) {
                EchoJson(array('Redirect' => GetRewriteUrl('profile/signin')));
            }
            Redirect(GetRewriteUrl('profile/signin'));
        }
        return false;
    }

    protected function setAuth() {
        Session::Init();
        $this->API->url = GetAPIUrl('profile/signin');
        $this->API->method = ActionMethod::POST;
        $cartItems = Session::Get('cart');
        $pData = array('CartItems' => $cartItems);
        $this->API->data = array_merge($pData, $this->GetData());
        $Data = $this->API->Process();
        if ($Data['success']) {
            $Data['data']['AuthUserName'] = $this->API->data['UserName'];
            $Data['data']['AuthPassword'] = $this->API->data['Password'];

            $this->Data['pUser'] = $Data['data'];

            $Cart = $this->getCart(true, true);

            $Data['data']['CartHasItems'] = $Cart ? true : false;
            Session::Init();
            Session::Set(_UD, $Data['data']);
        } else {
            $this->Data['dError'] = $Data['error'];
        }
    }

    protected function getCart($update = false, $authed = false) {
        Session::Init();
        $Cart = Session::Get('cart');
        if (!$Cart || $update == true) {

            $this->API->url = GetAPIUrl('cart');
            $this->API->method = ActionMethod::POST;
            if ($authed == true || $this->Authentication(false)) {
                $this->API->SendAuth($this->Data['pUser']);
            } else {
                Session::Init();
                $cartItems = Session::Get('cart');
                $this->API->data = array('CartItems' => $cartItems);
            }
            $dCart = $this->API->Process();

            $Cart = $dCart['data'];
        }
        Session::Set('cart', $Cart);
        return $Cart;
    }

    public function GetCategories() {
//Category
        $Category = Cache::Get('Category.' . $this->Lang['Code']);
        if (!$Category) {
            $this->API->url = GetAPIUrl('general/categories');
            $Category = $this->API->Process();
            Cache::Set('Category.' . $this->Lang['Code'], $Category);
        }
        return $Category['data'];
    }

    public function BuildMenu() {
        $MenusData = null; //Cache::Get('Menus.' . $this->Lang['Code']);
        if (!$MenusData) {
            $this->API->url = GetAPIUrl('general/menus');
            $MenusData = $this->API->Process();
            Cache::Set('Menus.' . $this->Lang['Code'], $MenusData);
        }
        return $MenusData['data'];
    }

    protected function SetMenuLink($Link, $Alias = '') {
        if (strstr($Link, 'http://') || strstr($Link, 'javascript:void(0)')) {
            return $Link;
        }
        return GetRewriteUrl($Link, $Alias);
    }

    protected function GetData() {
        return $this->FilterData($_POST);
    }

    protected function DoValidation(array $Data) {
        $json = array();
        $e = array();
        $_ = &$this->_;
        foreach ($Data as $i) {
            $ID = $i->ID;
            $Name = $i->Name ? GetValue($_[$i->Name], $i->Name) : null;
            $Value = $i->Value;
            $Type = $i->Type;
            $Array = $i->Array;
            $InArray = $i->InArray;
            $Equal = $i->Equal;
            if ($ID && $Name) {
                if ($i->Required == true && empty($Value) && $Type != FieldType::Bool) {
                    $e[$ID] = str_format($_['_Error_Required'], $_['_' . $Name]);
                } elseif ($i->MaxLength > 0 && GetLength($Value) > $i->MaxLength) {
                    $e[$ID] = str_format($_['_Error_Max_Length'], $_['_' . $Name], $i->MaxLength);
                } elseif ($i->MinLength > 0 && GetLength($Value) < $i->MinLength) {
                    $e[$ID] = str_format($_['_Error_Min_Length'], $_['_' . $Name], $i->MinLength);
                } elseif ($i->Required == true && !empty($Value) && (($Type == FieldType::Integer && !is_numeric($Value)) ||
                        ($Type == FieldType::Email && !(filter_var($Value, FILTER_VALIDATE_EMAIL))))) {
                    $e[$ID] = str_format($_['_Error_Incorrect'], $_['_' . $Name]);
                } elseif ($Equal && $Value != $Equal) {
                    $e[$ID] = str_format($_['_Error_Is_Not_Matched'], $_['_' . $Name]);
                } elseif ($Value && ($Array != null || $Array == false)) {
                    if ($InArray == false && $Array == true) {
                        $e[$ID] = str_format($_['_Error_Is_Found'], $_['_' . $Name]);
                    } elseif ($InArray == true && $Array == false) {
                        $e[$ID] = str_format($_['_Error_Is_Not_Found'], $_['_' . $Name]);
                    }
                }
            }
        }
        return $e;
    }

    function EchoErrors($json) {
        if (Request::IsAjax()) {
            EchoJson(array('Error' => $json));
        } else {
            if ($json && is_array($json)) {
                foreach ($json as $k => $v) {
                    $this->Data['err' . $k] = $v;
                }
            }
        }
    }

    protected function GetBannerPositions() {
        return array(
            array(// row #1
                'ID' => 1,
                'BannerPositionAlias' => 'Header',
                'Height' => 90,
                'Width' => 728,
            ),
            array(// row #2
                'ID' => 2,
                'BannerPositionAlias' => 'Left1',
                'Height' => 370,
                'Width' => 330,
            ),
        );
    }

    protected function GetJsonData() {
        if (isset($_POST['post_data'])) {
            $Data = unserialize($_POST['post_data']);
            return $this->FilterData($Data);
        } else {
            $json = file_get_contents('php://input');
            return $this->FilterData(json_decode($json, true));
        }
    }

}
