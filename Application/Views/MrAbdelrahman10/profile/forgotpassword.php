<?php if (!defined('BASE_DIR')) exit(header('Location: /')); ?>

<div class="container">
    <div class="row">
        <div class="col-md-6 col-md-offset-3">
            <div class="panel panel-primary">
                <div class="panel-heading"><?php echo $d['dTitle']; ?></div>
                <div class="panel-body">
                    <div id="status"></div>
                    <div class="col-md-12">
                        <form class="form-signin" action="<?php echo GetRewriteUrl('profile/forgotpassword'); ?>" method="POST" id="passForm">
                            <div class="form-group">
                                <label class="col-md-3"><?php echo $_['_Email']; ?></label>
                                <div class="col-md-9">
                                    <div class="input-group">
                                        <?php echo AppendBox('Email', $__, $_['_Email'], '<i class="fa fa-envelope"></i>',null,'email', 'class="form-control"'); ?>
                                        <?php echo ErrorSpan('Error', $d['Error']); ?>
                                    </div>
                                </div>
                            </div>
                            <br class="clearfix" />
                            <div class="row">
                                <div class="col-md-6 col-md-offset-3">
                                    <button class="btn btn-lg btn-block btn-success" type="submit">
                                        <?php echo $_['_Reset_Password']; ?>
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
                url: '<?php echo GetRewriteUrl('profile/forgotpassword'); ?>',
                type: 'post',
                data: _Data,
                beforeSend: function () {
                    $('.Error').html('').fadeOut('slow');
                }, success: function (json) {
                    var _Result = (json);
                    if (_Result['Msg']) {
                        $('#msgModalBody').html(_Result['Msg']);
                        $('#msgModal').modal();
                    } else if (_Result) {
                        var e = _Result;
                        ShowError(e);
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