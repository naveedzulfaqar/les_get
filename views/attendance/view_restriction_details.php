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
                /* echo '<pre>'; print_r($employeeshift); echo '</pre>'; */
            }
            if ($RestrictionType == 'sr') {
                $type = 'Shift Overtime';
            } else if ($RestrictionType == 'ot') {
                $type = 'Overtime';
            }
            ?>
            <h3 style="font-size: 23px; color: #2fa4e7;">
                <span>View <?php echo $type; ?> Details of <?php
                    echo $employee['FirstName'] . ' ' . $employee['LastName'];
                    if ($employee['PseudoFirstName']) {
                        echo ' (' . $employee['PseudoFirstName'] . ' ' . $employee['PseudoLastName'] . ')';
                    }
                    ?></span>
            </h3>
            <p style="font-weight: bold; font-size: 12px; color: #2fa4e7; line-height: 21px;float: left;">Shift : <a href="<?php echo site_url('shifts/view/' . $employeeshift['ShiftID']); ?>" target="_blank"><?php echo $employeeshift['Name']; ?></a></p>
            <div style="clear: both;"></div>
            <p style="font-weight: bold; font-size: 12px;line-height: 21px;float: left;"><?php echo 'Date: ' . $attendance['Date'] . ' (' . $employeeshift['DayName'] . ')'; ?></p>
            <div style="clear: both;"></div>
            <?php /* echo'<pre>'; print_r($employee); echo '</pre>'; */ ?>
            <form class="form-horizontal" role="form" action="" method="post" enctype="multipart/form-data" id="approveotform"> 
                <div class="table-responsive">
                    <input type="hidden" id="EmployeeID" name="EmployeeID" value="<?php echo $attendance['EmployeeID']; ?>" />
                    <input type="hidden" id="RestrictionType" name="RestrictionType" value="<?php echo $RestrictionType; ?>" />
                    <input type="hidden" id="ID" name="ID" value="<?php echo $attendance['ID']; ?>" />
                    <input type="hidden" id="ottype" name="ottype" value="<?php echo $ottype; ?>" />
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

                    <?php
                    //if ($this->session->userdata('id') == '189') {
                    //echo '<pre>';  print_r($restriction_request); echo '</pre>';
                    if ($restriction_request['ApprovalStatus'] == 'y') {
                        $ot_type = 'Approved';
                    } else if ($restriction_request['ApprovalStatus'] == 'n') {
                        $ot_type = 'Rejected';
                    } else {
                        $ot_type = 'Unapproved';
                    }
                    //}
                    ?>

                    <table class="table table-striped table-bordered" width="50%" cellspacing="0">       
                        <colgroup>
                            <col class="col-xs-3">
                            <col class="col-xs-3">
                            <col class="col-xs-3" <?php echo $style; ?>>
                            <col class="col-xs-3" <?php echo $style; ?>>
                        </colgroup>
                        <thead>
                            <tr class="trformat">
                                <th class="thformat" >Actual Time In</th>
                                <th class="thformat" >Actual Time Out</th>
                                <th class="thformat" <?php echo $style; ?>>Calculated Worked Duration</th>
                                <th class="thformat" <?php echo $style; ?>><?php echo $ot_type . ' ' . $type; ?></th>
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
                                <td <?php echo $style; ?>>
                                    <span class="form-control viewemployee" id="inputem3" style="text-align: center;">
                                        <?php
                                        echo CalculateWorkedTime($attendance['total_time']);
                                        ?>
                                    </span> 
                                </td>
                                <td <?php echo $style; ?>>
                                    <span class="form-control viewemployee" id="inputem3" style="text-align: center; font-weight: bolder">
                                        <?php
                                        echo CalculateWorkedTime($attendance['ot_duration']);
                                        ?>
                                    </span>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                    <table class="table table-striped table-bordered" width="50%" cellspacing="0">       
                        <colgroup>
                            <col class="col-xs-6">
                            <col class="col-xs-6">
                        </colgroup>
                        <thead>
                            <tr class="trformat">
                                <th class="thformat" >Requested Info</th>
                                <th class="thformat" >Updated Info</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr id="">
                                <td>
                                    <?php
                                    if ($restriction_request['RequestedTo']['ID']) {
                                        $requested_to_name = $restriction_request['RequestedTo']['FirstName'] . ' ' . $restriction_request['RequestedTo']['LastName'];
                                        if ($restriction_request['RequestedTo']['PseudoFirstName']) {
                                            $requested_to_name .= ' (' . $restriction_request['RequestedTo']['PseudoFirstName'] . ' ' . $restriction_request['RequestedTo']['PseudoLastName'] . ')';
                                        }
                                        echo '<strong>Requested Date: </strong>' . $restriction_request['RequestedDate'];
                                        echo '<br><strong>Requested Comment: </strong>' . $restriction_request['OTComments'];
                                        echo '<br><strong>Requested To: </strong>' . $requested_to_name;
                                    } else {
                                        echo '<span style="text-align: center;"> - </span>';
                                    }
                                    ?>
                                </td> 
                                <td>
                                    <?php
                                    if ($restriction_request['UpdatedBy']['ID']) {
                                        $updated_by_name = $restriction_request['UpdatedBy']['FirstName'] . ' ' . $restriction_request['UpdatedBy']['LastName'];
                                        if ($restriction_request['UpdatedBy']['PseudoFirstName']) {
                                            $updated_by_name .= ' (' . $restriction_request['UpdatedBy']['PseudoFirstName'] . ' ' . $restriction_request['UpdatedBy']['PseudoLastName'] . ')';
                                        }
                                        echo '<strong>Updated Date: </strong>' . $restriction_request['UpdatedDate'];
                                        echo '<br><strong>Updated Comment: </strong>' . $restriction_request['UpdatedComments'];
                                        echo '<br><strong>Updated By: </strong>' . $updated_by_name;
                                    } else {
                                        echo '<span style="text-align: center;"> - </span>';
                                    }
                                    ?>
                                </td>
                            </tr>
                        </tbody>
                    </table>
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
                    $('#approve_ot').hide();
                    $('#a_loader').show();

                    var url = window.opener.location.href;
                    console.log(url);
                    /*console.log(url.indexOf("breakapprovalrequests"));
                     if (url.indexOf("shiftreports") == '-1' && url.indexOf("breaksreport") == '-1' && url.indexOf("breakapprovalrequests") == '-1') {
                     window.opener.document.getElementById("bttnatt").click();
                     }*/
                    $.post("", {ID: $("#ID").val(), EmployeeID: $("#EmployeeID").val(), RestrictionType: $("#RestrictionType").val(), ottype: $("#ottype").val(), comments: $("#comments").val()}, function (data) {
                        console.log(url.indexOf("attendance"));
                        if (url.indexOf("attendance") != '-1') {
                            window.opener.document.getElementById("bttnatt").click();
                        }
                        if (url.indexOf("approveot") != '-1') {
                            window.opener.document.getElementById("bttn_approve_ot").click();
                        }

                        //console.log('attendance popup');
                        window.close();
                    });

                }
            }
        </script>

        <?php $this->load->view('templates/footer'); ?>