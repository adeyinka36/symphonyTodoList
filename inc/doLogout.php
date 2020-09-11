<?php

require_once 'bootstrap.php';


$session->remove("auth-userid");
$session->remove("username");

$session->getFlashBag()->add("sucess","Successfully logged-out");
 
return redirect('../index.php');