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

        $sql = <<<'EOD'
CREATE TABLE STORES (
            store_id int AUTO_INCREMENT NOT NULL PRIMARY KEY,
            store_short_name varchar(15) UNIQUE NOT NULL,
            store_full_name varchar(35) UNIQUE NOT NULL,
            store_ip varchar(30) NOT NULL,
            store_status varchar(30) NOT NULL
        )
EOD;
        if ($conn->query($sql) === TRUE) {
            echo "Table STORES created";
        } else {
            echo "Table STORES Not created ".$conn->error;
        }
        $conn->query($sql);

        foreach($csv as $value){
            $storeID=substr($value[1],1);
            $sql = "INSERT INTO STORES (store_id, store_short_name, store_full_name, store_ip, store_status) VALUES (".$storeID.", '".$value[1]."', '".$value[0]."', '".$value[2]."', 'close')";

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