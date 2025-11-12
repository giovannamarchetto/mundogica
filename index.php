<?php
include 'conexao.php';

$marca_filtro = isset($_GET['marca']) ? $_GET['marca'] : 'todas';

$busca = isset($_GET['busca']) ? trim($_GET['busca']) : '';

$where_conditions = [];

if($marca_filtro == 'todas') {
    $where_conditions[] = "marca != 'Coleções'";
} elseif($marca_filtro == 'Coleções') {
    $where_conditions[] = "marca = 'Coleções'";
} else {
    $marca_escaped = mysqli_real_escape_string($conn, $marca_filtro);
    $where_conditions[] = "marca = '$marca_escaped'";
}

if(!empty($busca)) {
    $busca_escaped = mysqli_real_escape_string($conn, $busca);
    $where_conditions[] = "(nome LIKE '%$busca_escaped%' OR descricao LIKE '%$busca_escaped%' OR marca LIKE '%$busca_escaped%')";
}

$sql = "SELECT * FROM produtos";
if(count($where_conditions) > 0) {
    $sql .= " WHERE " . implode(" AND ", $where_conditions);
}
$sql .= " ORDER BY id ASC";

$resultado = mysqli_query($conn, $sql);
$produtos = array();
while($produto = mysqli_fetch_assoc($resultado)) {
    $produtos[] = $produto;
}

