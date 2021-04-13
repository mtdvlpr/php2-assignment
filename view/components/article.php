<?php
require_once __DIR__ . '/../../model/article.php';

/**
 * The view component for an article on the website.
 */
class Article
{
    public function __construct(
        private articleModel $article
    ) {
    }

    public function render(): void
    {
        $title = $this->article->getTitle();
        $content = $this->article->getContent();
        $extraContent = $this->article->getExtraContent();

        echo "
            <article>
                <h1 class='h4'>$title</h2>
                <img src='/img/icons/favicon.ico' alt='Logo'/>
                <p>$content</p>
        ";

        if ($extraContent != null) {
            echo "<p>$extraContent</p>";
        }

        echo "</article>";
    }
}
