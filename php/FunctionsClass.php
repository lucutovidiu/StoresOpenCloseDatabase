<?php
include './FireBirdConnections.php';
class LocalSqlConnection{
    public $servername = "localhost";
    public $username = "root";
    public $password = "";
    public $dbname = "store_data";
    public $email_subject="Avizul a fost aprobat pentru magazinul";
    public $conn;

    function getConnection(){
        // Create connection
        $this->conn = new mysqli($this->servername, $this->username, $this->password, $this->dbname);
        // Check connection
        if ($this->conn->connect_error) {
            $err="Error: connection failed: " . $this->conn->connect_error;
            die($err. "<br>");
            return $err;
        }
    }

    function closeConection(){
        $this->conn->close();
    }

    public function getIDFromStoresTable($store_short_name,$store_ip){
        $log_id="select store_id from stores where store_short_name='".$store_short_name."' and store_ip='".$store_ip."'";
        $result = $this->conn->query($log_id);
        $row = $result->fetch_assoc();
        $log_id = $row["store_id"];
        return $log_id;
    }

    function logUpdateToLogsTable($store_short_name,$store_ip,$sql,$msg){
        $log_id = $this->getIDFromStoresTable($store_short_name,$store_ip);
        $date = new DateTime();
        $date = $date->format('Y-m-d H:i:sP');
        $sql = str_replace("'", "", $sql);
        $msg = str_replace("'", "", $msg);
        $log_sql ="INSERT INTO LOGS (log_date, log_sql_action, log_result, store_id) VALUES ( '".$date."',  '.$sql.', '".$msg."', ".$log_id.")";
        echo $log_sql;
        echo $this->conn->query($log_sql);
    }

    function logUpdateToLogsTableByID($store_short_name,$store_id,$sql,$msg){
        $log_id = $store_id;
        $date = new DateTime();
        $date = $date->format('Y-m-d H:i:sP');
        $sql = str_replace("'", "", $sql);
        $msg = str_replace("'", "", $msg);
        $log_sql ="INSERT INTO LOGS (log_date, log_sql_action, log_result, store_id) VALUES ( '".$date."',  '.$sql.', '".$msg."', ".$log_id.")";
        //echo $log_sql;
        echo $this->conn->query($log_sql);
    }

    public function insertDataIntoTableStores($store_short_name,$store_full_name,$store_ip,$store_status){
        $this->getConnection();

        $sql = "INSERT INTO STORES (store_short_name, store_full_name, store_ip, store_status) VALUES ('".$store_short_name."', '".$store_full_name."', '".$store_ip."','".$store_status."');";

        if ($this->conn->query($sql) === TRUE) {
            $msg = "Insert : Record : Store short name - ".$store_short_name." Store full name - ".$store_full_name." Store IP: ".$store_ip."  has added to Stores table<br>";
            echo $msg. "<br>";
            //return $msg. "<br>";
        } else {
            $msg = "Error: " . $sql . "<br>" . $this->conn->error;
            echo $msg. "<br>";
            //return $msg. "<br>";
        }

        $this->logUpdateToLogsTable($store_short_name,$store_ip,$sql,$msg);

        $this->closeConection();
    }

    public function updateStatusIntoTableStores($store_short_name,$store_ip,$store_status){
        $this->getConnection();

        $sql = "UPDATE stores SET store_status = '".$store_status."' WHERE store_short_name='".$store_short_name."' and store_ip='".$store_ip."'";

        $msg ="";
        if ($this->conn->query($sql) === TRUE) {
            $msg = "Update : Record : Store short name - ".$store_short_name." Store IP: ".$store_ip."  has Updated with new Status<br>";
            echo $msg. "<br>";
            //return $msg. "<br>";
        } else {
            $msg = "Error: " . $sql . "<br>" . $this->conn->error;
            echo $msg. "<br>";
            //return $msg. "<br>";
        } 

        $this->logUpdateToLogsTable($store_short_name,$store_ip,$sql,$msg);
        
        $this->closeConection();
    }

    public function getIpAddressByStoreID($store_id){
        $sql  = "select store_ip from stores where store_id='".$store_id."'";
        $result = $this->conn->query($sql);
        $row = $result->fetch_assoc();
        $ip = $row["store_ip"];
        return $ip;
    }

