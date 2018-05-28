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
            <?php if($this->session->userdata('id') == '189'){
					/*echo '<pre>'; print_r($break); echo '</pre>';*/
				}   ?>
            <h3 style="font-size: 23px; color: #2fa4e7;">
            <?php if($break['FirstName']){
					$Name = $break['FirstName'].' '.$break['LastName'];
				}
				if($break['PseudoFirstName']){
					$Name .= ' ('.$break['PseudoFirstName'].' '.$break['PseudoLastName'].')';
				}?>
            	<span>Break Approval request <?php echo 'of ' . $Name; ?></span>
            </h3>
            <p style="font-weight: bold; font-size: 12px;line-height: 21px;float: left;"><?php echo 'Break Date: '; 
			
			if($break['BreakSwap']['ApprovedBy']){
				echo date('l', strtotime($break['BreakSwap']['SwapFrom'])) . ', ' . $break['BreakSwap']['SwapFrom'];
			}else{
				echo date('l', strtotime($break['Date'])) . ', ' . $break['Date'];
			}
			
			 ?></p>
            <div style="clear: both;"></div>
            <form class="form-horizontal" role="form" action="" method="post" enctype="multipart/form-data" id="breakapprovalrequestform">                
                <div class="table-responsive">

                    <table class="table table-striped table-bordered" width="50%" cellspacing="0">       
                        <colgroup>
                            <col class="col-xs-2">
                            <col class="col-xs-2"> 
                            <col class="col-xs-2">
                            <col class="col-xs-2"> 
                            <col class="col-xs-4">
                        </colgroup>
                        <thead>
                            <tr class="trformat">
                                <th class="thformat" >Break Start</th>
                                <th class="thformat" >Break End</th>
                                <th class="thformat" >Break Type</th>
                                <th class="thformat">Break Duration</th>    
                                <th class="thformat">Break Comment</th>   
                            </tr>
                        </thead>
                        <tbody>
                            <tr id=""><?php /* echo '<pre>'; print_r($breaklog); echo '</pre>'; */ ?>
                            <input type="hidden" id="BreakID" name="BreakID" value="<?php echo $break['ID']; ?>" />
                            <input type="hidden" id="RequestedDate" name="RequestedDate" value="<?php echo date('Y-m-d'); ?>" />
                            <input type="hidden" id="DepartmentID" name="DepartmentID" value="<?php echo $break['DepartmentID']; ?>" />
                            <input type="hidden" id="EmployeeID" name="EmployeeID" value="<?php echo $break['EmployeeID']; ?>" />
                            <input type="hidden" id="ReportingTo" name="ReportingTo" value="<?php echo $break['ReportingTo']; ?>" />
                            <input type="hidden" id="BreakDate" name="BreakDate" value="<?php echo $break['Date']; ?>" />
                            <input type="hidden" id="BreakDay" name="BreakDay" value="<?php echo date('l', strtotime($break['Date'])); ?>" />
                            <input type="hidden" id="BreakStart" name="BreakStart" value="<?php echo $break['BreakStart']; ?>" />
                            <input type="hidden" id="BreakEnd" name="BreakEnd" value="<?php echo $break['BreakEnd']; ?>" />
                            <input type="hidden" id="BreakType" name="BreakType" value="<?php echo $break['BreakType']; ?>" />
                            <input type="hidden" id="BreakDuration" name="BreakDuration" value="<?php echo CalculateWorkedTime($break['BreakDuration']); ?>" /> 
                                   
                            <td>
                            	<span class="form-control viewemployee" id="inputem3">
                                	<?php echo $break['BreakStart']; ?>
                                </span>
                            </td> 
                            <td>
                            	<span class="form-control viewemployee" id="inputem3">
                                	<?php echo $break['BreakEnd']; ?>
                                </span>
                            </td>
                            <td>
                            	<span class="form-control viewemployee" id="inputem3">
                                	<?php echo $break['BreakType']; ?>
                                </span>
                            </td>
                            <td>
                            	<span class="form-control viewemployee" id="inputem3">
                                	<?php echo CalculateWorkedTime($break['BreakDuration']); ?>
                                </span>
                            </td>
                            <?php
                            if ($break['CheckedBy']['FirstName'] && $break['Comments'] != '') {
                                $commented_by = ' (<strong>By: </strong>' . $break['CheckedBy']['FirstName'] . ' ' . $break['CheckedBy']['LastName'] . ')';
                            } else if ($break['DeletedBy']['FirstName'] && $break['Comments'] != '') {
                                $commented_by = ' (<strong>Deleted By: </strong>' . $break['DeletedBy']['FirstName'] . ' ' . $break['DeletedBy']['LastName'] . ')';
                            } else {
                                if ($break['Comments'] != '') {
                                    $commented_by = ' <br><br>Regards,<br>' . $break['AddedBy']['FirstName'] . ' ' . $break['AddedBy']['LastName'] . '';
                                }
                            }
                            ?>
    
                            <td><?php
                                if ($break['Comments'] != '') {
                                    echo $break['Comments'] ;/*. $commented_by*/
                                }
                                ?></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                
                <div class="form-group"> 
                    <div class="col-lg-6">
                        <label> 
                            Request Comment<span class="mandatory">*</span>
                        </label>
                        <textarea class="form-control" id="requestcomment" rows="5" name="requestcomment" ></textarea><!--required-->
                    </div>
                </div>
                <?php 
						if($break['ReportingTo'] == '' || $break['ReportingTo'] == NULL){ ?>
                        	<input type="button" name="break_approval_request" id="break_approval_request" class="btn btn-primary btn-lg btn-block" value="Send Request" style="font:menu; font-size:14px" onClick="validateForm()" disabled />
                            <p style="color:red"> Your Reporting To has not been assigned. Please contact your supervisor</p>
                  <?php }else{ ?>
                        	<input type="button" name="break_approval_request" id="break_approval_request" class="btn btn-primary btn-lg btn-block" value="Send Request" style="font:menu; font-size:14px" onClick="validateForm()" />
                 <?php  } ?>
                <img id="loader"  style="margin-left:470px; display:none;"  src="/les/assets/images/ajax-loader.gif">
                <iframe width="200" height="25" frameborder="0" src="/les/assets/images/ajax-loader.gif" scrolling="no" id="a_loader" style="display:none;"></iframe>
            </form>
            <!-- PERSONAL INFORMATION FORM END -->
        </div>
        <!-- CONTECT END --> 

