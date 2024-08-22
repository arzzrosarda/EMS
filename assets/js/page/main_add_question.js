"use strict";
//Change Password//
$("#changepw1").on("click", function(){
  $("#changepwModal").modal('show');
});
$("#changepw").on("click", function(){
  $("#changepwModal").modal('show');
});

$("#logoutbtn").click( function(){
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
            window.location.href="../../queries/logout.php";
          }
        });

      }
  });
});
$("#logoutbtn1").click( function(){
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
            window.location.href="../../queries/logout.php";
          }
        });
      }
  });
});


$(document).ready(function (){

    var credentials = setInterval(function () {
        $.post("../../queries/log_credentials.php", function (res){
            if (res == 1){
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
                        window.location.href="../../queries/logout.php";
                    }
                });
            }else if (res == 3){
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
                        $.redirect("../../auth/auth-login.php");
                    }
                });
            }
        });
    }, 2000);

    $('.db-list').on('focus', function () {
        var ddl = $(this);
        ddl.data('previous', ddl.val());
    }).on('change', function () {
        var ddl = $(this);
        var previous = ddl.data('previous');
        ddl.data('previous', ddl.val());

        if (previous) {
            $('#fields-list').find('select option[value='+previous+']').css({"display":"block"});
        }
        $('#fields-list').find('select option[value='+$(this).val()+']:not(:selected)').css({"display":"none"});
    });

});







