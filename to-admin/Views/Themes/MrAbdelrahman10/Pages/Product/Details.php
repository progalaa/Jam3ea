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
                                    <label><?php echo imgFlag($Lng) . $_Name; ?></label>
                                    <?php echo $d['Name-' . $lID]; ?>
                                </div>

                            </div>
                        <?php } ?>
                    </div>
                </div>
            </div>
        </div>
        <div class="formRow">
            <label><?php // echo $_User;  ?></label>
            <?php // echo $d['UserName'] ?>
        </div>
        <div class="formRow">
            <label><?php echo $_Category; ?></label>
            <?php echo $d['CategoryName'] ?>
        </div>
        <div class="formRow">
            <label><?php echo $_Price; ?></label>
            <?php echo $d['Price'] ?>
        </div>
        <div class="formRow">
            <label><?php echo $_Quantity; ?></label>
            <?php echo $d['Quantity'] ?>
        </div>
        <div class="formRow">
            <label><?php echo $_Quantity; ?></label>
            <?php echo $d['Quantity'] ?>
        </div>
        <div class="formRow">
            <label><?php echo $_CreatedDate; ?></label>
            <?php echo $d['CreatedDate'] ?>
        </div>
        <div class="formRow">
            <label><?php echo $_ModifiedDate; ?></label>
            <?php echo $d['ModifiedDate'] ?>
        </div>
        <div class="formRow">
            <label><?php echo $_Viewed; ?></label>
            <?php echo $d['Viewed'] ?>
        </div>
        <div class="formRow">
            <label><?php echo $_Featured; ?></label>
            <?php echo $d['Featured'] ?>
        </div>
        <div class="formRow">
            <label><?php echo $_Slider; ?></label>
            <?php echo $d['Slider'] ?>
        </div>
        <div class="formRow">
            <label><?php echo $_Approved; ?></label>
            <?php echo $d['Approved'] ?>
        </div>
        <div class="formRow">
            <label><?php echo $_State; ?></label>
            <?php echo $d['State'] ?>
        </div>
        <div class="formRow">
            <label><?php echo $_Picture; ?></label>
            <?php echo Img(GetImageOriginal($d['Picture']), 'class="img-responsive"'); ?>
        </div>
        <div class="formRow">
            <label><?php echo $_Gallery; ?></label>
            <div class="col-md-12">
                <?php
                $Pics = unserialize($d['SliderPictures']);
                foreach ($Pics as $p) {
                    echo Img(GetImageThumbnail($p), 'class="col-md-3 img-responsive img-thumbnail"');
                }
                ?>
            </div>
        </div>
    </div>
    <?php
}