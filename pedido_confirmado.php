<?php
include 'conexao.php';

// Verificar se tem o número do pedido
if(!isset($_GET['pedido_id'])) {
    header('Location: index.php');
    exit;
}

$pedido_id = $_GET['pedido_id'];

// Buscar dados do pedido
$sql = "SELECT p.*, c.nome as cliente_nome, c.email 
        FROM pedidos p 
        JOIN clientes c ON p.cliente_id = c.id 
        WHERE p.id = $pedido_id";
$resultado = mysqli_query($conn, $sql);

if(mysqli_num_rows($resultado) == 0) {
    header('Location: index.php');
    exit;
}

$pedido = mysqli_fetch_assoc($resultado);
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pedido Confirmado! - Mundo GiCa</title>
    <link rel="stylesheet" href="style.css">
    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }
        
        .confirmacao-container {
            max-width: 600px;
            background: white;
            border-radius: 20px;
            padding: 50px;
            text-align: center;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
            animation: slideDown 0.5s ease;
        }
        
        @keyframes slideDown {
            from {
                opacity: 0;
                transform: translateY(-50px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        .icone-sucesso {
            font-size: 5rem;
            margin-bottom: 20px;
            animation: bounce 1s ease infinite;
        }
        
        @keyframes bounce {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-10px); }
        }
        
        h1 {
            color: #10b981;
            font-size: 2.5rem;
            margin-bottom: 15px;
        }
        
        .pedido-numero {
            background: #f0fdf4;
            color: #10b981;
            padding: 15px 30px;
            border-radius: 10px;
            font-size: 1.5rem;
            font-weight: bold;
            margin: 20px 0;
            border: 2px solid #10b981;
        }
        
        .detalhes-pedido {
            background: #f8fafc;
            padding: 20px;
            border-radius: 10px;
            margin: 20px 0;
            text-align: left;
        }
        
        .detalhe-item {
            display: flex;
            justify-content: space-between;
            padding: 10px 0;
            border-bottom: 1px solid #e2e8f0;
        }
        
        .detalhe-item:last-child {
            border-bottom: none;
        }
        
        .detalhe-label {
            color: #64748b;
            font-weight: 600;
        }
        
        .detalhe-valor {
            color: #334155;
            font-weight: 500;
        }
        
        .total {
            font-size: 1.3rem;
            color: #7c3aed;
            font-weight: bold;
        }
        
        .mensagem {
            color: #64748b;
            margin: 20px 0;
            line-height: 1.6;
        }
        
        .btn-voltar {
            display: inline-block;
            margin-top: 20px;
            padding: 15px 40px;
            background: #7c3aed;
            color: white;
            text-decoration: none;
            border-radius: 10px;
            font-weight: 600;
            transition: all 0.3s;
        }
        
        .btn-voltar:hover {
            background: #6d28d9;
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(124, 58, 237, 0.3);
        }
        
        .contador {
            color: #94a3b8;
            font-size: 1.1rem;
            margin-top: 20px;
            font-weight: 600;
        }
        
        .contador-numero {
            color: #7c3aed;
            font-size: 2rem;
            font-weight: bold;
            display: block;
            margin-top: 10px;
        }
        
        .barra-progresso {
            width: 100%;
            height: 6px;
            background: #e2e8f0;
            border-radius: 10px;
            margin-top: 15px;
            overflow: hidden;
        }
        
        .barra-progresso-fill {
            height: 100%;
            background: linear-gradient(90deg, #7c3aed, #667eea);
            border-radius: 10px;
            transition: width 1s linear;
        }
    </style>
</head>
<body>
    <div class="confirmacao-container">
        <div class="icone-sucesso">🎉</div>
        
        <h1>Pedido Confirmado!</h1>
        
        <div class="pedido-numero">
            Pedido #<?php echo str_pad($pedido['id'], 5, '0', STR_PAD_LEFT); ?>
        </div>
        
        <div class="detalhes-pedido">
            <div class="detalhe-item">
                <span class="detalhe-label">📧 Email:</span>
                <span class="detalhe-valor"><?php echo $pedido['email']; ?></span>
            </div>
            
            <div class="detalhe-item">
                <span class="detalhe-label">📅 Data:</span>
                <span class="detalhe-valor"><?php echo date('d/m/Y H:i', strtotime($pedido['data_pedido'])); ?></span>
            </div>
            
            <div class="detalhe-item">
                <span class="detalhe-label">💳 Pagamento:</span>
                <span class="detalhe-valor">
                    <?php 
                    switch($pedido['forma_pagamento']) {
                        case 'cartao': echo 'Cartão de Crédito'; break;
                        case 'boleto': echo 'Boleto Bancário'; break;
                        case 'pix': echo 'PIX'; break;
                        default: echo ucfirst($pedido['forma_pagamento']);
                    }
                    ?>
                </span>
            </div>
            
            <div class="detalhe-item">
                <span class="detalhe-label total">💰 Total:</span>
                <span class="detalhe-valor total">R$ <?php echo number_format($pedido['total'], 2, ',', '.'); ?></span>
            </div>
        </div>
        
        <div class="mensagem">
            <strong>Obrigado, <?php echo $pedido['cliente_nome']; ?>!</strong><br><br>
            Recebemos seu pedido e em breve entraremos em contato para confirmar o pagamento e informar sobre o envio.<br><br>
            Você receberá um e-mail em <strong><?php echo $pedido['email']; ?></strong> com todos os detalhes.
        </div>
        
        <a href="index.php" class="btn-voltar">← Voltar para a Loja</a>
        
        <div class="contador">
            Redirecionando automaticamente em
            <span class="contador-numero" id="countdown">60</span>
            segundos
        </div>
        
        <!-- Barra de progresso -->
        <div class="barra-progresso">
            <div class="barra-progresso-fill" id="progressBar" style="width: 100%;"></div>
        </div>
    </div>

    <script>
        // Contador regressivo de 60 segundos
        let segundos = 60;
        const countdownElement = document.getElementById('countdown');
        const progressBar = document.getElementById('progressBar');
        
        const intervalo = setInterval(() => {
            segundos--;
            countdownElement.textContent = segundos;
            
            // Atualizar barra de progresso
            const porcentagem = (segundos / 60) * 100;
            progressBar.style.width = porcentagem + '%';
            
            // Quando chegar a 0, redireciona
            if(segundos <= 0) {
                clearInterval(intervalo);
                window.location.href = 'index.php';
            }
        }, 1000);
    </script>
</body>
</html>