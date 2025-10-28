<?php
session_start();

$servername = "localhost";
$database = "loja_esmaltes";
$usuario = "root";
$senha = "usbw";

$conn = mysqli_connect($servername,$usuario,$senha,$database);

if(!$conn){
    die("Erro ao conectar ". mysqli_connect_error());
}
?>