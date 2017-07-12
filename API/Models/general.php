<?php

class generalModel extends Model {

    public function AddDevice($Data) {
        $fData = array(
            new DBField('DeviceToken', $Data['DeviceToken'], PDO::PARAM_STR),
            new DBField('DeviceType', $Data['DeviceType'], PDO::PARAM_INT),
            new DBField('CreatedDate', GetDateValue(), PDO::PARAM_STR),
        );
        $this->db->Insert($fData, 'device');
        return $this->db->InsertedID();
    }

    public function DeleteDevice($Data) {
        $Where = array(
            new DBField('DeviceToken', $Data['DeviceToken'], PDO::PARAM_STR)
        );
        return $this->db->Delete(null, 'device', $Where);
    }

    public function GetSettings() {
        $AllSettings = $this->db->GetRows("
        Select t.*, tl.* From setting t LEFT JOIN
		setting_lang tl
		ON (tl.SettingID = t.ID)
        ");
        $Settings = array();
        foreach ($AllSettings as $s) {
            $Settings['s' . $s['Name']] = $s['Value']? : $s['DefaultValue'];
        }
        return $Settings;
    }

    public function GetLanguages() {
        return $this->db->Select("language");
    }

}