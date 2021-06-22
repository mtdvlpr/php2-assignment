<?php
//TODO: Move these functions to the proper place
class controller
{
    private $salt;
    private $view;
    private $user_DAO;
    private $movie_DAO;
    private $contact_DAO;

  public function __construct()
  {
      $this->salt = '$6$rounds=7000$fishandchips$';
  }

    /**
     * Get the title of a movie based on it's id
     *
     * @param int @id The id of the movie
     */
  public function getTitle($id)
  {
      return $this->movie_DAO->getMovieById($id)["title"];
  }

    /**
     * Write the head of the html page
     *
     * @param string @title The title of the page
     */
  public function writeHead($title)
  {
      $this->view->writeHead($title);
  }

    /**
     * Write the header of the html page
     */
  public function showHeader()
  {
      $this->view->showHeader();
  }

    /**
     * Write the dynamic part of the menu
     *
     * @param bool @isLoggedIn Whether the user is logged in
     */
  public function showMenu($isLoggedIn)
  {
      $this->view->showMenu($isLoggedIn);
  }

    /**
     * Write the footer of the html page
     */
  public function showFooter()
  {
      $this->view->showFooter();
  }

    /**
     * Validate the contact form input
     *
     * @param string @email The email address provided by the user
     * @param string @name The name of the user
     * @param string @subject The subject of the message
     * @param string @message The message of the user
     */
  public function contactForm($email, $name, $subject, $message)
  {
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $this->view->showErrorMessage('Please fill in a valid email address!');
    } elseif (preg_match('~[0-9]~', $name) === 1) {
        $this->view->showErrorMessage('Please fill in a valid name!');
    } else if (isset($_POST['g-recaptcha-response']) && !empty($_POST['g-recaptcha-response'])) {

        // Google secret API
        $secretAPIkey = '6Lenh-MZAAAAAMv--kR6my39trkTaJIxR34ujQnI';

        // reCAPTCHA response verification
        $verifyResponse = file_get_contents('https://www.google.com/recaptcha/api/siteverify?secret=' . $secretAPIkey . '&response=' . $_POST['g-recaptcha-response']);

        // Decode JSON data
        $response = json_decode($verifyResponse);
      if ($response->success) {
          $this->contact_DAO->addInformation($email, $name, $subject, $message);
          $this->view->showSuccessMessage('Your message was succesfully sent.');
      } else {
          $this->view->showErrorMessage('Robot verification failed, please try again.');
      }
    } else {
        $this->view->showErrorMessage('Please check on the reCAPTCHA box.');
    }
  }

    /**
     * Validate the attempt to create an account
     *
     * @param string @username The username/email provided by the user
     * @param string @name The name of the user
     * @param string @password The password of the user
     * @param string @confirm The confirmed password
     * @param bool @byAdmin Whether the admin wanted to create this account or not
     */
  public function register($username, $name, $password, $confirm, $byAdmin = false)
  {
    if (!filter_var($username, FILTER_VALIDATE_EMAIL)) {
        $this->view->showErrorMessage('Please fill in a valid email address!');
    } elseif (preg_match('~[0-9]~', $name) === 1) {
        $this->view->showErrorMessage('Please fill in a valid name!');
    } elseif (strlen($password) < 8) {
        $this->view->showErrorMessage('Your password should have a length of 8 or more!');
    } elseif ($password !== $confirm) {
        $this->view->showErrorMessage('Your password and the confirmed password do not match!');
    } else {
        $user = $this->user_DAO->getUserByUsername($username);
      if ($user !== null) {
          $this->view->showErrorMessage('An account with the given email address already exists!');
      } else if ($byAdmin) {
        if ($_SESSION["login"]["role"] == 2) {
            $this->user_DAO->addUser($name, $username, crypt($password, $this->salt));
            $this->view->showSuccessMessage('The user was succesfully added! Refresh to see the change.');
        } else {
            $this->view->showErrorMessage("You don't have the rights to do this!");
        }
      } else if (isset($_POST['g-recaptcha-response']) && !empty($_POST['g-recaptcha-response']) || $byAdmin) {

          // Google secret API
          $secretAPIkey = '6Lenh-MZAAAAAMv--kR6my39trkTaJIxR34ujQnI';

          // reCAPTCHA response verification
          $verifyResponse = file_get_contents('https://www.google.com/recaptcha/api/siteverify?secret=' . $secretAPIkey . '&response=' . $_POST['g-recaptcha-response']);

          // Decode JSON data
          $response = json_decode($verifyResponse);
        if ($response->success || $byAdmin) {
            $hash = md5(rand(0, 1000));
            $this->user_DAO->addUser($name, $username, crypt($password, $this->salt), $hash, 0);
            mail("$username", "Account Verification", "Dear $name,\n\nThank you for creating an account!\n\nClick this link to activate your account:\nhttp://www.643622.infhaarlem.nl/verify?email=$username&hash=$hash&new=none\n\nKind regards,\n\n\n643622.infhaarlem.nl", 'From:noreply@643622.infhaarlem.nl');
            $this->view->showSuccessMessage('Your account was succesfully created. Check your email to activate it.');
        } else {
            $this->view->showErrorMessage('Robot verification failed, please try again.');
        }
      } else {
          $this->view->showErrorMessage('Please check on the reCAPTCHA box.');
      }
    }
  }

    /**
     * Validate the attempt to verify an account
     *
     * @param string @email The username/email of the user
     * @param string @hash A long string to make sure the user has access to the provided email address
     * @param bool @buttonClicked Whether the 'Activate Account' button was clicked or not
     */
  public function verifyAccount($email, $hash, $new, $buttonClicked)
  {
    if (isset($email) && !empty($email) && isset($hash) && !empty($hash) && isset($new) && !empty($new)) {
      if ($buttonClicked) {
        $user = $this->user_DAO->verifyAccount($email, $hash);
        if ($user != null) {
          if ($new == "none") {
            $this->user_DAO->activateUser($email);
            $this->view->showMessage('Your account has been activated, you can now log in.');
          } else {
                $this->user_DAO->updateUsername($email, $new);
                $this->view->showMessage('Your email address has been changed. Log in using the new email.');
          }
        } else {
            $this->view->showMessage("We couldn't find your account, <a href='/signup'>try registering</a>.");
        }
      } else {
          $this->view->showMessage('<form method="post"><input type="submit" name="verify" class="linkButton" value="Activate your account/email."></form>');
      }
    } else {
        header("Location: /404.php");
    }
  }

    /**
     * Validate the attempt to log in
     *
     * @param string @username The username/email provided by the user
     * @param string @password The password of the user
     */
  public function login($username, $password)
  {
    if (!isset($_SESSION["tries"])) {
        $_SESSION["tries"] = 0;
    }
    if (isset($_SESSION["expire_time"])) {
        $_SESSION["time_remaining"] = round(($_SESSION["expire_time"] - time()) / 60, 0);
      if ($_SESSION["time_remaining"] < 1) {
          $_SESSION["tries"] = 0;
          unset($_SESSION["expire_time"]);
          unset($_SESSION["time_remaining"]);
      }
    }
    if (isset($_SESSION["time_remaining"])) {
        $timeRemaining = $_SESSION["time_remaining"];
        $this->view->showWarningMessage("Logging in is temporarly disabled due to too many failed login attempts. Try again in $timeRemaining minute(s).");
    } else if (!filter_var($username, FILTER_VALIDATE_EMAIL)) {
        unset($_SESSION["tries"]);
        $this->view->showErrorMessage('Please fill in a valid email address!');
    } else {
        $user = $this->user_DAO->getUserByUsername($username);
      if ($user != null) {
        if ($user["password"] == crypt($password, $this->salt)) {
          unset($_SESSION["tries"]);
          if ($user["is_active"] == 1) {
                $_SESSION["login"] = $user;
                header("Location: /");
          } else {
                  $this->view->showErrorMessage("This account has not been activated yet!");
          }
        } else {
            $this->view->showErrorMessage("Your password is wrong!");
            $_SESSION["tries"]++;
          if ($_SESSION["tries"] == 2) {
                $this->view->showWarningMessage("You failed to log in twice. You have one more try before logging in will be disabled for a while!");
          } else if ($_SESSION["tries"] == 3) {
                  $_SESSION["expire_time"] = time() + 120;
                  $this->view->showWarningMessage("Logging in is disabled for a while!");
          }
        }
      } else {
          unset($_SESSION["tries"]);
          $this->view->showErrorMessage("This username does not exist!");
      }
    }
  }

    /**
     * Validate the attempt to get a new password
     *
     * @param string @username The username/email provided by the user
     * @param string @confirm The confirmed email address
     */
  public function forgotPassword($username, $confirm)
  {
    if (!filter_var($username, FILTER_VALIDATE_EMAIL)) {
        $this->view->showErrorMessage("Please enter a valid email address!");
    } else if ($username == $confirm) {
        $user = $this->user_DAO->getUserByUsername($username);
      if ($user != null) {
          $newPassword = "";
          $characters = "aAbBcCdDeEfFgGhHiIjJkKlLmMnNoOpPqQrRsStTuUvVwWxXyYzZ1234567890";

        for ($i = 0; $i < 8; $i++) {
          $position = rand(0, 61);
          $newPassword .= substr($characters, $position, 1);
        }
          $this->user_DAO->updatePassword(crypt($newPassword, $this->salt), $username);
          mail("$username", "New Password", "Dear user,\n\nYou have forgotten your password, so we made a new one for you!\n\nYour new password is: $newPassword\n\nKind regards,\n\n\n643622.infhaarlem.nl", 'From:noreply@643622.infhaarlem.nl');
          $this->view->showSuccessMessage("A new password has been sent to your email address.");
      } else {
          $this->view->showErrorMessage("The email address provided does not have an account!");
      }
    } else {
        $this->view->showErrorMessage("The email addresses do not match!");
    }
  }

  public function showContactArticle()
  {
      $this->view->showContactArticle();
  }

  public function showAboutArticle()
  {
      $this->view->showAboutArticle();
  }

  public function showCollectionArticle()
  {
      $this->view->showCollectionArticle();
  }

  public function showAccount(array $user)
  {
      $this->view->showAccount($user);
  }

    /**
     * Validate the attempt to update a user's profile
     *
     * @param string @username The username/email of the user
     * @param string @oldPass The current password of the user
     * @param string @newName The new name of the user
     * @param string @newPass The new password of the user
     * @param string @confirm The confirmed new password
     * @param string @oldPic The current profile picture of the user
     */
  public function updateProfile($username, $oldPass, $newEmail, $newName, $newPass, $confirm, $oldPic, $byAdmin = false)
  {
    if ($_SESSION["login"]["password"] == crypt($oldPass, $this->salt) || isset($_SESSION["reloadPass"])) {

        // Email change
      if (!empty($newEmail)) {
        if (!filter_var($newEmail, FILTER_VALIDATE_EMAIL)) {
            $this->view->showErrorMessage('Please fill in a valid email address!');
        } else {
          if (!isset($_SESSION["reloadEmail"])) {
            if ($byAdmin) {
              $user = $this->user_DAO->getUserByUsername($username);
              if ($user["role"] >= $_SESSION["login"]["role"]) {
                      $this->view->showErrorMessage("You don't have the rights to do this!");
              } else {
                      $this->user_DAO->updateUsername($username, $newEmail);
                      $username = $newEmail;
              }
            } else {
                $hash = md5(rand(0, 1000));
                $this->user_DAO->setHash($username, $hash);
                mail("$username", "Email Address Changed", "Dear user,\n\nThe email address linked to your account has been changed to: $newEmail.\n\nIf this was not you, please contact us: http://www.643622.infhaarlem.nl/contact\n\nKind regards,\n\n\n643622.infhaarlem.nl", 'From:noreply@643622.infhaarlem.nl');
                mail("$newEmail", "Email Verification", "Dear user,\n\nYou want to change the email address linked to your account.\n\nClick this link to activate your new email:\nhttp://www.643622.infhaarlem.nl/verify?email=$username&hash=$hash&new=$newEmail\n\nKind regards,\n\n\n643622.infhaarlem.nl", 'From:noreply@643622.infhaarlem.nl');
            }
                  $_SESSION["reloadEmail"] = 1;
          } else {
                  unset($_SESSION["reloadEmail"]);
          }
                  $this->view->showSuccessMessage($byAdmin ? "The email has been changed successfully! Refresh to see the change." : "An email was sent to your new email address to verify it!");
        }
      }

        // Name change
      if (!empty($newName)) {
        if (preg_match('~[0-9]~', $newName) === 1) {
          $this->view->showErrorMessage('Please fill in a valid name!');
        } else {
            $this->user_DAO->updateName($username, $newName);
            $this->view->showSuccessMessage($byAdmin ? "The name has been changed successfully!" : "Your name has been changed successfully!");
          if (!$byAdmin) {$_SESSION["login"] = $this->user_DAO->getUserByUsername($username);
          }
          if (!isset($_SESSION["reloadName"])) {
            echo '<script>parent.window.location.reload(true);</script>';
            $_SESSION["reloadName"] = 1;
          } else {
              unset($_SESSION["reloadName"]);
          }
        }
      }

        // Password change
      if (!empty($newPass)) {
        if ($newPass !== $confirm) {
            $this->view->showSuccessMessage("The passwords did not match!");
        } elseif (strlen($newPass) < 8) {
            $this->view->showErrorMessage($byAdmin ? "The password should have a length of 8 or more!" : 'Your new password should have a length of 8 or more!');
        } else {
            $this->user_DAO->updatePassword(crypt($newPass, $this->salt), $username);
            $this->view->showSuccessMessage($byAdmin ? "The password has been changed successfully!" : "Your password has been changed successfully!");
          if (!$byAdmin) {$_SESSION["login"] = $this->user_DAO->getUserByUsername($username);
          }
          if (!isset($_SESSION["reloadPass"])) {
              echo '<script>parent.window.location.reload(true);</script>';
              $_SESSION["reloadPass"] = 1;
          } else {
              unset($_SESSION["reloadPass"]);
          }
        }
      }

        // Profile picture change
        $targetDir = "img/uploads/";
        $fileName = basename($_FILES["pic"]["name"]);
        $targetFilePath = $targetDir . $fileName;
        $fileType = pathinfo($targetFilePath, PATHINFO_EXTENSION);

      if (!empty($_FILES["pic"]["name"])) {
          $allowTypes = array('jpg', 'png', 'jpeg', 'gif');
        if (!in_array($fileType, $allowTypes)) {
            $this->view->showErrorMessage('Sorry, only JPG, JPEG, PNG & GIF files are allowed.');
        } else if (isset($_SESSION["reload"])) {
            $this->view->showSuccessMessage($byAdmin ? "The profile picture has been changed successfully!" : "Your profile picture has been changed successfully!");
            unset($_SESSION["reload"]);
        } else if (file_exists($targetFilePath)) {
            $this->view->showErrorMessage("This filename already exists. Change the name and try again.");
        } else if (move_uploaded_file($_FILES["pic"]["tmp_name"], $targetFilePath)) {
            $oldPic = $this->user_DAO->getUserByUsername($username)["profile_picture"];
            $this->user_DAO->updateImage($username, $targetFilePath);
          if ($oldPic !== "img/fillerface.png") {unlink($oldPic);
          }
          if (!$byAdmin) {$_SESSION["login"] = $this->user_DAO->getUserByUsername($username);
          }
            $_SESSION["reload"] = 1;
            echo '<script>parent.window.location.reload(true);</script>';
        } else {
            $this->view->showErrorMessage($byadmin ? "Sorry, there was an error changing the profile picture." : "Sorry, there was an error changing your profile picture.");
        }
      }
    } else {
        $this->view->showErrorMessage("Sorry, you entered the wrong password!");
    }
  }

    /**
     * Give/remove a user's admin rights
     *
     * @param string @username The username/email of the user
     */
  public function changeAdmin($username)
  {
    if ($_SESSION["login"]["role"] < 2) {
        $this->view->showErrorMessage("You don't have the rights to perform this action!");
    } else if ($this->user_DAO->getUserByUsername($username)["role"] == 0) {
        $this->user_DAO->makeAdmin($username);
        $this->view->showSuccessMessage("The user has been given admin rights! Refresh to see the change.");
    } else {
        $this->user_DAO->RemoveAdmin($username);
        $this->view->showSuccessMessage("The user has lost his admin rights! Refresh to see the change.");
      if ($_SESSION["login"]["username"] == $username) {
          $this->view->showWarningMessage("You lost your rights and will be logged off!");
          unset($_SESSION["login"]);
      }
    }
  }

    /**
     * Remove a user's profile picture
     *
     * @param string @username The username/email provided by the user
     * @param string @pic The profile picture
     */
  public function removePicture($username, $pic)
  {
      $this->user_DAO->updateImage($username, "img/fillerface.png");
    if ($pic !== "img/fillerface.png") {unlink($pic);
    }
      $this->view->showSuccessMessage('Your profile picture has been removed.');
      $_SESSION["login"] = $this->user_DAO->getUserByUsername($username);
    if (!isset($_SESSION["reloadName"])) {
        echo '<script>parent.window.location.reload(true);</script>';
        $_SESSION["reloadName"] = 1;
    } else {
        unset($_SESSION["reloadName"]);
    }
  }

    /**
     * Validate the attempt to remove an account
     *
     * @param string @username The username/email provided by the user
     * @param string @password The password of the user
     * @param bool @byAdmin Whether the admin is removing an account
     */
  public function removeAccount($username, $password, $byAdmin = false)
  {
    if (crypt($password, $this->salt) == $_SESSION["login"]["password"]) {
      if ($byAdmin) {
        if ($_SESSION["login"]["role"] == 2) {
            $this->user_DAO->deleteUser($username);
            $this->view->showSuccessMessage("The user has been deleted! Refresh to see the change.");
        } else {
            $this->view->showErrorMessage("You don't have the rights to do this!");
        }
      } else {
            $this->user_DAO->deleteUser($username);
            unset($_SESSION["login"]);
            echo '<script>parent.window.location.reload(true);</script>';
      }
    } else {
        $this->view->showErrorMessage("You entered the wrong password!");
    }
  }

    /**
     * Show the movie collection to the user
     *
     * @param string @title The title of the movie that the user searches
     * @param string @orderBy The field that the list should be ordered by
     */
  public function showCollection($title = "", $orderBy = "id")
  {
      $movies = $this->movie_DAO->getMovies($title, $orderBy);
    foreach ($movies as $movie) {
        $this->view->showMovie($movie);
    }
    if (count($movies) == 0) {
        $this->view->showErrorMessage("Sorry, there are no results for your search...");
    }
  }

    /**
     * Show the movie details of a specific movie
     *
     * @param int @id The id of the movie
     */
  public function showMovieDetails($id)
  {
      $movie = $this->movie_DAO->getMovieById($id);
    if ($movie != null) {
        $this->view->showMovieDetails($movie);
    } else {
        $this->view->showErrorMessage("We couldn't find the movie you were looking for...");
    }
  }

    /**
     * Show all users from the database
     */
  public function showUsers()
  {
      $users = $this->user_DAO->getUsers();
    foreach ($users as $user) {
        $this->view->showUser($user);
    }
  }

    /**
     * Show all usernames
     */
  public function showUsernames()
  {
      $users = $this->user_DAO->getUserSelection($_SESSION["login"]["role"]);
    foreach ($users as $user) {
        $this->view->showUsername($user["username"]);
    }
  }
}
