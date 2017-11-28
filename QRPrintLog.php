<?php
    require_once('./includes/db_conn.php');
    $db = new sqlDb();
    
    $AccessKey = $_GET["key"];
    $checkQ = "SELECT * FROM mlp_PlunkettLibrary.dbo.ApiAccess WHERE ApiPass = '$AccessKey'";
    $checkRes = $db->query_conn($checkQ);
    $returnArray = array();
    
    if ($db->num_rows($checkRes) > 0) {
        // this is valid
        $rid = $_GET["rid"];
        $resp = "";        
        $updtQ = "INSERT INTO mlp_PlunkettLibrary.dbo.InventoryPrintLog";
        $selRes = $db->query_conn($updtQ);
        $resp = "OK";
    }
    else {
        echo "X";
    }
?>
