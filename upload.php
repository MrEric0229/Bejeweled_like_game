
<html>
<head>
    <Title>Update Profile Picture</Title>
    <link rel="stylesheet" href="css/normalize.min.css">
    <link rel="stylesheet" href="css/main.css">
</head>
</html>

<?php
    include 'connection.php';

    session_start();
    $id = $_SESSION['id'];
    $connection = new connection();

    $target_dir = "pic/".$id."/";
    $target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]);
    $uploadOk = 1;
    $imageFileType = pathinfo($target_file,PATHINFO_EXTENSION);

    $target_file = $target_dir.date("YnjGis").".".$imageFileType;
    //var_dump($target_dir.date("YnjGis").".".$imageFileType);
    // Check if image file is a actual image or fake image
    echo "<div class='upload_result'> 
            <p class='upload_text' style='color: #a32638'>";

    if(isset($_POST["submit"])) {
        $check = getimagesize($_FILES["fileToUpload"]["tmp_name"]);
        if($check !== false) {
            //echo "File is an image - " . $check["mime"] . ".";
            $uploadOk = 1;
        } else {
            echo "File is not an image.";
            $uploadOk = 0;
        }
    }

    if (!file_exists($target_dir) ){
        mkdir($target_dir);
    }

    // Check if file already exists
    if (file_exists($target_file)) {
        echo "Sorry, file already exists.";
        $uploadOk = 0;
    }
    // Check file size
//    if ($_FILES["fileToUpload"]["size"] > 500000) {
//        echo "Sorry, your file is too large.";
//        $uploadOk = 0;
//    }
    // Allow certain file formats
    if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
        && $imageFileType != "gif" ) {
        echo "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
        $uploadOk = 0;
    }
    // Check if $uploadOk is set to 0 by an error
    if ($uploadOk == 0) {
        echo "Sorry, your file was not uploaded.";
    // if everything is ok, try to upload file
    } else {
        if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
            //echo "The file ". basename( $_FILES["fileToUpload"]["name"]). " has been uploaded.";
            echo "Your new profile picture has been uploaded.";
            $connection->updatePic($id, $target_file);
        } else {
            echo "Sorry, there was an error uploading your file.";
        }
    }
    echo "</p><a class='back_link' style='color: #a32638' href='profile.php'> Back </a></div>";
?>

