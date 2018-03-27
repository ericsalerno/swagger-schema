<?php
/**
 * Swagger Feed Parser
 *
 * @package SwaggerSchema
 * @author Eric
 */
namespace SwaggerSchema;

class FeedParser
{
    /**
     * @var mixed|\Psr\Http\Message\ResponseInterface
     */
    private $result;

    /**
     * @var mixed
     */
    private $data;

    /**
     * @var mixed
     */
    private $feedSchema;

    /**
     * FeedParser constructor.
     * @param $url
     * @param $node
     * @param $connectionParams $params
     * @throws \Exception
     */
    public function buildFromURL($url, $node, $connectionParams = [])
    {
        $this->downloadFeedData($url, $connectionParams);

        $this->resolveObject($node);
    }

    /**
     * @param $object
     * @param $node
     */
    public function buildFromObject($object, $node)
    {
        $this->data = $object;

        $this->resolveObject($node);
    }

    /**
     * @param $node
     * @throws \Exception
     */
    private function resolveObject($node)
    {
        if (empty($this->data))
        {
            throw new \Exception("No valid object data was found!");
        }

        if (empty($this->data->$node))
        {
            throw new \Exception("Failed to find data->" . $node);
        }

        $this->feedSchema = new \stdClass();
        $this->feedSchema->$node = $this->parseSchema($node, $this->data->$node);
    }


    /**
     * @param $url
     * @param array $params
     * @throws \Exception
     */
    private function downloadFeedData($url, $params = [])
    {
        $client = new \GuzzleHttp\Client();

        $this->result = $client->request('GET', $url, $params);

        if (empty($this->result) || $this->result->getStatusCode() != 200)
        {
            throw new \Exception("Invalid response code " . $this->result->getStatusCode() . " from url " . $url);
        }

        $this->data = json_decode($this->result->getBody());
    }

    /**
     * @return string
     */
    public function getSchema()
    {
        return json_encode($this->feedSchema, JSON_PRETTY_PRINT);
    }

    /**
     * @param $nodeName
     * @param $node
     * @return \stdClass
     */
    private function parseSchema($nodeName, $node)
    {
        $output = new \stdClass();

        if (is_object($node))
        {
            $output->type = 'object';
            $output->properties = new \stdClass();

            foreach ($node as $field => $value)
            {
                $output->properties->$field = $this->parseSchema($field, $value);
            }
        }
        else if (is_array($node))
        {
            $output->type = 'array';
            $output->items = $this->parseSchema('index', $node[0]);
        }
        else if (is_string($node))
        {
            $output->type = 'string';
        }
        else if (is_bool($node))
        {
            $output->type = 'boolean';
        }
        else
        {
            if (is_integer($node))
            {
                $output->type = 'integer';
            }
            else
            {
                $output->type = 'number';
            }

        }

        return $output;
    }
}