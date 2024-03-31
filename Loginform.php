<!DOCTYPE html>
<html> 
<head>
    <title>LOGIN</title>
    <link rel = "stylesheet" type = "text/css" href = "Stylesheet.css">
</head>

<body>
    <form action =  "index.php" method = "post">
        <h2>LOGIN</h2>
        <?php if(isset($_GET['error'])) { ?>
            <p class = "error"><?php echo $_GET['error']; ?></p>
        <?php } ?>
        <label>User Name</label>
        <input type = "text" name = "uname" placeholder = "User name">
        <br>
        <label>Password</label>
        <input type = "password" name = "password" placeholder = "Password">
        <br>
        <button type = "submit">Login</button>
    </form>
    <p style="color: white;">Don't have an account?&nbsp;<a href="register.php" style="color: white; background-color: black; padding: 5px;">Register here</a></p>
</body>
</html>