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


$(document).ready(function (){

    var list = $("#list_sel option:selected").val();

    if (list == '0'){
        $.post("modal/loader.php", function(load){
            $("#exam_list").html(load);
            $.post("fragments/list_exam_table.php", function (list){
                $("#exam_list").html(list);
            });
        });
        $("#exam_active").show();
        $("#deleted_exam").hide();

        $("#active_button_container").show();
        $("#trash_button_container").hide();

        $("#status_sel").change( function (){
            var selected_div = $("#diviselect option:selected").val();
            var selected_status = $("#status_sel option:selected").val();
            if (selected_div != '' && selected_status != ''){
                $.post("modal/loader.php", function (load) {
                    $("#exam_list").html(load);
                    $.post("fragments/list_exam_table.php", { division:selected_div, status:selected_status}, function (user) {
                        $("#exam_list").html(user);
                    });
                });
            }else if (selected_status != ''){
                $.post("modal/loader.php", function (load) {
                    $("#exam_list").html(load);
                    $.post("fragments/list_exam_table.php", { status:selected_status}, function (user) {
                        $("#exam_list").html(user);
                    });
                });
            }else if (selected_div != ''){
                $.post("modal/loader.php", function (load) {
                    $("#exam_list").html(load);
                    $.post("fragments/list_exam_table.php", { division:selected_div}, function (user) {
                        $("#exam_list").html(user);
                    });
                });
            }else {
                $.post("modal/loader.php", function (load) {
                    $("#exam_list").html(load);
                    $.post("fragments/list_exam_table.php", function (user) {
                        $("#exam_list").html(user);
                    });
                });
            }
        });

        $("#diviselect").change( function (){
            var selected_div = $("#diviselect option:selected").val();
            var selected_status = $("#status_sel option:selected").val();
            if (selected_div != '' && selected_status != ''){
                $.post("modal/loader.php", function (load) {
                    $("#exam_list").html(load);
                    $.post("fragments/list_exam_table.php", { division:selected_div, status:selected_status}, function (user) {
                        $("#exam_list").html(user);
                    });
                });
            }else if (selected_status != ''){
                $.post("modal/loader.php", function (load) {
                    $("#exam_list").html(load);
                    $.post("fragments/list_exam_table.php", { status:selected_status}, function (user) {
                        $("#exam_list").html(user);
                    });
                });
            }else if (selected_div != ''){
                $.post("modal/loader.php", function (load) {
                    $("#exam_list").html(load);
                    $.post("fragments/list_exam_table.php", { division:selected_div}, function (user) {
                        $("#exam_list").html(user);
                    });
                });
            }else {
                $.post("modal/loader.php", function (load) {
                    $("#exam_list").html(load);
                    $.post("fragments/list_exam_table.php", function (user) {
                        $("#exam_list").html(user);
                    });
                });
            }
        });

    }

    $("#list_sel").on("change", function (){
        var list = $("#list_sel option:selected").val();
        var empty = "";
        $("#status_sel").val(empty);
        $("#diviselect").val(empty);
        if (list == '0' ){
            $.post("modal/loader.php", function(load){
                $("#exam_list").html(load);
                $.post("fragments/list_exam_table.php", function (list){
                    $("#exam_list").html(list);
                });
            });
            $("#exam_active").show();
            $("#deleted_exam").hide();

            $("#active_button_container").show();
            $("#trash_button_container").hide();

            $("#status_sel").change( function (){
                var selected_div = $("#diviselect option:selected").val();
                var selected_status = $("#status_sel option:selected").val();
                if (selected_div != '' && selected_status != ''){
                    $.post("modal/loader.php", function (load) {
                        $("#exam_list").html(load);
                        $.post("fragments/list_exam_table.php", { division:selected_div, status:selected_status}, function (user) {
                            $("#exam_list").html(user);
                        });
                    });
                }else if (selected_status != ''){
                    $.post("modal/loader.php", function (load) {
                        $("#exam_list").html(load);
                        $.post("fragments/list_exam_table.php", { status:selected_status}, function (user) {
                            $("#exam_list").html(user);
                        });
                    });
                }else if (selected_div != ''){
                    $.post("modal/loader.php", function (load) {
                        $("#exam_list").html(load);
                        $.post("fragments/list_exam_table.php", { division:selected_div}, function (user) {
                            $("#exam_list").html(user);
                        });
                    });
                }else {
                    $.post("modal/loader.php", function (load) {
                        $("#exam_list").html(load);
                        $.post("fragments/list_exam_table.php", function (user) {
                            $("#exam_list").html(user);
                        });
                    });
                }
            });

            $("#diviselect").change( function (){
                var selected_div = $("#diviselect option:selected").val();
                var selected_status = $("#status_sel option:selected").val();
                if (selected_div != '' && selected_status != ''){
                    $.post("modal/loader.php", function (load) {
                        $("#exam_list").html(load);
                        $.post("fragments/list_exam_table.php", { division:selected_div, status:selected_status}, function (user) {
                            $("#exam_list").html(user);
                        });
                    });
                }else if (selected_status != ''){
                    $.post("modal/loader.php", function (load) {
                        $("#exam_list").html(load);
                        $.post("fragments/list_exam_table.php", { status:selected_status}, function (user) {
                            $("#exam_list").html(user);
                        });
                    });
                }else if (selected_div != ''){
                    $.post("modal/loader.php", function (load) {
                        $("#exam_list").html(load);
                        $.post("fragments/list_exam_table.php", { division:selected_div}, function (user) {
                            $("#exam_list").html(user);
                        });
                    });
                }else {
                    $.post("modal/loader.php", function (load) {
                        $("#exam_list").html(load);
                        $.post("fragments/list_exam_table.php", function (user) {
                            $("#exam_list").html(user);
                        });
                    });
                }
            });

        }
        else if (list == '1'){

            $.post("modal/loader.php", function(load){
                $("#exam_list_deleted").html(load);
                $.post("fragments/list_recently_deleted_exam.php", function (list){
                    $("#exam_list_deleted").html(list);
                });
            });
            $("#exam_active").hide();
            $("#deleted_exam").show();

            $("#trash_button_container").show();
            $("#active_button_container").hide();

            $("#status_sel").change( function (){
                var selected_div = $("#diviselect option:selected").val();
                var selected_status = $("#status_sel option:selected").val();
                if (selected_div != '' && selected_status != ''){
                    $.post("modal/loader.php", function (load) {
                        $("#exam_list_deleted").html(load);
                        $.post("fragments/list_recently_deleted_exam.php", { division:selected_div, status:selected_status}, function (user) {
                            $("#exam_list_deleted").html(user);
                        });
                    });
                }else if (selected_status != ''){
                    $.post("modal/loader.php", function (load) {
                        $("#exam_list_deleted").html(load);
                        $.post("fragments/list_recently_deleted_exam.php", { status:selected_status}, function (user) {
                            $("#exam_list_deleted").html(user);
                        });
                    });
                }else if (selected_div != ''){
                    $.post("modal/loader.php", function (load) {
                        $("#exam_list_deleted").html(load);
                        $.post("fragments/list_recently_deleted_exam.php", { division:selected_div}, function (user) {
                            $("#exam_list_deleted").html(user);
                        });
                    });
                }else {
                    $.post("modal/loader.php", function (load) {
                        $("#exam_list_deleted").html(load);
                        $.post("fragments/list_recently_deleted_exam.php", function (user) {
                            $("#exam_list_deleted").html(user);
                        });
                    });
                }
            });

            $("#diviselect").change( function (){
                var selected_div = $("#diviselect option:selected").val();
                var selected_status = $("#status_sel option:selected").val();
                if (selected_div != '' && selected_status != ''){
                    $.post("modal/loader.php", function (load) {
                        $("#exam_list_deleted").html(load);
                        $.post("fragments/list_recently_deleted_exam.php", { division:selected_div, status:selected_status}, function (user) {
                            $("#exam_list_deleted").html(user);
                        });
                    });
                }else if (selected_status != ''){
                    $.post("modal/loader.php", function (load) {
                        $("#exam_list_deleted").html(load);
                        $.post("fragments/list_recently_deleted_exam.php", { status:selected_status}, function (user) {
                            $("#exam_list_deleted").html(user);
                        });
                    });
                }else if (selected_div != ''){
                    $.post("modal/loader.php", function (load) {
                        $("#exam_list_deleted").html(load);
                        $.post("fragments/list_recently_deleted_exam.php", { division:selected_div}, function (user) {
                            $("#exam_list_deleted").html(user);
                        });
                    });
                }else {
                    $.post("modal/loader.php", function (load) {
                        $("#exam_list_deleted").html(load);
                        $.post("fragments/list_recently_deleted_exam.php", function (user) {
                            $("#exam_list_deleted").html(user);
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

});










