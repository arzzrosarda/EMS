<?php
global $conn;
session_start();
require "../../../db/conn.php"; ?>
<ul class="list-group">
    <?php
    if (isset($_POST['dept_id'])){
        $index = 1;
        $dept_id = $_POST['dept_id']; ?>
        <input type="hidden" name="dept_id" value="<?php echo $dept_id; ?>">
        <?php $division_query = $conn->query("SELECT division, id FROM division WHERE department_id = '$dept_id'");
        if ($division_query->rowCount() > 0){
            while ($div = $division_query->fetch()){
                $division_name = $div['division'];
                $division_id = $div['id'];
                ?>
                    <script>
                        $(document).ready(function (){
                           $("#btnNoteDiv").show();
                        });
                    </script>
                <label>Divison <?php echo $index++; ?>: </label>
                <input type="text" class="form-control list-group-item pt-2" name="div_input<?php echo $division_id; ?>" value="<?php echo $division_name; ?>">
                <?php
            }
        }else { ?>
            <script>
                $(document).ready(function (){
                    $("#btnNoteDiv").hide();
                });
            </script>
            <label>Divison: </label>
            <li class="list-group-item">No Division Yet</li>
        <?php }
    }
    ?>
</ul>




