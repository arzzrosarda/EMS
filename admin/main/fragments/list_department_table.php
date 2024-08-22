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
        // list of departments
        $("#dep_list").dataTable({
            order: [[ 0, 'desc']],
            "bAutoWidth": false
        });
        $(".btnDelAllDept").click( function (){
            var department = [];
            var department_checkbox = $("[data-checkboxes=mygroup]:not([data-checkbox-role=dad]):checked");
            for (var i = 0; i < department_checkbox.length; i++) {
                department.push(department_checkbox[i].value)
            }
            var length = department_checkbox.length;
            var uid = "<?php echo $idU; ?>";
            Swal.fire({
                title: "DELETE",
                html: "This will transfer department, examinees, and exams under the department into the deleted section. Are you sure you want to delete this department?",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#1c3d77",
                confirmButtonText: "Yes, I am sure",
                cancelButtonText: "No, Cancel it",
                closeOnConfirm: false,
                closeOnCancel: true
            }).then((result) => {
                if (result.isConfirmed) {
                    $.post("queries/del_department.php", {all_department:department, user_id:uid, length:length}, function (del) {
                        if (del == 1) {
                            $.post("modal/loader.php", function(load){
                                $("#department_list").html(load);
                                $.post("fragments/list_department_table.php", function (list){
                                    $("#department_list").html(list);
                                });
                            });
                            $(".btnDelAllDept").prop("disabled", true);
                        }else {
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
                        $(".btnDelAllDept").prop("disabled", false);
                        me.prop("checked", true);
                    }else{
                        $(".btnDelAllDept").prop("disabled", true);
                        me.prop("checked", false);
                    }
                    if (dad.is(":checked")){
                        $(".btnDelAllDept").prop("disabled", false);
                        all.prop("checked", true);
                    }else {
                        $(".btnDelAllDept").prop("disabled", true);
                        all.prop("checked", false);
                    }
                }else{
                    if(checked_length >= total) {
                        $(".btnDelAllDept").prop("disabled", false);
                        dad.prop("checked", true);
                    }else{
                        $(".btnDelAllDept").prop("disabled", false);
                        dad.prop("checked", false);
                    }
                }
                if (all.is(":checked")){
                    $(".btnDelAllDept").prop("disabled", false);
                }else {
                    $(".btnDelAllDept").prop("disabled", true);
                }
            });
        });

    });
