<?php
$produtos = require __DIR__ . '/../functions/products.php';

// slug vindo da rota
$categoriaSlug = strtolower($slugCategoria);

// filtrar produtos
$produtosCategoria = array_filter($produtos, function ($produto) use ($categoriaSlug) {
    return $produto['categoria'] === $categoriaSlug && $produto['ativo'];
});
?>

<section class="categoria-page">

    <h1 class="categoria-titulo" style="text-align: center;"><?= ucfirst($categoriaSlug) ?></h1>

    <?php if (empty($produtosCategoria)): ?>
    <p class="categoria-vazia">Nenhum produto encontrado nesta categoria.</p>
    <?php else: ?>
    <div class="produtos">
        <?php foreach ($produtosCategoria as $produto): ?>
        <div class="produto-card" data-id="<?= $produto['id'] ?>" data-nome="<?= $produto['nome'] ?>"
            data-preco="<?= number_format($produto['preco'], 2, ',', '.') ?>"
            data-descricao="<?= $produto['descricao'] ?>" data-imagens='<?= json_encode($produto['imagens']) ?>'>

            <img src="/img/<?= $produto['imagens'][0] ?>" alt="<?= $produto['nome'] ?>">
            <h3><?= $produto['nome'] ?></h3>
            <p>R$ <?= number_format($produto['preco'], 2, ',', '.') ?></p>
        </div>

        <?php endforeach; ?>
    </div>
    <?php endif; ?>

</section>
<?php require __DIR__ . '/../includes/modal-comprar.php'; ?>