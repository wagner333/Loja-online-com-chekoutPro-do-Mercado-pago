<?php
function deleteCart()
{
    global $action;

    if ($action === '/add-cart' && $_SERVER['REQUEST_METHOD'] === 'POST') {

        if (!isset($_SESSION['cart'])) {
            $_SESSION['cart'] = [];
        }

        $produtos = require __DIR__ . '/all-products.php';

        $id = (int) ($_POST['id'] ?? 0);

        foreach ($produtos as $produto) {
            if ($produto['id'] === $id) {

                if (isset($_SESSION['cart'][$id])) {
                    $_SESSION['cart'][$id]['qtd']++;
                } else {
                    $_SESSION['cart'][$id] = [
                        'nome'  => $produto['nome'],
                        'preco' => $produto['preco'],
                        'qtd'   => 1
                    ];
                }

                break;
            }
        }

        header('Location: /');
        exit;
    }
}