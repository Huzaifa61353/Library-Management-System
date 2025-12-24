<?php
use PHPUnit\Framework\TestCase;
require_once __DIR__ . '/../logic/PasswordLogic.php';

class PasswordLogicTest extends TestCase {
    private $dbh;
    protected function setUp(): void {
        $this->dbh = new PDO("mysql:host=localhost;dbname=library", "root", "");
        $this->dbh->beginTransaction();
        $this->dbh->query("INSERT INTO tblstudents(EmailId,Password) VALUES ('p@t.com','".md5('old')."')");
    }
    protected function tearDown(): void { $this->dbh->rollBack(); }

    public function testPassChangeSuccess() { $this->assertTrue(changePassword($this->dbh, 'p@t.com', 'old', 'new')); }
    public function testPassChangeWrongOld() { $this->assertFalse(changePassword($this->dbh, 'p@t.com', 'wrong', 'new')); }
    public function testPassChangeUserNotFound() { $this->assertFalse(changePassword($this->dbh, 'none@t.com', 'old', 'new')); }
    public function testPassChangeEmptyNew() { $this->assertFalse(changePassword($this->dbh, 'p@t.com', 'old', '')); }
    public function testPassChangeVerifyDb() {
        changePassword($this->dbh, 'p@t.com', 'old', 'secret');
        $pass = $this->dbh->query("SELECT Password FROM tblstudents WHERE EmailId='p@t.com'")->fetchColumn();
        $this->assertEquals(md5('secret'), $pass);
    }
}