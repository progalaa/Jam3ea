<?php

class CartModel extends ModelAdmin {

    /**
     *
     */
    protected $SearchFields = array(
        'u.ID'
    );
    protected $TableName = "cart";
    protected $Table = " cart t
        LEFT JOIN
		user u ON t.UserID = u.ID
        LEFT JOIN
		user_address a ON u.ID = a.ID
        LEFT JOIN
		cart_payment py ON t.ID = py.CartID ";
    protected $Select = " STRAIGHT_JOIN py.*, a.*, t.*, u.FullName, u.UserName, u.Email, u.Mobile,
                          (Select COUNT(cp.ID) From cart_product cp Where cp.CartID = t.ID LIMIT 1) AS CountProducts,
                          (Select SUM(cp.Price) From cart_product cp Where cp.CartID = t.ID LIMIT 1) AS SumAmount
        ";

    protected function GetData($Data) {
        return null;
    }

    protected function GetLangData($Data, $lng) {

    }

    public function Add($Data) {

    }

    public function Edit($Data) {
        $sData = $this->GetData($Data);
        $Where = array(
            new DBField('ID', $Data['ID'], PDO::PARAM_INT)
        );
        $this->db->Update($sData, strtolower($this->TableName), $Where);
    }

    public function GetAll($rows = true) {
        $Where = array(
            new DBField('State', '0', PDO::PARAM_INT, 't', '>')
        );
        if (GetValue($_GET['FromDate'])) {
            $min = addslashes(urldecode($_GET['FromDate']));
            $Where[] = new DBField('OrderDate', GetDateValue($min), PDO::PARAM_STR, 't', '>=', 'AND', 'MinDate');
        }
        if (GetValue($_GET['ToDate'])) {
            $max = addslashes(urldecode($_GET['ToDate']));
            $Where[] = new DBField('OrderDate', GetDateValue($max), PDO::PARAM_STR, 't', '<=', 'AND', 'MaxDate');
        }

        if (GetValue($_GET['User'])) {
            $Where[] = new DBField('(');
            $Where[] = new DBField('FullName', '%' . addslashes($_GET['User']) . '%', PDO::PARAM_STR, 'u', 'LIKE', 'OR');
            $Where[] = new DBField('UserName', '%' . addslashes($_GET['User']) . '%', PDO::PARAM_STR, 'u', 'LIKE', 'OR');
            $Where[] = new DBField('Mobile', '%' . addslashes($_GET['User']) . '%', PDO::PARAM_STR, 'u', 'LIKE', 'OR');
            $Where[] = new DBField('VisitorData', '%' . addslashes($_GET['User']) . '%', PDO::PARAM_STR, 't', 'LIKE');
            $Where[] = new DBField(')');
        }
//        return $this->db->Select($this->Table, $this->Select, $this->GetSearchValues($Where), 't.OrderDate DESC');
        if ($rows) {
            return $this->db->Paginate($this->Table, $this->Select, $this->GetSearchValues($Where), 't.ID', 't.OrderDate DESC');
        } else {
            return $this->db->RowsSum($this->Table, 't.TotalPrice', $this->GetSearchValues($Where));
        }
    }

    public function GetByID($ID) {
        $Where = array(
            new DBField('ID', $ID, PDO::PARAM_INT, 't')
        );
        $Data = $this->db->SelectRow($this->Table, $this->Select, $Where);

        $Select = "  STRAIGHT_JOIN t.*, tl.*, cl.Name AS CategoryName, cp.ID AS cpID, cp.Quantity AS cQuantity, cp.Price AS cPrice, cp.Returned ";
        $Table = " product t
        INNER JOIN
		product_lang tl ON tl.ProductID = t.ID
        INNER JOIN
		category_lang cl ON cl.CategoryID = t.CategoryID
        INNER JOIN
		cart_product cp ON cp.ProductID = t.ID
        ";
        $Where = array(
            new DBField('CartID', $ID, PDO::PARAM_INT, 'cp'),
            new DBField('LanguageID', $this->Language['ID'], PDO::PARAM_INT, 'tl'),
            new DBField('LanguageID', $this->Language['ID'], PDO::PARAM_INT, 'cl')
        );

        $Data['Products'] = $this->db->Select($Table, $Select, $Where, 'cl.Name');
        return $Data;
    }

