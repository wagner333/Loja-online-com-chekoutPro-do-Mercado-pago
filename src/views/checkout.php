<?php

require_once __DIR__ . '/../config/db.php';

$db = new Database();

// Verifica se há carrinho
if (empty($_SESSION['cart'])) {
    header('Location: /carrinho');
    exit;
}

// Define passo atual
$step = isset($_GET['step']) ? (int)$_GET['step'] : 1;
$steps = [
    1 => 'Informações',
    2 => 'Entrega',
    3 => 'Pagamento',
    4 => 'Confirmação'
];

// Calcula total
$total = 0;
foreach ($_SESSION['cart'] as $item) {
    $total += $item['preco'] * $item['qtd'];
}

// Se form enviado no passo 1
if ($_SERVER['REQUEST_METHOD'] === 'POST' && $step === 1) {
    // Valida dados pessoais
    $required = ['nome', 'email', 'telefone', 'cpf'];
    foreach ($required as $field) {
        if (empty($_POST[$field])) {
            $error = "Por favor, preencha todos os campos obrigatórios.";
            break;
        }
    }

    if (!isset($error)) {
        // Salva dados na sessão
        $_SESSION['checkout_data'] = [
            'nome' => $_POST['nome'],
            'email' => $_POST['email'],
            'telefone' => $_POST['telefone'],
            'cpf' => $_POST['cpf'],
            'endereco' => $_POST['endereco'] ?? '',
            'cidade' => $_POST['cidade'] ?? '',
            'estado' => $_POST['estado'] ?? '',
            'cep' => $_POST['cep'] ?? '',
            'observacoes' => $_POST['observacoes'] ?? ''
        ];

        // Vai para próximo passo
        header('Location: /checkout?step=2');
        exit;
    }
}

// Se form enviado no passo 2
if ($_SERVER['REQUEST_METHOD'] === 'POST' && $step === 2) {
    // Valida endereço
    $required = ['endereco', 'cidade', 'estado', 'cep'];
    foreach ($required as $field) {
        if (empty($_POST[$field])) {
            $error = "Por favor, preencha todos os campos do endereço.";
            break;
        }
    }

    if (!isset($error)) {
        // Atualiza dados na sessão
        $_SESSION['checkout_data'] = array_merge(
            $_SESSION['checkout_data'] ?? [],
            [
                'endereco' => $_POST['endereco'],
                'cidade' => $_POST['cidade'],
                'estado' => $_POST['estado'],
                'cep' => $_POST['cep'],
                'observacoes' => $_POST['observacoes'] ?? ''
            ]
        );

        // Vai para próximo passo
        header('Location: /checkout?step=3');
        exit;
    }
}

// Se form enviado no passo 3 (finalizar)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && $step === 3) {
    // Verifica se todos os dados estão completos
    $required = ['nome', 'email', 'telefone', 'cpf', 'endereco', 'cidade', 'estado', 'cep'];
    $checkoutData = $_SESSION['checkout_data'] ?? [];

    $missing = false;
    foreach ($required as $field) {
        if (empty($checkoutData[$field])) {
            $missing = true;
            break;
        }
    }

    if (!$missing) {
        // Salva cliente no banco
        $customerData = [
            'email' => $checkoutData['email'],
            'name' => $checkoutData['nome'],
            'phone' => $checkoutData['telefone'],
            'cpf' => $checkoutData['cpf'],
            'address' => $checkoutData['endereco'],
            'city' => $checkoutData['cidade'],
            'state' => $checkoutData['estado'],
            'zip_code' => $checkoutData['cep']
        ];

        $customerId = $db->saveCustomer($customerData);

        // Cria pedido no banco
        $orderData = [
            'customer_id' => $customerId,
            'customer_email' => $checkoutData['email'],
            'customer_name' => $checkoutData['nome'],
            'customer_phone' => $checkoutData['telefone'],
            'customer_cpf' => $checkoutData['cpf'],
            'address' => $checkoutData['endereco'],
            'city' => $checkoutData['cidade'],
            'state' => $checkoutData['estado'],
            'zip_code' => $checkoutData['cep'],
            'order_items' => $_SESSION['cart'],
            'total_amount' => $total,
            'observations' => $checkoutData['observacoes'] ?? null
        ];

        $orderId = $db->createOrder($orderData);

        // Salva ID do pedido na sessão
        $_SESSION['current_order_id'] = $orderId;

        // Redireciona para processamento do pagamento
        header('Location: /processar-pagamento');
        exit;
    } else {
        $error = "Por favor, complete todas as etapas anteriores.";
        $step = 1; // Volta para primeira etapa
    }
}

