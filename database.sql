CREATE DATABASE IF NOT EXISTS loja_esmaltes1;
USE loja_esmaltes1;

-- Tabela de clientes com campo admin
CREATE TABLE IF NOT EXISTS clientes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(100) NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    senha VARCHAR(255) NOT NULL,
    telefone VARCHAR(20),
    admin TINYINT(1) DEFAULT 0,
    data_cadastro TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Tabela de produtos
CREATE TABLE IF NOT EXISTS produtos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(100) NOT NULL,
    descricao TEXT,
    preco DECIMAL(10,2) NOT NULL,
    imagem VARCHAR(255),
    marca VARCHAR(50),
    estoque INT DEFAULT 0,
    data_cadastro TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Tabela de pedidos
CREATE TABLE IF NOT EXISTS pedidos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    cliente_id INT,
    total DECIMAL(10,2) NOT NULL,
    status ENUM('pendente', 'pago', 'enviado', 'entregue') DEFAULT 'pendente',
    endereco TEXT,
    cep VARCHAR(20),
    forma_pagamento VARCHAR(50),
    observacoes TEXT,
    data_pedido TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (cliente_id) REFERENCES clientes(id)
);

-- Tabela de itens do pedido
CREATE TABLE IF NOT EXISTS pedido_itens (
    id INT AUTO_INCREMENT PRIMARY KEY,
    pedido_id INT,
    produto_id INT,
    quantidade INT NOT NULL,
    preco DECIMAL(10,2) NOT NULL,
    FOREIGN KEY (pedido_id) REFERENCES pedidos(id),
    FOREIGN KEY (produto_id) REFERENCES produtos(id)
);

-- Inserir produtos
INSERT INTO produtos (nome, descricao, preco, imagem, marca, estoque) VALUES
('A.mar Risqué', 'Nova cor de esmalte da nossa amada linha de esmaltes! Alta durabilidade, brilho intenso e secagem rápida', 3.50, 'img/esmalteamar.png', 'Risqué', 50),
('Escarlate Risqué', 'Nosso querido vermelho escarlate! Alta durabilidade, brilho intenso e secagem rápida', 3.00, 'img/esmalteescarlate.png', 'Risqué', 45),
('Sou Topping Risqué', 'Aquele brilho para arrasar! Alta durabilidade, brilho intenso e secagem rápida', 4.00, 'img/esmaltesoutopping.png', 'Risqué', 30),
('Ano Novo no Rio Risqué', 'Aquele brilho que te faz suspirar! Alta durabilidade, brilho intenso e secagem rápida', 7.00, 'img/esmalteanonovonorio.png', 'Risqué', 25),
('Olho Grego Dailus', 'Aquele azul que não mancha! Alta durabilidade, brilho intenso e secagem rápida', 5.50, 'img/esmalteolhogrego.png', 'Dailus', 40),
('Tá Chovendo Fini Colorama', 'A coleção com cheirinho de fini chegou! Alta durabilidade, brilho intenso e secagem rápida', 4.50, 'img/esmaltetachovendofini.png', 'Colorama', 35),
('Imensidão Impala', 'Para quem curte aquele tom de verde maravilhoso. Alta durabilidade, brilho intenso e secagem rápida', 3.00, 'img/esmalteimensidao.png', 'Impala', 60),
('Dizeres Impala', 'A combinação linda do vinho cintilante. Alta durabilidade, brilho intenso e secagem rápida', 3.50, 'img/esmaltedizeres.png', 'Impala', 55),
('Carícia Impala', 'O perolado maravilhoso! Alta durabilidade, brilho intenso e secagem rápida', 3.00, 'img/esmaltecaricia.png', 'Impala', 48),
('Laçada Perfeita Impala', 'A boiadeira chegou, bebê! Alta durabilidade, brilho intenso e secagem rápida', 3.50, 'img/esmaltelaçadaperfeita.png', 'Impala', 42),
('Vermelhaço Avon', 'Para os amantes de vermelho! Alta durabilidade, brilho intenso e secagem rápida', 7.00, 'img/esmaltevermelhaco.png', 'Avon', 38),
('Azul Liberdade Avon', 'A cor que te valoriza! Alta durabilidade, brilho intenso e secagem rápida', 7.00, 'img/esmalteazulliberdade.png', 'Avon', 36),
('Coleção Ana Hickman Estrelas da Ana', 'A cor ideal para você! Alta durabilidade, brilho intenso e secagem rápida', 40.00, 'img/esmaltesana.png', 'Coleções', 20),
('Coleção Hits Diamante', 'Os melhores glitters! Alta durabilidade, brilho intenso e secagem rápida', 48.00, 'img/esmalteshits.png', 'Coleções', 18),
('Coleção Anita Capadócia', 'Todos os tons! Alta durabilidade, brilho intenso e secagem rápida', 37.00, 'img/esmaltesanita.png', 'Coleções', 22);

