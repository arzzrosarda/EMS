"use strict";

$("#testsel").change(function (){
    var sel_test = $("#testsel option:selected").val();
    if (sel_test == 1){
        $("#testI").show();
        $("#testII").hide();
        $("#testIII").hide();
        $("#testIV").hide();
        $("#testV").hide();
    }else if(sel_test == 2) {
        $("#testI").show();
        $("#testII").show();
        $("#testIII").hide();
        $("#testIV").hide();
        $("#testV").hide();
    }else if(sel_test == 3) {
        $("#testI").show();
        $("#testII").show();
        $("#testIII").show();
        $("#testIV").hide();
        $("#testV").hide();
    }else if(sel_test == 4) {
        $("#testI").show();
        $("#testII").show();
        $("#testIII").show();
        $("#testIV").show();
        $("#testV").hide();
    }else if(sel_test == 5) {
        $("#testI").show();
        $("#testII").show();
        $("#testIII").show();
        $("#testIV").show();
        $("#testV").show();
    }else {
        $("#testI").hide();
        $("#testII").hide();
        $("#testIII").hide();
        $("#testIV").hide();
        $("#testV").hide();
    }
});
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

    $('input[type="file"]').each(function(){

        var $file = $(this),
            $label = $file.next('label'),
            $labelText = $label.find('span'),
            labelDefault = $labelText.text();

        $file.on('change', function(event){
            var fileName = $file.val().split( '\\' ).pop(),
                tmppath = URL.createObjectURL(event.target.files[0]);
            if( fileName ){
                $label
                    .addClass('file-ok')
                    .css('background-image', 'url(' + tmppath + ')');
            }else{
                $label.removeClass('file-ok');
                $labelText.text(labelDefault);
            }
        });

    });

});

