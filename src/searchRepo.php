<?php

$opts = ["http" => ["method" => "GET", "header" => "User-Agent: hamza-kadiri"]];
$context = stream_context_create($opts);


// get the search api link matching the query \\
if(isset($_POST["query_url"])){
    $query_url = $_POST["query_url"];
}
else {
    echo "Error : query_url is missing";
}

$data = file_get_contents($query_url, false, $context);
$json = json_decode($data, true); 

$format_results = function ($result)
{
  return array(
    "name" => $result['name'],
    "description" => $result['description'],
    "url" => $result['url'],
    "stars" => $result["stargazers_count"],

  );
};

// keep only the informations that we'll display \\

$results = array_map($format_results, $json['items']);


?>

<section class="section">
    <?php foreach($results as $result) { ?>
    <div class="card">
        <div class="card-content">
            <div class="level">
                <div class="level-left">
                    <p class="title is-4">
                        <a href=<? echo "/index.php/?url=" . $result['url'] . "/commits" ?>><? echo $result['name'] ?></a>
                    </p>
                </div>
                <div class="level-right">
                    <div>
                        <? echo $result['stars'] ?>
                    </div>
                    <span class="icon star-icon is-small"><i class="fas fa-star"></i></span>
                </div>
            </div>
            <div class="content">
                <p class="subtitle is-6">
                    <? echo $result['description'] ?>
                </p>
            </div>
        </div>
    </div>
    <?php }?>
</section>