<?php

/**
 * General Methods
 */
class generalController extends ControllerAPI {

    public function thumb() {
        $url = $_GET['url'];
        $height = $_GET['height'];
        $width = $_GET['width'];
        echo GetImageThumbnail($url, $width, $height);
    }

    public function menus() {
        $this->CacheFile = 'Menus.' . $this->Language['Code'];
        $this->setJsonParser($this->Model->GetMenus());
    }

    public function categories() {
        $this->CacheFile = 'Category.' . $this->Language['Code'];
        $this->setJsonParser($this->Model->GetCategories());
    }

    public function categoriesbyparent($ID) {
        $this->setJsonParser($this->Model->GetCategories(\intval($ID)));
    }

    public function allbanners() {
        $this->CacheFile = 'Banners';
        $this->setJsonParser($this->Model->GetAllBanners());
    }

    public function banner($id) {
        $this->setJsonParser($this->Model->GetBanners($id));
    }

    public function uploadimage() {
        if ($this->Authentication() == true) {
            if (Request::IsPost()) {
                $Img = &$this->Image;
                $Img->Name = time();
                $Img->Picture = 'Image';
                if ($Img->Upload() == true) {
                    $this->Data['dImage'] = $Img->UploadUrl . $Img->Name . '.' . $Img->Ext;
                }
            }
            $this->View->RenderOnly('general/uploadimage', false);
        }
    }

    public function uploadimage_m() {
//        if ($this->Authentication() == true) {
        if (Request::IsPost()) {
            $json = array();
            $Img = &$this->Image;
            $Img->Name = time();
            $Img->Picture = 'Image';
            if ($Img->Upload() == true) {
                $json['Image'] = $Img->UploadUrl . $Img->Name . '.' . $Img->Ext;
                EchoJson($json);
            }
        }
//        }
    }

    public function imagefromtext() {
        $img = $_POST['Image'];
        $img = str_replace('data:image/png;base64,', '', $img);
        $img = str_replace(' ', '+', $img);
        $Img = &$this->Image;
        $fldr = $Img->GetFolderDay();
        $fl = uniqid() . '.png';
        $data = base64_decode($img);
        $file = BASE_DIR . $fldr . $fl;
        $success = file_put_contents($file, $data);
        echo $success ? SITE_URL . $fldr . $fl : 'Unable to save the file.';
    }

    public function add_device() {
        if (Request::IsPost()) {
            $Data = $this->GetJsonData();
            $ID = $this->Model->AddDevice($Data);
            if ($ID > 0) {
                $this->JsonParser->data = $this->_['_SavedDone'];
            } else {
                $this->JsonParser->error = $this->_['_ErrorUnexpected'];
                $this->JsonParser->success = false;
            }
            $this->JsonParser->Response();
        }
    }

    public function delete_device() {
        if (Request::IsPost()) {
            $Data = $this->GetData();
            $this->Model->DeleteDevice($Data);
        }
    }

    public function settings() {
        $this->CacheFile = 'Settings';
        $this->setJsonParser($this->Model->GetSettings());
    }

    public function languages() {
        $this->CacheFile = 'Languages';
        $this->setJsonParser($this->Model->GetLanguages());
    }

}
