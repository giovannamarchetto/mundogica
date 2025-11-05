<?php
include 'conexao.php';

// PEGAR FILTRO DE MARCA DA URL
$marca_filtro = isset($_GET['marca']) ? $_GET['marca'] : 'todas';

// BUSCAR PRODUTOS DO BANCO COM FILTRO
if($marca_filtro == 'todas') {
    $sql = "SELECT * FROM produtos ORDER BY id ASC";
} else {
    $marca_escaped = mysqli_real_escape_string($conn, $marca_filtro);
    $sql = "SELECT * FROM produtos WHERE marca = '$marca_escaped' ORDER BY id ASC";
}

$resultado = mysqli_query($conn, $sql);
$produtos = array();
while($produto = mysqli_fetch_assoc($resultado)) {
    $produtos[] = $produto;
}

// ARRAY COM IMAGENS FIXAS
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
?>

<!DOCTYPE html> 
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>E-commerce de Esmaltes - Mundo GiCa</title>
    <link rel="stylesheet" href="style.css">
    <style>
        /* ESTILOS PARA O FILTRO ATIVO */
        .brand-options a {
            display: inline-block;
            padding: 10px 20px;
            border: 2px solid #7c3aed;
            border-radius: 25px;
            background-color: #ffffff;
            color: #7c3aed;
            text-decoration: none;
            cursor: pointer;
            transition: all 0.3s ease;
            font-weight: 500;
            margin: 5px;
        }
        
        .brand-options a:hover {
            background-color: #7c3aed;
            color: #ffffff;
            transform: translateY(-2px);
        }
        
        .brand-options a.active {
            background-color: #7c3aed;
            color: #ffffff;
        }
        
        .mensagem-filtro {
            text-align: center;
            padding: 15px;
            margin: 20px auto;
            border-radius: 8px;
            font-weight: 600;
            max-width: 600px;
        }
        
        .mensagem-sucesso {
            background-color: #f0f9ff;
            color: #0369a1;
        }
        
        .mensagem-vazio {
            background-color: #fef3c7;
            color: #92400e;
        }
    </style>
</head>
<body>
  <!-- Grupo - Carla Victória Barros da Silva 09323067
              - Giovanna Borba Marchetto 09323042 -->
              
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
            <li><a href="#inicio">Início</a></li>
            <li><a href="#linkprodutos">Produtos</a></li>
            <li><a href="#promocoes">Promoções</a></li>
            
            <?php if(isset($_SESSION['cliente_id'])): ?>
                <li><a href="carrinho.php">Carrinho</a></li>
                
                <?php
                // Verificar se é admin
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

    <?php if(isset($_SESSION['erro'])): ?>
        <div style="background: #fee2e2; color: #dc2626; padding: 15px; text-align: center; border-radius: 8px; margin: 20px auto; max-width: 800px; border: 1px solid #fecaca;">
            ⚠️ <?php echo $_SESSION['erro']; unset($_SESSION['erro']); ?>
        </div>
    <?php endif; ?>

    <?php if(isset($_SESSION['pedido_sucesso'])): ?>
        <div style="background: #d1fae5; color: #065f46; padding: 15px; text-align: center; border-radius: 8px; margin: 20px auto; max-width: 800px; border: 1px solid #a7f3d0;">
            🎉 <?php echo $_SESSION['pedido_sucesso']; unset($_SESSION['pedido_sucesso']); ?>
        </div>
    <?php endif; ?>

    <main>
      <div class="banner">
        <div class="banner-content">
            <h1>Descubra Cores Incríveis!</h1>
            <p>Dê vida às suas unhas com as últimas tendências em esmaltes. Aproveite descontos de até <strong>20%</strong> nas coleções da estação!</p>
            <a href="#linkprodutos" class="banner-button">Confira Agora</a>
        </div>
    </div>
    
<!-- FILTRO DE MARCAS COM PHP -->
<div class="brand-filter">
  <h3>Filtrar por Marca</h3>
  <div class="brand-options">
      <a href="index.php?marca=Risqué#linkprodutos" class="<?php echo $marca_filtro == 'Risqué' ? 'active' : ''; ?>">Risqué</a>
      <a href="index.php?marca=Dailus#linkprodutos" class="<?php echo $marca_filtro == 'Dailus' ? 'active' : ''; ?>">Dailus</a>
      <a href="index.php?marca=Colorama#linkprodutos" class="<?php echo $marca_filtro == 'Colorama' ? 'active' : ''; ?>">Colorama</a>
      <a href="index.php?marca=Impala#linkprodutos" class="<?php echo $marca_filtro == 'Impala' ? 'active' : ''; ?>">Impala</a>
      <a href="index.php?marca=Avon#linkprodutos" class="<?php echo $marca_filtro == 'Avon' ? 'active' : ''; ?>">Avon</a>
      <a href="index.php?marca=Coleções#linkprodutos" class="<?php echo $marca_filtro == 'Coleções' ? 'active' : ''; ?>">Coleções</a>
      <a href="index.php?marca=todas#linkprodutos" class="<?php echo $marca_filtro == 'todas' ? 'active' : ''; ?>">Todas as Marcas</a>
  </div>
</div>

