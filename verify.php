<?php
include "db_conn.php";


if(isset($_GET['email'])) {
    $email = $_GET['email'];

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $verification_code = $_POST['verification_code'];

        
        $check_verification_sql = "SELECT * FROM user WHERE Email='$email' AND verification_code='$verification_code'";
        $check_verification_result = mysqli_query($conn, $check_verification_sql);

        if (mysqli_num_rows($check_verification_result) > 0) {
            
            $update_status_sql = "UPDATE user SET Status='active' WHERE Email='$email'";
            if (mysqli_query($conn, $update_status_sql)) {
                
                header("Location: Loginform.php");
                exit();
            } else {
                echo '<p class="error">Error updating status.</p>';
            }
        } else {
            echo '<p class="error-message">Invalid verification code. Please try again.</p>';
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Verification</title>
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
    <form action="verify.php?email=<?php echo $email; ?>" method="post">
        <div class="container">
            <h2>Verification</h2>
            <div class="form-group">
                <label for="verification_code">Verification Code</label>
                <input type="text" name="verification_code" id="verification_code" class="form-control" placeholder="Verification Code" required>
            </div>
            <button type="submit" class="btn btn-primary btn-black">Verify</button>
        </div>
    </form>
</body>
</html>
