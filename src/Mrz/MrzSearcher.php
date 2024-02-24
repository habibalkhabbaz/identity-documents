<?php

namespace HabibAlkhabbaz\IdentityDocuments\Mrz;

use HabibAlkhabbaz\IdentityDocuments\Helpers\HashCheck;

class MrzSearcher extends Mrz
{
    public function search(string $string): ?string
    {
        [$strippedString, $characters] = $this->stripString($string);
        $keysPositions = $this->findPositionsInCharacters($characters);
        $startPosition = $this->findMrzStartPosition($keysPositions, $characters);

        if ($startPosition === null) {
            return null;
        }

        return $this->getMrz($strippedString, $startPosition);
    }

    private function getMrz($strippedString, $startPosition): string
    {
        return substr($strippedString, $startPosition, $this->type->length());
    }

    private function findPositionsInCharacters(array $characters): array
    {
        $positions = [];

        foreach (array_keys($this->typesByKeys) as $key) {
            $positions[$key] = array_keys($characters, $key, true);
        }

        return $positions;
    }

    private function buildCheckString(array $checkOver, int $position, array $characters, bool $convert = false): string
    {
        $checkPositions = [];

        foreach ($checkOver as $check) {
            $start = $position + $check[0];
            $end = $start + $check[1] - 1;
            $checkPositions = array_merge($checkPositions, range($start, $end));
        }

        $checkString = '';

        foreach ($checkPositions as $checkPosition) {
            $checkString .= ($characters[$checkPosition] === 'O' && $convert) ? '0' : $characters[$checkPosition];
        }

        return $checkString;
    }

    private function checkPositionInFormat(int $position, array $characters, array $checkDigits): bool
    {
        foreach ($checkDigits as $hashIndex => $checkOver) {
            $hashPosition = $position + $hashIndex;

            if (! isset($characters[$hashPosition])) {
                return false;
            }

            $hash = ($characters[$hashPosition] === 'O') ? '0' : $characters[$hashPosition];

            $checkString = $this->buildCheckString($checkOver, $position, $characters);

            if (! HashCheck::isValid($checkString, $hash)) {
                $checkString = $this->buildCheckString($checkOver, $position, $characters, true);
                if (! HashCheck::isValid($checkString, $hash)) {
                    return false;
                }
            }
        }

        return true;
    }

    private function testPositions(array $checks, array $positions, $characters): ?int
    {
        foreach ($positions as $position) {
            if ($this->checkPositionInFormat($position, $characters, $checks)) {
                return $position;
            }
        }

        return null;
    }

    private function testKeyTemplates(string $key, array $positions, array $characters): ?int
    {
        $types = $this->typesByKeys[$key];

        foreach ($types as $type) {
            $position = $this->testPositions($type->checks(), $positions, $characters);
            if ($position !== null) {
                $this->type = $type;

                return $position;
            }
        }

        return null;
    }

    private function findMrzStartPosition(array $keysPositions, array $characters): ?int
    {
        foreach ($keysPositions as $key => $positions) {
            $position = $this->testKeyTemplates($key, $positions, $characters);
            if ($position !== null) {
                return $position;
            }
        }

        return null;
    }

    private function stripString(string $string): array
    {
        $strippedString = preg_replace('/\r\n|\r|\n/', '', $string);
        $strippedString = preg_replace('/\s+/', '', $strippedString);
        $strippedString = (is_string($strippedString)) ? $strippedString : $string;
        $characters = str_split($strippedString);

        return [$strippedString, $characters];
    }
}
