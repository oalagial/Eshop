<?php



error_reporting(E_ERROR | E_PARSE);



session_start();



$server = "webpagesdb.it.auth.gr";
$username = "oalagial";
$password = "oalagial_Electronic_Eshop";
$database = "oalagial_Electronic_Eshop";



$connection = mysqli_connect($server, $username, $password, $database);





if(isset($_POST["add"])){

    if(isset($_SESSION["shopping_cart"])){

		$item_array_id = array_column($_SESSION["shopping_cart"], "item_id");

        if (!in_array($_GET["id"], $item_array_id)) {

            $count = count($_SESSION["shopping_cart"]);

            $item_array = array(

            'item_id' => $_GET["id"],

            'item_name' => $_POST["hidden_name"],

            'item_price' => $_POST["hidden_price"],

            'item_quantity' => $_POST["quantity"]

            );

            $_SESSION["shopping_cart"][$count] = $item_array;


        }

		else

		{

			echo '<script>alert("Products already added to cart")</script>';

			// echo '<script>window.location="products.php"</script>';

		}

    }

    else{

        $item_array = array(

            'item_id' => $_GET["id"],

            'item_name' => $_POST["hidden_name"],

            'item_price' => $_POST["hidden_price"],

            'item_quantity' => $_POST["quantity"]

            );

            $_SESSION["shopping_cart"][0] = $item_array;

    }

}



if(isset($_GET["action"]))

{

	if($_GET["action"] == "delete")

	{

		foreach($_SESSION["shopping_cart"] as $keys => $values)

		{

			if($values["item_id"] == $_GET["id"])

			{

				unset($_SESSION["shopping_cart"][$keys]);

				// echo '<script>alert("Product has been removed")</script>';

				// echo '<script>window.location="products.php"</script>';

			}

		}

	}

}











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

<img onClick="window.location='products.php'" src="./Other_Images/eshop-banner2.png" alt="Banner" height="80" width="230"></div>

<img src="./Other_Images/Basket.png" alt="Basket" height="34" width="50"></div>

</header>



