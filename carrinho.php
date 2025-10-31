<?php
include 'conexao.php';

// Redirecionar se não estiver logado
if(!isset($_SESSION['cliente_id'])) {
    $_SESSION['erro'] = "Você precisa fazer login para acessar o carrinho!";
    header('Location: login.php');
    exit;
}

// Remover item do carrinho
if(isset($_GET['remover'])) {
    $produto_id = $_GET['remover'];
    
    if(isset($_SESSION['carrinho'])) {
        foreach($_SESSION['carrinho'] as $key => $item) {
            if($item['produto_id'] == $produto_id) {
                unset($_SESSION['carrinho'][$key]);
                $_SESSION['carrinho'] = array_values($_SESSION['carrinho']);
                $_SESSION['sucesso'] = "Produto removido do carrinho!";
                break;
            }
        }
    }
    header('Location: carrinho.php');
    exit;
}

// Pegar itens do carrinho
$itens_carrinho = array();
if(isset($_SESSION['carrinho'])) {
    $itens_carrinho = $_SESSION['carrinho'];
}

$total_carrinho = 0;
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Carrinho - Mundo GiCa</title>
    <link rel="stylesheet" href="style.css">
    <style>
        .carrinho-container {
            max-width: 1200px;
            margin: 100px auto 50px;
            padding: 20px;
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
            background: white;
            border-radius: 12px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.05);
        }
        
        .carrinho-vazio a {
            color: #7c3aed;
            font-weight: 600;
            font-size: 1.2rem;
        }
        
        .carrinho-itens {
            background: white;
            border-radius: 12px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.05);
            padding: 20px;
        }
        
        .carrinho-item {
            display: grid;
            grid-template-columns: 100px 1fr auto auto;
            gap: 20px;
            align-items: center;
            padding: 20px;
            border-bottom: 2px solid #e2e8f0;
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
        
        .btn-remover {
            padding: 10px 20px;
            background-color: #ef4444;
            color: white;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            text-decoration: none;
            font-weight: 600;
        }
        
        .btn-remover:hover {
            background-color: #dc2626;
        }
        
        .carrinho-total {
            text-align: right;
            margin-top: 30px;
            font-size: 2rem;
            font-weight: bold;
            color: #334155;
            padding: 20px;
            background: white;
            border-radius: 12px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.05);
        }
        
        .btn-finalizar {
            display: block;
            width: 300px;
            margin: 30px auto;
            padding: 15px;
            background-color: #10b981;
            color: white;
            border: none;
            border-radius: 8px;
            font-size: 1.1rem;
            text-align: center;
            text-decoration: none;
            font-weight: 600;
        }
        
        .btn-finalizar:hover {
            background-color: #059669;
        }
    </style>
</head>
<body>
    <?php include 'header.php'; ?>
    
    <div class="carrinho-container">
        <h1 class="carrinho-titulo">🛒 Meu Carrinho</h1>
        
        <?php if(empty($itens_carrinho)): ?>
            <div class="carrinho-vazio">
                <h2>😢 Seu carrinho está vazio</h2>
                <p><a href="index.php">← Continue comprando</a></p>
            </div>
        <?php else: ?>
            <div class="carrinho-itens">
                <?php foreach($itens_carrinho as $item): 
                    $subtotal = $item['preco'] * $item['quantidade'];
                    $total_carrinho += $subtotal;
                ?>
                    <div class="carrinho-item">
                        <div class="item-imagem">
                            <img src="<?php echo $item['imagem']; ?>" alt="<?php echo $item['nome']; ?>">
                        </div>
                        
                        <div class="item-info">
                            <h3><?php echo $item['nome']; ?></h3>
                            <div class="item-preco">R$ <?php echo number_format($item['preco'], 2, ',', '.'); ?> cada</div>
                            <div>Quantidade: <?php echo $item['quantidade']; ?></div>
                        </div>
                        
                        <div class="item-subtotal">
                            <strong>R$ <?php echo number_format($subtotal, 2, ',', '.'); ?></strong>
                        </div>
                        
                        <a href="carrinho.php?remover=<?php echo $item['produto_id']; ?>" 
                           class="btn-remover" 
                           onclick="return confirm('Deseja remover este produto?')">
                            🗑️ Remover
                        </a>
                    </div>
                <?php endforeach; ?>
            </div>
            
            <div class="carrinho-total">
                💰 Total: R$ <?php echo number_format($total_carrinho, 2, ',', '.'); ?>
            </div>
            
            <a href="finalizar_pedido.php" class="btn-finalizar">✅ Finalizar Pedido</a>
        <?php endif; ?>
    </div>
</body>
</html>