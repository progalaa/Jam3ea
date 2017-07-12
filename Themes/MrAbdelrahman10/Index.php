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
        <link href="<?php echo APP_CURRENT_URL_TEMPLATE; ?>css/prettyPhoto.css" rel="stylesheet" />
        <link href="<?php echo APP_CURRENT_URL_TEMPLATE; ?>css/price-range.css" rel="stylesheet" />
        <link href="<?php echo APP_CURRENT_URL_TEMPLATE; ?>css/animate.css" rel="stylesheet" />
        <link href="<?php echo APP_CURRENT_URL_TEMPLATE; ?>css/main.css" rel="stylesheet" />
        <link href="<?php echo APP_CURRENT_URL_TEMPLATE; ?>css/responsive.css" rel="stylesheet" />
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
        <link rel="shortcut icon" href="<?php echo APP_CURRENT_URL_TEMPLATE; ?>images/favicon.ico" />
    </head><!--/head-->

    <body>
        <?php
        if (!NO_STYLE) {
            ?>
            <header id="header"><!--header-->
                <div class="header_top"><!--header_top-->
                    <div class="container">
                        <div class="row">
                            <div class="col-sm-6">
                                <div class="contactinfo">
                                    <ul class="nav nav-pills">
                                        <li>
                                            <a href="tel:<?php echo $s['sPhone']; ?>">
                                                <i class="fa fa-phone"></i>
                                                <?php echo $s['sPhone']; ?>
                                            </a>
                                        </li>
                                        <!--                                    <li>
                                                                                <a href="mailto:<?php echo $s['sEmail']; ?>">
                                                                                    <i class="fa fa-envelope"></i>
                                        <?php echo $s['sEmail']; ?>
                                                                                </a>
                                                                            </li>-->
                                    </ul>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="social-icons">
                                    <ul class="nav navbar-nav">
                                        <li>
                                            <a href="https://www.fb.com/<?php echo $s['sFacebook']; ?>" target="_blank">
                                                <img src="<?php echo APP_CURRENT_URL_TEMPLATE; ?>images/social/facebook.png" alt=""/>
                                            </a>
                                        </li>
                                        <li>
                                            <a href="https://twitter.com/<?php echo $s['sTwitter']; ?>" target="_blank">
                                                <img src="<?php echo APP_CURRENT_URL_TEMPLATE; ?>images/social/twitter.png" alt=""/>
                                            </a>
                                        </li>
                                        <li>
                                            <a href="https://instagram.com/<?php echo $s['sInstagram']; ?>" target="_blank">
                                                <img src="<?php echo APP_CURRENT_URL_TEMPLATE; ?>images/social/instagram.png" alt=""/>
                                            </a>
                                        </li>
                                        <!--<li><a href="https://youtube.com/<?php echo $s['sYoutube']; ?>" target="_blank"><i class="fa fa-youtube"></i></a></li>-->
                                        <!--<li><a href="https://plus.google.com/u/0/<?php echo $s['sGoogle_Plus']; ?>" target="_blank"><i class="fa fa-google-plus"></i></a></li>-->
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div><!--/header_top-->

                <div class="header-middle"><!--header-middle-->
                    <div class="container">
                        <div class="row">
                            <div class="col-sm-2">
                                <div class="logo">
                                    <a href="<?php echo BASE_URL; ?>">
                                        <img src="<?php echo APP_CURRENT_URL_TEMPLATE; ?>images/home/logo.png" alt="<?php echo $s['sSiteName']; ?>" class="img-responsive" />
                                    </a>
                                </div>
                            </div>
                            <div class="col-sm-5">
                                <div class="search_box pull-left">
                                    <form method="GET" action="<?php echo GetRewriteUrl('product/search'); ?>">
                                        <input type="text" name="q" placeholder="<?php echo $_['_SearchWord']; ?>" class="form-control" />
                                        <button type="submit" class="btn btn-primary btn-search">
                                            <?php echo $_['_Search']; ?>
                                        </button>
                                    </form>
                                </div>
                            </div>
                            <div class="col-sm-5">
                                <div class="shop-menu">
                                    <ul class="nav navbar-nav">
                                        <?php
                                        if ($d['pUser']) {
                                            ?>
                                            <li>
                                                <a href="<?php echo GetRewriteUrl('profile'); ?>">
                                                    <i class="fa fa-user"></i>
                                                    <?php echo $_['_Profile']; ?>
                                                </a>
                                            </li>
                                            <!--                                        <li>
                                                                                        <a href="<?php echo GetRewriteUrl('wishlist'); ?>">
                                                                                            <i class="fa fa-star"></i>
                                            <?php echo $_['_Wishlist']; ?>
                                                                                        </a>
                                                                                    </li>-->
                                            <!--                                        <li>
                                                                                        <a href="<?php echo GetRewriteUrl('checkout'); ?>">
                                                                                            <i class="fa fa-crosshairs"></i>
                                            <?php echo $_['_Checkout']; ?>
                                                                                        </a>
                                                                                    </li>-->
                                            <li>
                                                <a href="<?php echo GetRewriteUrl('cart'); ?>">
                                                    <i class="fa fa-shopping-cart"></i>
                                                    <?php echo $_['_Cart']; ?>
                                                </a>
                                            </li>
                                            <li>
                                                <a href="<?php echo GetRewriteUrl('profile/signout'); ?>">
                                                    <i class="fa fa-sign-out"></i>
                                                    <?php echo $_['_SignOut']; ?>
                                                </a>
                                            </li>
                                            <?php
                                        } else {
                                            ?>
                                            <li>
                                                <a href="#SignInModal" data-toggle="modal">
                                                    <!--<i class="fa fa-sign-in"></i>-->
                                                    <?php echo $_['_SignIn']; ?>
                                                </a>
                                            </li>
                                            <!--                                        <li>
                                                                                        <a href="<?php echo GetRewriteUrl('profile/register'); ?>">
                                                                                            <i class="fa fa-sign-in"></i>
                                            <?php echo $_['_Register']; ?>
                                                                                        </a>
                                                                                    </li>-->
                                            <?php
                                        }
                                        ?>
                                        <li>
                                            <a href="<?php echo GetRewriteUrl('cart'); ?>">
                                                <span class="cart_items_count">0</span>
                                                <img src="<?php echo APP_CURRENT_URL_TEMPLATE; ?>images/cart.png" class="navCart" />
                                            </a>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div><!--/header-middle-->

                <div class="header-bottom hidden-xs"><!--header-bottom-->
                    <div class="container">
                        <div class="row">
                            <div class="navbar-header">
                                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
                                    <span class="sr-only">Toggle navigation</span>
                                    <span class="icon-bar"></span>
                                    <span class="icon-bar"></span>
                                    <span class="icon-bar"></span>
                                </button>
                                <h2 class="pull-right visible-xs"><?php echo $_['_Categories']; ?></h2>
                            </div>
                            <div class="mainmenu">
                                <?php echo $d['dMainMenu']; ?>
                            </div>
                        </div>
                    </div>
                </div><!--/header-bottom-->
            </header><!--/header-->
            <?php
        }
        ?>
        <section>
            <div class="container<?php echo $d['pID'] == 'home' && $d['pAction'] == 'index' ? '-fluid' : ''; ?>">
                <div class="row">
                    <div class="col-md-12">
                        <?php
                        if (!$d['dFullWidth'] && NO_STYLE == FALSE) {
                            ?>
                            <div class="col-sm-3">
                                <div class="left-sidebar">
                                    <h2><?php echo $_['_Categories']; ?></h2>
                                    <div class="panel-group category-products" id="accordian"><!--category-productsr-->
                                        <?php
                                        echo $d['dSideCategories'];
                                        ?>

                                    </div><!--/category-products-->

                                    <div class="shipping text-center">
                                        <?php
                                        echo GetBanner($d['dBanners'], 'Left1');
                                        ?>
                                    </div>

                                    <!--                                <div id="QRcode" class="text-center">
                                                                        <h4><?php echo $_['_Copy_QRcode']; ?></h4>
                                                                        <img src="http://chart.apis.google.com/chart?cht=qr&chs=250x250&chl=<?php echo $d['pUrl']; ?>" class="img-responsive" />
                                                                    </div>-->

                                </div>
                            </div>
                            <?php
                        }

                        if (isset($_GET['showrequest'])) {
                            echo '<pre dir="ltr">';
                            var_dump($_REQUEST);
                            echo '</pre>';
                            echo '<br />';
                        }
                        ?>
                        <script src="<?php echo APP_CURRENT_URL_TEMPLATE; ?>js/jquery.js"></script>
                        <script src="<?php echo APP_CURRENT_URL_TEMPLATE; ?>js/bootstrap.min.js"></script>
                        <script src="<?php echo APP_CURRENT_URL_TEMPLATE; ?>js/jquery.scrollUp.min.js"></script>
                        <script src="<?php echo APP_CURRENT_URL_TEMPLATE; ?>js/price-range.js"></script>
                        <script src="<?php echo APP_CURRENT_URL_TEMPLATE; ?>js/jquery.prettyPhoto.js"></script>
                        <script src="<?php echo APP_CURRENT_URL_TEMPLATE; ?>js/main.js"></script>
                        <div class="col-sm-<?php echo $d['dFullWidth'] ? 12 : 9; ?>">
                            <?php include $PageContents; ?>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <?php
        if (!NO_STYLE) {
            ?>
            <footer id="footer"><!--Footer-->

                <div class="footer-widget">
                    <div class="container">
                        <div class="row">
                            <div class="col-sm-3">
                                <div class="single-widget">
                                    <h2><?php echo $_['_Pages']; ?></h2>
                                    <ul class="nav nav-pills nav-stacked">
                                        <?php
                                        if ($d['dPages']) {
                                            foreach ($d['dPages'] as $i) {
                                                echo '<li>';
                                                echo Anchor(GetRewriteUrl('page/i/' . $i['ID']), $i['Title']);
                                                echo '</li>';
                                            }
                                        }
                                        echo '<li>';
                                        echo Anchor(GetRewriteUrl('home/contactus'), $_['_Contact']);
                                        echo '</li>';
                                        ?>
                                    </ul>
                                </div>
                            </div>
                            <div class="col-sm-3">
                                <div class="single-widget">
                                    <h2><?php echo $_['_Profile']; ?></h2>
                                    <ul class="nav nav-pills nav-stacked">
                                        <?php
                                        if ($d['pUser']) {
                                            ?>
                                            <li>
                                                <a href="<?php echo GetRewriteUrl('profile'); ?>">
                                                    <?php echo $_['_Profile']; ?>
                                                </a>
                                            </li>
                                            <!--                                        <li>
                                                                                        <a href="<?php echo GetRewriteUrl('wishlist'); ?>">
                                            <?php echo $_['_Wishlist']; ?>
                                                                                        </a>
                                                                                    </li>-->
                                            <!--                                        <li>
                                                                                        <a href="<?php echo GetRewriteUrl('checkout'); ?>">
                                            <?php echo $_['_Checkout']; ?>
                                                                                        </a>
                                                                                    </li>-->
                                            <li>
                                                <a href="<?php echo GetRewriteUrl('cart'); ?>">
                                                    <?php echo $_['_Cart']; ?>
                                                </a>
                                            </li>
                                            <li>
                                                <a href="<?php echo GetRewriteUrl('profile/signout'); ?>">
                                                    <?php echo $_['_SignOut']; ?>
                                                </a>
                                            </li>
                                            <?php
                                        } else {
                                            ?>
                                            <li>
                                                <a href="<?php echo GetRewriteUrl('profile/signin'); ?>">
                                                    <?php echo $_['_SignIn']; ?>
                                                </a>
                                            </li>
                                            <li>
                                                <a href="<?php echo GetRewriteUrl('profile/register'); ?>">
                                                    <?php echo $_['_Register']; ?>
                                                </a>
                                            </li>
                                            <?php
                                        }
                                        ?>
                                    </ul>
                                </div>
                            </div>
                            <div class="col-sm-2">
                                <!--                            <div class="single-widget">
                                                                <h2><?php echo $_['_Categories']; ?></h2>
                                                                <ul class="nav nav-pills nav-stacked">
                                <?php
                                foreach ($d['dPages'] as $i) {
                                    echo '<li>';
                                    echo Anchor(GetRewriteUrl('page/i/' . $i['ID']), $i['Title']);
                                    echo '</li>';
                                }
                                ?>
                                                                </ul>
                                                            </div>-->
                            </div>
                            <div class="col-sm-3">
                                <div class="single-widget">
                                    <h2><?php echo $_['_MailingList']; ?></h2>
                                    <input type="text" placeholder="<?php echo $_['_Email']; ?>" id="mlEmail" />
                                    <a class="btn btn-default" id="btnMailingList"><i class="fa fa-arrow-circle-o-right"></i></a>
                                    <p><?php echo $_['_MailingList_Subscribe_Description']; ?></p>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>

                <div class="footer-bottom">
                    <div class="container">
                        <div class="row">
                            <p class="pull-left">
                                <?php echo $_['_Powered']; ?>
                            </p>
                            <p class="pull-right">
                                <?php echo $_['_Copyright']; ?>
                            </p>
                        </div>
                    </div>
                </div>

            </footer><!--/Footer-->

            <div id="floatCart">
                <a href="<?php echo GetRewriteUrl('cart'); ?>">
                    <span class="cart_items_count">0</span>
                    <img src="<?php echo APP_CURRENT_URL_TEMPLATE; ?>images/cart.png" />
                </a>
            </div>
            <?php
        }
        ?>
        <div class="hidden-xs">
            <div class="alert alert-success" id="alert-message" role="alert">
                <i class="fa fa-info-circle"></i>
                <span id="alert-message-text"></span>
            </div>
        </div>
        <!-- Modal signin -->
        <div id="signin-modal" class="modal fade"  tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-sm" role="document">
                <div class="modal-content modal-msg">
                    <i class="fa fa-lock fa-4x"></i><br>
                    <h2>
                        <?php echo Anchor(GetRewriteUrl('profile/signin'), $_['_SignIn_Please']); ?>
                    </h2>
                </div>
            </div>
        </div>
        <!-- Modal message -->
        <div id="msgModal" class="modal fade"  tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-sm" role="document">
                <div class="modal-content modal-msg">
                    <i class="fa fa-info-circle fa-4x"></i>
                    <h1 id="msgModalBody"></h1>
                </div>
            </div>
        </div>
        <!-- Modal like -->
        <div class="modal fade"  tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-sm" role="document">
                <div class="modal-content modal-msg">
                    <i class="fa fa-thumbs-up fa-4x"></i>
                    <h1 id="FavMsg"></h1>
                </div>
            </div>
        </div>
        <?php
        if (!$d['pUser']) {
            ?>
            <!-- Modal SignIn -->
            <div id="SignInModal" class="modal fade"  tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-sm" role="document">
                    <div class="modal-content modal-form">
                        <div class="col-md-10 col-md-offset-1">
                            <div class="well">
                                <form method="post" action="<?php echo GetRewriteUrl('profile/signin'); ?>">
                                    <div class="form-group">
                                        <label for="UserName"><?php echo $_['_UserName']; ?> <em>*</em></label>
                                        <input type="text" class="form-control" placeholder="<?php echo $_['_UserName']; ?>" name="UserName" required autofocus>
                                    </div>
                                    <div class="form-group">
                                        <label for="Password"><?php echo $_['_Password']; ?> *</label>
                                        <input type="password" name="Password" class="form-control" required placeholder="<?php echo $_['_Password']; ?>">
                                    </div>
                                    <div class="form-group">
                                        <div class="col-md-6 col-md-offset-3">
                                            <button class="btn btn-primary btn-block" id="login" name="login" title="" type="submit">
                                                <span> <?php echo $_['_SignIn']; ?>  </span>
                                            </button>
                                        </div>
                                    </div>
                                    <div class="form-group remember-forgot">
                                        <a href="<?php echo GetRewriteUrl('profile/forgotpassword'); ?>" class="forgot">
                                            <?php echo $_['_Forgot_Password']; ?>?
                                        </a>

                                        <a href="<?php echo GetRewriteUrl('profile/register'); ?>" class="pull-left">
                                            <?php echo $_['_Register']; ?>
                                        </a>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <?php
        }
        ?>
        <script type="text/javascript">
            function parseArabic(str) {
                return Number(str.replace(/[٠١٢٣٤٥٦٧٨٩]/g, function (d) {
                    return d.charCodeAt(0) - 1632;
                }).replace(/[۰۱۲۳۴۵۶۷۸۹]/g, function (d) {
                    return d.charCodeAt(0) - 1776;
                }));
            }

