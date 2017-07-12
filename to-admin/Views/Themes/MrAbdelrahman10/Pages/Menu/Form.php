<?php
$d = &$dRow;
?>
<div class="row-fluid">
    <form method="POST" enctype="multipart/form-data" id="form" class="form-horizontal" role="form">
        <div class="col-md-9">
            <input type="hidden" name="ID" id="ID" value="<?php echo GetValue($d['ID'], 0); ?>" />


            <div class="panel panel-primary">
                <div class="panel-heading"><?php echo $_Lang_Details; ?></div>
                <div class="panel-body">
                    <ul id="the-tabs" class="nav nav-tabs">
                        <?php
                        $Data = &$this->Data;
                        foreach ($dLangs as $Lng) {
                            $lID = $Lng['ID'];
                            ?>
                            <li>
                                <?php echo Anchor('#tabs-' . $lID, imgFlag($Lng) . $Lng['LanguageName'], null, false) ?>
                            </li>
                            <?php
                        }
                        ?>
                    </ul>
                    <div class="tab-content">
                        <?php
                        foreach ($dLangs as $Lng) {
                            $lID = $Lng['ID'];
                            ?>
                            <div class="tab-pane" id="tabs-<?php echo $lID; ?>">
                                <div class="form-group">
                                    <?php echo Label('Name-' . $lID, imgFlag($Lng) . $_Name, 'class="col-sm-2 control-label"'); ?>
                                    <div class="col-sm-10">
                                        <?php echo InputBox('Name-' . $lID, $d['Name-' . $lID], $_Name, 'class="DoAlias" data-alias="Alias-' . $lID . '"'); ?>
                                        <?php echo ErrorSpan('Name-' . $lID, $Data['errName-' . $lID]); ?>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <?php echo Label('Description-' . $lID, imgFlag($Lng) . $_Description, 'class="col-sm-2 control-label"'); ?>
                                    <div class="col-sm-10">
                                        <?php echo TextArea('Description-' . $lID, $d['Description-' . $lID], $_Description); ?>
                                        <?php echo ErrorSpan('Description-' . $lID, $Data['errDescription-' . $lID]); ?>
                                    </div>
                                </div>


                            </div>
                        <?php } ?>
                    </div>
                </div>
            </div>

        </div>
        <div class="col-md-3">

            <div class="panel panel-default">
                <div class="panel-heading"><?php echo $_PublishOptions; ?></div>
                <div class="panel-body">
                    <div class="form-group">
                        <?php echo Label('Parent', $_Parent, 'class="col-sm-12 control-label"'); ?>
                        <div class="col-sm-12">
                            <?php echo DropDown('Parent', $dMenusList, $d['ParentID']); ?>
                            <?php echo ErrorSpan('Parent', $errParent); ?>
                        </div>
                    </div>
                    <div class="form-group">
                        <?php echo Label('MenuItemType', $_MenuItemType, 'class="col-sm-12 control-label"'); ?>
                        <div class="col-sm-9">
                            <?php echo DropDown('MenuItemType', $dMenuItemTypes, $d['MenuItemType']); ?>
                            <?php echo ErrorSpan('MenuItemType', $errMenuItemType); ?>
                        </div>
                        <div class="col-sm-3">
                            <?php echo Anchor('#TypeOfMenu', '<i class="icon-zoom-in icon-white"></i>' . $_Choose, 'name="Choose" id="Choose" class="btn btn-success" data-toggle="modal"', false); ?>
                        </div>
                    </div>
                    <div class="form-group">
                        <?php echo Label('Link', $_Link, 'class="col-sm-12 control-label"'); ?>
                        <div class="col-sm-12">
                            <?php echo InputBox('Link', $d['Link'], $_Link, 'dir="ltr"'); ?>
                            <?php echo ErrorSpan('Link', $errLink); ?>
                            <?php echo InputHidden('LinkFormat', $dLinkFormat); ?>
                        </div>
                    </div>
                    <div class="form-group">
                        <?php echo Label('SortingOrder', $_SortingOrder, 'class="col-sm-12 control-label"'); ?>
                        <div class="col-sm-12">
                            <?php echo InputBox('SortingOrder', $d['SortingOrder'], $_SortingOrder, '', 0); ?>
                            <?php echo ErrorSpan('SortingOrder', $errSortingOrder); ?>
                        </div>
                    </div>
                    <div class="form-group">
                        <?php echo Label('Featured', $_Featured, 'class="col-sm-6 control-label"'); ?>
                        <?php echo CheckBox('Featured', $d['Featured']); ?>
                        <?php echo ErrorSpan('Featured', $errFeatured); ?>
                    </div>
                    <div class="form-group">
                        <?php echo Label('State', $_State, 'class="col-sm-6 control-label"'); ?>
                        <?php echo CheckBox('State', $d['State']); ?>
                        <?php echo ErrorSpan('State', $errState); ?>
                    </div>

                    <div class="form-actions">
                        <button type="submit" class="btn btn-large btn-primary btn-submit"><?php echo $_Save; ?></button>
                        <?php echo Anchor(ADM_BASE . $pID, $_Back, 'class="btn btn-large btn-danger"'); ?>
                    </div>
                </div>
            </div>

            <div class="panel panel-default">
                <div class="panel-heading"><?php echo $_PictureOptions; ?></div>
                <div class="panel-body">
                    <div class="form-group">
                        <?php echo Label('Picture', $_Picture, 'class="col-sm-4 control-label"'); ?>
                        <?php echo ImageAjaxUpload($d['Picture'], ADM_BASE . $pID . '/UploadImage', $errPicture); ?>
                    </div>
                </div>
            </div>

        </div>
    </form>
