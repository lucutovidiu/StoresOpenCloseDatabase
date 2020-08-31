<?php

function alterTriggerActive($ip,$store_short_name){
    if($store_short_name === "G40" || $store_short_name === "G42")
        $db = $ip.':supermarket-bo-'.$store_short_name;
    else
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
        return false;
    } else{
        return true; 
    }
    ibase_close($dbh);
}

//echo alterTriggerActive("192.168.13.20","G3");

function alterTriggerInActive($ip,$store_short_name){
    if($store_short_name === "G40" || $store_short_name === "G42")
        $db = $ip.':supermarket-bo-'.$store_short_name;
    else
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
        return false;
    } else{
        return true; 
    }
    ibase_close($dbh);
}
//echo alterTriggerActive("192.168.8.21");
?>