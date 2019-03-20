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

    //存储数据
    public function save_data(){
        $blogs = \Modules\Ziroom\Entities\blogs::orderBy('id','desc')->take(10)->get();
        $res = [];
        foreach ($blogs as $blog) {

            $params = [
                'index' => 'blogs',
                'type' => 'news',
                'id' => $blog->id,
                'body' => [
                    'title' => $blog->title,
                    'content' => $blog->content,
                    'author' => $blog->author,
                    'created_at' => $blog->created_at->toDateTimeString(),
                    'updated_at' => $blog->updated_at->toDateTimeString()
                ]
            ];

            $res[] = $this->es_client->index($params);
//            $res[] = $this->es_client->bulk($params);
        }
        dump($res);

    }

    //查询数据
    public function find_data(){
        $keywords = \request('keywords','');

        $params = [
            'index' => 'blogs',
            'type' => 'news',
            'body' => [
                'query' => [
                    'match' => [
                        'title' => $keywords
                    ],
                    'match' =>  [
                        'content'   =>  $keywords
                    ]
                ]
            ]
        ];

        $results = $this->es_client->search($params);
        return $results;

    }


}
