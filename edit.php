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
    <title>Edit Post</title>
</head>
<body>

  <h1>Edit Post</h1>

  <form method="post" action="edit-action.php">

    <input
      type="hidden"
      name="slug"
      value="<?= htmlspecialchars($slug) ?>"
    >

    <input
      id="notetokenid"
      name="token"
      placeholder="Authentication token"
      required
    >
    <button type="button" onclick="savetoken()">Save token</button>

    <br><br>

    <textarea style='width: 90vw; margin: 0 0.5rem; height: 70vh;'
              name="content"
              required
    ><?= htmlspecialchars($content) ?></textarea>

    <br><br>

    <button type="submit">
      Save Changes
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
