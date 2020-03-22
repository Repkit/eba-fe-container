<?php
/**
 * Created by PhpStorm.
 * User: Andra-Mihaela State
 * Date: 9/28/2016
 * Time: 6:42 PM
 */

namespace MicroIceContainer\tests;


use Elasticsearch\ClientBuilder;
use MicroIceContainer\V1\Rest\Container\ContainerResource;
use MicroIceContainer\V1\Rest\Container\Storage\ElasticsearchAdapter;
use PHPUnit_Framework_TestCase as TestCase;
use ApplicationTest\Bootstrap;
use ZF\ContentNegotiation\Request;
use Zend\Mvc\Router\RouteMatch;
use Zend\Stdlib\ArrayObject;
use ZF\Rest\ResourceEvent;

class ContainerTest extends TestCase
{
    private $serviceManager;
    private $Storage;
    private $EsClient;
    private $Request;
    private $Headers;
    private $TTL;
    private $DocumentLimit;

    /**
     * Set up
     */
    public function setUp()
    {
        $this->serviceManager = Bootstrap::getServiceManager();
        $this->EsClient = $this->getMockBuilder(ClientBuilder::class);
        $this->Storage = $this->getMockBuilder(ElasticsearchAdapter::class)
            ->disableOriginalConstructor()
            ->setMethods(['insert', 'fetch'])
            ->getMock();
        $this->Request = $this->getMockBuilder(Request::class)
            ->disableOriginalConstructor()
            ->setMethods(['getHeaders','getContent', 'has', 'getUri'])
            ->getMock();

        $this->Headers = new \Zend\Http\Headers();
        $this->Headers->addHeaderLine('Content-Type','application/json');
        $this->Headers->addHeaderLine('Content-Length', 1000);
        $this->TTL = "60m";
        $this->DocumentLimit = 10240;
    }

    public function containerDataProvider()
    {
        return array(
            array(
                "{
                'name': 'trip-microservices/logs',
                'type': 'package',
                'description': 'Implementation of container microservice',
                'keywords': ['php','container', 'history'],
                'license': 'dcs+',
                'config': {
                    'secure-http': false
                },
                'autoload': {
                    'psr-4': {'MicroIceContainer\\': 'src/MicroIceContainer'}
                },
                'scripts': {
                        'pre-update-cmd': [
                            'sh standalone.install.sh MicroIceContainer'
                        ]
                    }
                }"
            )
        );
    }

    /**
     * @param $Content
     * @dataProvider containerDataProvider
     */
    public function testCreateContainerElement($Content)
    {
        $routeMatch = new RouteMatch(
            array(
                'container_id' => 'token'
            )
        );

        $resultArray = array(
            "id" => "AVdxnYueUvDWvgAKR6rL",
        );

        $resourceEvent = new ResourceEvent('create', null,
            array(
                'data' => new ArrayObject ([
                    $Content
                ], ArrayObject::ARRAY_AS_PROPS)
            )
        );

        $this->Request->expects($this->once())
            ->method('getUri')
            ->willReturn(new \Zend\Uri\Http("http://192.168.1.15/~andra_state/Bucket/public/container/token"));

        $this->Request->expects($this->once())
            ->method('getHeaders')
            ->willReturn($this->Headers);

        $this->Storage->expects($this->once())
            ->method('insert')
            ->willReturn($resultArray);

        $resource = new ContainerResource($this->Storage, $this->TTL, $this->DocumentLimit);
        $resourceEvent->setRouteMatch($routeMatch);
        $resourceEvent->setRequest($this->Request);
        $result = $resource->dispatch($resourceEvent);
        $this->assertEquals($resultArray, $result);
    }

    public function testFetchContainerElement()
    {
        $resultArray = array(
            "_index" =>  "192.168.1.15",
            "_type" => "token",
            "_id" => 'UvDWvgAKR6rE'
        );

        $this->Request->expects($this->once())
            ->method('getUri')
            ->willReturn(new \Zend\Uri\Http("http://192.168.1.15/~andra_state/Bucket/public/container/token"));

        $resourceEvent = new ResourceEvent('fetch', null, [
                'id' => 'UvDWvgAKR6rE'
            ]
        );

        $this->Storage->expects($this->once())
            ->method('fetch')
            ->willReturn($resultArray);

        $resource = new ContainerResource($this->Storage, $this->TTL, $this->DocumentLimit);
        $resourceEvent->setRequest($this->Request);
        $result = $resource->dispatch($resourceEvent);
        $this->assertEquals($result, $resultArray);
    }

}