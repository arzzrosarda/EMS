<?php
global $conn;
session_start();
require "../../db/conn.php";
$email = $_SESSION['user'];
$department = $_SESSION['department'];
$query = $conn->query("SELECT * FROM user WHERE email = '$email' OR username = '$email'");
$row = $query->fetch();
$idU = $row['id'];
?>
<script>
    $(document).ready(function (){
        // list of recently deleted exams
        $("#Tlog").dataTable({
            order: [[ 0, 'desc']],
            "bAutoWidth": false
        });

        $("[data-boxes]").each(function() {
            var me = $(this),
                group = me.data("boxes"),
                role = me.data("box-role");

            me.change(function() {
                var all = $("[data-boxes=" + group + "]:not([data-box-role=dad])"),
                    checked = $("[data-boxes=" + group + "]:not([data-box-role=dad]):checked"),
                    dad = $("[data-boxes=" + group + "][data-box-role=dad]"),
                    total = all.length,
                    checked_length = checked.length;

                if(role == "dad") {
                    if(me.is(":checked")) {
                        $('.btnRestoreAllExam').prop("disabled", false);
                        me.prop("checked", true);
                    }else{

                        $('.btnRestoreAllExam').prop("disabled", true);
                        me.prop("checked", false);
                    }
                    if (dad.is(":checked")){
                        $('.btnRestoreAllExam').prop("disabled", false);
                        all.prop("checked", true);
                    }else {

                        $('.btnRestoreAllExam').prop("disabled", true);
                        all.prop("checked", false);
                    }
                }else{
                    if(checked_length >= total) {
                        $('.btnRestoreAllExam').prop("disabled", false);
                        dad.prop("checked", true);
                    }else{
                        $('.btnRestoreAllExam').prop("disabled", false);
                        dad.prop("checked", false);
                    }
                }
                if (all.is(":checked")){
                    $('.btnRestoreAllExam').prop("disabled", false);
                }else {

                    $('.btnRestoreAllExam').prop("disabled", true);
                }
            });
        });

        //Restore all Exam from recent delete
        $('.btnRestoreAllExam').on('click', function (){
            var exam = [];
            var exam_checkbox = $("[data-boxes=delgroup]:not([data-box-role=dad]):checked");
            for (var i = 0; i < exam_checkbox.length; i++) {
                exam.push(exam_checkbox[i].value)
            }
            var length = exam_checkbox.length;
            var uid = "<?php echo $idU; ?>";
            Swal.fire({
                title: "RESTORE",
                html: "Are you sure you want to restore selected exam?",
                icon: "info",
                showCancelButton: true,
                confirmButtonText: "Yes, I am sure",
                cancelButtonText: "No, Cancel it",
                closeOnConfirm: false,
                closeOnCancel: true
            }).then((result) => {
                if (result.isConfirmed) {
                    $.post("queries/restore_exam.php", {all_exam:exam, user_id:uid, length:length}, function (del) {
                        if (del == 1) {
                            $.post("modal/loader.php", function (load) {
                                $("#exam_list_deleted").html(load);
                                $.post("fragments/list_recently_deleted_exam.php", function (list) {
                                    $("#exam_list_deleted").html(list);
                                });
                            });
                            $('.btnRestoreAllExam').prop("disabled", true);
                        } else {
                            Swal.fire("DELETE", "Something went wrong, delete unsuccessful", "error");
                        }
                    });
                }
            });
        });
    });
</script>
<div class="table-responsive">
<table class="table table-sm" id="Tlog">
    <thead>
    <tr>
        <th style="display: none;">ID</th>
        <th class="text-center">
            <div class="custom-checkbox custom-control">
                <input type="checkbox" data-boxes="delgroup" data-box-role="dad" class="custom-control-input" id="checkbox-deleted">
                <label for="checkbox-deleted" class="custom-control-label">&nbsp;</label>
            </div>
        </th>
        <th>Title</th>
        <th>Division</th>
        <th class="text-center">Number of Test</th>
        <th class="text-center">Time Limit</th>
        <th class="text-center">Date Deleted</th>
        <th class="text-center">Time Deleted</th>
    </tr>
    </thead>
    <tbody>
    <?php
    $index = 1;
    if (isset($_REQUEST['division']) && isset($_REQUEST['status'])){
        $division = $_REQUEST['division'];
        $status = $_REQUEST['status'];
        $examQuery2 = $conn->query("SELECT * FROM exam_title WHERE department = '$department' AND division = '$division' AND isActiveExam = '$status' AND isDeletedExam = '1'");
    }
    else if (isset($_REQUEST['division'])){
        $division = $_REQUEST['division'];
        $examQuery2 = $conn->query("SELECT * FROM exam_title WHERE department = '$department' AND division = '$division' AND isDeletedExam = '1'");
    }
    else if (isset($_REQUEST['status'])){
        $status = $_REQUEST['status'];
        $examQuery2 = $conn->query("SELECT * FROM exam_title WHERE isActiveExam = '$status' AND isDeletedExam = '1'");
    }
    else {
        $examQuery2 = $conn->query("SELECT * FROM exam_title WHERE isDeletedExam = '1' AND department = '$department'");
    }
    if ($examQuery2->rowCount() > 0){
        while ($exam = $examQuery2->fetch()) {
            $exam_id = $exam['id'];
            $exam_title = $exam['title'];
            $exam_div = $exam['division'];
            $exam_del_date = $exam['logDate'];
            $exam_del_time= $exam['logTime'];
            $exam_test = $exam['num_test'];
            $time_limit = $exam['time_limit'];
            ?>
    <tr style="line-height: 30px;">
        <th style="display: none;"><?php echo $exam_id; ?></th>
        <td class="text-center">
            <div class="custom-checkbox custom-control">
                <input type="checkbox" data-boxes="delgroup" class="custom-control-input" id="box<?php echo $exam_id; ?>" value="<?php echo $exam_id; ?>">
                <label for="box<?php echo $exam_id; ?>" class="custom-control-label">&nbsp;</label>
            </div>
        </td>
        <td><?php echo $exam_title; ?></td>
        <td><?php echo $exam_div ; ?></td>
        <td class="text-center">
            <?php
            if ($exam_test == '1'){
                echo 'Test I only';
            }else if ($exam_test  == '2'){
                echo 'Test I - Test II';
            }else if ($exam_test  == '3'){
                echo 'Test I - Test III';
            }else if ($exam_test  == '4'){
                echo 'Test I - Test IV';
            }else if ($exam_test  == '5'){
                echo 'Test I - Test V';
            }
            ?>
        </td>
        <td class="text-center"><?php echo $time_limit; ?></td>
        <td class="text-center"><?php echo $exam_del_date; ?></td>
        <td class="text-center"><?php echo $exam_del_time; ?></td>
    </tr>
    <?php  }
    }?>
    </tbody>
</table>
</div>
