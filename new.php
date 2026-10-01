<?php

$config = require __DIR__ . "/config.php";

$error = null;
$success = false;

if ($_SERVER["REQUEST_METHOD"] === "POST") {

  $token = $_POST["token"] ?? "";

  if (!hash_equals($config["editor_token"], $token)) {

    $error = "Invalid token.";

  } else {

    $slug = trim($_POST["slug"] ?? "");
    $content = trim($_POST["content"] ?? "");
    $correction = trim($_POST["correction"] ?? "");
    $summary = trim($_POST["summary"] ?? "");

    if ($slug === "" || $content === "") {

      $error = "Slug and content are required.";

    } else {

      $slug = strtolower($slug);

      $slug = preg_replace(
        '/[^a-z0-9-]/',
        '-',
        $slug
      );

      $slug = preg_replace('/-+/', '-', $slug);

      $posts_dir = $config["posts_dir"];

      if (!is_dir($posts_dir)) {
        mkdir(
          $posts_dir,
          0755,
          true
        );
      }

      $path =
        $config["posts_dir"]
      . "/"
      . $slug
      . ".md";

      if (file_exists($path)) {

        $error = "Post already exists.";

      } else {

        file_put_contents($path, "### Original\n\n" . $content . "\n\n### Correction\n\n" . $correction . "\n\n### Summary\n\n" . $summary);

        // Begin zip routine

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

        $success = true;

        // redirect to read.php
        header("Location: read.php?slug=" . urlencode($slug));
        exit;
      }
    }
  }
}

?>

<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>New entry</title>
    <style>
    textarea {
      font-family: sans-serif;
    }
    </style>
  </head>
  <body>

    <form method="post">
      <div>New entry, enter authentication token:
    <input
      id="notetokenid"
      name="token"
      placeholder="Authentication token"
      required
    >
    <button type="button" onclick="savetoken()">Save token</button>

  </div>

<?php if ($error): ?>
    <p><?= htmlspecialchars($error) ?></p>
<?php endif; ?>

<?php if ($success): ?>
  <p>Entry saved.</p>
<?php endif; ?>

<br>
    <input
        type="text"
        name="slug"
        placeholder="Slug"
	required
        autofocus
    >

    <br><br>

    <textarea style='width: 90vw; margin: 0 0.5rem; height: 24vh;'
              name="content"
              placeholder="Original"
              required
    ></textarea>

    <textarea style='width: 90vw; margin: 0 0.5rem; height: 24vh;'
              name="correction"
              placeholder="Correction"
              required
    ></textarea>

    <textarea style='width: 90vw; margin: 0 0.5rem; height: 25vh;'
              name="summary"
              placeholder="Summary"
              required
    ></textarea>

    <br><br>

    <button type="submit">
      Save
    </button>

</form>

<script>
// load token
document.getElementById("notetokenid").value = window.localStorage.getItem("phpitaliantoken");

function savetoken() {
  window.localStorage.setItem("phpitaliantoken", document.getElementById("notetokenid").value);
}
</script>
</body>
</html>
