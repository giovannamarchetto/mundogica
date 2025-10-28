<?php
include 'conexao.php';

// Redirecionar se não estiver logado
if(!isset($_SESSION['cliente_id'])) {
    header('Location: login.php');
    exit;
}

// Redirecionar se carrinho estiver vazio
if(empty($_SESSION['carrinho'])) {
    header('Location: carrinho.php');
    exit;
}

// Processar finalização do pedido
if($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Calcular total
    $total = 0;
    foreach($_SESSION['carrinho'] as $item) {
        $total += $item['preco'] * $item['quantidade'];
    }
    
    // Inserir pedido
    $stmt = $conn->prepare("INSERT INTO pedidos (cliente_id, total, status) VALUES (?, ?, 'pendente')");
    $stmt->bind_param("id", $_SESSION['cliente_id'], $total);
    $stmt->execute();
    $pedido_id = $stmt->insert_id;
    
    // Inserir itens do pedido e atualizar estoque
    foreach($_SESSION['carrinho'] as $item) {
        $stmt = $conn->prepare("INSERT INTO pedido_itens (pedido_id, produto_id, quantidade, preco) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("iiid", $pedido_id, $item['produto_id'], $item['quantidade'], $item['preco']);
        $stmt->execute();
        
        // Atualizar estoque
        $stmt = $conn->prepare("UPDATE produtos SET estoque = estoque - ? WHERE id = ?");
        $stmt->bind_param("ii", $item['quantidade'], $item['produto_id']);
        $stmt->execute();
    }
    
    // Limpar carrinho
    unset($_SESSION['carrinho']);
    
    $_SESSION['pedido_sucesso'] = "Pedido #$pedido_id realizado com sucesso!";
    header('Location: index.php');
    exit;
}

// Calcular total para exibição
$total = 0;
foreach($_SESSION['carrinho'] as $item) {
    $total += $item['preco'] * $item['quantidade'];
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Finalizar Pedido - Mundo GiCa</title>
    <link rel="stylesheet" href="style.css">
    <style>
        .finalizar-container {
            max-width: 800px;
            margin: 100px auto 50px;
            padding: 0 20px;
        }
        
        .finalizar-titulo {
            font-size: 2.5rem;
            color: #334155;
            margin-bottom: 30px;
            text-align: center;
        }
        
        .resumo-pedido {
            background: white;
            border-radius: 12px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.05);
            padding: 30px;
            margin-bottom: 30px;
        }
        
        .resumo-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 15px 0;
            border-bottom: 1px solid #e2e8f0;
        }
        
        .resumo-item:last-child {
            border-bottom: none;
        }
        
        .item-info {
            display: flex;
            align-items: center;
            gap: 15px;
        }
        
        .item-imagem img {
            width: 60px;
            height: 60px;
            object-fit: cover;
            border-radius: 6px;
        }
        
        .total-pedido {
            text-align: right;
            font-size: 1.5rem;
            font-weight: bold;
            color: #334155;
            margin-top: 20px;
            padding-top: 20px;
            border-top: 2px solid #e2e8f0;
        }
        
        .form-finalizar {
            background: white;
            border-radius: 12px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.05);
            padding: 30px;
        }
        
        .form-group {
            margin-bottom: 20px;
        }
        
        .form-group label {
            display: block;
            margin-bottom: 5px;
            font-weight: 600;
            color: #334155;
        }
        
        .form-group input,
        .form-group select,
        .form-group textarea {
            width: 100%;
            padding: 12px;
            border: 2px solid #e2e8f0;
            border-radius: 8px;
            font-size: 1rem;
            transition: border-color 0.3s;
        }
        
        .form-group input:focus,
        .form-group select:focus,
        .form-group textarea:focus {
            outline: none;
            border-color: #7c3aed;
        }
        
        .btn-finalizar {
            width: 100%;
            padding: 15px;
            background-color: #7c3aed;
            color: white;
            border: none;
            border-radius: 8px;
            font-size: 1.1rem;
            cursor: pointer;
            transition: background-color 0.3s;
        }
        
        .btn-finalizar:hover {
            background-color: #6d28d9;
        }
    </style>
</head>
<body>
    <?php include 'header.php'; ?>
    
    <div class="finalizar-container">
        <h1 class="finalizar-titulo">Finalizar Pedido</h1>
        
        <div class="resumo-pedido">
            <h2>Resumo do Pedido</h2>
            <?php foreach($_SESSION['carrinho'] as $item): ?>
                <div class="resumo-item">
                    <div class="item-info">
                        <div class="item-imagem">
                            <img src="<?php echo $item['imagem']; ?>" alt="<?php echo $item['nome']; ?>">
                        </div>
                        <div>
                            <h3><?php echo $item['nome']; ?></h3>
                            <p>Quantidade: <?php echo $item['quantidade']; ?></p>
                        </div>
                    </div>
                    <div class="item-preco">
                        R$ <?php echo number_format($item['preco'] * $item['quantidade'], 2, ',', '.'); ?>
                    </div>
                </div>
            <?php endforeach; ?>
            
            <div class="total-pedido">
                Total: R$ <?php echo number_format($total, 2, ',', '.'); ?>
            </div>
        </div>
        
        <form method="POST" action="" class="form-finalizar">
            <h2>Informações de Entrega</h2>
            
            <div class="form-group">
                <label for="endereco">Endereço de Entrega</label>
                <textarea id="endereco" name="endereco" rows="3" required placeholder="Rua, número, bairro, cidade..."></textarea>
            </div>
            
            <div class="form-group">
                <label for="cep">CEP</label>
                <input type="text" id="cep" name="cep" required>
            </div>
            
            <div class="form-group">
                <label for="forma_pagamento">Forma de Pagamento</label>
                <select id="forma_pagamento" name="forma_pagamento" required>
                    <option value="">Selecione...</option>
                    <option value="cartao">Cartão de Crédito</option>
                    <option value="boleto">Boleto Bancário</option>
                    <option value="pix">PIX</option>
                </select>
            </div>
            
            <div class="form-group">
                <label for="observacoes">Observações</label>
                <textarea id="observacoes" name="observacoes" rows="2" placeholder="Alguma observação sobre o pedido..."></textarea>
            </div>
            
            <button type="submit" class="btn-finalizar">Confirmar Pedido</button>
        </form>
    </div>
</body>
</html>