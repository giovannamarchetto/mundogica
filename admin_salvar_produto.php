<?php
include 'conexao.php';

if(!isset($_SESSION['cliente_id'])) {
    header('Location: login.php');
    exit;
}

// Verificar se é administrador
$stmt = $conn->prepare("SELECT admin FROM clientes WHERE id = ?");
$stmt->bind_param("i", $_SESSION['cliente_id']);
$stmt->execute();
$result = $stmt->get_result();
$usuario = $result->fetch_assoc();

if(!$usuario || $usuario['admin'] != 1) {
    header('Location: index.php');
    exit;
}

if($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nome = trim($_POST['nome']);
    $descricao = trim($_POST['descricao']);
    $preco = floatval($_POST['preco']);
    $marca = trim($_POST['marca']);
    $estoque = intval($_POST['estoque']);
    $imagem = trim($_POST['imagem']);
    
    // Verificar se é edição ou novo produto
    if(isset($_POST['id']) && !empty($_POST['id'])) {
        // Editar produto existente
        $id = intval($_POST['id']);
        $stmt = $conn->prepare("UPDATE produtos SET nome = ?, descricao = ?, preco = ?, marca = ?, estoque = ?, imagem = ? WHERE id = ?");
        $stmt->bind_param("ssdsisi", $nome, $descricao, $preco, $marca, $estoque, $imagem, $id);
        
        if($stmt->execute()) {
            $_SESSION['sucesso'] = "Produto atualizado com sucesso!";
        } else {
            $_SESSION['erro'] = "Erro ao atualizar produto!";
        }
    } else {
        // Inserir novo produto
        $stmt = $conn->prepare("INSERT INTO produtos (nome, descricao, preco, marca, estoque, imagem) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("ssdsis", $nome, $descricao, $preco, $marca, $estoque, $imagem);
        
        if($stmt->execute()) {
            $_SESSION['sucesso'] = "Produto cadastrado com sucesso!";
        } else {
            $_SESSION['erro'] = "Erro ao cadastrar produto!";
        }
    }
    
    header('Location: admin.php');
    exit;
}
?>