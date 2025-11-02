<?php
include 'conexao.php';

// Verificar se tem ID do produto
if(!isset($_GET['id'])) {
    header('Location: index.php');
    exit;
}

// Buscar produto
$produto_id = $_GET['id'];
$sql = "SELECT * FROM produtos WHERE id = $produto_id";
$resultado = mysqli_query($conn, $sql);

// Se não encontrou, voltar para index
if(mysqli_num_rows($resultado) == 0) {
    header('Location: index.php');
    exit;
}

$produto = mysqli_fetch_assoc($resultado);
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $produto['nome']; ?> - Mundo GiCa</title>
    <link rel="stylesheet" href="style.css">
    <style>
        .detalhes-container {
            max-width: 1200px;
            margin: 120px auto 50px;
            padding: 20px;
        }
        
        .produto-detalhes {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 50px;
            background: white;
            border-radius: 12px;
            box-shadow: 0 10px 25px rgba(0,0,0,0.1);
            padding: 40px;
            align-items: start;
        }
        
        .produto-imagem {
            text-align: center;
        }
        
        .produto-imagem img {
            width: 100%;
            max-width: 500px;
            border-radius: 12px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
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
            display: inline-block;
            background: #f3e8ff;
            padding: 5px 15px;
            border-radius: 20px;
        }
        
        .produto-preco {
            font-size: 2.5rem;
            color: #7c3aed;
            font-weight: bold;
            margin-bottom: 20px;
        }
        
        .produto-descricao {
            color: #64748b;
            line-height: 1.8;
            margin-bottom: 30px;
            font-size: 1.1rem;
            padding: 20px;
            background: #f8fafc;
            border-radius: 8px;
            border-left: 4px solid #7c3aed;
        }
        
        .estoque-info {
            font-weight: 600;
            margin-bottom: 25px;
            font-size: 1.1rem;
            padding: 10px 15px;
            border-radius: 8px;
            display: inline-block;
        }
        
        .estoque-ok {
            background: #d1fae5;
            color: #059669;
        }
        
        .estoque-baixo {
            background: #fef3c7;
            color: #f59e0b;
        }
        
        .estoque-zero {
            background: #fee2e2;
            color: #dc2626;
        }
        
        .add-carrinho-form {
            display: flex;
            gap: 15px;
            align-items: center;
            margin-bottom: 30px;
            padding: 20px;
            background: #f8fafc;
            border-radius: 12px;
        }
        
        .quantidade-label {
            font-weight: 600;
            color: #334155;
            font-size: 1.1rem;
        }
        
        .quantidade-input {
            width: 100px;
            padding: 12px;
            border: 2px solid #e2e8f0;
            border-radius: 8px;
            text-align: center;
            font-size: 1.1rem;
            font-weight: 600;
        }
        
        .btn-adicionar {
            padding: 15px 40px;
            background-color: #7c3aed;
            color: white;
            border: none;
            border-radius: 8px;
            font-size: 1.1rem;
            cursor: pointer;
            font-weight: 600;
            transition: all 0.3s;
            flex: 1;
        }
        
        .btn-adicionar:hover {
            background-color: #6d28d9;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(124, 58, 237, 0.3);
        }
        
        .btn-adicionar:disabled {
            opacity: 0.5;
            cursor: not-allowed;
        }
        
        .voltar-link {
            display: inline-block;
            margin-top: 20px;
            color: #7c3aed;
            text-decoration: none;
            font-weight: 600;
            font-size: 1.1rem;
            padding: 10px 20px;
            border: 2px solid #7c3aed;
            border-radius: 8px;
            transition: all 0.3s;
        }
        
        .voltar-link:hover {
            background: #7c3aed;
            color: white;
        }
        
        .rating {
            color: #f59e0b;
            font-size: 1.2rem;
            margin-bottom: 20px;
        }
        
        @media (max-width: 768px) {
            .produto-detalhes {
                grid-template-columns: 1fr;
                gap: 30px;
                padding: 20px;
            }
            
            .produto-info h1 {
                font-size: 2rem;
            }
            
            .add-carrinho-form {
                flex-direction: column;
            }
            
            .quantidade-input {
                width: 100%;
            }
        }
    </style>
