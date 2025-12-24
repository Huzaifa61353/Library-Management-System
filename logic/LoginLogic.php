<?php
function loginUser($dbh, $email, $password) {
    $password = md5($password);
    $sql = "SELECT StudentId, Status FROM tblstudents WHERE EmailId=:email AND Password=:password";
    $query = $dbh->prepare($sql);
    $query->execute([':email' => $email, ':password' => $password]);
    return $query->fetch(PDO::FETCH_OBJ);
}