$("#btnsbmtexamtitle").click( function (){
    var title = $('input[name="txt_title"]').val();
    var division = $('#divselect option:selected').val();
    var department = $('input[name="txt_department"]').val();
    var hour = $('#hoursel option:selected').val();
    var minutes = $('#minsel option:selected').val();
    var seconds = $('#secsel option:selected').val();
    var test_num = $('#testsel option:selected').val();
    var testI = $('#txt_testI option:selected').val();
    var testII = $('#txt_testII option:selected').val();
    var testIII = $('#txt_testIII option:selected').val();
    var testIV = $('#txt_testIV option:selected').val();
    var testV = $('#txt_testV option:selected').val();
    if (title == ''){
        $('input[name="txt_title"]').focus();
        iziToast.warning({
            title: 'Empty!',
            message: 'Exam title is required',
            position: 'topRight'
        });
    }else if (division == ''){
        $('#divselect').focus();
        iziToast.warning({
            title: 'Empty!',
            message: 'Division for exam is required',
            position: 'topRight'
        });
    }else if (hour == ''){
        $('#hoursel').focus();
        iziToast.warning({
            title: 'Empty!',
            message: 'HOUR/S in time limit is required',
            position: 'topRight'
        });
    }else if (minutes == ''){
        $('#minsel').focus();
        iziToast.warning({
            title: 'Empty!',
            message: 'MINUTES in time limit is required',
            position: 'topRight'
        });
    }else if (seconds == ''){
        $('#secsel').focus();
        iziToast.warning({
            title: 'Empty!',
            message: 'SECOND/S in time limit is required',
            position: 'topRight'
        });
    }else if (test_num == ''){
        $('#testI').focus();
        iziToast.warning({
            title: 'Empty!',
            message: 'Number of test is required',
            position: 'topRight'
        });
    }
    else if (test_num == '1'){
        if (testI == ''){
            $('#txt_testI').focus();
            iziToast.warning({
                title: 'Empty!',
                message: 'Test I type is required',
                position: 'topRight'
            });
        }else {
            Swal.fire({
                title: "ADD EXAM?",
                html: "Press continue to proceed to adding questions into your exam",
                icon: "info",
                showCancelButton: true,
                confirmButtonText: "Continue",
                cancelButtonText: "Cancel",
                closeOnConfirm: false,
            }).then((result) => {
                if (result.isConfirmed) {
                    showModal();
                    $("#loader").modal("show");
                    $.post("queries/add_exam.php", { exam_title:title, exam_dep:department, exam_div:division, hours:hour, minute:minutes, second:seconds, test_number:test_num}, function (exam){
                        hideModal();
                        $("#loader").modal("hide");
                        if (exam == 1){
                            $.post("queries/add_exam_test.php", {exam_title:title, test_1:testI, test_2:testII, test_3:testIII, test_4:testIV, test_5:testV}, function (test){
                                if (test == 1){
                                    iziToast.success({
                                        timeout: 2000,
                                        title: 'SUCCESS',
                                        message: 'Successfully added exam ' + title + ' redirecting......',
                                        position: 'topCenter', // bottomRight, bottomLeft, topRight, topLeft, topCenter, bottomCenter
                                        onClosing: function(){
                                            $.post('queries/redirect_exam.php', {exam_title:title}, function (title_id){
                                                $.redirect('add_question.php', {'exam_id': title_id});
                                            });
                                        }
                                    });
                                }else {
                                    iziToast.warning({
                                        title: 'ERROR',
                                        message: 'Something went wrong',
                                        position: 'topRight'
                                    });
                                }
                            });
                        }else if (exam == 2) {
                            iziToast.warning({
                                title: '2',
                                message: 'Exam already exists',
                                position: 'topRight'
                            });
                        }else {
                            iziToast.error({
                                title: 'else',
                                message: 'Something went wrong!',
                                position: 'topRight'
                            });
                        }
                    });
                }
            });
        }
    }
    else if (test_num == '2'){
        if (testI == ''){
            $('#txt_testI').focus();
            iziToast.warning({
                title: 'Empty!',
                message: 'Test I type is required is required',
                position: 'topRight'
            });
        }else if (testII == ''){
            $('#txt_testII').focus();
            iziToast.warning({
                title: 'Empty!',
                message: 'Test II type is required is required',
                position: 'topRight'
            });
        }else {
            Swal.fire({
                title: "ADD EXAM?",
                html: "Press continue to proceed to adding questions into your exam",
                icon: "info",
                showCancelButton: true,
                confirmButtonText: "Continue",
                cancelButtonText: "Cancel",
                closeOnConfirm: false,
            }).then((result) => {
                if (result.isConfirmed) {
                    showModal();
                    $("#loader").modal("show");
                    $.post("queries/add_exam.php", { exam_title:title, exam_dep:department, exam_div:division, hours:hour, minute:minutes, second:seconds, test_number:test_num}, function (exam){
                        hideModal();
                        $("#loader").modal("hide");
                        if (exam == 1){
                            $.post("queries/add_exam_test.php", {exam_title:title, test_1:testI, test_2:testII, test_3:testIII, test_4:testIV, test_5:testV}, function (test){
                                if (test == 1){
                                    iziToast.success({
                                        timeout: 2000,
                                        title: 'SUCCESS',
                                        message: 'Successfully added exam ' + title + ' redirecting......',
                                        position: 'topCenter', // bottomRight, bottomLeft, topRight, topLeft, topCenter, bottomCenter
                                        onClosing: function(){
                                            $.post('queries/redirect_exam.php', {exam_title:title}, function (title_id){
                                                $.redirect('add_question.php', {'exam_id': title_id});
                                            });
                                        }
                                    });
                                }else {
                                    iziToast.warning({
                                        title: 'ERROR',
                                        message: 'Something went wrong',
                                        position: 'topRight'
                                    });
                                }
                            });
                        }else if (exam == 2) {
                            iziToast.warning({
                                title: 'ERROR',
                                message: 'Exam already exists',
                                position: 'topRight'
                            });
                        }else {
                            iziToast.error({
                                title: 'ERROR',
                                message: 'Something went wrong!',
                                position: 'topRight'
                            });
                        }
                    });
                }
            });
        }
    }
    else if (test_num == '3'){
        if (testI == ''){
            $('#txt_testI').focus();
            iziToast.warning({
                title: 'Empty!',
                message: 'Test I type is required is required',
                position: 'topRight'
            });
        }
        else if (testII == ''){
            $('#txt_testII').focus();
            iziToast.warning({
                title: 'Empty!',
                message: 'Test II type is required is required',
                position: 'topRight'
            });
        }else if (testIII == ''){
            $('#txt_testIII').focus();
            iziToast.warning({
                title: 'Empty!',
                message: 'Test III type is required is required',
                position: 'topRight'
            });
        }else {
            Swal.fire({
                title: "ADD EXAM?",
                html: "Press continue to proceed to adding questions into your exam",
                icon: "info",
                showCancelButton: true,
                confirmButtonText: "Continue",
                cancelButtonText: "Cancel",
                closeOnConfirm: false,
            }).then((result) => {
                if (result.isConfirmed) {
                    showModal();
                    $("#loader").modal("show");
                    $.post("queries/add_exam.php", { exam_title:title, exam_dep:department, exam_div:division, hours:hour, minute:minutes, second:seconds, test_number:test_num}, function (exam){
                        hideModal();
                        $("#loader").modal("hide");
                        if (exam == 1){
                            $.post("queries/add_exam_test.php", {exam_title:title, test_1:testI, test_2:testII, test_3:testIII, test_4:testIV, test_5:testV}, function (test){
                                if (test == 1){
                                    iziToast.success({
                                        timeout: 2000,
                                        title: 'SUCCESS',
                                        message: 'Successfully added exam ' + title + ' redirecting......',
                                        position: 'topCenter', // bottomRight, bottomLeft, topRight, topLeft, topCenter, bottomCenter
                                        onClosing: function(){
                                            $.post('queries/redirect_exam.php', {exam_title:title}, function (title_id){
                                                $.redirect('add_question.php', {'exam_id': title_id});
                                            });
                                        }
                                    });
                                }else {
                                    iziToast.warning({
                                        title: 'ERROR',
                                        message: 'Something went wrong',
                                        position: 'topRight'
                                    });
                                }
                            });
                        }else if (exam == 2) {
                            iziToast.warning({
                                title: 'ERROR',
                                message: 'Exam already exists',
                                position: 'topRight'
                            });
                        }else {
                            iziToast.error({
                                title: 'ERROR',
                                message: 'Something went wrong!',
                                position: 'topRight'
                            });
                        }
                    });
                }
            });
        }
    }
    else if (test_num == '4'){
        if (testI == ''){
            $('#txt_testI').focus();
            iziToast.warning({
                title: 'Empty!',
                message: 'Test I type is required is required',
                position: 'topRight'
            });
        }else if (testII == ''){
            $('#txt_testII').focus();
            iziToast.warning({
                title: 'Empty!',
                message: 'Test II type is required is required',
                position: 'topRight'
            });
        }else if (testIII == ''){
            $('#txt_testIII').focus();
            iziToast.warning({
                title: 'Empty!',
                message: 'Test III type is required is required',
                position: 'topRight'
            });
        }else if (testIV == ''){
            $('#txt_testIV').focus();
            iziToast.warning({
                title: 'Empty!',
                message: 'Test IV type is required is required',
                position: 'topRight'
            });
        }else {
            Swal.fire({
                title: "ADD EXAM?",
                html: "Press continue to proceed to adding questions into your exam",
                icon: "info",
                showCancelButton: true,
                confirmButtonText: "Continue",
                cancelButtonText: "Cancel",
                closeOnConfirm: false,
            }).then((result) => {
                if (result.isConfirmed) {
                    showModal();
                    $("#loader").modal("show");
                    $.post("queries/add_exam.php", { exam_title:title, exam_dep:department, exam_div:division, hours:hour, minute:minutes, second:seconds, test_number:test_num}, function (exam){
                        hideModal();
                        $("#loader").modal("hide");
                        if (exam == 1){
                            $.post("queries/add_exam_test.php", {exam_title:title, test_1:testI, test_2:testII, test_3:testIII, test_4:testIV, test_5:testV}, function (test){
                                if (test == 1){
                                    iziToast.success({
                                        timeout: 2000,
                                        title: 'SUCCESS',
                                        message: 'Successfully added exam ' + title + ' redirecting......',
                                        position: 'topCenter', // bottomRight, bottomLeft, topRight, topLeft, topCenter, bottomCenter
                                        onClosing: function(){
                                            $.post('queries/redirect_exam.php', {exam_title:title}, function (title_id){
                                                $.redirect('add_question.php', {'exam_id': title_id});
                                            });
                                        }
                                    });
                                }else {
                                    iziToast.warning({
                                        title: 'ERROR',
                                        message: 'Something went wrong',
                                        position: 'topRight'
                                    });
                                }
                            });
                        }else if (exam == 2) {
                            iziToast.warning({
                                title: 'ERROR',
                                message: 'Exam already exists',
                                position: 'topRight'
                            });
                        }else {
                            iziToast.error({
                                title: 'ERROR',
                                message: 'Something went wrong!',
                                position: 'topRight'
                            });
                        }
                    });
                }
            });
        }
    }
    else if (test_num == '5'){
        if (testI == ''){
            $('#txt_testI').focus();
            iziToast.warning({
                title: 'Empty!',
                message: 'Test I type is required is required',
                position: 'topRight'
            });
        }else if (testII == ''){
            $('#txt_testII').focus();
            iziToast.warning({
                title: 'Empty!',
                message: 'Test II type is required is required',
                position: 'topRight'
            });
        }else if (testIII == ''){
            $('#txt_testIII').focus();
            iziToast.warning({
                title: 'Empty!',
                message: 'Test III type is required is required',
                position: 'topRight'
            });
        }else if (testIV == ''){
            $('#txt_testIV').focus();
            iziToast.warning({
                title: 'Empty!',
                message: 'Test IV type is required is required',
                position: 'topRight'
            });
        }else if (testV == ''){
            $('#txt_testV').focus();
            iziToast.warning({
                title: 'Empty!',
                message: 'Test V type is required is required',
                position: 'topRight'
            });
        }else {
            Swal.fire({
                title: "ADD EXAM?",
                html: "Press continue to proceed to adding questions into your exam",
                icon: "info",
                showCancelButton: true,
                confirmButtonText: "Continue",
                cancelButtonText: "Cancel",
                closeOnConfirm: false,
            }).then((result) => {
                if (result.isConfirmed) {
                    showModal();
                    $("#loader").modal("show");
                    $.post("queries/add_exam.php", { exam_title:title, exam_dep:department, exam_div:division, hours:hour, minute:minutes, second:seconds, test_number:test_num}, function (exam){
                        hideModal();
                        $("#loader").modal("hide");
                        if (exam == 1){
                            $.post("queries/add_exam_test.php", {exam_title:title, test_1:testI, test_2:testII, test_3:testIII, test_4:testIV, test_5:testV}, function (test){
                                if (test == 1){
                                    iziToast.success({
                                        timeout: 2000,
                                        title: 'SUCCESS',
                                        message: 'Successfully added exam ' + title + ' redirecting......',
                                        position: 'topCenter', // bottomRight, bottomLeft, topRight, topLeft, topCenter, bottomCenter
                                        onClosing: function(){
                                            $.post('queries/redirect_exam.php', {exam_title:title}, function (title_id){
                                                $.redirect('add_question.php', {'exam_id': title_id});
                                            });
                                        }
                                    });
                                }else {
                                    iziToast.warning({
                                        title: 'ERROR',
                                        message: 'Something went wrong',
                                        position: 'topRight'
                                    });
                                }
                            });
                        }else if (exam == 2) {
                            iziToast.warning({
                                title: 'ERROR',
                                message: 'Exam already exists',
                                position: 'topRight'
                            });
                        }else {
                            iziToast.error({
                                title: 'ERROR',
                                message: 'Something went wrong!',
                                position: 'topRight'
                            });
                        }
                    });
                }
            });
        }
    }

});

