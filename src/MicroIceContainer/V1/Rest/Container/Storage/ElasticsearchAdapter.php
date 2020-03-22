<?php
/**
 * Created by PhpStorm.
 * User: Andra-Mihaela State
 * Date: 9/27/2016
 * Time: 4:24 PM
 */

namespace MicroIceContainer\V1\Rest\Container\Storage;

use Zend\Mvc\Application;
use ZF\ApiProblem\ApiProblem;

class ElasticsearchAdapter implements StorageInterface
{
    protected $EsClient;

    /**
     * ElasticsearchAdapter constructor.
     * @param $EsClient
     */
    public function __construct($EsClient)
    {
        $this->EsClient = $EsClient;
    }

    /**
     * @param $Content
     * @return mixed
     */
    public function insert($Content)
    {
        $index = $Content['index'];
        $bodyContent = $Content['body']['content'];
        unset($Content['body']['content']);
        if (isset($Content['body']['id']))
        {
            $id = $Content['body']['id'];
            unset($Content['body']['id']);
        }
        $exists = $this->EsClient->indices()->exists(array('index' => $index));
        
        if (!$exists)
        {
            $this->EsClient->indices()->create(array('index' => $index));
        }

        $this->EsClient->indices()->putMapping($Content);
        $Content['body']['Content'] = json_decode(json_encode($bodyContent), true);
        if (isset($id))
        {
            $Content['id'] = $id;
        }
        $response = $this->EsClient->index($Content);

        return $response;
    }

    /**
     * @param $Content
     * @return ApiProblem
     */
    public function fetch($Content)
    {
        $response = $this->EsClient->search($Content);

        if (!isset($response['hits']['hits']) || empty($response['hits']['hits']))
        {
            return new ApiProblem(417, 'Document not found!');
        }
        else
        {
            return $response;
        }
    }
}