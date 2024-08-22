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
        $("#ulog").dataTable({
            order: [[ 0, 'desc' ]],
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
                        $('.btnRestoreAllUser').prop("disabled", false);
                        me.prop("checked", true);
                    }else{
                        $('.btnRestoreAllUser').prop("disabled", true);
                        me.prop("checked", false);
                    }
                    if (dad.is(":checked")){
                        $('.btnRestoreAllUser').prop("disabled", false);
                        all.prop("checked", true);
                    }else {
                        $('.btnRestoreAllUser').prop("disabled", true);
                        all.prop("checked", false);
                    }
                }else{
                    if(checked_length >= total) {
                        $('.btnRestoreAllUser').prop("disabled", false);
                        dad.prop("checked", true);
                    }else{
                        $('.btnRestoreAllUser').prop("disabled", false);
                        dad.prop("checked", false);
                    }
                }
                if (all.is(":checked")){
                    $('.btnRestoreAllUser').prop("disabled", false);
                }else {
                    $('.btnRestoreAllUser').prop("disabled", true);
                }
            });
        });

        $('.btnRestoreAllUser').on('click', function (){
            var user = [];
            var user_checkbox = $("[data-boxes=delgroup]:not([data-box-role=dad]):checked");
            for (var i = 0; i < user_checkbox.length; i++) {
                user.push(user_checkbox[i].value)
            }
            var length = user_checkbox.length;
            var uid = "<?php echo $idU; ?>";
            Swal.fire({
                title: "RESTORE",
                html: "Are you sure you want to restore selected user?",
                icon: "info",
                showCancelButton: true,
                confirmButtonText: "Yes, I am sure",
                cancelButtonText: "No, Cancel it",
                closeOnConfirm: false,
                closeOnCancel: true
            }).then((result) => {
                if (result.isConfirmed) {
                    $.post("queries/restore_user.php", {all_user:user, user_id:uid, length:length}, function (del) {
                        if (del == 1) {
                            $.post("modal/loader.php", function (load){
                                $("#examinee_list_deleted").html(load);
                                $.post("fragments/list_examinee_table_deleted.php", function (list){
                                    $("#examinee_list_deleted").html(list);
                                });
                            });
                            $('.btnRestoreAllUser').prop("disabled", true);
                        } else {
                            Swal.fire("RESTORE", "Something went wrong, restore unsuccessful", "error");
                        }
                    });
                }
            });
        });
    });
</script>
<div class="table-responsive">
    <table class="table table-sm" id="ulog">
        <thead>
        <tr>
            <th scope="col" style="display: none;">ID</th>
            <th scope="col" >
                <div class="custom-checkbox custom-control">
                    <input type="checkbox" data-boxes="delgroup" data-box-role="dad" class="custom-control-input" id="checkbox-deleted">
                    <label for="checkbox-deleted" class="custom-control-label">&nbsp;</label>
                </div>
            </th>
            <th scope="col">Control No.</th>
            <th scope="col">User name</th>
            <th scope="col">Division</th>
            <th scope="col">Address</th>
        </tr>
        </thead>
        <tbody>
        <?php
        $index = 1;
        $userQuery = $conn->query("SELECT * FROM user WHERE usertype = 'examinee' AND isDelActive = '1' AND department = '$department'");
        while ($user = $userQuery->fetch()){
            $userid = $user['id'];
            $userexamno = $user['exam_no'];
            $username = $user['lname']. ", " . $user['fname'] . " " . $user['mname'];
            $userfulladd = $user['home_address'] . ', '. $user['brgy'] . ', ' .  $user['city'] . ', ' . $user['province'];
        if (isset($_POST['division'])){
            $user_div = $_POST['division'];
            $div_query = $conn->query("SELECT division FROM user_division WHERE user_id = '$userid' AND division = '$user_div'");
            if ($div_query->rowCount() > 0 ) { ?>
                <tr>
                    <td style="display: none;"><?php echo $userid; ?></td>
                    <td class="text-center">
                        <div class="custom-checkbox custom-control">
                            <input type="checkbox" data-boxes="delgroup" class="custom-control-input" id="box<?php echo $userid; ?>" value="<?php echo $userid; ?>">
                            <label for="box<?php echo $userid; ?>" class="custom-control-label">&nbsp;</label>
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
                </tr>
            <?php }
        }
            else {
            ?>
            <tr>
                <td style="display: none;"><?php echo $userid; ?></td>
                <td class="text-center">
                    <div class="custom-checkbox custom-control">
                        <input type="checkbox" data-boxes="delgroup" class="custom-control-input" id="box<?php echo $userid; ?>" value="<?php echo $userid; ?>">
                        <label for="box<?php echo $userid; ?>" class="custom-control-label">&nbsp;</label>
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
            </tr>
        <?php
        }
    }?>
        </tbody>
    </table>
</div>