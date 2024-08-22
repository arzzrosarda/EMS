<?php
    global $conn;
    session_start();
    require "../../db/conn.php";


?>
<script>
    $(document).ready(function (){
        var dismiss = $("#btnCancelReset");
        $(function () {
            $("#eye_reset").click(function () {
                $(this).toggleClass("fa-eye fa-eye-slash");
                var type = $(this).hasClass("fa-eye-slash") ? "text" : "password";
                $("#password_reset").attr("type", type);
            });
        });

        $(function () {
            $("#eye_reset1").click(function () {
                $(this).toggleClass("fa-eye fa-eye-slash");
                var type = $(this).hasClass("fa-eye-slash") ? "text" : "password";
                $("#password_reset2").attr("type", type);
            });
        });

        function isValidPassword($pass) {
            var pattern = new RegExp(/^(?=.*\d)(?=.*[a-z])(?=.*[A-Z])[0-9a-zA-Z]{8,}$/);
            return pattern.test($pass);
        }
        $("#btnReset").on('click', function (){
            var password = $("#password_reset").val();
            var re_password = $("#password_reset2").val();
            var user_id = $("#user_id").val();
            if (password == ''){
                $("#password_reset").focus();
                iziToast.info({
                    title: "PASSWORD",
                    message: "is required!!",
                    position: "topRight"
                });
            }else if (!isValidPassword(password)){
                $("#password_reset").focus();
                iziToast.info({
                    title: "PASSWORD",
                    message: "must be 8 Characters long, 1 uppercase, 1 lowercase, and 1 number.!!",
                    position: "topRight"
                });
            }else if (re_password == ''){
                $("#password_reset2").focus();
                iziToast.info({
                    title: "PASSWORD CONFIRMATION",
                    message: "is required!!",
                    position: "topRight"
                });
            }else if (re_password != password){
                $("#password_reset2").focus();
                iziToast.info({
                    title: "PASSWORD CONFIRMATION",
                    message: "does not match!!",
                    position: "topRight"
                });
            }else {
                $.post('queries/reset_pass.php', {user_id:user_id, password:password}, function (res){
                   if (res == 1){
                       iziToast.success({
                           title: "PASSWORD",
                           message: "Successfully reset!!",
                           position: "topRight"
                       });
                       dismiss.click();
                   }
                });
            }
        });
    });
</script>
<div class="row"></div>
<div class="row">
    <div class="form-group col-12">
        <label for="password" class="d-block">Password <label style="color:red;">*</label></label>
        <div class="input-group">
            <input id="user_id" type="hidden" class="form-control" value="<?php echo $_POST['user_id']; ?>">
            <input id="password_reset" type="password" class="form-control pwstrength" data-indicator="pwindicator" name="password" required>
            <div class="input-group-prepend">
                <div class="input-group-text">
                    <i class="fas fa-eye" style="cursor:pointer;" id="eye_reset"></i>
                </div>
            </div>
        </div>
        <span style="font-size: 10px;">Note: Password must be 8 Characters long, 1 uppercase, 1 lowercase, and 1 number. </span>
    </div>
    <div class="form-group col-12">
        <label for="password2" class="d-block">Password Confirmation <label style="color:red;">*</label></label>
        <div class="input-group">
            <input id="password_reset2" type="password" class="form-control" name="password-confirm" required>
            <div class="input-group-prepend">
                <div class="input-group-text">
                    <i class="fas fa-eye" style="cursor:pointer;" id="eye_reset1"></i>
                </div>
            </div>
        </div>
    </div>
</div>
