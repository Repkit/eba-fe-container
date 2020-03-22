<?php
namespace MicroIceContainer\V1\Rest\Container;

use ZF\ApiProblem\ApiProblem;
use ZF\Rest\AbstractResourceListener;
use MicroIceContainer\V1\Rest\Container\Storage\StorageInterface;

class ContainerResource extends AbstractResourceListener
{
    protected $Storage;
    protected $DocumentLimit;
    protected $TTL;

    /**
     * LogsResource constructor.
     * @param StorageInterface $Storage
     */
    public function __construct(StorageInterface $Storage, $TTL = null, $DocumentLimit = null)
    {
        $this->Storage = $Storage;
        $this->TTL = $TTL;
        $this->DocumentLimit = $DocumentLimit;
    }

    /**
     * Create a resource
     *
     * @param  mixed $data
     * @return ApiProblem|mixed
     */
    public function create($data)
    {
        $request = $this->getEvent()->getRequest();
        $token = $this->getEvent()->getRouteParam('container_id');
        $headers = $request->getHeaders();
        $uri = $request->getUri();

        $host = $uri->getHost();
        $params = $request->getQuery();

        if ($headers->has('Content-Length'))
        {
            $headers = $headers->toArray();
            $contentLength = $headers['Content-Length'];
        }
        $indexParams['index'] = $host;
        $indexParams['type'] = $token;

        if (isset($params['replace']) && !empty($params['replace']))
        {
            $indexParams['body']['id'] = $token;
        }
        
        $mapping = array(
            '_ttl' => array(
                'enabled' => true,
                'default' => $this->TTL
            ),
        );

        $indexParams['body'][$token] = $mapping;
        $indexParams['body']['content'] = $data;

        if ($contentLength <= $this->DocumentLimit)
        {
            $result = $this->Storage->insert($indexParams);
            if (isset($result['_id']) && !empty($result['_id'])) {
                $result['id'] = $result['_id'];
            }

            return $result;
        }
        else
        {
            return new ApiProblem('405', "Maximum document limit has been reached!");
        }

       // return new ApiProblem(405, 'The POST method has not been defined');
    }

    /**
     * Delete a resource
     *
     * @param  mixed $id
     * @return ApiProblem|mixed
     */
    public function delete($id)
    {
        return new ApiProblem(405, 'The DELETE method has not been defined for individual resources');
    }

    /**
     * Delete a collection, or members of a collection
     *
     * @param  mixed $data
     * @return ApiProblem|mixed
     */
    public function deleteList($data)
    {
        return new ApiProblem(405, 'The DELETE method has not been defined for collections');
    }

    /**
     * Fetch a resource
     *
     * @param  mixed $id
     * @return ApiProblem|mixed
     */
    public function fetch($id)
    {
        $event = $this->getEvent();
        $request = $event->getRequest();
        $queryParams = $event->getQueryParams();
        $token = $event->getRouteParam("container_id");
        $uri = $request->getUri();
        $host = $uri->getHost();

        $content = array(
            'index' => $host,
            'body' => array(
                'query' => array(
                    'match' => array(
                        '_type' => $token
                    )
                )
            )
        );

        if (isset($queryParams['from']))
        {
            $content['body']['from'] = $queryParams['from'];
        }
        if (isset($queryParams['size']))
        {
            $content['body']['size'] = $queryParams['size'];
        }

        $result = $this->Storage->fetch($content);

        return $result;
        //return new ApiProblem(405, 'The GET method has not been defined for individual resources');
    }

    /**
     * Fetch all or a subset of resources
     *
     * @param  array $params
     * @return ApiProblem|mixed
     */
    public function fetchAll($params = array())
    {
        return new ApiProblem(405, 'The GET method has not been defined for collections');
    }

    /**
     * Patch (partial in-place update) a resource
     *
     * @param  mixed $id
     * @param  mixed $data
     * @return ApiProblem|mixed
     */
    public function patch($id, $data)
    {
        return new ApiProblem(405, 'The PATCH method has not been defined for individual resources');
    }

    /**
     * Replace a collection or members of a collection
     *
     * @param  mixed $data
     * @return ApiProblem|mixed
     */
    public function replaceList($data)
    {
        return new ApiProblem(405, 'The PUT method has not been defined for collections');
    }

    /**
     * Update a resource
     *
     * @param  mixed $id
     * @param  mixed $data
     * @return ApiProblem|mixed
     */
    public function update($id, $data)
    {
        return new ApiProblem(405, 'The PUT method has not been defined for individual resources');
    }
}
