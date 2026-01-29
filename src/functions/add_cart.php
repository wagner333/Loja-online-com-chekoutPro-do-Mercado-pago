<?php

function addCart()
{
    global $action;
    if ($action === '/remove-cart' && $_SERVER['REQUEST_METHOD'] === 'POST') {

        $id = (int) $_POST['id'];

        if (isset($_SESSION['cart'][$id])) {
            unset($_SESSION['cart'][$id]);
        }

        header('Location: /');
        exit;
    }
}