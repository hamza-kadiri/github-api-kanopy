<?php

if(isset($_POST["commits"])) {
  $commits=$_POST["commits"];
}
else {
    echo 'Error: commit is missing';
  }
if(isset($_POST["committer"])) {
  $committer=$_POST["committer"];
}
else {
    echo 'Error: committer is missing';
  }

if(isset($_POST["repo_url"])) {
  $repo_url=$_POST["repo_url"];
}
else {
    echo 'Error: committer is missing';
  }
$timeAgo = require 'timeAgo.php';


$filter_by_committer = function ($commit) use ($committer) { 
    if(strcmp($committer, "All")==0) {
        return true;
    }
  return strcmp($commit["committer_name"],$committer)==0;
};

$filtered_commits = array_filter($commits,$filter_by_committer);

foreach ($filtered_commits as $commit) { ?>
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
                    <?php 
                    if (strlen($commit["message"]) <= 300) {
                        echo $commit["message"]; 
                    }
                    else {
                        echo substr($commit["message"], 0, 300) . " ..."; 
                     } ?>
                </p>
            </div>
        </div>
        <div class="media-right">
            <a href=<?php echo "/commit.php?id=" . $commit["sha"] . "&repo_url=" . $repo_url; ?>>
            <span><?php echo substr($commit["sha"], 0, 12); ?></span>
            </a>
        </div>
    </article>
<?php } ?>