// ARRAY COM IMAGENS FIXAS PQ NAO TAVAM APARECENDO
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

        .notificacao-erro {
            background: linear-gradient(135deg, #ef4444, #dc2626);
            color: white;
        }

        @keyframes slideInRight {
            from {
                transform: translateX(500px);
                opacity: 0;
            }
            to {
                transform: translateX(0);
                opacity: 1;
            }
        }

        @keyframes fadeOut {
            from {
                opacity: 1;
            }
            to {
                opacity: 0;
            }
        }

        .notificacao-flutuante::before {
            content: '';
            font-size: 1.5rem;
        }

        .notificacao-sucesso::before {
            content: '✓';
        }

        .notificacao-erro::before {
            content: '⚠';
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

        /* Estilo para limpar busca */
        .limpar-busca {
            text-align: center;
            margin: 15px 0;
        }

        .limpar-busca a {
            color: #7c3aed;
            text-decoration: none;
            font-weight: 600;
            padding: 8px 20px;
            border: 2px solid #7c3aed;
            border-radius: 20px;
            display: inline-block;
            transition: all 0.3s;
        }

        .limpar-busca a:hover {
            background: #7c3aed;
            color: white;
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
            <form method="GET" action="index.php#linkprodutos">
                <input type="text" name="busca" placeholder="Buscar esmaltes..." class="search-bar" value="<?php echo htmlspecialchars($busca); ?>">
                <button type="submit" style="display: none;"></button>
            </form>
            <img src="img/lupa.png" alt="Lupa" class="lupa" onclick="this.parentElement.querySelector('form').submit();">
        </div>
        
        <div class="cart-container">
            <?php if(isset($_SESSION['cliente_id'])): ?>
                <div style="display: flex; align-items: center; gap: 15px;">
                    <?php $total_itens = isset($_SESSION['carrinho']) ? count($_SESSION['carrinho']) : 0; ?>
                    <button class="cart-button" onclick="window.location.href='carrinho.php'">
                        <img src="img/sacoladecompras.png" alt="Ícone de sacola" style="width: 20px; height: 20px; margin-right: 8px;">
                        <span>Carrinho (<?php echo $total_itens; ?>)</span>
                    </button>
                    
                    <div class="user-avatar" title="<?php echo $_SESSION['cliente_nome']; ?>">
                        <?php echo strtoupper(substr(explode(' ', $_SESSION['cliente_nome'])[0], 0, 1)); ?>
                    </div>
                </div>
            <?php else: ?>
                <button class="cart-button" onclick="window.location.href='login.php'">
                    <img src="img/sacoladecompras.png" alt="Ícone de sacola" style="width: 20px; height: 20px; margin-right: 8px;">
                        <span>Carrinho<?php if(isset($_SESSION['carrinho'])) echo ' (' . count($_SESSION['carrinho']) . ')'; ?></span>
                </button>
            <?php endif; ?>
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
                $cliente_id = $_SESSION['cliente_id'];
                $sql_admin = "SELECT admin FROM clientes WHERE id = $cliente_id";
                $resultado_admin = mysqli_query($conn, $sql_admin);
                if($resultado_admin && mysqli_num_rows($resultado_admin) > 0) {
                    $usuario = mysqli_fetch_assoc($resultado_admin);
                    if($usuario['admin'] == 1):
                ?>
                    <li><a href="admin.php" style="background: #10b981; padding: 15px 15px; border-radius: 0px;">Admin</a></li>
                <?php 
                    endif;
                }
                ?>
                
                <li><a href="logout.php">Sair</a></li>
            <?php else: ?>
                <li><a href="cadastro.php">Cadastro</a></li>
                <li><a href="login.php">Login</a></li>
            <?php endif; ?>
        </ul>
    </nav>
  </div>
</header>

<div class="spacer"></div>

    <?php if(isset($_SESSION['sucesso'])): ?>
        <div class="notificacao-flutuante notificacao-sucesso">
            <strong><?php echo $_SESSION['sucesso']; ?></strong>
        </div>
        <script>
            setTimeout(() => {
                document.querySelector('.notificacao-flutuante')?.remove();
            }, 5000);
        </script>
        <?php unset($_SESSION['sucesso']); ?>
    <?php endif; ?>

    <?php if(isset($_SESSION['erro'])): ?>
        <div class="notificacao-flutuante notificacao-erro">
            <strong><?php echo $_SESSION['erro']; ?></strong>
        </div>
        <script>
            setTimeout(() => {
                document.querySelector('.notificacao-flutuante')?.remove();
            }, 5000);
        </script>
        <?php unset($_SESSION['erro']); ?>
    <?php endif; ?>

    <?php if(isset($_SESSION['pedido_sucesso'])): ?>
        <div class="notificacao-flutuante notificacao-sucesso">
            <strong><?php echo $_SESSION['pedido_sucesso']; ?></strong>
        </div>
        <script>
            setTimeout(() => {
                document.querySelector('.notificacao-flutuante')?.remove();
            }, 5000);
        </script>
        <?php unset($_SESSION['pedido_sucesso']); ?>
    <?php endif; ?>

<main>
    <div class="banner">
        <img src="img/banner2.jpeg" alt="Banner de esmaltes" class="banner-img">
        <div class="banner-overlay"></div>
        <div class="banner-content">
            <h1>Descubra Cores Incríveis!</h1>
            <p>Dê vida às suas unhas com as últimas tendências em esmaltes. Aproveite descontos de até <strong>20%</strong> nas coleções da estação!</p>
            <a href="#linkprodutos" class="banner-button">Confira Agora</a>
        </div>
    </div>
</main>
    
<div class="brand-filter">
  <h3>Filtrar por Marca</h3>
  <div class="brand-options">
      <a href="index.php?marca=Risqué<?php echo !empty($busca) ? '&busca='.urlencode($busca) : ''; ?>#linkprodutos" class="<?php echo $marca_filtro == 'Risqué' ? 'active' : ''; ?>">Risqué</a>
      <a href="index.php?marca=Dailus<?php echo !empty($busca) ? '&busca='.urlencode($busca) : ''; ?>#linkprodutos" class="<?php echo $marca_filtro == 'Dailus' ? 'active' : ''; ?>">Dailus</a>
      <a href="index.php?marca=Colorama<?php echo !empty($busca) ? '&busca='.urlencode($busca) : ''; ?>#linkprodutos" class="<?php echo $marca_filtro == 'Colorama' ? 'active' : ''; ?>">Colorama</a>
      <a href="index.php?marca=Impala<?php echo !empty($busca) ? '&busca='.urlencode($busca) : ''; ?>#linkprodutos" class="<?php echo $marca_filtro == 'Impala' ? 'active' : ''; ?>">Impala</a>
      <a href="index.php?marca=Avon<?php echo !empty($busca) ? '&busca='.urlencode($busca) : ''; ?>#linkprodutos" class="<?php echo $marca_filtro == 'Avon' ? 'active' : ''; ?>">Avon</a>
      <a href="index.php?marca=todas<?php echo !empty($busca) ? '&busca='.urlencode($busca) : ''; ?>#linkprodutos" class="<?php echo $marca_filtro == 'todas' ? 'active' : ''; ?>">Todas as Marcas</a>
  </div>
</div>

<?php if(!empty($busca)): ?>
    <div class="mensagem-filtro mensagem-sucesso">
        🔍 Buscando por: "<strong><?php echo htmlspecialchars($busca); ?></strong>" 
        <?php if($marca_filtro != 'todas'): ?>
            em "<?php echo htmlspecialchars($marca_filtro); ?>"
        <?php endif; ?>
        - <?php echo count($produtos); ?> resultado(s) encontrado(s)
    </div>
    <div class="limpar-busca">
        <a href="index.php?marca=<?php echo $marca_filtro; ?>#linkprodutos">✕ Limpar Busca</a>
    </div>
<?php elseif($marca_filtro != 'todas'): ?>
    <?php if(count($produtos) > 0): ?>
        <div class="mensagem-filtro mensagem-sucesso">
            ✓ <?php echo count($produtos); ?> produto(s) encontrado(s) para "<?php echo htmlspecialchars($marca_filtro); ?>"
        </div>
    <?php else: ?>
        <div class="mensagem-filtro mensagem-vazio">
            Nenhum produto encontrado para "<?php echo htmlspecialchars($marca_filtro); ?>"
        </div>
    <?php endif; ?>
<?php endif; ?>
    
      <section class="produtos" id="linkprodutos">
        <h2>Nossos Produtos</h2>
        
        <?php if(count($produtos) == 0): ?>
            <div style="text-align: center; padding: 40px; width: 100%;">
                <h3 style="color: #64748b;">
                    <?php if(!empty($busca)): ?>
                        Nenhum produto encontrado para "<?php echo htmlspecialchars($busca); ?>"
                    <?php else: ?>
                        Nenhum produto disponível nesta marca
                    <?php endif; ?>
                </h3>
                <p>
                    <a href="index.php?marca=todas#linkprodutos" style="color: #7c3aed; font-weight: 600;">← Ver todos os produtos</a>
                </p>
            </div>
        <?php else: ?>
            <?php foreach($produtos as $produto): 
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