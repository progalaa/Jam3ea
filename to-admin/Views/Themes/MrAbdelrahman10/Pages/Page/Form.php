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
                                    <?php echo Label('Title-' . $lID, imgFlag($Lng) . $_Title, 'class="col-sm-2 control-label"'); ?>
                                    <div class="col-sm-10">
                                        <?php echo InputBox('Title-' . $lID, $d['Title-' . $lID], $_Title, 'class="DoAlias" data-alias="Alias-' . $lID . '"'); ?>
                                        <?php echo ErrorSpan('Title-' . $lID, $Data['errTitle-' . $lID]); ?>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <?php echo Label('Alias-' . $lID, imgFlag($Lng) . $_Alias, 'class="col-sm-2 control-label"'); ?>
                                    <div class="col-sm-10">
                                        <?php echo InputBox('Alias-' . $lID, $d['Alias-' . $lID], $_Alias, 'maxlength="50"'); ?>
                                        <?php echo ErrorSpan('Alias-' . $lID, $Data['errAlias-' . $lID]); ?>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <?php echo Label('Contents-' . $lID, imgFlag($Lng) . $_Contents, 'class="col-sm-2 control-label"'); ?>
                                    <div class="col-sm-10">
                                        <?php
                                        $Cnt = GetDecodeHTML(GetValue($d['Contents-' . $lID]));
                                        echo TextArea('Contents-' . $lID, $Cnt, $_Contents, 'class="editor"'); ?>
                                        <?php echo ErrorSpan('Contents-' . $lID, $Data['errContents-' . $lID]); ?>
                                    </div>
                                </div>


                                <div class="panel panel-default">
                                    <div class="panel-heading"><?php echo $_SeoOptions; ?></div>
                                    <div class="panel-body">
                                        <div class="form-group">
                                            <?php echo Label('Description-' . $lID, imgFlag($Lng) . $_Description, 'class="col-sm-2 control-label"'); ?>
                                            <div class="col-sm-10">
                                                <?php echo TextArea('Description-' . $lID, $d['Description-' . $lID], $_Description); ?>
                                                <?php echo ErrorSpan('Description-' . $lID, $Data['errDescription-' . $lID]); ?>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <?php echo Label('Keywords-' . $lID, imgFlag($Lng) . $_Keywords, 'class="col-sm-2 control-label"'); ?>
                                            <div class="col-sm-10">
                                                <?php echo TextArea('Keywords-' . $lID, $d['Keywords-' . $lID], $_Keywords, 'class="tags"'); ?>
                                                <?php echo ErrorSpan('Keywords-' . $lID, $Data['errKeywords-' . $lID]); ?>
                                            </div>
                                        </div>
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
                        <?php echo Label('SortingOrder', $_SortingOrder, 'class="col-sm-5 control-label"'); ?>
                        <div class="col-sm-5">
                            <?php echo InputBox('SortingOrder', $d['SortingOrder'], $_SortingOrder, '', 0); ?>
                            <?php echo ErrorSpan('SortingOrder', $errSortingOrder); ?>
                        </div>
                    </div>
                    <div class="form-group">
                        <?php echo Label('State', $_State, 'class="col-sm-6 control-label"'); ?>
                        <?php echo CheckBox('State', $d['State']); ?>
                        <?php echo ErrorSpan('State', $errState); ?>
                    </div>

                    <div class="form-actions">
                        <button type="submit" class="btn btn-large btn-primary btn-submit"><?php echo $_Save; ?></button>
                        <?php // echo Anchor('javascript:void(0)', $_Preview, 'id="Preview" target="_blank" class="btn btn-large btn-primary"', false); ?>
                        <?php echo Anchor(ADM_BASE . $pID, $_Back, 'class="btn btn-large btn-danger"'); ?>
                    </div>
                </div>
            </div>

        </div>
    </form>
</div>