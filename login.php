<?php
include 'conexao.php';

// Se já está logado, redirecionar para index
if(isset($_SESSION['cliente_id'])) {
    header('Location: index.php');
    exit;
}

$erro = '';

// Verificar se o formulário foi enviado
if($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Pegar dados do formulário
    $email = $_POST['email'];
    $senha = $_POST['senha'];
    
    // Validar se campos estão vazios
    if(empty($email) || empty($senha)) {
        $erro = "Preencha todos os campos!";
    }
    else {
        // Buscar cliente no banco
        $sql = "SELECT * FROM clientes WHERE email = '$email'";
        $resultado = mysqli_query($conn, $sql);
        
        // Verificar se encontrou o cliente
        if(mysqli_num_rows($resultado) == 1) {
            $cliente = mysqli_fetch_assoc($resultado);
            
            // Verificar se a senha está correta
            if(password_verify($senha, $cliente['senha'])) {
                // Login com sucesso!
                $_SESSION['cliente_id'] = $cliente['id'];
                $_SESSION['cliente_nome'] = $cliente['nome'];
                $_SESSION['cliente_email'] = $cliente['email'];
                
                // Redirecionar para index
                header('Location: index.php');
                exit;
            }
            else {
                $erro = "Senha incorreta!";
            }
        }
        else {
            $erro = "E-mail não encontrado!";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Login - Mundo GiCa</title>
    <link rel="stylesheet" href="style.css">
    <style>
        .login-container {
            max-width: 400px;
            margin: 100px auto;
            padding: 40px;
            background: white;
            border-radius: 12px;
            box-shadow: 0 10px 25px rgba(0,0,0,0.1);
        }
        
        .login-container h2 {
            text-align: center;
            margin-bottom: 30px;
            color: #7c3aed;
        }
        
        .form-group {
            margin-bottom: 20px;
        }
        
        .form-group label {
            display: block;
            margin-bottom: 5px;
            font-weight: 600;
        }
        
        .form-group input {
            width: 100%;
            padding: 12px;
            border: 2px solid #e2e8f0;
            border-radius: 8px;
            font-size: 1rem;
            box-sizing: border-box;
        }
        
        .btn-login {
            width: 100%;
            padding: 15px;
            background-color: #7c3aed;
            color: white;
            border: none;
            border-radius: 8px;
            font-size: 1.1rem;
            cursor: pointer;
            font-weight: 600;
        }
        
        .btn-login:hover {
            background-color: #6d28d9;
        }
        
        .alert {
            background-color: #fee2e2;
            color: #dc2626;
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 20px;
            border: 1px solid #fecaca;
        }
        
        .cadastro-link {
            text-align: center;
            margin-top: 20px;
        }
        
        .cadastro-link a {
            color: #7c3aed;
            font-weight: 600;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <h2>Login</h2>
        
        <?php if($erro): ?>
            <div class="alert"><?php echo $erro; ?></div>
        <?php endif; ?>
        
        <form method="POST" action="">
            <div class="form-group">
                <label>E-mail</label>
                <input type="email" name="email" required>
            </div>
            
            <div class="form-group">
                <label>Senha</label>
                <input type="password" name="senha" required>
            </div>
            
            <button type="submit" class="btn-login">Entrar</button>
        </form>
        
        <div class="cadastro-link">
            <p>Não tem uma conta? <a href="cadastro.php">Cadastre-se aqui</a></p>
        </div>
    </div>
</body>
</html>