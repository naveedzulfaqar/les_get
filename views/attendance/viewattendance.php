<!doctype html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <title>::.. ADMIN ..::</title>
        <link rel="stylesheet" href="<?php echo site_url('assets/css/bootstrap.min.css'); ?>">
        <link rel="stylesheet" href="<?php echo site_url('assets/css/bootstrap.css'); ?>">
        <link rel="stylesheet" href="<?php echo site_url('assets/admin/style.css'); ?>">
        <link rel="stylesheet" href="<?php echo site_url('assets/css/datatable.css'); ?>">
        <link rel="shortcut icon" href="<?php echo site_url('assets/images/LA-Favicon.jpg'); ?>">
        <link rel="stylesheet" href="//code.jquery.com/ui/1.11.2/themes/smoothness/jquery-ui.css">
        <script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.7/jquery.js"></script>
        <!---->
    </head>
    <body>
        <!-- CONTECT START --> 
        <div class="container content">
            <!-- PERSONAL INFORMATION FORM START -->
            <?php /* echo '<pre>'; print_r($view_attendance); echo '</pre>'; */ ?>
            <h3 style="font-size: 23px; color: #2fa4e7;"><span>Attendance <?php echo 'of ' . $employee['FirstName'] . ' ' . $employee['LastName']; ?></span></h3>
            <h3 style="font-size: 16px; color: #2fa4e7;"><span>Attendance Log Date <?php
                    echo $AttendanceDate;
                    if ($holiday) {
                        echo ' (' . $holiday['HolidayTitle'] . ')';
                    }
                    ?></span></h3>

            <div class="table-responsive top">
                <table class="table table-striped table-bordered" width="100%" cellspacing="0">
                    <colgroup>
                        <col class="col-xs-3">
                        <col class="col-xs-1">
                        <col class="col-xs-2">
                        <col class="col-xs-2">
                        <col class="col-xs-2">
                        <col class="col-xs-2">

                    </colgroup>
                    <thead>
                        <tr>
                            <!--<th>Date</th>-->

                            <th><?php echo $employee['FirstName']; ?> <?php echo $employee['LastName']; ?>'s Timing</th>
                            <th>Attendance Marked By</th>
                            <th>Comments: </th>
                            <th>System Name </th>
                            <th>IP Address</th>
                            <th>Log Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $a = 1;
                        $attendanceID = 0;
                        /* echo '<br>ShiftID: '.$ShiftID;
                          echo '<br>EmployeeID: '.$EmployeeID;
                          echo '<br>AttendanceDate: '.$AttendanceDate; */
                        /* echo '<br>GMTModifiedDate: '.$ModifiedDate;
                          echo '<br>AttModifiedDate: '.$attendanceMD;
                          echo '<br>Date: '.$Date;
                          echo '<br>ITime: '.$ITime;
                          echo '<br>Time: '.$Time; */
                        if ($this->session->userdata('id') == '189') {
                            /* echo '<pre>'; print_r($view_attendance); echo '</pre>'; */
                        }

                        foreach ($view_attendance as $key => $attendance) {
                            ?>
                            <tr id="listItem_<?php echo $key; ?>">
                                <?php /* ?><td><?php echo $Day . '<br>' . $attendance['Date']; ?></td><?php */ ?>
                                <td>
                                    <strong>Time In:</strong> <?php echo $attendance['InTime'] . ' (' . $attendance['Date'] . ')'; ?> <br/>
                                    <strong>Time Out:</strong> <?php echo $attendance['OutTime'] . ' (' . $attendance['EndDate'] . ')'; ?> <br/>
                                    <?php if($this->session->userdata('id') == '189' || $this->session->userdata('id') == '321') { ?>
                                        <br/><strong>GMT Time In:</strong> <?php echo $attendance['GMTInTime']; ?> <br/>
                                        <strong>GMT Time Out:</strong> <?php echo $attendance['GMTOutTime'] ; ?> <br/><br/>
                                    <?php }?>
                                    <strong>By:</strong> <?php
                                    if ($attendance['Source'] == 'website') {
                                        echo "Admin Panel";
                                    } else if ($attendance['Source'] == 'webservice') {
                                        echo "Desktop Application";
                                    } else {
                                        echo $attendance['Source'];
                                    }
                                    ?><br/><br/>
                                    <strong>Total Time:</strong> <?php
                                    /* $WorkedHours = (strtotime($attendance['OutTime']) - strtotime($attendance['InTime'])) / 3600; round($attendance['WorkedHours'], 1) . ' hours ' */
                                    if ($attendance['OutTime'] == '-') {
                                        echo ' - ';
                                    } else {
                                        echo CalculateWorkedTime($attendance['WorkedHours']);
                                    }
                                    ?>

                                    <?php
                                    if ($attendance['AttendanceCrash'] == '1') {
                                        echo '(crash) ';
                                    }
                                    if ($attendance['OTApproved'] == '1') {
                                        echo '<br><br><strong>Overtime Approved</strong>';
                                        echo '<br><strong>OT Approved Comments : </strong>' . $attendance['OTApprovedComments'];
                                    }
                                    ?>
                                </td>
                                <td><?php echo $attendance['AddedBy']['FirstName'] . ' ' . $attendance['AddedBy']['LastName']; ?></td>
                                <td><?php echo $attendance['Comments']; ?></td>
                                <td><?php echo $attendance['ComputerName']; ?></td>
                                <td><?php echo $attendance['IPAddress']; ?></td>
                                <td><?php echo $attendance['ModifiedDate']; 
                                 if($this->session->userdata('id') == '189') {
                                        echo '<br><br>GMT Modified Date: '.$attendance['GMTModifiedDate'];
                                    }
                                ?></td>
                            </tr> 
                            <?php
                            $a++;
                        }
                        ?>
                    </tbody>
                </table>
            </div>
            <!-- PERSONAL INFORMATION FORM END -->
        </div>
        <!-- CONTECT END --> 

        <script>
            window.onunload = refreshParent;
            function refreshParent() {
                window.opener.document.getElementById("bttnatt").click();
            }
        </script>
        <?php $this->load->view('templates/shiftreports_footer'); ?>   
        <?php $this->load->view('templates/footer'); ?>