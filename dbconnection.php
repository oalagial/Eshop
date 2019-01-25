<?php
    echo '<script>console.log("inside")</script>';


try {

     $server = "webpagesdb.it.auth.gr";
     $username = "oalagial";
     $password = "oalagial_Electronic_Eshop";
     $database = "oalagial_Electronic_Eshop";

    $connection = mysqli_connect($server, $username, $password, $database);
    echo '<script>console.log("inside2")</script>';
    if ($connection) {
        echo '<script>console.log("database connection was successfull")</script>';
        // echo "win!!!!!";
    }
} catch (Exception $errormsg) {
    echo $errormsg->getMessage();
    echo '<script>console.log("ERROR")</script>';

}


