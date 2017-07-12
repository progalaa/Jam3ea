<?php

function viewRow($i, $pUrl, $canDelete = true) {
    ?>
    <div class="row">
        <div class="col-xs-2">
            <img class="img-responsive" src="<?php echo GetImageThumbnail($i['Picture'], 100, 70); ?>" alt="<?php echo $i['Name']; ?>" />
        </div>
        <div class="col-xs-4">
            <h4 class="product-name">
                <strong>
                    <a href="<?php echo BASE_SITE_URL . "ar/product/i/{$i['ID']}"; ?>" target="_blank">
                        <?php echo $i['Name']; ?>
                    </a>
                </strong>
            </h4>
            <h4>
                <small>
                    <?php echo $i['CategoryName']; ?>
                </small>
            </h4>
        </div>
        <div class="col-xs-6">
            <div class="col-xs-3 text-right">
                <h6>
                    <strong>
                        <?php echo $i['cPrice']; ?>
                        د.ك
                        <span class="text-muted">x</span>
                    </strong>
                </h6>
            </div>
            <div class="col-xs-3">
                <?php echo $i['cQuantity']; ?>
            </div>
            <div class="col-xs-2">
                <?php echo ($i['cPrice'] * $i['cQuantity']); ?>
                د.ك
            </div>
            <?php
            if ($canDelete) {
                ?>
                <div class="col-xs-2">
                    <?php echo Anchor($pUrl . '?' . RemoveFromUrlGet('ProdID') . "&ProdID={$i['cpID']}", '<i class="fa fa-trash"></i>', 'title="' . GetLang('_Cancel') . '" data-id="' . $i['cpID'] . '"'); ?>
                </div>
                <?php
            }else{
                ?>
                <div class="col-xs-2">
                    <?php echo Anchor($pUrl . '?' . RemoveFromUrlGet('ProdID') . "&ProdID={$i['cpID']}&NoReturned", '<i class="fa fa-repeat"></i>', 'title="' . GetLang('_Reset') . '" data-id="' . $i['cpID'] . '"'); ?>
                </div>
                <?php
            }
            ?>
        </div>
    </div>
    <hr>
    <?php
}

