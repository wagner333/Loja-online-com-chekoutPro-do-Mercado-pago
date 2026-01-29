<?php
// processar-pagamento.p
require_once __DIR__ . '/../config/db.php';
require __DIR__ . '/../../vendor/autoload.php';

use MercadoPago\MercadoPagoConfig;
use MercadoPago\Client\Preference\PreferenceClient;

// Configurações
MercadoPagoConfig::setAccessToken('TOKEN_MERCADO_PAGO');

// Verifica se há pedido na sessão
if (!isset($_SESSION['current_order_id']) || empty($_SESSION['cart'])) {
    header('Location: /carrinho');
    exit;
}

$db = new Database();

// Busca o pedido
$order = $db->getOrder($_SESSION['current_order_id']);
if (!$order) {
    die('Pedido não encontrado');
}

// Prepara itens para Mercado Pago
$items = [];
foreach ($_SESSION['cart'] as $product) {
    $items[] = [
        'id' => $product['id'] ?? uniqid(),
        'title' => $product['nome'] ?? 'Produto',
        'quantity' => (int)($product['qtd'] ?? 1),
        'currency_id' => 'BRL',
        'unit_price' => (float)($product['preco'] ?? 0.00)
    ];
}

// Cria preferência no Mercado Pago
try {
    $client = new PreferenceClient();

    $preferenceData = [
        "items" => $items,
        "payer" => [
            "name" => $order['customer_name'],
            "email" => $order['customer_email'],
            "phone" => [
                "number" => $order['customer_phone']
            ],
            "identification" => [
                "type" => "CPF",
                "number" => $order['customer_cpf']
            ]
        ],
        "back_urls" => [
            "success" => "https://theawis.com.br/sucesso.php?order_id=" . $order['id'],
            "failure" => "https://theawis.com.br/erro.php?order_id=" . $order['id'],
            "pending" => "https://theawis.com.br/pendente.php?order_id=" . $order['id']
        ],
        "auto_return" => "approved",
        "external_reference" => $order['id'], // ID do pedido no seu sistema
        "notification_url" => "https://theawis.com.br/notificacoes.php",
        "statement_descriptor" => "THEAWIS"
    ];

    $preference = $client->create($preferenceData);

    // Atualiza pedido com ID do Mercado Pago
    $db->updateOrderWithMercadoPagoId($order['id'], $preference->id);

    // Redireciona para o Mercado Pago
    header("Location: " . $preference->init_point);
    exit;
} catch (Exception $e) {
    echo "<h2>Erro ao processar pagamento</h2>";
    echo "<p>" . htmlspecialchars($e->getMessage()) . "</p>";
    echo '<a href="/checkout.php">Voltar ao checkout</a>';
}
