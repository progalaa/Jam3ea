<div class="row">
    <div class="col-xs-12 col-sm-12 col-md-12">
        <h2 class="title text-center"><?php echo $d['dTitle']; ?></h2>
        <div class="col-md-8 col-md-offset-2">
            <?php
            if ($d['dResults']) {
                ?>
                <table class="table table-bordered table-responsive table-striped table-hover">
                    <tr>
                        <th><?php echo $_['_ID']; ?></th>
                        <th><?php echo $_['_TotalPrice']; ?></th>
                        <th><?php echo $_['_ProductsCount']; ?></th>
                        <th><?php echo $_['_OrderDate']; ?></th>
                        <th><?php echo $_['_State']; ?></th>
                    </tr>
                    <?php
                    foreach ($d['dResults'] as $i) {
                        ?>
                        <tr>
                            <td><?php echo $i['ID']; ?></td>
                            <td><?php echo $i['TotalPrice']; ?></td>
                            <td><?php echo $i['ProductsCount']; ?></td>
                            <td><?php echo $i['OrderDate']; ?></td>
                            <td><?php echo $i['State']; ?></td>
                        </tr>
                        <?php
                    }
                    ?>
                </table>
                <?php
            } else {
                ?>

                <?php
            }
            ?>
        </div>
    </div>
</div>