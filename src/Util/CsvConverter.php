<?php

namespace App\Util;

class CsvConverter
{
    public static function convert(array $data): string
    {
        $file = fopen(__DIR__ . '/temp.tmp', 'w+');
        $dataArray = json_encode($data);
        $dataArray = json_decode($dataArray, true);
        foreach ($dataArray as $row) {
            foreach ($data as $product) {
                if ($row['id'] == $product->getId()) {
                    $row['releaseDate'] = date('Y-m-d', $product->getReleaseDate()->getTimestamp());
                    break;
                }
            }
            unset($row['services']);
            fputcsv($file, $row, ',', '"');
        }
        rewind($file);
        $result = fread($file, filesize(__DIR__ . '/temp.tmp'));
        unlink(__DIR__ . '/temp.tmp');

        return $result;
    }
}