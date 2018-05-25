<?php

namespace todorowww;

class BankingSRB
{

    /**
     * Places checksum at the back of reference number
     */
    const CHECKSUM_BACK = 1;
    /**
     * Places checksum at the front of reference number
     */
    const CHECKSUM_FRONT = 2;

    /**
     * Calculates checksum, using ISO 7064 MOD 97-10 algorithm
     *
     * @param string $number Number for which we want to calculate checksum
     * @return int
     */
    public static function calculateChecksum($number)
    {
        return (98 - bcmod(bcmul(self::sanitize($number), 100), '97')) % 97;
    }

    /**
     * Sanitizes input, removing all non-numeric values. Characters A-Z are converted to numbers
     * following this pattern: A=10, B=11, C=13, ..., Y=34, Z=35
     *
     * @todo Add conversion of A-Z characters
     * @param string $input Input we want to sanitize
     * @return string
     */
    public static function sanitize($input)
    {
        return preg_replace("/[^0-9,.]/", null, $input);
    }

    /**
     * Generates valid bank account number, with checksum
     *
     * @param int $bankId Unique bank Id
     * @param string $accountNumber Account number
     * @return string
     */
    public static function createBankAccountNumber($bankId, $accountNumber)
    {
        $padd = 16 - strlen(self::sanitize($bankId) . self::sanitize($accountNumber));
        $accNo = $bankId . str_repeat('0', $padd) . $accountNumber;
        return $accNo . self::calculateChecksum($accNo);
    }

    /**
     * Generates valid reference number, with appropriate checksum
     *
     * @param string $number Desired reference number
     * @param int $checksumPosition Where to place checksum number CHECKSUM_BACK or CHECKSUM_FRONT
     * @return string
     */
    public static function createReferenceNumber($number, $checksumPosition = BankingSRB::CHECKSUM_BACK)
    {
        $checksum = self::calculateChecksum($number);
        return ($checksumPosition === BankingSRB::CHECKSUM_BACK) ?
            "$number-$checksum" :
            "$checksum-$number";
    }

    /**
     * Formats account number in a human readable way (3-13-2 digits)
     *
     * @param string $number Account number to format
     * @return string
     */
    public static function formatAccountNumber($number)
    {
        $number = self::sanitize($number);
        $bankId = substr($number, 0, 3);
        $checksum = substr($number, -2, 2);
        $account = substr($number, 3, -2);
        return "$bankId-$account-$checksum";
    }
}
