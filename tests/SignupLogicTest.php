<?php
use PHPUnit\Framework\TestCase;
require_once __DIR__ . '/../logic/SignupLogic.php';

class SignupLogicTest extends TestCase {
    private $dbh;
    protected function setUp(): void {
        $this->dbh = new PDO("mysql:host=localhost;dbname=library", "root", "");
        $this->dbh->beginTransaction();
    }
    protected function tearDown(): void { $this->dbh->rollBack(); }

    public function testSignupSuccess() {
        $this->assertTrue(registerStudent($this->dbh, rand(1000,9999), "Test", "03001234567", "t".rand()."@t.com", "123456"));
    }
    public function testSignupEmptyName() {
        $this->assertFalse(registerStudent($this->dbh, 101, "", "0300", "e@t.com", "123456"));
    }
    public function testSignupShortPassword() {
        $this->assertFalse(registerStudent($this->dbh, 102, "User", "0300", "e2@t.com", "1"));
    }
    public function testSignupInvalidMobile() {
        $this->assertFalse(registerStudent($this->dbh, 103, "User", "abc", "e3@t.com", "123456"));
    }
    public function testSignupDuplicateEmail() {
        registerStudent($this->dbh, 104, "User", "0300", "dup@t.com", "123456");
        $this->expectException(PDOException::class);
        registerStudent($this->dbh, 105, "User", "0300", "dup@t.com", "123456");
    }
}