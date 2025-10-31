<?php
include 'conexao.php';

// ATENÇÃO: Execute este arquivo apenas UMA VEZ e depois DELETE ele por segurança!

// Verificar se já existe admin
$check = $conn->query("SELECT COUNT(*) as total FROM clientes WHERE admin = 1");
$result = $check->fetch_assoc();

if($result['total'] > 0) {
    echo "<h2>Já existe um administrador cadastrado!</h2>";
    echo "<p>Faça login com: <strong>admin@mundogica.com</strong></p>";
    echo "<p><a href='index.php'>Ir para a loja</a></p>";
    exit;
}

// Criar um usuário administrador
$nome = "Administrador";
$email = "admin@mundogica.com";
$senha = password_hash("admin123", PASSWORD_DEFAULT);
$telefone = "(16) 99999-9999";

$stmt = $conn->prepare("INSERT INTO clientes (nome, email, senha, telefone, admin) VALUES (?, ?, ?, ?, 1)");
$stmt->bind_param("ssss", $nome, $email, $senha, $telefone);

?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Criar Administrador</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            max-width: 600px;
            margin: 50px auto;
            padding: 20px;
            background: #f5f5f5;
        }
        .container {
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        h1 {
            color: #7c3aed;
            border-bottom: 3px solid #7c3aed;
            padding-bottom: 10px;
        }
        .success {
            background: #d1fae5;
            border-left: 4px solid #10b981;
            padding: 15px;
            margin: 20px 0;
            border-radius: 5px;
        }
        .error {
            background: #fee2e2;
            border-left: 4px solid #ef4444;
            padding: 15px;
            margin: 20px 0;
            border-radius: 5px;
        }
        .warning {
            background: #fef3c7;
            border-left: 4px solid #f59e0b;
            padding: 15px;
            margin: 20px 0;
            border-radius: 5px;
        }
        .credentials {
            background: #f8fafc;
            padding: 20px;
            border-radius: 8px;
            margin: 20px 0;
        }
        .credentials strong {
            color: #7c3aed;
        }
        a {
            display: inline-block;
            margin-top: 20px;
            padding: 12px 30px;
            background: #7c3aed;
            color: white;
            text-decoration: none;
            border-radius: 8px;
            font-weight: 600;
        }
        a:hover {
            background: #6d28d9;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>🔐 Criar Administrador</h1>
        
        <?php
        if($stmt->execute()) {
            echo '<div class="success">';
            echo '<h2>✓ Administrador criado com sucesso!</h2>';
            echo '</div>';
            
            echo '<div class="credentials">';
            echo '<h3>Credenciais de Acesso:</h3>';
            echo '<p><strong>Email:</strong> admin@mundogica.com</p>';
            echo '<p><strong>Senha:</strong> admin123</p>';
            echo '</div>';
            
            echo '<div class="warning">';
            echo '<h3>⚠ IMPORTANTE:</h3>';
            echo '<p><strong>DELETE este arquivo (criar_admin.php) AGORA por segurança!</strong></p>';
            echo '<p>Qualquer pessoa pode executá-lo e criar um administrador.</p>';
            echo '</div>';
            
            echo '<a href="login.php">Fazer Login como Admin</a>';
        } else {
            echo '<div class="error">';
            echo '<h2>✗ Erro ao criar administrador!</h2>';
            echo '<p>Erro: ' . $conn->error . '</p>';
            echo '</div>';
        }
        ?>
    </div>
</body>
</html>