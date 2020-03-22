<?php
namespace MicroIceContainer\V1\Rest\Container;

class ContainerResourceFactory
{
    public function __invoke($services)
    {
        $ttl = null;
        $storage = $services->get(Storage\StorageInterface::class);
        $config = $services->get('config');
        if (isset($config['ttl']) && !empty($config['ttl']))
        {
            $ttl = $config['ttl'];
        }
        if (isset($config['storage_size'])) {
            $storageSize = $config['storage_size'];
        }

        $container = new  ContainerResource($storage, $ttl, $storageSize);

        return $container;
    }
}
 