<?php

namespace Tests;

class BankingSRBTest extends \PHPUnit\Framework\TestCase {

    public function testSanitize() {
        $this->assertEquals("200", \todorowww\BankingSRB::sanitize("A200D"));
    }
}