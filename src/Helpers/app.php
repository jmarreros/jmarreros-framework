<?php

use Jmarreros\App;
use Jmarreros\Container\Container;

function app(string $class =  App::class): mixed {
    return Container::resolve($class);
}

function singleton(string $class) {
    return Container::singleton($class);
}
