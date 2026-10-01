<?php

$config = require __DIR__ . "/config.php";

$token = $_POST["token"] ?? "";
$slug = $_POST["slug"] ?? "";
$content = $_POST["content"] ?? "";

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

file_put_contents($path, $content);


// begin zip routine

$posts_dir =
  escapeshellarg(
    $config["posts_dir"]
  );

$archive =
  escapeshellarg(
    $config["posts_dir"] . "/heitorchang_italianblog.tar.gz"
  );

$files = glob($config["posts_dir"] . "/*.md");

$escaped_files = array_map(
  function ($file) use ($posts_dir) {

    return escapeshellarg(
      basename($file)
    );

  },
  $files
);

$command =
  "tar --format=ustar -czf "
  . $archive
. " -C "
. $posts_dir
. " "
. implode(" ", $escaped_files);

exec($command, $output, $result);

// end zip routine


header(
  "Location: read.php?slug="
. urlencode($slug)
);

exit;
