<div class="row">
    <div class="col-xs-12 col-sm-12 col-md-12">
        <div class="wishlist">
            <h1 class="header-title"> <?php echo $d['dTitle']; ?> </h1><br>
            <?php
            if ($d['dResults']) {
                ?>
                <table class="table table-striped table-bordered">
                    <tbody><tr>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
                        </tr>
                        <?php
                        foreach ($d['dResults'] as $i) {
                            ?>
                            <tr>
                                <td><?php echo $i['ID']; ?></td>
                                <td>
                                    <img src="<?php echo GetImageThumbnail($i['Picture'], 150, 150); ?>" alt="<?php echo $i['Name']; ?>" class="img-favorite">
                                </td>
                                <td>
                                    <?php echo Anchor(GetRewriteUrl('product/i/' . $i['ID']), $i['Name'], 'title="' . $i['Name'] . '"'); ?>
                                </td>
                                <td>
                                    <?php echo Anchor(GetRewriteUrl('product/c/' . $i['CategoryID']), $i['CategoryName']); ?>
                                </td>
                                <td>
                                    <a class="btn deletewishlist" data-id="<?php echo $i['ID']; ?>">
                                        <i class="fa fa-times"></i>
                                    </a>
                                </td>
                            </tr>
                            <?php
                        }
                        ?>
                    </tbody>
                </table>
                <?php
            }
            ?>
        </div>
    </div>
</div>