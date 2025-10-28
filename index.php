<?php
include 'conexao.php';
?>

<!DOCTYPE html> 
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>E-commerce de Esmaltes - Atividade 2° bimestre Juliano</title>
    <link rel="stylesheet" href="style.css">
 
</head>
<body>
  GRUPO - Carla Victória Barros da Silva 09323067
        - Giovanna Borba Marchetto 09323042
  <header  id="inicio">
   <div class="fixo">
    <div class="header-top">
        <div class="logo-container">
            <span class="store-name">Mundo Gica</span>
        </div>
        <div class="search-container">
            <input type="text" placeholder="Buscar esmaltes..." class="search-bar">
            <img src="img/lupa.png" alt="Lupa" class="lupa">
        </div>
        <div class="cart-container">
            <button class="cart-button">
                <img src="img/sacoladecompras.png" alt="Ícone de sacola">
                <span>Minha Sacola</span>
            </button>
        </div>
    </div>

    <hr class="separator">

    <nav>
        <ul class="menu">
            <li><a href="#inicio">Início</a></li>
            <li><a href="#linkprodutos">Produtos</a></li>
            <li><a href="#promocoes">Promoções</a></li>
            <li><a href="cadastro.php">Cadastro</a></li>
        </ul>
    </nav>
  </div>
</header>
<div class="spacer"></div>
    <main>
      <div class="banner">
        <div class="banner-content">
            <h1>Descubra Cores Incríveis!</h1>
            <p>Dê vida às suas unhas com as últimas tendências em esmaltes. Aproveite descontos de até <strong>20%</strong> nas coleções da estação!</p>
            <a href="#linkprodutos" class="banner-button">Confira Agora</a>
        </div>
    </div>
    
<div class="brand-filter">
  <h3>Filtrar por Marca</h3>
  <div class="brand-options">
      <input type="radio" id="marca-risque" name="brand" />
      <label for="marca-risque">Risqué</label>
      
      <input type="radio" id="marca-Dailus" name="brand" />
      <label for="marca-Dailus">Dailus</label>
      
      <input type="radio" id="marca-Colorama" name="brand" />
      <label for="marca-Colorama">Colorama</label>

      <input type="radio" id="marca-Impala" name="brand" />
      <label for="marca-Impala">Impala</label>

      <input type="radio" id="marca-Avon" name="brand" />
      <label for="marca-Avon">Avon</label>

      <input type="radio" id="marca-Coleções" name="brand" />
      <label for="marca-Coleções">Coleções</label>
      
      <input type="radio" id="marca-todas" name="brand" checked />
      <label for="marca-todas">Todas as Marcas</label>
  </div>
