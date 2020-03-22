<?php
return array(
    'service_manager' => array(
        'factories' => array(
            'MicroIceContainer\\V1\\Rest\\Container\\ContainerResource' => 'MicroIceContainer\\V1\\Rest\\Container\\ContainerResourceFactory',
            'MicroIceContainer\\V1\\Rest\\Container\\Storage\\StorageInterface' => 'MicroIceContainer\\V1\\Rest\\Container\\Storage\\ElasticsearchAdapterFactory',
        ),
    ),
    'router' => array(
        'routes' => array(
            'container.rest.container' => array(
                'type' => 'Segment',
                'options' => array(
                    'route' => '/container[/:container_id]',
                    'defaults' => array(
                        'controller' => 'MicroIceContainer\\V1\\Rest\\Container\\Controller',
                    ),
                ),
            ),
        ),
    ),
    'zf-versioning' => array(
        'uri' => array(
            0 => 'container.rest.container',
        ),
    ),
    'zf-rest' => array(
        'MicroIceContainer\\V1\\Rest\\Container\\Controller' => array(
            'listener' => 'MicroIceContainer\\V1\\Rest\\Container\\ContainerResource',
            'route_name' => 'container.rest.container',
            'route_identifier_name' => 'container_id',
            'collection_name' => 'container',
            'entity_http_methods' => array(
                0 => 'GET',
                1 => 'PATCH',
                2 => 'PUT',
                3 => 'DELETE',
                4 => 'POST',
            ),
            'collection_http_methods' => array(
                0 => 'GET',
                1 => 'POST',
            ),
            'collection_query_whitelist' => array(),
            'page_size' => 25,
            'page_size_param' => null,
            'entity_class' => 'MicroIceContainer\\V1\\Rest\\Container\\ContainerEntity',
            'collection_class' => 'MicroIceContainer\\V1\\Rest\\Container\\ContainerCollection',
            'service_name' => 'container',
        ),
    ),
    'zf-content-negotiation' => array(
        'controllers' => array(
            'MicroIceContainer\\V1\\Rest\\Container\\Controller' => 'HalJson',
        ),
        'accept_whitelist' => array(
            'MicroIceContainer\\V1\\Rest\\Container\\Controller' => array(
                0 => 'application/vnd.container.v1+json',
                1 => 'application/hal+json',
                2 => 'application/json',
            ),
        ),
        'content_type_whitelist' => array(
            'MicroIceContainer\\V1\\Rest\\Container\\Controller' => array(
                0 => 'application/vnd.container.v1+json',
                1 => 'application/json',
                2 => 'application/x-www-form-urlencoded',
            ),
        ),
    ),
    'zf-hal' => array(
        'metadata_map' => array(
            'MicroIceContainer\\V1\\Rest\\Container\\ContainerEntity' => array(
                'entity_identifier_name' => 'id',
                'route_name' => 'container.rest.container',
                'route_identifier_name' => 'container_id',
                'hydrator' => 'Zend\\Hydrator\\ArraySerializable',
            ),
            'MicroIceContainer\\V1\\Rest\\Container\\ContainerCollection' => array(
                'entity_identifier_name' => 'id',
                'route_name' => 'container.rest.container',
                'route_identifier_name' => 'container_id',
                'is_collection' => true,
            ),
        ),
    ),
);
