<?php if(isset($_SESSION['cliente_id'])): ?>
    <!-- Verificar se é admin -->
    <?php
    $is_admin = false;
    if(isset($_SESSION['cliente_id'])) {
        $stmt = $conn->prepare("SELECT admin FROM clientes WHERE id = ?");
        $stmt->bind_param("i", $_SESSION['cliente_id']);
        $stmt->execute();
        $result = $stmt->get_result();
        $usuario = $result->fetch_assoc();
        $is_admin = ($usuario && $usuario['admin'] == 1);
    }
    ?>
    
    <li><a href="carrinho.php">Carrinho</a></li>
    <?php if($is_admin): ?>
        <li><a href="admin.php">Administração</a></li>
    <?php endif; ?>
    <li><a href="logout.php">Sair (<?php echo $_SESSION['cliente_nome']; ?>)</a></li>
<?php else: ?>
    <li><a href="cadastro.php">Cadastro</a></li>
    <li><a href="login.php">Login</a></li>
<?php endif; ?>