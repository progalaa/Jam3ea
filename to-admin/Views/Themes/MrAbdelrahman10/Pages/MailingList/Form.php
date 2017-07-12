<?php
$d = &$dRow;
?>
<div class="row-fluid">
    <form method="POST" enctype="multipart/form-data" id="form" class="form-horizontal" role="form">
        <div class="col-md-9">
            <input type="hidden" name="ID" id="ID" value="<?php echo GetValue($d['ID'], 0); ?>" />
            <div class="form-group">
                <?php echo Label('Subject', $_Subject, 'class="col-sm-2 control-label"'); ?>
                <div class="col-sm-10">
                    <?php echo InputBox('Subject', $d['Subject']); ?>
                    <?php echo ErrorSpan('Subject', $errSubject); ?>
                </div>
            </div>
            <div class="form-group">
                <?php echo Label('Message', $_Message, 'class="col-sm-2 control-label"'); ?>
                <div class="col-sm-10">
                    <?php echo TextArea('Message', $d['Message'], $_Message, 'class="editor"'); ?>
                    <?php echo ErrorSpan('Message', $errMessage); ?>
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

<script type="text/javascript" language="javascript"> $(document).ready(function () {
<?php
if ($sAdminForm_Ajax == 1) {
    ?>
            $('#form').submit(function (e) {
                var _Data = $(this).serialize();
                $.ajax({
                    url: '<?php echo $pUrl; ?>',
                    type: 'post',
                    data: _Data,
                    beforeSend: function () {
                        BeforeSubmit();
                    }, success: function (json) {
                        var _Result = json;
                        if (_Result['Redirect']) {
                            DoSuccessed();
                            Redirect(_Result['Redirect']);
                        } else if (_Result['Error']) {
                            DoWarning();
                            var e = _Result['Error'];
                            ShowError(e);
                        }
                    }, complete: function () {
                        AfterSubmit();
                    }, error: function (xhr, ajaxOptions, thrownError) {
                        DoError();
                    }});
                e.preventDefault();
                return false;
            });
    <?php
}
?>
    });
</script>