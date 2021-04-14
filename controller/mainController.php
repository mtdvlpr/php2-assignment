<?php
// Models
require_once __DIR__ . '/../model/article.php';
require_once __DIR__ . '/../model/form.php';
require_once __DIR__ . '/../model/field.php';
require_once __DIR__ . '/../model/user.php';

// Views
require_once __DIR__ . '/../view/components/field.php';

class MainController
{
  public function __construct()
  {
  }

  public function getHomepage(?userModel $user): array
  {
    return [
      "title" => "Home",
      "user" => $user,
      "asideArticles" => [
        ArticleModel::get('about'),
        ArticleModel::get('contact')
      ],
      "mainArticles" => [
        new ArticleModel(
          'Welcome!',
          "How great that you're visiting our website! We want you to be able to enjoy the rich culture of the movie industry.",
          $user == null ? null : '<a href="/register">Create an account</a> to get a more complete experience. With an account you can do, see and interact more!'
        ),
        ArticleModel::get('collection')
      ]
      ];
  }

  public function getAboutPage(?UserModel $user) {
    return [
      "title" => "About Us",
      "user" => $user,
      "asideArticles" => [
        ArticleModel::get('collection')
      ],
      "mainArticles" => [
        new ArticleModel(
          'Who Are We?',
          "We are a company dedicated to providing everyone with a variety of amazing movies!"
        )
      ]
    ];
  }

  public function getContactPage(?UserModel $user) {
    return [
      "title" => "Home",
      "user" => $user,
      "asideArticles" => [
        ArticleModel::get('about'),
        ArticleModel::get('collection')
      ],
      "mainArticles" => [
        new FormModel(
          'Contact Us',
          [
            new Field(
              new FieldModel(
                'Email Address',
                'email',
                'email',
                'example@gmail.com',
                'email'
              )
            ),
            new Field(
              new FieldModel(
                'Name',
                'name',
                'name',
                'Francisco de Bernardo'
              )
            ),
            new Field(
              new FieldModel(
                'Subject',
                'subject',
                'subject',
                'Homepage layout'
              )
            ),
            new Field(
              new FieldModel(
                'Message',
                'msg',
                'msg',
                'I love it!!!',
                'textarea'
              )
            )
          ],
          'send',
          true
        )
      ]
    ];
  }
}