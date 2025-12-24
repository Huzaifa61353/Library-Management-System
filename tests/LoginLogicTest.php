<?php
use PHPUnit\Framework\TestCase;
require_once __DIR__ . '/../logic/LoginLogic.php';

class LoginLogicTest extends TestCase {
    private $dbh;
    protected function setUp(): void {
        $this->dbh = new PDO("mysql:host=localhost;dbname=library", "root", "");
        $this->dbh->beginTransaction();
        $this->dbh->query("INSERT INTO tblstudents(StudentId,FullName,EmailId,Password,Status) VALUES ('S1','Test','log@t.com','".md5('123')."', 1)");
    }
    protected function tearDown(): void { $this->dbh->rollBack(); }

    public function testLoginSuccess() { $this->assertIsObject(loginUser($this->dbh, 'log@t.com', '123')); }
    public function testLoginWrongPass() { $this->assertFalse(loginUser($this->dbh, 'log@t.com', 'wrong')); }
    public function testLoginBlockedUser() {
        $this->dbh->query("UPDATE tblstudents SET Status=0 WHERE StudentId='S1'");
        $user = loginUser($this->dbh, 'log@t.com', '123');
        $this->assertEquals(0, $user->Status);
    }
    public function testLoginInvalidEmail() { $this->assertFalse(loginUser($this->dbh, 'no@t.com', '123')); }
    public function testLoginEmptyFields() { $this->assertFalse(loginUser($this->dbh, '', '')); }
}