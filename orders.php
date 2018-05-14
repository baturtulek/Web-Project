<?php
session_start();
require_once 'db.php';

?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <meta charset="UTF-8">
        <!-- Latest compiled and minified CSS -->
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">

        <!-- jQuery library -->
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>

        <!-- Latest compiled JavaScript -->
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
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
        
    #header{
            margin: 0px auto;
        }
        #header td{
            padding-left:120px;
        }
        #products{
            width:100%;
            height:60px;
            background: #66e0ff;
            border: 2px solid black;
            border-collapse: collapse;
        }
        #products td{
            border:1px solid black;
            text-align: center;
        }
        #products td:hover{
            background: white;
            color: black;
        }
        #pro{
            margin: 20px auto;
            border-collapse: collapse;
        }
        #pro td {
            border:1px solid black;
            padding:15px;
        }
        h2{
            text-align: center;
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
if (empty($_SESSION["user"])) {
    echo "<h2>Your orders is empty!</h2>";
} else {

    $sql = "select proId from orders where custMail = ?";
    $stmt = $db->prepare($sql);
    $stmt->execute([$_SESSION["user"]["mail"]]);
    $p1 = $stmt->fetch();
    $p [] = explode(",", $p1);
    if ($stmt->rowCount() > 0) {

        $index = 1;
        echo "<table id = 'pro'>";
        echo "<tr><td></td><td></td><td>Brand</td><td>Title</td><td>Price</td><td>Rating</td></tr>";
            $sql1 = "select * from products where id = ?";
            $stmt1 = $db->prepare($sql1);
            $stmt1->execute($p[0]);
            $value = $stmt1->fetch();
            echo "<tr>";
            echo "<td>{$index}</td>";
            echo "<td><img src = 'img/{$value["pimg"]}' width=175></td>";
            echo "<td>{$value["brand"]}</td>";
            echo "<td>{$value["title"]}</td>";
            echo "<td>{$value["price"]}</td>";
            echo "<td>{$value["rating"]}</td>";
            echo "</tr>";
            $index += 1;
        
        echo "</table>";

    }
}
?>
</body>
</html>
