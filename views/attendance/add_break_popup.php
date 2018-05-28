<!doctype html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <title>::.. ADMIN ..::</title>
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
        <script type="text/javascript" src="<?php echo site_url('assets/js/bootstrap-timepicker.js'); ?>"></script>
        <script type="text/javascript" src="<?php echo site_url('assets/js/bootstrap-timepicker.min.js'); ?>"></script>
        <!---->
    </head> 
    <body>
        <!-- CONTENT START --> 
        <div class="container content">
            <!-- PERSONAL INFORMATION FORM START -->
            <?php /* echo '<pre>'; print_r($employee); echo '</pre>'; */ ?>
            <h3 style="font-size: 23px; color: #2fa4e7;">
                <?php if ($BLogID == 0) { ?>
                    <span>Add Break <?php echo 'of ' . $employee['FirstName'] . ' ' . $employee['LastName']; ?></span>
                <?php } else { ?>
                    <span>Modify Break <?php echo 'of ' . $employee['FirstName'] . ' ' . $employee['LastName']; ?></span>
                <?php } ?>
            </h3>
            <p style="font-weight: bold; font-size: 12px;line-height: 21px;float: left;"><?php echo 'Break Date: ' . $Day . ', ' . $Date; ?></p>
            <div style="clear: both;"></div>
            <?php /* ?><p style="font-weight: bold; font-size: 12px; color: #2fa4e7; line-height: 21px;float: left;"><?php echo 'Shift : '. $employeeshift['Name'];?></p><?php */ ?>
            <div style="clear: both;"></div>
            <form class="form-horizontal" role="form" action="" method="post" enctype="multipart/form-data" id="addbreakform"> 
                <div class="table-responsive">

                    <table class="table table-striped table-bordered" width="50%" cellspacing="0">       
                        <colgroup>
                            <col class="col-xs-2">
                            <col class="col-xs-2"> 
                            <col class="col-xs-2">
                            <?php if ($BLogID != 0) { ?>
                                <col class="col-xs-4">
                            <?php } ?>
                        </colgroup>
                        <thead>
                            <tr class="trformat">
                                <th class="thformat" >Break Type</th>
                                <th class="thformat" >Break Start</th>
                                <th class="thformat">Break End</th>      
                                <?php if ($BLogID != 0) { ?>
                                    <th class="thformat">Break Comment</th>   
                                <?php } ?>
                            </tr>
                        </thead>
                        <tbody>
                            <tr id=""><?php /* echo '<pre>'; print_r($breaklog); echo '</pre>'; */ ?>
                        <input type="hidden" id="ID" name="ID" value="<?php echo $ID; ?>" />
                        <input type="hidden" id="BLogID" name="BLogID" value="<?php echo $BLogID; ?>" />
                        <input type="hidden" id="EmployeeID" name="EmployeeID" value="<?php echo $employee['ID']; ?>" />
                        <input type="hidden" id="AttendenceID" name="AttendenceID" value="<?php echo $AttendenceID; ?>" /> 
                        <input type="hidden" id="ShiftID" name="ShiftID" value="<?php echo $employeeshift['ShiftID']; ?>" />
                        <input type="hidden" id="AID" name="AID" value="<?php echo $AID; ?>" />
                        <input type="hidden" id="Day" name="Day" value="<?php echo $Day; ?>" /> 
                        <input type="hidden" id="Date" name="Date" value="<?php echo $Date; ?>" /> 
                        <td>
                            <select name="breaktype" id="breaktype" width="100" style="margin-top: 5px; width: 95%;" required>

                                <?php
                                if ($breaklog[0]['BreakType'] == 'Discussion' || $breaklog[0]['BreakType'] == 'discussion') {
                                    $selected0 = 'selected="selected"';
                                } else if ($breaklog[0]['BreakType'] == 'Meeting' || $breaklog[0]['BreakType'] == 'meeting') {
                                    $selected1 = 'selected="selected"';
                                } else if ($breaklog[0]['BreakType'] == 'Interview' || $breaklog[0]['BreakType'] == 'interview') {
                                    $selected2 = 'selected="selected"';
                                } else if ($breaklog[0]['BreakType'] == 'Prayer' || $breaklog[0]['BreakType'] == 'prayer') {
                                    $selected3 = 'selected="selected"';
                                } else if ($breaklog[0]['BreakType'] == 'Lunch/Dinner' || $breaklog[0]['BreakType'] == 'lunchDinner') {
                                    $selected4 = 'selected="selected"';
                                } else if ($breaklog[0]['BreakType'] == 'Tea' || $breaklog[0]['BreakType'] == 'tea') {
                                    $selected5 = 'selected="selected"';
                                } else if ($breaklog[0]['BreakType'] == 'Training' || $breaklog[0]['BreakType'] == 'training') {
                                    $selected6 = 'selected="selected"';
                                } else if ($breaklog[0]['BreakType'] == 'inactivity') {
                                    $selected9 = 'selected="selected"';
                                } else if ($breaklog[0]['BreakType'] == 'crash') {
                                    $selected10 = 'selected="selected"';
                                } else if ($breaklog[0]['BreakType'] == 'Network crash' || $breaklog[0]['BreakType'] == 'networkCrash') {
                                    $selected11 = 'selected="selected"';
                                } else if ($breaklog[0]['BreakType'] == 'Hibernate/Sleep' || $breaklog[0]['BreakType'] == 'hibernateSleep') {
                                    $selected12 = 'selected="selected"';
                                } else if ($breaklog[0]['BreakType'] == 'System Changed' || $breaklog[0]['BreakType'] == 'systemChanged') {
                                    $selected13 = 'selected="selected"';
                                } else if ($breaklog[0]['BreakType'] == 'Other' || $breaklog[0]['BreakType'] == 'other') {
                                    $selected14 = 'selected="selected"';
                                } else if ($breaklog[0]['BreakType'] == 'Manual Logout' || $breaklog[0]['BreakType'] == 'manualLogout') {
                                    $selected15 = 'selected="selected"';
                                } else if ($breaklog[0]['BreakType'] == 'ShutDown' || $breaklog[0]['BreakType'] == 'shutDown') {
                                    $selected16 = 'selected="selected"';
                                } else if ($breaklog[0]['BreakType'] == 'WC' || $breaklog[0]['BreakType'] == 'wc') {
                                    $selected17 = 'selected="selected"';
                                } else if ($breaklog[0]['BreakType'] == 'Coaching Session' || $breaklog[0]['BreakType'] == 'coachingSession') {
                                    $selected18 = 'selected="selected"';
                                }
                                ?>
                                <option value="">Select</option>
                                <option <?php echo $selected17; ?> value="wc">WC</option>
                                <option <?php echo $selected0; ?> value="discussion">Discussion</option>
                                <option <?php echo $selected1; ?> value="meeting">Meeting</option>
                                <option <?php echo $selected2; ?> value="interview">Interview</option>
                                <option <?php echo $selected6; ?> value="training">Training</option>
                                <option <?php echo $selected18; ?> value="coachingSession">Coaching Session</option>
                                <option <?php echo $selected3; ?> value="prayer">Prayer</option>
                                <option <?php echo $selected4; ?> value="lunchDinner">Lunch/Dinner</option>
                                <option <?php echo $selected5; ?> value="tea">Tea</option>                                
                                <option <?php echo $selected11; ?> value="networkCrash">Network Crash</option>
                                <option <?php echo $selected12; ?> value="hibernateSleep">Hibernate/Sleep</option>
                                <option <?php echo $selected13; ?> value="systemChanged">System Changed</option>
                                <option <?php echo $selected14; ?> value="other">Other</option>
                                <option <?php echo $selected15; ?> value="manualLogout">Manual Logout</option>
                                <option <?php echo $selected16; ?> value="shutDown">ShutDown</option>                                
                                <option <?php echo $selected9; ?> value="inactivity">Inactivity</option>
                                <option <?php echo $selected10; ?> value="crash">Crash</option>                                
                                <?php //}  ?>
                            </select>
                        </td>       
                        <td>
                            <?php
                            if ($breaklog) {
                                $breaktimein = $breaklog[0]['BreakStart'];
                                $breaktimeinvalue = $breaklog[0]['BreakStart'];
                            } else {
                                $breaktimein = 'Select Break Start';
                                $breaktimeinvalue = '';
                            }

                            if ($BLogID != 0) {
                                $timepicker1 = '';
                            } else {
                                $timepicker1 = 'timepicker1';
                            }
                            ?>
                            <div class="input-group bootstrap-timepicker timepicker <?php echo $timepicker1; ?>">
                                <input id="timein" type="text" name="timein" class="form-control input-small input-group-addon" value="<?php echo $breaktimein; ?>" readonly="readonly">
                                <span class="input-group-addon"><i class="glyphicon glyphicon-time"></i></span>
                            </div>

                        </td> 
                        <td>
                            <?php
                            if ($breaklog) {
                                $breaktimeout = $breaklog[0]['BreakEnd'];
                                $breaktimeoutvalue = $breaklog[0]['BreakEnd'];
                            } else {
                                $breaktimeout = 'Enter Break End';
                                $breaktimeoutvalue = '';
                            }

                            if ($BLogID != 0) {
                                $timepicker2 = '';
                            } else {
                                $timepicker2 = 'timepicker2';
                            }
                            ?>
                            <div class="input-group bootstrap-timepicker timepicker <?php echo $timepicker2; ?>">
                                <input id="timeout" type="text" name="timeout" class="form-control input-small input-group-addon" value="<?php echo $breaktimeout; ?>" readonly="readonly">
                                <span class="input-group-addon"><i class="glyphicon glyphicon-time"></i></span>
                            </div>
                        </td>
                        <?php
                        if ($BLogID != 0) {

                            if ($breaklog[0]['CheckedBy']['FirstName'] && $breaklog[0]['Comments'] != '') {
                                $commented_by = ' (<strong>By: </strong>' . $breaklog[0]['CheckedBy']['FirstName'] . ' ' . $breaklog[0]['CheckedBy']['LastName'] . ')';
                            } else if ($breaklog[0]['DeletedBy']['FirstName'] && $breaklog[0]['Comments'] != '') {
                                $commented_by = ' (<strong>Deleted By: </strong>' . $breaklog[0]['DeletedBy']['FirstName'] . ' ' . $breaklog[0]['DeletedBy']['LastName'] . ')';
                            } else {
                                if ($breaklog[0]['Comments'] != '') {
                                    $commented_by = ' <br><br>Regards,<br>' . $breaklog[0]['AddedBy']['FirstName'] . ' ' . $breaklog[0]['AddedBy']['LastName'] . '';
                                }
                            }
                            ?>

                            <td><?php
                                if ($breaklog[0]['Comments'] != '') {
                                    echo $breaklog[0]['Comments'] . $commented_by;
                                }
                                ?></td>
                        <?php } ?>

                        </tr>
                        </tbody>
                    </table>
                </div>
                <?php if ($this->session->userdata('id') == '189') { ?>
<!--                <div class="table-responsive" id="trainingbreaktype" style="display:none;">
                        <table class="table table-striped table-bordered" width="50%" cellspacing="0">       
                            <colgroup>
                                <col class="col-xs-2">
                                <col class="col-xs-2"> 
                                <col class="col-xs-2">
                            </colgroup>
                            <thead>
                                <tr class="trformat">
                                    <th class="thformat" >Training Type</th>
                                    <th class="thformat" ></th>
                                    <th class="thformat"></th> 
                                </tr>
                            </thead>
                            <tbody>
                                <tr id=""><?php /* echo '<pre>'; print_r($breaklog); echo '</pre>'; */ ?>
                                    <td>
                                        <select name="title" id="title" width="100" style="margin-top: 5px; width: 95%;" required>                                            
                                            
                                            <option <?php echo $selected6; ?> value="Training">Training</option>
                                        </select>
                                    </td>       
                                    <td>


                                    </td> 
                                    <td>

                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>-->
                <?php } ?>     

                <div class="form-group"> 
                    <div class="col-lg-6">
                        <label> 
                            Comments<span class="mandatory">*</span>
                        </label>
                        <textarea class="form-control" id="comments" rows="3" name="comments" ></textarea><!--required-->
                    </div>
                </div>
                <?php if ($BLogID == 0) { ?>
                    <input type="button" name="add_break" id="add_break" class="btn btn-primary btn-lg btn-block" value="Add Break" style="font:menu; font-size:14px" onClick="validateForm()" />
                <?php } else { ?>
                    <input type="button" name="add_break" id="add_break" class="btn btn-primary btn-lg btn-block" value="Update Break" style="font:menu; font-size:14px" onClick="validateForm()" />
                <?php } ?>
                <img id="loader"  style="margin-left:470px; display:none;"  src="/les/assets/images/ajax-loader.gif">
                <iframe width="200" height="25" frameborder="0" src="/les/assets/images/ajax-loader.gif" scrolling="no" id="a_loader" style="display:none;"></iframe>
            </form>
            <!-- PERSONAL INFORMATION FORM END -->
        </div>
        <!-- CONTENT END --> 

        <script>
            $(document).ready(function () {
//                $('#breaktype').change(function () {
//                    var timein = $(this).val();
//                });
                $('#timein').timepicker();
                $('#timeout').timepicker();

                $('#timein').change(function () {
                    var timein = $(this).val();
                    var timeout = $('#timeout').val();
                    //console.log(timein);
                    //console.log(timeout);
                    var breaktimeout = '<?php echo $breaktimeout; ?>';
                    //console.log(breaktimeout);
                    if (breaktimeout == 'Enter Break End') {
                        $("#timeout").val(timein);
                    }
                });
            });
            function validateForm() {
                var pathname = window.opener.location;
                var timein = $("#timein").val();
                var timeout = $("#timeout").val();
                var breaktype = $("#breaktype").val();
                var comments = $("#comments").val();

                $('#timein').css('border-color', 'green');
                $('#timeout').css('border-color', 'green');
                $('#breaktype').css('border-color', 'green');
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
                if (breaktype == '') {
                    $('#breaktype').css('border-color', 'red');
                    error = 1;
                }
                if (comments == '') {
                    $('#comments').css('border-color', 'red');
                    error = 1;
                }

                if (error == 0) {
                    $('#add_break').hide();
                    $('#a_loader').show();

                    var url = window.opener.location.href;
                    if (url.indexOf("shiftreports") == '-1' && url.indexOf("breakapprovalrequests") == '-1') {
                        window.opener.document.getElementById("bttnatt").click();
                    }
                    $.post("", {timein: $("#timein").val(), timeout: $("#timeout").val(), EmployeeID: $("#EmployeeID").val(), ID: $("#ID").val(), ShiftID: $("#ShiftID").val(), Day: $("#Day").val(), BreakDate: $("#Date").val(), comments: $("#comments").val(), BLogID: $("#BLogID").val(), breaktype: $("#breaktype").val(), AttendenceID: $("#AttendenceID").val()}, function (data) {
                        if (url.indexOf("shiftreports") == '-1' && url.indexOf("breakapprovalrequests") == '-1') {
                            window.opener.document.getElementById("bttnatt").click();
                        }
                        window.close();
                        if (url.indexOf("shiftreports") != '-1' && url.indexOf("breakapprovalrequests") == '-1') {
                            window.opener.location.reload();
                        }
                        console.log(url.indexOf("breakapprovalrequests"));
                        if (url.indexOf("breakapprovalrequests") != '-1') {
                            window.opener.document.getElementById("bttn_approval_requests").click();
                        }
                    });

                }
            }
        </script>

        <?php $this->load->view('templates/shiftreports_footer'); ?>   

        <?php $this->load->view('templates/footer'); ?>