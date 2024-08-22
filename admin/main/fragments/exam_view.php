<?php
global $conn;
session_start();
require "../../../db/conn.php";
if (isset($_POST['exam_id'])){
    $exam_id = $_POST['exam_id'];
    $exam_test = $conn->query("SELECT a.`id`, a.`title`, a.`department`, a.`division`, a.`num_test`, a.`time_limit`, a.`isActiveExam`, b.`Test_I`, b.`Test_II`, b.`Test_III`, b.`Test_IV`, b.`Test_V` FROM exam_title a LEFT JOIN exam_test b ON a.`id` = b.`exam_id` WHERE a.`id` = '$exam_id'");
    $fetch_exam = $exam_test->fetch();
    $Active = $fetch_exam['isActiveExam'];
    $test1 = $fetch_exam['Test_I'];
    $test2 = $fetch_exam['Test_II'];
    $test3 = $fetch_exam['Test_III'];
    $test4 = $fetch_exam['Test_IV'];
    $test5 = $fetch_exam['Test_V'];
    $department = $fetch_exam['department'];
    $division = $fetch_exam['division'];
    if ($Active == '1'){
        $tag_active = "btnActivatedExam";
        $tag_legend = "info";
        $tag = "Active";
    }else {
        $tag_legend = "light";
        $tag = "inActive";
        $tag_active = "btnEditExam";
    }
    ?>
    <script>
        $(document).ready(function (){
            $("#btnActivatedExam").on("click", function (){
                Swal.fire("EDIT DETAILS", "<strong>Deactivate</strong> the exam first to edit the exam", "info");
            });
            $("#btnEditExam").on("click", function (){
                var exam_id = "<?php echo $fetch_exam['id']; ?>";
                Swal.fire({
                    title: "EXAM DETAILS?",
                    html: "press continue to proceed to exam details",
                    icon: "info",
                    showCancelButton: true,
                    confirmButtonColor: "#1c3d77",
                    confirmButtonText: "Continue",
                    cancelButtonText: "Cancel",
                    closeOnConfirm: false,
                    closeOnCancel: true
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.redirect("add_question.php", {"exam_id": exam_id});
                    }
                });
            });

            var test1 = "<?php echo $fetch_exam['Test_I']; ?>";
            var test2 = "<?php echo $fetch_exam['Test_II']; ?>";
            var test3 = "<?php echo $fetch_exam['Test_III']; ?>";
            var test4 = "<?php echo $fetch_exam['Test_IV']; ?>";
            var test5 = "<?php echo $fetch_exam['Test_V']; ?>";

            if (test1 == ''){
                $("#question-testI").hide();
            }
            if (test2 == '') {
                $("#question-testII").hide();
            }
            if (test3 == ''){
                $("#question-testIII").hide();
            }
            if (test4 == ''){
                $("#question-testIV").hide();
            }
            if (test5 == ''){
                $("#question-testV").hide();
            }
        });
    </script>
    <div class="row mb-4">
        <div class="col-lg-10">
            <h4>
                <?php echo $fetch_exam['title']; ?>
            </h4>
        </div>
        <div class="col-lg-2">
            <span class="float-right">
                <button class="btn btn-primary" id="<?php echo $tag_active; ?>">
                        Edit
                </button>
            </span>
        </div>
        <div class="col-lg-2 mb-2">
            <div class="badge-pill w-auto text-center badge-<?php echo $tag_legend; ?>">
                <?php echo $tag; ?>
            </div>
        </div>
        <div class="col-lg-12">
            <span>
                <strong>
                    Department & Division:
                </strong>
                <?php echo $fetch_exam['department']." - ".$fetch_exam['division']; ?>
            </span>
        </div>
        <div class="col-lg-12">
            <span>
                <strong>
                    Time Limit:
                </strong>
                <?php echo $fetch_exam['time_limit']; ?>
            </span>
        </div>
    </div>

