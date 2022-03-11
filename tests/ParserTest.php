<?php

namespace Abbotton\BigXmlParser\Tests;

use Abbotton\BigXmlParser\GenericHandler;
use Abbotton\BigXmlParser\Parser;
use PHPUnit\Framework\TestCase;

class ParserTest extends TestCase
{
    public function testParser()
    {
        $file = __DIR__.'/sample.xml';

        $self = $this;
        $iteration = 1;
        $handler = new GenericHandler;
        $handler->setOnItemParsedCallback(function ($item) use ($self, &$iteration) {
            $self->assertEquals('VALUE '.$iteration, $item['value']);
            $iteration++;
        });
        $handler->setOnProgressCallback(function ($bytesProcessed, $bytesTotal) use ($self, $file) {
            $self->assertEquals(filesize($file), $bytesProcessed);
            $self->assertEquals(filesize($file), $bytesTotal);
        });
        $parser = new Parser($handler);
        $parser->setIgnoreTags(['root']);
        $parser->setEndTag('value');
        $parser->parse($file);
    }

    public function testParserSkipsTags()
    {
        $file = __DIR__.'/sample2.xml';
        $iteration = 1;
        $handler = new GenericHandler;
        $handler->setOnItemParsedCallback(function ($item) use (&$iteration) {
            $this->assertArrayNotHasKey('invalid', $item);
            $iteration++;
        });
        $parser = new Parser($handler);
        $parser->setIgnoreTags(['root', 'invalid']);
        $parser->setEndTag('content');
        $parser->parse($file);
    }

    public function testParserReportsOnProgress()
    {
        $file = __DIR__.'/sample3.xml';

        $handler = new GenericHandler;
        $handler->setOnProgressCallback(function ($bytesProcessed, $bytesTotal) {
            $this->assertContains($bytesProcessed, array(100, 200, 300, 363));
        });
        $parser = new Parser($handler);
        $parser->setReadBuffer(100);
        $parser->setIgnoreTags(['root']);
        $parser->setEndTag('content');
        $parser->parse($file);
    }

    public function testParsingXmlContainingArrayTypes()
    {
        $file = __DIR__.'/sample4.xml';
        $handler = new GenericHandler;
        $handler->setOnItemParsedCallback(function ($item) {
            $this->assertTrue(is_array($item['value']));
        });
        $parser = new Parser($handler);
        $parser->setIgnoreTags(['root']);
        $parser->setEndTag('value');
        $parser->setArrayTags(['value']);
        $parser->parse($file);
    }
}
