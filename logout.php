<?php

require_once 'db.php' ;

$cookie_session_name = session_name() ;
setcookie($cookie_session_name, "", 1 , '/') ;
session_destroy();
header("Location: login.php");
exit;