<?php

namespace Controllers;

use Http;
use Renderer;

class Article extends Controller
{

    protected $modelName = "\Models\Article";

    protected $categories = ["Animé & Manga" => "Animé Manga", "Sport" => "Sport", "Musique" => "Musique", "Jeux vidéos" => "Jeux vidéos", "Nature" => "Nature", "Mode & Tendances" => "Modes Tendances", "Conseils" => "Conseils", "Photographie" => "Photographie", "Technologie" => "Technologie", "Internet" => "Internet"];

    public function index()
    {
        $categories = $this->categories;
        /**
         * 2. Récupération des articles
         */
        $articles = $this->model->findLasts();
        /**
         * 3. Affichage
         */
        $pageTitle = "Accueil";
        $css = "public/css/index.css";

        Renderer::render('articles/index', compact('pageTitle', 'css', 'categories', 'articles'));
    }

    public function category()
    {
        $category = null;

        if (!empty($_GET['category']) && in_array($_GET['category'], $this->categories)) {
            $category = strip_tags($_GET['category']);
        }

        $categories = $this->categories;

        if (!$category) {
            die("Cette catégorie n'existe pas");
        }
        /**
         * 2. Récupération des articles
         */
        $articles = $this->model->findAll($category);
        /**
         * 3. Affichage
         */
        $pageTitle = $category;
        $css = "public/css/category.css";

        Renderer::render('articles/category', compact('pageTitle', 'css', 'articles', 'category', 'categories'));
    }

    public function show()
    {
        $categories = $this->categories;

        $article_id = null;

        // Mais si il y'en a un et que c'est un nombre entier, alors c'est cool
        if (!empty($_GET['id']) && ctype_digit($_GET['id'])) {
            $article_id = strip_tags($_GET['id']);
        }

        // On peut désormais décider : erreur ou pas ?!
        if (!$article_id) {
            die("Ce post n'existe pas");
        }
        /**
         * 3. Récupération de l'article en question
         * On va ici utiliser une requête préparée car elle inclue une variable qui provient de l'utilisateur : Ne faites
         * jamais confiance à ce connard d'utilisateur ! :D
         */
        $article = $this->model->find($article_id);

        /**
         * 4. Récupération des commentaires de l'article en question
         * Pareil, toujours une requête préparée pour sécuriser la donnée filée par l'utilisateur (cet enfoiré en puissance !)
         */
        $commentairesModel = new \Models\Comment();
        $commentaires = $commentairesModel->findAllInArticle($article_id);

        /**
         * 5. On affiche 
         */
        $pageTitle = $article['title'] . " - No." . $article['id'];
        $css = "public/css/post.css";
        Renderer::render('articles/show', compact('pageTitle', 'css', 'article', 'commentaires', 'article_id', 'categories'));
    }

    public function insert()
    {
        /**
         * 1. On vérifie que les données ont bien été envoyées en POST
         * D'abord, on récupère les informations à partir du POST
         * Ensuite, on vérifie qu'elles ne sont pas nulles
         */
        // On commence par l'author
        $author = null;
        if (!empty($_POST['author'])) {
            $author = strip_tags($_POST['author']);
        } else {
            $author = "Anonymous";
        }

        // Ensuite le contenu
        try {
            $category = null;
            if (!empty($_GET['category'])) {
                $category = strip_tags($_GET['category']);
            } else {
                throw new \Exception('Il manque une catégorie');
            }

            // Enfin l'id de l'article
            $title = null;
            if (!empty($_POST['title'])) {
                $title = strip_tags($_POST['title']);
            } else {
                throw new \Exception('Il manque un titre');
            }

            $content = null;
            if (!empty($_POST['content'])) {
                // On fait quand même gaffe à ce que le gars n'essaye pas des balises cheloues dans son commentaire
                $content = strip_tags($_POST['content']);
            } else {
                throw new \Exception('Il manque un contenu');
            }

            if (!empty($_FILES['image'])) {
                $image = $_FILES['image'];
            } else {
                throw new \Exception('Il manque une image');
            }

            // Vérification finale des infos envoyées dans le formulaire (donc dans le POST)
            // Si il n'y a pas d'auteur OU qu'il n'y a pas de contenu OU qu'il n'y a pas d'identifiant d'article
            if (!$author || !$title || !$content || !$image || !$category) {
                throw new \Exception("Votre formulaire a été mal rempli !");
            }

            if (isset($image) && $image['error'] == 0) {
                if ($image['size'] <= (1048576 * 10)) {
                    $fileInfo = pathinfo($image['name']);
                    $extension = strip_tags($fileInfo['extension']);
                    $allowedExtensions = ['jpg', 'jpeg', 'png'];
                    if (in_array($extension, $allowedExtensions)) {
                        // Add 1 to imagecounter.txt
                        $imagecounter = fopen('imagecounter.txt', 'r+');
                        $image_number = fgets($imagecounter);
                        $image_number += 1;
                        fseek($imagecounter, 0);
                        fputs($imagecounter, $image_number);
                        fclose($imagecounter);

                        move_uploaded_file($image['tmp_name'], 'public/images/' . $image_number . '_' . strip_tags(basename($image['name'])));
                        $image =  ('./public/images/' . $image_number . '_' . strip_tags(basename($image['name'])));
                    } else {
                        throw new \Exception('Le format de l\'image n\'est pas correct (jpg,jpeg,png)');
                    }
                } else {
                    throw new \Exception('L\'image est trop grosse (10Mo max)');
                }
            } else {
                throw new \Exception('Il manque une image');
            }
        } catch (\Exception $e) {
            $errorMessage = $e->getMessage();
            $category = strip_tags($_GET['category']);
            Http::redirect("index.php?controller=article&task=category&category=$category&error=$errorMessage");
        }

        // 3. Insertion de l'article
        $this->model->insert($author, $content, $title, $image, $category);

        // 4. Redirection vers l'article en question :
        Http::redirect("index.php?controller=article&task=category&category=" . $category);
    }
}
