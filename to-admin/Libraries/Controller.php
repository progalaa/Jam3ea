<?php

interface IControllerAdmin {

}

abstract class ControllerAdmin extends Controller {

    public $View;
    public $Model;
    public $_ = array();
    public $Data = array();

    public function __construct() {
        $Registry = Registry::GetInstance();
        $this->_ = &$Registry->_;
        $this->Data = &$Registry->Data;
        $this->Image = &$Registry->Image;
        $this->Settings = &$Registry->Settings;
        $this->Data['dAdminPages'] = $this->AdminPages();
        $this->Data['dTitle'] = $this->getTitle();
        $this->View = new ViewAdmin();
    }

    public function LoadModel($Name) {
        if (IncludeFileOnce(ADM_MODELS . $Name . '.php', true)) {
            $ModelName = $Name . 'Model';
            return new $ModelName;
        }
    }

    protected function GetIDs($Str) {
        $IDs = array();
        $StrID = isset($Str) ? explode(',', trim($Str, ',')) : NULL;
        for ($idx = 0; $idx < count($StrID); $idx++) {
            $IDs[$idx] = $this->Filter($StrID[$idx]);
        }
        return $IDs;
    }

    protected function Authentication($redirect = true) {
        Session::Init();
        $Model = $this->Model;
        $Data = array();
        $User = Session::Get('AdminUser');
        if (isset($User)) {
            $Data['AdminUserName'] = $User['UserName'];
            $Data['AdminPassword'] = $User['Password'];
            $Auth = $Model->Authentication($Data);
            if ($Auth) {
                if ($Auth['IsAdmin'] == 1) {
                    return true;
                }
                $Pgs = explode(',', $Auth['Permission']);
                if ($this->Data['pID'] == 'Home' || in_array($this->Data['pID'], $Pgs)) {
                    return TRUE;
                }
                exit('Access Denied');
                return true;
            }
        }
        if ((isset($_GET['rel']) && $_GET['rel'] == 'ajax')) {
            $json = array();
            $json['RedirectError'] = ADM_BASE . 'Home/SignIn';
            echo json_encode($json);
            return;
        }
        Redirect(ADM_BASE . 'Home/SignIn');
        return false;
    }

    public function Index() {
        if ($this->Authentication() == true) {
            $this->Data['dResults'] = $this->Model->GetAll();
            $this->Data['dRenderNav'] = RenderHTMLNavDB($this->Model->db->RenderFullNav());
            $this->IndexBeforeRender();
            $this->View->Render();
        }
    }

    protected function IndexBeforeRender() {

    }

    public function Details() {
        if ($this->Authentication() == true) {
            $this->BeforeRenderDetails();
            $ID = $this->Filter($_GET['dID']);
            $this->Data['dRow'] = $this->Model->GetByID($ID);
            if (Request::IsAjax()) {
                $this->View->RenderOnly();
            } else {
                $this->View->Render();
            }
        }
    }

    protected function BeforeRenderDetails() {

    }

    public function Add() {
        if ($this->Authentication() == true) {
            if (Request::IsPost()) {
                $json = $this->Validation();
                if ($json) {
                    if (Request::IsAjax()) {
                        EchoJson(array('Error' => $json));
                    } else {
                        $this->Data['dRow'] = $this->GetData();
                        foreach ($json as $k => $v) {
                            $this->Data['err' . $k] = $v;
                        }
                    }
                } else {
                    $Data = $this->GetData();
                    $this->BeforeAdd();
                    $this->Model->Add($Data);
                    $this->AfterAdd();
                    Cache::Delete();
                    $json['Redirect'] = ADM_BASE . $this->Data['pID'];
                    if (Request::IsAjax()) {
                        EchoJson($json);
                    } else {
                        Redirect($json['Redirect']);
                    }
                }
            } else {
                if (isset($_GET['id'])) {
                    $ID = intval($_GET['id']);
                    $this->Data['dRow'] = $this->Model->GetByID($ID);
                }
            }
            $this->GetForm();
        }
    }

    protected function BeforeAdd() {

    }

    protected function AfterAdd() {

    }

    public function Edit($ID) {
        if ($this->Authentication() == true) {
            if (Request::IsPost()) {
                $json = $this->Validation();
                if ($json) {
                    if (Request::IsAjax()) {
                        EchoJson(array('Error' => $json));
                    } else {
                        $this->Data['dRow'] = $this->GetData();
                        foreach ($json as $k => $v) {
                            $this->Data['err' . $k] = $v;
                        }
                    }
                } else {
                    $Data = $this->GetData();
                    $Data['ID'] = $ID;
                    $this->Model->Edit($Data);
                    Cache::Delete();
                    $json['Redirect'] = ADM_BASE . $this->Data['pID'];
                    if (Request::IsAjax()) {
                        EchoJson($json);
                    } else {
                        Redirect($json['Redirect']);
                    }
                }
            } else {
                $this->Data['dRow'] = $this->Model->GetByID($ID);
            }
            $this->GetForm();
        }
    }

    public function Delete() {
        if ($this->Authentication() == true) {
            if ($this->Data['pUser']['IsAdmin']) {
                $IDs = $_POST['dIDs'];
                if (Request::IsPost() && strlen($IDs) > 0) {
                    $dIDs = $this->GetIDs($IDs);
                    for ($idx = 0; $idx < count($dIDs); $idx++) {
                        $Mdl = $this->Data['pID'] . 'Model';
                        $this->Model->Delete($dIDs[$idx]);
                    }
                }
            }
            Cache::Delete();
            Redirect(ADM_BASE . $this->Data['pID']);
        }
    }

