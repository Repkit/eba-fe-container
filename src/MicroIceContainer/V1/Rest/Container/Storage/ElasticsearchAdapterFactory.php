<?php
/**
 * Created by PhpStorm.
 * User: Andra-Mihaela State
 * Date: 9/27/2016
 * Time: 4:24 PM
 */

namespace MicroIceContainer\V1\Rest\Container\Storage;

use Elasticsearch\ClientBuilder;
use MicroIceContainer\V1\Rest\Container\Storage\ElasticsearchAdapter;

class ElasticsearchAdapterFactory
{
    public function __invoke($services)
    {
        $config = $services->get('config');
        $elasticConfig = $config['container_elastic_config'];
        
        $clientBuilder = ClientBuilder::create()
            ->setHosts($elasticConfig)
            ->setRetries(3)
            ->build();

        return new ElasticsearchAdapter($clientBuilder);
    }
}