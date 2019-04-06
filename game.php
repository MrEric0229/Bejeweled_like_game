<!DOCTYPE html>

<!-- [if lt IE 7]>      <html class="no-js lt-ie9 lt-ie8 lt-ie7" lang=""> <![endif]-->
<!--[if IE 7]>         <html class="no-js lt-ie9 lt-ie8" lang=""> <![endif]-->
<!--[if IE 8]>         <html class="no-js lt-ie9" lang=""> <![endif]-->
<!--[if gt IE 8]><!-->
<!-- <html class="no-js" lang="">  -->
<!--<![endif] -->
<?php
include 'connection.php';
session_start();

$username = $_SESSION['username'];
$connection = new connection();
$id = $_SESSION['id'];
$max = $connection->getHighScore($id);
?>

<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
  <title>A Bejeweled like game</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="css/normalize.min.css">
  <link rel="stylesheet" href="css/main.css">

  <script src="vendor/modernizr-2.8.3-respond-1.4.2.min.js"></script>

</head>

<body>

    <!--[if lt IE 8]>
      <p class="browserupgrade">You are using an <strong>outdated</strong> browser. Please <a href="http://browsehappy.com/">upgrade your browser</a> to improve your experience.</p>
    <![endif]-->

    <div class="user_profile" >
        <!a style="color:#a32638" href="profile.php" id="logo">
        <!--div class="small_pic_dic">
            <img src="pic/default.png" alt="Profile Picture" width="30px" height="30px">
        </div-->

        <div class="dropdown">

            <button class="dropbtn"><?php echo $username; ?></button>
            <div class="dropdown-content">
                <a href="profile.php">Profile</a>
                <a href="sign_out.php">Log out</a>
            </div>
        </div>
        <!/a>
    </div>

    <header>
        <h1>A Bejeweled like game</h1>
        <input type="button" value="Start Game" id="newgamebutton" onclick="newGame()">
        <input type="button" value="Leader Board" id="leader_board_button" onclick="leaderBoard()">
        <p id="score_part">Your <span id="score">Best Score: <?php echo $max; ?></span></p>
        <h1 id="timer">

        </h1>
    </header>

    <!--div style="border: solid red; float:left; margin-top:50px; margin-left: 33%; width: 33%; height: 50px;">

    </div-->



    <br>
    <div id="grid-container">
    </div>

    <script src="vendor/jquery-1.11.2.min.js"></script>
    <script>window.jQuery || document.write('<script src="//ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"><\/script>')</script>
    <script src="logic.js"></script>

</body>
</html>
