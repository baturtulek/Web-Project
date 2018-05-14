<?php
session_start();
require_once './db.php';

if (!empty($_SESSION["user"])) {
    header("Location: main.php");
    exit;
}
if (isset($_POST["saveBtn"])) {

    //Sanitization
    $sanitizied = [];
    foreach ($_POST as $key => $value) {
        $sanitizied[$key] = filter_var($value, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    }
    extract($sanitizied);

    $error = [];

    //Validation
    if (strlen(trim($fullname)) === 0) {
        $error [] = "name";
    }
    if (filter_var($mail, FILTER_VALIDATE_EMAIL) === false) {
        $error [] = "mail";
    }
    if (strlen(trim($address)) === 0) {
        $error [] = "address";
    }

    if ((strlen(trim($password1)) === 0) && (strlen(trim($password2)) === 0) && ($password1 != $password2)) {
        $error [] = "password";
    }

    //If mail exists
    $sql = "select * from users where mail = ?";
    $stmt = $db->prepare($sql);
    $stmt->execute([$_POST["mail"]]);
    if ($stmt->rowCount() == 1) {
        $error [] = "exists";
    }

    if (empty($error)) {

        $role = 1; //Role for customer
        $encPass = password_hash($password1, PASSWORD_BCRYPT);
        $sql = "insert into users (fname, mail, address, password, role) values (?, ?, ?, ?, ?)";
        $stmt = $db->prepare($sql);
        $stmt->execute([$fullname, $mail, $address, $encPass, $role]);

        $sql = "select * from users where mail = ?";
        $stmt = $db->prepare($sql);
        $stmt->execute([$_POST["mail"]]);
        $user = $stmt->fetch();
        $_SESSION["user"] = $user;
        header("Location: main.php");
        exit;
    }
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
        
        #register{
            margin:50px auto;
        }
        #register td{
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

    <h1>Register</h1>
    <form action = "" method = "post">
    <table id = "register">
        <tr>
            <td>FullName : </td>
            <td><input type = "text" name = "fullname"
                       value = <?= isset($error) ? $sanitizied["fullname"] : "" ?>>
                       <?= isset($error) && in_array("fullname", $error) ? "<span>Name can not be empty !</span>" : "" ?>
            </td>
        </tr>
         <tr>
            <td>E-Mail : </td>
            <td><input type = "text" name = "mail"
                       value = <?= isset($error) ? $sanitizied["mail"] : "" ?>>
                    <?= isset($error) && in_array("mail", $error) ? "<span>Please enter correct mail address !</span>" : "" ?>

            </td>
        </tr>
        <tr>
            <td>Password : </td>
            <td><input type = "password" name = "password1">
                <?= isset($error) && in_array("password", $error) ? "<span>Passwords doesnt match!</span>" : ""?>
            </td>
        </tr>
        <tr>
            <td>Re-enter Password : </td>
            <td><input type = "password" name = "password2">
            </td>
        </tr>
        <tr>
            <td>Cargo Address : </td>
            <td><input type = "text" name = "address"
                       value = <?= isset($error) ? $sanitizied["address"] : "" ?>>
                    <?= isset($error) && in_array("address", $error) ? "<span>Address can not be empty !</span>" : "" ?>

            </td>
        </tr>
         <tr>
            <td colspan = "2" style = "text-align:center" ><input type = "submit" name = "saveBtn" value = "Register"></td>
        </tr>
        <tr>
            <td colspan = "2" style = "text-align:center" ><a href = "login.php">Do you have an account ?</a></td>
        </tr>
        <tr>
            <td colspan = 2 style = "text-align:center" > <?= isset($error) && in_array("exists", $error) ? "<span>Mail address already used !</span>" : "" ?></td>
        </tr>
    </table>
    </form>
</body>
</html>
