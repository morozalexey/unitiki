<?php

$db_host = "localhost";
$db_name = "unitiki";
$db_user = "mysql";
$db_pass = "mysql";

$db = mysqli_connect ($db_host, $db_user, "", $db_name) or die ("Невозможно подключиться к БД");
mysqli_query ($db, 'set character_set_results = "utf8"');

$startFrom = $_POST['startFrom'];

$res = mysqli_query($db, "SELECT * FROM posts, categories WHERE categories.id = posts.category_id AND hide = 1 ORDER BY posts.dt_add DESC LIMIT {$startFrom}, 10");

$articles = array();
while ($row = mysqli_fetch_assoc($res))
{
    $articles[] = $row;
}

echo json_encode($articles);