<!-- MENSAGEM DE RESULTADO DO FILTRO -->
<?php if($marca_filtro != 'todas'): ?>
    <?php if(count($produtos) > 0): ?>
        <div class="mensagem-filtro mensagem-sucesso">
            ✨ <?php echo count($produtos); ?> produto(s) encontrado(s) para "<?php echo htmlspecialchars($marca_filtro); ?>"
        </div>
    <?php else: ?>
        <div class="mensagem-filtro mensagem-vazio">
            😢 Nenhum produto encontrado para "<?php echo htmlspecialchars($marca_filtro); ?>"
        </div>
    <?php endif; ?>
<?php endif; ?>
    
      <section class="produtos" id="linkprodutos">
        <h2>Nossos Produtos</h2>
        
        <?php if(count($produtos) == 0): ?>
            <div style="text-align: center; padding: 40px; width: 100%;">
                <h3 style="color: #64748b;">Nenhum produto disponível nesta marca 😢</h3>
                <p><a href="index.php?marca=todas#linkprodutos" style="color: #7c3aed; font-weight: 600;">← Ver todos os produtos</a></p>
            </div>
        <?php else: ?>
            <?php foreach($produtos as $produto): 
                // Usar imagem fixa do array
                $imagem = isset($imagens_produtos[$produto['id']]) ? $imagens_produtos[$produto['id']] : $produto['imagem'];
            ?>
            <div class="produto">
              <a href="detalhes_produto.php?id=<?php echo $produto['id']; ?>">
                <img src="<?php echo $imagem; ?>" alt="<?php echo htmlspecialchars($produto['nome']); ?>">
                <h3><?php echo htmlspecialchars($produto['nome']); ?></h3>
              </a>
                <p>R$ <?php echo number_format($produto['preco'], 2, ',', '.'); ?></p>
              <div class="rating">⭐⭐⭐⭐⭐</div>
              
              <form method="POST" action="adicionar_carrinho.php">
                <input type="hidden" name="produto_id" value="<?php echo $produto['id']; ?>">
                <input type="hidden" name="quantidade" value="1">
                <button type="submit" class="banner-button">Adicionar ao Carrinho</button>
              </form>
            </div>
            <?php endforeach; ?>
        <?php endif; ?>
      </section>
  </main>

  <div class="promotions-section" id="promocoes">
    <h2 class="promotions-title">Promoções de Coleção</h2>
    <div class="promotions-container">
        <div class="promotion-item">
            <a href="detalhes_produto.php?id=13">
                <img src="img/esmaltesana.png" alt="Coleção Ana Hickman Estrelas">
                <h3 class="promotion-item-title">Coleção Ana Hickman Estrelas</h3>
            </a>
                <p class="promotion-item-price">R$ 40,00</p>
                <p class="promotion-item-discount">Desconto de 20%</p>
            
            <form method="POST" action="adicionar_carrinho.php">
                <input type="hidden" name="produto_id" value="13">
                <input type="hidden" name="quantidade" value="1">
                <button type="submit" class="promotion-item-button">Comprar</button>
            </form>
        </div>
        <div class="promotion-item">
            <a href="detalhes_produto.php?id=14">
                <img src="img/esmalteshits.png" alt="Coleção Hits Diamante">
                <h3 class="promotion-item-title">Coleção Hits Diamante</h3>
            </a>
                <p class="promotion-item-price">R$ 48,00</p>
                <p class="promotion-item-discount">Desconto de 15%</p>
            
            <form method="POST" action="adicionar_carrinho.php">
                <input type="hidden" name="produto_id" value="14">
                <input type="hidden" name="quantidade" value="1">
                <button type="submit" class="promotion-item-button">Comprar</button>
            </form>
        </div>
        <div class="promotion-item">
            <a href="detalhes_produto.php?id=15">
                <img src="img/esmaltesanita.png" alt="Coleção Anita Capadócia">
                <h3 class="promotion-item-title">Coleção Anita Capadócia</h3>
            </a>
            <p class="promotion-item-price">R$ 37,00</p>
            <p class="promotion-item-discount">Desconto de 10%</p>
            
            <form method="POST" action="adicionar_carrinho.php">
                <input type="hidden" name="produto_id" value="15">
                <input type="hidden" name="quantidade" value="1">
                <button type="submit" class="promotion-item-button">Comprar</button>
            </form>
        </div>
    </div>
  </div>

<section class="cadastrese" id="cadastro">
  <h2>Faça seu cadastro para ficar por dentro das promoções!</h2>
  <form method="GET" action="cadastro.php">
      <input type="email" name="email" placeholder="Digite seu email" required>
      <button type="submit">Cadastrar</button>
  </form>
</section>

  <footer>
    <div class="footer-content">
        <div class="footer-info">
            <div class="footer-about">
                <h3>Sobre Nós</h3>
                <p>A Loja de Esmaltes oferece uma ampla variedade de cores e acabamentos para todos os gostos. Nossa missão é proporcionar beleza e qualidade em cada vidrinho de esmalte.</p>
            </div>
        </div>
        <div class="footer-meio">
          <h3>Nossos Produtos</h3>
          <p>Explore nossa linha de esmaltes com diferentes acabamentos: cremoso, glitter, fosco, perolado e muito mais!</p>
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