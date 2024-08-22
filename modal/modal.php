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