// Busca dados do cliente se existir
$customer = null;
if (isset($_SESSION['user_email'])) {
    $customer = $db->getCustomerByEmail($_SESSION['user_email']);
}

// Dados do formulário
$formData = $_SESSION['checkout_data'] ?? [];
?>
<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout - Etapa <?= $step ?> de 4</title>
    <style>
    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
    }

    body {
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        background-color: #f8f9fa;
        color: #333;
        line-height: 1.6;
    }

    .checkout-container {
        max-width: 1200px;
        margin: 0 auto;
        padding: 20px;
        display: grid;
        grid-template-columns: 1fr 400px;
        gap: 40px;
    }

    @media (max-width: 992px) {
        .checkout-container {
            grid-template-columns: 1fr;
        }
    }

    /* Progress Steps */
    .checkout-progress {
        grid-column: 1 / -1;
        margin-bottom: 40px;
    }

    .progress-steps {
        display: flex;
        justify-content: space-between;
        position: relative;
        margin-bottom: 30px;
    }

    .progress-steps::before {
        content: '';
        position: absolute;
        top: 15px;
        left: 0;
        right: 0;
        height: 2px;
        background: #e0e0e0;
        z-index: 1;
    }

    .progress-bar {
        position: absolute;
        top: 15px;
        left: 0;
        height: 2px;
        background: #3498db;
        z-index: 2;
        transition: width 0.3s ease;
        width: <?=(($step - 1) / 3) * 100 ?>;

    }

    .step {
        display: flex;
        flex-direction: column;
        align-items: center;
        position: relative;
        z-index: 3;
    }

    .step-number {
        width: 32px;
        height: 32px;
        border-radius: 50%;
        background: white;
        border: 2px solid #e0e0e0;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: bold;
        color: #999;
        margin-bottom: 8px;
        transition: all 0.3s ease;
    }

    .step.active .step-number {
        background: #3498db;
        border-color: #3498db;
        color: white;
    }

    .step.completed .step-number {
        background: #2ecc71;
        border-color: #2ecc71;
        color: white;
    }

    .step-label {
        font-size: 14px;
        color: #999;
        font-weight: 500;
    }

    .step.active .step-label {
        color: #3498db;
    }

    .step.completed .step-label {
        color: #2ecc71;
    }

    /* Main Content */
    .checkout-main {
        background: white;
        border-radius: 12px;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
        padding: 40px;
    }

    .checkout-main h2 {
        color: #2c3e50;
        margin-bottom: 30px;
        padding-bottom: 20px;
        border-bottom: 1px solid #eee;
        font-size: 1.8em;
    }

    /* Step Content */
    .step-content {
        display: none;
    }

    .step-content.active {
        display: block;
    }

    .form-group {
        margin-bottom: 25px;
    }

    label {
        display: block;
        margin-bottom: 8px;
        font-weight: 600;
        color: #495057;
    }

    .required::after {
        content: " *";
        color: #e74c3c;
    }

    input[type="text"],
    input[type="email"],
    input[type="tel"],
    textarea,
    select {
        width: 100%;
        padding: 14px;
        border: 2px solid #e0e0e0;
        border-radius: 8px;
        font-size: 16px;
        transition: all 0.3s ease;
        background: #fafafa;
    }

    input:focus,
    textarea:focus,
    select:focus {
        outline: none;
        border-color: #3498db;
        background: white;
        box-shadow: 0 0 0 3px rgba(52, 152, 219, 0.1);
    }

    .field-row {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 20px;
    }

    @media (max-width: 768px) {
        .field-row {
            grid-template-columns: 1fr;
        }
    }

    /* Navigation Buttons */
    .step-navigation {
        display: flex;
        justify-content: space-between;
        margin-top: 40px;
        padding-top: 30px;
        border-top: 1px solid #eee;
    }

    .btn {
        padding: 14px 32px;
        border: none;
        border-radius: 8px;
        font-size: 16px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s ease;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        min-width: 120px;
    }

    .btn-prev {
        background: #f8f9fa;
        color: #495057;
        border: 2px solid #e0e0e0;
    }

    .btn-prev:hover {
        background: #e9ecef;
        border-color: #adb5bd;
    }

    .btn-next {
        background: #3498db;
        color: white;
    }

    .btn-next:hover {
        background: #2980b9;
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(52, 152, 219, 0.3);
    }

    .btn-confirm {
        background: #2ecc71;
        color: white;
        width: 100%;
        padding: 16px;
        font-size: 18px;
    }

    .btn-confirm:hover {
        background: #27ae60;
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(46, 204, 113, 0.3);
    }

    /* Sidebar */
    .checkout-sidebar {
        position: sticky;
        top: 20px;
        height: fit-content;
    }

    .order-summary {
        background: white;
        border-radius: 12px;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
        padding: 30px;
        margin-bottom: 20px;
    }

    .order-summary h3 {
        color: #2c3e50;
        margin-bottom: 25px;
        padding-bottom: 15px;
        border-bottom: 1px solid #eee;
    }

    .order-items {
        max-height: 300px;
        overflow-y: auto;
        margin-bottom: 20px;
    }

    .order-item {
        display: flex;
        justify-content: space-between;
        padding: 12px 0;
        border-bottom: 1px solid #f0f0f0;
    }

    .order-item:last-child {
        border-bottom: none;
    }

    .item-info {
        display: flex;
        flex-direction: column;
    }

    .item-name {
        font-weight: 500;
        color: #2c3e50;
    }

    .item-qty {
        font-size: 14px;
        color: #7f8c8d;
    }

    .item-price {
        font-weight: 600;
        color: #2c3e50;
    }

    .order-totals {
        margin-top: 20px;
        padding-top: 20px;
        border-top: 2px solid #eee;
    }

    .total-row {
        display: flex;
        justify-content: space-between;
        margin-bottom: 10px;
    }

    .total-label {
        color: #7f8c8d;
    }

    .total-value {
        font-weight: 600;
        color: #2c3e50;
    }

    .final-total {
        font-size: 1.3em;
        font-weight: bold;
        color: #e74c3c;
        margin-top: 15px;
        padding-top: 15px;
        border-top: 2px solid #eee;
    }

    /* Customer Info Summary */
    .customer-summary {
        background: white;
        border-radius: 12px;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
        padding: 25px;
    }

    .customer-summary h4 {
        color: #2c3e50;
        margin-bottom: 15px;
        font-size: 16px;
    }

    .customer-details {
        font-size: 14px;
        color: #495057;
    }

    .customer-details p {
        margin-bottom: 8px;
    }

    .edit-link {
        color: #3498db;
        font-size: 14px;
        text-decoration: none;
        margin-top: 10px;
        display: inline-block;
    }

    .edit-link:hover {
        text-decoration: underline;
    }

    /* Error Message */
    .error-message {
        background: linear-gradient(135deg, #ff6b6b, #ee5a52);
        color: white;
        padding: 15px 20px;
        border-radius: 8px;
        margin-bottom: 25px;
        display: flex;
        align-items: center;
        animation: slideIn 0.3s ease;
    }

    .error-message::before {
        content: "⚠️";
        margin-right: 10px;
        font-size: 18px;
    }

    @keyframes slideIn {
        from {
            opacity: 0;
            transform: translateY(-10px);
        }

        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    /* Success Message */
    .success-message {
        background: linear-gradient(135deg, #2ecc71, #27ae60);
        color: white;
        padding: 15px 20px;
        border-radius: 8px;
        margin-bottom: 25px;
        display: flex;
        align-items: center;
    }

    .success-message::before {
        content: "✅";
        margin-right: 10px;
        font-size: 18px;
    }

    /* Shipping Options */
    .shipping-options {
        display: grid;
        gap: 15px;
    }

    .shipping-option {
        border: 2px solid #e0e0e0;
        border-radius: 8px;
        padding: 20px;
        cursor: pointer;
        transition: all 0.3s ease;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .shipping-option:hover {
        border-color: #3498db;
        background: #f8fafc;
    }

    .shipping-option.selected {
        border-color: #3498db;
        background: #f0f8ff;
    }

    .shipping-info h4 {
        margin: 0 0 5px 0;
        color: #2c3e50;
    }

    .shipping-info p {
        margin: 0;
        color: #7f8c8d;
        font-size: 14px;
    }

    .shipping-price {
        font-weight: bold;
        color: #2c3e50;
        font-size: 1.2em;
    }

    /* Payment Methods */
    .payment-methods {
        display: grid;
        gap: 15px;
    }

    .payment-method {
        border: 2px solid #e0e0e0;
        border-radius: 8px;
        padding: 20px;
        cursor: pointer;
        transition: all 0.3s ease;
        display: flex;
        align-items: center;
    }

    .payment-method:hover {
        border-color: #3498db;
        background: #f8fafc;
    }

    .payment-method.selected {
        border-color: #3498db;
        background: #f0f8ff;
    }

    .payment-icon {
        width: 40px;
        height: 40px;
        background: #f8f9fa;
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-right: 15px;
        font-size: 20px;
    }

    .payment-info h4 {
        margin: 0 0 5px 0;
        color: #2c3e50;
    }

    .payment-info p {
        margin: 0;
        color: #7f8c8d;
        font-size: 14px;
    }

    /* Responsive */
    @media (max-width: 768px) {
        .checkout-main {
            padding: 25px;
        }

        .progress-steps {
            flex-wrap: wrap;
            gap: 15px;
        }

        .step {
            flex: 1;
            min-width: 70px;
        }
    }
    </style>
</head>

<body>
    <div class="checkout-container">
        <!-- Progress Steps -->
        <div class="checkout-progress">
            <div class="progress-steps">
                <div class="progress-bar"></div>
                <?php for ($i = 1; $i <= 4; $i++): ?>
                <div class="step <?= $i < $step ? 'completed' : '' ?> <?= $i == $step ? 'active' : '' ?>">
                    <div class="step-number"><?= $i ?></div>
                    <div class="step-label"><?= $steps[$i] ?></div>
                </div>
                <?php endfor; ?>
            </div>
        </div>

        <!-- Main Content -->
        <div class="checkout-main">
            <?php if (isset($error)): ?>
            <div class="error-message"><?= htmlspecialchars($error) ?></div>
            <?php endif; ?>

            <!-- Step 1: Informações Pessoais -->
            <div class="step-content <?= $step == 1 ? 'active' : '' ?>" id="step1">
                <h2>Informações Pessoais</h2>
                <form method="post" action="/checkout?step=1">
                    <div class="form-group">
                        <label for="nome" class="required">Nome Completo</label>
                        <input type="text" id="nome" name="nome" required
                            value="<?= htmlspecialchars($formData['nome'] ?? $customer['name'] ?? '') ?>"
                            placeholder="Digite seu nome completo">
                    </div>

                    <div class="field-row">
                        <div class="form-group">
                            <label for="email" class="required">E-mail</label>
                            <input type="email" id="email" name="email" required
                                value="<?= htmlspecialchars($formData['email'] ?? $customer['email'] ?? '') ?>"
                                placeholder="seu@email.com">
                        </div>

                        <div class="form-group">
                            <label for="telefone" class="required">Telefone</label>
                            <input type="tel" id="telefone" name="telefone" required
                                value="<?= htmlspecialchars($formData['telefone'] ?? $customer['phone'] ?? '') ?>"
                                placeholder="(11) 99999-9999">
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="cpf" class="required">CPF</label>
                        <input type="text" id="cpf" name="cpf" required
                            value="<?= htmlspecialchars($formData['cpf'] ?? $customer['cpf'] ?? '') ?>"
                            placeholder="000.000.000-00">
                    </div>

                    <div class="step-navigation">
                        <a href="/carrinho" class="btn btn-prev">
                            ← Voltar ao Carrinho
                        </a>
                        <button type="submit" class="btn btn-next">
                            Continuar para Entrega →
                        </button>
                    </div>
                </form>
            </div>

            <!-- Step 2: Endereço de Entrega -->
            <div class="step-content <?= $step == 2 ? 'active' : '' ?>" id="step2">
                <h2>Endereço de Entrega</h2>
                <form method="post" action="/checkout?step=2">
                    <div class="form-group">
                        <label for="cep" class="required">CEP</label>
                        <input type="text" id="cep" name="cep" required
                            value="<?= htmlspecialchars($formData['cep'] ?? $customer['zip_code'] ?? '') ?>"
                            placeholder="00000-000" onblur="buscarCEP(this.value)">
                    </div>

                    <div class="form-group">
                        <label for="endereco" class="required">Endereço</label>
                        <input type="text" id="endereco" name="endereco" required
                            value="<?= htmlspecialchars($formData['endereco'] ?? $customer['address'] ?? '') ?>"
                            placeholder="Rua, número, complemento">
                    </div>

                    <div class="field-row">
                        <div class="form-group">
                            <label for="cidade" class="required">Cidade</label>
                            <input type="text" id="cidade" name="cidade" required
                                value="<?= htmlspecialchars($formData['cidade'] ?? $customer['city'] ?? '') ?>"
                                placeholder="Cidade">
                        </div>

                        <div class="form-group">
                            <label for="estado" class="required">Estado</label>
                            <select id="estado" name="estado" required>
                                <option value="">Selecione...</option>
                                <?php
                                $estados = [
                                    'AC' => 'Acre',
                                    'AL' => 'Alagoas',
                                    'AP' => 'Amapá',
                                    'AM' => 'Amazonas',
                                    'BA' => 'Bahia',
                                    'CE' => 'Ceará',
                                    'DF' => 'Distrito Federal',
                                    'ES' => 'Espírito Santo',
                                    'GO' => 'Goiás',
                                    'MA' => 'Maranhão',
                                    'MT' => 'Mato Grosso',
                                    'MS' => 'Mato Grosso do Sul',
                                    'MG' => 'Minas Gerais',
                                    'PA' => 'Pará',
                                    'PB' => 'Paraíba',
                                    'PR' => 'Paraná',
                                    'PE' => 'Pernambuco',
                                    'PI' => 'Piauí',
                                    'RJ' => 'Rio de Janeiro',
                                    'RN' => 'Rio Grande do Norte',
                                    'RS' => 'Rio Grande do Sul',
                                    'RO' => 'Rondônia',
                                    'RR' => 'Roraima',
                                    'SC' => 'Santa Catarina',
                                    'SP' => 'São Paulo',
                                    'SE' => 'Sergipe',
                                    'TO' => 'Tocantins'
                                ];
                                foreach ($estados as $sigla => $nome):
                                    $selected = ($formData['estado'] ?? $customer['state'] ?? '') == $sigla ? 'selected' : '';
                                ?>
                                <option value="<?= $sigla ?>" <?= $selected ?>><?= $nome ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="observacoes">Observações (opcional)</label>
                        <textarea id="observacoes" name="observacoes" rows="3"
                            placeholder="Instruções especiais para entrega"><?=
                                                                            htmlspecialchars($formData['observacoes'] ?? '') ?></textarea>
                    </div>

                    <div class="step-navigation">
                        <a href="/checkout?step=1" class="btn btn-prev">
                            ← Voltar
                        </a>
                        <button type="submit" class="btn btn-next">
                            Continuar para Pagamento →
                        </button>
                    </div>
                </form>
            </div>

            <!-- Step 3: Pagamento -->
            <div class="step-content <?= $step == 3 ? 'active' : '' ?>" id="step3">
                <h2>Método de Pagamento</h2>
                <form method="post" action="/checkout?step=3">
                    <div class="payment-methods">
                        <div class="payment-method selected" onclick="selectPayment('mercado_pago')">
                            <div class="payment-icon">💳</div>
                            <div class="payment-info">
                                <h4>Mercado Pago</h4>
                                <p>Cartão de crédito, débito, boleto ou PIX</p>
                            </div>
                        </div>

                        <div class="payment-method" onclick="selectPayment('pix')">
                            <div class="payment-icon">🏧</div>
                            <div class="payment-info">
                                <h4>PIX</h4>
                                <p>Pagamento instantâneo com QR Code</p>
                            </div>
                        </div>

                        <div class="payment-method" onclick="selectPayment('boleto')">
                            <div class="payment-icon">📄</div>
                            <div class="payment-info">
                                <h4>Boleto Bancário</h4>
                                <p>Pagamento em qualquer banco</p>
                            </div>
                        </div>
                    </div>

                    <input type="hidden" id="payment_method" name="payment_method" value="mercado_pago">

                    <div class="step-navigation">
                        <a href="/checkout?step=2" class="btn btn-prev">
                            ← Voltar
                        </a>
                        <button type="submit" class="btn btn-confirm">
                            Finalizar Pedido e Pagar
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="checkout-sidebar">
            <!-- Resumo do Pedido -->
            <div class="order-summary">
                <h3>Resumo do Pedido</h3>
                <div class="order-items">
                    <?php foreach ($_SESSION['cart'] as $id => $item):
                        $subtotal = $item['preco'] * $item['qtd'];
                    ?>
                    <div class="order-item">
                        <div class="item-info">
                            <span class="item-name"><?= htmlspecialchars($item['nome']) ?></span>
                            <span class="item-qty">Quantidade: <?= $item['qtd'] ?></span>
                        </div>
                        <div class="item-price">
                            R$ <?= number_format($subtotal, 2, ',', '.') ?>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>

                <div class="order-totals">
                    <div class="total-row">
                        <span class="total-label">Subtotal</span>
                        <span class="total-value">R$ <?= number_format($total, 2, ',', '.') ?></span>
                    </div>
                    <div class="total-row">
                        <span class="total-label">Frete</span>
                        <span class="total-value">Grátis</span>
                    </div>
                    <div class="final-total">
                        <span class="total-label">Total</span>
                        <span class="total-value">R$ <?= number_format($total, 2, ',', '.') ?></span>
                    </div>
                </div>
            </div>

            <!-- Resumo dos Dados -->
            <?php if (!empty($formData)): ?>
            <div class="customer-summary">
                <h4>Seus Dados</h4>
                <div class="customer-details">
                    <?php if (!empty($formData['nome'])): ?>
                    <p><strong>Nome:</strong> <?= htmlspecialchars($formData['nome']) ?></p>
                    <?php endif; ?>
                    <?php if (!empty($formData['email'])): ?>
                    <p><strong>E-mail:</strong> <?= htmlspecialchars($formData['email']) ?></p>
                    <?php endif; ?>
                    <?php if (!empty($formData['telefone'])): ?>
                    <p><strong>Telefone:</strong> <?= htmlspecialchars($formData['telefone']) ?></p>
                    <?php endif; ?>
                    <?php if (!empty($formData['endereco'])): ?>
                    <p><strong>Endereço:</strong> <?= htmlspecialchars($formData['endereco']) ?></p>
                    <?php endif; ?>
                    <?php if (!empty($formData['cidade']) && !empty($formData['estado'])): ?>
                    <p><strong>Cidade/UF:</strong>
                        <?= htmlspecialchars($formData['cidade']) ?>/<?= htmlspecialchars($formData['estado']) ?></p>
                    <?php endif; ?>
                </div>
                <a href="/checkout?step=1" class="edit-link">Editar dados</a>
            </div>
            <?php endif; ?>
        </div>
    </div>
    <script>
    // Máscaras
    document.addEventListener('DOMContentLoaded', function() {
        // CPF
        const cpfInput = document.getElementById('cpf');
        if (cpfInput) {
            cpfInput.addEventListener('input', function(e) {
                let value = e.target.value.replace(/\D/g, '');
                if (value.length > 3) value = value.replace(/^(\d{3})(\d)/, '$1.$2');
                if (value.length > 6) value = value.replace(/^(\d{3})\.(\d{3})(\d)/, '$1.$2.$3');
                if (value.length > 9) value = value.replace(/^(\d{3})\.(\d{3})\.(\d{3})(\d)/,
                    '$1.$2.$3-$4');
                e.target.value = value.substring(0, 14);
            });
        }

        // Telefone
        const telInput = document.getElementById('telefone');
        if (telInput) {
            telInput.addEventListener('input', function(e) {
                let value = e.target.value.replace(/\D/g, '');
                if (value.length > 2) value = `(${value.substring(0,2)}) ${value.substring(2)}`;
                if (value.length > 7) value = value.replace(/(\d{5})(\d)/, '$1-$2');
                e.target.value = value.substring(0, 15);
            });
        }

        // CEP
        const cepInput = document.getElementById('cep');
        if (cepInput) {
            cepInput.addEventListener('input', function(e) {
                let value = e.target.value.replace(/\D/g, '');
                if (value.length > 5) value = `${value.substring(0,5)}-${value.substring(5)}`;
                e.target.value = value.substring(0, 9);
            });
        }
    });

    // Buscar CEP
    function buscarCEP(cep) {
        cep = cep.replace(/\D/g, '');
        if (cep.length === 8) {
            fetch(`https://viacep.com.br/ws/${cep}/json/`)
                .then(response => response.json())
                .then(data => {
                    if (!data.erro) {
                        document.getElementById('endereco').value = data.logradouro;
                        document.getElementById('cidade').value = data.localidade;
                        document.getElementById('estado').value = data.uf;
                    }
                })
                .catch(error => console.error('Erro ao buscar CEP:', error));
        }
    }

    // Selecionar método de pagamento
    function selectPayment(method) {
        document.getElementById('payment_method').value = method;

        // Remove selected de todos
        document.querySelectorAll('.payment-method').forEach(el => {
            el.classList.remove('selected');
        });

        // Adiciona selected ao clicado
        event.currentTarget.classList.add('selected');
    }

    // Validação antes de enviar
    document.querySelectorAll('form').forEach(form => {
        form.addEventListener('submit', function(e) {
            const requiredFields = this.querySelectorAll('[required]');
            let valid = true;

            requiredFields.forEach(field => {
                if (!field.value.trim()) {
                    valid = false;
                    field.style.borderColor = '#e74c3c';
                } else {
                    field.style.borderColor = '#3498db';
                }
            });

            if (!valid) {
                e.preventDefault();
                alert('Por favor, preencha todos os campos obrigatórios.');
            }
        });
    });
    </script>
</body>

</html>