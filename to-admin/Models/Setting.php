<?php

class SettingModel extends ModelAdmin {

    /**
     *
     */
    private $BaseSql = '';

    public function GetAll(){
        $AllSettings = $this->db->GetRows("
        Select t.*, tl.* From setting t LEFT JOIN
		setting_lang tl
		ON (tl.SettingID = t.ID)
        ");
        $Settings = array();
        foreach ($AllSettings as $s) {
            $Settings[$s['Name']] = $s['Value']? : $s['DefaultValue'];
        }
        return $Settings;
    }

        public function Update($Name, $Value) {
        $Sql = "Update setting Set DefaultValue = '$Value' Where Name = '$Name' LIMIT 1";
        $this->db->RunQuery($Sql);
    }

    protected function GetLangData($Data, $lng) {

    }

}

?>