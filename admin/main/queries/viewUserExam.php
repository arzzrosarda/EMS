<?php
global $conn;
session_start();
require "../../../db/conn.php";
if (isset($_POST['userid'])){
    $result3 = '<ul class="list-group">';
    $userid = $_POST['userid'];
    $userQ = $conn->query("SELECT * FROM user WHERE id = '$userid'");
    $user = $userQ->fetch();
    $userfullname = $user['lname']. ", " . $user['fname'] . " " . $user['mname'];
    $result3 .= '<br>
                    <div class="row"><div class="col"><h5>' . $userfullname . '</h5></div></div>
                 <br>';
    $division_query = $conn->query("SELECT division FROM user_division WHERE user_id = '$userid'");
    while ($divi = $division_query->fetch()){
        $userdiv = $divi['division'];
        $titleExamQ = $conn->query("SELECT * FROM exam_title WHERE division = '$userdiv'AND isActiveExam = '1'");
        $index = 1;
        $result3 .= '
                        <li class="list-group-item mb-3">
                        <div class="row">
                        <div class="col">
                        <h6>' . $userdiv . '</h6>
                        </div>
                        </div>
                        <div class="table-responsive">
                        <table class="table table-sm mb-3 table-bordered">
                                            <thead style="font-weight: 700;">
                                                <tr>
                                                    <td>No.</td>
                                                    <td>Exam Title</td>
                                                    <td>Score</td>
                                                    <td>Status</td>
                                                    <td>Action</td>
                                                </tr>       
                                                </thead>
                                            <tbody>';
        while ($titleExam = $titleExamQ->fetch()){
            $examTitle = $titleExam['title'];
            $examID = $titleExam['id'];
            $result3 .= '<tr>
                            <td>' . $index++ . '</td>
                            <td>' . $examTitle . '</td>';
            $takenExamQ = $conn->query("SELECT * FROM exam_result WHERE examiner_id = '$userid' AND  exam_id = '$examID'");
            $scoreCorrectQ = $conn->query("SELECT SUM(points) as sum_correct FROM exam_result WHERE exam_id = '$examID' AND examiner_id = '$userid' AND correct_incorrect = 'correct' ");
            $countCorrectFetch = $scoreCorrectQ->fetch();
            $countCorrect = $countCorrectFetch['sum_correct'];
            $scoreForReviewQ = $conn->query("SELECT * FROM exam_result WHERE exam_id = '$examID' AND examiner_id = '$userid' AND correct_incorrect = 'forreview'");
            $OverallQ = $conn->query("SELECT SUM(points) as sum_score FROM question WHERE q_id = '$examID' AND active = '1'");
            $Overfetch = $OverallQ->fetch();
            $OverallPoints = $Overfetch['sum_score'];
            if ($countCorrect == '' || $countCorrect == 0 || $countCorrect == null){
                $countCorrect .= '0';
            }
            $isFinal = $conn->query("SELECT * FROM exam_result WHERE exam_id = '$examID' and examiner_id = '$userid' AND isFinal = '1'");
            if ($takenExamQ->rowCount() > 0){
                if ($scoreForReviewQ->rowCount() > 0){
                    $result3 .= '<script type="text/javascript">
                        $(document).ready(function (){
                            $(".btnForRetake").on("click", function (){
                                                     var user_id = $(this).data("id");
                                                     var exam_id = $(this).data("examid");
                                                     Swal.fire({
                                                        title: "PERMISSION TO RETAKE",
                                                        html: "Are you sure you want to give permission to this user to retake the exam?",
                                                        icon: "info",
                                                        showCancelButton: true,
                                                        confirmButtonText: "Continue",
                                                        cancelButtonText: "Cancel",
                                                        closeOnConfirm: false,
                                                    }).then((result) => {
                                                        if (result.isConfirmed) {
                                                            $("#viewExamModal").modal("hide");
                                                            $.post("../queries/retake_user_exam.php", {user_id:user_id, exam_id:exam_id}, function (ret){
                                                                if(ret == 1){
                                                                    showModal();
                                                                    $("#loader").show();
                                                                    $("#viewExamModal").modal("show");
                                                                    $.post("queries/viewUserExam.php", {userid:user_id}, function (user){
                                                                        hideModal();
                                                                        $("#loader").hide();
                                                                        $("#viewExamContainerBody").html(user);
                                                                    });
                                                                    iziToast.success({
                                                                        title: "PERMISSION GRANTED",
                                                                        position: "topRight"
                                                                    });
                                                                }else {
                                                                    iziToast.error({
                                                                        title: "ERROR",
                                                                        position: "topRight"
                                                                    });
                                                                }
                                                            });
                                                        }
                                                    });
                                                 });
                           $(".btnForReview").click(function (){
                               var eid = $(this).attr("data-examid");
                               var uid = $(this).attr("data-id");
                               $("#viewExamModal").modal("hide");
                               showModal();
                               $("#loader").show();
                               $("#viewReviewModal").modal("show");
                               $.post("queries/review.php", {userid:uid, examid:eid}, function (exam){
                                   hideModal();
                                   $("#loader").hide();
                                   $("#viewReviewContainerBody").html(exam);
                                   $("#examReviewClose").click(function (){
                                   $("#viewReviewModal").modal("hide");
                                   $("#viewExamModal").modal("show");
                                });
                               });
                           });
                         
                        });
                     </script>
                     <td class="text-center">
                                    <div class="badge badge-pill badge-warning mb-1">
                                    ' . $countCorrect . '/' . $OverallPoints . '
                                    </div>
                                 </td>';
                    $result3 .=  '<td class="text-center">
                                    <div class="badge badge-pill badge-warning mb-1">
                                        Pending Review
                                    </div>
                                  </td>';
                    $result3 .= '<td>
                                    <button type="button" class="btn btn-sm btn-warning btnForReview" data-id="' . $userid . '" data-examid="' . $examID . '">
                                    <i class="fas fa-search"></i> &nbsp;
                                        For Review
                                    </button>
                                    <button type="button" class="btn btn-sm btn-default btnForRetake" data-id="' . $userid . '" data-examid="' . $examID . '">
                                    <i class="fas fa-undo"></i> &nbsp;
                                        Retake
                                    </button>
                                 </td>';
                }
                else {
                    if ($isFinal->rowCount() > 0){
                        $result3 .= '<td class="text-center">
                                    <div class="badge badge-pill badge-info mb-1">
                                    ' . $countCorrect . '/' . $OverallPoints . '
                                    </div>
                                 </td>';
                        $result3 .=  '<td class="text-center">
                                    <div class="badge badge-pill badge-info mb-1">
                                        Completed
                                    </div>
                                  </td>';
                        $result3 .= '<td>
                                    <form action="accuracy.php" method="POST" target="_blank" id="accuracy'. $examID .'">
                                        <section>
                                            <input class="form-control" name="userid" value="' . $userid . '" type="hidden">
                                            <input class="form-control" name="full_name" value="' . $userfullname . '" type="hidden">
                                            <input class="form-control" name="exam_id" value="' . $examID . '" type="hidden">
                                            <input class="form-control" name="exam_title" value="' . $examTitle . '" type="hidden">
                                            <input class="form-control" name="user_div" value="' . $userdiv . '" type="hidden">
                                            <input class="form-control" name="count_correct" value="'.$countCorrect. '/'. $OverallPoints .'" type="hidden">
                                            <button class="btn btn-sm btn-primary" type="submit" name="submit" id="btn'. $examID .'"><i class="fas fa-external-link-alt"></i>View</button>
                                        </section>
                                    </form>
                                 </td>';
                    }else {
                        $result3 .= '
                                    <script type="text/javascript"> 
                                     $(document).ready(function (){
                                           $(".btnForRetake").on("click", function (){
                                                     var user_id = $(this).data("id");
                                                     var exam_id = $(this).data("examid");
                                                     Swal.fire({
                                                        title: "PERMISSION TO RETAKE",
                                                        html: "Are you sure you want to give permission to this user to retake the exam?",
                                                        icon: "info",
                                                        showCancelButton: true,
                                                        confirmButtonText: "Continue",
                                                        cancelButtonText: "Cancel",
                                                        closeOnConfirm: false,
                                                    }).then((result) => {
                                                        if (result.isConfirmed) {
                                                            $("#viewExamModal").modal("hide");
                                                            $.post("../queries/retake_user_exam.php", {user_id:user_id, exam_id:exam_id}, function (ret){
                                                                if(ret == 1){
                                                                    showModal();
                                                                    $("#loader").show();
                                                                    $("#viewExamModal").modal("show");
                                                                    $.post("queries/viewUserExam.php", {userid:user_id}, function (user){
                                                                        hideModal();
                                                                        $("#loader").hide();
                                                                        $("#viewExamContainerBody").html(user);
                                                                    });
                                                                    iziToast.success({
                                                                        title: "PERMISSION GRANTED",
                                                                        position: "topRight"
                                                                    });
                                                                }else {
                                                                    iziToast.error({
                                                                        title: "ERROR",
                                                                        position: "topRight"
                                                                    });
                                                                }
                                                            });
                                                        }
                                                    });
                                                 });
                                           $(".btnForFinal").on("click", function (){
                                               var user_id = $(this).data("id");
                                                     var exam_id = $(this).data("examid");
                                                     Swal.fire({
                                                        title: "FINALIZE?",
                                                        html: "Are you sure you want finalize user score in this exam?",
                                                        icon: "info",
                                                        showCancelButton: true,
                                                        confirmButtonText: "Continue",
                                                        cancelButtonText: "Cancel",
                                                        closeOnConfirm: false,
                                                    }).then((result) => {
                                                        if (result.isConfirmed) {
                                                            $("#viewExamModal").modal("hide");
                                                            $.post("../queries/finalize_user_exam.php", {user_id:user_id, exam_id:exam_id}, function (ret){
                                                                if(ret == 1){
                                                                    showModal();
                                                                    $("#loader").show();
                                                                    $("#viewExamModal").modal("show");
                                                                    $.post("queries/viewUserExam.php", {userid:user_id}, function (user){
                                                                        hideModal();
                                                                        $("#loader").hide();
                                                                        $("#viewExamContainerBody").html(user);
                                                                    });
                                                                    iziToast.success({
                                                                        title: "SCORE FINALIZED",
                                                                        position: "topRight"
                                                                    });
                                                                }else {
                                                                    iziToast.error({
                                                                        title: "ERROR",
                                                                        position: "topRight"
                                                                    });
                                                                }
                                                            });
                                                        }
                                                    });
                                           });
                                     });
                                     </script>
                                    <td class="text-center">
                                    <div class="badge badge-pill badge-info mb-1">
                                    ' . $countCorrect . '/' . $OverallPoints . '
                                    </div>
                                 </td>';
                        $result3 .= '<td class="text-center">
                                    <div class="badge badge-pill badge-info mb-1">
                                        Completed
                                    </div>
                                  </td>';
                        $result3 .= '
                                <td class="justify-content-center">
                                    <button type="button" class="btn btn-sm btn-info btnForFinal" data-id="' . $userid . '" data-examid="' . $examID . '">
                                    <i class="fas fa-check-square"></i> &nbsp;
                                        Finalize
                                    </button>
                                    <button type="button" class="btn btn-sm btn-default btnForRetake" data-id="' . $userid . '" data-examid="' . $examID . '">
                                    <i class="fas fa-undo"></i> &nbsp;
                                        Retake
                                    </button>
                                 </td>';
                    }
                }
            }else {
                $result3 .= '<td class="text-center">
                                <div class="badge badge-pill badge-light mb-1">
                                    Empty
                                </div>
                             </td>';
                $result3 .= '<td class="text-center">
                                <div class="badge badge-pill badge-light mb-1">
                                    Not yet completed
                                </div>
                             </td>';
                $result3 .= '<td>
                                <button type="button" class="btn btn-sm btn-light btnViewAccuracy" data-id="' . $userid . '" data-examid="' . $examID . '" disabled style="text-decoration: line-through;">
                                <i class="fas fa-external-link-alt"></i> &nbsp;
                                    View Accuracy
                                </button>
                             </td>';
            }
        }
        $result3 .= '</tr>
                    </tbody>
                    </table>
                    </div>
                    </li>';
    }
    $result3.= '</ul>';
    echo $result3;
}