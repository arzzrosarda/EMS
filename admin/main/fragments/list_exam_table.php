<?php
global $conn;
session_start();
require "../../../db/conn.php";
$email = $_SESSION['user'];
$query = $conn->query("SELECT * FROM user WHERE email = '$email' OR username = '$email'");
$row = $query->fetch();
$idU = $row['id'];
?>
<script>
    $(document).ready( function (){
        // list of exams
        $("#ulog").dataTable({
            order: [[ 0, 'desc']],
            "bAutoWidth": false
        });

        $(".btnDelAllExam").click( function (){
            var exam = [];
            var exam_checkbox = $("[data-checkboxes=mygroup]:not([data-checkbox-role=dad]):checked");
            for (var i = 0; i < exam_checkbox.length; i++) {
                exam.push(exam_checkbox[i].value)
            }
            var length = exam_checkbox.length;
            var uid = "<?php echo $idU; ?>";
            Swal.fire({
                title: "DELETE",
                html: "Are you sure you want to delete this exam?",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#1c3d77",
                confirmButtonText: "Yes, I am sure",
                cancelButtonText: "No, Cancel it",
                closeOnConfirm: false,
                closeOnCancel: true
            }).then((result) => {
                if (result.isConfirmed) {
                    $.post("../queries/del_all_exam.php", {all_exam: exam, user_id: uid, length: length}, function (del) {
                        if (del == 1) {
                            $.post("modal/loader.php", function (load) {
                                $("#exam_list").html(load);
                                $.post("fragments/list_exam_table.php", function (list) {
                                    $("#exam_list").html(list);
                                });
                            });
                            $.post("modal/loader.php", function (load) {
                                $("#recent_deleted_exam").html(load);
                                $.post("fragments/list_recently_deleted_exam.php", function (list) {
                                    $("#recent_deleted_exam").html(list);
                                });
                            });
                            $(".btnDelAllExam").prop("disabled", true);
                        } else {
                            Swal.fire("DELETE", "Something went wrong, delete unsuccessful", "error");
                        }
                    });
                }
            });
        });

        $("[data-checkboxes]").each(function() {
            var me = $(this),
                group = me.data("checkboxes"),
                role = me.data("checkbox-role");

            me.change(function() {
                var all = $("[data-checkboxes=" + group + "]:not([data-checkbox-role=dad])"),
                    checked = $("[data-checkboxes=" + group + "]:not([data-checkbox-role=dad]):checked"),
                    dad = $("[data-checkboxes=" + group + "][data-checkbox-role=dad]"),
                    total = all.length,
                    checked_length = checked.length;

                if(role == "dad") {
                    if(me.is(":checked")) {
                        $(".btnDelAllExam").prop("disabled", false);
                        me.prop("checked", true);
                    }else{
                        $(".btnDelAllExam").prop("disabled", true);
                        me.prop("checked", false);
                    }
                    if (dad.is(":checked")){
                        $(".btnDelAllExam").prop("disabled", false);
                        all.prop("checked", true);
                    }else {
                        $(".btnDelAllExam").prop("disabled", true);
                        all.prop("checked", false);
                    }
                }else{
                    if(checked_length >= total) {
                        $(".btnDelAllExam").prop("disabled", false);
                        dad.prop("checked", true);
                    }else{
                        $(".btnDelAllExam").prop("disabled", false);
                        dad.prop("checked", false);
                    }
                }
                if (all.is(":checked")){
                    $(".btnDelAllExam").prop("disabled", false);
                }else {
                    $(".btnDelAllExam").prop("disabled", true);
                }
            });
        });

        $(".btnViewExams").click(function (){
            var exam_id = $(this).attr("data-id");
            Swal.fire({
                title: "EXAM DETAILS?",
                html: "press continue to proceed to exam details",
                icon: "info",
                showCancelButton: true,
                confirmButtonColor: "#1c3d77",
                confirmButtonText: "Continue",
                cancelButtonText: "Cancel",
                closeOnConfirm: false,
                closeOnCancel: true
            }).then((result) => {
                if (result.isConfirmed) {
                    $.redirect('add_question.php', {exam_id: exam_id});
                }
            });
        });
    });