    public function UpdateSorting() {
        if (Request::IsPost()) {
            $Data = $this->GetData();
            if (isset($Data['Sort'])) {
                foreach ($Data['Sort'] as $i) {
                    $__ = explode('=', $i);
                    $id = intval(GetValue($__[0]));
                    $sort = intval(GetValue($__[1]));
                    if ($id > 0) {
                        $this->Model->SetSort($id, $sort);
                    }
                }
                EchoExit($this->_['_SavedDone']);
            }
        }
    }

    public function ChangeState() {
        if ($this->Authentication() == true) {
            $ID = intval($_POST['ID']);
            $State = intval($_POST['State']);
            if ($ID > 0) {
                $this->Model->SetState($ID, $State);
                Cache::Delete();
                $this->AfterChangeState();
                EchoExit($this->_['_SavedDone']);
            }
            EchoError($this->_['_Unexpected_Error']);
        }
    }

    protected function AfterChangeState() {

    }

    protected function BeforeGetForm() {

    }

    protected function GetForm() {
        $this->BeforeGetForm();
        $this->View->Render($this->Data['pID'] . '/Form');
    }

    protected function AutoComplete($Table, $IsLang, $ID, $Label, $Value) {
        if ($this->Authentication()) {
            $json = array();
            $Q = urldecode($this->Filter($_GET['term']));
            $Data = $this->Model->GetAutoComplete($Table, $Label, $Q, $IsLang);
            foreach ($Data as $i) {
                $json[] = array(
                    'id' => $i[$ID],
                    'label' => $i[$Label],
                    'value' => $i[$Value]
                );
            }
            EchoJson($json);
        }
    }

    protected abstract function Validation();

    public function UploadImage() {
        if ($this->Authentication() == true) {
            $Img = &$this->Image;
            $Img->Name = time();
            $Img->Picture = 'Image';
            if ($Img->Upload() == true) {
                $this->Data['dImage'] = $Img->UploadUrl . $Img->Name . '.' . $Img->Ext;
            }
            $this->View->RenderOnly('General/UploadImage', false);
        }
    }

    public function UploadImage_Ajax() {
        if ($this->Authentication() == true) {
            $Img = &$this->Image;
            $Img->Name = time();
            $Img->Picture = 'imagealbum';
            if ($Img->Upload() == true) {
                $UploadedImage = $Img->UploadUrl . $Img->Name . '.' . $Img->Ext;

                echo json_encode(array(
                    'name' => $UploadedImage,
                    'error' => '',
                ));
                exit;
            }
        }
    }

    public function MultiUploadImage() {
        if (Request::IsPost()) {
            $Img = &$this->Image;
            $Img->Name = time();
            $Img->Picture = 'Image';
            if ($Img->MultipleUpload()) {
                EchoJson($Img->MultiUploadUrl);
            }
        }
    }

    public function DeleteFile($File) {
        if (file_exists($File)) {
            unlink($File);
        }
    }

    public function GetCategories() {
        return $this->Model->GetCategories();
    }

    private function getTitle() {
        $_aps = $this->AdminPages();
        foreach ($_aps as $i) {
            if ($i['ID'] == $this->Data['pID']) {
                return $i['Name'];
            }
        }
    }

    protected function AdminPages() {
        $_ = &$this->_;
        $ps = array();

        $ps[] = array("ID" => "Product",
            "Name" => $_["_Products"],
            "Icon" => 'tv');

        $ps[] = array("ID" => "Category",
            "Name" => $_["_Categories"],
            "Icon" => 'folder');

        $ps[] = array("ID" => "Brand",
            "Name" => $_["_Brands"],
            "Icon" => 'copyright');

        $ps[] = array("ID" => "Cart",
            "Name" => $_["_Carts"],
            "Icon" => 'shopping-cart');

        $ps[] = array("ID" => "Cart/Log",
            "Name" => $_["_Carts_"],
            "Icon" => 'shopping-cart');

//        $ps[] = array("ID" => "Cart/Log",
//            "Name" => $_["_Carts_Log"],
//            "Icon" => 'shopping-cart');

        $ps[] = array("ID" => "Cart/ProductLog",
            "Name" => $_["_ByProduct"],
            "Icon" => 'shopping-cart');

        $ps[] = array("ID" => "Cart/UserLog",
            "Name" => $_["_ByUser"],
            "Icon" => 'shopping-cart');

        $ps[] = array("ID" => "Page",
            "Name" => $_["_Pages"],
            "Icon" => 'newspaper-o');

        $ps[] = array("ID" => "Slider",
            "Name" => $_["_Sliders"],
            "Icon" => 'photo');

        $ps[] = array("ID" => "Menu",
            "Name" => $_["_Menus"],
            "Icon" => 'navicon');

        $ps[] = array("ID" => "MailingList_User",
            "Name" => $_["_MailingList_Users"],
            "Icon" => 'user');

        $ps[] = array("ID" => "MailingList",
            "Name" => $_["_MailingList"],
            "Icon" => 'envelope');

        $ps[] = array("ID" => "Notification",
            "Name" => $_["_Notifications"],
            "Icon" => 'mobile');

        $ps[] = array("ID" => "Banner",
            "Name" => $_["_Banners"],
            "Icon" => 'paw');

        $ps[] = array("ID" => "User",
            "Name" => $_["_Users"],
            "Icon" => 'group');

        $ps[] = array("ID" => "Setting",
            "Name" => $_["_Settings"],
            "Icon" => 'gear');

        $ps[] = array("ID" => "AdminSetting",
            "Name" => $_["_AdminSettings"],
            "Icon" => 'gear');

        return $ps;
    }

    public function GetLoggedUser() {
        Session::Init();
        return Session::Get('AdminUser');
    }

}
