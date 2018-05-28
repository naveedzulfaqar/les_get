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
            <h3 style="font-size: 23px; color: #2fa4e7;"><span>Delete Break <?php echo 'of ' . $employee['FirstName'] . ' ' . $employee['LastName']; ?></span></h3>
            <h3 style="font-size: 16px; color: #2fa4e7;"><span>Break Log Date <?php echo $breaklog[0]['Date'].' ('.$Day.')'; ?></span></h3>

            <form class="form-horizontal" role="form" action="" method="post" enctype="multipart/form-data" id="deletebreakform"> 
                <div class="table-responsive top">
                    <table class="table table-striped table-bordered" width="100%" cellspacing="0">
                        <colgroup>
                            <!--<col class="col-xs-1">
                            <col class="col-xs-1">-->
                            <col class="col-xs-2">
                            <col class="col-xs-2">
                            <col class="col-xs-2">
                            <col class="col-xs-2">
                            <col class="col-xs-2">
                        </colgroup>
                        <thead>
                            <tr>
                                <!--<th>Date</th>
                                <th>Day</th>-->
                                <th><?php echo $employee['FirstName']; ?> <?php echo $employee['LastName']; ?>'s Timing</th>
                                <th>Break Type </th>
                                <th>Marked By</th>
                                <th>Comments By: <br><?php echo $breaklog[0]['AddedBy']['FirstName'].' '.$breaklog[0]['AddedBy']['LastName']; ?></th>
                                <!--<th>Approved By </th>-->
                                <th>Modified Date</th>
                            </tr>
                        </thead>
                        <tbody>
                        <input type="hidden" id="ID" name="ID" value="<?php echo $ID; ?>" />
                        <input type="hidden" id="EmployeeID" name="EmployeeID" value="<?php echo $employee['ID']; ?>" />
                        <input type="hidden" id="AttendenceID" name="AttendenceID" value="<?php echo $AttendenceID; ?>" />
                        <?php
                        $a = 1;
                        $breaklogID = 0;
                        /* echo '<pre>'; print_r($breaklog); echo '</pre>'; */
                        foreach ($breaklog as $key => $blog) {
                            ?>
                            <tr id="listItem_<?php echo $key; ?>">
                                <?php /*?><td><?php echo $blog['Date']; ?></td>
                                <td><?php echo $Day; ?></td><?php */?>
                                <td>
                                    <strong>Time In:</strong> <?php echo $blog['BreakStart']; ?> <br/>
                                    <strong>Time Out:</strong> <?php echo $blog['BreakEnd']; ?> <br/><br/>
                                    <strong>Total Time:</strong> <?php echo CalculateWorkedTime($blog['WorkedHours']); ?><!--round($blog['WorkedHours'], 1) . ' hours'-->
                                </td>
                                <td><?php echo $blog['BreakType']; ?></td>
                                <td><?php echo $blog['AddedBy']['FirstName'].' '.$blog['AddedBy']['LastName']; ?></td>
                                <td><?php echo $blog['Comments']; ?></td>
                                <?php /* ?><td><?php echo $blog['CheckedBy']['FirstName'] . ' ' . $blog['CheckedBy']['LastName']; ?></td><?php */ ?>
                                <td><?php echo $blog['ModifiedDate']; ?></td>
                            </tr> 
                            <?php
                            $a++;
                        }
                        ?>
                        </tbody>
                    </table>
                </div>
                <div class="form-group"> 
                    <div class="col-lg-6">
                        <label> 
                            Deleted Comment<span class="mandatory">*</span>
                        </label>
                        <textarea class="form-control" id="comments" rows="3" name="comments" required ></textarea><!--required-->
                    </div>
                </div>
                <input type="submit" name="delete_break" id="delete_break" class="btn btn-primary btn-lg btn-block" value="Delete Break" style="font:menu; font-size:14px" ></input>
                <img id="loader"  style="margin-left:470px; display:none;"  src="/les/assets/images/ajax-loader.gif">
                <iframe width="200" height="25" frameborder="0" src="/les/assets/images/ajax-loader.gif" scrolling="no" id="a_loader" style="display:none;"></iframe>
            </form>
            <!-- PERSONAL INFORMATION FORM END -->
        </div>
        <!-- CONTECT END --> 

        <script>
            window.onunload = refreshParent;
            function refreshParent() {
                window.opener.document.getElementById("bttnatt").click();
            }

            $("input[name='delete_break']").on('click', function(e) {
				e.preventDefault();
				var comments = $("#comments").val(); 
				 $('#comments').css('border-color', 'green');
				var error = 0;
				if (comments == '') {
				 $('#comments').css('border-color', 'red');
				 error = 1;
				 }
                if (error == 0) {
					$('#delete_break').hide();
					$('#a_loader').show();
	
					var url = window.opener.location.href;
					if (url.indexOf("shiftreports") == '-1') {
						window.opener.document.getElementById("bttnatt").click();
					}
					$.post("", {EmployeeID: $("#EmployeeID").val(), ID: $("#ID").val(), comments: $("#comments").val(), AttendenceID: $("#AttendenceID").val()}, function(data) {
	
						//console.log('delete_break popup');
						window.close();
					});
				}

            });
        </script>
        <?php $this->load->view('templates/shiftreports_footer'); ?>   
        <?php $this->load->view('templates/footer'); ?>