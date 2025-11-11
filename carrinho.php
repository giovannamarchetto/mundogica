<?php
include 'conexao.php';

if(!isset($_SESSION['cliente_id'])) {
    $_SESSION['erro'] = "Você precisa fazer login para acessar o carrinho!";
    header('Location: login.php');
    exit;
}

// ARRAY COM IMAGENS FIXAS - MUITO IMPORTANTE!
$imagens_produtos = [
    1 => 'img/esmalteamar.png',
    2 => 'img/esmalteescarlate.png',
    3 => 'img/esmaltesoutopping.png',
    4 => 'img/esmalteanonovonorio.png',
    5 => 'img/esmalteolhogrego.png',
    6 => 'img/esmaltetachovendofini.png',
    7 => 'img/esmalteimensidao.png',
    8 => 'img/esmaltedizeres.png',
    9 => 'img/esmaltecaricia.png',
    10 => 'img/esmaltelaçadaperfeita.png',
    11 => 'img/esmaltevermelhaco.png',
    12 => 'img/esmalteazulliberdade.png',
    13 => 'img/esmaltesana.png',
    14 => 'img/esmalteshits.png',
    15 => 'img/esmaltesanita.png'
];

if(isset($_GET['remover'])) {
    $produto_id = intval($_GET['remover']);
    
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
            grid-template-columns: 120px 1fr auto auto;
            gap: 20px;
            align-items: center;
            padding: 20px;
            border-bottom: 2px solid #e2e8f0;
        }
        
        .carrinho-item:last-child {
            border-bottom: none;
        }
        
        .item-imagem img {
            width: 100px;
            height: 100px;
            object-fit: cover;
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        }
        
        .item-info h3 {
            margin-bottom: 8px;
            color: #334155;
            font-size: 1.2rem;
        }
        
        .item-preco {
            color: #7c3aed;
            font-weight: 600;
            font-size: 1.1rem;
            margin-bottom: 5px;
        }
        
        .item-quantidade {
            color: #64748b;
            font-size: 0.95rem;
        }
        
        .item-subtotal {
            font-size: 1.3rem;
            font-weight: bold;
            color: #334155;
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
            display: inline-block;
            transition: background-color 0.3s;
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
            transition: background-color 0.3s;
        }
        
        .btn-finalizar:hover {
            background-color: #059669;
        }

        .notificacao-flutuante {
            position: fixed;
            top: 20px;
            right: 20px;
            padding: 15px 25px;
            border-radius: 12px;
            z-index: 9999;
            max-width: 400px;
            box-shadow: 0 10px 25px rgba(0,0,0,0.2);
            animation: slideInRight 0.3s ease, fadeOut 0.3s ease 4.7s;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .notificacao-sucesso {
            background: linear-gradient(135deg, #10b981, #059669);
            color: white;
        }

        @keyframes slideInRight {
            from { transform: translateX(500px); opacity: 0; }
            to { transform: translateX(0); opacity: 1; }
        }

        @keyframes fadeOut {
            from { opacity: 1; }
            to { opacity: 0; }
        }

        .user-avatar {
            width: 45px;
            height: 45px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: bold;
            font-size: 1.3rem;
            box-shadow: 0 2px 8px rgba(0,0,0,0.15);
            cursor: pointer;
            border: 3px solid white;
            transition: transform 0.3s;
        }

        .user-avatar:hover {
            transform: scale(1.1);
        }

        @media (max-width: 768px) {
            .carrinho-item {
                grid-template-columns: 80px 1fr;
                gap: 15px;
            }
            
            .item-subtotal, .btn-remover {
                grid-column: 2;
            }
        }
    </style>
</head>
<body>
    <header id="inicio">
        <div class="fixo">
            <div class="header-top">
                <div class="logo-container">
                    <a href="index.php" style="text-decoration: none;">
                        <span class="store-name">Mundo Gica</span>
                    </a>
                </div>
                <div class="search-container">
                    <input type="text" placeholder="Buscar esmaltes..." class="search-bar">
                    <img src="img/lupa.png" alt="Lupa" class="lupa">
                </div>
                <div class="cart-container">
                    <div style="display: flex; align-items: center; gap: 15px;">
                        <button class="cart-button" onclick="window.location.href='carrinho.php'">
                            <img src="img/sacoladecompras.png" alt="Ícone de sacola" style="width: 20px; height: 20px; margin-right: 8px;">
                            <span>Minha Sacola (<?php echo count($itens_carrinho); ?>)</span>
                        </button>
                        
                        <div class="user-avatar" title="<?php echo $_SESSION['cliente_nome']; ?>">
                            <?php echo strtoupper(substr(explode(' ', $_SESSION['cliente_nome'])[0], 0, 1)); ?>
                        </div>
                    </div>
                </div>
            </div>

            <hr class="separator">

            <nav>
                <ul class="menu">
                    <li><a href="index.php">Início</a></li>
                    <li><a href="index.php#linkprodutos">Produtos</a></li>
                    <li><a href="index.php#promocoes">Promoções</a></li>
                    <li><a href="carrinho.php">Carrinho</a></li>
                    <li><a href="logout.php">Sair</a></li>
                </ul>
            </nav>
        </div>
    </header>
    <div class="spacer"></div>

    <?php if(isset($_SESSION['sucesso'])): ?>
        <div class="notificacao-flutuante notificacao-sucesso">
            <strong>✓ <?php echo $_SESSION['sucesso']; ?></strong>
        </div>
        <script>
            setTimeout(() => {
                document.querySelector('.notificacao-flutuante')?.remove();
            }, 5000);
        </script>
        <?php unset($_SESSION['sucesso']); ?>
    <?php endif; ?>
    
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
                    // USAR IMAGEM DO ARRAY FIXO
                    $produto_id = intval($item['produto_id']);
                    $imagem = isset($imagens_produtos[$produto_id]) ? $imagens_produtos[$produto_id] : 'img/placeholder.png';
                    
                    $subtotal = $item['preco'] * $item['quantidade'];
                    $total_carrinho += $subtotal;
                ?>
                    <div class="carrinho-item">
                        <div class="item-imagem">
                            <img src="<?php echo $imagem; ?>" alt="<?php echo htmlspecialchars($item['nome']); ?>">
                        </div>
                        
                        <div class="item-info">
                            <h3><?php echo htmlspecialchars($item['nome']); ?></h3>
                            <div class="item-preco">R$ <?php echo number_format($item['preco'], 2, ',', '.'); ?> cada</div>
                            <div class="item-quantidade">Quantidade: <?php echo $item['quantidade']; ?></div>
                        </div>
                        
                        <div class="item-subtotal">
                            R$ <?php echo number_format($subtotal, 2, ',', '.'); ?>
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