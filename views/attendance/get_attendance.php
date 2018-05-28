<div class="col-xs-12 appli-records">
    <?php if ($result) { ?>
        <div class="col-md-7  col-sm-6">
            <h3><span><?php echo $result; ?></span></h3>
        </div>
    <?php } else {
        ?>
        <h3 class="info-person" style="text-align:right; float:right; min-width: 0;" >
            <span style="float:left">
                <?php
                if ($employee) {
                    $completeName = $employee['FirstName'] . ' ' . $employee['LastName'];
                    $name_brk = ' ';
                    if (strlen("$completeName") > 21) {
                        $name_brk = '<br>';
                    }
                    if ($employee['PseudoFirstName']) {
                        $completeName .= $name_brk . '(' . $employee['PseudoFirstName'] . ' ' . $employee['PseudoLastName'] . ')';
                    }
                    ?> 
                    <span style="text-align:right"><a href="<?php echo site_url(File_BASE_URL . '/employees/view') . '/' . $employee['ID']; ?>" target="_blank">
                            <?php echo trim($completeName); ?></a>&nbsp;
                        <span id="active-att" style="display: none;">
                            <img src="<?php echo site_url('/assets/images/') . '/att-active.png'; ?>" class="img-rounded">
                        </span>
                        <a href="<?php echo site_url(File_BASE_URL . '/employees/view') . '/' . $employee['ID']; ?>" target="_blank">
                            <img src="<?php echo site_url('/assets/uploads/avatars') . '/' . $employee['Image']; ?>" width="32" height="32" class="img-rounded att-active">
                        </a>
                    </span>
                    <?php
                    $department_detail = $department_history[0]['Name'] . ' - ' . $department_history[0]['CompanyName'] . ' (' . $department_history[0]['GroupName'] . ')';
                    if ($department_history[0]['DepartmentID'] == '1') {
                        $department_detail .= ' - ' . $employee['Pool'];
                    }
                    echo '<br><span style="font-size: 14px; float: left; margin-top: 4px; margin-right: 5px;">' . $department_detail . '</span> ';

                    if ($this->session->userdata('id') == '189' || $this->session->userdata('id') == '321') {
                        ?> 
                        <span class="viewshift" style="color : #131186; font-size: 12px; float: left; margin-top: 4px; margin-right: 5px;">
                            <a href="<?php echo site_url(File_BASE_URL . '/employees/logged_in_as') . '/' . $employee['ID']; ?>"><?php echo $employee['FirstName'] . ' ' . $employee['LastName'] ?></a>
                        </span>
                        <?php
                    }
                }
                ?>                
            </span></h3>
        <div style="clear: both;"></div>
        <div class="leaves_info col-xs-12" style="float:left;">
            <div id="attendance_details_summary" style="position:relative; float:left;"></div>
        </div>
        <div style="clear: both;"></div>
        <?php //if($this->session->userdata('role') == '1' || $this->session->userdata('role') == '2'|| $this->session->userdata('role') == '3'){        ?>
        <div style="float:right">
        <!-- <input type="submit" name="download-excel-report" id ="bttn_att_download_excel" class="btn btn-primary btn-sm download_excel modify_break" value="Download Report in Excel " style="padding-top: 1px;padding-bottom: 1px;">-->
        </div>
        <?php //}        ?>    

        <div style="clear: both;"></div>

        <?php
        if ($this->session->userdata('id') == $employee['ID']) {
            if ($this->session->userdata('role') != '1') {
                if ($employee['UserOSType'] == "windows") {

                    if ($employee['ID'] == 159 ||
                            $employee['ID'] == 26 ||
                            $employee['ID'] == 63 ||
                            $employee['ID'] == 34 ||
                            $employee['ID'] == 42 ||
                            $employee['ID'] == 163 ||
                            $employee['ID'] == 161 ||
                            $employee['ID'] == 37
                    ) {
                        
                    } else {
                        ?>
                        <div id="les_version" class="notifications" style="display:none;">Latest LES build is available, please <a href="http://les.thelivechatsoftware.com/les/download/les_5.3/LES_5.3.msi">Click here</a> to download latest LES version 5.3</div>
                        <?php
                    }
                }
            }
        }
        ?>
        <div style="float:right">
            <?php
            if ($this->session->userdata('id') == $employee['ID']) {
                if ($encashment_counter == '1') {
                    ?>
                    <input type="button" name="apply-leave-encashment" class="btn btn-primary btn-sm get_reports apply-leave-encashment modify_break" data-employee-id-show="<?php echo $employee['ID']; ?>" data-current-month="<?php echo $current_month; ?>" value="Apply for Leave Encashment" style="padding-top: 3px !important;padding-bottom: 3px !important;"> 
                <?php } ?>
                <input type="button" name="leave-apply-future" class="btn btn-primary btn-sm get_reports leave-apply-future modify_break" data-employee-id-show="<?php echo $employee['ID']; ?>" data-leave-year="<?php echo $LeaveYear; ?>" value="Apply for Future Leave" style="padding-top: 3px !important;padding-bottom: 3px !important;">
            <?php } ?>
        </div>
        <div style="clear: both;"></div>
        <div class="table-responsive top">
            <table class="table table-striped table-bordered admin-table" width="100%" cellspacing="0">
                <colgroup>
                    <col class="col-xs-1">
                    <?php if ($this->session->userdata('role') != '4') { ?>
                        <col class="col-xs-3">	
                    <?php } else { ?>
                        <col class="col-xs-4">
                    <?php } ?>

                    <?php if ($this->session->userdata('role') != '4') { ?>
                        <col class="col-xs-2">
                    <?php } ?>

                    <?php if ($this->session->userdata('role') == '1' || $this->session->userdata('role') == '2') { ?>
                        <col class="col-xs-3">
                    <?php } else if ($this->session->userdata('role') == '3') {
                        ?>
                        <col class="col-xs-4">	
                    <?php } else {
                        ?>
                        <col class="col-xs-5">
                    <?php } ?>

                    <col class="col-xs-2">
                </colgroup>
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Timing Info</th>
                        <?php if (!($this->session->userdata('role') == '4')) { ?>
                            <th>Manage Attendance</th>
                        <?php }
                        ?>
                        <th>Break Info</th>
                        <th>Shift Info</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    if (isset($_COOKIE['clientTimeZone'])) {
                        $timezone = $_COOKIE['clientTimeZone'];
                    } else {
                        $timezone = 'UP5';
                    }
                    $FromTimeZone = 'GMT';
                    $ToTimeZone = $timezone;

                    $total_shift_ot_duration = 0;
                    $total_att_ot_duration = 0;
                    $total_pending_ot_duration = 0;
                    $total_pending_ot_count = 0;
                    $total_unapproved_ot_count = 0;
                    $total_approved_att_ot_duration = 0;
                    $total_approved_ot_count = 0;
                    $calculated_worked_hours = 0;
                    $total_calculated_worked_duration = 0;
                    $punctuality = 0;
                    $approved_sick_leave_counter = 0;
                    $pending_sick_leave_counter = 0;
                    $approved_floater_counter = 0;
                    $pending_floater_counter = 0;
                    $total_late_time = 0;
                    $total_late_intime = 0;
                    $total_late_intime_15min = 0;
                    $total_late_intime_15min_days = '';
                    $total_late_intime_30min = 0;
                    $total_late_intime_30min_days = '';
                    $total_late_intime_1hr = 0;
                    $total_late_intime_1hr_days = '';
                    $late_intime = 0;
                    $total_early_logout_days = '';
                    $total_early_logout_6hrs_days = '';
                    $weekend_shift = 0;
                    $weekend_shift_days = 0;
                    $total_break_time_15min = 0;
                    $total_break_time_15min_days = '';
                    $total_break_time_45min = 0;
                    $total_break_time_45min_days = '';
                    $total_early_outtime = 0;
                    $late_outtime = 0;
                    $intime_late_comer = 0;
                    $pending_late_comer = 0;
                    $approved_late_comer = 0;
                    $approved_late_comer_duration = 0;
                    $rejected_late_comer = 0;
                    $outtime_early_exit = 0;
                    $pending_early_exit = 0;
                    $approved_early_exit = 0;
                    $approved_early_exit_duration = 0;
                    $rejected_early_exit = 0;
                    $latestModifiedDate = 0;
                    $total_time_in = 0;
                    $total_allocated_break_time = 0;
                    $total_wc_break = 0;
                    $total_wc_break_time = 0;
                    $total_break_time = 0;
                    $total_break_time_ot = 0;
                    $total_working_time = 0;
                    $total_incentive_balance = 0;

                    $total_break_training_approved = 0;
                    $total_break_training_unapproved = 0;
                    $total_break_meeting_approved = 0;
                    $total_break_meeting_unapproved = 0;
                    $total_break_discussion_approved = 0;
                    $total_break_discussion_unapproved = 0;
                    $total_break_interview_approved = 0;
                    $total_break_interview_unapproved = 0;
                    $total_break_coaching_session_approved = 0;
                    $total_break_coaching_session_unapproved = 0;

                    $total_break_inactivity = 0;
                    $total_break_meal = 0;
                    $total_break_tea = 0;
                    $total_break_prayer = 0;
                    $total_break_other = 0;
                    $total_break_hibernate_sleep = 0;
                    $total_break_crash = 0;
                    $total_break_network = 0;
                    $total_break_web = 0;
                    $total_break_employee = 0;
                    $total_break_manual_logout = 0;
                    $total_break_system_changed = 0;
                    $total_break_shut_down = 0;

                    $total_shift_days = 0;
                    $total_worked_days = 0;
                    $total_break_days = 0;
                    $total_absents = 0;
                    $absent_days = '';
                    $total_leaves_duration = 0;
                    $total_leaves = 0;
                    $total_pending_leaves = 0;
                    $total_unapproved_leaves = 0;
                    $pending_leave_days = '';
                    $total_pending_leaves_duration = 0;
                    $total_holidays = 0;
                    $holidays_days = 0;
                    $total_holidays_duration = 0;
                    $total_compensatedays = 0;
                    $compensatedays_days = 0;
                    $total_compensatedays_duration = 0;
                    $extra_days = '';
                    $total_extra_days = 0;
                    $total_extra_duration = 0;
                    $total_swaps_duration = 0;
                    $total_swaps = 0;
                    $total_pending_swaps = 0;
                    $pending_swaps_days = '';
                    $total_pending_swaps_duration = 0;
                    $a = 0;

                    $Friday_Break_Counter = 0;
                    $total_allocated_friday_break_time = 0;
                    $wc_allocated_break_count = 0;

                    $total_chats = 0;
                    $total_billable_chats = 0;
                    $total_chat_incentive = 0;
                    $count_eid_compensate_days = 0;
                    $total_ramzan_break_timing = 0;
                    $shift_ot_auto_popup = 0;
                    $break_request_auto_popup = 0;
                    $daily_break_allowed = 0;
                    $daily_break_allowed_time = 0;

                    $count = count($department_history) - 1;
                    if ($this->session->userdata('id') == '189') {
                        //echo '<pre>'; print_r($attendances); echo '</pre>'; exit;
                    }
                    foreach ($attendances as $key => $attendance) {
                        //  code for salary lock 2018-03-02
                        $data = array();
                        $month = date('m', strtotime($attendance['Date']));
                        $year = date('Y', strtotime($attendance['Date']));

                        $month_start_date = date("$year-$month-21");
                        $year_start_date = date("$year-12-21");

                        if (strtotime($attendance['Date']) >= strtotime($month_start_date)) {
                            $month = $month + 1;
                            if ($month == 13) {
                                $month = 1;
                            }
                        }
                        if (strtotime($attendance['Date']) >= strtotime($year_start_date)) {
                            $year = $year + 1;
                        }

                        $data['SalaryMonth'] = $month;
                        $data['SalaryYear'] = $year;
                        $data['DepartmentID'] = $attendance['DepartmentID'];

                        //calling helper function to get current month salary lock data
                        $salary_lock = department_salary_lock_data($data);

                        // if lock status is y then salary is locked else not locked
                        $lock_status = 'n';
                        foreach ($salary_lock as $lock) {
                            if ($lock['DepartmentID'] == $data['DepartmentID']) {
                                $lock_status = $lock['LockStatus'];
                            }
                        }
                        //echo '<br>lock_status '.$lock_status;
                        //  code for salary lock 2018-03-02

                        if (strtotime($attendance['Date']) <= strtotime(date('Y-m-d'))) {
                            if (strtotime($attendance['Date']) <= strtotime(date('Y-m-d'))) {
                                if (strtotime($attendance['ModifiedDate']) >= $latestModifiedDate) {
                                    $latestModifiedDate = date("Y-m-d H:i:s", strtotime($attendance['ModifiedDate']));
                                }
                            }
                            ?>
                            <tr id="listItem_<?php echo $key; ?>"  >
                                <td><?php echo $attendance['Date']; ?><br /><?php echo $attendance['Day']; ?></td>
                                <td>
                                    <?php
                                    if ($attendance['WorkingDay'] == 1) {
                                        $total_shift_days++;
                                    }
                                    $worked_time = 0;
                                    if ($attendance['WorkingDay'] == 0 && $attendance['InTime'] != 'Off') {
                                        $total_extra_days++;
                                        $worked_time = CalculateTimeDifferencce($attendance['InTime'], $attendance['OutTime']);
                                        $total_extra_duration = $worked_time + $total_extra_duration;
                                        $extra_days .= $attendance['Date'] . ' (' . $attendance['Day'] . ')&#013;';
                                    }

                                    if ($attendance['InTime'] != 'Off') {
                                        $total_worked_days++;
                                        //if ($this->session->userdata('id') == '321') {
                                        //Setting Up TimeZones
                                        if (isset($_COOKIE['clientTimeZone'])) {
                                            $timezone = $_COOKIE['clientTimeZone'];
                                        } else {
                                            $timezone = 'UP5';
                                        }
                                        $FromTimeZone = 'GMT';
                                        $ToTimeZone = $timezone;

                                        $current_time = date('h:i a');
                                        $current_time = ConvertTimeZone($current_time, $FromTimeZone, $ToTimeZone);
                                        if ((($attendance['ActualSRInTime']) || ($attendance['ActualSROutTime'])) && ($attendance['EmpLoggedIn'] == '1')) {
                                            if (strtotime($attendance['InTime']) >= strtotime($current_time)) {
                                                $total_working_time = 0 + $total_working_time;
                                            } else {
                                                $total_working_time = $attendance['WorkedHours'] + $total_working_time;
                                            }
                                        } else {
                                            $total_working_time = $attendance['WorkedHours'] + $total_working_time;
                                        }
                                        //} else {
                                        //$total_working_time = $attendance['WorkedHours'] + $total_working_time;
                                        //}
                                        if ($attendance['OutTime']) {
                                            //Setting Up TimeZones
                                            if (isset($_COOKIE['clientTimeZone'])) {
                                                $timezone = $_COOKIE['clientTimeZone'];
                                            } else {
                                                $timezone = 'UP5';
                                            }
                                            $FromTimeZone = $timezone;
                                            $ToTimeZone = 'GMT';

                                            $ITime = 0;
                                            $OTime = 0;

                                            $ITime = ConvertTimeZone($attendance['InTime'], $FromTimeZone, $ToTimeZone);
                                            $OTime = ConvertTimeZone($attendance['OutTime'], $FromTimeZone, $ToTimeZone);

                                            $totaltime = CalculateTimeDifferenceNew($ITime, $attendance['CDate'], $OTime, $attendance['EndDate']);
                                            //$totaltime = CalculateTimeDifferencce($attendance['InTime'], $attendance['OutTime']);
                                        } else {
                                            //Setting Up TimeZones
                                            if (isset($_COOKIE['clientTimeZone'])) {
                                                $timezone = $_COOKIE['clientTimeZone'];
                                            } else {
                                                $timezone = 'UP5';
                                            }
                                            $FromTimeZone = 'GMT';
                                            $ToTimeZone = $timezone;

                                            $gmtcurrenttime = date('h:i a', strtotime("now"));
                                            $currentdate = date('Y-m-d');
                                            $currenttime = ConvertTimeZone($gmtcurrenttime, $FromTimeZone, $ToTimeZone);
                                            $totaltime = CalculateTimeDifferenceNew($attendance['InTime'], $attendance['CDate'], $currenttime, $currentdate);
                                            //$totaltime = CalculateTimeDifferencce($attendance['InTime'], $currenttime);
                                        }

                                        $breakallocationtime = (($attendance['ShiftsHours'] / 2) - (($attendance['ShiftsHours'] * 6.67 / 60) / 2));
                                        $allocated_break_time = 0;
                                        $wc_allocated_break_time = 0;
                                        if ($totaltime >= $breakallocationtime) {
                                            $allocated_break_time = ($attendance['ShiftsHours'] * 6.67 / 60);

                                            //echo '<pre>'; print_r($attendance); echo '</pre>';
                                            if (($attendance['DailyBreakAllowed']['DailyBreakAllowed'] == '0') && (date('Y-m-d', strtotime($attendance['Date'])) >= date('Y-m-d', strtotime($attendance['DailyBreakAllowed']['EffectiveDate'])))) {
                                                if ($daily_break_allowed_time == 0) {
                                                    $daily_break_allowed = 'no';
                                                    $daily_break_allowed_time = ($attendance['ShiftsHours'] * 6.67 / 60);
                                                }
                                                $total_break_time_duration = $attendance['BreakTime']['total_break_time'];     //- $attendance['BreakTime']['overall_break_time_approved']  
                                                if ($total_break_time_duration < $allocated_break_time) {
                                                    $allocated_break_time = $total_break_time_duration;
                                                }
                                            }

                                            $total_allocated_break_time = $allocated_break_time + $total_allocated_break_time;
                                            if ($allocated_break_time > 0) {
                                                $total_break_days++;
                                            }

                                            if (($department_history[0]['DepartmentID'] == 1 && $employee['ID'] != '1' && $employee['ID'] != '2' && $employee['ID'] != '3') || ($department_history[0]['DepartmentID'] == 15)) { //if department is operations
                                                if (date('Y-m-d', strtotime($attendance['Date'])) >= date('Y-m-d', strtotime('2016-07-21'))) {
                                                    //Operation Pool B Breaks Adjustment
                                                    if ($employee['Pool'] == 'Pool B' && $attendance['ShiftsHours'] == '28800') { //adding 7 min for Operation pool B employees
                                                        $allocated_break_time = pool_b_breaks_adjustment($allocated_break_time);
                                                        $total_allocated_break_time = pool_b_breaks_adjustment($total_allocated_break_time);
                                                    }

                                                    if (date('Y-m-d', strtotime($attendance['Date'])) < date('Y-m-d', strtotime('2017-08-21'))) {
                                                        $wc_allocated_break_count++;
                                                        $wc_allocated_break_time = OperationWCBreakAllocation($wc_allocated_break_time); // adding 15 min wc break
                                                        $total_wc_break_time = $wc_allocated_break_time + $total_wc_break_time; // calculating total wc break time
                                                        $allocated_break_time = OperationWCBreakAllocation($allocated_break_time); // adding 15 min to the allocated break time
                                                        $total_allocated_break_time = OperationWCBreakAllocation($total_allocated_break_time); // adding 15 min to the total allocated break time
                                                    }
                                                }
                                            }
                                        } else {
                                            /* $breakallocated = 0 * 60; */
                                        }

                                        $Friday_Break_Allowed = 0;
                                        $friday_break_time = 0;
                                        if ($attendance['Day'] == 'Friday') {
                                            //friday break is not allowed for Operations, QC, QA, Training, CSA, Operations Philippines, QC Philippines after 2018-04-21 added 2018-05-11
                                            if ((date('Y-m-d', strtotime($attendance['Date'])) >= date('Y-m-d', strtotime('2018-04-21'))) && ($department_history[0]['DepartmentID'] == 1 || $department_history[0]['DepartmentID'] == 4 || $department_history[0]['DepartmentID'] == 6 || $department_history[0]['DepartmentID'] == 10 || $department_history[0]['DepartmentID'] == 15 || $department_history[0]['DepartmentID'] == 34 || $department_history[0]['DepartmentID'] == 36)) {
                                                
                                            } else {
                                                $GMTFridayPrayerStartTime = strtotime("08:00 am");   // 1455267600 means PST 1PM // GMT 8AM (9:00 am) (1455267600)
                                                $GMTFridayPrayerEndTime = strtotime("09:00 am");   // 1455267600 means PST 2PM // GMT 8AM (9:00 am) (1455267600)
                                                if (strtotime($attendance['GMTInTime']) < $GMTFridayPrayerEndTime) { // means employee comes before 2PM PST jo 2 bajey sey pehlay aaye ga                                                
                                                    if (strtotime($attendance['GMTOutTime']) > $GMTFridayPrayerStartTime || $attendance['GMTOutTime'] == '' || $attendance['GMTOutTime'] == Null) { // means employee left before 1PM PST jo 1 bajey k baad gaya tha
                                                        $Friday_Break_Allowed = 1;
                                                        $Friday_Break_Counter++;
                                                        /* echo '<br>Early Friday '.$Friday_Break_Counter; */
                                                    }
                                                }
                                                if ($Friday_Break_Allowed == 1) {
                                                    $friday_break_time = '1800' + $friday_break_time;
                                                    $allocated_break_time = '1800' + $allocated_break_time;
                                                    $total_allocated_break_time = '1800' + $total_allocated_break_time;
                                                    $total_allocated_friday_break_time = '1800' + $total_allocated_friday_break_time;
                                                }
                                            }
                                        }

                                        //adding ramzan prayer break timing of 30 minutes
                                        if (strtotime($attendance['Date']) >= strtotime('2017-06-21') && strtotime($attendance['Date']) <= strtotime('2017-06-25')) {
                                            if (($attendance['CompanyID'] == '1' && $attendance['DepartmentID'] != '33') || ($attendance['DepartmentID'] == '32' && $EmployeeID != '164' && $EmployeeID != '316' && $EmployeeID != '500' && $EmployeeID != '545') || $EmployeeID == '26' || $EmployeeID == '177' || $EmployeeID == '566') {
                                                $total_ramzan_break_timing = '1800' + $total_ramzan_break_timing;
                                            }
                                        }
                                    }
                                    if ($attendance['WorkingDay'] == 1 && $attendance['InTime'] == 'Off' && !($attendance['Holiday']['HolidayDate'])) {
                                        $total_absents++;
                                        $absent_days .= $attendance['Date'] . ' (' . $attendance['Day'] . ')&#013;';
                                    }

                                    if ($attendance['InTime'] != 'Off' || $attendance['OutTime'] != 'Off') {
                                        if ($attendance['StartDayID'] != $attendance['EndDayID']) {
                                            /* $startdate = '('.$attendance['Date'].')'; */
                                            /* $enddate = '<br>('.date('Y-m-d', strtotime(' +1 day', strtotime($attendance['Date']))).')'; */
                                        } else {
                                            $enddate = '';
                                        }
                                        $total_time_in = date('H:i', strtotime($attendance['InTime'])) + $total_time_in;
                                        //echo $attendance['Date'];
                                        if (strtotime($attendance['Date']) == strtotime(date('Y-m-d'))) {
                                            //echo $attendance['LESVersion'];
                                            if ($attendance['LESVersion'] != "5.3 Beta") {
                                                ?>
                                                <script>$("#les_version").show();
                                                    $("#les_version").click(function () {
                                                        $("#les_version").hide();
                                                    });

                                                </script>
                                                <?php
                                            }
                                        }
                                        ?>

                                        <?php if ($attendance['InTime']) { ?>
                                            <span>
                                                <strong>Time In:</strong> 
                                                <?php
                                                echo '<span class="timedisplay" >' . $attendance['InTime'] . '</span></span>';

                                                if ((strtotime($attendance['Date']) == strtotime(date('Y-m-d'))) && ($attendance['OutTime'] == '')) {
                                                    ?>
                                                    <script>
                                                        //$("#active-att").show();
                                                        $(".att-active").css("border", "solid green");
                                                    </script>
                                                    <?php
                                                }

                                                if (($department_history[0]['DepartmentID'] == '10')) {
                                                    if (strtotime($attendance['InTime']) > strtotime($attendance['ShiftStartTime'])) {
                                                        $late_duration = 0;
                                                        $late_duration = strtotime($attendance['InTime']) - strtotime($attendance['ShiftStartTime']);
                                                        if ($late_duration > 900 && $late_duration < 1800) {
                                                            $total_late_intime_15min++;
                                                            $total_late_intime_15min_days .= $attendance['Date'] . ' (' . $attendance['Day'] . ') - ' . $late_duration . '&#013;';
                                                        }
                                                        if ($late_duration > 1800 && $late_duration < 3600) {
                                                            $total_late_intime_30min++;
                                                            $total_late_intime_30min_days .= $attendance['Date'] . ' (' . $attendance['Day'] . ') - ' . CalculateWorkedTime($late_duration) . '&#013;';
                                                        }
                                                        if ($late_duration > 3600) {
                                                            $total_late_intime_1hr++;
                                                            $total_late_intime_1hr_days .= $attendance['Date'] . ' (' . $attendance['Day'] . ') - ' . CalculateWorkedTime($late_duration) . '&#013;';
                                                        }
                                                    }
                                                    if (strtotime($attendance['OutTime']) < strtotime($attendance['ShiftEndTime'])) {
                                                        $early_duration = 0;
                                                        $early_duration = strtotime($attendance['ShiftEndTime']) - strtotime($attendance['OutTime']);
                                                        if ($early_duration < 21600) {
                                                            $total_early_logout++;
                                                            $total_early_logout_days .= $attendance['Date'] . ' (' . $attendance['Day'] . ') &#013;';
                                                        } else {
                                                            $total_early_logout_6hrs++;
                                                            $total_early_logout_6hrs_days .= $attendance['Date'] . ' (' . $attendance['Day'] . ') &#013;';
                                                        }
                                                    }
                                                    if ($attendance['WorkingDay'] == 1 && ($attendance['StartDayID'] == 6 || $attendance['StartDayID'] == 7)) {
                                                        $weekend_shift++;
                                                        $weekend_shift_days .= $attendance['Date'] . ' (' . $attendance['Day'] . ')&#013;';
                                                    }
                                                    foreach ($attendance['BreakTime'] as $key => $breakData) {
                                                        if ($breakData['total_break_time'] > 900 && $breakData['total_break_time'] < 2700) {
                                                            $total_break_time_15min++;
                                                            $total_break_time_15min_days .= $attendance['Date'] . ' (' . $attendance['Day'] . ') - ' . CalculateWorkedTime($breakData['total_break_time']) . '&#013;';
                                                        }
                                                        if ($breakData['total_break_time'] > 2700) {
                                                            $total_break_time_45min++;
                                                            $total_break_time_45min_days .= $attendance['Date'] . ' (' . $attendance['Day'] . ') - ' . CalculateWorkedTime($breakData['total_break_time']) . '&#013;';
                                                        }
                                                    }
                                                    //echo '<pre>'; print_r($attendance); echo '</pre>';
                                                }
                                                $punctuality_check = 0;

                                                if (($attendance['DepartmentID'] == '1' || $attendance['DepartmentID'] == '2' || $attendance['DepartmentID'] == '4' || $attendance['DepartmentID'] == '8' || $attendance['DepartmentID'] == '10' || $attendance['CompanyID'] == '3') && ($attendance['WorkingDay'] == 1)) {
                                                    /* echo '<pre>'; print_r($attendance); echo '</pre>'; */
                                                    $late_arrival = get_late_arrival_punctuality_request($attendance, $total_late_intime, $intime_late_comer, $pending_late_comer, $approved_late_comer, $rejected_late_comer, $punctuality, $punctuality_check, $approved_late_comer_duration);

                                                    if ($late_arrival['total_late_intime']) {
                                                        $total_late_intime = $late_arrival['total_late_intime'];
                                                    }

                                                    $punctuality_check = $late_arrival['punctuality_check'];

                                                    if ($late_arrival['approved_late_comer_duration']) {
                                                        $approved_late_comer_duration = $late_arrival['approved_late_comer_duration'];
                                                    }
                                                    if ($late_arrival['punctuality']) {
                                                        $punctuality = $late_arrival['punctuality'];
                                                    }
                                                    if ($late_arrival['intime_late_comer']) {
                                                        $intime_late_comer = $late_arrival['intime_late_comer'];
                                                    }
                                                    if ($late_arrival['approved_late_comer']) {
                                                        $approved_late_comer = $late_arrival['approved_late_comer'];
                                                    }
                                                    if ($late_arrival['pending_late_comer']) {
                                                        $pending_late_comer = $late_arrival['pending_late_comer'];
                                                    }
                                                    if ($late_arrival['rejected_late_comer']) {
                                                        $rejected_late_comer = $late_arrival['rejected_late_comer'];
                                                    }
                                                }
                                            }
                                            ?> <br/>
                                            <?php
                                            if ($attendance['OutTime'] && $attendance['EmpLoggedIn'] == '0') {
                                                ?>
                                                <strong>Time Out:</strong> <?php
                                                echo '<span class="timedisplay">' . $attendance['OutTime'] . '</span>';

                                                if (($attendance['DepartmentID'] == '1' || $attendance['DepartmentID'] == '2' || $attendance['DepartmentID'] == '4' || $attendance['DepartmentID'] == '8' || $attendance['DepartmentID'] == '10' || $attendance['CompanyID'] == '3') && ($attendance['WorkingDay'] == 1)) {
                                                    $early_exit = get_early_exit_punctuality_request($attendance, $total_early_outtime, $outtime_early_exit, $pending_early_exit, $approved_early_exit, $rejected_early_exit, $punctuality, $punctuality_check, $approved_early_exit_duration);

                                                    if ($early_exit['total_early_outtime']) {
                                                        $total_early_outtime = $early_exit['total_early_outtime'];
                                                    }

                                                    if ($early_exit['approved_early_exit_duration']) {
                                                        $approved_early_exit_duration = $early_exit['approved_early_exit_duration'];
                                                    }
                                                    if ($early_exit['punctuality']) {
                                                        $punctuality = $early_exit['punctuality'];
                                                    }
                                                    if ($early_exit['outtime_early_exit']) {
                                                        $outtime_early_exit = $early_exit['outtime_early_exit'];
                                                    }
                                                    if ($early_exit['approved_early_exit']) {
                                                        $approved_early_exit = $early_exit['approved_early_exit'];
                                                    }
                                                    if ($early_exit['pending_early_exit']) {
                                                        $pending_early_exit = $early_exit['pending_early_exit'];
                                                    }
                                                    if ($early_exit['rejected_early_exit']) {
                                                        $rejected_early_exit = $early_exit['rejected_early_exit'];
                                                    }
                                                }
                                            }
                                            if ($attendance['IsAmended'] == '1') {
                                                echo '<br><span class = "red">(Amended)</span>';
                                            }
                                            if ($late_arrival) {
                                                echo $late_arrival['late_punctuality_request'];
                                            }
                                            if ($early_exit) {
                                                echo $early_exit['early_punctuality_request'];
                                            }
                                            ?> <br />
                                            <?php
                                            if ($this->session->userdata('id') == '189' || $this->session->userdata('id') == '321') {
                                                //echo '<pre>'; print_r($attendance); echo '</pre>';
                                                if ($attendance['InTime']) {
                                                    ?>
                                                    <br /><strong>GMTIn: </strong><?php
                                                    echo '<span class="timedisplay">' . $attendance['CDate'] . '(' . $attendance['GMTInTime'] . ')</span>';
                                                }
                                                ?> <br/>
                                                <?php
                                                if ($attendance['OutTime'] && $attendance['EmpLoggedIn'] == '0') {
                                                    if (strtotime($attendance['CDate']) != strtotime($attendance['EndDate'])) {
                                                        //echo " <h3>Date changed</h3>";
                                                        $date_changed_style = 'class="red"';
                                                    } else {
                                                        $date_changed_style = '';
                                                    }
                                                    ?>
                                                    <span <?php echo $date_changed_style; ?>>
                                                        <strong>GMTOut: </strong><?php
                                                        echo '<span class="timedisplay">' . $attendance['EndDate'] . '(' . $attendance['GMTOutTime'] . ')</span></span>';
                                                        if ($attendance['AttendanceCrash'] == 1) {
                                                            $attendance['AttendanceCrash'] = 'Crash';
                                                        }
                                                        echo '<br><br><strong>Logout Type: </strong><span class="">' . $attendance['AttendanceCrash'] . '</span>';
                                                    }
                                                }
                                                ?> <br />
                                                <?php if ($attendance['InTime']) { ?>
                                                    <strong>Logged In Duration: </strong> <?php
                                                    //echo ($total_working_time != '') ? CalculateWorkedTime($attendance['WorkedHours']) : "";
                                                    echo CalculateWorkedTime($attendance['WorkedHours']);
                                                    ?>
                                                <?php } ?><br />
                                                <?php
                                                if ($this->session->userdata('id') == '189' || $this->session->userdata('id') == '321') {
                                                    /* echo '<pre>'; print_r($attendance); echo '</pre>'; */
                                                    if ($attendance['LastActivityDate']) {
                                                        ?><strong><br>Last Activity:</strong> <?php
                                                        //Setting Up TimeZones
                                                        if (isset($_COOKIE['clientTimeZone'])) {
                                                            $timezone = $_COOKIE['clientTimeZone'];
                                                        } else {
                                                            $timezone = 'UP5';
                                                        }
                                                        $FromTimeZone = 'GMT';
                                                        $ToTimeZone = $timezone;
                                                        //echo '<br>'.date('h:i a', strtotime($attendance['LastActivityDate'])).'<br>';
                                                        echo '<span class="timedisplay">' . ConvertTimeZone(date('h:i a', strtotime($attendance['LastActivityDate'])), $FromTimeZone, $ToTimeZone) . '</span>';
                                                    }
                                                    if ($attendance['ComputerName']) {
                                                        ?>
                                                        <strong><br>System Name:</strong> 
                                                        <?php
                                                        echo '<span>' . $attendance['ComputerName'] . '</span>';
                                                    }
                                                    if ($attendance['IPAddress']) {
                                                        ?>
                                                        <strong><br>IP Address:</strong> 
                                                        <?php
                                                        echo '<span class="timedisplay">' . $attendance['IPAddress'] . '</span>';
                                                    }
                                                    if ($attendance['LESVersion']) {
                                                        ?>
                                                        <strong><br>LES Version:</strong> 
                                                        <?php
                                                        echo '<span class="timedisplay">' . $attendance['LESVersion'] . '</span>';
                                                    }
                                                    ?>
                                                    <br/>
                                                    <strong>Attendance ID:</strong> <?php
                                                    echo $attendance['ID'] . '<br>';
                                                }
                                                if ($this->session->userdata('id') == '2' || $this->session->userdata('id') == '3') {
                                                    if ($attendance['ComputerName']) {
                                                        ?>
                                                        <strong><br>System Name:</strong> 
                                                        <?php
                                                        echo '<span>' . $attendance['ComputerName'] . '</span>';
                                                    }
                                                    if ($attendance['IPAddress']) {
                                                        ?>
                                                        <strong><br>IP Address:</strong> 
                                                        <?php
                                                        echo '<span class="timedisplay">' . $attendance['IPAddress'] . '</span>' . '<br>';
                                                    }
                                                }

                                                if ($attendance['InTime']) {
                                                    $breaktime = 0;
                                                    foreach ($attendance['BreakTime'] as $key => $breakData) {
                                                        if ((($breakData['BreakType'] == 'Meeting' || $breakData['BreakType'] == 'meeting') && $breakData['breakapprovalrequest']['ApprovalStatus'] != 'y') ||
                                                                (($breakData['BreakType'] == 'Discussion' || $breakData['BreakType'] == 'discussion') && $breakData['breakapprovalrequest']['ApprovalStatus'] != 'y') ||
                                                                (($breakData['BreakType'] == 'Training' || $breakData['BreakType'] == 'training') && $breakData['breakapprovalrequest']['ApprovalStatus'] != 'y') ||
                                                                (($breakData['BreakType'] == 'Interview' || $breakData['BreakType'] == 'interview') && $breakData['breakapprovalrequest']['ApprovalStatus'] != 'y') ||
                                                                (($breakData['BreakType'] == 'coachingsession' || $breakData['BreakType'] == 'coachingSession') && $breakData['breakapprovalrequest']['ApprovalStatus'] != 'y') ||
                                                                ($breakData['BreakType'] == 'Inactivity') || ($breakData['BreakType'] == 'inactivity') || ($breakData['BreakType'] == 'Lunch/Dinner') ||
                                                                ($breakData['BreakType'] == 'lunchDinner') || ($breakData['BreakType'] == 'lunchordinner') || ($breakData['BreakType'] == 'Tea') ||
                                                                ($breakData['BreakType'] == 'tea') || ($breakData['BreakType'] == 'Prayer') || ($breakData['BreakType'] == 'prayer') ||
                                                                ($breakData['BreakType'] == 'Other') || ($breakData['BreakType'] == 'other') || ($breakData['BreakType'] == 'Hibernate/Sleep') ||
                                                                ($breakData['BreakType'] == 'hibernateSleep') || ($breakData['BreakType'] == 'Crash') || ($breakData['BreakType'] == 'crash') ||
                                                                ($breakData['BreakType'] == 'Network crash') || ($breakData['BreakType'] == 'networkCrash') || ($breakData['BreakType'] == 'web') ||
                                                                ($breakData['BreakType'] == 'employee') || ($breakData['BreakType'] == 'Manual Logout') || ($breakData['BreakType'] == 'manualLogout') ||
                                                                ($breakData['BreakType'] == 'ManualLogout') || ($breakData['BreakType'] == 'System Changed') || ($breakData['BreakType'] == 'systemChanged') ||
                                                                ($breakData['BreakType'] == ' SystemChanged') || ($breakData['BreakType'] == 'ShutDown') || ($breakData['BreakType'] == 'shutDown')) {

                                                            $breaktime = $breakData['total_break_time'] + $breaktime;

                                                            //echo '<br>total_break_time '.$breakData['total_break_time'];
                                                        }
                                                    }

                                                    $calculated_worked_hours = $attendance['WorkedHours'] + $allocated_break_time - $breaktime;

                                                    if ($attendance['WorkingDay'] == 0 && $attendance['InTime'] != 'Off') {
                                                        $total_extra_duration = $total_extra_duration + $allocated_break_time - $breaktime;
                                                    }

                                                    if ($attendance['Holiday']['HolidayDate']) {
                                                        echo "<br><span style='color: green;'><span style='font-weight: bold;'>Holiday  </span><br>" . $attendance['Holiday']['HolidayTitle'] . '</span><br />';
                                                        $total_holidays++;
                                                        $holidays_days .= $attendance['Holiday']['HolidayDate'] . ' (' . date('l', strtotime($attendance['Holiday']['HolidayDate'])) . ')&#013;';
                                                    }
                                                    //if ($this->session->userdata('id') == '321') { 
                                                    if ((($attendance['ActualSRInTime']) || ($attendance['ActualSROutTime'])) && ($attendance['EmpLoggedIn'] == '1')) {
                                                        if (strtotime($attendance['InTime']) >= strtotime($current_time)) {
                                                            $calculated_worked_hours = 0;
                                                        }
                                                    }
                                                    //}

                                                    $total_calculated_worked_duration = $calculated_worked_hours + $total_calculated_worked_duration;
                                                    if ($this->session->userdata('id') == '321') {
                                                        //echo '<pre>'; print_r($attendance); echo '</pre>';
                                                    }

                                                    if ($attendance['compensation_days']['ID'] && ($attendance['EmployeeID'] != '1' && $attendance['EmployeeID'] != '2' && $attendance['EmployeeID'] != '3')) {

                                                        echo '<br /><strong>Calculated Worked Duration: </strong>';
                                                        if ($calculated_worked_hours < 0) {
                                                            echo CalculateWorkedTime(0);
                                                        } else {
                                                            echo CalculateWorkedTime($calculated_worked_hours);
                                                        }

                                                        $count_eid_compensate_days++;

                                                        if ($attendance['compensation_days']['PerDayRate'] == 'f') {
                                                            $per_day_rate = 1;
                                                        } else if ($attendance['compensation_days']['PerDayRate'] == 'h') {
                                                            $per_day_rate = 2;
                                                        }

                                                        if ($attendance['compensation_days']['CompensateType'] == 1) {
                                                            echo '<hr>';
                                                            echo "<span style='color: green;'><span style='font-weight: bold;'>Holiday Compensation:  </span><br>" . CalculateWorkedTime($attendance['WorkedHours'] / $per_day_rate) . '</span><br>';
                                                            echo '<hr>';
                                                            echo '<strong>Total Duration: </strong>';
                                                        }
                                                        if ($attendance['compensation_days']['CompensateType'] == 2) {
                                                            if ($count_eid_compensate_days == 1) {
                                                                echo '<hr>';
                                                                echo "<span style='color: green;'><span style='font-weight: bold;'>Holiday Compensation:  </span><br>" . CalculateWorkedTime($attendance['WorkedHours'] / $per_day_rate) . '</span><br>';
                                                                echo '<hr>';
                                                                echo '<strong>Total Duration: </strong>';
                                                            }
                                                        }
                                                        if ($attendance['compensation_days']['CompensateType'] == 3) {
                                                            if ($count_eid_compensate_days >= 1) {
                                                                echo '<hr>';
                                                                echo "<span style='color: green;'><span style='font-weight: bold;'>Holiday Compensation:  </span><br>" . CalculateWorkedTime($attendance['WorkedHours'] / $per_day_rate) . '</span><br>';
                                                                echo '<hr>';
                                                                echo '<strong>Total Duration: </strong>';
                                                            }
                                                        }
                                                    } else {
                                                        ?>
                                                        <br /><strong>Calculated Worked Duration: </strong>
                                                        <?php
                                                    }

                                                    if ($attendance['Holiday']['HolidayDate'] && $attendance['WorkingDay'] != 0) {
                                                        $ShiftHr = 'Holiday Duration (' . CalculateWorkedTime($attendance['ShiftsHours']) . ') + ';
                                                    } else {
                                                        $ShiftHr = '';
                                                    }
                                                    if (($attendance['compensation_days']['ID']) && $attendance['EmployeeID'] != '1' && $attendance['EmployeeID'] != '2' && $attendance['EmployeeID'] != '3') {
                                                        $total_compensatedays++;
                                                        $compensatedays_days .= $attendance['CompensateDate'] . ' (' . $attendance['CompensateTitle'] . ') - ';


                                                        if ($attendance['compensation_days']['PerDayRate'] == 'f') {
                                                            $per_day_rate = 1;
                                                        } else if ($attendance['compensation_days']['PerDayRate'] == 'h') {
                                                            $per_day_rate = 2;
                                                        }

                                                        if ($attendance['compensation_days']['CompensateType'] == 1) {
                                                            $total_compensatedays_duration = ($attendance['WorkedHours'] / $per_day_rate) + $total_compensatedays_duration;
                                                            $CompensateDay = $attendance['CompensateTitle'] . ' (' . CalculateWorkedTime($attendance['WorkedHours'] / $per_day_rate) . ') + ';
                                                            $totalduration = $calculated_worked_hours + ($attendance['WorkedHours'] / $per_day_rate);
                                                            if ($totalduration < 0) {
                                                                $totalduration = 0;
                                                            }
                                                            echo CalculateWorkedTime($totalduration);
                                                        }
                                                        if ($attendance['compensation_days']['CompensateType'] == 2) {
                                                            if ($count_eid_compensate_days == 1) {
                                                                $total_compensatedays_duration = ($attendance['WorkedHours'] / $per_day_rate) + $total_compensatedays_duration;
                                                                $CompensateDay = $attendance['CompensateTitle'] . ' (' . CalculateWorkedTime($attendance['WorkedHours'] / $per_day_rate) . ') + ';
                                                                $totalduration = $calculated_worked_hours + ($attendance['WorkedHours'] / $per_day_rate);
                                                                if ($totalduration < 0) {
                                                                    $totalduration = 0;
                                                                }
                                                                echo CalculateWorkedTime($totalduration);
                                                            }
                                                        }
                                                        if ($attendance['compensation_days']['CompensateType'] == 3) {
                                                            if ($count_eid_compensate_days >= 1) {
                                                                $total_compensatedays_duration = ($attendance['WorkedHours'] / $per_day_rate) + $total_compensatedays_duration;
                                                                $CompensateDay = $attendance['CompensateTitle'] . ' (' . CalculateWorkedTime($attendance['WorkedHours'] / $per_day_rate) . ') + ';
                                                                $totalduration = $calculated_worked_hours + ($attendance['WorkedHours'] / $per_day_rate);
                                                                if ($totalduration < 0) {
                                                                    $totalduration = 0;
                                                                }
                                                                echo CalculateWorkedTime($totalduration);
                                                            }
                                                        }
                                                    } else {
                                                        $CompensateDay = '';
                                                        if ($calculated_worked_hours < 0) {
                                                            echo CalculateWorkedTime(0);
                                                        } else {
                                                            echo CalculateWorkedTime($calculated_worked_hours);
                                                        }
                                                    }
                                                    if (date('Y-m-d', strtotime($attendance['Date'])) < date('Y-m-d', strtotime('2017-08-21'))) {
                                                        if (($department_history[0]['DepartmentID'] == 1 && $employee['ID'] != '1' && $employee['ID'] != '2' && $employee['ID'] != '3') || ($department_history[0]['DepartmentID'] == 15)) { //if department is operations
                                                            $allocated_breaks = 'Allowed Shift Breaks  (' . CalculateWorkedTime($allocated_break_time - $wc_allocated_break_time) . ')';
                                                            $allocated_breaks .= ' + Allowed WC Breaks  (' . CalculateWorkedTime($wc_allocated_break_time) . ')';
                                                        } else {
                                                            $allocated_breaks = 'Allowed Shift Breaks  (' . CalculateWorkedTime($allocated_break_time) . ')';
                                                        }
                                                    }

                                                    if (date('Y-m-d', strtotime($attendance['Date'])) == date('Y-m-d', strtotime(date('Y-m-d'))) && $attendance['OutTime'] == '') {
                                                        if ($attendance['ShiftsHours'] > $calculated_worked_hours) {
                                                            $remaining_hrs = $attendance['ShiftsHours'] - $calculated_worked_hours;
                                                            //Setting Up TimeZones
                                                            if (isset($_COOKIE['clientTimeZone'])) {
                                                                $timezone = $_COOKIE['clientTimeZone'];
                                                            } else {
                                                                $timezone = 'UP5';
                                                            }
                                                            $FromTimeZone = 'GMT';
                                                            $ToTimeZone = $timezone;

                                                            $current_time = date('h:i a');
                                                            $current_time = ConvertTimeZone($current_time, $FromTimeZone, $ToTimeZone);
                                                            $remaining_hrs = round($remaining_hrs / 60);
                                                            $remaining_hrs = date("h:i a", strtotime("+$remaining_hrs minutes", strtotime($current_time)));
                                                            $remaining_shift_time = '&#013;Your shift will be completed on ' . $remaining_hrs;
                                                        } else {
                                                            $remaining_shift_time = '&#013;Your shift time is over.';
                                                        }
                                                    }
                                                    ?>
                                                    <img src="<?php echo LES_BASE_URL; ?>assets/images/tooltip.png" alt="Display Picture" title="Calculated Worked Duration = <?php echo $CompensateDay; ?> <?php echo $ShiftHr; ?> Total Logged In Duration <?php echo ' (' . CalculateWorkedTime($attendance['WorkedHours']) . ')'; ?> + Allowed Breaks <?php echo ' (' . CalculateWorkedTime($allocated_break_time) . ')'; ?>  - Total Break <?php
                                                    echo ' (' . CalculateWorkedTime($breaktime) . ')';
                                                    echo $remaining_shift_time;
                                                    ?>" class="img-rounded tooltips tooltipimage">    

                                                    <?php
                                                    if ($calculated_worked_hours < 0) {
                                                        $email_type = 'attendane';
                                                        //Email($attendance['Date'], $employee['ID'], $completeName, CalculateWorkedTime($calculated_worked_hours), $email_type, $attendance['ID'], $department_history[0]['DepartmentID']);
                                                    }
                                                    if ($calculated_worked_hours > 43200) {
                                                        $email_type = 'attendane_greater';
                                                        Email($attendance['Date'], $employee['ID'], $completeName, CalculateWorkedTime($calculated_worked_hours), $email_type, $attendance['ID'], $department_history[0]['DepartmentID']);
                                                    }

                                                    /* ---------------------- Time Restriction: Shift Restriction ------------------------ */
                                                    if ($attendance['TimingRestrictions']['ShiftRestriction'] == '1' && date('Y-m-d', strtotime($attendance['Date'])) != date('Y-m-d', strtotime($attendance['Holiday']['HolidayDate']))) {
                                                        if (date('Y-m-d', strtotime($attendance['Date'])) >= date('Y-m-d', strtotime($attendance['TimingRestrictions']['ShiftRestrictionStartDate']))) {
                                                            //echo '<pre>'; print_r($attendance); echo '</pre>';   
                                                            //actual calculated_worked_hours without restriction
                                                            $actual_calculated_worked_hours = '0';
                                                            if ($attendance['ActualWorkedHours']) {
                                                                $actualbreaktime = 0;
                                                                foreach ($attendance['ActualBreakTime'] as $key => $breakData) {
                                                                    if ((($breakData['BreakType'] == 'Meeting' || $breakData['BreakType'] == 'meeting') && $breakData['breakapprovalrequest']['ApprovalStatus'] != 'y') || (($breakData['BreakType'] == 'Discussion' || $breakData['BreakType'] == 'discussion') && $breakData['breakapprovalrequest']['ApprovalStatus'] != 'y') || (($breakData['BreakType'] == 'Training' || $breakData['BreakType'] == 'training') && $breakData['breakapprovalrequest']['ApprovalStatus'] != 'y') || (($breakData['BreakType'] == 'Interview' || $breakData['BreakType'] == 'interview') && $breakData['breakapprovalrequest']['ApprovalStatus'] != 'y') || ($breakData['BreakType'] == 'Inactivity') || ($breakData['BreakType'] == 'inactivity') || ($breakData['BreakType'] == 'Lunch/Dinner') || ($breakData['BreakType'] == 'lunchDinner') || ($breakData['BreakType'] == 'lunchordinner') || ($breakData['BreakType'] == 'Tea') || ($breakData['BreakType'] == 'tea') || ($breakData['BreakType'] == 'Prayer') || ($breakData['BreakType'] == 'prayer') || ($breakData['BreakType'] == 'Other') || ($breakData['BreakType'] == 'other') || ($breakData['BreakType'] == 'Hibernate/Sleep') || ($breakData['BreakType'] == 'hibernateSleep') || ($breakData['BreakType'] == 'Crash') || ($breakData['BreakType'] == 'crash') || ($breakData['BreakType'] == 'Network crash') || ($breakData['BreakType'] == 'networkCrash') || ($breakData['BreakType'] == 'web') || ($breakData['BreakType'] == 'employee') || ($breakData['BreakType'] == 'Manual Logout') || ($breakData['BreakType'] == 'manualLogout') || ($breakData['BreakType'] == 'ManualLogout') || ($breakData['BreakType'] == 'System Changed') || ($breakData['BreakType'] == 'systemChanged') || ($breakData['BreakType'] == ' SystemChanged') || ($breakData['BreakType'] == 'ShutDown') || ($breakData['BreakType'] == 'shutDown')) {

                                                                        $actualbreaktime = $breakData['total_break_time'] + $actualbreaktime;
                                                                    }
                                                                }
                                                                $actual_calculated_worked_hours = $attendance['ActualWorkedHours'] + $allocated_break_time - $actualbreaktime;
                                                            }

                                                            if ((($attendance['ActualSRInTime']) || ($attendance['ActualSROutTime'])) && ($attendance['EmpLoggedIn'] == '1')) {
                                                                if (strtotime($attendance['InTime']) >= strtotime($current_time)) {
                                                                    if ($this->session->userdata('id') == '321') {
                                                                        echo '<br><br><strong style="color:green";>Your have logged in out of your shift duration.</strong><br>';
                                                                    }
                                                                }
                                                            }

                                                            if (($this->session->userdata('id') == $attendance['EmployeeID']) && ($calculated_worked_hours > $attendance['ShiftsHours']) && (($attendance['SRInTime'] != '') || ($attendance['SROutTime'] != ''))) {
                                                                if (date('Y-m-d', strtotime($attendance['Date'])) == date('Y-m-d', strtotime(date('Y-m-d')))) {
                                                                    echo '<br><br><strong style="color:green";>Your shift is over.</strong>';
                                                                }
                                                            }

                                                            if ($attendance['SRRequest']) {
                                                                if ($attendance['SRRequest']['ApprovalStatus'] == 'p') {
                                                                    $sr_requestedto_name = $attendance['SRRequest']['RequestedTo']['FirstName'] . ' ' . $attendance['SRRequest']['RequestedTo']['LastName'];
                                                                    if ($attendance['SRRequest']['RequestedTo']['PseudoFirstName']) {
                                                                        $sr_requestedto_name .= ' (' . $attendance['SRRequest']['RequestedTo']['PseudoFirstName'] . ' ' . $attendance['SRRequest']['RequestedTo']['PseudoLastName'] . ')';
                                                                    }
                                                                    echo "<br><br><span style='color:red;' >Shift OT request sent to " . $sr_requestedto_name . " on " . $attendance['SRRequest']['RequestedDate'] . "</span>";
                                                                    if (($this->session->userdata('role') != '4') && ($this->session->userdata('id') != $attendance['EmployeeID']) && (($attendance['SRInTime'] != '') || ($attendance['SROutTime'] != '')) && ($this->session->userdata('id') == $employee['ReportingTo'])) {
                                                                        // if lock status is n then salary is not locked
                                                                        if ($lock_status == 'n') {
                                                                            ?>
                                                                            <br /><br /><input type="button" name="approve-restriction" class="btn btn-primary btn-sm get_reports modify_break approve-restriction" data-approve-restriction="<?php echo $attendance['ID']; ?>" data-approve-type="1" value="Approve Shift OT">
                                                                            <?php
                                                                        }
                                                                    }
                                                                }
                                                                if ($attendance['SRRequest']['ApprovalStatus'] == 'y') {
                                                                    $sr_approvedby_name = $attendance['SRRequest']['UpdatedBy']['FirstName'] . ' ' . $attendance['SRRequest']['UpdatedBy']['LastName'];
                                                                    if ($attendance['SRRequest']['UpdatedBy']['PseudoFirstName']) {
                                                                        $sr_approvedby_name .= ' (' . $attendance['SRRequest']['UpdatedBy']['PseudoFirstName'] . ' ' . $attendance['SRRequest']['UpdatedBy']['PseudoLastName'] . ')';
                                                                    }
                                                                    echo "<br><br><span style='color:green;'>Shift OT approved by " . $sr_approvedby_name . " on " . $attendance['SRRequest']['UpdatedDate'] . "</span>";
                                                                }
                                                                if ($attendance['SRRequest']['ApprovalStatus'] == 'n') {
                                                                    $sr_approvedby_name = $attendance['SRRequest']['UpdatedBy']['FirstName'] . ' ' . $attendance['SRRequest']['UpdatedBy']['LastName'];
                                                                    if ($attendance['SRRequest']['UpdatedBy']['PseudoFirstName']) {
                                                                        $sr_approvedby_name .= ' (' . $attendance['SRRequest']['UpdatedBy']['PseudoFirstName'] . ' ' . $attendance['SRRequest']['UpdatedBy']['PseudoLastName'] . ')';
                                                                    }
                                                                    echo "<br><br><span >Shift OT rejected by " . $sr_approvedby_name . " on " . $attendance['SRRequest']['UpdatedDate'] . "</span>";
                                                                }
                                                            } else {
                                                                if (($this->session->userdata('id') == $attendance['EmployeeID']) && (($attendance['SRInTime'] != '') || ($attendance['SROutTime'] != '')) && ($attendance['Date'] != date('Y-m-d'))) {
                                                                    // if lock status is n then salary is not locked
                                                                    if ($lock_status == 'n') {
                                                                        // if department are Operations (Philippines & Pakistan),  QA, QC, Training, CSA
                                                                        if ($attendance['DepartmentID'] == '1' || $attendance['DepartmentID'] == '4' || $attendance['DepartmentID'] == '6' || $attendance['DepartmentID'] == '10' || $attendance['DepartmentID'] == '15' || $attendance['DepartmentID'] == '36') {
                                                                            //if last login attendance exists only then employee can apply ot request
                                                                            //if (($last_login_attendance['ID']) && (strtotime($last_login_attendance['Date']) == strtotime($attendance['Date']))) {
                                                                            $shift_ot_auto_popup = 1;
                                                                            ?>
                                                                            <br/><br/><input type="button" name="restriction-approval-request" id="shift_ot_auto_popup" class="btn btn-primary btn-sm get_reports modify_break restriction-approval-request" data-attendance-id="<?php echo $attendance['ID']; ?>" data-restriction-type="sr" value="Shift OT Approval Request">                                                                        
                                                                            <?php
                                                                            //}
                                                                        } else {
                                                                            ?>
                                                                            <br/><br/><input type="button" name="restriction-approval-request" class="btn btn-primary btn-sm get_reports modify_break restriction-approval-request" data-attendance-id="<?php echo $attendance['ID']; ?>" data-restriction-type="sr" value="Shift OT Approval Request">
                                                                            <?php
                                                                        }
                                                                    }
                                                                }
                                                            }

                                                            if ((($attendance['SRInTime'] != '') || ($attendance['SROutTime'] != '')) && ($attendance['EmpLoggedIn'] == '0')) {
                                                                ?>
                                                                <br/><input type="button" name="view-restriction-details" class="btn btn-primary btn-sm get_reports modify_break view-restriction-details" data-attendance-id="<?php echo $attendance['ID']; ?>" data-restriction-type="sr" data-calculated_worked_hours="<?php echo $calculated_worked_hours; ?>" value="View Shift OT Details">
                                                                <?php
                                                            }

                                                            // calling helper function to add ot exists for this employee in attendance table
                                                            generate_ot_exist_new($attendance['ID'], 'shiftot');
                                                            if (($this->session->userdata('role') != '4') && ($this->session->userdata('id') != $attendance['EmployeeID'])) {
                                                                if ((($attendance['ActualSRInTime']) || ($attendance['ActualSROutTime'])) && ($attendance['EmpLoggedIn'] == '0')) {
                                                                    echo '<hr>';
                                                                }
                                                                if ($this->session->userdata('id') == '321') {
                                                                    //echo '<pre>'; print_r($attendance); echo '</pre>';
                                                                    echo 'DepartmentID '.$department_history[0]['DepartmentID'];
                                                                }
                                                                if ($attendance['ActualSRInTime']) {
                                                                    echo '<strong>Actual Time In: </strong>' . $attendance['ActualSRInTime'] . '<br>';
                                                                }
                                                                if ($attendance['ActualSROutTime'] && $attendance['EmpLoggedIn'] == '0') {
                                                                    echo '<strong>Actual Time Out: </strong>' . $attendance['ActualSROutTime'] . '<br>';
                                                                }
                                                                if ((($attendance['ActualSRInTime']) || ($attendance['ActualSROutTime'])) && ($attendance['EmpLoggedIn'] == '0')) {
                                                                    echo '<br><strong>Actual Calculated Worked Duration: </strong>';
                                                                    if ($actual_calculated_worked_hours > 0) {
                                                                        echo CalculateWorkedTime($actual_calculated_worked_hours);
                                                                        if ($actual_calculated_worked_hours > 43200) {
                                                                            $email_type = 'attendane_greater';
                                                                            Email($attendance['Date'], $employee['ID'], $completeName, CalculateWorkedTime($actual_calculated_worked_hours), $email_type, $attendance['ID'], $department_history[0]['DepartmentID']);
                                                                        }
                                                                    } else {
                                                                        echo CalculateWorkedTime(0);
                                                                    }
                                                                }
                                                            }
                                                            if ((($attendance['ActualSRInTime']) || ($attendance['ActualSROutTime'])) && ($attendance['EmpLoggedIn'] == '0')) {
                                                                $shift_ot_duration = $actual_calculated_worked_hours - $calculated_worked_hours;
                                                                $total_shift_ot_duration = $total_shift_ot_duration + $shift_ot_duration;
                                                            }
                                                            if ($this->session->userdata('id') == '189' || $this->session->userdata('id') == '321') {
                                                                echo '<br>total_shift_ot_duration ' . CalculateWorkedTime($total_shift_ot_duration);
                                                            }
                                                        }
                                                    }
                                                    /* ---------------------- Time Restriction: Over Time ------------------------ */
                                                    if ($attendance['TimingRestrictions']['OTRestriction'] == '1' && date('Y-m-d', strtotime($attendance['Date'])) != date('Y-m-d', strtotime($attendance['Holiday']['HolidayDate']))) {
                                                        if (date('Y-m-d', strtotime($attendance['Date'])) >= date('Y-m-d', strtotime($attendance['TimingRestrictions']['OTRestrictionStartDate']))) {
                                                            //actual calculated_worked_hours without restriction
                                                            $actual_calculated_worked_hours = '0';
                                                            if ($attendance['ActualWorkedHours']) {
                                                                $actual_calculated_worked_hours = $attendance['ActualWorkedHours'] + $allocated_break_time - $breaktime;
                                                            }

                                                            //getting the relaxed shift time
                                                            $ot_restriction_extra_duration = $attendance['TimingRestrictions']['OTRestrictionExtraDuration'] * 60;
                                                            $relaxed_shifts_hours = $attendance['ShiftsHours'] + $ot_restriction_extra_duration;

                                                            if (($this->session->userdata('id') == $attendance['EmployeeID']) && ($actual_calculated_worked_hours > $relaxed_shifts_hours)) {
                                                                if (date('Y-m-d', strtotime($attendance['Date'])) == date('Y-m-d', strtotime(date('Y-m-d')))) {
                                                                    echo '<br><br><strong style="color:green";>Your shift time is over.</strong>';
                                                                }
                                                            }

                                                            if ($this->session->userdata('id') == '189') {
                                                                //echo '<pre>'; print_r($attendance); echo '</pre>';
                                                            }

                                                            if ($attendance['OTRequest']) {
                                                                if ($attendance['OTRequest']['ApprovalStatus'] == 'p') {
                                                                    $ot_requestedto_name = $attendance['OTRequest']['RequestedTo']['FirstName'] . ' ' . $attendance['OTRequest']['RequestedTo']['LastName'];
                                                                    if ($attendance['OTRequest']['RequestedTo']['PseudoFirstName']) {
                                                                        $ot_requestedto_name .= ' (' . $attendance['OTRequest']['RequestedTo']['PseudoFirstName'] . ' ' . $attendance['OTRequest']['RequestedTo']['PseudoLastName'] . ')';
                                                                    }
                                                                    echo "<br><br><span style='color:red;' >OT request sent to " . $ot_requestedto_name . " on " . $attendance['OTRequest']['RequestedDate'] . "</span>";
                                                                    if (($this->session->userdata('role') != '4') && ($this->session->userdata('id') != $attendance['EmployeeID']) && ($actual_calculated_worked_hours > $relaxed_shifts_hours) && ($this->session->userdata('id') == $employee['ReportingTo'])) {
                                                                        // if lock status is n then salary is not locked
                                                                        if ($lock_status == 'n') {
                                                                            ?>
                                                                            <br /><br /><input type="button" name="approve-restriction" class="btn btn-primary btn-sm get_reports modify_break approve-restriction" data-approve-restriction="<?php echo $attendance['ID']; ?>" data-approve-type="0" value="Approve Attendance OT">
                                                                            <?php
                                                                        }
                                                                    }
                                                                }
                                                                if ($attendance['OTRequest']['ApprovalStatus'] == 'y') {
                                                                    $total_approved_ot_count++;
                                                                    $ot_duration = $calculated_worked_hours - $relaxed_shifts_hours;
                                                                    $total_approved_att_ot_duration = $ot_duration + $total_approved_att_ot_duration;

                                                                    $ot_approvedby_name = $attendance['OTRequest']['UpdatedBy']['FirstName'] . ' ' . $attendance['OTRequest']['UpdatedBy']['LastName'];
                                                                    if ($attendance['OTRequest']['UpdatedBy']['PseudoFirstName']) {
                                                                        $ot_approvedby_name .= ' (' . $attendance['OTRequest']['UpdatedBy']['PseudoFirstName'] . ' ' . $attendance['OTRequest']['UpdatedBy']['PseudoLastName'] . ')';
                                                                    }
                                                                    echo "<br><br><span style='color:green;'>OT approved by " . $ot_approvedby_name . " on " . $attendance['OTRequest']['UpdatedDate'] . "</span>";
                                                                }
                                                                if ($attendance['OTRequest']['ApprovalStatus'] == 'n') {
                                                                    $ot_approvedby_name = $attendance['OTRequest']['UpdatedBy']['FirstName'] . ' ' . $attendance['OTRequest']['UpdatedBy']['LastName'];
                                                                    if ($attendance['OTRequest']['UpdatedBy']['PseudoFirstName']) {
                                                                        $ot_approvedby_name .= ' (' . $attendance['OTRequest']['UpdatedBy']['PseudoFirstName'] . ' ' . $attendance['OTRequest']['UpdatedBy']['PseudoLastName'] . ')';
                                                                    }
                                                                    echo "<br><br><span >OT rejected by " . $ot_approvedby_name . " on " . $attendance['OTRequest']['UpdatedDate'] . "</span>";
                                                                }
                                                            } else {
                                                                $relaxed_shifts_hours = $relaxed_shifts_hours + 60; // adding 1 minute to the shift hour for showing approval request button                                                            
                                                                if (($this->session->userdata('id') == $attendance['EmployeeID']) && ($actual_calculated_worked_hours >= $relaxed_shifts_hours) && ($attendance['Date'] != date('Y-m-d'))) {
                                                                    // if lock status is n then salary is not locked
                                                                    if ($lock_status == 'n') {
                                                                        // if department are Operations (Philippines & Pakistan),  QA, QC, Training, CSA
                                                                        if ($attendance['DepartmentID'] == '1' || $attendance['DepartmentID'] == '4' || $attendance['DepartmentID'] == '6' || $attendance['DepartmentID'] == '10' || $attendance['DepartmentID'] == '15' || $attendance['DepartmentID'] == '36') {
                                                                            //if last login attendance exists only then employee can apply ot request
                                                                            //if (($last_login_attendance['ID']) && (strtotime($last_login_attendance['Date']) == strtotime($attendance['Date']))) {
                                                                            ?>
                                                                            <br/><br/><input type="button" id="otrestriction_<?php echo $total_worked_days; ?>" name="restriction-approval-request" class="btn btn-primary btn-sm get_reports modify_break restriction-approval-request" data-attendance-id="<?php echo $attendance['ID']; ?>" data-restriction-type="ot" value="OT Approval Request">                                                                        
                                                                            <?php
                                                                            //}
                                                                        } else {
                                                                            ?>
                                                                            <br/><br/><input type="button" id="otrestriction_<?php echo $total_worked_days; ?>" name="restriction-approval-request" class="btn btn-primary btn-sm get_reports modify_break restriction-approval-request" data-attendance-id="<?php echo $attendance['ID']; ?>" data-restriction-type="ot" value="OT Approval Request">                                                                        
                                                                            <?php
                                                                        }
                                                                    }
                                                                }
                                                            }

                                                            if (!($attendance['ActualWorkedHours'])) {
                                                                $actual_calculated_worked_hours = $calculated_worked_hours;
                                                            }
                                                            if ($actual_calculated_worked_hours > $relaxed_shifts_hours && $attendance['EmpLoggedIn'] == '0') {
                                                                ?>
                                                                <br/><input type="button" name="view-restriction-details" class="btn btn-primary btn-sm get_reports modify_break view-restriction-details" data-attendance-id="<?php echo $attendance['ID']; ?>" data-restriction-type="ot" data-calculated_worked_hours="<?php echo $calculated_worked_hours; ?>" value="View OT Details">
                                                                <?php
                                                            }

                                                            // calling helper function to add ot exists for this employee in attendance table
                                                            generate_ot_exist_new($attendance['ID'], 'attendanceot');
                                                            if (($this->session->userdata('role') != '4') && ($this->session->userdata('id') != $attendance['EmployeeID'])) {
                                                                if ((($attendance['ActualInTime']) || ($attendance['ActualOutTime'])) && ($attendance['EmpLoggedIn'] == '0')) {
                                                                    echo '<hr>';
                                                                }
                                                                if ($attendance['ActualInTime']) {
                                                                    echo '<strong>Actual Time In: </strong>' . $attendance['ActualInTime'] . '<br>';
                                                                }
                                                                if ($attendance['ActualOutTime'] && $attendance['EmpLoggedIn'] == '0') {
                                                                    echo '<strong>Actual Time Out: </strong>' . $attendance['ActualOutTime'] . '<br>';
                                                                }
                                                                if ((($attendance['ActualInTime']) || ($attendance['ActualOutTime'])) && ($attendance['EmpLoggedIn'] == '0')) {

                                                                    echo '<br><strong>Actual Calculated Worked Duration: </strong>';
                                                                    if ($actual_calculated_worked_hours > 0) {
                                                                        echo CalculateWorkedTime($actual_calculated_worked_hours);
                                                                        if ($actual_calculated_worked_hours > 43200) {
                                                                            $email_type = 'attendane_greater';
                                                                            Email($attendance['Date'], $employee['ID'], $completeName, CalculateWorkedTime($actual_calculated_worked_hours), $email_type, $attendance['ID'], $department_history[0]['DepartmentID']);
                                                                        }
                                                                    } else {
                                                                        echo CalculateWorkedTime(0);
                                                                    }
                                                                }
                                                            }
                                                            if ((($attendance['ActualInTime']) || ($attendance['ActualOutTime'])) && ($attendance['EmpLoggedIn'] == '0')) {
                                                                $total_unapproved_ot_count++;
                                                                $att_ot_duration = $actual_calculated_worked_hours - $calculated_worked_hours;
                                                                $total_att_ot_duration = $total_att_ot_duration + $att_ot_duration;
                                                                if ($attendance['OTRequest']['ApprovalStatus'] == 'p') {
                                                                    $total_pending_ot_duration = $att_ot_duration + $total_pending_ot_duration;
                                                                    $total_pending_ot_count++;
                                                                }
                                                            }
                                                            if ($this->session->userdata('id') == '189' || $this->session->userdata('id') == '321') {
                                                                //echo '<br>actual_calculated_worked_hours '.CalculateWorkedTime($actual_calculated_worked_hours);
                                                                //echo '<br>calculated_worked_hours '.CalculateWorkedTime($calculated_worked_hours);
                                                                echo '<br>Total OT Duration: ' . CalculateWorkedTime($total_att_ot_duration);
                                                            }
                                                        }
                                                    }
                                                    /* ------------------------- Time Restriction: Over Time ------------------------ */
                                                }
                                                //	if($this->session->userdata('id') == '189' || $this->session->userdata('id') == '170' || $this->session->userdata('id') == '321' || $this->session->userdata('id') == '339'){
                                                if ($this->session->userdata('DepartmentID') == '1' || $this->session->userdata('role') == '1') {
                                                    if (($attendance['SwapApprovedBy']['ID']) && !($attendance['LeaveDate'])) {
                                                        $total_swaps++;
                                                        $swap_days .= 'Swap From: ' . $attendance['SwapFrom'] . ' (' . date('l', strtotime($attendance['SwapFrom'])) . ') - Swap With: ' . $attendance['SwapWith'] . ' (' . date('l', strtotime($attendance['SwapWith'])) . ')' . '&#013;';
                                                        $total_swaps_duration = $calculated_worked_hours + $total_swaps_duration;
                                                    }

                                                    if ($attendance['SwapApprovedBy']['ID']) {
                                                        $swapapprovedbyname = $attendance['SwapApprovedBy']['FirstName'] . ' ' . $attendance['SwapApprovedBy']['LastName'];
                                                        if ($attendance['SwapApprovedBy']['PseudoFirstName']) {
                                                            $swapapprovedbyname .= ' (' . $attendance['SwapApprovedBy']['PseudoFirstName'] . ' ' . $attendance['SwapApprovedBy']['PseudoLastName'] . ')';
                                                        }
                                                        echo "<br><br><span >Swapped from " . $attendance['SwapFrom'] . " (" . date('l', strtotime($attendance['SwapFrom'])) . ")" . " <br>approved by " . $swapapprovedbyname . " on " . $attendance['SwapApprovedDate'] . "</span>";
                                                    }

                                                    //if ($this->session->userdata('id') == '6') {
                                                    //echo '<pre>'; print_r($attendance); echo '</pre>';
                                                    if (!($attendance['SwapFrom']) && $attendance['WorkingDay'] == '0' && $this->session->userdata('DepartmentID') == '1' && ($this->session->userdata('id') == $employee['ID'])) {
                                                        // if lock status is n then salary is not locked
                                                        if ($lock_status == 'n') {
                                                            ?>
                                                            &nbsp;&nbsp;&nbsp;<input type="button" name="swap-apply" class="btn btn-primary btn-sm get_reports modify_break swap-apply" data-swap-from="<?php echo $attendance['Date']; ?>" value="Apply for Swap">
                                                            <?php
                                                        }
                                                    }
                                                    if ($attendance['SwapFrom'] && !($attendance['SwapApprovedBy']['ID'])) {
                                                        $swapappliedtoname = $attendance['SwapRequestedTo']['FirstName'] . ' ' . $attendance['SwapRequestedTo']['LastName'];
                                                        if ($attendance['SwapRequestedTo']['PseudoFirstName']) {
                                                            $swapappliedtoname .= ' (' . $attendance['SwapRequestedTo']['PseudoFirstName'] . ' ' . $attendance['SwapRequestedTo']['PseudoLastName'] . ')';
                                                        }
                                                        echo " <br><span style='color:red;' >Swap request (From : " . $attendance['SwapFrom'] . " - With : " . $attendance['SwapWith'] . ")  sent to " . $swapappliedtoname . " on " . $attendance['SwapRequestedDate'] . "</span>";
                                                    }
                                                    //}
                                                }

                                                // code to reject approved leave in case employee logged in on his approved leave day 2018-03-05 starts
                                                if ($attendance['LeaveDate']) {
                                                    if ($attendance['ApprovalStatus'] == 'y') {
                                                        echo "<br /><br /><span style='color:red;' >" . $completeName . ' has logged in on his approved ' . $attendance['LeaveType']['Title'] . " day " . "</span>";
                                                        if (($this->session->userdata('role') == '1' || $attendance['RequestedTo']['ID'] == $this->session->userdata('id')) && ($attendance['EmployeeID'] != $this->session->userdata('id'))) {
                                                            // if lock status is n then salary is not locked
                                                            if ($lock_status == 'n') {
                                                                ?>
                                                                <br /><input type="button" name="update-leave" class="btn btn-primary btn-sm btn_reports modify_break update-leave" data-leave-id="<?php echo $attendance['LeaveID']; ?>" data-leave-status="n" value="Disapprove leave">
                                                                <?php
                                                            }
                                                        }
                                                        echo '<input type="button" name="view-leave-log" class="btn btn-primary btn-sm get_reports modify_break view-leave-log" data-leave-id="' . $attendance['LeaveID'] . '" value="View Leave Log">';
                                                    } else if ($attendance['ApprovalStatus'] == 'n') {
                                                        $approvedbyname = $attendance['LeaveUpdatedBy']['FirstName'] . ' ' . $attendance['LeaveUpdatedBy']['LastName'];
                                                        if ($attendance['LeaveUpdatedBy']['PseudoFirstName']) {
                                                            $approvedbyname .= ' (' . $attendance['LeaveUpdatedBy']['PseudoFirstName'] . ' ' . $attendance['LeaveUpdatedBy']['PseudoLastName'] . ')';
                                                        }
                                                        echo "<br /><br /><span style='color:red;'>" . $attendance['LeaveType']['Title'] . " disapproved by " . $approvedbyname . " on " . $attendance['UpdatedDate'] . "</span>";
                                                        echo '<input type="button" name="view-leave-log" class="btn btn-primary btn-sm get_reports modify_break view-leave-log" data-leave-id="' . $attendance['LeaveID'] . '" value="View Leave Log">';
                                                    }
                                                }
                                                // code to reject approved leave in case employee logged in on his approved leave day 2018-03-05 ends

                                                /**/
                                                if (($attendance['InTime'] != 'Off') && ($this->session->userdata('role') == '4')) {
                                                    echo '<hr />';
                                                    if ($attendance["EOD"] == "") {
                                                        $color = 'style="color:red"';
                                                        if (($this->session->userdata('id') == $attendance['EmployeeID'] && date('Y-m-d', strtotime($attendance['Date'])) != date('Y-m-d')) || ($this->session->userdata('id') == $attendance['EmployeeID'] && ($attendance['DepartmentID'] == '1' || $attendance['DepartmentID'] == '13' || $attendance['DepartmentID'] == '15' || $attendance['DepartmentID'] == '19' || $attendance['DepartmentID'] == '21' || $attendance['DepartmentID'] == '22' || $attendance['DepartmentID'] == '24'))) {
                                                            // if lock status is n then salary is not locked
                                                            if ($lock_status == 'n') {
                                                                ?>
                                                                <input type="button" name="updateeod" class="btn btn-primary btn-sm get_reports modify_break updateeod" data-attendance-id = "<?php echo $attendance['ID']; ?>" data-eod-status = "0" value="Add EOD"/>
                                                                <?php
                                                            }
                                                        }
                                                    } else {
                                                        echo $color = '';
                                                        echo "<strong>EOD: </strong>" . nl2br($attendance['EOD']);
                                                        if ($attendance['EODAddedDate'] != '') {
                                                            echo ' (Added On ' . $attendance['EODAddedDate'] . ')';
                                                        }
                                                        if ($this->session->userdata('id') == $attendance['EmployeeID']) {
                                                            ?>
                            <!--<input type="button" name="updateeod" class="btn btn-primary btn-sm get_reports modify_break updateeod" data-attendance-id = "<?php echo $attendance['ID']; ?>" data-eod-status = "1" value="Modify EOD"/>-->
                                                            <?php
                                                        }
                                                    }
                                                }
                                                if (($attendance['CompanyID'] == '3' || $attendance['DepartmentID'] == '1') && $this->session->userdata('role') == '4') {
                                                    if ($attendance['ChatRecord']['ID']) {
                                                        echo '<hr />';
                                                        $chat_incentive = 0;
                                                        $no_of_chat_incentive = 0;
                                                        $chat_required_incentive = 0;
                                                        $total_chats = $attendance['ChatRecord']['TotalChats'] + $total_chats;
                                                        $total_billable_chats = $attendance['ChatRecord']['TotalBillableChats'] + $total_billable_chats;

                                                        //helper function to calculate chat incentive
                                                        $data_incentive = calculate_chat_incentive($attendance['ShiftsHours'], $attendance['ChatRecord']['TotalBillableChats']);
                                                        $no_of_chat_incentive = $data_incentive['no_of_chat_incentive'];
                                                        $chat_incentive = $data_incentive['chat_incentive'];
                                                        $chat_required_incentive = $data_incentive['chat_required_incentive'];
                                                        $total_chat_incentive = $chat_incentive + $total_chat_incentive;
                                                        if ($this->session->userdata('id') == '189') {
                                                            //echo '<br>chat_incentive ' . $chat_incentive.'<br>';
                                                        }
                                                        echo '<strong>Total Chats: </strong>' . $attendance['ChatRecord']['TotalChats'];
                                                        echo '<strong><br>Total Billable Chats: </strong>' . $attendance['ChatRecord']['TotalBillableChats'];
                                                        echo '<hr />';
                                                        echo '<strong>Total Chats Required for incentive: </strong>' . $chat_required_incentive;
                                                        echo '<br><strong>Total Payable Chats: </strong>' . $no_of_chat_incentive;
                                                        echo '<strong><br>Chats Incentive Amount: </strong> Rs. ' . $chat_incentive;
                                                    }
                                                }
                                            } else {
                                                if ($attendance['ShiftID'] == '') {
                                                    echo '-';
                                                } else if ($attendance['WorkingDay'] == 0) {
                                                    echo 'Off Day ';
                                                    if ($attendance['Holiday']['HolidayDate']) {
                                                        echo "<span style='color: green;'><span style='font-weight: bold;'> ( Holiday )  </span><br>" . $attendance['Holiday']['HolidayTitle'] . '</span><br />';
                                                        $total_holidays++;
                                                        $holidays_days .= $attendance['Holiday']['HolidayDate'] . ' (' . date('l', strtotime($attendance['Holiday']['HolidayDate'])) . ')&#013;';
                                                    }

                                                    if ($this->session->userdata('DepartmentID') == '1' || $this->session->userdata('role') == '1') {
                                                        if ($this->session->userdata('id') == '129') {
                                                            /* echo '<pre>'; print_r($attendance); echo '</pre>'; */
                                                        }
                                                        if ($attendance['SwapApprovedBy']['ID']) {
                                                            $swapapprovedbyname = $attendance['SwapApprovedBy']['FirstName'] . ' ' . $attendance['SwapApprovedBy']['LastName'];
                                                            if ($attendance['SwapApprovedBy']['PseudoFirstName']) {
                                                                $swapapprovedbyname .= ' (' . $attendance['SwapApprovedBy']['PseudoFirstName'] . ' ' . $attendance['SwapApprovedBy']['PseudoLastName'] . ')';
                                                            }
                                                            echo "<br><br><span >Swapped with " . $attendance['SwapWith'] . " (" . date('l', strtotime($attendance['SwapWith'])) . ")" . " <br>approved by " . $swapapprovedbyname . " on " . $attendance['SwapUpdatedDate'] . "</span>";
                                                        }
                                                    }
                                                } else if ($attendance['Holiday']['HolidayDate']) {
                                                    echo "<span style='color: green;'><span style='font-weight: bold;'>Holiday  </span><br>" . $attendance['Holiday']['HolidayTitle'] . '</span><br /><br />';
                                                    $total_holidays++;
                                                    $holidays_days .= $attendance['Holiday']['HolidayDate'] . ' (' . date('l', strtotime($attendance['Holiday']['HolidayDate'])) . ')&#013;';
                                                } else {
                                                    echo "<span style='font-weight: bold; color:red;' >Absent </span>";
                                                    if ($this->session->userdata('id') == '189') {
                                                        /* echo '<pre>'; print_r($attendance); echo '</pre>'; */
                                                    }

                                                    if ($attendance['ApprovalStatus'] == 'y') {
                                                        if ($attendance['LeaveType']['Title'] == 'Sick Leave') {
                                                            $approved_sick_leave_counter++;
                                                        }
                                                        if ($attendance['LeaveType']['Title'] == 'Floater') {
                                                            $approved_floater_counter++;
                                                        }
                                                        $total_leaves++;
                                                        $leave_days .= $attendance['LeaveDate'] . ' (' . date('l', strtotime($attendance['LeaveDate'])) . ') - ' . $attendance['LeaveType']['Title'] . '&#013;';
                                                        $total_leaves_duration = $attendance['ShiftsHours'] + $total_leaves_duration;
                                                    } else if ($attendance['LeaveDate'] && $attendance['ApprovalStatus'] == 'p') {
                                                        if ($attendance['LeaveType']['Title'] == 'Sick Leave') {
                                                            $pending_sick_leave_counter++;
                                                        }
                                                        if ($attendance['LeaveType']['Title'] == 'Floater') {
                                                            $pending_floater_counter++;
                                                        }
                                                        $total_pending_leaves++;
                                                        $pending_leave_days .= $attendance['LeaveDate'] . ' (' . date('l', strtotime($attendance['LeaveDate'])) . ')&#013;';
                                                        $total_pending_leaves_duration = $attendance['ShiftsHours'] + $total_pending_leaves_duration;
                                                    } else if ($attendance['LeaveDate'] && $attendance['ApprovalStatus'] == 'n') {

                                                        $total_unapproved_leaves++;
                                                    }
                                                    if ($attendance['LeaveDate']) {
                                                        $update_leave = '<br /><br />';
                                                        if (($this->session->userdata('role') == '1' || $attendance['RequestedTo']['ID'] == $this->session->userdata('id')) && ($attendance['EmployeeID'] != $this->session->userdata('id'))) {
                                                            if ($attendance['ApprovalStatus'] == 'p') {
                                                                // if lock status is n then salary is not locked
                                                                if ($lock_status == 'n') {
                                                                    $update_leave .= '<input type="button" name="update-leave" class="btn btn-primary btn-sm btn_reports modify_break update-leave" data-leave-id="' . $attendance['LeaveID'] . '" data-leave-status="y" value="Approve leave">';
                                                                    $update_leave .= ' <input type="button" name="update-leave" class="btn btn-primary btn-sm btn_reports modify_break update-leave" data-leave-id="' . $attendance['LeaveID'] . '" data-leave-status="n" value="Reject leave">';
                                                                }
                                                            }
                                                        }
                                                        $view_leave_log = '<input type="button" name="view-leave-log" class="btn btn-primary btn-sm get_reports modify_break view-leave-log" data-leave-id="' . $attendance['LeaveID'] . '" value="View Leave Log">';
                                                        $appliedtoname = $attendance['RequestedTo']['FirstName'] . ' ' . $attendance['RequestedTo']['LastName'];
                                                        if ($attendance['RequestedTo']['PseudoFirstName']) {
                                                            $appliedtoname .= ' (' . $attendance['RequestedTo']['PseudoFirstName'] . ' ' . $attendance['RequestedTo']['PseudoLastName'] . ')';
                                                        }
                                                        if ($attendance['LeaveUpdatedBy']['ID']) {
                                                            $approvedbyname = $attendance['LeaveUpdatedBy']['FirstName'] . ' ' . $attendance['LeaveUpdatedBy']['LastName'];
                                                            if ($attendance['LeaveUpdatedBy']['PseudoFirstName']) {
                                                                $approvedbyname .= ' (' . $attendance['LeaveUpdatedBy']['PseudoFirstName'] . ' ' . $attendance['LeaveUpdatedBy']['PseudoLastName'] . ')';
                                                            }
                                                            if ($attendance['ApprovalStatus'] == 'y') {
                                                                echo " <br><span >" . $attendance['LeaveType']['Title'] . " approved by " . $approvedbyname . " on " . $attendance['UpdatedDate'] . "</span>";
                                                                echo $update_leave . ' ' . $view_leave_log;
                                                                echo '<br /><br /><strong>Calculated Duration: </strong>' . CalculateWorkedTime($attendance['ShiftsHours']);
                                                            } else if ($attendance['ApprovalStatus'] == 'n') {
                                                                echo " <br><span >" . $attendance['LeaveType']['Title'] . " disapproved by " . $approvedbyname . " on " . $attendance['UpdatedDate'] . "</span>";
                                                                echo $update_leave . ' ' . $view_leave_log;
                                                            }
                                                        } else {
                                                            echo " <br><span style='color:red;' >" . $attendance['LeaveType']['Title'] . " request sent to " . $appliedtoname . " on " . $attendance['RequestedDate'] . "</span>";
                                                            echo $update_leave . ' ' . $view_leave_log;
                                                        }
                                                    }

                                                    /**/
                                                    //	if($this->session->userdata('id') == '189' || $this->session->userdata('id') == '170' || $this->session->userdata('id') == '321' || $this->session->userdata('id') == '339'){
                                                    if ($this->session->userdata('DepartmentID') == '1' || $this->session->userdata('role') == '1') {
                                                        if ($this->session->userdata('id') == '129') {
                                                            /* echo '<pre>'; print_r($attendance); echo '</pre>'; */
                                                        }

                                                        if ($attendance['SwapFrom']) {
                                                            $total_pending_swaps++;
                                                            $pending_swaps_days .= 'Swap From: ' . $attendance['SwapFrom'] . ' (' . date('l', strtotime($attendance['SwapFrom'])) . ') - Swap With: ' . $attendance['SwapWith'] . ' (' . date('l', strtotime($attendance['SwapWith'])) . ')' . '&#013;';
                                                            $total_pending_swaps_duration = $attendance['ShiftsHours'] + $total_pending_swaps_duration;

                                                            $swapappliedtoname = $attendance['SwapRequestedTo']['FirstName'] . ' ' . $attendance['SwapRequestedTo']['LastName'];
                                                            if ($attendance['SwapRequestedTo']['PseudoFirstName']) {
                                                                $swapappliedtoname .= ' (' . $attendance['SwapRequestedTo']['PseudoFirstName'] . ' ' . $attendance['SwapRequestedTo']['PseudoLastName'] . ')';
                                                            }
                                                            echo " <br><span style='color:red;' >Swap request (From : " . $attendance['SwapFrom'] . " - With : " . $attendance['SwapWith'] . ")  sent to " . $swapappliedtoname . " on " . $attendance['swaprequesteddate'] . "</span>";
                                                        }
                                                    }
                                                    //	}

                                                    /**/

                                                    if ($this->session->userdata('id') == $employee['ID'] || $this->session->userdata('id') == $employee['ReportingTo']) {
                                                        if ($attendance['LeaveDate']) {
                                                            /* $appliedtoname = $attendance['RequestedTo']['FirstName'].' '.$attendance['RequestedTo']['LastName'];
                                                              if($attendance['RequestedTo']['PseudoFirstName']){
                                                              $appliedtoname .= $attendance['RequestedTo']['PseudoFirstName'].' '.$attendance['RequestedTo']['PseudoLastName'];
                                                              }
                                                              if($attendance['LeaveUpdatedBy']['ID']){
                                                              echo " <span >(leave approved)</span>";

                                                              }else{
                                                              echo " <br><span style='color:red;' >Leave request sent to ".$appliedtoname." on ".$attendance['RequestedDate']."</span>";
                                                              } */
                                                        } else {
                                                            /* Getting the salary month start date to display apply for leaves button */
                                                            $TodayDate = date('Y-m-d');
                                                            $PreviousDate = date('Y-m-d', strtotime('-1 day', strtotime($TodayDate)));
                                                            if (date('Y-m-d', strtotime($TodayDate)) > date('Y-m-d', strtotime(date('Y-m-20')))) {
                                                                $sdate = date('Y-m-d', strtotime((date('Y-m-21'))));
                                                            } else {
                                                                $sdate = date('Y-m-d', strtotime('-1 month', strtotime(date('Y-m-21'))));
                                                            }
                                                            if ($this->session->userdata('id') == $employee['ReportingTo']) {
                                                                if (strtotime($attendance['Date']) == strtotime($TodayDate) || strtotime($attendance['Date']) == strtotime($PreviousDate)) {
                                                                    if (!($attendance['SwapFrom'])) {
                                                                        if ($lock_status == 'n') {
                                                                            ?>
                                                                            &nbsp;&nbsp;&nbsp;<input type="button" name="leave-apply" class="btn btn-primary btn-sm get_reports modify_break leave-apply" data-employee-id-show="<?php echo $attendance['EmployeeID']; ?>" data-shift-id-show="<?php echo $attendance['ShiftID']; ?>" data-leave-startdate="<?php echo $attendance['Date']; ?>" data-leave-year="<?php echo $LeaveYear; ?>" value="Apply for Leave">
                                                                            <?php
                                                                        }
                                                                    }
                                                                }
                                                            } else {
                                                                if (!($attendance['SwapFrom'])) {
                                                                    if ($lock_status == 'n') {
                                                                        ?>
                                                                        &nbsp;&nbsp;&nbsp;<input type="button" name="leave-apply" class="btn btn-primary btn-sm get_reports modify_break leave-apply" data-employee-id-show="<?php echo $attendance['EmployeeID']; ?>" data-shift-id-show="<?php echo $attendance['ShiftID']; ?>" data-leave-startdate="<?php echo $attendance['Date']; ?>" data-leave-year="<?php echo $LeaveYear; ?>" value="Apply for Leave">
                                                                        <?php
                                                                    }
                                                                }
                                                            }

                                                            // if($this->session->userdata('id') == '189' || $this->session->userdata('id') == '170' || $this->session->userdata('id') == '321' || $this->session->userdata('id') == '339'){ 
                                                            if ($this->session->userdata('DepartmentID') == '1' && ($this->session->userdata('id') == $employee['ID'])) {
                                                                if (!($attendance['SwapFrom'])) {
                                                                    ?>
                                                                    &nbsp;&nbsp;&nbsp;<input type="button" name="swap-apply" class="btn btn-primary btn-sm get_reports modify_break swap-apply" data-swap-from="<?php echo $attendance['Date']; ?>" value="Apply for Swap">
                                                                    <?php
                                                                }
                                                            }
                                                            //	}
                                                        }
                                                    }
                                                }
                                            }
                                            ?></td>
                                            <?php if (!($this->session->userdata('role') == '4')) {
                                                ?>
                                                <td>
                                                    <?php
                                                    if ((trim($attendance['InTime']) != 'Off' && ($attendance['OutTime'] != '' || $attendance['OutTime'] != NULL )) && ($this->session->userdata('id') != $attendance['EmployeeID']) && ($this->session->userdata('id') == $employee['ReportingTo'] || $this->session->userdata('role') == '1' || $this->session->userdata('id') == '2' || $this->session->userdata('id') == '3' || $this->session->userdata('id') == '179' || $this->session->userdata('id') == '180')) {
                                                        // if lock status is n then salary is not locked
                                                        if ($lock_status == 'n') {
                                                            // if department are Operations (Philippines & Pakistan)
                                                            if ($attendance['DepartmentID'] == '1' || $attendance['DepartmentID'] == '15') {
                                                                //only super admin or managers can add or modify attendance
                                                                if ($this->session->userdata('role') == '1' || $this->session->userdata('id') == '2' || $this->session->userdata('id') == '3') {
                                                                    ?>
                                                                    <input type="button" name="attendance-modify" class="btn btn-primary btn-sm get_reports attendance-modify" data-attendance-id-modify="<?php echo $attendance['ID']; ?>" data-employee-id-show="<?php echo $attendance['EmployeeID']; ?>" data-shift-id-show="<?php echo $attendance['ShiftID']; ?>" data-shift-date-show="<?php echo $attendance['Date']; ?>" data-shift-odate-show="<?php
                                                                    if ($attendance['ODate']) {
                                                                        echo $attendance['ODate'];
                                                                    } else {
                                                                        echo '0';
                                                                    }
                                                                    ?>" data-shift-day-show="<?php echo $attendance['Day']; ?>" data-shift-workingday-show="<?php echo $attendance['WorkingDay']; ?>" data-shift-timein-show="<?php echo str_replace(" ", "_", $attendance['InTime']); ?>" data-shift-timeout-show="<?php echo str_replace(" ", "_", $attendance['OutTime']); ?>" data-break-allocation-show="<?php echo $allocated_break_time; ?>" value="Modify Attendance"> 
                                                                           <?php
                                                                       }
                                                                   } else {
                                                                       ?>
                                                                <input type="button" name="attendance-modify" class="btn btn-primary btn-sm get_reports attendance-modify" data-attendance-id-modify="<?php echo $attendance['ID']; ?>" data-employee-id-show="<?php echo $attendance['EmployeeID']; ?>" data-shift-id-show="<?php echo $attendance['ShiftID']; ?>" data-shift-date-show="<?php echo $attendance['Date']; ?>" data-shift-odate-show="<?php
                                                                if ($attendance['ODate']) {
                                                                    echo $attendance['ODate'];
                                                                } else {
                                                                    echo '0';
                                                                }
                                                                ?>" data-shift-day-show="<?php echo $attendance['Day']; ?>" data-shift-workingday-show="<?php echo $attendance['WorkingDay']; ?>" data-shift-timein-show="<?php echo str_replace(" ", "_", $attendance['InTime']); ?>" data-shift-timeout-show="<?php echo str_replace(" ", "_", $attendance['OutTime']); ?>" data-break-allocation-show="<?php echo $allocated_break_time; ?>" value="Modify Attendance"> 
                                                                       <?php
                                                                   }
                                                               }
                                                           } else if (trim($attendance['InTime']) != 'Off' && ($attendance['OutTime'] == '' || $attendance['OutTime'] == NULL) && $attendance['EmployeeID'] != $this->session->userdata('id') && ($this->session->userdata('id') == $employee['ReportingTo'] || $this->session->userdata('role') == '1' || $this->session->userdata('id') == '2' || $this->session->userdata('id') == '3' || $this->session->userdata('id') == '179' || $this->session->userdata('id') == '180')) {
                                                               // if lock status is n then salary is not locked
                                                               if ($lock_status == 'n') {
                                                                   // if department are Operations (Philippines & Pakistan)
                                                                   if ($attendance['DepartmentID'] == '1' || $attendance['DepartmentID'] == '15') {
                                                                       //only super admin or managers can add or modify attendance
                                                                       if ($this->session->userdata('role') == '1' || $this->session->userdata('id') == '2' || $this->session->userdata('id') == '3') {
                                                                           ?>								 
                                                                    <input type="button" name="attendance-modify" class="btn btn-primary btn-sm get_reports attendance-modify" data-attendance-id-modify = "<?php echo $attendance['ID']; ?>" data-employee-id-show="<?php echo $attendance['EmployeeID']; ?>" data-shift-id-show="<?php echo $attendance['ShiftID']; ?>" data-shift-date-show="<?php echo $attendance['Date']; ?>" data-shift-odate-show="<?php
                                                                    if ($attendance['ODate']) {
                                                                        echo $attendance['ODate'];
                                                                    } else {
                                                                        echo '0';
                                                                    }
                                                                    ?>" data-shift-day-show="<?php echo $attendance['Day']; ?>" data-shift-workingday-show="<?php echo $attendance['WorkingDay']; ?>" data-shift-timein-show="<?php echo str_replace(" ", "_", $attendance['InTime']); ?>" data-shift-timeout-show="<?php echo 'Off'; ?>" data-break-allocation-show="<?php echo $allocated_break_time; ?>" value="Modify Attendance" />                   
                                                                           <?php
                                                                       }
                                                                   } else {
                                                                       ?>								 
                                                                <input type="button" name="attendance-modify" class="btn btn-primary btn-sm get_reports attendance-modify" data-attendance-id-modify = "<?php echo $attendance['ID']; ?>" data-employee-id-show="<?php echo $attendance['EmployeeID']; ?>" data-shift-id-show="<?php echo $attendance['ShiftID']; ?>" data-shift-date-show="<?php echo $attendance['Date']; ?>" data-shift-odate-show="<?php
                                                                if ($attendance['ODate']) {
                                                                    echo $attendance['ODate'];
                                                                } else {
                                                                    echo '0';
                                                                }
                                                                ?>" data-shift-day-show="<?php echo $attendance['Day']; ?>" data-shift-workingday-show="<?php echo $attendance['WorkingDay']; ?>" data-shift-timein-show="<?php echo str_replace(" ", "_", $attendance['InTime']); ?>" data-shift-timeout-show="<?php echo 'Off'; ?>" data-break-allocation-show="<?php echo $allocated_break_time; ?>" value="Modify Attendance" />                   
                                                                       <?php
                                                                   }
                                                               }
                                                           } else {
                                                               if (($attendance['ShiftID'] == '')) {
                                                                   // if lock status is n then salary is not locked
                                                                   if ($lock_status == 'n') {
                                                                       ?> 
                                                                <input type="button" name="attendance-modify" class="btn btn-primary btn-sm get_reports attendance-modify disabled" data-attendance-id-modify = "0" data-employee-id-show="<?php echo $attendance['EmployeeID']; ?>" data-shift-id-show="<?php echo $attendance['ShiftID']; ?>" data-shift-date-show="<?php echo $attendance['Date']; ?>" data-shift-odate-show="<?php
                                                                if ($attendance['ODate']) {
                                                                    echo $attendance['ODate'];
                                                                } else {
                                                                    echo '0';
                                                                }
                                                                ?>" data-shift-day-show="<?php echo $attendance['Day']; ?>" data-shift-workingday-show="<?php echo $attendance['WorkingDay']; ?>" data-shift-timein-show="<?php echo str_replace(" ", "_", $attendance['InTime']); ?>" data-shift-timeout-show="<?php echo str_replace(" ", "_", $attendance['OutTime']); ?>" value="Modify Attendance" disabled="disabled"/>

                                                                <?php
                                                            }
                                                        } else {
                                                            if ((strtotime($attendance['Date']) >= strtotime($department_history[$count]['StartDate'])) && ($this->session->userdata('id') != $attendance['EmployeeID'])) {
                                                                //if ($attendance['CompanyID'] != '1') {
                                                                if ((date('Y-m-d', strtotime($attendance['Date'])) != date('Y-m-d')) && !($attendance['SwapRequestedTo']['FirstName']) && !($attendance['RequestedTo']['ID']) && ($this->session->userdata('id') == $employee['ReportingTo'] || $this->session->userdata('role') == '1' || $this->session->userdata('id') == '2' || $this->session->userdata('id') == '3' || $this->session->userdata('id') == '179' || $this->session->userdata('id') == '180')) { //&& ($attendance['WorkingDay'] != 0)
                                                                    // if lock status is n then salary is not locked
                                                                    if ($lock_status == 'n') {
                                                                        // if department are Operations (Philippines & Pakistan)
                                                                        if ($attendance['DepartmentID'] == '1' || $attendance['DepartmentID'] == '15') {
                                                                            //only super admin or managers can add or modify attendance
                                                                            if ($this->session->userdata('role') == '1' || $this->session->userdata('id') == '2' || $this->session->userdata('id') == '3') {
                                                                                ?>
                                                                                <input type="button" name="attendance-modify" class="btn btn-primary btn-sm get_reports attendance-modify" data-attendance-id-modify = "0" data-employee-id-show="<?php echo $attendance['EmployeeID']; ?>" data-shift-id-show="<?php echo $attendance['ShiftID']; ?>" data-shift-date-show="<?php echo $attendance['Date']; ?>" data-shift-odate-show="<?php
                                                                                if ($attendance['ODate']) {
                                                                                    echo $attendance['ODate'];
                                                                                } else {
                                                                                    echo '0';
                                                                                }
                                                                                ?>" data-shift-day-show="<?php echo $attendance['Day']; ?>" data-shift-workingday-show="<?php echo $attendance['WorkingDay']; ?>" data-shift-timein-show="<?php echo str_replace(" ", "_", $attendance['InTime']); ?>" data-shift-timeout-show="<?php echo str_replace(" ", "_", $attendance['OutTime']); ?>" data-break-allocation-show="<?php echo $allocated_break_time; ?>" value="Add Attendance"/>
                                                                                       <?php
                                                                                   }
                                                                               } else {
                                                                                   ?>
                                                                            <input type="button" name="attendance-modify" class="btn btn-primary btn-sm get_reports attendance-modify" data-attendance-id-modify = "0" data-employee-id-show="<?php echo $attendance['EmployeeID']; ?>" data-shift-id-show="<?php echo $attendance['ShiftID']; ?>" data-shift-date-show="<?php echo $attendance['Date']; ?>" data-shift-odate-show="<?php
                                                                            if ($attendance['ODate']) {
                                                                                echo $attendance['ODate'];
                                                                            } else {
                                                                                echo '0';
                                                                            }
                                                                            ?>" data-shift-day-show="<?php echo $attendance['Day']; ?>" data-shift-workingday-show="<?php echo $attendance['WorkingDay']; ?>" data-shift-timein-show="<?php echo str_replace(" ", "_", $attendance['InTime']); ?>" data-shift-timeout-show="<?php echo str_replace(" ", "_", $attendance['OutTime']); ?>" data-break-allocation-show="<?php echo $allocated_break_time; ?>" value="Add Attendance"/>
                                                                                   <?php
                                                                               }
                                                                           }
                                                                       }
                                                                       //   }
                                                                   } else {
                                                                       if ($this->session->userdata('role') == '1') {
                                                                           // if lock status is n then salary is not locked
                                                                           if ($lock_status == 'n') {
                                                                               ?> 
                                                                        <input type="button" name="attendance-modify" class="btn btn-primary btn-sm get_reports attendance-modify disabled tooltips" value="Modify Attendance" disabled="disabled" title="Your attendance can be modified by another super admin." style="pointer-events: auto; cursor: pointer; font-weight: normal;"/>
                                                                        <?php
                                                                    }
                                                                } else {
                                                                    // if lock status is n then salary is not locked
                                                                    if ($lock_status == 'n') {
                                                                        ?>
                                                                        <input type="button" name="attendance-modify" class="btn btn-primary btn-sm get_reports attendance-modify disabled" data-attendance-id-modify = "0" data-employee-id-show="<?php echo $attendance['EmployeeID']; ?>" data-shift-id-show="<?php echo $attendance['ShiftID']; ?>" data-shift-date-show="<?php echo $attendance['Date']; ?>" data-shift-odate-show="<?php
                                                                        if ($attendance['ODate']) {
                                                                            echo $attendance['ODate'];
                                                                        } else {
                                                                            echo '0';
                                                                        }
                                                                        ?>" data-shift-day-show="<?php echo $attendance['Day']; ?>" data-shift-workingday-show="<?php echo $attendance['WorkingDay']; ?>" data-shift-timein-show="<?php echo str_replace(" ", "_", $attendance['InTime']); ?>" data-shift-timeout-show="<?php echo str_replace(" ", "_", $attendance['OutTime']); ?>" value="Modify Attendance" disabled="disabled"/>
                                                                               <?php
                                                                           }
                                                                       }
                                                                   }
                                                               }
                                                           }

                                                           if ($attendance['InTime'] != 'Off') {
                                                               if ($attendance['ODate']) {
                                                                   $odate = $attendance['ODate'];
                                                               } else {
                                                                   $odate = '0';
                                                               }
                                                               ?>
                                                        <div class="clear"></div><input type="button" name="viewattendance" class="btn btn-primary btn-sm get_reports view-attendance" data-attendance-id-modify="<?php echo $attendance['ID']; ?>" data-employee-id-show="<?php echo $attendance['EmployeeID']; ?>" data-shift-id-show="<?php echo $attendance['ShiftID']; ?>" data-shift-date-show="<?php echo $attendance['Date']; ?>" data-shift-odate-show="<?php echo $odate; ?>" data-shift-day-show="<?php echo $attendance['Day']; ?>" data-attendance-timein-show="<?php echo date("h:ia", strtotime($attendance['InTime'])); ?>" data-attendance-timeout-show="<?php echo ($attendance['OutTime'] != '') ? date("h:ia", strtotime($attendance['OutTime'])) : '-'; ?>" data-attendance-modifieddate="<?php echo date("Y-m-dH:i:s", strtotime($latestModifiedDate)); ?>" data-attendance-approve-show="0"  value="View Log">
                                                        <?php
                                                        if ($this->session->userdata('id') == '189' || $this->session->userdata('id') == '321') {
                                                            if ($this->session->userdata('show_usage') == '1') {
                                                                if ($attendance['UsageHistory'] != '') {
                                                                    ?>
                                                                    <br /><input type="button" name="viewusagehistory" class="btn btn-primary btn-sm get_reports view-usagehistory" data-employee-id-show="<?php echo $attendance['EmployeeID']; ?>" data-usagehistory-date-show="<?php echo $attendance['Date']; ?>" value="View Usage History">
                                                                    <?php
                                                                }
                                                            }
                                                        }
                                                    }
                                                    if ($this->session->userdata('role') == '1' || $this->session->userdata('role') == '2') {
                                                        $added_by_style = '';
                                                        if ($attendance['AddedBy']['FirstName'] != $employee['FirstName'] && $attendance['AddedBy']['FirstName'] != '') {
                                                            $added_by_style = 'style="color:red;"';
                                                        }
                                                        if ($attendance['AddedBy']['FirstName']) {
                                                            $added_by_name = $attendance['AddedBy']['FirstName'] . ' ' . $attendance['AddedBy']['LastName'];
                                                            if ($attendance['AddedBy']['PseudoFirstName']) {
                                                                $added_by_name .= ' (' . $attendance['AddedBy']['PseudoFirstName'] . ' ' . $attendance['AddedBy']['PseudoLastName'] . ')';
                                                            }
                                                            echo "<hr><span $added_by_style><strong>Attendance Marked by: </strong><br>" . $added_by_name . '</span><br><br>';
                                                        } if ($attendance['CheckedBy']['FirstName'] && $attendance['Source'] != 'webservice') {
                                                            $approved_by_name = $attendance['CheckedBy']['FirstName'] . ' ' . $attendance['CheckedBy']['LastName'];
                                                            if ($attendance['CheckedBy']['PseudoFirstName']) {
                                                                $approved_by_name .= ' (' . $attendance['CheckedBy']['PseudoFirstName'] . ' ' . $attendance['CheckedBy']['PseudoLastName'] . ')';
                                                            }
                                                            echo "<span $added_by_style><strong $added_by_style>Attendance Approved by: </strong><br>" . $approved_by_name . '</span>';
                                                        }
                                                        if ($this->session->userdata('id') == '189') {
                                                            if ($attendance['AddedBy']['FirstName']) {
                                                                $eid = $employee['ID'];
                                                                $cdate = $attendance['Date'];
                                                                if ($attendance['ScreenShot'] == '1') {
                                                                    ?>
                                                                    <hr />
                                                                    <br /><a class="fancybox-buttons btn btn-primary btn-sm get_reports modify_break" href="<?php echo site_url("screenshot/index/$eid/$cdate"); ?>"> view Screen Shots</a>
                                                                    <?php
                                                                }
                                                            }
                                                        }
                                                    }
                                                    if (($attendance['InTime'] != 'Off')) {
                                                        if ($attendance["EOD"] == "") {
                                                            echo '-';
                                                            //if (date('Y-m-d', strtotime($attendance['Date'])) != date('Y-m-d') || ($attendance['DepartmentID'] == '1' || $attendance['DepartmentID'] == '13' || $attendance['DepartmentID'] == '15' || $attendance['DepartmentID'] == '19' || $attendance['DepartmentID'] == '21' || $attendance['DepartmentID'] == '22' || $attendance['DepartmentID'] == '24')) {
                                                            if (($this->session->userdata('id') == $attendance['EmployeeID'] && date('Y-m-d', strtotime($attendance['Date'])) != date('Y-m-d')) || ($this->session->userdata('id') == $attendance['EmployeeID'] && ($attendance['DepartmentID'] == '1' || $attendance['DepartmentID'] == '13' || $attendance['DepartmentID'] == '15' || $attendance['DepartmentID'] == '19' || $attendance['DepartmentID'] == '21' || $attendance['DepartmentID'] == '22' || $attendance['DepartmentID'] == '24'))) {
                                                                // if lock status is n then salary is not locked
                                                                if ($lock_status == 'n') {
                                                                    echo '<hr />';

                                                                    echo "<strong>EOD: </strong>";
                                                                    ?>
                                                                    <br /><input type="button" name="updateeod" class="btn btn-primary btn-sm get_reports modify_break updateeod" data-attendance-id = "<?php echo $attendance['ID']; ?>" data-eod-status = "0" value="Add EOD"/>
                                                                    <?php
                                                                }
                                                            }
                                                        } else {
                                                            echo '<hr />';

                                                            echo "<strong>EOD: </strong>";
                                                            echo nl2br($attendance['EOD']);
                                                            if ($attendance['EODAddedDate'] != '') {
                                                                echo ' (Added On ' . $attendance['EODAddedDate'] . ')';
                                                            }
                                                            if ($this->session->userdata('id') != $attendance['EmployeeID']) {
                                                                // if lock status is n then salary is not locked
                                                                if ($lock_status == 'n') {
                                                                    ?>
                                                                    <input type="button" name="updateeod" class="btn btn-primary btn-sm get_reports modify_break updateeod" data-attendance-id = "<?php echo $attendance['ID']; ?>" data-eod-status = "1" value="Modify EOD"/>
                                                                    <?php
                                                                }
                                                            }
                                                        }
                                                    }
                                                    if (($attendance['CompanyID'] == '3' || $attendance['DepartmentID'] == '1') && $this->session->userdata('role') != '4') {
                                                        if ($attendance['ChatRecord']['ID']) {
                                                            echo '<hr />';
                                                            $chat_incentive = 0;
                                                            $no_of_chat_incentive = 0;
                                                            $chat_required_incentive = 0;
                                                            $total_chats = $attendance['ChatRecord']['TotalChats'] + $total_chats;
                                                            $total_billable_chats = $attendance['ChatRecord']['TotalBillableChats'] + $total_billable_chats;

                                                            //helper function to calculate chat incentive
                                                            $data_incentive = calculate_chat_incentive($attendance['ShiftsHours'], $attendance['ChatRecord']['TotalBillableChats']);
                                                            $no_of_chat_incentive = $data_incentive['no_of_chat_incentive'];
                                                            $chat_incentive = $data_incentive['chat_incentive'];
                                                            $chat_required_incentive = $data_incentive['chat_required_incentive'];
                                                            $total_chat_incentive = $chat_incentive + $total_chat_incentive;
                                                            if ($this->session->userdata('id') == '189') {
                                                                //echo '<br>chat_incentive ' . $chat_incentive.'<br>';
                                                            }
                                                            echo '<strong>Total Chats: </strong>' . $attendance['ChatRecord']['TotalChats'];
                                                            echo '<strong><br>Total Billable Chats: </strong>' . $attendance['ChatRecord']['TotalBillableChats'];
                                                            echo '<hr />';
                                                            echo '<strong>Total Chats Required for incentive: </strong>' . $chat_required_incentive;
                                                            echo '<br><strong>Total Payable Chats: </strong>' . $no_of_chat_incentive;
                                                            echo '<strong><br>Chats Incentive Amount: </strong> Rs. ' . $chat_incentive;
                                                        }
                                                    }
                                                    ?>
                                                </td>
                                            <?php }
                                            ?>
                                            <td>
                                                <?php
                                                if ($attendance['InTime'] != 'Off' || $attendance['OutTime'] != 'Off') {
                                                    if ($attendance['BreakTime']) {
                                                        if (($this->session->userdata('role') != '4') && ($this->session->userdata('id') != $attendance['EmployeeID']) && ($this->session->userdata('id') == $employee['ReportingTo'] || $this->session->userdata('role') == '1' || $this->session->userdata('id') == '2' || $this->session->userdata('id') == '3' || $this->session->userdata('id') == '179' || $this->session->userdata('id') == '180')) {
                                                            // if lock status is n then salary is not locked
                                                            if ($lock_status == 'n') {
                                                                // if department are Operations (Philippines & Pakistan)
                                                                if ($attendance['DepartmentID'] == '1' || $attendance['DepartmentID'] == '15') {
                                                                    //only super admin or managers can add or modify break
                                                                    if ($this->session->userdata('role') == '1' || $this->session->userdata('id') == '2' || $this->session->userdata('id') == '3') {
                                                                        ?>
                                                                        <input type="button" name="attendance-break-add" class="btn btn-primary btn-sm get_reports attendance-break-add" data-break-id-modify="<?php echo $attendance['BreakTime'][0]['ID']; ?>" data-employee-id-show="<?php echo $attendance['EmployeeID']; ?>" data-shift-id-show="<?php echo $attendance['ShiftID']; ?>" data-shift-date-show="<?php echo $attendance['Date']; ?>" data-shift-day-show="<?php echo $attendance['Day']; ?>" data-breaklog-id-modify="0" data-attendance-id="<?php echo $attendance['ID']; ?>" value="Add Break"><br />
                                                                        <?php
                                                                    }
                                                                } else {
                                                                    ?>
                                                                    <input type="button" name="attendance-break-add" class="btn btn-primary btn-sm get_reports attendance-break-add" data-break-id-modify="<?php echo $attendance['BreakTime'][0]['ID']; ?>" data-employee-id-show="<?php echo $attendance['EmployeeID']; ?>" data-shift-id-show="<?php echo $attendance['ShiftID']; ?>" data-shift-date-show="<?php echo $attendance['Date']; ?>" data-shift-day-show="<?php echo $attendance['Day']; ?>" data-breaklog-id-modify="0" data-attendance-id="<?php echo $attendance['ID']; ?>" value="Add Break"><br />
                                                                    <?php
                                                                }
                                                            }
                                                        } else {
                                                            if ($this->session->userdata('role') == '1') {
                                                                ?>
                                                                <?php /* ?><input type="button" name="attendance-break-add" class="btn btn-primary btn-sm get_reports attendance-break-add" data-break-id-modify="<?php echo $attendance['BreakTime'][0]['ID']; ?>" data-employee-id-show="<?php echo $attendance['EmployeeID']; ?>" data-shift-id-show="<?php echo $attendance['ShiftID']; ?>" data-shift-date-show="<?php echo $attendance['Date']; ?>" data-shift-day-show="<?php echo $attendance['Day']; ?>" data-breaklog-id-modify="0" data-attendance-id="<?php echo $attendance['ID']; ?>" value="Add Break"><br /><?php */ ?>
                                                            <?php } else {
                                                                ?>
                                                                <?php /* ?><input type="button" name="attendance-break-add" class="btn btn-primary btn-sm get_reports attendance-break-add disabled" data-break-id-modify ="<?php echo $attendance['BID']; ?>" data-employee-id-show="<?php echo $attendance['EmployeeID']; ?>" data-shift-id-show="<?php echo $attendance['ShiftID']; ?>" data-shift-date-show="<?php echo $attendance['Date']; ?>" data-shift-day-show="<?php echo $attendance['Day']; ?>" data-breaklog-id-modify="0" data-attendance-id="<?php echo $attendance['ID']; ?>" value="Add Break" disabled="disabled"><?php */ ?>
                                                                <?php
                                                            }
                                                        }
                                                    } else {
                                                        if (($this->session->userdata('role') != '4') && ($this->session->userdata('id') != $attendance['EmployeeID']) && ($this->session->userdata('id') == $employee['ReportingTo'] || $this->session->userdata('role') == '1' || $this->session->userdata('id') == '2' || $this->session->userdata('id') == '3' || $this->session->userdata('id') == '179' || $this->session->userdata('id') == '180')) {
                                                            // if lock status is n then salary is not locked
                                                            if ($lock_status == 'n') {
                                                                // if department are Operations (Philippines & Pakistan)
                                                                if ($attendance['DepartmentID'] == '1' || $attendance['DepartmentID'] == '15') {
                                                                    //only super admin or managers can add or modify break
                                                                    if ($this->session->userdata('role') == '1' || $this->session->userdata('id') == '2' || $this->session->userdata('id') == '3') {
                                                                        ?>
                                                                        <input type="button" name="attendance-break-add" class="btn btn-primary btn-sm get_reports attendance-break-add" data-break-id-modify="0" data-employee-id-show="<?php echo $attendance['EmployeeID']; ?>" data-shift-id-show="<?php echo $attendance['ShiftID']; ?>" data-shift-date-show="<?php echo $attendance['Date']; ?>" data-breaklog-id-modify="0" data-shift-day-show="<?php echo $attendance['Day']; ?>" data-attendance-id="<?php echo $attendance['ID']; ?>" value="Add Break"> <br />
                                                                        <?php
                                                                    }
                                                                } else {
                                                                    ?>
                                                                    <input type="button" name="attendance-break-add" class="btn btn-primary btn-sm get_reports attendance-break-add" data-break-id-modify="0" data-employee-id-show="<?php echo $attendance['EmployeeID']; ?>" data-shift-id-show="<?php echo $attendance['ShiftID']; ?>" data-shift-date-show="<?php echo $attendance['Date']; ?>" data-breaklog-id-modify="0" data-shift-day-show="<?php echo $attendance['Day']; ?>" data-attendance-id="<?php echo $attendance['ID']; ?>" value="Add Break"> <br />
                                                                    <?php
                                                                }
                                                            }
                                                        } else {
                                                            if ($this->session->userdata('role') == '1') {
                                                                ?>
                                                                <?php /* ?><input type="button" name="attendance-break-add" class="btn btn-primary btn-sm get_reports attendance-break-add" data-break-id-modify="0" data-employee-id-show="<?php echo $attendance['EmployeeID']; ?>" data-shift-id-show="<?php echo $attendance['ShiftID']; ?>" data-shift-date-show="<?php echo $attendance['Date']; ?>" data-breaklog-id-modify="0" data-shift-day-show="<?php echo $attendance['Day']; ?>" data-attendance-id="<?php echo $attendance['ID']; ?>"  value="Add Break"><br /><?php */ ?>
                                                                <?php
                                                            }
                                                        }
                                                        ?>

                                                        <?php
                                                    }
                                                    ?>
                                                    <?php if ($attendance['BreakTime']) { ?>
                                                        <strong>Total Break: </strong> 
                                                        <?php
                                                        echo CalculateWorkedTime($attendance['BreakTime']['total_break_time']); /* echo round(($attendance['BreakTime']['total_break_time'] / 60), 1); */

                                                        $total_break_time = ($attendance['BreakTime']['total_break_time']) + $total_break_time;
                                                        //echo "-+-" . CalculateWorkedTime($total_break_time). "-+-";
                                                        $total_break_time_ot = ($attendance['BreakTime']['total_break_time_ot']) + $total_break_time_ot;
                                                        ?> 
                                                        <br/> <?php
                                                        $breakcount = count($attendance['BreakTime']) - 3;
                                                        foreach ($attendance['BreakTime'] as $key => $breakData) {

                                                            if (($breakData['BreakType'] == 'WC' || $breakData['BreakType'] == 'wc') && $breakData['BreakType']) {
                                                                $total_wc_break = $breakData['total_break_time'] + $total_wc_break;
                                                            }

                                                            if (($breakData['BreakType'] == 'Meeting' || $breakData['BreakType'] == 'meeting') && $breakData['BreakType'] && $breakData['breakapprovalrequest']['ApprovalStatus'] == 'y') {
                                                                $total_break_meeting_approved = $breakData['total_break_time'] + $total_break_meeting_approved;
                                                            } else if (($breakData['BreakType'] == 'Meeting' || $breakData['BreakType'] == 'meeting') && $breakData['BreakType'] && $breakData['breakapprovalrequest']['ApprovalStatus'] != 'y') {
                                                                $total_break_meeting_unapproved = $breakData['total_break_time'] + $total_break_meeting_unapproved;
                                                            }

                                                            if (($breakData['BreakType'] == 'Discussion' || $breakData['BreakType'] == 'discussion') && $breakData['BreakType'] && $breakData['breakapprovalrequest']['ApprovalStatus'] == 'y') {
                                                                $total_break_discussion_approved = $breakData['total_break_time'] + $total_break_discussion_approved;
                                                            } else if (($breakData['BreakType'] == 'Discussion' || $breakData['BreakType'] == 'discussion') && $breakData['BreakType'] && $breakData['breakapprovalrequest']['ApprovalStatus'] != 'y') {
                                                                $total_break_discussion_unapproved = $breakData['total_break_time'] + $total_break_discussion_unapproved;
                                                            }

                                                            if (($breakData['BreakType'] == 'Training' || $breakData['BreakType'] == 'training') && $breakData['BreakType'] && $breakData['breakapprovalrequest']['ApprovalStatus'] == 'y') {
                                                                $total_break_training_approved = $breakData['total_break_time'] + $total_break_training_approved;
                                                            } else if (($breakData['BreakType'] == 'Training' || $breakData['BreakType'] == 'training') && $breakData['BreakType'] && $breakData['breakapprovalrequest']['ApprovalStatus'] != 'y') {
                                                                $total_break_training_unapproved = $breakData['total_break_time'] + $total_break_training_unapproved;
                                                            }

                                                            if (($breakData['BreakType'] == 'Interview' || $breakData['BreakType'] == 'interview') && $breakData['BreakType'] && $breakData['breakapprovalrequest']['ApprovalStatus'] == 'y') {
                                                                $total_break_interview_approved = $breakData['total_break_time'] + $total_break_interview_approved;
                                                            } else if (($breakData['BreakType'] == 'Interview' || $breakData['BreakType'] == 'interview') && $breakData['BreakType'] && $breakData['breakapprovalrequest']['ApprovalStatus'] != 'y') {
                                                                $total_break_interview_unapproved = $breakData['total_break_time'] + $total_break_interview_unapproved;
                                                            }

                                                            if (($breakData['BreakType'] == 'coachingSession' || $breakData['BreakType'] == 'coachingsession') && $breakData['BreakType'] && $breakData['breakapprovalrequest']['ApprovalStatus'] == 'y') {
                                                                $total_break_coaching_session_approved = $breakData['total_break_time'] + $total_break_coaching_session_approved;
                                                            } else if (($breakData['BreakType'] == 'coachingSession' || $breakData['BreakType'] == 'coachingsession') && $breakData['BreakType'] && $breakData['breakapprovalrequest']['ApprovalStatus'] != 'y') {
                                                                $total_break_coaching_session_unapproved = $breakData['total_break_time'] + $total_break_coaching_session_unapproved;
                                                            }

                                                            if (($breakData['BreakType'] == 'inactivity' || $breakData['BreakType'] == 'Inactivity') && $breakData['BreakType']) {
                                                                $total_break_inactivity = $breakData['total_break_time'] + $total_break_inactivity;
                                                            }

                                                            if (($breakData['BreakType'] == 'Lunch/Dinner' || $breakData['BreakType'] == 'lunchDinner' || $breakData['BreakType'] == 'lunchordinner') && $breakData['BreakType']) {
                                                                $total_break_meal = $breakData['total_break_time'] + $total_break_meal;
                                                            }

                                                            if (($breakData['BreakType'] == 'Tea' || $breakData['BreakType'] == 'tea') && $breakData['BreakType']) {
                                                                $total_break_tea = $breakData['total_break_time'] + $total_break_tea;
                                                            }

                                                            if (($breakData['BreakType'] == 'Prayer' || $breakData['BreakType'] == 'prayer') && $breakData['BreakType']) {
                                                                $total_break_prayer = $breakData['total_break_time'] + $total_break_prayer;
                                                            }

                                                            if (($breakData['BreakType'] == 'Other' || $breakData['BreakType'] == 'other') && $breakData['BreakType']) {
                                                                $total_break_other = $breakData['total_break_time'] + $total_break_other;
                                                            }

                                                            if (($breakData['BreakType'] == 'Hibernate/Sleep' || $breakData['BreakType'] == 'hibernateSleep') && $breakData['BreakType']) {
                                                                $total_break_hibernate_sleep = $breakData['total_break_time'] + $total_break_hibernate_sleep;
                                                            }

                                                            if (($breakData['BreakType'] == 'crash' || $breakData['BreakType'] == 'Crash') && $breakData['BreakType']) {
                                                                $total_break_crash = $breakData['total_break_time'] + $total_break_crash;
                                                            }

                                                            if (($breakData['BreakType'] == 'Network crash' || $breakData['BreakType'] == 'networkCrash') && $breakData['BreakType']) {
                                                                $total_break_network = $breakData['total_break_time'] + $total_break_network;
                                                            }

                                                            if ($breakData['BreakType'] == 'web' && $breakData['BreakType']) {
                                                                $total_break_web = $breakData['total_break_time'] + $total_break_web;
                                                            }

                                                            if ($breakData['BreakType'] == 'employee' && $breakData['BreakType']) {
                                                                $total_break_employee = $breakData['total_break_time'] + $total_break_employee;
                                                            }

                                                            if (($breakData['BreakType'] == 'Manual Logout' || $breakData['BreakType'] == 'manualLogout' || $breakData['BreakType'] == 'ManualLogout') && $breakData['BreakType']) {
                                                                $total_break_manual_logout = $breakData['total_break_time'] + $total_break_manual_logout;
                                                            }

                                                            if (($breakData['BreakType'] == 'System Changed' || $breakData['BreakType'] == 'systemChanged' || $breakData['BreakType'] == 'SystemChanged') && $breakData['BreakType']) {
                                                                $total_break_system_changed = $breakData['total_break_time'] + $total_break_system_changed;
                                                            }
                                                            if (($breakData['BreakType'] == 'ShutDown' || $breakData['BreakType'] == 'shutDown') && $breakData['BreakType']) {
                                                                $total_break_shut_down = $breakData['total_break_time'] + $total_break_shut_down;
                                                            }

                                                            //}System Changed
                                                            //  || $breakData['BreakType'] == 'Discussion' || $breakData['BreakType'] == 'Training' || $breakData['BreakType'] == 'Interview') && $breakData['BreakFlag'] == 'Complete'


                                                            if (is_array($breakData)) {
                                                                if (($breakData['IsActive'] != '0') && ($this->session->userdata('role') != '4') && ($this->session->userdata('id') != $attendance['EmployeeID'])) {

                                                                    if ($breakData['BreakType'] == 'Lunch/Dinner' || $breakData['BreakType'] == 'lunchDinner' || $breakData['BreakType'] == 'lunchordinner') {
                                                                        $break_type = 'Lunch/Dinner';
                                                                    } else if ($breakData['BreakType'] == 'Tea' || $breakData['BreakType'] == 'tea') {
                                                                        $break_type = $breakData['BreakType'];
                                                                    } else if ($breakData['BreakType'] == 'Hibernate/Sleep' || $breakData['BreakType'] == 'hibernateSleep') {
                                                                        $break_type = 'Hibernate/Sleep';
                                                                    } else if ($breakData['BreakType'] == 'System Changed' || $breakData['BreakType'] == 'systemChanged') {
                                                                        $break_type = 'SystemChanged';
                                                                    } else if ($breakData['BreakType'] == 'Network crash' || $breakData['BreakType'] == 'networkCrash') {
                                                                        $break_type = 'NetworkCrash';
                                                                    } else if ($breakData['BreakType'] == 'Manual Logout' || $breakData['BreakType'] == 'manualLogout' || $breakData['BreakType'] == 'ManualLogout') {
                                                                        $break_type = 'ManualLogout';
                                                                    } else if ($breakData['BreakType'] == 'System Changed' || $breakData['BreakType'] == 'systemChanged' || $breakData['BreakType'] == 'SystemChanged') {
                                                                        $break_type = 'SystemChanged';
                                                                    } else if ($breakData['BreakType'] == 'ShutDown' || $breakData['BreakType'] == 'shutDown') {
                                                                        $break_type = 'ShutDown';
                                                                    } else if ($breakData['BreakType'] == 'WC' || $breakData['BreakType'] == 'wc') {
                                                                        $break_type = 'WC';
                                                                    } else {
                                                                        $break_type = $breakData['BreakType'];
                                                                    }
                                                                    echo '<div>' . $breakData['BreakLog'] . ' (' . $break_type . ')';
                                                                    if ($breakData['IsAmended'] == '1') {
                                                                        echo '<span class = "red"> (Amended)</span>';
                                                                    }
                                                                    echo '<br>';
                                                                    if ($this->session->userdata('id') == '189' || $this->session->userdata('id') == '321') {
                                                                        echo '<div><strong>' . $breakData['GMTBreakLog'] . '</strong><br>';
                                                                    }
                                                                    if (($this->session->userdata('id') == $employee['ReportingTo'] || $this->session->userdata('role') == '1' || $this->session->userdata('id') == '2' || $this->session->userdata('id') == '3' || $this->session->userdata('id') == '179' || $this->session->userdata('id') == '180')) {
                                                                        // if lock status is n then salary is not locked
                                                                        if ($lock_status == 'n') {
                                                                            // if department are Operations (Philippines & Pakistan)
                                                                            if ($attendance['DepartmentID'] == '1' || $attendance['DepartmentID'] == '15') {
                                                                                //only super admin or managers can add or modify break
                                                                                if ($this->session->userdata('role') == '1' || $this->session->userdata('id') == '2' || $this->session->userdata('id') == '3') {
                                                                                    ?> 
                                                                                    <input type="button" name="attendance-break-add" class="btn btn-primary btn-sm get_reports modify_break attendance-break-add" data-break-id-modify="<?php echo $breakData['ID'] ?>" data-employee-id-show="<?php echo $attendance['EmployeeID']; ?>" data-shift-id-show="<?php echo $attendance['ShiftID']; ?>" data-shift-date-show="<?php echo $attendance['Date']; ?>" data-shift-day-show="<?php echo $attendance['Day']; ?>" data-breaklog-id-modify="<?php echo $breakData['ID'] ?>" data-attendance-id="<?php echo $attendance['ID']; ?>" value="Modify Break">
                                                                                    <?php
                                                                                }
                                                                            } else {
                                                                                ?> 
                                                                                <input type="button" name="attendance-break-add" class="btn btn-primary btn-sm get_reports modify_break attendance-break-add" data-break-id-modify="<?php echo $breakData['ID'] ?>" data-employee-id-show="<?php echo $attendance['EmployeeID']; ?>" data-shift-id-show="<?php echo $attendance['ShiftID']; ?>" data-shift-date-show="<?php echo $attendance['Date']; ?>" data-shift-day-show="<?php echo $attendance['Day']; ?>" data-breaklog-id-modify="<?php echo $breakData['ID'] ?>" data-attendance-id="<?php echo $attendance['ID']; ?>" value="Modify Break">
                                                                                <?php
                                                                            }
                                                                        }
                                                                    }
                                                                    ?>

                                                                    <input type="button" name="view-break-log" class="btn btn-primary btn-sm get_reports modify_break view-break-log" data-break-id-modify="<?php echo $breakData['ID'] ?>" data-employee-id-show="<?php echo $attendance['EmployeeID']; ?>" data-shift-id-show="<?php echo $attendance['ShiftID']; ?>" data-shift-date-show="<?php echo $attendance['Date']; ?>" data-shift-day-show="<?php echo $attendance['Day']; ?>" data-breaklog-id-modify="<?php echo $breakData['ID'] ?>" data-attendance-id="<?php echo $attendance['ID']; ?>" data-break-approve-show="0" value="View Log">

                                                                    <?php if ($breakData['BreakType'] == 'inactivity' && $breakData['Comments'] != '') { ?>
                                                                        <input type="button" name="view-break-comment" class="btn btn-primary btn-sm get_reports modify_break view-break-comment" data-break-id-modify="<?php echo $breakData['ID'] ?>" value="View Comments">
                                                                        <?php
                                                                    }

                                                                    if ($breakData['breakapprovalrequest']['ApprovalStatus'] == 'y' && ($breakData['BreakType'] == 'Meeting' || $breakData['BreakType'] == 'meeting' || $breakData['BreakType'] == 'Discussion' || $breakData['BreakType'] == 'discussion' || $breakData['BreakType'] == 'Training' || $breakData['BreakType'] == 'training' || $breakData['BreakType'] == 'Interview' || $breakData['BreakType'] == 'interview' || $breakData['BreakType'] == 'coachingSession' || $breakData['BreakType'] == 'coachingsession') && $breakData['BreakFlag'] == 'Complete') {
                                                                        // if lock status is n then salary is not locked
                                                                        if ($lock_status == 'n') {
                                                                            ?>
                                                                            <input type="button" name="approve-break" class="btn btn-primary btn-sm get_reports modify_break approved disabled" data-break-id-modify="<?php echo $breakData['ID'] ?>" data-employee-id-show="<?php echo $attendance['EmployeeID']; ?>" data-shift-id-show="<?php echo $attendance['ShiftID']; ?>" data-shift-date-show="<?php echo $attendance['Date']; ?>" data-shift-day-show="<?php echo $attendance['Day']; ?>" data-breaklog-id-modify="<?php echo $breakData['ID'] ?>" data-attendance-id="<?php echo $attendance['ID']; ?>" data-break-approve-show="1" value="Approved" disabled="disabled">
                                                                            <?php
                                                                        }
                                                                    } else if ((($breakData['BreakType'] == 'Meeting') || ($breakData['BreakType'] == 'meeting') || ($breakData['BreakType'] == 'Discussion') || ($breakData['BreakType'] == 'discussion') || ($breakData['BreakType'] == 'Training') || ($breakData['BreakType'] == 'training') || ($breakData['BreakType'] == 'Interview') || ($breakData['BreakType'] == 'interview') || ($breakData['BreakType'] == 'coachingSession') || ($breakData['BreakType'] == 'coachingsession')) && $breakData['BreakFlag'] == 'Complete' && $breakData['breakapprovalrequest']['ApprovalStatus'] == 'p' && ($this->session->userdata('id') == $employee['ReportingTo'])) {
                                                                        // if lock status is n then salary is not locked
                                                                        if ($lock_status == 'n') {
                                                                            ?>
                                                                            <input type="button" name="approve-break" class="btn btn-primary btn-sm get_reports modify_break approve-break" data-break-id-modify="<?php echo $breakData['ID'] ?>" data-employee-id-show="<?php echo $attendance['EmployeeID']; ?>" data-shift-id-show="<?php echo $attendance['ShiftID']; ?>" data-shift-date-show="<?php echo $attendance['Date']; ?>" data-shift-day-show="<?php echo $attendance['Day']; ?>" data-breaklog-id-modify="<?php echo $breakData['ID'] ?>" data-attendance-id="<?php echo $attendance['ID']; ?>" data-break-approve-show="1" value="Approve Break">
                                                                            <?php
                                                                        }
                                                                    }
                                                                    if ($this->session->userdata('id') == '189') {
                                                                        //echo '<pre>'; print_r($breakData); echo '</pre>'; /**/
                                                                    }

                                                                    if ($this->session->userdata('role') != '4' && $breakData['breakapprovalrequest']['RequestedTo'] == $this->session->userdata('id') && ($breakData['breakapprovalrequest']['ApprovalStatus'] == 'p')) {
                                                                        if (count($breakData['breakapprovalrequest']) > 1) {
                                                                            echo " <span >(break approval request pending since " . $breakData['breakapprovalrequest']['RequestedDate'] . ")</span>"; /* style='color: red;' */
                                                                        }
                                                                    }
                                                                    //if ($this->session->userdata('id') == '189') {
                                                                    if ($breakData['BreakType'] == 'training' && ($attendance['DepartmentID'] == '1' || $attendance['DepartmentID'] == '4' || $attendance['DepartmentID'] == '6' || $attendance['DepartmentID'] == '10' || $attendance['DepartmentID'] == '34' || $employee['ID'] == 230 || $employee['ID'] == 231 || $employee['ID'] == 255 || $employee['ID'] == 195)) {
                                                                        ?>
                                                                        <input type="button" name="training-break-info" class="btn btn-primary btn-sm get_reports modify_break training-break-info" data-break-id="<?php echo $breakData['ID'] ?>" data-department-id="<?php echo $attendance['DepartmentID']; ?>" value="View Training Info">
                                                                        <?php
                                                                    }
                                                                    //}
                                                                    ?>

                                                                    </div>
                                                                    <?php
                                                                } else {
                                                                    if ($this->session->userdata('role') != '4') {
                                                                        echo '<div><span class="breakdatadisabled">' . $breakData['BreakLog'] . ' (' . $breakData['BreakType'] . ') ';
                                                                        if ($breakData['breakapprovalrequest']['ApprovalStatus'] == 'y') {
                                                                            echo ' (Approved)';
                                                                        }
                                                                        ?><br /></span> 
                                                                    <?php
                                                                    // if lock status is n then salary is not locked
                                                                    if ($lock_status == 'n') {
                                                                        ?>
                                                                    <input type="button" name="attendance-break-add" class="btn btn-primary btn-sm get_reports modify_break attendance-break-add disabled tooltips" data-break-id-modify="<?php echo $breakData['ID'] ?>" data-employee-id-show="<?php echo $attendance['EmployeeID']; ?>" data-shift-id-show="<?php echo $attendance['ShiftID']; ?>" data-shift-date-show="<?php echo $attendance['Date']; ?>" data-shift-day-show="<?php echo $attendance['Day']; ?>" data-breaklog-id-modify="<?php echo $breakData['ID'] ?>"  data-attendance-id="<?php echo $attendance['ID']; ?>" value="Modify Break" disabled="disabled" <?php if ($this->session->userdata('role') == '1') { ?>title="Your break can be modified by another super admin." style="pointer-events: auto; cursor: pointer; font-weight: normal;" <?php } ?>>&nbsp;
                                                                <?php } ?>
                                                                <input type="button" name="view-break-log" class="btn btn-primary btn-sm get_reports modify_break view-break-log" data-break-id-modify="<?php echo $breakData['ID'] ?>" data-employee-id-show="<?php echo $attendance['EmployeeID']; ?>" data-shift-id-show="<?php echo $attendance['ShiftID']; ?>" data-shift-date-show="<?php echo $attendance['Date']; ?>" data-shift-day-show="<?php echo $attendance['Day']; ?>" data-breaklog-id-modify="<?php echo $breakData['ID'] ?>" data-attendance-id="<?php echo $attendance['ID']; ?>" value="View Log">
                                                                <?php
                                                            } else {
                                                                if (($breakData['IsActive'] != '0') && ($this->session->userdata('role') == '1')) {
                                                                    echo '<div>' . $breakData['BreakLog'] . ' (' . $breakData['BreakType'] . ')';
                                                                    if ($breakData['breakapprovalrequest']['ApprovalStatus'] == 'y') {
                                                                        echo ' (Approved)';
                                                                    }
                                                                    ?><br />
                                                                    <?php
                                                                    if (($this->session->userdata('id') == $employee['ReportingTo'] || $this->session->userdata('role') == '1' || $this->session->userdata('id') == '2' || $this->session->userdata('id') == '3' || $this->session->userdata('id') == '179' || $this->session->userdata('id') == '180')) {
                                                                        // if lock status is n then salary is not locked
                                                                        if ($lock_status == 'n') {
                                                                            // if department are Operations (Philippines & Pakistan)
                                                                            if ($attendance['DepartmentID'] == '1' || $attendance['DepartmentID'] == '15') {
                                                                                //only super admin or managers can add or modify break
                                                                                if ($this->session->userdata('role') == '1' || $this->session->userdata('id') == '2' || $this->session->userdata('id') == '3') {
                                                                                    ?>
                                                                                    <input type="button" name="attendance-break-add" class="btn btn-primary btn-sm get_reports modify_break attendance-break-add" data-break-id-modify="<?php echo $breakData['ID'] ?>" data-employee-id-show="<?php echo $attendance['EmployeeID']; ?>" data-shift-id-show="<?php echo $attendance['ShiftID']; ?>" data-shift-date-show="<?php echo $attendance['Date']; ?>" data-shift-day-show="<?php echo $attendance['Day']; ?>" data-breaklog-id-modify="<?php echo $breakData['ID'] ?>" data-attendance-id="<?php echo $attendance['ID']; ?>" value="Modify Break">
                                                                                    <?php
                                                                                }
                                                                            } else {
                                                                                ?>
                                                                                <input type="button" name="attendance-break-add" class="btn btn-primary btn-sm get_reports modify_break attendance-break-add" data-break-id-modify="<?php echo $breakData['ID'] ?>" data-employee-id-show="<?php echo $attendance['EmployeeID']; ?>" data-shift-id-show="<?php echo $attendance['ShiftID']; ?>" data-shift-date-show="<?php echo $attendance['Date']; ?>" data-shift-day-show="<?php echo $attendance['Day']; ?>" data-breaklog-id-modify="<?php echo $breakData['ID'] ?>" data-attendance-id="<?php echo $attendance['ID']; ?>" value="Modify Break">
                                                                                <?php
                                                                            }
                                                                        }
                                                                    }
                                                                    ?>
                                                                    <input type="button" name="view-break-log" class="btn btn-primary btn-sm get_reports modify_break view-break-log" data-break-id-modify="<?php echo $breakData['ID'] ?>" data-employee-id-show="<?php echo $attendance['EmployeeID']; ?>" data-shift-id-show="<?php echo $attendance['ShiftID']; ?>" data-shift-date-show="<?php echo $attendance['Date']; ?>" data-shift-day-show="<?php echo $attendance['Day']; ?>" data-breaklog-id-modify="<?php echo $breakData['ID'] ?>" data-attendance-id="<?php echo $attendance['ID']; ?>" value="View Log">


                                                                    <?php
                                                                } else if (($breakData['IsActive'] != '0')) {
                                                                    if ($breakData['breakapprovalrequest']['ApprovalStatus'] == 'y') {
                                                                        echo '<div><span class="breakdataapproved">' . $breakData['BreakLog'] . ' (' . $breakData['BreakType'] . ')';
                                                                        if ($breakData['breakapprovalrequest']['ApprovalStatus'] == 'y') {
                                                                            echo ' (Approved)';
                                                                        }
                                                                        echo '</span>';
                                                                    } else {
                                                                        echo '<div><span class="">' . $breakData['BreakLog'] . ' (' . $breakData['BreakType'] . ')';
                                                                    }
                                                                    if ((($breakData['BreakType'] == 'Meeting') || ($breakData['BreakType'] == 'meeting') || ($breakData['BreakType'] == 'Discussion') || ($breakData['BreakType'] == 'discussion') || ($breakData['BreakType'] == 'Training') || ($breakData['BreakType'] == 'training') || ($breakData['BreakType'] == 'Interview') || ($breakData['BreakType'] == 'interview') || ($breakData['BreakType'] == 'coachingSession') || ($breakData['BreakType'] == 'coachingsession'))) {
                                                                        ?>
                                                                        <input type="button" name="view-break-comment" class="btn btn-primary btn-sm get_reports modify_break view-break-comment" data-break-id-modify="<?php echo $breakData['ID'] ?>" value="View Comments">
                                                                        <?php
                                                                    }
                                                                }
                                                            }


                                                            if ((($breakData['BreakType'] == 'Meeting') || ($breakData['BreakType'] == 'meeting') || ($breakData['BreakType'] == 'Discussion') || ($breakData['BreakType'] == 'discussion') || ($breakData['BreakType'] == 'Training') || ($breakData['BreakType'] == 'training') || ($breakData['BreakType'] == 'Interview') || ($breakData['BreakType'] == 'interview') || ($breakData['BreakType'] == 'coachingSession') || ($breakData['BreakType'] == 'coachingsession')) && $breakData['BreakFlag'] == 'Complete' && $this->session->userdata('id') == $employee['ID']) {

                                                                if (count($breakData['breakapprovalrequest']) == 1) {
                                                                    // if lock status is n then salary is not locked
                                                                    if ($lock_status == 'n') {
                                                                        // if department are Operations (Philippines & Pakistan),  QA, QC, Training, CSA
                                                                        if ($attendance['DepartmentID'] == '1' || $attendance['DepartmentID'] == '4' || $attendance['DepartmentID'] == '6' || $attendance['DepartmentID'] == '10' || $attendance['DepartmentID'] == '15' || $attendance['DepartmentID'] == '36') {
                                                                            //if last login attendance exists or if it is the current day only then employee can apply ot request 
                                                                            //if (($last_login_attendance['ID']) && (strtotime($last_login_attendance['Date']) == strtotime($attendance['Date']) || strtotime($attendance['Date']) == strtotime(date('Y-m-d')))) {
                                                                            if ($break_request_auto_popup == 0) {
                                                                                $break_request_auto_popup = 1;
                                                                            }
                                                                            ?>
                                                                            <input type="button" name="break_raise_request" id="break_request_auto_popup" class="btn btn-primary btn-sm get_reports modify_break break_raise_request" data-break-id-modify="<?php echo $breakData['ID'] ?>" data-break-date-show="<?php echo $attendance['Date']; ?>" value="Request for Approval">
                                                                            <?php
                                                                            //}
                                                                        } else {
                                                                            ?>
                                                                            <input type="button" name="break_raise_request" class="btn btn-primary btn-sm get_reports modify_break break_raise_request" data-break-id-modify="<?php echo $breakData['ID'] ?>" data-break-date-show="<?php echo $attendance['Date']; ?>" value="Request for Approval">
                                                                            <?php
                                                                        }
                                                                    }
                                                                } else if ($breakData['breakapprovalrequest']['ApprovalStatus'] == 'p') {
                                                                    $requestedtoname = $breakData['breakapprovalrequest']['RequestedDetails']['FirstName'] . ' ' . $breakData['breakapprovalrequest']['RequestedDetails']['LastName'];
                                                                    if ($breakData['breakapprovalrequest']['RequestedDetails']['PseudoFirstName']) {
                                                                        $requestedtoname .= ' (' . $breakData['breakapprovalrequest']['RequestedDetails']['PseudoFirstName'] . ' ' . $breakData['breakapprovalrequest']['RequestedDetails']['PseudoLastName'] . ')';
                                                                    }
                                                                    echo " <span style='color: red;'>(approval request sent to " . $requestedtoname . " on " . $breakData['breakapprovalrequest']['RequestedDate'] . ")</span>";
                                                                }
                                                            }
                                                            if ($this->session->userdata('role') != '4' && $breakData['breakapprovalrequest']['RequestedTo'] == $this->session->userdata('id') && ($breakData['breakapprovalrequest']['ApprovalStatus'] == 'p')) {
                                                                if (count($breakData['breakapprovalrequest']) > 1) {
                                                                    echo " <span style='color: red;'>(break approval request pending since " . $breakData['breakapprovalrequest']['RequestedDate'] . ")</span>";
                                                                }
                                                            }
                                                            if ($breakData['BreakType'] == 'inactivity' && $breakData['Comments'] != '') {
                                                                ?>
                                                                <input type="button" name="view-break-comment" class="btn btn-primary btn-sm get_reports modify_break view-break-comment" data-break-id-modify="<?php echo $breakData['ID'] ?>" value="View Comments">
                                                                <?php
                                                            }
                                                            //if ($this->session->userdata('id') == '30' || $this->session->userdata('id') == '170') {
                                                            if ($breakData['BreakType'] == 'training' && ($attendance['DepartmentID'] == '1' || $attendance['DepartmentID'] == '4' || $attendance['DepartmentID'] == '6' || $attendance['DepartmentID'] == '10' || $attendance['DepartmentID'] == '34' || $employee['ID'] == 230 || $employee['ID'] == 231 || $employee['ID'] == 255 || $employee['ID'] == 195)) {
                                                                ?>
                                                                <input type="button" name="training-break-info" class="btn btn-primary btn-sm get_reports modify_break training-break-info" data-break-id="<?php echo $breakData['ID'] ?>" data-department-id="<?php echo $attendance['DepartmentID']; ?>" value="View Training Info">
                                                                <?php
                                                            }
                                                            //}
                                                            ?>
                                                            </div></div><?php
                                                        }
                                                        if ($key != $breakcount) {
                                                            echo '<hr />';
                                                        }
                                                    }
                                                }
                                            }
                                        }
                                        ?>
                                        </td>
                                        <td>
                                            <?php
                                            if ($attendance['ShiftID'] == '') {
                                                echo 'Shift Not Assigned';
                                            } else {
                                                if (strtotime($attendance['Date']) >= strtotime($department_history[$count]['StartDate'])) {
                                                    if ($attendance['WorkingDay'] == '0' || $attendance['WorkingDay'] == '') {
                                                        if ($attendance['WorkingDay'] == '0') {
                                                            $off = "Off Day";
                                                        } else if ($attendance['WorkingDay'] == '') {
                                                            foreach ($shift_offdays as $offdays) {
                                                                if ($offdays['DayName'] == $attendance['Day']) {
                                                                    $off = "Off Day";
                                                                }
                                                            }
                                                        }
                                                        if ($off == 'Off Day') {
                                                            echo $off;
                                                            $off = "";
                                                        } else {
                                                            $total_shift_time = $attendance['ShiftsHours'] + $total_shift_time;
                                                            ?>
                                                            <?php /* ?><a href="<?php echo site_url(File_BASE_URL . '/shifts/view') . '/' . $attendance['ShiftID']; ?>" target="_blank"><?php echo $attendance['Name'] ?></a><br /><?php */ ?>
                                                            <strong>Shift Start Time: </strong><?php echo '<span class="timedisplay">' . $attendance['ShiftStartTime'] . '</span>'; ?> <br/>
                                                            <strong>Shift End Time: </strong><?php echo '<span class="timedisplay">' . $attendance['ShiftEndTime'] . '</span>' ?> 
                                                            <br /><strong>Total Shift Duration: </strong> <?php echo ($attendance['ShiftsHours'] != '') ? CalculateWorkedTime($attendance['ShiftsHours']) : ""; ?>
                                                            <br /><span class="viewshift"><a href="<?php echo site_url(File_BASE_URL . '/shifts/view') . '/' . $attendance['ShiftID']; ?>" target="_blank">View Shift Detail</a></span>
                                                            <?php
                                                            if ($this->session->userdata('id') == '189' || $this->session->userdata('id') == '321') {
                                                                echo '<br>GMT Shift Start Time: ' . $attendance['ShiftGMTStartTime'];
                                                                echo '<br>GMT Shift End Time: ' . $attendance['ShiftGMTEndTime'];
                                                            }
                                                        }
                                                    } else {
                                                        $total_shift_time = $attendance['ShiftsHours'] + $total_shift_time;
                                                        ?>
                                                        <?php /* ?><a href="<?php echo site_url(File_BASE_URL . '/shifts/view') . '/' . $attendance['ShiftID']; ?>" target="_blank"><?php echo $attendance['Name'] ?></a><br /><?php */ ?>
                                                        <strong>Shift Start Time: </strong><?php echo '<span class="timedisplay">' . $attendance['ShiftStartTime'] . '</span>'; ?> <br/>
                                                        <strong>Shift End Time: </strong><?php echo '<span class="timedisplay">' . $attendance['ShiftEndTime'] . '</span>'; ?>  
                                                        <br /><strong>Total Shift Time: </strong><?php echo ($attendance['ShiftsHours'] != '') ? CalculateWorkedTime($attendance['ShiftsHours']) : ""; ?>
                                                        <br /><span class="viewshift"><a href="<?php echo site_url(File_BASE_URL . '/shifts/view') . '/' . $attendance['ShiftID']; ?>" target="_blank">View Shift Detail</a></span>

                                                        <?php
                                                        if ($this->session->userdata('id') == '189' || $this->session->userdata('id') == '321') {
                                                            echo '<br><br>GMT Shift Start Time: ' . $attendance['ShiftGMTStartTime'];
                                                            echo '<br>GMT Shift End Time: ' . $attendance['ShiftGMTEndTime'];
                                                        }
                                                    }
                                                } else {
                                                    echo 'Shift Not Assigned';
                                                }
                                            }
                                            ?></td>
                            </tr>  
                        <?php }
                        ?>

                        <?php
                        $a++;
                    }
                    ?>
                </tbody>
            </table>
        </div>
        <p id="source" style="line-height: 21px; float: left; font-weight: bold; font-size: 14px; padding-top: 27px; padding-left: 14px;">
            <?php echo $employee['FirstName']; ?>'s Total Worked Duration: <?php echo CalculateWorkedTime($total_working_time); ?> </br>
            <?php $totalcalculatedbreak = $total_break_time; // - $total_break_time_ot                                               ?>
            <span style="float: left;"> <?php echo $employee['FirstName']; ?>'s Total Break Duration: <?php echo CalculateWorkedTime($totalcalculatedbreak); ?><!--round(($total_break_time / 60), 2)--></span>
            <!--            </br></br>-->
            <?php
            /* if ($this->session->userdata('id') == '189') { */
            $total_incentive_balance = $total_working_time + $total_incentive_balance;
            if ($total_incentive_balance < 0) {
                $total_incentive_balance = trim($total_incentive_balance, '-');
                $total_incentive_balance = CalculateWorkedTime($total_incentive_balance);
                $total_incentive_balance = '-' . $total_incentive_balance;
            } else {
                $total_incentive_balance = CalculateWorkedTime($total_incentive_balance);
            }
            ?>
            <?php //echo $employee['FirstName']."'s Total Calculated Duration: ."echo $total_incentive_balance;                                         ?><!--round($total_working_time, 2)--> </br></br>

        <div id="attendance_source">			
            <table class="attendance_summary">
                <?php
                $total_productive_breaks = $total_break_meeting_approved + $total_break_discussion_approved + $total_break_interview_approved + $total_break_training_approved + $total_break_coaching_session_approved + $total_wc_break;

                $total_pending_breaks = $total_break_meeting_unapproved + $total_break_discussion_unapproved + $total_break_interview_unapproved + $total_break_training_unapproved + $total_break_coaching_session_unapproved;

                $total_non_productive_breaks = $total_break_inactivity + $total_break_meal + $total_break_tea + $total_break_prayer + $total_break_other + $total_break_hibernate_sleep + $total_break_crash + $total_break_network + $total_break_web + $total_break_employee + $total_break_manual_logout + $total_break_system_changed + $total_break_shut_down;

                $total_calculated_worked_hours = $total_working_time + $total_leaves_duration + $total_holidays_duration + $total_compensatedays_duration + $total_allocated_break_time - ($total_pending_breaks + $total_non_productive_breaks);

                $total_calculated_worked_hours = $total_ramzan_break_timing + $total_calculated_worked_hours;

                $total_leaves_encashed = 0;
                if ($this->session->userdata('id') == '189') {
                    //echo '<pre>';  print_r($leaves_encashment); echo '</pre>';
                }
                $encashment_year = date('Y', strtotime($end_month_date));
                $encashment_month = date('m', strtotime($end_month_date));
                if (date('Y-m-d', strtotime($end_month_date)) > date('Y-m-d', strtotime(date('Y-12-25')))) {
                    $encashment_year = $encashment_year + 1;
                }
                if (date('Y-m-d', strtotime($end_month_date)) > date('Y-m-d', strtotime(date('Y-m-25')))) {
                    $encashment_month = date('m', strtotime('+1 month', strtotime($end_month_date)));
                }
                foreach ($leaves_encashment as $encashment) {
                    if ($encashment['ApprovalStatus'] == 'y') {
                        if ($encashment['AdjustedYear'] == $encashment_year && $encashment['AdjustedMonth'] == $encashment_month) {
                            $encashed_hrs = $encashment['TotalEncashLeaves'] * $encashment['salary_detail']['MinShiftHoursPerDay'];
                            $total_leaves_encashed = $encashed_hrs + $total_leaves_encashed;
                        }
                    }
                }

                $total_hrs_encashed = $total_leaves_encashed * 3600;
                $overall_total_calculated_worked_hours = $total_calculated_worked_hours;
                //$total_leaves_encashed = $annual_leave_encashment + $floater_encashment + $biannual_leave_encashment;

                $average_time_in = $total_time_in / $total_worked_days;

                $total_late_time = $total_late_intime + $total_early_outtime;

                $punctuality_percentage = ($punctuality / $total_worked_days) * 100;

                if ($this->session->userdata('id') == '189') {
                    /* echo '<br>total_calculated_worked_hours ' . CalculateWorkedTime($total_calculated_worked_duration);
                      echo '<br>total_holidays_duration ' . CalculateWorkedTime($total_holidays_duration) . '<br>';
                      echo '<br>total_time_in '.$total_time_in;
                      echo '<br>total_worked_days '.$total_worked_days;
                      echo '<br>average_time_in '.$average_time_in.'<br>'; */
                    //echo '<br>overall_total_calculated_worked_hours ' . CalculateWorkedTime($overall_total_calculated_worked_hours);
                    //echo '<br>total_shift_time ' . CalculateWorkedTime($total_shift_time);
                }

                if ($overall_total_calculated_worked_hours >= $total_shift_time) {
                    $required_hours_alert = 'style="color:green;"';
                    $remarks = $overall_total_calculated_worked_hours - $total_shift_time;
                    $required_hours = 'Your Calculated Worked Duration is <strong>' . CalculateWorkedTime($remarks) . '</strong> greater than Shift Duration';
                } else {
                    $required_hours_alert = 'style="color:red;"';
                    $remarks = $total_shift_time - $overall_total_calculated_worked_hours;
                    $required_hours = 'Your Calculated Worked Duration is <strong>' . CalculateWorkedTime($remarks) . '</strong> less than Shift Duration';
                }
                $break_allowed_day = ($total_allocated_break_time - $total_allocated_friday_break_time) / $total_break_days;
                ?>
                <tbody>
                <th colspan="1" scope="row" class="column1"><strong>Total Shift Duration </strong>(<?php echo $total_shift_days; ?> Days)</th>
                <td colspan="4"><?php echo CalculateWorkedTime($total_shift_time); ?></td>
                </tr>	
                <tr >
                    <th colspan="1" scope="row" class="column1"><strong>Total Logged In Duration</strong> (<?php echo $total_worked_days; ?> Days) </th>
                    <td colspan="4"><?php echo CalculateWorkedTime($total_working_time); ?></td>
                </tr>
                <?php
                if ($department_history[0]['DepartmentID'] == '1' || $department_history[0]['DepartmentID'] == '2' || $department_history[0]['DepartmentID'] == '4' || $department_history[0]['DepartmentID'] == '8' || $department_history[0]['DepartmentID'] == '10' || $department_history[0]['CompanyID'] == '3') {
                    ?>
                    <tr >
                        <th colspan="1" scope="row" class="column1"><strong>Punctuality Bonus</strong> (<?php echo $punctuality; ?> Days) 
                            <button id="view_punctuality_details" class="btn btn-primary btn-sm btn-view-details">View Details</button>
                        </th>
                        <td colspan="4"><?php
                            $punctuality_acheived = 1;
                            $all_leaves = $total_leaves + $total_pending_leaves;
                            if ($total_absents > 0 && $all_leaves == 0) {
                                $punctuality_acheived = 0;
                            } else if ($total_absents > 0 && $all_leaves > 0) {
                                if ($approved_sick_leave_counter > 1) {
                                    $punctuality_acheived = 0;
                                }
                                if ($approved_floater_counter > 1) {
                                    $punctuality_acheived = 0;
                                }
                                if ($pending_sick_leave_counter > 0) {
                                    $punctuality_acheived = 0;
                                }
                                if ($pending_floater_counter > 0) {
                                    $punctuality_acheived = 0;
                                }
                            }
                            if ($this->session->userdata('id') == '189') {
                                //echo '<br>total_late_time '.$total_late_time;
                                //echo '<br>punctuality_acheived '.$punctuality_acheived;
                            }

                            if ($total_late_time < 10800 && $punctuality_acheived == 1) {
                                echo '<span style="color:green;">Acheived</span>';
                            } else {
                                echo '-';
                            }
                            ?></td>
                    </tr>
                <?php } ?>
                <?php if ($this->session->userdata('id') == '0' || $this->session->userdata('id') == '0') { ?>
                    <tr >
                        <th colspan="1" scope="row" class="column1"><strong>Average Time In</strong> </th>
                        <td colspan="4"><?php echo CalculateWorkedTime($average_time_in); ?></td>
                    </tr>
                    <?php
                }
                if (($department_history[0]['DepartmentID'] == '10' && $this->session->userdata('id') == '83') || ($department_history[0]['DepartmentID'] == '10' && $this->session->userdata('role') == '1') || ($department_history[0]['DepartmentID'] == '10' && $this->session->userdata('id') == '2') || ($department_history[0]['DepartmentID'] == '10' && $this->session->userdata('id') == '3') || ($department_history[0]['CompanyID'] == '3' && $this->session->userdata('id') == '2') || ($department_history[0]['CompanyID'] == '3' && $this->session->userdata('id') == '3')) {
                    ?>
                    <tr >
                        <th colspan="1" scope="row" class="column1"><strong>Dependability Report</strong> (<?php echo $total_worked_days; ?> Days) 
                            <button id="view_dependability_report" class="btn btn-primary btn-sm btn-view-details">View Details</button>
                        </th>
                        <td colspan="4"> - </td>
                    </tr>
                <?php } ?>
                <tr >
                    <?php
                    if ($employee['ID'] == '228') {
                        if ($daily_break_allowed == 'no') {
                            $break_allowed_day = 'Max. ' . CalculateWorkedTime($daily_break_allowed_time);
                        } else {
                            $break_allowed_day = CalculateWorkedTime($break_allowed_day);
                        }
                    } else {
                        $break_allowed_day = CalculateWorkedTime($break_allowed_day);
                    }
                    ?>
                    <th colspan="1" scope="row" class="column1"><strong>Allowed Breaks </strong>(<?php echo $break_allowed_day; ?> / Day)
                        <?php
                        $breaks_allowed = '';
                        if ($total_allocated_friday_break_time > 0) {
                            $breaks_allowed = 'Total Friday Prayer Break : (' . CalculateWorkedTime($total_allocated_friday_break_time) . ')&#013;';
                        }

                        if ($wc_allocated_break_count > 0) {
                            $breaks_allowed .= 'WC Break for Operations :  (' . CalculateWorkedTime('900') . ' / Day)';
                        }
                        if ($breaks_allowed != '') {
                            ?>
                            <img src="<?php echo LES_BASE_URL; ?>assets/images/tooltip.png" alt="Display Picture" title="<?php echo $breaks_allowed; ?> " class="img-rounded tooltips tooltipimage">
                        <?php }
                        ?>
                    </th>
                    <td colspan="4" ><?php echo CalculateWorkedTime($total_allocated_break_time); ?> </td>
                </tr>
                <tr >
                    <th colspan="1" scope="row" class="column1"><strong>Total Break Duration</strong>
                        <button id="view_details" class="btn btn-primary btn-sm btn-view-details">View Details</button>
                    </th>
                    <td colspan="4"><?php echo CalculateWorkedTime($total_break_time); ?></td>
                </tr>
                <tr>
                    <th colspan="1" scope="row" class="column1"><strong>Total Productive Approved Breaks </strong></th>
                    <td colspan="4"><?php echo CalculateWorkedTime($total_productive_breaks); ?></td>
                </tr>
                <tr>
                    <th colspan="1" scope="row" class="column1"><strong>Total Productive Unapproved Breaks </strong></th>
                    <td colspan="4"><?php echo CalculateWorkedTime($total_pending_breaks); ?></td>
                </tr>
                <tr>
                    <th colspan="1" scope="row" class="column1"><strong>Total Non-Productive Breaks </strong></th>
                    <td colspan="4"><?php echo CalculateWorkedTime($total_non_productive_breaks); ?></td>
                </tr>
                <?php
                //adding ramzan prayer break timing of 30 minutes
                if (strtotime($start_month_date) >= strtotime('2017-06-21') && strtotime($end_month_date) <= strtotime('2017-07-20')) {
                    if (($department_history[0]['CompanyID'] == '1' && $department_history[0]['DepartmentID'] != '33') || ($department_history[0]['DepartmentID'] == '32' && $EmployeeID != '164' && $EmployeeID != '316' && $EmployeeID != '500' && $EmployeeID != '545') || $EmployeeID == '26' || $EmployeeID == '177' || $EmployeeID == '566') {
                        $ramzan_break_tooltip = ' - Total Ramzan Prayer Break Duration (' . CalculateWorkedTime($total_ramzan_break_timing) . ')';
                        ?>
                        <tr>
                            <th colspan="1" scope="row" class="column1"><strong>Total Ramzan Prayer Break Duration </strong></th>
                            <td colspan="4"><?php echo CalculateWorkedTime($total_ramzan_break_timing); ?></td>
                        </tr>
                        <?php
                    }
                }
                ?>
                <?php if ($total_compensatedays > 0) { ?> 
                    <tr>
                        <th colspan="1" scope="row" class="column1"><strong>Total Compensate Days Duration</strong></th>
                        <td colspan="4"><?php echo CalculateWorkedTime($total_compensatedays_duration); ?></td>
                    </tr>
                <?php } ?>        
                <tr class="odd">
                    <th colspan="1" scope="row" class="column1">
                        <strong>Calculated Worked Hours</strong>
                        <img src="<?php echo LES_BASE_URL; ?>assets/images/tooltip.png" alt="Display Picture" title="Calculated Worked Hours = Total Logged In Duration <?php echo ' (' . CalculateWorkedTime($total_working_time) . ')'; ?> + Total Compensatedays Duration <?php echo ' (' . CalculateWorkedTime($total_compensatedays_duration) . ')'; ?> + Allowed Breaks <?php echo ' (' . CalculateWorkedTime($total_allocated_break_time) . ')'; ?>  - Total Non-Productive Breaks <?php echo ' (' . CalculateWorkedTime($total_non_productive_breaks) . ')'; ?> - Total Productive Unapproved Breaks <?php
                        echo ' (' . CalculateWorkedTime($total_pending_breaks) . ')';
                        echo $ramzan_break_tooltip;
                        ?>" class="img-rounded tooltips tooltipimage">
                    </th>
                    <td colspan="4"><?php echo CalculateWorkedTime($total_calculated_worked_hours); ?></td>
                </tr>
                <?php
                if (($this->session->userdata('role') < '4') && ($employee['ID'] != $this->session->userdata('id')) && ($department_history[0]['CompanyID'] == '1' || $department_history[0]['DepartmentID'] == '7' || $department_history[0]['DepartmentID'] == '11' || $department_history[0]['DepartmentID'] == '30' || $department_history[0]['DepartmentID'] == '32' || $department_history[0]['DepartmentID'] == '5' || $department_history[0]['DepartmentID'] == '9')) {
                    $grace_hours = '';
                    $grace_hours_counter = 0;

                    if ($total_shift_time > ($total_calculated_worked_hours)) {
                        $grace_hours = $total_shift_time - $total_calculated_worked_hours;
                        $leaves_counter = 0;
                        $total_approved_leaves = $myleaves['TotalApprovedLeaves'];
                        if (($total_absents > $total_leaves) || ($total_unapproved_leaves > 0)) {
                            $leaves_counter++;
                        }

                        $grace_period = '14400';
                        if ($employee['ID'] == '188' || $employee['ID'] == '409') {
                            $grace_period = '54000';
                        }

                        if ($grace_hours <= $grace_period) { //&& $leaves_counter == 0
                            $grace_hours_counter++;
                            ?>
                            <tr class="odd">
                                <th colspan="1" scope="row" class="column1">
                                    <strong>Grace Hours</strong>                                
                                </th>
                                <td colspan="4"><?php echo CalculateWorkedTime($grace_hours); ?></td>
                            </tr>                            
                            <?php
                        } else {
                            $grace_hours = 0;
                        }
                    }
                }
                if ($grace_hours_counter > 0) {
                    ?>
                    <tr class="odd">
                        <th colspan="1" scope="row" class="column1">
                            <strong>Overall Calculated Worked Hours</strong>
                            <img src="http://les1.thelivechatsoftware.com/assets/images/tooltip.png" alt="Display Picture" title="Overall Calculated Worked Hours = Calculated Worked Hours <?php echo ' (' . CalculateWorkedTime($total_calculated_worked_hours) . ')'; ?> + Grace Hours <?php echo ' (' . CalculateWorkedTime($grace_hours) . ')'; ?>" class="img-rounded tooltips tooltipimage">
                        </th>
                        <?php
                        $overall_total_calculated_worked_hours = $overall_total_calculated_worked_hours + $grace_hours;
                        ?>
                        <td colspan="4"><?php echo CalculateWorkedTime($overall_total_calculated_worked_hours); ?></td>
                    </tr>
                <?php }
                ?>

                </tbody>
                <tfoot>
                    <?php
                    if ($total_absents > 0) {
                        ?>
                        <tr class="odd">
                            <td colspan="5" class="column1" >
                                <span style="color:red;">Absent: <?php echo $total_absents; ?> Days 
                                    <?php if ($total_absents > 0) { ?><img src="<?php echo LES_BASE_URL; ?>assets/images/tooltip.png" alt="Display Picture" title="Absent:&#013;<?php echo $absent_days; ?>" class="img-rounded tooltips tooltipimage"> <?php } ?>
                                </span> 

                            </td>
                        </tr>
                    <?php } ?>

                    <?php if ($total_leaves > 0) { ?>
                        <tr class="odd">
                            <td colspan="5" class="column1" >
                                <?php if ($leave_days > 0) { ?><strong>Approved Leaves: <?php echo $total_leaves; ?> Days - (<?php echo CalculateWorkedTime($total_leaves_duration); ?> )</strong> 
                                    <img src="<?php echo LES_BASE_URL; ?>assets/images/tooltip.png" alt="Display Picture" title="Approved Days:&#013;<?php echo $leave_days . 'Included in Calculated Worked Hours'; ?>" class="img-rounded tooltips tooltipimage">
                                <?php } ?>
                            </td>
                        </tr>
                    <?php } ?>

                    <?php if ($total_pending_leaves > 0) { ?>
                        <tr class="odd">
                            <td colspan="5" class="column1" >
                                <?php if ($total_pending_leaves > 0) { ?><strong>Pending Leaves: <?php echo $total_pending_leaves; ?> Days - (<?php echo CalculateWorkedTime($total_pending_leaves_duration); ?> )</strong> 
                                    <img src="<?php echo LES_BASE_URL; ?>assets/images/tooltip.png" alt="Display Picture" title="Pending Days:&#013;<?php echo $pending_leave_days . 'Not Included in Calculated Worked Hours'; ?>" class="img-rounded tooltips tooltipimage">
                                <?php } ?>
                            </td>
                        </tr>
                    <?php } ?>

                    <?php if ($total_extra_days > 0) { ?>
                        <tr class="odd">
                            <td colspan="5" class="column1" >
                                <?php if ($total_extra_days > 0) { ?> <strong>Extra Days: <?php echo $total_extra_days; ?> (<?php echo CalculateWorkedTime($total_extra_duration); ?> )</strong> 
                                    <img src="<?php echo LES_BASE_URL; ?>assets/images/tooltip.png" alt="Display Picture" title="Extra Days:&#013;<?php echo $extra_days . 'Included in Calculated Worked Hours'; ?>" class="img-rounded tooltips tooltipimage">
                                <?php } ?>
                            </td>
                        </tr>
                    <?php } ?>

                    <?php if ($total_holidays > 0) { ?>
                        <tr class="odd">
                            <td colspan="5" class="column1" >
                                <?php if ($total_holidays > 0) { ?><strong>Holidays: <?php echo $total_holidays; ?> Days </strong> 
                                    <img src="<?php echo LES_BASE_URL; ?>assets/images/tooltip.png" alt="Display Picture" title="Holidays:&#013;<?php echo $holidays_days; ?>" class="img-rounded tooltips tooltipimage">
                                <?php } ?>
                            </td>
                        </tr>
                    <?php } ?>

                    <!---->
                    <?php
                    if ($this->session->userdata('DepartmentID') == '1' || $this->session->userdata('role') == '1') {
                        ?>
                        <?php if ($total_swaps > 0) { ?>
                            <tr class="odd">
                                <td colspan="5" class="column1" >
                                    <?php if ($total_swaps > 0) { ?><strong>Approved Swaps: <?php echo $total_swaps; ?> Days - (<?php echo CalculateWorkedTime($total_swaps_duration); ?> )</strong> 
                                        <img src="<?php echo LES_BASE_URL; ?>assets/images/tooltip.png" alt="Display Picture" title="Approved Swap Days:&#013;<?php echo $swap_days . 'Included in Calculated Worked Hours'; ?>" class="img-rounded tooltips tooltipimage">
                                    <?php } ?>
                                </td>
                            </tr>
                        <?php } ?>

                        <?php if ($total_pending_swaps > 0) { ?>
                            <tr class="odd">
                                <td colspan="5" class="column1" >
                                    <?php if ($total_pending_swaps > 0) { ?><strong>Pending Swaps: <?php echo $total_pending_swaps; ?> Days </strong> <!--- (<?php echo CalculateWorkedTime($total_pending_swaps_duration); ?> )-->
                                        <img src="<?php echo LES_BASE_URL; ?>assets/images/tooltip.png" alt="Display Picture" title="Pending Days:&#013;<?php echo $pending_swaps_days . 'Not Included in Calculated Worked Hours'; ?>" class="img-rounded tooltips tooltipimage">
                                    <?php } ?>
                                </td>
                            </tr>
                            <?php
                        }
                    }
                    ?>
                    <!---->
                    <tr class="odd">
                        <?php
                        if (($this->session->userdata('role') < '4') && ($department_history[0]['CompanyID'] == '1' || $department_history[0]['DepartmentID'] == '7' || $department_history[0]['DepartmentID'] == '11' || $department_history[0]['DepartmentID'] == '30' || $department_history[0]['DepartmentID'] == '32' || $department_history[0]['DepartmentID'] == '5' || $department_history[0]['DepartmentID'] == '9')) {
                            if ($grace_hours != '' && $grace_hours <= $grace_period) { //&& $leaves_counter == 0
                                ?>
                                <td colspan="5" class="column1" style="color:green;" ><?php echo 'Your Calculated Worked Duration is <strong>' . CalculateWorkedTime($total_shift_time - ($total_calculated_worked_hours + $grace_hours)) . '</strong> greater than Shift Duration'; ?> </td>
                            <?php } else { ?>
                                <td colspan="5" class="column1" <?php echo $required_hours_alert; ?> ><?php echo $required_hours; ?> </td>
                                <?php
                            }
                        } else {
                            ?>
                            <td colspan="5" class="column1" <?php echo $required_hours_alert; ?> ><?php echo $required_hours; ?> </td>
                        <?php } ?>

                    </tr>
                    <?php
                    if ($total_hrs_encashed > 0) {
                        $total = 0;
                        $total_encash_days = 0;
                        foreach ($leaves_encashment as $leaves) {
                            if ($leaves['ApprovalStatus'] == 'y') {
                                if ($leaves['AdjustedMonth'] == date('m')) {
                                    $total_encash_days = $leaves['TotalEncashLeaves'] + $total_encash_days;
                                }
                                $total = $leaves['TotalEncashLeaves'] + $total;
                                if ($leaves['LeaveTypeID'] == '3') {
                                    $encashment_info .= $leaves['TotalEncashLeaves'] . ' Annual Leaves on ' . $leaves['UpdatedDate'] . '&#013;';
                                }
                                if ($leaves['LeaveTypeID'] == '4') {
                                    $encashment_info .= $leaves['TotalEncashLeaves'] . ' Floaters on ' . $leaves['UpdatedDate'] . '&#013;';
                                }
                                if ($leaves['LeaveTypeID'] == '5') {
                                    $encashment_info .= $leaves['TotalEncashLeaves'] . ' Biannual Leaves on ' . $leaves['UpdatedDate'] . '&#013;';
                                }
                            }
                        }
                        $encashment_info .= 'Total Encashments = ' . $total . ' Leaves&#013;';
                        ?>
                        <tr>
                            <th colspan="1" scope="row" class="column1"><strong>Total Leave Encash Hours </strong>
                                <span>
                                    <img src="<?php echo LES_BASE_URL; ?>assets/images/tooltip.png" alt="Display Picture" title="Encashment Info:&#013;<?php echo $encashment_info; ?>" class="img-rounded tooltips tooltipimage">
                                    <a class="btn btn-primary btn-sm get_reports modify_break" href="<?php echo site_url(File_BASE_URL . "/leavesencashmentrecord/index/" . $employee['ID']); ?>" target="_blank"> View Details</a>
                                </span>
                            </th>
                            <td colspan="4"><?php echo CalculateWorkedTime($total_hrs_encashed) . ' (' . $total_encash_days . ' Days)'; ?></td>
                        </tr>
                        <?php
                    }
                    ?>
                    <tr>
                        <th colspan="1" scope="row" class="column1"><strong>Requests Status</strong></th>
                        <td colspan="4">
                            <button id="view_requests_status" class="btn btn-primary btn-sm btn-view-details">View Details</button>
                        </td>
                    </tr>
                    <?php
                    if (($department_history[0]['CompanyID'] == '3' || $department_history[0]['DepartmentID'] == '1') && $employee['ID'] != '1' && $employee['ID'] != '2' && $employee['ID'] != '3') {
                        ?>
                        <tr>
                            <th colspan="1" scope="row" class="column1"><strong>Total Chats </strong></th>
                            <td colspan="4"><?php echo '<strong>' . $total_chats . '</strong>'; ?></td>
                        </tr>
                        <tr>
                            <th colspan="1" scope="row" class="column1"><strong>Total Billable Chats </strong></th>
                            <td colspan="4"><?php echo '<strong>' . $total_billable_chats . '</strong>'; ?></td>
                        </tr>
                        <tr>
                            <th colspan="1" scope="row" class="column1"><strong>Total Chats Incentive </strong></th>
                            <td colspan="4"><?php echo '<strong> Rs. ' . $total_chat_incentive . '</strong>'; ?></td>
                        </tr>
                        <?php
                    }
                    if ($total_pending_ot_duration > 0) {
                        ?>
                        <tr>
                            <th colspan="1" scope="row" class="column1"><strong>Total Requested OT Duration (Pending)</strong></th>
                            <td colspan="4"><?php echo CalculateWorkedTime($total_pending_ot_duration); ?>
                                <span>
                                    <img src="<?php echo LES_BASE_URL; ?>assets/images/tooltip.png" alt="Display Picture" title="Total Instances : <?php echo $total_pending_ot_count; ?>" class="img-rounded tooltips tooltipimage">
                                </span>
                            </td>
                        </tr>
                        <?php
                    }
                    if ($this->session->userdata('role') == '1') {
                        if ($total_shift_ot_duration > 0) {
                            $ot_type = 'Total Unapproved Shift OT Duration';
                            $total_ot = $total_shift_ot_duration;
                        } else if ($total_att_ot_duration > 0) {
                            $ot_type = 'Total Unapproved OT Duration';
                            $total_ot = $total_att_ot_duration;
                        } else {
                            $ot_type = 'Total Unapproved OT Duration';
                        }
                        ?>
                        <tr>
                            <th colspan="1" scope="row" class="column1"><strong><?php echo $ot_type; ?></strong></th>
                            <td colspan="4"><?php echo CalculateWorkedTime($total_ot); ?>
                                <span>
                                    <img src="<?php echo LES_BASE_URL; ?>assets/images/tooltip.png" alt="Display Picture" title="Total Instances : <?php echo $total_unapproved_ot_count; ?>" class="img-rounded tooltips tooltipimage">
        <!--                                    <a class="btn btn-primary btn-sm get_reports modify_break" href="<?php echo site_url("leavesencashmentrecord/index/" . $employee['ID']); ?>" target="_blank"> View Details</a>-->
                                </span>
                            </td>
                        </tr>
                        <tr>
                            <th colspan="1" scope="row" class="column1"><strong>Total Approved OT Duration</strong></th>
                            <td colspan="4"><?php echo CalculateWorkedTime($total_approved_att_ot_duration); ?>
                                <span>
                                    <img src="<?php echo LES_BASE_URL; ?>assets/images/tooltip.png" alt="Display Picture" title="Total Instances : <?php echo $total_approved_ot_count; ?>" class="img-rounded tooltips tooltipimage">
                                </span>
                            </td>
                        </tr>
                    <?php } ?>
                </tfoot>
            </table>

            <div id="leaves_source" class="leaves_summary" style="margin-left: 2px;">
                <?php $this->load->view('templates/leaves_summary'); ?>
            </div>
            <div id="punctuality_details" class="leaves_summary" style="display:none; margin-left:5px">			
                <table >
                    <?php
                    $total_leave_time;
                    if ($total_calculated_worked_hours >= $total_shift_time) {
                        $ot_hours = CalculateWorkedTime($total_calculated_worked_hours - $total_shift_time);
                    } else {
                        $ot_hours = '-';
                    }
                    ?>
                    <tbody>
                        <tr>
                            <th class="odd" colspan="3" scope="col"><strong>Unapproved</strong></th>
                        </tr>
                        <tr>
                            <th class="odd" rowspan="2" scope="col"><strong>Punctuality Criteria</strong></th>
                            <th class="odd" rowspan="2" strong scope="strong" abbr="Home Plus"><strong>Total Duration</strong></th>
                            <th class="odd" rowspan="2" strong scope="col" abbr="Business"><strong>Total Instances</strong></th>
                        </tr>
                        <tr>
                        </tr>
                        <tr >
                            <th scope="row" class="column1 left">Late Arrival (Tardy)</th>
                            <td colspan="1"><?php echo CalculateWorkedTime($total_late_intime); ?></td>
                            <td colspan="1"><?php echo $intime_late_comer; ?></td>
                        </tr>
                        <tr >
                            <th scope="row" class="column1 left">Early Exit</th>
                            <td colspan="1"><?php echo CalculateWorkedTime($total_early_outtime); ?></td>
                            <td colspan="1"><?php echo $outtime_early_exit; ?></td>
                        </tr>
                        <tr >
                            <th class="odd column1 left" strong scope="row" abbr="Business"><strong>Total</strong></th>
                            <td colspan="1" class="column1"><?php echo CalculateWorkedTime($total_late_time); ?></td>
                            <?php
                            if ($punctuality >= 3) {
                                $color = 'style="color:red;"';
                            } else {
                                $color = '';
                            }
                            ?>
                            <td colspan="1" class="column1" <?php echo $color; ?>><?php echo '<strong>' . $punctuality . '</strong>'; ?></td>
                        </tr>
    <!--                        <tr >
                            <th scope="row" class="column1 left">Total OT</th>
                            <td colspan="1"><?php echo $ot_hours; ?></td>
                            <td colspan="1"><?php echo '-'; ?></td>
                        </tr>-->
                    </tbody>
                    <tfoot>

                    </tfoot>
                </table>
            </div>
            <div id="punctuality_approval_details" class="leaves_summary" style="display:none; margin-left:5px">	
                <table >
                    <tbody>
                        <tr>
                            <th class="odd" colspan="3" scope="col" style=" border-left: 0px;"><span style="color:#FFF"><strong>Approved</strong></span></th>
                        </tr>
                        <tr>
                            <th class="odd" colspan="3" scope="col"><strong>Approved</strong></th>
                        </tr>
                        <tr>
                            <th class="odd" rowspan="2" scope="col"><strong>Punctuality Criteria</strong></th>
                            <th class="odd" rowspan="2" strong scope="strong" abbr="Home Plus"><strong>Total Duration</strong></th>
                            <th class="odd" rowspan="2" strong scope="col" abbr="Business"><strong>Total Instances</strong></th>
    <!--                            <th class="odd" rowspan="2" strong scope="col" abbr="Business"><strong>Total Pending</strong></th>
                            <th class="odd" rowspan="2" strong scope="col" abbr="Business"><strong>Total Rejected</strong></th>-->
                        </tr>
                        <tr>
                        </tr>
                        <tr >
                            <th scope="row" class="column1 left">Late Arrival (Tardy)</th>
                            <td colspan="1"><?php echo CalculateWorkedTime($approved_late_comer_duration); ?></td>
                            <td colspan="1"><?php echo $approved_late_comer; ?></td>
    <!--                            <td colspan="1"><?php echo $pending_late_comer; ?></td>
                            <td colspan="1"><?php echo $rejected_late_comer; ?></td>-->
                        </tr>
                        <tr >
                            <th scope="row" class="column1 left">Early Exit</th>
                            <td colspan="1"><?php echo CalculateWorkedTime($approved_early_exit_duration); ?></td>
                            <td colspan="1"><?php echo $approved_early_exit; ?></td>
    <!--                            <td colspan="1"><?php echo $pending_early_exit; ?></td>
                            <td colspan="1"><?php echo $rejected_early_exit; ?></td>-->
                        </tr>
                        <tr >
                            <th class="odd column1 left" strong scope="row" abbr="Business"><strong>Total</strong></th>
                            <td colspan="1" class="column1"><?php echo CalculateWorkedTime($approved_late_comer_duration + $approved_early_exit_duration); ?></td>
                            <td colspan="1" class="column1"><?php echo $approved_late_comer + $approved_early_exit; ?></td>
    <!--                            <td colspan="1" class="column1"><?php echo $pending_late_comer + $pending_early_exit; ?></td>                            
                            <td colspan="1" class="column1"><?php echo $rejected_late_comer + $rejected_early_exit; ?></td>-->
                        </tr>
                    </tbody>
                </table>
            </div>
            <div id="dependability_report" class="leaves_summary" style="display:none; margin-left:5px">			
                <table >                    
                    <tbody>
                        <tr>
                            <th class="odd" colspan="3" scope="col"><strong>Dependability Report</strong></th>
                        </tr>
                        <tr>
                            <th class="odd" rowspan="2" scope="col"><strong>Dependability Criteria</strong></th>
                            <th class="odd" rowspan="2" strong scope="col" abbr="Business"><strong>Total Instances</strong></th>
                        </tr>
                        <tr>
                        </tr>                        
                        <tr >
                            <th scope="row" class="column1 left">Leaves (Approved)
                                <?php
                                if ($leave_days > 0) {
                                    ?>
                                    <img src="<?php echo LES_BASE_URL; ?>assets/images/tooltip.png" alt="Display Picture" title="<?php echo $leave_days; ?>" class="img-rounded tooltips tooltipimage">
                                <?php } ?>
                            </th>
                            <td colspan="1"><?php echo $total_leaves; ?></td>
                        </tr>
                        <tr >
                            <th scope="row" class="column1 left">Late For More Than 15 Mins
                                <?php
                                if ($total_late_intime_15min_days > 0) {
                                    ?>
                                    <img src="<?php echo LES_BASE_URL; ?>assets/images/tooltip.png" alt="Display Picture" title="<?php echo $total_late_intime_15min_days; ?>" class="img-rounded tooltips tooltipimage">
                                <?php } ?>
                            </th>
                            <td colspan="1"><?php echo $total_late_intime_15min; ?></td>
                        </tr>
                        <tr >
                            <th scope="row" class="column1 left">Late For More Than 30 Mins
                                <?php
                                if ($total_late_intime_30min_days > 0) {
                                    ?>
                                    <img src="<?php echo LES_BASE_URL; ?>assets/images/tooltip.png" alt="Display Picture" title="<?php echo $total_late_intime_30min_days; ?>" class="img-rounded tooltips tooltipimage">
                                <?php } ?>
                            </th>
                            <td colspan="1"><?php echo $total_late_intime_30min; ?></td>
                        </tr>
                        <tr >
                            <th scope="row" class="column1 left">Late For More Than 1 Hour
                                <?php
                                if ($total_late_intime_1hr_days > 0) {
                                    ?>
                                    <img src="<?php echo LES_BASE_URL; ?>assets/images/tooltip.png" alt="Display Picture" title="<?php echo $total_late_intime_1hr_days; ?>" class="img-rounded tooltips tooltipimage">
                                <?php } ?>
                            </th>
                            <td colspan="1"><?php echo $total_late_intime_1hr; ?></td>
                        </tr>
                        <tr >
                            <th scope="row" class="column1 left">Breakouts</th>
                            <td colspan="1"><?php echo ' - '; ?></td>
                        </tr>
                        <tr >
                            <th scope="row" class="column1 left">Early Logouts (Less Than 6 Hours In Bold)
                                <?php
                                if ($total_early_logout_days > 0 || $total_early_logout_6hrs_days > 0) {
                                    ?>
                                    <img src="<?php echo LES_BASE_URL; ?>assets/images/tooltip.png" alt="Display Picture" title="<?php echo $total_early_logout_days . '' . $total_early_logout_6hrs_days; ?>" class="img-rounded tooltips tooltipimage">
                                <?php } ?>
                            </th>
                            <td colspan="1"><?php echo $total_early_logout + $total_early_logout_6hrs; ?></td>
                        </tr>
                        <tr >
                            <th scope="row" class="column1 left">Weekend Shifts
                                <?php if ($weekend_shift_days > 0) {
                                    ?>
                                    <img src="<?php echo LES_BASE_URL; ?>assets/images/tooltip.png" alt="Display Picture" title="<?php echo $weekend_shift_days; ?>" class="img-rounded tooltips tooltipimage">
                                <?php } ?>
                            </th>
                            <td colspan="1"><?php echo $weekend_shift; ?></td>
                        </tr>
                        <tr >
                            <th scope="row" class="column1 left">Break Exceed For More Than 15 Mins
                                <?php
                                if ($total_break_time_15min_days > 0) {
                                    ?>
                                    <img src="<?php echo LES_BASE_URL; ?>assets/images/tooltip.png" alt="Display Picture" title="<?php echo $total_break_time_15min_days; ?>" class="img-rounded tooltips tooltipimage">
                                <?php } ?>
                            </th>
                            <td colspan="1"><?php echo $total_break_time_15min; ?></td>
                        </tr>
                        <tr >
                            <th scope="row" class="column1 left">Break Exceed For More Than 45 Mins
                                <?php if ($total_break_time_45min_days > 0) {
                                    ?>
                                    <img src="<?php echo LES_BASE_URL; ?>assets/images/tooltip.png" alt="Display Picture" title="<?php echo $total_break_time_45min_days; ?>" class="img-rounded tooltips tooltipimage">
                                <?php } ?>
                            </th>
                            <td colspan="1"><?php echo $total_break_time_45min; ?></td>
                        </tr>                        
                    </tbody>
                    <tfoot>

                    </tfoot>
                </table>
            </div>
            <div id="details" class="attendance_summary width" style="display:none; margin-left:5px">
                <table>
                    <tbody>
                        <tr>
                            <th scope="row" rowspan="20" class="column1"><strong>Total Breaks</strong> <br>(<?php echo CalculateWorkedTime($totalcalculatedbreak); ?>)</th>
                            <th scope="col"></th>
                            <th class="odd" scope="col"><strong>Break Type</strong></th>
                            <th class="odd" strong scope="strong" abbr="Home Plus"><strong>Approved</strong></th>	
                            <th class="odd" strong scope="col" abbr="Business"><strong>Pending</strong></th>
                        </tr>
                        <tr>
                            <th rowspan="7" scope="col"><strong>Productive <br>Breaks</strong></th>
                            <th scope="row" class="column1">Meeting</th>
                            <td><?php echo CalculateWorkedTime($total_break_meeting_approved); ?></td>
                            <td><?php echo CalculateWorkedTime($total_break_meeting_unapproved); ?></td>

                        </tr>	
                        <tr>
                            <th scope="row" class="column1">Discussion</th>
                            <td><?php echo CalculateWorkedTime($total_break_discussion_approved); ?></td>
                            <td><?php echo CalculateWorkedTime($total_break_discussion_unapproved); ?></td>
                        </tr>	
                        <tr>
                            <th scope="row" class="column1">Interview</th>
                            <td><?php echo CalculateWorkedTime($total_break_interview_approved); ?></td>
                            <td><?php echo CalculateWorkedTime($total_break_interview_unapproved); ?></td>
                        </tr>
                        <tr>
                            <th scope="row" class="column1">Training</th>
                            <td><?php echo CalculateWorkedTime($total_break_training_approved); ?></td>
                            <td><?php echo CalculateWorkedTime($total_break_training_unapproved); ?></td>
                        </tr>
                        <tr>
                            <th scope="row" class="column1">Coaching Session</th>
                            <td><?php echo CalculateWorkedTime($total_break_coaching_session_approved); ?></td>
                            <td><?php echo CalculateWorkedTime($total_break_coaching_session_unapproved); ?></td>
                        </tr>
                        <tr>
                            <th scope="row" class="column1">WC</th>
                            <td><?php echo CalculateWorkedTime($total_wc_break); ?></td>
                            <td><?php echo CalculateWorkedTime(0); ?></td>
                        </tr>
                        <tr>
                            <th scope="row" class="column1"><strong>Total</strong></th>
                            <td><strong><?php echo CalculateWorkedTime($total_productive_breaks); ?></strong></td>
                            <td><strong><?php echo CalculateWorkedTime($total_pending_breaks); ?></strong></td>
                        </tr> 
                        <tr>
                            <th rowspan="14" scope="col"><strong>Non-Productive <br>Breaks</strong><br><?php echo CalculateWorkedTime($total_non_productive_breaks); ?></th>
                            <th scope="row" class="column1">Inactivity</th>
                            <td  colspan="2"><?php echo CalculateWorkedTime($total_break_inactivity); ?></td>
                        </tr>
                        <tr>
                            <th scope="row" class="column1">Lunch/Dinner</th>
                            <td  colspan="2"><?php echo CalculateWorkedTime($total_break_meal); ?></td>
                        </tr>
                        <tr>
                            <th scope="row" class="column1">Tea</th>
                            <td colspan="2"><?php echo CalculateWorkedTime($total_break_tea); ?></td>
                        </tr>

                        <tr>
                            <th scope="row" class="column1">Prayer</th>
                            <td colspan="2"><?php echo CalculateWorkedTime($total_break_prayer); ?></td>
                        </tr>
                        <tr>
                            <th scope="row" class="column1">Other</th>
                            <td colspan="2"><?php echo CalculateWorkedTime($total_break_other); ?></td>
                        </tr>
                        <tr>
                            <th scope="row" class="column1">Hibernate/Sleep</th>
                            <td colspan="2"><?php echo CalculateWorkedTime($total_break_hibernate_sleep); ?></td>
                        </tr>

                        <tr>
                            <th scope="row" class="column1">Crash</th>
                            <td colspan="2"><?php echo CalculateWorkedTime($total_break_crash); ?></td>
                        </tr>
                        <tr>
                            <th scope="row" class="column1">Network Crash</th>
                            <td colspan="2"><?php echo CalculateWorkedTime($total_break_network); ?></td>
                        </tr>
                        <!--<tr>
                            <th scope="row" class="column1">Web</th>
                            <td colspan="2"><?php echo CalculateWorkedTime($total_break_web); ?></td>
                        </tr>

                        <tr>
                            <th scope="row" class="column1">Employee</th>
                            <td colspan="2"><?php echo CalculateWorkedTime($total_break_employee); ?></td>
                        </tr>-->
                        <tr>
                            <th scope="row" class="column1">Manual Logout</th>
                            <td colspan="2"><?php echo CalculateWorkedTime($total_break_manual_logout); ?></td>
                        </tr>
                        <tr>
                            <th scope="row" class="column1">System Changed</th>
                            <td colspan="2"><?php echo CalculateWorkedTime($total_break_system_changed); ?></td>
                        </tr>
                        <tr>
                            <th scope="row" class="column1">Shut Down</th>
                            <td colspan="2"><?php echo CalculateWorkedTime($total_break_shut_down); ?></td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div id="requests_source" class="leaves_summary" style="margin-top: 7px; margin-left: 2px; display:none;">
                <?php $this->load->view('templates/requests_status'); ?> 
            </div> 
        </div> 

        <div id="dvData" style="display:none;">
            <table>
                <tr>
                    <th>Department</th>
                    <th>Name</th>
                    <th>Date</th>
                    <th>Time In</th>
                    <th>Time Out</th>
                    <th>Total Breaks</th>
                    <th>Total Worked Hours</th>
                    <th>Shift Start Time</th>
                    <th>Shift End Time</th>
                </tr>
                <?php foreach ($attendances as $key => $attendance) { ?>
                    <tr>
                        <td><?php echo $department_detail; ?></td>
                        <td><?php echo $completeName; ?></td>
                        <td><?php echo $attendance['Date']; ?></td>
                        <td><?php echo $attendance['InTime']; ?></td>
                        <td><?php echo $attendance['OutTime']; ?></td>
                        <td><?php echo CalculateWorkedTime($attendance['BreakTime']['total_break_time']); ?></td>
                        <td><?php echo CalculateWorkedTime($attendance['WorkedHours']); ?></td>
                        <td><?php echo $attendance['ShiftStartTime']; ?></td>
                        <td><?php echo $attendance['ShiftEndTime']; ?></td>
                    </tr>
                <?php } ?>
            </table>
        </div>
        <br><br>
    <?php } ?>
