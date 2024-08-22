<?php
global $conn;
session_start();
require "../../../db/conn.php";
$email = $_SESSION['user'];
$query = $conn->query("SELECT * FROM user WHERE email = '$email' OR username = '$email'");
$row = $query->fetch();
$idU = $row['id'];
$department = $conn->query("SELECT a.`department`,YEAR(b.`AccDate`) AS Fullyear, b.`username`, COUNT(b.`id`) AS countUser FROM department a LEFT JOIN user b ON a.`department` = b.`department` WHERE b.`usertype` = 'examinee' GROUP BY a.`id`");
$d = 1;
$departmentData = '';
$departmentCount = 0;
$departmentYear = '';
while ($dept = $department->fetch()){

    if ($d == 1){
        $departmentYear .= $dept['Fullyear'];
        $departmentCount .= $dept['countUser'];
        $departmentData .= "'".$dept['department']."'";
    }else {
        $departmentYear .= ", ".$dept['Fullyear'];
        $departmentData .= ", '".$dept['department']."'";
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