<script>
	function validateForm() {
		var pathname = window.opener.location;
		var requestcomment = $("#requestcomment").val();
		
		$('#requestcomment').css('border-color', 'green');
		var error = 0;
		if (requestcomment == '') {
			$('#requestcomment').css('border-color', 'red');
			error = 1;
		}

		if (error == 0) {
			$('#break_approval_request').hide();
			$('#a_loader').show();

			var url = window.opener.location.href;
			$.post("", {BreakID: $("#BreakID").val(), RequestedComments: $("#requestcomment").val(), RequestedDate: $("#RequestedDate").val(), DepartmentID: $("#DepartmentID").val(), EmployeeID: $("#EmployeeID").val(), ReportingTo: $("#ReportingTo").val(), BreakDate: $("#BreakDate").val(), BreakDay: $("#BreakDay").val(), BreakStart: $("#BreakStart").val(), BreakEnd: $("#BreakEnd").val(), BreakType: $("#BreakType").val(), BreakDuration: $("#BreakDuration").val()}, function(data) {
				window.close();
				//window.opener.location.reload();
				window.opener.document.getElementById("bttnatt").click();
			});
		}
	}
</script>

<?php $this->load->view('templates/shiftreports_footer'); ?>  
<?php $this->load->view('templates/footer'); ?>