<?php
$d = &$dRow;
?>
<div class="row-fluid">
    <form method="POST" enctype="multipart/form-data" id="form" class="form-horizontal" role="form">
        <div class="col-md-9">
            <input type="hidden" name="ID" id="ID" value="<?php echo GetValue($d['ID'], 0); ?>" />

            <div class="panel panel-primary">
                <div class="panel-heading"><?php echo $_Notifications; ?></div>
                <div class="panel-body">
                    <div class="form-group">
                        <?php echo Label('Title', $_Title, 'class="col-sm-3 control-label"'); ?>
                        <div class="col-sm-9">
                            <?php echo InputBox('Title', $d['Title'], $_Title); ?>
                            <?php echo ErrorSpan('Title', $errTitle); ?>
                        </div>
                    </div>
                    <div class="form-group">
                        <?php echo Label('Details', $_Code, 'class="col-sm-3 control-label"'); ?>
                        <div class="col-sm-9">
                            <?php echo TextArea('Details', $d['Details'], $_Code, 'class="editor1"'); ?>
                            <?php echo ErrorSpan('Details', $errDetails); ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="panel panel-default">
                <div class="panel-heading"><?php echo $_PublishOptions; ?></div>
                <div class="panel-body">

                    <div class="form-actions">
                        <button type="submit" class="btn btn-large btn-primary btn-submit"><?php echo $_Send; ?></button>
                        <?php echo Anchor(ADM_BASE . $pID, $_Back, 'class="btn btn-large btn-danger"'); ?>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>

<script type="text/javascript" language="javascript">
    $(document).ready(function () {
        LoadType();
<?php
if ($sAdminForm_Ajax == 1) {
    ?>
            $('#form').submit(function () {
                var _Data = $(this).serialize();
                $.ajax({
                    url: '<?php echo $pUrl; ?>',
                    type: 'post', data: _Data,
                    beforeSend: function () {
                        DoWaiting();
                        $('.Error').fadeOut('slow');
                        $('.Error').html('');
                    }, success: function (json) {
                        var _Result = $.parseJSON(json);
                        if (_Result['Redirect']) {
                            DoSuccessed();
                            Redirect(_Result['Redirect']);
                        } else if (_Result['Error']) {
                            DoWarning();
                            var e = _Result['Error'];
                            ShowError(e);
                        }
                    }, complete: function () {
                    },
                    error: function (xhr, ajaxOptions, thrownError) {
                        DoError();
                    }
                });
                e.preventDefault();
                return false;
            });
    <?php
}
?>
    });
</script>