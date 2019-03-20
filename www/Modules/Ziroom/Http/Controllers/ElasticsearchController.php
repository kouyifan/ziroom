<?php

namespace Modules\Ziroom\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Elasticsearch\ClientBuilder;

class ElasticsearchController extends Controller
{
    private $es_client;

    public function __construct()
    {
        $this->es_client = ClientBuilder::create()->setHosts(config('custom.elasticsearch.url'))->build();
    }

    //建立索引
    public function create_index()
    {
        $params = [
            'index' => 'blogs',
            'body' => [
                'settings' => [
                    'number_of_shards' => 5,
                    'number_of_replicas' => 1
                ],
                'mappings' => [
                    'news' => [
                        '_source' => [
                            'enabled' => true
                        ],
                        'properties' => [
                            'title' => [
                                'type' => 'keyword',
                            ],
                            'content' => [
                                'type' => 'text'
                            ],
                            'author' => [
                                'type' => 'keyword',
                            ],
                            'created_at' => [
                                'type' => 'date',
                                'format' => 'yyyy-MM-dd HH:mm:ss'
                            ],
                            'updated_at' => [
                                'type' => 'date',
                                'format' => 'yyyy-MM-dd HH:mm:ss'
                            ]
                        ]
                    ],
                ]
            ]
        ];
        $response = $this->es_client->indices()->create($params);
        return $response;
    }


}
