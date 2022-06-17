<?php

class Renderer
{
    public static function render(string $path, array $variables = [])
    {
        extract($variables); // Créer des variables à partir des key dans un tableau associatif, $article = $article ..
        ob_start();
        require('templates/' . $path . '.html.php');
        $pageContent = ob_get_clean();

        require('templates/layout.html.php');
    }
}
