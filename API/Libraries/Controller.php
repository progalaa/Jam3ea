<?php

abstract class ControllerAPI extends Controller {

    public $View;
    public $Model;
    public $JsonParser;
    public $_ = array();
    public $Data = array();

    public function __construct() {
        $Registry = Registry::GetInstance();
        $this->_ = &$Registry->_;
        $this->Data = &$Registry->Data;
        $this->Image = &$Registry->Image;
        $this->Settings = &$Registry->Settings;
        $this->JsonParser = new JsonParser();
    }

    public function LoadModel($Name) {
        if (IncludeFileOnce(API_MODELS . $Name . '.php', true)) {
            $ModelName = $Name . 'Model';
            return new $ModelName;
        }
    }

    protected function setJsonParser($data, $cache = true, $pages = null) {
        $this->JsonParser->data = $data;
        $this->JsonParser->pages = $pages;
        if ($cache) {
            $this->JsonParser->cache = $this->CacheFile;
        }
        $this->JsonParser->Response();
    }

    protected function Authentication($redirect = true) {
        Session::Init();
        $Data = Session::Get(_UD);
        if (Request::IsPost() && !(isset($Data['AuthUserName']) || isset($Data['AuthPassword']))) {
            $Data = $this->GetJsonData();
        }
        if (isset($Data['AuthUserName']) && isset($Data['AuthPassword'])) {
            $UD = array();
            $UD['UserName'] = $Data['AuthUserName'];
            $UD['Password'] = $this->Encrypt($Data['AuthPassword']);
            $Res = $this->Model->Authentication($UD);
            if ($Res != null) {
                $Res['AuthUserName'] = $Data['AuthUserName'];
                $Res['AuthPassword'] = $Data['AuthPassword'];
                Session::Set(_UD, $Res);
                $this->pUser = $this->Model->pUser = $Res;
                return true;
            } else {
                $_ = &$this->_;
                $err = $_['_User_Login_Error'];
            }
        }
        if ($redirect) {
            $_ = &$this->_;
            $err = $_['_Must_Be_Login'];
            $this->JsonParser->error = array('Auth' => $err);
            $this->JsonParser->success = false;
            $this->JsonParser->Response();
        }
        return false;
    }

    protected function LoadLangFile() {
        $_ = array();
        include API_LANG_DIR . 'Local.php';
        $LangFile = API_LANG_DIR . 'Pages/' . $this->Data['pID'] . '.php';
        if (file_exists($LangFile)) {
            include_once $LangFile;
        }
        return $_;
    }

}
