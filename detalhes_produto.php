<?php
include 'conexao.php';

if(!isset($_GET['id'])) {
    header('Location: index.php');
    exit;
}

$produto_id = intval($_GET['id']);
$stmt = $conn->prepare("SELECT * FROM produtos WHERE id = ?");
$stmt->bind_param("i", $produto_id);
$stmt->execute();
$result = $stmt->get_result();

if($result->num_rows == 0) {
    header('Location: index.php');
    exit;
}

$produto = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $produto['nome']; ?> - Mundo GiCa</title>
    <link rel="stylesheet" href="style.css">
    <style>
        .produto-detalhes {
            max-width: 1200px;
            margin: 100px auto 50px;
            padding: 0 20px;
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 40px;
            align-items: start;
        }
        
        .produto-imagem img {
            width: 100%;
            border-radius: 12px;
            box-shadow: 0 10px 25px rgba(0,0,0,0.1);
        }
        
        .produto-info h1 {
            font-size: 2.5rem;
            color: #334155;
            margin-bottom: 15px;
        }
        
        .produto-marca {
            font-size: 1.2rem;
            color: #7c3aed;
            margin-bottom: 20px;
            font-weight: 600;
        }
        
        .produto-preco {
            font-size: 2rem;
            color: #7c3aed;
            font-weight: bold;
            margin-bottom: 20px;
        }
        
        .produto-descricao {
            color: #64748b;
            line-height: 1.6;
            margin-bottom: 30px;
            font-size: 1.1rem;
        }
        
        .add-carrinho-form {
            display: flex;
            gap: 15px;
            align-items: center;
            margin-bottom: 30px;
        }
        
        .quantidade-input {
            width: 80px;
            padding: 12px;
            border: 2px solid #e2e8f0;
            border-radius: 8px;
            text-align: center;
        }
        
        .btn-adicionar {
            padding: 15px 30px;
            background-color: #7c3aed;
            color: white;
            border: none;
            border-radius: 8px;
            font-size: 1.1rem;
            cursor: pointer;
            transition: background-color 0.3s;
        }
        
        .btn-adicionar:hover {
            background-color: #6d28d9;
        }
        
        .estoque-info {
            color: #059669;
            font-weight: 600;
            margin-bottom: 20px;
        }
        
        .voltar-link {
            display: inline-block;
            margin-top: 20px;
            color: #7c3aed;
            text-decoration: none;
            font-weight: 600;
        }
        
        @media (max-width: 768px) {
            .produto-detalhes {
                grid-template-columns: 1fr;
                gap: 20px;
            }
        }
    </style>
</head>
<body>
    <?php include 'header.php'; ?>
    
    <div class="produto-detalhes">
        <div class="produto-imagem">
            <img src="<?php echo $produto['imagem']; ?>" alt="<?php echo $produto['nome']; ?>">
        </div>
        
        <div class="produto-info">
            <h1><?php echo $produto['nome']; ?></h1>
            <div class="produto-marca">Marca: <?php echo $produto['marca']; ?></div>
            <div class="produto-preco">R$ <?php echo number_format($produto['preco'], 2, ',', '.'); ?></div>
            
            <div class="estoque-info">
                <?php 
                if($produto['estoque'] > 10) {
                    echo "Em estoque";
                } elseif($produto['estoque'] > 0) {
                    echo "Últimas unidades!";
                } else {
                    echo "Produto esgotado";
                }
                ?>
            </div>
            
            <div class="produto-descricao">
                <?php echo nl2br(htmlspecialchars($produto['descricao'])); ?>
            </div>
            
            <?php if($produto['estoque'] > 0): ?>
            <form method="POST" action="adicionar_carrinho.php" class="add-carrinho-form">
                <input type="hidden" name="produto_id" value="<?php echo $produto['id']; ?>">
                <input type="number" name="quantidade" value="1" min="1" max="<?php echo $produto['estoque']; ?>" class="quantidade-input">
                <button type="submit" class="btn-adicionar">Adicionar ao Carrinho</button>
            </form>
            <?php else: ?>
                <button class="btn-adicionar" disabled>Produto Esgotado</button>
            <?php endif; ?>
            
            <a href="index.php" class="voltar-link">← Voltar para a loja</a>
        </div>
    </div>
</body>
</html>