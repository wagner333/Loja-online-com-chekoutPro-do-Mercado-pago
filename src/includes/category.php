<?php
$categorias = [
    [
        'nome' => 'Chapéus',
        'imagem' => 'categorias/chapeus.jpg',
        'link' => '/categoria/chapeus'
    ],
    [
        'nome' => 'Relógios',
        'imagem' => 'categorias/relogio.webp',
        'link' => '/categoria/relogios'
    ],
    [
        'nome' => 'Acessórios',
        'imagem' => 'categorias/puceira.webp',
        'link' => '/categoria/acessorios'
    ],
    [
        'nome' => 'Streetwear',
        'imagem' => 'categorias/streetwear.webp',
        'link' => '/categoria/streetwear'
    ],

];
?>

<div class="categorias">
    <?php foreach ($categorias as $categoria): ?>
    <a href="<?= $categoria['link'] ?>" class="categoria-card">
        <img src="/img/<?= $categoria['imagem'] ?>" alt="<?= $categoria['nome'] ?>">
        <span><?= $categoria['nome'] ?></span>
    </a>
    <?php endforeach; ?>

</div>