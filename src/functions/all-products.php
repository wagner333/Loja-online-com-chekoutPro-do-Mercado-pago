<?php

$produtos = require __DIR__ . '/products.php';
$cursos   = require __DIR__ . '/more_products.php';

return array_merge($produtos, $cursos);