<?php
include "db_conn.php"; 
include "send_email.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];
    $lastname = $_POST['lastname'];
    $firstname = $_POST['firstname'];
    $middlename = $_POST['middlename'];
    $email = $_POST['email'];

    $check_email_sql = "SELECT * FROM user WHERE Email='$email'";
    $check_email_result = mysqli_query($conn, $check_email_sql);

    if (mysqli_num_rows($check_email_result) > 0) {
        echo '<p class="error-message-duplicate">Email already registered.</p>';
    } else {
        
        $verification_code = substr(md5(uniqid(mt_rand(), true)), 0, 5);

        if (sendVerificationCode($email, $verification_code)) {
           
            $sql = "INSERT INTO user (username, password, Lastname, First_name, Middle_name, Email, verification_code, Status) 
                    VALUES ('$username', '$password', '$lastname', '$firstname', '$middlename', '$email', '$verification_code', 'inactive')";
            if (mysqli_query($conn, $sql)) {
                
                header("Location: verify.php?email=$email");
                exit();
            } else {
                echo '<p class="error">Registration failed.</p>';
            }
        } else {
            echo '<p class="error">Email sending failed.</p>';
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registration</title>
   
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/admin-lte/3.2.0/css/adminlte.min.css">
</head>
<body class="hold-transition sidebar-mini">
<div class="wrapper">
    
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-6 offset-md-3">
                    <div class="card card-primary">
                        <div class="card-header">
                            <h3 class="card-title">Registration</h3>
                        </div>
                       
                        <form role="form" action="register.php" method="post">
                            <div class="card-body">
                                <div class="form-group">
                                    <label for="username">Username</label>
                                    <input type="text" class="form-control" id="username" name="username" placeholder="Username" required>
                                </div>
                                <div class="form-group">
                                    <label for="password">Password</label>
                                    <input type="password" class="form-control" id="password" name="password" placeholder="Password" required>
                                </div>
                                <div class="form-group">
                                    <label for="lastname">Last Name</label>
                                    <input type="text" class="form-control" id="lastname" name="lastname" placeholder="Last Name" required>
                                </div>
                                <div class="form-group">
                                    <label for="firstname">First Name</label>
                                    <input type="text" class="form-control" id="firstname" name="firstname" placeholder="First Name" required>
                                </div>
                                <div class="form-group">
                                    <label for="middlename">Middle Name</label>
                                    <input type="text" class="form-control" id="middlename" name="middlename" placeholder="Middle Name" required>
                                </div>
                                <div class="form-group">
                                    <label for="email">Email</label>
                                    <input type="email" class="form-control" id="email" name="email" placeholder="Email" required>
                                </div>
                            </div>
                           

                            <div class="card-footer">
                                <button type="submit" class="btn btn-primary">Register</button>
                            </div>
                        </form>
                    </div>
                    
                </div>
               
            </div>
            
        </div>
    </section>
    
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/admin-lte/3.2.0/js/adminlte.min.js"></script>
</body>
</html>
