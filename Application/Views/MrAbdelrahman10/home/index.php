<?php
$out = Cache::Get('HomePage.' . $l['Code']);
if (!$out) {
    $CanCashe = false;
    ob_start();
    ?>

    <?php
    if (isset($d['dSlideShow'])) {
        $CanCashe = true;
        ?>
        <section id="slider"><!--slider-->
            <div class="row">
                <div class="col-sm-12">
                    <div id="slider-carousel" class="carousel slide" data-ride="carousel">
                        <!--                        <ol class="carousel-indicators">
                        <?php
                        $j = 0;
                        foreach ($d['dSlideShow'] as $i) {
                            ?>
                                                                        <li data-target="#slider-carousel" data-slide-to="<?php echo $j; ?>"<?php echo $j == 0 ? ' class="active"' : ''; ?>></li>
                            <?php
                            $j++;
                        }
                        ?>
                                                </ol>-->

                        <div class="carousel-inner">
                            <?php
                            $j = 0;
                            foreach ($d['dSlideShow'] as $i) {
                                ?>
                                <div class="item<?php echo $j == 0 ? ' active' : ''; ?>">
                                    <div class="col-sm-12">
                                        <img src="<?php echo GetImageThumbnail($i['Picture'], 500, 260); ?>" class="girl img-responsive img-rounded" alt="" />
                                    </div>
                                </div>
                                <?php
                                $j++;
                            }
                            ?>
                        </div>

                        <a href="#slider-carousel" class="left control-carousel" data-slide="prev">
                            <i class="fa fa-angle-left"></i>
                        </a>
                        <a href="#slider-carousel" class="right control-carousel" data-slide="next">
                            <i class="fa fa-angle-right"></i>
                        </a>
                    </div>

                </div>
            </div>
        </section><!--/slider-->
        <?php
    }
    ?>

    <?php
    if ($d['dFeatured']) {
        $CanCashe = true;
        ?>
        <div class="features_items"><!--features_items-->
            <h2 class="title text-center"><?php echo $_['_Featured']; ?></h2>
            <?php
            foreach ($d['dFeatured'] as $i) {
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
                                <a href="javascript:void(0)" data-id="<?php echo $i['ID']; ?>" class="btn btn-default btn-cart add-to-cart" data-quantity="1">
                                    <i class="fa fa-shopping-cart"></i>
                                    <?php echo $_['_Add_To_Cart']; ?>
                                </a>
                            </div>
                        </div>
                        <div class="choose">
                            <ul class="nav nav-pills nav-justified">
                                <li>
                                    <a href="javascript:void(0)" class="auth btn-wishlist wishlist" data-id="<?php echo $i['ID']; ?>">
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
            ?>
        </div><!--features_items-->
        <?php
    }
    /*
      ?>
      <div class="category-tab"><!--category-tab-->
      <div class="col-sm-12">
      <ul class="nav nav-tabs">
      <?php
      foreach ($d['dFeaturedcats'] as $df) {
      $c = $df['Category'];
      ?>
      <li class="active">
      <a href="#tab<?php echo $c['ID']; ?>" data-toggle="tab">
      <?php echo $c['Name']; ?>
      </a>
      </li>
      <?php
      }
      ?>
      </ul>
      </div>
      <div class="tab-content">
      <?php
      foreach ($d['dFeaturedcats'] as $df) {
      $c = $df['Category'];
      $ps = $df['Products'];
      ?>
      <div class="tab-pane fade active in" id="tshirt" >
      <?php
      foreach ($ps as $i) {
      ?>
      <div class="col-sm-3">
      <div class="product-image-wrapper">
      <div class="single-products">
      <div class="productinfo text-center">
      <img src="<?php echo GetImageThumbnail($i['Picture'], 180, 160); ?>" />
      <h2><?php echo $i['Price']; ?></h2>
      <p><?php echo Anchor(GetRewriteUrl('product/i/' . $i['ID'], $i['Alias']), $i['Name']); ?></p>
      <a href="javascript:void(0)" data-id="<?php echo $i['ID']; ?>" class="auth btn btn-default btn-cart add-to-cart">
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
      }
      ?>
      </div>
      </div><!--/category-tab-->
      <?php

      if ($d['dLast']) {
      $p_arr = array_chunk($d['dLast'], 3);
      ?>
      <div class="recommended_items"><!--recommended_items-->
      <h2 class="title text-center"><?php echo $_['_Added_Recently']; ?></h2>

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
      <?php
      echo Anchor(GetRewriteUrl('product/i/' . $i['ID'], $i['Alias']), Img(GetImageThumbnail($i['Picture'], 255, 240))
      );
      ?>
      <h2>
      <?php echo $i['Price']; ?>
      د.ك
      </h2>
      <p><?php echo Anchor(GetRewriteUrl('product/i/' . $i['ID'], $i['Alias']), $i['Name']); ?></p>
      <a href="javascript:void(0)" data-id="<?php echo $i['ID']; ?>" class="auth btn btn-default btn-cart add-to-cart">
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
      <?php
      }
     */
    ?>

    <?php
    $out = ob_get_contents();
    if ($CanCashe) {
        Cache::Set('HomePage.product.' . $l['Code'], $out);
    }
    ob_end_clean();
}
echo $out;
