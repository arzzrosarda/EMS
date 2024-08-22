<?php
global $conn;
session_start();
require "../../../db/conn.php";
$email = $_SESSION['user'];
$query = $conn->query("SELECT * FROM user WHERE email = '$email' OR username = '$email'");
$row = $query->fetch();
$idU = $row['id'];
?>
<script>
    $(document).ready(function (){
        $('#alog').DataTable( {
            order: [[ 0, 'desc' ]],
            "bAutoWidth": false
        });
    });
</script>
<div class="table-responsive">
<table class="table table-condensed table-sm" id="alog">
    <thead>
    <tr>
        <th scope="col" style="display: none;">ID</th>
        <th scope="col">User name</th>
        <th scope="col">Activity</th>
        <th scope="col">Date</th>
        <th scope="col">Time</th>
    </tr>
    </thead>
    <tbody>
    <?php
    $index = 1;
    $logQ = $conn->query("SELECT * FROM ulog");
    if ($logQ->rowCount() > 0){
        while ($log = $logQ->fetch()){
            $logid = $log['userID'];
            $userQuery = $conn->query("SELECT * FROM user WHERE id = '$logid' AND usertype = 'admin' ");
            while ($user = $userQuery->fetch()){ ?>
                <tr  style="line-height: 30px;">
                    <td style="display: none;"><?php echo $log['id']; ?></td>
                    <td><?php echo $user['lname']. ", " . $user['fname'] . " " . $user['mname']; ?></td>
                    <td><?php echo $log['logs']; ?></td>
                    <td><?php echo $log['logDate']; ?></td>
                    <td><?php echo $log['logTime']; ?></td>
                </tr>
            <?php }

        }

    }
    ?>
    </tbody>
</table>
</div>