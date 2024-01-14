<?php
if (!function_exists('generateUniqueNumber')) {
    function generateUniqueNumber(int $length): int
    {
        $digits = range(1, 9);
        shuffle($digits);

        // Take the first 'length' digits to form a unique number
        $uniqueNumber = implode('', array_slice($digits, 0, $length));

        return (int)$uniqueNumber;
    }
}
