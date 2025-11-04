<?php
include 'conexao.php';

// Verificar se é administrador
if(!isset($_SESSION['cliente_id'])) {
    header('Location: login.php');
    exit;
}

$stmt = $conn->prepare("SELECT admin FROM clientes WHERE id = ?");
$stmt->bind_param("i", $_SESSION['cliente_id']);
$stmt->execute();
$result = $stmt->get_result();
$usuario = $result->fetch_assoc();

if(!$usuario || $usuario['admin'] != 1) {
    $_SESSION['erro'] = "Você não tem permissão para acessar esta área!";
    header('Location: index.php');
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

// Processar edição de produto
$produto_editar = null;
if(isset($_GET['editar'])) {
    $produto_id = intval($_GET['editar']);
    $stmt = $conn->prepare("SELECT * FROM produtos WHERE id = ?");
    $stmt->bind_param("i", $produto_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $produto_editar = $result->fetch_assoc();
}

// Buscar produtos
$produtos = [];
$sql = "SELECT * FROM produtos ORDER BY id DESC";
$resultado = mysqli_query($conn, $sql);
while($row = mysqli_fetch_assoc($resultado)) {
    $produtos[] = $row;
}

// Buscar pedidos
$pedidos = [];
$sql = "SELECT p.*, c.nome as cliente_nome FROM pedidos p 
        JOIN clientes c ON p.cliente_id = c.id 
        ORDER BY p.data_pedido DESC";
$resultado = mysqli_query($conn, $sql);
while($row = mysqli_fetch_assoc($resultado)) {
    $pedidos[] = $row;
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Administração - Mundo GiCa</title>
    <link rel="stylesheet" href="style.css">
    <style>
        .admin-container {
            max-width: 1200px;
            margin: 100px auto 50px;
            padding: 0 20px;
        }
        
        .admin-tabs {
            display: flex;
            margin-bottom: 30px;
            border-bottom: 2px solid #e2e8f0;
            flex-wrap: wrap;
        }
        
        .admin-tab {
            padding: 15px 30px;
            background: none;
            border: none;
            cursor: pointer;
            font-size: 1.1rem;
            border-bottom: 3px solid transparent;
            transition: all 0.3s;
        }
        
        .admin-tab.active {
            border-bottom-color: #7c3aed;
            color: #7c3aed;
            font-weight: 600;
        }
        
        .tab-content {
            display: none;
        }
        
        .tab-content.active {
            display: block;
        }
        
        .admin-table {
            width: 100%;
            background: white;
            border-radius: 12px;
            overflow-x: auto;
            box-shadow: 0 4px 6px rgba(0,0,0,0.05);
            margin-bottom: 20px;
        }
        
        .admin-table table {
            width: 100%;
            border-collapse: collapse;
        }
        
        .admin-table th,
        .admin-table td {
            padding: 15px;
            text-align: left;
            border-bottom: 1px solid #e2e8f0;
        }
        
        .admin-table th {
            background-color: #f8fafc;
            font-weight: 600;
            color: #334155;
        }
        
        .btn-admin {
            padding: 8px 15px;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            font-size: 0.9rem;
            margin: 2px;
            text-decoration: none;
            display: inline-block;
        }
        
        .btn-edit {
            background-color: #3b82f6;
            color: white;
        }
        
        .btn-delete {
            background-color: #ef4444;
            color: white;
        }
        
        .btn-add {
            background-color: #10b981;
            color: white;
            padding: 12px 20px;
            margin-bottom: 20px;
        }
        
        .btn-save {
            background-color: #7c3aed;
            color: white;
            padding: 12px 30px;
            width: 100%;
        }
        
        .status-pendente { color: #f59e0b; font-weight: 600; }
        .status-pago { color: #10b981; font-weight: 600; }
        .status-enviado { color: #3b82f6; font-weight: 600; }
        .status-entregue { color: #6b7280; font-weight: 600; }
        
        .form-admin {
            background: white;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.05);
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
        .form-group textarea,
        .form-group select {
            width: 100%;
            padding: 12px;
            border: 2px solid #e2e8f0;
            border-radius: 8px;
            font-size: 1rem;
            transition: border-color 0.3s;
            box-sizing: border-box;
        }
        
        .form-group input:focus,
        .form-group textarea:focus,
        .form-group select:focus {
            outline: none;
            border-color: #7c3aed;
        }
        
        .alert {
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 20px;
        }
        
        .alert-success {
            background-color: #d1fae5;
            color: #065f46;
            border: 1px solid #a7f3d0;
        }
        
        .alert-error {
            background-color: #fee2e2;
            color: #dc2626;
            border: 1px solid #fecaca;
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
                    <button class="cart-button" onclick="window.location.href='carrinho.php'">
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
                    <li><a href="carrinho.php">Carrinho</a></li>
                    <li><a href="admin.php" style="background: #10b981; padding: 8px 15px; border-radius: 6px;">⚙️ Admin</a></li>
                    <li><a href="logout.php">Sair (<?php echo $_SESSION['cliente_nome']; ?>)</a></li>
                </ul>
            </nav>
        </div>
    </header>
    <div class="spacer"></div>
    
    <div class="admin-container">
        <h1>🛠 Painel Administrativo</h1>
        
        <?php if(isset($_SESSION['sucesso'])): ?>
            <div class="alert alert-success"><?php echo $_SESSION['sucesso']; unset($_SESSION['sucesso']); ?></div>
        <?php endif; ?>
        
        <?php if(isset($_SESSION['erro'])): ?>
            <div class="alert alert-error"><?php echo $_SESSION['erro']; unset($_SESSION['erro']); ?></div>
        <?php endif; ?>
        
        <div class="admin-tabs">
            <button class="admin-tab active" onclick="openTab('produtos')">📦 Produtos</button>
            <button class="admin-tab" onclick="openTab('pedidos')">📋 Pedidos</button>
            <button class="admin-tab" onclick="openTab('adicionar')">➕ <?php echo isset($produto_editar) ? 'Editar Produto' : 'Adicionar Produto'; ?></button>
        </div>
        
        <!-- Tab Produtos -->
        <div id="produtos" class="tab-content active">
            <button class="btn-admin btn-add" onclick="openTab('adicionar')">+ Adicionar Novo Produto</button>
            
            <div class="admin-table">
                <table>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Imagem</th>
                            <th>Nome</th>
                            <th>Marca</th>
                            <th>Preço</th>
                            <th>Estoque</th>
                            <th>Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($produtos as $produto): 
                            // Usar imagem fixa do array
                            $imagem = isset($imagens_produtos[$produto['id']]) ? $imagens_produtos[$produto['id']] : $produto['imagem'];
                        ?>
                        <tr>
                            <td><?php echo $produto['id']; ?></td>
                            <td>
                                <img src="<?php echo $imagem; ?>" alt="<?php echo htmlspecialchars($produto['nome']); ?>" style="width: 50px; height: 50px; object-fit: cover; border-radius: 6px;">
                            </td>
                            <td><?php echo htmlspecialchars($produto['nome']); ?></td>
                            <td><?php echo htmlspecialchars($produto['marca']); ?></td>
                            <td>R$ <?php echo number_format($produto['preco'], 2, ',', '.'); ?></td>
                            <td><?php echo $produto['estoque']; ?></td>
                            <td>
                                <a href="admin.php?editar=<?php echo $produto['id']; ?>" class="btn-admin btn-edit">Editar</a>
                                <a href="admin_excluir_produto.php?id=<?php echo $produto['id']; ?>" class="btn-admin btn-delete" onclick="return confirm('Tem certeza que deseja excluir este produto?')">Excluir</a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
        
        <!-- Tab Pedidos -->
        <div id="pedidos" class="tab-content">
            <div class="admin-table">
                <table>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Cliente</th>
                            <th>Total</th>
                            <th>Status</th>
                            <th>Data</th>
                            <th>Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($pedidos as $pedido): ?>
                        <tr>
                            <td>#<?php echo $pedido['id']; ?></td>
                            <td><?php echo htmlspecialchars($pedido['cliente_nome']); ?></td>
                            <td>R$ <?php echo number_format($pedido['total'], 2, ',', '.'); ?></td>
                            <td>
                                <span class="status-<?php echo $pedido['status']; ?>">
                                    <?php echo ucfirst($pedido['status']); ?>
                                </span>
                            </td>
                            <td><?php echo date('d/m/Y H:i', strtotime($pedido['data_pedido'])); ?></td>
                            <td>
                                <select onchange="atualizarStatus(<?php echo $pedido['id']; ?>, this.value)" style="padding: 8px; border-radius: 6px; border: 1px solid #e2e8f0;">
                                    <option value="pendente" <?php echo $pedido['status'] == 'pendente' ? 'selected' : ''; ?>>Pendente</option>
                                    <option value="pago" <?php echo $pedido['status'] == 'pago' ? 'selected' : ''; ?>>Pago</option>
                                    <option value="enviado" <?php echo $pedido['status'] == 'enviado' ? 'selected' : ''; ?>>Enviado</option>
                                    <option value="entregue" <?php echo $pedido['status'] == 'entregue' ? 'selected' : ''; ?>>Entregue</option>
                                </select>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
        
        <!-- Tab Adicionar/Editar Produto -->
        <div id="adicionar" class="tab-content">
            <h2><?php echo isset($produto_editar) ? 'Editar Produto' : 'Adicionar Novo Produto'; ?></h2>
            <form method="POST" action="admin_salvar_produto.php" class="form-admin">
                <?php if(isset($produto_editar)): ?>
                    <input type="hidden" name="id" value="<?php echo $produto_editar['id']; ?>">
                <?php endif; ?>
                
                <div class="form-group">
                    <label for="nome">Nome do Produto</label>
                    <input type="text" id="nome" name="nome" value="<?php echo isset($produto_editar) ? htmlspecialchars($produto_editar['nome']) : ''; ?>" required>
                </div>
                
                <div class="form-group">
                    <label for="descricao">Descrição</label>
                    <textarea id="descricao" name="descricao" rows="4" required><?php echo isset($produto_editar) ? htmlspecialchars($produto_editar['descricao']) : ''; ?></textarea>
                </div>
                
                <div class="form-group">
                    <label for="preco">Preço (use ponto para centavos: 5.50)</label>
                    <input type="number" id="preco" name="preco" step="0.01" value="<?php echo isset($produto_editar) ? $produto_editar['preco'] : ''; ?>" required>
                </div>
                
                <div class="form-group">
                    <label for="marca">Marca</label>
                    <input type="text" id="marca" name="marca" value="<?php echo isset($produto_editar) ? htmlspecialchars($produto_editar['marca']) : ''; ?>" required>
                </div>
                
                <div class="form-group">
                    <label for="estoque">Estoque</label>
                    <input type="number" id="estoque" name="estoque" value="<?php echo isset($produto_editar) ? $produto_editar['estoque'] : ''; ?>" required>
                </div>
                
                <div class="form-group">
                    <label for="imagem">URL da Imagem (ex: img/produto.png)</label>
                    <input type="text" id="imagem" name="imagem" value="<?php echo isset($produto_editar) ? htmlspecialchars($produto_editar['imagem']) : ''; ?>" required placeholder="img/produto.png">
                </div>
                
                <button type="submit" class="btn-admin btn-save"><?php echo isset($produto_editar) ? 'Atualizar Produto' : 'Salvar Produto'; ?></button>
            </form>
        </div>
    </div>

    <script>
        function openTab(tabName) {
            // Esconder todas as tabs
            document.querySelectorAll('.tab-content').forEach(tab => {
                tab.classList.remove('active');
            });
            
            // Remover active de todos os botões
            document.querySelectorAll('.admin-tab').forEach(tab => {
                tab.classList.remove('active');
            });
            
            // Mostrar tab selecionada
            document.getElementById(tabName).classList.add('active');
            event.currentTarget.classList.add('active');
        }
        
        function atualizarStatus(pedidoId, status) {
            if(confirm('Deseja realmente atualizar o status do pedido?')) {
                window.location.href = 'admin_atualizar_status.php?pedido_id=' + pedidoId + '&status=' + status;
            }
        }
        
        // Abrir tab de edição se estiver editando
        <?php if(isset($produto_editar)): ?>
            window.addEventListener('DOMContentLoaded', function() {
                document.getElementById('adicionar').classList.add('active');
                document.getElementById('produtos').classList.remove('active');
                var tabs = document.getElementsByClassName('admin-tab');
                tabs[0].classList.remove('active');
                tabs[2].classList.add('active');
            });
        <?php endif; ?>
    </script>
</body>
</html>