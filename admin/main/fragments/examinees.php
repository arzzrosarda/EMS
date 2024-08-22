<?php
global $conn;
session_start();
require "../../../db/conn.php";
?>

<ul class="list-unstyled list-unstyled-border">
    <?php
    if (isset($_POST['department'])){
        $department = $_POST['department'];
        $examinees = $conn->query("SELECT lname, fname, mname, department, id FROM user WHERE usertype = 'examinee' AND department = '$department' AND isDelActive = '0'");
    }
    else {
        $examinees = $conn->query("SELECT lname, fname, mname, department, id FROM user WHERE usertype = 'examinee' AND isDelActive = '0'");
    }
    if ($examinees->rowCount() > 0) {
        while ($Urow = $examinees->fetch(PDO::FETCH_NUM)) {
            $data[] = $Urow;
        }
        $data = array_reverse($data, true);

        foreach($data as $examinee_row){
            if (isset($_POST['division'])) {
                $user_div = $_POST['division'];
                $div_query = $conn->query("SELECT division FROM user_division WHERE user_id = '$examinee_row[4]' AND division = '$user_div'");
                if ($div_query->rowCount() > 0) { ?>
                <script>
                    $(document).ready(function (){
                        $('#btnViewDetails<?php echo $examinee_row[4]; ?>').on('click', function (){
                            var uid = $(this).attr("data-id");
                            showModal();
                            $("#loader").show();
                            $("#viewModal").modal("show");
                            $.post("queries/viewdetails.php", {userid:uid}, function (user){
                                hideModal();
                                $("#loader").hide();
                                $("#viewContainerBody").html(user);
                            });
                        });
                    });
                </script>
                <li class="media align-items-center">
                    <i class="fas fa-user-circle" style="font-size: 30px; margin-right: 20px;"></i>
                    <a href="javascript:;" style="text-decoration: none;" id="btnViewDetails<?php echo $examinee_row[4]; ?>" data-id='<?php echo $examinee_row[4]; ?>'>
                        <div class="media-body">
                            <span class="media-title" style="font-size: 13px;">
                                <?php echo $examinee_row[0].", ".$examinee_row[1]." ".$examinee_row[2]; ?>
                            </span>
                            <div class="text-small text-muted">Department/Division:
                                <br>
                                <span class="text-primary">
                                    <?php echo $examinee_row[3];
                                    $division = $conn->query("SELECT division FROM user_division WHERE user_id = '$examinee_row[4]'");
                                    while ($divi = $division->fetch()) {
                                        echo '<div class="bullet"></div>'.$divi['division'];
                                    }
                                    ?></span>
                            </div>
                        </div>
                    </a>
                </li>
                <?php }
            }else {?>
            <script>
                $(document).ready(function (){
                    $('#btnViewDetails<?php echo $examinee_row[4]; ?>').on('click', function (){
                        var uid = $(this).attr("data-id");
                        showModal();
                        $("#loader").show();
                        $("#viewModal").modal("show");
                        $.post("queries/viewdetails.php", {userid:uid}, function (user){
                            hideModal();
                            $("#loader").hide();
                            $("#viewContainerBody").html(user);
                        });
                    });
                });
            </script>
            <li class="media align-items-center">
                <i class="fas fa-user-circle" style="font-size: 30px; margin-right: 20px;"></i>
                <a href="javascript:;" style="text-decoration: none;" id="btnViewDetails<?php echo $examinee_row[4]; ?>" data-id='<?php echo $examinee_row[4]; ?>'>
                    <div class="media-body">
                            <span class="media-title" style="font-size: 13px;">
                                <?php echo $examinee_row[0].", ".$examinee_row[1]." ".$examinee_row[2]; ?>
                            </span>
                        <div class="text-small text-muted">Department/Division:
                            <br>
                            <span class="text-primary">
                                <?php echo $examinee_row[3];
                                $division = $conn->query("SELECT division FROM user_division WHERE user_id = '$examinee_row[4]'");
                                while ($divi = $division->fetch()) {
                                    echo '<div class="bullet"></div>'.$divi['division'];
                                }
                                ?>
                            </span>
                        </div>
                    </div>
                </a>
            </li>
        <?php }
        }
    }
    ?>
</ul>


