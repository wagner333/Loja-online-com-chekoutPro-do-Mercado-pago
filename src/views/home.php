<?php require __DIR__ . '/../includes/banners.php'; ?>

<div class="categorias">
    <?= require __DIR__ . '/../includes/category.php' ?>
</div>

<h1 style="text-align: center;">Em Destaque</h1>
<div class="layout-loja">
    <div class="produtos">

        <?php foreach ($produtos as $produto): ?>
        <?php if (!$produto['ativo']) continue; ?>

        <div class="produto-card" data-id="<?= (int) $produto['id'] ?>"
            data-nome="<?= htmlspecialchars($produto['nome']) ?>"
            data-preco="<?= number_format($produto['preco'], 2, ',', '.') ?>"
            data-descricao="<?= htmlspecialchars($produto['descricao']) ?>"
            data-imagens='<?= json_encode($produto["imagens"]) ?>' data-cores='<?= json_encode($produto["cores"]) ?>'
            data-tamanhos='<?= json_encode($produto["tamanhos"]) ?>'>
            <img src="/img/<?= htmlspecialchars($produto['imagens'][0]) ?>"
                alt="<?= htmlspecialchars($produto['nome']) ?>">

            <h3><?= htmlspecialchars($produto['nome']) ?></h3>

            <p class="produto-preco">
                R$ <?= number_format($produto['preco'], 2, ',', '.') ?>
            </p>

        </div>

        <?php endforeach; ?>

    </div>
</div>

<?php require __DIR__ . '/../includes/modal-comprar.php'; ?>

<!-- essa parte depois eu mecho vou organizar a de cima-->
<section class="mais-produtos">
    <h2 style="text-align: center;">Você também pode gostar</h2>

    <div class="produtos">
        <?php foreach ($maisProdutos as $produto): ?>
        <?php if (!$produto['ativo']) continue; ?>

        <div class="produto-card" data-id="<?= $produto['id'] ?>" data-nome="<?= $produto['nome'] ?>"
            data-preco="<?= number_format($produto['preco'], 2, ',', '.') ?>"
            data-descricao="<?= $produto['descricao'] ?>" data-imagens='<?= json_encode($produto["imagens"]) ?>'>

            <div class="produto-imagem">
                <img src="/img/<?= $produto['imagens'][0] ?>" alt="<?= $produto['nome'] ?>">
            </div>

            <h3><?= $produto['nome'] ?></h3>
            <p class="produto-preco">
                R$ <?= number_format($produto['preco'], 2, ',', '.') ?>
            </p>

        </div>
        <?php endforeach; ?>
    </div>
</section>