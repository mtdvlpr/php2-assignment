<?php
require_once __DIR__ . '/../service/movieService.php';

class MovieController {
  public function _construct(
    private MovieService $service
  )
  {
  }
}