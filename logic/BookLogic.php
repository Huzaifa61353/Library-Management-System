<?php
function issueBookLogic($dbh, $sid, $bid) {
    if(empty($sid) || empty($bid)) return false;
    
    // Check stock
    $stmt = $dbh->prepare("SELECT Copies, IssuedCopies FROM tblbooks WHERE id = :bid");
    $stmt->execute([':bid' => $bid]);
    $book = $stmt->fetch(PDO::FETCH_OBJ);
    if (!$book || ($book->IssuedCopies >= $book->Copies)) return false;

    $sql = "INSERT INTO tblissuedbookdetails(StudentID, BookId) VALUES(:s, :b)";
    $query = $dbh->prepare($sql);
    if($query->execute([':s' => $sid, ':b' => $bid])) {
        $upd = $dbh->prepare("UPDATE tblbooks SET IssuedCopies = IssuedCopies + 1 WHERE id = :b");
        return $upd->execute([':b' => $bid]);
    }
    return false;
}