<?php

namespace Lewla\ProductProcessor\Parser;

use Lewla\ProductProcessor\Entities\Product;
use ReflectionClass;
use RuntimeException;

abstract class AbstractParser {
    /**
     * @var Product[]
     */
    public array $combinedProducts = [];
    protected static array $propertyMap;

    abstract public function parseFile(string $path): void;
    abstract public function generateCombinedFile(string $path): void;

    public function mapData(array $data): Product {
        $product = new Product();
        $refClass = new ReflectionClass(Product::class);

        foreach(static::$propertyMap as $header => $propertyName) {
            $value = $data[$header] ?? null;
            $property = $refClass->getProperty($propertyName);
            $isRequired = !$property->getType()->allowsNull();
            
            if ($isRequired && empty($value)) {
                throw new RuntimeException("Required value $header is empty or missing.");
            }

            $property->setValue($product, $value);
        }

        return $product;
    }
}
