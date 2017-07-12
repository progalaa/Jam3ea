<?php

function viewPill($i, $j) {
    ?>
    <tr>
        <td class="col-md-6">
            <h5>
                <?php echo $j; ?>
                -
                <?php echo $i['Name']; ?>
            </h5>
        </td>
        <td class="col-md-3 text-right">
            <h6>
                <?php echo $i['Price']; ?>
                د.ك
            </h6>
        </td>
        <td class="col-md-1">
            <h6>
                <?php echo $i['cQuantity']; ?>
            </h6>
        </td>
        <td class="col-md-2">
            <h6>
                <?php echo GetPrice($i['Price'] * $i['cQuantity']); ?>
                د.ك
            </h6>
        </td>
    </tr>
    <?php
}

$_ = $this->_;
$d = $this->Data;
$s = $this->Settings;
$l = $this->Language;
$rSum = 0;
?>
<!DOCTYPE html>
<html lang="<?php echo $l['Code'] ?>">
    <head>
        <base href="<?php echo SITE_URL; ?>" />
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <title><?php echo $d['dTitle']; ?></title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <link href="<?php echo APP_CURRENT_URL_TEMPLATE; ?>css/bootstrap.min.css" rel="stylesheet" />
        <link href="<?php echo APP_CURRENT_URL_TEMPLATE; ?>css/font-awesome.min.css" rel="stylesheet" />
        <link href="<?php echo APP_CURRENT_URL_TEMPLATE; ?>css/main.css" rel="stylesheet" />
        <link href="<?php echo APP_CURRENT_URL_TEMPLATE; ?>css/responsive.css" rel="stylesheet" />
        <link href="<?php echo APP_CURRENT_URL_TEMPLATE; ?>css/print.css"  media="print" rel="stylesheet" />
        <link href="<?php echo ADM_CURRENT_URL_TEMPLATE; ?>css/bill.css"  media="print" rel="stylesheet" />
        <?php
        if ($_['_Direction'] == 'rtl') {
            ?>
            <link href="<?php echo APP_CURRENT_URL_TEMPLATE; ?>css/rtl.css" rel="stylesheet" />
            <?php
        }
        ?>
        <!--[if lt IE 9]>
        <script src="<?php echo APP_CURRENT_URL_TEMPLATE; ?>js/html5shiv.js"></script>
        <script src="<?php echo APP_CURRENT_URL_TEMPLATE; ?>js/respond.min.js"></script>
        <![endif]-->
        <link rel="shortcut icon" href="<?php echo APP_CURRENT_URL_TEMPLATE; ?>images/ico/favicon.ico" />
    </head><!--/head-->

    <body>
        <div class="col-md-12" id="cartPrint">
            <div class="row">
                <div class="col-md-2 col-md-offset-5 col-xs-2 col-xs-offset-5">
                    <img src="<?php echo APP_CURRENT_URL_TEMPLATE; ?>images/home/logo.png" alt="<?php echo $s['sSiteName']; ?>" class="img-responsive" />
                </div>
            </div>

            <div class="row">
                <div class="col-md-8 col-md-offset-2">
                    <div id="view-cart">
                        <?php
                        if ($d['dResults']) {
                            $di = $d['dResults'];
                            $vu = unserialize($di['VisitorData']);
                            ?>
                            <div class="col-md-12">
                                <div class="formRow">
                                    <table class="table table-bordered table-hover table-responsive">
                                        <tr>
                                            <td><?php echo $_FullName; ?></td>
                                            <td><?php echo $di['FullName'] ?: $vu['FullName']; ?></td>
                                            <td><?php echo $_Mobile; ?></td>
                                            <td><?php echo $di['Mobile'] ?: $vu['Mobile']; ?></td>
                                        </tr>
                                    </table>
                                </div>
                                <div class="formRow">
                                    <table class="table table-bordered table-hover table-responsive">
                                        <tr>
                                            <td><?php echo $_Zone; ?></td>
                                            <td><?php echo $di['Zone'] ?: $vu['Zone']; ?></td>
                                            <td><?php echo $_Widget; ?></td>
                                            <td><?php echo $di['Widget'] ?: $vu['Widget']; ?></td>
                                            <td><?php echo $_Street; ?></td>
                                            <td><?php echo $di['Street'] ?: $vu['Street']; ?></td>
                                            <td><?php echo $_Gada; ?></td>
                                            <td><?php echo $di['Gada'] ?: $vu['Gada']; ?></td>
                                            <td><?php echo $_House; ?></td>
                                            <td><?php echo $di['House'] ?: $vu['House']; ?></td>
                                        </tr>
                                    </table>
                                </div>

                                <div class="panel panel-info">
                                    <div class="panel-heading">
                                        <div class="panel-title">
                                            <div class="row">
                                                <div class="col-xs-4">
                                                    <h5>
                                                        <span class="glyphicon glyphicon-shopping-cart"></span>
                                                        <?php echo $_['_Cart_Details']; ?>
                                                    </h5>
                                                </div>
                                                <div class="col-xs-3">
                                                    <h5>
                                                        <?php echo $_['_Order_Number']; ?> :
                                                        <?php echo $di['ID']; ?>
                                                    </h5>
                                                </div>
                                                <div class="col-xs-5">
                                                    <h5>
                                                        <?php echo $_['_Order_Date']; ?> :
                                                        <?php echo GetDateValue($di['OrderDate'], 'Y-m-d'); ?>
                                                    </h5>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="panel-body">
                                        <table class="table table-bordered table-hover table-responsive">
                                            <thead>
                                                <tr>
                                                    <th class="col-md-6">
                                                        <?php echo $_['_Product']; ?>
                                                    </th>
                                                    <th class="col-md-3">
                                                        <?php echo $_['_Price']; ?>
                                                    </th>
                                                    <th class="col-md-1">
                                                        <?php echo $_['_Quantity']; ?>
                                                    </th>
                                                    <th class="col-md-2">
                                                        <?php echo $_['_TotalProduct']; ?>
                                                    </th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php
                                                $j = 1;
                                                $Sum = 0;
                                                $Returned = 0;
                                                $canReturn = false;
                                                foreach ($di['Products'] as $i) {
                                                    if ($i['Returned'] == '0') {
                                                        $Sum += ($i['cPrice'] * $i['cQuantity']);
                                                        viewPill($i, $j);
                                                        $j++;
                                                    } else {
                                                        $Returned += ($i['cPrice'] * $i['cQuantity']);
                                                        $canReturn = true;
                                                    }
                                                }
                                                ?>
                                                <tr>
                                                    <td class="col-md-6">
                                                        <h4 class="product-name">
                                                            <?php echo $_['_ShippingCost']; ?>
                                                        </h4>
                                                    </td>
                                                    <td class="col-md-3 text-right">
                                                        <h6>
                                                        </h6>
                                                    </td>
                                                    <td class="col-md-1">
                                                        <h6>

                                                        </h6>
                                                    </td>
                                                    <td class="col-md-2">
                                                        <h6>
                                                            <?php echo $s['sShippingCost']; ?>
                                                            د.ك
                                                        </h6>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                        <?php
                                        if ($canReturn) {
                                            ?>
                                            <h3><?php echo $_Returned; ?></h3>
                                            <table class="table table-bordered table-hover table-responsive">
                                                <thead>
                                                    <tr>
                                                        <th class="col-md-6">
                                                            <?php echo $_['_Product']; ?>
                                                        </th>
                                                        <th class="col-md-3">
                                                            <?php echo $_['_Price']; ?>
                                                        </th>
                                                        <th class="col-md-1">
                                                            <?php echo $_['_Quantity']; ?>
                                                        </th>
                                                        <th class="col-md-2">
                                                            <?php echo $_['_TotalProduct']; ?>
                                                        </th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php
                                                    $j = 1;
                                                    foreach ($di['Products'] as $i) {
                                                        if ($i['Returned'] == '1') {
                                                            $rSum += ($i['cPrice'] * $i['cQuantity']);
                                                            viewPill($i, $j);
                                                            $j++;
                                                        }
                                                    }
                                                    ?>
                                                </tbody>
                                            </table>
                                            <?php
                                        }
                                        ?>
                                    </div>
                                    <div class="panel-footer">
                                        <div class="row text-center">
                                            <div class="col-xs-8">
                                                <h4 class="text-right">
                                                    <?php echo $_['_Total']; ?>
                                                    <strong>
                                                        <?php echo GetPrice($di['TotalPrice'] - $rSum); ?>
                                                        د.ك
                                                    </strong>
                                                </h4>
                                            </div>
                                            <div class="col-xs-2">
                                                <?php
                                                if ($di['PaymentID']) {
                                                    ?>
                                                    <div class="col-xs-8">
                                                        <img src="<?php echo APP_MEDIA; ?>knet.png" class="img-responsive" />
                                                    </div>
                                                    <?php
                                                }
                                                ?>
                                            </div>
                                            <div class="col-xs-2 non-printable">
                                                <h4>
                                                    <a href="javascript:void(0)" onclick="window.print();">
                                                        <i class="fa fa-print"></i>
                                                        <?php echo $_['_Print']; ?>
                                                    </a>
                                                </h4>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="panel-footer hide">
                                        <div class="row text-center">
                                            <div class="col-xs-4">
                                                <?php echo $_['_Signature_Administration']; ?>
                                                <br />
                                                ................
                                            </div>
                                            <div class="col-xs-4">
                                                <?php echo $_['_Signature_Delegate']; ?>
                                                <br />
                                                ................
                                            </div>
                                            <div class="col-xs-4">
                                                <?php echo $_['_Signature_Customer']; ?>
                                                <br />
                                                ................
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <?php
                        }
                        ?>
                    </div>
                </div>
            </div>

        </div>
    </body>
</html>