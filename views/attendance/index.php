<?php $this->load->view('templates/header'); ?>
<!-- CONTENT START --> 
<div class="container content">
    <div class="col-sm-12 attendencetab">
        <?php $this->load->view('templates/attendance_tab'); ?>
    </div>
    <!--    <div style="font-size: 14px" class="notifications">
    <?php
    //if ($this->session->userdata('role') == '1' || $this->session->userdata('role') == '2' || $this->session->userdata('role') == '3') {
    //echo 'Please make sure that your team Breaks, Leaves, Attendance & Bonuses must be addressed by <strong>22nd December 2016 03:00pm PST.</strong>';
    //} else {
    //echo 'Please make sure that Breaks, Leaves and Attendance must be addressed by your manager/supervisor/team lead.';
    //}
    ?>
        </div>     -->


    <script>        
        $(".notifications").click(function () {
            $(".notifications").hide();
        });
    </script>

    <!-- PERSONAL INFORMATION FORM START -->
    <div class="col-lg-12 appi-res" style="padding-left: 0px; padding-right: 0px;">
        <!--<h2><span>Attendance</span></h2>-->
        <form action="">
            <div class="get_attendance">
                <div class="form-group">
                    <!--<form action="">-->
                    <div class="col-md-2 col-sm-6 emp_id">
                        <label for="inputPassword">Search by ID</label>
                        <?php if (!($this->session->userdata('role') == '4')) { ?>
                            <input type="text" min="1" placeholder ="Employee ID" max="1000000000" name="empid" class="date_format" id="empid" value="<?php
                            if ($EmployeeID != '') {
                                echo $EmployeeID;
                            }
                            ?>" onkeyup="getname()" autocomplete="off" pattern= "[0-9]*" maxlength="10" style="width: 100%;">
                               <?php } else {
                                   ?>
                            <input type="text" name="empid" class="date_format" id="empid" value="<?php echo $this->session->userdata('id'); ?>" maxlength="10" disabled="disabled" style="width: 100%;">
                        <?php } ?>
                    </div>
                    <div class="col-md-3  col-sm-6 emp_name">
                        <label for="inputPassword">Search by Name (Pseudo)</label>   
                        <div class="input_container">
                            <?php if (!($this->session->userdata('role') == '4')) { ?>
                                <input type="text" id="empname" placeholder="Employee Name" name="empname" class="date_format" value="<?php
                                if ($EmployeeName != '') {
                                    echo $EmployeeName;
                                }
                                ?>" onkeyup="autocomplet()" autocomplete="off" style="width: 100%;">
                                   <?php } else {
                                       ?>
                                <input type="text" id="empname" name="empname" class="date_format" value="<?php echo $this->session->userdata('FirstName') . ' ' . $this->session->userdata('LastName'); ?>"  style="width: 100%;" disabled="disabled">
                            <?php } ?>	
                            <ul id="emp_list_name" style="max-height:160px; overflow: auto;"></ul>
                        </div>
                    </div>
                    <div class="col-md-2 col-xs-6 st_date">
                        <label for="inputPassword">Start Date</label>
                        <div class='input-group date'>
                            <input class="required date_format" id="datepicker33" value="<?php echo (isset($sdate) && $sdate != "") ? $sdate : ""; ?>" name="start_date" type="text" placeholder="Select Start Date" style="width: 100%;">
                            <span class="input-group-addon calendar">
                                <span class="glyphicon glyphicon-calendar"></span>
                            </span>
                        </div>
                    </div>
                    <div class="col-md-2 col-xs-6 end_date">
                        <label for="inputPassword">End Date</label>
                        <div class='input-group date'>
                            <input class="required date_format" id="datepicker44" name="end_date" placeholder="Select End Date" type="text" value="<?php echo (isset($edate) && $edate != "") ? $edate : ""; ?>" style="width: 100%;">
                            <span class="input-group-addon calendar">
                                <span class="glyphicon glyphicon-calendar"></span>
                            </span>
                        </div>
                    </div>
                    <?php if (!($this->session->userdata('role') == '4')) { ?>
                        <div class="col-md-2  col-sm-6 bttn_format" id="bttn_getattendance" <?php
                        if ((isset($EmployeeName) && $EmployeeName != '') && (isset($EmployeeID) && $EmployeeID != '')) {
                            echo 'style="display:block; margin-left:0;"';
                        } else {
                            ?>style="display:none;margin-left:0;"<?php } ?>>
                            <input type="submit" name="getattendance" id ="bttnatt" class="btn btn-primary btn-sm get_reports" value="Get Attendance">
                            <img id="loader"  style="display:none; border: solid 1px #eee; margin-top:7px;"  src="/les/assets/images/ajax-loader.gif">
                        </div>
                    <?php } else {
                        ?>
                        <div class="col-md-2  col-sm-6 bttn_format" style="margin-left:0" id="bttn_getattendance" >
                            <input type="submit" name="getattendance" id ="bttnatt" class="btn btn-primary btn-sm get_reports" value="Get Attendance">
                            <img id="loader"  style="display:none; border: solid 1px #eee; margin-top:7px;"  src="/les/assets/images/ajax-loader.gif">
                        </div>
                    <?php } ?>    

                    <!--</form>-->
                </div>
            </div>
        </form>
    </div>
    <div class="col-xs-12 section-one result">
        <div class="col-xs-12 col-lg-12 show-appi-record">

        </div>
    </div>
    <!-- PERSONAL INFORMATION FORM END -->
