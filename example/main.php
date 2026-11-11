<?php

declare(strict_types=1);

use ChrisDfr\Env\Env;

require str_replace("/", DIRECTORY_SEPARATOR, "../vendor/autoload.php");

try {
    Env::load("./conf");
}
catch(Exception $e) {
    echo $e->getMessage();
    exit(1);
}

echo Env::get("NOM") . PHP_EOL;
echo Env::get("ADRESSE") . PHP_EOL;
echo PHP_EOL;

var_dump(Env::getAll());