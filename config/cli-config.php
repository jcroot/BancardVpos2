<?php

require_once (__DIR__ . '/../bootstrap.php');

use Doctrine\ORM\Tools\Console\ConsoleRunner;

$ent = getEntityManager();
return ConsoleRunner::createHelperSet($ent);