<section>
    <div class="container">
        <div class="row">
            <?php
            $u = $d['pUser'];
            ?>
            <div class="col-md-8 col-md-offset-2">
                <div class="pinfo">


                    <table class="table table-striped table-hover">

                        <tr>
                            <td><i class="fa fa-user"></i> &nbsp; <?php echo $_['_FullName']; ?></td>
                            <td> <?php echo $u['FullName']; ?> </td>
                        </tr>
                        <tr>
                            <td><i class="fa fa-user-secret"></i> &nbsp; <?php echo $_['_UserName']; ?> </td>
                            <td> <?php echo $u['UserName']; ?> </td>
                        </tr>
                        <tr>
                            <td><i class="fa fa-envelope"></i> &nbsp; <?php echo $_['_Email']; ?></td>
                            <td> <?php echo $u['Email']; ?> </td>
                        </tr>

                        <tr>
                            <td><i class="fa fa-mobile"></i> &nbsp; <?php echo $_['_Mobile']; ?></td>
                            <td><?php echo $u['Mobile']; ?></td>
                        </tr>
                    </table>

                </div>

                <div class="row">
            <div class="col-md-10 col-md-offset-1">


                <div class="col-md-4">
                    <a href="<?php echo GetRewriteUrl('profile/changeuserdata'); ?>" class="btn btn-primary btn-block">
                        <i class="fa fa-lock"></i>
                        <?php echo $_['_ChangeData']; ?>
                    </a>
                    <hr class="clearfix" />
                </div>

                <div class="col-md-4">
                    <a href="<?php echo GetRewriteUrl('profile/changepassword'); ?>" class="btn btn-primary btn-block">
                        <i class="fa fa-lock"></i>
                        <?php echo $_['_ChangePassword']; ?>
                    </a>
                    <hr class="clearfix" />
                </div>


                <div class="col-md-4">
                    <a href="<?php echo GetRewriteUrl('cart'); ?>" class="btn btn-primary btn-block">
                        <i class="fa fa-heart"></i>
                        <?php echo $_['_Cart']; ?>
                    </a>
                    <hr class="clearfix" />
                </div>

                <div class="col-md-4">
                    <a href="<?php echo GetRewriteUrl('cart/history'); ?>" class="btn btn-primary btn-block">
                        <i class="fa fa-list"></i>
                        <?php echo $_['_Cart_History']; ?>
                    </a>
                    <hr class="clearfix" />
                </div>

<!--                <div class="col-md-4">
                    <a href="<?php echo GetRewriteUrl('wishlist'); ?>" class="btn btn-primary btn-block">
                        <i class="fa fa-list-ol"></i>
                        <?php echo $_['_Wishlist']; ?>
                    </a>
                </div>-->



            </div>
        </div>

            </div>

        </div>
    </div>
</section>
