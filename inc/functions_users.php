<?php

function createTasksTable(){
    global $db;
    try{
    $db->query("CREATE TABLE IF NOT EXISTS tasks( 
        id INTEGER PRIMARY KEY, 
        task TEXT    NOT NULL, 
        status TEXT NOT NULL, 
        user_id INTEGER,
        CONSTRAINT fk_user
        FOREIGN KEY (user_id)
        REFERENCES user(user_id)
        )");

    }catch(Exception $e){
      echo "Error creating Tasks table :".$e->getMessage();
    }
        
}

function createUsersTable(){
    global $db;
    try{
    $db->query("CREATE TABLE IF NOT EXISTS users( 
        id   INTEGER PRIMARY KEY, 
        username TEXT    NOT NULL, 
        password TEXT NOT NULL
        )");

    }catch(Exception $e){
      echo "Error creating Tasks table :".$e->getMessage();
    }
        
}


function registerUser($username,$password){
    global $db;
    $hashed=password_hash($password,PASSWORD_DEFAULT);
    try{
        $query="INSERT INTO users (username,password) VALUES (?,?)";

        $stmt= $db->prepare($query);
        $stmt->execute([$username,$hashed]);

    }catch(Exception $e){
        echo 'Error creating user '.$e->getMessage();
    }
}

function findUserByUsername($username){
    global $db;
    try{
    $query="SELECT * FROM users WHERE username=?";
    $stmt= $db->prepare($query);
    $stmt->execute([$username]);
    $result=$stmt->fetch();
    }
    catch(Exception $e){
        echo "error finind user name :<br>".$e->getMessage();
        
        return false;
    }
    return $result;
}

function storeDetails($signedInUser){

    global $session;

    $session->set("auth-userid",(int) $signedInUser["id"]);
    $session->set("username", $signedInUser["username"]);
}



function changePassword($currentPassword,$newPassword,$username){
    global $session;
    global $db;
    
    
    
    $new=password_hash($newPassword,PASSWORD_DEFAULT);

    try{
       $query="SELECT * FROM users WHERE username=?";
       $stmt=$db->prepare($query);
       $stmt->execute([$username]);
       $result=$stmt->fetch();
       $oldPassword=$result["password"];
       if(!password_verify($currentPassword,$oldPassword)){
          
        redirect("../account.php");
       
        $session->addFlashBag()->add("error","Incorrect Password");
        return false;
       }

    }catch(Exception $e){
        echo $e->getMessage();
       
        redirect("../account.php");
        $session->addFlashBag()->add("error","Incorrect Password");
        return false;
    }


    try{
        $query="UPDATE users SET password = ? WHERE username = ?";
        $stmt= $db->prepare($query);
        $stmt->execute([$new,$username]);
        
    }
    catch(Exception $e){
       echo "error updating password:".$e->getMessage();
      
       $session->getFlashBag()->add("error","Error changing password");
    redirect("../account.php");
    return false;
    }
    
    $session->getFlashBag()->add("success","Password successfully changed");
    redirect("../index.php");
    return true;
}