<div class="main_container">



    <div class="filters_container">



        <div class="filter_box">

                <h2>Filters</h2>

                <div class="filter">



                <form id="radio_form" action="" method="post" onchange="selection()">

                <input type="radio" name="radio" value="1">0-100€<br>

                <input type="radio" name="radio" value="2">100-200€<br>

                <input type="radio" name="radio" value="3">200-500€<br>

                <input type="radio" name="radio" value="4">500+€<br>

                <input type="radio" name="radio" value="5">All Products<br>

                <input type="submit" name="bttnsubmit" value="Add filter" class="add_filter-button"/>

                </form>



                </div>

        </div>





    </div>







        <div class="products_container">

            <div class="products_inner_container">

            <div class="grid-container">

                    <?php

                    include('dbconnection.php');



                    

                    if (isset($_POST['bttnsubmit']) or true) {

                        if (isset($_POST['radio'])) {

                            if($_POST['radio']==1){

                                $upPriceFilter = 0;

                                $downPriceFilter = 100;

                            }

                            if($_POST['radio']==2){

                                $upPriceFilter = 100;

                                $downPriceFilter = 200;

                            }

                            if($_POST['radio']==3){

                                $upPriceFilter = 200;

                                $downPriceFilter = 500;

                            }

                            if($_POST['radio']==4){

                                $upPriceFilter = 500;

                                $downPriceFilter = 9999999999;

                            }

                            if($_POST['radio']==5){

                                $upPriceFilter = 0;

                                $downPriceFilter = 9999999999;

                            }



                        }

                    }

                    else{

                        $upPriceFilter = 0;

                        $downPriceFilter = 50000;

                    }

                    



                    $total = "SELECT ID, ProductName, Price FROM products WHERE Price>=$upPriceFilter && Price<=$downPriceFilter";

                    $count = mysqli_query($connection, $total);

                    $nr = mysqli_num_rows($count);



                    $items_per_page=6;



                    $totalpages=ceil($nr/$items_per_page);



                    if (isset($_GET['page']) && !empty($_GET['page'])) {

                        $page=$_GET['page'];

                    } else {

                        $page=1;

                    }

                           $offset = ($page-1)*$items_per_page;

                           $sql = "SELECT * FROM products WHERE Price>=$upPriceFilter && Price<=$downPriceFilter LIMIT $items_per_page OFFSET $offset";

                           $query2 = "

                           SELECT products.Image,products.ID,products.Price, products.ProductName, AVG(products_ratings.rating) AS rating

                           FROM products

                           LEFT JOIN products_ratings

                           ON products.ID = products_ratings.Product

                           GROUP BY products.ID";

                           $query3 = "

                           SELECT products.Image,products.ID,products.Price, products.ProductName, AVG(products_ratings.rating) AS rating

                           FROM products

                           LEFT JOIN products_ratings

                           ON products.ID = products_ratings.Product

                           WHERE Price>=$upPriceFilter && Price<=$downPriceFilter

                           GROUP BY products.ID

                           LIMIT $items_per_page OFFSET $offset";



                           $result = mysqli_query($connection, $query3);

                           $row_count = mysqli_num_rows($result);





                           while ($row=mysqli_fetch_assoc($result)) {

                                ?>

                            <form method="post" action="products.php?action=add&id=<?php echo $row["ID"]; ?>">

                            <?php

                            echo '<div class="grid-item">';

                            echo '<img class="product_image" alt="Item" src="data:image/jpeg;base64,'.base64_encode( $row['Image'] ).'"/>';

                            echo '<div>';

                            echo "ID: " . $row["ID"]. " <br>Product Name: " . $row["ProductName"]. " <br>Price: " . $row["Price"]. "€".  "<br>";

                            ?>  

                            <div>Quantity: <input class="quantity_input" name="quantity" value="1" min='0' type="number" onkeypress="return (event.charCode == 8 || event.charCode == 0) ? null : event.charCode >= 48 && event.charCode <= 57"/></div>

                            

                                <div>Rating: <?php echo $row["rating"] ?> /5</div>

                            <div> Rate this product:

                                <?php foreach(range(1,5) as $rating): ?>

                                    <a class="rating-item" href="rate.php?product=<?php echo $row["ID"]?>&rating=<?php echo $rating?>"><?php echo $rating;?></a>

                                <?php endforeach; ?>

                            </div>

                            <div>

                                <a href="review.php?product=<?php echo $row["ID"]?>">All reviews</a>

                            </div>    

                            <input type="hidden" name="hidden_name" value="<?php echo $row["ProductName"]; ?>">

                            <input type="hidden" name="hidden_price" value="<?php echo $row["Price"]; ?>">

                            <a class="add_button-container"><input class="add_button" type="submit" name="add" value="Add to Cart"></a>

                            </div>

                            </div>



                                           

                        </form>

                        <?php

                        }

                        ?>

                    </div>





            <?php

                        echo "<div class='pagination'>";

                        for ($i=1;$i<=$totalpages;$i++) {

                            if ($i==$page) { //actual link

                                echo '<a class="active">' . $i . '</a>';

                            } else {

                                echo '<a href="/Our-Eshop/products.php?page='.$i.'">'.$i.'</a>';

                            }

                        }

                        echo "</div>";

                    ?>

            </div>



        </div>



        <div class="myBasket">

            <h2>My Basket</h2>



                    <?php

                    if(!empty($_SESSION["shopping_cart"]))

                    {

                        $total = 0;

                        foreach($_SESSION["shopping_cart"] as $keys => $values)

                        {

                            ?>

                            <div class="cart_item">

                            <div class="cart_item__name"><?php echo $values["item_name"]; ?></div>



                            <div>Quantity: <?php echo $values["item_quantity"] ?></div>

                            <div>Item Price: <?php echo $values["item_price"]; ?>€</div>

                            <div>Total: <?php echo number_format($values["item_quantity"] * $values["item_price"], 2); ?>€</div>

                            <a href="products.php?action=delete&id=<?php echo $values["item_id"]; ?>"><span>Remove</span></a>

                            <?php 

                            $total += ($values["item_quantity"] * $values["item_price"]);

                            echo '</div>';

                        }

                        ?>

                        <div class="cart_item__total-money">Total: <span class="cart_item__total-money--red"><?php echo number_format($total, 2); ?> €</span></div>

                        <button href="products.php" class="cart_item__checkout" onClick="alert('Sorry, our eshop is closed now.')">Checkout</button>



                        <?php

                    }

                    else{

                        echo '<div class="cart_item__total-money">Total: <span class="cart_item__total-money--red">0 €</span></div>';

                        echo '<button href="products.php" onClick="alert(\'Sorry, our eshop is closed now.\')" class="cart_item__checkout">Checkout</button>';

                    }

                    ?>





        </div>











</body>



</html>





<script>

            document.onload = page_load()



            function selection(){

                var myform = document.getElementById("radio_form");

                var selected = -1;

                for (var i = 0; i < myform.length; i++){

                    if (myform[i].checked){

                        selected = i;

                    }

                }

                if (selected!= -1){

                    sessionStorage.setItem("radioSelection",selected)

                }

                sessionStorage.setItem("setup",0)

            }





            function page_load(){

                var selected = sessionStorage.getItem("radioSelection")

                if (selected !== null){

                    document.getElementById("radio_form")[parseInt(selected)].checked = true

                    

                    if(parseInt(sessionStorage.getItem("setup")) == 0){

                        sessionStorage.setItem("setup",1)

                        document.getElementById("radio_form").submit()

                    }else{

                        sessionStorage.setItem("setup",0)

                    }

                }else{

                    document.getElementById("radio_form")[4].checked = true

                    sessionStorage.setItem("radioSelection",4)

                    document.getElementById("radio_form").submit()

                    sessionStorage.setItem("setup",1)



                }

            }

</script>

