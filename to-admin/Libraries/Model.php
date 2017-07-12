<?php

/**
 *
 */
interface IModelAdmin {

}

abstract class ModelAdmin extends Model {

    public function Authentication($Data) {
        $Where = array(
            new DBField('UserName', $Data['AdminUserName'], PDO::PARAM_STR, 'u'),
            new DBField('Password', $Data['AdminPassword'], PDO::PARAM_STR, 'u'),
            new DBField('IsActive', '1', PDO::PARAM_INT, 'u'),
            new DBField('('),
            new DBField('IsAdmin', '1', PDO::PARAM_INT, 'a', '=', 'OR'),
            new DBField('IsEditor', '1', PDO::PARAM_INT, 'a'),
            new DBField(')')
        );
        $Row = $this->db->SelectRow('user u
		INNER JOIN
		adminuser a ON a.UserID = u.ID', 'u.*, a.IsAdmin, a.IsEditor, a.Permission', $Where);
        if ($Row) {
            return $Row;
        }
        return null;
    }

    protected $LanguageFields = array();
    protected $DefaultSortField = 'ID DESC';

    protected abstract function GetLangData($Data, $lng);

    protected function BaseSql($param = null) {
        return $this->Select . ' From ' . $this->Table . " $param";
    }

    public function GetAutoComplete($Table, $Field, $Q, $IsLang = true) {
        $Where = array(new DBField($Field, "%" . $Q . "%", PDO::PARAM_STR, ($IsLang ? 't' : null), 'LIKE'));
        return $IsLang ? $this->GetTableWithDescription($Table, $Where, null, 10) : $this->db->Select(strtolower($Table), '*', $Where, null, null, 10);
    }

    public function Add($Data) {
        $sData = $this->GetData($Data);
        $sData[] = new DBField('CreatedDate', GetDateValue(), PDO::PARAM_STR);
        $Data['ID'] = $this->db->Insert($sData, strtolower($this->TableName));
        foreach ($this->Languages as $lng) {
            $fData = $this->GetLangData($Data, $lng);
            $this->db->Insert($fData, strtolower($this->TableName) . '_lang');
        }
    }

    public function Edit($Data) {
        $sData = $this->GetData($Data);
        $Where = array(
            new DBField('ID', $Data['ID'], PDO::PARAM_INT)
        );
        $this->db->Update($sData, strtolower($this->TableName), $Where);

        foreach ($this->Languages as $lng) {
            $fData = $this->GetLangData($Data, $lng);
            $this->db->InsertExists($fData, strtolower($this->TableName) . '_lang');
        }
    }

    public function Delete($ID) {
        $Where = array(
            new DBField('ID', $ID, PDO::PARAM_INT)
        );
        return $this->db->Delete(strtolower($this->TableName), $Where);
    }

    public function GetAll() {
        $__rowsNum = intval(Session::Get('Admin.rowsNum'));
        if (Request::IsPost()) {
            $__rowsNum = intval(GetValue($_POST['rowsNum']));
            Session::Set('Admin.rowsNum', $__rowsNum);
        }
        $rowsNum = $__rowsNum? : 50;
        $Where = array(
            new DBField('LanguageID', $this->Language['ID'], PDO::PARAM_INT, 'tl')
        );
        if (count($this->LanguageFields) && is_array($this->LanguageFields)) {
            foreach ($this->LanguageFields as $lf) {
                $Where[] = new DBField('LanguageID', $this->Language['ID'], PDO::PARAM_INT, $lf);
            }
        }
        return $this->db->Paginate($this->Table, $this->Select, $this->GetSearchValues($Where), 't.ID', $this->GetSortValues($this->DefaultSortField), null, $rowsNum);
    }

    public function GetByID($ID) {
        $Where = array(
            new DBField('ID', $ID, PDO::PARAM_INT, $this->LanguageFields ? 't' : null)
        );
        $Data = $this->db->SelectRow($this->LanguageFields ? $this->Table : strtolower($this->TableName), $this->LanguageFields ? $this->Select : '*', $Where);
        foreach ($this->Languages as $lng) {
            $Where = array(
                new DBField($this->TableName . 'ID', $Data['ID'], PDO::PARAM_INT, 'tl'),
                new DBField('LanguageID', $lng['ID'], PDO::PARAM_INT, 'tl')
            );
            $Dlang = $this->db->SelectRow($this->Table, $this->Select, $Where);
            $Data = is_array($Data) ? array_merge($Data, $this->GetDescriptionFeilds($Dlang, $lng['ID'])) : $this->GetDescriptionFeilds($Dlang, $lng['ID']);
        }
        return $Data;
    }

    public function SetState($ID, $State) {
        $fData = array(
            new DBField('State', $State, PDO::PARAM_BOOL)
        );
        $Where = array(
            new DBField('ID', $ID, PDO::PARAM_INT)
        );
        $this->db->Update($fData, strtolower($this->TableName), $Where);
    }

    public function SetSort($ID, $Sort) {
        $fData = array(
            new DBField('SortingOrder', $Sort + 1, PDO::PARAM_INT)
        );
        $Where = array(
            new DBField('ID', $ID, PDO::PARAM_INT)
        );
        $this->db->Update($fData, strtolower($this->TableName), $Where);
    }

    protected function GetDescriptionFeilds($Dlang, $lID) {
        $Data = array();
        if ($Dlang) {
            foreach ($Dlang as $k => $v) {
                $Data[$k . '-' . $lID] = $v;
            }
        }
        return $Data;
    }

}
