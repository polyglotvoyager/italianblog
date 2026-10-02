<?php

$config = require __DIR__ . "/config.php";

$posts_dir = $config["posts_dir"];

$files = glob($posts_dir . "/*.md");

$posts = [];

foreach ($files as $file) {

    $slug = basename($file, ".md");

    $content = file_get_contents($file);

  $title = $slug;


    $posts[] = [

        "slug" => $slug,

        "modified" => filemtime($file)

    ];
}

usort($posts, function ($a, $b) {

    return $b["modified"] <=> $a["modified"];
});

?>

<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Italian Blog</title>

    <style>

    h1, h2, h3 {
      margin: 0.5rem 0;
    }

        body {
            max-width: 800px;
            margin: 40px auto;
            font-family: sans-serif;
        }

        .post {
            margin: 0.5rem;
        }

        .date {
            color: #666;
            font-size: 0.9rem;
        }

        a {
            text-decoration: none;
        }

    .entrylink {
      font-weight: bold;
    }

    .created_at {
      margin-left: 2rem;
      color: #bbb;
    }

    </style>
</head>
<body>

  <h3>Italian Blog</h3>

<?php foreach ($posts as $post): ?>

    <div class="post">

      <a class="entrylink" href="read.php?slug=<?= urlencode($post["slug"]) ?>">
        <?= htmlspecialchars($post["slug"]) ?>
      </a>

      <span class="created_at"><?= date("Y-m-d", $post["modified"]) ?></span>

      <div class="date">


      </div>

    </div>

<?php endforeach; ?>

<br>

<p>
  <a href="italianblogfiles/heitorchang_italianblog.tar.gz">Download Zip of entries</a>
</p>

<p>
  <a href="delete.php">Delete entry (authentication required)</a>
</p>

<p>
  <a href="new.php">New entry (authentication required)</a>
</p>

<br><br><br>
<br><br><br>

</body>
</html>
