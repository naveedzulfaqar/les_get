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
            <h3 style="font-size: 23px; color: #2fa4e7;"><span>Break <?php echo 'of ' . $employee['FirstName'] . ' ' . $employee['LastName']; ?></span></h3>
            <h3 style="font-size: 16px; color: #2fa4e7;"><span>Break Date <?php echo $breaklog[0]['Date'] . ' (' . $Day . ')'; ?><br>
                    <?php
                    if ($breaklog[0]['IsActive'] == '0') {
                        echo '<h3 style="font-size: 16px; color:red;">Deleted By ' . $breaklog[0]['DeletedBy']['FirstName'] . ' ' . $breaklog[0]['DeletedBy']['LastName'] . ' at Date ' . $breaklog[0]['ModifiedDate'] . '</h3>';
                    }
                    ?>
                </span></h3>

            <div class="table-responsive top">
                <table class="table table-striped table-bordered" width="100%" cellspacing="0">
                    <colgroup>
                        <!--<col class="col-xs-1">-->
                        <col class="col-xs-2">
                        <col class="col-xs-1">
                        <col class="col-xs-2">
                        <col class="col-xs-2">
                        <col class="col-xs-3">
                        <col class="col-xs-3">
                        <?php if ($breaklog[0]['DeletedComments'] != '') { ?>
                            <col class="col-xs-3">
                        <?php } ?>
                        <col class="col-xs-2">
                        <?php if ($this->session->userdata('id') == '189') { ?>
                            <col class="col-xs-2">
                        <?php } ?>
                    </colgroup>
                    <thead>
                        <tr>
                            <!--<th>Date</th>-->
                            <th><?php echo $employee['FirstName']; ?> <?php echo $employee['LastName']; ?>'s Timing</th>
                            <th>Break Type </th>
                            <th>Marked By</th>
                            <th>Approved By</th>
                            <th>Comments </th>
                            <th>Updated Comments </th>
                            <th>Modified Date</th>
                            <?php if ($this->session->userdata('id') == '189') { ?>
                                <th>Break Logic</th>
                            <?php } ?>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $a = 1;
                        $breaklogID = 0;
                        $commented_by = '';
                        if ($this->session->userdata('id') == '189') {
                            /* echo '<pre>'; print_r($breaklog); echo '</pre>'; */
                        }

                        foreach ($breaklog as $key => $blog) {
                            ?>
                            <tr id="listItem_<?php echo $key; ?>">
                                <?php /* ?><td><?php echo $blog['Date']; ?></td>
                                  <td><?php echo $Day; ?></td><?php */ ?>
                                <td>
                                    <strong>Break Start:</strong> <?php echo $blog['BreakStart']; ?> <br/>
                                    <strong>Break End:</strong> <?php
                                    if ($blog['BreakEnd'] != NULL) {
                                        echo $blog['BreakEnd'];
                                    }
                                    ?> <br/><br/>
                                    <strong>Total Time:</strong> <?php echo CalculateWorkedTime($blog['WorkedHours']); ?><!--round($blog['WorkedHours'], 2) . ' hours'-->
                                </td>
                                <td><?php echo $blog['BreakType']; ?></td>
                                <td><?php echo $blog['AddedBy']['FirstName'] . ' ' . $blog['AddedBy']['LastName']; ?></td>
                                <td><?php echo $blog['CheckedBy']['FirstName'] . ' ' . $blog['CheckedBy']['LastName']; ?></td>
                                <?php
                                if ($blog['CheckedBy']['FirstName'] && $blog['Comments'] != '') {
                                    $commented_by = ' (<strong>By: </strong>' . $blog['CheckedBy']['FirstName'] . ' ' . $blog['CheckedBy']['LastName'] . ')';
                                } else if ($blog['DeletedBy']['FirstName'] && $blog['Comments'] != '') {
                                    $commented_by = ' (<strong>Deleted By: </strong>' . $blog['DeletedBy']['FirstName'] . ' ' . $blog['DeletedBy']['LastName'] . ')';
                                } else {
                                    if ($blog['Comments'] != '') {
                                        $commented_by = ' <br><br>Regards,<br>' . $blog['AddedBy']['FirstName'] . ' ' . $blog['AddedBy']['LastName'] . '';
                                    }
                                }
                                ?>

                                <td><?php
                                    if ($blog['Comments'] != '') {
                                        if ($blog['Comments'] == 'Break Flag True') {
                                            echo $blog['Comments'];
                                        } else {
                                            echo $blog['Comments'] . $commented_by;
                                        }
                                    }
                                    ?></td>

                                <td><?php
                                    if ($blog['UpdatedComments'] != '') {
                                        $updated_by = ' <br><br>Regards,<br>' . $blog['CheckedBy']['FirstName'] . ' ' . $blog['CheckedBy']['LastName'] . '';
                                    }
                                    if ($blog['UpdatedComments'] != '') {
                                        echo $blog['UpdatedComments'] . $updated_by;
                                    }
                                    ?></td>

                                <td><?php echo $blog['ModifiedDate']; ?></td>
                                <?php if ($this->session->userdata('id') == '189') { ?>
                                    <td><?php echo $blog['BreakLogic']; ?></td>
                                <?php } ?>
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
        <!-- CONTENT END --> 

        <script>
            window.onunload = refreshParent;
            function refreshParent() {
                window.opener.document.getElementById("bttnatt").click();
            }
        </script>
        <?php $this->load->view('templates/shiftreports_footer'); ?>   
        <?php $this->load->view('templates/footer'); ?>