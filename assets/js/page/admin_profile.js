"use strict";

//Change Password//
$("#changepw1").on("click", function(){
    $("#changepwModal").modal('show');
});
$("#changepw").on("click", function(){
    $("#changepwModal").modal('show');
});

//Logout btn//
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

    //Logo AJAX Query//
    $("form[name=department]").on("submit", function (ev) {
            ev.preventDefault();
            var form = new FormData(this);
            $.ajax({
                url: "queries/submit_img_info.php",
                type: "POST",
                data: form,
                contentType: false,
                cache: false,
                processData: false,
                success: function (data) {
                    if (data == "invalid") {
                        alert("error");
                    }else {
                        iziToast.success({
                            title: "DEPARTMENT LOGO",
                            message: "successfully changed!!",
                            position: "topRight"
                        });
                    }
                },
                error: function () {
                    Swal.fire("ERROR", "Something Went Wrong!!", "error");
                }
            });
        });

    //Image change query//
    $("input[type=file]").each(function () {
        var $file = $(this),
            $label = $file.next("label"),
            $labelText = $label.find("span"),
            labelDefault = $labelText.text();
        var btn = $("#btnSaveProfile");
        $file.on("change", function (event) {

            if (this.files[0].size > 2000000) {
                Swal.fire("IMAGE", "Please upload file less than 2MB. Thanks!!", "info");
            } else {
                var fileName = $file.val().split("\\").pop(),
                    tmppath = URL.createObjectURL(event.target.files[0]);
                if (fileName) {
                    $label
                        .addClass("file-ok")
                        .css("background-image", "url('" + tmppath + "')");
                    btn.click();
                } else {
                    $label.removeClass("file-ok");
                    $labelText.text(labelDefault);
                }
            }
        });
    });

    //Load Department Profile//
    $.post("modal/loader.php", function (load) {
        $("#department_information").html(load);
        $.post("fragments/department_information.php", function (user) {
            $("#department_information").html(user);
        });
    });
});