</script>
<div class="table-responsive">
<table class="table table-sm" id="ulog">
    <thead>
    <tr>
        <th style="display: none;">ID</th>
        <th class="text-center">
            <div class="custom-checkbox custom-control">
                <input type="checkbox" data-checkboxes="mygroup" data-checkbox-role="dad" class="custom-control-input" id="checkbox-all">
                <label for="checkbox-all" class="custom-control-label">&nbsp;</label>
            </div>
        </th>
        <th>Title</th>
        <th>Division</th>
        <th class="text-center">Number of Test</th>
        <th class="text-center">Time Limit</th>
        <th class="text-center">Status</th>
        <th class="text-center">Action</th>
    </tr>
    </thead>
    <tbody>
    <?php
    $index = 1;
    if (isset($_REQUEST['division']) && isset($_REQUEST['department']) && isset($_REQUEST['status'])){
        $department = $_REQUEST['department'];
        $division = $_REQUEST['division'];
        $status = $_REQUEST['status'];
        $examQuery1 = $conn->query("SELECT * FROM exam_title WHERE department = '$department' AND division = '$division' AND isActiveExam = '$status' AND isDeletedExam = '0'");
    }
    else if (isset($_REQUEST['division']) && isset($_REQUEST['department'])){
        $department = $_REQUEST['department'];
        $division = $_REQUEST['division'];
        $examQuery1 = $conn->query("SELECT * FROM exam_title WHERE department = '$department' AND division = '$division' AND isDeletedExam = '0'");

    }
    else if (isset($_REQUEST['department']) && isset($_REQUEST['status'])){
        $department = $_REQUEST['department'];
        $status = $_REQUEST['status'];
        $examQuery1 = $conn->query("SELECT * FROM exam_title WHERE department = '$department' AND isActiveExam = '$status' AND isDeletedExam = '0'");
    }
    else if (isset($_REQUEST['status'])){
        $status = $_REQUEST['status'];
        $examQuery1 = $conn->query("SELECT * FROM exam_title WHERE isActiveExam = '$status' AND isDeletedExam = '0'");
    }
    else if (isset($_REQUEST['department'])){
        $department = $_REQUEST['department'];
        $examQuery1 = $conn->query("SELECT * FROM exam_title WHERE department = '$department' AND isDeletedExam = '0'");
    }
    else {
        $examQuery1 = $conn->query("SELECT * FROM exam_title WHERE isDeletedExam = '0'");
    }
    if ($examQuery1->rowCount() > 0){
        while ($exam = $examQuery1->fetch()){
    $exam_id = $exam['id'];
    $exam_title = $exam['title'];
    $exam_div = $exam['division'];
    $exam_test = $exam['num_test'];
    $time_limit = $exam['time_limit'];
    $isActive = $exam['isActiveExam']; ?>
        <script>
            $(document).ready( function (){
                var toggle = $("#togBtn<?php echo $exam_id; ?>");
                var isActive = "<?php echo $isActive; ?>";
                if (isActive == '1'){
                    toggle.prop("checked", true);
                    $("#btnviewDetails<?php echo $exam_id; ?>").addClass("disabled");
                    $("#checkbox<?php echo $exam_id; ?>").attr({disabled:true, "data-checkboxes":""});
                }else {
                    toggle.prop("checked", false);
                    $("#btnviewDetails<?php echo $exam_id; ?>").removeClass("disabled");
                    $("#checkbox<?php echo $exam_id; ?>").attr({disabled:false, "data-checkboxes":"mygroup"});
                }
                var check_disabled = $("#checkbox<?php echo $exam_id; ?>:disabled");
                var btndisabled = $("#btnviewDetails<?php echo $exam_id; ?>.disabled");
                check_disabled.click(function (){
                    Swal.fire("SELECT", "<strong>DEACTIVATE</strong> the exam first to select the exam", "info");
                });
                btndisabled.click(function (){
                    Swal.fire("EDIT DETAILS", "<strong>Deactivate</strong> the exam first to view & edit exam", "info");
                });
                if (toggle.is(":checked")){
                    toggle.click(function (){
                        var off = '0';
                        var exam_id = toggle.val();
                        var title = "<?php echo $exam_title; ?>";
                        var uid = "<?php echo $idU; ?>";
                        Swal.fire({
                            title: "DEACTIVATE?",
                            html: "Are you sure you want to deactivate "+title,
                            icon: "warning",
                            showCancelButton: true,
                            confirmButtonColor: "#1c3d77",
                            confirmButtonText: "Yes, I am sure",
                            cancelButtonText: "No, Cancel it",
                            closeOnConfirm: false,
                            closeOnCancel: false
                        }).then((result) => {
                            if (result.isConfirmed) {
                                $.post("../queries/isActive_exam.php", {exam_id:exam_id, user_id:uid, isActive:off, exam_title:title}, function (isActive){
                                    if (isActive == 1){
                                        $.post("modal/loader.php", function (load) {
                                            $("#exam_list").html(load);
                                            $.post("fragments/list_exam_table.php", function (list) {
                                                $("#exam_list").html(list);
                                            });
                                        });
                                        $.post("modal/loader.php", function (load) {
                                            $("#recent_deleted_exam").html(load);
                                            $.post("fragments/list_recently_deleted_exam.php", function (list) {
                                                $("#recent_deleted_exam").html(list);
                                            });
                                        });
                                        $(".btnDelAllExam").prop("disabled", true);
                                    }else {
                                        Swal.fire("DEACTIVATE", "Something went wrong, DEACTIVATE unsuccessful", "error");
                                    }
                                });
                            }else {
                                toggle.prop("checked", true);
                            }
                        });
                    });
                }
                else {
                    toggle.click(function (){
                        var on = '1';
                        var exam_id = toggle.val();
                        var title = "<?php echo $exam_title; ?>";
                        var uid = "<?php echo $idU; ?>";
                        Swal.fire({
                            title: "ACTIVATE?",
                            html: "Are you sure you want to activate "+title,
                            icon: "warning",
                            showCancelButton: true,
                            confirmButtonColor: "#1c3d77",
                            confirmButtonText: "Yes, I am sure",
                            cancelButtonText: "No, Cancel it",
                            closeOnConfirm: false,
                            closeOnCancel: false
                        }).then((result) => {
                            if (result.isConfirmed) {
                                $.post("../queries/isActive_exam.php", {exam_id:exam_id, user_id:uid, isActive:on, exam_title:title}, function (isActive){
                                    if (isActive == 1){
                                        $.post("modal/loader.php", function (load) {
                                            $("#exam_list").html(load);
                                            $.post("fragments/list_exam_table.php", function (list) {
                                                $("#exam_list").html(list);
                                            });
                                        });
                                        $(".btnDelAllExam").prop("disabled", true);
                                    }else {
                                        Swal.fire("ACTIVATE", "Something went wrong, ACTIVATE unsuccessful", "error");
                                    }
                                });
                            }else {
                                toggle.prop("checked", false);
                            }
                        });
                    });
                }
            });
        </script>
    <tr  style="line-height: 30px;" class="align-items-center">
        <td style="display: none;"><?php echo $exam_id; ?></td>
        <th  scope="row" class="text-center">
            <div class="custom-checkbox custom-control">
                <input type="checkbox" data-checkboxes="mygroup" class="custom-control-input" id="checkbox<?php echo $exam_id; ?>" value="<?php echo $exam_id; ?>">
                <label for="checkbox<?php echo $exam_id; ?>" class="custom-control-label">&nbsp;</label>
            </div>
        </th>
        <td><?php echo $exam_title ?></td>
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
        <td class="text-center" ><?php echo $time_limit; ?></td>
            <td class="text-center">
                <label class="switch">
                    <input type="checkbox" id="togBtn<?php echo $exam_id; ?>" name="isActive" value="<?php echo $exam_id; ?>">
                    <div class="slider round">
                        <span class="on">Activated</span>
                        <span class="off">Deactivated</span>
                    </div>
                </label>
            </td>
        <td class="text-center">
            <button type='button' class='btn btn-sm btn-primary btnViewExams' id="btnviewDetails<?php echo $exam_id; ?>" data-id='<?php echo $exam_id ?>'>
                <i class='fas fa-pencil-alt'></i>&nbsp;
                Exam Details
            </button>&nbsp;

        </td>
    </tr>
    <?php }
    }?>
    </tbody>
</table>
</div>