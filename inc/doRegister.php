<?php 

require_once 'bootstrap.php';

$username= request()->get("username");
$password= request()->get('password');
$confirm_password= request()->get('confirm_password');

if($confirm_password!==$password){
    $session->getFlashBag()->add("error","Passwords do not match");
    return redirect('../register.php');
}

$user= findUserByUsername($username);

if(!empty($user)){
    $session->getFlashBag()->add("error","User already exists Please sign-in");
    return redirect('../login.php');
}

registerUser($username,$password);

$newUser=findUserByUsername($username);
storeDetails($newUser);


