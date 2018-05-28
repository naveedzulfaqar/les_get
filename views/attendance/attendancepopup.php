<!doctype html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <title>::.. Attendance Modification ADMIN ..::</title>
        <link rel="stylesheet" href="<?php echo site_url('assets/css/bootstrap.min.css'); ?>">
        <link rel="stylesheet" href="<?php echo site_url('assets/css/bootstrap.css'); ?>">
        <link rel="stylesheet" href="<?php echo site_url('assets/admin/style.css'); ?>">        
        <link rel="stylesheet" href="<?php echo site_url('assets/css/datatable.css'); ?>">
        <link rel="stylesheet" href="<?php echo site_url('assets/admin/bootstrap-timepicker.css'); ?>">
        <link rel="stylesheet" href="<?php echo site_url('assets/admin/bootstrap-timepicker.min.css'); ?>">
        <link rel="stylesheet/less" href="<?php echo site_url('assets/admin/timepicker.less'); ?>">
        <link rel="shortcut icon" href="<?php echo site_url('assets/images/LA-Favicon.jpg'); ?>">
        <link rel="stylesheet" href="//code.jquery.com/ui/1.11.2/themes/smoothness/jquery-ui.css">
        <script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.7/jquery.js"></script>
        <script type="text/javascript" src="<?php echo site_url('assets/js/attendancedetails.js'); ?>"></script>
        <script type="text/javascript" src="<?php echo site_url('assets/js/bootstrap-timepicker.js'); ?>"></script>
        <script type="text/javascript" src="<?php echo site_url('assets/js/bootstrap-timepicker.min.js'); ?>"></script>
        <!---->
    </head>
    <body>
        <!-- CONTENT START --> 
        <div class="container content">
            <!-- PERSONAL INFORMATION FORM START -->
            <?php /* echo '<pre>'; print_r($employeeshift); echo '</pre>'; */ ?>
            <h3 style="font-size: 23px; color: #2fa4e7;"><span>Modify Attendance <?php echo 'of ' . $employee['FirstName'] . ' ' . $employee['LastName']; ?></span></h3>
            <?php if ($WorkingDay == '0') { ?>
                <p style="font-weight: bold; font-size: 14px;line-height: 21px;float: left;">Shift: </p>
                <p style="color:red; font-weight: bold; font-size: 14px;float: left;margin-left: 5px;"> OFF DAY</p><div style="clear: both;"></div>
            <?php } ?>

            <?php if ($AID == '0') { ?>
                <p style="font-weight: bold; font-size: 14px;line-height: 21px;float: left;">Attendance: </p>
                <p style="color:red; font-weight: bold; font-size: 14px;float: left;margin-left: 5px;"> ABSENT <?php
                    if ($holiday) {
                        echo ' (' . $holiday['HolidayTitle'] . ')';
                    }
                    ?></p><div style="clear: both;"></div>
                <?php } ?>
            <p style="font-weight: bold; font-size: 12px; color: #2fa4e7; line-height: 21px;float: left;">Shift : <a href="<?php echo site_url(File_BASE_URL . '/shifts/view/' . $employeeshift['ShiftID']); ?>" target="_blank"><?php echo $employeeshift['Name']; ?></a></p>
            <div style="clear: both;"></div>
            <form class="form-horizontal" role="form" action="" method="post" enctype="multipart/form-data" id="modifyattendancepopup"> 
                <div class="table-responsive">
                    <table class="table table-striped table-bordered" width="50%" cellspacing="0">       
                        <colgroup>
                            <col class="col-xs-2">
                            <col class="col-xs-2"> 
                            <col class="col-xs-2">
                        </colgroup>
                        <thead>
                            <tr class="trformat trheight">
                                <th class="thformat" >Date</th>
                                <th class="thformat" >Time In</th>
                                <th class="thformat">Time Out</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr id="">
                                <?php
                                if ($this->session->userdata('id') == '189') {
                                    //echo '<pre>';  print_r($employeeshift);  echo '</pre>';
                                }
                                ?>

                        <input type="hidden" id="EmployeeID" name="EmployeeID" value="<?php echo $employee['ID']; ?>" />
                        <input type="hidden" id="ShiftID" name="ShiftID" value="<?php echo $employeeshift['ShiftID']; ?>" />
                        <input type="hidden" id="AID" name="AID" value="<?php echo $AID; ?>" />
                        <input type="hidden" id="Day" name="Day" value="<?php echo $Day; ?>" /> 
                        <input type="hidden" id="date" name="Date" value="<?php echo $Date; ?>" /> 
                        <input type="hidden" id="ShiftStartTime" name="ShiftStartTime" value="<?php echo $employeeshift['ShiftStartTime']; ?>" /> 
                        <input type="hidden" id="ShiftEndTime" name="ShiftEndTime" value="<?php echo $employeeshift['ShiftEndTime']; ?>" /> 
                        <input type="hidden" id="WorkingDay" name="WorkingDay" value="<?php echo $WorkingDay; ?>" />
                        <td style="text-align:center">
                            <span class="form-control viewdetails viewdisabled">
                                <?php echo $Date . ' (' . $Day . ')'; ?>
                            </span>
                        </td>    
                        <td style="text-align:center">
                            <div class="input-group bootstrap-timepicker timepicker timepicker1">
                                <?php if (count($employeeshift) > 0) { ?>
                                    <input id="timein" type="text" name="timein" class="form-control input-small input-group-addon" onchange="get_attendance_details()" 
                                           value="<?php
                                           if ($employeeshift['InTime'] != 'Off') {
                                               echo $employeeshift['InTime'];
                                           } else {
                                               echo $employeeshift['ShiftStartTime'];
                                           }
                                           ?>" required  readonly="readonly">

                                <?php } else { ?>
                                    <input id="timein" type="text" name="timein" class="form-control input-small input-group-addon" onchange="get_attendance_details()" 
                                           value="" placeholder="Select InTime" required  readonly="readonly">  
                                       <?php } ?>
                                <span class="input-group-addon"><i class="glyphicon glyphicon-time"></i></span>
                            </div>  
                        </td> 
                        <td style="text-align:center">
                            <?php
                            if (date('Y-m-d', strtotime($Date)) == date('Y-m-d') && ($employeeshift['OutTime'] == 'Off' || $employeeshift['OutTime'] == '')) {
                                echo '<span>Continue</span>';
                            } else {
                                ?>
                                <div class="input-group bootstrap-timepicker timepicker timepicker2">
                                    <?php if (count($employeeshift) > 0) { ?>
                                        <input id="timeout" type="text" name="timeout" class="form-control input-small input-group-addon" onchange="get_attendance_details()" 
                                               value="<?php
                                               if ($employeeshift['OutTime'] != 'Off') {
                                                   echo $employeeshift['OutTime'];
                                               } else {
                                                   echo $employeeshift['ShiftEndTime'];
                                               }
                                               ?>" required readonly="readonly">

                                    <?php } else { ?>
                                        <input id="timeout" type="text" name="timeout" class="form-control input-small input-group-addon" onchange="get_attendance_details()" 
                                               value="" placeholder="Select OutTime" required readonly="readonly">  
                                           <?php } ?>
                                    <span class="input-group-addon"><i class="glyphicon glyphicon-time"></i></span>
                                </div>
                                <?php
                            }
                            ?>

                        </td>
                        </tr>
                        </tbody>
                    </table>
                </div>
                <?php // if ($this->session->userdata('id') == '189') {       ?>
                <div class="table-responsive">
                    <table class="table table-striped table-bordered" width="50%" cellspacing="0">       
                        <colgroup>
                            <col class="col-xs-2"> 
                            <col class="col-xs-2">
                            <?php if ($AID != '0') { ?>
                                <col class="col-xs-2">
                                <col class="col-xs-2"> 
                            <?php } ?>
                            <col class="col-xs-2">
                        </colgroup>
                        <thead>
                            <tr class="trformat trheight">
                                <th class="thformat">Logged In Duration</th> 
                                <th class="thformat">Allowed Break</th> 
                                <?php if ($AID != '0') { ?>
                                    <th class="thformat">Total Productive Break</th> 
                                    <th class="thformat">Total Non-Productive Break</th>
                                <?php } ?>
                                <th class="thformat">Calculated Worked Duration</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr id="">       

                                <td style="text-align:center">
                                    <span class="form-control viewdetails viewdisabled" id="loggedin"></span>
                                </td>
                                <td style="text-align:center">
                                    <span class="form-control viewdetails viewdisabled" id="allowedbreak"></span>
                                </td>
                                <?php if ($AID != '0') { ?>
                                    <td style="text-align:center">
                                        <span class="form-control viewdetails viewdisabled" id="productivebreak"></span>
                                    </td>
                                    <td style="text-align:center">
                                        <span class="form-control viewdetails viewdisabled" id="nonproductivebreak"></span>
                                    </td>
                                <?php } ?>

                                <td style="text-align:center">
                                    <span class="form-control viewdetails viewdisabled" id="calculated_worked_duration"></span>
                                </td>

                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <?php //}      ?>


                <div class="form-group"> 
                    <div class="col-lg-6">
                        <label> 
                            Comments<span class="mandatory">*</span>
                        </label>
                        <textarea class="form-control" id="comments" rows="2" name="comments" ></textarea><!--required-->
                    </div>
                </div>

                <input type="button" id="get_attendance" name="get_attendance" class="btn btn-primary btn-lg btn-block" value="Save Attendance" style="font:menu; font-size:14px" onClick="validateForm()"></input>
                <iframe width="200" height="25" frameborder="0" src="<?php echo GET_REPORT_URL . 'images/ajax-loader.gif'; ?>" scrolling="no" id="a_loader" style="display:none;"></iframe>
                <?php if ($AID == '0') { ?>
                    <div class="notifications" style="font-size: 16px">
                        Please don't adjust leave from this popup.
                    </div>
                <?php } ?>
            </form>
            <!-- PERSONAL INFORMATION FORM END -->
        </div>
        <!-- CONTENT END --> 
        <script>
            $(document).ready(function () {
                $('#timein').timepicker();
                $('#timeout').timepicker();
            });
            function validateForm() {
                var pathname = window.opener.location;
                var timein = $("#timein").val();
                var timeout = $("#timeout").val();
                var comments = $("#comments").val();

                $('#timein').css('border-color', 'green');
                $('#timeout').css('border-color', 'green');
                $('#comments').css('border-color', 'green');

                var error = 0;

                if (timein == '') {
                    $('#timein').css('border-color', 'red');
                    error = 1;
                }
                if (timeout == '') {
                    $('#timeout').css('border-color', 'red');
                    error = 1;
                }
                if (comments == '') {
                    $('#comments').css('border-color', 'red');
                    error = 1;
                }
                /*console.log("timein:"+$("#timein").val()+", timeout:"+$("#timeout").val()+", comments:"+$("#comments").val());
                 console.log("226 error:"+error);
                 return false;*/
                if (error == 0) {
                    $('#get_attendance').hide();
                    $('#a_loader').show();

                    var url = window.opener.location.href;
                    if (url.indexOf("shiftreports") == '-1') {
                        window.opener.document.getElementById("bttnatt").click();
                    }
                    $.post("", {timein: $("#timein").val(), timeout: $("#timeout").val(), EmployeeID: $("#EmployeeID").val(), AID: $("#AID").val(), ShiftID: $("#ShiftID").val(), Day: $("#Day").val(), date: $("#date").val(), comments: $("#comments").val(), ShiftStartTime: $("#ShiftStartTime").val(), ShiftEndTime: $("#ShiftEndTime").val(), WorkingDay: $("#WorkingDay").val()}, function (data) {
                        if (url.indexOf("shiftreports") == '-1') {
                            window.opener.document.getElementById("bttnatt").click();
                        }

                        //console.log('attendance popup');
                        window.close();
                        if (url.indexOf("shiftreports") != '-1') {
                            window.opener.location.reload();
                        }
                    });

                }
            }
        </script>

        <?php $this->load->view('templates/shiftreports_footer'); ?>   
        <?php $this->load->view('templates/footer'); ?>