<?php

/**
 * Description of product
 *
 * @author MrAbdelrahman10
 */
class productModel extends Model {

    protected $Select = "  STRAIGHT_JOIN t.*, tl.*, cl.Name AS CategoryName, bl.Name AS BrandName ";
    protected $Table = " product t
        INNER JOIN
		product_lang tl ON tl.ProductID = t.ID
        INNER JOIN
		category c ON c.ID = t.CategoryID
        INNER JOIN
		category_lang cl ON cl.CategoryID = t.CategoryID
        LEFT JOIN
		brand_lang bl ON bl.BrandID = t.BrandID
        ";

    protected function BaseSql($param = null) {
        return $this->Select . ' From ' . $this->Table . " $param";
    }

    protected function Where($param = array()) {
        return $this->WhereProduct($param);
    }

    public function GetByID($ID) {
        $Where = $this->Where(array(
            new DBField('ID', $ID, PDO::PARAM_INT, 't')
        ));
        return $this->db->SelectRow($this->Table, $this->Select, $Where);
    }

    public function UpdateViewed($ID) {
        $Sql = "Update product SET Viewed = (Viewed + 1) WHERE ID = '$ID' LIMIT 1";
        $this->db->RunQuery($Sql);
        return $this->db->AffectedRows();
    }

    public function GetByCategory($ID) {
        $Where = $this->Where(array(
            new DBField('CategoryID', $ID, PDO::PARAM_INT, 't')
        ));
        return $this->db->Paginate($this->Table, $this->Select, $Where, 't.ID', 't.Code ASC', null);
    }

    public function GetRelated($ID) {
        $Where = $this->Where(array(
            new DBField('CategoryID', $ID, PDO::PARAM_INT, 't')
        ));
        return $this->db->Select($this->Table, $this->Select, $Where, 'RAND()', null, 8);
    }

    public function GetAll($q = null) {
        $_Where = array();
        if ($q) {
            $_Where[] = new DBField('(');
            $_Where[] = new DBField('Name', "%{$q}%", PDO::PARAM_STR, 'tl', 'LIKE', 'OR');
            $_Where[] = new DBField(')');
        }
        $Where = $this->Where($_Where);
        return $this->db->Paginate($this->Table, $this->Select, $Where, 't.ID', 't.ID DESC');
    }

    public function AddComment($Data) {
        $Sql = "Insert Into comment Set ArticleID = '$Data[ArticleID]', " .
                (GetValue($Data['UserID']) ? "UserID = '$Data[UserID]'," :
                        "VisitorName = '$Data[VisitorName]',"
                        . "vEmail = '$Data[vEmail]',")
                . " Title = '$Data[cTitle]',
                Contents = '$Data[cContents]',
                CreatedDate = NOW(), State = 0";
        $this->db->RunQuery($Sql);
        return $this->db->InsertedID();
    }

    public function GetComments($ID) {
        $Sql = "Select c.*, u.UserName, u.Picture From comment c
		LEFT JOIN
		user u
		ON (c.UserID = u.ID)
                Where c.ProductID = '$ID' And c.State = 1";
        return $this->db->GetRows($Sql);
    }

    public function GetLatest() {
        $Where = array(
            new DBField('State', '1', PDO::PARAM_INT, 't')
        );
        return $this->db->Select($this->Table, $this->Select, $Where, 't.ID DESC', null, 20);
    }

}
