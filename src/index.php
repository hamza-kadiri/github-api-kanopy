<?php
$opts = ["http" => ["method" => "GET", "header" => "User-Agent: hamza-kadiri"]];
$context = stream_context_create($opts);
$url = 'https://api.github.com/repos/torvalds/linux/commits';
$data = file_get_contents($url, false, $context);
$json = json_decode($data, true); 
$timeAgo = require 'timeAgo.php';

$format_commits = function ($commit)
{
  return array(
    "sha" => $commit["sha"],
    "committer_name" => $commit["commit"]["committer"]["name"],
    "committer_url" => $commit["committer"]["html_url"],
    "date" => $commit["commit"]["committer"]["date"],
    "message" => $commit["commit"]["message"],
    "login" => $commit["committer"]["login"],
    "image" => $commit["committer"]["avatar_url"]
  );
};


$commits = array_map($format_commits, $json);

?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Github API</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" type="text/css" media="screen" href="main.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bulma/0.7.2/css/bulma.min.css">
</head>
<body>
<body>
  <section class="section">
    <div class="container">
      <h1 class="title">
        Github commits
      </h1>
      <p class="subtitle">
        Made by <strong>Hamza Kadiri</strong>
      </p>
    <div>
  </section>
  <section class="section">
    <div class="container commits">
        <?php foreach ($commits as $commit) { ?>
            <article class=" box media">
                <figure class="media-left">
                    <p class="image is-96x96">
                        <a href=<?php echo $commit['committer_url']; ?>>
                            <img src=<?php echo $commit["image"]; ?>>
                        </a>
                    </p>
                </figure>
                <div class="media-content">
                    <div class="content">
                        <p> <strong> <?php echo $commit["committer_name"]; ?></strong> 
                            <small>@<?php echo $commit["login"]; ?></small>
                            <small> committed <?php echo $timeAgo($commit["date"]); ?> </small>
                            <br/>
                            <?php if (strlen($commit["message"]) <= 300) {
                                    echo $commit["message"]; 
                                    }
                                else {
                                    echo substr($commit["message"], 0, 300) . " ..."; 
                                    } ?>
                        </p>
                    </div>
                </div>
                <div class="media-right">
                    <a href=<?php echo "commit.php?id=" . $commit["sha"]; ?>>
                    <span><?php echo substr($commit["sha"], 0, 12); ?></span>
                    </a>
                </div>
            </article>
      <?php } ?>
    </div>
  </section>
</body>
</html>