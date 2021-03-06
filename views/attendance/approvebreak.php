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
            <?php /* echo '<pre>'; print_r($employee); echo '</pre>'; */ ?>
            <h3 style="font-size: 23px; color: #2fa4e7;">
                <span>Break Approval <?php
                    echo 'of ' . $employee['FirstName'] . ' ' . $employee['LastName'];
                    if ($employee['PseudoFirstName']) {
                        echo ' (' . $employee['PseudoFirstName'] . ' ' . $employee['PseudoLastName'] . ')';
                    }
                    ?></span>
            </h3>
            <p style="font-weight: bold; font-size: 12px;line-height: 21px;float: left;"><?php echo 'Break Date: ' . $Day . ', ' . $Date; ?></p>
            <div style="clear: both;"></div>
            <?php /* ?><p style="font-weight: bold; font-size: 12px; color: #2fa4e7; line-height: 21px;float: left;"><?php echo 'Shift : '. $employeeshift['Name'];?></p><?php */ ?>
            <div style="clear: both;"></div>
            <form class="form-horizontal" role="form" action="" method="post" enctype="multipart/form-data" id="approvebreakform"> 
                <div class="table-responsive">

                    <table class="table table-striped table-bordered" width="50%" cellspacing="0">       
                        <colgroup>
                            <col class="col-xs-2">
                            <col class="col-xs-2"> 
                            <col class="col-xs-2">
                            <col class="col-xs-6">
                        </colgroup>
                        <thead>
                            <tr class="trformat">
                                <th class="thformat" >Break Type</th>
                                <th class="thformat" >Break Start</th>
                                <th class="thformat">Break End</th>    
                                <th class="thformat">Break Comment</th>   
                            </tr>
                        </thead>
                        <tbody>
                            <tr id=""><?php /* echo '<pre>'; print_r($break_request); echo '</pre>'; */ ?>
                        <input type="hidden" id="ID" name="ID" value="<?php echo $break_request[0]['ID']; ?>" />
                        <input type="hidden" id="BLogID" name="BLogID" value="<?php echo $BLogID; ?>" />
                        <input type="hidden" id="EmployeeID" name="EmployeeID" value="<?php echo $employee['ID']; ?>" />
                        <input type="hidden" id="AttendenceID" name="AttendenceID" value="<?php echo $AttendenceID; ?>" /> 
                        <input type="hidden" id="ShiftID" name="ShiftID" value="<?php echo $employeeshift['ShiftID']; ?>" />
                        <input type="hidden" id="AID" name="AID" value="<?php echo $AID; ?>" />
                        <input type="hidden" id="Day" name="Day" value="<?php echo $Day; ?>" /> 
                        <input type="hidden" id="Date" name="Date" value="<?php echo $Date; ?>" /> 

                        <input type="hidden" id="breaktype" name="breaktype" value="<?php echo $break_request[0]['BreakType']; ?>" /> 
                        <input type="hidden" id="timein" name="timein" value="<?php echo $break_request[0]['BreakStart']; ?>" /> 
                        <input type="hidden" id="timeout" name="timeout" value="<?php echo $break_request[0]['BreakEnd']; ?>" /> 
                        <td>
                            <span class="form-control viewemployee" id="inputem3">
                                <?php
                                echo $break_request[0]['BreakType'];
                                ?>
                            </span>
                        </td>       
                        <td>
                            <?php
                            if ($break_request) {
                                $breaktimein = $break_request[0]['BreakStart'];
                                $breaktimeinvalue = $break_request[0]['BreakStart'];
                            } else {
                                $breaktimein = 'Select Break Start';
                                $breaktimeinvalue = '';
                            }
                            ?>
                            <span class="form-control viewemployee" id="inputem3">
                                <?php
                                if (isset($ID)) {
                                    echo $breaktimein;
                                }
                                ?>
                            </span>
                        </td> 
                        <td>
                            <?php
                            if ($break_request) {
                                $breaktimeout = $break_request[0]['BreakEnd'];
                                $breaktimeoutvalue = $break_request[0]['BreakEnd'];
                            } else {
                                $breaktimeout = 'Select Break End';
                                $breaktimeoutvalue = '';
                            }
                            ?>
                            <span class="form-control viewemployee" id="inputem3">
                                <?php
                                if (isset($ID)) {
                                    echo $breaktimeout;
                                }
                                ?>
                            </span> 
                        </td>
                        <?php
                        if ($break_request[0]['CheckedBy']['FirstName'] && $break_request[0]['Comments'] != '') {
                            $commented_by = ' (<strong>By: </strong>' . $break_request[0]['CheckedBy']['FirstName'] . ' ' . $break_request[0]['CheckedBy']['LastName'] . ')';
                        } else if ($break_request[0]['DeletedBy']['FirstName'] && $break_request[0]['Comments'] != '') {
                            $commented_by = ' (<strong>Deleted By: </strong>' . $break_request[0]['DeletedBy']['FirstName'] . ' ' . $break_request[0]['DeletedBy']['LastName'] . ')';
                        } else {
                            if ($break_request[0]['Comments'] != '') {
                                $commented_by = ' <br><br>Regards,<br>' . $break_request[0]['AddedBy']['FirstName'] . ' ' . $break_request[0]['AddedBy']['LastName'] . '';
                            }
                        }
                        ?>

                        <td><?php
                            if ($break_request[0]['RequestedComments'] != '') {
                                echo $break_request[0]['RequestedComments'] . $commented_by;
                            }
                            ?></td>
                        </tr>
                        </tbody>
                    </table>
                </div>
                <div class="form-group"> 
                    <div class="col-lg-6">
                        <label> 
                            Approval Comments <span class="mandatory">*</span>
                        </label>
                        <textarea class="form-control" id="comments" rows="3" name="comments" >Acknowledged and approved.</textarea><!--required-->
                    </div>
                </div>
                <input type="button" name="approve_break" id="approve_break" class="btn btn-primary btn-lg btn-block" value="Approve Break" style="font:menu; font-size:14px" onClick="validateForm()" />
                <img id="loader"  style="margin-left:470px; display:none;"  src="/les/assets/images/ajax-loader.gif">
                <iframe width="200" height="25" frameborder="0" src="/les/assets/images/ajax-loader.gif" scrolling="no" id="a_loader" style="display:none;"></iframe>
            </form>
            <!-- PERSONAL INFORMATION FORM END -->
        </div>
        <!-- CONTENT END --> 

        <script>
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
                    $('#approve_break').hide();
                    $('#a_loader').show();

                    var url = window.opener.location.href;
                    console.log(url);
                    console.log(url.indexOf("pendingbreaks"));
                    $.post("", {timein: $("#timein").val(), timeout: $("#timeout").val(), EmployeeID: $("#EmployeeID").val(), ID: $("#ID").val(), ShiftID: $("#ShiftID").val(), Day: $("#Day").val(), BreakDate: $("#Date").val(), comments: $("#comments").val(), BLogID: $("#BLogID").val(), breaktype: $("#breaktype").val(), AttendenceID: $("#AttendenceID").val()}, function (data) {
                        if (url.indexOf("shiftreports") == '-1' && url.indexOf("breaksreport") == '-1' && url.indexOf("breakapprovalrequests") == '-1' && url.indexOf("pendingbreaks") == '-1') {
                            window.opener.document.getElementById("bttnatt").click();
                        }
                        window.close();
                        if (url.indexOf("shiftreports") != '-1' && url.indexOf("breaksreport") == '-1' && url.indexOf("breakapprovalrequests") == '-1') {
                            window.opener.location.reload();
                        }
                        if (url.indexOf("breaksreport") != '-1') {
                            window.opener.document.getElementById("bttn_breaks").click();
                        }
                        if (url.indexOf("pendingbreaks") != '-1') {
                            window.opener.document.getElementById("bttn_approval_requests").click();
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