</div>

<!-- CONTENT END -->
<script>
    $(document).ready(function () {
        $("input[name='getattendance']").trigger("click");

        $("#bttnatt").click(function () {
            var empid = "<?php echo $this->session->userdata('id'); ?>";
            console.log("emp ", empid);
            get_pending_request_count(empid);
        });
    });
    $(function () {
        var dates = $("#datepicker33").datepicker({
            //minDate: "0",
            maxDate: new Date(),
            defaultDate: "-1w",
            dateFormat: 'yy-mm-dd',
            numberOfMonths: 2,
            //showButtonPanel: true,
            onSelect: function (date) {
                for (var i = 0; i < dates.length; ++i) {
                    if (dates[i].id < this.id)
                        $(dates[i]).datepicker('option', 'maxDate', date);
                    else if (dates[i].id > this.id)
                        $(dates[i]).datepicker('option', 'minDate', date);
                }
            }
        });
        var dates = $("#datepicker44").datepicker({
            minDate: $("#datepicker33").datepicker("getDate"),
            maxDate: new Date(),
            defaultDate: "-1w",
            dateFormat: 'yy-mm-dd',
            numberOfMonths: 2,
            //showButtonPanel: true,
            onSelect: function (date) {
                for (var i = 0; i < dates.length; ++i) {
                    if (dates[i].id < this.id)
                        $(dates[i]).datepicker('option', 'maxDate', date);
                    else if (dates[i].id > this.id)
                        $(dates[i]).datepicker('option', 'minDate', date);
                }
            }
        });
    });

    function get_pending_request_count(empid) {
        //var min_length = 3; // min caracters to display the autocomplete

        $.ajax({
            url: "/les/index.php/pendingrequestcount/index",
            type: 'POST',
            data: {empid: empid},
            success: function (response) {
                //console.log(data);
                json_obj = $.parseJSON(response); //parse JSON

                var count_pending_breaks = json_obj['count_pending_breaks'];
                var count_pending_leaves = json_obj['count_pending_leaves'];
                var count_pending_leaves_encashments = json_obj['count_pending_leaves_encashments'];
                var count_pending_ot = json_obj['count_pending_ot'];
                var count_pending_sr = json_obj['count_pending_sr'];
                var count_pending_pr = json_obj['count_pending_pr'];
                var count_pending_swaps = json_obj['count_pending_swaps'];
                var total_pending_requests = json_obj['total_pending_requests'];
                //console.log(count_pending_breaks + ' + ' + count_pending_leaves);

                $('#count_pending_breaks').show();
                $('#count_pending_breaks').html(count_pending_breaks);
                $('#count_pending_leaves').show();
                $('#count_pending_leaves').html(count_pending_leaves);
                $('#count_pending_leaves_encashments').show();
                $('#count_pending_leaves_encashments').html(count_pending_leaves_encashments);
                $('#count_pending_ot').show();
                $('#count_pending_ot').html(count_pending_ot);
                $('#count_pending_sr').show();
                $('#count_pending_sr').html(count_pending_sr);
                $('#count_pending_pr').show();
                $('#count_pending_pr').html(count_pending_pr);
                $('#count_pending_swaps').show();
                $('#count_pending_swaps').html(count_pending_swaps);
                $('#total_pending_requests').show();
                $('#total_pending_requests').html(total_pending_requests);
            }
        });
    }
</script>
<?php
$this->load->view('templates/attendance_footer');
$this->load->view('templates/footer');
