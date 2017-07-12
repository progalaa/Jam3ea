<?php

class MailingListModel extends ModelAdmin implements IModelAdmin {

    /**
     *
     */
    protected $TableName = " mailinglist ";

    protected $Table = " mailinglist t ";

    protected $Select = " t.* ";

    private function GetData($Data) {
        $fData = array(
            new DBField('Subject', $Data['Subject']),
            new DBField('Message', $Data['Message']),
            new DBField('CreatedDate', GetDateValue())
        );
        return $fData;
    }

    public function Add($Data) {
        $fData = $this->GetData($Data);
        $this->db->Insert($fData, 'mailinglist');
    }

    public function Edit($Data) {
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

    public function GetByID($ID) {
        $Where = array(
            new DBField('ID', $ID, PDO::PARAM_INT, 't')
        );
        return $this->db->SelectRow($this->Table, $this->Select, $Where);
    }

    public function GetAllEmails() {
        $Where = array(
            new DBField('State', '1', PDO::PARAM_BOOL)
        );
        return $this->db->SelectColumn('mailinglist_user', 'Email', $Where);
    }

    protected function GetLangData($Data, $lng) {

    }

}