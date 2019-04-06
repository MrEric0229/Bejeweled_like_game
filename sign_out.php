<?php
session_start();
session_destroy();
header( "refresh:5; url=index.php" );
?>
<html>
<head>
    <title>
        Sign Out
    </title>
    <link rel="stylesheet" href="css/main.css">
    <script src="https://ajax.googleapis.com/ajax/libs/angularjs/1.4.8/angular.min.js"></script>
</head>
<body>
    <div style="text-align: center; margin-top: 150px" ng-app="">
        <p style='color:#a32638'>
            Your session is out. You will be redirected to main page in <span id="counter">5</span> seconds.
            <a style='color:#bf273f' href='index.php'>Return now.</a>
        </p>
    </div>

    <script type="text/javascript">
        function countdown() {
            var i = document.getElementById('counter');
            i.innerHTML = parseInt(i.innerHTML)-1;
        }
        setInterval(function(){ countdown(); },1000);
    </script>
</body>
</html>
