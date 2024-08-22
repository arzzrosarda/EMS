<?php
global $conn;
session_start();
    require "../../db/conn.php";
    if (isset($_REQUEST['userid'])){
        $result1 = '';
        $index = 1;
        $userid = $_REQUEST['userid'];
        $examid = $_REQUEST['examid'];
        $userQ = $conn->query("SELECT * FROM user WHERE id = '$userid'");
        $fetchUser = $userQ->fetch();
        $userFname = $fetchUser['lname'] . ", " . $fetchUser['fname'] . " " . $fetchUser['mname'];
        $ExamQuestOptQuery = $conn->query("SELECT a.`id`, a.`title`,
                    b.`q_id`, b.`points`, b.`question_type`, b.`question`,
                    c.`o_id`, c.`option_1`, c.`option_2`, c.`option_3`, c.`option_4`, c.`img_1`, c.`img_2`, c.`img_3`, c.`img_4`, c.`ans`,
                    d.`q_no`, d.`ans` AS result_ans,
                    d.`examiner_id`
                    FROM `exam_title` a
                    LEFT JOIN `question` b ON a.`id` = b.`q_id` 
                    LEFT JOIN `options` c ON b.`id` = c.`id` 
                    LEFT JOIN `exam_result` d ON b.`id` = d.`q_no`
                    WHERE d.`correct_incorrect` = 'forreview' AND a.`id` = '$userid' AND d.`examiner_id` = '$userid'");
        if ($ExamQuestOptQuery->rowCount() > 0 ){
            while ($ExamQuestOpt = $ExamQuestOptQuery->fetch()){
                $result1 .= '        
                     <div class="row">
                        <div class="col">
                            <div class="form-wrapper" >
                                 <div class="row" style="display: flex;">
                                     <section><span class="question-no">' . $index++ . ' .'.'</span></section>
                                     <div class="col">
                                        <section>   
                                        <span class="question">' . $ExamQuestOpt['question'] . ' <br><span style="color: red;">' . '( ' . $ExamQuestOpt['points'] . ' Point/s )' . '</span>'. '</span></section>
                                        <textarea style="margin: 20px 0 20px 0;" id="txtAns" class="form-control" rows="3" name="' . $ExamQuestOpt['q_no'] . '" disabled>' . $ExamQuestOpt['result_ans'] .'</textarea>
                                        <div class="col">
                                            <div class="row" style="margin: 20px;">
                                                <section><label >Score: &nbsp;</label></section>
                                               <section style="text-align: center;">
                                               <input type="number" class="form-control " id="' . $ExamQuestOpt['q_no'] . '" onKeyDown="return false" min="0" max="'. $ExamQuestOpt['points'] .'">';
                                        $result1 .= '<button type="button" class="btn btn-sm btn-primary btnSubReview' . $ExamQuestOpt['q_no'] . '" 
                                        data-qno="'. $ExamQuestOpt['q_no'] .'"
                                        data-examid="'. $examid .'" 
                                        data-userid="' . $userid . '"
                                        data-uid="'.$userid . '" style="margin: 15px 0 0 0;">Submit</button>
                                               </section>
                                            </div>
                                        </div>
                                     </div>
                                 </div>
                            </div>
                        </div>
                     </div>
                     <script>
                                $(document).ready(function (){
                                    $(".btnSubReview' . $ExamQuestOpt['q_no'] . '").click(function (){
                                        var qno = $(this).attr("data-qno");
                                        var eid = $(this).attr("data-examid");
                                        var user = $(this).attr("data-userid");
                                        var score = $("#' . $ExamQuestOpt['q_no'] . '").val();
                                        var uid = $(this).attr("data-uid");
                                        showModal();
                                        $("#loader").show();
                                        if (score == "" || score == null){
                                            hideModal();
                                            $("#loader").hide();
                                            $("#' . $ExamQuestOpt['q_no'] . '").focus();
                                            iziToast.warning({
                                                title: "Empty!",
                                                message: "Please put a score in the score field to continue!",
                                                position: "topRight"
                                            });
                                        }else {
                                            hideModal();
                                            $("#loader").hide();
                                            Swal.fire({
                                                title: "SAVE",
                                                html: "Press continue to save the score",
                                                icon: "info",
                                                showCancelButton: true,
                                                confirmButtonColor: "#1c3d77",
                                                confirmButtonText: "Continue",
                                                cancelButtonText: "Cancel",
                                                closeOnCancel: true,
                                                closeOnConfirm: false
                                                }).then((result) => {
                                                    if (result.isConfirmed) {
                                                        showModal();
                                                        $("#loader").show();
                                                        $.post("queries/subreview.php", {quest:qno, sc:score, exam_id:eid, euser:user}, function (ques){
                                                            hideModal();
                                                            $("#loader").hide();
                                                            if (ques == 1){
                                                                Swal.fire({
                                                                    title: "SAVED",
                                                                    html: "Score Successfully Saved!",
                                                                    icon: "success",
                                                                    confirmButtonColor: "#1c3d77",
                                                                    confirmButtonText: "Ok",
                                                                    allowOutsideClick: false,
                                                                    closeOnConfirm: false
                                                                    }).then((result) => {
                                                                        if (result.isConfirmed) {
                                                                               showModal();
                                                                               $("#loader").show();
                                                                               $.post("queries/review.php", {userid:uid, examid:eid}, function (exam){
                                                                                   hideModal();
                                                                                   $("#loader").hide();
                                                                                   if (exam == 1){
                                                                                       $("#viewReviewModal").modal("hide");
                                                                                       $("#viewExamModal").modal("show");
                                                                                        $.post("queries/viewUserExam.php", {userid:uid}, function (user){
                                                                                            $("#viewExamContainerBody").html(user);
                                                                                        });
                                                                                   }else {
                                                                                       $("#viewReviewContainerBody").html(exam);
                                                                                       $("#examReviewClose").click(function (){
                                                                                           $("#viewReviewModal").modal("hide");
                                                                                           $("#viewExamModal").modal("show");
                                                                                       });
                                                                                   }
                                                                               });
                                                                            }
                                                                        });
                                                            }else if (ques == 2){
                                                                Swal.fire("ERROR!", "Something went wrong!", "error");
                                                            }
                                                        });
                                                    }
                                                });
                                            
                                        }
                                            
                                    });
                                });
                            </script>    ';
            }
        }else {
            $result1 = 1;
        }
        echo $result1;
    }