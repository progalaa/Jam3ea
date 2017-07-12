<?php if (!defined('BASE_DIR')) exit(header('Location: /')); ?><?php

define('DB_NULL', '(NULL)');

class FieldType {

    const String = 0;
    const Integer = 1;
    const Email = 2;
    const Bool = 3;

}

class ErrorField {

    public $ID;
    public $Name;
    public $Value;
    public $Type;
    public $Required;
    public $MaxLength;
    public $Array;
    public $InArray;
    public $Equal;

    public function __construct($ID, $Name, $Value = null, $Required = false, $Type = null, $MaxLength = 0, $MinLength = 0, $Array = null, $InArray = null, $Equal = false) {
        $this->ID = $ID;
        $this->Name = $Name;
        $this->Value = $Value;
        $this->Type = $Type;
        $this->Required = $Required;
        $this->MaxLength = $MaxLength;
        $this->MinLength = $MinLength;
        $this->Array = $Array;
        $this->InArray = $InArray;
        $this->Equal = $Equal;
    }

}

class DBField {

    public $ID;
    public $ParamName;
    public $Value;
    public $Type = PDO::PARAM_STR;
    public $Operator = '=';
    public $TableAlias = '';
    public $LogicalOperator = 'AND';

    public function __construct($ID, $Value = null, $Type = PDO::PARAM_STR, $TableAlias = null, $Operator = '=', $LogicalOperator = 'AND', $ParamName = null) {
        $this->ID = $ID;
        $this->ParamName = $ParamName? : $ID;
        $this->Value = $Value;
        $this->Type = $Type;
        $this->TableAlias = $TableAlias ? $TableAlias . '.' : '';
        $this->Operator = $Operator;
        $this->LogicalOperator = $LogicalOperator;
    }

}

class Database {

    public $PHP_Self;
    public $Rows_Per_Page = 9;
    public $Total_Rows = 0;
    public $Links_Per_Page = 5;
    public $Append = "";
    public $Sql = "";
    public $Debug = false;
    public $Page = 1;
    public $Max_Pages = 0;
    public $Offset = 0;
    public $Error = '';
    public $Url = '';
    public $_ = array();
    private $Conn;
    private $Query;
    private $Escape_Parameters = array('(', ')');

