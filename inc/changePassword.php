<?php

require_once 'bootstrap.php';

$password= request()->get('current_password');
$newPassword= request()->get('password');
$confirm= request()->get('confirm_password');

if($confirm!==$newPassword){
 
    $session->getFlashBag()->add("error","Passwords do not match");
    return redirect('../register.php');
}

$username= revealCookie("username");

changePassword($password,$newPassword,$username);