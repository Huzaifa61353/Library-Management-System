<?php
function changePassword($dbh, $email, $old, $new) {
    if(empty($new)) return false;
    $query = $dbh->prepare("SELECT Password FROM tblstudents WHERE EmailId=:e AND Password=:p");
    $query->execute([':e' => $email, ':p' => md5($old)]);
    
    if($query->rowCount() > 0) {
        $upd = $dbh->prepare("UPDATE tblstudents SET Password=:n WHERE EmailId=:e");
        return $upd->execute([':n' => md5($new), ':e' => $email]);
    }
    return false;
}