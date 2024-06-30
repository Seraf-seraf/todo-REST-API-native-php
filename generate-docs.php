<?php
require("./vendor/autoload.php");

$openapi = \OpenApi\Generator::scan(['./app']);

header('Content-Type: application/x-yaml');
$yml = $openapi->toYaml();

file_put_contents('docs/openapi.yml', $yml);
