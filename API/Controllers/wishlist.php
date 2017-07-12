<?php

/**
 *
 * @author MrAbdelrahman
 */
class wishlistController extends ControllerAPI {

    public function index() {
        if ($this->Authentication()) {
            $d = array();
            $Data = $this->Model->GetAll();
            foreach ($Data as $i) {
                if ($i['State']) {
                    $i['SliderPictures'] = (unserialize($i['SliderPictures']));
                    $d[] = $i;
                }
            }
            $this->setJsonParser($d, false);
        }
    }

    public function items_count() {
        if ($this->Authentication()) {
            $this->setJsonParser($this->Model->GetItemsCount(), false);
        }
    }

    public function add() {
        if ($this->Authentication()) {
            if (Request::IsPost()) {
                Session::Init();
                $Favs = $this->Model->GetAllIDs();
                $Data = $this->GetJsonData();
                $_ = &$this->_;
                if ($Favs) {
                    foreach ($Favs as $i) {
                        if ($Data['Product'] == $i) {
                            $this->JsonParser->error = $_['_Wishlist_Is_Found'];
                            $this->JsonParser->success = false;
                            $this->JsonParser->Response();
                        }
                    }
                }
                $ID = $this->Model->Add($Data['Product']);
                if (!$ID) {
                    $this->JsonParser->error = $_['_ErrorUnexpected'];
                    $this->JsonParser->success = false;
                } else {
                    $this->JsonParser->data = $_['_Wishlist_Added'];
                }

                $this->JsonParser->Response();
            }
        }
    }

    public function delete() {
        if ($this->Authentication()) {
            if (Request::IsPost()) {
                $Data = $this->GetJsonData();
                $_ = $this->_;
                $this->Model->Delete($Data['Product']);
                $this->JsonParser->data = $_['_Wishlist_Deleted'];
                $this->JsonParser->Response();
            }
        }
    }

}
