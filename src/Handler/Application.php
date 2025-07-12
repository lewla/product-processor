<?php

namespace Lewla\ProductProcessor\Handler;

use Lewla\ProductProcessor\Parser;
use RuntimeException;

class Application {
    public static array $parsers = [
        "csv" => Parser\CsvParser::class,
        "tsv" => Parser\TsvParser::class,
    ];

    public function run (array $args) {
        if (!isset($args['file'])) {
            throw new RuntimeException("Missing 'file' argument");
        }

        if (!isset($args['unique-combinations'])) {
            throw new RuntimeException("Missing 'unique-combinations' argument");
        }

        $inputFile = strval($args['file']);
        $outputFile = strval($args['unique-combinations']);

        $file = pathinfo($inputFile);
        $fileType = $file['extension'];

        if(!array_key_exists($fileType, static::$parsers)) {
            throw new RuntimeException("No parser exists for $fileType files.");
        }

        $parserClass = static::$parsers[$fileType];
        $parser = new $parserClass();

        $this->processFile($parser, $inputFile, $outputFile);
    }

    public function processFile(Parser\AbstractParser $parser, string $inputFile, string $outputFile): void {
        $parser->parseFile($inputFile);
        $parser->generateCombinedFile($outputFile);
    }
}
