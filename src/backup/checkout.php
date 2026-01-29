<?php

// Inicia a sessão se ainda não estiver iniciada
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require __DIR__ . '/../../vendor/autoload.php';

use MercadoPago\MercadoPagoConfig;
use MercadoPago\Client\Preference\PreferenceClient;
use MercadoPago\Client\Common\RequestOptions;

// Token TESTE - considere usar variáveis de ambiente em produção
MercadoPagoConfig::setAccessToken(
    'APP_USR-3062807951694957-071816-031478e20b803ad8b4db6ee99d0d11a5-1563056260'
);
MercadoPagoConfig::setRuntimeEnviroment(MercadoPagoConfig::LOCAL);

// Verifica se o carrinho existe e não está vazio
if (!isset($_SESSION['cart']) || empty($_SESSION['cart'])) {
    die('Carrinho vazio');
}

// Converte os itens do carrinho para o formato do Mercado Pago
$items = [];

foreach ($_SESSION['cart'] as $product) {
    // Mapeia campos do carrinho em português para o Mercado Pago
    $items[] = [
        'id' => $product['id'] ?? uniqid(),
        'title' => $product['nome'] ?? 'Produto sem nome',
        'quantity' => (int)($product['qtd'] ?? 1),
        'currency_id' => 'BRL',
        'unit_price' => (float)($product['preco'] ?? 0.00)
    ];

    // Verifica se os preços estão corretos
    if (!isset($product['preco']) || $product['preco'] <= 0) {
        die('Erro: Preço inválido para o produto ' . ($product['nome'] ?? 'desconhecido'));
    }
}

// Opcional: Adicionar um log para verificar os itens
error_log('Itens do carrinho: ' . print_r($items, true));

// Configuração de headers para evitar problemas de idempotência
$requestOptions = new RequestOptions();
$requestOptions->setCustomHeaders([
    'X-Idempotency-Key: ' . uniqid()
]);

$client = new PreferenceClient();

try {
    // Cria a preferência de pagamento
    $preferenceData = [
        "items" => $items,
        "back_urls" => [
            "success" => "https://theawis.com.br/sucesso.php",
            "failure" => "https://theawis.com.br/erro.php",
            "pending" => "https://theawis.com.br/pendente.php"
        ],
        "auto_return" => "approved",
        "external_reference" => uniqid("pedido_"),
        "notification_url" => "https://theawis.com.br/notificacoes.php",
        "statement_descriptor" => "NOME DA SUA LOJA"
    ];

    $preference = $client->create($preferenceData, $requestOptions);

    // Redireciona para a página de pagamento do Mercado Pago
    header("Location: " . $preference->init_point);
    exit;
} catch (Exception $e) {
    echo "<h2>Erro ao criar preferência de pagamento</h2>";
    echo "<p><strong>Mensagem:</strong> " . htmlspecialchars($e->getMessage()) . "</p>";
    if ($e instanceof \MercadoPago\Exceptions\MPApiException) {
        echo "<p><strong>Status:</strong> " . $e->getApiResponse()->getStatusCode() . "</p>";
        echo "<p><strong>Resposta da API:</strong></p>";
        echo "<pre>" . print_r($e->getApiResponse()->getContent(), true) . "</pre>";
    }
    echo "<h3>Itens que tentaram ser enviados:</h3>";
    echo "<pre>" . print_r($items, true) . "</pre>";
}
