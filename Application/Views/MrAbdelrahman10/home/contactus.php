<div class="row">
    <div class="col-md-8 col-md-offset-2">
        <?php
        if (GetValue($d['dMsg'])) {
            ?>
        <div class="alert alert-success"><?php echo $d['dMsg']; ?></div>
        <?php
        }else{
        ?>
        <div class="well well-sm">
            <div class="alert alert-info">
                    <?php echo $_['_Contact_Description']; ?>
            </div>
            <form class="form-horizontal" action="" method="post">
                <fieldset>
                    <legend class="text-center"><?php echo $d['dTitle']; ?></legend>

                    <!-- Name input-->
                    <div class="form-group">
                        <label class="col-md-3 control-label" for="mName"><?php echo $_['_URName']; ?></label>
                        <div class="col-md-9">
                            <input name="name" type="text" placeholder="<?php echo $_['_URName']; ?>" class="form-control" required>
                        </div>
                    </div>

                    <!-- Email input-->
                    <div class="form-group">
                        <label class="col-md-3 control-label" for="mEmail"><?php echo $_['_Email']; ?></label>
                        <div class="col-md-9">
                            <input name="email" type="email" placeholder="<?php echo $_['_Email']; ?>" class="form-control" required>
                        </div>
                    </div>

                    <!-- Mobile input-->
                    <div class="form-group">
                        <label class="col-md-3 control-label" for="Mobile"><?php echo $_['_Mobile']; ?></label>
                        <div class="col-md-9">
                            <input name="mobile" type="tel" placeholder="<?php echo $_['_Mobile']; ?>" class="form-control" required>
                        </div>
                    </div>

                    <!-- Message body -->
                    <div class="form-group">
                        <label class="col-md-3 control-label" for="mMessage"><?php echo $_['_URMsg']; ?></label>
                        <div class="col-md-9">
                            <textarea class="form-control" name="message" placeholder="<?php echo $_['_URMsg']; ?>" rows="5" required></textarea>
                        </div>
                    </div>

                    <!-- Form actions -->
                    <div class="form-group">
                        <div class="col-md-12 text-right">
                            <button type="submit" class="btn btn-primary btn-lg"><?php echo $_['_Send']; ?></button>
                        </div>
                    </div>
                </fieldset>
            </form>
        </div>
            <?php
        }
            ?>
    </div>
</div>