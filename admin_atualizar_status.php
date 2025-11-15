<?php
include 'conexao.php';

if(!isset($_SESSION['cliente_id'])) {
    $_SESSION['erro'] = "Você precisa fazer login!";
    header('Location: login.php');
    exit;
}

$stmt = $conn->prepare("SELECT admin FROM clientes WHERE id = ?");
$stmt->bind_param("i", $_SESSION['cliente_id']);
$stmt->execute();
$result = $stmt->get_result();
$usuario = $result->fetch_assoc();

if(!$usuario || $usuario['admin'] != 1) {
    $_SESSION['erro'] = "Você não tem permissão para atualizar status de pedidos!";
    header('Location: index.php');
    exit;
}

if(!isset($_GET['pedido_id']) || !isset($_GET['status'])) {
    $_SESSION['erro'] = "Parâmetros inválidos!";
    header('Location: admin.php');
    exit;
}

$pedido_id = intval($_GET['pedido_id']);
$status = $_GET['status'];

$status_validos = ['pendente', 'pago', 'enviado', 'entregue'];
if(!in_array($status, $status_validos)) {
    $_SESSION['erro'] = "Status inválido!";
    header('Location: admin.php');
    exit;
}

// Verificar se o pedido existe
$stmt = $conn->prepare("SELECT id FROM pedidos WHERE id = ?");
$stmt->bind_param("i", $pedido_id);
$stmt->execute();
$result = $stmt->get_result();

if($result->num_rows == 0) {
    $_SESSION['erro'] = "Pedido não encontrado!";
    header('Location: admin.php');
    exit;
}

// Atualizar status do pedido
$stmt = $conn->prepare("UPDATE pedidos SET status = ? WHERE id = ?");
$stmt->bind_param("si", $status, $pedido_id);

if($stmt->execute()) {
    $_SESSION['sucesso'] = "Status do pedido #$pedido_id atualizado para '" . ucfirst($status) . "' com sucesso!";
} else {
    $_SESSION['erro'] = "Erro ao atualizar status: " . $conn->error;
}

$stmt->close();
$conn->close();

header('Location: admin.php');
exit;
?>