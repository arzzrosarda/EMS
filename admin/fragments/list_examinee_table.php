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
    $(document).ready( function (){
        $("#rlog").dataTable({
            order: [[ 0, 'desc' ]],
            "bAutoWidth": false
        });

        $('.btnViewDetails').on('click', function (){
            var uid = $(this).attr("data-id");
            showModal();
            $("#loader").show();
            $("#viewModal").modal("show");
            $.post("queries/viewdetails.php", {userid:uid}, function (user){
                hideModal();
                $("#loader").hide();
                $("#viewContainerBody").html(user);
            });
        });

        $('.btnViewExams').on('click', function (){
            var uid = $(this).attr("data-id");
            showModal();
            $("#loader").show();
            $("#viewExamModal").modal("show");
            $.post("queries/viewUserExam.php", {userid:uid}, function (user){
                hideModal();
                $("#loader").hide();
                $("#viewExamContainerBody").html(user);
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
                        $(".btnViewDel").prop("disabled", false);
                        me.prop("checked", true);
                    }else{
                        $(".btnViewDel").prop("disabled", true);
                        me.prop("checked", false);
                    }
                    if (dad.is(":checked")){
                        $(".btnViewDel").prop("disabled", false);
                        all.prop("checked", true);
                    }else {
                        $(".btnViewDel").prop("disabled", true);
                        all.prop("checked", false);
                    }
                }else{
                    if(checked_length >= total) {
                        $(".btnViewDel").prop("disabled", false);
                        dad.prop("checked", true);
                    }else{
                        $(".btnViewDel").prop("disabled", false);
                        dad.prop("checked", false);
                    }
                }
                if (all.is(":checked")){
                    $(".btnViewDel").prop("disabled", false);
                }else {
                    $(".btnViewDel").prop("disabled", true);
                }
            });
        });

        $(".btnViewDel").click( function (){
            var user = [];
            var user_checkbox = $("[data-checkboxes=mygroup]:not([data-checkbox-role=dad]):checked");
            for (var i = 0; i < user_checkbox.length; i++) {
                user.push(user_checkbox[i].value)
            }
            var length = user_checkbox.length;
            var uid = "<?php echo $idU; ?>";
            Swal.fire({
                title: "DELETE",
                html: "Are you sure you want to delete this user?",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#1c3d77",
                confirmButtonText: "Yes, I am sure",
                cancelButtonText: "No, Cancel it",
                closeOnConfirm: false,
                closeOnCancel: true
            }).then((result) => {
                if (result.isConfirmed) {
                    $.post("queries/del_all_user.php", {all_user: user, user_id: uid, u_length: length}, function (del) {
                        if (del == 1) {
                            $.post("modal/loader.php", function (load) {
                                $("#list-of-examinee").html(load);
                                $.post("fragments/list_examinee_table.php", function (list) {
                                    $("#list-of-examinee").html(list);
                                });
                            });
                            $(".btnViewDel").prop("disabled", true);
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
<table class="table table-sm" id="rlog">
    <thead >
    <tr>
        <th scope="col" style="display: none;">ID</th>
        <th scope="col">
            <div class="custom-checkbox custom-control">
                <input type="checkbox" data-checkboxes="mygroup" data-checkbox-role="dad" class="custom-control-input" id="checkbox-all">
                <label for="checkbox-all" class="custom-control-label">&nbsp;</label>
            </div>
        </th>
        <th scope="col">Control No.</th>
        <th scope="col">Full Name</th>
        <th scope="col">Division</th>
        <th scope="col">Address</th>
        <th scope="col">Action</th>
    </tr>
    </thead>
    <tbody>
    <?php
    $index = 1;
    $userQuery = $conn->query("SELECT * FROM user WHERE usertype = 'examinee' AND isDelActive = '0' AND department = '$department'");
    while ($user = $userQuery->fetch()){
        $userid = $user['id'];
        $userexamno = $user['exam_no'];
        $username = $user['lname']. ", " . $user['fname'] . " " . $user['mname'];
        $userfulladd = $user['home_address'] . ', '. $user['brgy'] . ', ' .  $user['city'] . ', ' . $user['province'];
        if (isset($_POST['division'])){
        $user_div = $_POST['division'];
        $div_query = $conn->query("SELECT division FROM user_division WHERE user_id = '$userid' AND division = '$user_div'");
        if ($div_query->rowCount() > 0 ) { ?>
            <tr style="line-height: 35px;" class="align-items-center">
                <th scope="row" style="display: none;"><?php echo $userid; ?></th>
                <td>
                    <div class="custom-checkbox custom-control">
                        <input type="checkbox" data-checkboxes="mygroup" class="custom-control-input" id="checkbox<?php echo $userid; ?>" value="<?php echo $userid; ?>">
                        <label for="checkbox<?php echo $userid; ?>" class="custom-control-label">&nbsp;</label>
                    </div>
                </td>
                <td><?php echo $userexamno; ?></td>
                <td><?php echo $username; ?></td>
                <td><?php
                    $division = $conn->query("SELECT division FROM user_division WHERE user_id = '$userid'");
                    while ($divi = $division->fetch()) {
                        echo '<div class="bullet"></div>'.$divi['division'];
                    }
                    ?></td>
                <td><?php echo $userfulladd; ?></td>
                <td>
                    <button type='button' class='btn btn-sm btn-primary btnViewDetails' data-id='<?php echo $userid; ?>'>
                        <i class='fas fa-address-book'></i>&nbsp;
                        View Details
                    </button>
                    <button type='button' class='btn btn-sm btn-info btnViewExams' data-id='<?php echo $userid; ?>'>
                        <i class='fas fa-file-import'></i>&nbsp;
                        Exam Details
                    </button>&nbsp;
                </td>
            </tr>
        <?php }
        }
        else { ?>
        <tr style="line-height: 35px;" class="align-items-center">
            <th scope="row" style="display: none;"><?php echo $userid; ?></th>
            <td>
                <div class="custom-checkbox custom-control">
                    <input type="checkbox" data-checkboxes="mygroup" class="custom-control-input" id="checkbox<?php echo $userid; ?>" value="<?php echo $userid; ?>">
                    <label for="checkbox<?php echo $userid; ?>" class="custom-control-label">&nbsp;</label>
                </div>
            </td>

            <td><?php echo $userexamno; ?></td>
            <td><?php echo $username; ?></td>
            <td><?php
                $division = $conn->query("SELECT division FROM user_division WHERE user_id = '$userid'");
                while ($divi = $division->fetch()) {
                    echo '<div class="bullet"></div>'.$divi['division'];
                }
                ?></td>
            <td><?php echo $userfulladd; ?></td>
            <td>
                <button type='button' class='btn btn-sm btn-primary btnViewDetails' data-id='<?php echo $userid; ?>'>
                    <i class='fas fa-address-book'></i>&nbsp;
                    View
                </button>
                <button type='button' class='btn btn-sm btn-info btnViewExams' data-id='<?php echo $userid; ?>'>
                    <i class='fas fa-file-import'></i>&nbsp;
                    Results
                </button>&nbsp;

            </td>
        </tr>
    <?php }
    } ?>
    </tbody>
</table>
</div>
