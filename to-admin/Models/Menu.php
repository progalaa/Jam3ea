<?php

class MenuModel extends ModelAdmin implements IModelAdmin {

    /**
     *
     */
    protected $TableName = 'Menu';
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
    protected $Table = " menu t
        INNER JOIN
		menu_lang tl ON tl.MenuID = t.ID
        ";

    protected function GetData($Data) {
        return array(
            new DBField('ParentID', $Data['Parent']? : null, PDO::PARAM_NULL),
            new DBField('MenuItemTypeID', $Data['MenuItemType'], PDO::PARAM_INT),
            new DBField('Picture', $Data['Picture']? : null, PDO::PARAM_INT),
            new DBField('Link', $Data['Link'], PDO::PARAM_STR),
            new DBField('SortingOrder', $Data['SortingOrder'], PDO::PARAM_INT),
            new DBField('State', $Data['State'], PDO::PARAM_BOOL)
        );
    }

    protected function GetLangData($Data, $lng) {
        return array(
            new DBField('MenuID', $Data['ID'], PDO::PARAM_INT),
            new DBField('LanguageID', $lng['ID'], PDO::PARAM_INT),
            new DBField('Name', $Data['Name-' . $lng['ID']], PDO::PARAM_STR),
            new DBField('Description', $Data['Description-' . $lng['ID']], PDO::PARAM_STR)
        );
    }

    public function GetAll() {
        return $this->GetMenus();
    }

}
