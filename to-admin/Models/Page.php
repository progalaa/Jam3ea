<?php

class PageModel extends ModelAdmin implements IModelAdmin {

    /**
     *
     */
    protected $TableName = 'Page';

    protected $SearchFields = array(
        't.ID',
        'tl.Title',
        'UserName',
        't.Viewed',
        't.CreatedDate',
        't.State'
    );

    protected $SortFields = array(
        'ID',
        'Title',
        'Viewed',
        'CreatedDate',
        'State'
    );

    protected $DefaultSortField = 'SortingOrder ASC';

    protected $Select = "  STRAIGHT_JOIN t.*, tl.*
        ";
    protected $Table = " page t
        INNER JOIN
		page_lang tl ON tl.PageID = t.ID
        ";

    protected function GetData($Data) {
        return array(
            new DBField('SortingOrder', $Data['SortingOrder'], PDO::PARAM_INT),
            new DBField('State', $Data['State'], PDO::PARAM_BOOL)
        );
    }

    protected function GetLangData($Data, $lng) {
        return array(
            new DBField('PageID', $Data['ID'], PDO::PARAM_INT),
            new DBField('LanguageID', $lng['ID'], PDO::PARAM_INT),
            new DBField('Title', $Data['Title-' . $lng['ID']], PDO::PARAM_STR),
            new DBField('Alias', CleanUrl($Data['Alias-' . $lng['ID']]), PDO::PARAM_STR),
            new DBField('Contents', $Data['Contents-' . $lng['ID']], PDO::PARAM_STR),
            new DBField('Keywords', $Data['Keywords-' . $lng['ID']], PDO::PARAM_STR),
            new DBField('Description', $Data['Description-' . $lng['ID']], PDO::PARAM_STR),
        );
    }

}
