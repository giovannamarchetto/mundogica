<?php
include 'conexao.php';

$erro = '';
$sucesso = '';

// Pré-preencher email se vier da newsletter
$email_preenchido = isset($_GET['email']) ? htmlspecialchars($_GET['email']) : '';

if($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nome = trim($_POST['nome']);
    $email = trim($_POST['email']);
    $senha = $_POST['senha'];
    $confirmar_senha = $_POST['confirmar_senha'];
    $telefone = trim($_POST['telefone']);
    
    // Validações
    if(empty($nome) || empty($email) || empty($senha)) {
        $erro = "Todos os campos obrigatórios devem ser preenchidos!";
    } elseif($senha !== $confirmar_senha) {
        $erro = "As senhas não coincidem!";
    } elseif(strlen($senha) < 6) {
        $erro = "A senha deve ter pelo menos 6 caracteres!";
    } elseif(!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $erro = "E-mail inválido!";
    } else {
        // Verificar se email já existe
        $stmt = $conn->prepare("SELECT id FROM clientes WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if($result->num_rows > 0) {
            $erro = "Este e-mail já está cadastrado!";
        } else {
            // Inserir novo cliente
            $senha_hash = password_hash($senha, PASSWORD_DEFAULT);
            $stmt = $conn->prepare("INSERT INTO clientes (nome, email, senha, telefone) VALUES (?, ?, ?, ?)");
            $stmt->bind_param("ssss", $nome, $email, $senha_hash, $telefone);
            
            if($stmt->execute()) {
                $sucesso = "Cadastro realizado com sucesso! <a href='login.php' style='color: #065f46; font-weight: bold;'>Faça login aqui</a>";
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
        .cadastro-container {
            max-width: 500px;
            margin: 100px auto;
            padding: 40px;
            background: white;
            border-radius: 12px;
            box-shadow: 0 10px 25px rgba(0,0,0,0.1);
        }
        
        .cadastro-container h2 {
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
            color: #334155;
        }
        
        .form-group input {
            width: 100%;
            padding: 12px;
            border: 2px solid #e2e8f0;
            border-radius: 8px;
            font-size: 1rem;
            transition: border-color 0.3s;
            box-sizing: border-box;
        }
        
        .form-group input:focus {
            outline: none;
            border-color: #7c3aed;
        }
        
        .btn-cadastrar {
            width: 100%;
            padding: 15px;
            background-color: #7c3aed;
            color: white;
            border: none;
            border-radius: 8px;
            font-size: 1.1rem;
            cursor: pointer;
            transition: background-color 0.3s;
        }
        
        .btn-cadastrar:hover {
            background-color: #6d28d9;
        }
        
        .login-link {
            text-align: center;
            margin-top: 20px;
        }
        
        .login-link a {
            color: #7c3aed;
            text-decoration: none;
            font-weight: 600;
        }
        
        .alert {
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 20px;
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
    </style>
</head>
<body>
    <?php include 'header.php'; ?>
    
    <div class="cadastro-container">
        <h2>Criar Nova Conta</h2>
        
        <?php if($erro): ?>
            <div class="alert alert-erro"><?php echo $erro; ?></div>
        <?php endif; ?>
        
        <?php if($sucesso): ?>
            <div class="alert alert-sucesso"><?php echo $sucesso; ?></div>
        <?php endif; ?>
        
        <form method="POST" action="">
            <div class="form-group">
                <label for="nome">Nome Completo *</label>
                <input type="text" id="nome" name="nome" value="<?php echo isset($_POST['nome']) ? htmlspecialchars($_POST['nome']) : ''; ?>" required>
            </div>
            
            <div class="form-group">
                <label for="email">E-mail *</label>
                <input type="email" id="email" name="email" value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : $email_preenchido; ?>" required>
            </div>
            
            <div class="form-group">
                <label for="telefone">Telefone</label>
                <input type="tel" id="telefone" name="telefone" value="<?php echo isset($_POST['telefone']) ? htmlspecialchars($_POST['telefone']) : ''; ?>" placeholder="(16) 99999-9999">
            </div>
            
            <div class="form-group">
                <label for="senha">Senha * (mínimo 6 caracteres)</label>
                <input type="password" id="senha" name="senha" required minlength="6">
            </div>
            
            <div class="form-group">
                <label for="confirmar_senha">Confirmar Senha *</label>
                <input type="password" id="confirmar_senha" name="confirmar_senha" required minlength="6">
            </div>
            
            <button type="submit" class="btn-cadastrar">Cadastrar</button>
        </form>
        
        <div class="login-link">
            <p>Já tem uma conta? <a href="login.php">Faça login aqui</a></p>
        </div>
    </div>
</body>
</html>