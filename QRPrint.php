<?php
    require_once('./includes/db_conn.php');
    $db = new sqlDb();
    
    $AccessKey = $_GET["key"];
    $type = $_GET["type"];
    $rid = $_GET["rid"];
    $checkQ = "SELECT * FROM mlp_PlunkettLibrary.dbo.ApiAccess WHERE ApiPass = '$AccessKey'";
    $checkRes = $db->query_conn($checkQ);
    $returnArray = array();
    
    if ($db->num_rows($checkRes) > 0) {
        // this is valid
        $selQ = "SELECT * FROM mlp_PlunkettLibrary.dbo.SelectViewInventoryQRPrint WHERE Classroom_rid = 2";
        switch ($type) {
            case "SingleID":
                $selQ .= " AND Inv_rid = $rid";
                break;
            case "All":
                break;
            case "Unprinted":
                $selQ .= " AND PrintCount = 0";
                break;
            default:
                break;
        }        
        
        $respCount = 0;
        $respArray = array();        
        $selRes = $db->query_conn($selQ);
        while ($selRow = $db->fetch_array($selRes)) {
            $resp = array();
            $resp["rid"] = $selRow["Inv_rid"];
            $resp["title"] = $selRow["BookTitle"];
            $respArray[$respCount] = $resp;
    
            $respCount++;
        }
        $returnArray["data"] = $respArray;
        $ret = json_encode($returnArray);
        echo $ret;
    }
    else {
        echo "X";
    }
?>
