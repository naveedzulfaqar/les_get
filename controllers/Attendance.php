<?php

class Attendance extends Admin_Controller {

    public function __construct() {
        parent::__construct();

        if ($this->session->userdata('role') == '') { //If someone role changed due to system issue, then below change back to employees role
            $data = array('role' => '4');
            $this->session->set_userdata($data);
        }
    }

    public function index($EmployeeID) {

        // Models File Include
        $this->load->model('Employee_model');
        $data = array();

        //include js files
        $data["js_include"][] = JS_BASE_URL . "/script.js";
        $data["js_include"][] = JS_BASE_URL . "/attendance.js";

        if ($EmployeeID) {
            $data['EmployeeID'] = $EmployeeID;
            $data['EmployeeName'] = str_replace("_", " ", $this->uri->segment('4'));
        } else {
            $data['EmployeeID'] = $this->session->userdata('id');
            $data['EmployeeName'] = $this->session->userdata('FirstName') . ' ' . $this->session->userdata('LastName');
        }

        /* Getting the month search start date */
        if ($this->uri->segment('5')) {
            $data['sdate'] = date('Y-m-d', strtotime('-1 day', strtotime($this->uri->segment('5'))));
            $data['edate'] = $this->uri->segment('5');
            if ($this->uri->segment('6')) {
                $data['sdate'] = $this->uri->segment('5');
                $data['edate'] = $this->uri->segment('6');
            }
        } else {
            $TodayDate = date('Y-m-d');
            if (date('Y-m-d', strtotime($TodayDate)) > date('Y-m-d', strtotime(date('Y-m-20')))) {
                $data['sdate'] = date('Y-m-d', strtotime((date('Y-m-21'))));
            } else {
                $data['sdate'] = date('Y-m-d', strtotime('-1 month', strtotime(date('Y-m-21'))));
            }
            //$data['sdate'] = date('Y-m-d', strtotime(date('Y-m-16')));
            $data['edate'] = date('Y-m-d');
        }

        if ($this->session->userdata('DepartmentID') == '12') {
            if ($this->uri->segment('5')) {
                $data['sdate'] = date('Y-m-d', strtotime('-1 day', strtotime($this->uri->segment('5'))));
                $data['edate'] = $this->uri->segment('5');
            } else {
                $TodayDate = date('Y-m-d');
                if (date('Y-m-d', strtotime($TodayDate)) > date('Y-m-d', strtotime(date('Y-m-01')))) {
                    $data['sdate'] = date('Y-m-d', strtotime((date('Y-m-01'))));
                } else {
                    $data['sdate'] = date('Y-m-d', strtotime('-1 month', strtotime(date('Y-m-01'))));
                }
                //$data['sdate'] = date('Y-m-d', strtotime(date('Y-m-16')));
                $data['edate'] = date('Y-m-d');
            }
        }

        //calling helper function to get pending requests count
        count_pending_requests();
        //Getting the pending hr requests count
        count_pending_hr_requests($data);

        //Getting the employee profile info to check profile is complete or not
        $employee = $this->Employee_model->get_employee($this->session->userdata('id'));
        //echo '<pre>'; print_r($employee); echo '</pre>';

        $profile_status = 0;
        $missingNotification = '';
        if ($employee['CNIC'] == '') {
            $missingNotification .= 'CNIC, ';
        }
        if ($employee['Address'] == '') {
            $missingNotification .= 'Address, ';
        }
        if ($employee['BirthDate'] == '') {
            $missingNotification .= 'Date of Birth, ';
        }
        if ($employee['MobileNumber'] == '') {
            $missingNotification .= 'Mobile Number, ';
        }
        if ($employee['BloodGroup'] == '') {
            //$missingNotification .= 'Blood Group, ';
        }
        if ($employee['Vehicle1'] == '' && $employee['Vehicle2'] == '') {
            $missingNotification .= 'Vehicle Info';
        }

        if ($employee['BirthDate'] == '' || $employee['MobileNumber'] == '' || ($this->session->userdata('CompanyID') != '3' && $employee['CNIC'] == '')) {
            $profile_status++;
        }
        if ($employee['Address'] == '' || $employee['BloodGroup'] == '') {
            //$profile_status++;
        }
        if ($employee['Vehicle1'] == '' && $employee['Vehicle2'] == '') {
            $profile_status++;
        }
        if ($profile_status > 0) {
            $this->session->set_flashdata('failed_message', 'Your LES profile is incomplete. Please update your info (' . $missingNotification . ')');
            redirect(File_BASE_URL . '/profile/index' . '/' . $employee['ID']);
        }

        $this->load->view('attendance/index', $data);
    }

    public function autocomplete() {

        // Models File Include
        $this->load->model('Employee_model');
        $data = array();

        if ($this->input->post('keyword')) {
            $name = $this->input->post('keyword');
            $data['employee'] = $this->Employee_model->get_employeebyname($name);
        } else {
            $empid = $this->input->post('empid');
            $data['employee'] = $this->Employee_model->get_employeebyid($empid);
        }
        // $this->load->view('attendance/get_attendance', $data);
    }

    public function getdates() {

        // Models File Include
        $this->load->model('Department_history_model');
        $data = array();

        $empid = $this->input->post('empid');
        $deptid = $this->Department_history_model->get_departmentid($empid);
        //print_r($deptid);
        if ($deptid['DepartmentID'] == '12') {
            $TodayDate = date('Y-m-d');
            if (date('Y-m-d', strtotime($TodayDate)) > date('Y-m-d', strtotime(date('Y-m-01')))) {
                $data['sdate'] = date('Y-m-d', strtotime((date('Y-m-01'))));
            } else {
                $data['sdate'] = date('Y-m-d', strtotime('-1 month', strtotime(date('Y-m-01'))));
            }
            //$data['sdate'] = date('Y-m-d', strtotime(date('Y-m-16')));
            $data['edate'] = date('Y-m-d');
        } else {
            $TodayDate = date('Y-m-d');
            if (date('Y-m-d', strtotime($TodayDate)) > date('Y-m-d', strtotime(date('Y-m-20')))) {
                $data['sdate'] = date('Y-m-d', strtotime((date('Y-m-21'))));
            } else {
                $data['sdate'] = date('Y-m-d', strtotime('-1 month', strtotime(date('Y-m-21'))));
            }
            //$data['sdate'] = date('Y-m-d', strtotime(date('Y-m-16')));
            $data['edate'] = date('Y-m-d');
        }
        $data = json_encode($data);
        print_r($data);
    }

