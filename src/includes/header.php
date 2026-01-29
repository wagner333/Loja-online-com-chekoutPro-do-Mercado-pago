<header class="header">
    <div class="header-container">

        <!-- Logo -->
        <h1 class="logo"><img src="/img/LOGO.png" alt="" width="auto" height="50px"></h1>

        <!-- Navegação -->
        <nav class="nav">
            <ul>
                <li><a href="/">Início</a></li>
                <li><a href="/sobre">Sobre</a></li>
                <li><a href="/contato">Contato</a></li>
            </ul>
        </nav>

        <!-- Botão do carrinho -->
        <button class="cart-button" id="openCart">
            <i class="fa-solid fa-cart-shopping"></i>
            <span class="cart-count">
                <?= isset($_SESSION['cart']) ? count($_SESSION['cart']) : 0 ?>
            </span>
        </button>

    </div>
    <div class="header-categories">
        <nav class="categories-nav">
            <ul>
                <li><a href="/categoria/roupas">Roupas</a></li>
                <li><a href="/categoria/relogios">Relógios</a></li>
                <li><a href="/categoria/carteiras">Carteiras</a></li>
                <li><a href="/categoria/acessorios">Acessórios</a></li>
            </ul>
        </nav>
    </div>
</header>

<!-- Overlay -->
<div class="cart-overlay" id="cartOverlay"></div>

<!-- Carrinho lateral -->
<?php require __DIR__ . '/../includes/carrinho.php'; ?>