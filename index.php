<?php
include 'conexao.php';

// Buscar produtos do banco
$sql = "SELECT * FROM produtos ORDER BY id ASC";
$resultado = mysqli_query($conn, $sql);
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
    <title>E-commerce de Esmaltes - Atividade 2° bimestre Juliano</title>
    <link rel="stylesheet" href="style.css">
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
        <div class="banner-content" src = "img/banner2.jpeg">
            <h1>Descubra Cores Incríveis!</h1>
            <p>Dê vida às suas unhas com as últimas tendências em esmaltes. Aproveite descontos de até <strong>20%</strong> nas coleções da estação!</p>
            <a href="#linkprodutos" class="banner-button">Confira Agora</a>
        </div>
    </div>
    
<div class="brand-filter">
  <h3>Filtrar por Marca</h3>
  <div class="brand-options">
      <input type="radio" id="marca-risque" name="brand" />
      <label for="marca-risque">Risqué</label>
      
      <input type="radio" id="marca-Dailus" name="brand" />
      <label for="marca-Dailus">Dailus</label>
      
      <input type="radio" id="marca-Colorama" name="brand" />
      <label for="marca-Colorama">Colorama</label>

      <input type="radio" id="marca-Impala" name="brand" />
      <label for="marca-Impala">Impala</label>

      <input type="radio" id="marca-Avon" name="brand" />
      <label for="marca-Avon">Avon</label>

      <input type="radio" id="marca-Coleções" name="brand" />
      <label for="marca-Coleções">Coleções</label>
      
      <input type="radio" id="marca-todas" name="brand" checked />
      <label for="marca-todas">Todas as Marcas</label>
  </div>
