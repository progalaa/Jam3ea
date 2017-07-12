<?php
$i = &$d['dResults'];
$oPictures = unserialize($i['SliderPictures']);
$Pictures = array_merge(array($i['Picture']), is_array($oPictures) ? $oPictures : array());
$arr_pics = array_chunk($Pictures, 3);
?>
<div class="product-details"><!--product-details-->
    <div class="col-sm-5">
        <div class="view-product">
            <img id="bigImg" src="<?php echo GetImageOriginal($i['Picture']); ?>" alt="<?php echo $i['Name']; ?>" />
        </div>
        <div id="similar-product" class="carousel slide" data-ride="carousel">

            <!-- Wrapper for slides -->
            <div class="carousel-inner">
                <?php
                $j = 0;
                foreach ($arr_pics as $ps) {
                    ?>
                    <div class="item<?php echo $j == 0 ? ' active' : ''; ?>">
                        <?php
                        foreach ($ps as $p) {
                            ?>
                            <a href="javascript:void(0)" class="thumbPics" data-src="<?php echo $p; ?>">
                                <img src="<?php echo GetImageThumbnail($p, 84, 84); ?>" />
                            </a>
                            <?php
                        }
                        ?>
                    </div>
                    <?php
                    $j++;
                }
                ?>
            </div>

            <!-- Controls -->
            <a class="left item-control" href="#similar-product" data-slide="prev">
                <i class="fa fa-angle-left"></i>
            </a>
            <a class="right item-control" href="#similar-product" data-slide="next">
                <i class="fa fa-angle-right"></i>
            </a>
        </div>

    </div>
    <div class="col-sm-7">
        <div class="product-information"><!--/product-information-->
            <img src="<?php echo APP_CURRENT_URL_TEMPLATE; ?>images/product-details/new.jpg" class="newarrival" alt="" />
            <h2><?php echo $i['Name']; ?></h2>
            <p><?php echo $_['_Code']; ?> : <?php echo $i['Code']; ?></p>
            <!--<img src="<?php echo APP_CURRENT_URL_TEMPLATE; ?>images/product-details/rating.png" alt="" />-->
            <span>
                <div>
                <label><?php echo $_['_Price']; ?> : </label>
<!--                    <span class="oldPrice">
                        <?php echo $i['OldPrice'] ? $i['OldPrice'] . ' د.ك' : ''; ?>
                    </span>-->
                    <span class="newPrice">
                        <?php echo $i['Price']; ?>
                        د.ك
                    </span>
                </div>
                <label><?php echo $_['_Quantity']; ?> : </label>
                <div>
                    <div class="mini-cart">
                        <?php renderQuantity($i); ?>
                    </div>
                </div>
                <a href="javascript:void(0)" data-id="<?php echo $i['ID']; ?>" class="btn btn-fefault cart btn-cart add-to-cart" data-quantity="1">
                    <i class="fa fa-shopping-cart"></i>
                    <?php echo $_['_Add_To_Cart']; ?>
                </a>
            </span>
            <p><b><?php echo $_['_Availability']; ?> : </b> <?php echo $i['Quantity'] ? $_['_Availabile'] : $_['_NotAvailabile']; ?></p>
            <p><b><?php echo $_['_Category']; ?> : </b> <?php echo Anchor(GetRewriteUrl('product/c/' . $i['CategoryID']), $i['CategoryName']); ?></p>
            <?php
            if ($i['BrandName']) {
                ?>
                <p><b><?php echo $_['_Brand']; ?> : </b> <?php echo $i['BrandName']; ?></p>
                <?php
            }
            ?>
        </div><!--/product-information-->
    </div>
</div><!--/product-details-->

<div class="category-tab shop-details-tab">
    <div class="col-sm-12">
        <ul class="nav nav-tabs">
            <li><a href="#details" class="active" data-toggle="tab"><?php echo $_['_Contents']; ?></a></li>
        </ul>
    </div>
    <div class="tab-content">
        <div class="tab-pane fade active in" id="details">
<?php echo GetDecodeHTML($i['Contents']); ?>
        </div>

    </div>
</div>

<div id="related"></div>


<script type="text/javascript">
    $(document).ready(function () {

        $.ajax({
            url: '<?php echo GetRewriteUrl('product/related/' . $i['CategoryID']); ?>',
            type: 'get',
            beforeSend: function () {
            }, success: function (json) {
                $('#related').html(json);
            }, complete: function () {
            }, error: function (xhr, ajaxOptions, thrownError) {
            }
        });

        $('#commentform').submit(function () {
            var _Data = $(this).serialize();
            $.ajax({
                url: '<?php echo GetRewriteUrl('offer/add_comment'); ?>',
                type: 'post',
                data: _Data,
                beforeSend: function () {
                    //DoWaiting();
                    $('.Error').html('').fadeOut('slow');
                }, success: function (json) {
                    if (json['Redirect']) {
                        Redirect(json['Redirect']);
                    } else if (json['Error']) {
                        //DoWarning();
                        var e = json['Error'];
                        var i = new Array("Title", "Contents");
                        ShowError(i, e);
                    } else if (json['Result']) {
                        $('#msgModalBody').html(json['Result']);
                        $('#msgModal').modal();
                        $('#Title, #Contents').val('');
                    }
                }, complete: function () {
                },
                error: function (xhr, ajaxOptions, thrownError) {
                    //DoError();
                }
            });
            return false;
        });
        $('.thumbPics').click(function () {
            var img = $(this).attr('data-src');
            $('#bigImg').attr('src', img);
            $('#bigImgUrl').attr('href', img);
        });
    });
</script>