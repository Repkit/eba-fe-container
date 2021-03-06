<?php
/**
 * @license   http://opensource.org/licenses/BSD-3-Clause BSD-3-Clause
 * @copyright Copyright (c) 2014 Zend Technologies USA Inc. (http://www.zend.com)
 */

/**
 * Global Configuration Override
 *
 * You can use this file for overriding configuration values from modules, etc.
 * You would place values in here that are agnostic to the environment and not
 * sensitive to security.
 *
 * @NOTE: In practice, this file will typically be INCLUDED in your source
 * control, so do not include passwords or other sensitive information in this
 * file.
 */

$storageConfig = array(
    'mysql' => array(
        'driver'   => 'Pdo',
        'dsn'            => 'mysql:dbname=<db_name>;host=localhost',
        'driver_options' => array(
            PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES \'UTF8\''
        ),
        'username'       => '<your_db_username>',
        'password'       => '<your_db_password>'
    ),
    'container_elastic_config' => array(
        'localhost:9200',
    ),
    'ttl' => '60m', //(ms = miliseconds, m = minutes, h = hours, d = days, w = weeks)
    'storage_size' => "10240" //octets
);

return $storageConfig;