</div>


<div class="modal fade" id="TypeOfMenu">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title"><?php echo $_Details ?></h4>
            </div>
            <div class="modal-body">
                <p id="TypeDetails">
                </p>
            </div>
            <div class="modal-footer">
                <button class="btn col-md-3" data-dismiss="modal" aria-hidden="true">
                    <?php echo $_Cancel ?>
                </button>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div>


<script type="text/javascript" language="javascript">  $(document).ready(function () {
<?php
if ($sAdminForm_Ajax == 1) {
    ?>
            $('#form').submit(function () {
                var _Data = $(this).serialize();
                $.ajax({url: '<?php echo $pUrl; ?>', type: 'post', data: _Data, beforeSend: function () {
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
                    }, error: function (xhr, ajaxOptions, thrownError) {
                        DoError();
                    }});
                return false;
            });
    <?php
}
?>
        $('#MenuItemType').change(function () {
            var l = document.getElementById('Link');
            var d = $('option:selected', this).attr('data-format');
            var e = $('option:selected', this).attr('data-editable');
            var s = $('option:selected', $("#MenuItemType")).attr('data-load');
            l.value = d;
            if (s) {
                $('#Choose').show();
            } else {
                $('#Choose').hide();
            }
            SetReadOnly(e);
        });
        $('#Choose').click(function () {
            var l = document.getElementById('Link');
            var d = $('option:selected', $("#MenuItemType")).attr('data-format');
            var s = $('option:selected', $("#MenuItemType")).attr('data-load');
            if (s) {
                $.ajax({url: '<?php echo ADM_BASE ?>Show/' + s, type: 'get', beforeSend: function () {
                        $("#TypeDetails").html('<img src="<?php echo ADM_CURRENT_URL_TEMPLATE ?>img/ajax-loaders/ajax-loader-6.gif" />');
                        DoWaiting();
                    }, success: function (json) {
                        $("#TypeDetails").html(json);
                    }, complete: function () {
                        $('#Waiting').fadeOut();
                    }, error: function (xhr, ajaxOptions, thrownError) {
                        DoError();
                    }});
            }
        });
        function SetReadOnly(state) {
            var t = document.getElementById('Link');
            if (state == true) {
                t.setAttribute('readOnly', 'readonly');
            } else {
                t.removeAttribute('readOnly')
            }
        }
    });
</script>