<?php

function alterTriggerActive($ip){
    $db = $ip.':supermarket-bo';
    $username = 'SYSDBA';
    $password = 'masterkey';
    // Connect to database
    $dbh = ibase_connect($db, $username, $password);
    //echo var_dump($dbh);
    $stmt = 'alter trigger iesiri_poz_biu active';
    $sth = ibase_query($dbh, $stmt);
    $commitResult = ibase_commit ($dbh);
    if(!$sth || !$commitResult){
        echo "Erroare la baza de date sau calculator inchis";
    } else{
        return true; 
    }
    ibase_close($dbh);
}

// /alterTriggerActive("localhost");

function alterTriggerInActive($ip){
    $db = $ip.':supermarket-bo';
    $username = 'SYSDBA';
    $password = 'masterkey';
    // Connect to database
    $dbh = ibase_connect($db, $username, $password);
    $stmt = 'alter trigger iesiri_poz_biu inactive';
    $sth = ibase_query($dbh, $stmt);
    $commitResult = ibase_commit ($dbh);
    if(!$sth || !$commitResult){
        echo "Erroare la baza de date sau calculator inchis";
    } else{
        return true; 
    }
    ibase_close($dbh);
}

?>