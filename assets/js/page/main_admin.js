"use strict";

$(document).ready(function () {

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

    $("#filter-btn2").on("click", function (){
        var filter = $("#filter2");
        if (filter.hasClass("filter")){
            filter.show();
            filter.removeClass("filter");
        }else {
            filter.hide();
            filter.addClass("filter");
        }
    });
    $.post("modal/loader.php", function (load) {
        $("#user_feedback").html(load);
        $.post("fragments/feedback_user.php", function (user) {
            $("#user_feedback").html(user);
        });
    });

    $("#depselect2").change( function (){
        var selected_dep = $("#depselect2 option:selected").val();
        if (selected_dep != ''){
            $("#divselect2").prop("disabled", false);
            $.post("fragments/division_select.php", {department:selected_dep}, function(div){
                $("#divselect2").html(div);
            });
            $.post("modal/loader.php", function (load) {
                $("#user_feedback").html(load);
                $.post("fragments/feedback_user.php", {department:selected_dep}, function (user) {
                    $("#user_feedback").html(user);
                });
            });
            $("#divselect2").change( function (){
                var selected_div = $("#divselect2 option:selected").val();
                if (selected_div != ''){
                    $.post("modal/loader.php", function (load) {
                        $("#user_feedback").html(load);
                        $.post("fragments/feedback_user.php", {department:selected_dep, division:selected_div}, function (user) {
                            $("#user_feedback").html(user);
                        });
                    });
                }else {
                    $.post("modal/loader.php", function (load) {
                        $("#user_feedback").html(load);
                        $.post("fragments/feedback_user.php", {department:selected_dep}, function (user) {
                            $("#user_feedback").html(user);
                        });
                    });
                }
            });
        }else {
            $("#divselect2").prop("disabled", true);
            $.post("fragments/disabled_division.php", function (div){
                $("#divselect2").html(div);
            });
            $.post("modal/loader.php", function (load) {
                $("#user_feedback").html(load);
                $.post("fragments/feedback_user.php", function (user) {
                    $("#user_feedback").html(user);
                });
            });
        }

    });


    $.post("fragments/disabled_division.php", function (div){
        $("#divselect1").html(div);
    });

    $("#filter-btn1").on("click", function (){
        var filter = $("#filter1");
        if (filter.hasClass("filter")){
            filter.show();
            filter.removeClass("filter");
        }else {
            filter.hide();
            filter.addClass("filter");
        }
    });

    $("#status_sel").change( function (){
        var selected_div = $("#divselect1 option:selected").val();
        var selected_dep = $("#depselect1 option:selected").val();
        var selected_status = $("#status_sel option:selected").val();
        if (selected_dep != '' && selected_div != '' && selected_status != ''){
            $.post("modal/loader.php", function (load) {
                $("#list_exam_filter").html(load);
                $.post("fragments/exam_filtered.php", { department:selected_dep, division:selected_div, status:selected_status}, function (user) {
                    $("#list_exam_filter").html(user);
                });
            });
        }else if (selected_status != '' && selected_dep != ''){
            $.post("modal/loader.php", function (load) {
                $("#list_exam_filter").html(load);
                $.post("fragments/exam_filtered.php", { department:selected_dep, status:selected_status}, function (user) {
                    $("#list_exam_filter").html(user);
                });
            });
        }else if (selected_dep != '' && selected_div != ''){
            $.post("modal/loader.php", function (load) {
                $("#list_exam_filter").html(load);
                $.post("fragments/exam_filtered.php", {department:selected_dep, division:selected_div}, function (user) {
                    $("#list_exam_filter").html(user);
                });
            });
        }else if (selected_dep != ''){
            $.post("modal/loader.php", function (load) {
                $("#list_exam_filter").html(load);
                $.post("fragments/exam_filtered.php", {department:selected_dep}, function (user) {
                    $("#list_exam_filter").html(user);
                });
            });
        }else if (selected_status != ''){
            $.post("modal/loader.php", function (load) {
                $("#list_exam_filter").html(load);
                $.post("fragments/exam_filtered.php", { status:selected_status}, function (user) {
                    $("#list_exam_filter").html(user);
                });
            });
        }else {
            $.post("modal/loader.php", function (load) {
                $("#list_exam_filter").html(load);
                $.post("fragments/exam_filtered.php", function (user) {
                    $("#list_exam_filter").html(user);
                });
            });
        }
    });

    $("#divselect1").change( function (){
        var selected_div = $("#divselect1 option:selected").val();
        var selected_dep = $("#depselect1 option:selected").val();
        var selected_status = $("#status_sel option:selected").val();
        if (selected_dep != '' && selected_div != '' && selected_status != ''){
            $.post("modal/loader.php", function (load) {
                $("#list_exam_filter").html(load);
                $.post("fragments/exam_filtered.php", {department:selected_dep, division:selected_div, status:selected_status}, function (user) {
                    $("#list_exam_filter").html(user);
                });
            });

        }else if (selected_dep != '' && selected_div != ''){
            $.post("modal/loader.php", function (load) {
                $("#list_exam_filter").html(load);
                $.post("fragments/exam_filtered.php", {department:selected_dep, division:selected_div}, function (user) {
                    $("#list_exam_filter").html(user);
                });
            });
        }else if (selected_dep != '' && selected_status != ''){
            $.post("modal/loader.php", function (load) {
                $("#list_exam_filter").html(load);
                $.post("fragments/exam_filtered.php", {department:selected_dep, status:selected_status}, function (user) {
                    $("#list_exam_filter").html(user);
                });
            });
        }else if (selected_status != ''){
            $.post("modal/loader.php", function (load) {
                $("#list_exam_filter").html(load);
                $.post("fragments/exam_filtered.php", {status:selected_status}, function (user) {
                    $("#list_exam_filter").html(user);
                });
            });
        }else if (selected_dep != ''){
            $.post("modal/loader.php", function (load) {
                $("#list_exam_filter").html(load);
                $.post("fragments/exam_filtered.php", {department:selected_dep}, function (user) {
                    $("#list_exam_filter").html(user);
                });
            });
        }else {
            $.post("modal/loader.php", function (load) {
                $("#list_exam_filter").html(load);
                $.post("fragments/exam_filtered.php", function (user) {
                    $("#list_exam_filter").html(user);
                });
            });
        }
    });

    $("#depselect1").change( function (){
        var selected_dep = $("#depselect1 option:selected").val();
        var selected_div = $("#divselect1 option:selected").val();
        var selected_status = $("#status_sel option:selected").val();
        if (selected_status != '' && selected_dep != ''){
            $("#divselect1").prop("disabled", false);
            $.post("fragments/division_select.php", {department:selected_dep}, function(div){
                $("#divselect1").html(div);
            });
            $.post("modal/loader.php", function (load) {
                $("#list_exam_filter").html(load);
                $.post("fragments/exam_filtered.php", {department:selected_dep, status:selected_status}, function (user) {
                    $("#list_exam_filter").html(user);
                });
            });
        }else if (selected_status != '' && selected_dep != '' && selected_div != ''){
            $("#divselect1").prop("disabled", false);
            $.post("fragments/division_select.php", {department:selected_dep}, function(div){
                $("#divselect1").html(div);
            });
            $.post("modal/loader.php", function (load) {
                $("#list_exam_filter").html(load);
                $.post("fragments/exam_filtered.php", {department:selected_dep, division:selected_div, status:selected_status}, function (user) {
                    $("#list_exam_filter").html(user);
                });
            });
        }else if (selected_dep != ''){
            $("#divselect1").prop("disabled", false);
            $.post("fragments/division_select.php", {department:selected_dep}, function(div){
                $("#divselect1").html(div);
            });
            $.post("modal/loader.php", function (load) {
                $("#list_exam_filter").html(load);
                $.post("fragments/exam_filtered.php", {department:selected_dep}, function (user) {
                    $("#list_exam_filter").html(user);
                });
            });
        }else if (selected_status != ''){
            $("#divselect1").prop("disabled", true);
            $.post("fragments/disabled_division.php", function (div){
                $("#divselect1").html(div);
            });
            $.post("modal/loader.php", function (load) {
                $("#list_exam_filter").html(load);
                $.post("fragments/exam_filtered.php", {status:selected_status}, function (user) {
                    $("#list_exam_filter").html(user);
                });
            });
        }else {
            $("#divselect1").prop("disabled", true);
            $.post("fragments/disabled_division.php", function (div){
                $("#divselect1").html(div);
            });
            $.post("modal/loader.php", function (load) {
                $("#list_exam_filter").html(load);
                $.post("fragments/exam_filtered.php", function (user) {
                    $("#list_exam_filter").html(user);
                });
            });
        }
    });

    $.post("modal/loader.php", function (loader){
        $("#list_exam_filter").html(loader);
        $.post("fragments/exam_filtered.php", function (load){
            $("#list_exam_filter").html(load);
        });
    });

    $("#filter-btn").on("click", function (){
        var filter = $("#filter");
        if (filter.hasClass("filter")){
            filter.show();
            filter.removeClass("filter");
        }else {
            filter.hide();
            filter.addClass("filter");
        }
    });

    $.post("fragments/disabled_division.php", function (div){
        $("#divselect").html(div);
    });

    $("#depselect").change( function (){
        var selected_dep = $("#depselect option:selected").val();
        if (selected_dep != ''){
            $("#divselect").prop("disabled", false);
            $.post("fragments/division_select.php", {department:selected_dep}, function(div){
                $("#divselect").html(div);
            });
            $.post("modal/loader.php", function (load) {
                $("#main_examinees").html(load);
                $.post("fragments/examinees.php", {department:selected_dep}, function (user) {
                    $("#main_examinees").html(user);
                });
            });
            $("#divselect").change( function (){
                var selected_div = $("#divselect option:selected").val();
                if (selected_div != ''){
                    $.post("modal/loader.php", function (load) {
                        $("#main_examinees").html(load);
                        $.post("fragments/examinees.php", {department:selected_dep, division:selected_div}, function (user) {
                            $("#main_examinees").html(user);
                        });
                    });
                }else {
                    $.post("modal/loader.php", function (load) {
                        $("#main_examinees").html(load);
                        $.post("fragments/examinees.php", {department:selected_dep}, function (user) {
                            $("#main_examinees").html(user);
                        });
                    });
                }
            });
        }else {
            $("#divselect").prop("disabled", true);
            $.post("fragments/disabled_division.php", function (div){
                $("#divselect").html(div);
            });
            $.post("modal/loader.php", function (load) {
                $("#main_examinees").html(load);
                $.post("fragments/examinees.php", function (user) {
                    $("#main_examinees").html(user);
                });
            });
        }

    });
    setInterval(function (){
        $.post("fragments/total_examinee.php", function (user) {
            $("#total_examinee").html(user);
        });
    },1000);

    setInterval(function (){
        $.post("fragments/total_exams.php", function (exam) {
            $("#total_exams").html(exam);
        });
    },1000);

    setInterval(function (){
        $.post("fragments/total_department.php", function (dept) {
            $("#total_department").html(dept);
        });
    },1000);

    $.post("modal/loader.php", function (load) {
        $("#dept_list").html(load);
        $.post("fragments/department.php", function (user) {
            $("#dept_list").html(user);
        });
    });
    $.post("modal/loader.php", function (load) {
        $("#main_examinees").html(load);
        $.post("fragments/examinees.php", function (user) {
            $("#main_examinees").html(user);
        });
    });
    $.post("modal/loader.php", function (load) {
        $("#staff_log").html(load);
        $.post("fragments/staff_log.php", function (user) {
            $("#staff_log").html(user);
        });
    });
    $.post("modal/loader.php", function (load) {
        $("#user_log").html(load);
        $.post("fragments/user_log.php", function (user) {
            $("#user_log").html(user);
        });
    });

    $.post("modal/loader.php", function (load) {
        $("#chart_log").html(load);
        $.post("fragments/chart.php", function (user) {
            $("#chart_log").html(user);
        });
    });
});
//Change Password//
$("#changepw1").on("click", function () {
    $("#changepwModal").modal('show');
});
$("#changepw").on("click", function () {
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

