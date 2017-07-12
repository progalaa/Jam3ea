<?php

/**
 * @link http://mrabdelrahman10.com/product
 * Get all product, by category or search in all product
 */
class productController extends ControllerAPI {

    /**
     *  @param $_GET w word to search
     *  @return array of all product with paginate or if $_GET['w'] is not empty or null return product by search word
     *  @link http://mrabdelrahman10.com/product/index
     */
    public function index() {
        $this->setJsonParser($this->Model->GetAll(), false, $this->Model->db->RenderFullNav());
    }

    /**
     *  @param $_GET w word to search
     *  @return array of all product with paginate or if $_GET['w'] is not empty or null return product by search word
     *  @link http://mrabdelrahman10.com/product/index
     */
    public function search() {
        $q = $this->Filter(GetValue($_GET['q']));
        $this->setJsonParser($this->Model->GetAll($q), false, $this->Model->db->RenderFullNav());
    }

    /**
     * @link http://mrabdelrahman10.com/product/i/{ID}
     * @param int $ID
     * @example http://mrabdelrahman10.com/product/i/578
     * @return array one product row by id or return null.
     */
    public function i($_ID) {
        $ID = intval($_ID);
        $Data = null;
        if ($ID) {
            $Data = $this->Model->GetByID($ID);
            $this->Model->UpdateViewed($ID);
            $this->setJsonParser($Data);
        } else {
            $this->JsonParser->success = false;
            $this->JsonParser->Response();
        }
    }

    /**
     * @link http://mrabdelrahman10.com/product/v/{ID}
     * @param int $ID
     * @example http://mrabdelrahman10.com/product/v/578
     * @return add 1 to product Views
     */
    public function v($ID) {
        if (intval($ID) > 0) {
            $r = $this->Model->UpdateViewed($ID);
            if ($r > 0) {
                $this->JsonParser->data = true;
            } else {
                $this->JsonParser->success = false;
            }
        }
        $this->JsonParser->Response();
    }

    /**
     * @link http://mrabdelrahman10.com/product/related/{ID}
     * @param int $ID
     * @example http://mrabdelrahman10.com/product/related/578
     * @return array of related products by Category id or return null.
     */
    public function related($ID) {
        $this->JsonParser->data = $this->Model->GetRelated($ID);
        $this->JsonParser->Response();
    }

    /**
     * @link http://mrabdelrahman10.com/product/comments/{ID}
     * @param int $ID
     * @example http://mrabdelrahman10.com/product/comments/578
     * @return array product's Comments by product id or return null.
     */
    public function comments($ID) {
        $this->JsonParser->data = $this->Model->GetComments($ID);
        $this->JsonParser->Response();
    }

    public function add_comment() {
        if (Request::IsPost()) {
            $json = $this->Validation();
            if ($json) {
                EchoJson($json);
            } else {
                $Data = $this->GetJsonData();
                if (GetValue($this->pUser)) {
                    $Data['UserID'] = $this->pUser['ID'];
                }
                $ID = $this->Model->AddComment($Data);
                if ($ID > 0) {
                    EchoExit($this->_['_Comment_Added']);
                } else {
                    EchoError($this->_['_ErrorUnexpected']);
                }
            }
        }
    }

    /**
     * @link http://mrabdelrahman10.com/product/c/{ID}
     * @param int $ID
     * @example http://mrabdelrahman10.com/product/c/5
     * @return array of products by category id or return null.
     */
    public function c($ID) {
        $this->setJsonParser($this->Model->GetByCategory($this->Filter(urldecode($ID))), false, $this->Model->db->RenderFullNav());
    }

    /**
     *
     * @return type
     */
    public function like() {
        if (Request::IsPost()) {
            $id = $this->Filter($_POST['aID']);
            $result = $this->Model->Like($id);
            $d = array();
            if ($result > 0) {
                $this->JsonParser->data = $this->_['_Liked_S'];
            } else {
                $this->JsonParser->success = false;
                $this->JsonParser->data = $this->_['_Liked_F'];
            }
            $this->JsonParser->Response();
        }
    }

    /*
     *
     */

    public function unlike() {
        if (Request::IsPost()) {
            $id = $this->Filter($_POST['aID']);
            $result = $this->Model->UnLike($id);
            $d = array();
            if ($result > 0) {
                $this->JsonParser->data = $this->_['_UnLiked_S'];
            } else {
                $this->JsonParser->success = false;
                $this->JsonParser->data = $this->_['_UnLiked_F'];
            }
            $this->JsonParser->Response();
        }
    }

    protected function GetData() {
        $Data = $this->FilterData();
        $Data['UserID'] = $this->pUser['ID'];
        $Data['State'] = $Data['UserID'] ? 1 : 0;
        return $Data;
    }

    private function Validation() {
        $json = array();
        $Data = $this->GetData();
        $Title = $Data['cTitle'];
        $Contents = $Data['cContents'];
        $e = &$json['Error'];
        $_ = &$this->_;

//Title
        if (empty($Title)) {
            $e['cTitle'] = str_format($_['_Error_Required'], $_['_Title']);
        } else if (GetLength($Title) > 100) {
            $e['cTitle'] = str_format($_['_Error_Max_Length'], $_['_Title'], 100);
        }

//Contents
        if (empty($Contents)) {
            $e['cContents'] = str_format($_['_Error_Required'], $_['_Comment']);
        } else if (GetLength($Contents) > 300) {
            $e['cContents'] = str_format($_['_Error_Max_Length'], $_['_Comment'], 300);
        }

        return ($e) ? $json : null;
    }

}
