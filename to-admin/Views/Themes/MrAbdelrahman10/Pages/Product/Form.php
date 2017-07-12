<?php
$d = &$dRow;
?>
<div class="row-fluid">
    <form method="POST" enctype="multipart/form-data" id="form" class="form-horizontal" role="form">
        <div class="col-md-12">
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
                                    <?php echo Label('Contents-' . $lID, imgFlag($Lng) . $_Contents, 'class="col-sm-2 control-label"'); ?>
                                    <div class="col-sm-10">
                                        <?php
                                        $cnts = GetDecodeHTML(GetValue($d['Contents-' . $lID]));
                                        echo TextArea('Contents-' . $lID, $cnts, $_Contents, 'class="editor"'); ?>
                                        <?php echo ErrorSpan('Contents-' . $lID, $Data['errContents-' . $lID]); ?>
                                    </div>
                                </div>



                                <div class="panel panel-default">
                                    <div class="panel-heading"><?php echo $_SeoOptions; ?></div>
                                    <div class="panel-body">
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <?php echo Label('Alias-' . $lID, $_Alias, 'class="col-sm-12 control-label"'); ?>
                                                <div class="col-sm-12">
                                                    <?php echo InputBox('Alias-' . $lID, $d['Alias-' . $lID], $_Alias); ?>
                                                    <?php echo ErrorSpan('Alias-' . $lID, $Data['errAlias-' . $lID]); ?>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <?php echo Label('Description-' . $lID, $_Description, 'class="col-sm-12 control-label"'); ?>
                                                <div class="col-sm-12">
                                                    <?php echo TextArea('Description-' . $lID, $d['Description-' . $lID], $_Description); ?>
                                                    <?php echo ErrorSpan('Description-' . $lID, $Data['errDescription-' . $lID]); ?>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <?php echo Label('Keywords-' . $lID, $_Keywords, 'class="col-sm-12 control-label"'); ?>
                                                <div class="col-sm-12">
                                                    <?php echo InputBox('Keywords-' . $lID, $d['Keywords-' . $lID], $_Keywords, 'class="tags"'); ?>
                                                    <?php echo ErrorSpan('Keywords-' . $lID, $Data['errKeywords-' . $lID]); ?>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>


                            </div>
                        <?php } ?>
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

            <div class="panel panel-default">
                <div class="panel-heading"><?php echo $_Gallery; ?></div>
                <div class="panel-body">
                    <div class="col-sm-10 col-sm-offset-1">
                        <?php require_once ADM_CURRENT_DIR_PAGES . 'General/UploadAlbum.php'; ?>
                    </div>
                </div>
            </div>

        </div>
        <div class="col-md-12">

            <div class="panel panel-default">
                <div class="panel-heading"><?php echo $_PublishOptions; ?></div>
                <div class="panel-body">
                    <div class="col-md-6">
                        <div class="form-group">
                            <?php echo Label('Code', $_Code, 'class="col-sm-4 control-label"'); ?>
                            <div class="col-sm-8">
                                <?php echo InputBox('Code', $d['Code'], $_Code); ?>
                                <?php echo ErrorSpan('Code', $errCode); ?>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <?php echo Label('SoftCode', $_SoftCode, 'class="col-sm-4 control-label"'); ?>
                            <div class="col-sm-8">
                                <?php echo InputBox('SoftCode', $d['SoftCode'], $_SoftCode); ?>
                                <?php echo ErrorSpan('SoftCode', $errSoftCode); ?>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <?php echo Label('CategoryID', $_Category, 'class="col-sm-4 control-label"'); ?>
                            <div class="col-sm-8">
                                <?php echo DropDown('CategoryID', $dCategoriesList, $d['CategoryID']); ?>
                                <?php echo ErrorSpan('CategoryID', $errCategoryID); ?>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <?php echo Label('BrandID', $_Brand, 'class="col-sm-4 control-label"'); ?>
                            <div class="col-sm-8">
                                <?php echo DropDown('BrandID', $dBrandsList, $d['BrandID']); ?>
                                <?php echo ErrorSpan('BrandID', $errBrandID); ?>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <?php echo Label('OldPrice', $_OldPrice, 'class="col-sm-4 control-label"'); ?>
                            <div class="col-sm-8">
                                <?php echo InputBox('OldPrice', $d['OldPrice'], $_OldPrice, null, 0); ?>
                                <?php echo ErrorSpan('OldPrice', $errOldPrice); ?>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <?php echo Label('Price', $_Price, 'class="col-sm-4 control-label"'); ?>
                            <div class="col-sm-8">
                                <?php echo InputBox('Price', $d['Price'], $_Price, null, 0); ?>
                                <?php echo ErrorSpan('Price', $errPrice); ?>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <?php echo Label('Quantity', $_Quantity, 'class="col-sm-4 control-label"'); ?>
                            <div class="col-sm-8">
                                <?php echo InputBox('Quantity', $d['Quantity'], $_Quantity, null, 0, 'number'); ?>
                                <?php echo ErrorSpan('Quantity', $errQuantity); ?>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <?php echo Label('Featured', $_Featured, 'class="col-sm-4 control-label"'); ?>
                            <div class="col-sm-8">
                                <?php echo CheckBox('Featured', $d['Featured']); ?>
                                <?php echo ErrorSpan('Featured', $errFeatured); ?>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <?php echo Label('State', $_State, 'class="col-sm-4 control-label"'); ?>
                            <div class="col-sm-8">
                                <?php echo CheckBox('State', $d['State']); ?>
                                <?php echo ErrorSpan('State', $errState); ?>
                            </div>
                        </div>
                    </div>
                    <hr class="clearfix" />
                    <div class="form-actions">
                        <button type="submit" class="btn btn-large btn-primary btn-submit"><?php echo $_Save; ?></button>
                        <?php echo Anchor(ADM_BASE . $pID, $_Back, 'class="btn btn-large btn-danger"'); ?>
                    </div>
                </div>
            </div>

        </div>
    </form>
</div>