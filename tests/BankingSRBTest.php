<?php

namespace Tests;

class BankingSRBTest extends \PHPUnit\Framework\TestCase {

    public function testCalculateChecksum() {
        $this->assertEquals("54", \todorowww\BankingSRB::calculateChecksum("100-0001234567890"));
        $this->assertEquals("44", \todorowww\BankingSRB::calculateChecksum("900-0000000054321"));
    }

    public function testSanitize() {
        $this->assertEquals("1020013", \todorowww\BankingSRB::sanitize("A200D"));
        $this->assertEquals("272301327", \todorowww\BankingSRB::sanitize("RN01-327"));
    }

    public function testAlphaToNum() {
        $this->assertEquals("101112", \todorowww\BankingSRB::alphaToNum("ABC"));
        $this->assertEquals("353433", \todorowww\BankingSRB::alphaToNum("ZYX"));
        $this->assertEquals("29142829", \todorowww\BankingSRB::alphaToNum("TEST"));
    }

    public function testCreateBankAccountNumber() {
        $this->assertEquals("100000123456789054", \todorowww\BankingSRB::createBankAccountNumber(100, "1234567890"));
        $this->assertEquals("900000000005432144", \todorowww\BankingSRB::createBankAccountNumber(900, "54321"));
    }

    public function testCreateReferenceNumber() {
        $this->assertEquals("92-1234567890", \todorowww\BankingSRB::createReferenceNumber("1234567890"));
        $this->assertEquals("92-ABC-123", \todorowww\BankingSRB::createReferenceNumber("ABC-123"));
        $this->assertEquals("38-987-XYZ", \todorowww\BankingSRB::createReferenceNumber("987-XYZ"));
        $this->assertEquals("87-ABCDEF", \todorowww\BankingSRB::createReferenceNumber("ABCDEF"));
    }

    public function testFormatAccountNumber() {
        $this->assertEquals("100-1234567890-54", \todorowww\BankingSRB::formatAccountNumber("100-1234567890-54"));
        $this->assertEquals("100-0001234567890-54", \todorowww\BankingSRB::formatAccountNumber("100-1234567890-54", true));
    }
}