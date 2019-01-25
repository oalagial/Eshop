<?php

session_start();

$server = "webpagesdb.it.auth.gr";
$username = "oalagial";
$password = "oalagial_Electronic_Eshop";
$database = "oalagial_Electronic_Eshop";

$db = mysqli_connect($server, $username, $password, $database);


?>





<!DOCTYPE html>
<html lang="en">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <title>Our Eshop</title>
    <link rel="stylesheet" href="./style.css">
</head>

<body>
<header> 
<img onClick="window.location='products.php'" href="oalagial" src="./Other_Images/eshop-banner2.png" alt="Banner" height="80" width="230"></div>
<img src="./Other_Images/Basket.png" alt="Basket" height="34" width="50"></div>
</header>

<div class="review-container">



<?php
if (isset($_GET['product'])) {
    $product = (int)$_GET['product'];

    $queryForProductName = "SELECT * FROM products WHERE ID = $product";
    $resultName = mysqli_query($db, $queryForProductName);
    while ($row2=mysqli_fetch_assoc($resultName)) {
    ?>
        <h1><?php echo $row2["ProductName"]; ?> </h1>
    
    <?php
    }
        $queryReview = "SELECT * FROM products_reviews WHERE Product = {$product}";
    $resultReview = mysqli_query($db, $queryReview);


    ?>

    <h3>All Reviews: </h3>
    <?php
    while ($row=mysqli_fetch_assoc($resultReview)) {
   ?>   


        <div class="review-item"><span class="review-item__number"> Review No:  <?php echo $row["ID"]; ?> </span><br><span class="review-item__comment"> <?php echo $row["Comment"]; ?> <span></div>
  <?php
    }
}
?>

<form class="addind-review-form" action="review.php?product=<?php echo $product?>" method="post">
    <textarea class="review-input" type="text" name="review"> </textarea><br>
    <input class="add_filter-button" type="submit" value="Insert your Comment">
</form>



<?php

 if(isset($_POST['review'])) { 
    $addingReview = $_POST['review']; 

    $queryAddReview = "INSERT INTO products_reviews (Product,Comment) VALUES ('$product', '$addingReview')";
    if(mysqli_query($db,$queryAddReview))
    {
        echo '<script>console.log("successful")</script>';
    }else{

    }

    // header('Location: review.php?product='.$product);
      echo '<script>window.location="review.php?product='.$product.'"</script>';


}



?>
<a class="home-button" href="products.php"> Go to Home Page </a>

</div>