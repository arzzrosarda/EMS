<?php
global $conn;
session_start();
require "../../../db/conn.php";
?>
<ul class="list-unstyled list-unstyled-border">
    <?php

    if (isset($_POST['department'])){
        $department = $_POST['department'];
        $examinees = $conn->query("SELECT a.`lname`, a.`fname`, a.`mname`, a.`department`, b.`message`, a.`id` AS user_id, b.`id`, b.`feedback_date`, b.`feedback_time`, b.`rate` FROM feedback b LEFT JOIN user a ON a.`id` = b.`user_id` WHERE a.`usertype` = 'examinee' AND a.`department` = '$department' GROUP BY b.`id`");
    }
    else {
        $examinees = $conn->query("SELECT a.`lname`, a.`fname`, a.`mname`, a.`department`, b.`message`, a.`id` AS user_id, b.`id`, b.`feedback_date`, b.`feedback_time`, b.`rate` FROM feedback b LEFT JOIN user a ON a.`id` = b.`user_id` WHERE a.`usertype` = 'examinee' GROUP BY b.`id`");
    }
    if ($examinees->rowCount() > 0) {
        while ($Urow = $examinees->fetch(PDO::FETCH_NUM)) {
            $data[] = $Urow;
        }
        $data = array_reverse($data, true);

        foreach($data as $examinee_row){
        if (isset($_POST['division'])) {
            $user_div = $_POST['division'];
            $div_query = $conn->query("SELECT division FROM user_division WHERE user_id = '$examinee_row[5]' AND division = '$user_div'");
            if ($div_query->rowCount() > 0) { ?>
            <script>
                $(document).ready(function (){
                    $("#viewFeedback_<?php echo $examinee_row[6]; ?>").on("click", function (){
                        var message = "<?php echo $examinee_row[4];?>";
                        var user_id = "<?php echo $examinee_row[5]; ?>";
                        var feedback_id = "<?php echo $examinee_row[6]; ?>";
                        var date = "<?php echo $examinee_row[7]; ?>";
                        var time = "<?php echo $examinee_row[8]; ?>";
                        var rate = "<?php echo $examinee_row[9]; ?>";
                        var fullname = "<?php echo $examinee_row[0].", ".$examinee_row[1]." ".$examinee_row[2]; ?>";
                        showModal();
                        $("#loader").modal("show");
                        $("#viewFeedback").modal("show");
                        $.post("../fragments/feedback_container.php", {
                                user_id:user_id,
                                message:message,
                                feedback_id: feedback_id,
                                date:date,
                                time:time,
                                rate:rate,
                                fullname: fullname},
                            function (feedback){
                                hideModal();
                                $("#loader").modal("hide");
                                $("#viewFeedbackContainerBody").html(feedback);
                            });
                    });
                });
            </script>
                <li class="media align-items-center">
                    <i class="fas fa-comments" style="font-size: 35px; margin-right: 20px;"></i>
                    <a href="javascript:;" style="text-decoration: none;" id="viewFeedback_<?php echo $examinee_row[6]; ?>">
                        <div class="media-body">
                            <span class="media-title" style="font-size: 13px;"><?php echo $examinee_row[0].", ".$examinee_row[1]." ".$examinee_row[2]; ?></span>
                            <div class="text-small text-muted">Department/Division:
                                <br>
                                <span class="text-primary">
                                <?php echo $examinee_row[3]."-";
                                $division = $conn->query("SELECT division FROM user_division WHERE user_id = '$examinee_row[5]'");
                                while ($divi = $division->fetch()) {
                                    echo '<div class="bullet"></div>' . $divi['division'];
                                }

                                ?></span></div>
                            <div class="text-lg"><strong>Message:</strong> <span ><?php echo $examinee_row[4]; ?></span></div>
                        </div>
                    </a>
                </li>
            <?php }
        }else {?>
            <script>
                $(document).ready(function (){
                    $("#viewFeedback_<?php echo $examinee_row[6]; ?>").on("click", function (){
                        var message = "<?php echo $examinee_row[4];?>";
                        var user_id = "<?php echo $examinee_row[5]; ?>";
                        var feedback_id = "<?php echo $examinee_row[6]; ?>";
                        var date = "<?php echo $examinee_row[7]; ?>";
                        var time = "<?php echo $examinee_row[8]; ?>";
                        var rate = "<?php echo $examinee_row[9]; ?>";
                        var fullname = "<?php echo $examinee_row[0].", ".$examinee_row[1]." ".$examinee_row[2]; ?>";
                        showModal();
                        $("#loader").modal("show");
                        $("#viewFeedback").modal("show");
                        $.post("../fragments/feedback_container.php", {
                                user_id:user_id,
                                message:message,
                                feedback_id: feedback_id,
                                date:date,
                                time:time,
                                rate:rate,
                                fullname: fullname},
                            function (feedback){
                                hideModal();
                                $("#loader").modal("hide");
                                $("#viewFeedbackContainerBody").html(feedback);
                            });
                    });
                });
            </script>
            <li class="media align-items-center">
                <i class="fas fa-comments" style="font-size: 35px; margin-right: 20px;"></i>
                <a href="javascript:;" style="text-decoration: none;" id="viewFeedback_<?php echo $examinee_row[6]; ?>">
                <div class="media-body">
                    <span class="media-title" style="font-size: 13px;"><?php echo $examinee_row[0].", ".$examinee_row[1]." ".$examinee_row[2]; ?></span>
                    <div class="text-small text-muted">Department/Division:
                        <br>
                        <span class="text-primary">
                            <?php echo $examinee_row[3]."-";
                            $division = $conn->query("SELECT division FROM user_division WHERE user_id = '$examinee_row[5]'");
                            while ($divi = $division->fetch()) {
                                echo '<div class="bullet"></div>' . $divi['division'];
                            }

                            ?></span></div>
                    <div class="text-lg"><strong>Message:</strong> <span ><?php echo $examinee_row[4]; ?></span></div>
                </div>
                </a>
            </li>
        <?php }
        }
    }
    ?>
</ul>


