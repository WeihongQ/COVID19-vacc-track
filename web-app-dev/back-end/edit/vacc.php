<?php
include '../sql-queries.php';
session_start();

// get id and status
$id = $_SESSION['id'];
$status = $_SESSION['status'];

// sanity check: status should be teacher
if ($status != "health_worker") {
    header("Location: https://summer2022csc261.herokuapp.com/index.php?error=1");
    exit();
}

// get studentID from URL
$student_id = $_GET['id'];
// get realname
$realname = get_student_realname($student_id);
// get vaccine info
$vaccine_info = get_vacc_record($student_id);
// get record ID
$record_id = json_decode($vaccine_info)[0]->recordID;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // get 3 names and 3 dates from POST
    $first_vaccine = trim($_POST['first_vaccine']);
    $second_vaccine = trim($_POST['second_vaccine']);
    $third_vaccine = trim($_POST['third_vaccine']);
    $first_vaccine_date = trim($_POST['first_vaccine_date']);
    $second_vaccine_date = trim($_POST['second_vaccine_date']);
    $third_vaccine_date = trim($_POST['third_vaccine_date']);

    // sanitized all names
    $first_vaccine = htmlspecialchars($first_vaccine);
    $second_vaccine = htmlspecialchars($second_vaccine);
    $third_vaccine = htmlspecialchars($third_vaccine);

    // all names maximum first 10 chars
    $first_vaccine = substr($first_vaccine, 0, 10);
    $second_vaccine = substr($second_vaccine, 0, 10);
    $third_vaccine = substr($third_vaccine, 0, 10);

    // sanitize 3 dates
    $first_vaccine_date = date("Y-m-d", strtotime($first_vaccine_date));
    $second_vaccine_date = date("Y-m-d", strtotime($second_vaccine_date));
    $third_vaccine_date = date("Y-m-d", strtotime($third_vaccine_date));

    // update vaccine info
    update_vacc_record($record_id, $first_vaccine, $second_vaccine, $third_vaccine, $first_vaccine_date, $second_vaccine_date, $third_vaccine_date);

    // jump to the url with success
    header("Location: https://summer2022csc261.herokuapp.com/dashboard/health_worker.php?message=edit_success");

}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta name="description" content="" />
    <title>Editing Record #<?php echo $record_id ?></title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-0evHe/X+R7YkIZDRvuzKMRqM+OrBnVFBL6DOitfPri4tjfHxaWutUpFmBp4vmVor" crossorigin="anonymous" />

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css" />

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/css/bootstrap-datepicker.min.css" />

    <link rel="stylesheet" href="https://use.typekit.net/jdw6sjn.css" />

    <link href="../css/attached.css" rel="stylesheet" />
</head>

