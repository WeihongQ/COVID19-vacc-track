<?php
session_start();
include 'sql-queries.php';
    
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // get username, password from post
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);
    $password = md5($password . "csc261");

    // check if username and password is in the database
    $returnResults = validate_login($username, $password);

    // if there is returnResult, then jump to the url with returnResult
    if ($returnResults) {
        if (session_status() == PHP_SESSION_ACTIVE) {
            // get the id and status
            $id = json_decode($returnResults)->id;
            $status = json_decode($returnResults)->status;
            // set the session variables
            $_SESSION['id'] = $id;
            $_SESSION['status'] = $status;
            // if the status is student, set the realname
            if ($status == "student") {
                $_SESSION['realname'] = get_student_realname($id);
            }
            // if the status is teacher, set the realname
            else if ($status == "teacher") {
                $_SESSION['realname'] = get_teacher_realname($id);
            }
            // if the status is health_worker, set the realname
            else if ($status == "health_worker") {
                $_SESSION['realname'] = get_healthworker_realname($id);
            }
            header("Location: https://summer2022csc261.herokuapp.com/dashboard/redirect.php");
            exit;
        } else {
            header("Location: https://summer2022csc261.herokuapp.com/dashboard/redirect.php?error=session_error");
            exit;
        }
    } else {
        // if there is no returnResult, then jump to the url with error
        header("Location: https://summer2022csc261.herokuapp.com/index.php?error=1");
        exit;
    }
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta name="description" content="" />
    <title>Login</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-0evHe/X+R7YkIZDRvuzKMRqM+OrBnVFBL6DOitfPri4tjfHxaWutUpFmBp4vmVor" crossorigin="anonymous" />

    <link rel="stylesheet" href="https://use.typekit.net/jdw6sjn.css">

    <style>
        .bd-placeholder-img {
            font-size: 1.125rem;
            text-anchor: middle;
            -webkit-user-select: none;
            -moz-user-select: none;
            user-select: none;
        }

        @media (min-width: 768px) {
            .bd-placeholder-img-lg {
                font-size: 3.5rem;
            }
        }

        .b-example-divider {
            height: 3rem;
            background-color: rgba(0, 0, 0, 0.1);
            border: solid rgba(0, 0, 0, 0.15);
            border-width: 1px 0;
            box-shadow: inset 0 0.5em 1.5em rgba(0, 0, 0, 0.1),
                inset 0 0.125em 0.5em rgba(0, 0, 0, 0.15);
        }

        .b-example-vr {
            flex-shrink: 0;
            width: 1.5rem;
            height: 100vh;
        }

        .bi {
            vertical-align: -0.125em;
            fill: currentColor;
        }

        .nav-scroller {
            position: relative;
            z-index: 2;
            height: 2.75rem;
            overflow-y: hidden;
        }

        .nav-scroller .nav {
            display: flex;
            flex-wrap: nowrap;
            padding-bottom: 1rem;
            margin-top: -1px;
            overflow-x: auto;
            text-align: center;
            white-space: nowrap;
            -webkit-overflow-scrolling: touch;
        }
    </style>

    <link href="css/attached.css" rel="stylesheet" />
</head>

<body class="text-center">
    <main class="form-signin w-100 m-auto">
        <form method="POST">
            <svg class="w-6 h-6 mb-4" style="width: 60px; height: 60px" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                <path d="M12 14l9-5-9-5-9 5 9 5z"></path>
                <path d="M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z"></path>
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5zm0 0l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14zm-4 6v-7.5l4-2.222"></path>
            </svg>
            <h1 class="h3 mb-3 fw-normal" style="font-weight:900">Login</h1>
            <div class="form-floating">
                <input name="username" class="form-control" id="floatingInput" placeholder="Username" />
                <label for="floatingInput">Username</label>
            </div>
            <div class="form-floating">
                <input name="password" type="password" class="form-control" id="floatingPassword" placeholder="Password" />
                <label for="floatingPassword">Password</label>
            </div>
            <button id="submitBtn" class="w-100 btn btn-lg btn-primary" type="submit">
                Sign in
            </button>
        </form>
        <?php
        if (isset($_GET['error'])) {
            echo "<p class='text-danger'>Invalid username or password</p>";
        }
        ?>
        <p style="
          position: fixed;
          left: 0;
          right: 0;
          bottom: 10px;
          font-size: 10px;
        ">
            Milestone 3<br />Yongyi Zang / Weihong Qi / Erqian Xu
        </p>
    </main>
    <script src="https://code.jquery.com/jquery-3.6.0.slim.min.js" integrity="sha256-u7e5khyithlIdTpu22PHhENmPcRdFiHRjhAuHcs05RI=" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/js/bootstrap.bundle.min.js" integrity="sha384-pprn3073KE6tl6bjs2QrFaJGz5/SUsLqktiwsUTF55Jfv3qYSDhgCecCxMW52nD2" crossorigin="anonymous"></script>
</body>

</html>