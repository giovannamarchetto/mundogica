<?php
include 'conexao.php';

// Criar um usuário administrador (execute apenas uma vez)
$nome = "Administrador";
$email = "admin@mundogica.com";
$senha = password_hash("admin123", PASSWORD_DEFAULT);
$telefone = "(16) 99999-9999";

$stmt = $conn->prepare("INSERT INTO clientes (nome, email, senha, telefone, admin) VALUES (?, ?, ?, ?, 1)");
$stmt->bind_param("ssss", $nome, $email, $senha, $telefone);

if($stmt->execute()) {
    echo "Administrador criado com sucesso!<br>";
    echo "Email: admin@mundogica.com<br>";
    echo "Senha: admin123";
} else {
    echo "Erro ao criar administrador: " . $conn->error;
}
?>