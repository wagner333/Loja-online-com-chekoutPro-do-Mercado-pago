<?php

// Redireciona se carrinho vazio
if (empty($_SESSION['cart'])) {
    header('Location: /');
    exit;
}

// Calcula totais
$subtotal = 0;
$total_itens = 0;

foreach ($_SESSION['cart'] as $id => $item) {
    $subtotal += $item['preco'] * $item['qtd'];
    $total_itens += $item['qtd'];
}
?>
<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Carrinho de Compras</title>
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

    .cart-container {
        max-width: 1200px;
        margin: 40px auto;
        padding: 0 20px;
    }

    .cart-header {
        margin-bottom: 30px;
        padding-bottom: 15px;
        border-bottom: 2px solid #e9ecef;
    }

    .cart-header h1 {
        color: #2c3e50;
        font-size: 2.2em;
    }

    .cart-content {
        display: grid;
        grid-template-columns: 1fr 350px;
        gap: 40px;
    }

    @media (max-width: 992px) {
        .cart-content {
            grid-template-columns: 1fr;
        }
    }

    /* Lista de Produtos */
    .cart-items {
        background: white;
        border-radius: 10px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        overflow: hidden;
    }

    .cart-item {
        display: grid;
        grid-template-columns: 100px 1fr auto auto;
        align-items: center;
        gap: 20px;
        padding: 20px;
        border-bottom: 1px solid #e9ecef;
        transition: background-color 0.3s;
    }

    .cart-item:hover {
        background-color: #f8f9fa;
    }

    .cart-item:last-child {
        border-bottom: none;
    }

    .item-image {
        width: 100px;
        height: 100px;
        object-fit: cover;
        border-radius: 8px;
    }

    .item-details {
        flex: 1;
    }

    .item-name {
        font-size: 1.1em;
        font-weight: 600;
        color: #2c3e50;
        margin-bottom: 5px;
    }

    .item-price {
        color: #e74c3c;
        font-weight: bold;
        font-size: 1.2em;
    }

    .item-quantity {
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .qty-btn {
        width: 30px;
        height: 30px;
        background: #f8f9fa;
        border: 1px solid #ddd;
        border-radius: 4px;
        cursor: pointer;
        font-size: 16px;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: all 0.3s;
    }

    .qty-btn:hover {
        background: #e9ecef;
        border-color: #adb5bd;
    }

    .qty-input {
        width: 50px;
        height: 30px;
        text-align: center;
        border: 1px solid #ddd;
        border-radius: 4px;
        font-size: 16px;
    }

    .item-total {
        font-weight: bold;
        color: #2c3e50;
        font-size: 1.2em;
        min-width: 100px;
        text-align: right;
    }

    .remove-item {
        background: #e74c3c;
        color: white;
        border: none;
        width: 30px;
        height: 30px;
        border-radius: 50%;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: background-color 0.3s;
    }

    .remove-item:hover {
        background: #c0392b;
    }

    /* Resumo do Carrinho */
    .cart-summary {
        background: white;
        border-radius: 10px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        padding: 30px;
        height: fit-content;
        position: sticky;
        top: 20px;
    }

    .cart-summary h2 {
        color: #2c3e50;
        margin-bottom: 25px;
        padding-bottom: 15px;
        border-bottom: 2px solid #3498db;
    }

    .summary-row {
        display: flex;
        justify-content: space-between;
        margin-bottom: 15px;
        padding-bottom: 15px;
        border-bottom: 1px solid #e9ecef;
    }

    .summary-row:last-child {
        border-bottom: none;
    }

    .summary-label {
        color: #6c757d;
    }

    .summary-value {
        font-weight: 600;
        color: #2c3e50;
    }

    .summary-total {
        font-size: 1.3em;
        font-weight: bold;
        color: #e74c3c;
    }

    .summary-total .summary-value {
        font-size: 1.3em;
        color: #e74c3c;
    }

    .checkout-btn {
        display: block;
        width: 100%;
        padding: 16px;
        background: #2ecc71;
        color: white;
        text-align: center;
        text-decoration: none;
        border: none;
        border-radius: 8px;
        font-size: 18px;
        font-weight: 600;
        cursor: pointer;
        transition: background-color 0.3s;
        margin-top: 25px;
    }

    .checkout-btn:hover {
        background: #27ae60;
    }

    .continue-shopping {
        display: block;
        text-align: center;
        color: #3498db;
        text-decoration: none;
        margin-top: 15px;
        font-size: 16px;
    }

    .continue-shopping:hover {
        text-decoration: underline;
    }

    .empty-cart {
        text-align: center;
        padding: 60px 20px;
        background: white;
        border-radius: 10px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
    }

    .empty-cart-icon {
        font-size: 60px;
        color: #bdc3c7;
        margin-bottom: 20px;
    }

    .empty-cart h2 {
        color: #2c3e50;
        margin-bottom: 15px;
    }

    .empty-cart p {
        color: #7f8c8d;
        margin-bottom: 30px;
    }

    .empty-cart-btn {
        display: inline-block;
        padding: 12px 30px;
        background: #3498db;
        color: white;
        text-decoration: none;
        border-radius: 6px;
        font-weight: 600;
    }

    .empty-cart-btn:hover {
        background: #2980b9;
    }

    /* Ações do Carrinho */
    .cart-actions {
        display: flex;
        justify-content: space-between;
        margin-top: 30px;
        padding-top: 20px;
        border-top: 1px solid #e9ecef;
    }

    .clear-cart-btn {
        padding: 10px 20px;
        background: #95a5a6;
        color: white;
        border: none;
        border-radius: 6px;
        cursor: pointer;
        font-size: 16px;
        transition: background-color 0.3s;
    }

    .clear-cart-btn:hover {
        background: #7f8c8d;
    }

    @media (max-width: 768px) {
        .cart-item {
            grid-template-columns: 80px 1fr;
            grid-template-rows: auto auto;
            gap: 15px;
        }

        .item-quantity,
        .item-total,
        .remove-item {
            grid-column: 2;
            justify-self: start;
        }

        .item-image {
            width: 80px;
            height: 80px;
        }

        .cart-actions {
            flex-direction: column;
            gap: 15px;
        }
    }
    </style>
</head>

<body>
    <div class="cart-container">
        <div class="cart-header">
            <h1>Seu Carrinho</h1>
            <p><?= $total_itens ?> item<?= $total_itens != 1 ? 's' : '' ?> no carrinho</p>
        </div>

        <?php if (empty($_SESSION['cart'])): ?>
        <div class="empty-cart">
            <div class="empty-cart-icon"></div>

            <h2>Seu carrinho está vazio</h2>
            <p>Adicione produtos para continuar comprando</p>

            <a href="/" class="empty-cart-btn">
                Continuar comprando
            </a>
        </div>

        <?php else: ?>
        <div class="cart-content">

            <!-- Lista de Produtos -->
            <div class="cart-items">
                <?php foreach ($_SESSION['cart'] as $id => $item):
                        $item_total = $item['preco'] * $item['qtd'];
                        $imagem = $item['imagens'][0] ?? '/images/produto-sem-imagem.jpg';
                    ?>
                <div class="cart-item" id="item-<?= (int) $id ?>">


                    <div class="item-details">
                        <h3 class="item-name">
                            <?= htmlspecialchars($item['nome'], ENT_QUOTES, 'UTF-8') ?>
                        </h3>

                        <p class="item-price">
                            R$ <?= number_format($item['preco'], 2, ',', '.') ?>
                        </p>
                    </div>

                    <div class="item-quantity">
                        <button type="button" class="qty-btn" onclick="updateQuantity(<?= (int) $id ?>, -1)">
                            -
                        </button>

                        <input type="number" class="qty-input" id="qty-<?= (int) $id ?>"
                            value="<?= (int) $item['qtd'] ?>" min="1" max="99"
                            onchange="updateQuantity(<?= (int) $id ?>, 0, this.value)">

                        <button type="button" class="qty-btn" onclick="updateQuantity(<?= (int) $id ?>, 1)">
                            +
                        </button>
                    </div>

                    <div class="item-total">
                        R$ <?= number_format($item_total, 2, ',', '.') ?>
                    </div>

                    <button type="button" class="remove-item" onclick="removeItem(<?= (int) $id ?>)">
                        ×
                    </button>

                </div>
                <?php endforeach; ?>

                <div class="cart-actions">
                    <a href="/" class="continue-shopping">
                        Continuar comprando
                    </a>
                </div>
            </div>

            <!-- Resumo do Pedido -->
            <div class="cart-summary">
                <h2>Resumo do pedido</h2>

                <div class="summary-row">
                    <span class="summary-label">
                        Subtotal (<?= (int) $total_itens ?> itens)
                    </span>
                    <span class="summary-value">
                        R$ <?= number_format($subtotal, 2, ',', '.') ?>
                    </span>
                </div>

                <div class="summary-row">
                    <span class="summary-label">Frete</span>
                    <span class="summary-value">Calculado no checkout</span>
                </div>

                <div class="summary-row summary-total">
                    <span class="summary-label">Total</span>
                    <span class="summary-value">
                        R$ <?= number_format($subtotal, 2, ',', '.') ?>
                    </span>
                </div>

                <a href="/checkout" class="checkout-btn">
                    Finalizar compra
                </a>

                <p class="payment-note">
                    Pagamento seguro via Mercado Pago
                </p>
            </div>

        </div>
        <?php endif; ?>

    </div>

    <script>
    function updateQuantity(productId, change, specificValue = null) {
        let currentQty = parseInt(document.getElementById('qty-' + productId).value);
        let newQty;

        if (specificValue !== null) {
            newQty = parseInt(specificValue);
        } else {
            newQty = currentQty + change;
        }

        // Valida quantidade mínima e máxima
        if (newQty < 1) newQty = 1;
        if (newQty > 99) newQty = 99;

        // Atualiza via AJAX
        fetch('/update-cart-quantity', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: `id=${productId}&qtd=${newQty}`
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Recarrega a página para atualizar totais
                    location.reload();
                } else {
                    alert('Erro ao atualizar quantidade: ' + data.message);
                }
            })
            .catch(error => {
                console.error('Erro:', error);
                alert('Erro ao atualizar quantidade');
            });
    }

    function removeItem(productId) {
        if (confirm('Remover este item do carrinho?')) {
            fetch('/remove-from-cart', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded',
                    },
                    body: `id=${productId}`
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Remove o elemento da página
                        document.getElementById('item-' + productId).remove();
                        // Recarrega para atualizar totais
                        location.reload();
                    }
                });
        }
    }

    function clearCart() {
        if (confirm('Tem certeza que deseja limpar todo o carrinho?')) {
            fetch('/clear-cart', {
                    method: 'POST'
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        location.reload();
                    }
                });
        }
    }
    </script>
</body>

</html>