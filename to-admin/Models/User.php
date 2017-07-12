<?php

class UserModel extends ModelAdmin {

    /**
     *
     */
    protected $TableName = "user";
    protected $Table = " user ";
    protected $Select = " STRAIGHT_JOIN * ";
    protected $SearchFields = array(
        'ID',
        'UserName',
        'FullName',
        'Email',
    );
    protected $SortFields = array(
        'ID',
        'Name',
        'CreatedDate'
    );


    protected function GetData($Data) {
        $fData = array(
            new DBField('FullName', $Data['FullName']),
            new DBField('UserName', $Data['UserName']),
            new DBField('Password', Encrypt($Data['Password'])),
            new DBField('Email', $Data['Email']),
            new DBField('IsActive', $Data['IsActive'], PDO::PARAM_BOOL),
            new DBField('State', $Data['State'], PDO::PARAM_BOOL)
        );
        return $fData;
    }

    protected function GetLangData($Data, $lng) {

    }

    public function Add($Data) {
        $sData = $this->GetData($Data);
        $sData[] = new DBField('CreatedDate', GetDateValue(), PDO::PARAM_STR);
        return $this->db->Insert($sData, strtolower($this->TableName));
    }

    public function Edit($Data) {
        $sData = $this->GetData($Data);
        $Where = array(
            new DBField('ID', $Data['ID'], PDO::PARAM_INT)
        );
        $this->db->Update($sData, strtolower($this->TableName), $Where);
    }

    public function GetAll() {
        return $this->db->Paginate($this->Table, $this->Select, $this->GetSearchValues(), 'ID', 'ID DESC');
    }

    public function GetByID($ID) {
        $Where = array(
            new DBField('ID', $ID, PDO::PARAM_INT)
        );
        return $this->db->SelectRow($this->Table, $this->Select, $Where);
    }

    public function CheckEmail($Email, $ID) {
        $Where = array(
            new DBField('ID', $ID, PDO::PARAM_INT, null, '!='),
            new DBField('Email', $Email, PDO::PARAM_INT)
        );
        return $this->db->SelectRow($this->TableName, 'Email', $Where) ? true : false;
    }

    public function CheckUserName($UserName, $ID) {
        $Where = array(
            new DBField('ID', $ID, PDO::PARAM_INT, null, '!='),
            new DBField('UserName', $UserName, PDO::PARAM_INT)
        );
        return $this->db->SelectRow($this->TableName, 'UserName', $Where) ? true : false;
    }

}