if (GetValue($dRow)) {
    $d = &$dRow;
    $vu = unserialize($d['VisitorData']);
    ?>
    <?php
    if (isset($dMsg)) {
        ?>
        <div class="alert alert-success">
            <?php echo $dMsg; ?>
        </div>
        <?php
    }
    ?>
    <div id="details">
        <input type="hidden" name="ID" id="ID" value="<?php echo (isset($dID)) ? $dID : 0; ?>" />
        <div class="formRow">
            <label><?php echo $_ID; ?></label>
            <?php echo $d['ID']; ?>
        </div>
        <div class="formRow">
            <label><?php echo $_FullName; ?></label>
            <?php echo $d['FullName']? : $vu['FullName']; ?>
        </div>
        <div class="formRow">
            <label><?php echo $_UserName; ?></label>
            <?php echo $d['UserName']; ?>
        </div>
        <div class="formRow">
            <label><?php echo $_Email; ?></label>
            <?php echo $d['Email']; ?>
        </div>
        <div class="formRow">
            <label><?php echo $_Mobile; ?></label>
            <?php echo $d['Mobile']? : $vu['Mobile']; ?>
        </div>
        <div class="formRow">
            <h3 class="text-center"><?php echo $_Address; ?></h3>
        </div>
        <div class="formRow">
            <div class="panel panel-info">
                <div class="panel-heading">
                    <div class="panel-title">
                        <div class="row">
                            <div class="col-xs-6">
                                <h5>
                                    <span class="glyphicon glyphicon-road"></span>
                                    <?php echo $_Address; ?>
                                </h5>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="panel-body">
                    <div class="formRow">
                        <label><?php echo $_Gada; ?></label>
                        <?php echo $d['Gada']? : $vu['Gada']; ?>
                    </div>
                    <div class="formRow">
                        <label><?php echo $_House; ?></label>
                        <?php echo $d['House']? : $vu['House']; ?>
                    </div>
                    <div class="formRow">
                        <label><?php echo $_Widget; ?></label>
                        <?php echo $d['Widget']? : $vu['Widget']; ?>
                    </div>
                    <div class="formRow">
                        <label><?php echo $_Street; ?></label>
                        <?php echo $d['Street']? : $vu['Street']; ?>
                    </div>
                    <div class="formRow">
                        <label><?php echo $_Zone; ?></label>
                        <?php echo $d['Zone']? : $vu['Zone']; ?>
                    </div>
                </div>
            </div>
        </div>
        <div class="formRow">
            <form method="POST">
                <div class="col-md-8">
                    <div class="form-group">
                        <?php echo Label('State', $_State, 'class="col-sm-6 control-label"'); ?>
                        <div class="col-sm-6">
                            <?php echo DropDown('State', $dStatesList, $d['State']); ?>
                            <?php echo ErrorSpan('State', $errState); ?>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-actions">
                        <button type="submit" class="btn btn-large btn-primary btn-submit"><?php echo $_Save; ?></button>
                        <?php echo Anchor(ADM_BASE . $pID, $_Back, 'class="btn btn-large btn-danger"'); ?>
                    </div>
                </div>
            </form>
        </div>
        <div class="formRow">
            <h3 class="text-center"><?php echo $_Products; ?></h3>
        </div>
        <div class="formRow">
            <div class="panel panel-info">
                <div class="panel-heading">
                    <div class="panel-title">
                        <div class="row">
                            <div class="col-xs-6">
                                <h5>
                                    <span class="glyphicon glyphicon-shopping-cart"></span>
                                    <?php echo $_Products; ?>
                                </h5>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="panel-body">
                    <?php
                    $Sum = 0;
                    $Returned = 0;
                    foreach ($d['Products'] as $i) {
                        if ($i['Returned'] == '0') {
                            $Sum += ($i['cPrice'] * $i['cQuantity']);
                            viewRow($i, $pUrl);
                        } else {
                            $Returned += ($i['cPrice'] * $i['cQuantity']);
                        }
                    }
                    ?>
                </div>
                <div class="panel-footer">
                    <div class="row text-center">
                        <div class="col-xs-9">
                            <h4 class="text-right">
                                <?php echo $_Total; ?>
                                <strong>
                                    <?php echo $d['TotalPrice']; ?>
                                    د.ك
                                </strong>
                            </h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php
        if ($Returned) {
            ?>
            <div class="formRow">
                <div class="panel panel-info">
                    <div class="panel-heading">
                        <div class="panel-title">
                            <div class="row">
                                <div class="col-xs-6">
                                    <h5>
                                        <span class="glyphicon glyphicon-shopping-cart"></span>
                                        <?php echo $_Returned; ?>
                                    </h5>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="panel-body">
                        <?php
                        $Sum = 0;
                        foreach ($d['Products'] as $i) {
                            if ($i['Returned'] == '1') {
                                $Sum += ($i['cPrice'] * $i['cQuantity']);
                                viewRow($i, $pUrl, false);
                            }
                        }
                        ?>
                    </div>
                    <div class="panel-footer">
                        <div class="row text-center">
                            <div class="col-xs-9">
                                <h4 class="text-right">
                                    <?php echo $_Total; ?>
                                    <strong>
                                        <?php echo $Sum; ?>
                                        د.ك
                                    </strong>
                                </h4>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <?php
        }
        ?>
        <div class="formRow">
            <div class="col-md-4 col-md-offset-4">
                <h2 class="text-center">
                    <?php echo $_NetPaid . ' : ' . ($d['TotalPrice'] - $Sum); ?>
                </h2>
            </div>
        </div>
        <div class="formRow">
            <div class="col-md-4 col-md-offset-4">
                <a href="<?php echo ADM_BASE . $pID . '/Pill?dID=' . $_GET['dID']; ?>" class="btn btn-success btn-block">
                    <?php echo $_View_Pill; ?>
                </a>
            </div>
        </div>
    </div>
    <?php
}
?>

<script type="text/javascript">
    $(document).ready(function () {
        $('#Buttons *').hide('slow');
    });
</script>