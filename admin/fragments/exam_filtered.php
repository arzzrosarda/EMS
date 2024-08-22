<?php
    global $conn;
    session_start();
    require "../../db/conn.php";
    $department = $_SESSION['department'];
    $res = '';
    if (isset($_POST['division']) && isset($_POST['status'])){
        $division = $_POST['division'];
        $status = $_POST['status'];
        $exams = $conn->query("SELECT * FROM exam_title WHERE department = '$department' AND division = '$division' AND isActiveExam = '$status' AND isDeletedExam = '0'");
    }
    else if (isset($_POST['division'])){
        $division = $_POST['division'];
        $exams = $conn->query("SELECT * FROM exam_title WHERE department = '$department' AND division = '$division' AND isDeletedExam = '0'");
    }
    else if (isset($_POST['status'])){
        $status = $_POST['status'];
        $exams = $conn->query("SELECT * FROM exam_title WHERE department = '$department' AND isActiveExam = '$status' AND isDeletedExam = '0'");
    }
    else {
        $exams = $conn->query("SELECT * FROM exam_title WHERE department = '$department' AND isDeletedExam = '0'");
    }
    $res .= '<ul class="list-unstyled list-unstyled-border">';
    if ($exams->rowCount() > 0) {
        while ($exmrow = $exams->fetch(PDO::FETCH_NUM)) {
            $data[] = $exmrow;
        }
        $data = array_reverse($data, true);

        foreach($data as $exam_row){
            $exam = '';
            $tag = '';
            $btn = '';
            if ($exam_row[6] == '1'){
                $exam .= 'Activated';
                $tag .= 'info';
            }else {
                $exam .= 'InActive';
                $tag .= 'light';
            }
            $res .= '<script> 
                               $(document).ready(function (){
                                  $("#btnViewExam'.$exam_row[0].'").on("click", function (){
                                      var exam_id = $(this).attr("data-id");
                                      showModal();
                                      $("#loader").modal("show");
                                      $.post("fragments/exam_view.php", {exam_id:exam_id}, function (exam){
                                         hideModal();
                                         $("#loader").modal("hide");
                                         $("#viewExamDetailsModalContainerBody").html(exam);
                                         $("#viewExamDetailsModal").modal("show");
                                      });
                                  });
                               });
                     </script>';
            $res .= '<li class="media">
                        <i class="fas fa-tasks" style="font-size: 30px; margin-right: 10px"></i>
                        <div class="media-body">
                            <a href="javascript:;" data-id="'.$exam_row[0].'" id="btnViewExam'.$exam_row[0].'" style="text-decoration: none;">
                                <div class="badge badge-pill badge-'. $tag .' mb-1 float-right">'. $exam .'</div>
                                <h6 class="media-title">&nbsp;' . $exam_row[1]. '</h6>
                                <div class="text-small text-muted">&nbsp;Time Limit: '. $exam_row[5] .'
                                    <div class="bullet"></div>
                                    &nbsp;No. of test:
                                    <span class="text-primary">'. $exam_row[4] .'</span>
                                    </div>
                                    <div class="text-small text-muted">
                                    &nbsp;Division: 
                                    <span class="text-primary">'. $exam_row[3] .'</span>
                                </div>
                            </a>
                        </div>
                    </li>';
        }
    }
    $res .= '</ul>';
    echo $res;

