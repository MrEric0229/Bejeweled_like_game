<?php

class connection{
    public $conn;

    function __construct(){
        $username = "dbu319team099";
        $password = "NWNhMTAwMjAy";
        $dbServer = "mysql.cs.iastate.edu";
        $dbName = "db319team099";

        // Create connection
        $this->conn = new mysqli($dbServer, $username, $password, $dbName);
        // Check connection
        if ($this->conn->connect_error) {
            die("Connection failed: " . $this->conn->connect_error);
        }
    }

    function getConn(){
        return $this->conn;
    }

    function getData($sql){
        $result =  mysqli_query($this->conn, $sql);
        if($result->num_rows > 0) {
            return $result->fetch_assoc();
        }
        else return null;
    }

    function writeData($sql){
        if( mysqli_query($this->conn, $sql) ){
            return true;
        }
        else{
            false;
        }
    }

    function getPass($id){
        $sql = "SELECT password
                FROM user 
                WHERE id = $id";
        $result = $this->conn->query($sql);
        return $result->fetch_assoc()['password'];
    }

    function  updatePass($id, $newPass){
        $sql = "UPDATE user 
                SET password = '$newPass'
                WHERE id = $id";
        $this->conn->query($sql);
    }

    function updatePic($id, $pic){
        $sql = "UPDATE user 
                SET image = '$pic'
                WHERE id = $id";
        $this->conn->query($sql);
    }

    function getHighScore($id){
        $max = 0;
        $sql = "SELECT score
                FROM scores
                WHERE score_id = $id";
        $result = $this->conn->query($sql);
        if( $result->num_rows > 0 ){
            while( $row = $result->fetch_assoc() ){
                if( $row['score']>$max ){
                    $max = $row['score'];
                }
            }
        }
        return $max;
    }

    function getProfile($id){
        $data = array();
        $scores = array();
        $sql = "SELECT email, username, image, id
                FROM user 
                WHERE id = $id";
        $result = $this->conn->query($sql);
        if( $result->num_rows > 0 ){
            $row = $result->fetch_assoc();
            $data['username'] = $row['username'];
            $data['email'] = $row['email'];
            $data['image'] = $row['image'];
            $data['id'] = $row['id'];
        }
        $sql2 = "SELECT score
                 FROM scores
                 WHERE score_id = $id";
        $result2 = $this->conn->query($sql2);
        //trigger_error($this->conn->error);
        if( $result2->num_rows > 0 ){
            $count = 0;
            while( $row2 = $result2->fetch_assoc() ){
                //array_push($scores, $row2['score']);
                $scores[$count++] = $row2['score'];
                //var_dump($row2['score']);
            }
        }
        $data['scores'] = $this->sort($scores);
        //var_dump($data['scores']);
        return $data;
    }

    function sort($arr){
        for($i=0; $i <count($arr)-1; $i++){
            $index = $i;
            for($j=$i+1; $j<count($arr); $j++){
                if($arr[$j]>$arr[$index]){
                    $index = $j;
                }
            }
            $temp = $arr[$index];
            $arr[$index] = $arr[$i];
            $arr[$i] = $temp;
        }
        return $arr;
    }

//    function insertScore($id, $score){
//        $profile = $this->getProfile($id);
//        $scores = $profile['scores'];
//        $result = $scores;
//        $temp = null;
//        if( count($scores)<9 ){
//            $result[count($result)] = $score;
//        }
//        else{
//            if( $score >= $result[9] ){
//                $temp = $result[9];
//                $result[9] = $score;
//                $result = $this->sort($result);
//                $sql = "DELETE FROM scores
//                        WHERE score = '$temp'
//                        AND id = $id";
//                $this->conn->query($sql);
//            }
//
//        }
//        $sql2 = "INSERT INTO scores(score, score_id)
//                 VALUES('$score', $id)";
//        $this->conn->query($sql2);
//        return $result;
//    }

    function insertScore($id, $score){
        $sql = "INSERT INTO scores(score, score_id)
                VALUES('$score', $id)";
        $this->conn->query($sql);
        //trigger_error($this->conn->error);
    }

    function getTopScores(){

        $sql = "SELECT scores.score, scores.score_id, user.username, user.image
                FROM scores
                INNER JOIN user 
                ON user.id = scores.score_id";
        $result = $this->conn->query($sql);
        if( $result->num_rows > 0 ){
            $count = 0;
            while( $row = $result->fetch_assoc() ){
                $scores[$count++] = array(
                                        'score' => $row['score'],
                                        'username' => $row['username'],
                                        'id' => $row['score_id'],
                                        'image' => $row['image'] );
            }
        }
        $scores = $this->sortWithId($scores);
        return $scores;
    }

    function sortWithId($arr){
        for($i=0; $i <count($arr)-1; $i++){
            $index = $i;
            for($j=$i+1; $j<count($arr); $j++){
                if($arr[$j]['score']>$arr[$index]['score']){
                    $index = $j;
                }
            }
            $temp = $arr[$index];
            $arr[$index] = $arr[$i];
            $arr[$i] = $temp;
        }
        return $arr;
    }
}

$con = new connection();
//$con->getProfile(4)
//$con->insertScore(5,'8000');
//var_dump($con -> getTopScores());

?>