    public function get_attendance() {

        // Models File Include
        $this->load->model('Employee_model');
        $this->load->model('Attendance_model');
        $this->load->model('Attendance_log_model');
        $this->load->model('Shift_details_model');
        $this->load->model('Day_model');
        $this->load->model('Department_history_model');
        $this->load->model('Role_info_model');
        $this->load->model('Attendance_details_model');
        $this->load->model('Leaves_model');
        $this->load->model('Leaves_type_model');
        $this->load->model('Holidays_model');
        $this->load->model('Break_approval_request_model');
        $this->load->model('Leaves_requested_model');
        $this->load->model('Leaves_encashment_model');
        $this->load->model('Approve_ot_model');
        $this->load->model('Approve_sr_model');
        $this->load->model('Punctuality_request_model');
        $this->load->model('Swaps_requested_model');

        $data = array();

        $data['EmployeeID'] = $EmployeeID = $this->input->post('empid');

        $data['employee'] = $this->Employee_model->get_employee($data['EmployeeID']);

        //calling helper function to get pending requests count
        count_pending_requests();
        //Getting the pending hr requests count
        count_pending_hr_requests($data);
        if ($this->session->userdata('id') == '189') {
            /* echo '<pre>'; print_r($data); echo '</pre>'; */
        }
        // calling the helper function for all leave data
        $data = leaves_record($data);



        $data['EmployeeName'] = $post_empname = $this->input->post('empname');
        $post_strtdate = $this->input->post('strtdate');
        $post_enddate = $this->input->post('enddate');

        if ($this->input->post('strtdate') != '' && $this->input->post('enddate') != '') {
            $data['start_month_date'] = date('Y-m-d', strtotime($this->input->post('strtdate')));
            $data['end_month_date'] = date('Y-m-d', strtotime($this->input->post('enddate')));
        } else if ($this->input->post('strtdate') == '' && $this->input->post('enddate') != '') {
            $data['start_month_date'] = date('Y-m-d', strtotime('-1 month', strtotime(date('Y-m-21'))));
            $data['end_month_date'] = date('Y-m-d', strtotime($this->input->post('enddate')));
        } else if ($this->input->post('strtdate') != '' && $this->input->post('enddate') == '') {
            $data['start_month_date'] = date('Y-m-d', strtotime($this->input->post('strtdate')));
            $data['end_month_date'] = date('Y-m-20');
        } else {
            $data['start_month_date'] = date('Y-m-d', strtotime('-1 month', strtotime(date('Y-m-21'))));
            $data['end_month_date'] = date('Y-m-20');
        }

        /* Getting the leave requested expiry date */
        $TodayDate = date('Y-m-d');
        if (date('Y-m-d', strtotime(date('Y-m-d'))) > date('Y-m-d', strtotime(date('Y-m-20')))) {
            $data['expirydate'] = date('Y-m-d', strtotime((date('Y-m-25'))));
        } else {
            $data['expirydate'] = date('Y-m-d', strtotime('-1 month', strtotime(date('Y-m-25'))));
        }

        $start_date = date('d', strtotime($data['start_month_date']));
        $start_month = date('m', strtotime($data['start_month_date']));
        $start_year = date('Y', strtotime($data['start_month_date']));
        $end_date = date('d', strtotime($data['end_month_date']));
        $end_month = date('m', strtotime($data['end_month_date']));
        $end_year = date('Y', strtotime($data['end_month_date']));

        if (date('Y-m-d', strtotime(date('Y-m-d'))) > date('Y-m-d', strtotime(date('Y-m-20')))) {
            $data['current_month'] = date('m') + 1;
            if ($data['current_month'] == 13) {
                $data['current_month'] = 1;
            }
        } else {
            $data['current_month'] = date('m');
        }
        $data['current_year'] = date('Y');
        if (date('Y-m-d', strtotime($TodayDate)) > date('Y-m-d', strtotime(date('Y-12-20')))) {
            $data['current_year'] = $current_year + 1;
        }
        if ($this->session->userdata('id') == '321') {
            //$data['current_month'] = '12';
            //echo '<br>current_month '.$data['current_month'];
        }

        // getting employee pending requests
        $data['breaks_request_status'] = $this->Break_approval_request_model->get_employee_breaks_request_status($EmployeeID, $data['start_month_date'], $data['end_month_date']);
        $data['leaves_request_status'] = $this->Leaves_requested_model->get_employee_leaves_request_status($EmployeeID, $data['start_month_date'], $data['end_month_date']);
        $data['leave_encashments_request_status'] = $this->Leaves_encashment_model->get_employee_leave_encashments_request_status($EmployeeID, $data['current_month'], $data['current_year']);
        $data['ot_request_status'] = $this->Approve_ot_model->get_employee_ot_request_status($EmployeeID, $data['start_month_date'], $data['end_month_date']);
        $data['sr_request_status'] = $this->Approve_sr_model->get_employee_sr_request_status($EmployeeID, $data['start_month_date'], $data['end_month_date']);
        $data['pr_request_status'] = $this->Punctuality_request_model->get_employee_pr_request_status($EmployeeID, $data['start_month_date'], $data['end_month_date']);
        $data['swap_request_status'] = $this->Swaps_requested_model->get_employee_swap_request_status($EmployeeID, $data['start_month_date'], $data['end_month_date']);

        $data['leaves_available'] = get_leaves_available_for_encashment($data['leaves_allocated'], $data['current_month'], $data['current_year']);

        //Setting Up TimeZones
        if (isset($_COOKIE['clientTimeZone'])) {
            $timezone = $_COOKIE['clientTimeZone'];
        } else {
            $timezone = 'UP5';
        }
        $FromTimeZone = 'GMT';
        $ToTimeZone = $timezone;

        $CTime = date('h:i a');
        $CTime = ConvertTimeZone($CTime, $FromTimeZone, $ToTimeZone);

        $msg = "EID: " . $data['EmployeeID'];
        $msg .= ", EName: " . $post_empname;
        $msg .= ", Start Date: " . $post_strtdate;
        $msg .= ", End Date: " . $post_enddate;
        $msg .= ", On: " . $CTime;

        $ID = $this->session->userdata('id');
        if ($ID != $data['EmployeeID']) {
            $logfilename = 'attendance/' . $ID;
            lesLogger($msg, $logfilename);


            $session_data['FirstName'] = $this->session->userdata('FirstName');
            $session_data['LastName'] = $this->session->userdata('LastName');

            $msg = "EID: " . $ID;
            $msg .= ", EName: " . $session_data['FirstName'] . " " . $session_data['LastName'];

            $msg .= ", Start Date: " . $post_strtdate;
            $msg .= ", End Date: " . $post_enddate;
            $msg .= ", On: " . $CTime;


            $logfilename = 'attendance/emp/' . $data['EmployeeID'];
            lesLogger($msg, $logfilename);
        }

        if ($this->session->userdata('id') == '189') {
            //echo '<br>EmployeeID '.$data['EmployeeID'];
            /* echo '<pre>'; print_r($data['employee']); echo '</pre>';  */
        }
        $data['department_history'] = $this->Department_history_model->get_department_history($SID = 0, $data['EmployeeID']);
        $data['admindepartment_history'] = $this->Department_history_model->get_department_history($SID = 0, $this->session->userdata('id'));
        $data['department_history_shifts'] = $this->Department_history_model->get_department_history_shifts($data['EmployeeID']);

        /* echo 'department_history<pre>'; print_r($data['department_history']); echo '</pre>'; exit; */

        foreach ($data['department_history'] as $department_history) {
            $data['shift_offdays'] = $this->Shift_details_model->get_shift_offdays($department_history['ShiftID']);
        }

        if (count($data['department_history']) > 0) {
            $a = 0;
            foreach ($data['department_history'] as $department_history) {
                if ($a == 0 && strtotime($department_history['StartDate']) <= strtotime(date('Y-m-d'))) {
                    $data['EmployeeDepartmentID'] = $department_history['DepartmentID'];
                    $data['EmployeeCompanyID'] = $department_history['CompanyID'];
                    $data['current_ShiftID'] = $department_history['ShiftID'];
                    $a++;
                }
            }
            $data['shift_details'] = $this->Shift_details_model->get_shift_details($data['current_ShiftID']);
        } else {
            $data['current_ShiftID'] = '';
        }
        $a = 0;
        foreach ($data['admindepartment_history'] as $admindepartment_history) {
            if ($a == 0 && strtotime($admindepartment_history['StartDate']) <= strtotime(date('Y-m-d'))) {
                $data['AdminDepartmentID'] = $admindepartment_history['DepartmentID'];
                $a++;
            }
        }
        // getting the selected dates
        $data['dates'] = $this->Attendance_model->date_range_with_leaves_swaps_compesation($data['start_month_date'], $data['end_month_date'], $data['EmployeeID'], $data['EmployeeDepartmentID']);
        // getting the attendance details
        $data['getattendance'] = $this->Attendance_model->getattendance($data);

        // getting the last login attendance for restriction 2018-04-02
        $data['last_login_attendance'] = $this->Attendance_model->get_last_login_attendance($data['EmployeeID'], $TodayDate);

        /* $data['getshifts'] = $this->Shift_details_model->getshifts($data); */

        /* ----------------------- New Code Attendance day management Ends-------------------------------------- */


        $a = 0;
        $b = 1;
        $count = count($data['department_history']) - 1;
        if ($count == -1) {
            $data['department_history'][$count]['StartDate'] = '';
            $data['department_history'][count]['EndDate'] = '';
        }
        $employee_role = $this->Employee_model->get_employee_role($data['EmployeeID']);

        /* echo 'employee_role<pre>'; print_r($employee_role); echo '</pre>'; exit; */

        if ($this->session->userdata('role') != 1 || $start_year == '1970' || $end_year == '1970' || $start_date <= '0' || $start_date > '31' || $end_date <= '0' || $end_date > '31' || $start_month <= '0' || $start_month > '12' || $end_month <= '0' || $end_month > '12') {


            /**             * *******Multiple Department Rights Code Start********* */
            $overridecondition = '0';
            /* if (($this->session->userdata('id') == 45 && $data['EmployeeDepartmentID'] == 12)) {//Zujaja Ahmed (QA Manager) && // Sales Department
              $overridecondition = '1';
              } */
//            if (($this->session->userdata('id') == 186 && $data['EmployeeDepartmentID'] == 16)) {//Atif Kamran && // IT(Maavra Tech.)
//                $overridecondition = '1';
//            }
            if (($this->session->userdata('id') == 2 && $data['EmployeeDepartmentID'] == 2) || ($this->session->userdata('id') == 2 && $data['EmployeeDepartmentID'] == 4) || ($this->session->userdata('id') == 2 && $data['EmployeeDepartmentID'] == 6) || ($this->session->userdata('id') == 2 && $data['EmployeeDepartmentID'] == 10) || ($this->session->userdata('id') == 2 && $data['EmployeeDepartmentID'] == 15) || ($this->session->userdata('id') == 2 && $data['EmployeeDepartmentID'] == 34) || ($this->session->userdata('id') == 2 && $data['EmployeeDepartmentID'] == 36)) { //Azfar Naeem (Daniel Turner)
                $overridecondition = '1';
            }
            if (($this->session->userdata('id') == 3 && $data['EmployeeDepartmentID'] == 2) || ($this->session->userdata('id') == 3 && $data['EmployeeDepartmentID'] == 4) || ($this->session->userdata('id') == 3 && $data['EmployeeDepartmentID'] == 10) || ($this->session->userdata('id') == 3 && $data['EmployeeDepartmentID'] == 15) || ($this->session->userdata('id') == 3 && $data['EmployeeDepartmentID'] == 34) || ($this->session->userdata('id') == 3 && $data['EmployeeDepartmentID'] == 36)) { //Omer Mumtaz Chaudhry (Thomas Garcia)
                $overridecondition = '1';
            }
            if (($this->session->userdata('id') == 52 && $data['EmployeeDepartmentID'] == 8)) { // Muhammad Raza Azam (Ross) (Client Services Manager))
                $overridecondition = '1';
            }
//            if ($this->session->userdata('id') == 26 && $data['EmployeeDepartmentID'] == 3) {//Badar Farooq && // Marketing Manager
//                $overridecondition = '1';
//            }
            if (($this->session->userdata('id') == 263 && $data['EmployeeDepartmentID'] == 13) || ($this->session->userdata('id') == 263 && $data['EmployeeDepartmentID'] == 14) || ($this->session->userdata('id') == 263 && $data['EmployeeDepartmentID'] == 18) || ($this->session->userdata('id') == 263 && $data['EmployeeDepartmentID'] == 19) || ($this->session->userdata('id') == 263 && $data['EmployeeDepartmentID'] == 20) || ($this->session->userdata('id') == 263 && $data['EmployeeDepartmentID'] == 21) || ($this->session->userdata('id') == 263 && $data['EmployeeDepartmentID'] == 22) || ($this->session->userdata('id') == 263 && $data['EmployeeDepartmentID'] == 23) || ($this->session->userdata('id') == 263 && $data['EmployeeDepartmentID'] == 24) || ($this->session->userdata('id') == 263 && $data['EmployeeID'] == 11) || ($this->session->userdata('id') == 263 && $data['EmployeeID'] == 12) || ($this->session->userdata('id') == 263 && $data['EmployeeID'] == 10) || ($this->session->userdata('id') == 263 && $data['EmployeeID'] == 13) || ($this->session->userdata('id') == 263 && $data['EmployeeID'] == 493)) { //Kouadio Yao (Vincent Kandide)  (Team Lead / Supervisor)
                $overridecondition = '1';
            }
            if (($this->session->userdata('id') == 61) &&
                    (($data['EmployeeDepartmentID'] == 1) || ($data['EmployeeDepartmentID'] == 13) || ($data['EmployeeDepartmentID'] == 15) ||
                    ($data['EmployeeDepartmentID'] == 19) || ($data['EmployeeDepartmentID'] == 21) || ($data['EmployeeDepartmentID'] == 22) ||
                    ($data['EmployeeDepartmentID'] == 23) || ($data['EmployeeDepartmentID'] == 24) || ($data['EmployeeDepartmentID'] == 25) ) && ($employee_role['RoleID'] != 1) && ($employee_role['RoleID'] != 2)
            ) { //Haris Naeem (Norman Beaver)   (Team Lead / Supervisor)
                $overridecondition = '1';
            }
            if (($this->session->userdata('id') == 11 && $data['EmployeeID'] == 268) || ($this->session->userdata('id') == 11 && $data['EmployeeID'] == 292) || ($this->session->userdata('id') == 11 && $data['EmployeeID'] == 293) || ($this->session->userdata('id') == 11 && $data['EmployeeID'] == 346) || ($this->session->userdata('id') == 11 && $data['EmployeeID'] == 347) || ($this->session->userdata('id') == 11 && $data['EmployeeID'] == 28)) {//Sandra Schwab && // Team Lead / Supervisor
                $overridecondition = '1';
            }

            if ($this->session->userdata('id') == 83 && $data['EmployeeDepartmentID'] == 15 && ($data['EmployeeID'] == '230' || $data['EmployeeID'] == '231' || $data['EmployeeID'] == '255')) {//Muhammad Aneeq Hanyal (Keith Morgan)   (Team Lead / Supervisor)
                $overridecondition = '1';
            }
            if ($this->session->userdata('id') == 83 && ($data['EmployeeDepartmentID'] == 4 || $data['EmployeeDepartmentID'] == 6)) {//Muhammad Aneeq Hanyal (Keith Morgan)   (Team Lead / Supervisor)
                $overridecondition = '1';
            }
            if ($this->session->userdata('id') == 83 && $data['EmployeeDepartmentID'] == 1 && ($data['EmployeeID'] == '96')) {//Muhammad Aneeq Hanyal (Keith Morgan)   (Team Lead / Supervisor)
                $overridecondition = '1';
            }
            if ($this->session->userdata('id') == 83 && $data['EmployeeDepartmentID'] == 34 && ($data['EmployeeID'] == '229')) {//Muhammad Aneeq Hanyal (Keith Morgan)   (Team Lead / Supervisor)
                $overridecondition = '1';
            }

            if ($this->session->userdata('id') == 41 && $data['EmployeeDepartmentID'] == 12 && $data['EmployeeID'] != '1') {// Hammad Anwar Rights given for Sales team on 02-Feb-2017 (Brian Email)
                $overridecondition = '1';
            }
            if ($this->session->userdata('id') == 47 && $data['EmployeeDepartmentID'] == 12 && $data['EmployeeID'] != '1') {// Majid Rights given for Sales team on 02-Feb-2017 (Brian Email)
                $overridecondition = '1';
            }
            if (($this->session->userdata('id') == 48 && $data['EmployeeDepartmentID'] == 12 && $data['EmployeeID'] != '1') || ($this->session->userdata('id') == 48 && $data['EmployeeID'] == '50')) {// David Rights given for Sales team on 02-Feb-2017 (Brian Email)
                $overridecondition = '1';
            }
            if ($this->session->userdata('id') == 50 && $data['EmployeeDepartmentID'] == 12 && $data['EmployeeID'] != '1') {// Stella Rights given for Sales team on 02-Feb-2017 (Brian Email)
                $overridecondition = '1';
            }

            if (($this->session->userdata('id') == 4 ||
                    $this->session->userdata('id') == 5 ||
                    $this->session->userdata('id') == 6 ||
                    $this->session->userdata('id') == 7 ||
                    $this->session->userdata('id') == 8 ||
                    $this->session->userdata('id') == 9 ||
                    $this->session->userdata('id') == 58 ||
                    $this->session->userdata('id') == 89 ||
                    $this->session->userdata('id') == 90 ||
                    $this->session->userdata('id') == 91 ||
                    $this->session->userdata('id') == 92 ||
                    $this->session->userdata('id') == 93 ||
                    $this->session->userdata('id') == 94 ||
                    $this->session->userdata('id') == 95 ||
                    $this->session->userdata('id') == 98 ||
                    $this->session->userdata('id') == 102 ||
                    $this->session->userdata('id') == 112 ||
                    $this->session->userdata('id') == 127 ||
                    $this->session->userdata('id') == 128 ||
                    $this->session->userdata('id') == 498) && ($data['EmployeeDepartmentID'] == 15 || $data['EmployeeDepartmentID'] == 36)) { //Operations   (Team Lead / Supervisor)
                $overridecondition = '1';
            }

            if ($this->session->userdata('id') == 44 && ($data['EmployeeID'] == '165' || $data['EmployeeID'] == '386' || $data['EmployeeID'] == '397')) { //Qasim Javed Butt Team Lead / Supervisor
                $overridecondition = '1';
            }

            if ($this->session->userdata('id') == 37 && ($data['EmployeeID'] == '165' || $data['EmployeeID'] == '386' || $data['EmployeeID'] == '397')) { //Noreen Nusrat Team Lead / Supervisor
                $overridecondition = '1';
            }

            if ($this->session->userdata('id') == 228 && $data['EmployeeDepartmentID'] == 1 && $employee_role['RoleID'] != 1 && $employee_role['RoleID'] != 2 && $employee_role['RoleID'] != 3) { //Genevieve Carolino (Jenny Sanders) Team Lead / Supervisor
                $overridecondition = '1';
            }

            if ($this->session->userdata('id') == 48 && $data['EmployeeID'] == '540') {//Daniel David (David Cruze)   (Team Lead / Supervisor)
                $overridecondition = '1';
            }
            if ($this->session->userdata('id') == 48 && ($data['EmployeeDepartmentID'] == 4 || $data['EmployeeDepartmentID'] == 10)) {//Daniel David (David Cruze)   (Team Lead / Supervisor)
                $overridecondition = '1';
            }
            if ($this->session->userdata('id') == 65 && ($data['EmployeeDepartmentID'] == 6 || $data['EmployeeDepartmentID'] == 10)) { //Mushahid Muhammad (Jacob Williams)   (Training Team Lead / Supervisor)
                $overridecondition = '1';
            }
            if ($this->session->userdata('id') == 7 && $data['EmployeeDepartmentID'] == '1' && $employee_role['RoleID'] != 1 && $employee_role['RoleID'] != 2) {//Alex   (Team Lead / Supervisor)
                $overridecondition = '1';
            }
            if ($this->session->userdata('id') == 127 && $data['EmployeeDepartmentID'] == '1' && $employee_role['RoleID'] != 1 && $employee_role['RoleID'] != 2) {//Jasper   (Team Lead / Supervisor)
                $overridecondition = '1';
            }
            //if ($this->session->userdata('id') == 77) { //Muhammad Abbas Ali (Ben McAllister)    ( Client Services Team Lead / Supervisor)
            //if ($data['EmployeeID'] != 77 && $data['EmployeeID'] != 599 && $data['EmployeeID'] != 605  && $data['EmployeeID'] != 652) {
            //$data['result'] = 'Sorry! rights are permitted to see this person&rsquo;s details.';
            //}
            //}
            if ($this->session->userdata('id') == 177 && $data['EmployeeID'] == '320') {//Faryal Rafique   (Project Manager)
                $overridecondition = '1';
            }
            /* if ($this->session->userdata('id') == 48 && $data['EmployeeID'] == '51') {//Daniel David (David Cruze)   (Team Lead / Supervisor)
              $overridecondition = '0';
              } */

            /* Multiple Department Rights Code End */

            if (($overridecondition == '0') && ((count($data['employee']) == 0) || ($employee_role['RoleID'] <= $this->session->userdata('role') && $data['EmployeeID'] != $this->session->userdata('id')) || ( $start_year == '1970' || $end_year == '1970' || $start_date <= '0' || $start_date > '31' || $end_date <= '0' || $end_date > '31' || $start_month <= '0' || $start_month > '12' || $end_month <= '0' || $end_month > '12') || ($data['EmployeeDepartmentID'] != $data['AdminDepartmentID']))) {/*  || ($this->session->userdata('role') != 1 && $data['EmployeeDepartmentID'] != $data['AdminDepartmentID']) */
                if ($start_year == '1970' || $end_year == '1970' || $start_date <= '0' || $start_date > '31' || $end_date <= '0' || $end_date > '31' || $start_month <= '0' || $start_month > '12' || $end_month <= '0' || $end_month > '12') {
                    $data['result'] = 'Sorry! Invalid Date';
                } else if (count($data['employee']) == 0) {
                    $data['result'] = 'Sorry! No Employee Found Against This ID.';
                } else if ($employee_role['CurrentFlag'] == 0) {
                    $data['result'] = 'Sorry! Employee has been deactivated';
                } else if ($data['EmployeeDepartmentID'] != $data['AdminDepartmentID']) {

                    $data['result'] = 'Sorry! rights are permitted to see this person&rsquo;s details.';
                } else {
                    if ($employee_role['RoleID'] != '') {
                        $data['result'] = 'Sorry! rights are permitted to see this person&rsquo;s details.';
                    } else {
                        $data['result'] = 'Sorry! no role has been assigned to this employee.';
                    }
                }
            } else {
                //---------------------------Shift Details with Attendance & Date Management of an employee-------------------------------
                foreach ($data['dates'] as $key => $date) {

                    for ($i = $count; $i >= 0; $i--) {
                        if (date('Y-m-d', strtotime($date['date'])) > date('Y-m-d', strtotime($data['department_history'][$i]['EndDate'])) && $data['department_history'][$i]['EndDate'] != '0000-00-00' && $data['department_history'][$i]['EndDate'] != '') {

                            $count--;
                        }
                    }

                    if (date('Y-m-d', strtotime($date['date'])) > date('Y-m-d', strtotime($data['department_history'][$count]['StartDate'])) && date('Y-m-d', strtotime($date['date'])) > date('Y-m-d', strtotime($data['department_history'][$count]['EndDate'])) && $data['department_history'][$count]['EndDate'] != '0000-00-00' && $data['department_history'][$count]['EndDate'] != '') {

                        if (date('Y-m-d', strtotime($date['date'])) == date('Y-m-d', strtotime($data['getattendance'][$a]['Date']))) {

                            // if date loop is equal to attendance date
                            // calling helper function to get attendance details
                            $data['attendances'][$key] = get_attendance_details($data['getattendance'][$a], $date, $data['EmployeeCompanyID'], $data['EmployeeDepartmentID'], $data['employee'], $TimeZoneDifference);
                            $a++;
                        } else {
                            // calling helper function to get attendance details
                            $data['attendances'][$key] = get_off_attendance_details($data['getattendance'][$a], $data['department_history_shifts'], $date, $data['EmployeeCompanyID'], $data['EmployeeDepartmentID'], $data['employee']);
                        }
                        $countdates = count($data['dates']) - 1;
                        if (date('Y-m-d', strtotime($date['date'])) == date('Y-m-d', strtotime($data['dates'][$countdates])) && date('Y-m-d', strtotime($date['date'])) > date('Y-m-d', strtotime($data['department_history'][$count]['StartDate'])) && date('Y-m-d', strtotime($date['date'])) > date('Y-m-d', strtotime($data['department_history'][$count]['EndDate']))) {
                            $count--;
                        }
                    } else if (date('Y-m-d', strtotime($date['date'])) >= date('Y-m-d', strtotime($data['department_history'][$count]['StartDate'])) && date('Y-m-d', strtotime($date['date'])) <= date('Y-m-d', strtotime($data['department_history'][$count]['EndDate'])) && $data['department_history'][$count]['EndDate'] != '0000-00-00' && $data['department_history'][$count]['EndDate'] != '') {

                        if (date('Y-m-d', strtotime($date['date'])) == date('Y-m-d', strtotime($data['getattendance'][$a]['Date']))) {

                            // if date loop is equal to attendance date
                            // calling helper function to get attendance details
                            $data['attendances'][$key] = get_attendance_details($data['getattendance'][$a], $date, $data['EmployeeCompanyID'], $data['EmployeeDepartmentID'], $data['employee'], $TimeZoneDifference);
                            $a++;
                        } else {
                            // calling helper function to get attendance details
                            $data['attendances'][$key] = get_off_attendance_details($data['getattendance'][$a], $data['department_history_shifts'], $date, $data['EmployeeCompanyID'], $data['EmployeeDepartmentID'], $data['employee']);
                        }

                        if (date('Y-m-d', strtotime($date['date'])) > date('Y-m-d', strtotime($data['department_history'][$count]['EndDate']))) {
                            $count--;
                        }
                    } else if (date('Y-m-d', strtotime($date['date'])) >= date('Y-m-d', strtotime($data['department_history'][$count]['StartDate'])) && $data['department_history'][$count]['EndDate'] == '0000-00-00' && $data['department_history'][$count]['EndDate'] != '') {

                        if (date('Y-m-d', strtotime($date['date'])) == date('Y-m-d', strtotime($data['getattendance'][$a]['Date']))) {

                            // if date loop is equal to attendance date
                            // calling helper function to get attendance details
                            $data['attendances'][$key] = get_attendance_details($data['getattendance'][$a], $date, $data['EmployeeCompanyID'], $data['EmployeeDepartmentID'], $data['employee'], $TimeZoneDifference);
                            $a++;
                        } else {
                            // calling helper function to get attendance details
                            $data['attendances'][$key] = get_off_attendance_details($data['getattendance'][$a], $data['department_history_shifts'], $date, $data['EmployeeCompanyID'], $data['EmployeeDepartmentID'], $data['employee']);
                        }
                    } else {
                        // calling helper function to get attendance details
                        $data['attendances'][$key] = get_off_attendance_details($data['getattendance'][$a], $data['department_history_shifts'], $date, $data['EmployeeCompanyID'], $data['EmployeeDepartmentID'], $data['employee']);
                    }
                }
                /* ----------------------------------------------------- */
            }
        } else { /* || $employee_role['CurrentFlag'] == 0 */
            if ((count($data['employee']) == 0) || $start_year == '1970' || $end_year == '1970' || $start_date <= '0' || $start_date > '31' || $end_date <= '0' || $end_date > '31' || $start_month <= '0' || $start_month > '12' || $end_month <= '0' || $end_month > '12') {

                if ($start_year == '1970' || $end_year == '1970' || $start_date <= '0' || $start_date > '31' || $end_date <= '0' || $end_date > '31' || $start_month <= '0' || $start_month > '12' || $end_month <= '0' || $end_month > '12') {
                    $data['result'] = 'Sorry! Invalid Date';
                } else if (count($data['employee']) == 0) {
                    $data['result'] = 'Sorry! No Employee Found Against This ID.';
                } else if ($employee_role['CurrentFlag'] == 0) {
                    $data['result'] = 'Sorry! Employee has been deactivated';
                } else {
                    if ($employee_role['RoleID'] != '') {
                        $data['result'] = 'Sorry! rights are permitted to see this person&rsquo;s details';
                    } else {
                        $data['result'] = 'Sorry! no role has been assigned to this employee';
                    }
                }
            } else {
                //---------------------------Shift Details with Attendance & Date Management-------------------------------
                foreach ($data['dates'] as $key => $date) {

                    for ($i = $count; $i >= 0; $i--) {
                        if (date('Y-m-d', strtotime($date['date'])) > date('Y-m-d', strtotime($data['department_history'][$i]['EndDate'])) && $data['department_history'][$i]['EndDate'] != '0000-00-00' && $data['department_history'][$i]['EndDate'] != '') {

                            $count--;
                        }
                    }

                    if (date('Y-m-d', strtotime($date['date'])) > date('Y-m-d', strtotime($data['department_history'][$count]['StartDate'])) && date('Y-m-d', strtotime($date['date'])) > date('Y-m-d', strtotime($data['department_history'][$count]['EndDate'])) && $data['department_history'][$count]['EndDate'] != '0000-00-00' && $data['department_history'][$count]['EndDate'] != '') {
                        if (date('Y-m-d', strtotime($date['date'])) == date('Y-m-d', strtotime($data['getattendance'][$a]['Date']))) {

                            // if date loop is equal to attendance date
                            // calling helper function to get attendance details
                            $data['attendances'][$key] = get_attendance_details($data['getattendance'][$a], $date, $data['EmployeeCompanyID'], $data['EmployeeDepartmentID'], $data['employee'], $TimeZoneDifference);
                            $a++;
                        } else {
                            // calling helper function to get attendance details
                            $data['attendances'][$key] = get_off_attendance_details($data['getattendance'][$a], $data['department_history_shifts'], $date, $data['EmployeeCompanyID'], $data['EmployeeDepartmentID'], $data['employee']);
                        }
                        $countdates = count($data['dates']) - 1;
                        if (date('Y-m-d', strtotime($date['date'])) == date('Y-m-d', strtotime($data['dates'][$countdates])) && date('Y-m-d', strtotime($date['date'])) > date('Y-m-d', strtotime($data['department_history'][$count]['StartDate'])) && date('Y-m-d', strtotime($date['date'])) > date('Y-m-d', strtotime($data['department_history'][$count]['EndDate']))) {
                            $count--;
                        }
                    } else if (date('Y-m-d', strtotime($date['date'])) >= date('Y-m-d', strtotime($data['department_history'][$count]['StartDate'])) && date('Y-m-d', strtotime($date['date'])) <= date('Y-m-d', strtotime($data['department_history'][$count]['EndDate'])) && $data['department_history'][$count]['EndDate'] != '0000-00-00' && $data['department_history'][$count]['EndDate'] != '') {

                        if (date('Y-m-d', strtotime($date['date'])) == date('Y-m-d', strtotime($data['getattendance'][$a]['Date']))) {

                            // if date loop is equal to attendance date
                            // calling helper function to get attendance details
                            $data['attendances'][$key] = get_attendance_details($data['getattendance'][$a], $date, $data['EmployeeCompanyID'], $data['EmployeeDepartmentID'], $data['employee'], $TimeZoneDifference);
                            $a++;
                        } else {
                            // calling helper function to get attendance details
                            $data['attendances'][$key] = get_off_attendance_details($data['getattendance'][$a], $data['department_history_shifts'], $date, $data['EmployeeCompanyID'], $data['EmployeeDepartmentID'], $data['employee']);
                        }

                        if (date('Y-m-d', strtotime($date['date'])) > date('Y-m-d', strtotime($data['department_history'][$count]['EndDate']))) {
                            $count--;
                        }
                    } else if (date('Y-m-d', strtotime($date['date'])) >= date('Y-m-d', strtotime($data['department_history'][$count]['StartDate'])) && $data['department_history'][$count]['EndDate'] == '0000-00-00' && $data['department_history'][$count]['EndDate'] != '') {
                        if (date('Y-m-d', strtotime($date['date'])) == date('Y-m-d', strtotime($data['getattendance'][$a]['Date']))) {

                            // if date loop is equal to attendance date
                            // calling helper function to get attendance details
                            $data['attendances'][$key] = get_attendance_details($data['getattendance'][$a], $date, $data['EmployeeCompanyID'], $data['EmployeeDepartmentID'], $data['employee'], $TimeZoneDifference);
                            $a++;
                        } else {
                            // calling helper function to get attendance details
                            $data['attendances'][$key] = get_off_attendance_details($data['getattendance'][$a], $data['department_history_shifts'], $date, $data['EmployeeCompanyID'], $data['EmployeeDepartmentID'], $data['employee']);
                            //echo '<pre>'; print_r($data['attendances'][$key]); echo '</pre>'; exit;
                        }
                    } else {
                        // calling helper function to get attendance details
                        $data['attendances'][$key] = get_off_attendance_details($data['getattendance'][$a], $data['department_history_shifts'], $date, $data['EmployeeCompanyID'], $data['EmployeeDepartmentID'], $data['employee']);
                    }
                }
                /* ----------------------------------------------------- */
            }
        }

        /* ----------------------------------------- code for swapping days -------------------------------------------- */
        //if ($this->session->userdata('id') == '189' || $this->session->userdata('id') == '170' || $this->session->userdata('id') == '321' || $this->session->userdata('id') == '339') {
        if ($this->session->userdata('DepartmentID') == '1' || $data['EmployeeDepartmentID'] == '1' || $this->session->userdata('role') == '1') {
            foreach ($data['dates'] as $dates) {
                foreach ($data['attendances'] as $key => $attendance) {

                    if (($attendance['Date'] == $dates['applied_swaps']['SwapWith']) && ($dates['applied_swaps']['ApprovedBy'] != '')) {
                        $SwapWithDate[] = $dates['applied_swaps']['SwapWith'];
                        $withkey[] = $key;
                        $SwapWithEndDate[] = $attendance['EndDate'];
                    }

                    if (($attendance['Date'] == $dates['applied_swaps']['SwapFrom']) && ($dates['applied_swaps']['ApprovedBy'] != '')) {
                        $SwapFromDate[] = $dates['applied_swaps']['SwapFrom'];
                        $fromkey[] = $key;
                        $SwapFromEndDate[] = $dates['applied_swaps']['SwapFrom'];

                        $fromkeySwapID[] = $attendance['SwapID'];
                        $fromkeySwapFrom[] = $attendance['SwapFrom'];
                        $fromkeySwapWith[] = $attendance['SwapWith'];
                        $fromkeySwapComments[] = $attendance['SwapComments'];
                        $fromkeySwapRequestedTo[] = $attendance['SwapRequestedTo'];
                        $fromkeySwapRequestedDate[] = $attendance['SwapRequestedDate'];
                        $fromkeySwapApprovedBy[] = $attendance['SwapApprovedBy'];
                        $fromkeySwapApprovedDate[] = $attendance['SwapApprovedDate'];
                        $fromkeySwapApprovalComments[] = $attendance['SwapApprovalComments'];
                    }
                }
            }

            if ($SwapWithDate) {
                foreach ($SwapWithDate as $key => $SwapWith) {
                    if ($this->session->userdata('id') == '189') {
                        /* echo '<br><br><br>key '.$key;
                          echo '<br>withkey '.$withkey[$key];
                          echo '<br>SwapWithDate '.$SwapWithDate[$key];
                          echo '<br>SwapFromDate '.$SwapFromDate[$key];
                          echo '<br>fromkey '.$fromkey[$key]; */
                        //echo '<pre>'; print_r($data['attendances'][$fromkey[$key]]); echo '</pre>';
                    }
                    $tmpswap = $data['attendances'][$withkey[$key]]; // 1
                    $data['attendances'][$withkey[$key]] = $data['attendances'][$fromkey[$key]]; // 4
                    $data['attendances'][$withkey[$key]]['Date'] = $SwapWithDate[$key];
                    $data['attendances'][$withkey[$key]]['EndDate'] = $SwapWithEndDate[$key];
                    $data['attendances'][$withkey[$key]]['Day'] = date('l', strtotime($SwapWithDate[$key]));
                    $data['attendances'][$withkey[$key]]['WorkingDay'] = 0;
                    $data['attendances'][$fromkey[$key]] = $tmpswap;
                    $data['attendances'][$fromkey[$key]]['Date'] = $SwapFromDate[$key];
                    $data['attendances'][$fromkey[$key]]['EndDate'] = $SwapFromEndDate[$key];
                    $data['attendances'][$fromkey[$key]]['Day'] = date('l', strtotime($SwapFromDate[$key]));
                    $data['attendances'][$fromkey[$key]]['WorkingDay'] = 1;

                    $data['attendances'][$fromkey[$key]]['SwapID'] = $fromkeySwapID[$key];
                    $data['attendances'][$fromkey[$key]]['SwapFrom'] = $fromkeySwapFrom[$key];
                    $data['attendances'][$fromkey[$key]]['SwapWith'] = $fromkeySwapWith[$key];
                    $data['attendances'][$fromkey[$key]]['SwapComments'] = $fromkeySwapComments[$key];
                    $data['attendances'][$fromkey[$key]]['SwapRequestedTo'] = $fromkeySwapRequestedTo[$key];
                    $data['attendances'][$fromkey[$key]]['SwapRequestedDate'] = $fromkeySwapRequestedDate[$key];
                    $data['attendances'][$fromkey[$key]]['SwapApprovedBy'] = $fromkeySwapApprovedBy[$key];
                    $data['attendances'][$fromkey[$key]]['SwapApprovedDate'] = $fromkeySwapApprovedDate[$key];
                    $data['attendances'][$fromkey[$key]]['SwapApprovalComments'] = $fromkeySwapApprovalComments[$key];
                }
            }
            if ($this->session->userdata('id') == '129') {
                /* echo 'getattendance<pre>'; print_r($data['attendances']); echo '</pre>'; exit; */
            }
        }

        //}
        /* ----------------------------------- code for swapping days ends -------------------------------------------------------- */


        /* echo 'attendances<pre>'; print_r($data['attendances']); echo '</pre>';  exit; */
        $countCompensate = 0;
        foreach ($data['attendances'] as $key => $attendance) {
            if ($attendance['compensation_days']['ID']) {
                $countCompensate++;
            }
        }
        $data['countCompensate'] = $countCompensate;

        $data["js_include"][] = JS_BASE_URL . "/attendance.js";
        /* echo 'attendances<pre>'; print_r($data['attendances']); echo '</pre>'; exit; */
        $this->load->view('attendance/get_attendance', $data);
    }

