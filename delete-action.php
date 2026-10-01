<?php

$config = require __DIR__ . "/config.php";

$token = $_POST["token"] ?? "";
$slug = $_POST["slug"] ?? "";

if (
  !hash_equals(
    $config["editor_token"],
    $token
  )
) {

  http_response_code(403);

  die("Invalid token.");
}

$slug = strtolower($slug);

$slug = preg_replace(
  '/[^a-z0-9-]/',
  '',
  $slug
);

$path =
  $config["posts_dir"]
. "/"
. $slug
. ".md";

if (!file_exists($path)) {

  http_response_code(404);

  die("Post not found.");
}

unlink($path);

header("Location: index.php");

exit;
