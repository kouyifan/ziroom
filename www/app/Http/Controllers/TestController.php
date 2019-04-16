<?php

namespace App\Http\Controllers;

use App\Services\ElasticSearchService;

class TestController extends Controller
{
    //

    public function test_es()
    {
        $es_client = new ElasticSearchService();

        $json = '{
            "query" : {
                "match" : {
                    "Title" : "Is there a way to rename a COM component without actually recompiling the source code?"
                }
            }
        }';

        $params = [
            'index' => 'stackoverflow2013',
            'type' => 'Posts',
            'size' => 10,
            'from' => 0,
            'body' => $json
        ];

        $params = [
            'index' => 'stackoverflow2013',
            'type' => 'Posts',
            'body' => [
                'query' => [
                    'match' => [
                        'Body' => 'php'
                    ]
                ]
            ]
        ];

        $data = $es_client->searchData($params);

        p($data);

    }


}
