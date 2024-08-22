<?php
global $conn;
session_start();
require "../../db/conn.php";
if (isset($_SESSION['id'])){
    $user_id = $_SESSION['id'];
    $department = $_SESSION['department'];
    if (isset($_POST['title_id'])){
        $titleID = $_POST['title_id'];
        $titleExam = $_POST['title'];
        $examresultQ = $conn->query("SELECT * FROM exam_result WHERE examiner_id = '$user_id' and exam_id = '$titleID'");
        $active_taking = $conn->query("SELECT * FROM active_take WHERE examinee_id = '$user_id' AND active = '1'");
        $active_exam_transaction = $conn->query("SELECT * FROM active_take WHERE examinee_id = '$user_id' AND exam_id = '$titleID' AND active = '1'");
        if ($examresultQ->rowCount() > 0) {
            $status = 'Completed';
            $status_tag = "primary";
            $status_link = "already_";
        } else if ($examresultQ->rowCount() == 0 && $active_exam_transaction->rowCount() > 0) {
            $status = 'Taking...';
            $status_tag = "warning";
            $status_link = "taking_";
        } else if ($active_taking->rowCount() > 0){
            $status = 'Not yet completed';
            $status_tag = "secondary";
            $status_link = "already_taking_one_exam_";
        }else {
            $status = 'Not yet completed';
            $status_tag = "info";
            $status_link = "ready_";
        } ?>
        <script>
            $(document).ready(function (){
                $("a[name=already_taking_one_exam_<?php echo $titleID; ?>]").click( function(){
                    var title = "<?php echo $titleExam; ?>";
                    Swal.fire({
                        title: title,
                        html: "You are already taking another exam, submit it first to continue with this exam",
                        icon: "info",
                        confirmButtonText: "Okay",
                        closeOnConfirm: true
                    }).then((result) => {
                        if (result.isConfirmed) {
                        }
                    });
                });
                $("a[name=taking_<?php echo $titleID; ?>]").click( function(){
                    var title = "<?php echo $titleExam; ?>";
                    Swal.fire({
                        title: title,
                        html: "You are currently taking this exam",
                        icon: "info",
                        confirmButtonText: "Okay",
                        closeOnConfirm: true
                    }).then((result) => {
                        if (result.isConfirmed) {
                        }
                    });
                });
                $("a[name=already_<?php echo $titleID; ?>]").click( function(){
                    var title = "<?php echo $titleExam; ?>";
                    Swal.fire({
                        title: title,
                        html: "You already completed this exam!!, contact the administrator for retake of the exam",
                        icon: "info",
                        confirmButtonText: "Okay",
                        closeOnConfirm: true
                    }).then((result) => {
                        if (result.isConfirmed) {
                        }
                    });
                });
                $("a[name=ready_<?php echo $titleID; ?>]").on("click",  function(){
                    var btn = $("#btnExam_<?php echo $titleID; ?>");
                    var exam_id = "<?php echo $titleID; ?>";
                    var title = "<?php echo $titleExam; ?>";
                    var insert = "";
                    Swal.fire({
                        title: title,
                        html: "Do you want to proceed to the exam?<br>" +
                            "Press continue to proceed",
                        icon: "info",
                        showCancelButton: true,
                        confirmButtonText: "Continue",
                        cancelButtonText: "Cancel",
                        closeOnCancel: true,
                        closeOnConfirm: false
                    }).then((result) => {
                        if (result.isConfirmed) {
                            $.post("queries/active_transaction.php", {insert:insert, exam_id:exam_id}, function (res){
                                if (res == 1){
                                    btn.click();
                                }
                            });
                        }
                    });
                });
            });
        </script>
        <div class="badge badge-pill badge-<?php echo $status_tag; ?> float-right"><?php echo $status; ?></div>
        <span class="media-title">
      <a href="javascript:;" name="<?php echo $status_link.$titleID; ?>">
        <?php echo  $titleExam; ?>
      </a>
    </span>
    <?php }
}


?>