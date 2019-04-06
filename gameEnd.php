<?php
include 'connection.php';
session_start();

$connection= new connection();
$score = $_GET['score'];
$id = $_SESSION['id'];

$connection->insertScore($id, $score);
?>


<!DOCTYPE html>

<!-- [if lt IE 7]>      <html class="no-js lt-ie9 lt-ie8 lt-ie7" lang=""> <![endif]-->
<!--[if IE 7]>         <html class="no-js lt-ie9 lt-ie8" lang=""> <![endif]-->
<!--[if IE 8]>         <html class="no-js lt-ie9" lang=""> <![endif]-->
<!--[if gt IE 8]><!-->
<!-- <html class="no-js" lang="">  -->
<!--<![endif] -->
<html>

<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
  <title>A Bejeweled like game</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="css/normalize.min.css">
  <link rel="stylesheet" href="css/main.css">

  <script src="vendor/modernizr-2.8.3-respond-1.4.2.min.js"></script>
  <script>
  function getQueryVariable(variable){
       var query = window.location.search.substring(1);
       var vars = query.split("&");
       for (var i=0;i<vars.length;i++) {
               var pair = vars[i].split("=");
               if(pair[0] == variable){return pair[1];}
       }
       return(false);
  }

  function loadScore(){
    document.getElementById("finalScore").innerText = getQueryVariable("score");;
  }

</script>

</head>

<body onload="loadScore()">

    <!--[if lt IE 8]>
      <p class="browserupgrade">You are using an <strong>outdated</strong> browser. Please <a href="http://browsehappy.com/">upgrade your browser</a> to improve your experience.</p>
    <![endif]-->


    <header>
        <h1>Your time is out!</h1>
        <p>Your final score: <span id="finalScore"></span></p>
        <input type="button" value="Return" id="returnbutton" onclick="window.location.href='game.php';">

    </header>

    <script src="vendor/jquery-1.11.2.min.js"></script>
    <script>window.jQuery || document.write('<script src="//ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"><\/script>')</script>
    <script src="logic.js"></script>

</body>
</html>
