<p class="categories">
    [<?php foreach ($categories as $key => $value) : ?>

    <a href="index.php?controller=article&task=category&category=<?= $value ?>"><?= $key ?></a> /

    <?php endforeach ?>]
</p>
<header>

    <a href="index.php?controller=article&task=category&category=<?= $article['category'] ?>" class="return">Retour</a>
    <img src="<?= "public/images/ressources/" . mt_rand(0, 9) . ".jpg" ?>" class="poster">
    <h1><?php if ($article['category'] == "Animé Manga") {
            echo ("Animé & Manga");
        } elseif ($article['category'] == "Modes Tendances") {
            echo ("Modes & Tendances");
        } else {
            echo ($article['category']);
        }
        ?></h1>
    <hr>

    <p>[Répondre]</p>

    <table class="post-form">
        <form action="index.php?controller=comment&task=insert" method="POST" enctype="multipart/form-data">
            <tr>
                <td><label>Nom</label></td>
                <td><input type="text" name="author" placeholder="Anonymous"><button>Poster</button></td>
            </tr>
            <tr>
                <td><label>Contenu</label></td>
                <td><textarea name="content" id="" cols="30" rows="5"></textarea></td>
            </tr>
            <tr>
                <td><label>Image</label></td>
                <td><input type="file" name="image"></td>
            </tr>
            <input type="hidden" name="post_id" value="<?= $article['id'] ?>">
        </form>
    </table>
    <ul class="rules">
        <li>Il y a <?= count($commentaires) ?> posteurs sur ce post</li>
    </ul>

    <?php
    if (!empty($_GET['error'])) {
        echo ("<p class=\"error\">" . strip_tags($_GET['error']) . "</p>");
    }
    ?>

    <img src="https://source.unsplash.com/random/780x200" class="poster-two">

</header>

<hr>

<div class="post">

    <a href="<?= $article['image'] ?>"><img src="<?= $article['image'] ?>" class="post-image"></a>

    <div class="post-data">

        <p class="infos">
            <span class="title"><?php echo ($article['title']); ?></span>

            <span class="author"><?= $article['author'] ?></span> <?= date('d/m/Y H:i:s', strtotime($article['creation_date'])) ?> No.<?= $article['id'] ?>
        </p>

        <div class="post-title">

        </div>

        <div class="post-content">
            <?php echo ($article['content']); ?>
        </div>

    </div>

</div>

<hr>

<?php if (count($commentaires) === 0) : ?>
    <h2 class="comment_state">Il n'y a pas encore de commentaires pour ce post</h2>
<?php else : ?>
    <h2 class="comment_state"><?= count($commentaires) ?> Commentaires </h2>

    <?php foreach ($commentaires as $commentaire) : ?>
        <div class="comment">
            <p class="infos">
                <span class="author"><?= $commentaire['author'] ?></span> <?= date('d/m/Y H:i:s', strtotime($commentaire['creation_date'])) ?> No.<?= $commentaire['id'] ?>
            </p>

            <div class="post-content">
                <?php
                if ($commentaire['image']) {
                    echo ("<a href=\"" . $commentaire['image'] . "\"><img src=\"" . $commentaire['image'] . "\" class=\"post-image\"></a>");
                }
                ?>
                <p><?php echo ($commentaire['content']); ?></p>
            </div>
        </div>
    <?php endforeach ?>

<?php endif ?>

<hr>

<div class="bottom-nav">[<a href="index.php?controller=article&task=category&category=<?= $article['category'] ?>">Retour</a>] [<a href="#top">Top</a>] [<a href data-cmd="update">Rafraîchir</a>]</div>

<hr>