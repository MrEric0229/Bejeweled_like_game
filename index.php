<?php
include 'connection.php';

//$username = "dbu319team099";
//$password = "NWNhMTAwMjAy";
//$dbServer = "mysql.cs.iastate.edu";
//$dbName = "db319team099";
//
//// Create connection
//$conn = new mysqli($dbServer, $username, $password, $dbName);
//// Check connection
//if ($conn->connect_error) {
//    die("Connection failed: " . $conn->connect_error);
//}
session_start();
$connection = new connection();

if(!empty($_POST['login_button'])){
    $username = $_POST['username'];
    $password = $_POST['password'];
    $sql = "SELECT password, id 
            FROM user
            WHERE username = '$username'";
    if( ($result = $connection->getData($sql))!==null ){
        if(strcmp($password, $result['password'])==0){
            $_SESSION['username'] = $username;
            $_SESSION['id'] = $result['id'];
            header("Location: game.php");
        }
        else{
            echo "<script> alert('Wrong User Name / Password'); </script>";
        }
    }
    else{
        echo "<script> alert('Wrong User Name / Password') </script>";
    }


//    $result =  mysqli_query($conn, $sql);
//    if($result->num_rows > 0){
//        $row = $result->fetch_assoc();
//        if(strcmp($password,$row['password'])==0){
//            header("Location: game.html");
//        }
//        else{
//            echo "<script> alert('Wrong User Name / Password'); </script>";
//        }
//    }
//    else{
//        echo "<script> alert('Wrong User Name / Password') </script>";
//    }

}
?>

<html>
<head>
    <!--script src="http://ajax.googleapis.com/ajax/libs/angularjs/1.3.14/angular.min.js"></script-->
    <!--link rel="stylesheet" type="text/css" href="login.css"-->
    <!--script src ="admin.js"></script-->
	<link rel="stylesheet" href="css/normalize.min.css">
    <link rel="stylesheet" href="css/main.css">
</head>

<body>
	<header class="index_header">
		<h1>A Bejeweled like game</h1>
	</header>
    <div style="text-align:center" id="login_div" class="login_div">
        <div ">
            <form action="" method="post">
                <!--label class="username_text">User Name:</label-->
                <input type="text" id="username" placeholder="Username" name="username" required/><br><br />

                <!--label class="username_text">Password:</label-->
                <input type="password" id="password" placeholder="Password" name="password" required/><br>

                <br />
                <input type="submit" class="login_button" name="login_button" value="Login" />
            </form>
			<form action="sign_up.php" method="post">
				<input type="submit" class="sign_up_button" name="sign_up_button" value="Sign Up"/>
			</form>
        </div>
    </div>

</body>
</html>