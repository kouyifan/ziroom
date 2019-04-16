<?php
namespace App\Services;
use Elasticsearch\ClientBuilder;


class ElasticSearchService{

    protected $es_client;

    public function __construct()
    {
//        $this->es_client = ClientBuilder::create()->setHosts(['127.0.0.1:9200'])->build();
        $this->es_client = ClientBuilder::create()->setHosts(['elasticsearch.koukousky.com:80'])->build();
    }
    /*
     * 建立索引
     */
    public function createIndex($params = []){

        return $this->es_client->indices()->create($params);
    }

    /*
     * 删除索引
     */
    public function deleteIndex($params = []){

        return $this->es_client->indices()->delete($params);
    }

    /*
     * 更新索引
     */
    public function putIndex($params = []){

        return $this->es_client->indices()->putSettings($params);
    }

    /*
     *  获得索引信息
     */
    public function getIndex($params = []){

        return $this->es_client->indices()->getSettings($params);
    }

    /*
     * 插入数据
     */
    public function insertData($params = []){

        return $this->es_client->index($params);
    }

    /*
     * 批量插入数据
     */
    public function insertDatas($params = []){

        return $this->es_client->bulk($params);
    }

    /*
     * 更新数据
     */
    public function updateData($params = []){

        return $this->es_client->update($params);
    }

    /*
     * 删除数据
     */
    public function deleteData($params = []){

        return $this->es_client->delete($params);
    }

    /*
     * 搜索数据
     */
    public function searchData($params = []){

        return $this->es_client->search($params);
    }



}