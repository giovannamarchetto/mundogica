CREATE DATABASE IF NOT EXISTS loja_esmaltes;
USE loja_esmaltes;

CREATE TABLE clientes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(100) NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    senha VARCHAR(255) NOT NULL,
    telefone VARCHAR(20),
    data_cadastro TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE produtos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(100) NOT NULL,
    descricao TEXT,
    preco DECIMAL(10,2) NOT NULL,
    imagem VARCHAR(255),
    marca VARCHAR(50),
    estoque INT DEFAULT 0,
    data_cadastro TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE pedidos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    cliente_id INT,
    total DECIMAL(10,2) NOT NULL,
    status ENUM('pendente', 'pago', 'enviado', 'entregue') DEFAULT 'pendente',
    data_pedido TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (cliente_id) REFERENCES clientes(id)
);

CREATE TABLE pedido_itens (
    id INT AUTO_INCREMENT PRIMARY KEY,
    pedido_id INT,
    produto_id INT,
    quantidade INT NOT NULL,
    preco DECIMAL(10,2) NOT NULL,
    FOREIGN KEY (pedido_id) REFERENCES pedidos(id),
    FOREIGN KEY (produto_id) REFERENCES produtos(id)
);

-- Inserir todos os produtos do HTML
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