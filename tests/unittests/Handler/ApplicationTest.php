<?php

namespace Tests\UnitTests\Handler;

use Lewla\ProductProcessor\Handler\Application;
use Lewla\ProductProcessor\Parser\CsvParser;
use PHPUnit\Framework\TestCase;
use RuntimeException;

class ApplicationTest extends TestCase {
    public function testRunWithNoArgs() {
        $app = new Application();
        $args = [];

        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessageMatches("/^Missing '.+' argument$/");

        $app->run($args);
    }

    public function testRunWithInvalidArgs() {
        $app = new Application();
        $args = ["foo" => "bar"];

        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessageMatches("/^Missing '.+' argument$/");

        $app->run($args);
    }

    public function testRunWithOneValidArg() {
        $app = new Application();
        $args = ["file" => "bar.csv"];

        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage("Missing 'unique-combinations' argument");

        $app->run($args);
    }

    public function testRunWithInvalidFileExtension() {
        $app = new Application();
        $args = ["file" => "bar.invalidextension", "unique-combinations" => "bar-combined.csv"];

        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage("No parser exists for invalidextension files.");

        $app->run($args);
    }

    public function testRunWithValidFileExtension() {
        $args = ["file" => "foo.test", "unique-combinations" => "bar-combined.csv"];

        $mockApp = $this->getMockBuilder(Application::class)
            ->onlyMethods(["processFile"])
            ->getMock();

        $mockParser = $this->createMock(CsvParser::class);

        Application::$parsers = [
            "test" => $mockParser::class
        ];

        $mockApp->expects($this->once())
            ->method('processFile')
            ->with($mockParser, 'foo.test', 'bar-combined.csv');

        $mockApp->run($args);
    }

    public function testProcessFile() {
        $mockParser = $this->createMock(CsvParser::class);

        $app = new Application();
        $args = ["file" => "", "unique-combinations" => "bar-combined.csv"];

        $mockParser->expects($this->once())
            ->method('parseFile')
            ->with('bar.test');

        $mockParser->expects($this->once())
            ->method('generateCombinedFile')
            ->with('bar-combined.csv');

        $app->processFile($mockParser, "bar.test", "bar-combined.csv");
    }
}
