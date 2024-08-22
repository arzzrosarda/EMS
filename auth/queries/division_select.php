<?php
global $conn;
require "../../db/conn.php";?>
<script>
    $(document).ready(function (){
        $("[data-checkboxes]").each(function() {
            var me = $(this),
                group = me.data("checkboxes");

            me.change(function() {
                var all = $("[data-checkboxes=" + group + "]:not([data-box-role=dad])"),
                    checked = $("[data-checkboxes=" + group + "]:not([data-box-role=dad]):checked"),
                    dad = $("[data-checkboxes=" + group + "][data-box-role=dad]"),
                    checked_length = checked.length;

                if(all.is(":checked")) {
                    dad.prop("checked", true);
                }else if (checked_length > 0 ){
                    dad.prop("checked", true);
                }else {
                    dad.prop("checked", false);
                }

            });
        });
    });
</script>
    <div class="custom-checkbox custom-control" style="margin-top: -20px; opacity: 0;">
        <input type="checkbox" data-checkboxes="myDiv" data-box-role="dad" class="custom-control-input" id="div_checkbox" required>
        <label for="div_checkbox" class="custom-control-label">&nbsp;</label>
    </div>
<div class="row">
<?php
if (isset($_REQUEST['department'])){
    $department = $_REQUEST['department'];
    $division_query = $conn->query("SELECT a.`id`, a.`department`, b.`department_id`, b.`division`, b.`id` AS div_id FROM department a LEFT JOIN division b ON a.`id` = b.`department_id` WHERE a.`department` = '$department'");
    while($div = $division_query->fetch()){?>
        <div class="col-4">
            <div class="custom-checkbox custom-control">
                <input type="checkbox" data-checkboxes="myDiv" name="division_<?php echo $div['div_id']; ?>" class="custom-control-input" id="division_<?php echo $div['div_id']; ?>" value="<?php echo $div['division']; ?>">
                <label for="division_<?php echo $div['div_id']; ?>" class="custom-control-label">&nbsp; <?php echo $div['division']; ?></label>
            </div>
        </div>
    <?php }
}?>
</div>
