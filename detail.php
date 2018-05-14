
<?php
session_start();
if(isset($_GET["id"])){
    require_once './db.php';
    
    $added = false;
    $sql = "select * from products where id = ?";
    $stmt = $db->prepare($sql);
    $stmt->execute([$_GET["id"]]);
    if($stmt->rowCount() == 1){
        $pro = $stmt->fetch();
    }
    
    $products = [];
    if($_POST["addCart"]){
        if(empty($_SESSION["user"])){
            $error = true;
        }else{
            if(empty($_SESSION["pro"])){
                $pros [] = $pro;
                $_SESSION["pro"] = $pros;
                $added = true;
            }else{
                foreach($_SESSION["pro"] as $key => $value){
                    $products[$key] = $value;
                }
                $products[] = $pro;
                $_SESSION["pro"] = $products;
                $added = true;
            }

        }
    }
}else{
    header("Location: products.php");
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
        
       table{
            margin: 0px auto;
        }
        td{
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
        }
        #products td:hover{
            background: white;
            color: black;
        }
        #pro{
            width:100%;
            height:60px;
            border-collapse: collapse;
            margin-top: 30px;
        }
        #pro td{
            border:1px solid black;
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
    <table id = "pro">
        <tr>
            <td><?php echo "<img src = 'img/{$pro["pimg"]}' width = 175 >"; ?></td>
            <td>
                <table>
                    <tr>
                        <td>Category : </td>
                        <td><?= $pro["category"] ?> </td>
                    </tr>
                    <tr>
                        <td>Brand : </td>
                        <td><?= $pro["brand"] ?> </td>
                    </tr>
                    <tr>
                        <td>Tile : </td>
                        <td><?= $pro["title"] ?> </td>
                    </tr>
                    <tr>
                        <td>Price : </td>
                        <td><?= $pro["price"] ?> </td>
                    </tr>
                    <tr>
                        <td>Properties : </td>
                        <td><?= $pro["properties"] ?> </td>
                    </tr>
                    <tr>
                        <td>Rating : </td>
                        <td><?= $pro["rating"] ?> </td>
                    </tr>
                </table>
            </td>
        </tr>
        <form action = "" method = "post">
        <tr>
            <td colspan='2'><input type = "submit" name = "addCart" value = "Add To Cart"> </td>
        </tr>
        </form>
        <tr>
            <td><?= $error == true ? "<span>You must Log-In in order to buy product !</span>" : "" ?></td>
        </tr>
        <tr>
            <td><?= $added == true ? "<span>Product is added to your shopping cart !</span>" : "" ?></td>
        </tr>
    </table>
</body>
</html>
