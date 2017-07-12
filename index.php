<?php

require_once './System/Libraries/Config.php';
require_once './System/Libraries/Defines.php';
require_once APP_CURRENT_DIR_TEMPLATE . 'ThemeController.php';

include './System/Libraries/Restful.php';
$Registry = &DoRegistry();
$Registry->Data = array();
$FullW3Url = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
$FullUrl = ReplaceText('www.', '', $FullW3Url);
//if (!(isset($_SERVER['HTTPS']) && (($_SERVER['HTTPS'] == 'on') || ($_SERVER['HTTPS'] == '1') )) || (strpos($FullW3Url, 'www.') > 0)) {
//    Redirect("https://" . ReplaceText('www.', '', "$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]"));
//}
$ReqUrl = substr($FullUrl, strlen(BASE_SITE_URL));
$Url = isset($ReqUrl) ?
        explode('/', rtrim(substr($ReqUrl, 0, (strpos($ReqUrl, '?') > -1) ?
                                        strpos($ReqUrl, '?') :
                                        strlen($ReqUrl)))) :
        null;
$Url[0] = 'ar';
if ($Url[0] != 'ar') {
    Redirect(SITE_URL . 'ar');
}
$Registry->CurrentUrl = substr($FullUrl, 0, (strpos($FullUrl, '?') > -1) ?
                strpos($FullUrl, '?') : strlen($FullUrl));

// <editor-fold defaultstate="collapsed" desc="Lib">

$img = new Image();
$Registry->Image = $img;

$Registry->API = new Restful();

// End Lib

define('BASE_API_URL', SITE_URL . 'API/' . $Url[0] . '/');

function GetAPIUrl($url) {
    return BASE_API_URL . $url;
}

$DataLanguages = Cache::Get('Languages');
if (!$DataLanguages) {
    $Registry->API->url = GetAPIUrl('general/languages');
    $DataLanguages = $Registry->API->Process();
}
$Registry->Data['dLangs'] = $DataLanguages['data'];
$Lang = null;
if ($Url[0] && $Registry->Data['dLangs']) {
    foreach ($Registry->Data['dLangs'] as $Item) {
        if ($Url[0] === $Item['Code']) {
            $Lang = $Item;
            break;
        }
    }
}

$DataSettings = Cache::Get('Settings');
if (!$DataSettings) {
    $Registry->API->url = GetAPIUrl('general/settings');
    $DataSettings = $Registry->API->Process();
}
$Settings = $DataSettings['data'];
define('REWRITE_URL_STYLE', $Settings['sRewriteUrl']);

$Registry->Settings = $Settings;

if (!$Lang && $Registry->Data['dLangs']) {
    $i = $Settings['sWebsite_Language'];
    $dLang = null;
    foreach ($Registry->Data['dLangs'] as $Item) {
        if ($Item['ID'] === $i) {
            $dLang = $Item;
            Redirect(BASE_SITE_URL . $dLang['Code']);
            break;
        }
    }
}
$Registry->Lang = $Lang;

if (!$Lang) {
    die('No Language Installed.');
}

$Lang_Code = GetValue($Url[0]);
$Class = isset($Url[1]) && !empty($Url[1]) ? str_replace('.html', '', $Url[1]) : 'home';
$Method = isset($Url[2]) && !empty($Url[2]) ? str_replace('.html', '', $Url[2]) : 'index';
$Parameter = (isset($Url[3])) ? rtrim($Url[3], '.html') : null;
$CacheFile = "{$Lang_Code}.{$Class}" . ($Method != 'index' ? ".{$Method}" . ($Parameter ? ".{$Parameter}" : '') : '');

define('APP_LANG', $Lang['Directory']);
define('APP_LANG_DIR', APP_PATH . 'Language/' . APP_LANG . DIRECTORY_SEPARATOR);
define('BASE_URL', BASE_SITE_URL . $Lang['Code'] . '/');

$File = APP_CONTROLLERS . $Class . '.php';
if (!IncludeFile($File)) {
    RedirectNotFound();
}

// Start Language Loading
$_ = array();
require (APP_LANG_DIR . 'Local.php');
$LangFile = (APP_LANG_DIR . 'Pages/' . $Class . DIRECTORY_SEPARATOR . $Method . '.php');
$LangClassFile = (APP_LANG_DIR . 'Pages/' . $Class . DIRECTORY_SEPARATOR . $Class . '.php');
if (file_exists($LangFile)) {
    include $LangFile;
}
if (file_exists($LangClassFile)) {
    include $LangClassFile;
}
$Registry->_ = $_;
// End Language Loading
// Start Default Data
Session::Init();

$No_Style = Session::Get('No_Style');
if ($No_Style == null) {
    $No_Style = isset($_GET['nostyle']) ? true : false;
    Session::Set('No_Style', $No_Style);
}
define('NO_STYLE', $No_Style);

$Registry->Data['pID'] = $Class;
$Registry->Data['pAction'] = $Method;
$Registry->Data['pParameter'] = $Parameter;
$Registry->Data['psUrl'] = $Class . ($Method != 'index' ? '/' . $Method . ($Parameter ? '/' . $Parameter : '') : '');
$Registry->Data['pUrl'] = BASE_URL . ($Registry->Data['psUrl'] != 'home' ? $Registry->Data['psUrl'] : '');
$Registry->Data['pfUrl'] = $Registry->Data['pUrl'];
$Registry->Data['pImage'] = APP_CURRENT_URL_TEMPLATE . 'img/logo.png';
$Registry->Data['dTitle'] = null;
$Registry->Data['pUser'] = Session::Get(_UD);
$Registry->Data['dDescription'] = $Settings['sMetaDescription'];
$Registry->Data['dKeywords'] = $Settings['sMetaKeywords'];
$Registry->Data['dResults'] = array();
$Registry->Data['dFullWidth'] = false;
$Registry->Data['dRenderNav'] = false;
// End Defualt Data
// Start Controller Loading

$ControllerClass = $Class . 'Controller';
$Controller = new $ControllerClass;
$Controller->CacheFile = $CacheFile;
if (!Request::IsAjax()) {
    new ThemeController();
}

// End Load Controller

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

//ob_end_flush();
function RedirectNotFound() {
    exit;
    Redirect(GetRewriteUrl('home/error'));
}
