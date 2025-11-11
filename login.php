<?php
include 'conexao.php';

if(isset($_SESSION['cliente_id'])) {
    header('Location: index.php');
    exit;
}

$erro = '';

if($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];
    $senha = $_POST['senha'];
    
    if(empty($email) || empty($senha)) {
        $erro = "Preencha todos os campos!";
    } else {
        $sql = "SELECT * FROM clientes WHERE email = '$email'";
        $resultado = mysqli_query($conn, $sql);
        
        if(mysqli_num_rows($resultado) == 1) {
            $cliente = mysqli_fetch_assoc($resultado);
            
            if(password_verify($senha, $cliente['senha'])) {
                $_SESSION['cliente_id'] = $cliente['id'];
                $_SESSION['cliente_nome'] = $cliente['nome'];
                $_SESSION['cliente_email'] = $cliente['email'];
                
                header('Location: index.php');
                exit;
            } else {
                $erro = "Senha incorreta!";
            }
        } else {
            $erro = "E-mail não encontrado!";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Mundo GiCa</title>
    <link rel="stylesheet" href="style.css">
    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
            margin: 0;
        }
        
        header, footer, .spacer {
            display: none !important;
        }
        
        .login-container {
            max-width: 400px;
            width: 100%;
            padding: 40px;
            background: white;
            border-radius: 20px;
            box-shadow: 0 20px 60px rgba(0,0,0,0.3);
            animation: fadeIn 0.5s ease;
        }
        
        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(-20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        .logo-login {
            text-align: center;
            margin-bottom: 30px;
        }
        
        .logo-login h1 {
            color: #7c3aed;
            font-size: 2.5rem;
            margin-bottom: 10px;
        }
        
        .logo-login p {
            color: #64748b;
            font-size: 1rem;
        }
        
        .login-container h2 {
            text-align: center;
            margin-bottom: 30px;
            color: #334155;
            font-size: 1.8rem;
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
        
        .form-group input {
            width: 100%;
            padding: 12px;
            border: 2px solid #e2e8f0;
            border-radius: 8px;
            font-size: 1rem;
            box-sizing: border-box;
            transition: border-color 0.3s;
        }
        
        .form-group input:focus {
            outline: none;
            border-color: #7c3aed;
            box-shadow: 0 0 0 3px rgba(124, 58, 237, 0.1);
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
            transition: all 0.3s;
        }
        
        .btn-login:hover {
            background-color: #6d28d9;
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(124, 58, 237, 0.3);
        }
        
        .alert {
            background-color: #fee2e2;
            color: #dc2626;
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 20px;
            border: 1px solid #fecaca;
            animation: slideDown 0.3s ease;
        }
        
        @keyframes slideDown {
            from {
                opacity: 0;
                transform: translateY(-10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        .cadastro-link {
            text-align: center;
            margin-top: 20px;
            color: #64748b;
        }
        
        .cadastro-link a {
            color: #7c3aed;
            font-weight: 600;
            text-decoration: none;
            transition: color 0.3s;
        }
        
        .cadastro-link a:hover {
            color: #6d28d9;
        }
        
        .voltar-home {
            text-align: center;
            margin-top: 15px;
        }
        
        .voltar-home a {
            color: #94a3b8;
            text-decoration: none;
            font-size: 0.9rem;
            transition: color 0.3s;
        }
        
        .voltar-home a:hover {
            color: #64748b;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <div class="logo-login">
            <h1>Mundo GiCa</h1>
            <p>Faça login para continuar</p>
        </div>
        
        <h2>Login</h2>
        
        <?php if($erro): ?>
            <div class="alert"><?php echo $erro; ?></div>
        <?php endif; ?>
        
        <form method="POST" action="">
            <div class="form-group">
                <label for="email">E-mail</label>
                <input type="email" id="email" name="email" required placeholder="seu@email.com">
            </div>
            
            <div class="form-group">
                <label for="senha">Senha</label>
                <input type="password" id="senha" name="senha" required placeholder="Digite sua senha">
            </div>
            
            <button type="submit" class="btn-login">Entrar</button>
        </form>
        
        <div class="cadastro-link">
            <p>Não tem uma conta? <a href="cadastro.php">Cadastre-se aqui</a></p>
        </div>
        
        <div class="voltar-home">
            <a href="index.php">← Voltar para a loja</a>
        </div>
    </div>
</body>
</html>