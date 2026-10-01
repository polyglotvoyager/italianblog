<?php

$config = require __DIR__ . "/config.php";

$files = glob($config["posts_dir"] . "/*.md");

sort($files);

?>

<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Delete Post</title>
</head>
<body>

  <h1>Delete Post</h1>

  <form method="post" action="delete-action.php">

    <input
      id="notetokenid"
      name="token"
      placeholder="Authentication token"
      required
    >
    <button type="button" onclick="savetoken()">Save token</button>

    <br><br>

    <select name="slug" required>

        <?php foreach ($files as $file): ?>

            <?php
                $slug = basename($file, ".md");
            ?>

            <option value="<?= htmlspecialchars($slug) ?>">

                <?= htmlspecialchars($slug) ?>

            </option>

        <?php endforeach; ?>

    </select>

    <br><br>

    <button type="submit">
      Delete
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
