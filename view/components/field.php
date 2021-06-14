<?php
require_once __DIR__ . '/../../model/field.php';

/**
 * The view component for an article on the website.
 */
class Field
{
  public function __construct(
  private FieldModel $field
    )
  {
  }

  public function render(): void
  {
      echo "
            <div class='row'>
                <div class='col-20'>";
                  $this->renderLabel();
      echo "  </div>
                <div class='col-60'>";
                  $this->renderInput();
      echo "  </div>
            </div>
            ";
  }

  private function renderLabel(): void
  {
      $id = $this->field->getId();
      $type = $this->field->getType();
      $isRequired = $this->field->getRequired();
      $label = $isRequired ? $this->field->getLabel() . ' <span>(required)</span>' : $this->field->getLabel();

    switch ($type)
      {
      case 'file':
      case 'radio':
        echo "<label>$label</label>";
        break;

      default:
          echo "<label for='$id'>$label</label>";
    }
  }

  private function renderInput(): void
  {
      $id = $this->field->getId();
      $name = $this->field->getName();
      $type = $this->field->getType();
      $placeholder = $this->field->getPlaceholder();
      $isRequired = $this->field->getRequired();

    switch ($type)
      {
      case 'textarea':
        echo "<textarea type='$type' id='$id' name='$name' placeholder='$placeholder'";

        if ($isRequired) {
            echo " required";
        }
        echo "></textarea>";
        break;
      case 'file':
          echo "
                    <input type='$type' id='$id' name='$name' class='input-pic'>
                    <label for='pic'><span>Choose a file...</span></label>
                ";
        break;

      case 'radio':
          $ids = explode(';', $id);
          $id1 = $ids[0];
          $id2 = $ids[1];

          echo "
                    <label for='$id1'>
                        <input type='radio' id='$id1' name='$name' value='title'> Title
                    </label>
                    <label for='$id2'>
                        <input type='radio' id='$id2' name='$name' value='score'> Score
                    </label>
                ";
        break;

      case 'select':
        echo "<option value=''>Select Username</option>";

        foreach ($this->field->getComboList() as $user) {
          $value = $user->getUsername();
          echo "<option value='$value'>$value</option>";
        }
        break;

      case 'combo':
        echo "
        <select id='$id' name='$name' required>
          <option value=''>Select Payment Method</option>
          <option value='ideal'>iDEAL</option>
          <option value='paypal'>PayPal</option>
          <option value='creditcard'>VISA</option>
        </select>
        ";
        break;

      case 'date':
        $today = date('Y-m-d');
        echo "<input type='$type' id='$id' name='$name' max='$today' placeholder='$placeholder'>";
        break;

      case 'password':
        echo "<input type='$type' id='$id' name='$name' minlength='8' placeholder='$placeholder'";
        if ($isRequired) {
          echo " required";
        }
        echo ">";
        break;

      case 'number':
        echo "<input type='$type' id='$id' name='$name' placeholder='$placeholder' min='1' step='.01'";

        if ($isRequired) {
          echo " required";
        }
        echo ">";
        break;

      default:
          echo "<input type='$type' id='$id' name='$name' placeholder='$placeholder'";

        if ($isRequired) {
            echo " required";
        }
          echo ">";
    }
  }
}
