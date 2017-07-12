<?php

class AdminSettingModel extends ModelAdmin implements IModelAdmin {

    protected $Table = " adminuser g
		INNER JOIN
		user u
		ON (g.UserID = u.ID) ";
    protected $Select = " STRAIGHT_JOIN g.*, u.* ";

    public function GetAll() {
        $Sql = "select g.*, u.UserName from adminuser g
		INNER JOIN
		user u
		ON (g.UserID = u.ID) ";
        return $this->db->GetRows($Sql);
    }

    public function GetAllUsers() {
        $Sql = "select * From user";
        return $this->db->GetRows($Sql);
    }

    public function GetByID($ID) {
        $Where = array(
            new DBField('ID', $ID, PDO::PARAM_INT, 'u')
        );
        return $this->db->SelectRow($this->Table, $this->Select, $Where);
    }

    public function GetData($Data) {
        return array(
            new DBField('FullName', $Data['FullName']),
            new DBField('UserName', $Data['UserName']),
            new DBField('Password', Encrypt($Data['Password'])),
            new DBField('Email', $Data['Email']),
            new DBField('IsActive', 1, PDO::PARAM_BOOL),
            new DBField('State', $Data['State'], PDO::PARAM_BOOL)
        );
    }

    public function Add($Data) {
        $sData = $this->GetData($Data);
        $sData[] = new DBField('CreatedDate', GetDateValue(), PDO::PARAM_STR);
        $this->db->Insert($sData, 'user');
        $ID = $this->db->InsertedID();


        $fData = array(
            new DBField('UserID', $ID),
            new DBField('IsAdmin', 0),
            new DBField('IsEditor', 1),
            new DBField('Permission', $Data['Permission']),
            new DBField('CreatedDate', GetDateValue())
        );
        $this->db->Insert($fData, 'adminuser');
    }

    public function Edit($data) {
        $Sql = "Update user Set FullName = '" . $data['FullName'] .
                (GetValue($data['Password']) ? "', Password = '" . $data['Password'] : '') .
                "', UserName = '" . $data['UserName'] .
                "', Email = '" . $data['Email'] . "'
                    Where ID = $data[ID]";
        $this->db->RunQuery($Sql);
        $Sql = "update adminuser set
                    Permission = '$data[Permission]'
                    where UserID = '$data[ID]' LIMIT 1";
        $this->db->RunQuery($Sql);
    }

    public function Delete($id) {
        $Sql = "delete from adminuser where UserID = '$id' AND IsAdmin = 0 LIMIT 1";
        $this->db->RunQuery($Sql);
        if ($this->db->AffectedRows() > 0) {
            $Sql = "delete from user where ID = '$id' LIMIT 1";
            $this->db->RunQuery($Sql);
        }
    }

    public function SetState($ID, $State) {

    }

    public function CheckEmail($Email, $ID) {
        $Where = array(
            new DBField('ID', $ID, PDO::PARAM_INT, null, '!='),
            new DBField('Email', $Email, PDO::PARAM_INT)
        );
        return $this->db->SelectRow('user', 'Email', $Where) ? true : false;
    }

    public function CheckUserName($UserName, $ID) {
        $Where = array(
            new DBField('ID', $ID, PDO::PARAM_INT, null, '!='),
            new DBField('UserName', $UserName, PDO::PARAM_INT)
        );
        return $this->db->SelectRow('user', 'UserName', $Where) ? true : false;
    }

    protected function GetLangData($Data, $lng) {

    }

}