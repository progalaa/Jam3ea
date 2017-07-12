<?php

/**
 * Description of Profile
 *
 * @author abduo
 */
class profileModel extends Model {

    public function Add($Data) {
        $fData = array(
            new DBField('FullName', $Data['FullName'], PDO::PARAM_STR),
            new DBField('UserName', $Data['UserName'], PDO::PARAM_STR),
            new DBField('Password', $Data['Password'], PDO::PARAM_STR),
            new DBField('Email', $Data['Email']?:null, PDO::PARAM_NULL),
            new DBField('Picture', GetValue($Data['Picture']), PDO::PARAM_NULL),
            new DBField('Mobile', $Data['Mobile'], PDO::PARAM_STR),
//            new DBField('ActivationCode', $Data['ActivationCode'], PDO::PARAM_STR),
            new DBField('IsActive', '1', PDO::PARAM_BOOL),
            new DBField('CreatedDate', GetDateValue(), PDO::PARAM_STR),
            new DBField('State', '1', PDO::PARAM_BOOL)
        );
        return $this->db->Insert($fData, 'user');
    }

    public function Add_Address($Data) {
        $fData = array(
            new DBField('ID', $Data['ID']),
            new DBField('Zone', GetValue($Data['Zone'])),
            new DBField('Widget', GetValue($Data['Widget'])),
            new DBField('Street', GetValue($Data['Street'])),
            new DBField('Gada', GetValue($Data['Gada'])),
            new DBField('House', GetValue($Data['House']))
        );
        return $this->db->InsertExists($fData, 'user_address');
    }

    public function ChangeData($Data) {
        $uData = array(
            new DBField('FullName', $Data['FullName'], PDO::PARAM_STR),
            new DBField('UserName', $Data['UserName'], PDO::PARAM_STR),
            new DBField('Email', $Data['Email'], PDO::PARAM_STR),
            new DBField('Picture', GetValue($Data['Picture']), PDO::PARAM_NULL),
            new DBField('Mobile', $Data['Mobile'], PDO::PARAM_STR),
        );
        $Where = array(
            new DBField('ID', $this->pUser['ID'], PDO::PARAM_STR)
        );
        $this->db->Update($uData, 'user', $Where);

        $Data['ID'] = $this->pUser['ID'];

        $this->Add_Address($Data);
    }

    public function ActiveUser($Data) {
        $uData = array();
        $uData[] = new DBField('IsActive', '1', PDO::PARAM_STR);
        $Where = array(
            new DBField('ActivationCode', $Data['ActivationCode'], PDO::PARAM_STR),
            new DBField('Mobile', $Data['Mobile'], PDO::PARAM_STR),
        );
        return $this->db->Update($uData, 'user', $Where);
    }

    public function Delete($ID) {
        $Sql = "Delete From user Where ID = $ID LIMIT 1;";
        $this->db->RunQuery($Sql);
        return $this->db->AffectedRows();
    }

    public function ForgotPassword($Data) {
        $uData = array();
        $uData[] = new DBField('Password', $Data['Password'], PDO::PARAM_STR);
        $Where = array(
            new DBField('Email', $Data['Email'], PDO::PARAM_STR)
        );
        return $this->db->Update($uData, 'user', $Where);
    }

    public function EditMobile($Data) {
        $uData = array();
        $uData[] = new DBField('Mobile', $Data['Mobile'], PDO::PARAM_STR);
        $Where = array(
            new DBField('ID', $this->pUser['ID'], PDO::PARAM_STR)
        );
        return $this->db->Update($uData, 'user', $Where);
    }

    public function SignIn($Data) {
        $Where = array(
            new DBField('UserName', $Data['UserName'], PDO::PARAM_STR, 'u'),
            new DBField('Password', $Data['Password'], PDO::PARAM_STR, 'u')
        );
        return $this->db->SelectRow('user u
        LEFT JOIN
		user_address a ON a.ID = u.ID', 'u.*, a.*, u.ID', $Where);
//        $User = $this->db->GetRow($Sql);
//        if ($User) {
//            $User['Devices'] = $this->GetDevicesByUserID($User['ID']);
//        }
//        return $User;
    }

    public function GetDevicesByUserID($ID) {
        $Where = array(
            new DBField('UserID', $ID, PDO::PARAM_INT)
        );
        return $this->db->Select('device', '*', $Where);
    }

    public function GetByID($ID) {
        $Where = array(
            new DBField('ID', $ID, PDO::PARAM_INT)
        );
        return $this->db->SelectRow('user', '*', $Where);
    }

    public function CheckEmail($Email) {
        $Where = array(
            new DBField('Email', $Email, PDO::PARAM_STR)
        );
        if ($this->pUser) {
            $Where[] = new DBField('ID', $this->pUser['ID'], PDO::PARAM_INT, null, '!=');
        }
        return $this->db->SelectRow('user', 'Email', $Where) ? true : false;
    }

    public function CheckMobile($Mobile) {
        $Where = array(
            new DBField('Mobile', $Mobile, PDO::PARAM_STR)
        );
        if ($this->pUser) {
            $Where[] = new DBField('ID', $this->pUser['ID'], PDO::PARAM_INT, null, '!=');
        }
        return $this->db->SelectRow('user', 'Mobile', $Where) ? true : false;
    }

    public function CheckUserName($User) {
        $Where = array(
            new DBField('UserName', $User, PDO::PARAM_STR)
        );
        if ($this->pUser) {
            $Where[] = new DBField('ID', $this->pUser['ID'], PDO::PARAM_INT, null, '!=');
        }
        return $this->db->SelectRow('user', 'UserName', $Where) ? true : false;
    }

    public function ChangePassword($Data) {
        $Sql = "Update user Set Password = '$Data[Password]'
Where ID = '$Data[ID]'";
        $this->db->RunQuery($Sql);
    }

    public function ChangeEmail($Data) {
        $uData = array();
        $uData[] = new DBField('Email', $Data['Email'], PDO::PARAM_STR);
        $Where = array(
            new DBField('ID', $this->pUser['ID'], PDO::PARAM_STR)
        );
        return $this->db->Update($uData, 'user', $Where);
    }

}
