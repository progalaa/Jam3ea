<?php
if ($d['dResults']) {
    ?>
    <div class="features_items"><!--features_items-->
        <h2 class="title text-center"><?php echo $d['dTitle']; ?></h2>
        <?php
        foreach ($d['dResults'] as $i) {
            ?>
            <div class="col-sm-4 col-xs-6">
                <div class="product-image-wrapper">
                    <div class="single-products">
                        <div class="productinfo text-center">
                            <?php
                            echo Anchor(GetRewriteUrl('product/i/' . $i['ID'], $i['Alias']), Img(GetImageThumbnail($i['Picture'], 255, 240))
                            );
                            ?>
                            <h2>
                                <span class="oldPrice">
                                    <?php echo $i['OldPrice'] > 0 ? $i['OldPrice'] . ' د.ك' : ''; ?>
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
                            <a href="javascript:void(0)" data-id="<?php echo $i['ID']; ?>" class="btn btn-default btn-cart add-to-cart" data-quantity="1">
                                <i class="fa fa-shopping-cart"></i>
                                <?php echo $_['_Add_To_Cart']; ?>
                            </a>
                        </div>
                    </div>
                    <div class="choose">
                        <ul class="nav nav-pills nav-justified">
                            <li>
                                <a href="javascript:void(0)" class="auth btn-wishlist" data-id="<?php echo $i['ID']; ?>">
                                    <i class="fa fa-plus-square"></i>
                                    <?php echo $_['_Add_To_Wishlist']; ?>
                                </a>
                            </li>
                            <li>
                                <a href="javascript:void(0)" class="btn-cart" data-id="<?php echo $i['ID']; ?>" data-quantity="1">
                                    <i class="fa fa-plus-square"></i>
                                    <?php echo $_['_Add_To_Cart']; ?>
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
            <?php
        }
        echo $d['dRenderNav'];
        ?>
    </div><!--features_items-->
    <?php
} else {
    ?>
    <div class="col-md-8 col-md-offset-2">
        <div class="alert alert-info">
            <?php echo $_['_NoResults']; ?>
        </div>
    </div>
    <?php
}
