<?php
require_once __DIR__ . "../vendor/abraham/twitteroauth/autoload.php";

use Abraham\TwitterOAuth\TwitterOAuth;

/**
 * Twitter feed model
 */

class TwitterModel
{
  private static const CONSUMER_KEY = '';
  private static const CONSUMER_SECRET = '';
  private static const ACCESS_TOKEN = '';
  private static const ACCESS_TOKEN_SECRET = '';
  private static ?TwitterOAuth $connection = null;

  public function __construct()
  {
    if (self::$connection == null) {
      self::$connection = new TwitterOAuth($this::CONSUMER_KEY, $this::CONSUMER_SECRET, $this::ACCESS_TOKEN, $this::ACCESS_TOKEN_SECRET);
    }
  }
  // Get tweets from API
  public function getTweets(): array
  {
    return self::$connection->get("statuses/home_timeline",["count" => 10, "exclude_replies" => true]);
  }
}
