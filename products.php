<?php
require_once 'db.php';
session_start();
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
        .paging {
            text-align: center;
            padding-top: 30px;
            margin-bottom: 60px;
        }
        .paging a { margin-right: 20px; text-decoration: none; font-size: 16px; color: red;}
        .active { color: black; font-weight: bold;}
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
if (isset($_GET["category"])) {

    $category = $_GET["category"];
} else {
    $category = "phone";
}
$PER_PAGE = 5;

//Paging
$currentPage = isset($_GET['page']) ? $_GET['page'] : 1;
$currentPage = filter_var($currentPage, FILTER_VALIDATE_INT) === false ? 1 : $currentPage;
if ($currentPage < 0)
    $currentPage = 1;

$sql1 = "select count(*) from products where category = ?";
$stmt1 = $db->prepare($sql1);
$stmt1->execute([$category]);
$total = $stmt1->fetch();

$maxPage = ceil($total["count(*)"] / $PER_PAGE);
if ($currentPage > $maxPage)
    $currentPage = $maxPage;
$startIndex = ($currentPage - 1) * $PER_PAGE;


$sql = "select * from products where category = ? LIMIT $startIndex," . $PER_PAGE;
$stmt = $db->prepare($sql);
$stmt->execute([$category]);
if ($stmt->rowCount() > 0) {
    echo "<table id = 'products'>";
    echo "<tr id='theader' ><td></td><td>Brand</td><td>Title</td><td>Price</td><td>Rating</td></tr>";
    foreach ($stmt as $pro) {
        //$pro = $stmt->fetch();
        echo "<tr>";
        echo "<td><img src = 'img/{$pro['pimg']}' width=150> </td>";
        echo "<td><a href ='detail.php?id={$pro['id']}'>{$pro['brand']}</td>";
        echo "<td>{$pro['title']}</td>";
        echo "<td>{$pro['price']}</td>";
        echo "<td>{$pro['rating']}</td>";
        echo "</tr>";
    }
    echo "</table>";
} else {
    echo "<p>There arent any product!</p>";
}

//Numbering
if ($maxPage > 0) {
    echo "<div class = 'paging'>";
    if ($currentPage > 1) {
        echo "<a href='?category={$category}&page=" . ($currentPage - 1) . "'>Prev</a>";
    }
    for ($i = 1; $i <= $maxPage; $i++) {
        if ($currentPage == $i) {
            echo "<a class = 'active' href='?category={$category}&page=$i'>$i</a>";
        } else {
            echo "<a href = '?category={$category}&page=$i'>$i</a>";
        }
    }

    if ($currentPage < $maxPage) {
        echo "<a href = '?category={$category}&page=" . ($currentPage + 1) . "'>Next</a>";
    }
    echo "</div>";
}
?>
</body>
</html>



