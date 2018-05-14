<?php
session_start();
if(empty($_SESSION["user"])){
    header("Location: main.php");
    exit;
}

if($_SESSION["user"]["role"] != 2){
    header("Location: main.php");
    exit;
}

if (isset($_POST["addPro"])) {
    require_once './db.php';
    extract($_POST);
    $proimg = $_FILES["pimg"]["name"];
    $sql = "insert into products (pimg, category, brand, title, price, properties) values (?, ?, ?, ?, ?, ?)";
    $stmt = $db->prepare($sql);
    $stmt->execute([$proimg, $category, $brand, $title, $price, $properties]);
    header("Location: products.php?category={$category}");
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
        
        #header{
            margin: 0px auto;
        }
        #header td{
            padding-left:120px;
        }

        #addPro{
            margin:50px auto;
        }
        #addPro td{
            padding:10px;
        }
        h1{
            text-align: center;
            margin: 40px auto;
        }
        span{
            font-style: italic;
            color: red;
        }
    </style>
</head>
<body>
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
    
    <h1>Add Product</h1>
    <form action = "" method = "post" enctype="multipart/form-data">
    <table id = "addPro">
        <tr>
            <td>Profile Img : </td>
            <td><input type = "file" name = "pimg">
            </td>
        </tr>
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
            <td>Brand : </td>
            <td><input type = "text" name = "brand">
            </td>
        </tr>
        <tr>
            <td>Title </td>
            <td><input type = "text" name = "title">
            </td>
        </tr>
        <tr>
            <td>Price </td>
            <td><input type = "text" name = "price">
            </td>
        </tr>
        <tr>
            <td>Properties: </td>
            <td><input type = "text" name = "properties">
            </td>
        </tr>
         <tr>
            <td colspan = 2><input type = "submit" name = "addPro" value = "Add Product"></td>
        </tr>
    </table>
    </form>
</body>
</html>
