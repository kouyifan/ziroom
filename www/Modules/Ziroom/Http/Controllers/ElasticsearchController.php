<?php

namespace Modules\Ziroom\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Elasticsearch\ClientBuilder;

class ElasticsearchController extends Controller
{
    private $es_client = null;

    public function __construct()
    {
        $this->client = ClientBuilder::create()           // Instantiate a new ClientBuilder
        ->setHosts(config('custom.elasticsearch.url'))      // Set the hosts
        ->build();
    }
    //建立索引
    public function create_index()
    {

        $params = [
            'index' => 'book',
            'body' => [
                'settings' => [
                    'number_of_shards' => 5,
                    'number_of_replicas' => 1
                ],
                'mappings' => [
                    'my_type' => [
                        '_source' => [
                            'enabled' => true
                        ],
                        'properties' => [
                            'first_name' => [
                                'type' => 'string',
                                'analyzer' => 'standard'
                            ],
                            'age' => [
                                'type' => 'integer'
                            ]
                        ]
                    ]
                ]
            ]
        ];
        $response = $this->es_client->indices()->create($params);
        return $response;
    }


}
