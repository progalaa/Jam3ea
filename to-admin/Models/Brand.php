<?php

class BrandModel extends ModelAdmin implements IModelAdmin {

    /**
     *
     */
    protected $TableName = 'Brand';
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
    protected $DefaultSortField = 'SortingOrder ASC';
    protected $Select = "  STRAIGHT_JOIN t.*, tl.*
        ";
    protected $Table = " brand t
        INNER JOIN
		brand_lang tl ON tl.BrandID = t.ID
        ";

    protected function GetData($Data) {
        return array(
            new DBField('Picture', $Data['Picture']),
            new DBField('SortingOrder', $Data['SortingOrder'], PDO::PARAM_INT),
            new DBField('State', $Data['State'], PDO::PARAM_BOOL)
        );
    }

    protected function GetLangData($Data, $lng) {
        return array(
            new DBField('BrandID', $Data['ID'], PDO::PARAM_INT),
            new DBField('LanguageID', $lng['ID'], PDO::PARAM_INT),
            new DBField('Name', $Data['Name-' . $lng['ID']], PDO::PARAM_STR),
            new DBField('Description', $Data['Description-' . $lng['ID']], PDO::PARAM_STR),
        );
    }

}