    public function getDbNameByStoreId($store_id){
        $sql  = "select db_name from stores where store_id='".$store_id."'";
        $result = $this->conn->query($sql);
        $row = $result->fetch_assoc();
        $db_name = $row["db_name"];
        return $db_name;
    }

    public function getDbNameUrlFromStoreId($store_id){
        $sql  = "select store_ip, db_name from stores where store_id='".$store_id."'";
        $result = $this->conn->query($sql);
        $row = $result->fetch_assoc();
        $store_ip = $row["store_ip"];
        $db_name = $row["db_name"];
        return $store_ip.":".$db_name;
    }

    public function updateStatusIntoTableStoresByID($store_short_name,$store_id,$store_status){
        $this->getConnection();
        $db_con_url = $this->getDbNameUrlFromStoreId($store_id);

        if($store_status==="open"){
            if(!alterTriggerInActive($db_con_url)){
                $sql = "alter trigger iesiri_poz_biu inactive";
                $this->logUpdateToLogsTableByID($store_short_name,$store_id,$sql,"firebase computer closed or database closed");
                $this->closeConection();
                return "Calculator la distanta Inchis sau problema la baza de date!, Informati IT";
                exit("Calculator la distanta Inchis sau problema la baza de date!, Informati IT");  
            }else{
                $sql = "alter trigger iesiri_poz_biu inactive";
                $this->logUpdateToLogsTableByID($store_short_name,$store_id,$sql,"Success");
            }
        }elseif($store_status==="close"){
            if(!alterTriggerActive($db_con_url)){
                $sql = "alter trigger iesiri_poz_biu active";
                $this->logUpdateToLogsTableByID($store_short_name,$store_id,$sql,"firebase computer closed or database closed");
                $this->closeConection();
                return "Calculator la distanta Inchis sau problema la baza de date!, Informati IT";
                exit("Calculator la distanta Inchis sau problema la baza de date!, Informati IT");  
            }else{
                $sql = "alter trigger iesiri_poz_biu active";
                $this->logUpdateToLogsTableByID($store_short_name,$store_id,$sql,"Success");
            }
        }


        $sql = "UPDATE stores SET store_status = '".$store_status."'  WHERE store_id='".$store_id."'";

        $msg ="";
        if ($this->conn->query($sql) === TRUE) {
            $msg = "Update : Record : Store short name - ".$store_short_name." has Updated with new Status<br>";
            //echo $msg. "<br>";
            //return $msg. "<br>";
        } else {
            $msg = "Error: " . $sql . "<br>" . $this->conn->error;
            //echo $msg. "<br>";
            //return $msg. "<br>";
        } 

        $this->logUpdateToLogsTableByID($store_short_name,$store_id,$sql,$msg);
        
        $this->closeConection();
    }

    public function getLogsAsCSV(){
        $this->getConnection();

        $fp = fopen('../backend-files/all_logs.csv', 'w');
        
        $sql = "select l.log_date, s.store_full_name, s.store_ip, s.store_status, l.log_sql_action, l.log_result from logs l,stores s where l.store_id = s.store_id";
        $result = $this->conn->query($sql);
        $output = "<table> <tr> <th>Log Date</th><th>Store Full Name</th><th>Store IP</th><th>Store Status</th><th>Log Sql Action</th><th>Log Result</th></tr>";
        $csvOutput = "Log Date,Store Full Name,Store IP,Store Status,Log Sql Action,Log Result\n";
        while($row = $result->fetch_assoc()){
            $output .="<tr>";

            $output .="<td>".$row['log_date']."</td>";
            $output .="<td>".$row['store_full_name']."</td>";
            $output .="<td>".$row['store_ip']."</td>";
            $output .="<td>".$row['store_status']."</td>";
            $output .="<td>".$row['log_sql_action']."</td>";
            $output .="<td>".$row['log_result']."</td>";

            $output .="</tr>";

            $csvOutput .=  str_replace(",", "",$row['log_date']).",".str_replace(",", "",$row['store_full_name']).",".str_replace(",", "",$row['store_ip']).",".str_replace(",", "",$row['store_status']).",".str_replace(",", "",$row['log_sql_action']).",".str_replace(",", "",$row['log_result'])."\n";
        }

        $output .="</table>";

        echo $output;

        fwrite($fp, $csvOutput);
        fclose($fp);
        $this->closeConection();
    }

