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
            <label><?php echo $_Title; ?></label>
            <?php echo $d['Title']; ?>
        </div>
        <div class="formRow">
            <label><?php echo $_Details; ?></label>
            <?php echo $d['Details']; ?>
        </div>
        <div class="formRow">
            <label><?php echo $_CreatedDate; ?></label>
            <?php echo $d['CreatedDate']; ?>
        </div>
    </div>
    <?php
}