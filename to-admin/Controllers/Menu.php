<?php

class MenuController extends ControllerAdmin {

    protected function BeforeGetForm() {
        $this->Data['dMenusList'] = $this->GetMenus();
        $this->Data['dMenuItemTypes'] = $this->LoadDropDown($this->_['_MenuItemTypesList']);
    }

    private function GetMenus() {
        $Data = $this->Model->GetAll();
        $Output = '<option value="0">' . $this->_['_NotFound'] . '</option>';
        foreach ($Data as $Item) {
            $PathName = array_column($Item['PathName'], 'Name');
            $Output .= '<option value="' . $Item['ID'] . '">' . implode(' &gt;&gt; ', $PathName) . '</option>';
        }
        return $Output;
    }

    protected function Validation() {
        $Data = $this->GetData();
        $Valid = array(
            new ErrorField('Link', 'Link', $Data['Link'], true),
            new ErrorField('SortingOrder', 'SortingOrder', $Data['SortingOrder'], false, FieldType::Integer),
            new ErrorField('State', 'State', $Data['State'], true, FieldType::Bool)
        );

        foreach ($this->Data['dLangs'] as $lng) {
            $Valid[] = new ErrorField('Name-' . $lng['ID'], 'Name', $Data['Name-' . $lng['ID']], true, NULL, 50);
        }

        return $this->DoValidation($Valid);
    }

}
