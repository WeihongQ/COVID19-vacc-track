<?php
    session_start();
    // check if session contains id and status
    if (isset($_SESSION['id']) && isset($_SESSION['status'])) {
        // get status
        $status = $_SESSION['status'];
        if ($status == "student") {
            header("Location: https://summer2022csc261.herokuapp.com/dashboard/student.php");
        } else if ($status == "teacher") {
            header("Location: https://summer2022csc261.herokuapp.com/dashboard/teacher.php");
        } else if ($status == "health_worker") {
            header("Location: https://summer2022csc261.herokuapp.com/dashboard/health_worker.php");
        } else {
            header("Location: https://summer2022csc261.herokuapp.com/index.php?error=1");
        }
    } else {
        echo "<p>Please login first.</p>";
    }
    exit;
?>