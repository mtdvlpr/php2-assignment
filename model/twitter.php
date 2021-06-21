<?php
require_once __DIR__ . "/../vendor/abraham/twitteroauth/autoload.php";

use Abraham\TwitterOAuth\TwitterOAuth;

/**
 * Twitter feed model
 */

class TwitterFeedModel
{
  private const CONSUMER_KEY = 'W9D0lXy3NzsH7ou1QvBqhgokJ';
  private const CONSUMER_SECRET = 'JPUrXQawLgakzeLb56vZzUJdpFEZ5JH0SqFvMK4bIsB525trKk';
  private const ACCESS_TOKEN = '1405162948864118786-YYT1wguTpYSOOYwuaYTIoOffdQS0zI';
  private const ACCESS_TOKEN_SECRET = 'zmzOuDJbRQLWccq7o4VLEnIKYdouBQBeG2XLK7KHZt640';
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
