<p class="categories">
    [<?php foreach ($categories as $key => $value) : ?>

    <a href="index.php?controller=article&task=category&category=<?= $value ?>"><?= $key ?></a> /

    <?php endforeach ?>]
</p>
<header>

    <a href="index.php" class="return">Retour</a>
    <img src="<?= "public/images/ressources/" . mt_rand(0, 9) . ".jpg" ?>" class="poster">
    <h1><?php if ($category == "Animé Manga") {
            echo ("Animé & Manga");
        } elseif ($category == "Modes Tendances") {
            echo ("Modes & Tendances");
        } else {
            echo ($category);
        }
        ?></h1>
    <hr>

    <p>[Créer un nouveau post]</p>

    <table class="post-form">
        <form action="index.php?controller=article&task=insert&category=<?= $category ?>" method="POST" enctype="multipart/form-data">
            <tr>
                <td><label>Nom</label></td>
                <td><input type="text" name="author" placeholder="Anonymous"></td>
            </tr>
            <tr>
                <td><label>Titre</label></td>
                <td><input type="text" name="title"><button>Poster</button></td>
            </tr>
            <tr>
                <td><label>Contenu</label></td>
                <td><textarea name="content" id="" cols="30" rows="5"></textarea></td>
            </tr>
            <tr>
                <td><label>Image</label></td>
                <td><input type="file" name="image"></td>
            </tr>
        </form>
    </table>
    <ul class="rules">
        <li>Il y a <?= count($articles) ?> posts dans cette catégorie</li>
    </ul>

    <?php
    if (!empty($_GET['error'])) {
        echo ("<p class=\"error\">" . strip_tags($_GET['error']) . "</p>");
    }
    ?>

    <img src="https://source.unsplash.com/random/780x200" class="poster-two">

</header>

<hr>

<?php foreach ($articles as $article) : ?>

    <div class="post">

        <a href="<?= $article['image'] ?>"><img src="<?= $article['image'] ?>" class="post-image"></a>

        <div class="post-data">

            <p class="infos">
                <span class="title"><?php
                                    if ((strlen($article['title']) > 100)) {
                                        echo (substr($article['title'], 0, 100) . "...");
                                    } else {
                                        echo ($article['title']);
                                    } ?></span>

                <span class="author"><?= $article['author'] ?></span> <?= date('d/m/Y H:i:s', strtotime($article['creation_date'])) ?> No.<?= $article['id'] ?> [<a href="index.php?controller=article&task=show&id=<?= $article['id'] ?>">Répondre</a>]
            </p>

            <div class="post-title">

            </div>

            <div class="post-content">
                <?php
                if ((strlen($article['content']) > 300)) {
                    echo (substr($article['content'], 0, 300) . "...");
                } else {
                    echo ($article['content']);
                } ?>
            </div>

        </div>

    </div>

    <hr>

<?php endforeach ?>

<hr>

<div class="bottom-nav">[<a href="index.php">Retour</a>] [<a href="#top">Top</a>] [<a href data-cmd="update">Rafraîchir</a>]</div>

<hr>