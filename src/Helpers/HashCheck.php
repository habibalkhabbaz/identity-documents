<?php

namespace HabibAlkhabbaz\IdentityDocuments\Helpers;

class HashCheck
{
    public static function isValid(string $subject, string $hash): bool
    {
        if ($hash === '<') {
            return true;
        }

        $pattern = [7, 3, 1];
        $characters = str_split($subject);
        $total = 0;

        foreach ($characters as $key => $character) {
            $value = self::toInt($character);

            if (is_null($value)) {
                return false;
            }

            $value = $value * $pattern[$key % 3];
            $total += $value;
        }

        $remainder = $total % 10;

        return $remainder === (int) $hash;
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
