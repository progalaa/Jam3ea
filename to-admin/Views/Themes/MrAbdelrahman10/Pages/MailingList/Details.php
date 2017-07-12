<?php
if (GetValue($dRow)) {
    $d = &$dRow;
    ?>
    <div id="details">
        <div class="formRow">
            <label><?php echo $_ID; ?></label>
            <?php echo $d['ID']; ?>
        </div>
        <div class="formRow">
            <label><?php echo $_Subject; ?></label>
            <?php echo $d['Subject']; ?>
        </div>
        <div class="formRow">
            <label><?php echo $_Message; ?></label>
            <?php echo $d['Message']; ?>
        </div>
    </div>
    <?php
}
?>