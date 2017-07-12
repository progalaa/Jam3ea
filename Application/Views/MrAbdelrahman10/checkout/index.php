<div class="col-md-12">
    <!-- onsubmit="return validOnSubmit();"-->
    <form action="<?php echo GetRewriteUrl('checkout/buy') . '?paymethod=' . Payments_Methods::Cash_On_Delivery; ?>" method="POST" id="buyform">
        <div class="col-md-6 col-md-offset-3">
            <div class="panel panel-success">
                <div class="panel-heading">
                    <?php echo $_['_Payment_Methods']; ?>
                </div>
                <div class="panel-body">
                    <div class="funkyradio1">
                        <div class="funkyradio-primary1">
                            <input type="radio" name="Payment_Method" id="Payment_Method1" value="1" checked />
                            <label for="Payment_Method1"><?php echo $_['_Pay_Cash_On_Delivery']; ?></label>
                        </div>
                    </div>
                    <div class="funkyradio1">
                        <div class="funkyradio-primary1">
                            <input type="radio" name="Payment_Method" id="Payment_Method2" value="2"  />
                            <label for="Payment_Method2">
                                <?php echo $_['_Pay_By_Knet']; ?>
                            </label>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-8 col-md-offset-2">
            <?php
            if (!$d['pUser']) {
                ?>
                <div class="panel panel-info">
                    <div class="panel-heading">
                        <?php echo $_['_MyData']; ?>
                    </div>
                    <div class="panel-body">
                        <div class="form-group">
                            <label for="FullName" class="col-md-3"> <?php echo $_['_FullName']; ?> <em>*</em></label>
                            <div class="col-md-9">
                                <input type="text" class="form-control" name="FullName" placeholder="<?php echo $_['_FullName']; ?>" required>
                                <?php echo ErrorSpan('FullName', $d['errFullName']); ?>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="Mobile" class="col-md-3"> <?php echo $_['_Mobile']; ?> <em>*</em></label>
                            <div class="col-md-9">
                                <input type="text" class="form-control" name="Mobile" placeholder="<?php echo $_['_Mobile']; ?>" required>
                                <?php echo ErrorSpan('Mobile', $d['errMobile']); ?>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="Zone" class="col-md-3"> <?php echo $_['_Zone']; ?> </label>
                            <div class="col-md-9">
                                <input type="text" class="form-control" name="Zone" placeholder="<?php echo $_['_Zone']; ?>">
                                <?php echo ErrorSpan('Zone', $d['errZone']); ?>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="Widget" class="col-md-3"> <?php echo $_['_Widget']; ?> </label>
                            <div class="col-md-9">
                                <input type="text" class="form-control" name="Widget" placeholder="<?php echo $_['_Widget']; ?>">
                                <?php echo ErrorSpan('Widget', $d['errWidget']); ?>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="Street" class="col-md-3"> <?php echo $_['_Street']; ?> </label>
                            <div class="col-md-9">
                                <input type="text" class="form-control" name="Street" placeholder="<?php echo $_['_Street']; ?>">
                                <?php echo ErrorSpan('Street', $d['errStreet']); ?>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="Gada" class="col-md-3"> <?php echo $_['_Gada']; ?> </label>
                            <div class="col-md-9">
                                <input type="text" class="form-control" name="Gada" placeholder="<?php echo $_['_Gada']; ?>">
                                <?php echo ErrorSpan('Gada', $d['errGada']); ?>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="House" class="col-md-3"> <?php echo $_['_House']; ?> </label>
                            <div class="col-md-9">
                                <input type="text" class="form-control" name="House" placeholder="<?php echo $_['_House']; ?>">
                                <?php echo ErrorSpan('House', $d['errHouse']); ?>
                            </div>
                        </div>
                    </div>
                </div>
                <?php
            }
            ?>
            <div class="col-md-4 col-md-offset-4">
                <button type="submit" id="btnCheckout" class="btn btn-primary btn-block btn-sm">
                    <?php echo $_['_BuyNow']; ?>
                </button>
            </div>
        </div>
    </form>
    <?php
    if (!NO_STYLE) {
        ?>
        <div class="col-md-8 col-md-offset-2">
            <img src="<?php echo APP_CURRENT_URL_TEMPLATE; ?>images/knet.png" class="center-block img-responsive" />
        </div>
        <?php
    }
    ?>
</div>

<script type="text/javascript">


    $(window).load(function () {
        $('input:radio[name="Payment_Method"]').change(function () {
            var _pay_method = $(this).val();
            $('#buyform').get(0).setAttribute('action', '<?php echo GetRewriteUrl('checkout/buy'); ?>?paymethod=' + _pay_method);
        });
    });

<?php
if (!$d['pUser']) {
    ?>
        function validOnSubmit() {
            var _Data = $('#buyform').serialize();
            var pageurl = '<?php echo GetRewriteUrl("checkout/checkvalidation"); ?>';
            $.ajax({
                url: pageurl,
                type: 'post',
                data: _Data,
                async: false,
                beforeSend: function () {
                    $('.Error').html('').fadeOut('slow');
                }, success: function (json) {
                    var _Result = (json);
                    if (_Result['Error']) {
                        var e = _Result['Error'];
                        ShowError(e);
                        return false;
                    } else {
                        return true;
                    }
                }, complete: function () {
                },
                error: function (xhr, ajaxOptions, thrownError) {
                }
            });
            return false;
        }
    <?php
}
?>

</script>