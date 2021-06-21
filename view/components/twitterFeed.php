<?php

require_once __DIR__ . '../../../model/twitter.php';

class TwitterFeed
{

  private TwitterFeedModel $twitter;


  public function __construct(TwitterFeedModel $twitter)
  {
    $this->twitter = $twitter;
  }

  public function render()
  {

    $tweets = array();
    if(!isset($_SESSION['tweets'])){
      $_SESSION['tweets'] = $this->twitter->getTweets();
    }

      $tweets = $_SESSION['tweets'];
    echo /*html*/ "

    <article id='twitter-feed'>
      <h2 class='h4'>Get the latest twitter news!</h2>
      <p class='p'>The feed is automatically refreshed every 30 mins, but you can also manually refresh.</p>
      <form method='post'><button class='twitter-btn' type='submit' name='refreshTweets'>Refresh tweets</button></form>
    <section class='tweets'>";

    foreach($tweets as $key => $value){
      $tweetContent = explode("https",$value->text)[0];
      $userName = $value->user->name;
      $createdAt = explode('+',$value->created_at)[0];
      $createdAt = DateTime::createFromFormat(' D M d H:i:s ', explode('+', $value->created_at)[0]);
      $createdAt = date_format($createdAt, "d-m-y H:i");
      $profileImage = $value->user->profile_image_url_https;
      $contentImage = isset($value->entities->media) ? $value->entities->media[0]->media_url_https : null;
      $id = $value->id_str;
      $target = "window.location.href='https://twitter.com/Php2A/status/$id'";
      $onclick = 'onclick="' . $target . '"';

      echo /*html*/"
          <section class='tweet' $onclick>
            <img src='$profileImage' width='50px' height='50px' alt='Profile picture' class='tweet-profile-pic'>
            ";
      if($contentImage != null){
             echo "<img src='$contentImage' alt='Image' class='tweet-img'>";
      }

      echo "
             <p class='usname'>$userName</p>
             <p class='tweet-text'>$tweetContent</p>
             <span>$createdAt</span>
          </section>
              ";
    }
          echo /*html*/"
          </section>
        </article>
    ";
  }
}
