<?php

class ProductController extends ControllerAdmin {

    protected function GetData() {
        $remSlashes = array();
        $Langs = $this->Data['dLangs'];
        foreach ($Langs as $Lng) {
            $lID = $Lng['ID'];
            $remSlashes[] = "Contents-$lID";
        }
        $Data = $this->FilterPost($remSlashes);
        foreach ($Langs as $Lng) {
            $lID = $Lng['ID'];
            $Desc = SubText(GetTextFromHTML($Data['Contents-' . $lID]), 0, 200);
            $old = trim($Data['Description-' . $lID]);
            $Data['Description-' . $lID] = empty($old) ? $Desc : $old;
        }
        return $Data;
    }

    public function AutoCompleteStore() {
        $this->AutoComplete('Store', true, 'ID', 'Name', 'Name');
    }

    protected function BeforeGetForm() {
        $this->Data['dBrandsList'] = $this->LoadDropDown($this->Model->GetBrands());
        $this->Data['dCategoriesList'] = $this->GetCategoriesList();
    }

    private function GetCategoriesList() {
        $Categories = $this->Model->GetCategories();
        $Output = '<option value="0">' . $this->_['_NotFound'] . '</option>';
        foreach ($Categories as $Category) {
            $PathName = array_column($Category['PathName'], 'Name');
            $Output .= '<option value="' . $Category['ID'] . '"'
                    . ($Category['IsParent'] ? ' disabled style="background-color: #0e0e0e; color: #ffffff;"' : '') . '>' . implode(' &gt;&gt; ', $PathName) . '</option>';
        }
        return $Output;
    }

    protected function Validation() {
        $Data = $this->GetData();
        $Valid = array(
            new ErrorField('CategoryID', 'Category', $Data['CategoryID'], true, FieldType::Integer),
            new ErrorField('BrandID', 'Brand', $Data['BrandID'], false, FieldType::Integer),
            new ErrorField('Price', 'Price', $Data['Price'], true, FieldType::Integer),
            new ErrorField('Quantity', 'Quantity', $Data['Quantity'], true, FieldType::Integer),
//            new ErrorField('Code', 'Code', $Data['Code'], true, FieldType::String, 8, 1, $this->Model->CheckCode($Data['Code'], GetValue($this->Data['pParameter'], 0)), false),
        );

        foreach ($this->Data['dLangs'] as $lng) {
            if ($lng['ID'] == 1) {
                $Valid[] = new ErrorField('Name-' . $lng['ID'], 'Name', $Data['Name-' . $lng['ID']], true, NULL, 200);
                $Valid[] = new ErrorField('Contents-' . $lng['ID'], 'Contents', $Data['Contents-' . $lng['ID']], true);
            }
        }

        return $this->DoValidation($Valid);
    }

}
