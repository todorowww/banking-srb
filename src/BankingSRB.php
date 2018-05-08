<?php

namespace todorowww;

class BankingSRB
{

    const CHECKSUM_BACK = 1;
    const CHECKSUM_FRONT = 2;

    public static function calculateChecksum($number)
    {
        return (98 - bcmod(bcmul(self::sanitize($number) , 100), '97')) % 97;
    }

    public static function sanitize($number)
    {
        return preg_replace("/[^0-9,.]/", null, $number);
    }

    public static function createBankAccountNumber($bankId, $accountNumber)
    {
        $padd = 16 - strlen(self::sanitize($bankId) . self::sanitize($accountNumber));
        $accNo = $bankId . str_repeat('0', $padd) . $accountNumber;
        return $accNo . self::calculateChecksum($accNo);
    }

    public static function createReferenceNumber($number, $checksumPosition = BankingSRB::CHECKSUM_BACK)
    {
        $checksum = self::calculateChecksum($number);
        return ($checksumPosition === BankingSRB::CHECKSUM_BACK) ?
            "$number-$checksum" :
            "$checksum-$number";
    }

    public static function formatAccountNumber($number)
    {
        $number = self::sanitize($number);
        $bankId = substr($number, 0, 3);
        $checksum = substr($number, -2, 2);
        $account = substr($number, 3, -2);
        return "$bankId-$account-$checksum";
    }

}
