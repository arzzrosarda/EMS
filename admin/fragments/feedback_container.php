<?php
global $conn;
session_start();
require "../../db/conn.php";
if (isset($_POST['user_id'])){
    $user_id = $_POST['user_id'];
    $message = $_POST['message'];
    $feedback_id = $_POST['feedback_id'];
    $date = $_POST['date'];
    $time = $_POST['time'];
    $rate = $_POST['rate'];
    $fullname = $_POST['fullname'];?>
        <script>
            $(document).ready(function (){
                $("#txt_feedback").val("<?php echo $message; ?>");
                $('input[name="icon-input"]').each(function (){
                    var rating = $(this).val();
                    var rate = "<?php echo $rate; ?>";
                    if (rating == rate){
                        $(this).attr("checked", true);
                    }
                })

            })
        </script>
    <div class="form-group">
        <h4><?php echo $fullname; ?></h4>
        <span><?php
            $division = $conn->query("SELECT division FROM user_division WHERE user_id = '$user_id'");
            while ($divi = $division->fetch()) {
            echo '<div class="bullet"></div>' . $divi['division']."<br>";
            }
            ?>
        </span>
        <br>
        <label>&nbsp;Date & Time : <?php echo $date." - ".$time;?></label>
    </div>
    <div class="form-group">
        <div class="selectgroup selectgroup-pills">
            <input type="hidden" class="form-control" id="input-rating">
            <label class="selectgroup-item">
                <input type="radio" name="icon-input" value="1" class="selectgroup-input" disabled>
                <span class="selectgroup-button selectgroup-button-icon">&#128533;</span>
            </label>
            <label class="selectgroup-item">
                <input type="radio" name="icon-input" value="2" class="selectgroup-input" disabled>
                <span class="selectgroup-button selectgroup-button-icon">&#128528;</span>
            </label>
            <label class="selectgroup-item">
                <input type="radio" name="icon-input" value="3" class="selectgroup-input" disabled>
                <span class="selectgroup-button selectgroup-button-icon">&#128512;</span>
            </label>
            <label class="selectgroup-item">
                <input type="radio" name="icon-input" value="4" class="selectgroup-input" disabled>
                <span class="selectgroup-button selectgroup-button-icon">&#128516;</span>
            </label>
            <label class="selectgroup-item">
                <input type="radio" name="icon-input" value="5" class="selectgroup-input" disabled>
                <span class="selectgroup-button selectgroup-button-icon">&#128513;</span>
            </label>
        </div>
    </div>
    <div class="form-group">
        <label>Message</label>
        <textarea class="form-control" id="txt_feedback" disabled></textarea>
    </div>
<?php } ?>