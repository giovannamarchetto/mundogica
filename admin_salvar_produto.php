<?php
include 'conexao.php';

if(!isset($_SESSION['cliente_id'])) {
    header('Location: login.php');
    exit;
}

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
    $nome = mysqli_real_escape_string($conn, trim($_POST['nome']));
    $descricao = mysqli_real_escape_string($conn, trim($_POST['descricao']));
    $preco = floatval($_POST['preco']);
    $marca = mysqli_real_escape_string($conn, trim($_POST['marca']));
    $estoque = intval($_POST['estoque']);
    $imagem = mysqli_real_escape_string($conn, trim($_POST['imagem']));
    
    if(isset($_POST['id']) && !empty($_POST['id'])) {
        $id = intval($_POST['id']);
        $sql = "UPDATE produtos SET nome = '$nome', descricao = '$descricao', preco = $preco, 
                marca = '$marca', estoque = $estoque, imagem = '$imagem' WHERE id = $id";
        
        if(mysqli_query($conn, $sql)) {
            $_SESSION['sucesso'] = "Produto atualizado com sucesso!";
        } else {
            $_SESSION['erro'] = "Erro ao atualizar produto: " . mysqli_error($conn);
        }
    } else {
        $sql = "INSERT INTO produtos (nome, descricao, preco, marca, estoque, imagem) 
                VALUES ('$nome', '$descricao', $preco, '$marca', $estoque, '$imagem')";
        
        if(mysqli_query($conn, $sql)) {
            $_SESSION['sucesso'] = "Produto cadastrado com sucesso!";
        } else {
            $_SESSION['erro'] = "Erro ao cadastrar produto: " . mysqli_error($conn);
        }
    }
    
    header('Location: admin.php');
    exit;
}
?>