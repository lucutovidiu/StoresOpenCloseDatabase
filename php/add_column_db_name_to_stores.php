<?php
function populateStoresTableFirstTime()
{
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
ALTER TABLE STORES
ADD db_name varchar(100);
EOD;
    if ($conn->query($sql) === TRUE) {
        echo "column db_name created";
    } else {
        echo "column db_name Not created " . $conn->error;
    }
    $conn->query($sql);

    foreach ($csv as $value) {
        $store_short_name = $value[1];
        if ($store_short_name === "G40" || $store_short_name === "G42")
            $db_name = 'supermarket-bo-' . $store_short_name;
        else
            $db_name = 'supermarket-bo';
        $sql = "UPDATE STORES SET db_name='" . $db_name . "' WHERE store_short_name='" . $store_short_name . "'";
        if ($conn->query($sql) === TRUE) {
            echo "column db_name for store:" . $store_short_name . " updated : " . $db_name . "</br>";
        } else {
            echo "column db_name Not updated " . $conn->error;
        }
    }

    $conn->close();
}

populateStoresTableFirstTime();