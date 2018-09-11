<?php

namespace todorowww;

class BankingSRB
{

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
     * @param string $input Input we want to sanitize
     * @return string
     */
    public static function sanitize($input)
    {
        $sanitized = self::alphaToNum($input);
        return preg_replace("/[^0-9]/", null, $sanitized);
    }

    /**
     * Converts all A-Z characters to their numeric counterparts
     *
     * @param string $input
     * @return string
     */
    public static function alphaToNum($input)
    {
        $output = null;
        $sanitized = preg_replace("/[^0-9A-Z]/", null, strtoupper($input));
        foreach(str_split($sanitized) as $char) {
            $charOrd = ord($char);
            if (($charOrd >= 65) && ($charOrd <= 90)) {
                $output .= ($charOrd - 55);
            } else {
                $output .= $char;
            }
        }
        return $output;
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
     * @return string
     */
    public static function createReferenceNumber($number)
    {
        $checksum = self::calculateChecksum($number);
        return "$checksum-$number";
    }

    /**
     * Formats account number in a human readable way (3-13-2 digits)
     *
     * @param string $number Account number to format
     * @param bool $zeroPad Should we pad account number with 0
     * @return string
     */
    public static function formatAccountNumber($number, $zeroPad = false)
    {
        $sanitized = self::sanitize($number);
        $bankId = substr($sanitized, 0, 3);
        $checksum = substr($sanitized, -2, 2);
        $account = substr($sanitized, 3, -2);
        if ($zeroPad) {
            $account = str_repeat("0", 13 - strlen($account)) . $account;
        }
        return "$bankId-$account-$checksum";
    }
}
