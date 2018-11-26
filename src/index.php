<?php
$opts = ["http" => ["method" => "GET", "header" => "User-Agent: hamza-kadiri"]];
$context = stream_context_create($opts);
$url = 'https://api.github.com/repos/torvalds/linux/commits';
if(isset($_GET["url"])){
    $url = $_GET["url"];
}
$data = file_get_contents($url, false, $context);

$json = json_decode($data, true); 

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


$format_committer = function ($commit)
{
  return $commit["commit"]["committer"]["name"];
};

$commits = array_map($format_commits, $json);
$committers = array_unique(array_map($format_committer, $json));


$timeAgo = require 'timeAgo.php';


?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Github API</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" type="text/css" media="screen" href="/main.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bulma/0.7.2/css/bulma.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bulma-quickview@2.0.0/dist/css/bulma-quickview.min.css">
    <script src="https://cdn.jsdelivr.net/npm/bulma-quickview@2.0.0/dist/js/bulma-quickview.min.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    <script defer src="https://use.fontawesome.com/releases/v5.3.1/js/all.js"></script>
</head>
<body>
  <section class="section">
    <div class="container">
      <h1 class="title">
        Github commits
      </h1>
      <p class="subtitle">
        Made by <strong>Hamza Kadiri</strong>
      </p>
      <div class="level">
        <div class="level-left">
            <div class="field has-addons">
                <div class="control">
                    <input class="input search-bar" type="text" name="url" placeholder="Search for a repository">
                </div>
                <div class="control">
                    <button type="submit" class="button is-info" data-show="quickview" onclick="quickview(this)" data-target="quickviewDefault">Search</button>
                </div>
            </div>
        </div>  
        <div class="level-right">
            <div class="control level-item">
                <strong class="level-item">Filter by committer:</strong>
                <div class="select" >
                    <select class="is-hovered" id="committers-dropdown">
                        <option value="All">All</option>
                        <?php foreach($committers as $committer) { ?>
                        <option <?php echo "value='$committer'" ?>>
                            <?php echo $committer ?>
                        </option>
                        <?php } ?>
                    </select>
                </div>
            </div>
        </div>
    <div>
  </section>
  <section class="section">
    <div class="container commits">
    </div>
  </section>
</body>


<script type="text/javascript">
var commits = <?php echo json_encode($commits);?>;
var repo_url = <?php echo json_encode($url) ?>;

$(document).ready(function(){
    console.log('ready')
    filter_data()

    function filter_data() {
        $('.commits').html('<div> Loading...</div>');
        var action = 'filter_data';
        var committer=$("#committers-dropdown")[0].value
        console.log(committer)
        $.ajax({
            url:"/filteredData.php",
            method:"POST",
            data:{action:action,committer:committer, commits:commits ,repo_url:repo_url},
            success:function(data){
                $('.commits').html(data);
            }
        });
    }


    $("#committers-dropdown").change(function(){
        filter_data();
    });
})

function quickview(e) {
       var searchBar=$(".search-bar")
       $(".quickview-block").html('<div class="loader">Loading...</div>');
       var action = 'search_repo';
       var query=searchBar[0].value
       var query_url = `https://api.github.com/search/repositories?q=${query}&sort=stars&order=desc`
        $.ajax({
            url:"/searchRepo.php",
            method:"POST",
            data:{action:action,query_url:query_url},
            success:function(data){
                $(".quickview-block").html(data);
            }
        });
    }

    bulmaQuickview.attach();
</script>

<div id="quickviewDefault" class="quickview">
  <header class="quickview-header">
    <p class="title"><strong>Choose your repository </strong></p>
    <span class="delete" data-dismiss="quickview"></span>
  </header>

  <div class="quickview-body">
    <div class="quickview-block">
    <div class="loader">Loading...</div>    
    </div>
  </div>
</div>
</html>