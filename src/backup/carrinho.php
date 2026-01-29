<aside class="cart-drawer" id="cartDrawer">

    <div class="cart-header">
        <h2>Seu carrinho</h2>
        <button class="close-cart" id="closeCart">✕</button>
    </div>

    <div class="cart-content">
        <?php if (empty($_SESSION['cart'])): ?>
        <p class="empty-cart">Seu carrinho está vazio</p>
        <?php else: ?>
        <ul class="cart-items">
            <?php
                $total = 0;
                foreach ($_SESSION['cart'] as $id => $item):
                    $subtotal = $item['preco'] * $item['qtd'];
                    $total += $subtotal;
                ?>
            <li class="cart-item">
                <div class="item-info">

                    <strong><?= $item['nome'] ?></strong>
                    <span><?= $item['qtd'] ?>x R$ <?= number_format($item['preco'], 2, ',', '.') ?></span>
                </div>

                <form method="post" action="/remove-cart">
                    <input type="hidden" name="id" value="<?= $id ?>">
                    <button class="remove-item">✕</button>
                </form>
            </li>
            <?php endforeach; ?>
        </ul>
        <?php endif; ?>
    </div>

    <?php if (!empty($_SESSION['cart'])): ?>
    <div class="cart-footer">
        <p class="cart-total">
            Total:
            <strong>R$ <?= number_format($total, 2, ',', '.') ?></strong>
        </p>

        <form method="post" action="/checkout">
            <button class="checkout-button">
                Finalizar compra
            </button>
        </form>
    </div>
    <?php endif; ?>

</aside>