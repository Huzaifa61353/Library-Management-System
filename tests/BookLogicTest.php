<?php
use PHPUnit\Framework\TestCase;
require_once __DIR__ . '/../logic/BookLogic.php';

class BookLogicTest extends TestCase {
    private $dbh;
    protected function setUp(): void {
        $this->dbh = new PDO("mysql:host=localhost;dbname=library", "root", "");
        $this->dbh->beginTransaction();
        $this->dbh->query("INSERT INTO tblbooks(id,Copies,IssuedCopies) VALUES (99, 5, 0)");
    }
    protected function tearDown(): void { $this->dbh->rollBack(); }

    public function testIssueSuccess() { $this->assertTrue(issueBookLogic($this->dbh, 'S1', 99)); }
    public function testIssueInvalidBook() { $this->assertFalse(issueBookLogic($this->dbh, 'S1', 888)); }
    public function testIssueIncrementsCount() {
        issueBookLogic($this->dbh, 'S1', 99);
        $count = $this->dbh->query("SELECT IssuedCopies FROM tblbooks WHERE id=99")->fetchColumn();
        $this->assertEquals(1, $count);
    }
    public function testIssueOutStack() {
        $this->dbh->query("UPDATE tblbooks SET IssuedCopies=5 WHERE id=99");
        $this->assertFalse(issueBookLogic($this->dbh, 'S1', 99));
    }
    public function testIssueInvalidStudent() {
        // Assuming your DB has FK constraints, this would return false or throw error
        $res = issueBookLogic($this->dbh, '', 99);
        $this->assertFalse($res);
    }
}