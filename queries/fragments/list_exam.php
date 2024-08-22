<?php
global $conn;
session_start();
require "../../db/conn.php";
$email = $_SESSION['user'];
$department = $_SESSION['department'];
$user_id = $_SESSION['id'];
$user_division = $conn->query("SELECT * FROM `user_division` WHERE user_id = '$user_id'");
while ($user_div = $user_division->fetch()){
    $division = $user_div['division']; ?>


    <div class="list-group">
        <p class="m-2"><?php echo $division; ?></p>

        <?php $exams = $conn->query("SELECT * FROM exam_title WHERE division = '$division' AND isActiveExam = '1' AND department = '$department'");
        if ($exams->rowCount() > 0){
        while ($exmrow = $exams->fetch()) {
            $titleID = $exmrow['id'];
            $titleExam = $exmrow['title'];
            $titleTime = $exmrow['time_limit'];
            $titleTest = $exmrow['num_test']; ?>
            <script>
                $(document).ready(function (){
                    setInterval(function (){
                        var title = "<?php echo $titleExam; ?>";
                        var title_id = "<?php echo $titleID; ?>";
                        $.post("queries/fragments/exam_tag.php", {title:title, title_id:title_id}, function (load){
                            $("#status_container_<?php echo $titleID; ?>").html(load);
                        });
                    }, 1000);
                });
            </script>
            <form action="exam.php" method="POST" target="_blank" id="exam_<?php echo $titleID; ?>">
                <input class="form-control" type="hidden" name="examid" value="<?php echo $titleID; ?>">
                <button class="btn btn-primary btn-sm" type="submit" style="display: none;" id="btnExam_<?php echo $titleID; ?>">Submit</button>
            </form>
            <div class="list-group-item">
                <div id="status_container_<?php echo $titleID; ?>">

                </div>
                <div class="text-small text-muted">Time Limit: <?php echo  $titleTime; ?>
                    <div class="bullet"></div>&nbsp; No. of test:
                    <span class="text-primary">
                        <?php echo  $titleTest; ?>
                    </span>
                </div>
            </div>

        <?php }
        }else {?>
            <div class="list-group-item">
                <div class="badge badge-pill badge-light float-right">Empty</div>
                <span class="media-title">
                   <a href="javascript:;">
                      Empty
                   </a>
                </span>
                <div class="text-small text-muted">Time Limit: N/A
                    <div class="bullet"></div>&nbsp; No. of test:
                    <span class="text-primary">
                        N/A
                    </span>
                </div>
            </div>
        <?php }?>
    </div>
    <?php
}
?>
