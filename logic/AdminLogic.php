<?php
function adminLogin($dbh, $username, $password) {
    $password = md5($password);
    $sql = "SELECT UserName FROM admin WHERE UserName=:user AND Password=:pass";
    $query = $dbh->prepare($sql);
    $query->execute([':user' => $username, ':pass' => $password]);
    return $query->rowCount() > 0;
}