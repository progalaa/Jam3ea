<?php
// Required
if ($d['dResults']) {
    $p_arr = array_chunk($d['dResults'], 3);
    ?>
    <div class="recommended_items"><!--recommended_items-->
        <h2 class="title text-center"><?php echo $_['_Related']; ?></h2>

        <div id="recommended-item-carousel" class="carousel slide" data-ride="carousel">
            <div class="carousel-inner">
                <?php
                $j = 0;
                foreach ($p_arr as $ps) {
                    ?>
                    <div class="item<?php echo $j == 0 ? ' active' : ''; ?>">
                        <?php
                        foreach ($ps as $i) {
                            ?>
                            <div class="col-sm-4">
                                <div class="product-image-wrapper">
                                    <div class="single-products">
                                        <div class="productinfo text-center">
                                            <img src="<?php echo GetImageThumbnail($i['Picture'], 255, 200); ?>" alt="<?php echo $i['Name']; ?>" />
                                            <h2>
                                                <span class="oldPrice">
                                                    <?php echo $i['OldPrice']>0 ? $i['OldPrice'] . ' د.ك' : ''; ?>
                                                </span>
                                                <span class="newPrice">
                                                    <?php echo $i['Price']; ?>
                                                    د.ك
                                                </span>
                                            </h2>
                                            <p><?php echo Anchor(GetRewriteUrl('product/i/' . $i['ID'], $i['Alias']), $i['Name']); ?></p>
                                            <div class="mini-cart">
                                                <?php renderQuantity($i); ?>
                                            </div>
                                            <a href="javascript:void(0)" data-id="<?php echo $i['ID']; ?>" class="btn btn-default btn-cart add-to-cart">
                                                <i class="fa fa-shopping-cart"></i>
                                                <?php echo $_['_Add_To_Cart']; ?>
                                            </a>
                                        </div>

                                    </div>
                                </div>
                            </div>
                            <?php
                        }
                        ?>
                    </div>
                    <?php
                    $j++;
                }
                ?>
            </div>
            <a class="left recommended-item-control" href="#recommended-item-carousel" data-slide="prev">
                <i class="fa fa-angle-left"></i>
            </a>
            <a class="right recommended-item-control" href="#recommended-item-carousel" data-slide="next">
                <i class="fa fa-angle-right"></i>
            </a>
        </div>
    </div><!--/recommended_items-->

    <script type="text/javascript">
        $(document).ready(function () {
            $('#recommended-item-carousel').carousel();
        });
    </script>
    <?php
}
?>