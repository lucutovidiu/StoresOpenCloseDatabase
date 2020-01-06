<?php
include './FunctionsClass.php';

$post = $_POST;

if(isset($post["status"])){
    if($post["status"]==="Get_Buttons_Lista_Magazine"){
        $result = new LocalSqlConnection();
        echo $result->Get_Buttons_Lista_Magazine();
        $result = null;
    }
    
    if($post["status"]==="Get_Buttons_Lista_Dechisa"){
        $result = new LocalSqlConnection();
        echo $result->Get_Buttons_Lista_Dechisa();
        $result = null;
    }
    
    if($post["status"]==="Update_Store_Status"){
        $result = new LocalSqlConnection();
        
        echo $result->updateStatusIntoTableStoresByID($post['store'],$post['data_store_id'],$post['data_status']);
        $result = null;
    }
}

if(isset($_GET["status"])){
    $get = trim(htmlspecialchars($_GET["status"]));
    if($get==="GET_LOGS_DATA"){
        $result = new LocalSqlConnection();
        echo $result->getOnScreenHTMLLogs();
        $result = null;
    }
}



?>