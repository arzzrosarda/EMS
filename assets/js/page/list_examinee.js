"use strict";

$(document).ready(function (){

    var list = $("#list_sel option:selected").val();

    if (list == '0'){
        $.post("modal/loader.php", function (load){
            $("#list-of-examinee").html(load);
            $.post("fragments/list_examinee_table.php", function (list){
                $("#list-of-examinee").html(list);
            });
        });
        $("#active_examinee_list").show();
        $("#deleted_examinee_list").hide();

        $("#examinee_list").show();
        $("#deleted_examinee").hide();

        $("#diviselect").change( function (){
            var selected_div = $("#diviselect option:selected").val();
            if (selected_div != ''){
                $.post("modal/loader.php", function (load) {
                    $("#list-of-examinee").html(load);
                    $.post("fragments/list_examinee_table.php", {division:selected_div}, function (user) {
                        $("#list-of-examinee").html(user);
                    });
                });
            }else {
                $.post("modal/loader.php", function (load) {
                    $("#list-of-examinee").html(load);
                    $.post("fragments/list_examinee_table.php", function (user) {
                        $("#list-of-examinee").html(user);
                    });
                });
            }
        });

    }

    $("#list_sel").on("change", function (){
        var list = $("#list_sel option:selected").val();
        var empty = "";
        $("#diviselect").val(empty);
        if (list == '0' ){
            $.post("modal/loader.php", function (load){
                $("#list-of-examinee").html(load);
                $.post("fragments/list_examinee_table.php", function (list){
                    $("#list-of-examinee").html(list);
                });
            });

            $("#active_examinee_list").show();
            $("#deleted_examinee_list").hide();

            $("#examinee_list").show();
            $("#deleted_examinee").hide();

            $("#diviselect").change( function (){
                var selected_div = $("#diviselect option:selected").val();
                if (selected_div != ''){
                    $.post("modal/loader.php", function (load) {
                        $("#list-of-examinee").html(load);
                        $.post("fragments/list_examinee_table.php", {division:selected_div}, function (user) {
                            $("#list-of-examinee").html(user);
                        });
                    });
                }else {
                    $.post("modal/loader.php", function (load) {
                        $("#list-of-examinee").html(load);
                        $.post("fragments/list_examinee_table.php", function (user) {
                            $("#list-of-examinee").html(user);
                        });
                    });
                }
            });

        }else if (list == '1'){
            $.post("modal/loader.php", function (load){
                $("#examinee_list_deleted").html(load);
                $.post("fragments/list_examinee_table_deleted.php", function (list){
                    $("#examinee_list_deleted").html(list);
                });
            });

            $("#active_examinee_list").hide();
            $("#deleted_examinee_list").show();

            $("#examinee_list").hide();
            $("#deleted_examinee").show();

            $("#diviselect").change( function (){
                var selected_div = $("#diviselect option:selected").val();
                if (selected_div != ''){
                    $.post("modal/loader.php", function (load) {
                        $("#examinee_list_deleted").html(load);
                        $.post("fragments/list_examinee_table_deleted.php", {division:selected_div}, function (user) {
                            $("#examinee_list_deleted").html(user);
                        });
                    });
                }else {
                    $.post("modal/loader.php", function (load) {
                        $("#examinee_list_deleted").html(load);
                        $.post("fragments/list_examinee_table_deleted.php", function (user) {
                            $("#examinee_list_deleted").html(user);
                        });
                    });
                }
            });
        }
    });

var credentials = setInterval(function () {
    $.post("../queries/log_credentials.php", function (res){
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
                    window.location.href="../queries/logout.php";
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
                    $.redirect("../auth/auth-login.php");
                }
            });
        }
    });
}, 2000);

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
            window.location.href="../queries/logout.php";
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
            window.location.href="../queries/logout.php";
          }
        });

      }
  });
});

});




