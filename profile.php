<?php
include 'connection.php';
session_start();

$connection = new connection();
//$profile = $connection->getProfile($_SESSION['id']);
//var_dump($profile);
//$profile =
//var_dump($_GET['id']);
if(!empty($_GET)){
    $profile = $connection->getProfile($_GET['id']);
}
else{
    $profile = $connection->getProfile($_SESSION['id']);
}

?>
<html>
<head>
    <title>
        Profile
    </title>
    <link rel="stylesheet" href="css/normalize.min.css">
    <link rel="stylesheet" href="css/main.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
</head>
<body>
    <header>
        <h1>Profile</h1>
    </header>
    <div class="profile_div" >
        <div class="detail_div">
            <br>
            <p class="username_line">Username: <?php echo $profile['username']?></p>
            <p class="username_line">Password: <button class="change_password_button" onclick="window.location.href='change_password.php'">Change Password</button> </p>
            <p class="username_line">Email   : <?php echo $profile['email']?></p>
            <form action="upload.php" method="post" enctype="multipart/form-data" class="upload_form">
                <input type="button" value="Change Profile Picture" class="select_pic" id="select_pic" name="select_pic">
                <input type="file" name="fileToUpload" id="fileToUpload" value="Select" class="select_pic" style="display: none">
                <input type="submit" name="submit" value="Submit" style="margin-top: 5px" class="submit_pic">
            </form>
        </div>
        <div class="picture_div">
            <img src="<?php echo $profile['image']; ?>" alt="Profile Picture" style="width: 100%; height: 200px;">
        </div>
        <div class="score_div" >
            <h1 class="score_h1" > Highest 10 scores</h1>
            <div class="score_table_div">
                <table>
                    <tr>
                        <th>Number</th>
                        <th>Score</th>
                        <?php
                            $scores = $profile['scores'];
                            $count = 0;
                            count($scores)>9 ? $count=10 : $count = count($scores);
                            for( $i=0; $i<$count; $i++ ){
                                echo "<tr>";
                                echo "<th>".($i+1)."</th>";
                                echo "<th>".$scores[$i]."</th>";
                                echo "</tr>";
                            }
//                            foreach( $scores as $score){
//                                echo "<tr>";
//                                echo "<th>".($count+1)."</th>"; $count++;
//                                echo "<th>".$score."</th>";
//                                echo "</tr>";
//                            }
                        ?>
                    </tr>
                </table>
            </div>
        </div>
        <div class="profile_back_button_div">
            <form action="game.php" method="post">
                <input class="back_button" type="submit" value="Back">
            </form>
        </div>
    </div>

    <script>
        $(document).ready(function(){
            $("#select_pic").click(function () {
                $("#fileToUpload").trigger('click');
            });
        });

    </script>
</body>
</html>
