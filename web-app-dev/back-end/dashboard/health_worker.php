<?php
include '../sql-queries.php';
session_start();

// get id and status
$id = $_SESSION['id'];
$status = $_SESSION['status'];

// sanity check: status should be teacher. if not, go back to index.php
if ($status != "health_worker") {
    header("Location: https://summer2022csc261.herokuapp.com/index.php?error=1");
    exit;
}

// get the realname
$realname = get_healthworker_realname($id);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta name="description" content="" />
    <title><?php echo $realname ?>'s Dashboard</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-0evHe/X+R7YkIZDRvuzKMRqM+OrBnVFBL6DOitfPri4tjfHxaWutUpFmBp4vmVor" crossorigin="anonymous" />

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

    <link rel="stylesheet" href="https://use.typekit.net/jdw6sjn.css" />

    <link href="../css/attached.css" rel="stylesheet" />
</head>

<body class="text-center">
    <main class="w-100 m-auto">
        <div style="max-width: 1260px; margin: auto">
            <?php
            // if there is message in URL, display it
            if (isset($_GET['message'])) {
                $message = $_GET['message'];
                if ($message == "edit_success") {
                    // display success message
                    echo "<div class='alert alert-success' id='successAlert' style='position:fixed;top:10px;left:0px;right:0px' role='alert'>
                        <strong>Success!</strong> Vaccination Record has been updated.
                    </div>";
                }
            }
            ?>
            <div class="titleBar" style="text-align: left">
                <button type="button" class="btn btn-outline-primary btn-sm" style="float:right;margin-right:15px" onclick="window.location.href='https://summer2022csc261.herokuapp.com/logout.php'">Log out</button>
                <svg class="w-6 h-6 mb-4" style="width: 60px; height: 60px" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path d="M12 14l9-5-9-5-9 5 9 5z"></path>
                    <path d="M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z"></path>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5zm0 0l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14zm-4 6v-7.5l4-2.222"></path>
                </svg>
                <span style="font-size: 32px">Hi, <?php echo $realname ?>!</span>
                <p>Select the student vaccination status you want to edit from above.</p>
            </div>
            <div class="form">
                <input id="filterBar" type="text" class="form-control form-input" placeholder="Filter student names..." style="margin-bottom:20px">
            </div>
            <div class="studentListWrapper">
                <p id="noResults">No results found.</p>
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

        .studentName {
            font-size: 18px;
            min-width: 150px;
            margin-right: 10px;
            margin-bottom: 10px;
        }

        @media screen and (max-width: 768px) {
            .studentNameWrapper {
                display: grid;
                grid-template-columns: repeat(3, 1fr);
                gap: 10px;
                text-align: left;
            }
        }
    </style>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/js/bootstrap.bundle.min.js" integrity="sha384-pprn3073KE6tl6bjs2QrFaJGz5/SUsLqktiwsUTF55Jfv3qYSDhgCecCxMW52nD2" crossorigin="anonymous"></script>
    <script>
        var studentList = <?php echo get_students_by_healthworker($id) ?>;

        function renderStudentList() {
            // for each name in student Name, create a button
            studentList.forEach(function(item) {
                var button = document.createElement("button");
                button.className = "btn btn-outline-success studentName";
                button.innerHTML = item.realname;
                // add a onclick event to the button
                button.onclick = function() {
                    window.location.href = "https://summer2022csc261.herokuapp.com/edit/vacc.php?id=" + item.studentID;
                };
                document.querySelector(".studentListWrapper").appendChild(button);
            });
        }

        var currentShowingStudents = []
        // filter student names based on the input
        function filterStudentNames() {
            currentShowingStudents = []
            var filter = document.querySelector("#filterBar").value.toUpperCase();
            var studentNames = document.querySelectorAll(".studentName");
            studentNames.forEach(function(name) {
                // if the name contains the filter, show it
                if (name.innerHTML.toUpperCase().indexOf(filter) > -1) {
                    name.style.display = "";
                    currentShowingStudents.push(name)
                } else {
                    name.style.display = "none";
                }
            });
            // check if all names are hidden
            if (currentShowingStudents.length == 0) {
                $("#noResults").show();
            } else {
                $("#noResults").hide();
            }
        }

        // everytime the input changes, filter the student names
        $("#filterBar").on("change keydown paste input", function() {
            filterStudentNames();
        });
        // onload
        $(document).ready(function() {
            renderStudentList();
            $("#noResults").hide();
            // if there is div with id successAlert, hide it after 1 second
            if ($("#successAlert").length > 0) {
                setTimeout(function() {
                    // hide it with fade out effect
                    $("#successAlert").animate({
                        opacity: 0
                    }, 1000, function() {
                        // after fade out, remove the div
                        $(this).remove();
                    });
                }, 1000);
            }
        });
    </script>
</body>

</html>