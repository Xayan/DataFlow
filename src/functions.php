<?php

namespace DataFlow;

/**
 * Converts an array into CSV string
 * Source: https://stackoverflow.com/questions/16352591/convert-php-array-to-csv-string
 *
 * @param $input
 * @param string $delimiter
 * @param string $enclosure
 * @param string $escape
 * @return string
 */
function str_putcsv($input, $delimiter = ',', $enclosure = '"', $escape = '\\')
{
    $fp = fopen('php://memory', 'r+');

    fputcsv($fp, $input, $delimiter, $enclosure, $escape);

    rewind($fp);
    $data = rtrim(stream_get_contents($fp), "\n");
    fclose($fp);

    return rtrim($data, "\n");
}