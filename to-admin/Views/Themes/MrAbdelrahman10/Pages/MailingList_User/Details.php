<?php
if (GetValue($dRow)) {
    $d = &$dRow;
    ?>
    <div id="details">
        <input type="hidden" name="ID" id="ID" value="<?php echo (isset($dID)) ? $dID : 0; ?>" />
        <div class="formRow">
            <label><?php echo $_ID; ?></label>
            <?php echo $d['ID']; ?>
        </div>
        <div class="formRow">
            <label><?php echo $_Email; ?></label>
            <?php echo $d['Email']; ?>
        </div>
        <div class="formRow">
            <label for="State"><?php echo $_State; ?></label>
            <?php echo CheckBox('State', $_State, 'data-state="false"') ?>
        </div>
    </div>
    <?php
}
?>