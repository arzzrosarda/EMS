<?php
global $conn;
session_start();
require "../../../db/conn.php";
?>
<ul class="list-unstyled list-unstyled-border pb-3">
    <?php
    $department = $conn->query("SELECT a.`id`, a.`department`, a.`department_name`, a.`department_logo`, COUNT(b.`division`) AS countDivision, a.`department_no` FROM department a LEFT JOIN division b ON a.`id` = b.`department_id` WHERE a.`department_active` = '0' GROUP BY a.`id`");
    if ($department->rowCount() > 0) {
        while ($deprow = $department->fetch(PDO::FETCH_NUM)) {
            $data[] = $deprow;
        }
        $data = array_reverse($data, true);

        foreach($data as $dept_row){ ?>
            <script>
                $(document).ready(function (){
                    $("#viewDepartment<?php echo $dept_row[0]; ?>").on('click',function (){
                        var dept_id = $(this).attr("data-id");
                        var dept = "<?php echo $dept_row[1]; ?>";
                        var dept_name = "<?php echo $dept_row[2]; ?>";
                        var dept_logo = "<?php echo $dept_row[3]; ?>";
                        var dept_no = "<?php echo $dept_row[5]; ?>";
                        showModal();
                        $("#loader").modal("show");
                        $("#viewDepartment").modal("show");
                        $.post("queries/viewdepartment.php", {dept_id:dept_id, dept_name:dept_name, dept:dept, dept_logo:dept_logo, dept_no:dept_no}, function (Dept){
                            hideModal();
                            $("#loader").modal("hide");
                            $("#viewDepartmentContainerBody").html(Dept);
                        });
                    });
                });
            </script>
            <li class="media ">
                <i class="fas fa-university" style="font-size: 35px; margin-right: 20px"></i>
                <a href="javascript:;" style="text-decoration: none;"  id="viewDepartment<?php echo $dept_row[0]; ?>" data-id='<?php echo $dept_row[0]; ?>'>
                    <div class="media-body">
                        <span class="media-title" style="font-size: 13px;"><?php echo $dept_row[1]." - ".$dept_row[2]; ?></span>
                        <div class="text-small text-muted">
                            No. of Division:
                            <div class="bullet"></div>
                            <span class="text-primary">
                                <?php echo $dept_row[4]; ?>
                            </span>
                        </div>
                    </div>
                </a>
            </li>

        <?php }
    }
    ?>
</ul >


