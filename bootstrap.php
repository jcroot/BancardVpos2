<?php

require_once(__DIR__ . '/vendor/autoload.php');

// include the composer autoloader for autoloading packages
use Doctrine\ORM\EntityManager;
use iRAP\Autoloader\Autoloader;

// set up an autoloader for loading classes that aren't in /vendor
// $classDirs is an array of all folders to load from
$classDirs = array(
    __DIR__,
    __DIR__ . '/entities',
);

new Autoloader($classDirs);

function getEntityManager(): EntityManager
{
    $entityManager = null;

    if ($entityManager === null) {
        $paths = array(__DIR__ . '/entities');
        $config = \Doctrine\ORM\Tools\Setup::createAnnotationMetadataConfiguration($paths);

        # set up configuration parameters for doctrine.
        # Make sure you have installed the php7.0-sqlite package.
        $connectionParams = array(
            'driver' => 'pdo_mysql',
            'user' => 'root',
            'password' => 'g3pboNMP',
            'host' => '127.0.0.1',
            'port' => 3306,
            'dbname' => 'cartdb',
        );

        $entityManager = EntityManager::create($connectionParams, $config);
    }

    return $entityManager;
}