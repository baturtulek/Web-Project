<?php
session_start();
require_once 'db.php';

if(isset($_POST["completeShop"])){

    foreach ($_SESSION["pro"] as $key => $value){
        $pId [] = $value["id"];
    }
    $sql = "insert into orders (custMail, proId) values (?, ?) ";
    $stmt = $db->prepare($sql);
    $stPıd = implode(",", $pId);
    $stmt->execute([$_SESSION["user"]["mail"], $stPıd]);

    $_SESSION["pro"] = "";
    header("Location: orders.php");
    exit;
}

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
        <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
        <meta charset="UTF-8">
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
      
        #theader{
            font-weight: bold;
            font-size: 21px;
            background: #bebebe;
        }
        #products{
           margin:10px auto;
           border-collapse: collapse;
        }
        #products td{
            border:1px solid black;
            padding:20px;
            font-size: 16px;
        }
        h2{
            text-align: center;
        }
        #complete{
            margin:0px auto;
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
                <a href = 'myCart.php'><img src = 'img/cart.jpg' width=35></a>
                <?= isset($_SESSION["user"]) ? "<a href = 'logout.php'><input type = 'button' name = 'logout' value = 'Logout'></a>" : ""?>
                <?= (isset($_SESSION["user"]) && $_SESSION["user"]["role"] == 2) ? "<a href = 'addProduct.php'><input type = 'button' name = 'admin' value = 'Add Product'></a>" : ""?>
                <?= (isset($_SESSION["user"]) && $_SESSION["user"]["role"] == 2) ? "<a href = 'selectPromotional.php'><input type = 'button' name = 'addPromo' value = 'Select Promotional'></a>" : ""?>
                <?= (isset($_SESSION["user"]) && $_SESSION["user"]["role"] == 2) ? "<a href = 'addUser.php'><input type = 'button' name = 'addUser' value = 'Add User'></a>" : ""?>

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

<?php
if(empty($_SESSION["user"])){
    echo "<h2>Your shopping cart is empty!</h2>";
}else{
    if(empty($_SESSION["pro"])){
        echo "<h2>Your shopping cart is empty!</h2>";
    }else{
        $sql = "select * from products where id = ?";
        $stmt = $db->prepare($sql);
        
        $index = 1;
        echo "<table id = 'products'>";
        echo "<tr id='theader'><td></td><td></td><td>Brand</td><td>Title</td><td>Price</td><td>Rating</td></tr>";
        foreach($_SESSION["pro"] as $key => $value){
            $stmt->execute([$value["id"]]);
            echo "<tr>";
            echo "<td>{$index}</td>";
            echo "<td><img src = 'img/{$value["pimg"]}' width=175></td>";
            echo "<td>{$value["brand"]}</td>";
            echo "<td>{$value["title"]}</td>";
            echo "<td>{$value["price"]}</td>";
            echo "<td>{$value["rating"]}</td>";
            echo "</tr>";
            $index += 1;
        }
        echo "</table>";
        echo "<form action = '' method = 'post'>";
        echo "<table id ='complete'><tr>";
        echo "<td><input type = 'submit' name = 'completeShop' value = 'Complete Shopping'></td>";
        echo "</tr></table></form>";
    }
}

?>
</body>
</html>
