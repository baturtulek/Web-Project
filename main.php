<?php
session_start();
require_once 'db.php';
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <!-- Latest compiled and minified CSS -->
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">

        <!-- jQuery library -->
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>

        <!-- Latest compiled JavaScript -->
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
        <!-- Latest w3 CSS library -->
        <link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">

        <meta name="viewport" content="width=device-width, initial-scale=1.0"/>

    <title></title>
    <style>
        body{
            margin:0px auto;
            background:#F5F3EE;
        }
        #list li{
            padding-left:80px;
            padding-right:60px;
        }
        #list li:hover{
            background:#050505;
        }
        #backC{
            background:#FFFFFF;
        }
        #header{
            margin: 5px 38px;
        }
        #header td{
            padding-left:80px;
        }
       
    </style>
</head>
<body>
<div class="container-fluid" id="backC">
    <table id="header">
        <tr>
            <td>
                <a href = "main.php"> <img src = "logo.png" width = "150" height = "190" > 
            </td>
            <td>
                <form class="example" action="action_page.php">
                <div class="input-group">
                    <input type="text" class="form-control" placeholder="I m your most humble servant.." name="search" size="60">
                    <div class="input-group-btn">
                        <button class="btn btn-default" type="submit"> <i class="glyphicon glyphicon-search"></i>
                        </button>
                    </div>
                </div>
                    
                </form>            
            </td>
            <td>
                <?= (isset($_SESSION["user"])) ? "" : "<a href = 'login.php'><input type = 'button' name = 'login' class='btn btn-danger' value = 'Login'></a>"?>
                <?= (isset($_SESSION["user"])) ? "" : "<a href = 'register.php'><input type = 'button' name = 'register' class='btn btn-primary' value = 'Register'></a>"?>
            </td>
            <td>
                <a href = 'myCart.php'><img src = 'img/cart.jpg' width=40 title="My Cart"></a>
            </td>
            <td>
                <div class="dropdown">
                    <button class="btn btn-success" type="button" data-toggle="dropdown">Panel<span class="caret"></span></button>
                    <ul class="dropdown-menu">
                        <li><?= isset($_SESSION["user"]) ? "<a href = 'logout.php'>Logout</a>" : ""?></li>
                        <li><?= (isset($_SESSION["user"]) && $_SESSION["user"]["role"] == 2) ? "<a href = 'addProduct.php'>Add Product</a>" : ""?></li>
                        <li> <?= (isset($_SESSION["user"]) && $_SESSION["user"]["role"] == 2) ? "<a href = 'selectPromotional.php'>Select Promotional</a>" : ""?></li>
                        <li> <?= (isset($_SESSION["user"]) && $_SESSION["user"]["role"] == 2) ? "<a href = 'addUser.php'>Add User</a>" : ""?></li>
                
                    </ul>
                </div>
            </td>
        </tr>
    </table>
    </div>
    
    <nav class="navbar navbar-inverse">
        <div class="container-fluid">
            <ul class="nav navbar-nav" id="list">
                <li><a href = "products.php?category=computer">Computer</a></li>
                <li><a href = "products.php?category=phone">Smart Phone</a></li>
                <li><a href = "products.php?category=television">Television</a></li>
                <li><a href = "products.php?category=book-film">Book and Film</a></li>
            </ul>
        </div>
    </nav>
    <div class="w3-content w3-display-container">
    <?php 
    try{
        $sql4 = "select * from proproducts"; 
        $stmt = $db->prepare($sql4);
        $stmt->execute();
        $products = $stmt->fetch(PDO::FETCH_ASSOC);
        var_dump($products);
        $pros = explode(",", $products['pid']);
        var_dump($pros);
        //$sql5 = "select * from products where id = ?";
        //$stmt3 = $db->prepare($sql5);
        //var_dump($stmt3);
        //var_dump($pros);
        //$first[] = $pros[0];
       /* foreach($pros as $item[]){
            $stmt3->execute($item);
            $pro1 = $stmt3->fetch(PDO::FETCH_ASSOC);
            $name = $pro1["pimg"];
            echo "<img class='mySlides' src='img/{$name}' style='width:100%'>";
        }
        */
        for($i = 0; $i < sizeof($pros); $i++){
            $sql5 = "select * from products where id = ?";
            $stmt3 = $db->prepare($sql5);
            $item[$i][] = $pros[$i];
            $stmt3->execute($item[$i]);
            $pro = $stmt3->fetch(PDO::FETCH_ASSOC);
            $name = $pro["pimg"];
            echo "<img class='mySlides' src='img/{$name}' style='width:100%'>";

        }
        //var_dump($first);
        //$stmt3->execute($first);
        //var_dump( $stmt3->execute($first));
        //$pro1 = $stmt3->fetch(PDO::FETCH_ASSOC);
        //var_dump($pro1);
        //$name = $pro1["pimg"];
        //echo "<img class='mySlides' src='img/{$name}' style='width:100%'>";
    }catch(Exception $ex){
        echo "<p>query Error!".$ex->getMessage()."</p>";
    }
  
     ?>
  <img class="mySlides" src="img_lights.jpg" style="width:100%">
  <img class="mySlides" src="img_mountains.jpg" sstyle="width:100%">
  <img class="mySlides" src="img_forest.jpg" style="width:100%">

  <button class="w3-button w3-black w3-display-left" onclick="plusDivs(-1)">&#10094;</button>
  <button class="w3-button w3-black w3-display-right" onclick="plusDivs(1)">&#10095;</button>
</div>


<script>
var slideIndex = 1;
showDivs(slideIndex);

function plusDivs(n) {
  showDivs(slideIndex += n);
}

function showDivs(n) {
  var i;
  var x = document.getElementsByClassName("mySlides");
  if (n > x.length) {slideIndex = 1}    
  if (n < 1) {slideIndex = x.length}
  for (i = 0; i < x.length; i++) {
     x[i].style.display = "none";  
  }
  x[slideIndex-1].style.display = "block";  
}
</script>

</body>
</html>
