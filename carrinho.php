<?php
include 'conexao.php';

// Redirecionar se não estiver logado
if(!isset($_SESSION['cliente_id'])) {
    header('Location: login.php');
    exit;
}

// Processar remoção de item
if(isset($_GET['remover'])) {
    $produto_id = intval($_GET['remover']);
    
    if(isset($_SESSION['carrinho'])) {
        foreach($_SESSION['carrinho'] as $key => $item) {
            if($item['produto_id'] == $produto_id) {
                unset($_SESSION['carrinho'][$key]);
                $_SESSION['carrinho'] = array_values($_SESSION['carrinho']); // Reindexar array
                $_SESSION['sucesso'] = "Produto removido do carrinho!";
                break;
            }
        }
    }
}

// Processar atualização de quantidade
if($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['atualizar_quantidade'])) {
    $produto_id = intval($_POST['produto_id']);
    $quantidade = intval($_POST['quantidade']);
    
    if(isset($_SESSION['carrinho'])) {
        foreach($_SESSION['carrinho'] as &$item) {
            if($item['produto_id'] == $produto_id) {
                if($quantidade > 0) {
                    // Verificar estoque
                    $stmt = $conn->prepare("SELECT estoque FROM produtos WHERE id = ?");
                    $stmt->bind_param("i", $produto_id);
                    $stmt->execute();
                    $result = $stmt->get_result();
                    $produto = $result->fetch_assoc();
                    
                    $item['quantidade'] = min($quantidade, $produto['estoque']);
                } else {
                    unset($_SESSION['carrinho'][$key]);
                    $_SESSION['carrinho'] = array_values($_SESSION['carrinho']);
                }
                break;
            }
        }
    }
}

$total_carrinho = 0;
$itens_carrinho = $_SESSION['carrinho'] ?? [];
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Carrinho - Mundo GiCa</title>
    <link rel="stylesheet" href="style.css">
    <style>
        .carrinho-container {
            max-width: 1200px;
            margin: 100px auto 50px;
            padding: 0 20px;
        }
        
        .carrinho-titulo {
            font-size: 2.5rem;
            color: #334155;
            margin-bottom: 30px;
            text-align: center;
        }
        
        .carrinho-vazio {
            text-align: center;
            padding: 60px 20px;
            color: #64748b;
        }
        
        .carrinho-vazio a {
            color: #7c3aed;
            text-decoration: none;
            font-weight: 600;
        }
        
        .carrinho-itens {
            background: white;
            border-radius: 12px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.05);
            overflow: hidden;
        }
        
        .carrinho-item {
            display: grid;
            grid-template-columns: 100px 1fr auto auto auto;
            gap: 20px;
            align-items: center;
            padding: 20px;
            border-bottom: 1px solid #e2e8f0;
        }
        
        .carrinho-item:last-child {
            border-bottom: none;
        }
        
        .item-imagem img {
            width: 80px;
            height: 80px;
            object-fit: cover;
            border-radius: 8px;
        }
        
        .item-info h3 {
            margin-bottom: 5px;
            color: #334155;
        }
        
        .item-preco {
            color: #7c3aed;
            font-weight: 600;
        }
        
        .quantidade-form {
            display: flex;
            align-items: center;
            gap: 10px;
        }
        
        .quantidade-input {
            width: 60px;
            padding: 8px;
            border: 2px solid #e2e8f0;
            border-radius: 6px;
            text-align: center;
        }
        
        .btn-atualizar {
            padding: 8px 15px;
            background-color: #7c3aed;
            color: white;
            border: none;
            border-radius: 6px;
            cursor: pointer;
        }
        
        .btn-remover {
            padding: 8px 15px;
            background-color: #ef4444;
            color: white;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            text-decoration: none;
            font-size: 0.9rem;
        }
        
        .carrinho-total {
            text-align: right;
            margin-top: 30px;
            font-size: 1.5rem;
            font-weight: bold;
            color: #334155;
        }
        
        .btn-finalizar {
            display: block;
            width: 200px;
            margin: 30px auto;
            padding: 15px;
            background-color: #7c3aed;
            color: white;
            border: none;
            border-radius: 8px;
            font-size: 1.1rem;
            cursor: pointer;
            text-align: center;
            text-decoration: none;
        }
        
        .alert {
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 20px;
        }
        
        .alert-sucesso {
            background-color: #d1fae5;
            color: #065f46;
            border: 1px solid #a7f3d0;
        }
        
        @media (max-width: 768px) {
            .carrinho-item {
                grid-template-columns: 80px 1fr;
                gap: 15px;
            }
            
            .item-acoes {
                grid-column: 1 / -1;
                display: flex;
                justify-content: space-between;
                align-items: center;
            }
        }
    </style>
</head>
<body>
    <?php include 'header.php'; ?>
    
    <div class="carrinho-container">
        <h1 class="carrinho-titulo">Meu Carrinho</h1>
        
        <?php if(isset($_SESSION['sucesso'])): ?>
            <div class="alert alert-sucesso"><?php echo $_SESSION['sucesso']; unset($_SESSION['sucesso']); ?></div>
        <?php endif; ?>
        
        <?php if(empty($itens_carrinho)): ?>
            <div class="carrinho-vazio">
                <h2>Seu carrinho está vazio</h2>
                <p><a href="index.php">Continue comprando</a></p>
            </div>
        <?php else: ?>
            <div class="carrinho-itens">
                <?php 
                $total_carrinho = 0;
                foreach($itens_carrinho as $item): 
                    $subtotal = $item['preco'] * $item['quantidade'];
                    $total_carrinho += $subtotal;
                ?>
                    <div class="carrinho-item">
                        <div class="item-imagem">
                            <img src="<?php echo $item['imagem']; ?>" alt="<?php echo $item['nome']; ?>">
                        </div>
                        
                        <div class="item-info">
                            <h3><?php echo $item['nome']; ?></h3>
                            <div class="item-preco">R$ <?php echo number_format($item['preco'], 2, ',', '.'); ?></div>
                        </div>
                        
                        <form method="POST" action="carrinho.php" class="quantidade-form">
                            <input type="hidden" name="produto_id" value="<?php echo $item['produto_id']; ?>">
                            <input type="number" name="quantidade" value="<?php echo $item['quantidade']; ?>" min="1" class="quantidade-input">
                            <button type="submit" name="atualizar_quantidade" class="btn-atualizar">Atualizar</button>
                        </form>
                        
                        <div class="item-subtotal">
                            R$ <?php echo number_format($subtotal, 2, ',', '.'); ?>
                        </div>
                        
                        <a href="carrinho.php?remover=<?php echo $item['produto_id']; ?>" class="btn-remover">Remover</a>
                    </div>
                <?php endforeach; ?>
            </div>
            
            <div class="carrinho-total">
                Total: R$ <?php echo number_format($total_carrinho, 2, ',', '.'); ?>
            </div>
            
            <a href="finalizar_pedido.php" class="btn-finalizar">Finalizar Pedido</a>
        <?php endif; ?>
    </div>
</body>
</html>