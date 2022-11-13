<?php
include '../sql-queries.php';
session_start();

// get id and status
$id = $_SESSION['id'];
$status = $_SESSION['status'];

// sanity check: status should be student. if not, go back to index.php
if ($status != "student") {
    header("Location: https://summer2022csc261.herokuapp.com/index.php?error=1");
    exit;
}

// get the realname
$realname = get_student_realname($id);
// get student info
$student_info = get_student_info($id);

// from student_info (JSON), get teacherID and health_workerID
$teacherID = json_decode($student_info)->teacherID;
$health_workerID = json_decode($student_info)->health_workerID;

// get teacherName and health_workerName
$teacherName = get_teacher_realname($teacherID);
$health_workerName = get_healthworker_realname($health_workerID);

$grade = get_gradeReport($id);
// get the vacc record in JSON format
$vacc_record = get_vacc_record($id);

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta name="description" content="" />
    <title><?php echo $realname ?> Dashboard</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-0evHe/X+R7YkIZDRvuzKMRqM+OrBnVFBL6DOitfPri4tjfHxaWutUpFmBp4vmVor" crossorigin="anonymous" />

    <link rel="stylesheet" href="https://use.typekit.net/jdw6sjn.css" />

    <link href="../css/attached.css" rel="stylesheet" />
</head>

<body class="text-center">
    <main class="w-100 m-auto">
        <div style="max-width:1260px;margin: auto">
            <div class="titleBar" style="text-align: left">
                <svg class="w-6 h-6 mb-4" style="width: 60px; height: 60px" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path d="M12 14l9-5-9-5-9 5 9 5z"></path>
                    <path d="M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z"></path>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5zm0 0l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14zm-4 6v-7.5l4-2.222"></path>
                </svg>
                <span style="font-size: 32px">Hi, <?php echo $realname ?>!</span>
                <button type="button" class="btn btn-outline-primary btn-sm" style="float:right" onclick="window.print()">Print results</button>
                <button type="button" class="btn btn-outline-primary btn-sm" style="float:right;margin-right:15px" onclick="window.location.href='https://summer2022csc261.herokuapp.com/logout.php'">Log out</button>
            </div>
            <div class="container">
                <div class="row">
                    <div class="col col-sm-12 col-md-6">
                        <div class="card">
                            <div class="card-body" style="text-align:left">
                                <p class="cardTitle">Coursework</p>
                                <div class="divider"></div>
                                <p>Grade Report ID: <span id="grade_report_id"></span></p>
                                <p>Assigned Teacher: <?php echo $teacherName . " (ID: " . $teacherID . ")" ?></p>
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th scope="col">Exam</th>
                                            <th scope="col">Grade</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>First Exam</td>
                                            <td id="first_grade">Fetching...</td>
                                        </tr>
                                        <tr>
                                            <td>Second Exam</td>
                                            <td id="second_grade">Fetching...</td>
                                        </tr>
                                        <tr>
                                            <td>Third Exam</td>
                                            <td id="third_grade">Fetching...</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div class="col col-sm-12 col-md-6">
                        <div class="card">
                            <div class="card-body" style="text-align:left">
                                <p class="cardTitle">Vaccination</p>
                                <p>Record ID: <span id="recordID"></span></p>
                                <p>Healthworker: <?php echo $health_workerName . " (ID: " . $health_workerID . ")" ?></p>
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th scope="col">Item</th>
                                            <th scope="col">Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>First Vaccine</td>
                                            <td id="first_vaccine">Fetching...</td>
                                        </tr>
                                        <tr>
                                            <td>First Vaccine Date</td>
                                            <td id="first_vaccine_date">Fetching...</td>
                                        </tr>
                                        <tr>
                                            <td>Second Vaccine</td>
                                            <td id="second_vaccine">Fetching...</td>
                                        </tr>
                                        <tr>
                                            <td>First Vaccine Date</td>
                                            <td id="second_vaccine_date">Fetching...</td>
                                        </tr>
                                        <tr>
                                            <td>Third Vaccine</td>
                                            <td id="third_vaccine">Fetching...</td>
                                        </tr>
                                        <tr>
                                            <td>First Vaccine Date</td>
                                            <td id="third_vaccine_date">Fetching...</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
    <style>
        body,
        html {
            font-family: finalsix, sans-serif;
        }

        .cardTitle {
            font-size: 24px;
            font-weight: bold;
        }

        .card {
            border: 1px solid #e5e5e5;
            border-radius: 5px;
            box-shadow: 0 1px 2px rgba(0, 0, 0, 0.05);
            background-color: #fff;
            margin-bottom: 20px;
            padding: 10px;
        }
    </style>
    <script src="https://code.jquery.com/jquery-3.6.0.slim.min.js" integrity="sha256-u7e5khyithlIdTpu22PHhENmPcRdFiHRjhAuHcs05RI=" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/js/bootstrap.bundle.min.js" integrity="sha384-pprn3073KE6tl6bjs2QrFaJGz5/SUsLqktiwsUTF55Jfv3qYSDhgCecCxMW52nD2" crossorigin="anonymous"></script>
    <script>
        // onload
        $(document).ready(function() {
            var gradesJSON = <?php echo $grade ?>;
            var vaccinationsJSON = <?php echo $vacc_record ?>;

            gradesJSON = gradesJSON[0];
            // write grades to page
            var first_grade = gradesJSON.first_grade;
            var second_grade = gradesJSON.second_grade;
            var final_grade = gradesJSON.final_grade;
            var grade_report_ID = gradesJSON.gradeReportID;
            $("#first_grade").text(first_grade);
            $("#second_grade").text(second_grade);
            $("#third_grade").text(final_grade);
            $("#grade_report_id").text(grade_report_ID);

            vaccinationsJSON = vaccinationsJSON[0];
            // write vaccinations to page
            var recordID = vaccinationsJSON.recordID;
            if (recordID == null) {
                $("#recordID").text("No record found");
            } else {
                $("#recordID").text(recordID);
            }
            var first_vaccine = vaccinationsJSON.first_vaccine;
            var first_vaccine_date = vaccinationsJSON.first_vaccine_date;
            var second_vaccine = vaccinationsJSON.second_vaccine;
            var second_vaccine_date = vaccinationsJSON.second_vaccine_date;
            var third_vaccine = vaccinationsJSON.third_vaccine;
            var third_vaccine_date = vaccinationsJSON.third_vaccine_date;

            if (first_vaccine_date == "0000-00-00") {
                $("#first_vaccine").text("Not Given");
                $("#first_vaccine_date").text("Not Applicable");
            } else {
                $("#first_vaccine").text(first_vaccine);
                $("#first_vaccine_date").text(first_vaccine_date);
            };
            if (second_vaccine_date == "0000-00-00") {
                $("#second_vaccine").text("Not Given");
                $("#second_vaccine_date").text("Not Applicable");
            } else {
                $("#second_vaccine").text(second_vaccine);
                $("#second_vaccine_date").text(second_vaccine_date);
            };
            if (third_vaccine_date == "0000-00-00") {
                $("#third_vaccine").text("Not Given");
                $("#third_vaccine_date").text("Not Applicable");
            } else {
                $("#third_vaccine").text(third_vaccine);
                $("#third_vaccine_date").text(third_vaccine_date);
            };
        });
    </script>
</body>

</html>