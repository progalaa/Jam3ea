<?php if (!defined('BASE_DIR')) exit(header('Location: /')); ?>

<div class="container">
    <div class="row">
        <div class="col-md-6 col-md-offset-3">
            <div class="panel panel-primary">
                <div class="panel-heading"><?php echo $d['dTitle']; ?></div>
                <div class="panel-body">
                    <div id="status"></div>
                    <div class="col-md-12">
                        <form class="form-signin" action="<?php echo GetRewriteUrl('profile/changepassword'); ?>" method="POST" id="passForm">
                            <div class="form-group">
                                <label class="col-md-4"><?php echo $_['_OldPassword']; ?></label>
                                <div class="col-md-8">
                                    <div class="input-group">
                                        <?php echo AppendBox('OldPassword', $__, $_['_OldPassword'], '<i class="fa fa-lock"></i>', null, 'password'); ?>
                                        <?php echo ErrorSpan('OldPassword', $errOldPassword); ?>
                                    </div>
                                </div>
                            </div>
                            <br class="clearfix" />
                            <div class="form-group">
                                <label class="col-md-4"><?php echo $_['_NewPassword']; ?></label>
                                <div class="col-md-8">
                                    <div class="input-group">
                                        <?php echo AppendBox('NewPassword', $__, $_['_NewPassword'], '<i class="fa fa-lock"></i>', null, 'password'); ?>
                                        <?php echo ErrorSpan('NewPassword', $errNewPassword); ?>
                                    </div>
                                </div>
                            </div>
                            <br class="clearfix" />
                            <div class="form-group">
                                <label class="col-md-4"><?php echo $_['_rNewPassword']; ?></label>
                                <div class="col-md-8">
                                    <div class="input-group">
                                        <?php echo AppendBox('rNewPassword', $__, $_['_rNewPassword'], '<i class="fa fa-lock"></i>', null, 'password'); ?>
                                        <?php echo ErrorSpan('rNewPassword', $errrNewPassword); ?>
                                    </div>
                                </div>
                            </div>
                            <?php echo InputHidden('Redirect', $d['pUrl']); ?>
                            <br class="clearfix" />
                            <div class="row">
                                <div class="col-md-6 col-md-offset-3">
                                    <button class="btn btn-lg btn-block btn-success" type="submit">
                                        <?php echo $_['_ChangePassword']; ?>
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<script type="text/javascript">

    $(document).ready(function () {
        $('#passForm').submit(function () {
            var _Data = $(this).serialize();
            $.ajax({
                url: '<?php echo GetRewriteUrl('profile/changepassword'); ?>',
                type: 'post',
                data: _Data,
                beforeSend: function () {
                    $('.Error').html('').fadeOut('slow');
                }, success: function (json) {
                    var _Result = (json);
                    if (_Result['Msg']) {
                        $('#msgModalBody').html(_Result['Msg']);
                        $('#msgModal').modal();
                    } else if (_Result['Error']) {
                        var e = _Result['Error'];
                        if (e['Auth']) {
                            alert(e['Auth']);
                            Redirect('<?php echo GetRewriteUrl('profile/signin') ?>');
                        }
                        ShowError(e);
                    }
                    if (_Result['Redirect']) {

                        setTimeout(
                                function ()
                                {
                                    Redirect(_Result['Redirect']);
                                }, 3000);
                    }
                }, complete: function () {
                },
                error: function (xhr, ajaxOptions, thrownError) {
                }
            });
            return false;
        });

    });

</script>