</div>
    
      <section class="produtos" id="linkprodutos">
        <h2>Nossos Produtos</h2>
        <section class="produtos">
            <div class="produto">
              <a href="#popup1">
                <img src="img/esmalteamar.png" alt="Esmalte A.mar Risqué">
                <h3>A.mar Risqué</h3>
              </a>
                <p>R$ 3,50</p>
              <div class="rating">⭐⭐⭐⭐⭐ (12)</div>
            </div>
            <div class="produto">
              <a href="#popup2">
                <img src="img/esmalteescarlate.png" alt="Esmalte Escarlate Risqué">
                <h3>Escarlate Risqué</h3>
              </a>
                <p>R$ 3,00</p>
              <div class="rating">⭐⭐⭐⭐⭐ (15)</div>
            </div>
          <div class="produto">
              <a href="#popup3">
                <img src="img/esmaltesoutopping.png" alt="Esmalte Sou Topping Risqué">
                <h3>Sou Topping Risqué</h3>
              </a>
                <p>R$ 4,00</p>
              <div class="rating">⭐⭐⭐⭐⭐ (20)</div>
            </div>
          <div class="produto">
              <a href="#popup4">
                <img src="img/esmalteanonovonorio.png" alt="Esmalte Ano Novo no Rio Risqué">
                <h3>Ano Novo no Rio Risqué</h3>
              </a>
                <p>R$ 7,00</p>
              <div class="rating">⭐⭐⭐⭐⭐ (30)</div>
            </div>
            <div class="produto">
              <a href="#popup5">
                <img src="img/esmalteolhogrego.png" alt="Esmalte Olho Grego Dailus">
                <h3>Olho Grego Dailus</h3>
              </a>
                <p>R$ 5,50</p>
             <div class="rating">⭐⭐⭐⭐⭐ (15)</div>
            </div>
            <div class="produto">
              <a href="#popup6">
                <img src="img/esmaltetachovendofini.png" alt="Esmalte Tá Chovendo Fini Colorama">
                <h3>Tá Chovendo Fini Colorama</h3>
              </a>
                <p>R$ 4,50</p>
              <div class="rating">⭐⭐⭐⭐⭐ (09)</div>
            </div>
            <div class="produto">
              <a href="#popup7">
                <img src="img/esmalteimensidao.png" alt="Esmalte Imensidão Impala">
                <h3>Imensidão Impala</h3>
              </a>
                <p>R$ 3,00</p>
             <div class="rating">⭐⭐⭐⭐⭐ (12)</div>
            </div>
            <div class="produto">
              <a href="#popup8">
                <img src="img/esmaltedizeres.png" alt="Esmalte Dizeres Impala">
                <h3>Dizeres Impala</h3>
              </a>
                <p>R$ 3,00</p>
             <div class="rating">⭐⭐⭐⭐⭐ (07)</div>
            </div>
            <div class="produto">
              <a href="#popup9">
                <img src="img/esmaltecaricia.png" alt="Esmalte Carícia Impala">
                <h3>Carícia Impala</h3>
              </a>
                <p>R$ 3,00</p>
             <div class="rating">⭐⭐⭐⭐⭐ (14)</div>
            </div>
            <div class="produto">
              <a href="#popup10">
                <img src="img/esmaltelaçadaperfeita.png" alt="Esmalte Laçada Perfeita Impala">
                <h3>Laçada Perfeita Impala</h3>
              </a>
                <p>R$ 3,50</p>
             <div class="rating">⭐⭐⭐⭐⭐ (11)</div>
            </div>
            <div class="produto">
              <a href="#popup11">
                <img src="img/esmaltevermelhaco.png" alt="Esmalte Vermelhaço Avon">
                <h3>Vermelhaço Avon</h3>
              </a>
                <p>R$ 7,00</p>
             <div class="rating">⭐⭐⭐⭐⭐ (16)</div>
            </div>
            <div class="produto">
              <a href="#popup12">
                <img src="img/esmalteazulliberdade.png " alt="Esmalte Azul Liberdade Avon">
                <h3>Azul Liberdade Avon</h3>
              </a>
                <p>R$ 7,00</p>
             <div class="rating">⭐⭐⭐⭐⭐ (12)</div>
            </div>
        </section>
  </main>

  <div class="promotions-section" id="promocoes">
    <h2 class="promotions-title">Promoções de Coleção</h2>
    <div class="promotions-container">
        <div class="promotion-item">
            <a href="#popup13">
                <img src="img/esmaltesana.png" alt="Coleção Ana Hickman Estrelas da Ana">
                <h3 class="promotion-item-title">Coleção Ana Hickman Estrelas da Ana</h3>
            </a>
                <p class="promotion-item-price">R$ 40,00</p>
                <p class="promotion-item-discount">Desconto de 20%</p>
            <button class="promotion-item-button">Comprar</button>
        </div>
        <div class="promotion-item">
            <a href="#popup14">
                <img src="img/esmalteshits.png" alt="Coleção Hits Diamante">
                <h3 class="promotion-item-title">Coleção Hits Diamante</h3>
            </a>
                <p class="promotion-item-price">R$ 48,00</p>
                <p class="promotion-item-discount">Desconto de 15%</p>
            <button class="promotion-item-button">Comprar</button>
        </div>
        <div class="promotion-item">
            <a href="#popup15">
                <img src="img/esmaltesanita.png" alt="Coleção Anita Capadócia">
                <h3 class="promotion-item-title">Coleção Anita Capadócia</h3>
            </a>
            <p class="promotion-item-price">R$ 37,00</p>
            <p class="promotion-item-discount">Desconto de 10%</p>
            <button class="promotion-item-button">Comprar</button>
        </div>
    </div>
        
  <div id="popup1" class="popup">
    <div class="popup-content">
      <a href="#fechar" class="close">&times;</a>
      <img src="img/esmalteamar.png" alt="Esmalte A.mar Risqué" class="popup-image">
      <h3>Esmalte A.mar Risqué</h3>
      <p>Nova cor de esmalte da nossa amada linha de esmaltes! Alta durabilidade, brilho intenso e secagem rápida <br> R$3,50</p>
      <button>Comprar</button>
    </div>
  </div>
  
  <div id="popup2" class="popup">
    <div class="popup-content">
      <a href="#fechar" class="close">&times;</a>
      <img src="img/esmalteescarlate.png" alt="Esmalte Escarlate Risqué" class="popup-image">
      <h3>Esmalte Escarlate Risqué</h3>
      <p>Nosso querido vermelho escarlate! Alta durabilidade, brilho intenso e secagem rápida <br> R$3,00</p>
      <button>Comprar</button>
    </div>
  </div>
  
    <div id="popup3" class="popup">
    <div class="popup-content">
      <a href="#fechar" class="close">&times;</a>
      <img src="img/esmaltesoutopping.png" alt="Esmalte Sou Topping Risqué" class="popup-image">
      <h3>Esmalte Sou Topping Risqué</h3>
      <p>Aquele brilho para arrasar! Alta durabilidade, brilho intenso e secagem rápida <br> R$4,00</p>
      <button>Comprar</button>
    </div>
  </div>
  
  <div id="popup4" class="popup">
    <div class="popup-content">
      <a href="#fechar" class="close">&times;</a>
      <img src="img/esmalteanonovonorio.png" alt="Esmalte Ano Novo no Rio Risqué" class="popup-image">
      <h3>Esmalte Ano Novo no Rio Risqué</h3>
      <p>Aquele brilho que te faz suspirar! Alta durabilidade, brilho intenso e secagem rápida <br> R$7,00</p>
      <button>Comprar</button>
    </div>
  </div>
  
  <div id="popup5" class="popup">
    <div class="popup-content">
      <a href="#fechar" class="close">&times;</a>
      <img src="img/esmalteolhogrego.png" alt="Esmalte Olho Grego Dailus" class="popup-image">
      <h3>Esmalte Olho Grego Dailus</h3>
      <p>Aquele azul que não mancha! Alta durabilidade, brilho intenso e secagem rápida <br> R$5,50</p>
      <button>Comprar</button>
    </div>
  </div>
  
  <div id="popup6" class="popup">
    <div class="popup-content">
      <a href="#fechar" class="close">&times;</a>
      <img src="img/esmaltetachovendofini.png" alt="Esmalte A.mar Risqué" class="popup-image">
      <h3>Esmalte Tá Chovendo Fini Colorama</h3>
      <p>A coleção com cheirinho de fini chegou! Alta durabilidade, brilho intenso e secagem rápida <br> R$4,50</p>
      <button>Comprar</button>
    </div>
  </div>
  
  <div id="popup7" class="popup">
    <div class="popup-content">
        <a href="#fechar" class="close">&times;</a>
        <img src="img/esmalteimensidao.png" alt="Esmalte Imensidão Impala" class="popup-image">
        <h3>Esmalte Imensidão Impala</h3>
        <p>Para quem curte aquele tom de verde maravilhoso. Alta durabilidade, brilho intenso e secagem rápida <br> R$3,00</p>
        <button>Comprar</button>
    </div>
 </div>

 <div id="popup8" class="popup">
   <div class="popup-content">
      <a href="#fechar" class="close">&times;</a>
      <img src="img/esmaltedizeres.png" alt="Esmalte Dizeres Impala" class="popup-image">
      <h3>Esmalte Dizeres Impala</h3>
      <p>A combinação linda do vinho cintilante. Alta durabilidade, brilho intenso e secagem rápida <br> R$3,50</p>
      <button>Comprar</button>
  </div>
 </div>
  
 <div id="popup9" class="popup">
   <div class="popup-content">
      <a href="#fechar" class="close">&times;</a>
      <img src="img/esmaltecaricia.png" alt="Esmalte Carícia Impala" class="popup-image">
      <h3>Esmalte Carícia Impala</h3>
      <p>O perolado maravilhoso! Alta durabilidade, brilho intenso e secagem rápida <br> R$3,00</p>
      <button>Comprar</button>
   </div>
 </div>
  
  <div id="popup10" class="popup">
   <div class="popup-content">
      <a href="#fechar" class="close">&times;</a>
      <img src="img/esmaltelaçadaperfeita.png" alt="Esmalte Laçada Perfeita Impala" class="popup-image">
      <h3>Esmalte Laçada Perfeita Impala</h3>
      <p>A boiadeira chegou, bebê! Alta durabilidade, brilho intenso e secagem rápida <br> R$3,50</p>
      <button>Comprar</button>
   </div>
  </div>
  
  <div id="popup11" class="popup">
    <div class="popup-content">
      <a href="#fechar" class="close">&times;</a>
      <img src="img/esmaltevermelhaco.png" alt="Esmalte Vermelhaço Avon" class="popup-image">
      <h3>Esmalte Vermelhaço Avon</h3>
      <p>Para os amantes de vermelho! Alta durabilidade, brilho intenso e secagem rápida <br> R$7,00</p>
      <button>Comprar</button>
    </div>
  </div>
  
  
  <div id="popup12" class="popup">
    <div class="popup-content">
        <a href="#fechar" class="close">&times;</a>
        <img src="img/esmalteazulliberdade.png" alt="Esmalte Azul Liberdade Avon" class="popup-image">
        <h3>Esmalte Azul Liberdade Avon</h3>
        <p>A cor que te valoriza! Alta durabilidade, brilho intenso e secagem rápida <br> R$7,00</p>
        <button>Comprar</button>
    </div>
  </div>

  <div id="popup13" class="popup">
    <div class="popup-content">
        <a href="#fechar" class="close">&times;</a>
        <img src="img/esmaltesana.png" alt="Coleção Ana Hickman Estrelas da Ana" class="popup-image">
        <h3>Coleção Ana Hickman Estrelas da Ana</h3>
        <p>A cor ideal para você! Alta durabilidade, brilho intenso e secagem rápida <br> R$40,00</p>
        <button>Comprar</button>
    </div>
  </div>
  <div id="popup14" class="popup">
    <div class="popup-content">
        <a href="#fechar" class="close">&times;</a>
        <img src="img/esmalteshits.png" alt="Coleção Hits Diamante" class="popup-image">
        <h3>Coleção Hits Diamante</h3>
        <p>Os melhores glitters! Alta durabilidade, brilho intenso e secagem rápida <br> R$7,00</p>
        <button>Comprar</button>
    </div>
  </div>
  <div id="popup15" class="popup">
    <div class="popup-content">
        <a href="#fechar" class="close">&times;</a>
        <img src="img/esmaltesanita.png" alt="Coleção Anita Capadócia" class="popup-image">
        <h3>Coleção Anita Capadócia</h3>
        <p>Todos os tons! Alta durabilidade, brilho intenso e secagem rápida <br> R$7,00</p>
        <button>Comprar</button>
    </div>
  </div>
