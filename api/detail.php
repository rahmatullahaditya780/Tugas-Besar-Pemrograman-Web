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

if (isset($_GET['anime'])){
    $id_anime = $_GET['anime'];
    $result = $dbConn->fetchRow("SELECT * FROM tb_anime WHERE id_anime=$id_anime");
    $list_episode = $dbConn->fetchRowMany("SELECT id_anime,id_episode,episode FROM tb_episode WHERE id_anime=$id_anime ORDER BY id_episode DESC");
    if ((!$result == NULL)){
        echo json_encode([
            "status"=>true,
            "detail"=>$result,
            "list_episode"=>$list_episode
        ],JSON_PRETTY_PRINT);
    }else{
        echo json_encode([
            "status"=>false,
            "msg"=>"Data tidak ditemukan"
        ],JSON_PRETTY_PRINT);
    }
    

}else{
    echo json_encode([
        "status"=>false,
        "msg"=>"Parameter tidak valid"
    ],JSON_PRETTY_PRINT);
}

?>