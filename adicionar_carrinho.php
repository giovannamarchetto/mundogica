<?php
include 'conexao.php';

// Redirecionar para login se não estiver logado
if(!isset($_SESSION['cliente_id'])) {
    $_SESSION['erro'] = "Você precisa fazer login para adicionar produtos ao carrinho!";
    header('Location: login.php');
    exit;
}

if($_SERVER['REQUEST_METHOD'] == 'POST') {
    $produto_id = intval($_POST['produto_id']);
    $quantidade = intval($_POST['quantidade']);
    
    if($quantidade <= 0) {
        $_SESSION['erro'] = "Quantidade inválida!";
        header('Location: ' . ($_SERVER['HTTP_REFERER'] ?? 'index.php'));
        exit;
    }
    
    // Buscar informações do produto
    $stmt = $conn->prepare("SELECT * FROM produtos WHERE id = ? AND estoque > 0");
    $stmt->bind_param("i", $produto_id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if($result->num_rows > 0) {
        $produto = $result->fetch_assoc();
        
        // Inicializar carrinho se não existir
        if(!isset($_SESSION['carrinho'])) {
            $_SESSION['carrinho'] = [];
        }
        
        // Verificar se produto já está no carrinho
        $produto_existe = false;
        foreach($_SESSION['carrinho'] as &$item) {
            if($item['produto_id'] == $produto_id) {
                $nova_quantidade = $item['quantidade'] + $quantidade;
                if($nova_quantidade <= $produto['estoque']) {
                    $item['quantidade'] = $nova_quantidade;
                    $_SESSION['sucesso'] = "Quantidade atualizada no carrinho!";
                } else {
                    $item['quantidade'] = $produto['estoque'];
                    $_SESSION['erro'] = "Adicionamos apenas as unidades disponíveis em estoque (" . $produto['estoque'] . ")";
                }
                $produto_existe = true;
                break;
            }
        }
        
        // Adicionar novo item se não existir
        if(!$produto_existe) {
            $_SESSION['carrinho'][] = [
                'produto_id' => $produto_id,
                'nome' => $produto['nome'],
                'preco' => $produto['preco'],
                'quantidade' => min($quantidade, $produto['estoque']),
                'imagem' => $produto['imagem']
            ];
            $_SESSION['sucesso'] = "Produto adicionado ao carrinho!";
        }
    } else {
        $_SESSION['erro'] = "Produto não encontrado ou esgotado!";
    }
}

header('Location: ' . ($_SERVER['HTTP_REFERER'] ?? 'index.php'));
exit;
?>