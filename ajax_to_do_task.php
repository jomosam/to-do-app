<?php
/*
@description Page for ajax to add,update,delete
@author Jomon
@date   2-5-2016
*/
$servername = "localhost";
$username = "root";
$password = "123456";
$dbname = "users";
extract($_GET);
    try {
            $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
            // set the PDO error mode to exception
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }
    catch(PDOException $e)
    {
    echo $sql . "<br>" . $e->getMessage();
    }

    switch($action)
    {
        //function to add data
    	case 'add':
    	{
    		$sql  = "INSERT INTO `to_do` (`title`) VALUES('".$title."')";
    		$conn->exec($sql);
    		echo  $conn->lastInsertId();
    		exit;
            break;		
        }
        //functio to fetch data
        case 'fetch':
        {
            $sql    = $conn->prepare('SELECT id,title,status as done from to_do');
            $sql->execute();
            $result = $sql->fetchAll(PDO::FETCH_ASSOC);
            echo isset($result)&&is_array($result)&&count($result)>0?json_encode($result):'no_records';
            exit;
            break;
        }
        //function to delete
        case 'delete':
        {
            $sql    = "DELETE FROM to_do WHERE id={$_GET['id']}";
            $conn->exec($sql);
            break;
        }
        //function to update the status
        case 'update':
        {
            $id     =  $_GET['id'];
            $sql    =  "UPDATE to_do SET `status`=IF(`status`='true', 'false', 'true') WHERE id=$id";  
            $stmt   = $conn->prepare($sql);
            $stmt->execute();
            break;
        }
        //function to delete record which are done
        case 'delete_all':
        {
            $sql    = "DELETE FROM to_do WHERE status='true'";
            $conn->exec($sql);
            break;
        }
    }
$conn = null;
?>