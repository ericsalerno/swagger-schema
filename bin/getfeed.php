<?php

require_once(__DIR__ . '/../vendor/autoload.php');

global $argv;

if (empty($argv[1]))
{
    echo 'Usage: php ' . __FILE__ . ' <url> [node name]' . PHP_EOL;
    echo 'Node name is "data" if not specified.' . PHP_EOL;

    return;
}

$node = 'data';
if (!empty($argv[2]))
{
    $node = $argv[2];
}

try
{
    $feedParser = new \SwaggerSchema\FeedParser();
    $feedParser->buildFromURL($argv[1], $node, []);

    echo $feedParser->getSchema();
}
catch (\Exception $exception)
{
    echo 'Exception: ' . $exception->getMessage();
}
