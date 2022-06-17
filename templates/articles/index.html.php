<main>
    <a href="index.php"><img src="public/images/ressources/logo-transparent-remake.png" alt="Logo 4chan remake" id="logo"></a>

    <div class="box-container">

        <div class="box-top">
            <h2>Qu'est ce que 4chan remake</h2>
        </div>

        <div class="box-main">
            <p>4chan remake vise à répliquer le site très connu 4chan. Développé en HTML/CSS/PHP par <a href="https://antonylanglois.com">Antony Langlois </a>étudiant à l'EEMI, ce site n'a pas vocation à être réellement utilisé mais développé en tant qu'entraînement et pour le plaisir :)</p>
        </div>

    </div>


    <div class="box-container">

        <div class="box-top">
            <h2>Planches</h2>
        </div>

        <div class="box-main">

            <?php foreach ($categories as $key => $value) : ?>

                <a href="index.php?controller=article&task=category&category=<?= $value ?>" class="box-item"><?= $key ?></a>

            <?php endforeach ?>

        </div>

    </div>


    <div class="box-container">

        <div class="box-top">
            <h2>Derniers Posts</h2>
        </div>

        <div class="box-main-post">

            <?php foreach ($articles as $article) : ?>

                <div class="post">

                    <a href="index.php?controller=article&task=category&category=<?= $article['category'] ?>" class="category"><?= $article['category'] ?></a>
                    <a href="index.php?controller=article&task=show&id=<?= $article['id'] ?>"><img src="<?= $article['image'] ?>" class="post-image"></a>
                    <div class="post-title">
                        <?php
                        if ((strlen($article['title']) > 50)) {
                            echo (substr($article['title'], 0, 50) . "...");
                        } else {
                            echo ($article['title']);
                        } ?>
                    </div>
                    <div class="post-content">
                        <?php
                        if ((strlen($article['content']) > 100)) {
                            echo (substr($article['content'], 0, 100) . "...");
                        } else {
                            echo ($article['content']);
                        } ?>
                    </div>

                </div>

            <?php endforeach ?>

        </div>

    </div>


    <div class="box-container">

        <div class="box-top">
            <h2>Stats</h2>
        </div>

        <div class="box-main-stats">

            <div class="box-item">
                <p><span class="totalposts">Images Totales : </span><?php
                                                                    $imagecounter = fopen('imagecounter.txt', 'r+');
                                                                    $image_number = fgets($imagecounter);
                                                                    echo ($image_number)
                                                                    ?></p>
            </div>

        </div>

    </div>
</main>