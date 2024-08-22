<?php
global $conn;
session_start();
require "../../db/conn.php";
if (isset($_REQUEST['userid'])){
    $result1 = '';
    $result2 = '';
    $result3 = '';
    $department = $_SESSION['department'];
    $userid = $_REQUEST['userid'];
    $userQ = $conn->query("SELECT * FROM user WHERE id = '$userid'");
    $user = $userQ->fetch();
    $userfullname = $user['lname']. ", " . $user['fname'] . " " . $user['mname'];
    $result1 .= '<script>
                    $(document).ready( function (){
                        $.post("fragments/division_select.php", {user_id:'.$userid.'}, function (div){
                            $("#divisionsel").html(div);
                        });
                        $("#gendersel").val("'.$user['gender'].'");
                        $("#btnResetPass").on("click", function (){
                        var user_id = $(this).attr("data-id");
                        showModal();
                            $("#loader").modal("show");
                            $("#btnEditDetails").show();
                            $("#btnSaveDetails").hide();
                            $("#btnCancelDetails").hide();
                            $("#viewModal").modal("hide");
                            $("#resetDepartmentPassword").modal("show");
                            $.post("fragments/reset_password.php", {user_id:user_id}, function (reset){
                                hideModal();
                                $("#loader").modal("hide");
                                $("#resetDepartmentPasswordContainerBody").html(reset)
                            });
                        });
                    });
                </script>';
    $result1 .= '<br>
                    <table class="table table-sm table-striped table-responsive-lg">
                   <thead>
                    <tr style="line-height: 35px;">
                        <td style="font-weight: 700">Basic Information:</td>
                        <td width="70%"></td>
                    </tr>
                   </thead>
                   <tbody>
                    <tr style="line-height: 25px;">
                        <td>Exam Control No.</td>
                        <td id="td_exam_no">'. $user['exam_no'] .'<input class="form-control" type="hidden" value="'.$userid.'" id="txt_user_id" name="txt_user_id"></td>
                        <td id="td_exam_no_edit" style="display: none;"><input class="form-control" type="text" value="'. $user['exam_no'] .'" id="txt_exam_no" name="txt_exam_no" required></td>
                    </tr>
                    <tr style="line-height: 25px;">
                        <td>Username:</td>
                        <td id="td_username">'. $user['username'] .'</td>
                        <td id="td_username_edit" style="display: none;"><input class="form-control" type="text" value="' . $user['username'] . '" id="txt_username" name="txt_username"required> </td>
                    </tr>
                    <tr style="line-height: 35px;">
                        <td style="font-weight: 700;">Full Name:</td>
                        <td></td>
                    </tr>
                    <tr style="line-height: 25px;">
                        <td class="text-right">Last Name:</td>
                        <td id="td_lname">'. $user['lname'] .'</td>
                        <td id="td_lname_edit" style="display: none;"><input class="form-control" type="text" value="' . $user['lname'] . '" id="txt_lname" name="txt_lname" required> </td>
                    </tr>
                    <tr style="line-height: 25px;">
                        <td class="text-right">First Name:</td>
                        <td id="td_fname">'. $user['fname'] .'</td>
                        <td id="td_fname_edit" style="display: none;"><input class="form-control" type="text" value="' . $user['fname'] . '" id="txt_fname" name="txt_fname" required> </td>
                    </tr>
                    <tr style="line-height: 25px;">
                        <td class="text-right">Middle Name:</td>
                        <td id="td_mname">'. $user['mname'] .'</td>
                        <td id="td_mname_edit" style="display: none;"><input class="form-control" type="text" value="' . $user['mname'] . '" id="txt_mname" name="txt_mname"> </td>
                    </tr>
                    <tr style="line-height: 25px;">
                        <td>Division: </td>
                        <td id="td_division">';
                        $division = $conn->query("SELECT * FROM user_division WHERE user_id = '$userid'");
                        while($div = $division->fetch()){
                            $result1 .= "<div class='bullet'></div>".$div['division']."<br>";
                        }
    $result1 .= '</td>
                        <td id="td_division_edit" style="display: none;">
                        <ul class="list-group">
                           <li class="list-group-item">
                               <div id="divisionsel">
                               </div>
                           </li>
                        </ul>
                      </td>
                    </tr>
                    <tr style="line-height: 25px;">
                        <td>Email: </td>
                        <td id="td_email">' . $user['email'] . ' </td>
                        <td id="td_email_edit" style="display: none;"><input class="form-control" type="email" value="' . $user['email'] . '" id="txt_email" name="txt_email" required></td>
                    </tr>
                    <tr style="line-height: 25px;">
                        <td>Gender:</td>
                        <td id="td_gender" >' . $user['gender'] . ' </td>
                        <td id="td_gender_edit" style="display:none;">
                            <select id="gendersel" class="form-control" name="gendersel" required>
                                <option value="">Select Gender</option>
                                <option value="Male">Male</option>
                                <option value="Female">Female</option>
                            </select>
                        </td>
                    </tr>
                    <tr style="line-height: 25px;">
                        <td>Phone No.</td>
                        <td id="td_contact">' . $user['contact_no'] . ' </td>
                        <td id="td_contact_edit" style="display:none;"><input id="txt_phone" type="text" pattern="\d*" maxlength="11" class="form-control" value="' . $user['contact_no'] . '" name="txt_phone" required/></td>
                    </tr>
                   </tbody>
                   </table>';
    $result2 .= '<div class="float-right">
                    <button class="btn btn-warning" type="button" id="btnResetPass" data-id="'. $user['id'] .'">Reset Password</button>
                </div>';
    $result2 .= '<br> <table class="table table-sm table-striped table-responsive-lg">
                    <thead>
                        <tr style="line-height: 35px;">
                            <td style="font-weight: 700">Address Information:</td>
                            <td width="70%"></td>
                        </tr>
                        </thead>
                    <tbody>
                            <tr style="line-height: 25px;">
                                <td>Home: </td>
                                <td id="td_home">' . $user['home_address'] . ' </td>
                                <td id="td_home_edit" style="display: none;"><input class="form-control" type="text" value="' . $user['home_address'] . '" id="txt_home" name="txt_home" required></td>
                            </tr>
                            <tr style="line-height: 25px;">
                                <td>Barangay: </td>
                                <td id="td_brgy">' . $user['brgy'] . ' </td>
                                <td id="td_brgy_edit" style="display: none;"><input class="form-control" type="text" value="' . $user['brgy'] . '" id="txt_brgy" name="txt_brgy" required></td>
                            </tr>
                            <tr style="line-height: 25px;">
                                <td>City/Municipality: </td>
                                <td id="td_city">' . $user['city'] . ' </td>
                                <td id="td_city_edit" style="display: none;"><input class="form-control" type="text" value="' . $user['city'] . '" id="txt_city" name="txt_city" required></td>
                            </tr>
                            <tr style="line-height: 25px;">
                                <td>Province: </td>
                                <td id="td_province">' . $user['province'] . ' </td>
                                <td id="td_province_edit" style="display: none;"><input class="form-control" type="text" value="' . $user['province'] . '" id="txt_province" name="txt_province" required></td>
                            </tr>
                            <tr style="line-height: 25px;">
                                <td>Postal Code: </td>
                                <td id="td_postal_code">' . $user['postal_code'] . ' </td>
                                <td id="td_postal_code_edit" style="display: none;"><input class="form-control" type="text" value="' . $user['postal_code'] . '" id="txt_postal_code" name="txt_postal_code" required></td>
                            </tr>
                    </tbody>
                    </table>
                    ';

    echo  $result1 . $result2 . $result3;
}