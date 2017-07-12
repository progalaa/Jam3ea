<?php

require_once '../System/Libraries/Config.php';
require_once '../System/Libraries/Defines.php';

$Template = 'MrAbdelrahman10';
$Area = 'to-admin';
define('ADM_URL', SITE_URL . $Area . '/');
define('ADM_BASE', ADM_URL . BASE);
define('ADM_PATH', BASE_DIR . $Area . '/');
define('ADM_LIB', ADM_PATH . 'Libraries/');
define('ADM_MODELS', ADM_PATH . 'Models/');
define('ADM_CONTROLLERS', ADM_PATH . 'Controllers/');
define('ADM_VIEWS', ADM_PATH . 'Views/');
define('ADM_SCRIPTS', ADM_VIEWS . 'Scripts/');
define('ADM_CURRENT_THEME', ADM_VIEWS . 'Themes/' . $Template . '/');
define('ADM_CURRENT_DIR_PAGES', ADM_CURRENT_THEME . 'Pages/');
define('ADM_CURRENT_DIR_TEMPLATE', ADM_CURRENT_THEME . 'Files/');
define('ADM_CURRENT_URL_TEMPLATE', ADM_URL . 'Views/Themes/' . $Template . '/Files/');
define('ADM_TEMPLATE_SHARED', ADM_CURRENT_THEME . 'Shared/');
define('APP_PUBLIC', SITE_URL . 'Public/');
define('ADM_LANG', 'Arabic');
define('ADM_LANG_DIR', ADM_PATH . 'Language/' . ADM_LANG . '/');
include ADM_LIB . 'Helper.php';
include ADM_LIB . 'Controller.php';
include ADM_LIB . 'Model.php';
include ADM_LIB . 'View.php';
include APP_LIB . 'Restful.php';

$Registry = Registry::GetInstance();
$_FullUrl = ReplaceText('www.', '', "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]");
$Registry->FullUrl = "$_FullUrl";

$ReqUrl = str_replace(ADM_BASE, '', substr($Registry->FullUrl, 0, strpos($Registry->FullUrl, '?')? : strlen($Registry->FullUrl)));

$Url = explode('/', $ReqUrl);
$Registry->CurrentUrl = ROOT_URL . $Area . '/' . $ReqUrl;

$FC = FullCurrentUrl();

// <editor-fold defaultstate="collapsed" desc="Lib">
$db = new Database();
$db->Url = trim((str_replace(ADM_BASE, '', $FC)) . (strpos($FC, '?') ? '&' : '?'), '/');

$img = new Image();
$Registry->Image = $img;

$Registry->API = new Restful();

// End Lib

define('BASE_API_URL', SITE_URL . 'API/ar/');

function GetAPIUrl($url) {
    return BASE_API_URL . $url;
}

$DataLanguages = Cache::Get('Languages');
if (!$DataLanguages) {
    $Registry->API->url = GetAPIUrl('general/languages');
    $DataLanguages = $Registry->API->Process();
}
$Registry->Data['dLangs'] = $DataLanguages['data'];


$DataSettings = Cache::Get('Settings');
if (!$DataSettings) {
    $Registry->API->url = GetAPIUrl('general/settings');
    $DataSettings = $Registry->API->Process();
}
$Registry->Settings = $Settings = $DataSettings['data'];

Session::Init();
$Lang = Session::Get('AdministratorLanguage');
$Langs = $Registry->Data['dLangs'];
if (!$Lang) {
    $i = $Settings['sAdministrator_Languege'];
    foreach ($Langs as $Item) {
        if ($Item['ID'] === $i) {
            $Lang = $Item;
            break;
        }
    }
}
if (!$Lang) {
    die('No Language Installed');
}
Session::Set('AdministratorLanguage', $Lang);

define('REWRITE_URL_STYLE', $Settings['sRewriteUrl']);
$Class = isset($Url[0]) && !empty($Url[0]) ? $Url[0] : 'Home';
$Method = isset($Url[1]) && !empty($Url[1]) ? $Url[1] : 'Index';
$Parameter = (isset($Url[2])) ? $Url[2] : null;

$File = ADM_CONTROLLERS . $Class . '.php';
if (file_exists($File)) {
    include_once $File;
} else {
    exit($File);
    RedirectNotFound();
    return;
}

// Start Language Loading
$_ = array();
include ADM_LANG_DIR . 'Database.php';
$db->_ = $_;
define('API_LANG_DIR', BASE_DIR . 'API/Language/' . ADM_LANG . DIRECTORY_SEPARATOR);
include API_LANG_DIR . 'Attribute.php';
include ADM_LANG_DIR . 'Local.php';
include ADM_LANG_DIR . 'Error.php';
$LangFile = ADM_LANG_DIR . 'Pages/' . $Class . '.php';
if (file_exists($LangFile)) {
    include $LangFile;
}
$Registry->_ = $_;
$Registry->Language = $Lang;
$Registry->Languages = $Langs;
// End Language Loading
// Start Default Data
$Registry->Data['pID'] = $Class;
$Registry->Data['pAction'] = $Method;
$Registry->Data['pParameter'] = $Parameter;
$Registry->Data['pUrl'] = ADM_BASE . $Class . '/' . $Method . '/' . $Parameter;
$Registry->Data['pfUrl'] = $_FullUrl;
$Registry->Data['dButtons'] = true;
$Registry->Data['dRenderNav'] = false;

// End Defualt Data
// <editor-fold defaultstate="collapsed" desc="Load Controller">
$ControllerClass = $Class . 'Controller';
$Controller = new $ControllerClass;
$Controller->Model = $Controller->LoadModel($Class);
$Controller->Model->db = $db;
$Controller->Model->Language = $Lang;
$Controller->Model->Languages = $Controller->Data['dLangs'] = $Langs;
$Controller->Data['pUser'] = $Controller->Model->pUser = $Controller->GetLoggedUser();
// </editor-fold>


if (isset($Parameter)) {
    if (method_exists($Controller, $Method)) {
        $Controller->{$Method}($Parameter);
    } else {
        RedirectNotFound();
    }
} else if (isset($Method)) {
    if (method_exists($Controller, $Method)) {
        $Controller->{$Method}();
    } else {
        RedirectNotFound();
    }
} else {
    $Controller->Index();
}

function RedirectNotFound() {
    Redirect(ADM_BASE);
}
