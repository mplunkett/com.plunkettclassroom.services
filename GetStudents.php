<?php
    require_once('./includes/db_conn.php');
    $db = new sqlDb();
    
    $AccessKey = $_GET["key"];
    $checkQ = "SELECT * FROM mlp_PlunkettLibrary.dbo.ApiAccess WHERE ApiPass = '$AccessKey'";
    $checkRes = $db->query_conn($checkQ);
    $returnArray = array();
    
    if ($db->num_rows($checkRes) > 0) {
        // this is valid
        $respCount = 0;
        $respArray = array();
        $selQ = "SELECT * FROM mlp_PlunkettLibrary.dbo.SelectViewStudentMobileApp ORDER BY StudentName";
        $selRes = $db->query_conn($selQ);
        while ($selRow = $db->fetch_array($selRes)) {
            $resp = array();
            $resp["rid"] = $selRow["rid"];
            $resp["name"] = $selRow["StudentName"];
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
