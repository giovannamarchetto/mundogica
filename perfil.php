<?php
include 'conexao.php';

if(!isset($_SESSION['cliente_id'])) {
    $_SESSION['erro'] = "Você precisa fazer login para acessar o perfil!";
    header('Location: login.php');
    exit;
}

$cliente_id = $_SESSION['cliente_id'];

$sql_cliente = "SELECT * FROM clientes WHERE id = $cliente_id";
$resultado_cliente = mysqli_query($conn, $sql_cliente);
$cliente = mysqli_fetch_assoc($resultado_cliente);

$sql_pedidos = "SELECT p.*, 
                COUNT(pi.id) as total_itens
                FROM pedidos p
                LEFT JOIN pedido_itens pi ON p.id = pi.pedido_id
                WHERE p.cliente_id = $cliente_id
                GROUP BY p.id
                ORDER BY p.data_pedido DESC";
$resultado_pedidos = mysqli_query($conn, $sql_pedidos);
$pedidos = array();
while($pedido = mysqli_fetch_assoc($resultado_pedidos)) {
    $pedidos[] = $pedido;
}

$total_pedidos = count($pedidos);
$total_gasto = 0;
foreach($pedidos as $pedido) {
    $total_gasto += $pedido['total'];
}

$is_admin = ($cliente['admin'] == 1);

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
    <title>Meu Perfil - Mundo GiCa</title>
    <link rel="stylesheet" href="style.css">
    <style>
        .perfil-container {
            max-width: 1200px;
            margin: 100px auto 50px;
            padding: 20px;
        }
        
        .perfil-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border-radius: 12px;
            padding: 40px;
            color: white;
            margin-bottom: 30px;
            display: flex;
            align-items: center;
            gap: 30px;
            box-shadow: 0 10px 25px rgba(0,0,0,0.1);
        }
        
        .perfil-avatar {
            width: 120px;
            height: 120px;
            background: white;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 3rem;
            font-weight: bold;
            color: #7c3aed;
            box-shadow: 0 4px 15px rgba(0,0,0,0.2);
            flex-shrink: 0;
        }
        
        .perfil-info {
            flex: 1;
        }
        
        .perfil-info h1 {
            margin: 0 0 10px 0;
            font-size: 2.5rem;
        }
        
        .perfil-info p {
            margin: 5px 0;
            opacity: 0.9;
            font-size: 1.1rem;
        }
        
        .perfil-stats {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }
        
        .stat-card {
            background: white;
            padding: 25px;
            border-radius: 12px;
            text-align: center;
            box-shadow: 0 4px 6px rgba(0,0,0,0.05);
            border: 2px solid #f1f5f9;
            transition: all 0.3s;
        }
        
        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 15px rgba(0,0,0,0.1);
            border-color: #7c3aed;
        }
        
        .stat-number {
            font-size: 2.5rem;
            font-weight: bold;
            color: #7c3aed;
            margin-bottom: 5px;
        }
        
        .stat-label {
            color: #64748b;
            font-size: 0.95rem;
            font-weight: 600;
        }
        
        .pedidos-section {
            background: white;
            border-radius: 12px;
            padding: 30px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.05);
        }
        
        .pedidos-section h2 {
            color: #334155;
            margin-bottom: 25px;
            font-size: 2rem;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        
        .pedido-card {
            background: #f8fafc;
            border: 2px solid #e2e8f0;
            border-radius: 12px;
            padding: 25px;
            margin-bottom: 20px;
            transition: all 0.3s;
            cursor: pointer;
        }
        
        .pedido-card:hover {
            border-color: #7c3aed;
            box-shadow: 0 4px 12px rgba(124, 58, 237, 0.1);
        }
        
        .pedido-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 15px;
            flex-wrap: wrap;
            gap: 15px;
        }
        
        .pedido-numero {
            font-size: 1.3rem;
            font-weight: bold;
            color: #334155;
        }
        
        .pedido-status {
            padding: 8px 20px;
            border-radius: 20px;
            font-weight: 600;
            font-size: 0.9rem;
        }
        
        .status-pendente {
            background: #fef3c7;
            color: #f59e0b;
        }
        
        .status-pago {
            background: #d1fae5;
            color: #059669;
        }
        
        .status-enviado {
            background: #dbeafe;
            color: #2563eb;
        }
        
        .status-entregue {
            background: #e2e8f0;
            color: #475569;
        }
        
        .pedido-info {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 15px;
            margin-bottom: 15px;
        }
        
        .pedido-info-item {
            display: flex;
            flex-direction: column;
            gap: 5px;
        }
        
        .pedido-info-label {
            color: #64748b;
            font-size: 0.85rem;
            font-weight: 600;
        }
        
        .pedido-info-value {
            color: #334155;
            font-weight: 600;
            font-size: 1rem;
        }
        
        .pedido-total {
            font-size: 1.5rem;
            color: #7c3aed;
        }
        
        .pedido-detalhes {
            display: none;
            margin-top: 20px;
            padding-top: 20px;
            border-top: 2px solid #e2e8f0;
        }
        
        .pedido-detalhes.show {
            display: block;
            animation: slideDown 0.3s ease;
        }
        
        @keyframes slideDown {
            from {
                opacity: 0;
                max-height: 0;
            }
            to {
                opacity: 1;
                max-height: 1000px;
            }
        }
        
        .item-pedido {
            display: flex;
            align-items: center;
            gap: 15px;
            padding: 15px;
            background: white;
            border-radius: 8px;
            margin-bottom: 10px;
        }
        
        .item-pedido img {
            width: 60px;
            height: 60px;
            object-fit: cover;
            border-radius: 6px;
        }
        
        .item-pedido-info {
            flex: 1;
        }
        
        .item-pedido-nome {
            font-weight: 600;
            color: #334155;
            margin-bottom: 5px;
        }
        
        .item-pedido-quantidade {
            color: #64748b;
            font-size: 0.9rem;
        }
        
        .item-pedido-preco {
            font-weight: bold;
            color: #7c3aed;
            font-size: 1.1rem;
        }
        
        .btn-ver-detalhes {
            background: none;
            border: none;
            color: #7c3aed;
            font-weight: 600;
            cursor: pointer;
            padding: 8px 15px;
            border-radius: 6px;
            transition: all 0.3s;
        }
        
        .btn-ver-detalhes:hover {
            background: #f3e8ff;
        }
        
        .sem-pedidos {
            text-align: center;
            padding: 60px 20px;
            color: #64748b;
        }
        
        .sem-pedidos h3 {
            font-size: 1.5rem;
            margin-bottom: 15px;
        }
        
        .sem-pedidos a {
            color: #7c3aed;
            font-weight: 600;
            text-decoration: none;
            font-size: 1.1rem;
        }
        
        .badge-admin {
            background: #10b981;
            color: white;
            padding: 5px 15px;
            border-radius: 20px;
            font-size: 0.85rem;
            font-weight: 600;
            margin-left: 10px;
        }
        
        .btn-voltar {
            display: inline-block;
            margin-bottom: 20px;
            padding: 10px 20px;
            background: #f1f5f9;
            color: #64748b;
            text-decoration: none;
            border-radius: 8px;
            font-weight: 600;
            transition: all 0.3s;
        }
        
        .btn-voltar:hover {
            background: #e2e8f0;
            color: #334155;
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
            .perfil-header {
                flex-direction: column;
                text-align: center;
                padding: 30px 20px;
            }
            
            .perfil-info h1 {
                font-size: 2rem;
            }
            
            .pedido-header {
                flex-direction: column;
                align-items: flex-start;
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
                            <span>Carrinho<?php if(isset($_SESSION['carrinho'])) echo ' (' . count($_SESSION['carrinho']) . ')'; ?></span>
                        </button>
                        
                        <div class="user-avatar" onclick="window.location.href='perfil.php'" title="<?php echo $cliente['nome']; ?>">
                            <?php echo strtoupper(substr(explode(' ', $cliente['nome'])[0], 0, 1)); ?>
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
    
    <div class="perfil-container">
        <a href="index.php" class="btn-voltar">← Voltar para a loja</a>
        
        <div class="perfil-header">
            <div class="perfil-avatar">
                <?php echo strtoupper(substr(explode(' ', $cliente['nome'])[0], 0, 1)); ?>
            </div>
            <div class="perfil-info">
                <h1>
                    <?php echo htmlspecialchars($cliente['nome']); ?>
                    <?php if($is_admin): ?>
                        <span class="badge-admin">ADMIN</span>
                    <?php endif; ?>
                </h1>
                <p><?php echo htmlspecialchars($cliente['email']); ?></p>
                <?php if($cliente['telefone']): ?>
                    <p><?php echo htmlspecialchars($cliente['telefone']); ?></p>
                <?php endif; ?>
                <p>Membro desde <?php echo date('d/m/Y', strtotime($cliente['data_cadastro'])); ?></p>
            </div>
        </div>
        
        <div class="perfil-stats">
            <div class="stat-card">
                <div class="stat-number"><?php echo $total_pedidos; ?></div>
                <div class="stat-label">Pedidos Realizados</div>
            </div>
            <div class="stat-card">
                <div class="stat-number">R$ <?php echo number_format($total_gasto, 2, ',', '.'); ?></div>
                <div class="stat-label">Total Gasto</div>
            </div>
            <div class="stat-card">
                <div class="stat-number">
                    <?php 
                    $pedidos_entregues = 0;
                    foreach($pedidos as $p) {
                        if($p['status'] == 'entregue') $pedidos_entregues++;
                    }
                    echo $pedidos_entregues;
                    ?>
                </div>
                <div class="stat-label">Pedidos Entregues</div>
            </div>
        </div>
        
        <div class="pedidos-section">
            <h2>Histórico de Pedidos</h2>
            
            <?php if(empty($pedidos)): ?>
                <div class="sem-pedidos">
                    <h3>Você ainda não fez nenhum pedido</h3>
                    <p><a href="index.php#linkprodutos">Comece a comprar agora! →</a></p>
                </div>
            <?php else: ?>
                <?php foreach($pedidos as $pedido): 
                    $pedido_id = $pedido['id'];
                    $sql_itens = "SELECT pi.*, p.nome, p.imagem 
                                  FROM pedido_itens pi 
                                  JOIN produtos p ON pi.produto_id = p.id 
                                  WHERE pi.pedido_id = $pedido_id";
                    $resultado_itens = mysqli_query($conn, $sql_itens);
                    $itens = array();
                    while($item = mysqli_fetch_assoc($resultado_itens)) {
                        $itens[] = $item;
                    }
                ?>
                <div class="pedido-card" onclick="toggleDetalhes(<?php echo $pedido['id']; ?>)">
                    <div class="pedido-header">
                        <div>
                            <div class="pedido-numero">Pedido #<?php echo str_pad($pedido['id'], 5, '0', STR_PAD_LEFT); ?></div>
                            <div style="color: #64748b; font-size: 0.9rem; margin-top: 5px;">
                                <?php echo date('d/m/Y \à\s H:i', strtotime($pedido['data_pedido'])); ?>
                            </div>
                        </div>
                        <div>
                            <span class="pedido-status status-<?php echo $pedido['status']; ?>">
                                <?php 
                                switch($pedido['status']) {
                                    case 'pendente': echo 'Pendente'; break;
                                    case 'pago': echo '✓ Pago'; break;
                                    case 'enviado': echo 'Enviado'; break;
                                    case 'entregue': echo 'Entregue'; break;
                                }
                                ?>
                            </span>
                        </div>
                    </div>
                    
                    <div class="pedido-info">
                        <div class="pedido-info-item">
                            <span class="pedido-info-label">Total de Itens</span>
                            <span class="pedido-info-value"><?php echo $pedido['total_itens']; ?> produto(s)</span>
                        </div>
                        <div class="pedido-info-item">
                            <span class="pedido-info-label">Forma de Pagamento</span>
                            <span class="pedido-info-value">
                                <?php 
                                switch($pedido['forma_pagamento']) {
                                    case 'cartao': echo 'Cartão'; break;
                                    case 'boleto': echo 'Boleto'; break;
                                    case 'pix': echo 'PIX'; break;
                                    default: echo ucfirst($pedido['forma_pagamento']);
                                }
                                ?>
                            </span>
                        </div>
                        <div class="pedido-info-item">
                            <span class="pedido-info-label">Valor Total</span>
                            <span class="pedido-info-value pedido-total">R$ <?php echo number_format($pedido['total'], 2, ',', '.'); ?></span>
                        </div>
                    </div>
                    
                    <button class="btn-ver-detalhes" onclick="event.stopPropagation(); toggleDetalhes(<?php echo $pedido['id']; ?>)">
                        <span id="btn-text-<?php echo $pedido['id']; ?>">Ver Detalhes ▼</span>
                    </button>
                    
                    <div id="detalhes-<?php echo $pedido['id']; ?>" class="pedido-detalhes">
                        <h4 style="color: #334155; margin-bottom: 15px;">Itens do Pedido:</h4>
                        <?php foreach($itens as $item): 
                            $imagem = isset($imagens_produtos[$item['produto_id']]) ? $imagens_produtos[$item['produto_id']] : $item['imagem'];
                        ?>
                        <div class="item-pedido">
                            <img src="<?php echo $imagem; ?>" alt="<?php echo htmlspecialchars($item['nome']); ?>">
                            <div class="item-pedido-info">
                                <div class="item-pedido-nome"><?php echo htmlspecialchars($item['nome']); ?></div>
                                <div class="item-pedido-quantidade">
                                    Quantidade: <?php echo $item['quantidade']; ?> × R$ <?php echo number_format($item['preco'], 2, ',', '.'); ?>
                                </div>
                            </div>
                            <div class="item-pedido-preco">
                                R$ <?php echo number_format($item['quantidade'] * $item['preco'], 2, ',', '.'); ?>
                            </div>
                        </div>
                        <?php endforeach; ?>
                        
                        <?php if($pedido['endereco']): ?>
                        <div style="margin-top: 20px; padding: 15px; background: white; border-radius: 8px;">
                            <h4 style="color: #334155; margin-bottom: 10px;">Endereço de Entrega:</h4>
                            <p style="color: #64748b; margin: 5px 0;"><?php echo nl2br(htmlspecialchars($pedido['endereco'])); ?></p>
                            <p style="color: #64748b; margin: 5px 0;"><strong>CEP:</strong> <?php echo htmlspecialchars($pedido['cep']); ?></p>
                        </div>
                        <?php endif; ?>
                        
                        <?php if($pedido['observacoes']): ?>
                        <div style="margin-top: 15px; padding: 15px; background: white; border-radius: 8px;">
                            <h4 style="color: #334155; margin-bottom: 10px;">Observações:</h4>
                            <p style="color: #64748b;"><?php echo nl2br(htmlspecialchars($pedido['observacoes'])); ?></p>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>
    
 <div style="text-align: center; margin-top: 40px; padding: 30px; background: white; border-radius: 12px; box-shadow: 0 4px 6px rgba(0,0,0,0.05);">
            <h3 style="color: #334155; margin-bottom: 15px;">Precisa sair?</h3>
            <a href="logout.php" style="display: inline-block; padding: 12px 30px; background: #ef4444; color: white; text-decoration: none; border-radius: 8px; font-weight: 600; transition: all 0.3s;">
                Deslogar da Conta
            </a>
        </div>
    </div>

    <script>
        function toggleDetalhes(pedidoId) {
            const detalhes = document.getElementById('detalhes-' + pedidoId);
            const btnText = document.getElementById('btn-text-' + pedidoId);
            
            if(detalhes.classList.contains('show')) {
                detalhes.classList.remove('show');
                btnText.textContent = 'Ver Detalhes ▼';
            } else {
                detalhes.classList.add('show');
                btnText.textContent = 'Ocultar Detalhes ▲';
            }
        }
    </script>
</body>
</html>