//            $(window).bind('resize', function (event) {
//                var widowWidth = $(window).width();
//                if (widowWidth >= 1024) {
//                    $('.txtQ').prop('disabled', false);
//                } else if ((widowWidth <= 1023)) {
//                    $('.txtQ').prop('disabled', true);
//                }
//            });

            $(document).ready(function () {
                $('.txtQ').prop('disabled', true);
                $(".quantity").on("click", function () {
                    $(this).select();
                });
                //Cart
                $('.btn-cart').click(function (e) {
                    var _ID = $(this).attr('data-id');
                    var _Quantity = $(this).attr('data-quantity');
                    var btn = $(this);
                    $.ajax({
                        url: '<?php echo GetRewriteUrl('cart/add'); ?>',
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
                            } else {
                                _txt = msg['IsError'];
                            }
                            $(btn).html('<i class="fa fa-check-circle"></i> <?php echo $_['_Cart_View']; ?>');
                            $(btn).attr('href', '<?php echo GetRewriteUrl('cart'); ?>');
                            $(btn).addClass('btn-success');
                            $(btn).removeClass('btn-cart');
                            $('#alert-message-text').html(_txt);
                            $('#alert-message').fadeIn('slow').delay(3000).fadeOut('slow');
                            getCartItemsCount();
                        }, complete: function () {
                        }, error: function (xhr, ajaxOptions, thrownError) {
                        }});
                });

                $('.deletecart').click(function (e) {
                    var _ID = $(this).attr('data-id');
                    var _Data = "Product=" + _ID;
                    $.ajax({
                        url: '<?php echo GetRewriteUrl('cart/delete'); ?>',
                        type: 'post',
                        data: _Data,
                        beforeSend: function () {
                        }, success: function (json) {
                            var msg = (json);
                            var _txt = '';
                            if (msg['IsResult']) {
                                _txt = msg['IsResult'];
                            } else {
                                _txt = msg['IsError'];
                            }
                            $('#msgModalBody').html(_txt);
                            $('#msgModal').modal('show');
                            setTimeout(function () {
                                Redirect('<?php echo $d['pUrl']; ?>');
                            }, 2000);
                        }, complete: function () {
                        }, error: function (xhr, ajaxOptions, thrownError) {
                        }});
                    e.preventDefault();
                    return false;
                });

