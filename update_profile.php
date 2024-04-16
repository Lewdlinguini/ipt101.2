<?php
session_start();

if (!isset($_SESSION['authenticated'])) {
    header("Location: Loginform.php");
    exit();
}

include 'db_conn.php';

$user_id = $_SESSION['id'];
$user_name = '';
$user_email = '';
$user_phone_number = '';
$user_address = '';
$success_message = '';
$error_message = '';


$sql_fetch_profile = "SELECT * FROM user_profile WHERE user_id = ?";
$stmt_fetch_profile = $conn->prepare($sql_fetch_profile);
$stmt_fetch_profile->bind_param("i", $user_id);
$stmt_fetch_profile->execute();
$result_fetch_profile = $stmt_fetch_profile->get_result();
$row = $result_fetch_profile->fetch_assoc();
$stmt_fetch_profile->close();

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["submit"])) {
   
    $sql_profile = "UPDATE user_profile SET ";
    $sql_profile_params = array();

    if (!empty($_POST['user_name'])) {
        $sql_profile .= "full_name = ?, ";
        $sql_profile_params[] = $_POST['user_name'];
    }

    if (!empty($_POST['email'])) {
        $sql_profile .= "email = ?, ";
        $sql_profile_params[] = $_POST['email'];
    }

    if (!empty($_POST['phone_number'])) {
        $sql_profile .= "phone_number = ?, ";
        $sql_profile_params[] = $_POST['phone_number'];
    }

    if (!empty($_POST['address'])) {
        $sql_profile .= "address = ?, ";
        $sql_profile_params[] = $_POST['address'];
    }

    
    $sql_profile = rtrim($sql_profile, ', ');

    
    $sql_profile .= " WHERE user_id = ?";
    $sql_profile_params[] = $user_id;

   
    $stmt_profile = $conn->prepare($sql_profile);
    $stmt_profile->bind_param(str_repeat("s", count($sql_profile_params)), ...$sql_profile_params);
    $stmt_profile->execute();
    $stmt_profile->close();

    
    if (!empty($_FILES["user_photo"]["name"])) {
        $target_dir = "uploads/";
        $target_file = $target_dir . basename($_FILES["user_photo"]["name"]);
        $uploadOk = 1;
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

       
        $check = getimagesize($_FILES["user_photo"]["tmp_name"]);
        if ($check !== false) {
            $uploadOk = 1;
        } else {
            $error_message = "File is not an image.";
            $uploadOk = 0;
        }

       
        if (file_exists($target_file)) {
            $error_message = "Sorry, file already exists.";
            $uploadOk = 0;
        }

       
        if ($_FILES["user_photo"]["size"] > 500000) {
            $error_message = "Sorry, your file is too large.";
            $uploadOk = 0;
        }

       
        if ($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "gif") {
            $error_message = "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
            $uploadOk = 0;
        }

        
        if ($uploadOk == 0) {
            $error_message = "Sorry, your file was not uploaded.";
        } else {
            
            if (move_uploaded_file($_FILES["user_photo"]["tmp_name"], $target_file)) {
                
                $sql_update_photo = "UPDATE user_profile SET photo = ? WHERE user_id = ?";
                $stmt_update_photo = $conn->prepare($sql_update_photo);
                $stmt_update_photo->bind_param("si", $target_file, $user_id);
                $stmt_update_photo->execute();
                $stmt_update_photo->close();
                $success_message = "The file " . htmlspecialchars(basename($_FILES["user_photo"]["name"])) . " has been uploaded and profile photo updated.";
            } else {
                $error_message = "Sorry, there was an error uploading your file.";
            }
        }
    } else {
        
        $error_message = "No photo selected.";
    }

    
    $sql_fetch_profile = "SELECT * FROM user_profile WHERE user_id = ?";
    $stmt_fetch_profile = $conn->prepare($sql_fetch_profile);
    $stmt_fetch_profile->bind_param("i", $user_id);
    $stmt_fetch_profile->execute();
    $result_fetch_profile = $stmt_fetch_profile->get_result();
    $row = $result_fetch_profile->fetch_assoc();
    $stmt_fetch_profile->close();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Profile</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/admin-lte/3.2.0/css/adminlte.min.css">
</head>

<body>
    <div class="wrapper">
        <nav class="main-header navbar navbar-expand navbar-white navbar-light">
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link" href="home.php"><i class="fas fa-home"></i> Home</a>
                </li>
            </ul>
        </nav>
        <div class="content-wrapper">
            <div class="content-header">
                <div class="container-fluid">
                    <div class="row mb-2">
                        <div class="col-sm-6">
                            <h1 class="m-0">Update Profile</h1>
                        </div>
                    </div>
                </div>
            </div>
            <section class="content">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="card card-primary">
                                <div class="card-header">
                                    <h3 class="card-title">Profile</h3>
                                </div>
                                <div class="card-body">
                                    <?php if (!empty($success_message)) : ?>
                                        <div class="alert alert-success" role="alert">
                                            <?php echo $success_message; ?>
                                        </div>
                                    <?php endif; ?>
                                    <?php if (!empty($error_message)) : ?>
                                        <div class="alert alert-danger" role="alert">
                                            <?php echo $error_message; ?>
                                        </div>
                                    <?php endif; ?>
                                    <form action="" method="post" enctype="multipart/form-data">
                                        <div class="form-group">
                                            <label for="user_photo">Photo:</label>
                                            <input type="file" id="user_photo" name="user_photo" class="form-control-file">
                                        </div>
                                        <div class="form-group">
                                            <label for="user_name">Name:</label>
                                            <input type="text" id="user_name" name="user_name" class="form-control"
                                                value="<?php echo htmlspecialchars($row['full_name']); ?>">
                                        </div>
                                        <div class="form-group">
                                            <label for="email">Email:</label>
                                            <input type="email" id="email" name="email" class="form-control"
                                                value="<?php echo htmlspecialchars($row['email']); ?>">
                                        </div>
                                        <div class="form-group">
                                            <label for="phone_number">Phone Number:</label>
                                            <input type="text" id="phone_number" name="phone_number"
                                                class="form-control" value="<?php echo htmlspecialchars($row['phone_number']); ?>">
                                        </div>
                                        <div class="form-group">
                                            <label for="address">Address:</label>
                                            <textarea id="address" name="address"
                                                class="form-control"><?php echo htmlspecialchars($row['address']); ?></textarea>
                                        </div>
                                        <button type="submit" name="submit" class="btn btn-primary">Update Profile</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </div>
        <aside class="control-sidebar control-sidebar-dark">
        </aside>
    </div>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/4.6.0/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/admin-lte/3.2.0/js/adminlte.min.js"></script>
</body>

</html>
