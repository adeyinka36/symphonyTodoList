<?php
//task functions



function getTasks($where = null)
{
    global $session;
    global $db;

    $creator=$session->get("auth-userid");
    if($creator){
        
        $creatorsTasks=[];
        $query = "SELECT * FROM tasks ";
    if (!empty($where)) $query .= "WHERE $where";
    $query .= " ORDER BY id";
    try {
        $statement = $db->prepare($query);
        $statement->execute();
        $tasks = $statement->fetchAll();
    } catch (Exception $e) {
        echo "Error!: " . $e->getMessage() . "<br />";
        return false;
        die();
    }
    foreach($tasks as $t){
        if($t["id"]==$creator){
            
            array_push($creatorsTasks,$t);
        }

    }
    return $creatorsTasks;
    
    }

 else{
    $query = "SELECT * FROM tasks ";
    if (!empty($where)) $query .= "WHERE $where";
    $query .= " ORDER BY id";
    try {
        $statement = $db->prepare($query);
        $statement->execute();
        $tasks = $statement->fetchAll();
    } catch (Exception $e) {
        echo "Error!: " . $e->getMessage() . "<br />";
        return false;
    }
    return $tasks;
}
}
function getIncompleteTasks()
{
    return getTasks('status=0');
}
function getCompleteTasks()
{
    return getTasks('status=1');
}
function getTask($task_id)
{
    global $db;

    try {
        $statement = $db->prepare('SELECT id, task, status FROM tasks WHERE id=:id');
        $statement->bindParam('id', $task_id);
        $statement->execute();
        $task = $statement->fetch();
    } catch (Exception $e) {
        echo "Error!: " . $e->getMessage() . "<br />";
        return false;
    }
    return $task;
}
function createTask($data)
{
    global $db;
    global $session;

    try {
        $user_id=$session->get("auth-userid");
        $statement = $db->prepare('INSERT INTO tasks (task, status, user_id) VALUES (:task, :status, :user_id)');
        $statement->bindParam('task', $data['task']);
        $statement->bindParam('status', $data['status']);
        $statement->bindParam('user_id', $user_id);
        $statement->execute();
    } catch (Exception $e) {
        echo "Error!: " . $e->getMessage() . "<br />";
        die();
        
        return false;
    }
    return getTask($db->lastInsertId());
}
function updateTask($data)
{
    global $db;

    try {
        getTask($data['task_id']);
        $statement = $db->prepare('UPDATE tasks SET task=:task, status=:status WHERE id=:id');
        $statement->bindParam('task', $data['task']);
        $statement->bindParam('status', $data['status']);
        $statement->bindParam('id', $data['task_id']);
        $statement->execute();
    } catch (Exception $e) {
        echo "Error!: " . $e->getMessage() . "<br />";
        return false;
    }
    return getTask($data['task_id']);
}
function updateStatus($data)
{
    global $db;

    try {
        getTask($data['task_id']);
        $statement = $db->prepare('UPDATE tasks SET status=:status WHERE id=:id');
        $statement->bindParam('status', $data['status']);
        $statement->bindParam('id', $data['task_id']);
        $statement->execute();
    } catch (Exception $e) {
        echo "Error!: " . $e->getMessage() . "<br />";
        return false;
    }
    return getTask($data['task_id']);
}
function deleteTask($task_id)
{
    global $db;

    try {
        getTask($task_id);
        $statement = $db->prepare('DELETE FROM tasks WHERE id=:id');
        $statement->bindParam('id', $task_id);
        $statement->execute();
    } catch (Exception $e) {
        echo "Error!: " . $e->getMessage() . "<br />";
        return false;
    }
    return true;
}
