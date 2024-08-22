<!-- Change PW Modal -->
<div class="modal animated zoomIn" id="changepwModal" data-backdrop="static" data-keyboard="false" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Change Password</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close" tabindex="1">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <div class="form-group">
          <label for="txtoldpw">Old Password</label>
            <input id="txtuserid" type="text" class="form-control" value="<?php echo $row['id']; ?>" style="display: none;" required>
            <div class="input-group">
                <input id="txtoldpw" type="password" class="form-control" tabindex="2" autocomplete="false" required>
                <div class="input-group-prepend">
                    <div class="input-group-text">
                        <i class="fas fa-eye" style="cursor:pointer;" id="eye"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="form-group">
          <div class="d-block">
          	<label for="newpw" class="control-label">New Password</label>
          </div>
            <div class="input-group">
                <input type="password" id="newpw" class="form-control" tabindex="3" required>
                <div class="input-group-prepend">
                    <div class="input-group-text">
                        <i class="fas fa-eye" style="cursor:pointer;" id="eye1"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="form-group">
          <div class="d-block">
          	<label for="renewpw" class="control-label">Re-type New Password</label>
          </div>
            <div class="input-group">
                <input type="password" id="renewpw" class="form-control" tabindex="4" required>
                <div class="input-group-prepend">
                    <div class="input-group-text">
                        <i class="fas fa-eye" style="cursor:pointer;" id="eye2"></i>
                    </div>
                </div>
            </div>
        </div>
      </div>
      <div class="modal-footer bg-whitesmoke br">
        <button type="button" class="btn btn-primary" id="updatepw" tabindex="5">Save changes</button>
        <button type="button" class="btn btn-light" data-dismiss="modal" tabindex="6">Close</button>
      </div>
      <script>
          $(function () {
              $("#eye").click(function () {
                  $(this).toggleClass("fa-eye fa-eye-slash");
                  var type = $(this).hasClass("fa-eye-slash") ? "text" : "password";
                  $("#txtoldpw").attr("type", type);
              });
          });
          $(function () {
              $("#eye1").click(function () {
                  $(this).toggleClass("fa-eye fa-eye-slash");
                  var type = $(this).hasClass("fa-eye-slash") ? "text" : "password";
                  $("#newpw").attr("type", type);
              });
          });
          $(function () {
              $("#eye2").click(function () {
                  $(this).toggleClass("fa-eye fa-eye-slash");
                  var type = $(this).hasClass("fa-eye-slash") ? "text" : "password";
                  $("#renewpw").attr("type", type);
              });
          });
          function isValidPassword($pass) {
              var pattern = new RegExp(/^(?=.*\d)(?=.*[a-z])(?=.*[A-Z])[0-9a-zA-Z]{8,}$/);
              return pattern.test($pass);
          }
          $('#updatepw').on('click', function(){
              oldpw = $("#txtoldpw").val();
              newpw = $("#newpw").val();
              Renewpw = $("#renewpw").val();
              uid = $("#txtuserid").val();
              if (oldpw == '') {
                  $("#txtoldpw").focus();
                  $("#txtoldpw").css({"border-color":"red"});
                  iziToast.warning({
                      title: 'EMPTY',
                      message: 'Old password field is empty!',
                      position: 'topRight'
                  });
              }else if(newpw == ''){
                  $("#newpw").focus();
                  $("#newpw").css({"border-color":"red"});
                  iziToast.warning({
                      title: 'EMPTY',
                      message: 'New password field is empty!',
                      position: 'topRight'
                  });
              }else if (!isValidPassword(newpw)) {
                  $("#newpw").focus();
                  $("#newpw").css({"border-color":"red"});
                  iziToast.warning({
                      title: 'Password!',
                      message: 'must be 8 Characters long, 1 Uppercase , 1 lowercase, and 1 Number',
                      position: 'topRight'
                  });
              }else if(Renewpw != newpw){
                  $("#renewpw").focus();
                  $("#renewpw").css({"border-color":"red"});
                  iziToast.warning({
                      title: 'Password!',
                      message: 'does not match',
                      position: 'topRight'
                  });
              }else {
                  Swal.fire({
                      title: "CHANGE?",
                      html: "Are you sure, you want to change your password?",
                      icon: "info",
                      showCancelButton: true,
                      confirmButtonText: "Ok, Sure",
                      cancelButtonText: "No, Cancel it",
                      closeOnConfirm: false,
                  }).then((result) => {
                      if (result.isConfirmed) {
                          $.post("queries/change.php", {userid:uid, password:oldpw, newpass:newpw}, function(res){
                              if (res == 1) {
                                  Swal.fire({
                                      title: "Successfully Changed password!",
                                      icon: "success",
                                      confirmButtonText: "Ok",
                                      allowOutsideClick: false,
                                      closeOnConfirm: false,
                                  }).then((result) => {
                                      if (result.isConfirmed) {
                                          $("#changepwModal").modal('hide');
                                          $("#txtoldpw").val('');
                                          $("#newpw").val('');
                                          $("#renewpw").val('');
                                          $("#renewpw").css({"border-color":""});
                                          $("#newpw").css({"border-color":""});
                                          $("#txtoldpw").css({"border-color":""});
                                      }
                                  });
                              }else if (res == 2) {
                                  Swal.fire("WRONG PASSWORD", "Old Password is not correct", "error");
                              }
                          });
                      }
                  });

              }
          });
          
      </script>
    </div>
  </div>