    public function __construct() {
        $this->Conn = new PDO("mysql:host=" . DB_SERVER . "; dbname=" . DB_Name, DB_USER, DB_PASSWORD);
        $this->Conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING);
        $this->Conn->exec("SET CHARACTER SET utf8");
    }

    public function RunQuery($Sql, $Params = array()) {
        $this->Query = $this->Conn->prepare($Sql);
        if (count($Params) > 0) {
            foreach ($Params as $i) {
                if (!in_array($i->ID, $this->Escape_Parameters) && $i->Operator != 'IS') {
                    $this->Query->bindValue(
                            ":$i->ParamName", $i->Value, $i->Type
                    );
                }
            }
        }
        $this->Query->execute();
    }

    public function Select($Table, $Select = '*', $Where = null, $Order = '', $GroupBy = '', $Limit = 0) {
        $Sql = "Select $Select From $Table";
        $Params = $this->WhereQuery($Sql, null, $Where, $Order, $GroupBy, $Limit);
        $this->RunQuery($this->Sql, $Params);
        return $this->Query->fetchAll(PDO::FETCH_ASSOC);
    }

    public function SelectColumn($Table, $Column = '*', $Where = null, $Order = '', $GroupBy = '', $Limit = 0) {
        $Sql = "Select $Column From $Table";
        $Params = $this->WhereQuery($Sql, null, $Where, $Order, $GroupBy, $Limit);
        $this->RunQuery($this->Sql, $Params);
        $Data = $this->Query->fetchAll(PDO::FETCH_ASSOC);
        $arr = array();
        if ($Data) {
            foreach ($Data as $i) {
                $arr[] = $i[$Column];
            }
        }
        return $arr;
    }

    public function SelectRow($Table, $Select, $Where = null, $Order = '', $GroupBy = '') {
        $Data = $this->Select($Table, $Select, $Where, $Order, $GroupBy, 1);
        return GetValue($Data[0]);
    }

    public function Insert($Data, $Table) {
        $Sql = "Insert Into $Table Set ";
        $Params = $this->WhereQuery($Sql, $Data, null, null, null, null);
        $this->RunQuery($this->Sql, $Params);
        return $this->InsertedID();
    }

    public function InsertExists($Data, $Table) {
        $Sql = "INSERT INTO `$Table` SET ";
        $j = 1;
        $n = count($Data);
        $Params = array();
        foreach ($Data as $i) {
            $Sql .= $i->ID . ' = :' . $i->ParamName . ($j < $n ? ',' : '');
            $Params[] = $i;
            $j++;
        }
        $Sql.=" ON DUPLICATE KEY UPDATE ";
        $j = 1;
        foreach ($Data as $i) {
            $Sql .= $i->ID . ' = :' . $i->ParamName . ($j < $n ? ',' : '');
            $j++;
        }
        $this->RunQuery($Sql, $Params);
        return $this->InsertedID();
    }

    public function Update($Data, $Table, $Where = null, $Limit = 1) {
        $Sql = "Update $Table Set ";
        $Params = $this->WhereQuery($Sql, $Data, $Where, null, null, $Limit);
        $this->RunQuery($this->Sql, $Params);
        return $this->AffectedRows();
    }

    public function Delete($Table, $Where, $Limit = 1) {
        $Sql = "Delete From $Table ";
        $Params = $this->WhereQuery($Sql, null, $Where, null, null, $Limit);
        $this->RunQuery($this->Sql, $Params);
    }

    private function WhereQuery($Sql, $InputParams = null, $Where = null, $Order = null, $GroupBy = null, $Limit = 1) {
        $j = 1;
        $n = count($InputParams);
        $Params = array();
        if ($InputParams) {
            foreach ($InputParams as $i) {
                $Sql .= $i->ID . ' = :' . $i->ParamName . ($j < $n ? ',' : '');
                $Params[] = $i;
                $j++;
            }
        }
        if ($Where) {
            $Sql .= ' Where ';
            $wj = 1;
            $wn = count($Where);
            foreach ($Where as $w) {
                $NextParam = GetValue($Where[$wj]->ID);
                if ($w->ID == '(') {
                    $Sql .= $w->ID;
                } elseif ($w->ID != ')') {
                    $Sql .= $w->TableAlias . $w->ID . ' ' .
                            ($w->Operator == 'IS' && !$w->Value ? ' IS NULL ' : $w->Operator . ' :' . $w->ParamName) .
                            ($wj < $wn ?
                                    ' ' . ($NextParam == ')' ? ') ' . ($wj + 1 < $wn ? $Where[$wj]->LogicalOperator : '') : $w->LogicalOperator)
                                    . ' ' : '');
                }
                $Params[] = $w;
                $wj++;
            }
        }
        $Sql .= ($GroupBy ? ' Group By ' . $GroupBy : '');
        $Sql .= ($Order ? ' Order By ' . $Order : '');
        $Sql .= ($Limit ? ' LIMIT ' . $Limit : '');
        $this->Sql = $Sql;
        return $Params;
    }

    public function GetRows($Sql, $params = array()) {
        $this->RunQuery($Sql, $params);
        return $this->Query->fetchAll(PDO::FETCH_ASSOC);
    }

    public function GetRow($Sql, $params = array()) {
        $Data = $this->GetRows($Sql, $params);
        return GetValue($Data[0]);
    }

    public function RowsCount($Table, $Field = 'ID', $Where = null) {
        $Sql = "Select STRAIGHT_JOIN Count($Field) As 'ItemsCount' From $Table";
        $Params = $this->WhereQuery($Sql, null, $Where, null, null, 1);
        $this->RunQuery($this->Sql, $Params);
        $Rows = $this->Query->fetchAll(PDO::FETCH_ASSOC);
        return GetValue($Rows[0]['ItemsCount'], 0);
    }

    public function RowsSum($Table, $Field = 'ID', $Where = null) {
        $Sql = "Select STRAIGHT_JOIN SUM($Field) As 'ItemsSum' From $Table";
        $Params = $this->WhereQuery($Sql, null, $Where, null, null, 1);
        $this->RunQuery($this->Sql, $Params);
        $Rows = $this->Query->fetchAll(PDO::FETCH_ASSOC);
        return GetValue($Rows[0]['ItemsSum'], 0);
    }

    public function InsertedID() {
        return $this->Conn->lastInsertId();
    }

    public function AffectedRows() {
        return $this->Query->rowCount();
    }

    function Paginate($Table, $Select, $params = array(), $Field = 'ID', $OrderBy = '', $GroupBy = '', $rows_per_page = 200, $links_per_page = 10, $append = "") {
        $this->Sql = "Select $Select From $Table";
        $this->Rows_Per_Page = (int) $rows_per_page;
        $this->Links_Per_Page = (int) $links_per_page;
        $this->Append = $append;
        $this->PHP_Self = htmlspecialchars($_SERVER['PHP_SELF']);
        $this->Page = intval(GetValue($_GET['page'], 1));
        $this->Total_Rows = $this->RowsCount($Table, $Field, $params);
        if ($this->Total_Rows == 0) {
            return array();
        }
        $this->Max_Pages = ceil($this->Total_Rows / $this->Rows_Per_Page);
        if ($this->Links_Per_Page > $this->Max_Pages) {
            $this->Links_Per_Page = $this->Max_Pages;
        }
        if ($this->Page > $this->Max_Pages || $this->Page <= 0) {
            return array();
        }
        $this->Offset = $this->Rows_Per_Page * ($this->Page - 1);
        $Limit = $this->Offset . ", " . $this->Rows_Per_Page;
        return $this->Select($Table, $Select, $params, $OrderBy, $GroupBy, $Limit);
    }

    function RenderFirst() {
        if ($this->Total_Rows == 0)
            return FALSE;
        $tag = $this->_['_First'];
        if ($this->Page == 1) {
            return array(
                'Url' => 'javascript:void(0);',
                'Title' => $tag,
                'Ajax' => false
            );
        } else {
            return array(
                'Url' => $this->Url . 'page=1',
                'Title' => $tag,
                'Ajax' => true
            );
        }
    }

    function RenderLast() {
        if ($this->Total_Rows == 0)
            return FALSE;

        $tag = $this->_['_Last'];
        if ($this->Page == $this->Max_Pages) {
            return array(
                'Url' => 'javascript:void(0);',
                'Title' => $tag,
                'Ajax' => false
            );
        } else {
            $pageno = $this->Max_Pages;
            return array(
                'Url' => $this->Url . 'page=' . $pageno,
                'Title' => $tag,
                'Ajax' => true
            );
        }
    }

    function RenderNext() {
        if ($this->Total_Rows == 0)
            return FALSE;

        $tag = $this->_['_NextPage'];
        if ($this->Page < $this->Max_Pages) {
            $pageno = $this->Page + 1;
            return array(
                'Url' => $this->Url . 'page=' . $pageno,
                'Title' => $tag,
                'Ajax' => true
            );
        } else {
            return array(
                'Url' => 'javascript:void(0);',
                'Title' => $tag,
                'Ajax' => false
            );
        }
    }

    function RenderPrev($tag = null) {
        if ($this->Total_Rows == 0)
            return FALSE;

        $tag = $this->_['_PreviousPage'];
        if ($this->Page > 1) {
            $pageno = $this->Page - 1;
            return array(
                'Url' => $this->Url . 'page=' . $pageno,
                'Title' => $tag,
                'Ajax' => true
            );
        } else {
            return array(
                'Url' => 'javascript:void(0);',
                'Title' => $tag,
                'Ajax' => false
            );
        }
    }

    function RenderNav() {
        if ($this->Total_Rows == 0)
            return FALSE;

        $batch = ceil($this->Page / $this->Links_Per_Page);
        $end = $batch * $this->Links_Per_Page;
        if ($end > $this->Max_Pages) {
            $end = $this->Max_Pages;
        }
        $start = $end - $this->Links_Per_Page + 1;
        $links = array();

        for ($i = $start; $i <= $end; $i++) {
            if ($i == $this->Page) {
                $links[] = array(
                    'Url' => 'javascript:void(0);',
                    'Title' => $i,
                    'Ajax' => false
                );
            } else {
                $links[] = array(
                    'Url' => $this->Url . 'page=' . $i,
                    'Title' => $i,
                    'Ajax' => true
                );
            }
        }

        return $links;
    }

//
    function RenderFullNav() {
        if ($this->Total_Rows > $this->Links_Per_Page) {
            $nav = array();
            $nav[] = $this->RenderFirst();
            $nav[] = $this->RenderPrev();
            $nav = array_merge($nav, $this->RenderNav());
            $nav[] = $this->RenderNext();
            $nav[] = $this->RenderLast();
            return $nav;
        }
        return array();
    }

    public function GetError() {
        return $this->Conn->errorInfo();
    }

    function SetDebug($debug) {
        $this->Debug = $debug;
    }

    public function __destruct() {
//        $this->Conn->
    }

}
