<?php

namespace Controllers;

use Http;

class Comment extends Controller
{

    protected $modelName = "\Models\Comment";

    public function insert()
    {
        /**
         * 1. On vérifie que les données ont bien été envoyées en POST
         * D'abord, on récupère les informations à partir du POST
         * Ensuite, on vérifie qu'elles ne sont pas nulles
         */
        // On commence par l'author
        try {
            $author = null;
            if (!empty($_POST['author'])) {
                $author = strip_tags($_POST['author']);
            } else {
                $author = "Anonymous";
            }

            // Ensuite le contenu
            $content = null;
            if (!empty($_POST['content'])) {
                // On fait quand même gaffe à ce que le gars n'essaye pas des balises cheloues dans son commentaire
                $content = strip_tags($_POST['content']);
            } else {
                throw new \Exception('Il manque un contenu');
            }

            if (!empty($_FILES['image'])) {
                $image = $_FILES['image'];
            }

            // Vérification finale des infos envoyées dans le formulaire (donc dans le POST)
            // Si il n'y a pas d'auteur OU qu'il n'y a pas de contenu OU qu'il n'y a pas d'identifiant d'article
            if (!$author || !$content) {
                throw new \Exception("Votre formulaire a été mal rempli !");
            }

            if (!empty($image) && $image['error'] == 0) {
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
                $image = "";
            }

            // Enfin l'id de l'article
            $article_id = null;
            if (!empty($_POST['post_id']) && ctype_digit($_POST['post_id'])) {
                $article_id = strip_tags($_POST['post_id']);
            } else {
                throw new \Exception("Il manque un numéro de post");
            }

            // Trouve l'article
            $articleModel = new \Models\Article();
            $article = $articleModel->find($article_id);

            // Si rien n'est revenu, on fait une erreur
            if (!$article) {
                throw new \Exception("Le post No.$article_id n'existe pas");
            }
        } catch (\Exception $e) {
            $errorMessage = $e->getMessage();
            $article_id = strip_tags($_POST['post_id']);
            Http::redirect("index.php?controller=article&task=show&id=$article_id&error=$errorMessage");
        }

        // 3. Insertion du commentaire
        $this->model->insert($author, $content, $article_id, $image);

        // 4. Redirection vers l'article en question :
        Http::redirect("index.php?controller=article&task=show&id=" . $article_id);
    }
}
