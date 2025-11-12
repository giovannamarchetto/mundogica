<?php
include 'conexao.php';

// Verificar se está logado
if(!isset($_SESSION['cliente_id'])) {
    $_SESSION['erro'] = "Você precisa fazer login!";
    header('Location: login.php');
    exit;
}

// Verificar se é admin
$stmt = $conn->prepare("SELECT admin FROM clientes WHERE id = ?");
$stmt->bind_param("i", $_SESSION['cliente_id']);
$stmt->execute();
$result = $stmt->get_result();
$usuario = $result->fetch_assoc();

if(!$usuario || $usuario['admin'] != 1) {
    $_SESSION['erro'] = "Você não tem permissão para excluir produtos!";
    header('Location: index.php');
    exit;
}

// Verificar se o ID foi passado
if(!isset($_GET['id']) || empty($_GET['id'])) {
    $_SESSION['erro'] = "ID do produto não informado!";
    header('Location: admin.php');
    exit;
}

$id = intval($_GET['id']);

// Verificar se o produto existe
$stmt = $conn->prepare("SELECT nome FROM produtos WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();

if($result->num_rows == 0) {
    $_SESSION['erro'] = "Produto não encontrado!";
    header('Location: admin.php');
    exit;
}

$produto = $result->fetch_assoc();
$nome_produto = $produto['nome'];

// Excluir o produto
$stmt = $conn->prepare("DELETE FROM produtos WHERE id = ?");
$stmt->bind_param("i", $id);

if($stmt->execute()) {
    $_SESSION['sucesso'] = "Produto '$nome_produto' excluído com sucesso!";
} else {
    $_SESSION['erro'] = "Erro ao excluir produto: " . $conn->error;
}

$stmt->close();
$conn->close();

header('Location: admin.php');
exit;
?>