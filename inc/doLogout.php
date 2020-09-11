<?php

require_once 'bootstrap.php';


// $session->remove("auth-userid");
// $session->remove("username");

$cookies= setAuthCookie("expired",1);
$session->getFlashBag()->add("sucess","Successfully logged-out");
 
return redirect('../index.php',["cookies"=>[$cookies]]);