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
                        <?php echo Anchor(SetSortingOrderLink('Subject'), $_Subject); ?>
                    </th>
                    <th>
                        <?php echo Anchor(SetSortingOrderLink('CreatedDate'), $_CreatedDate); ?>
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
                            <?php echo $Item['Subject']; ?>
                        </td>
                        <td>
                            <?php echo $Item['CreatedDate']; ?>
                        </td>
                        <td>
                            <?php
                            echo Anchor('#DetailOperation', '<i class="glyphicon glyphicon-zoom-in glyphicon-white"></i>', 'data-id="' . $Item['ID'] . '" class="Details btn btn-success" data-toggle="modal" title="' . $_Details . '"', false);
                            echo Anchor(ADM_BASE . $pID . '/Add?id=' . $Item['ID'], '<i class="fa fa-copy"></i>', 'class="btn btn-info btn-tools" title="' . $_AddCopy . '"');
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
<?php } ?>


<script type="text/javascript">
    $(document).ready(function () {
        $('#btnEdit').hide('slow');
    });
</script>