    public function attendancepopup($ID) {
        if (!($this->session->userdata('role') == '4')) {

            if ($this->session->userdata('id') == $this->uri->segment(4)) {
                redirect(File_BASE_URL . '/attendance/index');
            }

            // Models File Include
            $this->load->model('Shift_details_model');
            $this->load->model('Employee_model');
            $this->load->model('Department_history_model');
            $this->load->model('Day_model');
            $this->load->model('Attendance_model');
            $this->load->model('Attendance_log_model');
            $this->load->model('Holidays_model');

            if (isset($_COOKIE['clientTimeZone'])) {
                $timezone = $_COOKIE['clientTimeZone'];
            } else {
                $timezone = 'UP5';
            }

            $data = array();

            /* Getting the prev attendancce details */
            $EmployeeID = $this->uri->segment(4);
            $ShiftID = $this->uri->segment(5);
            $data['Day'] = $Day = $this->uri->segment(8);

            $dayid = $this->Day_model->get_dayid($Day);
            $prevemployeeshift = $this->Department_history_model->get_employee_shift($ShiftID, $EmployeeID, $dayid);

            $data['AID'] = $ID;
            $data['Date'] = $this->uri->segment(6);
            $data['ODate'] = $this->uri->segment(7);
            $data['WorkingDay'] = $this->uri->segment(9);

            $dayid = $this->Day_model->get_dayid($data['Day']);
            $data['employee'] = $this->Employee_model->get_employee($EmployeeID);
            //echo '<pre>'; print_r($data); echo '</pre>';
            // other than super admin only reporting to manager can modify attendance 2018-03-06
            if ($this->session->userdata('role') != '1') {
                // if department are Operations (Philippines & Pakistan) and Daniel or Thomas 2018-04-02
                if (($data['employee']['DepartmentID'] == '1' || $data['employee']['DepartmentID'] == '4' || $data['employee']['DepartmentID'] == '6' || $data['employee']['DepartmentID'] == '10' || $data['employee']['DepartmentID'] == '15' || $data['employee']['DepartmentID'] == '36') && ($this->session->userdata('id') == '2' || $this->session->userdata('id') == '3')) {
                    
                } else if (($data['employee']['DepartmentID'] == '26') && ($this->session->userdata('id') == '179' || $this->session->userdata('id') == '180')) {
                    
                } else {
                    if ($data['employee']['ReportingTo'] != $this->session->userdata('id')) {
                        redirect(File_BASE_URL . '/attendance/index');
                    }
                }
            }
            // other than super admin only reporting to manager can modify break 2018-03-06
            // code for salary lock 2018-03-02        
            $month = date('m', strtotime($data['Date']));
            $year = date('Y', strtotime($data['Date']));

            $month_start_date = date("$year-$month-21");
            $year_start_date = date("$year-12-21");

            if (strtotime($data['Date']) >= strtotime($month_start_date)) {
                $month = $month + 1;
                if ($month == 13) {
                    $month = 1;
                }
            }
            if (strtotime($data['Date']) >= strtotime($year_start_date)) {
                $year = $year + 1;
            }

            $data['SalaryMonth'] = $month;
            $data['SalaryYear'] = $year;
            $data['DepartmentID'] = $data['employee']['DepartmentID'];

            //calling helper function to get current month salary lock data
            $salary_lock = department_salary_lock_data($data);

            // if lock status is y then salary is locked else not locked
            $lock_status = 'n';
            foreach ($salary_lock as $lock) {
                if ($lock['DepartmentID'] == $data['DepartmentID']) {
                    $lock_status = $lock['LockStatus'];
                }
            }
            //echo '<br>lock_status ' . $lock_status;
            if ($lock_status == 'y') {
                redirect(File_BASE_URL . '/attendance/index');
            }
            //  code for salary lock 2018-03-02

            $data['employeeshift'] = $this->Department_history_model->get_employee_shift($ShiftID, $EmployeeID, $dayid);

            $data['holiday'] = $this->Holidays_model->get_holiday($data['Date'], $data['employeeshift']['AllowedHolidayTypeID']);

            $data['WorkingDay'] = $data['employeeshift']['WorkingDay'];

            $ShiftStartTime = $data['employeeshift']['ShiftStartTime'];
            $ShiftEndTime = $data['employeeshift']['ShiftEndTime'];

            $FromTimeZone = 'GMT';
            $ToTimeZone = $timezone;
            $data['employeeshift']['ShiftStartTime'] = ConvertTimeZone($ShiftStartTime, $FromTimeZone, $ToTimeZone);
            $data['employeeshift']['ShiftEndTime'] = ConvertTimeZone($ShiftEndTime, $FromTimeZone, $ToTimeZone);


            $data['employeeshift']['InTime'] = str_replace("_", " ", $this->uri->segment(10));
            $data['employeeshift']['OutTime'] = str_replace("_", " ", $this->uri->segment(11));

            // if there is no attendance then no allowed break
            if ($data['AID'] == 0) {

                $ShiftsHours = CalculateTimeDifferencce($ShiftStartTime, $ShiftEndTime);
                $WorkedHours = $ShiftsHours;

                $breakallocationtime = (($ShiftsHours / 2) - (($ShiftsHours * 6.67 / 60) / 2));

                if ($WorkedHours >= $breakallocationtime) {
                    $AllowedBreak = ($ShiftsHours * 6.67 / 60);
                    if ($this->input->post('Day') == 'Friday') {
                        //friday break is not allowed for Operations, QC, QA, Training, CSA, Operations Philippines, QC Philippines after 2018-04-21 added 2018-05-11
                        if ((date('Y-m-d', strtotime($data['Date'])) >= date('Y-m-d', strtotime('2018-04-21'))) && ($data['DepartmentID'] == 1 || $data['DepartmentID'] == 4 || $data['DepartmentID'] == 6 || $data['DepartmentID'] == 10 || $data['DepartmentID'] == 15 || $data['DepartmentID'] == 34 || $data['DepartmentID'] == 36)) {
                            
                        } else {
                            $FridayPrayerTime = strtotime("01:00 pm");   // 1467205200 means PST 1PM 
                            if (strtotime($InTime) < $FridayPrayerTime) { // means employee comes before 1PM PST jo 1 bajey sey pehlay aaye ga
                                $AllowedBreak = '1800' + $AllowedBreak;
                            }
                        }
                    }
                } else {
                    $AllowedBreak = 0;
                }

                // if there is no attendance then no allowed break
                $AllowedBreak = round($AllowedBreak);
                $ShiftEndTime = date("h:i a", strtotime("-$AllowedBreak seconds", strtotime($ShiftEndTime)));
                $data['employeeshift']['ShiftEndTime'] = ConvertTimeZone($ShiftEndTime, $FromTimeZone, $ToTimeZone);
            }
            /**/
            if ($this->input->post()) {
                $data_to_save = array();

                $data_to_save['EmployeeID'] = $this->input->post('EmployeeID');
                $data_to_save['ShiftID'] = $this->input->post('ShiftID');

                //$data_to_save['Date'] = $this->input->post('date');
                //get the current day date
                $currentdaydate = $this->input->post('date');

                //get the past day date
                $pastdaydate = date('Y-m-d', strtotime('-1 day', strtotime($currentdaydate)));

                //get the next day date
                $nextdaydate = date('Y-m-d', strtotime('+1 day', strtotime($currentdaydate)));

                $ShiftStartTime = $this->input->post('ShiftStartTime');
                $ShiftEndTime = $this->input->post('ShiftEndTime');

                $InTime = $this->input->post('timein');

                if (date('Y-m-d', strtotime($currentdaydate)) != date('Y-m-d')) {
                    $OutTime = $this->input->post('timeout');
                }

                //$data_to_save['TotalTime'] = CalculateTimeDifferencce($InTime, $OutTime);

                $FromTimeZone = 'GMT';
                $ToTimeZone = $timezone;
                $TimeZoneDifference = GetTimeZoneDifference($FromTimeZone, $ToTimeZone);

                $FromTimeZone = $timezone;
                $ToTimeZone = 'GMT';
                $data_to_save['InTime'] = ConvertTimeZone($InTime, $FromTimeZone, $ToTimeZone);
                $data_to_save['InTime_New'] = date('H:i:s', strtotime($data_to_save['InTime']));
                if (date('Y-m-d', strtotime($currentdaydate)) != date('Y-m-d')) {
                    $data_to_save['OutTime'] = ConvertTimeZone($OutTime, $FromTimeZone, $ToTimeZone);
                    $data_to_save['OutTime_New'] = date('H:i:s', strtotime($data_to_save['OutTime']));
                }


                $ShiftStartTime = ConvertTimeZone($ShiftStartTime, $FromTimeZone, $ToTimeZone);
                $ShiftEndTime = ConvertTimeZone($ShiftEndTime, $FromTimeZone, $ToTimeZone);

                $data_to_save['Date'] = $currentdaydate;

                if (date('Y-m-d', strtotime($data_to_save['Date'])) != date('Y-m-d')) {
                    $data_to_save['EndDate'] = $data_to_save['Date'];
                }

                /* $data_to_save['Status'] = 1; */
                $data_to_save['AddedBy'] = $this->session->userdata('id');
                $data_to_save['Comments'] = str_replace("'", "", $this->input->post('comments'));
                $data_to_save['CheckedBy'] = 0;
                $data_to_save['Source'] = 'website';
                $data_to_save['AttendanceCrash'] = 'ManualLogout';

                $data_to_save['LastActivityDate'] = date("Y-m-d H:i:s");
                $data_to_save['ModifiedDate'] = date("Y-m-d H:i:s");

                $this->db->where('Date', $data_to_save['Date']);
                $this->db->where('EmployeeID', $data_to_save['EmployeeID']);
                $query = $this->db->get('attendence');
                $attendance_exist = 0;
                if ($query->num_rows() > 0) {
                    $attendance_exist = 1;
                } else {
                    $attendance_exist = 0;
                }

                if ($data_to_save['EmployeeID'] != $data_to_save['AddedBy']) {
                    $data_to_save['IsAmended'] = 1;
                }

                if ($this->input->post('AID') == 0 && $attendance_exist == 0) {
                    $data_to_save['LogID'] = $this->Attendance_model->save_attendance($data_to_save);
                } else {
                    /* checking attendance is amended or not */
                    if (($prevemployeeshift['InTime'] != $data_to_save['InTime']) || ($prevemployeeshift['OutTime'] != $data_to_save['OutTime'])) {
                        $data_to_save['IsAmended'] = 1;
                    }
                    /**/

                    $data_to_save['ID'] = $this->input->post('AID');
                    $this->Attendance_model->update_attendance($data_to_save);
                    $data_to_save['LogID'] = $this->input->post('AID');
                }
                unset($data_to_save['ID']);
                unset($data_to_save['IsAmended']);
                /*  $existingattendancelogdata = $this->Attendance_log_model->get_attendancelog_data($data_to_save);
                  if($existingattendancelogdata == false){ */
                $this->Attendance_log_model->save_attendancelog($data_to_save);
                /* } */

                //unset($data_to_save['TotalTime']);
                $data_to_save['InTime'] = $InTime;
                $data_to_save['OutTime'] = $OutTime;
                $data_to_save['AID'] = $this->input->post('AID');
                $data_to_save['AttendenceID'] = $data_to_save['LogID'];
                $data_to_save['ShiftStartTime'] = $this->input->post('ShiftStartTime');

                $data_to_save['ShiftEndTime'] = $this->input->post('ShiftEndTime');

                $data_to_save['WorkingDay'] = $this->input->post('WorkingDay');

                /* LOGGER ****************************** */
                //if ($this->session->userdata('id') == '189') {
                $starttime = currentTime();
                $logfilename = $data_to_save['EmployeeID'];
                if ($ID == 0) {
                    $attendance_modify = 'Added';
                } else {
                    $attendance_modify = 'Modify';
                }
                logger_attendance_modify("*************** Attendance $attendance_modify By  LES ID: " . $data_to_save['AddedBy'] . " on $starttime ******************************************", $logfilename);
                logger_attendance_modify("Before Modification Attendance Info : Attendance Date: " . $this->uri->segment(6) . " Time In: " . str_replace("_", " ", $this->uri->segment(10)) . " Time Out: " . str_replace("_", " ", $this->uri->segment(11)), $logfilename);
                logger_attendance_modify("After  Modification Attendance Info : Attendance Date: " . $data_to_save['Date'] . " Time In: $InTime Time Out: $OutTime", $logfilename);
                logger_attendance_modify("************************************************************************************************************************************", $logfilename);
                //}

                /*------------*****************LOGGER ENDS****************************** */

                /* sending email to Employee */

                $email_to = $this->Employee_model->get_employee_email($data_to_save['EmployeeID']);
                $email_from = $this->Employee_model->get_employee_email($this->session->userdata('id'));

                /* echo '<pre>'; print_r($email_to); echo '</pre>'; exit; */

                $sender_email = $email_from['OfficialEmailAddress'];
                $sender_name = $email_from['FirstName'] . ' ' . $email_from['LastName'];

                if ($email_from['PseudoFirstName']) {
                    $sender_name .= ' (' . $email_from['PseudoFirstName'] . ' ' . $email_from['PseudoLastName'] . ')';
                }
                $sender_designation = $email_from['JobTitle'];

                $receiver_email = $email_to['OfficialEmailAddress'];
                $receiver_name = $email_to['FirstName'] . ' ' . $email_to['LastName'];
                if ($email_to['PseudoFirstName']) {
                    $receiver_name .= ' (' . $email_to['PseudoFirstName'] . ' ' . $email_to['PseudoLastName'] . ')';
                }

                $FromTimeZone = $timezone;
                $ToTimeZone = 'GMT';

                $ITime = ConvertTimeZone($this->input->post('timein'), $FromTimeZone, $ToTimeZone);
                $OTime = ConvertTimeZone($this->input->post('timeout'), $FromTimeZone, $ToTimeZone);

                $FromTimeZone = 'GMT';
                $ToTimeZone = 'UP5';

                $InTime = ConvertTimeZone($ITime, $FromTimeZone, $ToTimeZone);
                $OutTime = ConvertTimeZone($OTime, $FromTimeZone, $ToTimeZone);

                $modifiedattendancedetails = '<strong>Date: </strong>' . $data_to_save['Date'] . '<br><br><strong>Time In: </strong>' . $InTime . ' (PST)<br><strong>Time Out: </strong>' . $OutTime . ' (PST)<br><br><strong>Modification Comments: </strong>' . $data_to_save['Comments'] . '<br><br><strong>Modified By: </strong>' . $sender_name;

                //Sending Email	

                $subject = 'Attendance Modification of Date ' . $data_to_save['Date'];
                $message = "Dear $receiver_name,<br><br>Your attendance has been modified. Following are the details<br><br>$modifiedattendancedetails";
                $data_email = array();
                $data_email['email_body'] = $message;
                $message = $this->load->view('email-templates/default', $data_email, true);

                $this->load->helper('email');
                $this->load->library('email');

                $this->email->from('no-reply@liveadmins.net', 'LES');
                $this->email->to($receiver_email, $receiver_name);
                //$this->email->bcc('naveed.zulfaqar@maavratech.com, muhammad.imran@maavratech.com');
                $this->email->subject($subject);
                $this->email->message($message);
                $em = $this->email->send();

                //Sending Email again to sender	 

                $subject = 'Attendance Modification of Date ' . $data_to_save['Date'];
                $message = "Dear $sender_name,<br><br>You have modified the attendance of $receiver_name. Following are the details<br><br>$modifiedattendancedetails";
                $data_email = array();
                $data_email['email_body'] = $message;
                $message = $this->load->view('email-templates/default', $data_email, true);

                $this->load->helper('email');
                $this->load->library('email');

                $this->email->from('no-reply@liveadmins.net', 'LES');
                $this->email->to($sender_email, $sender_name);
                //$this->email->bcc('naveed.zulfaqar@maavratech.com, muhammad.imran@maavratech.com');
                $this->email->subject($subject);
                $this->email->message($message);
                $em = $this->email->send();

                //echo $this->email->print_debugger();
                /* sending email ends */
            }

            $this->load->view('attendance/attendancepopup', $data);
        } else {
            redirect(File_BASE_URL . '/shiftreports/index');
        }
    }

