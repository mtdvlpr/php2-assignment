<?php
// Models
require_once __DIR__ . '/../model/article.php';
require_once __DIR__ . '/../model/twitter.php';
require_once __DIR__ . '/../model/form.php';
require_once __DIR__ . '/../model/field.php';
require_once __DIR__ . '/../model/user.php';

// DB
require_once __DIR__ . '/../db/contactDB.php';

// Views
require_once __DIR__ . '/../view/components/field.php';

use Dotenv\Dotenv;
$dotenv = Dotenv::createUnsafeImmutable(__DIR__ . '/../');
if (getenv('ENV') != 'production') {
  $dotenv->load();
}

class MainController
{
  private ContactDB $contactDB;

  public function __construct()
  {
    $this->contactDB = new ContactDB();
  }

  public function getHomepage(?userModel $user): array
  {
    return [
      "title" => "Home",
      "user" => $user,
      "asideArticles" => [
        ArticleModel::get('about'),
        ArticleModel::get('contact'),
        ArticleModel::get('collection')
      ],
      "mainArticles" => [
        new ArticleModel(
          'Welcome!',
          "How great that you're visiting our website! We want you to be able to enjoy the rich culture of the movie industry.",
          $user != null ? null : '<a href="/signup">Create an account</a> to get a more complete experience. With an account you can do, see and interact more!'
        ),
        new TwitterFeedModel()
      ]
      ];
  }

  public function getAboutPage(?UserModel $user): array
  {
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

  public function getDonatePage(?UserModel $user) : array
  {
    $fields = [];

    if ($user == null) {
      array_push(
        $fields,
        new Field(
          new FieldModel(
            'Email',
            'email',
            'email',
            'John.doe@example.com',
            'email'
          )
        ),
        new Field(
          new FieldModel(
            'Name',
            'name',
            'name',
            'John Doe'
          )
        )
      );
    }

    array_push(
      $fields,
      new Field(
        new FieldModel(
          'Amount',
          'amount',
          'amount',
          '50.00',
          'number'
        )
        ),
        new Field(
          new FieldModel(
            'Payment method',
            'method',
            'method',
            'Payment Method',
            'combo'
          )
        )
      );

    return [
      "title" => "Donate",
      "user" => $user,
      "asideArticles" => [
        ArticleModel::get('about'),
        ArticleModel::get('contact'),
        ArticleModel::get('collection')
      ],
      "mainArticles" => [
        new FormModel(
          'Donate',
          $fields,
          'Donate',
          false
        )
      ]
    ];
  }

  public function getContactPage(
    ?UserModel $user,
    ?string $email = null,
    ?string $name = null,
    ?string $subject = null,
    ?string $msg = null,
    ?string $captcha  = null
  ) : array
  {
    $content = null;
    $class = ' class="error"';

    if ($email != null) {
      if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $content = "$email is not a valid email address.";
      } else if (isset($captcha) && !empty($captcha)) {

        // Google secret API
        $secretAPIkey = getenv('CAPTCHA_SECRET');

        // reCAPTCHA response verification
        $verifyResponse = file_get_contents('https://www.google.com/recaptcha/api/siteverify?secret=' . $secretAPIkey . '&response=' . $captcha);

        // Decode JSON data
        $response = json_decode($verifyResponse);
        if ($response->success) {
          try {

            // Add message to database
            $this->contactDB->addInformation($email, $name, $subject, $msg);

            // Send contact mail
            $mailer = new Mailer();
            $mailer->sendMail(
              subject: $subject,
              body: "From $name ($email):<br><br>$msg",
              address: getenv('MAIL_SENDER')
            );

            // Show user feedback
            $class = ' class="success"';
            $content = "Your message has been sent successfully.";
          } catch (Exception $error) {
            $content = $error->getMessage();
          }
        } else {
          $content = 'Robot verification failed, please try again.';
        }
      } else {
        $content = "Please check the reCAPTCHA box.";
      }
    }


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
          true,
          $content,
          $class
        )
      ]
    ];
  }
}
