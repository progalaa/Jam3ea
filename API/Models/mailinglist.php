<?php

/**
 * Description of mailinglist
 *
 * @author mrabdelrahman10
 */
class mailinglistModel extends Model {


    protected $Select = "  STRAIGHT_JOIN t.* ";
    protected $Table = " mailinglist_user t ";

    public function Add($Email) {
        $fData = array(
            new DBField('Email', $Email),
            new DBField('CreatedDate', GetDateValue(), PDO::PARAM_STR)
        );
        return $this->db->Insert($fData, 'mailinglist_user');
    }

    public function Delete($Email) {
        $Where = array(
            new DBField('Email', $Email)
        );
        return $this->db->Delete('mailinglist_user', $Where);
    }

    public function CheckEmail($Email) {
        $Where = array(
            new DBField('Email', $Email, PDO::PARAM_STR)
        );
        return $this->db->SelectRow('mailinglist_user', 'Email', $Where) ? true : false;
    }

}