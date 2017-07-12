<!DOCTYPE html>
<html lang="<?php echo $l['Code'] ?>">
    <head>
        <base href="<?php echo SITE_URL; ?>" />
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <title><?php echo $d['dTitle']; ?></title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <meta name="description" content="<?php echo $d['dDescription']; ?>" />
        <meta name="keywords" content="<?php echo $d['dKeywords']; ?>" />
        <meta name="author" content="<?php echo $s['sSiteName']; ?>" />
        <meta property="og:title" content="<?php echo $d['dTitle']; ?>" />
        <meta property="og:url" content="<?php echo $d['pUrl']; ?>" />
        <meta property="og:image" content="<?php echo $d['pImage']; ?>" />
        <meta property="og:site_name" content="<?php echo $s['sSiteName']; ?>" />
        <meta property="og:description" content="<?php echo $d['dDescription']; ?>" />
        <link href="<?php echo APP_CURRENT_URL_TEMPLATE; ?>css/bootstrap.min.css" rel="stylesheet" />
        <link href="<?php echo APP_CURRENT_URL_TEMPLATE; ?>css/font-awesome.min.css" rel="stylesheet" />
        <link href="<?php echo APP_CURRENT_URL_TEMPLATE; ?>css/main.css" rel="stylesheet" />
        <link href="<?php echo APP_CURRENT_URL_TEMPLATE; ?>css/responsive.css" rel="stylesheet" />
        <link href="<?php echo APP_CURRENT_URL_TEMPLATE; ?>css/print.css"  media="print" rel="stylesheet" />
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

    <body dir="<?php echo $_['_Direction']; ?>">
        <div class="col-md-12">

            <div class="row non-printable">
                <div class="col-md-6 col-md-offset-3">
                    <div class="alert alert-success">
                        <h1><?php echo $d['dMsg']; ?></h1>
                    </div>
                </div>
            </div>
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
                            ?>

                            <div class="col-md-12">
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
                                                        <?php echo $d['dOrderID']; ?>
                                                    </h5>
                                                </div>
                                                <div class="col-xs-5">
                                                    <h5>
                                                        <?php echo $_['_Order_Date']; ?> :
                                                        <?php echo $d['dOrderDate']; ?>
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
                                                $Sum = 0;
                                                $j = 1;
                                                foreach ($d['dResults'] as $i) {
                                                    $Sum += ($i['Price'] * $i['cQuantity']);
                                                    ?>
                                                    <tr>
                                                        <td class="col-md-6">
                                                            <h4 class="product-name">
                                                                <?php echo $j; ?>
                                                                -
                                                                <?php echo $i['Name']; ?>
                                                            </h4>
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
                                                                <?php echo ($i['Price'] * $i['cQuantity']); ?>
                                                                د.ك
                                                            </h6>
                                                        </td>
                                                    </tr>
                                                    <?php
                                                    $j++;
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
                                    </div>
                                    <div class="panel-footer">
                                        <div class="row text-center">
                                            <div class="col-md-8">
                                                <h4 class="text-right">
                                                    <?php echo $_['_Total']; ?>
                                                    <strong>
                                                        <?php echo GetPrice($Sum + $s['sShippingCost']); ?>
                                                        د.ك
                                                    </strong>
                                                </h4>
                                            </div>
                                            <div class="col-md-4 non-printable">
                                                <h4>
                                                    <a href="javascript:void(0)" onclick="window.print();">
                                                        <i class="fa fa-print"></i>
                                                        <?php echo $_['_Print']; ?>
                                                    </a>
                                                </h4>
                                            </div>
                                        </div>
                                    </div>
                                </div>


                                <?php
                                            $g = $_GET;
                                if (isset($g['PaymentID'])) {
                                    ?>
                                    <div class="panel panel-info non-printable" dir="ltr">
                                        <div class="panel-heading">
                                            Transaction Details
                                            (from Merchant Notification Message)
                                        </div>
                                        <div class="panel-body">
                                            <table width=100% border="0" cellpadding="0" cellspacing="1" bgcolor="#CCCCCC" col="2">
                                                <tr>
                                                    <td>Payment ID :</td>
                                                    <td><?php echo $g['PaymentID']; ?></td>
                                                </tr>
                                                <tr>
                                                    <td>Post Date :</td>
                                                    <td><?php echo $g['PostDate']; ?></td>
                                                </tr>
                                                <tr>
                                                    <td>Result Code :</td>
                                                    <td><?php echo $g['Result']; ?></td>
                                                </tr>
                                                <tr>
                                                    <td>Transaction ID :</td>
                                                    <td><?php echo $g['TranID']; ?></td>
                                                </tr>
                                                <tr>
                                                    <td>Auth :</td>
                                                    <td><?php echo $g['Auth']; ?></td>
                                                </tr>
                                                <tr>
                                                    <td>Track ID :</td>
                                                    <td><?php echo $g['TrackID']; ?></td>
                                                </tr>
                                                <tr>
                                                    <td>Ref No :</td>
                                                    <td><?php echo $g['Ref']; ?></td>
                                                </tr>
                                            </table>
                                        </div>
                                    </div>
                                    <?php
                                }
                                ?>

                            </div>


                            <div class="col-md-4 col-md-offset-4 non-printable">
                                <?php echo Anchor(GetRewriteUrl('home'), $_['_BackToHome'], 'id="btnBackToHome" class="btn btn-primary btn-md btn-block"'); ?>
                            </div>
                            <?php
                            if (!NO_STYLE) {
                                ?>
                                <br />
                                <div class="col-md-8 col-md-offset-2 non-printable">
                                    <img src="<?php echo APP_CURRENT_URL_TEMPLATE; ?>images/knet.png" class="center-block img-responsive" />
                                </div>
                                <?php
                            }
                        }
                        ?>
                    </div>
                </div>
            </div>

        </div>
    </body>
</html>