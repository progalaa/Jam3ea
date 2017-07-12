<div id="details">
    <p>
        <label><?php echo imgFlag($Lng) . $_UserName; ?></label>
       <?php echo $dUserName; ?>
    </p>
    <p>
        <label><?php echo imgFlag($Lng) . $_Email; ?></label>
       <?php echo $dEmail; ?>
    </p>
    <p>
        <label><?php echo imgFlag($Lng) . $_Title; ?></label>
        <?php echo $dTitle; ?>
    </p>
    <p>
        <label><?php echo imgFlag($Lng) . $_Contents; ?></label>
        <?php echo $dContents; ?>
    </p>
    <p>
        <label><?php echo imgFlag($Lng) . $_CreatedDate; ?></label>
        <?php echo $dCreatedDate; ?>
    </p>
    <p>
        <label for="State"><?php echo $_State; ?></label>
        <?php echo CheckBox('State', $dState, 'data-State="false"') ?>
    </p>
</div>