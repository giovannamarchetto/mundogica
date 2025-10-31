<?php
// Mostrar erros (remova depois da apresentação)
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Iniciar sessão
session_start();

// Configurações do banco de dados
$servername = "localhost";
$database = "loja_esmaltes1";
$usuario = "root";
$senha = "usbw"; // AJUSTE AQUI: coloque sua senha do MySQL

// Conectar ao banco
$conn = mysqli_connect($servername, $usuario, $senha, $database);

// Verificar se conectou
if(!$conn){
    die("Erro ao conectar ao banco de dados: " . mysqli_connect_error());
}

// Configurar charset para UTF-8
mysqli_set_charset($conn, "utf8");
?>