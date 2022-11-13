<?php
function OpenCon()
{
   //Get Heroku ClearDB connection information
    $cleardb_url = parse_url(getenv("CLEARDB_DATABASE_URL"));
    $cleardb_server = $cleardb_url["host"];
    $cleardb_username = $cleardb_url["user"];
    $cleardb_password = $cleardb_url["pass"];
    $cleardb_db = substr($cleardb_url["path"],1);
    $active_group = 'default';
    $query_builder = TRUE;

    // if connection died, echo error message
    $conn = mysqli_connect($cleardb_server, $cleardb_username, $cleardb_password, $cleardb_db) or die("Connect failed: %s\n" . $conn->error);

    return $conn;
}

function CloseCon($conn)
{
    $conn->close();
}

// get_student_realname: get realname of student with id $id
function get_student_realname($student_id) {
    // get the connection
    $conn = OpenCon();
    // create the query
    $sql = "SELECT realname FROM student WHERE studentID = $student_id";
    // execute the query
    $result = $conn->query($sql);
    // get the realname
    $realname = $result->fetch_assoc()['realname'];
    // close the connection
    CloseCon($conn);
    // return the realname
    return $realname;
}

// get_student_info: get all info of student with id $id. return format is JSON
function get_student_info($student_id) {
    // get the connection
    $conn = OpenCon();
    // create the query
    $sql = "SELECT * FROM student WHERE studentID = $student_id";
    // execute the query
    $result = $conn->query($sql);
    // get the student info
    $student_info = $result->fetch_assoc();
    // close the connection
    CloseCon($conn);
    // return the student info
    return json_encode($student_info);
}

// get_teacher_realname: get realname of teacher with id $id
function get_teacher_realname($teacher_id) {
    // get the connection
    $conn = OpenCon();
    // create the query
    $sql = "SELECT realname FROM teacher WHERE teacherID = $teacher_id";
    // execute the query
    $result = $conn->query($sql);
    // get the realname
    $realname = $result->fetch_assoc()['realname'];
    // close the connection
    CloseCon($conn);
    // return the realname
    return $realname;
}

// get_healthworker_realname: get realname of healthworker with id $id
function get_healthworker_realname($healthworker_id) {
    // get the connection
    $conn = OpenCon();
    // create the query
    $sql = "SELECT realname FROM health_worker WHERE health_workerID = $healthworker_id";
    // execute the query
    $result = $conn->query($sql);
    // get the realname
    $realname = $result->fetch_assoc()['realname'];
    // close the connection
    CloseCon($conn);
    // return the realname
    return $realname;
}

function get_students_by_teacher($teacher_id) {
    // get the connection
    $conn = OpenCon();
    // create the query
    $sql = "SELECT studentID, realname FROM student WHERE teacherID = $teacher_id";
    // execute the query
    $result = $conn->query($sql);
    // get the student info
    $students = $result->fetch_all(MYSQLI_ASSOC);
    // close the connection
    CloseCon($conn);
    // return the student info
    return json_encode($students);
}

function get_students_by_healthworker($healthworker_id) {
    // get the connection
    $conn = OpenCon();
    // create the query
    $sql = "SELECT studentID, realname FROM student WHERE health_workerID = '$healthworker_id'";
    // execute the query
    $result = $conn->query($sql);
    // get the student info
    $students = $result->fetch_all(MYSQLI_ASSOC);
    // close the connection
    CloseCon($conn);
    // return the student info
    return json_encode($students);
}

// get_gradeReport: get all grades of student with id $id. return format is JSON
function get_gradeReport($student_id) {
    // get the connection
    $conn = OpenCon();
    // create the query
    $sql = "SELECT * FROM grades WHERE studentID = $student_id";
    // execute the query
    $result = $conn->query($sql);
    // get the grade info
    $grade_info = $result->fetch_all(MYSQLI_ASSOC);
    // close the connection
    CloseCon($conn);
    // return the grade info
    return json_encode($grade_info);
}

// get_vacc_record: get all vacc records with studentID $id. return format is JSON
function get_vacc_record($student_id) {
    // get the connection
    $conn = OpenCon();
    // create the query
    $sql = "SELECT * FROM vaccination_records WHERE studentID = $student_id";
    // execute the query
    $result = $conn->query($sql);
    // get the vacc record info
    $vacc_record_info = $result->fetch_all(MYSQLI_ASSOC);
    // close the connection
    CloseCon($conn);
    // return the vacc record info
    return json_encode($vacc_record_info);
}

// update_grade: update gradeReport with gradeReportID $id. given gradeReportID, grade1, grade2 and grade3.
function update_grade($grade_report_id, $grade1, $grade2, $grade3) {
    // get the connection
    $conn = OpenCon();
    // create the query
    $sql = "UPDATE grades SET first_grade = $grade1, second_grade = $grade2, final_grade = $grade3 WHERE gradeReportID = $grade_report_id";
    // execute the query
    $conn->query($sql);
    // close the connection
    CloseCon($conn);
}

// update_vacc_record: update vacc record with recordID $id. given recordID, 3 vacc names and 3 vacc dates.
function update_vacc_record($record_id, $vacc1, $vacc2, $vacc3, $date1, $date2, $date3) {
    // get the connection
    $conn = OpenCon();
    // create the query
    $sql = "UPDATE vaccination_records SET first_vaccine = '$vacc1', second_vaccine = '$vacc2', third_vaccine = '$vacc3', first_vaccine_date = '$date1', second_vaccine_date = '$date2', third_vaccine_date = '$date3' WHERE recordID = $record_id";
    echo $sql;
    // execute the query
    $conn->query($sql);
    // close the connection
    CloseCon($conn);
}

// check_login_info_in_table: check if login info is in table $table. given l $username and $password. would return row if found, otherwise return false.
function check_login_info_in_table($login_username, $login_password, $table){
    // get the connection
    $conn = OpenCon();
    // create the query
    $sql = "SELECT * FROM $table WHERE username = '$login_username' AND password = '$login_password'";
    // execute the query
    $result = $conn->query($sql);
    // get the associated info
    $login_info = $result->fetch_assoc();
    // close the connection
    CloseCon($conn);
    // if the login info is not empty, return login info. If empty, return false.
    if(!empty($login_info)){
        return $login_info;
    }
    else{
        return false;
    }
}

// validate_login: validate the login info. given username and password.
function validate_login($username, $password){
    // possible tables: student, teacher, health_worker
    $table = "student";
    $login_info = check_login_info_in_table($username, $password, $table);
    if($login_info){
        $returnVal = array(
            "id" => $login_info['studentID'],
            "status" => "student"
        );
        return json_encode($returnVal);
    }
    
    $table = "teacher";
    $login_info = check_login_info_in_table($username, $password, $table);
    if($login_info){
        $returnVal = array(
            "id" => $login_info['teacherID'],
            "status" => "teacher"
        );
        return json_encode($returnVal);
    }
    
    $table = "health_worker";
    $login_info = check_login_info_in_table($username, $password, $table);
    if($login_info){
        $returnVal = array(
            "id" => $login_info['health_workerID'],
            "status" => "health_worker"
        );
        return json_encode($returnVal);
    }
    return false;
}
