<div class="collapse in" id="collapseSearch">
    <form method="get" class="form-horizontal" role="form">
        <div class="col-md-6">
            <div class="form-group">
                <?php echo Label('search[t.ID]', $_ID, 'class="col-sm-4 control-label"'); ?>
                <div class="col-sm-8">
                    <?php echo InputBox('search[t.ID]', $_GET['search']['t.ID'], $_ID); ?>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                <?php echo Label('search[tl.Name]', $_Name, 'class="col-sm-4 control-label"'); ?>
                <div class="col-sm-8">
                    <?php echo InputBox('search[tl.Name]', $_GET['search']['tl.Name'], $_Name); ?>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                <?php echo Label('search[t.State]', $_State, 'class="col-sm-4 control-label"'); ?>
                <div class="col-sm-8">
                    <select name="search[t.State]" data-select="<?php echo @$_GET['search[t.State]']; ?>" class="form-control">
                        <option value="1"><?php echo $_Yes; ?></option>
                        <option value="0"><?php echo $_No; ?></option>
                    </select>
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
<?php
if ($dResults) {
    ?>
    <div class="table-responsive">
        <table class="table table-striped table-bordered bootstrap-datatable">
            <thead>
                <tr>
                    <th class="check"></th>
                    <th class="ID"><?php echo Anchor(SetSortingOrderLink('ID'), $_ID); ?></th>
                    <th><?php echo Anchor(SetSortingOrderLink('Name'), $_Name); ?></th>
                    <th><?php echo Anchor(SetSortingOrderLink('CategoryName'), $_Category); ?></th>
                    <th class="bool"><?php echo Anchor(SetSortingOrderLink('CreatedDate'), $_CreatedDate); ?></th>
                    <th class="bool"><?php echo Anchor(SetSortingOrderLink('State'), $_State); ?></th>
                    <th class="tools"></th>
                </tr>
            </thead>
            <tbody>
                <?php
                foreach ($dResults as $Item) {
                    ?>
                    <tr>
                        <td>
                            <input id="C-<?php echo $Item['ID']; ?>" class="DelCheck" type="checkbox" onclick="MultiCheck('<?php echo $Item['ID']; ?>')" />
                        </td>
                        <td><?php echo $Item['ID']; ?></td>
                        <td><?php echo $Item['Name']; ?></td>
                        <td><?php echo $Item['CategoryName']; ?></td>
                        <td><?php echo $Item['CreatedDate']; ?></td>
                        <td>
                            <?php
                            echo
                            CheckBox('State-' . $Item['ID'], $Item['State'], 'itemState', 'data-id="' . $Item['ID'] . '"');
                            ?>
                        </td>
                        <td>
                            <?php
                            echo Anchor('#DetailOperation', '<i class="fa fa-info"></i>', 'data-id="' . $Item['ID'] . '" class="Details btn btn-success" data-toggle="modal" title="' . $_Details . '"', false);
                            echo Anchor(ADM_BASE . $pID . '/Add?id=' . $Item['ID'], '<i class="fa fa-copy"></i>', 'class="btn btn-info btn-tools" title="' . $_AddCopy . '"');
                            echo Anchor(ADM_BASE . $pID . '/Edit/' . $Item['ID'], '<i class="fa fa-edit"></i>', 'class="btn btn-primary btn-tools" title="' . $_Edit . '"');
                            echo Anchor('#DeleteOperation', '<i class="fa fa-trash"></i>', 'data-id="' . $Item['ID'] . '" class="Delete btn btn-danger btn-tools" data-toggle="modal" title="' . $_Delete . '"', false);
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
    <?php
}