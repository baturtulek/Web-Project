<?php
session_start();
require_once 'db.php';
$added = FALSE;
if(empty($_SESSION["user"])){
    header("Location: main.php");
    exit;
}
if($_SESSION["user"]["role"] != 2){
    header("Location: main.php");
    exit; 
}
if(isset($_POST["choose"])){
    
    try{
    $sql2 = "insert into proProducts (pid) values (?)";
    $stmt2 = $db->prepare($sql2);
    $stmt2->execute([$_POST["id"]]);
    } catch (Exception $ex ){
        echo "<p> Query error !" . $ex->getMessage() . "</p>";
    }
    $added = true;

}
?>
<!DOCTYPE html>
<html>
    <head>
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
        
        #header{
            margin: 0px auto;
        }
        #header td{
            padding-left:120px;
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
        #theader{
            font-weight: bold;
            font-size: 21px;
            background: #bebebe;
        }
        #ask{
            margin: 10px auto;
        }
        #ask td{
            text-align: center;
            padding: 10px;
        }
        h1, h2{
            text-align: center;
            margin: 50px;
        }
    </style>
</head>
<body>
    <div class="container-fluid" id="backC">
    <table id = "header">
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
                <?= (isset($_SESSION["user"]) && $_SESSION["user"]["role"] == 2) ? "<a href = 'addProduct.php'><input type = 'button' name = 'addproduct' value = 'Add Product'></a>" : ""?>
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
<h1>Select Promotional Product</h1>
<form action = "" method = "post">
    <table id="ask">
        <tr>
            <td>Category : </td>
            <td><select name="category">
                    <option value = "computer">Computer</option>
                    <option value = "phone">Phone</option>
                    <option value = "television">Television</option>
                    <option value = "book-film">Book & Film</option>
                </select>
            </td>
        </tr>
        <tr>
            <td colspan="2"><input type = "submit" name = "select" value = "Show"></td>
        </tr>
    </table>
</form>
<?= $added == true ? "<h2>Product is added to promotional Products !<h2>" : "" ; ?>

<?php 
    if (isset($_POST["select"])) {
        extract($_POST);
        $sql = "select * from products where category = ?";
        $stmt = $db->prepare($sql);
        $stmt->execute([$category]);
        if ($stmt->rowCount() > 0) {
            echo "<form action = '' method = 'post'>";

            echo "<table id = 'products'>";
            echo "<tr id='theader' ><td></td><td>Brand</td><td>Title</td><td>Price</td><td>Rating</td><td>Promotional ?</td></tr>";
            foreach ($stmt as $pro) {
                //$pro = $stmt->fetch();
                echo "<tr>";
                echo "<td><img src = 'img/{$pro['pimg']}' width=150> </td>";
                echo "<td><a href ='detail.php?id={$pro['id']}'>{$pro['brand']}</td>";
                echo "<td>{$pro['title']}</td>";
                echo "<td>{$pro['price']}</td>";
                echo "<td>{$pro['rating']}</td>";
                echo "<td><input type = 'checkbox' name = 'id' value = '{$pro['id']}'></td>";
                echo "</tr>";
            }
            echo "<tr><td colspan = '6' style='text-align:center'><input type = 'submit' name = 'choose' value = 'Select'></td></tr>";
            echo "</table>";
            echo "</form>";
            
        } else {
            echo "<p>There arent any product!</p>";
        }
    }
?>
</body>
</html>
