<?php
/**
 * Script de Verificação da Instalação
 * Execute este arquivo para verificar se tudo está configurado corretamente
 * Acesse: http://localhost/seu-projeto/verificar_instalacao.php
 */

error_reporting(E_ALL);
ini_set('display_errors', 1);

?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verificação de Instalação - Mundo GiCa</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            max-width: 900px;
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
        .check-item {
            margin: 15px 0;
            padding: 15px;
            border-radius: 5px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .success {
            background: #d1fae5;
            border-left: 4px solid #10b981;
        }
        .error {
            background: #fee2e2;
            border-left: 4px solid #ef4444;
        }
        .warning {
            background: #fef3c7;
            border-left: 4px solid #f59e0b;
        }
        .status {
            font-weight: bold;
            padding: 5px 15px;
            border-radius: 20px;
        }
        .status.ok {
            background: #10b981;
            color: white;
        }
        .status.fail {
            background: #ef4444;
            color: white;
        }
        .status.warn {
            background: #f59e0b;
            color: white;
        }
        .details {
            font-size: 0.9em;
            color: #666;
            margin-top: 5px;
        }
        .section {
            margin: 30px 0;
        }
        .section h2 {
            color: #334155;
            margin-bottom: 15px;
        }
        .btn-action {
            display: inline-block;
            margin-top: 15px;
            padding: 12px 30px;
            background: #7c3aed;
            color: white;
            text-decoration: none;
            border-radius: 8px;
            font-weight: 600;
        }
        .btn-action:hover {
            background: #6d28d9;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>🔍 Verificação de Instalação - Mundo GiCa</h1>
        
        <div class="section">
            <h2>1. Verificação do PHP</h2>
            <?php
            $php_version = phpversion();
            $php_ok = version_compare($php_version, '7.4.0', '>=');
            ?>
            <div class="check-item <?php echo $php_ok ? 'success' : 'error'; ?>">
                <div>
                    <strong>Versão do PHP:</strong> <?php echo $php_version; ?>
                    <div class="details">Requerido: PHP 7.4 ou superior</div>
                </div>
                <span class="status <?php echo $php_ok ? 'ok' : 'fail'; ?>">
                    <?php echo $php_ok ? '✓ OK' : '✗ ERRO'; ?>
                </span>
            </div>
            
            <?php
            $mysqli_loaded = extension_loaded('mysqli');
            ?>
            <div class="check-item <?php echo $mysqli_loaded ? 'success' : 'error'; ?>">
                <div>
                    <strong>Extensão MySQLi:</strong>
                    <div class="details">Necessária para conectar ao banco de dados</div>
                </div>
                <span class="status <?php echo $mysqli_loaded ? 'ok' : 'fail'; ?>">
                    <?php echo $mysqli_loaded ? '✓ OK' : '✗ ERRO'; ?>
                </span>
            </div>
        </div>

        <div class="section">
            <h2>2. Conexão com Banco de Dados</h2>
            <?php
            $servername = "localhost";
            $username = "root";
            $password = ""; // AJUSTE SE NECESSÁRIO
            $database = "loja_esmaltes";
            
            $conn_ok = false;
            $conn_message = "";
            
            $conn = @mysqli_connect($servername, $username, $password);
            
            if($conn) {
                $conn_ok = true;
                $conn_message = "Conexão estabelecida com sucesso";
                
                // Verificar se o banco existe
                $db_exists = mysqli_select_db($conn, $database);
                ?>
                <div class="check-item success">
                    <div>
                        <strong>Conexão MySQL:</strong>
                        <div class="details"><?php echo $conn_message; ?></div>
                    </div>
                    <span class="status ok">✓ OK</span>
                </div>
                
                <div class="check-item <?php echo $db_exists ? 'success' : 'error'; ?>">
                    <div>
                        <strong>Banco de Dados:</strong> <?php echo $database; ?>
                        <div class="details">
                            <?php echo $db_exists ? 'Banco encontrado' : 'Banco não encontrado. Execute o database.sql'; ?>
                        </div>
                    </div>
                    <span class="status <?php echo $db_exists ? 'ok' : 'fail'; ?>">
                        <?php echo $db_exists ? '✓ OK' : '✗ ERRO'; ?>
                    </span>
                </div>
                
                <?php if($db_exists): ?>
                    <div class="section">
                        <h2>3. Verificação das Tabelas</h2>
                        <?php
                        $tables = ['clientes', 'produtos', 'pedidos', 'pedido_itens'];
                        foreach($tables as $table):
                            $result = mysqli_query($conn, "SHOW TABLES LIKE '$table'");
                            $exists = mysqli_num_rows($result) > 0;
                            
                            if($exists) {
                                $count_result = mysqli_query($conn, "SELECT COUNT(*) as total FROM $table");
                                $count = mysqli_fetch_assoc($count_result)['total'];
                            }
                        ?>
                        <div class="check-item <?php echo $exists ? 'success' : 'error'; ?>">
                            <div>
                                <strong>Tabela:</strong> <?php echo $table; ?>
                                <div class="details">
                                    <?php 
                                    if($exists) {
                                        echo "Registros: $count";
                                    } else {
                                        echo "Tabela não encontrada";
                                    }
                                    ?>
                                </div>
                            </div>
                            <span class="status <?php echo $exists ? 'ok' : 'fail'; ?>">
                                <?php echo $exists ? '✓ OK' : '✗ ERRO'; ?>
                            </span>
                        </div>
                        <?php endforeach; ?>
                        
                        <?php
                        // Verificar se tem admin
                        $admin_result = mysqli_query($conn, "SELECT COUNT(*) as total FROM clientes WHERE admin = 1");
                        $has_admin = false;
                        if($admin_result) {
                            $admin_count = mysqli_fetch_assoc($admin_result)['total'];
                            $has_admin = $admin_count > 0;
                        }
                        ?>
                        <div class="check-item <?php echo $has_admin ? 'success' : 'warning'; ?>">
                            <div>
                                <strong>Usuário Administrador:</strong>
                                <div class="details">
                                    <?php 
                                    if($has_admin) {
                                        echo "Administrador cadastrado";
                                    } else {
                                        echo "Nenhum admin encontrado. Execute criar_admin.php";
                                    }
                                    ?>
                                </div>
                            </div>
                            <span class="status <?php echo $has_admin ? 'ok' : 'warn'; ?>">
                                <?php echo $has_admin ? '✓ OK' : '⚠ AVISO'; ?>
                            </span>
                        </div>
                    </div>
                <?php endif; ?>
                
            <?php
            } else {
                $conn_message = mysqli_connect_error();
                ?>
                <div class="check-item error">
                    <div>
                        <strong>Conexão MySQL:</strong>
                        <div class="details">Erro: <?php echo $conn_message; ?></div>
                    </div>
                    <span class="status fail">✗ ERRO</span>
                </div>
            <?php
            }
            ?>
        </div>

        <div class="section">
            <h2>4. Verificação de Arquivos</h2>
            <?php
            $required_files = [
                'conexao.php',
                'index.php',
                'header.php',
                'login.php',
                'cadastro.php',
                'carrinho.php',
                'detalhes_produto.php',
                'adicionar_carrinho.php',
                'finalizar_pedido.php',
                'admin.php',
                'admin_salvar_produto.php',
                'admin_excluir_produto.php',
                'admin_atualizar_status.php',
                'logout.php',
                'style.css'
            ];
            
            foreach($required_files as $file):
                $exists = file_exists($file);
            ?>
            <div class="check-item <?php echo $exists ? 'success' : 'error'; ?>">
                <div>
                    <strong>Arquivo:</strong> <?php echo $file; ?>
                </div>
                <span class="status <?php echo $exists ? 'ok' : 'fail'; ?>">
                    <?php echo $exists ? '✓ OK' : '✗ ERRO'; ?>
                </span>
            </div>
            <?php endforeach; ?>
        </div>

        <div class="section">
            <h2>5. Verificação da Pasta de Imagens</h2>
            <?php
            $img_folder = 'img';
            $img_exists = is_dir($img_folder);
            ?>
            <div class="check-item <?php echo $img_exists ? 'success' : 'error'; ?>">
                <div>
                    <strong>Pasta img/:</strong>
                    <div class="details">
                        <?php 
                        if($img_exists) {
                            $files = count(scandir($img_folder)) - 2; // Remove . e ..
                            echo "Pasta encontrada com $files arquivo(s)";
                        } else {
                            echo "Pasta não encontrada. Crie a pasta 'img' e adicione as imagens";
                        }
                        ?>
                    </div>
                </div>
                <span class="status <?php echo $img_exists ? 'ok' : 'fail'; ?>">
                    <?php echo $img_exists ? '✓ OK' : '✗ ERRO'; ?>
                </span>
            </div>
        </div>

        <div class="section">
            <h2>📊 Resumo</h2>
            <?php
            $total_checks = 0;
            $passed_checks = 0;
            
            if($php_ok) $passed_checks++;
            $total_checks++;
            
            if($mysqli_loaded) $passed_checks++;
            $total_checks++;
            
            if($conn_ok) $passed_checks++;
            $total_checks++;
            
            if(isset($db_exists) && $db_exists) $passed_checks++;
            $total_checks++;
            
            $percentage = ($passed_checks / $total_checks) * 100;
            ?>
            <div class="check-item <?php echo $percentage == 100 ? 'success' : ($percentage >= 50 ? 'warning' : 'error'); ?>">
                <div>
                    <strong>Status Geral:</strong>
                    <div class="details">
                        <?php echo $passed_checks; ?> de <?php echo $total_checks; ?> verificações principais passaram
                    </div>
                </div>
                <span class="status <?php echo $percentage == 100 ? 'ok' : ($percentage >= 50 ? 'warn' : 'fail'); ?>">
                    <?php echo round($percentage); ?>%
                </span>
            </div>
            
            <?php if($percentage == 100): ?>
                <div style="text-align: center; margin-top: 30px; padding: 20px; background: #d1fae5; border-radius: 10px;">
                    <h2 style="color: #10b981; margin: 0;">🎉 Instalação Completa!</h2>
                    <p style="margin: 10px 0 0 0;">Seu sistema está pronto para uso!</p>
                    <a href="index.php" class="btn-action">Ir para a Loja</a>
                    <?php if(!$has_admin): ?>
                        <a href="criar_admin.php" class="btn-action" style="background: #f59e0b;">Criar Administrador</a>
                    <?php endif; ?>
                </div>
            <?php else: ?>
                <div style="text-align: center; margin-top: 30px; padding: 20px; background: #fef3c7; border-radius: 10px;">
                    <h3 style="color: #f59e0b; margin: 0;">⚠ Atenção Necessária</h3>
                    <p style="margin: 10px 0 0 0;">Corrija os erros acima antes de usar o sistema.</p>
                </div>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>