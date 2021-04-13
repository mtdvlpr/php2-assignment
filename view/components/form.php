<?php
require_once __DIR__ . '/../../model/form.php';

/**
 * The view component for an article on the website.
 */
class Form
{
    public function __construct(
        private formModel $form
    ) {
    }

    public function render(): void
    {
        $title = $this->form->getTitle();
        $content = $this->form->getContent();
        $fields = $this->form->getFields();
        $submitName = $this->form->getSubmitName();
        $submitValue = $this->form->getSubmitValue();
        $extraContent = $this->form->getExtraContent();
        $method = $this->form->getMethod();
        $hasCaptcha = $this->form->getHasCaptcha();

        echo "
            <article class='form-container'>
                <header>
                    <h2>$title</h2>";
                    if ($content != null)  {
                        echo "<p>$content</p>";
                    }
        echo "  </header>
                <form method='$method' autocomplete='off' enctype='multipart/form-data'>";

        foreach ($fields as $field) {
            $field->render();
        }

        if ($extraContent != null) {
            $lines = explode(';', $extraContent);
            foreach ($lines as $line) {
                echo "<p>$line</p>";
            }
        }

        if ($hasCaptcha) {
            echo '<div class="g-recaptcha" data-sitekey="6Lenh-MZAAAAANqwKEkTjSNDy6Q7XnreHObxUM1V"></div>';
        }

        echo "
                    <div class='row'>
                        <input type='submit' name='$submitName' value='$submitValue'>
                    </div>
                </form>
            </article>
        ";
    }
}
