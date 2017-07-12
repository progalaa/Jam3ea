<?php
if (GetValue($dRow)) {
    $d = &$dRow;
    ?>
    <div id="details">
        <div class="formRow">
            <label><?php echo $_ID; ?></label>
            <?php echo $d['ID']; ?>
        </div>
        <div class="col-md-12">



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
                                <div class="formRow">
                                    <label><?php echo imgFlag($Lng) . $_Title; ?></label>
                                    <?php echo $d['Title-' . $lID]; ?>
                                </div>
                                <div class="formRow">
                                    <label><?php echo imgFlag($Lng) . $_Url; ?></label>
                                    <?php echo $d['Url-' . $lID]; ?>
                                </div>
                                <div class="formRow">
                                    <label><?php echo imgFlag($Lng) . $_Description; ?></label>
                                    <?php echo $d['Description-' . $lID]; ?>
                                </div>

                            </div>
                        <?php } ?>
                    </div>
                </div>
            </div>
        </div>
        <div class="formRow">
            <label><?php echo $_Picture; ?></label>
            <?php echo Img(GetImageOriginal($d['Picture']),'class="img-responsive"'); ?>
        </div>
        <div class="formRow">
            <label><?php echo $_State; ?></label>
            <?php echo CheckBox('State', $d['State'], '', '', false); ?>
        </div>
    </div>
    <?php
}