<?php
require_once __DIR__ . "/../vendor/abraham/twitteroauth/autoload.php";
require_once __DIR__ . '/../vendor/autoload.php';
use Dotenv\Dotenv;
use Abraham\TwitterOAuth\TwitterOAuth;

$dotenv = Dotenv::createUnsafeImmutable(__DIR__ . '/../');
if (getenv('ENV') != 'production') {
  $dotenv->load();
}

/**
 * Twitter feed model
 */

class TwitterFeedModel
{
  private static ?TwitterOAuth $connection = null;

  public function __construct()
  {
    if (self::$connection == null) {
      self::$connection = new TwitterOAuth(getenv('TWITTER_CONSUMER_KEY'), getenv('TWITTER_CONSUMER_SECRET'), getenv('TWITTER_ACCESS_TOKEN'), getenv('TWITTER_ACCESS_TOKEN_SECRET'));
    }
  }
  // Get tweets from API
  public function getTweets(): array
  {
    return self::$connection->get("statuses/home_timeline",["count" => 10, "exclude_replies" => true]);
  }
}
