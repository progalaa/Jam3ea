<?php if (!defined('BASE_DIR')) exit(header('Location: /')); ?>
<div class="row">
    <div class="col-md-8 col-md-offset-2">
        <?php
        $u = &$d['pUser'];
        ?>
        <div class="panel panel-primary">
            <div class="panel-heading">
                <?php echo $d['dTitle']; ?>
            </div>
            <div class="panel-body">
                <form action="<?php echo GetRewriteUrl('profile/changeuserdata'); ?>" method="POST" id="regForm">
                    <div class="form-group">
                        <label for="FullName" class="col-md-3"> <?php echo $_['_FullName']; ?> <em>*</em></label>
                        <div class="col-md-9">
                            <input type="text" class="form-control" name="FullName" placeholder="<?php echo $_['_FullName']; ?>" value="<?php echo $u['FullName']; ?>">
                            <?php echo ErrorSpan('FullName', $d['errFullName']); ?>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="UserName" class="col-md-3"> <?php echo $_['_UserName']; ?> <em>*</em></label>
                        <div class="col-md-9">
                            <input type="text" class="form-control" name="UserName" placeholder="<?php echo $_['_UserName']; ?>" value="<?php echo $u['UserName']; ?>">
                            <?php echo ErrorSpan('UserName', $d['errUserName']); ?>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="Email" class="col-md-3"> <?php echo $_['_Email']; ?> <em>*</em></label>
                        <div class="col-md-9">
                            <input type="text" class="form-control" name="Email" placeholder="<?php echo $_['_Email']; ?>" value="<?php echo $u['Email']; ?>">
                            <?php echo ErrorSpan('Email', $d['errEmail']); ?>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="Mobile" class="col-md-3"> <?php echo $_['_Mobile']; ?> <em>*</em></label>
                        <div class="col-md-9">
                            <input type="text" class="form-control" name="Mobile" placeholder="<?php echo $_['_Mobile']; ?>" value="<?php echo $u['Mobile']; ?>">
                            <?php echo ErrorSpan('Mobile', $d['errMobile']); ?>
                        </div>
                    </div>
                    <div class="panel panel-info">
                        <div class="panel-heading">
                            <?php echo $_['_Address']; ?>
                        </div>
                        <div class="panel-body">
                            <div class="form-group">
                                <label for="Zone" class="col-md-3"> <?php echo $_['_Zone']; ?> </label>
                                <div class="col-md-9">
                                    <input type="text" class="form-control" name="Zone" placeholder="<?php echo $_['_Zone']; ?>" value="<?php echo $u['Zone']; ?>">
                                    <?php echo ErrorSpan('Zone', $d['errZone']); ?>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="Widget" class="col-md-3"> <?php echo $_['_Widget']; ?> </label>
                                <div class="col-md-9">
                                    <input type="text" class="form-control" name="Widget" placeholder="<?php echo $_['_Widget']; ?>" value="<?php echo $u['Widget']; ?>">
                                    <?php echo ErrorSpan('Widget', $d['errWidget']); ?>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="Street" class="col-md-3"> <?php echo $_['_Street']; ?> </label>
                                <div class="col-md-9">
                                    <input type="text" class="form-control" name="Street" placeholder="<?php echo $_['_Street']; ?>" value="<?php echo $u['Street']; ?>">
                                    <?php echo ErrorSpan('Street', $d['errStreet']); ?>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="Gada" class="col-md-3"> <?php echo $_['_Gada']; ?> </label>
                                <div class="col-md-9">
                                    <input type="text" class="form-control" name="Gada" placeholder="<?php echo $_['_Gada']; ?>" value="<?php echo $u['Gada']; ?>">
                                    <?php echo ErrorSpan('Gada', $d['errGada']); ?>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="House" class="col-md-3"> <?php echo $_['_House']; ?> </label>
                                <div class="col-md-9">
                                    <input type="text" class="form-control" name="House" placeholder="<?php echo $_['_House']; ?>" value="<?php echo $u['House']; ?>">
                                    <?php echo ErrorSpan('House', $d['errHouse']); ?>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 col-md-offset-4">
                        <div class="form-group">
                            <button class="btn btn-block" type="submit">
                                <span><span> <?php echo $_['_ChangeData']; ?> </span></span>
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

    </div>
</div>


<script type="text/javascript">

    $(document).ready(function () {
        $('#regForm').submit(function () {
            var _Data = $(this).serialize();
            $.ajax({
                url: '<?php echo GetRewriteUrl('profile/changeuserdata'); ?>',
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