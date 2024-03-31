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
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verification</title>
    
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
                            <h3 class="card-title">Verification</h3>
                        </div>
                        
                        <form role="form" action="verify.php?email=<?php echo $email; ?>" method="post">
                            <div class="card-body">
                                <div class="form-group">
                                    <label for="verification_code">Verification Code</label>
                                    <input type="text" class="form-control" id="verification_code" name="verification_code" placeholder="Enter verification code" required>
                                </div>
                            </div>
                            <!-- /.card-body -->

                            <div class="card-footer">
                                <button type="submit" class="btn btn-primary">Verify</button>
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
