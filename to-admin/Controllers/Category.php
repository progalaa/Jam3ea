<?php

class CategoryController extends ControllerAdmin {

    protected function BeforeGetForm() {
        $this->Data['dCategoriesList'] = $this->GetCategoriesList();
    }

    private function GetCategoriesList() {
        $Categories = $this->Model->GetCategories();
        $Output = '<option value="0">' . $this->_['_NotFound'] . '</option>';
        foreach ($Categories as $Category) {
            $PathName = array_column($Category['PathName'], 'Name');
            $Output .= '<option value="' . $Category['ID'] . '">' . implode(' &gt;&gt; ', $PathName) . '</option>';
        }
        return $Output;
    }

    protected function Validation() {
        $Data = $this->GetData();
        $Valid = array(
            new ErrorField('SortingOrder', 'SortingOrder', $Data['SortingOrder'], false, FieldType::Integer),
            new ErrorField('Featured', 'Featured', $Data['Featured'], true, FieldType::Bool),
            new ErrorField('State', 'State', $Data['State'], true, FieldType::Bool)
        );

        foreach ($this->Data['dLangs'] as $lng) {
            $Valid[] = new ErrorField('Name-' . $lng['ID'], 'Name', $Data['Name-' . $lng['ID']], true, NULL, 500);
            $Valid[] = new ErrorField('Alias-' . $lng['ID'], 'Alias', $Data['Alias-' . $lng['ID']], true, NULL);
        }

        return $this->DoValidation($Valid);
    }

}
