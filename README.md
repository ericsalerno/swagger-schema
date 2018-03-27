# SwaggerSchema Builder

This library just builds out the definition schema for objects in a feed that already exists and that you're trying to build a swagger file for. This was something really quickly thrown together to help with building a swagger file for an old API that did not have appropriate documentation. It's not exhaustive and it was purpose built for the implementation I was trying to document, but feel free to fork if you need to. 

A better tool for this most likely exists but I'm very limited in what I can run on this particular computer and "need a tool, make a tool". I do not currently have any plans to make it not work with the sub field specified. This was done on purpose for the implementation but feel free to change it if you want to. This uses recursion.

## Usage

Instantiate a FeedParser class and either make it download a URL with guzzle, or dump an object directly into it.

    $parser = new \SwaggerSchema\FeedParser();
    $parser->buildFromObject($object, 'data');
    
Or to download directly and use it just do

    $parser = new \SwaggerSchema\FeedParser();
    $parser->buildFromURL($url, 'data', ['optional parameters for guzzle client']);

## Built-in Script Usage

You can use the built-in getfeed.php script to generate the definition schema. For example:

    php bin/getfeed.php http://www.example.com/somefeed.json data
    
This will download the feed, and parse the structure of $feed->data into swagger json.

If the feed there looked like this:

    {
        "status": "OK",
        "data": {
            "id": 3561,
            "title": "Blog Post!",
            "date": "2018-03-27",
            "hotness": 0.1,
            "public": true,
            "tags": [
                "happy",
                "blog",
                "post"
            ],
            "related": [
                45,
                66,
                77
            ],
            "show": {
                "title": "The Me Show",
                "public": false
            }
        }
    }

After you run the script it will output:

    {
        "data": {
            "type": "object",
            "properties": {
                "id": {
                    "type": "integer"
                },
                "title": {
                    "type": "string"
                },
                "date": {
                    "type": "string"
                },
                "hotness": {
                    "type": "number"
                },
                "public": {
                    "type": "boolean"
                },
                "tags": {
                    "type": "array",
                    "items": {
                        "type": "string"
                    }
                },
                "related": {
                    "type": "array",
                    "items": {
                        "type": "integer"
                    }
                },
                "show": {
                    "type": "object",
                    "properties": {
                        "title": {
                            "type": "string"
                        },
                        "public": {
                            "type": "boolean"
                        }
                    }
                }
            }
        }
    }

This segment is suitable for injecting into your definitions section of your swagger json document.