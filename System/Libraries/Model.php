<?php

abstract class Model {

    public $db;
    protected $Select = '';
    protected $Table = '';


    protected $SelectProduct = "  STRAIGHT_JOIN t.*, tl.*, cl.Name AS CategoryName, bl.Name AS BrandName ";
    protected $TableProduct = " product t
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

    protected function WhereProduct($param = array()) {
        return array_merge(array(
            new DBField('LanguageID', $this->Language['ID'], PDO::PARAM_INT, 'tl'),
            new DBField('LanguageID', $this->Language['ID'], PDO::PARAM_INT, 'cl'),
            new DBField('('),
            new DBField('LanguageID', $this->Language['ID'], PDO::PARAM_INT, 'bl', '=', 'OR'),
            new DBField('LanguageID', null, PDO::PARAM_NULL, 'bl', 'IS'),
            new DBField(')'),
            new DBField('State', '1', PDO::PARAM_INT, 'c'),
            new DBField('State', '1', PDO::PARAM_INT, 't')
                ), $param);
    }

    protected function SetLevel($Level = 0, $Str = ' . ') {
        return str_repeat($Str, $Level);
    }

    public function GenerateWord($Len) {
        require_once APP_LIB . 'Generator.php';
        $Gen = new Generator();
        return $Gen->RandomID($Len);
    }

    public function Authentication($Data) {
        $Where = array(
            new DBField('UserName', $Data['UserName'], PDO::PARAM_STR),
            new DBField('Password', $Data['Password'], PDO::PARAM_STR),
            new DBField('IsActive', '1', PDO::PARAM_INT),
            new DBField('State', '1', PDO::PARAM_INT),
        );
        return $this->db->SelectRow('user', '*', $Where);
    }

    protected $SortFields = array();

    protected function GetSortValues($Sort = 'ID DESC') {
        if (isset($_GET['sort'])) {
            if (is_array($_GET['sort'])) {
                $Sort = '';
                foreach ($_GET['sort'] as $k => $v) {
                    if (in_array($k, $this->SortFields) && in_array(strtoupper($v), array('ASC', 'DESC'))) {
                        $Sort .= "$k $v,";
                    }
                }
            }
        }
        return trim($Sort, ',');
    }

    protected $SearchFields = array();

    protected function GetSearchValues($Where = array()) {
        if (isset($_GET['search'])) {
            if (is_array($_GET['search'])) {
                foreach ($_GET['search'] as $k => $v) {
                    if (($v || $v == 0) && in_array($k, $this->SearchFields)) {
                        $it = explode('.', $k);
                        $i = isset($it[1]) ? $it[1] : $it[0];
                        $t = isset($it[1]) ? $it[0] : null;
                        $op = is_numeric($v) ? '=' : 'LIKE';
                        $v = is_numeric($v) ? $v : "%$v%";
                        $Where[] = new DBField($i, $v, PDO::PARAM_STR, $t, $op);
                    }
                }
            }
        }
        return $Where;
    }

    public function GetTableWithDescription($_Table, $Where = array(), $Order = 't.ID DESC', $Limit = 0) {
        $Select = "  STRAIGHT_JOIN t.*, tl.*
        ";
        $Table = " " . strtolower($_Table) . " t
        INNER JOIN
		" . strtolower($_Table) . "_lang tl ON tl." . $_Table . "ID = t.ID
        ";
        $Where[] = new DBField('LanguageID', $this->Language['ID'], PDO::PARAM_INT, 'tl');
        return $Limit == 1 ?
                $this->db->SelectRow($Table, $Select, $Where, $Order) :
                $this->db->Select($Table, $Select, $Where, $Order, null, $Limit);
    }

