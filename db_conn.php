<?php
//https://github.com/Lewdlinguini/ipt101.2
$sname = "localhost";

$uname = "root";

$password = "";

$db_name = "IPT101";

$conn = mysqli_connect($sname, $uname, $password, $db_name);

if(!$conn) {

    echo "Connection failed!";

}