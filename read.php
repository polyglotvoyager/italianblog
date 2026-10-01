<?php

$config = require __DIR__ . "/config.php";

$slug = $_GET["slug"] ?? "";

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

$content = file_get_contents($path);

?>

<!DOCTYPE html>
<html>
  <head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= htmlspecialchars($slug) ?></title>

    <script src="marked.min.js"></script>

    <style>
    h1, h2, h3, h4 {
      margin: 0.5rem 1rem 0.5rem 0;
    }

    body {
      max-width: 800px;
      margin: 40px auto;
      font-family: sans-serif;
      line-height: 1.6;
    }

    pre {
      background: #eee;
      padding: 12px;
      overflow-x: auto;
    }

    code {
      font-family: monospace;
    }

    #content {
      margin: 1rem;
    }

    a {
      text-decoration: none;
    }

    </style>
</head>
<body>

  <div>
    <a href="index.php">All entries</a>
  </div>

  <p>
    <strong><?= $slug ?></strong>
  </p>

  <div id="content"></div>

  <p>
    <a href="italianblogfiles/<?= $slug ?>.md">View Markdown</a> (<?= $slug ?>)
  </p>

  <p>
    <a id="editlink" href="">Edit entry (authentication required)</a>
  </p>
  <br><br><br>

  <script>

  const markdown =
    <?= json_encode($content) ?>;

  document.getElementById("content").innerHTML =
    marked.parse(markdown);

  document.getElementById('editlink').href = "edit.php?slug=" +
                                             encodeURIComponent(
                                               new URLSearchParams(window.location.search).get("slug")
                                             );
  </script>

</body>
</html>