</script>
<div class="table-responsive">
    <table class="table table-sm" id="dep_list">
        <thead>
        <tr>
            <th style="display: none;">ID</th>
            <th class="text-center">
                <div class="custom-checkbox custom-control">
                    <input type="checkbox" data-checkboxes="mygroup" data-checkbox-role="dad" class="custom-control-input" id="checkbox-all">
                    <label for="checkbox-all" class="custom-control-label">&nbsp;</label>
                </div>
            </th>
            <th>Department Initials</th>
            <th>Department Name</th>
            <th>Email Address</th>
            <th>Contact No.</th>
            <th>Status</th>
            <th class="text-center">Action</th>
        </tr>
        </thead>
        <tbody>
        <?php
        if (isset($_POST['account']) && isset($_POST['div'])){
            $account = $_POST['account'];
            $div = $_POST['div'];
            $department_query = $conn->query("SELECT a.`id` AS dept_id, a.`department`, a.`department_name`, a.`department_logo`, a.`department_no`, COUNT(b.`username`) AS countAccount, b.`email`, b.`username`, COUNT(c.`division`) AS countDiv FROM department a LEFT JOIN user b ON b.`department` = a.`department` AND b.`usertype` = 'admin' LEFT JOIN division c ON a.`id` = c.`department_id` WHERE department_active = '0' GROUP BY a.`id` HAVING COUNT(b.`username`) $account 0 AND COUNT(c.`division`) $div 0");
        }
        else if (isset($_POST['account'])){
            $account = $_POST['account'];
            $department_query = $conn->query("SELECT a.`id` AS dept_id, a.`department`, a.`department_name`, a.`department_logo`, a.`department_no`, COUNT(b.`username`) AS countAccount, b.`email`, b.`username`, COUNT(c.`division`) AS countDiv FROM department a LEFT JOIN user b ON b.`department` = a.`department` AND b.`usertype` = 'admin' LEFT JOIN division c ON a.`id` = c.`department_id` WHERE department_active = '0' GROUP BY a.`id` HAVING COUNT(b.`username`) $account 0 ");
        }
        else if (isset($_POST['div'])){
            $div = $_POST['div'];
            $department_query = $conn->query("SELECT a.`id` AS dept_id, a.`department`, a.`department_name`, a.`department_logo`, a.`department_no`, COUNT(b.`username`) AS countAccount, b.`email`, b.`username`, COUNT(c.`division`) AS countDiv FROM department a LEFT JOIN user b ON b.`department` = a.`department` AND b.`usertype` = 'admin' LEFT JOIN division c ON a.`id` = c.`department_id` WHERE department_active = '0' GROUP BY a.`id` HAVING COUNT(c.`division`) $div 0");
        }
        else {
            $department_query = $conn->query("SELECT a.`id` AS dept_id, a.`department`, a.`department_name`, a.`department_logo`, a.`department_no`, COUNT(b.`username`) AS countAccount, b.`email`, b.`username`, COUNT(c.`division`) AS countDiv FROM department a LEFT JOIN user b ON b.`department` = a.`department` AND b.`usertype` = 'admin' LEFT JOIN division c ON a.`id` = c.`department_id` WHERE department_active = '0' GROUP BY a.`id`");
        }
            while ($dept = $department_query->fetch()){
                $dept_id = $dept['dept_id'];
                $department = $dept['department'];
                $dept_name = $dept['department_name'];
                $dept_logo = $dept['department_logo'];
                $contact_no = $dept['department_no'];
                if ($dept['countAccount'] > 0){
                    $dept_email_tag = "Something";
                    $dept_email = $dept['email'];
                    $dept_account_tag = "info";
                    $dept_account = "Account Created";

                }else {
                    $dept_email_tag = "light";
                    $dept_email = "No Email Yet";
                    $dept_account_tag = "light";
                    $dept_account = "No Account Yet";

                }
                if ($dept['countDiv'] > 0){
                    $dept_division = $dept['countDiv']." Division Created";
                    $dept_division_tag = "info";
                }else {
                    $dept_division = "No Division Yet";
                    $dept_division_tag = "light";
                }
                ?>
                <script>
                    $(document).ready(function (){
                        $("#viewDepartment<?php echo $dept_id; ?>").on('click',function (){
                            var dept_id = $(this).attr("data-id");
                            var dept = "<?php echo $department; ?>";
                            var dept_name = "<?php echo $dept_name; ?>";
                            var dept_logo = "<?php echo $dept_logo; ?>";
                            var dept_no = "<?php echo $contact_no; ?>";
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
                <tr style="line-height: 30px;" class="align-items-center">
                    <td style="display: none;"><?php echo $dept_id; ?></td>
                    <th scope="row" class="text-center">
                        <div class="custom-checkbox custom-control">
                            <input type="checkbox" data-checkboxes="mygroup" class="custom-control-input" id="checkbox<?php echo $dept_id; ?>" value="<?php echo $dept_id; ?>">
                            <label for="checkbox<?php echo $dept_id; ?>" class="custom-control-label">&nbsp;</label>
                        </div>
                    </th>
                    <td><?php echo $department ?></td>
                    <td><?php echo $dept_name ; ?></td>
                    <td>
                        <div class="badge badge-pill badge-<?php echo $dept_email_tag; ?> mb-1">
                            <?php echo $dept_email ; ?>
                        </div>
                    </td>
                    <td><?php echo $contact_no ; ?></td>
                    <td>
                        <div class="badge badge-pill badge-<?php echo $dept_account_tag; ?> mb-1">
                            <?php echo $dept_account; ?>
                        </div>
                        <br>
                        <div class="badge badge-pill badge-<?php echo $dept_division_tag; ?> mb-1">
                            <?php echo $dept_division; ?>
                        </div>
                    </td>
                    <td><button class="btn btn-primary btn-sm" type="button" id="viewDepartment<?php echo $dept_id; ?>" data-id='<?php echo $dept_id; ?>'>
                            <i class="fas fa-file-alt"></i>&nbsp;
                            View Details
                        </button>
                    </td>
                </tr>
            <?php
        } ?>
        </tbody>
    </table>
</div>