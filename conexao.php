<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();

$servername = "localhost";
$database = "loja_esmaltes1";
$usuario = "root";
$senha = "usbw";

$conn = mysqli_connect($servername, $usuario, $senha, $database);

if(!$conn){
    die("Erro ao conectar ao banco de dados: " . mysqli_connect_error());
}

mysqli_set_charset($conn, "utf8");
?>