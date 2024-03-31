<!DOCTYPE html>
<html>
<head>
    <title>Registration</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="Stylesheet.css">
    <style>
       
        .container {
            max-width: 600px; 
            margin-top: 50px; 
        }
        
        .btn-black {
            background-color: black;
            color: white;
            border: none; 
        }

        .btn.btn-black:focus,
        .btn.btn-black.focus {
            box-shadow: none !important;
            outline: none !important;
        }

    </style>
</head>
<body>
    <form action="register.php" method="post"> 
        <div class="container">
            <h2>Registration</h2>
            <div class="form-group">
                <label for="username">Username</label>
                <input type="text" name="username" id="username" class="form-control" placeholder="Username" required>
            </div>
            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" name="password" id="password" class="form-control" placeholder="Password" required>
            </div>
            <div class="form-group">
                <label for="lastname">Last Name</label>
                <input type="text" name="lastname" id="lastname" class="form-control" placeholder="Last Name" required>
            </div>
            <div class="form-group">
                <label for="firstname">First Name</label>
                <input type="text" name="firstname" id="firstname" class="form-control" placeholder="First Name" required>
            </div>
            <div class="form-group">
                <label for="middlename">Middle Name</label>
                <input type="text" name="middlename" id="middlename" class="form-control" placeholder="Middle Name" required>
            </div>
            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" name="email" id="email" class="form-control" placeholder="Email" required>
            </div>
            <button type="submit" class="btn btn-primary btn-black">Register</button>

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
        </div>
    </form>
</body>
</html>