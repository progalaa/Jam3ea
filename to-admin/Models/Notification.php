<?php

class NotificationModel extends ModelAdmin implements IModelAdmin {

    /**
     *
     */
    protected $Table = " notification t ";

    protected $Select = " t.* ";

    private function GetData($Data) {
        $fData = array(
            new DBField('Title', $Data['Title'], PDO::PARAM_STR),
            new DBField('Details', $Data['Details'], PDO::PARAM_STR),
        );
        return $fData;
    }

    public function Add($Data) {
        $fData = $this->GetData($Data);
        $fData[] = new DBField('CreatedDate', GetDateValue(), PDO::PARAM_STR);
        $this->db->Insert($fData, 'notification');
    }

    public function Edit($Data) {
        $fData = $this->GetData($Data);
        $fData[] = new DBField('ModifiedDate', GetDateValue(), PDO::PARAM_STR);
        $Where = array(
            new DBField('ID', $Data['ID'], PDO::PARAM_INT)
        );
//        $this->db->Update($fData, 'notification', $Where);
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

    public function GetDevices() {
        return $this->db->Select('device');
    }

    protected function GetLangData($Data, $lng) {

    }

}