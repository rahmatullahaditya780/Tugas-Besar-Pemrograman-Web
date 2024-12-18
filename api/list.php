<?php 
header("content-type: application/json");
use Simplon\Mysql\Mysql;
use Simplon\Mysql\PDOConnector;
require_once("vendor/autoload.php");

$pdo = new PDOConnector(
	'localhost', // server
	'root',      // user
	'',      // password
	'db_anime'   // database
);

$pdoConn = $pdo->connect('utf8', []);
$dbConn = new Mysql($pdoConn);

$result = $dbConn->fetchRowMany("SELECT * FROM tb_episode ORDER BY id_episode DESC LIMIT 12");
$result_fix = [];
foreach($result as $list){
    $list = (object)$list;
    $list->detail_anime = $dbConn->fetchRow("SELECT * FROM tb_anime WHERE id_anime = $list->id_anime");
    $result_fix[] = $list;
}

echo json_encode($result_fix,JSON_PRETTY_PRINT);
?>