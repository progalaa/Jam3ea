<?php

class CategoryModel extends ModelAdmin implements IModelAdmin {

    /**
     *
     */
    protected $TableName = 'Category';
    protected $SearchFields = array(
        't.ID',
        'tl.Name',
        't.State'
    );
    protected $SortFields = array(
        'ID',
        'Name',
        'CreatedDate',
        'State'
    );
    protected $Select = "  STRAIGHT_JOIN t.*, tl.*
        ";
    protected $Table = " category t
        INNER JOIN
		category_lang tl ON tl.CategoryID = t.ID
        ";

    protected function GetData($Data) {
        return array(
            new DBField('ParentID', $Data['Parent']? : null, PDO::PARAM_NULL),
            new DBField('Picture', $Data['Picture']? : null, PDO::PARAM_INT),
            new DBField('SortingOrder', $Data['SortingOrder'], PDO::PARAM_INT),
            new DBField('Featured', $Data['Featured'], PDO::PARAM_BOOL),
            new DBField('State', $Data['State'], PDO::PARAM_BOOL)
        );
    }

    protected function GetLangData($Data, $lng) {
        return array(
            new DBField('CategoryID', $Data['ID'], PDO::PARAM_INT),
            new DBField('LanguageID', $lng['ID'], PDO::PARAM_INT),
            new DBField('Name', $Data['Name-' . $lng['ID']], PDO::PARAM_STR),
            new DBField('Alias', CleanUrl($Data['Alias-' . $lng['ID']]), PDO::PARAM_STR),
            new DBField('Keywords', $Data['Keywords-' . $lng['ID']], PDO::PARAM_STR),
            new DBField('Description', $Data['Description-' . $lng['ID']], PDO::PARAM_STR),
        );
    }

    public function GetAll() {
        return $this->GetCategories();
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
		WHERE (language.ID = " . $this->Language['ID'] . ")
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

}
