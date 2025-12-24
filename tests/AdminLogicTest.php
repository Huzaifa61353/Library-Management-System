<?php
use PHPUnit\Framework\TestCase;
require_once __DIR__ . '/../logic/AdminLogic.php';

class AdminLogicTest extends TestCase {
    private $dbh;
    protected function setUp(): void {
        $this->dbh = new PDO("mysql:host=localhost;dbname=library", "root", "");
        $this->dbh->beginTransaction();
        $this->dbh->query("INSERT INTO admin(UserName,Password) VALUES ('admin','".md5('admin')."')");
    }
    protected function tearDown(): void { $this->dbh->rollBack(); }

    public function testAdminSuccess() { $this->assertTrue(adminLogin($this->dbh, 'admin', 'admin')); }
    public function testAdminWrongPass() { $this->assertFalse(adminLogin($this->dbh, 'admin', 'wrong')); }
    public function testAdminWrongUser() { $this->assertFalse(adminLogin($this->dbh, 'fake', 'admin')); }
    public function testAdminEmptyFields() { $this->assertFalse(adminLogin($this->dbh, '', '')); }
    public function testAdminCaseSensitivity() { $this->assertTrue(adminLogin($this->dbh, 'ADMIN', 'admin')); }
}