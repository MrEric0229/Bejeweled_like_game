<?php
include "connection.php";
session_start();

$connection = new connection();
$username = $_SESSION['username'];
?>

<html>
<head>
    <title>
        Leader Board
    </title>
    <link rel="stylesheet" href="css/main.css">
</head>
<body>
    <div class="user_profile" style="margin-bottom: 50px">
        <div class="dropdown">
            <button class="dropbtn"><?php echo $username; ?></button>
            <div class="dropdown-content">
                <a href="profile.php">Profile</a>
                <a href="sign_out.php">Log out</a>
            </div>
        </div>
        <!/a>
    </div>

    <div class="leader_board_div">
        <div class="leader_board_text">
            <h2 color="a32638">Leader Board</h2>
        </div>
        <div class="leader_board_table_div">
            <table class="leader_board_table">
                <tr>
                    <th>N0.</th>
                    <th>Player</th>
                    <th>Score</th>
                </tr>
                <?php
                $leaders = $connection->getTopScores();
                $count = 0;
                count($leaders)>10 ? $count=10 : $count = count($leaders);
                for( $i=0; $i<$count; $i++){
                    echo "<tr>";
                    echo "<th>".($i+1)."</th>";
                    echo "<th>
                                <img src='".$leaders[$i]['image']."' width='20px' height='20px'>
                                <a class='leader_board_link' href='profile.php?id=".$leaders[$i]['id']."'>".$leaders[$i]['username']."</a>
                          </th>";
                    echo "<th>".$leaders[$i]['score']."</th>";
                    echo "</tr>";
                }
                ?>
            </table>
        </div>
        <form action="game.php" method="post" style="margin-top: 50px">
            <input class="back_button" type="submit" value="Back">
        </form>
    </div>

<script>
    function goGame(){
        window.location.href = "game.php"
    }
</script>
</body>
</html>