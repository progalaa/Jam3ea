<?php

/**
 * Description of cart
 *
 * @author mrabdelrahman10
 */
class cartModel extends Model {

    public $LastCartID = 0;

    // <editor-fold defaultstate="collapsed" desc="Cart">

    private function AddNewCart() {
        if (!$this->LastCartID) {
            $fData = array(
                new DBField('UserID', $this->pUser['ID'], PDO::PARAM_INT),
                new DBField('CreatedDate', GetDateValue(), PDO::PARAM_STR),
                new DBField('State', '0', PDO::PARAM_BOOL),
            );
            $this->LastCartID = $this->db->Insert($fData, 'cart');
        }
        return $this->LastCartID;
    }

    private function GetLastCart() {
        if (!$this->LastCartID) {
            $Where = array(
                new DBField('UserID', $this->pUser['ID'], PDO::PARAM_INT),
                new DBField('State', '0', PDO::PARAM_BOOL),
            );
            $Data = $this->db->SelectRow('cart', 'ID', $Where);
            $this->LastCartID = $Data ? $Data['ID'] : $this->AddNewCart();
        }
        return $this->LastCartID;
    }

    // </editor-fold>
    // <editor-fold defaultstate="collapsed" desc="Cart Products">
    protected $Select = "  STRAIGHT_JOIN t.*, tl.*, cl.Name AS CategoryName, cp.Quantity AS cQuantity ";
    protected $Table = " product t
        INNER JOIN
		product_lang tl ON tl.ProductID = t.ID
        INNER JOIN
		category_lang cl ON cl.CategoryID = t.CategoryID
        INNER JOIN
		cart_product cp ON cp.ProductID = t.ID
        ";

    public function Add($Data) {
        $fData = array(
            new DBField('CartID', $this->GetLastCart(), PDO::PARAM_INT),
            new DBField('ProductID', $Data['Product'], PDO::PARAM_INT),
            new DBField('Quantity', $Data['Quantity'], PDO::PARAM_INT),
            new DBField('CreatedDate', GetDateValue(), PDO::PARAM_STR)
        );
        return $this->db->Insert($fData, 'cart_product');
    }

    public function GetAll() {
        $Where = array(
            new DBField('LanguageID', $this->Language['ID'], PDO::PARAM_INT, 'tl'),
            new DBField('LanguageID', $this->Language['ID'], PDO::PARAM_INT, 'cl'),
            new DBField('CartID', $this->GetLastCart(), PDO::PARAM_INT, 'cp')
        );
        return $this->db->Select($this->Table, $this->Select, $Where, 'cp.ID DESC');
    }

    public function GetAllByIDs($Data) {
        $Select = "  STRAIGHT_JOIN t.*, tl.*, cl.Name AS CategoryName ";
        $Table = " product t
        INNER JOIN
		product_lang tl ON tl.ProductID = t.ID
        INNER JOIN
		category_lang cl ON cl.CategoryID = t.CategoryID
        ";
        $Where = array(
            new DBField('LanguageID', $this->Language['ID'], PDO::PARAM_INT, 'tl'),
            new DBField('LanguageID', $this->Language['ID'], PDO::PARAM_INT, 'cl'),
        );

        $Where[] = new DBField('(');
        foreach ($Data as $i) {
            $Where[] = new DBField('ID', $i['ID'], PDO::PARAM_INT, 't', '=', 'OR', 'ID' . $i['ID']);
        }
        $Where[] = new DBField(')');

        return $this->db->Select($Table, $Select, $Where);
    }

    public function GetItemsCount() {
        $Where = array(
            new DBField('CartID', $this->GetLastCart(), PDO::PARAM_INT)
        );
        return $this->db->RowsCount('cart_product', 'ID', $Where);
    }

    public function GetAllIDs() {
        $Where = array(
            new DBField('CartID', $this->GetLastCart(), PDO::PARAM_INT)
        );
        return $this->db->SelectColumn('cart_product', 'ProductID', $Where);
    }

    public function Delete($ID) {
        $Where = array(
            new DBField('CartID', $this->GetLastCart(), PDO::PARAM_INT),
            new DBField('ProductID', $ID, PDO::PARAM_INT)
        );
        return $this->db->Delete('cart_product', $Where);
    }

    // </editor-fold>
    // <editor-fold defaultstate="collapsed" desc="Quantity">
    public function GetQuantity($ID) {
        $Where = array(
            new DBField('ID', $ID, PDO::PARAM_INT),
            new DBField('State', 0, PDO::PARAM_BOOL),
        );
        $Data = $this->db->SelectRow('product', 'Quantity', $Where);
        return $Data['Quantity'];
    }

    public function EditQuantity($Data) {
        $fData = array();
        $fData[] = new DBField('Quantity', $Data['Quantity'], PDO::PARAM_INT);
        $Where = array(
            new DBField('CartID', $this->GetLastCart(), PDO::PARAM_INT),
            new DBField('ProductID', $Data['Product'], PDO::PARAM_INT)
        );
        return $this->db->Update($fData, 'cart_product', $Where);
    }

// </editor-fold>
    // <editor-fold defaultstate="collapsed" desc="History">
    public function GetHistory() {
        $Where = array(
            new DBField('UserID', $this->pUser['ID'], PDO::PARAM_INT, 'c'),
            new DBField('State', '0', PDO::PARAM_INT, 'c', '>'),
        );
        return $this->db->Select('cart c', 'c.*,
            (Select COUNT(cp.ID) From cart_product cp Where cp.CartID = c.ID LIMIT 1) AS ProductsCount', $Where, 'c.ID DESC');
    }

    public function GetHistoryProducts($ID) {
        $Where = array(
            new DBField('LanguageID', $this->Language['ID'], PDO::PARAM_INT, 'tl'),
            new DBField('LanguageID', $this->Language['ID'], PDO::PARAM_INT, 'cl'),
            new DBField('CartID', $ID, PDO::PARAM_INT, 'cp')
        );
        return $this->db->Select($this->Table, $this->Select, $Where, 'cp.ID DESC');
    }

// </editor-fold>
}
