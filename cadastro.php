<?php
include 'conexao.php';

$erro = '';
$sucesso = '';

$email_preenchido = isset($_GET['email']) ? htmlspecialchars($_GET['email']) : '';

if($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nome = trim($_POST['nome']);
    $email = trim($_POST['email']);
    $senha = $_POST['senha'];
    $confirmar_senha = $_POST['confirmar_senha'];
    $telefone = trim($_POST['telefone']);
    
    if(empty($nome) || empty($email) || empty($senha)) {
        $erro = "Todos os campos obrigatórios devem ser preenchidos!";
    } elseif($senha !== $confirmar_senha) {
        $erro = "As senhas não coincidem!";
    } elseif(strlen($senha) < 6) {
        $erro = "A senha deve ter pelo menos 6 caracteres!";
    } elseif(!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $erro = "E-mail inválido!";
    } else {
        $stmt = $conn->prepare("SELECT id FROM clientes WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if($result->num_rows > 0) {
            $erro = "Este e-mail já está cadastrado!";
        } else {
            $senha_hash = password_hash($senha, PASSWORD_DEFAULT);
            $stmt = $conn->prepare("INSERT INTO clientes (nome, email, senha, telefone) VALUES (?, ?, ?, ?)");
            $stmt->bind_param("ssss", $nome, $email, $senha_hash, $telefone);
            
            if($stmt->execute()) {
                $sucesso = "Cadastro realizado com sucesso! Redirecionando para login...";
                header("Refresh: 2; url=login.php");
            } else {
                $erro = "Erro ao cadastrar. Tente novamente.";
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro - Mundo GiCa</title>
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
            overflow: hidden; 
        }
        
        header, footer, .spacer {
            display: none !important;
        }
        
        .cadastro-container {
            max-width: 700px; 
            width: 100%;
            padding: 35px 40px;
            background: white;
            border-radius: 20px;
            box-shadow: 0 20px 60px rgba(0,0,0,0.3);
            animation: fadeIn 0.5s ease;
            max-height: 90vh;
            overflow-y: auto; 
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
        
        .logo-cadastro {
            text-align: center;
            margin-bottom: 20px; 
        }
        
        .logo-cadastro h1 {
            color: #7c3aed;
            font-size: 2rem; 
            margin-bottom: 5px;
        }
        
        .logo-cadastro p {
            color: #64748b;
            font-size: 0.9rem;
        }
        
        .cadastro-container h2 {
            text-align: center;
            margin-bottom: 20px; 
            color: #334155;
            font-size: 1.5rem; 
        }
        
        .form-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 15px;
            margin-bottom: 15px;
        }
        
        .form-group {
            margin-bottom: 0; 
        }
        
        .form-group.full-width {
            grid-column: 1 / -1; 
        }
        
        .form-group label {
            display: block;
            margin-bottom: 5px;
            font-weight: 600;
            color: #334155;
            font-size: 0.9rem;
        }
        
        .form-group input {
            width: 100%;
            padding: 10px; 
            border: 2px solid #e2e8f0;
            border-radius: 8px;
            font-size: 0.95rem;
            transition: border-color 0.3s;
            box-sizing: border-box;
        }
        
        .form-group input:focus {
            outline: none;
            border-color: #7c3aed;
            box-shadow: 0 0 0 3px rgba(124, 58, 237, 0.1);
        }
        
        .btn-cadastrar {
            width: 100%;
            padding: 12px; 
            background-color: #7c3aed;
            color: white;
            border: none;
            border-radius: 8px;
            font-size: 1rem;
            cursor: pointer;
            transition: all 0.3s;
            font-weight: 600;
            margin-top: 10px;
        }
        
        .btn-cadastrar:hover {
            background-color: #6d28d9;
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(124, 58, 237, 0.3);
        }
        
        .login-link {
            text-align: center;
            margin-top: 15px; 
            color: #64748b;
            font-size: 0.9rem;
        }
        
        .login-link a {
            color: #7c3aed;
            text-decoration: none;
            font-weight: 600;
            transition: color 0.3s;
        }
        
        .login-link a:hover {
            color: #6d28d9;
        }
        
        .alert {
            padding: 12px; 
            border-radius: 8px;
            margin-bottom: 15px; 
            animation: slideDown 0.3s ease;
            font-size: 0.9rem;
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
        
        .alert-erro {
            background-color: #fee2e2;
            color: #dc2626;
            border: 1px solid #fecaca;
        }
        
        .alert-sucesso {
            background-color: #d1fae5;
            color: #065f46;
            border: 1px solid #a7f3d0;
        }
        
        .voltar-home {
            text-align: center;
            margin-top: 12px;
        }
        
        .voltar-home a {
            color: #94a3b8;
            text-decoration: none;
            font-size: 0.85rem;
            transition: color 0.3s;
        }
        
        .voltar-home a:hover {
            color: #64748b;
        }
        
        .campo-opcional {
            color: #94a3b8;
            font-size: 0.75rem;
            font-weight: normal;
        }
        
        .requisitos-senha {
            font-size: 0.75rem;
            color: #64748b;
            margin-top: 3px;
        }
        
        @media (max-width: 600px) {
            .form-grid {
                grid-template-columns: 1fr;
            }
            .cadastro-container {
                padding: 25px;
            }
        }
    </style>
</head>
<body>
    <div class="cadastro-container">
        <div class="logo-cadastro">
            <h1>Mundo GiCa</h1>
            <p>Crie sua conta e comece a comprar</p>
        </div>
        
        <h2>Cadastro</h2>
        
        <?php if($erro): ?>
            <div class="alert alert-erro"> <?php echo $erro; ?></div>
        <?php endif; ?>
        
        <?php if($sucesso): ?>
            <div class="alert alert-sucesso"> <?php echo $sucesso; ?></div>
        <?php endif; ?>
        
        <form method="POST" action="">
            <div class="form-grid">
                <div class="form-group full-width">
                    <label for="nome">Nome Completo *</label>
                    <input type="text" id="nome" name="nome" value="<?php echo isset($_POST['nome']) ? htmlspecialchars($_POST['nome']) : ''; ?>" required placeholder="João da Silva">
                </div>
                
                <div class="form-group">
                    <label for="email">E-mail *</label>
                    <input type="email" id="email" name="email" value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : $email_preenchido; ?>" required placeholder="seu@email.com">
                </div>
                
                <div class="form-group">
                    <label for="telefone">Telefone <span class="campo-opcional">(opcional)</span></label>
                    <input type="tel" id="telefone" name="telefone" value="<?php echo isset($_POST['telefone']) ? htmlspecialchars($_POST['telefone']) : ''; ?>" placeholder="(16) 99999-9999">
                </div>
                
                <div class="form-group">
                    <label for="senha">Senha *</label>
                    <input type="password" id="senha" name="senha" required minlength="6" placeholder="Mínimo 6 caracteres">
                    <div class="requisitos-senha">Mínimo 6 caracteres</div>
                </div>
                
                <div class="form-group">
                    <label for="confirmar_senha">Confirmar Senha *</label>
                    <input type="password" id="confirmar_senha" name="confirmar_senha" required minlength="6" placeholder="Digite novamente">
                </div>
            </div>
            
            <button type="submit" class="btn-cadastrar">Criar Conta</button>
        </form>
        
        <div class="login-link">
            <p>Já tem uma conta? <a href="login.php">Faça login aqui</a></p>
        </div>
        
        <div class="voltar-home">
            <a href="index.php">← Voltar para a loja</a>
        </div>
    </div>
</body>
</html>