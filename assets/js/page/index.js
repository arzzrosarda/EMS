"use strict";



var credentials = setInterval(function () {
  $.post("queries/log_credentials.php", function (res) {
    if (res == 1) {
      clearInterval(credentials);
      Swal.fire({
        title: "YOU HAVE BEEN LOGGED OUT",
        html: "Press continue to proceed",
        icon: "warning",
        allowOutsideClick: false,
        confirmButtonColor: "#1c3d77",
        confirmButtonText: "Continue",
        closeOnConfirm: false,
      }).then((result) => {
        if (result.isConfirmed) {
          window.location.href = "queries/logout.php";
        }
      });
    } else if (res == 3) {
      clearInterval(credentials);
      Swal.fire({
        title: "YOU HAVE BEEN LOGGED OUT",
        html: "Press continue to proceed",
        icon: "warning",
        confirmButtonColor: "#1c3d77",
        allowOutsideClick: false,
        confirmButtonText: "Continue",
        closeOnConfirm: false,
      }).then((result) => {
        if (result.isConfirmed) {
          $.redirect("auth/auth-login.php");
        }
      });
    }
  });
}, 2000);

$.post("modal/loader.php", function (load) {
  $("#list_available_exam").html(load);
  $.post("queries/fragments/list_exam.php", function (exam) {
    $("#list_available_exam").html(exam);
  });
});

//Change Password//
$("#changepw1").on("click", function () {
  $("#changepwModal").modal('show');
});

$("#logoutbtn").click(function () {
  Swal.fire({
    title: "LOGOUT",
    html: "Are you sure you want to logout?",
    icon: "warning",
    showCancelButton: true,
    confirmButtonColor: "#1c3d77",
    confirmButtonText: "Yes, I am sure",
    cancelButtonText: "No, Stay logged in",
    closeOnConfirm: false,
    closeOnCancel: true
  }).then((result) => {
    if (result.isConfirmed) {
      let timerInterval;
      Swal.fire({
        title: "LOGGING OUT....",
        html: "",
        timer: 1000,
        icon: "warning",
        confirmButtonColor: "#1c3d77",
        showConfirmButton: false,
        timerProgressBar: true,
        allowOutsideClick: false,
        didOpen: () => {
          Swal.showLoading();
          const timer = Swal.getPopup().querySelector("b");
          timerInterval = setInterval(() => {
            timer.textContent = `${Swal.getTimerLeft()}`;
          }, 100);
        },
        willClose: () => {
          clearInterval(timerInterval);
        }
      }).then((result) => {
        if (result.dismiss === Swal.DismissReason.timer) {
          window.location.href = "queries/logout.php";
        }
      });

    }
  });
});

$('input[name="icon-input"]').change(function () {
  if ($(this).is(":checked")) {
    var checked = $(this).val();
    $("#input-rating").val(checked);
  }
});
$(".btnFeedback").click(function () {
  var user_id = $(this).data('user');
  var txt_feedback = $("#txt_feedback").val();
  var rate = $("#input-rating").val();
  if (txt_feedback == '') {
    $("#txt_feedback").focus();
    iziToast.info({
      title: 'MESSAGE',
      message: 'IS EMPTY!!',
      position: 'bottomCenter'
    });
  } else if (rate == '') {
    iziToast.info({
      title: 'RATE',
      message: 'you must choose your rating, thank you for understanding.',
      position: 'bottomCenter'
    });
  } else {
    $.post("queries/feedback.php", {user_id: user_id, message: txt_feedback, rate: rate}, function (feedback) {
      if (feedback == 1) {
        $("#txt_feedback").val("");
        $('input[name="icon-input"]').prop("checked", false);
        iziToast.success({
          title: 'FEEDBACK',
          message: 'Thank you for your feedback',
          position: 'topCenter'
        });
      } else {
        iziToast.error({
          title: 'FEEDBACK',
          message: 'Something went wrong!!',
          position: 'bottomCenter'
        });
      }
    })
  }
});