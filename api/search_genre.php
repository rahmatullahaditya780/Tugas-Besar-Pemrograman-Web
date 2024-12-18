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

if (isset($_GET['genre'])){
    $genre = $_GET['genre'];
    $data_search = $dbConn->fetchRowMany("SELECT * FROM tb_anime WHERE genre LIKE '%$genre%' ORDER BY id_anime DESC");

    if(!($data_search == NULL)){
        echo json_encode([
            "status"=>true,
            "result"=>$data_search
        ],JSON_PRETTY_PRINT);
    }else{
        echo json_encode([
            "status"=>false,
            "msg"=>"Pencarian tidak ada hasil"
        ],JSON_PRETTY_PRINT);

    }
}else{
    echo json_encode([
        "status"=>false,
        "msg"=>"Parameter tidak valid"
    ],JSON_PRETTY_PRINT);
}

?>