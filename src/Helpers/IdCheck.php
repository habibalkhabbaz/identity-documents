<?php

namespace HabibAlkhabbaz\IdentityDocuments\Helpers;

class IdCheck
{
    public static function checkDigit(string $subject, string $check): ?bool
    {
        if (is_numeric($check)) {
            $check = intval($check);
        } elseif ($check === '<') {
            return true;
        }

        $pattern = [7, 3, 1];
        $characters = str_split($subject);
        $total = 0;
        foreach ($characters as $key => $character) {
            $weight = $key;
            while ($weight > 2) {
                $weight -= 3;
            }
            $value = self::toInt($character);
            if (is_null($value)) {
                return false;
            }
            $value = $value * $pattern[$weight];
            $total += $value;
        }
        $remainder = $total % 10;

        return $remainder === $check;
    }

    private static function toInt(string $character): ?int
    {
        $value = null;
        $alphabet = range('A', 'Z');
        if ($character === '<') {
            $value = 0;
        } elseif (is_numeric($character)) {
            $value = intval($character);
        } else {
            $character = strtoupper($character);
            if (in_array($character, $alphabet)) {
                $value = array_search($character, $alphabet) + 10;
            }
        }

        return $value;
    }
}
