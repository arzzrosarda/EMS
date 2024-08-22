<?php
    global $conn;
    session_start();
    require "../../../db/conn.php";
    if (isset($_POST['dept_id'])){
        $dept = $_POST['dept'];
        $dept_id = $_POST['dept_id'];
        $dept_name = $_POST['dept_name'];
        ?>
        <script>

            $(function () {
                $("#eye_account").click(function () {
                    $(this).toggleClass("fa-eye fa-eye-slash");
                    var type = $(this).hasClass("fa-eye-slash") ? "text" : "password";
                    $("#txtpassword").attr("type", type);
                });
            });

            $(function () {
                $("#eye_account1").click(function () {
                    $(this).toggleClass("fa-eye fa-eye-slash");
                    var type = $(this).hasClass("fa-eye-slash") ? "text" : "password";
                    $("#txtpassword2").attr("type", type);
                });
            });
            $(document).ready(function (){
                var dept_id = "<?php echo $_POST['dept_id']; ?>";
                var dept = "<?php echo $_POST['dept']; ?>";
                var dept_name = "<?php echo $_POST['dept_name']; ?>";
                var dept_logo = "<?php echo $_POST['dept_logo']; ?>";
                var dept_DeptNo = "<?php echo $_POST['dept_DeptNo']; ?>";

                $("#btnCancelAccount").on('click', function (){
                    showModal();
                    $("#loader").modal("show");
                    $("#addAccount").modal("hide");
                    $("#viewDepartment").modal("show");
                    $.post("queries/viewdepartment.php", {dept_id:dept_id, dept_name:dept_name, dept:dept, dept_logo:dept_logo, dept_no:dept_DeptNo}, function (Dept){
                        hideModal();
                        $("#loader").modal("hide");
                        $("#viewDepartmentContainerBody").html(Dept);
                    });
                });

                function validateEmail($email) {
                    var emailReg = /^([\w-\.]+@([\w-]+\.)+[\w-]{2,4})?$/;
                    return emailReg.test( $email );
                }
                function isValidPassword($pass) {
                    var pattern = new RegExp(/^(?=.*\d)(?=.*[a-z])(?=.*[A-Z])[0-9a-zA-Z]{8,}$/);
                    return pattern.test($pass);
                }
                $("#btnAddAccount").on('click', function (){
                    var username = $("#txtuser_name").val();
                    var name = $("#txt_name").val();
                    var email = $("#txtemail").val();
                    var contact_no = $("#txtPhone").val();
                    var password = $("#txtpassword").val();
                    var password2 = $("#txtpassword2").val();

                    if (username == ''){
                        $("#txtuser_name").focus();
                        iziToast.info({
                           title: "USERNAME",
                           message: "is required!!",
                           position: "topRight"
                        });
                    }else if (name == ''){
                        $("#txt_name").focus();
                        iziToast.info({
                            title: "ACCOUNT NAME",
                            message: "is required!!",
                            position: "topRight"
                        });
                    }else if (email == ''){
                        $("#txtemail").focus();
                        iziToast.info({
                            title: "EMAIL",
                            message: "is required!!",
                            position: "topRight"
                        });
                    }
                    else if (!validateEmail(email)){
                        $("#txtemail").focus();
                        iziToast.info({
                            title: "EMAIL",
                            message: "email is not valid!!",
                            position: "topRight"
                        });
                    }else if (contact_no == ''){
                        $("#txtPhone").focus();
                        iziToast.info({
                            title: "CONTACT NUMBER",
                            message: "is required!!",
                            position: "topRight"
                        });
                    }else if (password == ''){
                        $("#txtpassword").focus();
                        iziToast.info({
                            title: "PASSWORD",
                            message: "is required!!",
                            position: "topRight"
                        });
                    }
                    else if (!isValidPassword(password)){
                        $("#txtpassword").focus();
                        iziToast.info({
                            title: "PASSWORD",
                            message: "must be 8 Characters long, 1 uppercase, 1 lowercase, and 1 number.!!",
                            position: "topRight"
                        });
                    }else if (password2 != password){
                        $("#txtpassword2").focus();
                        iziToast.info({
                            title: "PASSWORD",
                            message: "does not match!!",
                            position: "topRight"
                        });
                    }else {
                        Swal.fire({
                            title: "ADD ACCOUNT",
                            html: "<strong>NOTE:</strong> This is a one time creation of account per department <br>" +
                                "Press continue to create the account.",
                            icon: "info",
                            showCancelButton: true,
                            confirmButtonText: "Continue",
                            cancelButtonText: "Cancel",
                            closeOnConfirm: false,
                        }).then((result) => {
                            if (result.isConfirmed) {
                                $.post("queries/add_department_account.php", {dept_id:dept_id, dept:dept, username:username, name:name, email:email, contact_no:contact_no, password:password}, function (res){
                                    if (res == 1){
                                        iziToast.success({
                                           title: "SUCCESS",
                                           message: "Successfully Added Account",
                                           position: "topRight"
                                        });
                                        showModal();
                                        $("#loader").modal("show");
                                        $("#addAccount").modal("hide");
                                        $("#viewDepartment").modal("show");
                                        $.post("queries/viewdepartment.php", {dept_id:dept_id, dept_name:dept_name, dept:dept, dept_logo:dept_logo, dept_no:dept_DeptNo}, function (Dept){
                                            hideModal();
                                            $("#loader").modal("hide");
                                            $("#viewDepartmentContainerBody").html(Dept);
                                        });
                                        $.post("modal/loader.php", function(load){
                                            $("#department_list").html(load);
                                            $.post("fragments/list_department_table.php", function (list){
                                                $("#department_list").html(list);
                                            });
                                        });
                                    }else {
                                        iziToast.error({
                                            title: "ERROR",
                                            message: "Something went wrong!!",
                                            position: "topRight"
                                        });
                                    }
                                });
                            }
                        });
                    }

                });
            });

        </script>
        <div class="row">
            <div class="form-group col-12">
                <h6><?php echo $dept." - ".$dept_name; ?></h6>
                <span style="font-size: 10px;">Note: all <label style="color:red;">*</label> is required!</span>
            </div>
        </div>
        <div class="row">
            <div class="form-group col-6" >
                <label>Account Username</label>
                <input type="text" class="form-control" id="txtuser_name" placeholder="Type username here..." required>
            </div>
            <div class="form-group col-6" >
                <label>Account name</label>
                <input type="text" class="form-control" id="txt_name" value="<?php echo $dept; ?>" placeholder="Type account name here..." required>
            </div>
        </div>
        <div class="row">
            <div class="form-group col-6" >
                <label>Account email</label>
                <input type="email" class="form-control" id="txtemail" placeholder="Type email here..." required>
            </div>
            <div class="form-group col-6" >
                <label>Account Contact No.</label>
                <input type="text" class="form-control" id="txtPhone" placeholder="Type contact infromation here..." required>
            </div>
        </div>
        <div class="row">
            <div class="form-group col-6">
                <label for="password" class="d-block">Password <label style="color:red;">*</label></label>
                <div class="input-group">
                    <input id="txtpassword" type="password" class="form-control pwstrength" data-indicator="pwindicator" name="password" required>
                    <div class="input-group-prepend">
                        <div class="input-group-text">
                            <i class="fas fa-eye" style="cursor:pointer;" id="eye_account"></i>
                        </div>
                    </div>
                </div>
                <span style="font-size: 10px;">Note: Password must be 8 Characters long, 1 uppercase, 1 lowercase, and 1 number. </span>
            </div>
            <div class="form-group col-6">
                <label for="password2" class="d-block">Password Confirmation <label style="color:red;">*</label></label>
                <div class="input-group">
                    <input id="txtpassword2" type="password" class="form-control" name="password-confirm" required>
                    <div class="input-group-prepend">
                        <div class="input-group-text">
                            <i class="fas fa-eye" style="cursor:pointer;" id="eye_account1"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
<?php }
?>


