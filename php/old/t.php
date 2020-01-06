<?php
    //var_dump($_POST);
    $script="CONNECT firstdb.fdb USER 'SYSDBA' PASSWORD 'masterkey';\n";
    $script .= "SELECT * FROM sales_catalog;\n";
    $script .= "INSERT INTO sales_catalog VALUES('005','Aluminium Wok', 'Chinese wok used for stir fry dishes');\n";
    $script .= "exit;";
    $fp = fopen('./scripts/g2.bat', 'w');
    fwrite($fp, $script);
    fclose($fp);
?>