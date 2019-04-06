<?php
include 'connection.php';
session_start();

$connection = new connection();
$conn = $connection->getConn();

if(!empty($_POST['sign_up_submit_button'])){
    $username = $_POST['username'];
    $password = $_POST['password'];
    $email = $_POST['email'];
    $sql = "INSERT INTO user(email, username, password, image) 
            VALUES('$email','$username','$password', 'pic/default.png')";
    if( $connection->writeData($sql) ){
        header("Location: index.php");
    }
    else
        echo "<script> alert('Sorry, we can not add you to our user list.')";
}

?>

<html>
<head>
    <title>Sign Up</title>
    <link rel="stylesheet" href="css/normalize.min.css">
    <link rel="stylesheet" href="css/main.css">
</head>
<body>
    <header>
        <h1>Sign Up!</h1>
    </header>
    <div class="sign_up_div">
        <form action="" method="post">
            <!--p class="sign_up_email_text">Email: <input type="text" class="email" placeholder="enter your email address" name="email"></p>
            <p class="sign_up_username_text">User Name: <input type="text" class="username" placeholder="enter your user name" name="username"/> </p>
            <p class="sign_up_password_text">Password: <input type="text" class="password" placeholder="enter your password" name="password" </p-->
            <p> <input type="text" class="email" placeholder="Email Address" name="email"></p>
            <p> <input type="text" class="username" placeholder="Username" name="username"/> </p>
            <p> <input type="text" class="password" placeholder="Password" name="password" ></p>
            <p><input type="submit" class="sign_up_submit_button" name="sign_up_submit_button" value="Submit"/></p>
        </form>
        <form action="index.php" method="post">
            <input class="sign_up_cancel_button" type="submit" value="Cancel">
        </form>
    </div>
</body>
</html>
