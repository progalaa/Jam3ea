<?php

class SliderController extends ControllerAdmin {

    protected function Validation() {
        $Data = $this->GetData();
        $Valid = array(
            new ErrorField('SortingOrder', 'SortingOrder', $Data['SortingOrder'], false, FieldType::Integer),
            new ErrorField('State', 'State', $Data['State'], true, FieldType::Bool)
        );

        foreach ($this->Data['dLangs'] as $lng) {
            $Valid[] = new ErrorField('Title-' . $lng['ID'], 'Title', $Data['Title-' . $lng['ID']], false, NULL, 200);
        }

        return $this->DoValidation($Valid);
    }

}