</div>
<script>
    $("#destination").append($("#source").html());
    $("#source").hide();
    $("#attendance_details_summary").append($("#attendance_source").html());
    $("#attendance_source").hide();
<?php if ($department_history[0]['CompanyID'] != '1' && $department_history[0]['DepartmentID'] != '11') { ?>
        //$("#leaves_source").hide();
<?php } ?>
    /*window.onunload = refreshParent;
     function refreshParent() {
     window.opener.location = window.opener.location.protocol+"//"+window.opener.location.host+window.opener.location.pathname+"?tab=today_shifts";
     }*/
    $(document).ready(function () {
        var table = $('.admin-table').DataTable();
        table.order([0, 'desc']).draw();

<?php
//if shift ot request is due for approval
if ($shift_ot_auto_popup > 0) {
    ?>
            jQuery(function () {
                jQuery('#shift_ot_auto_popup').click();
            });
    <?php
}
//if break request is due for approval
if ($break_request_auto_popup > 0 && $shift_ot_auto_popup == 0) {
    ?>
            jQuery(function () {
                jQuery('#break_request_auto_popup').click();
            });
<?php } ?>

        $("#bttn_att_download_excel").click(function (e) {
            window.open('data:application/vnd.ms-excel,' + $('#dvData').html());
            e.preventDefault();

        });
        $("#view_details").click(function () {
            $("div #details").animate({width: 'toggle'});
            $("div #leaves_source").animate({width: 'toggle'});
        });

        $("#view_requests_status").click(function () {
            $("div #requests_source").animate({width: 'toggle'});
        });

        $("#view_punctuality_details").click(function () {
            $("div #leaves_source").animate({width: 'toggle'});
            $("div #punctuality_details").animate({width: 'toggle'});
            $("div #punctuality_approval_details").animate({width: 'toggle'});
        });

        $("#view_dependability_report").click(function () {
            $("div #dependability_report").animate({width: 'toggle'});
        });

        $('.attendance-modify').on('click', function () {
            window.open('<?php echo site_url(File_BASE_URL . '/attendance/attendancepopup'); ?>' + "/" + $(this).attr("data-attendance-id-modify") + "/" + $(this).attr("data-employee-id-show") + "/" + $(this).attr("data-shift-id-show") + "/" + $(this).attr("data-shift-date-show") + "/" + $(this).attr("data-shift-odate-show") + "/" + $(this).attr("data-shift-day-show") + "/" + $(this).attr("data-shift-workingday-show") + "/" + $(this).attr("data-shift-timein-show") + "/" + $(this).attr("data-shift-timeout-show") + "/" + $(this).attr("data-break-allocation-show"), 'Attendance', 'top = 50, width = 1100, height = 572, left = 120, titlebar=no, scrollbars = no, resizable = no, menubar=no, toolbar=no, location=no');
            /*location.reload();*/
        });
        $('.modify_att').on('click', function () {
            window.open('<?php echo site_url(File_BASE_URL . '/attendance/add'); ?>' + "/" + $(this).attr("data-attendance-id") + "/" + $(this).attr("data-date-show"), 'Add Attendance', 'top = 50, width = 1100, height = 572, left = 120, titlebar=no, scrollbars = no, resizable = no, menubar=no, toolbar=no, location=no');
            /*location.reload();*/
        });

        $('.attendance-break-add').on('click', function () {
            /*location.reload();*/
            var child = window.open('<?php echo site_url(File_BASE_URL . '/attendance/add_break_popup'); ?>' + "/" + $(this).attr("data-break-id-modify") + "/" + $(this).attr("data-employee-id-show") + "/" + $(this).attr("data-shift-id-show") + "/" + $(this).attr("data-shift-date-show") + "/" + $(this).attr("data-shift-day-show") + "/" + $(this).attr("data-breaklog-id-modify") + "/" + $(this).attr("data-attendance-id"), 'Break', 'top = 120, width = 1000, height = 460, left = 180, titlebar=no, scrollbars = no, resizable = no, menubar=no, toolbar=no, location=no');
            var timer = setInterval(checkChild, 500);

            function checkChild() {
                if (child.closed) {
                    $("#bttnatt").trigger("click");
                    console.log("window closed");
                    clearInterval(timer);
                }
            }

        });
        // On checked update
        $('.attendance-show-hide').on('click', function () {
            var id = $(this).data('attendance-id-show');
            var cls = $(this).parent().parent().attr('id');
            if ($(this).is(':checked'))
                var val = 1;
            else
                var val = 0;

            $.post("attendance_show_hide", {id: id, val: val}, function (result) {
                $('#' + cls + ' .five').text(result);
                //location.reload();
            });
        });

        //view attendance
        $('.view-attendance').on('click', function () {
            window.open('<?php echo site_url(File_BASE_URL . '/attendance/viewattendancelog'); ?>' + "/" + $(this).attr("data-attendance-id-modify") + "/" + $(this).attr("data-employee-id-show") + "/" + $(this).attr("data-shift-id-show") + "/" + $(this).attr("data-shift-date-show") + "/" + $(this).attr("data-shift-odate-show") + "/" + $(this).attr("data-shift-day-show") + "/" + $(this).attr("data-attendance-timein-show") + "/" + $(this).attr("data-attendance-timeout-show") + "/" + $(this).attr("data-attendance-modifieddate") + "/" + $(this).attr("data-attendance-approve-show"), 'View Log', 'top = 55, width = 1000, height = 550, left = 180, titlebar=no, scrollbars = yes, resizable = no, menubar=no, toolbar=no, location=no');
            /*location.reload();*/
        });

        //add EOD
        $('.addeod').on('click', function () {
            window.open('<?php echo site_url(File_BASE_URL . '/attendance/addeod'); ?>' + "/" + $(this).attr("data-attendance-id-modify"), 'Add EOD', 'top = 40, width = 1000, height = 600, left = 180, titlebar=no, scrollbars = yes, resizable = no, menubar=no, toolbar=no, location=no');
            /*location.reload();*/
        });
        //update EOD
        $('.updateeod').on('click', function () {
<?php if ($department_history[0]['CompanyID'] == '3' || $department_history[0]['DepartmentID'] == '1') { ?>
                window.open('<?php echo site_url(File_BASE_URL . '/attendance/updateeod'); ?>' + "/" + $(this).attr("data-attendance-id") + "/" + $(this).attr("data-eod-status"), 'Add EOD', 'top = 40, width = 1000, height = 600, left = 180, titlebar=no, scrollbars = yes, resizable = no, menubar=no, toolbar=no, location=no');
<?php } else { ?>
                window.open('<?php echo site_url(File_BASE_URL . '/attendance/updateeod'); ?>' + "/" + $(this).attr("data-attendance-id") + "/" + $(this).attr("data-eod-status"), 'Add EOD', 'top = 72, width = 1000, height = 500, left = 180, titlebar=no, scrollbars = yes, resizable = no, menubar=no, toolbar=no, location=no');
<?php } ?>

            /*location.reload();*/
        });


        //view usage history
        $('.view-usagehistory').on('click', function () {
            window.open('<?php echo site_url(File_BASE_URL . '/attendance/viewusagehistory'); ?>' + "/" + $(this).attr("data-employee-id-show") + "/" + $(this).attr("data-usagehistory-date-show"), 'view usage history', 'top = 55, width = 1000, height = 550, left = 180, titlebar=no, scrollbars = yes, resizable = no, menubar=no, toolbar=no, location=no');
            /*location.reload();*/
        });

        //view break log
        $('.view-break-log').on('click', function () {
            window.open('<?php echo site_url(File_BASE_URL . '/attendance/viewbreaklog'); ?>' + "/" + $(this).attr("data-break-id-modify") + "/" + $(this).attr("data-employee-id-show") + "/" + $(this).attr("data-shift-id-show") + "/" + $(this).attr("data-shift-date-show") + "/" + $(this).attr("data-shift-day-show") + "/" + $(this).attr("data-breaklog-id-modify") + "/" + $(this).attr("data-attendance-id") + "/" + $(this).attr("data-break-approve-show"), 'View Break Log', 'top = 72, width = 1000, height = 500, left = 180, titlebar=no, scrollbars = yes, resizable = no, menubar=no, toolbar=no, location=no');
            /*location.reload();*/
        });

        //view break comment
        $('.view-break-comment').on('click', function () {
            window.open('<?php echo site_url(File_BASE_URL . '/breaks/viewbreakcomments'); ?>' + "/" + $(this).attr("data-break-id-modify"), 'View Break Comment', 'top = 140, width = 770, height = 360, left = 270, titlebar=no, scrollbars = yes, resizable = no, menubar=no, toolbar=no, location=no');
            /*location.reload();*/
        });

        //Approve Break
        $('.approve-break').on('click', function () {
            window.open('<?php echo site_url(File_BASE_URL . '/attendance/approvebreak'); ?>' + "/" + $(this).attr("data-break-id-modify") + "/" + $(this).attr("data-employee-id-show") + "/" + $(this).attr("data-shift-id-show") + "/" + $(this).attr("data-shift-date-show") + "/" + $(this).attr("data-shift-day-show") + "/" + $(this).attr("data-breaklog-id-modify") + "/" + $(this).attr("data-attendance-id") + "/" + $(this).attr("data-break-approve-show"), 'Approve Break', 'top = 72, width = 1000, height = 500, left = 180, titlebar=no, scrollbars = yes, resizable = no, menubar=no, toolbar=no, location=no');
            /*location.reload();*/
        });

        //Break Approval Request
        $('.break_raise_request').on('click', function () {
            window.open('<?php echo site_url(File_BASE_URL . '/attendance/breakapprovalrequest'); ?>' + "/" + $(this).attr("data-break-id-modify") + "/" + $(this).attr("data-break-date-show"), 'Approve Break', 'top = 72, width = 1000, height = 550, left = 150, titlebar=no, scrollbars = no, resizable = no, menubar=no, toolbar=no, location=no');
            /*location.reload();*/
        });

        //delete break
        $('.delete-break').on('click', function () {
            window.open('<?php echo site_url(File_BASE_URL . '/attendance/deletebreak'); ?>' + "/" + $(this).attr("data-break-id-modify") + "/" + $(this).attr("data-employee-id-show") + "/" + $(this).attr("data-shift-id-show") + "/" + $(this).attr("data-shift-date-show") + "/" + $(this).attr("data-shift-day-show") + "/" + $(this).attr("data-breaklog-id-modify") + "/" + $(this).attr("data-attendance-id"), 'View Break Log', 'top = 72, width = 1000, height = 550, left = 180, titlebar=no, scrollbars = yes, resizable = no, menubar=no, toolbar=no, location=no');
            /*location.reload();*/
        });

        //apply leave
        $('.leave-apply').on('click', function () {
            window.open('<?php echo site_url(File_BASE_URL . '/leaves/leave_apply'); ?>' + "/" + $(this).attr("data-employee-id-show") + "/" + $(this).attr("data-shift-id-show") + "/" + $(this).attr("data-leave-startdate") + "/" + $(this).attr("data-leave-year"), 'Apply Leave', 'top = 72, width = 770, height = 510, left = 270, titlebar=no, scrollbars = yes, resizable = no, menubar=no, toolbar=no, location=no');
            /*location.reload();*/
        });

        //apply swap
        $('.swap-apply').on('click', function () {
            window.open('<?php echo site_url(File_BASE_URL . '/swaps/apply'); ?>' + "/" + $(this).attr("data-swap-from"), 'Apply Swap', 'top = 72, width = 770, height = 510, left = 270, titlebar=no, scrollbars = yes, resizable = no, menubar=no, toolbar=no, location=no');
            /*location.reload();*/
        });

        //approve OT
        $('.approve-restriction').on('click', function () {
            window.open('<?php echo site_url(File_BASE_URL . '/attendance/approve_restriction'); ?>' + "/" + $(this).attr("data-approve-restriction") + "/" + $(this).attr("data-approve-type"), 'Approve OT', 'top = 14, width = 770, height = 630, left = 270, titlebar=no, scrollbars = no, resizable = no, menubar=no, toolbar=no, location=no');
            /*location.reload();*/
        });

        //Restriction Approval Request
        $('.restriction-approval-request').on('click', function () {
            window.open('<?php echo site_url(File_BASE_URL . '/attendance/restriction_approval_request'); ?>' + "/" + $(this).attr("data-attendance-id") + "/" + $(this).attr("data-restriction-type"), 'Restriction Approval Request', 'top = 14, width = 770, height = 630, left = 270, titlebar=no, scrollbars = no, resizable = no, menubar=no, toolbar=no, location=no');
            /*location.reload();*/
        });

        //View Restriction Details
        $('.view-restriction-details').on('click', function () {
            window.open('<?php echo site_url(File_BASE_URL . '/attendance/view_restriction_details'); ?>' + "/" + $(this).attr("data-attendance-id") + "/" + $(this).attr("data-restriction-type") + "/" + $(this).attr("data-calculated_worked_hours"), 'View Restriction Details', 'top = 14, width = 770, height = 630, left = 270, titlebar=no, scrollbars = no, resizable = no, menubar=no, toolbar=no, location=no');
            /*location.reload();*/
        });

        //Late Arrival Punctuality Request
        $('.late-punctuality-request').on('click', function () {
            window.open('<?php echo site_url(File_BASE_URL . '/attendance/punctuality_request'); ?>' + "/" + $(this).attr("data-late-punctuality-request") + "/" + $(this).attr("data-punctuality-type"), 'Late Arrival Punctuality Request', 'top = 72, width = 770, height = 510, left = 270, titlebar=no, scrollbars = no, resizable = no, menubar=no, toolbar=no, location=no');
            /*location.reload();*/
        });

        //Early Exit Punctuality Request
        $('.early-punctuality-request').on('click', function () {
            window.open('<?php echo site_url(File_BASE_URL . '/attendance/punctuality_request'); ?>' + "/" + $(this).attr("data-early-punctuality-request") + "/" + $(this).attr("data-punctuality-type"), 'Early Exit Punctuality Request', 'top = 72, width = 770, height = 510, left = 270, titlebar=no, scrollbars = no, resizable = no, menubar=no, toolbar=no, location=no');
            /*location.reload();*/
        });

        //view leave
        $('.view_leaves').on('click', function () {
            window.open('<?php echo site_url(File_BASE_URL . '/leaves/view'); ?>' + "/" + $(this).attr("data-employee-id"), 'View Leave', 'top = 72, width = 1100, height = 500, left = 110, titlebar=no, scrollbars = no, resizable = no, menubar=no, toolbar=no, location=no');
            /*location.reload();*/
        });

        //apply leave future
        //$('.leave-apply-future').on('click', function () {
        //window.open('<?php echo site_url(File_BASE_URL . '/leaves/leave_apply_future'); ?>' + "/" + $(this).attr("data-employee-id-show") + "/" + $(this).attr("data-leave-year"), 'Apply Leave Future', 'top = 100, width = 770, height = 435, left = 270, titlebar=no, scrollbars = no, resizable = no, menubar=no, toolbar=no, location=no');
        /*location.reload();*/
        //});

        //apply leave future
        $('.leave-apply-future').on('click', function () {
            window.open('<?php echo site_url(File_BASE_URL . '/leaves/apply_future'); ?>' + "/" + $(this).attr("data-employee-id-show") + "/" + $(this).attr("data-leave-year"), 'Apply Leave Future', 'top = 100, width = 770, height = 435, left = 270, titlebar=no, scrollbars = no, resizable = no, menubar=no, toolbar=no, location=no');
            /*location.reload();*/
        });

        //apply floater encashment
        $('.apply-leave-encashment').on('click', function () {
            window.open('<?php echo site_url(File_BASE_URL . '/leavesencashment/encash_leaves'); ?>' + "/" + $(this).attr("data-employee-id-show") + "/" + $(this).attr("data-current-month"), 'Apply Leave Encashment', 'top = 100, width = 770, height = 435, left = 270, titlebar=no, scrollbars = no, resizable = no, menubar=no, toolbar=no, location=no');
            /*location.reload();*/
        });

        //Training Break Info
        $('.training-break-info').on('click', function () {
            window.open('<?php echo site_url(File_BASE_URL . '/breaks/training_break_info'); ?>' + "/" + $(this).attr("data-break-id") + "/" + $(this).attr("data-department-id"), 'Training Break Info', 'top = 100, width = 770, height = 435, left = 270, titlebar=no, scrollbars = no, resizable = no, menubar=no, toolbar=no, location=no');
            /*location.reload();*/
        });

        //View Leave Log
        $('.view-leave-log').on('click', function () {
            window.open('<?php echo site_url(File_BASE_URL . '/leaves/view_leave_log'); ?>' + "/" + $(this).attr("data-leave-id"), 'View Leave Log', 'top = 140, width = 1000, height = 400, left = 140, titlebar=no, scrollbars = no, resizable = no, menubar=no, toolbar=no, location=no');
            /*location.reload();*/
        });

        //Approve leave
        $('.update-leave').on('click', function () {
            window.open('<?php echo site_url(File_BASE_URL . '/leaves/updateleave'); ?>' + "/" + $(this).attr("data-leave-id") + "/" + $(this).attr("data-leave-status"), 'Approve leave', 'top = 120, width = 1000, height = 440, left = 180, titlebar=no, scrollbars = yes, resizable = no, menubar=no, toolbar=no, location=no');
            /*location.reload();*/
        });
    });
