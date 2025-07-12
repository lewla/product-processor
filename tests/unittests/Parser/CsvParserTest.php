<?php

namespace Tests\UnitTests\Parser;

use Lewla\ProductProcessor\Entities\Product;
use Lewla\ProductProcessor\Parser\CsvParser;
use PHPUnit\Framework\TestCase;
use RuntimeException;

class CsvParserTest extends TestCase {
    public function testMapData() {
        $parser = new CsvParser();

        $testData = [
            'brand_name' => 'Test',
            'model_name' => 'ABC',
            'colour_name' => 'White',
            'gb_spec_name' => '128 GB',
            'network_name' => 'EE',
            'grade_name' => 'Foo',
            'condition_name' => 'Grade A',
        ];

        $expected = new Product();
        $expected->make = 'Test';
        $expected->model = 'ABC';
        $expected->colour = 'White';
        $expected->capacity = '128 GB';
        $expected->network = 'EE';
        $expected->grade = 'Foo';
        $expected->condition = 'Grade A';

        $actual = $parser->mapData($testData);

        $this->assertEquals($expected, $actual);
    }

    public function testMapDataMissingRequiredField() {
        $parser = new CsvParser();

        $testData = [
            'brand_name' => 'Test',
            'model_name' => null,
            'colour_name' => 'White',
            'gb_spec_name' => '128 GB',
            'network_name' => 'EE',
            'grade_name' => 'Foo',
            'condition_name' => 'Grade A',
        ];

        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage("Required value model_name is empty or missing.");

        $parser->mapData($testData);
    }

    public function testMapDataMissingAllData() {
        $parser = new CsvParser();

        $testData = [];

        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessageMatches("/^Required value .+ is empty or missing.$/");

        $parser->mapData($testData);
    }
}