-- EXECUTE ESTE SQL NO PHPMYADMIN PARA USAR IMAGENS DE PLACEHOLDER
-- Isso vai fazer as imagens aparecerem automaticamente!

USE loja_esmaltes;

-- EXECUTE ESTE SQL NO PHPMYADMIN
-- Vai atualizar as imagens para URLs que FUNCIONAM

USE loja_esmaltes;

-- Atualizar todas as imagens para placeholders coloridos
UPDATE produtos SET imagem = 'https://via.placeholder.com/300x300/7c3aed/ffffff?text=A.mar' WHERE id = 1;
UPDATE produtos SET imagem = 'https://via.placeholder.com/300x300/dc2626/ffffff?text=Escarlate' WHERE id = 2;
UPDATE produtos SET imagem = 'https://via.placeholder.com/300x300/f59e0b/ffffff?text=Sou+Topping' WHERE id = 3;
UPDATE produtos SET imagem = 'https://via.placeholder.com/300x300/3b82f6/ffffff?text=Ano+Novo' WHERE id = 4;
UPDATE produtos SET imagem = 'https://via.placeholder.com/300x300/2563eb/ffffff?text=Olho+Grego' WHERE id = 5;
UPDATE produtos SET imagem = 'https://via.placeholder.com/300x300/ec4899/ffffff?text=Fini' WHERE id = 6;
UPDATE produtos SET imagem = 'https://via.placeholder.com/300x300/10b981/ffffff?text=Imensidao' WHERE id = 7;
UPDATE produtos SET imagem = 'https://via.placeholder.com/300x300/7c2d12/ffffff?text=Dizeres' WHERE id = 8;
UPDATE produtos SET imagem = 'https://via.placeholder.com/300x300/e879f9/ffffff?text=Caricia' WHERE id = 9;
UPDATE produtos SET imagem = 'https://via.placeholder.com/300x300/f97316/ffffff?text=Lacada' WHERE id = 10;
UPDATE produtos SET imagem = 'https://via.placeholder.com/300x300/b91c1c/ffffff?text=Vermelhaco' WHERE id = 11;
UPDATE produtos SET imagem = 'https://via.placeholder.com/300x300/1e40af/ffffff?text=Azul+Liberdade' WHERE id = 12;
UPDATE produtos SET imagem = 'https://via.placeholder.com/300x300/db2777/ffffff?text=Ana+Hickman' WHERE id = 13;
UPDATE produtos SET imagem = 'https://via.placeholder.com/300x300/eab308/ffffff?text=Hits+Diamante' WHERE id = 14;
UPDATE produtos SET imagem = 'https://via.placeholder.com/300x300/8b5cf6/ffffff?text=Anita' WHERE id = 15;

-- Verificar se atualizou
SELECT id, nome, imagem FROM produtos;


-- Alterações no Banco dps de criar os campos do pedido 

USE loja_esmaltes1;

ALTER TABLE pedidos 
ADD COLUMN endereco TEXT AFTER status;
ALTER TABLE pedidos 
ADD COLUMN cep VARCHAR(20) AFTER endereco;
ALTER TABLE pedidos 
ADD COLUMN forma_pagamento VARCHAR(50) AFTER cep;
ALTER TABLE pedidos 
ADD COLUMN observacoes TEXT AFTER forma_pagamento;
SELECT * FROM pedidos;
