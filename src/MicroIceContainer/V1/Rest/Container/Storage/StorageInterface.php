<?php
/**
 * Created by PhpStorm.
 * User: Andra-Mihaela State
 * Date: 9/27/2016
 * Time: 4:24 PM
 */

namespace MicroIceContainer\V1\Rest\Container\Storage;

interface StorageInterface
{
    public function insert($Content);
    public function fetch($Content);
}