    public function add($ID) {
        if (!($this->session->userdata('role') == '4')) {

            if ($this->session->userdata('id') == $this->uri->segment(4)) {
                redirect(File_BASE_URL . '/attendance/index');
            }

            // Helper File Include
            $this->load->helper('string');
            $this->load->helper('form');

            // Libraries File Include
            $this->load->library('form_validation');

            // Models File Include
            $this->load->model('Shift_details_model');

            $this->load->model('Employee_model');
            $this->load->model('Department_history_model');
            $this->load->model('Day_model');
            $this->load->model('Attendance_model');
            $this->load->model('Attendance_log_model');
            $this->load->model('Holidays_model');

            if (isset($_COOKIE['clientTimeZone'])) {
                $timezone = $_COOKIE['clientTimeZone'];
            } else {
                $timezone = 'UP5';
            }

            $data = array();

            /* Getting the prev attendancce details */
            $EmployeeID = $this->uri->segment(4);
            $ShiftID = $this->uri->segment(5);
            $Day = $this->uri->segment(8);

            $dayid = $this->Day_model->get_dayid($Day);
            $prevemployeeshift = $this->Department_history_model->get_employee_shift($ShiftID, $EmployeeID, $dayid);
            /**/
            if ($this->input->post()) {
                $data['EmployeeID'] = $this->input->post('EmployeeID');
                $data['ShiftID'] = $this->input->post('ShiftID');

                //$data['Date'] = $this->input->post('date');
                //get the current day date
                $currentdaydate = $this->input->post('date');

                //get the past day date
                $pastdaydate = date('Y-m-d', strtotime('-1 day', strtotime($currentdaydate)));

                //get the next day date
                $nextdaydate = date('Y-m-d', strtotime('+1 day', strtotime($currentdaydate)));

                $ShiftStartTime = $this->input->post('ShiftStartTime');
                $ShiftEndTime = $this->input->post('ShiftEndTime');

                $InTime = $this->input->post('timein');

                if (date('Y-m-d', strtotime($currentdaydate)) != date('Y-m-d')) {
                    $OutTime = $this->input->post('timeout');
                }


                $data['TotalTime'] = CalculateTimeDifferencce($InTime, $OutTime);

                $FromTimeZone = 'GMT';
                $ToTimeZone = $timezone;
                $TimeZoneDifference = GetTimeZoneDifference($FromTimeZone, $ToTimeZone);

                $FromTimeZone = $timezone;
                $ToTimeZone = 'GMT';
                $data['InTime'] = ConvertTimeZone($InTime, $FromTimeZone, $ToTimeZone);
                if (date('Y-m-d', strtotime($currentdaydate)) != date('Y-m-d')) {
                    $data['OutTime'] = ConvertTimeZone($OutTime, $FromTimeZone, $ToTimeZone);
                }


                $ShiftStartTime = ConvertTimeZone($ShiftStartTime, $FromTimeZone, $ToTimeZone);
                $ShiftEndTime = ConvertTimeZone($ShiftEndTime, $FromTimeZone, $ToTimeZone);

                $data['Date'] = $currentdaydate;

                if (date('Y-m-d', strtotime($data['Date'])) != date('Y-m-d')) {
                    $data['EndDate'] = $data['Date'];
                }

                /* $data['Status'] = 1; */
                $data['AddedBy'] = $this->session->userdata('id');
                $data['Comments'] = str_replace("'", "", $this->input->post('comments'));
                $data['CheckedBy'] = 0;
                $data['Source'] = 'website';
                $data['AttendanceCrash'] = 'ManualLogout';

                $data['LastActivityDate'] = date("Y-m-d H:i:s");
                $data['ModifiedDate'] = date("Y-m-d H:i:s");

                $this->db->where('Date', $data['Date']);
                $this->db->where('EmployeeID', $data['EmployeeID']);
                $query = $this->db->get('attendence');
                $attendance_exist = 0;
                if ($query->num_rows() > 0) {
                    $attendance_exist = 1;
                } else {
                    $attendance_exist = 0;
                }

                if ($data['EmployeeID'] != $data['AddedBy']) {
                    $data['IsAmended'] = 1;
                }

                if ($this->input->post('AID') == 0 && $attendance_exist == 0) {
                    $data['LogID'] = $this->Attendance_model->save_attendance($data);
                } else {
                    /* checking attendance is amended or not */
                    if (($prevemployeeshift['InTime'] != $data['InTime']) || ($prevemployeeshift['OutTime'] != $data['OutTime'])) {
                        $data['IsAmended'] = 1;
                    }
                    /**/

                    $data['ID'] = $this->input->post('AID');
                    $this->Attendance_model->update_attendance($data);
                    $data['LogID'] = $this->input->post('AID');
                }
                unset($data['ID']);
                unset($data['IsAmended']);
                /*  $existingattendancelogdata = $this->Attendance_log_model->get_attendancelog_data($data);
                  if($existingattendancelogdata == false){ */
                $this->Attendance_log_model->save_attendancelog($data);
                /* } */

                unset($data['TotalTime']);
                $data['InTime'] = $InTime;
                $data['OutTime'] = $OutTime;
                $data['AID'] = $this->input->post('AID');
                $data['AttendenceID'] = $data['LogID'];
                $data['ShiftStartTime'] = $this->input->post('ShiftStartTime');

                $data['ShiftEndTime'] = $this->input->post('ShiftEndTime');

                $data['WorkingDay'] = $this->input->post('WorkingDay');

                if ($this->session->userdata('id') == '189') {
                    
                }

                /* ----------------- LOGGER -------------------- */
                //if ($this->session->userdata('id') == '189') {
                $starttime = currentTime();
                $logfilename = $data['EmployeeID'];
                if ($ID == 0) {
                    $attendance_modify = 'Added';
                } else {
                    $attendance_modify = 'Modify';
                }
                logger_attendance_modify("*************** Attendance $attendance_modify By  LES ID: " . $data['AddedBy'] . " on $starttime ******************************************", $logfilename);
                logger_attendance_modify("Before Modification Attendance Info : Attendance Date: " . $this->uri->segment(6) . " Time In: " . str_replace("_", " ", $this->uri->segment(10)) . " Time Out: " . str_replace("_", " ", $this->uri->segment(11)), $logfilename);
                logger_attendance_modify("After  Modification Attendance Info : Attendance Date: " . $data['Date'] . " Time In: $InTime Time Out: $OutTime", $logfilename);
                logger_attendance_modify("************************************************************************************************************************************", $logfilename);
                //}

                /* --------------LOGGER ENDS-------------------- */

                /* sending email to Employee */

                $email_to = $this->Employee_model->get_employee_email($data['EmployeeID']);
                $email_from = $this->Employee_model->get_employee_email($this->session->userdata('id'));

                /* echo '<pre>'; print_r($email_to); echo '</pre>'; exit; */

                $sender_email = $email_from['OfficialEmailAddress'];
                $sender_name = $email_from['FirstName'] . ' ' . $email_from['LastName'];

                if ($email_from['PseudoFirstName']) {
                    $sender_name .= ' (' . $email_from['PseudoFirstName'] . ' ' . $email_from['PseudoLastName'] . ')';
                }
                $sender_designation = $email_from['JobTitle'];

                $receiver_email = $email_to['OfficialEmailAddress'];
                $receiver_name = $email_to['FirstName'] . ' ' . $email_to['LastName'];
                if ($email_to['PseudoFirstName']) {
                    $receiver_name .= ' (' . $email_to['PseudoFirstName'] . ' ' . $email_to['PseudoLastName'] . ')';
                }

                $FromTimeZone = $timezone;
                $ToTimeZone = 'GMT';

                $ITime = ConvertTimeZone($this->input->post('timein'), $FromTimeZone, $ToTimeZone);
                $OTime = ConvertTimeZone($this->input->post('timeout'), $FromTimeZone, $ToTimeZone);

                $FromTimeZone = 'GMT';
                $ToTimeZone = 'UP5';

                $InTime = ConvertTimeZone($ITime, $FromTimeZone, $ToTimeZone);
                $OutTime = ConvertTimeZone($OTime, $FromTimeZone, $ToTimeZone);

                $modifiedattendancedetails = '<strong>Date: </strong>' . $data['Date'] . '<br><br><strong>Time In: </strong>' . $InTime . ' (PST)<br><strong>Time Out: </strong>' . $OutTime . ' (PST)<br><br><strong>Modification Comments: </strong>' . $data['Comments'] . '<br><br><strong>Modified By: </strong>' . $sender_name;

                //Sending Email	

                $subject = 'Attendance Modification of Date ' . $data['Date'];
                $message = "Dear $receiver_name,<br><br>Your attendance has been modified. Following are the details<br><br>$modifiedattendancedetails";
                $data_email = array();
                $data_email['email_body'] = $message;
                $message = $this->load->view('email-templates/default', $data_email, true);

                $this->load->helper('email');
                $this->load->library('email');

                $this->email->from('no-reply@liveadmins.net', 'LES');
                $this->email->to($receiver_email, $receiver_name);
                //$this->email->bcc('naveed.zulfaqar@maavratech.com, muhammad.imran@maavratech.com');
                $this->email->subject($subject);
                $this->email->message($message);
                $em = $this->email->send();

                //Sending Email again to sender	 

                $subject = 'Attendance Modification of Date ' . $data['Date'];
                $message = "Dear $sender_name,<br><br>You have modified the attendance of $receiver_name. Following are the details<br><br>$modifiedattendancedetails";
                $data_email = array();
                $data_email['email_body'] = $message;
                $message = $this->load->view('email-templates/default', $data_email, true);

                $this->load->helper('email');
                $this->load->library('email');

                $this->email->from('no-reply@liveadmins.net', 'LES');
                $this->email->to($sender_email, $sender_name);
                //$this->email->bcc('naveed.zulfaqar@maavratech.com, muhammad.imran@maavratech.com');
                $this->email->subject($subject);
                $this->email->message($message);
                $em = $this->email->send();

                //echo $this->email->print_debugger();
                /* sending email ends */
            }

            $data = array();

            $data['AID'] = $ID;
            $EmployeeID = $this->uri->segment(4);
            $ShiftID = $this->uri->segment(5);
            $data['Date'] = $this->uri->segment(6);
            $data['ODate'] = $this->uri->segment(7);
            $data['Day'] = $this->uri->segment(8);
            $data['WorkingDay'] = $this->uri->segment(9);




            $dayid = $this->Day_model->get_dayid($data['Day']);
            $data['employee'] = $this->Employee_model->get_employee($EmployeeID);
            $data['employeeshift'] = $this->Department_history_model->get_employee_shift($ShiftID, $EmployeeID, $dayid);

            $data['holiday'] = $this->Holidays_model->get_holiday($data['Date'], $data['employeeshift']['AllowedHolidayTypeID']);

            $data['WorkingDay'] = $data['employeeshift']['WorkingDay'];

            $ShiftStartTime = $data['employeeshift']['ShiftStartTime'];
            $ShiftEndTime = $data['employeeshift']['ShiftEndTime'];

            $FromTimeZone = 'GMT';
            $ToTimeZone = $timezone;
            $data['employeeshift']['ShiftStartTime'] = ConvertTimeZone($ShiftStartTime, $FromTimeZone, $ToTimeZone);
            $data['employeeshift']['ShiftEndTime'] = ConvertTimeZone($ShiftEndTime, $FromTimeZone, $ToTimeZone);


            $data['employeeshift']['InTime'] = str_replace("_", " ", $this->uri->segment(10));
            $data['employeeshift']['OutTime'] = str_replace("_", " ", $this->uri->segment(11));


            $this->load->view('attendance/add', $data);
        } else {
            redirect(File_BASE_URL . '/shiftreports/index');
        }
    }

    public function calculate_attendance_info() {

        // Models File Include
        $this->load->model('Employee_model');
        $this->load->model('Break_model');
        $this->load->model('Holidays_model');

        $ShiftStartTime = $this->input->post('ShiftStartTime');
        $ShiftEndTime = $this->input->post('ShiftEndTime');
        $InTime = $this->input->post('timein');
        if ($this->input->post('timeout')) {
            $OutTime = $this->input->post('timeout');
        } else {

            if (isset($_COOKIE['clientTimeZone'])) {
                $timezone = $_COOKIE['clientTimeZone'];
            } else {
                $timezone = 'UP5';
            }
            $OutTime = date('h:i a');

            $FromTimeZone = 'GMT';
            $ToTimeZone = $timezone;
            $OutTime = ConvertTimeZone($OutTime, $FromTimeZone, $ToTimeZone);
        }
        $data['AID'] = $this->input->post('AID');

        // if there is no attendance 
        if ($data['AID'] == 0) {
            // Models File Include
            $this->load->model('Day_model');
            $this->load->model('Department_history_model');

            $EmployeeID = $this->input->post('EmployeeID');
            $ShiftID = $this->input->post('ShiftID');
            $Day = $this->input->post('Day');
            $dayid = $this->Day_model->get_dayid($Day);

            $data['employeeshift'] = $this->Department_history_model->get_employee_shift($ShiftID, $EmployeeID, $dayid);
            $ShiftStartTime = $data['employeeshift']['ShiftStartTime'];
            $ShiftEndTime = $data['employeeshift']['ShiftEndTime'];
            $InTime = $ShiftStartTime;
            $OutTime = $ShiftEndTime;
        }

        $Date = $this->input->post('date');
        $data['EmployeeID'] = $this->input->post('EmployeeID');

        $employee = $this->Employee_model->get_employee($data['EmployeeID']);

        $ShiftsHours = CalculateTimeDifferencce($ShiftStartTime, $ShiftEndTime);
        $WorkedHours = CalculateTimeDifferencce($InTime, $OutTime);

        $breakallocationtime = (($ShiftsHours / 2) - (($ShiftsHours * 6.67 / 60) / 2));

        if ($WorkedHours >= $breakallocationtime) {
            $AllowedBreak = ($ShiftsHours * 6.67 / 60);
            if ($this->input->post('Day') == 'Friday') {
                //friday break is not allowed for Operations, QC, QA, Training, CSA, Operations Philippines, QC Philippines after 2018-04-21 added 2018-05-11
                if ((date('Y-m-d', strtotime($Date)) >= date('Y-m-d', strtotime('2018-04-21'))) && ($employee['DepartmentID'] == 1 || $employee['DepartmentID'] == 4 || $employee['DepartmentID'] == 6 || $employee['DepartmentID'] == 10 || $employee['DepartmentID'] == 15 || $employee['DepartmentID'] == 34 || $employee['DepartmentID'] == 36)) {
                    
                } else {
                    $FridayPrayerTime = strtotime("01:00 pm");   // 1467205200 means PST 1PM 
                    if (strtotime($InTime) < $FridayPrayerTime) { // means employee comes before 1PM PST jo 1 bajey sey pehlay aaye ga
                        $AllowedBreak = '1800' + $AllowedBreak;
                    }
                }
            }
        } else {
            $AllowedBreak = 0;
        }
        // if there is no attendance then no break allowed
        if ($data['AID'] == 0) {
            $AllowedBreak = 0;
        }

        $data['holiday'] = $this->Holidays_model->get_holiday($Date, $employee['AllowedHolidayTypeID']);

        $breaktime = $this->Break_model->get_breaks_time($data);

        $totalProductiveBreakTime = $breaktime['TotalProductiveBreakTime'];
        $totalnonProductiveBreakTime = $breaktime['TotalnonProductiveBreakTime'];


        $calculated_worked_hours = $WorkedHours + $AllowedBreak - $totalnonProductiveBreakTime;

        $data['loggedInDuration'] = CalculateWorkedTime($WorkedHours);
        $data['allowedBreak'] = CalculateWorkedTime($AllowedBreak);
        $data['totalProductiveBreakTime'] = CalculateWorkedTime($totalProductiveBreakTime);
        $data['totalnonProductiveBreakTime'] = CalculateWorkedTime($totalnonProductiveBreakTime);

        if ($data['holiday']) {
            //$calculated_worked_hours = $ShiftsHours + $calculated_worked_hours;
        }

        $data['calculatedWorkedDuration'] = CalculateWorkedTime($calculated_worked_hours);

        $data = json_encode($data);
        print_r($data);
    }

    public function get_restriction_summary() {

        // Models File Include
        $this->load->model('Department_model');
        $this->load->model('Attendance_model');

        $Name = $this->input->post('departmentname');

        $DepartmentID = $this->Department_model->get_departmentid_by_name($Name);
        //getting leave summary of the year
        $data['restriction_summary'] = $this->Attendance_model->get_restriction_summary($DepartmentID);

        $data = json_encode($data);
        print_r($data);
    }

    public function addeod($ID) {

        // Helper File Include
        $this->load->helper('string');
        $this->load->helper('form');

        // Models File Include
        $this->load->model('Shift_details_model');

        $this->load->model('Employee_model');
        $this->load->model('Department_history_model');
        $this->load->model('Day_model');
        $this->load->model('Attendance_model');
        $this->load->model('Attendance_log_model');
        $this->load->model('Chat_record_model');

        if (isset($_COOKIE['clientTimeZone'])) {
            $timezone = $_COOKIE['clientTimeZone'];
        } else {
            $timezone = 'UP5';
        }

        $data = array();
        /**/
        if ($this->input->post()) {

            $data['ID'] = $this->input->post('AID');

            $data['EOD'] = str_replace("'", '', $this->input->post('eod'));
            //$data['EOD'] = htmlspecialchars($data['EOD']);
            //$data['EOD'] = iconv("utf-8", "ascii//TRANSLIT//IGNORE", $data['EOD']);
            //$data['EOD'] = preg_replace("/^'|[^A-Za-z0-9\s-]|'$/", '', $data['EOD']); // lets remove utf-8 special characters except blank spaces
            $data['EODAddedDate'] = date("Y-m-d H:i:s");
            $this->db->where('ID', $data['ID']);
            $query = $this->db->get('attendence');
            if ($query->num_rows() > 0) {
                $result = $query->row_array();
                /* echo '<pre>'; print_r($result); echo '</pre>'; exit; */
                $this->Attendance_model->update_attendance($data);
            }
            $result['LogID'] = $this->input->post('AID');
            $result['EOD'] = $data['EOD'];
            $result['EODAddedDate'] = $data['EODAddedDate'];

            unset($result['ID']);
            unset($data['ID']);
            unset($data['EOD']);
            unset($data['EODAddedDate']);
            unset($result['IsAmended']);
            /* echo '<pre>'; print_r($result); echo '</pre>'; exit; */
            $this->Attendance_log_model->save_attendancelog($result);
            /* } */
            unset($data);
            if ($this->input->post('DepartmentID') == '1') {
                $data['AttendenceID'] = $this->input->post('AID');
                $data['TotalChats'] = $this->input->post('TotalChats');
                $data['TotalBillableChats'] = $this->input->post('TotalBillableChats');
                $this->Chat_record_model->save_chat_record($data);
            }
        }

        $data = array();
        $data['AID'] = $ID;

        $data['attendance_details'] = $this->Attendance_model->get_attendance_details($ID);
        if ($this->session->userdata('id') == '189') {
            /* echo '<pre>'; print_r($data['attendance_details']); echo '</pre>'; exit; /* */
        }
        $InTime = $data['attendance_details']['InTime'];
        $OutTime = $data['attendance_details']['OutTime'];

        $FromTimeZone = 'GMT';
        $ToTimeZone = $timezone;
        $data['attendance_details']['InTime'] = ConvertTimeZone($InTime, $FromTimeZone, $ToTimeZone);
        $data['attendance_details']['OutTime'] = ConvertTimeZone($OutTime, $FromTimeZone, $ToTimeZone);

        /* echo '<pre>'; print_r($data['attendance_details']); echo '</pre>'; exit; */

        $this->load->view('attendance/addeod', $data);
    }

    public function updateeod($ID) {
        // Models File Include
        $this->load->model('Attendance_model');
        $this->load->model('Attendance_log_model');
        $this->load->model('Chat_record_model');

        if (isset($_COOKIE['clientTimeZone'])) {
            $timezone = $_COOKIE['clientTimeZone'];
        } else {
            $timezone = 'UP5';
        }

        $data = array();
        if ($this->input->post('AID')) {
            $data['AID'] = $this->input->post('AID');
        } else {
            $data['AID'] = $ID;
        }

        if ($this->input->post('Status')) {
            $data['Status'] = $this->input->post('Status');
        } else {
            $data['Status'] = $this->uri->segment('4');
        }

        $data['attendance_details'] = $this->Attendance_model->get_attendance_details($data['AID']);
        //echo '<pre>'; print_r($data['attendance_details']); echo '</pre>'; exit; 
        // employee can only apply his restriction approval request 2018-03-06        
        if (($data['attendance_details']['EmployeeID'] != $this->session->userdata('id')) && ($data['Status'] == 0)) {
            redirect(File_BASE_URL . '/attendance/index');
        }
        // employee can only apply his restriction approval request 2018-03-06
        // code for salary lock 2018-03-02        
        $month = date('m', strtotime($data['attendance_details']['Date']));
        $year = date('Y', strtotime($data['attendance_details']['Date']));

        $month_start_date = date("$year-$month-21");
        $year_start_date = date("$year-12-21");

        if (strtotime($data['attendance_details']['Date']) >= strtotime($month_start_date)) {
            $month = $month + 1;
            if ($month == 13) {
                $month = 1;
            }
        }
        if (strtotime($data['attendance_details']['Date']) >= strtotime($year_start_date)) {
            $year = $year + 1;
        }

        $data['SalaryMonth'] = $month;
        $data['SalaryYear'] = $year;
        $data['DepartmentID'] = $data['employee']['DepartmentID'];

        //calling helper function to get current month salary lock data
        $salary_lock = department_salary_lock_data($data);

        // if lock status is y then salary is locked else not locked
        $lock_status = 'n';
        foreach ($salary_lock as $lock) {
            if ($lock['DepartmentID'] == $data['DepartmentID']) {
                $lock_status = $lock['LockStatus'];
            }
        }
        //echo '<br>lock_status ' . $lock_status;
        if ($lock_status == 'y') {
            redirect(File_BASE_URL . '/attendance/index');
        }
        //  code for salary lock 2018-03-02

        $InTime = $data['attendance_details']['InTime'];
        $OutTime = $data['attendance_details']['OutTime'];

        $FromTimeZone = 'GMT';
        $ToTimeZone = $timezone;
        $data['attendance_details']['InTime'] = ConvertTimeZone($InTime, $FromTimeZone, $ToTimeZone);
        $data['attendance_details']['OutTime'] = ConvertTimeZone($OutTime, $FromTimeZone, $ToTimeZone);

        $data['attendance_details']['ChatRecord'] = $this->Chat_record_model->get_chat_records($ID);

        if ($this->session->userdata('id') == '4') {
            /* echo '<pre>'; print_r($data['attendance_details']); echo '</pre>'; exit; */
        }
        /**/
        if ($this->input->post()) {
            $data_to_save = array();
            if ($this->input->post('eod') != '') {
                $data_to_save['ID'] = $this->input->post('AID');

                $data_to_save['EOD'] = str_replace("'", '', $this->input->post('eod'));
                $data_to_save['EOD'] = htmlspecialchars($data_to_save['EOD']);
                //$data_to_save['EOD'] = iconv("utf-8", "ascii//TRANSLIT//IGNORE", $data_to_save['EOD']);
                //$data_to_save['EOD'] = preg_replace("/^'|[^A-Za-z0-9\s-]|'$/", '', $data_to_save['EOD']); // lets remove utf-8 special characters except blank spaces
                $data_to_save['EODAddedDate'] = date("Y-m-d H:i:s");
                $this->db->where('ID', $data_to_save['ID']);
                $query = $this->db->get('attendence');
                if ($query->num_rows() > 0) {
                    $result = $query->row_array();
                    /* echo '<pre>'; print_r($result); echo '</pre>'; exit; */
                    $this->Attendance_model->update_attendance($data_to_save);
                }
                $result['LogID'] = $this->input->post('AID');
                $result['EOD'] = $data_to_save['EOD'];
                $result['EODAddedDate'] = $data_to_save['EODAddedDate'];

                unset($result['ID']);
                unset($data_to_save['ID']);
                unset($data_to_save['EOD']);
                unset($data_to_save['EODAddedDate']);
                unset($result['IsAmended']);
                /* echo '<pre>'; print_r($result); echo '</pre>'; exit; */
                $this->Attendance_log_model->save_attendancelog($result);
                /* } */
                unset($data_to_save);
            }
            if ($this->input->post('CompanyID') == '3' || $this->input->post('DepartmentID') == '1') {
                $data_to_save['AttendenceID'] = $this->input->post('AID');
                $status = 0;
                if ($this->input->post('TotalChats') != '') {
                    $data_to_save['TotalChats'] = $this->input->post('TotalChats');
                    $status = $status + 1;
                }
                if ($this->input->post('TotalBillableChats') != '') {
                    $data_to_save['TotalBillableChats'] = $this->input->post('TotalBillableChats');
                    $status = $status + 1;
                }
                $this->Chat_record_model->save_chat_record($data_to_save);
//                if ($status != 0) {
//                    if ($this->input->post('Status') == '0') {
//                        $this->Chat_record_model->save_chat_record($data_to_save);
//                    } else {
//                        $this->Chat_record_model->update_chat_record($data_to_save);
//                    }
//                }
            }
        }

        $this->load->view('attendance/updateeod', $data);
    }

