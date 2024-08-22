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

    var list = $("#list_sel option:selected").val();

    if (list == '0'){
        $.post("fragments/disabled_division.php", function (div){
            $("#divselect").html(div);
        });

        $.post("modal/loader.php", function(load){
            $("#exam_list").html(load);
            $.post("fragments/list_exam_table.php", function (list){
                $("#exam_list").html(list);
            });
        });

        $("#active_exam_container").show();
        $("#deleted_exam_container").hide();

        $("#exam_active_list").show();
        $("#exam_deleted_list").hide();

        $("#depselect").change( function (){
            var selected_dep = $("#depselect option:selected").val();
            var selected_div = $("#divselect option:selected").val();
            var selected_status = $("#status_sel option:selected").val();
            if (selected_status != '' && selected_dep != ''){
                $("#divselect").prop("disabled", false);
                $.post("fragments/division_select.php", {department:selected_dep}, function(div){
                    $("#divselect").html(div);
                });
                $.post("modal/loader.php", function (load) {
                    $("#exam_list").html(load);
                    $.post("fragments/list_exam_table.php", {department:selected_dep, status:selected_status}, function (user) {
                        $("#exam_list").html(user);
                    });
                });
            }else if (selected_status != '' && selected_dep != '' && selected_div != ''){
                $("#divselect").prop("disabled", false);
                $.post("fragments/division_select.php", {department:selected_dep}, function(div){
                    $("#divselect").html(div);
                });
                $.post("modal/loader.php", function (load) {
                    $("#exam_list").html(load);
                    $.post("fragments/list_exam_table.php", {department:selected_dep, division:selected_div, status:selected_status}, function (user) {
                        $("#exam_list").html(user);
                    });
                });
            }else if (selected_dep != ''){
                $("#divselect").prop("disabled", false);
                $.post("fragments/division_select.php", {department:selected_dep}, function(div){
                    $("#divselect").html(div);
                });
                $.post("modal/loader.php", function (load) {
                    $("#exam_list").html(load);
                    $.post("fragments/list_exam_table.php", {department:selected_dep}, function (user) {
                        $("#exam_list").html(user);
                    });
                });
            }else if (selected_status != ''){
                $("#divselect").prop("disabled", true);
                $.post("fragments/disabled_division.php", function (div){
                    $("#divselect").html(div);
                });
                $.post("modal/loader.php", function (load) {
                    $("#exam_list").html(load);
                    $.post("fragments/list_exam_table.php", {status:selected_status}, function (user) {
                        $("#exam_list").html(user);
                    });
                });
            }else {
                $("#divselect").prop("disabled", true);
                $.post("fragments/disabled_division.php", function (div){
                    $("#divselect").html(div);
                });
                $.post("modal/loader.php", function (load) {
                    $("#exam_list").html(load);
                    $.post("fragments/list_exam_table.php", function (user) {
                        $("#exam_list").html(user);
                    });
                });
            }
        });

        $("#divselect").change( function (){
            var selected_div = $("#divselect option:selected").val();
            var selected_dep = $("#depselect option:selected").val();
            var selected_status = $("#status_sel option:selected").val();
            if (selected_dep != '' && selected_div != '' && selected_status != ''){
                $.post("modal/loader.php", function (load) {
                    $("#exam_list").html(load);
                    $.post("fragments/list_exam_table.php", {department:selected_dep, division:selected_div, status:selected_status}, function (user) {
                        $("#exam_list").html(user);
                    });
                });

            }else if (selected_dep != '' && selected_div != ''){
                $.post("modal/loader.php", function (load) {
                    $("#exam_list").html(load);
                    $.post("fragments/list_exam_table.php", {department:selected_dep, division:selected_div}, function (user) {
                        $("#exam_list").html(user);
                    });
                });
            }else if (selected_dep != '' && selected_status != ''){
                $.post("modal/loader.php", function (load) {
                    $("#exam_list").html(load);
                    $.post("fragments/list_exam_table.php", {department:selected_dep, status:selected_status}, function (user) {
                        $("#exam_list").html(user);
                    });
                });
            }
            else if (selected_status != ''){
                $.post("modal/loader.php", function (load) {
                    $("#exam_list").html(load);
                    $.post("fragments/list_exam_table.php", {status:selected_status}, function (user) {
                        $("#exam_list").html(user);
                    });
                });
            }else if (selected_dep != ''){
                $.post("modal/loader.php", function (load) {
                    $("#exam_list").html(load);
                    $.post("fragments/list_exam_table.php", {department:selected_dep}, function (user) {
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

        $("#status_sel").change( function (){
            var selected_div = $("#divselect option:selected").val();
            var selected_dep = $("#depselect option:selected").val();
            var selected_status = $("#status_sel option:selected").val();
            if (selected_dep != '' && selected_div != '' && selected_status != ''){
                $.post("modal/loader.php", function (load) {
                    $("#exam_list").html(load);
                    $.post("fragments/list_exam_table.php", { department:selected_dep, division:selected_div, status:selected_status}, function (user) {
                        $("#exam_list").html(user);
                    });
                });
            }else if (selected_status != '' && selected_dep != ''){
                $.post("modal/loader.php", function (load) {
                    $("#exam_list").html(load);
                    $.post("fragments/list_exam_table.php", { department:selected_dep, status:selected_status}, function (user) {
                        $("#exam_list").html(user);
                    });
                });
            }else if (selected_dep != '' && selected_div != ''){
                $.post("modal/loader.php", function (load) {
                    $("#exam_list").html(load);
                    $.post("fragments/list_exam_table.php", {department:selected_dep, division:selected_div}, function (user) {
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
            }else if (selected_dep != ''){
                $.post("modal/loader.php", function (load) {
                    $("#exam_list").html(load);
                    $.post("fragments/list_exam_table.php", {department:selected_dep}, function (user) {
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
        $("#depselect").val(empty);
        $.post("fragments/disabled_division.php", function (div){
            $("#divselect").html(div);
        });

        if (list == '0' ){
            $.post("modal/loader.php", function(load){
                $("#exam_list").html(load);
                $.post("fragments/list_exam_table.php", function (list){
                    $("#exam_list").html(list);
                });
            });
            $("#active_exam_container").show();
            $("#deleted_exam_container").hide();
            $("#exam_active_list").show();
            $("#exam_deleted_list").hide();

            $("#status_sel").change( function (){
                var selected_div = $("#divselect option:selected").val();
                var selected_dep = $("#depselect option:selected").val();
                var selected_status = $("#status_sel option:selected").val();
                if (selected_dep != '' && selected_div != '' && selected_status != ''){
                    $.post("modal/loader.php", function (load) {
                        $("#exam_list").html(load);
                        $.post("fragments/list_exam_table.php", { department:selected_dep, division:selected_div, status:selected_status}, function (user) {
                            $("#exam_list").html(user);
                        });
                    });
                }else if (selected_status != '' && selected_dep != ''){
                    $.post("modal/loader.php", function (load) {
                        $("#exam_list").html(load);
                        $.post("fragments/list_exam_table.php", { department:selected_dep, status:selected_status}, function (user) {
                            $("#exam_list").html(user);
                        });
                    });
                }else if (selected_dep != '' && selected_div != ''){
                    $.post("modal/loader.php", function (load) {
                        $("#exam_list").html(load);
                        $.post("fragments/list_exam_table.php", {department:selected_dep, division:selected_div}, function (user) {
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
                }else if (selected_dep != ''){
                    $.post("modal/loader.php", function (load) {
                        $("#exam_list").html(load);
                        $.post("fragments/list_exam_table.php", {department:selected_dep}, function (user) {
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

            $("#divselect").change( function (){
                var selected_div = $("#divselect option:selected").val();
                var selected_dep = $("#depselect option:selected").val();
                var selected_status = $("#status_sel option:selected").val();
                if (selected_dep != '' && selected_div != '' && selected_status != ''){
                    $.post("modal/loader.php", function (load) {
                        $("#exam_list").html(load);
                        $.post("fragments/list_exam_table.php", {department:selected_dep, division:selected_div, status:selected_status}, function (user) {
                            $("#exam_list").html(user);
                        });
                    });

                }else if (selected_dep != '' && selected_div != ''){
                    $.post("modal/loader.php", function (load) {
                        $("#exam_list").html(load);
                        $.post("fragments/list_exam_table.php", {department:selected_dep, division:selected_div}, function (user) {
                            $("#exam_list").html(user);
                        });
                    });
                }else if (selected_dep != '' && selected_status != ''){
                    $.post("modal/loader.php", function (load) {
                        $("#exam_list").html(load);
                        $.post("fragments/list_exam_table.php", {department:selected_dep, status:selected_status}, function (user) {
                            $("#exam_list").html(user);
                        });
                    });
                }
                else if (selected_status != ''){
                    $.post("modal/loader.php", function (load) {
                        $("#exam_list").html(load);
                        $.post("fragments/list_exam_table.php", {status:selected_status}, function (user) {
                            $("#exam_list").html(user);
                        });
                    });
                }else if (selected_dep != ''){
                    $.post("modal/loader.php", function (load) {
                        $("#exam_list").html(load);
                        $.post("fragments/list_exam_table.php", {department:selected_dep}, function (user) {
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

            $("#depselect").change( function (){
                var selected_dep = $("#depselect option:selected").val();
                var selected_div = $("#divselect option:selected").val();
                var selected_status = $("#status_sel option:selected").val();
                if (selected_status != '' && selected_dep != ''){
                    $("#divselect").prop("disabled", false);
                    $.post("fragments/division_select.php", {department:selected_dep}, function(div){
                        $("#divselect").html(div);
                    });
                    $.post("modal/loader.php", function (load) {
                        $("#exam_list").html(load);
                        $.post("fragments/list_exam_table.php", {department:selected_dep, status:selected_status}, function (user) {
                            $("#exam_list").html(user);
                        });
                    });
                }else if (selected_status != '' && selected_dep != '' && selected_div != ''){
                    $("#divselect").prop("disabled", false);
                    $.post("fragments/division_select.php", {department:selected_dep}, function(div){
                        $("#divselect").html(div);
                    });
                    $.post("modal/loader.php", function (load) {
                        $("#exam_list").html(load);
                        $.post("fragments/list_exam_table.php", {department:selected_dep, division:selected_div, status:selected_status}, function (user) {
                            $("#exam_list").html(user);
                        });
                    });
                }else if (selected_dep != ''){
                    $("#divselect").prop("disabled", false);
                    $.post("fragments/division_select.php", {department:selected_dep}, function(div){
                        $("#divselect").html(div);
                    });
                    $.post("modal/loader.php", function (load) {
                        $("#exam_list").html(load);
                        $.post("fragments/list_exam_table.php", {department:selected_dep}, function (user) {
                            $("#exam_list").html(user);
                        });
                    });
                }else if (selected_status != ''){
                    $("#divselect").prop("disabled", true);
                    $.post("fragments/disabled_division.php", function (div){
                        $("#divselect").html(div);
                    });
                    $.post("modal/loader.php", function (load) {
                        $("#exam_list").html(load);
                        $.post("fragments/list_exam_table.php", {status:selected_status}, function (user) {
                            $("#exam_list").html(user);
                        });
                    });
                }else {
                    $("#divselect").prop("disabled", true);
                    $.post("fragments/disabled_division.php", function (div){
                        $("#divselect").html(div);
                    });
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
                $("#exam_deleted").html(load);
                $.post("fragments/list_recently_deleted_exam.php", function (list){
                    $("#exam_deleted").html(list);
                });
            });

            $("#active_exam_container").hide();
            $("#deleted_exam_container").show();

            $("#exam_active_list").hide();
            $("#exam_deleted_list").show();

            $("#status_sel").change( function (){
                var selected_div = $("#divselect option:selected").val();
                var selected_dep = $("#depselect option:selected").val();
                var selected_status = $("#status_sel option:selected").val();
                if (selected_dep != '' && selected_div != '' && selected_status != ''){
                    $.post("modal/loader.php", function (load) {
                        $("#exam_deleted").html(load);
                        $.post("fragments/list_recently_deleted_exam.php", { department:selected_dep, division:selected_div, status:selected_status}, function (user) {
                            $("#exam_deleted").html(user);
                        });
                    });
                }else if (selected_status != '' && selected_dep != ''){
                    $.post("modal/loader.php", function (load) {
                        $("#exam_deleted").html(load);
                        $.post("fragments/list_recently_deleted_exam.php", { department:selected_dep, status:selected_status}, function (user) {
                            $("#exam_deleted").html(user);
                        });
                    });
                }else if (selected_dep != '' && selected_div != ''){
                    $.post("modal/loader.php", function (load) {
                        $("#exam_deleted").html(load);
                        $.post("fragments/list_recently_deleted_exam.php", {department:selected_dep, division:selected_div}, function (user) {
                            $("#exam_deleted").html(user);
                        });
                    });
                }else if (selected_status != ''){
                    $.post("modal/loader.php", function (load) {
                        $("#exam_deleted").html(load);
                        $.post("fragments/list_recently_deleted_exam.php", { status:selected_status}, function (user) {
                            $("#exam_deleted").html(user);
                        });
                    });
                }else if (selected_dep != ''){
                    $.post("modal/loader.php", function (load) {
                        $("#exam_deleted").html(load);
                        $.post("fragments/list_recently_deleted_exam.php", {department:selected_dep}, function (user) {
                            $("#exam_deleted").html(user);
                        });
                    });
                }else{
                    $.post("modal/loader.php", function (load) {
                        $("#exam_deleted").html(load);
                        $.post("fragments/list_recently_deleted_exam.php", function (user) {
                            $("#exam_deleted").html(user);
                        });
                    });
                }
            });

            $("#divselect").change( function (){
                var selected_div = $("#divselect option:selected").val();
                var selected_dep = $("#depselect option:selected").val();
                var selected_status = $("#status_sel option:selected").val();
                if (selected_dep != '' && selected_div != '' && selected_status != ''){
                    $.post("modal/loader.php", function (load) {
                        $("#exam_deleted").html(load);
                        $.post("fragments/list_recently_deleted_exam.php", {department:selected_dep, division:selected_div, status:selected_status}, function (user) {
                            $("#exam_deleted").html(user);
                        });
                    });

                }else if (selected_dep != '' && selected_div != ''){
                    $.post("modal/loader.php", function (load) {
                        $("#exam_deleted").html(load);
                        $.post("fragments/list_recently_deleted_exam.php", {department:selected_dep, division:selected_div}, function (user) {
                            $("#exam_deleted").html(user);
                        });
                    });
                }else if (selected_dep != '' && selected_status != ''){
                    $.post("modal/loader.php", function (load) {
                        $("#exam_deleted").html(load);
                        $.post("fragments/list_recently_deleted_exam.php", {department:selected_dep, status:selected_status}, function (user) {
                            $("#exam_deleted").html(user);
                        });
                    });
                }
                else if (selected_status != ''){
                    $.post("modal/loader.php", function (load) {
                        $("#exam_deleted").html(load);
                        $.post("fragments/list_recently_deleted_exam.php", {status:selected_status}, function (user) {
                            $("#exam_deleted").html(user);
                        });
                    });
                }else if (selected_dep != ''){
                    $.post("modal/loader.php", function (load) {
                        $("#exam_deleted").html(load);
                        $.post("fragments/list_recently_deleted_exam.php", {department:selected_dep}, function (user) {
                            $("#exam_deleted").html(user);
                        });
                    });
                }else {
                    $.post("modal/loader.php", function (load) {
                        $("#exam_deleted").html(load);
                        $.post("fragments/list_recently_deleted_exam.php", function (user) {
                            $("#exam_deleted").html(user);
                        });
                    });
                }
            });

            $("#depselect").change( function (){
                var selected_dep = $("#depselect option:selected").val();
                var selected_div = $("#divselect option:selected").val();
                var selected_status = $("#status_sel option:selected").val();
                if (selected_status != '' && selected_dep != ''){
                    $("#divselect").prop("disabled", false);
                    $.post("fragments/division_select.php", {department:selected_dep}, function(div){
                        $("#divselect").html(div);
                    });
                    $.post("modal/loader.php", function (load) {
                        $("#exam_deleted").html(load);
                        $.post("fragments/list_recently_deleted_exam.php", {department:selected_dep, status:selected_status}, function (user) {
                            $("#exam_deleted").html(user);
                        });
                    });
                }else if (selected_status != '' && selected_dep != '' && selected_div != ''){
                    $("#divselect").prop("disabled", false);
                    $.post("fragments/division_select.php", {department:selected_dep}, function(div){
                        $("#divselect").html(div);
                    });
                    $.post("modal/loader.php", function (load) {
                        $("#exam_deleted").html(load);
                        $.post("fragments/list_recently_deleted_exam.php", {department:selected_dep, division:selected_div, status:selected_status}, function (user) {
                            $("#exam_deleted").html(user);
                        });
                    });
                }else if (selected_dep != ''){
                    $("#divselect").prop("disabled", false);
                    $.post("fragments/division_select.php", {department:selected_dep}, function(div){
                        $("#divselect").html(div);
                    });
                    $.post("modal/loader.php", function (load) {
                        $("#exam_deleted").html(load);
                        $.post("fragments/list_recently_deleted_exam.php", {department:selected_dep}, function (user) {
                            $("#exam_deleted").html(user);
                        });
                    });
                }else if (selected_status != ''){
                    $("#divselect").prop("disabled", true);
                    $.post("fragments/disabled_division.php", function (div){
                        $("#divselect").html(div);
                    });
                    $.post("modal/loader.php", function (load) {
                        $("#exam_deleted").html(load);
                        $.post("fragments/list_recently_deleted_exam.php", {status:selected_status}, function (user) {
                            $("#exam_deleted").html(user);
                        });
                    });
                }else {
                    $("#divselect").prop("disabled", true);
                    $.post("fragments/disabled_division.php", function (div){
                        $("#divselect").html(div);
                    });
                    $.post("modal/loader.php", function (load) {
                        $("#exam_deleted").html(load);
                        $.post("fragments/list_recently_deleted_exam.php", function (user) {
                            $("#exam_deleted").html(user);
                        });
                    });
                }
            });
        }

    });


});










