<div class="breadcrumb">
    <form method="get" class="form-horizontal" role="form">
        <div class="col-md-6">
            <div class="form-group">
                <?php echo Label('daterange', $_OrderDate, 'class="col-sm-4 control-label"'); ?>
                <div class="col-sm-8">
                    <div class="col-md-6">
                        <div class="form-group">
                            <?php echo Label('FromDate', $_FromDate, 'class="col-sm-3 control-label"'); ?>
                            <div class="col-sm-9">
                                <?php echo InputBox('FromDate', $_GET['FromDate'], $_FromDate, 'class="date"'); ?>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <?php echo Label('ToDate', $_ToDate, 'class="col-sm-3 control-label"'); ?>
                            <div class="col-sm-9">
                                <?php echo InputBox('ToDate', $_GET['ToDate'], $_ToDate, 'class="date"'); ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="form-group">
                <?php echo Label('User', "{$_FullName} / {$_Mobile}", 'class="col-sm-4 control-label"'); ?>
                <div class="col-sm-8">
                    <?php echo InputAutoComplete('FullName', 'User', ADM_BASE . $pID . '/AutoCompleteUsers', $_GET['User'], $_GET['User'], $_FullName); ?>
                </div>
            </div>
        </div>
        <br class="clearfix" />
        <div class="col-md-6 col-md-offset-3">
            <div class="col-md-6 no-padding">
                <button type="submit" class="btn btn-success btn-block">
                    <i class="fa fa-search"></i>
                    <?php echo $_Search; ?>
                </button>
            </div>
            <div class="col-md-6 no-padding">
                <a class="btn btn-danger btn-block" href="<?php echo $pUrl; ?>">
                    <i class="fa fa-repeat"></i>
                    <?php echo $_Reset; ?>
                </a>
            </div>
        </div>
    </form>
</div>
<?php
if ($dResults) {

    function array_orderby() {
        $args = func_get_args();
        $data = array_shift($args);
        foreach ($args as $n => $field) {
            if (is_string($field)) {
                $tmp = array();
                foreach ($data as $key => $row)
                    $tmp[$key] = $row[$field];
                $args[$n] = $tmp;
            }
        }
        $args[] = &$data;
        call_user_func_array('array_multisort', $args);
        return array_pop($args);
    }
    ?>
    <div class="table-responsive">
        <table class="table table-striped table-bordered bootstrap-datatable ">
            <thead>
                <tr>
                    <th>
                        <?php echo $_FullName; ?>
                    </th>
                    <th>
                        <?php echo $_Mobile; ?>
                    </th>
                    <th>
                        <?php echo $_BillsCount; ?>
                    </th>
                    <th>
                        <?php echo $_Total; ?>
                    </th>
                    <th>
                        <?php echo $_OrderDate; ?>
                    </th>
                </tr>
            </thead>
            <tbody>
                <?php
                $Total = 0;
                $Rows = array();
                foreach ($dResults as $i) {
                    if ($i['FullName']) {
                        if (isset($Rows[$i['Mobile']])) {
                            $Rows[$i['Mobile']]['BillsCount'] ++;
                            $Rows[$i['Mobile']]['TotalPrice'] += $i['TotalPrice'];
                        } else {
                            $i['BillsCount'] = 1;
                            $Rows[$i['Mobile']] = $i;
                        }
                    } else {
                        $ud = unserialize($i['VisitorData']);
                        $i = array_merge($i, $ud);
                        if (isset($Rows[$i['Mobile']])) {
                            $Rows[$i['Mobile']]['BillsCount'] ++;
                            $Rows[$ud['Mobile']]['TotalPrice'] += $i['TotalPrice'];
                        } else {
                            $i['BillsCount'] = 1;
                            $Rows[$ud['Mobile']] = $i;
                        }
                    }
                }
                $sorted = array_orderby($Rows, 'TotalPrice', SORT_DESC);
                foreach ($sorted as $RowK => $RowV) {
                    $Item = $RowV;
                    $Total += $Item['TotalPrice'];
                    ?>
                    <tr>
                        <td>
                            <?php
                            if ($Item['FullName']) {
                                echo $Item['FullName'];
                            } else {
                                $ud = unserialize($Item['VisitorData']);
                                echo $ud['FullName'];
                            }
                            ?>
                        </td>
                        <td>
                            <?php
                            if ($Item['Mobile']) {
                                echo $Item['Mobile'];
                            } else {
                                $ud = unserialize($Item['VisitorData']);
                                echo $ud['Mobile'];
                            }
                            ?>
                        </td>
                        <td>
                            <?php echo $Item['BillsCount']; ?>
                        </td>
                        <td>
                            <?php echo $Item['TotalPrice']; ?>
                        </td>
                        <td>
                            <?php echo $Item['OrderDate']; ?>
                        </td>
                    </tr>
                <?php } ?>
            </tbody>
            <tfoot>
                <tr>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td><?php echo $Total; ?></td>
                    <td></td>
                </tr>
            </tfoot>
        </table>
    </div>
    <div class="dataTables_paginate">
        <?php echo $dRenderNav; ?>
    </div>
    <?php
}