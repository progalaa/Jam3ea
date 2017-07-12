<?php

class ProductModel extends ModelAdmin implements IModelAdmin {

    /**
     *
     */
    protected $TableName = 'Product';
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
    protected $LanguageFields = array('cl');
    protected $Select = "  STRAIGHT_JOIN t.*, tl.*, cl.Name AS CategoryName ";
    protected $Table = " product t
        INNER JOIN
		product_lang tl ON tl.ProductID = t.ID
        INNER JOIN
		category_lang cl ON cl.CategoryID = t.CategoryID
        ";

    protected function GetData($Data) {
        return array(
            new DBField('Code', $Data['Code']),
            new DBField('SoftCode', $Data['SoftCode']),
            new DBField('CategoryID', $Data['CategoryID'], PDO::PARAM_INT),
            new DBField('OldPrice', ($Data['OldPrice']), PDO::PARAM_INT),
            new DBField('Price', $Data['Price'], PDO::PARAM_INT),
            new DBField('Quantity', $Data['Quantity'], PDO::PARAM_INT),
            new DBField('BrandID', $Data['BrandID']? : null, PDO::PARAM_NULL),
            new DBField('Picture', $Data['Picture']? : null, PDO::PARAM_NULL),
            new DBField('SliderPictures', SerializeIfData($Data['SliderPictures'])),
            new DBField('Featured', $Data['Featured'], PDO::PARAM_BOOL),
            new DBField('State', $Data['State'], PDO::PARAM_BOOL)
        );
    }

    protected function GetLangData($Data, $lng) {
        return array(
            new DBField('ProductID', $Data['ID'], PDO::PARAM_INT),
            new DBField('LanguageID', $lng['ID'], PDO::PARAM_INT),
            new DBField('Name', $Data['Name-' . $lng['ID']], PDO::PARAM_STR),
            new DBField('Alias', $Data['Alias-' . $lng['ID']], PDO::PARAM_STR),
            new DBField('Contents', $Data['Contents-' . $lng['ID']], PDO::PARAM_STR),
            new DBField('Description', $Data['Description-' . $lng['ID']], PDO::PARAM_STR),
            new DBField('Keywords', $Data['Keywords-' . $lng['ID']], PDO::PARAM_STR)
        );
    }

    public function CheckCode($Code, $ID) {
        return $this->db->GetRow('Select Code From product Where ID != ' . intval($ID) . ' AND Code = ' . $Code . ' LIMIT 1') ? true : false;
    }

    public function GetBrands() {
        return $this->GetTableWithDescription('Brand');
    }

}