<?php
if ($d['pUser']) {
    ?>
                    //Wishlist
                    $('.btn-wishlist').click(function (e) {
                        var _ID = $(this).attr('data-id');
                        var _Data = "Product=" + _ID;
                        $.ajax({
                            url: '<?php echo GetRewriteUrl('wishlist/add'); ?>',
                            type: 'post',
                            data: _Data,
                            beforeSend: function () {
                            }, success: function (json) {
                                var msg = (json);
                                var _txt = '';
                                if (msg['IsResult']) {
                                    _txt = msg['IsResult'];
                                } else {
                                    _txt = msg['IsError'];
                                }
                                $('#msgModalBody').html(_txt);
                                $('#msgModal').modal('show');
                            }, complete: function () {
                            }, error: function (xhr, ajaxOptions, thrownError) {
                            }});
                        e.preventDefault();
                        return false;
                    });

                    $('.deletewishlist').click(function (e) {
                        var _ID = $(this).attr('data-id');
                        var _Data = "Product=" + _ID;
                        $.ajax({
                            url: '<?php echo GetRewriteUrl('wishlist/delete'); ?>',
                            type: 'post',
                            data: _Data,
                            beforeSend: function () {
                            }, success: function (json) {
                                var msg = (json);
                                var _txt = '';
                                if (msg['IsResult']) {
                                    _txt = msg['IsResult'];
                                } else {
                                    _txt = msg['IsError'];
                                }
                                $('#msgModalBody').html(_txt);
                                $('#msgModal').modal('show');
                                setInterval(function () {
                                    Redirect('<?php echo $d['pUrl']; ?>');
                                }, 2000);
                            }, complete: function () {
                            }, error: function (xhr, ajaxOptions, thrownError) {
                            }});
                        e.preventDefault();
                        return false;
                    });


    <?php
} else {
    ?>
                    $('.auth').attr('href', '#signin-modal');
                    $('.auth').attr('data-toggle', 'modal');

    <?php
}
?>
                $('#btnMailingList').click(function (e) {
                    var _Email = $('#mlEmail').val();
                    var _Data = "Email=" + _Email;
                    $.ajax({
                        url: '<?php echo GetRewriteUrl('mailinglist/add'); ?>',
                        type: 'post',
                        data: _Data,
                        beforeSend: function () {
                        }, success: function (json) {
                            var msg = (json);
                            var _txt = '';
                            if (msg['IsResult']) {
                                _txt = msg['IsResult'];
                            } else {
                                _txt = msg['IsError'];
                            }
                            $('#msgModalBody').html(_txt);
                            $('#msgModal').modal('show');
                            $('#mlEmail').val('');
                            setTimeout(
                                    function ()
                                    {
                                        $('#msgModal').modal('hide');
                                    }, 5000);
                        }, complete: function () {
                        }, error: function (xhr, ajaxOptions, thrownError) {
                        }});
                    e.preventDefault();
                    return false;
                });


                $('.minipQ').click(function (e) {
                    var _ID = $(this).attr('data-id');
                    var _Quantity = parseInt($('#miniq-' + _ID).val());
                    $('#miniq-' + _ID).val(_Quantity + 1);
                    updateminiQuantity(_ID);
                });
                $('.minimQ').click(function (e) {
                    var _ID = $(this).attr('data-id');
                    var _Quantity = parseInt($('#miniq-' + _ID).val()) - 1;
                    if (_Quantity < 1) {
                        _Quantity = 1;
                    }
                    $('#miniq-' + _ID).val(_Quantity);
                    updateminiQuantity(_ID);
                });
                $('.quantity').change(function (e) {
                    updateminiQuantity($(this).attr('data-id'));
                    e.preventDefault();
                    return false;
                });

                getCartItemsCount();

            });

            function updateminiQuantity(id) {
                $('.add-to-cart[data-id="' + id + '"]').attr('data-quantity', $('#miniq-' + id).val());
            }

            function getCartItemsCount() {
<?php
get_ajax_data_into_div(GetRewriteUrl('cart/items_count'), 'cart_items_count', '.');
?>
            }

        </script>
        <!-- Piwik -->
        <script type="text/javascript">
            var _paq = _paq || [];
            _paq.push(['trackPageView']);
            _paq.push(['enableLinkTracking']);
            (function () {
                var u = "//jm3eia.com/piwik/";
                _paq.push(['setTrackerUrl', u + 'piwik.php']);
                _paq.push(['setSiteId', 1]);
                var d = document, g = d.createElement('script'), s = d.getElementsByTagName('script')[0];
                g.type = 'text/javascript';
                g.async = true;
                g.defer = true;
                g.src = u + 'piwik.js';
                s.parentNode.insertBefore(g, s);
            })();
        </script>
        <noscript><p><img src="//jm3eia.com/piwik/piwik.php?idsite=1" style="border:0;" alt="" /></p></noscript>
        <!-- End Piwik Code -->
    </body>
</html>