<?php

use Doctrine\Common\Annotations\AnnotationRegistry;
use Doctrine\ODM\MongoDB\Configuration;
use Doctrine\ODM\MongoDB\DocumentManager;
use Doctrine\ODM\MongoDB\Mapping\Driver\AnnotationDriver;
use Documents\Benchmark;
use MongoDB\Client;

if (!file_exists($file = __DIR__ . '/vendor/autoload.php')) {
    throw new RuntimeException('Install dependencies to run this script.');
}

$loader = require_once $file;
$loader->add('Documents', __DIR__);

AnnotationRegistry::registerLoader([$loader, 'loadClass']);

$config = new Configuration();
$config->setProxyDir('Proxies');
$config->setProxyNamespace('Proxies');
$config->setHydratorDir('Hydrators');
$config->setHydratorNamespace('Hydrators');
$config->setMetadataDriverImpl(AnnotationDriver::create('/path/to/document/classes'));

$client = new Client('mongodb://127.0.0.1', [], ['typeMap' => DocumentManager::CLIENT_TYPEMAP]);
$dm = DocumentManager::create($client, $config);

for ($j = 0; $j < 10; $j++) {
    $dm->getDocumentCollection(Benchmark::class)->deleteMany([]);

    $startTime = microtime(true);
    for ($i = 0; $i < 10000; $i++) {
        $benchmark = new Benchmark('Peter ' . $i);
        $dm->persist($benchmark);
    }
    $dm->flush();

    echo "insert " . ((microtime(true) - $startTime) * 1000) . "\n";

    $dm->clear();
    $startTime = microtime(true);

    $repository = $dm->getRepository(Benchmark::class);
    foreach ($repository->findAll() as $item) {

    }

    echo "fetch " . ((microtime(true) - $startTime) * 1000) . "\n";
}
