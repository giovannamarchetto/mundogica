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
                    <?php
                    // Contar itens no carrinho
                    $total_itens = 0;
                    if(isset($_SESSION['carrinho'])) {
                        $total_itens = count($_SESSION['carrinho']);
                    }
                    ?>
                    <a href="carrinho.php" class="cart-button" style="text-decoration: none;">
                        <span>🛒 Carrinho (<?php echo $total_itens; ?>)</span>
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
                <li><a href="index.php#linkprodutos">Produtos</a></li>
                <li><a href="index.php#promocoes">Promoções</a></li>
                
                <?php if(isset($_SESSION['cliente_id'])): ?>
                    <!-- Usuário está logado -->
                    <li><a href="carrinho.php">Carrinho</a></li>
                    
                    <?php
                    // Verificar se é admin - SEM USAR PREPARED STATEMENT
                    $cliente_id = $_SESSION['cliente_id'];
                    $sql_admin = "SELECT admin FROM clientes WHERE id = $cliente_id";
                    $resultado_admin = mysqli_query($conn, $sql_admin);
                    
                    $is_admin = false;
                    if($resultado_admin && mysqli_num_rows($resultado_admin) > 0) {
                        $usuario = mysqli_fetch_assoc($resultado_admin);
                        $is_admin = ($usuario['admin'] == 1);
                    }
                    
                    if($is_admin):
                    ?>
                        <li><a href="admin.php" style="background: #10b981; padding: 8px 15px; border-radius: 6px; color: white;">⚙️ Admin</a></li>
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