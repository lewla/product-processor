<?php

namespace Lewla\ProductProcessor\Parser;

use RuntimeException;

class CsvParser extends AbstractParser {
    protected static string $separator = ',';
    protected static array $propertyMap = [
        'brand_name' => 'make',
        'model_name' => 'model',
        'colour_name' => 'colour',
        'gb_spec_name' => 'capacity',
        'network_name' => 'network',
        'grade_name' => 'grade',
        'condition_name' => 'condition',
    ];

    public function parseFile(string $path): void {
        if (!file_exists($path)) {
            throw new RuntimeException("File $path not found");
        }

        $handle = fopen($path, 'r');

        if ($handle === false) {
            throw new RuntimeException("Cannot open file $path");
        }

        $headers = fgetcsv($handle, null, static::$separator);

        while (($row = fgetcsv($handle, null, static::$separator)) !== false) {
            $data = array_combine($headers, $row);
            $product = $this->mapData($data);
            $hash = $product->generateHash();

            if(!array_key_exists($hash, $this->combinedProducts)) {
                $this->combinedProducts[$hash] = $product;
            }

            $this->combinedProducts[$hash]->incrementCount();
        }

        fclose($handle);
    }

    public function generateCombinedFile(string $path): void {
        $handle = fopen($path, 'w');

        $headers = array_keys(static::$propertyMap);
        $headers[] = 'count';

        fputcsv($handle, $headers, static::$separator);

        foreach ($this->combinedProducts as $product) {
            $row = array_map(fn($property) => $product->$property ?? '', static::$propertyMap);
            $row[] = $product->count;

            fputcsv($handle, $row, static::$separator);
        }

        fclose($handle);
    }
}
