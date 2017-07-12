<?php

/**
 * Description of cart
 *
 * @author mrabdelrahman10
 */
class checkoutModel extends Model {

    // <editor-fold defaultstate="collapsed" desc="Buy">

    public $LastCartID = 0;

    private function GetLastCart() {
        if (!$this->LastCartID) {
            if ($this->pUser) {
                $Where[] = new DBField('UserID', $this->pUser['ID'], PDO::PARAM_INT);
                $Where[] = new DBField('State', '0', PDO::PARAM_BOOL);
                $Data = $this->db->SelectRow('cart', 'ID', $Where);
                $this->LastCartID = $Data ? $Data['ID'] : null;
            } elseif (isset($this->VisitorData)) {
                $fData = array(
                    new DBField('VisitorData', $this->VisitorData, PDO::PARAM_INT),
                    new DBField('CreatedDate', GetDateValue(), PDO::PARAM_STR),
                    new DBField('State', '0', PDO::PARAM_BOOL),
                );
                $this->LastCartID = $this->db->Insert($fData, 'cart');
            }
        }
        return $this->LastCartID;
    }

    public function FinishCart($Payment_Method) {
        $fData = array();
        $fData[] = new DBField('Payment_Method', $Payment_Method, PDO::PARAM_INT);
        $fData[] = new DBField('State', '1', PDO::PARAM_INT);
        $fData[] = new DBField('ModifiedDate', GetDateValue());
        $Where = array(
            new DBField('ID', $this->GetLastCart(), PDO::PARAM_INT),
        );
        return $this->db->Update($fData, 'cart', $Where);
    }

    public function GetLastCartData() {
        $Where = array(
            new DBField('ID', $this->GetLastCart(), PDO::PARAM_INT),
        );
        return $this->db->SelectRow('cart', '*', $Where);
    }

    public function AddPayment($Data) {
        $fData = array(
            new DBField('CartID', $Data['CartID']? : null, PDO::PARAM_NULL),
            new DBField('PaymentID', $Data['PaymentID']),
            new DBField('Amount', $Data['Amount'], PDO::PARAM_INT),
            new DBField('ResultCode', $Data['Result']),
            new DBField('TransactionID', $Data['TranID'], PDO::PARAM_INT),
            new DBField('Auth', $Data['Auth']),
            new DBField('TrackID', $Data['TrackID'], PDO::PARAM_INT),
            new DBField('RefNo', $Data['Ref'], PDO::PARAM_INT),
            new DBField('CreatedDate', GetDateValue())
        );

        return $this->db->Insert($fData, 'cart_payment');
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

    public function GetAll() {
        $Where = array(
            new DBField('LanguageID', $this->Language['ID'], PDO::PARAM_INT, 'tl'),
            new DBField('LanguageID', $this->Language['ID'], PDO::PARAM_INT, 'cl'),
            new DBField('CartID', $this->GetLastCart(), PDO::PARAM_INT, 'cp')
        );
        return $this->db->Select($this->Table, $this->Select, $Where, 'cp.ID DESC');
    }

    public function GetAllByIDs($Data) {
        $Select = "  STRAIGHT_JOIN t.*, tl.*, cl.Name AS CategoryName  ";
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

    public function UpdateProductData() {
        $TotalPrice = 0;
        $Products = $this->GetAll();
        foreach ($Products as $i) {
            $Where = array(
                new DBField('CartID', $this->GetLastCart(), PDO::PARAM_INT),
                new DBField('ProductID', $i['ID'], PDO::PARAM_INT)
            );
            $uData = array(
                new DBField('Price', $i['Price'], PDO::PARAM_INT),
                new DBField('ModifiedDate', GetDateValue())
            );
            $this->db->Update($uData, 'cart_product', $Where);
            $TotalPrice += $i['Price'] * $i['cQuantity'];
        }


        $Where = array(
            new DBField('ID', $this->GetLastCart(), PDO::PARAM_INT),
        );
        $uData = array(
            new DBField('OrderDate', GetDateValue()),
            new DBField('TotalPrice', $TotalPrice + $this->Settings['sShippingCost'], PDO::PARAM_INT),
        );
        $this->db->Update($uData, 'cart', $Where);
    }

    public function Add($Data) {
        $fData = array(
            new DBField('CartID', $this->GetLastCart(), PDO::PARAM_INT),
            new DBField('ProductID', $Data['Product'], PDO::PARAM_INT),
            new DBField('Quantity', $Data['Quantity'], PDO::PARAM_INT),
            new DBField('CreatedDate', GetDateValue(), PDO::PARAM_STR)
        );
        return $this->db->Insert($fData, 'cart_product');
    }

    // </editor-fold>
}
