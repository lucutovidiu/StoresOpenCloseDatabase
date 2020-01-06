<?php
    /*
    
    */
    function populateStoresTableFirstTime(){
        $csv = array_map('str_getcsv', file('../csvMagazine/magazine.csv'));

        $servername = "localhost";
        $username = "root";
        $password = "";
        $dbname = "store_data";

        // Create connection
        $conn = new mysqli($servername, $username, $password, $dbname);
        // Check connection
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        } 
        
        foreach($csv as $value){

            $sql = "INSERT INTO STORES (store_short_name, store_full_name, store_ip, store_status) VALUES ('".$value[1]."', '".$value[0]."', '".$value[2]."', 'close');";

            if ($conn->query($sql) === TRUE) {
                echo "Record : Store short name - ".$value[1]." Store full name - ".$value[0]." Store IP: ".$value[2]."  has added to Stores table<br>";
            } else {
                echo "Error: " . $sql . "<br>" . $conn->error;
            }
        }

        $conn->close();
    }
    populateStoresTableFirstTime();

    function populateFakeLog(){
        $servername = "localhost";
        $username = "root";
        $password = "";
        $dbname = "store_data";
        // Create connection
        $conn = new mysqli($servername, $username, $password, $dbname);
        // Check connection
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        } 

        $date = new DateTime();
        $date = $date->format('Y-m-d H:i:sP');

        $comand="isql -user sysdba -password masterkey localhost:supermarket-bo";
        
        $sql = "INSERT INTO LOGS (log_date,  log_sql_action, log_result,    store_id) VALUES ( '".$date."',  '.$comand.', 'ok', 1);";

        if ($conn->query($sql) === TRUE) {
            echo "Record has added to Logs table<br>";
        } else {
            echo "Error: " . $sql . "<br>" . $conn->error;
        }

        $conn->close();
    }
    //populateFakeLog();
?>