<?php
/**
 * Feed Parser Tests
 *
 * @package SwaggerSchema
 * @subpackage Tests
 * @author Eric
 */
namespace SwaggerSchema\Tests;

class FeedParserTest extends \PHPUnit\Framework\TestCase
{
    /**
     * Test comprehensive object parsing
     */
    public function testObjectParsing()
    {
        $object = new \stdClass();
        $object->data = new \stdClass();
        $object->data->item = 'thing';
        $object->data->stuff = 1.0;
        $object->data->bill = 15;
        $object->data->boolean = true;
        $object->data->strings = ['string array'];
        $object->data->numbers = [4.5, 4.2];
        $object->data->integers = [1, 2, 3, 4];
        $object->data->booleans = [false, true, false];

        $subObject = new \stdClass();
        $subObject->wear = 'watch';

        $object->data->objects = [$subObject];

        $object->data->subObject = $subObject;

        $parser = new \SwaggerSchema\FeedParser();
        $parser->buildFromObject($object, 'data');

        $schema = $parser->getSchema();
        $this->assertEquals(file_get_contents(__DIR__ . '/data/result1.txt'), $schema);
    }

    /**
     * Test array parsing
     */
    public function testArrayParsing()
    {
        $object = new \stdClass();
        $object->data = ["test"];

        $parser = new \SwaggerSchema\FeedParser();
        $parser->buildFromObject($object, 'data');

        $schema = $parser->getSchema();
        $this->assertEquals(file_get_contents(__DIR__ . '/data/result2.txt'), $schema);
    }
}