</head>
<body>
    <!-- HEADER -->
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
                    <button class="cart-button" onclick="window.location.href='<?php echo isset($_SESSION['cliente_id']) ? 'carrinho.php' : 'login.php'; ?>'">
                        <img src="img/sacoladecompras.png" alt="Ícone de sacola">
                        <span>Minha Sacola<?php if(isset($_SESSION['carrinho'])) echo ' (' . count($_SESSION['carrinho']) . ')'; ?></span>
                    </button>
                </div>
            </div>

            <hr class="separator">

            <nav>
                <ul class="menu">
                    <li><a href="index.php">Início</a></li>
                    <li><a href="index.php#linkprodutos">Produtos</a></li>
                    <li><a href="index.php#promocoes">Promoções</a></li>
                    
                    <?php if(isset($_SESSION['cliente_id'])): ?>
                        <li><a href="carrinho.php">Carrinho</a></li>
                        
                        <?php
                        $cliente_id = $_SESSION['cliente_id'];
                        $sql_admin = "SELECT admin FROM clientes WHERE id = $cliente_id";
                        $resultado_admin = mysqli_query($conn, $sql_admin);
                        if($resultado_admin && mysqli_num_rows($resultado_admin) > 0) {
                            $usuario = mysqli_fetch_assoc($resultado_admin);
                            if($usuario['admin'] == 1):
                        ?>
                            <li><a href="admin.php" style="background: #10b981; padding: 8px 15px; border-radius: 6px;">⚙️ Admin</a></li>
                        <?php 
                            endif;
                        }
                        ?>
                        
                        <li><a href="logout.php">Sair (<?php echo $_SESSION['cliente_nome']; ?>)</a></li>
                    <?php else: ?>
                        <li><a href="cadastro.php">Cadastro</a></li>
                        <li><a href="login.php">Login</a></li>
                    <?php endif; ?>
                </ul>
            </nav>
        </div>
    </header>
    <div class="spacer"></div>

    <!-- MENSAGENS -->
    <?php if(isset($_SESSION['sucesso'])): ?>
        <div style="background: #d1fae5; color: #065f46; padding: 15px; text-align: center; border-radius: 8px; margin: 20px auto; max-width: 800px; border: 1px solid #a7f3d0;">
            ✅ <?php echo $_SESSION['sucesso']; unset($_SESSION['sucesso']); ?>
        </div>
    <?php endif; ?>

    <!-- DETALHES DO PRODUTO -->
    <div class="detalhes-container">
        <div class="produto-detalhes">
            <div class="produto-imagem">
                <img src="<?php echo $produto['imagem']; ?>" alt="<?php echo $produto['nome']; ?>">
            </div>
            
            <div class="produto-info">
                <h1><?php echo $produto['nome']; ?></h1>
                
                <div class="produto-marca">
                    📦 Marca: <?php echo $produto['marca']; ?>
                </div>
                
                <div class="rating">⭐⭐⭐⭐⭐</div>
                
                <div class="produto-preco">
                    💰 R$ <?php echo number_format($produto['preco'], 2, ',', '.'); ?>
                </div>
                
                <div class="estoque-info <?php 
                    if($produto['estoque'] > 10) {
                        echo 'estoque-ok';
                    } elseif($produto['estoque'] > 0) {
                        echo 'estoque-baixo';
                    } else {
                        echo 'estoque-zero';
                    }
                ?>">
                    <?php 
                    if($produto['estoque'] > 10) {
                        echo "✓ Em estoque - Pronta entrega!";
                    } elseif($produto['estoque'] > 0) {
                        echo "⚠ Últimas " . $produto['estoque'] . " unidades!";
                    } else {
                        echo "✗ Produto esgotado";
                    }
                    ?>
                </div>
                
                <div class="produto-descricao">
                    <strong>📝 Descrição:</strong><br><br>
                    <?php echo nl2br($produto['descricao']); ?>
                </div>
                
                <?php if($produto['estoque'] > 0): ?>
                    <form method="POST" action="adicionar_carrinho.php" class="add-carrinho-form">
                        <input type="hidden" name="produto_id" value="<?php echo $produto['id']; ?>">
                        
                        <span class="quantidade-label">Quantidade:</span>
                        
                        <input type="number" 
                               id="quantidade" 
                               name="quantidade" 
                               value="1" 
                               min="1" 
                               max="<?php echo $produto['estoque']; ?>" 
                               class="quantidade-input">
                        
                        <button type="submit" class="btn-adicionar">
                            🛒 Adicionar ao Carrinho
                        </button>
                    </form>
                <?php else: ?>
                    <button class="btn-adicionar" disabled>
                        ❌ Produto Esgotado
                    </button>
                <?php endif; ?>
                
                <a href="index.php" class="voltar-link">← Voltar para a loja</a>
            </div>
        </div>
    </div>

    <!-- FOOTER -->
    <footer>
        <div class="footer-content">
            <div class="footer-info">
                <div class="footer-about">
                    <h3>Sobre Nós</h3>
                    <p>A Loja de Esmaltes oferece uma ampla variedade de cores e acabamentos para todos os gostos.</p>
                </div>
            </div>
            <div class="footer-meio">
                <h3>Nossos Produtos</h3>
                <p>Explore nossa linha de esmaltes com diferentes acabamentos!</p>
            </div>
            <div class="footer-contato">
                <h3>Contato</h3>
                <p>Email: contato@mundogica.com.br</p>
                <p>Telefone: (16) 4002-8922</p>
            </div>
        </div>
        <div class="footer-fim">
            <p>&copy; 2025 E-commerce de Esmaltes. Todos os direitos reservados.</p>
            <div class="social-icons">
                <a href="#"><img src="img/logofacebook.png" alt="Facebook"></a>
                <a href="#"><img src="img/logoinstagram.png" alt="Instagram"></a>
                <a href="#"><img src="img/logox.png" alt="X"></a>
            </div>
        </div>
    </footer>
</body>
</html>