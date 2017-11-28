<?php
    require_once('./includes/db_conn.php');
    $db = new sqlDb();
    
    $AccessKey = $_GET["key"];
    $StudentId = $_GET["student"];
    $InvId = $_GET["book"];
    $checkQ = "SELECT * FROM mlp_PlunkettLibrary.dbo.ApiAccess WHERE ApiPass = '$AccessKey'";
    $checkRes = $db->query_conn($checkQ);
    $returnArray = array();
    
    if ($db->num_rows($checkRes) > 0) {
        $resp = "";
        // this is valid
        // first lets see if this student has the book checked out
        $invLoanQ = "SELECT * FROM mlp_PlunkettLibrary.dbo.Loans WHERE Inventory_rid = $InvId AND CheckInDate IS NULL";
        $invLoanRes = $db->query_conn(($invLoanQ));
        if ($db->num_rows($invLoanRes) > 0) {
            // book is checked out
            $invLoanRow = $db->fetch_array($invLoanRes);
            $loanId = $invLoanRow["rid"];
            $outStudent = $invLoanRow["Student_rid"];
            if ($outStudent != $StudentId) {
                // this isnt the student who has the book checked out
                $resp = "This book is checked out by another student. Please see your teacher";
            }
            else {
                //this student can check the book in
                $checkinQ = "UPDATE mlp_PlunkettLibrary.dbo.Loans SET CheckInDate = GetDate(), Loanstatus = 'RETURNED' WHERE rid = $loanId";
                $db->query_conn($checkinQ);
                $invCheckinQ = "UPDATE mlp_PlunkettLibrary.dbo.Inventory SET InventoryStatus = 'IN' WHERE rid = $InvId";
                $db->query_conn($invCheckinQ);
                $resp = "You have checked your book back in";
            }
        }
        else {
            // book is not out. we can check it out
            $checkoutQ = "INSERT INTO mlp_PlunkettLibrary.dbo.Loans (LoanStatus,Student_rid,Inventory_rid) VALUES ('CHECKEDOUT',$StudentId,$InvId)";
            $db->query_conn($checkoutQ);
            $invCheckoutQ = "UPDATE mlp_PlunkettLibrary.dbo.Inventory SET InventoryStatus = 'OUT' WHERE rid = $InvId";
            $db->query_conn($invCheckoutQ);
            $resp = "You have checked this book out";
        }
        echo $resp;
    }
    else {
        echo "X";
    }
?>
