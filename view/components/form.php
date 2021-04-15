<?php
require_once __DIR__ . '/../../model/form.php';

/**
 * The view component for an article on the website.
 */
class Form
{
  public function __construct(
  private formModel $form
    )
  {
  }

  public function render(): void
  {
    $title = $this->form->getTitle();
    $content = $this->form->getContent();
    $contentClass = $this->form->getContentClass();
    $fields = $this->form->getFields();
    $submit = $this->form->getSubmit();
    $extraContent = $this->form->getExtraContent();
    $method = $this->form->getMethod();
    $hasCaptcha = $this->form->getHasCaptcha();

    echo "<article class='form-container'><h1 class='h3'>$title</h1>";

    if ($content != null)  {
      $content = match($contentClass) {
        ' class="error"' => '<i class="fa fa-times-circle"></i> ' . $content,
        ' class="warning"' => '<i class="fa fa-warning"></i> ' . $content,
        ' class="success"' => '<i class="fa fa-check"></i> ' . $content,
        default => $content
      };
      echo "<p$contentClass>$content</p>";
    }

    echo "<form method='$method' autocomplete='off' enctype='multipart/form-data'>";

    foreach ($fields as $field) {
      $field->render();
    }

    if ($hasCaptcha) {
      echo '<div class="g-recaptcha" data-sitekey="6Lenh-MZAAAAANqwKEkTjSNDy6Q7XnreHObxUM1V"></div>';
    }

    if ($extraContent != null) {
      foreach (explode(';', $extraContent) as $line) {
        echo "<p>$line</p>";
      }
    }

    $submitName = $method == 'post' ? " name='submit'" : '';
    echo "<div class='row'>
            <button type='submit' class='submit'$submitName>$submit</button>
          </div>
        </form>
      </article>
    ";
  }
}
