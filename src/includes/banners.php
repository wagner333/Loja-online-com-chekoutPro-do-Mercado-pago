<?php
$banners = [
    ['imagem' => 'banners/banner1.png', 'link' => '/produto/1'],
    ['imagem' => 'banners/banner2.png', 'link' => '/produto/2'],
    ['imagem' => 'banners/banner3.png', 'link' => '/produto/3']
];
?>

<section class="banner-slider">

    <div class="banner-track">
        <?php foreach ($banners as $index => $banner): ?>
            <a href="<?= $banner['link'] ?>" class="banner-slide <?= $index === 0 ? 'active' : '' ?>">
                <img src="/img/<?= $banner['imagem'] ?>" alt="Banner Promocional">
            </a>
        <?php endforeach; ?>
    </div>

    <!-- Controles -->
    <button class="banner-prev">‹</button>
    <button class="banner-next">›</button>

    <!-- Indicadores -->
    <div class="banner-dots">
        <?php foreach ($banners as $index => $banner): ?>
            <span class="dot <?= $index === 0 ? 'active' : '' ?>"></span>
        <?php endforeach; ?>
    </div>

</section>