<body class="text-center">
    <main class="w-100 m-auto container" id="main">
        <div style="max-width: 1260px; margin: auto">
        <button type="button" class="btn btn-outline-primary btn-sm" style="float:right;margin-right:15px" onclick="window.location.href='https://summer2022csc261.herokuapp.com/dashboard/health_worker.php'">Back to Dashboard</button>
            <div class="titleBar" style="text-align: left">
                <svg class="w-6 h-6 mb-4" style="width: 60px; height: 60px" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path d="M12 14l9-5-9-5-9 5 9 5z"></path>
                    <path d="M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z">
                    </path>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5zm0 0l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14zm-4 6v-7.5l4-2.222">
                    </path>
                </svg>
                <span style="font-size: 32px;line-height:40px">Editing Record #<?php echo $record_id ?> for <?php echo $realname ?></span>
            </div>
            <form id="vaccForm" method="post">
                <div class="form">
                    <div class="form-floating">
                        <button class="btn btn-success" id="addFirstVac" style="width:100%;margin-top:10px">Add First Vaccination</button>
                    </div>
                    <div id="firstVac">
                        <div class="form-floating">
                            <input class="form-control" id="firstVacName" placeholder="First Vaccination Name" name="first_vaccine" />
                            <label for="firstVacName">First Vaccination Name</label>
                        </div>
                        <div class="form-floating" style="text-align:left">
                            <p style="margin-bottom:0">First Vaccination Date</p>
                            <div class="input-group date" id="datepicker_first_vacc">
                                <input type="text" class="form-control" name="first_vaccine_date" readonly />
                                <span class="input-group-append">
                                    <span class="input-group-text bg-white d-block">
                                        <i class="fa fa-calendar"></i>
                                    </span>
                                </span>
                            </div>
                        </div>
                        <div class="form-floating">
                            <button class="btn btn-warning" id="delFirstVac" style="width:100%;margin-top:10px">Delete First Vaccination</button>
                        </div>
                    </div>
                    <div class="form-floating">
                        <button class="btn btn-success" id="addSecondVac" style="width:100%;margin-top:10px">Add Second Vaccination</button>
                    </div>
                    <div id="secondVac">
                        <div class="form-floating" style="margin-top:30px">
                            <input class="form-control" id="secondVacName" placeholder="Second Vaccination Name" name="second_vaccine" />
                            <label for="secondVacName">Second Vaccination Name</label>
                        </div>
                        <div class="form-floating" style="text-align:left">
                            <p style="margin-bottom:0">Second Vaccination Date</p>
                            <div class="input-group date" id="datepicker_second_vacc">
                                <input type="text" class="form-control" readonly name="second_vaccine_date" />
                                <span class="input-group-append">
                                    <span class="input-group-text bg-white d-block">
                                        <i class="fa fa-calendar"></i>
                                    </span>
                                </span>
                            </div>
                        </div>
                        <div class="form-floating">
                            <button class="btn btn-warning" id="delSecondVac" style="width:100%;margin-top:10px">Delete Second Vaccination</button>
                        </div>
                    </div>
                    <div class="form-floating">
                        <button class="btn btn-success" id="addThirdVac" style="width:100%;margin-top:10px">Add Third Vaccination</button>
                    </div>
                    <div id="thirdVac">
                        <div class="form-floating" style="margin-top:30px">
                            <input class="form-control" id="thirdVacName" placeholder="Third Vaccination Name" name="third_vaccine" />
                            <label for="thirdVacName">Third Vaccination Name</label>
                        </div>
                        <div class="form-floating" style="text-align:left">
                            <p style="margin-bottom:0">Third Vaccination Date</p>
                            <div class="input-group date" id="datepicker_third_vacc">
                                <input type="text" class="form-control" readonly name="third_vaccine_date" />
                                <span class="input-group-append">
                                    <span class="input-group-text bg-white d-block">
                                        <i class="fa fa-calendar"></i>
                                    </span>
                                </span>
                            </div>
                        </div>
                        <div class="form-floating">
                            <button class="btn btn-warning" id="delThirdVac" style="width:100%;margin-top:10px">Delete Third Vaccination</button>
                        </div>
                    </div>
            </form>
            <button id="submitBtn" style="margin-top:10px" class="w-100 btn btn-lg btn-primary" data-bs-toggle="modal" data-bs-target="#confirmChanges">
                Submit
            </button>
        </div>
        <div class="modal fade" id="confirmChanges" tabindex="-1" aria-labelledby="confirmChangesLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="confirmChangesLabel">
                            Confirm Changes
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <p>Are you sure you want to submit these changes?</p>
                        <span id="changes-confirm"></span>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                            Close
                        </button>
                        <button type="button" class="btn btn-primary" type="submit" id="submitForm">
                            Confirm
                        </button>
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

        .form-floating {
            margin-bottom: 10px;
        }
    </style>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/js/bootstrap.bundle.min.js" integrity="sha384-pprn3073KE6tl6bjs2QrFaJGz5/SUsLqktiwsUTF55Jfv3qYSDhgCecCxMW52nD2" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/js/bootstrap-datepicker.min.js"></script>
    <script>
        // get everything from php
        var vaccRecord_RAW = <?php echo $vaccine_info ?>[0];
        console.log(vaccRecord_RAW);

        // convert yyyy-mm-dd to mm/dd/yyyy
        function convertHelper(input) {
            var date = new Date(input);
            var month = date.getMonth() + 1;
            var day = date.getDate() + 1;
            var year = date.getFullYear();
            return autoAddZero(month) + '/' + autoAddZero(day) + '/' + year;
        }

        // submit helper: convert date to yyyy-mm-dd
        function submitHelper(input) {
            var date = new Date(input);
            var month = date.getMonth() + 1;
            var day = date.getDate();
            var year = date.getFullYear();
            return year + '-' + autoAddZero(month) + '-' + autoAddZero(day);
        }

        var vaccRecords = [
            vaccRecord_RAW["first_vaccine"],
            vaccRecord_RAW["first_vaccine_date"],
            vaccRecord_RAW["second_vaccine"],
            vaccRecord_RAW["second_vaccine_date"],
            vaccRecord_RAW["third_vaccine"],
            vaccRecord_RAW["third_vaccine_date"]
        ];

        // render vaccRecords to the form, and set the datepickers to the correct date
        function renderVaccRecords() {
            $("#firstVacName").val(vaccRecords[0]);
            $("#datepicker_first_vacc").datepicker("setDate", convertHelper(vaccRecords[1]));
            $("#secondVacName").val(vaccRecords[2]);
            $("#datepicker_second_vacc").datepicker("setDate", convertHelper(vaccRecords[3]));
            $("#thirdVacName").val(vaccRecords[4]);
            $("#datepicker_third_vacc").datepicker("setDate", convertHelper(vaccRecords[5]));
        }

        function autoAddZero(input) {
            // if input less than 10, add a zero in front of it
            if (input < 10) {
                return "0" + input;
            } else {
                return input;
            }
        }

        function getFormattedDates() {
            // check three vaccination display states
            var firstVacDisplayState = $("#firstVac").css("display");
            var secondVacDisplayState = $("#secondVac").css("display");
            var thirdVacDisplayState = $("#thirdVac").css("display");

            if (firstVacDisplayState != "none") {
                var firstVacDate = $("#datepicker_first_vacc").datepicker("getDate");
                var firstVacDateFormatted =
                    submitHelper(firstVacDate);
            } else {
                var firstVacDateFormatted = "0000-00-00";
            }
            if (secondVacDisplayState != "none") {
                var secondVacDate = $("#datepicker_second_vacc").datepicker("getDate");
                var secondVacDateFormatted =
                    submitHelper(secondVacDate);
            } else {
                var secondVacDateFormatted = "0000-00-00";
            }
            if (thirdVacDisplayState != "none") {
                var thirdVacDate = $("#datepicker_third_vacc").datepicker("getDate");
                var thirdVacDateFormatted =
                    submitHelper(thirdVacDate);
            } else {
                var thirdVacDateFormatted = "0000-00-00";
            }
            return [firstVacDateFormatted, secondVacDateFormatted, thirdVacDateFormatted];
        }

        function sanitizeNameInput() {
            var firstVacName = $("#firstVacName").val();
            var secondVacName = $("#secondVacName").val();
            var thirdVacName = $("#thirdVacName").val();
            // if any of the input is empty, set it to "no"
            if (firstVacName == "") {
                firstVacName = "no";
            }
            if (secondVacName == "") {
                secondVacName = "no";
            }
            if (thirdVacName == "") {
                thirdVacName = "no";
            }
            return [firstVacName, secondVacName, thirdVacName];
        }

        // update changes to changes-confirm
        function renderConfirmChanges() {
            var changes = "";
            var flag = false;
            var formattedDates = getFormattedDates();
            var vaccNames = sanitizeNameInput();
            // check if any changes were made, update the changes string
            if (vaccNames[0] != vaccRecords[0]) {
                changes +=
                    "First Vaccination Name: " +
                    vaccRecords[0] + "->" + $("#firstVacName").val() +
                    "<br />";
                flag = true;
            }
            if (formattedDates[0] != vaccRecords[1]) {
                changes +=
                    "First Vaccination Date: " +
                    vaccRecords[1] + "->" + formattedDates[0] +
                    "<br />";
                flag = true;
            }
            if (vaccNames[1] != vaccRecords[2]) {
                changes +=
                    "Second Vaccination Name: " +
                    vaccRecords[2] + "->" + $("#secondVacName").val() + "<br />";
                flag = true;
            }
            if (formattedDates[1] != vaccRecords[3]) {
                changes +=
                    "Second Vaccination Date: " +
                    vaccRecords[3] + "->" + formattedDates[1] + "<br />";
                flag = true;
            }
            if (vaccNames[2] != vaccRecords[4]) {
                changes +=
                    "Third Vaccination Name: " +
                    vaccRecords[4] + "->" + $("#thirdVacName").val() + "<br />";
                flag = true;
            }
            if (formattedDates[2] != vaccRecords[5]) {
                changes +=
                    "Third Vaccination Date: " +
                    vaccRecords[5] + "->" + formattedDates[2] + "<br />";
                flag = true;
            }

            if (flag) {
                $("#changes-confirm").html(changes);
            } else {
                $("#changes-confirm").html("No changes were made.");
            }
        }

        // when click submit, update grades and render confirm changes
        $("#submitBtn").click(function(e) {
            e.preventDefault();
            renderConfirmChanges();
        });

        // when click submitForm, submit form
        $("#submitForm").click(function() {
            // before submitting, start by sanitizing the input
            var vaccNames = sanitizeNameInput();
            // update the form with the new values
            $("#firstVacName").val(vaccNames[0]);
            $("#secondVacName").val(vaccNames[1]);
            $("#thirdVacName").val(vaccNames[2]);

            // for every date, check if it is valid, if not, set it to "0000-00-00"
            var firstVacDate = $("#datepicker_first_vacc").datepicker("getDate");
            var secondVacDate = $("#datepicker_second_vacc").datepicker("getDate");
            var thirdVacDate = $("#datepicker_third_vacc").datepicker("getDate");
            if (vaccNames[0] == "no") {
                firstVacDate = "0000-00-00";
            } else {
                // add one day
                firstVacDate = new Date(firstVacDate.getTime() + (24 * 60 * 60 * 1000));
                firstVacDate = firstVacDate.toISOString().split("T")[0];
                firstVacDate = submitHelper(firstVacDate);
            }
            if (vaccNames[1] == "no") {
                secondVacDate = "0000-00-00";
            } else {
                // add one day
                secondVacDate = new Date(secondVacDate.getTime() + (24 * 60 * 60 * 1000));
                secondVacDate = secondVacDate.toISOString().split("T")[0];
                secondVacDate = submitHelper(secondVacDate);
            }
            if (vaccNames[2] == "no") {
                thirdVacDate = "0000-00-00";
            } else {
                // add one day
                thirdVacDate = new Date(thirdVacDate.getTime() + (24 * 60 * 60 * 1000));
                thirdVacDate = thirdVacDate.toISOString().split("T")[0];
                thirdVacDate = submitHelper(thirdVacDate);
            }
            // write to input form
            $("#firstVacDate").val(firstVacDate);
            $("#secondVacDate").val(secondVacDate);
            $("#thirdVacDate").val(thirdVacDate);
            // submit form
            $("#vaccForm").submit();
        });

        $(document).ready(function() {
            // hide entire page
            $("#main").hide();
            renderVaccRecords();
            // init datepickers
            $("#datepicker_first_vacc").datepicker({
                format: "mm/dd/yyyy",
                autoclose: true,
                todayHighlight: true,
            });
            $("#datepicker_second_vacc").datepicker({
                format: "mm/dd/yyyy",
                autoclose: true,
                todayHighlight: true,
            });
            $("#datepicker_third_vacc").datepicker({
                format: "mm/dd/yyyy",
                autoclose: true,
                todayHighlight: true,
            });

            // determine which parts to render.
            // start by hiding everything.
            $("#firstVac").hide();
            $("#secondVac").hide();
            $("#thirdVac").hide();
            $("#addFirstVac").hide();
            $("#addSecondVac").hide();
            $("#addThirdVac").hide();
            if (vaccRecords[0] == "no") {
                // show first vac
                $("#addFirstVac").show();
            } else {
                // show first vac
                $("#firstVac").show();
                // hide delete button
                $("#delFirstVac").hide();
                if (vaccRecords[2] == "no") {
                    // show second vac
                    $("#addSecondVac").show();
                } else {
                    // show second vac
                    $("#secondVac").show();
                    // hide delete button
                    $("#delSecondVac").hide();
                    if (vaccRecords[4] == "no") {
                        // show third vac
                        $("#addThirdVac").show();
                    } else {
                        // show third vac
                        $("#thirdVac").show();
                        // hide delete button
                        $("#delThirdVac").hide();
                    }
                }
            }

            // when click add first vac, show first vac and hide add first vac
            $("#addFirstVac").click(function(e) {
                e.preventDefault();
                $("#firstVac").show();
                // change vaccination name input to ""
                $("#firstVacName").val("");
                $("#addFirstVac").hide();
            });
            // when click add second vac, show second vac and hide add second vac
            $("#addSecondVac").click(function(e) {
                e.preventDefault();
                $("#secondVac").show();
                // change vaccination name input to ""
                $("#secondVacName").val("");
                $("#addSecondVac").hide();
            });
            // when click add third vac, show third vac and hide add third vac
            $("#addThirdVac").click(function(e) {
                e.preventDefault();
                $("#thirdVac").show();
                // change vaccination name input to ""
                $("#thirdVacName").val("");
                $("#addThirdVac").hide();
            });

            // define delete btn behaviors
            // when click delete first vac, hide first vac and show add first vac
            $("#delFirstVac").click(function(e) {
                e.preventDefault();
                $("#firstVac").hide();
                // change vaccination name input to ""
                $("#firstVacName").val("");
                $("#addFirstVac").show();
            });
            // when click delete second vac, hide second vac and show add second vac
            $("#delSecondVac").click(function(e) {
                e.preventDefault();
                $("#secondVac").hide();
                // change vaccination name input to ""
                $("#secondVacName").val("");
                $("#addSecondVac").show();
            });
            // when click delete third vac, hide third vac and show add third vac
            $("#delThirdVac").click(function(e) {
                e.preventDefault();
                $("#thirdVac").hide();
                // change vaccination name input to ""
                $("#thirdVacName").val("");
                $("#addThirdVac").show();
            });

            // show entire page, with fade in animation
            $("#main").fadeIn(200);
        });
    </script>
</body>

</html>