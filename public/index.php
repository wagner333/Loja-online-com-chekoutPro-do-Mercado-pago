<?php
// INÍCIO DO BUFFER (tem que ser a primeira coisa do arquivo)
ob_start();
// Sessão sempre depois do ob_start
session_start();
require __DIR__ . '/../src/functions/isAdminLoggedIn.php';
require __DIR__ . '/../src/functions/adminLogin.php';
require __DIR__ . '/../src/functions/adminLogout.php';
require __DIR__ . '/../src/functions/add_cart.php';
require __DIR__ . '/../src/functions/delete_cart.php';
$produtos = require __DIR__ . '/../src/functions/products.php';
$maisProdutos = require __DIR__ . '/../src/functions/more_products.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['admin_login'])) {
    if (adminLogin($_POST['username'] ?? '', $_POST['password'] ?? '')) {
        header('Location: /admin');
        exit;
    } else {
        $login_error = "Credenciais inválidas";
    }
}

if (isset($_GET['logout'])) {
    adminLogout();
    header('Location: /admin-login');
    exit;
}
$action = rtrim(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH), '/');
$action = $action === '' ? '/' : $action;
addCart();
deleteCart();
$routes = require __DIR__ . '/../src/routers/router.php';

$isAdminRoute = strpos($action, '/admin') === 0;
if ($isAdminRoute && $action !== '/admin-login' && !isAdminLoggedIn()) {
    header('Location: /admin-login');
    exit;
}

if ($action === '/admin-login' && isAdminLoggedIn()) {
    header('Location: /admin');
    exit;
}
?>
<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <title>Nora<?php echo $isAdminRoute ? ' - Admin' : ''; ?></title>
    <link rel="stylesheet" href="/style/style.css">
    <script src="https://kit.fontawesome.com/2fd24895a9.js" crossorigin="anonymous"></script>
</head>

<body>

    <?php if (!$isAdminRoute): ?>
    <?php include __DIR__ . '/../src/includes/header.php'; ?>
    <?php else: ?>

    <header style="background: #333; color: white; padding: 10px;">
        <h2>Painel Admin</h2>
        <?php if (isAdminLoggedIn()): ?>
        <div style="float: right;">
            <span>Olá, <?php echo htmlspecialchars($_SESSION['admin_username'] ?? 'Admin'); ?></span>
            <a href="?logout=1" style="color: white; margin-left: 20px;">Sair</a>
        </div>
        <?php endif; ?>
    </header>


    <?php endif; ?>

    <?php

    if (isset($routes[$action])) {
        include $routes[$action];
    } elseif (preg_match('/^\/categoria\/([a-zA-Z0-9-_]+)$/', $action, $matches)) {
        $slugCategoria = $matches[1];
        include __DIR__ . '/../src/views/categoria.php';
    } else {

        http_response_code(404);
        echo '<h1>404 - Página não encontrada</h1>';
    }
    ?>

    <?php if (!$isAdminRoute): ?>
    <?php include __DIR__ . '/../src/includes/footer.php'; ?>
    <?php endif; ?>

    <script src="/js/script.js"></script>

</body>

</html>

<?php
ob_end_flush();