    public function viewattendancelog($ID) {
        if ($this->session->userdata('role') != '4' || $this->session->userdata('id') == '645') {

            // Helper File Include
            $this->load->helper('email');

            // Libraries File Include
            $this->load->library('email');

            // Models File Include
            $this->load->model('Shift_model');
            $this->load->model('Shift_details_model');
            $this->load->model('Employee_model');
            $this->load->model('Department_history_model');
            $this->load->model('Day_model');
            $this->load->model('Attendance_model');
            $this->load->model('Attendance_log_model');
            $this->load->model('Holidays_model');

            $data = array();

            $EmployeeID = $this->uri->segment(4);
            $ShiftID = $this->uri->segment(5);
            $AttendanceDate = $this->uri->segment(6);
            $OAttendanceDate = $this->uri->segment(7);
            /* Approved By */
            $data['ID'] = $ID;
            $data['ModifiedDate'] = date("Y-m-d H:i:s", strtotime($this->uri->segment(11)));

            if (isset($_COOKIE['clientTimeZone'])) {
                $timezone = $_COOKIE['clientTimeZone'];
            } else {
                $timezone = 'UP5';
            }
            $FromTimeZone = $timezone;
            $ToTimeZone = 'GMT';

            $InTime = date("h:i a", strtotime($this->uri->segment(9)));
            $OutTime = date("h:i a", strtotime($this->uri->segment(10)));



            $data['CheckedBy'] = $this->uri->segment(12);

            if ($data['CheckedBy'] == 1) {
                $data['CheckedBy'] = $this->session->userdata('id');
                $this->Attendance_model->update_attendance($data);

                $AttendanceData = $this->Attendance_model->get_attendance($data);
                unset($data);
                $data['LogID'] = $AttendanceData['ID'];
                $data['EmployeeID'] = $AttendanceData['EmployeeID'];
                $data['ShiftID'] = $AttendanceData['ShiftID'];
                $data['Date'] = $AttendanceData['Date'];
                $data['EndDate'] = $AttendanceData['Date'];
                $data['InTime'] = $AttendanceData['InTime'];
                $data['OutTime'] = $AttendanceData['OutTime'];
                $data['AddedBy'] = $AttendanceData['AddedBy'];
                $data['Comments'] = str_replace("'", '', $AttendanceData['Comments']);
                $data['CheckedBy'] = $AttendanceData['CheckedBy'];
                $data['AttendanceCrash'] = $AttendanceData['AttendanceCrash'];
                $data['LastActivityDate'] = $AttendanceData['LastActivityDate'];
                $data['Source'] = $AttendanceData['Source'];
                $data['ComputerName'] = $AttendanceData['ComputerName'];
                $data['ModifiedDate'] = $AttendanceData['ModifiedDate'];
                /* print_r($data); exit; */

                $this->Attendance_log_model->save_attendancelog($data);

                /* sending email to Employee */

                $email_to = $this->Employee_model->get_employee_email($data['EmployeeID']);
                $email_from = $this->Employee_model->get_employee_email($data['CheckedBy']);


                /* echo '<pre>'; print_r($email_to); echo '</pre>'; exit; */

                $sender_email = $email_from['OfficialEmailAddress'];
                $sender_name = $email_from['FirstName'] . ' ' . $email_from['LastName'];

                if ($email_from['PseudoFirstName']) {
                    $sender_name .= ' (' . $email_from['PseudoFirstName'] . ' ' . $email_from['PseudoLastName'] . ')';
                }
                $sender_designation = $email_from['JobTitle'];

                $receiver_email = $email_to['OfficialEmailAddress'];
                $receiver_name = $email_to['FirstName'] . ' ' . $email_to['LastName'];
                if ($email_to['PseudoFirstName']) {
                    $receiver_name .= ' (' . $email_to['PseudoFirstName'] . ' ' . $email_to['PseudoLastName'] . ')';
                }

                if (isset($_COOKIE['clientTimeZone'])) {
                    $timezone = $_COOKIE['clientTimeZone'];
                } else {
                    $timezone = 'UP5';
                }
                $FromTimeZone = 'GMT';
                $ToTimeZone = $timezone;

                $InTime = ConvertTimeZone($data['InTime'], $FromTimeZone, $ToTimeZone);
                $OutTime = ConvertTimeZone($data['OutTime'], $FromTimeZone, $ToTimeZone);

                $attendancedetail = '<strong>Date: </strong>' . $data['Date'] . '<br><br><strong>Approval Comments: </strong>' . $data['Comments'] . '<br><br><strong>Approved By: </strong>' . $sender_name;

                //Sending Email	
                //$this->email->from($sender_email, $sender_name);
                $this->email->from('no-reply@liveadmins.net', 'LES');
                $this->email->to($receiver_email, $receiver_name);
                /* $this->email->cc('another@another-example.com'); */
                //$this->email->bcc('naveed.zulfaqar@maavratech.com, muhammad.imran@maavratech.com');

                $subject = 'Approval of Attendance Modification Dated ' . $data['Date'];
                $message = "Dear $receiver_name$,<br><br>Your attendance has been approved. Following are the details<br><br>$attendancedetail";
                $this->email->subject($subject);

                //Sending Email	again to sender
                $this->email->from('no-reply@liveadmins.net', 'LES');
                $this->email->to($sender_email, $sender_name);
                /* $this->email->cc('another@another-example.com'); */
                //$this->email->bcc('naveed.zulfaqar@maavratech.com, muhammad.imran@maavratech.com');

                $subject = 'Approval of Attendance Modification Dated ' . $data['Date'];
                $message = "Dear $sender_name,<br><br>You have approved the attendance of $receiver_name. Following are the details<br><br>$attendancedetail";
                $this->email->subject($subject);

                $data_email = array();
                $data_email['email_body'] = $message;
                $message = $this->load->view('email-templates/default', $data_email, true);
                $this->email->message($message);
                $em = $this->email->send();

                //echo $this->email->print_debugger();
                /* sending email ends */
            }
            unset($data);
            /**/
            $data['ID'] = $ID;
            $data['ModifiedDate'] = date("Y-m-d H:i:s", strtotime($this->uri->segment(11)));

            $data['Day'] = $this->uri->segment(8);

            $DayId = $this->Day_model->get_dayid($data['Day']);
            $data['employee'] = $this->Employee_model->get_employee($EmployeeID);
            $data['ShiftID'] = $ShiftID;
            $data['EmployeeID'] = $EmployeeID;
            $data['AttendanceDate'] = $AttendanceDate;
            $data['OAttendanceDate'] = $OAttendanceDate;

            if ($data['OAttendanceDate'] == 0) {
                $data['view_attendance'] = $this->Attendance_log_model->view_attendancelog($ShiftID, $EmployeeID, $data['AttendanceDate']);
            } else {
                $data['view_attendance'] = $this->Attendance_log_model->view_attendancelog($ShiftID, $EmployeeID, $data['OAttendanceDate']);
            }

            $data['holiday'] = $this->Holidays_model->get_holiday($data['AttendanceDate'], $data['employee']['AllowedHolidayTypeID']);

            if ($this->session->userdata('id') == '189') {
                //echo '<pre>'; print_r($data['view_attendance']); echo '</pre>';
            }

            foreach ($data['view_attendance'] as $key => $attendance) {

                $data['view_attendance'][$key]['GMTInTime'] = $attendance['InTime'];
                $InTime = $attendance['InTime'];

                $FromTimeZone = 'GMT';
                $ToTimeZone = $timezone;
                $data['view_attendance'][$key]['InTime'] = ConvertTimeZone($InTime, $FromTimeZone, $ToTimeZone);
                if ($attendance['OutTime'] != '') {
                    $data['view_attendance'][$key]['GMTOutTime'] = $attendance['OutTime'];
                    $OutTime = $attendance['OutTime'];
                    $data['view_attendance'][$key]['OutTime'] = ConvertTimeZone($OutTime, $FromTimeZone, $ToTimeZone);
                } else {
                    $OutTime = ''; /* date("h:i a",strtotime("now")) */
                    $data['view_attendance'][$key]['OutTime'] = '-';
                }

                $data['view_attendance'][$key]['WorkedHours'] = CalculateTimeDifferencce($InTime, $OutTime);
                $data['view_attendance'][$key]['AttendanceCrash'] = $attendance['AttendanceCrash'];

                $ShiftStartTime = $attendance['ShiftStartTime'];
                $ShiftEndTime = $attendance['ShiftEndTime'];
                $data['view_attendance'][$key]['ShiftStartTime'] = ConvertTimeZone($ShiftStartTime, $FromTimeZone, $ToTimeZone);
                $data['view_attendance'][$key]['ShiftEndTime'] = ConvertTimeZone($ShiftEndTime, $FromTimeZone, $ToTimeZone);

                if (isset($_COOKIE['clientTimeZone'])) {
                    $timezone = $_COOKIE['clientTimeZone'];
                } else {
                    $timezone = 'UP5';
                }
                $FromTimeZone = 'GMT';
                $ToTimeZone = $timezone;

                $data['view_attendance'][$key]['GMTModifiedDate'] = $data['attendanceMD'] = $attendance['ModifiedDate'];
                $Date = date("Y-m-d", strtotime($attendance['ModifiedDate']));
                $data['Date'] = $Date;
                $ITime = date("h:i a", strtotime($attendance['ModifiedDate']));
                $data['ITime'] = $ITime;
                $Time = ConvertTimeZone(date("h:i a", strtotime($attendance['ModifiedDate'])), $FromTimeZone, $ToTimeZone);
                $data['Time'] = $Time;
                $data['view_attendance'][$key]['ModifiedDate'] = date('Y-m-d H:i:s', strtotime("$Date $Time"));
            }

            $this->load->view('attendance/viewattendance', $data);
        }
    }

    public function add_break_popup($ID) {
        if (!($this->session->userdata('role') == '4')) {
            // Helper File Include
            $this->load->helper('email');

            // Libraries File Include
            $this->load->library('email');

            // Models File Include
            $this->load->model('Shift_details_model');
            $this->load->model('Employee_model');
            $this->load->model('Department_history_model');
            $this->load->model('Day_model');
            $this->load->model('Attendance_model');
            $this->load->model('Attendance_log_model');
            $this->load->model('Break_model');
            $this->load->model('Break_log_model');

            $data = array();

            /* getting the previous break */
            $data['BLogID'] = $this->uri->segment(8);
            $prevbreaks = $this->Break_model->get_breaks($data);
            unset($data['BLogID']);

            $data['ID'] = $ID;
            $EmployeeID = $this->uri->segment(4);
            if ($EmployeeID == $this->session->userdata('id')) {
                redirect(File_BASE_URL . '/attendance/index');
            }

            $ShiftID = $this->uri->segment(5);
            $data['Date'] = $this->uri->segment(6);
            $data['Day'] = $this->uri->segment(7);
            $data['BLogID'] = $this->uri->segment(8);
            $data['AttendenceID'] = $this->uri->segment(9);

            $dayid = $this->Day_model->get_dayid($data['Day']);
            $data['employee'] = $this->Employee_model->get_employee($EmployeeID);
            //echo '<pre>'; print_r($data); echo '</pre>';
            // other than super admin only reporting to manager can modify break 2018-03-06
            if ($this->session->userdata('role') != '1') {
                // if department are Operations (Philippines & Pakistan) and Daniel or Thomas 2018-04-02
                if (($data['employee']['DepartmentID'] == '1' || $data['employee']['DepartmentID'] == '4' || $data['employee']['DepartmentID'] == '6' || $data['employee']['DepartmentID'] == '10' || $data['employee']['DepartmentID'] == '15' || $data['employee']['DepartmentID'] == '36') && ($this->session->userdata('id') == '2' || $this->session->userdata('id') == '3')) {
                    
                } else if (($data['employee']['DepartmentID'] == '26') && ($this->session->userdata('id') == '179' || $this->session->userdata('id') == '180')) {
                    
                } else {
                    if ($data['employee']['ReportingTo'] != $this->session->userdata('id')) {
                        redirect(File_BASE_URL . '/attendance/index');
                    }
                }
            }
            // other than super admin only reporting to manager can modify break 2018-03-06
            // code for salary lock 2018-03-02        
            $month = date('m', strtotime($data['Date']));
            $year = date('Y', strtotime($data['Date']));

            $month_start_date = date("$year-$month-21");
            $year_start_date = date("$year-12-21");

            if (strtotime($data['Date']) >= strtotime($month_start_date)) {
                $month = $month + 1;
                if ($month == 13) {
                    $month = 1;
                }
            }
            if (strtotime($data['Date']) >= strtotime($year_start_date)) {
                $year = $year + 1;
            }

            $data['SalaryMonth'] = $month;
            $data['SalaryYear'] = $year;
            $data['DepartmentID'] = $data['employee']['DepartmentID'];

            //calling helper function to get current month salary lock data
            $salary_lock = department_salary_lock_data($data);

            // if lock status is y then salary is locked else not locked
            $lock_status = 'n';
            foreach ($salary_lock as $lock) {
                if ($lock['DepartmentID'] == $data['DepartmentID']) {
                    $lock_status = $lock['LockStatus'];
                }
            }
            //echo '<br>lock_status ' . $lock_status;
            if ($lock_status == 'y') {
                redirect(File_BASE_URL . '/attendance/index');
            }
            //  code for salary lock 2018-03-02

            $data['employeeshift'] = $this->Department_history_model->get_employee_shift($ShiftID, $EmployeeID, $dayid);
            $data['breaklog'] = $this->Break_model->get_breaks($data);

            /**/
            if ($this->input->post()) {

                $data_to_save = array();

                $data_to_save['ID'] = $this->input->post('ID');
                $BLogID = $this->input->post('BLogID');
                $data_to_save['EmployeeID'] = $this->input->post('EmployeeID');
                $data_to_save['AttendenceID'] = $this->input->post('AttendenceID');
                $data_to_save['ShiftID'] = $this->input->post('ShiftID');
                $data_to_save['Date'] = $this->input->post('BreakDate');
                $InTime = $this->input->post('timein');
                $OutTime = $this->input->post('timeout');

                if (strtotime($InTime) < strtotime($OutTime)) {
                    $data_to_save['EndDate'] = $this->input->post('BreakDate');
                } else if (strtotime($InTime) > strtotime($OutTime)) {
                    $data_to_save['EndDate'] = date('Y-m-d', strtotime(' +1 day', strtotime($this->input->post('BreakDate'))));
                }

                if (isset($_COOKIE['clientTimeZone'])) {
                    $timezone = $_COOKIE['clientTimeZone'];
                } else {
                    $timezone = 'UP5';
                }
                $FromTimeZone = $timezone;
                $ToTimeZone = 'GMT';

                $data_to_save['BreakStart'] = ConvertTimeZone($InTime, $FromTimeZone, $ToTimeZone);
                $data_to_save['BreakStart_New'] = date('H:i:s', strtotime($data_to_save['BreakStart']));
                $data_to_save['BreakEnd'] = ConvertTimeZone($OutTime, $FromTimeZone, $ToTimeZone);
                $data_to_save['BreakEnd_New'] = date('H:i:s', strtotime($data_to_save['BreakEnd']));

                /* $data_to_save['Status'] = 1; */
                $data_to_save['AddedBy'] = $this->session->userdata('id');
                $data_to_save['Comments'] = str_replace("'", '', $this->input->post('comments'));
                $data_to_save['Comments'] = htmlspecialchars($data_to_save['Comments']);
                $data_to_save['Comments'] = iconv("utf-8", "ascii//TRANSLIT//IGNORE", $data_to_save['Comments']);
                $data_to_save['Comments'] = preg_replace("/^'|[^A-Za-z0-9\s-]|'$/", '', $data_to_save['Comments']); // lets remove utf-8 special characters except blank spaces
                $data_to_save['BreakType'] = $this->input->post('breaktype');

                $data_to_save['ModifiedDate'] = date("Y-m-d H:i:s");

                if ($data_to_save['ID'] == 0 || $BLogID == 0) {
                    unset($data_to_save['ID']);
                    $data_to_save['LogID'] = $this->Break_model->save_break($data_to_save);
                } else {
                    /* checking break is amended or not */
                    if (($prevbreaks[0]['BreakType'] != $data_to_save['BreakType']) || ($prevbreaks[0]['BreakStart'] != $data_to_save['BreakStart']) || ($prevbreaks[0]['BreakEnd'] != $data_to_save['BreakEnd'])) {
                        $data_to_save['IsAmended'] = 1;
                    }
                    /**/
                    $data_to_save['CheckedBy'] = NULL;
                    $this->Break_model->update_break($data_to_save);
                    $data_to_save['LogID'] = $data_to_save['ID'];
                }
                unset($data_to_save['ID']);
                unset($data_to_save['IsAmended']);
                $this->Break_log_model->save_breaklog($data_to_save);

                /*                 * ****************** LOGGER ****************************** */
                //if ($this->session->userdata('id') == '189') {
                $starttime = currentTime();
                $logfilename = $data_to_save['EmployeeID'];
                if ($this->input->post('ID') == 0 || $BLogID == 0) {
                    $break_modify = 'Added';
                } else {
                    $break_modify = 'Modify';
                }
                logger_break_modify("******************* Break $break_modify By  LES ID: " . $data_to_save['AddedBy'] . " on $starttime ******************************************", $logfilename);
                logger_break_modify("Before Modification Break Info : Break Type: " . $prevbreaks[0]['BreakType'] . " Break Date: " . $this->uri->segment(6) . " Break Start: " . $prevbreaks[0]['BreakStart'] . " Break End: " . $prevbreaks[0]['BreakEnd'], $logfilename);
                logger_break_modify("After  Modification Break Info : Break Type: " . $data_to_save['BreakType'] . " Break Date: " . $data_to_save['Date'] . " Break Start: $InTime Break End: $OutTime", $logfilename);
                logger_break_modify("************************************************************************************************************************************", $logfilename);
                //}

                /*-----------------******************LOGGER ENDS****************************** */

                /* sending email to Employee */

                $email_to = $this->Employee_model->get_employee_email($data_to_save['EmployeeID']);
                $email_from = $this->Employee_model->get_employee_email($this->session->userdata('id'));

                /* echo '<pre>'; print_r($email_to); echo '</pre>'; exit; */

                $sender_email = $email_from['OfficialEmailAddress'];
                $sender_name = $email_from['FirstName'] . ' ' . $email_from['LastName'];

                if ($email_from['PseudoFirstName']) {
                    $sender_name .= ' (' . $email_from['PseudoFirstName'] . ' ' . $email_from['PseudoLastName'] . ')';
                }

                $sender_designation = $email_from['JobTitle'];

                $receiver_email = $email_to['OfficialEmailAddress'];
                $receiver_name = $email_to['FirstName'] . ' ' . $email_to['LastName'];
                if ($email_to['PseudoFirstName']) {
                    $receiver_name .= ' (' . $email_to['PseudoFirstName'] . ' ' . $email_to['PseudoLastName'] . ')';
                }

                $FromTimeZone = $timezone;
                $ToTimeZone = 'GMT';

                $BreakStart = ConvertTimeZone($this->input->post('timein'), $FromTimeZone, $ToTimeZone);
                $BreakEnd = ConvertTimeZone($this->input->post('timeout'), $FromTimeZone, $ToTimeZone);

                $FromTimeZone = 'GMT';
                $ToTimeZone = 'UP5';

                $BreakStart = ConvertTimeZone($BreakStart, $FromTimeZone, $ToTimeZone);
                $BreakEnd = ConvertTimeZone($BreakEnd, $FromTimeZone, $ToTimeZone);

                $BreakTime = CalculateTimeDifferencce($data_to_save['BreakStart'], $data_to_save['BreakEnd']);
                $BreakTime = CalculateWorkedTime($BreakTime);

                $breakdetail = '<strong>Date: </strong>' . $data_to_save['Date'] . '<br><br><strong>Break Start: </strong>' . $BreakStart . ' (PST)<br><strong>Break End: </strong>' . $BreakEnd . ' (PST)<br><br><strong>Break Duration: </strong>' . $BreakTime . '<br><strong>Break Type: </strong>' . $data_to_save['BreakType'] . '<br><br><strong>Break Comments: </strong>' . $data_to_save['Comments'];

                //Sending Email	

                if ($this->input->post('ID') == 0 || $BLogID == 0) {
                    $breakdetail .= '<br><br><strong>Break Added By: </strong>' . $sender_name;

                    $subject = 'Break Added of Date ' . $data_to_save['Date'];
                    $message = "Dear $receiver_name$receiver_pseudoname,<br><br>Your Break has been added. Following are the details<br><br>$breakdetail";
                } else {
                    $breakdetail .= '<br><br><strong>Break Modified By: </strong>' . $sender_name;

                    $subject = 'Break Modified of Date ' . $data_to_save['Date'];
                    $message = "Dear $receiver_name$receiver_pseudoname,<br><br>Your break has been modified. Following are the details:<br><br>$breakdetail";
                }

                $data_email = array();
                $data_email['email_body'] = $message;
                $message = $this->load->view('email-templates/default', $data_email, true);

                $this->load->helper('email');
                $this->load->library('email');

                $this->email->from('no-reply@liveadmins.net', 'LES');
                $this->email->to($receiver_email, $receiver_name);
                //$this->email->bcc('naveed.zulfaqar@maavratech.com, muhammad.imran@maavratech.com');
                $this->email->subject($subject);
                $this->email->message($message);
                $em = $this->email->send();

                //Sending Email again to sender

                if ($this->input->post('ID') == 0 || $BLogID == 0) {
                    $message = "Dear $sender_name,<br><br>You have added the Break of $receiver_name. Following are the details<br><br>$breakdetail";
                } else {
                    $message = "Dear $sender_name,<br><br>You have modified the break of $receiver_name. Following are the details:<br><br>$breakdetail";
                }

                $data_email = array();
                $data_email['email_body'] = $message;
                $message = $this->load->view('email-templates/default', $data_email, true);

                $this->load->helper('email');
                $this->load->library('email');

                $this->email->from('no-reply@liveadmins.net', 'LES');
                $this->email->to($sender_email, $sender_name);
                //$this->email->bcc('naveed.zulfaqar@maavratech.com, muhammad.imran@maavratech.com');
                $this->email->subject($subject);
                $this->email->message($message);
                $em = $this->email->send();

                //echo $this->email->print_debugger();
                /* sending email ends */
            }

            $this->load->view('attendance/add_break_popup', $data);
        } else {
            redirect(File_BASE_URL . '/shiftreports/index');
        }
    }

    public function viewbreaklog($ID) {
        if (!($this->session->userdata('role') == '4')) {

            // Helper File Include
            $this->load->helper('string');
            $this->load->helper('form');

            // Libraries File Include
            $this->load->library('form_validation');

            // Models File Include
            $this->load->model('Shift_details_model');

            $this->load->model('Employee_model');
            $this->load->model('Department_history_model');
            $this->load->model('Day_model');
            $this->load->model('Attendance_model');
            $this->load->model('Attendance_log_model');
            $this->load->model('Break_model');
            $this->load->model('Break_log_model');

            $data = array();

            $data['ID'] = $ID;
            $EmployeeID = $this->uri->segment(4);
            $CheckedBy = $this->uri->segment(10);

            $data['Date'] = $this->uri->segment(6);
            $data['Day'] = $this->uri->segment(7);
            $data['BLogID'] = $this->uri->segment(8);

            $dayid = $this->Day_model->get_dayid($data['Day']);

            /**/
            $data['employee'] = $this->Employee_model->get_employee($EmployeeID);
            $data['breaklog'] = $this->Break_log_model->get_breaklog($data);

            $this->load->view('attendance/viewbreaklog', $data);
        }
    }

