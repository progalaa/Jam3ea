<?php

/**
 * Description of offer
 *
 * @author mrabdelrahman10
 */
class wishlistModel extends Model {


    protected $Select = "  STRAIGHT_JOIN t.*, tl.*, cl.Name AS CategoryName ";
    protected $Table = " product t
        INNER JOIN
		product_lang tl ON tl.ProductID = t.ID
        INNER JOIN
		category_lang cl ON cl.CategoryID = t.CategoryID
        INNER JOIN
		wishlist w ON w.ProductID = t.ID
        ";

    public function Add($ProductID) {
        $fData = array(
            new DBField('UserID', $this->pUser['ID'], PDO::PARAM_INT),
            new DBField('ProductID', $ProductID, PDO::PARAM_INT),
            new DBField('CreatedDate', GetDateValue(), PDO::PARAM_STR)
        );
        return $this->db->InsertExists($fData, 'wishlist');
    }

    public function GetAll() {
        $Where = array(
            new DBField('LanguageID', $this->Language['ID'], PDO::PARAM_INT, 'tl'),
            new DBField('LanguageID', $this->Language['ID'], PDO::PARAM_INT, 'cl'),
            new DBField('UserID', $this->pUser['ID'], PDO::PARAM_INT, 'w')
        );
        return $this->db->Select($this->Table, $this->Select, $Where, 'w.ID DESC');
    }

    public function GetItemsCount() {
        $Where = array(
            new DBField('UserID', $this->pUser['ID'], PDO::PARAM_INT)
        );
        return $this->db->RowsCount('wishlist', 'ID', $Where);
    }

    public function GetAllIDs() {
        $Where = array(
            new DBField('UserID', $this->pUser['ID'], PDO::PARAM_INT)
        );
        return $this->db->SelectColumn('wishlist', 'ProductID', $Where);
    }

    public function Delete($ID) {
        $Where = array(
            new DBField('UserID', $this->pUser['ID'], PDO::PARAM_INT),
            new DBField('ProductID', $ID, PDO::PARAM_INT)
        );
        return $this->db->Delete('wishlist', $Where);
    }

}