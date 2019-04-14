<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\ElasticSearchService;

class importToEsCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:importEs';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Import to Es';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    private $es_service;
    private $index_name;
    private $index_type;

    public function __construct()
    {
        parent::__construct();
        $this->es_service = new ElasticSearchService();
        $this->index_name = 'stackoverflow2013';
        $this->index_type = 'Posts';
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {

        $this->delete_index($this->index_name);
        $this->create_index($this->index_name);
        $num = 0;
        $id = 0;
        while (true){

            $res = $this->select_data($id);
            $id = $res['id'] ?? 0;
            if (!$id) break;
            echo $id;
            $param = $res['data'];
            //入库
            $insert_res = $this->insert_index($param);
            //记录
            if (empty($insert_res['errors'])){
                \App\Record::create([
                   'count'  =>  count($param),
                   'max_id' =>  $id
                ]);
            }
            $num++;
            if ($num > 3){
                break;
            }
        }


    }

    //查询数据
    private function select_data($id = 0){
        $data = \App\Posts::where('Id','>',$id)->orderBy('Id','ASC')->take(2)->get()->toArray();
        $end = end($data);

        $res = [
            'data'  =>  $data,
            'id'    =>  !empty($end['Id']) ? $end['Id'] : false
        ];
        return $res;
    }

    //添加数据
    private function insert_index($params = []){
        if (empty($params)) return false;
        $insert = [];
        foreach ($params as $param){
            $insert['body'][] = [
                'index' => [
                    '_index' => $this->index_name,
                    '_type' => $this->index_type,
                ]
            ];
            $insert['body'][] = [
                'index' => [
                    'AcceptedAnswerId' => $param['AcceptedAnswerId'],
                    'AnswerCount' => $param['AnswerCount'],
                    'Body' => $param['Body'],
                    'ClosedDate' => $param['ClosedDate'],
                    'CommentCount' => $param['CommentCount'],
                    'CommunityOwnedDate' => $param['CommunityOwnedDate'],
                    'CreationDate' => $param['CreationDate'],
                    'FavoriteCount' => $param['FavoriteCount'],
                    'LastActivityDate' => $param['LastActivityDate'],
                    'LastEditDate' => $param['LastEditDate'],
                    'LastEditorDisplayName' => $param['LastEditorDisplayName'],
                    'LastEditorUserId' => $param['LastEditorUserId'],
                    'OwnerUserId' => $param['OwnerUserId'],
                    'ParentId' => $param['ParentId'],
                    'PostTypeId' => $param['PostTypeId'],
                    'Score' => $param['Score'],
                    'Tags' => $param['Tags'],
                    'Title' => $param['Title'],
                    'ViewCount' => $param['ViewCount'],
                ]
            ];
        }
        return $this->es_service->insertDatas($insert);
    }

    private function create_index($index = ''){
        $params = [
            'index' => $index,
            'body' => [
                'settings' => [
                    'number_of_shards' => 5,
                    'number_of_replicas' => 1
                ],
                'mappings' => [
                    $this->index_type => [
                        '_source' => [
                            'enabled' => true
                        ],
                        'properties' => [
                            'AcceptedAnswerId' => [
                                'type' => 'integer',
                            ],
                            'AnswerCount' => [
                                'type' => 'integer',
                            ],
                            'Body' => [
                                'type' => 'text',
                                'analyzer'  =>  'ik_max_word'
                            ],
                            'ClosedDate' => [
                                'type' => 'date',
                                'format' => 'yyyy-MM-dd HH:mm:ss'
                            ],
                            'CommentCount' => [
                                'type' => 'integer',
                            ],
                            'CommunityOwnedDate' => [
                                'type' => 'date',
                                'format' => 'yyyy-MM-dd HH:mm:ss'
                            ],
                            'CreationDate' => [
                                'type' => 'date',
                                'format' => 'yyyy-MM-dd HH:mm:ss'
                            ],
                            'FavoriteCount' => [
                                'type' => 'integer',
                            ],
                            'LastActivityDate' => [
                                'type' => 'date',
                                'format' => 'yyyy-MM-dd HH:mm:ss'
                            ],
                            'LastEditDate' => [
                                'type' => 'date',
                                'format' => 'yyyy-MM-dd HH:mm:ss'
                            ],
                            'LastEditorDisplayName' => [
                                'type' => 'keyword',
                            ],
                            'LastEditorUserId' => [
                                'type' => 'integer',
                            ],
                            'OwnerUserId' => [
                                'type' => 'integer',
                            ],
                            'ParentId' => [
                                'type' => 'integer',
                            ],
                            'PostTypeId' => [
                                'type' => 'integer',
                            ],
                            'Score' => [
                                'type' => 'integer',
                            ],
                            'Tags' => [
                                'type' => 'text'
                            ],
                            'Title' => [
                                'type' => 'text'
                            ],
                            'ViewCount' => [
                                'type' => 'integer',
                            ],
                        ]
                    ],
                ]
            ]
        ];
        return $this->es_service->createIndex($params);
    }

    private function delete_index($index = ''){
        $param = [
            'index' =>  $index
        ];
        $this->es_service->deleteIndex($param);
    }

}
