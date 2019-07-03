<?php

namespace Paprika;

class Encryption
{
    /**
     * @param string $string
     * @return string
     */
    public static function checksum(string $string): string
    {
        $CRC16Polynomial = 0x1021;
        $result = 0xFFFF;

        if (($length = strlen($string)) > 0) {
            for ($offset = 0; $offset < $length; $offset++) {
                $result ^= (ord($string[$offset]) << 8);
                for ($bitwise = 0; $bitwise < 8; $bitwise++) {
                    if (($result <<= 1) & 0x10000) $result ^= $CRC16Polynomial;
                    $result &= 0xFFFF;
                }
            }
        }

        return strtoupper(dechex($result));
    }
}
