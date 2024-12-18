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

    $data_episode = $dbConn->fetchRowMany("SELECT * FROM tb_genre");

    if(!($data_episode == NULL)){
        echo json_encode([
            "status"=>true,
            "result"=>$data_episode
        ],JSON_PRETTY_PRINT);
    }else{
        echo json_encode([
            "status"=>false,
            "msg"=>"Episode tidak ditemukan"
        ],JSON_PRETTY_PRINT);

    }

?>