    public function approvebreak($ID) {

        if (!($this->session->userdata('role') == '4')) {
            // Helper File Include
            $this->load->helper('string');
            $this->load->helper('form');
            $this->load->helper('email');

            // Libraries File Include
            $this->load->library('form_validation');
            $this->load->library('email');

            // Models File Include
            $this->load->model('Employee_model');
            $this->load->model('Day_model');
            $this->load->model('Break_approval_request_model');
            $this->load->model('Break_model');
            $this->load->model('Break_log_model');

            $data = array();

            if ($this->input->post()) {
                $data['ID'] = $this->input->post('ID');
                $data['ApprovalStatus'] = 'y';
                $data['UpdatedBy'] = $this->session->userdata('id');
                $data['UpdatedDate'] = date('Y-m-d');
                $data['UpdatedComments'] = str_replace("'", '', $this->input->post('comments'));
                $data['UpdatedComments'] = htmlspecialchars($data['UpdatedComments']);
                //$data['UpdatedComments'] = iconv("utf-8", "ascii//TRANSLIT//IGNORE", $data['UpdatedComments']);
                //$data['UpdatedComments'] = preg_replace("/^'|[^A-Za-z0-9\s-]|'$/", '', $data['UpdatedComments']); // lets remove utf-8 special characters except blank spaces

                $this->Break_approval_request_model->update_requested_break($data);
                unset($data['ID']);
                $data['ID'] = $ID;
                $break = $this->Break_model->get_a_break($data);

                if ($this->session->userdata('id') == '189') {
                    //echo '<pre>'; print_r($break); echo '</pre>'; exit;
                }

                $data['EmployeeID'] = $break['EmployeeID'];
                $data['Date'] = $break['Date'];
                $data['BreakType'] = $break['BreakType'];
                //Setting Up TimeZones
                if (isset($_COOKIE['clientTimeZone'])) {
                    $timezone = $_COOKIE['clientTimeZone'];
                } else {
                    $timezone = 'UP5';
                }
                $FromTimeZone = $timezone;
                $ToTimeZone = 'GMT';
                $data['BreakStart'] = ConvertTimeZone($break['BreakStart'], $FromTimeZone, $ToTimeZone);
                $data['BreakEnd'] = ConvertTimeZone($break['BreakEnd'], $FromTimeZone, $ToTimeZone);

                $this->session->set_flashdata('success_message', 'Break Successfully Approved.');

                /* sending email to Employee */

                $email_to = $this->Employee_model->get_employee_email($data['EmployeeID']);
                $email_from = $this->Employee_model->get_employee_email($data['UpdatedBy']);

                /* echo '<pre>'; print_r($email_to); echo '</pre>'; exit; */

                $sender_email = $email_from['OfficialEmailAddress'];
                $sender_name = $email_from['FirstName'] . ' ' . $email_from['LastName'];

                if ($email_from['PseudoFirstName']) {
                    $sender_name .= ' (' . $email_from['PseudoFirstName'] . ' ' . $email_from['PseudoLastName'] . ')';
                }

                $sender_designation = $email_from['JobTitle'];

                $receiver_email = $email_to['OfficialEmailAddress'];
                $receiver_name = $email_to['FirstName'] . ' ' . $email_to['LastName'];
                if ($email_to['PseudoFirstName']) {
                    $receiver_name .= ' (' . $email_to['PseudoFirstName'] . ' ' . $email_to['PseudoLastName'] . ')';
                }

                $BreakTime = CalculateTimeDifferencce($data['BreakStart'], $data['BreakEnd']);
                $BreakTime = CalculateWorkedTime($BreakTime);

                $breakdetail = '<strong>Date: </strong>' . $data['Date'] . '<br><br><strong>BreakType: </strong>' . $data['BreakType'] . '<br><strong>Break Duration: </strong>' . $BreakTime . '<br><br><strong>Approval Comments: </strong>' . $data['UpdatedComments'] . '<br><br><strong>Approved By: </strong>' . $sender_name;

                //Sending Email	

                $subject = 'Approval of Break Dated ' . $data['Date'];
                $message = "Dear $receiver_name,<br><br>Your break has been approved. Following are the details:<br><br>$breakdetail";
                $data_email = array();
                $data_email['email_body'] = $message;
                $message = $this->load->view('email-templates/default', $data_email, true);

                $this->load->helper('email');
                $this->load->library('email');

                $this->email->from('no-reply@liveadmins.net', 'LES');
                $this->email->to($receiver_email, $receiver_name);
                $this->email->bcc('naveed.zulfaqar@maavratech.com, muhammad.imran@maavratech.com');
                $this->email->subject($subject);
                $this->email->message($message);
                $em = $this->email->send();

                //Sending Email	again to sender

                $message = "Dear $sender_name,<br><br>You have approved the break of $receiver_name. Following are the details:<br><br>$breakdetail";
                $data_email = array();
                $data_email['email_body'] = $message;
                $message = $this->load->view('email-templates/default', $data_email, true);

                $this->load->helper('email');
                $this->load->library('email');

                $this->email->from('no-reply@liveadmins.net', 'LES');
                $this->email->to($sender_email, $sender_name);
                //$this->email->bcc('naveed.zulfaqar@maavratech.com, muhammad.imran@maavratech.com');
                $this->email->subject($subject);
                $this->email->message($message);
                $em = $this->email->send();

                //echo $this->email->print_debugger();
                /* sending email ends */

                //calling helper function to get pending requests count
                count_pending_requests();
            }

            $data['ID'] = $ID;
            $CheckedBy = $this->uri->segment(10);
            $data['EmployeeID'] = $this->uri->segment(4);
            if ($data['EmployeeID'] == $this->session->userdata('id')) {
                redirect(File_BASE_URL . '/attendance/index');
            }
            $data['ShiftID'] = $this->uri->segment(5);
            $data['Date'] = $this->uri->segment(6);
            $data['Day'] = $this->uri->segment(7);
            $data['BLogID'] = $this->uri->segment(8);

            $dayid = $this->Day_model->get_dayid($data['Day']);

            /**/
            $data['employee'] = $this->Employee_model->get_employee($data['EmployeeID']);
            $data['break_request'] = $this->Break_approval_request_model->get_breaks_requests($data);

            if ($this->session->userdata('id') == '189') {
                /* echo '<pre>'; print_r($data['breaklog']); echo '</pre>'; */
            }

            $this->load->view('attendance/approvebreak', $data);
        }
    }

    public function breakapprovalrequest($ID) {

        // Libraries File Include
        $this->load->library('email');

        // Models File Include
        $this->load->model('Employee_model');
        $this->load->model('Break_approval_request_model');
        $this->load->model('Break_model');

        $data = array();
        if ($this->input->post()) {
            /* echo '<pre>'; print_r($this->input->post()); echo '</pre>'; */
            $data['EmployeeID'] = $this->input->post('EmployeeID');
            $data['BreakID'] = $this->input->post('BreakID');
            $data['RequestedComments'] = $this->input->post('RequestedComments');
            $data['RequestedComments'] = htmlspecialchars($data['RequestedComments']);
            $data['RequestedComments'] = iconv("utf-8", "ascii//TRANSLIT//IGNORE", $data['RequestedComments']);
            $data['RequestedComments'] = preg_replace("/^'|[^A-Za-z0-9\s-]|'$/", '', $data['RequestedComments']); // lets remove utf-8 special characters except blank spaces
            $data['RequestedTo'] = $this->input->post('ReportingTo');
            $data['RequestedDate'] = $this->input->post('RequestedDate');
            $data['ApprovalStatus'] = 'p';

            $this->Break_approval_request_model->save_breakapprovalrequests($data);

            /* sending email to Employee */

            $email_to = $this->Employee_model->get_employee_email($this->input->post('ReportingTo'));
            $email_from = $this->Employee_model->get_employee_email($this->input->post('EmployeeID'));

            /* echo '<pre>'; print_r($email_to); echo '</pre>'; exit; */

            $sender_email = $email_from['OfficialEmailAddress'];
            $sender_name = $email_from['FirstName'] . ' ' . $email_from['LastName'];

            if ($email_from['PseudoFirstName']) {
                $sender_name .= ' (' . $email_from['PseudoFirstName'] . ' ' . $email_from['PseudoLastName'] . ')';
            }

            $sender_designation = $email_from['JobTitle'];

            $receiver_email = $email_to['OfficialEmailAddress'];
            $receiver_name = $email_to['FirstName'] . ' ' . $email_to['LastName'];
            if ($email_to['PseudoFirstName']) {
                $receiver_name .= ' (' . $email_to['PseudoFirstName'] . ' ' . $email_to['PseudoLastName'] . ')';
            }

            /* $receiver_email = 'lesteamla@gmail.com';   $OfficialEmailAddress */
            /* $receiver_name = 'LES Test';$Name */

            $breakdetail = '<strong>Break Date: </strong>' . $this->input->post('BreakDate') . ' (' . $this->input->post('BreakDay') . ')' . '<br><br>';
            /* $breakdetail .= '<strong>Break Start: </strong>' . $this->input->post('BreakStart').' (PST)<br><strong>Break End: </strong>'.$this->input->post('BreakEnd') . ' (PST)<br><br>'; */
            $breakdetail .= '<strong>Break Type: </strong>' . $this->input->post('BreakType') . '<br><br><strong>Break Duration: </strong>' . $this->input->post('BreakDuration') . '<br><br><strong>Requested To: </strong>' . $receiver_name;

            $breakdetail .= '<br><br><strong>Requested Comment: </strong>' . $data['RequestedComments'] . '<br><br><strong>Requested Date: </strong>' . $data['RequestedDate'];

            $ADate = date("Y-m-d", strtotime($data['AddedDate']));

            $url = LES_BASE_URL . 'breaks/approvebreak' . '/' . $ID;
            $link = '<a href=' . "$url" . ' target="_blank">Please click here to approve the break</a>';

            //Sending Email	

            $subject = 'Break Approval Request of ' . $this->input->post('BreakType') . ' Dated: ' . $this->input->post('BreakDate');
            $message = "Dear $receiver_name,<br><br>$sender_name has sent you the break approval request.<br><br> Following are the details: <br><br>$breakdetail<br><br>$link";
            $data_email = array();
            $data_email['email_body'] = $message;
            $message = $this->load->view('email-templates/default', $data_email, true);

            $CC_email = CC_Email($this->input->post('DepartmentID'));
            /* $CC_email.= 'ralph@liveadmins.com, zeeshan.mir@livegreeter.com, rabia.rafaqat@livegreeter.com, zunaira@liveadmins.com, farooq.ghauri@liveadmins.com, schedule@liveadmins.com'; */

            $this->load->helper('email');
            $this->load->library('email');

            $this->email->from('no-reply@liveadmins.net', 'LES');
            $this->email->to($receiver_email, $receiver_name);
            //$this->email->cc($CC_email);
            //$this->email->bcc('naveed.zulfaqar@maavratech.com, muhammad.imran@maavratech.com');
            $this->email->subject($subject);
            $this->email->message($message);
            if ($this->input->post('ReportingTo') != '159') {
                $em = $this->email->send();
            }

            //echo $this->email->print_debugger();
            // sending email ends 
        }
        $data = array();

        $data['ID'] = $ID;

        $data['breakapprovalrequest'] = $this->Break_approval_request_model->get_a_breakapprovalrequest($data['ID']);

        if (count($data['breakapprovalrequest']) > 0) {
            redirect(File_BASE_URL . '/attendance/index');
        }


        $data['break'] = $this->Break_model->get_a_break($data);

        $data['break']['BreakDuration'] = CalculateTimeDifferencce($data['break']['BreakStart'], $data['break']['BreakEnd']);

        $this->load->view('attendance/breakapprovalrequest', $data);
    }

    public function deletebreak($ID) {
        if (!($this->session->userdata('role') == '4')) {

            // Helper File Include
            $this->load->helper('string');
            $this->load->helper('form');

            // Libraries File Include
            $this->load->library('form_validation');

            // Models File Include
            $this->load->model('Shift_details_model');

            $this->load->model('Employee_model');
            $this->load->model('Department_history_model');
            $this->load->model('Day_model');
            $this->load->model('Attendance_model');
            $this->load->model('Attendance_log_model');
            $this->load->model('Break_model');
            $this->load->model('Break_log_model');

            $data = array();

            if ($this->input->post()) {
                $data['ID'] = $this->input->post('ID');
                $data['AttendenceID'] = $this->input->post('AttendenceID');
                $data['Comments'] = str_replace("'", '', $this->input->post('comments'));
                $data['Comments'] = htmlspecialchars($data['Comments']);
                $data['Comments'] = iconv("utf-8", "ascii//TRANSLIT//IGNORE", $data['Comments']);
                $data['Comments'] = preg_replace("/^'|[^A-Za-z0-9\s-]|'$/", '', $data['Comments']); // lets remove utf-8 special characters except blank spaces
                $data['ModifiedDate'] = date("Y-m-d H:i:s");
                $this->Break_model->delete_break($data);

                $break = $this->Break_model->get_a_break($data);
                unset($data);

                $data['LogID'] = $break['ID'];
                $data['EmployeeID'] = $break['EmployeeID'];
                $data['AttendenceID'] = $break['AttendenceID'];
                $data['ShiftID'] = $break['ShiftID'];
                $data['Date'] = $break['Date'];
                $data['EndDate'] = $break['EndDate'];
                $data['BreakType'] = $break['BreakType'];
                $data['BreakStart'] = $break['BreakStart'];
                $data['BreakEnd'] = $break['BreakEnd'];
                $data['AddedBy'] = $break['AddedBy'];
                $data['Comments'] = str_replace("'", '', $break['Comments']);
                $data['Comments'] = htmlspecialchars($data['Comments']);
                $data['Comments'] = iconv("utf-8", "ascii//TRANSLIT//IGNORE", $data['Comments']);
                $data['Comments'] = preg_replace("/^'|[^A-Za-z0-9\s-]|'$/", '', $data['Comments']); // lets remove utf-8 special characters except blank spaces
                $data['DeletedBy'] = $break['DeletedBy'];
                $data['CheckedBy'] = $break['CheckedBy'];
                $data['IsActive'] = '0';
                $data['ModifiedDate'] = $break['ModifiedDate'];

                $this->Break_log_model->save_breaklog($data);

                /* sending email to Employee */

                $email_to = $this->Employee_model->get_employee_email($data['EmployeeID']);
                $email_from = $this->Employee_model->get_employee_email($this->session->userdata('id'));

                /* echo '<pre>'; print_r($email_to); echo '</pre>'; exit; */

                $sender_email = $email_from['OfficialEmailAddress'];
                $sender_name = $email_from['FirstName'] . ' ' . $email_from['LastName'];

                if ($email_from['PseudoFirstName']) {
                    $sender_name .= ' (' . $email_from['PseudoFirstName'] . ' ' . $email_from['PseudoLastName'] . ')';
                }
                $sender_designation = $email_from['JobTitle'];

                $receiver_email = $email_to['OfficialEmailAddress'];
                $receiver_name = $email_to['FirstName'] . ' ' . $email_to['LastName'];
                if ($email_to['PseudoFirstName']) {
                    $receiver_name .= ' (' . $email_to['PseudoFirstName'] . ' ' . $email_to['PseudoLastName'] . ')';
                }

                $FromTimeZone = 'GMT';
                $ToTimeZone = 'UP5';

                $BreakStart = ConvertTimeZone($data['BreakStart'], $FromTimeZone, $ToTimeZone);
                $BreakEnd = ConvertTimeZone($data['BreakEnd'], $FromTimeZone, $ToTimeZone);

                $BreakTime = CalculateTimeDifferencce($data['BreakStart'], $data['BreakEnd']);
                $BreakTime = CalculateWorkedTime($BreakTime);

                $breakdetail = '<strong>Date: </strong>' . $data['Date'] . '<br><br><strong>Break Start: </strong>' . $BreakStart . ' (PST)<br><strong>Break End: </strong>' . $BreakEnd . ' (PST)<br><strong>Break Duration: </strong>' . $BreakTime . '<br><strong>Break Type: </strong>' . $data['BreakType'] . '<br><br><strong>Add Break Comments: </strong>' . $data['Comments'] . '<br><br><strong>Break Deleted By: </strong>' . $sender_name;

                //Sending Email

                $subject = 'Break Deleted of Date ' . $data['Date'];
                $message = "Dear $receiver_name$receiver_pseudoname,<br><br>Your break has been deleted. Following are the details:<br><br>$breakdetail";
                $data_email = array();
                $data_email['email_body'] = $message;
                $message = $this->load->view('email-templates/default', $data_email, true);

                $this->load->helper('email');
                $this->load->library('email');

                $this->email->from('no-reply@liveadmins.net', 'LES');
                $this->email->to($receiver_email, $receiver_name);
                //$this->email->bcc('naveed.zulfaqar@maavratech.com, muhammad.imran@maavratech.com');
                $this->email->subject($subject);
                $this->email->message($message);
                $em = $this->email->send();

                //Sending Email again to sender

                $message = "Dear $sender_name,<br><br>You  break have deleted the break of $receiver_name. Following are the details:<br><br>$breakdetail";
                $data_email = array();
                $data_email['email_body'] = $message;
                $message = $this->load->view('email-templates/default', $data_email, true);

                $this->load->helper('email');
                $this->load->library('email');

                $this->email->from('no-reply@liveadmins.net', 'LES');
                $this->email->to($sender_email, $sender_name);
                //$this->email->bcc('naveed.zulfaqar@maavratech.com, muhammad.imran@maavratech.com');
                $this->email->subject($subject);
                $this->email->message($message);
                $em = $this->email->send();

                //echo $this->email->print_debugger();
                /* sending email ends */

                //calling helper function to get pending requests count
                count_pending_requests();
            }

            $data['ID'] = $ID;
            $EmployeeID = $this->uri->segment(4);
            $ShiftID = $this->uri->segment(5);
            $data['Date'] = $this->uri->segment(6);
            $data['Day'] = $this->uri->segment(7);
            $data['BLogID'] = $this->uri->segment(8);
            $data['AttendenceID'] = $this->uri->segment(9);

            $dayid = $this->Day_model->get_dayid($data['Day']);

            /**/
            $data['employee'] = $this->Employee_model->get_employee($EmployeeID);
            $data['breaklog'] = $this->Break_model->get_breaks($data);
            //echo '<pre>'; print_r($data['breaklog']); echo '</pre>';
            //$data['breaklog'] = $this->Break_log_model->get_breaklog($data);

            $this->load->view('attendance/deletebreak', $data);
        }
    }

    public function viewusagehistory($EmployeeID) {

        // Models File Include
        $this->load->model('Shift_details_model');

        $this->load->model('Usage_history_model');

        $data = array();

        $data['EmployeeID'] = $EmployeeID;
        $data['Date'] = $this->uri->segment(4);

        $data['usage_history'] = $this->Usage_history_model->get_usage_history($data['EmployeeID'], $data['Date']);

        /* echo '<pre>'; print_r($data['usage_history']); echo '</pre>'; exit; */


        $this->load->view('attendance/viewusagehistory', $data);
    }

