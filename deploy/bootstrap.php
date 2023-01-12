<?php
use Doctrine\ORM\Tools\Setup;
use Doctrine\ORM\EntityManager;

use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;

$encoders = [new JsonEncoder()];
$normalizers = [new ObjectNormalizer()];
$serializer = new Serializer($normalizers, $encoders);
$jsonContent = $serializer->serialize($yourDoctrineObject, 'json');

date_default_timezone_set('America/Lima');
require_once "vendor/autoload.php";
$isDevMode = true;
$config = Setup::createYAMLMetadataConfiguration(array(__DIR__ . "/config/yaml"), $isDevMode);
$conn = array(
'host' => 'dpg-cedisa02i3mr7lh3is20-a.frankfurt-postgres.render.com',
'driver' => 'pdo_pgsql',
'user' => 'esattler',
'password' => 'Sir2okTfglXCpGOfQ9F5oyWptSC3T42P',
'dbname' => 'fruit_app',
'port' => '5432'
);
$entityManager = EntityManager::create($conn, $config);