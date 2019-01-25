<?php

session_start();


$server = "webpagesdb.it.auth.gr";
$username = "oalagial";
$password = "oalagial_Electronic_Eshop";
$database = "oalagial_Electronic_Eshop";

$db = mysqli_connect($server, $username, $password, $database);

if(isset($_GET['product'], $_GET['rating'])) {

        $product = (int)$_GET['product'];
        $rating = (int)$_GET['rating'];
        echo '<script>console.log("1")</script>';

        if(in_array($rating, [1, 2, 3, 4, 5])) {
            echo '<script>console.log("2")</script>';
            echo '<script>console.log("d.5")</script>';       

        $exists = $db->query("SELECT ID FROM products WHERE ID = {$product}")->num_rows ? true : false;
            echo '<script>console.log("a")</script>';       

        if($exists) {
            echo '<script>console.log("3.5")</script>';       
        $sql2 = "INSERT INTO products_ratings (Product, rating) VALUES ({$product}, {$rating})";
        if ($db->query($sql2) === TRUE) {
            echo "New record created successfully";
            ob_start();

        } else {
            echo "Error: " . $sql2 . "<br>" . $db->error;
        }  
    }

        }
        echo '<script>window.location="products.php"</script>';
    }
?>