    public function approve_restriction($ID) {

        // Models File Include
        $this->load->model('Employee_model');
        $this->load->model('Department_history_model');
        $this->load->model('Day_model');
        $this->load->model('Attendance_model');
        $this->load->model('Overtime_request_model');
        $this->load->model('Shiftrestriction_request_model');
        $this->load->model('Approve_ot_model');
        $this->load->model('Approve_sr_model');
        $this->load->model('Timing_restrictions_model');

        if ($this->input->post()) {

            $data['AttendanceID'] = $this->input->post('ID');
            $data['EmployeeID'] = $this->input->post('EmployeeID');
            $data['ApprovalStatus'] = 'y';
            $data['UpdatedBy'] = $this->session->userdata('id');
            $data['UpdatedDate'] = date('Y-m-d');
            $data['UpdatedComments'] = str_replace("'", '', $this->input->post('comments'));
            $data['UpdatedComments'] = htmlspecialchars($data['UpdatedComments']);
            //$data['UpdatedComments'] = iconv("utf-8", "ascii//TRANSLIT//IGNORE", $data['UpdatedComments']);
            //$data['UpdatedComments'] = preg_replace("/^'|[^A-Za-z0-9\s-]|'$/", '', $data['UpdatedComments']); // lets remove utf-8 special characters except blank spaces

            if ($this->input->post('ottype') == '0') { //ottype == 0 is OT Restriction
                $this->Overtime_request_model->update_ot_approval_request($data);
            } else if ($this->input->post('ottype') == '1') { //ottype == 1 is Shift Restriction
                $this->Shiftrestriction_request_model->update_sr_approval_request($data);
            }

            $Date = $this->input->post('Date');

            /* sending email to Employee */
            $email_to = $this->Employee_model->get_employee_email($data['EmployeeID']);

            $receiver_email = $email_to['OfficialEmailAddress'];
            $receiver_name = $email_to['FirstName'] . ' ' . $email_to['LastName'];
            if ($email_to['PseudoFirstName']) {
                $receiver_name .= ' (' . $email_to['PseudoFirstName'] . ' ' . $email_to['PseudoLastName'] . ')';
            }

            $email_from = $this->Employee_model->get_employee_email($data['UpdatedBy']);

            /* echo '<pre>'; print_r($email_to); echo '</pre>'; exit; */

            $sender_email = $email_from['OfficialEmailAddress'];
            $sender_name = $email_from['FirstName'] . ' ' . $email_from['LastName'];

            if ($email_from['PseudoFirstName']) {
                $sender_name .= ' (' . $email_from['PseudoFirstName'] . ' ' . $email_from['PseudoLastName'] . ')';
            }

            $sender_designation = $email_from['JobTitle'];

            $date = $Date . ' (' . date('l', strtotime($Date)) . ')';

            //$url = LES_BASE_URL . 'otrequests/index';
            //$link = '<a href=' . "$url" . ' target="_blank">Please click here to approve the over time requests</a>';

            $otdetail = '<strong>Date: </strong>' . $date . '<br><br><strong>Comments: </strong>' . $data['UpdatedComments'] . '<br><br><strong>Approved By: </strong>' . $sender_name;

            //Sending Email            
            if ($this->input->post('ottype') == '0') {
                $ot_type = 'Attendance OT';
            } else if ($this->input->post('ottype') == '1') {
                $ot_type = 'Shift OT';
            }

            $subject = "Approval of $ot_type Dated " . $date;
            $message = "Dear $receiver_name,<br><br>Your $ot_type of date $date has been approved. Following are the details:<br><br>$otdetail";
            $data_email = array();
            $data_email['email_body'] = $message;
            $message = $this->load->view('email-templates/default', $data_email, true);

            $this->load->helper('email');
            $this->load->library('email');

            $this->email->from('no-reply@liveadmins.net', 'LES');
            $this->email->to($receiver_email, $receiver_name);
            $this->email->bcc('naveed.zulfaqar@maavratech.com, muhammad.imran@maavratech.com');
            $this->email->subject($subject);
            $this->email->message($message);
            $em = $this->email->send();

            //Sending Email again to sender

            $message = "Dear $sender_name,<br><br>You have approved the $ot_type of $receiver_name for date $date. Following are the details:<br><br>$otdetail";
            $data_email = array();
            $data_email['email_body'] = $message;
            $message = $this->load->view('email-templates/default', $data_email, true);

            $this->load->helper('email');
            $this->load->library('email');

            $this->email->from('no-reply@liveadmins.net', 'LES');
            $this->email->to($sender_email, $sender_name);
            $this->email->bcc('naveed.zulfaqar@maavratech.com, muhammad.imran@maavratech.com');
            $this->email->subject($subject);
            $this->email->message($message);
            $em = $this->email->send();

            //echo $this->email->print_debugger();
            /* sending email ends */

            //calling helper function to get pending requests count
            count_pending_requests();
        }

        if (isset($_COOKIE['clientTimeZone'])) {
            $timezone = $_COOKIE['clientTimeZone'];
        } else {
            $timezone = 'UP5';
        }

        $data = array();

        $data['ID'] = $ID;
        $data['ottype'] = $this->uri->segment('4');

        $data['attendance'] = $this->Attendance_model->get_attendance($data);

        if ($data['ottype'] == '0') {
            $data['restriction_approval_requests'] = $this->Approve_ot_model->get_ot_approval_requests($data);
        } elseif ($data['ottype'] == '1') {
            $data['restriction_approval_requests'] = $this->Approve_sr_model->get_sr_approval_requests($data);
        }

        $InTime = $data['attendance']['InTime'];
        $OutTime = $data['attendance']['OutTime'];
        $data['attendance']['total_time'] = CalculateTimeDifferencce($InTime, $OutTime);

        $FromTimeZone = 'GMT';
        $ToTimeZone = $timezone;

        $dayid = $this->Day_model->get_dayid(date('l', strtotime($data['attendance']['Date'])));

        /* echo '<pre>'; print_r($dayid); echo '</pre>'; exit; */
        /**/
        $data['employee'] = $this->Employee_model->get_employee($data['attendance']['EmployeeID']);

        $data['employeeshift'] = $this->Department_history_model->get_employee_shift($data['attendance']['ShiftID'], $data['attendance']['EmployeeID'], $dayid);

        $data['employeeshift']['ShiftStartTime'] = ConvertTimeZone($data['employeeshift']['ShiftStartTime'], $FromTimeZone, $ToTimeZone);
        $data['employeeshift']['ShiftEndTime'] = ConvertTimeZone($data['employeeshift']['ShiftEndTime'], $FromTimeZone, $ToTimeZone);
        $data['employeeshift']['ShiftsHours'] = CalculateTimeDifferencce($data['employeeshift']['ShiftStartTime'], $data['employeeshift']['ShiftEndTime']);

        $data['attendance']['ShiftsHours'] = $data['employeeshift']['ShiftsHours'];
        $data['attendance']['Day'] = $dayid['DayName'];

        $data['TimingRestrictions'] = $this->Timing_restrictions_model->get_timing_restrictions($data['attendance']['EmployeeID']);

        //sending data to calculate worked duration
        $data['attendance']['total_time'] = calculated_work_duration($data['attendance']);

        $data['attendance']['InTime'] = ConvertTimeZone($InTime, $FromTimeZone, $ToTimeZone);
        $data['attendance']['OutTime'] = ConvertTimeZone($OutTime, $FromTimeZone, $ToTimeZone);

        if ($data['ottype'] == '0') { //ot
            $ot_restriction_extra_duration = $data['TimingRestrictions']['OTRestrictionExtraDuration'] * 60;
            $relaxed_shifts_hours = $data['employeeshift']['ShiftsHours'] + $ot_restriction_extra_duration;
        } elseif ($data['ottype'] == '1') { //sr
            $relaxed_shifts_hours = $data['employeeshift']['ShiftsHours'];
        }

        $data['attendance']['ot_duration'] = $data['attendance']['total_time'] - $relaxed_shifts_hours;

        $this->load->view('attendance/approve_restriction', $data);
    }

    public function approve_all_ot($ID) {

        // Models File Include
        $this->load->model('Employee_model');
        $this->load->model('Overtime_request_model');
        $this->load->model('Shiftrestriction_request_model');

        if ($this->input->post()) {
            $data['ID'] = $this->input->get_post('checkboxID', null);

            $data['ApprovalStatus'] = 'y';
            $data['UpdatedBy'] = $this->session->userdata('id');
            $data['UpdatedDate'] = date('Y-m-d');
            $data['UpdatedComments'] = str_replace("'", '', $this->input->post('comments'));
            $data['UpdatedComments'] = htmlspecialchars($data['UpdatedComments']);
            $data['UpdatedComments'] = iconv("utf-8", "ascii//TRANSLIT//IGNORE", $data['UpdatedComments']);
            $data['UpdatedComments'] = preg_replace("/^'|[^A-Za-z0-9\s-]|'$/", '', $data['UpdatedComments']); // lets remove utf-8 special characters except blank spaces

            if ($this->input->post('gettype') == 'approveot') {
                $this->Overtime_request_model->update_all_ot_request($data);
                $all_approved_ot_request = $this->Overtime_request_model->get_all_approved_ot_request($data);
            } else if ($this->input->post('gettype') == 'approvesr') {
                $this->Shiftrestriction_request_model->update_all_sr_request($data);
                $all_approved_ot_request = $this->Shiftrestriction_request_model->get_all_approved_sr_request($data);
            }


            /* sending email to Employee */
            /* echo '<pre>'; print_r($all_approved_ot_request); echo '</pre>'; exit; */
            /* echo 'EmployeeID '.$all_approved_ot_request[0]['EmployeeID']; */

            $email_to = $this->Employee_model->get_employee_email($all_approved_ot_request[0]['EmployeeID']);

            /* echo '<pre>'; print_r($email_to); echo '</pre>'; exit; */

            $receiver_email = $email_to['OfficialEmailAddress'];
            $receiver_name = $email_to['FirstName'] . ' ' . $email_to['LastName'];
            if ($email_to['PseudoFirstName']) {
                $receiver_name .= ' (' . $email_to['PseudoFirstName'] . ' ' . $email_to['PseudoLastName'] . ')';
            }

            $email_from = $this->Employee_model->get_employee_email($data['UpdatedBy']);

            /* echo '<pre>'; print_r($email_to); echo '</pre>'; exit; */

            $sender_email = $email_from['OfficialEmailAddress'];
            $sender_name = $email_from['FirstName'] . ' ' . $email_from['LastName'];

            if ($email_from['PseudoFirstName']) {
                $sender_name .= ' (' . $email_from['PseudoFirstName'] . ' ' . $email_from['PseudoLastName'] . ')';
            }

            $sender_designation = $email_from['JobTitle'];

            $otdetails = "<table style='border-spacing: 0px; border: 1px solid #eee;'>
			<tr style='background: #5087c7;color: #fff;'>
				<th style='border: 1px solid #eee; padding: 5px; border-spacing: 0px; font-weight: normal;'>Attendance Date</th>
				<th style='border: 1px solid #eee; padding: 5px; border-spacing: 0px; font-weight: normal;'>Requested Comments</th>
				<th style='border: 1px solid #eee; padding: 5px; border-spacing: 0px; font-weight: normal;'>Updated By</th>
				<th style='border: 1px solid #eee; padding: 5px; border-spacing: 0px; font-weight: normal;'>Updated Comments</th>
			</tr>";

            foreach ($all_approved_ot_request as $ot_request) {
                if ($this->input->post('gettype') == 'approveot') {
                    $requested_comments = $ot_request['OTComments'];
                } else if ($this->input->post('gettype') == 'approvesr') {
                    $requested_comments = $ot_request['SRComments'];
                }
                $otdetails .= "<tr>
					<td style='border: 1px solid #eee; padding: 5px; border-spacing: 0px;'>" . $ot_request['Date'] . " </td>
                                    <td style='border: 1px solid #eee; padding: 5px; border-spacing: 0px;'>" . $requested_comments . " </td>
					<td style='border: 1px solid #eee; padding: 5px; border-spacing: 0px;'>" . $ot_request['UpdatedBy']['FirstName'] . ' ' . $ot_request['UpdatedBy']['LastName'] . " </td>
					<td style='border: 1px solid #eee; padding: 5px; border-spacing: 0px;'>" . $ot_request['UpdatedComments'] . " </td>
				</tr>";
            }
            $otdetails .= "</table>";

            //Sending Email

            $month = date('M Y');

            $data_email = array();
            if ($this->input->post('gettype') == 'approveot') {
                $subject = 'Bulk OT Approved';
                $message = "Dear $receiver_name,<br><br>Your OT has been approved.<br>Following are the details:<br><br><br>";
            } else if ($this->input->post('gettype') == 'approvesr') {
                $subject = 'Bulk Shift OT Approved';
                $message = "Dear $receiver_name,<br><br>Your Shift OT has been approved.<br>Following are the details:<br><br><br>";
            }


            $message .= $otdetails;
            $data_email['email_body'] = $message;

            $message = $this->load->view('email-templates/default', $data_email, true);

            $this->load->helper('email');
            $this->load->library('email');

            $this->email->from('no-reply@liveadmins.net', 'LES');
            $this->email->to($receiver_email, $receiver_name);
            $this->email->bcc('naveed.zulfaqar@maavratech.com, muhammad.imran@maavratech.com');
            $this->email->subject($subject);
            $this->email->message($message);
            $em = $this->email->send();

            //Sending Email again to sender

            if ($this->input->post('gettype') == 'approveot') {
                $message = "Dear $sender_name,<br><br>You have approved the OT of $receiver_name.<br>Following are the details:<br><br><br>";
            } else if ($this->input->post('gettype') == 'approvesr') {
                $message = "Dear $sender_name,<br><br>You have approved the Shift OT of $receiver_name.<br>Following are the details:<br><br><br>";
            }

            $message .= $otdetails;
            $data_email = array();
            $data_email['email_body'] = $message;
            $message = $this->load->view('email-templates/default', $data_email, true);

            $this->load->helper('email');
            $this->load->library('email');

            $this->email->from('no-reply@liveadmins.net', 'LES');
            $this->email->to($sender_email, $sender_name);
            $this->email->bcc('naveed.zulfaqar@maavratech.com, muhammad.imran@maavratech.com');
            $this->email->subject($subject);
            $this->email->message($message);
            $em = $this->email->send();

            //echo $this->email->print_debugger();
            /* sending email ends */

            //calling helper function to get pending requests count
            count_pending_requests();
        }

        exit;
    }

    public function disapprove_restriction($ID) {

        // Models File Include
        $this->load->model('Employee_model');
        $this->load->model('Department_history_model');
        $this->load->model('Day_model');
        $this->load->model('Attendance_model');
        $this->load->model('Overtime_request_model');
        $this->load->model('Shiftrestriction_request_model');
        $this->load->model('Approve_ot_model');
        $this->load->model('Approve_sr_model');
        $this->load->model('Timing_restrictions_model');

        if ($this->input->post()) {

            $data['AttendanceID'] = $this->input->post('ID');
            $data['EmployeeID'] = $this->input->post('EmployeeID');
            $data['ApprovalStatus'] = 'n';
            $data['UpdatedBy'] = $this->session->userdata('id');
            $data['UpdatedDate'] = date('Y-m-d');
            $data['UpdatedComments'] = str_replace("'", '', $this->input->post('comments'));
            $data['UpdatedComments'] = htmlspecialchars($data['UpdatedComments']);
            //$data['UpdatedComments'] = iconv("utf-8", "ascii//TRANSLIT//IGNORE", $data['UpdatedComments']);
            //$data['UpdatedComments'] = preg_replace("/^'|[^A-Za-z0-9\s-]|'$/", '', $data['UpdatedComments']); // lets remove utf-8 special characters except blank spaces

            if ($this->input->post('ottype') == '0') { //ottype == 0 is OT Restriction
                $this->Overtime_request_model->update_ot_approval_request($data);
            } else if ($this->input->post('ottype') == '1') { //ottype == 1 is Shift Restriction
                $this->Shiftrestriction_request_model->update_sr_approval_request($data);
            }

            /* sending email to Employee */
            $email_to = $this->Employee_model->get_employee_email($data['EmployeeID']);

            $receiver_email = $email_to['OfficialEmailAddress'];
            $receiver_name = $email_to['FirstName'] . ' ' . $email_to['LastName'];
            if ($email_to['PseudoFirstName']) {
                $receiver_name .= ' (' . $email_to['PseudoFirstName'] . ' ' . $email_to['PseudoLastName'] . ')';
            }

            $email_from = $this->Employee_model->get_employee_email($data['UpdatedBy']);

            /* echo '<pre>'; print_r($email_to); echo '</pre>'; exit; */

            $sender_email = $email_from['OfficialEmailAddress'];
            $sender_name = $email_from['FirstName'] . ' ' . $email_from['LastName'];

            if ($email_from['PseudoFirstName']) {
                $sender_name .= ' (' . $email_from['PseudoFirstName'] . ' ' . $email_from['PseudoLastName'] . ')';
            }

            $sender_designation = $email_from['JobTitle'];

            $Date = $this->input->post('Date');

            $date = $Date . ' (' . date('l', strtotime($Date)) . ')';


            //$url = LES_BASE_URL . 'otrequests/index';
            //$link = '<a href=' . "$url" . ' target="_blank">Please click here to approve the over time requests</a>';

            $otdetail = '<strong>Date: </strong>' . $date . '<br><br><strong>Comments: </strong>' . $data['UpdatedComments'] . '<br><br><strong>Approved By: </strong>' . $sender_name;

            //Sending Email            
            if ($this->input->post('ottype') == '0') {
                $ot_type = 'Attendance OT';
            } else if ($this->input->post('ottype') == '1') {
                $ot_type = 'Shift OT';
            }

            $subject = "Disapproval of $ot_type Dated " . $date;
            $message = "Dear $receiver_name,<br><br>Your $ot_type of date $date has been disapproved. Following are the details:<br><br>$otdetail";
            $data_email = array();
            $data_email['email_body'] = $message;
            $message = $this->load->view('email-templates/default', $data_email, true);

            $this->load->helper('email');
            $this->load->library('email');

            $this->email->from('no-reply@liveadmins.net', 'LES');
            $this->email->to('naveed.zulfaqar@maavratech.com, muhammad.imran@maavratech.com');
            //$this->email->to($receiver_email, $receiver_name);
            //$this->email->bcc('naveed.zulfaqar@maavratech.com, muhammad.imran@maavratech.com');
            $this->email->subject($subject);
            $this->email->message($message);
            $em = $this->email->send();

            //Sending Email again to sender

            $message = "Dear $sender_name,<br><br>You have disapproved the $ot_type of $receiver_name for date $date. Following are the details:<br><br>$otdetail";
            $data_email = array();
            $data_email['email_body'] = $message;
            $message = $this->load->view('email-templates/default', $data_email, true);

            $this->load->helper('email');
            $this->load->library('email');

            $this->email->from('no-reply@liveadmins.net', 'LES');
            $this->email->to('naveed.zulfaqar@maavratech.com, muhammad.imran@maavratech.com');
            //$this->email->to($sender_email, $sender_name);
            //$this->email->bcc('naveed.zulfaqar@maavratech.com, muhammad.imran@maavratech.com');
            $this->email->subject($subject);
            $this->email->message($message);
            $em = $this->email->send();

            //echo $this->email->print_debugger();
            /* sending email ends */

            //calling helper function to get pending requests count
            count_pending_requests();
        }

        if (isset($_COOKIE['clientTimeZone'])) {
            $timezone = $_COOKIE['clientTimeZone'];
        } else {
            $timezone = 'UP5';
        }

        $data = array();

        $data['ID'] = $ID;
        $data['ottype'] = $this->uri->segment('4');

        $data['attendance'] = $this->Attendance_model->get_attendance($data);

        if ($data['ottype'] == '0') {
            $data['restriction_approval_requests'] = $this->Approve_ot_model->get_ot_approval_requests($data);
        } elseif ($data['ottype'] == '1') {
            $data['restriction_approval_requests'] = $this->Approve_sr_model->get_sr_approval_requests($data);
        }

        $InTime = $data['attendance']['InTime'];
        $OutTime = $data['attendance']['OutTime'];
        $data['attendance']['total_time'] = CalculateTimeDifferencce($InTime, $OutTime);

        $FromTimeZone = 'GMT';
        $ToTimeZone = $timezone;

        $dayid = $this->Day_model->get_dayid(date('l', strtotime($data['attendance']['Date'])));

        /* echo '<pre>'; print_r($dayid); echo '</pre>'; exit; */
        /**/
        $data['employee'] = $this->Employee_model->get_employee($data['attendance']['EmployeeID']);

        $data['employeeshift'] = $this->Department_history_model->get_employee_shift($data['attendance']['ShiftID'], $data['attendance']['EmployeeID'], $dayid);

        $data['employeeshift']['ShiftStartTime'] = ConvertTimeZone($data['employeeshift']['ShiftStartTime'], $FromTimeZone, $ToTimeZone);
        $data['employeeshift']['ShiftEndTime'] = ConvertTimeZone($data['employeeshift']['ShiftEndTime'], $FromTimeZone, $ToTimeZone);
        $data['employeeshift']['ShiftsHours'] = CalculateTimeDifferencce($data['employeeshift']['ShiftStartTime'], $data['employeeshift']['ShiftEndTime']);

        $data['attendance']['ShiftsHours'] = $data['employeeshift']['ShiftsHours'];
        $data['attendance']['Day'] = $dayid['DayName'];

        $data['TimingRestrictions'] = $this->Timing_restrictions_model->get_timing_restrictions($data['attendance']['EmployeeID']);

        //sending data to calculate worked duration
        $data['attendance']['total_time'] = calculated_work_duration($data['attendance']);

        $data['attendance']['InTime'] = ConvertTimeZone($InTime, $FromTimeZone, $ToTimeZone);
        $data['attendance']['OutTime'] = ConvertTimeZone($OutTime, $FromTimeZone, $ToTimeZone);

        if ($data['ottype'] == '0') { //ot
            $ot_restriction_extra_duration = $data['TimingRestrictions']['OTRestrictionExtraDuration'] * 60;
            $relaxed_shifts_hours = $data['employeeshift']['ShiftsHours'] + $ot_restriction_extra_duration;
        } elseif ($data['ottype'] == '1') { //sr
            $relaxed_shifts_hours = $data['employeeshift']['ShiftsHours'];
        }

        $data['attendance']['ot_duration'] = $data['attendance']['total_time'] - $relaxed_shifts_hours;

        $this->load->view('attendance/disapprove_restriction', $data);
    }

