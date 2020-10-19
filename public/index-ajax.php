<?php

/** @noinspection PhpIncludeInspection */

use System\App;

require ($path = dirname(__DIR__)) . '/vendor/autoload.php';

App::create($path)
    ->setRegistry();

require $path . '/ajax/System/Bootstrap.php';

\Ajax\System\Bootstrap::run();
