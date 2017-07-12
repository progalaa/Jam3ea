<div class="collapse in" id="collapseSearch">
    <form method="get" class="form-horizontal" role="form">
        <div class="col-md-6">
            <div class="form-group">
                <?php echo Label('search[ID]', $_ID, 'class="col-sm-4 control-label"'); ?>
                <div class="col-sm-8">
                    <?php echo InputBox('search[ID]', $_GET['search']['ID'], $_ID); ?>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                <?php echo Label('search[UserName]', $_UserName, 'class="col-sm-4 control-label"'); ?>
                <div class="col-sm-8">
                    <?php echo InputBox('search[UserName]', $_GET['search']['UserName'], $_UserName); ?>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                <?php echo Label('search[FullName]', $_FullName, 'class="col-sm-4 control-label"'); ?>
                <div class="col-sm-8">
                    <?php echo InputBox('search[FullName]', $_GET['search']['FullName'], $_FullName); ?>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                <?php echo Label('search[Email]', $_Email, 'class="col-sm-4 control-label"'); ?>
                <div class="col-sm-8">
                    <?php echo InputBox('search[Email]', $_GET['search']['Email'], $_Email); ?>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="col-md-6 no-padding">
                <button type="submit" class="btn btn-success btn-block">
                    <i class="fa fa-search"></i>
                    <?php echo $_Search; ?>
                </button>
            </div>
            <div class="col-md-6 no-padding">
                <a class="btn btn-danger btn-block" href="<?php echo $pUrl; ?>">
                    <i class="fa fa-repeat"></i>
                    <?php echo $_Reset; ?>
                </a>
            </div>
        </div>
    </form>
</div>
<?php if ($dResults) { ?>
    <div class="table-responsive">
        <table class="table table-striped table-bordered bootstrap-datatable ">
            <thead>
                <tr>
                    <th class="check">
                    </th>
                    <th class="ID">
                        <?php echo Anchor(SetSortingOrderLink('ID'), $_ID); ?>
                    </th>
                    <th>
                        <?php echo Anchor(SetSortingOrderLink('FullName'), $_FullName); ?>
                    </th>
                    <th>
                        <?php echo Anchor(SetSortingOrderLink('Email'), $_Email); ?>
                    </th>
                    <th>
                        <?php echo Anchor(SetSortingOrderLink('CreatedDate'), $_CreatedDate); ?>
                    </th>
                    <th class="state">
                        <?php echo Anchor(SetSortingOrderLink('State'), $_State); ?>
                    </th>
                    <th>
                    </th>
                </tr>
            </thead>
            <tbody>
                <?php
                foreach ($dResults as $Item) {
                    $HasPerm = true;
                    ?>
                    <tr>
                        <td>
                            <input id="C-<?php echo $Item['ID']; ?>" class="DelCheck" type="checkbox" onclick="MultiCheck('<?php echo $Item['ID']; ?>')" />
                        </td>
                        <td>
        <?php echo $Item['ID']; ?>
                        </td>
                        <td>
        <?php echo $Item['FullName']; ?>
                        </td>
                        <td>
        <?php echo $Item['Email']; ?>
                        </td>
                        <td>
        <?php echo $Item['CreatedDate']; ?>
                        </td>
                        <td>
        <?php echo CheckBox('State-' . $Item['ID'], $Item['State'], 'itemState', 'data-id="' . $Item['ID'] . '"') ?>
                        </td>
                        <td>
                            <?php
                            echo Anchor('#DetailOperation', '<i class="glyphicon glyphicon-zoom-in glyphicon-white"></i>', 'data-id="' . $Item['ID'] . '" class="Details btn btn-success" data-toggle="modal" title="' . $_Details . '"', false);
                            echo Anchor(ADM_BASE . $pID . '/Add?id=' . $Item['ID'], '<i class="fa fa-copy"></i>', 'class="btn btn-info btn-tools" title="' . $_AddCopy . '"');
                            if ($HasPerm) {
                                echo Anchor(ADM_BASE . $pID . '/Edit/' . $Item['ID'], '<i class="glyphicon glyphicon-edit glyphicon-white"></i>', 'class="btn btn-primary btn-tools" title="' . $_Edit . '"');
                                echo Anchor('#DeleteOperation', '<i class="fa fa-trash"></i>', 'data-id="' . $Item['ID'] . '" class="Delete btn btn-danger btn-tools" data-toggle="modal" title="' . $_Delete . '"', false);
                            }
                            ?>
                        </td>
                    </tr>
    <?php } ?>
            </tbody>
        </table>
    </div>
    <div class="dataTables_paginate">
    <?php echo $dRenderNav; ?>
    </div>
<?php } ?>