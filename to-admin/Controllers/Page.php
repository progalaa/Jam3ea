<?php

class PageController extends ControllerAdmin {

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

    protected function Filter($_Value, $htmlDecode = false) {
        return $_Value;
        if ($htmlDecode == true) {
            return $_Value;
        }
        return addslashes(strip_tags($_Value));
    }

    protected function Validation() {
        $Data = $this->GetData();
        $Valid = array(
            new ErrorField('SortingOrder', 'SortingOrder', $Data['SortingOrder'], false, FieldType::Integer),
            new ErrorField('State', 'State', $Data['State'], true, FieldType::Bool)
        );

        foreach ($this->Data['dLangs'] as $lng) {
            $Valid[] = new ErrorField('Title-' . $lng['ID'], 'Title', $Data['Title-' . $lng['ID']], true, NULL, 500);
            $Valid[] = new ErrorField('Alias-' . $lng['ID'], 'Alias', $Data['Alias-' . $lng['ID']], true, NULL);
            $Valid[] = new ErrorField('Contents-' . $lng['ID'], 'Contents', $Data['Contents-' . $lng['ID']], true);
        }

        return $this->DoValidation($Valid);
    }

}

?>