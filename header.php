<header id="inicio">
    <div class="fixo">
        <div class="header-top">
            <div class="logo-container">
                <a href="index.php" style="text-decoration: none;">
                    <span class="store-name">Mundo Gica</span>
                </a>
            </div>
            
            <div class="search-container">
                <form method="GET" action="index.php">
                    <input type="text" name="busca" placeholder="Buscar esmaltes..." class="search-bar">
                </form>
            </div>
            
            <div class="cart-container">
                <?php if(isset($_SESSION['cliente_id'])): ?>
                    <div style="display: flex; align-items: center; gap: 15px;">
                        <?php $total_itens = isset($_SESSION['carrinho']) ? count($_SESSION['carrinho']) : 0; ?>
                        <a href="carrinho.php" class="cart-button" style="text-decoration: none; display: flex; align-items: center; gap: 5px;">
                        <span>Carrinho<?php if(isset($_SESSION['carrinho'])) echo ' (' . count($_SESSION['carrinho']) . ')'; ?></span>
                        </a>
                        
                        <div style="display: flex; align-items: center; gap: 10px; background: rgba(124, 58, 237, 0.1); padding: 8px 15px; border-radius: 25px; border: 2px solid #7c3aed;">
                            <div style="width: 35px; height: 35px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); border-radius: 50%; display: flex; align-items: center; justify-content: center; color: white; font-weight: bold; font-size: 1.1rem; flex-shrink: 0;">
                                <?php echo strtoupper(substr(explode(' ', $_SESSION['cliente_nome'])[0], 0, 1)); ?>
                            </div>
                            <span style="color: #7c3aed; font-weight: 600; white-space: nowrap;">
                                <?php echo explode(' ', $_SESSION['cliente_nome'])[0]; ?>
                            </span>
                        </div>
                    </div>
                <?php else: ?>
                    <a href="login.php" class="cart-button" style="text-decoration: none;">
                        <span>Carrinho</span>
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
                    <li><a href="carrinho.php">Carrinho</a></li>
                    
                    <?php
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
                        <li><a href="admin.php" style="background: #10b981; padding: 15px 15px; border-radius: 0px; color: white;">Admin</a></li>
                    <?php endif; ?>
                    
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
    <div style="background: #d1fae5; color: #065f46; padding: 15px; text-align: center; border-radius: 8px; margin: 20px auto; max-width: 800px; border: 1px solid #a7f3d0;">
        <?php echo $_SESSION['sucesso']; unset($_SESSION['sucesso']); ?>
    </div>
<?php endif; ?>

<?php if(isset($_SESSION['erro'])): ?>
    <div style="background: #fee2e2; color: #dc2626; padding: 15px; text-align: center; border-radius: 8px; margin: 20px auto; max-width: 800px; border: 1px solid #fecaca;">
        <?php echo $_SESSION['erro']; unset($_SESSION['erro']); ?>
    </div>
<?php endif; ?>

<?php if(isset($_SESSION['pedido_sucesso'])): ?>
    <div style="background: #d1fae5; color: #065f46; padding: 15px; text-align: center; border-radius: 8px; margin: 20px auto; max-width: 800px; border: 1px solid #a7f3d0;">
        <?php echo $_SESSION['pedido_sucesso']; unset($_SESSION['pedido_sucesso']); ?>
    </div>
<?php endif; ?>

<style>
.cart-button {
    background-color: #7c3aed;
    color: #ffffff;
    padding: 10px 18px;
    border: none;
    border-radius: 25px;
    cursor: pointer;
    transition: all 0.3s ease;
    font-weight: 600;
    font-size: 0.95rem;
}

.cart-button:hover {
    background-color: #6d28d9;
    transform: translateY(-2px);
}

@media (max-width: 768px) {
    .cart-container > div {
        gap: 10px !important;
    }
    
    .cart-container > div > div:last-child span {
        display: none; }
}
</style>