<?php
require_once 'bootstrap.php';


$username= request()->get("username");
$password= request()->get('password');

$user= findUserByUsername($username);


if(empty($user)){
   $session->getFlashBag()->add("error","This user does not exist");
   return redirect('../login.php');
}
if(!password_verify($password,$user["password"])){
    $session->getFlashBag()->add("error","Incorrect password");
   return redirect('../login.php');
}

storeDetails($user);



$session->getFlashBag()->add("success","Successfully logged-in");
return redirect('../index.php');