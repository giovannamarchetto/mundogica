<?php
include 'conexao.php';

if(!isset($_SESSION['cliente_id'])) {
    header('Location: login.php');
    exit;
}

if(isset($_GET['pedido_id']) && isset($_GET['status'])) {
    $pedido_id = intval($_GET['pedido_id']);
    $status = $_GET['status'];
    
    $stmt = $conn->prepare("UPDATE pedidos SET status = ? WHERE id = ?");
    $stmt->bind_param("si", $status, $pedido_id);
    
    if($stmt->execute()) {
        $_SESSION['sucesso'] = "Status atualizado com sucesso!";
    } else {
        $_SESSION['erro'] = "Erro ao atualizar status!";
    }
}

header('Location: admin.php');
exit;
?>