<?php
global $conn;
session_start();
require "../../../db/conn.php"; ?>
<script>
    $(document).ready( function (){
        var dept_id = "<?php echo $_POST['dept_id']; ?>";
        var dept = "<?php echo $_POST['dept']; ?>";
        var dept_name = "<?php echo $_POST['dept_name']; ?>";
        var dept_logo = "<?php echo $_POST['dept_logo']; ?>";
        var dept_DeptNo = "<?php echo $_POST['dept_DeptNo']; ?>";

        $("#btnNoteDiv").on("click", function (){
            var btnSubmit = $("#btnAddDivision");
            Swal.fire({
                title: "SAVE",
                html: "<strong>Note: </strong> This is a one time adding of division per department, Make sure that you input all needed information." +
                    "<br>Press continue to save your progress.",
                icon: "info",
                showCancelButton: true,
                confirmButtonText: "Continue",
                cancelButtonText: "Cancel",
                closeOnConfirm: false,
            }).then((result) => {
                if (result.isConfirmed) {
                    btnSubmit.click();
                }
            });
        });

        $("form[name=div_form]").on("submit", function (ev) {
            ev.preventDefault();
            var form = new FormData(this);
            $.ajax({
                url: "queries/add_Div.php",
                type: "POST",
                data: form,
                contentType: false,
                cache: false,
                processData: false,
                success: function (data) {
                    if (data == "invalid") {
                        alert("error");
                    }else {
                        showModal();
                        $("#loader").modal("show");
                        $("#addDivision").modal("hide");
                        $("#viewDepartment").modal("show");
                        $.post("queries/viewdepartment.php", {dept_id:dept_id, dept_name:dept_name, dept:dept, dept_logo:dept_logo, dept_no:dept_DeptNo}, function (Dept){
                            hideModal();
                            $("#loader").modal("hide");
                            $("#viewDepartmentContainerBody").html(Dept);
                        });
                        $.post("modal/loader.php", function (load) {
                            $("#dept_list").html(load);
                            $.post("fragments/department.php", function (user) {
                                $("#dept_list").html(user);
                            });
                        });
                    }
                },
                error: function () {
                    Swal.fire("ERROR", "Something Went Wrong!!", "error");
                }
            });
        });

        $("#btnCancel").on('click', function (){
            $.post("queries/delete_division.php", {dept_id:dept_id});
            showModal();
            $("#loader").modal("show");
            $("#addDivision").modal("hide");
            $("#viewDepartment").modal("show");
            $.post("queries/viewdepartment.php", {dept_id:dept_id, dept_name:dept_name, dept:dept, dept_logo:dept_logo, dept_no:dept_DeptNo}, function (Dept){
                hideModal();
                $("#loader").modal("hide");
                $("#viewDepartmentContainerBody").html(Dept);
            });
        });

        $.post("modal/loader.php", function (load){
            $("#input_division").html(load);
            $.post("fragments/input_division.php", {dept_id:dept_id}, function (input){
                $("#input_division").html(input);
            })
        });
        $(".btnAddDiv").on('click', function (){
            $(".btnAddDiv").addClass("btn-progress");
            $.post("queries/addDivision.php", {dept_id:dept_id}, function (res){
                if (res == 1){
                    $(".btnAddDiv").removeClass("btn-progress");
                    $.post("fragments/input_division.php", {dept_id:dept_id}, function (div){
                        $("#input_division").html(div);
                    });
                }
            });
        });
    });
</script>
<div class="row">
    <div class="form-group col-12 text-right">
        <button type="button" class="btn btn-primary btnAddDiv"
                data-id="<?php echo $_POST['dept_id']; ?>"
                data-dept="<?php echo $_POST['dept']; ?>"
                data-name="<?php echo $_POST['dept_name']; ?>"
                data-logo="<?php echo $_POST['dept_logo']; ?>"
                data-deptNo="<?php echo $_POST['dept_DeptNo']; ?>">
            Add Division
        </button>
    </div>
</div>
<div class="row">
    <div class="form-group col-12" id="input_division">

    </div>
</div>



