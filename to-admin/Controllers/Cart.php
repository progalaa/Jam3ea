<?php

class CartController extends ControllerAdmin {

    protected function BeforeRenderDetails() {
        $this->Data['dStatesList'] = $this->LoadDropDownAttr($this->_['_Order_State_List']);
        if (Request::IsPost()) {
            $Data = $this->GetData();
            $Data['ID'] = GetValue($_GET['dID']);
            if (intval($Data['ID']) && intval($Data['State'])) {
                $this->Model->UpdateCart($Data);
                $this->Data['dMsg'] = $this->_['_SavedDone'];
            }
        }
        if (isset($_GET['ProdID']) && isset($_GET['dID'])) {
            $Data['ID'] = intval($_GET['ProdID']);
            $Data['Returned'] = isset($_GET['NoReturned']) ? 0 : 1;
            $this->Model->SetReturned($Data);
            $this->Data['dMsg'] = $this->_['_SavedDone'];
        }
    }

    protected function IndexBeforeRender() {
        $this->Data['dTotal'] = $this->Model->GetAll(false);
    }

    public function Pill() {
        if ($this->Authentication() == true) {
            $ID = $this->Filter($_GET['dID']);
            $this->Data['dResults'] = $this->Model->GetByID($ID);
            $this->View->RenderOnly();
        }
    }

    public function UserLog() {
        if ($this->Authentication() == true) {
            $this->Data['dCategoriesList'] = $this->GetCategoriesList();
            $this->Data['dProductsList'] = $this->GetProductsByCategory(intval(GetValue($_GET['CategoryID'])));
            $this->Data['dResults'] = $this->Model->GetUserLog();
            $this->Data['dRenderNav'] = RenderHTMLNavDB($this->Model->db->RenderFullNav());
            $this->View->Render();
        }
    }

    public function ProductLog() {
        if ($this->Authentication() == true) {
            $this->Data['dCategoriesList'] = $this->GetCategoriesList();
            $this->Data['dProductsList'] = $this->GetProductsByCategory(intval(GetValue($_GET['CategoryID'])));
            $this->Data['dResults'] = $this->Model->GetProductLog();
            $this->Data['dRenderNav'] = RenderHTMLNavDB($this->Model->db->RenderFullNav());
            $this->View->Render();
        }
    }

    public function Log() {
        if ($this->Authentication() == true) {
            $this->Data['dCategoriesList'] = $this->GetCategoriesList();
            $this->Data['dProductsList'] = $this->GetProductsByCategory(intval(GetValue($_GET['CategoryID'])));
            $this->Data['dResults'] = $this->Model->GetAll();
//            $this->Data['dResults'] = $this->Model->GetLog();
            $this->Data['dRenderNav'] = RenderHTMLNavDB($this->Model->db->RenderFullNav());
            $this->Data['dTotal'] = $this->Model->GetAll(false);
            $this->View->Render();
        }
    }

    private function GetCategoriesList() {
        $Categories = $this->Model->GetCategories();
        $Output = '<option value="0">' . $this->_['_NotFound'] . '</option>';
        foreach ($Categories as $Category) {
            $PathName = array_column($Category['PathName'], 'Name');
            $Output .= '<option value="' . $Category['ID'] . '"'
                    . ($Category['IsParent'] ? ' disabled style="background-color: #0e0e0e; color: #ffffff;"' : '') . '>' . implode(' &gt;&gt; ', $PathName) . '</option>';
        }
        return $Output;
    }

    public function GetProductsByCategory($id) {
        $where = array(
            new DBField('CategoryID', intval($id), PDO::PARAM_INT, 't'),
            new DBField('State', '1', PDO::PARAM_INT, 't')
        );
        return $this->LoadDropDown($this->Model->GetTableWithDescription('Product', $where));
    }

    public function ProductsByCategory($id) {
        echo $this->GetProductsByCategory($id);
    }

    public function AutoCompleteProducts() {
        $this->AutoComplete('Product', true, 'Name', 'Name', 'Name');
    }

    public function AutoCompleteUsers() {
        $this->AutoComplete('User', false, 'FullName', 'FullName', 'FullName');
    }

    protected function Validation() {

    }

}
