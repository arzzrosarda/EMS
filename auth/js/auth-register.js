

$(function () {
  $("#eye").click(function () {
    $(this).toggleClass("fa-eye fa-eye-slash");
    var type = $(this).hasClass("fa-eye-slash") ? "text" : "password";
    $("#password").attr("type", type);
  });
});

$(function () {
  $("#eye1").click(function () {
    $(this).toggleClass("fa-eye fa-eye-slash");
    var type = $(this).hasClass("fa-eye-slash") ? "text" : "password";
    $("#password2").attr("type", type);
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
$(document).ready(function() {

  $("#depselect").change( function (){
    var selected_dep = $("#depselect option:selected").val();
    $.post("queries/division_select.php", {department:selected_dep}, function(div){
      $("#divisionsel").html(div);
    });
  });

  $("#btnCheckElse").on("click", function (){
    $("#last_name").css({"border-color": ""});
    $("#first_name").css({"border-color": ""});
    $("#txt_examno1").css({"border-color": ""});
    $("#txt_examno2").css({"border-color": ""});
    $("#txt_examno3").css({"border-color": ""});
    $("#txtno").css({"border-color": ""});
    $("#username").css({"border-color": ""});
    $("#email").css({"border-color": ""});
    $("#depselect").css({"border-color": ""});
    $("#gendersel").css({"border-color": ""});
    $("#password").css({"border-color": ""});
    $("#password2").css({"border-color": ""});
    $("#txtaddress").css({"border-color": ""});
    $("#txtbrgy").css({"border-color": ""});
    $("#txtcitymun").css({"border-color": ""});
    $("#txtprov").css({"border-color": ""});
    $("#txtpostal").css({"border-color": ""});
    $("#agree").css({"border-color": ""});
    examno1 = $("#txt_examno1").val();
    examno2 = $("#txt_examno2").val();
    examno3 = $("#txt_examno3").val();
    username = $("#username").val();
    fn = $("#first_name").val();
    ln = $("#last_name").val();
    mn = $("#middle_name").val();
    phone = $("#txtno").val();
    phoneleng = phone.length;
    email = $("#email").val();
    department = $("#depselect option:selected").val();
    division = $("#div_checkbox").is(":checked");
    selgen = $("#gendersel").val();
    pass = $("#password").val();
    passleng = pass.length;
    pass2 = $("#password2").val();
    address = $("#txtaddress").val();
    brgy = $("#txtbrgy").val();
    citymun = $("#txtcitymun").val();
    prov = $("#txtprov").val();
    postal = $("#txtpostal").val();
    agree = $("#agree").is(":checked");
    if (examno1 == "" || examno2 == "" || examno3 == "") {
      $("#txt_examno1").focus();
      $("#txt_examno1").css({"border-color": "red"});
      iziToast.warning({
        title: 'Empty!',
        message: 'Exam Control Number is Required',
        position: 'topRight'
      });
    } else if (ln == "") {
      $("#last_name").focus();
      $("#last_name").css({"border-color": "red"});
      iziToast.warning({
        title: 'Empty!',
        message: 'Last Name is Required',
        position: 'topRight'
      });
    } else if (fn == "") {
      $("#first_name").focus();
      $("#first_name").css({"border-color": "red"});
      iziToast.warning({
        title: 'Empty!',
        message: 'First Name is Required',
        position: 'topRight'
      });
    } else if (username == "") {
      $("#username").focus();
      $("#username").css({"border-color": "red"});
      iziToast.warning({
        title: 'Empty!',
        message: 'Username is Required',
        position: 'topRight'
      });
    }else if (email == "") {
      $("#email").focus();
      $("#email").css({"border-color": "red"});
      iziToast.warning({
        title: 'Empty!',
        message: 'Email Address is Required',
        position: 'topRight'
      });
    } else if (!validateEmail(email)) {
      $("#email").focus();
      $("#email").css({"border-color": "red"});
      iziToast.warning({
        title: 'EMAIL INVALID!',
        message: 'Plese Provide a valid email address',
        position: 'topRight'
      });
    } else if (department == ""){
      $("#depselect").focus();
      $("#depselect").css({"border-color": "red"});
      iziToast.warning({
        title: 'Empty!',
        message: 'Department is required',
        position: 'topRight'
      });
    } else if (!division) {
      iziToast.warning({
        title: 'Empty!',
        message: 'You must select atleast one division or more!!',
        position: 'topRight'
      });
    } else if (selgen == "") {
      $("#gendersel").focus();
      $("#gendersel").css({"border-color": "red"});
      iziToast.warning({
        title: 'Empty!',
        message: 'Gender is required',
        position: 'topRight'
      });
    } else if (phone == "") {
      $("#txtno").focus();
      $("#txtno").css({"border-color": "red"});
      iziToast.warning({
        title: 'Empty!',
        message: 'Mobile phone Number is Required',
        position: 'topRight'
      });
    } else if (phoneleng < 11) {
      $("#txtno").focus();
      $("#txtno").css({"border-color": "red"});
      iziToast.warning({
        title: 'Contact Number!',
        message: 'Mobile phone number must be 11 in length',
        position: 'topRight'
      });
    }  else if (pass2 != pass) {
      $("#password2").focus();
      $("#password2").css({"border-color": "red"});
      iziToast.warning({
        title: 'Password!',
        message: 'does not match',
        position: 'topRight'
      });
    } else if (!isValidPassword(pass)) {
      $("#password").focus();
      $("#password").css({"border-color": "red"});
      iziToast.warning({
        title: 'Password!',
        message: 'must be 8 Characters long, 1 Uppercase , 1 lowercase, and 1 Number',
        position: 'topRight'
      });
    } else if (address == "") {
      $("#txtaddress").focus();
      $("#txtaddress").css({"border-color": "red"});
      iziToast.warning({
        title: 'Empty!',
        message: 'Address is required',
        position: 'topRight'
      });
    } else if (brgy == "") {
      $("#txtbrgy").focus();
      $("#txtbrgy").css({"border-color": "red"});
      iziToast.warning({
        title: 'Empty!',
        message: 'Barangay is required',
        position: 'topRight'
      });
    } else if (citymun == "") {
      $("#txtcitymun").focus();
      $("#txtcitymun").css({"border-color": "red"});
      iziToast.warning({
        title: 'Empty!',
        message: 'City/Municipality is required',
        position: 'topRight'
      });
    } else if (prov == "") {
      $("#txtprov").focus();
      $("#txtprov").css({"border-color": "red"});
      iziToast.warning({
        title: 'Empty!',
        message: 'Province is required',
        position: 'topRight'
      });
    } else if (postal == "") {
      $("#txtpostal").focus();
      $("#txtpostal").css({"border-color": "red"});
      iziToast.warning({
        title: 'Empty!',
        message: 'Postal code is required',
        position: 'topRight'
      });
    } else if (!agree) {
      $("#agree").focus();
      $("#agree").css({"border-color": "red"});
      iziToast.warning({
        title: 'Terms and Condition!',
        message: 'you must agree to the terms and condition to proceed with the registration',
        position: 'topRight'
      });
    }else {
      Swal.fire({
        title: "REGISTER?",
        html: "Are you sure you want to register?",
        icon: "info",
        showCancelButton: true,
        confirmButtonText: "Ok, Sure",
        cancelButtonText: "No, Cancel",
        closeOnConfirm: false,
      }).then((result) => {
        if (result.isConfirmed) {
          $("#btnSubmit").click();
        }
      });
    }
  });

  $("form[name=register]").on("submit", function (ev) {
    ev.preventDefault();
    var form = new FormData(this);
    $.ajax({
      url: "../queries/register.php",
      type: "POST",
      data: form,
      contentType: false,
      cache: false,
      processData: false,
      success: function (data) {
        if (data == "invalid") {
          alert("Something went wrong!!");
        }else if (data == 'EMExists'){
          iziToast.warning({
            title: 'EMAIL!',
            message: 'is already registered',
            position: 'topRight'
          });
        }else if (data == 'EExists'){
          iziToast.warning({
            title: 'EXAM',
            message: 'Control Number is already registered!',
            position: 'topRight'
          });
        }else if (data == 'UExists'){
          iziToast.warning({
            title: 'USERNAME',
            message: 'already exists!',
            position: 'topRight'
          });
        }else if (data == 'valid'){
          $.ajax({
            url: "../queries/insert_div.php",
            type: "POST",
            data: form,
            contentType: false,
            cache: false,
            processData: false,
            success: function (data) {
              if (data == "invalid") {
                alert("Invalid!!");
              }
            },
            error: function () {
              Swal.fire("ERROR", "Something Went Wrong!!", "error");
            }
          });
          Swal.fire({
            title: "Successfully Registered!",
            html: "you want to proceed to login?",
            icon: "success",
            showCancelButton: true,
            confirmButtonText: "Ok, Sure",
            cancelButtonText: "No, Cancel",
            closeOnConfirm: false,
          }).then((result) => {
            if (result.isConfirmed) {
              window.location.href = "auth-login.php";
            } else {
              location.reload();
            }
          });
        }
      },
      error: function () {
        Swal.fire("ERROR", "Something Went Wrong!!", "error");
      }
    });


  });

});