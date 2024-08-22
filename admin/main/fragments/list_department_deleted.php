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
    $(document).ready(function (){

        $("#dept_del").dataTable({
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
                        $('.btnRestoreAllDepartment').prop("disabled", false);
                        $(".btnDelAllDepartment").prop("disabled", false);
                        me.prop("checked", true);
                    }else{
                        $(".btnDelAllDepartment").prop("disabled", true);
                        $('.btnRestoreAllDepartment').prop("disabled", true);
                        me.prop("checked", false);
                    }
                    if (dad.is(":checked")){
                        $('.btnRestoreAllDepartment').prop("disabled", false);
                        $(".btnDelAllDepartment").prop("disabled", false);
                        all.prop("checked", true);
                    }else {
                        $(".btnDelAllDepartment").prop("disabled", true);
                        $('.btnRestoreAllDepartment').prop("disabled", true);
                        all.prop("checked", false);
                    }
                }else{
                    if(checked_length >= total) {
                        $('.btnRestoreAllDepartment').prop("disabled", false);
                        $(".btnDelAllDepartment").prop("disabled", false);
                        dad.prop("checked", true);
                    }else{
                        $('.btnRestoreAllDepartment').prop("disabled", false);
                        $(".btnDelAllDepartment").prop("disabled", false);
                        dad.prop("checked", false);
                    }
                }
                if (all.is(":checked")){
                    $('.btnRestoreAllDepartment').prop("disabled", false);
                    $(".btnDelAllDepartment").prop("disabled", false);
                }else {
                    $(".btnDelAllDepartment").prop("disabled", true);
                    $('.btnRestoreAllDepartment').prop("disabled", true);
                }
            });
        });

        $('.btnRestoreAllDepartment').on('click', function (){
            var department = [];
            var department_checkbox = $("[data-boxes=delgroup]:not([data-box-role=dad]):checked");
            for (var i = 0; i < department_checkbox.length; i++) {
                department.push(department_checkbox[i].value)
            }
            var length = department_checkbox.length;
            var uid = "<?php echo $idU; ?>";
            Swal.fire({
                title: "RESTORE",
                html: "This will restore department, examinees and exams under the department into the active list, Are you sure you want to restore selected department?",
                icon: "info",
                showCancelButton: true,
                confirmButtonText: "Yes, I am sure",
                cancelButtonText: "No, Cancel it",
                closeOnConfirm: false,
                closeOnCancel: true
            }).then((result) => {
                if (result.isConfirmed) {
                    $.post("queries/restore_department.php", {all_department:department, user_id:uid, length:length}, function (del) {
                        if (del == 1) {
                            $.post("modal/loader.php", function (load) {
                                $("#department_deleted_list").html(load);
                                $.post("fragments/list_department_deleted.php", function (list) {
                                    $("#department_deleted_list").html(list);
                                });
                            });
                            $('.btnRestoreAllDepartment').prop("disabled", true);
                            $(".btnDelAllDepartment").prop("disabled", true);
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
<table class="table table-sm" id="dept_del">
    <thead>
    <tr>
        <th style="display: none;">ID</th>
        <th class="text-center">
            <div class="custom-checkbox custom-control">
                <input type="checkbox" data-boxes="delgroup" data-box-role="dad" class="custom-control-input" id="checkbox-deleted">
                <label for="checkbox-deleted" class="custom-control-label">&nbsp;</label>
            </div>
        </th>
        <th>Department Initials</th>
        <th>Department Name</th>
        <th>Email Address</th>
        <th>Contact No.</th>
        <th>Status</th>
    </tr>
    </thead>
    <tbody>
    <?php
    $index = 1;
    if (isset($_POST['account']) && isset($_POST['div'])){
        $account = $_POST['account'];
        $div = $_POST['div'];
        $department_query = $conn->query("SELECT a.`id` AS dept_id, a.`department`, a.`department_name`, a.`department_logo`, a.`department_no`, COUNT(b.`username`) AS countAccount, b.`email`, b.`username`, COUNT(c.`division`) AS countDiv FROM department a LEFT JOIN user b ON b.`department` = a.`department` AND b.`usertype` = 'admin' LEFT JOIN division c ON a.`id` = c.`department_id` WHERE department_active = '1' GROUP BY a.`id` HAVING COUNT(b.`username`) $account 0 AND COUNT(c.`division`) $div 0");
    }
    else if (isset($_POST['account'])){
        $account = $_POST['account'];
        $department_query = $conn->query("SELECT a.`id` AS dept_id, a.`department`, a.`department_name`, a.`department_logo`, a.`department_no`, COUNT(b.`username`) AS countAccount, b.`email`, b.`username`, COUNT(c.`division`) AS countDiv FROM department a LEFT JOIN user b ON b.`department` = a.`department` AND b.`usertype` = 'admin' LEFT JOIN division c ON a.`id` = c.`department_id` WHERE department_active = '1' GROUP BY a.`id` HAVING COUNT(b.`username`) $account 0 ");
    }
    else if (isset($_POST['div'])){
        $div = $_POST['div'];
        $department_query = $conn->query("SELECT a.`id` AS dept_id, a.`department`, a.`department_name`, a.`department_logo`, a.`department_no`, COUNT(b.`username`) AS countAccount, b.`email`, b.`username`, COUNT(c.`division`) AS countDiv FROM department a LEFT JOIN user b ON b.`department` = a.`department` AND b.`usertype` = 'admin' LEFT JOIN division c ON a.`id` = c.`department_id` WHERE department_active = '1' GROUP BY a.`id` HAVING COUNT(c.`division`) $div 0");
    }
    else {
        $department_query = $conn->query("SELECT a.`id` AS dept_id, a.`department`, a.`department_name`, a.`department_logo`, a.`department_no`, COUNT(b.`username`) AS countAccount, b.`email`, b.`username`, COUNT(c.`division`) AS countDiv FROM department a LEFT JOIN user b ON b.`department` = a.`department` AND b.`usertype` = 'admin' LEFT JOIN division c ON a.`id` = c.`department_id` WHERE department_active = '1' GROUP BY a.`id`");
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
            $dept_account_tag = "success";
            $dept_account = "Account Created";

        }else {
            $dept_email_tag = "warning";
            $dept_email = "No Email Yet";
            $dept_account_tag = "warning";
            $dept_account = "No Account Yet";

        }
        if ($dept['countDiv'] > 0){
            $dept_division = $dept['countDiv']." Division Created";
            $dept_division_tag = "success";
        }else {
            $dept_division = "No Division Yet";
            $dept_division_tag = "warning";
        }
        ?>
        <tr style="line-height: 30px;" class="align-items-center">
            <td style="display: none;"><?php echo $dept_id; ?></td>
            <th scope="row" class="text-center">
                <div class="custom-checkbox custom-control">
                    <input type="checkbox" data-boxes="delgroup" class="custom-control-input" id="box<?php echo $dept_id; ?>" value="<?php echo $dept_id; ?>">
                    <label for="box<?php echo $dept_id; ?>" class="custom-control-label">&nbsp;</label>
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
        </tr>
        <?php
    } ?>
    </tbody>
</table>
</div>
