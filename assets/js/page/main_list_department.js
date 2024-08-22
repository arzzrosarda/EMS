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

    $(".btnAddDepartment").click(function (){
        $("#addDepartment").modal("show");
    });

    var list = $("#list_sel option:selected").val();

    if (list == '0'){
        $.post("modal/loader.php", function(load){
            $("#department_list").html(load);
            $.post("fragments/list_department_table.php", function (list){
                $("#department_list").html(list);
            });
        });
        $("#department_active").show();
        $("#department_deleted").hide();
        $("#active_button_container").show();
        $("#trash_button_container").hide();

        $("#AccSel").change( function (){
            var select_account = $("#AccSel option:selected").val();
            var selected_div = $("#WDivSel option:selected").val();
            if (select_account != '' && selected_div != ''){
                $.post("modal/loader.php", function (load) {
                    $("#department_list").html(load);
                    $.post("fragments/list_department_table.php", {account:select_account, div:selected_div}, function (list) {
                        $("#department_list").html(list);
                    });
                });
            }else if (select_account != ''){
                $.post("modal/loader.php", function (load) {
                    $("#department_list").html(load);
                    $.post("fragments/list_department_table.php", {account:select_account}, function (list) {
                        $("#department_list").html(list);
                    });
                });
            }else if (selected_div != ''){
                $.post("modal/loader.php", function (load) {
                    $("#department_list").html(load);
                    $.post("fragments/list_department_table.php", {div:selected_div}, function (list) {
                        $("#department_list").html(list);
                    });
                });
            }else {
                $.post("modal/loader.php", function(load){
                    $("#department_list").html(load);
                    $.post("fragments/list_department_table.php", function (list){
                        $("#department_list").html(list);
                    });
                });
            }
        });

        $("#WDivSel").change( function (){
            var select_account = $("#AccSel option:selected").val();
            var selected_div = $("#WDivSel option:selected").val();
            if (select_account != '' && selected_div != ''){
                $.post("modal/loader.php", function (load) {
                    $("#department_list").html(load);
                    $.post("fragments/list_department_table.php", {account:select_account, div:selected_div}, function (list) {
                        $("#department_list").html(list);
                    });
                });
            }else if (select_account != ''){
                $.post("modal/loader.php", function (load) {
                    $("#department_list").html(load);
                    $.post("fragments/list_department_table.php", {account:select_account}, function (list) {
                        $("#department_list").html(list);
                    });
                });
            }else if (selected_div != ''){
                $.post("modal/loader.php", function (load) {
                    $("#department_list").html(load);
                    $.post("fragments/list_department_table.php", {div:selected_div}, function (list) {
                        $("#department_list").html(list);
                    });
                });
            }else {
                $.post("modal/loader.php", function(load){
                    $("#department_list").html(load);
                    $.post("fragments/list_department_table.php", function (list){
                        $("#department_list").html(list);
                    });
                });
            }
        });
    }

    $("#list_sel").on("change", function (){
        var list = $("#list_sel option:selected").val();
        var empty = "";
        $("#AccSel").val(empty);
        $("#WDivSel").val(empty);
        if (list == '0' ){
                $.post("modal/loader.php", function(load){
                    $("#department_list").html(load);
                    $.post("fragments/list_department_table.php", function (list){
                        $("#department_list").html(list);
                    });
                });
                $("#department_active").show();
                $("#department_deleted").hide();
                $("#active_button_container").show();
                $("#trash_button_container").hide();

                $("#AccSel").change( function (){
                    var select_account = $("#AccSel option:selected").val();
                    var selected_div = $("#WDivSel option:selected").val();
                    if (select_account != '' && selected_div != ''){
                        $.post("modal/loader.php", function (load) {
                            $("#department_list").html(load);
                            $.post("fragments/list_department_table.php", {account:select_account, div:selected_div}, function (list) {
                                $("#department_list").html(list);
                            });
                        });
                    }else if (select_account != ''){
                        $.post("modal/loader.php", function (load) {
                            $("#department_list").html(load);
                            $.post("fragments/list_department_table.php", {account:select_account}, function (list) {
                                $("#department_list").html(list);
                            });
                        });
                    }else if (selected_div != ''){
                        $.post("modal/loader.php", function (load) {
                            $("#department_list").html(load);
                            $.post("fragments/list_department_table.php", {div:selected_div}, function (list) {
                                $("#department_list").html(list);
                            });
                        });
                    }else {
                        $.post("modal/loader.php", function(load){
                            $("#department_list").html(load);
                            $.post("fragments/list_department_table.php", function (list){
                                $("#department_list").html(list);
                            });
                        });
                    }
                });

                $("#WDivSel").change( function (){
                    var select_account = $("#AccSel option:selected").val();
                    var selected_div = $("#WDivSel option:selected").val();
                    if (select_account != '' && selected_div != ''){
                        $.post("modal/loader.php", function (load) {
                            $("#department_list").html(load);
                            $.post("fragments/list_department_table.php", {account:select_account, div:selected_div}, function (list) {
                                $("#department_list").html(list);
                            });
                        });
                    }else if (select_account != ''){
                        $.post("modal/loader.php", function (load) {
                            $("#department_list").html(load);
                            $.post("fragments/list_department_table.php", {account:select_account}, function (list) {
                                $("#department_list").html(list);
                            });
                        });
                    }else if (selected_div != ''){
                        $.post("modal/loader.php", function (load) {
                            $("#department_list").html(load);
                            $.post("fragments/list_department_table.php", {div:selected_div}, function (list) {
                                $("#department_list").html(list);
                            });
                        });
                    }else {
                        $.post("modal/loader.php", function(load){
                            $("#department_list").html(load);
                            $.post("fragments/list_department_table.php", function (list){
                                $("#department_list").html(list);
                            });
                        });
                    }
                });
        }

        else if (list == '1'){
                $.post("modal/loader.php", function(load){
                    $("#department_deleted_list").html(load);
                    $.post("fragments/list_department_deleted.php", function (list){
                        $("#department_deleted_list").html(list);
                    });
                });
                $("#department_active").hide();
                $("#department_deleted").show();
                $("#trash_button_container").show();
                $("#active_button_container").hide();

                $("#AccSel").change( function (){
                    var select_account = $("#AccSel option:selected").val();
                    var selected_div = $("#WDivSel option:selected").val();
                    if (select_account != '' && selected_div != ''){
                        $.post("modal/loader.php", function (load) {
                            $("#department_deleted_list").html(load);
                            $.post("fragments/list_department_deleted.php", {account:select_account, div:selected_div}, function (list) {
                                $("#department_deleted_list").html(list);
                            });
                        });
                    }else if (select_account != ''){
                        $.post("modal/loader.php", function (load) {
                            $("#department_deleted_list").html(load);
                            $.post("fragments/list_department_deleted.php", {account:select_account}, function (list) {
                                $("#department_deleted_list").html(list);
                            });
                        });
                    }else if (selected_div != ''){
                        $.post("modal/loader.php", function (load) {
                            $("#department_deleted_list").html(load);
                            $.post("fragments/list_department_deleted.php", {div:selected_div}, function (list) {
                                $("#department_deleted_list").html(list);
                            });
                        });
                    }else {
                        $.post("modal/loader.php", function(load){
                            $("#department_deleted_list").html(load);
                            $.post("fragments/list_department_deleted.php", function (list){
                                $("#department_deleted_list").html(list);
                            });
                        });
                    }
                });

                $("#WDivSel").change( function (){
                    var select_account = $("#AccSel option:selected").val();
                    var selected_div = $("#WDivSel option:selected").val();
                    if (select_account != '' && selected_div != ''){
                        $.post("modal/loader.php", function (load) {
                            $("#department_deleted_list").html(load);
                            $.post("fragments/list_department_deleted.php", {account:select_account, div:selected_div}, function (list) {
                                $("#department_deleted_list").html(list);
                            });
                        });
                    }else if (select_account != ''){
                        $.post("modal/loader.php", function (load) {
                            $("#department_deleted_list").html(load);
                            $.post("fragments/list_department_deleted.php", {account:select_account}, function (list) {
                                $("#department_deleted_list").html(list);
                            });
                        });
                    }else if (selected_div != ''){
                        $.post("modal/loader.php", function (load) {
                            $("#department_deleted_list").html(load);
                            $.post("fragments/list_department_deleted.php", {div:selected_div}, function (list) {
                                $("#department_deleted_list").html(list);
                            });
                        });
                    }else {
                        $.post("modal/loader.php", function(load){
                            $("#department_deleted_list").html(load);
                            $.post("fragments/list_department_deleted.php", function (list){
                                $("#department_deleted_list").html(list);
                            });
                        });
                    }
                });
        }
    });






});










