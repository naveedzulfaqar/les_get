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
        <!-- CONTENT START --> 
        <div class="container content">
            <!-- PERSONAL INFORMATION FORM START -->
            <?php
            if ($this->session->userdata('id') == '189') {
                /* echo '<pre>'; print_r($employee); echo '</pre>'; */
            }
            if($RestrictionType == 'sr'){
                $type = 'Shift Over Time';
            } else if($RestrictionType == 'ot'){
                $type = 'Over Time';
            }
            ?>
            <h3 style="font-size: 23px; color: #2fa4e7;">
                <span><?php echo $type; ?> Approval Request <?php
                    echo 'of ' . $employee['FirstName'] . ' ' . $employee['LastName'];
                    if ($employee['PseudoFirstName']) {
                        echo ' (' . $employee['PseudoFirstName'] . ' ' . $employee['PseudoLastName'] . ')';
                    }
                    ?></span>
            </h3>
            <p style="font-weight: bold; font-size: 12px; color: #2fa4e7; line-height: 21px;float: left;">Shift : <a href="<?php echo site_url('index.php/shifts/view/' . $employeeshift['ShiftID']); ?>" target="_blank"><?php echo $employeeshift['Name']; ?></a></p>
            <div style="clear: both;"></div>
            <p style="font-weight: bold; font-size: 12px;line-height: 21px;float: left;"><?php echo 'Date: ' . $attendance['Date'] . ' (' . $employeeshift['DayName'] . ')'; ?></p>
            <div style="clear: both;"></div>
            <?php /* echo'<pre>'; print_r($employee); echo '</pre>'; */ ?>
            <form class="form-horizontal" role="form" action="" method="post" enctype="multipart/form-data" id="approveotform"> 
                <div class="table-responsive">
                    <input type="hidden" id="EmployeeID" name="EmployeeID" value="<?php echo $attendance['EmployeeID']; ?>" />
                    <input type="hidden" id="RestrictionType" name="RestrictionType" value="<?php echo $RestrictionType; ?>" />
                    <input type="hidden" id="ID" name="ID" value="<?php echo $attendance['ID']; ?>" />
                    <input type="hidden" id="RequestedTo" name="RequestedTo" value="<?php echo $employee['ReportingTo']; ?>" />
                    <table class="table table-striped table-bordered" width="50%" cellspacing="0">       
                        <colgroup>
                            <col class="col-xs-4">
                            <col class="col-xs-4">
                            <col class="col-xs-4">
                        </colgroup>
                        <thead>
                            <tr class="trformat">
                                <th class="thformat" >Shift Start Time</th>
                                <th class="thformat" >Shift End Time</th>
                                <th class="thformat" >Total Shift Duration</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr id="">
                                <td>
                                    <span class="form-control viewemployee" id="inputem3" style="text-align: center;">
                                        <?php
                                        echo $employeeshift['ShiftStartTime'];
                                        ?>
                                    </span>
                                </td> 
                                <td>
                                    <span class="form-control viewemployee" id="inputem3" style="text-align: center;">
                                        <?php
                                        echo $employeeshift['ShiftEndTime'];
                                        ?>
                                    </span> 
                                </td>
                                <td>
                                    <span class="form-control viewemployee <?php echo $border_color; ?>" id="inputem3" style="text-align: center;">
                                        <?php
                                        echo CalculateWorkedTime($employeeshift['ShiftsHours']);
                                        ?>
                                    </span> 
                                </td>

                            </tr>
                        </tbody>
                    </table>
                    <table class="table table-striped table-bordered" width="50%" cellspacing="0">       
                        <colgroup>
                            <col class="col-xs-3">
                            <col class="col-xs-3">
                            <col class="col-xs-3">
                            <col class="col-xs-3">
                        </colgroup>
                        <thead>
                            <tr class="trformat">
                                <th class="thformat" >Actual Time In</th>
                                <th class="thformat" >Actual Time Out</th>
                                <th class="thformat">Calculated Worked Duration</th>
                                <th class="thformat"><?php echo 'Unapproved '.$type; ?></th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr id="">
                                <td>
                                    <?php
                                    if (date('H:i', strtotime($attendance['InTime'])) < date('H:i', strtotime($employeeshift['ShiftStartTime']))) {
                                        $border_color = 'border-color';
                                    } else {
                                        $border_color = '';
                                    }
                                    ?>   
                                    <span class="form-control viewemployee <?php echo $border_color; ?>" id="inputem3" style="text-align: center;">
                                        <?php
                                        echo $attendance['InTime'];
                                        ?>
                                    </span>
                                </td>                         
                                <td>
                                    <?php
                                    if (date('H:i', strtotime($attendance['OutTime'])) > date('H:i', strtotime($employeeshift['ShiftEndTime']))) {
                                        $border_color = 'border-color';
                                    } else {
                                        $border_color = '';
                                    }
                                    ?>
                                    <span class="form-control viewemployee <?php echo $border_color; ?>" id="inputem3" style="text-align: center;">
                                        <?php
                                        echo $attendance['OutTime'];
                                        ?>
                                    </span> 
                                </td>
                                <td>
                                    <span class="form-control viewemployee" id="inputem3" style="text-align: center;">
                                        <?php
                                        echo CalculateWorkedTime($attendance['total_time']);
                                        ?>
                                    </span> 
                                </td>
                                <td>
                                    <span class="form-control viewemployee" id="inputem3" style="text-align: center; font-weight: bolder">
                                        <?php
                                        echo CalculateWorkedTime($attendance['ot_duration']);
                                        ?>
                                    </span> 
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <div class="form-group"> 
                    <div class="col-lg-12">
                        <label> 
                            Comment (Overtime Reason)<span class="mandatory">*</span>
                        </label>
                        <textarea class="form-control" id="comments" rows="3" name="comments" placeholder="Overtime Reason" ></textarea><!--required-->
                    </div>
                </div>
                <div class="form-group"> 
                    <div class="col-lg-2">
                        <?php if ($employee['ReportingTo'] == '' || $employee['ReportingTo'] == NULL) { ?>
                            <input type="button" name="approve_ot_request" id="approve_ot_request" class="btn btn-primary btn-lg btn-block" value="Send <?php echo $type; ?> Approval Request" style="font:menu; font-size:14px" onClick="validateForm()" disabled />
                            <p style="color:red"> Your Reporting To has not been assigned. Please contact your supervisor</p>
                        <?php } else {
                            ?>
                            <input type="button" name="approve_ot_request" id="approve_ot_request" class="btn btn-primary btn-lg btn-block" value="Send <?php echo $type; ?> Approval Request" style="font:menu; font-size:14px" onClick="validateForm()" />

                        <?php }
                        ?>

                        <img id="loader"  style="margin-left:470px; display:none;"  src="/les/assets/images/ajax-loader.gif">
                        <iframe width="200" height="25" frameborder="0" src="/les/assets/images/ajax-loader.gif" scrolling="no" id="a_loader" style="display:none;"></iframe>
                    </div>
                </div>

            </form>
            <!-- PERSONAL INFORMATION FORM END -->
        </div>
        <!-- CONTENT END --> 

        <script>
            function validateForm() {
                //var pathname = window.opener.location;
                var comments = $("#comments").val();

                $('#comments').css('border-color', 'green');
                var error = 0;

                if (comments == '') {
                    $('#comments').css('border-color', 'red');
                    error = 1;
                }

                if (error == 0) {
                    $('#approve_ot_request').hide();
                    $('#a_loader').show();

                    var url = window.opener.location.href;
                    console.log(url);
                    $.post("", {ID: $("#ID").val(), EmployeeID: $("#EmployeeID").val(), RestrictionType: $("#RestrictionType").val(), RequestedTo: $("#RequestedTo").val(), comments: $("#comments").val()}, function (data) {
                        console.log(url.indexOf("attendance"));
                        if (url.indexOf("attendance") != '-1') {
                            window.opener.document.getElementById("bttnatt").click();
                        }

                        //console.log('attendance popup');
                        window.close();
                    });

                }
            }
        </script>

        <?php $this->load->view('templates/footer'); ?>