    public function GetCategories($ParentID = null) {
        $Data = array();
        $Sql = "SELECT DISTINCT category.*,
                category_lang.*,
                (Select Count(o.ID) From product o Where o.CategoryID = category.ID AND o.State = 1) AS ItemsCount
		FROM    (   category_lang category_lang
		INNER JOIN
		language language
		ON (category_lang.LanguageID = language.ID))
		INNER JOIN
		category category
		ON (category_lang.CategoryID = category.ID)
		LEFT JOIN category cd ON (category.ID = cd.ID)
		WHERE category.State = 1 AND (language.ID = " . $this->Language['ID'] . ")
		AND category.ParentID ";
        $Sql .= (($ParentID == null) ? 'is null' : '= ' . $ParentID);
        $Sql .= " ORDER BY category.SortingOrder ASC";
        $Rows = $this->db->GetRows($Sql);
        foreach ($Rows as $Row) {
            $Row['IsParent'] = $this->CategoryIsParent($Row['ID']);
            $Row['PathName'] = $Row['ParentID'] ? $this->GetGategoryByPath($Row['ID']) : array($Row);
            $Data[] = $Row;
            $Data = array_merge($Data, $this->GetCategories($Row['ID']));
        }
        return $Data;
    }

    public function GetGategoryByPath($ID, $str = array()) {
        $Data = $this->GetTableWithDescription('Category', array(
            new DBField('ID', $ID, PDO::PARAM_INT, 't')
                ), 't.SortingOrder', 1);
        if ($Data['ParentID']) {
            $str = $this->GetGategoryByPath($Data['ParentID']);
        }
        return ($Data ? array_merge($str, array($Data)) : array());
    }

    protected function CategoryIsParent($ID) {
        $Sql = "Select ID From category Where ParentID = '$ID' Limit 1";
        return ($this->db->GetRow($Sql)) ? true : false;
    }

    public function GetMenus($Parent = null) {
        $Data = array();
        $Sql = "SELECT DISTINCT menu.*,
                menu_lang.*
		FROM    (   menu_lang menu_lang
		INNER JOIN
		language language
		ON (menu_lang.LanguageID = language.ID))
		INNER JOIN
		menu menu
		ON (menu_lang.MenuID = menu.ID)
		LEFT JOIN menu cd ON (menu.ID = cd.ID)
		WHERE menu.State = 1 AND (language.ID = " . $this->Language['ID'] . ")
		AND menu.ParentID ";
        $Sql .= (($Parent == null) ? 'is null' : "= '$Parent'");
        $Sql .= " ORDER BY menu.SortingOrder ASC";
        $Rows = $this->db->GetRows($Sql);
        foreach ($Rows as $Row) {
            $Row['PathName'] = $Row['ParentID'] ? $this->GetMenuByPath($Row['ID']) : array($Row);
            $Row['IsParent'] = $this->MenuIsParent($Row['ID']);
            $Row['TopLevel'] = ($Row['ParentID'] ? false : true);
            $Data[] = $Row;
            $Data = array_merge($Data, $this->GetMenus($Row['ID']));
        }
        return $Data;
    }

    public function GetMenuByPath($ID, $str = array()) {
        $Data = $this->GetTableWithDescription('Menu', array(
            new DBField('ID', $ID, PDO::PARAM_INT, 't')
                ), 't.SortingOrder', 1);
        if ($Data['ParentID']) {
            $str = $this->GetGategoryByPath($Data['ParentID']);
        }
        return ($Data ? array_merge($str, array($Data)) : array());
    }

    protected function MenuIsParent($ID) {
        $Sql = "Select ID From menu Where ParentID = $ID Limit 1";
        return ($this->db->GetRow($Sql)) ? true : false;
    }

    public function GetAllPages() {
        return $this->GetTableWithDescription('Page', array(new DBField('State', 1, PDO::PARAM_INT, 't')), 't.SortingOrder');
    }

    public function GetAllBanners() {
        $Sql = "SELECT *
                From banner
                Where State = 1";
        return $this->db->GetRows($Sql);
    }

    public function GetBanners($pos) {
        $Sql = "SELECT *
                From banner
                Where BannerPosition = '$pos' AND State = 1";
        return $this->db->GetRows($Sql);
    }

}