    public function getOnScreenHTMLLogs(){
        $this->getConnection();
        
        $sql = "select l.log_id,l.log_date, s.store_full_name, s.store_ip, l.log_sql_action, l.log_result from logs l,stores s where l.store_id = s.store_id ORDER BY l.log_id desc";
        $result = $this->conn->query($sql);
        $output = "<table border='1'> <tr> <th>Log ID</th><th>Log Date</th><th>Store Full Name</th><th>Store IP</th><th>Log Sql Action</th><th>Log Result</th></tr>";
        $csvOutput = "Log ID,Log Date,Store Full Name,Store IP,Log Sql Action,Log Result\n";
        while($row = $result->fetch_assoc()){
            $output .="<tr>";

            $output .="<td>".$row['log_id']."</td>";
            $output .="<td>".$row['log_date']."</td>";
            $output .="<td>".$row['store_full_name']."</td>";
            $output .="<td>".$row['store_ip']."</td>";
            $output .="<td>".$row['log_sql_action']."</td>";
            $output .="<td>".$row['log_result']."</td>";

            $output .="</tr>";

            $csvOutput .=  $row['log_id'].",".str_replace(",", "",$row['log_date']).",".str_replace(",", "",$row['store_full_name']).",".str_replace(",", "",$row['store_ip']).",".str_replace(",", "",$row['log_sql_action']).",".str_replace(",", "",$row['log_result'])."\n";
        }

        $output .="</table>";

        echo $output;

        $this->closeConection();
    }

    // <div class='storeListItem'>
    //     <span class="storeListItem-name">Magazin G3</span>
    //     <button class="storeListItem-btn" id="G3_open" data-status="open" data-magazin="G3"
    //         data-store-id="1">Deschide Baza de Date</button>
    // </div>

    public function Get_Buttons_Lista_Magazine(){

        $this->getConnection();

        $sql="select * from stores order by store_id asc";
        
        $outputBtnClose = "";
        $result = $this->conn->query($sql);
        $times = 0;

        while($row = $result->fetch_assoc()){
            
            $store_id =$row['store_id'];
            $store_short_name =$row['store_short_name'];
            $store_full_name =$row['store_full_name'];
            $store_ip =$row['store_ip'];
            $store_status =$row['store_status'];
            if($store_status === "close")
            $outputBtnClose .= "<div class='storeListItem'><span class='storeListItem-name'>".$store_full_name."</span><button class='storeListItem-btn' id='".$store_short_name
                                ."_open' data-status='open' data-magazin='".$store_short_name."' data-store-id='".$store_id."'>Deblocheaza Tranzactii</button></div>";
    
            } 

        return $outputBtnClose;

        $this->closeConection();
    }

    public function Get_Buttons_Lista_Dechisa(){
        $this->getConnection();

        $sql="select * from stores order by store_id asc";
        $outputBtnOpen = "";
        $result = $this->conn->query($sql);
        $times = 0;

        while($row = $result->fetch_assoc()){
            
            $store_id =$row['store_id'];
            $store_short_name =$row['store_short_name'];
            $store_full_name =$row['store_full_name'];
            $store_ip =$row['store_ip'];
            $store_status =$row['store_status'];
            if($store_status === "open")
            $outputBtnOpen .= "<div class='storeListItem'><span class='storeListItem-name'>".$store_full_name."</span><button class='storeListItem-btn storeListItem-btn-red' id='".$store_short_name
                            ."_close' data-status='close' data-magazin='".$store_short_name."' data-store-id='".$store_id."'>Blocheaza Tranzactii</button>"
                            ."<a class='email-send storeListItem-btn' href='mailto:".$store_short_name."@unicarm.ro?subject=".$this->email_subject." - ".$store_short_name."'>Trimite Email</a></div>";
    
            } 

        return $outputBtnOpen;

        $this->closeConection();
    }

}

?>