</div>
    
      <section class="produtos" id="linkprodutos">
        <h2>Nossos Produtos</h2>
        <section class="produtos">
            <!-- PRODUTO 1 -->
            <div class="produto">
              <!-- MUDANÇA AQUI: Agora vai para detalhes_produto.php -->
              <a href="detalhes_produto.php?id=1">
                <img src="img/esmalteamar.png" alt="Esmalte A.mar Risqué">
                <h3>A.mar Risqué</h3>
              </a>
                <p>R$ 3,50</p>
              <div class="rating">⭐⭐⭐⭐⭐ (12)</div>
              
              <form method="POST" action="adicionar_carrinho.php">
                <input type="hidden" name="produto_id" value="1">
                <input type="hidden" name="quantidade" value="1">
                <button type="submit" class="banner-button">Adicionar ao Carrinho</button>
              </form>
            </div>
            
            <!-- PRODUTO 2 -->
            <div class="produto">
              <a href="detalhes_produto.php?id=2">
                <img src="img/esmalteescarlate.png" alt="Esmalte Escarlate Risqué">
                <h3>Escarlate Risqué</h3>
              </a>
                <p>R$ 3,00</p>
              <div class="rating">⭐⭐⭐⭐⭐ (15)</div>
              
              <form method="POST" action="adicionar_carrinho.php">
                <input type="hidden" name="produto_id" value="2">
                <input type="hidden" name="quantidade" value="1">
                <button type="submit" class="banner-button">Adicionar ao Carrinho</button>
              </form>
            </div>
            
            <!-- PRODUTO 3 -->
          <div class="produto">
              <a href="detalhes_produto.php?id=3">
                <img src="img/esmaltesoutopping.png" alt="Esmalte Sou Topping Risqué">
                <h3>Sou Topping Risqué</h3>
              </a>
                <p>R$ 4,00</p>
              <div class="rating">⭐⭐⭐⭐⭐ (20)</div>
              
              <form method="POST" action="adicionar_carrinho.php">
                <input type="hidden" name="produto_id" value="3">
                <input type="hidden" name="quantidade" value="1">
                <button type="submit" class="banner-button">Adicionar ao Carrinho</button>
              </form>
            </div>
            
            <!-- PRODUTO 4 -->
          <div class="produto">
              <a href="detalhes_produto.php?id=4">
                <img src="img/esmalteanonovonorio.png" alt="Esmalte Ano Novo no Rio Risqué">
                <h3>Ano Novo no Rio Risqué</h3>
              </a>
                <p>R$ 7,00</p>
              <div class="rating">⭐⭐⭐⭐⭐ (30)</div>
              
              <form method="POST" action="adicionar_carrinho.php">
                <input type="hidden" name="produto_id" value="4">
                <input type="hidden" name="quantidade" value="1">
                <button type="submit" class="banner-button">Adicionar ao Carrinho</button>
              </form>
            </div>
            
            <!-- PRODUTO 5 -->
            <div class="produto">
              <a href="detalhes_produto.php?id=5">
                <img src="img/esmalteolhogrego.png" alt="Esmalte Olho Grego Dailus">
                <h3>Olho Grego Dailus</h3>
              </a>
                <p>R$ 5,50</p>
             <div class="rating">⭐⭐⭐⭐⭐ (15)</div>
             
             <form method="POST" action="adicionar_carrinho.php">
                <input type="hidden" name="produto_id" value="5">
                <input type="hidden" name="quantidade" value="1">
                <button type="submit" class="banner-button">Adicionar ao Carrinho</button>
              </form>
            </div>
            
            <!-- PRODUTO 6 -->
            <div class="produto">
              <a href="detalhes_produto.php?id=6">
                <img src="img/esmaltetachovendofini.png" alt="Esmalte Tá Chovendo Fini Colorama">
                <h3>Tá Chovendo Fini Colorama</h3>
              </a>
                <p>R$ 4,50</p>
              <div class="rating">⭐⭐⭐⭐⭐ (09)</div>
              
              <form method="POST" action="adicionar_carrinho.php">
                <input type="hidden" name="produto_id" value="6">
                <input type="hidden" name="quantidade" value="1">
                <button type="submit" class="banner-button">Adicionar ao Carrinho</button>
              </form>
            </div>
            
            <!-- PRODUTO 7 -->
            <div class="produto">
              <a href="detalhes_produto.php?id=7">
                <img src="img/esmalteimensidao.png" alt="Esmalte Imensidão Impala">
                <h3>Imensidão Impala</h3>
              </a>
                <p>R$ 3,00</p>
             <div class="rating">⭐⭐⭐⭐⭐ (12)</div>
             
             <form method="POST" action="adicionar_carrinho.php">
                <input type="hidden" name="produto_id" value="7">
                <input type="hidden" name="quantidade" value="1">
                <button type="submit" class="banner-button">Adicionar ao Carrinho</button>
              </form>
            </div>
            
            <!-- PRODUTO 8 -->
            <div class="produto">
              <a href="detalhes_produto.php?id=8">
                <img src="img/esmaltedizeres.png" alt="Esmalte Dizeres Impala">
                <h3>Dizeres Impala</h3>
              </a>
                <p>R$ 3,00</p>
             <div class="rating">⭐⭐⭐⭐⭐ (07)</div>
             
             <form method="POST" action="adicionar_carrinho.php">
                <input type="hidden" name="produto_id" value="8">
                <input type="hidden" name="quantidade" value="1">
                <button type="submit" class="banner-button">Adicionar ao Carrinho</button>
              </form>
            </div>
            
            <!-- PRODUTO 9 -->
            <div class="produto">
              <a href="detalhes_produto.php?id=9">
                <img src="img/esmaltecaricia.png" alt="Esmalte Carícia Impala">
                <h3>Carícia Impala</h3>
              </a>
                <p>R$ 3,00</p>
             <div class="rating">⭐⭐⭐⭐⭐ (14)</div>
             
             <form method="POST" action="adicionar_carrinho.php">
                <input type="hidden" name="produto_id" value="9">
                <input type="hidden" name="quantidade" value="1">
                <button type="submit" class="banner-button">Adicionar ao Carrinho</button>
              </form>
            </div>
            
            <!-- PRODUTO 10 -->
            <div class="produto">
              <a href="detalhes_produto.php?id=10">
                <img src="img/esmaltelaçadaperfeita.png" alt="Esmalte Laçada Perfeita Impala">
                <h3>Laçada Perfeita Impala</h3>
              </a>
                <p>R$ 3,50</p>
             <div class="rating">⭐⭐⭐⭐⭐ (11)</div>
             
             <form method="POST" action="adicionar_carrinho.php">
                <input type="hidden" name="produto_id" value="10">
                <input type="hidden" name="quantidade" value="1">
                <button type="submit" class="banner-button">Adicionar ao Carrinho</button>
              </form>
            </div>
            
            <!-- PRODUTO 11 -->
            <div class="produto">
              <a href="detalhes_produto.php?id=11">
                <img src="img/esmaltevermelhaco.png" alt="Esmalte Vermelhaço Avon">
                <h3>Vermelhaço Avon</h3>
              </a>
                <p>R$ 7,00</p>
             <div class="rating">⭐⭐⭐⭐⭐ (16)</div>
             
             <form method="POST" action="adicionar_carrinho.php">
                <input type="hidden" name="produto_id" value="11">
                <input type="hidden" name="quantidade" value="1">
                <button type="submit" class="banner-button">Adicionar ao Carrinho</button>
              </form>
            </div>
            
            <!-- PRODUTO 12 -->
            <div class="produto">
              <a href="detalhes_produto.php?id=12">
                <img src="img/esmalteazulliberdade.png " alt="Esmalte Azul Liberdade Avon">
                <h3>Azul Liberdade Avon</h3>
              </a>
                <p>R$ 7,00</p>
             <div class="rating">⭐⭐⭐⭐⭐ (12)</div>
             
             <form method="POST" action="adicionar_carrinho.php">
                <input type="hidden" name="produto_id" value="12">
                <input type="hidden" name="quantidade" value="1">
                <button type="submit" class="banner-button">Adicionar ao Carrinho</button>
              </form>
            </div>
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

<section class="cadastrese"id="cadastro">
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