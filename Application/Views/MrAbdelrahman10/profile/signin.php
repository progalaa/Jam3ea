<?php if (!defined('BASE_DIR')) exit(header('Location: /')); ?>
<div class="row">
    <div class="col-md-6 col-md-offset-3">
        <div class="well">
            <h1> <?php echo $d['dTitle']; ?> </h1>
            <form id="form-login" method="post" action="<?php echo GetRewriteUrl('profile/signin'); ?>">
                <?php
                if (isset($d['dError'])) {
                    ?>
                    <div class="alert alert-danger">
                        <big> <i class="fa fa-exclamation-circle"></i> </big>
                        <?php echo $d['dError']; ?>
                    </div>
                    <?php
                }
                ?>
                <div class="form-group">
                    <label for="UserName"><?php echo $_['_UserName']; ?> <em>*</em></label>
                    <input type="text" class="form-control" placeholder="<?php echo $_['_UserName']; ?>" name="UserName" required autofocus>
                </div>
                <div class="form-group">
                    <label for="Password"><?php echo $_['_Password']; ?> *</label>
                    <input type="password" name="Password" class="form-control" required placeholder="<?php echo $_['_Password']; ?>">
                </div>
                <div class="form-group remember-forgot">
                    <a href="<?php echo GetRewriteUrl('profile/forgotpassword'); ?>" class="forgot">
                        <?php echo $_['_Forgot_Password']; ?>?
                    </a>
                </div>
                <div class="form-group">
                    <button class="btn btn-log" id="login" name="login" title="" type="submit">
                        <span> <?php echo $_['_SignIn']; ?>  </span>
                    </button>
                </div>
                <div class="form-group">
                    <div class="col-md-6 col-md-offset-3">
                        <a href="<?php echo GetRewriteUrl('profile/register'); ?>" class="btn btn-primary btn-block">
                            <?php echo $_['_Register']; ?>
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>