</script>

<style>
    .attendance_summary {
        border-top:1px solid #e5eff8;
        border-right:1px solid #e5eff8;
        /*margin:1em auto;*/
        border-collapse:collapse;
        float:left;
        margin-right: 12px;
    }

    .attendance_summary tr.odd td {
        background:#f7fbff
    }
    .attendance_summary tr.odd .column1	{
        background:#f4f9fe;
    }	
    .attendance_summary .column1 {
        background:#f9fcfe;
    }
    .attendance_summary td {
        color:#678197;
        border-bottom:1px solid #e5eff8;
        border-left:1px solid #e5eff8;
        padding:.3em 1em;
        text-align:center;
    }
    .attendance_summary td a {
        color: #fff;
    }
    .attendance_summary td a:hover {
        color: #286090;
    }
    .attendance_summary .btn-view-details{
        font-size: 10px;
        padding: 0px 5px;
        margin-left:14px
    }				
    .attendance_summary th {
        font-weight:normal;
        color: #678197;
        text-align:left;
        border-bottom: 1px solid #e5eff8;
        border-left:1px solid #e5eff8;
        padding:.3em 1em;
    }
    .attendance_summary th {
        font-weight:normal;
        color: #678197;
        text-align:left;
        border-bottom: 1px solid #e5eff8;
        border-left:1px solid #e5eff8;
        padding:.3em 1em;
    }
    .attendance_summary thead th {
        background:#f4f9fe;
        text-align:center;
        font:bold 1.2em/2em "Century Gothic","Trebuchet MS",Arial,Helvetica,sans-serif;
        color:#66a3d3
    }	
    .attendance_summary tfoot th {
        text-align:center;
        background:#f4f9fe;
    }	
    .attendance_summary tfoot th strong {
        /*        font:bold 1.2em "Century Gothic","Trebuchet MS",Arial,Helvetica,sans-serif;
                margin:.5em .5em .5em 0;
                color:#66a3d3;*/
    }		
    .attendance_summary tfoot th em {
        color:#f03b58;
        font-weight: bold;
        font-size: 1.1em;
        font-style: normal;
    }
    .attendance_summary .width {
        margin-left: 63px;
    }
    /*    leaves record css */
    .leaves_summary {
        border-top:1px solid #e5eff8;
        border-right:1px solid #e5eff8;
        /*margin:1em auto;*/
        border-collapse:collapse;
        float:left;
    }

    .leaves_summary tr.odd td	{
        background:#f7fbff
    }
    .leaves_summary tr.odd .column1 {
        background:#f4f9fe;
    }	
    .leaves_summary .column1 {
        background:#f9fcfe;
    }
    .leaves_summary td {
        color:#678197;
        border-bottom:1px solid #e5eff8;
        border-left:1px solid #e5eff8;
        padding:.3em 1em;
        text-align:center;
    }
    .leaves_summary td a {
        color: #fff;
    }
    .leaves_summary td a:hover {
        color: #286090;
    }
    .leaves_summary .btn-view-details {
        font-size: 10px;
        padding: 0px 5px;
        margin-left:14px
    }				
    .leaves_summary th {
        font-weight:normal;
        color: #678197;
        text-align: center;
        border-bottom: 1px solid #e5eff8;
        border-left:1px solid #e5eff8;
        padding:.3em 1em;
    }
    .leaves_summary th {
        font-weight:normal;
        color: #678197;
        text-align: center;
        border-bottom: 1px solid #e5eff8;
        border-left:1px solid #e5eff8;
        padding:.3em 1em;
    }
    .leaves_summary thead th {
        background:#f4f9fe;
        text-align:center;
        font:bold 1.2em/2em "Century Gothic","Trebuchet MS",Arial,Helvetica,sans-serif;
        color:#66a3d3
    }	
    .leaves_summary tfoot th {
        text-align:center;
        background:#f4f9fe;
        color: #678197;
    }	
    /*    .leaves_summary tfoot th strong {
            font:bold 1.2em "Century Gothic","Trebuchet MS",Arial,Helvetica,sans-serif;
            margin:.5em .5em .5em 0;
            color:#66a3d3;
        }		*/
    .leaves_summary tfoot th em {
        color:#f03b58;
        font-weight: bold;
        font-size: 1.1em;
        font-style: normal;
    }
    .leaves_summary .width {
        margin-left: 63px;
    }
</style>