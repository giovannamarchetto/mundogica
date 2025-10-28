<?php
include 'conexao.php';

if(!isset($_SESSION['cliente_id'])) {
    header('Location: login.php');
    exit;
}

if(isset($_GET['id'])) {
    $id = intval($_GET['id']);
    
    $stmt = $conn->prepare("DELETE FROM produtos WHERE id = ?");
    $stmt->bind_param("i", $id);
    
    if($stmt->execute()) {
        $_SESSION['sucesso'] = "Produto excluído com sucesso!";
    } else {
        $_SESSION['erro'] = "Erro ao excluir produto!";
    }
}

header('Location: admin.php');
exit;
?>