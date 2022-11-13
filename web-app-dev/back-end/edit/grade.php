<?php
include '../sql-queries.php';
session_start();

// get id and status
$id = $_SESSION['id'];
$status = $_SESSION['status'];

// sanity check: status should be teacher
if ($status != "teacher") {
    header("Location: https://summer2022csc261.herokuapp.com/index.php?error=1");
    exit();
}

// get studentID from URL
$student_id = $_GET['id'];
// get realname
$realname = get_student_realname($student_id);
// get gradereport from studentID
$grade = get_gradeReport($student_id);
// get gradeReportID from grade
$gradeReportID = json_decode($grade)[0]->gradeReportID;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // get grade1, grade2, grade3 from post
    $grade1 = trim($_POST['grade1']);
    $grade2 = trim($_POST['grade2']);
    $grade3 = trim($_POST['grade3']);
    // sanitize: all should be int
    $grade1 = intval($grade1);
    $grade2 = intval($grade2);
    $grade3 = intval($grade3);
    // sanity check: all grades should be between 0 and 100
    if ($grade1 < 0 || $grade1 > 100 || $grade2 < 0 || $grade2 > 100 || $grade3 < 0 || $grade3 > 100) {
        header("Location: https://summer2022csc261.herokuapp.com/edit/grade.php?id=$student_id&error=1");
        exit();
    }
    // update gradeReport
    update_grade($gradeReportID, $grade1, $grade2, $grade3);
    // jump to teacher dashboard
    header("Location: https://summer2022csc261.herokuapp.com/dashboard/teacher.php?message=edit_success");
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta name="description" content="" />
    <title>Editing Grade Report <?php echo $gradeReportID ?></title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-0evHe/X+R7YkIZDRvuzKMRqM+OrBnVFBL6DOitfPri4tjfHxaWutUpFmBp4vmVor" crossorigin="anonymous" />

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css" />

    <link rel="stylesheet" href="https://use.typekit.net/jdw6sjn.css" />

    <link href="../css/attached.css" rel="stylesheet" />
</head>

<body class="text-center">
    <main class="w-100 m-auto container">
        <div style="max-width: 1260px; margin: auto">
            <div class="titleBar" style="text-align: left">
                <svg class="w-6 h-6 mb-4" style="width: 60px; height: 60px" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path d="M12 14l9-5-9-5-9 5 9 5z"></path>
                    <path d="M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z"></path>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5zm0 0l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14zm-4 6v-7.5l4-2.222"></path>
                </svg>
                <span style="font-size: 32px">Editing Grade Report #<?php echo $gradeReportID ?> for <?php echo $realname ?></span>
            </div>
            <form id="gradeForm" method="POST">
                <div class="form">
                    <div class="form-floating" style="display:none">
                        <input name="id" id="student_id"/>
                    </div>
                    <div class="form-floating">
                        <input name="grade1" type="number" class="form-control" id="grade1" placeholder="Grade 1" />
                        <label for="grade1">Grade 1</label>
                    </div>
                    <div class="form-floating">
                        <input name="grade2" type="number" class="form-control" id="grade2" placeholder="Grade 2" />
                        <label for="grade2">Grade 2</label>
                    </div>
                    <div class="form-floating">
                        <input name="grade3" type="number" class="form-control" id="grade3" placeholder="Grade 3" />
                        <label for="grade3">Grade 3</label>
                    </div>
                    <button id="submitBtn" class="w-100 btn btn-lg btn-primary" data-bs-toggle="modal" data-bs-target="#confirmChanges">
                        Submit
                    </button>
                    <?php
                    if (isset($_GET['error'])) {
                        echo "<p class='text-danger'>Invalid input. Please try again.</p>";
                    }
                    ?>
                </div>
            </form>
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
                        <button type="button" class="btn btn-primary" id="submitForm">Confirm</button>
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
    <script src="https://code.jquery.com/jquery-3.6.0.slim.min.js" integrity="sha256-u7e5khyithlIdTpu22PHhENmPcRdFiHRjhAuHcs05RI=" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/js/bootstrap.bundle.min.js" integrity="sha384-pprn3073KE6tl6bjs2QrFaJGz5/SUsLqktiwsUTF55Jfv3qYSDhgCecCxMW52nD2" crossorigin="anonymous"></script>
    <script>
        var gradesArray = <?php echo $grade ?>;
        // take first_grade, second_grade and final_grade, and put them in an array
        var grades = [gradesArray[0]["first_grade"], gradesArray[0]["second_grade"], gradesArray[0]["final_grade"]];
        // render grades into the form
        function renderGrades() {
            for (var i = 0; i < grades.length; i++) {
                $("#grade" + (i + 1)).val(grades[i]);
            }
        }
        // update changes to changes-confirm
        function renderConfirmChanges() {
            var changes = "";
            var flag = false;
            for (var i = 0; i < grades.length; i++) {
                if (grades[i] != $("#grade" + (i + 1)).val()) {
                    flag = true;
                    changes +=
                        "Grade " +
                        (i + 1) +
                        " changed from " +
                        grades[i] +
                        " to " +
                        $("#grade" + (i + 1)).val() +
                        "<br />";
                }
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
            for (var i = 0; i < grades.length; i++) {
                grades[i] = $("#grade" + (i + 1)).val();
            }
            // submit form
            $("#gradeForm").submit();
        });
        $(document).ready(function() {
            renderGrades();
            // set student_id input
            $("#student_id").val(<?php echo $student_id ?>);
        });
    </script>
</body>

</html>