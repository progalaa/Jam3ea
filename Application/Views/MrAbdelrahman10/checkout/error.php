<div class="col-md-12">

    <div class="col-md-8 col-md-offset-2">

        <div class="panel panel-info">
            <div class="panel-heading">
                <?php echo $_['_Message']; ?>
            </div>
            <div class="panel-body">
                <h3 class="text-center">
                    <?php echo $_['_UnCaptured_Order']; ?>
                </h3>

    <?php
    $g = $_GET;
    ?>
                <table width=100% border="0" cellpadding="0" cellspacing="1" bgcolor="#CCCCCC" col="2" dir="ltr">
                    <tr>
                        <td colspan="2" align="center" class="msg">
                            <strong class="text">
                                Transaction Details
                                (from Merchant Notification Message)
                            </strong>
                        </td>
                    </tr>
                    <tr>
                        <td>Payment ID :</td>
                        <td><?php echo $g['PaymentID']; ?></td>
                    </tr>
                    <tr>
                        <td>Post Date :</td>
                        <td><?php echo GetDateValue($g['PostDate']); ?></td>
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

    </div>

</div>