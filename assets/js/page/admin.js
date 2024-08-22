"use strict";

$(document).ready(function () {

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

    setInterval(function () {
        $.post("fragments/online_examinee.php", function (online){
            $("#online_examinee").html(online);
        });
    }, 1000);

    setInterval(function () {
        $.post("fragments/inactive_exams.php", function (inactive){
            $("#total_inactive_exam").html(inactive);
        });
    }, 1000);

    setInterval(function () {
        $.post("fragments/active_exams.php", function (active){
            $("#total_active_exam").html(active);
        });
    }, 1000);

    setInterval(function () {
        $.post("fragments/total_examinee.php", function (online){
            $("#total_examinee").html(online);
        });
    }, 1000);

    $.post("modal/loader.php", function (loader){
        $("#list_exam_filter").html(loader);
        $.post("fragments/exam_filtered.php", function (load){
            $("#list_exam_filter").html(load);
        });
    });

    $.post("modal/loader.php", function (load) {
        $("#chart_user").html(load);
        $.post("fragments/chart.php", function (user) {
            $("#chart_user").html(user);
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

    $.post("modal/loader.php", function (load) {
        $("#examinees").html(load);
        $.post("fragments/examinees.php", function (user) {
            $("#examinees").html(user);
        });
    });

    $("#divselect").change( function (){
        var selected_div = $("#divselect option:selected").val();
        if (selected_div != ''){
            $.post("modal/loader.php", function (load) {
                $("#examinees").html(load);
                $.post("fragments/examinees.php", { division:selected_div}, function (user) {
                    $("#examinees").html(user);
                });
            });
        }else {
            $.post("modal/loader.php", function (load) {
                $("#examinees").html(load);
                $.post("fragments/examinees.php", function (user) {
                    $("#examinees").html(user);
                });
            });
        }
    });

    $("#divisionselect").change( function (){
        var selected_div = $("#divisionselect option:selected").val();
        if (selected_div != ''){
            $.post("modal/loader.php", function (load) {
                $("#user_feedback").html(load);
                $.post("fragments/feedback_user.php", {division:selected_div}, function (user) {
                    $("#user_feedback").html(user);
                });
            });
        }else {
            $.post("modal/loader.php", function (load) {
                $("#user_feedback").html(load);
                $.post("fragments/feedback_user.php", function (user) {
                    $("#user_feedback").html(user);
                });
            });
        }
    });

    $("#diviselect").change(function (){
        var division = $("#diviselect option:selected").val();
        var status = $("#status_sel option:selected").val();
        if (division != '' && status != ''){
            $.post("modal/loader.php", function (loader) {
                $("#list_exam_filter").html(loader);
                $.post("fragments/exam_filtered.php", {division:division, status:status}, function (load) {
                    $("#list_exam_filter").html(load);
                });
            });
        }else if (status != ''){
            $.post("modal/loader.php", function (loader) {
                $("#list_exam_filter").html(loader);
                $.post("fragments/exam_filtered.php", {status:status}, function (load) {
                    $("#list_exam_filter").html(load);
                });
            });
        }else if (division != ''){
            $.post("modal/loader.php", function (loader) {
                $("#list_exam_filter").html(loader);
                $.post("fragments/exam_filtered.php", {division:division}, function (load) {
                    $("#list_exam_filter").html(load);
                });
            });
        }else {
            $.post("modal/loader.php", function (loader){
                $("#list_exam_filter").html(loader);
                $.post("fragments/exam_filtered.php", function (load){
                    $("#list_exam_filter").html(load);
                });
            });
        }
    });

    $("#status_sel").change(function (){
        var division = $("#diviselect option:selected").val();
        var status = $("#status_sel option:selected").val();
        if (division != '' && status != ''){
            $.post("modal/loader.php", function (loader) {
                $("#list_exam_filter").html(loader);
                $.post("fragments/exam_filtered.php", {division:division, status:status}, function (load) {
                    $("#list_exam_filter").html(load);
                });
            });
        }else if (status != ''){
            $.post("modal/loader.php", function (loader) {
                $("#list_exam_filter").html(loader);
                $.post("fragments/exam_filtered.php", {status:status}, function (load) {
                    $("#list_exam_filter").html(load);
                });
            });
        }else if (division != ''){
            $.post("modal/loader.php", function (loader) {
                $("#list_exam_filter").html(loader);
                $.post("fragments/exam_filtered.php", {division:division}, function (load) {
                    $("#list_exam_filter").html(load);
                });
            });
        }else {
            $.post("modal/loader.php", function (loader){
                $("#list_exam_filter").html(loader);
                $.post("fragments/exam_filtered.php", function (load){
                    $("#list_exam_filter").html(load);
                });
            });
        }
    });


    $.post("modal/loader.php", function (load) {
        $("#user_log").html(load);
        $.post("fragments/user_log.php", function (user) {
            $("#user_log").html(user);
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