<div class="row">
    <div class="col-md-12 text-md-center text-center mt-2">
        <ul class="nav nav-tabs" id="myTab4" role="tablist">
            <li class="nav-item">
                <a class="nav-link active" id="question-testI" data-toggle="tab" href="#test1" role="tab" aria-controls="home" aria-selected="true" >
                    Test I - <?php echo $test1; ?></a>
            </li>
            <li class="nav-item">
                <a class="nav-link" id="question-testII" data-toggle="tab" href="#test2" role="tab" aria-controls="home" aria-selected="true" >
                    Test II - <?php echo $test2; ?></a>
            </li>
            <li class="nav-item">
                <a class="nav-link" id="question-testIII" data-toggle="tab" href="#test3" role="tab" aria-controls="home" aria-selected="true" >
                    Test III - <?php echo $test3; ?></a>
            </li>
            <li class="nav-item">
                <a class="nav-link" id="question-testIV" data-toggle="tab" href="#test4" role="tab" aria-controls="home" aria-selected="true" >
                    Test IV - <?php echo $test4; ?></a>
            </li>
            <li class="nav-item">
                <a class="nav-link" id="question-testV" data-toggle="tab" href="#test5" role="tab" aria-controls="home" aria-selected="true" >
                    Test V - <?php echo $test5; ?></a>
            </li>
        </ul>
    </div>
    <div class="col-xl-12 col-sm-12 col-md-8">
        <div class="tab-content no-padding" id="myTab2Content">
            <div class="tab-pane fade show active" id="test1" role="tabpanel"  aria-labelledby="question-testI">
                <ul class="list-group">
                    <?php
                    $index = 1;
                    $exam = $conn->query("SELECT b.`id` AS question_no, b.`points`, b.`question_type`, b.`question`,
                            c.`id` AS option_id, c.`o_id`, c.`option_1`, c.`option_2`, c.`option_3`, c.`option_4`, 
                            c.`img_1`, c.`img_2`, c.`img_3`, c.`img_4`, c.`ans` 
                            FROM question b
                            LEFT JOIN options c ON b.`id` = c.`id` 
                            WHERE b.`question_type` = '$test1' AND b.`q_id` = '$exam_id' ");
                    while ($test = $exam->fetch()){ ?>
                        <script>
                            $(document).ready(function (){
                                var testI = "<?php echo $test1; ?>";
                                if (testI == "Short Answer"){
                                    $("#shortAnswerContainer<?php echo $test['option_id']; ?>").show();
                                    $("#multipleChoiceContainer<?php echo $test['option_id']; ?>").hide();
                                    $("#trueOrFalseContainer<?php echo $test['option_id']; ?>").hide();
                                    $("#multipleImageContainer<?php echo $test['option_id']; ?>").hide();
                                }else if (testI == "True/False"){
                                    $("#shortAnswerContainer<?php echo $test['option_id']; ?>").hide();
                                    $("#multipleChoiceContainer<?php echo $test['option_id']; ?>").hide();
                                    $("#trueOrFalseContainer<?php echo $test['option_id']; ?>").show();
                                    $("#multipleImageContainer<?php echo $test['option_id']; ?>").hide();
                                }else if (testI == "Multiple Choice"){
                                    $("#shortAnswerContainer<?php echo $test['option_id']; ?>").hide();
                                    $("#multipleChoiceContainer<?php echo $test['option_id']; ?>").show();
                                    $("#trueOrFalseContainer<?php echo $test['option_id']; ?>").hide();
                                    $("#multipleImageContainer<?php echo $test['option_id']; ?>").hide();
                                }else if (testI == "Multiple Image"){
                                    $("#shortAnswerContainer<?php echo $test['option_id']; ?>").hide();
                                    $("#multipleChoiceContainer<?php echo $test['option_id']; ?>").hide();
                                    $("#trueOrFalseContainer<?php echo $test['option_id']; ?>").hide();
                                    $("#multipleImageContainer<?php echo $test['option_id']; ?>").show();
                                }
                            });
                        </script>
                        <li class="list-group-item">
                            <div class="row pl-3 pr-3">
                                <span><?php echo $index++.".&nbsp;".$test['question']." [".$test['points']." point/s]"; ?></span>
                                <div id="shortAnswerContainer<?php echo $test['option_id']; ?>" class="col-lg-12" style="display: none;">
                                    <span>Answer: <?php echo $test['ans']; ?></span>
                                </div>
                                <div id="multipleChoiceContainer<?php echo $test['option_id']; ?>" class="col-lg-12" style="display: none;">
                                    <?php
                                    if ($test['img_1'] != null && $test['img_2'] != null && $test['img_3'] != null && $test['img_4'] != null){ ?>
                                        <div class="row" style="justify-content: space-evenly;">
                                            <div clas="col-3" style="text-align: center;">
                                                <img class="img-option" style="width: 150px; height: auto; border-radius: 2%; margin-top: 10px;" src="../../assets/uploads/<?php echo $department; ?>/<?php echo $division; ?>/<?php echo $exam_id; ?>/<?php echo $test['question_type']; ?>/<?php echo $test['question_no']; ?>/option1/<?php echo $test['img_1']; ?>"/>
                                                <h4>I</h4>
                                            </div>
                                            <div clas="col-3" style="text-align: center;">
                                                <img class="img-option" style="width: 150px; height: auto; border-radius: 2%; margin-top: 10px;" src="../../assets/uploads/<?php echo $department; ?>/<?php echo $division; ?>/<?php echo $exam_id; ?>/<?php echo $test['question_type']; ?>/<?php echo $test['question_no']; ?>/option2/<?php echo $test['img_2']; ?>"/>
                                                <h4>II</h4>
                                            </div>
                                            <div clas="col-3" style="text-align: center;">
                                                <img class="img-option" style="width: 150px; height: auto; border-radius: 2%; margin-top: 10px;" src="../../assets/uploads/<?php echo $department; ?>/<?php echo $division; ?>/<?php echo $exam_id; ?>/<?php echo $test['question_type']; ?>/<?php echo $test['question_no']; ?>/option3/<?php echo $test['img_3']; ?>"/>
                                                <h4>III</h4>
                                            </div>
                                            <div clas="col-3" style="text-align: center;">
                                                <img class="img-option" style="width: 150px; height: auto; border-radius: 2%; margin-top: 10px;" src="../../assets/uploads/<?php echo $department; ?>/<?php echo $division; ?>/<?php echo $exam_id; ?>/<?php echo $test['question_type']; ?>/<?php echo $test['question_no']; ?>/option4/<?php echo $test['img_4']; ?>"/>
                                                <h4>IV</h4>
                                            </div>
                                        </div>
                                    <?php }
                                    else if ($test['img_1'] != null && $test['img_2'] != null && $test['img_3'] != null
                                        && $test['img_4'] == null){ ?>
                                        <div class="row" style="justify-content: space-evenly;">
                                            <div clas="col-4" style="text-align: center;">
                                                <img class="img-option" style="width: 150px; height: auto; border-radius: 2%; margin-top: 10px;" src="../../assets/uploads/<?php echo $department; ?>/<?php echo $division; ?>/<?php echo $exam_id; ?>/<?php echo $test['question_type']; ?>/<?php echo $test['question_no']; ?>/option1/<?php echo $test['img_1']; ?>"/>
                                                <h4>I</h4>
                                            </div>
                                            <div clas="col-4" style="text-align: center;">
                                                <img class="img-option" style="width: 150px; height: auto; border-radius: 2%; margin-top: 10px;" src="../../assets/uploads/<?php echo $department; ?>/<?php echo $division; ?>/<?php echo $exam_id; ?>/<?php echo $test['question_type']; ?>/<?php echo $test['question_no']; ?>/option2/<?php echo $test['img_2']; ?>"/>
                                                <h4>II</h4>
                                            </div>
                                            <div clas="col-4" style="text-align: center;">
                                                <img class="img-option" style="width: 150px; height: auto; border-radius: 2%; margin-top: 10px;" src="../../assets/uploads/<?php echo $department; ?>/<?php echo $division; ?>/<?php echo $exam_id; ?>/<?php echo $test['question_type']; ?>/<?php echo $test['question_no']; ?>/option3/<?php echo $test['img_3']; ?>"/>
                                                <h4>III</h4>
                                            </div>
                                        </div>
                                    <?php }
                                    else if ($test['img_1'] != null && $test['img_2'] != null && $test['img_3'] == null
                                        && $test['img_4'] != null){ ?>
                                        <div class="row" style="justify-content: space-evenly;">
                                            <div clas="col-4" style="text-align: center;">
                                                <img class="img-option" style="width: 150px; height: auto; border-radius: 2%; margin-top: 10px;" src="../../assets/uploads/<?php echo $department; ?>/<?php echo $division; ?>/<?php echo $exam_id; ?>/<?php echo $test['question_type']; ?>/<?php echo $test['question_no']; ?>/option1/<?php echo $test['img_1']; ?>"/>
                                                <h4>I</h4>
                                            </div>
                                            <div clas="col-4" style="text-align: center;">
                                                <img class="img-option" style="width: 150px; height: auto; border-radius: 2%; margin-top: 10px;" src="../../assets/uploads/<?php echo $department; ?>/<?php echo $division; ?>/<?php echo $exam_id; ?>/<?php echo $test['question_type']; ?>/<?php echo $test['question_no']; ?>/option2/<?php echo $test['img_2']; ?>"/>
                                                <h4>II</h4>
                                            </div>
                                            <div clas="col-3" style="text-align: center;">
                                                <img class="img-option" style="width: 150px; height: auto; border-radius: 2%; margin-top: 10px;" src="../../assets/uploads/<?php echo $department; ?>/<?php echo $division; ?>/<?php echo $exam_id; ?>/<?php echo $test['question_type']; ?>/<?php echo $test['question_no']; ?>/option4/<?php echo $test['img_4']; ?>"/>
                                                <h4>III</h4>
                                            </div>
                                        </div>
                                    <?php }
                                    else if ($test['img_1'] != null && $test['img_2'] == null && $test['img_3'] != null
                                        && $test['img_4'] != null){ ?>
                                        <div class="row" style="justify-content: space-evenly;">
                                            <div clas="col-4" style="text-align: center;">
                                                <img class="img-option" style="width: 150px; height: auto; border-radius: 2%; margin-top: 10px;" src="../../assets/uploads/<?php echo $department; ?>/<?php echo $division; ?>/<?php echo $exam_id; ?>/<?php echo $test['question_type']; ?>/<?php echo $test['question_no']; ?>/option1/<?php echo $test['img_1']; ?>"/>
                                                <h4>I</h4>
                                            </div>
                                            <div clas="col-4" style="text-align: center;">
                                                <img class="img-option" style="width: 150px; height: auto; border-radius: 2%; margin-top: 10px;" src="../../assets/uploads/<?php echo $department; ?>/<?php echo $division; ?>/<?php echo $exam_id; ?>/<?php echo $test['question_type']; ?>/<?php echo $test['question_no']; ?>/option3/<?php echo $test['img_3']; ?>"/>
                                                <h4>II</h4>
                                            </div>
                                            <div clas="col-3" style="text-align: center;">
                                                <img class="img-option" style="width: 150px; height: auto; border-radius: 2%; margin-top: 10px;" src="../../assets/uploads/<?php echo $department; ?>/<?php echo $division; ?>/<?php echo $exam_id; ?>/<?php echo $test['question_type']; ?>/<?php echo $test['question_no']; ?>/option4/<?php echo $test['img_4']; ?>"/>
                                                <h4>III</h4>
                                            </div>
                                        </div>
                                    <?php }
                                    else if ($test['img_1'] == null && $test['img_2'] != null && $test['img_3'] != null
                                        && $test['img_4'] != null){ ?>
                                        <div class="row" style="justify-content: space-evenly;">
                                            <div clas="col-4" style="text-align: center;">
                                                <img class="img-option" style="width: 150px; height: auto; border-radius: 2%; margin-top: 10px;" src="../../assets/uploads/<?php echo $department; ?>/<?php echo $division; ?>/<?php echo $exam_id; ?>/<?php echo $test['question_type']; ?>/<?php echo $test['question_no']; ?>/option2/<?php echo $test['img_2']; ?>"/>
                                                <h4>I</h4>
                                            </div>
                                            <div clas="col-4" style="text-align: center;">
                                                <img class="img-option" style="width: 150px; height: auto; border-radius: 2%; margin-top: 10px;" src="../../assets/uploads/<?php echo $department; ?>/<?php echo $division; ?>/<?php echo $exam_id; ?>/<?php echo $test['question_type']; ?>/<?php echo $test['question_no']; ?>/option3/<?php echo $test['img_3']; ?>"/>
                                                <h4>II</h4>
                                            </div>
                                            <div clas="col-3" style="text-align: center;">
                                                <img class="img-option" style="width: 150px; height: auto; border-radius: 2%; margin-top: 10px;" src="../../assets/uploads/<?php echo $department; ?>/<?php echo $division; ?>/<?php echo $exam_id; ?>/<?php echo $test['question_type']; ?>/<?php echo $test['question_no']; ?>/option4/<?php echo $test['img_4']; ?>"/>
                                                <h4>III</h4>
                                            </div>
                                        </div>
                                    <?php }

                                    // 1-2 OR 2-1
                                    else if ($test['img_1'] != null && $test['img_2'] != null && $test['img_3'] == null
                                        && $test['img_4'] == null){ ?>
                                        <div class="row" style="justify-content: space-evenly;">
                                            <div clas="col-6" style="text-align: center;">
                                                <img class="img-option" style="width: 150px; height: auto; border-radius: 2%; margin-top: 10px;" src="../../assets/uploads/<?php echo $department; ?>/<?php echo $division; ?>/<?php echo $exam_id; ?>/<?php echo $test['question_type']; ?>/<?php echo $test['question_no']; ?>/option1/<?php echo $test['img_1']; ?>"/>
                                                <h4>I</h4>
                                            </div>
                                            <div clas="col-6" style="text-align: center;">
                                                <img class="img-option" style="width: 150px; height: auto; border-radius: 2%; margin-top: 10px;" src="../../assets/uploads/<?php echo $department; ?>/<?php echo $division; ?>/<?php echo $exam_id; ?>/<?php echo $test['question_type']; ?>/<?php echo $test['question_no']; ?>/option2/<?php echo $test['img_2']; ?>"/>
                                                <h4>II</h4>
                                            </div>
                                        </div>
                                    <?php }
                                    // 1-3 OR 3-1
                                    else if ($test['img_1'] != null && $test['img_2'] == null && $test['img_3'] != null
                                        && $test['img_4'] == null){ ?>
                                        <div class="row" style="justify-content: space-evenly;">
                                            <div clas="col-6" style="text-align: center;">
                                                <img class="img-option" style="width: 150px; height: auto; border-radius: 2%; margin-top: 10px;" src="../../assets/uploads/<?php echo $department; ?>/<?php echo $division; ?>/<?php echo $exam_id; ?>/<?php echo $test['question_type']; ?>/<?php echo $test['question_no']; ?>/option1/<?php echo $test['img_1']; ?>"/>
                                                <h4>I</h4>
                                            </div>
                                            <div clas="col-6" style="text-align: center;">
                                                <img class="img-option" style="width: 150px; height: auto; border-radius: 2%; margin-top: 10px;" src="../../assets/uploads/<?php echo $department; ?>/<?php echo $division; ?>/<?php echo $exam_id; ?>/<?php echo $test['question_type']; ?>/<?php echo $test['question_no']; ?>/option3/<?php echo $test['img_3']; ?>"/>
                                                <h4>II</h4>
                                            </div>
                                        </div>
                                    <?php }
                                    // 1-4 OR 4-1
                                    else if ($test['img_1'] != null && $test['img_2'] == null && $test['img_3'] == null
                                        && $test['img_4'] != null){ ?>
                                        <div class="row" style="justify-content: space-evenly;">
                                            <div clas="col-6" style="text-align: center;">
                                                <img class="img-option" style="width: 150px; height: auto; border-radius: 2%; margin-top: 10px;" src="../../assets/uploads/<?php echo $department; ?>/<?php echo $division; ?>/<?php echo $exam_id; ?>/<?php echo $test['question_type']; ?>/<?php echo $test['question_no']; ?>/option1/<?php echo $test['img_1']; ?>"/>
                                                <h4>I</h4>
                                            </div>
                                            <div clas="col-6" style="text-align: center;">
                                                <img class="img-option" style="width: 150px; height: auto; border-radius: 2%; margin-top: 10px;" src="../../assets/uploads/<?php echo $department; ?>/<?php echo $division; ?>/<?php echo $exam_id; ?>/<?php echo $test['question_type']; ?>/<?php echo $test['question_no']; ?>/option4/<?php echo $test['img_4']; ?>"/>
                                                <h4>II</h4>
                                            </div>
                                        </div>
                                    <?php }
                                    // 2-3 OR 3-2
                                    else if ($test['img_1'] == null && $test['img_2'] != null && $test['img_3'] != null
                                        && $test['img_4'] == null){ ?>
                                        <div class="row" style="justify-content: space-evenly;">
                                            <div clas="col-6" style="text-align: center;">
                                                <img class="img-option" style="width: 150px; height: auto; border-radius: 2%; margin-top: 10px;" src="../../assets/uploads/<?php echo $department; ?>/<?php echo $division; ?>/<?php echo $exam_id; ?>/<?php echo $test['question_type']; ?>/<?php echo $test['question_no']; ?>/option2/<?php echo $test['img_2']; ?>"/>
                                                <h4>I</h4>
                                            </div>
                                            <div clas="col-6" style="text-align: center;">
                                                <img class="img-option" style="width: 150px; height: auto; border-radius: 2%; margin-top: 10px;" src="../../assets/uploads/<?php echo $department; ?>/<?php echo $division; ?>/<?php echo $exam_id; ?>/<?php echo $test['question_type']; ?>/<?php echo $test['question_no']; ?>/option3/<?php echo $test['img_3']; ?>"/>
                                                <h4>II</h4>
                                            </div>
                                        </div>
                                    <?php }
                                    // 2-4 OR 4-2
                                    else if ($test['img_1'] == null && $test['img_2'] != null && $test['img_3'] == null
                                        && $test['img_4'] != null){ ?>
                                        <div class="row" style="justify-content: space-evenly;">
                                            <div clas="col-6" style="text-align: center;">
                                                <img class="img-option" style="width: 150px; height: auto; border-radius: 2%; margin-top: 10px;" src="../../assets/uploads/<?php echo $department; ?>/<?php echo $division; ?>/<?php echo $exam_id; ?>/<?php echo $test['question_type']; ?>/<?php echo $test['question_no']; ?>/option2/<?php echo $test['img_2']; ?>"/>
                                                <h4>I</h4>
                                            </div>
                                            <div clas="col-6" style="text-align: center;">
                                                <img class="img-option" style="width: 150px; height: auto; border-radius: 2%; margin-top: 10px;" src="../../assets/uploads/<?php echo $department; ?>/<?php echo $division; ?>/<?php echo $exam_id; ?>/<?php echo $test['question_type']; ?>/<?php echo $test['question_no']; ?>/option4/<?php echo $test['img_4']; ?>"/>
                                                <h4>II</h4>
                                            </div>
                                        </div>
                                    <?php }
                                    // 3-4 OR 4-3
                                    else if ($test['img_1'] == null && $test['img_2'] == null && $test['img_3'] != null
                                        && $test['img_4'] != null){ ?>
                                        <div class="row" style="justify-content: space-evenly;">
                                            <div clas="col-6" style="text-align: center;">
                                                <img class="img-option" style="width: 150px; height: auto; border-radius: 2%; margin-top: 10px;" src="../../assets/uploads/<?php echo $department; ?>/<?php echo $division; ?>/<?php echo $exam_id; ?>/<?php echo $test['question_type']; ?>/<?php echo $test['question_no']; ?>/option3/<?php echo $test['img_3']; ?>"/>
                                                <h4>I/h4>
                                            </div>
                                            <div clas="col-6" style="text-align: center;">
                                                <img class="img-option" style="width: 150px; height: auto; border-radius: 2%; margin-top: 10px;" src="../../assets/uploads/<?php echo $department; ?>/<?php echo $division; ?>/<?php echo $exam_id; ?>/<?php echo $test['question_type']; ?>/<?php echo $test['question_no']; ?>/option4/<?php echo $test['img_4']; ?>"/>
                                                <h4>II</h4>
                                            </div>
                                        </div>
                                    <?php }

                                    else if ($test['img_1'] != null && $test['img_2'] == null && $test['img_3'] == null
                                        && $test['img_4'] == null){ ?>
                                        <div class="row" style="justify-content: space-evenly;">
                                            <div clas="col-12" style="text-align: center;">
                                                <img class="img-option" style="width: 150px; height: auto; border-radius: 2%; margin-top: 10px;" src="../../assets/uploads/<?php echo $department; ?>/<?php echo $division; ?>/<?php echo $exam_id; ?>/<?php echo $test['question_type']; ?>/<?php echo $test['question_no']; ?>/option1/<?php echo $test['img_1']; ?>"/>
                                                <h4>I</h4>
                                            </div>
                                        </div>
                                    <?php }
                                    else if ($test['img_1'] == null && $test['img_2'] != null && $test['img_3'] == null
                                        && $test['img_4'] == null){ ?>
                                        <div class="row" style="justify-content: space-evenly;">
                                            <div clas="col-12" style="text-align: center;">
                                                <img class="img-option" style="width: 150px; height: auto; border-radius: 2%; margin-top: 10px;" src="../../assets/uploads/<?php echo $department; ?>/<?php echo $division; ?>/<?php echo $exam_id; ?>/<?php echo $test['question_type']; ?>/<?php echo $test['question_no']; ?>/option2/<?php echo $test['img_2']; ?>"/>
                                                <h4>I</h4>
                                            </div>
                                        </div>
                                    <?php }
                                    else if ($test['img_1'] == null && $test['img_2'] == null && $test['img_3'] != null
                                        && $test['img_4'] == null){ ?>
                                        <div class="row" style="justify-content: space-evenly;">
                                            <div clas="col-12" style="text-align: center;">
                                                <img class="img-option" style="width: 150px; height: auto; border-radius: 2%; margin-top: 10px;" src="../../assets/uploads/<?php echo $department; ?>/<?php echo $division; ?>/<?php echo $exam_id; ?>/<?php echo $test['question_type']; ?>/<?php echo $test['question_no']; ?>/option3/<?php echo $test['img_3']; ?>"/>
                                                <h4>I</h4>
                                            </div>
                                        </div>
                                    <?php }
                                    else if ($test['img_1'] == null && $test['img_2'] == null && $test['img_3'] == null
                                        && $test['img_4'] != null){ ?>
                                        <div class="row" style="justify-content: space-evenly;">
                                            <div clas="col-12" style="text-align: center;">
                                                <img class="img-option" style="width: 150px; height: auto; border-radius: 2%; margin-top: 10px;" src="../../assets/uploads/<?php echo $department; ?>/<?php echo $division; ?>/<?php echo $exam_id; ?>/<?php echo $test['question_type']; ?>/<?php echo $test['question_no']; ?>/option4/<?php echo $test['img_4']; ?>"/>
                                                <h4>I</h4>
                                            </div>
                                        </div>
                                    <?php } ?>
                                    <span>Option 1: <?php echo $test['option_1']; ?></span><br>
                                    <span>Option 2: <?php echo $test['option_2']; ?></span><br>
                                    <span>Option 3: <?php echo $test['option_3']; ?></span><br>
                                    <span>Option 4: <?php echo $test['option_4']; ?></span><br>
                                    <span>Answer: <?php echo $test['ans']; ?></span>
                                </div>
                                <div id="trueOrFalseContainer<?php echo $test['option_id']; ?>" class="col-lg-12 pt-2"  style="display: none;" >
                                    <?php
                                    if ($test['img_1'] != null && $test['img_2'] != null && $test['img_3'] != null && $test['img_4'] != null){ ?>
                                        <div class="row" style="justify-content: space-evenly;">
                                            <div clas="col-3" style="text-align: center;">
                                                <img class="img-option" style="width: 150px; height: auto; border-radius: 2%; margin-top: 10px;" src="../../assets/uploads/<?php echo $department; ?>/<?php echo $division; ?>/<?php echo $exam_id; ?>/<?php echo $test['question_type']; ?>/<?php echo $test['question_no']; ?>/option1/<?php echo $test['img_1']; ?>"/>
                                            </div>
                                            <div clas="col-3" style="text-align: center;">
                                                <img class="img-option" style="width: 150px; height: auto; border-radius: 2%; margin-top: 10px;" src="../../assets/uploads/<?php echo $department; ?>/<?php echo $division; ?>/<?php echo $exam_id; ?>/<?php echo $test['question_type']; ?>/<?php echo $test['question_no']; ?>/option2/<?php echo $test['img_2']; ?>"/>
                                            </div>
                                            <div clas="col-3" style="text-align: center;">
                                                <img class="img-option" style="width: 150px; height: auto; border-radius: 2%; margin-top: 10px;" src="../../assets/uploads/<?php echo $department; ?>/<?php echo $division; ?>/<?php echo $exam_id; ?>/<?php echo $test['question_type']; ?>/<?php echo $test['question_no']; ?>/option3/<?php echo $test['img_3']; ?>"/>
                                            </div>
                                            <div clas="col-3" style="text-align: center;">
                                                <img class="img-option" style="width: 150px; height: auto; border-radius: 2%; margin-top: 10px;" src="../../assets/uploads/<?php echo $department; ?>/<?php echo $division; ?>/<?php echo $exam_id; ?>/<?php echo $test['question_type']; ?>/<?php echo $test['question_no']; ?>/option4/<?php echo $test['img_4']; ?>"/>
                                            </div>
                                        </div>
                                    <?php }
                                    else if ($test['img_1'] != null && $test['img_2'] != null && $test['img_3'] != null
                                        && $test['img_4'] == null){ ?>
                                        <div class="row" style="justify-content: space-evenly;">
                                            <div clas="col-4" style="text-align: center;">
                                                <img class="img-option" style="width: 150px; height: auto; border-radius: 2%; margin-top: 10px;" src="../../assets/uploads/<?php echo $department; ?>/<?php echo $division; ?>/<?php echo $exam_id; ?>/<?php echo $test['question_type']; ?>/<?php echo $test['question_no']; ?>/option1/<?php echo $test['img_1']; ?>"/>
                                            </div>
                                            <div clas="col-4" style="text-align: center;">
                                                <img class="img-option" style="width: 150px; height: auto; border-radius: 2%; margin-top: 10px;" src="../../assets/uploads/<?php echo $department; ?>/<?php echo $division; ?>/<?php echo $exam_id; ?>/<?php echo $test['question_type']; ?>/<?php echo $test['question_no']; ?>/option2/<?php echo $test['img_2']; ?>"/>
                                            </div>
                                            <div clas="col-4" style="text-align: center;">
                                                <img class="img-option" style="width: 150px; height: auto; border-radius: 2%; margin-top: 10px;" src="../../assets/uploads/<?php echo $department; ?>/<?php echo $division; ?>/<?php echo $exam_id; ?>/<?php echo $test['question_type']; ?>/<?php echo $test['question_no']; ?>/option3/<?php echo $test['img_3']; ?>"/>
                                            </div>
                                        </div>
                                    <?php }
                                    else if ($test['img_1'] != null && $test['img_2'] != null && $test['img_3'] == null
                                        && $test['img_4'] != null){ ?>
                                        <div class="row" style="justify-content: space-evenly;">
                                            <div clas="col-4" style="text-align: center;">
                                                <img class="img-option" style="width: 150px; height: auto; border-radius: 2%; margin-top: 10px;" src="../../assets/uploads/<?php echo $department; ?>/<?php echo $division; ?>/<?php echo $exam_id; ?>/<?php echo $test['question_type']; ?>/<?php echo $test['question_no']; ?>/option1/<?php echo $test['img_1']; ?>"/>
                                            </div>
                                            <div clas="col-4" style="text-align: center;">
                                                <img class="img-option" style="width: 150px; height: auto; border-radius: 2%; margin-top: 10px;" src="../../assets/uploads/<?php echo $department; ?>/<?php echo $division; ?>/<?php echo $exam_id; ?>/<?php echo $test['question_type']; ?>/<?php echo $test['question_no']; ?>/option2/<?php echo $test['img_2']; ?>"/>
                                            </div>
                                            <div clas="col-3" style="text-align: center;">
                                                <img class="img-option" style="width: 150px; height: auto; border-radius: 2%; margin-top: 10px;" src="../../assets/uploads/<?php echo $department; ?>/<?php echo $division; ?>/<?php echo $exam_id; ?>/<?php echo $test['question_type']; ?>/<?php echo $test['question_no']; ?>/option4/<?php echo $test['img_4']; ?>"/>
                                            </div>
                                        </div>
                                    <?php }
                                    else if ($test['img_1'] != null && $test['img_2'] == null && $test['img_3'] != null
                                        && $test['img_4'] != null){ ?>
                                        <div class="row" style="justify-content: space-evenly;">
                                            <div clas="col-4" style="text-align: center;">
                                                <img class="img-option" style="width: 150px; height: auto; border-radius: 2%; margin-top: 10px;" src="../../assets/uploads/<?php echo $department; ?>/<?php echo $division; ?>/<?php echo $exam_id; ?>/<?php echo $test['question_type']; ?>/<?php echo $test['question_no']; ?>/option1/<?php echo $test['img_1']; ?>"/>
                                            </div>
                                            <div clas="col-4" style="text-align: center;">
                                                <img class="img-option" style="width: 150px; height: auto; border-radius: 2%; margin-top: 10px;" src="../../assets/uploads/<?php echo $department; ?>/<?php echo $division; ?>/<?php echo $exam_id; ?>/<?php echo $test['question_type']; ?>/<?php echo $test['question_no']; ?>/option3/<?php echo $test['img_3']; ?>"/>
                                            </div>
                                            <div clas="col-3" style="text-align: center;">
                                                <img class="img-option" style="width: 150px; height: auto; border-radius: 2%; margin-top: 10px;" src="../../assets/uploads/<?php echo $department; ?>/<?php echo $division; ?>/<?php echo $exam_id; ?>/<?php echo $test['question_type']; ?>/<?php echo $test['question_no']; ?>/option4/<?php echo $test['img_4']; ?>"/>
                                            </div>
                                        </div>
                                    <?php }
                                    else if ($test['img_1'] == null && $test['img_2'] != null && $test['img_3'] != null
                                        && $test['img_4'] != null){ ?>
                                        <div class="row" style="justify-content: space-evenly;">
                                            <div clas="col-4" style="text-align: center;">
                                                <img class="img-option" style="width: 150px; height: auto; border-radius: 2%; margin-top: 10px;" src="../../assets/uploads/<?php echo $department; ?>/<?php echo $division; ?>/<?php echo $exam_id; ?>/<?php echo $test['question_type']; ?>/<?php echo $test['question_no']; ?>/option2/<?php echo $test['img_2']; ?>"/>
                                            </div>
                                            <div clas="col-4" style="text-align: center;">
                                                <img class="img-option" style="width: 150px; height: auto; border-radius: 2%; margin-top: 10px;" src="../../assets/uploads/<?php echo $department; ?>/<?php echo $division; ?>/<?php echo $exam_id; ?>/<?php echo $test['question_type']; ?>/<?php echo $test['question_no']; ?>/option3/<?php echo $test['img_3']; ?>"/>
                                            </div>
                                            <div clas="col-3" style="text-align: center;">
                                                <img class="img-option" style="width: 150px; height: auto; border-radius: 2%; margin-top: 10px;" src="../../assets/uploads/<?php echo $department; ?>/<?php echo $division; ?>/<?php echo $exam_id; ?>/<?php echo $test['question_type']; ?>/<?php echo $test['question_no']; ?>/option4/<?php echo $test['img_4']; ?>"/>
                                            </div>
                                        </div>
                                    <?php }

                                    // 1-2 OR 2-1
                                    else if ($test['img_1'] != null && $test['img_2'] != null && $test['img_3'] == null
                                        && $test['img_4'] == null){ ?>
                                        <div class="row" style="justify-content: space-evenly;">
                                            <div clas="col-6" style="text-align: center;">
                                                <img class="img-option" style="width: 150px; height: auto; border-radius: 2%; margin-top: 10px;" src="../../assets/uploads/<?php echo $department; ?>/<?php echo $division; ?>/<?php echo $exam_id; ?>/<?php echo $test['question_type']; ?>/<?php echo $test['question_no']; ?>/option1/<?php echo $test['img_1']; ?>"/>
                                            </div>
                                            <div clas="col-6" style="text-align: center;">
                                                <img class="img-option" style="width: 150px; height: auto; border-radius: 2%; margin-top: 10px;" src="../../assets/uploads/<?php echo $department; ?>/<?php echo $division; ?>/<?php echo $exam_id; ?>/<?php echo $test['question_type']; ?>/<?php echo $test['question_no']; ?>/option2/<?php echo $test['img_2']; ?>"/>
                                            </div>
                                        </div>
                                    <?php }
                                    // 1-3 OR 3-1
                                    else if ($test['img_1'] != null && $test['img_2'] == null && $test['img_3'] != null
                                        && $test['img_4'] == null){ ?>
                                        <div class="row" style="justify-content: space-evenly;">
                                            <div clas="col-6" style="text-align: center;">
                                                <img class="img-option" style="width: 150px; height: auto; border-radius: 2%; margin-top: 10px;" src="../../assets/uploads/<?php echo $department; ?>/<?php echo $division; ?>/<?php echo $exam_id; ?>/<?php echo $test['question_type']; ?>/<?php echo $test['question_no']; ?>/option1/<?php echo $test['img_1']; ?>"/>
                                            </div>
                                            <div clas="col-6" style="text-align: center;">
                                                <img class="img-option" style="width: 150px; height: auto; border-radius: 2%; margin-top: 10px;" src="../../assets/uploads/<?php echo $department; ?>/<?php echo $division; ?>/<?php echo $exam_id; ?>/<?php echo $test['question_type']; ?>/<?php echo $test['question_no']; ?>/option3/<?php echo $test['img_3']; ?>"/>
                                            </div>
                                        </div>
                                    <?php }
                                    // 1-4 OR 4-1
                                    else if ($test['img_1'] != null && $test['img_2'] == null && $test['img_3'] == null
                                        && $test['img_4'] != null){ ?>
                                        <div class="row" style="justify-content: space-evenly;">
                                            <div clas="col-6" style="text-align: center;">
                                                <img class="img-option" style="width: 150px; height: auto; border-radius: 2%; margin-top: 10px;" src="../../assets/uploads/<?php echo $department; ?>/<?php echo $division; ?>/<?php echo $exam_id; ?>/<?php echo $test['question_type']; ?>/<?php echo $test['question_no']; ?>/option1/<?php echo $test['img_1']; ?>"/>
                                            </div>
                                            <div clas="col-6" style="text-align: center;">
                                                <img class="img-option" style="width: 150px; height: auto; border-radius: 2%; margin-top: 10px;" src="../../assets/uploads/<?php echo $department; ?>/<?php echo $division; ?>/<?php echo $exam_id; ?>/<?php echo $test['question_type']; ?>/<?php echo $test['question_no']; ?>/option4/<?php echo $test['img_4']; ?>"/>
                                            </div>
                                        </div>
                                    <?php }
                                    // 2-3 OR 3-2
                                    else if ($test['img_1'] == null && $test['img_2'] != null && $test['img_3'] != null
                                        && $test['img_4'] == null){ ?>
                                        <div class="row" style="justify-content: space-evenly;">
                                            <div clas="col-6" style="text-align: center;">
                                                <img class="img-option" style="width: 150px; height: auto; border-radius: 2%; margin-top: 10px;" src="../../assets/uploads/<?php echo $department; ?>/<?php echo $division; ?>/<?php echo $exam_id; ?>/<?php echo $test['question_type']; ?>/<?php echo $test['question_no']; ?>/option2/<?php echo $test['img_2']; ?>"/>
                                            </div>
                                            <div clas="col-6" style="text-align: center;">
                                                <img class="img-option" style="width: 150px; height: auto; border-radius: 2%; margin-top: 10px;" src="../../assets/uploads/<?php echo $department; ?>/<?php echo $division; ?>/<?php echo $exam_id; ?>/<?php echo $test['question_type']; ?>/<?php echo $test['question_no']; ?>/option3/<?php echo $test['img_3']; ?>"/>
                                            </div>
                                        </div>
                                    <?php }
                                    // 2-4 OR 4-2
                                    else if ($test['img_1'] == null && $test['img_2'] != null && $test['img_3'] == null
                                        && $test['img_4'] != null){ ?>
                                        <div class="row" style="justify-content: space-evenly;">
                                            <div clas="col-6" style="text-align: center;">
                                                <img class="img-option" style="width: 150px; height: auto; border-radius: 2%; margin-top: 10px;" src="../../assets/uploads/<?php echo $department; ?>/<?php echo $division; ?>/<?php echo $exam_id; ?>/<?php echo $test['question_type']; ?>/<?php echo $test['question_no']; ?>/option2/<?php echo $test['img_2']; ?>"/>
                                            </div>
                                            <div clas="col-6" style="text-align: center;">
                                                <img class="img-option" style="width: 150px; height: auto; border-radius: 2%; margin-top: 10px;" src="../../assets/uploads/<?php echo $department; ?>/<?php echo $division; ?>/<?php echo $exam_id; ?>/<?php echo $test['question_type']; ?>/<?php echo $test['question_no']; ?>/option4/<?php echo $test['img_4']; ?>"/>
                                            </div>
                                        </div>
                                    <?php }
                                    // 3-4 OR 4-3
                                    else if ($test['img_1'] == null && $test['img_2'] == null && $test['img_3'] != null
                                        && $test['img_4'] != null){ ?>
                                        <div class="row" style="justify-content: space-evenly;">
                                            <div clas="col-6" style="text-align: center;">
                                                <img class="img-option" style="width: 150px; height: auto; border-radius: 2%; margin-top: 10px;" src="../../assets/uploads/<?php echo $department; ?>/<?php echo $division; ?>/<?php echo $exam_id; ?>/<?php echo $test['question_type']; ?>/<?php echo $test['question_no']; ?>/option3/<?php echo $test['img_3']; ?>"/>
                                            </div>
                                            <div clas="col-6" style="text-align: center;">
                                                <img class="img-option" style="width: 150px; height: auto; border-radius: 2%; margin-top: 10px;" src="../../assets/uploads/<?php echo $department; ?>/<?php echo $division; ?>/<?php echo $exam_id; ?>/<?php echo $test['question_type']; ?>/<?php echo $test['question_no']; ?>/option4/<?php echo $test['img_4']; ?>"/>
                                            </div>
                                        </div>
                                    <?php }

                                    else if ($test['img_1'] != null && $test['img_2'] == null && $test['img_3'] == null
                                        && $test['img_4'] == null){ ?>
                                        <div class="row" style="justify-content: space-evenly;">
                                            <div clas="col-12" style="text-align: center;">
                                                <img class="img-option" style="width: 150px; height: auto; border-radius: 2%; margin-top: 10px;" src="../../assets/uploads/<?php echo $department; ?>/<?php echo $division; ?>/<?php echo $exam_id; ?>/<?php echo $test['question_type']; ?>/<?php echo $test['question_no']; ?>/option1/<?php echo $test['img_1']; ?>"/>
                                            </div>
                                        </div>
                                    <?php }
                                    else if ($test['img_1'] == null && $test['img_2'] != null && $test['img_3'] == null
                                        && $test['img_4'] == null){ ?>
                                        <div class="row" style="justify-content: space-evenly;">
                                            <div clas="col-12" style="text-align: center;">
                                                <img class="img-option" style="width: 150px; height: auto; border-radius: 2%; margin-top: 10px;" src="../../assets/uploads/<?php echo $department; ?>/<?php echo $division; ?>/<?php echo $exam_id; ?>/<?php echo $test['question_type']; ?>/<?php echo $test['question_no']; ?>/option2/<?php echo $test['img_2']; ?>"/>
                                            </div>
                                        </div>
                                    <?php }
                                    else if ($test['img_1'] == null && $test['img_2'] == null && $test['img_3'] != null
                                        && $test['img_4'] == null){ ?>
                                        <div class="row" style="justify-content: space-evenly;">
                                            <div clas="col-12" style="text-align: center;">
                                                <img class="img-option" style="width: 150px; height: auto; border-radius: 2%; margin-top: 10px;" src="../../assets/uploads/<?php echo $department; ?>/<?php echo $division; ?>/<?php echo $exam_id; ?>/<?php echo $test['question_type']; ?>/<?php echo $test['question_no']; ?>/option3/<?php echo $test['img_3']; ?>"/>
                                            </div>
                                        </div>
                                    <?php }
                                    else if ($test['img_1'] == null && $test['img_2'] == null && $test['img_3'] == null
                                        && $test['img_4'] != null){ ?>
                                        <div class="row" style="justify-content: space-evenly;">
                                            <div clas="col-12" style="text-align: center;">
                                                <img class="img-option" style="width: 150px; height: auto; border-radius: 2%; margin-top: 10px;" src="../../assets/uploads/<?php echo $department; ?>/<?php echo $division; ?>/<?php echo $exam_id; ?>/<?php echo $test['question_type']; ?>/<?php echo $test['question_no']; ?>/option4/<?php echo $test['img_4']; ?>"/>
                                            </div>
                                        </div>
                                    <?php } ?>
                                    <br>
                                    <span>Answer: <?php echo $test['ans']; ?></span>
                                </div>
                                <div id="multipleImageContainer<?php echo $test['option_id']; ?>" class="col-lg-12 pt-2" style="display: none;">
                                    <?php
                                    if ($test['img_1'] != null && $test['img_2'] != null && $test['img_3'] != null && $test['img_4'] != null){ ?>
                                        <div class="row" style="justify-content: space-evenly;">
                                            <div clas="col-3" style="text-align: center;">
                                                <img class="img-option" style="width: 150px; height: auto; border-radius: 2%; margin-top: 10px;" src="../../assets/uploads/<?php echo $department; ?>/<?php echo $division; ?>/<?php echo $exam_id; ?>/<?php echo $test['question_type']; ?>/<?php echo $test['question_no']; ?>/option1/<?php echo $test['img_1']; ?>" />
                                                <h4><?php echo $test['option_1']; ?></h4>
                                            </div>
                                            <div clas="col-3" style="text-align: center;">
                                                <img class="img-option" style="width: 150px; height: auto; border-radius: 2%; margin-top: 10px;" src="../../assets/uploads/<?php echo $department; ?>/<?php echo $division; ?>/<?php echo $exam_id; ?>/<?php echo $test['question_type']; ?>/<?php echo $test['question_no']; ?>/option2/<?php echo $test['img_2']; ?>" />
                                                <h4><?php echo $test['option_2']; ?></h4>
                                            </div>
                                            <div clas="col-3" style="text-align: center;">
                                                <img class="img-option" style="width: 150px; height: auto; border-radius: 2%; margin-top: 10px;" src="../../assets/uploads/<?php echo $department; ?>/<?php echo $division; ?>/<?php echo $exam_id; ?>/<?php echo $test['question_type']; ?>/<?php echo $test['question_no']; ?>/option3/<?php echo $test['img_3']; ?>" />
                                                <h4><?php echo $test['option_3']; ?></h4>
                                            </div>
                                            <div clas="col-3" style="text-align: center;">
                                                <img class="img-option" style="width: 150px; height: auto; border-radius: 2%; margin-top: 10px;" src="../../assets/uploads/<?php echo $department; ?>/<?php echo $division; ?>/<?php echo $exam_id; ?>/<?php echo $test['question_type']; ?>/<?php echo $test['question_no']; ?>/option4/<?php echo $test['img_4']; ?>" />
                                                <h4><?php echo $test['option_4']; ?></h4>
                                            </div>
                                        </div>
                                    <?php } ?>
                                    <br>
                                    <span>Answer: <?php echo $test['ans']; ?></span>
                                </div>

                            </div>
                        </li>
                    <?php } ?>
                </ul>
            </div>
            <div class="tab-pane fade " id="test2" role="tabpanel" aria-labelledby="question-testII">
                <ul class="list-group">
                    <?php
                    $index = 1;
                    $exam2 = $conn->query("SELECT b.`id` AS question_no, b.`points`, b.`question_type`, b.`question`,
                            c.`id` AS option_id, c.`o_id`, c.`option_1`, c.`option_2`, c.`option_3`, c.`option_4`, 
                            c.`img_1`, c.`img_2`, c.`img_3`, c.`img_4`, c.`ans` 
                            FROM question b
                            LEFT JOIN options c ON b.`id` = c.`id` 
                            WHERE b.`question_type` = '$test2' AND b.`q_id` = '$exam_id' ");
                    while ($testII = $exam2->fetch()){ ?>
                        <script>
                            $(document).ready(function (){
                                var testII = "<?php echo $test2; ?>";
                                if (testII == "Short Answer"){
                                    $("#shortAnswerContainer<?php echo $testII['option_id']; ?>").show();
                                    $("#multipleChoiceContainer<?php echo $testII['option_id']; ?>").hide();
                                    $("#trueOrFalseContainer<?php echo $testII['option_id']; ?>").hide();
                                    $("#multipleImageContainer<?php echo $testII['option_id']; ?>").hide();
                                }else if (testII == "True/False"){
                                    $("#shortAnswerContainer<?php echo $testII['option_id']; ?>").hide();
                                    $("#multipleChoiceContainer<?php echo $testII['option_id']; ?>").hide();
                                    $("#trueOrFalseContainer<?php echo $testII['option_id']; ?>").show();
                                    $("#multipleImageContainer<?php echo $testII['option_id']; ?>").hide();
                                }else if (testII == "Multiple Choice"){
                                    $("#shortAnswerContainer<?php echo $testII['option_id']; ?>").hide();
                                    $("#multipleChoiceContainer<?php echo $testII['option_id']; ?>").show();
                                    $("#trueOrFalseContainer<?php echo $testII['option_id']; ?>").hide();
                                    $("#multipleImageContainer<?php echo $testII['option_id']; ?>").hide();
                                }else if (testII == "Multiple Image"){
                                    $("#shortAnswerContainer<?php echo $testII['option_id']; ?>").hide();
                                    $("#multipleChoiceContainer<?php echo $testII['option_id']; ?>").hide();
                                    $("#trueOrFalseContainer<?php echo $testII['option_id']; ?>").hide();
                                    $("#multipleImageContainer<?php echo $testII['option_id']; ?>").show();
                                }
                            });
                        </script>
                        <li class="list-group-item">
                            <div class="row pl-3 pr-3">
                                <span><?php echo $index++.".&nbsp;".$testII['question']." [".$testII['points']." point/s]"; ?></span>
                                <div id="shortAnswerContainer<?php echo $testII['option_id']; ?>" class="col-lg-12" style="display: none;">
                                    <span>Answer: <?php echo $testII['ans']; ?></span>
                                </div>
                                <div id="multipleChoiceContainer<?php echo $testII['option_id']; ?>" class="col-lg-12" style="display: none;">
                                    <?php
                                    if ($testII['img_1'] != null && $testII['img_2'] != null && $testII['img_3'] != null && $testII['img_4'] != null){ ?>
                                        <div class="row" style="justify-content: space-evenly;">
                                            <div clas="col-3" style="text-align: center;">
                                                <img class="img-option" style="width: 150px; height: auto; border-radius: 2%; margin-top: 10px;" src="../../assets/uploads/<?php echo $department; ?>/<?php echo $division; ?>/<?php echo $exam_id; ?>/<?php echo $testII['question_type']; ?>/<?php echo $testII['question_no']; ?>/option1/<?php echo $testII['img_1']; ?>"/>
                                                <h4>I</h4>
                                            </div>
                                            <div clas="col-3" style="text-align: center;">
                                                <img class="img-option" style="width: 150px; height: auto; border-radius: 2%; margin-top: 10px;" src="../../assets/uploads/<?php echo $department; ?>/<?php echo $division; ?>/<?php echo $exam_id; ?>/<?php echo $testII['question_type']; ?>/<?php echo $testII['question_no']; ?>/option2/<?php echo $testII['img_2']; ?>"/>
                                                <h4>II</h4>
                                            </div>
                                            <div clas="col-3" style="text-align: center;">
                                                <img class="img-option" style="width: 150px; height: auto; border-radius: 2%; margin-top: 10px;" src="../../assets/uploads/<?php echo $department; ?>/<?php echo $division; ?>/<?php echo $exam_id; ?>/<?php echo $testII['question_type']; ?>/<?php echo $testII['question_no']; ?>/option3/<?php echo $testII['img_3']; ?>"/>
                                                <h4>III</h4>
                                            </div>
                                            <div clas="col-3" style="text-align: center;">
                                                <img class="img-option" style="width: 150px; height: auto; border-radius: 2%; margin-top: 10px;" src="../../assets/uploads/<?php echo $department; ?>/<?php echo $division; ?>/<?php echo $exam_id; ?>/<?php echo $testII['question_type']; ?>/<?php echo $testII['question_no']; ?>/option4/<?php echo $testII['img_4']; ?>"/>
                                                <h4>IV</h4>
                                            </div>
                                        </div>
                                    <?php }
                                    else if ($testII['img_1'] != null && $testII['img_2'] != null && $testII['img_3'] != null
                                        && $testII['img_4'] == null){ ?>
                                        <div class="row" style="justify-content: space-evenly;">
                                            <div clas="col-4" style="text-align: center;">
                                                <img class="img-option" style="width: 150px; height: auto; border-radius: 2%; margin-top: 10px;" src="../../assets/uploads/<?php echo $department; ?>/<?php echo $division; ?>/<?php echo $exam_id; ?>/<?php echo $testII['question_type']; ?>/<?php echo $testII['question_no']; ?>/option1/<?php echo $testII['img_1']; ?>"/>
                                                <h4>I</h4>
                                            </div>
                                            <div clas="col-4" style="text-align: center;">
                                                <img class="img-option" style="width: 150px; height: auto; border-radius: 2%; margin-top: 10px;" src="../../assets/uploads/<?php echo $department; ?>/<?php echo $division; ?>/<?php echo $exam_id; ?>/<?php echo $testII['question_type']; ?>/<?php echo $testII['question_no']; ?>/option2/<?php echo $testII['img_2']; ?>"/>
                                                <h4>II</h4>
                                            </div>
                                            <div clas="col-4" style="text-align: center;">
                                                <img class="img-option" style="width: 150px; height: auto; border-radius: 2%; margin-top: 10px;" src="../../assets/uploads/<?php echo $department; ?>/<?php echo $division; ?>/<?php echo $exam_id; ?>/<?php echo $testII['question_type']; ?>/<?php echo $testII['question_no']; ?>/option3/<?php echo $testII['img_3']; ?>"/>
                                                <h4>III</h4>
                                            </div>
                                        </div>
                                    <?php }
                                    else if ($testII['img_1'] != null && $testII['img_2'] != null && $testII['img_3'] == null
                                        && $testII['img_4'] != null){ ?>
                                        <div class="row" style="justify-content: space-evenly;">
                                            <div clas="col-4" style="text-align: center;">
                                                <img class="img-option" style="width: 150px; height: auto; border-radius: 2%; margin-top: 10px;" src="../../assets/uploads/<?php echo $department; ?>/<?php echo $division; ?>/<?php echo $exam_id; ?>/<?php echo $testII['question_type']; ?>/<?php echo $testII['question_no']; ?>/option1/<?php echo $testII['img_1']; ?>"/>
                                                <h4>I</h4>
                                            </div>
                                            <div clas="col-4" style="text-align: center;">
                                                <img class="img-option" style="width: 150px; height: auto; border-radius: 2%; margin-top: 10px;" src="../../assets/uploads/<?php echo $department; ?>/<?php echo $division; ?>/<?php echo $exam_id; ?>/<?php echo $testII['question_type']; ?>/<?php echo $testII['question_no']; ?>/option2/<?php echo $testII['img_2']; ?>"/>
                                                <h4>II</h4>
                                            </div>
                                            <div clas="col-3" style="text-align: center;">
                                                <img class="img-option" style="width: 150px; height: auto; border-radius: 2%; margin-top: 10px;" src="../../assets/uploads/<?php echo $department; ?>/<?php echo $division; ?>/<?php echo $exam_id; ?>/<?php echo $testII['question_type']; ?>/<?php echo $testII['question_no']; ?>/option4/<?php echo $testII['img_4']; ?>"/>
                                                <h4>III</h4>
                                            </div>
                                        </div>
                                    <?php }
                                    else if ($testII['img_1'] != null && $testII['img_2'] == null && $testII['img_3'] != null
                                        && $testII['img_4'] != null){ ?>
                                        <div class="row" style="justify-content: space-evenly;">
                                            <div clas="col-4" style="text-align: center;">
                                                <img class="img-option" style="width: 150px; height: auto; border-radius: 2%; margin-top: 10px;" src="../../assets/uploads/<?php echo $department; ?>/<?php echo $division; ?>/<?php echo $exam_id; ?>/<?php echo $testII['question_type']; ?>/<?php echo $testII['question_no']; ?>/option1/<?php echo $testII['img_1']; ?>"/>
                                                <h4>I</h4>
                                            </div>
                                            <div clas="col-4" style="text-align: center;">
                                                <img class="img-option" style="width: 150px; height: auto; border-radius: 2%; margin-top: 10px;" src="../../assets/uploads/<?php echo $department; ?>/<?php echo $division; ?>/<?php echo $exam_id; ?>/<?php echo $testII['question_type']; ?>/<?php echo $testII['question_no']; ?>/option3/<?php echo $testII['img_3']; ?>"/>
                                                <h4>II</h4>
                                            </div>
                                            <div clas="col-3" style="text-align: center;">
                                                <img class="img-option" style="width: 150px; height: auto; border-radius: 2%; margin-top: 10px;" src="../../assets/uploads/<?php echo $department; ?>/<?php echo $division; ?>/<?php echo $exam_id; ?>/<?php echo $testII['question_type']; ?>/<?php echo $testII['question_no']; ?>/option4/<?php echo $testII['img_4']; ?>"/>
                                                <h4>III</h4>
                                            </div>
                                        </div>
                                    <?php }
                                    else if ($testII['img_1'] == null && $testII['img_2'] != null && $testII['img_3'] != null
                                        && $testII['img_4'] != null){ ?>
                                        <div class="row" style="justify-content: space-evenly;">
                                            <div clas="col-4" style="text-align: center;">
                                                <img class="img-option" style="width: 150px; height: auto; border-radius: 2%; margin-top: 10px;" src="../../assets/uploads/<?php echo $department; ?>/<?php echo $division; ?>/<?php echo $exam_id; ?>/<?php echo $testII['question_type']; ?>/<?php echo $testII['question_no']; ?>/option2/<?php echo $testII['img_2']; ?>"/>
                                                <h4>I</h4>
                                            </div>
                                            <div clas="col-4" style="text-align: center;">
                                                <img class="img-option" style="width: 150px; height: auto; border-radius: 2%; margin-top: 10px;" src="../../assets/uploads/<?php echo $department; ?>/<?php echo $division; ?>/<?php echo $exam_id; ?>/<?php echo $testII['question_type']; ?>/<?php echo $testII['question_no']; ?>/option3/<?php echo $testII['img_3']; ?>"/>
                                                <h4>II</h4>
                                            </div>
                                            <div clas="col-3" style="text-align: center;">
                                                <img class="img-option" style="width: 150px; height: auto; border-radius: 2%; margin-top: 10px;" src="../../assets/uploads/<?php echo $department; ?>/<?php echo $division; ?>/<?php echo $exam_id; ?>/<?php echo $testII['question_type']; ?>/<?php echo $testII['question_no']; ?>/option4/<?php echo $testII['img_4']; ?>"/>
                                                <h4>III</h4>
                                            </div>
                                        </div>
                                    <?php }

                                    // 1-2 OR 2-1
                                    else if ($testII['img_1'] != null && $testII['img_2'] != null && $testII['img_3'] == null
                                        && $testII['img_4'] == null){ ?>
                                        <div class="row" style="justify-content: space-evenly;">
                                            <div clas="col-6" style="text-align: center;">
                                                <img class="img-option" style="width: 150px; height: auto; border-radius: 2%; margin-top: 10px;" src="../../assets/uploads/<?php echo $department; ?>/<?php echo $division; ?>/<?php echo $exam_id; ?>/<?php echo $testII['question_type']; ?>/<?php echo $testII['question_no']; ?>/option1/<?php echo $testII['img_1']; ?>"/>
                                                <h4>I</h4>
                                            </div>
                                            <div clas="col-6" style="text-align: center;">
                                                <img class="img-option" style="width: 150px; height: auto; border-radius: 2%; margin-top: 10px;" src="../../assets/uploads/<?php echo $department; ?>/<?php echo $division; ?>/<?php echo $exam_id; ?>/<?php echo $testII['question_type']; ?>/<?php echo $testII['question_no']; ?>/option2/<?php echo $testII['img_2']; ?>"/>
                                                <h4>II</h4>
                                            </div>
                                        </div>
                                    <?php }
                                    // 1-3 OR 3-1
                                    else if ($testII['img_1'] != null && $testII['img_2'] == null && $testII['img_3'] != null
                                        && $testII['img_4'] == null){ ?>
                                        <div class="row" style="justify-content: space-evenly;">
                                            <div clas="col-6" style="text-align: center;">
                                                <img class="img-option" style="width: 150px; height: auto; border-radius: 2%; margin-top: 10px;" src="../../assets/uploads/<?php echo $department; ?>/<?php echo $division; ?>/<?php echo $exam_id; ?>/<?php echo $testII['question_type']; ?>/<?php echo $testII['question_no']; ?>/option1/<?php echo $testII['img_1']; ?>"/>
                                                <h4>I</h4>
                                            </div>
                                            <div clas="col-6" style="text-align: center;">
                                                <img class="img-option" style="width: 150px; height: auto; border-radius: 2%; margin-top: 10px;" src="../../assets/uploads/<?php echo $department; ?>/<?php echo $division; ?>/<?php echo $exam_id; ?>/<?php echo $testII['question_type']; ?>/<?php echo $testII['question_no']; ?>/option3/<?php echo $testII['img_3']; ?>"/>
                                                <h4>II</h4>
                                            </div>
                                        </div>
                                    <?php }
                                    // 1-4 OR 4-1
                                    else if ($testII['img_1'] != null && $testII['img_2'] == null && $testII['img_3'] == null
                                        && $testII['img_4'] != null){ ?>
                                        <div class="row" style="justify-content: space-evenly;">
                                            <div clas="col-6" style="text-align: center;">
                                                <img class="img-option" style="width: 150px; height: auto; border-radius: 2%; margin-top: 10px;" src="../../assets/uploads/<?php echo $department; ?>/<?php echo $division; ?>/<?php echo $exam_id; ?>/<?php echo $testII['question_type']; ?>/<?php echo $testII['question_no']; ?>/option1/<?php echo $testII['img_1']; ?>"/>
                                                <h4>I</h4>
                                            </div>
                                            <div clas="col-6" style="text-align: center;">
                                                <img class="img-option" style="width: 150px; height: auto; border-radius: 2%; margin-top: 10px;" src="../../assets/uploads/<?php echo $department; ?>/<?php echo $division; ?>/<?php echo $exam_id; ?>/<?php echo $testII['question_type']; ?>/<?php echo $testII['question_no']; ?>/option4/<?php echo $testII['img_4']; ?>"/>
                                                <h4>II</h4>
                                            </div>
                                        </div>
                                    <?php }
                                    // 2-3 OR 3-2
                                    else if ($testII['img_1'] == null && $testII['img_2'] != null && $testII['img_3'] != null
                                        && $testII['img_4'] == null){ ?>
                                        <div class="row" style="justify-content: space-evenly;">
                                            <div clas="col-6" style="text-align: center;">
                                                <img class="img-option" style="width: 150px; height: auto; border-radius: 2%; margin-top: 10px;" src="../../assets/uploads/<?php echo $department; ?>/<?php echo $division; ?>/<?php echo $exam_id; ?>/<?php echo $testII['question_type']; ?>/<?php echo $testII['question_no']; ?>/option2/<?php echo $testII['img_2']; ?>"/>
                                                <h4>I</h4>
                                            </div>
                                            <div clas="col-6" style="text-align: center;">
                                                <img class="img-option" style="width: 150px; height: auto; border-radius: 2%; margin-top: 10px;" src="../../assets/uploads/<?php echo $department; ?>/<?php echo $division; ?>/<?php echo $exam_id; ?>/<?php echo $testII['question_type']; ?>/<?php echo $testII['question_no']; ?>/option3/<?php echo $testII['img_3']; ?>"/>
                                                <h4>II</h4>
                                            </div>
                                        </div>
                                    <?php }
                                    // 2-4 OR 4-2
                                    else if ($testII['img_1'] == null && $testII['img_2'] != null && $testII['img_3'] == null
                                        && $testII['img_4'] != null){ ?>
                                        <div class="row" style="justify-content: space-evenly;">
                                            <div clas="col-6" style="text-align: center;">
                                                <img class="img-option" style="width: 150px; height: auto; border-radius: 2%; margin-top: 10px;" src="../../assets/uploads/<?php echo $department; ?>/<?php echo $division; ?>/<?php echo $exam_id; ?>/<?php echo $testII['question_type']; ?>/<?php echo $testII['question_no']; ?>/option2/<?php echo $testII['img_2']; ?>"/>
                                                <h4>I</h4>
                                            </div>
                                            <div clas="col-6" style="text-align: center;">
                                                <img class="img-option" style="width: 150px; height: auto; border-radius: 2%; margin-top: 10px;" src="../../assets/uploads/<?php echo $department; ?>/<?php echo $division; ?>/<?php echo $exam_id; ?>/<?php echo $testII['question_type']; ?>/<?php echo $testII['question_no']; ?>/option4/<?php echo $testII['img_4']; ?>"/>
                                                <h4>II</h4>
                                            </div>
                                        </div>
                                    <?php }
                                    // 3-4 OR 4-3
                                    else if ($testII['img_1'] == null && $testII['img_2'] == null && $testII['img_3'] != null
                                        && $testII['img_4'] != null){ ?>
                                        <div class="row" style="justify-content: space-evenly;">
                                            <div clas="col-6" style="text-align: center;">
                                                <img class="img-option" style="width: 150px; height: auto; border-radius: 2%; margin-top: 10px;" src="../../assets/uploads/<?php echo $department; ?>/<?php echo $division; ?>/<?php echo $exam_id; ?>/<?php echo $testII['question_type']; ?>/<?php echo $testII['question_no']; ?>/option3/<?php echo $testII['img_3']; ?>"/>
                                                <h4>I/h4>
                                            </div>
                                            <div clas="col-6" style="text-align: center;">
                                                <img class="img-option" style="width: 150px; height: auto; border-radius: 2%; margin-top: 10px;" src="../../assets/uploads/<?php echo $department; ?>/<?php echo $division; ?>/<?php echo $exam_id; ?>/<?php echo $testII['question_type']; ?>/<?php echo $testII['question_no']; ?>/option4/<?php echo $testII['img_4']; ?>"/>
                                                <h4>II</h4>
                                            </div>
                                        </div>
                                    <?php }

                                    else if ($testII['img_1'] != null && $testII['img_2'] == null && $testII['img_3'] == null
                                        && $testII['img_4'] == null){ ?>
                                        <div class="row" style="justify-content: space-evenly;">
                                            <div clas="col-12" style="text-align: center;">
                                                <img class="img-option" style="width: 150px; height: auto; border-radius: 2%; margin-top: 10px;" src="../../assets/uploads/<?php echo $department; ?>/<?php echo $division; ?>/<?php echo $exam_id; ?>/<?php echo $testII['question_type']; ?>/<?php echo $testII['question_no']; ?>/option1/<?php echo $testII['img_1']; ?>"/>
                                                <h4>I</h4>
                                            </div>
                                        </div>
                                    <?php }
                                    else if ($testII['img_1'] == null && $testII['img_2'] != null && $testII['img_3'] == null
                                        && $testII['img_4'] == null){ ?>
                                        <div class="row" style="justify-content: space-evenly;">
                                            <div clas="col-12" style="text-align: center;">
                                                <img class="img-option" style="width: 150px; height: auto; border-radius: 2%; margin-top: 10px;" src="../../assets/uploads/<?php echo $department; ?>/<?php echo $division; ?>/<?php echo $exam_id; ?>/<?php echo $testII['question_type']; ?>/<?php echo $testII['question_no']; ?>/option2/<?php echo $testII['img_2']; ?>"/>
                                                <h4>I</h4>
                                            </div>
                                        </div>
                                    <?php }
                                    else if ($testII['img_1'] == null && $testII['img_2'] == null && $testII['img_3'] != null
                                        && $testII['img_4'] == null){ ?>
                                        <div class="row" style="justify-content: space-evenly;">
                                            <div clas="col-12" style="text-align: center;">
                                                <img class="img-option" style="width: 150px; height: auto; border-radius: 2%; margin-top: 10px;" src="../../assets/uploads/<?php echo $department; ?>/<?php echo $division; ?>/<?php echo $exam_id; ?>/<?php echo $testII['question_type']; ?>/<?php echo $testII['question_no']; ?>/option3/<?php echo $testII['img_3']; ?>"/>
                                                <h4>I</h4>
                                            </div>
                                        </div>
                                    <?php }
                                    else if ($testII['img_1'] == null && $testII['img_2'] == null && $testII['img_3'] == null
                                        && $testII['img_4'] != null){ ?>
                                        <div class="row" style="justify-content: space-evenly;">
                                            <div clas="col-12" style="text-align: center;">
                                                <img class="img-option" style="width: 150px; height: auto; border-radius: 2%; margin-top: 10px;" src="../../assets/uploads/<?php echo $department; ?>/<?php echo $division; ?>/<?php echo $exam_id; ?>/<?php echo $testII['question_type']; ?>/<?php echo $testII['question_no']; ?>/option4/<?php echo $testII['img_4']; ?>"/>
                                                <h4>I</h4>
                                            </div>
                                        </div>
                                    <?php } ?>
                                    <span>Option 1: <?php echo $testII['option_1']; ?></span><br>
                                    <span>Option 2: <?php echo $testII['option_2']; ?></span><br>
                                    <span>Option 3: <?php echo $testII['option_3']; ?></span><br>
                                    <span>Option 4: <?php echo $testII['option_4']; ?></span><br>
                                    <span>Answer: <?php echo $testII['ans']; ?></span>
                                </div>
                                <div id="trueOrFalseContainer<?php echo $testII['option_id']; ?>" class="col-lg-12 pt-2"  style="display: none;" >
                                    <?php
                                    if ($testII['img_1'] != null && $testII['img_2'] != null && $testII['img_3'] != null && $testII['img_4'] != null){ ?>
                                        <div class="row" style="justify-content: space-evenly;">
                                            <div clas="col-3" style="text-align: center;">
                                                <img class="img-option" style="width: 150px; height: auto; border-radius: 2%; margin-top: 10px;" src="../../assets/uploads/<?php echo $department; ?>/<?php echo $division; ?>/<?php echo $exam_id; ?>/<?php echo $testII['question_type']; ?>/<?php echo $testII['question_no']; ?>/option1/<?php echo $testII['img_1']; ?>" />
                                            </div>
                                            <div clas="col-3" style="text-align: center;">
                                                <img class="img-option" style="width: 150px; height: auto; border-radius: 2%; margin-top: 10px;" src="../../assets/uploads/<?php echo $department; ?>/<?php echo $division; ?>/<?php echo $exam_id; ?>/<?php echo $testII['question_type']; ?>/<?php echo $testII['question_no']; ?>/option2/<?php echo $testII['img_2']; ?>" />
                                            </div>
                                            <div clas="col-3" style="text-align: center;">
                                                <img class="img-option" style="width: 150px; height: auto; border-radius: 2%; margin-top: 10px;" src="../../assets/uploads/<?php echo $department; ?>/<?php echo $division; ?>/<?php echo $exam_id; ?>/<?php echo $testII['question_type']; ?>/<?php echo $testII['question_no']; ?>/option3/<?php echo $testII['img_3']; ?>" />
                                            </div>
                                            <div clas="col-3" style="text-align: center;">
                                                <img class="img-option" style="width: 150px; height: auto; border-radius: 2%; margin-top: 10px;" src="../../assets/uploads/<?php echo $department; ?>/<?php echo $division; ?>/<?php echo $exam_id; ?>/<?php echo $testII['question_type']; ?>/<?php echo $testII['question_no']; ?>/option4/<?php echo $testII['img_4']; ?>" />
                                            </div>
                                        </div>
                                    <?php }
                                    else if ($testII['img_1'] != null && $testII['img_2'] != null && $testII['img_3'] != null
                                        && $testII['img_4'] == null){ ?>
                                        <div class="row" style="justify-content: space-evenly;">
                                            <div clas="col-4" style="text-align: center;">
                                                <img class="img-option" style="width: 150px; height: auto; border-radius: 2%; margin-top: 10px;" src="../../assets/uploads/<?php echo $department; ?>/<?php echo $division; ?>/<?php echo $exam_id; ?>/<?php echo $testII['question_type']; ?>/<?php echo $testII['question_no']; ?>/option1/<?php echo $testII['img_1']; ?>" />
                                            </div>
                                            <div clas="col-4" style="text-align: center;">
                                                <img class="img-option" style="width: 150px; height: auto; border-radius: 2%; margin-top: 10px;" src="../../assets/uploads/<?php echo $department; ?>/<?php echo $division; ?>/<?php echo $exam_id; ?>/<?php echo $testII['question_type']; ?>/<?php echo $testII['question_no']; ?>/option2/<?php echo $testII['img_2']; ?>" />
                                            </div>
                                            <div clas="col-4" style="text-align: center;">
                                                <img class="img-option" style="width: 150px; height: auto; border-radius: 2%; margin-top: 10px;" src="../../assets/uploads/<?php echo $department; ?>/<?php echo $division; ?>/<?php echo $exam_id; ?>/<?php echo $testII['question_type']; ?>/<?php echo $testII['question_no']; ?>/option3/<?php echo $testII['img_3']; ?>" />
                                            </div>
                                        </div>
                                    <?php }
                                    else if ($testII['img_1'] != null && $testII['img_2'] != null && $testII['img_3'] == null
                                        && $testII['img_4'] != null){ ?>
                                        <div class="row" style="justify-content: space-evenly;">
                                            <div clas="col-4" style="text-align: center;">
                                                <img class="img-option" style="width: 150px; height: auto; border-radius: 2%; margin-top: 10px;" src="../../assets/uploads/<?php echo $department; ?>/<?php echo $division; ?>/<?php echo $exam_id; ?>/<?php echo $testII['question_type']; ?>/<?php echo $testII['question_no']; ?>/option1/<?php echo $testII['img_1']; ?>" />
                                            </div>
                                            <div clas="col-4" style="text-align: center;">
                                                <img class="img-option" style="width: 150px; height: auto; border-radius: 2%; margin-top: 10px;" src="../../assets/uploads/<?php echo $department; ?>/<?php echo $division; ?>/<?php echo $exam_id; ?>/<?php echo $testII['question_type']; ?>/<?php echo $testII['question_no']; ?>/option2/<?php echo $testII['img_2']; ?>" />
                                            </div>
                                            <div clas="col-3" style="text-align: center;">
                                                <img class="img-option" style="width: 150px; height: auto; border-radius: 2%; margin-top: 10px;" src="../../assets/uploads/<?php echo $department; ?>/<?php echo $division; ?>/<?php echo $exam_id; ?>/<?php echo $testII['question_type']; ?>/<?php echo $testII['question_no']; ?>/option4/<?php echo $testII['img_4']; ?>" />
                                            </div>
                                        </div>
                                    <?php }
                                    else if ($testII['img_1'] != null && $testII['img_2'] == null && $testII['img_3'] != null
                                        && $testII['img_4'] != null){ ?>
                                        <div class="row" style="justify-content: space-evenly;">
                                            <div clas="col-4" style="text-align: center;">
                                                <img class="img-option" style="width: 150px; height: auto; border-radius: 2%; margin-top: 10px;" src="../../assets/uploads/<?php echo $department; ?>/<?php echo $division; ?>/<?php echo $exam_id; ?>/<?php echo $testII['question_type']; ?>/<?php echo $testII['question_no']; ?>/option1/<?php echo $testII['img_1']; ?>" />
                                            </div>
                                            <div clas="col-4" style="text-align: center;">
                                                <img class="img-option" style="width: 150px; height: auto; border-radius: 2%; margin-top: 10px;" src="../../assets/uploads/<?php echo $department; ?>/<?php echo $division; ?>/<?php echo $exam_id; ?>/<?php echo $testII['question_type']; ?>/<?php echo $testII['question_no']; ?>/option3/<?php echo $testII['img_3']; ?>" />
                                            </div>
                                            <div clas="col-3" style="text-align: center;">
                                                <img class="img-option" style="width: 150px; height: auto; border-radius: 2%; margin-top: 10px;" src="../../assets/uploads/<?php echo $department; ?>/<?php echo $division; ?>/<?php echo $exam_id; ?>/<?php echo $testII['question_type']; ?>/<?php echo $testII['question_no']; ?>/option4/<?php echo $testII['img_4']; ?>" />
                                            </div>
                                        </div>
                                    <?php }
                                    else if ($testII['img_1'] == null && $testII['img_2'] != null && $testII['img_3'] != null
                                        && $testII['img_4'] != null){ ?>
                                        <div class="row" style="justify-content: space-evenly;">
                                            <div clas="col-4" style="text-align: center;">
                                                <img class="img-option" style="width: 150px; height: auto; border-radius: 2%; margin-top: 10px;" src="../../assets/uploads/<?php echo $department; ?>/<?php echo $division; ?>/<?php echo $exam_id; ?>/<?php echo $testII['question_type']; ?>/<?php echo $testII['question_no']; ?>/option2/<?php echo $testII['img_2']; ?>" />
                                            </div>
                                            <div clas="col-4" style="text-align: center;">
                                                <img class="img-option" style="width: 150px; height: auto; border-radius: 2%; margin-top: 10px;" src="../../assets/uploads/<?php echo $department; ?>/<?php echo $division; ?>/<?php echo $exam_id; ?>/<?php echo $testII['question_type']; ?>/<?php echo $testII['question_no']; ?>/option3/<?php echo $testII['img_3']; ?>" />
                                            </div>
                                            <div clas="col-3" style="text-align: center;">
                                                <img class="img-option" style="width: 150px; height: auto; border-radius: 2%; margin-top: 10px;" src="../../assets/uploads/<?php echo $department; ?>/<?php echo $division; ?>/<?php echo $exam_id; ?>/<?php echo $testII['question_type']; ?>/<?php echo $testII['question_no']; ?>/option4/<?php echo $testII['img_4']; ?>" />
                                            </div>
                                        </div>
                                    <?php }

                                    // 1-2 OR 2-1
                                    else if ($testII['img_1'] != null && $testII['img_2'] != null && $testII['img_3'] == null
                                        && $testII['img_4'] == null){ ?>
                                        <div class="row" style="justify-content: space-evenly;">
                                            <div clas="col-6" style="text-align: center;">
                                                <img class="img-option" style="width: 150px; height: auto; border-radius: 2%; margin-top: 10px;" src="../../assets/uploads/<?php echo $department; ?>/<?php echo $division; ?>/<?php echo $exam_id; ?>/<?php echo $testII['question_type']; ?>/<?php echo $testII['question_no']; ?>/option1/<?php echo $testII['img_1']; ?>" />
                                            </div>
                                            <div clas="col-6" style="text-align: center;">
                                                <img class="img-option" style="width: 150px; height: auto; border-radius: 2%; margin-top: 10px;" src="../../assets/uploads/<?php echo $department; ?>/<?php echo $division; ?>/<?php echo $exam_id; ?>/<?php echo $testII['question_type']; ?>/<?php echo $testII['question_no']; ?>/option2/<?php echo $testII['img_2']; ?>" />
                                            </div>
                                        </div>
                                    <?php }
                                    // 1-3 OR 3-1
                                    else if ($testII['img_1'] != null && $testII['img_2'] == null && $testII['img_3'] != null
                                        && $testII['img_4'] == null){ ?>
                                        <div class="row" style="justify-content: space-evenly;">
                                            <div clas="col-6" style="text-align: center;">
                                                <img class="img-option" style="width: 150px; height: auto; border-radius: 2%; margin-top: 10px;" src="../../assets/uploads/<?php echo $department; ?>/<?php echo $division; ?>/<?php echo $exam_id; ?>/<?php echo $testII['question_type']; ?>/<?php echo $testII['question_no']; ?>/option1/<?php echo $testII['img_1']; ?>" />
                                            </div>
                                            <div clas="col-6" style="text-align: center;">
                                                <img class="img-option" style="width: 150px; height: auto; border-radius: 2%; margin-top: 10px;" src="../../assets/uploads/<?php echo $department; ?>/<?php echo $division; ?>/<?php echo $exam_id; ?>/<?php echo $testII['question_type']; ?>/<?php echo $testII['question_no']; ?>/option3/<?php echo $testII['img_3']; ?>" />
                                            </div>
                                        </div>
                                    <?php }
                                    // 1-4 OR 4-1
                                    else if ($testII['img_1'] != null && $testII['img_2'] == null && $testII['img_3'] == null
                                        && $testII['img_4'] != null){ ?>
                                        <div class="row" style="justify-content: space-evenly;">
                                            <div clas="col-6" style="text-align: center;">
                                                <img class="img-option" style="width: 150px; height: auto; border-radius: 2%; margin-top: 10px;" src="../../assets/uploads/<?php echo $department; ?>/<?php echo $division; ?>/<?php echo $exam_id; ?>/<?php echo $testII['question_type']; ?>/<?php echo $testII['question_no']; ?>/option1/<?php echo $testII['img_1']; ?>" />
                                            </div>
                                            <div clas="col-6" style="text-align: center;">
                                                <img class="img-option" style="width: 150px; height: auto; border-radius: 2%; margin-top: 10px;" src="../../assets/uploads/<?php echo $department; ?>/<?php echo $division; ?>/<?php echo $exam_id; ?>/<?php echo $testII['question_type']; ?>/<?php echo $testII['question_no']; ?>/option4/<?php echo $testII['img_4']; ?>" />
                                            </div>
                                        </div>
                                    <?php }
                                    // 2-3 OR 3-2
                                    else if ($testII['img_1'] == null && $testII['img_2'] != null && $testII['img_3'] != null
                                        && $testII['img_4'] == null){ ?>
                                        <div class="row" style="justify-content: space-evenly;">
                                            <div clas="col-6" style="text-align: center;">
                                                <img class="img-option" style="width: 150px; height: auto; border-radius: 2%; margin-top: 10px;" src="../../assets/uploads/<?php echo $department; ?>/<?php echo $division; ?>/<?php echo $exam_id; ?>/<?php echo $testII['question_type']; ?>/<?php echo $testII['question_no']; ?>/option2/<?php echo $testII['img_2']; ?>" />
                                            </div>
                                            <div clas="col-6" style="text-align: center;">
                                                <img class="img-option" style="width: 150px; height: auto; border-radius: 2%; margin-top: 10px;" src="../../assets/uploads/<?php echo $department; ?>/<?php echo $division; ?>/<?php echo $exam_id; ?>/<?php echo $testII['question_type']; ?>/<?php echo $testII['question_no']; ?>/option3/<?php echo $testII['img_3']; ?>" />
                                            </div>
                                        </div>
                                    <?php }
                                    // 2-4 OR 4-2
                                    else if ($testII['img_1'] == null && $testII['img_2'] != null && $testII['img_3'] == null
                                        && $testII['img_4'] != null){ ?>
                                        <div class="row" style="justify-content: space-evenly;">
                                            <div clas="col-6" style="text-align: center;">
                                                <img class="img-option" style="width: 150px; height: auto; border-radius: 2%; margin-top: 10px;" src="../../assets/uploads/<?php echo $department; ?>/<?php echo $division; ?>/<?php echo $exam_id; ?>/<?php echo $testII['question_type']; ?>/<?php echo $testII['question_no']; ?>/option2/<?php echo $testII['img_2']; ?>" />
                                            </div>
                                            <div clas="col-6" style="text-align: center;">
                                                <img class="img-option" style="width: 150px; height: auto; border-radius: 2%; margin-top: 10px;" src="../../assets/uploads/<?php echo $department; ?>/<?php echo $division; ?>/<?php echo $exam_id; ?>/<?php echo $testII['question_type']; ?>/<?php echo $testII['question_no']; ?>/option4/<?php echo $testII['img_4']; ?>" />
                                            </div>
                                        </div>
                                    <?php }
                                    // 3-4 OR 4-3
                                    else if ($testII['img_1'] == null && $testII['img_2'] == null && $testII['img_3'] != null
                                        && $testII['img_4'] != null){ ?>
                                        <div class="row" style="justify-content: space-evenly;">
                                            <div clas="col-6" style="text-align: center;">
                                                <img class="img-option" style="width: 150px; height: auto; border-radius: 2%; margin-top: 10px;" src="../../assets/uploads/<?php echo $department; ?>/<?php echo $division; ?>/<?php echo $exam_id; ?>/<?php echo $testII['question_type']; ?>/<?php echo $testII['question_no']; ?>/option3/<?php echo $testII['img_3']; ?>" />
                                            </div>
                                            <div clas="col-6" style="text-align: center;">
                                                <img class="img-option" style="width: 150px; height: auto; border-radius: 2%; margin-top: 10px;" src="../../assets/uploads/<?php echo $department; ?>/<?php echo $division; ?>/<?php echo $exam_id; ?>/<?php echo $testII['question_type']; ?>/<?php echo $testII['question_no']; ?>/option4/<?php echo $testII['img_4']; ?>" />
                                            </div>
                                        </div>
                                    <?php }

                                    else if ($testII['img_1'] != null && $testII['img_2'] == null && $testII['img_3'] == null
                                        && $testII['img_4'] == null){ ?>
                                        <div class="row" style="justify-content: space-evenly;">
                                            <div clas="col-12" style="text-align: center;">
                                                <img class="img-option" style="width: 150px; height: auto; border-radius: 2%; margin-top: 10px;" src="../../assets/uploads/<?php echo $department; ?>/<?php echo $division; ?>/<?php echo $exam_id; ?>/<?php echo $testII['question_type']; ?>/<?php echo $testII['question_no']; ?>/option1/<?php echo $testII['img_1']; ?>" />
                                            </div>
                                        </div>
                                    <?php }
                                    else if ($testII['img_1'] == null && $testII['img_2'] != null && $testII['img_3'] == null
                                        && $testII['img_4'] == null){ ?>
                                        <div class="row" style="justify-content: space-evenly;">
                                            <div clas="col-12" style="text-align: center;">
                                                <img class="img-option" style="width: 150px; height: auto; border-radius: 2%; margin-top: 10px;" src="../../assets/uploads/<?php echo $department; ?>/<?php echo $division; ?>/<?php echo $exam_id; ?>/<?php echo $testII['question_type']; ?>/<?php echo $testII['question_no']; ?>/option2/<?php echo $testII['img_2']; ?>" />
                                            </div>
                                        </div>
                                    <?php }
                                    else if ($testII['img_1'] == null && $testII['img_2'] == null && $testII['img_3'] != null
                                        && $testII['img_4'] == null){ ?>
                                        <div class="row" style="justify-content: space-evenly;">
                                            <div clas="col-12" style="text-align: center;">
                                                <img class="img-option" style="width: 150px; height: auto; border-radius: 2%; margin-top: 10px;" src="../../assets/uploads/<?php echo $department; ?>/<?php echo $division; ?>/<?php echo $exam_id; ?>/<?php echo $testII['question_type']; ?>/<?php echo $testII['question_no']; ?>/option3/<?php echo $testII['img_3']; ?>" />
                                            </div>
                                        </div>
                                    <?php }
                                    else if ($testII['img_1'] == null && $testII['img_2'] == null && $testII['img_3'] == null
                                        && $testII['img_4'] != null){ ?>
                                        <div class="row" style="justify-content: space-evenly;">
                                            <div clas="col-12" style="text-align: center;">
                                                <img class="img-option" style="width: 150px; height: auto; border-radius: 2%; margin-top: 10px;" src="../../assets/uploads/<?php echo $department; ?>/<?php echo $division; ?>/<?php echo $exam_id; ?>/<?php echo $testII['question_type']; ?>/<?php echo $testII['question_no']; ?>/option4/<?php echo $testII['img_4']; ?>" />
                                            </div>
                                        </div>
                                    <?php } ?>
                                    <br>
                                    <span>Answer: <?php echo $testII['ans']; ?></span>
                                </div>
                                <div id="multipleImageContainer<?php echo $testII['option_id']; ?>" class="col-lg-12 pt-2" style="display: none;">
                                    <?php
                                    if ($testII['img_1'] != null && $testII['img_2'] != null && $testII['img_3'] != null && $testII['img_4'] != null){ ?>
                                        <div class="row" style="justify-content: space-evenly;">
                                            <div clas="col-3" style="text-align: center;">
                                                <img class="img-option" style="width: 150px; height: auto; border-radius: 2%; margin-top: 10px;" src="../../assets/uploads/<?php echo $department; ?>/<?php echo $division; ?>/<?php echo $exam_id; ?>/<?php echo $testII['question_type']; ?>/<?php echo $testII['question_no']; ?>/option1/<?php echo $testII['img_1']; ?>" />
                                                <h4><?php echo $testII['option_1']; ?></h4>
                                            </div>
                                            <div clas="col-3" style="text-align: center;">
                                                <img class="img-option" style="width: 150px; height: auto; border-radius: 2%; margin-top: 10px;" src="../../assets/uploads/<?php echo $department; ?>/<?php echo $division; ?>/<?php echo $exam_id; ?>/<?php echo $testII['question_type']; ?>/<?php echo $testII['question_no']; ?>/option2/<?php echo $testII['img_2']; ?>" />
                                                <h4><?php echo $testII['option_2']; ?></h4>
                                            </div>
                                            <div clas="col-3" style="text-align: center;">
                                                <img class="img-option" style="width: 150px; height: auto; border-radius: 2%; margin-top: 10px;" src="../../assets/uploads/<?php echo $department; ?>/<?php echo $division; ?>/<?php echo $exam_id; ?>/<?php echo $testII['question_type']; ?>/<?php echo $testII['question_no']; ?>/option3/<?php echo $testII['img_3']; ?>" />
                                                <h4><?php echo $testII['option_3']; ?></h4>
                                            </div>
                                            <div clas="col-3" style="text-align: center;">
                                                <img class="img-option" style="width: 150px; height: auto; border-radius: 2%; margin-top: 10px;" src="../../assets/uploads/<?php echo $department; ?>/<?php echo $division; ?>/<?php echo $exam_id; ?>/<?php echo $testII['question_type']; ?>/<?php echo $testII['question_no']; ?>/option4/<?php echo $testII['img_4']; ?>" />
                                                <h4><?php echo $testII['option_4']; ?></h4>
                                            </div>
                                        </div>
                                    <?php } ?>
                                    <br>
                                    <span>Answer: <?php echo $testII['ans']; ?></span>
                                </div>
                            </div>
                        </li>
                    <?php } ?>
                </ul>
            </div>
            <div class="tab-pane fade" id="test3" role="tabpanel" aria-labelledby="question-testIII">
                <ul class="list-group">
                    <?php
                    $index = 1;
                    $exam3 = $conn->query("SELECT b.`id` AS question_no, b.`points`, b.`question_type`, b.`question`,
                            c.`id` AS option_id, c.`o_id`, c.`option_1`, c.`option_2`, c.`option_3`, c.`option_4`, 
                            c.`img_1`, c.`img_2`, c.`img_3`, c.`img_4`, c.`ans` 
                            FROM question b
                            LEFT JOIN options c ON b.`id` = c.`id` 
                            WHERE b.`question_type` = '$test3' AND b.`q_id` = '$exam_id' ");
                    while ($testIII = $exam3->fetch()){ ?>
                        <script>
                            $(document).ready(function (){
                                var testIII = "<?php echo $test3; ?>";
                                if (testIII == "Short Answer"){
                                    $("#shortAnswerContainer<?php echo $testIII['option_id']; ?>").show();
                                    $("#multipleChoiceContainer<?php echo $testIII['option_id']; ?>").hide();
                                    $("#trueOrFalseContainer<?php echo $testIII['option_id']; ?>").hide();
                                    $("#multipleImageContainer<?php echo $testIII['option_id']; ?>").hide();
                                }else if (testIII == "True/False"){
                                    $("#shortAnswerContainer<?php echo $testIII['option_id']; ?>").hide();
                                    $("#multipleChoiceContainer<?php echo $testIII['option_id']; ?>").hide();
                                    $("#trueOrFalseContainer<?php echo $testIII['option_id']; ?>").show();
                                    $("#multipleImageContainer<?php echo $testIII['option_id']; ?>").hide();
                                }else if (testIII == "Multiple Choice"){
                                    $("#shortAnswerContainer<?php echo $testIII['option_id']; ?>").hide();
                                    $("#multipleChoiceContainer<?php echo $testIII['option_id']; ?>").show();
                                    $("#trueOrFalseContainer<?php echo $testIII['option_id']; ?>").hide();
                                    $("#multipleImageContainer<?php echo $testIII['option_id']; ?>").hide();
                                }else if (testIII == "Multiple Image"){
                                    $("#shortAnswerContainer<?php echo $testIII['option_id']; ?>").hide();
                                    $("#multipleChoiceContainer<?php echo $testIII['option_id']; ?>").hide();
                                    $("#trueOrFalseContainer<?php echo $testIII['option_id']; ?>").hide();
                                    $("#multipleImageContainer<?php echo $testIII['option_id']; ?>").show();
                                }
                            });
                        </script>
                        <li class="list-group-item">
                            <div class="row pl-3 pr-3">
                                <span><?php echo $index++.".&nbsp;".$testIII['question']." [".$testIII['points']." point/s]"; ?></span>
                                <div id="shortAnswerContainer<?php echo $testIII['option_id']; ?>" class="col-lg-12" style="display: none;">
                                    <span>Answer: <?php echo $testIII['ans']; ?></span>
                                </div>
                                <div id="multipleChoiceContainer<?php echo $testIII['option_id']; ?>" class="col-lg-12" style="display: none;">
                                    <?php
                                    if ($testIII['img_1'] != null && $testIII['img_2'] != null && $testIII['img_3'] != null && $testIII['img_4'] != null){ ?>
                                        <div class="row" style="justify-content: space-evenly;">
                                            <div clas="col-3" style="text-align: center;">
                                                <img class="img-option" style="width: 150px; height: auto; border-radius: 2%; margin-top: 10px;" src="../../assets/uploads/<?php echo $department; ?>/<?php echo $division; ?>/<?php echo $exam_id; ?>/<?php echo $testIII['question_type']; ?>/<?php echo $testIII['question_no']; ?>/option1/<?php echo $testIII['img_1']; ?>"/>
                                                <h4>I</h4>
                                            </div>
                                            <div clas="col-3" style="text-align: center;">
                                                <img class="img-option" style="width: 150px; height: auto; border-radius: 2%; margin-top: 10px;" src="../../assets/uploads/<?php echo $department; ?>/<?php echo $division; ?>/<?php echo $exam_id; ?>/<?php echo $testIII['question_type']; ?>/<?php echo $testIII['question_no']; ?>/option2/<?php echo $testIII['img_2']; ?>"/>
                                                <h4>II</h4>
                                            </div>
                                            <div clas="col-3" style="text-align: center;">
                                                <img class="img-option" style="width: 150px; height: auto; border-radius: 2%; margin-top: 10px;" src="../../assets/uploads/<?php echo $department; ?>/<?php echo $division; ?>/<?php echo $exam_id; ?>/<?php echo $testIII['question_type']; ?>/<?php echo $testIII['question_no']; ?>/option3/<?php echo $testIII['img_3']; ?>"/>
                                                <h4>III</h4>
                                            </div>
                                            <div clas="col-3" style="text-align: center;">
                                                <img class="img-option" style="width: 150px; height: auto; border-radius: 2%; margin-top: 10px;" src="../../assets/uploads/<?php echo $department; ?>/<?php echo $division; ?>/<?php echo $exam_id; ?>/<?php echo $testIII['question_type']; ?>/<?php echo $testIII['question_no']; ?>/option4/<?php echo $testIII['img_4']; ?>"/>
                                                <h4>IV</h4>
                                            </div>
                                        </div>
                                    <?php }
                                    else if ($testIII['img_1'] != null && $testIII['img_2'] != null && $testIII['img_3'] != null
                                        && $testIII['img_4'] == null){ ?>
                                        <div class="row" style="justify-content: space-evenly;">
                                            <div clas="col-4" style="text-align: center;">
                                                <img class="img-option" style="width: 150px; height: auto; border-radius: 2%; margin-top: 10px;" src="../../assets/uploads/<?php echo $department; ?>/<?php echo $division; ?>/<?php echo $exam_id; ?>/<?php echo $testIII['question_type']; ?>/<?php echo $testIII['question_no']; ?>/option1/<?php echo $testIII['img_1']; ?>"/>
                                                <h4>I</h4>
                                            </div>
                                            <div clas="col-4" style="text-align: center;">
                                                <img class="img-option" style="width: 150px; height: auto; border-radius: 2%; margin-top: 10px;" src="../../assets/uploads/<?php echo $department; ?>/<?php echo $division; ?>/<?php echo $exam_id; ?>/<?php echo $testIII['question_type']; ?>/<?php echo $testIII['question_no']; ?>/option2/<?php echo $testIII['img_2']; ?>"/>
                                                <h4>II</h4>
                                            </div>
                                            <div clas="col-4" style="text-align: center;">
                                                <img class="img-option" style="width: 150px; height: auto; border-radius: 2%; margin-top: 10px;" src="../../assets/uploads/<?php echo $department; ?>/<?php echo $division; ?>/<?php echo $exam_id; ?>/<?php echo $testIII['question_type']; ?>/<?php echo $testIII['question_no']; ?>/option3/<?php echo $testIII['img_3']; ?>"/>
                                                <h4>III</h4>
                                            </div>
                                        </div>
                                    <?php }
                                    else if ($testIII['img_1'] != null && $testIII['img_2'] != null && $testIII['img_3'] == null
                                        && $testIII['img_4'] != null){ ?>
                                        <div class="row" style="justify-content: space-evenly;">
                                            <div clas="col-4" style="text-align: center;">
                                                <img class="img-option" style="width: 150px; height: auto; border-radius: 2%; margin-top: 10px;" src="../../assets/uploads/<?php echo $department; ?>/<?php echo $division; ?>/<?php echo $exam_id; ?>/<?php echo $testIII['question_type']; ?>/<?php echo $testIII['question_no']; ?>/option1/<?php echo $testIII['img_1']; ?>"/>
                                                <h4>I</h4>
                                            </div>
                                            <div clas="col-4" style="text-align: center;">
                                                <img class="img-option" style="width: 150px; height: auto; border-radius: 2%; margin-top: 10px;" src="../../assets/uploads/<?php echo $department; ?>/<?php echo $division; ?>/<?php echo $exam_id; ?>/<?php echo $testIII['question_type']; ?>/<?php echo $testIII['question_no']; ?>/option2/<?php echo $testIII['img_2']; ?>"/>
                                                <h4>II</h4>
                                            </div>
                                            <div clas="col-3" style="text-align: center;">
                                                <img class="img-option" style="width: 150px; height: auto; border-radius: 2%; margin-top: 10px;" src="../../assets/uploads/<?php echo $department; ?>/<?php echo $division; ?>/<?php echo $exam_id; ?>/<?php echo $testIII['question_type']; ?>/<?php echo $testIII['question_no']; ?>/option4/<?php echo $testIII['img_4']; ?>"/>
                                                <h4>III</h4>
                                            </div>
                                        </div>
                                    <?php }
                                    else if ($testIII['img_1'] != null && $testIII['img_2'] == null && $testIII['img_3'] != null
                                        && $testIII['img_4'] != null){ ?>
                                        <div class="row" style="justify-content: space-evenly;">
                                            <div clas="col-4" style="text-align: center;">
                                                <img class="img-option" style="width: 150px; height: auto; border-radius: 2%; margin-top: 10px;" src="../../assets/uploads/<?php echo $department; ?>/<?php echo $division; ?>/<?php echo $exam_id; ?>/<?php echo $testIII['question_type']; ?>/<?php echo $testIII['question_no']; ?>/option1/<?php echo $testIII['img_1']; ?>"/>
                                                <h4>I</h4>
                                            </div>
                                            <div clas="col-4" style="text-align: center;">
                                                <img class="img-option" style="width: 150px; height: auto; border-radius: 2%; margin-top: 10px;" src="../../assets/uploads/<?php echo $department; ?>/<?php echo $division; ?>/<?php echo $exam_id; ?>/<?php echo $testIII['question_type']; ?>/<?php echo $testIII['question_no']; ?>/option3/<?php echo $testIII['img_3']; ?>"/>
                                                <h4>II</h4>
                                            </div>
                                            <div clas="col-3" style="text-align: center;">
                                                <img class="img-option" style="width: 150px; height: auto; border-radius: 2%; margin-top: 10px;" src="../../assets/uploads/<?php echo $department; ?>/<?php echo $division; ?>/<?php echo $exam_id; ?>/<?php echo $testIII['question_type']; ?>/<?php echo $testIII['question_no']; ?>/option4/<?php echo $testIII['img_4']; ?>"/>
                                                <h4>III</h4>
                                            </div>
                                        </div>
                                    <?php }
                                    else if ($testIII['img_1'] == null && $testIII['img_2'] != null && $testIII['img_3'] != null
                                        && $testIII['img_4'] != null){ ?>
                                        <div class="row" style="justify-content: space-evenly;">
                                            <div clas="col-4" style="text-align: center;">
                                                <img class="img-option" style="width: 150px; height: auto; border-radius: 2%; margin-top: 10px;" src="../../assets/uploads/<?php echo $department; ?>/<?php echo $division; ?>/<?php echo $exam_id; ?>/<?php echo $testIII['question_type']; ?>/<?php echo $testIII['question_no']; ?>/option2/<?php echo $testIII['img_2']; ?>"/>
                                                <h4>I</h4>
                                            </div>
                                            <div clas="col-4" style="text-align: center;">
                                                <img class="img-option" style="width: 150px; height: auto; border-radius: 2%; margin-top: 10px;" src="../../assets/uploads/<?php echo $department; ?>/<?php echo $division; ?>/<?php echo $exam_id; ?>/<?php echo $testIII['question_type']; ?>/<?php echo $testIII['question_no']; ?>/option3/<?php echo $testIII['img_3']; ?>"/>
                                                <h4>II</h4>
                                            </div>
                                            <div clas="col-3" style="text-align: center;">
                                                <img class="img-option" style="width: 150px; height: auto; border-radius: 2%; margin-top: 10px;" src="../../assets/uploads/<?php echo $department; ?>/<?php echo $division; ?>/<?php echo $exam_id; ?>/<?php echo $testIII['question_type']; ?>/<?php echo $testIII['question_no']; ?>/option4/<?php echo $testIII['img_4']; ?>"/>
                                                <h4>III</h4>
                                            </div>
                                        </div>
                                    <?php }

                                    // 1-2 OR 2-1
                                    else if ($testIII['img_1'] != null && $testIII['img_2'] != null && $testIII['img_3'] == null
                                        && $testIII['img_4'] == null){ ?>
                                        <div class="row" style="justify-content: space-evenly;">
                                            <div clas="col-6" style="text-align: center;">
                                                <img class="img-option" style="width: 150px; height: auto; border-radius: 2%; margin-top: 10px;" src="../../assets/uploads/<?php echo $department; ?>/<?php echo $division; ?>/<?php echo $exam_id; ?>/<?php echo $testIII['question_type']; ?>/<?php echo $testIII['question_no']; ?>/option1/<?php echo $testIII['img_1']; ?>"/>
                                                <h4>I</h4>
                                            </div>
                                            <div clas="col-6" style="text-align: center;">
                                                <img class="img-option" style="width: 150px; height: auto; border-radius: 2%; margin-top: 10px;" src="../../assets/uploads/<?php echo $department; ?>/<?php echo $division; ?>/<?php echo $exam_id; ?>/<?php echo $testIII['question_type']; ?>/<?php echo $testIII['question_no']; ?>/option2/<?php echo $testIII['img_2']; ?>"/>
                                                <h4>II</h4>
                                            </div>
                                        </div>
                                    <?php }
                                    // 1-3 OR 3-1
                                    else if ($testIII['img_1'] != null && $testIII['img_2'] == null && $testIII['img_3'] != null
                                        && $testIII['img_4'] == null){ ?>
                                        <div class="row" style="justify-content: space-evenly;">
                                            <div clas="col-6" style="text-align: center;">
                                                <img class="img-option" style="width: 150px; height: auto; border-radius: 2%; margin-top: 10px;" src="../../assets/uploads/<?php echo $department; ?>/<?php echo $division; ?>/<?php echo $exam_id; ?>/<?php echo $testIII['question_type']; ?>/<?php echo $testIII['question_no']; ?>/option1/<?php echo $testIII['img_1']; ?>"/>
                                                <h4>I</h4>
                                            </div>
                                            <div clas="col-6" style="text-align: center;">
                                                <img class="img-option" style="width: 150px; height: auto; border-radius: 2%; margin-top: 10px;" src="../../assets/uploads/<?php echo $department; ?>/<?php echo $division; ?>/<?php echo $exam_id; ?>/<?php echo $testIII['question_type']; ?>/<?php echo $testIII['question_no']; ?>/option3/<?php echo $testIII['img_3']; ?>"/>
                                                <h4>II</h4>
                                            </div>
                                        </div>
                                    <?php }
                                    // 1-4 OR 4-1
                                    else if ($testIII['img_1'] != null && $testIII['img_2'] == null && $testIII['img_3'] == null
                                        && $testIII['img_4'] != null){ ?>
                                        <div class="row" style="justify-content: space-evenly;">
                                            <div clas="col-6" style="text-align: center;">
                                                <img class="img-option" style="width: 150px; height: auto; border-radius: 2%; margin-top: 10px;" src="../../assets/uploads/<?php echo $department; ?>/<?php echo $division; ?>/<?php echo $exam_id; ?>/<?php echo $testIII['question_type']; ?>/<?php echo $testIII['question_no']; ?>/option1/<?php echo $testIII['img_1']; ?>"/>
                                                <h4>I</h4>
                                            </div>
                                            <div clas="col-6" style="text-align: center;">
                                                <img class="img-option" style="width: 150px; height: auto; border-radius: 2%; margin-top: 10px;" src="../../assets/uploads/<?php echo $department; ?>/<?php echo $division; ?>/<?php echo $exam_id; ?>/<?php echo $testIII['question_type']; ?>/<?php echo $testIII['question_no']; ?>/option4/<?php echo $testIII['img_4']; ?>"/>
                                                <h4>II</h4>
                                            </div>
                                        </div>
                                    <?php }
                                    // 2-3 OR 3-2
                                    else if ($testIII['img_1'] == null && $testIII['img_2'] != null && $testIII['img_3'] != null
                                        && $testIII['img_4'] == null){ ?>
                                        <div class="row" style="justify-content: space-evenly;">
                                            <div clas="col-6" style="text-align: center;">
                                                <img class="img-option" style="width: 150px; height: auto; border-radius: 2%; margin-top: 10px;" src="../../assets/uploads/<?php echo $department; ?>/<?php echo $division; ?>/<?php echo $exam_id; ?>/<?php echo $testIII['question_type']; ?>/<?php echo $testIII['question_no']; ?>/option2/<?php echo $testIII['img_2']; ?>"/>
                                                <h4>I</h4>
                                            </div>
                                            <div clas="col-6" style="text-align: center;">
                                                <img class="img-option" style="width: 150px; height: auto; border-radius: 2%; margin-top: 10px;" src="../../assets/uploads/<?php echo $department; ?>/<?php echo $division; ?>/<?php echo $exam_id; ?>/<?php echo $testIII['question_type']; ?>/<?php echo $testIII['question_no']; ?>/option3/<?php echo $testIII['img_3']; ?>"/>
                                                <h4>II</h4>
                                            </div>
                                        </div>
                                    <?php }
                                    // 2-4 OR 4-2
                                    else if ($testIII['img_1'] == null && $testIII['img_2'] != null && $testIII['img_3'] == null
                                        && $testIII['img_4'] != null){ ?>
                                        <div class="row" style="justify-content: space-evenly;">
                                            <div clas="col-6" style="text-align: center;">
                                                <img class="img-option" style="width: 150px; height: auto; border-radius: 2%; margin-top: 10px;" src="../../assets/uploads/<?php echo $department; ?>/<?php echo $division; ?>/<?php echo $exam_id; ?>/<?php echo $testIII['question_type']; ?>/<?php echo $testIII['question_no']; ?>/option2/<?php echo $testIII['img_2']; ?>"/>
                                                <h4>I</h4>
                                            </div>
                                            <div clas="col-6" style="text-align: center;">
                                                <img class="img-option" style="width: 150px; height: auto; border-radius: 2%; margin-top: 10px;" src="../../assets/uploads/<?php echo $department; ?>/<?php echo $division; ?>/<?php echo $exam_id; ?>/<?php echo $testIII['question_type']; ?>/<?php echo $testIII['question_no']; ?>/option4/<?php echo $testIII['img_4']; ?>"/>
                                                <h4>II</h4>
                                            </div>
                                        </div>
                                    <?php }
                                    // 3-4 OR 4-3
                                    else if ($testIII['img_1'] == null && $testIII['img_2'] == null && $testIII['img_3'] != null
                                        && $testIII['img_4'] != null){ ?>
                                        <div class="row" style="justify-content: space-evenly;">
                                            <div clas="col-6" style="text-align: center;">
                                                <img class="img-option" style="width: 150px; height: auto; border-radius: 2%; margin-top: 10px;" src="../../assets/uploads/<?php echo $department; ?>/<?php echo $division; ?>/<?php echo $exam_id; ?>/<?php echo $testIII['question_type']; ?>/<?php echo $testIII['question_no']; ?>/option3/<?php echo $testIII['img_3']; ?>"/>
                                                <h4>I/h4>
                                            </div>
                                            <div clas="col-6" style="text-align: center;">
                                                <img class="img-option" style="width: 150px; height: auto; border-radius: 2%; margin-top: 10px;" src="../../assets/uploads/<?php echo $department; ?>/<?php echo $division; ?>/<?php echo $exam_id; ?>/<?php echo $testIII['question_type']; ?>/<?php echo $testIII['question_no']; ?>/option4/<?php echo $testIII['img_4']; ?>"/>
                                                <h4>II</h4>
                                            </div>
                                        </div>
                                    <?php }

                                    else if ($testIII['img_1'] != null && $testIII['img_2'] == null && $testIII['img_3'] == null
                                        && $testIII['img_4'] == null){ ?>
                                        <div class="row" style="justify-content: space-evenly;">
                                            <div clas="col-12" style="text-align: center;">
                                                <img class="img-option" style="width: 150px; height: auto; border-radius: 2%; margin-top: 10px;" src="../../assets/uploads/<?php echo $department; ?>/<?php echo $division; ?>/<?php echo $exam_id; ?>/<?php echo $testIII['question_type']; ?>/<?php echo $testIII['question_no']; ?>/option1/<?php echo $testIII['img_1']; ?>"/>
                                                <h4>I</h4>
                                            </div>
                                        </div>
                                    <?php }
                                    else if ($testIII['img_1'] == null && $testIII['img_2'] != null && $testIII['img_3'] == null
                                        && $testIII['img_4'] == null){ ?>
                                        <div class="row" style="justify-content: space-evenly;">
                                            <div clas="col-12" style="text-align: center;">
                                                <img class="img-option" style="width: 150px; height: auto; border-radius: 2%; margin-top: 10px;" src="../../assets/uploads/<?php echo $department; ?>/<?php echo $division; ?>/<?php echo $exam_id; ?>/<?php echo $testIII['question_type']; ?>/<?php echo $testIII['question_no']; ?>/option2/<?php echo $testIII['img_2']; ?>"/>
                                                <h4>I</h4>
                                            </div>
                                        </div>
                                    <?php }
                                    else if ($testIII['img_1'] == null && $testIII['img_2'] == null && $testIII['img_3'] != null
                                        && $testIII['img_4'] == null){ ?>
                                        <div class="row" style="justify-content: space-evenly;">
                                            <div clas="col-12" style="text-align: center;">
                                                <img class="img-option" style="width: 150px; height: auto; border-radius: 2%; margin-top: 10px;" src="../../assets/uploads/<?php echo $department; ?>/<?php echo $division; ?>/<?php echo $exam_id; ?>/<?php echo $testIII['question_type']; ?>/<?php echo $testIII['question_no']; ?>/option3/<?php echo $testIII['img_3']; ?>"/>
                                                <h4>I</h4>
                                            </div>
                                        </div>
                                    <?php }
                                    else if ($testIII['img_1'] == null && $testIII['img_2'] == null && $testIII['img_3'] == null
                                        && $testIII['img_4'] != null){ ?>
                                        <div class="row" style="justify-content: space-evenly;">
                                            <div clas="col-12" style="text-align: center;">
                                                <img class="img-option" style="width: 150px; height: auto; border-radius: 2%; margin-top: 10px;" src="../../assets/uploads/<?php echo $department; ?>/<?php echo $division; ?>/<?php echo $exam_id; ?>/<?php echo $testIII['question_type']; ?>/<?php echo $testIII['question_no']; ?>/option4/<?php echo $testIII['img_4']; ?>"/>
                                                <h4>I</h4>
                                            </div>
                                        </div>
                                    <?php } ?>
                                    <span>Option 1: <?php echo $testIII['option_1']; ?></span><br>
                                    <span>Option 2: <?php echo $testIII['option_2']; ?></span><br>
                                    <span>Option 3: <?php echo $testIII['option_3']; ?></span><br>
                                    <span>Option 4: <?php echo $testIII['option_4']; ?></span><br>
                                    <span>Answer: <?php echo $testIII['ans']; ?></span>
                                </div>
                                <div id="trueOrFalseContainer<?php echo $testIII['option_id']; ?>" class="col-lg-12 pt-2"  style="display: none;" >
                                    <?php
                                    if ($testIII['img_1'] != null && $testIII['img_2'] != null && $testIII['img_3'] != null && $testIII['img_4'] != null){ ?>
                                        <div class="row" style="justify-content: space-evenly;">
                                            <div clas="col-3" style="text-align: center;">
                                                <img class="img-option" style="width: 150px; height: auto; border-radius: 2%; margin-top: 10px;" src="../../assets/uploads/<?php echo $department; ?>/<?php echo $division; ?>/<?php echo $exam_id; ?>/<?php echo $testIII['question_type']; ?>/<?php echo $testIII['question_no']; ?>/option1/<?php echo $testIII['img_1']; ?>" />
                                            </div>
                                            <div clas="col-3" style="text-align: center;">
                                                <img class="img-option" style="width: 150px; height: auto; border-radius: 2%; margin-top: 10px;" src="../../assets/uploads/<?php echo $department; ?>/<?php echo $division; ?>/<?php echo $exam_id; ?>/<?php echo $testIII['question_type']; ?>/<?php echo $testIII['question_no']; ?>/option2/<?php echo $testIII['img_2']; ?>" />
                                            </div>
                                            <div clas="col-3" style="text-align: center;">
                                                <img class="img-option" style="width: 150px; height: auto; border-radius: 2%; margin-top: 10px;" src="../../assets/uploads/<?php echo $department; ?>/<?php echo $division; ?>/<?php echo $exam_id; ?>/<?php echo $testIII['question_type']; ?>/<?php echo $testIII['question_no']; ?>/option3/<?php echo $testIII['img_3']; ?>" />
                                            </div>
                                            <div clas="col-3" style="text-align: center;">
                                                <img class="img-option" style="width: 150px; height: auto; border-radius: 2%; margin-top: 10px;" src="../../assets/uploads/<?php echo $department; ?>/<?php echo $division; ?>/<?php echo $exam_id; ?>/<?php echo $testIII['question_type']; ?>/<?php echo $testIII['question_no']; ?>/option4/<?php echo $testIII['img_4']; ?>" />
                                            </div>
                                        </div>
                                    <?php }
                                    else if ($testIII['img_1'] != null && $testIII['img_2'] != null && $testIII['img_3'] != null
                                        && $testIII['img_4'] == null){ ?>
                                        <div class="row" style="justify-content: space-evenly;">
                                            <div clas="col-4" style="text-align: center;">
                                                <img class="img-option" style="width: 150px; height: auto; border-radius: 2%; margin-top: 10px;" src="../../assets/uploads/<?php echo $department; ?>/<?php echo $division; ?>/<?php echo $exam_id; ?>/<?php echo $testIII['question_type']; ?>/<?php echo $testIII['question_no']; ?>/option1/<?php echo $testIII['img_1']; ?>" />
                                            </div>
                                            <div clas="col-4" style="text-align: center;">
                                                <img class="img-option" style="width: 150px; height: auto; border-radius: 2%; margin-top: 10px;" src="../../assets/uploads/<?php echo $department; ?>/<?php echo $division; ?>/<?php echo $exam_id; ?>/<?php echo $testIII['question_type']; ?>/<?php echo $testIII['question_no']; ?>/option2/<?php echo $testIII['img_2']; ?>" />
                                            </div>
                                            <div clas="col-4" style="text-align: center;">
                                                <img class="img-option" style="width: 150px; height: auto; border-radius: 2%; margin-top: 10px;" src="../../assets/uploads/<?php echo $department; ?>/<?php echo $division; ?>/<?php echo $exam_id; ?>/<?php echo $testIII['question_type']; ?>/<?php echo $testIII['question_no']; ?>/option3/<?php echo $testIII['img_3']; ?>" />
                                            </div>
                                        </div>
                                    <?php }
                                    else if ($testIII['img_1'] != null && $testIII['img_2'] != null && $testIII['img_3'] == null
                                        && $testIII['img_4'] != null){ ?>
                                        <div class="row" style="justify-content: space-evenly;">
                                            <div clas="col-4" style="text-align: center;">
                                                <img class="img-option" style="width: 150px; height: auto; border-radius: 2%; margin-top: 10px;" src="../../assets/uploads/<?php echo $department; ?>/<?php echo $division; ?>/<?php echo $exam_id; ?>/<?php echo $testIII['question_type']; ?>/<?php echo $testIII['question_no']; ?>/option1/<?php echo $testIII['img_1']; ?>" />
                                            </div>
                                            <div clas="col-4" style="text-align: center;">
                                                <img class="img-option" style="width: 150px; height: auto; border-radius: 2%; margin-top: 10px;" src="../../assets/uploads/<?php echo $department; ?>/<?php echo $division; ?>/<?php echo $exam_id; ?>/<?php echo $testIII['question_type']; ?>/<?php echo $testIII['question_no']; ?>/option2/<?php echo $testIII['img_2']; ?>" />
                                            </div>
                                            <div clas="col-3" style="text-align: center;">
                                                <img class="img-option" style="width: 150px; height: auto; border-radius: 2%; margin-top: 10px;" src="../../assets/uploads/<?php echo $department; ?>/<?php echo $division; ?>/<?php echo $exam_id; ?>/<?php echo $testIII['question_type']; ?>/<?php echo $testIII['question_no']; ?>/option4/<?php echo $testIII['img_4']; ?>" />
                                            </div>
                                        </div>
                                    <?php }
                                    else if ($testIII['img_1'] != null && $testIII['img_2'] == null && $testIII['img_3'] != null
                                        && $testIII['img_4'] != null){ ?>
                                        <div class="row" style="justify-content: space-evenly;">
                                            <div clas="col-4" style="text-align: center;">
                                                <img class="img-option" style="width: 150px; height: auto; border-radius: 2%; margin-top: 10px;" src="../../assets/uploads/<?php echo $department; ?>/<?php echo $division; ?>/<?php echo $exam_id; ?>/<?php echo $testIII['question_type']; ?>/<?php echo $testIII['question_no']; ?>/option1/<?php echo $testIII['img_1']; ?>" />
                                            </div>
                                            <div clas="col-4" style="text-align: center;">
                                                <img class="img-option" style="width: 150px; height: auto; border-radius: 2%; margin-top: 10px;" src="../../assets/uploads/<?php echo $department; ?>/<?php echo $division; ?>/<?php echo $exam_id; ?>/<?php echo $testIII['question_type']; ?>/<?php echo $testIII['question_no']; ?>/option3/<?php echo $testIII['img_3']; ?>" />
                                            </div>
                                            <div clas="col-3" style="text-align: center;">
                                                <img class="img-option" style="width: 150px; height: auto; border-radius: 2%; margin-top: 10px;" src="../../assets/uploads/<?php echo $department; ?>/<?php echo $division; ?>/<?php echo $exam_id; ?>/<?php echo $testIII['question_type']; ?>/<?php echo $testIII['question_no']; ?>/option4/<?php echo $testIII['img_4']; ?>" />
                                            </div>
                                        </div>
                                    <?php }
                                    else if ($testIII['img_1'] == null && $testIII['img_2'] != null && $testIII['img_3'] != null
                                        && $testIII['img_4'] != null){ ?>
                                        <div class="row" style="justify-content: space-evenly;">
                                            <div clas="col-4" style="text-align: center;">
                                                <img class="img-option" style="width: 150px; height: auto; border-radius: 2%; margin-top: 10px;" src="../../assets/uploads/<?php echo $department; ?>/<?php echo $division; ?>/<?php echo $exam_id; ?>/<?php echo $testIII['question_type']; ?>/<?php echo $testIII['question_no']; ?>/option2/<?php echo $testIII['img_2']; ?>" />
                                            </div>
                                            <div clas="col-4" style="text-align: center;">
                                                <img class="img-option" style="width: 150px; height: auto; border-radius: 2%; margin-top: 10px;" src="../../assets/uploads/<?php echo $department; ?>/<?php echo $division; ?>/<?php echo $exam_id; ?>/<?php echo $testIII['question_type']; ?>/<?php echo $testIII['question_no']; ?>/option3/<?php echo $testIII['img_3']; ?>" />
                                            </div>
                                            <div clas="col-3" style="text-align: center;">
                                                <img class="img-option" style="width: 150px; height: auto; border-radius: 2%; margin-top: 10px;" src="../../assets/uploads/<?php echo $department; ?>/<?php echo $division; ?>/<?php echo $exam_id; ?>/<?php echo $testIII['question_type']; ?>/<?php echo $testIII['question_no']; ?>/option4/<?php echo $testIII['img_4']; ?>" />
                                            </div>
                                        </div>
                                    <?php }

                                    // 1-2 OR 2-1
                                    else if ($testIII['img_1'] != null && $testIII['img_2'] != null && $testIII['img_3'] == null
                                        && $testIII['img_4'] == null){ ?>
                                        <div class="row" style="justify-content: space-evenly;">
                                            <div clas="col-6" style="text-align: center;">
                                                <img class="img-option" style="width: 150px; height: auto; border-radius: 2%; margin-top: 10px;" src="../../assets/uploads/<?php echo $department; ?>/<?php echo $division; ?>/<?php echo $exam_id; ?>/<?php echo $testIII['question_type']; ?>/<?php echo $testIII['question_no']; ?>/option1/<?php echo $testIII['img_1']; ?>" />
                                            </div>
                                            <div clas="col-6" style="text-align: center;">
                                                <img class="img-option" style="width: 150px; height: auto; border-radius: 2%; margin-top: 10px;" src="../../assets/uploads/<?php echo $department; ?>/<?php echo $division; ?>/<?php echo $exam_id; ?>/<?php echo $testIII['question_type']; ?>/<?php echo $testIII['question_no']; ?>/option2/<?php echo $testIII['img_2']; ?>" />
                                            </div>
                                        </div>
                                    <?php }
                                    // 1-3 OR 3-1
                                    else if ($testIII['img_1'] != null && $testIII['img_2'] == null && $testIII['img_3'] != null
                                        && $testIII['img_4'] == null){ ?>
                                        <div class="row" style="justify-content: space-evenly;">
                                            <div clas="col-6" style="text-align: center;">
                                                <img class="img-option" style="width: 150px; height: auto; border-radius: 2%; margin-top: 10px;" src="../../assets/uploads/<?php echo $department; ?>/<?php echo $division; ?>/<?php echo $exam_id; ?>/<?php echo $testIII['question_type']; ?>/<?php echo $testIII['question_no']; ?>/option1/<?php echo $testIII['img_1']; ?>" />
                                            </div>
                                            <div clas="col-6" style="text-align: center;">
                                                <img class="img-option" style="width: 150px; height: auto; border-radius: 2%; margin-top: 10px;" src="../../assets/uploads/<?php echo $department; ?>/<?php echo $division; ?>/<?php echo $exam_id; ?>/<?php echo $testIII['question_type']; ?>/<?php echo $testIII['question_no']; ?>/option3/<?php echo $testIII['img_3']; ?>" />
                                            </div>
                                        </div>
                                    <?php }
                                    // 1-4 OR 4-1
                                    else if ($testIII['img_1'] != null && $testIII['img_2'] == null && $testIII['img_3'] == null
                                        && $testIII['img_4'] != null){ ?>
                                        <div class="row" style="justify-content: space-evenly;">
                                            <div clas="col-6" style="text-align: center;">
                                                <img class="img-option" style="width: 150px; height: auto; border-radius: 2%; margin-top: 10px;" src="../../assets/uploads/<?php echo $department; ?>/<?php echo $division; ?>/<?php echo $exam_id; ?>/<?php echo $testIII['question_type']; ?>/<?php echo $testIII['question_no']; ?>/option1/<?php echo $testIII['img_1']; ?>" />
                                            </div>
                                            <div clas="col-6" style="text-align: center;">
                                                <img class="img-option" style="width: 150px; height: auto; border-radius: 2%; margin-top: 10px;" src="../../assets/uploads/<?php echo $department; ?>/<?php echo $division; ?>/<?php echo $exam_id; ?>/<?php echo $testIII['question_type']; ?>/<?php echo $testIII['question_no']; ?>/option4/<?php echo $testIII['img_4']; ?>" />
                                            </div>
                                        </div>
                                    <?php }
                                    // 2-3 OR 3-2
                                    else if ($testIII['img_1'] == null && $testIII['img_2'] != null && $testIII['img_3'] != null
                                        && $testIII['img_4'] == null){ ?>
                                        <div class="row" style="justify-content: space-evenly;">
                                            <div clas="col-6" style="text-align: center;">
                                                <img class="img-option" style="width: 150px; height: auto; border-radius: 2%; margin-top: 10px;" src="../../assets/uploads/<?php echo $department; ?>/<?php echo $division; ?>/<?php echo $exam_id; ?>/<?php echo $testIII['question_type']; ?>/<?php echo $testIII['question_no']; ?>/option2/<?php echo $testIII['img_2']; ?>" />
                                            </div>
                                            <div clas="col-6" style="text-align: center;">
                                                <img class="img-option" style="width: 150px; height: auto; border-radius: 2%; margin-top: 10px;" src="../../assets/uploads/<?php echo $department; ?>/<?php echo $division; ?>/<?php echo $exam_id; ?>/<?php echo $testIII['question_type']; ?>/<?php echo $testIII['question_no']; ?>/option3/<?php echo $testIII['img_3']; ?>" />
                                            </div>
                                        </div>
                                    <?php }
                                    // 2-4 OR 4-2
                                    else if ($testIII['img_1'] == null && $testIII['img_2'] != null && $testIII['img_3'] == null
                                        && $testIII['img_4'] != null){ ?>
                                        <div class="row" style="justify-content: space-evenly;">
                                            <div clas="col-6" style="text-align: center;">
                                                <img class="img-option" style="width: 150px; height: auto; border-radius: 2%; margin-top: 10px;" src="../../assets/uploads/<?php echo $department; ?>/<?php echo $division; ?>/<?php echo $exam_id; ?>/<?php echo $testIII['question_type']; ?>/<?php echo $testIII['question_no']; ?>/option2/<?php echo $testIII['img_2']; ?>" />
                                            </div>
                                            <div clas="col-6" style="text-align: center;">
                                                <img class="img-option" style="width: 150px; height: auto; border-radius: 2%; margin-top: 10px;" src="../../assets/uploads/<?php echo $department; ?>/<?php echo $division; ?>/<?php echo $exam_id; ?>/<?php echo $testIII['question_type']; ?>/<?php echo $testIII['question_no']; ?>/option4/<?php echo $testIII['img_4']; ?>" />
                                            </div>
                                        </div>
                                    <?php }
                                    // 3-4 OR 4-3
                                    else if ($testIII['img_1'] == null && $testIII['img_2'] == null && $testIII['img_3'] != null
                                        && $testIII['img_4'] != null){ ?>
                                        <div class="row" style="justify-content: space-evenly;">
                                            <div clas="col-6" style="text-align: center;">
                                                <img class="img-option" style="width: 150px; height: auto; border-radius: 2%; margin-top: 10px;" src="../../assets/uploads/<?php echo $department; ?>/<?php echo $division; ?>/<?php echo $exam_id; ?>/<?php echo $testIII['question_type']; ?>/<?php echo $testIII['question_no']; ?>/option3/<?php echo $testIII['img_3']; ?>" />
                                            </div>
                                            <div clas="col-6" style="text-align: center;">
                                                <img class="img-option" style="width: 150px; height: auto; border-radius: 2%; margin-top: 10px;" src="../../assets/uploads/<?php echo $department; ?>/<?php echo $division; ?>/<?php echo $exam_id; ?>/<?php echo $testIII['question_type']; ?>/<?php echo $testIII['question_no']; ?>/option4/<?php echo $testIII['img_4']; ?>" />
                                            </div>
                                        </div>
                                    <?php }

                                    else if ($testIII['img_1'] != null && $testIII['img_2'] == null && $testIII['img_3'] == null
                                        && $testIII['img_4'] == null){ ?>
                                        <div class="row" style="justify-content: space-evenly;">
                                            <div clas="col-12" style="text-align: center;">
                                                <img class="img-option" style="width: 150px; height: auto; border-radius: 2%; margin-top: 10px;" src="../../assets/uploads/<?php echo $department; ?>/<?php echo $division; ?>/<?php echo $exam_id; ?>/<?php echo $testIII['question_type']; ?>/<?php echo $testIII['question_no']; ?>/option1/<?php echo $testIII['img_1']; ?>" />
                                            </div>
                                        </div>
                                    <?php }
                                    else if ($testIII['img_1'] == null && $testIII['img_2'] != null && $testIII['img_3'] == null
                                        && $testIII['img_4'] == null){ ?>
                                        <div class="row" style="justify-content: space-evenly;">
                                            <div clas="col-12" style="text-align: center;">
                                                <img class="img-option" style="width: 150px; height: auto; border-radius: 2%; margin-top: 10px;" src="../../assets/uploads/<?php echo $department; ?>/<?php echo $division; ?>/<?php echo $exam_id; ?>/<?php echo $testIII['question_type']; ?>/<?php echo $testIII['question_no']; ?>/option2/<?php echo $testIII['img_2']; ?>" />
                                            </div>
                                        </div>
                                    <?php }
                                    else if ($testIII['img_1'] == null && $testIII['img_2'] == null && $testIII['img_3'] != null
                                        && $testIII['img_4'] == null){ ?>
                                        <div class="row" style="justify-content: space-evenly;">
                                            <div clas="col-12" style="text-align: center;">
                                                <img class="img-option" style="width: 150px; height: auto; border-radius: 2%; margin-top: 10px;" src="../../assets/uploads/<?php echo $department; ?>/<?php echo $division; ?>/<?php echo $exam_id; ?>/<?php echo $testIII['question_type']; ?>/<?php echo $testIII['question_no']; ?>/option3/<?php echo $testIII['img_3']; ?>" />
                                            </div>
                                        </div>
                                    <?php }
                                    else if ($testIII['img_1'] == null && $testIII['img_2'] == null && $testIII['img_3'] == null
                                        && $testIII['img_4'] != null){ ?>
                                        <div class="row" style="justify-content: space-evenly;">
                                            <div clas="col-12" style="text-align: center;">
                                                <img class="img-option" style="width: 150px; height: auto; border-radius: 2%; margin-top: 10px;" src="../../assets/uploads/<?php echo $department; ?>/<?php echo $division; ?>/<?php echo $exam_id; ?>/<?php echo $testIII['question_type']; ?>/<?php echo $testIII['question_no']; ?>/option4/<?php echo $testIII['img_4']; ?>" />
                                            </div>
                                        </div>
                                    <?php } ?>
                                    <br>
                                    <span>Answer: <?php echo $testIII['ans']; ?></span>
                                </div>
                                <div id="multipleImageContainer<?php echo $testIII['option_id']; ?>" class="col-lg-12 pt-2" style="display: none;">
                                    <?php
                                    if ($testIII['img_1'] != null && $testIII['img_2'] != null && $testIII['img_3'] != null && $testIII['img_4'] != null){ ?>
                                        <div class="row" style="justify-content: space-evenly;">
                                            <div clas="col-3" style="text-align: center;">
                                                <img class="img-option" style="width: 150px; height: auto; border-radius: 2%; margin-top: 10px;" src="../../assets/uploads/<?php echo $department; ?>/<?php echo $division; ?>/<?php echo $exam_id; ?>/<?php echo $testIII['question_type']; ?>/<?php echo $testIII['question_no']; ?>/option1/<?php echo $testIII['img_1']; ?>" />
                                                <h4><?php echo $testIII['option_1']; ?></h4>
                                            </div>
                                            <div clas="col-3" style="text-align: center;">
                                                <img class="img-option" style="width: 150px; height: auto; border-radius: 2%; margin-top: 10px;" src="../../assets/uploads/<?php echo $department; ?>/<?php echo $division; ?>/<?php echo $exam_id; ?>/<?php echo $testIII['question_type']; ?>/<?php echo $testIII['question_no']; ?>/option2/<?php echo $testIII['img_2']; ?>" />
                                                <h4><?php echo $testIII['option_2']; ?></h4>
                                            </div>
                                            <div clas="col-3" style="text-align: center;">
                                                <img class="img-option" style="width: 150px; height: auto; border-radius: 2%; margin-top: 10px;" src="../../assets/uploads/<?php echo $department; ?>/<?php echo $division; ?>/<?php echo $exam_id; ?>/<?php echo $testIII['question_type']; ?>/<?php echo $testIII['question_no']; ?>/option3/<?php echo $testIII['img_3']; ?>" />
                                                <h4><?php echo $testIII['option_3']; ?></h4>
                                            </div>
                                            <div clas="col-3" style="text-align: center;">
                                                <img class="img-option" style="width: 150px; height: auto; border-radius: 2%; margin-top: 10px;" src="../../assets/uploads/<?php echo $department; ?>/<?php echo $division; ?>/<?php echo $exam_id; ?>/<?php echo $testIII['question_type']; ?>/<?php echo $testIII['question_no']; ?>/option4/<?php echo $testIII['img_4']; ?>" />
                                                <h4><?php echo $testIII['option_4']; ?></h4>
                                            </div>
                                        </div>
                                    <?php } ?>
                                    <br>
                                    <span>Answer: <?php echo $testIII['ans']; ?></span>
                                </div>
                            </div>
                        </li>
                    <?php } ?>
                </ul>
            </div>
            <div class="tab-pane fade" id="test4" role="tabpanel" aria-labelledby="question-testIV">
                <ul class="list-group">
                    <?php
                    $index = 1;
                    $exam4 = $conn->query("SELECT b.`id` AS question_no, b.`points`, b.`question_type`, b.`question`,
                            c.`id` AS option_id, c.`o_id`, c.`option_1`, c.`option_2`, c.`option_3`, c.`option_4`, 
                            c.`img_1`, c.`img_2`, c.`img_3`, c.`img_4`, c.`ans` 
                            FROM question b
                            LEFT JOIN options c ON b.`id` = c.`id` 
                            WHERE b.`question_type` = '$test4' AND b.`q_id` = '$exam_id' ");
                    while ($testIV = $exam4->fetch()){ ?>
                        <script>
                            $(document).ready(function (){
                                var testIV = "<?php echo $test4; ?>";
                                if (testIV == "Short Answer"){
                                    $("#shortAnswerContainer<?php echo $testIV['option_id']; ?>").show();
                                    $("#multipleChoiceContainer<?php echo $testIV['option_id']; ?>").hide();
                                    $("#trueOrFalseContainer<?php echo $testIV['option_id']; ?>").hide();
                                    $("#multipleImageContainer<?php echo $testIV['option_id']; ?>").hide();
                                }else if (testIV == "True/False"){
                                    $("#shortAnswerContainer<?php echo $testIV['option_id']; ?>").hide();
                                    $("#multipleChoiceContainer<?php echo $testIV['option_id']; ?>").hide();
                                    $("#trueOrFalseContainer<?php echo $testIV['option_id']; ?>").show();
                                    $("#multipleImageContainer<?php echo $testIV['option_id']; ?>").hide();
                                }else if (testIV == "Multiple Choice"){
                                    $("#shortAnswerContainer<?php echo $testIV['option_id']; ?>").hide();
                                    $("#multipleChoiceContainer<?php echo $testIV['option_id']; ?>").show();
                                    $("#trueOrFalseContainer<?php echo $testIV['option_id']; ?>").hide();
                                    $("#multipleImageContainer<?php echo $testIV['option_id']; ?>").hide();
                                }else if (testIV == "Multiple Image"){
                                    $("#shortAnswerContainer<?php echo $testIV['option_id']; ?>").hide();
                                    $("#multipleChoiceContainer<?php echo $testIV['option_id']; ?>").hide();
                                    $("#trueOrFalseContainer<?php echo $testIV['option_id']; ?>").hide();
                                    $("#multipleImageContainer<?php echo $testIV['option_id']; ?>").show();
                                }
                            });
                        </script>
                        <li class="list-group-item">
                            <div class="row pl-3 pr-3">
                                <span><?php echo $index++.".&nbsp;".$testIV['question']." [".$testIV['points']." point/s]"; ?></span>
                                <div id="shortAnswerContainer<?php echo $testIV['option_id']; ?>" class="col-lg-12" style="display: none;">
                                    <span>Answer: <?php echo $testIV['ans']; ?></span>
                                </div>
                                <div id="multipleChoiceContainer<?php echo $testIV['option_id']; ?>" class="col-lg-12" style="display: none;">
                                    <?php
                                    if ($testIV['img_1'] != null && $testIV['img_2'] != null && $testIV['img_3'] != null && $testIV['img_4'] != null){ ?>
                                        <div class="row" style="justify-content: space-evenly;">
                                            <div clas="col-3" style="text-align: center;">
                                                <img class="img-option" style="width: 150px; height: auto; border-radius: 2%; margin-top: 10px;" src="../../assets/uploads/<?php echo $department; ?>/<?php echo $division; ?>/<?php echo $exam_id; ?>/<?php echo $testIV['question_type']; ?>/<?php echo $testIV['question_no']; ?>/option1/<?php echo $testIV['img_1']; ?>"/>
                                                <h4>I</h4>
                                            </div>
                                            <div clas="col-3" style="text-align: center;">
                                                <img class="img-option" style="width: 150px; height: auto; border-radius: 2%; margin-top: 10px;" src="../../assets/uploads/<?php echo $department; ?>/<?php echo $division; ?>/<?php echo $exam_id; ?>/<?php echo $testIV['question_type']; ?>/<?php echo $testIV['question_no']; ?>/option2/<?php echo $testIV['img_2']; ?>"/>
                                                <h4>II</h4>
                                            </div>
                                            <div clas="col-3" style="text-align: center;">
                                                <img class="img-option" style="width: 150px; height: auto; border-radius: 2%; margin-top: 10px;" src="../../assets/uploads/<?php echo $department; ?>/<?php echo $division; ?>/<?php echo $exam_id; ?>/<?php echo $testIV['question_type']; ?>/<?php echo $testIV['question_no']; ?>/option3/<?php echo $testIV['img_3']; ?>"/>
                                                <h4>III</h4>
                                            </div>
                                            <div clas="col-3" style="text-align: center;">
                                                <img class="img-option" style="width: 150px; height: auto; border-radius: 2%; margin-top: 10px;" src="../../assets/uploads/<?php echo $department; ?>/<?php echo $division; ?>/<?php echo $exam_id; ?>/<?php echo $testIV['question_type']; ?>/<?php echo $testIV['question_no']; ?>/option4/<?php echo $testIV['img_4']; ?>"/>
                                                <h4>IV</h4>
                                            </div>
                                        </div>
                                    <?php }
                                    else if ($testIV['img_1'] != null && $testIV['img_2'] != null && $testIV['img_3'] != null
                                        && $testIV['img_4'] == null){ ?>
                                        <div class="row" style="justify-content: space-evenly;">
                                            <div clas="col-4" style="text-align: center;">
                                                <img class="img-option" style="width: 150px; height: auto; border-radius: 2%; margin-top: 10px;" src="../../assets/uploads/<?php echo $department; ?>/<?php echo $division; ?>/<?php echo $exam_id; ?>/<?php echo $testIV['question_type']; ?>/<?php echo $testIV['question_no']; ?>/option1/<?php echo $testIV['img_1']; ?>"/>
                                                <h4>I</h4>
                                            </div>
                                            <div clas="col-4" style="text-align: center;">
                                                <img class="img-option" style="width: 150px; height: auto; border-radius: 2%; margin-top: 10px;" src="../../assets/uploads/<?php echo $department; ?>/<?php echo $division; ?>/<?php echo $exam_id; ?>/<?php echo $testIV['question_type']; ?>/<?php echo $testIV['question_no']; ?>/option2/<?php echo $testIV['img_2']; ?>"/>
                                                <h4>II</h4>
                                            </div>
                                            <div clas="col-4" style="text-align: center;">
                                                <img class="img-option" style="width: 150px; height: auto; border-radius: 2%; margin-top: 10px;" src="../../assets/uploads/<?php echo $department; ?>/<?php echo $division; ?>/<?php echo $exam_id; ?>/<?php echo $testIV['question_type']; ?>/<?php echo $testIV['question_no']; ?>/option3/<?php echo $testIV['img_3']; ?>"/>
                                                <h4>III</h4>
                                            </div>
                                        </div>
                                    <?php }
                                    else if ($testIV['img_1'] != null && $testIV['img_2'] != null && $testIV['img_3'] == null
                                        && $testIV['img_4'] != null){ ?>
                                        <div class="row" style="justify-content: space-evenly;">
                                            <div clas="col-4" style="text-align: center;">
                                                <img class="img-option" style="width: 150px; height: auto; border-radius: 2%; margin-top: 10px;" src="../../assets/uploads/<?php echo $department; ?>/<?php echo $division; ?>/<?php echo $exam_id; ?>/<?php echo $testIV['question_type']; ?>/<?php echo $testIV['question_no']; ?>/option1/<?php echo $testIV['img_1']; ?>"/>
                                                <h4>I</h4>
                                            </div>
                                            <div clas="col-4" style="text-align: center;">
                                                <img class="img-option" style="width: 150px; height: auto; border-radius: 2%; margin-top: 10px;" src="../../assets/uploads/<?php echo $department; ?>/<?php echo $division; ?>/<?php echo $exam_id; ?>/<?php echo $testIV['question_type']; ?>/<?php echo $testIV['question_no']; ?>/option2/<?php echo $testIV['img_2']; ?>"/>
                                                <h4>II</h4>
                                            </div>
                                            <div clas="col-3" style="text-align: center;">
                                                <img class="img-option" style="width: 150px; height: auto; border-radius: 2%; margin-top: 10px;" src="../../assets/uploads/<?php echo $department; ?>/<?php echo $division; ?>/<?php echo $exam_id; ?>/<?php echo $testIV['question_type']; ?>/<?php echo $testIV['question_no']; ?>/option4/<?php echo $testIV['img_4']; ?>"/>
                                                <h4>III</h4>
                                            </div>
                                        </div>
                                    <?php }
                                    else if ($testIV['img_1'] != null && $testIV['img_2'] == null && $testIV['img_3'] != null
                                        && $testIV['img_4'] != null){ ?>
                                        <div class="row" style="justify-content: space-evenly;">
                                            <div clas="col-4" style="text-align: center;">
                                                <img class="img-option" style="width: 150px; height: auto; border-radius: 2%; margin-top: 10px;" src="../../assets/uploads/<?php echo $department; ?>/<?php echo $division; ?>/<?php echo $exam_id; ?>/<?php echo $testIV['question_type']; ?>/<?php echo $testIV['question_no']; ?>/option1/<?php echo $testIV['img_1']; ?>"/>
                                                <h4>I</h4>
                                            </div>
                                            <div clas="col-4" style="text-align: center;">
                                                <img class="img-option" style="width: 150px; height: auto; border-radius: 2%; margin-top: 10px;" src="../../assets/uploads/<?php echo $department; ?>/<?php echo $division; ?>/<?php echo $exam_id; ?>/<?php echo $testIV['question_type']; ?>/<?php echo $testIV['question_no']; ?>/option3/<?php echo $testIV['img_3']; ?>"/>
                                                <h4>II</h4>
                                            </div>
                                            <div clas="col-3" style="text-align: center;">
                                                <img class="img-option" style="width: 150px; height: auto; border-radius: 2%; margin-top: 10px;" src="../../assets/uploads/<?php echo $department; ?>/<?php echo $division; ?>/<?php echo $exam_id; ?>/<?php echo $testIV['question_type']; ?>/<?php echo $testIV['question_no']; ?>/option4/<?php echo $testIV['img_4']; ?>"/>
                                                <h4>III</h4>
                                            </div>
                                        </div>
                                    <?php }
                                    else if ($testIV['img_1'] == null && $testIV['img_2'] != null && $testIV['img_3'] != null
                                        && $testIV['img_4'] != null){ ?>
                                        <div class="row" style="justify-content: space-evenly;">
                                            <div clas="col-4" style="text-align: center;">
                                                <img class="img-option" style="width: 150px; height: auto; border-radius: 2%; margin-top: 10px;" src="../../assets/uploads/<?php echo $department; ?>/<?php echo $division; ?>/<?php echo $exam_id; ?>/<?php echo $testIV['question_type']; ?>/<?php echo $testIV['question_no']; ?>/option2/<?php echo $testIV['img_2']; ?>"/>
                                                <h4>I</h4>
                                            </div>
                                            <div clas="col-4" style="text-align: center;">
                                                <img class="img-option" style="width: 150px; height: auto; border-radius: 2%; margin-top: 10px;" src="../../assets/uploads/<?php echo $department; ?>/<?php echo $division; ?>/<?php echo $exam_id; ?>/<?php echo $testIV['question_type']; ?>/<?php echo $testIV['question_no']; ?>/option3/<?php echo $testIV['img_3']; ?>"/>
                                                <h4>II</h4>
                                            </div>
                                            <div clas="col-3" style="text-align: center;">
                                                <img class="img-option" style="width: 150px; height: auto; border-radius: 2%; margin-top: 10px;" src="../../assets/uploads/<?php echo $department; ?>/<?php echo $division; ?>/<?php echo $exam_id; ?>/<?php echo $testIV['question_type']; ?>/<?php echo $testIV['question_no']; ?>/option4/<?php echo $testIV['img_4']; ?>"/>
                                                <h4>III</h4>
                                            </div>
                                        </div>
                                    <?php }

                                    // 1-2 OR 2-1
                                    else if ($testIV['img_1'] != null && $testIV['img_2'] != null && $testIV['img_3'] == null
                                        && $testIV['img_4'] == null){ ?>
                                        <div class="row" style="justify-content: space-evenly;">
                                            <div clas="col-6" style="text-align: center;">
                                                <img class="img-option" style="width: 150px; height: auto; border-radius: 2%; margin-top: 10px;" src="../../assets/uploads/<?php echo $department; ?>/<?php echo $division; ?>/<?php echo $exam_id; ?>/<?php echo $testIV['question_type']; ?>/<?php echo $testIV['question_no']; ?>/option1/<?php echo $testIV['img_1']; ?>"/>
                                                <h4>I</h4>
                                            </div>
                                            <div clas="col-6" style="text-align: center;">
                                                <img class="img-option" style="width: 150px; height: auto; border-radius: 2%; margin-top: 10px;" src="../../assets/uploads/<?php echo $department; ?>/<?php echo $division; ?>/<?php echo $exam_id; ?>/<?php echo $testIV['question_type']; ?>/<?php echo $testIV['question_no']; ?>/option2/<?php echo $testIV['img_2']; ?>"/>
                                                <h4>II</h4>
                                            </div>
                                        </div>
                                    <?php }
                                    // 1-3 OR 3-1
                                    else if ($testIV['img_1'] != null && $testIV['img_2'] == null && $testIV['img_3'] != null
                                        && $testIV['img_4'] == null){ ?>
                                        <div class="row" style="justify-content: space-evenly;">
                                            <div clas="col-6" style="text-align: center;">
                                                <img class="img-option" style="width: 150px; height: auto; border-radius: 2%; margin-top: 10px;" src="../../assets/uploads/<?php echo $department; ?>/<?php echo $division; ?>/<?php echo $exam_id; ?>/<?php echo $testIV['question_type']; ?>/<?php echo $testIV['question_no']; ?>/option1/<?php echo $testIV['img_1']; ?>"/>
                                                <h4>I</h4>
                                            </div>
                                            <div clas="col-6" style="text-align: center;">
                                                <img class="img-option" style="width: 150px; height: auto; border-radius: 2%; margin-top: 10px;" src="../../assets/uploads/<?php echo $department; ?>/<?php echo $division; ?>/<?php echo $exam_id; ?>/<?php echo $testIV['question_type']; ?>/<?php echo $testIV['question_no']; ?>/option3/<?php echo $testIV['img_3']; ?>"/>
                                                <h4>II</h4>
                                            </div>
                                        </div>
                                    <?php }
                                    // 1-4 OR 4-1
                                    else if ($testIV['img_1'] != null && $testIV['img_2'] == null && $testIV['img_3'] == null
                                        && $testIV['img_4'] != null){ ?>
                                        <div class="row" style="justify-content: space-evenly;">
                                            <div clas="col-6" style="text-align: center;">
                                                <img class="img-option" style="width: 150px; height: auto; border-radius: 2%; margin-top: 10px;" src="../../assets/uploads/<?php echo $department; ?>/<?php echo $division; ?>/<?php echo $exam_id; ?>/<?php echo $testIV['question_type']; ?>/<?php echo $testIV['question_no']; ?>/option1/<?php echo $testIV['img_1']; ?>"/>
                                                <h4>I</h4>
                                            </div>
                                            <div clas="col-6" style="text-align: center;">
                                                <img class="img-option" style="width: 150px; height: auto; border-radius: 2%; margin-top: 10px;" src="../../assets/uploads/<?php echo $department; ?>/<?php echo $division; ?>/<?php echo $exam_id; ?>/<?php echo $testIV['question_type']; ?>/<?php echo $testIV['question_no']; ?>/option4/<?php echo $testIV['img_4']; ?>"/>
                                                <h4>II</h4>
                                            </div>
                                        </div>
                                    <?php }
                                    // 2-3 OR 3-2
                                    else if ($testIV['img_1'] == null && $testIV['img_2'] != null && $testIV['img_3'] != null
                                        && $testIV['img_4'] == null){ ?>
                                        <div class="row" style="justify-content: space-evenly;">
                                            <div clas="col-6" style="text-align: center;">
                                                <img class="img-option" style="width: 150px; height: auto; border-radius: 2%; margin-top: 10px;" src="../../assets/uploads/<?php echo $department; ?>/<?php echo $division; ?>/<?php echo $exam_id; ?>/<?php echo $testIV['question_type']; ?>/<?php echo $testIV['question_no']; ?>/option2/<?php echo $testIV['img_2']; ?>"/>
                                                <h4>I</h4>
                                            </div>
                                            <div clas="col-6" style="text-align: center;">
                                                <img class="img-option" style="width: 150px; height: auto; border-radius: 2%; margin-top: 10px;" src="../../assets/uploads/<?php echo $department; ?>/<?php echo $division; ?>/<?php echo $exam_id; ?>/<?php echo $testIV['question_type']; ?>/<?php echo $testIV['question_no']; ?>/option3/<?php echo $testIV['img_3']; ?>"/>
                                                <h4>II</h4>
                                            </div>
                                        </div>
                                    <?php }
                                    // 2-4 OR 4-2
                                    else if ($testIV['img_1'] == null && $testIV['img_2'] != null && $testIV['img_3'] == null
                                        && $testIV['img_4'] != null){ ?>
                                        <div class="row" style="justify-content: space-evenly;">
                                            <div clas="col-6" style="text-align: center;">
                                                <img class="img-option" style="width: 150px; height: auto; border-radius: 2%; margin-top: 10px;" src="../../assets/uploads/<?php echo $department; ?>/<?php echo $division; ?>/<?php echo $exam_id; ?>/<?php echo $testIV['question_type']; ?>/<?php echo $testIV['question_no']; ?>/option2/<?php echo $testIV['img_2']; ?>"/>
                                                <h4>I</h4>
                                            </div>
                                            <div clas="col-6" style="text-align: center;">
                                                <img class="img-option" style="width: 150px; height: auto; border-radius: 2%; margin-top: 10px;" src="../../assets/uploads/<?php echo $department; ?>/<?php echo $division; ?>/<?php echo $exam_id; ?>/<?php echo $testIV['question_type']; ?>/<?php echo $testIV['question_no']; ?>/option4/<?php echo $testIV['img_4']; ?>"/>
                                                <h4>II</h4>
                                            </div>
                                        </div>
                                    <?php }
                                    // 3-4 OR 4-3
                                    else if ($testIV['img_1'] == null && $testIV['img_2'] == null && $testIV['img_3'] != null
                                        && $testIV['img_4'] != null){ ?>
                                        <div class="row" style="justify-content: space-evenly;">
                                            <div clas="col-6" style="text-align: center;">
                                                <img class="img-option" style="width: 150px; height: auto; border-radius: 2%; margin-top: 10px;" src="../../assets/uploads/<?php echo $department; ?>/<?php echo $division; ?>/<?php echo $exam_id; ?>/<?php echo $testIV['question_type']; ?>/<?php echo $testIV['question_no']; ?>/option3/<?php echo $testIV['img_3']; ?>"/>
                                                <h4>I/h4>
                                            </div>
                                            <div clas="col-6" style="text-align: center;">
                                                <img class="img-option" style="width: 150px; height: auto; border-radius: 2%; margin-top: 10px;" src="../../assets/uploads/<?php echo $department; ?>/<?php echo $division; ?>/<?php echo $exam_id; ?>/<?php echo $testIV['question_type']; ?>/<?php echo $testIV['question_no']; ?>/option4/<?php echo $testIV['img_4']; ?>"/>
                                                <h4>II</h4>
                                            </div>
                                        </div>
                                    <?php }

                                    else if ($testIV['img_1'] != null && $testIV['img_2'] == null && $testIV['img_3'] == null
                                        && $testIV['img_4'] == null){ ?>
                                        <div class="row" style="justify-content: space-evenly;">
                                            <div clas="col-12" style="text-align: center;">
                                                <img class="img-option" style="width: 150px; height: auto; border-radius: 2%; margin-top: 10px;" src="../../assets/uploads/<?php echo $department; ?>/<?php echo $division; ?>/<?php echo $exam_id; ?>/<?php echo $testIV['question_type']; ?>/<?php echo $testIV['question_no']; ?>/option1/<?php echo $testIV['img_1']; ?>"/>
                                                <h4>I</h4>
                                            </div>
                                        </div>
                                    <?php }
                                    else if ($testIV['img_1'] == null && $testIV['img_2'] != null && $testIV['img_3'] == null
                                        && $testIV['img_4'] == null){ ?>
                                        <div class="row" style="justify-content: space-evenly;">
                                            <div clas="col-12" style="text-align: center;">
                                                <img class="img-option" style="width: 150px; height: auto; border-radius: 2%; margin-top: 10px;" src="../../assets/uploads/<?php echo $department; ?>/<?php echo $division; ?>/<?php echo $exam_id; ?>/<?php echo $testIV['question_type']; ?>/<?php echo $testIV['question_no']; ?>/option2/<?php echo $testIV['img_2']; ?>"/>
                                                <h4>I</h4>
                                            </div>
                                        </div>
                                    <?php }
                                    else if ($testIV['img_1'] == null && $testIV['img_2'] == null && $testIV['img_3'] != null
                                        && $testIV['img_4'] == null){ ?>
                                        <div class="row" style="justify-content: space-evenly;">
                                            <div clas="col-12" style="text-align: center;">
                                                <img class="img-option" style="width: 150px; height: auto; border-radius: 2%; margin-top: 10px;" src="../../assets/uploads/<?php echo $department; ?>/<?php echo $division; ?>/<?php echo $exam_id; ?>/<?php echo $testIV['question_type']; ?>/<?php echo $testIV['question_no']; ?>/option3/<?php echo $testIV['img_3']; ?>"/>
                                                <h4>I</h4>
                                            </div>
                                        </div>
                                    <?php }
                                    else if ($testIV['img_1'] == null && $testIV['img_2'] == null && $testIV['img_3'] == null
                                        && $testIV['img_4'] != null){ ?>
                                        <div class="row" style="justify-content: space-evenly;">
                                            <div clas="col-12" style="text-align: center;">
                                                <img class="img-option" style="width: 150px; height: auto; border-radius: 2%; margin-top: 10px;" src="../../assets/uploads/<?php echo $department; ?>/<?php echo $division; ?>/<?php echo $exam_id; ?>/<?php echo $testIV['question_type']; ?>/<?php echo $testIV['question_no']; ?>/option4/<?php echo $testIV['img_4']; ?>"/>
                                                <h4>I</h4>
                                            </div>
                                        </div>
                                    <?php } ?>
                                    <span>Option 1: <?php echo $testIV['option_1']; ?></span><br>
                                    <span>Option 2: <?php echo $testIV['option_2']; ?></span><br>
                                    <span>Option 3: <?php echo $testIV['option_3']; ?></span><br>
                                    <span>Option 4: <?php echo $testIV['option_4']; ?></span><br>
                                    <span>Answer: <?php echo $testIV['ans']; ?></span>
                                </div>
                                <div id="trueOrFalseContainer<?php echo $testIV['option_id']; ?>" class="col-lg-12 pt-2"  style="display: none;" >
                                    <?php
                                    if ($testIV['img_1'] != null && $testIV['img_2'] != null && $testIV['img_3'] != null && $testIV['img_4'] != null){ ?>
                                        <div class="row" style="justify-content: space-evenly;">
                                            <div clas="col-3" style="text-align: center;">
                                                <img class="img-option" style="width: 150px; height: auto; border-radius: 2%; margin-top: 10px;" src="../../assets/uploads/<?php echo $department; ?>/<?php echo $division; ?>/<?php echo $exam_id; ?>/<?php echo $testIV['question_type']; ?>/<?php echo $testIV['question_no']; ?>/option1/<?php echo $testIV['img_1']; ?>" />
                                            </div>
                                            <div clas="col-3" style="text-align: center;">
                                                <img class="img-option" style="width: 150px; height: auto; border-radius: 2%; margin-top: 10px;" src="../../assets/uploads/<?php echo $department; ?>/<?php echo $division; ?>/<?php echo $exam_id; ?>/<?php echo $testIV['question_type']; ?>/<?php echo $testIV['question_no']; ?>/option2/<?php echo $testIV['img_2']; ?>" />
                                            </div>
                                            <div clas="col-3" style="text-align: center;">
                                                <img class="img-option" style="width: 150px; height: auto; border-radius: 2%; margin-top: 10px;" src="../../assets/uploads/<?php echo $department; ?>/<?php echo $division; ?>/<?php echo $exam_id; ?>/<?php echo $testIV['question_type']; ?>/<?php echo $testIV['question_no']; ?>/option3/<?php echo $testIV['img_3']; ?>" />
                                            </div>
                                            <div clas="col-3" style="text-align: center;">
                                                <img class="img-option" style="width: 150px; height: auto; border-radius: 2%; margin-top: 10px;" src="../../assets/uploads/<?php echo $department; ?>/<?php echo $division; ?>/<?php echo $exam_id; ?>/<?php echo $testIV['question_type']; ?>/<?php echo $testIV['question_no']; ?>/option4/<?php echo $testIV['img_4']; ?>" />
                                            </div>
                                        </div>
                                    <?php }
                                    else if ($testIV['img_1'] != null && $testIV['img_2'] != null && $testIV['img_3'] != null
                                        && $testIV['img_4'] == null){ ?>
                                        <div class="row" style="justify-content: space-evenly;">
                                            <div clas="col-4" style="text-align: center;">
                                                <img class="img-option" style="width: 150px; height: auto; border-radius: 2%; margin-top: 10px;" src="../../assets/uploads/<?php echo $department; ?>/<?php echo $division; ?>/<?php echo $exam_id; ?>/<?php echo $testIV['question_type']; ?>/<?php echo $testIV['question_no']; ?>/option1/<?php echo $testIV['img_1']; ?>" />
                                            </div>
                                            <div clas="col-4" style="text-align: center;">
                                                <img class="img-option" style="width: 150px; height: auto; border-radius: 2%; margin-top: 10px;" src="../../assets/uploads/<?php echo $department; ?>/<?php echo $division; ?>/<?php echo $exam_id; ?>/<?php echo $testIV['question_type']; ?>/<?php echo $testIV['question_no']; ?>/option2/<?php echo $testIV['img_2']; ?>" />
                                            </div>
                                            <div clas="col-4" style="text-align: center;">
                                                <img class="img-option" style="width: 150px; height: auto; border-radius: 2%; margin-top: 10px;" src="../../assets/uploads/<?php echo $department; ?>/<?php echo $division; ?>/<?php echo $exam_id; ?>/<?php echo $testIV['question_type']; ?>/<?php echo $testIV['question_no']; ?>/option3/<?php echo $testIV['img_3']; ?>" />
                                            </div>
                                        </div>
                                    <?php }
                                    else if ($testIV['img_1'] != null && $testIV['img_2'] != null && $testIV['img_3'] == null
                                        && $testIV['img_4'] != null){ ?>
                                        <div class="row" style="justify-content: space-evenly;">
                                            <div clas="col-4" style="text-align: center;">
                                                <img class="img-option" style="width: 150px; height: auto; border-radius: 2%; margin-top: 10px;" src="../../assets/uploads/<?php echo $department; ?>/<?php echo $division; ?>/<?php echo $exam_id; ?>/<?php echo $testIV['question_type']; ?>/<?php echo $testIV['question_no']; ?>/option1/<?php echo $testIV['img_1']; ?>" />
                                            </div>
                                            <div clas="col-4" style="text-align: center;">
                                                <img class="img-option" style="width: 150px; height: auto; border-radius: 2%; margin-top: 10px;" src="../../assets/uploads/<?php echo $department; ?>/<?php echo $division; ?>/<?php echo $exam_id; ?>/<?php echo $testIV['question_type']; ?>/<?php echo $testIV['question_no']; ?>/option2/<?php echo $testIV['img_2']; ?>" />
                                            </div>
                                            <div clas="col-3" style="text-align: center;">
                                                <img class="img-option" style="width: 150px; height: auto; border-radius: 2%; margin-top: 10px;" src="../../assets/uploads/<?php echo $department; ?>/<?php echo $division; ?>/<?php echo $exam_id; ?>/<?php echo $testIV['question_type']; ?>/<?php echo $testIV['question_no']; ?>/option4/<?php echo $testIV['img_4']; ?>" />
                                            </div>
                                        </div>
                                    <?php }
                                    else if ($testIV['img_1'] != null && $testIV['img_2'] == null && $testIV['img_3'] != null
                                        && $testIV['img_4'] != null){ ?>
                                        <div class="row" style="justify-content: space-evenly;">
                                            <div clas="col-4" style="text-align: center;">
                                                <img class="img-option" style="width: 150px; height: auto; border-radius: 2%; margin-top: 10px;" src="../../assets/uploads/<?php echo $department; ?>/<?php echo $division; ?>/<?php echo $exam_id; ?>/<?php echo $testIV['question_type']; ?>/<?php echo $testIV['question_no']; ?>/option1/<?php echo $testIV['img_1']; ?>" />
                                            </div>
                                            <div clas="col-4" style="text-align: center;">
                                                <img class="img-option" style="width: 150px; height: auto; border-radius: 2%; margin-top: 10px;" src="../../assets/uploads/<?php echo $department; ?>/<?php echo $division; ?>/<?php echo $exam_id; ?>/<?php echo $testIV['question_type']; ?>/<?php echo $testIV['question_no']; ?>/option3/<?php echo $testIV['img_3']; ?>" />
                                            </div>
                                            <div clas="col-3" style="text-align: center;">
                                                <img class="img-option" style="width: 150px; height: auto; border-radius: 2%; margin-top: 10px;" src="../../assets/uploads/<?php echo $department; ?>/<?php echo $division; ?>/<?php echo $exam_id; ?>/<?php echo $testIV['question_type']; ?>/<?php echo $testIV['question_no']; ?>/option4/<?php echo $testIV['img_4']; ?>" />
                                            </div>
                                        </div>
                                    <?php }
                                    else if ($testIV['img_1'] == null && $testIV['img_2'] != null && $testIV['img_3'] != null
                                        && $testIV['img_4'] != null){ ?>
                                        <div class="row" style="justify-content: space-evenly;">
                                            <div clas="col-4" style="text-align: center;">
                                                <img class="img-option" style="width: 150px; height: auto; border-radius: 2%; margin-top: 10px;" src="../../assets/uploads/<?php echo $department; ?>/<?php echo $division; ?>/<?php echo $exam_id; ?>/<?php echo $testIV['question_type']; ?>/<?php echo $testIV['question_no']; ?>/option2/<?php echo $testIV['img_2']; ?>" />
                                            </div>
                                            <div clas="col-4" style="text-align: center;">
                                                <img class="img-option" style="width: 150px; height: auto; border-radius: 2%; margin-top: 10px;" src="../../assets/uploads/<?php echo $department; ?>/<?php echo $division; ?>/<?php echo $exam_id; ?>/<?php echo $testIV['question_type']; ?>/<?php echo $testIV['question_no']; ?>/option3/<?php echo $testIV['img_3']; ?>" />
                                            </div>
                                            <div clas="col-3" style="text-align: center;">
                                                <img class="img-option" style="width: 150px; height: auto; border-radius: 2%; margin-top: 10px;" src="../../assets/uploads/<?php echo $department; ?>/<?php echo $division; ?>/<?php echo $exam_id; ?>/<?php echo $testIV['question_type']; ?>/<?php echo $testIV['question_no']; ?>/option4/<?php echo $testIV['img_4']; ?>" />
                                            </div>
                                        </div>
                                    <?php }

                                    // 1-2 OR 2-1
                                    else if ($testIV['img_1'] != null && $testIV['img_2'] != null && $testIV['img_3'] == null
                                        && $testIV['img_4'] == null){ ?>
                                        <div class="row" style="justify-content: space-evenly;">
                                            <div clas="col-6" style="text-align: center;">
                                                <img class="img-option" style="width: 150px; height: auto; border-radius: 2%; margin-top: 10px;" src="../../assets/uploads/<?php echo $department; ?>/<?php echo $division; ?>/<?php echo $exam_id; ?>/<?php echo $testIV['question_type']; ?>/<?php echo $testIV['question_no']; ?>/option1/<?php echo $testIV['img_1']; ?>" />
                                            </div>
                                            <div clas="col-6" style="text-align: center;">
                                                <img class="img-option" style="width: 150px; height: auto; border-radius: 2%; margin-top: 10px;" src="../../assets/uploads/<?php echo $department; ?>/<?php echo $division; ?>/<?php echo $exam_id; ?>/<?php echo $testIV['question_type']; ?>/<?php echo $testIV['question_no']; ?>/option2/<?php echo $testIV['img_2']; ?>" />
                                            </div>
                                        </div>
                                    <?php }
                                    // 1-3 OR 3-1
                                    else if ($testIV['img_1'] != null && $testIV['img_2'] == null && $testIV['img_3'] != null
                                        && $testIV['img_4'] == null){ ?>
                                        <div class="row" style="justify-content: space-evenly;">
                                            <div clas="col-6" style="text-align: center;">
                                                <img class="img-option" style="width: 150px; height: auto; border-radius: 2%; margin-top: 10px;" src="../../assets/uploads/<?php echo $department; ?>/<?php echo $division; ?>/<?php echo $exam_id; ?>/<?php echo $testIV['question_type']; ?>/<?php echo $testIV['question_no']; ?>/option1/<?php echo $testIV['img_1']; ?>" />
                                            </div>
                                            <div clas="col-6" style="text-align: center;">
                                                <img class="img-option" style="width: 150px; height: auto; border-radius: 2%; margin-top: 10px;" src="../../assets/uploads/<?php echo $department; ?>/<?php echo $division; ?>/<?php echo $exam_id; ?>/<?php echo $testIV['question_type']; ?>/<?php echo $testIV['question_no']; ?>/option3/<?php echo $testIV['img_3']; ?>" />
                                            </div>
                                        </div>
                                    <?php }
                                    // 1-4 OR 4-1
                                    else if ($testIV['img_1'] != null && $testIV['img_2'] == null && $testIV['img_3'] == null
                                        && $testIV['img_4'] != null){ ?>
                                        <div class="row" style="justify-content: space-evenly;">
                                            <div clas="col-6" style="text-align: center;">
                                                <img class="img-option" style="width: 150px; height: auto; border-radius: 2%; margin-top: 10px;" src="../../assets/uploads/<?php echo $department; ?>/<?php echo $division; ?>/<?php echo $exam_id; ?>/<?php echo $testIV['question_type']; ?>/<?php echo $testIV['question_no']; ?>/option1/<?php echo $testIV['img_1']; ?>" />
                                            </div>
                                            <div clas="col-6" style="text-align: center;">
                                                <img class="img-option" style="width: 150px; height: auto; border-radius: 2%; margin-top: 10px;" src="../../assets/uploads/<?php echo $department; ?>/<?php echo $division; ?>/<?php echo $exam_id; ?>/<?php echo $testIV['question_type']; ?>/<?php echo $testIV['question_no']; ?>/option4/<?php echo $testIV['img_4']; ?>" />
                                            </div>
                                        </div>
                                    <?php }
                                    // 2-3 OR 3-2
                                    else if ($testIV['img_1'] == null && $testIV['img_2'] != null && $testIV['img_3'] != null
                                        && $testIV['img_4'] == null){ ?>
                                        <div class="row" style="justify-content: space-evenly;">
                                            <div clas="col-6" style="text-align: center;">
                                                <img class="img-option" style="width: 150px; height: auto; border-radius: 2%; margin-top: 10px;" src="../../assets/uploads/<?php echo $department; ?>/<?php echo $division; ?>/<?php echo $exam_id; ?>/<?php echo $testIV['question_type']; ?>/<?php echo $testIV['question_no']; ?>/option2/<?php echo $testIV['img_2']; ?>" />
                                            </div>
                                            <div clas="col-6" style="text-align: center;">
                                                <img class="img-option" style="width: 150px; height: auto; border-radius: 2%; margin-top: 10px;" src="../../assets/uploads/<?php echo $department; ?>/<?php echo $division; ?>/<?php echo $exam_id; ?>/<?php echo $testIV['question_type']; ?>/<?php echo $testIV['question_no']; ?>/option3/<?php echo $testIV['img_3']; ?>" />
                                            </div>
                                        </div>
                                    <?php }
                                    // 2-4 OR 4-2
                                    else if ($testIV['img_1'] == null && $testIV['img_2'] != null && $testIV['img_3'] == null
                                        && $testIV['img_4'] != null){ ?>
                                        <div class="row" style="justify-content: space-evenly;">
                                            <div clas="col-6" style="text-align: center;">
                                                <img class="img-option" style="width: 150px; height: auto; border-radius: 2%; margin-top: 10px;" src="../../assets/uploads/<?php echo $department; ?>/<?php echo $division; ?>/<?php echo $exam_id; ?>/<?php echo $testIV['question_type']; ?>/<?php echo $testIV['question_no']; ?>/option2/<?php echo $testIV['img_2']; ?>" />
                                            </div>
                                            <div clas="col-6" style="text-align: center;">
                                                <img class="img-option" style="width: 150px; height: auto; border-radius: 2%; margin-top: 10px;" src="../../assets/uploads/<?php echo $department; ?>/<?php echo $division; ?>/<?php echo $exam_id; ?>/<?php echo $testIV['question_type']; ?>/<?php echo $testIV['question_no']; ?>/option4/<?php echo $testIV['img_4']; ?>" />
                                            </div>
                                        </div>
                                    <?php }
                                    // 3-4 OR 4-3
                                    else if ($testIV['img_1'] == null && $testIV['img_2'] == null && $testIV['img_3'] != null
                                        && $testIV['img_4'] != null){ ?>
                                        <div class="row" style="justify-content: space-evenly;">
                                            <div clas="col-6" style="text-align: center;">
                                                <img class="img-option" style="width: 150px; height: auto; border-radius: 2%; margin-top: 10px;" src="../../assets/uploads/<?php echo $department; ?>/<?php echo $division; ?>/<?php echo $exam_id; ?>/<?php echo $testIV['question_type']; ?>/<?php echo $testIV['question_no']; ?>/option3/<?php echo $testIV['img_3']; ?>" />
                                            </div>
                                            <div clas="col-6" style="text-align: center;">
                                                <img class="img-option" style="width: 150px; height: auto; border-radius: 2%; margin-top: 10px;" src="../../assets/uploads/<?php echo $department; ?>/<?php echo $division; ?>/<?php echo $exam_id; ?>/<?php echo $testIV['question_type']; ?>/<?php echo $testIV['question_no']; ?>/option4/<?php echo $testIV['img_4']; ?>" />
                                            </div>
                                        </div>
                                    <?php }

                                    else if ($testIV['img_1'] != null && $testIV['img_2'] == null && $testIV['img_3'] == null
                                        && $testIV['img_4'] == null){ ?>
                                        <div class="row" style="justify-content: space-evenly;">
                                            <div clas="col-12" style="text-align: center;">
                                                <img class="img-option" style="width: 150px; height: auto; border-radius: 2%; margin-top: 10px;" src="../../assets/uploads/<?php echo $department; ?>/<?php echo $division; ?>/<?php echo $exam_id; ?>/<?php echo $testIV['question_type']; ?>/<?php echo $testIV['question_no']; ?>/option1/<?php echo $testIV['img_1']; ?>" />
                                            </div>
                                        </div>
                                    <?php }
                                    else if ($testIV['img_1'] == null && $testIV['img_2'] != null && $testIV['img_3'] == null
                                        && $testIV['img_4'] == null){ ?>
                                        <div class="row" style="justify-content: space-evenly;">
                                            <div clas="col-12" style="text-align: center;">
                                                <img class="img-option" style="width: 150px; height: auto; border-radius: 2%; margin-top: 10px;" src="../../assets/uploads/<?php echo $department; ?>/<?php echo $division; ?>/<?php echo $exam_id; ?>/<?php echo $testIV['question_type']; ?>/<?php echo $testIV['question_no']; ?>/option2/<?php echo $testIV['img_2']; ?>" />
                                            </div>
                                        </div>
                                    <?php }
                                    else if ($testIV['img_1'] == null && $testIV['img_2'] == null && $testIV['img_3'] != null
                                        && $testIV['img_4'] == null){ ?>
                                        <div class="row" style="justify-content: space-evenly;">
                                            <div clas="col-12" style="text-align: center;">
                                                <img class="img-option" style="width: 150px; height: auto; border-radius: 2%; margin-top: 10px;" src="../../assets/uploads/<?php echo $department; ?>/<?php echo $division; ?>/<?php echo $exam_id; ?>/<?php echo $testIV['question_type']; ?>/<?php echo $testIV['question_no']; ?>/option3/<?php echo $testIV['img_3']; ?>" />
                                            </div>
                                        </div>
                                    <?php }
                                    else if ($testIV['img_1'] == null && $testIV['img_2'] == null && $testIV['img_3'] == null
                                        && $testIV['img_4'] != null){ ?>
                                        <div class="row" style="justify-content: space-evenly;">
                                            <div clas="col-12" style="text-align: center;">
                                                <img class="img-option" style="width: 150px; height: auto; border-radius: 2%; margin-top: 10px;" src="../../assets/uploads/<?php echo $department; ?>/<?php echo $division; ?>/<?php echo $exam_id; ?>/<?php echo $testIV['question_type']; ?>/<?php echo $testIV['question_no']; ?>/option4/<?php echo $testIV['img_4']; ?>" />
                                            </div>
                                        </div>
                                    <?php } ?>
                                    <br>
                                    <span>Answer: <?php echo $testIV['ans']; ?></span>
                                </div>
                                <div id="multipleImageContainer<?php echo $testIV['option_id']; ?>" class="col-lg-12 pt-2" style="display: none;">
                                    <?php
                                    if ($testIV['img_1'] != null && $testIV['img_2'] != null && $testIV['img_3'] != null && $testIV['img_4'] != null){ ?>
                                        <div class="row" style="justify-content: space-evenly;">
                                            <div clas="col-3" style="text-align: center;">
                                                <img class="img-option" style="width: 150px; height: auto; border-radius: 2%; margin-top: 10px;" src="../../assets/uploads/<?php echo $department; ?>/<?php echo $division; ?>/<?php echo $exam_id; ?>/<?php echo $testIV['question_type']; ?>/<?php echo $testIV['question_no']; ?>/option1/<?php echo $testIV['img_1']; ?>" />
                                                <h4><?php echo $testIV['option_1']; ?></h4>
                                            </div>
                                            <div clas="col-3" style="text-align: center;">
                                                <img class="img-option" style="width: 150px; height: auto; border-radius: 2%; margin-top: 10px;" src="../../assets/uploads/<?php echo $department; ?>/<?php echo $division; ?>/<?php echo $exam_id; ?>/<?php echo $testIV['question_type']; ?>/<?php echo $testIV['question_no']; ?>/option2/<?php echo $testIV['img_2']; ?>" />
                                                <h4><?php echo $testIV['option_2']; ?></h4>
                                            </div>
                                            <div clas="col-3" style="text-align: center;">
                                                <img class="img-option" style="width: 150px; height: auto; border-radius: 2%; margin-top: 10px;" src="../../assets/uploads/<?php echo $department; ?>/<?php echo $division; ?>/<?php echo $exam_id; ?>/<?php echo $testIV['question_type']; ?>/<?php echo $testIV['question_no']; ?>/option3/<?php echo $testIV['img_3']; ?>" />
                                                <h4><?php echo $testIV['option_3']; ?></h4>
                                            </div>
                                            <div clas="col-3" style="text-align: center;">
                                                <img class="img-option" style="width: 150px; height: auto; border-radius: 2%; margin-top: 10px;" src="../../assets/uploads/<?php echo $department; ?>/<?php echo $division; ?>/<?php echo $exam_id; ?>/<?php echo $testIV['question_type']; ?>/<?php echo $testIV['question_no']; ?>/option4/<?php echo $testIV['img_4']; ?>" />
                                                <h4><?php echo $testIV['option_4']; ?></h4>
                                            </div>
                                        </div>
                                    <?php } ?>
                                    <br>
                                    <span>Answer: <?php echo $testIV['ans']; ?></span>
                                </div>
                            </div>
                        </li>
                    <?php } ?>
                </ul>
            </div>
            <div class="tab-pane fade" id="test5" role="tabpanel" aria-labelledby="question-testV">
                <ul class="list-group">
                    <?php
                    $index = 1;
                    $exam5 = $conn->query("SELECT b.`id` AS question_no, b.`points`, b.`question_type`, b.`question`,
                            c.`id` AS option_id, c.`o_id`, c.`option_1`, c.`option_2`, c.`option_3`, c.`option_4`, 
                            c.`img_1`, c.`img_2`, c.`img_3`, c.`img_4`, c.`ans` 
                            FROM question b
                            LEFT JOIN options c ON b.`id` = c.`id` 
                            WHERE b.`question_type` = '$test5' AND b.`q_id` = '$exam_id' ");
                    while ($testV = $exam5->fetch()){ ?>
                        <script>
                            $(document).ready(function (){
                                var testV = "<?php echo $test5; ?>";
                                if (testV == "Short Answer"){
                                    $("#shortAnswerContainer<?php echo $testV['option_id']; ?>").show();
                                    $("#multipleChoiceContainer<?php echo $testV['option_id']; ?>").hide();
                                    $("#trueOrFalseContainer<?php echo $testV['option_id']; ?>").hide();
                                    $("#multipleImageContainer<?php echo $testV['option_id']; ?>").hide();
                                }else if (testV == "True/False"){
                                    $("#shortAnswerContainer<?php echo $testV['option_id']; ?>").hide();
                                    $("#multipleChoiceContainer<?php echo $testV['option_id']; ?>").hide();
                                    $("#trueOrFalseContainer<?php echo $testV['option_id']; ?>").show();
                                    $("#multipleImageContainer<?php echo $testV['option_id']; ?>").hide();
                                }else if (testV == "Multiple Choice"){
                                    $("#shortAnswerContainer<?php echo $testV['option_id']; ?>").hide();
                                    $("#multipleChoiceContainer<?php echo $testV['option_id']; ?>").show();
                                    $("#trueOrFalseContainer<?php echo $testV['option_id']; ?>").hide();
                                    $("#multipleImageContainer<?php echo $testV['option_id']; ?>").hide();
                                }else if (testV == "Multiple Image"){
                                    $("#shortAnswerContainer<?php echo $testV['option_id']; ?>").hide();
                                    $("#multipleChoiceContainer<?php echo $testV['option_id']; ?>").hide();
                                    $("#trueOrFalseContainer<?php echo $testV['option_id']; ?>").hide();
                                    $("#multipleImageContainer<?php echo $testV['option_id']; ?>").show();
                                }
                            });
                        </script>
                        <li class="list-group-item">
                            <div class="row pl-3 pr-3">
                                <span><?php echo $index++.".&nbsp;".$testV['question']." [".$testV['points']." point/s]"; ?></span>
                                <div id="shortAnswerContainer<?php echo $testV['option_id']; ?>" class="col-lg-12" style="display: none;">
                                    <span>Answer: <?php echo $testV['ans']; ?></span>
                                </div>
                                <div id="multipleChoiceContainer<?php echo $testV['option_id']; ?>" class="col-lg-12" style="display: none;">
                                    <?php
                                    if ($testV['img_1'] != null && $testV['img_2'] != null && $testV['img_3'] != null && $testV['img_4'] != null){ ?>
                                        <div class="row" style="justify-content: space-evenly;">
                                            <div clas="col-3" style="text-align: center;">
                                                <img class="img-option" style="width: 150px; height: auto; border-radius: 2%; margin-top: 10px;" src="../../assets/uploads/<?php echo $department; ?>/<?php echo $division; ?>/<?php echo $exam_id; ?>/<?php echo $testV['question_type']; ?>/<?php echo $testV['question_no']; ?>/option1/<?php echo $testV['img_1']; ?>"/>
                                                <h4>I</h4>
                                            </div>
                                            <div clas="col-3" style="text-align: center;">
                                                <img class="img-option" style="width: 150px; height: auto; border-radius: 2%; margin-top: 10px;" src="../../assets/uploads/<?php echo $department; ?>/<?php echo $division; ?>/<?php echo $exam_id; ?>/<?php echo $testV['question_type']; ?>/<?php echo $testV['question_no']; ?>/option2/<?php echo $testV['img_2']; ?>"/>
                                                <h4>II</h4>
                                            </div>
                                            <div clas="col-3" style="text-align: center;">
                                                <img class="img-option" style="width: 150px; height: auto; border-radius: 2%; margin-top: 10px;" src="../../assets/uploads/<?php echo $department; ?>/<?php echo $division; ?>/<?php echo $exam_id; ?>/<?php echo $testV['question_type']; ?>/<?php echo $testV['question_no']; ?>/option3/<?php echo $testV['img_3']; ?>"/>
                                                <h4>III</h4>
                                            </div>
                                            <div clas="col-3" style="text-align: center;">
                                                <img class="img-option" style="width: 150px; height: auto; border-radius: 2%; margin-top: 10px;" src="../../assets/uploads/<?php echo $department; ?>/<?php echo $division; ?>/<?php echo $exam_id; ?>/<?php echo $testV['question_type']; ?>/<?php echo $testV['question_no']; ?>/option4/<?php echo $testV['img_4']; ?>"/>
                                                <h4>IV</h4>
                                            </div>
                                        </div>
                                    <?php }
                                    else if ($testV['img_1'] != null && $testV['img_2'] != null && $testV['img_3'] != null
                                        && $testV['img_4'] == null){ ?>
                                        <div class="row" style="justify-content: space-evenly;">
                                            <div clas="col-4" style="text-align: center;">
                                                <img class="img-option" style="width: 150px; height: auto; border-radius: 2%; margin-top: 10px;" src="../../assets/uploads/<?php echo $department; ?>/<?php echo $division; ?>/<?php echo $exam_id; ?>/<?php echo $testV['question_type']; ?>/<?php echo $testV['question_no']; ?>/option1/<?php echo $testV['img_1']; ?>"/>
                                                <h4>I</h4>
                                            </div>
                                            <div clas="col-4" style="text-align: center;">
                                                <img class="img-option" style="width: 150px; height: auto; border-radius: 2%; margin-top: 10px;" src="../../assets/uploads/<?php echo $department; ?>/<?php echo $division; ?>/<?php echo $exam_id; ?>/<?php echo $testV['question_type']; ?>/<?php echo $testV['question_no']; ?>/option2/<?php echo $testV['img_2']; ?>"/>
                                                <h4>II</h4>
                                            </div>
                                            <div clas="col-4" style="text-align: center;">
                                                <img class="img-option" style="width: 150px; height: auto; border-radius: 2%; margin-top: 10px;" src="../../assets/uploads/<?php echo $department; ?>/<?php echo $division; ?>/<?php echo $exam_id; ?>/<?php echo $testV['question_type']; ?>/<?php echo $testV['question_no']; ?>/option3/<?php echo $testV['img_3']; ?>"/>
                                                <h4>III</h4>
                                            </div>
                                        </div>
                                    <?php }
                                    else if ($testV['img_1'] != null && $testV['img_2'] != null && $testV['img_3'] == null
                                        && $testV['img_4'] != null){ ?>
                                        <div class="row" style="justify-content: space-evenly;">
                                            <div clas="col-4" style="text-align: center;">
                                                <img class="img-option" style="width: 150px; height: auto; border-radius: 2%; margin-top: 10px;" src="../../assets/uploads/<?php echo $department; ?>/<?php echo $division; ?>/<?php echo $exam_id; ?>/<?php echo $testV['question_type']; ?>/<?php echo $testV['question_no']; ?>/option1/<?php echo $testV['img_1']; ?>"/>
                                                <h4>I</h4>
                                            </div>
                                            <div clas="col-4" style="text-align: center;">
                                                <img class="img-option" style="width: 150px; height: auto; border-radius: 2%; margin-top: 10px;" src="../../assets/uploads/<?php echo $department; ?>/<?php echo $division; ?>/<?php echo $exam_id; ?>/<?php echo $testV['question_type']; ?>/<?php echo $testV['question_no']; ?>/option2/<?php echo $testV['img_2']; ?>"/>
                                                <h4>II</h4>
                                            </div>
                                            <div clas="col-3" style="text-align: center;">
                                                <img class="img-option" style="width: 150px; height: auto; border-radius: 2%; margin-top: 10px;" src="../../assets/uploads/<?php echo $department; ?>/<?php echo $division; ?>/<?php echo $exam_id; ?>/<?php echo $testV['question_type']; ?>/<?php echo $testV['question_no']; ?>/option4/<?php echo $testV['img_4']; ?>"/>
                                                <h4>III</h4>
                                            </div>
                                        </div>
                                    <?php }
                                    else if ($testV['img_1'] != null && $testV['img_2'] == null && $testV['img_3'] != null
                                        && $testV['img_4'] != null){ ?>
                                        <div class="row" style="justify-content: space-evenly;">
                                            <div clas="col-4" style="text-align: center;">
                                                <img class="img-option" style="width: 150px; height: auto; border-radius: 2%; margin-top: 10px;" src="../../assets/uploads/<?php echo $department; ?>/<?php echo $division; ?>/<?php echo $exam_id; ?>/<?php echo $testV['question_type']; ?>/<?php echo $testV['question_no']; ?>/option1/<?php echo $testV['img_1']; ?>"/>
                                                <h4>I</h4>
                                            </div>
                                            <div clas="col-4" style="text-align: center;">
                                                <img class="img-option" style="width: 150px; height: auto; border-radius: 2%; margin-top: 10px;" src="../../assets/uploads/<?php echo $department; ?>/<?php echo $division; ?>/<?php echo $exam_id; ?>/<?php echo $testV['question_type']; ?>/<?php echo $testV['question_no']; ?>/option3/<?php echo $testV['img_3']; ?>"/>
                                                <h4>II</h4>
                                            </div>
                                            <div clas="col-3" style="text-align: center;">
                                                <img class="img-option" style="width: 150px; height: auto; border-radius: 2%; margin-top: 10px;" src="../../assets/uploads/<?php echo $department; ?>/<?php echo $division; ?>/<?php echo $exam_id; ?>/<?php echo $testV['question_type']; ?>/<?php echo $testV['question_no']; ?>/option4/<?php echo $testV['img_4']; ?>"/>
                                                <h4>III</h4>
                                            </div>
                                        </div>
                                    <?php }
                                    else if ($testV['img_1'] == null && $testV['img_2'] != null && $testV['img_3'] != null
                                        && $testV['img_4'] != null){ ?>
                                        <div class="row" style="justify-content: space-evenly;">
                                            <div clas="col-4" style="text-align: center;">
                                                <img class="img-option" style="width: 150px; height: auto; border-radius: 2%; margin-top: 10px;" src="../../assets/uploads/<?php echo $department; ?>/<?php echo $division; ?>/<?php echo $exam_id; ?>/<?php echo $testV['question_type']; ?>/<?php echo $testV['question_no']; ?>/option2/<?php echo $testV['img_2']; ?>"/>
                                                <h4>I</h4>
                                            </div>
                                            <div clas="col-4" style="text-align: center;">
                                                <img class="img-option" style="width: 150px; height: auto; border-radius: 2%; margin-top: 10px;" src="../../assets/uploads/<?php echo $department; ?>/<?php echo $division; ?>/<?php echo $exam_id; ?>/<?php echo $testV['question_type']; ?>/<?php echo $testV['question_no']; ?>/option3/<?php echo $testV['img_3']; ?>"/>
                                                <h4>II</h4>
                                            </div>
                                            <div clas="col-3" style="text-align: center;">
                                                <img class="img-option" style="width: 150px; height: auto; border-radius: 2%; margin-top: 10px;" src="../../assets/uploads/<?php echo $department; ?>/<?php echo $division; ?>/<?php echo $exam_id; ?>/<?php echo $testV['question_type']; ?>/<?php echo $testV['question_no']; ?>/option4/<?php echo $testV['img_4']; ?>"/>
                                                <h4>III</h4>
                                            </div>
                                        </div>
                                    <?php }

                                    // 1-2 OR 2-1
                                    else if ($testV['img_1'] != null && $testV['img_2'] != null && $testV['img_3'] == null
                                        && $testV['img_4'] == null){ ?>
                                        <div class="row" style="justify-content: space-evenly;">
                                            <div clas="col-6" style="text-align: center;">
                                                <img class="img-option" style="width: 150px; height: auto; border-radius: 2%; margin-top: 10px;" src="../../assets/uploads/<?php echo $department; ?>/<?php echo $division; ?>/<?php echo $exam_id; ?>/<?php echo $testV['question_type']; ?>/<?php echo $testV['question_no']; ?>/option1/<?php echo $testV['img_1']; ?>"/>
                                                <h4>I</h4>
                                            </div>
                                            <div clas="col-6" style="text-align: center;">
                                                <img class="img-option" style="width: 150px; height: auto; border-radius: 2%; margin-top: 10px;" src="../../assets/uploads/<?php echo $department; ?>/<?php echo $division; ?>/<?php echo $exam_id; ?>/<?php echo $testV['question_type']; ?>/<?php echo $testV['question_no']; ?>/option2/<?php echo $testV['img_2']; ?>"/>
                                                <h4>II</h4>
                                            </div>
                                        </div>
                                    <?php }
                                    // 1-3 OR 3-1
                                    else if ($testV['img_1'] != null && $testV['img_2'] == null && $testV['img_3'] != null
                                        && $testV['img_4'] == null){ ?>
                                        <div class="row" style="justify-content: space-evenly;">
                                            <div clas="col-6" style="text-align: center;">
                                                <img class="img-option" style="width: 150px; height: auto; border-radius: 2%; margin-top: 10px;" src="../../assets/uploads/<?php echo $department; ?>/<?php echo $division; ?>/<?php echo $exam_id; ?>/<?php echo $testV['question_type']; ?>/<?php echo $testV['question_no']; ?>/option1/<?php echo $testV['img_1']; ?>"/>
                                                <h4>I</h4>
                                            </div>
                                            <div clas="col-6" style="text-align: center;">
                                                <img class="img-option" style="width: 150px; height: auto; border-radius: 2%; margin-top: 10px;" src="../../assets/uploads/<?php echo $department; ?>/<?php echo $division; ?>/<?php echo $exam_id; ?>/<?php echo $testV['question_type']; ?>/<?php echo $testV['question_no']; ?>/option3/<?php echo $testV['img_3']; ?>"/>
                                                <h4>II</h4>
                                            </div>
                                        </div>
                                    <?php }
                                    // 1-4 OR 4-1
                                    else if ($testV['img_1'] != null && $testV['img_2'] == null && $testV['img_3'] == null
                                        && $testV['img_4'] != null){ ?>
                                        <div class="row" style="justify-content: space-evenly;">
                                            <div clas="col-6" style="text-align: center;">
                                                <img class="img-option" style="width: 150px; height: auto; border-radius: 2%; margin-top: 10px;" src="../../assets/uploads/<?php echo $department; ?>/<?php echo $division; ?>/<?php echo $exam_id; ?>/<?php echo $testV['question_type']; ?>/<?php echo $testV['question_no']; ?>/option1/<?php echo $testV['img_1']; ?>"/>
                                                <h4>I</h4>
                                            </div>
                                            <div clas="col-6" style="text-align: center;">
                                                <img class="img-option" style="width: 150px; height: auto; border-radius: 2%; margin-top: 10px;" src="../../assets/uploads/<?php echo $department; ?>/<?php echo $division; ?>/<?php echo $exam_id; ?>/<?php echo $testV['question_type']; ?>/<?php echo $testV['question_no']; ?>/option4/<?php echo $testV['img_4']; ?>"/>
                                                <h4>II</h4>
                                            </div>
                                        </div>
                                    <?php }
                                    // 2-3 OR 3-2
                                    else if ($testV['img_1'] == null && $testV['img_2'] != null && $testV['img_3'] != null
                                        && $testV['img_4'] == null){ ?>
                                        <div class="row" style="justify-content: space-evenly;">
                                            <div clas="col-6" style="text-align: center;">
                                                <img class="img-option" style="width: 150px; height: auto; border-radius: 2%; margin-top: 10px;" src="../../assets/uploads/<?php echo $department; ?>/<?php echo $division; ?>/<?php echo $exam_id; ?>/<?php echo $testV['question_type']; ?>/<?php echo $testV['question_no']; ?>/option2/<?php echo $testV['img_2']; ?>"/>
                                                <h4>I</h4>
                                            </div>
                                            <div clas="col-6" style="text-align: center;">
                                                <img class="img-option" style="width: 150px; height: auto; border-radius: 2%; margin-top: 10px;" src="../../assets/uploads/<?php echo $department; ?>/<?php echo $division; ?>/<?php echo $exam_id; ?>/<?php echo $testV['question_type']; ?>/<?php echo $testV['question_no']; ?>/option3/<?php echo $testV['img_3']; ?>"/>
                                                <h4>II</h4>
                                            </div>
                                        </div>
                                    <?php }
                                    // 2-4 OR 4-2
                                    else if ($testV['img_1'] == null && $testV['img_2'] != null && $testV['img_3'] == null
                                        && $testV['img_4'] != null){ ?>
                                        <div class="row" style="justify-content: space-evenly;">
                                            <div clas="col-6" style="text-align: center;">
                                                <img class="img-option" style="width: 150px; height: auto; border-radius: 2%; margin-top: 10px;" src="../../assets/uploads/<?php echo $department; ?>/<?php echo $division; ?>/<?php echo $exam_id; ?>/<?php echo $testV['question_type']; ?>/<?php echo $testV['question_no']; ?>/option2/<?php echo $testV['img_2']; ?>"/>
                                                <h4>I</h4>
                                            </div>
                                            <div clas="col-6" style="text-align: center;">
                                                <img class="img-option" style="width: 150px; height: auto; border-radius: 2%; margin-top: 10px;" src="../../assets/uploads/<?php echo $department; ?>/<?php echo $division; ?>/<?php echo $exam_id; ?>/<?php echo $testV['question_type']; ?>/<?php echo $testV['question_no']; ?>/option4/<?php echo $testV['img_4']; ?>"/>
                                                <h4>II</h4>
                                            </div>
                                        </div>
                                    <?php }
                                    // 3-4 OR 4-3
                                    else if ($testV['img_1'] == null && $testV['img_2'] == null && $testV['img_3'] != null
                                        && $testV['img_4'] != null){ ?>
                                        <div class="row" style="justify-content: space-evenly;">
                                            <div clas="col-6" style="text-align: center;">
                                                <img class="img-option" style="width: 150px; height: auto; border-radius: 2%; margin-top: 10px;" src="../../assets/uploads/<?php echo $department; ?>/<?php echo $division; ?>/<?php echo $exam_id; ?>/<?php echo $testV['question_type']; ?>/<?php echo $testV['question_no']; ?>/option3/<?php echo $testV['img_3']; ?>"/>
                                                <h4>I/h4>
                                            </div>
                                            <div clas="col-6" style="text-align: center;">
                                                <img class="img-option" style="width: 150px; height: auto; border-radius: 2%; margin-top: 10px;" src="../../assets/uploads/<?php echo $department; ?>/<?php echo $division; ?>/<?php echo $exam_id; ?>/<?php echo $testV['question_type']; ?>/<?php echo $testV['question_no']; ?>/option4/<?php echo $testV['img_4']; ?>"/>
                                                <h4>II</h4>
                                            </div>
                                        </div>
                                    <?php }

                                    else if ($testV['img_1'] != null && $testV['img_2'] == null && $testV['img_3'] == null
                                        && $testV['img_4'] == null){ ?>
                                        <div class="row" style="justify-content: space-evenly;">
                                            <div clas="col-12" style="text-align: center;">
                                                <img class="img-option" style="width: 150px; height: auto; border-radius: 2%; margin-top: 10px;" src="../../assets/uploads/<?php echo $department; ?>/<?php echo $division; ?>/<?php echo $exam_id; ?>/<?php echo $testV['question_type']; ?>/<?php echo $testV['question_no']; ?>/option1/<?php echo $testV['img_1']; ?>"/>
                                                <h4>I</h4>
                                            </div>
                                        </div>
                                    <?php }
                                    else if ($testV['img_1'] == null && $testV['img_2'] != null && $testV['img_3'] == null
                                        && $testV['img_4'] == null){ ?>
                                        <div class="row" style="justify-content: space-evenly;">
                                            <div clas="col-12" style="text-align: center;">
                                                <img class="img-option" style="width: 150px; height: auto; border-radius: 2%; margin-top: 10px;" src="../../assets/uploads/<?php echo $department; ?>/<?php echo $division; ?>/<?php echo $exam_id; ?>/<?php echo $testV['question_type']; ?>/<?php echo $testV['question_no']; ?>/option2/<?php echo $testV['img_2']; ?>"/>
                                                <h4>I</h4>
                                            </div>
                                        </div>
                                    <?php }
                                    else if ($testV['img_1'] == null && $testV['img_2'] == null && $testV['img_3'] != null
                                        && $testV['img_4'] == null){ ?>
                                        <div class="row" style="justify-content: space-evenly;">
                                            <div clas="col-12" style="text-align: center;">
                                                <img class="img-option" style="width: 150px; height: auto; border-radius: 2%; margin-top: 10px;" src="../../assets/uploads/<?php echo $department; ?>/<?php echo $division; ?>/<?php echo $exam_id; ?>/<?php echo $testV['question_type']; ?>/<?php echo $testV['question_no']; ?>/option3/<?php echo $testV['img_3']; ?>"/>
                                                <h4>I</h4>
                                            </div>
                                        </div>
                                    <?php }
                                    else if ($testV['img_1'] == null && $testV['img_2'] == null && $testV['img_3'] == null
                                        && $testV['img_4'] != null){ ?>
                                        <div class="row" style="justify-content: space-evenly;">
                                            <div clas="col-12" style="text-align: center;">
                                                <img class="img-option" style="width: 150px; height: auto; border-radius: 2%; margin-top: 10px;" src="../../assets/uploads/<?php echo $department; ?>/<?php echo $division; ?>/<?php echo $exam_id; ?>/<?php echo $testV['question_type']; ?>/<?php echo $testV['question_no']; ?>/option4/<?php echo $testV['img_4']; ?>"/>
                                                <h4>I</h4>
                                            </div>
                                        </div>
                                    <?php } ?>
                                    <span>Option 1: <?php echo $testV['option_1']; ?></span><br>
                                    <span>Option 2: <?php echo $testV['option_2']; ?></span><br>
                                    <span>Option 3: <?php echo $testV['option_3']; ?></span><br>
                                    <span>Option 4: <?php echo $testV['option_4']; ?></span><br>
                                    <span>Answer: <?php echo $testV['ans']; ?></span>
                                </div>
                                <div id="trueOrFalseContainer<?php echo $testV['option_id']; ?>" class="col-lg-12 pt-2"  style="display: none;" >
                                    <?php
                                    if ($testV['img_1'] != null && $testV['img_2'] != null && $testV['img_3'] != null && $testV['img_4'] != null){ ?>
                                        <div class="row" style="justify-content: space-evenly;">
                                            <div clas="col-3" style="text-align: center;">
                                                <img class="img-option" style="width: 150px; height: auto; border-radius: 2%; margin-top: 10px;" src="../../assets/uploads/<?php echo $department; ?>/<?php echo $division; ?>/<?php echo $exam_id; ?>/<?php echo $testV['question_type']; ?>/<?php echo $testV['question_no']; ?>/option1/<?php echo $testV['img_1']; ?>" />
                                            </div>
                                            <div clas="col-3" style="text-align: center;">
                                                <img class="img-option" style="width: 150px; height: auto; border-radius: 2%; margin-top: 10px;" src="../../assets/uploads/<?php echo $department; ?>/<?php echo $division; ?>/<?php echo $exam_id; ?>/<?php echo $testV['question_type']; ?>/<?php echo $testV['question_no']; ?>/option2/<?php echo $testV['img_2']; ?>" />
                                            </div>
                                            <div clas="col-3" style="text-align: center;">
                                                <img class="img-option" style="width: 150px; height: auto; border-radius: 2%; margin-top: 10px;" src="../../assets/uploads/<?php echo $department; ?>/<?php echo $division; ?>/<?php echo $exam_id; ?>/<?php echo $testV['question_type']; ?>/<?php echo $testV['question_no']; ?>/option3/<?php echo $testV['img_3']; ?>" />
                                            </div>
                                            <div clas="col-3" style="text-align: center;">
                                                <img class="img-option" style="width: 150px; height: auto; border-radius: 2%; margin-top: 10px;" src="../../assets/uploads/<?php echo $department; ?>/<?php echo $division; ?>/<?php echo $exam_id; ?>/<?php echo $testV['question_type']; ?>/<?php echo $testV['question_no']; ?>/option4/<?php echo $testV['img_4']; ?>" />
                                            </div>
                                        </div>
                                    <?php }
                                    else if ($testV['img_1'] != null && $testV['img_2'] != null && $testV['img_3'] != null
                                        && $testV['img_4'] == null){ ?>
                                        <div class="row" style="justify-content: space-evenly;">
                                            <div clas="col-4" style="text-align: center;">
                                                <img class="img-option" style="width: 150px; height: auto; border-radius: 2%; margin-top: 10px;" src="../../assets/uploads/<?php echo $department; ?>/<?php echo $division; ?>/<?php echo $exam_id; ?>/<?php echo $testV['question_type']; ?>/<?php echo $testV['question_no']; ?>/option1/<?php echo $testV['img_1']; ?>" />
                                            </div>
                                            <div clas="col-4" style="text-align: center;">
                                                <img class="img-option" style="width: 150px; height: auto; border-radius: 2%; margin-top: 10px;" src="../../assets/uploads/<?php echo $department; ?>/<?php echo $division; ?>/<?php echo $exam_id; ?>/<?php echo $testV['question_type']; ?>/<?php echo $testV['question_no']; ?>/option2/<?php echo $testV['img_2']; ?>" />
                                            </div>
                                            <div clas="col-4" style="text-align: center;">
                                                <img class="img-option" style="width: 150px; height: auto; border-radius: 2%; margin-top: 10px;" src="../../assets/uploads/<?php echo $department; ?>/<?php echo $division; ?>/<?php echo $exam_id; ?>/<?php echo $testV['question_type']; ?>/<?php echo $testV['question_no']; ?>/option3/<?php echo $testV['img_3']; ?>" />
                                            </div>
                                        </div>
                                    <?php }
                                    else if ($testV['img_1'] != null && $testV['img_2'] != null && $testV['img_3'] == null
                                        && $testV['img_4'] != null){ ?>
                                        <div class="row" style="justify-content: space-evenly;">
                                            <div clas="col-4" style="text-align: center;">
                                                <img class="img-option" style="width: 150px; height: auto; border-radius: 2%; margin-top: 10px;" src="../../assets/uploads/<?php echo $department; ?>/<?php echo $division; ?>/<?php echo $exam_id; ?>/<?php echo $testV['question_type']; ?>/<?php echo $testV['question_no']; ?>/option1/<?php echo $testV['img_1']; ?>" />
                                            </div>
                                            <div clas="col-4" style="text-align: center;">
                                                <img class="img-option" style="width: 150px; height: auto; border-radius: 2%; margin-top: 10px;" src="../../assets/uploads/<?php echo $department; ?>/<?php echo $division; ?>/<?php echo $exam_id; ?>/<?php echo $testV['question_type']; ?>/<?php echo $testV['question_no']; ?>/option2/<?php echo $testV['img_2']; ?>" />
                                            </div>
                                            <div clas="col-3" style="text-align: center;">
                                                <img class="img-option" style="width: 150px; height: auto; border-radius: 2%; margin-top: 10px;" src="../../assets/uploads/<?php echo $department; ?>/<?php echo $division; ?>/<?php echo $exam_id; ?>/<?php echo $testV['question_type']; ?>/<?php echo $testV['question_no']; ?>/option4/<?php echo $testV['img_4']; ?>" />
                                            </div>
                                        </div>
                                    <?php }
                                    else if ($testV['img_1'] != null && $testV['img_2'] == null && $testV['img_3'] != null
                                        && $testV['img_4'] != null){ ?>
                                        <div class="row" style="justify-content: space-evenly;">
                                            <div clas="col-4" style="text-align: center;">
                                                <img class="img-option" style="width: 150px; height: auto; border-radius: 2%; margin-top: 10px;" src="../../assets/uploads/<?php echo $department; ?>/<?php echo $division; ?>/<?php echo $exam_id; ?>/<?php echo $testV['question_type']; ?>/<?php echo $testV['question_no']; ?>/option1/<?php echo $testV['img_1']; ?>" />
                                            </div>
                                            <div clas="col-4" style="text-align: center;">
                                                <img class="img-option" style="width: 150px; height: auto; border-radius: 2%; margin-top: 10px;" src="../../assets/uploads/<?php echo $department; ?>/<?php echo $division; ?>/<?php echo $exam_id; ?>/<?php echo $testV['question_type']; ?>/<?php echo $testV['question_no']; ?>/option3/<?php echo $testV['img_3']; ?>" />
                                            </div>
                                            <div clas="col-3" style="text-align: center;">
                                                <img class="img-option" style="width: 150px; height: auto; border-radius: 2%; margin-top: 10px;" src="../../assets/uploads/<?php echo $department; ?>/<?php echo $division; ?>/<?php echo $exam_id; ?>/<?php echo $testV['question_type']; ?>/<?php echo $testV['question_no']; ?>/option4/<?php echo $testV['img_4']; ?>" />
                                            </div>
                                        </div>
                                    <?php }
                                    else if ($testV['img_1'] == null && $testV['img_2'] != null && $testV['img_3'] != null
                                        && $testV['img_4'] != null){ ?>
                                        <div class="row" style="justify-content: space-evenly;">
                                            <div clas="col-4" style="text-align: center;">
                                                <img class="img-option" style="width: 150px; height: auto; border-radius: 2%; margin-top: 10px;" src="../../assets/uploads/<?php echo $department; ?>/<?php echo $division; ?>/<?php echo $exam_id; ?>/<?php echo $testV['question_type']; ?>/<?php echo $testV['question_no']; ?>/option2/<?php echo $testV['img_2']; ?>" />
                                            </div>
                                            <div clas="col-4" style="text-align: center;">
                                                <img class="img-option" style="width: 150px; height: auto; border-radius: 2%; margin-top: 10px;" src="../../assets/uploads/<?php echo $department; ?>/<?php echo $division; ?>/<?php echo $exam_id; ?>/<?php echo $testV['question_type']; ?>/<?php echo $testV['question_no']; ?>/option3/<?php echo $testV['img_3']; ?>" />
                                            </div>
                                            <div clas="col-3" style="text-align: center;">
                                                <img class="img-option" style="width: 150px; height: auto; border-radius: 2%; margin-top: 10px;" src="../../assets/uploads/<?php echo $department; ?>/<?php echo $division; ?>/<?php echo $exam_id; ?>/<?php echo $testV['question_type']; ?>/<?php echo $testV['question_no']; ?>/option4/<?php echo $testV['img_4']; ?>" />
                                            </div>
                                        </div>
                                    <?php }

                                    // 1-2 OR 2-1
                                    else if ($testV['img_1'] != null && $testV['img_2'] != null && $testV['img_3'] == null
                                        && $testV['img_4'] == null){ ?>
                                        <div class="row" style="justify-content: space-evenly;">
                                            <div clas="col-6" style="text-align: center;">
                                                <img class="img-option" style="width: 150px; height: auto; border-radius: 2%; margin-top: 10px;" src="../../assets/uploads/<?php echo $department; ?>/<?php echo $division; ?>/<?php echo $exam_id; ?>/<?php echo $testV['question_type']; ?>/<?php echo $testV['question_no']; ?>/option1/<?php echo $testV['img_1']; ?>" />
                                            </div>
                                            <div clas="col-6" style="text-align: center;">
                                                <img class="img-option" style="width: 150px; height: auto; border-radius: 2%; margin-top: 10px;" src="../../assets/uploads/<?php echo $department; ?>/<?php echo $division; ?>/<?php echo $exam_id; ?>/<?php echo $testV['question_type']; ?>/<?php echo $testV['question_no']; ?>/option2/<?php echo $testV['img_2']; ?>" />
                                            </div>
                                        </div>
                                    <?php }
                                    // 1-3 OR 3-1
                                    else if ($testV['img_1'] != null && $testV['img_2'] == null && $testV['img_3'] != null
                                        && $testV['img_4'] == null){ ?>
                                        <div class="row" style="justify-content: space-evenly;">
                                            <div clas="col-6" style="text-align: center;">
                                                <img class="img-option" style="width: 150px; height: auto; border-radius: 2%; margin-top: 10px;" src="../../assets/uploads/<?php echo $department; ?>/<?php echo $division; ?>/<?php echo $exam_id; ?>/<?php echo $testV['question_type']; ?>/<?php echo $testV['question_no']; ?>/option1/<?php echo $testV['img_1']; ?>" />
                                            </div>
                                            <div clas="col-6" style="text-align: center;">
                                                <img class="img-option" style="width: 150px; height: auto; border-radius: 2%; margin-top: 10px;" src="../../assets/uploads/<?php echo $department; ?>/<?php echo $division; ?>/<?php echo $exam_id; ?>/<?php echo $testV['question_type']; ?>/<?php echo $testV['question_no']; ?>/option3/<?php echo $testV['img_3']; ?>" />
                                            </div>
                                        </div>
                                    <?php }
                                    // 1-4 OR 4-1
                                    else if ($testV['img_1'] != null && $testV['img_2'] == null && $testV['img_3'] == null
                                        && $testV['img_4'] != null){ ?>
                                        <div class="row" style="justify-content: space-evenly;">
                                            <div clas="col-6" style="text-align: center;">
                                                <img class="img-option" style="width: 150px; height: auto; border-radius: 2%; margin-top: 10px;" src="../../assets/uploads/<?php echo $department; ?>/<?php echo $division; ?>/<?php echo $exam_id; ?>/<?php echo $testV['question_type']; ?>/<?php echo $testV['question_no']; ?>/option1/<?php echo $testV['img_1']; ?>" />
                                            </div>
                                            <div clas="col-6" style="text-align: center;">
                                                <img class="img-option" style="width: 150px; height: auto; border-radius: 2%; margin-top: 10px;" src="../../assets/uploads/<?php echo $department; ?>/<?php echo $division; ?>/<?php echo $exam_id; ?>/<?php echo $testV['question_type']; ?>/<?php echo $testV['question_no']; ?>/option4/<?php echo $testV['img_4']; ?>" />
                                            </div>
                                        </div>
                                    <?php }
                                    // 2-3 OR 3-2
                                    else if ($testV['img_1'] == null && $testV['img_2'] != null && $testV['img_3'] != null
                                        && $testV['img_4'] == null){ ?>
                                        <div class="row" style="justify-content: space-evenly;">
                                            <div clas="col-6" style="text-align: center;">
                                                <img class="img-option" style="width: 150px; height: auto; border-radius: 2%; margin-top: 10px;" src="../../assets/uploads/<?php echo $department; ?>/<?php echo $division; ?>/<?php echo $exam_id; ?>/<?php echo $testV['question_type']; ?>/<?php echo $testV['question_no']; ?>/option2/<?php echo $testV['img_2']; ?>" />
                                            </div>
                                            <div clas="col-6" style="text-align: center;">
                                                <img class="img-option" style="width: 150px; height: auto; border-radius: 2%; margin-top: 10px;" src="../../assets/uploads/<?php echo $department; ?>/<?php echo $division; ?>/<?php echo $exam_id; ?>/<?php echo $testV['question_type']; ?>/<?php echo $testV['question_no']; ?>/option3/<?php echo $testV['img_3']; ?>" />
                                            </div>
                                        </div>
                                    <?php }
                                    // 2-4 OR 4-2
                                    else if ($testV['img_1'] == null && $testV['img_2'] != null && $testV['img_3'] == null
                                        && $testV['img_4'] != null){ ?>
                                        <div class="row" style="justify-content: space-evenly;">
                                            <div clas="col-6" style="text-align: center;">
                                                <img class="img-option" style="width: 150px; height: auto; border-radius: 2%; margin-top: 10px;" src="../../assets/uploads/<?php echo $department; ?>/<?php echo $division; ?>/<?php echo $exam_id; ?>/<?php echo $testV['question_type']; ?>/<?php echo $testV['question_no']; ?>/option2/<?php echo $testV['img_2']; ?>" />
                                            </div>
                                            <div clas="col-6" style="text-align: center;">
                                                <img class="img-option" style="width: 150px; height: auto; border-radius: 2%; margin-top: 10px;" src="../../assets/uploads/<?php echo $department; ?>/<?php echo $division; ?>/<?php echo $exam_id; ?>/<?php echo $testV['question_type']; ?>/<?php echo $testV['question_no']; ?>/option4/<?php echo $testV['img_4']; ?>" />
                                            </div>
                                        </div>
                                    <?php }
                                    // 3-4 OR 4-3
                                    else if ($testV['img_1'] == null && $testV['img_2'] == null && $testV['img_3'] != null
                                        && $testV['img_4'] != null){ ?>
                                        <div class="row" style="justify-content: space-evenly;">
                                            <div clas="col-6" style="text-align: center;">
                                                <img class="img-option" style="width: 150px; height: auto; border-radius: 2%; margin-top: 10px;" src="../../assets/uploads/<?php echo $department; ?>/<?php echo $division; ?>/<?php echo $exam_id; ?>/<?php echo $testV['question_type']; ?>/<?php echo $testV['question_no']; ?>/option3/<?php echo $testV['img_3']; ?>" />
                                            </div>
                                            <div clas="col-6" style="text-align: center;">
                                                <img class="img-option" style="width: 150px; height: auto; border-radius: 2%; margin-top: 10px;" src="../../assets/uploads/<?php echo $department; ?>/<?php echo $division; ?>/<?php echo $exam_id; ?>/<?php echo $testV['question_type']; ?>/<?php echo $testV['question_no']; ?>/option4/<?php echo $testV['img_4']; ?>" />
                                            </div>
                                        </div>
                                    <?php }

                                    else if ($testV['img_1'] != null && $testV['img_2'] == null && $testV['img_3'] == null
                                        && $testV['img_4'] == null){ ?>
                                        <div class="row" style="justify-content: space-evenly;">
                                            <div clas="col-12" style="text-align: center;">
                                                <img class="img-option" style="width: 150px; height: auto; border-radius: 2%; margin-top: 10px;" src="../../assets/uploads/<?php echo $department; ?>/<?php echo $division; ?>/<?php echo $exam_id; ?>/<?php echo $testV['question_type']; ?>/<?php echo $testV['question_no']; ?>/option1/<?php echo $testV['img_1']; ?>" />
                                            </div>
                                        </div>
                                    <?php }
                                    else if ($testV['img_1'] == null && $testV['img_2'] != null && $testV['img_3'] == null
                                        && $testV['img_4'] == null){ ?>
                                        <div class="row" style="justify-content: space-evenly;">
                                            <div clas="col-12" style="text-align: center;">
                                                <img class="img-option" style="width: 150px; height: auto; border-radius: 2%; margin-top: 10px;" src="../../assets/uploads/<?php echo $department; ?>/<?php echo $division; ?>/<?php echo $exam_id; ?>/<?php echo $testV['question_type']; ?>/<?php echo $testV['question_no']; ?>/option2/<?php echo $testV['img_2']; ?>" />
                                            </div>
                                        </div>
                                    <?php }
                                    else if ($testV['img_1'] == null && $testV['img_2'] == null && $testV['img_3'] != null
                                        && $testV['img_4'] == null){ ?>
                                        <div class="row" style="justify-content: space-evenly;">
                                            <div clas="col-12" style="text-align: center;">
                                                <img class="img-option" style="width: 150px; height: auto; border-radius: 2%; margin-top: 10px;" src="../../assets/uploads/<?php echo $department; ?>/<?php echo $division; ?>/<?php echo $exam_id; ?>/<?php echo $testV['question_type']; ?>/<?php echo $testV['question_no']; ?>/option3/<?php echo $testV['img_3']; ?>" />
                                            </div>
                                        </div>
                                    <?php }
                                    else if ($testV['img_1'] == null && $testV['img_2'] == null && $testV['img_3'] == null
                                        && $testV['img_4'] != null){ ?>
                                        <div class="row" style="justify-content: space-evenly;">
                                            <div clas="col-12" style="text-align: center;">
                                                <img class="img-option" style="width: 150px; height: auto; border-radius: 2%; margin-top: 10px;" src="../../assets/uploads/<?php echo $department; ?>/<?php echo $division; ?>/<?php echo $exam_id; ?>/<?php echo $testV['question_type']; ?>/<?php echo $testV['question_no']; ?>/option4/<?php echo $testV['img_4']; ?>" />
                                            </div>
                                        </div>
                                    <?php } ?>
                                    <br>
                                    <span>Answer: <?php echo $testV['ans']; ?></span>
                                </div>
                                <div id="multipleImageContainer<?php echo $testV['option_id']; ?>" class="col-lg-12 pt-2" style="display: none;">
                                    <?php
                                    if ($testV['img_1'] != null && $testV['img_2'] != null && $testV['img_3'] != null && $testV['img_4'] != null){ ?>
                                        <div class="row" style="justify-content: space-evenly;">
                                            <div clas="col-3" style="text-align: center;">
                                                <img class="img-option" style="width: 150px; height: auto; border-radius: 2%; margin-top: 10px;" src="../../assets/uploads/<?php echo $department; ?>/<?php echo $division; ?>/<?php echo $exam_id; ?>/<?php echo $testV['question_type']; ?>/<?php echo $testV['question_no']; ?>/option1/<?php echo $testV['img_1']; ?>" />
                                                <h4><?php echo $testV['option_1']; ?></h4>
                                            </div>
                                            <div clas="col-3" style="text-align: center;">
                                                <img class="img-option" style="width: 150px; height: auto; border-radius: 2%; margin-top: 10px;" src="../../assets/uploads/<?php echo $department; ?>/<?php echo $division; ?>/<?php echo $exam_id; ?>/<?php echo $testV['question_type']; ?>/<?php echo $testV['question_no']; ?>/option2/<?php echo $testV['img_2']; ?>" />
                                                <h4><?php echo $testV['option_2']; ?></h4>
                                            </div>
                                            <div clas="col-3" style="text-align: center;">
                                                <img class="img-option" style="width: 150px; height: auto; border-radius: 2%; margin-top: 10px;" src="../../assets/uploads/<?php echo $department; ?>/<?php echo $division; ?>/<?php echo $exam_id; ?>/<?php echo $testV['question_type']; ?>/<?php echo $testV['question_no']; ?>/option3/<?php echo $testV['img_3']; ?>" />
                                                <h4><?php echo $testV['option_3']; ?></h4>
                                            </div>
                                            <div clas="col-3" style="text-align: center;">
                                                <img class="img-option" style="width: 150px; height: auto; border-radius: 2%; margin-top: 10px;" src="../../assets/uploads/<?php echo $department; ?>/<?php echo $division; ?>/<?php echo $exam_id; ?>/<?php echo $testV['question_type']; ?>/<?php echo $testV['question_no']; ?>/option4/<?php echo $testV['img_4']; ?>" />
                                                <h4><?php echo $testV['option_4']; ?></h4>
                                            </div>
                                        </div>
                                    <?php } ?>
                                    <br>
                                    <span>Answer: <?php echo $testV['ans']; ?></span>
                                </div>
                            </div>
                        </li>
                    <?php } ?>
                </ul>
            </div>
        </div>
    </div>
</div>
    <?php

}?>