</div>

<!-- View Details -->
<div class="modal animated zoomIn" id="viewModal" data-backdrop="static" data-keyboard="false" tabindex="-1">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">INFORMATION</h5>
                <button type="button" class="close" data-dismiss="modal" id="btnCloseDetails" aria-label="Close" tabindex="6">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form method="get" name="register" enctype="multipart/form-data" action="">
                    <div id="viewContainerBody">

                    </div>
                    <button type="submit" class="btn btn-success" id="btnSaveFormDetails" style="display: none;">Save</button>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" id="btnEditDetails">EDIT</button>
                <button type="button" class="btn btn-success" id="btnSaveDetails" style="display:none;">SAVE</button>
                <button type="button" class="btn btn-light" id="btnCancelDetails" style="display:none;">CANCEL</button>
            </div>
            <script>
                $(document).ready( function (){
                    function validateEmail($email) {
                        var emailReg = /^([\w-\.]+@([\w-]+\.)+[\w-]{2,4})?$/;
                        return emailReg.test( $email );
                    }

                    $("#btnCloseDetails").on("click", function (){
                        $("#btnEditDetails").show();
                        $("#btnSaveDetails").hide();
                        $("#btnCancelDetails").hide();
                    });
                    var btnSubmit = $("#btnSaveFormDetails");
                    $("#btnEditDetails").on("click", function (){
                        Swal.fire({
                            title: "EDIT DETAILS?",
                            html: "Press continue to proceed to editing details ",
                            icon: "info",
                            showCancelButton: true,
                            confirmButtonText: "Continue",
                            cancelButtonText: "Cancel",
                            closeOnConfirm: false,
                        }).then((result) => {
                            if (result.isConfirmed) {
                                $("#btnEditDetails").hide();
                                $("#btnSaveDetails").show();
                                $("#btnCancelDetails").show();

                                $("#td_exam_no").hide();
                                $("#td_username").hide();
                                $("#td_lname").hide();
                                $("#td_fname").hide();
                                $("#td_mname").hide();
                                $("#td_department").hide();
                                $("#td_division").hide();
                                $("#td_email").hide();
                                $("#td_gender").hide();
                                $("#td_contact").hide();
                                $("#td_home").hide();
                                $("#td_brgy").hide();
                                $("#td_city").hide();
                                $("#td_province").hide();
                                $("#td_postal_code").hide();

                                $("#td_exam_no_edit").show();
                                $("#td_username_edit").show();
                                $("#td_lname_edit").show();
                                $("#td_fname_edit").show();
                                $("#td_mname_edit").show();
                                $("#td_department_edit").show();
                                $("#td_division_edit").show();
                                $("#td_email_edit").show();
                                $("#td_gender_edit").show();
                                $("#td_contact_edit").show();
                                $("#td_home_edit").show();
                                $("#td_brgy_edit").show();
                                $("#td_city_edit").show();
                                $("#td_province_edit").show();
                                $("#td_postal_code_edit").show();
                            }
                        });
                    });

                    $("#btnCancelDetails").on("click", function (){
                        Swal.fire({
                            title: "CANCEL EDIT DETAILS?",
                            html: "Press continue to proceed to cancel editing details ",
                            icon: "info",
                            showCancelButton: true,
                            confirmButtonText: "Continue",
                            cancelButtonText: "Cancel",
                            closeOnConfirm: false,
                        }).then((result) => {
                            if (result.isConfirmed) {
                                $("#btnEditDetails").show();
                                $("#btnSaveDetails").hide();
                                $("#btnCancelDetails").hide();

                                $("#td_exam_no").show();
                                $("#td_username").show();
                                $("#td_lname").show();
                                $("#td_fname").show();
                                $("#td_mname").show();
                                $("#td_department").show();
                                $("#td_division").show();
                                $("#td_email").show();
                                $("#td_gender").show();
                                $("#td_contact").show();
                                $("#td_home").show();
                                $("#td_brgy").show();
                                $("#td_city").show();
                                $("#td_province").show();
                                $("#td_postal_code").show();

                                $("#td_exam_no_edit").hide();
                                $("#td_username_edit").hide();
                                $("#td_lname_edit").hide();
                                $("#td_fname_edit").hide();
                                $("#td_mname_edit").hide();
                                $("#td_department_edit").hide();
                                $("#td_division_edit").hide();
                                $("#td_email_edit").hide();
                                $("#td_gender_edit").hide();
                                $("#td_contact_edit").hide();
                                $("#td_home_edit").hide();
                                $("#td_brgy_edit").hide();
                                $("#td_city_edit").hide();
                                $("#td_province_edit").hide();
                                $("#td_postal_code_edit").hide();
                            }
                        });
                    });

                    $("form[name=register]").on("submit", function (ev) {
                        ev.preventDefault();
                        var user_id = $("#txt_user_id").val();
                        var form = new FormData(this);
                        $.ajax({
                            url: "queries/save_details.php",
                            type: "POST",
                            data: form,
                            contentType: false,
                            cache: false,
                            processData: false,
                            success: function (data) {
                                if (data == "3") {
                                    iziToast.warning({
                                        title: 'EMAIL!',
                                        message: 'was already in use!',
                                        position: 'topRight'
                                    });
                                }else if (data == '4'){
                                    iziToast.warning({
                                        title: 'CONTROL NUMBER',
                                        message: 'was already registered by other user!',
                                        position: 'topRight'
                                    });
                                }else if (data == '5'){
                                    iziToast.warning({
                                        title: 'USERNAME',
                                        message: 'was already in use!',
                                        position: 'topRight'
                                    });
                                }else if (data == '2'){
                                    iziToast.warning({
                                        title: 'ERROR',
                                        message: 'Something went wrong!!',
                                        position: 'topRight'
                                    });
                                }else if (data == '1'){
                                    showModal();
                                    $("#loader").show();
                                    $.post("queries/viewdetails.php", {userid:user_id}, function (user){
                                        hideModal();
                                        $("#loader").hide();
                                        $("#viewContainerBody").html(user);
                                    });
                                    $.post("modal/loader.php", function (load) {
                                        $("#main_examinees").html(load);
                                        $.post("fragments/examinees.php", function (user) {
                                            $("#main_examinees").html(user);
                                        });
                                    });
                                    $.post("modal/loader.php", function (load){
                                        $("#list-of-examinee").html(load);
                                        $.post("fragments/list_examinee_table.php", function (list){
                                            $("#list-of-examinee").html(list);
                                        });
                                    });
                                    $("#btnEditDetails").show();
                                    $("#btnSaveDetails").hide();
                                    $("#btnCancelDetails").hide();
                                    Swal.fire("SAVED", "Successfully saved details", "success");
                                }else {
                                    iziToast.warning({
                                        title: 'ERROR',
                                        message: 'Something went wrong!!',
                                        position: 'topRight'
                                    });
                                }
                            },
                            error: function () {
                                Swal.fire("ERROR", "Something Went Wrong!!", "error");
                            }
                        });


                    });

                    $("#btnSaveDetails").on("click", function (){
                        Swal.fire({
                            title: "SAVE?",
                            html: "Press continue to save details ",
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
                })
            </script>
        </div>
    </div>
</div>
<!-- View Details End -->

<!-- View User Exams -->
<div class="modal animated zoomIn" id="viewExamModal" data-backdrop="static" data-keyboard="false" tabindex="-1">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">EXAM INFORMATION</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close" tabindex="6">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div id="viewExamContainerBody">

                </div>
            </div>
            <div class="modal-footer">
            </div>
        </div>
    </div>
</div>
<!-- View User Exams End -->

<!-- View Review -->
<div class="modal animated zoomIn" id="viewReviewModal" data-backdrop="static" data-keyboard="false" tabindex="-1">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">FOR REVIEW</h5>
                <button type="button" class="close" id="examReviewClose" aria-label="Close" tabindex="6">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div id="viewReviewContainerBody">

                </div>
            </div>
            <div class="modal-footer">
            </div>
        </div>
    </div>
</div>
<!-- View Review End -->

<!-- Add Department -->
<div class="modal animated zoomIn" id="addDepartment" data-backdrop="static" data-keyboard="false" tabindex="-1">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Add Department</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close" tabindex="6">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form enctype="multipart/form-data" action="" name="department" method="post">
            <div class="modal-body p-5">
                <div class="row">
                    <div class="form-group col-4">
                        <label>Department Logo: </label>
                            <div class="profile-widget-picture text-center p-2" data-toggle="tooltip" title="Department Logo" style="box-shadow: none;">
                                <div class="wrap-custom-file-logo-modal">
                                    <input type="file" name="dept_logo" id="dept_logo" accept="image/png" required/>
                                    <label  for="dept_logo" class="file-ok">
                                    </label>
                                </div>
                            </div>
                        <div class="row justify-content-center">
                            <button type="submit" class="btn btn-round btn-primary">Save</button>
                        </div>
                    </div>
                    <div class="form-group col-8">

                        <div class="row ">

                            <div class="form-group col-6">
                                <label>Department Initials: </label>
                                <input type="text" class="form-control" name="txtDept"  oninput="this.value = this.value.toUpperCase()" placeholder="Type the acronym here...." required autofocus>
                            </div>
                            <div class="form-group col-6">
                                <label>Department Contact No: </label>
                                <input type="text" class="form-control" name="txtDeptNo" placeholder="Contact Number here...." required autofocus>
                            </div>
                        </div>
                        <label>Department Name: </label>
                        <div class="row">
                            <div class="form-group col-12">
                                <textarea class="form-control" name="txtDepartment" placeholder="Type the full name here...." required></textarea>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            </form>
            <script>
                $(document).ready(function (){
                    $("form[name=department]").on("submit", function (ev) {
                        ev.preventDefault();
                        var form = new FormData(this);
                        $.ajax({
                            url: "queries/submit_dept_img.php",
                            type: "POST",
                            data: form,
                            contentType: false,
                            cache: false,
                            processData: false,
                            success: function (data) {
                                if (data == "invalid") {
                                    alert("error");
                                }else if (data == "dept_exists"){
                                    alert("Department already exists");
                                }else {
                                    iziToast.success({
                                        title: "SUCCESS",
                                        message: "Successfully added department",
                                        position: "topRight"
                                    });
                                    $.post("modal/loader.php", function (load) {
                                        $("#dept_list").html(load);
                                        $.post("fragments/department.php", function (user) {
                                            $("#dept_list").html(user);
                                        });
                                    });
                                    $.post("modal/loader.php", function(load){
                                        $("#department_list").html(load);
                                        $.post("fragments/list_department_table.php", function (list){
                                            $("#department_list").html(list);
                                        });
                                    });
                                    $("#addDepartment").modal("hide");
                                }
                            },
                            error: function () {
                                Swal.fire("ERROR", "Something Went Wrong!!", "error");
                            }
                        });
                    });
                    $("input[type=file]").each(function () {
                        var $file = $(this),
                            $label = $file.next("label"),
                            $labelText = $label.find("span"),
                            labelDefault = $labelText.text();
                        $file.on("change", function (event) {

                            if (this.files[0].size > 2000000) {
                                Swal.fire("IMAGE", "Please upload file less than 2MB. Thanks!!", "info");
                            } else {
                                var fileName = $file.val().split("\\").pop(),
                                    tmppath = URL.createObjectURL(event.target.files[0]);
                                if (fileName) {
                                    $label
                                        .addClass("file-ok")
                                        .css("background-image", "url('" + tmppath + "')");
                                } else {
                                    $label.removeClass("file-ok");
                                    $labelText.text(labelDefault);
                                }
                            }
                        });
                    });
                });
            </script>
        </div>
    </div>
</div>
<!-- Add Department End -->

<!-- View Department -->
<div class="modal animated zoomIn" id="viewDepartment" data-backdrop="static" data-keyboard="false" tabindex="-1">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">DEPARTMENT INFORMATION</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close" tabindex="6">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body p-5">
                <div id="viewDepartmentContainerBody">

                </div>
            </div>
        </div>
    </div>
</div>
<!-- View Department End -->

<!-- Add Division -->
<div class="modal animated zoomIn" id="addDivision" data-backdrop="static" data-keyboard="false" tabindex="-1">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">ADD DIVISION</h5>
            </div>
            <form action="" method="get" enctype="multipart/form-data" name="div_form">
                <div class="modal-body p-5">
                    <div id="addDivisionContainerBody">

                    </div>
                </div>
                <div class="modal-footer">
                    <div class="row">
                        <div class="form-group col-12 text-right">
                            <button type="button" class="btn btn-primary" id="btnNoteDiv">Save</button>
                            <button type="button" class="btn btn-secondary" id="btnCancel">Cancel</button>
                            <button type="submit" class="btn btn-primary" id="btnAddDivision" style="display: none;">Save</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- Add Division End -->

<!-- Add Account -->
<div class="modal animated zoomIn" id="addAccount" data-backdrop="static" data-keyboard="false" tabindex="-1">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">ADD ACCOUNT</h5>
            </div>
                <div class="modal-body p-5">
                    <div id="addAccountContainerBody">

                    </div>
                </div>
                <div class="modal-footer">
                    <div class="row">
                        <div class="form-group col-12 text-right">
                            <button type="button" class="btn btn-primary" id="btnAddAccount">Save</button>
                            <button type="button" class="btn btn-secondary" id="btnCancelAccount">Cancel</button>
                        </div>
                    </div>
                </div>
        </div>
    </div>
</div>
<!-- Add Account End -->

<!-- Reset Password Division -->
<div class="modal animated zoomIn" id="resetDepartmentPassword" data-backdrop="static" data-keyboard="false" tabindex="-1">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">RESET PASSWORD</h5>
            </div>
            <div class="modal-body p-5">
                <div id="resetDepartmentPasswordContainerBody">

                </div>
            </div>
            <div class="modal-footer">
                <div class="row">
                    <div class="form-group col-12 text-right">
                        <button type="button" class="btn btn-primary" id="btnReset">Reset</button>
                        <button type="button" class="btn btn-secondary" id="btnCancelReset" data-dismiss="modal">Cancel</button>
                    </div>
                </div>
            </div>
            <script>

            </script>
        </div>
    </div>
</div>
<!-- Reset Password End -->

<!-- View Feedback -->
<div class="modal animated zoomIn" id="viewFeedback" data-backdrop="static" data-keyboard="false" tabindex="-1">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">FEEDBACK</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close" tabindex="6">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body p-5">
                <div id="viewFeedbackContainerBody">

                </div>
            </div>
        </div>
    </div>
</div>
<!-- View Feedback End -->

<!-- View Exam -->
<div class="modal animated zoomIn" id="viewExamDetailsModal" data-backdrop="static" data-keyboard="false" tabindex="-1">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">EXAM DETAILS </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close" tabindex="6">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body p-5">
                <div id="viewExamDetailsModalContainerBody">

                </div>
            </div>
        </div>
    </div>
</div>
<!-- View Exam End -->