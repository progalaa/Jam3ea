<?php

require_once '../System/Libraries/Config.php';
require_once '../System/Libraries/Defines.php';
define('API_URL', SITE_URL . 'API/');
define('API_BASE', API_URL . BASE);

$FullUrl = ReplaceText('www.', '', "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]");
$ReqUrl = substr($FullUrl, strlen(API_BASE));
$Url = isset($ReqUrl) ?
        explode('/', rtrim(substr($ReqUrl, 0, (strpos($ReqUrl, '?') > 0) ?
                                        strpos($ReqUrl, '?') :
                                        strlen($ReqUrl)))) :
        null;

$Lang_Code = GetValue($Url[0], 'ar');
$Class = isset($Url[1]) && !empty($Url[1]) ? str_replace('.html', '', $Url[1]) : 'home';
$Method = isset($Url[2]) && !empty($Url[2]) ? str_replace('.html', '', $Url[2]) : 'index';
$Parameter = (isset($Url[3])) ? rtrim($Url[3], '.html') : null;
$CacheFile = "{$Lang_Code}.{$Class}" . ($Method != 'index' ? ".{$Method}" . ($Parameter ? ".{$Parameter}" : '') : '');

$Cached = Cache::Get($CacheFile);
if ($Cached) {
    EchoJson($Cached);
}

define('API_PATH', BASE_DIR . 'API/');
define('API_LIB', API_PATH . 'Libraries/');
define('API_MODELS', API_PATH . 'Models/');
define('API_CONTROLLERS', API_PATH . 'Controllers/');
include API_LIB . 'Controller.php';
include APP_LIB . 'JsonParser.php';

$Registry = Registry::GetInstance();
$Registry->CurrentUrl = substr($FullUrl, 0, (strpos($FullUrl, '?') > -1) ?
                strpos($FullUrl, '?') : strlen($FullUrl));

$FC = FullCurrentUrl($FullUrl);

// <editor-fold defaultstate="collapsed" desc="Lib">
$db = new Database();
$db->Url = (str_replace(API_BASE, '', $FC)) . (strpos($FC, '?') ? '&' : '?');

$img = new Image();
$Registry->Image = $img;
// </editor-fold>

$Registry->Data['dLangs'] = null;
$LangData = Cache::Get('Languages');
if ($LangData) {
    $Registry->Data['dLangs'] = $LangData['data'];
}
if (!$Registry->Data['dLangs']) {
    $Registry->Data['dLangs'] = $db->GetRows("Select * From language Where State = 1");
    if (!$Registry->Data['dLangs']) {
        exit();
    }
    Cache::Set('Languages', array('data' => $Registry->Data['dLangs']));
} $Lang = null;
$Langs = $Registry->Data['dLangs'];
foreach ($Langs as $Item) {
    if ($Url[0] === $Item['Code']) {
        $Lang = $Item;
        break;
    }
}

$Settings = null;
$SettingsData = Cache::Get('Settings');
if ($SettingsData) {
    $Settings = $SettingsData['data'];
}
if (!$Settings) {
    $AllSettings = $db->GetRows("
        Select t.*, tl.* From setting t LEFT JOIN
		setting_lang tl
		ON (tl.SettingID = t.ID)
        ");
    if (!$AllSettings) {
        exit();
    }
    foreach ($AllSettings as $s) {
        $Settings['s' . $s['Name']] = $s['Value']? : $s['DefaultValue'];
    }
    Cache::Set('Settings', array('data' => $Settings));
}
$Registry->Settings = $Settings;


if (!$Lang) {
    $i = $Settings['sWebsite_Language'];
    $dLang = null;
    foreach ($Langs as $Item) {
        if ($Item['ID'] === $i) {
            $dLang = $Item;
            Redirect(BASE_SITE_URL . $dLang['Code']);
            break;
        }
    }
}

define('API_LANG', $Lang['Directory']);
define('API_LANG_DIR', API_PATH . 'Language/' . API_LANG . DIRECTORY_SEPARATOR);
define('BASE_URL', BASE_SITE_URL . $Lang['Code'] . '/');


$File = API_CONTROLLERS . $Class . '.php';
if (file_exists($File)) {
    require_once $File;
} else {
    echo __LINE__;
    RedirectNotFound();
    return;
}

// Start Default Data
$Registry->Data['pID'] = $Class;
$Registry->Data['pAction'] = $Method;
$Registry->Data['pParameter'] = $Parameter;
$Registry->Data['pUrl'] = API_BASE . $Class . '/' . $Method . '/' . $Parameter;
// End Defualt Data
// Start Language Loading
$_ = array();
include API_LANG_DIR . 'Database.php';
$db->_ = $_;
include API_LANG_DIR . 'Attribute.php';
include API_LANG_DIR . 'Local.php';
//include API_LANG_DIR . 'Error.php';
$LangFile = API_LANG_DIR . 'Pages/' . $Class . '.php';
if (file_exists($LangFile)) {
    include $LangFile;
}
$Registry->_ = $_;
$Registry->Language = $Lang;
$Registry->Languages = $Langs;
// End Language Loading
// <editor-fold defaultstate="collapsed" desc="Load Controller">
$ControllerClass = $Class . 'Controller';
$Controller = new $ControllerClass;
$Controller->Model = $Controller->LoadModel($Class);
$Controller->Model->db = $db;
$Controller->Model->Language = $Controller->Language = $Lang;
$Controller->Model->Settings = $Controller->Settings = $Settings;
$Controller->CacheFile = $CacheFile;
Session::Init();
$Controller->pUser = $Controller->Model->pUser = Session::Get(_UD);

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
    $Controller->index();
}

function RedirectNotFound() {
    echo ' : API Not Found!';
}