$("#btnupdateexamtitle").click( function (){
    var exam_id = $('input[name="txt_exam_id"]').val();
    var title = $('input[name="txt_title"]').val();
    var division = $('#divselect option:selected').val();
    var department = $('input[name="txt_department"]').val();
    var hour = $('#hoursel option:selected').val();
    var minutes = $('#minsel option:selected').val();
    var seconds = $('#secsel option:selected').val();
    var test_num = $('#testsel option:selected').val();
    var testI = $('#txt_testI option:selected').val();
    var testII = $('#txt_testII option:selected').val();
    var testIII = $('#txt_testIII option:selected').val();
    var testIV = $('#txt_testIV option:selected').val();
    var testV = $('#txt_testV option:selected').val();
    if (title == ''){
        $('input[name="txt_title"]').focus();
        iziToast.warning({
            title: 'Empty!',
            message: 'Exam title is required',
            position: 'topRight'
        });
    }else if (division == ''){
        $('#divselect').focus();
        iziToast.warning({
            title: 'Empty!',
            message: 'Division for exam is required',
            position: 'topRight'
        });
    }else if (hour == ''){
        $('#hoursel').focus();
        iziToast.warning({
            title: 'Empty!',
            message: 'HOUR/S in time limit is required',
            position: 'topRight'
        });
    }else if (minutes == ''){
        $('#minsel').focus();
        iziToast.warning({
            title: 'Empty!',
            message: 'MINUTES in time limit is required',
            position: 'topRight'
        });
    }else if (seconds == ''){
        $('#secsel').focus();
        iziToast.warning({
            title: 'Empty!',
            message: 'SECOND/S in time limit is required',
            position: 'topRight'
        });
    }else if (test_num == ''){
        $('#testI').focus();
        iziToast.warning({
            title: 'Empty!',
            message: 'Number of test is required',
            position: 'topRight'
        });
    }
    else if (test_num == '1'){
        if (testI == ''){
            $('#txt_testI').focus();
            iziToast.warning({
                title: 'Empty!',
                message: 'Test I type is required',
                position: 'topRight'
            });
        }else {
            Swal.fire({
                title: "UPDATE EXAM?",
                html: "Press continue to proceed to update your exam",
                icon: "info",
                showCancelButton: true,
                confirmButtonText: "Continue",
                cancelButtonText: "Cancel",
                closeOnConfirm: false,
            }).then((result) => {
                if (result.isConfirmed) {
                    var testII = "";
                    var testIII = "";
                    var testIV = "";
                    var testV = "";
                    $.post("queries/update_exam.php", {exam_id:exam_id, exam_title:title, exam_dep:department, exam_div:division, hours:hour, minute:minutes, second:seconds, test_number:test_num, test_1:testI, test_2:testII, test_3:testIII, test_4:testIV, test_5:testV}, function (exam){
                        if (exam == 1){
                            $.post("queries/question_option_activate.php", {exam_id:exam_id});
                            Swal.fire({
                                title: "SAVED",
                                html: "<strong> Successfully saved! </strong><br> " +
                                    "Press continue to go into question details",
                                icon: "success",
                                showCancelButton: true,
                                confirmButtonText: "Continue",
                                cancelButtonText: "Close",
                                closeOnConfirm: false,
                            }).then((result) => {
                                if (result.isConfirmed) {
                                    $.redirect('add_question.php', {'exam_id':exam_id});
                                }
                            });
                        }else if (exam == 2){
                            iziToast.error({
                                title: 'ERROR',
                                message: 'Something went wrong!',
                                position: 'topRight'
                            });
                        }else {
                            iziToast.error({
                                title: 'ERROR',
                                message: 'Something went wrong!',
                                position: 'topRight'
                            });
                        }
                    });
                }
            });
        }
    }
    else if (test_num == '2'){
        if (testI == ''){
            $('#txt_testI').focus();
            iziToast.warning({
                title: 'Empty!',
                message: 'Test I type is required is required',
                position: 'topRight'
            });
        }else if (testII == ''){
            $('#txt_testII').focus();
            iziToast.warning({
                title: 'Empty!',
                message: 'Test II type is required is required',
                position: 'topRight'
            });
        }else {
            Swal.fire({
                title: "UPDATE EXAM?",
                html: "Press continue to proceed to update your exam",
                icon: "info",
                showCancelButton: true,
                confirmButtonText: "Continue",
                cancelButtonText: "Cancel",
                closeOnConfirm: false,
            }).then((result) => {
                if (result.isConfirmed) {
                    var testIII = "";
                    var testIV = "";
                    var testV = "";
                    $.post("queries/update_exam.php", {exam_id:exam_id, exam_title:title, exam_dep:department, exam_div:division, hours:hour, minute:minutes, second:seconds, test_number:test_num, test_1:testI, test_2:testII, test_3:testIII, test_4:testIV, test_5:testV}, function (exam){
                        if (exam == 1){
                            $.post("queries/question_option_activate.php", {exam_id:exam_id});
                            Swal.fire({
                                title: "SAVED",
                                html: "<strong> Successfully saved! </strong><br>" +
                                    "Press continue to go into question details",
                                icon: "success",
                                showCancelButton: true,
                                confirmButtonText: "Continue",
                                cancelButtonText: "Close",
                                closeOnConfirm: false,
                            }).then((result) => {
                                if (result.isConfirmed) {
                                    $.redirect('add_question.php', {'exam_id':exam_id});
                                }
                            });
                        }else if (exam == 2){
                            iziToast.error({
                                title: 'ERROR',
                                message: 'Something went wrong!',
                                position: 'topRight'
                            });
                        }else {
                            iziToast.error({
                                title: 'ERROR',
                                message: 'Something went wrong!',
                                position: 'topRight'
                            });
                        }
                    });
                }
            });
        }
    }
    else if (test_num == '3'){
        if (testI == ''){
            $('#txt_testI').focus();
            iziToast.warning({
                title: 'Empty!',
                message: 'Test I type is required is required',
                position: 'topRight'
            });
        }else if (testII == ''){
            $('#txt_testII').focus();
            iziToast.warning({
                title: 'Empty!',
                message: 'Test II type is required is required',
                position: 'topRight'
            });
        }else if (testIII == ''){
            $('#txt_testIII').focus();
            iziToast.warning({
                title: 'Empty!',
                message: 'Test III type is required is required',
                position: 'topRight'
            });
        }else {
            Swal.fire({
                title: "UPDATE EXAM?",
                html: "Press continue to proceed to update your exam",
                icon: "info",
                showCancelButton: true,
                confirmButtonText: "Continue",
                cancelButtonText: "Cancel",
                closeOnConfirm: false,
            }).then((result) => {
                if (result.isConfirmed) {
                    var testIV = "";
                    var testV = "";
                    $.post("queries/update_exam.php", {exam_id:exam_id, exam_title:title, exam_dep:department, exam_div:division, hours:hour, minute:minutes, second:seconds, test_number:test_num, test_1:testI, test_2:testII, test_3:testIII, test_4:testIV, test_5:testV}, function (exam){
                        if (exam == 1){
                            $.post("queries/question_option_activate.php", {exam_id:exam_id});
                            Swal.fire({
                                title: "SAVED",
                                html: "<strong> Successfully saved! </strong><br>" +
                                    "Press continue to go into question details",
                                icon: "success",
                                showCancelButton: true,
                                confirmButtonText: "Continue",
                                cancelButtonText: "Close",
                                closeOnConfirm: false,
                            }).then((result) => {
                                if (result.isConfirmed) {
                                    $.redirect('add_question.php', {'exam_id':exam_id});
                                }
                            });
                        }else if (exam == 2){
                            iziToast.error({
                                title: 'ERROR',
                                message: 'Something went wrong!',
                                position: 'topRight'
                            });
                        }else {
                            iziToast.error({
                                title: 'ERROR',
                                message: 'Something went wrong!',
                                position: 'topRight'
                            });
                        }
                    });
                }
            });
        }
    }
    else if (test_num == '4'){
        if (testI == ''){
            $('#txt_testI').focus();
            iziToast.warning({
                title: 'Empty!',
                message: 'Test I type is required is required',
                position: 'topRight'
            });
        }else if (testII == ''){
            $('#txt_testII').focus();
            iziToast.warning({
                title: 'Empty!',
                message: 'Test II type is required is required',
                position: 'topRight'
            });
        }else if (testIII == ''){
            $('#txt_testIII').focus();
            iziToast.warning({
                title: 'Empty!',
                message: 'Test III type is required is required',
                position: 'topRight'
            });
        }else if (testIV == ''){
            $('#txt_testIV').focus();
            iziToast.warning({
                title: 'Empty!',
                message: 'Test IV type is required is required',
                position: 'topRight'
            });
        }else {
            Swal.fire({
                title: "UPDATE EXAM?",
                html: "Press continue to proceed to update your exam",
                icon: "info",
                showCancelButton: true,
                confirmButtonText: "Continue",
                cancelButtonText: "Cancel",
                closeOnConfirm: false,
            }).then((result) => {
                if (result.isConfirmed) {
                    var testV = "";
                    $.post("queries/update_exam.php", {exam_id:exam_id, exam_title:title, exam_dep:department, exam_div:division, hours:hour, minute:minutes, second:seconds, test_number:test_num, test_1:testI, test_2:testII, test_3:testIII, test_4:testIV, test_5:testV}, function (exam){
                        if (exam == 1){
                            $.post("queries/question_option_activate.php", {exam_id:exam_id});
                            Swal.fire({
                                title: "SAVED",
                                html: "<strong> Successfully saved! </strong><br>" +
                                    "Press continue to go into question details",
                                icon: "success",
                                showCancelButton: true,
                                confirmButtonText: "Continue",
                                cancelButtonText: "Close",
                                closeOnConfirm: false,
                            }).then((result) => {
                                if (result.isConfirmed) {
                                    $.redirect('add_question.php', {'exam_id':exam_id});
                                }
                            });
                        }else if (exam == 2){
                            iziToast.error({
                                title: 'ERROR',
                                message: 'Something went wrong!',
                                position: 'topRight'
                            });
                        }else if (exam == 3){
                            iziToast.error({
                                title: 'ERROR',
                                message: 'Something went wrong!',
                                position: 'topRight'
                            });
                        }
                    });
                }
            });
        }
    }
    else if (test_num == '5'){
        if (testI == ''){
            $('#txt_testI').focus();
            iziToast.warning({
                title: 'Empty!',
                message: 'Test I type is required is required',
                position: 'topRight'
            });
        }else if (testII == ''){
            $('#txt_testII').focus();
            iziToast.warning({
                title: 'Empty!',
                message: 'Test II type is required is required',
                position: 'topRight'
            });
        }else if (testIII == ''){
            $('#txt_testIII').focus();
            iziToast.warning({
                title: 'Empty!',
                message: 'Test III type is required is required',
                position: 'topRight'
            });
        }else if (testIV == ''){
            $('#txt_testIV').focus();
            iziToast.warning({
                title: 'Empty!',
                message: 'Test IV type is required is required',
                position: 'topRight'
            });
        }else if (testV == ''){
            $('#txt_testV').focus();
            iziToast.warning({
                title: 'Empty!',
                message: 'Test V type is required is required',
                position: 'topRight'
            });
        }else {
            Swal.fire({
                title: "UPDATE EXAM?",
                html: "Press continue to proceed to update your exam",
                icon: "info",
                showCancelButton: true,
                confirmButtonText: "Continue",
                cancelButtonText: "Cancel",
                closeOnConfirm: false,
            }).then((result) => {
                if (result.isConfirmed) {
                    $.post("queries/update_exam.php", {exam_id:exam_id, exam_title:title, exam_dep:department, exam_div:division, hours:hour, minute:minutes, second:seconds, test_number:test_num, test_1:testI, test_2:testII, test_3:testIII, test_4:testIV, test_5:testV}, function (exam){
                        if (exam == 1){
                            $.post("queries/question_option_activate.php", {exam_id:exam_id});
                            Swal.fire({
                                title: "SAVED",
                                html: "<strong> Successfully saved! </strong><br>" +
                                    "Press continue to go into question details",
                                icon: "success",
                                showCancelButton: true,
                                confirmButtonText: "Continue",
                                cancelButtonText: "Close",
                                closeOnConfirm: false,
                            }).then((result) => {
                                if (result.isConfirmed) {
                                    $.redirect('add_question.php', {'exam_id':exam_id});
                                }
                            });
                        }else if (exam == 2){
                            iziToast.error({
                                title: 'ERROR',
                                message: 'Something went wrong!',
                                position: 'topRight'
                            });
                        }else {
                            iziToast.error({
                                title: 'ERROR',
                                message: 'Something went wrong!',
                                position: 'topRight'
                            });
                        }
                    });
                }
            });
        }
    }
});








