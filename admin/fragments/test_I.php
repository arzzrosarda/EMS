<?php
global $conn;
session_start();
require "../../db/conn.php";
$department = $_SESSION['department'];
if (isset($_POST['exam_id'])){
    $res = '';
    $exam_id = $_REQUEST['exam_id'];
    $examQuery = $conn->query("SELECT a.`title`, a.`division`, a.`num_test`, a.`time_limit`, b.`Test_I`, b.`Test_II`, b.`Test_III`, b.`Test_IV`, b.`Test_V` FROM exam_title a LEFT JOIN exam_test b ON a.`id` = b.`exam_id` WHERE a.`id` = '$exam_id'");
    $exam_fetch = $examQuery->fetch();
    $division = $exam_fetch['division'];
    $test_I = $exam_fetch['Test_I'];
    $res .= '<script>
                $(document).ready(function (){
                   $("#inputI_btn_W").click(function (){
                        var exam_id = "'. $exam_id .'";
                        var num = $("#input_W").val();
                        var qtype = "'. $test_I .'";
                        Swal.fire({
                          title: "ADD WITH IMAGES",
                          html: "Are you sure you want add question with images?" +
                           "<p> <strong>Note: </strong>before adding question make sure to submit your update to avoid repeating the information provided</p>",
                          icon: "info",
                          showCancelButton: true,
                          confirmButtonColor: "#1c3d77",
                          confirmButtonText: "Yes, I am sure",
                          cancelButtonText: "No, Cancel it",
                          closeOnConfirm: false,
                          closeOnCancel: true
                        }).then((result) => {
                          if (result.isConfirmed) {
                              showModal();
                              $("#loader").modal("show");
                                  $.post("queries/add_question_with.php", {exam_id:exam_id, qtype:qtype, input_num:num}, function (wi){
                                      hideModal();
                                      $("#loader").modal("hide");
                                      if (wi == 1){
                                          $.post("modal/loader.php", function(load){
                                           $("#test1_form").html(load);
                                              $.post("fragments/test_I.php", {exam_id:' . $exam_id . '}, function(test1){
                                                   $("#test1_form").html(test1);
                                               });
                                           });
                                      }else if (wi == 3){
                                          $("#input_W").focus();
                                          iziToast.warning({
                                                title: "EMPTY",
                                                message: "Number of Question to add is empty",
                                                position: "topRight"
                                            });
                                      }else{
                                           iziToast.warning({
                                                title: "ERROR",
                                                message: "Something went wrong!",
                                                position: "topRight"
                                            });
                                      }
                                  });
                             }
                          });
                        
                    });
                   $("#inputI_btn_WO").click(function (){
                        var exam_id = "'. $exam_id .'";
                        var num = $("#input_WO").val();
                        var qtype = "'. $test_I .'";
                        Swal.fire({
                          title: "ADD WITHOUT IMAGES",
                          html: "Are you sure you want add question without images?" +
                           "<p> <strong>Note: </strong>before adding question make sure to submit your update to avoid repeating the information provided</p>",
                          icon: "info",
                          showCancelButton: true,
                          confirmButtonColor: "#1c3d77",
                          confirmButtonText: "Yes, I am sure",
                          cancelButtonText: "No, Cancel it",
                          closeOnConfirm: false,
                          closeOnCancel: true
                        }).then((result) => {
                          if (result.isConfirmed) {
                              showModal();
                              $("#loader").modal("show");
                                  $.post("queries/add_question_without.php", {exam_id:exam_id, qtype:qtype, input_num:num}, function (wi){
                                      hideModal();
                                      $("#loader").modal("hide");
                                      if (wi == 1){
                                          $.post("modal/loader.php", function(load){
                                           $("#test1_form").html(load);
                                              $.post("fragments/test_I.php", {exam_id:' . $exam_id . '}, function(test1){
                                                   $("#test1_form").html(test1);
                                               });
                                           });
                                      }else if (wi == 3){
                                          $("#input_WO").focus();
                                          iziToast.warning({
                                                title: "EMPTY",
                                                message: "Number of Question to add is empty",
                                                position: "topRight"
                                            });
                                      }else{
                                           iziToast.warning({
                                                title: "ERROR",
                                                message: "Something went wrong!",
                                                position: "topRight"
                                            });
                                      }
                                  });
                              }
                          });
                        
                    });
                   $("#inputI_btn").click(function (){
                        var exam_id = "'. $exam_id .'";
                        var num = $("#input").val();
                        var qtype = "'. $test_I .'";
                        Swal.fire({
                          title: "ADD QUESTION",
                          html: "Are you sure you want add question?" +
                           "<p> <strong>Note: </strong>before adding question make sure to submit your update to avoid repeating the information provided. </p>",
                          icon: "info",
                          showCancelButton: true,
                          confirmButtonColor: "#1c3d77",
                          confirmButtonText: "Yes, I am sure",
                          cancelButtonText: "No, Cancel it",
                          closeOnConfirm: false,
                          closeOnCancel: true
                        }).then((result) => {
                          if (result.isConfirmed) {
                              showModal();
                              $("#loader").modal("show");
                                  $.post("queries/add_question_without.php", {exam_id:exam_id, qtype:qtype, input_num:num}, function (wi){
                                      hideModal();
                                      $("#loader").modal("hide");
                                      if (wi == 1){
                                          $.post("modal/loader.php", function(load){
                                           $("#test1_form").html(load);
                                              $.post("fragments/test_I.php", {exam_id:' . $exam_id . '}, function(test1){
                                                   $("#test1_form").html(test1);
                                               });
                                           });
                                      }else if (wi == 3){
                                          $("#input").focus();
                                          iziToast.warning({
                                                title: "EMPTY",
                                                message: "Number of Question to add is empty",
                                                position: "topRight"
                                            });
                                      }else{
                                           iziToast.warning({
                                                title: "ERROR",
                                                message: "Something went wrong!",
                                                position: "topRight"
                                            });
                                      }
                                  });
                              }
                          });
                        
                    });
                    
                   $("#testI_title").text("'. $test_I .'"); 
                   
                   
                });
            </script>
               <input class="form-control" type="hidden" value="'. $exam_id .'" name="exam_id">
               <input class="form-control" type="hidden" value="'. $test_I .'" name="question_type">';
                                if ($test_I == 'Multiple Choice'){
                                    echo '<div class="col-xl-12 col-md-12 col-12 col-sm-12">
                                            <div class="card">
                                                <div class="card-body row">
                                                    <h6 id="testI_title" class="col-md-5"> TITLE </h6>
                                                    <div class="col-md-7 text-md-right text-right mt-2">
                                                        <div class="btn-group">
                                                            <input class="form-control" type="number" min="0" id="input_W">
                                                            <button type="button" class="btn btn-primary" id="inputI_btn_W">
                                                                With Image
                                                            </button>
                                                                &nbsp;&nbsp;
                                                            <input class="form-control" type="number" min="0" id="input_WO">
                                                            <button type="button" class="btn btn-primary" id="inputI_btn_WO">
                                                                Without Image
                                                            </button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                          </div>';
                                }else if ($test_I == 'Short Answer'){
                                    echo '<div class="col-xl-12 col-md-12 col-12 col-sm-12">
                                            <div class="card">
                                                <div class="card-body row">
                                                    <h6 id="testI_title" class="col-md-5"> TITLE </h6>
                                                    <div class="col-md-7 text-md-right text-right mt-2">
                                                        <div class="btn-group">
                                                            <input class="form-control" type="number" min="0" id="input">
                                                            <button type="button" class="btn btn-primary" id="inputI_btn">Add Question</button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                          </div>';
                                }else if ($test_I == 'True/False'){
                                    echo '<div class="col-xl-12 col-md-12 col-12 col-sm-12">
                                            <div class="card">
                                                <div class="card-body row">
                                                    <h6 id="testI_title" class="col-md-5"> TITLE </h6>
                                                    <div class="col-md-7 text-md-right text-right mt-2">
                                                        <div class="btn-group">
                                                            <input class="form-control" type="number" min="0" id="input_W">
                                                            <button type="button" class="btn btn-primary" id="inputI_btn_W">
                                                                With Image
                                                            </button>
                                                                &nbsp;&nbsp;
                                                            <input class="form-control" type="number" min="0" id="input_WO">
                                                            <button type="button" class="btn btn-primary" id="inputI_btn_WO">
                                                                Without Image
                                                            </button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                          </div>';
                                }else if ($test_I == 'Essay'){
                                    echo '<div class="col-xl-12 col-md-12 col-12 col-sm-12">
                                            <div class="card">
                                                <div class="card-body row">
                                                    <h6 id="testI_title" class="col-md-5"> TITLE </h6>
                                                    <div class="col-md-7 text-md-right text-right mt-2">
                                                        <div class="btn-group">
                                                            <input class="form-control" type="number" min="0" id="input">
                                                            <button type="button" class="btn btn-primary" id="inputI_btn">Add Question</button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                          </div>';
                                }else if ($test_I == 'Multiple Image'){
                                    echo '<div class="col-xl-12 col-md-12 col-12 col-sm-12">
                                            <div class="card">
                                                <div class="card-body row">
                                                    <h6 id="testI_title" class="col-md-5"> TITLE </h6>
                                                    <div class="col-md-7 text-md-right text-right mt-2">
                                                        <div class="btn-group">
                                                            <input class="form-control" type="number" min="0" id="input_W">
                                                            <button type="button" class="btn btn-primary" id="inputI_btn_W">
                                                                Add Question
                                                            </button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                          </div>';
                                }
                                echo '<br>';
    $question_option_query = $conn->query("SELECT a.`id` AS question_no, a.`points`, a.`question_type`, a.`question`,
                                                 b.`id` AS option_id, b.`with_without`,b.`option_1`, b.`option_2`, b.`option_3`, b.`option_4`, b.`img_1`, b.`img_2`, b.`img_3`, b.`img_4`, b.`ans` 
                                                 FROM question a 
                                                 LEFT JOIN options b ON a.`id` = b.`id`
                                                 WHERE q_id = '$exam_id' AND o_id = '$exam_id' AND a.`question_type` = '$test_I'");
    $index = 1;
    $title = 1;
    while($qo = $question_option_query->fetch()){
        $res .= '<script>
                    $(document).ready(function (){
                        
                        $("input[type=file]").each(function(){
                        var $file = $(this),
                            $label = $file.next("label"),
                            $labelText = $label.find("span"),
                            labelDefault = $labelText.text();
                            
                        $file.on("change", function(event){
                        
                        if(this.files[0].size > 2000000){
                            Swal.fire("IMAGE", "Please upload file less than 2MB. Thanks!!", "info");
                        }else {
                            var fileName = $file.val().split("\\"" ).pop(),
                                tmppath = URL.createObjectURL(event.target.files[0]);
                            if( fileName ){
                                $label
                                    .addClass("file-ok")
                                    .css("background-image", "url(' . '"' . '+ tmppath +' . '"'.')");
                            }else{
                                $label.removeClass("file-ok");
                                $labelText.text(labelDefault);
                            }
                        }
                            
                        });
                    });
                        $("#del_'. $qo['option_id'] .'").click( function() {
                            var question_id = "'. $qo['option_id'] .'";
                            var examid = "'. $exam_id .'";
                            var department = "'.$department.'";
                            var division = "'.$division.'";
                            var question_type = "'.$qo['question_type'].'";
                            Swal.fire({
                                title: "DELETE QUESTION?",
                                html: "Are you sure you want to delete question no. '. $title++ .'?" +
                                    "<strong>Note: </strong>Before proceeding to delete question make sure to save your work first to prevent repeating of your work",
                                icon: "warning",
                                showCancelButton: true,
                                confirmButtonColor: "#1c3d77",
                                confirmButtonText: "Yes, Sure",
                                cancelButtonText: "No, Cancel",
                                closeOnConfirm: false,
                            }).then((result) => {
                                if (result.isConfirmed) {
                                    showModal();
                                    $("#loader").modal("show");
                                    $.post("queries/delete_question.php", {exam_id:examid, quest_id:question_id, department:department, division:division, question_type:question_type}, function(del){
                                        hideModal();
                                        $("#loader").modal("hide");
                                        if(del == 1){
                                           $.post("modal/loader.php", function(load){
                                           $("#test1_form").html(load);
                                              $.post("fragments/test_I.php", {exam_id:' . $exam_id . '}, function(test1){
                                                   $("#test1_form").html(test1);
                                               });
                                           });
                                        }else {
                                            iziToast.error({
                                                title: "ERROR",
                                                message: "Something went wrong!",
                                                position: "topCenter"
                                            });
                                        }
                                    });
                                }
                            });
                    
                        });
                        var option_1 = $("#option1_'. $qo['option_id'].'").val();
                        $("#customRadio1_'. $qo['option_id'].'").val(option_1);
                    
                        var option_2 = $("#option2_'. $qo['option_id'].'").val();
                        $("#customRadio2_'. $qo['option_id'].'").val(option_2);
                    
                        var option_3 = $("#option3_'. $qo['option_id'].'").val();
                        $("#customRadio3_'. $qo['option_id'].'").val(option_3);
                    
                        var option_4 = $("#option4_'. $qo['option_id'].'").val();
                        $("#customRadio4_'. $qo['option_id'].'").val(option_4);
                    
                        $("input[name=ans_'. $qo['option_id'].']").change( function (){
                            if ($(this).is(":checked")){
                                var checked = $(this).val();
                                $("#ans_'. $qo['option_id'] .'").val(checked);
                            }
                        });
                        
                        $("#option4_'. $qo['option_id'].'").on("change", function(){
                            var option_4 = $("#option4_'. $qo['option_id'].'").val();
                            $("#customRadio4_'. $qo['option_id'].'").val(option_4);
                        });
                        $("#option1_'. $qo['option_id'].'").on("change", function(){
                            var option_1 = $("#option1_'. $qo['option_id'].'").val();
                            $("#customRadio1_'. $qo['option_id'].'").val(option_1);
                        });
                        $("#option2_'. $qo['option_id'].'").on("change", function(){
                            var option_2 = $("#option2_'. $qo['option_id'].'").val();
                            $("#customRadio2_'. $qo['option_id'].'").val(option_2);
                        });
                        $("#option3_'. $qo['option_id'].'").on("change", function(){
                            var option_3 = $("#option3_'. $qo['option_id'].'").val();
                            $("#customRadio3_'. $qo['option_id'].'").val(option_3);
                        });
                        
                        var ans = "' . $qo['ans'] . '";
                        if (option_1 == ans){
                            $("#customRadio1_'. $qo['option_id'].'").attr("checked", true);
                        }else if (option_2 == ans){
                            $("#customRadio2_'. $qo['option_id'].'").attr("checked", true);
                        }else if (option_3 == ans){
                            $("#customRadio3_'. $qo['option_id'].'").attr("checked", true);
                        }else if (option_4 == ans){
                            $("#customRadio4_'. $qo['option_id'].'").attr("checked", true);
                        }
                        
                        if (ans == "True"){
                             $("#TF1_'. $qo['option_id'] .'").attr("checked", true);
                        }else if(ans == "False"){
                            $("#TF2_'. $qo['option_id'] .'").attr("checked", true);
                        }
                        var radio1 = $("#Radio1_'. $qo['option_id'].'");
                        var radio2 = $("#Radio2_'. $qo['option_id'].'");
                        var radio3 = $("#Radio3_'. $qo['option_id'].'");
                        var radio4 = $("#Radio4_'. $qo['option_id'].'");
                        if (radio1.val() == ans){
                            radio1.attr("checked", true);
                        }else if (radio2.val() == ans){
                            radio2.attr("checked", true);
                        }else if (radio3.val() == ans){
                            radio3.attr("checked", true);
                        }else if (radio4.val() == ans){
                            radio4.attr("checked", true);
                        }
                    });
                 </script>
                  <div class="col-xl-12 col-md-12 col-12 col-sm-12">
                      <div class="card col-xl-12">
                            <div class="card-header">
                            <h4 class="d-inline">No. ' . $index++ . '</h4>
                            <div class="card-header-action">
                                <input class="form-control" type="hidden" value="'. $qo['with_without'] .'" name="with_without'. $qo['option_id'] .'">
                                <button type="button" class="btn btn-danger" id="del_'. $qo['option_id'] .'" data-option_id="'. $qo['option_id'] .'" ><i class="fas fa-trash-alt"></i> </button>
                            </div>
                            </div>';
        if ($test_I == "Multiple Choice"){
            $res .= '<div class="card-body row">
                        <div class="form-group row align-items-center">
                            <label class="col-xl-10 text-md-right text-left">Points: <label style="color:red;">*</label></label>
                            <div class="col-xl-2 col-md-6">
                                <input type="number" name="points_' . $qo['option_id'] . '"class="form-control" min="0" value="'. $qo['points'] .'" required>
                            </div>
                             <label class="col-xl-12 text-md-right text-left"></label>
                            <label class="col-xl-2 text-md-right text-left">Question: <label style="color:red;">*</label></label>
                            <div class="col-xl-10 col-md-6">
                                <textarea id="question_' . $qo['option_id'] . '" name="question_' . $qo['option_id'] . '"class="form-control" placeholder="Type here your question...." required>'. $qo['question'] .'</textarea>
                            </div>';
            if ($qo['with_without'] == '1') {
                $res .= '<label class="col-xl-12"></label>
                         <label class="col-xl-2 text-md-right text-left">Images</label>
                        <div class="col-xl-10 col-md-6">
                            <div class="wrap-custom-file">
                                <input type="file" name="image1_' . $qo['option_id'] . '" id="image1-' . $qo['option_id'] . '" accept="image/*" />
                                <label  for="image1-' . $qo['option_id'] . '" class="file-ok" style="background-image: url('. "'../assets/uploads/".$department."/".$division."/".$exam_id."/".$qo['question_type']."/".$qo['option_id']."/"."/option1/".$qo['img_1']."'".')">
                                    <span>I</span>
                                </label>
                            </div>
                            <div class="wrap-custom-file">
                                <input type="file" name="image2_' . $qo['option_id'] . '" id="image2-' . $qo['option_id'] . '" accept="image/*" />
                                <label for="image2-' . $qo['option_id'] . '" class="file-ok" style="background-image: url('. "'../assets/uploads/".$department."/".$division."/".$exam_id."/".$qo['question_type']."/".$qo['option_id']."/"."/option2/".$qo['img_2']."'".')">
                                <span>II</span>
                                </label>
                            </div>
                            <div class="wrap-custom-file">
                                <input type="file" name="image3_' . $qo['option_id'] . '" id="image3-' . $qo['option_id'] . '" accept="image/*" />
                                <label for="image3-' . $qo['option_id'] . '" class="file-ok" style="background-image: url('. "'../assets/uploads/".$department."/".$division."/".$exam_id."/".$qo['question_type']."/".$qo['option_id']."/"."/option3/".$qo['img_3']."'".')">
                                <span>III</span>
                                </label>
                            </div>
                            <div class="wrap-custom-file">
                                <input type="file" name="image4_' . $qo['option_id'] . '" id="image4-' . $qo['option_id'] . '" accept="image/*" />
                                <label for="image4-' . $qo['option_id'] . '" class="file-ok" style="background-image: url('. "'../assets/uploads/".$department."/".$division."/".$exam_id."/".$qo['question_type']."/".$qo['option_id']."/"."/option4/".$qo['img_4']."'".')">
                                <span>IV</span>
                                </label>
                            </div>
                        </div>';
            }$res .= '<label class="col-xl-12"></label>
                        <label class="col-xl-2 text-md-right text-left">Option 1: <label style="color:red;">*</label></label>
                        <div class="custom-control custom-radio col-xl-10 col-md-6 text-md-center">
                            <input type="radio" id="customRadio1_' . $qo['option_id'] . '" name="ans_' . $qo['option_id'] . '" class="custom-control-input col-xl-2" required>
                            <label class="custom-control-label col" for="customRadio1_' . $qo['option_id'] . '" >
                                <input class="form-control" id="option1_' . $qo['option_id'] . '" name="option1_' . $qo['option_id'] . '" type="text" value="' . $qo['option_1'] . '" required>
                            </label>
                        </div>
                        <label class="col-xl-12 text-md-right text-left"></label>
                        <label class="col-xl-2 text-md-right text-left">Option 2: <label style="color:red;">*</label></label>
                        <div class="custom-control custom-radio col-xl-10 col-md-6 text-md-center">
                            <input type="radio" id="customRadio2_' . $qo['option_id'] . '" name="ans_' . $qo['option_id'] . '" class="custom-control-input col-xl-2" required>
                            <label class="custom-control-label col" for="customRadio2_' . $qo['option_id'] . '" >
                                <input class="form-control" id="option2_' . $qo['option_id'] . '" name="option2_' . $qo['option_id'] . '" type="text" value="' . $qo['option_2'] . '" required> 
                            </label>
                        </div>
                        <label class="col-xl-12 text-md-right text-left"></label>
                        <label class="col-xl-2 text-md-right text-left">Option 3: <label style="color:red;">*</label></label>
                        <div class="custom-control custom-radio col-xl-10 col-md-6 text-md-center">
                            <input type="radio" id="customRadio3_' . $qo['option_id'] . '" name="ans_' . $qo['option_id'] . '" class="custom-control-input col-xl-2" required>
                            <label class="custom-control-label col" for="customRadio3_' . $qo['option_id'] . '" >
                                <input class="form-control" id="option3_' . $qo['option_id'] . '" name="option3_' . $qo['option_id'] . '" type="text" value="' . $qo['option_3'] . '" required>
                            </label>
                        </div>
                        <label class="col-xl-12 text-md-right text-left"></label>
                        <label class="col-xl-2 text-md-right text-left">Option 4: <label style="color:red;">*</label></label>
                        <div class="custom-control custom-radio col-xl-10 col-md-6 text-md-center">
                            <input type="radio" id="customRadio4_' . $qo['option_id'] . '" name="ans_' . $qo['option_id'] . '" class="custom-control-input col-xl-2" required>
                            <label class="custom-control-label col" for="customRadio4_' . $qo['option_id'] . '" >
                                <input class="form-control" id="option4_' . $qo['option_id'] . '" name="option4_' . $qo['option_id'] . '" type="text" value="' . $qo['option_4'] . '" required>
                            </label>
                        </div>
                        <label class="col-xl-12 text-md-right text-left"></label>
                        <label class="col-xl-2 text-md-right text-left">Answer: <label style="color:red;">*</label></label>
                        <div class="col-xl-10 col-md-6">
                            <input class="form-control " id="ans_' . $qo['option_id'] . '" name="answer_' . $qo['option_id'] . '" type="text" value="' . $qo['ans'] . '" disabled required>
                        </div>
                        </div>
                        </div>
                        </div>
                        </div>';
        }
        else if ($test_I == "Short Answer"){
            $res .= '<div class="card-body row">
                        <div class="form-group row align-items-center">
                            <label class="col-xl-10 text-md-right text-left">Points: <label style="color:red;">*</label></label>
                                <div class="col-xl-2 col-md-6">
                                    <input type="number" name="points_' . $qo['option_id'] . '"class="form-control" min="0" value="'. $qo['points'] .'" required>
                                </div>
                            <label class="col-xl-12 text-md-right text-left"></label>
                            <label class="col-xl-2 text-md-right text-right">Question: <label style="color:red;">*</label></label>
                            <div class="col-xl-10 col-md-6">
                                <textarea id="question_' . $qo['option_id'] . '" name="question_' . $qo['option_id'] . '"class="form-control" placeholder="Type here your question...." required>'. $qo['question'] .'</textarea>
                            </div>
                        <label class="col-xl-12 text-md-right text-right"></label>
                        <label class="col-xl-2 text-md-right text-right">Answer: <label style="color:red;">*</label></label>
                        <div class="col-xl-10">
                            <input class="form-control " id="answer" name="ans_' . $qo['option_id'] . '" oninput="this.value = this.value.toUpperCase()" type="text" value="' . $qo['ans'] . '" required>
                        </div>
                        </div>
                        </div>
                        </div>
                        </div>';
        }
        else if ($test_I == "True/False"){
            $res .= '<div class="card-body row">
                        <div class="form-group row align-items-center">
                            <label class="col-xl-10 text-md-right text-left">Points: <label style="color:red;">*</label></label>
                            <div class="col-xl-2 col-md-6">
                                <input type="number" name="points_' . $qo['option_id'] . '"class="form-control" min="0" value="'. $qo['points'] .'" required>
                            </div>
                             <label class="col-xl-12 text-md-right text-left"></label>
                            <label class="col-xl-2 text-md-right text-left">Question: <label style="color:red;">*</label></label>
                            <div class="col-xl-10 col-md-6">
                                <textarea id="question_' . $qo['option_id'] . '" name="question_' . $qo['option_id'] . '"class="form-control" placeholder="Type here your question...." required>'. $qo['question'] .'</textarea>
                            </div>';
            if ($qo['with_without'] == '1') {
                $res .= '<label class="col-xl-12"></label>
                         <label class="col-xl-2 text-md-right text-left">Images: </label>
                        <div class="col-xl-10 col-md-6">
                            <div class="wrap-custom-file">
                                <input type="file" name="image1_' . $qo['option_id'] . '" id="image1-' . $qo['option_id'] . '" accept="image/*" />
                                <label  for="image1-' . $qo['option_id'] . '" class="file-ok" style="background-image: url('. "'../assets/uploads/".$department."/".$division."/".$exam_id."/".$qo['question_type']."/".$qo['option_id']."/"."/option1/".$qo['img_1']."'".')">
                                <span>I</span>
                                </label>
                            </div>
                            <div class="wrap-custom-file">
                                <input type="file" name="image2_' . $qo['option_id'] . '" id="image2-' . $qo['option_id'] . '" accept="image/*" />
                                <label for="image2-' . $qo['option_id'] . '" class="file-ok" style="background-image: url('. "'../assets/uploads/".$department."/".$division."/".$exam_id."/".$qo['question_type']."/".$qo['option_id']."/"."/option2/".$qo['img_2']."'".')">
                                <span>II</span>
                                </label>
                            </div>
                            <div class="wrap-custom-file">
                                <input type="file" name="image3_' . $qo['option_id'] . '" id="image3-' . $qo['option_id'] . '" accept="image/*" />
                                <label for="image3-' . $qo['option_id'] . '" class="file-ok" style="background-image: url('. "'../assets/uploads/".$department."/".$division."/".$exam_id."/".$qo['question_type']."/".$qo['option_id']."/"."/option3/".$qo['img_3']."'".')">
                                <span>III</span>
                                </label>
                            </div>
                            <div class="wrap-custom-file">
                                <input type="file" name="image4_' . $qo['option_id'] . '" id="image4-' . $qo['option_id'] . '" accept="image/*" />
                                <label for="image4-' . $qo['option_id'] . '" class="file-ok" style="background-image: url('. "'../assets/uploads/".$department."/".$division."/".$exam_id."/".$qo['question_type']."/".$qo['option_id']."/"."/option4/".$qo['img_4']."'".')">
                                <span>IV</span>
                                </label>
                            </div>
                        </div>';
            }$res .= '<label class="col-xl-12"></label>
                        <label class="col-xl-3 text-md-right text-left"></label>
                        <div class="custom-control custom-radio col-xl-9 col-md-6">
                            <input type="radio" id="TF1_' . $qo['option_id'] . '" name="ans_' . $qo['option_id'] . '" class="custom-control-input col-xl-2" value="True" required>
                            <label class="custom-control-label col" for="TF1_' . $qo['option_id'] . '">
                            TRUE
                            </label>
                        </div>
                        <label class="col-xl-12 text-md-right text-left"></label>
                        <label class="col-xl-3 text-md-right text-left"></label>
                        <div class="custom-control custom-radio col-xl-9 col-md-6">
                            <input type="radio" id="TF2_' . $qo['option_id'] . '" name="ans_' . $qo['option_id'] . '" class="custom-control-input col-xl-2" Value="False" required>
                            <label class="custom-control-label col" for="TF2_' . $qo['option_id'] . '" >
                            FALSE
                            </label>
                        </div>
                        </div>
                        </div>
                        </div>
                        </div>';
        }
        else if ($test_I == "Essay"){
            $res .= '<div class="card-body row">
                        <div class="form-group row align-items-center">
                            <label class="col-xl-10 text-md-right text-left">Points: <label style="color:red;">*</label></label>
                                <div class="col-xl-2 col-md-6">
                                    <input type="number" name="points_' . $qo['option_id'] . '"class="form-control" min="0" value="'. $qo['points'] .'" required>
                                </div>
                            <label class="col-xl-12 text-md-right text-left"></label>
                            <label class="col-xl-2 text-md-right text-right">Question: <label style="color:red;">*</label></label>
                            <div class="col-xl-10 col-md-6">
                                <textarea id="question_' . $qo['option_id'] . '" name="question_' . $qo['option_id'] . '"class="form-control" placeholder="Type here your question...." required>'. $qo['question'] .'</textarea>
                            </div>
                        <label class="col-xl-12 text-md-right text-right"></label>
                        <label class="col-xl-2 text-md-right text-right"></label>
                        <div class="col-xl-10">
                            <input class="form-control " name="ans_' . $qo['option_id'] . '" type="hidden" value="forreview" required>
                        </div>
                        </div>
                        </div>
                        </div>
                        </div>';
        }
        else if ($test_I == "Multiple Image"){
            $res .= '<script>
                        $(document).ready(function (){
                                var points = "'.$qo['points'].'";
                                if(points != "0"){
                                    $("#image1-' . $qo['option_id'] . '").removeAttr("required");
                                    $("#image2-' . $qo['option_id'] . '").removeAttr("required");
                                    $("#image3-' . $qo['option_id'] . '").removeAttr("required");
                                    $("#image4-' . $qo['option_id'] . '").removeAttr("required");
                                }
                        });
                    </script>';
            $res .= '<div class="card-body row">
                        <div class="form-group row align-items-center">
                            <label class="col-xl-10 text-md-right text-left">Points: <label style="color:red;">*</label></label>
                            <div class="col-xl-2 col-md-6">
                                <input type="number" name="points_' . $qo['option_id'] . '" class="form-control" min="0" value="'. $qo['points'] .'" required>
                            </div>
                             <label class="col-xl-12 text-md-right text-left"></label>
                            <label class="col-xl-2 text-md-right text-left">Question: <label style="color:red;">*</label></label>
                            <div class="col-xl-10 col-md-6">
                                <textarea id="question_' . $qo['option_id'] . '" name="question_' . $qo['option_id'] . '"class="form-control" placeholder="Type here your question...." required>'. $qo['question'] .'</textarea>
                            </div>';
                $res .= '<label class="col-xl-12"></label>
                         <label class="col-xl-2 text-md-right text-left">Images</label>
                        <div class="col-xl-10 col-md-6">
                            <div class="wrap-custom-file">
                                <input type="file" name="image1_' . $qo['option_id'] . '" id="image1-' . $qo['option_id'] . '" accept="image/*" required/>
                                <label  for="image1-' . $qo['option_id'] . '" class="file-ok" style="background-image: url('. "'../assets/uploads/".$department."/".$division."/".$exam_id."/".$qo['question_type']."/".$qo['option_id']."/"."/option1/".$qo['img_1']."'".')">
                                <span>I</span>
                                </label>
                            </div>
                            <div class="wrap-custom-file">
                                <input type="file" name="image2_' . $qo['option_id'] . '" id="image2-' . $qo['option_id'] . '" accept="image/*" required/>
                                <label for="image2-' . $qo['option_id'] . '" class="file-ok" style="background-image: url('. "'../assets/uploads/".$department."/".$division."/".$exam_id."/".$qo['question_type']."/".$qo['option_id']."/"."/option2/".$qo['img_2']."'".')">
                                <span>II</span>
                                </label>
                            </div>
                            <div class="wrap-custom-file">
                                <input type="file" name="image3_' . $qo['option_id'] . '" id="image3-' . $qo['option_id'] . '" accept="image/*" required/>
                                <label for="image3-' . $qo['option_id'] . '" class="file-ok" style="background-image: url('. "'../assets/uploads/".$department."/".$division."/".$exam_id."/".$qo['question_type']."/".$qo['option_id']."/"."/option3/".$qo['img_3']."'".')">
                                <span>III</span>
                                </label>
                            </div>
                            <div class="wrap-custom-file">
                                <input type="file" name="image4_' . $qo['option_id'] . '" id="image4-' . $qo['option_id'] . '" accept="image/*" required/>
                                <label for="image4-' . $qo['option_id'] . '" class="file-ok" style="background-image: url('. "'../assets/uploads/".$department."/".$division."/".$exam_id."/".$qo['question_type']."/".$qo['option_id']."/"."/option4/".$qo['img_4']."'".')">
                                <span>IV</span>
                                </label>
                            </div>
                        </div>
                        <label class="col-xl-12"></label>
                        <label class="col-xl-3 text-md-center text-center">Answer: <label style="color:red;">*</label></label>
                        <div class="custom-control custom-radio col-xl-3 col-md-6">
                            <input type="radio" id="Radio1_' . $qo['option_id'] . '" name="ans_' . $qo['option_id'] . '" value="'. $qo['option_1'] .'" class="custom-control-input col-xl-2" required>
                            <label class="custom-control-label col" for="Radio1_' . $qo['option_id'] . '" >
                                I
                            </label>
                        </div>
                        <label class="col-xl-2 text-md-right text-left"></label>
                        <div class="custom-control custom-radio col-xl-3 col-md-6">
                            <input type="radio" id="Radio2_' . $qo['option_id'] . '" name="ans_' . $qo['option_id'] . '"  value="'. $qo['option_2'] .'" class="custom-control-input col-xl-2" required>
                            <label class="custom-control-label col" for="Radio2_' . $qo['option_id'] . '" >
                                II
                            </label>
                        </div>
                        <label class="col-xl-12 text-md-right text-left"></label>
                        <label class="col-xl-3 text-md-right text-left"></label>
                        <div class="custom-control custom-radio col-xl-3 col-md-6">
                            <input type="radio" id="Radio3_' . $qo['option_id'] . '" name="ans_' . $qo['option_id'] . '"  value="'. $qo['option_3'] .'" class="custom-control-input col-xl-2" required>
                            <label class="custom-control-label col" for="Radio3_' . $qo['option_id'] . '" >
                                III
                            </label>
                        </div>
                        <label class="col-xl-2 text-md-right text-left"></label>
                        <div class="custom-control custom-radio col-xl-3 col-md-6 ">
                            <input type="radio" id="Radio4_' . $qo['option_id'] . '" name="ans_' . $qo['option_id'] . '"  value="'. $qo['option_4'] .'" class="custom-control-input col-xl-2" required>
                            <label class="custom-control-label col" for="Radio4_' . $qo['option_id'] . '" >
                                IV
                            </label>
                        </div>
                        </div>
                        </div>
                        </div>
                        </div>';
        }
    }
    echo $res;
}