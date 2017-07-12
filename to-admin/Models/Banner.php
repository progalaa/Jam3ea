<?php

class BannerModel extends ModelAdmin implements IModelAdmin {

    /**
     *
     */
    protected $Table = " banner t ";

    protected $Select = " t.* ";

    private function GetData($Data) {
        $fData = array(
            new DBField('Name', $Data['Name'], PDO::PARAM_STR),
            new DBField('BannerType', $Data['BannerType'], PDO::PARAM_STR),
            new DBField('Picture', $Data['Picture'], PDO::PARAM_STR),
            new DBField('Url', $Data['Url'], PDO::PARAM_STR),
            new DBField('BannerCode', $Data['BannerCode'], PDO::PARAM_STR),
            new DBField('State', $Data['State'], PDO::PARAM_BOOL)
        );
        return $fData;
    }

    public function Add($Data) {
        $fData = $this->GetData($Data);
        $fData[] = new DBField('CreatedDate', GetDateValue(), PDO::PARAM_STR);
        $this->db->Insert($fData, 'banner');
    }

    public function Edit($Data) {
        $fData = $this->GetData($Data);
        $fData[] = new DBField('ModifiedDate', GetDateValue(), PDO::PARAM_STR);
        $Where = array(
            new DBField('ID', $Data['ID'], PDO::PARAM_INT)
        );
        $this->db->Update($fData, 'banner', $Where);
    }

    public function GetByID($ID) {
        $Where = array(
            new DBField('ID', $ID, PDO::PARAM_INT, 't')
        );
        return $this->db->SelectRow($this->Table, $this->Select, $Where);
    }
    public function GetAll() {
        $__rowsNum = intval(Session::Get('Admin.rowsNum'));
        if (Request::IsPost()) {
            $__rowsNum = intval(GetValue($_POST['rowsNum']));
            Session::Set('Admin.rowsNum', $__rowsNum);
        }
        $rowsNum = $__rowsNum? : 50;
        $Where = array(
        );
        return $this->db->Paginate($this->Table, $this->Select, $this->GetSearchValues($Where), 't.ID', $this->GetSortValues($this->DefaultSortField), null, $rowsNum);
    }

    protected function GetLangData($Data, $lng) {

    }

}