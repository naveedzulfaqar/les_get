<?php $this->load->view('templates/header'); ?>
<!-- OVERLAY FOR PROFILE START -->
<div id="overlay">
    <div class="container centered">   
        <span id="close"><i class="glyphicon glyphicon-remove-circle"></i></span>
    </div>
</div>   
<!-- OVERLAY FOR PROFILE END -->
<!-- CONTECT START --> 
<div class="container content">
    <!-- PERSONAL INFORMATION FORM START -->
    <div class="section-one result">
        <div class="col-lg-12">
            <?php
            if (isset($msg)) {
                echo '<h5>' . $msg . '</h5>';
            }
            ?>
            <h2>Applicants</h2>
            <div class="table-responsive">
                <table class="table table-bordered table-striped admin-table">
                    <colgroup>
                        <col class="col-xs-1">
                        <col class="col-xs-1">
                        <col class="col-xs-1">
                        <col class="col-xs-2">
                        <col class="col-xs-2">
                        <col class="col-xs-1">
                        <col class="col-xs-1">
                        <col class="col-xs-1">
                        <col class="col-xs-2">
                    </colgroup>
                    <thead>
                        <tr>
                            <th>Applicant #</th>
                            <th>Test Date</th>
                            <th>Name</th>
                            <th>Cell# </th>
                            <th>Email</th>
                            <th>Score</th>
                            <th>Short List</th>
                            <th>Hired</th>
                            <th>Refferal</th>
                            <th>Country</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $count = 1;
                        $a = 0;
                        foreach ($applicants as $applicant) {
                            ?>
                            <tr>
                                <td><?php echo '#' . $count; ?></td>
                                <?php
                                $date = strtotime($applicant['date']);
                                $date = date('m-d-Y', $date);
                                ?>
                                <td><?php echo $date; ?></td>
                                <td><a href="#" data-id="<?php echo $applicant['id']; ?>" class="ap-detail"><?php echo $applicant['applicant_name']; ?></a></td>
                                <td><?php echo $applicant['phone_number']; ?></td>
                                <td><?php echo $applicant['email']; ?></td>
                                <td><?php echo $get_all_scores[$a]['obtain_score'] . '/' . $get_all_scores[$a++]['total_score'] ?></td>
                                <td>
                                    <?php
                                    if ($applicant['short_list'] == 0) {
                                        echo 'No';
                                    } else if ($applicant['short_list'] == 1) {
                                        echo 'Yes';
                                    } else {
                                        echo 'N/A';
                                    }
                                    ?>
                                </td>
                                <td>
                                    <?php
                                    if ($applicant['set_job'] == 0) {
                                        echo 'No';
                                    } else if ($applicant['set_job'] == 1) {
                                        echo 'Yes';
                                    } else {
                                        echo 'N/A';
                                    }
                                    ?>
                                </td>
                                <td><?php echo $applicant['about_info']; ?></td>
                                <td><?php echo $applicant['country']; ?></td>
                            </tr>
    <?php $count++;
}
?>
                    </tbody>
                </table>
            </div> 
        </div>
    </div>
    <!-- PERSONAL INFORMATION FORM END -->
</div>
<!-- CONTECT END -->    
<?php $this->load->view('templates/footer'); ?>