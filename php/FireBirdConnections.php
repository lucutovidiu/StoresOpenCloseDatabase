<?php

function alterTriggerActive($db_con_url){
    $username = 'SYSDBA';
    $password = 'masterkey';
    // Connect to database
    $dbh = ibase_connect($db_con_url, $username, $password);
    //echo var_dump($dbh);
    $stmt = 'alter trigger iesiri_poz_biu active';
    $sth = ibase_query($dbh, $stmt);
    $commitResult = ibase_commit ($dbh);
    if(!$sth || !$commitResult){
        echo "Erroare la baza de date sau calculator inchis";
        return false;
    } else{
        return true; 
    }
    ibase_close($dbh);
}

//echo alterTriggerActive("192.168.13.20","G3");

function alterTriggerInActive($db_con_url){
    $username = 'SYSDBA';
    $password = 'masterkey';
    // Connect to database
    $dbh = ibase_connect($db_con_url, $username, $password);
    $stmt = 'alter trigger iesiri_poz_biu inactive';
    $sth = ibase_query($dbh, $stmt);
    $commitResult = ibase_commit ($dbh);
    if(!$sth || !$commitResult){
        echo "Erroare la baza de date sau calculator inchis";
        return false;
    } else{
        return true; 
    }
    ibase_close($dbh);
}
//echo alterTriggerActive("192.168.8.21");
?>