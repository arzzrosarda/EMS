<?php
    global $conn;
    session_start();
    require "../../../db/conn.php";
    if (isset($_POST['dept_id'])){
        $dept_id = $_POST['dept_id'];
        $dept = $_POST['dept'];
        $dept_name = $_POST['dept_name'];
        $dept_logo = $_POST['dept_logo'];
        $dept_no = $_POST['dept_no'];
        ?>
        <script>
            $(document).ready(function (){
                var dept_id = "<?php echo $_POST['dept_id']; ?>";
                var dept = "<?php echo $_POST['dept']; ?>";
                var dept_name = "<?php echo $_POST['dept_name']; ?>";
                var dept_logo = "<?php echo $_POST['dept_logo']; ?>";
                var dept_DeptNo = "<?php echo $_POST['dept_no']; ?>";

                $(".btnAddDivision").on('click', function (){
                    showModal();
                    $("#loader").modal("show");
                    $("#viewDepartment").modal("hide");
                    $("#addDivision").modal("show");
                    $.post("fragments/list_division.php", {dept_id:dept_id, dept:dept, dept_name:dept_name, dept_logo:dept_logo, dept_DeptNo:dept_DeptNo}, function (div){
                        hideModal();
                        $("#loader").modal("hide");
                        $("#addDivisionContainerBody").html(div);
                    });
                });
                $(".btnAddAccount").on('click', function (){
                    showModal();
                    $("#loader").modal("show");
                    $("#viewDepartment").modal("hide");
                    $("#addAccount").modal("show");
                    $.post("fragments/account_form.php", {dept_id:dept_id, dept:dept, dept_name:dept_name, dept_logo:dept_logo, dept_DeptNo:dept_DeptNo}, function (acc){
                        hideModal();
                        $("#loader").modal("hide");
                        $("#addAccountContainerBody").html(acc);
                    });
                });

                $("#btnResetPass").on("click", function (){
                    var user_id = $(this).attr("data-id");
                    showModal();
                    $("#loader").modal("show");
                    $("#viewDepartment").modal("hide");
                    $("#resetDepartmentPassword").modal("show");
                    $.post("fragments/reset_password.php", {user_id:user_id, dept_id:dept_id, dept:dept, dept_name:dept_name, dept_logo:dept_logo, dept_DeptNo:dept_DeptNo}, function (reset){
                        hideModal();
                        $("#loader").modal("hide");
                        $("#resetDepartmentPasswordContainerBody").html(reset)
                    });

                });
            });
        </script>
        <div class="row">
            <div class="form-group col-4">
                <label>Department Logo: </label>
                <div class="profile-widget-picture text-center p-2" data-toggle="tooltip" title="<?php echo $dept_name; ?>" style="box-shadow: none;">
                    <div class="wrap-custom-file-logo-view">
                        <label class="file-ok" style="background-image: url('../../assets/uploads/<?php echo $dept; ?>/logo/<?php echo $dept_logo; ?>')">
                        </label>
                    </div>
                </div>
            </div>
            <div class="form-group col-8">
                <label>Department Initials: </label>
                <div class="row ">
                    <div class="form-group col-6">
                        <ul class="list-group">
                            <li class="list-group-item"><?php echo $dept; ?></li>
                        </ul>
                    </div>
                </div>
                <label>Department Name: </label>
                <div class="row">
                    <div class="form-group col-12">
                        <ul class="list-group">
                            <li class="list-group-item"><?php echo $dept_name; ?></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
        <div class="row pt-0">
            <div class="form-group col-12">
                <label>Contact No.: </label>
                <ul class="list-group">
                    <li class="list-group-item">
                        <?php echo $dept_no; ?>
                    </li>
                </ul>
            </div>
        </div>
        <div class="row">
            <div class="form-group col-6">
                <label>Division Offices: </label>
            </div>
            <?php
            $division_query = $conn->query("SELECT * FROM division WHERE department_id = '$dept_id'");
            if ($division_query->rowCount() > 0){ ?>
            <div class="form-group col-6">
            </div>
        </div>
        <div class="row">
            <div class="form-group col-12">
                <ul class="list-group">
                    <?php while($div = $division_query->fetch()){
                        ?>
                        <li class="list-group-item"><?php echo $div['division']; ?></li>
                    <?php }

                    }else { ?>
                    <div class="form-group col-6 text-md-right">
                        <button class="btn btn-sm btn-primary btnAddDivision"
                                data-id="<?php echo $dept_id; ?>"
                                data-dept="<?php echo $dept; ?>"
                                data-name="<?php echo $dept_name; ?>"
                                data-logo="<?php echo $dept_logo; ?>"
                                data-deptNo="<?php echo $dept_no; ?>">
                            Add Division
                        </button>
                    </div>
            </div>
            <div class="row">
                <div class="form-group col-12">
                    <ul class="list-group">
                        <li class="list-group-item">No Division Yet</li>

                        <?php }
                        ?>
                    </ul>
                </div>
            </div>
            <?php
                $account_query = $conn->query("SELECT * FROM user WHERE department = '$dept' AND usertype = 'admin'");
                $account = $account_query->fetch();
                if ($account_query->rowCount() > 0){ ?>
                    <div class="row">
                        <div class="form-group col-6">
                            <h6>Account Information</h6>
                        </div>
                        <div class="form-group col-6 text-right">
                            <button class="btn btn-warning" type="button" id="btnResetPass" data-id="<?php echo $account['id']; ?>">Reset Password</button>
                        </div>
                    </div>
                    <table class="table table-striped table-bordered">
                        <tbody>
                        <tr>
                            <td class="text-right w-25">Username: </td>
                            <td class="w-75"><?php echo $account['username']; ?></td>
                        </tr>
                        <tr>
                            <td class="text-right w-25">Email Address: </td>
                            <td class="w-75"><?php echo $account['email']; ?></td>
                        </tr>
                        <tr>
                            <td class="text-right w-25">Contact No: </td>
                            <td class="w-75"><?php echo $account['contact_no']; ?></td>
                        </tr>
                        </tbody>
                    </table>
                <?php
                }
                else { ?>
                    <div class="row">
                        <div class="form-group col-6">
                            <h6>Account Information</h6>
                        </div>
                        <div class="form-group col-6 text-md-right">
                            <button class="btn btn-sm btn-primary btnAddAccount"
                                    data-id="<?php echo $dept_id; ?>"
                                    data-dept="<?php echo $dept; ?>"
                                    data-name="<?php echo $dept_name; ?>"
                                    data-logo="<?php echo $dept_logo; ?>"
                                    data-deptNo="<?php echo $dept_no; ?>">
                                Add Account
                            </button>
                        </div>
                    </div>
                    <table class="table table-striped table-bordered">
                        <tbody>
                        <tr>
                            <td></td>
                            <td>No Account Yet</td>
                        </tr>
                        </tbody>
                    </table>
                <?php }
                ?>
        <?php
    }
    ?>