    public function restriction_approval_request($ID) {

        // Models File Include
        $this->load->model('Employee_model');
        $this->load->model('Department_history_model');
        $this->load->model('Day_model');
        $this->load->model('Attendance_model');
        $this->load->model('Break_model');
        $this->load->model('Overtime_request_model');
        $this->load->model('Shiftrestriction_request_model');
        $this->load->model('Daily_break_allowed_model');
        $this->load->model('Timing_restrictions_model');

        if (isset($_COOKIE['clientTimeZone'])) {
            $timezone = $_COOKIE['clientTimeZone'];
        } else {
            $timezone = 'UP5';
        }

        $data = array();

        $data['ID'] = $ID;
        $data['RestrictionType'] = $this->uri->segment('4');

        $data['attendance'] = $this->Attendance_model->get_attendance($data);

        $InTime = $data['attendance']['InTime'];
        $OutTime = $data['attendance']['OutTime'];
        $WorkedHours = CalculateTimeDifferencce($InTime, $OutTime);

        $FromTimeZone = 'GMT';
        $ToTimeZone = $timezone;

        $dayid = $this->Day_model->get_dayid(date('l', strtotime($data['attendance']['Date'])));
        /* echo '<pre>'; print_r($dayid); echo '</pre>'; exit; */

        $data['employee'] = $this->Employee_model->get_employee($data['attendance']['EmployeeID']);
        //echo '<pre>'; print_r($data); echo '</pre>'; exit;
        // employee can only apply his restriction approval request 2018-03-06
        if ($data['employee']['ID'] != $this->session->userdata('id')) {
            redirect(File_BASE_URL . '/attendance/index');
        }

        // code for salary lock 2018-03-02        
        $month = date('m', strtotime($data['attendance']['Date']));
        $year = date('Y', strtotime($data['attendance']['Date']));

        $month_start_date = date("$year-$month-21");
        $year_start_date = date("$year-12-21");

        if (strtotime($data['attendance']['Date']) >= strtotime($month_start_date)) {
            $month = $month + 1;
            if ($month == 13) {
                $month = 1;
            }
        }
        if (strtotime($data['attendance']['Date']) >= strtotime($year_start_date)) {
            $year = $year + 1;
        }

        $data['SalaryMonth'] = $month;
        $data['SalaryYear'] = $year;
        $data['DepartmentID'] = $data['employee']['DepartmentID'];

        //calling helper function to get current month salary lock data
        $salary_lock = department_salary_lock_data($data);

        // if lock status is y then salary is locked else not locked
        $lock_status = 'n';
        foreach ($salary_lock as $lock) {
            if ($lock['DepartmentID'] == $data['DepartmentID']) {
                $lock_status = $lock['LockStatus'];
            }
        }
        //echo '<br>lock_status ' . $lock_status;
        if ($lock_status == 'y') {
            redirect(File_BASE_URL . '/attendance/index');
        }
        //  code for salary lock 2018-03-02

        $data['employeeshift'] = $this->Department_history_model->get_employee_shift($data['attendance']['ShiftID'], $data['attendance']['EmployeeID'], $dayid);

        $data['employeeshift']['ShiftStartTime'] = ConvertTimeZone($data['employeeshift']['ShiftStartTime'], $FromTimeZone, $ToTimeZone);
        $data['employeeshift']['ShiftEndTime'] = ConvertTimeZone($data['employeeshift']['ShiftEndTime'], $FromTimeZone, $ToTimeZone);
        $data['employeeshift']['ShiftsHours'] = CalculateTimeDifferencce($data['employeeshift']['ShiftStartTime'], $data['employeeshift']['ShiftEndTime']);

        $data['attendance']['ShiftsHours'] = $data['employeeshift']['ShiftsHours'];
        $data['attendance']['Day'] = $dayid['DayName'];
        $data['attendance']['DepartmentID'] = $data['employeeshift']['DepartmentID'];

        $data['attendance']['DailyBreakAllowed'] = $this->Daily_break_allowed_model->get_daily_break_allowed($data['attendance']['EmployeeID']);

        $data['attendance']['BreakTime'] = $this->Attendance_model->getBreakTime($data['attendance']['EmployeeID'], $data['attendance']['ShiftID'], $data['attendance']['Date'], $data['attendance']['ID']);

        //sending data to calculate worked duration
        $data['attendance']['total_time'] = calculated_work_duration($data['attendance']);

        $data['attendance']['InTime'] = ConvertTimeZone($InTime, $FromTimeZone, $ToTimeZone);
        $data['attendance']['OutTime'] = ConvertTimeZone($OutTime, $FromTimeZone, $ToTimeZone);

        $data['TimingRestrictions'] = $this->Timing_restrictions_model->get_timing_restrictions($data['attendance']['EmployeeID']);

        // employee can only apply his restriction type 2018-03-06 start
        if ($data['TimingRestrictions']['OTRestriction'] == '1') {
            $restriction_type = 'ot';
        } else if ($data['TimingRestrictions']['ShiftRestriction'] == '1') {
            $restriction_type = 'sr';
        }
        if ($data['RestrictionType'] != $restriction_type) {
            redirect(File_BASE_URL . '/attendance/index');
        }
        // employee can only apply his restriction type 2018-03-06 ends

        if ($data['RestrictionType'] == 'sr') {
            $calculated_worked_hours = $this->uri->segment('5');
            $data['attendance']['ot_duration'] = $data['attendance']['total_time'] - $calculated_worked_hours;
        } else if ($data['RestrictionType'] == 'ot') {

            //echo '<pre>'; print_r($data); echo '</pre>';
            $ot_restriction_extra_duration = $data['TimingRestrictions']['OTRestrictionExtraDuration'] * 60;
            $relaxed_shifts_hours = $data['employeeshift']['ShiftsHours'] + $ot_restriction_extra_duration;

            $data['attendance']['ot_duration'] = $data['attendance']['total_time'] - $relaxed_shifts_hours;
        }

        if ($this->input->post()) {

            $data_to_save = array();

            $data_to_save['AttendanceID'] = $this->input->post('ID');
            $data_to_save['EmployeeID'] = $this->input->post('EmployeeID');

            if ($this->input->post('RestrictionType') == 'sr') {
                $data_to_save['SRComments'] = str_replace("'", '', $this->input->post('comments'));
                $data_to_save['SRComments'] = htmlspecialchars($data_to_save['SRComments']);
                //$data_to_save['SRComments'] = iconv("utf-8", "ascii//TRANSLIT//IGNORE", $data_to_save['SRComments']);
                //$data_to_save['SRComments'] = preg_replace("/^'|[^A-Za-z0-9\s-]|'$/", '', $data_to_save['SRComments']); // lets remove utf-8 special characters except blank spaces
            } else if ($this->input->post('RestrictionType') == 'ot') {
                $data_to_save['OTComments'] = str_replace("'", '', $this->input->post('comments'));
                $data_to_save['OTComments'] = htmlspecialchars($data_to_save['OTComments']);
                //$data_to_save['OTComments'] = iconv("utf-8", "ascii//TRANSLIT//IGNORE", $data_to_save['OTComments']);
                //$data_to_save['OTComments'] = preg_replace("/^'|[^A-Za-z0-9\s-]|'$/", '', $data_to_save['OTComments']); // lets remove utf-8 special characters except blank spaces
            }

            $data_to_save['RequestedTo'] = $this->input->post('RequestedTo');
            $data_to_save['RequestedDate'] = date('Y-m-d');
            $data_to_save['ApprovalStatus'] = 'p';

            if ($this->input->post('RestrictionType') == 'sr') {
                $this->Shiftrestriction_request_model->update_sr_request($data_to_save);
            } else if ($this->input->post('RestrictionType') == 'ot') {
                $this->Overtime_request_model->update_ot_request($data_to_save);
            }

            /* sending email to Employee */
            $email_to = $this->Employee_model->get_employee_email($data_to_save['RequestedTo']);

            $receiver_email = $email_to['OfficialEmailAddress'];
            $receiver_name = $email_to['FirstName'] . ' ' . $email_to['LastName'];
            if ($email_to['PseudoFirstName']) {
                $receiver_name .= ' (' . $email_to['PseudoFirstName'] . ' ' . $email_to['PseudoLastName'] . ')';
            }

            $email_from = $this->Employee_model->get_employee_email($data_to_save['EmployeeID']);

            /* echo '<pre>'; print_r($email_to); echo '</pre>'; exit; */

            $sender_email = $email_from['OfficialEmailAddress'];
            $sender_name = $email_from['FirstName'] . ' ' . $email_from['LastName'];

            if ($email_from['PseudoFirstName']) {
                $sender_name .= ' (' . $email_from['PseudoFirstName'] . ' ' . $email_from['PseudoLastName'] . ')';
            }

            $sender_designation = $email_from['JobTitle'];

            $date = $data_to_save['RequestedDate'] . ' (' . date('l', strtotime($data_to_save['RequestedDate'])) . ')';

            $otdetail = '<strong>Date: </strong>' . $date . '<br><br><strong>Comments: </strong>' . $data_to_save['OTComments'] . '<br><br><strong>Requested To: </strong>' . $receiver_name;

            if ($this->input->post('RestrictionType') == 'sr') {

                $url = LES_BASE_URL . 'approvesr/index';
                $link = '<a href=' . "$url" . ' target="_blank">Please click here to approve the shift restriction requests</a>';
                $subject = 'Shift Restriction Approval Request Dated ' . $data_to_save['RequestedDate'];
                $message = "Dear $receiver_name,<br><br>$sender_name has sent you the shift restriction request for approval. Following are the details:<br><br>$otdetail<br><br>$link";
            } else if ($this->input->post('RestrictionType') == 'ot') {

                $url = LES_BASE_URL . 'approveot/index';
                $link = '<a href=' . "$url" . ' target="_blank">Please click here to approve the over time requests</a>';
                $subject = 'OT Approval Request Dated ' . $data_to_save['RequestedDate'];
                $message = "Dear $receiver_name,<br><br>$sender_name has sent you the over time request for approval. Following are the details:<br><br>$otdetail<br><br>$link";
            }

            //Sending Email	
            $data_email = array();
            $data_email['email_body'] = $message;
            $message = $this->load->view('email-templates/default', $data_email, true);

            $this->load->helper('email');
            $this->load->library('email');
            if ($receiver_email != "rehan@liveadmins.com") {
                $this->email->from('no-reply@liveadmins.net', 'LES');
                $this->email->to($receiver_email, $receiver_name);
                //$this->email->bcc('naveed.zulfaqar@maavratech.com, muhammad.imran@maavratech.com');
                $this->email->bcc('naveed.zulfaqar@maavratech.com');
                $this->email->subject($subject);
                $this->email->message($message);
                $em = $this->email->send();
            }
            //echo $this->email->print_debugger();
            /* sending email ends */

            //calling helper function to get pending requests count
            count_pending_requests();
        }

        $this->load->view('attendance/restriction_approval_request', $data);
    }

    public function view_restriction_details($ID) {

        // Models File Include
        $this->load->model('Employee_model');
        $this->load->model('Attendance_model');
        $this->load->model('Department_history_model');
        $this->load->model('Day_model');
        $this->load->model('Overtime_request_model');
        $this->load->model('Shiftrestriction_request_model');
        $this->load->model('Daily_break_allowed_model');
        $this->load->model('Timing_restrictions_model');

        if (isset($_COOKIE['clientTimeZone'])) {
            $timezone = $_COOKIE['clientTimeZone'];
        } else {
            $timezone = 'UP5';
        }

        $data = array();

        $data['ID'] = $ID;
        $data['RestrictionType'] = $this->uri->segment('4');

        $data['attendance'] = $this->Attendance_model->get_attendance($data);
        if ($data['RestrictionType'] == 'sr') {
            $data['restriction_request'] = $this->Shiftrestriction_request_model->get_sr_request($data['ID']);
        } else if ($data['RestrictionType'] == 'ot') {
            $data['restriction_request'] = $this->Overtime_request_model->get_ot_request($data['ID']);
        }

        $InTime = $data['attendance']['InTime'];
        $OutTime = $data['attendance']['OutTime'];
        $WorkedHours = CalculateTimeDifferencce($InTime, $OutTime);

        $FromTimeZone = 'GMT';
        $ToTimeZone = $timezone;

        $dayid = $this->Day_model->get_dayid(date('l', strtotime($data['attendance']['Date'])));
        /* echo '<pre>'; print_r($dayid); echo '</pre>'; exit; */
        /**/
        //$data['employee'] = $this->Employee_model->get_employee($data['ot_request_details']['EmployeeID']);
        $data['employee'] = $this->Employee_model->get_employee($data['attendance']['EmployeeID']);
        /* echo '<pre>'; print_r($data['employee']); echo '</pre>'; exit; */

        $data['employeeshift'] = $this->Department_history_model->get_employee_shift($data['attendance']['ShiftID'], $data['attendance']['EmployeeID'], $dayid);

        $data['employeeshift']['ShiftStartTime'] = ConvertTimeZone($data['employeeshift']['ShiftStartTime'], $FromTimeZone, $ToTimeZone);
        $data['employeeshift']['ShiftEndTime'] = ConvertTimeZone($data['employeeshift']['ShiftEndTime'], $FromTimeZone, $ToTimeZone);
        $data['employeeshift']['ShiftsHours'] = CalculateTimeDifferencce($data['employeeshift']['ShiftStartTime'], $data['employeeshift']['ShiftEndTime']);

        $data['attendance']['ShiftsHours'] = $data['employeeshift']['ShiftsHours'];
        $data['attendance']['Day'] = $dayid['DayName'];
        $data['attendance']['DepartmentID'] = $data['employeeshift']['DepartmentID'];

        $data['attendance']['DailyBreakAllowed'] = $this->Daily_break_allowed_model->get_daily_break_allowed($data['attendance']['EmployeeID']);

        $data['attendance']['BreakTime'] = $this->Attendance_model->getBreakTime($data['attendance']['EmployeeID'], $data['attendance']['ShiftID'], $data['attendance']['Date'], $data['attendance']['ID']);

        //sending data to calculate worked duration
        $data['attendance']['EmployeeDetails'] = $data['employee'];
        $data['attendance']['total_time'] = calculated_work_duration($data['attendance']);

        $data['attendance']['InTime'] = ConvertTimeZone($InTime, $FromTimeZone, $ToTimeZone);
        $data['attendance']['OutTime'] = ConvertTimeZone($OutTime, $FromTimeZone, $ToTimeZone);

        $data['TimingRestrictions'] = $this->Timing_restrictions_model->get_timing_restrictions($data['attendance']['EmployeeID']);

        if ($data['RestrictionType'] == 'sr') {
            $calculated_worked_hours = $this->uri->segment('5');
            $data['attendance']['ot_duration'] = $data['attendance']['total_time'] - $calculated_worked_hours;
            if ($this->session->userdata('id') == '321') {
                //echo '<br><br>worked_hours ' . $calculated_worked_hours;
                //echo '<br>calculated_worked_hours ' . CalculateWorkedTime($calculated_worked_hours);
                //echo '<br>total_time ' . CalculateWorkedTime($data['attendance']['total_time']);
                //echo '<br>ot_duration ' . CalculateWorkedTime($data['attendance']['ot_duration']);
            }
        } else if ($data['RestrictionType'] == 'ot') {

            $ot_restriction_extra_duration = $data['TimingRestrictions']['OTRestrictionExtraDuration'] * 60;
            $relaxed_shifts_hours = $data['employeeshift']['ShiftsHours'] + $ot_restriction_extra_duration;

            $data['attendance']['ot_duration'] = $data['attendance']['total_time'] - $relaxed_shifts_hours;
        }


        $this->load->view('attendance/view_restriction_details', $data);
    }

    public function punctuality_request($ID) {

        // Models File Include
        $this->load->model('Employee_model');
        $this->load->model('Department_history_model');
        $this->load->model('Day_model');
        $this->load->model('Attendance_model');
        $this->load->model('Punctuality_request_model');


        if (isset($_COOKIE['clientTimeZone'])) {
            $timezone = $_COOKIE['clientTimeZone'];
        } else {
            $timezone = 'UP5';
        }

        if ($this->input->post()) {

            $data['AttendanceID'] = $this->input->post('ID');
            $data['EmployeeID'] = $this->input->post('EmployeeID');
            $data['PunctualityType'] = $this->input->post('PunctualityType');
            $data['PunctualityComments'] = str_replace("'", '', $this->input->post('comments'));
            $data['PunctualityComments'] = htmlspecialchars($data['PunctualityComments']);
            $data['PunctualityComments'] = iconv("utf-8", "ascii//TRANSLIT//IGNORE", $data['PunctualityComments']);
            $data['PunctualityComments'] = preg_replace("/^'|[^A-Za-z0-9\s-]|'$/", '', $data['PunctualityComments']); // lets remove utf-8 special characters except blank spaces
            $data['RequestedTo'] = $this->input->post('RequestedTo');
            $data['RequestedDate'] = date('Y-m-d');
            $data['ApprovalStatus'] = 'p';

            $this->Punctuality_request_model->update_punctuality_request($data);

            /* sending email to Employee */
            $email_to = $this->Employee_model->get_employee_email($data['RequestedTo']);

            $receiver_email = $email_to['OfficialEmailAddress'];
            $receiver_name = $email_to['FirstName'] . ' ' . $email_to['LastName'];
            if ($email_to['PseudoFirstName']) {
                $receiver_name .= ' (' . $email_to['PseudoFirstName'] . ' ' . $email_to['PseudoLastName'] . ')';
            }

            $email_from = $this->Employee_model->get_employee_email($data['EmployeeID']);

            /* echo '<pre>'; print_r($email_to); echo '</pre>'; exit; */

            $sender_email = $email_from['OfficialEmailAddress'];
            $sender_name = $email_from['FirstName'] . ' ' . $email_from['LastName'];

            if ($email_from['PseudoFirstName']) {
                $sender_name .= ' (' . $email_from['PseudoFirstName'] . ' ' . $email_from['PseudoLastName'] . ')';
            }

            $sender_designation = $email_from['JobTitle'];

            $date = $data['RequestedDate'] . ' (' . date('l', strtotime($data['RequestedDate'])) . ')';

            if ($data['PunctualityType'] == 0) {
                $type = 'Late Arrival';
            } else {
                $type = 'Early Exit';
            }

            $url = LES_BASE_URL . 'punctualityrequests/index';
            $link = '<a href=' . "$url" . ' target="_blank">Please click here to approve the punctuality requests</a>';

            $punctualitydetail = '<strong>Date: </strong>' . $date . '<br><br><strong>Punctuality Type: </strong>' . $type . '<br><br><strong>Comments: </strong>' . $data['PunctualityComments'] . '<br><br><strong>Requested To: </strong>' . $receiver_name;

            //Sending Email	

            $subject = 'Punctuality Request Dated ' . $data['RequestedDate'];
            $message = "Dear $receiver_name,<br><br>$sender_name has sent you the punctuality request for approval. Following are the details:<br><br>$punctualitydetail<br><br>$link";
            $data_email = array();
            $data_email['email_body'] = $message;
            $message = $this->load->view('email-templates/default', $data_email, true);

            $this->load->helper('email');
            $this->load->library('email');

            $this->email->from('no-reply@liveadmins.net', 'LES');
            $this->email->to($receiver_email, $receiver_name);
            //$this->email->bcc('naveed.zulfaqar@maavratech.com, muhammad.imran@maavratech.com');
            $this->email->subject($subject);
            $this->email->message($message);
            $em = $this->email->send();

            //Sending Email again to sender

            $message = "Dear $sender_name,<br><br>You have sent the punctuality request for approval to $receiver_name. Following are the details:<br><br> $punctualitydetail";
            $data_email = array();
            $data_email['email_body'] = $message;
            $message = $this->load->view('email-templates/default', $data_email, true);

            $this->load->helper('email');
            $this->load->library('email');

            $this->email->from('no-reply@liveadmins.net', 'LES');
            $this->email->to($sender_email, $sender_name);
            //$this->email->bcc('naveed.zulfaqar@maavratech.com, muhammad.imran@maavratech.com');
            $this->email->subject($subject);
            $this->email->message($message);
            $em = $this->email->send();

            //echo $this->email->print_debugger();
            /* sending email ends */
        }

        $data = array();

        $data['ID'] = $ID;
        $data['PunctualityType'] = $this->uri->segment(4);
        //$data['SwapFrom'] = $Date;

        $data['attendance'] = $this->Attendance_model->get_attendance($data);

        $InTime = $data['attendance']['InTime'];
        $OutTime = $data['attendance']['OutTime'];

        $FromTimeZone = 'GMT';
        $ToTimeZone = $timezone;
        $data['attendance']['InTime'] = ConvertTimeZone($InTime, $FromTimeZone, $ToTimeZone);
        $data['attendance']['OutTime'] = ConvertTimeZone($OutTime, $FromTimeZone, $ToTimeZone);

        $dayid = $this->Day_model->get_dayid(date('l', strtotime($data['attendance']['Date'])));

        /* echo '<pre>'; print_r($dayid); echo '</pre>'; exit; */
        /**/
        $data['employee'] = $this->Employee_model->get_employee($data['attendance']['EmployeeID']);

        $data['employeeshift'] = $this->Department_history_model->get_employee_shift($data['attendance']['ShiftID'], $data['attendance']['EmployeeID'], $dayid);
        $data['employeeshift']['ShiftStartTime'] = ConvertTimeZone($data['employeeshift']['ShiftStartTime'], $FromTimeZone, $ToTimeZone);
        $data['employeeshift']['ShiftEndTime'] = ConvertTimeZone($data['employeeshift']['ShiftEndTime'], $FromTimeZone, $ToTimeZone);

        $this->load->view('attendance/punctuality_request', $data);
    }

    public function SystemCheckIn($EmployeeId) {

        // Models File Include
        $this->load->model('Attendance_model');
        $this->load->model('Department_history_model');

        $data = array();

        //getting shift id
        $shifts = $this->Department_history_model->get_employee_shift_by_EID($EmployeeId);
        $data['EmployeeID'] = $EmployeeId;
        $data['ShiftID'] = $shifts['ShiftID'];
        $data['Date'] = date("Y-m-d");
        $InTime = date('h:i a');

        if (isset($_COOKIE['clientTimeZone'])) {
            $timezone = $_COOKIE['clientTimeZone'];
        } else {
            $timezone = 'UP5';
        }
        $FromTimeZone = $timezone;
        $ToTimeZone = 'GMT';
        $data['InTime'] = ConvertTimeZone($InTime, $FromTimeZone, $ToTimeZone);
        $data['OutTime'] = NULL;

        $data['AddedBy'] = $EmployeeId;
        $data['CheckedBy'] = 0;

        $Time = ConvertTimeZone(date('h:i a'), $FromTimeZone, $ToTimeZone);
        $data['ModifiedDate'] = date("Y-m-d H:i:s", strtotime($Time));

        if ($data['ShiftID'] != NULL || $data['ShiftID'] != '') {
            $this->Attendance_model->SystemCheckInOut($data);
            $msg = 'Attendance Time In Marked Successfully.';
        } else {
            $msg = 'Attendance Time In Cannot Be Marked Because No Shift is Assigned to You.';
        }


        echo $msg . '<br><br>';
        echo print_r($data);
    }

    public function SystemCheckOut($EmployeeId) {

        // Models File Include
        $this->load->model('Attendance_model');
        $this->load->model('Department_history_model');

        $data = array();

        //getting shift id
        $shifts = $this->Department_history_model->get_employee_shift_by_EID($EmployeeId);
        $data['EmployeeID'] = $EmployeeId;
        $data['ShiftID'] = $shifts['ShiftID'];
        $data['Date'] = $this->Attendance_model->get_employee_timeindate($data);
        $data['EndDate'] = date("Y-m-d");
        $OutTime = date('h:i a');

        if (isset($_COOKIE['clientTimeZone'])) {
            $timezone = $_COOKIE['clientTimeZone'];
        } else {
            $timezone = 'UP5';
        }
        $FromTimeZone = $timezone;
        $ToTimeZone = 'GMT';
        $data['OutTime'] = ConvertTimeZone($OutTime, $FromTimeZone, $ToTimeZone);

        $data['AddedBy'] = $EmployeeId;
        $data['CheckedBy'] = 0;

        $Time = ConvertTimeZone(date('h:i a'), $FromTimeZone, $ToTimeZone);
        $data['ModifiedDate'] = date("Y-m-d H:i:s", strtotime($Time));

        if ($data['ShiftID'] != NULL || $data['ShiftID'] != '') {
            $this->Attendance_model->SystemCheckInOut($data);
            $msg = 'Attendance Time Out Marked Successfully.';
        } else {
            $msg = 'Attendance Time Out Cannot Be Marked Because No Shift is Assigned to You.';
        }

        echo $msg . '<br><br>';
        echo print_r($data);
    }

    public function test_email() {

        $this->load->helper('email');

        // Libraries File Include
        $this->load->library('email');

        $subject = 'Break Approval Dated ';
        $message = "Dear Testing Message<br>";
        $data = array();
        $data['email_body'] = $message;
        $message = $this->load->view('email-templates/default', $data, true);
        print_r($message);
        $this->email->from('no-reply@liveadmins.net', 'LES');
        $this->email->to('muhammad.imran@maavratech.com', 'Muhammad Imran');
        $this->email->subject($subject);
        $this->email->message($message);

        $this->email->send();
        $rep = $this->email->print_debugger();
        print_r($rep);
    }

}
