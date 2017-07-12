<?php

class homeModel extends Model {

    public function GetSliders() {
        return $this->GetTableWithDescription('Slider', array(new DBField('State', 1, PDO::PARAM_BOOL, 't')), 't.SortingOrder');
    }

    public function GetFeatured() {
        $_Where = array(new DBField('Featured', 1, PDO::PARAM_BOOL, 't'));
        $Where = $this->WhereProduct($_Where);
        return $this->db->Paginate($this->TableProduct, $this->SelectProduct, $Where, 't.ID', 't.ID DESC');
    }

    public function GetLast() {
        return $this->GetTableWithDescription('Product', array(new DBField('State', 1, PDO::PARAM_BOOL, 't')), 't.ID DESC', 8);
    }

    public function GetFeaturedCategories() {
        return $this->GetTableWithDescription('Category',
                array(
                    new DBField('Featured', 1, PDO::PARAM_BOOL, 't'),
                    new DBField('State', 1, PDO::PARAM_BOOL, 't')),
                't.SortingOrder', 6);
    }

    public function GetProductsByCategory($ID) {
        return $this->GetTableWithDescription('Product',
                array(
                    new DBField('CategoryID', $ID, PDO::PARAM_BOOL, 't'),
                    new DBField('State', 1, PDO::PARAM_BOOL, 't')),
                't.ID', 4);
    }

}
