<?php
global $conn;
session_start();
require "../../db/conn.php";
$email = $_SESSION['user'];
$department = $_SESSION['department'];
$query = $conn->query("SELECT * FROM user WHERE email = '$email' OR username = '$email'");
$row = $query->fetch();
$idU = $row['id'];
$department = $conn->query("SELECT YEAR(a.`AccDate`) AS Fullyear, COUNT(a.`id`) AS countUser, b.`division` FROM user a LEFT JOIN user_division b ON a.`id` = b.`user_id` WHERE a.`usertype` = 'examinee' AND a.`department` = '$department  ' GROUP BY b.`division`  ");
$d = 1;
$departmentData = '';
$departmentCount = 0;
$departmentYear = '';
while ($dept = $department->fetch()){

    if ($d == 1){
        $departmentYear .= $dept['Fullyear'];
        $departmentCount .= $dept['countUser'];
        $departmentData .= "'".$dept['division']."'";
    }else {
        $departmentYear .= ", ".$dept['Fullyear'];
        $departmentData .= ", '".$dept['division']."'";
        $departmentCount .= ", ".$dept['countUser'];
    }
    $d++;
}
?>
<script>
    $(document).ready(function (){
        var curr_yer = new Date().getFullYear();
        var _start_year = [<?php echo $departmentYear; ?>];
         var start = Math.min.apply(Math, _start_year);
        $("#curr_year").html(curr_yer);
        $("#start_year").html(start);
        var ctx = document.getElementById("department_chart").getContext("2d");
        var myData = [<?php echo $departmentData; ?>].length;
        var myChart = new Chart(ctx, {
            type: "pie",
            data: {
                datasets: [{
                    data: [<?php echo $departmentCount; ?>],
                    label: "Dataset 1"
                }],
                labels: [<?php echo $departmentData; ?>],
            },
            options: {
                responsive: true,
                legend: {
                    position: "bottom",
                },
                plugins: {
                    colorschemes: {
                        scheme: 'brewer.Paired12'
                    }
                }
            }
        });
    });
</script>
<canvas id="department_chart"></canvas>
