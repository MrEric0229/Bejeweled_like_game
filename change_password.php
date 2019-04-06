<?php
include "connection.php";
session_start();

$connection = new connection();
$id = $_SESSION['id'];

if( !empty($_POST['change_pass_submit_button']) ){
    $old = $_POST['old_password'];
    $new1 = $_POST['new_password1'];
    $new2 = $_POST['new_password2'];
    $pass = $connection->getPass($id);

    if( strcmp($pass, $old)!==0 ){
        echo "<script> alert('Old password is wrong. Please enter again!') </script>";
    }
    else if( strcmp($new1, $new2)!==0 ){
        echo "<script> alert('Two passwords are not the same. Please enter again!') </script>";
    }
    else{
        $connection->updatePass($id, $new1);
        echo "<script> alert('Password is changed') </script>";
        header("Location: profile.php" );
    }
}

?>

<html>
<head>
    <title>
        Change Password
    </title>
    <link rel="stylesheet" href="css/normalize.min.css">
    <link rel="stylesheet" href="css/main.css">
</head>
<body>
    <div class="change_pass_div">
        <h1 class="change_pass_h1">
            Change Password
        </h1>
        <form action="" method="post" class="change_pass_form">
            <p> <input type="password" name="old_password" placeholder="Old Password" > </p>
            <p> <input type="password" name="new_password1" placeholder="New Password" > </p>
            <p> <input type="password" name="new_password2" placeholder="Enter Again" > </p>
            <p> <input type="submit"  class ="change_pass_submit_button" name="change_pass_submit_button" value="Submit" > </p>
        </form>
        <input type="button" class="change_pass_cancel_button" value="Cancel" onclick="window.location.href='profile.php'">
    </div>
</body>
</html>
