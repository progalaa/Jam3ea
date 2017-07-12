<div class="breadcrumb">
    <form method="get" class="form-horizontal" role="form">
        <div class="col-md-6">
            <div class="form-group">
                <?php echo Label('daterange', $_OrderDate, 'class="col-sm-4 control-label"'); ?>
                <div class="col-sm-8">
                    <div class="col-md-6">
                        <div class="form-group">
                            <?php echo Label('FromDate', $_FromDate, 'class="col-sm-3 control-label"'); ?>
                            <div class="col-sm-9">
                                <?php echo InputBox('FromDate', $_GET['FromDate'], $_FromDate, 'class="date"'); ?>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <?php echo Label('ToDate', $_ToDate, 'class="col-sm-3 control-label"'); ?>
                            <div class="col-sm-9">
                                <?php echo InputBox('ToDate', $_GET['ToDate'], $_ToDate, 'class="date"'); ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="form-group">
                <?php echo Label('User', "{$_FullName} / {$_Mobile}", 'class="col-sm-4 control-label"'); ?>
                <div class="col-sm-8">
                    <?php echo InputAutoComplete('FullName', 'User', ADM_BASE . $pID . '/AutoCompleteUsers', $_GET['User'], $_GET['User'], $_FullName); ?>
                </div>
            </div>
            <?php
            echo InputHidden('ByUser');
            echo InputHidden('ByProduct');
            ?>
        </div>
        <br class="clearfix" />
        <!--        <div class="col-md-6">
                    <div class="form-group">
        <?php echo Label('CategoryID', $_Category, 'class="col-sm-4 control-label"'); ?>
                        <div class="col-sm-8">
        <?php echo DropDown('CategoryID', $dCategoriesList, $_GET['CategoryID']); ?>
        <?php echo ErrorSpan('CategoryID', $errCategoryID); ?>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
        <?php echo Label('Product', $_Product, 'class="col-sm-4 control-label"'); ?>
                        <div class="col-sm-8">
        <?php echo DropDown('ProductID', $dProductsList, $_GET['ProductID']); ?>
                        </div>
                    </div>
                </div>-->
        <div class="col-md-6 col-md-offset-3">
            <div class="col-md-6 no-padding">
                <button type="submit" class="btn btn-success btn-block">
                    <i class="fa fa-search"></i>
                    <?php echo $_Search; ?>
                </button>
            </div>
            <div class="col-md-6 no-padding">
                <a class="btn btn-danger btn-block" href="<?php echo $pUrl; ?>">
                    <i class="fa fa-repeat"></i>
                    <?php echo $_Reset; ?>
                </a>
            </div>
        </div>
    </form>
</div>
<div class="breadcrumb">
<h3>
    إجمالي المبيعات :-
    <?php echo $dTotal; ?>
</h3>
</div>
<?php
include_once ADM_CURRENT_DIR_PAGES . $pID . '/Index.php';
//echo _autoComplete('Product');
//echo _autoComplete('User');
?>


<script type="text/javascript">
    $(document).ready(function () {
        $('#Buttons *').hide('slow');

        $('#CategoryID').change(function () {

            $.ajax({url: '<?php echo ADM_BASE . $pID; ?>/ProductsByCategory/' + $(this).val(),
                type: 'get',
                beforeSend: function () {
                }, success: function (json) {
                    $('#ProductID').html(json);
                }, complete: function () {
                }, error: function (xhr, ajaxOptions, thrownError) {
                }});

        });

    });
</script>