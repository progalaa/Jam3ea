<div class="row">
    <div class="col-xs-12 col-sm-12 col-md-12">
        <div id="view-cart">
            <?php
            if ($d['dResults']) {
                ?>

                <div class="col-md-12 no-padding">
                    <div class="panel panel-info">
                        <div class="panel-heading">
                            <div class="panel-title">
                                <div class="row">
                                    <div class="col-xs-6">
                                        <h5>
                                            <span class="glyphicon glyphicon-shopping-cart"></span>
                                            <?php echo $d['dTitle']; ?>
                                        </h5>
                                    </div>
                                    <div class="col-xs-6">
                                        <a href="<?php echo GetRewriteUrl('home'); ?>" class="btn btn-primary btn-sm btn-block">
                                            <span class="glyphicon glyphicon-share-alt"></span> Continue shopping
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="panel-body no-padding">

                            <table class="table table-responsive table-bordered table-striped no-padding">
                                <tbody>
                                    <?php
                                    $Sum = 0;
                                    foreach ($d['dResults'] as $i) {
                                        $Sum += ($i['Price'] * $i['cQuantity']);
                                        ?>
                                        <tr>
                                            <td class="hidden-xs">
                                                <img class="img-responsive" src="<?php echo GetImageThumbnail($i['Picture']); ?>" alt="<?php echo $i['Name']; ?>" />
                                            </td>
                                            <td>
                                                <small>
                                                    <?php echo Anchor(GetRewriteUrl('product/i/' . $i['ID']), $i['Name'], 'title="' . $i['Name'] . '"'); ?>
                                                </small>
                                            </td>
                                            <td class="text-right">
                                                <?php echo $i['Price']; ?>
                                                د.ك
                                                <span class="text-muted">x</span>
                                            </td>
                                            <td>
                                                <input type="number" data-id="<?php echo $i['ID']; ?>" class="form-control input-sm quantity" value="<?php echo $i['cQuantity']; ?>" id="q-<?php echo $i['ID']; ?>" />
                                            </td>
                                            <td>
                                                <a href="javascript:void(0)" class="pQ" data-id="<?php echo $i['ID']; ?>">
                                                    <i class="fa fa-plus-circle fa-lg"></i>
                                                </a>
                                                <a href="javascript:void(0)" class="mQ" data-id="<?php echo $i['ID']; ?>">
                                                    <i class="fa fa-minus-circle fa-lg"></i>
                                                </a>
                                            </td>
                                            <td>
                                                <?php echo ($i['Price'] * $i['cQuantity']); ?>
                                                د.ك
                                            </td>
                                            <td>
                                                <a class="btn btn-link btn-xs deletecart" data-id="<?php echo $i['ID']; ?>">
                                                    <span class="glyphicon glyphicon-trash"> </span>
                                                </a>
                                            </td>
                                        </tr>
                                        <?php
                                    }
                                    ?>
                                </tbody>
                            </table>
                            <div class="row">
                                <div class="col-md-1">
                                </div>
                                <div class="col-md-5">
                                    <h4 class="product-name">
                                        <strong>
                                            <?php echo $_['_ShippingCost']; ?>
                                        </strong>
                                    </h4>
                                </div>
                                <div class="col-md-6">
                                    <div class="col-md-3 text-right">
                                        <h6>
                                            <strong>
                                                <?php echo $s['sShippingCost']; ?>
                                                د.ك
                                            </strong>
                                        </h6>
                                    </div>
                                    <div class="col-md-3">
                                    </div>
                                    <div class="col-md-2">
                                    </div>
                                    <div class="col-md-2">
                                    </div>
                                    <div class="col-md-2">

                                    </div>
                                </div>
                            </div>
                            <hr>

                        </div>
                        <div class="panel-footer">
                            <div class="row text-center">
                                <div class="col-md-5">
                                    <h4 class="text-right">
                                        <?php echo $_['_Total']; ?>
                                        <strong>
                                            <?php echo $Sum + $s['sShippingCost']; ?>
                                            د.ك
                                        </strong>
                                    </h4>
                                </div>
                                <div class="col-md-3">
                                    <h4 class="text-right">
                                        <?php echo $_['_Pay_Cash_On_Delivery']; ?>
                                    </h4>
                                </div>
                                <div class="col-md-3">
                                    <?php
                                    if (intval($s['sMinCartOrder']) <= $Sum) {
                                        ?>
                                        <form action="<?php echo GetRewriteUrl('checkout'); ?>" method="POST">
                                            <button type="submit" class="btn btn-primary btn-block btn-sm">
                                                <?php echo $d['pUser'] ? $_['_BuyNow'] : $_['_Complete_Visitor']; ?>
                                            </button>
                                            <?php
                                            if (!$d['pUser']) {
                                                ?>
                                                <a href="<?php echo GetRewriteUrl('profile/signin'); ?>" class="btn btn-success btn-block btn-sm">
                                                    <?php echo $_['_SignInFirst']; ?>
                                                </a>
                                                <?php
                                            }
                                            ?>
                                        </form>
                                        <?php
                                    } else {
                                        ?>
                                        <h4 class="text-danger">
                                            <?php echo str_format($_['_Min_Limit_Cart'], $s['sMinCartOrder']); ?>
                                        </h4>
                                        <?php
                                    }
                                    ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-8 col-md-offset-2">
                    <img src="<?php echo APP_CURRENT_URL_TEMPLATE; ?>images/knet.png" class="center-block img-responsive" />
                </div>
                <?php
            } else {
                ?>
                <div class="col-md-8 col-md-offset-2">
                    <div class="alert alert-danger">
                        <h2 class="text-center">
                            <?php echo $_['_NoProducts']; ?>
                        </h2>
                    </div>
                </div>
                <?php
            }
            ?>
        </div>
    </div>
</div>

<script type="text/javascript">
    $(document).ready(function () {
        $('.pQ').click(function (e) {
            var _ID = $(this).attr('data-id');
            var _Quantity = parseInt($('#q-' + _ID).val());
            $('#q-' + _ID).val(_Quantity + 1);
            updateQuantity('#q-' + _ID);
        });
        $('.mQ').click(function (e) {
            var _ID = $(this).attr('data-id');
            var _Quantity = parseInt($('#q-' + _ID).val());
            $('#q-' + _ID).val(_Quantity - 1);
            updateQuantity('#q-' + _ID);
        });
        $('.quantity').blur(function (e) {
            updateQuantity(this);
            e.preventDefault();
            return false;
        });

    });
    function updateQuantity(elm) {
        var _ID = $(elm).attr('data-id');
        var _Quantity = $(elm).val();

        $.ajax({
            url: '<?php echo GetRewriteUrl('cart/update_quantity'); ?>',
            type: 'post',
            data: {
                Product: _ID,
                Quantity: _Quantity
            },
            beforeSend: function () {
            }, success: function (json) {
                var msg = (json);
                var _txt = '';
                if (msg['IsResult']) {
                    _txt = msg['IsResult'];
                    Redirect('<?php echo $d['pUrl']; ?>');
                } else {
                    _txt = msg['IsError'];
                    $('#msgModalBody').html(_txt);
                    $('#msgModal').modal('show');
                }
                setTimeout(function () {
                    Redirect('<?php echo $d['pUrl']; ?>');
                }, 2000);
            }, complete: function () {
            }, error: function (xhr, ajaxOptions, thrownError) {
            }});

    }
</script>