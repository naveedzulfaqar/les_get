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
            <?php /* echo '<pre>'; print_r($employee); echo '</pre>'; */ 
			if($usage_history[0]['FirstName']){
				$Name = $usage_history[0]['FirstName'] . ' ' . $usage_history[0]['LastName'];
			}
			if($usage_history[0]['PseudoFirstName']){
				$Name = $usage_history[0]['PseudoFirstName'] . ' ' . $usage_history[0]['PseudoLastName'];
			}
			?>
            
            <h3 style="font-size: 23px; color: #2fa4e7;"><span>Usage History <?php echo 'of ' . $Name; ?></span></h3>
            <h3 style="font-size: 16px; color: #2fa4e7;"><span>Date <?php echo $usage_history[0]['Date'] . ' (' . date("l", strtotime($usage_history[0]['Date'])) . ')'; ?><br>
                    
                </span></h3>

            <div class="table-responsive top">
                <table class="table table-striped table-bordered" width="100%" cellspacing="0">
                    <colgroup>
                        <col class="col-xs-1">
                        <col class="col-xs-2">
                        <col class="col-xs-2">
                        <col class="col-xs-2">
                    </colgroup>
                    <thead>
                        <tr>
                        	<th>ID</th>
                            <th>Data Info</th>
                            <th>Data Type </th>
                            <th>Duration</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $a = 1;
                        $breaklogID = 0;
						$commented_by = '';
						if($this->session->userdata('id') == '189'){
							/* echo '<pre>'; print_r($breaklog); echo '</pre>'; */
						}
                        
                        foreach ($usage_history as $key => $history) {
                            ?>
                            <tr id="listItem_<?php echo $key; ?>">
                            	<td><?php echo $history['ID']; ?></td>
                                <td>
                                    <?php echo $history['DataInfo']; ?>
                                </td>
                                <td><?php echo $history['DataType']; ?></td>
                                <td><?php echo CalculateWorkedTime($history['Duration']); ?></td>
                            </tr> 
                            <?php
                            //$a++;
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