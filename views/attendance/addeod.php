<!doctype html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <title>::.. Attendance Modification ADMIN ..::</title>
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
                /*echo '<pre>'; print_r($attendance_details); echo '</pre>'; */
            }
            ?>
            <?php ?>
            <h3 style="font-size: 23px; color: #2fa4e7;"><span>Add EOD <?php echo 'of ' . $attendance_details['FirstName'] . ' ' . $attendance_details['LastName']; ?></span></h3>
            <p style="font-weight: bold; font-size: 14px;line-height: 21px;float: left;"><?php echo 'Date: ' . $attendance_details['Date'] . ' (' . date('l', strtotime($attendance_details['Date'])) . ')'; ?></p>
            <div style="clear: both;"></div>
            <?php if ($WorkingDay == '0') { ?>
                <p style="font-weight: bold; font-size: 14px;line-height: 21px;float: left;">Shift: </p>
                <p style="color:red; font-weight: bold; font-size: 14px;float: left;margin-left: 5px;"> OFF DAY</p><div style="clear: both;"></div>
            <?php } ?>

            <?php if ($AID == '0') { ?>
                <p style="font-weight: bold; font-size: 14px;line-height: 21px;float: left;">Attendance: </p>
                <p style="color:red; font-weight: bold; font-size: 14px;float: left;margin-left: 5px;"> ABSENT</p><div style="clear: both;"></div>
            <?php } ?>
            <p style="font-weight: bold; font-size: 12px; color: #2fa4e7; line-height: 21px;float: left;"><?php echo 'Shift : ' . $attendance_details['Name']; ?></p>
            <div style="clear: both;"></div>
            <form class="form-horizontal" role="form" action="" method="post" enctype="multipart/form-data" id="modifyattendancepopup"> 
                <div class="table-responsive">
                    <table class="table table-striped table-bordered" width="50%" cellspacing="0">       
                        <colgroup>
                            <col class="col-xs-3"> 
                            <col class="col-xs-3">
                        </colgroup>
                        <thead>
                            <tr class="trformat">
                                <th class="thformat" >Time In</th>
                                <th class="thformat">Time Out</th>         
                            </tr>
                        </thead>
                        <tbody>
                            <tr id="">
                        <input type="hidden" id="AID" name="AID" value="<?php echo $AID; ?>" />     
                        <input type="hidden" id="CompanyID" name="CompanyID" value="<?php echo $attendance_details['CompanyID']; ?>" />     
                        <input type="hidden" id="DepartmentID" name="DepartmentID" value="<?php echo $attendance_details['DepartmentID']; ?>" />     
                        <td>
                            <span class="form-control viewemployee" id="inputem3" style="text-align: center;">
                                <?php
                                echo $attendance_details['InTime'];
                                ?>
                            </span>
                        </td> 
                        <td>
                            <span class="form-control viewemployee" id="inputem3" style="text-align: center;">
                                <?php
                                echo $attendance_details['OutTime'];
                                ?>
                            </span>
                        </td>
                        </tr>
                        </tbody>
                    </table>
                </div>
                <?php
                //if ($this->session->userdata('id') == '189') {
                if ($attendance_details['CompanyID'] == '3' || $attendance_details['DepartmentID'] == '1') {
                    ?>
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered" width="50%" cellspacing="0">       
                            <colgroup>
                                <col class="col-xs-3"> 
                                <col class="col-xs-3">
                            </colgroup>
                            <thead>
                                <tr class="trformat">
                                    <th class="thformat" >Total Chats</th>
                                    <th class="thformat">Total Billable Chats</th>         
                                </tr>
                            </thead>
                            <tbody>
                                <tr id="">     
                                    <td>
                                        <input type="number" name="TotalChats" class="form-control" id="totalchat"  placeholder="Total Chats" >
                                    </td> 
                                    <td>
                                        <input type="number" name="TotalBillableChats" class="form-control" id="totalbilablechat"  placeholder="Total Billable Chats" >
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <?php
                }
                //}
                ?>    

                <div class="form-group"> 
                    <div class="col-lg-6">
                        <label> 
                            EOD<span class="mandatory">*</span>
                        </label>
                        <textarea class="form-control" id="eod" rows="3" name="eod" ></textarea><!--required-->
                    </div>
                </div>
                <input type="button" id="get_eod" name="get_eod" class="btn btn-primary btn-lg btn-block" value="Save EOD" style="font:menu; font-size:14px" onClick="validateForm()"></input>
                <iframe width="200" height="25" frameborder="0" src="/les/assets/images/ajax-loader.gif" scrolling="no" id="a_loader" style="display:none;"></iframe>
            </form>
            <!-- PERSONAL INFORMATION FORM END -->
        </div>
        <!-- CONTENT END --> 
        <script>
            /*$(document).ready(function() {
             $("input[name='get_eod']").on('click', function(e) {
             e.preventDefault();
             });
             });*/
            function validateForm() {
                var pathname = window.opener.location;
                var totalchat = $("#totalchat").val();
                var totalbilablechat = $("#totalbilablechat").val();
                var eod = $("#eod").val();

                $('#totalchat').css('border-color', 'green');
                $('#totalbilablechat').css('border-color', 'green');
                $('#eod').css('border-color', 'green');

                var error = 0;

                if (totalchat == '') {
                    $('#totalchat').css('border-color', 'red');
                    error = 1;
                }
                if (totalbilablechat == '') {
                    $('#totalbilablechat').css('border-color', 'red');
                    error = 1;
                }
                if (eod == '') {
                    $('#eod').css('border-color', 'red');
                    error = 1;
                }
                /*console.log("timein:"+$("#timein").val()+", timeout:"+$("#timeout").val()+", eod:"+$("#eod").val());
                 console.log("226 error:"+error);
                 return false;*/
                if (error == 0) {
                    $('#get_eod').hide();
                    $('#a_loader').show();

                    var url = window.opener.location.href;
                    if (url.indexOf("shiftreports") == '-1') {
                        window.opener.document.getElementById("bttnatt").click();
                    }
                    $.post("", {AID: $("#AID").val(), TotalChats: $("#totalchat").val(), TotalBillableChats: $("#totalbilablechat").val(), CompanyID:$("#CompanyID").val(), DepartmentID: $("#DepartmentID").val(), eod: $("#eod").val()}, function (data) {
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