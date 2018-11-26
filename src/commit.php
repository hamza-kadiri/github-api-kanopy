<?php
if (isset($_GET['id'])) {
  $id = $_GET['id'];
}
else {
  echo 'Error: commit ID is missing';
}

if (isset($_GET['repo_url'])) {
    $repo_url = $_GET['repo_url'];
  }
  else {
    echo 'Error: repo_url is missing';
  }

                        /// Getting the commit details \\\
$opts = ["http" => ["method" => "GET", "header" => "User-Agent: hamza-kadiri"]];
$context = stream_context_create($opts);
$url = $repo_url . "/" . $id; //make the url with the repository url and the commit id.
$data = file_get_contents($url, false, $context);
$commit = json_decode($data, true); 


$message = preg_replace("/\\n/", "<br />", $commit['commit']["message"]); // formatting message string
                        /// formatting files array \\\
$map_for_files = function ($file) {   
    $patch = preg_replace('/@@(.*)/', '<div class="code-hunk">$0</div>', $file['patch']); // hunk
    $patch = preg_replace("/^-(.*)$/m", '<span class="code-deletion">$0</span>', $patch); // commit deleted lines
    $patch = preg_replace("/^\+(.*)$/m", '<span class="code-addition">$0</span>', $patch); // commit added lines
  return array(
    'patch' => $patch,
    'filename' => $file['filename']
  );
};

$files = array_map($map_for_files, $commit['files']);
$stats = $commit['stats']


?>
<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Commit
        <?php echo $id ?>
    </title>
    <link rel="stylesheet" type="text/css" href="/main.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bulma/0.7.2/css/bulma.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bulma-accordion@2.0.0/dist/css/bulma-accordion.min.css">
    <script defer src="https://use.fontawesome.com/releases/v5.3.1/js/all.js"></script>
    <script src="https://code.jquery.com/jquery-3.3.1.min.js" integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8="
        crossorigin="anonymous"></script>
</head>

<body>
    <section class="section">
        <div class="container">
            <a href="index.php" class="button is-link is-outlined">Return to main page</a>
        </div>
    </section>
    <section class="section">
        <div class="container">
            <div class="box full-commit">
                <p class=" title is-4">Commit message:</p>
                <pre class="commit-message"> <?php echo $message;?></pre>
            </div>
            <div class="box author-info">
                <article class="media">
                    <figure class="media-left">
                        <p class="image is-32x32">
                            <a href=<?php echo $commit['committer']["html_url"]; ?>>
                                <img src=<?php echo $commit["committer"]["avatar_url"]; ?>>
                            </a>
                        </p>
                    </figure>
                    <div class="media-content">
                        <div class="content">
                            <p> <strong>
                                    <?php echo $commit["committer"]["login"] ?> </strong>
                                committed at
                                <?php echo date_format(date_create($commit["commit"]["committer"]["date"]) , "Y-m-d H:i:s"); ?>
                            </p>
                        </div>
                    </div>
                    <div class="media-right">
                        <a href=<?php echo "/commit.php?id=" . $commit["sha"]; ?>>
                            <span>
                                <?php echo substr($commit["sha"], 0, 12); ?></span>
                        </a>
                    </div>
                </article>
            </div>
        </div>
    </section>
    <section class="accordions section">
        <p>
            <?php echo $stats["total"] . " total changes, with <strong class='green'>" . $stats["additions"] . " additions </strong> and <strong class='red'>" . $stats["deletions"] . " deletions. </strong>"  ?>
        </p>
        <?php 
      foreach($files as $file) {
        ?>
        <div class="code accordion is-active">
            <div class="file-header accordion-header">
                <p>
                    <?php echo $file["filename"]; ?>
                </p>
                <span class="level-right">
                    <a class="level-item">
                        <span class="icon arrow-icon is-small"><i class="fas fa-angle-down"></i></span>
                    </a>
                </span>
            </div>
            <div class="accordion-body inner-code">
                <?php echo $file['patch']; ?>
            </div>
        </div>
        <?php }?>
    </section>
</body>
<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/bulma-accordion@2.0.0/dist/js/bulma-accordion.min.js"></script>
<script>
    bulmaAccordion.attach();
</script>

</html>