<?php
return  [
    '/' => __DIR__ . '/../views/home.php',
    '/contato' => __DIR__ . '/../views/contato.php',
    '/sobre' => __DIR__ . '/../views/sobre.php',
    '/checkout' => __DIR__ . '/../views/checkout.php',
    '/processar-pagamento' => __DIR__ . '/../views/processar-pagamento.php',
    '/carrinho' => __DIR__ . '/../views/carrinho.php',

    // 🔐 ROTAS ADMIN (PROTEGIDAS)
    '/admin-login' => __DIR__ . '/../admin/login.php',
    '/admin' => __DIR__ . '/../admin/dashboard.php',
    '/admin/produtos' => __DIR__ . '/../admin/produtos.php',
    '/admin/usuarios' => __DIR__ . '/../admin/usuarios.php',
    '/admin/config' => __DIR__ . '/../admin/config.php',
    '/admin/logs' => __DIR__ . '/../admin/logs.php',
];