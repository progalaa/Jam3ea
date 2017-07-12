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
                        <?php echo $_Mobile; ?>
                    </th>
                    <th>
                        <?php echo Anchor(SetSortingOrderLink('CountProducts'), $_CountProducts); ?>
                    </th>
                    <th>
                        <?php echo Anchor(SetSortingOrderLink('SumAmount'), $_SumAmount); ?>
                    </th>
                    <th>
                        <?php echo Anchor(SetSortingOrderLink('OrderDate'), $_OrderDate); ?>
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
                            <?php
                            if ($Item['FullName']) {
                                echo $Item['FullName'];
                            } else {
                                $ud = unserialize($Item['VisitorData']);
                                echo $ud['FullName'];
                            }
                            ?>
                        </td>
                        <td>
                            <?php
                            if ($Item['Mobile']) {
                                echo $Item['Mobile'];
                            } else {
                                $ud = unserialize($Item['VisitorData']);
                                echo $ud['Mobile'];
                            }
                            ?>
                        </td>
                        <td>
                            <?php echo $Item['CountProducts']; ?>
                        </td>
                        <td>
                            <?php echo $Item['TotalPrice']; ?>
                        </td>
                        <td>
                            <?php echo $Item['OrderDate']; ?>
                        </td>
                        <td>
                            <?php
                            echo Anchor(ADM_BASE . $pID . '/Details?dID=' . $Item['ID'], '<i class="glyphicon glyphicon-zoom-in glyphicon-white"></i>', 'data-id="' . $Item['ID'] . '" class="btn btn-success" title="' . $_Details . '"', false);
                            if ($Item['Payment_Method'] == Payments_Methods::Knet) {
                                echo Anchor('javascript:void(0)', '<i class="fa fa-credit-card"></i>', 'data-id="' . $Item['ID'] . '" class="btn btn-info btn-tools" data-toggle="modal" title="' . $_Payments_MethodsList[Payments_Methods::Knet] . '"', false);
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


<script type="text/javascript">
    $(document).ready(function () {
        $('#btnAdd').hide('slow');
        $('#btnEdit').hide('slow');
        $('#btnSelectAll').hide('slow');
<?php
if (!$pUser['IsAdmin']) {
    ?>
            $('#btnDelete').hide('slow');
    <?php
}
?>
        $('#btnSearch').hide('slow');
    });
</script>