    public function GetLog() {
        $Select = "  STRAIGHT_JOIN t.*, tl.*, cl.Name AS CategoryName, cp.Quantity AS cQuantity, cp.Price AS cPrice,
                     u.FullName, u.UserName, u.Email, u.Mobile
            ";
        $Table = " product t
        INNER JOIN
		product_lang tl ON tl.ProductID = t.ID
        INNER JOIN
		category_lang cl ON cl.CategoryID = t.CategoryID
        INNER JOIN
		cart_product cp ON cp.ProductID = t.ID
        INNER JOIN
		cart cr ON cp.CartID = cr.ID
        LEFT JOIN
		user u ON cr.UserID = u.ID
        ";
        $Where = array(
            new DBField('LanguageID', $this->Language['ID'], PDO::PARAM_INT, 'tl'),
            new DBField('LanguageID', $this->Language['ID'], PDO::PARAM_INT, 'cl'),
            new DBField('State', '0', PDO::PARAM_BOOL, 'cr', '>')
        );

        if (GetValue($_GET['ProductID'])) {
            $Where[] = new DBField('ID', intval($_GET['ProductID']), PDO::PARAM_INT, 't');
        }

        if (GetValue($_GET['CategoryID'])) {
            $Where[] = new DBField('CategoryID', intval($_GET['CategoryID']), PDO::PARAM_INT, 't');
        }

        if (GetValue($_GET['FromDate'])) {
            $Where[] = new DBField('OrderDate', GetDateValue($_GET['FromDate']), PDO::PARAM_STR, 'cr', '>', 'AND', 'FromOrder');
        }

        if (GetValue($_GET['ToDate'])) {
            $Where[] = new DBField('OrderDate', GetDateValue($_GET['ToDate']), PDO::PARAM_STR, 'cr', '<', 'AND', 'ToOrder');
        }

        if (GetValue($_GET['User'])) {
            $Where[] = new DBField('(');
            $Where[] = new DBField('FullName', '%' . addslashes($_GET['User']) . '%', PDO::PARAM_STR, 'u', 'LIKE', 'OR');
            $Where[] = new DBField('UserName', '%' . addslashes($_GET['User']) . '%', PDO::PARAM_STR, 'u', 'LIKE', 'OR');
            $Where[] = new DBField('Mobile', '%' . addslashes($_GET['User']) . '%', PDO::PARAM_STR, 'u', 'LIKE', 'OR');
            $Where[] = new DBField('VisitorData', '%' . addslashes($_GET['User']) . '%', PDO::PARAM_STR, 'cr', 'LIKE');
            $Where[] = new DBField(')');
        }


        if (isset($_GET['ByProduct'])) {
            $Select = " STRAIGHT_JOIN t.*, tl.*, cl.Name AS CategoryName,
                     u.FullName, u.UserName, u.Email, u.Mobile,
                     (Select SUM(tcpup.Price) From cart_product tcpup
                        INNER JOIN
                        cart tcup ON tcpup.CartID = tcup.ID Where tcpup.ProductID = t.ID
                     " .
                    (GetValue($_GET['FromDate']) ? " AND tcup.OrderDate >= '" . GetDateValue($_GET['FromDate']) . "'" : '') .
                    (GetValue($_GET['ToDate']) ? " AND tcup.OrderDate <= '" . GetDateValue($_GET['ToDate']) . "'" : '') .
                    " LIMIT 1) AS cPrice,
                     (Select SUM(tcpuq.Quantity) From cart_product tcpuq
                        INNER JOIN
                        cart tcuq ON tcpuq.CartID = tcuq.ID Where tcpuq.ProductID = t.ID
                     " .
                    (GetValue($_GET['FromDate']) ? " AND tcuq.OrderDate >= '" . GetDateValue($_GET['FromDate']) . "'" : '') .
                    (GetValue($_GET['ToDate']) ? " AND tcuq.OrderDate <= '" . GetDateValue($_GET['ToDate']) . "'" : '') .
                    " LIMIT 1) AS cQuantity
        ";
        }

        if (isset($_GET['ByUser'])) {
            $Select = " STRAIGHT_JOIN t.*, tl.*, cl.Name AS CategoryName,
                     u.FullName, u.UserName, u.Email, u.Mobile,
                     (Select SUM(tcpp.Price) From cart_product tcpp Where tcpp.ProductID = t.ID LIMIT 1) AS cPrice,
                     (Select SUM(tcpq.Quantity) From cart_product tcpq
                        INNER JOIN
                        cart tcq ON tcpq.CartID = tcq.ID
                        Where tcpq.ProductID = t.ID AND tcq.UserID = u.ID LIMIT 1) AS cQuantity
        ";
        }

        $GroupBy = '';

        if (isset($_GET['ByProduct'])) {
            $GroupBy = 't.ID';
        }

        if (isset($_GET['ByUser'])) {
//            if (isset($_GET['ByProduct'])) {
//                $GroupBy .= ', ';
//            }
//            $GroupBy .= 'cr.UserID';
        }

        return $this->db->Select($Table, 'DISTINCT ' . $Select, $Where, 'cQuantity DESC', $GroupBy);
    }

    public function GetProductLog() {
        $Select = " STRAIGHT_JOIN t.*, tl.*, cl.Name AS CategoryName,
                     (Select SUM(tcpup.Price) From cart_product tcpup
                        INNER JOIN
                        cart tcup ON tcpup.CartID = tcup.ID Where tcpup.ProductID = t.ID
                     " .
                (GetValue($_GET['FromDate']) ? " AND tcup.OrderDate >= '" . GetDateValue($_GET['FromDate']) . "'" : '') .
                (GetValue($_GET['ToDate']) ? " AND tcup.OrderDate <= '" . GetDateValue($_GET['ToDate']) . "'" : '') .
                " LIMIT 1) AS cPrice,
                     (Select SUM(tcpuq.Quantity) From cart_product tcpuq
                        INNER JOIN
                        cart tcuq ON tcpuq.CartID = tcuq.ID Where tcpuq.ProductID = t.ID
                     " .
                (GetValue($_GET['FromDate']) ? " AND tcuq.OrderDate >= '" . GetDateValue($_GET['FromDate']) . "'" : '') .
                (GetValue($_GET['ToDate']) ? " AND tcuq.OrderDate <= '" . GetDateValue($_GET['ToDate']) . "'" : '') .
                " LIMIT 1) AS cQuantity
        ";
        $Table = " product t
        INNER JOIN
		product_lang tl ON tl.ProductID = t.ID
        INNER JOIN
		category_lang cl ON cl.CategoryID = t.CategoryID
        INNER JOIN
		cart_product cp ON cp.ProductID = t.ID
        INNER JOIN
		cart cr ON cp.CartID = cr.ID
        ";
        $Where = array(
            new DBField('LanguageID', $this->Language['ID'], PDO::PARAM_INT, 'tl'),
            new DBField('LanguageID', $this->Language['ID'], PDO::PARAM_INT, 'cl'),
            new DBField('State', '0', PDO::PARAM_BOOL, 'cr', '>')
        );

        if (GetValue($_GET['ProductID'])) {
            $Where[] = new DBField('ID', intval($_GET['ProductID']), PDO::PARAM_INT, 't');
        }

        if (GetValue($_GET['CategoryID'])) {
            $Where[] = new DBField('CategoryID', intval($_GET['CategoryID']), PDO::PARAM_INT, 't');
        }

        if (GetValue($_GET['FromDate'])) {
            $Where[] = new DBField('OrderDate', GetDateValue($_GET['FromDate']), PDO::PARAM_STR, 'cr', '>', 'AND', 'FromOrder');
        }

        if (GetValue($_GET['ToDate'])) {
            $Where[] = new DBField('OrderDate', GetDateValue($_GET['ToDate']), PDO::PARAM_STR, 'cr', '<', 'AND', 'ToOrder');
        }


        $GroupBy = 't.ID';

        return $this->db->Select($Table, 'DISTINCT ' . $Select, $Where, 'cQuantity DESC', $GroupBy);
    }

    public function GetUserLog() {

        $Select = " STRAIGHT_JOIN c.*, u.FullName, u.UserName, u.Email, u.Mobile ";
        $Table = " cart c
        LEFT JOIN
		user u ON c.UserID = u.ID
        ";
        $Where = array(
            new DBField('State', '0', PDO::PARAM_BOOL, 'c', '>')
        );

        if (GetValue($_GET['FromDate'])) {
            $Where[] = new DBField('OrderDate', GetDateValue($_GET['FromDate']), PDO::PARAM_STR, 'c', '>', 'AND', 'FromOrder');
        }

        if (GetValue($_GET['ToDate'])) {
            $Where[] = new DBField('OrderDate', GetDateValue($_GET['ToDate']), PDO::PARAM_STR, 'c', '<', 'AND', 'ToOrder');
        }

        if (GetValue($_GET['User'])) {
            $Where[] = new DBField('(');
            $Where[] = new DBField('FullName', '%' . addslashes($_GET['User']) . '%', PDO::PARAM_STR, 'u', 'LIKE', 'OR');
            $Where[] = new DBField('UserName', '%' . addslashes($_GET['User']) . '%', PDO::PARAM_STR, 'u', 'LIKE', 'OR');
            $Where[] = new DBField('Mobile', '%' . addslashes($_GET['User']) . '%', PDO::PARAM_STR, 'u', 'LIKE', 'OR');
            $Where[] = new DBField('VisitorData', '%' . addslashes($_GET['User']) . '%', PDO::PARAM_STR, 'c', 'LIKE');
            $Where[] = new DBField(')');
        }

        $GroupBy = '';

        if (isset($_GET['ByUser'])) {
//            if (isset($_GET['ByProduct'])) {
//                $GroupBy .= ', ';
//            }
//            $GroupBy .= 'cr.UserID';
        }

        return $this->db->Select($Table, 'DISTINCT ' . $Select, $Where, 'c.OrderDate DESC', $GroupBy);
    }

    public function SetState($ID, $State) {

    }

//    public function Delete($ID) {
//
//    }

    public function UpdateCart($Data) {
        $sData = array(
            new DBField('ModifiedDate', GetDateValue()),
            new DBField('State', $Data['State'], PDO::PARAM_INT)
        );
        $Where = array(
            new DBField('ID', $Data['ID'], PDO::PARAM_INT)
        );
        $this->db->Update($sData, 'cart', $Where);
    }

    public function SetReturned($Data) {
        $sData = array(
            new DBField('Returned', $Data['Returned'], PDO::PARAM_INT),
            new DBField('ModifiedDate', GetDateValue())
        );
        $Where = array(
            new DBField('ID', $Data['ID'], PDO::PARAM_INT)
        );
        $this->db->Update($sData, 'cart_product', $Where);
    }

}
