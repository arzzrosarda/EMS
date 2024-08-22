<?php
global $conn;
session_start();
    require "../db/conn.php";
    require "../FPDF/fpdf.php";

    if (!isset($_SESSION['user'])){
        header("location: ../auth/auth-login.php");
    }else {
        if (isset($_REQUEST['exam_id']) && isset($_REQUEST['userid'])){
            $index = 1;
            $exam_id = $_REQUEST['exam_id'];
            $department = $_SESSION['department'];
            $userid = $_REQUEST['userid'];
            $score = $_REQUEST['count_correct'];
            $full_name = $_REQUEST['full_name'];
            $exam_title = $_REQUEST['exam_title'];
            $user_div = $_REQUEST['user_div'];
            $userQ = $conn->query("SELECT * FROM user WHERE id = '$userid'");
            $fetchUser = $userQ->fetch();
            $userFname = $fetchUser['lname'] . ", " . $fetchUser['fname'] . " " . $fetchUser['mname'];
            $scoreForReviewQ = $conn->query("SELECT * FROM exam_result WHERE exam_id = '$exam_id' AND examiner_id = '$userid'");
            $scoreForReviewFetch = $scoreForReviewQ->fetch();
            $datescore = $scoreForReviewFetch['ans_date'];

            class PDF extends FPDF
            {
                // MultiCell
                function MultiCell($w, $h, $txt, $border=0, $ln=0, $align='J', $fill=false)
                {
                    // Custom Tomaz Ahlin
                    if($ln == 0) {
                        $current_y = $this->GetY();
                        $current_x = $this->GetX();
                    }

                    // Output text with automatic or explicit line breaks
                    $cw = &$this->CurrentFont['cw'];
                    if($w==0)
                        $w = $this->w-$this->rMargin-$this->x;
                    $wmax = ($w-2*$this->cMargin)*1000/$this->FontSize;
                    $s = str_replace("\r",'',$txt);
                    $nb = strlen($s);
                    if($nb>0 && $s[$nb-1]=="\n")
                        $nb--;
                    $b = 0;
                    if($border)
                    {
                        if($border==1)
                        {
                            $border = 'LTRB';
                            $b = 'LRT';
                            $b2 = 'LR';
                        }
                        else
                        {
                            $b2 = '';
                            if(strpos($border,'L')!==false)
                                $b2 .= 'L';
                            if(strpos($border,'R')!==false)
                                $b2 .= 'R';
                            $b = (strpos($border,'T')!==false) ? $b2.'T' : $b2;
                        }
                    }
                    $sep = -1;
                    $i = 0;
                    $j = 0;
                    $l = 0;
                    $ns = 0;
                    $nl = 1;
                    while($i<$nb)
                    {
                        // Get next character
                        $c = $s[$i];
                        if($c=="\n")
                        {
                            // Explicit line break
                            if($this->ws>0)
                            {
                                $this->ws = 0;
                                $this->_out('0 Tw');
                            }
                            $this->Cell($w,$h,substr($s,$j,$i-$j),$b,2,$align,$fill);
                            $i++;
                            $sep = -1;
                            $j = $i;
                            $l = 0;
                            $ns = 0;
                            $nl++;
                            if($border && $nl==2)
                                $b = $b2;
                            continue;
                        }
                        if($c==' ')
                        {
                            $sep = $i;
                            $ls = $l;
                            $ns++;
                        }
                        $l += $cw[$c];
                        if($l>$wmax)
                        {
                            // Automatic line break
                            if($sep==-1)
                            {
                                if($i==$j)
                                    $i++;
                                if($this->ws>0)
                                {
                                    $this->ws = 0;
                                    $this->_out('0 Tw');
                                }
                                $this->Cell($w,$h,substr($s,$j,$i-$j),$b,2,$align,$fill);
                            }
                            else
                            {
                                if($align=='J')
                                {
                                    $this->ws = ($ns>1) ?     ($wmax-$ls)/1000*$this->FontSize/($ns-1) : 0;
                                    $this->_out(sprintf('%.3F Tw',$this->ws*$this->k));
                                }
                                $this->Cell($w,$h,substr($s,$j,$sep-$j),$b,2,$align,$fill);
                                $i = $sep+1;
                            }
                            $sep = -1;
                            $j = $i;
                            $l = 0;
                            $ns = 0;
                            $nl++;
                            if($border && $nl==2)
                                $b = $b2;
                        }
                        else
                            $i++;
                    }
                    // Last chunk
                    if($this->ws>0)
                    {
                        $this->ws = 0;
                        $this->_out('0 Tw');
                    }
                    if($border && strpos($border,'B')!==false)
                        $b .= 'B';
                    $this->Cell($w,$h,substr($s,$j,$i-$j),$b,2,$align,$fill);
                    $this->x = $this->lMargin;

                    // Custom Tomaz Ahlin
                    if($ln == 0) {
                        $this->SetXY($current_x + $w, $current_y);
                    }
                }

                // Page header
                function Header()
                {
                    global $conn;
                    $department = $_SESSION['department'];
                    $departmentQuery = $conn->query("SELECT * FROM department WHERE department = '$department'");
                    $dep = $departmentQuery->fetch();
                    if ($this->page == 1)
                    {
                        // Logo
                        $this->Image('../assets/img/Logo/Cavite_Province.png',10,6,25);
                        $this->Image('../assets/uploads/'.$dep['department'].'/logo/'.$dep['department_logo'],175,6,25, 25, 'png');
                        // Arial bold 15
                        $this->SetFont('Arial','B',15);

                        // Project Title
                        $this->Cell(40, 0, '', 0, 0);
                        $this->MultiCell(110,5,'PROVINCIAL GOVERNMENT OF CAVITE',0,1,'C');

                        // Subtitle

                        $this->SetFont('Arial','I',13);
                        $this->Cell(40, 0, '', 0, 0);
                        $this->MultiCell(110,5,'Examination System for Applicants',0,1,'C');

                        // Exam Title
                        $this->SetFont('Arial','',13);
                        $this->Cell(40, 0, '', 0, 0);
                        $this->MultiCell(110,5, $dep['department_name'],0,1,'C');

                        // Line break
                        $this->Ln(10);
                    }else {
                        $this->Ln(10);
                    }
                }

                // Page footer
                function Footer()
                {
                    // Position at 1.5 cm from bottom
                    $this->SetY(-20);
                    // Arial italic 8
                    $this->SetFont('Arial','I',8);
                    // Page number
                    $this->Cell(0,10,'Page '.$this->PageNo().'/{nb}',0,0,'R');
                }
            }

            // Instanciation of inherited class
            $pdf = new PDF('P','mm','A4');
            $pdf->SetTitle($department." ".$exam_title." - ".$userFname);
            $pdf->AliasNbPages();
            $pdf->AddPage();
            $pdf->Cell(80);
            $pdf->SetFont('Times','B',15);
            $pdf->Cell(30,5, $exam_title,0,1, 'C');
            $pdf->Ln(10);
            $pdf->SetFont('Times','B',12);
            $pdf->Cell(15,5,'Name: ',0,0);
            $pdf->SetFont('Times','',12);
            $pdf->Cell(115,5, $full_name,0,0);
            $pdf->SetFont('Times','B',12);
            $pdf->Cell(13,5,'Date: ',0,0);
            $pdf->SetFont('Times','',12);
            $pdf->Cell(42,5,$datescore,0,0);
            $pdf->Ln(5);
            $pdf->SetFont('Times','B',12);
            $pdf->Cell(43,5,'Department/Division: ',0,0);
            $pdf->SetFont('Times','',12);
            $pdf->Cell(87,5, $department."/".$user_div,0,0);
            $pdf->SetFont('Times','B',12);
            $pdf->Cell(15,5,'Score: ',0,0);
            $pdf->SetFont('Times','',12);
            $pdf->Cell(40,5, $score,0,0);
            $pdf->Ln(10);


            // Multiple Choice Query //
            $MultipleChoiceQuery = $conn->query("SELECT a.`id`, a.`title`,
                    b.`q_id`, b.`points`, b.`question_type`, b.`question`,
                    c.`id` AS option_id, c.`o_id`, c.`option_1`, c.`option_2`, c.`option_3`, c.`option_4`, c.`img_1`, c.`img_2`, c.`img_3`, c.`img_4`, c.`ans`,
                    d.`q_no`, d.`ans` AS result_ans,
                    d.`examiner_id`, d.`correct_incorrect`, d.`ans_date`, d.`ans_time`, d.`points` AS score_points
                    FROM `exam_title` a
                    LEFT JOIN `question` b ON a.`id` = b.`q_id` 
                    LEFT JOIN `options` c ON b.`id` = c.`id` 
                    LEFT JOIN `exam_result` d ON b.`id` = d.`q_no`
                    WHERE d.`examiner_id` = '$userid' AND a.`id` = '$exam_id' AND b.`question_type` = 'Multiple Choice'");

            // Short Answer Query //
            $ShortAnswerQuery = $conn->query("SELECT a.`id`, a.`title`,
                    b.`q_id`, b.`points`, b.`question_type`, b.`question`,
                    c.`id` AS option_id, c.`o_id`, c.`option_1`, c.`option_2`, c.`option_3`, c.`option_4`, c.`img_1`, c.`img_2`, c.`img_3`, c.`img_4`, c.`ans`,
                    d.`q_no`, d.`ans` AS result_ans,
                    d.`examiner_id`, d.`correct_incorrect`, d.`ans_date`, d.`ans_time`, d.`points` AS score_points
                    FROM `exam_title` a
                    LEFT JOIN `question` b ON a.`id` = b.`q_id` 
                    LEFT JOIN `options` c ON b.`id` = c.`id` 
                    LEFT JOIN `exam_result` d ON b.`id` = d.`q_no`
                    WHERE d.`examiner_id` = '$userid' AND a.`id` = '$exam_id' AND b.`question_type` = 'Short Answer'");

            // True or False Query //
            $TrueFalseQuery = $conn->query("SELECT a.`id`, a.`title`,
                    b.`q_id`, b.`points`, b.`question_type`, b.`question`,
                    c.`id` AS option_id, c.`o_id`, c.`option_1`, c.`option_2`, c.`option_3`, c.`option_4`, c.`img_1`, c.`img_2`, c.`img_3`, c.`img_4`, c.`ans`,
                    d.`q_no`, d.`ans` AS result_ans,
                    d.`examiner_id`, d.`correct_incorrect`, d.`ans_date`, d.`ans_time`, d.`points` AS score_points
                    FROM `exam_title` a
                    LEFT JOIN `question` b ON a.`id` = b.`q_id` 
                    LEFT JOIN `options` c ON b.`id` = c.`id` 
                    LEFT JOIN `exam_result` d ON b.`id` = d.`q_no`
                    WHERE d.`examiner_id` = '$userid' AND a.`id` = '$exam_id' AND b.`question_type` = 'True/False'");

            // Multiple Image Query //
            $MultipleImageQuery = $conn->query("SELECT a.`id`, a.`title`,
                    b.`q_id`, b.`points`, b.`question_type`, b.`question`,
                    c.`id` AS option_id, c.`o_id`, c.`option_1`, c.`option_2`, c.`option_3`, c.`option_4`, c.`img_1`, c.`img_2`, c.`img_3`, c.`img_4`, c.`ans`,
                    d.`q_no`, d.`ans` AS result_ans,
                    d.`examiner_id`, d.`correct_incorrect`, d.`ans_date`, d.`ans_time`, d.`points` AS score_points
                    FROM `exam_title` a
                    LEFT JOIN `question` b ON a.`id` = b.`q_id` 
                    LEFT JOIN `options` c ON b.`id` = c.`id` 
                    LEFT JOIN `exam_result` d ON b.`id` = d.`q_no`
                    WHERE d.`examiner_id` = '$userid' AND a.`id` = '$exam_id' AND b.`question_type` = 'Multiple Image'");

            // Essay Query //
            $EssayQuery = $conn->query("SELECT a.`id`, a.`title`,
                    b.`q_id`, b.`points`, b.`question_type`, b.`question`,
                    c.`id` AS option_id, c.`o_id`, c.`option_1`, c.`option_2`, c.`option_3`, c.`option_4`, c.`img_1`, c.`img_2`, c.`img_3`, c.`img_4`, c.`ans`,
                    d.`q_no`, d.`ans` AS result_ans,
                    d.`examiner_id`, d.`correct_incorrect`, d.`ans_date`, d.`ans_time`, d.`points` AS score_points
                    FROM `exam_title` a
                    LEFT JOIN `question` b ON a.`id` = b.`q_id` 
                    LEFT JOIN `options` c ON b.`id` = c.`id` 
                    LEFT JOIN `exam_result` d ON b.`id` = d.`q_no`
                    WHERE d.`examiner_id` = '$userid' AND a.`id` = '$exam_id' AND b.`question_type` = 'Essay'");

            $testQuery = $conn->query("SELECT * FROM exam_test WHERE exam_id = '$exam_id'");
            $testRes = $testQuery->fetch();
            $testI = $testRes['Test_I'];
            $testII = $testRes['Test_II'];
            $testIII = $testRes['Test_III'];
            $testIV = $testRes['Test_IV'];
            $testV = $testRes['Test_V'];

            // Test I Start
            $pdf->SetTextColor(0,0,0);
            $pdf->SetFont('Times','B',13);
            if ($testI != null || $testI != '' || $testI != 0){
                if ($testI == 'Multiple Choice'){
                    $pdf->Cell(15,5, 'Test I:',0,0);
                    $pdf->Cell(177,5, ' Multiple Choice',0,1);
                    $pdf->SetFont('Times','',13);
                    $pdf->Cell(177,5, 'Directions: Choose the BEST answer for the following questions.',0,0);
                    $pdf->Ln(20);
                    while ($MultipleChoice = $MultipleChoiceQuery->fetch()){
                        $ans = $MultipleChoice['result_ans'];
                        $cans = $MultipleChoice['ans'];
                        $pdf->SetMargins(10, 10, 10);

                        //if there is image in question
                        $pdf->SetFont('Times','U',11);
                        if ($ans == $MultipleChoice['option_1']){
                            $pdf->Cell(12,5, '     '.'A'.'     ',0,0, 'C');
                        }else if ($ans == $MultipleChoice['option_2']){
                            $pdf->Cell(12,5, '     '.'B'.'     ',0,0, 'C');
                        }else if ($ans == $MultipleChoice['option_3']){
                            $pdf->Cell(12,5, '     '.'C'.'     ',0,0, 'C');
                        }else if ($ans == $MultipleChoice['option_4']){
                            $pdf->Cell(12,5, '     '.'D'.'     ',0,0, 'C');
                        }
                        if ($cans == $ans){
                            $check = "4";
                            $pdf->SetFont('ZapfDingbats','', 10);
                            $pdf->Cell(6,5, $check,0,0);
                        }else if ($cans != $ans){
                            $cross = "8";
                            $pdf->SetFont('ZapfDingbats','', 10);
                            $pdf->Cell(6,5, $cross,0,0);
                        }
                        $pdf->SetFont('Times','',11);
                        $pdf->Cell(6,5, $index++.'. ',0,0);
                        $pdf->MultiCell(166,5, $MultipleChoice['question'].' ('.$MultipleChoice['points'].' point/s)',0,1);
                        $pdf->Ln(5);
                        $pdf->SetTextColor(105,105,105);

                        if ($MultipleChoice['img_1'] != null && $MultipleChoice['img_2'] != null && $MultipleChoice['img_3'] != null && $MultipleChoice['img_4'] != null){
                            $pdf->Cell(20,25, '',0,0, 'R');
                            $pdf->MultiCell(25, 0, $pdf->Image("../assets/uploads/".$department."/".$user_div."/".$exam_id."/".$testI."/".$MultipleChoice['option_id']."/option1/".$MultipleChoice['img_1'], $pdf->GetX(),$pdf->GetY(),25,25), 0, 0);
                            $pdf->Cell(15,25, '',0,0, 'R');
                            $pdf->MultiCell(25, 0, $pdf->Image("../assets/uploads/".$department."/".$user_div."/".$exam_id."/".$testI."/".$MultipleChoice['option_id']."/option2/".$MultipleChoice['img_2'], $pdf->GetX(),$pdf->GetY(),25,25), 0, 0);
                            $pdf->Cell(15,25, '',0,0, 'R');
                            $pdf->MultiCell(25, 0, $pdf->Image("../assets/uploads/".$department."/".$user_div."/".$exam_id."/".$testI."/".$MultipleChoice['option_id']."/option3/".$MultipleChoice['img_3'], $pdf->GetX(),$pdf->GetY(),25,25), 0, 0);
                            $pdf->Cell(15,25, '',0,0, 'R');
                            $pdf->MultiCell(25, 0, $pdf->Image("../assets/uploads/".$department."/".$user_div."/".$exam_id."/".$testI."/".$MultipleChoice['option_id']."/option4/".$MultipleChoice['img_4'], $pdf->GetX(),$pdf->GetY(),25,25), 0, 0);
                            $pdf->Cell(15,25, '',0,1, 'R');
                            $pdf->Ln(5);
                        }
                        // 4 = null
                        else if ($MultipleChoice['img_1'] != null && $MultipleChoice['img_2'] != null && $MultipleChoice['img_3'] != null && $MultipleChoice['img_4'] == null){
                            $pdf->Cell(30,25, '',0,0 );
                            $pdf->MultiCell(20, 0, $pdf->Image("../assets/uploads/".$department."/".$user_div."/".$exam_id."/".$testI."/".$MultipleChoice['option_id']."/option1/".$MultipleChoice['img_1'], $pdf->GetX(),$pdf->GetY(),25,25), 0, 0);
                            $pdf->Cell(30,25, '',0,0);
                            $pdf->MultiCell(20, 0, $pdf->Image("../assets/uploads/".$department."/".$user_div."/".$exam_id."/".$testI."/".$MultipleChoice['option_id']."/option2/".$MultipleChoice['img_2'], $pdf->GetX(),$pdf->GetY(),25,25), 0, 0);
                            $pdf->Cell(30,25, '',0,0);
                            $pdf->MultiCell(20, 0, $pdf->Image("../assets/uploads/".$department."/".$user_div."/".$exam_id."/".$testI."/".$MultipleChoice['option_id']."/option3/".$MultipleChoice['img_3'], $pdf->GetX(),$pdf->GetY(),25,25), 0, 0);
                            $pdf->Cell(30,25, '',0,0);
                            $pdf->Ln(30);
                        }
                        // 3 = null
                        else if ($MultipleChoice['img_1'] != null && $MultipleChoice['img_2'] != null && $MultipleChoice['img_3'] == null && $MultipleChoice['img_4'] != null){
                            $pdf->Cell(30,25, '',0,0, 'R');
                            $pdf->MultiCell(20, 0, $pdf->Image("../assets/uploads/".$department."/".$user_div."/".$exam_id."/".$testI."/".$MultipleChoice['option_id']."/option1/".$MultipleChoice['img_1'], $pdf->GetX(),$pdf->GetY(),25,25), 0, 0);
                            $pdf->Cell(30,25, '',0,0, 'R');
                            $pdf->MultiCell(20, 0, $pdf->Image("../assets/uploads/".$department."/".$user_div."/".$exam_id."/".$testI."/".$MultipleChoice['option_id']."/option2/".$MultipleChoice['img_2'], $pdf->GetX(),$pdf->GetY(),25,25), 0, 0);
                            $pdf->Cell(30,25, '',0,0, 'R');
                            $pdf->MultiCell(20, 0, $pdf->Image("../assets/uploads/".$department."/".$user_div."/".$exam_id."/".$testI."/".$MultipleChoice['option_id']."/option4/".$MultipleChoice['img_4'], $pdf->GetX(),$pdf->GetY(),25,25), 0, 0);
                            $pdf->Cell(30,25, '',0,0, 'R');
                            $pdf->Ln(30);
                        }
                        // 2 = null
                        else if ($MultipleChoice['img_1'] != null && $MultipleChoice['img_2'] == null && $MultipleChoice['img_3'] != null && $MultipleChoice['img_4'] != null){
                            $pdf->Cell(30,25, '',0,0, 'R');
                            $pdf->MultiCell(20, 0, $pdf->Image("../assets/uploads/".$department."/".$user_div."/".$exam_id."/".$testI."/".$MultipleChoice['option_id']."/option1/".$MultipleChoice['img_1'], $pdf->GetX(),$pdf->GetY(),25,25), 0, 0);
                            $pdf->Cell(30,25, '',0,0, 'R');
                            $pdf->MultiCell(20, 0, $pdf->Image("../assets/uploads/".$department."/".$user_div."/".$exam_id."/".$testI."/".$MultipleChoice['option_id']."/option3/".$MultipleChoice['img_3'], $pdf->GetX(),$pdf->GetY(),25,25), 0, 0);
                            $pdf->Cell(30,25, '',0,0, 'R');
                            $pdf->MultiCell(20, 0, $pdf->Image("../assets/uploads/".$department."/".$user_div."/".$exam_id."/".$testI."/".$MultipleChoice['option_id']."/option4/".$MultipleChoice['img_4'], $pdf->GetX(),$pdf->GetY(),25,25), 0, 0);
                            $pdf->Cell(30,25, '',0,0, 'R');
                            $pdf->Ln(30);
                        }
                        // 1 = null
                        else if ($MultipleChoice['img_1'] == null && $MultipleChoice['img_2'] != null && $MultipleChoice['img_3'] != null && $MultipleChoice['img_4'] != null){
                            $pdf->Cell(30,25, '',0,0, 'R');
                            $pdf->MultiCell(20, 0, $pdf->Image("../assets/uploads/".$department."/".$user_div."/".$exam_id."/".$testI."/".$MultipleChoice['option_id']."/option2/".$MultipleChoice['img_2'], $pdf->GetX(),$pdf->GetY(),25,25), 0, 0);
                            $pdf->Cell(30,25, '',0,0, 'R');
                            $pdf->MultiCell(20, 0, $pdf->Image("../assets/uploads/".$department."/".$user_div."/".$exam_id."/".$testI."/".$MultipleChoice['option_id']."/option3/".$MultipleChoice['img_3'], $pdf->GetX(),$pdf->GetY(),25,25), 0, 0);
                            $pdf->Cell(30,25, '',0,0, 'R');
                            $pdf->MultiCell(20, 0, $pdf->Image("../assets/uploads/".$department."/".$user_div."/".$exam_id."/".$testI."/".$MultipleChoice['option_id']."/option4/".$MultipleChoice['img_4'], $pdf->GetX(),$pdf->GetY(),25,25), 0, 0);
                            $pdf->Cell(30,25, '',0,0, 'R');
                            $pdf->Ln(30);
                        }
                        //  1-2 || 2-1
                        else if ($MultipleChoice['img_1'] != null && $MultipleChoice['img_2'] != null && $MultipleChoice['img_3'] == null && $MultipleChoice['img_4'] == null){
                            $pdf->Cell(40,40, '',0,0, 'R');
                            $pdf->MultiCell(30, 0, $pdf->Image("../assets/uploads/".$department."/".$user_div."/".$exam_id."/".$testI."/".$MultipleChoice['option_id']."/option1/".$MultipleChoice['img_1'], $pdf->GetX(),$pdf->GetY(),0, 40), 0, 0);
                            $pdf->Cell(40,40, '',0,0, 'R');
                            $pdf->MultiCell(30, 0, $pdf->Image("../assets/uploads/".$department."/".$user_div."/".$exam_id."/".$testI."/".$MultipleChoice['option_id']."/option2/".$MultipleChoice['img_2'], $pdf->GetX(),$pdf->GetY(),0, 40), 0, 0);
                            $pdf->Cell(40,40, '',0,0, 'R');
                            $pdf->Ln(45);
                        }
                        //  1-3 || 3-1
                        else if ($MultipleChoice['img_1'] != null && $MultipleChoice['img_2'] == null && $MultipleChoice['img_3'] != null && $MultipleChoice['img_4'] == null){
                            $pdf->Cell(40,40, '',0,0, 'R');
                            $pdf->MultiCell(30, 0, $pdf->Image("../assets/uploads/".$department."/".$user_div."/".$exam_id."/".$testI."/".$MultipleChoice['option_id']."/option1/".$MultipleChoice['img_1'], $pdf->GetX(),$pdf->GetY(),0, 40), 0, 0);
                            $pdf->Cell(40,40, '',0,0, 'R');
                            $pdf->MultiCell(30, 0, $pdf->Image("../assets/uploads/".$department."/".$user_div."/".$exam_id."/".$testI."/".$MultipleChoice['option_id']."/option3/".$MultipleChoice['img_3'], $pdf->GetX(),$pdf->GetY(),0, 40), 0, 0);
                            $pdf->Cell(40,40, '',0,0, 'R');
                            $pdf->Ln(45);
                        }
                        //  1-4 || 4-1
                        else if ($MultipleChoice['img_1'] != null && $MultipleChoice['img_2'] == null && $MultipleChoice['img_3'] == null && $MultipleChoice['img_4'] != null){
                            $pdf->Cell(40,40, '',0,0, 'R');
                            $pdf->MultiCell(30, 0, $pdf->Image("../assets/uploads/".$department."/".$user_div."/".$exam_id."/".$testI."/".$MultipleChoice['option_id']."/option1/".$MultipleChoice['img_1'], $pdf->GetX(),$pdf->GetY(),0, 40), 0, 0);
                            $pdf->Cell(40,40, '',0,0, 'R');
                            $pdf->MultiCell(30, 0, $pdf->Image("../assets/uploads/".$department."/".$user_div."/".$exam_id."/".$testI."/".$MultipleChoice['option_id']."/option4/".$MultipleChoice['img_4'], $pdf->GetX(),$pdf->GetY(),0, 40), 0, 0);
                            $pdf->Cell(40,40, '',0,0, 'R');
                            $pdf->Ln(45);
                        }
                        //  2-3 || 3-2
                        else if ($MultipleChoice['img_1'] == null && $MultipleChoice['img_2'] != null && $MultipleChoice['img_3'] != null && $MultipleChoice['img_4'] == null){
                            $pdf->Cell(40,40, '',0,0, 'R');
                            $pdf->MultiCell(30, 0, $pdf->Image("../assets/uploads/".$department."/".$user_div."/".$exam_id."/".$testI."/".$MultipleChoice['option_id']."/option2/".$MultipleChoice['img_2'], $pdf->GetX(),$pdf->GetY(),0, 40), 0, 0);
                            $pdf->Cell(40,40, '',0,0, 'R');
                            $pdf->MultiCell(30, 0, $pdf->Image("../assets/uploads/".$department."/".$user_div."/".$exam_id."/".$testI."/".$MultipleChoice['option_id']."/option3/".$MultipleChoice['img_3'], $pdf->GetX(),$pdf->GetY(),0, 40), 0, 0);
                            $pdf->Cell(40,40, '',0,0, 'R');
                            $pdf->Ln(45);
                        }
                        //  2-4 || 4-2
                        else if ($MultipleChoice['img_1'] == null && $MultipleChoice['img_2'] != null && $MultipleChoice['img_3'] == null && $MultipleChoice['img_4'] != null){
                            $pdf->Cell(40,40, '',0,0, 'R');
                            $pdf->MultiCell(30, 0, $pdf->Image("../assets/uploads/".$department."/".$user_div."/".$exam_id."/".$testI."/".$MultipleChoice['option_id']."/option2/".$MultipleChoice['img_2'], $pdf->GetX(),$pdf->GetY(),0, 40), 0, 0);
                            $pdf->Cell(40,40, '',0,0, 'R');
                            $pdf->MultiCell(30, 0, $pdf->Image("../assets/uploads/".$department."/".$user_div."/".$exam_id."/".$testI."/".$MultipleChoice['option_id']."/option4/".$MultipleChoice['img_4'], $pdf->GetX(),$pdf->GetY(),0, 40), 0, 0);
                            $pdf->Cell(40,40, '',0,0, 'R');
                            $pdf->Ln(45);
                        }
                        //  3-4 || 4-3
                        else if ($MultipleChoice['img_1'] == null && $MultipleChoice['img_2'] == null && $MultipleChoice['img_3'] != null && $MultipleChoice['img_4'] != null){
                            $pdf->Cell(40,40, '',0,0, 'R');
                            $pdf->MultiCell(30, 0, $pdf->Image("../assets/uploads/".$department."/".$user_div."/".$exam_id."/".$testI."/".$MultipleChoice['option_id']."/option3/".$MultipleChoice['img_3'], $pdf->GetX(),$pdf->GetY(),0, 40), 0, 0);
                            $pdf->Cell(40,40, '',0,0, 'R');
                            $pdf->MultiCell(30, 0, $pdf->Image("../assets/uploads/".$department."/".$user_div."/".$exam_id."/".$testI."/".$MultipleChoice['option_id']."/option4/".$MultipleChoice['img_4'], $pdf->GetX(),$pdf->GetY(),0, 40), 0, 0);
                            $pdf->Cell(40,40, '',0,0, 'R');
                            $pdf->Ln(45);
                        }
                        else if ($MultipleChoice['img_1'] != null && $MultipleChoice['img_2'] == null && $MultipleChoice['img_3'] == null && $MultipleChoice['img_4'] == null){
                            $pdf->Cell(70,40, '',0,0, 'R');
                            $pdf->MultiCell(40, 0, $pdf->Image("../assets/uploads/".$department."/".$user_div."/".$exam_id."/".$testI."/".$MultipleChoice['option_id']."/option1/".$MultipleChoice['img_1'], $pdf->GetX(),$pdf->GetY(),0, 40), 0, 0);
                            $pdf->Cell(80,40, '',0,0, 'R');
                            $pdf->Ln(45);
                        }
                        else if ($MultipleChoice['img_1'] == null && $MultipleChoice['img_2'] != null && $MultipleChoice['img_3'] == null && $MultipleChoice['img_4'] == null){
                            $pdf->Cell(70,0, '',0,0, 'R');
                            $pdf->MultiCell(40, 0, $pdf->Image("../assets/uploads/".$department."/".$user_div."/".$exam_id."/".$testI."/".$MultipleChoice['option_id']."/option2/".$MultipleChoice['img_2'], $pdf->GetX(),$pdf->GetY(),0, 40), 0, 0);
                            $pdf->Cell(80,0, '',0,0, 'R');
                            $pdf->Ln(45);
                        }
                        else if ($MultipleChoice['img_1'] == null && $MultipleChoice['img_2'] == null && $MultipleChoice['img_3'] != null && $MultipleChoice['img_4'] == null){
                            $pdf->Cell(70,40, '',0,0, 'R');
                            $pdf->MultiCell(40, 0, $pdf->Image("../assets/uploads/".$department."/".$user_div."/".$exam_id."/".$testI."/".$MultipleChoice['option_id']."/option3/".$MultipleChoice['img_3'], $pdf->GetX(),$pdf->GetY(),0, 40), 0, 0);
                            $pdf->Cell(80,40, '',0,0, 'R');
                            $pdf->Ln(45);
                        }
                        else if ($MultipleChoice['img_1'] == null && $MultipleChoice['img_2'] == null && $MultipleChoice['img_3'] == null && $MultipleChoice['img_4'] != null){
                            $pdf->Cell(70,40, '',0,0, 'R');
                            $pdf->MultiCell(40, 0, $pdf->Image("../assets/uploads/".$department."/".$user_div."/".$exam_id."/".$testI."/".$MultipleChoice['option_id']."/option4/".$MultipleChoice['img_4'], $pdf->GetX(),$pdf->GetY(),0, 40), 0, 0);
                            $pdf->Cell(80,40, '',0,0, 'R');
                            $pdf->Ln(45);
                        }
                        $pdf->Cell(24,5, 'A.',0,0, 'R');
                        $pdf->MultiCell(76,5, $MultipleChoice['option_1'],0,0);
                        $pdf->Cell(7,5, 'C.',0,0, 'R');
                        $pdf->MultiCell(76, 5, $MultipleChoice['option_3'],0,1);
                        $pdf->Ln(2);
                        $pdf->Cell(24,5, 'B.',0,0, 'R');
                        $pdf->MultiCell(76, 5, $MultipleChoice['option_2'],0,0);
                        $pdf->Cell(7,5, 'D.',0,0, 'R');
                        $pdf->MultiCell(76, 5, $MultipleChoice['option_4'],0,1);
                        $pdf->Ln(5);
                        $pdf->SetTextColor(0,0,0);
                    }
                }
                else if ($testI == 'Short Answer'){
                    $pdf->Cell(15,5, 'Test I:',0,0);
                    $pdf->Cell(177,5, ' Short Answer',0,1);
                    $pdf->SetFont('Times','',13);
                    $pdf->MultiCell(177,5, 'Directions: Using your own words, answer each question in the space provided',0,0);
                    $pdf->Ln(20);

                    while ($ShortAnswer = $ShortAnswerQuery->fetch()){
                        $ans = $ShortAnswer['correct_incorrect'];
                        if ($ans == 'correct'){
                            $check = "4";
                            $pdf->SetFont('ZapfDingbats','', 10);
                            $pdf->Cell(5,5, $check,0,0);

                        }else if ($ans == 'incorrect'){
                            $cross = "8";
                            $pdf->SetFont('ZapfDingbats','', 10);
                            $pdf->Cell(5,5, $cross,0,0);

                        }
                        $pdf->SetFont('Times','',11);
                        $pdf->Cell(6,5, $index++.'. ',0,0);
                        $pdf->MultiCell(166,5, $ShortAnswer['question'].' ('.$ShortAnswer['points'].' point/s)',0,1);
                        $pdf->Ln(5);
                        $pdf->Cell(11,5, '',0,0);
                        $pdf->SetFont('Times','U',11);
                        $pdf->MultiCell(180,5, '  '.$ShortAnswer['result_ans'].'                          ',0,1);
                        $pdf->Ln(5);
                    }
                }
                else if ($testI == 'True/False'){
                    $pdf->Cell(15,5, 'Test I:',0,0);
                    $pdf->Cell(177,5, ' True or False',0,1);
                    $pdf->SetFont('Times','',13);
                    $pdf->MultiCell(177,5, 'Directions: Read the statements carefully and tell whether the statement is TRUE or FALSE. Choose TRUE if the statement is correct and FALSE if otherwise.',0,0);
                    $pdf->Ln(20);
                    while ($TrueFalse = $TrueFalseQuery->fetch()){
                        $ans = $TrueFalse['result_ans'];
                        $cans = $TrueFalse['ans'];
                        $pdf->SetMargins(10, 10, 10);
                        $pdf->SetFont('Times','U',11);
                        $pdf->Cell(12,5, '  '. $ans .'  ',0,0, 'C');
                        if ($cans == $ans){
                            $check = "4";
                            $pdf->SetFont('ZapfDingbats','', 10);
                            $pdf->Cell(6,5, $check,0,0);
                        }else if ($cans != $ans){
                            $cross = "8";
                            $pdf->SetFont('ZapfDingbats','', 10);
                            $pdf->Cell(6,5, $cross,0,0);
                        }
                        $pdf->SetFont('Times','',11);
                        $pdf->Cell(6,5, $index++.'. ',0,0);
                        $pdf->MultiCell(166,5, $TrueFalse['question'].' ('.$TrueFalse['points'].' point/s)', 0, 1);
                        $pdf->Ln(5);

                        if ($TrueFalse['img_1'] != null && $TrueFalse['img_2'] != null && $TrueFalse['img_3'] != null && $TrueFalse['img_4'] != null){
                            $pdf->Cell(20,25, '',0,0, 'R');
                            $pdf->MultiCell(25, 0, $pdf->Image("../assets/uploads/".$department."/".$user_div."/".$exam_id."/".$testI."/".$TrueFalse['option_id']."/option1/".$TrueFalse['img_1'], $pdf->GetX(),$pdf->GetY(),25,25), 0, 0);
                            $pdf->Cell(15,25, '',0,0, 'R');
                            $pdf->MultiCell(25, 0, $pdf->Image("../assets/uploads/".$department."/".$user_div."/".$exam_id."/".$testI."/".$TrueFalse['option_id']."/option2/".$TrueFalse['img_2'], $pdf->GetX(),$pdf->GetY(),25,25), 0, 0);
                            $pdf->Cell(15,25, '',0,0, 'R');
                            $pdf->MultiCell(25, 0, $pdf->Image("../assets/uploads/".$department."/".$user_div."/".$exam_id."/".$testI."/".$TrueFalse['option_id']."/option3/".$TrueFalse['img_3'], $pdf->GetX(),$pdf->GetY(),25,25), 0, 0);
                            $pdf->Cell(15,25, '',0,0, 'R');
                            $pdf->MultiCell(25, 0, $pdf->Image("../assets/uploads/".$department."/".$user_div."/".$exam_id."/".$testI."/".$TrueFalse['option_id']."/option4/".$TrueFalse['img_4'], $pdf->GetX(),$pdf->GetY(),25,25), 0, 0);
                            $pdf->Cell(15,25, '',0,1, 'R');
                            $pdf->Ln(5);
                        }
                        // 4 = null
                        else if ($TrueFalse['img_1'] != null && $TrueFalse['img_2'] != null && $TrueFalse['img_3'] != null && $TrueFalse['img_4'] == null){
                            $pdf->Cell(30,25, '',0,0 );
                            $pdf->MultiCell(20, 0, $pdf->Image("../assets/uploads/".$department."/".$user_div."/".$exam_id."/".$testI."/".$TrueFalse['option_id']."/option1/".$TrueFalse['img_1'], $pdf->GetX(),$pdf->GetY(),25,25), 0, 0);
                            $pdf->Cell(30,25, '',0,0);
                            $pdf->MultiCell(20, 0, $pdf->Image("../assets/uploads/".$department."/".$user_div."/".$exam_id."/".$testI."/".$TrueFalse['option_id']."/option2/".$TrueFalse['img_2'], $pdf->GetX(),$pdf->GetY(),25,25), 0, 0);
                            $pdf->Cell(30,25, '',0,0);
                            $pdf->MultiCell(20, 0, $pdf->Image("../assets/uploads/".$department."/".$user_div."/".$exam_id."/".$testI."/".$TrueFalse['option_id']."/option3/".$TrueFalse['img_3'], $pdf->GetX(),$pdf->GetY(),25,25), 0, 0);
                            $pdf->Cell(30,25, '',0,0);
                            $pdf->Ln(30);
                        }
                        // 3 = null
                        else if ($TrueFalse['img_1'] != null && $TrueFalse['img_2'] != null && $TrueFalse['img_3'] == null && $TrueFalse['img_4'] != null){
                            $pdf->Cell(30,25, '',0,0, 'R');
                            $pdf->MultiCell(20, 0, $pdf->Image("../assets/uploads/".$department."/".$user_div."/".$exam_id."/".$testI."/".$TrueFalse['option_id']."/option1/".$TrueFalse['img_1'], $pdf->GetX(),$pdf->GetY(),25,25), 0, 0);
                            $pdf->Cell(30,25, '',0,0, 'R');
                            $pdf->MultiCell(20, 0, $pdf->Image("../assets/uploads/".$department."/".$user_div."/".$exam_id."/".$testI."/".$TrueFalse['option_id']."/option2/".$TrueFalse['img_2'], $pdf->GetX(),$pdf->GetY(),25,25), 0, 0);
                            $pdf->Cell(30,25, '',0,0, 'R');
                            $pdf->MultiCell(20, 0, $pdf->Image("../assets/uploads/".$department."/".$user_div."/".$exam_id."/".$testI."/".$TrueFalse['option_id']."/option4/".$TrueFalse['img_4'], $pdf->GetX(),$pdf->GetY(),25,25), 0, 0);
                            $pdf->Cell(30,25, '',0,0, 'R');
                            $pdf->Ln(30);
                        }
                        // 2 = null
                        else if ($TrueFalse['img_1'] != null && $TrueFalse['img_2'] == null && $TrueFalse['img_3'] != null && $TrueFalse['img_4'] != null){
                            $pdf->Cell(30,25, '',0,0, 'R');
                            $pdf->MultiCell(20, 0, $pdf->Image("../assets/uploads/".$department."/".$user_div."/".$exam_id."/".$testI."/".$TrueFalse['option_id']."/option1/".$TrueFalse['img_1'], $pdf->GetX(),$pdf->GetY(),25,25), 0, 0);
                            $pdf->Cell(30,25, '',0,0, 'R');
                            $pdf->MultiCell(20, 0, $pdf->Image("../assets/uploads/".$department."/".$user_div."/".$exam_id."/".$testI."/".$TrueFalse['option_id']."/option3/".$TrueFalse['img_3'], $pdf->GetX(),$pdf->GetY(),25,25), 0, 0);
                            $pdf->Cell(30,25, '',0,0, 'R');
                            $pdf->MultiCell(20, 0, $pdf->Image("../assets/uploads/".$department."/".$user_div."/".$exam_id."/".$testI."/".$TrueFalse['option_id']."/option4/".$TrueFalse['img_4'], $pdf->GetX(),$pdf->GetY(),25,25), 0, 0);
                            $pdf->Cell(30,25, '',0,0, 'R');
                            $pdf->Ln(30);
                        }
                        // 1 = null
                        else if ($TrueFalse['img_1'] == null && $TrueFalse['img_2'] != null && $TrueFalse['img_3'] != null && $TrueFalse['img_4'] != null){
                            $pdf->Cell(30,25, '',0,0, 'R');
                            $pdf->MultiCell(20, 0, $pdf->Image("../assets/uploads/".$department."/".$user_div."/".$exam_id."/".$testI."/".$TrueFalse['option_id']."/option2/".$TrueFalse['img_2'], $pdf->GetX(),$pdf->GetY(),25,25), 0, 0);
                            $pdf->Cell(30,25, '',0,0, 'R');
                            $pdf->MultiCell(20, 0, $pdf->Image("../assets/uploads/".$department."/".$user_div."/".$exam_id."/".$testI."/".$TrueFalse['option_id']."/option3/".$TrueFalse['img_3'], $pdf->GetX(),$pdf->GetY(),25,25), 0, 0);
                            $pdf->Cell(30,25, '',0,0, 'R');
                            $pdf->MultiCell(20, 0, $pdf->Image("../assets/uploads/".$department."/".$user_div."/".$exam_id."/".$testI."/".$TrueFalse['option_id']."/option4/".$TrueFalse['img_4'], $pdf->GetX(),$pdf->GetY(),25,25), 0, 0);
                            $pdf->Cell(30,25, '',0,0, 'R');
                            $pdf->Ln(30);
                        }
                        //  1-2 || 2-1
                        else if ($TrueFalse['img_1'] != null && $TrueFalse['img_2'] != null && $TrueFalse['img_3'] == null && $TrueFalse['img_4'] == null){
                            $pdf->Cell(40,40, '',0,0, 'R');
                            $pdf->MultiCell(30, 0, $pdf->Image("../assets/uploads/".$department."/".$user_div."/".$exam_id."/".$testI."/".$TrueFalse['option_id']."/option1/".$TrueFalse['img_1'], $pdf->GetX(),$pdf->GetY(),0, 40), 0, 0);
                            $pdf->Cell(40,40, '',0,0, 'R');
                            $pdf->MultiCell(30, 0, $pdf->Image("../assets/uploads/".$department."/".$user_div."/".$exam_id."/".$testI."/".$TrueFalse['option_id']."/option2/".$TrueFalse['img_2'], $pdf->GetX(),$pdf->GetY(),0, 40), 0, 0);
                            $pdf->Cell(40,40, '',0,0, 'R');
                            $pdf->Ln(45);
                        }
                        //  1-3 || 3-1
                        else if ($TrueFalse['img_1'] != null && $TrueFalse['img_2'] == null && $TrueFalse['img_3'] != null && $TrueFalse['img_4'] == null){
                            $pdf->Cell(40,40, '',0,0, 'R');
                            $pdf->MultiCell(30, 0, $pdf->Image("../assets/uploads/".$department."/".$user_div."/".$exam_id."/".$testI."/".$TrueFalse['option_id']."/option1/".$TrueFalse['img_1'], $pdf->GetX(),$pdf->GetY(),0, 40), 0, 0);
                            $pdf->Cell(40,40, '',0,0, 'R');
                            $pdf->MultiCell(30, 0, $pdf->Image("../assets/uploads/".$department."/".$user_div."/".$exam_id."/".$testI."/".$TrueFalse['option_id']."/option3/".$TrueFalse['img_3'], $pdf->GetX(),$pdf->GetY(),0, 40), 0, 0);
                            $pdf->Cell(40,40, '',0,0, 'R');
                            $pdf->Ln(45);
                        }
                        //  1-4 || 4-1
                        else if ($TrueFalse['img_1'] != null && $TrueFalse['img_2'] == null && $TrueFalse['img_3'] == null && $TrueFalse['img_4'] != null){
                            $pdf->Cell(40,40, '',0,0, 'R');
                            $pdf->MultiCell(30, 0, $pdf->Image("../assets/uploads/".$department."/".$user_div."/".$exam_id."/".$testI."/".$TrueFalse['option_id']."/option1/".$TrueFalse['img_1'], $pdf->GetX(),$pdf->GetY(),0, 40), 0, 0);
                            $pdf->Cell(40,40, '',0,0, 'R');
                            $pdf->MultiCell(30, 0, $pdf->Image("../assets/uploads/".$department."/".$user_div."/".$exam_id."/".$testI."/".$TrueFalse['option_id']."/option4/".$TrueFalse['img_4'], $pdf->GetX(),$pdf->GetY(),0, 40), 0, 0);
                            $pdf->Cell(40,40, '',0,0, 'R');
                            $pdf->Ln(45);
                        }
                        //  2-3 || 3-2
                        else if ($TrueFalse['img_1'] == null && $TrueFalse['img_2'] != null && $TrueFalse['img_3'] != null && $TrueFalse['img_4'] == null){
                            $pdf->Cell(40,40, '',0,0, 'R');
                            $pdf->MultiCell(30, 0, $pdf->Image("../assets/uploads/".$department."/".$user_div."/".$exam_id."/".$testI."/".$TrueFalse['option_id']."/option2/".$TrueFalse['img_2'], $pdf->GetX(),$pdf->GetY(),0, 40), 0, 0);
                            $pdf->Cell(40,40, '',0,0, 'R');
                            $pdf->MultiCell(30, 0, $pdf->Image("../assets/uploads/".$department."/".$user_div."/".$exam_id."/".$testI."/".$TrueFalse['option_id']."/option3/".$TrueFalse['img_3'], $pdf->GetX(),$pdf->GetY(),0, 40), 0, 0);
                            $pdf->Cell(40,40, '',0,0, 'R');
                            $pdf->Ln(45);
                        }
                        //  2-4 || 4-2
                        else if ($TrueFalse['img_1'] == null && $TrueFalse['img_2'] != null && $TrueFalse['img_3'] == null && $TrueFalse['img_4'] != null){
                            $pdf->Cell(40,40, '',0,0, 'R');
                            $pdf->MultiCell(30, 0, $pdf->Image("../assets/uploads/".$department."/".$user_div."/".$exam_id."/".$testI."/".$TrueFalse['option_id']."/option2/".$TrueFalse['img_2'], $pdf->GetX(),$pdf->GetY(),0, 40), 0, 0);
                            $pdf->Cell(40,40, '',0,0, 'R');
                            $pdf->MultiCell(30, 0, $pdf->Image("../assets/uploads/".$department."/".$user_div."/".$exam_id."/".$testI."/".$TrueFalse['option_id']."/option4/".$TrueFalse['img_4'], $pdf->GetX(),$pdf->GetY(),0, 40), 0, 0);
                            $pdf->Cell(40,40, '',0,0, 'R');
                            $pdf->Ln(45);
                        }
                        //  3-4 || 4-3
                        else if ($TrueFalse['img_1'] == null && $TrueFalse['img_2'] == null && $TrueFalse['img_3'] != null && $TrueFalse['img_4'] != null){
                            $pdf->Cell(40,40, '',0,0, 'R');
                            $pdf->MultiCell(30, 0, $pdf->Image("../assets/uploads/".$department."/".$user_div."/".$exam_id."/".$testI."/".$TrueFalse['option_id']."/option3/".$TrueFalse['img_3'], $pdf->GetX(),$pdf->GetY(),0, 40), 0, 0);
                            $pdf->Cell(40,40, '',0,0, 'R');
                            $pdf->MultiCell(30, 0, $pdf->Image("../assets/uploads/".$department."/".$user_div."/".$exam_id."/".$testI."/".$TrueFalse['option_id']."/option4/".$TrueFalse['img_4'], $pdf->GetX(),$pdf->GetY(),0, 40), 0, 0);
                            $pdf->Cell(40,40, '',0,0, 'R');
                            $pdf->Ln(45);
                        }
                        else if ($TrueFalse['img_1'] != null && $TrueFalse['img_2'] == null && $TrueFalse['img_3'] == null && $TrueFalse['img_4'] == null){
                            $pdf->Cell(70,40, '',0,0, 'R');
                            $pdf->MultiCell(40, 0, $pdf->Image("../assets/uploads/".$department."/".$user_div."/".$exam_id."/".$testI."/".$TrueFalse['option_id']."/option1/".$TrueFalse['img_1'], $pdf->GetX(),$pdf->GetY(),0, 40), 0, 0);
                            $pdf->Cell(80,40, '',0,0, 'R');
                            $pdf->Ln(45);
                        }
                        else if ($TrueFalse['img_1'] == null && $TrueFalse['img_2'] != null && $TrueFalse['img_3'] == null && $TrueFalse['img_4'] == null){
                            $pdf->Cell(70,0, '',0,0, 'R');
                            $pdf->MultiCell(40, 0, $pdf->Image("../assets/uploads/".$department."/".$user_div."/".$exam_id."/".$testI."/".$TrueFalse['option_id']."/option2/".$TrueFalse['img_2'], $pdf->GetX(),$pdf->GetY(),0, 40), 0, 0);
                            $pdf->Cell(80,0, '',0,0, 'R');
                            $pdf->Ln(45);
                        }
                        else if ($TrueFalse['img_1'] == null && $TrueFalse['img_2'] == null && $TrueFalse['img_3'] != null && $TrueFalse['img_4'] == null){
                            $pdf->Cell(70,40, '',0,0, 'R');
                            $pdf->MultiCell(40, 0, $pdf->Image("../assets/uploads/".$department."/".$user_div."/".$exam_id."/".$testI."/".$TrueFalse['option_id']."/option3/".$TrueFalse['img_3'], $pdf->GetX(),$pdf->GetY(),0, 40), 0, 0);
                            $pdf->Cell(80,40, '',0,0, 'R');
                            $pdf->Ln(45);
                        }
                        else if ($TrueFalse['img_1'] == null && $TrueFalse['img_2'] == null && $TrueFalse['img_3'] == null && $TrueFalse['img_4'] != null){
                            $pdf->Cell(70,40, '',0,0, 'R');
                            $pdf->MultiCell(40, 0, $pdf->Image("../assets/uploads/".$department."/".$user_div."/".$exam_id."/".$testI."/".$TrueFalse['option_id']."/option4/".$TrueFalse['img_4'], $pdf->GetX(),$pdf->GetY(),0, 40), 0, 0);
                            $pdf->Cell(80,40, '',0,0, 'R');
                            $pdf->Ln(45);
                        }
                    }
                }
                else if ($testI == 'Multiple Image'){
                    $pdf->Cell(15,5, 'Test I:',0,0);
                    $pdf->Cell(177,5, ' Multiple Choice (Images)',0,1);
                    $pdf->SetFont('Times','',13);
                    $pdf->MultiCell(177,5, 'Directions: Choose the BEST IMAGE answer for the following questions, and write your answer in the blank.',0,0);
                    $pdf->Ln(20);
                    while ($MultipleImage = $MultipleImageQuery->fetch()) {
                        $ans = $MultipleImage['result_ans'];
                        $pdf->SetMargins(10, 10, 10);

                        $pdf->SetTextColor(0,0,0);
                        $pdf->SetFont('Times','U',11);
                        if ($ans == 'I'){
                            $pdf->Cell(12,5, ' A ',0,0, 'C');
                        }else if ($ans == 'II'){
                            $pdf->Cell(12,5, ' B ',0,0, 'C');
                        }else if ($ans == 'III'){
                            $pdf->Cell(12,5, ' C ',0,0, 'C');
                        }else if ($ans == 'IV'){
                            $pdf->Cell(12,5, ' D  ',0,0, 'C');
                        }
                        $ans1 = $MultipleImage['correct_incorrect'];
                        if ($ans1 == 'correct'){
                            $check = "4";
                            $pdf->SetFont('ZapfDingbats','', 10);
                            $pdf->Cell(5,5, '       '.$check,0,0, 'R');

                        }else if ($ans1 == 'incorrect'){
                            $cross = "8";
                            $pdf->SetFont('ZapfDingbats','', 10);
                            $pdf->Cell(5,5, '       '.$cross,0,0, 'R');

                        }
                        $pdf->SetFont('Times','',11);
                        $pdf->Cell(6,5, $index++.'. ',0,0);
                        $pdf->MultiCell(166,5, $MultipleImage['question'].' ('.$MultipleImage['points'].' point/s)',0,1);
                        $pdf->Ln(5);
                        $pdf->SetTextColor(105,105,105);
                        $pdf->Cell(25,25, 'A: ',0,0, 'R');
                        $pdf->MultiCell(30, 0, $pdf->Image("../assets/uploads/".$department."/".$user_div."/".$exam_id."/Multiple Image/".$MultipleImage['option_id']."/option1/".$MultipleImage['img_1'], $pdf->GetX(),$pdf->GetY(),25,25), 0, 0);
                        $pdf->Cell(15,25, 'B: ',0,0, 'R');
                        $pdf->MultiCell(25, 0, $pdf->Image("../assets/uploads/".$department."/".$user_div."/".$exam_id."/Multiple Image/".$MultipleImage['option_id']."/option2/".$MultipleImage['img_2'], $pdf->GetX(),$pdf->GetY(),25,25), 0, 0);
                        $pdf->Cell(15,25, 'C: ',0,0, 'R');
                        $pdf->MultiCell(25, 0, $pdf->Image("../assets/uploads/".$department."/".$user_div."/".$exam_id."/Multiple Image/".$MultipleImage['option_id']."/option3/".$MultipleImage['img_3'], $pdf->GetX(),$pdf->GetY(),25,25), 0, 0);
                        $pdf->Cell(15,25, 'D: ',0,0, 'R');
                        $pdf->MultiCell(25, 0, $pdf->Image("../assets/uploads/".$department."/".$user_div."/".$exam_id."/Multiple Image/".$MultipleImage['option_id']."/option4/".$MultipleImage['img_4'], $pdf->GetX(),$pdf->GetY(),25,25), 0, 0);
                        $pdf->Ln(10);
                        $pdf->Ln(20);
                        $pdf->Ln(20);

                    }

                }
                else if ($testI == 'Essay') {
                    $pdf->Cell(15,5, 'Test I:',0,0);
                    $pdf->Cell(177, 5, ' Essay', 0, 1);
                    $pdf->SetFont('Times', '', 13);
                    $pdf->MultiCell(177, 5, 'Directions: Answer the question to the best of your knowledge. Write your answer in the space provided', 0, 0);
                    $pdf->Ln(20);
                    while ($Essay = $EssayQuery->fetch()) {
                        $ans = $Essay['correct_incorrect'];
                        if ($ans == 'correct'){
                            $check = "4";
                            $pdf->SetFont('Times', '', 11);
                            $pdf->Cell(15,5, $Essay['score_points'].' Points',0,0);
                            $pdf->SetFont('ZapfDingbats','', 10);
                            $pdf->Cell(5,5, $check,0,0);

                        }else if ($ans == 'incorrect'){
                            $cross = "8";
                            $pdf->SetFont('Times', '', 11);
                            $pdf->Cell(15,5, $Essay['score_points'].' Points',0,0);
                            $pdf->SetFont('ZapfDingbats','', 10);
                            $pdf->Cell(5,5, $cross,0,0);

                        }
                        $pdf->SetFont('Times', '', 11);
                        $pdf->Cell(6, 5, $index++ . '. ', 0, 0);
                        $pdf->MultiCell(160, 5, $Essay['question'].' ('.$Essay['points'].' point/s)', 0, 1);
                        $pdf->Ln(5);
                        $pdf->Cell(20, 5, '', 0, 0);
                        $pdf->SetFont('Times', 'U', 11);
                        $pdf->MultiCell(166, 5, '           '. $Essay['result_ans'] . '              ', 0, 1);
                        $pdf->Ln(5);
                    }
                }
                $pdf->Ln(10);
            }
            // Test I End

            // Test II Start
            // $pdf->AddPage();
            $pdf->SetTextColor(0,0,0);
            $pdf->SetFont('Times','B',13);
            if ($testII != null || $testII != '' || $testII != 0){
                if ($testII == 'Multiple Choice'){
                    $pdf->Cell(15,5, 'Test II:',0,0);
                    $pdf->Cell(177,5, ' Multiple Choice',0,1);
                    $pdf->SetFont('Times','',13);
                    $pdf->Cell(177,5, 'Directions: Choose the BEST answer for the following questions.',0,0);
                    $pdf->Ln(20);
                    while ($MultipleChoice = $MultipleChoiceQuery->fetch()){
                        $ans = $MultipleChoice['result_ans'];
                        $cans = $MultipleChoice['ans'];
                        $pdf->SetMargins(10, 10, 10);

                        $pdf->SetFont('Times','U',11);
                        if ($ans == $MultipleChoice['option_1']){
                            $pdf->Cell(12,5, '     '.'A'.'     ',0,0, 'C');
                        }else if ($ans == $MultipleChoice['option_2']){
                            $pdf->Cell(12,5, '     '.'B'.'     ',0,0, 'C');
                        }else if ($ans == $MultipleChoice['option_3']){
                            $pdf->Cell(12,5, '     '.'C'.'     ',0,0, 'C');
                        }else if ($ans == $MultipleChoice['option_4']){
                            $pdf->Cell(12,5, '     '.'D'.'     ',0,0, 'C');
                        }
                        if ($cans == $ans){
                            $check = "4";
                            $pdf->SetFont('ZapfDingbats','', 10);
                            $pdf->Cell(6,5, $check,0,0);
                        }else if ($cans != $ans){
                            $cross = "8";
                            $pdf->SetFont('ZapfDingbats','', 10);
                            $pdf->Cell(6,5, $cross,0,0);
                        }
                        $pdf->SetFont('Times','',11);
                        $pdf->Cell(6,5, $index++.'. ',0,0);
                        $pdf->MultiCell(166,5, $MultipleChoice['question'].' ('.$MultipleChoice['points'].' point/s)',0,1);
                        $pdf->Ln(5);
                        $pdf->SetTextColor(105,105,105);
                        if ($MultipleChoice['img_1'] != null && $MultipleChoice['img_2'] != null && $MultipleChoice['img_3'] != null && $MultipleChoice['img_4'] != null){
                            $pdf->Cell(20,25, '',0,0, 'R');
                            $pdf->MultiCell(25, 0, $pdf->Image("../assets/uploads/".$department."/".$user_div."/".$exam_id."/".$testII."/".$MultipleChoice['option_id']."/option1/".$MultipleChoice['img_1'], $pdf->GetX(),$pdf->GetY(),25,25), 0, 0);
                            $pdf->Cell(15,25, '',0,0, 'R');
                            $pdf->MultiCell(25, 0, $pdf->Image("../assets/uploads/".$department."/".$user_div."/".$exam_id."/".$testII."/".$MultipleChoice['option_id']."/option2/".$MultipleChoice['img_2'], $pdf->GetX(),$pdf->GetY(),25,25), 0, 0);
                            $pdf->Cell(15,25, '',0,0, 'R');
                            $pdf->MultiCell(25, 0, $pdf->Image("../assets/uploads/".$department."/".$user_div."/".$exam_id."/".$testII."/".$MultipleChoice['option_id']."/option3/".$MultipleChoice['img_3'], $pdf->GetX(),$pdf->GetY(),25,25), 0, 0);
                            $pdf->Cell(15,25, '',0,0, 'R');
                            $pdf->MultiCell(25, 0, $pdf->Image("../assets/uploads/".$department."/".$user_div."/".$exam_id."/".$testII."/".$MultipleChoice['option_id']."/option4/".$MultipleChoice['img_4'], $pdf->GetX(),$pdf->GetY(),25,25), 0, 0);
                            $pdf->Cell(15,25, '',0,1, 'R');
                            $pdf->Ln(5);
                        }
                        // 4 = null
                        else if ($MultipleChoice['img_1'] != null && $MultipleChoice['img_2'] != null && $MultipleChoice['img_3'] != null && $MultipleChoice['img_4'] == null){
                            $pdf->Cell(30,25, '',0,0 );
                            $pdf->MultiCell(20, 0, $pdf->Image("../assets/uploads/".$department."/".$user_div."/".$exam_id."/".$testII."/".$MultipleChoice['option_id']."/option1/".$MultipleChoice['img_1'], $pdf->GetX(),$pdf->GetY(),25,25), 0, 0);
                            $pdf->Cell(30,25, '',0,0);
                            $pdf->MultiCell(20, 0, $pdf->Image("../assets/uploads/".$department."/".$user_div."/".$exam_id."/".$testII."/".$MultipleChoice['option_id']."/option2/".$MultipleChoice['img_2'], $pdf->GetX(),$pdf->GetY(),25,25), 0, 0);
                            $pdf->Cell(30,25, '',0,0);
                            $pdf->MultiCell(20, 0, $pdf->Image("../assets/uploads/".$department."/".$user_div."/".$exam_id."/".$testII."/".$MultipleChoice['option_id']."/option3/".$MultipleChoice['img_3'], $pdf->GetX(),$pdf->GetY(),25,25), 0, 0);
                            $pdf->Cell(30,25, '',0,0);
                            $pdf->Ln(30);
                        }
                        // 3 = null
                        else if ($MultipleChoice['img_1'] != null && $MultipleChoice['img_2'] != null && $MultipleChoice['img_3'] == null && $MultipleChoice['img_4'] != null){
                            $pdf->Cell(30,25, '',0,0, 'R');
                            $pdf->MultiCell(20, 0, $pdf->Image("../assets/uploads/".$department."/".$user_div."/".$exam_id."/".$testII."/".$MultipleChoice['option_id']."/option1/".$MultipleChoice['img_1'], $pdf->GetX(),$pdf->GetY(),25,25), 0, 0);
                            $pdf->Cell(30,25, '',0,0, 'R');
                            $pdf->MultiCell(20, 0, $pdf->Image("../assets/uploads/".$department."/".$user_div."/".$exam_id."/".$testII."/".$MultipleChoice['option_id']."/option2/".$MultipleChoice['img_2'], $pdf->GetX(),$pdf->GetY(),25,25), 0, 0);
                            $pdf->Cell(30,25, '',0,0, 'R');
                            $pdf->MultiCell(20, 0, $pdf->Image("../assets/uploads/".$department."/".$user_div."/".$exam_id."/".$testII."/".$MultipleChoice['option_id']."/option4/".$MultipleChoice['img_4'], $pdf->GetX(),$pdf->GetY(),25,25), 0, 0);
                            $pdf->Cell(30,25, '',0,0, 'R');
                            $pdf->Ln(30);
                        }
                        // 2 = null
                        else if ($MultipleChoice['img_1'] != null && $MultipleChoice['img_2'] == null && $MultipleChoice['img_3'] != null && $MultipleChoice['img_4'] != null){
                            $pdf->Cell(30,25, '',0,0, 'R');
                            $pdf->MultiCell(20, 0, $pdf->Image("../assets/uploads/".$department."/".$user_div."/".$exam_id."/".$testII."/".$MultipleChoice['option_id']."/option1/".$MultipleChoice['img_1'], $pdf->GetX(),$pdf->GetY(),25,25), 0, 0);
                            $pdf->Cell(30,25, '',0,0, 'R');
                            $pdf->MultiCell(20, 0, $pdf->Image("../assets/uploads/".$department."/".$user_div."/".$exam_id."/".$testII."/".$MultipleChoice['option_id']."/option3/".$MultipleChoice['img_3'], $pdf->GetX(),$pdf->GetY(),25,25), 0, 0);
                            $pdf->Cell(30,25, '',0,0, 'R');
                            $pdf->MultiCell(20, 0, $pdf->Image("../assets/uploads/".$department."/".$user_div."/".$exam_id."/".$testII."/".$MultipleChoice['option_id']."/option4/".$MultipleChoice['img_4'], $pdf->GetX(),$pdf->GetY(),25,25), 0, 0);
                            $pdf->Cell(30,25, '',0,0, 'R');
                            $pdf->Ln(30);
                        }
                        // 1 = null
                        else if ($MultipleChoice['img_1'] == null && $MultipleChoice['img_2'] != null && $MultipleChoice['img_3'] != null && $MultipleChoice['img_4'] != null){
                            $pdf->Cell(30,25, '',0,0, 'R');
                            $pdf->MultiCell(20, 0, $pdf->Image("../assets/uploads/".$department."/".$user_div."/".$exam_id."/".$testII."/".$MultipleChoice['option_id']."/option2/".$MultipleChoice['img_2'], $pdf->GetX(),$pdf->GetY(),25,25), 0, 0);
                            $pdf->Cell(30,25, '',0,0, 'R');
                            $pdf->MultiCell(20, 0, $pdf->Image("../assets/uploads/".$department."/".$user_div."/".$exam_id."/".$testII."/".$MultipleChoice['option_id']."/option3/".$MultipleChoice['img_3'], $pdf->GetX(),$pdf->GetY(),25,25), 0, 0);
                            $pdf->Cell(30,25, '',0,0, 'R');
                            $pdf->MultiCell(20, 0, $pdf->Image("../assets/uploads/".$department."/".$user_div."/".$exam_id."/".$testII."/".$MultipleChoice['option_id']."/option4/".$MultipleChoice['img_4'], $pdf->GetX(),$pdf->GetY(),25,25), 0, 0);
                            $pdf->Cell(30,25, '',0,0, 'R');
                            $pdf->Ln(30);
                        }
                        //  1-2 || 2-1
                        else if ($MultipleChoice['img_1'] != null && $MultipleChoice['img_2'] != null && $MultipleChoice['img_3'] == null && $MultipleChoice['img_4'] == null){
                            $pdf->Cell(40,40, '',0,0, 'R');
                            $pdf->MultiCell(30, 0, $pdf->Image("../assets/uploads/".$department."/".$user_div."/".$exam_id."/".$testII."/".$MultipleChoice['option_id']."/option1/".$MultipleChoice['img_1'], $pdf->GetX(),$pdf->GetY(),0, 40), 0, 0);
                            $pdf->Cell(40,40, '',0,0, 'R');
                            $pdf->MultiCell(30, 0, $pdf->Image("../assets/uploads/".$department."/".$user_div."/".$exam_id."/".$testII."/".$MultipleChoice['option_id']."/option2/".$MultipleChoice['img_2'], $pdf->GetX(),$pdf->GetY(),0, 40), 0, 0);
                            $pdf->Cell(40,40, '',0,0, 'R');
                            $pdf->Ln(45);
                        }
                        //  1-3 || 3-1
                        else if ($MultipleChoice['img_1'] != null && $MultipleChoice['img_2'] == null && $MultipleChoice['img_3'] != null && $MultipleChoice['img_4'] == null){
                            $pdf->Cell(40,40, '',0,0, 'R');
                            $pdf->MultiCell(30, 0, $pdf->Image("../assets/uploads/".$department."/".$user_div."/".$exam_id."/".$testII."/".$MultipleChoice['option_id']."/option1/".$MultipleChoice['img_1'], $pdf->GetX(),$pdf->GetY(),0, 40), 0, 0);
                            $pdf->Cell(40,40, '',0,0, 'R');
                            $pdf->MultiCell(30, 0, $pdf->Image("../assets/uploads/".$department."/".$user_div."/".$exam_id."/".$testII."/".$MultipleChoice['option_id']."/option3/".$MultipleChoice['img_3'], $pdf->GetX(),$pdf->GetY(),0, 40), 0, 0);
                            $pdf->Cell(40,40, '',0,0, 'R');
                            $pdf->Ln(45);
                        }
                        //  1-4 || 4-1
                        else if ($MultipleChoice['img_1'] != null && $MultipleChoice['img_2'] == null && $MultipleChoice['img_3'] == null && $MultipleChoice['img_4'] != null){
                            $pdf->Cell(40,40, '',0,0, 'R');
                            $pdf->MultiCell(30, 0, $pdf->Image("../assets/uploads/".$department."/".$user_div."/".$exam_id."/".$testII."/".$MultipleChoice['option_id']."/option1/".$MultipleChoice['img_1'], $pdf->GetX(),$pdf->GetY(),0, 40), 0, 0);
                            $pdf->Cell(40,40, '',0,0, 'R');
                            $pdf->MultiCell(30, 0, $pdf->Image("../assets/uploads/".$department."/".$user_div."/".$exam_id."/".$testII."/".$MultipleChoice['option_id']."/option4/".$MultipleChoice['img_4'], $pdf->GetX(),$pdf->GetY(),0, 40), 0, 0);
                            $pdf->Cell(40,40, '',0,0, 'R');
                            $pdf->Ln(45);
                        }
                        //  2-3 || 3-2
                        else if ($MultipleChoice['img_1'] == null && $MultipleChoice['img_2'] != null && $MultipleChoice['img_3'] != null && $MultipleChoice['img_4'] == null){
                            $pdf->Cell(40,40, '',0,0, 'R');
                            $pdf->MultiCell(30, 0, $pdf->Image("../assets/uploads/".$department."/".$user_div."/".$exam_id."/".$testII."/".$MultipleChoice['option_id']."/option2/".$MultipleChoice['img_2'], $pdf->GetX(),$pdf->GetY(),0, 40), 0, 0);
                            $pdf->Cell(40,40, '',0,0, 'R');
                            $pdf->MultiCell(30, 0, $pdf->Image("../assets/uploads/".$department."/".$user_div."/".$exam_id."/".$testII."/".$MultipleChoice['option_id']."/option3/".$MultipleChoice['img_3'], $pdf->GetX(),$pdf->GetY(),0, 40), 0, 0);
                            $pdf->Cell(40,40, '',0,0, 'R');
                            $pdf->Ln(45);
                        }
                        //  2-4 || 4-2
                        else if ($MultipleChoice['img_1'] == null && $MultipleChoice['img_2'] != null && $MultipleChoice['img_3'] == null && $MultipleChoice['img_4'] != null){
                            $pdf->Cell(40,40, '',0,0, 'R');
                            $pdf->MultiCell(30, 0, $pdf->Image("../assets/uploads/".$department."/".$user_div."/".$exam_id."/".$testII."/".$MultipleChoice['option_id']."/option2/".$MultipleChoice['img_2'], $pdf->GetX(),$pdf->GetY(),0, 40), 0, 0);
                            $pdf->Cell(40,40, '',0,0, 'R');
                            $pdf->MultiCell(30, 0, $pdf->Image("../assets/uploads/".$department."/".$user_div."/".$exam_id."/".$testII."/".$MultipleChoice['option_id']."/option4/".$MultipleChoice['img_4'], $pdf->GetX(),$pdf->GetY(),0, 40), 0, 0);
                            $pdf->Cell(40,40, '',0,0, 'R');
                            $pdf->Ln(45);
                        }
                        //  3-4 || 4-3
                        else if ($MultipleChoice['img_1'] == null && $MultipleChoice['img_2'] == null && $MultipleChoice['img_3'] != null && $MultipleChoice['img_4'] != null){
                            $pdf->Cell(40,40, '',0,0, 'R');
                            $pdf->MultiCell(30, 0, $pdf->Image("../assets/uploads/".$department."/".$user_div."/".$exam_id."/".$testII."/".$MultipleChoice['option_id']."/option3/".$MultipleChoice['img_3'], $pdf->GetX(),$pdf->GetY(),0, 40), 0, 0);
                            $pdf->Cell(40,40, '',0,0, 'R');
                            $pdf->MultiCell(30, 0, $pdf->Image("../assets/uploads/".$department."/".$user_div."/".$exam_id."/".$testII."/".$MultipleChoice['option_id']."/option4/".$MultipleChoice['img_4'], $pdf->GetX(),$pdf->GetY(),0, 40), 0, 0);
                            $pdf->Cell(40,40, '',0,0, 'R');
                            $pdf->Ln(45);
                        }
                        else if ($MultipleChoice['img_1'] != null && $MultipleChoice['img_2'] == null && $MultipleChoice['img_3'] == null && $MultipleChoice['img_4'] == null){
                            $pdf->Cell(70,40, '',0,0, 'R');
                            $pdf->MultiCell(40, 0, $pdf->Image("../assets/uploads/".$department."/".$user_div."/".$exam_id."/".$testII."/".$MultipleChoice['option_id']."/option1/".$MultipleChoice['img_1'], $pdf->GetX(),$pdf->GetY(),0, 40), 0, 0);
                            $pdf->Cell(80,40, '',0,0, 'R');
                            $pdf->Ln(45);
                        }
                        else if ($MultipleChoice['img_1'] == null && $MultipleChoice['img_2'] != null && $MultipleChoice['img_3'] == null && $MultipleChoice['img_4'] == null){
                            $pdf->Cell(70,0, '',0,0, 'R');
                            $pdf->MultiCell(40, 0, $pdf->Image("../assets/uploads/".$department."/".$user_div."/".$exam_id."/".$testII."/".$MultipleChoice['option_id']."/option2/".$MultipleChoice['img_2'], $pdf->GetX(),$pdf->GetY(),0, 40), 0, 0);
                            $pdf->Cell(80,0, '',0,0, 'R');
                            $pdf->Ln(45);
                        }
                        else if ($MultipleChoice['img_1'] == null && $MultipleChoice['img_2'] == null && $MultipleChoice['img_3'] != null && $MultipleChoice['img_4'] == null){
                            $pdf->Cell(70,40, '',0,0, 'R');
                            $pdf->MultiCell(40, 0, $pdf->Image("../assets/uploads/".$department."/".$user_div."/".$exam_id."/".$testII."/".$MultipleChoice['option_id']."/option3/".$MultipleChoice['img_3'], $pdf->GetX(),$pdf->GetY(),0, 40), 0, 0);
                            $pdf->Cell(80,40, '',0,0, 'R');
                            $pdf->Ln(45);
                        }
                        else if ($MultipleChoice['img_1'] == null && $MultipleChoice['img_2'] == null && $MultipleChoice['img_3'] == null && $MultipleChoice['img_4'] != null){
                            $pdf->Cell(70,40, '',0,0, 'R');
                            $pdf->MultiCell(40, 0, $pdf->Image("../assets/uploads/".$department."/".$user_div."/".$exam_id."/".$testII."/".$MultipleChoice['option_id']."/option4/".$MultipleChoice['img_4'], $pdf->GetX(),$pdf->GetY(),0, 40), 0, 0);
                            $pdf->Cell(80,40, '',0,0, 'R');
                            $pdf->Ln(45);
                        }
                        $pdf->Cell(24,5, 'A.',0,0, 'R');
                        $pdf->MultiCell(76,5, $MultipleChoice['option_1'],0,0);
                        $pdf->Cell(7,5, 'C.',0,0, 'R');
                        $pdf->MultiCell(76, 5, $MultipleChoice['option_3'],0,1);
                        $pdf->Ln(2);
                        $pdf->Cell(24,5, 'B.',0,0, 'R');
                        $pdf->MultiCell(76, 5, $MultipleChoice['option_2'],0,0);
                        $pdf->Cell(7,5, 'D.',0,0, 'R');
                        $pdf->MultiCell(76, 5, $MultipleChoice['option_4'],0,1);
                        $pdf->Ln(5);
                        $pdf->SetTextColor(0,0,0);
                    }
                }
                else if ($testII == 'Short Answer'){
                    $pdf->Cell(15,5, 'Test II:',0,0);
                    $pdf->Cell(177,5, ' Short Answer',0,1);
                    $pdf->SetFont('Times','',13);
                    $pdf->MultiCell(177,5, 'Directions: Using your own words, answer each question in the space provided',0,0);
                    $pdf->Ln(20);

                    while ($ShortAnswer = $ShortAnswerQuery->fetch()){
                        $ans = $ShortAnswer['correct_incorrect'];
                        if ($ans == 'correct'){
                            $check = "4";
                            $pdf->SetFont('ZapfDingbats','', 10);
                            $pdf->Cell(5,5, $check,0,0);

                        }else if ($ans == 'incorrect'){
                            $cross = "8";
                            $pdf->SetFont('ZapfDingbats','', 10);
                            $pdf->Cell(5,5, $cross,0,0);

                        }
                        $pdf->SetFont('Times','',11);
                        $pdf->Cell(6,5, $index++.'. ',0,0);
                        $pdf->MultiCell(166,5, $ShortAnswer['question'].' ('.$ShortAnswer['points'].' point/s)',0,1);
                        $pdf->Ln(5);
                        $pdf->Cell(11,5, '',0,0);
                        $pdf->SetFont('Times','U',11);
                        $pdf->MultiCell(180,5, '  '.$ShortAnswer['result_ans'].'                          ',0,1);
                        $pdf->Ln(5);
                    }
                }
                else if ($testII == 'True/False'){
                    $pdf->Cell(15,5, 'Test II:',0,0);
                    $pdf->Cell(177,5, ' True or False',0,1);
                    $pdf->SetFont('Times','',13);
                    $pdf->MultiCell(177,5, 'Directions: Read the statements carefully and tell whether the statement is TRUE or FALSE. Choose TRUE if the statement is correct and FALSE if otherwise.',0,0);
                    $pdf->Ln(20);
                    while ($TrueFalse = $TrueFalseQuery->fetch()){
                        $ans = $TrueFalse['result_ans'];
                        $cans = $TrueFalse['ans'];
                        $pdf->SetMargins(10, 10, 10);
                        $pdf->SetFont('Times','U',11);
                        $pdf->Cell(12,5, '  '.$TrueFalse['result_ans'].'  ',0,0, 'C');
                        if ($cans == $ans){
                            $check = "4";
                            $pdf->SetFont('ZapfDingbats','', 10);
                            $pdf->Cell(6,5, $check,0,0);
                        }else if ($cans != $ans){
                            $cross = "8";
                            $pdf->SetFont('ZapfDingbats','', 10);
                            $pdf->Cell(6,5, $cross,0,0);
                        }
                        $pdf->SetFont('Times','',11);
                        $pdf->Cell(6,5, $index++.'. ',0,0);
                        $pdf->MultiCell(166,5, $TrueFalse['question'].' ('.$TrueFalse['points'].' point/s)', 0, 1);
                        $pdf->Ln(5);


                        if ($TrueFalse['img_1'] != null && $TrueFalse['img_2'] != null && $TrueFalse['img_3'] != null && $TrueFalse['img_4'] != null){
                            $pdf->Cell(20,25, '',0,0, 'R');
                            $pdf->MultiCell(25, 0, $pdf->Image("../assets/uploads/".$department."/".$user_div."/".$exam_id."/".$testII."/".$TrueFalse['option_id']."/option1/".$TrueFalse['img_1'], $pdf->GetX(),$pdf->GetY(),25,25), 0, 0);
                            $pdf->Cell(15,25, '',0,0, 'R');
                            $pdf->MultiCell(25, 0, $pdf->Image("../assets/uploads/".$department."/".$user_div."/".$exam_id."/".$testII."/".$TrueFalse['option_id']."/option2/".$TrueFalse['img_2'], $pdf->GetX(),$pdf->GetY(),25,25), 0, 0);
                            $pdf->Cell(15,25, '',0,0, 'R');
                            $pdf->MultiCell(25, 0, $pdf->Image("../assets/uploads/".$department."/".$user_div."/".$exam_id."/".$testII."/".$TrueFalse['option_id']."/option3/".$TrueFalse['img_3'], $pdf->GetX(),$pdf->GetY(),25,25), 0, 0);
                            $pdf->Cell(15,25, '',0,0, 'R');
                            $pdf->MultiCell(25, 0, $pdf->Image("../assets/uploads/".$department."/".$user_div."/".$exam_id."/".$testII."/".$TrueFalse['option_id']."/option4/".$TrueFalse['img_4'], $pdf->GetX(),$pdf->GetY(),25,25), 0, 0);
                            $pdf->Cell(15,25, '',0,1, 'R');
                            $pdf->Ln(5);
                        }
                        // 4 = null
                        else if ($TrueFalse['img_1'] != null && $TrueFalse['img_2'] != null && $TrueFalse['img_3'] != null && $TrueFalse['img_4'] == null){
                            $pdf->Cell(30,25, '',0,0 );
                            $pdf->MultiCell(20, 0, $pdf->Image("../assets/uploads/".$department."/".$user_div."/".$exam_id."/".$testII."/".$TrueFalse['option_id']."/option1/".$TrueFalse['img_1'], $pdf->GetX(),$pdf->GetY(),25,25), 0, 0);
                            $pdf->Cell(30,25, '',0,0);
                            $pdf->MultiCell(20, 0, $pdf->Image("../assets/uploads/".$department."/".$user_div."/".$exam_id."/".$testII."/".$TrueFalse['option_id']."/option2/".$TrueFalse['img_2'], $pdf->GetX(),$pdf->GetY(),25,25), 0, 0);
                            $pdf->Cell(30,25, '',0,0);
                            $pdf->MultiCell(20, 0, $pdf->Image("../assets/uploads/".$department."/".$user_div."/".$exam_id."/".$testII."/".$TrueFalse['option_id']."/option3/".$TrueFalse['img_3'], $pdf->GetX(),$pdf->GetY(),25,25), 0, 0);
                            $pdf->Cell(30,25, '',0,0);
                            $pdf->Ln(30);
                        }
                        // 3 = null
                        else if ($TrueFalse['img_1'] != null && $TrueFalse['img_2'] != null && $TrueFalse['img_3'] == null && $TrueFalse['img_4'] != null){
                            $pdf->Cell(30,25, '',0,0, 'R');
                            $pdf->MultiCell(20, 0, $pdf->Image("../assets/uploads/".$department."/".$user_div."/".$exam_id."/".$testII."/".$TrueFalse['option_id']."/option1/".$TrueFalse['img_1'], $pdf->GetX(),$pdf->GetY(),25,25), 0, 0);
                            $pdf->Cell(30,25, '',0,0, 'R');
                            $pdf->MultiCell(20, 0, $pdf->Image("../assets/uploads/".$department."/".$user_div."/".$exam_id."/".$testII."/".$TrueFalse['option_id']."/option2/".$TrueFalse['img_2'], $pdf->GetX(),$pdf->GetY(),25,25), 0, 0);
                            $pdf->Cell(30,25, '',0,0, 'R');
                            $pdf->MultiCell(20, 0, $pdf->Image("../assets/uploads/".$department."/".$user_div."/".$exam_id."/".$testII."/".$TrueFalse['option_id']."/option4/".$TrueFalse['img_4'], $pdf->GetX(),$pdf->GetY(),25,25), 0, 0);
                            $pdf->Cell(30,25, '',0,0, 'R');
                            $pdf->Ln(30);
                        }
                        // 2 = null
                        else if ($TrueFalse['img_1'] != null && $TrueFalse['img_2'] == null && $TrueFalse['img_3'] != null && $TrueFalse['img_4'] != null){
                            $pdf->Cell(30,25, '',0,0, 'R');
                            $pdf->MultiCell(20, 0, $pdf->Image("../assets/uploads/".$department."/".$user_div."/".$exam_id."/".$testII."/".$TrueFalse['option_id']."/option1/".$TrueFalse['img_1'], $pdf->GetX(),$pdf->GetY(),25,25), 0, 0);
                            $pdf->Cell(30,25, '',0,0, 'R');
                            $pdf->MultiCell(20, 0, $pdf->Image("../assets/uploads/".$department."/".$user_div."/".$exam_id."/".$testII."/".$TrueFalse['option_id']."/option3/".$TrueFalse['img_3'], $pdf->GetX(),$pdf->GetY(),25,25), 0, 0);
                            $pdf->Cell(30,25, '',0,0, 'R');
                            $pdf->MultiCell(20, 0, $pdf->Image("../assets/uploads/".$department."/".$user_div."/".$exam_id."/".$testII."/".$TrueFalse['option_id']."/option4/".$TrueFalse['img_4'], $pdf->GetX(),$pdf->GetY(),25,25), 0, 0);
                            $pdf->Cell(30,25, '',0,0, 'R');
                            $pdf->Ln(30);
                        }
                        // 1 = null
                        else if ($TrueFalse['img_1'] == null && $TrueFalse['img_2'] != null && $TrueFalse['img_3'] != null && $TrueFalse['img_4'] != null){
                            $pdf->Cell(30,25, '',0,0, 'R');
                            $pdf->MultiCell(20, 0, $pdf->Image("../assets/uploads/".$department."/".$user_div."/".$exam_id."/".$testII."/".$TrueFalse['option_id']."/option2/".$TrueFalse['img_2'], $pdf->GetX(),$pdf->GetY(),25,25), 0, 0);
                            $pdf->Cell(30,25, '',0,0, 'R');
                            $pdf->MultiCell(20, 0, $pdf->Image("../assets/uploads/".$department."/".$user_div."/".$exam_id."/".$testII."/".$TrueFalse['option_id']."/option3/".$TrueFalse['img_3'], $pdf->GetX(),$pdf->GetY(),25,25), 0, 0);
                            $pdf->Cell(30,25, '',0,0, 'R');
                            $pdf->MultiCell(20, 0, $pdf->Image("../assets/uploads/".$department."/".$user_div."/".$exam_id."/".$testII."/".$TrueFalse['option_id']."/option4/".$TrueFalse['img_4'], $pdf->GetX(),$pdf->GetY(),25,25), 0, 0);
                            $pdf->Cell(30,25, '',0,0, 'R');
                            $pdf->Ln(30);
                        }
                        //  1-2 || 2-1
                        else if ($TrueFalse['img_1'] != null && $TrueFalse['img_2'] != null && $TrueFalse['img_3'] == null && $TrueFalse['img_4'] == null){
                            $pdf->Cell(40,40, '',0,0, 'R');
                            $pdf->MultiCell(30, 0, $pdf->Image("../assets/uploads/".$department."/".$user_div."/".$exam_id."/".$testII."/".$TrueFalse['option_id']."/option1/".$TrueFalse['img_1'], $pdf->GetX(),$pdf->GetY(),0, 40), 0, 0);
                            $pdf->Cell(40,40, '',0,0, 'R');
                            $pdf->MultiCell(30, 0, $pdf->Image("../assets/uploads/".$department."/".$user_div."/".$exam_id."/".$testII."/".$TrueFalse['option_id']."/option2/".$TrueFalse['img_2'], $pdf->GetX(),$pdf->GetY(),0, 40), 0, 0);
                            $pdf->Cell(40,40, '',0,0, 'R');
                            $pdf->Ln(45);
                        }
                        //  1-3 || 3-1
                        else if ($TrueFalse['img_1'] != null && $TrueFalse['img_2'] == null && $TrueFalse['img_3'] != null && $TrueFalse['img_4'] == null){
                            $pdf->Cell(40,40, '',0,0, 'R');
                            $pdf->MultiCell(30, 0, $pdf->Image("../assets/uploads/".$department."/".$user_div."/".$exam_id."/".$testII."/".$TrueFalse['option_id']."/option1/".$TrueFalse['img_1'], $pdf->GetX(),$pdf->GetY(),0, 40), 0, 0);
                            $pdf->Cell(40,40, '',0,0, 'R');
                            $pdf->MultiCell(30, 0, $pdf->Image("../assets/uploads/".$department."/".$user_div."/".$exam_id."/".$testII."/".$TrueFalse['option_id']."/option3/".$TrueFalse['img_3'], $pdf->GetX(),$pdf->GetY(),0, 40), 0, 0);
                            $pdf->Cell(40,40, '',0,0, 'R');
                            $pdf->Ln(45);
                        }
                        //  1-4 || 4-1
                        else if ($TrueFalse['img_1'] != null && $TrueFalse['img_2'] == null && $TrueFalse['img_3'] == null && $TrueFalse['img_4'] != null){
                            $pdf->Cell(40,40, '',0,0, 'R');
                            $pdf->MultiCell(30, 0, $pdf->Image("../assets/uploads/".$department."/".$user_div."/".$exam_id."/".$testII."/".$TrueFalse['option_id']."/option1/".$TrueFalse['img_1'], $pdf->GetX(),$pdf->GetY(),0, 40), 0, 0);
                            $pdf->Cell(40,40, '',0,0, 'R');
                            $pdf->MultiCell(30, 0, $pdf->Image("../assets/uploads/".$department."/".$user_div."/".$exam_id."/".$testII."/".$TrueFalse['option_id']."/option4/".$TrueFalse['img_4'], $pdf->GetX(),$pdf->GetY(),0, 40), 0, 0);
                            $pdf->Cell(40,40, '',0,0, 'R');
                            $pdf->Ln(45);
                        }
                        //  2-3 || 3-2
                        else if ($TrueFalse['img_1'] == null && $TrueFalse['img_2'] != null && $TrueFalse['img_3'] != null && $TrueFalse['img_4'] == null){
                            $pdf->Cell(40,40, '',0,0, 'R');
                            $pdf->MultiCell(30, 0, $pdf->Image("../assets/uploads/".$department."/".$user_div."/".$exam_id."/".$testII."/".$TrueFalse['option_id']."/option2/".$TrueFalse['img_2'], $pdf->GetX(),$pdf->GetY(),0, 40), 0, 0);
                            $pdf->Cell(40,40, '',0,0, 'R');
                            $pdf->MultiCell(30, 0, $pdf->Image("../assets/uploads/".$department."/".$user_div."/".$exam_id."/".$testII."/".$TrueFalse['option_id']."/option3/".$TrueFalse['img_3'], $pdf->GetX(),$pdf->GetY(),0, 40), 0, 0);
                            $pdf->Cell(40,40, '',0,0, 'R');
                            $pdf->Ln(45);
                        }
                        //  2-4 || 4-2
                        else if ($TrueFalse['img_1'] == null && $TrueFalse['img_2'] != null && $TrueFalse['img_3'] == null && $TrueFalse['img_4'] != null){
                            $pdf->Cell(40,40, '',0,0, 'R');
                            $pdf->MultiCell(30, 0, $pdf->Image("../assets/uploads/".$department."/".$user_div."/".$exam_id."/".$testII."/".$TrueFalse['option_id']."/option2/".$TrueFalse['img_2'], $pdf->GetX(),$pdf->GetY(),0, 40), 0, 0);
                            $pdf->Cell(40,40, '',0,0, 'R');
                            $pdf->MultiCell(30, 0, $pdf->Image("../assets/uploads/".$department."/".$user_div."/".$exam_id."/".$testII."/".$TrueFalse['option_id']."/option4/".$TrueFalse['img_4'], $pdf->GetX(),$pdf->GetY(),0, 40), 0, 0);
                            $pdf->Cell(40,40, '',0,0, 'R');
                            $pdf->Ln(45);
                        }
                        //  3-4 || 4-3
                        else if ($TrueFalse['img_1'] == null && $TrueFalse['img_2'] == null && $TrueFalse['img_3'] != null && $TrueFalse['img_4'] != null){
                            $pdf->Cell(40,40, '',0,0, 'R');
                            $pdf->MultiCell(30, 0, $pdf->Image("../assets/uploads/".$department."/".$user_div."/".$exam_id."/".$testII."/".$TrueFalse['option_id']."/option3/".$TrueFalse['img_3'], $pdf->GetX(),$pdf->GetY(),0, 40), 0, 0);
                            $pdf->Cell(40,40, '',0,0, 'R');
                            $pdf->MultiCell(30, 0, $pdf->Image("../assets/uploads/".$department."/".$user_div."/".$exam_id."/".$testII."/".$TrueFalse['option_id']."/option4/".$TrueFalse['img_4'], $pdf->GetX(),$pdf->GetY(),0, 40), 0, 0);
                            $pdf->Cell(40,40, '',0,0, 'R');
                            $pdf->Ln(45);
                        }
                        else if ($TrueFalse['img_1'] != null && $TrueFalse['img_2'] == null && $TrueFalse['img_3'] == null && $TrueFalse['img_4'] == null){
                            $pdf->Cell(70,40, '',0,0, 'R');
                            $pdf->MultiCell(40, 0, $pdf->Image("../assets/uploads/".$department."/".$user_div."/".$exam_id."/".$testII."/".$TrueFalse['option_id']."/option1/".$TrueFalse['img_1'], $pdf->GetX(),$pdf->GetY(),0, 40), 0, 0);
                            $pdf->Cell(80,40, '',0,0, 'R');
                            $pdf->Ln(45);
                        }
                        else if ($TrueFalse['img_1'] == null && $TrueFalse['img_2'] != null && $TrueFalse['img_3'] == null && $TrueFalse['img_4'] == null){
                            $pdf->Cell(70,0, '',0,0, 'R');
                            $pdf->MultiCell(40, 0, $pdf->Image("../assets/uploads/".$department."/".$user_div."/".$exam_id."/".$testII."/".$TrueFalse['option_id']."/option2/".$TrueFalse['img_2'], $pdf->GetX(),$pdf->GetY(),0, 40), 0, 0);
                            $pdf->Cell(80,0, '',0,0, 'R');
                            $pdf->Ln(45);
                        }
                        else if ($TrueFalse['img_1'] == null && $TrueFalse['img_2'] == null && $TrueFalse['img_3'] != null && $TrueFalse['img_4'] == null){
                            $pdf->Cell(70,40, '',0,0, 'R');
                            $pdf->MultiCell(40, 0, $pdf->Image("../assets/uploads/".$department."/".$user_div."/".$exam_id."/".$testII."/".$TrueFalse['option_id']."/option3/".$TrueFalse['img_3'], $pdf->GetX(),$pdf->GetY(),0, 40), 0, 0);
                            $pdf->Cell(80,40, '',0,0, 'R');
                            $pdf->Ln(45);
                        }
                        else if ($TrueFalse['img_1'] == null && $TrueFalse['img_2'] == null && $TrueFalse['img_3'] == null && $TrueFalse['img_4'] != null){
                            $pdf->Cell(70,40, '',0,0, 'R');
                            $pdf->MultiCell(40, 0, $pdf->Image("../assets/uploads/".$department."/".$user_div."/".$exam_id."/".$testII."/".$TrueFalse['option_id']."/option4/".$TrueFalse['img_4'], $pdf->GetX(),$pdf->GetY(),0, 40), 0, 0);
                            $pdf->Cell(80,40, '',0,0, 'R');
                            $pdf->Ln(45);
                        }
                    }
                }
                else if ($testII == 'Multiple Image'){
                    $pdf->Cell(15,5, 'Test II:',0,0);
                    $pdf->Cell(177,5, ' Multiple Choice (Images)',0,1);
                    $pdf->SetFont('Times','',13);
                    $pdf->MultiCell(177,5, 'Directions: Choose the BEST IMAGE answer for the following questions, and write your answer in the blank.',0,0);
                    $pdf->Ln(20);
                    while ($MultipleImage = $MultipleImageQuery->fetch()) {
                        $ans = $MultipleImage['result_ans'];
                        $pdf->SetMargins(10, 10, 10);
                        $pdf->SetTextColor(0,0,0);
                        $pdf->SetFont('Times','U',11);
                        if ($ans == 'I'){
                            $pdf->Cell(12,5, ' A ',0,0, 'C');
                        }else if ($ans == 'II'){
                            $pdf->Cell(12,5, ' B ',0,0, 'C');
                        }else if ($ans == 'III'){
                            $pdf->Cell(12,5, ' C ',0,0, 'C');
                        }else if ($ans == 'IV'){
                            $pdf->Cell(12,5, ' D  ',0,0, 'C');
                        }
                        $ans1 = $MultipleImage['correct_incorrect'];
                        if ($ans1 == 'correct'){
                            $check = "4";
                            $pdf->SetFont('ZapfDingbats','', 10);
                            $pdf->Cell(5,5, '       '.$check,0,0, 'R');

                        }else if ($ans1 == 'incorrect'){
                            $cross = "8";
                            $pdf->SetFont('ZapfDingbats','', 10);
                            $pdf->Cell(5,5, '       '.$cross,0,0, 'R');

                        }
                        $pdf->SetFont('Times','',11);
                        $pdf->Cell(6,5, $index++.'. ',0,0);
                        $pdf->MultiCell(166,5, $MultipleImage['question'].' ('.$MultipleImage['points'].' point/s)',0,1);
                        $pdf->Ln(5);
                        $pdf->SetTextColor(105,105,105);
                        $pdf->Cell(25,25, 'A: ',0,0, 'R');
                        $pdf->MultiCell(30, 0, $pdf->Image("../assets/uploads/".$department."/".$user_div."/".$exam_id."/Multiple Image/".$MultipleImage['option_id']."/option1/".$MultipleImage['img_1'], $pdf->GetX(),$pdf->GetY(),25,25), 0, 0);
                        $pdf->Cell(15,25, 'B: ',0,0, 'R');
                        $pdf->MultiCell(25, 0, $pdf->Image("../assets/uploads/".$department."/".$user_div."/".$exam_id."/Multiple Image/".$MultipleImage['option_id']."/option2/".$MultipleImage['img_2'], $pdf->GetX(),$pdf->GetY(),25,25), 0, 0);
                        $pdf->Cell(15,25, 'C: ',0,0, 'R');
                        $pdf->MultiCell(25, 0, $pdf->Image("../assets/uploads/".$department."/".$user_div."/".$exam_id."/Multiple Image/".$MultipleImage['option_id']."/option3/".$MultipleImage['img_3'], $pdf->GetX(),$pdf->GetY(),25,25), 0, 0);
                        $pdf->Cell(15,25, 'D: ',0,0, 'R');
                        $pdf->MultiCell(25, 0, $pdf->Image("../assets/uploads/".$department."/".$user_div."/".$exam_id."/Multiple Image/".$MultipleImage['option_id']."/option4/".$MultipleImage['img_4'], $pdf->GetX(),$pdf->GetY(),25,25), 0, 0);
                        $pdf->Ln(10);
                        $pdf->Ln(20);
                        $pdf->Ln(20);

                    }

                }
                else if ($testII == 'Essay') {
                    $pdf->Cell(15,5, 'Test II:',0,0);
                    $pdf->Cell(177, 5, ' Essay', 0, 1);
                    $pdf->SetFont('Times', '', 13);
                    $pdf->MultiCell(177, 5, 'Directions: Answer the question to the best of your knowledge. Write your answer in the space provided', 0, 0);
                    $pdf->Ln(20);
                    while ($Essay = $EssayQuery->fetch()) {
                        $ans = $Essay['correct_incorrect'];
                        if ($ans == 'correct'){
                            $check = "4";
                            $pdf->SetFont('Times', '', 11);
                            $pdf->Cell(15,5, $Essay['score_points'].' Points',0,0);
                            $pdf->SetFont('ZapfDingbats','', 10);
                            $pdf->Cell(5,5, $check,0,0);

                        }else if ($ans == 'incorrect'){
                            $cross = "8";
                            $pdf->SetFont('Times', '', 11);
                            $pdf->Cell(15,5, $Essay['score_points'].' Points',0,0);
                            $pdf->SetFont('ZapfDingbats','', 10);
                            $pdf->Cell(5,5, $cross,0,0);

                        }
                        $pdf->SetFont('Times', '', 11);
                        $pdf->Cell(6, 5, $index++ . '. ', 0, 0);
                        $pdf->MultiCell(160, 5, $Essay['question'].' ('.$Essay['points'].' point/s)', 0, 1);
                        $pdf->Ln(5);
                        $pdf->Cell(20, 5, '', 0, 0);
                        $pdf->SetFont('Times', 'U', 11);
                        $pdf->MultiCell(166, 5, '           '. $Essay['result_ans'] . '              ', 0, 1);
                        $pdf->Ln(5);
                    }
                }
                $pdf->Ln(10);
            }
            // Test II End


            // Test III Start
            // pdf->AddPage();
            $pdf->SetTextColor(0,0,0);
            $pdf->SetFont('Times','B',13);
            if ($testIII != null || $testIII != '' || $testIII != 0){
                if ($testIII == 'Multiple Choice'){
                    $pdf->Cell(15,5, 'Test III:',0,0);
                    $pdf->Cell(177,5, ' Multiple Choice',0,1);
                    $pdf->SetFont('Times','',13);
                    $pdf->Cell(177,5, 'Directions: Choose the BEST answer for the following questions.',0,0);
                    $pdf->Ln(20);
                    while ($MultipleChoice = $MultipleChoiceQuery->fetch()){
                        $ans = $MultipleChoice['result_ans'];
                        $cans = $MultipleChoice['ans'];
                        $pdf->SetMargins(10, 10, 10);
                        $pdf->SetFont('Times','U',11);
                        if ($ans == $MultipleChoice['option_1']){
                            $pdf->Cell(12,5, '     '.'A'.'     ',0,0, 'C');
                        }else if ($ans == $MultipleChoice['option_2']){
                            $pdf->Cell(12,5, '     '.'B'.'     ',0,0, 'C');
                        }else if ($ans == $MultipleChoice['option_3']){
                            $pdf->Cell(12,5, '     '.'C'.'     ',0,0, 'C');
                        }else if ($ans == $MultipleChoice['option_4']){
                            $pdf->Cell(12,5, '     '.'D'.'     ',0,0, 'C');
                        }
                        if ($cans == $ans){
                            $check = "4";
                            $pdf->SetFont('ZapfDingbats','', 10);
                            $pdf->Cell(6,5, $check,0,0);
                        }else if ($cans != $ans){
                            $cross = "8";
                            $pdf->SetFont('ZapfDingbats','', 10);
                            $pdf->Cell(6,5, $cross,0,0);
                        }
                        $pdf->SetFont('Times','',11);
                        $pdf->Cell(6,5, $index++.'. ',0,0);
                        $pdf->MultiCell(166,5, $MultipleChoice['question'].' ('.$MultipleChoice['points'].' point/s)',0,1);
                        $pdf->Ln(5);
                        $pdf->SetTextColor(105,105,105);

                        if ($MultipleChoice['img_1'] != null && $MultipleChoice['img_2'] != null && $MultipleChoice['img_3'] != null && $MultipleChoice['img_4'] != null){
                            $pdf->Cell(20,25, '',0,0, 'R');
                            $pdf->MultiCell(25, 0, $pdf->Image("../assets/uploads/".$department."/".$user_div."/".$exam_id."/".$testIII."/".$MultipleChoice['option_id']."/option1/".$MultipleChoice['img_1'], $pdf->GetX(),$pdf->GetY(),25,25), 0, 0);
                            $pdf->Cell(15,25, '',0,0, 'R');
                            $pdf->MultiCell(25, 0, $pdf->Image("../assets/uploads/".$department."/".$user_div."/".$exam_id."/".$testIII."/".$MultipleChoice['option_id']."/option2/".$MultipleChoice['img_2'], $pdf->GetX(),$pdf->GetY(),25,25), 0, 0);
                            $pdf->Cell(15,25, '',0,0, 'R');
                            $pdf->MultiCell(25, 0, $pdf->Image("../assets/uploads/".$department."/".$user_div."/".$exam_id."/".$testIII."/".$MultipleChoice['option_id']."/option3/".$MultipleChoice['img_3'], $pdf->GetX(),$pdf->GetY(),25,25), 0, 0);
                            $pdf->Cell(15,25, '',0,0, 'R');
                            $pdf->MultiCell(25, 0, $pdf->Image("../assets/uploads/".$department."/".$user_div."/".$exam_id."/".$testIII."/".$MultipleChoice['option_id']."/option4/".$MultipleChoice['img_4'], $pdf->GetX(),$pdf->GetY(),25,25), 0, 0);
                            $pdf->Cell(15,25, '',0,1, 'R');
                            $pdf->Ln(5);
                        }
                        // 4 = null
                        else if ($MultipleChoice['img_1'] != null && $MultipleChoice['img_2'] != null && $MultipleChoice['img_3'] != null && $MultipleChoice['img_4'] == null){
                            $pdf->Cell(30,25, '',0,0 );
                            $pdf->MultiCell(20, 0, $pdf->Image("../assets/uploads/".$department."/".$user_div."/".$exam_id."/".$testIII."/".$MultipleChoice['option_id']."/option1/".$MultipleChoice['img_1'], $pdf->GetX(),$pdf->GetY(),25,25), 0, 0);
                            $pdf->Cell(30,25, '',0,0);
                            $pdf->MultiCell(20, 0, $pdf->Image("../assets/uploads/".$department."/".$user_div."/".$exam_id."/".$testIII."/".$MultipleChoice['option_id']."/option2/".$MultipleChoice['img_2'], $pdf->GetX(),$pdf->GetY(),25,25), 0, 0);
                            $pdf->Cell(30,25, '',0,0);
                            $pdf->MultiCell(20, 0, $pdf->Image("../assets/uploads/".$department."/".$user_div."/".$exam_id."/".$testIII."/".$MultipleChoice['option_id']."/option3/".$MultipleChoice['img_3'], $pdf->GetX(),$pdf->GetY(),25,25), 0, 0);
                            $pdf->Cell(30,25, '',0,0);
                            $pdf->Ln(30);
                        }
                        // 3 = null
                        else if ($MultipleChoice['img_1'] != null && $MultipleChoice['img_2'] != null && $MultipleChoice['img_3'] == null && $MultipleChoice['img_4'] != null){
                            $pdf->Cell(30,25, '',0,0, 'R');
                            $pdf->MultiCell(20, 0, $pdf->Image("../assets/uploads/".$department."/".$user_div."/".$exam_id."/".$testIII."/".$MultipleChoice['option_id']."/option1/".$MultipleChoice['img_1'], $pdf->GetX(),$pdf->GetY(),25,25), 0, 0);
                            $pdf->Cell(30,25, '',0,0, 'R');
                            $pdf->MultiCell(20, 0, $pdf->Image("../assets/uploads/".$department."/".$user_div."/".$exam_id."/".$testIII."/".$MultipleChoice['option_id']."/option2/".$MultipleChoice['img_2'], $pdf->GetX(),$pdf->GetY(),25,25), 0, 0);
                            $pdf->Cell(30,25, '',0,0, 'R');
                            $pdf->MultiCell(20, 0, $pdf->Image("../assets/uploads/".$department."/".$user_div."/".$exam_id."/".$testIII."/".$MultipleChoice['option_id']."/option4/".$MultipleChoice['img_4'], $pdf->GetX(),$pdf->GetY(),25,25), 0, 0);
                            $pdf->Cell(30,25, '',0,0, 'R');
                            $pdf->Ln(30);
                        }
                        // 2 = null
                        else if ($MultipleChoice['img_1'] != null && $MultipleChoice['img_2'] == null && $MultipleChoice['img_3'] != null && $MultipleChoice['img_4'] != null){
                            $pdf->Cell(30,25, '',0,0, 'R');
                            $pdf->MultiCell(20, 0, $pdf->Image("../assets/uploads/".$department."/".$user_div."/".$exam_id."/".$testIII."/".$MultipleChoice['option_id']."/option1/".$MultipleChoice['img_1'], $pdf->GetX(),$pdf->GetY(),25,25), 0, 0);
                            $pdf->Cell(30,25, '',0,0, 'R');
                            $pdf->MultiCell(20, 0, $pdf->Image("../assets/uploads/".$department."/".$user_div."/".$exam_id."/".$testIII."/".$MultipleChoice['option_id']."/option3/".$MultipleChoice['img_3'], $pdf->GetX(),$pdf->GetY(),25,25), 0, 0);
                            $pdf->Cell(30,25, '',0,0, 'R');
                            $pdf->MultiCell(20, 0, $pdf->Image("../assets/uploads/".$department."/".$user_div."/".$exam_id."/".$testIII."/".$MultipleChoice['option_id']."/option4/".$MultipleChoice['img_4'], $pdf->GetX(),$pdf->GetY(),25,25), 0, 0);
                            $pdf->Cell(30,25, '',0,0, 'R');
                            $pdf->Ln(30);
                        }
                        // 1 = null
                        else if ($MultipleChoice['img_1'] == null && $MultipleChoice['img_2'] != null && $MultipleChoice['img_3'] != null && $MultipleChoice['img_4'] != null){
                            $pdf->Cell(30,25, '',0,0, 'R');
                            $pdf->MultiCell(20, 0, $pdf->Image("../assets/uploads/".$department."/".$user_div."/".$exam_id."/".$testIII."/".$MultipleChoice['option_id']."/option2/".$MultipleChoice['img_2'], $pdf->GetX(),$pdf->GetY(),25,25), 0, 0);
                            $pdf->Cell(30,25, '',0,0, 'R');
                            $pdf->MultiCell(20, 0, $pdf->Image("../assets/uploads/".$department."/".$user_div."/".$exam_id."/".$testIII."/".$MultipleChoice['option_id']."/option3/".$MultipleChoice['img_3'], $pdf->GetX(),$pdf->GetY(),25,25), 0, 0);
                            $pdf->Cell(30,25, '',0,0, 'R');
                            $pdf->MultiCell(20, 0, $pdf->Image("../assets/uploads/".$department."/".$user_div."/".$exam_id."/".$testIII."/".$MultipleChoice['option_id']."/option4/".$MultipleChoice['img_4'], $pdf->GetX(),$pdf->GetY(),25,25), 0, 0);
                            $pdf->Cell(30,25, '',0,0, 'R');
                            $pdf->Ln(30);
                        }
                        //  1-2 || 2-1
                        else if ($MultipleChoice['img_1'] != null && $MultipleChoice['img_2'] != null && $MultipleChoice['img_3'] == null && $MultipleChoice['img_4'] == null){
                            $pdf->Cell(40,40, '',0,0, 'R');
                            $pdf->MultiCell(30, 0, $pdf->Image("../assets/uploads/".$department."/".$user_div."/".$exam_id."/".$testIII."/".$MultipleChoice['option_id']."/option1/".$MultipleChoice['img_1'], $pdf->GetX(),$pdf->GetY(),0, 40), 0, 0);
                            $pdf->Cell(40,40, '',0,0, 'R');
                            $pdf->MultiCell(30, 0, $pdf->Image("../assets/uploads/".$department."/".$user_div."/".$exam_id."/".$testIII."/".$MultipleChoice['option_id']."/option2/".$MultipleChoice['img_2'], $pdf->GetX(),$pdf->GetY(),0, 40), 0, 0);
                            $pdf->Cell(40,40, '',0,0, 'R');
                            $pdf->Ln(45);
                        }
                        //  1-3 || 3-1
                        else if ($MultipleChoice['img_1'] != null && $MultipleChoice['img_2'] == null && $MultipleChoice['img_3'] != null && $MultipleChoice['img_4'] == null){
                            $pdf->Cell(40,40, '',0,0, 'R');
                            $pdf->MultiCell(30, 0, $pdf->Image("../assets/uploads/".$department."/".$user_div."/".$exam_id."/".$testIII."/".$MultipleChoice['option_id']."/option1/".$MultipleChoice['img_1'], $pdf->GetX(),$pdf->GetY(),0, 40), 0, 0);
                            $pdf->Cell(40,40, '',0,0, 'R');
                            $pdf->MultiCell(30, 0, $pdf->Image("../assets/uploads/".$department."/".$user_div."/".$exam_id."/".$testIII."/".$MultipleChoice['option_id']."/option3/".$MultipleChoice['img_3'], $pdf->GetX(),$pdf->GetY(),0, 40), 0, 0);
                            $pdf->Cell(40,40, '',0,0, 'R');
                            $pdf->Ln(45);
                        }
                        //  1-4 || 4-1
                        else if ($MultipleChoice['img_1'] != null && $MultipleChoice['img_2'] == null && $MultipleChoice['img_3'] == null && $MultipleChoice['img_4'] != null){
                            $pdf->Cell(40,40, '',0,0, 'R');
                            $pdf->MultiCell(30, 0, $pdf->Image("../assets/uploads/".$department."/".$user_div."/".$exam_id."/".$testIII."/".$MultipleChoice['option_id']."/option1/".$MultipleChoice['img_1'], $pdf->GetX(),$pdf->GetY(),0, 40), 0, 0);
                            $pdf->Cell(40,40, '',0,0, 'R');
                            $pdf->MultiCell(30, 0, $pdf->Image("../assets/uploads/".$department."/".$user_div."/".$exam_id."/".$testIII."/".$MultipleChoice['option_id']."/option4/".$MultipleChoice['img_4'], $pdf->GetX(),$pdf->GetY(),0, 40), 0, 0);
                            $pdf->Cell(40,40, '',0,0, 'R');
                            $pdf->Ln(45);
                        }
                        //  2-3 || 3-2
                        else if ($MultipleChoice['img_1'] == null && $MultipleChoice['img_2'] != null && $MultipleChoice['img_3'] != null && $MultipleChoice['img_4'] == null){
                            $pdf->Cell(40,40, '',0,0, 'R');
                            $pdf->MultiCell(30, 0, $pdf->Image("../assets/uploads/".$department."/".$user_div."/".$exam_id."/".$testIII."/".$MultipleChoice['option_id']."/option2/".$MultipleChoice['img_2'], $pdf->GetX(),$pdf->GetY(),0, 40), 0, 0);
                            $pdf->Cell(40,40, '',0,0, 'R');
                            $pdf->MultiCell(30, 0, $pdf->Image("../assets/uploads/".$department."/".$user_div."/".$exam_id."/".$testIII."/".$MultipleChoice['option_id']."/option3/".$MultipleChoice['img_3'], $pdf->GetX(),$pdf->GetY(),0, 40), 0, 0);
                            $pdf->Cell(40,40, '',0,0, 'R');
                            $pdf->Ln(45);
                        }
                        //  2-4 || 4-2
                        else if ($MultipleChoice['img_1'] == null && $MultipleChoice['img_2'] != null && $MultipleChoice['img_3'] == null && $MultipleChoice['img_4'] != null){
                            $pdf->Cell(40,40, '',0,0, 'R');
                            $pdf->MultiCell(30, 0, $pdf->Image("../assets/uploads/".$department."/".$user_div."/".$exam_id."/".$testIII."/".$MultipleChoice['option_id']."/option2/".$MultipleChoice['img_2'], $pdf->GetX(),$pdf->GetY(),0, 40), 0, 0);
                            $pdf->Cell(40,40, '',0,0, 'R');
                            $pdf->MultiCell(30, 0, $pdf->Image("../assets/uploads/".$department."/".$user_div."/".$exam_id."/".$testIII."/".$MultipleChoice['option_id']."/option4/".$MultipleChoice['img_4'], $pdf->GetX(),$pdf->GetY(),0, 40), 0, 0);
                            $pdf->Cell(40,40, '',0,0, 'R');
                            $pdf->Ln(45);
                        }
                        //  3-4 || 4-3
                        else if ($MultipleChoice['img_1'] == null && $MultipleChoice['img_2'] == null && $MultipleChoice['img_3'] != null && $MultipleChoice['img_4'] != null){
                            $pdf->Cell(40,40, '',0,0, 'R');
                            $pdf->MultiCell(30, 0, $pdf->Image("../assets/uploads/".$department."/".$user_div."/".$exam_id."/".$testIII."/".$MultipleChoice['option_id']."/option3/".$MultipleChoice['img_3'], $pdf->GetX(),$pdf->GetY(),0, 40), 0, 0);
                            $pdf->Cell(40,40, '',0,0, 'R');
                            $pdf->MultiCell(30, 0, $pdf->Image("../assets/uploads/".$department."/".$user_div."/".$exam_id."/".$testIII."/".$MultipleChoice['option_id']."/option4/".$MultipleChoice['img_4'], $pdf->GetX(),$pdf->GetY(),0, 40), 0, 0);
                            $pdf->Cell(40,40, '',0,0, 'R');
                            $pdf->Ln(45);
                        }
                        else if ($MultipleChoice['img_1'] != null && $MultipleChoice['img_2'] == null && $MultipleChoice['img_3'] == null && $MultipleChoice['img_4'] == null){
                            $pdf->Cell(70,40, '',0,0, 'R');
                            $pdf->MultiCell(40, 0, $pdf->Image("../assets/uploads/".$department."/".$user_div."/".$exam_id."/".$testIII."/".$MultipleChoice['option_id']."/option1/".$MultipleChoice['img_1'], $pdf->GetX(),$pdf->GetY(),0, 40), 0, 0);
                            $pdf->Cell(80,40, '',0,0, 'R');
                            $pdf->Ln(45);
                        }
                        else if ($MultipleChoice['img_1'] == null && $MultipleChoice['img_2'] != null && $MultipleChoice['img_3'] == null && $MultipleChoice['img_4'] == null){
                            $pdf->Cell(70,0, '',0,0, 'R');
                            $pdf->MultiCell(40, 0, $pdf->Image("../assets/uploads/".$department."/".$user_div."/".$exam_id."/".$testIII."/".$MultipleChoice['option_id']."/option2/".$MultipleChoice['img_2'], $pdf->GetX(),$pdf->GetY(),0, 40), 0, 0);
                            $pdf->Cell(80,0, '',0,0, 'R');
                            $pdf->Ln(45);
                        }
                        else if ($MultipleChoice['img_1'] == null && $MultipleChoice['img_2'] == null && $MultipleChoice['img_3'] != null && $MultipleChoice['img_4'] == null){
                            $pdf->Cell(70,40, '',0,0, 'R');
                            $pdf->MultiCell(40, 0, $pdf->Image("../assets/uploads/".$department."/".$user_div."/".$exam_id."/".$testIII."/".$MultipleChoice['option_id']."/option3/".$MultipleChoice['img_3'], $pdf->GetX(),$pdf->GetY(),0, 40), 0, 0);
                            $pdf->Cell(80,40, '',0,0, 'R');
                            $pdf->Ln(45);
                        }
                        else if ($MultipleChoice['img_1'] == null && $MultipleChoice['img_2'] == null && $MultipleChoice['img_3'] == null && $MultipleChoice['img_4'] != null){
                            $pdf->Cell(70,40, '',0,0, 'R');
                            $pdf->MultiCell(40, 0, $pdf->Image("../assets/uploads/".$department."/".$user_div."/".$exam_id."/".$testIII."/".$MultipleChoice['option_id']."/option4/".$MultipleChoice['img_4'], $pdf->GetX(),$pdf->GetY(),0, 40), 0, 0);
                            $pdf->Cell(80,40, '',0,0, 'R');
                            $pdf->Ln(45);
                        }
                        $pdf->Cell(24,5, 'A.',0,0, 'R');
                        $pdf->MultiCell(76,5, $MultipleChoice['option_1'],0,0);
                        $pdf->Cell(7,5, 'C.',0,0, 'R');
                        $pdf->MultiCell(76, 5, $MultipleChoice['option_3'],0,1);
                        $pdf->Ln(2);
                        $pdf->Cell(24,5, 'B.',0,0, 'R');
                        $pdf->MultiCell(76, 5, $MultipleChoice['option_2'],0,0);
                        $pdf->Cell(7,5, 'D.',0,0, 'R');
                        $pdf->MultiCell(76, 5, $MultipleChoice['option_4'],0,1);
                        $pdf->Ln(5);
                        $pdf->SetTextColor(0,0,0);

                    }
                }
                else if ($testIII == 'Short Answer'){
                    $pdf->Cell(15,5, 'Test III:',0,0);
                    $pdf->Cell(177,5, ' Short Answer',0,1);
                    $pdf->SetFont('Times','',13);
                    $pdf->MultiCell(177,5, 'Directions: Using your own words, answer each question in the space provided',0,0);
                    $pdf->Ln(20);

                    while ($ShortAnswer = $ShortAnswerQuery->fetch()){
                        $ans = $ShortAnswer['correct_incorrect'];
                        if ($ans == 'correct'){
                            $check = "4";
                            $pdf->SetFont('ZapfDingbats','', 10);
                            $pdf->Cell(5,5, $check,0,0);

                        }else if ($ans == 'incorrect'){
                            $cross = "8";
                            $pdf->SetFont('ZapfDingbats','', 10);
                            $pdf->Cell(5,5, $cross,0,0);

                        }
                        $pdf->SetFont('Times','',11);
                        $pdf->Cell(6,5, $index++.'. ',0,0);
                        $pdf->MultiCell(166,5, $ShortAnswer['question'].' ('.$ShortAnswer['points'].' point/s)',0,1);
                        $pdf->Ln(5);
                        $pdf->Cell(11,5, '',0,0);
                        $pdf->SetFont('Times','U',11);
                        $pdf->MultiCell(180,5, '  '.$ShortAnswer['result_ans'].'                          ',0,1);
                        $pdf->Ln(5);
                    }
                }
                else if ($testIII == 'True/False'){
                    $pdf->Cell(15,5, 'Test III:',0,0);
                    $pdf->Cell(177,5, ' True or False',0,1);
                    $pdf->SetFont('Times','',13);
                    $pdf->MultiCell(177,5, 'Directions: Read the statements carefully and tell whether the statement is TRUE or FALSE. Choose TRUE if the statement is correct and FALSE if otherwise.',0,0);
                    $pdf->Ln(20);
                    while ($TrueFalse = $TrueFalseQuery->fetch()){
                        $ans = $TrueFalse['result_ans'];
                        $cans = $TrueFalse['ans'];
                        $pdf->SetMargins(10, 10, 10);
                        $pdf->SetFont('Times','U',11);
                        $pdf->Cell(12,5, '  '.$TrueFalse['result_ans'].'  ',0,0, 'C');
                        if ($cans == $ans){
                            $check = "4";
                            $pdf->SetFont('ZapfDingbats','', 10);
                            $pdf->Cell(6,5, $check,0,0);
                        }else if ($cans != $ans){
                            $cross = "8";
                            $pdf->SetFont('ZapfDingbats','', 10);
                            $pdf->Cell(6,5, $cross,0,0);
                        }
                        $pdf->SetFont('Times','',11);
                        $pdf->Cell(6,5, $index++.'. ',0,0);
                        $pdf->MultiCell(166,5, $TrueFalse['question'].' ('.$TrueFalse['points'].' point/s)', 0, 1);
                        $pdf->Ln(5);

                        if ($TrueFalse['img_1'] != null && $TrueFalse['img_2'] != null && $TrueFalse['img_3'] != null && $TrueFalse['img_4'] != null){
                            $pdf->Cell(20,25, '',0,0, 'R');
                            $pdf->MultiCell(25, 0, $pdf->Image("../assets/uploads/".$department."/".$user_div."/".$exam_id."/".$testIII."/".$TrueFalse['option_id']."/option1/".$TrueFalse['img_1'], $pdf->GetX(),$pdf->GetY(),25,25), 0, 0);
                            $pdf->Cell(15,25, '',0,0, 'R');
                            $pdf->MultiCell(25, 0, $pdf->Image("../assets/uploads/".$department."/".$user_div."/".$exam_id."/".$testIII."/".$TrueFalse['option_id']."/option2/".$TrueFalse['img_2'], $pdf->GetX(),$pdf->GetY(),25,25), 0, 0);
                            $pdf->Cell(15,25, '',0,0, 'R');
                            $pdf->MultiCell(25, 0, $pdf->Image("../assets/uploads/".$department."/".$user_div."/".$exam_id."/".$testIII."/".$TrueFalse['option_id']."/option3/".$TrueFalse['img_3'], $pdf->GetX(),$pdf->GetY(),25,25), 0, 0);
                            $pdf->Cell(15,25, '',0,0, 'R');
                            $pdf->MultiCell(25, 0, $pdf->Image("../assets/uploads/".$department."/".$user_div."/".$exam_id."/".$testIII."/".$TrueFalse['option_id']."/option4/".$TrueFalse['img_4'], $pdf->GetX(),$pdf->GetY(),25,25), 0, 0);
                            $pdf->Cell(15,25, '',0,1, 'R');
                            $pdf->Ln(5);
                        }
                        // 4 = null
                        else if ($TrueFalse['img_1'] != null && $TrueFalse['img_2'] != null && $TrueFalse['img_3'] != null && $TrueFalse['img_4'] == null){
                            $pdf->Cell(30,25, '',0,0 );
                            $pdf->MultiCell(20, 0, $pdf->Image("../assets/uploads/".$department."/".$user_div."/".$exam_id."/".$testIII."/".$TrueFalse['option_id']."/option1/".$TrueFalse['img_1'], $pdf->GetX(),$pdf->GetY(),25,25), 0, 0);
                            $pdf->Cell(30,25, '',0,0);
                            $pdf->MultiCell(20, 0, $pdf->Image("../assets/uploads/".$department."/".$user_div."/".$exam_id."/".$testIII."/".$TrueFalse['option_id']."/option2/".$TrueFalse['img_2'], $pdf->GetX(),$pdf->GetY(),25,25), 0, 0);
                            $pdf->Cell(30,25, '',0,0);
                            $pdf->MultiCell(20, 0, $pdf->Image("../assets/uploads/".$department."/".$user_div."/".$exam_id."/".$testIII."/".$TrueFalse['option_id']."/option3/".$TrueFalse['img_3'], $pdf->GetX(),$pdf->GetY(),25,25), 0, 0);
                            $pdf->Cell(30,25, '',0,0);
                            $pdf->Ln(30);
                        }
                        // 3 = null
                        else if ($TrueFalse['img_1'] != null && $TrueFalse['img_2'] != null && $TrueFalse['img_3'] == null && $TrueFalse['img_4'] != null){
                            $pdf->Cell(30,25, '',0,0, 'R');
                            $pdf->MultiCell(20, 0, $pdf->Image("../assets/uploads/".$department."/".$user_div."/".$exam_id."/".$testIII."/".$TrueFalse['option_id']."/option1/".$TrueFalse['img_1'], $pdf->GetX(),$pdf->GetY(),25,25), 0, 0);
                            $pdf->Cell(30,25, '',0,0, 'R');
                            $pdf->MultiCell(20, 0, $pdf->Image("../assets/uploads/".$department."/".$user_div."/".$exam_id."/".$testIII."/".$TrueFalse['option_id']."/option2/".$TrueFalse['img_2'], $pdf->GetX(),$pdf->GetY(),25,25), 0, 0);
                            $pdf->Cell(30,25, '',0,0, 'R');
                            $pdf->MultiCell(20, 0, $pdf->Image("../assets/uploads/".$department."/".$user_div."/".$exam_id."/".$testIII."/".$TrueFalse['option_id']."/option4/".$TrueFalse['img_4'], $pdf->GetX(),$pdf->GetY(),25,25), 0, 0);
                            $pdf->Cell(30,25, '',0,0, 'R');
                            $pdf->Ln(30);
                        }
                        // 2 = null
                        else if ($TrueFalse['img_1'] != null && $TrueFalse['img_2'] == null && $TrueFalse['img_3'] != null && $TrueFalse['img_4'] != null){
                            $pdf->Cell(30,25, '',0,0, 'R');
                            $pdf->MultiCell(20, 0, $pdf->Image("../assets/uploads/".$department."/".$user_div."/".$exam_id."/".$testIII."/".$TrueFalse['option_id']."/option1/".$TrueFalse['img_1'], $pdf->GetX(),$pdf->GetY(),25,25), 0, 0);
                            $pdf->Cell(30,25, '',0,0, 'R');
                            $pdf->MultiCell(20, 0, $pdf->Image("../assets/uploads/".$department."/".$user_div."/".$exam_id."/".$testIII."/".$TrueFalse['option_id']."/option3/".$TrueFalse['img_3'], $pdf->GetX(),$pdf->GetY(),25,25), 0, 0);
                            $pdf->Cell(30,25, '',0,0, 'R');
                            $pdf->MultiCell(20, 0, $pdf->Image("../assets/uploads/".$department."/".$user_div."/".$exam_id."/".$testIII."/".$TrueFalse['option_id']."/option4/".$TrueFalse['img_4'], $pdf->GetX(),$pdf->GetY(),25,25), 0, 0);
                            $pdf->Cell(30,25, '',0,0, 'R');
                            $pdf->Ln(30);
                        }
                        // 1 = null
                        else if ($TrueFalse['img_1'] == null && $TrueFalse['img_2'] != null && $TrueFalse['img_3'] != null && $TrueFalse['img_4'] != null){
                            $pdf->Cell(30,25, '',0,0, 'R');
                            $pdf->MultiCell(20, 0, $pdf->Image("../assets/uploads/".$department."/".$user_div."/".$exam_id."/".$testIII."/".$TrueFalse['option_id']."/option2/".$TrueFalse['img_2'], $pdf->GetX(),$pdf->GetY(),25,25), 0, 0);
                            $pdf->Cell(30,25, '',0,0, 'R');
                            $pdf->MultiCell(20, 0, $pdf->Image("../assets/uploads/".$department."/".$user_div."/".$exam_id."/".$testIII."/".$TrueFalse['option_id']."/option3/".$TrueFalse['img_3'], $pdf->GetX(),$pdf->GetY(),25,25), 0, 0);
                            $pdf->Cell(30,25, '',0,0, 'R');
                            $pdf->MultiCell(20, 0, $pdf->Image("../assets/uploads/".$department."/".$user_div."/".$exam_id."/".$testIII."/".$TrueFalse['option_id']."/option4/".$TrueFalse['img_4'], $pdf->GetX(),$pdf->GetY(),25,25), 0, 0);
                            $pdf->Cell(30,25, '',0,0, 'R');
                            $pdf->Ln(30);
                        }
                        //  1-2 || 2-1
                        else if ($TrueFalse['img_1'] != null && $TrueFalse['img_2'] != null && $TrueFalse['img_3'] == null && $TrueFalse['img_4'] == null){
                            $pdf->Cell(40,40, '',0,0, 'R');
                            $pdf->MultiCell(30, 0, $pdf->Image("../assets/uploads/".$department."/".$user_div."/".$exam_id."/".$testIII."/".$TrueFalse['option_id']."/option1/".$TrueFalse['img_1'], $pdf->GetX(),$pdf->GetY(),0, 40), 0, 0);
                            $pdf->Cell(40,40, '',0,0, 'R');
                            $pdf->MultiCell(30, 0, $pdf->Image("../assets/uploads/".$department."/".$user_div."/".$exam_id."/".$testIII."/".$TrueFalse['option_id']."/option2/".$TrueFalse['img_2'], $pdf->GetX(),$pdf->GetY(),0, 40), 0, 0);
                            $pdf->Cell(40,40, '',0,0, 'R');
                            $pdf->Ln(45);
                        }
                        //  1-3 || 3-1
                        else if ($TrueFalse['img_1'] != null && $TrueFalse['img_2'] == null && $TrueFalse['img_3'] != null && $TrueFalse['img_4'] == null){
                            $pdf->Cell(40,40, '',0,0, 'R');
                            $pdf->MultiCell(30, 0, $pdf->Image("../assets/uploads/".$department."/".$user_div."/".$exam_id."/".$testIII."/".$TrueFalse['option_id']."/option1/".$TrueFalse['img_1'], $pdf->GetX(),$pdf->GetY(),0, 40), 0, 0);
                            $pdf->Cell(40,40, '',0,0, 'R');
                            $pdf->MultiCell(30, 0, $pdf->Image("../assets/uploads/".$department."/".$user_div."/".$exam_id."/".$testIII."/".$TrueFalse['option_id']."/option3/".$TrueFalse['img_3'], $pdf->GetX(),$pdf->GetY(),0, 40), 0, 0);
                            $pdf->Cell(40,40, '',0,0, 'R');
                            $pdf->Ln(45);
                        }
                        //  1-4 || 4-1
                        else if ($TrueFalse['img_1'] != null && $TrueFalse['img_2'] == null && $TrueFalse['img_3'] == null && $TrueFalse['img_4'] != null){
                            $pdf->Cell(40,40, '',0,0, 'R');
                            $pdf->MultiCell(30, 0, $pdf->Image("../assets/uploads/".$department."/".$user_div."/".$exam_id."/".$testIII."/".$TrueFalse['option_id']."/option1/".$TrueFalse['img_1'], $pdf->GetX(),$pdf->GetY(),0, 40), 0, 0);
                            $pdf->Cell(40,40, '',0,0, 'R');
                            $pdf->MultiCell(30, 0, $pdf->Image("../assets/uploads/".$department."/".$user_div."/".$exam_id."/".$testIII."/".$TrueFalse['option_id']."/option4/".$TrueFalse['img_4'], $pdf->GetX(),$pdf->GetY(),0, 40), 0, 0);
                            $pdf->Cell(40,40, '',0,0, 'R');
                            $pdf->Ln(45);
                        }
                        //  2-3 || 3-2
                        else if ($TrueFalse['img_1'] == null && $TrueFalse['img_2'] != null && $TrueFalse['img_3'] != null && $TrueFalse['img_4'] == null){
                            $pdf->Cell(40,40, '',0,0, 'R');
                            $pdf->MultiCell(30, 0, $pdf->Image("../assets/uploads/".$department."/".$user_div."/".$exam_id."/".$testIII."/".$TrueFalse['option_id']."/option2/".$TrueFalse['img_2'], $pdf->GetX(),$pdf->GetY(),0, 40), 0, 0);
                            $pdf->Cell(40,40, '',0,0, 'R');
                            $pdf->MultiCell(30, 0, $pdf->Image("../assets/uploads/".$department."/".$user_div."/".$exam_id."/".$testIII."/".$TrueFalse['option_id']."/option3/".$TrueFalse['img_3'], $pdf->GetX(),$pdf->GetY(),0, 40), 0, 0);
                            $pdf->Cell(40,40, '',0,0, 'R');
                            $pdf->Ln(45);
                        }
                        //  2-4 || 4-2
                        else if ($TrueFalse['img_1'] == null && $TrueFalse['img_2'] != null && $TrueFalse['img_3'] == null && $TrueFalse['img_4'] != null){
                            $pdf->Cell(40,40, '',0,0, 'R');
                            $pdf->MultiCell(30, 0, $pdf->Image("../assets/uploads/".$department."/".$user_div."/".$exam_id."/".$testIII."/".$TrueFalse['option_id']."/option2/".$TrueFalse['img_2'], $pdf->GetX(),$pdf->GetY(),0, 40), 0, 0);
                            $pdf->Cell(40,40, '',0,0, 'R');
                            $pdf->MultiCell(30, 0, $pdf->Image("../assets/uploads/".$department."/".$user_div."/".$exam_id."/".$testIII."/".$TrueFalse['option_id']."/option4/".$TrueFalse['img_4'], $pdf->GetX(),$pdf->GetY(),0, 40), 0, 0);
                            $pdf->Cell(40,40, '',0,0, 'R');
                            $pdf->Ln(45);
                        }
                        //  3-4 || 4-3
                        else if ($TrueFalse['img_1'] == null && $TrueFalse['img_2'] == null && $TrueFalse['img_3'] != null && $TrueFalse['img_4'] != null){
                            $pdf->Cell(40,40, '',0,0, 'R');
                            $pdf->MultiCell(30, 0, $pdf->Image("../assets/uploads/".$department."/".$user_div."/".$exam_id."/".$testIII."/".$TrueFalse['option_id']."/option3/".$TrueFalse['img_3'], $pdf->GetX(),$pdf->GetY(),0, 40), 0, 0);
                            $pdf->Cell(40,40, '',0,0, 'R');
                            $pdf->MultiCell(30, 0, $pdf->Image("../assets/uploads/".$department."/".$user_div."/".$exam_id."/".$testIII."/".$TrueFalse['option_id']."/option4/".$TrueFalse['img_4'], $pdf->GetX(),$pdf->GetY(),0, 40), 0, 0);
                            $pdf->Cell(40,40, '',0,0, 'R');
                            $pdf->Ln(45);
                        }
                        else if ($TrueFalse['img_1'] != null && $TrueFalse['img_2'] == null && $TrueFalse['img_3'] == null && $TrueFalse['img_4'] == null){
                            $pdf->Cell(70,40, '',0,0, 'R');
                            $pdf->MultiCell(40, 0, $pdf->Image("../assets/uploads/".$department."/".$user_div."/".$exam_id."/".$testIII."/".$TrueFalse['option_id']."/option1/".$TrueFalse['img_1'], $pdf->GetX(),$pdf->GetY(),0, 40), 0, 0);
                            $pdf->Cell(80,40, '',0,0, 'R');
                            $pdf->Ln(45);
                        }
                        else if ($TrueFalse['img_1'] == null && $TrueFalse['img_2'] != null && $TrueFalse['img_3'] == null && $TrueFalse['img_4'] == null){
                            $pdf->Cell(70,0, '',0,0, 'R');
                            $pdf->MultiCell(40, 0, $pdf->Image("../assets/uploads/".$department."/".$user_div."/".$exam_id."/".$testIII."/".$TrueFalse['option_id']."/option2/".$TrueFalse['img_2'], $pdf->GetX(),$pdf->GetY(),0, 40), 0, 0);
                            $pdf->Cell(80,0, '',0,0, 'R');
                            $pdf->Ln(45);
                        }
                        else if ($TrueFalse['img_1'] == null && $TrueFalse['img_2'] == null && $TrueFalse['img_3'] != null && $TrueFalse['img_4'] == null){
                            $pdf->Cell(70,40, '',0,0, 'R');
                            $pdf->MultiCell(40, 0, $pdf->Image("../assets/uploads/".$department."/".$user_div."/".$exam_id."/".$testIII."/".$TrueFalse['option_id']."/option3/".$TrueFalse['img_3'], $pdf->GetX(),$pdf->GetY(),0, 40), 0, 0);
                            $pdf->Cell(80,40, '',0,0, 'R');
                            $pdf->Ln(45);
                        }
                        else if ($TrueFalse['img_1'] == null && $TrueFalse['img_2'] == null && $TrueFalse['img_3'] == null && $TrueFalse['img_4'] != null){
                            $pdf->Cell(70,40, '',0,0, 'R');
                            $pdf->MultiCell(40, 0, $pdf->Image("../assets/uploads/".$department."/".$user_div."/".$exam_id."/".$testIII."/".$TrueFalse['option_id']."/option4/".$TrueFalse['img_4'], $pdf->GetX(),$pdf->GetY(),0, 40), 0, 0);
                            $pdf->Cell(80,40, '',0,0, 'R');
                            $pdf->Ln(45);
                        }
                    }
                }
                else if ($testIII == 'Multiple Image'){
                    $pdf->Cell(15,5, 'Test III:',0,0);
                    $pdf->Cell(177,5, ' Multiple Choice (Images)',0,1);
                    $pdf->SetFont('Times','',13);
                    $pdf->MultiCell(177,5, 'Directions: Choose the BEST IMAGE answer for the following questions, and write your answer in the blank.',0,0);
                    $pdf->Ln(20);
                    while ($MultipleImage = $MultipleImageQuery->fetch()) {
                        $ans = $MultipleImage['result_ans'];
                        $pdf->SetMargins(10, 10, 10);
                        $pdf->SetTextColor(0,0,0);
                        $pdf->SetFont('Times','U',11);
                        if ($ans == 'I'){
                            $pdf->Cell(12,5, ' A ',0,0, 'C');
                        }else if ($ans == 'II'){
                            $pdf->Cell(12,5, ' B ',0,0, 'C');
                        }else if ($ans == 'III'){
                            $pdf->Cell(12,5, ' C ',0,0, 'C');
                        }else if ($ans == 'IV'){
                            $pdf->Cell(12,5, ' D  ',0,0, 'C');
                        }
                        $ans1 = $MultipleImage['correct_incorrect'];
                        if ($ans1 == 'correct'){
                            $check = "4";
                            $pdf->SetFont('ZapfDingbats','', 10);
                            $pdf->Cell(5,5, '       '.$check,0,0, 'R');

                        }else if ($ans1 == 'incorrect'){
                            $cross = "8";
                            $pdf->SetFont('ZapfDingbats','', 10);
                            $pdf->Cell(5,5, '       '.$cross,0,0, 'R');

                        }
                        $pdf->SetFont('Times','',11);
                        $pdf->Cell(6,5, $index++.'. ',0,0);
                        $pdf->MultiCell(166,5, $MultipleImage['question'].' ('.$MultipleImage['points'].' point/s)',0,1);
                        $pdf->Ln(5);
                        $pdf->SetTextColor(105,105,105);
                        $pdf->Cell(25,25, 'A: ',0,0, 'R');
                        $pdf->MultiCell(30, 0, $pdf->Image("../assets/uploads/".$department."/".$user_div."/".$exam_id."/Multiple Image/".$MultipleImage['option_id']."/option1/".$MultipleImage['img_1'], $pdf->GetX(),$pdf->GetY(),25,25), 0, 0);
                        $pdf->Cell(15,25, 'B: ',0,0, 'R');
                        $pdf->MultiCell(25, 0, $pdf->Image("../assets/uploads/".$department."/".$user_div."/".$exam_id."/Multiple Image/".$MultipleImage['option_id']."/option2/".$MultipleImage['img_2'], $pdf->GetX(),$pdf->GetY(),25,25), 0, 0);
                        $pdf->Cell(15,25, 'C: ',0,0, 'R');
                        $pdf->MultiCell(25, 0, $pdf->Image("../assets/uploads/".$department."/".$user_div."/".$exam_id."/Multiple Image/".$MultipleImage['option_id']."/option3/".$MultipleImage['img_3'], $pdf->GetX(),$pdf->GetY(),25,25), 0, 0);
                        $pdf->Cell(15,25, 'D: ',0,0, 'R');
                        $pdf->MultiCell(25, 0, $pdf->Image("../assets/uploads/".$department."/".$user_div."/".$exam_id."/Multiple Image/".$MultipleImage['option_id']."/option4/".$MultipleImage['img_4'], $pdf->GetX(),$pdf->GetY(),25,25), 0, 0);
                        $pdf->Ln(10);
                        $pdf->Ln(20);
                        $pdf->Ln(20);

                    }

                }
                else if ($testIII == 'Essay') {
                    $pdf->Cell(15,5, 'Test III:',0,0);
                    $pdf->Cell(177, 5, ' Essay', 0, 1);
                    $pdf->SetFont('Times', '', 13);
                    $pdf->MultiCell(177, 5, 'Directions: Answer the question to the best of your knowledge. Write your answer in the space provided', 0, 0);
                    $pdf->Ln(20);
                    while ($Essay = $EssayQuery->fetch()) {
                        $ans = $Essay['correct_incorrect'];
                        if ($ans == 'correct'){
                            $check = "4";
                            $pdf->SetFont('Times', '', 11);
                            $pdf->Cell(15,5, $Essay['score_points'].' Points',0,0);
                            $pdf->SetFont('ZapfDingbats','', 10);
                            $pdf->Cell(5,5, $check,0,0);

                        }else if ($ans == 'incorrect'){
                            $cross = "8";
                            $pdf->SetFont('Times', '', 11);
                            $pdf->Cell(15,5, $Essay['score_points'].' Points',0,0);
                            $pdf->SetFont('ZapfDingbats','', 10);
                            $pdf->Cell(5,5, $cross,0,0);

                        }
                        $pdf->SetFont('Times', '', 11);
                        $pdf->Cell(6, 5, $index++ . '. ', 0, 0);
                        $pdf->MultiCell(160, 5, $Essay['question'].' ('.$Essay['points'].' point/s)', 0, 1);
                        $pdf->Ln(5);
                        $pdf->Cell(20, 5, '', 0, 0);
                        $pdf->SetFont('Times', 'U', 11);
                        $pdf->MultiCell(166, 5, '           '. $Essay['result_ans'] . '              ', 0, 1);
                        $pdf->Ln(5);
                    }
                }
                $pdf->Ln(10);
            }
            // Test III End

            // Test IV Start
            // $pdf->AddPage();
            $pdf->SetTextColor(0,0,0);
            $pdf->SetFont('Times','B',13);
            if ($testIV != null || $testIV != '' || $testIV != 0){
                if ($testIV == 'Multiple Choice'){
                    $pdf->Cell(15,5, 'Test IV:',0,0);
                    $pdf->Cell(177,5, ' Multiple Choice',0,1);
                    $pdf->SetFont('Times','',13);
                    $pdf->Cell(177,5, 'Directions: Choose the BEST answer for the following questions.',0,0);
                    $pdf->Ln(20);
                    while ($MultipleChoice = $MultipleChoiceQuery->fetch()){
                        $ans = $MultipleChoice['result_ans'];
                        $cans = $MultipleChoice['ans'];
                        $pdf->SetMargins(10, 10, 10);
                        $pdf->SetFont('Times','U',11);
                        if ($ans == $MultipleChoice['option_1']){
                            $pdf->Cell(12,5, '     '.'A'.'     ',0,0, 'C');
                        }else if ($ans == $MultipleChoice['option_2']){
                            $pdf->Cell(12,5, '     '.'B'.'     ',0,0, 'C');
                        }else if ($ans == $MultipleChoice['option_3']){
                            $pdf->Cell(12,5, '     '.'C'.'     ',0,0, 'C');
                        }else if ($ans == $MultipleChoice['option_4']){
                            $pdf->Cell(12,5, '     '.'D'.'     ',0,0, 'C');
                        }
                        if ($cans == $ans){
                            $check = "4";
                            $pdf->SetFont('ZapfDingbats','', 10);
                            $pdf->Cell(6,5, $check,0,0);
                        }else if ($cans != $ans){
                            $cross = "8";
                            $pdf->SetFont('ZapfDingbats','', 10);
                            $pdf->Cell(6,5, $cross,0,0);
                        }
                        $pdf->SetFont('Times','',11);
                        $pdf->Cell(6,5, $index++.'. ',0,0);
                        $pdf->MultiCell(166,5, $MultipleChoice['question'].' ('.$MultipleChoice['points'].' point/s)',0,1);
                        $pdf->Ln(5);
                        $pdf->SetTextColor(105,105,105);
                        if ($MultipleChoice['img_1'] != null && $MultipleChoice['img_2'] != null && $MultipleChoice['img_3'] != null && $MultipleChoice['img_4'] != null){
                            $pdf->Cell(20,25, '',0,0, 'R');
                            $pdf->MultiCell(25, 0, $pdf->Image("../assets/uploads/".$department."/".$user_div."/".$exam_id."/".$testIV."/".$MultipleChoice['option_id']."/option1/".$MultipleChoice['img_1'], $pdf->GetX(),$pdf->GetY(),25,25), 0, 0);
                            $pdf->Cell(15,25, '',0,0, 'R');
                            $pdf->MultiCell(25, 0, $pdf->Image("../assets/uploads/".$department."/".$user_div."/".$exam_id."/".$testIV."/".$MultipleChoice['option_id']."/option2/".$MultipleChoice['img_2'], $pdf->GetX(),$pdf->GetY(),25,25), 0, 0);
                            $pdf->Cell(15,25, '',0,0, 'R');
                            $pdf->MultiCell(25, 0, $pdf->Image("../assets/uploads/".$department."/".$user_div."/".$exam_id."/".$testIV."/".$MultipleChoice['option_id']."/option3/".$MultipleChoice['img_3'], $pdf->GetX(),$pdf->GetY(),25,25), 0, 0);
                            $pdf->Cell(15,25, '',0,0, 'R');
                            $pdf->MultiCell(25, 0, $pdf->Image("../assets/uploads/".$department."/".$user_div."/".$exam_id."/".$testIV."/".$MultipleChoice['option_id']."/option4/".$MultipleChoice['img_4'], $pdf->GetX(),$pdf->GetY(),25,25), 0, 0);
                            $pdf->Cell(15,25, '',0,1, 'R');
                            $pdf->Ln(5);
                        }
                        // 4 = null
                        else if ($MultipleChoice['img_1'] != null && $MultipleChoice['img_2'] != null && $MultipleChoice['img_3'] != null && $MultipleChoice['img_4'] == null){
                            $pdf->Cell(30,25, '',0,0 );
                            $pdf->MultiCell(20, 0, $pdf->Image("../assets/uploads/".$department."/".$user_div."/".$exam_id."/".$testIV."/".$MultipleChoice['option_id']."/option1/".$MultipleChoice['img_1'], $pdf->GetX(),$pdf->GetY(),25,25), 0, 0);
                            $pdf->Cell(30,25, '',0,0);
                            $pdf->MultiCell(20, 0, $pdf->Image("../assets/uploads/".$department."/".$user_div."/".$exam_id."/".$testIV."/".$MultipleChoice['option_id']."/option2/".$MultipleChoice['img_2'], $pdf->GetX(),$pdf->GetY(),25,25), 0, 0);
                            $pdf->Cell(30,25, '',0,0);
                            $pdf->MultiCell(20, 0, $pdf->Image("../assets/uploads/".$department."/".$user_div."/".$exam_id."/".$testIV."/".$MultipleChoice['option_id']."/option3/".$MultipleChoice['img_3'], $pdf->GetX(),$pdf->GetY(),25,25), 0, 0);
                            $pdf->Cell(30,25, '',0,0);
                            $pdf->Ln(30);
                        }
                        // 3 = null
                        else if ($MultipleChoice['img_1'] != null && $MultipleChoice['img_2'] != null && $MultipleChoice['img_3'] == null && $MultipleChoice['img_4'] != null){
                            $pdf->Cell(30,25, '',0,0, 'R');
                            $pdf->MultiCell(20, 0, $pdf->Image("../assets/uploads/".$department."/".$user_div."/".$exam_id."/".$testIV."/".$MultipleChoice['option_id']."/option1/".$MultipleChoice['img_1'], $pdf->GetX(),$pdf->GetY(),25,25), 0, 0);
                            $pdf->Cell(30,25, '',0,0, 'R');
                            $pdf->MultiCell(20, 0, $pdf->Image("../assets/uploads/".$department."/".$user_div."/".$exam_id."/".$testIV."/".$MultipleChoice['option_id']."/option2/".$MultipleChoice['img_2'], $pdf->GetX(),$pdf->GetY(),25,25), 0, 0);
                            $pdf->Cell(30,25, '',0,0, 'R');
                            $pdf->MultiCell(20, 0, $pdf->Image("../assets/uploads/".$department."/".$user_div."/".$exam_id."/".$testIV."/".$MultipleChoice['option_id']."/option4/".$MultipleChoice['img_4'], $pdf->GetX(),$pdf->GetY(),25,25), 0, 0);
                            $pdf->Cell(30,25, '',0,0, 'R');
                            $pdf->Ln(30);
                        }
                        // 2 = null
                        else if ($MultipleChoice['img_1'] != null && $MultipleChoice['img_2'] == null && $MultipleChoice['img_3'] != null && $MultipleChoice['img_4'] != null){
                            $pdf->Cell(30,25, '',0,0, 'R');
                            $pdf->MultiCell(20, 0, $pdf->Image("../assets/uploads/".$department."/".$user_div."/".$exam_id."/".$testIV."/".$MultipleChoice['option_id']."/option1/".$MultipleChoice['img_1'], $pdf->GetX(),$pdf->GetY(),25,25), 0, 0);
                            $pdf->Cell(30,25, '',0,0, 'R');
                            $pdf->MultiCell(20, 0, $pdf->Image("../assets/uploads/".$department."/".$user_div."/".$exam_id."/".$testIV."/".$MultipleChoice['option_id']."/option3/".$MultipleChoice['img_3'], $pdf->GetX(),$pdf->GetY(),25,25), 0, 0);
                            $pdf->Cell(30,25, '',0,0, 'R');
                            $pdf->MultiCell(20, 0, $pdf->Image("../assets/uploads/".$department."/".$user_div."/".$exam_id."/".$testIV."/".$MultipleChoice['option_id']."/option4/".$MultipleChoice['img_4'], $pdf->GetX(),$pdf->GetY(),25,25), 0, 0);
                            $pdf->Cell(30,25, '',0,0, 'R');
                            $pdf->Ln(30);
                        }
                        // 1 = null
                        else if ($MultipleChoice['img_1'] == null && $MultipleChoice['img_2'] != null && $MultipleChoice['img_3'] != null && $MultipleChoice['img_4'] != null){
                            $pdf->Cell(30,25, '',0,0, 'R');
                            $pdf->MultiCell(20, 0, $pdf->Image("../assets/uploads/".$department."/".$user_div."/".$exam_id."/".$testIV."/".$MultipleChoice['option_id']."/option2/".$MultipleChoice['img_2'], $pdf->GetX(),$pdf->GetY(),25,25), 0, 0);
                            $pdf->Cell(30,25, '',0,0, 'R');
                            $pdf->MultiCell(20, 0, $pdf->Image("../assets/uploads/".$department."/".$user_div."/".$exam_id."/".$testIV."/".$MultipleChoice['option_id']."/option3/".$MultipleChoice['img_3'], $pdf->GetX(),$pdf->GetY(),25,25), 0, 0);
                            $pdf->Cell(30,25, '',0,0, 'R');
                            $pdf->MultiCell(20, 0, $pdf->Image("../assets/uploads/".$department."/".$user_div."/".$exam_id."/".$testIV."/".$MultipleChoice['option_id']."/option4/".$MultipleChoice['img_4'], $pdf->GetX(),$pdf->GetY(),25,25), 0, 0);
                            $pdf->Cell(30,25, '',0,0, 'R');
                            $pdf->Ln(30);
                        }
                        //  1-2 || 2-1
                        else if ($MultipleChoice['img_1'] != null && $MultipleChoice['img_2'] != null && $MultipleChoice['img_3'] == null && $MultipleChoice['img_4'] == null){
                            $pdf->Cell(40,40, '',0,0, 'R');
                            $pdf->MultiCell(30, 0, $pdf->Image("../assets/uploads/".$department."/".$user_div."/".$exam_id."/".$testIV."/".$MultipleChoice['option_id']."/option1/".$MultipleChoice['img_1'], $pdf->GetX(),$pdf->GetY(),0, 40), 0, 0);
                            $pdf->Cell(40,40, '',0,0, 'R');
                            $pdf->MultiCell(30, 0, $pdf->Image("../assets/uploads/".$department."/".$user_div."/".$exam_id."/".$testIV."/".$MultipleChoice['option_id']."/option2/".$MultipleChoice['img_2'], $pdf->GetX(),$pdf->GetY(),0, 40), 0, 0);
                            $pdf->Cell(40,40, '',0,0, 'R');
                            $pdf->Ln(45);
                        }
                        //  1-3 || 3-1
                        else if ($MultipleChoice['img_1'] != null && $MultipleChoice['img_2'] == null && $MultipleChoice['img_3'] != null && $MultipleChoice['img_4'] == null){
                            $pdf->Cell(40,40, '',0,0, 'R');
                            $pdf->MultiCell(30, 0, $pdf->Image("../assets/uploads/".$department."/".$user_div."/".$exam_id."/".$testIV."/".$MultipleChoice['option_id']."/option1/".$MultipleChoice['img_1'], $pdf->GetX(),$pdf->GetY(),0, 40), 0, 0);
                            $pdf->Cell(40,40, '',0,0, 'R');
                            $pdf->MultiCell(30, 0, $pdf->Image("../assets/uploads/".$department."/".$user_div."/".$exam_id."/".$testIV."/".$MultipleChoice['option_id']."/option3/".$MultipleChoice['img_3'], $pdf->GetX(),$pdf->GetY(),0, 40), 0, 0);
                            $pdf->Cell(40,40, '',0,0, 'R');
                            $pdf->Ln(45);
                        }
                        //  1-4 || 4-1
                        else if ($MultipleChoice['img_1'] != null && $MultipleChoice['img_2'] == null && $MultipleChoice['img_3'] == null && $MultipleChoice['img_4'] != null){
                            $pdf->Cell(40,40, '',0,0, 'R');
                            $pdf->MultiCell(30, 0, $pdf->Image("../assets/uploads/".$department."/".$user_div."/".$exam_id."/".$testIV."/".$MultipleChoice['option_id']."/option1/".$MultipleChoice['img_1'], $pdf->GetX(),$pdf->GetY(),0, 40), 0, 0);
                            $pdf->Cell(40,40, '',0,0, 'R');
                            $pdf->MultiCell(30, 0, $pdf->Image("../assets/uploads/".$department."/".$user_div."/".$exam_id."/".$testIV."/".$MultipleChoice['option_id']."/option4/".$MultipleChoice['img_4'], $pdf->GetX(),$pdf->GetY(),0, 40), 0, 0);
                            $pdf->Cell(40,40, '',0,0, 'R');
                            $pdf->Ln(45);
                        }
                        //  2-3 || 3-2
                        else if ($MultipleChoice['img_1'] == null && $MultipleChoice['img_2'] != null && $MultipleChoice['img_3'] != null && $MultipleChoice['img_4'] == null){
                            $pdf->Cell(40,40, '',0,0, 'R');
                            $pdf->MultiCell(30, 0, $pdf->Image("../assets/uploads/".$department."/".$user_div."/".$exam_id."/".$testIV."/".$MultipleChoice['option_id']."/option2/".$MultipleChoice['img_2'], $pdf->GetX(),$pdf->GetY(),0, 40), 0, 0);
                            $pdf->Cell(40,40, '',0,0, 'R');
                            $pdf->MultiCell(30, 0, $pdf->Image("../assets/uploads/".$department."/".$user_div."/".$exam_id."/".$testIV."/".$MultipleChoice['option_id']."/option3/".$MultipleChoice['img_3'], $pdf->GetX(),$pdf->GetY(),0, 40), 0, 0);
                            $pdf->Cell(40,40, '',0,0, 'R');
                            $pdf->Ln(45);
                        }
                        //  2-4 || 4-2
                        else if ($MultipleChoice['img_1'] == null && $MultipleChoice['img_2'] != null && $MultipleChoice['img_3'] == null && $MultipleChoice['img_4'] != null){
                            $pdf->Cell(40,40, '',0,0, 'R');
                            $pdf->MultiCell(30, 0, $pdf->Image("../assets/uploads/".$department."/".$user_div."/".$exam_id."/".$testIV."/".$MultipleChoice['option_id']."/option2/".$MultipleChoice['img_2'], $pdf->GetX(),$pdf->GetY(),0, 40), 0, 0);
                            $pdf->Cell(40,40, '',0,0, 'R');
                            $pdf->MultiCell(30, 0, $pdf->Image("../assets/uploads/".$department."/".$user_div."/".$exam_id."/".$testIV."/".$MultipleChoice['option_id']."/option4/".$MultipleChoice['img_4'], $pdf->GetX(),$pdf->GetY(),0, 40), 0, 0);
                            $pdf->Cell(40,40, '',0,0, 'R');
                            $pdf->Ln(45);
                        }
                        //  3-4 || 4-3
                        else if ($MultipleChoice['img_1'] == null && $MultipleChoice['img_2'] == null && $MultipleChoice['img_3'] != null && $MultipleChoice['img_4'] != null){
                            $pdf->Cell(40,40, '',0,0, 'R');
                            $pdf->MultiCell(30, 0, $pdf->Image("../assets/uploads/".$department."/".$user_div."/".$exam_id."/".$testIV."/".$MultipleChoice['option_id']."/option3/".$MultipleChoice['img_3'], $pdf->GetX(),$pdf->GetY(),0, 40), 0, 0);
                            $pdf->Cell(40,40, '',0,0, 'R');
                            $pdf->MultiCell(30, 0, $pdf->Image("../assets/uploads/".$department."/".$user_div."/".$exam_id."/".$testIV."/".$MultipleChoice['option_id']."/option4/".$MultipleChoice['img_4'], $pdf->GetX(),$pdf->GetY(),0, 40), 0, 0);
                            $pdf->Cell(40,40, '',0,0, 'R');
                            $pdf->Ln(45);
                        }
                        else if ($MultipleChoice['img_1'] != null && $MultipleChoice['img_2'] == null && $MultipleChoice['img_3'] == null && $MultipleChoice['img_4'] == null){
                            $pdf->Cell(70,40, '',0,0, 'R');
                            $pdf->MultiCell(40, 0, $pdf->Image("../assets/uploads/".$department."/".$user_div."/".$exam_id."/".$testIV."/".$MultipleChoice['option_id']."/option1/".$MultipleChoice['img_1'], $pdf->GetX(),$pdf->GetY(),0, 40), 0, 0);
                            $pdf->Cell(80,40, '',0,0, 'R');
                            $pdf->Ln(45);
                        }
                        else if ($MultipleChoice['img_1'] == null && $MultipleChoice['img_2'] != null && $MultipleChoice['img_3'] == null && $MultipleChoice['img_4'] == null){
                            $pdf->Cell(70,0, '',0,0, 'R');
                            $pdf->MultiCell(40, 0, $pdf->Image("../assets/uploads/".$department."/".$user_div."/".$exam_id."/".$testIV."/".$MultipleChoice['option_id']."/option2/".$MultipleChoice['img_2'], $pdf->GetX(),$pdf->GetY(),0, 40), 0, 0);
                            $pdf->Cell(80,0, '',0,0, 'R');
                            $pdf->Ln(45);
                        }
                        else if ($MultipleChoice['img_1'] == null && $MultipleChoice['img_2'] == null && $MultipleChoice['img_3'] != null && $MultipleChoice['img_4'] == null){
                            $pdf->Cell(70,40, '',0,0, 'R');
                            $pdf->MultiCell(40, 0, $pdf->Image("../assets/uploads/".$department."/".$user_div."/".$exam_id."/".$testIV."/".$MultipleChoice['option_id']."/option3/".$MultipleChoice['img_3'], $pdf->GetX(),$pdf->GetY(),0, 40), 0, 0);
                            $pdf->Cell(80,40, '',0,0, 'R');
                            $pdf->Ln(45);
                        }
                        else if ($MultipleChoice['img_1'] == null && $MultipleChoice['img_2'] == null && $MultipleChoice['img_3'] == null && $MultipleChoice['img_4'] != null){
                            $pdf->Cell(70,40, '',0,0, 'R');
                            $pdf->MultiCell(40, 0, $pdf->Image("../assets/uploads/".$department."/".$user_div."/".$exam_id."/".$testIV."/".$MultipleChoice['option_id']."/option4/".$MultipleChoice['img_4'], $pdf->GetX(),$pdf->GetY(),0, 40), 0, 0);
                            $pdf->Cell(80,40, '',0,0, 'R');
                            $pdf->Ln(45);
                        }
                        $pdf->Cell(24,5, 'A.',0,0, 'R');
                        $pdf->MultiCell(76,5, $MultipleChoice['option_1'],0,0);
                        $pdf->Cell(7,5, 'C.',0,0, 'R');
                        $pdf->MultiCell(76, 5, $MultipleChoice['option_3'],0,1);
                        $pdf->Ln(2);
                        $pdf->Cell(24,5, 'B.',0,0, 'R');
                        $pdf->MultiCell(76, 5, $MultipleChoice['option_2'],0,0);
                        $pdf->Cell(7,5, 'D.',0,0, 'R');
                        $pdf->MultiCell(76, 5, $MultipleChoice['option_4'],0,1);
                        $pdf->Ln(5);
                        $pdf->SetTextColor(0,0,0);
                    }
                }
                else if ($testIV == 'Short Answer'){
                    $pdf->Cell(15,5, 'Test IV:',0,0);
                    $pdf->Cell(177,5, ' Short Answer',0,1);
                    $pdf->SetFont('Times','',13);
                    $pdf->MultiCell(177,5, 'Directions: Using your own words, answer each question in the space provided',0,0);
                    $pdf->Ln(20);

                    while ($ShortAnswer = $ShortAnswerQuery->fetch()){
                        $ans = $ShortAnswer['correct_incorrect'];
                        if ($ans == 'correct'){
                            $check = "4";
                            $pdf->SetFont('ZapfDingbats','', 10);
                            $pdf->Cell(5,5, $check,0,0);

                        }else if ($ans == 'incorrect'){
                            $cross = "8";
                            $pdf->SetFont('ZapfDingbats','', 10);
                            $pdf->Cell(5,5, $cross,0,0);

                        }
                        $pdf->SetFont('Times','',11);
                        $pdf->Cell(6,5, $index++.'. ',0,0);
                        $pdf->MultiCell(166,5, $ShortAnswer['question'].' ('.$ShortAnswer['points'].' point/s)',0,1);
                        $pdf->Ln(5);
                        $pdf->Cell(11,5, '',0,0);
                        $pdf->SetFont('Times','U',11);
                        $pdf->MultiCell(180,5, '  '.$ShortAnswer['result_ans'].'                          ',0,1);
                        $pdf->Ln(5);
                    }
                }
                else if ($testIV == 'True/False'){
                    $pdf->Cell(15,5, 'Test IV:',0,0);
                    $pdf->Cell(177,5, ' True or False',0,1);
                    $pdf->SetFont('Times','',13);
                    $pdf->MultiCell(177,5, 'Directions: Read the statements carefully and tell whether the statement is TRUE or FALSE. Choose TRUE if the statement is correct and FALSE if otherwise.',0,0);
                    $pdf->Ln(20);
                    while ($TrueFalse = $TrueFalseQuery->fetch()){
                        $ans = $TrueFalse['result_ans'];
                        $cans = $TrueFalse['ans'];
                        $pdf->SetMargins(10, 10, 10);
                        $pdf->SetFont('Times','U',11);
                        $pdf->Cell(12,5, '  '.$TrueFalse['result_ans'].'  ',0,0, 'C');
                        if ($cans == $ans){
                            $check = "4";
                            $pdf->SetFont('ZapfDingbats','', 10);
                            $pdf->Cell(6,5, $check,0,0);
                        }else if ($cans != $ans){
                            $cross = "8";
                            $pdf->SetFont('ZapfDingbats','', 10);
                            $pdf->Cell(6,5, $cross,0,0);
                        }
                        $pdf->SetFont('Times','',11);
                        $pdf->Cell(6,5, $index++.'. ',0,0);
                        $pdf->MultiCell(166,5, $TrueFalse['question'].' ('.$TrueFalse['points'].' point/s)', 0, 1);
                        $pdf->Ln(5);

                        if ($TrueFalse['img_1'] != null && $TrueFalse['img_2'] != null && $TrueFalse['img_3'] != null && $TrueFalse['img_4'] != null){
                            $pdf->Cell(20,25, '',0,0, 'R');
                            $pdf->MultiCell(25, 0, $pdf->Image("../assets/uploads/".$department."/".$user_div."/".$exam_id."/".$testIV."/".$TrueFalse['option_id']."/option1/".$TrueFalse['img_1'], $pdf->GetX(),$pdf->GetY(),25,25), 0, 0);
                            $pdf->Cell(15,25, '',0,0, 'R');
                            $pdf->MultiCell(25, 0, $pdf->Image("../assets/uploads/".$department."/".$user_div."/".$exam_id."/".$testIV."/".$TrueFalse['option_id']."/option2/".$TrueFalse['img_2'], $pdf->GetX(),$pdf->GetY(),25,25), 0, 0);
                            $pdf->Cell(15,25, '',0,0, 'R');
                            $pdf->MultiCell(25, 0, $pdf->Image("../assets/uploads/".$department."/".$user_div."/".$exam_id."/".$testIV."/".$TrueFalse['option_id']."/option3/".$TrueFalse['img_3'], $pdf->GetX(),$pdf->GetY(),25,25), 0, 0);
                            $pdf->Cell(15,25, '',0,0, 'R');
                            $pdf->MultiCell(25, 0, $pdf->Image("../assets/uploads/".$department."/".$user_div."/".$exam_id."/".$testIV."/".$TrueFalse['option_id']."/option4/".$TrueFalse['img_4'], $pdf->GetX(),$pdf->GetY(),25,25), 0, 0);
                            $pdf->Cell(15,25, '',0,1, 'R');
                            $pdf->Ln(5);
                        }
                        // 4 = null
                        else if ($TrueFalse['img_1'] != null && $TrueFalse['img_2'] != null && $TrueFalse['img_3'] != null && $TrueFalse['img_4'] == null){
                            $pdf->Cell(30,25, '',0,0 );
                            $pdf->MultiCell(20, 0, $pdf->Image("../assets/uploads/".$department."/".$user_div."/".$exam_id."/".$testIV."/".$TrueFalse['option_id']."/option1/".$TrueFalse['img_1'], $pdf->GetX(),$pdf->GetY(),25,25), 0, 0);
                            $pdf->Cell(30,25, '',0,0);
                            $pdf->MultiCell(20, 0, $pdf->Image("../assets/uploads/".$department."/".$user_div."/".$exam_id."/".$testIV."/".$TrueFalse['option_id']."/option2/".$TrueFalse['img_2'], $pdf->GetX(),$pdf->GetY(),25,25), 0, 0);
                            $pdf->Cell(30,25, '',0,0);
                            $pdf->MultiCell(20, 0, $pdf->Image("../assets/uploads/".$department."/".$user_div."/".$exam_id."/".$testIV."/".$TrueFalse['option_id']."/option3/".$TrueFalse['img_3'], $pdf->GetX(),$pdf->GetY(),25,25), 0, 0);
                            $pdf->Cell(30,25, '',0,0);
                            $pdf->Ln(30);
                        }
                        // 3 = null
                        else if ($TrueFalse['img_1'] != null && $TrueFalse['img_2'] != null && $TrueFalse['img_3'] == null && $TrueFalse['img_4'] != null){
                            $pdf->Cell(30,25, '',0,0, 'R');
                            $pdf->MultiCell(20, 0, $pdf->Image("../assets/uploads/".$department."/".$user_div."/".$exam_id."/".$testIV."/".$TrueFalse['option_id']."/option1/".$TrueFalse['img_1'], $pdf->GetX(),$pdf->GetY(),25,25), 0, 0);
                            $pdf->Cell(30,25, '',0,0, 'R');
                            $pdf->MultiCell(20, 0, $pdf->Image("../assets/uploads/".$department."/".$user_div."/".$exam_id."/".$testIV."/".$TrueFalse['option_id']."/option2/".$TrueFalse['img_2'], $pdf->GetX(),$pdf->GetY(),25,25), 0, 0);
                            $pdf->Cell(30,25, '',0,0, 'R');
                            $pdf->MultiCell(20, 0, $pdf->Image("../assets/uploads/".$department."/".$user_div."/".$exam_id."/".$testIV."/".$TrueFalse['option_id']."/option4/".$TrueFalse['img_4'], $pdf->GetX(),$pdf->GetY(),25,25), 0, 0);
                            $pdf->Cell(30,25, '',0,0, 'R');
                            $pdf->Ln(30);
                        }
                        // 2 = null
                        else if ($TrueFalse['img_1'] != null && $TrueFalse['img_2'] == null && $TrueFalse['img_3'] != null && $TrueFalse['img_4'] != null){
                            $pdf->Cell(30,25, '',0,0, 'R');
                            $pdf->MultiCell(20, 0, $pdf->Image("../assets/uploads/".$department."/".$user_div."/".$exam_id."/".$testIV."/".$TrueFalse['option_id']."/option1/".$TrueFalse['img_1'], $pdf->GetX(),$pdf->GetY(),25,25), 0, 0);
                            $pdf->Cell(30,25, '',0,0, 'R');
                            $pdf->MultiCell(20, 0, $pdf->Image("../assets/uploads/".$department."/".$user_div."/".$exam_id."/".$testIV."/".$TrueFalse['option_id']."/option3/".$TrueFalse['img_3'], $pdf->GetX(),$pdf->GetY(),25,25), 0, 0);
                            $pdf->Cell(30,25, '',0,0, 'R');
                            $pdf->MultiCell(20, 0, $pdf->Image("../assets/uploads/".$department."/".$user_div."/".$exam_id."/".$testIV."/".$TrueFalse['option_id']."/option4/".$TrueFalse['img_4'], $pdf->GetX(),$pdf->GetY(),25,25), 0, 0);
                            $pdf->Cell(30,25, '',0,0, 'R');
                            $pdf->Ln(30);
                        }
                        // 1 = null
                        else if ($TrueFalse['img_1'] == null && $TrueFalse['img_2'] != null && $TrueFalse['img_3'] != null && $TrueFalse['img_4'] != null){
                            $pdf->Cell(30,25, '',0,0, 'R');
                            $pdf->MultiCell(20, 0, $pdf->Image("../assets/uploads/".$department."/".$user_div."/".$exam_id."/".$testIV."/".$TrueFalse['option_id']."/option2/".$TrueFalse['img_2'], $pdf->GetX(),$pdf->GetY(),25,25), 0, 0);
                            $pdf->Cell(30,25, '',0,0, 'R');
                            $pdf->MultiCell(20, 0, $pdf->Image("../assets/uploads/".$department."/".$user_div."/".$exam_id."/".$testIV."/".$TrueFalse['option_id']."/option3/".$TrueFalse['img_3'], $pdf->GetX(),$pdf->GetY(),25,25), 0, 0);
                            $pdf->Cell(30,25, '',0,0, 'R');
                            $pdf->MultiCell(20, 0, $pdf->Image("../assets/uploads/".$department."/".$user_div."/".$exam_id."/".$testIV."/".$TrueFalse['option_id']."/option4/".$TrueFalse['img_4'], $pdf->GetX(),$pdf->GetY(),25,25), 0, 0);
                            $pdf->Cell(30,25, '',0,0, 'R');
                            $pdf->Ln(30);
                        }
                        //  1-2 || 2-1
                        else if ($TrueFalse['img_1'] != null && $TrueFalse['img_2'] != null && $TrueFalse['img_3'] == null && $TrueFalse['img_4'] == null){
                            $pdf->Cell(40,40, '',0,0, 'R');
                            $pdf->MultiCell(30, 0, $pdf->Image("../assets/uploads/".$department."/".$user_div."/".$exam_id."/".$testIV."/".$TrueFalse['option_id']."/option1/".$TrueFalse['img_1'], $pdf->GetX(),$pdf->GetY(),0, 40), 0, 0);
                            $pdf->Cell(40,40, '',0,0, 'R');
                            $pdf->MultiCell(30, 0, $pdf->Image("../assets/uploads/".$department."/".$user_div."/".$exam_id."/".$testIV."/".$TrueFalse['option_id']."/option2/".$TrueFalse['img_2'], $pdf->GetX(),$pdf->GetY(),0, 40), 0, 0);
                            $pdf->Cell(40,40, '',0,0, 'R');
                            $pdf->Ln(45);
                        }
                        //  1-3 || 3-1
                        else if ($TrueFalse['img_1'] != null && $TrueFalse['img_2'] == null && $TrueFalse['img_3'] != null && $TrueFalse['img_4'] == null){
                            $pdf->Cell(40,40, '',0,0, 'R');
                            $pdf->MultiCell(30, 0, $pdf->Image("../assets/uploads/".$department."/".$user_div."/".$exam_id."/".$testIV."/".$TrueFalse['option_id']."/option1/".$TrueFalse['img_1'], $pdf->GetX(),$pdf->GetY(),0, 40), 0, 0);
                            $pdf->Cell(40,40, '',0,0, 'R');
                            $pdf->MultiCell(30, 0, $pdf->Image("../assets/uploads/".$department."/".$user_div."/".$exam_id."/".$testIV."/".$TrueFalse['option_id']."/option3/".$TrueFalse['img_3'], $pdf->GetX(),$pdf->GetY(),0, 40), 0, 0);
                            $pdf->Cell(40,40, '',0,0, 'R');
                            $pdf->Ln(45);
                        }
                        //  1-4 || 4-1
                        else if ($TrueFalse['img_1'] != null && $TrueFalse['img_2'] == null && $TrueFalse['img_3'] == null && $TrueFalse['img_4'] != null){
                            $pdf->Cell(40,40, '',0,0, 'R');
                            $pdf->MultiCell(30, 0, $pdf->Image("../assets/uploads/".$department."/".$user_div."/".$exam_id."/".$testIV."/".$TrueFalse['option_id']."/option1/".$TrueFalse['img_1'], $pdf->GetX(),$pdf->GetY(),0, 40), 0, 0);
                            $pdf->Cell(40,40, '',0,0, 'R');
                            $pdf->MultiCell(30, 0, $pdf->Image("../assets/uploads/".$department."/".$user_div."/".$exam_id."/".$testIV."/".$TrueFalse['option_id']."/option4/".$TrueFalse['img_4'], $pdf->GetX(),$pdf->GetY(),0, 40), 0, 0);
                            $pdf->Cell(40,40, '',0,0, 'R');
                            $pdf->Ln(45);
                        }
                        //  2-3 || 3-2
                        else if ($TrueFalse['img_1'] == null && $TrueFalse['img_2'] != null && $TrueFalse['img_3'] != null && $TrueFalse['img_4'] == null){
                            $pdf->Cell(40,40, '',0,0, 'R');
                            $pdf->MultiCell(30, 0, $pdf->Image("../assets/uploads/".$department."/".$user_div."/".$exam_id."/".$testIV."/".$TrueFalse['option_id']."/option2/".$TrueFalse['img_2'], $pdf->GetX(),$pdf->GetY(),0, 40), 0, 0);
                            $pdf->Cell(40,40, '',0,0, 'R');
                            $pdf->MultiCell(30, 0, $pdf->Image("../assets/uploads/".$department."/".$user_div."/".$exam_id."/".$testIV."/".$TrueFalse['option_id']."/option3/".$TrueFalse['img_3'], $pdf->GetX(),$pdf->GetY(),0, 40), 0, 0);
                            $pdf->Cell(40,40, '',0,0, 'R');
                            $pdf->Ln(45);
                        }
                        //  2-4 || 4-2
                        else if ($TrueFalse['img_1'] == null && $TrueFalse['img_2'] != null && $TrueFalse['img_3'] == null && $TrueFalse['img_4'] != null){
                            $pdf->Cell(40,40, '',0,0, 'R');
                            $pdf->MultiCell(30, 0, $pdf->Image("../assets/uploads/".$department."/".$user_div."/".$exam_id."/".$testIV."/".$TrueFalse['option_id']."/option2/".$TrueFalse['img_2'], $pdf->GetX(),$pdf->GetY(),0, 40), 0, 0);
                            $pdf->Cell(40,40, '',0,0, 'R');
                            $pdf->MultiCell(30, 0, $pdf->Image("../assets/uploads/".$department."/".$user_div."/".$exam_id."/".$testIV."/".$TrueFalse['option_id']."/option4/".$TrueFalse['img_4'], $pdf->GetX(),$pdf->GetY(),0, 40), 0, 0);
                            $pdf->Cell(40,40, '',0,0, 'R');
                            $pdf->Ln(45);
                        }
                        //  3-4 || 4-3
                        else if ($TrueFalse['img_1'] == null && $TrueFalse['img_2'] == null && $TrueFalse['img_3'] != null && $TrueFalse['img_4'] != null){
                            $pdf->Cell(40,40, '',0,0, 'R');
                            $pdf->MultiCell(30, 0, $pdf->Image("../assets/uploads/".$department."/".$user_div."/".$exam_id."/".$testIV."/".$TrueFalse['option_id']."/option3/".$TrueFalse['img_3'], $pdf->GetX(),$pdf->GetY(),0, 40), 0, 0);
                            $pdf->Cell(40,40, '',0,0, 'R');
                            $pdf->MultiCell(30, 0, $pdf->Image("../assets/uploads/".$department."/".$user_div."/".$exam_id."/".$testIV."/".$TrueFalse['option_id']."/option4/".$TrueFalse['img_4'], $pdf->GetX(),$pdf->GetY(),0, 40), 0, 0);
                            $pdf->Cell(40,40, '',0,0, 'R');
                            $pdf->Ln(45);
                        }
                        else if ($TrueFalse['img_1'] != null && $TrueFalse['img_2'] == null && $TrueFalse['img_3'] == null && $TrueFalse['img_4'] == null){
                            $pdf->Cell(70,40, '',0,0, 'R');
                            $pdf->MultiCell(40, 0, $pdf->Image("../assets/uploads/".$department."/".$user_div."/".$exam_id."/".$testIV."/".$TrueFalse['option_id']."/option1/".$TrueFalse['img_1'], $pdf->GetX(),$pdf->GetY(),0, 40), 0, 0);
                            $pdf->Cell(80,40, '',0,0, 'R');
                            $pdf->Ln(45);
                        }
                        else if ($TrueFalse['img_1'] == null && $TrueFalse['img_2'] != null && $TrueFalse['img_3'] == null && $TrueFalse['img_4'] == null){
                            $pdf->Cell(70,0, '',0,0, 'R');
                            $pdf->MultiCell(40, 0, $pdf->Image("../assets/uploads/".$department."/".$user_div."/".$exam_id."/".$testIV."/".$TrueFalse['option_id']."/option2/".$TrueFalse['img_2'], $pdf->GetX(),$pdf->GetY(),0, 40), 0, 0);
                            $pdf->Cell(80,0, '',0,0, 'R');
                            $pdf->Ln(45);
                        }
                        else if ($TrueFalse['img_1'] == null && $TrueFalse['img_2'] == null && $TrueFalse['img_3'] != null && $TrueFalse['img_4'] == null){
                            $pdf->Cell(70,40, '',0,0, 'R');
                            $pdf->MultiCell(40, 0, $pdf->Image("../assets/uploads/".$department."/".$user_div."/".$exam_id."/".$testIV."/".$TrueFalse['option_id']."/option3/".$TrueFalse['img_3'], $pdf->GetX(),$pdf->GetY(),0, 40), 0, 0);
                            $pdf->Cell(80,40, '',0,0, 'R');
                            $pdf->Ln(45);
                        }
                        else if ($TrueFalse['img_1'] == null && $TrueFalse['img_2'] == null && $TrueFalse['img_3'] == null && $TrueFalse['img_4'] != null){
                            $pdf->Cell(70,40, '',0,0, 'R');
                            $pdf->MultiCell(40, 0, $pdf->Image("../assets/uploads/".$department."/".$user_div."/".$exam_id."/".$testIV."/".$TrueFalse['option_id']."/option4/".$TrueFalse['img_4'], $pdf->GetX(),$pdf->GetY(),0, 40), 0, 0);
                            $pdf->Cell(80,40, '',0,0, 'R');
                            $pdf->Ln(45);
                        }
                    }
                }
                else if ($testIV == 'Multiple Image'){
                    $pdf->Cell(15,5, 'Test IV:',0,0);
                    $pdf->Cell(177,5, ' Multiple Choice (Images)',0,1);
                    $pdf->SetFont('Times','',13);
                    $pdf->MultiCell(177,5, 'Directions: Choose the BEST IMAGE answer for the following questions, and write your answer in the blank.',0,0);
                    $pdf->Ln(20);
                    while ($MultipleImage = $MultipleImageQuery->fetch()) {
                        $ans = $MultipleImage['result_ans'];
                        $pdf->SetMargins(10, 10, 10);
                        $pdf->SetTextColor(0,0,0);
                        $pdf->SetFont('Times','U',11);
                        if ($ans == 'I'){
                            $pdf->Cell(12,5, ' A ',0,0, 'C');
                        }else if ($ans == 'II'){
                            $pdf->Cell(12,5, ' B ',0,0, 'C');
                        }else if ($ans == 'III'){
                            $pdf->Cell(12,5, ' C ',0,0, 'C');
                        }else if ($ans == 'IV'){
                            $pdf->Cell(12,5, ' D  ',0,0, 'C');
                        }
                        $ans1 = $MultipleImage['correct_incorrect'];
                        if ($ans1 == 'correct'){
                            $check = "4";
                            $pdf->SetFont('ZapfDingbats','', 10);
                            $pdf->Cell(5,5, '       '.$check,0,0, 'R');

                        }else if ($ans1 == 'incorrect'){
                            $cross = "8";
                            $pdf->SetFont('ZapfDingbats','', 10);
                            $pdf->Cell(5,5, '       '.$cross,0,0, 'R');

                        }
                        $pdf->SetFont('Times','',11);
                        $pdf->Cell(6,5, $index++.'. ',0,0);
                        $pdf->MultiCell(166,5, $MultipleImage['question'].' ('.$MultipleImage['points'].' point/s)',0,1);
                        $pdf->Ln(5);
                        $pdf->SetTextColor(105,105,105);
                        $pdf->Cell(25,25, 'A: ',0,0, 'R');
                        $pdf->MultiCell(30, 0, $pdf->Image("../assets/uploads/".$department."/".$user_div."/".$exam_id."/Multiple Image/".$MultipleImage['option_id']."/option1/".$MultipleImage['img_1'], $pdf->GetX(),$pdf->GetY(),25,25), 0, 0);
                        $pdf->Cell(15,25, 'B: ',0,0, 'R');
                        $pdf->MultiCell(25, 0, $pdf->Image("../assets/uploads/".$department."/".$user_div."/".$exam_id."/Multiple Image/".$MultipleImage['option_id']."/option2/".$MultipleImage['img_2'], $pdf->GetX(),$pdf->GetY(),25,25), 0, 0);
                        $pdf->Cell(15,25, 'C: ',0,0, 'R');
                        $pdf->MultiCell(25, 0, $pdf->Image("../assets/uploads/".$department."/".$user_div."/".$exam_id."/Multiple Image/".$MultipleImage['option_id']."/option3/".$MultipleImage['img_3'], $pdf->GetX(),$pdf->GetY(),25,25), 0, 0);
                        $pdf->Cell(15,25, 'D: ',0,0, 'R');
                        $pdf->MultiCell(25, 0, $pdf->Image("../assets/uploads/".$department."/".$user_div."/".$exam_id."/Multiple Image/".$MultipleImage['option_id']."/option4/".$MultipleImage['img_4'], $pdf->GetX(),$pdf->GetY(),25,25), 0, 0);
                        $pdf->Ln(10);
                        $pdf->Ln(20);
                        $pdf->Ln(20);

                    }

                }
                else if ($testIV == 'Essay') {
                    $pdf->Cell(15,5, 'Test IV:',0,0);
                    $pdf->Cell(177, 5, ' Essay', 0, 1);
                    $pdf->SetFont('Times', '', 13);
                    $pdf->MultiCell(177, 5, 'Directions: Answer the question to the best of your knowledge. Write your answer in the space provided', 0, 0);
                    $pdf->Ln(20);
                    while ($Essay = $EssayQuery->fetch()) {
                        $ans = $Essay['correct_incorrect'];
                        if ($ans == 'correct'){
                            $check = "4";
                            $pdf->SetFont('Times', '', 11);
                            $pdf->Cell(15,5, $Essay['score_points'].' Points',0,0);
                            $pdf->SetFont('ZapfDingbats','', 10);
                            $pdf->Cell(5,5, $check,0,0);

                        }else if ($ans == 'incorrect'){
                            $cross = "8";
                            $pdf->SetFont('Times', '', 11);
                            $pdf->Cell(15,5, $Essay['score_points'].' Points',0,0);
                            $pdf->SetFont('ZapfDingbats','', 10);
                            $pdf->Cell(5,5, $cross,0,0);

                        }
                        $pdf->SetFont('Times', '', 11);
                        $pdf->Cell(6, 5, $index++ . '. ', 0, 0);
                        $pdf->MultiCell(160, 5, $Essay['question'].' ('.$Essay['points'].' point/s)', 0, 1);
                        $pdf->Ln(5);
                        $pdf->Cell(20, 5, '', 0, 0);
                        $pdf->SetFont('Times', 'U', 11);
                        $pdf->MultiCell(166, 5, '           '. $Essay['result_ans'] . '              ', 0, 1);
                        $pdf->Ln(5);
                    }
                }
                $pdf->Ln(10);
            }
            // Test IV End

            // Test V Start
            // $pdf->AddPage();
            $pdf->SetTextColor(0,0,0);
            $pdf->SetFont('Times','B',13);
            if ($testV != null || $testV != '' || $testV != 0){
                if ($testV == 'Multiple Choice'){
                    $pdf->Cell(15,5, 'Test V:',0,0);
                    $pdf->Cell(177,5, ' Multiple Choice',0,1);
                    $pdf->SetFont('Times','',13);
                    $pdf->Cell(177,5, 'Directions: Choose the BEST answer for the following questions.',0,0);
                    $pdf->Ln(20);
                    while ($MultipleChoice = $MultipleChoiceQuery->fetch()){
                        $ans = $MultipleChoice['result_ans'];
                        $cans = $MultipleChoice['ans'];
                        $pdf->SetMargins(10, 10, 10);
                        $pdf->SetFont('Times','U',11);
                        if ($ans == $MultipleChoice['option_1']){
                            $pdf->Cell(12,5, '     '.'A'.'     ',0,0, 'C');
                        }else if ($ans == $MultipleChoice['option_2']){
                            $pdf->Cell(12,5, '     '.'B'.'     ',0,0, 'C');
                        }else if ($ans == $MultipleChoice['option_3']){
                            $pdf->Cell(12,5, '     '.'C'.'     ',0,0, 'C');
                        }else if ($ans == $MultipleChoice['option_4']){
                            $pdf->Cell(12,5, '     '.'D'.'     ',0,0, 'C');
                        }
                        if ($cans == $ans){
                            $check = "4";
                            $pdf->SetFont('ZapfDingbats','', 10);
                            $pdf->Cell(6,5, $check,0,0);
                        }else if ($cans != $ans){
                            $cross = "8";
                            $pdf->SetFont('ZapfDingbats','', 10);
                            $pdf->Cell(6,5, $cross,0,0);
                        }
                        $pdf->SetFont('Times','',11);
                        $pdf->Cell(6,5, $index++.'. ',0,0);
                        $pdf->MultiCell(166,5, $MultipleChoice['question'].' ('.$MultipleChoice['points'].' point/s)',0,1);
                        $pdf->Ln(5);
                        $pdf->SetTextColor(105,105,105);
                        if ($MultipleChoice['img_1'] != null && $MultipleChoice['img_2'] != null && $MultipleChoice['img_3'] != null && $MultipleChoice['img_4'] != null){
                            $pdf->Cell(20,25, '',0,0, 'R');
                            $pdf->MultiCell(25, 0, $pdf->Image("../assets/uploads/".$department."/".$user_div."/".$exam_id."/".$testV."/".$MultipleChoice['option_id']."/option1/".$MultipleChoice['img_1'], $pdf->GetX(),$pdf->GetY(),25,25), 0, 0);
                            $pdf->Cell(15,25, '',0,0, 'R');
                            $pdf->MultiCell(25, 0, $pdf->Image("../assets/uploads/".$department."/".$user_div."/".$exam_id."/".$testV."/".$MultipleChoice['option_id']."/option2/".$MultipleChoice['img_2'], $pdf->GetX(),$pdf->GetY(),25,25), 0, 0);
                            $pdf->Cell(15,25, '',0,0, 'R');
                            $pdf->MultiCell(25, 0, $pdf->Image("../assets/uploads/".$department."/".$user_div."/".$exam_id."/".$testV."/".$MultipleChoice['option_id']."/option3/".$MultipleChoice['img_3'], $pdf->GetX(),$pdf->GetY(),25,25), 0, 0);
                            $pdf->Cell(15,25, '',0,0, 'R');
                            $pdf->MultiCell(25, 0, $pdf->Image("../assets/uploads/".$department."/".$user_div."/".$exam_id."/".$testV."/".$MultipleChoice['option_id']."/option4/".$MultipleChoice['img_4'], $pdf->GetX(),$pdf->GetY(),25,25), 0, 0);
                            $pdf->Cell(15,25, '',0,1, 'R');
                            $pdf->Ln(5);
                        }
                        // 4 = null
                        else if ($MultipleChoice['img_1'] != null && $MultipleChoice['img_2'] != null && $MultipleChoice['img_3'] != null && $MultipleChoice['img_4'] == null){
                            $pdf->Cell(30,25, '',0,0 );
                            $pdf->MultiCell(20, 0, $pdf->Image("../assets/uploads/".$department."/".$user_div."/".$exam_id."/".$testV."/".$MultipleChoice['option_id']."/option1/".$MultipleChoice['img_1'], $pdf->GetX(),$pdf->GetY(),25,25), 0, 0);
                            $pdf->Cell(30,25, '',0,0);
                            $pdf->MultiCell(20, 0, $pdf->Image("../assets/uploads/".$department."/".$user_div."/".$exam_id."/".$testV."/".$MultipleChoice['option_id']."/option2/".$MultipleChoice['img_2'], $pdf->GetX(),$pdf->GetY(),25,25), 0, 0);
                            $pdf->Cell(30,25, '',0,0);
                            $pdf->MultiCell(20, 0, $pdf->Image("../assets/uploads/".$department."/".$user_div."/".$exam_id."/".$testV."/".$MultipleChoice['option_id']."/option3/".$MultipleChoice['img_3'], $pdf->GetX(),$pdf->GetY(),25,25), 0, 0);
                            $pdf->Cell(30,25, '',0,0);
                            $pdf->Ln(30);
                        }
                        // 3 = null
                        else if ($MultipleChoice['img_1'] != null && $MultipleChoice['img_2'] != null && $MultipleChoice['img_3'] == null && $MultipleChoice['img_4'] != null){
                            $pdf->Cell(30,25, '',0,0, 'R');
                            $pdf->MultiCell(20, 0, $pdf->Image("../assets/uploads/".$department."/".$user_div."/".$exam_id."/".$testV."/".$MultipleChoice['option_id']."/option1/".$MultipleChoice['img_1'], $pdf->GetX(),$pdf->GetY(),25,25), 0, 0);
                            $pdf->Cell(30,25, '',0,0, 'R');
                            $pdf->MultiCell(20, 0, $pdf->Image("../assets/uploads/".$department."/".$user_div."/".$exam_id."/".$testV."/".$MultipleChoice['option_id']."/option2/".$MultipleChoice['img_2'], $pdf->GetX(),$pdf->GetY(),25,25), 0, 0);
                            $pdf->Cell(30,25, '',0,0, 'R');
                            $pdf->MultiCell(20, 0, $pdf->Image("../assets/uploads/".$department."/".$user_div."/".$exam_id."/".$testV."/".$MultipleChoice['option_id']."/option4/".$MultipleChoice['img_4'], $pdf->GetX(),$pdf->GetY(),25,25), 0, 0);
                            $pdf->Cell(30,25, '',0,0, 'R');
                            $pdf->Ln(30);
                        }
                        // 2 = null
                        else if ($MultipleChoice['img_1'] != null && $MultipleChoice['img_2'] == null && $MultipleChoice['img_3'] != null && $MultipleChoice['img_4'] != null){
                            $pdf->Cell(30,25, '',0,0, 'R');
                            $pdf->MultiCell(20, 0, $pdf->Image("../assets/uploads/".$department."/".$user_div."/".$exam_id."/".$testV."/".$MultipleChoice['option_id']."/option1/".$MultipleChoice['img_1'], $pdf->GetX(),$pdf->GetY(),25,25), 0, 0);
                            $pdf->Cell(30,25, '',0,0, 'R');
                            $pdf->MultiCell(20, 0, $pdf->Image("../assets/uploads/".$department."/".$user_div."/".$exam_id."/".$testV."/".$MultipleChoice['option_id']."/option3/".$MultipleChoice['img_3'], $pdf->GetX(),$pdf->GetY(),25,25), 0, 0);
                            $pdf->Cell(30,25, '',0,0, 'R');
                            $pdf->MultiCell(20, 0, $pdf->Image("../assets/uploads/".$department."/".$user_div."/".$exam_id."/".$testV."/".$MultipleChoice['option_id']."/option4/".$MultipleChoice['img_4'], $pdf->GetX(),$pdf->GetY(),25,25), 0, 0);
                            $pdf->Cell(30,25, '',0,0, 'R');
                            $pdf->Ln(30);
                        }
                        // 1 = null
                        else if ($MultipleChoice['img_1'] == null && $MultipleChoice['img_2'] != null && $MultipleChoice['img_3'] != null && $MultipleChoice['img_4'] != null){
                            $pdf->Cell(30,25, '',0,0, 'R');
                            $pdf->MultiCell(20, 0, $pdf->Image("../assets/uploads/".$department."/".$user_div."/".$exam_id."/".$testV."/".$MultipleChoice['option_id']."/option2/".$MultipleChoice['img_2'], $pdf->GetX(),$pdf->GetY(),25,25), 0, 0);
                            $pdf->Cell(30,25, '',0,0, 'R');
                            $pdf->MultiCell(20, 0, $pdf->Image("../assets/uploads/".$department."/".$user_div."/".$exam_id."/".$testV."/".$MultipleChoice['option_id']."/option3/".$MultipleChoice['img_3'], $pdf->GetX(),$pdf->GetY(),25,25), 0, 0);
                            $pdf->Cell(30,25, '',0,0, 'R');
                            $pdf->MultiCell(20, 0, $pdf->Image("../assets/uploads/".$department."/".$user_div."/".$exam_id."/".$testV."/".$MultipleChoice['option_id']."/option4/".$MultipleChoice['img_4'], $pdf->GetX(),$pdf->GetY(),25,25), 0, 0);
                            $pdf->Cell(30,25, '',0,0, 'R');
                            $pdf->Ln(30);
                        }
                        //  1-2 || 2-1
                        else if ($MultipleChoice['img_1'] != null && $MultipleChoice['img_2'] != null && $MultipleChoice['img_3'] == null && $MultipleChoice['img_4'] == null){
                            $pdf->Cell(40,40, '',0,0, 'R');
                            $pdf->MultiCell(30, 0, $pdf->Image("../assets/uploads/".$department."/".$user_div."/".$exam_id."/".$testV."/".$MultipleChoice['option_id']."/option1/".$MultipleChoice['img_1'], $pdf->GetX(),$pdf->GetY(),0, 40), 0, 0);
                            $pdf->Cell(40,40, '',0,0, 'R');
                            $pdf->MultiCell(30, 0, $pdf->Image("../assets/uploads/".$department."/".$user_div."/".$exam_id."/".$testV."/".$MultipleChoice['option_id']."/option2/".$MultipleChoice['img_2'], $pdf->GetX(),$pdf->GetY(),0, 40), 0, 0);
                            $pdf->Cell(40,40, '',0,0, 'R');
                            $pdf->Ln(45);
                        }
                        //  1-3 || 3-1
                        else if ($MultipleChoice['img_1'] != null && $MultipleChoice['img_2'] == null && $MultipleChoice['img_3'] != null && $MultipleChoice['img_4'] == null){
                            $pdf->Cell(40,40, '',0,0, 'R');
                            $pdf->MultiCell(30, 0, $pdf->Image("../assets/uploads/".$department."/".$user_div."/".$exam_id."/".$testV."/".$MultipleChoice['option_id']."/option1/".$MultipleChoice['img_1'], $pdf->GetX(),$pdf->GetY(),0, 40), 0, 0);
                            $pdf->Cell(40,40, '',0,0, 'R');
                            $pdf->MultiCell(30, 0, $pdf->Image("../assets/uploads/".$department."/".$user_div."/".$exam_id."/".$testV."/".$MultipleChoice['option_id']."/option3/".$MultipleChoice['img_3'], $pdf->GetX(),$pdf->GetY(),0, 40), 0, 0);
                            $pdf->Cell(40,40, '',0,0, 'R');
                            $pdf->Ln(45);
                        }
                        //  1-4 || 4-1
                        else if ($MultipleChoice['img_1'] != null && $MultipleChoice['img_2'] == null && $MultipleChoice['img_3'] == null && $MultipleChoice['img_4'] != null){
                            $pdf->Cell(40,40, '',0,0, 'R');
                            $pdf->MultiCell(30, 0, $pdf->Image("../assets/uploads/".$department."/".$user_div."/".$exam_id."/".$testV."/".$MultipleChoice['option_id']."/option1/".$MultipleChoice['img_1'], $pdf->GetX(),$pdf->GetY(),0, 40), 0, 0);
                            $pdf->Cell(40,40, '',0,0, 'R');
                            $pdf->MultiCell(30, 0, $pdf->Image("../assets/uploads/".$department."/".$user_div."/".$exam_id."/".$testV."/".$MultipleChoice['option_id']."/option4/".$MultipleChoice['img_4'], $pdf->GetX(),$pdf->GetY(),0, 40), 0, 0);
                            $pdf->Cell(40,40, '',0,0, 'R');
                            $pdf->Ln(45);
                        }
                        //  2-3 || 3-2
                        else if ($MultipleChoice['img_1'] == null && $MultipleChoice['img_2'] != null && $MultipleChoice['img_3'] != null && $MultipleChoice['img_4'] == null){
                            $pdf->Cell(40,40, '',0,0, 'R');
                            $pdf->MultiCell(30, 0, $pdf->Image("../assets/uploads/".$department."/".$user_div."/".$exam_id."/".$testV."/".$MultipleChoice['option_id']."/option2/".$MultipleChoice['img_2'], $pdf->GetX(),$pdf->GetY(),0, 40), 0, 0);
                            $pdf->Cell(40,40, '',0,0, 'R');
                            $pdf->MultiCell(30, 0, $pdf->Image("../assets/uploads/".$department."/".$user_div."/".$exam_id."/".$testV."/".$MultipleChoice['option_id']."/option3/".$MultipleChoice['img_3'], $pdf->GetX(),$pdf->GetY(),0, 40), 0, 0);
                            $pdf->Cell(40,40, '',0,0, 'R');
                            $pdf->Ln(45);
                        }
                        //  2-4 || 4-2
                        else if ($MultipleChoice['img_1'] == null && $MultipleChoice['img_2'] != null && $MultipleChoice['img_3'] == null && $MultipleChoice['img_4'] != null){
                            $pdf->Cell(40,40, '',0,0, 'R');
                            $pdf->MultiCell(30, 0, $pdf->Image("../assets/uploads/".$department."/".$user_div."/".$exam_id."/".$testV."/".$MultipleChoice['option_id']."/option2/".$MultipleChoice['img_2'], $pdf->GetX(),$pdf->GetY(),0, 40), 0, 0);
                            $pdf->Cell(40,40, '',0,0, 'R');
                            $pdf->MultiCell(30, 0, $pdf->Image("../assets/uploads/".$department."/".$user_div."/".$exam_id."/".$testV."/".$MultipleChoice['option_id']."/option4/".$MultipleChoice['img_4'], $pdf->GetX(),$pdf->GetY(),0, 40), 0, 0);
                            $pdf->Cell(40,40, '',0,0, 'R');
                            $pdf->Ln(45);
                        }
                        //  3-4 || 4-3
                        else if ($MultipleChoice['img_1'] == null && $MultipleChoice['img_2'] == null && $MultipleChoice['img_3'] != null && $MultipleChoice['img_4'] != null){
                            $pdf->Cell(40,40, '',0,0, 'R');
                            $pdf->MultiCell(30, 0, $pdf->Image("../assets/uploads/".$department."/".$user_div."/".$exam_id."/".$testV."/".$MultipleChoice['option_id']."/option3/".$MultipleChoice['img_3'], $pdf->GetX(),$pdf->GetY(),0, 40), 0, 0);
                            $pdf->Cell(40,40, '',0,0, 'R');
                            $pdf->MultiCell(30, 0, $pdf->Image("../assets/uploads/".$department."/".$user_div."/".$exam_id."/".$testV."/".$MultipleChoice['option_id']."/option4/".$MultipleChoice['img_4'], $pdf->GetX(),$pdf->GetY(),0, 40), 0, 0);
                            $pdf->Cell(40,40, '',0,0, 'R');
                            $pdf->Ln(45);
                        }
                        else if ($MultipleChoice['img_1'] != null && $MultipleChoice['img_2'] == null && $MultipleChoice['img_3'] == null && $MultipleChoice['img_4'] == null){
                            $pdf->Cell(70,40, '',0,0, 'R');
                            $pdf->MultiCell(40, 0, $pdf->Image("../assets/uploads/".$department."/".$user_div."/".$exam_id."/".$testV."/".$MultipleChoice['option_id']."/option1/".$MultipleChoice['img_1'], $pdf->GetX(),$pdf->GetY(),0, 40), 0, 0);
                            $pdf->Cell(80,40, '',0,0, 'R');
                            $pdf->Ln(45);
                        }
                        else if ($MultipleChoice['img_1'] == null && $MultipleChoice['img_2'] != null && $MultipleChoice['img_3'] == null && $MultipleChoice['img_4'] == null){
                            $pdf->Cell(70,0, '',0,0, 'R');
                            $pdf->MultiCell(40, 0, $pdf->Image("../assets/uploads/".$department."/".$user_div."/".$exam_id."/".$testV."/".$MultipleChoice['option_id']."/option2/".$MultipleChoice['img_2'], $pdf->GetX(),$pdf->GetY(),0, 40), 0, 0);
                            $pdf->Cell(80,0, '',0,0, 'R');
                            $pdf->Ln(45);
                        }
                        else if ($MultipleChoice['img_1'] == null && $MultipleChoice['img_2'] == null && $MultipleChoice['img_3'] != null && $MultipleChoice['img_4'] == null){
                            $pdf->Cell(70,40, '',0,0, 'R');
                            $pdf->MultiCell(40, 0, $pdf->Image("../assets/uploads/".$department."/".$user_div."/".$exam_id."/".$testV."/".$MultipleChoice['option_id']."/option3/".$MultipleChoice['img_3'], $pdf->GetX(),$pdf->GetY(),0, 40), 0, 0);
                            $pdf->Cell(80,40, '',0,0, 'R');
                            $pdf->Ln(45);
                        }
                        else if ($MultipleChoice['img_1'] == null && $MultipleChoice['img_2'] == null && $MultipleChoice['img_3'] == null && $MultipleChoice['img_4'] != null){
                            $pdf->Cell(70,40, '',0,0, 'R');
                            $pdf->MultiCell(40, 0, $pdf->Image("../assets/uploads/".$department."/".$user_div."/".$exam_id."/".$testV."/".$MultipleChoice['option_id']."/option4/".$MultipleChoice['img_4'], $pdf->GetX(),$pdf->GetY(),0, 40), 0, 0);
                            $pdf->Cell(80,40, '',0,0, 'R');
                            $pdf->Ln(45);
                        }
                        $pdf->Cell(24,5, 'A.',0,0, 'R');
                        $pdf->MultiCell(76,5, $MultipleChoice['option_1'],0,0);
                        $pdf->Cell(7,5, 'C.',0,0, 'R');
                        $pdf->MultiCell(76, 5, $MultipleChoice['option_3'],0,1);
                        $pdf->Ln(2);
                        $pdf->Cell(24,5, 'B.',0,0, 'R');
                        $pdf->MultiCell(76, 5, $MultipleChoice['option_2'],0,0);
                        $pdf->Cell(7,5, 'D.',0,0, 'R');
                        $pdf->MultiCell(76, 5, $MultipleChoice['option_4'],0,1);
                        $pdf->Ln(5);
                        $pdf->SetTextColor(0,0,0);
                    }
                }
                else if ($testV == 'Short Answer'){
                    $pdf->Cell(15,5, 'Test V:',0,0);
                    $pdf->Cell(177,5, ' Short Answer',0,1);
                    $pdf->SetFont('Times','',13);
                    $pdf->MultiCell(177,5, 'Directions: Using your own words, answer each question in the space provided',0,0);
                    $pdf->Ln(20);

                    while ($ShortAnswer = $ShortAnswerQuery->fetch()){
                        $ans = $ShortAnswer['correct_incorrect'];
                        if ($ans == 'correct'){
                            $check = "4";
                            $pdf->SetFont('ZapfDingbats','', 10);
                            $pdf->Cell(5,5, $check,0,0);

                        }else if ($ans == 'incorrect'){
                            $cross = "8";
                            $pdf->SetFont('ZapfDingbats','', 10);
                            $pdf->Cell(5,5, $cross,0,0);

                        }
                        $pdf->SetFont('Times','',11);
                        $pdf->Cell(6,5, $index++.'. ',0,0);
                        $pdf->MultiCell(166,5, $ShortAnswer['question'].' ('.$ShortAnswer['points'].' point/s)',0,1);
                        $pdf->Ln(5);
                        $pdf->Cell(11,5, '',0,0);
                        $pdf->SetFont('Times','U',11);
                        $pdf->MultiCell(180,5, '  '.$ShortAnswer['result_ans'].'                          ',0,1);
                        $pdf->Ln(5);
                    }
                }
                else if ($testV == 'True/False'){
                    $pdf->Cell(15,5, 'Test V:',0,0);
                    $pdf->Cell(177,5, ' True or False',0,1);
                    $pdf->SetFont('Times','',13);
                    $pdf->MultiCell(177,5, 'Directions: Read the statements carefully and tell whether the statement is TRUE or FALSE. Choose TRUE if the statement is correct and FALSE if otherwise.',0,0);
                    $pdf->Ln(20);
                    while ($TrueFalse = $TrueFalseQuery->fetch()){
                        $ans = $TrueFalse['result_ans'];
                        $cans = $TrueFalse['ans'];
                        $pdf->SetMargins(10, 10, 10);
                        $pdf->SetFont('Times','U',11);
                        $pdf->Cell(12,5, '  '.$TrueFalse['result_ans'].'  ',0,0, 'C');
                        if ($cans == $ans){
                            $check = "4";
                            $pdf->SetFont('ZapfDingbats','', 10);
                            $pdf->Cell(6,5, $check,0,0);
                        }else if ($cans != $ans){
                            $cross = "8";
                            $pdf->SetFont('ZapfDingbats','', 10);
                            $pdf->Cell(6,5, $cross,0,0);
                        }
                        $pdf->SetFont('Times','',11);
                        $pdf->Cell(6,5, $index++.'. ',0,0);
                        $pdf->MultiCell(166,5, $TrueFalse['question'].' ('.$TrueFalse['points'].' point/s)', 0, 1);
                        $pdf->Ln(5);

                        if ($TrueFalse['img_1'] != null && $TrueFalse['img_2'] != null && $TrueFalse['img_3'] != null && $TrueFalse['img_4'] != null){
                            $pdf->Cell(20,25, '',0,0, 'R');
                            $pdf->MultiCell(25, 0, $pdf->Image("../assets/uploads/".$department."/".$user_div."/".$exam_id."/".$testV."/".$TrueFalse['option_id']."/option1/".$TrueFalse['img_1'], $pdf->GetX(),$pdf->GetY(),25,25), 0, 0);
                            $pdf->Cell(15,25, '',0,0, 'R');
                            $pdf->MultiCell(25, 0, $pdf->Image("../assets/uploads/".$department."/".$user_div."/".$exam_id."/".$testV."/".$TrueFalse['option_id']."/option2/".$TrueFalse['img_2'], $pdf->GetX(),$pdf->GetY(),25,25), 0, 0);
                            $pdf->Cell(15,25, '',0,0, 'R');
                            $pdf->MultiCell(25, 0, $pdf->Image("../assets/uploads/".$department."/".$user_div."/".$exam_id."/".$testV."/".$TrueFalse['option_id']."/option3/".$TrueFalse['img_3'], $pdf->GetX(),$pdf->GetY(),25,25), 0, 0);
                            $pdf->Cell(15,25, '',0,0, 'R');
                            $pdf->MultiCell(25, 0, $pdf->Image("../assets/uploads/".$department."/".$user_div."/".$exam_id."/".$testV."/".$TrueFalse['option_id']."/option4/".$TrueFalse['img_4'], $pdf->GetX(),$pdf->GetY(),25,25), 0, 0);
                            $pdf->Cell(15,25, '',0,1, 'R');
                            $pdf->Ln(5);
                        }
                        // 4 = null
                        else if ($TrueFalse['img_1'] != null && $TrueFalse['img_2'] != null && $TrueFalse['img_3'] != null && $TrueFalse['img_4'] == null){
                            $pdf->Cell(30,25, '',0,0 );
                            $pdf->MultiCell(20, 0, $pdf->Image("../assets/uploads/".$department."/".$user_div."/".$exam_id."/".$testV."/".$TrueFalse['option_id']."/option1/".$TrueFalse['img_1'], $pdf->GetX(),$pdf->GetY(),25,25), 0, 0);
                            $pdf->Cell(30,25, '',0,0);
                            $pdf->MultiCell(20, 0, $pdf->Image("../assets/uploads/".$department."/".$user_div."/".$exam_id."/".$testV."/".$TrueFalse['option_id']."/option2/".$TrueFalse['img_2'], $pdf->GetX(),$pdf->GetY(),25,25), 0, 0);
                            $pdf->Cell(30,25, '',0,0);
                            $pdf->MultiCell(20, 0, $pdf->Image("../assets/uploads/".$department."/".$user_div."/".$exam_id."/".$testV."/".$TrueFalse['option_id']."/option3/".$TrueFalse['img_3'], $pdf->GetX(),$pdf->GetY(),25,25), 0, 0);
                            $pdf->Cell(30,25, '',0,0);
                            $pdf->Ln(30);
                        }
                        // 3 = null
                        else if ($TrueFalse['img_1'] != null && $TrueFalse['img_2'] != null && $TrueFalse['img_3'] == null && $TrueFalse['img_4'] != null){
                            $pdf->Cell(30,25, '',0,0, 'R');
                            $pdf->MultiCell(20, 0, $pdf->Image("../assets/uploads/".$department."/".$user_div."/".$exam_id."/".$testV."/".$TrueFalse['option_id']."/option1/".$TrueFalse['img_1'], $pdf->GetX(),$pdf->GetY(),25,25), 0, 0);
                            $pdf->Cell(30,25, '',0,0, 'R');
                            $pdf->MultiCell(20, 0, $pdf->Image("../assets/uploads/".$department."/".$user_div."/".$exam_id."/".$testV."/".$TrueFalse['option_id']."/option2/".$TrueFalse['img_2'], $pdf->GetX(),$pdf->GetY(),25,25), 0, 0);
                            $pdf->Cell(30,25, '',0,0, 'R');
                            $pdf->MultiCell(20, 0, $pdf->Image("../assets/uploads/".$department."/".$user_div."/".$exam_id."/".$testV."/".$TrueFalse['option_id']."/option4/".$TrueFalse['img_4'], $pdf->GetX(),$pdf->GetY(),25,25), 0, 0);
                            $pdf->Cell(30,25, '',0,0, 'R');
                            $pdf->Ln(30);
                        }
                        // 2 = null
                        else if ($TrueFalse['img_1'] != null && $TrueFalse['img_2'] == null && $TrueFalse['img_3'] != null && $TrueFalse['img_4'] != null){
                            $pdf->Cell(30,25, '',0,0, 'R');
                            $pdf->MultiCell(20, 0, $pdf->Image("../assets/uploads/".$department."/".$user_div."/".$exam_id."/".$testV."/".$TrueFalse['option_id']."/option1/".$TrueFalse['img_1'], $pdf->GetX(),$pdf->GetY(),25,25), 0, 0);
                            $pdf->Cell(30,25, '',0,0, 'R');
                            $pdf->MultiCell(20, 0, $pdf->Image("../assets/uploads/".$department."/".$user_div."/".$exam_id."/".$testV."/".$TrueFalse['option_id']."/option3/".$TrueFalse['img_3'], $pdf->GetX(),$pdf->GetY(),25,25), 0, 0);
                            $pdf->Cell(30,25, '',0,0, 'R');
                            $pdf->MultiCell(20, 0, $pdf->Image("../assets/uploads/".$department."/".$user_div."/".$exam_id."/".$testV."/".$TrueFalse['option_id']."/option4/".$TrueFalse['img_4'], $pdf->GetX(),$pdf->GetY(),25,25), 0, 0);
                            $pdf->Cell(30,25, '',0,0, 'R');
                            $pdf->Ln(30);
                        }
                        // 1 = null
                        else if ($TrueFalse['img_1'] == null && $TrueFalse['img_2'] != null && $TrueFalse['img_3'] != null && $TrueFalse['img_4'] != null){
                            $pdf->Cell(30,25, '',0,0, 'R');
                            $pdf->MultiCell(20, 0, $pdf->Image("../assets/uploads/".$department."/".$user_div."/".$exam_id."/".$testV."/".$TrueFalse['option_id']."/option2/".$TrueFalse['img_2'], $pdf->GetX(),$pdf->GetY(),25,25), 0, 0);
                            $pdf->Cell(30,25, '',0,0, 'R');
                            $pdf->MultiCell(20, 0, $pdf->Image("../assets/uploads/".$department."/".$user_div."/".$exam_id."/".$testV."/".$TrueFalse['option_id']."/option3/".$TrueFalse['img_3'], $pdf->GetX(),$pdf->GetY(),25,25), 0, 0);
                            $pdf->Cell(30,25, '',0,0, 'R');
                            $pdf->MultiCell(20, 0, $pdf->Image("../assets/uploads/".$department."/".$user_div."/".$exam_id."/".$testV."/".$TrueFalse['option_id']."/option4/".$TrueFalse['img_4'], $pdf->GetX(),$pdf->GetY(),25,25), 0, 0);
                            $pdf->Cell(30,25, '',0,0, 'R');
                            $pdf->Ln(30);
                        }
                        //  1-2 || 2-1
                        else if ($TrueFalse['img_1'] != null && $TrueFalse['img_2'] != null && $TrueFalse['img_3'] == null && $TrueFalse['img_4'] == null){
                            $pdf->Cell(40,40, '',0,0, 'R');
                            $pdf->MultiCell(30, 0, $pdf->Image("../assets/uploads/".$department."/".$user_div."/".$exam_id."/".$testV."/".$TrueFalse['option_id']."/option1/".$TrueFalse['img_1'], $pdf->GetX(),$pdf->GetY(),0, 40), 0, 0);
                            $pdf->Cell(40,40, '',0,0, 'R');
                            $pdf->MultiCell(30, 0, $pdf->Image("../assets/uploads/".$department."/".$user_div."/".$exam_id."/".$testV."/".$TrueFalse['option_id']."/option2/".$TrueFalse['img_2'], $pdf->GetX(),$pdf->GetY(),0, 40), 0, 0);
                            $pdf->Cell(40,40, '',0,0, 'R');
                            $pdf->Ln(45);
                        }
                        //  1-3 || 3-1
                        else if ($TrueFalse['img_1'] != null && $TrueFalse['img_2'] == null && $TrueFalse['img_3'] != null && $TrueFalse['img_4'] == null){
                            $pdf->Cell(40,40, '',0,0, 'R');
                            $pdf->MultiCell(30, 0, $pdf->Image("../assets/uploads/".$department."/".$user_div."/".$exam_id."/".$testV."/".$TrueFalse['option_id']."/option1/".$TrueFalse['img_1'], $pdf->GetX(),$pdf->GetY(),0, 40), 0, 0);
                            $pdf->Cell(40,40, '',0,0, 'R');
                            $pdf->MultiCell(30, 0, $pdf->Image("../assets/uploads/".$department."/".$user_div."/".$exam_id."/".$testV."/".$TrueFalse['option_id']."/option3/".$TrueFalse['img_3'], $pdf->GetX(),$pdf->GetY(),0, 40), 0, 0);
                            $pdf->Cell(40,40, '',0,0, 'R');
                            $pdf->Ln(45);
                        }
                        //  1-4 || 4-1
                        else if ($TrueFalse['img_1'] != null && $TrueFalse['img_2'] == null && $TrueFalse['img_3'] == null && $TrueFalse['img_4'] != null){
                            $pdf->Cell(40,40, '',0,0, 'R');
                            $pdf->MultiCell(30, 0, $pdf->Image("../assets/uploads/".$department."/".$user_div."/".$exam_id."/".$testV."/".$TrueFalse['option_id']."/option1/".$TrueFalse['img_1'], $pdf->GetX(),$pdf->GetY(),0, 40), 0, 0);
                            $pdf->Cell(40,40, '',0,0, 'R');
                            $pdf->MultiCell(30, 0, $pdf->Image("../assets/uploads/".$department."/".$user_div."/".$exam_id."/".$testV."/".$TrueFalse['option_id']."/option4/".$TrueFalse['img_4'], $pdf->GetX(),$pdf->GetY(),0, 40), 0, 0);
                            $pdf->Cell(40,40, '',0,0, 'R');
                            $pdf->Ln(45);
                        }
                        //  2-3 || 3-2
                        else if ($TrueFalse['img_1'] == null && $TrueFalse['img_2'] != null && $TrueFalse['img_3'] != null && $TrueFalse['img_4'] == null){
                            $pdf->Cell(40,40, '',0,0, 'R');
                            $pdf->MultiCell(30, 0, $pdf->Image("../assets/uploads/".$department."/".$user_div."/".$exam_id."/".$testV."/".$TrueFalse['option_id']."/option2/".$TrueFalse['img_2'], $pdf->GetX(),$pdf->GetY(),0, 40), 0, 0);
                            $pdf->Cell(40,40, '',0,0, 'R');
                            $pdf->MultiCell(30, 0, $pdf->Image("../assets/uploads/".$department."/".$user_div."/".$exam_id."/".$testV."/".$TrueFalse['option_id']."/option3/".$TrueFalse['img_3'], $pdf->GetX(),$pdf->GetY(),0, 40), 0, 0);
                            $pdf->Cell(40,40, '',0,0, 'R');
                            $pdf->Ln(45);
                        }
                        //  2-4 || 4-2
                        else if ($TrueFalse['img_1'] == null && $TrueFalse['img_2'] != null && $TrueFalse['img_3'] == null && $TrueFalse['img_4'] != null){
                            $pdf->Cell(40,40, '',0,0, 'R');
                            $pdf->MultiCell(30, 0, $pdf->Image("../assets/uploads/".$department."/".$user_div."/".$exam_id."/".$testV."/".$TrueFalse['option_id']."/option2/".$TrueFalse['img_2'], $pdf->GetX(),$pdf->GetY(),0, 40), 0, 0);
                            $pdf->Cell(40,40, '',0,0, 'R');
                            $pdf->MultiCell(30, 0, $pdf->Image("../assets/uploads/".$department."/".$user_div."/".$exam_id."/".$testV."/".$TrueFalse['option_id']."/option4/".$TrueFalse['img_4'], $pdf->GetX(),$pdf->GetY(),0, 40), 0, 0);
                            $pdf->Cell(40,40, '',0,0, 'R');
                            $pdf->Ln(45);
                        }
                        //  3-4 || 4-3
                        else if ($TrueFalse['img_1'] == null && $TrueFalse['img_2'] == null && $TrueFalse['img_3'] != null && $TrueFalse['img_4'] != null){
                            $pdf->Cell(40,40, '',0,0, 'R');
                            $pdf->MultiCell(30, 0, $pdf->Image("../assets/uploads/".$department."/".$user_div."/".$exam_id."/".$testV."/".$TrueFalse['option_id']."/option3/".$TrueFalse['img_3'], $pdf->GetX(),$pdf->GetY(),0, 40), 0, 0);
                            $pdf->Cell(40,40, '',0,0, 'R');
                            $pdf->MultiCell(30, 0, $pdf->Image("../assets/uploads/".$department."/".$user_div."/".$exam_id."/".$testV."/".$TrueFalse['option_id']."/option4/".$TrueFalse['img_4'], $pdf->GetX(),$pdf->GetY(),0, 40), 0, 0);
                            $pdf->Cell(40,40, '',0,0, 'R');
                            $pdf->Ln(45);
                        }
                        else if ($TrueFalse['img_1'] != null && $TrueFalse['img_2'] == null && $TrueFalse['img_3'] == null && $TrueFalse['img_4'] == null){
                            $pdf->Cell(70,40, '',0,0, 'R');
                            $pdf->MultiCell(40, 0, $pdf->Image("../assets/uploads/".$department."/".$user_div."/".$exam_id."/".$testV."/".$TrueFalse['option_id']."/option1/".$TrueFalse['img_1'], $pdf->GetX(),$pdf->GetY(),0, 40), 0, 0);
                            $pdf->Cell(80,40, '',0,0, 'R');
                            $pdf->Ln(45);
                        }
                        else if ($TrueFalse['img_1'] == null && $TrueFalse['img_2'] != null && $TrueFalse['img_3'] == null && $TrueFalse['img_4'] == null){
                            $pdf->Cell(70,0, '',0,0, 'R');
                            $pdf->MultiCell(40, 0, $pdf->Image("../assets/uploads/".$department."/".$user_div."/".$exam_id."/".$testV."/".$TrueFalse['option_id']."/option2/".$TrueFalse['img_2'], $pdf->GetX(),$pdf->GetY(),0, 40), 0, 0);
                            $pdf->Cell(80,0, '',0,0, 'R');
                            $pdf->Ln(45);
                        }
                        else if ($TrueFalse['img_1'] == null && $TrueFalse['img_2'] == null && $TrueFalse['img_3'] != null && $TrueFalse['img_4'] == null){
                            $pdf->Cell(70,40, '',0,0, 'R');
                            $pdf->MultiCell(40, 0, $pdf->Image("../assets/uploads/".$department."/".$user_div."/".$exam_id."/".$testV."/".$TrueFalse['option_id']."/option3/".$TrueFalse['img_3'], $pdf->GetX(),$pdf->GetY(),0, 40), 0, 0);
                            $pdf->Cell(80,40, '',0,0, 'R');
                            $pdf->Ln(45);
                        }
                        else if ($TrueFalse['img_1'] == null && $TrueFalse['img_2'] == null && $TrueFalse['img_3'] == null && $TrueFalse['img_4'] != null){
                            $pdf->Cell(70,40, '',0,0, 'R');
                            $pdf->MultiCell(40, 0, $pdf->Image("../assets/uploads/".$department."/".$user_div."/".$exam_id."/".$testV."/".$TrueFalse['option_id']."/option4/".$TrueFalse['img_4'], $pdf->GetX(),$pdf->GetY(),0, 40), 0, 0);
                            $pdf->Cell(80,40, '',0,0, 'R');
                            $pdf->Ln(45);
                        }
                    }
                }
                else if ($testV == 'Multiple Image'){
                    $pdf->Cell(15,5, 'Test V:',0,0);
                    $pdf->Cell(177,5, ' Multiple Choice (Images)',0,1);
                    $pdf->SetFont('Times','',13);
                    $pdf->MultiCell(177,5, 'Directions: Choose the BEST IMAGE answer for the following questions, and write your answer in the blank.',0,0);
                    $pdf->Ln(20);
                    while ($MultipleImage = $MultipleImageQuery->fetch()) {
                        $ans = $MultipleImage['result_ans'];
                        $pdf->SetMargins(10, 10, 10);
                        $pdf->SetTextColor(0,0,0);
                        $pdf->SetFont('Times','U',11);
                        if ($ans == 'I'){
                            $pdf->Cell(12,5, ' A ',0,0, 'C');
                        }else if ($ans == 'II'){
                            $pdf->Cell(12,5, ' B ',0,0, 'C');
                        }else if ($ans == 'III'){
                            $pdf->Cell(12,5, ' C ',0,0, 'C');
                        }else if ($ans == 'IV'){
                            $pdf->Cell(12,5, ' D  ',0,0, 'C');
                        }
                        $ans1 = $MultipleImage['correct_incorrect'];
                        if ($ans1 == 'correct'){
                            $check = "4";
                            $pdf->SetFont('ZapfDingbats','', 10);
                            $pdf->Cell(5,5, '       '.$check,0,0, 'R');

                        }else if ($ans1 == 'incorrect'){
                            $cross = "8";
                            $pdf->SetFont('ZapfDingbats','', 10);
                            $pdf->Cell(5,5, '       '.$cross,0,0, 'R');

                        }
                        $pdf->SetFont('Times','',11);
                        $pdf->Cell(6,5, $index++.'. ',0,0);
                        $pdf->MultiCell(166,5, $MultipleImage['question'].' ('.$MultipleImage['points'].' point/s)',0,1);
                        $pdf->Ln(5);
                        $pdf->SetTextColor(105,105,105);
                        $pdf->Cell(25,25, 'A: ',0,0, 'R');
                        $pdf->MultiCell(30, 0, $pdf->Image("../assets/uploads/".$department."/".$user_div."/".$exam_id."/Multiple Image/".$MultipleImage['option_id']."/option1/".$MultipleImage['img_1'], $pdf->GetX(),$pdf->GetY(),25,25), 0, 0);
                        $pdf->Cell(15,25, 'B: ',0,0, 'R');
                        $pdf->MultiCell(25, 0, $pdf->Image("../assets/uploads/".$department."/".$user_div."/".$exam_id."/Multiple Image/".$MultipleImage['option_id']."/option2/".$MultipleImage['img_2'], $pdf->GetX(),$pdf->GetY(),25,25), 0, 0);
                        $pdf->Cell(15,25, 'C: ',0,0, 'R');
                        $pdf->MultiCell(25, 0, $pdf->Image("../assets/uploads/".$department."/".$user_div."/".$exam_id."/Multiple Image/".$MultipleImage['option_id']."/option3/".$MultipleImage['img_3'], $pdf->GetX(),$pdf->GetY(),25,25), 0, 0);
                        $pdf->Cell(15,25, 'D: ',0,0, 'R');
                        $pdf->MultiCell(25, 0, $pdf->Image("../assets/uploads/".$department."/".$user_div."/".$exam_id."/Multiple Image/".$MultipleImage['option_id']."/option4/".$MultipleImage['img_4'], $pdf->GetX(),$pdf->GetY(),25,25), 0, 0);
                        $pdf->Ln(10);
                        $pdf->Ln(20);
                        $pdf->Ln(20);

                    }

                }
                else if ($testV == 'Essay') {
                    $pdf->Cell(15,5, 'Test V:',0,0);
                    $pdf->Cell(177, 5, ' Essay', 0, 1);
                    $pdf->SetFont('Times', '', 13);
                    $pdf->MultiCell(177, 5, 'Directions: Answer the question to the best of your knowledge. Write your answer in the space provided', 0, 0);
                    $pdf->Ln(20);
                    while ($Essay = $EssayQuery->fetch()) {
                        $ans = $Essay['correct_incorrect'];
                        if ($ans == 'correct'){
                            $check = "4";
                            $pdf->SetFont('Times', '', 11);
                            $pdf->Cell(15,5, $Essay['score_points'].' Points',0,0);
                            $pdf->SetFont('ZapfDingbats','', 10);
                            $pdf->Cell(5,5, $check,0,0);

                        }else if ($ans == 'incorrect'){
                            $cross = "8";
                            $pdf->SetFont('Times', '', 11);
                            $pdf->Cell(15,5, $Essay['score_points'].' Points',0,0);
                            $pdf->SetFont('ZapfDingbats','', 10);
                            $pdf->Cell(5,5, $cross,0,0);

                        }
                        $pdf->SetFont('Times', '', 11);
                        $pdf->Cell(6, 5, $index++ . '. ', 0, 0);
                        $pdf->MultiCell(160, 5, $Essay['question'].' ('.$Essay['points'].' point/s)', 0, 1);
                        $pdf->Ln(5);
                        $pdf->Cell(20, 5, '', 0, 0);
                        $pdf->SetFont('Times', 'U', 11);
                        $pdf->MultiCell(166, 5, '           '. $Essay['result_ans'] . '              ', 0, 1);
                        $pdf->Ln(5);
                    }
                }
                $pdf->Ln(10);
            }
            // Test V End



            $pdf->Output('', $userFname.'_'.$exam_title.'.pdf');
        }
        else { ?>
            <script>
                window.close();
            </script>
        <?php }
    }?>