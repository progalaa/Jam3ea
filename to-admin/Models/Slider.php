<?php

class SliderModel extends ModelAdmin implements IModelAdmin {

    /**
     *
     */
    protected $TableName = 'Slider';
    protected $SearchFields = array(
        't.ID',
        'tl.Title',
        't.State'
    );
    protected $SortFields = array(
        'ID',
        'Title',
        'CreatedDate',
        'State'
    );
    protected $DefaultSortField = 'SortingOrder ASC';
    protected $Select = "  STRAIGHT_JOIN t.*, tl.*
        ";
    protected $Table = " slider t
        INNER JOIN
		slider_lang tl ON tl.SliderID = t.ID
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
            new DBField('SliderID', $Data['ID'], PDO::PARAM_INT),
            new DBField('LanguageID', $lng['ID'], PDO::PARAM_INT),
            new DBField('Title', $Data['Title-' . $lng['ID']], PDO::PARAM_STR),
            new DBField('Description', $Data['Description-' . $lng['ID']], PDO::PARAM_STR),
            new DBField('Url', $Data['Url-' . $lng['ID']], PDO::PARAM_STR),
        );
    }

}