</div>

<section class="cadastrese"id="cadastro">
  <h2>Faça seu cadastro para ficar por dentro das promoções!</h2>
  <form>
      <input type="email" placeholder="Digite seu email" required>
      <button type="submit">Cadastrar</button>
  </form>
</section>

  <footer>
    <div class="footer-content">
        <div class="footer-info">
            <div class="footer-about">
                <h3>Sobre Nós</h3>
                <p>A Loja de Esmaltes oferece uma ampla variedade de cores e acabamentos para todos os gostos. Nossa missão é proporcionar beleza e qualidade em cada vidrinho de esmalte.</p>
            </div>
        </div>
        <div class="footer-meio">
          <h3>Nossos Produtos</h3>
          <p>Explore nossa linha de esmaltes com diferentes acabamentos: cremoso, glitter, fosco, perolado e muito mais!</p>
      </div>
        <div class="footer-contato">
            <h3>Contato</h3>
            <p>Email: contato@mundogica.com.br</p>
            <p>Telefone: (16) 4002-8922</p>
        </div>
    </div>
    <div class="footer-fim">
        <p>&copy; 2025 E-commerce de Esmaltes. Todos os direitos reservados.</p>
        <div class="social-icons">
            <a href="#"><img src="img/logofacebook.png" alt="Facebook"></a>
            <a href="#"><img src="img/logoinstagram.png" alt="Instagram"></a>
            <a href="#"><img src="img/logox.png"X"></a>
        </div>
    </div>
</footer> 

<script>
  function confirmPurchase() {
      alert('Obrigado pela sua compra!');
  }
</script> 

</body>
</html>

