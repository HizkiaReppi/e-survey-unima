<?php

namespace App\Helpers;

class TextFormattingHelper
{
    /**
     * Formats a given NIM (Nomor Induk Mahasiswa) number according to the standard format.
     *
     * @param string $nim The NIM number to be formatted.
     * @throws No exception is thrown, but returns $nim if the NIM number is invalid.
     * @return string The formatted NIM number.
     */
    public static function formatNIM(string $nim): string
    {
        $cleanedNIM = preg_replace('/[^0-9]/', '', $nim);

        if (strlen($cleanedNIM) > 10 || strlen($cleanedNIM) < 8) {
            return $nim;
        }

        $part1 = substr($cleanedNIM, 0, 2);
        $part2 = substr($cleanedNIM, 2, 3);
        $part3 = substr($cleanedNIM, 5, 4);

        return sprintf('%s %s %s', $part1, $part2, $part3);
    }
}
