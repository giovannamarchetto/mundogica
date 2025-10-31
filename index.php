<?php
// Incluir conexão com banco de dados
include 'conexao.php';

// Buscar todos os produtos do banco de dados
$sql = "SELECT * FROM produtos ORDER BY id DESC";
$resultado = mysqli_query($conn, $sql);

// Guardar produtos em um array
$produtos = array();
while($produto = mysqli_fetch_assoc($resultado)) {
    $produtos[] = $produto;
}
?>

<!DOCTYPE html> 
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>E-commerce de Esmaltes - Mundo GiCa</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <!-- CABEÇALHO -->
    <header id="inicio">
        <div class="fixo">
            <div class="header-top">
                <div class="logo-container">
                    <a href="index.php" style="text-decoration: none;">
                        <span class="store-name">Mundo GiCa</span>
                    </a>
                </div>
                
                <div class="search-container">
                    <form method="GET" action="index.php">
                        <input type="text" name="busca" placeholder="Buscar esmaltes..." class="search-bar">
                    </form>
                </div>
                
                <div class="cart-container">
                    <?php if(isset($_SESSION['cliente_id'])): ?>
                        <a href="carrinho.php" class="cart-button" style="text-decoration: none;">
                            <span>🛒 Carrinho</span>
                        </a>
                    <?php else: ?>
                        <a href="login.php" class="cart-button" style="text-decoration: none;">
                            <span>🛒 Carrinho</span>
                        </a>
                    <?php endif; ?>
                </div>
            </div>

            <hr class="separator">

            <nav>
                <ul class="menu">
                    <li><a href="index.php">Início</a></li>
                    <li><a href="#linkprodutos">Produtos</a></li>
                    <li><a href="#promocoes">Promoções</a></li>
                    
                    <?php if(isset($_SESSION['cliente_id'])): ?>
                        <!-- Usuário está logado -->
                        <li><a href="carrinho.php">Carrinho</a></li>
                        
                        <?php
                        // Verificar se é admin
                        $cliente_id = $_SESSION['cliente_id'];
                        $sql_admin = "SELECT admin FROM clientes WHERE id = $cliente_id";
                        $resultado_admin = mysqli_query($conn, $sql_admin);
                        $usuario = mysqli_fetch_assoc($resultado_admin);
                        
                        if($usuario && $usuario['admin'] == 1):
                        ?>
                            <li><a href="admin.php">⚙️ Admin</a></li>
                        <?php endif; ?>
                        
                        <li><a href="logout.php">Sair (<?php echo $_SESSION['cliente_nome']; ?>)</a></li>
                    <?php else: ?>
                        <!-- Usuário não está logado -->
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
        <div style="background: #d1fae5; color: #065f46; padding: 15px; text-align: center; border-radius: 8px; margin: 20px; border: 1px solid #a7f3d0;">
            <?php echo $_SESSION['sucesso']; unset($_SESSION['sucesso']); ?>
        </div>
    <?php endif; ?>

    <?php if(isset($_SESSION['erro'])): ?>
        <div style="background: #fee2e2; color: #dc2626; padding: 15px; text-align: center; border-radius: 8px; margin: 20px; border: 1px solid #fecaca;">
            <?php echo $_SESSION['erro']; unset($_SESSION['erro']); ?>
        </div>
    <?php endif; ?>

    <?php if(isset($_SESSION['pedido_sucesso'])): ?>
        <div style="background: #d1fae5; color: #065f46; padding: 15px; text-align: center; border-radius: 8px; margin: 20px; border: 1px solid #a7f3d0;">
            <?php echo $_SESSION['pedido_sucesso']; unset($_SESSION['pedido_sucesso']); ?>
        </div>
    <?php endif; ?>
    
    <main>
        <!-- BANNER -->
        <div class="banner">
            <div class="banner-content">
                <h1>Descubra Cores Incríveis!</h1>
                <p>Dê vida às suas unhas com as últimas tendências em esmaltes. Aproveite descontos de até <strong>20%</strong> nas coleções da estação!</p>
                <a href="#linkprodutos" class="banner-button">Confira Agora</a>
            </div>
        </div>
        
        <!-- PRODUTOS -->
        <section class="produtos" id="linkprodutos">
            <h2>Nossos Produtos</h2>
            
            <div class="produtos-grid">
                <?php 
                // Mostrar cada produto
                foreach($produtos as $produto): 
                ?>
                    <div class="produto">
                        <a href="detalhes_produto.php?id=<?php echo $produto['id']; ?>">
                            <img src="<?php echo $produto['imagem']; ?>" alt="<?php echo $produto['nome']; ?>">
                            <h3><?php echo $produto['nome']; ?></h3>
                        </a>
                        <p>R$ <?php echo number_format($produto['preco'], 2, ',', '.'); ?></p>
                        <div class="rating">⭐⭐⭐⭐⭐</div>
                        
                        <?php if($produto['estoque'] > 0): ?>
                            <!-- Produto tem estoque -->
                            <form method="POST" action="adicionar_carrinho.php">
                                <input type="hidden" name="produto_id" value="<?php echo $produto['id']; ?>">
                                <input type="hidden" name="quantidade" value="1">
                                <button type="submit" class="banner-button">Adicionar ao Carrinho</button>
                            </form>
                        <?php else: ?>
                            <!-- Produto esgotado -->
                            <button class="banner-button" disabled style="opacity: 0.5; cursor: not-allowed;">Esgotado</button>
                        <?php endif; ?>
                    </div>
                <?php endforeach; ?>
            </div>
        </section>
        
        <!-- PROMOÇÕES -->
        <div class="promotions-section" id="promocoes">
            <h2 class="promotions-title">Promoções de Coleção</h2>
            <div class="promotions-container">
                <?php
                // Buscar produtos de coleção
                $sql_colecoes = "SELECT * FROM produtos WHERE marca = 'Coleções' ORDER BY id DESC LIMIT 3";
                $resultado_colecoes = mysqli_query($conn, $sql_colecoes);
                
                while($colecao = mysqli_fetch_assoc($resultado_colecoes)):
                ?>
                    <div class="promotion-item">
                        <a href="detalhes_produto.php?id=<?php echo $colecao['id']; ?>">
                            <img src="<?php echo $colecao['imagem']; ?>" alt="<?php echo $colecao['nome']; ?>">
                            <h3 class="promotion-item-title"><?php echo $colecao['nome']; ?></h3>
                        </a>
                        <p class="promotion-item-price">R$ <?php echo number_format($colecao['preco'], 2, ',', '.'); ?></p>
                        <p class="promotion-item-discount">Desconto de 20%</p>
                        
                        <?php if($colecao['estoque'] > 0): ?>
                            <form method="POST" action="adicionar_carrinho.php">
                                <input type="hidden" name="produto_id" value="<?php echo $colecao['id']; ?>">
                                <input type="hidden" name="quantidade" value="1">
                                <button type="submit" class="promotion-item-button">Comprar</button>
                            </form>
                        <?php else: ?>
                            <button class="promotion-item-button" disabled style="opacity: 0.5;">Esgotado</button>
                        <?php endif; ?>
                    </div>
                <?php endwhile; ?>
            </div>
        </div>

        <!-- NEWSLETTER -->
        <section class="cadastrese" id="cadastro">
            <h2>Faça seu cadastro para ficar por dentro das promoções!</h2>
            <form method="GET" action="cadastro.php">
                <input type="email" name="email" placeholder="Digite seu email" required>
                <button type="submit">Cadastrar</button>
            </form>
        </section>
    </main>

    <!-- FOOTER -->
    <footer>
        <div class="footer-content">
            <div class="footer-about">
                <h3>Sobre Nós</h3>
                <p>A Mundo GiCa oferece uma ampla variedade de cores e acabamentos para todos os gostos.</p>
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
            <p>&copy; 2025 Mundo GiCa. Todos os direitos reservados.</p>
            <p style="margin-top: 10px; font-size: 0.9rem;">
                Grupo - Carla Victória Barros da Silva 09323067 | Giovanna Borba Marchetto 09323042
            </p>
        </div>
    </footer>
</body>
</html>