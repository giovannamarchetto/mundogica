<?php
include 'conexao.php';

if(!isset($_SESSION['cliente_id'])) {
    $_SESSION['erro'] = "Você precisa fazer login para acessar o carrinho!";
    header('Location: login.php');
    exit;
}

// PROCESSAR ATUALIZAÇÃO DE QUANTIDADE VIA AJAX
if(isset($_POST['ajax_update']) && $_POST['ajax_update'] == '1') {
    header('Content-Type: application/json');
    
    $produto_id = intval($_POST['produto_id']);
    $nova_quantidade = intval($_POST['quantidade']);
    
    if($nova_quantidade <= 0) {
        echo json_encode(['erro' => 'Quantidade inválida']);
        exit;
    }
    
    // Verificar estoque disponível
    $sql = "SELECT estoque, nome FROM produtos WHERE id = $produto_id";
    $resultado = mysqli_query($conn, $sql);
    
    if(mysqli_num_rows($resultado) > 0) {
        $produto = mysqli_fetch_assoc($resultado);
        
        if($nova_quantidade > $produto['estoque']) {
            echo json_encode([
                'erro' => 'Estoque insuficiente! Disponível: ' . $produto['estoque'] . ' unidades',
                'estoque_disponivel' => $produto['estoque']
            ]);
            exit;
        }
        
        // Atualizar quantidade no carrinho
        if(isset($_SESSION['carrinho'])) {
            foreach($_SESSION['carrinho'] as &$item) {
                if($item['produto_id'] == $produto_id) {
                    $item['quantidade'] = $nova_quantidade;
                    
                    // Calcular novo subtotal
                    $subtotal = $item['preco'] * $nova_quantidade;
                    
                    // Calcular novo total do carrinho
                    $total_carrinho = 0;
                    foreach($_SESSION['carrinho'] as $i) {
                        $total_carrinho += $i['preco'] * $i['quantidade'];
                    }
                    
                    echo json_encode([
                        'sucesso' => true,
                        'subtotal' => number_format($subtotal, 2, ',', '.'),
                        'total' => number_format($total_carrinho, 2, ',', '.'),
                        'quantidade' => $nova_quantidade,
                        'mensagem' => 'Quantidade atualizada!'
                    ]);
                    exit;
                }
            }
        }
    }
    
    echo json_encode(['erro' => 'Produto não encontrado']);
    exit;
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

if(isset($_GET['remover'])) {
    $produto_id = intval($_GET['remover']);
    
    if(isset($_SESSION['carrinho'])) {
        foreach($_SESSION['carrinho'] as $key => $item) {
            if($item['produto_id'] == $produto_id) {
                $nome_removido = $item['nome'];
                unset($_SESSION['carrinho'][$key]);
                $_SESSION['carrinho'] = array_values($_SESSION['carrinho']);
                $_SESSION['sucesso'] = $nome_removido . " removido do carrinho!";
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

// Buscar estoque atualizado para cada produto
$estoques = [];
if(!empty($itens_carrinho)) {
    $ids = array_column($itens_carrinho, 'produto_id');
    $ids_string = implode(',', $ids);
    $sql_estoque = "SELECT id, estoque FROM produtos WHERE id IN ($ids_string)";
    $resultado_estoque = mysqli_query($conn, $sql_estoque);
    while($row = mysqli_fetch_assoc($resultado_estoque)) {
        $estoques[$row['id']] = $row['estoque'];
    }
}

// Verificar se é admin
$is_admin = false;
if(isset($_SESSION['cliente_id'])) {
    $cliente_id = $_SESSION['cliente_id'];
    $sql_admin = "SELECT admin FROM clientes WHERE id = $cliente_id";
    $resultado_admin = mysqli_query($conn, $sql_admin);
    if($resultado_admin && mysqli_num_rows($resultado_admin) > 0) {
        $usuario = mysqli_fetch_assoc($resultado_admin);
        $is_admin = ($usuario['admin'] == 1);
    }
}
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
            grid-template-columns: 120px 1fr 200px auto;
            gap: 20px;
            align-items: center;
            padding: 20px;
            border-bottom: 2px solid #e2e8f0;
            transition: background 0.3s;
        }
        
        .carrinho-item.updating {
            background: #f8fafc;
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
            margin-bottom: 10px;
        }
        
        .item-estoque {
            color: #64748b;
            font-size: 0.85rem;
            margin-top: 5px;
        }
        
        .item-quantidade-controle {
            display: flex;
            align-items: center;
            gap: 10px;
            justify-content: center;
            flex-direction: column;
        }
        
        .quantidade-box {
            display: flex;
            align-items: center;
            gap: 8px;
            background: #f8fafc;
            padding: 8px 12px;
            border-radius: 8px;
            border: 2px solid #e2e8f0;
        }
        
        .btn-quantidade {
            width: 32px;
            height: 32px;
            border: none;
            background: #7c3aed;
            color: white;
            border-radius: 6px;
            cursor: pointer;
            font-size: 1.2rem;
            font-weight: bold;
            transition: all 0.3s;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        .btn-quantidade:hover:not(:disabled) {
            background: #6d28d9;
            transform: scale(1.1);
        }
        
        .btn-quantidade:disabled {
            opacity: 0.3;
            cursor: not-allowed;
        }
        
        .quantidade-display {
            min-width: 40px;
            text-align: center;
            font-size: 1.1rem;
            font-weight: 600;
            color: #334155;
        }
        
        .item-subtotal-box {
            text-align: center;
        }
        
        .item-subtotal {
            font-size: 1.5rem;
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
            transition: all 0.3s;
        }
        
        .btn-remover:hover {
            background-color: #dc2626;
            transform: translateY(-2px);
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
            transition: all 0.3s;
        }
        
        .btn-finalizar:hover {
            background-color: #059669;
            transform: translateY(-2px);
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
            animation: slideInRight 0.3s ease;
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
            from { transform: translateX(500px); opacity: 0; }
            to { transform: translateX(0); opacity: 1; }
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
        
        .loading-mini {
            display: none;
            width: 16px;
            height: 16px;
            border: 2px solid #e2e8f0;
            border-top-color: #7c3aed;
            border-radius: 50%;
            animation: spin 0.6s linear infinite;
        }
        
        @keyframes spin {
            to { transform: rotate(360deg); }
        }

        @media (max-width: 768px) {
            .carrinho-item {
                grid-template-columns: 80px 1fr;
                gap: 15px;
            }
            
            .item-quantidade-controle,
            .item-subtotal-box,
            .btn-remover {
                grid-column: 2;
                margin-top: 10px;
            }
            
            .item-quantidade-controle {
                justify-content: flex-start;
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
                    <form method="GET" action="index.php#linkprodutos">
                        <input type="text" name="busca" placeholder="Buscar esmaltes..." class="search-bar">
                        <button type="submit" style="display: none;"></button>
                    </form>
                    <img src="img/lupa.png" alt="Lupa" class="lupa" onclick="this.parentElement.querySelector('form').submit();">
                </div>
                <div class="cart-container">
                    <div style="display: flex; align-items: center; gap: 15px;">
                        <button class="cart-button" onclick="window.location.href='carrinho.php'">
                            <img src="img/sacoladecompras.png" alt="Ícone de sacola" style="width: 20px; height: 20px; margin-right: 8px;">
                            <span>Carrinho (<span id="contador-carrinho"><?php echo count($itens_carrinho); ?></span>)</span>
                        </button>
                        
                        <div class="user-avatar" onclick="window.location.href='perfil.php'" title="<?php echo $_SESSION['cliente_nome']; ?>">
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
                    
                    <?php if($is_admin): ?>
                        <li><a href="admin.php" style="background: #10b981; padding: 15px 15px; border-radius: 0px;">Admin</a></li>
                    <?php endif; ?>
                    
                    <li><a href="logout.php">Sair</a></li>
                </ul>
            </nav>
        </div>
    </header>
    <div class="spacer"></div>

    <div id="notificacao-container"></div>

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
        <h1 class="carrinho-titulo">Meu Carrinho</h1>
        
        <?php if(empty($itens_carrinho)): ?>
            <div class="carrinho-vazio">
                <h2>🛒 Seu carrinho está vazio</h2>
                <p><a href="index.php#linkprodutos">← Continue comprando</a></p>
            </div>
        <?php else: ?>
            <div class="carrinho-itens">
                <?php foreach($itens_carrinho as $item): 
                    $produto_id = intval($item['produto_id']);
                    $imagem = isset($imagens_produtos[$produto_id]) ? $imagens_produtos[$produto_id] : 'img/placeholder.png';
                    
                    $subtotal = $item['preco'] * $item['quantidade'];
                    $total_carrinho += $subtotal;
                    
                    $estoque_disponivel = isset($estoques[$produto_id]) ? $estoques[$produto_id] : 0;
                ?>
                    <div class="carrinho-item" id="item-<?php echo $produto_id; ?>" data-produto-id="<?php echo $produto_id; ?>">
                        <div class="item-imagem">
                            <img src="<?php echo $imagem; ?>" alt="<?php echo htmlspecialchars($item['nome']); ?>">
                        </div>
                        
                        <div class="item-info">
                            <h3><?php echo htmlspecialchars($item['nome']); ?></h3>
                            <div class="item-preco">R$ <?php echo number_format($item['preco'], 2, ',', '.'); ?> cada</div>
                            <div class="item-estoque">
                                <?php if($estoque_disponivel > 10): ?>
                                    ✓ Disponível em estoque
                                <?php elseif($estoque_disponivel > 0): ?>
                                    ⚠ Apenas <?php echo $estoque_disponivel; ?> unidades disponíveis
                                <?php else: ?>
                                    ✗ Produto esgotado
                                <?php endif; ?>
                            </div>
                        </div>
                        
                        <div class="item-quantidade-controle">
                            <div class="quantidade-box">
                                <button class="btn-quantidade" onclick="alterarQuantidade(<?php echo $produto_id; ?>, -1)" <?php echo $item['quantidade'] <= 1 ? 'disabled' : ''; ?>>
                                    −
                                </button>
                                
                                <span class="quantidade-display" id="qtd-<?php echo $produto_id; ?>">
                                    <?php echo $item['quantidade']; ?>
                                </span>
                                
                                <button class="btn-quantidade" onclick="alterarQuantidade(<?php echo $produto_id; ?>, 1)" <?php echo $item['quantidade'] >= $estoque_disponivel ? 'disabled' : ''; ?>>
                                    +
                                </button>
                            </div>
                            <div class="loading-mini" id="loading-<?php echo $produto_id; ?>"></div>
                        </div>
                        
                        <div class="item-subtotal-box">
                            <div class="item-subtotal" id="subtotal-<?php echo $produto_id; ?>">
                                R$ <?php echo number_format($subtotal, 2, ',', '.'); ?>
                            </div>
                            <a href="carrinho.php?remover=<?php echo $produto_id; ?>" 
                               class="btn-remover" 
                               onclick="return confirm('Deseja remover este produto?')">
                                Remover
                            </a>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
            
            <div class="carrinho-total" id="total-carrinho">
                Total: R$ <span id="valor-total"><?php echo number_format($total_carrinho, 2, ',', '.'); ?></span>
            </div>
            
            <a href="finalizar_pedido.php" class="btn-finalizar">Finalizar Pedido →</a>
        <?php endif; ?>
    </div>
        
    <!-- Notificação flutuante -->
    <script>
        function mostrarNotificacao(mensagem, tipo = 'sucesso') {
            const container = document.getElementById('notificacao-container');
            const notif = document.createElement('div');
            notif.className = `notificacao-flutuante notificacao-${tipo}`;
            notif.innerHTML = `<strong>${tipo === 'sucesso' ? '✓' : '⚠'} ${mensagem}</strong>`;
            
            container.appendChild(notif);
            
            setTimeout(() => {
                notif.remove();
            }, 4000);
        }
        
        function alterarQuantidade(produtoId, delta) {
            const qtdElement = document.getElementById('qtd-' + produtoId);
            const loadingElement = document.getElementById('loading-' + produtoId);
            const itemElement = document.getElementById('item-' + produtoId);
            
            const quantidadeAtual = parseInt(qtdElement.textContent);
            const novaQuantidade = quantidadeAtual + delta;
            
            if(novaQuantidade < 1) return;
            
            // Mostrar loading
            loadingElement.style.display = 'block';
            itemElement.classList.add('updating');
            
            // Enviar requisição AJAX
            fetch('carrinho.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: `ajax_update=1&produto_id=${produtoId}&quantidade=${novaQuantidade}`
            })
            .then(response => response.json())
            .then(data => {
                loadingElement.style.display = 'none';
                itemElement.classList.remove('updating');
                
                if(data.sucesso) {
                    // Atualizar quantidade
                    qtdElement.textContent = data.quantidade;
                    
                    // Atualizar subtotal
                    document.getElementById('subtotal-' + produtoId).textContent = 'R$ ' + data.subtotal;
                    
                    // Atualizar total
                    document.getElementById('valor-total').textContent = data.total;
                    
                    // Atualizar botões
                    const btnMenos = itemElement.querySelector('.btn-quantidade:first-of-type');
                    const btnMais = itemElement.querySelector('.btn-quantidade:last-of-type');
                    
                    btnMenos.disabled = (data.quantidade <= 1);
                    
                    mostrarNotificacao(data.mensagem, 'sucesso');
                } else {
                    mostrarNotificacao(data.erro, 'erro');
                    
                    // Se tinha estoque máximo, desabilitar botão +
                    if(data.estoque_disponivel) {
                        const btnMais = itemElement.querySelector('.btn-quantidade:last-of-type');
                        btnMais.disabled = true;
                    }
                }
            })
            .catch(error => {
                loadingElement.style.display = 'none';
                itemElement.classList.remove('updating');
                mostrarNotificacao('Erro ao atualizar quantidade!', 'erro');
                console.error('Erro:', error);
            });
        }
    </script>
</body>
</html>